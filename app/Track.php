<?php
namespace MusicCity;

use Illuminate\Database\Eloquent\Model;

/**
 * @package MusicCity
 * @uses    \Illuminate\Database\Eloquent\Model
 * @author  Mahendra Rai
 */
class Track extends Model
{
    /**
     * @var array
     */
    protected $fillable = array(
        'albumId',
        'title'
    );
}