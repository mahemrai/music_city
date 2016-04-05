<?php
namespace MusicCity\Http\Controllers\App;

use Cache;

use MusicCity\Http\Controllers\Controller;
use MusicCity\Services\Clients\DiscogsClient;
use MusicCity\Services\App\ListGenerator;
use MusicCity\Repositories\ArtistRepository;
use MusicCity\Repositories\AlbumRepository;

use Illuminate\Http\Request;

/**
 * @package MusicCity
 * @uses    \MusicCity\Http\Controllers\Controller
 * @author  Mahendra Rai
 */
class AppController extends Controller
{
    /**
     * @var \MusicCity\Services\Clients\DiscogsClient
     */
    protected $client;

    /**
     * @var \MusicCity\Services\App\ListGenerator
     */
    protected $listGenerator;

    /**
     * @var \MusicCity\Repositories\ArtistRepository
     */
    protected $artistRepository;

    /**
     * @var \MusicCity\Repositories\AlbumRepository
     */
    protected $albumRepository;

    /**
     * @param \MusicCity\Services\Clients\DiscogsClient $client
     * @param \MusicCity\Services\App\ListGenerator $listGenerator
     * @param \MusicCity\Repositories\ArtistRepository $artistRepository
     * @param \MusicCity\Repositories\AlbumRepository $albumRepository
     */
    public function __construct(DiscogsClient $client, ListGenerator $listGenerator, ArtistRepository $artistRepository, AlbumRepository $albumRepository)
    {
        $this->client = $client;
        $this->listGenerator = $listGenerator;
        $this->artistRepository = $artistRepository;
        $this->albumRepository = $albumRepository;
    }

    /**
     * @return \MusicCity\Http\Response
     */
    public function homePage()
    {
        $artists = $this->artistRepository->getSorted('id', 'desc', 4);
        $albums = $this->albumRepository->getSorted('id', 'desc', 4);
        $data = array(
            'recentArtists' => $artists,
            'recentRecords' => $albums
        );
        return view('app.homepage')->with('data', $data);
    }

    /**
     * @param  \MusicCity\Http\Request $request
     * @return \MusicCity\Http\Response
     */
    public function results(Request $request)
    {
        $this->validate($request, array(
            'artist' => 'required'
        ));

        $data = Cache::get('search:artist:' . $request->artist);
        if (is_null($data)) {
            $data = $this->client->search($request->artist, 'artist');
            (!$data) ?: Cache::put('search:artist:' . $request->artist, $data, 60);
        }

        return view('app.results')->with('data', json_decode($data));
    }

    /**
     * @param  int $artistId
     * @return \Illuminate\Http\Response
     */
    public function findAlbums($artistId)
    {
        $data = Cache::get('albums:artist:' . $artistId);
        if (is_null($data)) {
            $data = $this->client->getArtistReleases((int) $artistId);
            (!$data) ?: Cache::put('albums:artist:' . $artistId, $data, 60);
        }

        $data = array(
            'artist' => $artistId,
            'album'  => json_decode($data)
        );
        
        return view('app.results')->with('data', $data);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function listArtists()
    {
        $artists = $this->artistRepository->getSorted('name', 'asc');
        $list = $this->listGenerator->createAlphabeticalList($artists, 'name');
        return view('app.artists')->with('list', $list);
    }

    /**
     * @return \Illumiante\Http\Response
     */
    public function listAlbums()
    {
        $albums = $this->albumRepository->getSorted('title', 'asc', 20);
        return view('app.albums')->with('albums', $albums);
    }
}