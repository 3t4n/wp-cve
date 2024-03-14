<?php
class Wpil_Stemmer {

    static $stem_cache = array();

    /*
     * Stem an input buffer of Slovak text.
     *
     * @param s input buffer
     * @param len length of input buffer
     * @return length of input buffer after normalization
     *
     * <p><b>NOTE</b>: Input is expected to be in lowercase,
     * but with diacritical marks</p>
     */
    public static function Stem($word, $deaccent = false, $ignore_cache = false) {
        // if the wamania project is active, use their strtolower function
        if(class_exists('Wamania\\Snowball\\Utf8')){
            $word = Wamania\Snowball\Utf8::strtolower($word);
        }else{
            $word = strtolower($word);
        }

        // check if we've already stemmed the word
        $cached = self::get_cached_stem($word);
        if(!empty($cached)){
            // if we have, return the cached
            return $cached;
        }

        $original_word = $word;

        $word_length = mb_strlen($word);
        $word = self::removeCase($word, $word_length);
        $word = self::removePossessives($word, $word_length);
        $word = self::removePrefixes($word, $word_length);

        // and update the cache with the (hopefully) stemmed word
        self::update_cached_stem($original_word, $word);

        return $word;
    }

    private static function removePrefixes($word, $word_length) {
        if ($word_length > 5 && self::starts_with($word, "naj")) {
            return mb_substr($word, 3);
        }
        return $word;
    }

    private static function removeCase($word, $word_length) {
        if ($word_length > 7 && self::ends_with($word, $word_length, "atoch")) {
            return mb_substr($word, 0, $word_length - 5);
        }

        if ($word_length > 6 && self::ends_with($word, $word_length, "aťom")) {
            return self::palatalize(mb_substr($word, 0, $word_length - 3), $word_length - 3);
        }

        if ($word_length > 5) {
            if (self::ends_with($word, $word_length, "och") ||
                self::ends_with($word, $word_length, "ich") ||
                self::ends_with($word, $word_length, "ích") ||
                self::ends_with($word, $word_length, "ého") ||
                self::ends_with($word, $word_length, "ami") ||
                self::ends_with($word, $word_length, "emi") ||
                self::ends_with($word, $word_length, "ému") ||
                self::ends_with($word, $word_length, "ete") ||
                self::ends_with($word, $word_length, "eti") ||
                self::ends_with($word, $word_length, "iho") ||
                self::ends_with($word, $word_length, "ího") ||
                self::ends_with($word, $word_length, "ími") ||
                self::ends_with($word, $word_length, "imu") ||
                self::ends_with($word, $word_length, "aťa")) {
                return self::palatalize(mb_substr($word, 0, $word_length - 2), $word_length - 2);
            }
            if (self::ends_with($word, $word_length, "ách") ||
                self::ends_with($word, $word_length, "ata") ||
                self::ends_with($word, $word_length, "aty") ||
                self::ends_with($word, $word_length, "ých") ||
                self::ends_with($word, $word_length, "ami") ||
                self::ends_with($word, $word_length, "ové") ||
                self::ends_with($word, $word_length, "ovi") ||
                self::ends_with($word, $word_length, "ými")) {
                return mb_substr($word, 0, $word_length - 3);
            }
        }

        if ($word_length > 4) {
            if (self::ends_with($word, $word_length, "om")) {
                return self::palatalize(mb_substr($word, 0, $word_length - 1), $word_length - 1);
            }
            if (self::ends_with($word, $word_length, "es") ||
                self::ends_with($word, $word_length, "ém") ||
                self::ends_with($word, $word_length, "ím")) {
                return self::palatalize(mb_substr($word, 0, $word_length - 2), $word_length - 1);
            }
            if (self::ends_with($word, $word_length, "úm") ||
                self::ends_with($word, $word_length, "at") ||
                self::ends_with($word, $word_length, "ám") ||
                self::ends_with($word, $word_length, "os") ||
                self::ends_with($word, $word_length, "us") ||
                self::ends_with($word, $word_length, "ým") ||
                self::ends_with($word, $word_length, "mi") ||
                self::ends_with($word, $word_length, "ou") ||
                self::ends_with($word, $word_length, "ej")) {
                return mb_substr($word, 0, $word_length - 2);
            }
        }

        if ($word_length > 3) {
            $last_letter = mb_substr($word, $word_length - 1);
            switch ($last_letter) {
                case 'e':
                case 'i':
                case 'í':
                    return self::palatalize($word, $word_length);
                case 'ú':
                case 'y':
                case 'a':
                case 'o':
                case 'á':
                case 'é':
                case 'ý':
                    return mb_substr($word, 0, $word_length - 1);
                default:
            }
        }

        return $word;
    }

    private static function removePossessives($word, $word_length) {
        if ($word > 5) {
            if (self::ends_with($word, $word_length, "ov")) {
                return mb_substr($word, 0, $word_length - 2);
            }
            if (self::ends_with($word, $word_length, "in")) {
                return self::palatalize(mb_substr($word, 0, $word_length - 1), $word_length - 1);
            }
        }

        return $word;
    }

    private static function palatalize($word = '', $word_length = 0) {
        $word_parts = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);

        if (self::ends_with($word, $word_length, "ci") ||
            self::ends_with($word, $word_length, "ce") ||
            self::ends_with($word, $word_length, "či") ||
            self::ends_with($word, $word_length, "če")) { // [cč][ie] -> k
                $word_parts[$word_length - 2] = 'k';
        } else if (self::ends_with($word, $word_length, "zi") ||
            self::ends_with($word, $word_length, "ze") ||
            self::ends_with($word, $word_length, "ži") ||
            self::ends_with($word, $word_length, "že")) { // [zž][ie] -> h
                $word_parts[$word_length - 2] = 'h';
        } else if (self::ends_with($word, $word_length, "čte") ||
            self::ends_with($word, $word_length, "čti") ||
            self::ends_with($word, $word_length, "čtí")) { // čt[eií] -> ck
                $word_parts[$word_length - 3] = 'c';
                $word_parts[$word_length - 2] = 'k';
        } else if (self::ends_with($word, $word_length, "šte") ||
            self::ends_with($word, $word_length, "šti") ||
            self::ends_with($word, $word_length, "ští")) { // št[eií] -> sk
                $word_parts[$word_length - 3] = 's';
                $word_parts[$word_length - 2] = 'k';
        }

        array_splice($word_parts, -1, 1);
    
        return implode('', $word_parts);
    }


    public static function starts_with($word, $string){
        if(0 === mb_stripos($word, $string)){
            return true;
        }

        return false;
    }

    public static function ends_with($word, $length, $string){
        if(((int)mb_strpos($word, $string, 0) + mb_strlen($string)) === $length){
            return true;
        }else{
            return false;
        }
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