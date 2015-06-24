<?php

use FP\Larmo\Agents\WebHookAgent\Services\Github\Events\Push;
use FP\Larmo\Agents\WebHookAgent\Services\Github\Events\CommitComment;
use FP\Larmo\Agents\WebHookAgent\Services\Github\Events\PullRequest;
use FP\Larmo\Agents\WebHookAgent\Services\Github\Events\Issues;
use FP\Larmo\Agents\WebHookAgent\Services\Github\Events\IssueComment;

class GithubEventsTest extends PHPUnit_Framework_TestCase
{
    private function loadFile($fileName)
    {
        return file_get_contents($fileName);
    }

    private function getDataObjectFromJson($fileName)
    {
        if($json = $this->loadFile($fileName)) {
            return json_decode($json);
        }

        return null;
    }

    /**
     * @test
     */
    public function pushEventReturnsCorrectData()
    {
        $push = new Push($this->getDataObjectFromJson(dirname(__FILE__).'/InputData/github-push.json'));
        $expectedResult = json_decode($this->loadFile(dirname(__FILE__).'/OutputData/github-push.json'), true);

        $this->assertEquals($expectedResult, $push->getMessages());
    }

    /**
     * @test
     */
    public function commitCommentEventReturnsCorrectData()
    {
        $commitComment = new CommitComment($this->getDataObjectFromJson(dirname(__FILE__).'/InputData/github-commit_comment.json'));
        $expectedResult = json_decode($this->loadFile(dirname(__FILE__).'/OutputData/github-commit_comment.json'), true);

        $this->assertEquals($expectedResult, $commitComment->getMessages());
    }

    /**
     * @test
     */
    public function pullRequestEventReturnsCorrectData()
    {
        $pullRequest = new PullRequest($this->getDataObjectFromJson(dirname(__FILE__).'/InputData/github-pull_request.json'));
        $expectedResult = json_decode($this->loadFile(dirname(__FILE__).'/OutputData/github-pull_request.json'), true);

        $this->assertEquals($expectedResult, $pullRequest->getMessages());
    }

    /**
     * @test
     */
    public function issueEventReturnsCorrectData()
    {
        $issue = new Issues($this->getDataObjectFromJson(dirname(__FILE__).'/InputData/github-issue.json'));
        $expectedResult = json_decode($this->loadFile(dirname(__FILE__).'/OutputData/github-issue.json'), true);

        $this->assertEquals($expectedResult, $issue->getMessages());
    }

    /**
     * @test
     */
    public function issueCommentEventReturnsCorrectData()
    {
        $issueComment = new IssueComment($this->getDataObjectFromJson(dirname(__FILE__).'/InputData/github-issue_comment.json'));
        $expectedResult = json_decode($this->loadFile(dirname(__FILE__).'/OutputData/github-issue_comment.json'), true);

        $this->assertEquals($expectedResult, $issueComment->getMessages());
    }
}

