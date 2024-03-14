<?php

$imageId = $_GET['image_id'];
$GalleryID = $_GET['option_id'];
$gid = $_GET['option_id'];

// Tabellennamen ermitteln, GalleryID wurde als Shortcode bereits �bermittelt.
global $wpdb;

require_once(dirname(__FILE__) . "/../nav-menu.php");

$start = sanitize_text_field(!empty($_GET["start"]) ? $_GET["start"] : 0);
$start = intval($start) ? $start : 0;

$end = sanitize_text_field(!empty($_GET["end"]) ? $_GET["end"] : 50);
$end = intval($end) ? $end : 50;

$tablename = $wpdb->prefix . "contest_gal1ery";
$tablename_options = $wpdb->prefix . "contest_gal1ery_options";
$tablename_pro_options = $wpdb->prefix . "contest_gal1ery_pro_options";
$tablename_categories = $wpdb->prefix . "contest_gal1ery_categories";
$tablename_ip = $wpdb->prefix . "contest_gal1ery_ip";
$table_posts = $wpdb->prefix."posts";
$table_wp_users = $wpdb->base_prefix."users";
$tablename_google_users = $wpdb->base_prefix."contest_gal1ery_google_users";

$imageData = $wpdb->get_row("SELECT * FROM $tablename WHERE id = '$imageId'");
$WpUserId = $imageData->WpUserId;
$ImgType = $imageData->ImgType;
$user_login = $wpdb->get_var("SELECT user_login  FROM $table_wp_users WHERE ID = $WpUserId ORDER BY ID ASC");

$categories = $wpdb->get_results( "SELECT * FROM $tablename_categories WHERE GalleryID = '$GalleryID' ORDER BY Field_Order DESC");

$galeryID = $GalleryID;

// for check-language.php
include(__DIR__ ."/../../../check-language.php");

$categoriesUidsNames = array();

if(count($categories)){

    $categoriesUidsNames = array();

    $categoriesUidsNames[0] = $language_Other;

    foreach ($categories as $category) {

        $categoriesUidsNames[$category->id] = $category->Name;

    }

}

$generalOptions = $wpdb->get_row("SELECT * FROM $tablename_options WHERE id = '$GalleryID'");
$AllowRating = $generalOptions->AllowRating;
if($AllowRating==1){
    $AllowRating = 15;
}
$AllowRatingMax = 0;// define variable simple
if($AllowRating>=12 AND $AllowRating<=20){
    $AllowRatingMax = $AllowRating-10;// set some value here
}

$proOptions = $wpdb->get_row("SELECT * FROM $tablename_pro_options WHERE GalleryID = '$GalleryID'");

$IsModernFiveStar = (!empty($proOptions->IsModernFiveStar)) ? true : false;

if(!empty($_POST['cg_remove_votes'])){
    include('remove-votes-and-correct-gallery.php');
    $imageData = $wpdb->get_row("SELECT * FROM $tablename WHERE id = '$imageId'"); // weil sich erneuert hat hier nochmal einfügen
}

$multipleRatingQueryString = '';

if($AllowRatingMax){
    $multipleRatingQueryString = " OR (Rating>=1 AND Rating<=$AllowRatingMax)";
}
$votingData = $wpdb->get_results("SELECT * FROM $tablename_ip WHERE pid = '$imageId' AND (RatingS = 1$multipleRatingQueryString)  ORDER BY id DESC LIMIT $start, 50");

$votingDataLength = $wpdb->get_var("SELECT COUNT(*) FROM $tablename_ip WHERE pid = '$imageId' AND (RatingS = 1$multipleRatingQueryString)");

$upload_folder = wp_upload_dir();
$upload_folder_url = $upload_folder['baseurl']; // Pfad zum Bilderordner angeben

$wpUserIdsArray = array();

if(count($votingData)){
    foreach($votingData as $row){

        if(!empty($row->WpUserId)){
            $wpUserIdsArray[$row->WpUserId] = true;
        }

    }
}

$userIdsSelectString = '';

if(count($wpUserIdsArray)){

    foreach($wpUserIdsArray as $id => $bool){
        if(empty($userIdsSelectString)){
            $userIdsSelectString .= "ID = $id";
        }else{
            $userIdsSelectString .= " OR ID = $id";
        }
    }

    $wpUsersData = $wpdb->get_results("SELECT ID, user_login, user_email FROM $table_wp_users WHERE $userIdsSelectString ORDER BY ID ASC");

    foreach($wpUsersData as $row){
        $wpUserIdsArray[$row->ID] = array();
        $wpUserIdsArray[$row->ID]['user_login'] = $row->user_login;
        $wpUserIdsArray[$row->ID]['user_email'] = $row->user_email;
    }

}

