<?php
//echo "<pre>";
//print_r($_POST);
//echo "</pre>";

/*error_reporting(E_ALL); 
ini_set('display_errors', 'On');*/

/*echo "<pre>";
echo print_r($_POST);
echo "</pre>";*/

$start = 0; // Startwert setzen (0 = 1. Zeile)
$step = 10;

if (isset($_GET["start"])) {
    $muster = "/^[0-9]+$/"; // reg. Ausdruck f�r Zahlen
    if (preg_match($muster, $_GET["start"]) == 0) {
        $start = 0; // Bei Manipulation R�ckfall auf 0
    } else {
        $start = absint($_GET["start"]);
    }
}

if (isset($_GET["step"])) {
    $muster = "/^[0-9]+$/"; // reg. Ausdruck f�r Zahlen
    if (preg_match($muster, (isset($_GET["start"]) ? absint($_GET["start"]) : 1)) == 0) {
        $step = 10; // Bei Manipulation R�ckfall auf 0
    } else {
        $step = absint($_GET["step"]);
    }
}

global $wpdb;

// Set table names
$tablename = $wpdb->prefix . "contest_gal1ery";
$table_posts = $wpdb->prefix . "posts";
$table_users = $wpdb->base_prefix . "users";
$table_usermeta = $wpdb->base_prefix . "usermeta";
$tablenameOptions = $wpdb->prefix . "contest_gal1ery_options";
$tablenameentries = $wpdb->prefix . "contest_gal1ery_entries";
$tablename_categories = $wpdb->prefix . "contest_gal1ery_categories";
$tablename_pro_options = $wpdb->prefix . "contest_gal1ery_pro_options";
$tablename_comments = $wpdb->prefix . "contest_gal1ery_comments";
$tablename_options_visual = $wpdb->prefix . "contest_gal1ery_options_visual";
$tablename_form_input = $wpdb->prefix . "contest_gal1ery_f_input";

$GalleryID = absint($GalleryID);
$infoPidsArray = [];

// check which fileds are allowed for json save because allowed gallery or single view
$uploadFormFields = $wpdb->get_results($wpdb->prepare("SELECT * FROM $tablename_form_input WHERE GalleryID = %d",[$GalleryID]));
$Field1IdGalleryView = $wpdb->get_var($wpdb->prepare("SELECT Field1IdGalleryView FROM $tablename_options_visual WHERE GalleryID = %d",[$GalleryID]));
//$watermarkPositionId = $wpdb->get_var($wpdb->prepare("SELECT id FROM $tablename_form_input WHERE GalleryID = %d AND WatermarkPosition != '' AND Active = 1",[$GalleryID]));
$IsForWpPageTitleInputId = $wpdb->get_var($wpdb->prepare("SELECT id FROM $tablename_form_input WHERE GalleryID = %d AND IsForWpPageTitle = 1",[$GalleryID]));

$fieldsForSaveContentArray = array();

foreach ($uploadFormFields as $field) {
    if (empty($fieldsForSaveContentArray[$field->id])) {
        $fieldsForSaveContentArray[$field->id] = array();
    }
    $fieldsForSaveContentArray[$field->id]['Field_Type'] = $field->Field_Type;
    $fieldsForSaveContentArray[$field->id]['Field_Order'] = $field->Field_Order;
    $fieldContent = unserialize($field->Field_Content);
    $fieldsForSaveContentArray[$field->id]['Field_Title'] = $fieldContent['titel'];
    if ($field->Field_Type == 'date-f') {
        $fieldsForSaveContentArray[$field->id]['Field_Format'] = $fieldContent['format'];
    }
}


$wpUsers = $wpdb->base_prefix . "users";

$imageInfoArray = array();

$wp_upload_dir = wp_upload_dir();

$jsonUpload = $wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-' . $GalleryID . '/json';
$jsonUploadImageData = $wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-' . $GalleryID . '/json/image-data';
$jsonUploadImageInfoDir = $wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-' . $GalleryID . '/json/image-info';
$jsonUploadImageCommentsDir = $wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-' . $GalleryID . '/json/image-comments';

