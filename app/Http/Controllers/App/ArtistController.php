<?php
namespace MusicCity\Http\Controllers\App;

use Redis;
use MusicCity\Http\Controllers\Controller;
use MusicCity\Services\Clients\DiscogsClient;
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
    protected $client;

    protected $artistRepository;

    /**
     * @param \MusicCity\Services\Clients\DiscogsClient $client
     */
    public function __construct(DiscogsClient $client, ArtistRepository $artistRepository)
    {
        $this->client = $client;
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
     * @param int $artistId
     */
    public function artistInfo($artistId)
    {
        $artist = Artist::find($artistId);
        return view('artist.info')->with('data', $artist);
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