// select google users
$googleUsersArray = [];

$selectGoogleUsersQuery = "SELECT DISTINCT WpUserId, Email FROM $tablename_google_users WHERE";

foreach ($wpUserIdsArray as $wpUserId => $wpUserData){
    $selectGoogleUsersQuery .= " WpUserId = $wpUserId OR ";
}

$selectGoogleUsersQuery = substr($selectGoogleUsersQuery,0,-3);
$googleUsers = $wpdb->get_results($selectGoogleUsersQuery);

foreach ($googleUsers as $googleUser){
    $googleUsersArray[$googleUser->WpUserId] = [];
    $googleUsersArray[$googleUser->WpUserId]['Email'] = $googleUser->Email;
}
// select google users --- END

$widthOriginalImg = $imageData->Width;
$heightOriginalImg = $imageData->Height;
$rThumb = $imageData->rThumb;
$WpUpload = $imageData->WpUpload;

if(!empty($imageData->MultipleFiles) && $imageData->MultipleFiles!='""'){
    $MultipleFilesUnserialized = unserialize($imageData->MultipleFiles);
    if(!empty($MultipleFilesUnserialized)){
        //check for sure if really exists and unserialize went right, because might happen that "" was in database from earlier versions
        foreach($MultipleFilesUnserialized as $order => $MultipleFile){
            if($order==1 && empty($MultipleFile['isRealIdSource'])){
                $ImgType = (!empty($MultipleFile['ImgType'])) ? $MultipleFile['ImgType'] : 0;
                $widthOriginalImg = (!empty($MultipleFile['Width'])) ? $MultipleFile['Width'] : 0;
                $heightOriginalImg = (!empty($MultipleFile['Height'])) ? $MultipleFile['Height'] : 0;
                $rThumb = (!empty($MultipleFile['rThumb'])) ? $MultipleFile['rThumb'] : '';
                $WpUpload = (!empty($MultipleFile['WpUpload'])) ? $MultipleFile['WpUpload'] : 0;
                break;
            }
        }
    }
}

$status = ($imageData->Active>0) ? 'activated' : 'deactivated';

if($ImgType=='con'){
    $image_url = '';
    $post_title = '';
    $post_description = '';
    $post_excerpt = '';
    $post_type = '';
    $wp_image_id = '';
    $sourceOriginalImgShow = '';
}else{
    $wp_image_info = $wpdb->get_row("SELECT * FROM $table_posts WHERE ID = '$WpUpload'");
    $image_url = $wp_image_info->guid;
    $post_title = $wp_image_info->post_title;
    $post_description = $wp_image_info->post_content;
    $post_excerpt = $wp_image_info->post_excerpt;
    $post_type = $wp_image_info->post_mime_type;
    $wp_image_id = $wp_image_info->ID;
    $sourceOriginalImgShow = $image_url;
}

if(cg_is_is_image($ImgType)){

$imageThumb = wp_get_attachment_image_src($WpUpload, 'large');
$imageThumb = $imageThumb[0];

$WidthThumb = 300;
$HeightThumb = 200;

// Ermittlung der Höhe nach Skalierung. Falls unter der eingestellten Höhe, dann nächstgrößeres Bild nehmen.
$heightScaledThumb = $WidthThumb*$heightOriginalImg/$widthOriginalImg;


// Falls unter der eingestellten Höhe, dann größeres Bild nehmen (normales Bild oder panorama Bild, kein Vertikalbild)
if ($heightScaledThumb <= $HeightThumb) {

    $imageThumb = wp_get_attachment_image_src($WpUpload, 'large');
    $imageThumb = $imageThumb[0];

    // Bestimmung von Breite des Bildes
    $WidthThumbPic = $HeightThumb*$widthOriginalImg/$heightOriginalImg;

    // Bestimmung wie viel links und rechts abgeschnitten werden soll
    $paddingLeftRight = ($WidthThumbPic-$WidthThumb)/2;
    $paddingLeftRight = $paddingLeftRight.'px';

    $padding = "left: -$paddingLeftRight;right: -$paddingLeftRight";

    $WidthThumbPic = $WidthThumbPic.'px';


}

// Falls über der eingestellten Höhe, dann kleineres Bild nehmen (kein Vertikalbild)

if ($heightScaledThumb > $HeightThumb) {

    $imageThumb = wp_get_attachment_image_src($WpUpload, 'large');
    $imageThumb = $imageThumb[0];

    // Bestimmung von Breite des Bildes
    $WidthThumbPic = $WidthThumb.'px';

    // Bestimmung wie viel oben und unten abgeschnitten werden soll
    $heightImageThumb = $WidthThumb*$heightOriginalImg/$widthOriginalImg;
    $paddingTopBottom = ($heightImageThumb-$HeightThumb)/2;
    $paddingTopBottom = $paddingTopBottom.'px';

    $padding = "top: -$paddingTopBottom;bottom: -$paddingTopBottom";

}
}