$thumbSizesWp = array();
$thumbSizesWp['thumbnail_size_w'] = get_option("thumbnail_size_w");
$thumbSizesWp['medium_size_w'] = get_option("medium_size_w");
$thumbSizesWp['large_size_w'] = get_option("large_size_w");

$uploadFolder = wp_upload_dir();

// DELTE PICS FIRST

//echo "DELETE PICS!<br>";
include(__DIR__.'/../delete-pics.php');

$activate = '';
if (!empty($_POST['cg_activate'])) {
    $activate = $_POST['cg_activate'];
}else{
    $_POST['cg_activate'] = array();
}

if (empty($_POST['cg_deactivate'])) {
    $_POST['cg_deactivate'] = array();
}

if (!empty($_POST['cg_row'])) {
    $rowids = $_POST['cg_row'];
} else {
    $rowids = [];
}

$content = array();

if (!empty($_POST['content'])) {
	$_POST['content'] = cg1l_sanitize_post($_POST['content']);
    $content = $_POST['content'];
}else{
    $_POST['content'] = array();
}

if (empty($_POST['imageCategory'])) {
    $_POST['imageCategory'] = array();
}

// unset rowids if Deleted!!!!
if (!empty($_POST['cg_delete'])) {

    foreach ($_POST['cg_delete'] as $key => $value) {
        unset($rowids[$key]);
        unset($content[$key]);
        unset($_POST['imageCategory'][$key]);
        // activate or deactivate can't be send if delete is send! But unset to go sure :)
        unset($_POST['cg_activate'][$key]);
        unset($_POST['cg_deactivate'][$key]);

    }

}

if (!is_dir($jsonUpload)) {
    mkdir($jsonUpload, 0755, true);
}

if (!is_dir($jsonUploadImageData)) {
    mkdir($jsonUploadImageData, 0755, true);
}

if (!is_dir($jsonUploadImageInfoDir)) {
    mkdir($jsonUploadImageInfoDir, 0755, true);
}

if (!is_dir($jsonUploadImageCommentsDir)) {
    mkdir($jsonUploadImageCommentsDir, 0755, true);
}

$jsonFile = $wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-' . $GalleryID . '/json/' . $GalleryID . '-images.json';
$fp = fopen($jsonFile, 'r');
$imageArray = json_decode(fread($fp, filesize($jsonFile)), true);
fclose($fp);

if (!empty($_POST['imageCategory'])) {

    $querySETrowForCategoryIds = 'UPDATE ' . $tablename . ' SET Category = CASE id ';
    $querySETaddRowForCategoryIds = ' ELSE Category END WHERE id IN (';
    $queryArgsArray = [];
    $queryAddArgsArray = [];
    $queryArgsCounter = 0;

    foreach ($_POST['imageCategory'] as $imageId => $categoryId) {

        if ($categoryId == 'off' && is_string($categoryId)) {
            continue;
        } else {

            $imageId = absint(sanitize_text_field($imageId));
            $categoryId = absint(sanitize_text_field($categoryId));

            $querySETrowForCategoryIds .= " WHEN %d THEN %d";
            $querySETaddRowForCategoryIds .= "%d,";
            $queryArgsArray[] = $imageId;
            $queryArgsArray[] = $categoryId;
            $queryAddArgsArray[] = $imageId;
            $queryArgsCounter++;

        }
    }

    // ic = i counter
    for ($ic = 0;$ic<$queryArgsCounter;$ic++){
        $queryArgsArray[] =$queryAddArgsArray[$ic];
    }

    $querySETaddRowForCategoryIds = substr($querySETaddRowForCategoryIds,0,-1);
    $querySETaddRowForCategoryIds .= ")";

    $querySETrowForCategoryIds .= $querySETaddRowForCategoryIds;

    $wpdb->query($wpdb->prepare($querySETrowForCategoryIds,$queryArgsArray));

}

// Change Order Auswahl --- ENDE

$galeryrow = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tablenameOptions WHERE id = %d",[$GalleryID]));

$informORnot = $galeryrow->Inform;

