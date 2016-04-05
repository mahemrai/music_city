<?php
namespace MusicCity\Repositories;

use MusicCity\Artist;

/**
 * @package MusicCity
 * @uses    RepositoryInterface
 * @author  Mahendra Rai
 */
class ArtistRepository implements RepositoryInterface
{
    /**
     * @param  int $id
     * @return \MusicCity\Artist
     */
    public function getById($id)
    {
        return Artist::find($id);
    }

    /**
     * @param  int $value
     * @param  string $field
     * @return \MusicCity\Artist
     */
    public function getByField($value, $field)
    {
        return Artist::where($field, $value)->first();
    }

    /**
     * @param  string|null $field
     * @param  string|null $orderOption
     * @param  int|null $limit
     * @param  boolean $paginate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSorted($field = null, $orderOption = null, $limit = null, $paginate = false)
    {
        if (is_null($limit)) {
            return Artist::orderBy($field, $orderOption)->get();
        }

        if ($paginate) {
            return Artist::orderBy($field, $orderOption)->paginate($limit);
        }

        return Artist::orderBy($field, $orderOption)->take($limit)->get();
    }

    /**
     * @param  object $data
     * @param  int|null $id
     * @return boolean
     */
    public function create($data, $id = null)
    {
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
        return $artist->save();
    }

    /**
     * @param  int $artistId
     * @return boolean
     */
    public function remove($artistId)
    {
        $artist = Artist::find($artistId);
        return $artist->delete();
    }

    public function update($value, $field, $updateArray)
    {
        return Artist::where($field, $value)->update($updateArray);
    }
}