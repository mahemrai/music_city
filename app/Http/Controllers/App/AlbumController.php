<?php
namespace MusicCity\Http\Controllers\App;

use Redis;
use MusicCity\Http\Controllers\Controller;
use MusicCity\Services\Clients\DiscogsClient;
use MusicCity\Artist;
use MusicCity\Album;
use MusicCity\Track;

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
     * @param \MusicCity\Services\Clients\Discogs\Client $client
     */
    public function __construct(DiscogsClient $client)
    {
        $this->client = $client;
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

        $album = new Album(array(
            'discogsId' => $albumId,
            'artist_id' => $artist->id,
            'title'     => $data->title,
            'year'      => $data->year,
            'image'     => $data->images[0]->uri,
            'thumb'     => $data->images[0]->uri150
        ));

        if ($album->save()) {
            foreach ($data->tracklist as $track) {
                $track = new Track(array('title' => $track->title));
                $tracks[] = $track;
            }

            $album->tracks()->saveMany($tracks);

            return redirect('/')->with('success', 'Album added successfully.');
        }

        return redirect('/')->with('fail', 'Album could not be added. Try again.');
    }
}