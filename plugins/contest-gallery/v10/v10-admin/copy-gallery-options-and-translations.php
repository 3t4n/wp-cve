<?php

$thumbnail_size_w = get_option("thumbnail_size_w");
$medium_size_w = get_option("medium_size_w");
$large_size_w = get_option("large_size_w");

$valueCollect = array();

$jsonOptions = array();
$jsonOptions['visual'] = array();
$jsonOptions['general'] = array();
$jsonOptions['input'] = array();
$jsonOptions['pro'] = array();

$isAllowGalleryScriptWasActivated = false;

foreach ($galleryToCopy as $key => $value) {

    if ($key == 'id') {
        $value = '';
    }
    if ($key == 'ShowAlways') {
        $value = 3;
    }
    $valueCollect[$key] = $value;
    $jsonOptions['general'][$key] = $value;

}

if (!empty($valueCollect['GalleryName'])) {
    $valueCollect['GalleryName'] = $valueCollect['GalleryName'] . ' - COPY';
}
if (!empty($valueCollect['FbLikeGoToGalleryLink'])) {
    $FbLikeGoToGalleryLink = $valueCollect['FbLikeGoToGalleryLink'];
}

//$galleryDBversion = cg_get_db_version();
//$valueCollect['Version'] = $galleryDBversion;
$VersionForScripts = cg_get_version_for_scripts();// was cg_get_db_version before version 21.0.0
$VersionDecimal = floatval(cg_get_db_version());
$valueCollect['Version'] = $VersionForScripts;// was cg_get_db_version before version 21.0.0
$valueCollect['VersionDecimal'] = $VersionDecimal;// was cg_get_db_version before version 21.0.0
$jsonOptions['general']['Version'] = $VersionForScripts;
$jsonOptions['general']['VersionDecimal'] = $VersionDecimal;

if(!empty($valueCollect['AllowGalleryScript'])){
    $isAllowGalleryScriptWasActivated = true;
}

include ('json-values-21-version-release.php');

// V10 adaption
if ($cgVersion < 10) {

    $valueCollect['AllowGalleryScript'] = 0;// since 15.05 version always 0. Slideout will be not used anymore
    $valueCollect['FullSize'] = 0;
    $valueCollect['FullSizeGallery'] = 1;
    $valueCollect['FullSizeImageOutGallery'] = 0;
    $valueCollect['OnlyGalleryView'] = 0;
    $valueCollect['RandomSortButton'] = 1;
    $valueCollect['FbLikeGoToGalleryLink'] = '';
    $valueCollect['CheckIp'] = ($valueCollect['CheckLogin'] == 1) ? 0 : 1;
    $valueCollect['CheckCookie'] = 0;
    $valueCollect['CheckCookieAlertMessage'] = 'Please allow cookies to vote';
    $valueCollect['SliderLook'] = 1;
    $valueCollect['ContestStart'] = 1;// old logic, take care $valueCollect amount at insert bottom
    $valueCollect['ContestStartTime'] = 1;// old logic, take care $valueCollect amount at insert bottom

    $jsonOptions['general']['AllowGalleryScript'] = $valueCollect['AllowGalleryScript'];
    $jsonOptions['general']['FullSize'] = $valueCollect['FullSize'];
    $jsonOptions['general']['FullSizeGallery'] = $valueCollect['FullSizeGallery'];
    $jsonOptions['general']['FullSizeImageOutGallery'] = $valueCollect['FullSizeImageOutGallery'];
    $jsonOptions['general']['OnlyGalleryView'] = $valueCollect['OnlyGalleryView'];
    $jsonOptions['general']['RandomSortButton'] = $valueCollect['RandomSortButton'];
    $jsonOptions['general']['FbLikeGoToGalleryLink'] = $valueCollect['FbLikeGoToGalleryLink'];
    $jsonOptions['general']['CheckIp'] = $valueCollect['CheckIp'];
    $jsonOptions['general']['CheckCookie'] = $valueCollect['CheckCookie'];
    $jsonOptions['general']['CheckCookieAlertMessage'] = $valueCollect['CheckCookieAlertMessage'];
    $jsonOptions['general']['SliderLook'] = 1;
    $jsonOptions['general']['ContestStart'] = 0;
    $jsonOptions['general']['ContestStartTime'] = '';
    $jsonOptions['general']['ContestEnd'] = 0;
    $jsonOptions['general']['ContestEndTime'] = '';
    $jsonOptions['general']['ShowAlways'] = 3;

    $jsonOptions['general']['MaxResICOon'] = 1;
    $jsonOptions['general']['MaxResICOwidth'] = 2000;
    $jsonOptions['general']['MaxResICOheight'] = 2000;

    $jsonOptions['general']['MinResJPGon'] = 0;
    $jsonOptions['general']['MinResJPGwidth'] = 800;
    $jsonOptions['general']['MinResJPGheight'] = 800;

    $jsonOptions['general']['MinResPNGon'] = 0;
    $jsonOptions['general']['MinResPNGwidth'] = 800;
    $jsonOptions['general']['MinResPNGheight'] = 800;

    $jsonOptions['general']['MinResGIFon'] = 0;
    $jsonOptions['general']['MinResGIFwidth'] = 800;
    $jsonOptions['general']['MinResGIFheight'] = 800;

    $jsonOptions['general']['ActivatePostMaxMBfile'] = 1;
    $jsonOptions['general']['PostMaxMBfile'] = 2000;

}

// since version 17.0.0, fb like complete reset
$valueCollect['FbLike'] = 0;
$valueCollect['FbLikeGallery'] = 0;
$valueCollect['FbLikeGoToGalleryLink'] = '';
$jsonOptions['general']['FbLike'] = 0;
$jsonOptions['general']['FbLikeGallery'] = 0;
$jsonOptions['general']['FbLikeGoToGalleryLink'] = '';

// falls was schief gelaufen ist
if((empty($valueCollect['HeightLook']) && empty($valueCollect['ThumbLook']) && empty($valueCollect['RowLook']) && empty($valueCollect['SliderLook']))
    OR $cgVersion < 10
){
    $valueCollect['SliderLook'] = 1;
    $valueCollect['HeightLook'] = 1;
    $valueCollect['ThumbLook'] = 1;
    $valueCollect['RowLook'] = 1;

    $jsonOptions['general']['HeightLook'] = $valueCollect['HeightLook'];
    $jsonOptions['general']['ThumbLook'] = $valueCollect['ThumbLook'];
    $jsonOptions['general']['RowLook'] = $valueCollect['RowLook'];
    $jsonOptions['general']['SliderLook'] = $valueCollect['SliderLook'];

    $valueCollect['HeightLookOrder'] = 1;
    $valueCollect['ThumbLookOrder'] = 2;
    $valueCollect['RowLookOrder'] = 3;
    $valueCollect['SliderLookOrder'] = 4;

    $jsonOptions['general']['HeightLookOrder'] = $valueCollect['HeightLookOrder'];
    $jsonOptions['general']['ThumbLookOrder'] = $valueCollect['ThumbLookOrder'];
    $jsonOptions['general']['RowLookOrder'] = $valueCollect['RowLookOrder'];
    $jsonOptions['general']['SliderLookOrder'] = $valueCollect['SliderLookOrder'];

}

/*$wpdb->insert(
    $tablenameOptions,
    $valueCollect,
    array(
        '%s', '%s', '%d', '%d', '%d', '%d',
        '%d', '%d', '%d', '%d', '%d', '%d',
        '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d',
        '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d',
        '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d',
        '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d',
        '%d', '%d', '%d', '%d', '%d',
        '%d', '%d', '%d',
        '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d',
        '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%s', '%d',
        '%d', '%d', '%s', '%d', '%d', '%s', '%d', '%s',
        '%d', '%d', '%d',
        '%d', '%d', '%f',
        '%d', '%d', '%d', '%d'
    )// WpPageParentWinner was last one
);*/

$nextIDgallery = cg_copy_table_row('contest_gal1ery_options',$idToCopy,['contest_gal1ery_options' => $valueCollect]);

// cg_gallery shortcode
$array = [
    'post_title'=>'Contest Gallery ID '.$nextIDgallery,
    'post_type'=>'contest-gallery',
    'post_content'=>"<!-- wp:shortcode -->"."\r\n".
        "<!--This is a comment: cg_galley... shortcode is required to display Contest Gallery on a Contest Gallery Custom Post Type page. You can place your own content before and after this shortcode, whatever you like. You can place cg_gallery... shortcode also on any other of your pages.-->"."\r\n".
        "[cg_gallery id=\"$nextIDgallery\"]"."\r\n".
        "<!-- /wp:shortcode -->",
    'post_mime_type'=>'contest-gallery-plugin-page',
    'post_status'=>'publish',
];

$WpPageParent = wp_insert_post($array);
$jsonOptions['general']['WpPageParent'] = $WpPageParent;


