<?php

use FP\Larmo\Agents\WebHookAgent\Request;
use FP\Larmo\Agents\WebHookAgent\Services\Travis\SecuritySignature as TravisSecuritySignature;

class TravisSecuritySignatureTest extends PHPUnit_Framework_TestCase
{
    private $secrets;
    private $payload;

    protected function setUp()
    {
        $this->payload = file_get_contents(dirname(__FILE__).'/InputData/travis.json');
        $this->secrets = ['travis' => 'super-security'];
    }

    private function createRequest(array $server, $payload)
    {
        return new Request($server, $payload);
    }

    /**
     * @test
     */
    public function requestWithCorrectSecuritySignatureShouldBeTrue()
    {
        $_SERVER['HTTP_TRAVIS_REPO_SLUG'] = 'user/repo';
        $_SERVER['HTTP_AUTHORIZATION'] = hash('sha256', 'user/repo' . $this->secrets['travis']);
        $request = $this->createRequest([], $this->payload);
        $security = new TravisSecuritySignature($request, $this->secrets);
        $this->assertEquals(true, $security->isSecuritySignatureCorrect());
    }

    /**
     * @test
     */
    public function requestWithEmptyConfigSecretShouldBeTrue()
    {
        $_SERVER['HTTP_TRAVIS_REPO_SLUG'] = 'user/repo';
        $_SERVER['HTTP_AUTHORIZATION'] = hash('sha256', 'user/repo' . $this->secrets['travis']);
        $request = $this->createRequest([], $this->payload);
        $security = new TravisSecuritySignature($request, ['travis' => '']);
        $this->assertEquals(true, $security->isSecuritySignatureCorrect());
    }

    /**
     * @test
     */
    public function requestWithIncorrectSecuritySignatureShouldBeFalse()
    {
        $_SERVER['HTTP_TRAVIS_REPO_SLUG'] = 'user/repo';
        $_SERVER['HTTP_AUTHORIZATION'] = hash('sha256', 'user/repo' . 'wrong-security');
        $request = $this->createRequest([], $this->payload);
        $security = new TravisSecuritySignature($request, $this->secrets);
        $this->assertEquals(false, $security->isSecuritySignatureCorrect());
    }
}
