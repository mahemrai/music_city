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
     * @param  object $data
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
}