<?php
/**
 * @package Gator Cache
 * @version 2.1.8
 */
/*
Plugin Name: Gator Cache
Plugin URI: http://wordpress.org/plugins/gator-cache/
Description: A Better, Stronger, Faster WordPress Cache Plugin. Easy to install and manage.
Author: GatorDev
Author URI: http://www.gatordev.com/
Text Domain: gator-cache
Domain Path: /lang
Version: 2.1.8
*/
class WpGatorCache
{
    protected static $defaults = array(
        'installed' => false,
        'enabled' => false,
        'oc_enabled' => false,
        'debug' => true,
        'lifetime' => array('value' => '2', 'unit' => 'week', 'sec' => 1209600),
        'post_types' => array('product'),
        'exclude_paths' => array(),
        'refresh_paths' => array('all' => array()),
        'app_support' => array(),
        'roles' => array(),
        'only_user' => false,
        'refresh' => array('home' => true, 'archive' => true, 'all' => false),
        'pingback' => false,
        'skip_ssl' => true,
        'version' => false,
        'multisite_paths' => false,
        'enable_hooks' => false,
        'jp_mobile_cache' => false,
        'cache_warm' => false,
        'skip_feeds' => false,
        'sys_load' => 0,
        'mobile' => array('phone' => false, 'tablet' => false, 'mobile' => false, 'ios' => false, 'android' => false),
    );

    protected static $options;
    protected static $path;
    protected static $configPath;
    protected static $sslHandler; // specific handler for WordPressHTTPS
    protected static $obHandlers = array(); // array of generic handlers for other ob handlers that have started before GatorCache
    protected static $webUser;
    protected static $multiSiteData;
    const PREFIX = 'gtr_cache';
    const VERSION = '2.1.8';
    const JP_MOBILE_MOD = 'minileven'; // JetPack mobile module slug
    const SUPPORT_LINK = 'https://wordpress.org/support/plugin/gator-cache';

    public static function initBuffer()
    {
        $options = self::getOptions();
        $request = GatorCache::getRequest();
        // work-around for recent version of WooCommerce prevent caching on later hook
        if (defined('WOOCOMMERCE_VERSION') && false !== has_filter('wp', 'WC_Cache_Helper::prevent_caching')) {
            WC_Cache_Helper::prevent_caching();
            remove_filter('wp', 'WC_Cache_Helper::prevent_caching');
        }
        global $post;
        if (!$options['enabled']
          || '.php' === ($ext = substr($path = $request->getPathInfo(), -4)) || '.txt' === $ext || '.xml' === $ext //uri returns the whole qs
          || (defined('DONOTCACHEPAGE') && DONOTCACHEPAGE)
          || !isset($post)
          || false === ($config = GatorCache::getConfig(self::$configPath, true)) // check if cache exists, advanced cache can return for log in cookies
          || false === ($cache = GatorCache::getCache($opts = $config->toArray()))
          || $cache->has($path, $request->isSecure() ? 'ssl@' . $opts['group'] : $opts['group']) // group is checked in advanced cache
          || ($options['skip_feeds'] && is_feed())
          || 'GET' !== $request->getMethod()
          || $request->hasQueryString()
          || ('post' !== $post->post_type && 'page' !== $post->post_type && !in_array($post->post_type, self::getCacheTypes()))
          || (defined('DOING_AJAX') && DOING_AJAX) || is_admin()
          || (($loggedIn = is_user_logged_in()) && (!$options['enable_hooks'] || !self::cacheUserContent()))
          || (!$loggedIn && $options['only_user'])
          || '' === get_option('permalink_structure')
          || self::hasPathExclusion($path)
          || self::isWooCart()
          || isset($_COOKIE['comment_author_' . COOKIEHASH])
          || (self::isJetPackMobileSite() && !$options['jp_mobile_cache'])
          //|| ($options['enable_hooks'] && apply_filters('gc_skip_cache', false))
          //|| (false !== $options['multisite_paths'] && self::isMultiSubPath($path))
          || (($isSecure = $request->isSecure()) && $options['skip_ssl'])
         ) {
            return;
        }
        // check for compatiblity with plugins that modify output from buffers added before this
        self::checkObHandlers($isSecure);
        if ($options['enable_hooks']) {
            $bufferHandlers = apply_filters('gc_buffer_handlers', array());
            if (!empty($bufferHandlers)) { //apply handlers
                foreach ($bufferHandlers as $handler) {
                    if (isset($handler['callback']) && is_callable($handler['callback'])) {
                        self::$obHandlers[] = array('handler' => false, 'method' => $handler['callback'], 'name' => isset($handler['name']) ? $handler['name'] : '');
                    }
                }
            }
        }
        ob_start('WpGatorCache::onBuffer');
    }

