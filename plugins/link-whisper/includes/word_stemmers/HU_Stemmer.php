<?php


class Wpil_Stemmer {
    static $stem_cache = array();
    static $p1;

    /* special characters (in ISO Latin I) */
    static $a1 = 'á';  //a-acute
    static $e1 = 'é';  //e-acute
    static $i1 = 'í';  //i-acute
    static $o1 = 'ó';  //o-acute
    static $o2 = 'ö';  //o-umlaut
    static $oq = 'õ';  //o-double acute
    static $u1 = 'ú';  //u-acute
    static $u2 = 'ü';  //u-umlaut
    static $uq = 'û';  //u-double acute

    static $v = array('a', 'e', 'i', 'o', 'u', 'á', 'é', 'í', 'ó', 'ö', 'õ', 'ú', 'ü', 'û');

    static $consonants = array('b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z');

    public static function mark_regions($word){

        // set p1 as the limit (strlen) as a default
        self::$p1 = mb_strlen($word);

        $digraphs = array(
            'cs',
            'gy',
            'ly',
            'ny',
            'sz',
            'ty',
            'zs',
            'dzs'
        );

        // if the word begins with a vowel
        if(in_array(mb_substr($word, 0, 1), self::$v, true)){
            // remove the leading vowels so we can find the first consonant
            $temp_word = ltrim($word, 'aeiouáéíóöõúüû');

            // if there are not any
            if(empty($temp_word)){
                // set the offset to zero
                self::$p1 = 0;
                // and exit here
                return;
            }

            // see if it's followed by a digraph
            foreach($digraphs as $digraph){
                if(0 === mb_stripos($temp_word, $digraph)){
                    // set the word offset for right after the digraph
                    self::$p1 = mb_stripos($word, $digraph) + mb_strlen($digraph);
                    // and exit
                    return;
                }
            }

            // see if the vowel is followed with a consonant
            $maybe_c = mb_substr($temp_word, 0, 1);
            if(in_array($maybe_c, self::$consonants, true)){
                // if it is, set the position for just after the consonant
                self::$p1 = mb_stripos($word, $maybe_c) + 1;
                // and exit
            }

        }elseif(in_array(mb_substr($word, 0, 1), self::$consonants, true)){ // if the word begins with a consonant
            // remove the leading consonants
            $temp_word = ltrim($word, implode(self::$consonants));

            // if there are not any
            if(empty($temp_word)){
                // set the offset to zero
                self::$p1 = 0;
                // and exit here
                return;
            }

            // make sure the next letter is a vowel
            $maybe_v = mb_substr($temp_word, 0, 1);
            if(in_array($maybe_v, self::$v, true)){
                // if it is, grab it's position
                self::$p1 = mb_stripos($word, $maybe_v) + 1;
            }
        }
    }


    public static function v_ending($word){
        // get the word length
        $length = mb_strlen($word);

        // create the list of word endings to replace
        $strings = array(
            'á' => 'a',
            'é' => 'e'
        );

        foreach($strings as $s_key => $s_replace){
            // if this is a string replace and the string key is in the word
            if(false !== stripos($word, $s_key)){
                $key_len = mb_strlen($s_key);
                $key_pos = mb_strripos($word, $s_key, self::$p1);

                // see if the key comes at the end of the word by adding the key pos and it's length and seeing if that equals the word length
                if(($key_len + $key_pos) === $length){
                    // if it does, remove the ending
                    $word = mb_substr($word, 0, $key_pos);
                    // add the new ending
                    $word = ($word . $s_replace);
                    // and exit the loop
                    break;
                }
            }
        }

        return $word;
    }

    /**
     * Checks to see if there's a double consonant in the word.
     * Checks from right to left so we get the last occurance.
     * @return int|bool Returns the position if there is a double consonant, and false if there isn't.
     **/
    public static function double($word, $pos = 0){
        $strings = array(
            'bb', 
            'cc', 
            'ccs', 
            'dd', 
            'ff', 
            'gg', 
            'ggy', 
            'jj', 
            'kk', 
            'll', 
            'lly', 
            'mm',
            'nn', 
            'nny', 
            'pp', 
            'rr', 
            'ss', 
            'ssz', 
            'tt', 
            'tty', 
            'vv', 
            'zz', 
            'zzs'
        );

        // remove the part of the word that would come after the double
        $word_part = mb_substr($word, 0, $pos);

        // get the length of the resulting word
        $length = mb_strlen($word_part);

        foreach($strings as $string){
            if(false !== stripos($word_part, $string)){
                $key_len = mb_strlen($string);
                $key_pos = mb_strripos($word_part, $string); // don't need the offset since we've removed the ending already

                // see if the key comes at the end of the word by adding the key pos and it's length and seeing if that equals the word length
                if(($key_len + $key_pos) === $length){
                    // if it does, return the position of the double
                    return $key_pos;
                }
            }
        }

        return false;
    }

