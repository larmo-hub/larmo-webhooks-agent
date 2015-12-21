# Larmo WebHooks Agent

[![Build Status](https://travis-ci.org/larmo-hub/larmo-webhooks-agent.svg?branch=master)](https://travis-ci.org/larmo-hub/larmo-webhooks-agent)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/larmo-hub/larmo-webhooks-agent/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/larmo-hub/larmo-webhooks-agent/?branch=master)
[![Code Climate](https://codeclimate.com/github/larmo-hub/larmo-webhooks-agent/badges/gpa.svg)](https://codeclimate.com/github/larmo-hub/larmo-webhooks-agent)
[![Test Coverage](https://codeclimate.com/github/larmo-hub/larmo-webhooks-agent/badges/coverage.svg)](https://codeclimate.com/github/larmo-hub/larmo-webhooks-agent/coverage)

This is a WebHooks Agent for open source project *Larmo*.

## Supported services
- [Github](#github) (events: push, commit comment, issues, issue comment, pull request, deployment, deployment status, create, delete, fork, gollum)
- [Bitbucket](#bitbucket) (events: push, issues, issue comment)
- [Gitlab](#gitlab) (event: push, tag, comments, issues, merge requests)
- [Travis CI](#travis-c)
- [Scrutinizer CI](#scrutinizer-ci) (event: completed)

## To do
- Add support for more services:
    - Jira
    - Trello

## Installation guide
The WebhookAgent is a simple PHP application ready to be deploy to different servers.

### Deploying
#### Use case: Deploying to Heroku 
*The best way - use Heroku Toolbelt https://toolbelt.heroku.com/*

1. Create empty git repository in this directory (if does not exists), type in console `git init`
2. Create heroku app, run command in console `heroku apps:create`
3. Install composer dependencies, type in console `composer install`
4. Commit all files, use command: `git commit -am "Save files for deploy"`
5. Deploy to heroku, run command: `git push heroku master`

After deploying process you can verify that your site is running using the command `heroku open`.

### Activate WebHooks in supported services
#### GitHub
1. Copy your deployed site URL.
2. Go to Github, and select your repository. Go to Settings > Webhooks & Services > Add webhook (https://github.com/USER-NAME/REPO-NAME/settings/hooks/new)
3. In field *Payload URL* put your site URL and add to end of URL */github*, example full URL: *http://still-reef-8508.herokuapp.com/github*
4. Select individual events, but check which events are [supported](#supported-services) in agent.
5. Click 'Add webhook' button.

#### Bitbucket
1. Copy your deployed site URL.
2. Go to Bitbucket, and select your repository. Go to Settings > Integrations > Webhooks > 
Add webhook (https://bitbucket.org/USER-NAME/REPO-NAME/admin/addon/admin/bitbucket-webhooks/bb-webhooks-repo-admin)
3. In field *URL* put your site URL and add to end of URL */bitbucket*, example full URL: *http://still-reef-8508.herokuapp.com/bitbucket*
4. Choose from a full list of triggers, but check which events are [supported](#supported-services) in agent.
5. Click 'Save' button.

#### Gitlab
1. Copy your deployed site URL.
2. Go to your Gitlab page, and select repository. Go to Settings > Web Hooks (https://your-gitlab-site/USER-NAME/REPO-NAME/hooks)
3. In field *URL* put your site URL and add to end of URL */gitlab*, example full URL: *http://still-reef-8508.herokuapp.com/gitlab*
4. Choose from list of triggers, but check which events are [supported](#supported-services) in agent.
5. Click 'Add Web Hook' button.

#### Travis CI
1. Copy your deployed site URL.
2. Open .travis file in your project, and add webhooks notifications, for example:
```
 notifications:
   webhooks: http://your-deployed-site.herokuapp.com/travis
```
3. Save file, commit and push changes to your repository.

#### Scrutinizer CI
1. Copy your deployed agent URL.
2. Go to your repository settings on Scrutinizer and find 'Service Hooks' (example url: https://scrutinizer-ci.com/g/USER/REPO-NAME/settings/service-hooks).
3. Click 'Add web-hook' button and paste agent URL. Save settings.