<?php

namespace FP\Larmo\Agents\WebHookAgent;

class Metadata {
    private $timestamp;
    private $source;
    private $authinfo;

    public function __construct($timestamp = null) {
        $this->timestamp = $timestamp ? $timestamp : time();
        $this->source = "github";
        $this->authinfo = "AUTH";
    }

    public function getMetadata() {
        return array(
            'timestamp' => $this->timestamp,
            'source' => $this->source,
            'authinfo' => $this->authinfo
        );
    }
}
