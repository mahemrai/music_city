<?php
namespace MusicCity\Http\Controllers\App;

use Cache;

use MusicCity\Http\Controllers\Controller;
use MusicCity\Services\Clients\DiscogsClient;
use MusicCity\Repositories\AlbumRepository;
use MusicCity\Repositories\ArtistRepository;

use Illuminate\Http\Request;

/**
 * @package MusicCity
 * @uses    \MusicCity\Http\Controllers\Controller
 * @author  Mahendra Rai
 */
class AlbumController extends Controller
{
    /**
     * @var \MusicCity\Services\Clients\Discogs\Client
     */
    protected $client;

    /**
     * @var \MusicCity\Repositories\ArtistRepository
     */
    protected $artistRepository;

    /**
     * @var \MusicCity\Repositories\AlbumRepository
     */
    protected $albumRepository;

    /**
     * @param \MusicCity\Services\Clients\Discogs\Client $client
     * @param \MusicCity\Repositories\ArtistRepository $artistRepository
     * @param \MusicCity\Repositories\AlbumRepository $albumRepository
     */
    public function __construct(DiscogsClient $client, ArtistRepository $artistRepository, AlbumRepository $albumRepository)
    {
        $this->client = $client;
        $this->artistRepository = $artistRepository;
        $this->albumRepository = $albumRepository;
    }

    /**
     * Add selected album into the database.
     * 
     * @param  int $artistId
     * @param  int $albumId
     * @return \Illuminate\Http\Response
     */
    public function addAlbum($artistId, $albumId)
    {
        // get artist info
        $artist = $this->artistRepository->getByField($artistId, 'discogsId');
        // fetch cached data
        $data = Cache::get('info:album:' . $albumId);
        // if the album is not cached then retrieve from an external service
        if (is_null($data)) {
            $data = $this->client->getReleaseInfo((int) $albumId);
            // cache data if it was successfully retrieved
            (!$data) ?: Cache::put('info:album:' . $albumId, $data, 60);
        }

        $data = json_decode($data);

        // add album into the database
        $result = $this->albumRepository->create($data, $artist->id);
        if (!$result || count($result) == 0) {
            return redirect('/')->with('fail', 'Album could not be added. Try again.');
        }

        return redirect('/')->with('success', 'Album added successfully');
    }

    /**
     * @param  int $artistId
     * @param  int $albumId
     * @return \Illuminate\Http\Response
     */
    public function albumInfo($artistId, $albumId)
    {
        $album = $this->albumRepository->getById($albumId);
        return view('album.info')->with('data', $album);
    }

    /**
     * @param  int $albumId
     * @return \Illuminate\Http\Response
     */
    public function deleteAlbum($albumId)
    {
        if ($this->albumRepository->remove($albumId)) {
            return redirect('/')->with('success', 'Album successfully deleted.');
        }
    }
}