// cg_gallery_user shortcode
$array = [
    'post_title'=>'Contest Gallery ID '.$nextIDgallery.' user',
    'post_type'=>'contest-gallery',
    'post_content'=>"<!-- wp:shortcode -->"."\r\n".
        "<!--This is a comment: cg_galley... shortcode is required to display Contest Gallery on a Contest Gallery Custom Post Type page. You can place your own content before and after this shortcode, whatever you like. You can place cg_gallery... shortcode also on any other of your pages.-->"."\r\n".
        "[cg_gallery_user id=\"$nextIDgallery\"]"."\r\n".
        "<!-- /wp:shortcode -->",
    'post_mime_type'=>'contest-gallery-plugin-page',
    'post_status'=>'publish',
];
$WpPageParentUser = wp_insert_post($array);
$jsonOptions['general']['WpPageParentUser'] = $WpPageParentUser ;


// cg_gallery_no_voting shortcode
$array = [
    'post_title'=>'Contest Gallery ID '.$nextIDgallery.' no voting',
    'post_type'=>'contest-gallery',
    'post_content'=>"<!-- wp:shortcode -->"."\r\n".
        "<!--This is a comment: cg_galley... shortcode is required to display Contest Gallery on a Contest Gallery Custom Post Type page. You can place your own content before and after this shortcode, whatever you like. You can place cg_gallery... shortcode also on any other of your pages.-->"."\r\n".
        "[cg_gallery_no_voting id=\"$nextIDgallery\"]"."\r\n".
        "<!-- /wp:shortcode -->",
    'post_mime_type'=>'contest-gallery-plugin-page',
    'post_status'=>'publish',
];
$WpPageParentNoVoting = wp_insert_post($array);
$jsonOptions['general']['WpPageParentNoVoting'] = $WpPageParentNoVoting ;


// cg_gallery_winner shortcode
$array = [
    'post_title'=>'Contest Gallery ID '.$nextIDgallery.' winner',
    'post_type'=>'contest-gallery',
    'post_content'=>"<!-- wp:shortcode -->"."\r\n".
        "<!--This is a comment: cg_galley... shortcode is required to display Contest Gallery on a Contest Gallery Custom Post Type page. You can place your own content before and after this shortcode, whatever you like. You can place cg_gallery... shortcode also on any other of your pages.-->"."\r\n".
        "[cg_gallery_winner id=\"$nextIDgallery\"]"."\r\n".
        "<!-- /wp:shortcode -->",
    'post_mime_type'=>'contest-gallery-plugin-page',
    'post_status'=>'publish',
];
$WpPageParentWinner = wp_insert_post($array);
$jsonOptions['general']['WpPageParentWinner'] = $WpPageParentWinner ;

$wpdb->update(
    "$tablenameOptions",
    array('WpPageParent' => $WpPageParent,'WpPageParentUser' => $WpPageParentUser,'WpPageParentNoVoting' => $WpPageParentNoVoting,'WpPageParentWinner' => $WpPageParentWinner),
    array('id' => $nextIDgallery),
    array('%d','%d','%d','%d'),
    array('%d')
);

$tag = get_term_by('slug', ' contest-gallery-plugin-tag','post_tag');
if(empty($tag)){
    $tag = cg_create_contest_gallery_plugin_tag();
    $term_id = $tag['term_id'];
}else{
    $term_id = $tag->term_id;
}

$wpdb->query( $wpdb->prepare(
    "
                    INSERT INTO $tablename_wp_pages
                        ( id,WpPage
                         )
                        VALUES ( %s,%d
                        )
                    ",
    '',$WpPageParent
) );

$wpdb->query( $wpdb->prepare(
    "
                    INSERT INTO $tablename_wp_pages
                        ( id,WpPage
                         )
                        VALUES ( %s,%d
                        )
                    ",
    '',$WpPageParentUser
) );

$wpdb->query( $wpdb->prepare(
    "
                    INSERT INTO $tablename_wp_pages
                        ( id,WpPage
                         )
                        VALUES ( %s,%d
                        )
                    ",
    '',$WpPageParentNoVoting
) );

$wpdb->query( $wpdb->prepare(
    "
                    INSERT INTO $tablename_wp_pages
                        ( id,WpPage
                         )
                        VALUES ( %s,%d
                        )
                    ",
    '',$WpPageParentWinner
) );


// Erschaffen eines Galerieordners
$galleryUpload = $uploadFolder['basedir'] . '/contest-gallery/gallery-id-' . $nextIDgallery . '';
$galleryJsonFolder = $uploadFolder['basedir'] . '/contest-gallery/gallery-id-' . $nextIDgallery . '/json';
$galleryJsonImagesFolder = $uploadFolder['basedir'] . '/contest-gallery/gallery-id-' . $nextIDgallery . '/json/image-data';
$galleryJsonInfoDir = $uploadFolder['basedir'] . '/contest-gallery/gallery-id-' . $nextIDgallery . '/json/image-info';
$galleryJsonCommentsDir = $uploadFolder['basedir'] . '/contest-gallery/gallery-id-' . $nextIDgallery . '/json/image-comments';

if (!is_dir($galleryUpload)) {
    mkdir($galleryUpload, 0755, true);
}

if (!is_dir($galleryJsonFolder)) {
    mkdir($galleryJsonFolder, 0755, true);
}

if (!is_dir($galleryJsonImagesFolder)) {
    mkdir($galleryJsonImagesFolder, 0755);
}

if (!is_dir($galleryJsonInfoDir)) {
    mkdir($galleryJsonInfoDir, 0755);
}

if (!is_dir($galleryJsonCommentsDir)) {
    mkdir($galleryJsonCommentsDir, 0755);
}

$galleryJsonFolderReadMeFile = $galleryJsonFolder . '/do not remove json or txt files manually.txt';

$fp = fopen($galleryJsonFolderReadMeFile, 'w');
fwrite($fp, 'Removing json or txt files manually will break functionality of your gallery');
fclose($fp);

$fp = fopen($galleryUpload . '/json/' . $nextIDgallery . '-categories.json', 'w');
fwrite($fp, json_encode(array()));
fclose($fp);

$fp = fopen($galleryUpload . '/json/' . $nextIDgallery . '-form-upload.json', 'w');
fwrite($fp, json_encode(array()));
fclose($fp);

$fp = fopen($galleryUpload . '/json/' . $nextIDgallery . '-images.json', 'w');
fwrite($fp, json_encode(array()));
fclose($fp);

$fp = fopen($galleryUpload . '/json/' . $nextIDgallery . '-single-view-order.json', 'w');
fwrite($fp, json_encode(array()));
fclose($fp);


// Gleich auf die Version ab der mit der neuen Art des Hinzufügens von Images updaten
$wpdb->update(
    "$tablenameOptions",
    array('Version' => $valueCollect['Version']),
    array('id' => $nextIDgallery),
    array('%s'),
    array('%d')
);

// Create f_input

$uploadInputsToCopy = $wpdb->get_results("SELECT * FROM $tablename_form_input WHERE GalleryID = '$idToCopy' ");

$valueCollect = array();

$collectInputIdsArray = array();

$uploadInputsToCopyOldNewIds = array();

$SubTitleFieldId = 0;
$ThirdTitleFieldId = 0;

$tableNameForColumns = $wpdb->prefix . 'contest_gal1ery_f_input';
$columns = $wpdb->get_results( "SHOW COLUMNS FROM $tableNameForColumns" );

foreach ($uploadInputsToCopy as $key => $rowObject) {

    foreach ($rowObject as $key1 => $value1) {

        if ($key1 == 'id') {
            $lastInputIdOld = $value1;
            $value1 = '';
        }
        if ($key1 == 'GalleryID') {
            $value1 = $nextIDgallery;
        }
        if ($key1 == 'SubTitle' && $value1==1) {
            $SubTitleFieldId = $rowObject->id;
        }
        if ($key1 == 'ThirdTitle' && $value1==1) {
            $ThirdTitleFieldId = $rowObject->id;
        }
        if ($key1 == 'Field_Content') {
            $fieldContent = unserialize($value1);
        }
        $valueCollect[$key1] = $value1;

    }

    /*$wpdb->insert(
        $tablename_form_input,
        $valueCollect,
        array(
            '%s', '%d', '%s',
            '%d', '%s', '%d', '%d', '%d', '%s', '%s', '%s', '%s',
            '%d', '%d'
        )// last one is WatermarkPosition, IsForWpPageTitle, IsForWpPageDescription
    );*/

    $nextInputId = cg_copy_table_row('contest_gal1ery_f_input',$rowObject->id,['contest_gal1ery_f_input' => $valueCollect],'',$columns);

    $uploadInputsToCopyOldNewIds[$rowObject->id] = $nextInputId;

    //$lastInputInfo = $wpdb->get_var("SELECT MAX(id) FROM $tablename_form_input");
    //$lastInputId = $lastInputInfo;
    $collectInputIdsArray[$lastInputIdOld] = $nextInputId;

    $valueCollect = array();

}

do_action('cg_json_upload_form', $nextIDgallery);

// Create Options Visual

$galleryToCopy = $wpdb->get_row("SELECT * FROM $tablename_options_visual WHERE GalleryID = '$idToCopy' ");

$valueCollect = array();