    public static function undouble($word, $offset = 0){
        // get the word length
        $length = mb_strlen($word);

        $strings = array(
            'bb' => 'b',
            'cc' => 'c',
            'ccs' => 'cs',
            'dd' => 'd',
            'ff' => 'f',
            'gg' => 'g',
            'ggy' => 'gy',
            'jj' => 'j',
            'kk' => 'k',
            'll' => 'l',
            'lly' => 'ly',
            'mm' => 'm',
            'nn' => 'n',
            'nny' => 'ny',
            'pp' => 'p',
            'rr' => 'r',
            'ss' => 's',
            'ssz' => 'sz',
            'tt' => 't',
            'tty' => 'ty',
            'vv' => 'v',
            'zz' => 'z',
            'zzs' => 'zs',
        );

        foreach($strings as $s_key => $s_replace){
            if(false !== stripos($word, $s_key)){
                $key_len = mb_strlen($s_key);
                $key_pos = mb_strripos($word, $s_key, $offset);

                // see if the key comes at the end of the word by adding the key pos and it's length and seeing if that equals the word length
                if(($key_len + $key_pos) === $length){
                    // if it does, remove the ending
                    $word = mb_substr($word, 0, $key_pos);
                    // add the new ending
                    $word = ($word . $s_replace);
                    // and exit the loop
                    break;
                }
            }
        }

        return $word;
    }

    public static function instrum($word){
        if(empty(self::$p1)){ // todo I _think_ this is correct, test to make sure
            return $word;
        }

        // create the list of word endings to replace
        $strings = array(
            'al' => '',
            'el' => ''
        );

        // get the length of the word
        $length = mb_strlen($word);

        foreach($strings as $s_key => $s_replace){
            // if this is a string replace and the string key is in the word
            if(false !== stripos($word, $s_key)){
                $key_len = mb_strlen($s_key);
                $key_pos = mb_strripos($word, $s_key, self::$p1);
                // see if the key comes at the end of the word by adding the key pos and it's length and seeing if that equals the word length
                if(($key_len + $key_pos) === $length){
                    // check if a doubled consonant comes before the key
                    $pos = self::double($word, $key_pos);
                    if(false !== $pos){
                        // if it does delete the words that come after the double
                        $word = mb_substr($word, 0, $key_pos);
                        // and undouble the word
                        $word = self::undouble($word, $pos);
                    }
                }
            }
        }

        return $word;

    }


    public static function case($word){
        // create the list of word endings to replace
        $strings = array(
            'ban' => '',
            'ben' => '',
            'ba' => '',
            'be' => '',
            'ra' => '',
            're' => '',
            'nak' => '',
            'nek' => '',
            'val' => '',
            'vel' => '',
            'tól' => '',
            'tõl' => '',
            'ról' => '',
            'rõl' => '',
            'ból' => '',
            'bõl' => '',
            'hoz' => '',
            'hez' => '',
            'höz' => '',
            'nál' => '',
            'nél' => '',
            'ig' => '',
            'at' => '',
            'et' => '',
            'ot' => '',
            'öt' => '',
            'ért' => '',
            'képp' => '',
            'képpen' => '',
            'kor' => '',
            'ul' => '',
            'ül' => '',
            'vá' => '',
            'vé' => '',
            'onként' => '',
            'enként' => '',
            'anként' => '',
            'ként' => '',
            'en' => '',
            'on' => '',
            'an' => '',
            'ön' => '',
            'n' => '',
            't' => '',
        );

        // get the word length
        $length = mb_strlen($word);

        foreach($strings as $s_key => $s_replace){
            // if this is a string replace and the string key is in the word
            if(false !== stripos($word, $s_key)){
                $key_len = mb_strlen($s_key);
                $key_pos = mb_strripos($word, $s_key, self::$p1);

                // see if the key comes at the end of the word by adding the key pos and it's length and seeing if that equals the word length
                if(($key_len + $key_pos) === $length){
                    // if it does, remove the ending
                    $word = mb_substr($word, 0, $key_pos);
                    // and exit the loop
                    break;
                }
            }
        }

        $word = self::v_ending($word);

        return $word;
    }

