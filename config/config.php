<?php

$config = [
    'authentication' => getenv('authinfo'),
    'hubURI' => getenv('hub_uri'),
    'signatures' => [
        'github' => getenv('github_secret_signature'),
        'travis' => getenv('travis_secret_signature')
    ]
];
