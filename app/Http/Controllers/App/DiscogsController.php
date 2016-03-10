<?php
namespace MusicCity\Http\Controllers\App;

use MusicCity\Http\Controllers\Controller;
use MusicCity\Services\Clients\DiscogsClient;

class DiscogsController extends Controller
{
    protected $client;

    /**
     * @param \MusicCity\Services\Clients\DiscogsClient $client
     */
    public function __construct(DiscogsClient $client)
    {
        $this->client = $client;
    }

    public function searchArtist($artist)
    {
        echo $this->client->search($artist, 'artist');
    }

    public function fetchArtist($artistId)
    {
        echo $this->client->getArtistInfo($artistId);
    }

    public function fetchAlbums($artistId)
    {
        echo $this->client->getArtistReleases($artistId);
    }

    public function fetchAlbum($albumId)
    {
        echo $this->client->getReleaseInfo($albumId);
    }
}