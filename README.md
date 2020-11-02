# spherepack/bot-creator

You can easily create your own bot and work with it

## Installation
``` bash
composer require spherepack/bot-creator
```

## Usage

``` php
$bot = new TelegramBot(TOKEN)
```

## Example

``` php
$bot = new TelegramBot(TG_TOKEN);
if (!$bot->isAnswer()){
    $bot->setUserId(TG_TEST_USER_ID);
    $bot->setMessage('регистрация');
}

$command = new BotRouter(new TelegramCommands());
$message = $command->call($bot->getMessage(), $bot->getUserId());

$bot->setMessage($message);
$bot->setKeyboard(['Регистрация']);
echo json_encode($bot->send(), JSON_UNESCAPED_UNICODE);
```