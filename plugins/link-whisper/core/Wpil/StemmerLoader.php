<?php

class Wpil_StemmerLoader{

    public function register(){
        self::load_word_stemmer();
    }
    
    public static function load_word_stemmer(){
        
        $selected_language = Wpil_Settings::getCurrentLanguage();
        $stemmer_file = '';

        switch($selected_language){
            case 'spanish':
                $stemmer_file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/word_stemmers/ES_Stemmer.php';
                define('WPIL_CURRENT_LANGUAGE', 'spanish');
                break;
            case 'french':
                $stemmer_file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/word_stemmers/FR_Stemmer.php';
                define('WPIL_CURRENT_LANGUAGE', 'french');
                break;
            case 'german':
                $stemmer_file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/word_stemmers/DE_Stemmer.php';
                define('WPIL_CURRENT_LANGUAGE', 'german');
                break;
            case 'russian':
                $stemmer_file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/word_stemmers/RU_Stemmer.php';
                define('WPIL_CURRENT_LANGUAGE', 'russian');
                break;
            case 'portuguese':
                $stemmer_file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/word_stemmers/PT_Stemmer.php';
                define('WPIL_CURRENT_LANGUAGE', 'portuguese');
                break;
            case 'dutch':
                $stemmer_file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/word_stemmers/NL_Stemmer.php';
                define('WPIL_CURRENT_LANGUAGE', 'dutch');
                break;
            case 'danish':
                $stemmer_file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/word_stemmers/DA_Stemmer.php';
                define('WPIL_CURRENT_LANGUAGE', 'danish');
                break;
            case 'italian':
                $stemmer_file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/word_stemmers/IT_Stemmer.php';
                define('WPIL_CURRENT_LANGUAGE', 'italian');
                break;
            case 'polish':
                $stemmer_file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/word_stemmers/PL_Stemmer.php';
                define('WPIL_CURRENT_LANGUAGE', 'polish');
                break;
            case 'norwegian':
                $stemmer_file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/word_stemmers/NO_Stemmer.php';
                define('WPIL_CURRENT_LANGUAGE', 'norwegian');
                break;
            case 'swedish':
                $stemmer_file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/word_stemmers/SW_Stemmer.php';
                define('WPIL_CURRENT_LANGUAGE', 'swedish');
                break;
            case 'slovak':
                $stemmer_file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/word_stemmers/SK_Stemmer.php';
                define('WPIL_CURRENT_LANGUAGE', 'slovak');
                break;
            case 'arabic':
                $stemmer_file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/word_stemmers/AR_Stemmer.php';
                define('WPIL_CURRENT_LANGUAGE', 'arabic');
                break;
            case 'serbian':
                $stemmer_file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/word_stemmers/SR_Stemmer.php';
                define('WPIL_CURRENT_LANGUAGE', 'serbian');
                break;
            case 'finnish':
                $stemmer_file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/word_stemmers/FI_Stemmer.php';
                define('WPIL_CURRENT_LANGUAGE', 'finnish');
                break;
            case 'hebrew':
                $stemmer_file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/word_stemmers/HE_Stemmer.php';
                define('WPIL_CURRENT_LANGUAGE', 'hebrew');
                break;
            case 'hindi':
                $stemmer_file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/word_stemmers/HI_Stemmer.php';
                define('WPIL_CURRENT_LANGUAGE', 'hindi');
                break;
            case 'hungarian':
                $stemmer_file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/word_stemmers/HU_Stemmer.php';
                define('WPIL_CURRENT_LANGUAGE', 'hungarian');
                break;
            case 'romanian':
                $stemmer_file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/word_stemmers/RO_Stemmer.php';
                define('WPIL_CURRENT_LANGUAGE', 'romanian');
                break;
            case 'ukrainian':
                $stemmer_file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/word_stemmers/UK_Stemmer.php';
                define('WPIL_CURRENT_LANGUAGE', 'ukrainian');
                break;
            default:
                $stemmer_file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/word_stemmers/EN_Stemmer.php';
                define('WPIL_CURRENT_LANGUAGE', 'english');
                break;
        }

        include_once(WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/word_stemmers/vendor/autoload.php');
        include_once($stemmer_file);
    }
}
?>
