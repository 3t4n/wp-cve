<?php

/**
 * Easy Related Posts .
 *
 * @package Easy_Related_Posts
 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @link http://example.com
 * @copyright 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 */

/**
 * Plugin class.
 *
 * @package Easy_related_posts
 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
 */
class easyRelatedPosts {

    /**
     * Plugin version, used for cache-busting of style and script file references.
     *
     * @since 2.0.0
     * @var string
     */
    const VERSION = '2.0.2';

    /**
     * Unique identifier for your plugin.
     * The variable name is used as the text domain when internationalizing strings
     * of text. Its value should match the Text Domain file header in the main
     * plugin file.
     *
     * @since 2.0.0
     * @var string
     */
    protected $plugin_slug = ERP_SLUG;

    /**
     * Instance of this class.
     *
     * @since 2.0.0
     * @var object
     */
    protected static $instance = null;

    /**
     * Main options class.
     *
     * @since 2.0.0
     * @var erpMainOpts
     */
    protected $widOpts;

    /**
     * Widget options class.
     *
     * @since 2.0.0
     * @var erpMainOpts
     */
    protected $mainOpts;

    /**
     * Default options class.
     *
     * @deprecated
     *
     * @since 2.0.0
     * @var erpDefaults
     */
    protected $defOpts;

    /**
     * Initialize the plugin by setting localization and loading public scripts
     * and styles.
     *
     * @since 2.0.0
     */
    private function __construct() {
        // Dependencies
        erpPaths::requireOnce(erpPaths::$erpMainOpts);
        erpPaths::requireOnce(erpPaths::$erpWidOpts);

        $this->mainOpts = new erpMainOpts();
        $this->widOpts = new erpWidOpts();

        // }
        /**
         * Call content modifier
         */
        add_filter('the_content', array(
            $this,
            'contentFilter'
                ), 1000);

        // Load plugin text domain
        add_action('init', array(
            $this,
            'load_plugin_textdomain'
        ));

        // Activate plugin when new blog is added
        add_action('wpmu_new_blog', array(
            $this,
            'activate_new_site'
        ));

        // Load public-facing style sheet and JavaScript.
        add_action('wp_enqueue_scripts', array(
            $this,
            'enqueue_styles'
        ));
        add_action('wp_enqueue_scripts', array(
            $this,
            'enqueue_scripts'
        ));
    }

