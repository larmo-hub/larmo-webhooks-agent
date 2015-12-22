<?php

namespace FP\Larmo\Agents\WebHookAgent\Services\Github\Events;

use FP\Larmo\Agents\WebHookAgent\Services\Github\GithubEvent;

class Delete extends GithubEvent
{
    protected function prepareMessages($data)
    {
        return [$this->prepareSingleMessage($data)];
    }

    protected function prepareType($data)
    {
        return 'github.deleted_' . $data->ref_type;
    }

    protected function prepareBody($data)
    {
        return 'deleted ' . $data->ref_type;
    }

    protected function prepareTimeStamp($data)
    {
        return date('c');
    }

    protected function prepareAuthor($data)
    {
        return [
            'login' => $data->sender->login
        ];
    }

    protected function prepareExtras($data)
    {
        return [
            'ref' => $data->ref,
            'repository' => $this->getRepositoryInfo()
        ];
    }
}
