<?php

$cg_copy_start = $_POST['cg_copy_start'];
$cg_processed_images = $cg_copy_start + 100;

// otherwise is already defined
if(!empty($_POST['option_id_next_gallery'])){
    $nextIDgallery = absint($_POST['option_id_next_gallery']);
}

$galleryUpload = $uploadFolder['basedir'] . '/contest-gallery/gallery-id-' . $nextIDgallery . '';
$galleryJsonFolder = $uploadFolder['basedir'] . '/contest-gallery/gallery-id-' . $nextIDgallery . '/json';
$galleryJsonImagesFolder = $uploadFolder['basedir'] . '/contest-gallery/gallery-id-' . $nextIDgallery . '/json/image-data';
$galleryJsonInfoDir = $uploadFolder['basedir'] . '/contest-gallery/gallery-id-' . $nextIDgallery . '/json/image-info';
$galleryJsonCommentsDir = $uploadFolder['basedir'] . '/contest-gallery/gallery-id-' . $nextIDgallery . '/json/image-comments';

$tablenameOptions = $wpdb->prefix . "contest_gal1ery_options";
$options = $wpdb->get_row( "SELECT * FROM $tablenameOptions WHERE id = '$nextIDgallery'" );

$imagesToProcess = $wpdb->get_var( $wpdb->prepare(
    "
						SELECT COUNT(*) AS NumberOfRows
						FROM $tablename 
						WHERE GalleryID = %d
					",
    $idToCopy
));

// var_dump('$idToCopy');
// var_dump($idToCopy);
//  var_dump($cg_copy_start);

if($cg_processed_images<$imagesToProcess){

    $processPercent = round($cg_processed_images/$imagesToProcess*100);
    echo "<h2>In progress $processPercent%...</h2>";
    echo "<p><strong>Do not cancel</strong></p>";

}else{

    if($cg_copy_start > 0 && $cg_processed_images >= $imagesToProcess){
        echo "<h2 class='cg_in_process'>In progress 99%...</h2>";
        echo "<p class='cg_in_process'><strong>Do not cancel</strong></p>";
    }else{
        echo "<h2 class='cg_in_process'>In progress ...</h2>";
        echo "<p class='cg_in_process'><strong>Do not cancel</strong></p>";
    }

}

// Important, order by ID asc!!!! last pictures first, then ids gets descending!
$galleryToCopy = $wpdb->get_results($wpdb->prepare("SELECT * FROM $tablename WHERE GalleryID = %d ORDER BY id ASC LIMIT %d, 100",[$idToCopy,$cg_copy_start]));
// var_dump($galleryToCopy);
//die;

$collectForPostTitle = '';
$WpUploadsArray = [];
foreach ($galleryToCopy as $rowObject){
    $WpUpload = $rowObject->WpUpload;
    if($collectForPostTitle == ''){
        $collectForPostTitle .= "ID = $WpUpload";
    }else{
        $collectForPostTitle .= " OR ID = $WpUpload";
    }
}


$post_titles_array = [];
$WpPostTitles = $wpdb->get_results( "SELECT DISTINCT ID, post_title FROM $table_posts WHERE ($collectForPostTitle)");
foreach ($WpPostTitles as $WpPostTitle){
    $post_titles_array[$WpPostTitle->ID] = $WpPostTitle->post_title;
}

$IsForWpPageTitleInputId = $wpdb->get_var("SELECT id FROM $tablename_form_input WHERE GalleryID = '$idToCopy' AND IsForWpPageTitle=1");

// get $collectInputIdsArray
$fp = fopen($galleryUpload . '/json/' . $nextIDgallery . '-collect-cat-ids-array.json', 'r');
$collectCatIdsArray =json_decode(fread($fp,filesize($galleryUpload . '/json/' . $nextIDgallery . '-collect-cat-ids-array.json')),true);
fclose($fp);

if($cgVersion<7 && !empty($_POST['copy_v7'])){
    // gallerie bilder in offizielle wordpress library platzieren
    $galleryToCopy = cg_copy_pre7_gallery_images($galleryToCopy);
}

