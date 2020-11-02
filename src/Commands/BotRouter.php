<?php
namespace Bot\Commands;

use Bot\Contracts\BotCommandsInterface;
use Bot\Contracts\BotRouterInterface;

class BotRouter implements BotRouterInterface
{

    protected $defaultMethod = 'home';

    /**
     * @var BotCommandsInterface
     */
    private $botCommand;

    /**
     * BotRouter constructor.
     * @param BotCommandsInterface $botCommand
     */
    function __construct(BotCommandsInterface $botCommand)
    {
        $this->botCommand = $botCommand;
    }

    /**
     * @param string $command
     * @param int $userId
     * @param array $params
     * @return array
     */
    function call($command, $userId, $params = [])
    {
        $result = $this->botCommand->getMethod(mb_strtolower($command));

        $answer = false;
        if (!empty($result['method']) && method_exists($this->botCommand, $result['method'])){
            $method = $result['method'];
            $answer = $this->botCommand->$method($userId, $params);

        }elseif(method_exists($this->botCommand, $this->defaultMethod)){
            $method = $this->defaultMethod;
            $answer = $this->botCommand->$method($userId, $params);

        }
        return [
            'answer' => $answer,
            'errors' => $result['errors']
        ];
    }

}