    public static function onBuffer($buffer, $phase)
    {
        if (empty($buffer) || is_404() || !self::responseOk()) {
            //do not cache
            return $buffer;
        }
        $options = self::getOptions();
        if (false === ($config = GatorCache::getConfig(self::$configPath, true))) {
            //check config is loaded
            return;
        }
        if (false === ($cache = GatorCache::getCache($opts = $config->toArray()))) { //jpmobile group is set in advanced-cache.php
            return $buffer;
        }
        if ($options['debug']) {
            global $post;
            $buffer .= ($debugMsg = "\n" . '<!-- Gator Cached ' . $post->post_type . (isset(self::$sslHandler) ? ' via ' . self::$sslHandler : '') . ' on [' . gmdate('Y-m-d H:i:s', time() + (get_option('gmt_offset') * 3600)) . '] -->');
        } else {
            $debugMsg = '';
        }
        $request = GatorCache::getRequest();
        if ($request->isSecure()) {
            $opts['group'] = 'ssl@' . $opts['group'];
        }
        if (!$cache->has($path = $request->getPathInfo(), $opts['group'])) {
            if (isset(self::$sslHandler) && false !== ($replace = self::doHttpsHandler($buffer))) {
                $buffer = $replace;
            }
            if (!empty(self::$obHandlers)) {
                $buffer = self::doObHandlers($buffer, $debugMsg);
            }
            $result = $cache->save($path, $buffer, $opts['group']);//return $result;
        }
        return $buffer;// "\n <!--" . ($hasItem ? ' exists ' : ' written ') . $path . ' ' . $cache->getCache()->_file . ' ' .  $cache->getCache()->has($id, $opts['group']) .  '-->';
    }

    public static function chkUser($cookie_elements, $user)
    {
        if (!defined('GC_CHK_USER') || is_admin()) {
            return;
        }
        $options = self::getOptions();
        if (!$options['enabled'] || empty($user->roles) || 1 < count($user->roles)) {
            //no cache for mult user roles, this indicates custom role such as bbpress
            return;
        }
        $cacheme = array_intersect($options['roles'], (array)$user->roles);
        if (!empty($cacheme)) {
            //serve the cache
            include(WP_CONTENT_DIR . '/advanced-cache.php');
        }
    }

    public static function updateSettings()
    {
        $options = self::getOptions(); // also sets config path and loads GatorCache factory
        GatorCache::getAdminSettings($options, self::$configPath)->update();
    }

    public static function Activate()
    {
        $options = self::getOptions();
        GatorCache::getPlugin($options, self::$configPath, self::$path)->activate();
    }

    public static function Deactivate()
    {
        $options = self::getOptions();
        GatorCache::getPlugin($options, self::$configPath, self::$path)->deactivate();
    }

    public static function doInstall()
    {
        if (!current_user_can('edit_posts')) {
            die('0');
        }
        $options = self::getOptions();
        GatorCache::getPlugin($options, self::$configPath, self::$path)->install();
    }

    public static function prePostUpdate($postId)
    {
        $options = self::getOptions();
        GatorCache::getRefresh($options, self::$configPath)->getPreUpdateData($postId);
    }

    public static function savePost($new_status, $old_status, $post)
    {
        $options = self::getOptions();
        GatorCache::getRefresh($options, self::$configPath)->refresh($new_status, $old_status, $post);
    }

    public static function checkUpgrade()
    {
        $options = self::getOptions();
        GatorCache::getPlugin($options, self::$configPath, self::$path)->checkUpgrade();
    }

/**
 * newMpmuSite
 *
 * If the plugin is not active for the network, add subsite paths not
 * to be cached
 *
 * @note: need the blog id, not sure if it's passed here
 */
    public static function newMpmuSite($domain, $path)
    {
        /*$options = self::getOptions();
        GatorCache::getOptions(self::PREFIX . '_opts', self::$defaults);*/
        return true;
    }

    public static function addOptMenu()
    {
        if (current_user_can('install_plugins') || (is_multisite() && current_user_can('activate_plugins'))) {
            add_menu_page('Gator Cache', 'Gator Cache', 'edit_posts', self::PREFIX, 'WpGatorCache::renderMenu', 'dashicons-performance', '76.5');
        }
    }

