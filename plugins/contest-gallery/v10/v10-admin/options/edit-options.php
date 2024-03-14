<?php
if(!defined('ABSPATH')){exit;}

global $wpdb;

$galeryNR = absint($_GET['option_id']);
$GalleryID = absint($_GET['option_id']);

$upload_dir = wp_upload_dir();
$uploadFolder = wp_upload_dir();
$plugin_dir_path = plugin_dir_path(__FILE__);

$isEditOptions = true;

$replyMailNote = '<br><b>(Note for testing: mail is send to and "Reply e-mail" can not be the same)</b>';

$tablenameOptions = $wpdb->prefix . "contest_gal1ery_options";
$tablenameGoogleOptions = $wpdb->prefix . "contest_gal1ery_google_options";
$tablename_options_input = $wpdb->prefix . "contest_gal1ery_options_input";
$tablename_options_visual = $wpdb->prefix . "contest_gal1ery_options_visual";
$tablename_form_input = $wpdb->prefix . "contest_gal1ery_f_input";
$tablename_mail_admin = $wpdb->prefix . "contest_gal1ery_mail_admin";
$tablename_mail_user_upload = $wpdb->prefix . "contest_gal1ery_mail_user_upload";
$tablename_mail_user_comment = $wpdb->prefix . "contest_gal1ery_mail_user_comment";
$tablename_mail_user_vote = $wpdb->prefix . "contest_gal1ery_mail_user_vote";
$tablenameemail = $wpdb->prefix . "contest_gal1ery_mail";
$tablename_pro_options = $wpdb->prefix . "contest_gal1ery_pro_options";
//$tablename_mail_gallery = $wpdb->prefix . "contest_gal1ery_mail_gallery";
$tablename_mail_confirmation = $wpdb->prefix . "contest_gal1ery_mail_confirmation";
$tablename_comments_notification_options = $wpdb->prefix . "contest_gal1ery_comments_notification_options";
$tablename_registry_and_login_options = $wpdb->prefix . "contest_gal1ery_registry_and_login_options";

/*$tinymceStyle = '<style type="text/css">
				   .switch-tmce {display:inline;}
				   .wp-editor-area{height:120px;}
				   .wp-editor-tabs{float:left;}
				   body#tinymce{width:unset !important;}
				   </style>';

// TINY MCE Settings here
$settingsHTMLarea = array(
    "media_buttons"=>false,
    'editor_class' => 'cg-small-textarea',
    'default_post_edit_rows'=> 10,
    "teeny" => true,
    "dfw" => true,
    'editor_css' => $tinymceStyle
);*/

//$optionID = @@$_POST['option_id'];
$galeryID = $GalleryID;
include(__DIR__ ."/../../../check-language.php");

// create options if required
$checkCommentsNotificationOptions = $wpdb->get_var($wpdb->prepare( "SELECT COUNT(*) as NumberOfRows FROM $tablename_comments_notification_options WHERE GalleryID = %d",[$GalleryID]));

if(empty($checkCommentsNotificationOptions)){
    include(__DIR__ ."/../../../update/update-entries-check/update-entries-comments-notification-options.php");
}

$selectSQL1 = $wpdb->get_row($wpdb->prepare( "SELECT * FROM $tablenameOptions WHERE id = %d",[$GalleryID]));

$galleryDbVersion = $selectSQL1->Version;

echo "<input type='hidden' id='cgGalleryDbVersion' value='$galleryDbVersion' >";

if(intval($galleryDbVersion)<14){
    $count_tablename_registry_and_login_options = $wpdb->get_var($wpdb->prepare("SELECT id FROM $tablename_registry_and_login_options WHERE GalleryID = %d LIMIT 1",[$GalleryID]));
    if(empty($count_tablename_registry_and_login_options)){
        cg_create_registry_and_login_options($galeryNR);
    }
}
if(intval($galleryDbVersion)>=14){
    $registryAndLoginOptions = $wpdb->get_row( "SELECT * FROM $tablename_registry_and_login_options WHERE GeneralID = '1'" );
    $RegistryUserRole = html_entity_decode(stripslashes($registryAndLoginOptions->RegistryUserRole));
}else{
    $registryAndLoginOptions = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tablename_registry_and_login_options WHERE GalleryID = %d",[$GalleryID]));
}

$selectSQL2 = $wpdb->get_results($wpdb->prepare( "SELECT * FROM $tablename_options_input WHERE GalleryID = %d",[$GalleryID]));

$selectSQL3 = $wpdb->get_results($wpdb->prepare(  "SELECT * FROM $tablename_options_visual WHERE GalleryID = %d",[$GalleryID]));

$selectSQL4 = $wpdb->get_results($wpdb->prepare( "SELECT * FROM $tablename_pro_options WHERE GalleryID = %d",[$GalleryID]));

$selectSQLgoogleOptions = cg_get_google_options();

$ClientId = contest_gal1ery_convert_for_html_output_without_nl2br($selectSQLgoogleOptions->ClientId);
$GoogleButtonBorderRadius = ($selectSQLgoogleOptions->BorderRadius==1) ? 'checked' : '';
$TextBeforeGoogleSignInButton = contest_gal1ery_convert_for_html_output_without_nl2br($selectSQLgoogleOptions->TextBeforeGoogleSignInButton);
$ButtonTextOnLoad = contest_gal1ery_convert_for_html_output_without_nl2br($selectSQLgoogleOptions->ButtonTextOnLoad);
$FeControlsStyleGoogleSignIn = contest_gal1ery_convert_for_html_output_without_nl2br($selectSQLgoogleOptions->FeControlsStyle);
$FeControlsStyleWhiteGoogleSignIn = ($FeControlsStyleGoogleSignIn=='white' OR empty($FeControlsStyleGoogleSignIn)) ? 'checked' : '';
$FeControlsStyleBlackGoogleSignIn = ($FeControlsStyleGoogleSignIn=='black') ? 'checked' : '';
$ButtonStyle = contest_gal1ery_convert_for_html_output_without_nl2br($selectSQLgoogleOptions->ButtonStyle);
$ButtonStyleBrightChecked = '';
$ButtonStyleDarkChecked = '';
if($ButtonStyle=='bright'){
    $ButtonStyleBrightChecked = 'checked';
}
if($ButtonStyle=='dark'){
    $ButtonStyleDarkChecked = 'checked';
}

$commentsNotificationOptions = $wpdb->get_results($wpdb->prepare( "SELECT * FROM $tablename_comments_notification_options WHERE GalleryID = %d",[$GalleryID]));

foreach($commentsNotificationOptions as $commentsNotificationOption){

    $CommNoteAddressor = contest_gal1ery_convert_for_html_output_without_nl2br($commentsNotificationOption->CommNoteAddressor);
    $CommNoteAdminMail = contest_gal1ery_convert_for_html_output_without_nl2br($commentsNotificationOption->CommNoteAdminMail);
    $CommNoteCC = contest_gal1ery_convert_for_html_output_without_nl2br($commentsNotificationOption->CommNoteCC);
    $CommNoteBCC = contest_gal1ery_convert_for_html_output_without_nl2br($commentsNotificationOption->CommNoteBCC);
    $CommNoteReply = contest_gal1ery_convert_for_html_output_without_nl2br($commentsNotificationOption->CommNoteReply);
    $CommNoteSubject = contest_gal1ery_convert_for_html_output_without_nl2br($commentsNotificationOption->CommNoteSubject);
    $CommNoteContent = contest_gal1ery_convert_for_html_output_without_nl2br($commentsNotificationOption->CommNoteContent);

}

