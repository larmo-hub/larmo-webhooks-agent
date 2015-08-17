<?php

use FP\Larmo\Agents\WebHookAgent\Request;
use FP\Larmo\Agents\WebHookAgent\Services\ServiceFactory;
use FP\Larmo\Agents\WebHookAgent\Services\ServiceDataInterface;

class ServiceFactoryTest extends PHPUnit_Framework_TestCase
{
    private $secrets;
    private $request;
    private $serviceName;
    private $payload;

    protected function setUp()
    {
        $server = ['HTTP_X_GITHUB_EVENT' => 'push'];

        $this->payload = file_get_contents(dirname(__FILE__).'/InputData/github-push.json');
        $this->secrets = ['github' => 'super-security'];
        $this->serviceName = 'github';
        $this->request = $this->createRequest($server, $this->payload);
    }

    private function createRequest(array $server, $payload)
    {
        return new Request($server, $payload);
    }

    /**
     * @test
     */
    public function serviceFactoryShouldReturnCorrectServiceInstance()
    {
        $service = ServiceFactory::create($this->serviceName, $this->request);
        $this->assertInstanceOf(ServiceDataInterface::class, $service);
    }

    /**
     * @test
     */
    public function wrongServiceNameShouldThrownException()
    {
        $this->setExpectedException("FP\\Larmo\\Agents\\WebHookAgent\\Exceptions\\ServiceNotFoundException");
        ServiceFactory::create('wrong-service', $this->request);
    }

    /**
     * @test
     */
    public function wrongEventNameShouldThrownException()
    {
        $this->setExpectedException("FP\\Larmo\\Agents\\WebHookAgent\\Exceptions\\EventTypeNotFoundException");

        $request = $this->createRequest(['HTTP_X_GITHUB_EVENT' => 'unknown'], $this->payload);
        ServiceFactory::create($this->serviceName, $request);
    }

    /**
     * @test
     */
    public function wrongPayloadShouldThrownException()
    {
        $this->setExpectedException("\\Exception");

        $request = $this->createRequest(['HTTP_X_GITHUB_EVENT' => 'push'], array());
        ServiceFactory::create($this->serviceName, $request);
    }

    /**
     * @test
     */
    public function requestWithoutSecretSignatureShouldThrownException()
    {
        $this->setExpectedException("FP\\Larmo\\Agents\\WebHookAgent\\Exceptions\\InvalidSecretSignatureException");
        ServiceFactory::create($this->serviceName, $this->request, $this->secrets);;
    }
}
