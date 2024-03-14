<?php defined('ABSPATH') || die('Cheatin\' uh?'); ?>
<?php

/**
 * Handles the parameters and url
 *
 * @author StarBox
 */
class ABH_Classes_Tools extends ABH_Classes_FrontController {

    /** @var array Saved options in database */
    public static $options = array();

    /** @var integer Count the errors in site */
    static $errors_count = 0;

    /** @var array */
    private static $debug;

    function __construct() {
        parent::__construct();

        self::$options = self::getOptions();
    }

    /**
     * Check if admin ajax is called
     *
     * @return boolean
     */
    public static function isAjax() {
        if (function_exists('wp_doing_ajax')) {
            return wp_doing_ajax();
        }

        return defined('DOING_AJAX') && DOING_AJAX;
    }

    public static function isApi() {
        $bIsRest = false;
        if (function_exists('rest_url') && isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI'])) {
            $sRestUrlBase = get_rest_url(get_current_blog_id(), '/');
            $sRestPath = trim(parse_url($sRestUrlBase, PHP_URL_PATH), '/');
            $sRequestPath = trim($_SERVER['REQUEST_URI'], '/');
            $bIsRest = (strpos($sRequestPath, $sRestPath) === 0);
        }
        return $bIsRest;
    }

    public static function getUserID() {
        global $current_user;
        return $current_user->ID;
    }

    /**
     * This hook will save the current version in database
     *
     * @return void
     */
    function hookInit() {

        //TinyMCE editor required
        //set_user_setting('editor', 'tinymce');

        $this->loadMultilanguage();

        //add setting link in plugin
        add_filter('plugin_action_links', array($this, 'hookActionlink'), 5, 2);
    }

    /**
     * Hook the frontent event to load the translations
     */
    function hookFrontinit() {
        $this->loadMultilanguage();
    }

    /**
     * Add a link to settings in the plugin list
     *
     * @param array $links
     * @param string $file
     * @return array
     */
    public function hookActionlink($links, $file) {

        if ($file == strtolower(_ABH_PLUGIN_NAME_) . '/' . strtolower(_ABH_PLUGIN_NAME_) . '.php') {
            $link = '<a href="' . admin_url('admin.php?page=abh_settings') . '">' . __('Settings', _ABH_PLUGIN_NAME_) . '</a>';
            array_unshift($links, $link);
        }
        return $links;
    }

    /**
     * Load the Options from user option table in DB
     *
     * @return array
     */
    public static function getOptions() {
        $default = array(
            'abh_version' => ABH_VERSION,
            'abh_use' => 1,
            'abh_subscribe' => 0,
            'abh_inposts' => 1,
            'abh_strictposts' => 0,
            'abh_inpages' => 0,
            'abh_ineachpost' => 0,
            'abh_showopengraph' => 1,
            'abh_shortcode' => 1,
            'abh_powered_by' => 1,
            // --
            'abh_position' => 'down',
            'anh_crt_posts' => 3,
            'abh_author' => array(),
            'abh_theme' => 'business',
            'abh_achposttheme' => 'drop-down',
            'abh_titlefontsize' => 'default',
            'abh_descfontsize' => 'default',
        );
        $options = json_decode(get_option(ABH_OPTION), true);

        if (is_array($options)) {
            $options = @array_merge($default, $options);
        } else {
            $options = $default;
        }

        $options['abh_themes'] = array('business', 'fancy', 'minimal', 'drop-down', 'topstar', 'topstar-round');
        $options['abh_achpostthemes'] = array('drop-down', 'topstar', 'topstar-round');
        $options['abh_titlefontsizes'] = array('default', '10px', '12px', '14px', '16px', '18px', '20px', '24px', '26px', '30px');
        $options['abh_descfontsizes'] = array('default', '10px', '12px', '14px', '16px', '18px', '20px', '24px', '26px', '30px');

        return $options;
    }

    public static function setOption($value, $new) {
        self::$options[$value] = $new;
        return self::$options[$value];
    }

    public static function getOption($value) {
        if (isset(self::$options[$value]))
            return self::$options[$value];
        else
            return false;
    }

    /**
     * Save the Options in user option table in DB
     *
     * @return void
     */
    public static function saveOptions($key, $value) {
        self::$options[$key] = $value;
        update_option(ABH_OPTION, json_encode(self::$options));
    }

