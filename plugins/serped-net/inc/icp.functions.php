<?php
if ( !defined( 'ABSPATH' ) ) exit; 
$frame_path = "https://members.serped.net";
//$frame_path = "http://members.srpdtest.net";
//$frame_path = "http://serpedlocal.com";
//$frame_path = "http://srpdtest:vaLEJQE0zPkV15g7iTzKnGz7YdP08W@members.srpdtest.net";

if (!function_exists('srpd_get_error_msg')) {
    function srpd_get_error_msg($content) {
        $data = explode("\n", $content);
        $i = 0;
        foreach ($data as $entry) {
            if (strpos($entry, "R=301") > -1)
                $tmp = 301;
            if (strpos($entry, "R,L") > -1)
                $tmp = 302;
            if (strpos($entry, "R=400") > -1)
                $tmp = 400;
            if (strpos($entry, "R=401") > -1)
                $tmp = 401;
            if (strpos($entry, "R=403") > -1)
                $tmp = 403;
            if (strpos($entry, "R=404") > -1)
                $tmp = 404;
            if (strpos($entry, "R=408") > -1)
                $tmp = 408;
            if (strpos($entry, "R=410") > -1)
                $tmp = 410;
            if (strpos($entry, "R=412") > -1)
                $tmp = 412;
            if (strpos($entry, "R=500") > -1)
                $tmp = 500;
            if (strpos($entry, "R=503") > -1)
                $tmp = 503;
        }
        return $tmp;
    }
}

if (!function_exists('srpd_get_htaccess_content')) {
    function srpd_get_htaccess_content() {
        $file_path = $_SERVER["DOCUMENT_ROOT"] . '/.htaccess';
        $fh = fopen($file_path, 'r');
        $length = filesize($file_path);
        if ($length == 0)
            $length = 1;
        $content = fread($fh, $length);
        return $content;
    }
}

if (!function_exists('srpd_get_sa_form')) {
    function srpd_get_sa_form($filename) {
        $file_path = srpd_plugin_path() . '/inc/sa_forms/' . $filename;
        $fh = fopen($file_path, 'r');
        $length = filesize($file_path);
        if ($length == 0)
            $length = 1;
        $content = fread($fh, $length);
        return $content;
    }
}

if (!function_exists('srpd_update_htaccess')) {
    function srpd_update_htaccess($cmd) {
        $file_path = $_SERVER["DOCUMENT_ROOT"] . '/.htaccess';
        $full_content = srpd_get_htaccess_content();
        if ($cmd == 'disable_folder_browsing') {
            if (strpos($full_content, 'Options -Indexes') === false) {
                $new_content = "Options -Indexes \n\n" . $full_content;
                //save into htaccess
                rename($_SERVER["DOCUMENT_ROOT"] . '/.htaccess', $_SERVER["DOCUMENT_ROOT"] . '/.htaccessOLD');
                $fh = fopen($file_path, 'w');
                fwrite($fh, $new_content);
                fclose($fh);
            }
        }
    }
}

if (!function_exists('srpd_get_tracking_code')) {
    function srpd_get_tracking_code() {
        $tracking_code = get_option('icp_tracking_code');
        if ($tracking_code == false)
            return '';
        else
            return $tracking_code;
    }
}
if (!function_exists('srpd_get_tracking_domain')) {
    function srpd_get_tracking_domain() {
        $tracking_domain = get_option('icp_tracking_domain');
        if ($tracking_domain == false)
            return '';
        else
            return $tracking_domain;
    }
}

if (!function_exists('srpd_update_tracking_code')) {
    function srpd_update_tracking_code() {      
        $respoce = wp_remote_get("https://members.serped.net/plugin/plugin.site.auditor.php?plugin_key=" . srpd_get_plugin_key() . '&siteId=' . $_POST['icp_tracking_code'] . "&webanalytics");
        $respoce = wp_remote_retrieve_body($respoce);
        $respoce = json_decode($respoce, true);
        if($respoce['status'] == true && isset($respoce['domain']) && !empty($respoce['domain'])){
            $domain = $respoce['domain'];
            update_option('icp_tracking_domain', $domain);
            $tracking_code = sanitize_text_field($_POST['icp_tracking_code']);  
            $code = str_replace("\'", "'", str_replace('\"', '"', $tracking_code));
            update_option('icp_tracking_code', $code);
            return '1';
        }
        return '0';   
    }
}

