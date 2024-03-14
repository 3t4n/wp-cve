<?php
add_action('cg_create_fb_sites','cg_create_fb_sites');
if(!function_exists('cg_create_fb_sites')){
    function cg_create_fb_sites($prevIDgallery,$nextIDgallery){// IMAGE ID Will be considered in this case. Thats why it is done so!

        global $wpdb;

        $tablename = $wpdb->prefix . "contest_gal1ery";
        $tablename_pro_options = $wpdb->prefix . "contest_gal1ery_pro_options";

        $proOptions = $wpdb->get_row( "SELECT * FROM $tablename_pro_options WHERE GalleryID = '$nextIDgallery'" );
        $DataShare = ($proOptions->FbLikeNoShare==1) ? 'false' : 'true';
        $DataClass = ($proOptions->FbLikeOnlyShare==1) ? 'fb-share-button' : 'fb-like';
        $DataLayout = ($proOptions->FbLikeOnlyShare==1) ? 'button' : 'button_count';

        $picsSQL = $wpdb->get_results("SELECT id, Timestamp, NamePic, ImgType, WpUpload FROM $tablename WHERE GalleryID = '$nextIDgallery' AND Active='1' ");
        $GalleryID = $nextIDgallery;
        $wp_blog_title = get_bloginfo('name');
        $wp_blog_description = get_bloginfo('description');
        $uploadFolder = wp_upload_dir();

        foreach($picsSQL as $object){

            $Timestamp = $object->Timestamp;
            $NamePic = $object->NamePic;

            $dirHTML = $uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$prevIDgallery.'/'.$Timestamp."_".$NamePic."413.html";

            // get blog title and description of old gallery!!!!
            if(file_exists($dirHTML)){
                $handle = fopen($dirHTML, "r");

                if ($handle) {

                    $isTitlePassed = false;
                    $isDescriptionPassed = false;

                    while (($line = fgets($handle)) !== false) {

                        if(!$isDescriptionPassed){
                            if($isTitlePassed){
                                if(strpos($line,'og:description')!==false){
                                    $isDescriptionPassed = true;
                                    // regex
                                    // preg match example from https://stackoverflow.com/questions/23343087/php-preg-match-return-position-of-last-match
                                    preg_match('/content="(.*?)" \/>/', (string)$line, $match);
                                    if(!empty($match)){
                                        $blog_description = $match[1];
                                    }else{
                                        $blog_description = $wp_blog_description;
                                    }
                                }
                            }
                            if(strpos($line,'property="og:title"')!==false){
                                $isTitlePassed = true;
                                // regex
                                // preg match example from https://stackoverflow.com/questions/23343087/php-preg-match-return-position-of-last-match
                                preg_match('/content="(.*?)" \/>/', (string)$line, $match);
                                if(!empty($match)){
                                    $blog_title = $match[1];
                                }else{
                                    $blog_title = $wp_blog_title;
                                }
                            }
                        }else{
                            break;
                        }

                    }

                    fclose($handle);

                }
            }else{
                $blog_title = $wp_blog_title;
                $blog_description = $wp_blog_description;
            }

            include(__DIR__.'/../../v10/v10-admin/gallery/change-gallery/4_2_fb-creation.php');

        }


    }
}


//Notice: Undefined offset: 1 in /home3/ralphpayne/public_html/wp-content/plugins/contest-gallery/v10/v10-admin/gallery/wp-uploader.php on line 45

//Notice: getimagesize(): Read error! in /home3/ralphpayne/public_html/wp-content/plugins/contest-gallery/v10/v10-admin/gallery/wp-uploader.php on line 64

