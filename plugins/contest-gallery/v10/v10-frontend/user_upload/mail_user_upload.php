<?php
if(!defined('ABSPATH')){exit;}

// Inform Admin if configured

if (!function_exists('contest_gal1ery_mail_user_upload'))   {
    function contest_gal1ery_mail_user_upload($selectSQLemailUserUpload,$Msg,$galeryID,$userMail) {

        $Subject = contest_gal1ery_convert_for_html_output($selectSQLemailUserUpload->Subject);
        $Header = $selectSQLemailUserUpload->Header;
        $Reply = $selectSQLemailUserUpload->Reply;
        $cc = $selectSQLemailUserUpload->CC;
        $bcc = $selectSQLemailUserUpload->BCC;

        $headers = array();
        $headers[] = "From: $Header <". html_entity_decode(strip_tags($Reply)) . ">\r\n";
        $headers[] = "Reply-To: ". strip_tags($Reply) . "\r\n";

        if(strpos($cc,';')){
            $cc = explode(';',$cc);
            foreach($cc as $ccValue){
                $ccValue = trim($ccValue);
                $headers[] = "CC: $ccValue\r\n";
            }
        }
        else{
            $headers[] = "CC: $cc\r\n";
        }

        if(strpos($bcc,';')){
            $bcc = explode(';',$bcc);
            foreach($bcc as $bccValue){
                $bccValue = trim($bccValue);
                $headers[] = "BCC: $bccValue\r\n";
            }
        }
        else{
            $headers[] = "BCC: $bcc\r\n";
        }

        $headers[] = "MIME-Version: 1.0\r\n";
        $headers[] = "Content-Type: text/html; charset=utf-8\r\n";

        global $cgMailAction;
        global $cgMailGalleryId;
        $cgMailAction = "E-mail to user after upload";
        $cgMailGalleryId = $galeryID;
        add_action( 'wp_mail_failed', 'cg_on_wp_mail_error', 10, 1 );

        wp_mail($userMail, $Subject, $Msg, $headers);

    }
}

add_action('contest_gal1ery_mail_user_upload', 'contest_gal1ery_mail_user_upload',2,4);

$tablename_mail_user_upload = $wpdb->prefix . "contest_gal1ery_mail_user_upload";
$tablename_form_input = $wpdb->prefix . "contest_gal1ery_f_input";

$selectSQLemailUserUpload = $wpdb->get_row( "SELECT * FROM $tablename_mail_user_upload WHERE GalleryID = '$galeryID'" );


