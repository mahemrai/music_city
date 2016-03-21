<?php
namespace MusicCity\Http\Controllers\App;

use Redis;
use MusicCity\Http\Controllers\Controller;
use MusicCity\Services\Clients\DiscogsClient;
use MusicCity\Artist;

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

    /**
     * @param \MusicCity\Services\Clients\DiscogsClient $client
     */
    public function __construct(DiscogsClient $client)
    {
        $this->client = $client;
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

        $artist = new Artist();
        $artist->discogsId = $data->id;
        $artist->name = $data->name;
        $artist->bio = $data->profile;
        $artist->website = $data->urls[0];
        $artist->image = $data->images[0]->uri;
        $artist->thumb = $data->images[0]->uri150;
        if (isset($data->members)) {
            $artist->members = $artist->createMembersString($data->members);
        }
        $artist->favourite = 0;
        
        if ($artist->save()) {
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

    public function deleteArtist($artistId)
    {
        $artist = Artist::find($artistId);
        foreach ($artist->albums as $album) {
            $album->tracks()->delete();
        }
        $artist->albums()->delete();
        $artist->delete();
    }
}