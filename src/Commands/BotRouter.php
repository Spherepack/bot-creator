<?php
namespace Bot\Commands;

use Bot\Contracts\BotCommandsInterface;
use Bot\Contracts\BotRouterInterface;

class BotRouter implements BotRouterInterface
{

    /**
     * @var BotCommandsInterface
     */
    private $botCommand;

    function __construct(BotCommandsInterface $botCommand)
    {
        $this->botCommand = $botCommand;
    }

    function call($command, $userId, $params = [])
    {
        $result = $this->botCommand->getMethod(mb_strtolower($command));

        if (!empty($result['method']) && method_exists($this->botCommand, $result['method'])){
            $method = $result['method'];
            $answer = $this->botCommand->$method($userId, $params);

            return $answer;
        }
        return implode(', ', $result['errors']);


    }
}