$Field1IdGalleryView = 0;
$Field2IdGalleryView = 0;
$Field3IdGalleryView = 0;

foreach ($galleryToCopy as $key => $value) {

    if ($key == 'id') {
        $value = '';
    }
    if ($key == 'GalleryID') {
        $value = $nextIDgallery;
    }
    if ($key == 'Field1IdGalleryView') {
        if(!empty($collectInputIdsArray[$value])){
            $value = $collectInputIdsArray[$value];
            $Field1IdGalleryView = $value;
        }else{
            $value = 0;
        }
    }
    if ($key == 'Field2IdGalleryView') {
        if(!empty($collectInputIdsArray[$value])){
            $value = $collectInputIdsArray[$value];
            $Field2IdGalleryView = $value;
        }else{
            $value = 0;
        }
    }
    if ($key == 'Field3IdGalleryView') {
        if(!empty($collectInputIdsArray[$value])){
            $value = $collectInputIdsArray[$value];
            $Field3IdGalleryView = $value;
        }else{
            $value = 0;
        }
    }
    if ($key == 'ThumbViewBorderRadius') {
        $value = 0;
    }
    if ($key == 'ThumbViewBorderOpacity') {
        $value = 1;
    }
    if ($key == 'HeightViewBorderRadius') {
        $value = 0;
    }
    if ($key == 'HeightViewBorderOpacity') {
        $value = 1;
    }
    if ($key == 'RowViewBorderRadius') {
        $value = 0;
    }
    if ($key == 'RowViewBorderOpacity') {
        $value = 1;
    }
    if (!empty($isAllowGalleryScriptWasActivated) && $key == 'BlogLookFullWindow') {// since 15.05 version always 0. Slideout will be not used anymore.
        $value = 1;
    }
    $valueCollect[$key] = $value;
    $jsonOptions['visual'][$key] = $value;
}

// v10 adaption
if ($cgVersion < 10) {

    $valueCollect['OriginalSourceLinkInSlider'] = 1;
    $valueCollect['FeControlsStyle'] = 'white';
    $valueCollect['AllowSortOptions'] = 'custom,date-desc,date-asc,rate-desc,rate-asc,rate-average-desc,rate-average-asc,rate-sum-desc,rate-sum-asc,comment-desc,comment-asc,random';
    $valueCollect['GalleryStyle'] = 'center-white';
    $valueCollect['BlogLook'] = 1;
    $valueCollect['BlogLookOrder'] = 5;
    $valueCollect['BlogLookFullWindow'] = 0;// since 15.05 version always 0. Slideout will be not used anymore.
    $valueCollect['ImageViewFullWindow'] = 1;
    $valueCollect['ImageViewFullScreen'] = 1;
    $valueCollect['SliderThumbNav'] = 1;
    $valueCollect['BorderRadius'] = 1;
    $valueCollect['CopyImageLink'] = 1;
    $valueCollect['CommentsDateFormat'] = 'YYYY-MM-DD';
    $valueCollect['FeControlsStyleUpload'] = 'white';
    $valueCollect['FeControlsStyleRegistry'] = 'white';
    $valueCollect['FeControlsStyleLogin'] = 'white';
    $valueCollect['BorderRadiusUpload'] = 1;
    $valueCollect['BorderRadiusRegistry'] = 1;
    $valueCollect['BorderRadiusLogin'] = 1;
    $valueCollect['ThankVote'] = 1;
    $valueCollect['GeneralID'] = 0;
    $valueCollect['CopyOriginalFileLink'] = 1;
    $valueCollect['ForwardOriginalFile'] = 1;

    $jsonOptions['visual']['OriginalSourceLinkInSlider'] = $valueCollect['OriginalSourceLinkInSlider'];
    $jsonOptions['visual']['FeControlsStyle'] = $valueCollect['FeControlsStyle'];
    $jsonOptions['visual']['AllowSortOptions'] = $valueCollect['AllowSortOptions'];
    $jsonOptions['visual']['BlogLook'] = $valueCollect['BlogLook'];
    $jsonOptions['visual']['BlogLookOrder'] = $valueCollect['BlogLookOrder'];
    $jsonOptions['visual']['BlogLookFullWindow'] = $valueCollect['BlogLookFullWindow'];
    $jsonOptions['visual']['ImageViewFullWindow'] = $valueCollect['ImageViewFullWindow'];
    $jsonOptions['visual']['ImageViewFullScreen'] = $valueCollect['ImageViewFullScreen'];
    $jsonOptions['visual']['SliderThumbNav'] = $valueCollect['SliderThumbNav'];
    $jsonOptions['visual']['BorderRadius'] = $valueCollect['BorderRadius'];
    $jsonOptions['visual']['CopyImageLink'] = $valueCollect['CopyImageLink'];
    $jsonOptions['visual']['CommentsDateFormat'] = $valueCollect['CommentsDateFormat'];
    $jsonOptions['visual']['FeControlsStyleUpload'] = $valueCollect['FeControlsStyleUpload'];
    $jsonOptions['visual']['FeControlsStyleRegistry'] = $valueCollect['FeControlsStyleRegistry'];
    $jsonOptions['visual']['FeControlsStyleLogin'] = $valueCollect['FeControlsStyleLogin'];
    $jsonOptions['visual']['BorderRadiusUpload'] = $valueCollect['BorderRadiusUpload'];
    $jsonOptions['visual']['BorderRadiusRegistry'] = $valueCollect['BorderRadiusRegistry'];
    $jsonOptions['visual']['BorderRadiusLogin'] = $valueCollect['BorderRadiusLogin'];
    $jsonOptions['visual']['ThankVote'] = $valueCollect['ThankVote'];
    $jsonOptions['visual']['GeneralID'] = $valueCollect['GeneralID'];
    $jsonOptions['visual']['CopyOriginalFileLink'] = $valueCollect['CopyOriginalFileLink'];
    $jsonOptions['visual']['ForwardOriginalFile'] = $valueCollect['ForwardOriginalFile'];
    $jsonOptions['visual']['ShareButtons'] = $valueCollect['ShareButtons'];
    $jsonOptions['visual']['TextBeforeWpPageEntry'] = $valueCollect['TextBeforeWpPageEntry'];
    $jsonOptions['visual']['TextAfterWpPageEntry'] = $valueCollect['TextAfterWpPageEntry'];
    $jsonOptions['visual']['ForwardToWpPageEntry'] = $valueCollect['ForwardToWpPageEntry'];
    $jsonOptions['visual']['ForwardToWpPageEntryInNewTab'] = $valueCollect['ForwardToWpPageEntryInNewTab'];
    $jsonOptions['visual']['ShowBackToGalleryButton'] = $valueCollect['ShowBackToGalleryButton'];
    $jsonOptions['visual']['BackToGalleryButtonText'] = $valueCollect['BackToGalleryButtonText'];
    $jsonOptions['visual']['TextDeactivatedEntry'] = $valueCollect['TextDeactivatedEntry'];
}

// has to be done extra was introduced later in 21.0.0, other logic before 21.0.0
if ($cgVersion < 21) {
    $valueCollect['ShareButtons'] = $ShareButtons;
    $valueCollect['TextBeforeWpPageEntry'] = $TextBeforeWpPageEntry;
    $valueCollect['TextAfterWpPageEntry'] = $TextAfterWpPageEntry;
    $valueCollect['ForwardToWpPageEntry'] = $ForwardToWpPageEntry;
    $valueCollect['ForwardToWpPageEntryInNewTab'] = $ForwardToWpPageEntryInNewTab;
    $valueCollect['ShowBackToGalleryButton'] = $ShowBackToGalleryButton;
    $valueCollect['BackToGalleryButtonText'] = $BackToGalleryButtonText;
    $valueCollect['TextDeactivatedEntry'] = $TextDeactivatedEntry;
    $jsonOptions['visual']['ShareButtons'] = $ShareButtons;
    $jsonOptions['visual']['TextBeforeWpPageEntry'] = $TextBeforeWpPageEntry;
    $jsonOptions['visual']['TextAfterWpPageEntry'] = $TextAfterWpPageEntry;
    $jsonOptions['visual']['ForwardToWpPageEntry'] = $ForwardToWpPageEntry;
    $jsonOptions['visual']['ForwardToWpPageEntryInNewTab'] = $ForwardToWpPageEntryInNewTab;
    $jsonOptions['visual']['ShowBackToGalleryButton'] = $ShowBackToGalleryButton;
    $jsonOptions['visual']['BackToGalleryButtonText'] = $BackToGalleryButtonText;
    $jsonOptions['visual']['TextDeactivatedEntry'] = $TextDeactivatedEntry;
    $jsonOptions['visual']['AdditionalCssEntryLandingPage'] = $AdditionalCssEntryLandingPage;// json only
    $jsonOptions['visual']['AdditionalCssGalleryPage'] = $AdditionalCssGalleryPage;// json only
}else{// because only json
    if(!isset($jsonOptions['visual']['AdditionalCssEntryLandingPage'])){$jsonOptions['visual']['AdditionalCssEntryLandingPage'] = $AdditionalCssEntryLandingPage;}// json only
    if(!isset($jsonOptions['visual']['AdditionalCssGalleryPage'])){$jsonOptions['visual']['AdditionalCssGalleryPage'] = $AdditionalCssGalleryPage;}// json only
}

