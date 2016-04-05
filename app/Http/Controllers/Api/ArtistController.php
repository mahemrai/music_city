<?php
namespace MusicCity\Http\Controllers\Api;

use MusicCity\Http\Controllers\Controller;
use MusicCity\Repositories\ArtistRepository;

use Illuminate\Http\Request;

/**
 * @package MusicCity
 * @uses    \MusicCity\Http\Controllers\Controller
 * @author  Mahendra Rai
 */
class ArtistController extends Controller
{
    /**
     * @var \MusicCity\Repositories\ArtistRepository
     */
    protected $artistRepository;

    public function __construct(ArtistRepository $artistRepository)
    {
        $this->artistRepository = $artistRepository;
    }

    public function markAsFavourite($artistId, Request $request)
    {
        $result = $this->artistRepository->update($artistId, 'id', array('favourite' => true));
        return response()->json(array(
            'result' => $result
        ));
    }
}