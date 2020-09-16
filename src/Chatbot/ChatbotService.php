<?php

namespace StreamWidgets\Chatbot;

use Minicli\App;
use Minicli\ServiceInterface;
use StreamWidgets\Service\LoggerProvider;

class ChatbotService implements ServiceInterface
{
    /** @var TwitchChatClient */
    protected $twitch_client;

    /** @var LoggerProvider */
    public $notifications;

    /** @var array */
    public $team_info;

    /** @var array */
    protected $bot_commands = [];

    /** @var array */
    protected $autoload_commands = [];

    /** @var string path for auto loading commands */
    protected $chatbot_autoload;

    public function load(App $app)
    {
        $this->team_info = $app->config->team_users;
        $twitch_user = $app->config->twitch_user;
        $twitch_oauth = $app->config->twitch_oauth;

        if (!$twitch_user or !$twitch_oauth) {
            $app->getPrinter()->error("Missing 'twitch_user' and/or 'twitch_oauth' config settings.");
            return;
        }

        $this->twitch_client = new TwitchChatClient($twitch_user, $twitch_oauth);
        if ($app->notifications) {
            $this->notifications = $app->notifications;
        }

        $chatbot_autoload = __DIR__ . '/../../chatbot';


        if ($app->config->has('chatbot_autoload')) {
            $chatbot_autoload = $app->config->chatbot_autoload;
        }

        $this->chatbot_autoload = $chatbot_autoload;
        $this->autoloadBotCommands();
    }

    public function getClient()
    {
        return $this->twitch_client;
    }

    public function autoloadBotCommands()
    {
        foreach (glob($this->chatbot_autoload . '/*Command.php') as $filename) {
            $controller_class = basename($filename);
            $controller_class = str_replace('.php', '', $controller_class);

            $full_class_name = sprintf("%s\\%s", $this->getNamespace($filename), $controller_class);

            /** @var ChatbotCommandInterface $controller */
            $controller = new $full_class_name();
            $this->autoload_commands[$controller->getName()] = $controller;
        }
    }

    public function registerBotCommand($command_name, $callback)
    {
        $this->bot_commands[$command_name] = $callback;
    }

    public function botHasCommand($command_name)
    {
        return array_key_exists($command_name, $this->bot_commands) || array_key_exists($command_name, $this->autoload_commands);
    }

    public function getCommandsList()
    {
        return array_keys($this->autoload_commands);
    }

    public function getBotCommand($command_name)
    {
        return $this->bot_commands[$command_name] ?? null;
    }

    public function runCommand($command_name, array $args = [])
    {
        if ($this->getBotCommand($command_name) && is_callable($this->getBotCommand($command_name))) {
            call_user_func($this->getBotCommand($command_name), $this, $args);
        }

        if (array_key_exists($command_name, $this->autoload_commands)) {
            /** @var ChatbotCommandInterface $command */
            $command = $this->autoload_commands[$command_name];

            $command->handle($this, $args);
        }
    }

    protected function getNamespace($filename)
    {
        $lines = preg_grep('/^namespace /', file($filename));
        $namespace_line = array_shift($lines);
        $match = [];
        preg_match('/^namespace (.*);$/', $namespace_line, $match);

        return array_pop($match);
    }
}