    public static function renderMenu()
    {
        $options = self::getOptions();
        //var_dump($options);
        if (!GatorCache::getPlugin($options, self::$configPath, self::$path)->verifyInstall(self::$defaults)) {
            //new install or corrupted install
            include self::$path . 'tpl/install.php';
            return;
        }
        $config = GatorCache::getConfig(self::$configPath);
        include  self::$path . 'tpl/options.php';
    }

    public static function settingsLink($links)
    {
        $links[] = '<a href="' . admin_url('admin.php?page=' . self::PREFIX) .'">Settings</a>';
        $links[] = '<a href="' . self::SUPPORT_LINK . '" target="_blank">Support</a>';
        return $links;
    }

    public static function addToolbarButton()
    {
        global $wp_admin_bar; //@note wp_admin_bar_render is hooked on wp_footer 1000
        GatorCache::getPurge(self::getOptions(), self::$configPath)->renderToolbar($wp_admin_bar);
    }

    public static function xhrDelete()
    {
        GatorCache::getPurge(self::getOptions(), self::$configPath)->handleXhr();
    }

    public static function loadPurgeScripts()
    {
        wp_enqueue_style('dash-icons');
        wp_enqueue_script('gc-purge', ($pluginUrl = plugins_url(null, __FILE__)) . '/js/gator-cache-purge.min.js', array('jquery'), null, true);
        wp_localize_script('gc-purge', 'gcData', array(
            'page' => array('path' => is_admin() ? '' : GatorCache::getRequest()->getPathInfo(), 'token' => wp_create_nonce('gc_purge'), 'action' => 'gc_delete'),
            'ajaxurl' => admin_url('admin-ajax.php'),
            'msg' => array('page' => __('Page refreshed', 'gator-cache'), 'zap' => __('Cache deleted', 'gator-cache'), 'loading' => __('Loading', 'gator-cache')),
            //'loading' => version_compare(get_bloginfo('version'), '3.9', '>=') ? '/wp-includes/js/tinymce/skins/lightgray/img/loader.gif' : '/wp-includes/js/tinymce/themes/advanced/skins/default/img/progress.gif',
        ));
    }

    public static function loadAdminJs($context)
    {
        if ('toplevel_page_gtr_cache' !== $context) {
            return;
        }
        if (wp_script_is('chosen', 'registered')) {
            //make sure the correct version of chosen is registered
            wp_deregister_script('chosen');
        }
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('jquery-ui-selectable');
        wp_enqueue_script('chosen', ($pluginUrl = plugins_url(null, __FILE__)) . '/js/chosen.jquery.min.js', array('jquery'), '0.9.8', true);
        wp_enqueue_script('gator-cache', $pluginUrl . '/js/gator-cache.min.js', array('jquery-ui-tabs'), self::VERSION, true);
        wp_enqueue_style('jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/redmond/jquery-ui.css', array(), null);
        wp_enqueue_style('chosen', $pluginUrl . '/css/chosen.css', array(), '0.9.8');
        wp_enqueue_style('gator-cache', $pluginUrl . '/css/gator-cache.css', array(), self::VERSION);
        wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css', array(), null);
        // remove any annoying admin notices that are unrelated to the GatorCache dash
        remove_all_actions('admin_notices');
        remove_all_actions('all_admin_notices');
        // remove pesky woocommerce notices from our lovely dashboard
        if (defined('WOOCOMMERCE_VERSION') && class_exists('WC_Admin_Notices', false)) {
            global $wp_filter;
            $wp_filter['admin_print_styles'][10] = array_filter($wp_filter['admin_print_styles'][10], 'WpGatorCache::filterNotices');
        }
    }

    public static function filterNotices($callback)
    {//static instead of anonymous function WP still supports php 5.2
        return !is_array($callback['function']) || !($callback['function'][0] instanceof WC_Admin_Notices);
    }

    public static function filterCacheUpdate($v)
    {
        return null !== $v;
    }

    public static function pingSetting($whitelist_options)
    {
        if (isset($_POST['option_page']) && 'discussion' === $_POST['option_page']) {
            $options = self::getOptions();
            $pingback = isset($_POST['default_ping_status']) && 'open' === $_POST['default_ping_status'];
            if ($pingback !== $options['pingback']) {
                GatorCache::getOptions(self::PREFIX . '_opts')->save('pingback', $pingback);
                GatorCache::getConfig(self::$configPath)->save('pingback', $pingback ? get_bloginfo('pingback_url') : false);
            }
        }
        return $whitelist_options;
    }

