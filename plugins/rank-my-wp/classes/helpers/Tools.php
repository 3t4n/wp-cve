<?php

/**
 * Handles the parameters and url
 *
 */
class RKMW_Classes_Helpers_Tools {

    /** @var array Options, User Metas, Package and Plugin details */
    public static $options, $usermeta = array();

    public function __construct() {
        self::$options = $this->getOptions();

        RKMW_Classes_ObjController::getClass('RKMW_Classes_HookController')->setHooks($this);
    }

    public static function isAjax() {
        return (defined('DOING_AJAX') && DOING_AJAX);
    }

    /**
     * This hook will save the current version in database
     *
     * @return void
     */
    function hookInit() {
        //Load the languages pack
        $this->loadMultilanguage();
        //add extra links to the plugin in the Plugins list
        add_filter("plugin_row_meta", array($this, 'hookExtraLinks'), 10, 4);
        //add setting link in plugin
        add_filter('plugin_action_links', array($this, 'hookActionlink'), 5, 2);
    }

    /**
     * Add a link to settings in the plugin list
     *
     * @param array $links
     * @param string $file
     * @return array
     */
    public function hookActionlink($links, $file) {
        if ($file == RKMW_PLUGIN_NAME . '/index.php') {
            $link = '<a href="' . self::getAdminUrl('rkmw_dashboard') . '" style="font-weight:bold; color:#f48c0b">' . esc_html__("Do Research", RKMW_PLUGIN_NAME) . '</a>';
            array_unshift($links, $link);
        }

        return $links;
    }

