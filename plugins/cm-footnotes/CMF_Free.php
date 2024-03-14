<?php

class FootnoteException extends Exception {
    
}

class CMF_Free {

    protected static $filePath = '';
    protected static $cssPath = '';
    protected static $jsPath = '';
    protected static $imagesPath = '';
    protected static $customFootnotesPostMetaKey = '_cm_simple_footnotes';
    protected static $simpleFootnoteShortcode = 'cm_simple_footnote';
    protected static $simpleFootnoteDefinitionsRenderArray = array();
    public static $lastQueryDetails = array();
    public static $calledClassName;

    const DISPLAY_NOWHERE = 0;
    const DISPLAY_EVERYWHERE = 1;
    const DISPLAY_ONLY_ON_PAGES = 2;
    const DISPLAY_EXCEPT_ON_PAGES = 3;
    const PAGE_YEARLY_OFFER = 'https://www.cminds.com/wordpress-plugins-library/cm-wordpress-plugins-yearly-membership/';

    public static function init() {

        self::setupConstants();

        self::includeFiles();

        self::initFiles();

        if (empty(self::$calledClassName)) {
            self::$calledClassName = __CLASS__;
        }

        $file = basename(__FILE__);
        $folder = basename(dirname(__FILE__));
        $hook = "in_plugin_update_message-{$folder}/{$file}";
        add_action($hook, array(__CLASS__, 'cmf_warn_on_upgrade'));

        self::$filePath = plugin_dir_url(__FILE__);
        self::$cssPath = self::$filePath . 'assets/css/';
        self::$jsPath = self::$filePath . 'assets/js/';
        self::$imagesPath = self::$filePath . 'assets/images/';

        add_action('admin_menu', array(__CLASS__, 'cmf_admin_menu'));               // Add Plugin Admin Menu
        add_action('admin_init', array(__CLASS__, 'cm_options_upgrade'));
        add_action('wp_loaded', ['com\cminds\footnotes\settings\CMF_Settings', 'init']);

        add_action('admin_enqueue_scripts', array(__CLASS__, 'cmf_footnote_admin_settings_scripts'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'cmf_footnote_admin_edit_scripts'));

        add_action('wp_print_styles', array(__CLASS__, 'cmf_footnote_css'));
        add_action('admin_notices', array(__CLASS__, 'cmf_footnote_admin_notice_wp33'));
        add_action('admin_notices', array(__CLASS__, 'cmf_footnote_admin_notice_mbstring'));

        add_action('wp_enqueue_scripts', array(__CLASS__, 'cmf_front_scripts_settings'));

        add_action('add_meta_boxes', array(__CLASS__, 'cmf_RegisterBoxes'));
        add_action('save_post', array(__CLASS__, 'cmf_save_postdata'));
        add_action('update_post', array(__CLASS__, 'cmf_save_postdata'));

        // Add foot note simple definition box at the end of the content
        add_filter('the_content', array(__CLASS__, 'addSimpleFootNoteDefinitionBox'), get_option('cmf_footnoteSimpleFilterPriority', 9999999));

        // Implicit footnote parser
        add_shortcode(self::$simpleFootnoteShortcode, array(__CLASS__, 'cmf_handle_footnote_parse_implicit_style'));
    }

    /**
     * Include the files
     */
    public static function includeFiles() {
        include_once CMF_PLUGIN_DIR . "settings/CMF_Settings.php";
        include_once CMF_PLUGIN_DIR . "functions.php";
        include_once CMF_PLUGIN_DIR . "package/" . CMF_PACKAGE;
        include_once CMF_PLUGIN_DIR . "CMF_Pro.php";
    }

    /**
     * Init the files
     */
    public static function initFiles() {
        CMF_Pro::init();
    }

    /**
     *  Set options if plugin has new release version
     *
     */
    public static function cm_options_upgrade() {
        if (self::checkRecordedVersion()) {  // Check if new release updated - set new Options if added
        }
    }

