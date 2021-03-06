<?php
namespace MusicCity;

use Illuminate\Database\Eloquent\Model;

/**
 * @package MusicCity
 * @uses    \Illuminate\Database\Eloquent\Model
 * @author  Mahendra Rai
 */
class Album extends Model
{
    /**
     * @var array
     */
    protected $fillable = array(
        'discogsId',
        'artist_id',
        'title',
        'year',
        'image',
        'thumb',
        'rating'
    );

    /**
     * @return boolean
     */
    public static function boot()
    {
        parent::boot();

        Album::deleting(function ($album) {
            $album->tracks->each(function ($track) {
                if (!$track->delete()) {
                    return false;
                }
            });
            return true;
        });
    }

    /**
     * @return \MusicCity\Artist
     */
    public function artist()
    {
        return $this->belongsTo('MusicCity\Artist');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function tracks()
    {
        return $this->hasMany('MusicCity\Track');
    }
}