<?php

namespace Chatbot;

use StreamWidgets\Chatbot\ChatbotCommandInterface;
use StreamWidgets\Chatbot\ChatbotService;

class InfoCommand implements ChatbotCommandInterface
{
    public function handle(ChatbotService $chatbot, array $args = [])
    {
        $channel = $args['channel'];
        $users = implode(', ', array_keys($chatbot->team_info));

        $message = "Girls Live Coders BR <3 - we are: $users . Follow for support ;)";
        $chatbot->getClient()->sendMessage($message, $channel);
    }

    public function getName()
    {
        return 'info';
    }
}