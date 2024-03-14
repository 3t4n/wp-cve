<?php

// An implementation of "A Lightweight Stemmer for Hindi":
// http://www.kbcs.in/downloads/papers/StmmerHindi.pdf


class Wpil_Stemmer {
    // The transliteration scheme used for our stringdefs matches that used in the
    // paper, as documented in the appendix.  It appears to match the WX notation
    // (https://en.wikipedia.org/wiki/WX_notation) except that WX apparently
    // uses 'z' for Anunasika whereas the paper uses Mh.
    //
    // We discriminate dependent vowels by adding a leading "_" to their stringdef
    // names (mnemonic: the _ signifies removing the implicit a from the preceding
    // character).

    static $stem_cache = array();
    static $p = 1; // int

    static $letters = array(
        // Vowels and sonorants:
        'a' => 'अ',
        'A' => 'आ',
        'i' => 'इ',
        'I' => 'ई',
        'u' => 'उ',
        'U' => 'ऊ',
        'q' => 'ऋ',
        'e' => 'ए',
        'E' => 'ऐ',
        'o' => 'ओ',
        'O' => 'औ',

        // Vowel signs:
        '_A' => 'ा',
        '_i' => 'ि',
        '_I' => 'ी',
        '_u' => 'ु',
        '_U' => 'ू',
        '_q' => 'ृ',
        '_e' => 'े',
        '_E' => 'ै',
        '_o' => 'ो',
        '_O' => 'ौ',

        // Diacritics:
        'M' => 'ं',
        'H' => 'ः',
        'Mh' => 'ँ',
        'Z' => '़', // Nukta
        'virama' => '्',

        // Velar consonants:
        'k' => 'क',
        'K' => 'ख',
        'g' => 'ग',
        'G' => 'घ',
        'f' => 'ङ',

        // Palatal consonants:
        'c' => 'च',
        'C' => 'छ',
        'j' => 'ज',
        'J' => 'झ',
        'F' => 'ञ',

        // Retroflex consonants:
        't' => 'ट',
        'T' => 'ठ',
        'd' => 'ड',
        'D' => 'ढ',
        'N' => 'ण',

        // Dental consonants:
        'w' => 'त',
        'W' => 'थ',
        'x' => 'द',
        'X' => 'ध',
        'n' => 'न',

        // Labial consonants:
        'p' => 'प',
        'P' => 'फ',
        'b' => 'ब',
        'B' => 'भ',
        'm' => 'म',

        // Semi-vowels:
        'y' => 'य',
        'r' => 'र',
        'l' => 'ल',
        'v' => 'व',

        // Fricatives:
        'S' => 'श',
        'R' => 'ष',
        's' => 'स',
        'h' => 'ह',

        'lY' => 'ळ',

        // Precomposed characters - letters + nukta:
        'nZ' => 'ऩ', // ≡ {n}{Z}
        'rZ' => 'ऱ', // ≡ {r}{Z}
        'lYZ' => 'ऴ', // ≡ {lY}{Z}
        'kZ' => 'क़', // ≡ {k}{Z}
        'KZ' => 'ख़', // ≡ {K}{Z}
        'gZ' => 'ग़', // ≡ {g}{Z}
        'jZ' => 'ज़', // ≡ {j}{Z}
        'dZ' => 'ड़', // ≡ {d}{Z}
        'DZ' => 'ढ़', // ≡ {D}{Z}
        'PZ' => 'फ़', // ≡ {P}{Z}
        'yZ' => 'य़', // ≡ {y}{Z}
    );

