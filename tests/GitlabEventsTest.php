<?php

use FP\Larmo\Agents\WebHookAgent\Services\Gitlab\Events\Push;
use FP\Larmo\Agents\WebHookAgent\Services\Gitlab\Events\MergeRequest;
use FP\Larmo\Agents\WebHookAgent\Services\Gitlab\Events\Issue;
use FP\Larmo\Agents\WebHookAgent\Services\Gitlab\Events\Tag;
use FP\Larmo\Agents\WebHookAgent\Services\Gitlab\Events\Note;

class GitlabEventsTest extends BaseEventsTest
{
    /**
     * @test
     */
    public function pushEventReturnsCorrectData()
    {
        $push = new Push($this->getDataObjectFromJson(dirname(__FILE__).'/InputData/gitlab-push.json'));
        $expectedResult = json_decode($this->loadFile(dirname(__FILE__).'/OutputData/gitlab-push.json'), true);

        $this->assertEquals($expectedResult, $push->getMessages());
    }

    /**
     * @test
     */
    public function mergeRequestReturnsCorrectData()
    {
        $mergeRequest = new MergeRequest($this->getDataObjectFromJson(dirname(__FILE__).'/InputData/gitlab-merge_request.json'));
        $expectedResult = json_decode($this->loadFile(dirname(__FILE__).'/OutputData/gitlab-merge_request.json'), true);

        $this->assertEquals($expectedResult, $mergeRequest->getMessages());
    }

    /**
     * @test
     */
    public function issueReturnsCorrectData()
    {
        $issue = new Issue($this->getDataObjectFromJson(dirname(__FILE__).'/InputData/gitlab-issue.json'));
        $expectedResult = json_decode($this->loadFile(dirname(__FILE__).'/OutputData/gitlab-issue.json'), true);

        $this->assertEquals($expectedResult, $issue->getMessages());
    }

    /**
     * @test
     */
    public function tagReturnsCorrectData()
    {
        $tag = new Tag($this->getDataObjectFromJson(dirname(__FILE__).'/InputData/gitlab-tag.json'));
        $now = date('Y-m-d H:i:s T',time());
        $expectedFromFile = str_replace('{now}',$now,$this->loadFile(dirname(__FILE__).'/OutputData/gitlab-tag.json'));
        $expectedResult = json_decode($expectedFromFile, true);

        $this->assertEquals($expectedResult, $tag->getMessages());
    }

    /**
     * @test
     */
    public function commentCommitReturnCorrectData()
    {
        $comment = new Note($this->getDataObjectFromJson(dirname(__FILE__).'/InputData/gitlab-comment_commit.json'));
        $expectedResult = json_decode($this->loadFile(dirname(__FILE__).'/OutputData/gitlab-comment_commit.json'), true);

        $this->assertEquals($expectedResult, $comment->getMessages());
    }

    /**
     * @test
     */
    public function commentIssueReturnCorrectData()
    {
        $comment = new Note($this->getDataObjectFromJson(dirname(__FILE__).'/InputData/gitlab-comment_issue.json'));
        $expectedResult = json_decode($this->loadFile(dirname(__FILE__).'/OutputData/gitlab-comment_issue.json'), true);

        $this->assertEquals($expectedResult, $comment->getMessages());
    }

    /**
     * @test
     */
    public function commentMergeRequestReturnCorrectData()
    {
        $comment = new Note($this->getDataObjectFromJson(dirname(__FILE__).'/InputData/gitlab-comment_mergerequest.json'));
        $expectedResult = json_decode($this->loadFile(dirname(__FILE__).'/OutputData/gitlab-comment_mergerequest.json'), true);

        $this->assertEquals($expectedResult, $comment->getMessages());
    }

    /**
     * @test
     */
    public function commentSnippetReturnCorrectData()
    {
        $comment = new Note($this->getDataObjectFromJson(dirname(__FILE__).'/InputData/gitlab-comment_snippet.json'));
        $expectedResult = json_decode($this->loadFile(dirname(__FILE__).'/OutputData/gitlab-comment_snippet.json'), true);

        $this->assertEquals($expectedResult, $comment->getMessages());
    }
}