if (!function_exists('srpd_get_plugins')) {
    function srpd_get_plugins() {
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
        $plugins = get_plugins();
        $active = get_option('active_plugins', array());
        // Delete the transient so wp_update_plugins can get fresh data
        if (function_exists('get_site_transient'))
            delete_site_transient('update_plugins');
        else
            delete_transient('update_plugins');
        wp_update_plugins();
        if (function_exists('get_site_transient') && $transient = get_site_transient('update_plugins'))
            $current = $transient;
        elseif ($transient = get_transient('update_plugins'))
            $current = $transient;
        else
            $current = get_option('update_plugins');
        $manage_wp_updates = apply_filters('mwp_premium_update_notification', array());
        foreach ((array) $plugins as $plugin_file => $plugin) {
            if (is_plugin_active($plugin_file))
                $plugins[$plugin_file]['active'] = true;
            else
                $plugins[$plugin_file]['active'] = false;
            $manage_wp_plugin_update = false;
            foreach ($manage_wp_updates as $manage_wp_update) {
                if (!empty($manage_wp_update['Name']) && $plugin['Name'] == $manage_wp_update['Name'])
                    $manage_wp_plugin_update = $manage_wp_update;
            }
            if ($manage_wp_plugin_update) {
                $plugins[$plugin_file]['latest_version'] = $manage_wp_plugin_update['new_version'];
                $plugins[$plugin_file]['latest_package'] = '';
            } else if (isset($current->response[$plugin_file])) {
                $plugins[$plugin_file]['latest_version'] = $current->response[$plugin_file]->new_version;
                $plugins[$plugin_file]['latest_package'] = $current->response[$plugin_file]->package;
                $plugins[$plugin_file]['slug'] = $current->response[$plugin_file]->slug;
            } else {
                $plugins[$plugin_file]['latest_version'] = $plugin['Version'];
                $plugins[$plugin_file]['latest_package'] = '';
            }
        }
        return $plugins;
    }
}

if (!function_exists('srpd_activate_plugin')) {
    function srpd_activate_plugin($plugin) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
        $result = activate_plugin($plugin);
        if (is_wp_error($result))
            return array('status' => 'failed', 'msg' => $result);
        return array('status' => 'success');
    }
}

if (!function_exists('srpd_deactivate_plugin')) {
    function srpd_deactivate_plugin($plugin) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
        if (is_plugin_active($plugin))
            deactivate_plugins($plugin);
        return array('status' => 'success');
    }
}

if (!function_exists('srpd_install_plugin')) {
    function srpd_install_plugin($download_link) {
        if (defined('DISALLOW_FILE_MODS') && DISALLOW_FILE_MODS)
            return array('status' => 'failed', 'msg' => 'File modification is disabled with the DISALLOW_FILE_MODS constant');
        include_once ABSPATH . 'wp-admin/includes/admin.php';
        include_once ABSPATH . 'wp-admin/includes/upgrade.php';
        include_once ABSPATH . 'wp-includes/update.php';
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        require_once srpd_plugin_path() . '/inc/icp.classes.php';
        $skin = new SRPD_Plugin_Upgrader_Skin();
        $upgrader = new Plugin_Upgrader($skin);
        $result = $upgrader->install($download_link);
        if (is_wp_error($result))
            return array('status' => 'failed', 'msg' => $result);
        else if (!$result)
            return array('status' => 'failed', 'msg' => 'Unknown error installing plugin.');
        return array('status' => 'success');
    }
}

