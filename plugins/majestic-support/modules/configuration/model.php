<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_configurationModel {

    function getConfigurations() {
        $query = "SELECT configname,configvalue,addon
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_config` ";//WHERE configfor != 'ticketviaemail'";
        $data = majesticsupport::$_db->get_results($query);

        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        foreach ($data AS $config) {
            if($config->addon == '' ||  in_array($config->addon, majesticsupport::$_active_addons)){
                majesticsupport::$_data[0][$config->configname] = $config->configvalue;
            }
        }

        majesticsupport::$_data[1] = MJTC_includer::MJTC_getModel('email')->getAllEmailsForCombobox();
        if(in_array('banemail', majesticsupport::$_active_addons)){
            MJTC_includer::MJTC_getModel('banemaillog')->checkbandata();
        }
        return;
    }

    function getConfigurationByFor($for) {
		if($for == 'ticketviaemail'){
			$query = "SELECT COUNT(configname) FROM `" . majesticsupport::$_db->prefix . "mjtc_support_config` WHERE configfor = '".$for."'";
			$count = majesticsupport::$_db->get_var($query);
			if($count < 5){
				$query = "SELECT configname,configvalue
							FROM `" . majesticsupport::$_db->prefix . "mjtc_support_config` ";
				$data = majesticsupport::$_db->get_results($query);
				if (majesticsupport::$_db->last_error != null) {
					MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
				}
				foreach ($data AS $config) {
					majesticsupport::$_data[0][$config->configname] = $config->configvalue;
				}
				if(in_array('banemail', majesticsupport::$_active_addons)){
                    MJTC_includer::MJTC_getModel('banemaillog')->checkbandata();
                }
                return;
			}
		}
        $query = "SELECT configname,configvalue
					FROM `" . majesticsupport::$_db->prefix . "mjtc_support_config` WHERE configfor = '".$for."'";
        $data = majesticsupport::$_db->get_results($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        foreach ($data AS $config) {
            majesticsupport::$_data[0][$config->configname] = $config->configvalue;
        }
        if(in_array('banemail', majesticsupport::$_active_addons)){
            MJTC_includer::MJTC_getModel('banemaillog')->checkbandata();
        }
        return;
    }
    function getCountByConfigFor($for) {
        if (( in_array('agent',majesticsupport::$_active_addons) && MJTC_includer::MJTC_getModel('agent')->isUserStaff())) {
            $query = "SELECT COUNT(configvalue)
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_config` WHERE configfor = '".$for. "' AND configname LIKE '%staff' AND configvalue = 1 " ;
        }else{
            $query = "SELECT COUNT(configvalue)
                    FROM `" . majesticsupport::$_db->prefix . "mjtc_support_config` WHERE configfor = '".$for. "' AND configname LIKE '%user' AND configvalue = 1 " ;
        }
        $data = majesticsupport::$_db->get_var($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return $data;
    }

    function storeDesktopNotificationLogo($filename) {
        majesticsupport::$_db->query("UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_config` SET configvalue = '" . esc_sql($filename) . "' WHERE configname = 'logo_for_desktop_notfication_url' ");
    }

    function deleteDesktopNotificationsLogo() {
        $datadirectory = majesticsupport::$_config['data_directory'];

        $maindir = wp_upload_dir();
        $path = $maindir['basedir'];
        $path = $path .'/'.$datadirectory;

        $file_name = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('logo_for_desktop_notfication_url');

        $path = $path . '/attachmentdata/';
        $dsk_logo_file =  $path.$file_name;
        if($file_name != ''){
            @unlink($dsk_logo_file);
        }
    }


    function storeConfiguration($data) {
        $notsave = false;
        $updateColors = false;
        foreach ($data AS $key => $value) {
            $query = true;

            if ($key == 'offline_message') {
                $offline_message = $value;
                if(!empty($offline_message)){
                    $value = MJTC_includer::MJTC_getModel('majesticsupport')->getSanitizedEditorData($_POST['offline_message']); // use mjsupport_message to avoid conflict
                }
            }

            if ($key == 'visitor_message') {
                $visitor_message = $value;
                if(!empty($visitor_message)){
                    $value = MJTC_includer::MJTC_getModel('majesticsupport')->getSanitizedEditorData($_POST['visitor_message']); // use mjsupport_message to avoid conflict
                }
            }

            if ($key == 'new_ticket_message') {
                $new_ticket_message = $value;
                if(!empty($new_ticket_message)){
                    $value = MJTC_includer::MJTC_getModel('majesticsupport')->getSanitizedEditorData($_POST['new_ticket_message']); // use mjsupport_message to avoid conflict
                }
            }

            if ($key == 'screentag_position') {
                if ($value != majesticsupport::$_config['screentag_position']) {
                    $updateColors = true;
                }
            }

            if ($key == 'pagination_default_page_size') {
                if ($value < 3) {
                    MJTC_message::MJTC_setMessage(esc_html(__('Pagination default page size not saved', 'majestic-support')), 'error');
                    continue;
                }
            }

            if($key == 'del_logo_for_desktop_notfication' && $value == 1){
                $this->deleteDesktopNotificationsLogo();
                $key = 'logo_for_desktop_notfication_url';
                $value = '';
            }


            if ($key == 'data_directory') {
                $data_directory = $value;
                if(empty($data_directory)){
                    MJTC_message::MJTC_setMessage(esc_html(__('Data directory cannot empty.', 'majestic-support')), 'error');
                    continue;
                }
                if(MJTC_majesticsupportphplib::MJTC_strpos($data_directory, '/') !== false){
                    MJTC_message::MJTC_setMessage(esc_html(__('Data directory is not proper.', 'majestic-support')), 'error');
                    continue;
                }
                $path = MJTC_PLUGIN_PATH.'/'.$data_directory;
                if ( ! file_exists($path)) {
                   mkdir($path, 0755);
                }
                if( ! is_writeable($path)){
                    MJTC_message::MJTC_setMessage(esc_html(__('Data directory is not writable.', 'majestic-support')), 'error');
                    continue;
                }
            }
            if ($key == 'system_slug') {
                if(empty($value)){
                    MJTC_message::MJTC_setMessage(esc_html(__('System slug not be empty.', 'majestic-support')), 'error');
                    continue;
                }
                if($value != ''){
                    $value = MJTC_majesticsupportphplib::MJTC_str_replace(' ', '-', $value);
                }
                $query = 'SELECT COUNT(ID) FROM `'.majesticsupport::$_db->prefix.'posts` WHERE post_name = "'.esc_sql($value).'"';
                $countslug = majesticsupport::$_db->get_var($query);
                if($countslug >= 1){
                    MJTC_message::MJTC_setMessage(esc_html(__('System slug is conflicted with post or page slug.', 'majestic-support')), 'error');
                    continue;
                }
            }
            majesticsupport::$_db->update(majesticsupport::$_db->prefix . 'mjtc_support_config', array('configvalue' => $value), array('configname' => $key));
            if (majesticsupport::$_db->last_error != null) {
                MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
                $notsave = true;
            }
        }
        if ($notsave == false) {
            MJTC_message::MJTC_setMessage(esc_html(__('The setting has been stored', 'majestic-support')), 'updated');
        } else {
            MJTC_message::MJTC_setMessage(esc_html(__('The setting not has been stored', 'majestic-support')), 'error');
        }
        if ($updateColors == true) {
            MJTC_includer::MJTC_getModel('majesticsupport')->updateColorFile();
        }
        update_option('rewrite_rules', '');

        if (isset($_FILES['logo_for_desktop_notfication'])) { // upload image for desktop notifications
            MJTC_includer::MJTC_getObjectClass('uploads')->MJTC_uploadDesktopNotificationLogo();
        }
        if (isset($_FILES['support_custom_img'])) { // upload image for custom image
            $this->storeSupportCustomImage();
        }
        return;
    }

    function storeSupportCustomImage() {
        if (!function_exists('wp_handle_upload')) {
            do_action('majesticsupport_load_wp_file');
        }
        $maindir = wp_upload_dir();
        $basedir = $maindir['basedir'];
        $datadirectory = majesticsupport::$_config['data_directory'];
        
        $path = $basedir . '/' . $datadirectory;
        if (!file_exists($path)) { // create user directory
            MJTC_includer::MJTC_getModel('majesticsupport')->makeDir($path);
        }
        $isupload = false;
        $path = $path . '/supportImg';
        if (!file_exists($path)) { // create user directory
            MJTC_includer::MJTC_getModel('majesticsupport')->makeDir($path);
        }
        
        if ($_FILES['support_custom_img']['size'] > 0) {
            $file_name = MJTC_majesticsupportphplib::MJTC_str_replace(' ', '_', sanitize_file_name($_FILES['support_custom_img']['name']));
            $file_tmp = majesticsupport::MJTC_sanitizeData($_FILES['support_custom_img']['tmp_name']); // actual location
            // MJTC_sanitizeData() function uses wordpress santize functions

            $userpath = $path;
            $isupload = true;
        }
        if ($isupload) {
            $this->uploadfor = 'supportcustomlogo';
            // Register our path override.
            add_filter( 'upload_dir', array($this,'majesticsupport_upload_custom_logo'));
            // Do our thing. WordPress will move the file to 'uploads/mycustomdir'.
            $result = array();
            $file = array(
                'name' => sanitize_file_name($_FILES['support_custom_img']['name']),
                'type' => majesticsupport::MJTC_sanitizeData($_FILES['support_custom_img']['type']),
                'tmp_name' => majesticsupport::MJTC_sanitizeData($_FILES['support_custom_img']['tmp_name']),
                'error' => majesticsupport::MJTC_sanitizeData($_FILES['support_custom_img']['error']),
                'size' => majesticsupport::MJTC_sanitizeData($_FILES['support_custom_img']['size']),
            ); // MJTC_sanitizeData() function uses wordpress santize functions
            $result = wp_handle_upload($file, array('test_form' => false));
            if ( $result && ! isset( $result['error'] ) ) {
                $this->setSupportCustomImage($file_name, $userpath);
            }
            // Set everything back to normal.
            remove_filter( 'upload_dir', array($this,'majesticsupport_upload_custom_logo'));
        }
    }

    function majesticsupport_upload_custom_logo( $dir ) {
        if($this->uploadfor == 'supportcustomlogo'){
            $datadirectory = majesticsupport::$_config['data_directory'];
            $path = $datadirectory . '/supportImg';
            $array = array(
                'path'   => $dir['basedir'] . '/' . $path,
                'url'    => $dir['baseurl'] . '/' . $path,
                'subdir' => '/'. $path,
            ) + $dir;
            return $array;
        }else{
            return $dir;
        }
    }

    function setSupportCustomImage($filename, $userpath){
        $query = "SELECT configvalue FROM `".majesticsupport::$_db->prefix."mjtc_support_config` WHERE configname = 'support_custom_img'";
        $key = majesticsupport::$_db->get_var($query);
        if ($key) {
            $unlinkPath = $userpath.'/'.$key;
            if (is_file($unlinkPath)) {
                unlink($unlinkPath);
            }
        }
        majesticsupport::$_db->update(majesticsupport::$_db->prefix . 'mjtc_support_config', array('configvalue' => $filename), array('configname' => 'support_custom_img'));
    }

    function deleteSupportCustomImage(){
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-support-customimage') ) {
            die( 'Security check Failed' );
        }
        $maindir = wp_upload_dir();
        $basedir = $maindir['basedir'];
        $datadirectory = majesticsupport::$_config['data_directory'];
        $path = $basedir . '/' . $datadirectory;
        $path = $path . '/supportImg';

        $query = "SELECT configvalue FROM `".majesticsupport::$_db->prefix."mjtc_support_config` WHERE configname = 'support_custom_img'";
        $key = majesticsupport::$_db->get_var($query);
        if ($key) {
            $unlinkPath = $path.'/'.$key;
            if (is_file($unlinkPath)) {
                unlink($unlinkPath);
            }
        }
        majesticsupport::$_db->update(majesticsupport::$_db->prefix . 'mjtc_support_config', array('configvalue' => 0), array('configname' => 'support_custom_img'));
        return 'success';
    }

    function getEmailReadTime() {
        $time = null;
        $query = "SELECT config.configvalue FROM `".majesticsupport::$_db->prefix."mjtc_support_config` AS config WHERE config.configname = 'lastEmailReadingTime'";
        $time = majesticsupport::$_db->get_var($query);
        return $time;
    }

    function setEmailReadTime($time) {
        majesticsupport::$_db->update(majesticsupport::$_db->prefix . 'mjtc_support_config', array('configvalue' => $time), array('configname' => 'lastEmailReadingTime'));
    }

    function getConfiguration() {
        do_action('majesticsupport_load_wp_plugin_file');
        // check for plugin using plugin name
        if (is_plugin_active('majestic-support/majestic-support.php')) {
            //plugin is activated
            $query = "SELECT config.* FROM `" . majesticsupport::$_db->prefix . "mjtc_support_config` AS config WHERE config.configfor != 'ticketviaemail'";
            $config = majesticsupport::$_db->get_results($query);
            foreach ($config as $conf) {
                majesticsupport::$_config[$conf->configname] = $conf->configvalue;
            }
            majesticsupport::$_config['config_count'] = COUNT($config);
        }
    }

    function getCheckCronKey() {
        $query = "SELECT configvalue FROM `".majesticsupport::$_db->prefix."mjtc_support_config` WHERE configname = 'ck'";
        $key = majesticsupport::$_db->get_var($query);
        if ($key && $key != '')
            return true;
        else
            return false;
    }

    function genearateCronKey() {
        $key = MJTC_majesticsupportphplib::MJTC_md5(date('Y-m-d'));
        $query = "UPDATE `".majesticsupport::$_db->prefix."mjtc_support_config` SET configvalue = '".esc_sql($key)."' WHERE configname = 'ck'" ;
        majesticsupport::$_db->query($query);
        return true;
    }

    function getCronKey($passkey) {
        if ($passkey == MJTC_majesticsupportphplib::MJTC_md5(date('Y-m-d'))) {
            $query = "SELECT configvalue FROM `".majesticsupport::$_db->prefix."mjtc_support_config` WHERE configname = 'ck'";
            $key = majesticsupport::$_db->get_var($query);
            return $key;
        }
        else
            return false;
    }

    function getConfigValue($configname){
        $query = "SELECT configvalue FROM `".majesticsupport::$_db->prefix."mjtc_support_config` WHERE configname = '".esc_sql($configname)."'";
        $configvalue = majesticsupport::$_db->get_var($query);
        return $configvalue;
    }

    function getPageList() {
        $query = "SELECT ID AS id, post_title AS text FROM `" . majesticsupport::$_db->prefix . "posts` WHERE post_type = 'page' AND post_status = 'publish' ";
        $emails = majesticsupport::$_db->get_results($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return $emails;
    }

    function getWooCommerceCategoryList() {
        $orderby = 'term_id';
        $order = 'desc';
        $hide_empty = false ;
        $cat_args = array(
            'orderby'    => $orderby,
            'order'      => $order,
            'hide_empty' => $hide_empty,
        );
        $product_categories = get_terms( 'product_cat', $cat_args );
        $catList = array();
        foreach ($product_categories as $category) {
            $catList[] = (object) array('id' => $category->term_id, 'text' => $category->name);
        }
        return $catList;
    }

    function getConfigurationByConfigName($configname) {
        $query = "SELECT configvalue
                  FROM  `".majesticsupport::$_db->prefix."mjtc_support_config` WHERE configname ='" . esc_sql($configname) . "'";
        $result = majesticsupport::$_db->get_var($query);
        return $result;
    }
    function getCountConfig() {
        $query = "SELECT COUNT(*)
                  FROM `".majesticsupport::$_db->prefix."mjtc_support_config`";
        $result = majesticsupport::$_db->get_var($query);
        return $result;
    }
}

?>
