<?php
namespace Bot\Contracts;

interface BotKeyboardInterface
{

    function setKeyboard(array $array, $resize = true, $oneTime = false);
}