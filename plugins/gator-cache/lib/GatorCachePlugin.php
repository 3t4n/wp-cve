<?php
class GatorCachePlugin
{
    protected $options;
    protected $configPath;
    protected $basePath;

    public function __construct($options, $configPath, $basePath)
    {
        $this->options = $options;
        $this->configPath = $configPath;
        $this->basePath = $basePath;
    }

    public function activate()
    {
        if (!$this->options['installed']) {
            //install will handle this
            return;
        }
        //check config and advance cache
        if (!$this->saveWpConfig() || !$this->copyAdvCache()) {
            $wpConfig = GatorCache::getOptions(WpGatorCache::PREFIX . '_opts');
            $wpConfig->set('installed', false);
            $wpConfig->set('enabled', false);
            $wpConfig->write();
        }
    }

    public function deactivate()
    {
        //purge the cache
        GatorCache::purgeCache($this->configPath);
        //update wp-cache setting in wp-config.php
        if ($this->saveWpConfig(false)) {
            //remove the advanced cache file
            @unlink(WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'advanced-cache.php');
        }
    }

    public function checkUpgrade()
    {
        if (defined('DOING_AJAX') && DOING_AJAX) {
            return;
        }

        if (!$this->options['installed']) {
            return;
        }
        //1.0 > 1.11 store the version and move the config file
        if (WpGatorCache::VERSION !== $this->options['version']) {
            $version = (float)$this->options['version'];
            $wpConfig = GatorCache::getOptions(WpGatorCache::PREFIX . '_opts');
            if (1.56 > $version) {
                $this->copyAdvCache();
                $config = GatorCache::getConfig($this->configPath);
                $config->set('jp_mobile', WpGatorCache::isJetPackMobile(false));
                $config->set('jp_mobile_cache', false);
                $config->write();
            }
            if (1.57 > $version) {
                $this->copyAdvCache();
                $this->setContentTypes(GatorCache::getConfig($this->configPath));
            }
            if (1 === version_compare('2.0.8', $this->options['version'])) {
                //latest version should copy this
                $this->copyAdvCache();
            }
            if (1 === version_compare('2.0.9', $this->options['version'])) {
                //initialize cache warm variable
                $config = GatorCache::getConfig($this->configPath);
                if (!$config->has('cache_warm')) {
                    $config->save('cache_warm', false);
                }
            }
            $config = GatorCache::getConfig($this->configPath);
            if (empty($this->options['roles']) && $config->get('skip_user')) {//
                $config->save('skip_user', false);
            }
            //upgrades done or nothing to upgrade, update the version
            $wpConfig->set('version', $this->options['version'] = WpGatorCache::VERSION);
            $wpConfig->write();
        }
        /*if(is_multisite() && !is_subdomain_install() && is_main_site(get_current_blog_id()) && !is_plugin_active_for_network($this->basePath, 'gator-cache.php')){
            add_filter('wpmu_signup_blog_notification', 'WpGatorCache::newMpmuSite', 10, 2);
        }*/
    }

