<?php

namespace FP\Larmo\Agents\WebHookAgent\Services\Trello;

use FP\Larmo\Agents\WebHookAgent\Services\EventAbstract;

abstract class TrelloEvent extends EventAbstract
{
    private function getTypeAsString()
    {
        return implode(' ', explode('_', $this->type));
    }
}
