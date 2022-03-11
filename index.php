<?php

include __DIR__ . '/vendor/autoload.php';
require './global.php';


use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;
use Discord\Builders\MessageBuilder;

$discord = new Discord([
    'token' => getenv('BOT_TOKEN'),
]);


$discord->on('ready', function (Discord $discord) {
    echo "Bot is ready!", PHP_EOL;

    $builder = MessageBuilder::new();
    
    // Listen for messages.
    $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) use($builder){
        if ($message->author->bot) return;
        echo "{$message->author->username}: {$message->content}", PHP_EOL;
        
    });
});

$discord->run();
