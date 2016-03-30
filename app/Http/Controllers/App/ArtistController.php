<?php
namespace MusicCity\Http\Controllers\App;

use Redis;
use MusicCity\Http\Controllers\Controller;
use MusicCity\Services\Clients\DiscogsClient;
use MusicCity\Services\Clients\LastfmClient;
use MusicCity\Services\Clients\SongkickClient;
use MusicCity\Artist;
use MusicCity\Repositories\ArtistRepository;

use Illuminate\Http\Request;

/**
 * @package MusicCity
 * @uses    \MusicCity\Http\Controllers\Controller
 * @author  Mahendra Rai
 */
class ArtistController extends Controller
{
    /**
     * @var \MusicCity\Services\Clients\DiscogsClient
     */
    protected $discogsClient;

    /**
     * @var \MusicCity\Services\Clients\LastfmClient
     */
    protected $lastfmClient;

    /**
     * @var \MusicCity\Services\Clients\SongkickClient
     */
    protected $songkickClient;

    /**
     * @var \MusicCity\Repositories\ArtistRepository
     */
    protected $artistRepository;

    /**
     * @param \MusicCity\Services\Clients\DiscogsClient $discogsClient
     * @param \MusicCity\Services\Clients\LastfmClient $lastfmClient
     * @param \MusicCity\Services\Clients\SongkickClient $songkickClient
     * @param \MusicCity\Repositories\ArtistRepository $artistRepository
     */
    public function __construct(
        DiscogsClient $discogsClient, 
        LastfmClient $lastfmClient, 
        SongkickClient $songkickClient, 
        ArtistRepository $artistRepository
    )
    {
        $this->discogsClient = $discogsClient;
        $this->lastfmClient = $lastfmClient;
        $this->songkickClient = $songkickClient;
        $this->artistRepository = $artistRepository;
    }

    /**
     * @param  int $artistId
     * @return \Illuminate\Http\Response
     */
    public function addArtist($artistId)
    {
        $data = Redis::get('info:artist:' . $artistId);
        if (is_null($data)) {
            $data = $this->client->getArtistInfo((int) $artistId);
            (!$data) ?: Redis::set('info:artist:' . $artistId, $data);
        }

        $data = json_decode($data);

        if ($this->artistRepository->create($data)) {
            return redirect('/')->with('success', 'Artist succesfully added.');
        }

        return redirect('/')->with('fail', 'Artist could not be added. Try again.');
    }

    /**
     * @param  int $artistId
     * @return \Illuminate\Http\Response
     */
    public function artistInfo($artistId)
    {
        $artist = Artist::find($artistId);

        $similarArtists = Redis::get('similar:artist:' . $artistId);
        if (is_null($similarArtists)) {
            $similarArtists = $this->lastfmClient->getSimilarArtists($artist->name);
            (!$similarArtists) ?: Redis::set('similar:artist:' . $artistId, $similarArtists);
        }

        $events = $this->songkickClient->getArtistEvents($artist->name);
        $data = array(
            'artist'  => $artist,
            'similar' => json_decode($similarArtists),
            'events'  => json_decode($events)->resultsPage
        );
        return view('artist.info')->with('data', $data);
    }

    /**
     * @param int $artistId
     */
    public function deleteArtist($artistId)
    {
        if ($this->artistRepository->remove($artistId)) {
            return redirect('/')->with('success', 'Artist successfully deleted.');
        }
    }
}