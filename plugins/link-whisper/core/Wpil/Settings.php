<?php

/**
 * Work with settings
 */
class Wpil_Settings
{
    public static $ignore_phrases = null;
    public static $stemmed_ignore_phrases = null;
    public static $ignore_words = null;
    public static $stemmed_ignore_words = null;
    public static $keys = [
        'wpil_2_ignore_numbers',
        'wpil_2_post_types',
        'wpil_suggestion_limited_post_types',
        'wpil_2_term_types',
        'wpil_option_update_reporting_data_on_save',
        'wpil_skip_section_type',
        'wpil_skip_sentences',
        'wpil_selected_language',
        'wpil_ignore_links',
        'wpil_ignore_categories',
        'wpil_show_all_links',
        'wpil_make_suggestion_filtering_persistent',
        'wpil_disable_acf',
        'wpil_count_related_post_links',
        'wpil_domains_marked_as_internal',
        'wpil_content_formatting_level',
        'wpil_delete_all_data',
        'wpil_include_post_meta_in_support_export',
        'wpil_max_suggestion_count',
        'wpil_skip_section_type',
        'wpil_override_global_post_during_scan',
        'wpil_use_ugly_permalinks',
        'wpil_ignore_shortcodes_by_name',
        'wpil_update_reusable_block_links',
    ];

    /**
     * Show settings page
     */
    public static function init()
    {
        //exit if user role lower than editor
        $capability = apply_filters('wpil_filter_main_permission_check', 'manage_categories');
        if (!current_user_can($capability)) {
            exit;
        }

        $types_active = Wpil_Settings::getPostTypes();
        $suggestion_types_active = self::getSuggestionPostTypes();
        $term_types_active = Wpil_Settings::getTermTypes();
        $types_available = get_post_types(['public' => true]);
        $term_types_available = array_intersect(array('category', 'post_tag', 'product_cat', 'product_tag'), get_taxonomies());
        $statuses_available = [
            'publish',
            'private',
            'future',
            'pending',
            'draft'
        ];
        $statuses_active = Wpil_Settings::getPostStatuses();

        include WP_INTERNAL_LINKING_PLUGIN_DIR . '/templates/wpil_settings_v2.php';
    }

    /**
     * Get ignore phrases
     */
    public static function getIgnorePhrases()
    {
        if(is_null(self::$ignore_phrases)){
            $phrases = [];
            $stemmed = array();
            $no_stemmed = is_null(self::$stemmed_ignore_phrases);
            foreach (self::getIgnoreWords() as $word) {
                if (strpos($word, ' ') !== false) {
                    $cleaned = preg_replace('/\s+/', ' ', $word);
                    $phrases[] = $cleaned;
                    if($no_stemmed){
                        $stemmed[] = Wpil_Word::getStemmedSentence($cleaned);
                    }
                }
            }

            self::$ignore_phrases = $phrases;

            if($no_stemmed){
                self::$stemmed_ignore_phrases = $stemmed;
            }
        }

        return self::$ignore_phrases;
    }

    /**
     * Gets the stemmed version of the phrases to ignore
     **/
    public static function getStemmedIgnorePhrases()
    {
        if(is_null(self::$stemmed_ignore_phrases)){
            self::getIgnorePhrases();
        }

        return self::$stemmed_ignore_phrases;
    }

    /**
     * Gets the site's current language as defined in the WP settings
     **/
    public static function getSiteLanguage(){
        $locale = get_locale();

        switch ($locale) {
            case 'en':
            case 'en_AU':
            case 'en_GB':
            case 'en_CA':
            case 'en_NZ':
            case 'en_ZA':
                $language = 'english';
                break;
            case 'es_ES':
            case 'es_AR':
            case 'es_EC':
            case 'es_CO':
            case 'es_VE':
            case 'es_DO':
            case 'es_UY':
            case 'es_PE':
            case 'es_CL':
            case 'es_PR':
            case 'es_CR':
            case 'es_GT':
            case 'es_MX':
                $language = 'spanish';
                break;
            case 'fr_CA':
            case 'fr_FR':
            case 'fr_BE':
                $language = 'french';
                break;
            case 'de_CH_informal':
            case 'de_DE':
            case 'de_CH':
            case 'de_AT':
                $language = 'german';
                break;
            case 'ru_RU':
                $language = 'russian';
                break;
            case 'pt_BR':
            case 'pt_PT_ao90':
            case 'pt_PT':
            case 'pt_AO':
                $language = 'portuguese';
                break;
            case 'nl_NL':
            case 'nl_NL_formal':
            case 'nl_BE':
                $language = 'dutch';
                break;
            case 'da_DK':
                $language = 'danish';
                break;
            case 'it_IT':
                $language = 'italian';
                break;
            case 'pl_PL':
                $language = 'polish';
                break;
            case 'sk_SK':
                $language = 'slovak';
                break;
            case 'nb_NO':
                $language = 'norwegian';
                break;
            case 'sv_SE':
                $language = 'swedish';
                break;
            case 'ar':
            case 'ary':
                $language = 'arabic';
                break;
            case 'sr_RS':
                $language = 'serbian';
                break;
            case 'fi':
                $language = 'finnish';
                break;
            case 'he_IL':
                $language = 'hebrew';
                break;
            case 'hi_IN':
                $language = 'hindi';
                break;
            case 'hu_HU':
                $language = 'hungarian';
                break;
            case 'ro_RO':
                $language = 'romanian';
                break;
            case 'uk':
                $language = 'ukrainian';
                break;
            default:
                $language = 'english';
                break;
        }

        return $language;
    }

    /**
     * Get ignore words
     */
    public static function getIgnoreWords()
    {
        if (is_null(self::$ignore_words)) {
            $words = get_option('wpil_2_ignore_words', null);
            // get the user's current language
            $selected_language = self::getSelectedLanguage();

            // if there are no stored words or the current language is different from the selected one
            if (is_null($words) || (WPIL_CURRENT_LANGUAGE !== $selected_language)) {
                $ignore_words_file = self::getIgnoreFile($selected_language);
                $words = file($ignore_words_file);

                foreach($words as $key => $word) {
                    $words[$key] = trim(Wpil_Word::strtolower($word));
                }
            } else {

                $words = explode("\n", $words);
                $words = array_unique($words);
                sort($words);

                foreach($words as $key => $word) {
                    $words[$key] = trim(Wpil_Word::strtolower($word));
                }
            }

            self::$ignore_words = $words;
        }

        return self::$ignore_words;
    }

    /**
     * Get stemmed versions of the ignore words
     */
    public static function getStemmedIgnoreWords()
    {
        if (is_null(self::$stemmed_ignore_words)) {
            $words = self::getIgnoreWords();
            foreach($words as $key => $word) {
                $words[$key] = Wpil_Word::remove_accents(trim(Wpil_Stemmer::Stem($word)));
            }

            // remove any duplicates
            $words = array_keys(array_flip($words));

            self::$stemmed_ignore_words = $words;
        }

        return self::$stemmed_ignore_words;
    }

