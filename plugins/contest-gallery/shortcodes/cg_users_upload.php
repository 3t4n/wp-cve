<?php

if(!function_exists('contest_gal1ery_users_upload')){

    function contest_gal1ery_users_upload($atts){

        // PLUGIN VERSION CHECK HERE

        contest_gal1ery_db_check();

        if(is_admin()){
            return '';
        }

        $shortcode_name = 'cg_users_upload';

        extract( shortcode_atts( array(
            'id' => ''
        ), $atts ) );
        $galeryID = trim($atts['id']);

        $entryId = 0;
        if(!empty($atts['entryId'])){
            $entryId = $atts['entryId'];
        }

        $frontend_gallery = '';

        $wp_upload_dir = wp_upload_dir();
        $optionsFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/'.$galeryID.'-options.json';

        if(file_exists($optionsFile)){

            $isReallyUploadForm = true;
            $options = json_decode(file_get_contents($optionsFile),true);

            include(__DIR__.'/../v10/include-scripts-v10.php');

        }
        else{

            $usedShortcode = 'cg_users_upload';
            include(__DIR__.'/../prev10/information.php');

        }

        return $frontend_gallery;

    }

}

?>