if (!function_exists('srpd_uninstall_plugin')) {
    function srpd_uninstall_plugin($plugin) {
        include_once ABSPATH . 'wp-admin/includes/admin.php';
        include_once ABSPATH . 'wp-admin/includes/upgrade.php';
        include_once ABSPATH . 'wp-includes/update.php';
        WP_Filesystem();
        global $wp_filesystem;
        if (defined('DISALLOW_FILE_MODS') && DISALLOW_FILE_MODS)
            return array('status' => 'failed', 'msg' => 'File modification is disabled with the DISALLOW_FILE_MODS constant');
        $plugins_dir = $wp_filesystem->wp_plugins_dir();
        if (empty($plugins_dir))
            return array('status' => 'failed', 'msg' => 'Unable to locate WordPress Plugin directory');
        $plugins_dir = trailingslashit($plugins_dir);
        if (is_uninstallable_plugin($plugin))
            uninstall_plugin($plugin);
        $this_plugin_dir = trailingslashit(dirname($plugins_dir . $plugin));
        if (strpos($plugin, '/') && $this_plugin_dir != $plugins_dir)
            $deleted = $wp_filesystem->delete($this_plugin_dir, true);
        else
            $deleted = $wp_filesystem->delete($plugins_dir . $plugin);
        if ($deleted) {
            if ($current = get_site_transient('update_plugins')) {
                unset($current->response[$plugin]);
                set_site_transient('update_plugins', $current);
            }
            return array('status' => 'success');
        } else
            return array('status' => 'failed', 'msg' => 'Plugin uninstalled, but not deleted');
    }
}

if (!function_exists('srpd_forcably_filter_update_plugins')) {
    function srpd_update_plugin($plugin_file, $package_zip) {
        global $wprp_zip_update;
        if (defined('DISALLOW_FILE_MODS') && DISALLOW_FILE_MODS)
            return array('status' => 'failed', 'msg' => 'File modification is disabled with the DISALLOW_FILE_MODS constant');
        include_once ABSPATH . 'wp-admin/includes/admin.php';
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        require_once srpd_plugin_path() . '/inc/icp.classes.php';
        $is_active = is_plugin_active($plugin_file);
        foreach (get_plugins() as $path => $maybe_plugin) {
            if ($path == $plugin_file) {
                $plugin = $maybe_plugin;
                break;
            }
        }
        if (!empty($package_zip))
            $zip_url = $package_zip;
        else {
            $manage_wp_updates = apply_filters('mwp_premium_perform_update', array());
            $manage_wp_plugin_update = false;
            foreach ($manage_wp_updates as $manage_wp_update) {
                if (!empty($manage_wp_update['Name']) && $plugin['Name'] == $manage_wp_update['Name'] && !empty($manage_wp_update['url'])) {
                    $zip_url = $manage_wp_update['url'];
                    break;
                }
            }
        }
        $skin = new SRPD_Plugin_Upgrader_Skin();
        $upgrader = new Plugin_Upgrader($skin);
        if (!empty($zip_url)) {
            $wprp_zip_update = array(
                'plugin_file' => $plugin_file,
                'package' => $zip_url,
            );
            add_filter('pre_site_transient_update_plugins', 'srpd_forcably_filter_update_plugins');
        } else {
            wp_update_plugins();
        }
        // Upgrade Now
        ob_start();
        $result = $upgrader->upgrade($plugin_file);
        $data = ob_get_contents();
        ob_clean();
        if ($manage_wp_plugin_update)
            remove_filter('pre_site_transient_update_plugins', 'srpd_forcably_filter_update_plugins');
        if (!empty($skin->error))
            return array('status' => 'failed', 'msg' => $upgrader->strings[$skin->error]);
        else if (is_wp_error($result))
            return array('status' => 'failed', 'msg' => $result);
        else if ((!$result && !is_null($result) ) || $data)
            return array('status' => 'failed', 'msg' => 'Unknown error updating plugin.');
        if ($is_active)
            activate_plugin($plugin_file, '', false, true);
        return array('status' => 'success');
    }
}

if (!function_exists('srpd_forcably_filter_update_plugins')) {
    function srpd_forcably_filter_update_plugins() {
        global $wprp_zip_update;
        $current = new stdClass;
        $current->response = array();
        $plugin_file = $wprp_zip_update['plugin_file'];
        $current->response[$plugin_file] = new stdClass;
        $current->response[$plugin_file]->package = $wprp_zip_update['package'];
        return $current;
    }
}

