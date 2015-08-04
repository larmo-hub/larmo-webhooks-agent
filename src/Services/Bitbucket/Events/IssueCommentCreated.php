<?php

namespace FP\Larmo\Agents\WebHookAgent\Services\Bitbucket\Events;

use FP\Larmo\Agents\WebHookAgent\Services\Bitbucket\BitbucketEvent;

class IssueCommentCreated extends BitbucketEvent
{
    protected function prepareMessages($data)
    {
        $issue = $data->issue;
        $comment = $data->comment;

        $message = array(
            'type' => 'bitbucket.issue_comment_created',
            'timestamp' => strtotime($issue->created_on),
            'author' => array(
                'login' => $comment->user->username,
                'name' => $comment->user->display_name,
            ),
            'body' => $comment->user->display_name . ' has commented on issue "' . $issue->title . '"',
            'extras' => array(
                'issue_id' => $issue->id,
                'comment_id' => $comment->id,
                'body' => $comment->content->raw,
                'html' => $comment->content->html,
                'url' => $comment->links->html->href,
            )
        );

        return array($message);
    }
}