    public static function case_special($word){
        // get the word length
        $length = mb_strlen($word);

        // create the list of word endings to replace
        $strings = array(
            'ánként' => 'a',
            'én' => 'e',
            'án' => 'a',
        );

        foreach($strings as $s_key => $s_replace){
            // if this is a string replace and the string key is in the word
            if(false !== stripos($word, $s_key)){
                $key_len = mb_strlen($s_key);
                $key_pos = mb_strripos($word, $s_key, self::$p1);

                // see if the key comes at the end of the word by adding the key pos and it's length and seeing if that equals the word length
                if(($key_len + $key_pos) === $length){
                    // if it does, remove the ending
                    $word = mb_substr($word, 0, $key_pos);
                    // add the new ending
                    $word = ($word . $s_replace);
                    // and exit the loop
                    break;
                }
            }
        }

        return $word;
    }

    public static function case_other($word){
        // get the word length
        $length = mb_strlen($word);

        // create the list of word endings to replace
        $strings = array(
            'astul' => '',
            'estül' => '',
            'ástul' => 'a',
            'éstül' => 'e',
            'stul' => '',
            'stül' => '',
        );

        foreach($strings as $s_key => $s_replace){
            // if this is a string replace and the string key is in the word
            if(false !== stripos($word, $s_key)){
                $key_len = mb_strlen($s_key);
                $key_pos = mb_strripos($word, $s_key, self::$p1);

                // see if the key comes at the end of the word by adding the key pos and it's length and seeing if that equals the word length
                if(($key_len + $key_pos) === $length){
                    // if it does, remove the ending
                    $word = mb_substr($word, 0, $key_pos);
                    // add the new ending
                    $word = ($word . $s_replace);
                    // and exit the loop
                    break;
                }
            }
        }

        return $word;
    }

    public static function factive($word){
        if(empty(self::$p1)){ // todo I _think_ this is correct, test to make sure
            return $word;
        }

        // create the list of word endings to replace
        $strings = array(
            'á' => '',
            'é' => ''
        );

        // get the length of the word
        $length = mb_strlen($word);

        foreach($strings as $s_key => $s_replace){
            // if this is a string replace and the string key is in the word
            if(false !== stripos($word, $s_key)){
                $key_len = mb_strlen($s_key);
                $key_pos = mb_strripos($word, $s_key, self::$p1);

                // see if the key comes at the end of the word by adding the key pos and it's length and seeing if that equals the word length
                if(($key_len + $key_pos) === $length){
                    // check if a doubled consonant comes after the key
                    $pos = self::double($word, $key_pos);
                    if(false !== $pos){
                        // if it does delete the letters that come after the double
                        $word = mb_substr($word, 0, $pos);
                        // and undouble the word
                        $word = self::undouble($word, $key_pos);
                    }
                }
            }
        }

        return $word;
    }

    public static function plural($word){
        // get the word length
        $length = mb_strlen($word);

        // create the list of word endings to replace
        $strings = array(
            'ák' => 'a',
            'ék' => 'e',
            'ök' => '',
            'ak' => '',
            'ok' => '',
            'ek' => '',
            'k' => ''
        );

        foreach($strings as $s_key => $s_replace){
            // if this is a string replace and the string key is in the word
            if(false !== stripos($word, $s_key)){
                $key_len = mb_strlen($s_key);
                $key_pos = mb_strripos($word, $s_key, self::$p1);

                // see if the key comes at the end of the word by adding the key pos and it's length and seeing if that equals the word length
                if(($key_len + $key_pos) === $length){
                    // if it does, remove the ending
                    $word = mb_substr($word, 0, $key_pos);
                    // add the new ending
                    $word = ($word . $s_replace);
                    // and exit the loop
                    break;
                }
            }
        }

        return $word;
    }

    public static function owned($word){
        // get the word length
        $length = mb_strlen($word);

        // create the list of word endings to replace
        $strings = array(
            'oké' => '',
            'öké' => '',
            'aké' => '',
            'eké' => '',
            'éké' => 'e',
            'áké' => 'a',
            'ké' => '',
            'ééi' => 'e',
            'áéi' => 'a',
            'éi' => '',
            'éé' => 'e',
            'é' => ''
        );

        foreach($strings as $s_key => $s_replace){
            // if this is a string replace and the string key is in the word
            if(false !== stripos($word, $s_key)){
                $key_len = mb_strlen($s_key);
                $key_pos = mb_strripos($word, $s_key, self::$p1);

                // see if the key comes at the end of the word by adding the key pos and it's length and seeing if that equals the word length
                if(($key_len + $key_pos) === $length){
                    // if it does, remove the ending
                    $word = mb_substr($word, 0, $key_pos);
                    // add the new ending
                    $word = ($word . $s_replace);
                    // and exit the loop
                    break;
                }
            }
        }

        return $word;
    }