    static $consonants = array(
        'k' => 'क',
        'K' => 'ख',
        'g' => 'ग',
        'G' => 'घ',
        'f' => 'ङ',
        'c' => 'च',
        'C' => 'छ',
        'j' => 'ज',
        'J' => 'झ',
        'F' => 'ञ',
        't' => 'ट',
        'T' => 'ठ',
        'd' => 'ड',
        'D' => 'ढ',
        'N' => 'ण',
        'w' => 'त',
        'W' => 'थ',
        'x' => 'द',
        'X' => 'ध',
        'n' => 'न',
        'p' => 'प',
        'P' => 'फ',
        'b' => 'ब',
        'B' => 'भ',
        'm' => 'म',
        'y' => 'य',
        'r' => 'र',
        'l' => 'ल',
        'v' => 'व',
        'S' => 'श',
        'R' => 'ष',
        's' => 'स',
        'h' => 'ह',
        'lY' => 'ळ',
        'Z' => '़', // Nukta
        // Precomposed characters - letter and nukta:
        'nZ' => 'ऩ', // ≡ {n}{Z}
        'rZ' => 'ऱ', // ≡ {r}{Z}
        'lYZ' => 'ऴ', // ≡ {lY}{Z}
        'kZ' => 'क़', // ≡ {k}{Z}
        'KZ' => 'ख़', // ≡ {K}{Z}
        'gZ' => 'ग़', // ≡ {g}{Z}
        'jZ' => 'ज़', // ≡ {j}{Z}
        'dZ' => 'ड़', // ≡ {d}{Z}
        'DZ' => 'ढ़', // ≡ {D}{Z}
        'PZ' => 'फ़', // ≡ {P}{Z}
        'yZ' => 'य़', // ≡ {y}{Z}
    );

