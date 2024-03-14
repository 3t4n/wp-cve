<?php
/**
 * Copyright (c) 2005 Richard Heyes (http://www.phpguru.org/)
 *
 * All rights reserved.
 *
 * This script is free software.
 */

/**
 * PHP5 Implementation of the Porter Stemmer algorithm. Certain elements
 * were borrowed from the (broken) implementation by Jon Abernathy.
 *
 * Usage:
 *
 *  $stem = PorterStemmer::Stem($word);
 *
 * How easy is that?
 */

class Wpil_Stemmer
{
    /**
     * Regex for matching a consonant
     * @var string
     */
    private static $regex_consonant = '(?:[bcdfghjklmnpqrstvwxz]|(?<=[aeiou])y|^y)';


    /**
     * Regex for matching a vowel
     * @var string
     */
    private static $regex_vowel = '(?:[aeiou]|(?<![aeiou])y)';

    static $stem_cache = array();

    /**
     * Stems a word. Simple huh?
     *
     * @param  string $word Word to stem
     * @return string       Stemmed word
     */
    public static function Stem($word)
    {
        if (strlen($word) <= 2) {
            return $word;
        }

        // check if we've already stemmed the word
        $cached = self::get_cached_stem($word);
        if(!empty($cached)){
            // if we have, return the cached
            return $cached;
        }

        $original_word = $word;
        $word = self::step1ab($word);
        $word = self::step1c($word);
        $word = self::step2($word);
        $word = self::step3($word);
        $word = self::step4($word);
        $word = self::step5($word);
        $word = self::remove_punctuation($word);

        // and update the cache with the stemmed word
        self::update_cached_stem($original_word, $word);

        return $word;
    }


    /**
     * Step 1
     */
    private static function step1ab($word)
    {
        // Part a
        if (substr($word, -1) == 's') {

            self::replace($word, 'sses', 'ss')
            OR self::replace($word, 'ies', 'i')
            OR self::replace($word, 'ss', 'ss')
            OR self::replace($word, 's', '');
        }

        // Part b
        if (substr($word, -2, 1) != 'e' OR !self::replace($word, 'eed', 'ee', 0)) { // First rule
            $v = self::$regex_vowel;

            // ing and ed
            if (   preg_match("#$v+#", substr($word, 0, -3)) && self::replace($word, 'ing', '')
                OR preg_match("#$v+#", substr($word, 0, -2)) && self::replace($word, 'ed', '')) { // Note use of && and OR, for precedence reasons

                // If one of above two test successful
                if (    !self::replace($word, 'at', 'ate')
                    AND !self::replace($word, 'bl', 'ble')
                    AND !self::replace($word, 'iz', 'ize')) {

                    // Double consonant ending
                    if (    self::doubleConsonant($word)
                        AND substr($word, -2) != 'll'
                        AND substr($word, -2) != 'ss'
                        AND substr($word, -2) != 'zz') {

                        $word = substr($word, 0, -1);

                    } else if (self::m($word) == 1 AND self::cvc($word)) {
                        $word .= 'e';
                    }
                }
            }
        }

        return $word;
    }


    /**
     * Step 1c
     *
     * @param string $word Word to stem
     * @return string
     */
    private static function step1c($word)
    {
        $v = self::$regex_vowel;

        if (substr($word, -1) == 'y' && preg_match("#$v+#", substr($word, 0, -1))) {
            self::replace($word, 'y', 'i');
        }

        return $word;
    }


