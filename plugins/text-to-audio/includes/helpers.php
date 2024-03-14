<?php
use TTA\TTA_Helper;
/**
 * If classic editor is active then on new-post and edit post
 * activate recording  for blog content.
 */
function tta_clean_content($text) {
    $quotationMarks = array(
        "'" => "\'",
        '"' => '\"',
        '&#8216;' => "\'",
        '&#8217;' => "\'",
        '&rsquo;' => "\'",
        '&lsquo;' => "\'",
        '&#8218;' => '',
        '&#8220;' => '\"',
        '&#8221;' => '\"',
        '&#8222;' => '\"',
        '&ldquo;' => '\"',
        '&rdquo;' => '\"',
        '&quot;' => '\"',
    );

    $otherMarks = array(
        '&auml;' => 'ä',
        '&Auml;' => 'Ä',
        '&ouml;' => 'ö',
        '&Ouml;' => 'Ö',
        '&uuml;' => 'ü',
        '&Uuml;' => 'Ü',
        '&szlig;' => 'ß',
        '&euro;' => '€',
        '&copy;' => '©',
        '&trade;' => '™',
        '&reg;' => '®',
        '&nbsp;' => '',
        '&mdash;' => '—',
        '&amp;' => '&',
        '&gt;' => 'greater than',
        '&lt;' => 'less than',
        '&#8211;' => '-',
        '&#8212;' => '—',
    );

    $text = apply_filters('tta_before_clean_content', $text);

    $text = wp_strip_all_tags($text, true);

    $text = apply_filters('tta_after_clean_content', $text);

    $text = str_replace(array_keys($quotationMarks), array_values($quotationMarks), $text);
    $text = str_replace(array_keys($otherMarks), array_values($otherMarks), $text);

    // CF 16-Oct-19: We want to make sure no quotes are over-escaped (if somebody writes \" it will get substituted as \\",
    // which will escape the slash instead of the quotation mark. We don't merge them in one regex because neither mark
    // can _always_ be substituted with the other without changing the meaning of the sentence for the TTS engine.
    // Note: backspaces need to be doubled. The first regex (\\\\{2,}") means: match two or more \ followed by "
    $text = preg_replace('/\\\\{2,}"/', '\"', $text);
    $text = preg_replace("/\\\\{2,}'/", "\'", $text);

    $text = preg_replace('/\s+/', ' ', trim($text)); // Get rid of /n and /s in the string.

    return apply_filters('tta_clean_content', $text);

}

/**
 * 
 */
function tta_should_add_dilimiter($title, $delimiter) {
    $dilimiterArr = ['.', ',', '?', '!', '|', ];
    $end = substr($title, -1);
    if(in_array($end, $dilimiterArr)){
        return $title. ' ';
    }

    if(! $title) {
        return $title;
    }

    return $title.$delimiter. " ";

}


/**
 * @param $atts
 *
 * @param $is_block
 *
 * @return string
 */
