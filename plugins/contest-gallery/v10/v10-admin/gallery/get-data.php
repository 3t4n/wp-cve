<?php
/*error_reporting(E_ALL); 
ini_set('display_errors', 'On');
AUFBAU
*/

// Tabellennamen ermitteln, GalleryID wurde als Shortcode bereits �bermittelt.
global $wpdb;

$tablename = $wpdb->prefix . "contest_gal1ery";
$tablenameOptions = $wpdb->prefix . "contest_gal1ery_options";
$tablename_pro_options = $wpdb->prefix . "contest_gal1ery_pro_options";
$tablename_google_users = $wpdb->prefix . "contest_gal1ery_google_users";
$tablename_form_input = $wpdb->prefix . "contest_gal1ery_f_input";

$isNewGallery = false;

$thumbSizesWp = array();
$thumbSizesWp['thumbnail_size_w'] = get_option("thumbnail_size_w");
$thumbSizesWp['medium_size_w'] = get_option("medium_size_w");
$thumbSizesWp['large_size_w'] = get_option("large_size_w");

$search = '';
if(!empty($_POST['cg_search'])){
    if(!empty(trim($_POST['cg_search']))){
        $search = trim(sanitize_text_field($_POST['cg_search']));
    }
}

if(!empty($_GET['option_id'])){
    $GalleryID = absint(sanitize_text_field($_GET['option_id']));
}else{

    if(empty($_POST['cg_id'])){
        $isNewGallery = true;
        // dann hat er reloaded und einfach die letzte gallerie anzeigen
        $GalleryID = $wpdb->get_var("SELECT MAX(id) FROM $tablenameOptions");
    }else{
        $GalleryID = absint(sanitize_text_field($_POST['cg_id']));
    }

}

$start = 0; // Startwert setzen (0 = 1. Zeile)
$step =10;
$order ='date_desc';


if (isset($_GET["start"])) {
    $muster = "/^[0-9]+$/"; // reg. Ausdruck f�r Zahlen
    if (preg_match($muster, $_GET["start"]) == 0) {
        $start = 0; // Bei Manipulation R�ckfall auf 0
    } else {
        $start = absint(sanitize_text_field($_GET["start"]));
    }
}else{
    if(isset($_POST['cg_start'])){
        $start = absint(sanitize_text_field($_POST['cg_start']));
    }else{
        $start = 0;
    }
}

if(!is_numeric($start)){
    $start = 0;
}

// reset to 0 if steps were changed
if(isset($_POST['cgStepsChanged'])){
    $start = 0;
}

if (isset($_GET["step"])) {
    $muster = "/^[0-9]+$/"; // reg. Ausdruck f�r Zahlen
    if (preg_match($muster, (isset($_GET["start"]) ? $_GET["start"] : 1)) == 0) {
        $step = 10; // Bei Manipulation R�ckfall auf 0
    } else {
        $step = absint(sanitize_text_field($_GET["step"]));
    }
}else{
    if(isset($_POST['cg_step'])){
        $step = absint(sanitize_text_field($_POST['cg_step']));
    }else{
        $step = 10;
    }
}

if(isset($_POST['cg_order'])){
    $order = sanitize_text_field($_POST['cg_order']);
}else{
    $order = 'date_desc';
}


// since point based system sorting by average is deprecated
/*if(($AllowRating==1 OR ($AllowRating >= 12 AND $AllowRating <=20)) && $checkTablenameIPentries>=1){
    $orderByAverage = '<option value="rating_desc_average" id="cg_rating_desc_average">Rating average descend without manipulation (longer loading possible if many images or votes)</option>
        <option value="rating_asc_average" id="cg_rating_asc_average">Rating average ascend without manipulation (longer loading possible if many images or votes)</option>';
}*/

// since point based system sorting by average is deprecated
/*if(($AllowRating==1 OR ($AllowRating >= 12 AND $AllowRating <=20)) && $Manipulate==1 && $checkTablenameIPentries>=1){
    $orderByAverageWithManip = '<option value="rating_desc_average_with_manip" id="cg_rating_desc_average_with_manip">Rating average descend with manipulation (longer loading possible if many images or votes)</option>
        <option value="rating_asc_average_with_manip" id="cg_rating_asc_average_with_manip">Rating average ascend with manipulation (longer loading possible if many images or votes)</option>';
}*/

// fallback to go sure if empty or old order options are activated
if(empty(trim($order))){
    $order = 'date_desc';
}else if($order=='rating_desc_average' OR $order=='rating_asc_average' OR $order=='rating_desc_average_with_manip' OR $order=='rating_asc_average_with_manip'){
    $order = 'date_desc';
}