    /**
     * Decides if content should be modified, if yes calls content modifier
     *
     * @param string $content
     * @return string
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function contentFilter($content) {
        global $post;
        /**
         * Check if is time to take action
         */
        if ($this->isShowTime($post) && !$this->isInExcludedPostTypes($post) && !$this->isInExcludedTaxonomies($post) && (bool) $this->mainOpts->getValue('activate')) {

            erpPaths::requireOnce(erpPaths::$erpRelated);
            erpPaths::requireOnce(erpPaths::$VPluginThemeFactory);
            
            $relatedObj = erpRelated::get_instance($this->mainOpts);
            $result = $relatedObj->getRelated($post->ID);
            $ratings = $relatedObj->getRatingsFromRelDataObj();
            if (empty($result) || empty($result->posts)) {
                return $content;
            }

            VPluginThemeFactory::registerThemeInPathRecursive(erpPaths::getAbsPath(erpPaths::$mainThemesFolder), $this->mainOpts->getDsplLayout());
            $theme = VPluginThemeFactory::getThemeByName($this->mainOpts->getDsplLayout());
            if(!$theme){
                return $content;
            }
            $theme->formPostData($result, $this->mainOpts, $ratings);
                        
            $relContent = $theme->render();
            
            return $content . $relContent;
        }
        return $content;
    }

    /**
     * Checks if current post belongs in an excluded post type
     * Returns true iff post is of excluded types defined in main options
     *
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function isInExcludedPostTypes($post) {
        $postType = get_post_type($post);
        if (!empty($postType) && is_array($this->mainOpts->getPostTypes()) && in_array($postType, $this->mainOpts->getPostTypes())) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Checks if current post belongs in an excluded taxonimies
     * Returns true iff all post categories are in excluded ones
     * or all post tags are in excluded ones
     *
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function isInExcludedTaxonomies($post) {
        if (is_a($post, 'WP_Post')) {
            $post = $post->ID;
        }

        $exCats = $this->mainOpts->getCategories();
        if (!empty($exCats)) {
            $postCategories = get_the_category($post);
            if (is_array($postCategories) && !empty($postCategories)) {
                $catIds = array();
                foreach ($postCategories as $cat) {
                    array_push($catIds, $cat->term_id);
                }
                $intersect = array_intersect($catIds, $exCats);
                if (!empty($intersect) && count($intersect) == count($postCategories)) {
                    return TRUE;
                }
            }
        }

        $exTags = $this->mainOpts->getTags();
        if (!empty($exTags)) {
            $postTags = get_the_tags($post);
            if (is_array($postTags) && !empty($postTags)) {
                $tagsIds = array();
                foreach ($postTags as $tag) {
                    array_push($tagsIds, (string) $tag->term_id);
                }
                $intersect = array_intersect($tagsIds, $exTags);
                if (!empty($intersect) && count($intersect) == count($postTags)) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    /**
     * Checks if it's time to display related
     *
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public function isShowTime($post) {
        if (empty($post) || !is_single($post->ID) || !is_main_query() || !in_the_loop()) {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Return the plugin slug.
     *
     * @since 2.0.0
     * @return Plugin slug variable.
     */
    public function get_plugin_slug() {
        return $this->plugin_slug;
    }

    /**
     * Return an instance of this class.
     *
     * @since 2.0.0
     * @return easyRelatedPosts A single instance of this class.
     */
    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Fired when the plugin is activated.
     *
     * @since 2.0.0
     * @param boolean $network_wide
     *        	True if WPMU superadmin uses
     *        	"Network Activate" action, false if
     *        	WPMU is disabled or plugin is
     *        	activated on an individual blog.
     */
    public static function activate($network_wide) {
        if (function_exists('is_multisite') && is_multisite()) {

            if ($network_wide) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ($blog_ids as $blog_id) {

                    switch_to_blog($blog_id);
                    self::single_activate();
                }

                restore_current_blog();
            } else {
                self::single_activate();
            }
        } else {
            self::single_activate();
        }
    }

    /**
     * Fired when the plugin is deactivated.
     *
     * @since 2.0.0
     * @param boolean $network_wide
     *        	True if WPMU superadmin uses
     *        	"Network Deactivate" action, false if
     *        	WPMU is disabled or plugin is
     *        	deactivated on an individual blog.
     */
    public static function deactivate($network_wide) {
        if (function_exists('is_multisite') && is_multisite()) {

            if ($network_wide) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ($blog_ids as $blog_id) {

                    switch_to_blog($blog_id);
                    self::single_deactivate();
                }

                restore_current_blog();
            } else {
                self::single_deactivate();
            }
        } else {
            self::single_deactivate();
        }
    }

    /**
     * Fired when a new site is activated with a WPMU environment.
     *
     * @since 2.0.0
     * @param int $blog_id
     *        	ID of the new blog.
     */
    public function activate_new_site($blog_id) {
        if (1 !== did_action('wpmu_new_blog')) {
            return;
        }

        switch_to_blog($blog_id);
        self::single_activate();
        restore_current_blog();
    }

    /**
     * Get all blog ids of blogs in the current network that are:
     * - not archived
     * - not spam
     * - not deleted
     *
     * @since 2.0.0
     * @return array false blog ids, false if no matches.
     */
    private static function get_blog_ids() {
        global $wpdb;

        // get an array of blog ids
        $sql = "SELECT blog_id FROM $wpdb->blogs
		WHERE archived = '0' AND spam = '0'
		AND deleted = '0'";

        return $wpdb->get_col($sql);
    }

    /**
     * Fired for each blog when the plugin is activated.
     *
     * @since 2.0.0
     */
    private static function single_activate() {
        erpPaths::requireOnce(erpPaths::$erpActivator);

        $compareVersions = erpDefaults::compareVersion(get_option(erpDefaults::versionNumOptName));

        if ($compareVersions < 0) {
            // New install

            /**
             * If an old version is present translate options
             */
            if (get_option('erpVersion')) {
                $mainOpts = self::migrateMainOptions();
                
                $notice = new WP_Updated_Notice('<strong>Easy Related Posts updated from V1 to V2.</strong>'
                        . 'You should review the main plugin and widget settings.<br>'
                        . 'There are some major changes in this version and this affects options as well. '
                        . 'We are  sorry for the inconvenience but this was necessary to move this plugin forward');
                WP_Admin_Notices::getInstance()->addNotice($notice);
                
                // Delete old options
                delete_option('erpVersion');
                delete_option('erpSubVersion');
                delete_option('erpOpts');
            } else {
                $mainOpts = erpDefaults::$comOpts + erpDefaults::$mainOpts;
            }
            erpActivator::addNonExistingMainOptions($mainOpts, EPR_MAIN_OPTIONS_ARRAY_NAME);
            erpActivator::addNonExistingWidgetOptions(erpDefaults::$comOpts + erpDefaults::$mainOpts, 'widget_' . erpDefaults::erpWidgetOptionsArrayName);
            erpDefaults::updateVersionNumbers();
        } elseif ($compareVersions === 0) {
            // Major update
            erpDefaults::updateVersionNumbers();
        } elseif ($compareVersions === 1) {
            // Release update
            erpDefaults::updateVersionNumbers();
        } elseif ($compareVersions === 2) {
            // Minor update
            erpDefaults::updateVersionNumbers();
        }
    }

    private static function migrateMainOptions() {
        $oldOptions = get_option('erpOpts');
        if (empty($oldOptions) || $oldOptions === false) {
            return erpDefaults::$comOpts + erpDefaults::$mainOpts;
        }
        $defOptions = erpDefaults::$comOpts + erpDefaults::$mainOpts;

        $opt = array();

        $opt['title'] = $oldOptions ['titletd'];
        if(isset($opt ['activate_plugin'])){
            $opt['activate'] = $oldOptions ['activate_plugin'];
        }
        if(isset($oldOptions ['num_of_p_t_dspl'])){
            $opt['numberOfPostsToDisplay'] = $oldOptions ['num_of_p_t_dspl'];
        }
        
        if(isset($oldOptions ['getPostsBy'])){
            $opt['fetchBy'] = $oldOptions ['getPostsBy'];
        }
        
        
        $opt['content'] = array();
        if ($oldOptions['display_thumbnail']) {
            array_push($opt['content'], 'thumbnail');
        }
        if ($oldOptions['erpcontent'] == 'post_title') {
            array_push($opt['content'], 'title');
        } else {
            array_push($opt['content'], 'title');
            array_push($opt['content'], 'excerpt');
        }
        if(isset($oldOptions ['exc_len'])){
            $opt['excLength'] = (int) ceil($oldOptions ['exc_len'] / 8) + 1;
        }
        
        if(isset($oldOptions ['ttl_sz'])){
            $opt['postTitleFontSize'] = $oldOptions ['ttl_sz'];
        }
        
        if(isset($oldOptions ['exc_sz'])){
            $opt['excFontSize'] = $oldOptions ['exc_sz'];
        }
        
        if(isset($oldOptions ['more_txt'])){
            $opt['moreTxt'] = $oldOptions ['more_txt'];
        }
        
        $opt['dsplLayout'] = 'Grid';
        if(isset($oldOptions['crop_thumbnail'])){
            $opt['cropThumbnail'] = $oldOptions['crop_thumbnail'] == 1;
        }
        
        if(isset($oldOptions ['thumbnail_height'])){
            $opt['thumbnailHeight'] = $oldOptions ['thumbnail_height'];
        }
        
        if(isset($oldOptions ['thumbnail_width'])){
            $opt['thumbnailWidth'] = $oldOptions ['thumbnail_width'];
        }
        
        if(isset($oldOptions['categories'])){
            $opt ['categories'] = $oldOptions['categories'];
        }
        
        if(isset($oldOptions['tags'])){
            $opt ['tags'] = $oldOptions['tags'];
        }
        
        if(isset($oldOptions ['post_types'])){
            $opt['postTypes'] = $oldOptions ['post_types'];
        }
        

        return array_merge($defOptions, $opt);
    }

    /**
     * Fired for each blog when the plugin is deactivated.
     *
     * @since 2.0.0
     */
    private static function single_deactivate() {
        
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since 2.0.0
     */
    public function load_plugin_textdomain() {
        $domain = $this->plugin_slug;
        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, trailingslashit(WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
        load_plugin_textdomain($domain, FALSE, basename(plugin_dir_path(dirname(__FILE__))) . '/languages/');
    }

    /**
     * Register and enqueue public-facing style sheet.
     *
     * @since 2.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_slug . '-plugin-styles', plugins_url('assets/css/public.min.css', __FILE__), array(), self::VERSION);
        wp_register_style($this->plugin_slug . '-bootstrap', plugins_url('assets/css/bootstrap.min.css', __FILE__), array(), self::VERSION);
        wp_register_style($this->plugin_slug . '-bootstrap-text', plugins_url('assets/css/bootstrap-text.min.css', __FILE__), array(), self::VERSION);
        wp_register_style($this->plugin_slug . '-erpCaptionCSS', plugins_url('assets/css/captionjs.min.css', __FILE__), array(), self::VERSION);
    }

    /**
     * Register and enqueues public-facing JavaScript files.
     *
     * @since 2.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_slug . '-plugin-script', plugins_url('assets/js/public.min.js', __FILE__), array('jquery'), self::VERSION);
        wp_enqueue_script($this->plugin_slug . '-erpCaptionJS', plugins_url('assets/js/jquery.caption.min.js', __FILE__), array('jquery'), self::VERSION);
    }

}
