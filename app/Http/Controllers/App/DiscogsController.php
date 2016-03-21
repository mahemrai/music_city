<?php
namespace MusicCity\Http\Controllers\App;

use Redis;
use Illuminate\Http\Request;
use MusicCity\Http\Controllers\Controller;
use MusicCity\Services\Clients\DiscogsClient;

class DiscogsController extends Controller
{
    /**
     * @var \MusicCity\Services\Clients\DiscogsClient
     */
    protected $client;

    /**
     * @param \MusicCity\Services\Clients\DiscogsClient $client
     */
    public function __construct(DiscogsClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $artist
     */
    public function searchArtist(Request $request)
    {
        $this->validate($request, array(
            'artist' => 'required'
        ));

        $data = Redis::get('search:artist:' . $request->artist);
        if (is_null($data)) {
            $data = $this->client->search($request->artist, 'artist');
            (!$data) ?: Redis::set('search:artist:' . $request->artist, $data);
        }
        
        return redirect()->action('App\AppController@results', ['data' => json_decode($data)]);
    }

    /**
     * @param string $artistId
     */
    public function fetchArtist($artistId)
    {
        $data = Redis::get('info:artist:' . $artistId);
        if (is_null($data)) {
            $data = $this->client->getArtistInfo((int) $artistId);
            (!$data) ?: Redis::set('info:artist:' . $artistId, $data);
        }
        echo $data;
    }

    /**
     * @param string $artistId
     */
    public function fetchAlbums($artistId)
    {
        $data = Redis::get('albums:artist:' . $artistId);
        if (is_null($data)) {
            $data = $this->client->getArtistReleases((int) $artistId);
            (!$data) ?: Redis::set('albums:artist:' . $artistId, $data);
        }
        echo $data;
    }

    /**
     * @param string $albumId
     */
    public function fetchAlbum($albumId)
    {
        $data = Redis::get('info:album:' . $albumId);
        if (is_null($data)) {
            $data = $this->client->getReleaseInfo((int) $albumId);
            (!$data) ?: Redis::set('info:album:' . $albumId, $data);
        }
        echo $data;
    }
}