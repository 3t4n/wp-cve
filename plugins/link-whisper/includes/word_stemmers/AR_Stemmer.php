<?php

/******************************************************************************************
* ARLSTem: is a free Arabic light stemmer that is based on some rules for stripping affixes.
*
* K. Abainia, S. Ouamour and H. Sayoud, "A Novel Robust Arabic Light Stemmer", International
* Journal of Experimental & Theoretical Artificial Intelligence (Taylor & Francis).
* DOI:10.1080/0952813X.2016.1212100
******************************************************************************************/
/*****************************************************************************************
 * Basic PHP code is from https://github.com/xprogramer/Arabic-Stemmers and is licenesed under GPL 3.0
 * Link Whisper modifications use default Link Whisper license
 *****************************************************************************************/

class Wpil_Stemmer {

    static $m_word; // array of utf-8 characters
    static $stem_cache = array();

    public static function Stem($word, $deaccent = false, $ignore_cache = false) {
        if(empty(mb_strlen($word, 'UTF-8'))){
            return $word;
        }

        // check if we've already stemmed the word
        $cached = self::get_cached_stem($word);
        if(!empty($cached)){
            // if we have, return the cached
            return $cached;
        }

        $original_word = $word;

        self::ARLSTem($word);
        
        $word = self::getStem();

        // and update the cache with the (hopefully) stemmed word
        self::update_cached_stem($original_word, $word);

        return $word;
    }
        
    public static function ARLSTem($str){
        // split the utf-8 string into an array of characters
        $arr = array();
        $strLen = mb_strlen($str, 'UTF-8');
        for ($i = 0; $i < $strLen; $i++)
        {
            $arr[] = mb_substr($str, $i, 1, 'UTF-8');
        }
        // keep the characters array of the word
        self::$m_word = $arr;
    }

    public static function getStem()
    {
        self::normalize();
        $b1 = self::deletePrefixes();
        $b2 = self::deleteSuffixes();
        if(!self::pluralToSingular()) if(!self::feminineToMasculine()) if(!$b1) self::verbStemming();
        
        return self::getWord();
    }

    public static function getWord(){
        $temp='';
        $nb_chars = count(self::$m_word);
        // concatenate the characters array in a single string
        for($i=0; $i<$nb_chars; $i++) $temp .= self::$m_word[$i];
        
        return $temp;
    }

    private static function normalize(){
        $len = count(self::$m_word);
        for($i=0; $i<$len; $i++)
        {
            // replace Hamzated Alif with Alif bare
            if(self::utf8_char_equal(self::$m_word[$i], 0xD8A2) || 
                self::utf8_char_equal(self::$m_word[$i], 0xD8A3) || 
                self::utf8_char_equal(self::$m_word[$i], 0xD8A5))
            {
                self::utf8_replace_char(self::$m_word[$i], 0xA7D8);
            }
            // replace Alif MaqSura with Yaa
            if(self::utf8_char_equal(self::$m_word[$i], 0xD989))
            {
                self::utf8_replace_char(self::$m_word[$i], 0x8AD9);
            }
        }
        // remove the Waaw from the beginning if the remaining is 4 characters at least
        if($len >= 4 && self::utf8_char_equal(self::$m_word[0], 0xD988))
        {
            self::$m_word = array_slice(self::$m_word,1,$len);
        }
    }