$upload_form_inputs = $wpdb->get_results($wpdb->prepare( "SELECT * FROM $tablename_form_input WHERE GalleryID = %d"  ,[
    $GalleryID
]));

foreach ($upload_form_inputs as $key => $upload_input){
    $upload_form_inputs[$key]->Field_Content = unserialize($upload_input->Field_Content);
}

$pro_options = $wpdb->get_row($wpdb->prepare( "SELECT * FROM $tablename_pro_options WHERE GalleryID = %d"  ,[
    $GalleryID
]));

$Manipulate = $pro_options->Manipulate;
$ShowOther = $pro_options->ShowOther;
$IsModernFiveStar = $pro_options->IsModernFiveStar;
$RegUserUploadOnly = $pro_options->RegUserUploadOnly;
$FbLikeOnlyShare = $pro_options->FbLikeOnlyShare;
$CatWidgetColor = ($pro_options->CatWidget==1) ? "background-color:#ffffff" : 'background-color:#ffffff';
$CatWidgetChecked = ($pro_options->CatWidget==1) ? "checked='checked'" : '';
$ShowCatsUnchecked = ($pro_options->ShowCatsUnchecked==1) ? "checked='checked'" : '';
$ShowCatsUnfolded = ($pro_options->ShowCatsUnfolded==1) ? "checked='checked'" : '';

//$rowType = $wpdb->get_var("SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$tablename' AND column_name = 'Rating'");
//var_dump($rowType);

//$tablenameVisualOptions = $wpdb->prefix . "contest_gal1ery_options_visual";
$tablenameComments = $wpdb->prefix . "contest_gal1ery_comments";
$tablenameentries = $wpdb->prefix . "contest_gal1ery_entries";
$tablename_create_user_entries = $wpdb->prefix . "contest_gal1ery_create_user_entries";
$tablename_f_input = $wpdb->prefix . "contest_gal1ery_f_input";
$tablename_categories = $wpdb->prefix . "contest_gal1ery_categories";
$tablenameIP = $wpdb->prefix ."contest_gal1ery_ip";
$table_posts = $wpdb->prefix."posts";
$wpUsers = $wpdb->base_prefix . "users";

$categories = $wpdb->get_results($wpdb->prepare( "SELECT * FROM $tablename_categories WHERE GalleryID = %d ORDER BY Field_Order",[$GalleryID]));

if(!empty($categories)){

    $categoriesSumString = "SUM(CASE
             WHEN Category = '0' THEN 1
             ELSE 0
           END) AS Category0";

    foreach ($categories as $category){

        $categoriesSumString .= ", SUM(CASE
             WHEN Category = '".$category->id."' THEN 1
             ELSE 0
           END) AS Category".$category->id."";

    }

    $countCategoriesObject = $wpdb->get_results($wpdb->prepare( "SELECT Category, $categoriesSumString  FROM $tablename WHERE GalleryID = %d AND Active = '1' GROUP BY Category ",[
        $GalleryID
    ]));

}

if(!empty($_POST['cg_image_rotate_save_values'])){

    $rThumbValue = absint($_POST['rThumb']);

    $wpdb->update(
        "$tablename",
        array('rThumb' => $rThumbValue),
        array(
            'id' => absint($_POST['cg_image_rotate_id'])
        ),
        array('%d'),
        array('%d')
    );

    $wp_upload_dir = wp_upload_dir();

    $jsonUpload = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json';
    if(!is_dir($jsonUpload)){
        mkdir($jsonUpload,0755,true);
    }
    // save images file
    $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-images.json';

    $fp = fopen($jsonFile, 'r');
    $imagesArray = json_decode(fread($fp,filesize($jsonFile)),true);
    fclose($fp);

    $imagesArray[$_POST['cg_image_rotate_id']]['rThumb'] = $rThumbValue;

    $fp = fopen($jsonFile, 'w');
    $imagesArray = fwrite($fp,json_encode($imagesArray));
    fclose($fp);

    // save image data file
    $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/image-data/image-data-'.$_POST['cg_image_rotate_id'].'.json';

    if(file_exists($jsonFile)){
        $fp = fopen($jsonFile, 'r');
        $imageArray = json_decode(fread($fp,filesize($jsonFile)),true);
        fclose($fp);

        $imageArray['rThumb'] = $rThumbValue;

        $fp = fopen($jsonFile, 'w');
        $imageArray = fwrite($fp,json_encode($imageArray));
        fclose($fp);
    }

    // Tstamp has to be modified.
    $tstampFile = $wp_upload_dir["basedir"]."/contest-gallery/gallery-id-$GalleryID/json/$GalleryID-gallery-tstamp.json";
    $fp = fopen($tstampFile, 'w');
    fwrite($fp, time());
    fclose($fp);


}

