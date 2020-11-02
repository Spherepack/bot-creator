<?php

namespace Bot\Commands;


use Bot\Contracts\BotCommandsInterface;

abstract class BotCommands implements BotCommandsInterface
{
    private $routes;
    private $method = false;
    private $errors = [];
    private $answer = [
        'message' => null,
        'keyboard' => false
    ];

    protected $keyboardInline = 'setInlineKeyboard';
    protected $keyboardDefault = 'setKeyboard';



    function __construct()
    {
        try {
            $routePath = $this->getRouteConfigPath();

            if (!file_exists($routePath)) {
                throw new \Exception("File [$routePath] not found!");
            }
            $this->routes = include $routePath;
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
        }
    }

    abstract protected function getRouteConfigPath(): string;

    final function getMethod(string $command): array
    {
        try {
            if (empty($this->routes[$command])) {
                throw new \Exception("Command [{$command}] not found!");
            }
            $this->method = $this->routes[$command];
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
        }

        return [
            'method' => $this->method,
            'errors' => $this->errors
        ];
    }

    final protected function createAnswer(string $message, string $keyboardType = '', array $keyboard = [])
    {
        $this->answer['message'] = $message;
        if (!empty($keyboardType) && !empty($keyboard)){
            $this->answer['keyboard'] = ['type' => $keyboardType, 'keyboard' => $keyboard];
        }

        return $this->answer;
    }

    protected function keyboardCreate(array $params)
    {
        $res = [];
        if (!empty($params['type']) && !empty($params['keyboard'])){
            switch ($params['type']){
                case $this->keyboardDefault:
                    $res = [
                        $params['keyboard'],
                        isset($params['resize']) ? $params['resize'] : true,
                        isset($params['oneTime']) ? $params['oneTime'] : true,
                    ];

                    break;

                case $this->keyboardInline:
                    foreach ($params['keyboard'] as $keyboard){
                        $res[] = $keyboard;
                    }


                    break;
            }
        }

        return $res;
    }

    private function exampleCommand($userId, $params)
    {
        $message = 'Example command';
        $keyboardType = $this->keyboardDefault;
        $keyboard = $this->keyboardCreate([
            'type' => $keyboardType,
            'keyboard' => ['Example button'],
        ]);

        $keyboardType = $this->keyboardInline;
        $keyboard = $this->keyboardCreate([
            'type' => $keyboardType,
            'keyboard' => [
                [
                    'text' => 'Yandex',
                    'url' => 'https://yandex.ru/'
                ]
            ],
        ]);

        return $this->createAnswer($message, $keyboardType, $keyboard);
    }

}