    /**
     * Set the header type
     * @param string $type
     */
    public static function setHeader($type) {
        if (ABH_Classes_Tools::getValue('abh_debug') == 'on')
            return;

        switch ($type) {
            case 'json':
                header('Content-Type: application/json');
        }
    }

    /**
     * Clear the field string
     *
     * @param  $value
     * @return mixed|null|string|string[]
     */
    public static function sanitizeField($value)
    {

        if(is_array($value)){
            return array_map(array('ABH_Classes_Tools', 'sanitizeField'), $value);
        }

        if (is_string($value) && $value <> '') {

            $search = array(
                "'<!--(.*?)-->'is",
                "'<script[^>]*?>.*?<\/script>'si", // strip out javascript
                "'<style[^>]*?>.*?<\/style>'si", // strip out styles
                "'<form.*?<\/form>'si",
                "'<iframe.*?<\/iframe>'si",
                "'&lt;!--(.*?)--&gt;'is",
                "'&lt;script&gt;.*?&lt;\/script&gt;'si", // strip out javascript
                "'&lt;style&gt;.*?&lt;\/style&gt;'si", // strip out styles
            );
            $value = preg_replace($search, "", $value);

            $search = array(
                "/&nbsp;/si",
                "/\s{2,}/",
            );
            $value = preg_replace($search, " ", $value);

            //more sanitization
            $value = wp_strip_all_tags($value);
            $value = ent2ncr($value);
            $value = trim($value);

            $value = ABH_Classes_Tools::i18n($value);

        }

        return $value;
    }


    /**
     * Get a value from $_POST / $_GET
     * if unavailable, take a default value
     *
     * @param string $key Value key
     * @param mixed $defaultValue (optional)
     * @return mixed Value
     */
    public static function getValue($key = null, $defaultValue = false) {
        if (!isset($key) || (isset($key) && $key == '')) {
            return false;
        }

        $ret = (isset($_POST[$key]) ? $_POST[$key] : $defaultValue);
        $ret = ABH_Classes_Tools::sanitizeField($ret);

        return wp_unslash($ret);
    }

    /**
     * Check if the parameter is set
     *
     * @param string $key
     * @return boolean
     */
    public static function getIsset($key) {
        if (!isset($key) OR empty($key) OR !is_string($key))
            return false;
        return isset($_POST[$key]) ? true : (isset($_GET[$key]) ? true : false);
    }

    /**
     * Show the notices to WP
     *
     * @return void
     */
    public static function showNotices($message, $type = 'abh_notices') {
        if (file_exists(_ABH_THEME_DIR_ . 'Notices.php')) {
            ob_start();
            include(_ABH_THEME_DIR_ . 'Notices.php');
            $message = ob_get_contents();
            ob_end_clean();
        }

        return $message;
    }

    /**
     * Load the multilanguage support from .mo
     */
    private function loadMultilanguage() {
        if (!defined('WP_PLUGIN_DIR')) {
            load_plugin_textdomain(_ABH_PLUGIN_NAME_, _ABH_PLUGIN_NAME_ . '/languages/');
        } else {
            load_plugin_textdomain(_ABH_PLUGIN_NAME_, null, _ABH_PLUGIN_NAME_ . '/languages/');
        }
    }

    /**
     * Connect remote with CURL if exists
     */
    public static function abh_remote_get($url, $param = array()) {
        $timeout = (isset($param['timeout']) ? isset($param['timeout']) : 30);
        return self::abh_wpcall($url, array('timeout' => $timeout));
    }

    /**
     * Use the WP remote call
     * @param string $url
     * @param array $param
     * @return string
     */
    private static function abh_wpcall($url, $param = array()) {
        $response = wp_remote_get($url, $param);
        $response = self::cleanResponce(wp_remote_retrieve_body($response)); //clear and get the body
        return $response;
    }

    /**
     * Get the Json from responce if any
     * @param string $response
     * @return string
     */
    private static function cleanResponce($response) {

        if (function_exists('substr_count'))
            if (substr_count($response, '(') > 1)
                return $response;

        if (strpos($response, '(') !== false && strpos($response, ')') !== false)
            $response = substr($response, (strpos($response, '(') + 1), (strpos($response, ')') - 1));

        return $response;
    }