foreach($selectSQL4 as $value4){

    $ForwardAfterRegUrl = html_entity_decode(stripslashes($value4->ForwardAfterRegUrl));
    $ForwardAfterRegText = contest_gal1ery_convert_for_html_output_without_nl2br($value4->ForwardAfterRegText);
    $ForwardAfterLoginUrlCheck = ($value4->ForwardAfterLoginUrlCheck==1) ? 'checked' : '';
    $ReviewComm = ($value4->ReviewComm==1) ? 'checked' : '';
    $ForwardAfterLoginUrlStyle = ($value4->ForwardAfterLoginUrlCheck==1) ? 'style="height:100px;"' : 'disabled style="background-color:#e0e0e0;height:100px;"';
    $ForwardAfterLoginUrl = contest_gal1ery_no_convert($value4->ForwardAfterLoginUrl);
    $ForwardAfterLoginTextCheck = ($value4->ForwardAfterLoginTextCheck==1) ? 'checked' : '';
    $ForwardAfterLoginTextStyle = ($value4->ForwardAfterLoginTextCheck==1) ? 'style="height:100px;"' : 'disabled style="background-color:#e0e0e0;height:100px;"';
    $ForwardAfterLoginText = contest_gal1ery_convert_for_html_output_without_nl2br($value4->ForwardAfterLoginText);
    $TextEmailConfirmation = contest_gal1ery_convert_for_html_output_without_nl2br($value4->TextEmailConfirmation);
    $TextAfterEmailConfirmation = contest_gal1ery_convert_for_html_output_without_nl2br($value4->TextAfterEmailConfirmation);
    $RegMailAddressor = contest_gal1ery_convert_for_html_output_without_nl2br($value4->RegMailAddressor);
    $RegMailReply = contest_gal1ery_convert_for_html_output_without_nl2br($value4->RegMailReply);
    $RegMailSubject = contest_gal1ery_convert_for_html_output_without_nl2br($value4->RegMailSubject);
    $UploadRequiresCookieMessage = contest_gal1ery_convert_for_html_output_without_nl2br($value4->UploadRequiresCookieMessage);
    $RegUserUploadOnlyText = contest_gal1ery_convert_for_html_output_without_nl2br($value4->RegUserUploadOnlyText);
    $BackToGalleryButtonURL = contest_gal1ery_convert_for_html_output_without_nl2br($value4->BackToGalleryButtonURL);
    $WpPageParentRedirectURL = contest_gal1ery_convert_for_html_output_without_nl2br($value4->WpPageParentRedirectURL);
    $RedirectURLdeletedEntry = contest_gal1ery_convert_for_html_output_without_nl2br($value4->RedirectURLdeletedEntry);

    $RegUserGalleryOnly = ($value4->RegUserGalleryOnly==1) ? 'checked' : '';

    $CheckLoginUpload = ($value4->RegUserUploadOnly==1) ? 'checked' : '';
    $CheckCookieUpload = ($value4->RegUserUploadOnly==2) ? 'checked' : '';
    $CheckIpUpload = ($value4->RegUserUploadOnly==3) ? 'checked' : '';

    $RegUserGalleryOnlyText = contest_gal1ery_convert_for_html_output_without_nl2br($value4->RegUserGalleryOnlyText);
    $RegUserMaxUpload = (empty($value4->RegUserMaxUpload)) ? '' : $value4->RegUserMaxUpload;
    $RegUserMaxUploadPerCategory = (empty($value4->RegUserMaxUploadPerCategory)) ? '' : $value4->RegUserMaxUploadPerCategory;
    $PreselectSort = (empty($value4->PreselectSort)) ? 'date_descend' : $value4->PreselectSort;

    $FbLikeNoShare = ($value4->FbLikeNoShare==1) ? 'checked' : '';
    $FbLikeOnlyShare = ($value4->FbLikeOnlyShare==1) ? 'checked' : '';

    $DeleteFromStorageIfDeletedInFrontend = ($value4->DeleteFromStorageIfDeletedInFrontend==1) ? 'checked' : '';

    $Manipulate = ($value4->Manipulate==1) ? 'checked' : '';
    $MinusVote = ($value4->MinusVote==1) ? 'checked' : '';
    $SliderFullWindow = ($value4->SliderFullWindow==1) ? 'checked' : '';
    $VoteNotOwnImage = ($value4->VoteNotOwnImage==1) ? 'checked' : '';
    $RegMailOptional = ($value4->RegMailOptional==1) ? 'checked' : '';

    $CustomImageName = ($value4->CustomImageName==1) ? 'checked' : '';
    $CustomImageNamePath = contest_gal1ery_convert_for_html_output_without_nl2br($value4->CustomImageNamePath);

    if(empty($value4->SlideTransition)){
        $value4->SlideTransition = 'translateX';
    }

    $checkIpCheckUpload = '';

    if(empty($RegUserMaxUpload) && empty($value4->RegUserUploadOnly)){// do this for upgrade version 10.9.8.4.3
        $checkIpCheckUpload = 'checked';
    }

    $SlideHorizontal = ($value4->SlideTransition=='translateX') ? 'checked' : '';
    $SlideVertical = ($value4->SlideTransition=='slideDown') ? 'checked' : '';
    $Search = ($value4->Search==1) ? 'checked' : '';
    $GalleryUpload = ($value4->GalleryUpload==1) ? 'checked' : '';
    $VotePerCategory = ($value4->VotePerCategory==1) ? 'checked' : '';
    $VotesPerCategory = $value4->VotesPerCategory;
    if($VotesPerCategory==0){$VotesPerCategory='';}

    $GalleryUploadOnlyUser = ($value4->GalleryUploadOnlyUser==1) ? 'checked' : '';
    $ShowNickname = ($value4->ShowNickname==1) ? 'checked' : '';
    $ShowProfileImage = ($value4->ShowProfileImage==1) ? 'checked' : '';

    $AllowUploadJPG = ($value4->AllowUploadJPG==1) ? 'checked' : '';
    $AllowUploadPNG = ($value4->AllowUploadPNG==1) ? 'checked' : '';
    $AllowUploadGIF = ($value4->AllowUploadGIF==1) ? 'checked' : '';
    $AllowUploadICO = ($value4->AllowUploadICO==1) ? 'checked' : '';
    if(!empty($cgProFalse)){// PNG and GIF as file types only available in PRO version
        $AllowUploadPNG = ($value4->AllowUploadPNG==1) ? '' : '';
        $AllowUploadGIF = ($value4->AllowUploadGIF==1) ? '' : '';
    }

    $ShowExif = ($value4->ShowExif==1) ? 'checked' : '';
    $GalleryUploadConfirmationText = contest_gal1ery_convert_for_html_output_without_nl2br($value4->GalleryUploadConfirmationText);
    $GalleryUploadTextAfter = contest_gal1ery_convert_for_html_output_without_nl2br($value4->GalleryUploadTextAfter);
    $GalleryUploadTextBefore = contest_gal1ery_convert_for_html_output_without_nl2br($value4->GalleryUploadTextBefore);

    $VotesInTime = ($value4->VotesInTime==1) ? 'checked' : '';
    $VotesInTimeQuantity = html_entity_decode(stripslashes($value4->VotesInTimeQuantity));
    $VotesInTimeIntervalReadable = html_entity_decode(stripslashes($value4->VotesInTimeIntervalReadable));
    $VotesInTimeIntervalReadableExploded = explode(':',$VotesInTimeIntervalReadable);
    $cg_date_hours_vote_interval = $VotesInTimeIntervalReadableExploded[0];


    if(!empty($VotesInTimeIntervalReadableExploded[1])){
        $cg_date_mins_vote_interval = $VotesInTimeIntervalReadableExploded[1];
    }else{
        $cg_date_mins_vote_interval = '00';
    }

    $VotesInTimeIntervalSeconds = html_entity_decode(stripslashes($value4->VotesInTimeIntervalSeconds));
    $VotesInTimeIntervalAlertMessage = contest_gal1ery_no_convert($value4->VotesInTimeIntervalAlertMessage);

    $HideRegFormAfterLogin = ($value4->HideRegFormAfterLogin==1) ? 'checked' : '';
    $HideRegFormAfterLoginShowTextInstead = ($value4->HideRegFormAfterLoginShowTextInstead==1) ? 'checked' : '';
    $HideRegFormAfterLoginTextToShow = contest_gal1ery_convert_for_html_output_without_nl2br($value4->HideRegFormAfterLoginTextToShow);

    $VoteMessageSuccessActive = ($value4->VoteMessageSuccessActive==1) ? 'checked' : '';
    $VoteMessageSuccessText = contest_gal1ery_convert_for_html_output_without_nl2br($value4->VoteMessageSuccessText);

    $VoteMessageWarningActive = ($value4->VoteMessageWarningActive==1) ? 'checked' : '';
    $VoteMessageWarningText = contest_gal1ery_convert_for_html_output_without_nl2br($value4->VoteMessageWarningText);

    $CommNoteActive = ($value4->CommNoteActive==1) ? 'checked' : '';

    $AdditionalFiles = ($value4->AdditionalFiles==1) ? 'checked' : '';
    $AdditionalFilesCount = (empty($value4->AdditionalFilesCount)) ? 1 : $value4->AdditionalFilesCount;

    $InformAdminAllowActivateDeactivate = ($value4->InformAdminAllowActivateDeactivate==1) ? 'checked' : '';
    $InformAdminActivationURL = contest_gal1ery_convert_for_html_output_without_nl2br($value4->InformAdminActivationURL);
    $InformAdminActivationURLDisabled = ($value4->InformAdminAllowActivateDeactivate!=1) ? 'cg_disabled_override' : '';

}


