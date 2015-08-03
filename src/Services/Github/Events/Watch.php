<?php

namespace FP\Larmo\Agents\WebHookAgent\Services\Github\Events;

class Watch extends EventAbstract
{
    protected function prepareMessages($dataObject)
    {
        $message = array(
            'type' => 'github.watch_' . $dataObject->action,
            'author' => array(
                'login' => $dataObject->sender->login
            ),
            'body' => $dataObject->action . ' watching repository',
            'extras' => array(
                'action' => $dataObject->action,
                'repository' => $this->getRepositoryInfo()
            )
        );

        return array($message);
    }
}