    /**
     * Step 2
     *
     * @param string $word Word to stem
     * @return string
     */
    private static function step2($word)
    {
        switch (substr($word, -2, 1)) {
            case 'a':
                self::replace($word, 'ational', 'ate', 0)
                OR self::replace($word, 'tional', 'tion', 0);
                break;

            case 'c':
                self::replace($word, 'enci', 'ence', 0)
                OR self::replace($word, 'anci', 'ance', 0);
                break;

            case 'e':
                self::replace($word, 'izer', 'ize', 0);
                break;

            case 'g':
                self::replace($word, 'logi', 'log', 0);
                break;

            case 'l':
                self::replace($word, 'entli', 'ent', 0)
                OR self::replace($word, 'ousli', 'ous', 0)
                OR self::replace($word, 'alli', 'al', 0)
                OR self::replace($word, 'bli', 'ble', 0)
                OR self::replace($word, 'eli', 'e', 0);
                break;

            case 'o':
                self::replace($word, 'ization', 'ize', 0)
                OR self::replace($word, 'ation', 'ate', 0)
                OR self::replace($word, 'ator', 'ate', 0);
                break;

            case 's':
                self::replace($word, 'iveness', 'ive', 0)
                OR self::replace($word, 'fulness', 'ful', 0)
                OR self::replace($word, 'ousness', 'ous', 0)
                OR self::replace($word, 'alism', 'al', 0);
                break;

            case 't':
                self::replace($word, 'biliti', 'ble', 0)
                OR self::replace($word, 'aliti', 'al', 0)
                OR self::replace($word, 'iviti', 'ive', 0);
                break;
        }

        return $word;
    }


    /**
     * Step 3
     *
     * @param string $word String to stem
     * @return string
     */
    private static function step3($word)
    {
        switch (substr($word, -2, 1)) {
            case 'a':
                self::replace($word, 'ical', 'ic', 0);
                break;

            case 's':
                self::replace($word, 'ness', '', 0);
                break;

            case 't':
                self::replace($word, 'icate', 'ic', 0)
                OR self::replace($word, 'iciti', 'ic', 0);
                break;

            case 'u':
                self::replace($word, 'ful', '', 0);
                break;

            case 'v':
                self::replace($word, 'ative', '', 0);
                break;

            case 'z':
                self::replace($word, 'alize', 'al', 0);
                break;
        }

        return $word;
    }


    /**
     * Step 4
     *
     * @param string $word Word to stem
     * @return string
     */
    private static function step4($word)
    {
        switch (substr($word, -2, 1)) {
            case 'a':
                self::replace($word, 'al', '', 1);
                break;

            case 'c':
                self::replace($word, 'ance', '', 1)
                OR self::replace($word, 'ence', '', 1);
                break;

            case 'e':
                self::replace($word, 'er', '', 1);
                break;

            case 'i':
                self::replace($word, 'ic', '', 1);
                break;

            case 'l':
                self::replace($word, 'able', '', 1)
                OR self::replace($word, 'ible', '', 1);
                break;

            case 'n':
                self::replace($word, 'ant', '', 1)
                OR self::replace($word, 'ement', '', 1)
                OR self::replace($word, 'ment', '', 1)
                OR self::replace($word, 'ent', '', 1);
                break;

            case 'o':
                if (substr($word, -4) == 'tion' OR substr($word, -4) == 'sion') {
                    self::replace($word, 'ion', '', 1);
                } else {
                    self::replace($word, 'ou', '', 1);
                }
                break;

            case 's':
                self::replace($word, 'ism', '', 1);
                break;

            case 't':
                self::replace($word, 'ate', '', 1)
                OR self::replace($word, 'iti', '', 1);
                break;

            case 'u':
                self::replace($word, 'ous', '', 1);
                break;

            case 'v':
                self::replace($word, 'ive', '', 1);
                break;

            case 'z':
                self::replace($word, 'ize', '', 1);
                break;
        }

        return $word;
    }


    /**
     * Step 5
     *
     * @param string $word Word to stem
     * @return false|string
     */
    private static function step5($word)
    {
        // Part a
        if (substr($word, -1) == 'e') {
            if (self::m(substr($word, 0, -1)) > 1) {
                self::replace($word, 'e', '');

            } else if (self::m(substr($word, 0, -1)) == 1) {

                if (!self::cvc(substr($word, 0, -1))) {
                    self::replace($word, 'e', '');
                }
            }
        }

        // Part b
        if (self::m($word) > 1 AND self::doubleConsonant($word) AND substr($word, -1) == 'l') {
            $word = substr($word, 0, -1);
        }

        return $word;
    }


