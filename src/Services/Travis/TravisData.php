<?php

namespace FP\Larmo\Agents\WebHookAgent\Services\Travis;

use FP\Larmo\Agents\WebHookAgent\Services\ServiceAbstract;

class TravisData extends ServiceAbstract
{
    protected $serviceName = 'travis';

    protected function prepareData($data)
    {
        $message = array(
            'type' => 'travis',
            'timestamp' => $data->finished_at,
            'author' => array(
                'name' => $data->committer_name,
                'email' => $data->committer_email
            ),
            'body' => 'The Travis CI build',
            'extras' => array(
                'build_url' => $data->build_url,
                'number_build' => $data->number,
                'type' => $data->type,
                'state' => $data->state,
                'git_number' => $data->type == "push" ? $data->commit : $data->pull_request_number,
                'git_url' => $data->compare_url,
                'repository' => array(
                    'name' => $data->repository->name,
                    'owner' => $data->repository->owner_name,
                    'branch' => $data->branch
                )
            )
        );

        return array($message);
    }
}