// Update Inform

// START QUERIES --- END

$tablenameemail = $wpdb->prefix . "contest_gal1ery_mail";
$tablenameOptions = $wpdb->prefix . "contest_gal1ery_options";
$tablename_pro_options = $wpdb->prefix . "contest_gal1ery_pro_options";
$contest_gal1ery_f_input = $wpdb->prefix . "contest_gal1ery_f_input";
$selectSQLemail = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tablenameemail WHERE GalleryID = %d",[$GalleryID]));
$proOptions = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tablename_pro_options WHERE GalleryID = %d",[$GalleryID]));

$Manipulate = $proOptions->Manipulate;
$FbLikeNoShare = $proOptions->FbLikeNoShare;
$DataShare = ($FbLikeNoShare == 1) ? 'true' : 'false';
$DataClass = ($proOptions->FbLikeOnlyShare==1) ? 'fb-share-button' : 'fb-like';
$DataLayout = ($proOptions->FbLikeOnlyShare==1) ? 'button' : 'button_count';

$Subject = contest_gal1ery_convert_for_html_output($selectSQLemail->Header);
$Admin = $selectSQLemail->Admin;
$Reply = $selectSQLemail->Reply;
$cc = $selectSQLemail->CC;
$bcc = $selectSQLemail->BCC;
$contentMail = contest_gal1ery_convert_for_html_output($selectSQLemail->Content);

$url = trim(sanitize_text_field($selectSQLemail->URL));
//	$url = (strpos($url,'?')) ? $url.'&' : $url.'?';

$posUrl = "\$url\$";

// echo $posUrl;

$urlCheck = (stripos($contentMail, $posUrl)) ? 1 : 0;

/*echo "<pre>";
print_r($_POST['cg_rThumb']);
echo "</pre>";*/

if(!empty($_POST['cg_rThumb'])){
    $querySETrow = 'UPDATE ' . $tablename . ' SET rThumb = CASE';
    $querySETaddRow = ' ELSE rThumb END WHERE (id) IN (';
    $queryArgsArray = [];
    $queryAddArgsArray = [];
    $queryArgsCounter = 0;

    foreach ($_POST['cg_rThumb'] as $id => $rThumbValue){

        $id = absint (sanitize_text_field($id));
        $rThumbValue = absint(sanitize_text_field($rThumbValue));

        $querySETrow .= " WHEN (id = %d) THEN %d";
        $querySETaddRow .= "(%d), ";
        $queryArgsArray[] = $id;
        $queryArgsArray[] = $rThumbValue;
        $queryAddArgsArray[] = $id;
        $queryArgsCounter++;

    }

    // ic = i counter
    for ($ic = 0;$ic<$queryArgsCounter;$ic++){
        $queryArgsArray[] =$queryAddArgsArray[$ic];
    }

    $querySETaddRow = substr($querySETaddRow, 0, -2);
    $querySETaddRow .= ")";

    $querySETrow .= $querySETaddRow;

    $wpdb->query($wpdb->prepare($querySETrow,$queryArgsArray));
}

// has to be done here extra because might get empty after processing
if(!empty($_POST['cg_multiple_files_for_post']) && !empty($_POST['cgDeleteOriginalImageSourceAlso'])){
    // correct if delete was sent
    if(!empty($_POST['cg_delete']) && !empty($deletedWpUploadsFromSpace)){
        // check for realIds first
        foreach ($_POST['cg_delete'] as $idToDelete){
            if(array_key_exists($idToDelete,$_POST['cg_multiple_files_for_post'])){
                unset($_POST['cg_multiple_files_for_post'][$idToDelete]);
            }
        }
        // check for WpUploads then
        foreach ($_POST['cg_multiple_files_for_post'] as $id => $fileDataForPost){
            $isWpUploadDeleted = false;
            foreach ($fileDataForPost as $order => $array){
                if(in_array($array['WpUpload'],$deletedWpUploadsFromSpace)!==false){
                    $isWpUploadDeleted = true;
                    unset($fileDataForPost[$order]);
                }
            }
            if($isWpUploadDeleted){
                if(empty($fileDataForPost) OR count($fileDataForPost)==1){
                    $_POST['cg_multiple_files_for_post'][$id] = '';// simply empty then multiple files will get empty in field in database
                }else{
                    $newOrder = 1;
                    $newFileDataForPost = [];
                    foreach ($fileDataForPost as $order => $array){
                        $newFileDataForPost[$newOrder] = $array;
                        $newOrder++;
                    }
                    $_POST['cg_multiple_files_for_post'][$id] = $newFileDataForPost;
                }
            }
        }
    }
}

