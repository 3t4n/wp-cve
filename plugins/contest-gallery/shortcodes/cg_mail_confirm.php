<?php

if(!function_exists('contest_gal1ery_check_confirmation_link')){

    function contest_gal1ery_check_confirmation_link($atts){

        // PLUGIN VERSION CHECK HERE
        contest_gal1ery_db_check();

        extract( shortcode_atts( array(
            'id' => ''
        ), $atts ) );
        $galeryID = trim($atts['id']);

        $entryId = 0;
        if(!empty($atts['entryId'])){
            $entryId = $atts['entryId'];
        }

        $wp_upload_dir = wp_upload_dir();
        $optionsFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/'.$galeryID.'-options.json';

        $shortcode_name = 'cg_mail_confirm';

        ob_start();

        if(file_exists($optionsFile)){

            include(__DIR__.'/../v10/v10-frontend/mail_confirm/mail_confirm_email_link.php');

        }
        else{

            $usedShortcode = 'cg_mail_confirm';

            include(__DIR__.'/../prev10/information.php');

        }


        $mail_confirm = ob_get_clean();

        return $mail_confirm;

    }
}

?>