// Reset Informed

// Reset von allen Informed
if(!empty($_GET['resetInformed'])){
    if($_GET['resetInformed']=='true'){

        //echo "<br>resetInformed: $resetInformed<br>";

        //$querySEToptions = "UPDATE $tablename SET Informed='0' WHERE Informed = '1' AND GalleryID = '$GalleryID' ";
        //$updateSQL = $wpdb->query($querySEToptions);

        $wpdb->update(
            "$tablename",
            array('Informed' => '0'),
            array(
                'id' => '1',
                'GalleryID' => "$GalleryID"
            ),
            array('%d'),
            array('%d','%d')
        );

    }
}

// Reset Informed --- ENDE

$optionsSQL = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tablenameOptions WHERE id = %d"  ,[
    $GalleryID
]));

$AllowRating = $optionsSQL->AllowRating;
if($AllowRating==1){// this is required for the coming for loops
    $AllowRating = 15;
}
$galleryDbVersion = $optionsSQL->Version;

//echo "$GalleryID";

/*$selectSQL = $wpdb->get_results("SELECT $tablename.id,  $tablename.rowid,  $tablename.Timestamp,  $tablename.NamePic, $tablename.CountC,  $tablename.Rating,  $tablename.CountR,  $tablename.CountS,  $tablename.WpUpload,  $tablename.WpUserId
    FROM $tablename, $wpUsers WHERE $tablename.WpUserId = $wpUsers.ID
    AND ($wpUsers.user_login LIKE '%".@$_POST['cg-user-name']."%' or $wpUsers.user_email LIKE '%".@$_POST['cg-user-name']."%')";*/
//var_dump($isAjaxCall);

$fieldsToSelectString = "id, rowid, Timestamp, NamePic, ImgType, CountC, CountCtoReview, CountR, CountS, Rating, GalleryID, Active, Informed, WpUpload,
             Width, Height, WpUserId, rSource, rThumb, addCountS, addCountR1, addCountR2, addCountR3, addCountR4, addCountR5, addCountR6, addCountR7, 
             addCountR8, addCountR9, addCountR10,Category, Exif, IP, CountR1, CountR2, CountR3, CountR4, CountR5, CountR5, CountR6, CountR7, CountR8, CountR9, CountR10, 
             Version, CheckSet, CookieId, Winner, MultipleFiles, WpPage, WpPageUser, WpPageNoVoting, WpPageWinner";
if($isAjaxCall){
    // dann wurde die seite gerade aufgerufen oder ein reload gemacht nach dem neue gallery kreiert worden ist
    if($isNewGallery){
        //   var_dump('new gallery');
        $selectSQL = $wpdb->get_results($wpdb->prepare(  "SELECT * FROM $tablename WHERE GalleryID = %d ORDER BY id DESC LIMIT %d, %d ",[
            $GalleryID,$start,$step
        ]));

    }else{
        // since point based system sorting by average is deprecated
        if($order=='rating_desc_average' || $order=='rating_asc_average' || $order=='rating_desc_average_with_manip' || $order=='rating_asc_average_with_manip'){
            $order = 'date_desc';
        }
        // dann muss es ein ajax call sein!!!
        if($search==='' && !($order=='rating_desc_sum' || $order=='rating_asc_sum' || $order=='rating_desc_sum_with_manip' || $order=='rating_asc_sum_with_manip' || $order=='rating_desc_average' || $order=='rating_asc_average' || $order=='rating_desc_average_with_manip' || $order=='rating_asc_average_with_manip') && strpos($order, '_for_id_') === false){
            include ('order-gallery/order-without-search-and-average.php');
        }/*else if(($order=='rating_desc_average' || $order=='rating_asc_average' || $order=='rating_desc_average_with_manip' || $order=='rating_asc_average_with_manip') && strpos($order, '_for_id_') === false){
            include ('order-gallery/order-by-average-with-and-without-search.php');// will not be used anymore, just average example
        }*/else if(($order=='rating_desc_sum' || $order=='rating_asc_sum' || $order=='rating_desc_sum_with_manip' || $order=='rating_asc_sum_with_manip') && strpos($order, '_for_id_') === false){
            include ('order-gallery/order-by-sum-with-and-without-search.php');
        }else if(strpos($order, '_for_id_') !== false){
            include ('order-gallery/order-custom-fields-with-and-without-search.php');
        }else{
            include ('order-gallery/order-with-search-and-without-average.php');
        }
    }

    $countSelectSQL = count($selectSQL);

}

// check if an tablename ip entry exists
// seems to be fastest query https://stackoverflow.com/questions/1676551/best-way-to-test-if-a-row-exists-in-a-mysql-table#answer-10688065
$checkTablenameIPentries = count($wpdb->get_results( "SELECT 1 FROM $tablenameIP WHERE id >0 LIMIT 1" ));


