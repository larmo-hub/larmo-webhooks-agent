<?php

use FP\Larmo\Agents\WebHookAgent\Services\Gitlab\Events\Push;

class GitlabEventsTest extends PHPUnit_Framework_TestCase
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
        $push = new Push($this->getDataObjectFromJson(dirname(__FILE__).'/InputData/gitlab-push.json'));
        $expectedResult = json_decode($this->loadFile(dirname(__FILE__).'/OutputData/gitlab-push.json'), true);

        $this->assertEquals($expectedResult, $push->getMessages());
    }
}

