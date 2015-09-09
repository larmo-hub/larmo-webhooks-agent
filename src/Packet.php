<?php

namespace FP\Larmo\Agents\WebHookAgent;

use FP\Larmo\Agents\WebHookAgent\Services\ServiceDataInterface;

class Packet
{
    private $packetArray;

    public function __construct(Metadata $metadataObject, ServiceDataInterface $service)
    {
        $metadata = $metadataObject->getMetadata();
        $messages = $service->getData();
        $this->packetArray = $this->preparePacket($metadata, $messages);
    }

    private function preparePacket($metadata, $messages)
    {
        return array(
            'metadata' => $metadata,
            'data' => $messages
        );
    }

    public function getPacket()
    {
        return $this->packetArray;
    }

    public function send($uri)
    {
        $targetConnector = new TargetConnector($uri, $this->getPacket());
        $targetConnector->send();

        return $this->packetArray;
    }
}
