<?php

namespace Fab\Helper;

!defined( 'WPINC ' ) or die;

/**
 * Helper library for Fab plugins
 *
 * @package    Fab
 * @subpackage Fab\Includes
 */

trait Text {

    /**
     * Slugify
     */
    public function slugify($text){
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        if (empty($text)) { return 'n-a'; }
        return $text;
    }

    /**
     * Check string is json
     */
    public function isJson($string){
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

}