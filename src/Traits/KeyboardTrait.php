<?php

namespace Bot\Traits;

trait KeyboardTrait
{
    public function setKeyboard(array $array, $resize = true, $oneTime = true)
    {
        $replyMarkup = json_encode([ 'keyboard' => [$array], 'resize_keyboard' => $resize, 'one_time_keyboard' => $oneTime ], JSON_UNESCAPED_UNICODE);
        $this->addField('reply_markup', $replyMarkup);
    }

    public function setInlineKeyboard(...$array)
    {

        $replyMarkup = json_encode(['inline_keyboard' => [$array]]);

        $this->addField('reply_markup', $replyMarkup);
    }
}