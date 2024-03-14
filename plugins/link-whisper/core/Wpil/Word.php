<?php

/**
 * Work with words and sentences
 */
class Wpil_Word
{
    public static $endings = ['"', '!', '?', ',', ')','(', '.', '`', "'", ':', ';', '|'];
    public static $stemmed_sentence_cache = array();

    /**
     * Clean sentence from ignore phrases and divide to words
     *
     * @param $sentence
     * @return array
     */
    public static function cleanFromIgnorePhrases($sentence)
    {
        $phrases = Wpil_Settings::getIgnorePhrases();
        $sentence = self::clearFromUnicode($sentence);
        mb_eregi_replace('\s+', ' ', $sentence);
        $sentence = trim($sentence);
        $sentence = str_ireplace($phrases, '', $sentence);
        $stemmed_phrases = Wpil_Settings::getStemmedIgnorePhrases();
        $sentence = self::getStemmedSentence($sentence);
        $sentence = str_ireplace($stemmed_phrases, '', $sentence);
        $sentence = (!Wpil_Suggestion::isAsianText()) ? array_filter(explode(' ', $sentence)) : mb_str_split($sentence);
        return $sentence;
    }

    /**
     * Get words from sentence
     *
     * @param $sentence
     * @return array
     */
    public static function getWords($sentence)
    {
        $sentence = self::clearFromUnicode($sentence);
        $words = explode(' ', self::removeEndings($sentence));
        foreach ($words as $key => $word) {
            $word = trim($word);

            if (!empty($word)) {
                $words[$key] = self::strtolower($word);
            }
        }

        return $words;
    }

    /**
     * Gets the number of words in a given sentence
     **/
    public static function getWordCount($sentence = ''){
        if(empty($sentence) || !is_string($sentence)){
            return 0;
        }

        $sentence = mb_ereg_replace('[\s-]', '{{wpil-replace}}', $sentence);
        $sentence = array_filter(explode('{{wpil-replace}}', $sentence));

        return count($sentence);
    }

    /**
     * Removes the ending punctuation from the supplied text
     **/
    public static function removeEndings($text, $endings = array()){
        if(empty($endings)){
            $endings = self::$endings;
        }

        $endings = array_map(function($e){ return preg_quote($e); }, $endings);

        // make sure not to remove common abbreviations
        $abbrs = implode("(?![[:alpha:]<>-_1-9])|(?<![[:alpha:]<>-_1-9])", array_map(function($text){ return preg_quote($text, '/');}, Wpil_Suggestion::getIgnoreTextDefaults()));
        $abbrs = "(?<![[:alpha:]<>-_1-9])" . $abbrs . "(?![[:alpha:]<>-_1-9])";
        $abbrs .= "(?<![[:alpha:]<>-_1-9])(?:[A-Za-z]\.){2,20}(?![[:alpha:]<>-_1-9])";
        $text = preg_replace_callback('/' . $abbrs . '/i' , function($i){ return str_replace($i[0], 'wpil-ignore-replace_' . base64_encode($i[0]), $i[0]); }, $text);

        // remove all punctuation that isn't surrounded in letters (IE: Remove periods, but not the apostrophie in "that's" or the ".' in "example.com")
        $regex = '(?<![[:alpha:]])[' . implode('', $endings) . ']|[' . implode('', $endings) . '](?![[:alpha:]])';

        $text = mb_ereg_replace($regex, '', $text);

        // decode any abbrs.
        if(false !== strpos($text, 'wpil-ignore-replace_')){
            $text = Wpil_Suggestion::decodeIgnoredText($text);
        }

        return !empty($text) ? $text: '';
    }

