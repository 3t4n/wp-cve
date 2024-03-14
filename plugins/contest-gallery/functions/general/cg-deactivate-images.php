<?php

add_action('cg_deactivate_images','cg_deactivate_images');
if(!function_exists('cg_deactivate_images')){
    function cg_deactivate_images($GalleryID,$wp_upload_dir,$idsArrayTodeactivate){

        global $wpdb;

        $tablename = $wpdb->prefix . "contest_gal1ery";

        // erst mal alle deaktivieren, die deaktiviert gehören!!!

        $querySETrowDeactivate = 'UPDATE ' . $tablename . ' SET Active = CASE';
        $querySETaddRowDeactivate = ' ELSE Active END WHERE (id) IN (';
        $queryArgsArray = [];
        $queryAddArgsArray = [];
        $queryArgsCounter = 0;

        //foreach($_POST['cg_deactivate'] as $key => $value){
        foreach($idsArrayTodeactivate as $key => $value){

            $key = absint(sanitize_text_field($key));

            $querySETrowDeactivate .= " WHEN (id = %d) THEN 0";
            $querySETaddRowDeactivate .= "(%d), ";
            $queryArgsArray[] = $key;
            $queryAddArgsArray[] = $key;
            $queryArgsCounter++;

            if(!empty($imageArray[$key])){
                unset($imageArray[$key]);
            }

            if(file_exists($wp_upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-data/image-data-".$key.".json")){
                unlink($wp_upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-data/image-data-".$key.".json");
            }
            // since v16.0.0 no need to deactivate anymore, json comments files can stay existed, because backup is also in json files
            /*        if(file_exists($wp_upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-comments/image-comments-".$key.".json")){
                        unlink($wp_upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-comments/image-comments-".$key.".json");
                    }*/
            if(file_exists($wp_upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-info/image-info-".$key.".json")){
                unlink($wp_upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-info/image-info-".$key.".json");
            }

        }

        // ic = i counter
        for ($ic = 0;$ic<$queryArgsCounter;$ic++){
            $queryArgsArray[] =$queryAddArgsArray[$ic];
        }

        $querySETaddRowDeactivate = substr($querySETaddRowDeactivate,0,-2);
        $querySETaddRowDeactivate .= ")";

        $querySETrowDeactivate .= $querySETaddRowDeactivate;

        $wpdb->query($wpdb->prepare($querySETrowDeactivate,$queryArgsArray));

    }
}



?>