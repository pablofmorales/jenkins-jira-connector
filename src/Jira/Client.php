<?php
namespace Jira;

use GuzzleHttp\Client as HttpClient;

class Client
{
    
    private $client;
    private $config;

    /**
     * Client constructor.
     * @param HttpClient $client
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
        $response = $this->client->request('GET', $url, $this->getOptions($option));
        return json_decode($response->getBody(), true);
    }

    /**
     * @param $url
     * @param array $option
     * @return mixed
     */
    public function post($url, $option = [])
    {
        return $this->client->request(
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
        $option['auth'] = [$this->config['username'], $this->config['password']];
        return $option;
    }

}