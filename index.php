<?php

include_once 'vendor/autoload.php';

use Mobcash\Helthlife\ReddyBotService;

$reddyService = new ReddyBotService('<token>');


// отправка
$message =  $reddyService->sendMessage('hello ',null,null,"<id_reddy>");

// получение 
$updates = $reddyService->getUpdate();

foreach($updates as $message)
{
    echo "<pre>";
    var_dump($message);
    echo "</pre>";
}