function tta_get_button_content($atts, $is_block = false) {
    $settings = (array) get_option('tta_settings_data');
    // this is a pro feature to show button on blog main page with title and excerpt.
    if(!TTA_Helper::should_load_button()){
        return;
    }

    global $post;

    if ($is_block) {
        $customize = $atts;
    } else {
        $customize = (array) get_option('tta_customize_settings');
    }
    $recording = (array) get_option('tta_record_settings');


    // set default value.
    $settings['tta__settings_allow_listening_for_post_types'] = isset($settings['tta__settings_allow_listening_for_post_types']) && is_array($settings['tta__settings_allow_listening_for_post_types']) ? $settings['tta__settings_allow_listening_for_post_types'] : ['post'];

    $should_display_icon = isset( $settings['tta__settings_display_btn_icon'] ) && $settings['tta__settings_display_btn_icon'] ? 'inline-block' : 'none';

    static $btn_no = 0;
    $btn_no++;
    // TODO make it dynamic. now Recording it not available in UI.
    $sentence_delimiter = isset($recording['tta__sentence_delimiter']) ? $recording['tta__sentence_delimiter'] : '. ';
        global $post;


    $title = tta_clean_content( $post->post_title);
    $title = tta_should_add_dilimiter($title, $sentence_delimiter);
    $date = get_the_date('Y/m/d');
    
    // TODO: write functionality if current page is home page where content is excerpt.
    // if(is_single()) {
        // $description = get_the_content();
    // }elseif(did_filter( 'the_excerpt' )){
    //     $description = get_the_excerpt();
    // }
    $description = $post->post_content;
    $description_sanitized = tta_clean_content($description);

    $content     = apply_filters('tta__content_title', $title);
    $content    .= apply_filters('tta__content_description', $description_sanitized, $description, get_the_ID() );
    $content    = TTA_Helper::sazitize_content($content);

    // Button listen text.
     if($atts || has_filter('tta__button_text_arr')) {
        if( isset( $atts['text_to_read'] ) && $atts['text_to_read'] ) {
            $content = tta_clean_content($atts['text_to_read']);
        }
    }

    $content_read_time = apply_filters('tts_content_reading_time', 1, $content );
    $text_arr = get_button_text( $atts , $content_read_time);


    // Speak Icon
    $speakIcon = "<div class='tta_button'>";
    $speakIcon .= apply_filters( 'tta__listening_button_icon', '<span class="dashicons dashicons-controls-play"></span> ');
    $speakIcon .= '<span> '. $text_arr['listen_text'] . '<span></div>';
    // Button style.
    if (isset($customize) && count($customize)) {
        if ($is_block) {
            $backgroundColor = isset($customize['backgroundColor']) ? $customize['backgroundColor'] : '#184c53';
            $color = isset($customize['color']) ? $customize['color'] : '#ffffff';
            $width = isset($customize['width']) ? $customize['width'] : '100';
            $btn_style = 'background-color:' . esc_attr($backgroundColor) . ' !important;color:' . esc_attr($color) . ' !important;width:' . esc_attr($width) . '%;border:0;display:flex;align-content:center;justify-content:center;align-items:center;border-radius:4px;text-decoration:none;cursor:pointer;';
        } else {
            $btn_style = 'background-color:' . esc_attr($customize['backgroundColor']) . ';color:' . esc_attr($customize['color']) . ';width:' . esc_attr($customize['width']) . '%;border:0;display:flex;align-content:center;justify-content:center;align-items:center;border-radius:4px;text-decoration:none;cursor:pointer;';
        }
    } else {
        $btn_style = 'background-color:#184c53;color:#ffffff;width:100%;border:0;display:flex;align-content:center;justify-content:center;align-items:center;border-radius:4px;text-decoration:none;cursor:pointer;';
    }

    
    //Custom Css
    $custom_css = '';


    if (isset($customize['custom_css']) && '' !== $customize['custom_css']) {
        $custom_css = esc_attr($customize['custom_css']);
        $custom_css = str_replace( "\n", '', $custom_css );
    }
    $custom_css = compatibility_with_themes($custom_css);
    // Custom class to button.
    $class = (isset($text_arr['class'])) && strlen($text_arr['class']) ? esc_attr($text_arr['class']) : "";
    $class .= (isset($atts['class'])) && strlen($atts['class']) ? esc_attr($atts['class']) : "";
    
    $button = "<tts-play-button data-id='$btn_no' class='tts_play_button'></tts-play-button>";

    // init button scripts
    do_action('tts_enqueue_button_scripts' , $content, $btn_no, $class, $btn_style, $text_arr, $custom_css, $should_display_icon, $title, $date, $content_read_time, $atts);

    $data =  apply_filters( 'tts__listening_button', $button, $btn_no, $class );

    return $data;
}


add_action('tts_enqueue_button_scripts', 'tts_enqueue_button_scripts', 10, 11);

/**
 * Enqueue button scripts
 */
function tts_enqueue_button_scripts ($content, $btn_no, $class, $btn_style, $text_arr, $custom_css, $should_display_icon, $title, $date, $content_read_time, $atts) {
    // enqueue footer stript
    add_action('wp_print_footer_scripts', function() use ($content, $btn_no, $class, $btn_style, $text_arr, $custom_css, $should_display_icon, $title, $date, $content_read_time, $atts) { 
        $temp_title = trim(str_replace('.', '', $title));
        $title = trim(get_the_title());
        $title = tta_clean_content( $title );
        // Get plugin all settings and pass it to TTS javascript Object.
        $plugin_all_settings = TTA_Helper::tts_get_settings();
        if( isset($atts['lang']) && $atts['lang'] && isset($plugin_all_settings['listening']['tta__listening_lang'])  &&  $atts['lang'] != $plugin_all_settings['listening']['tta__listening_lang'] ) {
            $plugin_all_settings['listening']['tta__listening_lang'] = $atts['lang'];
        }

        if( isset($atts['voice']) && $atts['voice']  && isset($plugin_all_settings['listening']['tta__listening_voice'])  &&  $atts['voice'] != $plugin_all_settings['listening']['tta__listening_voice'] ) {
            $plugin_all_settings['listening']['tta__listening_voice'] = $atts['voice'];
        }
        
        if( apply_filters('tts_ignore_match_80_percent', false) && tts_text_match_80_percent($title , $temp_title) ) {
           get_enqued_js_object($content, $btn_no, $class, $btn_style, $text_arr, $custom_css, $should_display_icon, $title, $date, $content_read_time,  $plugin_all_settings);
        }else{
           get_enqued_js_object($content, $btn_no, $class, $btn_style, $text_arr, $custom_css, $should_display_icon, $title, $date, $content_read_time,  $plugin_all_settings);
        }
    });
}