    /**
     * Gets all current ignore word lists.
     * The word list for the language the user is currently using is loaded from the settings.
     * All other languages are loaded from the word files
     **/
    public static function getAllIgnoreWordLists(){
        $current_language       = self::getSelectedLanguage();
        $supported_languages    = self::getSupportedLanguages();
        $all_ignore_lists       = array();

        // go over all currently supported languages
        foreach($supported_languages as $language_id => $supported_language){

            // if the current language is the user's selected one
            if($language_id === $current_language){

                $words = get_option('wpil_2_ignore_words', null);
                if(is_null($words)){
                    $words = self::getIgnoreWords();
                }else{
                    $words = explode("\n", $words);
                    $words = array_unique($words);
                    sort($words);
                    foreach($words as $key => $word) {
                        $words[$key] = trim(Wpil_Word::strtolower($word));
                    }
                }

                $all_ignore_lists[$language_id] = $words;
            }else{
                $ignore_words_file = self::getIgnoreFile($language_id);
                $words = array();
                if(file_exists($ignore_words_file)){
                    $words = file($ignore_words_file);
                }else{
                    // if there is no word file, skip to the next one
                    continue;
                }
                
                if(empty($words)){
                    $words = array();
                }
                
                foreach($words as $key => $word) {
                    $words[$key] = trim(Wpil_Word::strtolower($word));
                }
                
                $all_ignore_lists[$language_id] = $words;
            }
        }

        return $all_ignore_lists;
    }

    /**
     * Get ignore words file based on current language
     *
     * @param $language
     * @return string
     */
    public static function getIgnoreFile($language)
    {
        switch($language){
            case 'spanish':
                $file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/ignore_word_lists/ES_ignore_words.txt';
                break;
            case 'french':
                $file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/ignore_word_lists/FR_ignore_words.txt';
                break;
            case 'german':
                $file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/ignore_word_lists/DE_ignore_words.txt';
                break;
            case 'russian':
                $file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/ignore_word_lists/RU_ignore_words.txt';
                break;
            case 'portuguese':
                $file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/ignore_word_lists/PT_ignore_words.txt';
                break;
            case 'dutch':
                $file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/ignore_word_lists/NL_ignore_words.txt';
                break;
            case 'danish':
                $file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/ignore_word_lists/DA_ignore_words.txt';
                break;
            case 'italian':
                $file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/ignore_word_lists/IT_ignore_words.txt';
                break;
            case 'polish':
                $file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/ignore_word_lists/PL_ignore_words.txt';
                break;            
            case 'slovak':
                $file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/ignore_word_lists/SK_ignore_words.txt';
                break;
            case 'norwegian':
                $file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/ignore_word_lists/NO_ignore_words.txt';
                break;
            case 'swedish':
                $file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/ignore_word_lists/SW_ignore_words.txt';
                break;            
            case 'arabic':
                $file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/ignore_word_lists/AR_ignore_words.txt';
                break;
            case 'serbian':
                $file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/ignore_word_lists/SR_ignore_words.txt';
                break;
            case 'finnish':
                $file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/ignore_word_lists/FI_ignore_words.txt';
                break;
            case 'hebrew':
                $file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/ignore_word_lists/HE_ignore_words.txt';
                break;
            case 'hindi':
                $file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/ignore_word_lists/HI_ignore_words.txt';
                break;
            case 'hungarian':
                $file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/ignore_word_lists/HU_ignore_words.txt';
                break;
            case 'romanian':
                $file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/ignore_word_lists/RO_ignore_words.txt';
                break;
            case 'ukrainian':
                $file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/ignore_word_lists/UK_ignore_words.txt';
                break;
            default:
                $file = WP_INTERNAL_LINKING_PLUGIN_DIR . 'includes/ignore_word_lists/EN_ignore_words.txt';
                break;
        }

        return $file;
    }

    /**
     * Get selected post types
     *
     * @return mixed|void
     */
    public static function getPostTypes()
    {
        return get_option('wpil_2_post_types', ['post', 'page']);
    }


    /**
     * Get the post types that users have limited the suggestions to
     *
     * @return mixed|void
     */
    public static function getSuggestionPostTypes()
    {
        return get_option('wpil_suggestion_limited_post_types', self::getPostTypes());
    }

    /**
     * Gets the maximum number of words that should go into an anchor.
     * The default is 10
     * 
     * @return int
     */
    public static function getSuggestionMaxAnchorSize(){
        return (int) get_option('wpil_suggestion_anchor_max_size', 10);
    }

    /**
     * Gets the minimum number of words that should go into an anchor.
     * The default is 1 so that single-word target keyword matches can be allowed
     * 
     * @return int
     */
    public static function getSuggestionMinAnchorSize(){
        return (int) get_option('wpil_suggestion_anchor_min_size', 1);
    }

    /**
     * Get merged array of post types and term types
     *
     * @return array
     */
    public static function getAllTypes()
    {
        return array_merge(self::getPostTypes(), self::getTermTypes());
    }

    /**
     * Get selected post statuses
     *
     * @return array
     */
    public static function getPostStatuses()
    {
        return get_option('wpil_2_post_statuses', ['publish']);
    }

    public static function getInternalDomains(){
        $domains = get_transient('wpil_domains_marked_as_internal');
        if(empty($domains) && $domains === false){
            $domains = array();
            $domain_data = get_option('wpil_domains_marked_as_internal');
            $domain_data = explode("\n", $domain_data);
            foreach ($domain_data as $domain) {
                $pieces = wp_parse_url(trim($domain));
                if(!empty($pieces) && isset($pieces['host'])){
                    $domains[] = str_replace('www.', '', $pieces['host']);
                }
            }

            set_transient('wpil_domains_marked_as_internal', $domains, 15 * MINUTE_IN_SECONDS);
        }

        return $domains;
    }

    /**
     * Checks to see if ACF is installed on the site and if the user has disabled ACF processing or not
     * @return bool
     **/
    public static function get_acf_active(){
        if(!class_exists('ACF') || get_option('wpil_disable_acf', false)){
            return false;
        }

        return true;
    }

