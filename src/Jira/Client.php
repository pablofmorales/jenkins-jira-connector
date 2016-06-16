<?php
namespace CD\Jira;

class Client
{
    
    private $options;
    private $client;

    /**
     * Client constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->client = new \GuzzleHttp\Client([
           'base_uri' => $config['host'],
        ]);
        
        $this->options = ['auth' => [$config['username'], $config['password']]];
    }

    /**
     * @param $issue
     * @return mixed
     */
    public function getIssue($issue)
    {
        $res = $this->client->request(
            'GET', '/rest/api/2/issue/' . $issue, $this->options
        );
        return json_decode($res->getBody(), true);
    }

    /**
     * @param $issue
     * @return mixed
     */
    public function getTransitions($issue)
    {
        $res = $this->client->request(
            'GET', '/rest/api/2/issue/' . $issue . '/transitions', $this->options
        );
        return json_decode($res->getBody(), true);
    }

    /**
     * @param $issue
     * @param $params
     */
    public function updateTransitions($issue, $params)
    {

        $options = $this->options;
        $options['json'] = json_decode($params); 

        $this->client->request(
            'post', '/rest/api/2/issue/' . $issue . '/transitions', $options
        );        
        
    }

    /**
     * @param $issue
     * @param $params
     */
    public function createRemoteLink($issue, $params)
    {

        $options = $this->options;
        $options['json'] = json_decode($params); 

        $this->client->request(
            'post', '/rest/api/2/issue/' . $issue . '/remotelink', $options
        );        
        
    }
    
}