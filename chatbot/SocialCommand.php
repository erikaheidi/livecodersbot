<?php

namespace Chatbot;

use StreamWidgets\Chatbot\ChatbotCommandInterface;
use StreamWidgets\Chatbot\ChatbotService;

class SocialCommand implements ChatbotCommandInterface
{
    public function handle(ChatbotService $chatbot, array $args = [])
    {
        $author = $args['author'];
        $channel = $args['channel'];

        $message = "";
        if (key_exists($channel, $chatbot->team_info)) {
            foreach ($chatbot->team_info[$channel] as $service => $url) {
                $message .= $service . ': ' . $url . ', ';
            }

            $message .= " follow for support ;)";
        }

        $chatbot->getClient()->sendMessage($message, $channel);
    }

    public function getName()
    {
        return 'social';
    }
}