    /**
     * Gets the currently supported languages
     * 
     * @return array
     **/
    public static function getSupportedLanguages(){
        $languages = array(
            'english'       => 'English',
            'spanish'       => 'Español',
            'french'        => 'Français',
            'german'        => 'Deutsch',
            'russian'       => 'Русский',
            'portuguese'    => 'Português',
            'dutch'         => 'Nederlands',
            'danish'        => 'Dansk',
            'italian'       => 'Italiano',
            'polish'        => 'Polskie',
            'norwegian'     => 'Norsk bokmål',
            'swedish'       => 'Svenska',
            'slovak'        => 'Slovenčina',
            'arabic'        => 'عربي',
            'serbian'       => 'Српски / srpski',
            'finnish'       => 'Suomi',
            'hebrew'        => 'עִבְרִית',
            'hindi'         => 'हिन्दी',
            'hungarian'     => 'Magyar',
            'romanian'      => 'Română',
            'ukrainian'     => 'Українська',
        );
        
        return $languages;
    }

    /**
     * Gets the currently selected language
     * 
     * @return array
     **/
    public static function getSelectedLanguage(){
        return get_option('wpil_selected_language', 'english');
    }

    /**
     * Gets the language for the current processing run.
     * Does a check to see if there's a translation plugin active.
     * If there is, it tries to set the current language to the current post's language.
     * If that's not possible, or there isn't a translation plugin, it defaults to the set language
     **/
    public static function getCurrentLanguage(){

        // if Polylang is active
        if(defined('POLYLANG_VERSION')){
            // see if we're creating suggestions and there's a post
            if( isset($_POST['action']) && ($_POST['action'] === 'get_post_suggestions' || $_POST['action'] === 'update_suggestion_display') &&
                isset($_POST['post_id']) && !empty($_POST['post_id']))
            {
                global $wpdb;
                $post_id = (int) $_POST['post_id'];

                // get the language ids
                $language_ids = $wpdb->get_col("SELECT `term_taxonomy_id` FROM $wpdb->term_taxonomy WHERE `taxonomy` = 'language'");

                // if there are no ids, return the selected language from the settings
                if(empty($language_ids)){
                    return self::getSelectedLanguage();
                }

                $language_ids = implode(', ', $language_ids);

                // check the term_relationships to see if any are applied to the current post
                $tax_id = $wpdb->get_var("SELECT `term_taxonomy_id` FROM $wpdb->term_relationships WHERE `object_id` = {$post_id} AND `term_taxonomy_id` IN ({$language_ids})");

                // if there are no ids, return the selected language from the settings
                if(empty($tax_id)){
                    return self::getSelectedLanguage();
                }

                // query the wp_terms to get the language code for the applied language
                $code = $wpdb->get_var("SELECT `slug` FROM $wpdb->terms WHERE `term_id` = {$tax_id}");

                // if we've gotten the language code, see if we support the language
                if($code){
                    $supported_language_codes = array(
                        'en' => 'english',
                        'es' => 'spanish',
                        'fr' => 'french',
                        'de' => 'german',
                        'ru' => 'russian',
                        'pt' => 'portuguese',
                        'nl' => 'dutch',
                        'da' => 'danish',
                        'it' => 'italian',
                        'pl' => 'polish',
                        'sk' => 'slovak',
                        'nb' => 'norwegian',
                        'sv' => 'swedish',
                        'sd' => 'arabic',
                        'snd' => 'arabic',
                        'sr' => 'serbian',
                        'fi' => 'finnish',
                        'he' => 'hebrew',
                        'hi' => 'hindi',
                        'hu' => 'hungarian',
                        'ro' => 'romanian',
                        'uk' => 'ukrainian'
                    );

                    // if we support the language, return it as the active one
                    if(isset($supported_language_codes[$code])){
                        return $supported_language_codes[$code];
                    }
                }
            }
        }

        // if WPML is active
        if(self::wpml_enabled()){
            // see if we're creating suggestions and there's a post
            if( isset($_POST['action']) && ($_POST['action'] === 'get_post_suggestions' || $_POST['action'] === 'update_suggestion_display') &&
            isset($_POST['post_id']) && !empty($_POST['post_id']))
            {
                global $wpdb;
                $post_id = (int) $_POST['post_id'];
                $post_type = get_post_type($post_id);
                $post_type = 'post_' . $post_type;
                $code = $wpdb->get_var("SELECT language_code FROM {$wpdb->prefix}icl_translations WHERE element_id = $post_id AND `element_type` = '{$post_type}'");

                if(!empty($code)){

                    $supported_language_codes = array(
                        'en' => 'english',
                        'es' => 'spanish',
                        'fr' => 'french',
                        'de' => 'german',
                        'ru' => 'russian',
                        'pt-br' => 'portuguese',
                        'pt-pt' => 'portuguese',
                        'nl' => 'dutch',
                        'da' => 'danish',
                        'it' => 'italian',
                        'pl' => 'polish',
                        'sk' => 'slovak',
                        'no' => 'norwegian',
                        'sv' => 'swedish',
                        'ar' => 'arabic',
                        'sr' => 'serbian',
                        'fi' => 'finnish',
                        'he' => 'hebrew',
                        'hi' => 'hindi',
                        'hu' => 'hungarian',
                        'ro' => 'romanian',
                        'uk' => 'ukrainian'
                    );

                    // if we support the language, return it as the active one
                    if(isset($supported_language_codes[$code])){
                        return $supported_language_codes[$code];
                    }
                }
            }
        }

        return self::getSelectedLanguage();
    }

    public static function getProcessingBatchSize(){
        $batch_size = (int) get_option('wpil_option_suggestion_batch_size', 300);
        if($batch_size < 10){
            $batch_size = 10;
        }
        return $batch_size;
    }

