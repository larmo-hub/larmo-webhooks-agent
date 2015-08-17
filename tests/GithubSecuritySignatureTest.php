<?php

use FP\Larmo\Agents\WebHookAgent\Request;
use FP\Larmo\Agents\WebHookAgent\Services\Github\SecuritySignature as GHSecuritySignature;

class GithubSecuritySignatureTest extends PHPUnit_Framework_TestCase
{
    private $secrets;
    private $payload;

    protected function setUp()
    {
        $this->payload = file_get_contents(dirname(__FILE__).'/InputData/github-push.json');
        $this->secrets = ['github' => 'super-security'];
    }

    private function createRequest(array $server, $payload)
    {
        return new Request($server, $payload);
    }

    /**
     * @test
     */
    public function requestWithCorrectSecuritySignatureShouldReturnService()
    {
        $hash = hash_hmac('sha1', $this->payload, $this->secrets['github']);
        $server = ['HTTP_X_GITHUB_EVENT' => 'push', 'HTTP_X_HUB_SIGNATURE' => 'sha1=' . $hash];
        $request = $this->createRequest($server, $this->payload);

        $security = new GHSecuritySignature($request, $this->secrets);
        $this->assertEquals(true, $security->isSecuritySignatureCorrect());
    }

    /**
     * @test
     */
    public function requestWithIncorrectSecuritySignatureShouldThrownError()
    {
        $hash = hash_hmac('sha1', $this->payload, 'wrong-security');
        $server = ['HTTP_X_GITHUB_EVENT' => 'push', 'HTTP_X_HUB_SIGNATURE' => 'sha1=' . $hash];
        $request = $this->createRequest($server, $this->payload);

        $security = new GHSecuritySignature($request, $this->secrets);
        $this->assertEquals(false, $security->isSecuritySignatureCorrect());
    }
}
