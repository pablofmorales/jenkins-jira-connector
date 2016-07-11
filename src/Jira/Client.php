<?php
namespace Jira;

use GuzzleHttp\Client as HttpClient;

class Client
{
    
    private $client;
    private $config;

    /**
     * Client constructor.
     * @param $config
     */
    public function __construct(HttpClient $client, $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * @param $url
     * @param array $option
     * @return mixed
     */
    public function get($url, $option = [])
    {
        $response = $this->client('GET', $url, $this->getOptions($option));
        return json_decode($response->getBody(), true);
    }

    /**
     * @param $url
     * @param array $option
     * @return mixed
     */
    public function post($url, $option = [])
    {
        return $this->client(
            'POST', $url, $this->getOptions($option)
        );
    }

    /**
     * @param array $option
     * @return array
     */
    private function getOptions($option = [])
    {

        $option['base_uri'] = $this->config['host'];
        
        return $option;
    }

}