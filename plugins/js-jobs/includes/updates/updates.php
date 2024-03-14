<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSupdates {

    static function checkUpdates() {
        $installedversion = JSJOBSupdates::getInstalledVersion();
        if ($installedversion != jsjobs::$_currentversion) {
            $from = $installedversion + 1;
            $to = jsjobs::$_currentversion;
            for ($i = $from; $i <= $to; $i++) {
                $installfile = JSJOBS_PLUGIN_PATH . 'includes/updates/sql/' . $i . '.sql';
                if (file_exists($installfile)) {
                    $delimiter = ';';
                    $file = fopen($installfile, 'r');
                    if (is_resource($file) === true) {
                        $query = array();
                        while (feof($file) === false) {
                            $query[] = fgets($file);
                            if (jsjobslib::jsjobs_preg_match('~' . preg_quote($delimiter, '~') . '\s*$~iS', end($query)) === 1) {
                                $query = jsjobslib::jsjobs_trim(implode('', $query));
                                $query = jsjobslib::jsjobs_str_replace("#__", jsjobs::$_db->prefix, $query);
                                if (!empty($query)) {
                                    jsjobs::$_db->query($query);
                                }
                            }
                            if (is_string($query) === true) {
                                $query = array();
                            }
                        }
                        fclose($file);
                    }
                }
            }
        }
    }

    static function getInstalledVersion() {
        $query = "SELECT configvalue FROM `" . jsjobs::$_db->prefix . "js_job_config` WHERE configname = 'versioncode'";
        $version = jsjobs::$_db->get_var($query);
        if (!$version)
            $version = '100';
        else
            $version = jsjobslib::jsjobs_str_replace('.', '', $version);
        return $version;
    }

}

?>
