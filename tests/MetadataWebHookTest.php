<?php

use FP\Larmo\Agents\WebHookAgent\Metadata;

class MetadataWebHookTest extends PHPUnit_Framework_TestCase
{
    private $timestamp;
    private $metadata;

    public function setup()
    {
        $this->timestamp = time();
        $this->metadata = new Metadata("SOURCE","AUTHSTRING",$this->timestamp);
    }

    /**
     * @test
     */
    public function metadataHasCorrectKeys()
    {
        $data = $this->metadata->getMetadata();

        $this->assertArrayHasKey('timestamp', $data);
        $this->assertArrayHasKey('source', $data);
        $this->assertArrayHasKey('authinfo', $data);
    }
}
