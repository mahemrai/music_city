<?php
namespace MusicCity\Http\Controllers\App;

use Redis;

use MusicCity\Artist;
use MusicCity\Album;
use MusicCity\Http\Controllers\Controller;
use MusicCity\Services\Clients\DiscogsClient;
use MusicCity\Services\App\ListGenerator;

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
     * @param \MusicCity\Services\Clients\DiscogsClient $client
     * @param \MusicCity\Services\App\ListGenerator $listGenerator
     */
    public function __construct(DiscogsClient $client, ListGenerator $listGenerator)
    {
        $this->client = $client;
        $this->listGenerator = $listGenerator;
    }

    /**
     * @return \MusicCity\Http\Response
     */
    public function homePage()
    {
        $artists = Artist::orderBy('id', 'desc')->take(4)->get();
        $albums = Album::orderBy('id', 'desc')->take(4)->get();
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

        $data = Redis::get('search:artist:' . $request->artist);
        if (is_null($data)) {
            $data = $this->client->search($request->artist, 'artist');
            (!$data) ?: Redis::set('search:artist:' . $request->artist, $data);
        }

        return view('app.results')->with('data', json_decode($data));
    }

    /**
     * @param  int $artistId
     * @return \Illuminate\Http\Response
     */
    public function findAlbums($artistId)
    {
        $data = Redis::get('albums:artist:' . $artistId);
        if (is_null($data)) {
            $data = $this->client->getArtistReleases((int) $artistId);
            (!$data) ?: Redis::set('albums:artist:' . $artistId, $data);
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
        $artists = Artist::orderBy('name', 'asc')->get();
        $list = $this->listGenerator->createAlphabeticalList($artists, 'name');
        return view('app.list')->with('list', $list);
    }
}