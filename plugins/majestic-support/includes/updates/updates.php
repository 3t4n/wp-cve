<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_updates {

    static function MJTC_checkUpdates($cversion=null) {
        if (is_null($cversion)) {
            $cversion = majesticsupport::$_currentversion;
        }
        $installedversion = MJTC_updates::MJTC_getInstalledVersion();
        if ($installedversion != $cversion) {
			//UPDATE the last_version of the plugin
			$query = "REPLACE INTO `".majesticsupport::$_db->prefix."mjtc_support_config` (`configname`, `configvalue`, `configfor`) VALUES ('last_version','','default');";
			majesticsupport::$_db->query($query); //old actual
			$query = "SELECT configvalue FROM `".majesticsupport::$_db->prefix."mjtc_support_config` WHERE configname='versioncode'";
			$versioncode = majesticsupport::$_db->get_var($query);
            if($versioncode != ''){
			    $versioncode = MJTC_majesticsupportphplib::MJTC_str_replace('.','',$versioncode);
            }
			$query = "UPDATE `".majesticsupport::$_db->prefix."mjtc_support_config` SET configvalue = '".esc_sql($versioncode)."' WHERE configname = 'last_version';";
			majesticsupport::$_db->query($query);
            $from = $installedversion + 1;
            $to = $cversion;
            for ($i = $from; $i <= $to; $i++) {
                $installfile = MJTC_PLUGIN_PATH . 'includes/updates/sql/' . $i . '.sql';
                if (file_exists($installfile)) {
                    $delimiter = ';';
                    $file = fopen($installfile, 'r');
                    if (is_resource($file) === true) {
                        $query = array();

                        while (feof($file) === false) {
                            $query[] = fgets($file);
                            if (MJTC_majesticsupportphplib::MJTC_preg_match('~' . preg_quote($delimiter, '~') . '\s*$~iS', end($query)) === 1) {
                                $query = trim(implode('', $query));
                                if($query != ''){
                                    $query = MJTC_majesticsupportphplib::MJTC_str_replace("#__", majesticsupport::$_db->prefix, $query);
                                }
                                if (!empty($query)) {
                                    majesticsupport::$_db->query($query);
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

    static function MJTC_getInstalledVersion() {
        $query = "SELECT configvalue FROM `" . majesticsupport::$_db->prefix . "mjtc_support_config` WHERE configname = 'versioncode'";
        $version = majesticsupport::$_db->get_var($query);
        if (!$version){
            $version = '102';
        }
        else{
            if($version != ''){
                $version = MJTC_majesticsupportphplib::MJTC_str_replace('.', '', $version);
            }
        }
        return $version;
    }

}

?>
