<?php

namespace FP\Larmo\Agents\WebHookAgent\Services\Gitlab;

use FP\Larmo\Agents\WebHookAgent\Services\EventAbstract;
use FP\Larmo\Agents\WebHookAgent\Services\RepositoryInfoTrait;

abstract class GitlabEvent extends EventAbstract
{
    use RepositoryInfoTrait;

    protected $type = '';

    public function __construct($data)
    {
        $this->repositoryInfo = $this->setRepositoryInfo($data);
        parent::__construct($data);
    }

    protected function prepareRepositoryData($repository)
    {
        return array(
            'name' => $repository->name,
            'full_name' => $repository->name,
            'owner' => null,
        );
    }

    private function getTypeAsString() {
        return implode(' ', explode('_', $this->type));
    }

    protected function prepareType($data)
    {
        $attributes = $data->object_attributes;
        return 'gitlab.' . $this->type . '_' . $attributes->action;
    }

    protected function prepareBody($data)
    {
        $attributes = $data->object_attributes;
        return $attributes->action . ' ' . $this->getTypeAsString();
    }

    protected function prepareTimeStamp($data)
    {
        $attributes = $data->object_attributes;
        return $attributes->updated_at;
    }

    protected function prepareAuthor($data)
    {
        return [
            'login' => $data->user->username,
            'name' => $data->user->name
        ];
    }

    protected function prepareExtras($data)
    {
        $attributes = $data->object_attributes;
        return [
            'id' => $attributes->id,
            'number' => $attributes->iid,
            'title' => $attributes->title,
            'body' => $attributes->description,
            'url' => $attributes->url,
            'state' => $attributes->state
        ];
    }
}
