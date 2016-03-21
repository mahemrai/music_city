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