if(!empty($isAllowGalleryScriptWasActivated)){// since 15.05 version always 0. Slideout will be not used anymore.
    $valueCollect['BlogLookFullWindow'] = 1;
    $jsonOptions['visual']['BlogLookFullWindow'] = 1;
}

// copy all as %s string here
/*$wpdb->insert(
    $tablename_options_visual,
    $valueCollect,
    array(
        '%s', '%s', '%s', '%s',
        '%s', '%s', '%s', '%s', '%s', '%s',
        '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
        '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
        '%s', '%s', '%s',
        '%s', '%s', '%s',
        '%s', '%s',
        '%s', '%s',
        '%s', '%s',
        '%s', '%s', '%s',
        '%s', '%s', '%s', '%s', '%s',
        '%s', '%s', '%s',// copy all as %s string here
        '%s', '%s', '%s', '%s',// copy all as %s string here
        '%s', '%s', '%s'// copy all as %s string here
    )// TextDeactivatedEntry is last one
);*/

//$contest_gal1ery_options_visual_id = cg_copy_table_row('contest_gal1ery_options_visual',$idToCopy,$nextIDgallery);

$contest_gal1ery_options_visual_id = cg_copy_table_row('contest_gal1ery_options_visual',$idToCopy,['contest_gal1ery_options_visual' => $valueCollect]);

$newOptionsVisual = $wpdb->get_row("SELECT * FROM $tablename_options_visual WHERE id = '$contest_gal1ery_options_visual_id' ");

/*
var_dump('$contest_gal1ery_options_visual_id');
var_dump($contest_gal1ery_options_visual_id);
print_r('$uploadInputsToCopyOldNewIds');

echo "<pre>";
print_r($uploadInputsToCopyOldNewIds);
echo "</pre>";

var_dump('$Field1IdGalleryView');
echo "<pre>";
print_r($Field1IdGalleryView);
echo "</pre>";
echo "<pre>";
print_r($Field2IdGalleryView);
echo "</pre>";
echo "<pre>";
print_r($Field3IdGalleryView);
echo "</pre>";
echo "<br>";*/

if(!empty($Field1IdGalleryView)){
    $wpdb->update(
        "$tablename_options_visual",
        array('Field1IdGalleryView' => $Field1IdGalleryView),
        array('id' => $newOptionsVisual->id),
        array('%d'),
        array('%d')
    );
}

if(!empty($Field2IdGalleryView)){
    $wpdb->update(
        "$tablename_options_visual",
        array('Field2IdGalleryView' => $Field2IdGalleryView),
        array('id' => $newOptionsVisual->id),
        array('%d'),
        array('%d')
    );
}

if(!empty($Field3IdGalleryView)){
    $wpdb->update(
        "$tablename_options_visual",
        array('Field3IdGalleryView' => $Field3IdGalleryView),
        array('id' => $newOptionsVisual->id),
        array('%d'),
        array('%d')
    );
}

$newSubTitleFieldId = 0;
if(!empty($SubTitleFieldId)){
    $newSubTitleFieldId = $uploadInputsToCopyOldNewIds[$SubTitleFieldId];
}

$newThirdTitleFieldId = 0;
if(!empty($ThirdTitleFieldId)){
    $newThirdTitleFieldId = $uploadInputsToCopyOldNewIds[$ThirdTitleFieldId];
}

// Create Options Input
$galleryToCopy = $wpdb->get_row("SELECT * FROM $tablename_options_input WHERE GalleryID = '$idToCopy' ");

$valueCollect = array();

foreach ($galleryToCopy as $key => $value) {

    if ($key == 'id') {
        $value = '';
    }
    if ($key == 'GalleryID') {
        $value = $nextIDgallery;
    }
    $valueCollect[$key] = $value;
    $jsonOptions['input'][$key] = $value;
}

/*$wpdb->insert(
    $tablename_options_input,
    $valueCollect,
    array(
        '%s', '%d', '%d',
        '%s', '%s', '%d'
    )// ShowFormAfterUpload is last
);*/

//cg_copy_table_row('contest_gal1ery_options_input',$idToCopy,$nextIDgallery);

cg_copy_table_row('contest_gal1ery_options_input',$idToCopy,['contest_gal1ery_options_input' => $valueCollect]);

// Create email user
$galleryToCopy = $wpdb->get_row("SELECT * FROM $tablenameMail WHERE GalleryID = '$idToCopy' ");

$valueCollect = array();

foreach ($galleryToCopy as $key => $value) {

    if ($key == 'id') {
        $value = '';
    }
    if ($key == 'GalleryID') {
        $value = $nextIDgallery;
    }
    $valueCollect[$key] = $value;

}

/*$wpdb->insert(
    $tablenameMail,
    $valueCollect,
    array(
        '%s', '%d', '%s',
        '%s', '%s', '%s',
        '%s', '%s', '%s'
    )
);*/

//cg_copy_table_row('contest_gal1ery_mail',$idToCopy,$nextIDgallery);

cg_copy_table_row('contest_gal1ery_mail',$idToCopy,['contest_gal1ery_mail' => $valueCollect]);

// Create mail admin
$galleryToCopy = $wpdb->get_row("SELECT * FROM $tablename_mail_admin WHERE GalleryID = '$idToCopy' ");

$valueCollect = array();

foreach ($galleryToCopy as $key => $value) {

    if ($key == 'id') {
        $value = '';
    }
    if ($key == 'GalleryID') {
        $value = $nextIDgallery;
    }
    $valueCollect[$key] = $value;

}

/*$wpdb->insert(
    $tablename_mail_admin,
    $valueCollect,
    array(
        '%s', '%d', '%s', '%s',
        '%s', '%s', '%s',
        '%s', '%s', '%s'
    )
);*/

//cg_copy_table_row('contest_gal1ery_mail_admin',$idToCopy,$nextIDgallery);

cg_copy_table_row('contest_gal1ery_mail_admin',$idToCopy,['contest_gal1ery_mail_admin' => $valueCollect]);


// Create mail user upload
$galleryToCopy = $wpdb->get_row("SELECT * FROM $tablename_mail_user_upload WHERE GalleryID = '$idToCopy' ");

$valueCollect = array();

foreach ($galleryToCopy as $key => $value) {

    if ($key == 'id') {
        $value = '';
    }
    if ($key == 'GalleryID') {
        $value = $nextIDgallery;
    }
    $valueCollect[$key] = $value;

}

/*$wpdb->insert(
    $tablename_mail_user_upload,
    $valueCollect,
    array(
        '%s', '%d', '%d', '%s',
        '%s', '%s', '%s',
        '%s', '%s', '%d'
    )
);*/

//cg_copy_table_row('contest_gal1ery_mail_user_upload',$idToCopy,$nextIDgallery);

cg_copy_table_row('contest_gal1ery_mail_user_upload',$idToCopy,['contest_gal1ery_mail_user_upload' => $valueCollect]);

// Create mail user upload comment
$galleryToCopy = $wpdb->get_row("SELECT * FROM $tablename_mail_user_comment WHERE GalleryID = '$idToCopy' ");

$valueCollect = array();

foreach ($galleryToCopy as $key => $value) {

    if ($key == 'id') {
        $value = '';
    }
    if ($key == 'GalleryID') {
        $value = $nextIDgallery;
    }
    $valueCollect[$key] = $value;

}

/*$wpdb->insert(
    $tablename_mail_user_comment,
    $valueCollect,
    array(
        '%s', '%d', '%d', '%s',
        '%s', '%s', '%s',
        '%s', '%s', '%s', '%s'
    )
);*/

//cg_copy_table_row('contest_gal1ery_mail_user_comment',$idToCopy,$nextIDgallery);

cg_copy_table_row('contest_gal1ery_mail_user_comment',$idToCopy,['contest_gal1ery_mail_user_comment' => $valueCollect]);

// Create mail user upload vote
$galleryToCopy = $wpdb->get_row("SELECT * FROM $tablename_mail_user_vote WHERE GalleryID = '$idToCopy' ");

$valueCollect = array();

foreach ($galleryToCopy as $key => $value) {

    if ($key == 'id') {
        $value = '';
    }
    if ($key == 'GalleryID') {
        $value = $nextIDgallery;
    }
    $valueCollect[$key] = $value;

}

/*$wpdb->insert(
    $tablename_mail_user_vote,
    $valueCollect,
    array(
        '%s', '%d', '%d', '%s',
        '%s', '%s', '%s',
        '%s', '%s', '%s', '%s'
    )
);*/

//cg_copy_table_row('contest_gal1ery_mail_user_vote',$idToCopy,$nextIDgallery);

cg_copy_table_row('contest_gal1ery_mail_user_vote',$idToCopy,['contest_gal1ery_mail_user_vote' => $valueCollect]);

// Create confirmation email

