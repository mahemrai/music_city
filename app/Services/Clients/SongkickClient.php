<?php
namespace MusicCity\Services\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ClientException;

/**
 * @package MusicCity
 * @uses    \GuzzleHttp\Client
 * @author  Mahendra Rai
 */
class SongkickClient extends Client
{
    /**
     * @var string
     */
    protected $apiKey;

    protected $serviceUrl = 'http://api.songkick.com/api/3.0/';

    public function __construct($apiKey)
    {
        parent::__construct(array(
            'base_uri' => $this->serviceUrl,
            'timeout'  => 10.0
        ));

        $this->apiKey = $apiKey;
    }

    public function getArtistEvents($artist)
    {
        $params = http_build_query(array(
            'apikey'       => $this->apiKey,
            'artist_name'  => $artist
        ));
        $query = $this->serviceUrl . 'events.json?' . $params;

        try {
            $response = $this->get($query);
            return $response->getBody()->getContents();
        } catch (ClientException $e) {
            return false;
        }
    }
}