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
     * @param  int $id
     * @return \MusicCity\Album
     */
    public function getById($id)
    {
        return Album::find($id);
    }

    /**
     * @param  int $value
     * @param  string $field
     * @return \MusicCity\Album
     */
    public function getByField($value, $field)
    {
        return Album::where($field, $value)->first();
    }

    /**
     * @param  string|null $field
     * @param  string|null $orderOption
     * @param  int|null $limit
     * @param  boolean $paginate
     * @return mixed
     */
    public function getSorted($field = null, $orderOption = null, $limit = null, $paginate = false)
    {
        if (is_null($limit)) {
            return Album::orderBy($field, $orderOption)->get();
        }

        if ($paginate) {
            return Album::orderBy($field, $orderOption)->paginate($limit);
        }

        return Album::orderBy($field, $orderOption)->take($limit)->get();
    }

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