if (!function_exists('srpd_get_themes')) {
    function srpd_get_themes() {
        require_once( ABSPATH . '/wp-admin/includes/theme.php' );
        if (function_exists('wp_get_themes'))
            $themes = wp_get_themes();
        else
            $themes = get_themes();
        $active = get_option('current_theme');
        if (function_exists('get_site_transient'))
            delete_site_transient('update_themes');
        else
            delete_transient('update_themes');
        wp_update_themes();
        if (function_exists('get_site_transient') && $transient = get_site_transient('update_themes'))
            $current = $transient;
        elseif ($transient = get_transient('update_themes'))
            $current = $transient;
        else
            $current = get_option('update_themes');
        foreach ((array) $themes as $key => $theme) {
            if (is_object($theme) && is_a($theme, 'WP_Theme')) {
                $new_version = isset($current->response[$theme['Template']]) ? $current->response[$theme['Template']]['new_version'] : null;
                $theme_array = array(
                    'Name' => $theme->get('Name'),
                    'active' => $active == $theme->get('Name'),
                    'Template' => $theme->get_template(),
                    'Stylesheet' => $theme->get_stylesheet(),
                    'Screenshot' => $theme->get_screenshot(),
                    'AuthorURI' => $theme->get('AuthorURI'),
                    'Author' => $theme->get('Author'),
                    'latest_version' => $new_version ? $new_version : $theme->get('Version'),
                    'Version' => $theme->get('Version'),
                    'ThemeURI' => $theme->get('ThemeURI')
                );
                $themes[$key] = $theme_array;
            } else {
                $new_version = isset($current->response[$theme['Template']]) ? $current->response[$theme['Template']]['new_version'] : null;
                if ($active == $theme['Name'])
                    $themes[$key]['active'] = true;
                else
                    $themes[$key]['active'] = false;
                if ($new_version) {
                    $themes[$key]['latest_version'] = $new_version;
                    $themes[$key]['latest_package'] = $current->response[$theme['Template']]['package'];
                } else {
                    $themes[$key]['latest_version'] = $theme['Version'];
                }
            }
        }
        return $themes;
    }
}

if (!function_exists('srpd_install_theme')) {
    function srpd_install_theme($download_link) {
        if (defined('DISALLOW_FILE_MODS') && DISALLOW_FILE_MODS)
            return array('status' => 'failed', 'msg' => 'File modification is disabled with the DISALLOW_FILE_MODS constant');
        include_once ABSPATH . 'wp-admin/includes/admin.php';
        include_once ABSPATH . 'wp-admin/includes/upgrade.php';
        include_once ABSPATH . 'wp-includes/update.php';
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        require_once srpd_plugin_path() . '/inc/icp.classes.php';
        $skin = new SRPD_Theme_Upgrader_Skin();
        $upgrader = new Theme_Upgrader($skin);
        $result = $upgrader->install($download_link);
        if (is_wp_error($result))
            return array('status' => 'failed', 'msg' => $result);
        else if (!$result)
            return array('status' => 'failed', 'msg' => 'Unknown error installing theme.');
        return array('status' => 'success');
    }
}

if (!function_exists('srpd_activate_theme')) {
    function srpd_activate_theme($theme) {
        if (!wp_get_theme($theme)->exists())
            return array('status' => 'failed', 'msg' => 'Theme is not installed.');
        switch_theme($theme);
        return array('status' => 'success');
    }
}

