<?php

namespace Bot\Traits;

trait KeyboardTrait
{
    public function setKeyboard(array $array, $resize = true, $oneTime = false)
    {
        $replyMarkup = json_encode([ 'keyboard' => [$array], 'resize_keyboard' => $resize, 'one_time_keyboard' => $oneTime ], JSON_UNESCAPED_UNICODE);
        $this->addField('reply_markup', $replyMarkup);
    }
}