    /**
     * Replaces the first string with the second, at the end of the string. If third
     * arg is given, then the preceding string must match that m count at least.
     *
     * @param  string $str   String to check
     * @param  string $check Ending to check for
     * @param  string $repl  Replacement string
     * @param  int    $m     Optional minimum number of m() to meet
     * @return bool          Whether the $check string was at the end
     *                       of the $str string. True does not necessarily mean
     *                       that it was replaced.
     */
    private static function replace(&$str, $check, $repl, $m = null)
    {
        $len = 0 - strlen($check);

        if (substr($str, $len) == $check) {
            $substr = substr($str, 0, $len);
            if (is_null($m) OR self::m($substr) > $m) {
                $str = $substr . $repl;
            }

            return true;
        }

        return false;
    }


    /**
     * What, you mean it's not obvious from the name?
     *
     * m() measures the number of consonant sequences in $str. if c is
     * a consonant sequence and v a vowel sequence, and <..> indicates arbitrary
     * presence,
     *
     * <c><v>       gives 0
     * <c>vc<v>     gives 1
     * <c>vcvc<v>   gives 2
     * <c>vcvcvc<v> gives 3
     *
     * @param  string $str The string to return the m count for
     * @return int         The m count
     */
    private static function m($str)
    {
        $c = self::$regex_consonant;
        $v = self::$regex_vowel;

        $str = preg_replace("#^$c+#", '', $str);
        $str = preg_replace("#$v+$#", '', $str);

        preg_match_all("#($v+$c+)#", $str, $matches);

        return count($matches[1]);
    }


    /**
     * Returns true/false as to whether the given string contains two
     * of the same consonant next to each other at the end of the string.
     *
     * @param  string $str String to check
     * @return bool        Result
     */
    private static function doubleConsonant($str)
    {
        if(empty($str)){
            return false;
        }

        $c = self::$regex_consonant;
        $check = preg_match("#$c[2]$#", $str, $matches);

        if( !empty($check) ||                   // if the check didn't turn up any matches,
            !isset($matches) ||                 // 
            !isset($matches[0]) ||              // there isn't a first index to the match array,
            !isset($matches[0][0]) ||           // there isn't a first index of the first array,
            !isset($matches[0][1]) ||           // there isn't a second index of the first array
            $matches[0][0] != $matches[0][1])   // or the first and second matches don't equal each other
        {
            // return false
            return false;
        }else{
            // if all that wasn't true, then these must be double consonants
            return true;
        }
    }


    /**
     * Checks for ending CVC sequence where second C is not W, X or Y
     *
     * @param  string $str String to check
     * @return bool        Result
     */
    private static function cvc($str)
    {
        $c = self::$regex_consonant;
        $v = self::$regex_vowel;

        return     preg_match("#($c$v$c)$#", $str, $matches)
            AND strlen($matches[1]) == 3
            AND $matches[1][2] != 'w'
            AND $matches[1][2] != 'x'
            AND $matches[1][2] != 'y';
    }

    /**
     * Takes an array of stemmed words and trims them back further to their more essential letters.
     * This is so we can use these words to perform a simple match check to see if any title words show up in a phrase.
     **/
    public static function get_segments($word_array = array()){
        $return_words = array();

        foreach($word_array as $word){
            $word = rtrim($word, 'i');  // remove the "i" from any words that end with "i"
            $word = strtok($word, '\'"');      // get the portion of word before any quotes

            if(strlen($word) < 2){
                continue;
            }

            $return_words[] = $word;
        }

        return $return_words;
    }

    /**
     * Removes any punctuation from the end of word.
     * In most cases, the problem is apostropies after removing "s" or "re" from words like "that's" or "we're".
     * 
     * If a punctuation is removed, we'll send it around for another stem to make sure it matches other stemmed words.
     *
     * @param string $word The word to process
     * @return string $word The processed word
     */
    private static function remove_punctuation($word){
        $new_word = rtrim($word, '’\'"`-_');

        return ($new_word !== $word) ? self::Stem($new_word): $word;
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