    public function verifyInstall($defaults)
    {
        //check install flag
        if (!$this->options['installed']) {
            return false;
        }
        //config file missing or corrupted
        if (!is_file($this->configPath) || false === ($config = GatorCache::getConfig($this->configPath))) {
            $msg = __('Your Gator Cache configuration file is missing or corrupted.', 'gator-cache');
            GatorCache::getNotices()->add($msg, '107');
            $this->disableCache(false);//requires reinstall
            return false;
        }
        //cache directory is missing or set to the default
        if ('/tmp' === ($cacheDir = $config->get('cache_dir')) || !is_dir($cacheDir)) {
            $msg = __('Your Gator Cache directory is missing or no longer set.', 'gator-cache');
            GatorCache::getNotices()->add($msg, '108');
            $this->disableCache();//requires reinstall
            return false;
        }
        $isDir = true;
       /* if ('/tmp' === ($objCacheDir = $config->get('oc_cache_dir')) || empty($objCacheDir) || !($isDir = @is_dir($objCacheDir))) {
            $objCacheDir = str_replace('/gator_cache', '/gator_cache_oc', $cacheDir);
            if (!$isDir) {
                @mkdir($objCacheDir, 0755);
                //put htaccess here to prevent direct access
                @file_put_contents($objCacheDir . DIRECTORY_SEPARATOR . '.htaccess', "Order Deny,Allow\nDeny from all\n");
            }
            $config->save('oc_cache_dir', $objCacheDir);
            GatorCache::getOptions(WpGatorCache::PREFIX . '_opts', $this->defaults)->save('oc_cache_dir', $objCacheDir);
        }*/
        //for apache, make sure htaccess protects the cache dir
        if (!@file_exists($htaccess = $cacheDir . '/.htaccess')) {
            @file_put_contents($htaccess, "Order Deny,Allow\nDeny from all\nAllow from env=redirect_gc_green\n") ;
        }
        if (!@file_exists($htaccess = $objCacheDir . '/.htaccess')) {
            @file_put_contents($htaccess, "Order Deny,Allow\nDeny from all\n") ;
        }
        //check wp cache is set and the right adv cache is present
        if (!(defined('WP_CACHE') && WP_CACHE) || !$this->copyAdvCache(false)) {
            //attempt to repair
            if (!($wpCache = $this->saveWpConfig()) || !$this->copyAdvCache()) {
                if (!$wpCache) {
                    $msg = __('Your WordPress configuration file could not be updated.', 'gator-cache');
                    $code = '109';
                } else {
                    $msg = __('Your advanced cache file is missing or corrupted.', 'gator-cache');
                    $code = '110';
                }
                GatorCache::getNotices()->add($msg, $code);
                $this->disableCache();//requires reinstall
                return false;
            }
        }
        //check for the host
        if (!$config->has('host')) {
            if (false === ($url = parse_url(get_option('siteurl')))) {
                $msg = __('Could not reliably set your host name.', 'gator-cache');
                GatorCache::getNotices()->add($msg, '111');
                $this->disableCache();//requires reinstall
                return false;
            }
            $config->save('host', $url['host']);
        }
        $this->setSecureHost();
        global $wp_rewrite;//make sure these match
        if ($config->get('dir_slash') != ($dirSlash = (isset($wp_rewrite->use_trailing_slashes) && $wp_rewrite->use_trailing_slashes))) {
            $config->save('dir_slash', $dirSlash);
        }
        //url checks
        $url = parse_url(get_option('siteurl'));
        //multisite
        if (is_multisite()) {
            if (!$this->checkBlogConfig()) {
                //no file and couldn't create
                $msg = __('Your multisite blog configuration file could not be created.', 'gator-cache');
                GatorCache::getNotices()->add($msg, '112');
                return false;
            }
            //verify the host and reset if not matching
            $host = $this->getMultiHost($url);
            if ($host !== GatorCache::getBlogMap()->getHost($blogId = get_current_blog_id())) {
                GatorCache::getBlogMap()->saveBlogId($host, $blogId);
            }
            //refresh sub blog exclusions if applicable
            /*if(!is_subdomain_install() && is_main_site($blogId)){
                //@note more effecient to query db that to use wp_get_sites (wp >= 3.7)
                global $wpdb;
                if(null === ($sites = $wpdb->get_results('select * from ' . $wpdb->prefix . 'blogs where site_id = ' . $blogId . ' and blog_id != ' . $blogId . ' order by blog_id limit 0, 10000', 'ARRAY_A'))){
                    $sites = array();
                }
                $paths = array();
                foreach($sites as $site){
                    if('0' === $site['deleted'] && '' !== $site['path'] && '/' !== $site['path']){
                        $paths[$site['blog_id']] = $site['path'];
                    }
                }
                if(!empty($paths)){
                    if($paths !== $options['multisite_paths']){
                        GatorCache::getOptions()->save('multisite_paths', $paths);
                    }
                }
                elseif(false !== $options['multisite_paths']){
                    GatorCache::getOptions()->save('multisite_paths', false);
                }
            }*/
        }
        //@note an upgrade can move the cache dir
        if ($config->get('group') !== $url['host']) {
            $config->save('group', $url['host']);
        }
        if (!empty($url['path']) && '/' !== $url['path']) {
            if ('/' === substr($url['path'], -1)) {
                $url['path'] = rtrim($url['path'], '/');
            }
            if ($url['path'] !== $config->get('path')) {
                $config->save('path', $url['path']);
            }
        } elseif (false !== $config->get('path')) {
            $config->remove('path');
            $config->write();
        }
        if (WpGatorCache::isJetPackMobile(false) && !$config->get('jp_moblie')) {
            $config->save('jp_mobile', true);
        } elseif ($config->get('jp_moblie')) {
            $config->save('jp_mobile', false);
        }
        $this->setContentTypes($config);
        $config = GatorCache::getConfig($this->configPath);
        if (empty($this->options['roles']) && $config->get('skip_user')) {
            $config->save('skip_user', false);
        }
        return true;
    }

