<?php

namespace FP\Larmo\Agents\WebHookAgent\Services\Scrutinizer;

use FP\Larmo\Agents\WebHookAgent\Services\EventAbstract;
use FP\Larmo\Agents\WebHookAgent\Services\RepositoryInfoTrait;

class ScrutinizerEvent extends EventAbstract
{
    use RepositoryInfoTrait;

    public function __construct($data)
    {
        $this->repositoryInfo = $this->prepareRepositoryData($data);
        parent::__construct($data);
    }

    protected function prepareRepositoryData($data)
    {
        return array(
            'user' => isset($data->metadata->source) ? $data->metadata->source->login : $data->_embedded->repository->login,
            'branch' => isset($data->metadata->source) ? $data->metadata->source->branch : $data->metadata->branch,
            'name' => isset($data->metadata->source) ? $data->metadata->source->repository : $data->_embedded->repository->name
        );
    }

    protected function prepareType($data)
    {
        return 'scrutinizer.' . $data->state;
    }

    protected function prepareBody($data)
    {
        return 'Scrutinizer CI inspection';
    }

    protected function prepareTimeStamp($data)
    {
        return $data->finished_at;
    }

    protected function prepareAuthor($data)
    {
        return [];
    }

    protected function prepareExtras($data)
    {
        $diff = $data->_embedded->index_diff;
        return [
            'id' => $data->uuid,
            'repository' => $this->getRepositoryInfo(),
            'pull_request_number' => isset($data->pull_request_number) ? $data->pull_request_number : '',
            'status' => $data->build->status,
            'results' => array(
                'new_issues' => $diff->nb_new_issues,
                'common_issues' => $diff->nb_common_issues,
                'fixed_issues' => $diff->nb_new_issues,
                'added_code_elements' => $diff->nb_added_code_elements,
                'common_code_elements' => $diff->nb_common_code_elements,
                'changed_code_elements' => $diff->nb_changed_code_elements,
                'removed_code_elements' => $diff->nb_removed_code_elements
            )
        ];
    }
}