if(!empty($_POST['cg_multiple_files_for_post'])){

    // NamePic
    // ImgType
    // WpUpload
    // Width
    // Height
    // Exif

    $querySETrowMultipleFiles = 'UPDATE ' . $tablename . ' SET MultipleFiles = CASE';
    $querySETaddRowMultipleFiles = ' ELSE MultipleFiles END WHERE (id) IN (';

    $hasRealIdDeleted = false;
    $queryHasRealIdDeleted = 'INSERT INTO '.$tablename.' (id, NamePic, ImgType, WpUpload, Width, Height, rThumb, Exif) VALUES ';
    $queryArgsArray = [];
    $queryAddArgsArray = [];
    $queryArgsCounter = 0;
    $queryArgsArray1 = [];

    foreach ($_POST['cg_multiple_files_for_post'] as $id => $fileDataForPost){

        $id = absint($id);

        if(!empty($fileDataForPost)){

            $fileDataForPostArray = json_decode(stripslashes(sanitize_text_field($fileDataForPost)),true);
            $hasRealId = false;

            foreach ($fileDataForPostArray as $order => $array){

                if(!empty($array['isRealIdSource'])){
                    $hasRealId = true;
                }

                if(!empty($fileDataForPostArray[$order]['ImgType']) && cg_is_is_image($fileDataForPostArray[$order]['ImgType']) && empty($fileDataForPostArray[$order]['Exif']) && empty($fileDataForPostArray[$order]['IsExifDataChecked'])){
                    $fileDataForPostArray[$order]['Exif'] = cg_create_exif_data($fileDataForPostArray[$order]['WpUpload']);
                    if(empty($fileDataForPostArray[$order]['Exif'])){$fileDataForPostArray[$order]['Exif']='';}
                    $fileDataForPostArray[$order]['IsExifDataChecked'] = true;
                }

                $fileDataForPostArray[$order]['WpUpload'] = absint($fileDataForPostArray[$order]['WpUpload']);// absint this value for sure for later queries when files will be deleted to check in serialized MultipleFiles

            }

            if(!$hasRealId){// then realId must be deleted
                $hasRealIdDeleted = true;

                $queryHasRealIdDeleted .= "(%d,%s,%s,%d,%d,%d,%d,%s),";
                $queryArgsArray1[] = $id;
                $queryArgsArray1[] = $fileDataForPostArray[1]['NamePic'];
                $queryArgsArray1[] = $fileDataForPostArray[1]['ImgType'];
                $queryArgsArray1[] = $fileDataForPostArray[1]['WpUpload'];
                $queryArgsArray1[] = absint($fileDataForPostArray[1]['Width']);
                $queryArgsArray1[] = absint($fileDataForPostArray[1]['Height']);
                $queryArgsArray1[] = absint($fileDataForPostArray[1]['rThumb']);
                $queryArgsArray1[] = absint($fileDataForPostArray[1]['Exif']);

                $fileDataForPostArrayNew = [];
                $fileDataForPostArrayNew['WpUpload'] = $fileDataForPostArray[1]['WpUpload'];
                $fileDataForPostArrayNew['isRealIdSource'] = true;
                $fileDataForPostArray[1] = $fileDataForPostArrayNew;
            }

            if(count($fileDataForPostArray)>1){
                $fileDataForPost = serialize($fileDataForPostArray);
            }else{
                //$fileDataForPost = '""';
                $fileDataForPost = '';// now with prepare can be really empty
            }

        }else{
            //$fileDataForPost = '""';
            $fileDataForPost = '';// now with prepare can be really empty
        }
        $querySETrowMultipleFiles .= " WHEN (id = %d) THEN %s";
        $querySETaddRowMultipleFiles .= "(%d), ";
        $queryArgsArray[] = $id;
        $queryArgsArray[] = $fileDataForPost;
        $queryAddArgsArray[] = $id;
        $queryArgsCounter++;

    }

    // ic = i counter
    for ($ic = 0;$ic<$queryArgsCounter;$ic++){
        $queryArgsArray[] =$queryAddArgsArray[$ic];
    }

    $querySETaddRowMultipleFiles = substr($querySETaddRowMultipleFiles, 0, -2);
    $querySETaddRowMultipleFiles .= ")";

    $querySETrowMultipleFiles .= $querySETaddRowMultipleFiles;

    $wpdb->query($wpdb->prepare($querySETrowMultipleFiles,$queryArgsArray));

    if(!empty($hasRealIdDeleted)){
        $queryHasRealIdDeleted = substr($queryHasRealIdDeleted, 0, -1);
        $queryHasRealIdDeleted .= " ON DUPLICATE KEY UPDATE NamePic = VALUES(NamePic), ImgType = VALUES(ImgType), WpUpload = VALUES(WpUpload), Width = VALUES(Width), Height = VALUES(Height),  rThumb = VALUES(rThumb), Exif = VALUES(Exif)";

        $wpdb->query($wpdb->prepare($queryHasRealIdDeleted,$queryArgsArray1));
    }

}