    /**
     * Check for SEO blog bad settings
     */
    public static function checkErrorSettings($count_only = false) {


        if (function_exists('is_network_admin') && is_network_admin())
            return;

        if (false) {
            if ($count_only)
                self::$errors_count++;
            else
                ABH_Classes_Error::setError(__('Notice', _ABH_PLUGIN_NAME_) . " <br /> ", 'settings');
        }
    }

    /**
     * Support for i18n with wpml, polyglot or qtrans
     *
     * @param string $in
     * @return string $in localized
     */
    public static function i18n($in) {
        if (function_exists('langswitch_filter_langs_with_message')) {
            $in = langswitch_filter_langs_with_message($in);
        }
        if (function_exists('polyglot_filter')) {
            $in = polyglot_filter($in);
        }
        if (function_exists('qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage')) {
            $in = qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage($in);
        }
        $in = apply_filters('localization', $in);
        return $in;
    }

    /**
     * Convert integer on the locale format.
     *
     * @param int $number The number to convert based on locale.
     * @param int $decimals Precision of the number of decimal places.
     * @return string Converted number in string format.
     */
    public static function i18n_number_format($number, $decimals = 0) {
        global $wp_locale;
        $formatted = number_format($number, absint($decimals), $wp_locale->number_format['decimal_point'], $wp_locale->number_format['thousands_sep']);
        return apply_filters('number_format_i18n', $formatted);
    }

    /**
     * Used only for debug purpose
     * Check if debug is called
     */
    private function checkDebug() {
        //if debug is called
        if (ABH_DEBUG && self::getIsset('abh_debug')) {
            if (self::getValue('abh_debug') === 'on') {
                if (function_exists('register_shutdown_function'))
                    register_shutdown_function(array($this, 'showDebug'));
            }
        }
    }

    /**
     * Used only for debug purpose
     * Store the debug for a later view
     */
    public static function dump() {
        if (ABH_DEBUG && self::getIsset('abh_debug')) {
            $output = '';
            $callee = array('file' => '', 'line' => '');
            if (function_exists('func_get_args')) {
                $arguments = func_get_args();
                $total_arguments = count($arguments);
            } else
                $arguments = array();


            if (function_exists('debug_backtrace'))
                list($callee) = debug_backtrace();

            $output .= '<fieldset style="background: #FFFFFF; border: 1px #CCCCCC solid; padding: 5px; font-size: 9pt; margin: 0;">';
            $output .= '<legend style="background: #EEEEEE; padding: 2px; font-size: 8pt;">' . $callee['file'] . ' @ line: ' . $callee['line']
                . '</legend><pre style="margin: 0; font-size: 8pt; text-align: left;">';

            $i = 0;
            foreach ($arguments as $argument) {
                if (count($arguments) > 1)
                    $output .= "\n" . '<strong>#' . (++$i) . ' of ' . $total_arguments . '</strong>: ';

                // if argument is boolean, false value does not display, so ...
                if (is_bool($argument))
                    $argument = ($argument) ? 'TRUE' : 'FALSE';
                else
                    if (is_object($argument) && function_exists('array_reverse') && function_exists('class_parents'))
                        $output .= implode("\n" . '|' . "\n", array_reverse(class_parents($argument))) . "\n" . '|' . "\n";

                $output .= htmlspecialchars(print_r($argument, TRUE))
                    . ((is_object($argument) && function_exists('spl_object_hash')) ? spl_object_hash($argument) : '');
            }
            $output .= "</pre>";
            $output .= "</fieldset>";

            self::$debug[] = $output;
        }
    }

    /**
     * Used only for debug purpose
     * Show the debug dump
     */
    public static function showDebug() {
        echo "Debug result: <br />" . @implode('<br />', self::$debug);
    }

    public static function emptyCache() {
        if (function_exists('w3tc_pgcache_flush')) {
            w3tc_pgcache_flush();
        }
        if (function_exists('wp_cache_clear_cache')) {
            wp_cache_clear_cache();
        }
        if (function_exists('wp_cache_post_edit') && isset($post_id)) {
            wp_cache_post_edit($post_id);
        }

        if (class_exists("WpFastestCache") && method_exists("WpFastestCache", "deleteCache")) {
            $wpfc = new WpFastestCache();
            $wpfc->deleteCache();
        }
    }

}