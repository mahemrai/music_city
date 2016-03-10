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
    protected $consumerKey;
    protected $consumerSecret;
    protected $serviceUrl = 'https://api.discogs.com/';
    protected $client;

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
     * @param string $artistId
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
     * @param string $artistId
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
     * @param string $releaseId
     */
    public function getReleaseInfo($releaseId)
    {
        $params = http_build_query(array(
            'key' => $this->consumerKey,
            'secret' => $this->consumerSecret
        ));
        $query = $this->serviceUrl . 'releases/' . $releaseId . '?' . $params;
        
        try {
            $response = $this->get($query);
            return $response->getBody()->getContents();
        } catch (ClientException $e) {
            return false;
        }
    }

    /**
     * @param string $artist
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