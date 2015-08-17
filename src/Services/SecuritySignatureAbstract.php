<?php

namespace FP\Larmo\Agents\WebHookAgent\Services;

use FP\Larmo\Agents\WebHookAgent\Request;

abstract class SecuritySignatureAbstract
{
    protected $serviceName;
    protected $secret;
    private $result;

    public function __construct(Request $request, array $secrets)
    {
        $this->secret = $this->getSecretFromConfig($secrets);
        $this->result = $this->testSignature($request);
    }

    final protected function getSecretFromConfig(array $secrets)
    {
        if(empty($secrets[$this->serviceName])) {
            return null;
        }

        return $secrets[$this->serviceName];
    }

    final protected function testSignature(Request $request)
    {
        // If secret isn't set in config then return true
        // or check that request has correct signature
        if($this->secret === null || $this->requestHasCorrectSecuritySignature($request)) {
            return true;
        }

        return false;
    }

    final public function isSecuritySignatureCorrect()
    {
        return $this->result;
    }

    abstract protected function requestHasCorrectSecuritySignature(Request $request);
}
