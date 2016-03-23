<?php
namespace MusicCity\Repositories;

use MusicCity\Album;
use MusicCity\Track;

/**
 * @package MusicCity
 * @uses    RepositoryInterface
 * @author  Mahendra Rai
 */
class AlbumRepository implements RepositoryInterface
{
    /**
     * @param  object $data
     * @param  int $id
     * @return boolean
     */
    public function create($data, $id = null)
    {
        $album = new Album(array(
            'discogsId' => $data->id,
            'artist_id' => $id,
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

            return $album->tracks()->saveMany($tracks);
        }

        return false;
    }

    /**
     * @param  int $albumId
     * @return boolean
     */
    public function remove($albumId)
    {
        $album = Album::find($albumId);
        return $album->delete();
    }
}