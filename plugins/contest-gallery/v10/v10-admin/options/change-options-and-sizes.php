<?php

check_admin_referer('cg_admin');


$id = absint($_GET['option_id']);


//echo "<br>id: $id<br>";

global $wpdb;

$tablename = $wpdb->prefix . "contest_gal1ery";
$tablenameOptions = $wpdb->prefix . "contest_gal1ery_options";
$tablename_options_visual = $wpdb->prefix . "contest_gal1ery_options_visual";
//$tablename_contact_options = $wpdb->prefix . "contest_gal1ery_contact_options";
$tablenameOptionsInput = $wpdb->prefix . "contest_gal1ery_options_input";
$tablename_ip = $wpdb->prefix . "contest_gal1ery_ip";
$tablename_mail_admin = $wpdb->prefix . "contest_gal1ery_mail_admin";
$tablename_mail_user_upload = $wpdb->prefix . "contest_gal1ery_mail_user_upload";
$tablename_mail_user_comment = $wpdb->prefix . "contest_gal1ery_mail_user_comment";
$tablename_mail_user_vote = $wpdb->prefix . "contest_gal1ery_mail_user_vote";
$tablenameemail = $wpdb->prefix . "contest_gal1ery_mail";
$tablename_f_input = $wpdb->prefix . "contest_gal1ery_f_input";
$tablename_pro_options = $wpdb->prefix . "contest_gal1ery_pro_options";
//$tablename_mail_gallery = $wpdb->prefix . "contest_gal1ery_mail_gallery";
$tablename_mail_confirmation = $wpdb->prefix . "contest_gal1ery_mail_confirmation";
$tablename_comments_notification_options = $wpdb->prefix . "contest_gal1ery_comments_notification_options";
$tablenameGoogleOptions = $wpdb->prefix . "contest_gal1ery_google_options";
$tablename_registry_and_login_options = $wpdb->prefix . "contest_gal1ery_registry_and_login_options";

$optionsForGeneralIDsinceV14 = [];

$wp_upload_dir = wp_upload_dir();
$galleryUploadFolder = $wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-' . $id . '';

if (!is_dir($galleryUploadFolder)) {
    mkdir($galleryUploadFolder, 0755, true);
}

if(!empty($_POST['CgEntriesOwnSlugNameChanged'])){
    $wp_upload_dir = wp_upload_dir();
    $slugNameFilePath = $wp_upload_dir['basedir'].'/contest-gallery/gallery-general/post-type-slug-name-do-not-edit-or-remove.txt';
    if (is_multisite()) {
        $CgEntriesOwnSlugNameOption = cg_get_blog_option(get_current_blog_id(),'CgEntriesOwnSlugName');
    }else{
        $CgEntriesOwnSlugNameOption = get_option('CgEntriesOwnSlugName');
    }
    if(!empty($_POST['CgEntriesOwnSlugName'])){
        $CgEntriesOwnSlugNameValue = trim(sanitize_text_field($_POST['CgEntriesOwnSlugName']));
        file_put_contents($slugNameFilePath,$CgEntriesOwnSlugNameValue);
        if($CgEntriesOwnSlugNameOption===false){
            if (is_multisite()) {
                cg_add_blog_option(get_current_blog_id(),'CgEntriesOwnSlugName',$CgEntriesOwnSlugNameValue);
            }else{
                add_option('CgEntriesOwnSlugName',$CgEntriesOwnSlugNameValue);
            }
        }else{
            if (is_multisite()) {
                cg_update_blog_option(get_current_blog_id(),'CgEntriesOwnSlugName',$CgEntriesOwnSlugNameValue);
            }else{
                update_option('CgEntriesOwnSlugName',$CgEntriesOwnSlugNameValue);
            }
        }
    }else{// if empty
        if(file_exists($slugNameFilePath)){
            unlink($slugNameFilePath);
            }
        if($CgEntriesOwnSlugNameOption!==false){
            if (is_multisite()) {
                cg_delete_blog_option(get_current_blog_id(),'CgEntriesOwnSlugName');
            }else{
                delete_option('CgEntriesOwnSlugName');
            }
        }
    }
    $wp_upload_dir = wp_upload_dir();
    $rewriteRulesChangedFilePath = $wp_upload_dir['basedir'].'/contest-gallery/gallery-general/rewrite-rules-changed-do-not-edit-or-remove.txt';
    file_put_contents($rewriteRulesChangedFilePath,'changed');// register_post_type has to be executed in register_post_type.php, which will be executed on init, after register_post_type()
}


$isResetVotes = false;
$isResetVotesViaManipulationOneStar = false;
$isResetVotesViaManipulationMultipleStars = false;

if (!empty($_GET['reset_votes'])) {
    $isResetVotes = true;
    $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM $tablename_ip WHERE GalleryID = %d AND Rating >= %d",
            $id, 1
        )
    );

    $wpdb->update(
        "$tablename",
        array(
                'CountR' => 0, 'Rating' => 0, 'CountR1' => 0, 'CountR2' => 0, 'CountR3' => 0, 'CountR4' => 0, 'CountR5' => 0,
                'CountR6' => 0, 'CountR7' => 0, 'CountR8' => 0, 'CountR9' => 0, 'CountR10' => 0
        ),
        array('GalleryID' => $id),
        array(
                '%d', '%d', '%d', '%d', '%d', '%d', '%d',
                '%d', '%d', '%d', '%d', '%d'
        ),
        array('%d')
    );

    // image data has to be actualized before later bottom cg_actualize_all_images_data_sort_values_file will be executed
    $imageDataJsonFiles = glob($wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$id.'/json/image-data/*.json');

    foreach ($imageDataJsonFiles as $jsonFile) {

        $fp = fopen($jsonFile, 'r');
        $imageDataArray = json_decode(fread($fp, filesize($jsonFile)),true);
        fclose($fp);

        // get image id
        $stringArray= explode('/image-data-',$jsonFile);
        $subString = end($stringArray);
        $imageId = substr($subString,0, -5);

        if(empty($imageDataArray)){
            $imageDataArray = [];
        }

        $imageDataArray['CountR'] = 0;
        $imageDataArray['Rating'] = 0;
        $imageDataArray['CountR1'] = 0;
        $imageDataArray['CountR2'] = 0;
        $imageDataArray['CountR3'] = 0;
        $imageDataArray['CountR4'] = 0;
        $imageDataArray['CountR5'] = 0;
        $imageDataArray['CountR6'] = 0;
        $imageDataArray['CountR7'] = 0;
        $imageDataArray['CountR8'] = 0;
        $imageDataArray['CountR9'] = 0;
        $imageDataArray['CountR10'] = 0;

        // set rating data
        $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$id.'/json/image-data/image-data-'.$imageId.'.json';
        $fp = fopen($jsonFile, 'w');
        fwrite($fp, json_encode($imageDataArray));
        fclose($fp);

    }

    ?>
    <script>
        alert('All multiple stars votes were completely deleted.\nFrontend needs to be reloaded.\nFrontend changes might require 30 seconds.');
    </script>

    <?php

}

if (!empty($_GET['reset_votes2'])) {
    $isResetVotes = true;
    $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM $tablename_ip WHERE GalleryID = %d AND RatingS = %d",
            $id, 1
        )
    );

    $wpdb->update(
        "$tablename",
        array('CountS' => 0),
        array('GalleryID' => $id),
        array('%d'),
        array('%d')
    );

    // image data has to be actualized before later bottom cg_actualize_all_images_data_sort_values_file will be executed
    $imageDataJsonFiles = glob($wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$id.'/json/image-data/*.json');

    foreach ($imageDataJsonFiles as $jsonFile) {

        $fp = fopen($jsonFile, 'r');
        $imageDataArray = json_decode(fread($fp, filesize($jsonFile)),true);
        fclose($fp);

        // get image id
        $stringArray= explode('/image-data-',$jsonFile);
        $subString = end($stringArray);
        $imageId = substr($subString,0, -5);

        if(empty($imageDataArray)){
            $imageDataArray = [];
        }

        $imageDataArray['CountS'] = 0;

        // set rating data
        $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$id.'/json/image-data/image-data-'.$imageId.'.json';
        $fp = fopen($jsonFile, 'w');
        fwrite($fp, json_encode($imageDataArray));
        fclose($fp);

    }

    ?>
    <script>
        alert('All 1 star votes were completely deleted.\nFrontend needs to be reloaded.\nFrontend changes might require 30 seconds.');
    </script>

    <?php

}

if (!empty($_GET['reset_admin_votes'])) {
    $isResetVotes = true;
    $isResetVotesViaManipulationMultipleStars = true;
    $wpdb->update(
        "$tablename",
        array('addCountR1' => 0, 'addCountR2' => 0, 'addCountR3' => 0, 'addCountR4' => 0, 'addCountR5' => 0, 'addCountR6' => 0, 'addCountR7' => 0, 'addCountR8' => 0, 'addCountR9' => 0, 'addCountR10' => 0),
        array('GalleryID' => $id),
        array('%d','%d','%d','%d','%d','%d','%d','%d','%d','%d'),
        array('%d')
    );

    // image data has to be actualized before later bottom cg_actualize_all_images_data_sort_values_file will be executed
    $imageDataJsonFiles = glob($wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$id.'/json/image-data/*.json');

    foreach ($imageDataJsonFiles as $jsonFile) {

        $fp = fopen($jsonFile, 'r');
        $imageDataArray = json_decode(fread($fp, filesize($jsonFile)),true);
        fclose($fp);

        // get image id
        $stringArray= explode('/image-data-',$jsonFile);
        $subString = end($stringArray);
        $imageId = substr($subString,0, -5);

        if(empty($imageDataArray)){
            $imageDataArray = [];
        }

        $imageDataArray['addCountR1'] = 0;
        $imageDataArray['addCountR2'] = 0;
        $imageDataArray['addCountR3'] = 0;
        $imageDataArray['addCountR4'] = 0;
        $imageDataArray['addCountR5'] = 0;
        $imageDataArray['addCountR6'] = 0;
        $imageDataArray['addCountR7'] = 0;
        $imageDataArray['addCountR8'] = 0;
        $imageDataArray['addCountR9'] = 0;
        $imageDataArray['addCountR10'] = 0;

        // set rating data
        $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$id.'/json/image-data/image-data-'.$imageId.'.json';
        $fp = fopen($jsonFile, 'w');
        fwrite($fp, json_encode($imageDataArray));
        fclose($fp);

    }

    ?>
    <script>
        alert('All multiple stars by administrator manually (via manipulation) added votes were deleted.\nFrontend needs to be reloaded.\nFrontend changes might require 30 seconds.');
    </script>

    <?php

}

if (!empty($_GET['reset_admin_votes2'])) {

    $isResetVotes = true;
    $isResetVotesViaManipulationOneStar = true;

    $wpdb->update(
        "$tablename",
        array('addCountS' => 0),
        array('GalleryID' => $id),
        array('%d'),
        array('%d')
    );

    // image data has to be actualized before later bottom cg_actualize_all_images_data_sort_values_file will be executed
    $imageDataJsonFiles = glob($wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$id.'/json/image-data/*.json');

    foreach ($imageDataJsonFiles as $jsonFile) {

        $fp = fopen($jsonFile, 'r');
        $imageDataArray = json_decode(fread($fp, filesize($jsonFile)),true);
        fclose($fp);

        // get image id
        $stringArray= explode('/image-data-',$jsonFile);
        $subString = end($stringArray);
        $imageId = substr($subString,0, -5);

        if(empty($imageDataArray)){
            $imageDataArray = [];
        }

        $imageDataArray['addCountS'] = 0;

        // set rating data
        $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$id.'/json/image-data/image-data-'.$imageId.'.json';
        $fp = fopen($jsonFile, 'w');
        fwrite($fp, json_encode($imageDataArray));
        fclose($fp);

    }

    ?>
    <script>
        alert('All 1 star by administrator manually (via manipulation) added votes were deleted.\nFrontend needs to be reloaded.\nFrontend changes might require 30 seconds.');
    </script>

    <?php

}

if($isResetVotes){
    $IsModernFiveStar = $wpdb->get_var($wpdb->prepare( "SELECT IsModernFiveStar FROM $tablename_pro_options WHERE GalleryID = %d",[$id]));

    $IsModernFiveStarBool = (!empty($IsModernFiveStar)) ? true : false;
    cg_actualize_all_images_data_sort_values_file($id,true,$IsModernFiveStarBool,$isResetVotesViaManipulationOneStar,$isResetVotesViaManipulationMultipleStars);
}

// Values which should not be saved if not sended
$unsavingValues = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tablenameOptions WHERE id = %d",[$id]));


$PicsPerSite = $unsavingValues->PicsPerSite;
$ScaleOnly = $unsavingValues->ScaleOnly;
$ScaleAndCut = $unsavingValues->ScaleAndCut;
$WidthThumb = $unsavingValues->WidthThumb;
$HeightThumb = $unsavingValues->HeightThumb;
$WidthGallery = $unsavingValues->WidthGallery;
$HeightGallery = $unsavingValues->HeightGallery;
$PicsInRow = $unsavingValues->PicsInRow;
$HeightLookHeight = $unsavingValues->HeightLookHeight;
$CheckCookieAlertMessage = $unsavingValues->CheckCookieAlertMessage;
$MaxResJPG = $unsavingValues->MaxResJPG;
$MaxResPNG = $unsavingValues->MaxResPNG;
$MaxResGIF = $unsavingValues->MaxResGIF;
$HeightGallery = $unsavingValues->HeightGallery;
$ContestEndTime = $unsavingValues->ContestEndTime;
$ContestStartTime = $unsavingValues->ContestStartTime;
$AdjustThumbLook = $unsavingValues->AdjustThumbLook;
$DistancePics = $unsavingValues->DistancePics;
$DistancePicsV = $unsavingValues->DistancePicsV;
$HideInfo = $unsavingValues->HideInfo;
$FbLikeGoToGalleryLink = $unsavingValues->FbLikeGoToGalleryLink;
$FullSize = $unsavingValues->FullSize;
$FullSizeGallery = $unsavingValues->FullSizeGallery;
$FullSizeSlideOutStart = $unsavingValues->FullSizeSlideOutStart;
$IpBlock = $unsavingValues->IpBlock;
$dbVersion = floatval($unsavingValues->Version);// IMPORTANT! Do not remove, required for json-options.php!!!
$VersionForScripts = $unsavingValues->Version;// new logic since 21.0.0
$VersionDecimal = $unsavingValues->VersionDecimal;// new logic since 21.0.0
$WpPageParent = $unsavingValues->WpPageParent;
$WpPageParentUser = $unsavingValues->WpPageParentUser;
$WpPageParentNoVoting = $unsavingValues->WpPageParentNoVoting;
$WpPageParentWinner = $unsavingValues->WpPageParentWinner;

