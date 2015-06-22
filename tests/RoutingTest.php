<?php

use FP\Larmo\Agents\WebHookAgent\Routing;
use FP\Larmo\Agents\WebHookAgent\Services\Github\GithubData;

class RoutingTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function identifierIsExtractedCorrectlyWithUnslashedUri()
    {
        $routing = new Routing("http://localhost/agents/github", "", "push");
        $this->assertEquals("github",$routing->getSourceIdentifier());
    }

    /**
     * @test
     */
    public function identifierIsExtractedCorrectlyWithSlashedUri()
    {
        $routing = new Routing("http://localhost/agents/github/", "", "push");
        $this->assertEquals("github",$routing->getSourceIdentifier());
    }

    /**
     * @test
     */
    public function identifierIsEmptyIfUriIsEmpty()
    {
        $routing = new Routing("", "", "push");
        $this->assertEquals(null,$routing->getSourceIdentifier());
    }
}