    public static function savePostContext($location)
    {
        $options = self::getOptions();
        if (GatorCache::getRefresh($options, self::$configPath)->isRefreshed()) {
            $location = add_query_arg('gtrcached', 1, $location);
        }
        return $location;
    }

    public static function savePostMsg($messages)
    {
        if (isset($_GET['gtrcached'])) {
            $options = self::getOptions();
            if (!$options['enabled']) {
                return $messages;
            }
            $extra = sprintf(' (GatorCache %s)', __('refreshed', 'gator-cache'));
            $messages['post'][1] .=  $extra;
            $messages['page'][1] .= $extra;
            foreach ($options['post_types'] as $type) {
                if (isset($messages[$type])) {
                    $messages[$type][1] .= $extra;
                }
            }
        }
        return $messages;
    }

    public static function postComment($id, $comment = null)
    {
        if (!isset($comment)) {
            $comment = get_comment($id);
        }
        if ($comment->comment_approved) {
            self::saveComment('approved', 'any', $comment);
        }
    }

    public static function saveComment($new_status, $old_status, $comment)
    {
        if ('approved' !== $new_status && 'approved' !== $old_status) {
            //will not change page
            return;
        }
        if (null === ($path =  parse_url(get_permalink($comment->comment_post_ID), PHP_URL_PATH))) {
            return;
        }
        $options = self::getOptions();
        if (false === ($cache = GatorCache::getCache($opts = GatorCache::getConfig(self::$configPath)->toArray()))) {
            return;
        }
        $cache->removeGroups($path, $groups = self::getCacheGroups($opts));
        //purge the feed
        if (!$options['skip_feeds']) {
            $cache->removeGroups('/comments/feed', $groups);
        }
    }

    public static function filterCookieLifetime($lifetime)
    {
        return 1800;//set to reasonable lifetime, 0 won't work for life of the browser session, see wp_set_comment_cookies
    }

    public static function loadTextDomain()
    {
        load_plugin_textdomain('gator-cache', false, 'gator-cache/lang/');
    }

    public static function filterStatus($header)
    {
        return 0 === strpos($header, 'Location');//the status header is not in the return stack
    }

    public static function responseOk()
    {
        //in 5.4 see http_response_code
        $status = array_filter(headers_list(), 'WpGatorCache::filterStatus');//in 5.3 simply use a lambda
        return empty($status);
    }

    public static function getWebUser($name = true)
    {
        if (!isset(self::$webUser)) {
            if (function_exists('posix_geteuid')) {
                $user = posix_getpwuid(posix_geteuid());
                $group = posix_getgrgid($user['gid']);
                self::$webUser = array('user' => $user['name'], 'group' => $group['name']);
            } else {
                $user = get_current_user();
                self::$webUser =  array('user' => $user, 'group' => $user);
            }
        }
        return $name ? self::$webUser['user'] : self::$webUser['group'];
    }

    public static function getCacheDir()
    {
        self::getOptions();//loads GatorCache and config path
        return GatorCache::getConfig(self::$configPath)->get('cache_dir');
    }

    public static function getCache()
    {
        self::getOptions();
        return GatorCache::getCache(GatorCache::getConfig(self::$configPath)->toArray());
    }

/**
 * purgePath
 *
 * Public access to purging a url path. Will purge in all cache groups.
 *
 * @param $path string | bool the relative url path, false to flush cache
 */
    public static function purgePath($path)
    {
        $options = self::getOptions();
        if (false === ($cache = GatorCache::getCache($opts = GatorCache::getConfig(self::$configPath)->toArray()))) {
            return;
        }
        if (empty($path)) {
            $cache->purgeGroups(self::getCacheGroups($opts));
            return;
        }
        $cache->removeGroups($path, self::getCacheGroups($opts));
    }

/**
 * flush
 *
 * Public access to purging the entire cache.
 */
    public static function flush()
    {
        GatorCache::flushCache(self::$configPath);
    }

    protected static function getOptions()
    {
        if (isset(self::$options)) {
            return self::$options;
        }
        require_once((self::$path = plugin_dir_path(__FILE__)) . 'lib/GatorCache.php');
        self::$configPath = self::getConfigPath();
        //rather than implementing arrayaccess
        return self::$options = GatorCache::getOptions(self::PREFIX . '_opts', self::$defaults)->toArray();
    }