$galleryToCopy = $wpdb->get_row("SELECT * FROM $tablename_mail_confirmation WHERE GalleryID = '$idToCopy' ");

$valueCollect = array();

foreach ($galleryToCopy as $key => $value) {

    if ($key == 'id') {
        $value = '';
    }
    if ($key == 'GalleryID') {
        $value = $nextIDgallery;
    }
    $valueCollect[$key] = $value;

}

/*$wpdb->insert(
    $tablename_mail_confirmation,
    $valueCollect,
    array(
        '%s', '%d', '%s',
        '%s', '%s', '%s',
        '%s', '%s', '%s',
        '%s'
    )
);*/

//cg_copy_table_row('contest_gal1ery_mail_confirmation',$idToCopy,$nextIDgallery);

cg_copy_table_row('contest_gal1ery_mail_confirmation',$idToCopy,['contest_gal1ery_mail_confirmation' => $valueCollect]);

// Create categories

if ($cgVersion >= 0) {

    $galleryCategoriesToCopy = $wpdb->get_results("SELECT * FROM $tablenameCategories WHERE GalleryID = '$idToCopy' ");

    if (!empty($galleryCategoriesToCopy)) {

        $valueCollect = array();
        $categoriesJson = array();
        $collectCatIdsArray = array();

        $tableNameForColumns = $wpdb->prefix . 'contest_gal1ery_categories';
        $columns = $wpdb->get_results( "SHOW COLUMNS FROM $tableNameForColumns" );

        foreach ($galleryCategoriesToCopy as $key => $rowObject) {

            foreach ($rowObject as $key1 => $value1) {

                if ($key1 == 'id') {
                    //$collectCatIdsArray[$value1] = 0;// pauschal erstmal null setzen
                    $catIdOld = $value1;
                    $value1 = '';
                }
                if ($key1 == 'GalleryID') {
                    $value1 = $nextIDgallery;
                }
                $valueCollect[$key1] = $value1;

            }

            /*$wpdb->insert(
                $tablenameCategories,
                $valueCollect,
                array(
                    '%s', '%d', '%s',
                    '%d', '%s', '%d', '%d'
                )
            );
            $lastCategoryId = $wpdb->get_var("SELECT MAX(id) FROM $tablenameCategories");*/
            //$nextInputId = cg_copy_table_row('contest_gal1ery_categories',$rowObject->id,$nextIDgallery,'',0,$columns);

            $nextCategoryId = cg_copy_table_row('contest_gal1ery_categories',$rowObject->id,['contest_gal1ery_categories' => $valueCollect],'',$columns);

            $collectCatIdsArray[$catIdOld] = $nextCategoryId;

            /*                $lastCategoryInfo = $wpdb->get_var("SELECT MAX(id) FROM $tablenameCategories");
                            $lastCategoryId = $lastCategoryInfo;
                            $collectCatIdsArray[$catIdOld] = $lastCategoryId;
                            $valueCollect['id'] = $lastCategoryId;
                            $categoriesJson[] = $valueCollect;

                            $valueCollect = array();*/

        }


        $galleryCategoriesCopied = $wpdb->get_results("SELECT * FROM $tablenameCategories WHERE GalleryID = '$nextIDgallery' ");

        $categoriesJson = array();

        if(!empty($galleryCategoriesCopied)){

            foreach ($galleryCategoriesCopied as $key => $value) {

                $categoriesJson[$value->id] = array();
                $categoriesJson[$value->id]['id'] = $value->id;
                $categoriesJson[$value->id]['GalleryID'] = $value->GalleryID;
                $categoriesJson[$value->id]['Name'] = $value->Name;
                $categoriesJson[$value->id]['Field_Order'] = $value->Field_Order;
                $categoriesJson[$value->id]['Active'] = $value->Active;

            }

            $jsonFile = $uploadFolder['basedir'] . '/contest-gallery/gallery-id-' . $nextIDgallery . '/json/' . $nextIDgallery . '-categories.json';
            $fp = fopen($jsonFile, 'w');
            fwrite($fp, json_encode($categoriesJson));
            fclose($fp);

        }


    }

}


// Create Pro Options

$galleryToCopy = $wpdb->get_row("SELECT * FROM $tablename_pro_options WHERE GalleryID = '$idToCopy' ");

$valueCollect = array();

foreach ($galleryToCopy as $key => $value) {

    if ($key == 'id') {
        $value = '';
    }
    if ($key == 'GalleryID') {
        $value = $nextIDgallery;
    }
    $valueCollect[$key] = $value;
    $jsonOptions['pro'][$key] = $value;

}

// v10 adaption
if ($cgVersion < 10) {
    $valueCollect['Search'] = 1;
    $valueCollect['GalleryUpload'] = 1;
    $valueCollect['MinusVote'] = 0;
    $valueCollect['SlideTransition'] = 'translateX';

    $valueCollect['HideRegFormAfterLogin'] = 0;
    $valueCollect['HideRegFormAfterLoginShowTextInstead'] = 0;
    $valueCollect['HideRegFormAfterLoginTextToShow'] = '';


    $GalleryUploadTextBefore = "<h2>Welcome to the contest</h2><p>Do your entry to be a part of the contest</p>";
    $GalleryUploadTextBefore = htmlentities($GalleryUploadTextBefore, ENT_QUOTES);
    $valueCollect['GalleryUploadTextBefore'] = $GalleryUploadTextBefore;
    $valueCollect['GalleryUploadTextAfter'] = '';

    $confirmationText = '<p>Your entry was successful<br><br><br><b>Note for first time Contest Gallery user:</b>
<br/><br/>This text can be configurated in "Edit options" > "Contact options" > "In gallery contact form configuration"<br/><br/>
"Automatically activate users entries after successful frontend contact" can be activated/deactivated in "Edit options" >>> "Contact options"
</p>';
    $confirmationText = htmlentities($confirmationText, ENT_QUOTES);
    $valueCollect['GalleryUploadConfirmationText'] = $confirmationText;

    $jsonOptions['pro']['GalleryUploadTextBefore'] = $GalleryUploadTextBefore;
    $jsonOptions['pro']['GalleryUploadTextAfter'] = '';
    $jsonOptions['pro']['GalleryUploadConfirmationText'] = $confirmationText;

    $VotesInTime = 0;
    $VotesInTimeQuantity = 1;
    $VotesInTimeIntervalReadable = '24:00';
    $VotesInTimeIntervalSeconds = 86400;
    $VotesInTimeIntervalAlertMessage = "You can vote only 1 time per day";

    $jsonOptions['pro']['VotesInTime'] = $VotesInTime;
    $jsonOptions['pro']['VotesInTimeQuantity'] = $VotesInTimeQuantity;
    $jsonOptions['pro']['VotesInTimeIntervalReadable'] = $VotesInTimeIntervalReadable;
    $jsonOptions['pro']['VotesInTimeIntervalSeconds'] = $VotesInTimeIntervalSeconds;
    $jsonOptions['pro']['VotesInTimeIntervalAlertMessage'] = $VotesInTimeIntervalAlertMessage;

    $jsonOptions['pro']['HideRegFormAfterLogin'] = $valueCollect['HideRegFormAfterLogin'];
    $jsonOptions['pro']['HideRegFormAfterLoginShowTextInstead'] = $valueCollect['HideRegFormAfterLoginShowTextInstead'];
    $jsonOptions['pro']['HideRegFormAfterLoginTextToShow'] = $valueCollect['HideRegFormAfterLoginTextToShow'];

    $jsonOptions['pro']['SliderFullWindow'] = 0;

    $ShowExif = 0;

    if(function_exists('exif_read_data')){
        $jsonOptions['pro']['ShowExif'] = 1;
    }

    $jsonOptions['pro']['RegUserGalleryOnly'] = 0;
    $jsonOptions['pro']['RegUserGalleryOnlyText'] = '';

    $jsonOptions['pro']['RegUserMaxUpload'] = 0;
    $jsonOptions['pro']['IsModernFiveStar'] = 0;// old created galleries have to be additionally converted to get modern five star

    $jsonOptions['pro']['GalleryUploadOnlyUser'] = 0;

    $jsonOptions['pro']['FbLikeNoShare'] = 0;
    $jsonOptions['pro']['FbLikeOnlyShare'] = 0;
    $jsonOptions['pro']['VoteNotOwnImage'] = 0;
    $jsonOptions['pro']['PreselectSort'] = '';
    $jsonOptions['pro']['UploadRequiresCookieMessage'] = 'Please allow cookies to upload';
    $jsonOptions['pro']['ShowCatsUnchecked'] = 1;
    $jsonOptions['pro']['ShowCatsUnfolded'] = 1;
    $jsonOptions['pro']['RegMailOptional'] = 0;

    $jsonOptions['pro']['CustomImageName'] = 0;
    $jsonOptions['pro']['CustomImageNamePath'] = '';

    $jsonOptions['pro']['DeleteFromStorageIfDeletedInFrontend'] = 0;
    $jsonOptions['pro']['VotePerCategory'] = 0;
    $jsonOptions['pro']['VotesPerCategory'] = 0;

    $jsonOptions['pro']['VoteMessageSuccessActive'] = 0;
    $jsonOptions['pro']['VoteMessageWarningActive'] = 0;

    $jsonOptions['pro']['VoteMessageSuccessText'] = '';
    $jsonOptions['pro']['VoteMessageWarningText'] = '';

    $jsonOptions['pro']['CommNoteActive'] = 0;
    $jsonOptions['pro']['GeneralID'] = 0;
    $jsonOptions['pro']['ShowProfileImage'] = 0;
    /*        $jsonOptions['pro']['BulkUploadType'] = 1;
            $jsonOptions['pro']['UploadFormAppearance'] = 1;*/

    $jsonOptions['pro']['AllowUploadJPG'] = 1;
    $jsonOptions['pro']['AllowUploadPNG'] = 1;
    $jsonOptions['pro']['AllowUploadGIF'] = 1;
    $jsonOptions['pro']['AllowUploadICO'] = 1;
    $jsonOptions['pro']['AdditionalFiles'] = 0;
    $jsonOptions['pro']['AdditionalFilesCount'] = (cg_get_version()=='contest-gallery-pro') ? 9 : 2;
    $jsonOptions['pro']['ReviewComm'] = 0;
    $jsonOptions['pro']['BackToGalleryButtonURL'] = '';
    $jsonOptions['pro']['WpPageParentRedirectURL'] = '';
    $jsonOptions['pro']['RedirectURLdeletedEntry'] = '';
    $jsonOptions['pro']['RegUserMaxUploadPerCategory'] = 0;

}

