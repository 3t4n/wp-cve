<?php
if(!function_exists('cg_user_data_registry_csv_export')){

    function cg_user_data_registry_csv_export(){

            if(!current_user_can('manage_options')){
                echo "Logged in user have to be able to manage_options to execute export.";die;
            }

        global $wpdb;

        $tablename_contest_gal1ery_options = $wpdb->prefix . "contest_gal1ery_options";
        $tablename_contest_gal1ery_create_user_form = $wpdb->prefix . "contest_gal1ery_create_user_form";
        $tablename_contest_gal1ery_create_user_entries = $wpdb->prefix . "contest_gal1ery_create_user_entries";
        $entriesShort = $tablename_contest_gal1ery_create_user_entries;
        $wpUsers = $wpdb->base_prefix . "users";

// Tabellennamen bestimmen

        if(!empty($_GET['wp_uid'])){$selectWPusers = $wpdb->get_results("SELECT DISTINCT * FROM $wpUsers WHERE ID='".@$_GET['wp_uid']."' ORDER BY id ASC");}
        else if(empty($_POST['cg-user-name']) AND !empty($_POST['galleryIdToSelect'])){

            $selectWPusers = $wpdb->get_results("SELECT DISTINCT  $wpUsers.* FROM $wpUsers, $entriesShort WHERE $wpUsers.ID=$entriesShort.wp_user_id AND $entriesShort.GalleryID='".$_POST['galleryIdToSelect']."'");

        }
        else if(!empty($_POST['cg-user-name']) AND empty($_POST['galleryIdToSelect'])){
            // var_dump(1);
            $selectWPusers = $wpdb->get_results("SELECT DISTINCT  $wpUsers.* FROM $wpUsers WHERE user_login LIKE '%".@$_POST['cg-user-name']."%' or user_email LIKE '%".@$_POST['cg-user-name']."%'");
        }
        else if(!empty($_POST['cg-user-name']) AND !empty($_POST['galleryIdToSelect'])){
            //  var_dump(2);
            $selectWPusers = $wpdb->get_results("SELECT DISTINCT  $wpUsers.* FROM $wpUsers, $entriesShort WHERE $wpUsers.id=$entriesShort.wp_user_id AND   
        ($wpUsers.user_login LIKE '%".@$_POST['cg-user-name']."%' or $wpUsers.user_email LIKE '%".@$_POST['cg-user-name']."%')
		 AND $entriesShort.GalleryID='".$_POST['galleryIdToSelect']."'
		 ");
        }
        else{

            $start = 0; // Startwert setzen (0 = 1. Zeile)
            $step =10;

            if (isset($_GET["start"])) {
                $muster = "/^[0-9]+$/"; // reg. Ausdruck für Zahlen
                if (preg_match($muster, @$_GET["start"]) == 0) {
                    $start = 0; // Bei Manipulation Rückfall auf 0
                } else {
                    $start = @$_GET["start"];
                }
            }

            if (isset($_GET["step"])) {
                $muster = "/^[0-9]+$/"; // reg. Ausdruck für Zahlen
                if (preg_match($muster, @$_GET["start"]) == 0) {
                    $step = 10; // Bei Manipulation Rückfall auf 0
                } else {
                    $step = @$_GET["step"];
                }
            }

            $selectWPusers = $wpdb->get_results("SELECT * FROM $wpUsers ORDER BY id ASC LIMIT $start, $step");

        }

        $selectWPusersFormFields = $wpdb->get_results("SELECT $tablename_contest_gal1ery_create_user_form.* FROM $tablename_contest_gal1ery_create_user_form, $tablename_contest_gal1ery_options WHERE
$tablename_contest_gal1ery_create_user_form.Field_Type != 'main-user-name' and 
$tablename_contest_gal1ery_create_user_form.Field_Type != 'main-mail' and 
$tablename_contest_gal1ery_create_user_form.Field_Type != 'password' and 
$tablename_contest_gal1ery_create_user_form.Field_Type != 'password-confirm' AND 
$tablename_contest_gal1ery_create_user_form.GalleryID = $tablename_contest_gal1ery_options.id 
ORDER BY GalleryID ASC, Field_Order ASC");



        $csvData = array();

        $i=0;
        $r=0;

        //Bestimmung der Spalten Namen

        $wpUserId="wpUserId";
        $wpLoginName="loginName";
        $wpUserMail="userMail";

        $csvData[$i][$r]=$wpUserId;
        $r++;
        $csvData[$i][$r]=$wpLoginName;
        $r++;
        $csvData[$i][$r]=$wpUserMail;
        $r++;

        // Vorab Variablen setzen damit bei späteren php versionen keine Fehler angezeigt werden.
        $userId = '';
        $user_login = '';
        $user_email = '';

        // ACHTUNG!!!! ZWEI Varianten hier. Einmal wenn es keine zusätzlichen UserDaten gibt und einmal wenn es welche gibt
        if(empty($selectWPusersFormFields)){

            foreach($selectWPusers as $userKey => $userValue){
                $i++;
                foreach($userValue as $userKey1 => $userValue1){

                    //Sammel und anzeigen einzelner User Werte
                    if($userKey1 == "ID"){
                        $userId = $userValue1;
                    }
                    if($userKey1 == "user_login"){
                        $user_login = $userValue1;
                    }
                    if($userKey1 == "user_email"){
                        $user_email = $userValue1;
                    }
                    /*                    else{
                                            var_dump($f_input_id);//$f_input_id existiert an der stelle nicht! kommt immer NULL raus
                                            @$userFieldContent = $wpdb->get_var("SELECT Field_Content FROM $tablename_contest_gal1ery_create_user_entries WHERE f_input_id = '$f_input_id' AND wp_user_id = '$userId'");
                                        }*/
                    $r++;
                    $csvData[$i][0] = $userId;
                    $csvData[$i][1] = $user_login;
                    $csvData[$i][2] = $user_email;
                    //  $csvData[$i][$r] = $userFieldContent;

                }


            }
        }

        if(!empty($selectWPusersFormFields)) {
            foreach($selectWPusersFormFields as $keyField => $keyValue){

                foreach($keyValue as $keyField1 => $keyValue1){
                    //var_dump($keyField1);die;
                    if($keyField1=='id'){
                        $f_input_id=$keyValue1;
                    }

                    if($keyField1=='GalleryID'){
                        $regFormGalleryID=$keyValue1;
                    }

                    if($keyField1=='Field_Name'){
                        $csvData[$i][$r]=$keyValue1." (G-ID $regFormGalleryID)";

                        foreach($selectWPusers as $userKey => $userValue){
                            $i++;
                            foreach($userValue as $userKey1 => $userValue1){

                                //Sammel und anzeigen einzelner User Werte
                                if($userKey1 == "ID"){
                                    $userId = $userValue1;
                                }
                                if($userKey1 == "user_login"){
                                    $user_login = $userValue1;
                                }
                                if($userKey1 == "user_email"){
                                    $user_email = $userValue1;
                                }
                            }

                            @$userFieldContent = $wpdb->get_var("SELECT Field_Content FROM $tablename_contest_gal1ery_create_user_entries WHERE f_input_id = '$f_input_id' AND wp_user_id = '$userId'");

                            $csvData[$i][0] = $userId;
                            $csvData[$i][1] = $user_login;
                            $csvData[$i][2] = $user_email;
                            $csvData[$i][$r] = $userFieldContent;

                        }
                        $r++;
                        //Wert muss wieder auf 1 gesetzt werde
                        $i=0;
                    }

                }

            }
        }


        $admin_email = get_option('admin_email');
        $adminHashedPass = $wpdb->get_var("SELECT user_pass FROM $wpUsers WHERE user_email = '$admin_email'");

        $code = $wpdb->base_prefix; // database prefix
        $code = md5($code.$adminHashedPass);

        $filename = $code."_userregdata.csv";


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