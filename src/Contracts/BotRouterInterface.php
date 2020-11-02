<?php
namespace Bot\Contracts;

interface BotRouterInterface
{

    function call($method, $userId, $params = []);

}