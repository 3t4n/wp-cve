<?php
if(!function_exists('cg_votes_csv_export_all')){
    function cg_votes_csv_export_all(){

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

        $GalleryID = absint($_POST['cg_option_id']);

        $generalOptions = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tablename_options WHERE id = %d",[$GalleryID]));

        $AllowRating = $generalOptions->AllowRating;
        if($AllowRating==1){
            $AllowRating = 15;
        }
        $AllowRatingMax = 0;// define variable simple
        if($AllowRating>=12 AND $AllowRating<=20){
            $AllowRatingMax = $AllowRating-10;// set some value here
        }

        $categories = $wpdb->get_results($wpdb->prepare("SELECT * FROM $tablename_categories WHERE GalleryID = %d ORDER BY Field_Order DESC",[$GalleryID]));

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

        $getWpUserIds = $wpdb->get_results($wpdb->prepare("
                SELECT DISTINCT WpUserId
                 FROM  $tablename_ip
                 WHERE GalleryID = %d AND WpUserId > 0
				GROUP BY WpUserId 
				ORDER BY WpUserId DESC
        ",[$GalleryID]));


        $selectQuery = '';
        $wpUsersDataArray = [];

        if(count($getWpUserIds)){
            foreach ($getWpUserIds as $objectRow){
                if(empty($selectQuery)){
                    $selectQuery = 'ID = '.$objectRow->WpUserId;
                }else{
                    $selectQuery .= ' OR ID = '.$objectRow->WpUserId;
                }
            }

            $getWpUserEmailAndLogin = $wpdb->get_results("
                    SELECT DISTINCT ID, user_email, user_login
                     FROM  $wpUsers
                     WHERE $selectQuery
                    GROUP BY ID 
                    ORDER BY ID DESC
            ");

            if(count($getWpUserEmailAndLogin)){
                foreach ($getWpUserEmailAndLogin as $objectRow){
                    $wpUsersDataArray[$objectRow->ID] = [];
                    $wpUsersDataArray[$objectRow->ID]['user_login'] = $objectRow->user_login;
                    $wpUsersDataArray[$objectRow->ID]['user_email'] = $objectRow->user_email;
                }
            }

        }

        $multipleRatingQueryString = '';

        if($AllowRatingMax){
            $multipleRatingQueryString = " OR ($tablename_ip.Rating>=1 && $tablename_ip.Rating<=$AllowRatingMax)";
        }

        // always admin user_email and admin user_login as result in this query, this why $wpUsersDataArray will be created and used
        $votingData = $wpdb->get_results($wpdb->prepare("SELECT DISTINCT 
                 $wpUsers.user_login, $wpUsers.user_email,  
                 $wpPosts.post_title, $wpPosts.guid, 
                $tablename.id as pid, $tablename.WpUpload, 
                $tablename_ip.id as ipId, $tablename_ip.pid, $tablename_ip.Tstamp, $tablename_ip.VoteDate, $tablename_ip.IP, $tablename_ip.Rating, $tablename_ip.RatingS, $tablename_ip.WpUserId, $tablename_ip.OptionSet, $tablename_ip.CookieId, $tablename_ip.Category, $tablename_ip.CategoriesOn 
                FROM $tablename, $tablename_ip, $wpPosts, $wpUsers WHERE 
				(($tablename_ip.GalleryID = %d AND $tablename_ip.pid = $tablename.id AND $tablename.WpUpload = $wpPosts.ID AND ($tablename_ip.RatingS = 1$multipleRatingQueryString)) OR 
				($tablename_ip.GalleryID = %d AND $tablename_ip.pid = $tablename.id AND $tablename.WpUpload = $wpPosts.ID AND $tablename.WpUserId = $wpUsers.ID AND ($tablename_ip.RatingS = 1$multipleRatingQueryString)) OR 
				($tablename_ip.GalleryID = %d AND $tablename_ip.pid = $tablename.id AND $tablename.WpUpload = $wpPosts.ID AND $tablename_ip.WpUserId = $wpUsers.ID AND ($tablename_ip.RatingS = 1$multipleRatingQueryString))) 
				OR 
				(($tablename_ip.GalleryID = %d AND $tablename_ip.pid = $tablename.id AND $tablename.WpUpload = 0 AND ($tablename_ip.RatingS = 1$multipleRatingQueryString)) OR 
				($tablename_ip.GalleryID = %d AND $tablename_ip.pid = $tablename.id AND $tablename.WpUpload = 0 AND $tablename.WpUserId = $wpUsers.ID AND ($tablename_ip.RatingS = 1$multipleRatingQueryString)) OR 
				($tablename_ip.GalleryID = %d AND $tablename_ip.pid = $tablename.id AND $tablename.WpUpload = 0 AND $tablename_ip.WpUserId = $wpUsers.ID AND ($tablename_ip.RatingS = 1$multipleRatingQueryString)))  
				GROUP BY $tablename_ip.id 
				ORDER BY $tablename_ip.id DESC
                 ",[$GalleryID,$GalleryID,$GalleryID,$GalleryID,$GalleryID,$GalleryID]));

        $csvData = array();

        $csvData[0][0] = 'gallery id: '.$GalleryID;
        $csvData[1][0] = 'entry id';
        $csvData[1][1] = 'file name';
        $csvData[1][2] = 'file url';
        $csvData[1][3] = 'User recognition method';
        $csvData[1][4] = 'vote id';
        $csvData[1][5] = 'IP';
        $csvData[1][6] = 'Cookie id';
        $csvData[1][7] = 'Category of image as voting was done - id (name)';
        $csvData[1][8] = 'Rating one star';
        $csvData[1][9] = 'Rating multiple stars';
        $csvData[1][10] = 'WordPress user id';
        $csvData[1][11] = 'WordPress user name';
        $csvData[1][12] = 'WordPress user email';
        $csvData[1][13] = 'Vote date';

        $i=2;
        $r=0;

        foreach($votingData as $value) {

            $csvData[$i][$r] = $value->pid;
            $r++;
            $csvData[$i][$r] = (!empty($value->WpUpload)) ? $value->post_title : '';// check by WpUpload is better
            $r++;
            $csvData[$i][$r] = (!empty($value->WpUpload)) ? $value->guid : '';// check by WpUpload is better
            $r++;
            $csvData[$i][$r] = $value->OptionSet;
            $r++;
            $csvData[$i][$r] = $value->ipId;
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

            $username = (!empty($wpUsersDataArray[$value->WpUserId])) ? $wpUsersDataArray[$value->WpUserId]['user_login'] : "";

            $csvData[$i][$r] = $username;
            $r++;

            $useremail = (!empty($wpUsersDataArray[$value->WpUserId])) ? $wpUsersDataArray[$value->WpUserId]['user_email'] : "";

            $csvData[$i][$r] = $useremail;
            $r++;

            $csvData[$i][$r] = cg_get_time_based_on_wp_timezone_conf($value->Tstamp,'d-M-Y H:i:s');
            $r++;

            $i++;

        }

        $filename = "cg-votes-gallery-id-$GalleryID.csv";

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