if($search===''){

    $selectWinnersOnly = '';
    if(!empty($_POST['cg_show_only_winners'])){
        $selectWinnersOnly = " AND Winner = 1 ";
    }

    $selectActiveOnly = '';

    if(!empty($_POST['cg_show_only_active'])){
        $selectActiveOnly = " AND Active = 1 ";
    }

    $selectInactiveOnly = '';

    if(!empty($_POST['cg_show_only_inactive'])){
        $selectInactiveOnly = " AND Active = 0 ";
    }

    $rows = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) AS NumberOfRows FROM $tablename WHERE GalleryID = %d $selectWinnersOnly$selectActiveOnly$selectInactiveOnly" ,[
        $GalleryID
    ]));

}else{
    $rows = $countSearchSQL;
}




// Pr�fen ob ein bestimmtes input Feld als Forward URL dienen soll

$Use_as_URL_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM $tablename_f_input WHERE GalleryID = %d AND Use_as_URL = '1' " ,[
    $GalleryID
]));

// Pr�fen ob ein bestimmtes input Feld als Forward URL dienen soll --- ENDE




// Selected Slider Fields bestimmen

$selectFormInput = $wpdb->get_results($wpdb->prepare( "SELECT id, Show_Slider FROM $tablename_f_input WHERE GalleryID = %d AND Show_Slider = '1' ",[
    $GalleryID
]));


$ShowSliderInputID = array();

$ShowSliderInputID[] = 0;

foreach ($selectFormInput as $value) {

    //$ShowSlider = 	$value->Show_Slider;
    $ShowSliderInputID[] = $value->id;

}

//print_r($ShowSliderInputID);

//echo "lol";

// Selected Fields bestimmen -- ENDE

//$optionsSQL = $wpdb->get_row( "SELECT * FROM $tablename_f_input WHERE id = '$GalleryID'" );

$selectFormInput = $wpdb->get_results($wpdb->prepare("SELECT id, Field_Type, Field_Order, Field_Content FROM $tablename_f_input WHERE GalleryID = %d AND (Field_Type = 'check-f' OR Field_Type = 'text-f' OR Field_Type = 'comment-f' OR Field_Type ='email-f' OR Field_Type ='select-f'  OR Field_Type ='selectc-f' OR Field_Type ='url-f' OR Field_Type ='date-f') ORDER BY Field_Order ASC" ,[
    $GalleryID
]));

//$checkInformed = $wpdb->get_var("SELECT COUNT(*) as NumberOfRows FROM $tablename WHERE GalleryID = '$GalleryID' AND Informed = '1' LIMIT 1");

// Die Field_Content Felder werden jetzt schon usnerialized und in einem Array gespeichert um weniger Load zu erzeugen
/*
echo "<pre>";
print_r($selectFormInput);
echo "</pre>";
*/

