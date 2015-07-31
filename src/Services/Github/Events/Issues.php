<?php

namespace FP\Larmo\Agents\WebHookAgent\Services\Github\Events;

class Issues extends EventAbstract
{
    protected function prepareMessages($dataObject)
    {
        $issue = $dataObject->issue;

        $message = array(
            'type' => 'github.issue_' . $dataObject->action,
            'timestamp' => $issue->created_at,
            'author' => array(
                'login' => $issue->user->login
            ),
            'body' => $dataObject->action . ' issue',
            'extras' => array(
                'id' => $issue->id,
                'number' => $issue->number,
                'title' => $issue->title,
                'body' => $issue->body,
                'url' => $issue->html_url
            )
        );

        return array($message);
    }
}