// since version 17.0.0, fb like complete reset
$valueCollect['FbLikeNoShare'] = 0;
$valueCollect['FbLikeOnlyShare'] = 0;
$jsonOptions['pro']['FbLikeNoShare'] = 0;
$jsonOptions['pro']['FbLikeOnlyShare'] = 0;

/*$wpdb->insert(
    $tablename_pro_options,
    $valueCollect,
    array(
        '%s', '%d', '%s', '%s',
        '%d', '%s',
        '%d', '%s',
        '%s', '%s',
        '%s', '%s', '%s', '%d', '%s',
        '%d', '%d', '%d', '%d',
        '%d', '%s', '%s', '%s', '%d', '%d', '%s',
        '%d', '%d', '%s', '%d', '%s', '%d', '%d',
        '%d', '%d', '%s',
        '%d', '%s', '%d', '%d',
        '%d', '%d', '%d', '%s',
        '%s', '%d', '%d',
        '%d', '%s', '%d',
        '%d', '%d', '%d',
        '%d', '%d', '%s', '%s',
        '%d', '%d', '%d',
        '%d', '%d', '%d', '%d',
        '%d', '%d', '%d', '%s', '%s', '%s',
        '%d', '%s'
    )// InformAdminActivationURL was last one
);*/
//cg_copy_table_row('contest_gal1ery_pro_options',$idToCopy,$nextIDgallery);

cg_copy_table_row('contest_gal1ery_pro_options',$idToCopy,['contest_gal1ery_pro_options' => $valueCollect]);

$shortcodeSpecificToSetArray = include(__DIR__.'/../../vars/general/short-code-specific-to-set-array.php');

$jsonOptionsGalleryPrev = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$idToCopy.'/json/'.$idToCopy.'-options.json';

