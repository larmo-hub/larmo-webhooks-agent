<?php

namespace FP\Larmo\Agents\WebHookAgent\Services\Github;

use FP\Larmo\Agents\WebHookAgent\Services\SecuritySignatureInterface;

class SecuritySignature implements SecuritySignatureInterface
{
    private $result;

    public function __construct($request, $secrets)
    {
        $this->result = $this->testSignature($request, $secrets);
    }

    private function testSignature($request, $secrets)
    {
        // If secret isn't set in config then return true
        // or check that request has correct signature
        if(empty($secrets['github']) || $this->requestHasCorrectSecuritySignature($request, $secrets['github'])) {
            return true;
        }

        return false;
    }

    private function getSignatureFromHeader($headers)
    {
        $key = 'HTTP_X_HUB_SIGNATURE';
        if (isset($key) && is_array($headers) && array_key_exists($key, $headers)) {
            return $headers[$key];
        }

        return null;
    }

    private function requestHasCorrectSecuritySignature($request, $secret)
    {
        if(($signature = $this->getSignatureFromHeader($request->getHeaders())) !== null) {
            // Signature in header has format "ALGORITHM=HASH" (ex. sha1=fe767cff7b00733332eb18e5229a8ef3f65cfa06)
            $hashArray = explode('=', $signature);
            if(isset($hashArray[1]) && $hashArray[1] === hash_hmac($hashArray[0], $request->getPayload(), $secret)) {
                return true;
            }

        }

        return false;
    }

    public function isSetCorrectSignature()
    {
        return $this->result;
    }
}
