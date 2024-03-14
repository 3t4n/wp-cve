<?php
/**
 * Polish Stemmer
 *
 * Class Wpil_Stemmer
 */

class Wpil_Stemmer
{
    const VOWELS = ["a", "i", "e", "o", "u", "y"];

    const SUFFIXES = ["dziesiatko", "dziesiatce", "dziesiatka", "dziesiatke", "dziesiatki", "ynascioro", "anascioro", "enascioro", "nascioro", "dziesiat", "dziescia", "dziesci", "nastke", "nastka", "nastki", "iescie", "nastko", "nascie", "nastce", "iunio", "uszek", "iuset", "escie", "eczka", "yczek", "eczko", "iczek", "eczek", "setka", "iema", "iemu", "ysta", "ioma", "owie", "owym", "iego", "usia", "giem", "unia", "iami", "unio", "usui", "owi", "ych", "szy", "ema", "emu", "óch", "ymi", "ami", "set", "ich", "ech", "iom", "imi", "ach", "oma", "owa", "owe", "owy", "iem", "ego", "ym", "um", "yk", "om", "ow", "us", "im", "om", "ek", "ej", "ga", "gu", "ia", "ie", "aj", "ik", "iu", "ka", "ki", "ko", "mi", "em", "ce", "a", "e", "i", "y", "o", "u"];

    const MAP = ["ą" => "a", "ę" => "e", "ó" => "o", "ś" => "s", "ł" => "l", "ż" => "z", "ź" => "z", "ć" => "c", "ń" => "n"];

    public static $encoding = "UTF-8";
    static $stem_cache = array();

    public static function filter($word){
        $word = mb_strtolower($word, self::$encoding);
        $word = strtr($word, self::MAP);
        if(strlen($word) and ctype_alpha($word)) {
            return $word;
        }
        return null;
    }

    public static function Stem($word, $deaccent = false, $ignore_cache = false){
        $fword = self::filter($word);
        if($fword){
            $wl = mb_strlen($fword, self::$encoding);
            foreach (self::SUFFIXES as $suffix) {
                $l = mb_strlen($suffix, self::$encoding);
                if ($l > $wl) {
                    continue;
                } else {
                    $last_part = mb_substr($fword, -$l, null, self::$encoding);
                    if ($last_part == $suffix) {
                        $one_before_last_part = mb_substr($fword, -$l - 1, 1, self::$encoding);
                        if (!in_array($one_before_last_part, self::VOWELS)) {
                            return mb_substr($fword, 0, $wl - $l, self::$encoding);
                        }
                    }
                }

            }
            return $fword;
        }
        return null;
    }
}