    protected static function getConfigPath()
    {
        return ABSPATH . (is_multisite() ? 'gc-config-' . get_current_blog_id() . '.ini.php' : 'gc-config.ini.php');//has to go here in case if subdir hosts
    }

    protected static function hasPathExclusion($path)
    {
        if ('/' === $path) {
            return in_array('/', self::$options['exclude_paths']);
        }
        foreach (self::$options['exclude_paths'] as $exPath) {
            if ('/' !== $exPath && strstr($path, $exPath)) {
                return true;
                break;
            }
        }
        return false;
    }

    protected static function isMultiSubPath($path)
    {
        foreach (self::$options['multisite_paths'] as $subPath) {
            if (0 === strpos($path, $subPath)) {
                return true;
                break;
            }
        }
        return false;
    }

    protected static function getCacheTypes()
    {
        if (!isset(self::$options['app_support']['bbpress']) || !in_array('bbpress', self::$options['post_types'])) {
            return self::$options['post_types'];
        }
        $options = self::$options;
        array_shift($options['app_support']['bbpress']);//exclude reply
        return array_merge($options['post_types'], array_keys($options['app_support']['bbpress']));
    }

    public static function filterWidgets($name)
    {
        return 0 === strpos($name, 'recent') && false === strpos($name, 'recently') && false === strpos($name, 'comments');
    }

    public static function filterNggBuffer($valid)
    {
        return false;
    }

    
    public static function isMultiSite()
    {
        if (isset(self::$multiSiteData)) {
            return self::$multiSiteData['isMulti'];
        }
        self::$multiSiteData = array();
        return self::$multiSiteData['isMulti'] = is_multisite();
    }

    public static function isMainSite()
    {
        if (!isset(self::$multiSiteData)) {
            self::isMultiSite();
        }
        if (!isset(self::$multiSiteData['isMain'])) {
            self::$multiSiteData['isMain'] = self::$multiSiteData['isMulti'] && is_main_site(get_current_blog_id());
            self::$multiSiteData['isSubDomain'] = self::$multiSiteData['isMulti'] && is_subdomain_install();
        }
        return self::$multiSiteData['isMain'];
    }

    public static function isMultiSubDomain()
    {
        if (!isset(self::$multiSiteData['isSubDomain'])) {
            self::isMainSite();
        }
        return self::$multiSiteData['isSubDomain'];
    }

    public static function hasRecentWidgets()
    {
        if (false === ($sidebars = get_option('sidebars_widgets')) || empty($sidebars)) {
            //instead of wp_get_sidebars_widgets()
            return false;
        }
        $hasRecent = false;
        foreach ($sidebars as $key => $value) {
            if ('array_version' !== $key && is_array($value) && false === strpos($key, 'orphan') && false === strpos($key, 'inactive')) {
                $recent = array_filter($value, 'WpGatorCache::filterWidgets');
                if (!empty($recent)) {
                    $hasRecent = true;
                    break;
                }
            }
        }
        return $hasRecent;
    }

    protected static function rangeSelect($min, $max, $sel)
    {
        for ($max++, $xx=$min;$xx<$max;$xx++) {
            $opts[] = '<option value="' . $xx . '"' . ($xx == $sel ? ' selected="selected"' : '') . '>' . $xx . '</option>';
        }
        return implode("\n", $opts);
    }

    protected static function getSupportInfo()
    {
        return '<textarea style="background:cyan;width:100%;" rows="6">
WordPress: ' . get_bloginfo('version') . '
PHP: ' . phpversion() . '
Handler: ' . php_sapi_name() . '
System: ' . php_uname() . '
Web User: ' . self::getWebUser() . '
Writable: ' . (is_writable(self::$path . 'lib' . DIRECTORY_SEPARATOR . 'config.ini.php') ? 'Yes' : 'No') . '
</textarea>';
        //Path: ' . $path; echo var_export($options);echo var_export($config->toArray());
    }

    protected static function isWooCart()
    {
        //don't cache the mini-cart, lots of themes php code it
        global $woocommerce;
        return defined('WOOCOMMERCE_VERSION') && isset($woocommerce) && isset($woocommerce->cart) && 0 < $woocommerce->cart->cart_contents_count;
    }