if (!empty($_POST['cg_winner'])) {

    $querySETrowWinner = 'UPDATE ' . $tablename . ' SET Winner = CASE';
    $querySETaddRowWinner = ' ELSE Winner END WHERE (id) IN (';
    $queryArgsArray = [];
    $queryAddArgsArray = [];
    $queryArgsCounter = 0;

    foreach ($_POST['cg_winner'] as $key => $value) {

        $key = absint($key);

        $querySETrowWinner .= " WHEN (id = %d) THEN 1";
        $querySETaddRowWinner .= "(%d), ";
        $queryArgsArray[] = $key;
        $queryAddArgsArray[] = $key;
        $queryArgsCounter++;

    }

    // ic = i counter
    for ($ic = 0;$ic<$queryArgsCounter;$ic++){
        $queryArgsArray[] =$queryAddArgsArray[$ic];
    }

    $querySETaddRowWinner = substr($querySETaddRowWinner, 0, -2);
    $querySETaddRowWinner .= ")";

    $querySETrowWinner .= $querySETaddRowWinner;

    $wpdb->query($wpdb->prepare($querySETrowWinner,$queryArgsArray));

}

if (!empty($_POST['cg_winner_not'])) {

    $querySETrowWinnerNot = 'UPDATE ' . $tablename . ' SET Winner = CASE';
    $querySETaddRowWinnerNot = ' ELSE Winner END WHERE (id) IN (';
    $queryArgsArray = [];
    $queryAddArgsArray = [];
    $queryArgsCounter = 0;

    foreach ($_POST['cg_winner_not'] as $key => $value) {

        $key = absint($key);

        $querySETrowWinnerNot .= " WHEN (id = %d) THEN 0";
        $querySETaddRowWinnerNot .= "(%d), ";
        $queryArgsArray[] = $key;
        $queryAddArgsArray[] = $key;
        $queryArgsCounter++;

    }

    // ic = i counter
    for ($ic = 0;$ic<$queryArgsCounter;$ic++){
        $queryArgsArray[] =$queryAddArgsArray[$ic];
    }

    $querySETaddRowWinnerNot = substr($querySETaddRowWinnerNot, 0, -2);
    $querySETaddRowWinnerNot .= ")";

    $querySETrowWinnerNot .= $querySETaddRowWinnerNot;

    $wpdb->query($wpdb->prepare($querySETrowWinnerNot,$queryArgsArray));

}

$_POST['addCountChange'] = array();


// Rating manipulieren hier