    /**
     * Adds extra links to plugin  page
     *
     * @param $meta
     * @param $file
     * @param $data
     * @param $status
     * @return array
     */
    public function hookExtraLinks($meta, $file, $data = null, $status = null) {
        if ($file == RKMW_PLUGIN_NAME . '/index.php') {
            echo '<style>
                .ml-stars{display:inline-block;color:#ffb900;position:relative;top:3px}
                .ml-stars svg{fill:#ffb900}
                .ml-stars svg:hover{fill:#ffb900}
                .ml-stars svg:hover ~ svg{fill:none}
            </style>';

            $meta[] = "<a href='https://howto.rankmywp.com/' target='_blank'>" . esc_html__("Learn", RKMW_PLUGIN_NAME) . "</a>";
            $meta[] = "<a href='https://wordpress.org/support/plugin/rank-my-wp/reviews/#new-post' target='_blank' title='" . esc_html__("Leave a review", RKMW_PLUGIN_NAME) . "'><i class='ml-stars'><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg></i></a>";
        }
        return $meta;
    }

    /**
     * Load the Options from user option table in DB
     *
     * @param string $action
     * @return array|mixed|object
     */
    public static function getOptions($action = '') {
        $default = array(
            //Global settings
            'api' => '',
            'installed' => date('Y-m-d H:i:s'),
            //Alerts and Notices
            'alert' => array(),
            //Rankings
            'google_country' => false,

            //menu restrictions
            'menu_visited' => array(),
            'menu' => array(
                'dashboard' => 1,
                'account_info' => 1,
                'panel' => 1,
                'live_assistant' => 0,
                'research/research' => 1,
                'research/briefcase' => 1,
                'research/suggested' => 0,
                'research/history' => 1,
                'focuspages' => 0,
                'audits/audit' => 0,
                'rankings/rankings' => 1,
                'rankings/gscsync' => 1,
                'ads' => 1,
                'offers' => 1,
            ),

        );

        //Get the options from database
        $options = json_decode(get_option(RKMW_OPTION), true);

        //if there are saved options, merge them with the default ones
        if (is_array($options)) {

            //Get the API from Rank My Wp Cloud
            if (defined('SQ_OPTION') && $options['api'] == '') {
                $sqoptions = json_decode(get_option(RKMW_OPTION), true);
                $options['api'] = isset($sqoptions['sq_api']) ? $sqoptions['sq_api'] : '';
                $options['google_country'] = isset($sqoptions['sq_google_country']) ? $sqoptions['sq_google_country'] : '';
            }

            return array_replace_recursive((array)$default, (array)$options);
        }

        return $default;
    }

    /**
     * Get the option from database
     * @param $key
     * @return mixed
     */
    public static function getOption($key) {
        if (!isset(self::$options[$key])) {
            self::$options = self::getOptions();

            if (!isset(self::$options[$key])) {
                self::$options[$key] = false;
            }
        }

        return apply_filters('rkmw_option_' . $key, self::$options[$key]);
    }

    /**
     * Save the Options in user option table in DB
     *
     * @param null $key
     * @param string $value
     */
    public static function saveOptions($key = null, $value = '') {
        if (isset($key)) {
            self::$options[$key] = $value;
        }

        update_option(RKMW_OPTION, wp_json_encode(self::$options));
    }

    /**
     * Get user metas
     * @param null $user_id
     * @return array|mixed
     */
    public static function getUserMetas($user_id = null) {
        if (!isset($user_id)) {
            $user_id = get_current_user_id();
        }

        $default = array('rkmw_auto_sticky' => 0,);

        $usermeta = get_user_meta($user_id);
        $usermetatmp = array();
        if (is_array($usermeta)) foreach ($usermeta as $key => $values) {
            $usermetatmp[$key] = $values[0];
        }
        $usermeta = $usermetatmp;

        if (is_array($usermeta)) {
            $usermeta = array_merge((array)$default, (array)$usermeta);
        } else {
            $usermeta = $default;
        }
        self::$usermeta = $usermeta;
        return $usermeta;
    }

    /**
     * Get use meta
     * @param $value
     * @return bool
     */
    public static function getUserMeta($value) {
        if (!isset(self::$usermeta[$value])) {
            self::getUserMetas();
        }

        if (isset(self::$usermeta[$value])) {
            return apply_filters('rkmw_usermeta_' . $value, self::$usermeta[$value]);
        }

        return false;
    }

    /**
     * Save user meta
     * @param $key
     * @param $value
     * @param null $user_id
     */
    public static function saveUserMeta($key, $value, $user_id = null) {
        if (!isset($user_id)) {
            $user_id = get_current_user_id();
        }
        self::$usermeta[$key] = $value;
        update_user_meta($user_id, $key, $value);
    }

    /**
     * Delete User meta
     * @param $key
     * @param null $user_id
     */
    public static function deleteUserMeta($key, $user_id = null) {
        if (!isset($user_id)) {
            $user_id = get_current_user_id();
        }
        unset(self::$usermeta[$key]);
        delete_user_meta($user_id, $key);
    }

    /**
     * Check if the menu item exists as option in the plugin
     * @param $key
     * @return bool
     */
    public static function menuOptionExists($key) {

        return isset(self::$options['menu'][$key]);

    }

    /**
     * Get the option from database
     * @param $key
     * @return mixed
     */
    public static function getMenuVisible($key) {
        if (!isset(self::$options['menu'][$key])) {
            self::$options = self::getOptions();

            if (!isset(self::$options['menu'][$key])) {
                self::$options['menu'][$key] = false;
            }
        }

        return apply_filters('rkmw_menu_visible', self::$options['menu'][$key]);

    }

    /**
     * Set the header type
     * @param string $type
     */
    public static function setHeader($type) {
        if (self::getValue('rkmw_debug') == 'on') {
            // header("Content-type: text/html");
            return;
        }

        switch ($type) {
            case 'json':
                header('Content-Type: application/json');
                break;
            case 'ico':
                header('Content-Type: image/x-icon');
                break;
            case 'png':
                header('Content-Type: image/png');
                break;
            case'text':
                header("Content-type: text/plain");
                break;
            case'html':
                header("Content-type: text/html");
                break;
        }
    }

    /**
     * Set the Nonce action
     * @param $action
     * @param string $name
     * @param bool $referer
     * @param bool $echo
     * @return string
     */
    public static function setNonce($action, $name = '_wpnonce', $referer = true, $echo = true) {
        $name = esc_attr($name);
        $nonce_field = '<input type="hidden" name="' . $name . '" value="' . wp_create_nonce($action) . '" />';

        if ($referer) {
            $nonce_field .= wp_referer_field(false);
        }

        if ($echo) {
            echo (string)$nonce_field;
        }

        return $nonce_field;
    }

    /**
     * Get a value from $_POST / $_GET
     * if unavailable, take a default value
     *
     * @param string $key Value key
     * @param mixed $defaultValue (optional)
     * @param bool $htmlcode
     * @param bool $keep_newlines
     * @return mixed Value
     */
    public static function getValue($key, $defaultValue = false, $keep_newlines = false) {
        if (!isset($key) || (isset($key) && $key == '')) {
            return $defaultValue;
        }

        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $ret = (isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : ''));
        } else {
            $ret = (isset($_GET[$key]) ? $_GET[$key] : '');
        }

        if (is_array($ret)) {
            if (!empty($ret)) {
                foreach ($ret as &$row) {
                    if (!is_array($row)) {
                        $row = sanitize_text_field($row);
                    }
                }
            }
        } elseif (is_string($ret) && $ret <> '') {
            if ($keep_newlines && function_exists('sanitize_textarea_field')) {
                $ret = sanitize_textarea_field($ret);
            } else {
                $ret = sanitize_text_field($ret);
            }
        }

        if (!$ret) {
            return sanitize_text_field($defaultValue);
        } else {
            return wp_unslash($ret);
        }

    }

    /**
     * Check if the parameter is set
     *
     * @param string $key
     * @return boolean
     */
    public static function getIsset($key) {
        return (isset($_GET[$key]) || isset($_POST[$key]));
    }


    /**
     * Find the string in the content
     *
     * @param string $content
     * @param string $string
     * @param bool $normalize
     * @return bool|false|int
     */
    public static function findStr($content, $string, $normalize = false) {
        if ($normalize) {
            //Check if the search requires char normalization
            $content = RKMW_Classes_Helpers_Sanitize::normalizeChars($content);
            $string = RKMW_Classes_Helpers_Sanitize::normalizeChars($string);
        } else {
            //decode the content to match quotes and special chars
            $content = html_entity_decode($content, ENT_QUOTES);
            $string = html_entity_decode($string, ENT_QUOTES);
        }

        if (function_exists('mb_stripos')) {
            return mb_stripos($content, $string);
        } else {
            RKMW_Classes_Error::setMessage(esc_html__("For better text comparison you need to install PHP mbstring extension.", RKMW_PLUGIN_NAME));

            return stripos($content, $string);
        }
    }

    /**
     * Load the multilanguage support from .mo
     */
    private function loadMultilanguage() {
        load_plugin_textdomain(RKMW_PLUGIN_NAME, false, RKMW_PLUGIN_NAME . '/languages/');
    }


    /**
     * Hook the activate process
     */
    public function rkmw_activate() {
        RKMW_Classes_ObjController::getClass('RKMW_Models_RoleManager')->addRKMWRoles();
    }

    /**
     * Hook the deactivate process
     */
    public function rkmw_deactivate() {
        RKMW_Classes_ObjController::getClass('RKMW_Models_RoleManager')->removeRKMWCaps();
        RKMW_Classes_ObjController::getClass('RKMW_Models_RoleManager')->removeRKMWRoles();
    }

    /**
     * Triggered when a plugin update is made
     */
    public static function rkmw_upgrade($upgrader_object, $options) {

    }

    /**
     * Empty the cache from other plugins
     * @param null $post_id
     */
    public static function emptyCache($post_id = null) {
        if (function_exists('w3tc_pgcache_flush')) {
            w3tc_pgcache_flush();
        }

        if (function_exists('w3tc_minify_flush')) {
            w3tc_minify_flush();
        }
        if (function_exists('w3tc_dbcache_flush')) {
            w3tc_dbcache_flush();
        }
        if (function_exists('w3tc_objectcache_flush')) {
            w3tc_objectcache_flush();
        }

        if (function_exists('wp_cache_clear_cache')) {
            wp_cache_clear_cache();
        }

        if (function_exists('rocket_clean_domain')) {
            // Remove all cache files
            rocket_clean_domain();
        }

        if (function_exists('opcache_reset')) {
            // Remove all opcache if enabled
            opcache_reset();
        }

        if (function_exists('apc_clear_cache')) {
            // Remove all apc if enabled
            apc_clear_cache();
        }

        if (class_exists('Cache_Enabler_Disk') && method_exists('Cache_Enabler_Disk', 'clear_cache')) {
            // clear disk cache
            Cache_Enabler_Disk::clear_cache();
        }

        //Clear the fastest cache
        global $wp_fastest_cache;
        if (isset($wp_fastest_cache) && method_exists($wp_fastest_cache, 'deleteCache')) {
            $wp_fastest_cache->deleteCache();
        }
    }


    /**
     * Check if a plugin is installed
     * @param $name
     * @return bool
     */
    public static function isPluginInstalled($name) {
        switch ($name) {
            case 'instapage':
                return defined('INSTAPAGE_PLUGIN_PATH');
            case 'quick-seo':
                return defined('QSS_VERSION') && defined('_QSS_ROOT_DIR_');
            case 'premium-seo-pack':
                return defined('PSP_VERSION') && defined('_PSP_ROOT_DIR_');
            default:
                $plugins = (array)get_option('active_plugins', array());

                if (is_multisite()) {
                    $plugins = array_merge(array_values($plugins), array_keys(get_site_option('active_sitewide_plugins')));
                }

                return in_array($name, $plugins, true);

        }
    }

    /**
     * Check if frontend and user is logged in
     * @return bool
     */
    public static function isFrontAdmin() {
        return (!is_admin() && is_user_logged_in());
    }

    /**
     * Check if user is in dashboard
     * @return bool
     */
    public static function isBackedAdmin() {
        return (is_admin() || is_network_admin());
    }


    /**
     * Get the admin url for the specific age
     *
     * @param string $page
     * @param string $tab
     * @param array $args
     * @return string
     */
    public static function getAdminUrl($page, $tab = null, $args = array()) {
        if (strpos($page, '.php')) {
            $url = admin_url($page);
        } else {
            if (strpos($page, '/') !== false) {
                list($page, $tab) = explode('/', $page);
            }

            $url = admin_url('admin.php?page=' . $page);
        }

        if (isset($tab) && $tab <> '') {
            $url .= '&tab=' . $tab;
        }

        if (!empty($args)) {
            if (strpos($url, '?') !== false) {
                $url .= '&';
            } else {
                $url .= '?';
            }
            $url .= join('&', $args);
        }

        return apply_filters('rkmw_menu_url', $url, $page, $tab, $args);
    }

}
