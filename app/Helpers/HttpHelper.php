<?php
namespace App\Helpers;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
/**
* Class to handle all RESTful requests
*/
class HttpHelper
{
    private static $singletonObj = null;

    private $guzzle;
    private $un;
    private $pw;

    public static function getInstance() {

        if (self::$singletonObj !== null) {
            return self::$singletonObj;
        }

        self::$singletonObj = new self();
        return self::$singletonObj;
    }

    /**
    * HttpHelper constructor.
    */
    private function __construct()
    {
        $this->guzzle = new Client(['base_uri' => getenv("API_URL")]);
        $this->un = getenv("API_USERNAME");
        $this->pw = getenv("API_PASSWORD");
    }

    /**
     * @param $endpoint
     * @param $array - Array of data to be JSON encoded
     * @return mixed
     * @throws GuzzleException
     */
    public function post($endpoint, $array) {
        $response = $this->guzzle->post($this->cleanEndpoint($endpoint), [
            'headers' => [
                'Content-Type' => 'application/json-patch+json',
                'Accept' => 'application/json',
                'timeout' => 60,
                'Authorization' => 'Bearer '. session()->get('token')
            ],
            'json' => $array
        ]);

        return json_decode($response->getBody());
    }

    public function postNew($endpoint, $array) {
        $response = $this->guzzle->post($this->cleanEndpoint($endpoint), [
            'headers' => [
                'Content-Type' => 'application/json-patch+json',
                'Accept' => 'application/json',
                'timeout' => 60,
                'Authorization' => 'Bearer '. session()->get('token')
            ],
            'query' => $array
        ]);

        return json_decode($response->getBody());
    }

    /**
     * @param $endpoint
     * @param int $page
     * @return mixed
     * @throws GuzzleException
     */
    public function get($endpoint, $page = 1, $assoc = false) {
        $page = intval($page);
        $response = $this->guzzle->get($this->cleanEndpoint($endpoint) . "?page=$page", [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'timeout' => 60,
                'Authorization' => 'Bearer '. session()->get('token')
            ],
        ]);
        
        return json_decode($response->getBody(), $assoc);
    }

    public function get2($endpoint, $page = 1, $assoc = false) {
        $page = intval($page);
        $response = $this->guzzle->get($this->cleanEndpoint($endpoint), [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'timeout' => 60,
                'Authorization' => 'Bearer '. session()->get('token')
            ],
        ]);
        
        return json_decode($response->getBody(), $assoc);
    }

    /**
     * @param $endpoint
     * @param $array - Array of data to be JSON encoded
     * @return mixed
     * @throws GuzzleException
     */
    public function patch($endpoint, $array) {
        $response = $this->guzzle->patch($this->cleanEndpoint($endpoint), [
            'headers' => [
                'Content-Type' => 'application/json-patch+json',
                'Accept' => 'application/json',
                'timeout' => 10,
                'Authorization' => 'Bearer '. session()->get('token')
            ],
            'json' => $array
        ]);

        $body = json_decode($response->getBody());
        return $body->data;
    }

    /**
     * @param $endpoint
     * @return mixed
     * @throws GuzzleException
     */
    public function delete($endpoint) {
        $response = $this->guzzle->delete($this->cleanEndpoint($endpoint), [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'timeout' => 10,
                'Authorization' => 'Bearer '. session()->get('token')
            ],
        ]);
        $body = json_decode($response->getBody());
        return $body->data;
    }

    public function uploadFile($endpoint, $array) {
        try {
            $response = $this->guzzle->post($this->cleanEndpoint($endpoint), [
                'headers' => [
                    //'Content-Type' => 'text/plain',
                    //'Accept' => 'application/json',
                    'timeout' => 10,
                    'Authorization' => 'Bearer ' . session()->get('token')
                ],
                'multipart' =>  [$array]
            ]);
        } catch (GuzzleException $e) {
            return false;
        }
        return json_decode($response->getBody());
    }

    /**
    * Remove leading or trailing forward slashes from the endpoint.
    * @param $endpoint
    * @return string
    */
    private function cleanEndpoint($endpoint) {
        $endpoint = ltrim($endpoint,"/");
        $endpoint = rtrim($endpoint,"/");
        return $endpoint;
    }
}
