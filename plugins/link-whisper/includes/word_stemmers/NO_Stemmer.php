<?php
/**
 * Utility wrapper for interfacing Link Whisper's existing word stemming code with
 * the Wamania php-stemmer project.
 * 
 * https://github.com/wamania/php-stemmer
 * 
 **/

use Wamania\Snowball\Norwegian;

class Wpil_Stemmer {

    static $stemmer = null;
    static $stem_cache = array();
    
    public static function Stem($word, $deaccent = false, $ignore_cache = false){
        if(is_null(self::$stemmer)){
            self::$stemmer = new Norwegian();
        }

        // lowercase the word
        $word = Wamania\Snowball\Utf8::strtolower($word);

        // check if we've already stemmed the word
        $cached = self::get_cached_stem($word);
        if(!empty($cached)){
            // if we have, return the cached
            return $cached;
        }

        $original_word = $word;

        // if the current word is not in UTF-8 (or contains a letter the stemmer can't deal with...)
        if(!Wamania\Snowball\Utf8::check($word)){

            // try processing the text
            $converted_word = self::codes_to_chars($word);

            // if we were able to process the text into a useable form
            if(Wamania\Snowball\Utf8::check($converted_word)){
                // stem and return the word
                $word = self::$stemmer->stem($converted_word);
            }
        }else{
            $word = self::$stemmer->stem($word);
        }

        // and update the cache with the (hopefully) stemmed word
        self::update_cached_stem($original_word, $word);

        return $word;
    }
    
    public static function codes_to_chars($string){
        
        // create a fallback conversion table in case html_entity_decode runs into trouble
        $conversion_table = array(
            "&#2013266112;" =>"À",
            "&#2013266113;" =>"Á", // in testing, was converted to &#2013265923;
            "&#2013266114;" =>"Â",
            "&#2013266115;" =>"Ã",
            "&#2013266116;" =>"Ä",
            "&#2013266117;" =>"Å",
            "&#2013266118;" =>"Æ",
            "&#2013266119;" =>"Ç",
            "&#2013266120;" =>"È",
            "&#2013266121;" =>"É",
            "&#2013266122;" =>"Ê", // in testing, was converted to &#2013266138;
            "&#2013266123;" =>"Ë",
            "&#2013266140;" =>"Ì",
            "&#2013266141;" =>"Í", // in testing, was converted to &#2013265923;
            "&#2013266142;" =>"Î",
            "&#2013266143;" =>"Ï", // in testing, was converted to &#2013265923;
            "&#2013266129;" =>"Ñ",
            "&#2013266130;" =>"Ò",
            "&#2013266131;" =>"Ó",
            "&#2013266132;" =>"Ô",
            "&#2013266133;" =>"Õ",
            "&#2013266134;" =>"Ö",
            "&#2013266136;" =>"Ø",
            "&#2013266137;" =>"Ù",
            "&#2013266138;" =>"Ú",
            "&#2013266139;" =>"Û",
            "&#2013266140;" =>"Ü",
            "&#2013265923;" =>"Ý", // in testing, was converted to &#2013265923;
            "\u00df" =>"ß",        // in testing, gets encoded as "&#2013265923;&#2013266175;" 
            "&#2013266144;" =>"à", // in testing, was converted to &#2013265923;
            "&#2013266145;" =>"á",
            "&#2013266146;" =>"â",
            "&#2013266147;" =>"ã",
            "&#2013266148;" =>"ä",
            "&#2013266149;" =>"å",
            "&#2013266150;" =>"æ",
            "&#2013266151;" =>"ç",
            "&#2013266152;" =>"è",
            "&#2013266153;" =>"é",
            "&#2013266154;" =>"ê",
            "&#2013266155;" =>"ë",
            "&#2013266156;" =>"ì",
            "&#2013266157;" =>"í", // in testing, was converted to &#2013265923;
            "&#2013266158;" =>"î",
            "&#2013266159;" =>"ï",
            "&#2013266160;" =>"ð",
            "&#2013266161;" =>"ñ",
            "&#2013266162;" =>"ò",
            "&#2013266163;" =>"ó",
            "&#2013266164;" =>"ô",
            "&#2013266165;" =>"õ",
            "&#2013266166;" =>"ö",
            "&#2013266168;" =>"ø",
            "&#2013266169;" =>"ù",
            "&#2013266170;" =>"ú",
            "&#2013266171;" =>"û",
            "&#2013266172;" =>"ü",
            "&#2013266173;" =>"ý",
            "&#2013266175;" =>"ÿ",
            "\u2019" =>"’"
        );

        // convert the string into html entities, then decode the entities.
        $string = str_replace(array_keys($conversion_table), array_values($conversion_table), html_entity_decode(mb_convert_encoding($string, "HTML-ENTITIES")));

        return $string;
    }

    /**
     * Checks to see if the word was previously stemmed and is in the stem cache.
     * If it is in the cache, it returns the cached word so we don't have to run through the process again.
     * Returns false if the word hasn't been stemmed yet, or the "word" isn't a word
     **/
    public static function get_cached_stem($word = ''){
        if(empty($word) || !isset(self::$stem_cache[$word]) || !is_string($word)){
            return false;
        }

        return self::$stem_cache[$word];
    }

    /**
     * Updates the stemmed word cache when we come across a word that we haven't stemmed yet.
     * Also does some housekeeping to make sure the cache doesn't grow too big
     **/
    public static function update_cached_stem($word, $stemmed_word){
        if(empty($word) || empty($stemmed_word) || isset(self::$stem_cache[$word]) || !is_string($word)){
            return false;
        }

        self::$stem_cache[$word] = $stemmed_word;

        if(count(self::$stem_cache) > 25000){
            $ind = key(self::$stem_cache);
            unset(self::$stem_cache[$ind]);
        }
    }
}

?>