    /**
     * Clear the sentence of Unicode whitespace symbols
     *
     * @param $sentence
     * @return string
     */
    public static function clearFromUnicode($sentence)
    {
        $selected_lang = (defined('WPIL_CURRENT_LANGUAGE')) ? WPIL_CURRENT_LANGUAGE : 'english';
        if('russian' === $selected_lang || 'serbian' === $selected_lang || 'ukrainian' === $selected_lang){
            // just remove a limited set of chars since Cyrillic chars can be defined with a pair of UTF-8 hex codes.
            // So what is a control char in latin-01, in the Cyrillic char set can be the first hex code in the "Э" char.
            // And removing the "control" hex code breaks the "Э" char.
            $sentence = preg_replace('/[\x00-\x1F\x7F]/', ' ', $sentence);
            return $sentence;
        }elseif('french' === $selected_lang){
            $urlEncodedWhiteSpaceChars   = '%81,%7F,%8D,%8F,%C2%90,%C2,%90,%9D,%C2%A0,%C2%AD,%AD,%08,%09,%0A,%0D';
        }elseif('spanish' === $selected_lang || 'portuguese' === $selected_lang || 'hungarian' === $selected_lang){
            $urlEncodedWhiteSpaceChars   = '%81,%7F,%8D,%8F,%C2%90,%C2,%90,%9D,%C2%A0,%C2%AD,%08,%09,%0A,%0D';
        }elseif('arabic' === $selected_lang || 'hebrew' === $selected_lang || 'slovak' === $selected_lang || 'hindi' === $selected_lang){
            $sentence = preg_replace('/\xC2\x90|\xC2\xA0|\xC2\xAD|&nbsp;/', ' ', $sentence);
            return preg_replace('/[\x00-\x1F]/', ' ', $sentence);
        }else{
            $urlEncodedWhiteSpaceChars   = '%81,%7F,%8D,%8F,%C2%90,%C2,%90,%9D,%C2%A0,%A0,%C2%AD,%AD,%08,%09,%0A,%0D';
        }

        // replace distinct punctuation with generic punctuation so it doesn't break
        $sentence = str_replace(array('“', '”'), array('"', '"'), $sentence);
        $sentence = preg_replace("/&nbsp;/"," ", $sentence);

        $temp = explode(',', $urlEncodedWhiteSpaceChars);
        $sentence  = urlencode($sentence);
        foreach($temp as $v){
            $sentence  =  str_replace($v, ' ', $sentence);
        }
        $sentence = urldecode($sentence);

        return $sentence;
    }

    /**
     * Clean words from ignore words
     *
     * @param $words
     * @return mixed
     */
    public static function cleanIgnoreWords($words)
    {
        $ignore_words = Wpil_Settings::getIgnoreWords();
        $ignore_numbers = get_option(WPIL_OPTION_IGNORE_NUMBERS, 1);

        foreach ($words as $key => $word) {
            if (($ignore_numbers && is_numeric(str_replace(['.', ',', '$'], '', $word))) || in_array($word, $ignore_words)) {
                unset($words[$key]);
            }
        }

        return $words;
    }

    /**
     * Divice text to words and Stem them
     *
     * @param $text
     * @return array
     */
    public static function getStemmedWords($text)
    {
        $words = Wpil_Word::cleanFromIgnorePhrases($text);
        $words = array_unique(Wpil_Word::cleanIgnoreWords($words));

        foreach ($words as $key_word => $word) {
            $words[$key_word] = Wpil_Stemmer::Stem($word);
        }

        return $words;
    }
    
    /**
     * Takes a string of words and lowercases and stemms the words.
     * Will strip out punctuation, so should only be used on single sentences
     * 
     * @param string $text The input string to be set to lower case and stemmed
     * @return string $words The stemmed and lower cased string of words.
     **/
    public static function getStemmedSentence($text, $remove_accents = false){
        if(!empty(self::get_cached_stem_sentence($text))){
            return self::get_cached_stem_sentence($text);
        }

        $text = Wpil_Word::strtolower($text);
        $words = self::getWords($text);

        foreach ($words as $key_word => $word) {
            $words[$key_word] = Wpil_Stemmer::Stem($word, $remove_accents);
        }

        $stemmed = implode(' ', $words);
        self::update_cached_stem_sentence($text, $stemmed);

        return $stemmed;
    }

    /**
     * Checks to see if the sentence was previously stemmed and is in the stem cache.
     * If it is in the cache, it returns the cached sentence so we don't have to run through the process again.
     * Returns false if the sentence hasn't been stemmed yet, or the "sentence" isn't a sentence
     **/
    public static function get_cached_stem_sentence($sentence = ''){
        if(empty($sentence) || !isset(self::$stemmed_sentence_cache[$sentence]) || !is_string($sentence)){
            return false;
        }

        return self::$stemmed_sentence_cache[$sentence];
    }

