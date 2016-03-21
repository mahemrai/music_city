<?php
namespace MusicCity\Http\Controllers\App;

use Redis;
use MusicCity\Http\Controllers\Controller;
use MusicCity\Services\Clients\LastfmClient;

class LastfmController extends Controller
{
    protected $client;

    public function __construct(LastfmClient $client)
    {
        $this->client = $client;
    }

    public function similarArtists($artist)
    {
        $data = Redis::get('similar:artist:' . $artist);
        if (is_null($data)) {
            $data = $this->client->getSimilarArtists($artist);
            (!$data) ?: Redis::set('similar:artist:' . $artist, $data);
        }
        echo $data;
    }
}