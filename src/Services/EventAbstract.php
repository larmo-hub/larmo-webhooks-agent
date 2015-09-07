<?php

namespace FP\Larmo\Agents\WebHookAgent\Services;

abstract class EventAbstract implements EventInterface
{
    private $messages;

    public function __construct($data)
    {
        $this->messages = $this->prepareMessages($data);
    }

    public function getMessages()
    {
        return $this->messages;
    }

    protected function prepareMessages($data)
    {
        return [$this->prepareSingleMessage($data)];
    }

    final protected function prepareSingleMessage($data)
    {
        $message = [
            'type' => $this->prepareType($data),
            'timestamp' => $this->prepareTimeStamp($data),
            'author' => $this->prepareAuthor($data),
            'body' => $this->prepareBody($data),
            'extras' => $this->prepareExtras($data)
        ];

        return $message;
    }

    abstract protected function prepareType($data);

    abstract protected function prepareBody($data);

    abstract protected function prepareTimeStamp($data);

    abstract protected function prepareAuthor($data);

    abstract protected function prepareExtras($data);
}
