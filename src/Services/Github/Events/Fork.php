<?php

namespace FP\Larmo\Agents\WebHookAgent\Services\Github\Events;

class Fork extends EventAbstract
{
    protected function prepareMessages($dataObject)
    {
        $message = array(
            'type' => 'github.fork',
            'timestamp' => $dataObject->forkee->created_at,
            'author' => array(
                'login' => $dataObject->sender->login
            ),
            'body' => 'deployment',
            'extras' => array(
                'fork' => array(
                    'name' => $dataObject->forkee->name,
                    'full_name' => $dataObject->forkee->full_name,
                    'owner' => $dataObject->forkee->owner->login
                ),
                'repository' => $this->getRepositoryInfo()
            )
        );

        return array($message);
    }
}