$checkDataFormOutput = $wpdb->get_results($wpdb->prepare( "SELECT * FROM $tablename_form_input WHERE GalleryID = %d and (Field_Type = 'comment-f' or Field_Type = 'text-f' or Field_Type = 'email-f')",[$GalleryID]));

$selectSQLemailAdmin = $wpdb->get_row($wpdb->prepare( "SELECT * FROM $tablename_mail_admin WHERE GalleryID = %d",[$GalleryID]));

$ContentAdminMail = $selectSQLemailAdmin->Content;
$ContentAdminMail = contest_gal1ery_convert_for_html_output_without_nl2br($ContentAdminMail);

$selectSQLemailUserUpload = $wpdb->get_row($wpdb->prepare( "SELECT * FROM $tablename_mail_user_upload WHERE GalleryID = %d",[$GalleryID]));

$ContentUserUploadMail = $selectSQLemailUserUpload->Content;
$ContentUserUploadMail = contest_gal1ery_convert_for_html_output_without_nl2br($ContentUserUploadMail);

$selectSQLemailUserComment = $wpdb->get_row($wpdb->prepare( "SELECT * FROM $tablename_mail_user_comment WHERE GalleryID = %d",[$GalleryID]));

$ContentUserCommentMail = $selectSQLemailUserComment->Content;
$ContentUserCommentMail = contest_gal1ery_convert_for_html_output_without_nl2br($ContentUserCommentMail);

$selectSQLemailUserVote = $wpdb->get_row($wpdb->prepare( "SELECT * FROM $tablename_mail_user_vote WHERE GalleryID = %d",[$GalleryID]));

$ContentUserVoteMail = $selectSQLemailUserVote->Content;
$ContentUserVoteMail = contest_gal1ery_convert_for_html_output_without_nl2br($ContentUserVoteMail);


// Reihenfolge der Gallerien wird ermittelt --- ENDE

$selectedCheckComments = ($selectSQL1->AllowComments==1) ? 'checked' : '';
$AllowRating = $selectSQL1->AllowRating;
    if($AllowRating==1){
        $AllowRating = 15;
    }
$selectedCheckRating = ($selectSQL1->AllowRating==1 OR ($selectSQL1->AllowRating>=12 && $selectSQL1->AllowRating<=20)) ? 'checked' : '';
$selectedCheckRating2 = ($selectSQL1->AllowRating==2) ? 'checked' : '';
$selectedCheckFbLike = ($selectSQL1->FbLike==1) ? 'checked' : '';
$selectedCheckFbLikeGallery = ($selectSQL1->FbLikeGallery==1) ? 'checked' : '';
$selectedCheckFbLikeGalleryVote = ($selectSQL1->FbLikeGalleryVote==1) ? 'checked' : '';
$selectedRatingOutGallery = ($selectSQL1->RatingOutGallery==1) ? 'checked' : '';
$AllowComments = $selectSQL1->AllowComments;

$selectedCommentsOutGallery = ($selectSQL1->CommentsOutGallery==1) ? 'checked' : '';
$selectedCheckIp = ($selectSQL1->IpBlock==1) ? 'checked' : '';
$selectedCheckFb = ($selectSQL1->FbLike==1) ? 'checked' : '';
$CheckLogin = ($selectSQL1->CheckLogin==1) ? 'checked' : '';
$CheckIp = ($selectSQL1->CheckIp==1 && $selectSQL1->CheckCookie!=1) ? 'checked' : '';
$CheckCookie = ($selectSQL1->CheckCookie==1 && $selectSQL1->CheckIp!=1) ? 'checked' : '';
$CheckIpAndCookie = ($selectSQL1->CheckIp==1 && $selectSQL1->CheckCookie==1) ? 'checked' : '';
if($dbVersion<14){
$RegistryUserRole = html_entity_decode(stripslashes($selectSQL1->RegistryUserRole));
}

    if($CheckLogin == '' && $CheckIp == '' && $CheckCookie == ''  && $CheckIpAndCookie == ''){
        $CheckLogin = 'checked';
    }

$CheckCookieAlertMessage = contest_gal1ery_no_convert($selectSQL1->CheckCookieAlertMessage);

    if(empty($CheckCookieAlertMessage)){
        $CheckCookieAlertMessage = 'Please allow cookies to vote';
    }

$HideUntilVote = ($selectSQL1->HideUntilVote==1) ? 'checked' : '';
$ShowOnlyUsersVotes = ($selectSQL1->ShowOnlyUsersVotes==1) ? 'checked' : '';
$HideInfo = ($selectSQL1->HideInfo==1) ? 'checked' : '';

    //echo "<br>HideInfo: $HideInfo<br>";

