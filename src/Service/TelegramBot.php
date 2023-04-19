<?php

namespace Bot\Service;

use Bot\Contracts\BotInterface;
use Bot\Contracts\BotKeyboardInterface;
use Bot\Helpers\Helper;
use Bot\Traits\KeyboardTrait;
use GuzzleHttp\Client;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

class TelegramBot implements BotInterface, BotKeyboardInterface
{

    private string $method = 'send';
    private string $token;
    private $bot;
    private $response;
    private array $sendArray = [];
    private string $message = '';
    private $files = [];
    private ?int $userId;
    private string $userName = '';

    private $validate = [];
    private $requiredFields = ['userId', 'message'];
    private $error = [];
    private $status = false;

    use KeyboardTrait;

    /**
     * TelegramBot constructor.
     *
     * @param string $token
     * @throws TelegramSDKException
     */
    function __construct(string $token)
    {
        $this->token = $token;
        $this->bot = new Api($token);
        $this->response = $this->bot->getWebhookUpdate();

        if (!empty($this->response['message'])) {
            $this->method = 'answer';
            $this->setUserId($this->response['message']['chat']['id']);
            $this->setUserName($this->response['message']['chat']['first_name']);

            if (!empty($this->response['message']['text'])) {
                $this->setMessage($this->response['message']['text']);
            }

            if (!empty($this->response['message']['caption'])) {
                $this->setMessage($this->response['message']['caption']);
            }

            if (!empty($this->response['message']['photo'])) {
                $photos = [...$this->response['message']['photo']];
                $photo = array_pop($photos);
                $photoAr = $this->bot->getFile(['file_id' => $photo['file_id']]);
                $this->setFiles($photoAr->getRawResponse());
            }
        }
    }

    function setUserName($name)
    {
        $this->userName = $name;
        $this->validate[] = 'userName';

    }


    // Функция вызова методов API.
    function sendTelegram($method, $response)
    {
        $ch = curl_init('https://api.telegram.org/bot' . $this->token . '/' . $method);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }

    function getUserName()
    {
        return $this->userName;

    }
    /**
     * Метод устанавливает Telegram UserId для последующей отправки ему сообщения
     *
     * @param bool|integer $userId
     * @return bool
     */
    function setUserId($userId = false)
    {
        if (!$userId) {
            $userId = $this->getUserId();
        }

        $this->userId = $userId;
        $this->validate[] = 'userId';
        return true;
    }

    /**
     * Метод возвращает установленный Telegram UserId
     *
     * @return int
     */
    function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Метод возвращает сообщение, полученное от пользователя
     *
     * @return bool|string
     */
    public function getMessage(): string
    {
        return (string) $this->message;
    }

    /**
     * Метод возвращает файлы, полученное от пользователя
     *
     * @return bool|string
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Метод устанавливает текст сообщения для последующей отправки
     *
     * @param string $message
     */
    function setMessage($message)
    {
        $this->message = $message;

        $this->validate[] = 'message';
    }

    function setFiles($files)
    {
        $this->files = $files;

        $this->validate[] = 'files';
    }

    /**
     * Метод проверяет в каком режиме используется класс
     * True - ответ на входящее сообщение из Telegram
     * False - отправка рассылки пользователю
     *
     * @return bool
     */
    function isAnswer(): bool
    {
        return $this->method == 'answer';
    }

    /**
     * Метод собирает массив обязательных полей для отправки
     *
     * @return void
     */
    private function prepareBot()
    {
        $this->addField('chat_id', $this->getUserId());
        $this->addField('text', $this->message);
    }

    /**
     * Метод добавляет значение value по ключу key в массив для отправки
     *
     * @param $key
     * @param $value
     */
    private function addField($key, $value)
    {
        $this->sendArray[$key] = $value;
    }

    /**
     * Метод возвращает все ошибки произошедшие во время выполнения скрипта
     *
     * @return array
     */
    private function getError()
    {
        return $this->error;
    }

    /**
     * Метод добавляет текст ошибки  в общий массив
     *
     * @param $error
     */
    public function setError($error)
    {
        $this->error[] = $error;
    }

    /**
     * Метод проверяет, заполнены ли обязательные поля
     *
     * @return bool
     */
    private function validate()
    {
        if (count($this->requiredFields) == count(array_intersect($this->requiredFields, $this->validate))) {
            return true;
        }
        foreach ($this->requiredFields as $field) {
            if (!in_array($field, $this->validate)) {
                $this->setError("Field [{$field}] is empty!");
            }
        }

        return false;

    }

    /**
     * Метод отправляет сообщение пользователю
     *
     * @return array
     */
    function send(): array
    {
        try {
            if ($this->validate()) {
                $this->prepareBot();
                $this->bot->sendMessage($this->sendArray);
                $this->status = true;
            } else {
                throw new \Exception('Message not send!');
            }
        } catch (\Exception $exception) {
            $this->setError($exception->getMessage());
        }

        return [
            'status' => $this->status,
            'fields' => $this->sendArray,
            'errors' => $this->getError()
        ];
    }

    public static function setCertificate($url, $token)
    {


        $data = [
            'url' => $url,
        ];
        /**@var Client $client */
        $client = new Client();
        try {
            /**
             * @var Client $response
             */
            $response = $client->post("https://api.telegram.org/bot{$token}/setWebhook", Helper::requestParams($data));
            echo "<pre>";
            print_r($response->getBody());
            echo "</pre>";
            die;
        }catch (\Exception $exception){
            echo "<pre>";
            print_r($exception->getMessage());
            echo "</pre>";
        }
    }


}