    /**
     * Updates the stemmed sentence cache when we come across a sentence that we haven't stemmed yet.
     * Also does some housekeeping to make sure the cache doesn't grow too big
     **/
    public static function update_cached_stem_sentence($sentence, $stemmed_sentence){
        if(empty($sentence) || empty($stemmed_sentence) || isset(self::$stemmed_sentence_cache[$sentence]) || !is_string($sentence)){
            return false;
        }

        self::$stemmed_sentence_cache[$sentence] = $stemmed_sentence;

        if(count(self::$stemmed_sentence_cache) > 1000){
            $ind = key(self::$stemmed_sentence_cache);
            unset(self::$stemmed_sentence_cache[$ind]);
        }
    }

    /**
     * A strtolower function for use on languages that are accented, or non latin.
     * 
     * @param string $string (The text to be lowered)
     * @return string (The string that's been put into lower case)
     */
    public static function strtolower($string){
        // if the wamania project is active, use their strtolower function
        if(class_exists('Wamania\\Snowball\\Utf8')){
            return Wamania\Snowball\Utf8::strtolower($string);
        }else{
            return mb_strtolower($string,'utf-8');
        }
    }

    /**
     * Remove quotes in the begin and in the end of sentence
     *
     * @param $sentence
     * @return false|string
     */
    public static function removeQuotes($sentence)
    {
        if (substr($sentence, 0, 1) == '"' || substr($sentence, 0, 1) == "'") {
            $sentence = substr($sentence, 1);
        }

        if (substr($sentence, -1) == '"' || substr($sentence, -1) == "'") {
            $sentence = substr($sentence, 0,  -1);
        }

        return $sentence;
    }

    /**
     * Replace non ASCII symbols with unicode
     *
     * @param $content
     * @return string
     */
    public static function replaceUnicodeCharacters($content, $revert = false)
    {
        $replacements = [
            ['à', '\u00E0'],
            ['À', '\u00C0'],
            ['â', '\u00E2'],
            ['Â', '\u00C2'],
            ['è', '\u00E8'],
            ['È', '\u00C8'],
            ['é', '\u00E9'],
            ['É', '\u00C9'],
            ['ê', '\u00EA'],
            ['Ê', '\u00CA'],
            ['ë', '\u00EB'],
            ['Ë', '\u00CB'],
            ['î', '\u00EE'],
            ['Î', '\u00CE'],
            ['ï', '\u00EF'],
            ['Ï', '\u00CF'],
            ['ô', '\u00F4'],
            ['Ô', '\u00D4'],
            ['ù', '\u00F9'],
            ['Ù', '\u00D9'],
            ['û', '\u00FB'],
            ['Û', '\u00DB'],
            ['ü', '\u00FC'],
            ['Ü', '\u00DC'],
            ['ÿ', '\u00FF'],
            ['Ÿ', '\u0178'],
            ['-', '\u2013'],
            ["'", '\u2019'],
            ["’", '\u2019']
        ];

        $from = [];
        $to = [];
        foreach ($replacements as $replacement) {
            if ($revert) {
                $from[] = $replacement[1];
                $to[] = $replacement[0];
            } else {
                $from[] = $replacement[0];
                $to[] = $replacement[1];
            }
        }

        return str_ireplace($from, $to, $content);
    }

    /**
     * Add slashes to the new line code
     *
     * @param $content
     */
    public static function addSlashesToNewLine(&$content)
    {
        $content = str_replace('\n', '\\\n', $content);
    }