function get_enqued_js_object($content, $btn_no, $class, $btn_style, $text_arr, $custom_css, $should_display_icon, $title, $date, $content_read_time, $plugin_all_settings) {
    
   global $post;

    // delete_post_meta($post->ID, 'tts_mp3_file_urls');
    $mp3_file_urls = TTA_Helper::get_mp3_file_urls($post);
    $language = TTA_Helper::tts_site_language($plugin_all_settings);
    $file_name = TTA_Helper::tts_file_name($title, $language);

    $object = ob_start();
    ?>
            <!-- Text To Speech TTS Settings  -->
        <script id='tts_button_settings_<?php echo $btn_no; ?>' >
            var ttsCurrentButtonNo = <?php echo $btn_no; ?>;
            var ttsCurrentContent = "<?php echo $content; ?>";
            var ttsListening = <?php echo json_encode($plugin_all_settings['listening']); ?>;
            var ttsCSSClass = "<?php echo $class; ?>";
            var ttsBtnStyle = "<?php echo $btn_style; ?>";
            var ttsTextArr = <?php echo json_encode($text_arr); ?>;
            var allSettings = <?php echo json_encode( $plugin_all_settings) ?>;
            var ttsCustomCSS = "<?php print($custom_css); ?>";
            var ttsShouldDisplayIcon = "<?php echo $should_display_icon; ?>";
            var readingTime = "<?php echo $content_read_time; ?>";
            var postId = "<?php echo $post->ID; ?>";
            var fileURLs = <?php echo json_encode($mp3_file_urls); ?>;


            var ttsSettings = {
                listening : ttsListening, 
                cssClass : ttsCSSClass , 
                btnStyle : ttsBtnStyle, 
                textArr : ttsTextArr, 
                customCSS : ttsCustomCSS, 
                shouldDisplayIcon : ttsShouldDisplayIcon,
                settings: allSettings,
                readingTime: readingTime,
                postId: postId,
                fileURLs: fileURLs,
            };


            var dateTitle = {
                title: "<?php echo $title; ?>",
                file_name: "<?php echo $file_name; ?>",
                date: "<?php echo $date; ?>",
                language: "<?php echo $language; ?>",
            }

            if(window.hasOwnProperty('TTS')){ // add content if a page have multiple button
                window.TTS.contents[ttsCurrentButtonNo] = ttsCurrentContent;
                window.TTS.extra[ttsCurrentButtonNo] = dateTitle;
            }else{ // add content for the if a page have one button
                window.TTS = {}
                window.TTS.contents = {}
                window.TTS.contents[ttsCurrentButtonNo] = ttsCurrentContent;
                window.TTS.extra  = {}
                window.TTS.extra[ttsCurrentButtonNo] = dateTitle;
            }

            // add settings
            if(!window.TTS.hasOwnProperty('settings')){
                window.TTS.settings = ttsSettings
            }

        </script>
    <?php
    $object = ob_get_contents();
    return $object;
}


function tts_text_match_80_percent($text1, $text2) {
    // Tokenize the input texts into words
    $words1 = explode(" ", $text1);
    $words2 = explode(" ", $text2);

    // Convert the arrays of words into sets for faster comparison
    $set1 = array_unique($words1);
    $set2 = array_unique($words2);

    // Calculate the intersection and union of the two sets
    $intersection = count(array_intersect($set1, $set2));
    $union = count($set1) + count($set2) - $intersection;

    // Calculate the Jaccard similarity coefficient
    $jaccardSimilarity = $intersection / $union;

    // If the similarity is at least 80%, return true; otherwise, return false
    if ($jaccardSimilarity >= 0.8) {
        return true;
    } else {
        return false;
    }
}





/**
 * Get button text
 */
