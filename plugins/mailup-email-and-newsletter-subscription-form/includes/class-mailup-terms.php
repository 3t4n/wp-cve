<?php

declare(strict_types=1);

class Mailup_Terms
{
    public static function getTerms($args)
    {
        return array_map(
            static function ($term) {
                return (object) [
                    'id' => $term['id'],
                    'show' => filter_var($term['show'], FILTER_VALIDATE_BOOLEAN),
                    'required' => filter_var($term['required'], FILTER_VALIDATE_BOOLEAN),
                    'text' => wp_kses_post(stripslashes($term['text'])) ?? '',
                ];
            },
            $args
        );
    }
}
