<?php
namespace Bot\Contracts;

interface BotCommandsInterface
{
    function getMethod($command);
}