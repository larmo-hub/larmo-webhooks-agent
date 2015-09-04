<?php

namespace FP\Larmo\Agents\WebHookAgent\Services\Travis;

use FP\Larmo\Agents\WebHookAgent\Services\SecuritySignatureAbstract;
use FP\Larmo\Agents\WebHookAgent\Request;

class SecuritySignature extends SecuritySignatureAbstract
{
    protected $serviceName = 'travis';

    protected function requestHasCorrectSecuritySignature(Request $request)
    {
        $headers = getallheaders();
        if ( ($signature = $headers['Authorization']) !== null) {

            $repoSlug = isset($headers['Travis-Repo-Slug']) ? $headers['Travis-Repo-Slug'] : '';
            if(hash('sha256', $repoSlug . $this->secret) === $signature) {
                return true;
            }
        }

        return false;
    }
}
