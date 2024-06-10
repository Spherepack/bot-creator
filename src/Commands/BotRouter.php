<?php
namespace Bot\Commands;

use Bot\Contracts\BotCommandsInterface;
use Bot\Contracts\BotInterface;
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
     * @param BotInterface $bot
     * @return array
     */
    function call($command, $userId, BotInterface $bot)
    {
        $result = $this->botCommand->getMethod(mb_strtolower($command));
        $answer = false;

        if (!empty($result['method']) && method_exists($this->botCommand, $result['method'])){
            $method = $result['method'];
            $answer = $this->botCommand->$method($userId, $bot, ...$result['params']);

        }elseif(method_exists($this->botCommand, $this->defaultMethod)){
            $method = $this->defaultMethod;
            $answer = $this->botCommand->$method($userId, $bot);
        } else {
            $result['errors'][] = 'Метод не найден';
        }
        return [
            'answer' => $answer,
            'errors' => $result['errors']
        ];
    }

}
