<?php


namespace Chatbot;


use StreamWidgets\Chatbot\ChatbotCommandInterface;
use StreamWidgets\Chatbot\ChatbotService;

class HelloCommand implements ChatbotCommandInterface
{
    public function handle(ChatbotService $chatbot, array $args = [])
    {
        $author = $args['author'];
        $channel = $args['channel'];
        $hello = [ 'oh hello', 'hey', 'howdy', 'oie', 'hallo'];
        $message = $hello[array_rand($hello)] . " @$author !";

        $chatbot->getClient()->sendMessage($message, $channel);
    }

    public function getName()
    {
        return 'hello';
    }
}