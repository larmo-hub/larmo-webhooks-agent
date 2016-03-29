<?php

namespace FP\Larmo\Agents\WebHookAgent\Services\Bitbucket;

use FP\Larmo\Agents\WebHookAgent\Services\EventAbstract;
use FP\Larmo\Agents\WebHookAgent\Services\RepositoryInfoTrait;

abstract class BitbucketEvent extends EventAbstract
{
    use RepositoryInfoTrait;

    public function __construct($data)
    {
        $this->repositoryInfo = $this->setRepositoryInfo($data);
        parent::__construct($data);
    }

    protected function prepareRepositoryData($repository)
    {
        return array(
            'name' => $repository->name,
            'full_name' => $repository->full_name,
            'owner' => isset($repository->owner->display_name) ? $repository->owner->display_name : $repository->owner->username
        );
    }
}