function get_button_text( $atts, $content_read_time ) {
    $saved_texts = get_option('tta__button_text_arr');
    if(!$saved_texts){
        $saved_texts = set_initial_button_texts($content_read_time);
    }

    $listen_text = (isset($atts['listen_text'])) && strlen($atts['listen_text']) ? esc_html__( sanitize_text_field( $atts['listen_text'] ) ) : $saved_texts['listen_text'];
    $pause_text = (isset($atts['pause_text'])) && strlen($atts['pause_text']) ? esc_html__( sanitize_text_field( $atts['pause_text'] ) ) : $saved_texts['pause_text'];
    $resume_text = (isset($atts['resume_text'])) && strlen($atts['resume_text']) ? esc_html__( sanitize_text_field( $atts['resume_text'] ) ) : $saved_texts['resume_text'];
    $replay_text = (isset($atts['replay_text'])) && strlen($atts['replay_text']) ? esc_html__( sanitize_text_field( $atts['replay_text'] ) ) : $saved_texts['replay_text'];
    $start_text = (isset($atts['start_text'])) && strlen($atts['start_text']) ? esc_html__( sanitize_text_field( $atts['start_text'] ) ) : $saved_texts['start_text'];
    $stop_text = (isset($atts['stop_text'])) && strlen($atts['stop_text']) ? esc_html__( sanitize_text_field( $atts['stop_text'] ) ) : $saved_texts['stop_text'];

    $text_arr = [
        'listen_text' => $listen_text,
        'pause_text' => $pause_text,
        'resume_text' => $resume_text,
        'replay_text' => $replay_text,
        'start_text' => $start_text,
        'stop_text' => $stop_text,
    ];


    $customize_settings = (array) TTA_Helper::tts_get_settings('customize');
    $text_arr = get_text_array_from_shortcode($customize_settings, $text_arr);

    $text_arr =  apply_filters('tta__button_text_arr', $text_arr, $atts, $content_read_time );
    
    update_option( 'tta__button_text_arr', $text_arr);


    return $text_arr;
}



function get_text_array_from_shortcode($customize_settings, $text_arr) {

    if(isset($customize_settings['tta_play_btn_shortcode']) && $customize_settings['tta_play_btn_shortcode']) {
        $shortcode = $customize_settings['tta_play_btn_shortcode'];
    }
 
    // Define the pattern for matching attributes and their values
    $pattern = '/\b(\w+)="([^"]*)"/';
    
    // Match all attribute-value pairs
    preg_match_all($pattern, $shortcode, $matches, PREG_SET_ORDER);

    // Create an associative array to store attribute values
    $attributes = array();

    // Iterate through matches and populate the array
    foreach ($matches as $match) {
        $attributes[$match[1]] = $match[2];
    }

    foreach($attributes as $key => $value) {
        if( isset( $attributes[$key] ) && $attributes[$key] ) {
            $text = sanitize_text_field($value);
            $text = esc_html($text);
            if($text) {
                $text_arr[$key] = $text;
            }
        }
    }


    return $text_arr;

}


add_filter( 'the_content', 'add_listen_button',  999 );

/**
 * Add listening button to every post by default.
 */
function add_listen_button( $content ) {
    $settings = (array) get_option( 'tta_settings_data');
    if( ! isset( $settings['tta__settings_enable_button_add'] ) ) {
        TTA\TTA_Activator::activate(true);
    }
    global $post;
    if( isset( $settings['tta__settings_enable_button_add'] ) &&  $settings['tta__settings_enable_button_add'] ) {    
        // TODO: write functionality if current page is home page where content is excerpt.
        // if(is_single()) {
        //     add_filter( 'the_content', 'add_listen_button' );
        // }
        // elseif(did_filter( 'the_excerpt' )){
        //     add_filter( 'the_excerpt', 'add_listen_button' , 9999 );
        // }

        if( ! has_shortcode($post->post_content, 'tta_listen_btn') ) {

            ob_start();
            echo tta_get_button_content('');
            $button = ob_get_contents();
            ob_end_clean();
            
            return apply_filters('tts_button_with_content', $button.$content, $button, $content);
        }else{
             return $content;
        }
    }

    return $content;

}


