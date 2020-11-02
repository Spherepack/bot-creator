<?php
namespace Bot\Contracts;

interface BotInterface
{

    function getUserId();
    function setMessage($message);
    function getMessage();
    function setKeyboard(array $array);
    function setUserId($userId);
    function send();
    function isAnswer();
}