$ActivateUpload = ($selectSQL1->ActivateUpload==1) ? 'checked' : '';

    if(floatval($galleryDbVersion)>=21.1){// to be complete, in real the old contestStart and contestEnd settings will be not displayed
        $ContestEnd = 0;
        $ContestStart = 0;
    }

$ContestEnd = ($selectSQL1->ContestEnd==1) ? 'checked' : '';
$ContestEndInstant = ($selectSQL1->ContestEnd==2) ? 'checked' : '';
$ContestEndTime = date('Y-m-d',(!empty($selectSQL1->ContestEndTime)) ? $selectSQL1->ContestEndTime : 0);
$ContestStart = ($selectSQL1->ContestStart==1) ? 'checked' : '';
$ContestStartTime = date('Y-m-d',(!empty($selectSQL1->ContestStartTime)) ? $selectSQL1->ContestStartTime : 0);

    $ContestEndTimeHours = '';
    $ContestEndTimeMins = '';
    if(!empty($ContestEndTime)){
    $ContestEndTimeHours = date('H',($selectSQL1->ContestEndTime==='') ? 0 : $selectSQL1->ContestEndTime);
    $ContestEndTimeMins = date('i',($selectSQL1->ContestEndTime==='') ? 0 : $selectSQL1->ContestEndTime);
    }

    $ContestStartTimeHours = '';
    $ContestStartTimeMins = '';
    if(!empty($ContestStartTime)){
    $ContestStartTimeHours = date('H',($selectSQL1->ContestStartTime==='') ? 0 : $selectSQL1->ContestStartTime);
    $ContestStartTimeMins = date('i',($selectSQL1->ContestStartTime==='') ? 0 : $selectSQL1->ContestStartTime);
    }

    echo "<input type='hidden' id='getContestEndTime' value='".@$ContestEndTime."'>";
    echo "<input type='hidden' id='getContestStartTime' value='".@$ContestStartTime."'>";
$FullSize = ($selectSQL1->FullSize==1) ? 'checked' : '';// full screen mode!
$FullSizeGallery = ($selectSQL1->FullSizeGallery==1) ? 'checked' : '';// full window mode!
$FullSizeSlideOutStart = ($selectSQL1->FullSizeSlideOutStart==1) ? 'checked' : '';
$OnlyGalleryView = ($selectSQL1->OnlyGalleryView==1) ? 'checked' : '';
$SinglePicView = ($selectSQL1->SinglePicView==1) ? 'checked' : '';
$ScaleOnly = ($selectSQL1->ScaleOnly==1) ? 'checked' : '';
$ScaleAndCut = ($selectSQL1->ScaleAndCut==1) ? 'checked' : '';

$AllowGalleryScript = ($selectSQL1->AllowGalleryScript==1) ? 'checked' : '';

$InfiniteScroll = $selectSQL1->InfiniteScroll;

    //echo "<br>InfiniteScroll: $InfiniteScroll<br>";


    //$InfiniteScroll = ($value->InfiniteScroll==1) ? 'checked' : '';

$FullSizeImageOutGallery = ($selectSQL1->FullSizeImageOutGallery==1) ? 'checked' : '';
$FullSizeImageOutGalleryNewTab = ($selectSQL1->FullSizeImageOutGalleryNewTab==1) ? 'checked' : '';
$ShowAlwaysInfoSlider = ($selectSQL1->ShowAlwaysInfoSlider==1) ? 'checked' : '';

$HeightLook = ($selectSQL1->HeightLook==1) ? 'checked' : '';
$RowLook = ($selectSQL1->RowLook==1) ? 'checked' : '';
    if($RowLook=='checked'){ // since 07.02.2022 row look will be replaced by height look if selected
        $RowLook = '';
        $HeightLook = 'checked';
    }
$ThumbLook = ($selectSQL1->ThumbLook==1) ? 'checked' : '';
$SliderLook = ($selectSQL1->SliderLook==1) ? 'checked' : '';

$ThumbsInRow = ($selectSQL1->ThumbsInRow==1) ? 'checked' : '';
$LastRow = ($selectSQL1->LastRow==1) ? 'checked' : '';
$AllowSort = ($selectSQL1->AllowSort==1) ? 'checked' : '';
$RandomSort = ($selectSQL1->RandomSort==1) ? 'checked' : '';
$RandomSortButton = ($selectSQL1->RandomSortButton==1) ? 'checked' : '';
$PicsInRow = $selectSQL1->PicsInRow;
$PicsPerSite = $selectSQL1->PicsPerSite;
$VotesPerUser = $selectSQL1->VotesPerUser;
    if($VotesPerUser==0){$VotesPerUser='';}
$GalleryName1 = $selectSQL1->GalleryName;
$ShowAlways = ($selectSQL1->ShowAlways==1) ? 'checked' : '';

    //echo "<br>GalleryName: $GalleryName<br>";

    // Forward images to URL options

    $Use_as_URL = $wpdb->get_var($wpdb->prepare("SELECT Use_as_URL FROM $tablename_form_input WHERE GalleryID = %d AND Use_as_URL = '1' ",[$GalleryID]));

    //echo "<br>Use_as_URL: $Use_as_URL<br>";
$ForwardToURL = ($selectSQL1->ForwardToURL==1) ? 'checked' : '';
$ForwardType = ($selectSQL1->ForwardType==2) ? 'checked' : '';
    //echo $ForwardType;
    //Prüfen ob Forward URL aus dem Slider oder aus der Gallerie weiterleiten soll
$ForwardFrom = $selectSQL1->ForwardFrom;
    $ForwardFromSlider = ($ForwardFrom==1) ? 'checked' : '';
    $ForwardFromGallery = ($ForwardFrom==2) ? 'checked' : '';
    $ForwardFromSinglePic = ($ForwardFrom==3) ? 'checked' : '';

    // Forward images to URL options --- ENDE

$AdjustThumbLook = ($selectSQL1->AdjustThumbLook==1) ? 'checked' : '';

$WidthThumb = $selectSQL1->WidthThumb;
$HeightThumb = $selectSQL1->HeightThumb;
$DistancePics = $selectSQL1->DistancePics;
$DistancePicsV = $selectSQL1->DistancePicsV;

$WidthGallery = $selectSQL1->WidthGallery;
$HeightGallery = $selectSQL1->HeightGallery;
$HeightLookHeight = $selectSQL1->HeightLookHeight;
$Inform = $selectSQL1->Inform;
$InformAdmin = ($selectSQL1->InformAdmin==1) ? 'checked' : '';
$MaxResJPGwidth = $selectSQL1 ->MaxResJPGwidth;
$MaxResJPGheight = $selectSQL1 ->MaxResJPGheight;
$MinResJPGwidth = $selectSQL1 ->MinResJPGwidth;
$MinResJPGheight = $selectSQL1 ->MinResJPGheight;
    //Leeren Wert kann man by MySQL nicht einfügen. Es entsteht immer eine NULL
    if($MaxResJPGwidth==0){$MaxResJPGwidth='';}
    if($MaxResJPGheight==0){$MaxResJPGheight='';}