// Bild wird mittig und passend zum Div angezeigt	--------  ENDE

// Notwendig um sp�ter die star Icons anzuzeigen
$iconsURL = plugins_url().'/'.cg_get_version().'/v10/v10-css';

$starOn = $iconsURL.'/star_48_reduced.png';
$starOff = $iconsURL.'/star_off_48_reduced.png';

$starCountS = ($imageData->CountS>0) ? $starOn : $starOff;

//$uploadTime = date('d-M-Y H:i', $imageData->Timestamp);
$uploadTime = cg_get_time_based_on_wp_timezone_conf($imageData->Timestamp,'d-M-Y H:i:s');

if ($imageData->CountR!=0){
    $averageStars = $imageData->Rating/$imageData->CountR;
    $averageStarsRounded = round($averageStars,0);
}
else{$countRtotalCheck=0; $averageStarsRounded = 0;}


if($averageStarsRounded>=1){$star1 = $starOn;}
else{$star1 = $starOff;}
if($averageStarsRounded>=2){$star2 = $starOn;}
else{$star2 = $starOff;}
if($averageStarsRounded>=3){$star3 = $starOn;}
else{$star3 = $starOff;}
if($averageStarsRounded>=4){$star4 = $starOn;}
else{$star4 = $starOff;}
if($averageStarsRounded>=5){$star5 = $starOn;}
else{$star5 = $starOff;}

if(empty($imageData->CountS)){$imageData->CountS = 0;}
if(empty($imageData->CountR)){$imageData->CountR = 0;}

if(!empty($imageData->IP)){
    $userIP = $imageData->IP;
}else{
    $userIP = 'User IP when uploading will be tracked since plugin version 10.9.3.7';
}

if(!empty($imageData->CookieId)){
    $CookieId = $imageData->CookieId;
}else{
    $CookieId = '';
}

echo "<div id='cgVotes'>";

echo '<div id=\'cgVotesExport\'>
<form method="POST" action="?page='.cg_get_version().'/index.php&cg_picture_id='.$imageId.'&cg_export_votes=true">
<input type="hidden" name="cg_export_votes" value="true">
<input type="hidden" name="cg_picture_id" id="cg_picture_id" value="'.$imageId.'">
<input type="hidden" name="cg_option_id" value="'.$GalleryID.'">
<input class="cg_backend_button_gallery_action" type="submit" value="Export votes" style="margin: 0 auto;"></form>
</div>';

echo "<form  data-cg-submit-message='Votes corrected'  action='?page=".cg_get_version()."/index.php&show_votes=true&show_votes=true&image_id=$imageId&option_id=$GalleryID' method=\"post\" class=\"cg_load_backend_submit\">";
echo '<input type="hidden" name="cg_remove_votes" value="true">';


if (!empty($_POST['cg_remove_votes'])) {
    echo "<div>";
    echo "<p style='text-align: center;margin-bottom:25px;margin-top:20px;font-weight:bold;font-size:20px;line-height:24px;'>Votes corrected</p>";
    echo "</div>";
}