    private static function feminineToMasculine(){
        $len = count(self::$m_word);
        // remove the taaMarbuta at the end if the remaining is 4 characters at least
        if($len >= 4 && self::utf8_char_equal(self::$m_word[$len-1], 0xD8A9))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-1);
            return true;
        }
        return false;
    }

    private static function deletePrefixes(){
        $len = count(self::$m_word);
        // remove baa, alif and laam from the beginning if the remaining is 3 characters at least
        if($len >= 6 && self::utf8_char_equal(self::$m_word[0], 0xD8A8) &&
                        self::utf8_char_equal(self::$m_word[1], 0xD8A7) &&
                        self::utf8_char_equal(self::$m_word[2], 0xD984))
        {
            self::$m_word = array_slice(self::$m_word,3,$len);
            return true;
        }
        // remove kaaf, alif and laam from the beginning if the remaining is 3 characters at least
        if($len >= 6 && self::utf8_char_equal(self::$m_word[0], 0xD983) &&
                        self::utf8_char_equal(self::$m_word[1], 0xD8A7) &&
                        self::utf8_char_equal(self::$m_word[2], 0xD984))
        {
            self::$m_word = array_slice(self::$m_word,3,$len);
            return true;
        }
        // remove waaw, alif and laam from the beginning if the remaining is 3 characters at least
        if($len >= 6 && self::utf8_char_equal(self::$m_word[0], 0xD988) &&
                        self::utf8_char_equal(self::$m_word[1], 0xD8A7) &&
                        self::utf8_char_equal(self::$m_word[2], 0xD984))
        {
            self::$m_word = array_slice(self::$m_word,3,$len);
            return true;
        }
        // remove faa, baa, alif and laam from the beginning if the remaining is 3 characters at least
        if($len >= 7 && self::utf8_char_equal(self::$m_word[0], 0xD981) &&
                        self::utf8_char_equal(self::$m_word[1], 0xD8A8) &&
                        self::utf8_char_equal(self::$m_word[2], 0xD8A7) &&
                        self::utf8_char_equal(self::$m_word[3], 0xD984))
        {
            self::$m_word = array_slice(self::$m_word,4,$len);
            return true;
        }
        // remove waaw, baa, alif and laam from the beginning if the remaining is 3 characters at least
        if($len >= 7 && self::utf8_char_equal(self::$m_word[0], 0xD988) &&
                        self::utf8_char_equal(self::$m_word[1], 0xD8A8) &&
                        self::utf8_char_equal(self::$m_word[2], 0xD8A7) &&
                        self::utf8_char_equal(self::$m_word[3], 0xD984))
        {
            self::$m_word = array_slice(self::$m_word,4,$len);
            return true;
        }
        // remove faa, kaaf, alif and laam  from the beginning if the remaining is 3 characters at least
        if($len >= 7 && self::utf8_char_equal(self::$m_word[0], 0xD981) &&
                        self::utf8_char_equal(self::$m_word[1], 0xD983) &&
                        self::utf8_char_equal(self::$m_word[2], 0xD8A7) &&
                        self::utf8_char_equal(self::$m_word[3], 0xD984))
        {
            self::$m_word = array_slice(self::$m_word,4,$len);
            return true;
        }
        // remove faa, laam and laam from the beginning if the remaining is 3 characters at least
        if($len >= 6 && self::utf8_char_equal(self::$m_word[0], 0xD981) &&
                        self::utf8_char_equal(self::$m_word[1], 0xD984) &&
                        self::utf8_char_equal(self::$m_word[2], 0xD984))
        {
            self::$m_word = array_slice(self::$m_word,3,$len);
            return true;
        }
        // remove waa, laam and laam from the beginning if the remaining is 3 characters at least
        if($len >= 6 && self::utf8_char_equal(self::$m_word[0], 0xD988) &&
                        self::utf8_char_equal(self::$m_word[1], 0xD984) &&
                        self::utf8_char_equal(self::$m_word[2], 0xD984))
        {
            self::$m_word = array_slice(self::$m_word,3,$len);
            return true;
        }
        // remove alif and laam from the beginning if the remaining is 3 characters at least
        if($len >= 5 && self::utf8_char_equal(self::$m_word[0], 0xD8A7) &&
                        self::utf8_char_equal(self::$m_word[1], 0xD984))
        {
            self::$m_word = array_slice(self::$m_word,2,$len);
            //self::$m_word = null;
            //self::$m_word = $temp;
            return true;
        }
        // remove laam and laam from the beginning if the remaining is 3 characters at least
        if($len >= 5 && self::utf8_char_equal(self::$m_word[0], 0xD984) &&
                        self::utf8_char_equal(self::$m_word[1], 0xD984))
        {
            self::$m_word = array_slice(self::$m_word,2,$len);
            return true;
        }
        // remove faa and laam from the beginning if the remaining is 3 characters at least
        if($len >= 5 && self::utf8_char_equal(self::$m_word[0], 0xD981) &&
                        self::utf8_char_equal(self::$m_word[1], 0xD984))
        {
            self::$m_word = array_slice(self::$m_word,2,$len);
            return true;
        }
        // remove faa and baa from the beginning if the remaining is 3 characters at least
        if($len >= 5 && self::utf8_char_equal(self::$m_word[0], 0xD981) &&
                        self::utf8_char_equal(self::$m_word[1], 0xD8A8))
        {
            self::$m_word = array_slice(self::$m_word,2,$len);
            return true;
        }
        return false;
    }
        
    private static function deleteSuffixes(){
        $len = count(self::$m_word);
        // remove kaaf at the end if the remaining is 3 characters at least
        if($len >= 4 && self::utf8_char_equal(self::$m_word[$len-1], 0xD983))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-1);
            return true;
        }
        // remove kaaf and yaa at the end if the remaining is 3 characters at least
        if($len >= 5 && self::utf8_char_equal(self::$m_word[$len-2], 0xD983) &&
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD98A))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-2);
            return true;
        }
        // remove kaaf and miim at the end if the remaining is 3 characters at least
        if($len >= 5 && self::utf8_char_equal(self::$m_word[$len-2], 0xD983) &&
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD985))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-2);
            return true;
        }
        // remove kaaf, miim and alif at the end if the remaining is 3 characters at least
        if($len >= 6 && self::utf8_char_equal(self::$m_word[$len-3], 0xD983) &&
                        self::utf8_char_equal(self::$m_word[$len-2], 0xD985) &&
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD8A7))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-3);
            return true;
        }
        // remove kaaf, noon and shedda at the end if the remaining is 3 characters at least
        if($len >= 6 && self::utf8_char_equal(self::$m_word[$len-3], 0xD983) &&
                        self::utf8_char_equal(self::$m_word[$len-2], 0xD986) &&
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD991))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-3);
            return true;
        }
        // remove haa at the end if the remaining is 3 characters at least
        if($len >= 4 && self::utf8_char_equal(self::$m_word[$len-1], 0xD987))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-1);
            return true;
        }
        // remove haa and alif at the end if the remaining is 3 characters at least
        if($len >= 5 && self::utf8_char_equal(self::$m_word[$len-2], 0xD987) &&
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD8A7))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-2);
            return true;
        }
        // remove haa and miim at the end if the remaining is 3 characters at least
        if($len >= 5 && self::utf8_char_equal(self::$m_word[$len-2], 0xD987) &&
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD985))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-2);
            return true;
        }
        // remove haa, miim and alif at the end if the remaining is 3 characters at least
        if($len >= 6 && self::utf8_char_equal(self::$m_word[$len-3], 0xD987) &&
                        self::utf8_char_equal(self::$m_word[$len-2], 0xD985) &&
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD8A7))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-3);
            return true;
        }
        // remove haa, noon and shedda at the end if the remaining is 3 characters at least
        if($len >= 6 && self::utf8_char_equal(self::$m_word[$len-3], 0xD987) &&
                        self::utf8_char_equal(self::$m_word[$len-2], 0xD986) &&
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD991))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-3);
            return true;
        }
        // remove noon and alif at the end if the remaining is 3 characters at least
        if($len >= 5 && self::utf8_char_equal(self::$m_word[$len-2], 0xD986) &&
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD8A7))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-2);
            return true;
        }
        return false;
    }

    private static function pluralToSingular(){
        $len = count(self::$m_word);
        // remove alif and noon if the remaining is 3 characters at least
        if($len >= 5 && self::utf8_char_equal(self::$m_word[$len-2], 0xD8A7) &&
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD986))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-2);
            return true;
        }
        // remove yaa and noon if the remaining is 3 characters at least
        if($len >= 5 && self::utf8_char_equal(self::$m_word[$len-2], 0xD98A) &&
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD986))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-2);
            return true;
        }
        // remove waaw and noon if the remaining is 3 characters at least
        if($len >= 5 && self::utf8_char_equal(self::$m_word[$len-2], 0xD988) &&
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD986))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-2);
            return true;
        }
        // remove alif and taa if the remaining is 3 characters at least
        if($len >= 5 && self::utf8_char_equal(self::$m_word[$len-2], 0xD8A7) &&
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD8AA))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-2);
            return true;
        }
        // remove taa, alif and noon at the end if the remaining is 3 characters at least
        if($len >= 6 && self::utf8_char_equal(self::$m_word[$len-3], 0xD8AA) &&
                        self::utf8_char_equal(self::$m_word[$len-2], 0xD8A7) &&
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD986))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-3);
            return true;
        }
        // remove taa, yaa and noon at the end if the remaining is 3 characters at least
        if($len >= 6 && self::utf8_char_equal(self::$m_word[$len-3], 0xD8AA) &&
                        self::utf8_char_equal(self::$m_word[$len-2], 0xD98A) &&
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD986))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-2);
            return true;
        }
        // remove alif at the third position if it also exists at the first position
        if($len >= 4 && self::utf8_char_equal(self::$m_word[0], 0xD8A7) &&
                        self::utf8_char_equal(self::$m_word[2], 0xD8A7))
        {
            self::$m_word = array_merge(array_slice(self::$m_word,0,2) , array_slice(self::$m_word,3,$len));
            return true;
        }
        // remove alif from the beginning and before the last char
        if($len >= 5 && self::utf8_char_equal(self::$m_word[0], 0xD8A7) &&
                        self::utf8_char_equal(self::$m_word[$len-2], 0xD8A7))
        {
            self::$m_word = array_merge(array_slice(self::$m_word,1,$len-3) , array_slice(self::$m_word,$len-1,$len));
            return true;
        }
        return false;
    }

    private static function verbStemming(){
        $len = count(self::$m_word);
        // remove taa from the beginning # yaa and noon from the end
        if($len >= 6 && self::utf8_char_equal(self::$m_word[0], 0xD8AA) && 
                        self::utf8_char_equal(self::$m_word[$len-2], 0xD98A) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD986))
        {
            self::$m_word = array_slice(self::$m_word,1,$len-3);
            return true;
        }
        // remove taa from the beginning # alif and noon from the end
        if($len >= 6 && self::utf8_char_equal(self::$m_word[0], 0xD8AA) && 
                        self::utf8_char_equal(self::$m_word[$len-2], 0xD8A7) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD986))
        {
            self::$m_word = array_slice(self::$m_word,1,$len-3);
            return true;
        }
        // remove taa from the beginning # waaw and noon from the end
        if($len >= 6 && self::utf8_char_equal(self::$m_word[0], 0xD8AA) && 
                        self::utf8_char_equal(self::$m_word[$len-2], 0xD988) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD986))
        {
            self::$m_word = array_slice(self::$m_word,1,$len-3);
            return true;
        }
        // remove yaa from the beginning # alif and noon from the end
        if($len >= 6 && self::utf8_char_equal(self::$m_word[0], 0xD98A) && 
                        self::utf8_char_equal(self::$m_word[$len-2], 0xD8A7) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD986))
        {
            self::$m_word = array_slice(self::$m_word,1,$len-3);
            return true;
        }
        // remove yaa from the beginning # waaw and noon from the end
        if($len >= 6 && self::utf8_char_equal(self::$m_word[0], 0xD98A) && 
                        self::utf8_char_equal(self::$m_word[$len-2], 0xD988) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD986))
        {
            self::$m_word = array_slice(self::$m_word,1,$len-3);
            return true;
        }
        // remove alif (hamzat wassel) from the beginning # waaw and alif from the end
        if($len >= 6 && self::utf8_char_equal(self::$m_word[0], 0xD8A5) && 
                        self::utf8_char_equal(self::$m_word[$len-2], 0xD988) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD8A7))
        {
            self::$m_word = array_slice(self::$m_word,1,$len-3);
            return true;
        }
        // remove alif from the beginning # waaw and alif from the end
        if($len >= 6 && self::utf8_char_equal(self::$m_word[0], 0xD8A7) && 
                        self::utf8_char_equal(self::$m_word[$len-2], 0xD988) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD8A7))
        {
            self::$m_word = array_slice(self::$m_word,1,$len-3);
            return true;
        }
        // remove alif (hamzat wassel) from the beginning # yaa from the end
        if($len >= 5 && self::utf8_char_equal(self::$m_word[0], 0xD8A5) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD98A))
        {
            self::$m_word = array_slice(self::$m_word,1,$len-2);
            return true;
        }
        // remove alif from the beginning # yaa from the end
        if($len >= 5 && self::utf8_char_equal(self::$m_word[0], 0xD8A7) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD98A))
        {
            self::$m_word = array_slice(self::$m_word,1,$len-2);
            return true;
        }
        // remove alif (hamzat wassel) from the beginning # alif from the end
        if($len >= 5 && self::utf8_char_equal(self::$m_word[0], 0xD8A5) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD8A7))
        {
            self::$m_word = array_slice(self::$m_word,1,$len-2);
            return true;
        }
        // remove alif from the beginning # alif from the end
        if($len >= 5 && self::utf8_char_equal(self::$m_word[0], 0xD8A7) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD8A7))
        {
            self::$m_word = array_slice(self::$m_word,1,$len-2);
            return true;
        }
        // remove alif (hamzat wassel) from the beginning # noon from the end
        if($len >= 5 && self::utf8_char_equal(self::$m_word[0], 0xD8A5) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD986))
        {
            self::$m_word = array_slice(self::$m_word,1,$len-2);
            return true;
        }
        // remove alif from the beginning # noon from the end
        if($len >= 5 && self::utf8_char_equal(self::$m_word[0], 0xD8A7) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD986))
        {
            self::$m_word = array_slice(self::$m_word,1,$len-2);
            return true;
        }
        // remove yaa from the beginning # noon from the end
        if($len >= 5 && self::utf8_char_equal(self::$m_word[0], 0xD98A) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD986))
        {
            self::$m_word = array_slice(self::$m_word,1,$len-2);
            return true;
        }
        // remove taa from the beginning # noon from the end
        if($len >= 5 && self::utf8_char_equal(self::$m_word[0], 0xD8AA) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD986))
        {
            self::$m_word = array_slice(self::$m_word,1, $len-2);
            return true;
        }

        /**************************************************************************
        * future = siin + present
        **************************************************************************/
        // remove siin and taa from the beginning # yaa and noon from the end
        if($len >= 7 && self::utf8_char_equal(self::$m_word[0], 0xD8B3) && 
                        self::utf8_char_equal(self::$m_word[1], 0xD8AA) && 
                        self::utf8_char_equal(self::$m_word[$len-2], 0xD98A) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD986))
        {
            self::$m_word = array_slice(self::$m_word,2,$len-3);
            return true;
        }
        // remove siin and taa from the beginning # alif and noon from the end
        if($len >= 7 && self::utf8_char_equal(self::$m_word[0], 0xD8B3) && 
                        self::utf8_char_equal(self::$m_word[1], 0xD8AA) && 
                        self::utf8_char_equal(self::$m_word[$len-2], 0xD8A7) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD986))
        {
            self::$m_word = array_slice(self::$m_word,2,$len-3);
            return true;
        }
        // remove siin and taa from the beginning # waaw and noon from the end
        if($len >= 7 && self::utf8_char_equal(self::$m_word[0], 0xD8B3) && 
                        self::utf8_char_equal(self::$m_word[1], 0xD8AA) && 
                        self::utf8_char_equal(self::$m_word[$len-2], 0xD988) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD986))
        {
            self::$m_word = array_slice(self::$m_word,2,$len-3);
            return true;
        }
        // remove siin and yaa from the beginning # alif and noon from the end
        if($len >= 7 && self::utf8_char_equal(self::$m_word[0], 0xD8B3) && 
                        self::utf8_char_equal(self::$m_word[1], 0xD98A) && 
                        self::utf8_char_equal(self::$m_word[$len-2], 0xD8A7) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD986))
        {
            self::$m_word = array_slice(self::$m_word,2,$len-3);
            return true;
        }
        // remove siin and yaa from the beginning # waaw and noon from the end
        if($len >= 7 && self::utf8_char_equal(self::$m_word[0], 0xD8B3) && 
                        self::utf8_char_equal(self::$m_word[1], 0xD98A) && 
                        self::utf8_char_equal(self::$m_word[$len-2], 0xD988) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD986))
        {
            self::$m_word = array_slice(self::$m_word,2,$len-3);
            return true;
        }
        // remove siin and yaa from the beginning # noon from the end
        if($len >= 6 && self::utf8_char_equal(self::$m_word[0], 0xD8B3) && 
                        self::utf8_char_equal(self::$m_word[1], 0xD98A) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD986))
        {
            self::$m_word = array_slice(self::$m_word,2,$len-2);
            return true;
        }
        // remove siin and taa from the beginning # noon from the end
        if($len >= 6 && self::utf8_char_equal(self::$m_word[0], 0xD8B3) && 
                        self::utf8_char_equal(self::$m_word[1], 0xD8AA) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD986))
        {
            self::$m_word = array_slice(self::$m_word,2,$len-2);
            return true;
        }

        /**************************************************************************
        * At the end
        **************************************************************************/
        // remove taa, miim and alif from the end
        if($len >= 6 && self::utf8_char_equal(self::$m_word[$len-3], 0xD8AA) && 
                        self::utf8_char_equal(self::$m_word[$len-2], 0xD985) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD8A7))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-4);
            return true;
        }
        // remove taa, noon and chedda from the end
        if($len >= 6 && self::utf8_char_equal(self::$m_word[$len-3], 0xD8AA) && 
                        self::utf8_char_equal(self::$m_word[$len-2], 0xD986) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD991))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-4);
            return true;
        }
        // remove noon and alif from the end
        if($len >= 5 && self::utf8_char_equal(self::$m_word[$len-2], 0xD986) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD8A7))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-3);
            return true;
        }
        // remove taa and miim from the end
        if($len >= 5 && self::utf8_char_equal(self::$m_word[$len-2], 0xD8AA) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD985))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-3);
            return true;
        }
        // remove taa and alif from the end
        if($len >= 5 && self::utf8_char_equal(self::$m_word[$len-2], 0xD8AA) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD8A7))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-3);
            return true;
        }
        // remove waaw and alif from the end
        if($len >= 5 && self::utf8_char_equal(self::$m_word[$len-2], 0xD988) && 
                        self::utf8_char_equal(self::$m_word[$len-1], 0xD8A7))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-3);
            return true;
        }
        // remove taa the end
        if($len >= 4 && self::utf8_char_equal(self::$m_word[$len-1], 0xD8AA))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-2);
            return true;
        }
        // remove alif from the end
        if($len >= 4 && self::utf8_char_equal(self::$m_word[$len-1], 0xD8A7))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-2);
            return true;
        }
        // remove noon from the end
        if($len >= 4 && self::utf8_char_equal(self::$m_word[$len-1], 0xD986))
        {
            self::$m_word = array_slice(self::$m_word,0,$len-2);
            return true;
        }

        /**************************************************************************
        * At the begining
        **************************************************************************/
        // remove alif from the beginning
        if($len >= 4 && self::utf8_char_equal(self::$m_word[0], 0xD8A7) || 
                        self::utf8_char_equal(self::$m_word[0], 0xD8A3) || 
                        self::utf8_char_equal(self::$m_word[0], 0xD8A5))
        {
            self::$m_word = array_slice(self::$m_word,1,$len);
            return true;
        }
        // remove noon from the beginning
        if($len >= 4 && self::utf8_char_equal(self::$m_word[0], 0xD986))
        {
            self::$m_word = array_slice(self::$m_word,1,$len);
            return true;
        }
        // remove taa from the beginning
        if($len >= 4 && self::utf8_char_equal(self::$m_word[0], 0xD8AA))
        {
            self::$m_word = array_slice(self::$m_word,1,$len);
            return true;
        }
        // remove yaa from the beginning
        if($len >= 4 && self::utf8_char_equal(self::$m_word[0], 0xD98A))
        {
            self::$m_word = array_slice(self::$m_word,1,$len);
            return true;
        }

        /**************************************************************************
        * Futur = siin + present
        **************************************************************************/
        // remove siin and alif from the beginning
        if($len >= 5 && self::utf8_char_equal(self::$m_word[0], 0xD8B3) && 
                        self::utf8_char_equal(self::$m_word[1], 0xD8A7))
        {
            self::$m_word = array_slice(self::$m_word,2,$len);
            return true;
        }
        // remove siin and noon from the beginning
        if($len >= 5 && self::utf8_char_equal(self::$m_word[0], 0xD8B3) && 
                        self::utf8_char_equal(self::$m_word[1], 0xD986))
        {
            self::$m_word = array_slice(self::$m_word,2,$len);
            return true;
        }
        // remove siin and taa from the beginning
        if($len >= 5 && self::utf8_char_equal(self::$m_word[0], 0xD8B3) && 
                        self::utf8_char_equal(self::$m_word[1], 0xD8AA))
        {
            self::$m_word = array_slice(self::$m_word,2,$len);
            return true;
        }
        // remove siin and yaa from the beginning
        if($len >= 5 && self::utf8_char_equal(self::$m_word[0], 0xD8B3) && 
                        self::utf8_char_equal(self::$m_word[1], 0xD98A))
        {
            self::$m_word = array_slice(self::$m_word,2,$len);
            return true;
        }
        return false;
    }

    // check the equivalence of two characters
    public static function utf8_char_equal($char, $hex)
    {
        if(strlen($char) == 1) return false; // if the utf-8 character is not Arabic
        return (((ord($char[0])<<8) | ord($char[1])) == $hex);
    }

    // replace an utf-8 character with another (given the hex code point)
    public static function utf8_replace_char(&$char, $hex)
    {
        $char[0] = chr($hex & 0xFF);
        $char[1] = chr($hex>>8);
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