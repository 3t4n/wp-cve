<?php
// 1. ID
// 2. Feldreihenfolge
// 3. Feldart
// 4. Content/

if (!empty($content)) {

    // 1. Title des Feldes
    // 2. ID des Feldes in F_INPUT
    // 3. Feld Reihenfolge
    // 4. Feld Typ
    // 5. Feld Content 'short-text' oder 'long-text' oder 'date-f'

    $querySETrow = 'UPDATE ' . $tablenameentries . ' SET Short_Text = CASE';
    $querySETaddRow = ' ELSE Short_Text END WHERE (pid,f_input_id) IN (';
    $queryArgsArray1 = [];
    $queryAddArgsArray1 = [];
    $queryArgsCounter1 = 0;

    $querySETrowLongText = 'UPDATE ' . $tablenameentries . ' SET Long_Text = CASE';
    $querySETaddRowLongText = ' ELSE Long_Text END WHERE (pid,f_input_id) IN (';
    $queryArgsArray2 = [];
    $queryAddArgsArray2 = [];
    $queryArgsCounter2 = 0;

    $querySETrowInputDate = 'UPDATE ' . $tablenameentries . ' SET InputDate = CASE';
    $querySETaddRowInputDate = ' ELSE InputDate END WHERE (pid,f_input_id) IN (';
    $queryArgsArray3 = [];
    $queryAddArgsArray3 = [];
    $queryArgsCounter3 = 0;

    /*
        "UPDATE wp_contest_gal1ery SET
    rowid = CASE id WHEN 26957 THEN 26957 WHEN 1387 THEN 1387 WHEN 1386 THEN 1386 WHEN 1385 THEN 1385 WHEN 74 THEN 74 WHEN 10 THEN 10 WHEN 9 THEN 9 WHEN 8 THEN 8 WHEN 7 THEN 7 WHEN 6 THEN 6
     ELSE rowid END WHERE id IN (26957,1387,1386,1385,74,10,9,8,7,6)";*/

    $isSetShortText = false;
    $isUpdateShortText = false;
    $isSetLongText = false;
    $isUpdateLongText = false;
    $isSetInputDate = false;
    $imagesInfoArray = array();
    $inputTstamp = time();

    foreach($content as $key => $arrayValue){

        //reset Array first
        $imageInfoArray = array();
        $i = 0;

            // 2. Bild-ID und Uniuqe Form ID
	    $imageId=absint($key);
		if(in_array($key,$infoPidsArray)===false){
			$infoPidsArray[] = $imageId;
		}

        foreach($arrayValue as $arrayKey => $value){

            // 3. ID des Feldes in F_INPUT
            $formFieldId=$arrayKey;
            $imageInfoArray[$formFieldId] = array();

            // 4. Feldreihenfolge
            $field_order=$fieldsForSaveContentArray[$formFieldId]['Field_Order'];

            // 5. Feldart
            $field_type = $fieldsForSaveContentArray[$formFieldId]['Field_Type'];
            $imageInfoArray[$formFieldId]['field-type'] = $field_type;

            $imageInfoArray[$formFieldId]['field-title'] = $fieldsForSaveContentArray[$formFieldId]['Field_Title'];

            $imageInfoArray[$formFieldId]['field-content'] = '';

            $imageInfoArray[$formFieldId]['Tstamp'] = $inputTstamp;

            // !IMPORTANT HERE TO RESET
            $field_content = '';

            // 6. Content
            if (($field_type=='text-f' OR $field_type=='email-f' OR $field_type=='select-f' OR $field_type=='url-f') && array_key_exists('short-text',$value)){
                $field_content = contest_gal1ery_htmlentities_and_preg_replace($value['short-text']);
                $checkEntries = $wpdb->get_var("SELECT COUNT(*) as NumberOfRows FROM $tablenameentries WHERE pid = '$imageId' AND f_input_id = '$formFieldId' LIMIT 1");

                $imageInfoArray[$formFieldId]['field-content'] = $field_content;
                $imageInfoArray[$formFieldId]['to-update'] = true;

                if(!$checkEntries){

                    $wpdb->query( $wpdb->prepare(
                        "
									INSERT INTO $tablenameentries
									( id, pid, f_input_id, GalleryID, 
									Field_Type, Field_Order, Short_Text, Long_Text, Tstamp)
									VALUES ( %s,%d,%d,%d,
									%s,%d,%s,%s,%d ) 
								",
                        '',$imageId,$formFieldId,$GalleryID,
                        $field_type,$field_order,$field_content,'',$inputTstamp
                    ) );

                    if(!empty($formFieldId)){
                        $isUpdateShortText = true;
                    }

                }


                if($checkEntries){

                    if(!empty($formFieldId)){

                        $isSetShortText = true;

                        $querySETrow .= " WHEN (pid = %d && f_input_id = %d ) THEN %s";
                        $queryArgsArray1[] = $imageId;
                        $queryArgsArray1[] = $formFieldId;
                        $queryArgsArray1[] = $field_content;

                        $querySETaddRow .= "(%d,%d), ";
                        $queryAddArgsArray1[] = $imageId;
                        $queryAddArgsArray1[] = $formFieldId;
                        $queryArgsCounter1++;
                        $queryArgsCounter1++;

                    }

                }

            }

            // 5. Content
            if (($field_type=='comment-f') && array_key_exists('long-text',$value)) {
                $field_content = contest_gal1ery_htmlentities_and_preg_replace_textarea($value['long-text']);
                $field_content = $sanitize_textarea_field($field_content);

                $imageInfoArray[$formFieldId]['field-content'] = $field_content;
                $imageInfoArray[$formFieldId]['to-update'] = true;

                $checkEntries = $wpdb->get_var("SELECT COUNT(*) as NumberOfRows FROM $tablenameentries WHERE pid = '$imageId' AND f_input_id = '$formFieldId' LIMIT 1");

                if(!$checkEntries){

                    $wpdb->query( $wpdb->prepare(
                        "
									INSERT INTO $tablenameentries
									( id, pid, f_input_id, GalleryID, 
									Field_Type, Field_Order, Short_Text, Long_Text, Tstamp)
									VALUES ( %s,%d,%d,%d,
									%s,%d,%s,%s,%d ) 
								",
                        '',$imageId,$formFieldId,$GalleryID,
                        $field_type,$field_order,'',$field_content,$inputTstamp
                    ) );

                    if(!empty($formFieldId)){
                        $isUpdateLongText = true;
                    }

                }

                if($checkEntries){

                    if(!empty($formFieldId)){

                        $isSetLongText = true;

                        $querySETrowLongText .= " WHEN (pid = %d && f_input_id = %d ) THEN %s";
                        $queryArgsArray2[] = $imageId;
                        $queryArgsArray2[] = $formFieldId;
                        $queryArgsArray2[] = $field_content;

                        $querySETaddRowLongText .= "(%d,%d), ";
                        $queryAddArgsArray2[] = $imageId;
                        $queryAddArgsArray2[] = $formFieldId;

                        $queryArgsCounter2++;
                        $queryArgsCounter2++;

                    }

                }


            }

            // 5. Content date-f
            if (($field_type=='date-f') && array_key_exists('date-field',$value)) {

                $field_content = contest_gal1ery_htmlentities_and_preg_replace($value['date-field']);
                $field_content = $sanitize_textarea_field($field_content);

                $imageInfoArray[$formFieldId]['field-content'] = $field_content;
                $imageInfoArray[$formFieldId]['to-update'] = true;

                $checkEntries = $wpdb->get_var("SELECT COUNT(*) as NumberOfRows FROM $tablenameentries WHERE pid = '$imageId' AND f_input_id = '$formFieldId' LIMIT 1");

                $newDateTimeString = '0000-00-00 00:00:00';

                try {

                    $dtFormat = $fieldsForSaveContentArray[$formFieldId]['Field_Format'];

                    $dtFormat = str_replace('YYYY','Y',$dtFormat);
                    $dtFormat = str_replace('MM','m',$dtFormat);
                    $dtFormat = str_replace('DD','d',$dtFormat);

                    $newDateTimeObject = DateTime::createFromFormat("$dtFormat H:i:s","$field_content 00:00:00");
                    if(is_object($newDateTimeObject)){
                        $newDateTimeString = $newDateTimeObject->format("Y-m-d H:i:s");
                    }
                }catch (Exception $e) {

                    $newDateTimeString = '0000-00-00 00:00:00';

                }

              //  var_dump($newDateTimeString);

                if(!$checkEntries){

                    $wpdb->query( $wpdb->prepare(
                        "
									INSERT INTO $tablenameentries
									( id, pid, f_input_id, GalleryID, 
									Field_Type, Field_Order, Short_Text, Long_Text, InputDate, Tstamp)
									VALUES ( %s,%d,%d,%d,
									%s,%d,%s,%s,%s,%d ) 
								",
                        '',$imageId,$formFieldId,$GalleryID,
                        $field_type,$field_order,'','',$newDateTimeString,$inputTstamp
                    ) );

                    if(!empty($formFieldId)){
                        //$isSetInputDate = true;
                    }

                }

                if($checkEntries){

                    if(!empty($formFieldId)){

                        $isSetInputDate = true;

                        $querySETrowInputDate .= " WHEN (pid = %d && f_input_id = %d ) THEN %s";
                        $queryArgsArray3[] = $imageId;
                        $queryArgsArray3[] = $formFieldId;
                        $queryArgsArray3[] = $newDateTimeString;

                        $querySETaddRowInputDate .= "(%d,%d), ";
                        $queryAddArgsArray3[] = $imageId;
                        $queryAddArgsArray3[] = $formFieldId;
                        $queryArgsCounter3++;
                        $queryArgsCounter3++;

                    }

                }


            }

            if(!empty($IsForWpPageTitleInputId) && $formFieldId==$IsForWpPageTitleInputId){
                $entryRow = $wpdb->get_var($wpdb->prepare("SELECT WpPage, WpPageUser, WpPageNoVoting, WpPageWinner FROM $tablename WHERE id = %d",[$imageId]));

                if(!empty($entryRow->WpPage)){
                    $post_update = array(
                        'ID'         => $entryRow->WpPage,
                        'post_title' => $field_content
                    );
                    wp_update_post( $post_update );
                }
                if(!empty($entryRow->WpPageUser)){
                    $post_update = array(
                        'ID'         => $entryRow->WpPageUser,
                        'post_title' => $field_content
                    );
                    wp_update_post( $post_update );
                }
                if(!empty($entryRow->WpPageNoVoting)){
                    $post_update = array(
                        'ID'         => $entryRow->WpPageNoVoting,
                        'post_title' => $field_content
                    );
                    wp_update_post( $post_update );
                }
                if(!empty($entryRow->WpPageWinner)){
                    $post_update = array(
                        'ID'         => $entryRow->WpPageWinner,
                        'post_title' => $field_content
                    );
                    wp_update_post( $post_update );
                }

            }

            $i++;

        }


        // rowid aufbau
        // row[$id][$Active]
        // nur dann inserten, wenn active ist!!!!
        // key($rowids[$imageId])==1 bedeutet, dass es schon aktiviert war und jetzt hier nochmal geschickt wird einfach
        // beutet, dass es jetzt gerade aktiviert wird!!!! !empty($activate[$imageId])

        /*if(($isSetShortText OR $isSetLongText OR $isSetInputDate OR $isUpdateShortText OR $isUpdateLongText) or !empty($activate[$imageId])){

            if(file_exists($jsonUploadImageInfoDir.'/image-info-'.$imageId.'.json')){

                $jsonFile = $jsonUploadImageInfoDir.'/image-info-'.$imageId.'.json';
                $fp = fopen($jsonFile, 'r');
                $imageInfoFileDataArray = json_decode(fread($fp, filesize($jsonFile)),true);
                fclose($fp);

                foreach ($imageInfoArray as $f_input_id => $imageInfoValuesArray){
                    if(array_key_exists('to-update',$imageInfoValuesArray)){
                        $imageInfoFileDataArray[$f_input_id] = $imageInfoValuesArray;
                        unset($imageInfoArray[$f_input_id]['to-update']);
                    }
                }
                $imagesInfoArray[$imageId] = $imageInfoFileDataArray;

            }else{
                foreach ($imageInfoArray as $f_input_id => $imageInfoValuesArray){
                    if(!array_key_exists('to-update',$imageInfoValuesArray)){
                        unset($imageInfoArray[$f_input_id]);
                    }else{
                        unset($imageInfoArray[$f_input_id]['to-update']);
                    }
                }
                $imageInfoFileDataArray = $imageInfoArray;
                $imagesInfoArray[$imageId] = $imageInfoFileDataArray;

            }

            $jsonUploadImageInfoFile = $jsonUploadImageInfoDir.'/image-info-'.$imageId.'.json';
            $fp = fopen($jsonUploadImageInfoFile, 'w');
            fwrite($fp, json_encode($imageInfoFileDataArray));
            fclose($fp);

        }*/

    }


    // for short text
    if($isSetShortText){
        // ic = i counter
        for ($ic = 0;$ic<$queryArgsCounter1;$ic++){
            $queryArgsArray1[] =$queryAddArgsArray1[$ic];
        }

        $querySETaddRow = substr($querySETaddRow,0,-2);
        $querySETaddRow .= ")";

        $querySETrow .= $querySETaddRow;

        $wpdb->query($wpdb->prepare($querySETrow,$queryArgsArray1));

    }

    // for long text
    if($isSetLongText){
        // ic = i counter
        for ($ic = 0;$ic<$queryArgsCounter2;$ic++){
            $queryArgsArray2[] =$queryAddArgsArray2[$ic];
        }
        $querySETaddRowLongText = substr($querySETaddRowLongText,0,-2);
        $querySETaddRowLongText .= ")";

        $querySETrowLongText .= $querySETaddRowLongText;

        $wpdb->query($wpdb->prepare($querySETrowLongText,$queryArgsArray2));

    }

    // for date field
    if($isSetInputDate){
        // ic = i counter
        for ($ic = 0;$ic<$queryArgsCounter3;$ic++){
            $queryArgsArray3[] =$queryAddArgsArray3[$ic];
        }
        $querySETaddRowInputDate = substr($querySETaddRowInputDate,0,-2);
        $querySETaddRowInputDate .= ")";

        $querySETrowInputDate .= $querySETaddRowInputDate;

        $wpdb->query($wpdb->prepare($querySETrowInputDate,$queryArgsArray3));
    }

	/*
    if(!empty($imagesInfoArray)){

        if(file_exists($wp_upload_dir['basedir'] . "/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-images-info-values.json")){

            $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-images-info-values.json';
            $fp = fopen($jsonFile, 'r');
            $allImagesInfoDataArray = json_decode(fread($fp, filesize($jsonFile)),true);
            fclose($fp);

        }else{
            $allImagesInfoDataArray = array();
        }


        foreach ($imagesInfoArray as $imageId => $imageInfoValues){

            if(!empty($imageInfoValues)){// do not remove this
                foreach ($imageInfoValues as $f_input_id => $imageInfoValuesArray){
                    $allImagesInfoDataArray[$imageId][$f_input_id] = $imageInfoValuesArray;
                }
            }

        }

/*        echo "all images info arra save here";

        echo "<pre>";

        print_r($allImagesInfoDataArray);

        echo "</pre>";*/
		/*
        $actualizingFilePath = $wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-'.$GalleryID.'/json/cg-actualizing-all-images-info-json-data-file.txt';

        // then will be currently actualized in cg_actualize_all_images_data_info_file
        // this file will be unliked after full execution in cg_actualize_all_images_data_info_file
        //if(!file_exists($actualizingFilePath)){ currently not using 23.09.2020
            $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-images-info-values.json';
            $fp = fopen($jsonFile, 'w');
            fwrite($fp, json_encode($allImagesInfoDataArray));
            fclose($fp);

            $tstampFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-gallery-image-info-tstamp.json';
            $fp = fopen($tstampFile, 'w');
            fwrite($fp, json_encode(time()));
            fclose($fp);
       // }

    }*/

	if(empty($isFromFrontendGalleryImageEdit)){
		cg_json_upload_form_info_data_files_new($GalleryID,$infoPidsArray);
    }

}