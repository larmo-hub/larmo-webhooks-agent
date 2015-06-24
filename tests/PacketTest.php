<?php

use FP\Larmo\Agents\WebHookAgent\Packet;
use FP\Larmo\Agents\WebHookAgent\Metadata;
use FP\Larmo\Agents\WebHookAgent\Services\Github\GithubData;

class PacketTest extends PHPUnit_Framework_TestCase
{
    private $packet;

    public function setup()
    {
        $_SERVER['HTTP_X_GITHUB_EVENT'] = 'push';
        $service = new GithubData($this->getDataObjectFromJson(), $_SERVER);
        $metadata = new Metadata($service->getServiceName(),"AUTHENTICATION_KEY");
        $this->packet = new Packet($metadata, $service);
    }


    private function loadFile($fileName)
    {
        return file_get_contents($fileName);
    }

    private function getDataObjectFromJson()
    {
        if($json = $this->loadFile(dirname(__FILE__).'/InputData/github-push.json')) {
            return json_decode($json);
        }

        return null;
    }

    /**
     * @test
     */
    public function packetHasCorrectStructure()
    {
        $result = json_decode($this->packet->send(), true);
        $this->assertArrayHasKey('metadata', $result);
        $this->assertArrayHasKey('data', $result);
    }

    /**
     * @test
     */
    public function packetHasCorrectMessages()
    {
        $exprectedResult = json_decode($this->loadFile(dirname(__FILE__).'/OutputData/github-push.json'), true);
        $result = json_decode($this->packet->send(), true);

        $this->assertEquals($exprectedResult, $result['data']);
    }
}