if($isAjaxCall){

    // entries select here
    $selectEntriesQuery = "SELECT * FROM $tablenameentries WHERE";

    foreach($selectFormInput as $selectFormInputRow){

        foreach($selectSQL as $imageRow){
            $selectEntriesQuery .= " (pid = $imageRow->id AND f_input_id = $selectFormInputRow->id) OR";
        }

    }

    $selectEntriesQuery = substr($selectEntriesQuery,0,-3);
    $selectEntriesQuery .= " ORDER BY pid DESC, Field_Order DESC";
    $allEntries = $wpdb->get_results("$selectEntriesQuery");

    $allEntriesByImageIdArrayWithContent = array();
    $wpUsersIdsArray = array();
    $googleUsersIdsArray = array();
    $wpUsersIdsWithNotConfirmedMailArray = array();

    $querySETrowLongText = 'UPDATE ' . $tablenameentries . ' SET Long_Text = CASE';
    $querySETaddRowLongText = ' ELSE Long_Text END WHERE (pid,f_input_id) IN (';

    $wpPostIdsArray = array();
    $wpPostIdsArray = array();
    $fbLikeLinksArray = array();

    foreach($selectSQL as $imageRow){

        if($optionsSQL->FbLike==1 OR $FbLikeOnlyShare==1){
            $fbLikeLinksArray[$imageRow->id] = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/'.$imageRow->Timestamp."_".$imageRow->NamePic."413.html";
        }

        // entries create here
        if(empty($allEntriesByImageIdArrayWithContent[$imageRow->id])){
            $allEntriesByImageIdArrayWithContent[$imageRow->id] = array();
        }

        foreach($selectFormInput as $selectFormInputRow){
            foreach($allEntries as $entryRow){
                if($entryRow->pid==$imageRow->id && $entryRow->f_input_id==$selectFormInputRow->id){
                    $textColumn = 'Short_Text';
                    if($entryRow->Field_Type == 'comment-f' OR $entryRow->Field_Type == 'check-f'){
                        $textColumn = 'Long_Text';
                    }
                    if($entryRow->Field_Type == 'date-f'){
                        $textColumn = 'InputDate';
                    }
                    $allEntriesByImageIdArrayWithContent[$imageRow->id][$selectFormInputRow->id]['Content'] = $entryRow->$textColumn;
                    $allEntriesByImageIdArrayWithContent[$imageRow->id][$selectFormInputRow->id]['Checked'] = $entryRow->Checked;
                    $allEntriesByImageIdArrayWithContent[$imageRow->id][$selectFormInputRow->id]['ConfMailId'] = $entryRow->ConfMailId;
                }
            }
            if(empty($allEntriesByImageIdArrayWithContent[$imageRow->id][$selectFormInputRow->id])){
                $allEntriesByImageIdArrayWithContent[$imageRow->id][$selectFormInputRow->id]['Content']  = '';
                $allEntriesByImageIdArrayWithContent[$imageRow->id][$selectFormInputRow->id]['Checked'] = 0;
                $allEntriesByImageIdArrayWithContent[$imageRow->id][$selectFormInputRow->id]['ConfMailId'] = 0;
            }
        }

        // wp users ids create here
        if(!in_array($imageRow->WpUserId,$wpUsersIdsArray)){
            $wpUsersIdsArray[] = $imageRow->WpUserId;
        }

        // wp posts ids create here
        if(!in_array($imageRow->WpUpload,$wpPostIdsArray)){
            if(!empty($imageRow->MultipleFiles) && $imageRow->MultipleFiles!='""'){
                $MultipleFilesUnserialized = unserialize($imageRow->MultipleFiles);
                foreach ($MultipleFilesUnserialized as $MultipleFile){
                    if(!empty($MultipleFile->isRealIdSource)){
                        continue;
                    }else{
                        $wpPostIdsArray[] = $MultipleFile['WpUpload'];
                    }
                }
            }else{
            $wpPostIdsArray[] = $imageRow->WpUpload;
        }
        }

    }

    $fbLikeContentArray = array();

    if($optionsSQL->FbLike==1 OR $FbLikeOnlyShare==1){

        if(count($fbLikeLinksArray)){

            foreach($fbLikeLinksArray as $imageId => $link){

                $fbLikeContentArray[$imageId] = array();

                if(file_exists($link)){

                    $handle = fopen($link, "r");

                    if ($handle) {

                        $isTitlePassed = false;
                        $isDescriptionPassed = false;

                        while (($line = fgets($handle)) !== false) {

                            if(!$isDescriptionPassed){
                                if($isTitlePassed){
                                    if(strpos($line,'og:description')!==false){
                                        $isDescriptionPassed = true;
                                        // regex
                                        // preg match example from https://stackoverflow.com/questions/23343087/php-preg-match-return-position-of-last-match
                                        preg_match('/content="(.*?)" \/>/', (string)$line, $match);
                                        if(!empty($match)){
                                            $fbLikeContentArray[$imageId]['description'] = $match[1];
                                        }else{
                                            $fbLikeContentArray[$imageId]['description'] = '';
                                        }
                                    }
                                }
                                if(strpos($line,'property="og:title"')!==false){
                                    $isTitlePassed = true;
                                    // regex
                                    // preg match example from https://stackoverflow.com/questions/23343087/php-preg-match-return-position-of-last-match
                                    preg_match('/content="(.*?)" \/>/', (string)$line, $match);
                                    if(!empty($match)){
                                        $fbLikeContentArray[$imageId]['title'] = $match[1];
                                    }else{
                                        $fbLikeContentArray[$imageId]['title'] = '';
                                    }
                                }
                            }else{
                                break;
                            }

                        }

                        fclose($handle);

                    }

                }else{
                    $fbLikeContentArray[$imageId]['title'] = '';
                    $fbLikeContentArray[$imageId]['description'] = '';
                }

            }

        }

    }


    // select not confirmed users
    $selectWpUsersNotConfirmedQuery = "SELECT DISTINCT wp_user_id FROM $tablename_create_user_entries WHERE";

    foreach ($wpUsersIdsArray as $userId){
        $selectWpUsersNotConfirmedQuery .= " (wp_user_id = $userId AND activation_key != '') OR ";
    }

    $selectWpUsersNotConfirmedQuery = substr($selectWpUsersNotConfirmedQuery,0,-3);
    $allWpUsersNotConfirmed = $wpdb->get_results($selectWpUsersNotConfirmedQuery);

    foreach ($allWpUsersNotConfirmed as $notConfirmedUser){
        $wpUsersIdsWithNotConfirmedMailArray[] = $notConfirmedUser->wp_user_id;
    }

    // wp users select here
    $selectWpUsersQuery = "SELECT ID, user_login, user_nicename, user_email, display_name FROM $wpUsers WHERE";

    foreach ($wpUsersIdsArray as $userId){
        $selectWpUsersQuery .= " (ID = $userId) OR";
    }

    $selectWpUsersQuery = substr($selectWpUsersQuery,0,-3);
    $selectWpUsersQuery .= " ORDER BY ID DESC";
    $allWpUsers = $wpdb->get_results($selectWpUsersQuery);

    $allWpUsersByIdArray = array();

    foreach($allWpUsers as $wpUserRow){
        if(empty($allWpUsersByIdArray[$wpUserRow->ID])){
            $allWpUsersByIdArray[$wpUserRow->ID] =  json_decode(json_encode($wpUserRow), true);
        }
    }


    // image post info select here
    $allWpPostsByWpUploadIdArray = array();

    if(!empty($wpPostIdsArray)){// check has to be done otherwise might lead to error on some servers!!
    $selectWpPostsQuery = "SELECT guid, post_title, post_name, post_content, post_excerpt, post_mime_type, ID FROM $table_posts WHERE";

    foreach ($wpPostIdsArray as $postId){
        $selectWpPostsQuery .= " (ID = $postId) OR";
    }

    $selectWpPostsQuery = substr($selectWpPostsQuery,0,-3);
    $selectWpPostsQuery .= " ORDER BY ID DESC";
    $allWpPosts = $wpdb->get_results($selectWpPostsQuery);


    foreach($allWpPosts as $wpPostRow){
        if(empty($allWpPostsByWpUploadIdArray[$wpPostRow->ID])){
            $allWpPostsByWpUploadIdArray[$wpPostRow->ID] =  json_decode(json_encode($wpPostRow), true);
        }
    }
    }

    // wp users select here --- END

    // select google users
    $googleUsersArray = [];

    $selectGoogleUsersQuery = "SELECT DISTINCT WpUserId, ImageUrl FROM $tablename_google_users WHERE";

    foreach ($wpUsersIdsArray as $userId){
        $selectGoogleUsersQuery .= " WpUserId = $userId OR ";
    }

    $selectGoogleUsersQuery = substr($selectGoogleUsersQuery,0,-3);
    $googleUsers = $wpdb->get_results($selectGoogleUsersQuery);

    foreach ($googleUsers as $googleUser){
        $googleUsersArray[$googleUser->WpUserId] = [];
        $googleUsersArray[$googleUser->WpUserId]['ImageUrl'] = $googleUser->ImageUrl;
    }
    // select google users --- END

}