if (!function_exists('srpd_delete_theme')) {
    function srpd_delete_theme($theme) {
        include_once ABSPATH . 'wp-admin/includes/admin.php';
        include_once ABSPATH . 'wp-admin/includes/upgrade.php';
        include_once ABSPATH . 'wp-includes/update.php';
        WP_Filesystem();
        global $wp_filesystem;
        if (defined('DISALLOW_FILE_MODS') && DISALLOW_FILE_MODS)
            return array('status' => 'failed', 'msg' => 'File modification is disabled with the DISALLOW_FILE_MODS constant');
        if (!wp_get_theme($theme)->exists())
            return array('status' => 'failed', 'msg' => 'Theme is not installed');
        $themes_dir = $wp_filesystem->wp_themes_dir();
        if (empty($themes_dir))
            return array('status' => 'failed', 'msg' => 'Unable to locate WordPress theme directory');
        $themes_dir = trailingslashit($themes_dir);
        $theme_dir = trailingslashit($themes_dir . $theme);
        $deleted = $wp_filesystem->delete($theme_dir, true);
        if (!$deleted)
            return array('status' => 'failed', 'msg' => 'Could not fully delete the theme');
        delete_site_transient('update_themes');
        return array('status' => 'success');
    }
}

if (!function_exists('srpd_update_theme')) {
    function srpd_update_theme($theme) {
        if (defined('DISALLOW_FILE_MODS') && DISALLOW_FILE_MODS)
            return array('status' => 'failed', 'msg' => 'File modification is disabled with the DISALLOW_FILE_MODS constant');
        include_once ( ABSPATH . 'wp-admin/includes/admin.php' );
        require_once ( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
        require_once srpd_plugin_path() . '/inc/icp.classes.php';
        $skin = new SRPD_Theme_Upgrader_Skin();
        $upgrader = new Theme_Upgrader($skin);
        // Upgrade Now
        ob_start();
        $result = $upgrader->upgrade($theme);
        $data = ob_get_contents();
        ob_clean();
        if (!empty($skin->error))
            return array('status' => 'failed', 'msg' => $upgrader->strings[$skin->error]);
        else if (is_wp_error($result))
            return array('status' => 'failed', 'msg' => $result);
        else if ((!$result && !is_null($result) ) || $data)
            return array('status' => 'failed', 'msg' => 'Unknown error updating theme.');
        return array('status' => 'success');
    }
}

if (!function_exists('srpd_update_core')) {
    function srpd_update_core() {
        if (defined('DISALLOW_FILE_MODS') && DISALLOW_FILE_MODS)
            return array('status' => 'failed', 'msg' => 'File modification is disabled with the DISALLOW_FILE_MODS constant');
        include_once ( ABSPATH . 'wp-admin/includes/admin.php' );
        include_once ( ABSPATH . 'wp-admin/includes/upgrade.php' );
        include_once ( ABSPATH . 'wp-includes/update.php' );
        require_once ( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
        require_once srpd_plugin_path() . '/inc/icp.classes.php';
        wp_version_check();
        $updates = get_core_updates();
        if (is_wp_error($updates) || !$updates)
            return array('status' => 'failed', 'msg' => 'No Update Available');
        $update = reset($updates);
        if (!$update)
            return array('status' => 'failed', 'msg' => 'No Update Available');
        $skin = new SRPD_Core_Upgrader_Skin();
        $upgrader = new Core_Upgrader($skin);
        $result = $upgrader->upgrade($update);
        if (is_wp_error($result))
            return array('status' => 'failed', 'msg' => $result);
        global $wp_current_db_version, $wp_db_version;
        require( ABSPATH . WPINC . '/version.php' );
        wp_upgrade();
        return array('status' => 'success');
    }
}

if (!function_exists('srpd_check_access')) {
    function srpd_check_access($access = 0) {
        global $frame_path;
        $block = false;
        $check = wp_remote_get($frame_path . "/plugin/plugin.check.key.php?plugin_key=" . srpd_get_plugin_key());
        $check = wp_remote_retrieve_body($check);
        if ($check) { //check if request is completed
            $check = json_decode($check, true);
            if ($check['status'] == 'not_found') //if public key is not found block the plugin
                $block = true;
        } else
            $block = true;
        if ($access == 1)
            $block = false;
        if ($block) {
            include srpd_plugin_path() . '/inc/pages/icp.activate.php';
            die();
        } else
            return $check;
    }
}

if (!function_exists('srpd_get_plugin_key')) {
    function srpd_get_plugin_key() {
        $plugin_key = get_option('icp_plugin_key');
        if ($plugin_key == false)
            return '';
        else
            return $plugin_key;
    }
}

if (!function_exists('srpd_get_clickbank_id')) {
    function srpd_get_clickbank_id() {
        $clickbank_id = get_option('srpd_clickbank_id');
        if ($clickbank_id == false)
            return '';
        else
            return $clickbank_id;
    }
}

if (!function_exists('srpd_update_plugin_key')) {
    function srpd_update_plugin_key() {
        $code = str_replace("\'", "'", str_replace('\"', '"', sanitize_text_field($_POST['plugin_key'])));
        $code = trim($code);
        $code = str_replace(array("\n", "\r\n", "\tab"), "", $code);
        update_option('icp_plugin_key', $code);
        return '1';
    }
}

if (!function_exists('srpd_update_clickbank_id')) {
    function srpd_update_clickbank_id() {
        $id = sanitize_text_field($_POST['clickbank_id']);
        $id = str_replace(array("\n", "\r\n", "\tab"), "", $id);
        update_option('srpd_clickbank_id', $id);
        return '1';
    }
}


if (!function_exists('srpd_curPageURL')) {
    function srpd_curPageURL() {
        $pageURL = 'http';
        if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        return $pageURL;
    }
}

if (!function_exists('srpd_encrypt')) {
    function srpd_encrypt($text, $salt = '()_+f834jd89d39r') {
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }
}

// ADD NEW COLUMN
if (!function_exists('srpd_encrypt_decrypt')) {

    function srpd_encrypt_decrypt($string, $action = 'e') {
        // you may change these values to your own
        $secret_key = '()_+f834jd89d39r';
        $secret_iv = 'f834jd89d39r';
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ($action == 'e') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else if ($action == 'd') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

}

if (!function_exists('srpd_serped_innerLink_column')) {
    function srpd_serped_innerLink_column($defaults) {
        $defaults['serped_innerLink'] = 'SERPed.net';
        return $defaults;
    }
}

// SHOW THE FEATURED IMAGE
function srpd_serped_innerLink_column_content($column_name, $post_ID) {
    echo '<style>
    #TB_title { text-align: center;
    background: rgba(74,144,226,.06);
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    padding: 6px 15px;
    height: 40px;
    }
    #TB_ajaxWindowTitle {
    line-height: 35px;
    font-size: 22px;
    font-weight: 800;
    color: #5b6169!important;
    font-family: Avenir,sans-serif!important;
    }
    #TB_window {
    border-radius: 15px;
    }
    </style>';
    global $frame_path;
    if ($column_name == 'serped_innerLink') {
        $post = get_post($post_ID);
        $respoce = wp_remote_get("https://members.serped.net/plugin/add.inner.page.php?pkey=" . srpd_get_plugin_key() . '&url=' . urlencode(get_permalink($post_ID)) . "&checkurl");
        $respoce = wp_remote_retrieve_body($respoce);
        $respoce = json_decode($respoce, true);

        if($respoce['status']){
           echo '<a href="https://members.serped.net/plugin/add.inner.page.php?pkey=' . srpd_get_plugin_key() . '&url=' . urlencode(get_permalink($post_ID)) . '&eid='. $respoce['id'] .'&TB_iframe=true&width=950&height=300" class="thickbox" title="Edit Inner Page">Edit Inner Page <br> in SERPed</a> ';
        } else {
           echo '<a href="https://members.serped.net/plugin/add.inner.page.php?pkey=' . srpd_get_plugin_key() . '&url=' . urlencode(get_permalink($post_ID)) . '&TB_iframe=true&width=950&height=650" class="thickbox" title="Add as Inner Page">Add ' . ucfirst($post->post_type) . ' To SERPed</a> ';
        }        
    }
}
 function srpd_mw_enqueue_color_picker($hook_suffix) {
                        // first check that $hook_suffix is appropriate for your admin page
                        wp_enqueue_style( 'wp-color-picker' );
                        wp_enqueue_script( 'my-script-handle', srpd_root_path().'/js/color-picker.js', array( 'wp-color-picker' ), false, true );
                    }
                   add_action( 'admin_enqueue_scripts', 'srpd_mw_enqueue_color_picker' );
?>