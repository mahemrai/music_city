<?php
namespace MusicCity\Http\Controllers\App;

use Cache;

use MusicCity\Http\Controllers\Controller;
use MusicCity\Services\Clients\DiscogsClient;
use MusicCity\Services\Clients\LastfmClient;
use MusicCity\Services\Clients\SongkickClient;
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
        $data = Cache::get('info:artist:' . $artistId);
        if (is_null($data)) {
            $data = $this->discogsClient->getArtistInfo((int) $artistId);
            (!$data) ?: Cache::put('info:artist:' . $artistId, $data, 60);
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
        $artist = $this->artistRepository->getById($artistId);

        $similarArtists = Cache::get('similar:artist:' . $artistId);
        if (is_null($similarArtists)) {
            $similarArtists = $this->lastfmClient->getSimilarArtists($artist->name);
            (!$similarArtists) ?: Cache::put('similar:artist:' . $artistId, $similarArtists, 60);
        }

        $events = $this->songkickClient->getArtistEvents($artist->name);
        $similar = json_decode($similarArtists);
        $events = (json_decode($events)->resultsPage->totalEntries == 0) ? 
                    '' : json_decode($events)->resultsPage->results->event;

        $data = array(
            'artist'  => $artist,
            'similar' => array_slice($similar->similarartists->artist, 0, 9),    //return only 10 items from the array
            'events'  => $events
        );
        return view('artist.info')->with('data', $data);
    }

    /**
     * @param  int $artistId
     * @return \Illuminate\Http\Response
     */
    public function deleteArtist($artistId)
    {
        if ($this->artistRepository->remove($artistId)) {
            return redirect('/')->with('success', 'Artist successfully deleted.');
        }
    }
}