$selectContentFieldArray = array();
$dateFieldFormatTypesArray = array();

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

    if($selectFieldType=='date-f'){
        $dateFieldFormatTypesArray[$id] = $selectContentField["format"] ;
    }

    /*        if($value->Field_Type=='selectc-f'){

                $selectCategoriesArray = html_entity_decode($selectContentField["content"]);
                $selectCategoriesArray = preg_split('/\r\n|\r|\n/', $selectCategoriesArray);

            }*/

}

//print_r($dateFieldFormatTypesArray);


// ------------ Ermitteln der Options-Daten

$RowLook = $optionsSQL->RowLook; // Bilder in einer Reiehe nacheinander anzeigen oder nicht
$LastRow = $optionsSQL->LastRow; // Wenn $RowLook an dann wie viele Bilder sollen in letzter Spalte gezeigt werden
//$PicsPerSite = $optionsSQL->PicsPerSite; // Wie viele Bilder sollen insgesamt  gezeigt werden --- UPDATE: Wird bereits weiter oben oder bei get-data-1.php ermittelt
$PicsInRow = $optionsSQL->PicsInRow; // Wie viele Bilder in einer Reiehe sollen gezeigt werden
$GalleryName = $optionsSQL->GalleryName;
$WidthGalery = $optionsSQL->WidthGallery;
$HeightGalery = $optionsSQL->HeightGallery;
$DistancePics = $optionsSQL->DistancePics;
$DistancePicsV = $optionsSQL->DistancePicsV;
$AllowComments = $optionsSQL->AllowComments;

if(!empty($optionsSQL->ScalePics)){
    $ScalePics = $optionsSQL->ScalePics;
}else{
    $ScalePics = 0;
}

