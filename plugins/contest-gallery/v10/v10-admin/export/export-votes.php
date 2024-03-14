<?php
if(!function_exists('cg_votes_csv_export')){

    function cg_votes_csv_export(){

        if(!current_user_can('manage_options')){
            echo "Logged in user have to be able to manage_options to execute export votes.";die;
        }

        global $wpdb;

        $tablename = $wpdb->prefix . "contest_gal1ery";
        $tablename_ip = $wpdb->prefix . "contest_gal1ery_ip";
        $tablename_categories = $wpdb->prefix . "contest_gal1ery_categories";
        $tablename_options = $wpdb->prefix . "contest_gal1ery_options";

        $wpPosts = $wpdb->base_prefix . "posts";
        $wpUsers = $wpdb->base_prefix . "users";

        $imageId = absint($_POST['cg_picture_id']);
        $GalleryID = absint($_POST['cg_option_id']);

        $categories = $wpdb->get_results( "SELECT * FROM $tablename_categories WHERE GalleryID = '$GalleryID' ORDER BY Field_Order DESC");

        $generalOptions = $wpdb->get_row("SELECT * FROM $tablename_options WHERE id = '$GalleryID'");
        $AllowRating = $generalOptions->AllowRating;
        if($AllowRating==1){
            $AllowRating = 15;
        }
        $AllowRatingMax = 0;// define variable simple
        if($AllowRating>=12 AND $AllowRating<=20){
            $AllowRatingMax = $AllowRating-10;// set some value here
        }

        // for check-language.php
        $galeryID = $GalleryID;

        include(__DIR__ ."/../../../check-language.php");

        $categoriesUidsNames = array();

        if(count($categories)){

            $categoriesUidsNames = array();

            $categoriesUidsNames[0] = $language_Other;

            foreach ($categories as $category) {

                $categoriesUidsNames[$category->id] = $category->Name;

            }

        }

        $imageObject = $wpdb->get_row( "SELECT * FROM $tablename WHERE id = '$imageId'");

        $multipleRatingQueryString = '';

        if($AllowRatingMax){
            $multipleRatingQueryString = " OR (Rating>=1 && Rating<=$AllowRatingMax)";
        }

        $votingData = $wpdb->get_results("SELECT id, Tstamp, VoteDate, IP, Rating, RatingS, WpUserId, OptionSet, CookieId, Category,CategoriesOn FROM $tablename_ip WHERE pid = '$imageId' AND (RatingS = 1$multipleRatingQueryString)  ORDER BY id DESC");

        $wpPostsId = $imageObject->WpUpload;

        $imageWpObject = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpPosts WHERE ID = %d",[$wpPostsId]));

        $wpUserIdsArray = array();

        if(count($votingData)){
            foreach($votingData as $row){

                if(!empty($row->WpUserId)){
                    $wpUserIdsArray[$row->WpUserId] = true;
                }
            }
        }

        $userIdsSelectString = '';

        if(count($wpUserIdsArray)){

            foreach($wpUserIdsArray as $id => $bool){
                if(empty($userIdsSelectString)){
                    $userIdsSelectString .= "ID = $id";
                }else{
                    $userIdsSelectString .= " OR ID = $id";
                }
            }

            $wpUsersData = $wpdb->get_results("SELECT ID, user_login, user_email FROM $wpUsers WHERE $userIdsSelectString ORDER BY ID ASC");

            foreach($wpUsersData as $row){
                $wpUserIdsArray[$row->ID] = array();
                $wpUserIdsArray[$row->ID]['user_login'] = $row->user_login;
                $wpUserIdsArray[$row->ID]['user_email'] = $row->user_email;
            }

        }


        $csvData = array();

        $csvData[0][0] = 'gallery id: '.$GalleryID;
        $csvData[1][0] = 'file id: '.$imageId;
        $csvData[2][0] = 'file name: '.$imageWpObject->post_title;
        $csvData[4][0] = 'file url: '.$imageWpObject->guid;

        $csvData[5][0] = 'User recognition method';
        $csvData[5][1] = 'vote id';
        $csvData[5][2] = 'IP';
        $csvData[5][3] = 'Cookie id';
        $csvData[5][4] = 'Category of image as voting was done - id (name)';
        $csvData[5][5] = 'Rating one star';
        $csvData[5][6] = 'Rating multiple stars (displayed only if activated)';
        $csvData[5][7] = 'WordPress user id';
        $csvData[5][8] = 'WordPress user name';
        $csvData[5][9] = 'WordPress user email';
        $csvData[5][10] = 'Vote date';

        $i=6;
        $r=0;

        foreach($votingData as $value) {

            $csvData[$i][$r] = $value->OptionSet;
            $r++;
            $csvData[$i][$r] = $value->id;
            $r++;
            $csvData[$i][$r] = $value->IP;
            $r++;
            $csvData[$i][$r] = $value->CookieId;
            $r++;

            // Categories were available in that time when CategoriesOn not empty
            if (!empty($value->CategoriesOn)){
                $category = (!empty($categoriesUidsNames[$value->Category])) ? $value->Category.' ('.$categoriesUidsNames[$value->Category].')' : $value->Category.' (deleted category)';
            }else{
                $category = '';
            }
            $csvData[$i][$r] = $category;
            $r++;

            $csvData[$i][$r] = (!empty($value->RatingS)) ? $value->RatingS : '';
            $r++;

            $csvData[$i][$r] = (!empty($value->Rating)) ? $value->Rating : '';
            $r++;

            $csvData[$i][$r] = (!empty($value->WpUserId)) ? $value->WpUserId : '';
            $r++;

            $username = (!empty($value->WpUserId)) ? $wpUserIdsArray[$value->WpUserId]['user_login'] : "";

            $csvData[$i][$r] = $username;
            $r++;

            $useremail = (!empty($value->WpUserId)) ? $wpUserIdsArray[$value->WpUserId]['user_email'] : "";

            $csvData[$i][$r] = $useremail;
            $r++;

            $csvData[$i][$r] = cg_get_time_based_on_wp_timezone_conf($value->Tstamp,'d-M-Y H:i:s');
            $r++;
            $i++;

        }

        $filename = "cg-votes-gallery-id-$GalleryID-image-id-$imageId.csv";

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=$filename");

        ob_start();

        $fp = fopen("php://output", 'w');
        fputs($fp, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
        foreach ($csvData as $fields) {
            fputcsv($fp, $fields, ";");

        }
        fclose($fp);
        $masterReturn = ob_get_clean();
        echo $masterReturn;
        die();
    }


}


?>