echo "<div id='cgVotesImage'>";

    echo "<div id='cgVotesImageVisual'>";

        if(cg_is_alternative_file_type_file($ImgType)){
                echo '<a href="'.$sourceOriginalImgShow.'" target="_blank" title="Show full size">';
                    echo '<div id="cgVotesImageVisualContent">';
                        echo '<div class="cg-votes-image-visual-content-file-type-'.$ImgType.'">';
                        echo "</div>";
                    echo "</div>";
                echo '</a>';
        }else if(cg_is_alternative_file_type_video($ImgType)){
            echo '<a href="'.$sourceOriginalImgShow.'" target="_blank" title="Show file" alt="Show file">';
                echo '<video width="300" height="200"  >';
                    echo '<source src="'.$sourceOriginalImgShow.'" type="video/mp4">';
                    echo '<source src="'.$sourceOriginalImgShow.'" type="video/'.$ImgType.'">';
                echo '</video>';
            echo '</a>';
        }else if(cg_is_is_image($ImgType)){
            echo '<div id="cgVotesImageVisualContent">';
                echo '<a href="'.$sourceOriginalImgShow.'" target="_blank" title="Show full size"><img class="cg'.$rThumb.'degree" src="'.$imageThumb.'" style="'.$padding.';position: absolute !important;max-width:none !important;" width="'.$WidthThumbPic.'"></a>';
            echo "</div>";
        }else{// then must be simple contact form entry
            echo '<div id="cgVotesImageVisualContent">';
            echo "</div>";
        }

        echo '<div id="cgVotesImageVisualId">';

        if($ImgType=='con'){
            echo "<strong>Contact form entry ID:</strong> $imageData->id";
        }else{
            echo "<strong>Entry ID:</strong> $imageData->id";
        }
        echo "<br><strong>status:</strong> $status";
        echo "<br>";
        echo "<strong>IP:</strong><span style='font-size:12px;'>$userIP</span>";
        if($proOptions->RegUserUploadOnly==2){
            echo "<br>";
            echo "<strong>Cookie ID:</strong><br><span style='font-size:12px;'>$CookieId</span>";
        }

        if($WpUserId>0){

            echo "<br>";
            echo "<div class='cg_backend_info_user_link_container'>";
            echo "<span style='display:table;'><strong>Added by:</strong></span><a style=\"display:flex;margin-top:5px;\" class=\"cg_image_action_href cg_load_backend_link\" href='?page=".cg_get_version()."/index.php&users_management=true&option_id=$GalleryID&wp_user_id=".$WpUserId."'><span class=\"cg_image_action_span\" >".$user_login."</span></a>";
            echo '</div>';

        }

        echo '</div>';
    echo "</div>";

    echo "<div id='cgVotesImageInfo'>";
        if($ImgType!='con'){
            echo "<div class='cg-votes-image-info-header'>File name (original WordPress title):</div>";
            echo "<div class='cg-votes-image-info-content'>$post_title</div>";
        }
        echo "<div class='cg-votes-image-info-header'>Entry time:</div>";
        echo "<div class='cg-votes-image-info-content'><span id='cgVotesUploadTimeTimestamp'>$imageData->Timestamp</span>$uploadTime</div>";
        echo "<div class='cg-votes-image-info-header'>Count one star voting:</div>";
        echo "<div class='cg-votes-image-info-content'>";

        if($imageData->CountS>=1){
            echo "<div class='cg-votes-image-info-content-rating-average-stars cg_backend_star_on'>";
            echo "</div>";
        }else{
            echo "<div class='cg-votes-image-info-content-rating-average-stars cg_backend_star_off'>";
            echo "</div>";
        }

            echo "<div class='cg-votes-image-info-content-rating-count'>";

                $countStoShow = $wpdb->get_var( $wpdb->prepare(
                    "
                                                        SELECT COUNT(*) AS NumberOfRows
                                                        FROM $tablename_ip
                                                        WHERE GalleryID = %d AND RatingS = %d AND pid = %d
                                                    ",
                    $GalleryID,1,$imageId
                ) );

            echo $countStoShow;

            echo "</div>";
        echo "</div>";

        echo "<div class='cg-votes-image-info-five-star'>";

            echo "<div class='cg-votes-image-info-five-star-content'>";
                echo "<div class='cg-votes-image-info-header'>Cummulated multiple stars voting:</div>";
                if($AllowRating==2 OR $AllowRating==0){
                    echo "<div class='cg-votes-image-info-content'>Only visible if multiple stars voting is activated</div>";
                }else{

                    $RatingOverview = $wpdb->get_results( $wpdb->prepare(
                        "
                                        SELECT Rating, COUNT(*) AS NumberOfRows
                                        FROM $tablename_ip
                                        WHERE GalleryID = %d AND Rating >= %d AND Rating <= %d AND pid = %d 
                                        GROUP By Rating
                                    ",
                        $GalleryID,1,$AllowRatingMax, $imageId
                    ) );

                    $RatingOverviewArray = [];

                    if(count($RatingOverview)){
                        foreach ($RatingOverview as $item) {
                            $RatingOverviewArray[$item->Rating] = $item->NumberOfRows;
                        }
                    }

                    $ratingCummulated = 0;

                    for($iR=1;$iR<=$AllowRating-10;$iR++){
                        if(!empty($RatingOverviewArray[$iR])){
                            ${'countR'.$iR} = $RatingOverviewArray[$iR];
                        }else{
                            ${'countR'.$iR} = 0;
                        }
                        ${'ratingCummulated'.$iR} = ${'countR'.$iR}*$iR;
                        $ratingCummulated = $ratingCummulated + ${'ratingCummulated'.$iR};
                    }

                    echo "<div class='cg-votes-image-info-content' style='align-items: normal;'>";

                        if($ratingCummulated>=1){
                            echo "<div class='cg-votes-image-info-content-rating-average-stars cg_backend_star_on'>";
                            echo "</div>";
                        }else{
                            echo "<div class='cg-votes-image-info-content-rating-average-stars cg_backend_star_off'>";
                            echo "</div>";
                        }

                        echo "<div class='cg-votes-image-info-content-rating-count' style='margin-right: 7px;'>$ratingCummulated => </div>";

                        echo "<div>";

                            for($iR=$AllowRating-10;$iR>=1;$iR--){

                                // CONTINUE HERE!!!!
                                echo '<div class="cg_stars_overview">';

                                    echo "<div class='cg_backend_star_number'>".$iR."</div>";
                                    echo "<div class='cg_backend_star cg_backend_five_star cg_backend_star_on'></div>";
                                    echo "<div class='cg_stars_overview_countR cg_rating_value_countR".$iR."' >".${'countR'.$iR}."</div>";
                                    echo "<div class='cg_stars_overview_equal' > = </div>";
                                    echo "<div class='cg_stars_overview_rating_cummulated' > ".${'ratingCummulated'.$iR}." </div>";

                                echo "</div>";

                            }

                        echo "</div>";

                    echo "</div>";

                }
            echo "</div>";