$ScaleAndCut = $optionsSQL->ScaleAndCut;
$AllowGalleryScript = $optionsSQL->AllowGalleryScript;
$ThumbsInRow = $optionsSQL->ThumbsInRow; // Anzahl der Bilder in einer Reihe bei Auswahl von ReihenAnsicht (Semi-Flickr-Ansicht)
$ThumbLook = $optionsSQL->ThumbLook;
$WidthThumb = $optionsSQL->WidthThumb; // Breite der ThumbBilder (Normale Ansicht mit Bewertung etc.)
$HeightThumb = $optionsSQL->HeightThumb;  // H�he der ThumbBilder (Normale Ansicht mit Bewertung etc.)
$LastRow = $optionsSQL->LastRow;
$FullSize = $optionsSQL->FullSize;
$IpBlock = $optionsSQL->IpBlock;
$FbLike = $optionsSQL->FbLike;
$ScaleOnly = $optionsSQL->ScaleOnly;
if(!empty($optionsSQL->JqgGalery)){
    $JqgGalery = $optionsSQL->JqgGalery;
}else{
    $JqgGalery = 0;
}
$Inform = $optionsSQL->Inform;
$ForwardToURL = $optionsSQL->ForwardToURL;
$ForwardType = $optionsSQL->ForwardType;
$cgVersion = floatval($optionsSQL->Version);

// Notwendig um sp�ter die star Icons anzuzeigen
$iconsURL = plugins_url().'/'.cg_get_version().'/v10/v10-css';

$starOn = $iconsURL.'/star_48_reduced.png';
$starOff = $iconsURL.'/star_off_48_reduced_with_border.png';

$titleIcon = $iconsURL.'/backend/img-title-icon.png';
$descriptionIcon = $iconsURL.'/backend/img-description-icon.png';
$excerptIcon = $iconsURL.'/backend/img-excerpt-icon.png';
$cgDragIcon = $iconsURL.'/backend/cg-drag-icon.png';

$WidthThumb = $WidthThumb.'px';// Breite Thumb mit px f�r Heredoc
$HeightThumb = $HeightThumb.'px';// H�he Thumb mit px f�r Heredoc
$DistancePics = $DistancePics.'px'; // Abstand der Thumbs Breite mit px f�r Heredoc
$DistancePicsV = $DistancePicsV.'px'; // Abstand der Thumbs H�he mit px f�r Heredoc

// Ermitteln ob checked oder nicht

$selectedCheckComments = ($AllowComments==1) ? 'checked' : '';
$selectedCheckRating = ($AllowRating==1 OR ($AllowRating>=12 && $AllowRating<=20)) ? 'checked' : '';
$selectedCheckIp = ($IpBlock==1) ? 'checked' : '';
$selectedCheckFb = ($FbLike==1) ? 'checked' : '';
$selectedCheckScalePics = ($ScalePics==1) ? 'checked' : '';
//$selectedCheckPicUpload = ($value->PicUpload==1) ? 'checked' : '';
//$selectedCheckSendEmail = ($value->SendEmail==1) ? 'checked' : '';
//$selectedSendName = ($value->SendName==1) ? 'checked' : '';
//$selectedCheckSendComment = ($value->SendComment==1) ? 'checked' : '';
//$AllowGalleryScript = ($AllowGalleryScript==1) ? 'checked' : '';


$urlSource = site_url();









// ----------  Auswahl aufsteigend oder absteigend

/*if (@$_POST['dauswahl']==false) {

$galeryrow = $wpdb->get_row( "SELECT * FROM $tablenameOptions WHERE id = '$GalleryID'" );

$orderpicsdesc = ($galeryrow->OrderPics == 0) ? 'selected' : '';
$orderpicsasc = ($galeryrow->OrderPics == 1) ? 'selected' : '';

}


    // Show choice desc or asc

    if (@@$_POST['dauswahl'] == "dab" OR @@$_GET['dauswahl'] == "dab") {
    $turn = 'DESC';
    $turn1 = 'dab';
    $orderpicsdesc = 'selected';
    }

    echo @$_POST['dauswahl'];

    if (@@$_POST['dauswahl'] == "dauf"  OR @@$_GET['dauswahl'] == "dauf") {
    $turn = 'ASC';
    $turn1 = 'dauf';
    $orderpicsasc = 'selected';
    }

    else {
    $turn = 'DESC';
    } */

// Auswahl aufsteigend oder absteigend ----------- ENDE


//// Config how many pics should be shown at one time

$i=0;



$nr1 = $start + 1;



// Configuration link urls ----->

//$content_url  = content_url();

$content_url = wp_upload_dir();
$content_url = $content_url['baseurl']; // Pfad zum Bilderordner angeben

