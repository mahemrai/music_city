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
class DiscogsClient extends Client
{
    /**
     * @var string
     */
    protected $consumerKey;

    /**
     * @var string
     */
    protected $consumerSecret;

    /**
     * @var string
     */
    protected $serviceUrl = 'https://api.discogs.com/';

    /**
     * @param string $consumerKey
     * @param string $consumerSecret
     */
    public function __construct($consumerKey, $consumerSecret)
    {
        parent::__construct(array(
            'base_uri' => $this->serviceUrl,
            'timeout'  => 10.0
        ));

        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
    }

    /**
     * @param  int $artistId
     * @return mixed
     */
    public function getArtistInfo($artistId)
    {
        $params = http_build_query(array(
            'key'    => $this->consumerKey,
            'secret' => $this->consumerSecret
        ));
        $query = $this->serviceUrl . 'artists/' . $artistId . '?' . $params;

        try {
            $response = $this->get($query);
            return $response->getBody()->getContents();
        } catch (ClientException $e) {
            return false;
        }
    }

    /**
     * @param  int $artistId
     * @return string
     */
    public function getArtistReleases($artistId)
    {
        $params = http_build_query(array(
            'key'        => $this->consumerKey,
            'secret'     => $this->consumerSecret,
            'sort'       => 'year',
            'sort_order' => 'asc'
        ));
        $query = $this->serviceUrl . 'artists/' . $artistId . '/releases?' . $params;

        try {
            $response = $this->get($query);
            return $response->getBody()->getContents();
        } catch (ClientException $e) {
            return false;
        }
    }

    /**
     * @param  int $releaseId
     * @return mixed
     */
    public function getReleaseInfo($releaseId)
    {
        $params = http_build_query(array(
            'key' => $this->consumerKey,
            'secret' => $this->consumerSecret
        ));
        $query = $this->serviceUrl . 'masters/' . $releaseId . '?' . $params;
        
        try {
            $response = $this->get($query);
            return $response->getBody()->getContents();
        } catch (ClientException $e) {
            return false;
        }
    }

    /**
     * @param  string $artist
     * @param  string $type
     * @return mixed
     */
    public function search($artist, $type)
    {
        $params = http_build_query(array(
            'q'      => $artist,
            'type'   => $type,
            'key'    => $this->consumerKey,
            'secret' => $this->consumerSecret
        ));
        $query = $this->serviceUrl . 'database/search?' . $params;

        try {
            $response = $this->get($query);
            return $response->getBody()->getContents();
        } catch (ClientException $e) {
            return false;
        }
    }
}