    public function install()
    {
        //install create cache dir
        if (is_dir($path = $this->getInitDir(false)) || is_dir($path = $this->getInitDir(true))) {
            //cache dir already exists
            if (!@is_writable($path)) {
                $error = sprintf(__('Error [%d]: Cache Directory [%s] is not writable, please change permissions.', 'gator-cache'), 101, $path);
                GatorCache::getJsonResponse()->setParam('error', $error)->setParam('code', '101')->send();
            }
        } else {
            //create
            $path = $this->getInitDir(isset($_POST['ndoc_root']) && '1' === $_POST['ndoc_root']);
            if (false === @mkdir($path, 0755) || !@is_writable($path)) {
                //maybe a reinstall in doc root
                $error = sprintf(__('Error [%d]: Cache Directory could not be created %s', 'gator-cache'), 100, $path);
                GatorCache::getJsonResponse()->setParam('error', $error)->setParam('code', '100')->send();
            }
            // make the object cache
            // @mkdir($ocPath = str_replace('/gator_cache', '/gator_cache_oc', $path), 0755);
            //put htaccess here to prevent direct access
            @file_put_contents($path . DIRECTORY_SEPARATOR . '.htaccess', "Order Deny,Allow\nDeny from all\nAllow from env=redirect_gc_green\n");
            // @file_put_contents($ocPath . DIRECTORY_SEPARATOR . '.htaccess', "Order Deny,Allow\nDeny from all\n");
        }
        //cache dir created or exists - get the group for subdir support or people that put blogs in the doc root
        if (false === ($url = parse_url($siteurl = get_option('siteurl')))) {
            $error = sprintf(__('Error [%d]: Could not parse site url setting [%s], please check WordPress configuration.', 'gator-cache'), 105, $siteurl);
            GatorCache::getJsonResponse()->setParam('error', $error)->send();
        }
        //initial config
        if (!$this->copyConfigFile()) {
            $error = sprintf(__('Error [%d]: Could not copy config file to your WordPress directory [%s], please check permissions.', 'gator-cache'), 106, ABSPATH);
            GatorCache::getJsonResponse()->setParam('error', $error)->send();
        }
        //multisite support
        if (is_multisite() && !$this->checkBlogConfig()) {
            $error = sprintf(__('Error [%d]: Could not copy multisite config file to your WordPress directory [%s], please check permissions.', 'gator-cache'), 112, ABSPATH);
            GatorCache::getJsonResponse()->setParam('error', $error)->send();
        }
        if (!$this->saveCachePath($path, $url)) {
            //1.42 save host
            $error = sprintf(__('Error [%d]: Could not write to config file [%s], please check permissions.', 'gator-cache'), 102, $this->configPath);
            GatorCache::getJsonResponse()->setParam('error', $error)->send();
        }
        //intial setup done, copy advance cache and write to wp config
        if (!$this->copyAdvCache()) {
            $error = sprintf(__('Error [%d]: could not copy advance cache php file, please copy manually', 'gator-cache'), 103);
            GatorCache::getJsonResponse()->setParam('error', $error)->send();
        }
        if (!$this->saveWpConfig()) {
            $error = sprintf(__('Error [%d]: Could not write to your wordpress config file, please change permissions or manually insert WP_CACHE', 'gator-cache'), 104);
            GatorCache::getJsonResponse()->setParam('error', $error)->send();
        }
        //Installation complete
        $wpConfig = GatorCache::getOptions(WpGatorCache::PREFIX . '_opts');
        $wpConfig->set('installed', true);
        $wpConfig->set('version', WpGatorCache::VERSION);
        if ('open' === get_option('default_ping_status')) {
            $wpConfig->set('pingback', true);
            GatorCache::getConfig($this->configPath)->save('pingback', get_bloginfo('pingback_url'));
        }
        $wpConfig->write();
        $msg = __('Gator Cache Successfully Installed', 'gator-cache');
        GatorCache::getJsonResponse()->setParam('msg', $msg)->send(true);
    }

    public function getInitDir($inRoot = false, $show = false)
    {
        if (defined('ABSPATH')) {
            $dir = ABSPATH;
        }
        elseif (null === ($dir = GatorCache::getRequest()->getServer('DOCUMENT_ROOT'))
          && null === ($dir = GatorCache::getRequest()->getServer('PWD'))) {
            $dir = realpath('./../../');
        }
        return ($inRoot ? $dir : dirname($dir)) . DIRECTORY_SEPARATOR . 'gator_cache';
    }

    protected function copyAdvCache($copy = true)
    {
        // @depricated get rid of object cache for now, its caches tons of transients, not necessary any longer since it's not installed anymore
        /*if (is_file($cacheFile = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'object-cache.php')) {
            @unlink($cacheFile);
        }*/
        // @depricated allow the object cache file to be edited, just check existance
        /*$sourceFile = $this->basePath . 'lib' . DIRECTORY_SEPARATOR . 'object-cache.php';
        if (!is_file($cacheFile = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'object-cache.php')) {
            @copy($sourceFile, $cacheFile);
        }*/
        $sourceFile = $this->basePath . 'lib' . DIRECTORY_SEPARATOR . 'advanced-cache.php';
        if (is_file($cacheFile = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'advanced-cache.php')
          && md5_file($cacheFile) === md5_file($sourceFile)) {
            return true;
        }
        return $copy ? (false !== @copy($sourceFile, $cacheFile)) : false;
    }