    public static function Stem($word, $deaccent = false, $ignore_cache = false){
        // first check if we've already stemmed the word
        $cached = self::get_cached_stem($word);
        if(!empty($cached)){
            // if we have return the cached
            return $cached;
        }

        // if it's not cached, stemm the word
        $original_word = $word;

        // get the word length
        $length = mb_strlen($word);

        // We assume in this implementation that the whole word doesn't count
        // as a valid suffix to remove, so we remove the longest suffix from
        // the list which leaves at least one character.  This change affects
        // 47 words out of the 65,140 in the sample vocabulary from Hindi
        // wikipedia.

        // The list below is derived from figure 3 in the paper.
        //
        // We perform the stemming on the Devanagari characters rather than
        // transliterating to Latin, so we have adapted the list below to
        // reflect this by converting suffixes back to Devanagari as
        // follows:
        //
        // * within the suffixes, "a" after a consonant is dropped since
        //   consonants have an implicit "a".
        //
        // * within the suffixes, a vowel other than "a" after a consonant
        //   is a dependent vowel (vowel sign); a vowel (including "a")
        //   after a non-consonant is an independent vowel.
        //
        // * to allow the vowel at the start of each suffix being dependent
        //   or independent, we include each suffix twice.  For the
        //   dependent version, a leading "a" is dropped and we check that
        //   the suffix is preceded by a consonant (which will have an
        //   implicit "a").
        //
        // * we add '{a}', which is needed for the example given right at
        //   the end of section 5 to work (conflating BarawIya and
        //   BarawIyawA), and which 3.1 a.v strongly suggests should be in
        //   the list:
        //
        //     Thus, the following suffix deletions (longest possible
        //     match) are required to reduce inflected forms of masculine
        //     nouns to a common stem:
        //     a A i [...]
        //
        //   Adding '{a}' only affect 2 words out of the 65,140 in the
        //   sample vocabulary.
        //
        // * The transliterations of our stems would end with "a" when our
        //   stems end in a consonant, so we also include {virama} in the
        //   list of suffixes to remove (this affects 222 words from the
        //   sample vocabulary).
        //
        // We've also assumed that Mh in the suffix list always means {Mh}
        // and never {M}{h}{virama}.  Only one of the 65,140 words in the
        // sample vocabulary stems differently due to this (and that word
        // seems to be a typo).
        $suffixes = array(
            "ाइयां",
            "ाएंगे",
            "ाइयों",
            "ाएंगी",
            "अनाओं",
            "अनाएं",
            "अताओं",
            "अताएं",
            "आइयाँ",
            "आऊंगा",
            "आऊंगी",
            "आइयों",
            "आइयां",
            "ाइयाँ",
            "आएंगे",
            "आएंगी",
            "ाऊंगी",
            "ाऊंगा",
            "ऊंगा",
            "ऊंगी",
            "ाएगी",
            "ाएगा",
            "आएगी",
            "ाओगी",
            "आएगा",
            "ाओगे",
            "आओगी",
            "आओगे",
            "ेंगी",
            "एंगे",
            "एंगी",
            "आतीं",
            "ेंगे",
            "अतीं",
            "ियां",
            "इयाँ",
            "ातीं",
            "नाओं",
            "ियों",
            "इयां",
            "इयों",
            "ताएं",
            "ियाँ",
            "ूंगा",
            "ताओं",
            "ूंगी",
            "नाएं",
            "ाते",
            "ाना",
            "ाने",
            "ेगा",
            "ेगी",
            "ाता",
            "ोगी",
            "ाती",
            "अकर",
            "ोगे",
            "ुआं",
            "ुएं",
            "ुओं",
            "ाएं",
            "ाओं",
            "ाया",
            "आकर",
            "आइए",
            "अना",
            "तीं",
            "उआं",
            "उएं",
            "उओं",
            "आएं",
            "आओं",
            "अता",
            "अती",
            "ाकर",
            "अते",
            "आता",
            "ाईं",
            "आते",
            "आती",
            "अनी",
            "एगी",
            "अने",
            "ाइए",
            "आया",
            "आईं",
            "एगा",
            "ओगी",
            "आने",
            "आना",
            "ओगे",
            "ाओ",
            "िए",
            "ाए",
            "ता",
            "ती",
            "ाई",
            "ते",
            "ना",
            "नी",
            "ने",
            "कर",
            "ीं",
            "आओ",
            "एं",
            "ओं",
            "आं",
            "आँ",
            "ईं",
            "आए",
            "ाँ",
            "इए",
            "आई",
            "ों",
            "ां",
            "ें",
            "आ",
            "इ",
            "ई",
            "उ",
            "ऊ",
            "ए",
            "ओ",
            "ा",
            "अ",
            "ो",
            "े",
            "ू",
            "ु",
            "ी",
            "ि",
            "्",
        );

        $consonant_suffixes = array(
            "नाओं" => true,
            "ताएं" => true,
            "ताओं" => true,
            "नाएं" => true,
            "तीं" => true,
            "ता" => true,
            "ती" => true,
            "ते" => true,
            "ना" => true,
            "नी" => true,
            "ने" => true,
            "कर" => true,
        );

        // If I ever have the misforture of doing something like this again, this section of code below means:
        // "Count this as a match if it's proceeded by a consonant".
        /* Suffixes with a leading implicit a: *//*
        '{w}{_A}{e}{M}' CONSONANT
        '{w}{_A}{o}{M}' CONSONANT
        '{n}{_A}{e}{M}' CONSONANT
        '{n}{_A}{o}{M}' CONSONANT
        '{w}{_A}' CONSONANT
        '{w}{_I}' CONSONANT
        '{w}{_I}{M}' CONSONANT
        '{w}{_e}' CONSONANT
        '{n}{_A}' CONSONANT
        '{n}{_I}' CONSONANT
        '{n}{_e}' CONSONANT
        '{k}{r}' CONSONANT*/

        // go over all the listed suffixes
        foreach($suffixes as $suffix){
            // if the suffix is in the word
            if(false !== strpos($word, $suffix)){
                $key_len = mb_strlen($suffix);
                $key_pos = mb_strrpos($word, $suffix);

                // skip to the next suffix if removing this one removes the whole word
                if($key_pos < self::$p){
                    continue;
                }

                // see if the suffix comes at the end of the word by adding the suffix pos and it's length and seeing if that equals the word length
                if(($key_len + $key_pos) === $length){
                    // check if the suffix is one of the ones that omits the "a"
                    if(isset($consonant_suffixes[$suffix])){
                        // if the suffix is preceeded by a consonant
                        $prev = mb_substr($word, ($key_pos - 1), 1);
                        if(in_array($prev, self::$consonants, true)){
                            // remove the suffix
                            $word = mb_substr($word, 0, $key_pos);
                            // and exit the loop
                            break;
                        }
                    }else{
                        // if it's a suffix that doesn't need to be preceeded by a consonanant, remove it
                        $word = mb_substr($word, 0, $key_pos);
                        // and exit the loop
                        break;
                    }
                }

            }
        }

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