$valueCollect = array();
$collectImageIdsArray = array();
$collectActiveImageIdsArray = array();
$imageRatingArray = array();
$imagesDataArray = array();

$oldIdsToCopyStringCollect = '';
$newIdsToCopyStringCollect = '';

$Version = cg_get_version_for_scripts();

$tableNameForColumns = $wpdb->prefix . 'contest_gal1ery';
$columns = $wpdb->get_results( "SHOW COLUMNS FROM $tableNameForColumns" );
foreach($galleryToCopy as $key => $rowObject){

    $imageRatingArray = array();

    $WpUpload = $rowObject->WpUpload;
    $Active = $rowObject->Active;
    $lastImageIdOld = $rowObject->id;

    if(empty($oldIdsToCopyStringCollect)){
        $oldIdsToCopyStringCollect = "$tablename_entries.pid = $lastImageIdOld";
    }else{
        $oldIdsToCopyStringCollect .= " OR $tablename_entries.pid = $lastImageIdOld";
    }

    $prevId = 0;
    $WpUpload = 0;
    foreach($rowObject as $key1 => $value1){

        if ($key1 == 'id') {
            $prevId = $value1;
            $value1 = '';
        }
        if ($key1 == 'rowid') {
            $value1 = 0;
        }
        if ($key1 == 'GalleryID') {
            $value1 = $nextIDgallery;
        }
        if ($key1 == 'WpUpload') {
            $WpUpload = $value1;
        }

        // if only options and images then set to 0
        if($cgCopyType=='cg_copy_type_options_and_images'){
            if ($key1 == 'CountC') {
                $value1 = 0;
            }
            if ($key1 == 'CountR') {
                $value1 = 0;
            }
            if ($key1 == 'CountS') {
                $value1 = 0;
            }
            if ($key1 == 'Rating') {
                $value1 = 0;
            }
        }


        if ($key1 == 'Category') {
            if(empty($collectCatIdsArray[$value1])){
                $value1 = 0;
            }else{
                $value1 = $collectCatIdsArray[$value1];
            }
        }

        if ($key1 == 'Version') {
            if(empty($value1)){// put in the current version then
                $value1 = $Version;
            }
        }

        $valueCollect[$key1] = $value1;

    }

    /*$wpdb->insert(
        $tablename,
        $valueCollect,
        array(
            '%s', '%s', '%s',
            '%s', '%s',
            '%s', '%s', '%s', '%s',
            '%s', '%s', '%s',
            '%s', '%s', '%s', '%s', '%s', '%s',
            '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
            '%s', '%s', '%s', '%s', '%s', // CountR1-5 added since modern five star rating
            '%s', // Added Version
            '%s', // Added CountCtoReview
            '%s', '%s', // Added CheckSet and CookieId
            '%s', '%s', '%s',// Added Winner, IsProfileImage, MultipleFiles
            '%s', '%s', '%s', '%s', '%s',// CountR6-10 added for multiple stars rating. Everything has to be string %s so copying work because of that!
            '%s', '%s', '%s', '%s', '%s', // addCountR6-10 added for multiple stars rating. Everything has to be string %s so copying work because of that!
            '%s' // Added PositionNumber
        )
    );*/

    //$nextId = cg_copy_table_row('contest_gal1ery',$rowObject->id,$nextIDgallery,$cgCopyType,0,$columns);

    $nextId = cg_copy_table_row('contest_gal1ery',$rowObject->id,['contest_gal1ery' => $valueCollect],$cgCopyType,$columns);

    // $collectImageIdsArray will be collected in next step at the bottom
    $nextId = $wpdb->insert_id;

    $WpPageTitle = '';
    if(!empty($IsForWpPageTitleInputId)){
        $ShortText = $wpdb->get_var("SELECT Short_Text FROM $tablename_entries WHERE pid = '$prevId' AND f_input_id=$IsForWpPageTitleInputId");
        if(!empty($ShortText)){
            $WpPageTitle = $ShortText;
        }
    }

    if(!empty($WpPageTitle)){
        $post_title = $WpPageTitle;
    }else{
        $post_title = '';
        if(isset($post_titles_array[$WpUpload])){
        $post_title = $post_titles_array[$WpUpload];
    }
    }

    // cg_gallery shortcode
    $array = [
        'post_title'=> $post_title,
        'post_type'=>'contest-gallery',
        'post_content'=>"<!-- wp:shortcode -->"."\r\n".
            "<!--This is a comment: cg_galley... shortcode with entry id is required to display Contest Gallery entry on a Contest Gallery Custom Post Type entry page. You can place your own content before and after this shortcode, whatever you like. You can place cg_gallery... shortcode with entry_id also on any other of your pages. -->"."\r\n".
            "[cg_gallery id=\"$nextIDgallery\" entry_id=\"$nextId\"]"."\r\n".
            "<!-- /wp:shortcode -->",
        'post_mime_type'=>'contest-gallery-plugin-page',
        'post_status'=>'publish',
        'post_parent'=>$options->WpPageParent
    ];

    $WpPage = wp_insert_post($array);

    // cg_gallery_user shortcode
    $array = [
        'post_title'=> $post_title,
        'post_type'=>'contest-gallery',
        'post_content'=>"<!-- wp:shortcode -->"."\r\n".
            "<!--This is a comment: cg_galley... shortcode with entry id is required to display Contest Gallery entry on a Contest Gallery Custom Post Type entry page. You can place your own content before and after this shortcode, whatever you like. You can place cg_gallery... shortcode with entry_id also on any other of your pages. -->"."\r\n".
            "[cg_gallery_user id=\"$nextIDgallery\" entry_id=\"$nextId\"]"."\r\n".
            "<!-- /wp:shortcode -->",
        'post_mime_type'=>'contest-gallery-plugin-page',
        'post_status'=>'publish',
        'post_parent'=>$options->WpPageParentUser
    ];

    $WpPageUser = wp_insert_post($array);

    // cg_gallery_no_voting shortcode
    $array = [
        'post_title'=> $post_title,
        'post_type'=>'contest-gallery',
        'post_content'=>"<!-- wp:shortcode -->"."\r\n".
            "<!--This is a comment: cg_galley... shortcode with entry id is required to display Contest Gallery entry on a Contest Gallery Custom Post Type entry page. You can place your own content before and after this shortcode, whatever you like. You can place cg_gallery... shortcode with entry_id also on any other of your pages. -->"."\r\n".
            "[cg_gallery_no_voting id=\"$nextIDgallery\" entry_id=\"$nextId\"]"."\r\n".
            "<!-- /wp:shortcode -->",
        'post_mime_type'=>'contest-gallery-plugin-page',
        'post_status'=>'publish',
        'post_parent'=>$options->WpPageParentNoVoting
    ];

    $WpPageNoVoting = wp_insert_post($array);

    // cg_gallery_winner shortcode
    $array = [
        'post_title'=> $post_title,
        'post_type'=>'contest-gallery',
        'post_content'=>"<!-- wp:shortcode -->"."\r\n".
            "<!--This is a comment: cg_galley... shortcode with entry id is required to display Contest Gallery entry on a Contest Gallery Custom Post Type entry page. You can place your own content before and after this shortcode, whatever you like. You can place cg_gallery... shortcode with entry_id also on any other of your pages. -->"."\r\n".
            "[cg_gallery_winner id=\"$nextIDgallery\" entry_id=\"$nextId\"]"."\r\n".
            "<!-- /wp:shortcode -->",
        'post_mime_type'=>'contest-gallery-plugin-page',
        'post_status'=>'publish',
        'post_parent'=>$options->WpPageParentWinner
    ];

    $WpPageWinner = wp_insert_post($array);
    $wpdb->update(
        "$tablename",
        array('WpPage' => $WpPage,'WpPageUser' => $WpPageUser,'WpPageNoVoting' => $WpPageNoVoting,'WpPageWinner' => $WpPageWinner),
        array('id' => $nextId),
        array('%d','%d','%d','%d'),
        array('%d')
    );

    if($Active==1){

        if(!empty($WpUpload)){
            $rowObject = $wpdb->get_row($wpdb->prepare("SELECT DISTINCT $table_posts.*, $tablename.* FROM $table_posts, $tablename WHERE 
                                              ($tablename.GalleryID=%d AND $tablename.Active='1' and $table_posts.ID = $tablename.WpUpload)
                                          GROUP BY $tablename.id ORDER BY $tablename.id DESC LIMIT 0, 1",[$nextIDgallery]));
        }else{
            $rowObject = $wpdb->get_row($wpdb->prepare("SELECT DISTINCT $table_posts.*, $tablename.* FROM $table_posts, $tablename WHERE 
                                              ($tablename.GalleryID=%d AND $tablename.Active='1' AND $tablename.WpUpload = 0) 
                                          GROUP BY $tablename.id ORDER BY $tablename.id DESC LIMIT 0, 1",[$nextIDgallery]));
        }

        $imagesDataArray = cg_create_json_files_when_activating($nextIDgallery,$rowObject,$thumbSizesWp,$uploadFolder,$imagesDataArray);

        $collectImageIdsArray[$lastImageIdOld] = $rowObject->id;
        $collectActiveImageIdsArray[$lastImageIdOld] = $rowObject->id;

    }else{

        $lastImageId = $wpdb->get_var("SELECT id FROM $tablename ORDER BY id DESC LIMIT 0, 1");
        $collectImageIdsArray[$lastImageIdOld] = $lastImageId;

    }

    $valueCollect = array();

}

//cg_set_data_in_images_files_with_all_data($nextIDgallery,$imagesDataArray);

// since 17.0.0 not used anymore, no share button anymore
//cg_create_fb_sites($idToCopy,$nextIDgallery);// IMAGE ID Will be considered in this case. Thats why it is done so!

if($cgVersion<10){

    $backToGalleryFile = $uploadFolder["basedir"]."/contest-gallery/gallery-id-$nextIDgallery/backtogalleryurl.js";
    $FbLikeGoToGalleryLink = 'backToGalleryUrl="";';
    $fp = fopen($backToGalleryFile, 'w');
    fwrite($fp, $FbLikeGoToGalleryLink);
    fclose($fp);

}else{

    $backToGalleryFile = $uploadFolder["basedir"]."/contest-gallery/gallery-id-$nextIDgallery/backtogalleryurl.js";
    $FbLikeGoToGalleryLink = 'backToGalleryUrl="'.$FbLikeGoToGalleryLink.'";';
    $fp = fopen($backToGalleryFile, 'w');
    fwrite($fp, $FbLikeGoToGalleryLink);
    fclose($fp);

}


// create user entries

// get $collectInputIdsArray
$fp = fopen($galleryUpload . '/json/' . $nextIDgallery . '-collect-input-ids-array.json', 'r');
$collectInputIdsArray =json_decode(fread($fp,filesize($galleryUpload . '/json/' . $nextIDgallery . '-collect-input-ids-array.json')),true);
fclose($fp);

// check which fileds are allowed for json save because allowed gallery or single view
$uploadFormFields = $wpdb->get_results($wpdb->prepare("SELECT * FROM $tablename_form_input WHERE GalleryID = %d",[$nextIDgallery]));

$Field1IdGalleryView = $wpdb->get_var($wpdb->prepare("SELECT Field1IdGalleryView FROM $tablename_options_visual WHERE GalleryID = %d",[$nextIDgallery]));

$fieldsForFrontendArray = array();
$inputTitles = array();

foreach ($uploadFormFields as $field) {
    $Field_Content = unserialize($field->Field_Content);

    $inputTitles[$field->id] = $Field_Content['titel'];

    if ($field->id == $Field1IdGalleryView or $field->Show_Slider == 1) {
        $fieldsForFrontendArray[] = $field->id;
    }
}

if(!empty($oldIdsToCopyStringCollect)){
    $oldIdsToCopyStringCollect = "AND ($oldIdsToCopyStringCollect)";
}

$galleryToCopy = $wpdb->get_results($wpdb->prepare("SELECT * FROM $tablename_entries WHERE GalleryID = %d $oldIdsToCopyStringCollect ORDER BY pid DESC",[$idToCopy]));

$valueCollect = array();

$pidBefore = '';

/*echo "<pre>";
print_r($galleryToCopy);
echo "</pre>";*/

/*
echo "<pre>";
print_r($collectImageIdsArray);
echo "</pre>";*/

if(!empty($galleryToCopy)){

    $tableNameForColumns = $wpdb->prefix . 'contest_gal1ery_entries';
    $columns = $wpdb->get_results( "SHOW COLUMNS FROM $tableNameForColumns" );

    foreach ($galleryToCopy as $key => $rowObject) {

        if(!empty($rowObject->InputDate) AND $rowObject->InputDate!='0000-00-00 00:00:00'){
            // simply continue processing then
        } else if ($rowObject->Short_Text == '' && $rowObject->Long_Text == '') {// to reduce amount of copy
            continue;
        }

        foreach ($rowObject as $key1 => $value1) {

            if ($key1 == 'id') {
                $value1 = '';
            }
            if ($key1 == 'GalleryID') {
                $value1 = $nextIDgallery;
            }
            if ($key1 == 'pid') {
                $value1 = $collectImageIdsArray[$value1];
            }
            if ($key1 == 'f_input_id') {
                $lastInputIdOld = $value1;
                $value1 = $collectInputIdsArray[$lastInputIdOld];
                $fInputId = $value1;
            }

            $valueCollect[$key1] = $value1;

        }

        /*$wpdb->insert(
            $tablename_entries,
            $valueCollect,
            array(
                '%s', '%d', '%d', '%d',
                '%s', '%d', '%s', '%s', '%d', '%d', '%s', '%d'// InputDate was last
            )
        ); // the last two are*/

     /*   echo "<pre>";
        print_r($collectInputIdsArray);
        echo "</pre>";

        var_dump($rowObject->pid);*/

        $nextPid = (!empty($collectImageIdsArray[$rowObject->pid])) ? $collectImageIdsArray[$rowObject->pid] : 0;
       // var_dump('$nextPid');
        //var_dump($nextPid);
        //$nextId = cg_copy_table_row('contest_gal1ery_entries',$rowObject->id,$nextIDgallery,$cgCopyType,$nextPid,$columns,$collectInputIdsArray[$lastInputIdOld]);

        $nextId = cg_copy_table_row('contest_gal1ery_entries',$rowObject->id,['contest_gal1ery_entries' => $valueCollect],'',$columns);

        if ($rowObject->pid != $pidBefore) {

            if ($pidBefore == '') {
                $pidBefore = $rowObject->pid;
                continue;
            }

        }

        $pidBefore = $rowObject->pid;

        $valueCollect = array();

    }

}



// insert entries json

foreach ($collectActiveImageIdsArray as $oldImageId => $newImageId){

    if(empty($newIdsToCopyStringCollect)){
        $newIdsToCopyStringCollect = "$tablename_entries.pid = $newImageId";
    }else{
        $newIdsToCopyStringCollect .= " OR $tablename_entries.pid = $newImageId";
    }

}
/*echo "<br>";
var_dump ($newIdsToCopyStringCollect);
echo "<br>";

die;*/
//do_action('cg_json_upload_form_info_data_files',$nextIDgallery,$newIdsToCopyStringCollect);
cg_json_upload_form_info_data_files_new($nextIDgallery);

if($cgCopyType=='cg_copy_type_all'){

    // copy rating here
    cg_copy_rating($cg_copy_start,$idToCopy,$nextIDgallery,$collectImageIdsArray);

    // copy comments here
    cg_copy_comments($cg_copy_start,$idToCopy,$nextIDgallery,$collectImageIdsArray);
}

cg_actualize_all_images_data_sort_values_file($nextIDgallery,true,true);

// forward

if($cg_processed_images<$imagesToProcess){

    //   ?page=".cg_get_version()."/index.php&option_id=137
    //    &edit_gallery=true&copy=true
    $cg_copy_start = $cg_processed_images;

    echo "<input type='hidden' id='cgProcessedImages' value='$cg_copy_start' />";
    echo "<input type='hidden' id='cgNextIdGallery' value='$nextIDgallery' />";


    die;


    //require("forward-url.php");

    //exit;
    //echo $Forward_URL;

}

?>