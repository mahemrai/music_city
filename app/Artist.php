<?php
namespace MusicCity;

use Illuminate\Database\Eloquent\Model;

/**
 * @package MusicCity
 * @uses    \Illuminate\Database\Eloquent\Model
 * @author  Mahendra Rai
 */
class Artist extends Model
{
    /**
     * @var array
     */
    protected $fillable = array(
        'discogsId', 
        'name', 
        'bio', 
        'website', 
        'wiki', 
        'image', 
        'thumb', 
        'members', 
        'favourite'
    );

    public static function boot()
    {
        parent::boot();

        Artist::deleting(function ($artist) {
            foreach ($artist->albums as $album) {
                $album->tracks->each(function ($track) {
                    if (!$track->delete()) {
                        return false;
                    }
                });
                if (!$album->delete()) {
                    return false;
                }
            }
            return true;
        });
    }

    /**
     * @return \MusicCity\Album
     */
    public function albums()
    {
        return $this->hasMany('MusicCity\Album');
    }

    /**
     * @param  array $membersArray
     * @return string
     */
    public function createMembersString($membersArray)
    {
        foreach ($membersArray as $member) {
            if ($member->active) {
                $members[] = $member->name;
            }
        }

        return implode(",", $members);
    }
}