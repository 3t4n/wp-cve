<?php

add_action('cg_registry_add_profile_image','cg_registry_add_profile_image');
if(!function_exists('cg_registry_add_profile_image')){
    function cg_registry_add_profile_image($filesKey, $WpUserId = 0, $isMediaHandleUploadOnly = false, $isReturnAttachId = false, $attach_id = 0){

        global $wpdb;
        $table_posts = $wpdb->prefix."posts";
        $tablename = $wpdb->base_prefix . "contest_gal1ery";

        if(empty($attach_id)){
            $dateityp = GetImageSize($_FILES[$filesKey]["tmp_name"][0]);

            /*$imageTypeArray = array
            (
                0=>'UNKNOWN',
                1=>'GIF',
                2=>'JPEG',
                3=>'PNG',
                4=>'SWF',
                5=>'PSD',
                6=>'BMP',
                7=>'TIFF_II',
                8=>'TIFF_MM',
                9=>'JPC',
                10=>'JP2',
                11=>'JPX',
                12=>'JB2',
                13=>'SWC',
                14=>'IFF',
                15=>'WBMP',
                16=>'XBM',
                17=>'ICO',
                18=>'COUNT'
            );*/

            if ($dateityp[2] != 1 && $dateityp[2] != 2 && $dateityp[2] != 3) {
                return false;
            }

            $post_data = array(
                'post_content' => "Contest Gallery user profile image"
            );

            $file = array(
                'name' => $_FILES[$filesKey]['name'][0],
                'type' => $_FILES[$filesKey]['type'][0],
                'tmp_name' => $_FILES[$filesKey]['tmp_name'][0],
                'error' => $_FILES[$filesKey]['error'][0],
                'size' => $_FILES[$filesKey]['size'][0]
            );

            $_FILES = array ($filesKey => $file);

            $attach_id = media_handle_upload($filesKey,0,$post_data);
        }

        if($isMediaHandleUploadOnly){
            if($isReturnAttachId){
                return $attach_id;
            }else{
                return true;
            }
        }

        $Version = cg_get_version_for_scripts();

        $wp_image_info = $wpdb->get_row("SELECT * FROM $table_posts WHERE ID = '$attach_id'");
        $post_type = $wp_image_info->post_mime_type;
        $post_title = $wp_image_info->post_title;
        $wp_image_id = $wp_image_info->ID;

        $imageInfoArray = wp_get_attachment_image_src($wp_image_id,'full');
        $current_width = $imageInfoArray[1];
        $current_height = $imageInfoArray[2];

        $unix = time();
        $unixadd = $unix+2;

        $dateityp = '';

        if(strpos($post_type,'jpg')!==false OR strpos($post_type,'jpeg')!==false){
            $dateityp = 'jpg';
        }else if(strpos($post_type,'png')!==false){
            $dateityp = 'png';
        }else if(strpos($post_type,'gif')!==false){
            $dateityp = 'gif';
        }

        $dateiname = $post_title;

        $userIP = sanitize_text_field(cg_get_user_ip());

        // updating string after all the 0 at the end does not work. That is why Version is not inserted there
        // default 0 to countr1-5 was added lately on 15.05.2020
        $wpdb->query( $wpdb->prepare(
            "
                INSERT INTO $tablename
                ( id, rowid, Timestamp, NamePic,
                ImgType, CountC, CountR, Rating,
                GalleryID, Active, Informed, WpUpload, Width, Height, WpUserId, IP,
                CountR1,CountR2,CountR3,CountR4,CountR5,IsProfileImage,Version)
                VALUES ( %s,%s,%d,%s,
                %s,%d,%s,%s,
                %d,%s,%s,%s,%s,%s,%s,%s,
                %d,%d,%d,%d,%d,%d,%s)
            ",
            '','',$unixadd,$dateiname,
            $dateityp,0,'','',
            0,'','',$wp_image_id,$current_width,$current_height,$WpUserId,$userIP,
            0,0,0,0,0,1,$Version
        ) );

        return true;

        // not required to use this logic in the moment
        //$imgSrcLarge = wp_get_attachment_image_src($wp_image_id, 'large');

    }
}


?>