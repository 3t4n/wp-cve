<?php

namespace Avecdo\SDK\Classes;

class Helpers
{
    /**
     * Takes an array and makes it json ready
     *
     * @param array $data
     * @return array
     */
    public static function sanitizeArray ($data = array()) {
        if (!is_array($data) || !count($data)) {
            return array();
        }
        foreach ($data as $k => $v) {
            if (!is_array($v) && !is_object($v)) {
                $data[$k] = static::getJSONReadyString($v, $k);
            }
            if (is_array($v)) {
                $data[$k] = static::sanitizeArray($v);
            }
        }
        return $data;
    }

    /**
     * Takes a string and makes it json ready
     *
     * @param $string
     * @param $key
     * @return string
     */
    public static function getJSONReadyString($string, $key)
    {
        $string = htmlspecialchars_decode($string);
        $string = html_entity_decode($string);
        $string = static::removeInlineStyle($string);

        if ($key === 'description') {
            $string = static::sanitizeHTMLTags($string);
        }

        $string = strip_tags($string,'<br>');
        $string = trim($string);
        //$string = stripUnwantedCharacters($string);
        //$string = removeDoubleSpaces($string);

        return $string;
    }

    /**
     * Takes a string, checks for wrong word spacings and returns a new one
     *
     * @param $string
     * @return string
     */
    public static function removeDoubleSpaces($string)
    {
        return preg_replace('/\s{2,}/', ' ', $string);
    }

    /**
     * Takes a string, removes inline style
     *
     * @param $string
     * @return mixed
     */
    public static function removeInlineStyle($string){
        return preg_replace('/(<[^>]+) style=".*?"/i', '$1', $string);
    }

    /**
     * @param $string
     * @return mixed
     */
    public static function stripUnwantedCharacters($string)
    {
        //return preg_replace('/[^\w \.,\s!+\'"#:?=\d<>^_\-\\/]/u', '', $string);
        return preg_replace('/[^\w \.,\s!+"]/u', '', $string);
    }

    /**
     * @param $string
     * @return mixed
     */
    public static function sanitizeHTMLTags($string)
    {
        return preg_replace('/<\/p>|<\/h\d+?>|<br ?\/?>/i', "\n", $string);
    }

    /**
     * Adds apache-only headers function to nginx servers
     *
     * @return array
     */
    public static function getAllHeaders()
    {
        $headers = array();

        if (function_exists('getallheaders')) {
            $_headers = getallheaders();

            foreach ($_headers as $name => $value) {
                $headers[strtolower($name)] = $value;
            }
            return $headers;
        }

        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[strtolower(str_replace(' ', '-', str_replace('_', ' ', substr($name, 5))))] = $value;
            }
        }

        return $headers;
    }
}
