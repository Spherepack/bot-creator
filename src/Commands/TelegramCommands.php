<?php
namespace Bot\Commands;


use Bot\Contracts\BotCommandsInterface;

class TelegramCommands extends BotCommands
{

    protected function getRouteConfigName(): string
    {
        return 'telegram11.php';
    }

    function register($userId, $params)
    {
        return 'register ' . $userId . ' yes';
    }


}