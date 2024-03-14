<?php
if(!function_exists('cg_user_data_registry_csv_new_export')){

    function cg_user_data_registry_csv_new_export(){
            if(!current_user_can('manage_options')){
                echo "Logged in user have to be able to manage_options to execute export.";die;
            }

        global $wpdb;

        $tablename = $wpdb->prefix . "contest_gal1ery";
        $tablename_contest_gal1ery_options = $wpdb->prefix . "contest_gal1ery_options";
        $tablename_contest_gal1ery_create_user_form = $wpdb->prefix . "contest_gal1ery_create_user_form";
        $userFormShort = $tablename_contest_gal1ery_create_user_form;
        $tablename_contest_gal1ery_create_user_entries = $wpdb->prefix . "contest_gal1ery_create_user_entries";
        $entriesShort = $tablename_contest_gal1ery_create_user_entries;
        $wpUsers = $wpdb->base_prefix . "users";
        $wpUsermeta = $wpdb->base_prefix . "usermeta";

        $cgSearchGalleryId = '';
        $cgSearchGalleryIdParam = '';

        if(!empty($_POST['cg-search-gallery-id-original']) OR !empty($_GET['cg-search-gallery-id-original'])){
            $cgSearchGalleryId = (!empty($_POST['cg-search-gallery-id-original'])) ? absint($_POST['cg-search-gallery-id-original']) : absint($_GET['cg-search-gallery-id-original']);
            $cgSearchGalleryIdParam = '&cg-search-gallery-id='.$cgSearchGalleryId;
        }

        $toSelect = "$wpUsers.ID, $wpUsers.user_login, $wpUsers.user_nicename, $wpUsers.user_email";

        if(!empty($_POST['cg-search-user-name-original']) OR !empty($_GET['cg-search-user-name-original'])){

            $cgUserName = (!empty($_POST['cg-search-user-name-original'])) ? sanitize_text_field(htmlentities(html_entity_decode($_POST['cg-search-user-name-original']))) : sanitize_text_field(htmlentities(html_entity_decode($_GET['cg-search-user-name'])));

            $cgUserName  = esc_sql( $wpdb->esc_like( $cgUserName ));

            if(!empty($cgSearchGalleryId)){

                $selectWPusers = $wpdb->get_results("SELECT DISTINCT $toSelect FROM $wpUsers, $entriesShort WHERE ($wpUsers.user_login LIKE '%$cgUserName%' OR $wpUsers.user_email LIKE '%$cgUserName%') AND ($wpUsers.ID = $entriesShort.wp_user_id AND $entriesShort.GalleryID = '$cgSearchGalleryId') ORDER BY $wpUsers.ID ASC");

                $selectWPusersFormFields = $wpdb->get_results("SELECT DISTINCT $userFormShort.* FROM $wpUsers,  $userFormShort, $entriesShort, $tablename_contest_gal1ery_options WHERE
                (
                    $userFormShort.Field_Type != 'main-user-name' AND 
                    $userFormShort.Field_Type != 'main-nick-name' AND 
                    $userFormShort.Field_Type != 'main-mail' AND 
                    $userFormShort.Field_Type != 'wpfn' AND 
                    $userFormShort.Field_Type != 'wpln' AND 
                    $userFormShort.Field_Type != 'password' AND 
                    $userFormShort.Field_Type != 'password-confirm' AND 
                    $userFormShort.Field_Type != 'user-robot-field' AND 
                    $userFormShort.Field_Type != 'user-robot-recaptcha-field' AND 
                    $userFormShort.GalleryID = $tablename_contest_gal1ery_options.id AND
                    $userFormShort.id = $entriesShort.f_input_id AND
                    $entriesShort.wp_user_id >= 1 AND
                    $wpUsers.ID = $entriesShort.wp_user_id AND
                    $entriesShort.GalleryID = '$cgSearchGalleryId' AND
                    ($wpUsers.user_login LIKE '%$cgUserName%' OR $wpUsers.user_email LIKE '%$cgUserName%')
                )
                ORDER BY GalleryID ASC, Field_Order DESC");

            }else{

                $selectWPusers = $wpdb->get_results("SELECT $toSelect FROM $wpUsers WHERE user_login LIKE '%$cgUserName%' OR user_email LIKE '%$cgUserName%' ORDER BY id ASC");

                $selectWPusersFormFields = $wpdb->get_results("SELECT DISTINCT $userFormShort.* FROM $wpUsers,  $userFormShort, $entriesShort, $tablename_contest_gal1ery_options WHERE
                (
                    $userFormShort.Field_Type != 'main-user-name' AND 
                    $userFormShort.Field_Type != 'main-nick-name' AND 
                    $userFormShort.Field_Type != 'main-mail' AND 
                    $userFormShort.Field_Type != 'wpfn' AND 
                    $userFormShort.Field_Type != 'wpln' AND 
                    $userFormShort.Field_Type != 'password' AND 
                    $userFormShort.Field_Type != 'password-confirm' AND 
                    $userFormShort.Field_Type != 'user-robot-field' AND 
                    $userFormShort.Field_Type != 'user-robot-recaptcha-field' AND 
                    $userFormShort.GalleryID = $tablename_contest_gal1ery_options.id AND
                    $userFormShort.id = $entriesShort.f_input_id AND
                    $entriesShort.wp_user_id >= 1 AND
                    $wpUsers.ID = $entriesShort.wp_user_id AND
                    ($wpUsers.user_login LIKE '%$cgUserName%' OR $wpUsers.user_email LIKE '%$cgUserName%')
                )
                ORDER BY GalleryID ASC, Field_Order DESC");

            }

        }else if(!empty($cgSearchGalleryId)){

            $selectWPusers = $wpdb->get_results("SELECT DISTINCT $toSelect FROM $wpUsers, $entriesShort WHERE $wpUsers.ID = $entriesShort.wp_user_id AND $entriesShort.GalleryID = '$cgSearchGalleryId' ORDER BY $wpUsers.ID ASC");

            $selectWPusersFormFields = $wpdb->get_results("SELECT DISTINCT $userFormShort.* FROM $wpUsers,  $userFormShort, $entriesShort, $tablename_contest_gal1ery_options WHERE
                (
                    $userFormShort.Field_Type != 'main-user-name' AND 
                    $userFormShort.Field_Type != 'main-nick-name' AND 
                    $userFormShort.Field_Type != 'main-mail' AND 
                    $userFormShort.Field_Type != 'wpfn' AND 
                    $userFormShort.Field_Type != 'wpln' AND 
                    $userFormShort.Field_Type != 'password' AND 
                    $userFormShort.Field_Type != 'password-confirm' AND 
                    $userFormShort.Field_Type != 'user-robot-field' AND 
                    $userFormShort.Field_Type != 'user-robot-recaptcha-field' AND 
                    $userFormShort.GalleryID = $tablename_contest_gal1ery_options.id AND
                    $userFormShort.id = $entriesShort.f_input_id AND
                    $entriesShort.wp_user_id >= 1 AND
                    $wpUsers.ID = $entriesShort.wp_user_id AND
                    $entriesShort.GalleryID = '$cgSearchGalleryId'
                )
                ORDER BY GalleryID ASC, Field_Order DESC");

        }else{

            $selectWPusers = $wpdb->get_results("SELECT $toSelect FROM $wpUsers ORDER BY id ASC");

            $selectWPusersFormFields = $wpdb->get_results("SELECT DISTINCT $userFormShort.* FROM $wpUsers,  $userFormShort, $entriesShort, $tablename_contest_gal1ery_options WHERE
                (
                    $userFormShort.Field_Type != 'main-user-name' AND 
                    $userFormShort.Field_Type != 'main-nick-name' AND 
                    $userFormShort.Field_Type != 'main-mail' AND 
                    $userFormShort.Field_Type != 'wpfn' AND 
                    $userFormShort.Field_Type != 'wpln' AND 
                    $userFormShort.Field_Type != 'password' AND 
                    $userFormShort.Field_Type != 'password-confirm' AND 
                    $userFormShort.Field_Type != 'user-robot-field' AND 
                    $userFormShort.Field_Type != 'user-robot-recaptcha-field' AND 
                    $userFormShort.GalleryID = $tablename_contest_gal1ery_options.id AND
                    $userFormShort.id = $entriesShort.f_input_id AND
                    $entriesShort.wp_user_id >= 1 AND
                    $wpUsers.ID = $entriesShort.wp_user_id
                )
                ORDER BY GalleryID ASC, Field_Order DESC");

        }

        $selectWPusersFormFieldsGeneralID = $wpdb->get_results("SELECT DISTINCT $userFormShort.* FROM  $userFormShort WHERE
                (
                    $userFormShort.Field_Type != 'main-user-name' AND 
                    $userFormShort.Field_Type != 'main-nick-name' AND 
                    $userFormShort.Field_Type != 'main-mail' AND 
                    $userFormShort.Field_Type != 'wpfn' AND 
                    $userFormShort.Field_Type != 'wpln' AND 
                    $userFormShort.Field_Type != 'password' AND 
                    $userFormShort.Field_Type != 'password-confirm' AND 
                    $userFormShort.Field_Type != 'user-robot-field' AND 
                    $userFormShort.Field_Type != 'user-robot-recaptcha-field' AND 
                    $userFormShort.GeneralID = 1
                )
                ORDER BY GeneralID ASC, Field_Order DESC");


        // $selectCGentries array sorted
        // main-user-name wird gewählt zur bestimmung von Gallery ID wo der user herkame. Ansonsten nicht vorhanden.
        $selectCGentries = $wpdb->get_results("SELECT DISTINCT * FROM $entriesShort WHERE
                (
                    $entriesShort.Field_Type != 'main-user-name' AND 
                    $entriesShort.Field_Type != 'main-nick-name' AND 
                    $entriesShort.Field_Type != 'main-mail' AND 
                    $entriesShort.Field_Type != 'wpfn' AND 
                    $entriesShort.Field_Type != 'wpln' AND 
                    $entriesShort.Field_Type != 'password' AND 
                    $entriesShort.Field_Type != 'password-confirm' AND 
                    $entriesShort.Field_Type != 'user-robot-field' AND 
                    $entriesShort.Field_Type != 'user-robot-recaptcha-field' AND 
                    $entriesShort.wp_user_id >= 1
                )
                ORDER BY wp_user_id ASC");

        $selectWpUsersFirstLastEntries = [];

        if(!empty($selectWPusers)){
            $userIdsQueryString = '';
            foreach($selectWPusers as $user){
                if(empty($userIdsQueryString)){
                    $userIdsQueryString .= 'user_id = '.$user->ID;
                }else{
                    $userIdsQueryString .= ' OR user_id = '.$user->ID;
                }
                $selectWpUsersFirstLastEntries = $wpdb->get_results("SELECT user_id, meta_key, meta_value FROM $wpUsermeta WHERE ($userIdsQueryString)  AND (meta_key = 'first_name' OR meta_key = 'last_name') ORDER BY user_id ASC");
            }
        }

        $selectWpUsersFirstLastEntriesArray = [];

        foreach ($selectWpUsersFirstLastEntries as $selectWpUsersFirstLastEntry){
            if(empty($selectWpUsersFirstLastEntriesArray[$selectWpUsersFirstLastEntry->user_id])){
                $selectWpUsersFirstLastEntriesArray[$selectWpUsersFirstLastEntry->user_id] = [];
            }
            if($selectWpUsersFirstLastEntry->meta_key=='first_name'){
                $selectWpUsersFirstLastEntriesArray[$selectWpUsersFirstLastEntry->user_id]['first_name']=$selectWpUsersFirstLastEntry->meta_value;
            }
            if($selectWpUsersFirstLastEntry->meta_key=='last_name'){
                $selectWpUsersFirstLastEntriesArray[$selectWpUsersFirstLastEntry->user_id]['last_name']=$selectWpUsersFirstLastEntry->meta_value;
            }
        }

        $selectUsermetaEntries = $wpdb->get_results("SELECT DISTINCT * FROM $wpUsermeta WHERE meta_key LIKE '%cg_custom_field_id_%' ORDER BY user_id ASC");

        // select profileImages
        $profileImagesArray = [];

        $selectProfileImage = $wpdb->get_row("SELECT * FROM $userFormShort WHERE GeneralID = '1' && 
                (Field_Type = 'profile-image')");


        if(!empty($selectProfileImage)){

            $collectForProfileImages = '';

            foreach ($selectWPusers as $user){
                $userId = $user->ID;
                if($collectForProfileImages==''){
                    $collectForProfileImages .= "WpUserId = ".$userId;
                }else{
                    $collectForProfileImages .= " OR WpUserId = ".$userId;
                }
            }

            $profileImages = $wpdb->get_results( "SELECT WpUserId, WpUpload FROM $tablename WHERE ($collectForProfileImages) AND (IsProfileImage = '1')");
            if(!empty($profileImages)){
                foreach ($profileImages as $image){
                    $imgSrcLarge=wp_get_attachment_image_src($image->WpUpload, 'large');
                    if(!empty($imgSrcLarge)){
                        $imgSrcLarge=$imgSrcLarge[0];
                        $profileImagesArray[$image->WpUserId] = $imgSrcLarge;
                    }
                }
            }

        }

        // select profileImages --- END

        // sorted by wp user id
        $selectWPusersEntriesArraySortedByWpUserIdAndFormFieldId = array();

        foreach($selectUsermetaEntries as $entry){
            if(empty($selectWPusersEntriesArraySortedByWpUserIdAndFormFieldId[$entry->user_id])){
                $selectWPusersEntriesArraySortedByWpUserIdAndFormFieldId[$entry->user_id] = array();
            }
            $selectWPusersEntriesArraySortedByWpUserIdAndFormFieldId[$entry->user_id][substr($entry->meta_key,strlen('cg_custom_field_id_'),strlen($entry->meta_key))] = $entry;
        }

        foreach($selectCGentries as $entry){
            if(empty($selectWPusersEntriesArraySortedByWpUserIdAndFormFieldId[$entry->wp_user_id])){
                $selectWPusersEntriesArraySortedByWpUserIdAndFormFieldId[$entry->wp_user_id] = array();
            }
            $selectWPusersEntriesArraySortedByWpUserIdAndFormFieldId[$entry->wp_user_id][$entry->f_input_id] = $entry;
        }

        foreach($profileImagesArray as $WpUserId => $imgSrcLarge){
            if(empty($selectWPusersEntriesArraySortedByWpUserIdAndFormFieldId[$WpUserId])){
                $selectWPusersEntriesArraySortedByWpUserIdAndFormFieldId[$WpUserId] = array();
            }
            $entry = new stdClass();
            $entry->imgSrcLarge = $imgSrcLarge;
            $selectWPusersEntriesArraySortedByWpUserIdAndFormFieldId[$WpUserId][$selectProfileImage->id] = $entry;
        }

        $selectWPusersFormFields =  (object) array_merge((array) $selectWPusersFormFieldsGeneralID, (array) $selectWPusersFormFields);

        // sorted by form field id
        // sicherheitscheck falls alte Gallerie gelöscht wurde

        $selectWPusersFormFieldsSortedById = array();

        foreach($selectWPusersFormFieldsGeneralID as $formField){

            $selectWPusersFormFieldsSortedById[$formField->id] = $formField;

        }

        foreach($selectWPusersFormFields as $formField){
            $selectWPusersFormFieldsSortedById[$formField->id] = $formField;
        }

        // $selectCGentries array sorted -- ENDE

        $wp_roles = wp_roles();

        $csvData = array();

        $i=0;
        $r=0;

        $csvData[$i][$r]="WP user id";
        $r++;
        $csvData[$i][$r]="WP username";
        $r++;
        $csvData[$i][$r]="WP nickname";
        $r++;
        $csvData[$i][$r]="WP first name";
        $r++;
        $csvData[$i][$r]="WP last name";
        $r++;
        $csvData[$i][$r]="WP email";
        $r++;
        $csvData[$i][$r]="WP role";

        $headerColumnsArray = array();

        // ACHTUNG!!!! ZWEI Varianten hier. Einmal wenn es keine zusätzlichen UserDaten gibt und einmal wenn es welche gibt
        if(!empty($selectWPusersFormFields)) {

            // add further column header names, but only from existing and selected entries fields
            if(!empty($selectWPusersFormFields)){
            foreach($selectWPusersFormFields as $formField){
                $r++;
                    if(!empty( $formField->GalleryID)){
                $GalleryIDtoWrite = $formField->GalleryID;
                $csvData[$i][$r] = $formField->Field_Name." (gallery id = $GalleryIDtoWrite)";
                    }else{// then must be general id
                        $csvData[$i][$r] = $formField->Field_Name;
                    }


                $headerColumnsArray[$formField->id] = $r;
            }
            }

            foreach($selectWPusers as $user){

                $i++;

                $csvData[$i][0] = $user->ID;
                $csvData[$i][1] = $user->user_login;
                $csvData[$i][2] = $user->user_nicename;
                $csvData[$i][3] = '';
                if(!empty($selectWpUsersFirstLastEntriesArray[$user->ID])){
                    if(!empty($selectWpUsersFirstLastEntriesArray[$user->ID]['first_name'])){
                        $csvData[$i][3] = $selectWpUsersFirstLastEntriesArray[$user->ID]['first_name'];
                    }
                }
                $csvData[$i][4] = '';
                if(!empty($selectWpUsersFirstLastEntriesArray[$user->ID])){
                    if(!empty($selectWpUsersFirstLastEntriesArray[$user->ID]['last_name'])){
                        $csvData[$i][4] = $selectWpUsersFirstLastEntriesArray[$user->ID]['last_name'];
                    }
                }
                $csvData[$i][5] = $user->user_email;

                $user_meta=get_userdata($user->ID);

                $user_roles=$user_meta->roles; //array of roles the user is part of.

                if(!empty($user_roles[0])){
                    $firstRoleKey = $user_roles[0];
                    $firstRoleName = $wp_roles->roles[$firstRoleKey]['name'];
                }else{
                    $firstRoleName = '';
                }

                $csvData[$i][6] = $firstRoleName;

                if(!empty($selectWPusersEntriesArraySortedByWpUserIdAndFormFieldId[$user->ID])){

                    // einfach erstes array element nehmen und dann die GalleryId
                    //$csvData[$i][4] = $selectWPusersEntriesArraySortedByWpUserIdAndFormFieldId[$user->ID][key($selectWPusersEntriesArraySortedByWpUserIdAndFormFieldId[$user->ID])]->GalleryID;

                    // CSV Array muss bei spalten fortgesetzt werden, wenn 8,9,10 nicht vorhanden sind dann wird immer bei 7 geendet
                    foreach($headerColumnsArray as $formFieldId => $count){
                        $csvData[$i][$count] = '';
                    };

                    foreach($selectWPusersEntriesArraySortedByWpUserIdAndFormFieldId[$user->ID] as $formFieldId => $entry){
                        // sicherheitscheck falls alte Gallerie gelöscht wurde
                        if(empty($selectWPusersFormFieldsSortedById[$formFieldId])){
                            continue;
                        }

                        // empty check has to be done otherwise undefined property error might be for Field_Type
                        if(!empty($entry->Field_Type) && $entry->Field_Type=='user-check-agreement-field'){
                            if($entry->Checked==1 OR empty($entry->Version)){// For old versions Version was always empty
                                $csvData[$i][$headerColumnsArray[$formFieldId]] = 'checked';
                            }else{
                                $csvData[$i][$headerColumnsArray[$formFieldId]] = 'not checked';
                            }
                        }else{
                            if(!empty($entry->meta_value)){
                                if(strpos($entry->meta_value,'yes(cg-user-checked) ---')!==false){
                                    $csvData[$i][$headerColumnsArray[$formFieldId]] = 'checked';
                                }else if(strpos($entry->meta_value,'yes(cg-user-not-checked) ---')!==false){
                                    $csvData[$i][$headerColumnsArray[$formFieldId]] = 'not checked';
                                }else{
                                    $csvData[$i][$headerColumnsArray[$formFieldId]] = $entry->meta_value;
                                }
                            }else if(!empty($entry->imgSrcLarge)){
                            $csvData[$i][$headerColumnsArray[$formFieldId]] = $entry->imgSrcLarge;
                            }else if(!empty($entry->Field_Content)){
                            $csvData[$i][$headerColumnsArray[$formFieldId]] = $entry->Field_Content;
                            }
                        }
                    };

                }
                // hier gehts weiter
            }
        }

        // ACHTUNG!!!! ZWEI Varianten hier. Einmal wenn es keine zusätzlichen UserDaten gibt und einmal wenn es welche gibt
        if(empty($selectWPusersFormFields)){

            foreach($selectWPusers as $user){
                $i++;

                $csvData[$i][0] = $user->ID;
                $csvData[$i][1] = $user->user_login;
                $csvData[$i][2] = $user->user_nicename;
                $csvData[$i][3] = '';
                if(!empty($selectWpUsersFirstLastEntriesArray[$user->ID])){
                    if(!empty($selectWpUsersFirstLastEntriesArray[$user->ID]['first_name'])){
                        $csvData[$i][3] = $selectWpUsersFirstLastEntriesArray[$user->ID]['first_name'];
                    }
                }
                $csvData[$i][4] = '';
                if(!empty($selectWpUsersFirstLastEntriesArray[$user->ID])){
                    if(!empty($selectWpUsersFirstLastEntriesArray[$user->ID]['last_name'])){
                        $csvData[$i][4] = $selectWpUsersFirstLastEntriesArray[$user->ID]['last_name'];
                    }
                }
                $csvData[$i][5] = $user->user_email;

                $user_meta=get_userdata($user->ID);

                $user_roles=$user_meta->roles; //array of roles the user is part of.

                if(!empty($user_roles[0])){
                    $firstRoleKey = $user_roles[0];
                    $firstRoleName = $wp_roles->roles[$firstRoleKey]['name'];
                }else{
                    $firstRoleName = '';
                }

                $csvData[$i][6] = $firstRoleName;


            }

        }

        // old logic do not remove

        $filename = "wordpress-users-export-from-contest-gallery.csv";

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