    public static function sing_owner($word){
        // get the word length
        $length = mb_strlen($word);

        // if the word length is shorter than the $p1, exit
        if($length < self::$p1){
            return $word;
        }

        // create the list of word endings to replace
        $strings = array(
            'ünk' => '',
            'unk' => '',
            'ánk' => 'a',
            'énk' => 'e',
            'nk' => '',
            'ájuk' => 'a',
            'éjük' => 'e',
            'juk' => '',
            'jük' => '',
            'uk' => '',
            'ük' => '',
            'em' => '',
            'om' => '',
            'am' => '',
            'ám' => 'a',
            'ém' => 'e',
            'm' => '',
            'od' => '',
            'ed' => '',
            'ad' => '',
            'öd' => '',
            'ád' => 'a',
            'éd' => 'e',
            'd' => '',
            'ja' => '',
            'je' => '',
            'a' => '',
            'e' => '',
            'o' => '',
            'á' => 'a',
            'é' => 'e',
        );

        foreach($strings as $s_key => $s_replace){
            // if this is a string replace and the string key is in the word
            if(false !== stripos($word, $s_key)){
                $key_len = mb_strlen($s_key);
                $key_pos = mb_strripos($word, $s_key, self::$p1);

                // see if the key comes at the end of the word by adding the key pos and it's length and seeing if that equals the word length
                if(($key_len + $key_pos) === $length){
                    // if it does, remove the ending
                    $word = mb_substr($word, 0, $key_pos);
                    // add the new ending
                    $word = ($word . $s_replace);
                    // and exit the loop
                    break;
                }
            }
        }

        return $word;
    }

    public static function plur_owner($word){
        // get the word length
        $length = mb_strlen($word);

        // create the list of word endings to replace
        $strings = array(
            'jaim' => '',
            'jeim' => '',
            'áim' => 'a',
            'éim' => 'e',
            'aim' => '',
            'eim' => '',
            'im' => '',
            'jaid' => '',
            'jeid' => '',
            'áid' => 'a',
            'éid' => 'e',
            'aid' => '',
            'eid' => '',
            'id' => '',
            'jai' => '',
            'jei' => '',
            'ái' => 'a',
            'éi' => 'e',
            'ai' => '',
            'ei' => '',
            'i' => '',
            'jaink' => '',
            'jeink' => '',
            'eink' => '',
            'aink' => '',
            'áink' => 'a',
            'éink' => 'e',
            'ink' => '',
            'jaitok' => '',
            'jeitek' => '',
            'aitok' => '',
            'eitek' => '',
            'áitok' => 'a',
            'éitek' => 'e',
            'itek' => '',
            'jeik' => '',
            'jaik' => '',
            'aik' => '',
            'eik' => '',
            'áik' => 'a',
            'éik' => 'e',
            'ik' => '',
        );

        foreach($strings as $s_key => $s_replace){
            // if this is a string replace and the string key is in the word
            if(false !== stripos($word, $s_key)){
                $key_len = mb_strlen($s_key);
                $key_pos = mb_strripos($word, $s_key, self::$p1);

                // see if the key comes at the end of the word by adding the key pos and it's length and seeing if that equals the word length
                if(($key_len + $key_pos) === $length){
                    // if it does, remove the ending
                    $word = mb_substr($word, 0, $key_pos);
                    // add the new ending
                    $word = ($word . $s_replace);
                    // and exit the loop
                    break;
                }
            }
        }

        return $word;
    }

    public static function Stem($word, $deaccent = false, $ignore_cache = false){
        // first check if we've already stemmed the word
        $cached = self::get_cached_stem($word);
        if(!empty($cached)){
            // if we have return the cached
            return $cached;
        }

        // if it's not cached, stemm the word
        $original_word = $word;
        self::mark_regions($word);
        $word = self::instrum($word);
        $word = self::case($word);
        $word = self::case_special($word);
        $word = self::case_other($word);
        $word = self::factive($word);
        $word = self::owned($word);
        $word = self::sing_owner($word);
        $word = self::plur_owner($word);
        $word = self::plural($word);

        // and update the cache with the stemmed word
        self::update_cached_stem($original_word, $word);

        return $word;
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