    /**
     * This function is used handle settting page submission
     *
     * @return  void
     */
    public static function save()
    {
        if (isset($_POST['wpil_save_settings_nonce'])
            && wp_verify_nonce($_POST['wpil_save_settings_nonce'], 'wpil_save_settings')
            && isset($_POST['hidden_action'])
            && $_POST['hidden_action'] == 'wpil_save_settings'
        ) {
            // ignore any external caches so they don't get in the way of the option saving
            Wpil_Base::ignore_external_object_cache(true);

            //prepare ignore words to save
            $ignore_words = sanitize_textarea_field(stripslashes(trim(base64_decode($_POST['ignore_words']))));
            $ignore_words = mb_split("\n|\r", $ignore_words);
            $ignore_words = array_unique($ignore_words);
            $ignore_words = array_filter(array_map('trim', $ignore_words));
            sort($ignore_words);
            $ignore_words = implode(PHP_EOL, $ignore_words);

            //update ignore words
            update_option(WPIL_OPTION_IGNORE_WORDS, $ignore_words);

            if (empty($_POST[WPIL_OPTION_POST_TYPES]))
            {
                $_POST[WPIL_OPTION_POST_TYPES] = [];
            }

            if (empty($_POST['wpil_2_term_types'])) {
                $_POST['wpil_2_term_types'] = [];
            }

            // if the settings aren't set for showing all post types, remove all but the public ones
            if( empty($_POST['wpil_2_show_all_post_types']) &&
                isset($_POST['wpil_2_post_types']) &&
                !empty($_POST['wpil_2_post_types']))
            {
                $types_available = get_post_types(['public' => true]);
                foreach($_POST['wpil_2_post_types'] as $key => $type){
                    if(!isset($types_available[$type])){
                        unset($_POST['wpil_2_post_types'][$key]);
                    }
                }
            }

            //save other settings
            $opt_keys = self::$keys;
            foreach($opt_keys as $opt_key) {
                if (array_key_exists($opt_key, $_POST)) {
                    if(is_array($_POST[$opt_key])){
                        update_option($opt_key, array_map('sanitize_text_field', $_POST[$opt_key]));
                    }elseif($opt_key === 'wpil_ignore_shortcodes_by_name'){
                        update_option($opt_key, sanitize_textarea_field($_POST[$opt_key]));
                    }else{
                        update_option($opt_key, sanitize_text_field($_POST[$opt_key]));
                    }
                }
            }

            // clear the item caches if they're set
            delete_transient('wpil_ignore_links');
            delete_transient('wpil_ignore_external_links');
            delete_transient('wpil_ignore_keywords_posts');
            delete_transient('wpil_ignore_categories');
            delete_transient('wpil_domains_marked_as_internal');
            delete_transient('wpil_links_to_ignore');
            delete_transient('wpil_suggest_to_outbound_posts');
            delete_transient('wpil_ignore_shortcodes_by_name');
            delete_transient('wpil_ignore_acf_fields');
            delete_transient('wpil_ignore_click_links');
            delete_transient('wpil_custom_fields_to_process');
            delete_transient('wpil_redirected_post_ids');
            delete_transient('wpil_redirected_post_urls');

            // flush the cache to make sure nothing's hanging
            wp_cache_flush();

            wp_redirect(admin_url('admin.php?page=link_whisper_settings&success'));
            exit;
        }
    }

    public static function getSkipSectionType()
    {
        return 'sentences';
    }

    public static function getSkipSentences()
    {
        return get_option('wpil_skip_sentences', 3);
    }

    /**
     * Gets the max number of suggestions that will be shown at once in the suggestion panel.
     * @return int
     **/
    public static function get_max_suggestion_count(){
        return (int) get_option('wpil_max_suggestion_count', 0);
    }

    /**
     * Checks to see if the site has a translation plugin active
     * 
     * @return bool
     **/
    public static function translation_enabled(){
        if(defined('POLYLANG_VERSION')){
            return true;
        }elseif(self::wpml_enabled()){
            return true;
        }

        return false;
    }