    /**
     *  Check if plugin current version is similar to stored in db
     *
     */
    public static function checkRecordedVersion() {  //  Check new release version
        if (!function_exists('get_plugin_data')) {
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }
        $plugin_url = CMF_PLUGIN_FILE;

        $file_data = get_file_data($plugin_url, array('Version'), 'plugin');
        $current_plugin_version = $file_data[0];
        $recorded_plugin_version = \com\cminds\footnotes\settings\CMF_Settings::get('cmf_footnote_recorded_plugin_version');

        $current_plugin_version_int = intval(str_replace('.', '', $current_plugin_version));
        if (!empty($recorded_plugin_version)) {
            $recorded_plugin_version_int = intval(str_replace('.', '', $recorded_plugin_version));
        } else {
            $recorded_plugin_version_int = 0;
        }

        if ($current_plugin_version_int > $recorded_plugin_version_int) {
            add_option('cmf_footnote_recorded_plugin_version', $current_plugin_version);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Setup plugin constants
     *
     * @access private
     * @since 1.1
     * @return void
     */
    public static function setupConstants() {
        /**
         * Define Plugin Directory
         *
         * @since 1.0
         */
        if (!defined('CMF_PLUGIN_DIR')) {
            define('CMF_PLUGIN_DIR', plugin_dir_path(__FILE__));
        }

        /**
         * Define Plugin URL
         *
         * @since 1.0
         */
        if (!defined('CMF_PLUGIN_URL')) {
            define('CMF_PLUGIN_URL', plugin_dir_url(__FILE__));
        }

        /**
         * Define Plugin Slug name
         *
         * @since 1.0
         */
        if (!defined('CMF_SLUG_NAME')) {
            define('CMF_SLUG_NAME', 'cm-footnote-footnote');
        }

        /**
         * Define Plugin basename
         *
         * @since 1.0
         */
        if (!defined('CMF_PLUGIN')) {
            define('CMF_PLUGIN', plugin_basename(__FILE__));
        }

        if (!defined('CMF_MENU_OPTION')) {
            define('CMF_MENU_OPTION', 'cmf_settings');
        }

        define('CMF_ABOUT_OPTION', 'cmf_about');
        define('CMF_EXTENSIONS_OPTION', 'cmf_extensions');
        define('CMF_SETTINGS_OPTION', 'cmf_settings');

        do_action('cmf_setup_constants_after');
    }

    /**
     *  Function to show admin menu
     */
    public static function cmf_admin_menu() {
        global $submenu;
        $current_user = wp_get_current_user();
        $page_menu = add_menu_page('Footnote', CMF_NAME, 'edit_posts', CMF_MENU_OPTION, array(__CLASS__, 'outputOptions'), CMF_PLUGIN_URL . 'assets/css/images/cm-footnote-icon.png');
    }

    /**
     * Shows extensions page
     */
    public static function cmf_extensions() {
        ob_start();
        include_once 'views/backend/admin_extensions.php';
        $content = ob_get_contents();
        ob_end_clean();
        require 'views/backend/admin_template.php';
    }

    public static function cm_footnote_admin_styles() {
        wp_enqueue_style('jqueryUIStylesheet', self::$cssPath . 'jquery-ui-1.10.3.custom.css');
        wp_enqueue_style('footnote-settings', self::$cssPath . 'footnote-settings.css');
    }

    /**
     * Function enqueues the scripts and styles for the admin Settings view
     * @global type $parent_file
     * @return type
     */
    public static function cmf_footnote_admin_settings_scripts() {
        global $parent_file;

        if (CMF_MENU_OPTION !== $parent_file) {
            return;
        }

        wp_enqueue_style('jqueryUIStylesheet', self::$cssPath . 'jquery-ui-1.10.3.custom.css');
        wp_enqueue_style('footnote', self::$cssPath . 'footnote.css');
        wp_enqueue_script('footnote-admin-js', self::$jsPath . 'cm-footnote.js', array('jquery'));

        wp_enqueue_script('jquery-ui-core');
//        wp_enqueue_script('jquery-ui-tooltip');
        wp_enqueue_script('jquery-ui-tabs');

        $footnoteData['ajaxurl'] = admin_url('admin-ajax.php');
        $footnoteData['imagePath'] = self::$imagesPath;
        wp_localize_script('footnote-admin-js', 'cmf_data', $footnoteData);
    }

    /**
     * Function outputs the scripts and styles for the edit views
     * @global type $typenow
     * @return type
     */
    public static function cmf_footnote_admin_edit_scripts() {
        global $typenow;
        $gutenbergActive = FALSE;

        $current_screen = get_current_screen();
        if (method_exists($current_screen, 'is_block_editor') && $current_screen->is_block_editor()) {
            $gutenbergActive = TRUE;
        }
        $isCaseSensitive = \com\cminds\footnotes\settings\CMF_Settings::get('cmf_footnoteCaseSensitive', 0);
        wp_enqueue_style('footnote', self::$cssPath . 'footnote.css');
        wp_enqueue_script('footnote-admin-js', self::$jsPath . 'cm-footnote.js', array('jquery'));
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-tooltip');

        $footnoteData['imagePath'] = self::$imagesPath;
        wp_localize_script('footnote-admin-js', 'cmf_data', $footnoteData);
    }

    /**
     * Filters admin navigation menus to show horizontal link bar
     * @global string $submenu
     * @global type $plugin_page
     * @param type $views
     * @return string
     */
    public static function cmf_filter_admin_nav($views) {
        global $submenu, $plugin_page;
        $scheme = is_ssl() ? 'https://' : 'http://';
        $adminUrl = str_replace($scheme . $_SERVER['HTTP_HOST'], '', admin_url());
        $currentUri = str_replace($adminUrl, '', $_SERVER['REQUEST_URI']);
        $submenus = array();
        if (isset($submenu[CMF_MENU_OPTION])) {
            $thisMenu = $submenu[CMF_MENU_OPTION];

            $firstMenuItem = $thisMenu[0];
            unset($thisMenu[0]);

            $secondMenuItem = array('Trash', 'edit_posts', 'edit.php?post_status=trash&post_type=footnote', 'Trash');

            array_unshift($thisMenu, $firstMenuItem, $secondMenuItem);

            foreach ($thisMenu as $item) {
                $slug = $item[2];
                $isCurrent = ($slug == $plugin_page || strpos($item[2], '.php') === strpos($currentUri, '.php'));
                $isExternalPage = strpos($item[2], 'http') !== FALSE;
                $isNotSubPage = $isExternalPage || strpos($item[2], '.php') !== FALSE;
                $url = $isNotSubPage ? $slug : get_admin_url(null, 'admin.php?page=' . $slug);
                $target = $isExternalPage ? '_blank' : '';
                $submenus[$item[0]] = '<a href="' . $url . '" target="' . $target . '" class="' . ($isCurrent ? 'current' : '') . '">' . $item[0] . '</a>';
            }
        }
        return $submenus;
    }

    /**
     * Displays the horizontal navigation bar
     * @global string $submenu
     * @global type $plugin_page
     */
    public static function cmf_showNav() {
        global $submenu, $plugin_page;
        $submenus = array();
        $scheme = is_ssl() ? 'https://' : 'http://';
        $adminUrl = str_replace($scheme . $_SERVER['HTTP_HOST'], '', admin_url());
        $currentUri = str_replace($adminUrl, '', $_SERVER['REQUEST_URI']);

        if (isset($submenu[CMF_MENU_OPTION])) {
            $thisMenu = $submenu[CMF_MENU_OPTION];
            foreach ($thisMenu as $item) {
                $slug = $item[2];
                $isCurrent = ($slug == $plugin_page || strpos($item[2], '.php') === strpos($currentUri, '.php'));
                $isExternalPage = strpos($item[2], 'http') !== FALSE;
                $isNotSubPage = $isExternalPage || strpos($item[2], '.php') !== FALSE;
                $url = $isNotSubPage ? $slug : get_admin_url(null, 'admin.php?page=' . $slug);
                $submenus[] = array(
                    'link'    => $url,
                    'title'   => $item[0],
                    'current' => $isCurrent,
                    'target'  => $isExternalPage ? '_blank' : ''
                );
            }
            require('views/backend/admin_nav.php');
        }
    }

    /**
     * Add the dynamic CSS to reflect the styles set by the options
     * @return type
     */
    public static function cmf_footnote_dynamic_css() {
        ob_start();
        echo apply_filters('cmf_dynamic_css_before', '');
        ?>

        span.cmf_has_footnote a.cmf_footnote_link,
        .cmf_has_footnote_custom a.cmf_simple_footnote_link {
        <?php if (\com\cminds\footnotes\settings\CMF_Settings::get('cmf_footnoteFormat') === 'none') : ?>
            font-weight : 400 ;
        <?php elseif (\com\cminds\footnotes\settings\CMF_Settings::get('cmf_footnoteFormat') === 'bold') : ?>
            font-weight : 800 ;
        <?php endif; ?>    
        font-size: <?php echo \com\cminds\footnotes\settings\CMF_Settings::get('cmf_footnoteSymbolSize'); ?>;
        color: <?php echo \com\cminds\footnotes\settings\CMF_Settings::get('cmf_footnoteSymbolColor'); ?>;
        }
        .type3, .type4{
        background: <?php echo \com\cminds\footnotes\settings\CMF_Settings::get('cmf_footnoteSymbolColor'); ?>;
        }
        sup > a.type3:hover, sup > a.type4:hover{
        color: <?php echo \com\cminds\footnotes\settings\CMF_Settings::get('cmf_footnoteSymbolColor'); ?> !important;
        }
        .type4:after{
        border-top-color: <?php echo \com\cminds\footnotes\settings\CMF_Settings::get('cmf_footnoteSymbolColor'); ?>;
        }
        .cmf_footnotes_wrapper table.cmf_footnotes_table .cmf_footnote_row .cmf_footnote_link_anchor a,
        #cmfSimpleFootnoteDefinitionBox .cmfSimpleFootnoteDefinitionItem .cmfSimpleFootnoteDefinitionItemId {
        font-size: <?php echo \com\cminds\footnotes\settings\CMF_Settings::get('cmf_footnoteSymbolLinkAnchorSize'); ?>;
        color: <?php echo \com\cminds\footnotes\settings\CMF_Settings::get('cmf_footnoteSymbolLinkAnchorColor'); ?>;
        }

        <?php if (!\com\cminds\footnotes\settings\CMF_Settings::get('cmf_footnoteInOneLine')): ?>
            #cmfSimpleFootnoteDefinitionBox > div { display: inline-block; width: 33.3333%; vertical-align: top; margin-bottom: 10px; padding: 10px; }
            .cmfSimpleFootnoteDefinitionItem {width: 100%; text-align: <?php echo \com\cminds\footnotes\settings\CMF_Settings::get('cmf_footnoteDescriptionAlignment') ?>;}
        <?php else : ?>
            #cmfSimpleFootnoteDefinitionBox > div { display: block; width: 100%; vertical-align: top; margin-bottom: 10px; padding: 10px; }
        <?php endif; ?>

        <?php
        echo apply_filters('cmf_dynamic_css_after', '');
        $content = ob_get_clean();
        return trim($content);
    }

    /**
     * Outputs the frontend CSS
     */
    public static function cmf_footnote_css() {
        $fontName = \com\cminds\footnotes\settings\CMF_Settings::get('cmf_footnoteFontStyle', 'default');

        wp_enqueue_style('footnote', self::$cssPath . 'footnote.css');
        if (is_string($fontName) && $fontName !== 'default') {
            wp_enqueue_style('footnote-google-font', '//fonts.googleapis.com/css?family=' . $fontName);
        }
        // It's WP 3.3+ function
        if (function_exists('wp_add_inline_style')) {
            wp_add_inline_style('footnote', self::cmf_footnote_dynamic_css());
        }
    }

    /**
     * Outputs the frontend scripts and settings
     */
    public static function cmf_front_scripts_settings() {
        global $post;

        $postId = empty($post->ID) ? '' : $post->ID;

        do_action('cmf_front_scripts_styles');

        wp_enqueue_script('footnote-frontend', self::$jsPath . 'footnote.js', array('jquery'), false, true);
        wp_enqueue_style('jqueryUIStylesheet', self::$cssPath . 'jquery-ui-1.10.3.custom.css');

        $footnoteData = array();

        $footnoteData['ajaxurl'] = admin_url('admin-ajax.php');
        $footnoteData['post_id'] = $postId;

        wp_localize_script('footnote-frontend', 'cmf_data', apply_filters('cmf_footnote_script_data', $footnoteData));
    }

    /**
     * Adds a notice about wp version lower than required 3.3
     * @global type $wp_version
     */
    public static function cmf_footnote_admin_notice_wp33() {
        global $wp_version;

        if (version_compare($wp_version, '3.3', '<')) {
            $message = sprintf(CMF_Free::__('%s requires Wordpress version 3.3 or higher to work properly.'), CMF_NAME);
            cminds_show_message($message, true);
        }
    }

    /**
     * Adds a notice about mbstring not being installed
     * @global type $wp_version
     */
    public static function cmf_footnote_admin_notice_mbstring() {
        $mb_support = function_exists('mb_strtolower');

        if (!$mb_support) {
            $message = sprintf(CMF_Free::__('%s since version 2.6.0 requires "mbstring" PHP extension to work! '), CMF_NAME);
            $message .= '<a href="http://www.php.net/manual/en/mbstring.installation.php" target="_blank">(' . CMF_Free::__('Installation instructions.') . ')</a>';
            cminds_show_message($message, true);
        }
    }

    /**
     * Strips just one tag
     * @param type $str
     * @param type $tags
     * @param type $stripContent
     * @return type
     */
    public static function cmf_strip_only($str, $tags, $stripContent = false) {
        $content = '';
        if (!is_array($tags)) {
            $tags = (strpos($str, '>') !== false ? explode('>', str_replace('<', '', $tags)) : array($tags));
            if (end($tags) == '') {
                array_pop($tags);
            }
        }
        foreach ($tags as $tag) {
            if ($stripContent) {
                $content = '(.+</' . $tag . '[^>]*>|)';
            }
            $str = preg_replace('#</?' . $tag . '[^>]*>' . $content . '#is', '', $str);
        }
        return $str;
    }

    /**
     * @param $atts
     *   OUR MAIN FUNCTION FOR USE SIMPLE SHORTCODES
     * @return false|string
     */
    public static function cmf_handle_footnote_parse_implicit_style($atts) {
        global $post;
        $currentPostType = get_post_type($post);

        if (intval($post->ID) === intval(\com\cminds\footnotes\settings\CMF_Settings::get('page_on_front'))) {
            $currentPostType = 'frontpage';
        }
        $showOnPostTypes = \com\cminds\footnotes\settings\CMF_Settings::get('cmf_footnoteOnPosttypes');
        $disable_enabled_meta = get_post_meta($post->ID, '_footnote_disable_footnote_for_page', true);

        if (!is_array($showOnPostTypes)) {
            $showOnPostTypes = array();
        }
        if (( in_array($currentPostType, $showOnPostTypes) && $disable_enabled_meta)) {
            return false;
        }
        if ((!in_array($currentPostType, $showOnPostTypes) && !$disable_enabled_meta)) {
            return false;
        }

        $existingDefinitions = get_post_meta($post->ID, self::$customFootnotesPostMetaKey, true);

        $id = false;
        if (!empty($atts['id'])) {
            $id = $atts['id'];
        }
        if (!empty($atts['Id'])) {
            $id = $atts['Id'];
        }
        if (!empty($atts['ID'])) {
            $id = $atts['ID'];
        }
        if (!empty($atts['iD'])) {
            $id = $atts['iD'];
        }
        if ($id) {

            if (!empty($existingDefinitions['ids'])) {

                foreach ($existingDefinitions['ids'] as $key => $value) {
                    if ($value == $id) {
                        if (empty(self::$simpleFootnoteDefinitionsRenderArray[$id])) {
                            if (!empty($existingDefinitions['content'][$key])) {
                                do_action('cmf_display_footnote', $id, $existingDefinitions, $key);
                                self::$simpleFootnoteDefinitionsRenderArray[$id] = $existingDefinitions['content'][$key];
                            } else {
                                self::$simpleFootnoteDefinitionsRenderArray[$id] = '';
                            }
                        }

                        $tooltip_cnt_cstm = apply_filters('cmf_tooltip_content', '', $existingDefinitions, $key);
                        $extlink = apply_filters('cmf_extlink', '', $existingDefinitions, $key);
                        $extlinkLabel = apply_filters('cmf_extlink_label', '', $existingDefinitions, $key);
                        $extLinkTitle = apply_filters('cmf_extlink_title', '', $existingDefinitions, $key);

                        $footnoteLink = '#cmfSimpleFootnoteLink' . $id;
                        $footnoteBackLink = str_replace('#', '', $footnoteLink) . '-0';

                        $cmf_footnoteAestheticsType = \com\cminds\footnotes\settings\CMF_Settings::get('cmf_footnoteAestheticsType');

                        $cmf_footnoteFormat = \com\cminds\footnotes\settings\CMF_Settings::get('cmf_footnoteFormat');
                        $cmf_footnoteFormatStart = '';
                        $cmf_footnoteFormatEnd = '';
                        if ($cmf_footnoteFormat == 'bold') {
                            $cmf_footnoteFormatStart = '<b>';
                            $cmf_footnoteFormatEnd = '</b>';
                        } elseif ($cmf_footnoteFormat == 'italic') {
                            $cmf_footnoteFormatStart = '<i>';
                            $cmf_footnoteFormatEnd = '</i>';
                        }

                        return '<span id="' . $footnoteBackLink . '" class="cmf_has_footnote_custom"><sup><a href="' . $footnoteLink . '" class="et_smooth_scroll_disabled cmf_simple_footnote_link show-tooltip ' . $cmf_footnoteAestheticsType . '" title ="' . $tooltip_cnt_cstm . '" data-extlink="' . esc_attr($extLinkTitle) . '" data-extlink_label='.esc_attr($extlinkLabel).'>' . $cmf_footnoteFormatStart . $id . $cmf_footnoteFormatEnd . '</a></sup></span>';
                    }
                }
            }
        } else {
            return '';
        }
    }

    public static function addSimpleFootNoteDefinitionBox($content) {
		global $post;

        static $runOnce = [];

        if (isset($runOnce[$post->ID])) {
            return $content;
        }

        $disable_enabled_meta = get_post_meta($post->ID, '_footnote_disable_footnote_for_page', true);
        $showOnPostTypes = \com\cminds\footnotes\settings\CMF_Settings::get('cmf_footnoteOnPosttypes');
        if (!in_array(get_post_type(), $showOnPostTypes) && !$disable_enabled_meta) {
            return $content;
        }
        $outString = '';
        if (!empty(self::$simpleFootnoteDefinitionsRenderArray)) {
            ksort(self::$simpleFootnoteDefinitionsRenderArray);

            $outString .= '<div class="pg-ft-sep">';
            $outString .= apply_filters('cmf_bottom_custom_separator', '');
            $outString .= '</div>';

            $outString .= '<div id="cmfSimpleFootnoteDefinitionBox">';
            foreach (self::$simpleFootnoteDefinitionsRenderArray as $key => $definition) {

                $outString .= '<div class="cmfSimpleFootnoteDefinitionItem cmfSimpleFootnoteLink' . $key . '">';

                $outString .= apply_filters('cmf_definition_id', '<span class="cmfSimpleFootnoteDefinitionItemId" id="cmfSimpleFootnoteLink' . $key . '">' . $key . '. </span>', $key);
                $outString .= '<span class="cmfSimpleFootnoteDefinitionItemContent">' . do_shortcode($definition) . '</span>';
                $outString .= apply_filters('cmf_definition_after_content', '', $key);
                $outString .= '</div>';
            }

            $outString .= '</div>';
			$runOnce[$post->ID] = true;
			/*
			 * We need to clear this before rendering next post because otherwise the footnotes
			 * from the previous post will be displayed
			 */
			self::$simpleFootnoteDefinitionsRenderArray = [];
        }
        return $content . $outString;
    }

    /**
     * Displays the options screen
     */
    public static function outputOptions() {
        self::displayAdminPage('');
    }

    public static function displayAdminPage($content) {
        include 'views/backend/admin_template.php';
    }

    /**
     * Outputs the Affiliate Referral Snippet
     * @return type
     */
    public static function cmf_getReferralSnippet() {
        ob_start();
        ?>
        <span class="footnote_referral_link">
            <a target="_blank" href="https://www.cminds.com/wordpress-plugins-library/cm-footnotes-plugin-for-wordpress/?af=<?php echo \com\cminds\footnotes\settings\CMF_Settings::get('cmf_footnoteAffiliateCode') ?>">
                <img src="https://www.cminds.com/wp-content/uploads/download_footnote.png" width=122 height=22 alt="Download Footnotes Pro" title="Download Footnotes Pro" />
            </a>
        </span>
        <?php
        $referralSnippet = ob_get_clean();
        return $referralSnippet;
    }

    public static function cmf_warn_on_upgrade() {
        ?>
        <div style="margin-top: 1em"><span style="color: red; font-size: larger">STOP!</span> Do <em>not</em> click &quot;update automatically&quot; as you will be <em>downgraded</em> to the free version of Footnote. Instead, download the Pro update directly from <a href="http://www.cminds.com/downloads/cm-enhanced-footnote-footnote-premium-version/">http://www.cminds.com/downloads/cm-enhanced-footnote-footnote-premium-version/</a>.</div>
        <div style="font-size: smaller">Footnotes Pro does not use WordPress's standard update mechanism. We apologize for the inconvenience!</div>
        <?php
    }

    /**
     * Registers the metaboxes
     */
    public static function cmf_RegisterBoxes() {
        add_meta_box('footnote-disable-box', 'CM Footnote - Disables', array(__CLASS__, 'cmf_render_disable_for_page'), get_post_type(), 'side', 'high');
        add_meta_box('footnote-definition-box', 'CM Footnotes - Definitions', array(__CLASS__, 'cmf_render_definition_box'), get_post_type(), 'normal', 'high');

        do_action('cmf_register_boxes');
    }

    public static function cmf_render_meta_header($arr) {
        $content = '';
        foreach ($arr as $value) {
            $classname = $value['class'] ?? '';
            $label = $value['label'] ?? '';
            $content .= '<div class="cm-foot-meta-header cm-foot-meta-header__' . $classname . '">' . $label . '</div>';
        }
        return $content;
    }

    public static function cmf_render_meta_block($arr, $existingDefinitions, $i) {
        $content = '';
        foreach ($arr as $value) {
            $classname = $value['class'] ?? '';
            $content_prefiltered = (!empty($value['callback']) ? call_user_func_array($value['callback'], [$value, $existingDefinitions, $i]) : '');
            $content .= apply_filters('cmf_render_block_' . $classname, $content_prefiltered, $value, $existingDefinitions, $i);
        }
        return $content;
    }

    public static function cmf_render_meta_block_id($block_arr, $existingDefinitions, $i) {
        ob_start();
        $value = $existingDefinitions['ids'][$i] ?? $block_arr['default'];
        ?>
        <div class="cm_footnote_definitions_td_id">
            <input type="text" name="cm_footnote_definitions_row_id[]" class="cm_footnote_definitions_row_id" value="<?php echo $value; ?>">
        </div>
        <?php
        $content = ob_get_clean();
        return $content;
    }

    public static function cmf_render_meta_block_content($block_arr, $existingDefinitions, $i) {
        ob_start();
        $value = (!empty($existingDefinitions['content'][$i]) ? $existingDefinitions['content'][$i] : $block_arr['default']);
        ?>
        <div class="cm_footnote_definitions_td_content">
            <textarea rows="3" cols="50" name="cm_footnote_definitions_row_content[]" class="cm_footnote_definitions_row_content"><?php echo $value ?></textarea>
            <?php do_action('cmf_meta_after_content', $block_arr, $existingDefinitions, $i); ?>
        </div>
        <?php
        $content = ob_get_clean();
        return $content;
    }

    public static function cmf_render_definition_box($post) {
        $existingDefinitions = get_post_meta($post->ID, self::$customFootnotesPostMetaKey, true);

        $metabox_description = '<p>In this section you can put the definitions for the CM Simple Footnotes. To put a footnote link you can use the [cm_simple_footnote id="1"] shortcode. The "id" of the shortcode must match the id of the definition.</p>';
        $metabox_description .= '<p><strong>Important notice :</strong> ID values can contain only letters, digits, hyphens, underscores, colons and periods.</p>';
        echo apply_filters('cmf_metabox_description', $metabox_description);

        $meta_header = apply_filters('cmf_meta_header_arr', [
            ['class' => 'id', 'label' => 'ID', 'default' => '1', 'callback' => [__CLASS__, 'cmf_render_meta_block_id']],
            ['class' => 'definition', 'label' => 'Definition', 'default' => '', 'callback' => [__CLASS__, 'cmf_render_meta_block_content']],
        ]);
        ?>
        <div class="custm-ftnote-tbl">

            <div class="cm-foot-settings-flex-block cm-foot-meta-header-block" >
                <?php echo self::cmf_render_meta_header($meta_header); ?>
            </div>

            <?php
            if (!empty($existingDefinitions)) {
                $j = 1;
                for ($i = 0, $ii = count($existingDefinitions['ids']); $i < $ii; $i++) {
                    ?>
                    <div class="cm-foot-settings-flex-block cm-foot-meta-values-block" >

                        <?php echo self::cmf_render_meta_block($meta_header, $existingDefinitions, $i); ?>

                        <div>
                            <a href="#" class="cm_footnote_definitions_row_remove"><img src="<?php echo self::$imagesPath . 'cancel.png'; ?>" /></a>
                        </div>

                    </div>
                    <?php
                    $j++;
                }
                echo '<input type="hidden" value="' . $j . '" id="get_row-id">';
            } else {
                ?>
                <input type="hidden" value="1" id="get_row-id">
                <div class="cm-foot-settings-flex-block cm-foot-meta-values-block" >

                    <?php echo self::cmf_render_meta_block($meta_header, [], 1); ?>

                    <div>
                        <a href="#" class="cm_footnote_definitions_row_remove"><img src="<?php echo self::$imagesPath . 'cancel.png'; ?>" /></a>
                    </div>

                </div>

                <?php
            }

            echo '<hr>';
            echo '<div><a href="#" id="cm_footnote_add_new_definition" title="Add definition"><img src="' . self::$imagesPath . 'add.png" /></a></div>';
            echo '</div>';
            do_action('cmf_render_definition_box', $post);
        }

        public static function cmf_render_disable_for_page($post) {

            $dTTpage = get_post_meta($post->ID, '_footnote_disable_footnote_for_page', true);
            $disableFootnoteForPage = (int) (!empty($dTTpage) && $dTTpage == 1 );

            $post_type = get_post_type($post->ID);
            if (intval($post->ID) === intval(\com\cminds\footnotes\settings\CMF_Settings::get('page_on_front'))) {
                $post_type = 'frontpage';
            }
            $saved_post_types = \com\cminds\footnotes\settings\CMF_Settings::get('cmf_footnoteOnPosttypes');

            if (!in_array($post_type, $saved_post_types)) {
                $trigger = 'OFF';
            } else {
                $trigger = 'ON';
            };

            echo '<div>';
            echo '<p>' . ucfirst($post_type) . ' type footnotes global is <span class ="cm-post-type-switcher">' . $trigger . '</span></p>';
            echo '<label for="footnote_disable_footnote_for_page" class="blocklabel">';

            echo '<input type="checkbox" name="footnote_disable_footnote_for_page" id="footnote_disable_footnote_for_page" value="1" ' . checked(1, $disableFootnoteForPage, false) . '>';
            if ($trigger === 'ON') {
                echo '&nbsp;&nbsp;&nbsp;Disable Footnotes on this post/page</label>';
            } else {
                echo '&nbsp;&nbsp;&nbsp;Enable Footnotes on this post/page</label>';
            }
            echo '</div>';

            do_action('cmf_add_disables_metabox', $post);
        }

        public static function cmf_save_postdata($post_id) {

            $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $postUnfiltered = filter_input_array(INPUT_POST);

            // Disable / Enable footnotes for current page
            $disableFootnotesForPage = 0;
            if (isset($post["footnote_disable_footnote_for_page"]) && $post["footnote_disable_footnote_for_page"] == 1) {
                $disableFootnotesForPage = 1;
            }
            update_post_meta($post_id, '_footnote_disable_footnote_for_page', $disableFootnotesForPage);

            if (!empty($post['cm_footnote_definitions_row_id'])) {

                $post['cm_footnote_definitions_row_content'] = $postUnfiltered['cm_footnote_definitions_row_content'];

                $postMetaInsertArray = apply_filters('cmf_save_meta_arr', array(
                    'ids'     => $post['cm_footnote_definitions_row_id'],
                    'content' => ( (!empty($post['cm_footnote_definitions_row_content']) ) ? $post['cm_footnote_definitions_row_content'] : array() ),
                        ), $post_id, $post, $postUnfiltered);

                update_post_meta($post_id, self::$customFootnotesPostMetaKey, $postMetaInsertArray);
            }

            do_action('cmf_on_footnote_item_save', $post_id, $post);
        }

        /**
         * Function renders (default) or returns the setttings tabs
         *
         * @param type $return
         * @return string
         */
        public static function renderSettingsTabs($return = false) {
            $content = '';
            $settingsTabsArrayBase = array();

            $settingsTabsArray = apply_filters('cmf-settings-tabs-array', $settingsTabsArrayBase);

            if ($settingsTabsArray) {
                foreach ($settingsTabsArray as $tabKey => $tabLabel) {
                    $filterName = 'cmf-custom-settings-tab-content-' . $tabKey;

                    $content .= '<div id="tabs-' . $tabKey . '">';
                    $tabContent = apply_filters($filterName, '');
                    $content .= $tabContent;
                    $content .= '</div>';
                }
            }

            if ($return) {
                return $content;
            }
            echo $content;
        }

        /**
         * Function renders (default) or returns the setttings tabs
         *
         * @param type $return
         * @return string
         */
        public static function renderSettingsTabsControls($return = false) {
            $content = '';
            $settingsTabsArrayBase = array(
                '1' => 'General Settings',
            );

            $settingsTabsArray = apply_filters('cmf-settings-tabs-array', $settingsTabsArrayBase);

            ksort($settingsTabsArray);

            if ($settingsTabsArray) {
                $content .= '<ul>';
                foreach ($settingsTabsArray as $tabKey => $tabLabel) {
                    $content .= '<li><a href="#tabs-' . $tabKey . '">' . $tabLabel . '</a></li>';
                }
                $content .= '</ul>';
            }

            if ($return) {
                return $content;
            }
            echo $content;
        }

        public static function outputCustomPostTypesList($return_array = 0) {
            $content = '';
            $array = [];
            $args = array(
                'public' => true,
//            '_builtin' => false
            );
            $output = 'objects'; // names or objects, note names is the default
            $operator = 'and'; // 'and' or 'or'

            $post_types = get_post_types($args, $output, $operator);

            $post_types['homepage'] = (object) array(// Add to parsed Object Homepage to control Options
                        'name'   => 'frontpage',
                        'label'  => 'Homepage',
                        'labels' => (object) array(
                            'name'          => 'Homapage',
                            'singular_name' => 'Homepage')
            );

            if (!$return_array) {
                $selected_post_types = \com\cminds\footnotes\settings\CMF_Settings::get('cmf_footnoteOnPosttypes');
                if (!is_array($selected_post_types)) {
                    $selected_post_types = array();
                }
            }

            foreach ($post_types as $post_type) {
                $label = $post_type->labels->singular_name . ' (' . $post_type->name . ')';
                $name = $post_type->name;
                if (!$return_array) {
                    $content .= '<div class="cm-foot-cont-links-symb-field"><label><input type="checkbox" name="cmf_footnoteOnPosttypes[]" ' . checked(true, in_array($name, $selected_post_types), false) . ' value="' . $name . '" />' . $label . '</label></div>';
                } else {
                    $array[$name] = $label;
                }
            }
            if ($return_array) {
                return $array;
            }
            return $content;
        }

        /**
         * Plugin activation
         */
        protected static function _activate() {
            do_action('cmf_do_activate');
        }

        /**
         * Plugin installation
         *
         * @global type $wpdb
         * @param type $networkwide
         * @return type
         */
        public static function _install($networkwide) {
            global $wpdb;

            if (function_exists('is_multisite') && is_multisite()) {
                // check if it is a network activation - if so, run the activation function for each blog id
                if ($networkwide) {
                    $old_blog = $wpdb->blogid;
                    // Get all blog ids
                    $blogids = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM {$wpdb->blogs}"));
                    foreach ($blogids as $blog_id) {
                        switch_to_blog($blog_id);
                        self::_activate();
                    }
                    switch_to_blog($old_blog);
                    return;
                }
            }

            self::_activate();
        }

        /**
         * Scoped i18n function
         * @param type $message
         * @return type
         */
        public static function __($message) {
            return __($message, CMF_SLUG_NAME);
        }

        /**
         * Scoped i18n function
         * @param type $message
         * @return type
         */
        public static function _e($message) {
            return _e($message, CMF_SLUG_NAME);
        }

        public static function _get_meta($meta_key, $id = null) {
            global $wpdb;
            static $_cache = array();

            if (!isset($_cache[$meta_key])) {
                $_cache[$meta_key] = array_column($wpdb->get_results($wpdb->prepare('SELECT post_id,meta_value FROM ' . $wpdb->postmeta . ' WHERE meta_key="%s" LIMIT %d', $meta_key, '18446744073709551615'), ARRAY_A), 'meta_value', 'post_id');
            }

            if (null !== $id) {
                $result = isset($_cache[$meta_key][$id]) ? $_cache[$meta_key][$id] : false;
            } else {
                $result = $_cache[$meta_key];
            }
            return $result;
        }

    }
    