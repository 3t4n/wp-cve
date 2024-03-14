<?php

namespace luckywp\tableOfContents\core\helpers;

class Json
{

    /**
     * @param string $json
     * @param bool $asArray
     * @return mixed
     */
    public static function decode($json, $asArray = true)
    {
        if ($json === null || $json === '') {
            return null;
        }
        return json_decode((string)$json, $asArray);
    }

    /**
     * @see http://php.net/manual/ru/function.json-encode.php
     * @param mixed $value Данные для кодирования
     * @param int $options Настройки кодирования. По-умолчанию, `JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE`
     * @return string
     */
    public static function encode($value, $options = 320)
    {
        return wp_json_encode($value, $options);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public static function htmlEncode($value)
    {
        return static::encode($value, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS);
    }
}
