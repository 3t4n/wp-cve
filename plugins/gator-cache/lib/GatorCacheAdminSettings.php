<?php
class GatorCacheAdminSettings
{
    protected $options;
    protected $configPath;
    protected $isBbSupport;

    public function __construct($options, $configPath)
    {
        $this->options = $options;
        $this->configPath = $configPath;
    }

    public function update()
    {
        if (!current_user_can('manage_options') || !isset($_POST['action'])) {
            die();
        }
        $update = false;
        $cache = array('lifetime' => null, 'enabled' => null, 'oc_enabled' => null, 'skip_user' => null, 'debug' => null, 'skip_ssl' => null, 'cache_warm' => null, 'mobile' => null, 'sys_load' => null);
        switch ($_POST['action']) {
            case 'gci_crf':
            case 'gci_xrf':
                if (empty($_POST['rf_dir']) || '' === ($dir = trim(wp_kses(stripslashes($_POST['rf_dir']), 'strip')))
                  || '' === $dir = trim(preg_replace('~^/+|/+$~', '', $dir))) {
                    $error = __('Please enter a path name', 'gator-cache');
                    GatorCache::getJsonResponse()->setParam('error', $error)->send();
                }
                $validTypes = get_post_types(array('public'   => true, '_builtin' => false));
                if ('gci_crf' ===$_POST['action'] && (empty($_POST['rf_type']) || ('all' !== ($type = $_POST['rf_type']) && 'bbpress' !== $type && !isset($validTypes[$type])))) {
                    $error = __('Please enter a post type', 'gator-cache');
                    GatorCache::getJsonResponse()->setParam('error', $error)->send();
                }
                $dir = '/' . preg_replace('~\s+~', '-', $dir) . '/';
                $dirKey = false;
                foreach ($this->options['refresh_paths'] as $typeKey => $paths) {
                    if (false !== ($dirKey = array_search($dir, $paths))) {
                        break;
                    }
                }
                if ('gci_xrf' === $_POST['action']) {
                    if (false !== $dirKey) {
                        unset($this->options['refresh_paths'][$typeKey][$dirKey]);
                        if ('all' !== $typeKey && empty($this->options['refresh_paths'][$typeKey])) {
                            unset($this->options['refresh_paths'][$typeKey]);
                        }
                    }
                } else {
                    if (false !== $dirKey) {
                        $error = __('This path is already added to refresh rules', 'gator-cache');
                        GatorCache::getJsonResponse()->setParam('error', $error)->send();
                    }
                    if (isset($this->options['refresh_paths'][$type])) {
                        $this->options['refresh_paths'][$type][] = $dir;
                    } else {
                        $this->options['refresh_paths'][$type] = array($dir);
                    }
                    if ('bbpress' === $type && !isset($this->options['app_support']['bbpress'])) {
                        $this->options['app_support']['bbpress'] = $this->getBbPressSupport();
                    }
                }
                $update = true;
            break;
            case 'gci_mcd':
                if (!$this->moveCache()) {
                    $msg = sprintf(__('Error [%d]: Could not move your cache directory', 'gator-cache'), 111);
                    GatorCache::getJsonResponse()->setParam('error', $msg)->send();
                }
                GatorCache::getJsonResponse()->send(true);
            break;
            case 'gci_dir':
            case 'gci_xex':
                if (empty($_POST['ex_dir']) || '' === ($dir = trim(wp_kses(stripslashes($_POST['ex_dir']), 'strip')))
                  || ('/' !== ($dir = trim($dir)) && '' === ($dir = preg_replace('~^/+|/+$~', '', $dir)))) {
                    $error = __('Please enter a path name', 'gator-cache');
                    GatorCache::getJsonResponse()->setParam('error', $error)->send();
                }
                //if(!filter_var(get_option('siteurl') . ($dir = '/' . preg_replace('~\s+~', '-', $dir) . '/'), FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)){}
                $key = array_search($dir = ('/' === $dir) ? '/' : '/' . preg_replace('~\s+~', '-', $dir) . '/', $this->options['exclude_paths']);
                if ('gci_xex' === $_POST['action']) {
                    if (false !== $key) {
                        unset($this->options['exclude_paths'][$key]);
                    }
                } else {
                    if (false !== $key) {
                        $error = __('This path is already excluded', 'gator-cache');
                        GatorCache::getJsonResponse()->setParam('error', $error)->send();
                    }
                    $this->options['exclude_paths'][] = $dir;
                }
                $update = true;
            break;
            case 'gci_del':
                GatorCache::flushCache($this->configPath);
                GatorCache::getJsonResponse()->setParam('msg', __('Cache successfully purged', 'gator-cache'))->send(true);
            break;
            case 'gci_del_oc':
                GatorCache::getObjectCache()->flush();
                GatorCache::getJsonResponse()->setParam('msg', __('Object Cache successfully purged', 'gator-cache'))->send(true);
            break;
            case 'gci_ref':
                $refresh = array(
                    'home'    => isset($_POST['rf_home']) && '1' === $_POST['rf_home'],
                    'archive' => isset($_POST['rf_archive']) && '1' === $_POST['rf_archive'],
                    'all'     => isset($_POST['rf_all']) && '1' === $_POST['rf_all']
                );
                $mobile = $mobileCache = array();
                foreach ($this->options['mobile'] as $key => $val) {
                    if ($mobile[$key] = isset($_POST['mobile_cache'][$key]) && '1' === $_POST['mobile_cache'][$key]) {
                        $mobileCache[] = $key;
                    }
                }
                if (empty($_POST['sys_load']) || '' === trim($_POST['sys_load']) || !ctype_digit($sys_load = $_POST['sys_load'])) {
                    $sys_load = 0;
                }
                $skip_ssl = !isset($_POST['cache_ssl']) || '1' !== $_POST['cache_ssl'];
                $enable_hooks = isset($_POST['enable_hooks']) && '1' === $_POST['enable_hooks'];
                $jp_mobile_cache = isset($_POST['jp_mobile_cache']) && '1' === $_POST['jp_mobile_cache'];
                $cache_warm = isset($_POST['cache_warm']) && '1' === $_POST['cache_warm'];
                $skip_feeds = !isset($_POST['cache_feeds']) || '1' !== $_POST['cache_feeds'];
                if ($refresh !== $this->options['refresh'] || $skip_ssl !== $this->options['skip_ssl'] || $enable_hooks !== $this->options['enable_hooks']
                  || $jp_mobile_cache !== $this->options['jp_mobile_cache'] || $cache_warm !== $this->options['cache_warm'] || $skip_feeds !== $this->options['skip_feeds']
                  || $mobile != $this->options['mobile'] || $sys_load !== $this->options['sys_load']) {
                    $update = true;
                    $this->options['refresh'] = $refresh;
                    $this->options['enable_hooks'] = $enable_hooks;
                    $this->options['skip_ssl'] = $cache['skip_ssl'] = $skip_ssl;
                    $this->options['jp_mobile_cache'] = $cache['jp_mobile_cache'] = $jp_mobile_cache;
                    $this->options['cache_warm'] = $cache['cache_warm'] = $cache_warm;
                    $this->options['skip_feeds'] = $skip_feeds;
                    $this->options['mobile'] = $mobile;
                    $cache['mobile'] = implode(':', $mobileCache);
                    $this->options['sys_load'] = $cache['sys_load'] = $sys_load;
                }
            break;
            case 'gci_gen':
                $enabled = isset($_POST['enabled']) && '1' === $_POST['enabled'];
                $ocEnabled = false;// isset($_POST['oc_enabled']) && '1' === $_POST['oc_enabled'];
                if (!isset($_POST['lifetime_val']) || !ctype_digit($value = $_POST['lifetime_val'])) {
                    $value = '0';
                }
                $validUnits = array('hr' => false, 'day' => false, 'week' => false, 'month' => false);
                if (!isset($_POST['lifetime_unit']) || !ctype_alpha($unit = $_POST['lifetime_unit']) || !isset($validUnits[$unit])) {
                    $unit = 'hr';
                }
                if ($value !== $this->options['lifetime']['value'] || $unit !== $this->options['lifetime']['unit']) {
                    $update = true;
                    $mult = 'hr' === $unit ? 3600 : ('day' === $unit ? 86400: ('week' === $unit ? 604800 : 2629800));
                    $cache['lifetime'] = '0' === $value ? 0 : $mult * $value;
                    $this->options['lifetime'] = array('value' => $value, 'unit' => $unit, 'sec' => $cache['lifetime']);
                }
                if ($enabled !== $this->options['enabled']) {
                    $update = true;
                    $this->options['enabled'] = $cache['enabled'] = $enabled;
                }
                if ($ocEnabled !== $this->options['oc_enabled']) {
                    $update = true;
                    $this->options['oc_enabled'] = $cache['oc_enabled'] = $ocEnabled;
                }
            break;
            case 'gci_usr':
                if (!isset($_POST['gci_roles'])) {
                    $error = __('Roles not specified', 'gator-cache');
                    GatorCache::getJsonResponse()->setParam('error', $error)->send();
                }
                $roles = '' === $_POST['gci_roles'] ? array() : explode(',', $_POST['gci_roles']);
                global $wp_roles;
                if (!isset($wp_roles)) {
                    $wp_roles = new WP_Roles();
                }
                $validRoles = $wp_roles->get_names();
                foreach ($roles as $key => $role) {
                    //for php 5.2 compat array filter not used here
                    if (!isset($validRoles[$role])) {
                        unset($roles[$key]);
                    }
                }
                $onlyUser = isset($_POST['only_user']) && '1' === $_POST['only_user'];
                var_dump($onlyUser, $_POST);
                if ($roles !== $this->options['roles'] || $onlyUser !== $this->options['only_user']) {
                    $update = true;
                    $this->options['roles'] = $roles;
                    $cache['skip_user'] = !empty($roles);
                    $this->options['only_user'] = $cache['only_user'] = $cache['skip_user'] && $onlyUser;
                }
            break;
            case 'gci_cpt':
                if (!isset($_POST['post_types'])) {
                    $error = __('Post Types not specified', 'gator-cache');
                    GatorCache::getJsonResponse()->setParam('error', $error)->send();
                }
                $types = '' === $_POST['post_types'] ? array() : explode(',', $_POST['post_types']);
                $validTypes = get_post_types(array('public'   => true, '_builtin' => false));
                foreach ($types as $key => $type) {
                    //for php 5.2 compat array filter not used here
                    if ('bbpress' === $type) {
                        if (false !== ($app_support = $this->getBbPressSupport())) {
                            //!isset($this->options['app_support']['bbpress'])
                            $this->options['app_support']['bbpress'] = $app_support;
                            $update = true;
                        }
                    } elseif (!isset($validTypes[$type])) {
                        unset($types[$key]);
                    }
                }
                if ($types !== $this->options['post_types']) {
                    $update = true;
                    $this->options['post_types'] = $types;
                }
            break;
            case 'gci_dbg':
                $debug = isset($_POST['debug']) && '1' === $_POST['debug'];
                if ($debug !== $this->options['debug']) {
                    $update = true;
                    $this->options['debug'] = $cache['debug'] = $debug;
                }
            break;
            default:
                $error = __('Invalid Action', 'gator-cache');
                GatorCache::getJsonResponse()->setParam('error', $error)->send();
            break;
        }
        
        if (!$update) {
            die('{"success":"0","error":"Settings were not changed"}');
        }
        $wpConfig = GatorCache::getOptions(WpGatorCache::PREFIX . '_opts');
        $wpConfig->write($this->options);//update with modified options
        //some options have to be saved to file
        $cache = array_filter($cache, 'WpGatorCache::filterCacheUpdate');//php 5.2 compat
        if (!empty($cache)) {
            $config = GatorCache::getConfig($this->configPath);
            foreach ($cache as $k => $v) {
                $config->set($k, $v);
            }
            $config->write();
        }
        if ('gci_dir' === $_POST['action']) {
            //include payload for added custom dirs
            GatorCache::getJsonResponse()->setParam('xdir', $dir)->send(true);
        }
        if ('gci_crf' === $_POST['action']) {
            //include payload for added custom dirs
            GatorCache::getJsonResponse()->setParam('xdir', $dir)->setParam('xtype', $type)->send(true);
        }
        GatorCache::getJsonResponse()->send(true);
    }

    protected static function moveCache($docRoot = true)
    {
        $config = GatorCache::getConfig($this->configPath);
        if (!is_dir($cacheDir = ABSPATH . 'gator_cache')) {
            if (!@rename($config->get('cache_dir'), $cacheDir)) {
                return false;
            }
        } elseif (!is_writable($cacheDir)) {
            return false;
        }
        return $config->save('cache_dir', $cacheDir);
    }

    protected function getBbPressSupport()
    {
        if (isset($this->isBbSupport)) {
            return $this->isBbSupport;
        }
        if (is_plugin_active('bbpress/bbpress.php')) {
            $app_support = array();
            $app_support[bbp_get_reply_post_type()] = true;
            $app_support[bbp_get_topic_post_type()] = true;
            $app_support[bbp_get_forum_post_type()] = true;
            return $this->isBbSupport = $app_support;
        }
        return $this->isBbSupport = false;
    }
}