echo "</div>";
        if($generalOptions->FbLike==1){
            echo "<div class='cg-votes-image-info-fblike'>";
                echo "<div class='cg-votes-image-info-header'>Facebook Like voting:</div>";
                echo "<div class='cg-votes-image-info-content'>";

                echo "Facebook Like Button can be only shown in frontend because of WordPress security features";

/*                if(file_exists($upload_folder["basedir"]."/contest-gallery/gallery-id-".$gid."/".$imageData->Timestamp."_".$imageData->NamePic."413.html")){
                    $fbSiteUrl = $upload_folder_url."/contest-gallery/gallery-id-".$gid."/".$imageData->Timestamp."_".$imageData->NamePic."413.html";
                    echo "<div id='cgFacebookGalleryDiv".$gid."' class='cg_gallery_facebook_div' >";
                    echo "<iframe src='".$fbSiteUrl."'  scrolling='no' class='cg_fb_like_iframe_slider_order' id='cg_fb_like_iframe_slider".$imageId."'  name='cg_fb_like_iframe_slider".$imageId."'></iframe>";
                    echo "</div>";
                }else{
                    echo "This image has to be activated at least one time to see Facebook Like voting";
                }*/

            echo "</div>";
            echo "</div>";
        }

/*        echo "<div id='cgVotesNote'>";
        echo "<p>NOTE: Vote date will be tracked only since plugin version 10.3.0</p>";
        echo "</div>";*/
    echo "</div>";

echo "</div>";

