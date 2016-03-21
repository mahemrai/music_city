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
class LastfmClient extends Client
{
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $serviceUrl = 'http://ws.audioscrobbler.com/2.0/?';

    /**
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        parent::__construct(array(
            'base_uri' => $this->serviceUrl,
            'timeout'  => 10.0
        ));

        $this->apiKey = $apiKey;
    }

    public function getSimilarArtists($artist)
    {
        $params = http_build_query(array(
            'method'  => 'artist.getsimilar',
            'artist'  => $artist,
            'api_key' => $this->apiKey,
            'format'  => 'json'
        ));
        $query = $this->serviceUrl . $params;

        try {
            $response = $this->get($query);
            return $response->getBody()->getContents();
        } catch (ClientException $e) {
            return false;
        }
    }
}