    /**
     * Check if WPML installed and has at least 2 languages
     *
     * @return bool
     */
    public static function wpml_enabled()
    {
        global $wpdb;

        // if WPML is activated
        if(function_exists('icl_object_id') || class_exists('SitePress')){
            $languages_count = 1;
            $table = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}icl_languages'");
            if ($table == $wpdb->prefix . 'icl_languages') {
                $languages_count = $wpdb->get_var("SELECT count(*) FROM {$wpdb->prefix}icl_languages WHERE active = 1");
            } else {
                $languages_count = $wpdb->get_var("SELECT count(*) FROM {$wpdb->term_taxonomy} WHERE taxonomy = 'language'");
            }

            if (!empty($languages_count) && $languages_count > 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * Gets the list of WPML supported locales
     **/
    public static function get_wpml_locales(){
        $locales = array();

        if(function_exists('icl_get_languages_locales')){
            $locales = icl_get_languages_locales();
        }

        return $locales;
    }

    /**
     * Checks if the given local is one supported by WPML
     **/
    public static function is_supported_wpml_local($local = ''){
        if(empty($local)){
            return false;
        }

        $locales = self::get_wpml_locales();
        if(!empty($locales) && isset($locales[$local])){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Get checked term types
     *
     * @return array
     */
    public static function getTermTypes()
    {
        $terms = get_option('wpil_2_term_types', []);
        return array_intersect(array('category', 'post_tag', 'product_cat', 'product_tag'), $terms);
    }

    /**
     * Get ignore posts
     * Currently disabled.
     * Pulls posts from cache if available to save processing time.
     *
     * @return array
     */
    public static function getIgnorePosts()
    {
        return array();
        $posts = get_transient('wpil_ignore_links');
        if(empty($posts)){
            $posts = [];
            $links = get_option('wpil_ignore_links');
            $links = explode("\n", $links);
            foreach ($links as $link) {
                $link = trim($link);
                if(empty($link)){
                    continue;
                }

                $post = Wpil_Post::getPostByLink($link);
                if (!empty($post)) {
                    $posts[] = $post->type . '_' . $post->id;
                }
            }

            set_transient('wpil_ignore_links', $posts, 15 * MINUTE_IN_SECONDS);
        }

        return $posts;
    }

    /**
     * Get categories list to be ignored
     *
     * @return array
     */
    public static function getIgnoreCategoriesPosts()
    {
        return array(); // todo: remove if we implement ignore categories
    }

    /**
     * Gets an array of post ids to affirmatively make outbound links to.
     *
     * @return array
     */
    public static function getOutboundSuggestionPostIds()
    {
        $posts = get_transient('wpil_suggest_to_outbound_posts');
        if(empty($posts)){
            $posts = [];
            $links = get_option('wpil_suggest_to_outbound_posts', '');
            $links = explode("\n", $links);
            foreach ($links as $link) {
                $post = Wpil_Post::getPostByLink($link);
                if (!empty($post)) {
                    $posts[] = $post->type . '_' . $post->id;
                }
            }

            if(empty($posts)){
                $posts = 'no-posts';
            }

            set_transient('wpil_suggest_to_outbound_posts', $posts, 15 * MINUTE_IN_SECONDS);
        }

        // if there are no posts
        if($posts === 'no-posts'){
            // return an empty array
            $posts = array();
        }

        return $posts;
    }

    /**
     * Gets an array of type specific ids from the url input settings.
     */
    public static function getItemTypeIds($ids = array(), $type = 'post'){
        $data = array('post' => array(), 'term' => array());

        foreach($ids as $id){
            $dat = explode('_', $id);
            if(isset($dat[0]) && !empty($dat[0]) && isset($dat[1]) && !empty($dat[1])){
                $data[$dat[0]][] = $dat[1];
            }
        }

        if(isset($data[$type])){
            return $data[$type];
        }else{
            return $data;
        }
    }

    /**
     * Gets if the user wants to count links from related post plugins in the Links Report.
     * Returns false if the user has opted to show all links because that includes related post links already.
     **/
    public static function get_related_post_links()
    {
        return !empty(get_option('wpil_count_related_post_links', false));
    }

    /**
     * Gets if the user wants to run a special link update process when Gutenberg Reusable Blocks are Updated
     **/
    public static function update_reusable_block_links()
    {
        return !empty(get_option('wpil_update_reusable_block_links', false));
    }

    /**
     * Gets if the user wants to use "Ugly" permalinks in the reports.
     * It turns out that calculating the "Pretty" permalinks in the reports can take a TON of time.
     * Using the ugly ones hardly takes any time at all
     **/
    public static function use_ugly_permalinks()
    {
        return (!empty(get_option('wpil_use_ugly_permalinks', false)));
    }

    /**
     * Gets any active suggestion filter based on requested index
     * @param string $index The $_REQUEST or stored data index to search for
     * @return bool|array
     */
    public static function get_suggestion_filter($index = ''){
        if(empty($index)){
            return false;
        }

        $filters_persistent = true; //!empty(get_option('wpil_make_suggestion_filtering_persistent', false));
        $filtering_settings = ($filters_persistent) ? get_user_meta(get_current_user_id(), 'wpil_persistent_filter_settings', true) : false;

        $status = false;
        switch ($index) {
            // bool filters
            case 'same_category':
            case 'same_tag':
            case 'select_post_types':
            case 'link_orphaned':
            case 'same_parent':
                if($filters_persistent){
                    $status = (isset($filtering_settings[$index]) && !empty($filtering_settings[$index])) ? true: false;
                }else{
                    $status = (isset($_REQUEST[$index]) && !empty($_REQUEST[$index])) ? true: false;
                }
            break;
            // number array filters
            case 'selected_category':
            case 'selected_tag':
                if($filters_persistent){
                    $data = (isset($filtering_settings[$index]) && !empty($filtering_settings[$index])) ? $filtering_settings[$index]: array();
                }else{
                    $data = (isset($_REQUEST[$index]) && !empty($_REQUEST[$index])) ? $_REQUEST[$index]: array();
                }

                $status = (!empty($data) && is_array($data)) ? array_filter(array_map(function($dat){ return (int)$dat; }, $data)): array();
            break;
            // selected post type filter
            case 'selected_post_types':
                if($filters_persistent){
                    $data = (isset($filtering_settings[$index]) && !empty($filtering_settings[$index])) ? $filtering_settings[$index]: array();
                }else{
                    $data = (isset($_REQUEST[$index]) && !empty($_REQUEST[$index])) ? $_REQUEST[$index]: array();
                }

                // make sure the post types that are being requested are ones that the user selected in the settings
                $status = (!empty($data) && is_array($data)) ? array_intersect(Wpil_Settings::getPostTypes(), $data): array();
            break;
            default:
                $status = false;
                break;
        }

        return $status;
    }

    /**
     * Updates the suggestion filter settings based on $_REQUEST data
     **/
    public static function update_suggestion_filters(){
        // if we're not making the filters persistent
//        if(empty(get_option('wpil_make_suggestion_filtering_persistent', false))){
            // exit
//            return;
//        }

        // set the default state of the filters. (off)
        $setting_data = array(
            'same_category' => false,
            'same_tag' => false,
            'select_post_types' => false,
            'link_orphaned' => false,
            'same_parent' => false,
            'selected_category' => array(),
            'selected_tag' => array(),
            'selected_post_types' => array()
        );

        // go over the $_REQUEST variable to see if any of the filters are turned on
        foreach($setting_data as $index => $default){
            switch ($index) {
                // bool filters
                case 'same_category':
                case 'same_tag':
                case 'select_post_types':
                case 'link_orphaned':
                case 'same_parent':
                    $status = (isset($_REQUEST[$index]) && !empty($_REQUEST[$index])) ? true: false;
                break;
                // number array filters
                case 'selected_category':
                case 'selected_tag':
                    $data = (isset($_REQUEST[$index]) && !empty($_REQUEST[$index])) ? $_REQUEST[$index]: array();
                    $status = (!empty($data) && is_array($data)) ? array_filter(array_map(function($dat){ return (int)$dat; }, $data)): array();
                break;
                // selected post type filter
                case 'selected_post_types':
                    $data = (isset($_REQUEST[$index]) && !empty($_REQUEST[$index])) ? $_REQUEST[$index]: array();
                    // make sure the post types that are being requested are ones that the user selected in the settings
                    $status = (!empty($data) && is_array($data)) ? array_intersect(Wpil_Settings::getPostTypes(), $data): array();
                break;
                default:
                    $status = false;
                    break;
            }

            // if there is a filter active
            if(!empty($status)){
                // save the data
                $setting_data[$index] = $status;
            }
        }

        // update the stored settings with the results of our efforts
        update_user_meta(get_current_user_id(), 'wpil_persistent_filter_settings', $setting_data); // the settings are user-specific
    }

    /**
     * Gets the selected suggestion filtering options in a URL encoded string for when the suggestions are initially loaded
     * Checks for the global post type suggestion setting
     **/
    public static function get_suggestion_filter_string(){
        $indexes = array(
            'same_category',
            'same_tag',
            'select_post_types',
            'link_orphaned',
            'same_parent',
            'selected_category',
            'selected_tag',
            'selected_post_types'
        );

        $string_data = array();
        $suggestion_post_type_filtering = (!empty(get_option('wpil_limit_suggestions_to_post_types', false))) ? self::getSuggestionPostTypes() : false;

        foreach($indexes as $index){
            $filter_setting = self::get_suggestion_filter($index);
            if(!empty($filter_setting)){
                $string_data[$index] = is_array($filter_setting) ? implode(',', $filter_setting): $filter_setting;
            }
        }

        // if the user has selected a limited set of post types to point suggestions to
        if(!empty($suggestion_post_type_filtering) && is_array($suggestion_post_type_filtering)){
            $string_data['select_post_types'] = 1; // check the "filter post types" box
            $string_data['selected_post_types'] = implode(',', $suggestion_post_type_filtering); // and set the post types
        }

        return !empty($string_data) ? '&' . http_build_query($string_data): '';
    }

    /**
     * Gets the current content formatting level when pulling links from content
     **/
    public static function getContentFormattingLevel()
    {
        // if the user has programattically disabled formatting, return zero
        if(apply_filters('wpil_disable_content_link_formatting', false)){
            return 0;
        }

        return (int) get_option('wpil_content_formatting_level', 2);
    }

    /**
     * Gets if the user wants to override the global $post varible during link scans with a new one that matches the content currently being scanned.
     * Mostly it's a compatibility setting for shortcodes that rely on the global $post variable to determine what to display
     **/
    public static function overrideGlobalPost()
    {
        return !empty(get_option('wpil_override_global_post_during_scan', false));
    }

    /**
     * Gets an array of shortcode names that the user wants to ignore
     **/
    public static function get_ignored_shortcode_names(){
        $shortcodes = get_transient('wpil_ignore_shortcodes_by_name');
        if(empty($shortcodes)){

            $shortcodes = get_option('wpil_ignore_shortcodes_by_name', array());
            if(!empty($shortcodes)){
                $shortcodes = explode("\n", $shortcodes);
                foreach($shortcodes as $key => $shortcode){
                    $shortcode = trim(preg_replace('`[^\w-]`', '', $shortcode)); // remove all non-word chars minus hyphens from the shortcode name
                    if(empty($shortcode)){
                        unset($shortcodes[$key]);
                    }else{
                        $shortcodes[$key] = $shortcode;
                    }
                }
            }

            $defaults = self::get_default_ignored_shortcodes();
            if(!empty($defaults) && is_array($shortcodes)){
                $shortcodes = array_merge($shortcodes, $defaults);
            }elseif(!empty($defaults) && empty($shortcodes)){
                $shortcodes = $defaults;
            }

            if(empty($shortcodes)){
                $shortcodes = 'no-shortcodes-ignored';
            }

            set_transient('wpil_ignore_shortcodes_by_name', $shortcodes, 60 * MINUTE_IN_SECONDS);
        }

        if($shortcodes === 'no-shortcodes-ignored'){
            return array();
        }

        return $shortcodes;
    }

    /**
     * Gets all of the shortcodes that we don't want to/can't process
     **/
    public static function get_default_ignored_shortcodes(){
        $shortcodes = array();
        // if GiveWP is active
        if(defined('GIVE_VERSION')){
            // add the payment reciept shortcode
            $shortcodes[] = 'give_receipt';
        }

        // return the list of assmebled shortcodes
        return $shortcodes;
    }

    /**
     * Get links that was marked as external
     *
     * @return array
     */
    public static function getMarkedAsExternalLinks()
    {
        $links = get_option('wpil_marked_as_external', '');

        if (!empty($links)) {
            $links = explode("\n", $links);
            foreach ($links as $key => $link) {
                $links[$key] = trim($link);
            }

            return $links;
        }

        return [];
    }

    /**
     * Gets a list of posts that have had redirects applied to their urls.
     * Obtains the redirect list from plugins that offer redirects.
     * Results are cached for 5 minutes
     * 
     * @param bool $flip Should we return a flipped array of post ids so they can be searched easily?
     * @return array $post_ids And array of posts that have had redirections applied to them
     **/
    public static function getRedirectedPosts($flip = false){
        global $wpdb;

        $post_ids = get_transient('wpil_redirected_post_ids');

        if(!empty($post_ids) && $post_ids !== 'no-ids'){
            // refresh the transient
            set_transient('wpil_redirected_post_ids', $post_ids, 5 * MINUTE_IN_SECONDS);
            // and return the ids
            return ($flip) ? array_flip($post_ids) : $post_ids;
        }elseif($post_ids === 'no-ids'){
            // if a prevsious run hadn't found any ids, return an empty array
            return array();
        }

        // set up the id array
        $post_ids = array();

        // if RankMath is active and the redirections table exists
        if(defined('RANK_MATH_VERSION') && !empty($wpdb->query("SHOW TABLES LIKE '{$wpdb->prefix}rank_math_redirections'"))){
            $dest_url_cache = array();

            $permalink_format = get_option('permalink_structure', '');
            $post_name_position = false;

            if(false !== strpos($permalink_format, '%postname%')){
                $pieces = explode('/', $permalink_format);
                $piece_count = count($pieces);
                $post_name_position = array_search('%postname%', $pieces);
            }

            // get the active redirect rules from Rank Math
            $active_redirections = $wpdb->get_results("SELECT `id`, `url_to` FROM {$wpdb->prefix}rank_math_redirections WHERE `status` = 'active'");

            // if there are redirections
            if(!empty($active_redirections)){
                $redirection_ids = array();
                foreach($active_redirections as $dat){
                    // create a list of the destination urls so that we can exclude posts that aren't hidden by redirects
                    if(!isset($dest_url_cache[$dat->url_to])){
                        $post = Wpil_Post::getPostByLink($dat->url_to);
                        if(!empty($post) && $post->type === 'post'){
                            $dest_url_cache[$dat->url_to] = $post->id;
                        }
                    }

                    $redirection_ids[] = $dat->id;
                }

                // if there are posts with updated urls, get the ids so we can ignore them
                $ignore_posts = '';
                if(!empty($dest_url_cache) && !empty(array_filter(array_values($dest_url_cache)))){
                    $ignore_posts = "AND `object_id` NOT IN (" . implode(', ',array_filter(array_values($dest_url_cache))) . ")";
                }

                $redirection_ids = implode(', ', $redirection_ids);
                $redirection_data = $wpdb->get_results("SELECT `from_url`, `object_id` FROM {$wpdb->prefix}rank_math_redirections_cache WHERE `redirection_id` IN ({$redirection_ids}) {$ignore_posts}"); // we're getting the redriects from the cache to save processing time. Rules based searching could take a long time

                // go over the data from the Rank Math cache
                $post_names = array();
                foreach($redirection_data as $dat){
                    // if a redirect was specified for a post, grab the id directly
                    if(isset($dat->object_id) && !empty($dat->object_id)){
                        $post_ids[] = $dat->object_id;
                    }else{
                        // if a url was redirected based on a rule, try to get the post name from the data so we can search the post table for it
                        $url_pieces = explode('/', $dat->from_url);
                        $url_pieces_count = count($url_pieces);

                        if($post_name_position && $url_pieces_count === $piece_count){  // if the url uses the permalink settings and therefor has the same number of pieces as the permalink string (EX: it's a post)
                            $post_names[] = $url_pieces[$post_name_position];
                        }elseif($url_pieces_count === 1){                               // if the url is just the slug
                            $post_names[] = $dat->from_url;
                        }elseif($url_pieces_count === 2 || $url_pieces_count === 3){    // if the url is just the slug, but there's a slash or two
                            $post_names[] = $url_pieces[1];
                        }
                    }
                }

                // if we've found the post names
                if(!empty($post_names)){
                    // query the post table with them to get the post ids
                    $post_names = implode('\', \'', $post_names);
                    $ids = $wpdb->get_col("SELECT `ID` FROM {$wpdb->posts} WHERE `post_name` IN ('{$post_names}')");

                    // if there's ids
                    if(!empty($ids)){
                        // add them to the list of post ids that are redirected away from
                        $post_ids = array_merge($post_ids, $ids);
                    }
                }
            }
        }

        // if SEOPress is active
        if(defined('SEOPRESS_PRO_VERSION')){
            // try getting redirected posts
            $query = "SELECT p.post_title as 'old_relative' FROM {$wpdb->posts} p 
                        LEFT JOIN {$wpdb->postmeta} m ON p.ID = m.post_id
                        WHERE p.post_type = 'seopress_404' AND m.meta_key = '_seopress_redirections_enabled' AND m.meta_value = 'yes'";

            $results = $wpdb->get_results($query);

            // if there are some posts
            if(!empty($results)){
                $ids = array();
                // go over them and obtain the redirected post's ids
                foreach($results as $dat){
                    $post = Wpil_Post::getPostByLink($dat->old_relative);

                    if(!empty($post)){
                        $ids[] = $post->id;
                    }
                }

                // if there are ids
                if(!empty($ids)){
                    // add them to the list of post ids that are redirected away from
                    $post_ids = array_merge($post_ids, $ids);
                }
            }
        }

        // if there aren't any ids
        if(empty($post_ids)){
            // make a note that there aren't any and return an empty
            set_transient('wpil_redirected_post_ids', 'no-ids', 5 * MINUTE_IN_SECONDS);
        }else{
            // save the fruits of our labours in the cache
            set_transient('wpil_redirected_post_ids', $post_ids, 5 * MINUTE_IN_SECONDS);
        }

        return ($flip && !empty($post_ids)) ? array_flip($post_ids) : $post_ids;
    }

    /**
     * Obtains an array of URLs that have been redirected away from and their destination URLs.
     * The output is an array of new URLs keyed to the old URLs that are being redirected away from.
     * All URLs are trailing slashed for consistency.
     * When comparing URLs in content to the URLs, be sure to slash them.
     *
     * Currently supports Rank Math, Yoast, SEO Press and Redirection (John Godley)
     * At the moment, we're only focusing on the absolute versions of the URLs.
     * Nobody has asked for relative, and there's only been a couple users that have ever mentioned using relative links.
     * Added to this is the fact that the inbound linking functionality only counts absolute URLs makes adding relative moot.
     **/
    public static function getRedirectionUrls(){
        global $wpdb;

        $urls = get_transient('wpil_redirected_post_urls');

        if($urls !== 'no-redirects' && !empty($urls)){
            // refresh the transient
            set_transient('wpil_redirected_post_urls', $urls, 5 * MINUTE_IN_SECONDS);
            // and return the URLs
            return $urls;
        }elseif($urls === 'no-redirects'){
            return array();
        }

        // set up the url array
        $urls = array();

        if(defined('RANK_MATH_VERSION') && !empty($wpdb->query("SHOW TABLES LIKE '{$wpdb->prefix}rank_math_redirections'"))){
            // get the active redirect rules from Rank Math
            $active_redirections = $wpdb->get_results("SELECT `id`, `url_to` FROM {$wpdb->prefix}rank_math_redirections WHERE `status` = 'active'");

            // if there are redirections
            if(!empty($active_redirections)){

                $redirection_ids = array();
                foreach($active_redirections as $dat){
                    $redirection_ids[$dat->id] = trailingslashit($dat->url_to);
                }

                $id_string = implode(', ', array_keys($redirection_ids));
                $redirection_data = $wpdb->get_results("SELECT `from_url`, `object_id`, `redirection_id` FROM {$wpdb->prefix}rank_math_redirections_cache WHERE `redirection_id` IN ({$id_string})"); // we're getting the redriects from the cache to save processing time. Rules based searching could take a long time

                // go over the data from the Rank Math cache
                foreach($redirection_data as $dat){
                    $url = trailingslashit(self::makeLinkAbsolute($dat->from_url));
                    $redirected_url = trailingslashit(self::makeLinkAbsolute($redirection_ids[$dat->redirection_id]));
                    $urls[$url] = $redirected_url;
                }
            }
        }

        if(defined('WPSEO_VERSION')){
            $active_redirections   = $wpdb->get_results("SELECT option_name, option_value FROM  {$wpdb->options} WHERE option_name = 'wpseo-premium-redirects-export-plain'");
            foreach ( $active_redirections as $redirection ) {
                $dat = maybe_unserialize($redirection->option_value);
                if(!empty($dat)){
                    foreach($dat as $key => $d){
                        $url = trailingslashit(self::makeLinkAbsolute($key));
                        $redirected_url = trailingslashit(self::makeLinkAbsolute($d['url']));
                        $urls[$url] = $redirected_url;
                    }
                }
            }
        }

        /**
         * Search for the redirects from the dedicated redirect pl;ugin last to override the SEO plugins' redirects
         **/
        if(defined('REDIRECTION_VERSION') && !empty($wpdb->query("SHOW TABLES LIKE '{$wpdb->prefix}redirection_items'"))){
            // get the redirect plugin data
            $active_redirections = $wpdb->get_results("SELECT `url`, `action_data` FROM {$wpdb->prefix}redirection_items WHERE `match_type` ='url' AND `match_url` != 'regex'");

            // add the redirections to the url list
            foreach($active_redirections as $dat){
                if(is_string($dat->action_data)){
                    $url = trailingslashit(self::makeLinkAbsolute($dat->url));
                    $action_data = trailingslashit(self::makeLinkAbsolute($dat->action_data));
                    $urls[$url] = $action_data;
                }
            }
        }

        // if SEOPress is active
        if(defined('SEOPRESS_PRO_VERSION')){
            // try getting redirected posts...
            // We're specifically searching for posts that aren't regex-based, are currently active, and result in a 3xx response code so it's not a dead end result
            $query = "SELECT p.post_title AS 'old_relative', m.meta_value as 'new_absolute' FROM {$wpdb->posts} p 
                LEFT JOIN {$wpdb->postmeta} m ON p.ID = m.post_id
                LEFT JOIN {$wpdb->postmeta} ex ON p.ID = ex.post_id AND ex.meta_key = '_seopress_redirections_enabled_regex' 
                LEFT JOIN {$wpdb->postmeta} act ON p.ID = act.post_id AND act.meta_key = '_seopress_redirections_enabled' 
                LEFT JOIN {$wpdb->postmeta} red ON p.ID = red.post_id AND red.meta_key = '_seopress_redirections_type' 
                WHERE p.post_type = 'seopress_404' AND m.meta_key = '_seopress_redirections_value' AND act.meta_value = 'yes' AND ex.meta_key IS NULL AND red.meta_value IN (301,302,307)";
            $results = $wpdb->get_results($query);

            // if there are some posts
            if(!empty($results)){
                // go over them 
                foreach($results as $dat){
                    $url = trailingslashit(self::makeLinkAbsolute($dat->old_relative));
                    $redirected_url = trailingslashit($dat->new_absolute);
                    $urls[$url] = $redirected_url;
                }
            }
        }

        // if Custom Permalinks is active
        if(defined('CUSTOM_PERMALINKS_FILE') && class_exists('Custom_Permalinks_Frontend') && false){

            // TODO: work out why I'm not getting the original post links like I expect to.
            // create a CP url handler class
            $permalink_handler = new Custom_Permalinks_Frontend();

            // search the db for changed urls
			$ids = $wpdb->get_col("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'custom_permalink'");

            // if there are some posts
            if(!empty($ids)){
                // go over them 
                foreach($ids as $id){
                    $url = trailingslashit($permalink_handler->original_post_link($id));
                    $redirected_url = $permalink_handler->custom_post_link($url, get_post($id));
                    $urls[$url] = $redirected_url;
                }
            }
        }

        // if we've found some redirected urls
        if(!empty($urls)){
            // save the fruits of our labours in the cache
            set_transient('wpil_redirected_post_urls', $urls, 5 * MINUTE_IN_SECONDS);
        }else{
            // otherwise, set a flag so we know there's no urls to keep an eye out for
            set_transient('wpil_redirected_post_urls', 'no-redirects', 5 * MINUTE_IN_SECONDS);
        }

        if('no-redirects' === $urls){
            return array();
        }

        return $urls;
    }

    /**
     * Obtains an array of ids from posts that we know have been hidden by redirects.
     * Our standard for 'hidden' are that the original post is inaccessible by url due to being redirected to a different post.
     * 
     * @param bool $return_hidden_ids Should we just return the ids of posts that have been hidden?
     * @return array
     **/
    public static function getPostsHiddenByRedirects($return_hidden_ids = false){
        $posts = get_transient('wpil_redirected_hidden_posts');

        if(!empty($posts) && $posts !== 'no-redirects'){
            // refresh the transient
            set_transient('wpil_redirected_hidden_posts', $posts, 15 * MINUTE_IN_SECONDS);
            // and return the URLs
            return ($return_hidden_ids)? array_keys($posts): $posts;
        }elseif($posts === 'no-redirects'){
            return array();
        }

        $urls = self::getRedirectionUrls();

        if(empty($urls)){
            set_transient('wpil_redirected_hidden_posts', 'no-redirects', 15 * MINUTE_IN_SECONDS);
            return array();
        }

        $posts = array();
        foreach($urls as $old_url => $new_url){
            $old_post = Wpil_Post::getPostByLink($old_url);

            // if we can't identify the original post
            if(empty($old_post)){
                // skip to the next URL since we can't confirm if the original post is hidden or not
                continue;
            }

            // try getting the new post
            $new_post = Wpil_Post::getPostByLink($new_url);
            // if there's no post that we can find
            if(empty($new_post)){
                // skip to the next one
                continue;
            }

            // if we've made it here, check if the ids are different between the posts
            if($old_post->id !== $new_post->id){
                // if it is different, we know that the post is hidden by a redirect
                $posts[$old_post->id] = $new_post->id;
            }
        }

        // if we've managed to find some hidden posts
        if(!empty($posts)){
            // save the fruits of our labours in the cache
            set_transient('wpil_redirected_hidden_posts', $posts, 15 * MINUTE_IN_SECONDS);
        }else{
            // otherwise, set a flag so we know there's no posts to keep an eye out for
            set_transient('wpil_redirected_hidden_posts', 'no-redirects', 15 * MINUTE_IN_SECONDS);
        }

        return $posts;
    }

    /**
     * Makes the supplied link an absolute one.
     * If the link is already absolute, the link is returned unchanged
     * 
     * @param string $url The relative link to make absolute
     * @return string $url The absolute version of the link
     **/
    public static function makeLinkAbsolute($url){
        $site_url = trailingslashit(get_home_url());
        $site_domain = wp_parse_url($site_url, PHP_URL_HOST);
        $site_scheme = wp_parse_url($site_url, PHP_URL_SCHEME);
        $url_domain = wp_parse_url($url, PHP_URL_HOST);

        // if the link isn't pointing to the current domain, 
        if( strpos($url, $site_domain) === false && 
            empty($url_domain) &&                       // but also isn't pointing to an external one
            strpos($url, 'www.') !== 0)                 // and doesn't start with "www.". (Even though browsers DO consider this to be a relative URL. The user didn't mean for it to be)
        {
            $url = ltrim($url, '/');
            $url_pieces = array_reverse(explode('/', rtrim(trim($site_url), '/')));

            foreach($url_pieces as $piece){
                if(empty($piece) || false === strpos(trim($url), $piece)){
                    $url = $piece . '/' . $url;
                }
            }
        }elseif(strpos($url, 'http') === false){
            $url = rtrim($site_scheme, ':') . '://' . ltrim($url, '/');
        }

        return $url;
    }

    /**
     * Gets the labels for the given post types.
     * Currently, only gets the labels for the public post types because the non-public ones are usually utility post types and the labels are often generic.
     * So if we used their given labels, it may confuse the user.
     *
     * @param string|array $post_types The list of post types that we're getting the labels for. Can also accept a single post type string
     * @return array $labled_types An array of post type labels keyed to their respective post types. Or an empty array if we can't find the post types...
     **/
    public static function getPostTypeLabels($post_types = array()){
        $labled_types = array();

        if(empty($post_types) || (!is_array($post_types) && !is_string($post_types))){
            return $labled_types;
        }

        if(is_string($post_types)){
            $post_types = array($post_types);
        }

        foreach($post_types as $type){
            $type_object = get_post_type_object($type);
            if(!empty($type_object)){
                if(!empty($type_object->public)){
                    $labled_types[$type_object->name] = $type_object->label;
                }else{
                    $labled_types[$type_object->name] = $type_object->name;
                }
            }
        }

        return $labled_types;
    }

    /**
     * Gets an array of WP constants that are active on the site and could have some impact on Link Whisper's functioning.
     **/
    public static function get_wp_constants($constant = ''){
        $constants = array();

        if(defined('WP_MEMORY_LIMIT')){
            $constants['WP_MEMORY_LIMIT'] = WP_MEMORY_LIMIT;
        }

        if(defined('WP_MAX_MEMORY_LIMIT')){
            $constants['WP_MAX_MEMORY_LIMIT'] = WP_MAX_MEMORY_LIMIT;
        }
        
        if(defined('DISABLE_WP_CRON')){
            $constants['DISABLE_WP_CRON'] = DISABLE_WP_CRON;
        }

        if(!empty($constant) && !empty($constants) && isset($constants[$constant])){
            return $constants[$constant];
        }elseif(!empty($constant)){
            return null;
        }

        return $constants;
    }

    /**
     * Recursively applies sanitize_text_field to array values
     **/
    public static function simple_textfield_array_sanitizer($array){

        $cleaned = array();
        foreach($array as $index => $data){
            if(is_array($data)){
                $cleaned[sanitize_text_field($index)] = Wpil_Settings::simple_textfield_array_sanitizer($data);
            }else{
                $cleaned[sanitize_text_field($index)] = sanitize_text_field($data);
            }
        }

        return $cleaned;
    }
}