if($votingDataLength>50){
    echo "<div class='cg-votes-steps-container'>";
    $i = -1;
    for($stepStart=0;$stepStart<$votingDataLength;$stepStart = $stepStart+50){

            $check = $stepStart+50;
            if($votingDataLength<$check){// then last step
                $stepEnd = $votingDataLength;
                $stepStartToStart = $stepStart+1;
            }else{
                $stepEnd = $stepStart+($votingDataLength-50*$i-($votingDataLength-($stepStart)));
                $stepStartToStart = $stepStart+1;
            }

            if($start==$stepStart){
                $checked = 'cg-votes-step-checked';
            }else{
                $checked = '';
            }

            echo "<div class='cg-votes-step $checked'>";
            echo "[ <a class='cg-votes-step-link' href='?page=".cg_get_version()."/index.php&image_id=$imageId&show_votes=true&option_id=$GalleryID&start=$stepStart&end=$stepEnd'>$stepStartToStart-$stepEnd</a> ]";
            echo "</div>";

        $i++;


    }
    echo "</div>";
}

    if($votingDataLength>=1){
        echo "<div id='cgVotesContent'>";

        $multipleStarsNotActivatedMessage = '';

        if($AllowRating==2 OR $AllowRating==0){
            $multipleStarsNotActivatedMessage = "<br>-<br>not<br>activated";
        }

        // Header
        echo "<div id='cgVotesHeaderContainer'>";
            echo "<div class='cg-votes-header'>User recognition Method</div>";
            echo "<div class='cg-votes-header'>vote id</div>";
            echo "<div class='cg-votes-header'>IP</div>";
            echo "<div class='cg-votes-header'>Cookie id</div>";
            echo "<div class='cg-votes-header'>Google email</div>";
            echo "<div class='cg-votes-header'>Category of file as voting was done<br>id (name)</div>";
            echo "<div class='cg-votes-header'>Rating<br>one star</div>";
            echo "<div class='cg-votes-header'>Rating<br>multiple<br>stars$multipleStarsNotActivatedMessage</div>";
            echo "<div class='cg-votes-header'>WordPress<br>user id</div>";
            echo "<div class='cg-votes-header'>WordPress<br>user name</div>";
            echo "<div class='cg-votes-header'>WordPress<br>user email</div>";
            echo "<div class='cg-votes-header'>Vote date</div>";
            echo "<div class='cg-votes-header'>Select all <br/><input type='checkbox' id='cgVotesSelectAll'><br/><br/>Remove vote</div>";
        echo "</div>";

        // Rows
        foreach ($votingData as $row) {
            echo "<div class='cg-votes-row-container'>";
                echo "<div class='cg-votes-row'>$row->OptionSet</div>";
                echo "<div class='cg-votes-row'>$row->id</div>";
                echo "<div class='cg-votes-row'>$row->IP</div>";
                echo "<div class='cg-votes-row cg-votes-row-cookie-id-parent' data-title='$row->CookieId'><div class='cg-votes-row-cookie-id'>$row->CookieId</div></div>";

            $GoogleEmail = '';

            if(!empty($row->WpUserId)){
                if(!empty($googleUsersArray[$row->WpUserId])){
                    $GoogleEmail = $googleUsersArray[$row->WpUserId]['Email'];
                }
            }

            echo "<div class='cg-votes-row'>$GoogleEmail</div>";
            // Categories were available in that time when CategoriesOn not empty
            if (!empty($row->CategoriesOn)){
                $category = (!empty($categoriesUidsNames[$row->Category])) ? $row->Category.' ('.$categoriesUidsNames[$row->Category].')' : $row->Category.' (deleted category)';
            }else{
                $category = '';
            }
                echo "<div class='cg-votes-row'>$category</div>";
                $row->RatingS = (empty($row->RatingS)) ? '&nbsp;' : $row->RatingS;
                echo "<div class='cg-votes-row'>$row->RatingS</div>";
                $row->Rating = (empty($row->Rating)) ? '&nbsp;' : $row->Rating;
                echo "<div class='cg-votes-row'>$row->Rating</div>";
                echo "<div class='cg-votes-row'>$row->WpUserId</div>";
                $username = (!empty($row->WpUserId)) ? $wpUserIdsArray[$row->WpUserId]['user_login'] : "";
                $useremail = (!empty($row->WpUserId)) ? $wpUserIdsArray[$row->WpUserId]['user_email'] : "";
                echo "<div class='cg-votes-row'>$username</div>";
                echo "<div class='cg-votes-row'>$useremail</div>";
                if(empty($row->VoteDate)){$row->VoteDate="&nbsp";};
            echo "<div class='cg-votes-row'>".cg_get_time_based_on_wp_timezone_conf($row->Tstamp,'d-M-Y H:i:s')."</div>";
                $ratingVariant = ($row->RatingS>0) ? 'RatingS' : 'Rating';
                $ratingHeight = ($row->RatingS>0) ? $row->RatingS : $row->Rating;
                echo "<div class='cg-votes-row'><input type='checkbox' class='cg-votes-remove-vote-checkbox' name='ipId[$row->id][$ratingVariant]' value='$ratingHeight'></div>";
            echo "</div>";
        }
        echo "</div>";
    }else{

        echo "<div id='cgVotesContent'>";
            echo "<br>";
        echo "<p>This entry has no votes</p>";
        echo "</div>";

    }

if($votingDataLength>=1) {
    echo "<div id='cgOptionsSaveButtonContainer'><input class='cg_backend_button_gallery_action' type=\"submit\" value=\"Remove and correct votes\" id='cgOptionsSaveButton'></div>";
    echo "</form>";
}


echo "</div>";


