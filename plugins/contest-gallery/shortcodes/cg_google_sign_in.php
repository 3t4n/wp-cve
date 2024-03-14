<?php

if(!function_exists('contest_gal1ery_google_sign_in')){

    function contest_gal1ery_google_sign_in($atts){

        contest_gal1ery_db_check();

        extract( shortcode_atts( array(
            'id' => ''
        ), $atts ) );
        $GalleryID = trim($atts['id']);

        $entryId = 0;
        if(!empty($atts['entryId'])){
            $entryId = $atts['entryId'];
        }

        $shortcode_name = 'cg_google_sign_in';

        if(is_admin()){// no execution in admin area
            return '';
        }

        if(strpos(cg_get_version_for_scripts(),'PRO')===FALSE){
            echo "<p style='text-align: center;font-weight: bold;'>Works only in PRO version</p>";
            return '';
        }

    }

}

?>