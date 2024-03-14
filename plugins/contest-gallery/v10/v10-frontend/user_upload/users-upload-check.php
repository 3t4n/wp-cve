<?php
if(!defined('ABSPATH')){exit;}

// Aurufen von WP-Config hier notwendig
//include("../../../../wp-config.php");

// User ID �berpr�fung ob es die selbe ist
$CheckCheck = wp_create_nonce("check");

$check = (!empty($_POST['check'])) ? $_POST['check'] : '';
$sendUserMail = '';
$userMail = '';
$checkWpMail = '';
$inputsTime = time();
$inputContents = [];

$isManipulated = false;

$Version = cg_get_version_for_scripts();

$CookieId='';

/*
echo "<pre>";
print_r($_POST);
echo "</pre>";

echo "<pre>";
print_r($_FILES);
echo "</pre>";

die;*/

/*echo "<pre>";
print_r($_FILES);
echo "</pre>";die;*/

$galeryID = absint($_POST['GalleryID']);
$galeryIDuser = $galeryID;

$_POST = cg1l_sanitize_post($_POST);
$_FILES = cg1l_sanitize_post($_FILES);// since 21.0.1 can be also done

if(isset($_POST['galeryIDuser'])){
    $galeryIDuser = $_POST['galeryIDuser'];
}

$imagesArray = [];

//$_FILES['data']['name'][0] = "fire-3792<script>95<script>1_1920.jpg";
$isOnlyContactEntry = false;
if(!empty($_POST['isOnlyContactEntry'])){
    $isOnlyContactEntry = true;
    $_FILES = [
        'data' => [
            'name' => ['0' => ['']],
            'full_path' => ['0' => ['']],
            'type' => ['0' => ['con']],
            'tmp_name' => ['0' => ['']],
            'error' => ['0' => ['']],
            'size' => ['0' => ['']],
        ]
    ];
}

/*echo "<pre>";
print_r($_FILES);
echo "</pre>";

echo "<pre>";
print_r($_POST);
echo "</pre>";

die;*/

if(empty($_POST["cg_upload_action"]) OR empty($_FILES["data"])){
    $isManipulated = true;
}else{

    $wp_upload_dir = wp_upload_dir();
    $optionsPath = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/'.$galeryID.'-options.json';
    $optionsSource =json_decode(file_get_contents($optionsPath),true);
    $intervalConf = cg_shortcode_interval_check($galeryID,$optionsSource,'cg_users_contact');
    if(!$intervalConf['shortcodeIsActive']){
        ?>
        <script data-cg-processing="true">
            var gid = <?php echo json_encode($galeryIDuser);?>;
            cgJsData[gid].vars.isShortcodeIntervalOver = true;
            cgJsData[gid].vars.upload.doneUploadFailed = true;
        </script>
        <?php
        cg_shortcode_interval_check_show_ajax_message($intervalConf,$galeryIDuser);
        return;
    }

    global $wpdb;

    $tablenameOptions = $wpdb->prefix . "contest_gal1ery_options";
    $tablenameProOptions = $wpdb->prefix . "contest_gal1ery_pro_options";
    $tablename1 = $wpdb->prefix . "contest_gal1ery";
    $tablename_mail_user_upload = $wpdb->prefix . "contest_gal1ery_mail_user_upload";
    $tablename_form_input = $wpdb->prefix . "contest_gal1ery_f_input";

    $proOptions = $wpdb->get_row( "SELECT * FROM $tablenameProOptions WHERE GalleryID = '$galeryID'" );
    $selectSQL1 = $wpdb->get_row( "SELECT * FROM $tablenameOptions WHERE id = '$galeryID'" );
    $InformUserUpload = $wpdb->get_var( "SELECT InformUserUpload FROM $tablename_mail_user_upload WHERE GalleryID = '$galeryID'" );

    // correction should be done here, in case bulk upload from older versions is activated
    if($proOptions->AdditionalFiles==1){
        $selectSQL1->ActivateBulkUpload = 0;
    }

    if(!$isOnlyContactEntry){
        if(!empty($proOptions->AdditionalFiles)){
            if(!empty($selectSQL1->ActivateBulkUpload)){
                #toDo!
            }else{
                //if(count($_FILES['data']["name"]) > (1+$proOptions->AdditionalFilesCount)){
                if(count($_FILES['data']["name"]) > (10)){
                    ?>
                    <script data-cg-processing="true">
                        var gid = <?php echo json_encode($galeryIDuser);?>;
                        cgJsData[gid].vars.upload.doneUploadFailed = true;
                        cgJsData[gid].vars.upload.failMessage = <?php echo json_encode("Please don't manipulate upload additional files quantity.");?>;
                    </script>
                    <?php
                    echo "Please don't manipulate upload additional files quantity.";die;
                }
                $_FILES = cg1l_sanitize_files($_FILES, 'data',0,$galeryIDuser,$galeryID,true);
            }
        }else{
            $_FILES = cg1l_sanitize_files($_FILES, 'data',0,$galeryIDuser,$galeryID,true);
        }
    }

    $arrayInfoDataForImageAddedEntries = [];

    $RegUserUploadOnly = $proOptions->RegUserUploadOnly;
    $RegUserMaxUpload = $proOptions->RegUserMaxUpload;
    $UploadRequiresCookieMessage = $proOptions->UploadRequiresCookieMessage;

    $DataShare = ($proOptions->FbLikeNoShare==1) ? 'false' : 'true';
    $DataClass = ($proOptions->FbLikeOnlyShare==1) ? 'fb-share-button' : 'fb-like';
    $DataLayout = ($proOptions->FbLikeOnlyShare==1) ? 'button' : 'button_count';

    $CustomImageName = $proOptions->CustomImageName;
    $CustomImageNamePath = $proOptions->CustomImageNamePath;

    $WpUserName = '';
    $WpUserId = '';

    $is_user_logged_in = is_user_logged_in();

    if($is_user_logged_in){
        $wp_get_current_user = wp_get_current_user();
        $WpUserId = $wp_get_current_user->data->ID;
        $WpUserName = $wp_get_current_user->data->user_login;
    }

    if(!empty($RegUserUploadOnly)){

        $isCountCheckHasToBeDone = false;

        if($RegUserUploadOnly==1 && !empty($RegUserMaxUpload) && $is_user_logged_in==true){

            $isCountCheckHasToBeDone = true;
            $regUserUploadsCount = $wpdb->get_var("SELECT COUNT(*) FROM $tablename1 WHERE WpUserId = '$WpUserId' and GalleryID = '$galeryID'");

        }else if($RegUserUploadOnly==2 && !empty($RegUserMaxUpload)){

            if($RegUserUploadOnly==2){

                if(!isset($_COOKIE['contest-gal1ery-'.$galeryID.'-upload'])) {

                    echo $UploadRequiresCookieMessage;

                    ?>

                    <script data-cg-processing="true" data-cg-upload-cookie-requires="true">

                        var gid = <?php echo json_encode($galeryIDuser);?>;
                        cgJsData[gid].vars.upload.doneUploadFailed = true;
                        var cookieRequiredMessage = true;
                        cgJsData[gid].vars.upload.failMessage = <?php echo json_encode($UploadRequiresCookieMessage);?>;

                    </script>

                    <?php
                    die;

                }else{
                    $CookieId = $_COOKIE['contest-gal1ery-'.$galeryID.'-upload'];
                }

            }

            $isCountCheckHasToBeDone = true;
            if(!empty($CookieId)){
                $regUserUploadsCount = $wpdb->get_var("SELECT COUNT(*) FROM $tablename1 WHERE CookieId = '$CookieId' and GalleryID = '$galeryID'");
            }else{
                $regUserUploadsCount = 0;
            }
        }else if($RegUserUploadOnly==3 && !empty($RegUserMaxUpload)){
            $isCountCheckHasToBeDone = true;
            $userIP = sanitize_text_field(cg_get_user_ip());
            if(empty($userIP) OR $userIP == 'unknown'){
                ?>
                <script data-cg-processing="true">
                    var gid = <?php echo json_encode($galeryIDuser);?>;
                    cgJsData[gid].vars.upload.doneUploadFailed = true;
                    cgJsData[gid].vars.upload.failMessage = <?php echo json_encode("IP could not be identified, code 504. Please contact administrator.");?>;
                </script>
                <?php
                echo 'IP could not be identified, code 504. Please contact administrator.';
                die;
            }
            if(!empty($userIP)){
                $regUserUploadsCount = $wpdb->get_var("SELECT COUNT(*) FROM $tablename1 WHERE IP = '$userIP' and GalleryID = '$galeryID'");
            }else{
                $regUserUploadsCount = 0;
            }
        }

        if($isCountCheckHasToBeDone){
            if($proOptions->AdditionalFiles==1){
                $uploadedFilesCount = 1;
            }else{
                $uploadedFilesCount = count($_FILES["data"]["name"]);
            }
            $totalUserUploads = $regUserUploadsCount+$uploadedFilesCount;
            if($totalUserUploads>$RegUserMaxUpload){
                $isManipulated = true;
            }
        }
    }

}

