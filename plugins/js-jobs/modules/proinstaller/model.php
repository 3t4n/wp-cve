<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSproinstallerModel {

    function getServerValidate() {
        $array = jsjobslib::jsjobs_explode('.', phpversion());
        $phpversion = $array[0] . '.' . $array[1];
        // $curlexist = function_exists('curl_version');
        //$curlversion = curl_version()['version'];
        // $curlversion = '';
        jsjobs::$_data['phpversion'] = $phpversion;
        // jsjobs::$_data['curlexist'] = $curlexist;
        // jsjobs::$_data['curlversion'] = $curlversion;
    }

    function getConfiguration() {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        // check for plugin using plugin name
        if (is_plugin_active('js-jobs/js-jobs.php')) {
            //plugin is activated
            $query = "SELECT config.* FROM `" . jsjobs::$_db->prefix . "js_job_config` AS config";
            $config = jsjobs::$_db->get_results($query);
            foreach ($config as $conf) {
                jsjobs::$_configuration[$conf->configname] = $conf->configvalue;
            }
            jsjobs::$_configuration['config_count'] = COUNT($config);
        }
    }

    function makeDir($path) {
        if (!file_exists($path)) { // create directory
            mkdir($path, 0755);
            $ourFileName = $path . '/index.html';
            $ourFileHandle = fopen($ourFileName, 'w') or die("$path  can't create. Please create directory with 0755 permissions");
            fclose($ourFileHandle);
        }
    }

    function getMessagekey(){
        $key = 'proinstaller';if(JSJOBSincluder::getJSModel('common')->jsjobs_isadmin()){$key = 'admin_'.$key;}return $key;
    }


}

?>
