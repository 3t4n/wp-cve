<?php

if(!function_exists('cg_update_column_types')){

    function cg_update_column_types($i){

        global $wpdb;

        $tablename = $wpdb->base_prefix . "$i"."contest_gal1ery";

    // Update 6.08.2018
        $rowType = $wpdb->get_var("SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$tablename' AND column_name = 'CountS'");
        if(strpos(strtolower($rowType),'varchar')>=0){
            $wpdb->query("ALTER TABLE $tablename MODIFY CountS INT(11) DEFAULT 0");
            $wpdb->update(
                "$tablename",
                array('CountS' => 0),
                array('CountS' => NULL),
                array('%d'),
                array('%d')
            );
        }

        $rowType = $wpdb->get_var("SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$tablename' AND column_name = 'CountR'");
        if(strpos(strtolower($rowType),'varchar')>=0){
            $wpdb->query("ALTER TABLE $tablename MODIFY CountR INT(11) DEFAULT 0");
            $wpdb->update(
                "$tablename",
                array('CountR' => 0),
                array('CountR' => NULL),
                array('%d'),
                array('%d')
            );
        }

        $rowType = $wpdb->get_var("SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$tablename' AND column_name = 'CountC'");
        if(strpos(strtolower($rowType),'varchar')>=0){
            $wpdb->query("ALTER TABLE $tablename MODIFY CountC INT(11) DEFAULT 0");
            $wpdb->update(
                "$tablename",
                array('CountC' => 0),
                array('CountC' => NULL),
                array('%d'),
                array('%d')
            );
        }

        $rowType = $wpdb->get_var("SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$tablename' AND column_name = 'Rating'");
        if(strpos(strtolower($rowType),'varchar')>=0){
            $wpdb->query("ALTER TABLE $tablename MODIFY Rating INT(17) DEFAULT 0");
            $wpdb->update(
                "$tablename",
                array('Rating' => 0),
                array('Rating' => NULL),
                array('%d'),
                array('%d')
            );
        }
    }

}


?>