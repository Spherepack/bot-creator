<?php
namespace Bot\Helpers;
class Helper {
    static function requestParams(array $data) {
        $has_resource = false;
        $multipart = [];

        array_walk($data, function (&$item) {
            is_array($item) && $item = json_encode($item);
        });

        foreach ($data as $key => $item) {
            $has_resource |= is_resource($item);
            $multipart[] = [
                'name' => $key,
                'contents' => $item,
            ];
        }

        if ($has_resource) {
            return [
                'multipart' => $multipart,
            ];
        }

        return [
            'form_params' => $data,
        ];
    }
}
