<?php

namespace FP\Larmo\Agents\WebHookAgent\Services;

interface SecuritySignatureInterface
{
    public function __construct($request, $secrets);
    public function isSetCorrectSignature();
}
