<?php

namespace CD\Commands;

use GuzzleHttp\Client as HttpClient;
use JenkinsKhan\Jenkins;
use Jira\Client as JiraClient;
use Github\Client as GithubClient;
use Jira\Resources\Issues;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetLastPR extends Command
{
    private $pullRequest;

    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this
            ->setName('getLastPR:get')
            ->setDescription('Get the last PR from Github')
            ->addArgument(
                'project',
                InputArgument::REQUIRED,
                'With ticket is related with this project'
            )
            ->addArgument(
                'build',
                InputArgument::REQUIRED,
                'Build of the jenkins example: ATS_JobApplication_CI/32'
            );

    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return  null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $project = $input->getArgument('project');
        $build = $input->getArgument('build');

        $jiraId = $this->getJiraIdFromTheLastClosedPR($project);

        $config = [
            'host' => getenv("JIRA_HOST"),
            'username' => getenv("JIRA_USER"),
            'password' => getenv("JIRA_PASS"),
        ];

        $httpClient = new HttpClient();

        $jira = new JiraClient($httpClient, $config);


        $jiraIssue = new Issues($jira);

        // Create a link for PR
//        $params = '{"object": {"url":"' . $this->pullRequest['html_url'] .
//            '", "title":"Pull Request :: '. $this->pullRequest['id'] .'"}}';

//        $jiraIssue->createRemoteLink($jiraId, $params);

        $buildUrl = getenv('JENKINS_JOB_URL') . $build;
        // Create a link for PR

        $aux = explode("/", $build);

        $title = 'Jenkins Build :: ' . trim($aux[1]);

//        $params = '{"object": {"url":"' . $buildUrl .
//            '", "title":"'.$title.'"}}';

//        $jiraIssue->createRemoteLink($jiraId, $params);
        $params = '{"update": {"comment": [{"add": {"body": "Jenkins :: Project in Staging to be reviewed."}}]},
            "transition": {"id": "931"}}';
        $jiraIssue->updateTransitions($jiraId, $params);

    }

    private function getJiraIdFromTheLastClosedPR($project)
    {
        $client = new GithubClient();
        $client->authenticate(getenv('GITHUB_ACCESS_TOKEN'), null, GithubClient::AUTH_URL_TOKEN);
        $pullRequest = $client->api('pull_request')
            ->all('endouble', $project, ['state' => 'closed']);
        $this->pullRequest = $pullRequest[0];
        $aux = explode("::", $this->pullRequest['title']);
        return (count($aux) >= 2) ? trim($aux[0]) : null;
    }

    /**
     * @param $jobId
     * @return string
     */
    private function getJenkinsJobStatus($jobId) : string
    {

        $jenkinsUrl = getenv('JENKIN_API_URL');
        $jenkins = new Jenkins($jenkinsUrl);
        $job = $jenkins->getJob('ATS_JobApplication_CI');
        var_dump ($job->getJenkinsBuild(36));

        exit;
    }


}