if (!empty($_POST['changeSize'])) {

    if (!$cgProVersion) {

        unset($_POST['ContestEnd']); // bool $tablenameOptions $jsonOptions['general']['ContestEnd']
        unset($_POST['ContestStart']); // bool $tablenameOptions $jsonOptions['general']['ContestStart']
        unset($_POST['checkLogin']); // bool $tablenameOptions $jsonOptions['general']['CheckLogin']
        unset($_POST['HideUntilVote']); // bool $tablenameOptions $jsonOptions['general']['HideUntilVote']
        unset($_POST['VotesPerUser']);// int $tablenameOptions $jsonOptions['general']['VotesPerUser']
        unset($_POST['VotesPerCategory']);// int $tablename_pro_options $jsonOptions['pro']['VotesPerCategory']
        unset($_POST['ShowOnlyUsersVotes']);// bool $tablenameOptions $jsonOptions['general']['ShowOnlyUsersVotes']
        unset($_POST['RegUserMaxUpload']);// int $tablename_pro_options $jsonOptions['pro']['RegUserMaxUpload']
        unset($_POST['RegUserGalleryOnly']);// bool $tablename_pro_options $jsonOptions['pro']['RegUserGalleryOnly']
        unset($_POST['InformAdmin']);// bool $tablenameOptions $jsonOptions['general']['InformAdmin']
        unset($_POST['mConfirmSendConfirm']);// bool SendConfirm $tablename_mail_confirmation
        unset($_POST['InformUsers']);// bool Inform is column name $tablenameOptions $jsonOptions['general']['Inform']
        unset($_POST['ShowNickname']);// bool $tablename_pro_options $jsonOptions['pro']['ShowNickname']
        unset($_POST['ShowProfileImage']);// bool $tablename_pro_options $jsonOptions['pro']['ShowProfileImage']
        unset($_POST['ShowExifDateTimeOriginal']);// bool no table option $jsonOptions['general']['ShowExifDateTimeOriginal']
        unset($_POST['ShowExifDateTimeOriginalFormat']);// string - not reset
        unset($_POST['VoteMessageSuccessActive']);// bool $tablename_pro_options  $jsonOptions['pro']['VoteMessageSuccessActive']
        unset($_POST['VoteMessageSuccessText']);// string - not reset
        unset($_POST['VoteMessageWarningActive']);// bool VoteMessageWarningActive  $jsonOptions['pro']['VoteMessageWarningActive']
        unset($_POST['VoteMessageWarningText']);// string - not reset
        ###NORMAL###
        if($IpBlock!=1){
            unset($_POST['IpBlock']); // bool $tablenameOptions $jsonOptions['general']['IpBlock']
        }
        ###NORMAL-END###
        unset($_POST['MinusVote']);// bool  $tablename_pro_options $jsonOptions['pro']['MinusVote']
        unset($_POST['ForwardAfterLoginUrlCheck']);// bool $tablename_pro_options$jsonOptions['pro']['ForwardAfterLoginUrlCheck']
        unset($_POST['ForwardAfterLoginTextCheck']);// bool $tablename_pro_options $jsonOptions['pro']['ForwardAfterLoginUrlCheck']
        unset($_POST['VotesInTime']);// bool $tablename_pro_options $jsonOptions['pro']['VotesInTime']
        unset($_POST['HideRegFormAfterLogin']);// bool $tablename_pro_options $jsonOptions['pro']['HideRegFormAfterLogin']
        unset($_POST['HideRegFormAfterLoginShowTextInstead']);// bool $tablename_pro_options $jsonOptions['pro']['HideRegFormAfterLoginShowTextInstead']
        unset($_POST['FbLikeNoShare']);// added on 26.03.2020 // bool $tablename_pro_options no json options!
        unset($_POST['FbLikeOnlyShare']); // bool $tablename_pro_options no json options!
        unset($_POST['VoteNotOwnImage']);// added on 13.04.2020 // bool $tablename_pro_options $jsonOptions['pro']['VoteNotOwnImage']
        unset($_POST['RegMailOptional']);// added on 13.07.2020 // bool $tablename_pro_options $jsonOptions['pro']['RegMailOptional']
        unset($_POST['CustomImageName']);// added on 03.09.2020 // bool $tablename_pro_options $jsonOptions['pro']['CustomImageName']
        unset($_POST['CommNoteActive']);// added on 14.08.2021 // bool $tablename_pro_options $jsonOptions['pro']['CommNoteActive']
        unset($_POST['VotesPerUserAllVotesUsedHtmlMessage']);// added on 01.02.2020 // string - not reset
        unset($_POST['LostPasswordMailActive']);// added on 02.21.2021 // bool
        //unset($_POST['ActivateBulkUpload']);// do not remove!!!! Because user has to be able to deactivate manually!!!! added on 28.04.2021 //
        unset($_POST['AllowUploadPNG']);// added on 03.05.2022 // bool
        unset($_POST['AllowUploadGIF']);// added on 03.05.2022 // bool
        unset($_POST['ReviewComm']);// added on 11.09.2022 // bool
        unset($_POST['InformAdminAllowActivateDeactivate']);// added on 03.03.2023 // bool
        $_POST['CustomImageNamePath'] = '';// string - not reset
        $_POST['multiple-pics']['cg_gallery_user']['pro']['ShowNickname'] = 0; // bool $tablename_pro_options  $jsonOptions['pro']['ShowNickname']
        $_POST['multiple-pics']['cg_gallery_no_voting']['pro']['ShowNickname'] = 0; // bool
        $_POST['multiple-pics']['cg_gallery_winner']['pro']['ShowNickname'] = 0; // bool
        $_POST['multiple-pics']['cg_gallery_user']['pro']['ShowProfileImage'] = 0; // bool $tablename_pro_options  $jsonOptions['pro']['ShowProfileImage']
        $_POST['multiple-pics']['cg_gallery_no_voting']['pro']['ShowProfileImage'] = 0; // bool
        $_POST['multiple-pics']['cg_gallery_winner']['pro']['ShowProfileImage'] = 0; // bool
        $_POST['multiple-pics']['cg_gallery']['pro']['CheckLoginComment'] = 0;
        $_POST['multiple-pics']['cg_gallery_user']['pro']['CheckLoginComment'] = 0;
        $_POST['multiple-pics']['cg_gallery_no_voting']['pro']['CheckLoginComment'] = 0;
        $_POST['multiple-pics']['cg_gallery_winner']['pro']['CheckLoginComment'] = 0;
    }


// Values which should not be saved if not sended
    $unsavingValues = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tablename_pro_options WHERE GalleryID = %d",[$id]));

    $IsModernFiveStar = $unsavingValues->IsModernFiveStar;
    $ForwardAfterLoginUrl = $unsavingValues->ForwardAfterLoginUrl;
    $ForwardAfterLoginText = $unsavingValues->ForwardAfterLoginText;
    $RegUserUploadOnlyText = $unsavingValues->RegUserUploadOnlyText;
    $RegUserGalleryOnlyText = $unsavingValues->RegUserGalleryOnlyText;
    $GalleryUploadConfirmationText = $unsavingValues->GalleryUploadConfirmationText;
    $GalleryUploadTextBefore = $unsavingValues->GalleryUploadTextBefore;
    $GalleryUploadTextAfter = $unsavingValues->GalleryUploadTextAfter;
    $VotesInTimeQuantity = $unsavingValues->VotesInTimeQuantity;
    $VotesInTimeIntervalReadable = $unsavingValues->VotesInTimeIntervalReadable;
    $VotesInTimeIntervalSeconds = $unsavingValues->VotesInTimeIntervalSeconds;
    $VotesInTimeIntervalAlertMessage = $unsavingValues->VotesInTimeIntervalAlertMessage;
    $HideRegFormAfterLoginTextToShow = $unsavingValues->HideRegFormAfterLoginTextToShow;
    $RegUserMaxUpload = $unsavingValues->RegUserMaxUpload;
    $RegUserMaxUploadPerCategory = $unsavingValues->RegUserMaxUploadPerCategory;
    $FbLikeNoShareBefore = $unsavingValues->FbLikeNoShare;
    $FbLikeOnlyShareBefore = $unsavingValues->FbLikeOnlyShare;
    $CatWidget = $unsavingValues->CatWidget;
    $ShowOther = $unsavingValues->ShowOther;
    $ShowCatsUnchecked = $unsavingValues->ShowCatsUnchecked;
    $ShowCatsUnfolded = $unsavingValues->ShowCatsUnfolded;
    $VoteMessageSuccessText = $unsavingValues->VoteMessageSuccessText;
    $VoteMessageWarningText = $unsavingValues->VoteMessageWarningText;
    $AdditionalFilesCount = $unsavingValues->AdditionalFilesCount;

    $VoteMessageSuccessText = (isset($_POST['VoteMessageSuccessText'])) ? contest_gal1ery_htmlentities_and_preg_replace(trim($_POST['VoteMessageSuccessText'])) : $VoteMessageSuccessText;
    $VoteMessageWarningText = (isset($_POST['VoteMessageWarningText'])) ? contest_gal1ery_htmlentities_and_preg_replace(trim($_POST['VoteMessageWarningText'])) : $VoteMessageWarningText;

    $VoteMessageSuccessActive = (!empty($_POST['VoteMessageSuccessActive'])) ? '1' : '0';
    $VoteMessageWarningActive = (!empty($_POST['VoteMessageWarningActive'])) ? '1' : '0';

    $HideRegFormAfterLogin = (!empty($_POST['HideRegFormAfterLogin'])) ? '1' : '0';
    $HideRegFormAfterLoginShowTextInstead = (!empty($_POST['HideRegFormAfterLoginShowTextInstead'])) ? '1' : '0';
    $HideRegFormAfterLoginTextToShow = (isset($_POST['HideRegFormAfterLoginTextToShow'])) ? contest_gal1ery_htmlentities_and_preg_replace($_POST['HideRegFormAfterLoginTextToShow']) : $HideRegFormAfterLoginTextToShow;

    $CustomImageNamePath = $unsavingValues->CustomImageNamePath;

    $CustomImageName = (!empty($_POST['CustomImageName'])) ? 1 : 0;

    $CommNoteActive = (!empty($_POST['CommNoteActive'])) ? 1 : 0;

    if(!empty($CustomImageName)){
        $CustomImageNamePath = trim(sanitize_text_field(contest_gal1ery_htmlentities_and_preg_replace($_POST['CustomImageNamePath'])));
    }

// Values which should not be saved if not sended
    $unsavingValuesVisual = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tablename_options_visual WHERE GalleryID = %d",[$id]));

    $ThumbViewBorderWidth = $unsavingValuesVisual->ThumbViewBorderWidth;
    $ThumbViewBorderRadius = $unsavingValuesVisual->ThumbViewBorderRadius;
    $ThumbViewBorderColor = $unsavingValuesVisual->ThumbViewBorderColor;
    $ThumbViewBorderOpacity = $unsavingValuesVisual->ThumbViewBorderOpacity;
    $HeightViewBorderWidth = $unsavingValuesVisual->HeightViewBorderWidth;
    $HeightViewBorderRadius = $unsavingValuesVisual->HeightViewBorderRadius;
    $HeightViewSpaceWidth = $unsavingValuesVisual->HeightViewSpaceWidth;
    $HeightViewSpaceHeight = $unsavingValuesVisual->HeightViewSpaceHeight;
    $HeightViewBorderColor = $unsavingValuesVisual->HeightViewBorderColor;
    $HeightViewBorderOpacity = $unsavingValuesVisual->HeightViewBorderOpacity;
    $RowViewBorderWidth = $unsavingValuesVisual->RowViewBorderWidth;
    $RowViewBorderColor = $unsavingValuesVisual->RowViewBorderColor;
    $RowViewBorderOpacity = $unsavingValuesVisual->RowViewBorderOpacity;
    $RowViewBorderRadius = $unsavingValuesVisual->RowViewBorderRadius;
    $RowViewSpaceWidth = $unsavingValuesVisual->RowViewSpaceWidth;
    $RowViewSpaceHeight = $unsavingValuesVisual->RowViewSpaceHeight;
    $TitlePositionGallery = $unsavingValuesVisual->TitlePositionGallery;
    $RatingPositionGallery = $unsavingValuesVisual->RatingPositionGallery;
    $CommentPositionGallery = $unsavingValuesVisual->CommentPositionGallery;
    $ActivateGalleryBackgroundColor = $unsavingValuesVisual->ActivateGalleryBackgroundColor;
    $GalleryBackgroundColor = $unsavingValuesVisual->GalleryBackgroundColor;
    $GalleryBackgroundOpacity = $unsavingValuesVisual->GalleryBackgroundOpacity;
    $OriginalSourceLinkInSlider = $unsavingValuesVisual->OriginalSourceLinkInSlider;
    $PreviewInSlider = $unsavingValuesVisual->PreviewInSlider;
    $BlogLookFullWindow = $unsavingValuesVisual->BlogLookFullWindow;
    $ImageViewFullWindow = $unsavingValuesVisual->ImageViewFullWindow;
    $ImageViewFullScreen = $unsavingValuesVisual->ImageViewFullScreen;
    $SliderThumbNav = $unsavingValuesVisual->SliderThumbNav;
    $ShareButtons = $unsavingValuesVisual->ShareButtons;

    $AllowSortOptionsArray = (!empty($_POST['AllowSortOptionsArray'])) ? $_POST['AllowSortOptionsArray'] : [];

    if (!empty($AllowSortOptionsArray)) {

        $AllowSortOptions = '';

        foreach ($AllowSortOptionsArray as $AllowSortOptionsValue) {
            if (empty($AllowSortOptions)) {
                $AllowSortOptions .= $AllowSortOptionsValue;
            } else {
                $AllowSortOptions .= ',' . $AllowSortOptionsValue;
            }
        }

    } else {
        $AllowSortOptions = 'empty';
    }

    $OriginalSourceLinkInSlider = (isset($_POST['OriginalSourceLinkInSlider'])) ? '1' : '0';
    $ForwardToWpPageEntry = (isset($_POST['ForwardToWpPageEntry'])) ? 1 : 0;
    $ForwardToWpPageEntryInNewTab = (isset($_POST['ForwardToWpPageEntryInNewTab'])) ? 1 : 0;
    $ShowBackToGalleryButton = (isset($_POST['ShowBackToGalleryButton'])) ? 1 : 0;

    if (!empty($_POST['PreviewInSlider']) && !empty($_POST['AllowGalleryScript'])) {

        $PreviewInSlider = $PreviewInSlider;

    } else {
        $PreviewInSlider = (isset($_POST['PreviewInSlider'])) ? '1' : '0';
    }

    $ShowNickname = (!empty($_POST['ShowNickname'])) ? '1' : '0';

    $ShowProfileImage = (!empty($_POST['ShowProfileImage'])) ? '1' : '0';

/*    $BulkUploadType = intval($_POST['BulkUploadType']);

    $UploadFormAppearance = isset($_POST['UploadFormAppearance']) ? '1' : '2';*/

    $ShowExif = (!empty($_POST['ShowExif'])) ? '1' : '0';

    $MinusVote = (!empty($_POST['MinusVote'])) ? '1' : '0';

    $SliderFullWindow = (!empty($_POST['SliderFullWindow'])) ? '1' : '0';

  //  var_dump($SliderFullWindow);

    $BlogLookFullWindow = (!empty($_POST['BlogLookFullWindow'])) ? 1 : 0;

  //  var_dump($BlogLookFullWindow);


    $SlideTransition = (!empty($_POST['SlideTransition'])) ? $_POST['SlideTransition'] : 'translateX';

    // Votes in a time start

    // var_dump($_POST['cg_date_hours_vote_interval']);
    // var_dump($_POST['cg_date_mins_vote_interval']);

    $VotesInTime = (!empty($_POST['VotesInTime'])) ? '1' : '0';
    $VotesInTimeQuantity = (!empty($_POST['VotesInTimeQuantity'])) ? $_POST['VotesInTimeQuantity'] : $VotesInTimeQuantity;
    if (!empty($_POST['cg_date_hours_vote_interval'])) {
        $_POST['VotesInTimeIntervalReadable'] = $_POST['cg_date_hours_vote_interval'] . ":" . $_POST['cg_date_mins_vote_interval'];

        /*        if(intval($_POST['cg_date_hours_vote_interval'])==0){
                    $_POST['VotesInTimeIntervalSeconds'] =  intval($_POST['cg_date_mins_vote_interval'])*60;
                }else{
                    $_POST['VotesInTimeIntervalSeconds'] = intval($_POST['cg_date_hours_vote_interval'])*(intval($_POST['cg_date_mins_vote_interval'])*60);
                }*/


        //   $str_time = "1:01";

        sscanf($_POST['VotesInTimeIntervalReadable'], "%d:%d:%d", $hours, $minutes, $seconds);

        $_POST['VotesInTimeIntervalSeconds'] = isset($hours) ? $hours * 3600 + $minutes * 60 + $seconds : $minutes * 60 + $seconds;

    }
    //  var_dump($_POST['VotesInTimeIntervalSeconds']);

    $VotesInTimeIntervalReadable = (isset($_POST['VotesInTimeIntervalReadable'])) ? sanitize_text_field(htmlentities($_POST['VotesInTimeIntervalReadable'])) : $VotesInTimeIntervalReadable;
    $VotesInTimeIntervalSeconds = (isset($_POST['VotesInTimeIntervalSeconds'])) ? $_POST['VotesInTimeIntervalSeconds'] : $VotesInTimeIntervalSeconds;
    $VotesInTimeIntervalAlertMessage = (isset($_POST['VotesInTimeIntervalAlertMessage'])) ? sanitize_text_field(contest_gal1ery_htmlentities_and_preg_replace($_POST['VotesInTimeIntervalAlertMessage'])) : $VotesInTimeIntervalAlertMessage;

    // Votes in a time end

    $ActivateGalleryBackgroundColor = (isset($_POST['ActivateGalleryBackgroundColor'])) ? '1' : '0';

    $TitlePositionGallery = (isset($_POST['TitlePositionGallery'])) ? $_POST['TitlePositionGallery'] : $TitlePositionGallery;
    $RatingPositionGallery = (isset($_POST['RatingPositionGallery'])) ? $_POST['RatingPositionGallery'] : $RatingPositionGallery;
    $CommentPositionGallery = (isset($_POST['CommentPositionGallery'])) ? $_POST['CommentPositionGallery'] : $CommentPositionGallery;

    $ThumbViewBorderWidth = (!empty($_POST['ThumbViewBorderWidth'])) ? $_POST['ThumbViewBorderWidth'] : $ThumbViewBorderWidth;
    $ThumbViewBorderRadius = (!empty($_POST['ThumbViewBorderRadius'])) ? $_POST['ThumbViewBorderRadius'] : $ThumbViewBorderRadius;


    if (!isset($_POST['GalleryBackgroundColor'])) {
        $GalleryBackgroundColor = $GalleryBackgroundColor;
        $GalleryBackgroundOpacity = $GalleryBackgroundOpacity;
    } else {
        $GalleryBackgroundColorPOST = $_POST['GalleryBackgroundColor'];
        if ($GalleryBackgroundColorPOST) {
            foreach ($GalleryBackgroundColorPOST as $key1 => $value1) {
                $GalleryBackgroundOpacity = $key1;
                $GalleryBackgroundColor = $value1;
            }
        } else {
            $GalleryBackgroundColor = $GalleryBackgroundColor;
        }
    }

    $GalleryBackgroundOpacity = 1;

    if (!isset($_POST['ThumbViewBorderColor'])) {
        $ThumbViewBorderColor = $ThumbViewBorderColor;
        $ThumbViewBorderOpacity = $ThumbViewBorderOpacity;
    } else {
        $ThumbViewBorderColorPOST = $_POST['ThumbViewBorderColor'];
        if ($ThumbViewBorderColorPOST) {
            foreach ($ThumbViewBorderColorPOST as $key1 => $value1) {
                $ThumbViewBorderOpacity = sanitize_text_field(htmlentities($key1));
                $ThumbViewBorderColor = sanitize_text_field(htmlentities($value1));
            }
        } else {
            $ThumbViewBorderColor = sanitize_text_field(htmlentities($ThumbViewBorderColor));
        }
    }

    if (empty($ThumbViewBorderColor)) {
        $ThumbViewBorderColor = '#000000';
    }

    $ThumbViewBorderOpacity = 1;

    if (!isset($_POST['HeightViewBorderColor'])) {
        $HeightViewBorderColor = $HeightViewBorderColor;
        $HeightViewBorderOpacity = $HeightViewBorderOpacity;
    } else {
        $HeightViewBorderColorPOST = $_POST['HeightViewBorderColor'];
        if ($HeightViewBorderColorPOST) {
            foreach ($HeightViewBorderColorPOST as $key2 => $value2) {
                $HeightViewBorderOpacity = sanitize_text_field(htmlentities($key2));
                $HeightViewBorderColor = sanitize_text_field(htmlentities($value2));
            }
        } else {
            $HeightViewBorderColor = sanitize_text_field(htmlentities($HeightViewBorderColor));
        }
    }

    if (empty($HeightViewBorderColor)) {
        $HeightViewBorderColor = '#000000';
    }

    $HeightViewBorderOpacity = 1;

    if (!isset($_POST['RowViewBorderColor'])) {
        $RowViewBorderColor = $RowViewBorderColor;
        $RowViewBorderOpacity = $RowViewBorderOpacity;
    } else {
        $RowViewBorderColorPOST = $_POST['RowViewBorderColor'];
        if ($RowViewBorderColorPOST) {
            foreach ($RowViewBorderColorPOST as $key3 => $value3) {
                $RowViewBorderOpacity = sanitize_text_field(htmlentities($key3));
                $RowViewBorderColor = sanitize_text_field(htmlentities($value3));
            }
        } else {
            $RowViewBorderOpacity = sanitize_text_field(htmlentities($RowViewBorderOpacity));
        }
    }

    if (empty($RowViewBorderColor)) {
        $RowViewBorderColor = '#000000';
    }

    $RowViewBorderOpacity = 1;

    $GalleryName = (isset($_POST['GalleryName'])) ? trim(sanitize_text_field(contest_gal1ery_htmlentities_and_preg_replace($_POST['GalleryName']))) : $GalleryName;

    $RowViewBorderWidth = (isset($_POST['RowViewBorderWidth'])) ? $_POST['RowViewBorderWidth'] : $RowViewBorderWidth;
    $RowViewBorderRadius = (isset($_POST['RowViewBorderRadius'])) ? $_POST['RowViewBorderRadius'] : $RowViewBorderRadius;
    $RowViewSpaceWidth = (isset($_POST['RowViewSpaceWidth'])) ? $_POST['RowViewSpaceWidth'] : $RowViewSpaceWidth;
    $RowViewSpaceHeight = (isset($_POST['RowViewSpaceHeight'])) ? $_POST['RowViewSpaceHeight'] : $RowViewSpaceHeight;

    $HeightViewBorderWidth = (isset($_POST['HeightViewBorderWidth'])) ? $_POST['HeightViewBorderWidth'] : $HeightViewBorderWidth;
    $HeightViewBorderRadius = (isset($_POST['HeightViewBorderRadius'])) ? $_POST['HeightViewBorderRadius'] : $HeightViewBorderRadius;
    $HeightViewSpaceWidth = (isset($_POST['HeightViewSpaceWidth'])) ? $_POST['HeightViewSpaceWidth'] : $HeightViewSpaceWidth;
    $HeightViewSpaceHeight = (isset($_POST['HeightViewSpaceHeight'])) ? $_POST['HeightViewSpaceHeight'] : $HeightViewSpaceHeight;


//echo $HeightViewBorderWidth;


//    var_dump($OriginalSourceLinkInSlider);
//    var_dump($PreviewInSlider);

    $FeControlsStyle = (!empty($_POST['FeControlsStyle'])) ? sanitize_text_field($_POST['FeControlsStyle']) : 'white';
    $GalleryStyle = (!empty($_POST['GalleryStyle'])) ? sanitize_text_field($_POST['GalleryStyle']) : 'center-black';

    $FeControlsStyleUpload = (!empty($_POST['FeControlsStyleWhiteUpload'])) ? 'white' : 'black';
    $FeControlsStyleRegistry = (!empty($_POST['FeControlsStyleWhiteRegistry'])) ? 'white' : 'black';// will be also saved for general options in cg_update_registry_and_login_options_v14
    $FeControlsStyleLogin = (!empty($_POST['FeControlsStyleWhiteLogin'])) ? 'white' : 'black';// will be also saved for general options in cg_update_registry_and_login_options_v14

    // view order

    $order = $_POST['order'];

    $i = 0;
    //echo "<br>Order:<br>";
    //print_r($order);
    //echo "<br>";

    foreach ($order as $key => $value) {

        $i++;

        if ($value == 't') {
            $t = $i;
        }
        if ($value == 'h') {
            $h = $i;
        }
        if($dbVersion<15){
            if ($value == 'r') {
                $r = $i;
            }
        }
        if ($value == 's') {
            $s = $i;
        }
        if ($value == 'b') {
            $b = $i;
        }

    }

    $ThumbLook = (!empty($_POST['ThumbLook'])) ? '1' : '0';
    $HeightLook = (!empty($_POST['HeightLook'])) ? '1' : '0';
    $RowLook = (!empty($_POST['RowLook'])) ? '1' : '0';
    $SliderLook = (!empty($_POST['SliderLook'])) ? '1' : '0';
    $BlogLook = (!empty($_POST['BlogLook'])) ? 1 : 0;

    $ThumbLookOrder = $t;
    $HeightLookOrder = 0;
    if($dbVersion<15){
        $RowLookOrder = $r;
    }else{
        $RowLookOrder = 0;
    }
    $SliderLookOrder = $s;
    $BlogLookOrder = $b;

    //var_dump($ThumbLookOrder);
    //var_dump($HeightLookOrder);
    //var_dump($RowLookOrder);
    //var_dump($SliderLookOrder);
    //var_dump($BlogLookOrder);

    // view order --- END

    $ImageViewFullWindow = (!empty($_POST['ImageViewFullWindow'])) ? 1 : 0;
    $ImageViewFullScreen = (!empty($_POST['ImageViewFullScreen'])) ? 1 : 0;
    $SliderThumbNav = (!empty($_POST['SliderThumbNav'])) ? 1 : 0;
    $BorderRadius = (!empty($_POST['BorderRadius'])) ? 1 : 0;
    $BorderRadiusUpload = (!empty($_POST['BorderRadiusUpload'])) ? 1 : 0;
    $BorderRadiusRegistry = (!empty($_POST['BorderRadiusRegistry'])) ? 1 : 0;// will be also saved for general options in cg_update_registry_and_login_options_v14
    $BorderRadiusLogin = (!empty($_POST['BorderRadiusLogin'])) ? 1 : 0;// will be also saved for general options in cg_update_registry_and_login_options_v14
    $ThankVote = (!empty($_POST['ThankVote'])) ? 1 : 0;
    $CopyImageLink = (!empty($_POST['CopyImageLink'])) ? 1 : 0;
    $CopyOriginalFileLink = (!empty($_POST['CopyOriginalFileLink'])) ? 1 : 0;
    $ForwardOriginalFile = (!empty($_POST['ForwardOriginalFile'])) ? 1 : 0;
    $CommentsDateFormat = (!empty($_POST['CommentsDateFormat'])) ? $_POST['CommentsDateFormat'] : 'YYYY-MM-DD';
    $ShareButtons = sanitize_text_field($_POST['ShareButtons']);

    $optionsForGeneralIDsinceV14['visual'] = [];
    $optionsForGeneralIDsinceV14['visual']['BorderRadiusRegistry'] = $BorderRadiusRegistry;
    $optionsForGeneralIDsinceV14['visual']['FeControlsStyleRegistry'] = $FeControlsStyleRegistry;
    $optionsForGeneralIDsinceV14['visual']['BorderRadiusLogin'] = $BorderRadiusLogin;
    $optionsForGeneralIDsinceV14['visual']['FeControlsStyleLogin'] = $FeControlsStyleLogin;

    $TextBeforeWpPageEntry = (isset($_POST['TextBeforeWpPageEntry'])) ? contest_gal1ery_htmlentities_and_preg_replace($_POST['TextBeforeWpPageEntry']) : '';
    $TextAfterWpPageEntry = (isset($_POST['TextAfterWpPageEntry'])) ? contest_gal1ery_htmlentities_and_preg_replace($_POST['TextAfterWpPageEntry']) : '';

    $BackToGalleryButtonText = (isset($_POST['BackToGalleryButtonText'])) ? contest_gal1ery_htmlentities_and_preg_replace($_POST['BackToGalleryButtonText']) : '';
    $TextDeactivatedEntry = (isset($_POST['TextDeactivatedEntry'])) ? contest_gal1ery_htmlentities_and_preg_replace($_POST['TextDeactivatedEntry']) : '';

    $BackToGalleryButtonURL = (isset($_POST['BackToGalleryButtonURL'])) ? contest_gal1ery_htmlentities_and_preg_replace($_POST['BackToGalleryButtonURL']) : '';
    $WpPageParentRedirectURL = (isset($_POST['WpPageParentRedirectURL'])) ? contest_gal1ery_htmlentities_and_preg_replace($_POST['WpPageParentRedirectURL']) : '';
    $RedirectURLdeletedEntry = (isset($_POST['RedirectURLdeletedEntry'])) ? contest_gal1ery_htmlentities_and_preg_replace($_POST['RedirectURLdeletedEntry']) : '';

    $wpdb->update(
        "$tablename_options_visual",
        array('ThumbViewBorderWidth' => $ThumbViewBorderWidth, 'ThumbViewBorderRadius' => $ThumbViewBorderRadius,
            'ThumbViewBorderColor' => $ThumbViewBorderColor, 'ThumbViewBorderOpacity' => $ThumbViewBorderOpacity,
            'HeightViewBorderWidth' => $HeightViewBorderWidth, 'HeightViewBorderRadius' => $HeightViewBorderRadius,
            'HeightViewBorderColor' => $HeightViewBorderColor, 'HeightViewBorderOpacity' => $HeightViewBorderOpacity, 'HeightViewSpaceWidth' => $HeightViewSpaceWidth, 'HeightViewSpaceHeight' => $HeightViewSpaceHeight,
            'RowViewBorderWidth' => $RowViewBorderWidth, 'RowViewBorderRadius' => $RowViewBorderRadius,
            'RowViewBorderColor' => $RowViewBorderColor, 'RowViewBorderOpacity' => $RowViewBorderOpacity, 'RowViewSpaceWidth' => $RowViewSpaceWidth, 'RowViewSpaceHeight' => $RowViewSpaceHeight,
            'TitlePositionGallery' => $TitlePositionGallery, 'RatingPositionGallery' => $RatingPositionGallery, 'CommentPositionGallery' => $CommentPositionGallery,
            'ActivateGalleryBackgroundColor' => $ActivateGalleryBackgroundColor, 'GalleryBackgroundColor' => $GalleryBackgroundColor, 'GalleryBackgroundOpacity' => $GalleryBackgroundOpacity,
            'OriginalSourceLinkInSlider' => $OriginalSourceLinkInSlider, 'PreviewInSlider' => $PreviewInSlider,
            'FeControlsStyle' => $FeControlsStyle, 'AllowSortOptions' => $AllowSortOptions, 'GalleryStyle' => $GalleryStyle,
            'BlogLook' => $BlogLook, 'BlogLookOrder' => $BlogLookOrder, 'BlogLookFullWindow' => $BlogLookFullWindow,
            'ImageViewFullWindow' => $ImageViewFullWindow, 'ImageViewFullScreen' => $ImageViewFullScreen,
            'SliderThumbNav' => $SliderThumbNav,'BorderRadius' => $BorderRadius,
            'CopyImageLink' => $CopyImageLink,'CommentsDateFormat' => $CommentsDateFormat,
            'FeControlsStyleUpload' => $FeControlsStyleUpload,'FeControlsStyleRegistry' => $FeControlsStyleRegistry,'FeControlsStyleLogin' => $FeControlsStyleLogin,
            'BorderRadiusUpload' => $BorderRadiusUpload,'BorderRadiusRegistry' => $BorderRadiusRegistry,'BorderRadiusLogin' => $BorderRadiusLogin,'ThankVote' => $ThankVote,
            'CopyOriginalFileLink' => $CopyOriginalFileLink,'ForwardOriginalFile' => $ForwardOriginalFile , 'ShareButtons' => $ShareButtons,
            'ForwardToWpPageEntry' => $ForwardToWpPageEntry, 'ForwardToWpPageEntryInNewTab' => $ForwardToWpPageEntryInNewTab,'TextBeforeWpPageEntry' => $TextBeforeWpPageEntry,'TextAfterWpPageEntry' => $TextAfterWpPageEntry,
            'ShowBackToGalleryButton' => $ShowBackToGalleryButton, 'BackToGalleryButtonText' => $BackToGalleryButtonText, 'TextDeactivatedEntry' => $TextDeactivatedEntry
        ),
        array('GalleryID' => $id),
        array('%d', '%d',
            '%s', '%s',
            '%d', '%d',
            '%s', '%s', '%d', '%d',
            '%d', '%d',
            '%s', '%s', '%d', '%d',
            '%d', '%d', '%d',
            '%d', '%s', '%s', '%s', '%s',
            '%s', '%s', '%s',
            '%d', '%d', '%d',
            '%d', '%d',
            '%d', '%d',
            '%d', '%s',
            '%s', '%s', '%s',
            '%d', '%d', '%d', '%d',
            '%d', '%d', '%s',
            '%d', '%d', '%s', '%s',
            '%d', '%s', '%s'
        ),
        array('%d')
    );

// Unix Zeitstempel wird eingetragen. Sp�ter browserabh�ngig verarbeitet.
// 86400 = Anzahl der Sekunden an einem Tag
// Man w�hlt immer den Tag aus an dem der Contest endet in, edit-options.php, das ist dann immer 00:00 und f�gt die Sekunden hinzu bis Ende des Taes.
// �berall anders aknn die Zeit dann direkt verarbeitet werden
//$unix = time();

    if (!empty($_POST['ContestEndTime'])) {

        $ContestEndTimeHours = (!empty($_POST['ContestEndTimeHours'])) ? $_POST['ContestEndTimeHours'] : '00';
        $ContestEndTimeMins = (!empty($_POST['ContestEndTimeMins'])) ? $_POST['ContestEndTimeMins'] : '00';

        $ContestEndTime = (!empty($_POST['ContestEndTime'])) ? strtotime($_POST['ContestEndTime'] . ' ' . $ContestEndTimeHours . ':' . $ContestEndTimeMins) : $ContestEndTime;

    }else{
        if (!empty($_POST['ContestEndTime']) AND !empty($_POST['ContestEndTimeHours']) AND !empty($_POST['ContestEndTimeMins'])) {
            $ContestEndTime = strtotime($_POST['ContestEndTime'] . ' ' . $_POST['ContestEndTimeHours'] . ':' . $_POST['ContestEndTimeMins']);
        }else{
            $ContestEndTime = $ContestEndTime;
        }
    }

    $DistancePics = (isset($_POST['DistancePics'])) ? absint($_POST['DistancePics']) : $DistancePics;
    $DistancePicsV = (isset($_POST['DistancePicsV'])) ? absint($_POST['DistancePicsV']) : $DistancePicsV;

    if (!empty($_POST['ContestStartTime'])) {

        $ContestStartTimeHours = (!empty($_POST['ContestStartTimeHours'])) ? $_POST['ContestStartTimeHours'] : '00';
        $ContestStartTimeMins = (!empty($_POST['ContestStartTimeMins'])) ? $_POST['ContestStartTimeMins'] : '00';

        $ContestStartTime = (!empty($_POST['ContestStartTime'])) ? strtotime($_POST['ContestStartTime'] . ' ' . $ContestStartTimeHours . ':' . $ContestStartTimeMins) : $ContestStartTime;

    }else{
        if (!empty($_POST['ContestStartTime']) AND !empty($_POST['ContestStartTimeHours']) AND !empty($_POST['ContestStartTimeMins'])) {
            $ContestStartTime = strtotime($_POST['ContestStartTime'] . ' ' . $_POST['ContestStartTimeHours'] . ':' . $_POST['ContestStartTimeMins']);
        }else{
            $ContestStartTime = $ContestStartTime;
        }
    }

    if (isset($_POST['ThumbLook'])) {
        $AdjustThumbLook = (isset($_POST['AdjustThumbLook'])) ? 1 : 0;
    } else {
        $AdjustThumbLook = $AdjustThumbLook;
    }

    $WidthThumb = (!empty($_POST['WidthThumb'])) ? $_POST['WidthThumb'] : $WidthThumb;
    $HeightThumb = (!empty($_POST['HeightThumb'])) ? $_POST['HeightThumb'] : $HeightThumb;
    $WidthGallery = (!empty($_POST['WidthGallery'])) ? $_POST['WidthGallery'] : $WidthGallery;
    $HeightGallery = (!empty($_POST['HeightGallery'])) ? $_POST['HeightGallery'] : $HeightGallery;

    // echo "<br>WidthGalery: $WidthGalery<br>";
    // echo "<br>HeightGalery: $HeightGalery<br>";

// Ermittel die gesendeten Werte f�r die Gr��e der Bilder --- ENDE


// Ermittel zuerst die gesendeten Zahlenwerte der Einstellungen


    //$querySETvaluesThumbs = "UPDATE $tablenameOptions SET $WidthThumb $HeightThumb $WidthGallery $HeightGallery
    //$DistancePics $DistancePicsV  WHERE id = '$id'";
    //$wpdb->query($querySETvaluesThumbs);

    $ContestStart = (!empty($_POST['ContestStart'])) ? '1' : '0';

    $wpdb->update(
        "$tablenameOptions",
        array('WidthThumb' => $WidthThumb, 'HeightThumb' => $HeightThumb, 'WidthGallery' => $WidthGallery, 'HeightGallery' => $HeightGallery,
            'DistancePics' => $DistancePics, 'DistancePicsV' => $DistancePicsV, 'ContestEndTime' => $ContestEndTime, 'ContestStart' => $ContestStart, 'ContestStartTime' => $ContestStartTime),
        array('id' => $id),
        array('%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%s'),
        array('%d')
    );


// Ermittel zuerst die gesendeten Zahlenwerte der Einstellungen --- ENDE

// Ermittelt die gesendeten Einstellungen (checkboxes)


    $PicsPerSite = (isset($_POST['PicsPerSite'])) ? $_POST['PicsPerSite'] : $PicsPerSite;



    $OnlyGalleryView = (!empty($_POST['OnlyGalleryView'])) ? '1' : '0';
    $SinglePicView = (!empty($_POST['SinglePicView'])) ? '1' : '0';

    if (empty($_POST['ScaleWidthGalery']) or empty($_POST['ScaleSizesGalery'])) {

        if ($ScaleAndCut == 1 AND empty($_POST['ScaleWidthGalery'])) {
            $ScaleAndCut = 1;
        } else if ($ScaleOnly == 1 AND empty($_POST['ScaleSizesGalery'])) {
            $ScaleOnly = 1;
        } else if ($ScaleOnly != 1 AND empty($_POST['ScaleSizesGalery'])) {
            $ScaleOnly = 1;
        } else {
            $ScaleAndCut = 1;
        }

    } else {
        $ScaleOnly = (!empty($_POST['ScaleWidthGalery'])) ? '1' : '0';
        $ScaleAndCut = (!empty($_POST['ScaleSizesGalery'])) ? '1' : '0';
    }

    $AllowGalleryScript = (!empty($_POST['AllowGalleryScript'])) ? '1' : '0';

    $FullSizeGallery = (!empty($_POST['FullSizeGallery'])) ? '1' : '0';


    if (!empty($_POST['AllowGalleryScript'])) {
        $HideInfo = (!empty($_POST['HideInfo'])) ? '1' : 0;
    } else {
        $HideInfo = (!empty($_POST['HideInfo'])) ? '1' : $HideInfo;
    }

    $FbLikeNoShare = (!empty($_POST['FbLikeNoShare'])) ? 1 : 0;
    $FbLikeOnlyShare = (!empty($_POST['FbLikeOnlyShare'])) ? 1 : 0;

    // 1 = Height, 2 = Thumb, 3 = Row
    if (!empty($_POST['InfiniteScrollHeight'])) {
        $InfiniteScroll = 1;
    } else if (!empty($_POST['InfiniteScrollThumb'])) {
        $InfiniteScroll = 2;
    } else if (!empty($_POST['InfiniteScrollRow'])) {
        $InfiniteScroll = 3;
    } else {
        $InfiniteScroll = 0;
    }


    //echo "InfiniteScroll: $InfiniteScroll";


    $FullSizeImageOutGallery = (isset($_POST['FullSizeImageOutGallery'])) ? '1' : '0';
    $FullSizeImageOutGalleryNewTab = '1'; //Bei aktuellem Entwicklungsstand immer 1
    $ShowAlwaysInfoSlider = (isset($_POST['ShowAlwaysInfoSlider'])) ? '1' : '0';

    $HeightLookHeight = (isset($_POST['HeightLookHeight'])) ? $_POST['HeightLookHeight'] : $HeightLookHeight;
    $VotesPerUser = (!empty($_POST['VotesPerUser'])) ? sanitize_text_field($_POST['VotesPerUser']) : 0;

    $FbLikeGoToGalleryLink = (isset($_POST['FbLikeGoToGalleryLink'])) ? $_POST['FbLikeGoToGalleryLink'] : '';

    // Zuerst insert
    $backToGalleryFile = $wp_upload_dir["basedir"] . "/contest-gallery/gallery-id-$id/backtogalleryurl.js";
    $backToGalleryFileContent = 'backToGalleryUrl="' . $FbLikeGoToGalleryLink . '";';
    $FbLikeGoToGalleryLink = contest_gal1ery_htmlentities_and_preg_replace($FbLikeGoToGalleryLink, ENT_QUOTES);

    $fp = fopen($backToGalleryFile, 'w');
    fwrite($fp, $backToGalleryFileContent);
    fclose($fp);


    $PicsInRow = (!empty($_POST['PicsInRow'])) ? $_POST['PicsInRow'] : $PicsInRow;
    if ($PicsInRow == 0) {
        $PicsInRow = 1;
    }
    $LastRow = (!empty($_POST['LastRow'])) ? '1' : '0';
    $HideUntilVote = (!empty($_POST['HideUntilVote'])) ? '1' : '0';
    $ShowOnlyUsersVotes = (!empty($_POST['ShowOnlyUsersVotes'])) ? '1' : '0';
    $ActivateUpload = (!empty($_POST['ActivateUpload'])) ? '1' : '0';
    $ContestEnd = (!empty($_POST['ContestEnd'])) ? '1' : '0';
    $ContestStart = (!empty($_POST['ContestStart'])) ? '1' : '0';
    $ContestEndInstant = (!empty($_POST['ContestEndInstant'])) ? '1' : '0';
    $ReviewComm = (!empty($_POST['ReviewComm'])) ? '1' : '0';

    if ($ContestEndInstant == 1) {
        $ContestEnd = 2;
    }

    $ThumbsInRow = (!empty($_POST['ThumbsInRow'])) ? '1' : '0';

    $FullSize = (!empty($_POST['FullSize'])) ? '1' : '0';
    $FullSizeGallery = (!empty($_POST['FullSizeGallery'])) ? '1' : '0';
    $OriginalSourceLinkInSlider = (!empty($_POST['OriginalSourceLinkInSlider'])) ? '1' : '0';


    $FullSizeSlideOutStart = (!empty($_POST['FullSizeSlideOutStart'])) ? '1' : '0';


    $AllowSort = (!empty($_POST['AllowSort'])) ? '1' : '0';
    $RandomSort = (!empty($_POST['RandomSort'])) ? '1' : '0';
    $RandomSortButton = (!empty($_POST['RandomSortButton'])) ? '1' : '0';

    $AllowComments = $_POST['AllowComments'];

    $CommentsOutGallery = (!empty($_POST['CommentsOutGallery'])) ? '1' : '0';

    if($dbVersion<21){
    $ShowAlways = (!empty($_POST['ShowAlways'])) ? '1' : '0';
    }else{
        $ShowAlways = (!empty($_POST['ShowAlways'])) ? 3 : 3;
    }

    $AllowRating = (!empty($_POST['AllowRating'])) ? '1' : '0';

    //var_dump("allow rating 3 post");
    //var_dump($_POST['AllowRating3']);
    if (!empty($_POST['AllowRating'])) {
        //var_dump(1);
        $AllowRating = 1;
        if (!empty($_POST['AllowRating3'])) {
            //var_dump(2);
            $AllowRating = intval($_POST['AllowRating3']);// will be 12-20
        }
    } else if (!empty($_POST['AllowRating2'])) {
        //var_dump(3);
        $AllowRating = 2;
    } else {
        //var_dump(4);
        $AllowRating = 0;
    }

    //var_dump('$AllowRating');
    //var_dump($AllowRating);

    $RatingOutGallery = (!empty($_POST['RatingOutGallery'])) ? '1' : '0';
    $IpBlock = (!empty($_POST['IpBlock'])) ? '1' : '0';

    $CheckLogin = 0;
    $CheckCookie = 0;
    $CheckIp = 0;

    if (empty($_POST['CheckMethod'])) {

        $CheckIp = 1;

    } else {
        switch ($_POST['CheckMethod']) {
            case 'login':
                $CheckLogin = 1;
                break;
            case 'cookie':
                $CheckCookie = 1;
                break;
            case 'ip':
                $CheckIp = 1;
                break;
            case 'ip-and-cookie':
                $CheckCookie = 1;
                $CheckIp = 1;
                break;
            default:
                $CheckIp = 1;
                break;
        }
    }

    $CheckCookieAlertMessage = (isset($_POST['CheckCookieAlertMessage'])) ? contest_gal1ery_htmlentities_and_preg_replace($_POST['CheckCookieAlertMessage']) : $CheckCookieAlertMessage;

    if($dbVersion>=14){// for new galleries after 14
        $RegistryUserRole = '';
        $RegistryUserRoleForRegistryAndLoginOptions = sanitize_text_field(htmlentities((isset($_POST['RegistryUserRole'])) ? $_POST['RegistryUserRole'] : ''));
    }else{// for older galleries before 14
        $RegistryUserRole = sanitize_text_field(htmlentities((isset($_POST['RegistryUserRole'])) ? $_POST['RegistryUserRole'] : ''));
        $RegistryUserRoleForRegistryAndLoginOptions = '';
    }

    $Manipulate = (!empty($_POST['Manipulate'])) ? '1' : '0';
    $Search = (!empty($_POST['Search'])) ? '1' : '0';
    $GalleryUpload = (!empty($_POST['GalleryUpload'])) ? '1' : '0';
    $GalleryUploadOnlyUser = (!empty($_POST['GalleryUploadOnlyUser'])) ? '1' : '0';

    $VotePerCategory = (!empty($_POST['VotePerCategory'])) ? '1' : '0';

    // var_dump($GalleryUploadConfirmationText);
    //var_dump($_POST['GalleryUploadConfirmationText']);

    $GalleryUploadConfirmationText = contest_gal1ery_htmlentities_and_preg_replace((isset($_POST['GalleryUploadConfirmationText'])) ? $_POST['GalleryUploadConfirmationText'] : '');
    $GalleryUploadTextBefore = contest_gal1ery_htmlentities_and_preg_replace((isset($_POST['GalleryUploadTextBefore'])) ? $_POST['GalleryUploadTextBefore'] : '');
    $GalleryUploadTextAfter = contest_gal1ery_htmlentities_and_preg_replace((isset($_POST['GalleryUploadTextAfter'])) ? $_POST['GalleryUploadTextAfter'] : '');

    $FbLike = (!empty($_POST['FbLike'])) ? '1' : '0';
    $FbLikeGallery = (!empty($_POST['FbLikeGallery'])) ? '1' : '0';
    $FbLikeGalleryVote = (!empty($_POST['FbLikeGalleryVote'])) ? '1' : '0';

    $Inform = (!empty($_POST['InformUsers'])) ? '1' : '0';

    // Forward Images to URL options


    //Pr�fen ob bei Klick auf images weitergelitet werden soll

    //if(){}
    $ForwardToURL = 1;

    $ForwardType = (isset($_POST['ForwardType'])) ? '2' : '1';

    // Pauschal auf 1 wenn nichts gesendet wird
    // Slider = 1, Gallery = 2, SinglePic = 3
    $ForwardFrom = $wpdb->get_var("SELECT ForwardFrom FROM $tablenameOptions WHERE id = '$id'");
    // Wenn Gallerie Jquery gew�hlt ist dann 1 (Forward from Slider)
    if ($AllowGalleryScript == 1 && empty($_POST['ForwardFromGallery'])) {
        $ForwardFrom = 1;
    } // Wenn ForwardFromGallery mitgeschickt wurde dann 2
    else if (empty(['ForwardFromGallery'])) {
        $ForwardFrom = 2;
    } // Wenn SinglePic Ansicht gew�hlt ist dann 3
    else if ($SinglePicView == 1) {
        $ForwardFrom = 3;
    } else {
        $ForwardFrom = $ForwardFrom;
    }


    //echo "$ForwardFrom";
    //else {$ForwardFrom=$ForwardFrom;}



    // Ermitteln der maximalen Uploads beim Hochalden in MB

    $ActivatePostMaxMB = (isset($_POST['ActivatePostMaxMB'])) ? '1' : '0';
    $PostMaxMB = (isset($_POST['PostMaxMB'])) ? $_POST['PostMaxMB'] : 0;

    $ActivatePostMaxMBfile = (isset($_POST['ActivatePostMaxMBfile'])) ? '1' : '0';
    $PostMaxMBfile = (isset($_POST['PostMaxMBfile'])) ? $_POST['PostMaxMBfile'] : 0;

    // Ermitteln des maximalen Uploads beim Hochladen in MB --- ENDE

    // Ermitteln ob und der Anzahl des Bulk Uploads

    $ActivateBulkUpload = (isset($_POST['ActivateBulkUpload'])) ? '1' : '0';

    $BulkUploadQuantity = (isset($_POST['BulkUploadQuantity'])) ? $_POST['BulkUploadQuantity'] : 0;

    $BulkUploadMinQuantity = (isset($_POST['BulkUploadMinQuantity'])) ? $_POST['BulkUploadMinQuantity'] : 0;

    // Ermitteln ob und der Anzahl des Bulk Uploads	 --- ENDE

    // Ermitteln der m�glichen Aufl�sung beim Hochalden

    $MaxResJPGon = (isset($_POST['MaxResJPGon'])) ? 1 : 0;

    $MaxResJPGwidth = (isset($_POST['MaxResJPGwidth'])) ? $_POST['MaxResJPGwidth'] : 0;
    $MaxResJPGheight = (isset($_POST['MaxResJPGheight'])) ? $_POST['MaxResJPGheight'] : 0;

    $MinResJPGon = (isset($_POST['MinResJPGon'])) ? 1 : 0;
    $MinResJPGwidth = (isset($_POST['MinResJPGwidth'])) ? $_POST['MinResJPGwidth'] : 0;
    $MinResJPGheight = (isset($_POST['MinResJPGheight'])) ? $_POST['MinResJPGheight'] : 0;

    $MaxResPNGon = (isset($_POST['MaxResPNGon'])) ? 1 : 0;

    $MaxResPNGwidth = (isset($_POST['MaxResPNGwidth'])) ? $_POST['MaxResPNGwidth'] : 0;
    $MaxResPNGheight = (isset($_POST['MaxResPNGheight'])) ? $_POST['MaxResPNGheight'] : 0;

    $MinResPNGon = (isset($_POST['MinResPNGon'])) ? 1 : 0;
    $MinResPNGwidth = (isset($_POST['MinResPNGwidth'])) ? $_POST['MinResPNGwidth'] : 0;
    $MinResPNGheight = (isset($_POST['MinResPNGheight'])) ? $_POST['MinResPNGheight'] : 0;

    $MaxResGIFon = (isset($_POST['MaxResGIFon'])) ? 1 : 0;

    $MaxResGIFwidth = (isset($_POST['MaxResGIFwidth'])) ? $_POST['MaxResGIFwidth'] : 0;
    $MaxResGIFheight = (isset($_POST['MaxResGIFheight'])) ? $_POST['MaxResGIFheight'] : 0;

    $MinResGIFon = (isset($_POST['MinResGIFon'])) ? 1 : 0;
    $MinResGIFwidth = (isset($_POST['MinResGIFwidth'])) ? $_POST['MinResGIFwidth'] : 0;
    $MinResGIFheight = (isset($_POST['MinResGIFheight'])) ? $_POST['MinResGIFheight'] : 0;

    $MaxResICOon = (isset($_POST['MaxResICOon'])) ? 1 : 0;

    $MaxResICOwidth = (isset($_POST['MaxResICOwidth'])) ? $_POST['MaxResICOwidth'] : 0;
    $MaxResICOheight = (isset($_POST['MaxResICOheight'])) ? $_POST['MaxResICOheight'] : 0;


    // Ermitteln der m�glichen Aufl�sung beim Hochalden --- ENDE


// Ermittelt die gesendeten Einstellungen (checkboxes) --- ENDE

    // Update non scale or cut values

    /*$querySETvalues = "UPDATE $tablenameOptions SET PicsPerSite='$PicsPerSite', MaxResJPGon='$MaxResJPGon', MaxResPNGon='$MaxResPNGon', MaxResGIFon='$MaxResGIFon',
    $MaxResJPG $MaxResPNG $MaxResGIF
    ScaleOnly='$ScaleOnly', ScaleAndCut='$ScaleAndCut', FullSize = '$FullSize', AllowSort = '$AllowSort',
    AllowComments = '$AllowComments', AllowRating = '$AllowRating', IpBlock = '$IpBlock', FbLike = '$FbLike', AllowGalleryScript='$AllowGalleryScript',
    ThumbLook = '$ThumbLook', HeightLook = '$HeightLook', RowLook = '$RowLook',
    ThumbLookOrder = '$ThumbLookOrder', HeightLookOrder = '$HeightLookOrder', RowLookOrder = '$RowLookOrder',
    $HeightLookHeight ThumbsInRow = '$ThumbsInRow', $PicsInRow LastRow = '$LastRow'
    WHERE id = '$id'";*/

    //$wpdb->query($querySETvalues);
    $ScaleOnly = 1;
    $ScaleAndCut = 0;

    $wpdb->update(
        "$tablenameOptions",
        array('PicsPerSite' => $PicsPerSite, 'GalleryName' => $GalleryName, 'MaxResJPGon' => $MaxResJPGon, 'MaxResPNGon' => $MaxResPNGon, 'MaxResGIFon' => $MaxResGIFon,
            'MinResJPGon' => $MinResJPGon, 'MinResPNGon' => $MinResPNGon, 'MinResGIFon' => $MinResGIFon,
            'MinResJPGwidth' => $MinResJPGwidth, 'MinResJPGheight' => $MinResJPGheight, 'MinResPNGwidth' => $MinResPNGwidth,
            'MinResPNGheight' => $MinResPNGheight, 'MinResGIFwidth' => $MinResGIFwidth, 'MinResGIFheight' => $MinResGIFheight,
            'MaxResJPGwidth' => $MaxResJPGwidth, 'MaxResJPGheight' => $MaxResJPGheight, 'MaxResPNGwidth' => $MaxResPNGwidth, 'MaxResPNGheight' => $MaxResPNGheight, 'MaxResGIFwidth' => $MaxResGIFwidth, 'MaxResGIFheight' => $MaxResGIFheight,
            'OnlyGalleryView' => $OnlyGalleryView, 'SinglePicView' => $SinglePicView, 'ScaleOnly' => $ScaleOnly, 'ScaleAndCut' => $ScaleAndCut, 'FullSize' => $FullSize, 'FullSizeGallery' => $FullSizeGallery, 'FullSizeSlideOutStart' => $FullSizeSlideOutStart, 'AllowSort' => $AllowSort, 'RandomSort' => $RandomSort, 'RandomSortButton' => $RandomSortButton, 'ShowAlways' => $ShowAlways,
            'AllowComments' => $AllowComments, 'CommentsOutGallery' => $CommentsOutGallery, 'AllowRating' => $AllowRating, 'VotesPerUser' => $VotesPerUser, 'RatingOutGallery' => $RatingOutGallery, 'IpBlock' => $IpBlock,
            'CheckLogin' => $CheckLogin, 'FbLike' => $FbLike, 'FbLikeGallery' => $FbLikeGallery, 'FbLikeGalleryVote' => $FbLikeGalleryVote,
            'AllowGalleryScript' => $AllowGalleryScript, 'InfiniteScroll' => $InfiniteScroll, 'FullSizeImageOutGallery' => $FullSizeImageOutGallery, 'FullSizeImageOutGalleryNewTab' => $FullSizeImageOutGalleryNewTab,
            'Inform' => $Inform, 'ShowAlwaysInfoSlider' => $ShowAlwaysInfoSlider, 'ThumbLook' => $ThumbLook, 'AdjustThumbLook' => $AdjustThumbLook, 'HeightLook' => $HeightLook, 'RowLook' => $RowLook,
            'ThumbLookOrder' => $ThumbLookOrder, 'HeightLookOrder' => $HeightLookOrder, 'RowLookOrder' => $RowLookOrder,
            'HeightLookHeight' => $HeightLookHeight, 'ThumbsInRow' => $ThumbsInRow, 'PicsInRow' => $PicsInRow, 'LastRow' => $LastRow, 'HideUntilVote' => $HideUntilVote, 'ShowOnlyUsersVotes' => $ShowOnlyUsersVotes, 'HideInfo' => $HideInfo, 'ActivateUpload' => $ActivateUpload, 'ContestEnd' => $ContestEnd,
            'ForwardToURL' => $ForwardToURL, 'ForwardFrom' => $ForwardFrom, 'ForwardType' => $ForwardType,
            'ActivatePostMaxMB' => $ActivatePostMaxMB, 'PostMaxMB' => $PostMaxMB, 'ActivateBulkUpload' => $ActivateBulkUpload,
            'BulkUploadQuantity' => $BulkUploadQuantity, 'BulkUploadMinQuantity' => $BulkUploadMinQuantity, 'CheckIp' => $CheckIp, 'CheckCookie' => $CheckCookie,
            'MaxResICOon' => $MaxResICOon, 'MaxResICOwidth' => $MaxResICOwidth, 'MaxResICOheight' => $MaxResICOheight,
            'ActivatePostMaxMBfile' => $ActivatePostMaxMBfile, 'PostMaxMBfile' => $PostMaxMBfile
            ),
        array('id' => $id),
        array('%d', '%s', '%d', '%d', '%d',
            '%d', '%d', '%d',
            '%d', '%d', '%d',
            '%d', '%d', '%d',
            '%d', '%d', '%d', '%d', '%d', '%d',
            '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d',
            '%d', '%d', '%d', '%d', '%d', '%d',
            '%d', '%d', '%d', '%d', '%d', '%d',
            '%d', '%d', '%d', '%d', '%d', '%d',
            '%d', '%d', '%d', '%d', '%d', '%d', '%d',
            '%d', '%d', '%d', '%d', '%d', '%d', '%d',
            '%d', '%d', '%d',
            '%d', '%d', '%d', '%d', '%d', '%d', '%d',
            '%d', '%d', '%d',
            '%d', '%d'
            ),
        array('%d')//HINZUFÜGEN WEITERER STRINGS NICHT MÖGLICH, FÜR STRINGS UPDATE GLEICH UNTEN VERWENDEN
    );

    // Extra update von STRINGS hier notwendig
    $wpdb->update(
        "$tablenameOptions",
        array('FbLikeGoToGalleryLink' => $FbLikeGoToGalleryLink, 'CheckCookieAlertMessage' => $CheckCookieAlertMessage, 'SliderLook' => $SliderLook, 'SliderLookOrder' => $SliderLookOrder, 'RegistryUserRole' => $RegistryUserRole),
        array('id' => $id),
        array('%s', '%s', '%d', '%d', '%s'),
        array('%d')
    );

    // input Options

    // Values which should not be saved if not sended
    $unsavingValuesInput = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tablenameOptionsInput WHERE GalleryID = %d",[$id]));

    $Forward_URL = $unsavingValuesInput->Forward_URL;
    $confirmation_text = $unsavingValuesInput->Confirmation_Text;

    $Forward = (isset($_POST['forward'])) ? '1' : '0';
    $ShowFormAfterUpload = (!empty($_POST['ShowFormAfterUpload'])) ? 1 : 0;
    $Forward_URL = (isset($_POST['forward_url'])) ? contest_gal1ery_htmlentities_and_preg_replace($_POST['forward_url']) : $Forward_URL;
    $confirmation_text = (isset($_POST['confirmation_text'])) ? contest_gal1ery_htmlentities_and_preg_replace($_POST['confirmation_text']) : $confirmation_text;

    // input Options --- ENDE

    //$querySETvaluesInputOptions = "UPDATE $tablenameOptionsInput SET $Forward_URL $confirmation_text Forward = '$Forward' WHERE id = '$id'";
    //$wpdb->query($querySETvaluesInputOptions);

    $wpdb->update(
        "$tablenameOptionsInput",
        array('Forward' => $Forward, 'Forward_URL' => $Forward_URL, 'Confirmation_Text' => $confirmation_text, 'ShowFormAfterUpload' => $ShowFormAfterUpload),
        array('GalleryID' => $id),
        array('%d', '%s', '%s', '%d'),
        array('%d')
    );

    // Save changes in table name admin

    $content = contest_gal1ery_htmlentities_and_preg_replace($_POST['InformAdminText']);

    // Magic Quotes on?
    // for old PHP versions less then 5.4.0
    // https://stackoverflow.com/questions/30736367/php-how-to-detect-magic-quotes-parameter-on-runtime
    if(function_exists('get_magic_quotes_gpc')){
        if (get_magic_quotes_gpc()) { // eingeschaltet?
            $_POST["from"] = stripslashes(isset($_POST['from']) ? $_POST['from'] : '');
            $_POST["reply"] = stripslashes(isset($_POST['reply']) ? $_POST['reply'] : '');
            $_POST["AdminMail"] = stripslashes(isset($_POST['AdminMail']) ? $_POST['AdminMail'] : '');
            $_POST["cc"] = stripslashes(isset($_POST['cc']) ? $_POST['cc'] : '');
            $_POST["bcc"] = stripslashes(isset($_POST['bcc']) ? $_POST['bcc'] : '');
            $_POST["url"] = stripslashes(isset($_POST['url']) ? $_POST['url'] : '');
        }
    }

    // Escape values wordpress sql
    $from = sanitize_text_field(isset($_POST['from']) ? $_POST['from'] : '');
    $reply = sanitize_text_field(isset($_POST['reply']) ? $_POST['reply'] : '');
    $AdminMail = sanitize_text_field(isset($_POST['AdminMail']) ? $_POST['AdminMail'] : '');
    $cc = sanitize_text_field(isset($_POST['cc']) ? $_POST['cc'] : '');
    $bcc = sanitize_text_field(isset($_POST['bcc']) ? $_POST['bcc'] : '');
    $header = sanitize_text_field(contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['header']) ? $_POST['header'] : ''));
    $url = sanitize_text_field(isset($_POST['url']) ? $_POST['url'] : '');

    // Make htmlspecialchars
    htmlentities($from);
    htmlentities($reply);
    htmlentities($AdminMail);
    htmlentities($cc);
    htmlentities($bcc);
    htmlentities($url);

    $wpdb->update(
        "$tablename_mail_admin",
        array(
            'Admin' => "$from", 'AdminMail' => "$AdminMail", 'Header' => "$header", 'Reply' => "$reply", 'BCC' => "$bcc",
            'CC' => "$cc", 'URL' => "$url", 'Content' => "$content"
        ),
        array('GalleryID' => $id),
        array('%s', '%s', '%s', '%s', '%s',
            '%s', '%s', '%s'),
        array('%d')
    );

    // Save changes in table mail user upload
    $content = contest_gal1ery_htmlentities_and_preg_replace($_POST['InformUserUploadContent']);

    // Magic Quotes on?
    // for old PHP versions less then 5.4.0
    // https://stackoverflow.com/questions/30736367/php-how-to-detect-magic-quotes-parameter-on-runtime
    if(function_exists('get_magic_quotes_gpc')){
        if (get_magic_quotes_gpc()) { // eingeschaltet?
            $_POST["InformUserUploadReply"] = stripslashes(isset($_POST['InformUserUploadReply']) ? $_POST['InformUserUploadReply'] : '');
            $_POST["InformUserUploadCC"] = stripslashes(isset($_POST['InformUserUploadCC']) ? $_POST['InformUserUploadCC'] : '');
            $_POST["InformUserUploadBCC"] = stripslashes(isset($_POST['InformUserUploadBCC']) ? $_POST['InformUserUploadBCC'] : '');
        }
    }

    // Escape values wordpress sql
    $reply = sanitize_text_field(isset($_POST['InformUserUploadReply']) ? $_POST['InformUserUploadReply'] : '');
    $cc = sanitize_text_field(isset($_POST['InformUserUploadCC']) ? $_POST['InformUserUploadCC'] : '');
    $bcc = sanitize_text_field(isset($_POST['InformUserUploadBCC']) ? $_POST['InformUserUploadBCC'] : '');
    $header = sanitize_text_field(contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['InformUserUploadHeader']) ? $_POST['InformUserUploadHeader'] : ''));
    $subject = sanitize_text_field(contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['InformUserUploadSubject']) ? $_POST['InformUserUploadSubject'] : ''));

    // Make htmlspecialchars
    htmlentities($reply);
    htmlentities($cc);
    htmlentities($bcc);

    $InformUserUpload = (!empty($_POST['InformUserUpload'])) ? 1 : 0;
    $InformUserContentInfoWithoutFileSource = (!empty($_POST['InformUserContentInfoWithoutFileSource'])) ? 1 : 0;

    $wpdb->update(
        "$tablename_mail_user_upload",
        array(
            'InformUserUpload' => "$InformUserUpload",  'Header' => "$header",
            'Subject' => "$subject", 'Reply' => "$reply", 'cc' => "$cc",
            'BCC' => "$bcc", 'Content' => "$content", 'ContentInfoWithoutFileSource' => "$InformUserContentInfoWithoutFileSource"
        ),
        array('GalleryID' => $id),
        array('%s', '%s', '%s', '%s', '%s',
            '%s', '%s', '%s'),
        array('%d')
    );

    // Save changes in table mail user comment
    $content = contest_gal1ery_htmlentities_and_preg_replace($_POST['InformUserCommentContent']);

    if(function_exists('get_magic_quotes_gpc')){
        if (get_magic_quotes_gpc()) { // eingeschaltet?
            $_POST["InformUserCommentReply"] = stripslashes(isset($_POST['InformUserCommentReply']) ? $_POST['InformUserCommentReply'] : '');
            $_POST["InformUserCommentCC"] = stripslashes(isset($_POST['InformUserCommentCC']) ? $_POST['InformUserCommentCC'] : '');
            $_POST["InformUserCommentBCC"] = stripslashes(isset($_POST['InformUserCommentBCC']) ? $_POST['InformUserCommentBCC'] : '');
        }
    }

    // Escape values wordpress sql
    $reply = sanitize_text_field(isset($_POST['InformUserCommentReply']) ? $_POST['InformUserCommentReply'] : '');
    $cc = sanitize_text_field(isset($_POST['InformUserCommentCC']) ? $_POST['InformUserCommentCC'] : '');
    $bcc = sanitize_text_field(isset($_POST['InformUserCommentBCC']) ? $_POST['InformUserCommentBCC'] : '');
    $header = sanitize_text_field(contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['InformUserCommentHeader']) ? $_POST['InformUserCommentHeader'] : ''));
    $subject = sanitize_text_field(contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['InformUserCommentSubject']) ? $_POST['InformUserCommentSubject'] : ''));
    $url = sanitize_text_field(contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['InformUserCommentURL']) ? $_POST['InformUserCommentURL'] : ''));
    $InformUserCommentMailInterval = sanitize_text_field(contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['InformUserCommentMailInterval']) ? $_POST['InformUserCommentMailInterval'] : ''));

    // Make htmlspecialchars
    htmlentities($reply);
    htmlentities($cc);
    htmlentities($bcc);

    $InformUserComment = (!empty($_POST['InformUserComment'])) ? 1 : 0;

    $wpdb->update(
        "$tablename_mail_user_comment",
        array(
            'InformUserComment' => "$InformUserComment",  'Header' => "$header",
            'Subject' => "$subject", 'Reply' => "$reply", 'cc' => "$cc",
            'BCC' => "$bcc", 'Content' => "$content", 'URL' => "$url", 'MailInterval' => "$InformUserCommentMailInterval"
        ),
        array('GalleryID' => $id),
        array('%s', '%s', '%s', '%s', '%s',
            '%s', '%s', '%s', '%s'),
        array('%d')
    );

    // Save changes in table mail user vote
    $InformUserContent = contest_gal1ery_htmlentities_and_preg_replace($_POST['InformUserVoteContent']);

    if(function_exists('get_magic_quotes_gpc')){
        if (get_magic_quotes_gpc()) { // eingeschaltet?
            $_POST["InformUserVoteReply"] = stripslashes(isset($_POST['InformUserVoteReply']) ? $_POST['InformUserVoteReply'] : '');
            $_POST["InformUserVoteCC"] = stripslashes(isset($_POST['InformUserVoteCC']) ? $_POST['InformUserVoteCC'] : '');
            $_POST["InformUserVoteBCC"] = stripslashes(isset($_POST['InformUserVoteBCC']) ? $_POST['InformUserVoteBCC'] : '');
        }
    }

    // Escape values wordpress sql
    $reply = sanitize_text_field(isset($_POST['InformUserVoteReply']) ? $_POST['InformUserVoteReply'] : '');
    $cc = sanitize_text_field(isset($_POST['InformUserVoteCC']) ? $_POST['InformUserVoteCC'] : '');
    $bcc = sanitize_text_field(isset($_POST['InformUserVoteBCC']) ? $_POST['InformUserVoteBCC'] : '');
    $header = sanitize_text_field(contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['InformUserVoteHeader']) ? $_POST['InformUserVoteHeader'] : ''));
    $subject = sanitize_text_field(contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['InformUserVoteSubject']) ? $_POST['InformUserVoteSubject'] : ''));
    $url = sanitize_text_field(contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['InformUserVoteURL']) ? $_POST['InformUserVoteURL'] : ''));
    $InformUserVoteMailInterval = sanitize_text_field(contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['InformUserVoteMailInterval']) ? $_POST['InformUserVoteMailInterval'] : ''));

    // Make htmlspecialchars
    htmlentities($reply);
    htmlentities($cc);
    htmlentities($bcc);

    $InformUserVote = (!empty($_POST['InformUserVote'])) ? 1 : 0;

    $wpdb->update(
        "$tablename_mail_user_vote",
        array(
            'InformUserVote' => "$InformUserVote",  'Header' => "$header",
            'Subject' => "$subject", 'Reply' => "$reply", 'cc' => "$cc",
            'BCC' => "$bcc", 'Content' => "$InformUserContent", 'URL' => "$url", 'MailInterval' => "$InformUserVoteMailInterval"
        ),
        array('GalleryID' => $id),
        array('%s', '%s', '%s', '%s', '%s',
            '%s', '%s', '%s', '%s'),
        array('%d')
    );


    if (!empty($_POST['InformAdmin'])) {

        //Echo "works";
        $InformAdmin = 1;

        $wpdb->update(
            "$tablenameOptions",
            array('InformAdmin' => '1'),
            array('id' => $id),
            array('%d'),
            array('%d')
        );

    } else {
        $InformAdmin = 0;

        $wpdb->update(
            "$tablenameOptions",
            array('InformAdmin' => '0'),
            array('id' => $id),
            array('%d'),
            array('%d')
        );

    }

    // Save changes in table name admin --- ENDE

    // Save changes in table user mail

        $contentUserMail = contest_gal1ery_htmlentities_and_preg_replace($_POST['cgEmailImageActivating']);

        //$content = htmlentities($content, ENT_QUOTES);

        // for old PHP versions less then 5.4.0
        // https://stackoverflow.com/questions/30736367/php-how-to-detect-magic-quotes-parameter-on-runtime
        if(function_exists('get_magic_quotes_gpc')){

            // Magic Quotes on?
            if (get_magic_quotes_gpc()) { // eingeschaltet?
                $_POST["from_user_mail"] = stripslashes(isset($_POST['from_user_mail']) ? $_POST['from_user_mail'] : '');
                $_POST["reply_user_mail"] = stripslashes(isset($_POST['reply_user_mail']) ? $_POST['reply_user_mail'] : '');
                $_POST["cc_user_mail"] = stripslashes(isset($_POST['cc_user_mail']) ? $_POST['cc_user_mail'] : '');
                $_POST["bcc_user_mail"] = stripslashes(isset($_POST['bcc_user_mail']) ? $_POST['bcc_user_mail'] : '');
                $_POST["url_user_mail"] = stripslashes(isset($_POST['url_user_mail']) ? $_POST['url_user_mail'] : '');
            }


        }


        // Escape values wordpress sql

        $from = sanitize_text_field(isset($_POST['from_user_mail']) ? $_POST['from_user_mail'] : '');
        $reply = sanitize_text_field(isset($_POST['reply_user_mail']) ? $_POST['reply_user_mail'] : '');
        $cc = sanitize_text_field(isset($_POST['cc_user_mail']) ? $_POST['cc_user_mail'] : '');
        $bcc = sanitize_text_field(isset($_POST['bcc_user_mail']) ? $_POST['bcc_user_mail'] : '');
        $header = sanitize_text_field(contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['header_user_mail']) ? $_POST['header_user_mail'] : ''));
        $url = sanitize_text_field(isset($_POST['url_user_mail']) ? $_POST['url_user_mail'] : '');
        //$content = sanitize_text_field($content); <<< ansonten verschieden html eingaben wie <br> und andere

        // Make htmlspecialchars

        htmlentities($from);
        htmlentities($reply);
        htmlentities($cc);
        htmlentities($bcc);
        htmlentities($url);
        //htmlentities($content); <<< ansonten verschieden html eingaben wie <br> und andere


        //$querySETemail = "UPDATE $tablenameemail SET Admin='$from', Header = '$header', Reply='$reply', BCC='$bcc',
        //CC='$cc', URL='$url', Content='$content' WHERE GalleryID = '$GalleryID' ";
        //$updateSQLemail = $wpdb->query($querySETemail);

        $wpdb->update(
            "$tablenameemail",
            array(
                'Admin' => "$from", 'Header' => "$header", 'Reply' => "$reply", 'BCC' => "$bcc",
                'CC' => "$cc", 'URL' => "$url", 'Content' => "$contentUserMail"
            ),
            array('GalleryID' => $id),
            array('%s', '%s', '%s', '%s',
                '%s', '%s', '%s'),
            array('%d')
        );


    // Save Pro options here

    $ForwardAfterLoginUrlCheck = (!empty($_POST['ForwardAfterLoginUrlCheck'])) ? '1' : '0';
    $ForwardAfterLoginTextCheck = (!empty($_POST['ForwardAfterLoginTextCheck'])) ? '1' : '0';
    $RegUserUploadOnly = (isset($_POST['RegUserUploadOnly'])) ? $_POST['RegUserUploadOnly'] : 0;//1=login tracking, 2=cookie,3=ip

    $RegUserGalleryOnly = (!empty($_POST['RegUserGalleryOnly'])) ? '1' : '0';
    $VoteNotOwnImage = (!empty($_POST['VoteNotOwnImage'])) ? '1' : '0';

    $AllowUploadJPG = (!empty($_POST['AllowUploadJPG'])) ? 1 : 0;
    $AllowUploadPNG = (!empty($_POST['AllowUploadPNG'])) ? 1 : 0;
    $AllowUploadGIF = (!empty($_POST['AllowUploadGIF'])) ? 1 : 0;
    $AllowUploadICO = (!empty($_POST['AllowUploadICO'])) ? 1 : 0;

    $AdditionalFiles = (!empty($_POST['AdditionalFiles'])) ? 1 : 0;
    $AdditionalFilesCount = (!empty($_POST['AdditionalFilesCount'])) ? intval($_POST['AdditionalFilesCount']) : $AdditionalFilesCount;

    // var_dump($_POST['RegUserUploadOnlyText']);die;

    $ForwardAfterLoginUrl = (isset($_POST['ForwardAfterLoginUrl'])) ? contest_gal1ery_htmlentities_and_preg_replace($_POST['ForwardAfterLoginUrl']) : $ForwardAfterLoginUrl;
    $ForwardAfterLoginText = (isset($_POST['ForwardAfterLoginText'])) ? contest_gal1ery_htmlentities_and_preg_replace($_POST['ForwardAfterLoginText']) : $ForwardAfterLoginText;
    $RegUserUploadOnlyText = (isset($_POST['RegUserUploadOnlyText'])) ? contest_gal1ery_htmlentities_and_preg_replace($_POST['RegUserUploadOnlyText']) : $RegUserUploadOnlyText;
    $RegUserGalleryOnlyText = (isset($_POST['RegUserGalleryOnlyText'])) ? contest_gal1ery_htmlentities_and_preg_replace($_POST['RegUserGalleryOnlyText']) : $RegUserGalleryOnlyText;

    $ForwardAfterRegUrl = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['ForwardAfterRegUrl']) ? $_POST['ForwardAfterRegUrl'] : '');
    $ForwardAfterRegText = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['ForwardAfterRegText']) ? $_POST['ForwardAfterRegText'] : '');
    $TextEmailConfirmation = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['TextEmailConfirmation']) ? $_POST['TextEmailConfirmation'] : '');

    $TextAfterEmailConfirmation = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['TextAfterEmailConfirmation']) ? $_POST['TextAfterEmailConfirmation'] : '');
    $RegMailAddressor = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['RegMailAddressor']) ? $_POST['RegMailAddressor'] : '');
    $RegMailReply = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['RegMailReply']) ? $_POST['RegMailReply'] : '');
    $RegMailSubject = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['RegMailSubject']) ? $_POST['RegMailSubject'] : '');

    $InformAdminAllowActivateDeactivate = (!empty($_POST['InformAdminAllowActivateDeactivate'])) ? 1 : 0;
    $InformAdminActivationURL = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['InformAdminActivationURL']) ? $_POST['InformAdminActivationURL'] : '');

    $RegUserMaxUpload = (isset($_POST['RegUserMaxUpload'])) ? sanitize_text_field($_POST['RegUserMaxUpload']) : $RegUserMaxUpload;
    $RegUserMaxUploadPerCategory = (isset($_POST['RegUserMaxUploadPerCategory'])) ? sanitize_text_field($_POST['RegUserMaxUploadPerCategory']) : $RegUserMaxUploadPerCategory;

    $PreselectSort = (isset($_POST['PreselectSort'])) ? sanitize_text_field($_POST['PreselectSort']) : 'date_descend';
    $UploadRequiresCookieMessage = (isset($_POST['UploadRequiresCookieMessage'])) ? sanitize_text_field($_POST['UploadRequiresCookieMessage']) : '';

    $RegMailOptional = (isset($_POST['RegMailOptional'])) ? 1 : 0;

    $DeleteFromStorageIfDeletedInFrontend = (isset($_POST['DeleteFromStorageIfDeletedInFrontend'])) ? 1 : 0;

    $VotesPerCategory = (!empty($_POST['VotesPerCategory'])) ? sanitize_text_field($_POST['VotesPerCategory']) : 0;

    $optionsForGeneralIDsinceV14['pro'] = [];
    $optionsForGeneralIDsinceV14['pro']['ForwardAfterLoginUrlCheck'] = $ForwardAfterLoginUrlCheck;
    $optionsForGeneralIDsinceV14['pro']['ForwardAfterLoginUrl'] = $ForwardAfterLoginUrl;
    $optionsForGeneralIDsinceV14['pro']['ForwardAfterLoginTextCheck'] = $ForwardAfterLoginTextCheck;
    $optionsForGeneralIDsinceV14['pro']['ForwardAfterLoginText'] = $ForwardAfterLoginText;
    $optionsForGeneralIDsinceV14['pro']['RegMailOptional'] = $RegMailOptional;
    $optionsForGeneralIDsinceV14['pro']['ForwardAfterRegText'] = $ForwardAfterRegText;
    $optionsForGeneralIDsinceV14['pro']['TextAfterEmailConfirmation'] = $TextAfterEmailConfirmation;
    $optionsForGeneralIDsinceV14['pro']['HideRegFormAfterLogin'] = $HideRegFormAfterLogin;
    $optionsForGeneralIDsinceV14['pro']['HideRegFormAfterLoginShowTextInstead'] = $HideRegFormAfterLoginShowTextInstead;
    $optionsForGeneralIDsinceV14['pro']['HideRegFormAfterLoginTextToShow'] = $HideRegFormAfterLoginTextToShow;
    $optionsForGeneralIDsinceV14['pro']['RegMailAddressor'] = $RegMailAddressor;
    $optionsForGeneralIDsinceV14['pro']['RegMailReply'] = $RegMailReply;
    $optionsForGeneralIDsinceV14['pro']['RegMailSubject'] = $RegMailSubject;
    $optionsForGeneralIDsinceV14['pro']['TextEmailConfirmation'] = $TextEmailConfirmation;

    // save number values extra
    $wpdb->update(
        "$tablename_pro_options",
        array(
            'ForwardAfterLoginUrlCheck' => $ForwardAfterLoginUrlCheck,'ForwardAfterLoginTextCheck' => $ForwardAfterLoginTextCheck,
            'Manipulate' => $Manipulate, 'Search' => $Search,
            'GalleryUpload' => $GalleryUpload,'ShowNickname' => $ShowNickname,
            'MinusVote' => $MinusVote, 'VotesInTime' => $VotesInTime,
            'VotesInTimeQuantity' => $VotesInTimeQuantity,'VotesInTimeIntervalSeconds' => $VotesInTimeIntervalSeconds,
            'ShowExif' => $ShowExif,'SliderFullWindow' => $SliderFullWindow,
            'HideRegFormAfterLogin' => $HideRegFormAfterLogin, 'HideRegFormAfterLoginShowTextInstead' => $HideRegFormAfterLoginShowTextInstead,
            'RegUserGalleryOnly' => $RegUserGalleryOnly,'RegUserMaxUpload' => $RegUserMaxUpload,
            'GalleryUploadOnlyUser' => $GalleryUploadOnlyUser,'FbLikeNoShare' => $FbLikeNoShare,
            'VoteNotOwnImage' => $VoteNotOwnImage,'RegMailOptional' => $RegMailOptional,
            'CustomImageName' => $CustomImageName,'RegUserUploadOnly' => $RegUserUploadOnly,
            'FbLikeOnlyShare' => $FbLikeOnlyShare,'DeleteFromStorageIfDeletedInFrontend' => $DeleteFromStorageIfDeletedInFrontend,
            'VotePerCategory' => $VotePerCategory,'VotesPerCategory' => $VotesPerCategory,
            'VoteMessageSuccessActive' => $VoteMessageSuccessActive,'VoteMessageWarningActive' => $VoteMessageWarningActive,
            'CommNoteActive' => $CommNoteActive,'ShowProfileImage' => $ShowProfileImage,
            'AllowUploadJPG' => $AllowUploadJPG,'AllowUploadPNG' => $AllowUploadPNG,
            'AllowUploadGIF' => $AllowUploadGIF,'AllowUploadICO' => $AllowUploadICO,
            'AdditionalFiles' => $AdditionalFiles,'AdditionalFilesCount' => $AdditionalFilesCount,
            'ReviewComm' => $ReviewComm, 'InformAdminAllowActivateDeactivate' => $InformAdminAllowActivateDeactivate, 'RegUserMaxUploadPerCategory' => $RegUserMaxUploadPerCategory
        ),
        array('GalleryID' => $id),
        array(
            '%d','%d',
            '%d','%d',
            '%d','%d',
            '%d','%d',
            '%d','%d',
            '%d','%d',
            '%d','%d',
            '%d','%d',
            '%d','%d',
            '%d','%d',
            '%d','%d',
            '%d','%d',
            '%d','%d',
            '%d','%d',
            '%d','%d',
            '%d','%d',
            '%d','%d',
            '%d','%d',
            '%d','%d','%d'
        ),
        array('%d')
    );

    // save string values extra
    $wpdb->update(
        "$tablename_pro_options",
        array(
            'ForwardAfterLoginUrl' => $ForwardAfterLoginUrl,'ForwardAfterRegText' => $ForwardAfterRegText,
            'ForwardAfterRegUrl' => $ForwardAfterRegUrl,'ForwardAfterRegText' => $ForwardAfterRegText,
            'ForwardAfterLoginText' => $ForwardAfterLoginText,'TextEmailConfirmation' => $TextEmailConfirmation,
            'TextAfterEmailConfirmation' => $TextAfterEmailConfirmation,'RegMailAddressor' => $RegMailAddressor,
            'RegMailReply' => $RegMailReply, 'RegMailSubject' => $RegMailSubject,
            'RegUserUploadOnlyText' => $RegUserUploadOnlyText,'GalleryUploadTextBefore' => $GalleryUploadTextBefore,
            'GalleryUploadTextAfter' => $GalleryUploadTextAfter,'GalleryUploadConfirmationText' => $GalleryUploadConfirmationText,

            'SlideTransition' => $SlideTransition,'VotesInTimeIntervalReadable' => $VotesInTimeIntervalReadable,
            'VotesInTimeIntervalAlertMessage' => $VotesInTimeIntervalAlertMessage,'HideRegFormAfterLoginTextToShow' => $HideRegFormAfterLoginTextToShow,
            'RegUserGalleryOnlyText' => $RegUserGalleryOnlyText,'PreselectSort' => $PreselectSort,
            'UploadRequiresCookieMessage' => $UploadRequiresCookieMessage,'CustomImageNamePath' => $CustomImageNamePath,
            'VoteMessageSuccessText' => $VoteMessageSuccessText,'VoteMessageWarningText' => $VoteMessageWarningText,
            'BackToGalleryButtonURL' => $BackToGalleryButtonURL,'WpPageParentRedirectURL' => $WpPageParentRedirectURL,
            'RedirectURLdeletedEntry' => $RedirectURLdeletedEntry, 'InformAdminActivationURL' => $InformAdminActivationURL
        ),
        array('GalleryID' => $id),
        array(
            '%s','%s',
            '%s','%s',
            '%s','%s',
            '%s','%s',
            '%s','%s',
            '%s','%s',

            '%s','%s',
            '%s','%s',
            '%s','%s',
            '%s','%s',
            '%s','%s',
            '%s','%s',
            '%s','%s'
        ),
        array('%d')
    );

  //  var_dump($wpdb->last_query);die;


    // Save Pro options here --- ENDE

    // Save changes in table user confirmation mail
     if($dbVersion<14){

         $mConfirmContent = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['mConfirmContent']) ? $_POST['mConfirmContent'] : '');
         $mConfirmConfirmationText = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['mConfirmConfirmationText']) ? $_POST['mConfirmConfirmationText'] : '');

         //$content = htmlentities($content, ENT_QUOTES);

         // for old PHP versions less then 5.4.0
         // https://stackoverflow.com/questions/30736367/php-how-to-detect-magic-quotes-parameter-on-runtime
         if(function_exists('get_magic_quotes_gpc')){

             // Magic Quotes on?
             if (get_magic_quotes_gpc()) { // eingeschaltet?
                 $_POST["mConfirmAdmin"] = stripslashes(isset($_POST['mConfirmAdmin']) ? $_POST['mConfirmAdmin'] : '');
                 $_POST["mConfirmReply"] = stripslashes(isset($_POST['mConfirmReply']) ? $_POST['mConfirmReply'] : '');
                 $_POST["mConfirmCC"] = stripslashes(isset($_POST['mConfirmCC']) ? $_POST['mConfirmCC'] : '');
                 $_POST["mConfirmBCC"] = stripslashes(isset($_POST['mConfirmBCC']) ? $_POST['mConfirmBCC'] : '');
                 $_POST["mConfirmURL"] = stripslashes(isset($_POST['mConfirmURL']) ? $_POST['mConfirmURL'] : '');
                 //	echo "<br>ja<br>";
             }
             //	stripslashes($content);
             //	echo "<br>content2: $content<br>";
         }

         $mConfirmSendConfirm = (isset($_POST['mConfirmSendConfirm'])) ? '1' : '0';

         // Escape values wordpress sql

         $mConfirmAdmin = sanitize_text_field(isset($_POST['mConfirmAdmin']) ? $_POST['mConfirmAdmin'] : '');
         $mConfirmReply = sanitize_text_field(isset($_POST['mConfirmReply']) ? $_POST['mConfirmReply'] : '');
         $mConfirmCC = sanitize_text_field(isset($_POST['mConfirmCC']) ? $_POST['mConfirmCC'] : '');
         $mConfirmBCC = sanitize_text_field(isset($_POST['mConfirmBCC']) ? $_POST['mConfirmBCC'] : '');
         $mConfirmHeader = sanitize_text_field(contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['mConfirmHeader']) ? $_POST['mConfirmHeader'] : ''));
         $mConfirmURL = sanitize_text_field(isset($_POST['mConfirmURL']) ? $_POST['mConfirmURL'] : '');
         //$content = sanitize_text_field($content); <<< ansonten verschieden html eingaben wie <br> und andere

         // Make htmlspecialchars

         htmlentities($mConfirmAdmin);
         htmlentities($mConfirmReply);
         htmlentities($mConfirmCC);
         htmlentities($mConfirmBCC);
         htmlentities($mConfirmURL);
         //htmlentities($content); <<< ansonten verschieden html eingaben wie <br> und andere


         //$querySETemail = "UPDATE $tablenameemail SET Admin='$from', Header = '$header', Reply='$reply', BCC='$bcc',
         //CC='$cc', URL='$url', Content='$content' WHERE GalleryID = '$GalleryID' ";
         //$updateSQLemail = $wpdb->query($querySETemail);

         $wpdb->update(
             "$tablename_mail_confirmation",
             array(
                 'Admin' => "$mConfirmAdmin", 'Header' => "$mConfirmHeader", 'Reply' => "$mConfirmReply", 'BCC' => "$mConfirmBCC",
                 'CC' => "$mConfirmCC", 'URL' => "$mConfirmURL", 'Content' => "$mConfirmContent", 'ConfirmationText' => "$mConfirmConfirmationText",
                 'SendConfirm' => "$mConfirmSendConfirm"
             ),
             array('GalleryID' => $id),
             array('%s', '%s', '%s', '%s',
                 '%s', '%s', '%s', '%s', '%d'),
             array('%d')
         );

    }
    // Save changes in table user comments mail --- ENDE

    // Update google options
    $unsavingValues = $wpdb->get_row("SELECT * FROM $tablenameGoogleOptions WHERE GeneralID = '1'");
    $ClientId = $unsavingValues->ClientId;
    $ButtonTextOnLoad = $unsavingValues->ButtonTextOnLoad;
    $ButtonStyle = $unsavingValues->ButtonStyle;

    $ClientId = sanitize_text_field(isset($_POST['GoogleClientId']) ? $_POST['GoogleClientId'] : $ClientId);
    $ButtonTextOnLoad = sanitize_text_field(isset($_POST['GoogleButtonTextOnLoad']) ? $_POST['GoogleButtonTextOnLoad'] : $ButtonTextOnLoad);
    $ButtonStyle = sanitize_text_field(isset($_POST['GoogleButtonStyle']) ? $_POST['GoogleButtonStyle'] : $ButtonStyle);
    $GoogleButtonBorderRadius = (!empty($_POST['GoogleButtonBorderRadius'])) ? '1' : '0';
    $FeControlsStyleGoogleSignIn = (!empty($_POST['FeControlsStyleWhiteGoogleSignIn'])) ? 'white' : 'black';
    $TextBeforeGoogleSignInButton = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['TextBeforeGoogleSignInButton']) ? $_POST['TextBeforeGoogleSignInButton'] : '');

    $wpdb->update(
        $tablenameGoogleOptions,
        array(
            'ClientId' => $ClientId,'ButtonTextOnLoad' => $ButtonTextOnLoad,
            'ButtonStyle' => $ButtonStyle, 'BorderRadius' => $GoogleButtonBorderRadius,
            'FeControlsStyle' => $FeControlsStyleGoogleSignIn,'TextBeforeGoogleSignInButton' => $TextBeforeGoogleSignInButton
        ),
        array('GeneralID' => 1),
        array('%s','%s',
            '%s', '%d', '%s', '%s'),
        array('%d')
    );

    // Update google options --- END


        $CommNoteAddressor = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['CommNoteAddressor']) ? $_POST['CommNoteAddressor'] : '');
        $CommNoteAdminMail = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['CommNoteAdminMail']) ? $_POST['CommNoteAdminMail'] : '');
        $CommNoteCC = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['CommNoteCC']) ? $_POST['CommNoteCC'] : '');
        $CommNoteBCC = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['CommNoteBCC']) ? $_POST['CommNoteBCC'] : '');
        $CommNoteReply = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['CommNoteReply']) ? $_POST['CommNoteReply'] : '');
        $CommNoteSubject = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['CommNoteSubject']) ? $_POST['CommNoteSubject'] : '');
        $CommNoteContent = sanitize_text_field(contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['CommNoteContent']) ? $_POST['CommNoteContent'] : ''));

        $wpdb->update(
            "$tablename_comments_notification_options",
            array(
                'CommNoteAddressor' => $CommNoteAddressor,'CommNoteAdminMail' => $CommNoteAdminMail,
                'CommNoteCC' => $CommNoteCC,'CommNoteBCC' => $CommNoteBCC,'CommNoteReply' => $CommNoteReply,
                'CommNoteSubject' => $CommNoteSubject,'CommNoteContent' => $CommNoteContent
            ),
            array('GalleryID' => $id),
            array(
                    '%s', '%s',
                '%s', '%s','%s',
                '%s', '%s'
            ),
            array('%d')
        );

    // Save changes in table user comments mail --- END

        $LogoutLink = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['LogoutLink']) ? $_POST['LogoutLink'] : '');
    $BackToGalleryLink = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['BackToGalleryLink']) ? $_POST['BackToGalleryLink'] : '');
        $LostPasswordMailAddressor = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['LostPasswordMailAddressor']) ? $_POST['LostPasswordMailAddressor'] : '');
        $LostPasswordMailReply = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['LostPasswordMailReply']) ? $_POST['LostPasswordMailReply'] : '');
        $LostPasswordMailSubject = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['LostPasswordMailSubject']) ? $_POST['LostPasswordMailSubject'] : '');
        $LostPasswordMailConfirmation = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['LostPasswordMailConfirmation']) ? $_POST['LostPasswordMailConfirmation'] : '');
        $TextBeforeLoginForm = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['TextBeforeLoginForm']) ? $_POST['TextBeforeLoginForm'] : '');
    $TextBeforeRegFormBeforeLoggedIn = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['TextBeforeRegFormBeforeLoggedIn']) ? $_POST['TextBeforeRegFormBeforeLoggedIn'] : '');
    $PermanentTextWhenLoggedIn = contest_gal1ery_htmlentities_and_preg_replace(isset($_POST['PermanentTextWhenLoggedIn']) ? $_POST['PermanentTextWhenLoggedIn'] : '');

    $optionsForGeneralIDsinceV14['registry-login'] = [];
    $optionsForGeneralIDsinceV14['registry-login']['LogoutLink'] = $LogoutLink;
    $optionsForGeneralIDsinceV14['registry-login']['BackToGalleryLink'] = $BackToGalleryLink;
    $optionsForGeneralIDsinceV14['registry-login']['RegistryUserRole'] = $RegistryUserRoleForRegistryAndLoginOptions;
    $optionsForGeneralIDsinceV14['registry-login']['LostPasswordMailActive'] = (isset($_POST['LostPasswordMailActive'])) ? 1 : 0;
    $optionsForGeneralIDsinceV14['registry-login']['LostPasswordMailAddressor'] = $LostPasswordMailAddressor;
    $optionsForGeneralIDsinceV14['registry-login']['LostPasswordMailReply'] = $LostPasswordMailReply;
    $optionsForGeneralIDsinceV14['registry-login']['LostPasswordMailSubject'] = $LostPasswordMailSubject;
    $optionsForGeneralIDsinceV14['registry-login']['LostPasswordMailConfirmation'] = $LostPasswordMailConfirmation;
    $optionsForGeneralIDsinceV14['registry-login']['TextBeforeLoginForm'] = $TextBeforeLoginForm;
    $optionsForGeneralIDsinceV14['registry-login']['EditProfileGroups'] = (!empty($_POST['EditProfileGroups'])) ? serialize($_POST['EditProfileGroups'])  : serialize([]);
    $optionsForGeneralIDsinceV14['registry-login']['TextBeforeRegFormBeforeLoggedIn'] = $TextBeforeRegFormBeforeLoggedIn;
    $optionsForGeneralIDsinceV14['registry-login']['PermanentTextWhenLoggedIn'] = $PermanentTextWhenLoggedIn;

    if($dbVersion>=14){
        cg_update_registry_and_login_options_v14($optionsForGeneralIDsinceV14);
    }else{
        cg_update_registry_and_login_options_v14($optionsForGeneralIDsinceV14,$id);
    }

    // Save translations

    $translations = array();

    foreach ($_POST['translations'] as $defaultKey => $translation) {

        if($defaultKey=='pro' OR $defaultKey=='general'){
            continue;
        }
        $translations[$defaultKey] = contest_gal1ery_htmlentities_and_preg_replace(trim($translation));
    }

    if(empty( $translations['pro'] )){
        $translations['pro'] = array();
    }

    // set PRO json messages here
    if(!empty($_POST['VotesPerUserAllVotesUsedHtmlMessage'])){
        $translations['pro']['VotesPerUserAllVotesUsedHtmlMessage'] =  contest_gal1ery_htmlentities_and_preg_replace(trim($_POST['VotesPerUserAllVotesUsedHtmlMessage']));
    }else {
        if(empty($translations['pro']['VotesPerUserAllVotesUsedHtmlMessage'])){
            $translations['pro']['VotesPerUserAllVotesUsedHtmlMessage'] = '';
        }
    }

    $translationsFile = $wp_upload_dir["basedir"] . "/contest-gallery/gallery-id-$id/json/$id-translations.json";

    $fp = fopen($translationsFile, 'w');
    fwrite($fp, json_encode($translations));
    fclose($fp);

    if(!empty( $_POST['translations']['general'] )){
        if(is_array( $_POST['translations']['general'])){
            $translations= array();

            foreach ($_POST['translations']['general'] as $defaultKey => $translation) {
                $translations[$defaultKey] = contest_gal1ery_htmlentities_and_preg_replace(trim($translation));
            }

            $translationsFile = $wp_upload_dir["basedir"] . "/contest-gallery/gallery-general/json/translations.json";
            $fp = fopen($translationsFile, 'w');
            fwrite($fp, json_encode($translations));
            fclose($fp);

        }
    }

    // Save translations --- ENDE

    $_POST['multiple-pics']['cg_gallery']['visual']['EnableEmojis'] = (!empty($_POST['multiple-pics']['cg_gallery']['visual']['EnableEmojis'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_user']['visual']['EnableEmojis'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['visual']['EnableEmojis'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_no_voting']['visual']['EnableEmojis'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['visual']['EnableEmojis'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_winner']['visual']['EnableEmojis'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['visual']['EnableEmojis'])) ? 1 : 0;

    $_POST['multiple-pics']['cg_gallery']['pro']['CheckLoginComment'] = (!empty($_POST['multiple-pics']['cg_gallery']['pro']['CheckLoginComment'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_user']['pro']['CheckLoginComment'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['pro']['CheckLoginComment'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_no_voting']['pro']['CheckLoginComment'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['pro']['CheckLoginComment'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_winner']['pro']['CheckLoginComment'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['pro']['CheckLoginComment'])) ? 1 : 0;

    $_POST['multiple-pics']['cg_gallery']['general']['ShowTextUntilAnImageAdded'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery']['general']['ShowTextUntilAnImageAdded']);
    $_POST['multiple-pics']['cg_gallery_user']['general']['ShowTextUntilAnImageAdded'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_user']['general']['ShowTextUntilAnImageAdded']);
    $_POST['multiple-pics']['cg_gallery_no_voting']['general']['ShowTextUntilAnImageAdded'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_no_voting']['general']['ShowTextUntilAnImageAdded']);
    $_POST['multiple-pics']['cg_gallery_winner']['general']['ShowTextUntilAnImageAdded'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_winner']['general']['ShowTextUntilAnImageAdded']);
    $ShowTextUntilAnImageAdded = $_POST['multiple-pics']['cg_gallery']['general']['ShowTextUntilAnImageAdded'];

    $_POST['multiple-pics']['cg_gallery']['general']['RatingVisibleForGalleryNoVoting'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['general']['RatingVisibleForGalleryNoVoting'])) ? 1 : 0;// have to be added to cg_gallery, cg_gallery_user and cg_gallery_winner so it will be automatically saved
    $_POST['multiple-pics']['cg_gallery_user']['general']['RatingVisibleForGalleryNoVoting'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['general']['RatingVisibleForGalleryNoVoting'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_no_voting']['general']['RatingVisibleForGalleryNoVoting'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['general']['RatingVisibleForGalleryNoVoting'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_winner']['general']['RatingVisibleForGalleryNoVoting'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['general']['RatingVisibleForGalleryNoVoting'])) ? 1 : 0;
    $RatingVisibleForGalleryNoVoting = $_POST['multiple-pics']['cg_gallery']['general']['RatingVisibleForGalleryNoVoting'];

    $_POST['multiple-pics']['cg_gallery']['general']['HideCommentNameField'] = (!empty($_POST['multiple-pics']['cg_gallery']['general']['HideCommentNameField'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_user']['general']['HideCommentNameField'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['general']['HideCommentNameField'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_no_voting']['general']['HideCommentNameField'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['general']['HideCommentNameField'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_winner']['general']['HideCommentNameField'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['general']['HideCommentNameField'])) ? 1 : 0;
    $HideCommentNameField = $_POST['multiple-pics']['cg_gallery']['general']['HideCommentNameField'];

    $ShowExifModel = (!empty($_POST['ShowExifModel'])) ? 1 : 0;
    $ShowExifApertureFNumber = (!empty($_POST['ShowExifApertureFNumber'])) ? 1 : 0;
    $ShowExifExposureTime = (!empty($_POST['ShowExifExposureTime'])) ? 1 : 0;
    $ShowExifISOSpeedRatings = (!empty($_POST['ShowExifISOSpeedRatings'])) ? 1 : 0;
    $ShowExifFocalLength = (!empty($_POST['ShowExifFocalLength'])) ? 1 : 0;
    $ShowExifDateTimeOriginal = (!empty($_POST['ShowExifDateTimeOriginal'])) ? 1 : 0;
    $ShowExifDateTimeOriginalFormat = (!empty($_POST['ShowExifDateTimeOriginalFormat'])) ? $_POST['ShowExifDateTimeOriginalFormat'] : 'YYYY-MM-DD';

    $_POST['multiple-pics']['cg_gallery']['pro']['CommNoteActive'] = $CommNoteActive;
    $_POST['multiple-pics']['cg_gallery_user']['pro']['CommNoteActive'] = $CommNoteActive;
    $_POST['multiple-pics']['cg_gallery_no_voting']['pro']['CommNoteActive'] = $CommNoteActive;
    $_POST['multiple-pics']['cg_gallery_winner']['pro']['CommNoteActive'] = $CommNoteActive;

    $_POST['multiple-pics']['cg_gallery']['visual']['EnableSwitchStyleGalleryButton'] = (!empty($_POST['multiple-pics']['cg_gallery']['visual']['EnableSwitchStyleGalleryButton'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_user']['visual']['EnableSwitchStyleGalleryButton'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['visual']['EnableSwitchStyleGalleryButton'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_no_voting']['visual']['EnableSwitchStyleGalleryButton'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['visual']['EnableSwitchStyleGalleryButton'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_winner']['visual']['EnableSwitchStyleGalleryButton'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['visual']['EnableSwitchStyleGalleryButton'])) ? 1 : 0;

    $_POST['multiple-pics']['cg_gallery']['visual']['SwitchStyleGalleryButtonOnlyTopControls'] = (!empty($_POST['multiple-pics']['cg_gallery']['visual']['SwitchStyleGalleryButtonOnlyTopControls'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_user']['visual']['SwitchStyleGalleryButtonOnlyTopControls'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['visual']['SwitchStyleGalleryButtonOnlyTopControls'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_no_voting']['visual']['SwitchStyleGalleryButtonOnlyTopControls'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['visual']['SwitchStyleGalleryButtonOnlyTopControls'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_winner']['visual']['SwitchStyleGalleryButtonOnlyTopControls'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['visual']['SwitchStyleGalleryButtonOnlyTopControls'])) ? 1 : 0;

    $_POST['multiple-pics']['cg_gallery']['visual']['EnableSwitchStyleImageViewButton'] = (!empty($_POST['multiple-pics']['cg_gallery']['visual']['EnableSwitchStyleImageViewButton'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_user']['visual']['EnableSwitchStyleImageViewButton'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['visual']['EnableSwitchStyleImageViewButton'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_no_voting']['visual']['EnableSwitchStyleImageViewButton'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['visual']['EnableSwitchStyleImageViewButton'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_winner']['visual']['EnableSwitchStyleImageViewButton'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['visual']['EnableSwitchStyleImageViewButton'])) ? 1 : 0;

    $_POST['multiple-pics']['cg_gallery']['visual']['SwitchStyleImageViewButtonOnlyImageView'] = (!empty($_POST['multiple-pics']['cg_gallery']['visual']['SwitchStyleImageViewButtonOnlyImageView'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_user']['visual']['SwitchStyleImageViewButtonOnlyImageView'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['visual']['SwitchStyleImageViewButtonOnlyImageView'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_no_voting']['visual']['SwitchStyleImageViewButtonOnlyImageView'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['visual']['SwitchStyleImageViewButtonOnlyImageView'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_winner']['visual']['SwitchStyleImageViewButtonOnlyImageView'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['visual']['SwitchStyleImageViewButtonOnlyImageView'])) ? 1 : 0;

    $_POST['multiple-pics']['cg_gallery']['visual']['ShowBackToGalleryButton'] = $ShowBackToGalleryButton;
    $_POST['multiple-pics']['cg_gallery_user']['visual']['ShowBackToGalleryButton'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['visual']['ShowBackToGalleryButton'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_no_voting']['visual']['ShowBackToGalleryButton'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['visual']['ShowBackToGalleryButton'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_winner']['visual']['ShowBackToGalleryButton'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['visual']['ShowBackToGalleryButton'])) ? 1 : 0;

    $_POST['multiple-pics']['cg_gallery']['visual']['ForwardToWpPageEntry'] = $ForwardToWpPageEntry;
    $_POST['multiple-pics']['cg_gallery_user']['visual']['ForwardToWpPageEntry'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['visual']['ForwardToWpPageEntry'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_no_voting']['visual']['ForwardToWpPageEntry'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['visual']['ForwardToWpPageEntry'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_winner']['visual']['ForwardToWpPageEntry'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['visual']['ForwardToWpPageEntry'])) ? 1 : 0;

    $_POST['multiple-pics']['cg_gallery']['visual']['ForwardToWpPageEntryInNewTab'] = $ForwardToWpPageEntryInNewTab;
    $_POST['multiple-pics']['cg_gallery_user']['visual']['ForwardToWpPageEntryInNewTab'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['visual']['ForwardToWpPageEntryInNewTab'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_no_voting']['visual']['ForwardToWpPageEntryInNewTab'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['visual']['ForwardToWpPageEntryInNewTab'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_winner']['visual']['ForwardToWpPageEntryInNewTab'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['visual']['ForwardToWpPageEntryInNewTab'])) ? 1 : 0;

    $EnableSwitchStyleGalleryButton = $_POST['multiple-pics']['cg_gallery']['visual']['EnableSwitchStyleGalleryButton'];
    $SwitchStyleGalleryButtonOnlyTopControls = $_POST['multiple-pics']['cg_gallery']['visual']['SwitchStyleGalleryButtonOnlyTopControls'];
    $EnableSwitchStyleImageViewButton = $_POST['multiple-pics']['cg_gallery']['visual']['EnableSwitchStyleImageViewButton'];
    $SwitchStyleImageViewButtonOnlyImageView = $_POST['multiple-pics']['cg_gallery']['visual']['SwitchStyleImageViewButtonOnlyImageView'];

    $_POST['multiple-pics']['cg_gallery']['general']['ShowAlways'] = $ShowAlways;
    if($dbVersion<21){
    $_POST['multiple-pics']['cg_gallery_user']['general']['ShowAlways'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['general']['ShowAlways'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_no_voting']['general']['ShowAlways'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['general']['ShowAlways'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_winner']['general']['ShowAlways'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['general']['ShowAlways'])) ? 1 : 0;
    }else{
        $_POST['multiple-pics']['cg_gallery_user']['general']['ShowAlways'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['general']['ShowAlways'])) ? 3 : 3;
        $_POST['multiple-pics']['cg_gallery_no_voting']['general']['ShowAlways'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['general']['ShowAlways'])) ? 3 : 3;
        $_POST['multiple-pics']['cg_gallery_winner']['general']['ShowAlways'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['general']['ShowAlways'])) ? 3 : 3;
    }

    $_POST['multiple-pics']['cg_gallery']['pro']['RegUserGalleryOnly'] = $RegUserGalleryOnly;
    $_POST['multiple-pics']['cg_gallery_user']['pro']['RegUserGalleryOnly'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['pro']['RegUserGalleryOnly'])) ? 1 : 0; // this setting is irrelevant for user gallery
    $_POST['multiple-pics']['cg_gallery_no_voting']['pro']['RegUserGalleryOnly'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['pro']['RegUserGalleryOnly'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_winner']['pro']['RegUserGalleryOnly'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['pro']['RegUserGalleryOnly'])) ? 1 : 0;

    $_POST['multiple-pics']['cg_gallery']['pro']['RegUserGalleryOnlyText'] = $RegUserGalleryOnlyText;
    $_POST['multiple-pics']['cg_gallery_no_voting']['pro']['RegUserGalleryOnlyText'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['pro']['RegUserGalleryOnlyText'])) ? contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_no_voting']['pro']['RegUserGalleryOnlyText']) : '';
    $_POST['multiple-pics']['cg_gallery_winner']['pro']['RegUserGalleryOnlyText'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['pro']['RegUserGalleryOnlyText'])) ? contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_winner']['pro']['RegUserGalleryOnlyText']) : '';
    $_POST['multiple-pics']['cg_gallery_user']['pro']['RegUserGalleryOnlyText'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['pro']['RegUserGalleryOnlyText'])) ? contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_user']['pro']['RegUserGalleryOnlyText']) : '';

    // has to be set here for json-options.php
    $AdditionalCssGalleryPage = '';
    $AdditionalCssEntryLandingPage = '';

    if(!empty($WpPageParent)){
        $_POST['multiple-pics']['cg_gallery']['visual']['ShareButtons'] = $ShareButtons;
        $_POST['multiple-pics']['cg_gallery_user']['visual']['ShareButtons'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_user']['visual']['ShareButtons']);
        $_POST['multiple-pics']['cg_gallery_no_voting']['visual']['ShareButtons'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_no_voting']['visual']['ShareButtons']);
        $_POST['multiple-pics']['cg_gallery_winner']['visual']['ShareButtons'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_winner']['visual']['ShareButtons']);

        $_POST['multiple-pics']['cg_gallery']['visual']['TextBeforeWpPageEntry'] = $TextBeforeWpPageEntry;
        $_POST['multiple-pics']['cg_gallery_user']['visual']['TextBeforeWpPageEntry'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_user']['visual']['TextBeforeWpPageEntry']);
        $_POST['multiple-pics']['cg_gallery_no_voting']['visual']['TextBeforeWpPageEntry'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_no_voting']['visual']['TextBeforeWpPageEntry']);
        $_POST['multiple-pics']['cg_gallery_winner']['visual']['TextBeforeWpPageEntry'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_winner']['visual']['TextBeforeWpPageEntry']);

        $_POST['multiple-pics']['cg_gallery']['visual']['TextAfterWpPageEntry'] = $TextAfterWpPageEntry;
        $_POST['multiple-pics']['cg_gallery_user']['visual']['TextAfterWpPageEntry'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_user']['visual']['TextAfterWpPageEntry']);
        $_POST['multiple-pics']['cg_gallery_no_voting']['visual']['TextAfterWpPageEntry'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_no_voting']['visual']['TextAfterWpPageEntry']);
        $_POST['multiple-pics']['cg_gallery_winner']['visual']['TextAfterWpPageEntry'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_winner']['visual']['TextAfterWpPageEntry']);

        $_POST['multiple-pics']['cg_gallery']['visual']['BackToGalleryButtonText'] = $BackToGalleryButtonText;
        $_POST['multiple-pics']['cg_gallery_user']['visual']['BackToGalleryButtonText'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_user']['visual']['BackToGalleryButtonText']);
        $_POST['multiple-pics']['cg_gallery_no_voting']['visual']['BackToGalleryButtonText'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_no_voting']['visual']['BackToGalleryButtonText']);
        $_POST['multiple-pics']['cg_gallery_winner']['visual']['BackToGalleryButtonText'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_winner']['visual']['BackToGalleryButtonText']);

        $_POST['multiple-pics']['cg_gallery']['visual']['TextDeactivatedEntry'] = $TextDeactivatedEntry;
        $_POST['multiple-pics']['cg_gallery_user']['visual']['TextDeactivatedEntry'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_user']['visual']['TextDeactivatedEntry']);
        $_POST['multiple-pics']['cg_gallery_no_voting']['visual']['TextDeactivatedEntry'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_no_voting']['visual']['TextDeactivatedEntry']);
        $_POST['multiple-pics']['cg_gallery_winner']['visual']['TextDeactivatedEntry'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_winner']['visual']['TextDeactivatedEntry']);

        if(isset($_POST['multiple-pics']['cg_gallery']['visual']['AdditionalCssGalleryPage'])){
            $AdditionalCssGalleryPage = sanitize_textarea_field($_POST['multiple-pics']['cg_gallery']['visual']['AdditionalCssGalleryPage']);// sanitize_textarea_field has to be done for textarea fields so line breaks are preserved
            $_POST['multiple-pics']['cg_gallery']['visual']['AdditionalCssGalleryPage'] = $AdditionalCssGalleryPage;
            $_POST['multiple-pics']['cg_gallery_user']['visual']['AdditionalCssGalleryPage'] = sanitize_textarea_field($_POST['multiple-pics']['cg_gallery_user']['visual']['AdditionalCssGalleryPage']);
            $_POST['multiple-pics']['cg_gallery_no_voting']['visual']['AdditionalCssGalleryPage'] = sanitize_textarea_field($_POST['multiple-pics']['cg_gallery_no_voting']['visual']['AdditionalCssGalleryPage']);
            $_POST['multiple-pics']['cg_gallery_winner']['visual']['AdditionalCssGalleryPage'] = sanitize_textarea_field($_POST['multiple-pics']['cg_gallery_winner']['visual']['AdditionalCssGalleryPage']);
        }

        if(isset($_POST['multiple-pics']['cg_gallery']['visual']['AdditionalCssEntryLandingPage'])){
            $AdditionalCssEntryLandingPage = sanitize_textarea_field($_POST['multiple-pics']['cg_gallery']['visual']['AdditionalCssEntryLandingPage']);// sanitize_textarea_field has to be done for textarea fields so line breaks are preserved
            $_POST['multiple-pics']['cg_gallery']['visual']['AdditionalCssEntryLandingPage'] = $AdditionalCssEntryLandingPage;
            $_POST['multiple-pics']['cg_gallery_user']['visual']['AdditionalCssEntryLandingPage'] = sanitize_textarea_field($_POST['multiple-pics']['cg_gallery_user']['visual']['AdditionalCssEntryLandingPage']);
            $_POST['multiple-pics']['cg_gallery_no_voting']['visual']['AdditionalCssEntryLandingPage'] = sanitize_textarea_field($_POST['multiple-pics']['cg_gallery_no_voting']['visual']['AdditionalCssEntryLandingPage']);
            $_POST['multiple-pics']['cg_gallery_winner']['visual']['AdditionalCssEntryLandingPage'] = sanitize_textarea_field($_POST['multiple-pics']['cg_gallery_winner']['visual']['AdditionalCssEntryLandingPage']);
        }

        $_POST['multiple-pics']['cg_gallery']['pro']['BackToGalleryButtonURL'] = $BackToGalleryButtonURL;
        $_POST['multiple-pics']['cg_gallery_user']['pro']['BackToGalleryButtonURL'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_user']['pro']['BackToGalleryButtonURL']);
        $_POST['multiple-pics']['cg_gallery_no_voting']['pro']['BackToGalleryButtonURL'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_no_voting']['pro']['BackToGalleryButtonURL']);
        $_POST['multiple-pics']['cg_gallery_winner']['pro']['BackToGalleryButtonURL'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_winner']['pro']['BackToGalleryButtonURL']);

        $_POST['multiple-pics']['cg_gallery']['pro']['WpPageParentRedirectURL'] = $WpPageParentRedirectURL;
        $_POST['multiple-pics']['cg_gallery_user']['pro']['WpPageParentRedirectURL'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_user']['pro']['WpPageParentRedirectURL']);
        $_POST['multiple-pics']['cg_gallery_no_voting']['pro']['WpPageParentRedirectURL'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_no_voting']['pro']['WpPageParentRedirectURL']);
        $_POST['multiple-pics']['cg_gallery_winner']['pro']['WpPageParentRedirectURL'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_winner']['pro']['WpPageParentRedirectURL']);

        $_POST['multiple-pics']['cg_gallery']['pro']['RedirectURLdeletedEntry'] = $RedirectURLdeletedEntry;
        $_POST['multiple-pics']['cg_gallery_user']['pro']['RedirectURLdeletedEntry'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_user']['pro']['RedirectURLdeletedEntry']);
        $_POST['multiple-pics']['cg_gallery_no_voting']['pro']['RedirectURLdeletedEntry'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_no_voting']['pro']['RedirectURLdeletedEntry']);
        $_POST['multiple-pics']['cg_gallery_winner']['pro']['RedirectURLdeletedEntry'] = contest_gal1ery_htmlentities_and_preg_replace($_POST['multiple-pics']['cg_gallery_winner']['pro']['RedirectURLdeletedEntry']);
    }

    $GalleryID = $id;

    $EnableEmojis = (!empty($_POST['multiple-pics']['cg_gallery']['visual']['EnableEmojis'])) ? 1 : 0;
    $CheckLoginComment = (!empty($_POST['multiple-pics']['cg_gallery']['pro']['CheckLoginComment'])) ? 1 : 0;

    include('json-options.php');

    $jsonOptionsAllGalleryVariants = array();

    // added in 10.9.8.9.2, do not remove in the moment! It is for fallback!
    // If somebody save the options and then switch back to older version, he will still be able to use gallery in frontend
    $jsonOptionsAllGalleryVariants = $jsonOptions;

    $jsonOptionsAllGalleryVariants[$GalleryID] = (!empty($jsonOptions[$GalleryID])) ? $jsonOptions[$GalleryID] : $jsonOptions;

    $isModernOptionsNew = false;

    if(empty($jsonOptions[$GalleryID.'-u'])){
        $isModernOptionsNew = true;
    }

    // adjustments AllowSortOptions

    $AllowSortOptionsCgGalleryUser = 'empty';

    if (!empty($_POST['multiple-pics'])) {
        if (!empty($_POST['multiple-pics']['cg_gallery_user'])) {
            if (!empty($_POST['multiple-pics']['cg_gallery_user']['visual'])) {
                if (!empty($_POST['multiple-pics']['cg_gallery_user']['visual']['AllowSortOptionsArray'])) {
                    foreach ($_POST['multiple-pics']['cg_gallery_user']['visual']['AllowSortOptionsArray'] as $AllowSortOptionsValue) {
                        if (empty($AllowSortOptions)) {
                            $AllowSortOptionsCgGalleryUser .= $AllowSortOptionsValue;
                        } else {
                            $AllowSortOptionsCgGalleryUser .= ',' . $AllowSortOptionsValue;
                        }
                    }
                }
            }
        }
    }

    $AllowSortOptionsCgGalleryNoVoting = 'empty';

    if (!empty($_POST['multiple-pics'])) {
        if (!empty($_POST['multiple-pics']['cg_gallery_no_voting'])) {
            if (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['visual'])) {
                if (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['visual']['AllowSortOptionsArray'])) {
                    foreach ($_POST['multiple-pics']['cg_gallery_no_voting']['visual']['AllowSortOptionsArray'] as $AllowSortOptionsValue) {
                        if (empty($AllowSortOptions)) {
                            $AllowSortOptionsCgGalleryNoVoting .= $AllowSortOptionsValue;
                        } else {
                            $AllowSortOptionsCgGalleryNoVoting .= ',' . $AllowSortOptionsValue;
                        }
                    }
                }
            }
        }
    }

    $AllowSortOptionsCgGalleryWinner = 'empty';

    if (!empty($_POST['multiple-pics'])) {
        if (!empty($_POST['multiple-pics']['cg_gallery_winner'])) {
            if (!empty($_POST['multiple-pics']['cg_gallery_winner']['visual'])) {
                if (!empty($_POST['multiple-pics']['cg_gallery_winner']['visual']['AllowSortOptionsArray'])) {
                    foreach ($_POST['multiple-pics']['cg_gallery_winner']['visual']['AllowSortOptionsArray'] as $AllowSortOptionsValue) {
                        if (empty($AllowSortOptions)) {
                            $AllowSortOptionsCgGalleryWinner .= $AllowSortOptionsValue;
                        } else {
                            $AllowSortOptionsCgGalleryWinner .= ',' . $AllowSortOptionsValue;
                        }
                    }
                }
            }
        }
    }

    // ADJUSTMENTS EXIF DATA

    $_POST['multiple-pics']['cg_gallery_user']['general']['ShowExifModel'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['general']['ShowExifModel'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_user']['general']['ShowExifApertureFNumber'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['general']['ShowExifApertureFNumber'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_user']['general']['ShowExifExposureTime'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['general']['ShowExifExposureTime'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_user']['general']['ShowExifISOSpeedRatings'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['general']['ShowExifISOSpeedRatings'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_user']['general']['ShowExifFocalLength'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['general']['ShowExifFocalLength'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_user']['general']['ShowExifDateTimeOriginal'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['general']['ShowExifDateTimeOriginal'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_user']['general']['ShowExifDateTimeOriginalFormat'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['general']['ShowExifDateTimeOriginalFormat'])) ? $_POST['multiple-pics']['cg_gallery_user']['general']['ShowExifDateTimeOriginalFormat'] : '';

    $_POST['multiple-pics']['cg_gallery_no_voting']['general']['ShowExifModel'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['general']['ShowExifModel'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_no_voting']['general']['ShowExifApertureFNumber'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['general']['ShowExifApertureFNumber'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_no_voting']['general']['ShowExifExposureTime'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['general']['ShowExifExposureTime'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_no_voting']['general']['ShowExifISOSpeedRatings'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['general']['ShowExifISOSpeedRatings'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_no_voting']['general']['ShowExifFocalLength'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['general']['ShowExifFocalLength'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_no_voting']['general']['ShowExifDateTimeOriginal'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['general']['ShowExifDateTimeOriginal'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_no_voting']['general']['ShowExifDateTimeOriginalFormat'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['general']['ShowExifDateTimeOriginalFormat'])) ? $_POST['multiple-pics']['cg_gallery_no_voting']['general']['ShowExifDateTimeOriginalFormat'] : '';

    $_POST['multiple-pics']['cg_gallery_winner']['general']['ShowExifModel'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['general']['ShowExifModel'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_winner']['general']['ShowExifApertureFNumber'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['general']['ShowExifApertureFNumber'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_winner']['general']['ShowExifExposureTime'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['general']['ShowExifExposureTime'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_winner']['general']['ShowExifISOSpeedRatings'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['general']['ShowExifISOSpeedRatings'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_winner']['general']['ShowExifFocalLength'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['general']['ShowExifFocalLength'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_winner']['general']['ShowExifDateTimeOriginal'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['general']['ShowExifDateTimeOriginal'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_winner']['general']['ShowExifDateTimeOriginalFormat'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['general']['ShowExifDateTimeOriginalFormat'])) ? $_POST['multiple-pics']['cg_gallery_winner']['general']['ShowExifDateTimeOriginalFormat'] : '';

    // ADJUSTMENTS AllowGalleryScript and SliderFullWindow and BlogLookFullWindow

    $_POST['multiple-pics']['cg_gallery_user']['general']['AllowGalleryScript'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['general']['AllowGalleryScript'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_user']['pro']['SliderFullWindow'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['pro']['SliderFullWindow'])) ? 1: 0;
    $_POST['multiple-pics']['cg_gallery_user']['visual']['BlogLookFullWindow'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['visual']['BlogLookFullWindow'])) ? 1: 0;
    $_POST['multiple-pics']['cg_gallery_user']['general']['FullSizeImageOutGallery'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['general']['FullSizeImageOutGallery'])) ? 1: 0;
    $_POST['multiple-pics']['cg_gallery_user']['general']['OnlyGalleryView'] = (!empty($_POST['multiple-pics']['cg_gallery_user']['general']['OnlyGalleryView'])) ? 1: 0;

    $_POST['multiple-pics']['cg_gallery_no_voting']['general']['AllowGalleryScript'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['general']['AllowGalleryScript'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_no_voting']['pro']['SliderFullWindow'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['pro']['SliderFullWindow'])) ? 1: 0;
    $_POST['multiple-pics']['cg_gallery_no_voting']['visual']['BlogLookFullWindow'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['visual']['BlogLookFullWindow'])) ? 1: 0;
    $_POST['multiple-pics']['cg_gallery_no_voting']['general']['FullSizeImageOutGallery'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['general']['FullSizeImageOutGallery'])) ? 1: 0;
    $_POST['multiple-pics']['cg_gallery_no_voting']['general']['OnlyGalleryView'] = (!empty($_POST['multiple-pics']['cg_gallery_no_voting']['general']['OnlyGalleryView'])) ? 1: 0;

    $_POST['multiple-pics']['cg_gallery_winner']['general']['AllowGalleryScript'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['general']['AllowGalleryScript'])) ? 1 : 0;
    $_POST['multiple-pics']['cg_gallery_winner']['pro']['SliderFullWindow'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['pro']['SliderFullWindow'])) ? 1: 0;
    $_POST['multiple-pics']['cg_gallery_winner']['visual']['BlogLookFullWindow'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['visual']['BlogLookFullWindow'])) ? 1: 0;
    $_POST['multiple-pics']['cg_gallery_winner']['general']['FullSizeImageOutGallery'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['general']['FullSizeImageOutGallery'])) ? 1: 0;
    $_POST['multiple-pics']['cg_gallery_winner']['general']['OnlyGalleryView'] = (!empty($_POST['multiple-pics']['cg_gallery_winner']['general']['OnlyGalleryView'])) ? 1: 0;

    // ADJUSTMENTS order look

    $order = $_POST['multiple-pics']['cg_gallery_user']['general']['order'];

    $i = 0;
/*    echo "<br>Order:<br>";
    print_r($order);
    echo "<br>";*/

    foreach ($order as $key => $value) {

        $i++;

        if ($value == 't') {
            $t = $i;
        }
        if ($value == 'h') {
            $h = $i;
        }
        if ($value == 'r') {
            $r = $i;
        }
        if ($value == 's') {
            $s = $i;
        }
        if ($value == 'b') {
            $b = $i;
        }

    }

    $_POST['multiple-pics']['cg_gallery_user']['general']['ThumbLookOrder'] = $t;
    $_POST['multiple-pics']['cg_gallery_user']['general']['HeightLookOrder'] = 0;
    if($dbVersion<15){
        $_POST['multiple-pics']['cg_gallery_user']['general']['RowLookOrder'] = $r;
    }
    $_POST['multiple-pics']['cg_gallery_user']['general']['SliderLookOrder'] = $s;
    $_POST['multiple-pics']['cg_gallery_user']['visual']['BlogLookOrder'] = $b;

    $order = $_POST['multiple-pics']['cg_gallery_no_voting']['general']['order'];

    $i = 0;

    foreach ($order as $key => $value) {

        $i++;

        if ($value == 't') {
            $t = $i;
        }
        if ($value == 'h') {
            $h = $i;
        }
        if($dbVersion<15){
            if ($value == 'r') {
                $r = $i;
            }
        }
        if ($value == 's') {
            $s = $i;
        }
        if ($value == 'b') {
            $b = $i;
        }

    }

    $_POST['multiple-pics']['cg_gallery_no_voting']['general']['ThumbLookOrder'] = $t;
    $_POST['multiple-pics']['cg_gallery_no_voting']['general']['HeightLookOrder'] = 0;
    if($dbVersion<15){
        $_POST['multiple-pics']['cg_gallery_no_voting']['general']['RowLookOrder'] = $r;
    }
    $_POST['multiple-pics']['cg_gallery_no_voting']['general']['SliderLookOrder'] = $s;
    $_POST['multiple-pics']['cg_gallery_no_voting']['visual']['BlogLookOrder'] = $b;

    $i = 0;

    $order = $_POST['multiple-pics']['cg_gallery_winner']['general']['order'];

    foreach ($order as $key => $value) {

        $i++;

        if ($value == 't') {
            $t = $i;
        }
        if ($value == 'h') {
            $h = $i;
        }
        if($dbVersion<15){
            if ($value == 'r') {
                $r = $i;
            }
        }
        if ($value == 's') {
            $s = $i;
        }
        if ($value == 'b') {
            $b = $i;
        }

    }

    $_POST['multiple-pics']['cg_gallery_winner']['general']['ThumbLookOrder'] = $t;
    $_POST['multiple-pics']['cg_gallery_winner']['general']['HeightLookOrder'] = 0;
    if($dbVersion<15){
        $_POST['multiple-pics']['cg_gallery_winner']['general']['RowLookOrder'] = $r;
    }

    $_POST['multiple-pics']['cg_gallery_winner']['general']['SliderLookOrder'] = $s;
    $_POST['multiple-pics']['cg_gallery_winner']['visual']['BlogLookOrder'] = $b;

    $jsonOptionsAllGalleryVariants[$GalleryID . '-u'] = (!empty($jsonOptions[$GalleryID . '-u'])) ? $jsonOptions[$GalleryID . '-u'] : $jsonOptions;

    $shortcodeSpecificToSetArray = include(__DIR__ . '/../../../vars/general/short-code-specific-to-set-array.php');

    // $type >>> general, input, visual, pro
    foreach ($jsonOptions as $type => $option) {
        foreach ($option as $key => $value) {
            if (!empty($_POST['multiple-pics']['cg_gallery_user'][$type][$key])) {
                if ($_POST['multiple-pics']['cg_gallery_user'][$type][$key] == 'on') {
                    $jsonOptionsAllGalleryVariants[$GalleryID . '-u'][$type][$key] = 1;
                } else {
                    if (in_array($key, $shortcodeSpecificToSetArray)) {
                        $jsonOptionsAllGalleryVariants[$GalleryID . '-u'][$type][$key] = $_POST['multiple-pics']['cg_gallery_user'][$type][$key];
                    } else {
                        $jsonOptionsAllGalleryVariants[$GalleryID . '-u'][$type][$key] = $jsonOptionsAllGalleryVariants[$GalleryID][$type][$key] ;
                    }
                }
            } else if ($key == 'AllowSortOptions') {
                $jsonOptionsAllGalleryVariants[$GalleryID . '-u'][$type][$key] = $AllowSortOptionsCgGalleryUser;
            } else if (isset($_POST['multiple-pics']['cg_gallery_user'][$type][$key])) {
                $jsonOptionsAllGalleryVariants[$GalleryID . '-u'][$type][$key] = 0;
            }
        }
    }

    if($GalleryUploadOnlyUser && $isModernOptionsNew){
        $jsonOptionsAllGalleryVariants[$GalleryID . '-u']['pro']['GalleryUpload'] = 1;
    }

    $jsonOptionsAllGalleryVariants[$GalleryID . '-nv'] = (!empty($jsonOptions[$GalleryID . '-nv'])) ? $jsonOptions[$GalleryID . '-nv'] : $jsonOptions;

    // $type >>> general, input, visual, pro
    foreach ($jsonOptions as $type => $option) {
        foreach ($option as $key => $value) {
            if (!empty($_POST['multiple-pics']['cg_gallery_no_voting'][$type][$key])) {
                if ($_POST['multiple-pics']['cg_gallery_no_voting'][$type][$key] == 'on') {
                    $jsonOptionsAllGalleryVariants[$GalleryID . '-nv'][$type][$key] = 1;
                } else {
                    if (in_array($key, $shortcodeSpecificToSetArray)) {
                        $jsonOptionsAllGalleryVariants[$GalleryID . '-nv'][$type][$key] = $_POST['multiple-pics']['cg_gallery_no_voting'][$type][$key];
                    } else {
                        $jsonOptionsAllGalleryVariants[$GalleryID . '-nv'][$type][$key] = $jsonOptionsAllGalleryVariants[$GalleryID][$type][$key] ;
                    }
                }
            } else if ($key == 'AllowSortOptions') {
                $jsonOptionsAllGalleryVariants[$GalleryID . '-nv'][$type][$key] = $AllowSortOptionsCgGalleryNoVoting;
            } else if (isset($_POST['multiple-pics']['cg_gallery_no_voting'][$type][$key])) {
                $jsonOptionsAllGalleryVariants[$GalleryID . '-nv'][$type][$key] = 0;
            }
        }
    }

    $jsonOptionsAllGalleryVariants[$GalleryID . '-w'] = (!empty($jsonOptions[$GalleryID . '-w'])) ? $jsonOptions[$GalleryID . '-w'] : $jsonOptions;

    // $type >>> general, input, visual, pro
    foreach ($jsonOptions as $type => $option) {
        foreach ($option as $key => $value) {
            if (!empty($_POST['multiple-pics']['cg_gallery_winner'][$type][$key])) {
                if ($_POST['multiple-pics']['cg_gallery_winner'][$type][$key] == 'on') {
                    $jsonOptionsAllGalleryVariants[$GalleryID . '-w'][$type][$key] = 1;
                } else {
                    if (in_array($key, $shortcodeSpecificToSetArray)) {
                        $jsonOptionsAllGalleryVariants[$GalleryID . '-w'][$type][$key] = $_POST['multiple-pics']['cg_gallery_winner'][$type][$key];
                    } else {
                        $jsonOptionsAllGalleryVariants[$GalleryID . '-w'][$type][$key] = $jsonOptionsAllGalleryVariants[$GalleryID][$type][$key] ;
                    }
                }
            } else if ($key == 'AllowSortOptions') {
                $jsonOptionsAllGalleryVariants[$GalleryID . '-w'][$type][$key] = $AllowSortOptionsCgGalleryWinner;
            } else if (isset($_POST['multiple-pics']['cg_gallery_winner'][$type][$key])) {
                $jsonOptionsAllGalleryVariants[$GalleryID . '-w'][$type][$key] = 0;
            }
        }
    }

    if(empty($jsonOptionsAllGalleryVariants['icons'])){
        $jsonOptionsAllGalleryVariants['icons'] = array();
    }
    if(!empty($_POST['iconVoteUndoneGalleryViewBase64'])){
        if($_POST['iconVoteUndoneGalleryViewBase64']=='off'){
            $jsonOptionsAllGalleryVariants['icons']['iconVoteUndoneGalleryViewBase64'] = '';
        }else{
            $jsonOptionsAllGalleryVariants['icons']['iconVoteUndoneGalleryViewBase64'] = $_POST['iconVoteUndoneGalleryViewBase64'];
        }
    }
    if(!empty($_POST['iconVoteDoneGalleryViewBase64'])){
        if($_POST['iconVoteDoneGalleryViewBase64']=='off'){
            $jsonOptionsAllGalleryVariants['icons']['iconVoteDoneGalleryViewBase64'] = '';
        }else{
            $jsonOptionsAllGalleryVariants['icons']['iconVoteDoneGalleryViewBase64'] = $_POST['iconVoteDoneGalleryViewBase64'];
        }
    }
    if(!empty($_POST['iconVoteHalfStarGalleryViewBase64'])){
        if($_POST['iconVoteHalfStarGalleryViewBase64']=='off'){
            $jsonOptionsAllGalleryVariants['icons']['iconVoteHalfStarGalleryViewBase64'] = '';
        }else{
            $jsonOptionsAllGalleryVariants['icons']['iconVoteHalfStarGalleryViewBase64'] = $_POST['iconVoteHalfStarGalleryViewBase64'];
        }
    }
    if(!empty($_POST['iconVoteUndoneImageViewBase64'])){
        if($_POST['iconVoteUndoneImageViewBase64']=='off'){
            $jsonOptionsAllGalleryVariants['icons']['iconVoteUndoneImageViewBase64'] = '';
        }else{
            $jsonOptionsAllGalleryVariants['icons']['iconVoteUndoneImageViewBase64'] = $_POST['iconVoteUndoneImageViewBase64'];
        }
    }
    if(!empty($_POST['iconVoteDoneImageViewBase64'])){
        if($_POST['iconVoteDoneImageViewBase64']=='off'){
            $jsonOptionsAllGalleryVariants['icons']['iconVoteDoneImageViewBase64'] = '';
        }else{
            $jsonOptionsAllGalleryVariants['icons']['iconVoteDoneImageViewBase64'] = $_POST['iconVoteDoneGalleryViewBase64'];
        }
    }
    if(!empty($_POST['iconVoteHalfStarImageViewBase64'])){
        if($_POST['iconVoteHalfStarImageViewBase64']=='off'){
            $jsonOptionsAllGalleryVariants['icons']['iconVoteHalfStarImageViewBase64'] = '';
        }else{
            $jsonOptionsAllGalleryVariants['icons']['iconVoteHalfStarImageViewBase64'] = $_POST['iconVoteHalfStarImageViewBase64'];
        }
    }
    if(!empty($_POST['iconVoteFiveStarsPercentageOverviewDoneImageViewBase64'])){
        if($_POST['iconVoteFiveStarsPercentageOverviewDoneImageViewBase64']=='off'){
            $jsonOptionsAllGalleryVariants['icons']['iconVoteFiveStarsPercentageOverviewDoneImageViewBase64'] = '';
        }else{
            $jsonOptionsAllGalleryVariants['icons']['iconVoteFiveStarsPercentageOverviewDoneImageViewBase64'] = $_POST['iconVoteDoneGalleryViewBase64'];
        }
    }
    if(!empty($_POST['iconVoteRemoveImageViewBase64'])){
        if($_POST['iconVoteRemoveImageViewBase64']=='off'){
            $jsonOptionsAllGalleryVariants['icons']['iconVoteRemoveImageViewBase64'] = '';
        }else{
            $jsonOptionsAllGalleryVariants['icons']['iconVoteRemoveImageViewBase64'] = $_POST['iconVoteRemoveImageViewBase64'];
        }
    }
    if(!empty($_POST['iconVoteRemoveGalleryOnlyViewBase64'])){
        if($_POST['iconVoteRemoveGalleryOnlyViewBase64']=='off'){
            $jsonOptionsAllGalleryVariants['icons']['iconVoteRemoveGalleryOnlyViewBase64'] = '';
        }else{
            $jsonOptionsAllGalleryVariants['icons']['iconVoteRemoveGalleryOnlyViewBase64'] = $_POST['iconVoteRemoveGalleryOnlyViewBase64'];
        }
    }
    if(!empty($_POST['iconCommentUndoneGalleryViewBase64'])){
        if($_POST['iconCommentUndoneGalleryViewBase64']=='off'){
            $jsonOptionsAllGalleryVariants['icons']['iconCommentUndoneGalleryViewBase64'] = '';
        }else{
            $jsonOptionsAllGalleryVariants['icons']['iconCommentUndoneGalleryViewBase64'] = $_POST['iconCommentUndoneGalleryViewBase64'];
        }
    }
    if(!empty($_POST['iconCommentDoneGalleryViewBase64'])){
        if($_POST['iconCommentDoneGalleryViewBase64']=='off'){
            $jsonOptionsAllGalleryVariants['icons']['iconCommentDoneGalleryViewBase64'] = '';
        }else{
            $jsonOptionsAllGalleryVariants['icons']['iconCommentDoneGalleryViewBase64'] = $_POST['iconCommentDoneGalleryViewBase64'];
        }
    }
    if(!empty($_POST['iconCommentUndoneImageViewBase64'])){
        if($_POST['iconCommentUndoneImageViewBase64']=='off'){
            $jsonOptionsAllGalleryVariants['icons']['iconCommentUndoneImageViewBase64'] = '';
        }else{
            $jsonOptionsAllGalleryVariants['icons']['iconCommentUndoneImageViewBase64'] = $_POST['iconCommentUndoneImageViewBase64'];
        }
    }
    if(!empty($_POST['iconCommentDoneImageViewBase64'])){
        if($_POST['iconCommentDoneImageViewBase64']=='off'){
            $jsonOptionsAllGalleryVariants['icons']['iconCommentDoneImageViewBase64'] = '';
        }else{
            $jsonOptionsAllGalleryVariants['icons']['iconCommentDoneImageViewBase64'] = $_POST['iconCommentDoneImageViewBase64'];
        }
    }
    if(!empty($_POST['iconCommentAddImageViewBase64'])){
        if($_POST['iconCommentAddImageViewBase64']=='off'){
            $jsonOptionsAllGalleryVariants['icons']['iconCommentAddImageViewBase64'] = '';
        }else{
            $jsonOptionsAllGalleryVariants['icons']['iconCommentAddImageViewBase64'] = $_POST['iconCommentAddImageViewBase64'];
        }
    }
    if(!empty($_POST['iconInfoImageViewBase64'])){
        if($_POST['iconInfoImageViewBase64']=='off'){
            $jsonOptionsAllGalleryVariants['icons']['iconInfoImageViewBase64'] = '';
        }else{
            $jsonOptionsAllGalleryVariants['icons']['iconInfoImageViewBase64'] = $_POST['iconInfoImageViewBase64'];
        }
    }

    if(isset($jsonOptionsAllGalleryVariants[$GalleryID]['interval'])){
        unset($jsonOptionsAllGalleryVariants[$GalleryID]['interval']);
    }
    if($jsonOptionsAllGalleryVariants[$GalleryID.'-u']['interval']){
        unset($jsonOptionsAllGalleryVariants[$GalleryID.'-u']['interval']);
    }
    if($jsonOptionsAllGalleryVariants[$GalleryID.'-nv']['interval']){
        unset($jsonOptionsAllGalleryVariants[$GalleryID.'-nv']['interval']);
    }
    if($jsonOptionsAllGalleryVariants[$GalleryID.'-w']['interval']){
        unset($jsonOptionsAllGalleryVariants[$GalleryID.'-w']['interval']);
    }

    $fp = fopen($galleryUploadFolder . '/json/' . $GalleryID . '-options.json', 'w');
    fwrite($fp, json_encode($jsonOptionsAllGalleryVariants));
    fclose($fp);

    $tstampFile = $wp_upload_dir["basedir"] . "/contest-gallery/gallery-id-$id/json/$id-gallery-tstamp.json";
    $fp = fopen($tstampFile, 'w');
    fwrite($fp, time());
    fclose($fp);

    $isChangeFbLikeOnlyShare = false;
    if ($FbLikeOnlyShare != $FbLikeOnlyShareBefore) {
        $isChangeFbLikeOnlyShare = true;
    }

    if ($isChangeFbLikeOnlyShare) {

/*        if ($FbLikeNoShare == 1) {
            $searchDataShare = 'data-share="true"';
            $replaceDataShare = 'data-share="false"';
            $searchClass = 'class="fb-share-button"';
            $replaceClass = 'class="fb-like"';
            $searchDataLayout = 'data-layout="button"';
            $replaceDataLayout = 'data-layout="button_count"';
        } else*/ if ($FbLikeOnlyShare == 1) {
            $searchDataShare = 'data-share="false"';
            $replaceDataShare = 'data-share="true"';
            $searchClass = 'class="fb-like"';
            $replaceClass = 'class="fb-share-button"';
            $searchDataLayout = 'data-layout="button_count"';
            $replaceDataLayout = 'data-layout="button"';
        } else {
            $searchDataShare = 'data-share="false"';
            $replaceDataShare = 'data-share="true"';
            $searchClass = 'class="fb-share-button"';
            $replaceClass = 'class="fb-like"';
            $searchDataLayout = 'data-layout="button"';
            $replaceDataLayout = 'data-layout="button_count"';
        }

        $htmlFiles = glob($wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-' . $id . '/*.html');

        foreach ($htmlFiles as $htmlFile) {

            $fp = fopen($htmlFile, 'r');
            $htmlFileData = fread($fp, filesize($htmlFile));
            fclose($fp);

            $htmlFileData = str_replace($searchDataShare, $replaceDataShare, $htmlFileData);
            $htmlFileData = str_replace($searchClass, $replaceClass, $htmlFileData);
            $htmlFileData = str_replace($searchDataLayout, $replaceDataLayout, $htmlFileData);

            $fp = fopen($htmlFile, 'w');
            fwrite($fp, $htmlFileData);
            fclose($fp);

        }

    }


}


?>