function get_used_shortcodes( $content) {
    global $shortcode_tags;
    if ( false === strpos( $content, '[' ) ) {
        return array();
    }
    if ( empty( $shortcode_tags ) || ! is_array( $shortcode_tags ) ) {
        return array();
    }
    // Find all registered tag names in $content.
    preg_match_all( '@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches );
    $tagnames = array_intersect( array_keys( $shortcode_tags ), $matches[1] );
    return $tagnames;
}

/**
 * Is pro license active
 */
function is_pro_license_active() {
    if(is_pro_active()){
        return apply_filters('tts_is_pro_license_active', false);
    }

    return false;
}


function tta_is_audio_folder_writable() {
    $upload_dir             = wp_upload_dir();
    $base_dir               = $upload_dir['basedir'];

    if ( is_writable( $base_dir ) ) {
        return true;
    }
    return false;
}

function tta_get_default_languages(){
    return array(
        'af'             => 'Afrikaans',
        'ar'             => 'العربية',
        'ary'            => 'العربية المغربية',
        'as'             => 'অসমীয়া',
        'azb'            => 'گؤنئی آذربایجان',
        'az'             => 'Azərbaycan dili',
        'bel'            => 'Беларуская мова',
        'bg_BG'          => 'Български',
        'bn_BD'          => 'বাংলা',
        'bo'             => 'བོད་ཡིག',
        'bs_BA'          => 'Bosanski',
        'ca'             => 'Català',
        'ceb'            => 'Cebuano',
        'cs_CZ'          => 'Čeština',
        'cy'             => 'Cymraeg',
        'da_DK'          => 'Dansk',
        'de_DE_formal'   => 'Deutsch (Sie)',
        'de_DE'          => 'Deutsch',
        'de_CH_informal' => 'Deutsch (Schweiz, Du)',
        'de_CH'          => 'Deutsch (Schweiz)',
        'de_AT'          => 'Deutsch (Österreich)',
        'dsb'            => 'Dolnoserbšćina',
        'dzo'            => 'རྫོང་ཁ',
        'el'             => 'Ελληνικά',
        'en_CA'          => 'English (Canada)',
        'en_NZ'          => 'English (New Zealand)',
        'en_ZA'          => 'English (South Africa)',
        'en_GB'          => 'English (UK)',
        'en_AU'          => 'English (Australia)',
        'eo'             => 'Esperanto',
        'es_DO'          => 'Español de República Dominicana',
        'es_CR'          => 'Español de Costa Rica',
        'es_VE'          => 'Español de Venezuela',
        'es_CO'          => 'Español de Colombia',
        'es_CL'          => 'Español de Chile',
        'es_UY'          => 'Español de Uruguay',
        'es_PR'          => 'Español de Puerto Rico',
        'es_ES'          => 'Español',
        'es_GT'          => 'Español de Guatemala',
        'es_PE'          => 'Español de Perú',
        'es_MX'          => 'Español de México',
        'es_EC'          => 'Español de Ecuador',
        'es_AR'          => 'Español de Argentina',
        'et'             => 'Eesti',
        'eu'             => 'Euskara',
        'fa_AF'          => '(فارسی (افغانستان',
        'fa_IR'          => 'فارسی',
        'fi'             => 'Suomi',
        'fr_FR'          => 'Français',
        'fr_CA'          => 'Français du Canada',
        'fr_BE'          => 'Français de Belgique',
        'fur'            => 'Friulian',
        'gd'             => 'Gàidhlig',
        'gl_ES'          => 'Galego',
        'gu'             => 'ગુજરાતી',
        'haz'            => 'هزاره گی',
        'he_IL'          => 'עִבְרִית',
        'hi_IN'          => 'हिन्दी',
        'hr'             => 'Hrvatski',
        'hsb'            => 'Hornjoserbšćina',
        'hu_HU'          => 'Magyar',
        'hy'             => 'Հայերեն',
        'id_ID'          => 'Bahasa Indonesia',
        'is_IS'          => 'Íslenska',
        'it_IT'          => 'Italiano',
        'ja'             => '日本語',
        'jv_ID'          => 'Basa Jawa',
        'ka_GE'          => 'ქართული',
        'kab'            => 'Taqbaylit',
        'kk'             => 'Қазақ тілі',
        'km'             => 'ភាសាខ្មែរ',
        'kn'             => 'ಕನ್ನಡ',
        'ko_KR'          => '한국어',
        'ckb'            => 'كوردی‎',
        'lo'             => 'ພາສາລາວ',
        'lt_LT'          => 'Lietuvių kalba',
        'lv'             => 'Latviešu valoda',
        'mk_MK'          => 'Македонски јазик',
        'ml_IN'          => 'മലയാളം',
        'mn'             => 'Монгол',
        'mr'             => 'मराठी',
        'ms_MY'          => 'Bahasa Melayu',
        'my_MM'          => 'ဗမာစာ',
        'nb_NO'          => 'Norsk bokmål',
        'ne_NP'          => 'नेपाली',
        'nl_NL_formal'   => 'Nederlands (Formeel)',
        'nl_BE'          => 'Nederlands (België)',
        'nl_NL'          => 'Nederlands',
        'nn_NO'          => 'Norsk nynorsk',
        'oci'            => 'Occitan',
        'pa_IN'          => 'ਪੰਜਾਬੀ',
        'pl_PL'          => 'Polski',
        'ps'             => 'پښتو',
        'pt_PT'          => 'Português',
        'pt_PT_ao90'     => 'Português (AO90)',
        'pt_AO'          => 'Português de Angola',
        'pt_BR'          => 'Português do Brasil',
        'rhg'            => 'Ruáinga',
        'ro_RO'          => 'Română',
        'ru_RU'          => 'Русский',
        'sah'            => 'Сахалыы',
        'snd'            => 'سنڌي',
        'si_LK'          => 'සිංහල',
        'sk_SK'          => 'Slovenčina',
        'skr'            => 'سرائیکی',
        'sl_SI'          => 'Slovenščina',
        'sq'             => 'Shqip',
        'sr_RS'          => 'Српски језик',
        'sv_SE'          => 'Svenska',
        'sw'             => 'Kiswahili',
        'szl'            => 'Ślōnskŏ gŏdka',
        'ta_IN'          => 'தமிழ்',
        'ta_LK'          => 'தமிழ்',
        'te'             => 'తెలుగు',
        'th'             => 'ไทย',
        'tl'             => 'Tagalog',
        'tr_TR'          => 'Türkçe',
        'tt_RU'          => 'Татар теле',
        'tah'            => 'Reo Tahiti',
        'ug_CN'          => 'ئۇيغۇرچە',
        'uk'             => 'Українська',
        'ur'             => 'اردو',
        'uz_UZ'          => 'O‘zbekcha',
        'vi'             => 'Tiếng Việt',
        'zh_TW'          => '繁體中文',
        'zh_HK'          => '香港中文版	',
        'zh_CN'          => '简体中文',
    );
}

// Define rtl
function tta_is_rtl() {
    global $locale;
    if ( false !== strpos( $locale, 'ar' )
        || false !== strpos( $locale, 'he' )
        || false !== strpos( $locale, 'he_IL' )
        || false !== strpos( $locale, 'ur' )
    ) {
        $rtl = true;
    } else {
        $rtl = false;
    }

    return $rtl;
}


function compatibility_with_themes( $custom_css ) {
    
    if( false !== strpos(get_option('stylesheet'), 'twenty') ){
       $custom_css .= '#tts__listent_content_1.tts__listent_content  {max-width:650px;margin:auto;}';
    }

    return $custom_css;
}

function set_initial_button_texts($content_read_time) {
    if( ! get_option( 'tta__button_text_arr' ) ) {
        
        // Button listen text.
        $listen_text = __( "Listen", 'text-to-audio' );
        $pause_text =  __( 'Pause', 'text-to-audio' ) ;
        $resume_text =  __( 'Resume', 'text-to-audio' ) ;
        $replay_text =  __( 'Replay', 'text-to-audio' ) ;
        $start_text =  __( 'Start', 'text-to-audio' ) ;
        $stop_text = __( 'Stop', 'text-to-audio' ) ;

        update_option( 'tta__button_text_arr', [
            'listen_text' => $listen_text,
            'pause_text' => $pause_text,
            'resume_text' => $resume_text,
            'replay_text' => $replay_text,
            'start_text' => $start_text,
            'stop_text' => $stop_text,
        ]);

    }

    return apply_filters('tts_initial_button_texts', [
            'listen_text' => $listen_text,
            'pause_text' => $pause_text,
            'resume_text' => $resume_text,
            'replay_text' => $replay_text,
            'start_text' => $start_text,
            'stop_text' => $stop_text,
        ], $content_read_time);
}



function get_player_id() {
    $customize_settings = (array) TTA_Helper::tts_get_settings('customize');
    $customize_settings['buttonSettings'] = isset( $customize_settings['buttonSettings'] ) ? (array) $customize_settings['buttonSettings'] : [ 'id' => 1];
    $player_id = isset($customize_settings['buttonSettings']['id']) ? $customize_settings['buttonSettings']['id'] : 1;
    if(is_pro_license_active() && $player_id == 1) {
        $player_id = 2;
    }

    if(!is_pro_license_active() && $player_id >  1) {
        $player_id = 1;
    }
    
    return $player_id;
}
