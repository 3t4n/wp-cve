<?php

namespace PlxPortal\Content;

class Filters
{
    public static function findReplaceables(string $content)
    {
        $to_replace = array();
        $matches = array();

        preg_match_all("/\[[^\]]*\]/", $content, $matches);

        if (count($matches[0])) {
            $to_replace = array_unique($matches[0]);
        }

        return $to_replace;
    }

    public static function findAndReplace(string $content, array $replacements)
    {
        $keys = array();
        $values = array();

        foreach ($replacements as $key => $value) {
            $keys[] = '[' . $key . ']';

            if (substr($value, 0, 1) === '[' && substr($value, -1) === ']') {
                $values[] =  do_shortcode($value);
            } else {
                $values[] = $value;
            }
        }

        return str_replace($keys, $values, $content);
    }
}