if(file_exists($jsonOptionsGalleryPrev)){

    $fp = fopen($jsonOptionsGalleryPrev, 'r');
    $jsonOptionsPrev = json_decode(fread($fp, filesize($jsonOptionsGalleryPrev)),true);
    fclose($fp);

    $jsonOptionsPrevSource = $jsonOptionsPrev;

    // only then shortcode specific options can be copied
    if(!empty($jsonOptionsPrev[$idToCopy])){
        /*
                    echo "<pre>";
                    print_r($jsonOptionsPrev);
                    echo "</pre>";*/

        // this is only for example, to show that old options will be copied in old way also and still available if user want to switch to old version
        // added in 10990, do not remove in the moment! It is for fallback!
        // If somebody save the options and then switch back to older version, he will still be able to use gallery in frontend
        $jsonOptions = $jsonOptions;

        if(!empty($jsonOptionsPrev[$idToCopy])){
            if(!empty($jsonOptionsPrev[$idToCopy]['general'])){
                $jsonOptionsPrev[$idToCopy]['general']['Version'] = $VersionForScripts;// update db version here additionally requires
                $jsonOptionsPrev[$idToCopy]['general']['VersionDecimal'] = $VersionDecimal;// update version decimal here requires
                $jsonOptionsPrev[$idToCopy]['pro']['FbLikeNoShare'] = 0;
                $jsonOptionsPrev[$idToCopy]['pro']['FbLikeOnlyShare'] = 0;
                $jsonOptionsPrev[$idToCopy]['general']['FbLike'] = 0;
                $jsonOptionsPrev[$idToCopy]['general']['FbLikeGallery'] = 0;
                $jsonOptionsPrev[$idToCopy]['general']['FbLikeGoToGalleryLink'] = '';
            }
        }
        if(!empty($jsonOptionsPrev[$idToCopy.'-u'])){
            if(!empty($jsonOptionsPrev[$idToCopy.'-u']['general'])){
                $jsonOptionsPrev[$idToCopy.'-u']['general']['Version'] = $VersionForScripts;// update db version here additionally requires
                $jsonOptionsPrev[$idToCopy.'-u']['general']['VersionDecimal'] = $VersionDecimal;// update version decimal here requires
                $jsonOptionsPrev[$idToCopy.'-u']['pro']['FbLikeNoShare'] = 0;
                $jsonOptionsPrev[$idToCopy.'-u']['pro']['FbLikeOnlyShare'] = 0;
                $jsonOptionsPrev[$idToCopy.'-u']['general']['FbLike'] = 0;
                $jsonOptionsPrev[$idToCopy.'-u']['general']['FbLikeGallery'] = 0;
                $jsonOptionsPrev[$idToCopy.'-u']['general']['FbLikeGoToGalleryLink'] = '';
            }
        }
        if(!empty($jsonOptionsPrev[$idToCopy.'-nv'])){
            if(!empty($jsonOptionsPrev[$idToCopy.'-nv']['general'])){
                $jsonOptionsPrev[$idToCopy.'-nv']['general']['Version'] = $VersionForScripts;// update db version here additionally requires
                $jsonOptionsPrev[$idToCopy.'-nv']['general']['VersionDecimal'] = $VersionDecimal;// update version decimal here requires
                $jsonOptionsPrev[$idToCopy.'-nv']['pro']['FbLikeNoShare'] = 0;
                $jsonOptionsPrev[$idToCopy.'-nv']['pro']['FbLikeOnlyShare'] = 0;
                $jsonOptionsPrev[$idToCopy.'-nv']['general']['FbLike'] = 0;
                $jsonOptionsPrev[$idToCopy.'-nv']['general']['FbLikeGallery'] = 0;
                $jsonOptionsPrev[$idToCopy.'-nv']['general']['FbLikeGoToGalleryLink'] = '';
            }
        }
        if(!empty($jsonOptionsPrev[$idToCopy.'-w'])){
            if(!empty($jsonOptionsPrev[$idToCopy.'-w']['general'])){
                $jsonOptionsPrev[$idToCopy.'-w']['general']['Version'] = $VersionForScripts;// update db version here additionally requires
                $jsonOptionsPrev[$idToCopy.'-w']['general']['VersionDecimal'] = $VersionDecimal;// update version decimal here requires
                $jsonOptionsPrev[$idToCopy.'-w']['pro']['FbLikeNoShare'] = 0;
                $jsonOptionsPrev[$idToCopy.'-w']['pro']['FbLikeOnlyShare'] = 0;
                $jsonOptionsPrev[$idToCopy.'-w']['general']['FbLike'] = 0;
                $jsonOptionsPrev[$idToCopy.'-w']['general']['FbLikeGallery'] = 0;
                $jsonOptionsPrev[$idToCopy.'-w']['general']['FbLikeGoToGalleryLink'] = '';
            }
        }

        $jsonOptions[$nextIDgallery] = $jsonOptionsPrev[$idToCopy];
        $jsonOptions[$nextIDgallery.'-u'] = $jsonOptionsPrev[$idToCopy.'-u'];
        $jsonOptions[$nextIDgallery.'-nv'] = $jsonOptionsPrev[$idToCopy.'-nv'];
        $jsonOptions[$nextIDgallery.'-w'] = $jsonOptionsPrev[$idToCopy.'-w'];
        if(!empty($Field1IdGalleryView)){
            $jsonOptions[$nextIDgallery]['visual']['Field1IdGalleryView'] = $Field1IdGalleryView;
            $jsonOptions[$nextIDgallery.'-u']['visual']['Field1IdGalleryView'] = $Field1IdGalleryView;
            $jsonOptions[$nextIDgallery.'-nv']['visual']['Field1IdGalleryView'] = $Field1IdGalleryView;
            $jsonOptions[$nextIDgallery.'-w']['visual']['Field1IdGalleryView'] = $Field1IdGalleryView;
        }
        if(!empty($Field2IdGalleryView)){
            $jsonOptions[$nextIDgallery]['visual']['Field2IdGalleryView'] = $Field2IdGalleryView;
            $jsonOptions[$nextIDgallery.'-u']['visual']['Field2IdGalleryView'] = $Field2IdGalleryView;
            $jsonOptions[$nextIDgallery.'-nv']['visual']['Field2IdGalleryView'] = $Field2IdGalleryView;
            $jsonOptions[$nextIDgallery.'-w']['visual']['Field2IdGalleryView'] = $Field2IdGalleryView;
        }
        if(!empty($Field3IdGalleryView)){
            $jsonOptions[$nextIDgallery]['visual']['Field3IdGalleryView'] = $Field3IdGalleryView;
            $jsonOptions[$nextIDgallery.'-u']['visual']['Field3IdGalleryView'] = $Field3IdGalleryView;
            $jsonOptions[$nextIDgallery.'-nv']['visual']['Field3IdGalleryView'] = $Field3IdGalleryView;
            $jsonOptions[$nextIDgallery.'-w']['visual']['Field3IdGalleryView'] = $Field3IdGalleryView;
        }
        if(!empty($newSubTitleFieldId)){
            $jsonOptions[$nextIDgallery]['visual']['SubTitle'] = $newSubTitleFieldId;
            $jsonOptions[$nextIDgallery.'-u']['visual']['SubTitle'] = $newSubTitleFieldId;
            $jsonOptions[$nextIDgallery.'-nv']['visual']['SubTitle'] = $newSubTitleFieldId;
            $jsonOptions[$nextIDgallery.'-w']['visual']['SubTitle'] = $newSubTitleFieldId;
        }
        if(!empty($newThirdTitleFieldId)){
            $jsonOptions[$nextIDgallery]['visual']['ThirdTitle'] = $newThirdTitleFieldId;
            $jsonOptions[$nextIDgallery.'-u']['visual']['ThirdTitle'] = $newThirdTitleFieldId;
            $jsonOptions[$nextIDgallery.'-nv']['visual']['ThirdTitle'] = $newThirdTitleFieldId;
            $jsonOptions[$nextIDgallery.'-w']['visual']['ThirdTitle'] = $newThirdTitleFieldId;
        }

        // since 15.05 version always 0. Slideout will be not used anymore.
        if(!empty($jsonOptions[$nextIDgallery]['general']['AllowGalleryScript'])){
            $jsonOptions[$nextIDgallery]['pro']['SliderFullWindow'] = 0;
            $jsonOptions[$nextIDgallery]['visual']['BlogLookFullWindow'] = 1;
        }
        if(!empty($jsonOptions[$nextIDgallery.'-u']['general']['AllowGalleryScript'])){
            $jsonOptions[$nextIDgallery.'-u']['pro']['SliderFullWindow'] = 0;
            $jsonOptions[$nextIDgallery.'-u']['visual']['BlogLookFullWindow'] = 1;
        }
        if(!empty($jsonOptions[$nextIDgallery.'-nv']['general']['AllowGalleryScript'])){
            $jsonOptions[$nextIDgallery.'-nv']['pro']['SliderFullWindow'] = 0;
            $jsonOptions[$nextIDgallery.'-nv']['visual']['BlogLookFullWindow'] = 1;
        }
        if(!empty($jsonOptions[$nextIDgallery.'-w']['general']['AllowGalleryScript'])){
            $jsonOptions[$nextIDgallery.'-w']['pro']['SliderFullWindow'] = 0;
            $jsonOptions[$nextIDgallery.'-w']['visual']['BlogLookFullWindow'] = 1;
        }

        $jsonOptions[$nextIDgallery]['general']['FullSizeSlideOutStart'] = 0;
        $jsonOptions[$nextIDgallery.'-u']['general']['FullSizeSlideOutStart'] = 0;
        $jsonOptions[$nextIDgallery.'-nv']['general']['FullSizeSlideOutStart'] = 0;
        $jsonOptions[$nextIDgallery.'-w']['general']['FullSizeSlideOutStart'] = 0;

        if ($cgVersion < 21) {
            $jsonOptions[$nextIDgallery]['visual']['ShareButtons'] = $ShareButtons;
            $jsonOptions[$nextIDgallery.'-u']['visual']['ShareButtons'] = $ShareButtons;
            $jsonOptions[$nextIDgallery.'-nv']['visual']['ShareButtons'] = $ShareButtons;
            $jsonOptions[$nextIDgallery.'-w']['visual']['ShareButtons'] = $ShareButtons;
            $jsonOptions[$nextIDgallery]['visual']['TextBeforeWpPageEntry'] = $TextBeforeWpPageEntry;
            $jsonOptions[$nextIDgallery.'-u']['visual']['TextBeforeWpPageEntry'] = $TextBeforeWpPageEntry;
            $jsonOptions[$nextIDgallery.'-nv']['visual']['TextBeforeWpPageEntry'] = $TextBeforeWpPageEntry;
            $jsonOptions[$nextIDgallery.'-w']['visual']['TextBeforeWpPageEntry'] = $TextBeforeWpPageEntry;
            $jsonOptions[$nextIDgallery]['visual']['TextAfterWpPageEntry'] = $TextAfterWpPageEntry;
            $jsonOptions[$nextIDgallery.'-u']['visual']['TextAfterWpPageEntry'] = $TextAfterWpPageEntry;
            $jsonOptions[$nextIDgallery.'-nv']['visual']['TextAfterWpPageEntry'] = $TextAfterWpPageEntry;
            $jsonOptions[$nextIDgallery.'-w']['visual']['TextAfterWpPageEntry'] = $TextAfterWpPageEntry;
            $jsonOptions[$nextIDgallery]['visual']['ForwardToWpPageEntry'] = $ForwardToWpPageEntry;
            $jsonOptions[$nextIDgallery.'-u']['visual']['ForwardToWpPageEntry'] = $ForwardToWpPageEntry;
            $jsonOptions[$nextIDgallery.'-nv']['visual']['ForwardToWpPageEntry'] = $ForwardToWpPageEntry;
            $jsonOptions[$nextIDgallery.'-w']['visual']['ForwardToWpPageEntry'] = $ForwardToWpPageEntry;
            $jsonOptions[$nextIDgallery]['visual']['ForwardToWpPageEntryInNewTab'] = $ForwardToWpPageEntryInNewTab;
            $jsonOptions[$nextIDgallery.'-u']['visual']['ForwardToWpPageEntryInNewTab'] = $ForwardToWpPageEntryInNewTab;
            $jsonOptions[$nextIDgallery.'-nv']['visual']['ForwardToWpPageEntryInNewTab'] = $ForwardToWpPageEntryInNewTab;
            $jsonOptions[$nextIDgallery.'-w']['visual']['ForwardToWpPageEntryInNewTab'] = $ForwardToWpPageEntryInNewTab;
            $jsonOptions[$nextIDgallery]['visual']['ShowBackToGalleryButton'] = $ShowBackToGalleryButton;
            $jsonOptions[$nextIDgallery.'-u']['visual']['ShowBackToGalleryButton'] = $ShowBackToGalleryButton;
            $jsonOptions[$nextIDgallery.'-nv']['visual']['ShowBackToGalleryButton'] = $ShowBackToGalleryButton;
            $jsonOptions[$nextIDgallery.'-w']['visual']['ShowBackToGalleryButton'] = $ShowBackToGalleryButton;
            $jsonOptions[$nextIDgallery]['visual']['BackToGalleryButtonText'] = $BackToGalleryButtonText;
            $jsonOptions[$nextIDgallery.'-u']['visual']['BackToGalleryButtonText'] = $BackToGalleryButtonText;
            $jsonOptions[$nextIDgallery.'-nv']['visual']['BackToGalleryButtonText'] = $BackToGalleryButtonText;
            $jsonOptions[$nextIDgallery.'-w']['visual']['BackToGalleryButtonText'] = $BackToGalleryButtonText;
            $jsonOptions[$nextIDgallery]['visual']['TextDeactivatedEntry'] = $TextDeactivatedEntry;
            $jsonOptions[$nextIDgallery.'-u']['visual']['TextDeactivatedEntry'] = $TextDeactivatedEntry;
            $jsonOptions[$nextIDgallery.'-nv']['visual']['TextDeactivatedEntry'] = $TextDeactivatedEntry;
            $jsonOptions[$nextIDgallery.'-w']['visual']['TextDeactivatedEntry'] = $TextDeactivatedEntry;
            $jsonOptions[$nextIDgallery]['visual']['AdditionalCssEntryLandingPage'] = $AdditionalCssEntryLandingPage;
            $jsonOptions[$nextIDgallery.'-u']['visual']['AdditionalCssEntryLandingPage'] = $AdditionalCssEntryLandingPage;
            $jsonOptions[$nextIDgallery.'-nv']['visual']['AdditionalCssEntryLandingPage'] = $AdditionalCssEntryLandingPage;
            $jsonOptions[$nextIDgallery.'-w']['visual']['AdditionalCssEntryLandingPage'] = $AdditionalCssEntryLandingPage;
            $jsonOptions[$nextIDgallery]['visual']['AdditionalCssGalleryPage'] = $AdditionalCssGalleryPage;
            $jsonOptions[$nextIDgallery.'-u']['visual']['AdditionalCssGalleryPage'] = $AdditionalCssGalleryPage;
            $jsonOptions[$nextIDgallery.'-nv']['visual']['AdditionalCssGalleryPage'] = $AdditionalCssGalleryPage;
            $jsonOptions[$nextIDgallery.'-w']['visual']['AdditionalCssGalleryPage'] = $AdditionalCssGalleryPage;

        }

        $jsonOptions[$nextIDgallery]['general']['ShowAlways'] = 3;
        $jsonOptions[$nextIDgallery.'-u']['general']['ShowAlways'] = 3;
        $jsonOptions[$nextIDgallery.'-nv']['general']['ShowAlways'] = 3;
        $jsonOptions[$nextIDgallery.'-w']['general']['ShowAlways'] = 3;

        $jsonOptions[$nextIDgallery]['general']['WpPageParent'] = $WpPageParent;
        $jsonOptions[$nextIDgallery.'-u']['general']['WpPageParent'] = $WpPageParent;
        $jsonOptions[$nextIDgallery.'-nv']['general']['WpPageParent'] = $WpPageParent;
        $jsonOptions[$nextIDgallery.'-w']['general']['WpPageParent'] = $WpPageParent;

        $jsonOptions[$nextIDgallery]['general']['WpPageParentUser'] = $WpPageParentUser;
        $jsonOptions[$nextIDgallery.'-u']['general']['WpPageParentUser'] = $WpPageParentUser;
        $jsonOptions[$nextIDgallery.'-nv']['general']['WpPageParentUser'] = $WpPageParentUser;
        $jsonOptions[$nextIDgallery.'-w']['general']['WpPageParentUser'] = $WpPageParentUser;

        $jsonOptions[$nextIDgallery]['general']['WpPageParentNoVoting'] = $WpPageParentNoVoting;
        $jsonOptions[$nextIDgallery.'-u']['general']['WpPageParentNoVoting'] = $WpPageParentNoVoting;
        $jsonOptions[$nextIDgallery.'-nv']['general']['WpPageParentNoVoting'] = $WpPageParentNoVoting;
        $jsonOptions[$nextIDgallery.'-w']['general']['WpPageParentNoVoting'] = $WpPageParentNoVoting;

        $jsonOptions[$nextIDgallery]['general']['WpPageParentWinner'] = $WpPageParentWinner;
        $jsonOptions[$nextIDgallery.'-u']['general']['WpPageParentWinner'] = $WpPageParentWinner;
        $jsonOptions[$nextIDgallery.'-nv']['general']['WpPageParentWinner'] = $WpPageParentWinner;
        $jsonOptions[$nextIDgallery.'-w']['general']['WpPageParentWinner'] = $WpPageParentWinner;

    }else{

        // if previous gallery options were never saved before required values will be copied
        $jsonOptions['visual']['Field1IdGalleryView'] = $Field1IdGalleryView;
        $jsonOptions['visual']['Field2IdGalleryView'] = $Field2IdGalleryView;
        $jsonOptions['visual']['Field3IdGalleryView'] = $Field3IdGalleryView;
        $jsonOptions['visual']['SubTitle'] = $newSubTitleFieldId;
        $jsonOptions['visual']['ThirdTitle'] = $newThirdTitleFieldId;

    }

    if(!empty($jsonOptionsPrev['icons'])){
        $jsonOptions['icons'] = $jsonOptionsPrev['icons'];
    }

    if(isset($jsonOptionsPrevSource['interval'])){
        $jsonOptions['interval'] = $jsonOptionsPrevSource['interval'];
    }

}

