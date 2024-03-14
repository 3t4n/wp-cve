<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSdeactivation {

    static function jsjobs_deactivate() {
        wp_clear_scheduled_hook('jsjobs_cronjobs_action');
        $id = jsjobs::getPageid();
        jsjobs::$_db->get_var("UPDATE `" . jsjobs::$_db->prefix . "posts` SET post_status = 'draft' WHERE ID = $id");
    }

}

?>