    protected static function checkObHandlers($isSecure)
    {
        $buffers = ob_list_handlers();
        if (empty($buffers)) {
            return false;
        }
        for ($buffered = false, $ct = count($buffers), $xx = 0; $xx < $ct; $xx++) {
            if (0 === strpos($buffers[$xx], 'WordPressHTTPS') && $isSecure) {
                //look for the https plugin ob handler
                $buffered = true;
                self::$sslHandler = $buffers[$xx];
                // break;
            } elseif ('WPMinify::modify_buffer' === $buffers[$xx] && isset($GLOBALS['wp_minify'])
              && @class_exists('WPMinify', false) && $GLOBALS['wp_minify'] instanceof WPMinify) {
                self::$obHandlers[] = array('handler' => 'wp_minify', 'method' => 'modify_buffer');
                $buffered = true;
            }
            elseif ('autoptimize_end_buffering' === $buffers[$xx]) {
                self::$obHandlers[] = array('handler' => false, 'method' => 'autoptimize_end_buffering', 'name' => 'Autoptimze');
                $buffered = true;
            }
        }
        if ($buffered) {
            // kill the buffers so the callback handlers are not called twice
            for ($xx = 0; $xx < $ct; $xx++) {
                ob_end_clean();
            }
        }
        return false;
    }

    protected static function doHttpsHandler($buffer)
    {
        global $wordpress_https;
        // recent versions use a module
        $module = false;
        list($class, $method) = explode('::', self::$sslHandler);
        if (strstr($class, 'Module_Parser')) {
            $module = $wordpress_https->getModule('Parser');
        }
        if (isset($wordpress_https) && isset($method) && method_exists(false === $module ? $wordpress_https : $module, $method)) {
            $out = false === $module ? $wordpress_https->{$method}($buffer) : $module->{$method}($buffer);// let WordPressHTTPS parse out theme developers src tag shananigans
            if (!empty($out)) {
                return $out;
            }
        }
        return false;
    }

    protected static function doObHandlers($buffer, $debugMsg)
    {
        foreach (self::$obHandlers as $handler) {
            if (false === $handler['handler']) { //function call
                $output = call_user_func($handler['method'], $buffer);
                if (!empty($output)) {
                    $buffer = $output;
                    if (!empty($handler['name'])) {
                        $buffer .= ('' === $debugMsg ? '' : str_replace(' on [', ' via ' . $handler['name'] . ' on [', $debugMsg));
                    }
                }
            }
            elseif (isset($GLOBALS[$handler['handler']])) {
                // this strips the cached on debug msg
                $output = $GLOBALS[$handler['handler']]->{$handler['method']}($buffer);
                if (!empty($output)) {
                    $buffer = $output;
                    if ('wp_minify' === $handler['handler']) {
                        $buffer .= ('' === $debugMsg ? '' : str_replace(' on [', ' via WPMinify on [', $debugMsg));
                    }
                }
            }
        }
        return $buffer;
    }

    public static function isJetPackMobile($skipSettings = true)
    {
        //jetpack checks settings on frontend
        return defined('JETPACK__VERSION') && false !== ($active = get_option('jetpack_active_modules')) && in_array(self::JP_MOBILE_MOD, $active) && ($skipSettings || '1' !== get_option('wp_mobile_disable'));
    }

    public static function isJetPackMobileSite()
    {
        return self::isJetPackMobile() && jetpack_check_mobile();
    }

    protected static function cacheUserContent()
    {
        $user = wp_get_current_user();
        $options = self::getOptions();
        $cacheme = array_intersect($options['roles'], (array)$user->roles);
        return !empty($cacheme) && apply_filters('gc_cache_user_content', false, $user);
    }

    protected static function getNoCacheHeaders()
    {
        $headers = array();
        foreach (wp_get_nocache_headers() as $k => $v) {
            if ('Last-Modified' === $k) {
                continue;
            }
            $headers[] = $k . ': ' . $v;
        }
        return empty($headers) ? false : $headers;
    }

    public static function getCacheGroups($opts)
    {
        $groups = array($opts['group']);
        if ($isJetPackMobile = isset($opts['jp_mobile_cache']) && $opts['jp_mobile_cache']) {
            $groups[] = $opts['group'] . '-jpmobile';
        }
        if (empty($opts['skip_ssl'])) {
            $groups[] = 'ssl@' . $opts['group'];
            if ($isJetPackMobile) {
                $groups[] = 'ssl@' . $opts['group'] . '-jpmobile';
            }
        }
        return $groups;
    }