    /**
     * Remove emoji from text
     *
     * @param $text
     * @return string|string[]|null
     */
    public static function remove_emoji($text){
        $pattern = file_get_contents(WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/emoji_pattern.txt');
        return preg_replace($pattern, '', $text);
    }

    /**
     * Remove everything except of characters from the text
     *
     * @param $text
     * @return string|string[]|null
     */
    public static function onlyText($text) {
        $text = mb_convert_encoding($text, 'UTF-8');
        return mb_eregi_replace('/[^A-Za-z0-9[:alpha:]\-\s]/', '', $text);
    }

    /**
     * Decodes unicode charactors in the supplied text
     **/
    public static function decode_unicode($text = ''){

        $decoded = '';
        $encoding = ini_get('mbstring.internal_encoding');
        $decoded =  preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/u', function($match) use ($encoding) {
            return mb_convert_encoding(pack('H*', $match[1]), $encoding, 'UTF-16BE');
        }, $text);

        if(empty($decoded) || $decoded === $text){
            return $text;
        }else{
            return $decoded;
        }
    }

    /**
     * Atempts to remove accents from letters for a consistent searching experience.
     * @param string $text The text to translate
     * @return string $text The text with accented letters translated to their non-accented forms
     **/
    public static function remove_accents($text) {
        $accents = array(
            'à' => 'a', 'ǻ' => 'a', 'ą' => 'a', 'ā' => 'a', 'á' => 'a', 'ă' => 'a', 'å' => 'a', 'â' => 'a', 'ä' => 'a',
            'ã' => 'a', 'æ' => 'ae', 'ǽ' => 'ae', 'ç' => 'c', 'č' => 'c', 'ć' => 'c', 'ĉ' => 'c', 'ċ' => 'c', 'ð' => 'd',
            'ď' => 'd', 'đ' => 'd', 'ē' => 'e', 'ĕ' => 'e', 'ę' => 'e', 'ė' => 'e', 'ě' => 'e', 'è' => 'e', 'é' => 'e',
            'ë' => 'e', 'ê' => 'e', 'ƒ' => 'f', 'ģ' => 'g', 'ĝ' => 'g', 'ğ' => 'g', 'ġ' => 'g', 'ħ' => 'h', 'ĥ' => 'h',
            'ï' => 'i', 'ì' => 'i', 'ĩ' => 'i', 'í' => 'i', 'ī' => 'i', 'ĭ' => 'i', 'į' => 'i', 'i̇' => 'i', 'ı' => 'i',
            'î' => 'i', 'ĳ' => 'ij', 'ĵ' => 'j', 'ĸ' => 'k', 'ķ' => 'k', 'ĺ' => 'l', 'ļ' => 'l', 'ľ' => 'l', 'ŀ' => 'l',
            'ł' => 'l', 'ñ' => 'n', 'ń' => 'n', 'ņ' => 'n', 'ň' => 'n', 'ŉ' => 'n', 'ŋ' => 'n', 'ō' => 'o', 'ŏ' => 'o',
            'ó' => 'o', 'ǿ' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o', 'ø' => 'o', 'ò' => 'o', 'œ' => 'oe',
            'ř' => 'r', 'ŗ' => 'r', 'ŕ' => 'r', 'ŝ' => 's', 'ş' => 's', 'š' => 's', 'ś' => 's', 'ß' => 'ss', 'ţ' => 't',
            'ť' => 't', 'ŧ' => 't', 'þ' => 'th', 'ű' => 'u', 'ů' => 'u', 'ü' => 'u', 'û' => 'u', 'ų' => 'u', 'ú' => 'u',
            'ŭ' => 'u', 'ū' => 'u', 'ũ' => 'u', 'ù' => 'u', 'ŵ' => 'w', 'ŷ' => 'y', 'ý' => 'y', 'ÿ' => 'y', 'ź' => 'z',
            'ż' => 'z', 'ž' => 'z');
        
        $length = mb_strlen($text);
        for($i = 0; $i < $length; $i++){
            $letter = mb_substr($text, $i, 1);
            if(isset($accents[$letter])){
                $text = mb_ereg_replace(preg_quote($letter), $accents[$letter], $text);
            }
        }

        return $text;
    }

    /** 
     * Accounts for the "Offest not contained in string" error that plagues strpos functions.
     * In this case, mb_strpos.
     * Virtually the same function, just with error handling
     **/
    public static function mb_strpos($haystack, $needle, $offset = 0){
        if(mb_strlen($haystack) < $offset){
            return false; // how hard is that?
        }

        return mb_strpos($haystack, $needle, $offset);
    }
}