if ($Manipulate == 1) {

    if ($galeryrow->AllowRating == 2) {

        if (!empty($_POST['addCountS'])) {

            $querySETrowAddCount = 'UPDATE ' . $tablename . ' SET addCountS = CASE';
            $querySETaddRowAddCount = ' ELSE addCountS END WHERE (id) IN (';
            $queryArgsArray = [];
            $queryAddArgsArray = [];
            $queryArgsCounter = 0;

            foreach ($_POST['addCountS'] as $key => $value) {

                $_POST['addCountChange'][$key] = $key;

                $key = absint($key);
                $value = absint($value);

                $querySETrowAddCount .= " WHEN (id = %d) THEN %d";
                $querySETaddRowAddCount .= "(%d), ";
                $queryArgsArray[] = $key;
                $queryArgsArray[] = $value;
                $queryAddArgsArray[] = $key;
                $queryArgsCounter++;

            }

            // ic = i counter
            for ($ic = 0;$ic<$queryArgsCounter;$ic++){
                $queryArgsArray[] =$queryAddArgsArray[$ic];
            }

            $querySETaddRowAddCount = substr($querySETaddRowAddCount, 0, -2);
            $querySETaddRowAddCount .= ")";

            $querySETrowAddCount .= $querySETaddRowAddCount;

            $wpdb->query($wpdb->prepare($querySETrowAddCount,$queryArgsArray));

        }
    }

    if ($galeryrow->AllowRating == 1 OR ($galeryrow->AllowRating >= 12 AND $galeryrow->AllowRating <=20)) {

        for ($forCounter = 1;$forCounter<=10;$forCounter++){
            if (!empty($_POST['addCountR'.$forCounter])) {

                    $querySETrowAddCount = 'UPDATE ' . $tablename . ' SET addCountR'.$forCounter.' = CASE';
                    $querySETaddRowAddCount = ' ELSE addCountR'.$forCounter.' END WHERE (id) IN (';
                    $queryArgsArray = [];
                    $queryAddArgsArray = [];
                    $queryArgsCounter = 0;

                    foreach ($_POST['addCountR'.$forCounter] as $key => $value) {

                        $_POST['addCountChange'][$key] = $key;

                        $key = absint($key);
                        $value = absint($value);

                        $querySETrowAddCount .= " WHEN (id = %d) THEN %d";
                        $querySETaddRowAddCount .= "(%d), ";
                        $queryArgsArray[] = $key;
                        $queryArgsArray[] = $value;
                        $queryAddArgsArray[] = $key;
                        $queryArgsCounter++;

                    }

                    // ic = i counter
                    for ($ic = 0;$ic<$queryArgsCounter;$ic++){
                        $queryArgsArray[] =$queryAddArgsArray[$ic];
                    }

                    $querySETaddRowAddCount = substr($querySETaddRowAddCount, 0, -2);
                    $querySETaddRowAddCount .= ")";

                    $querySETrowAddCount .= $querySETaddRowAddCount;

                    $wpdb->query($wpdb->prepare($querySETrowAddCount,$queryArgsArray));

                    }
            }

    }
}

// Insert fields content

include('1_content.php');

// Insert fields content fb like

include('1_content-fb-like.php');

// Insert fields content --- END

// 	Bilder daktivieren
include('2_deactivate.php');

// Reinfolge Bilder ändern (old file 3_row-order.php', not used anymore and deleted)
//include('3_row-order.php');

// 	Bilder aktivieren
include('4_activate.php');

// !IMPORTANT: have to be done before 5_create-no-script-html
include('5_set-image-array.php');

//do_action('cg_json_upload_form_info_data_files',$GalleryID,null);

include('5_create-no-script-html.php');

// Reset informierte Felder

// Reset informierte Felder ---- END

// Inform Users if picture is activated per Mail
include('7_inform.php');

// Move to another gallery selected images to move
//include('8_move-to-another-gallery.php');

// Inform Users if picture is activated per Mail --- END

//echo "<p id='cg_changes_saved' style='font-size:18px;'><strong>Changes saved</strong></p>";



?>