if(!$isManipulated){

    global $wp_version;

    $sanitize_textarea_field = ($wp_version<4.7) ? 'sanitize_text_field' : 'sanitize_textarea_field';

    //echo "galeryID: $galeryID";

    $unix = time();
    $unixadd = $unix+2;

    $GalleryID = $galeryID;

    //----------------------------Prove if user tries to reload ---------------->

    $tablename_f_input = $wpdb->prefix . "contest_gal1ery_f_input";
    $tablename_options_visual = $wpdb->prefix . "contest_gal1ery_options_visual";
    $optionsVisual = $wpdb->get_row( "SELECT * FROM $tablename_options_visual WHERE GalleryID = '$galeryID'" );
    $GalleryName = $selectSQL1->GalleryName;

    $tablenameentries = $wpdb->prefix . "contest_gal1ery_entries";
    $tablename_mail_confirmation = $wpdb->prefix . "contest_gal1ery_mail_confirmation";
    $tablename_mails_collected = $wpdb->prefix . "contest_gal1ery_mails_collected";
    $tablename_categories = $wpdb->prefix . "contest_gal1ery_categories";
    $table_posts = $wpdb->prefix."posts";
    $wpUsers = $wpdb->base_prefix . "users";

    // neue image Ids für Abfrage sammeln
    $collect = '';

    $formInputForFieldTitles = $wpdb->get_results( "SELECT id, Field_Content, Show_Slider, IsForWpPageDescription, IsForWpPageTitle FROM $tablename_f_input WHERE GalleryID = '$galeryID' ORDER BY Field_Order ASC" );

    $inputFieldTitlesArray = array();
    $inputFieldContentArray = array();
    $inputFieldsShowSlider = array();
    $newImagesPermalinksArray = array();
    $IsForWpPageTitle = 0;
    $IsForWpPageDescription = 0;// only relevant when checking header on a contest gallery page
    $WpPageTitle = '';

    foreach($formInputForFieldTitles as $row){

        $inputFieldsShowSlider[$row->id] = $row->Show_Slider;

        $row->Field_Content = unserialize($row->Field_Content);
        $fieldTitle = $row->Field_Content["titel"];

        $inputFieldTitlesArray[$row->id] = $fieldTitle;
        $inputFieldContentArray[$row->id] = $row->Field_Content;

        if(!empty($row->IsForWpPageTitle)){
            $IsForWpPageTitle = $row->id;
        }

    }

    $thumbSizesWp = array();
    $thumbSizesWp['thumbnail_size_w'] = get_option("thumbnail_size_w");
    $thumbSizesWp['medium_size_w'] = get_option("medium_size_w");
    $thumbSizesWp['large_size_w'] = get_option("large_size_w");

    $mailConfSettings = $wpdb->get_row( "SELECT * FROM $tablename_mail_confirmation WHERE GalleryID='$galeryID' ");
    $InformAdmin = $selectSQL1->InformAdmin;

    $tablenameemail = $wpdb->prefix . "contest_gal1ery_mail";
    $selectSQLemail = $wpdb->get_row( "SELECT * FROM $tablenameemail WHERE GalleryID = '$galeryID'" );

    add_action('contest_gal1ery_mail_image_activation', 'contest_gal1ery_mail_image_activation',3,7);

    include(plugin_dir_path(__FILE__).'mail_image_activation_function.php');

    $collectImageIDs = array();

    $checkCgMail = '';
    $cgMailChecked = false;
    $categoryId = 0;
    $processedFilesCounter = 0;

    // These files need to be included as dependencies when on the front end.
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/media.php' );

    $ActivateBulkUpload = $selectSQL1->ActivateBulkUpload;
    $BulkUploadQuantity = $selectSQL1->BulkUploadQuantity;
    $InformUsers = $selectSQL1->Inform;
    $ActivateUpload = $selectSQL1->ActivateUpload;
    $cgVersion = $selectSQL1->Version;
    $files = $_FILES["data"];
    $uploadQuantity = count($files["name"]);
    $fbContentArray = array();

    if(empty($BulkUploadQuantity)){
        $BulkUploadQuantity = 100;
    }

    if($ActivateBulkUpload==1 && $uploadQuantity > $BulkUploadQuantity){

        ?>

        <script data-cg-processing="true">


            var gid = <?php echo json_encode($galeryIDuser);?>;
            cgJsData[gid].vars.upload.doneUploadFailed = true;
            cgJsData[gid].vars.upload.failMessage = <?php echo json_encode("Please don't manipulate upload quantity.");?>;


        </script>

        <?php

        echo "Please don't manipulate upload quantity.";die;
    }

    if($ActivateBulkUpload==0 && empty($proOptions->AdditionalFiles) && $uploadQuantity > 1){

        ?>

        <script data-cg-processing="true">

            var gid = <?php echo json_encode($galeryIDuser);?>;
            cgJsData[gid].vars.upload.doneUploadFailed = true;
            cgJsData[gid].vars.upload.failMessage = <?php echo json_encode("Please don't manipulate upload quantity. Bulk upload deactivated.");?>;

        </script>

        <?php

        echo "Please don't manipulate upload quantity. Bulk upload deactivated.";die;

    }

    // validate send form first
    $form_input = [];
    if(!empty($_POST['form_input'])){
        $form_input = $_POST['form_input'];
    }

    // manipulation fields check of fields

    // 4 array numbers are one input field
    // 1 = fieldType
    // 2 = fieldId
    // 3 = fieldOrder
    // 4 = content
    $i = 1;
    $inputId = 0;
    $content = '';
    $fieldType = '';

    foreach($form_input as $key => $value){

        if(is_array($value)){
            $value = $value[$key];
        }

        if($i == 1){

            $fieldType = $value;

        }

        if($i == 2){

            $inputId = $value;

        }

        if($i == 4){

            $content = $value;

            if(!empty($inputFieldContentArray[$inputId]['mandatory'])){
                if($inputFieldContentArray[$inputId]['mandatory']=='on' && empty($content)){

                    ?>

                    <script data-cg-processing="true">


                        var gid = <?php echo json_encode($galeryIDuser);?>;
                        cgJsData[gid].vars.upload.doneUploadFailed = true;
                        cgJsData[gid].vars.upload.failMessage = <?php echo json_encode("Please don't manipulate the form. Field_Type: $fieldType , required manipulated");?>;


                    </script>

                    <?php

                    echo "Please don't manipulate the form. Field_Type: $fieldType , required manipulated";die;

                }
            }

            if($fieldType=='kf' OR $fieldType=='fbd'){
                $content = str_replace("\r","",$content);// then equal to html behaviour if maxlength was set in the textarea field
            }

            if(!empty($inputFieldContentArray[$inputId]['min-char'])){
                if(!empty($content) && strlen($content) < $inputFieldContentArray[$inputId]['min-char']){


                    ?>

                    <script data-cg-processing="true">


                        var gid = <?php echo json_encode($galeryIDuser);?>;
                        cgJsData[gid].vars.upload.doneUploadFailed = true;
                        cgJsData[gid].vars.upload.failMessage = <?php echo json_encode("Please do not manipulate the form. Field_Type: $fieldType , minimum characters manipulated");?>;


                    </script>

                    <?php


                    echo "Please do not manipulate the form. Field_Type: $fieldType , minimum characters manipulated";die;
                }
            }

            if(!empty($inputFieldContentArray[$inputId]['max-char'])){
                if(!empty($content) && strlen($content) > $inputFieldContentArray[$inputId]['max-char']){

                    ?>

                    <script data-cg-processing="true">

                        var gid = <?php echo json_encode($galeryIDuser);?>;
                        cgJsData[gid].vars.upload.doneUploadFailed = true;
                        cgJsData[gid].vars.upload.failMessage = <?php echo json_encode("Please do not manipulate the form. Field_Type: $fieldType , maximum characters manipulated");?>;

                    </script>

                    <?php


                    echo "Please do not manipulate the form. Field_Type: $fieldType , maximum characters manipulated";die;
                }
            }


        }

        // reset here
        if($i % 4 === 0){

            $i = 1;
            $inputId = 0;
            $content = '';
            $fieldType = '';

        }else{
            $i++;
        }

    }

    // manipulation fields check of fields --- END

    $AdditionalFilesArray = [];
    $ExifDataByRealIds = [];
    $AdditionalFilesMainWpUpload = 0;
    $AdditionalFilesMainRealId = 0;
    $WpUploadBefore = 0;
    $realIdBefore = 0;

    if(!empty($files['name']) && is_array($files["name"])){

        foreach ($files['name'] as $key => $value) {

            $keyFile = $key;
            $isNewProcessing = false;

            if (is_array($files['name'][$key])) {//new processing
                $isNewProcessing = true;
                $file = array(
                    'name' => $files['name'][$key][0],
                    'type' => $files['type'][$key][0],
                    'tmp_name' => $files['tmp_name'][$key][0],
                    'error' => $files['error'][$key][0],
                    'size' => $files['size'][$key][0]
                );
            }else{// old processing
                $file = array(
                    'name' => $files['name'][$key],
                    'type' => $files['type'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'error' => $files['error'][$key],
                    'size' => $files['size'][$key]
                );
            }

            $dateityp = false;
            $isAlternativeFileType = false;

            if(
                    $file["type"]=='image/jpeg' || $file["type"] == 'image/jpg' || $file["type"] == 'image/jpe' ||
                    //$file["type"]=='image/png' || $file["type"]=='image/gif' || $file["type"] == 'image/vnd.microsoft.icon' || $file["type"] == 'image/x-icon' // ico not allowed anymore since wp 6.0, status 02 Jul 2022
                    $file["type"]=='image/png' || $file["type"]=='image/gif'
            ){
                $dateityp = GetImageSize($file["tmp_name"]);
            }else{
                $isAlternativeFileType = true;
            }

            /*            $imageTypeArray = array
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

            if(
                    $file["type"]=='con' || $file["type"]=='application/pdf' || $file["type"] == 'application/x-zip-compressed' || $file["type"] == 'application/zip' ||
                    $file["type"]=='text/plain' || $file["type"] == 'application/msword' ||
                    $file["type"]=='application/vnd.openxmlformats-officedocument.wordprocessingml.document' || $file["type"] == 'application/vnd.ms-excel' ||
                    $file["type"]=='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $file["type"] == 'text/csv' ||
                    $file["type"] == 'audio/mpeg' || $file["type"] == 'audio/x-m4a' ||
                    $file["type"] == 'audio/ogg' || $file["type"] == 'audio/wav' ||
                    $file["type"] == 'video/mp4' || $file["type"] == 'video/avi' || $file["type"] == 'video/x-ms-wmv' || $file["type"] == 'video/quicktime' || $file["type"] == 'video/webm' ||
                    $file["type"] == 'application/vnd.ms-powerpoint' || $file["type"] == 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
            ){

            }else{

                    if(empty($dateityp) || ($dateityp[2] != 1 && $dateityp[2] != 2 && $dateityp[2] != 3 && $dateityp[2] != 17)) {

                        // File size wird als 0 ausgegeben wenn die hoch zu ladende Datei gr��er ist als Server erlaubt. File type und andere Infos dann auch nicht vorhanden.
                        //   echo "Don't manipulate the upload: wrong file type or file size"; die;

                        ?>

                        <script data-cg-processing="true">
                            debugger

                            var gid = <?php echo json_encode($galeryIDuser);?>;
                            cgJsData[gid].vars.upload.doneUploadFailed = true;
                            cgJsData[gid].vars.upload.failMessage = <?php echo json_encode("Don't manipulate the upload: wrong file type");?>;

                        </script>

                        <?php

                        echo "Don't manipulate the upload: wrong file type";

                        die;

                    }

            }

            $_FILES = array ("data" => $file);

            $CustomImageNamePathArrayValueToSet = '';

            if($CustomImageName==1 && empty($isOnlyContactEntry)){
                if(!empty($CustomImageNamePath)){
                    $CustomImageNamePathArray = explode('-',$CustomImageNamePath);

                    if(count($CustomImageNamePathArray)){
                        foreach($CustomImageNamePathArray as $CustomImageNamePathArrayValue){

                            if($CustomImageNamePathArrayValue=='GalleryId'){$CustomImageNamePathArrayValueToSet.=$galeryID.'-';}

                            if($CustomImageNamePathArrayValue=='GalleryName'){
                                if(!empty($GalleryName)){
                                    $CustomImageNamePathArrayValueToSet.=$GalleryName.'-';
                                }
                            }

                            if($CustomImageNamePathArrayValue=='WpUserId'){
                                if(!empty($WpUserId)){
                                    $CustomImageNamePathArrayValueToSet.=$WpUserId.'-';
                                }
                            }

                            if($CustomImageNamePathArrayValue=='WpUserName'){
                                if(!empty($WpUserName)){
                                    $CustomImageNamePathArrayValueToSet.=$WpUserName.'-';
                                }
                            }
                        }
                    }
                }
            }

            $_FILES['data']['name'] = $CustomImageNamePathArrayValueToSet.$_FILES['data']['name'];

            if(!$isOnlyContactEntry){
                foreach ($_FILES as $file => $array) {
                    // $newupload = my_handle_attachment($file,$post_id);

                    // Use the wordpress function to upload
                    // test_upload_pdf corresponds to the position in the $_FILES array
                    // 0 means the content is not associated with any other posts

                    $time = date("Y-m-d H:i:s");

                    $post_data = array(
                        'post_content' => "Contest Gallery ID-$galeryID $time"
                    );

                    $attach_id = media_handle_upload($file,0,$post_data);
                    //  var_dump($attach_id);die;

                    if ( is_wp_error( $attach_id ) ) {
                        //    echo "There was an error uploading the image. Please contact site administrator."; die;

                        ?>

                        <script data-cg-processing="true">

                            var gid = <?php echo json_encode($galeryIDuser);?>;
                            cgJsData[gid].vars.upload.doneUploadFailed = true;
                            var attachIdErrorMessage = true;
                            cgJsData[gid].vars.upload.failMessage = <?php echo json_encode(esc_html( $attach_id->get_error_message() ));?>;

                        </script>

                        <?php

                        echo esc_html( $attach_id->get_error_message());

                        die;

                    } else {
                        //echo "The image was uploaded successfully!";
                        //var_dump($attachment_id);
                    }

                }
            }

            //----------------------------Upload file and save in database ---------------->

            // simply can be always processed since 20.0
            if (true) {
            //if ($isOnlyContactEntry || $files['size'] > 0) {

                if(is_array($files['tmp_name'][$key])){// new processing
                    $tempname = $files['tmp_name'][$key][0];
                    $dateiname = strtolower($files['name'][$key][0]);
                    $dateigroesse = $files['size'][$key][0];
                    $type = $files['type'][$key][0];
                }else{// old processing
                    $tempname = $files['tmp_name'][$key];
                    $dateiname = strtolower($files['name'][$key]);
                    $dateigroesse = $files['size'][$key];
                    $type = $files['type'][$key];
                }

                if($isOnlyContactEntry){
                    $wp_image_info = '';
                    $image_url = '';
                    $post_title = '';
                    $post_type = 'con';
                    $wp_image_id = 0;
                }else{
                    $wp_image_info = $wpdb->get_row("SELECT * FROM $table_posts WHERE ID = '$attach_id'");
                    $image_url = $wp_image_info->guid;
                    $post_title = $wp_image_info->post_title;
                    $post_type = $wp_image_info->post_mime_type;
                    $wp_image_id = $wp_image_info->ID;
                }

                // Notwendig: wird in convert-several-pics so verabeitet. Darf keine Sonderzeichen enthalten!
                //$search = array(" ", "!", '"', "#", "$", "%", "&", "'", "(", ")", "*", "+", ",", "/", ":", ";", "=", "?", "@", "[","]","�"); // old code, only special signs example
                //$post_title = str_replace($search,"_",$post_title);
                // var_dump($post_title); die;
                $dateiname = $post_title;

                $doNotProcess=0;

                if(!$isOnlyContactEntry){
                    if($post_type=="image/jpeg"){$post_type="jpg";$imageType="jpg";}
                    else if($post_type=="image/jpg"){$post_type="jpg";$imageType="jpg";}
                    else if($post_type=="image/x-citrix-jpeg"){$post_type="jpg";$imageType="jpg";}// appears only  if file post with attach id already generated by wordpress, other cases so far have not to be proved
                    else if($post_type=="image/x-citrix-png"){$post_type="png";$imageType="png";}// appears only  if file post with attach id already generated by wordpress, other cases so far have not to be proved
                    else if($post_type=="image/x-citrix-gif"){$post_type="gif";$imageType="gif";}// appears only  if file post with attach id already generated by wordpress, other cases so far have not to be proved
                    else if($post_type=="image/png"){$post_type="png";$imageType="png";}
                    else if($post_type=="image/gif"){$post_type="gif";$imageType="gif";}
                    else if($post_type=="application/pdf"){$post_type="pdf";$imageType="pdf";}
                    else if($post_type=="application/zip"){$post_type="zip";$imageType="zip";}
                    else if($post_type=="text/plain"){$post_type="txt";$imageType="txt";}
                    else if($post_type=="application/msword"){$post_type="doc";$imageType="doc";}
                    else if($post_type=="application/vnd.openxmlformats-officedocument.wordprocessingml.document"){$post_type="docx";$imageType="docx";}
                    else if($post_type=="application/vnd.ms-excel"){$post_type="xls";$imageType="xls";}
                    else if($post_type=="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"){$post_type="xlsx";$imageType="xlsx";}
                    else if($post_type=="text/csv"){$post_type="csv";$imageType="csv";}
                    else if($post_type=="audio/mpeg" && $type=="audio/mpeg"){$post_type="mp3";$imageType="mp3";}
                    else if($post_type=="audio/mpeg" && $type=="audio/x-m4a"){$post_type="m4a";$imageType="m4a";}
                    else if($post_type=="audio/ogg"){$post_type="ogg";$imageType="ogg";}
                    else if($post_type=="audio/wav"){$post_type="wav";$imageType="wav";}
                    else if($post_type=="video/mp4"){$post_type="mp4";$imageType="mp4";}
                    //else if($post_type=="video/avi"){$post_type="avi";$imageType="avi";}
                    else if($post_type=="video/quicktime"){$post_type="mov";$imageType="mov";}
                    else if($post_type=="video/webm"){$post_type="webm";$imageType="webm";}
                    else if($post_type=="application/vnd.ms-powerpoint"){$post_type="ppt";$imageType="ppt";}
                    else if($post_type=="application/vnd.openxmlformats-officedocument.presentationml.presentation"){$post_type="pptx";$imageType="pptx";}
                    //else if($post_type=="video/x-ms-wmv"){$post_type="wmv";$imageType="wmv";}
                    else{

                        //    echo "There was an error uploading the image. Please contact site administrator."; die;

                        ?>

                        <script data-cg-processing="true">

                            var gid = <?php echo json_encode($galeryIDuser);?>;
                            cgJsData[gid].vars.upload.doneUploadFailed = true;
                            var attachIdErrorMessage = true;
                            cgJsData[gid].vars.upload.failMessage = <?php echo json_encode('The file type '.$post_type.' is not supported. Please contact support@contest-gallery.com 
                            and send the example file you tried to upload.');?>;

                        </script>

                        <?php

                        echo 'The file type '.$post_type.' is not supported. Please contact support@contest-gallery.com 
                            and send the example file you tried to upload.';

                        die;

                    }
                }

                $uploads = wp_upload_dir();

                //----------------------------Create Thumbs and Galery pics ---------------->

                $unix = time();
                $unixadd = $unix+2;

                $current_width = 0;
                $current_height = 0;

                if(!$isOnlyContactEntry){
                    if(
                        $type != 'application/pdf' && $type != 'application/x-zip-compressed' && $type != 'application/zip' &&
                        $type != 'text/plain' && $type != 'application/msword' &&
                        $type != 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' && $type != 'application/vnd.ms-excel' &&
                        $type != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' && $type != 'text/csv' &&
                        $type != 'audio/mpeg' && $type != 'audio/x-m4a' &&
                        $type != 'audio/ogg' && $type != 'audio/wav' &&
                        $type != 'video/mp4' && $type != 'video/webm' && $type != 'video/avi' && $type != 'video/quicktime' && $type != 'video/x-ms-wmv' &&
                        $type != 'application/vnd.ms-powerpoint' && $type != 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
                    ){
                        $imageInfoArray = wp_get_attachment_image_src($wp_image_id,'full');

                        $current_width = $imageInfoArray[1];
                        $current_height = $imageInfoArray[2];

                        // in case of buggy image, can happen for some ico files, then get thumbnail sizes for width
                        if($current_width=='0' || $current_width=='1' || $current_height=='0' || $current_height=='1'){
                            $imageInfoArray = wp_get_attachment_image_src($wp_image_id,'thumbnail');
                            $current_width = $imageInfoArray[1];
                            $current_height = $imageInfoArray[2];
                        }
                    }
                }

                //----------------------------Create Thumbs and Galery pics END ----------------//

                if(is_user_logged_in()){
                    $WpUserId = get_current_user_id();
                }
                else{
                    $WpUserId = '';
                }

                if(empty($userIP)){
                    $userIP = sanitize_text_field(cg_get_user_ip());
                }

                if(!empty($proOptions->AdditionalFiles)){
                    if(!empty($selectSQL1->ActivateBulkUpload)){
                        #toDo!
                    }else{
                        if($uploadQuantity>1 && $processedFilesCounter>=1){
                            if($processedFilesCounter==1){
                                $AdditionalFilesMainWpUpload = $WpUploadBefore;
                                $AdditionalFilesMainRealId = $realIdBefore;
                                $AdditionalFilesArray[1] = ['isRealIdSource' => true, 'WpUpload' => $WpUploadBefore];
                            }
                            if($processedFilesCounter>=1){
                                $AdditionalFilesArray[($processedFilesCounter+1)] = [
                                    'WpUpload' => $wp_image_id,
                                ];
                            }
                            $processedFilesCounter++;
                            continue;
                        }
                    }
                }

                // added since version 18.0.0
                if(!$isOnlyContactEntry && cg_is_alternative_file_type_video($post_type)){
                    $fileData = wp_get_attachment_metadata($wp_image_id);
                    $current_height = (!empty($fileData['height'])) ? $fileData['height'] : 0;
                    $current_width = (!empty($fileData['width'])) ? $fileData['width'] : 0;
                }

                // updating string after all the 0 at the end does not work. That is why version is not inserted there
                // default 0 to countr1-5 was added lately on 15.05.2020
                $wpdb->query( $wpdb->prepare(
                    "
					INSERT INTO $tablename1
					( id, rowid, Timestamp, NamePic,
					ImgType, CountC, CountR, Rating,
					GalleryID, Active, Informed, WpUpload, Width, Height, WpUserId, IP,
			        CountR1,CountR2,CountR3,CountR4,CountR5)
					VALUES ( %s,%s,%d,%s,
					%s,%d,%s,%s,
					%d,%s,%s,%s,%s,%s,%s,%s,
			        %d,%d,%d,%d,%d)
				",
                    '','',$unixadd,$dateiname,
                    $post_type,0,'','',
                    $galeryID,'','',$wp_image_id,$current_width,$current_height,$WpUserId,$userIP,
                    0,0,0,0,0
                ) );

                $nextId = $wpdb->insert_id;

                // Insert Upload Fields for pic if exists
                $realIdBefore = $nextId;
                $WpUploadBefore = $wp_image_id;

                if($isOnlyContactEntry){
                    $ExifDataByRealIds[$nextId] = '';
                }else{
                    $ExifDataByRealIds[$nextId] = cg_create_exif_data_and_add_to_database($nextId,$wp_image_id);// then create only exif data for backend
                }

                if($collect==''){
                    $collect .= "$tablename1.id = $nextId";
                }else{
                    $collect .= " OR $tablename1.id = $nextId";
                }

                $CheckSet = '';

                if($RegUserUploadOnly==1){
                    $CheckSet = 'CheckLogin';
                }else if($RegUserUploadOnly==2){
                    $CheckSet = 'CheckCookie';
                }else if($RegUserUploadOnly==3){
                    $CheckSet = 'CheckIp';
                }

                // updating string after all the 0 at the end does not work at the top insert query. That is why version have to be inserted here
                $wpdb->update(
                    "$tablename1",
                    array('rowid' => $nextId,'Version' => $Version,'CookieId' => $CookieId,'CheckSet' => $CheckSet),
                    array('id' => $nextId),
                    array('%d','%s','%s','%s'),
                    array('%d')
                );

                // Sp�ter f�r Inform Image wichtig
                $collectImageIDs[] = intval($nextId); // intval so later index of javascript goes right for sure

                try{
                    $hasInputToProcess = false;

                    if($isNewProcessing){
                        if (!empty($_POST['form_input']) && !empty($_POST['form_input'][$keyFile])){
                            $hasInputToProcess = true;
                        }
                    }else{
                        $form_input = $_POST['form_input'];
                        if (!empty($_POST['form_input'])){
                            $hasInputToProcess = true;
                        }
                   }
                    if ($hasInputToProcess){

                        //	print_r($form_input);

                        //$form_input = sanitize_text_field(@$_POST['form_input']);
                        if($isNewProcessing){
                            $form_input = $_POST['form_input'][$keyFile];
                        }else{
                            $form_input = $_POST['form_input'];
                        }

/*
                        echo "<pre>";
                        print_r($form_input);
                        echo "</pre>";*/

                        $i=0;

                        $sendUserMail = '';

                        // 1. Feldtyp <<< Zur Bestimmung der Feldart f�r weitere Verarbeitung in der Datenbank, Admin etc.
                        // 2. Feldnummer <<<  Zur Bestimmung der Feldreihenfolge in Frontend und Admin.
                        // 3. Feldreihenfolge
                        // 4. Feldinhalt
                        foreach ($form_input as $key => $value) {

                            $i++;

                            // Short_Text Entries werden eingef�gt (Name, E-Mail)

                            if(!isset($ft)){
                                $ft = '';
                            }

                            if ($i==1 AND ($ft!='kf' or $ft!='fbd')){$ft = $value; continue;}

                            if ($i==2 AND ($ft=='nf' or $ft=='ef' or $ft=='se' or $ft=='url' or $ft=='sec' or $ft=='cb' or $ft=='fbt' or $ft=='dt')){$f_input_id = $value; continue;}

                            if ($i==3 AND ($ft=='nf' or $ft=='ef' or $ft=='se' or $ft=='url' or $ft=='sec' or $ft=='cb' or $ft=='fbt' or $ft=='dt')){
                                $field_order = $value;
                                $Checked = 0;
                                if($ft=='cb'){// check if hook was in or not!!!!
                                    $keyPlusOne = $key+1;
                                    if(!empty($form_input[$keyPlusOne])){
                                        if($form_input[$keyPlusOne]=='checked'){// then can go one time more and proccessed natural way.
                                            $Checked = 1;
                                            continue;
                                        }else{
                                            $i=4;//
                                            $value = 'not-checked';
                                        }
                                    }else{
                                        $i=4;//
                                        $value = 'not-checked';
                                    }
                                }else{
                                    continue;
                                }
                            }
                            if ($i==4 AND ($ft=='nf' or $ft=='ef' or $ft=='se' or $ft=='url' or $ft=='sec' or $ft=='cb' or $ft=='fbt' or $ft=='dt')){

                                //echo "<br>insert $ft<br>";
                                //echo "<br>f_input_id $f_input_id<br>";
                                //echo "<br>field_order $field_order<br>";

                                if(is_user_logged_in() && $ft=='ef'){
                                    global $current_user;
                                    get_currentuserinfo();
                                    $content = $current_user->user_email;
                                }
                                else{
                                    $content = $value;
                                }

                                $content = contest_gal1ery_htmlentities_and_preg_replace($content);

                                if($ft=='dt'){
                                    //$wpdb->insert( $tablenameentries, array( 'id' => '', 'pid' => $nextId, 'f_input_id' => $f_input_id, 'GalleryID' => $galeryID, "Field_Type" => 'text-f', 'Field_Order' => $field_order, 'Short_Text' => $content, 'Long_Text' => '') );


                                    /* $stringTest = 'YYYY.MM.DD';
                                                                    $stringTest = str_replace('YYYY','Y',$stringTest);
                                                                    $stringTest = str_replace('MM','m',$stringTest);
                                                                    $stringTest = str_replace('DD','d',$stringTest);

                                                                    var_dump($stringTest);
                                                                    $newDateTimeObject = DateTime::createFromFormat("Y.m.d H:i:s",'2020.26.06 00:00:00');
                                                                    var_dump($newDateTimeObject);*/


                                    $newDateTimeString = '0000-00-00 00:00:00';

                                    try {

                                        $dtFieldContent = $inputFieldContentArray[$f_input_id];
                                        $dtFormat = $dtFieldContent['format'];

                                        $dtFormat = str_replace('YYYY','Y',$dtFormat);
                                        $dtFormat = str_replace('MM','m',$dtFormat);
                                        $dtFormat = str_replace('DD','d',$dtFormat);

                                        $newDateTimeObject = DateTime::createFromFormat("$dtFormat H:i:s","$content 00:00:00");
                                        if(is_object($newDateTimeObject)){
                                            $newDateTimeString = $newDateTimeObject->format("Y-m-d H:i:s");
                                        }
                                    }catch (Exception $e) {

                                        $newDateTimeString = '0000-00-00 00:00:00';

                                    }

                                    $wpdb->query( $wpdb->prepare(
                                        "
                                    INSERT INTO $tablenameentries
                                    ( id, pid, f_input_id, GalleryID, Field_Type, Field_Order, Short_Text, Long_Text, InputDate,Tstamp)
                                    VALUES ( %s,%d,%d,%d,%s,%d,%s,%s,%s,%d )
                                ",
                                        '',$nextId,$f_input_id,$galeryID,'date-f',$field_order,'','',$newDateTimeString,$inputsTime
                                    ) );

                                    $inputContents[$wpdb->insert_id] = $newDateTimeString;

                                }

                                if($ft=='nf'){
                                    //$wpdb->insert( $tablenameentries, array( 'id' => '', 'pid' => $nextId, 'f_input_id' => $f_input_id, 'GalleryID' => $galeryID, "Field_Type" => 'text-f', 'Field_Order' => $field_order, 'Short_Text' => $content, 'Long_Text' => '') );
                                    if($f_input_id==$IsForWpPageTitle && !empty($content)){
                                        $WpPageTitle = $content;
                                    }
                                    $wpdb->query( $wpdb->prepare(
                                        "
                                    INSERT INTO $tablenameentries
                                    ( id, pid, f_input_id, GalleryID, Field_Type, Field_Order, Short_Text, Long_Text,Tstamp)
                                    VALUES ( %s,%d,%d,%d,%s,%d,%s,%s,%d )
                                ",
                                        '',$nextId,$f_input_id,$galeryID,'text-f',$field_order,$content,'',$inputsTime
                                    ) );
                                    $inputContents[$wpdb->insert_id] = $content;

                                    // because then is not simple entry
                                    if(!$isOnlyContactEntry && !empty($attach_id)){// added 21.2.1
                                        $WpAttachmentDetailsType = $wpdb->get_var( "SELECT WpAttachmentDetailsType FROM $tablename_form_input WHERE id = '$f_input_id'" );
                                        if(!empty($WpAttachmentDetailsType)){// added 21.2.1
                                            if($WpAttachmentDetailsType=='alt'){
                                                add_post_meta( $attach_id, '_wp_attachment_image_alt', $content);
                                               //$text = get_post_meta($addedWpUploadsArray[$entryId], '_wp_attachment_image_alt', TRUE);
                                            }else{
                                                if($WpAttachmentDetailsType=='title'){
                                                    $post_update = array(
                                                        'ID'         => $attach_id,
                                                        'post_title' => $content
                                                    );
                                                    wp_update_post( $post_update );
                                                }else if($WpAttachmentDetailsType=='caption'){
                                                    $post_update = array(
                                                        'ID'         => $attach_id,
                                                        'post_excerpt' => $content
                                                    );
                                                    wp_update_post( $post_update );
                                                }else if($WpAttachmentDetailsType=='description'){
                                                    $post_update = array(
                                                        'ID'         => $attach_id,
                                                        'post_content' => $content
                                                    );
                                                    wp_update_post( $post_update );
                                                }
                                            }
                                        }
                                    }
                                }
                                if($ft=='fbt'){
                                    //$wpdb->insert( $tablenameentries, array( 'id' => '', 'pid' => $nextId, 'f_input_id' => $f_input_id, 'GalleryID' => $galeryID, "Field_Type" => 'text-f', 'Field_Order' => $field_order, 'Short_Text' => $content, 'Long_Text' => '') );

                                    $wpdb->query( $wpdb->prepare(
                                        "
                                    INSERT INTO $tablenameentries
                                    ( id, pid, f_input_id, GalleryID, Field_Type, Field_Order, Short_Text, Long_Text,)
                                    VALUES ( %s,%d,%d,%d,%s,%d,%s,%s )
                                ",
                                        '',$nextId,$f_input_id,$galeryID,'fbt-f',$field_order,$content,''
                                    ) );
                                    $inputContents[$wpdb->insert_id] = $content;
                                }

                                if($ft=='fbt'){// for facebook page create
                                    if(empty($fbContentArray[$nextId])){$fbContentArray[$nextId] = array();}
                                    $fbContentArray[$nextId]['title'] = $content;
                                }

                                if($ft=='cb'){
                                    //$wpdb->insert( $tablenameentries, array( 'id' => '', 'pid' => $nextId, 'f_input_id' => $f_input_id, 'GalleryID' => $galeryID, "Field_Type" => 'text-f', 'Field_Order' => $field_order, 'Short_Text' => $content, 'Long_Text' => '') );

                                    // insert original checked field_content to show later!
                                    $content = $wpdb->get_var("SELECT Field_Content FROM $tablename_f_input WHERE id = $f_input_id");

                                    $wpdb->query( $wpdb->prepare(
                                        "
                                    INSERT INTO $tablenameentries
                                    ( id, pid, f_input_id, GalleryID, Field_Type, Field_Order, Short_Text, Long_Text, Checked, Tstamp)
                                    VALUES ( %s,%d,%d,%d,%s,%d,%s,%s,%d,%d)
                                ",
                                        '',$nextId,$f_input_id,$galeryID,'check-f',$field_order,'',$content,$Checked,$inputsTime
                                    ) );
                                    $inputContents[$wpdb->insert_id] = $content;

                                }

                                if($ft=='url'){
                                    //$wpdb->insert( $tablenameentries, array( 'id' => '', 'pid' => $nextId, 'f_input_id' => $f_input_id, 'GalleryID' => $galeryID, "Field_Type" => 'text-f', 'Field_Order' => $field_order, 'Short_Text' => $content, 'Long_Text' => '') );

                                    $wpdb->query( $wpdb->prepare(
                                        "
                                    INSERT INTO $tablenameentries
                                    ( id, pid, f_input_id, GalleryID, Field_Type, Field_Order, Short_Text, Long_Text, Tstamp)
                                    VALUES ( %s,%d,%d,%d,%s,%d,%s,%s,%d )
                                ",
                                        '',$nextId,$f_input_id,$galeryID,'url-f',$field_order,$content,'',$inputsTime
                                    ) );
                                    $inputContents[$wpdb->insert_id] = $content;

                                }

                                if($ft=='se'){

                                    //$wpdb->insert( $tablenameentries, array( 'id' => '', 'pid' => $nextId, 'f_input_id' => $f_input_id, 'GalleryID' => $galeryID, "Field_Type" => 'text-f', 'Field_Order' => $field_order, 'Short_Text' => $content, 'Long_Text' => '') );

                                    if($content=='0'){
                                        $content = '';
                                    }

                                    $wpdb->query( $wpdb->prepare(
                                        "
                                    INSERT INTO $tablenameentries
                                    ( id, pid, f_input_id, GalleryID, Field_Type, Field_Order, Short_Text, Long_Text, Tstamp)
                                    VALUES ( %s,%d,%d,%d,%s,%d,%s,%s,%d )
                                ",
                                        '',$nextId,$f_input_id,$galeryID,'select-f',$field_order,$content,'',$inputsTime
                                    ) );
                                    $inputContents[$wpdb->insert_id] = $content;

                                }

                                if($ft=='sec'){

                                    $categoryId = $content;

                                    $wpdb->update(
                                        "$tablename1",
                                        array('Category' => $content),
                                        array('id' => $nextId),
                                        array('%s'),
                                        array('%s')
                                    );


                                }

                                if($ft=='ef'){
                                    //$wpdb->insert( $tablenameentries, array( 'id' => '', 'pid' => $nextId, 'f_input_id' => $f_input_id, 'GalleryID' => $galeryID, "Field_Type" => 'email-f', 'Field_Order' => $field_order, 'Short_Text' => $content, 'Long_Text' => '') );

                                    $sendUserMail = strtolower($content);

                                    if($cgMailChecked==false){
                                        $ConfMailId = 0;

                                        // Update des haupttables mit WpUserId weiter unten
                                        $checkWpMail = $wpdb->get_row( "SELECT ID, user_email FROM $wpUsers WHERE user_email = '$sendUserMail'" );

                                        if(empty($checkWpMail)){
                                            $checkCgMail = $wpdb->get_row( "SELECT * FROM $tablename_mails_collected WHERE Mail = '$sendUserMail'" );

                                            if(!empty($checkCgMail)){
                                                if($checkCgMail->Confirmed==1){
                                                    $ConfMailId = $checkCgMail->id;
                                                }
                                            }
                                        }

                                        $cgMailChecked=true;
                                    }


                                    $wpdb->query( $wpdb->prepare(
                                        "
                                        INSERT INTO $tablenameentries
                                        ( id, pid, f_input_id, GalleryID, Field_Type, Field_Order, Short_Text, Long_Text,ConfMailId,Tstamp)
                                        VALUES ( %s,%d,%d,%d,%s,%d,%s,%s,%d,%d )
                                    ",
                                        '',$nextId,$f_input_id,$galeryID,'email-f',$field_order,$content,'',$ConfMailId,$inputsTime
                                    ) );
                                    $inputContents[$wpdb->insert_id] = $content;

                                    if(!empty($checkWpMail)){

                                        $wpdb->update(
                                            "$tablename1",
                                            array('WpUserId' => $checkWpMail->ID),
                                            array('id' => $nextId),
                                            array('%d'),
                                            array('%d')
                                        );

                                    }

                                }

                                $ft=false;
                                $f_input_id=false;
                                $field_order=false;
                                $i=0;
                                continue;
                            }


                            // Short_Text Entries werden eingef�gt ---- ENDE

                            // Long Entries werden eingef�gt

                            if ($i==1 AND ($ft!='nf' or $ft!='ef' or $ft!='se' or $ft!='url' or $ft!='sec' or $ft!='cb' or $ft!='fbt' or $ft!='dt')){$ft = $value; continue;}

                            if ($i==2 AND ($ft=='kf' OR $ft == 'fbd')){$f_input_id = $value; continue;}

                            if ($i==3 AND ($ft=='kf' OR $ft == 'fbd')){$field_order = $value; continue;}

                            if ($i==4 AND ($ft=='kf' OR $ft == 'fbd')){

                                //echo "<br>insert $ft<br>";
                                //echo "<br>f_input_id $f_input_id<br>";
                                //echo "<br>field_order $field_order<br>";

                                $content = $value;

                                $content = contest_gal1ery_htmlentities_and_preg_replace_textarea($content);

                                //echo "<br>content $content<br>";

                                $fieldType = 'comment-f';

                                if($ft=='kf'){
                                    $fieldType = 'comment-f';
                                }
                                if($ft=='fbd'){
                                    $fieldType = 'fbd-f';
                                }

                                //$wpdb->insert( $tablenameentries, array( 'id' => '', 'pid' => $nextId, 'f_input_id' => $f_input_id, 'GalleryID' => $galeryID, "Field_Type" => 'comment-f', 'Field_Order' => $field_order, 'Short_Text' => '', 'Long_Text' => $content) );

                                $wpdb->query( $wpdb->prepare(
                                    "
					INSERT INTO $tablenameentries
					( id, pid, f_input_id, GalleryID, Field_Type, Field_Order, Short_Text, Long_Text, Tstamp)
					VALUES ( %s,%d,%d,%d,%s,%d,%s,%s,%d )
				",
                                    '',$nextId,$f_input_id,$galeryID,$fieldType,$field_order,'',$content,$inputsTime
                                ) );
                                $inputContents[$wpdb->insert_id] = $content;

                                if($fieldType=='fbd-f'){// for facebook page create
                                    if(empty($fbContentArray[$nextId])){$fbContentArray[$nextId] = array();}
                                    $fbContentArray[$nextId]['description'] = $content;
                                }

                                // Long Entries werden eingef�gt ---- ENDE
                                // because then is not simple entry
                                if(!$isOnlyContactEntry && !empty($attach_id)){// added 21.2.1
                                    $WpAttachmentDetailsType = $wpdb->get_var( "SELECT WpAttachmentDetailsType FROM $tablename_form_input WHERE id = '$f_input_id'" );
                                    if(!empty($WpAttachmentDetailsType)){// added 21.2.1
                                        if($WpAttachmentDetailsType=='alt'){
                                            add_post_meta( $attach_id, '_wp_attachment_image_alt', $content);
                                            //$text = get_post_meta($addedWpUploadsArray[$entryId], '_wp_attachment_image_alt', TRUE);
                                        }else{
                                            if($WpAttachmentDetailsType=='title'){
                                                $post_update = array(
                                                    'ID'         => $attach_id,
                                                    'post_title' => $content
                                                );
                                                wp_update_post( $post_update );
                                            }else if($WpAttachmentDetailsType=='caption'){
                                                $post_update = array(
                                                    'ID'         => $attach_id,
                                                    'post_excerpt' => $content
                                                );
                                                wp_update_post( $post_update );
                                            }else if($WpAttachmentDetailsType=='description'){
                                                $post_update = array(
                                                    'ID'         => $attach_id,
                                                    'post_content' => $content
                                                );
                                                wp_update_post( $post_update );
                                            }
                                        }
                                    }
                                }

                                $ft=false;
                                $f_input_id=false;
                                $field_order=false;
                                $i=0;

                                continue;
                            }

                        }

                    }
                }catch(Exception $e){
                    print_r('form_input upload error. Please contact administrator.');die;
                    echo 'Exception abgefangen: ',  $e->getMessage(), "\n";
                }

                if(!$post_title){
                    $post_title = 'entry';
                }

                if(!empty($selectSQL1->WpPageParent)){
                    // cg_gallery shortcode
                    $array = [
                        'post_title'=> ($WpPageTitle) ? $WpPageTitle : $post_title,
                        'post_type'=>'contest-gallery',
                        'post_content'=>"<!-- wp:shortcode -->"."\r\n".
                            "<!--This is a comment: cg_galley... shortcode with entry id is required to display Contest Gallery entry on a Contest Gallery Custom Post Type entry page. You can place your own content before and after this shortcode, whatever you like. You can place cg_gallery... shortcode with entry_id also on any other of your pages. -->"."\r\n".
                            "[cg_gallery id=\"$GalleryID\" entry_id=\"$nextId\"]"."\r\n".
                            "<!-- /wp:shortcode -->",
                        'post_mime_type'=>'contest-gallery-plugin-page',
                        'post_status'=>'publish',
                        'post_parent'=>$selectSQL1->WpPageParent
                    ];

                    $WpPage = wp_insert_post($array);

                    // cg_gallery_user shortcode
                    $array = [
                        'post_title'=> ($WpPageTitle) ? $WpPageTitle : $post_title,
                        'post_type'=>'contest-gallery',
                        'post_content'=>"<!-- wp:shortcode -->"."\r\n".
                            "<!--This is a comment: cg_galley... shortcode with entry id is required to display Contest Gallery entry on a Contest Gallery Custom Post Type entry page. You can place your own content before and after this shortcode, whatever you like. You can place cg_gallery... shortcode with entry_id also on any other of your pages. -->"."\r\n".
                            "[cg_gallery_user id=\"$GalleryID\" entry_id=\"$nextId\"]"."\r\n".
                            "<!-- /wp:shortcode -->",
                        'post_mime_type'=>'contest-gallery-plugin-page',
                        'post_status'=>'publish',
                        'post_parent'=>$selectSQL1->WpPageParentUser
                    ];

                    $WpPageUser = wp_insert_post($array);

                    // cg_gallery_no_voting shortcode
                    $array = [
                        'post_title'=> ($WpPageTitle) ? $WpPageTitle : $post_title,
                        'post_type'=>'contest-gallery',
                        'post_content'=>"<!-- wp:shortcode -->"."\r\n".
                            "<!--This is a comment: cg_galley... shortcode with entry id is required to display Contest Gallery entry on a Contest Gallery Custom Post Type entry page. You can place your own content before and after this shortcode, whatever you like. You can place cg_gallery... shortcode with entry_id also on any other of your pages. -->"."\r\n".
                            "[cg_gallery_no_voting id=\"$GalleryID\" entry_id=\"$nextId\"]"."\r\n".
                            "<!-- /wp:shortcode -->",
                        'post_mime_type'=>'contest-gallery-plugin-page',
                        'post_status'=>'publish',
                        'post_parent'=>$selectSQL1->WpPageParentNoVoting
                    ];

                    $WpPageNoVoting = wp_insert_post($array);

                    // cg_gallery_winner shortcode
                    $array = [
                        'post_title'=> ($WpPageTitle) ? $WpPageTitle : $post_title,
                        'post_type'=>'contest-gallery',
                        'post_content'=>"<!-- wp:shortcode -->"."\r\n".
                            "<!--This is a comment: cg_galley... shortcode with entry id is required to display Contest Gallery entry on a Contest Gallery Custom Post Type entry page. You can place your own content before and after this shortcode, whatever you like. You can place cg_gallery... shortcode with entry_id also on any other of your pages. -->"."\r\n".
                            "[cg_gallery_winner id=\"$GalleryID\" entry_id=\"$nextId\"]"."\r\n".
                            "<!-- /wp:shortcode -->",
                        'post_mime_type'=>'contest-gallery-plugin-page',
                        'post_status'=>'publish',
                        'post_parent'=>$selectSQL1->WpPageParentWinner
                    ];

                    $WpPageWinner = wp_insert_post($array);
                    $wpdb->update(
                        "$tablename1",
                        array('WpPage' => $WpPage,'WpPageUser' => $WpPageUser,'WpPageNoVoting' => $WpPageNoVoting,'WpPageWinner' => $WpPageWinner),
                        array('id' => $nextId),
                        array('%d','%d','%d','%d'),
                        array('%d')
                    );

                    if(strpos($galeryIDuser,'-uf')!==false || strpos($galeryIDuser,'-cf')!==false){
                        $newImagesPermalinksArray[$nextId] = get_permalink($WpPage);
                    }else{
                        if(strpos($galeryIDuser,'-u')!==false){
                            $newImagesPermalinksArray[$nextId] = get_permalink($WpPageUser);
                        }else if(strpos($galeryIDuser,'-nv')!==false){
                            $newImagesPermalinksArray[$nextId] = get_permalink($WpPageNoVoting);
                        }else if(strpos($galeryIDuser,'-w')!==false){
                            $newImagesPermalinksArray[$nextId] = get_permalink($WpPageWinner);
                        } else{
                            $newImagesPermalinksArray[$nextId] = get_permalink($WpPage);
                        }
                    }

                }


                if(is_user_logged_in()==true){
                    $userData = $wpdb->get_row("SELECT user_email FROM $wpUsers WHERE ID = $WpUserId");
                    $userMail = $userData->user_email;
                    $displayName = get_user_meta( $WpUserId, 'nickname');
                }
                else{
                    $userMail = $sendUserMail;
                    $displayName = '';
                }

                // Activate and send e-mail

                //@$ActivateUpload = $wpdb->get_var( "SELECT ActivateUpload FROM $tablenameOptions WHERE ActivateUpload='1' and id = '$galeryID' " );

                if($ActivateUpload==1){

                    $wpdb->update(
                        "$tablename1",
                        array('Active' => '1'),
                        array('id' => $nextId),
                        array('%d'),
                        array('%d')
                    );

                    if(!empty($userMail) && $InformUsers == 1){
                        include(plugin_dir_path(__FILE__).'mail_image_activation.php');
                    }

                }

                // create FB page
                $object = new stdClass();
                $object->id = $nextId;
                $object->Timestamp = $unixadd;
                $object->NamePic = $dateiname;
                $object->WpUpload = $wp_image_id;

                if(!empty($fbContentArray[$nextId])){
                    if(!empty($fbContentArray[$nextId]['title'])){
                        $blog_title = $fbContentArray[$nextId]['title'];
                    }
                    if(!empty($fbContentArray[$nextId]['description'])){
                        $blog_description = $fbContentArray[$nextId]['description'];
                    }
                }

                if(!$isAlternativeFileType && intval($selectSQL1->Version)<17){
                    include(__DIR__.'/../../v10-admin/gallery/change-gallery/4_2_fb-creation.php');
                }

                // create FB page --- END
                $imageInfoEntriesData = $wpdb->get_results("SELECT id, f_input_id, Field_Type, Short_Text, Long_Text, InputDate, Tstamp FROM $tablenameentries WHERE pid = $nextId ORDER BY f_input_id ASC");

                $Field1IdGalleryView = $optionsVisual->Field1IdGalleryView;
                $Field2IdGalleryView = $optionsVisual->Field2IdGalleryView;

                $watermarkPositionId = $wpdb->get_var("SELECT id FROM $tablename_f_input WHERE GalleryID = $GalleryID AND WatermarkPosition != '' AND Active = 1");

                if(!empty($imageInfoEntriesData)){
                    $arrayInfoDataForImage = array();

                    foreach($imageInfoEntriesData as $row){

                        if($row->Field_Type == 'email-f'){// email-f
                            continue;
                        }

                        // then nothing to display in forontend!!!!
                        if($row->f_input_id != $Field1IdGalleryView && $row->f_input_id != $Field2IdGalleryView && $inputFieldsShowSlider[$row->f_input_id] != 1 && $row->f_input_id != $watermarkPositionId){
                            continue;
                        }

                        if(empty($arrayInfoDataForImage[$row->f_input_id])){
                            $arrayInfoDataForImage[$row->f_input_id] = array();
                        }

                        $arrayInfoDataForImage[$row->f_input_id]['Tstamp'] = $row->Tstamp;
                        $arrayInfoDataForImage[$row->f_input_id]['field-type'] = $row->Field_Type;
                        $arrayInfoDataForImage[$row->f_input_id]['field-title'] = $inputFieldTitlesArray[$row->f_input_id];

                        if(!empty($row->Long_Text)){
                            $arrayInfoDataForImage[$row->f_input_id]['field-content'] = $inputContents[$row->id];
                        }else if($row->Field_Type == 'date-f'){

                            $newDateTimeString = '';

                            if(!empty($row->InputDate) && $row->InputDate!='0000-00-00 00:00:00'){

                                try {

                                    $dtFieldContent = $inputFieldContentArray[$row->f_input_id];
                                    $dtFormat = $dtFieldContent['format'];

                                    $dtFormat = str_replace('YYYY','Y',$dtFormat);
                                    $dtFormat = str_replace('MM','m',$dtFormat);
                                    $dtFormat = str_replace('DD','d',$dtFormat);

                                    $newDateTimeObject = DateTime::createFromFormat("Y-m-d H:i:s",$row->InputDate);

                                    if(is_object($newDateTimeObject)){
                                        $newDateTimeString = $newDateTimeObject->format($dtFormat);
                                    }
                                }catch (Exception $e) {

                                    $newDateTimeString = '';

                                }

                            }
                            $arrayInfoDataForImage[$row->f_input_id]['field-content'] = $newDateTimeString;
                        }else{
                            $arrayInfoDataForImage[$row->f_input_id]['field-content'] = $inputContents[$row->id];
                        }
                    }

                    $arrayInfoDataForImageAddedEntries[$nextId]=$arrayInfoDataForImage;

                    $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/image-info/image-info-'.$nextId.'.json';
                    $fp = fopen($jsonFile, 'w');
                    fwrite($fp, json_encode($arrayInfoDataForImage));
                    fclose($fp);

                }

            }

            $processedFilesCounter++;

        }

        if(!$isOnlyContactEntry && !empty($proOptions->AdditionalFiles)){
            if(!empty($selectSQL1->ActivateBulkUpload)){
                #toDo!
            }else{

                if(!empty($AdditionalFilesArray)){
                    $selectWpPostsQuery = "SELECT guid, post_title, post_name, post_content, post_excerpt, post_mime_type, ID FROM $table_posts WHERE";

                    foreach ($AdditionalFilesArray as $key => $AdditionalFileArray){
                        $selectWpPostsQuery .= " (ID = ".$AdditionalFileArray['WpUpload'].") OR";
                    }

                    $selectWpPostsQuery = substr($selectWpPostsQuery,0,-3);
                    $selectWpPostsQuery .= " ORDER BY ID DESC";
                    //var_dump($selectWpPostsQuery);
                    $allWpPosts = $wpdb->get_results($selectWpPostsQuery);

                    foreach($allWpPosts as $wpPostRow){
                        foreach ($AdditionalFilesArray as $key => $AdditionalFileArray){
                            $AdditionalFilesArray[$key]['WpUpload'] = intval($AdditionalFilesArray[$key]['WpUpload']);// intval this value for sure for later queries when files will be deleted to check in serialized MultipleFiles
                            if(!empty($AdditionalFileArray['isRealIdSource'])){continue;}
                            if($AdditionalFileArray['WpUpload']==$wpPostRow->ID){
                                $imgSrcLarge = '';
                                $imgSrcMedium = '';
                                $Width = '';
                                $Height = '';
                                $Exif = '';
                                $post_mime_type_fist_part = substr($wpPostRow->post_mime_type,0,strrpos($wpPostRow->post_mime_type,'/'));
                                $ImgType = strtolower(substr($wpPostRow->guid,(strrpos($wpPostRow->guid,'.')+1),strlen($wpPostRow->guid)));
                                if(cg_is_is_image($ImgType)){
                                    $imgSrcMedium=wp_get_attachment_image_src($AdditionalFileArray['WpUpload'], 'medium');
                                    $imgSrcMedium=$imgSrcMedium[0];
                                    $imgSrcLarge = wp_get_attachment_image_src($AdditionalFileArray['WpUpload'], 'large');
                                    $imgSrcLarge=$imgSrcLarge[0];
                                    $imgSrcFull = wp_get_attachment_image_src($AdditionalFileArray['WpUpload'], 'full');
                                    $Width=$imgSrcFull[1];
                                    $Height=$imgSrcFull[2];
                                    $AdditionalFilesArray[$key]['IsExifDataChecked'] = true;
                                    $AdditionalFilesArray[$key]['Exif'] = cg_create_exif_data($AdditionalFileArray['WpUpload']);
                                }else if(cg_is_alternative_file_type_video($ImgType)){// added since version 18.0.0
                                    $fileData = wp_get_attachment_metadata($AdditionalFileArray['WpUpload']);
                                    $Width = (!empty($fileData['height'])) ? $fileData['height'] : 0;
                                    $Height = (!empty($fileData['width'])) ? $fileData['width'] : 0;
                                }
                                $AdditionalFilesArray[$key]['post_title'] = $wpPostRow->post_title;
                                $AdditionalFilesArray[$key]['post_name'] = $wpPostRow->post_name;
                                $AdditionalFilesArray[$key]['post_content'] = $wpPostRow->post_content;
                                $AdditionalFilesArray[$key]['post_excerpt'] = $wpPostRow->post_excerpt;
                                $AdditionalFilesArray[$key]['post_mime_type'] = $wpPostRow->post_mime_type;
                                $AdditionalFilesArray[$key]['medium'] = $imgSrcMedium;
                                $AdditionalFilesArray[$key]['large'] = $imgSrcLarge;
                                $AdditionalFilesArray[$key]['full'] = $wpPostRow->guid;
                                $AdditionalFilesArray[$key]['guid'] = $wpPostRow->guid;
                                $AdditionalFilesArray[$key]['type'] = $post_mime_type_fist_part;
                                $AdditionalFilesArray[$key]['NamePic'] = $wpPostRow->post_name;
                                $AdditionalFilesArray[$key]['ImgType'] = $ImgType;
                                $AdditionalFilesArray[$key]['Width'] = $Width;
                                $AdditionalFilesArray[$key]['Height'] = $Height;
                                $AdditionalFilesArray[$key]['rThumb'] = 0;
                            }
                        }
                    }
/*                    var_dump('$AdditionalFilesArray');
                    echo "<pre>";
                    print_r($AdditionalFilesArray);
                    echo "</pre>";*/

                    $wpdb->update(
                        "$tablename1",
                        array('MultipleFiles' => serialize($AdditionalFilesArray)),
                        array('id' => $AdditionalFilesMainRealId),
                        array('%s'),
                        array('%d')
                    );

                }
            }
        }

        if($ActivateUpload==1){
            // json File kreieren wenn instant upload activation an ist!!!
            $picsSQL = $wpdb->get_results( "SELECT DISTINCT $table_posts.*, $tablename1.* FROM $table_posts, $tablename1 WHERE 
                                              (($collect) AND $tablename1.GalleryID='$galeryID' AND $tablename1.Active='1' and $table_posts.ID = $tablename1.WpUpload) 
                                             OR (($collect) AND $tablename1.GalleryID='$GalleryID' AND $tablename1.Active='1' AND $tablename1.WpUpload = 0) 
                            GROUP BY $tablename1.id ORDER BY $tablename1.id DESC");

            if(!empty($picsSQL)){
                $imagesArray = cg_set_json_data_of_row_objects($picsSQL,$galeryID,$wp_upload_dir,$thumbSizesWp,$ExifDataByRealIds);
                $pidsArray = [];
                foreach ($imagesArray as $pid => $imagesArrayValues){
	                $pidsArray[] = $pid;
                }
	            cg_json_upload_form_info_data_files_new($galeryID,$pidsArray);
            }

            foreach ($newImagesPermalinksArray as $nextEntryId => $entryPermalink){
                if(!empty($imagesArray[$nextEntryId])){
                    $imagesArray[$nextEntryId]['entryGuid'] = $entryPermalink;
                }
            }

        }

    }

    // since 19.10.2022 will be done also here, so not available sorting data and then gallery break definitely not appear
    $tstampFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/'.$galeryID.'-gallery-sort-values-tstamp.json';
    file_put_contents($tstampFile, json_encode(time()));

    // since 18.12.2022 will be done also here, so not available info will definetely appear in gallery, escpecially contact entry
    $tstampFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/'.$galeryID.'-gallery-image-info-tstamp.json';
    file_put_contents($tstampFile, json_encode(time()));

    if($InformAdmin==1){
        include(plugin_dir_path(__FILE__).'mail_admin.php');
    }

    if($InformUserUpload==1){
        include(plugin_dir_path(__FILE__).'mail_user_upload.php');
    }

    if($mailConfSettings->SendConfirm==1 && !empty($userMail) && is_user_logged_in()==false){
        if (filter_var($userMail, FILTER_VALIDATE_EMAIL)) {
            include(plugin_dir_path(__FILE__).'mail_confirm.php');
        }
    }

    // Forward confirmation text after upload
    if(empty($_POST['cg_is_in_gallery_form'])){
        $contest_gal1ery_options_input = $wpdb->prefix . "contest_gal1ery_options_input";

        $inputOptionsSQL = $wpdb->get_row( "SELECT * FROM $contest_gal1ery_options_input WHERE GalleryID='$galeryID'"); // hier aufgeh�rt. Die Gallery ID wird nicht �bertragen, muss her geholt werden aus dem Jquery Post vorher oder aus dem Wordpress-PHP
        $Forward = $inputOptionsSQL->Forward;

        $Forward_URL = '';

        if($Forward==1){

            $Forward_URL = $inputOptionsSQL->Forward_URL;
            $Forward_URL = html_entity_decode(stripslashes($Forward_URL));

            $Forward_URLcheck = substr($Forward_URL, 0, 3);
            $Forward_URLcheck = strtolower($Forward_URLcheck);

            if($Forward_URLcheck=='www'){
                if(is_ssl()){
                    $Forward_URL = "https://".$Forward_URL;
                }else{
                    $Forward_URL = "http://".$Forward_URL;
                }
            }

            if($Forward==1){
                // will be processed in submit-form.js
                exit();

            }

        }
    }

    if(!empty($_POST['cg_from_gallery_form_ajax_upload_or_contact'])){

        $isOnlyContactForm = false;

        if(!empty($_POST['isOnlyContactForm'])){
            $isOnlyContactForm = true;
        }

        if($ActivateUpload==1){
            ?>
            <script data-cg-processing="true">
                //  alert(1);

                var gid = <?php echo json_encode($galeryIDuser);?>;
                var categoryId = <?php echo json_encode($categoryId);?>;

                if(cgJsData[gid].vars.upload.UploadedUserFilesAmountPerCategoryArray[categoryId]){
                    cgJsData[gid].vars.upload.UploadedUserFilesAmountPerCategoryArray[categoryId] = parseInt(cgJsData[gid].vars.upload.UploadedUserFilesAmountPerCategoryArray[categoryId])+1;
                }else{
                    cgJsData[gid].vars.upload.UploadedUserFilesAmountPerCategoryArray[categoryId] = 1;
                }

                var gidReal = <?php echo json_encode($galeryID);?>;
                var realIdBefore = <?php echo json_encode($realIdBefore);?>;
                var data = <?php echo json_encode($imagesArray);?>;
                var newImageIdsArray = <?php echo json_encode($collectImageIDs);?>;
                var processedFilesCounter = <?php echo json_encode($processedFilesCounter);?>;
                var isOnlyContactForm = <?php echo json_encode($isOnlyContactForm);?>;
                var isOnlyContactEntry = <?php echo json_encode($isOnlyContactEntry);?>;
                var isAdditionalFilesUpload = parseInt(<?php echo json_encode($proOptions->AdditionalFiles);?>);
                var isBulkUpload = parseInt(<?php echo json_encode($selectSQL1->ActivateBulkUpload);?>);
                var ExifDataByRealIds = <?php echo json_encode($ExifDataByRealIds);?>;
                var AdditionalFilesArray = <?php echo json_encode($AdditionalFilesArray);?>;
                var arrayInfoDataForImageAddedEntries = <?php echo json_encode($arrayInfoDataForImageAddedEntries);?>;

                if(isOnlyContactForm){
                    cgJsClass.gallery.vars.$cgLoadedIds.each(function () {
                        var oneOfGalleryIdsOnPage = jQuery(this).val();
                        var gidAsStringForSure = ''+oneOfGalleryIdsOnPage;
                        if(gidAsStringForSure.indexOf(''+gidReal)==0 && gid!=oneOfGalleryIdsOnPage && oneOfGalleryIdsOnPage.indexOf('-cf')==-1  && oneOfGalleryIdsOnPage.indexOf('-uf')==-1){
                            // works also for multiple bulk upload
                            for(var realId in ExifDataByRealIds){if(!ExifDataByRealIds.hasOwnProperty(realId)){break;}
                                data[realId].Exif = ExifDataByRealIds[realId];
                            }
                            // works only for additional files without multiple bulk upload
                            if(isAdditionalFilesUpload && !isBulkUpload && Object.keys(AdditionalFilesArray).length > 1){
                                data[realIdBefore].MultipleFilesParsed = AdditionalFilesArray;
                            }

                            if(cgJsData[oneOfGalleryIdsOnPage].vars.mainCGallery){
                                cgJsData[oneOfGalleryIdsOnPage].vars.mainCGallery.addClass('cgShowNormalFormConfirmationTextInFullWindow');
                            }
                            cgJsClass.gallery.getJson.imageDataPreProcess(oneOfGalleryIdsOnPage,data,false,processedFilesCounter,true,newImageIdsArray);
                            return false;// do imageDataPreProcess the rest will be done in the imageDataPreProcess if more gallery ids are available
                        }
                    });
                }else{
                    // works also for multiple bulk upload
                    for(var realId in ExifDataByRealIds){if(!ExifDataByRealIds.hasOwnProperty(realId)){break;}
                        data[realId].Exif = ExifDataByRealIds[realId];
                    }
                    // works only for additional files without multiple bulk upload
                    if(isAdditionalFilesUpload && !isBulkUpload && Object.keys(AdditionalFilesArray).length > 1){
                        data[realIdBefore].MultipleFilesParsed = AdditionalFilesArray;
                    }
                    cgJsClass.gallery.views.close(gid,true);
                    for(var realId in arrayInfoDataForImageAddedEntries){if(!arrayInfoDataForImageAddedEntries.hasOwnProperty(realId)){break;}
                        cgJsData[gid].jsonInfoData[realId] = arrayInfoDataForImageAddedEntries[realId];
                    }
                    cgJsClass.gallery.getJson.imageDataPreProcess(gid,data,false,processedFilesCounter,true,newImageIdsArray);
                }

            </script>
            <?php
        }

        // confirmation message will be provided from submit-form.js
        if(!empty($_POST['cg_is_in_gallery_form'])){
            exit('cg_is_in_gallery_form exit');
        }else{
            if($Forward!=1){
                exit('exit normal form if not forward');
            }
        }

    }

    echo "<br/>";

}
else{

    echo "Plz don't manipulate the upload.";

    ?>


    <script data-cg-processing="true">

        var gid = <?php echo json_encode($galeryIDuser);?>;
        cgJsData[gid].vars.upload.doneUploadFailed = true;
        cgJsData[gid].vars.upload.failMessage = <?php echo json_encode("Plz don't manipulate the upload count.");?>;

    </script>

    <?php
    die;

}


?>