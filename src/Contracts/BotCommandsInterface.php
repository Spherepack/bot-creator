<?php
namespace Bot\Contracts;

interface BotCommandsInterface
{
    function getMethod(string $command):array;
}