file_put_contents($galleryUpload . '/json/' . $nextIDgallery . '-options.json',json_encode($jsonOptions));

// Create f_output

$galleryToCopy = $wpdb->get_results("SELECT * FROM $tablename_form_output WHERE GalleryID = '$idToCopy' ");

$valueCollect = array();

$tableNameForColumns = $wpdb->prefix . 'contest_gal1ery_f_output';
$columns = $wpdb->get_results( "SHOW COLUMNS FROM $tableNameForColumns" );

foreach ($galleryToCopy as $key => $rowObject) {

    foreach ($rowObject as $key1 => $value1) {

        if ($key1 == 'id') {
            $value1 = '';
        }
        if ($key1 == 'GalleryID') {
            $value1 = $nextIDgallery;
        }
        $valueCollect[$key1] = $value1;

    }

    /*$wpdb->insert(
        $tablename_form_output,
        $valueCollect,
        array(
            '%s', '%d', '%d',
            '%s', '%d', '%s'
        )
    );*/

    //cg_copy_table_row('contest_gal1ery_f_output',$rowObject->id,$nextIDgallery,'',0,$columns);

    cg_copy_table_row('contest_gal1ery_f_output',$rowObject->id,['contest_gal1ery_f_output' => $valueCollect],'',$columns);

    $valueCollect = array();

}

// Create a registry form and options for all galleries since 14.0.0
// since version 14.0.0 general form will be created if required, no copying anymore
cg_create_general_registration_form_v14();
cg_create_registry_and_login_options_v14();
// create a registry form and options for all galleries since 14.0.0--- END


// Create comment notification options
$galleryToCopy = $wpdb->get_results("SELECT * FROM $tablename_comments_notification_options WHERE GalleryID = '$idToCopy' ");

$valueCollect = array();
$tableNameForColumns = $wpdb->prefix . 'contest_gal1ery_comments_notification_options';
$columns = $wpdb->get_results( "SHOW COLUMNS FROM $tableNameForColumns" );
foreach ($galleryToCopy as $key => $rowObject) {

    foreach ($rowObject as $key1 => $value1) {

        if ($key1 == 'id') {
            $value1 = '';
        }
        if ($key1 == 'GalleryID') {
            $value1 = $nextIDgallery;
        }
        $valueCollect[$key1] = $value1;
    }

//var_dump($valueCollect);
    /*$wpdb->insert(
        $tablename_comments_notification_options,
        $valueCollect,
        array(
            '%s', '%d',
            '%s', '%s',
            '%s', '%s', '%s',
            '%s', '%s'
        )
    );*/

   // cg_copy_table_row('contest_gal1ery_comments_notification_options',$rowObject->id,$nextIDgallery,'',0,$columns);

    cg_copy_table_row('contest_gal1ery_comments_notification_options',$rowObject->id,['contest_gal1ery_comments_notification_options' => $valueCollect],'',$columns);

    $valueCollect = array();

}

// write $collectInputIdsArray json for getting it later when processing images
$fp = fopen($galleryUpload . '/json/' . $nextIDgallery . '-collect-input-ids-array.json', 'w');
fwrite($fp, json_encode($collectInputIdsArray));
fclose($fp);

if(empty($collectCatIdsArray)){
    $collectCatIdsArray = array();
}

// write $collectCatIdsArray json for getting it later when processing images
$fp = fopen($galleryUpload . '/json/' . $nextIDgallery . '-collect-cat-ids-array.json', 'w');
fwrite($fp, json_encode($collectCatIdsArray));
fclose($fp);

$tstampJson = array();
$fp = fopen($galleryUpload.'/json/'.$nextIDgallery.'-gallery-tstamp.json', 'w');
fwrite($fp, json_encode(time()));
fclose($fp);

// copy translations
$translationsFileToCopy = $uploadFolder["basedir"]."/contest-gallery/gallery-id-$idToCopy/json/$idToCopy-translations.json";

$translations = array();

if(file_exists($translationsFileToCopy)){
    $fp = fopen($translationsFileToCopy, 'r');
    $translations = json_decode(fread($fp, filesize($translationsFileToCopy)),true);
    fclose($fp);
}

$translationsFileNextGallery = $uploadFolder["basedir"]."/contest-gallery/gallery-id-$nextIDgallery/json/$nextIDgallery-translations.json";
$fp = fopen($translationsFileNextGallery, 'w');
fwrite($fp, json_encode($translations));
fclose($fp);

// copy txt files
$cgSwitchedFileToCopy = $uploadFolder["basedir"]."/contest-gallery/gallery-id-$idToCopy/json/cg-switched.txt";
if(file_exists($cgSwitchedFileToCopy)){
    $fp = fopen($cgSwitchedFileToCopy, 'r');
    $cgSwitchedFileToCopyTimestamp = json_decode(fread($fp, filesize($cgSwitchedFileToCopy)),true);
    fclose($fp);

    $cgSwitchedFileNextGallery = $uploadFolder["basedir"]."/contest-gallery/gallery-id-$nextIDgallery/json/cg-switched.txt";
    $fp = fopen($cgSwitchedFileNextGallery, 'w');
    fwrite($fp, $cgSwitchedFileToCopyTimestamp);
    fclose($fp);
}
// copy txt files --- END


?>