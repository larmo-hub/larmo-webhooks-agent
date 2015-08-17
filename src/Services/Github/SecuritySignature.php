<?php

namespace FP\Larmo\Agents\WebHookAgent\Services\Github;

use FP\Larmo\Agents\WebHookAgent\Services\SecuritySignatureAbstract;
use FP\Larmo\Agents\WebHookAgent\Request;

class SecuritySignature extends SecuritySignatureAbstract
{
    protected $serviceName = 'github';

    private function getSignatureFromHeader($headers)
    {
        $key = 'HTTP_X_HUB_SIGNATURE';
        if (isset($key) && is_array($headers) && array_key_exists($key, $headers)) {
            return $headers[$key];
        }

        return null;
    }

    protected function requestHasCorrectSecuritySignature(Request $request)
    {
        if(($signature = $this->getSignatureFromHeader($request->getHeaders())) !== null) {
            // Signature in header has format "ALGORITHM=HASH" (ex. sha1=fe767cff7b00733332eb18e5229a8ef3f65cfa06)
            $hashArray = explode('=', $signature);
            if(isset($hashArray[1]) && $hashArray[1] === hash_hmac($hashArray[0], $request->getPayload(), $this->secret)) {
                return true;
            }
        }

        return false;
    }
}