// Alle image IDs werden durchgegangen die gerade neu angelegt wurden
foreach($collectImageIDs as $key => $imageID){

    $UserEntries = '<br/>';

    if(!empty($categoryId)){
        $categoryName = $wpdb->get_var("SELECT Name FROM $tablename_categories WHERE id='$categoryId' and Active='1'");
        $UserEntries .= "<br/><strong>Category:</strong><br/>";
        $UserEntries .= $categoryName."<br/>";
    }

    $contentMail = contest_gal1ery_convert_for_html_output($selectSQLemailUserUpload->Content);

    $posUserInfo = "\$info\$";

    if(stripos($contentMail,$posUserInfo)!==false){

        $selectFormInput = $wpdb->get_results( "SELECT id, Field_Type, Field_Order, Field_Content FROM $tablename_f_input WHERE GalleryID = '$galeryID' AND (Field_Type = 'text-f' OR Field_Type = 'comment-f' OR Field_Type ='email-f' OR Field_Type ='select-f' OR Field_Type ='url-f') ORDER BY Field_Order ASC" );

        $selectContentFieldArray = array();

        foreach ($selectFormInput as $value) {

            // 1. Feld Typ
            // 2. ID des Feldes in F_INPUT
            // 3. Feld Reihenfolge
            // 4. Feld Content

            $selectFieldType = 	$value->Field_Type;
            $id = $value->id;// prim�re unique id der form wird auch gespeichert und sp�ter in entries inserted zur erkennung des verwendeten formular feldes
            $fieldOrder = $value->Field_Order;// Die originale Field order in f_input Tabelle. Zwecks Vereinheitlichung.
            $selectContentFieldArray[] = $selectFieldType;
            $selectContentFieldArray[] = $id;
            $selectContentFieldArray[] = $fieldOrder;
            $selectContentField = unserialize($value->Field_Content);
            $selectContentFieldArray[] = $selectContentField["titel"];

        }

        foreach($selectContentFieldArray as $key => $formvalue){

            //	echo "<br>$i<br>";

            // 1. Feld Typ
            // 2. ID des Feldes in F_INPUT
            // 3. Feld Reihenfolge
            // 4. Feld Content



            if($formvalue=='text-f'){$fieldtype="nf"; $n=1; continue;}
            if($fieldtype=="nf" AND $n==1){$formFieldId=$formvalue; $n=2; continue;}
            if($fieldtype=="nf" AND $n==2){$fieldOrder=$formvalue; $n=3; continue;}
            if ($fieldtype=="nf" AND $n==3) {

                $getEntries = $wpdb->get_var( $wpdb->prepare(
                    "
															SELECT Short_Text
															FROM $tablenameentries 
															WHERE pid = %d and f_input_id = %d
														",
                    $imageID,$formFieldId
                ) );

                $UserEntries .= "<br/><strong>$formvalue:</strong><br/>";
                $UserEntries .= "$getEntries<br/>";

                $fieldtype='';
                $i=0;

            }

            if($formvalue=='email-f'){$fieldtype="ef";  $n=1; continue;}
            if($fieldtype=="ef" AND $n==1){$formFieldId=$formvalue; $n=2; continue;}
            if($fieldtype=="ef" AND $n==2){$fieldOrder=$formvalue; $n=3; continue;}
            if ($fieldtype=='ef' AND $n==3) {

                $getEntries = $wpdb->get_var( $wpdb->prepare(
                    "
															SELECT Short_Text
															FROM $tablenameentries 
															WHERE pid = %d and f_input_id = %d
														",
                    $imageID,$formFieldId
                ) );

                if(empty($userData)){
                    $UserEntries .= "<br/><strong>$formvalue:</strong><br/>";
                    $UserEntries .= "$getEntries<br/>";
                }

                $fieldtype='';
                $i=0;


            }

            if($formvalue=='select-f'){$fieldtype="se";  $n=1; continue;}
            if($fieldtype=="se" AND $n==1){$formFieldId=$formvalue; $n=2; continue;}
            if($fieldtype=="se" AND $n==2){$fieldOrder=$formvalue; $n=3; continue;}
            if ($fieldtype=='se' AND $n==3) {



                $getEntries = $wpdb->get_var( $wpdb->prepare(
                    "
															SELECT Short_Text
															FROM $tablenameentries 
															WHERE pid = %d and f_input_id = %d
														",
                    $imageID,$formFieldId
                ) );

                $UserEntries .= "<br/><strong>$formvalue:</strong><br/>";
                $UserEntries .= "$getEntries<br/>";

                $fieldtype='';
                $i=0;

            }

            if($formvalue=='url-f'){$fieldtype="url";  $n=1; continue;}
            if($fieldtype=="url" AND $n==1){$formFieldId=$formvalue; $n=2; continue;}
            if($fieldtype=="url" AND $n==2){$fieldOrder=$formvalue; $n=3; continue;}
            if ($fieldtype=='url' AND $n==3) {



                $getEntries = $wpdb->get_var( $wpdb->prepare(
                    "
															SELECT Short_Text
															FROM $tablenameentries 
															WHERE pid = %d and f_input_id = %d
														",
                    $imageID,$formFieldId
                ) );

                $UserEntries .= "<br/><strong>$formvalue:</strong><br/>";
                $UserEntries .= "$getEntries<br/>";

                $fieldtype='';
                $i=0;

            }

            if($formvalue=='comment-f'){$fieldtype="kf"; $n=1; continue;}
            if($fieldtype=="kf" AND $n==1){$formFieldId=$formvalue; $n=2; continue;}
            if($fieldtype=="kf" AND $n==2){$fieldOrder=$formvalue; $n=3; continue;}
            if ($fieldtype=='kf' AND $n==3) {

                $getEntries = $wpdb->get_var( $wpdb->prepare(
                    "
															SELECT Long_Text
															FROM $tablenameentries 
															WHERE pid = %d and f_input_id = %d
														",
                    $imageID,$formFieldId
                ));

                if(!empty($getEntries)){
                    $getEntries = nl2br($getEntries);
                    $UserEntries .= "<br/><strong>$formvalue:</strong><br/>";
                    $UserEntries .= "$getEntries<br/>";
                }

                $fieldtype='';
                $i=0;

            }

        }

        // Daten zur User URL sammeln

        $wpImgId = $wpdb->get_var("SELECT WpUpload FROM $tablename1 WHERE GalleryID = '$galeryID' AND  id = '$imageID'");
        $selectImageData = $wpdb->get_var( "SELECT guid FROM $table_posts WHERE ID = '$wpImgId' ");
        $galleryName = $wpdb->get_var( "SELECT GalleryName FROM $tablenameOptions WHERE id = '$galeryID' ");

        if(empty($selectSQLemailUserUpload->ContentInfoWithoutFileSource)){
            $Field_Content_User_File_Field =unserialize($wpdb->get_var( "SELECT Field_Content FROM $tablename_form_input WHERE GalleryID = '$galeryID' AND Field_Type = 'image-f'" ));
            $File_Field_Title = $Field_Content_User_File_Field['titel'];

            if(!empty($AdditionalFilesArray) && count($AdditionalFilesArray)>1){
                $UserEntries .= "<br/><strong>$File_Field_Title:</strong><br/>";
                foreach ($AdditionalFilesArray as $keyOrder => $fileArray){
                    if($keyOrder==1){
                        $UserEntries .= $selectImageData.'<br/>';
                    }else{
                        $UserEntries .= $fileArray['guid'].'<br/>';
                    }
                }
            }else{
                if(!empty($selectImageData)){// otherwise must be contact entry
                    $UserEntries .= "<br/><strong>$File_Field_Title:</strong><br/>";
                    $UserEntries .= $selectImageData;
                }
            }
        }

        if(stripos($contentMail,$posUserInfo)!==false){

            $Msg = str_ireplace($posUserInfo, $UserEntries, $contentMail);

        }else{
            $Msg = $contentMail;
        }
        do_action( 'contest_gal1ery_mail_user_upload', $selectSQLemailUserUpload,$Msg,$galeryID,$userMail);

    }else{
        $Msg = $contentMail;

        do_action( 'contest_gal1ery_mail_user_upload', $selectSQLemailUserUpload,$Msg,$galeryID,$userMail);

    }


}


?>