<?php
namespace Bot\Contracts;

interface BotRouterInterface
{

    function call(string $method, int $userId, BotInterface $bot);

}