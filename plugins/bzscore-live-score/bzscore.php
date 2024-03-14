<?php
if (! defined('ABSPATH')) exit; // Exit if accessed directly
/*
Plugin Name: BZScore - Live Score
Plugin URI: https://www.livescore.bz
Description: Live Football Scores without ads
Version: 1.6.0
Author: https://www.livescore.bz
Author URI: https://www.livescore.bz
License: GPLv3
*/

/**
 * Main Class - CPA stands for Color Picker API
 */
class CPA_Theme_Options {

    /*--------------------------------------------*
     * Attributes
     *--------------------------------------------*/

    /** Refers to a single instance of this class. */
    private static $instance = null;

    /* Saved options */
    public $options;

    /*--------------------------------------------*
     * Constructor
     *--------------------------------------------*/

    /**
     * Creates or returns an instance of this class.
     *
     * @return  CPA_Theme_Options A single instance of this class.
     */
    public static function get_instance() {
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Initializes the plugin by setting localization, filters, and administration functions.
     */
    private function __construct() {
        // Add the page to the admin menu
        add_action('admin_menu', array(&$this, 'add_page'));

        // Register page options
        add_action('admin_init', array(&$this, 'register_page_options'));

        // Register javascript
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_js'));

        // Get registered option
        $this->options = get_option('cpa_settings_options');
    }


    /*--------------------------------------------*
    * Functions
    *--------------------------------------------*/

    /**
     * Function that will add the options page under Setting Menu.
     */
    public function add_page() {
        // $page_title, $menu_title, $capability, $menu_slug, $callback_function
        add_options_page('Theme Options', 'BZScore', 'manage_options', __FILE__, array($this, 'display_page'));
    }

    /**
     * Function that will display the options page.
     */
    public function display_page() {
        ?>
        <div class="wrap">

            <h2>BZScore Theme Options</h2>
            <form method="post" action="options.php">
            <?php
                submit_button();
                settings_fields(__FILE__);
                do_settings_sections(__FILE__);
                submit_button();
            ?>
            </form>
        <h2>Notes</h2>
        <ul>
            <li>How to use the plugin?
                <blockquote>
                    * Use [bzscore] shortcode where you want to display the live scores.<br>
                    * You can set font-size , font-family attributes. I.E. [bzscore font-size="13px" font-family="sans-serif"]
                    * For England - Premier League [bzscore data-2="league" league-is="premier league" country-is="england"]
                </blockquote>
            </li>
            <li>When you set "Author Credit Link" to "Off" means:
                <blockquote>
                    * You are not linking back to author site.<br>
                    * BZScore will work in an iframe that sized 100% x 5000px .<br>
                    * Color and Font customizations will be default setting.<br>
                </blockquote>
            </li>
            <li>When you set "Author Credit Link" to "On" means:
                <blockquote>
                    * You are linking back to author site.<br>
                    * BZScore will work without an iframe.<br>
                    * Color and Font customizations will be your setting.<br>
                </blockquote>
            </li>
        </ul>
        </div> <!-- /wrap -->
        <?php
    }

    /**
     * Function that will register admin page options.
     */
    public function register_page_options() {
        // Add Section for option fields
        add_settings_section('cpa_section', 'FONT, COLOR and OTHER SETTINGS', array($this, 'display_section'), __FILE__); // id, title, display cb, page
        add_settings_field('cpa_bz_lang', 'Language', array($this, 'bz_lang_settings_field'), __FILE__, 'cpa_section'); // id, title, display cb, page, section
        add_settings_field('cpa_bz_clink', 'Author Credit Link', array($this, 'bz_c_link_settings_field'), __FILE__, 'cpa_section'); // id, title, display cb, page, section
        add_settings_field('cpa_font_main_color_field', 'Main Font Color', array($this, 'bzcolorcb'), __FILE__, 'cpa_section', array('o_name' => 'main_font_color', 'def_color' => '#000000')); // id, title, display cb, page, section
        add_settings_field('cpa_your_word', '', array($this, 'bz_your_word_cb'), __FILE__, 'cpa_section'); // id, title, display cb, page, section

        add_settings_section('cpa_section_comp_row', 'Competition Row', array($this, 'display_section'), __FILE__); // id, title, display cb, page
        add_settings_field('cpa_com_row_bg_field', 'Background Color', array($this, 'bzcolorcb'), __FILE__, 'cpa_section_comp_row', array('o_name' => 'comp_row_bg', 'def_color' => '#000000')); // id, title, display cb, page, section
        add_settings_field('cpa_com_row_fclr_field', 'Font Color', array($this, 'bzcolorcb'), __FILE__, 'cpa_section_comp_row', array('o_name' => 'comp_font_color', 'def_color' => '#FFFFFF')); // id, title, display cb, page, section

        add_settings_section('cpa_section_match_row', 'Match Row', array($this, 'display_section'), __FILE__); // id, title, display cb, page
        add_settings_field('cpa_match_row_even_bg_field', 'Even Row Background Color', array($this, 'bzcolorcb'), __FILE__, 'cpa_section_match_row', array('o_name' => 'match_row_even_bg', 'def_color' => '#f8f8f8')); // id, title, display cb, page, section
        add_settings_field('cpa_match_row_odd_bg_field', 'Odd Row Background Color', array($this, 'bzcolorcb'), __FILE__, 'cpa_section_match_row', array('o_name' => 'match_row_odd_bg', 'def_color' => '#e8e8e8')); // id, title, display cb, page, section
        add_settings_field('cpa_match_row_hover_bg_field', 'Mouse Over Background Color', array($this, 'bzcolorcb'), __FILE__, 'cpa_section_match_row', array('o_name' => 'match_row_hover_bg', 'def_color' => '#e3fbcc')); // id, title, display cb, page, section
        add_settings_field('cpa_match_row_scored_bg_field', 'Scored Team Background Color', array($this, 'bzcolorcb'), __FILE__, 'cpa_section_match_row', array('o_name' => 'match_row_scored_bg', 'def_color' => '#C8FF91')); // id, title, display cb, page, section
        add_settings_field('cpa_match_row_scored_fclr_field', 'Scored Team Font Color', array($this, 'bzcolorcb'), __FILE__, 'cpa_section_match_row', array('o_name' => 'match_row_scored_fclr', 'def_color' => '#000000')); // id, title, display cb, page, section
        add_settings_field('cpa_match_row_score_high_bg_field', 'Score Highlight Color', array($this, 'bzcolorcb'), __FILE__, 'cpa_section_match_row', array('o_name' => 'match_row_score_high', 'def_color' => '#FFFFB5')); // id, title, display cb, page, section
        add_settings_field('cpa_match_row_live_color', 'Live Color', array($this, 'bzcolorcb'), __FILE__, 'cpa_section_match_row', array('o_name' => 'match_row_live_color', 'def_color' => '#FF0000')); // id, title, display cb, page, section
        add_settings_field('cpa_match_row_redcard_color', 'Red Card(s) Color', array($this, 'bzcolorcb'), __FILE__, 'cpa_section_match_row', array('o_name' => 'match_row_redcard_color', 'def_color' => '#FF0000')); // id, title, display cb, page, section
        add_settings_field('cpa_match_row_redcard_hcolor', 'Red Card Highlight Color', array($this, 'bzcolorcb'), __FILE__, 'cpa_section_match_row', array('o_name' => 'match_row_redcard_hcolor', 'def_color' => '#FFFFB5')); // id, title, display cb, page, section

        add_settings_section('cpa_section_incidents', 'Incidents (goal scorers,cards etc.)', array($this, 'display_section'), __FILE__); // id, title, display cb, page
        add_settings_field('cpa_inc_bg_color', 'Background Color', array($this, 'bzcolorcb'), __FILE__, 'cpa_section_incidents', array('o_name' => 'inc_bg_color', 'def_color' => '#eef4e8')); // id, title, display cb, page, section
        add_settings_field('cpa_inc_font_color', 'Font Color', array($this, 'bzcolorcb'), __FILE__, 'cpa_section_incidents', array('o_name' => 'inc_font_color', 'def_color' => '#000000')); // id, title, display cb, page, section

        add_settings_section('cpa_section_date_row', 'Single Date Row', array($this, 'display_section'), __FILE__); // id, title, display cb, page
        add_settings_field('cpa_date_bg_color', 'Background Color', array($this, 'bzcolorcb'), __FILE__, 'cpa_section_date_row', array('o_name' => 'date_bg_color', 'def_color' => '#ffffff')); // id, title, display cb, page, section
        add_settings_field('cpa_date_font_color', 'Font Color', array($this, 'bzcolorcb'), __FILE__, 'cpa_section_date_row', array('o_name' => 'date_font_color', 'def_color' => '#000000')); // id, title, display cb, page, section

        // Register Settings
        register_setting(__FILE__, 'cpa_settings_options', array($this, 'bz_validate_options')); // option group, option name, sanitize cb
    }

    /**
     * Function that will add javascript file for Color Piker.
     */
    public function enqueue_admin_js() {
        // Make sure to add the wp-color-picker dependecy to js file
        wp_enqueue_script('cpa_custom_js', plugins_url('jquery.custom.js', __FILE__), array('jquery', 'wp-color-picker'), '', true);

        // Css rules for Color Picker
        wp_enqueue_style('wp-color-picker');
    }

    /**
     * Function that will validate all fields.
     */
    public function bz_validate_options($fields) {
        $valid_fields = array();

        //language
        $bz_lang_is = trim($fields['bz_lang']);
        $valid_fields['bz_lang'] = strip_tags(stripslashes($bz_lang_is));

        //author link
        $bz_c_link = trim($fields['bz_c_link']);
        $valid_fields['bz_c_link'] = strip_tags(stripslashes($bz_c_link));

        $bz_lang_is_ok = array(
            'en' => 'ok',
            'de' => 'ok',
            'pt' => 'ok',
            'nl' => 'ok',
            'tr' => 'ok',
            'si' => 'ok',
            'rs' => 'ok',
            'it' => 'ok',
            'fr' => 'ok',
            'ro' => 'ok'
        );

        $bz_keywords = array(
            'en' => array(
                "football results",
                "football results",
                "football results",
                "livescore",
                "live score",
                "livescore",
                "soccer results",
                "mobile livescore",
                "yesterday live scores",
                "epl results",
                "live score",
                "livescore",
                "live score",
                "live scores",
                "live football scores",
                "football scores",
                "livescore football",
                "livescore mobile",
                "recent matches",
                "football",
                "livescore today",
                "livescores",
                "live scores football",
                "football results"
            ),
            'de' => array("Liveergebnisse",
                "live ticker",
                "live ticker",
                "liveticker",
                "liveticker",
                "livescore",
                "ergebnisse",
                "live fussball ergebnisse",
                "Fußball Liveergebnisse",
                "Fußball",
                "livescore Fußball",
                "fussball ergebnisse gestern",
                "livescore"
            ),
            'pt' => array(
                "futebol ao vivo",
                "livescore",
                "futebol live",
                "futebol",
                "livescore futebol",
                "resultados",
                "resultados",
                "resultados live"
            ),
            'nl' => array(
                "voetbal live",
                "voetbal",
                "livescore",
                "livescore voetbal",
                "uitslagen voetbal live",
                "live voetbal"
            ),
            'tr' => array(
                'canlı skor',
                'canlı skor',
                'canlı skor',
                'maç sonuçları',
                'maç sonuçları',
                'maç sonuçları',
                'iddaa sonuçları',
                'iddaa',
                'iddaa',
                'iddaa',
                'iddaa',
                'canlı maç sonuçları'
            ),
            'si' => array(
                "nogomet",
                "rezultati",
                "rezultati v živo",
                "nogomet v živo",
                "livescore nogomet",
                "livescore"
            ),
            'rs' => array(
                "fudbal",
                "rezultati",
                "rezultati uživo",
                "fudbal uživo",
                "livescore fudbal",
                "livescore"
            ),
            'it' => array(
                "calcio",
                "punteggio in tempo reale",
                "risultati calcio di ieri",
                "calcio di ieri",
                "risultati partite ieri",
                "risultati calcio",
                "risultati calcio live score",
                "livescore ieri",
                "risultato in tempo reale",
                "risultati dal vivo",
                "risultati",
                "livescore calcio",
                "livescore"
            ),
            'fr' => array(
                "foot",
                "en direct",
                "livescore demain",
                "en direct foot",
                "score en direct",
                "livescore foot",
                "livescore",
                "résultats de football",
                "résultat hier",
                "livescore hier",
                "resultat foot"
            ),
            'ro' => array(
                "fotbal",
                "in direct",
                "scor in direct",
                "scoruri live",
                "scor live",
                "scoruri in direct",
                "rezultate live",
                "livescore fotbal",
                "livescore",
                "rezultate"
            )
        );

        if ('ok' != $bz_lang_is_ok[$bz_lang_is]) {
            $bz_lang_is = 'en';
        }
        $bz_arr_lang = $bz_keywords[$bz_lang_is];
        $bz_k_is = array_rand($bz_arr_lang);
        $bz_your_word_is = $bz_arr_lang[$bz_k_is];
        $bz_your_word_is = trim($bz_your_word_is);
        $valid_fields['bz_your_word'] = strip_tags(stripslashes($bz_your_word_is));

        $bz_color_fields = array(
            'main_font_color',
            'comp_row_bg',
            'comp_font_color',
            'match_row_even_bg',
            'match_row_odd_bg',
            'match_row_hover_bg',
            'match_row_scored_bg',
            'match_row_scored_fclr',
            'match_row_score_high',
            'match_row_live_color',
            'match_row_redcard_color',
            'match_row_redcard_hcolor',
            'inc_bg_color',
            'inc_font_color',
            'date_bg_color',
            'date_font_color'
        );

        foreach($bz_color_fields as $bz_color_f) {
            // Validate Comp Background Color
            $bz_clr_is = trim($fields[$bz_color_f]);
            $bz_clr_is = strip_tags(stripslashes($bz_clr_is));
            if (FALSE === $this->check_color($bz_clr_is)) {
                // Set the error message
                add_settings_error('cpa_settings_options', 'cpa_bg_error', 'Insert a valid color for Background', 'error'); // $setting, $code, $message, $type

                // Get the previous valid value
                $valid_fields[$bz_color_f] = isset($this->options[$bz_color_f]) ? $this->options[$bz_color_f] : '';
            } else {
                $valid_fields[$bz_color_f] = $bz_clr_is;
            }

        }

        return apply_filters('bz_validate_options', $valid_fields, $fields);
    }

    /**
     * Function that will check if value is a valid HEX color.
     */
    public function check_color($value) {
        if (preg_match('/^#[a-f0-9]{6}$/i', $value)) { // if user insert a HEX color with #
            return true;
        }

        return false;
    }

    /**
     * Callback function for settings section
     */
    public function display_section() { /* Leave blank */ }

    public function bz_your_word_cb() {
        echo '<input type="hidden" name="cpa_settings_options[bz_your_word]" value="" />';
    }

    public function bz_c_link_settings_field() {
        $val = isset($this->options['bz_c_link']) ? $this->options['bz_c_link'] : 'off';

        if (!$val) {
            $val = 'off';
        }

        $selected_one = array('on' => '', 'off' => '');

        $selected_one[$val] = 'selected="selected"';
        echo '<select name="cpa_settings_options[bz_c_link]">
                <option value="off" '. esc_html($selected_one['off']) .'>Off</option>
                <option value="on" '. esc_html($selected_one['on']) .'>On</option>
            </select>
        <small>Set it "On" to use your color settings, else you may keep it "Off" for default colors. Need detailed information? Please have a look "Notes" at the bottom of page.</small>';
    }

    public function bz_lang_settings_field() {
        $bz_lang_is_ok = array(
            'en' => 'ok',
            'de' => 'ok',
            'pt' => 'ok',
            'nl' => 'ok',
            'tr' => 'ok',
            'si' => 'ok',
            'rs' => 'ok',
            'it' => 'ok',
            'fr' => 'ok',
            'ro' => 'ok'
        );
        $bz_locale = substr(get_locale(),0,2);
        $val = isset($this->options['bz_lang']) ? $this->options['bz_lang'] : 'en';
        $selected_one = array(
            'en' => '',
            'de' => '',
            'nl' => '',
            'pt' => '',
            'tr' => '',
            'si' => '',
            'rs' => '',
            'it' => '',
            'fr' => '',
            'ro' => ''
        );
        if ('' == $val) {
            if ('ok' != $bz_lang_is_ok[$bz_locale]) {
                $val = 'en';
            } else {
                $val = $bz_locale;
            }
        }

        $selected_one[$val] ='selected="selected"';

        echo '<select name="cpa_settings_options[bz_lang]">
            <option value="en" '. esc_html($selected_one['en']) .'>English</option>
            <option value="de" '. esc_html($selected_one['de']) .'>Deutsch</option>
            <option value="nl" '. esc_html($selected_one['nl']) .'>Nederlands</option>
            <option value="pt" '. esc_html($selected_one['pt']) .'>Português</option>
            <option value="tr" '. esc_html($selected_one['tr']) .'>Türkçe</option>
            <option value="si" '. esc_html($selected_one['si']) .'>Slovenščina</option>
            <option value="rs" '. esc_html($selected_one['rs']) .'>Srpski</option>
            <option value="it" '. esc_html($selected_one['it']) .'>İtaliano</option>
            <option value="fr" '. esc_html($selected_one['fr']) .'>Français</option>
            <option value="ro" '. esc_html($selected_one['ro']) .'>Română</option>
        </select>';
    }

    public function bzcolorcb(array $args) {
        $val = isset($this->options[$args['o_name']]) ? $this->options[$args['o_name']] : $args['def_color'];
        if (!$val) {
        $val = $args['def_color'];
        }
        echo '<input type="text" name="cpa_settings_options['. esc_attr($args['o_name']) .']" value="' . esc_attr($val) . '" class="cpa-color-picker" >  ';
    }


}

CPA_Theme_Options::get_instance();

function bz_code($atts) {
    $opts = get_option('cpa_settings_options');

    $bz_lang_is_ok = array(
        'en' => 'ok',
        'de' => 'ok',
        'pt' => 'ok',
        'nl' => 'ok',
        'tr' => 'ok',
        'si' => 'ok',
        'rs' => 'ok',
        'it' => 'ok',
        'fr' => 'ok',
        'ro' => 'ok'
    );

    $atts = shortcode_atts(
        array(
            'font-size' => '11px',
            'font-family' => '',
            'data' => 'today',
            'data-2' => '',
            'country-is' => '',
            'league-is' => '',
            'sport' => 'football(soccer)'
        ),
        $atts,
        'bzscore'
    );
    $bz_your_code='
            var fm_inf_1 = "'. esc_attr($atts['font-family']) .'";
            var fs_inf_1 = "'. esc_attr($atts['font-size']) .'";'
    ;

    $bz_color_fields = array(
        'main_font_color' => 'bz_main_color',
        'comp_row_bg' => 'tr_leagueHeader_bg',
        'comp_font_color' => 'tr_leagueHeader_color',
        'match_row_even_bg' => 'tr_even',
        'match_row_odd_bg' => 'tr_odd',
        'match_row_hover_bg' => 'tr_match_hover',
        'match_row_scored_bg' => 'match_goal_bg',
        'match_row_scored_fclr' => 'match_goal_color',
        'match_row_score_high' => 'match_goal_high_bg',
        'match_row_live_color' => 'clr_inf_1',
        'match_row_redcard_color' => 'match_redcard_color',
        'match_row_redcard_hcolor' => 'match_redcard_bg',
        'inc_bg_color' => 'match_events_bg',
        'inc_font_color' => 'match_events_color',
        'date_bg_color' => 'tr_dateHeader_bg',
        'date_font_color' => 'tr_dateHeader_color'
    );
    foreach ($bz_color_fields as $bz_key => $bz_val) {
        $bz_tmp_val = isset($opts[$bz_key]) ? $opts[$bz_key] : 'null';

        if ('' != $bz_tmp_val) {
            $bz_your_code .= '
            var '. esc_attr($bz_val) .' = "'. esc_attr($bz_tmp_val) .'";';
        }
    }
    if ('' != $bz_your_code) {
        // We can't use esc function here because I need not escaped quotes for valid JS code
        $bz_your_code='<script id="skin">'. $bz_your_code .'
        </script>';
    }
    $bz_lang_is_x = isset($opts['bz_lang']) ? $opts['bz_lang'] : '';
    // check if lang supported in case not do it en
    if (!isset($bz_lang_is_ok[$bz_lang_is_x]) || 'ok' != $bz_lang_is_ok[$bz_lang_is_x]) {
        $bz_locale = substr(get_locale(),0,2);
        $bz_lang_is_x = ('ok' == $bz_lang_is_ok[$bz_locale]) ? $bz_locale : 'en';
    }

    $bz_your_word_is = isset($opts['bz_your_word']) ? $opts['bz_your_word'] : '';

    if ('' == $bz_your_word_is) {
        $bz_your_word_is = 'livescore';
    }

    $ek_lang = '';
    $bz_domain = 'https://www.livescore.bz';

    if ('en' != $bz_lang_is_x) {
        $ek_lang = '/'.$bz_lang_is_x;
        $bz_domain .= $ek_lang;
    }
    if ('tr' == $bz_lang_is_x) {
        $bz_domain="https://www.macsonuclari.mobi";
    }

    if ('league' == $atts['data-2']) {
        $bz_your_word_is = $atts['country-is'] . ' ~ ' . $atts['league-is'] .' livescore';
    }
    if ('england' == $atts['country-is'] && 'premier league' == $atts['league-is']) {
        $bz_your_word_is = 'epl livescore';
    }
    $bz_your_code .= '
        <script
            type="text/javascript"
            src="https://www.livescore.bz/api.livescore.0.1.js?v1.3"
            api="livescore"
            async
        ></script>
        <a
            href="'.esc_attr($bz_domain).'"
            sport="'. esc_attr($atts['sport']) .'"
            data-1="'. esc_attr($atts['data']) .'"
            data-2="'. esc_attr($atts['data-2']) .'"
            lang="'. esc_attr($bz_lang_is_x) .'"
        >
            '. esc_attr($bz_your_word_is) .'
        </a>
    ';
    // check author credit link is on or not. if not set it without link via iframe
    $bz_c_link_is = isset($opts['bz_c_link']) ? $opts['bz_c_link'] : '';
    if ("on" != $bz_c_link_is) {
        $bz_your_code='
            <iframe
                src="https://www.livescore.bz/webmasters.asp'
                    .'?lang='. esc_attr($bz_lang_is_x)
                    .'&sport='. esc_attr($atts['sport'])
                    .'&data-1='. esc_attr($atts['data'])
                    .'&data-2='. esc_attr($atts['data-2'])
                    .'&word='. esc_attr($bz_your_word_is)
                .'"
                marginheight="0"
                marginwidth="0"
                scrolling="auto"
                height="5000"
                width="100"
                frameborder="0"
                id="bzscoreframe"
                style="width:100%; height:5000px"
            ></iframe>
        ';
    }
    return $bz_your_code;
}

add_shortcode('bzscore', 'bz_code');

?>
