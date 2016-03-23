<?php
namespace MusicCity\Http\Controllers\App;

use Redis;
use MusicCity\Http\Controllers\Controller;
use MusicCity\Services\Clients\DiscogsClient;
use MusicCity\Artist;
use MusicCity\Album;
use MusicCity\Track;
use MusicCity\Repositories\AlbumRepository;

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
     * @var \MusicCity\Repositories\AlbumRepository
     */
    protected $albumRepository;

    /**
     * @param \MusicCity\Services\Clients\Discogs\Client $client
     * @param \MusicCity\Repositories\AlbumRepository $albumRepository
     */
    public function __construct(DiscogsClient $client, AlbumRepository $albumRepository)
    {
        $this->client = $client;
        $this->albumRepository = $albumRepository;
    }

    /**
     * @param  int $artistId
     * @param  int $albumId
     * @return \Illuminate\Http\Response
     */
    public function addAlbum($artistId, $albumId)
    {
        $artist = Artist::where('discogsId', $artistId)->first();
        $data = Redis::get('info:album:' . $albumId);
        if (is_null($data)) {
            $data = $this->client->getReleaseInfo((int) $albumId);
            (!$data) ?: Redis::set('info:album:' . $albumId, $data);
        }

        $data = json_decode($data);

        $result = $this->albumRepository->create($data, $artist->id);
        if (!$result || count($result) == 0) {
            return redirect('/')->with('fail', 'Album could not be added. Try again.');
        }

        return redirect('/')->with('success', 'Album added successfully');
    }
}