$pathPlugin = plugins_url();
$pageURL = 'http';
if ( !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
$pageURL .= "://";

$path = $_SERVER['REQUEST_URI'];

$host = $_SERVER['HTTP_HOST'];

/*echo "<br/>";
echo "$path";
echo "<br/>";
echo "$host";
echo "<br/>";*/

$siteUrlOff = (strpos($path,'?')) ? $host.$path.'&' : $host.$path.'?';

// echo "<b>$siteUrlOff</b>";

$siteURL = $pageURL.$siteUrlOff;

//echo $siteURL;

// Wenn der zweite Teil des Explodes existiert, dann die URL wieder zur�ck machen

$siteURL = (strpos($siteURL,'&&')) ? str_replace("&&", "&","$siteURL") : $siteURL;

$explode = explode('&',$siteURL);

if(!$isAjaxCall){
    $siteURLdauswahl = ($explode[2]) ? $explode[0].'&'. $explode[1].'&'.'dauswahl' : $siteURL.'dauswahl';
}

//echo "$siteURLdauswahl";

// Configuration link urls -----> END



// Ermitteln der Options-Daten ---------------- ENDE






// Determine values of Options Table>>>>END

// Determine name fields names of upload Form

//$i=0;

/*echo "<br/>";
print_r($defineUpload);
echo "<br/>";

foreach ($defineUpload as $variant => $value) {

if ($value=='nf' AND $i==0) {$i++;continue;}
if ($i==1) {$name1uploadForm = $value;$i++;continue;}

if ($value=='nf' AND $i==2) {$i++;continue;}
if ($i==3) {$name2uploadForm = $value;$i++;continue;}

if ($value=='nf' AND $i==4) {$i++;continue;}
if ($i==5) {$name3uploadForm = $value;$i++;continue;}

}


// Checken ob Kommentar oder E-Mail Feld vorhanden sind

$keysDuKf = @array_keys($defineUpload,'kf',true);

if ($keysDuKf) {

$keysDuKf[0]++;
$keysDuKf1 = $keysDuKf[0];

echo "<br/>";
echo print_r($keysDuKf);
echo "<br/>";

}


$keysDuEf = @array_keys($defineUpload,'ef',true);

if ($keysDuEf) {

$keysDuEf[0]++;
$keysDuEf1 = $keysDuEf[0];

}


$kFtitle = ($keysDuKf) ? "$defineUpload[$keysDuKf1]": "";
$eFtitle = ($keysDuEf) ? "$defineUpload[$keysDuEf1]": "";

// Checken ob Kommentar oder E-Mail Feld vorhanden sind --- ENDE




echo "<br/>Name1: ";
echo $name1uploadForm;
echo "<br/>Name2: ";
echo $name2uploadForm;
echo "<br/>Name3: ";
echo $name3uploadForm;
echo "<br/>";*/





// Determine name fields names of upload Form --- END

// Determine if User should be informed or not

//	$disabledInform = ($Inform==0) ? 'disabled' : '';

// Determine if User should be informed or not END


/* creates a compressed zip file */
if(!function_exists('cg_action_create_zip')){
    function cg_action_create_zip($files = array(),$destination = '',$overwrite = false) {

        if(!class_exists('ZipArchive')){
            echo "The Zip extension for php is not installed on your server. Please contact your server provider in order to install you the Zip extension for php.";
            return false;
        }

        //if the zip file already exists and overwrite is false, return false
        if(file_exists($destination) && !$overwrite) { return false; }
        //vars
        $valid_files = array();
        //if files were passed in...
        if(is_array($files)) {
            //cycle through each file
            foreach($files as $file) {
                //make sure the file exists
                if(file_exists($file)) {
                    $valid_files[] = $file;
                }
            }
        }



        //if we have good files...
        if(count($valid_files)) {
            //create the archive
            $zip = new ZipArchive();
            if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
                return false;
            }
            //add the files
            foreach($valid_files as $file) {

                if (file_exists($file) && is_file($file)){
                    $zip->addFile($file, $file);
                }
            }
            //debug
            //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
            //close the zip -- done!
            $zip->close();

            //check to make sure the file exists
            return file_exists($destination);
        }
        else
        {
            return false;
        }
    }

}


// Maximal m�glich eingestellter Upload wird ermittelt
$upload_max_filesize = contest_gal1ery_return_mega_byte(ini_get('upload_max_filesize'));
$post_max_size = contest_gal1ery_return_mega_byte(ini_get('post_max_size'));
$memory_limit = (int)(ini_get('memory_limit'));
$max_input_vars = (int)(ini_get('max_input_vars'));

$cgProVersion = contest_gal1ery_key_check();
if(!$cgProVersion){
    $cgProFalse = 'cg-pro-false';
}else{
    $cgProFalse = '';
}

?>