if($MinResJPGwidth==0){$MinResJPGwidth='';}
if($MinResJPGheight==0){$MinResJPGheight='';}
$MaxResPNGwidth = $selectSQL1 ->MaxResPNGwidth;
$MaxResPNGheight = $selectSQL1 ->MaxResPNGheight;
$MinResPNGwidth = $selectSQL1 ->MinResPNGwidth;
$MinResPNGheight = $selectSQL1 ->MinResPNGheight;
    if($MaxResPNGwidth==0){$MaxResPNGwidth='';}
    if($MaxResPNGheight==0){$MaxResPNGheight='';}
if($MinResPNGwidth==0){$MinResPNGwidth='';}
if($MinResPNGheight==0){$MinResPNGheight='';}
$MaxResGIFwidth = $selectSQL1 ->MaxResGIFwidth;
$MaxResGIFheight = $selectSQL1 ->MaxResGIFheight;
$MinResGIFwidth = $selectSQL1 ->MinResGIFwidth;
$MinResGIFheight = $selectSQL1 ->MinResGIFheight;
    if($MaxResGIFwidth==0){$MaxResGIFwidth='';}
    if($MaxResGIFheight==0){$MaxResGIFheight='';}
if($MinResGIFwidth==0){$MinResGIFwidth='';}
if($MinResGIFheight==0){$MinResGIFheight='';}
$MaxResJPGon = ($selectSQL1->MaxResJPGon==1) ? 'checked' : '';
$MaxResPNGon = ($selectSQL1->MaxResPNGon==1) ? 'checked' : '';
$MaxResGIFon = ($selectSQL1->MaxResGIFon==1) ? 'checked' : '';
$MinResJPGon = ($selectSQL1->MinResJPGon==1) ? 'checked' : '';
$MinResPNGon = ($selectSQL1->MinResPNGon==1) ? 'checked' : '';
$MinResGIFon = ($selectSQL1->MinResGIFon==1) ? 'checked' : '';
$MaxResICOwidth = $selectSQL1 ->MaxResICOwidth;
$MaxResICOheight = $selectSQL1 ->MaxResICOheight;
    if($MaxResICOwidth==0){$MaxResICOwidth='';}
    if($MaxResICOheight==0){$MaxResICOheight='';}
$MaxResICOon = ($selectSQL1->MaxResICOon==1) ? 'checked' : '';
$FbLikeGoToGalleryLink = (empty($selectSQL1->FbLikeGoToGalleryLink)) ? '' : $selectSQL1->FbLikeGoToGalleryLink;
    $FbLikeGoToGalleryLink = contest_gal1ery_no_convert($FbLikeGoToGalleryLink);

$ActivatePostMaxMB = ($selectSQL1->ActivatePostMaxMB==1) ? 'checked' : '';
$PostMaxMB = $selectSQL1 ->PostMaxMB;
    if($PostMaxMB==0){$PostMaxMB='';}

$ActivateBulkUpload = ($selectSQL1->ActivateBulkUpload==1) ? 'checked' : '';
$BulkUploadQuantity = $selectSQL1 ->BulkUploadQuantity;
    if($BulkUploadQuantity==0){$BulkUploadQuantity='';}

$BulkUploadMinQuantity = $selectSQL1->BulkUploadMinQuantity;
    if($BulkUploadMinQuantity==0){$BulkUploadMinQuantity='';}

$GalleryName = $selectSQL1->GalleryName;

$ActivatePostMaxMBfile = ($selectSQL1->ActivatePostMaxMBfile==1) ? 'checked' : '';
$PostMaxMBfile = $selectSQL1 ->PostMaxMBfile;
$WpPageParent = $selectSQL1 ->WpPageParent;
$WpPageParentUser = $selectSQL1 ->WpPageParentUser;
$WpPageParentNoVoting = $selectSQL1 ->WpPageParentNoVoting;
$WpPageParentWinner = $selectSQL1 ->WpPageParentWinner;
    $WpPageParentPermalink = ($WpPageParent) ? get_permalink($WpPageParent) : '';
    $WpPageParentUserPermalink = ($WpPageParentUser) ? get_permalink($WpPageParentUser) : '';
    $WpPageParentNoVotingPermalink = ($WpPageParentNoVoting) ? get_permalink($WpPageParentNoVoting) : '';
    $WpPageParentWinnerPermalink = ($WpPageParentWinner) ? get_permalink($WpPageParentWinner) : '';


//print_r($selectSQL2);

foreach($selectSQL2 as $value2){

    // Wenn 0 dann confirmation text, wenn 1 dann URL Weiterleitung
    $Forward = ($value2->Forward==1) ? 'checked' : '';
    $ForwardUploadConf = ($value2->Forward==0) ? 'checked' : '';
    $ForwardUploadURL = ($value2->Forward==1) ? 'checked' : '';
    $ShowFormAfterUpload = ($value2->ShowFormAfterUpload==1) ? 'checked' : '';
    //echo "$Forward";
    $forward_url_disabled = ($value2->Forward==1) ? 'style="width:500px;"' : 'disabled style="background: #e0e0e0;width:500px;"';
    $Forward_URL = $value2->Forward_URL;
    $Forward_URL = contest_gal1ery_no_convert($Forward_URL);
    $Confirmation_Text = $value2->Confirmation_Text;
    $Confirmation_Text = contest_gal1ery_convert_for_html_output_without_nl2br($Confirmation_Text);
    $Confirmation_Text_Disabled = ($value2->Forward==0) ? '' : 'disabled';

}

//	print_r($selectSQL3);

