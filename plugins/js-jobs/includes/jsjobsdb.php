<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class jsjobsdb {

    function __construct() {
        
    }

    public static function get_var($query) {
        $result = jsjobs::$_db->get_var($query);
        if (jsjobs::$_db->last_error != null) {
            JSJOBSincluder::getJSModel('systemerror')->addSystemError();
        }
        return $result;
    }

    public static function get_row($query) {
        $result = jsjobs::$_db->get_row($query);
        if (jsjobs::$_db->last_error != null) {
            JSJOBSincluder::getJSModel('systemerror')->addSystemError();
        }
        return $result;
    }

    public static function get_results($query) {
        $result = jsjobs::$_db->get_results($query);
        if (jsjobs::$_db->last_error != null) {
            JSJOBSincluder::getJSModel('systemerror')->addSystemError();
        }
        return $result;
    }

    public static function query($query) {
        $result = true;
        jsjobs::$_db->query($query);
        if (jsjobs::$_db->last_error != null) {
            JSJOBSincluder::getJSModel('systemerror')->addSystemError();
            $result = false;
        }
        return $result;
    }

}

?>
