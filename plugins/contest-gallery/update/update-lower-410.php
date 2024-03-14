<?php

if(!function_exists('cg_update_lower_410')){

    function cg_update_lower_410($i){

        global $wpdb;

        $tablename = $wpdb->base_prefix . "$i"."contest_gal1ery";

        $p_cgal1ery_db_installed_ver = get_option( "p_cgal1ery_db_version" );
        $p_cgal1ery_db_installed_ver = floatval($p_cgal1ery_db_installed_ver);

        if($p_cgal1ery_db_installed_ver<4.10){

            $wpdb->query("ALTER TABLE $tablename MODIFY COLUMN CountC INT(7)");
            $wpdb->query("ALTER TABLE $tablename MODIFY COLUMN CountR INT(7)");
            $wpdb->query("ALTER TABLE $tablename MODIFY COLUMN CountS INT(7)");
            $wpdb->query("ALTER TABLE $tablename MODIFY COLUMN Rating INT(13)");
        }
    }

}


?>