foreach($selectSQL3 as $value3){

    $Field1IdGalleryView = $value3->Field1IdGalleryView;
    $ThumbViewBorderWidth = $value3->ThumbViewBorderWidth;
    $ThumbViewBorderRadius = $value3->ThumbViewBorderRadius;
    $ThumbViewBorderColor = $value3->ThumbViewBorderColor;
    $ThumbViewBorderColorPlaceholder = (empty($ThumbViewBorderColor)) ? "placeholder='000000'" : '';
    $ThumbViewBorderOpacity = $value3->ThumbViewBorderOpacity;
    $HeightViewBorderWidth = $value3->HeightViewBorderWidth;
    $HeightViewBorderRadius = $value3->HeightViewBorderRadius;
    $HeightViewBorderColor = $value3->HeightViewBorderColor;
    $HeightViewBorderColorPlaceholder = (empty($HeightViewBorderColor)) ? "placeholder='000000'" : '';
    $HeightViewBorderOpacity = $value3->HeightViewBorderOpacity;
    $HeightViewSpaceWidth = $value3->HeightViewSpaceWidth;
    $HeightViewSpaceHeight = $value3->HeightViewSpaceHeight;
    $RowViewBorderWidth = $value3->RowViewBorderWidth;
    $RowViewBorderRadius = $value3->RowViewBorderRadius;
    $RowViewBorderColor = $value3->RowViewBorderColor;
    $RowViewBorderColorPlaceholder = (empty($RowViewBorderColor)) ? "placeholder='000000'" : '';
    $RowViewBorderOpacity = $value3->RowViewBorderOpacity;
    $RowViewSpaceWidth = $value3->RowViewSpaceWidth;
    $RowViewSpaceHeight = $value3->RowViewSpaceHeight;
    $TitlePositionGallery = $value3->TitlePositionGallery;
    $RatingPositionGallery = $value3->RatingPositionGallery;
    $CommentPositionGallery = $value3->CommentPositionGallery;
    $ActivateGalleryBackgroundColor = ($value3->ActivateGalleryBackgroundColor==1) ? 'checked' : '' ;
    $GalleryBackgroundColor = $value3->GalleryBackgroundColor;
    $GalleryBackgroundColorPlaceholder = (empty($GalleryBackgroundColor)) ? "placeholder='000000'" : '';
    $GalleryBackgroundOpacity = $value3->GalleryBackgroundOpacity;
    $OriginalSourceLinkInSlider = ($value3->OriginalSourceLinkInSlider==1) ? 'checked' : '';
    $PreviewInSlider = ($value3->PreviewInSlider==1) ? 'checked' : '';
    $FeControlsStyleWhite = ($value3->FeControlsStyle=='white' OR empty($value3->FeControlsStyle)) ? 'checked' : '';
    $FeControlsStyleBlack = ($value3->FeControlsStyle=='black') ? 'checked' : '';
    $FeControlsStyleWhiteUpload = ($value3->FeControlsStyleUpload=='white' OR empty($value3->FeControlsStyleUpload)) ? 'checked' : '';
    $FeControlsStyleBlackUpload = ($value3->FeControlsStyleUpload=='black') ? 'checked' : '';
    $FeControlsStyleWhiteRegistry = ($value3->FeControlsStyleRegistry=='white' OR empty($value3->FeControlsStyleRegistry)) ? 'checked' : '';
    $FeControlsStyleBlackRegistry = ($value3->FeControlsStyleRegistry=='black') ? 'checked' : '';
    $FeControlsStyleWhiteLogin = ($value3->FeControlsStyleLogin=='white' OR empty($value3->FeControlsStyleLogin)) ? 'checked' : '';
    $FeControlsStyleBlackLogin = ($value3->FeControlsStyleLogin=='black') ? 'checked' : '';
    $GalleryStyleCenterWhiteChecked = ($value3->GalleryStyle=='center-white') ? 'checked' : '';
    $GalleryStyleCenterBlackChecked = ($value3->GalleryStyle=='center-black' OR empty($value3->GalleryStyle)) ? 'checked' : '';
    $AllowSortOptions = (!empty($value3->AllowSortOptions)) ? $value3->AllowSortOptions : 'date-desc,date-asc,rate-desc,rate-asc,rate-average-desc,rate-average-asc,rate-sum-desc,rate-sum-asc,comment-desc,comment-asc,random';
    $BlogLook = (!empty($value3->BlogLook)) ? 'checked' : '';
    $BlogLookOrder = (!empty($value3->BlogLookOrder)) ? $value3->BlogLookOrder : 5;
    $BlogLookFullWindow = ($value3->BlogLookFullWindow==1) ? 'checked' : '';
    $ImageViewFullWindow = ($value3->ImageViewFullWindow==1) ? 'checked' : '';
    $ImageViewFullScreen = ($value3->ImageViewFullScreen==1) ? 'checked' : '';
    $SliderThumbNav = ($value3->SliderThumbNav==1) ? 'checked' : '';
    $BorderRadius = ($value3->BorderRadius==1) ? 'checked' : '';
    $BorderRadiusUpload = ($value3->BorderRadiusUpload==1) ? 'checked' : '';
    $BorderRadiusRegistry = ($value3->BorderRadiusRegistry==1) ? 'checked' : '';
    $BorderRadiusLogin = ($value3->BorderRadiusLogin==1) ? 'checked' : '';
    $CopyImageLink = ($value3->CopyImageLink==1) ? 'checked' : '';
    $CopyOriginalFileLink = ($value3->CopyOriginalFileLink==1) ? 'checked' : '';
    $ForwardOriginalFile = ($value3->ForwardOriginalFile==1) ? 'checked' : '';
    $ForwardToWpPageEntry = ($value3->ForwardToWpPageEntry==1) ? 'checked' : '';
    $ForwardToWpPageEntryInNewTab = ($value3->ForwardToWpPageEntryInNewTab==1) ? 'checked' : '';
    $ThankVote = ($value3->ThankVote==1) ? 'checked' : '';
    $CommentsDateFormat = (!empty($value3->CommentsDateFormat)) ? $value3->CommentsDateFormat : 'YYYY-MM-DD';
    $ShareButtons = contest_gal1ery_convert_for_html_output_without_nl2br($value3->ShareButtons);
    $TextBeforeWpPageEntry = contest_gal1ery_convert_for_html_output_without_nl2br($value3->TextBeforeWpPageEntry);
    $TextAfterWpPageEntry = contest_gal1ery_convert_for_html_output_without_nl2br($value3->TextAfterWpPageEntry);
    $BackToGalleryButtonText = contest_gal1ery_convert_for_html_output_without_nl2br($value3->BackToGalleryButtonText);
    $TextDeactivatedEntry = contest_gal1ery_convert_for_html_output_without_nl2br($value3->TextDeactivatedEntry);
    $ShowBackToGalleryButton = ($value3->ShowBackToGalleryButton==1) ? 'checked' : '';
}

$LogoutLink = '';
$BackToGalleryLink = '';

