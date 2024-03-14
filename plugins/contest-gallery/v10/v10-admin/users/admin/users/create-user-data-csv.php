<?php


die('please contact site administrator if you see this, code 274');

    //echo "works";

//print_r($selectContentFieldArray);

    //$selectSQLall = $wpdb->get_results( "SELECT * FROM $tablename WHERE GalleryID = '$GalleryID' ORDER BY rowid DESC");

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
/*                else{
                     var_dump($f_input_id);//$f_input_id existiert an der stelle nicht! kommt immer NULL raus
                    @$userFieldContent = $wpdb->get_var("SELECT Field_Content FROM $tablename_contest_gal1ery_create_user_entries WHERE f_input_id = '$f_input_id' AND wp_user_id = '$userId'");
                }*/
                $r++;
                $csvData[$i][0] = $userId;
                $csvData[$i][1] = $user_login;
                $csvData[$i][2] = $user_email;
             //   $csvData[$i][$r] = $userFieldContent;

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

    $dir = plugin_dir_path( __FILE__ );
    $dir = $dir.$code."_userregdata.csv";
    //echo "$dir";
    chmod($dir,0644);
    $fp = fopen($dir, 'w');
    fputs($fp, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
    foreach ($csvData as $fields) {
        fputcsv($fp, $fields, ";");
    }

    fclose($fp);
//$bloginfo = bloginfo("language");

    //$code = $wpdb->prefix; // database prefix
    // $code = md5($code);
    /*
        if (file_exists($dir)) {
        unlink($dir);
        }*/

    $userDataCSVsource = plugins_url( '/'.$code.'_userregdata.csv', __FILE__ );



    //cg_action_create_zip($allPics,''.$pfad.'/contest-gallery/gallery-id-'.$GalleryID.'/'.$code.'_images_download.zip');
    echo '<p style="text-align:center;width:180px;"><a href="'.$userDataCSVsource.'">Download csv file</a></p>';
    echo '<p style="text-align:center;width:180px;"><a href="?page='.cg_get_version().'/index.php&option_id='.$GalleryID.'&delete_data_csv=true&users_management=true">Delete csv file</a></p>';





?>