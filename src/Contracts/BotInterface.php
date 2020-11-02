<?php

namespace Bot\Contracts;

interface BotInterface
{

    /**
     * @return int
     */
    function getUserId(): int;
    function getUserName();

    function setMessage(string $message);

    function getMessage();

    function setKeyboard(array $array);

    function setUserId(string $userId);

    function send(): array;

    function isAnswer(): bool;
}