if(intval($galleryDbVersion)>=14){
    $optionsForGeneralIDsinceV14 = cg_get_registry_and_login_options_v14();

    $BorderRadiusRegistry = $optionsForGeneralIDsinceV14['visual']['BorderRadiusRegistry'];
    $BorderRadiusRegistry = ($BorderRadiusRegistry==1) ? 'checked' : '';
    $FeControlsStyleRegistry = $optionsForGeneralIDsinceV14['visual']['FeControlsStyleRegistry'];
    $FeControlsStyleWhiteRegistry = ($FeControlsStyleRegistry=='white' OR empty($FeControlsStyleRegistry)) ? 'checked' : '';
    $FeControlsStyleBlackRegistry = ($FeControlsStyleRegistry=='black') ? 'checked' : '';
    $BorderRadiusLogin = $optionsForGeneralIDsinceV14['visual']['BorderRadiusLogin'];
    $BorderRadiusLogin = ($BorderRadiusLogin==1) ? 'checked' : '';
    $FeControlsStyleLogin = $optionsForGeneralIDsinceV14['visual']['FeControlsStyleLogin'];
    $FeControlsStyleWhiteLogin = ($FeControlsStyleLogin=='white' OR empty($FeControlsStyleLogin)) ? 'checked' : '';
    $FeControlsStyleBlackLogin = ($FeControlsStyleLogin=='black') ? 'checked' : '';

    $ForwardAfterLoginUrlCheck = $optionsForGeneralIDsinceV14['pro']['ForwardAfterLoginUrlCheck'];
    $ForwardAfterLoginUrlCheck = ($ForwardAfterLoginUrlCheck==1) ? 'checked' : '';
    $ForwardAfterLoginUrl = $optionsForGeneralIDsinceV14['pro']['ForwardAfterLoginUrl'];
    $ForwardAfterLoginUrl = contest_gal1ery_convert_for_html_output_without_nl2br($ForwardAfterLoginUrl);

    $ForwardAfterLoginTextCheck = $optionsForGeneralIDsinceV14['pro']['ForwardAfterLoginTextCheck'];
    $ForwardAfterLoginTextCheck = ($ForwardAfterLoginTextCheck==1) ? 'checked' : '';
    $ForwardAfterLoginText = $optionsForGeneralIDsinceV14['pro']['ForwardAfterLoginText'];
    $ForwardAfterLoginText = contest_gal1ery_convert_for_html_output_without_nl2br($ForwardAfterLoginText);

    $RegMailOptional = $optionsForGeneralIDsinceV14['pro']['RegMailOptional'];
    $RegMailOptional = ($RegMailOptional==1) ? 'checked' : '';

    $ForwardAfterRegText = $optionsForGeneralIDsinceV14['pro']['ForwardAfterRegText'];
    $ForwardAfterRegText = contest_gal1ery_convert_for_html_output_without_nl2br($ForwardAfterRegText);

    $TextAfterEmailConfirmation = $optionsForGeneralIDsinceV14['pro']['TextAfterEmailConfirmation'];
    $TextAfterEmailConfirmation = contest_gal1ery_convert_for_html_output_without_nl2br($TextAfterEmailConfirmation);

    $HideRegFormAfterLogin = $optionsForGeneralIDsinceV14['pro']['HideRegFormAfterLogin'];
    $HideRegFormAfterLogin = ($HideRegFormAfterLogin==1) ? 'checked' : '';

    $HideRegFormAfterLoginShowTextInstead = $optionsForGeneralIDsinceV14['pro']['HideRegFormAfterLoginShowTextInstead'];
    $HideRegFormAfterLoginShowTextInstead = ($HideRegFormAfterLoginShowTextInstead) ? 'checked' : '';

    $HideRegFormAfterLoginTextToShow = $optionsForGeneralIDsinceV14['pro']['HideRegFormAfterLoginTextToShow'];
    $HideRegFormAfterLoginTextToShow = contest_gal1ery_convert_for_html_output_without_nl2br($HideRegFormAfterLoginTextToShow);

    $RegMailAddressor = $optionsForGeneralIDsinceV14['pro']['RegMailAddressor'];
    $RegMailAddressor = contest_gal1ery_convert_for_html_output_without_nl2br($RegMailAddressor);

    $RegMailReply = $optionsForGeneralIDsinceV14['pro']['RegMailReply'];
    $RegMailReply = contest_gal1ery_convert_for_html_output_without_nl2br($RegMailReply);

    $RegMailSubject = $optionsForGeneralIDsinceV14['pro']['RegMailSubject'];
    $RegMailSubject = contest_gal1ery_convert_for_html_output_without_nl2br($RegMailSubject);

    $TextEmailConfirmation = $optionsForGeneralIDsinceV14['pro']['TextEmailConfirmation'];
    $TextEmailConfirmation = contest_gal1ery_convert_for_html_output_without_nl2br($TextEmailConfirmation);

}


$LogoutLink = contest_gal1ery_convert_for_html_output_without_nl2br($registryAndLoginOptions->LogoutLink);
$BackToGalleryLink = contest_gal1ery_convert_for_html_output_without_nl2br($registryAndLoginOptions->BackToGalleryLink);
$LostPasswordMailActive = ($registryAndLoginOptions->LostPasswordMailActive==1) ? "checked" : "";
$LostPasswordMailAddressor = contest_gal1ery_convert_for_html_output_without_nl2br($registryAndLoginOptions->LostPasswordMailAddressor);
$LostPasswordMailReply = contest_gal1ery_convert_for_html_output_without_nl2br($registryAndLoginOptions->LostPasswordMailReply);
$LostPasswordMailSubject = contest_gal1ery_convert_for_html_output_without_nl2br($registryAndLoginOptions->LostPasswordMailSubject);
$LostPasswordMailConfirmation = contest_gal1ery_convert_for_html_output_without_nl2br($registryAndLoginOptions->LostPasswordMailConfirmation);
$TextBeforeLoginForm = contest_gal1ery_convert_for_html_output_without_nl2br($registryAndLoginOptions->TextBeforeLoginForm);
$TextBeforeRegFormBeforeLoggedIn = contest_gal1ery_convert_for_html_output_without_nl2br($registryAndLoginOptions->TextBeforeRegFormBeforeLoggedIn);

$PermanentTextWhenLoggedIn = contest_gal1ery_convert_for_html_output_without_nl2br($registryAndLoginOptions->PermanentTextWhenLoggedIn);
$EditProfileGroups = (!empty($registryAndLoginOptions->EditProfileGroups)) ? unserialize($registryAndLoginOptions->EditProfileGroups) : [];

$AllowSortOptionsArray = explode(',',$AllowSortOptions);

//echo "source:".$OriginalSourceLinkInSlider;

$selectedRatingPositionGalleryLeft = ($RatingPositionGallery==1) ? "checked" : "";
$selectedRatingPositionGalleryCenter = ($RatingPositionGallery==2) ? "checked" : "";
$selectedRatingPositionGalleryRight = ($RatingPositionGallery==3) ? "checked" : "";

$selectedCommentPositionGalleryLeft = ($CommentPositionGallery==1) ? "checked" : "";
$selectedCommentPositionGalleryCenter = ($CommentPositionGallery==2) ? "checked" : "";
$selectedCommentPositionGalleryRight = ($CommentPositionGallery==3) ? "checked" : "";


$selectedTitlePositionGalleryLeft = ($TitlePositionGallery==1) ? "checked" : "";
$selectedTitlePositionGalleryCenter = ($TitlePositionGallery==2) ? "checked" : "";
$selectedTitlePositionGalleryRight = ($TitlePositionGallery==3) ? "checked" : "";

$GalleryBackgroundColorFields = ($value3->ActivateGalleryBackgroundColor==0) ? 'disabled' : '' ;
//$ThumbLookFieldsChecked = ($value->RowLook==0) ? 'checked' : '' ;
$GalleryBackgroundColorStyle = ($value3->ActivateGalleryBackgroundColor==0) ? 'background-color:#e0e0e0;' : '' ;

//echo "<br>ThumbViewBorderOpacity: $ThumbViewBorderOpacity<br>";
//echo "<br>HeightViewBorderOpacity: $HeightViewBorderOpacity<br>";
//	echo "<br>RowViewBorderOpacity: $RowViewBorderOpacity<br>";

// Disable enable RowLook and ThumbLook Fields

$RowLookFields = ($selectSQL1->RowLook==0) ? 'disabled' : '' ;
$RowLookFieldsStyle = ($selectSQL1->RowLook==0) ? 'background-color:#e0e0e0;' : '' ;
$HeightLookFields = ($selectSQL1->HeightLook==0) ? 'disabled' : '' ;
$HeightLookFieldsStyle = ($selectSQL1->HeightLook==0) ? 'background-color:#e0e0e0;' : '' ;
$ThumbLookFields = ($selectSQL1->ThumbLook==0) ? 'disabled' : '' ;
//$ThumbLookFieldsChecked = ($selectSQL1->RowLook==0) ? 'checked' : '' ;
$ThumbLookFieldsStyle = ($selectSQL1->ThumbLook==0) ? 'background-color:#e0e0e0;' : '' ;

// Disable enable RowLook Fields  --------- END

// set order

