<?php
add_action( 'delete_user', 'cg1l_pre_delete_user');
if(!function_exists('cg1l_pre_delete_user')){
    function cg1l_pre_delete_user( $user_id) {

        $user_id = intval($user_id);

        /*    echo "<pre>";
        print_r($_POST);
        echo "</pre>";
        die;*/

        if($_POST['delete_option'] == 'delete' OR $_POST['delete_option'] == 'reassign'){

            global $wpdb;

            $tablename = $wpdb->prefix . "contest_gal1ery";
            $tablenameEntries = $wpdb->prefix . "contest_gal1ery_entries";
            $tablenameIp = $wpdb->prefix . "contest_gal1ery_ip";
            $contest_gal1ery_create_user_entries = $wpdb->prefix . "contest_gal1ery_create_user_entries";
            $tablename_contest_gal1ery_google_users = $wpdb->prefix."contest_gal1ery_google_users";
            $tablenameComments = $wpdb->prefix . "contest_gal1ery_comments";
            $wp_usermeta_table = $wpdb->prefix . "usermeta";

            // do json files before by selecting all image ids with galleries, group by galleries
            // insert in all galeries user id which was deleted, files imporved then by load in frontend
            // also all json info file repair if empty frontend
            $galleryIDsOfUser = $wpdb->get_results("SELECT DISTINCT id, GalleryID FROM $tablename WHERE WpUserId = $user_id group by id order by GalleryID ASC");

            $wp_upload_dir = wp_upload_dir();

            $collect = '';
            $collectGalleryIDsAndImagesArray = array();

            foreach ($galleryIDsOfUser as $rowObject){// collect query here also

                if(empty($collectGalleryIDsAndImagesArray[$rowObject->GalleryID])){
                    $collectGalleryIDsAndImagesArray[$rowObject->GalleryID] = array();
                }

                // collect image ids for gallery
                $collectGalleryIDsAndImagesArray[$rowObject->GalleryID][]=$rowObject->id;

                // collect query here also
                if($collect==''){
                    $collect .= "pid = $rowObject->id";
                }else{
                    $collect .= " OR pid = $rowObject->id";
                }

            }

            if($_POST['delete_option'] == 'delete'){

                foreach ($collectGalleryIDsAndImagesArray as $GalleryID => $imageIDs){

                    $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-deleted-image-ids.json';
                    if(file_exists($jsonFile)){
                        $fp = fopen($jsonFile, 'r');
                        $json = json_decode(fread($fp, filesize($jsonFile)),true);
                        fclose($fp);
                    }else{
                        $json = array();
                    }

                    foreach ($imageIDs as $imageID){
                        $json[] = $imageID;
                    }

                    $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-deleted-image-ids.json';
                    $fp = fopen($jsonFile, 'w');
                    fwrite($fp, json_encode($json));
                    fclose($fp);

                }

                // search for WpUserId
                $wpdb->query($wpdb->prepare(
                    "
				DELETE FROM $tablename WHERE WpUserId = %d
			",
                    $user_id
                ));

                // search for wp_user_id
                $wpdb->query($wpdb->prepare(
                    "
				DELETE FROM $contest_gal1ery_create_user_entries WHERE wp_user_id = %d
			",
                    $user_id
                ));

                if(!empty($collect)){
                    $wpdb->query( "DELETE FROM  $tablenameEntries WHERE $collect");
                    $wpdb->query( "DELETE FROM  $tablenameIp WHERE $collect");
                    $wpdb->query( "DELETE FROM  $tablenameComments WHERE $collect");
                }

                // firstname, lastname description and so on are official wordpress fields and will be deleted from wordpress
                $wpdb->query("DELETE FROM $wp_usermeta_table WHERE meta_key LIKE '%cg_custom_field_id_%' AND user_id='$user_id' ");

                // delete from eventually cg google users table
                $wpdb->query("DELETE FROM $tablename_contest_gal1ery_google_users WHERE WpUserId = '$user_id' ");

            }

            if($_POST['delete_option'] == 'reassign' AND !empty($_POST['reassign_user'])){

                $reassign_user_id = intval($_POST['reassign_user']);

                // User "Edit options" >>> "Status and repair" >>> "Repair frontend" if somebeody asks relating display_name of images in frontend

                $wpdb->query("UPDATE $tablename SET WpUserId=$reassign_user_id WHERE WpUserId = $user_id");

                // search for wp_user_id
                $wpdb->query($wpdb->prepare(
                    "
				DELETE FROM $contest_gal1ery_create_user_entries WHERE wp_user_id = %d
			",
                    $user_id
                ));

                // update in eventually cg google users table
                $wpdb->query("UPDATE $tablename_contest_gal1ery_google_users SET WpUserId=$reassign_user_id WHERE WpUserId = $user_id");

            }

        }


    }
}

