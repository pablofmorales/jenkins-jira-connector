<?php

namespace Jira\Resources;

use Jira\Client as JiraClient;

class Issues
{
    /**
     * @var JiraClient
     */
    private $client;

    /**
     * Issues constructor.
     * @param JiraClient $client
     */
    public function __construct(JiraClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param $issueId
     * @return mixed
     * @internal param $issue
     */
    public function get($issueId)
    {
        return $this->client->get(
            '/rest/api/2/issue/' . $issueId
        );
    }
    
    /**
     * @param $issue
     * @return mixed
     */
    public function getTransitions($issue)
    {
        return $this->client->get(
            '/rest/api/2/issue/' . $issue . '/transitions'
        );
    }

    /**
     * @param $issue
     * @param $params
     * @return mixed
     */
    public function updateTransitions($issue, $params)
    {
        $options = ['json' => json_decode($params)];

        return $this->client->post(
            '/rest/api/2/issue/' . $issue . '/transitions', $options
        );
    }

    /**
     * @param $issue
     * @param $params
     * @return mixed
     */
    public function createRemoteLink($issue, $params)
    {

        $options = ['json' => json_decode($params)]; 

        return $this->client->post(
            '/rest/api/2/issue/' . $issue . '/remotelink', $options
        );
        
    }
}

