<?php
namespace Bot\Commands;


use Bot\Contracts\BotCommandsInterface;

abstract class BotCommands implements BotCommandsInterface
{
    private $routes;
    private $method = false;
    private $errors = [];
    function __construct()
    {
        try {
            $configName = $this->getRouteConfigPath();
            $routePath = $configName;
            if (!file_exists($routePath)){
                throw new \Exception("File [$configName] not found!");
            }
            $this->routes = $routePath;
        }catch (\Exception $e){
            $this->errors[] = $e->getMessage();
        }
    }

    abstract protected function getRouteConfigPath(): string;

    final function getMethod($command)
    {
        try {
            if (empty($this->routes[$command])){
                throw new \Exception("Command [{$command}] not found!");
            }
            $this->method = $this->routes[$command];
        }catch (\Exception $e){
            $this->errors[] = $e->getMessage();
        }

        return [
            'method' => $this->method,
            'errors' => $this->errors
        ];
    }

}