<?php

namespace Chatbot;

use StreamWidgets\Chatbot\ChatbotCommandInterface;
use StreamWidgets\Chatbot\ChatbotService;

class HelpCommand implements ChatbotCommandInterface
{
    public function handle(ChatbotService $chatbot, array $args = [])
    {
        $author = $args['author'];
        $channel = $args['channel'];

        $commands = implode(' !', $chatbot->getCommandsList());
        $message = "@$author available commands: $commands.";

        $chatbot->getClient()->sendMessage($message, $channel);
    }

    public function getName()
    {
        return 'commands';
    }
}