    protected function saveWpConfig($wp_cache = true)
    {
        if (defined('WP_CACHE') && $wp_cache === WP_CACHE) {
            return true;
        }
        if (!is_file($file = ABSPATH . 'wp-config.php')) {
            $file = dirname(ABSPATH) . DIRECTORY_SEPARATOR . 'wp-config.php';
        }
        //backup the config just in case
        if ($wp_cache) {
            @copy($file, str_replace('wp-config.php', 'wp-config-bu.php'));
        }
        $fh = @fopen($file, 'r+');
        if (false === $fh) {
            return false;
        }
        $lines = array();
        $pos = 0;
        $xx = 0;
        while (false !== ($buffer = fgets($fh))) {
            if (!preg_match('~^define\s*\(\s*("|\')WP_CACHE\\1~', trim($buffer))) {
                $lines[] = $buffer;
                if (preg_match('~^define\s*\(\s*("|\')WP_DEBUG\\1~', trim($buffer))) {
                    $pos = $xx;
                }
                $xx++;
            }
        }
        fclose($fh);
        $pos++;
        $lines = array_merge(
            array_slice($lines, 0, $pos), array('define(\'WP_CACHE\', '. ($wp_cache ? 'true' : 'false') .');' . PHP_EOL), array_slice($lines, $pos)
        );
        return false !== @file_put_contents($file, $lines);
    }

    protected function saveCachePath($path, $url)
    {
        if (false === ($config = GatorCache::getConfig($this->configPath, true))) {
            return false;
        }
        global $wp_rewrite;
        //$group = str_replace('.', '-', $url['host']) . (empty($url['path']) || '/' === $url['path'] ? '' : str_replace('/', '-', $url['path']));
        //for easier http rules $group = $url['host']
        $config->set('cache_dir', $path);
        $config->set('group', $url['host']);
        $config->set('host', $url['host']);
        if (false !== ($secureHost = $this->setSecureHost(false))) {
            $config->set('secure_host', $secureHost);
        }
        $config->set('dir_slash', isset($wp_rewrite->use_trailing_slashes) && $wp_rewrite->use_trailing_slashes);
        if (is_multisite()) {
            GatorCache::getBlogMap()->saveBlogId($this->getMultiHost($url), get_current_blog_id());
        }
        return $config->write();
    }

    protected function disableCache($all = true)
    {
        GatorCache::getOptions(WpGatorCache::PREFIX . '_opts')->save('enabled', false);
        if ($all) {
            GatorCache::getConfig($this->configPath)->save('enabled', false);
        }
    }

    protected function setSecureHost($save = true)
    {
        //wp https comptibility if the secure host does not match the wp host
        if (false !== ($secureUrl = get_option('wordpress-https_ssl_host')) && is_plugin_active('wordpress-https/wordpress-https.php')) {
            $config = GatorCache::getConfig($this->configPath);
            if (false !== ($url = parse_url($secureUrl)) && $config->get('host') !== $url['host']) {
                if (!$save) {
                    return $url['host'];
                }
                $config->save('secure_host', $url['host']);
            }
        }
        return false;
    }

    protected function copyConfigFile()
    {
        $source = $this->basePath . 'lib' . DIRECTORY_SEPARATOR . 'config.ini.php';
        if (!is_file($this->configPath)) {
            //|| md5_file($source) !==  md5_file($this->configPath)
            if (false === @copy($source,  $this->configPath)) {
                return false;
            }
        }
        return true;
    }

    protected function setContentTypes($config)
    {
        if ($config->get('content_type') !== ($contentType = 'Content-Type: ' . get_option('html_type') . '; charset=' . ($charset = get_option('blog_charset')))) {
            $config->save('content_type', $contentType);
        }
        if ($config->get('rss2_type') !== ($contentType = 'Content-Type: text/xml; charset=' . $charset)) {
            $config->save('rss2_type', $contentType);
        }
        if ($config->get('atom_type') !== ($contentType = 'Content-Type: application/atom+xml; charset=' . $charset)) {
            $config->save('atom_type', $contentType);
        }
        if ($config->get('rdf_type') !== ($contentType = 'Content-Type: application/rdf+xml; charset=' . $charset)) {
            $config->save('rdf_type', $contentType);
        }
        if ($config->get('default_feed') !== ($defaultFeed = get_default_feed())) {
            $config->save('default_feed', $defaultFeed);
        }
    }

    protected function checkBlogConfig()
    {
        return (false !== GatorCache::getBlogMap()) || @touch(GatorBlogMap::getPath());
    }

    protected function getMultiHost($url)
    {
        $host = $url['host'];
        if (!is_subdomain_install() && !empty($url['path']) && '/' !== $url['path']) {
            $host .= $url['path'];
        }
        return $host;
    }
}
