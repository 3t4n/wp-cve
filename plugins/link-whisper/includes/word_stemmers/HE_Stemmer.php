<?php

/**
 * Class Wpil_Stemmer for Hebrew language
 */
class Wpil_Stemmer {

    static $stemmer = null;

    //Hebrew words can not be stemmed, this is just a compatibility wrapper
    public static function Stem($word, $deaccent = false, $ignore_cache = false){
        return $word;
    }
}