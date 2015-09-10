<?php

namespace FP\Larmo\Agents\WebHookAgent\Services\Scrutinizer;

use FP\Larmo\Agents\WebHookAgent\Services\SecuritySignatureAbstract;
use FP\Larmo\Agents\WebHookAgent\Request;

class SecuritySignature extends SecuritySignatureAbstract
{
    protected $serviceName = 'scrutinizer';

    private function getValueFromHeader($key, $headers)
    {
        if (isset($key) && is_array($headers) && array_key_exists($key, $headers)) {
            return $headers[$key];
        }

        return null;
    }

    protected function requestHasCorrectSecuritySignature(Request $request)
    {
        $headers = $request->getHeaders();
        $signature = $this->getValueFromHeader('HTTP_X_SCRUTINIZER_SIGNATURE', $headers);

        if ($signature !== null) {
            $event = $this->getValueFromHeader('HTTP_X_SCRUTINIZER_EVENT', $headers);

            if($signature === hash_hmac('sha1', $event.$request->getPayload(), $this->secret)) {
                return true;
            }
        }

        return false;
    }
}