    protected static function getHostString($config)
    {
        //for apache rules
        if (!self::isMultiSubDomain() || false === ($blogMap = GatorCache::getBlogMap())) {
            //regular site
            return str_replace('.', '\.', $config->get('host'));
        }
        //special case main site of a subdomain install
        $blogs = $blogMap->all();
        foreach ($blogs as $key =>$blog) {
            $blogs[$key] = str_replace('.', '\.', $config->get('host'));
        }
        return '(' . implode('|', $blogs) . ')';
    }
}

//Hooks
register_activation_hook(__FILE__, 'WpGatorCache::Activate');
register_deactivation_hook(__FILE__, 'WpGatorCache::Deactivate');
add_action('auth_cookie_valid', 'WpGatorCache::chkUser', 5, 2);
add_action('wp', 'WpGatorCache::initBuffer', 5);//after_setup_theme
add_action('init', 'WpGatorCache::loadTextDomain');
//admin settings
if (is_admin()) {
    add_action('admin_menu', 'WpGatorCache::addOptMenu', 8);
    add_action('admin_init', 'WpGatorCache::checkUpgrade');
    add_action('admin_enqueue_scripts', 'WpGatorCache::loadAdminJs', 111);
    add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'WpGatorCache::settingsLink');
    //installation ajax
    add_action('wp_ajax_gcinstall', 'WpGatorCache::doInstall');
    //settings
    add_action('wp_ajax_gci_gen', 'WpGatorCache::updateSettings');
    add_action('wp_ajax_gci_usr', 'WpGatorCache::updateSettings');
    add_action('wp_ajax_gci_cpt', 'WpGatorCache::updateSettings');
    add_action('wp_ajax_gci_dbg', 'WpGatorCache::updateSettings');
    add_action('wp_ajax_gci_del', 'WpGatorCache::updateSettings');
    add_action('wp_ajax_gci_del_oc', 'WpGatorCache::updateSettings');
    add_action('wp_ajax_gci_ref', 'WpGatorCache::updateSettings');
    add_action('wp_ajax_gci_dir', 'WpGatorCache::updateSettings');
    add_action('wp_ajax_gci_xex', 'WpGatorCache::updateSettings');
    add_action('wp_ajax_gci_mcd', 'WpGatorCache::updateSettings');
    add_action('wp_ajax_gci_crf', 'WpGatorCache::updateSettings');
    add_action('wp_ajax_gci_xrf', 'WpGatorCache::updateSettings');
    add_filter('whitelist_options', 'WpGatorCache::pingSetting');
    add_filter('redirect_post_location', 'WpGatorCache::savePostContext');
    add_filter('post_updated_messages', 'WpGatorCache::savePostMsg', 11);
}
add_action('transition_post_status', 'WpGatorCache::savePost', 11111, 3);
add_action('pre_post_update', 'WpGatorCache::prePostUpdate');
add_action('transition_comment_status', 'WpGatorCache::saveComment', 11, 3);
add_action('wp_insert_comment', 'WpGatorCache::postComment', 10, 2);
add_action('edit_comment', 'WpGatorCache::postComment');
add_filter('comment_cookie_lifetime', 'WpGatorCache::filterCookieLifetime', 11111);
add_filter('run_ngg_resource_manager', 'WpGatorCache::filterNggBuffer', 99999);
//by popular demand, a delete button on the toolbar
add_action('wp_before_admin_bar_render', 'WpGatorCache::addToolbarButton');
add_action('admin_bar_init', 'WpGatorCache::loadPurgeScripts', 11);
add_action('wp_ajax_gc_delete', 'WpGatorCache::xhrDelete');
add_action('wp_ajax_nopriv_gc_delete', 'WpGatorCache::xhrDelete');

/**
 * Allow plugins, such as autoptimize to delete cache
 */
if (!function_exists('wp_cache_clear_cache')) {
    function wp_cache_clear_cache($blogId = null)
    {
        if (!empty($blogId) && $blogId != get_current_blog_id()) {// @note when hooked blogId is empty string
            return;
        }
        // WpGatorCache::purgePath(false);
        WpGatorCache::flush();
    }
    // @note - the autoptimize plugin tries to call wp_cache_clear_cache before it's even loaded!!
    add_action('autoptimize_action_cachepurged', 'wp_cache_clear_cache');
}
