<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSconfigurationModel {

    var $_data_directory = null;
    var $_comp_editor = null;
    var $_job_editor = null;
    var $_defaultcountry = null;
    var $_config = null;

    function __construct() {
        
    }

    function getConfiguration() {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        // check for plugin using plugin name
        if (is_plugin_active('js-jobs/js-jobs.php')) {
            $query = "SELECT config.* FROM `" . jsjobs::$_db->prefix . "js_job_config` AS config WHERE configfor = 'default'";
            $config = jsjobsdb::get_results($query);
            foreach ($config as $conf) {
                jsjobs::$_configuration[$conf->configname] = $conf->configvalue;
            }
            jsjobs::$_configuration['config_count'] = COUNT($config);
        }
    }

    function getConfigurationsForForm() {
        $query = "SELECT config.* FROM `" . jsjobs::$_db->prefix . "js_job_config` AS config";
        $config = jsjobsdb::get_results($query);
        foreach ($config as $conf) {
            jsjobs::$_data[0][$conf->configname] = $conf->configvalue;
        }
        jsjobs::$_data[0]['config_count'] = COUNT($config);
    }

    function storeConfig($data) {
        if (empty($data))
            return false;

        if ($data['isgeneralbuttonsubmit'] == 1) {
            if (!isset($data['employer_share_fb_like']))
                $data['employer_share_fb_like'] = 0;
            if (!isset($data['employer_share_fb_share']))
                $data['employer_share_fb_share'] = 0;
            if (!isset($data['employer_share_fb_comments']))
                $data['employer_share_fb_comments'] = 0;
            if (!isset($data['employer_share_google_like']))
                $data['employer_share_google_like'] = 0;
            if (!isset($data['employer_share_google_share']))
                $data['employer_share_google_share'] = 0;
            if (!isset($data['employer_share_blog_share']))
                $data['employer_share_blog_share'] = 0;
            if (!isset($data['employer_share_friendfeed_share']))
                $data['employer_share_friendfeed_share'] = 0;
            if (!isset($data['employer_share_linkedin_share']))
                $data['employer_share_linkedin_share'] = 0;
            if (!isset($data['employer_share_digg_share']))
                $data['employer_share_digg_share'] = 0;
            if (!isset($data['employer_share_twitter_share']))
                $data['employer_share_twitter_share'] = 0;
            if (!isset($data['employer_share_myspace_share']))
                $data['employer_share_myspace_share'] = 0;
            if (!isset($data['employer_share_yahoo_share']))
                $data['employer_share_yahoo_share'] = 0;
  
        }
        if(isset($_POST['offline_text'])){
            $data['offline_text'] = JSJOBSincluder::getJSModel('common')->getSanitizedEditorData($_POST['offline_text']);
		}
        $error = false;
        //DB class limitations
        foreach ($data as $key => $value) {
			if ($key == 'data_directory') {
				$data_directory = $value;
				if(empty($data_directory)){
					JSJOBSMessages::setLayoutMessage(__('Data directory can not empty.', 'js-jobs'), 'error',$this->getMessagekey());
					continue;
				}
				if(jsjobslib::jsjobs_strpos($data_directory, '/') !== false){
					JSJOBSMessages::setLayoutMessage(__('Data directory is not proper.', 'js-jobs'), 'error',$this->getMessagekey());
					continue;
				}
				$path = JSJOBS_PLUGIN_PATH.'/'.$data_directory;
				if ( ! file_exists($path)) {
				   mkdir($path, 0755);
				}
				if( ! is_writeable($path)){
					JSJOBSMessages::setLayoutMessage(__('Data directory is not writable.', 'js-jobs'), 'error',$this->getMessagekey());
					continue;
				}
			}
            $query = "UPDATE `" . jsjobs::$_db->prefix . "js_job_config` SET `configvalue` = '$value' WHERE `configname`= '" . $key . "'";
            if (false === jsjobsdb::query($query)) {
                $error = true;
            }
        }
        if ($error)
            return JSJOBS_SAVE_ERROR;
        else
            return JSJOBS_SAVED;
    }

    function getConfigByFor($configfor) {
        if (!$configfor)
            return;
        $query = "SELECT * FROM `" . jsjobs::$_db->prefix . "js_job_config` WHERE configfor = '" . $configfor . "'";
        $config = jsjobsdb::get_results($query);
        $configs = array();
        foreach ($config as $conf) {
            $configs[$conf->configname] = $conf->configvalue;
        }
        return $configs;
    }

    function getCountConfig() {

        $query = "SELECT COUNT(*) FROM `" . jsjobs::$_db->prefix . "js_job_config`";
        $result = jsjobsdb::get_var($query);
        return $result;
    }

    function getConfigValue($configname) {
        $query = "SELECT configvalue FROM `" . jsjobs::$_db->prefix . "js_job_config` WHERE configname = '" . $configname . "'";
        //return jsjobsdb::get_var($query);
		return jsjobs::$_db->get_var($query);
    }

    function getConfigurationByConfigForMultiple($configfor){
        $query = "SELECT configname,configvalue 
                  FROM `".jsjobs::$_db->prefix."js_job_config` WHERE configfor IN (".$configfor.")";
        $result = jsjobsdb::get_results($query);
        $config_array =  array();
        //to make configuration in to an array with key as index 
        foreach ($result as $config ) {
           $config_array[$config->configname] = $config->configvalue;
        }
        return $config_array;
    }

    function getConfigurationByConfigName($configname){
        $query = "SELECT configvalue 
                  FROM `".jsjobs::$_db->prefix."js_job_config` WHERE configname ='" . $configname . "'";
        $result = jsjobsdb::get_var($query);
        return $result;

    }

    function checkCronKey($passkey) {

        $query = "SELECT COUNT(configvalue) FROM `".jsjobs::$_db->prefix."js_job_config` WHERE configname = 'cron_job_alert_key' AND configvalue = '" . $passkey . "'";
        $key = jsjobsdb::get_var($query);
        if ($key == 1)
            return true;
        else
            return false;
    }
    function getMessagekey(){
        $key = 'configuration';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }



}

?>
