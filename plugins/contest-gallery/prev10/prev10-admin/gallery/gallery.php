<?php

cg_check_nonce();

$start = 0; // Startwert setzen (0 = 1. Zeile)
$step =10;

if (isset($_GET["start"])) {
    $muster = "/^[0-9]+$/"; // reg. Ausdruck f�r Zahlen
    if (preg_match($muster, absint($_GET["start"])) == 0) {
        $start = 0; // Bei Manipulation R�ckfall auf 0
    } else {
        $start = absint($_GET["start"]);
    }
}

if (isset($_GET["step"])) {
    $muster = "/^[0-9]+$/"; // reg. Ausdruck f�r Zahlen
    if (preg_match($muster, absint($_GET["start"])) == 0) {
        $step = 10; // Bei Manipulation R�ckfall auf 0
    } else {
        $step = absint($_GET["step"]);
    }
}

$GalleryID = absint($_GET['option_id']);

global $wpdb;
$tablenameOptions = $wpdb->prefix . "contest_gal1ery_options";

$CountGallery = $wpdb->get_var( "SELECT COUNT(*) FROM $tablenameOptions WHERE id = '$GalleryID'" );

if($CountGallery==0){
    echo "<p style='font-size:16px;text-align: center;font-weight: bold;'>There is no gallery with ID: $GalleryID</p>";
}else{
    $cgVersion = intval($wpdb->get_var( "SELECT Version FROM $tablenameOptions WHERE id = '$GalleryID'" ));
    if(empty($cgVersion) || $cgVersion<7){
        cg_check_if_database_tables_ok();
        cg_check_if_upload_folder_permissions_ok();
        echo "<div style='width:937px;text-align:center;font-size:20px;'>";
        echo "<p style='font-size:16px;'>    
        <strong>Please create a new gallery</strong><br> Galleries created before update to version 7 have old logic and will not supported anymore.<br>Everything will be copied: options, forms, images, votes and comments.</p><p>
        <strong>NOTE:</strong> If your gallery contains many images (100+), then some extra configuration might be required. <br>Check out this tutorial <a href='https://www.contest-gallery.com/copy-galleries-created-before-version-7-with-images-new/' target='_blank'>How to copy galleries created before update version 7 with images</a>.</p></div>";
    }else if($cgVersion>=7){
        echo "<div style='width:937px;font-size: 14px;text-align: center;' id='cgVersionExplanation'>";
        echo "<p style='line-height: 18px;'>You are using gallery created before version 10<br> This gallery version will be not supported anymore. <br> Copy this gallery (you can copy everything, forms, entries and images) or create a new one to use:
    <ul>
    <li style='line-height: 16px; height: 16px;font-weight: bold;margin-bottom: 0;'>new faster frontend engine</li>
    <li style='line-height: 16px; height: 16px;font-weight: bold;margin-bottom: 0;'>modern slide out view</li>
    <li style='line-height: 16px; height: 16px;font-weight: bold;margin-bottom: 0;'>full browser window gallery view</li>
    <li style='line-height: 16px; height: 16px;font-weight: bold;margin-bottom: 0;'>search option in frontend 
      (by file names, field entries or categories)</li>
    <li style='line-height: 16px; height: 16px;font-weight: bold;margin-bottom: 0;'>instant search results</li>
    <li style='line-height: 16px; height: 16px;font-weight: bold;margin-bottom: 0;'>instant pagination switching</li>
    <li style='line-height: 16px; height: 16px;font-weight: bold;margin-bottom: 0;'>instant sorting and category switching</li>
    <li style='line-height: 16px; height: 16px;font-weight: bold;margin-bottom: 0;'>instant random sort with random sort button</li>
    <li style='line-height: 16px; height: 16px;font-weight: bold;margin-bottom: 0;'>multiple gallery shortcodes on one page</li>
    <li style='line-height: 16px; height: 16px;font-weight: bold;margin-bottom: 0;'>end contest date in hours and minutes</li>
    <li style='line-height: 16px; height: 16px;font-weight: bold;margin-bottom: 0;'>in gallery upload form</li>
    </ul>
    </p></div>";
    }
}




?>