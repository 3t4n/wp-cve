<?php

if(!function_exists('contest_gal1ery_users_contact')){

    function contest_gal1ery_users_contact($atts){

        // PLUGIN VERSION CHECK HERE

        contest_gal1ery_db_check();

        if(is_admin()){
            return '';
        }

        extract( shortcode_atts( array(
            'id' => ''
        ), $atts ) );
        $galeryID = trim($atts['id']);

        $entryId = 0;
        if(!empty($atts['entryId'])){
            $entryId = $atts['entryId'];
        }

        $frontend_gallery = '';

        $shortcode_name = 'cg_users_contact';

        $wp_upload_dir = wp_upload_dir();
        $optionsFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/'.$galeryID.'-options.json';

        if(file_exists($optionsFile)){

            $isReallyContactForm = true;

            $options = json_decode(file_get_contents($optionsFile),true);

            include(__DIR__.'/../v10/include-scripts-v10.php');

        }
        else{

            $usedShortcode = 'cg_users_contact';
            include(__DIR__.'/../prev10/information.php');

        }

        return $frontend_gallery;

    }

}

?>