$selectGalleryLookOrder = $wpdb->get_results($wpdb->prepare( "SELECT SliderLookOrder, ThumbLookOrder, HeightLookOrder, RowLookOrder  FROM $tablenameOptions WHERE id = %d",[$GalleryID]));

// Reihenfolge der Gallerien wird ermittelt

$order = array();

$selectGalleryLookOrder[0]->BlogLookOrder = $BlogLookOrder;

foreach($selectGalleryLookOrder[0] as $key => $value){
    $order[$value]=$key;
}

ksort($order);

// set order --- END

// Inform set or not

$checkInform = ($Inform==1) ? 'checked' : '' ;

$id = $galeryNR;


//Update 4.00: Single Pic View Prüfung

if($AllowGalleryScript!= 'checked' AND $FullSizeImageOutGallery != 'checked' AND $SinglePicView != 'checked' AND $OnlyGalleryView != 'checked'){

    $SinglePicView = "checked";

}

//Update 4.00: Single Pic View Prüfung --- ENDE


//echo $SinglePicView;


// Get email text options

$selectSQLemail = $wpdb->get_row($wpdb->prepare( "SELECT * FROM $tablenameemail WHERE GalleryID = %d",[$GalleryID]));

$selectSQLmailConfirmation = $wpdb->get_row($wpdb->prepare( "SELECT * FROM $tablename_mail_confirmation WHERE GalleryID = %d",[$GalleryID]));

$mConfirmSendConfirm = ($selectSQLmailConfirmation->SendConfirm==1) ? 'checked' : '' ;

//$selectSQLmailGallery = $wpdb->get_row("SELECT * FROM $tablename_mail_gallery WHERE GalleryID = '$galeryNR'" );

/*$mGallerySendToImageOff = ($selectSQLmailGallery->SendToImageOff==1) ? 'checked' : '' ;
$mGallerySendToNotConfirmedUsers = ($selectSQLmailGallery->SendToNotConfirmedUsers==1) ? 'checked' : '' ;*/


//$content = (@$_POST['editpost']) ? @$_POST['editpost'] : $selectSQLemail->Content;
//$contentUserMail = $selectSQLemail->Content;


// JSON options KORREKTUR SCRIPT HIER

$jsonOptionsFile = $upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryNR.'/json/'.$galeryNR.'-options.json';
$fp = fopen($jsonOptionsFile, 'r');
$jsonOptions = json_decode(fread($fp,filesize($jsonOptionsFile)),true);
fclose($fp);

$isModernOptionsNew = false;

if(empty($jsonOptions[$galeryID.'-u'])){

    $jsonOptionsNew = array();
    $jsonOptionsNew[$galeryNR] = $jsonOptions;
    $jsonOptionsNew[$galeryNR.'-u'] = $jsonOptions;
    $jsonOptionsNew[$galeryNR.'-nv'] = $jsonOptions;
    $jsonOptionsNew[$galeryNR.'-w'] = $jsonOptions;
    $jsonOptionsNew[$GalleryID.'-u']['visual']['ShareButtons'] = '';// unset share buttons for gallery user, because logged in

    $jsonOptions = $jsonOptionsNew;
    $isModernOptionsNew = true;
}

// JSON options KORREKTUR SCRIPT HIER --- END

// get JSON PRO values here
// already converted for html output here (in check-language) if exists
$VotesPerUserAllVotesUsedHtmlMessage = (!empty($translations['pro']['VotesPerUserAllVotesUsedHtmlMessage'])) ? $translations['pro']['VotesPerUserAllVotesUsedHtmlMessage'] : '';

$CommentsDateFormatNamePathSelectedValuesArray = array(
    'YYYY-MM-DD','DD-MM-YYYY','MM-DD-YYYY','YYYY/MM/DD',
    'DD/MM/YYYY','MM/DD/YYYY',
    'YYYY.MM.DD','DD.MM.YYYY',
    'MM.DD.YYYY'
);

$ShowExifDateTimeOriginalFormatNamePathSelectedValuesArray = array(
    'YYYY-MM-DD','DD-MM-YYYY','MM-DD-YYYY','YYYY/MM/DD',
    'DD/MM/YYYY','MM/DD/YYYY',
    'YYYY.MM.DD','DD.MM.YYYY',
    'MM.DD.YYYY'
);

//$content = html_entity_decode(stripslashes($content));

//nl2br($contentBr);

// Get email text options --- ENDE

// get mail exception logs

$mailExceptions = '';
$fileName = md5(wp_salt( 'auth').'---cnglog---'.$GalleryID);
$fileMailExceptions = $uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/logs/errors/mail-'.$fileName.'.log';
if(file_exists($fileMailExceptions)){
    $mailExceptions = file_get_contents($fileMailExceptions);
}
// get mail exception logs --- END

$cg_get_version = cg_get_version();

$get_site_url = get_site_url();

$MailIntervalArray = ['1 Minute (recommended for testing only)'=>'1m','2 Minutes (recommended for testing only)'=>'2m','1 Hour'=>'1h','2 Hours'=>'2h','4 Hours'=>'4h','6 Hours'=>'6h','12 Hours'=>'12h','24 Hours'=>'24h','48 Hours'=>'48h','1 Week'=>'1week','2 Weeks'=>'2weeks','4 Weeks'=>'4weeks'];

// get possible domain mail ending
$bloginfo_wpurl = get_bloginfo('wpurl');
$cgYourDomainName = 'your domain name';

if(strpos($bloginfo_wpurl,'www.')!==false){
    $cgYourDomainName = 'your domain @'.substr($bloginfo_wpurl,strpos($bloginfo_wpurl,'www.')+4,strlen($bloginfo_wpurl));
}
// get possible domain mail ending --- END

$deprecatedGalleryHoverDivText = '';
$deprecatedGalleryHoverDisabledForever = '';

if(floatval($galleryDbVersion)>=12.10){
    $deprecatedGalleryHoverDivText = '<div style="margin-top: 10px;"><span style="font-weight: bold;">DEPRECATED</span><br>Not available for galleries created in version 12.1.0 or higher<br>New modern appearence will be used</div>';
    $deprecatedGalleryHoverDisabledForever = 'cg_disabled_forever';
}

require_once(dirname(__FILE__) . "/../nav-menu.php");

echo "<form action='?page=".cg_get_version()."/index.php&edit_options=true&option_id=$galeryNR' method='post'  data-cg-submit-message='Options saved'  class='cg_load_backend_submit cg_load_backend_submit_save_data'>";

wp_nonce_field( 'cg_admin');

//echo '<input type="hidden" name="editOptions" value="true" >';
echo '<input type="hidden" name="option_id" value="'.$galeryNR.'" >';
if (is_multisite()) {
    $CgEntriesOwnSlugName = cg_get_blog_option( get_current_blog_id(),'CgEntriesOwnSlugName');
}else{
    $CgEntriesOwnSlugName = get_option('CgEntriesOwnSlugName');
}

if(empty($CgEntriesOwnSlugName)){$CgEntriesOwnSlugName='';}

$cgHideUrlEntryFieldsForMails = '';
if(intval($galleryDbVersion)>=21){
    $cgHideUrlEntryFieldsForMails = 'cg_hide';
}

include ('edit-options-menu.php');

?>