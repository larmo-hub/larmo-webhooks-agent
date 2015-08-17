<?php

namespace FP\Larmo\Agents\WebHookAgent\Services;

use FP\Larmo\Agents\WebHookAgent\Exceptions\EventTypeNotFoundException;

abstract class ServiceAbstract implements ServiceDataInterface {
    protected $data;
    protected $serviceName;
    protected $eventHeader;
    protected $eventType;

    public function __construct($payload, $requestHeaders = null)
    {
        $this->eventType = $this->getEventType($requestHeaders);
        $this->data = $this->prepareData($payload);
    }

    protected function getEventType($requestHeaders)
    {
        $key = $this->eventHeader;
        if (isset($key) && is_array($requestHeaders) && array_key_exists($key, $requestHeaders)) {
            return $requestHeaders[$key];
        }

        return null;
    }

    protected function prepareData($payload)
    {
        if ($this->eventType) {
            $eventClass = $this->getEventClass();

            if (class_exists($eventClass) && $eventClass) {
                $event = new $eventClass($payload);
                return $event->getMessages();
            }

            throw new EventTypeNotFoundException;
        }

        throw new \InvalidArgumentException;
    }

    protected function getEventClass()
    {
        $eventTypeArray = explode('_', $this->eventType);
        $eventClassPath = '\\FP\\Larmo\\Agents\\WebHookAgent\\Services\\' . ucfirst($this->serviceName) . '\\Events\\';
        return $eventClassPath . implode('', array_map('ucfirst', $eventTypeArray));
    }

    public function getData()
    {
        return $this->data;
    }

    public function getServiceName()
    {
        return $this->serviceName;
    }
}
