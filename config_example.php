<?php

return [
    ########################
    # General App Settings
    ########################
    'app_path' => __DIR__ . '/app/Command',
    'data_dir' => __DIR__ . '/var/data',
    'cache_minutes' => 5,
    'notifications_file' => __DIR__ . '/var/logs/notifications.txt',
    'twig_templates_path' => __DIR__ . '/app/views',
    'team_name' => 'Live Coder Girls BR',
    'team_users' => require_once __DIR__ . '/config/users.php',
    'team_domain' => 'codergirls.live',

    ###############################
    # Twitch ChatBot Settings
    ###############################
    'twitch_user' => 'CHATBOT_USERNAME',
    'twitch_oauth' => 'CHATBOT_OAUTH_TOKEN',
    'chatbot_autoload' => __DIR__ . '/chatbot',
];