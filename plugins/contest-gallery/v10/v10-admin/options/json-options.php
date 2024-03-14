<?php

/*$jsonOptions = array();
$jsonOptions['general'] = array();
$jsonOptions['visual'] = array();
$jsonOptions['input'] = array();*/


if(!empty($nextIDgallery)){
    $GalleryID = $nextIDgallery;
    $id = $nextIDgallery;
    $jsonOptions = array();
    $jsonOptions['visual'] = array();
    $jsonOptions['general'] = array();
    $jsonOptions['input'] = array();
    $jsonOptions['pro'] = array();
}
else{
    $wp_upload_dir = wp_upload_dir();
    $optionsFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-options.json';
    $fp = fopen($optionsFile, 'r');
    $jsonOptions =json_decode(fread($fp,filesize($optionsFile)),true);
    fclose($fp);

    $jsonOptionsInterval = (!empty($jsonOptions['interval'])) ? $jsonOptions['interval'] : [];
    $jsonOptions = (!empty($jsonOptions[$GalleryID])) ? $jsonOptions[$GalleryID] : $jsonOptions;
    $jsonOptions['interval'] = $jsonOptionsInterval;

}

if(empty($jsonOptions)){
    $jsonOptions['visual'] = array();
    $jsonOptions['general'] = array();
    $jsonOptions['input'] = array();
    $jsonOptions['pro'] = array();
}


$ForwardAfterRegText = $ForwardAfterRegText;

$ForwardAfterLoginUrlCheck = 0;
$ForwardAfterLoginUrl = '';

$ForwardAfterLoginTextCheck = 1;
$ForwardAfterLoginText = $ForwardAfterLoginText;

$TextEmailConfirmation = $TextEmailConfirmation;
$TextAfterEmailConfirmation = $TextAfterEmailConfirmation;

$RegMailAddressor = $RegMailAddressor;
$RegMailReply = $RegMailReply;
$RegMailSubject = $RegMailSubject;
$RegUserUploadOnlyText = $RegUserUploadOnlyText;

$jsonOptions['visual']['ThumbViewBorderWidth'] = $ThumbViewBorderWidth;
$jsonOptions['visual']['ThumbViewBorderRadius'] = $ThumbViewBorderRadius;
$jsonOptions['visual']['ThumbViewBorderOpacity'] = $ThumbViewBorderOpacity;
$jsonOptions['visual']['ThumbViewBorderColor'] = $ThumbViewBorderColor;
$jsonOptions['visual']['HeightViewBorderWidth'] = $HeightViewBorderWidth;
$jsonOptions['visual']['HeightViewBorderRadius'] = $HeightViewBorderRadius;
$jsonOptions['visual']['HeightViewBorderColor'] = $HeightViewBorderColor;
$jsonOptions['visual']['HeightViewBorderOpacity'] = $HeightViewBorderOpacity;
$jsonOptions['visual']['HeightViewSpaceWidth'] = $HeightViewSpaceWidth;
$jsonOptions['visual']['HeightViewSpaceHeight'] = $HeightViewSpaceHeight;
$jsonOptions['visual']['RowViewBorderWidth'] = $RowViewBorderWidth;
$jsonOptions['visual']['RowViewBorderRadius'] = $RowViewBorderRadius;
$jsonOptions['visual']['RowViewBorderColor'] = $RowViewBorderColor;
$jsonOptions['visual']['RowViewBorderOpacity'] = $RowViewBorderOpacity;
$jsonOptions['visual']['RowViewSpaceWidth'] = $RowViewSpaceWidth;
$jsonOptions['visual']['RowViewSpaceHeight'] = $RowViewSpaceHeight;
$jsonOptions['visual']['TitlePositionGallery'] = $TitlePositionGallery;
$jsonOptions['visual']['RatingPositionGallery'] = $RatingPositionGallery;
$jsonOptions['visual']['CommentPositionGallery'] = $CommentPositionGallery;
$jsonOptions['visual']['ActivateGalleryBackgroundColor'] = $ActivateGalleryBackgroundColor;
$jsonOptions['visual']['GalleryBackgroundColor'] = $GalleryBackgroundColor;
$jsonOptions['visual']['OriginalSourceLinkInSlider'] = $OriginalSourceLinkInSlider;
$jsonOptions['visual']['PreviewInSlider'] = $PreviewInSlider;
$jsonOptions['visual']['FeControlsStyle'] = $FeControlsStyle;
$jsonOptions['visual']['FeControlsStyleUpload'] = $FeControlsStyleUpload;
$jsonOptions['visual']['FeControlsStyleRegistry'] = $FeControlsStyleRegistry;
$jsonOptions['visual']['FeControlsStyleLogin'] = $FeControlsStyleLogin;
$jsonOptions['visual']['GalleryStyle'] = $GalleryStyle;
$jsonOptions['visual']['AllowSortOptions'] = $AllowSortOptions;
$jsonOptions['visual']['BlogLook'] = $BlogLook;
$jsonOptions['visual']['BlogLookOrder'] = $BlogLookOrder;
$jsonOptions['visual']['BlogLookFullWindow'] = $BlogLookFullWindow;
$jsonOptions['visual']['ImageViewFullWindow'] = $ImageViewFullWindow;
$jsonOptions['visual']['ImageViewFullScreen'] = $ImageViewFullScreen;
$jsonOptions['visual']['SliderThumbNav'] = $SliderThumbNav;
$jsonOptions['visual']['BorderRadius'] = $BorderRadius;
$jsonOptions['visual']['BorderRadiusUpload'] = $BorderRadiusUpload;
$jsonOptions['visual']['BorderRadiusRegistry'] = $BorderRadiusRegistry;
$jsonOptions['visual']['BorderRadiusLogin'] = $BorderRadiusLogin;
$jsonOptions['visual']['CopyImageLink'] = $CopyImageLink;
$jsonOptions['visual']['CopyOriginalFileLink'] = $CopyOriginalFileLink;
$jsonOptions['visual']['ForwardOriginalFile'] = $ForwardOriginalFile;

$jsonOptions['general']['WidthThumb'] = $WidthThumb;
$jsonOptions['general']['HeightThumb'] = $HeightThumb;
$jsonOptions['general']['WidthGallery'] = $WidthGallery;
$jsonOptions['general']['HeightGallery'] = $HeightGallery;
$jsonOptions['general']['DistancePics'] = $DistancePics;
$jsonOptions['general']['DistancePicsV'] = $DistancePicsV;
$jsonOptions['general']['ContestEndTime'] = $ContestEndTime;

$jsonOptions['general']['gid'] = $id;
$jsonOptions['general']['plugins_url'] = plugins_url();

$jsonOptions['general']['PicsPerSite'] = $PicsPerSite;
$jsonOptions['general']['GalleryName'] = $GalleryName;
$jsonOptions['general']['MaxResJPGon'] = $MaxResJPGon;
$jsonOptions['general']['MaxResPNGon'] = $MaxResPNGon;
$jsonOptions['general']['MaxResGIFon'] = $MaxResGIFon;
$jsonOptions['general']['MaxResICOon'] = $MaxResICOon;

$jsonOptions['general']['MinResJPGon'] = $MinResJPGon;
$jsonOptions['general']['MinResPNGon'] = $MinResPNGon;
$jsonOptions['general']['MinResGIFon'] = $MinResGIFon;

$jsonOptions['general']['MaxResJPGwidth'] = $MaxResJPGwidth;
$jsonOptions['general']['MaxResJPGheight'] = $MaxResJPGheight;
$jsonOptions['general']['MaxResPNGwidth'] = $MaxResPNGwidth;
$jsonOptions['general']['MaxResPNGheight'] = $MaxResPNGheight;
$jsonOptions['general']['MaxResGIFwidth'] = $MaxResGIFwidth;
$jsonOptions['general']['MaxResGIFheight'] = $MaxResGIFheight;
$jsonOptions['general']['MaxResICOwidth'] = $MaxResICOwidth;
$jsonOptions['general']['MaxResICOheight'] = $MaxResICOheight;

$jsonOptions['general']['MinResJPGwidth'] = $MinResJPGwidth;
$jsonOptions['general']['MinResJPGheight'] = $MinResJPGheight;
$jsonOptions['general']['MinResPNGwidth'] = $MinResPNGwidth;
$jsonOptions['general']['MinResPNGheight'] = $MinResPNGheight;
$jsonOptions['general']['MinResGIFwidth'] = $MinResGIFwidth;
$jsonOptions['general']['MinResGIFheight'] = $MinResGIFheight;

$jsonOptions['general']['OnlyGalleryView'] = $OnlyGalleryView;
$jsonOptions['general']['SinglePicView'] = $SinglePicView;
$jsonOptions['general']['ScaleOnly'] = $ScaleOnly;
$jsonOptions['general']['ScaleAndCut'] = $ScaleAndCut;
$jsonOptions['general']['FullSize'] = $FullSize;
$jsonOptions['general']['FullSizeGallery'] = $FullSizeGallery;
$jsonOptions['general']['FullSizeSlideOutStart'] = $FullSizeSlideOutStart;
$jsonOptions['general']['AllowSort'] = $AllowSort;
$jsonOptions['general']['RandomSort'] = $RandomSort;
$jsonOptions['general']['RandomSortButton'] = $RandomSortButton;
$jsonOptions['general']['ShowAlways'] = $ShowAlways;

$jsonOptions['general']['AllowComments'] = $AllowComments;
$jsonOptions['general']['CommentsOutGallery'] = $CommentsOutGallery;
$jsonOptions['general']['AllowRating'] = $AllowRating;
$jsonOptions['general']['VotesPerUser'] = $VotesPerUser;
$jsonOptions['general']['RatingOutGallery'] = $RatingOutGallery;
$jsonOptions['general']['IpBlock'] = $IpBlock;

$jsonOptions['general']['ThumbLookOrder'] = $ThumbLookOrder;
$jsonOptions['general']['HeightLookOrder'] = $HeightLookOrder;

if(!empty($isNewGallery)){
    $jsonOptions['general']['RowLookOrder'] = 0;
}else{
    // so for old galleries still saved
    $jsonOptions['general']['RowLookOrder'] = $RowLookOrder;
}

$jsonOptions['general']['CheckLogin'] = $CheckLogin;
$jsonOptions['general']['CheckIp'] = $CheckIp;
$jsonOptions['general']['CheckCookie'] = $CheckCookie;
$jsonOptions['general']['CheckCookieAlertMessage'] = $CheckCookieAlertMessage;
$jsonOptions['general']['FbLike'] = $FbLike;
$jsonOptions['general']['FbLikeGallery'] = $FbLikeGallery;
$jsonOptions['general']['FbLikeGalleryVote'] = $FbLikeGalleryVote;
$jsonOptions['general']['AllowGalleryScript'] = $AllowGalleryScript;
$jsonOptions['general']['InfiniteScroll'] = $InfiniteScroll;
$jsonOptions['general']['FullSizeImageOutGallery'] = $FullSizeImageOutGallery;
$jsonOptions['general']['FullSizeImageOutGalleryNewTab'] = $FullSizeImageOutGalleryNewTab;

$jsonOptions['general']['Inform'] = $Inform;
$jsonOptions['general']['ShowAlwaysInfoSlider'] = $ShowAlwaysInfoSlider;
$jsonOptions['general']['ThumbLook'] = $ThumbLook;
$jsonOptions['general']['AdjustThumbLook'] = $AdjustThumbLook;
$jsonOptions['general']['HeightLook'] = $HeightLook;

if(!empty($isNewGallery)){
    $jsonOptions['general']['RowLook'] = 0;
}else{
    // so for old galleries still saved
    $jsonOptions['general']['RowLook'] = $RowLook;
}

$jsonOptions['general']['HeightLookHeight'] = $HeightLookHeight;
$jsonOptions['general']['ThumbsInRow'] = $ThumbsInRow;
$jsonOptions['general']['PicsInRow'] = $PicsInRow;
$jsonOptions['general']['LastRow'] = $LastRow;
$jsonOptions['general']['HideUntilVote'] = $HideUntilVote;
$jsonOptions['general']['ShowOnlyUsersVotes'] = $ShowOnlyUsersVotes;
$jsonOptions['general']['HideInfo'] = $HideInfo;
$jsonOptions['general']['ActivateBulkUpload'] = $ActivateBulkUpload;
$jsonOptions['general']['ContestEnd'] = $ContestEnd;

$jsonOptions['general']['ForwardToURL'] = $ForwardToURL;
$jsonOptions['general']['ForwardFrom'] = $ForwardFrom;
$jsonOptions['general']['ForwardType'] = $ForwardType;

$jsonOptions['general']['ActivatePostMaxMB'] = $ActivatePostMaxMB;
$jsonOptions['general']['PostMaxMB'] = $PostMaxMB;

$jsonOptions['general']['ActivatePostMaxMBfile'] = $ActivatePostMaxMBfile;
$jsonOptions['general']['PostMaxMBfile'] = $PostMaxMBfile;

$jsonOptions['general']['BulkUploadQuantity'] = $BulkUploadQuantity;
$jsonOptions['general']['BulkUploadMinQuantity'] = $BulkUploadMinQuantity;

if($dbVersion>=21){
    $jsonOptions['general']['Version'] = $VersionForScripts;
    $jsonOptions['general']['VersionDecimal'] = $VersionDecimal;
}else{
    $jsonOptions['general']['Version'] = $dbVersion;
    $jsonOptions['general']['VersionDecimal'] = 0;
}

$jsonOptions['general']['SliderLook'] = $SliderLook;
$jsonOptions['general']['SliderLookOrder'] = $SliderLookOrder;
$jsonOptions['general']['ContestStart'] = $ContestStart;
$jsonOptions['general']['ContestStartTime'] = $ContestStartTime;

$jsonOptions['general']['InformAdmin'] = $InformAdmin;

$jsonOptions['general']['WpPageParent'] = $WpPageParent;
$jsonOptions['general']['WpPageParentUser'] = $WpPageParentUser;
$jsonOptions['general']['WpPageParentNoVoting'] = $WpPageParentNoVoting;
$jsonOptions['general']['WpPageParentWinner'] = $WpPageParentWinner;

$jsonOptions['general']['ShowExifModel'] = $ShowExifModel;
$jsonOptions['general']['ShowExifApertureFNumber'] = $ShowExifApertureFNumber;
$jsonOptions['general']['ShowExifExposureTime'] = $ShowExifExposureTime;
$jsonOptions['general']['ShowExifISOSpeedRatings'] = $ShowExifISOSpeedRatings;
$jsonOptions['general']['ShowExifFocalLength'] = $ShowExifFocalLength;
$jsonOptions['general']['ShowExifDateTimeOriginal'] = $ShowExifDateTimeOriginal;
$jsonOptions['general']['ShowExifDateTimeOriginalFormat'] = $ShowExifDateTimeOriginalFormat;

// JSON only, no database option
$jsonOptions['general']['ShowTextUntilAnImageAdded'] = $ShowTextUntilAnImageAdded;
// JSON only, no database option --- END

$jsonOptions['input']['Forward'] = $Forward;
$jsonOptions['input']['Forward_URL'] = $Forward_URL;
$jsonOptions['input']['Confirmation_Text'] = $confirmation_text;

$jsonOptions['pro']['ForwardAfterRegUrl'] = $ForwardAfterRegUrl;
$jsonOptions['pro']['ForwardAfterRegText'] = $ForwardAfterRegText;
$jsonOptions['pro']['ForwardAfterLoginUrlCheck'] = $ForwardAfterLoginUrlCheck;
$jsonOptions['pro']['ForwardAfterLoginUrl'] = $ForwardAfterLoginUrl;
$jsonOptions['pro']['ForwardAfterLoginTextCheck'] = $ForwardAfterLoginTextCheck;
$jsonOptions['pro']['ForwardAfterLoginText'] = $ForwardAfterLoginText;
$jsonOptions['pro']['TextEmailConfirmation'] = $TextEmailConfirmation;
$jsonOptions['pro']['TextAfterEmailConfirmation'] = $TextAfterEmailConfirmation;
$jsonOptions['pro']['RegMailAddressor'] = $RegMailAddressor;
$jsonOptions['pro']['RegMailReply'] = $RegMailReply;
$jsonOptions['pro']['RegMailSubject'] = $RegMailSubject;
$jsonOptions['pro']['RegUserUploadOnly'] = $RegUserUploadOnly;
$jsonOptions['pro']['RegUserUploadOnlyText'] = $RegUserUploadOnlyText;
$jsonOptions['pro']['Manipulate'] = $Manipulate;
$jsonOptions['pro']['Search'] = $Search;
$jsonOptions['pro']['GalleryUpload'] = $GalleryUpload;
$jsonOptions['pro']['GalleryUploadOnlyUser'] = $GalleryUploadOnlyUser;
$jsonOptions['pro']['GalleryUploadConfirmationText'] = $GalleryUploadConfirmationText;
$jsonOptions['pro']['GalleryUploadTextBefore'] = $GalleryUploadTextBefore;
$jsonOptions['pro']['GalleryUploadTextAfter'] = $GalleryUploadTextAfter;
$jsonOptions['pro']['ShowNickname'] = $ShowNickname;
$jsonOptions['pro']['ShowProfileImage'] = $ShowProfileImage;
$jsonOptions['pro']['MinusVote'] = $MinusVote;
$jsonOptions['pro']['SlideTransition'] = $SlideTransition;
$jsonOptions['pro']['VotesInTime'] = $VotesInTime;
$jsonOptions['pro']['VotesInTimeQuantity'] = $VotesInTimeQuantity;
$jsonOptions['pro']['VotesInTimeIntervalReadable'] = $VotesInTimeIntervalReadable;
$jsonOptions['pro']['VotesInTimeIntervalSeconds'] = $VotesInTimeIntervalSeconds;
$jsonOptions['pro']['VotesInTimeIntervalAlertMessage'] = $VotesInTimeIntervalAlertMessage;
$jsonOptions['pro']['ShowExif'] = $ShowExif;
$jsonOptions['pro']['SliderFullWindow'] = $SliderFullWindow;
$jsonOptions['pro']['CatWidget'] = $CatWidget;
$jsonOptions['pro']['ShowOther'] = $ShowOther;
$jsonOptions['pro']['ShowCatsUnchecked'] = $ShowCatsUnchecked;
$jsonOptions['pro']['ShowCatsUnfolded'] = $ShowCatsUnfolded;

$jsonOptions['pro']['HideRegFormAfterLogin'] = $HideRegFormAfterLogin;
$jsonOptions['pro']['HideRegFormAfterLoginShowTextInstead'] = $HideRegFormAfterLoginShowTextInstead;
$jsonOptions['pro']['HideRegFormAfterLoginTextToShow'] = $HideRegFormAfterLoginTextToShow;

$jsonOptions['pro']['RegUserGalleryOnly'] = $RegUserGalleryOnly;
$jsonOptions['pro']['RegUserGalleryOnlyText'] = $RegUserGalleryOnlyText;
$jsonOptions['pro']['RegUserMaxUpload'] = $RegUserMaxUpload;
$jsonOptions['pro']['RegUserMaxUploadPerCategory'] = $RegUserMaxUploadPerCategory;
$jsonOptions['pro']['IsModernFiveStar'] = $IsModernFiveStar;
$jsonOptions['pro']['VoteNotOwnImage'] = $VoteNotOwnImage;
$jsonOptions['pro']['PreselectSort'] = $PreselectSort;
$jsonOptions['pro']['UploadRequiresCookieMessage'] = $UploadRequiresCookieMessage;
$jsonOptions['pro']['RegMailOptional'] = $RegMailOptional;

$jsonOptions['pro']['CustomImageName'] = $CustomImageName;
$jsonOptions['pro']['CustomImageNamePath'] = $CustomImageNamePath;
$jsonOptions['pro']['VotePerCategory'] = $VotePerCategory;
$jsonOptions['pro']['VotesPerCategory'] = $VotesPerCategory;

$jsonOptions['pro']['VoteMessageSuccessActive'] = $VoteMessageSuccessActive;
$jsonOptions['pro']['VoteMessageWarningActive'] = $VoteMessageWarningActive;

$jsonOptions['pro']['VoteMessageSuccessText'] = $VoteMessageSuccessText;
$jsonOptions['pro']['VoteMessageWarningText'] = $VoteMessageWarningText;

$jsonOptions['visual']['CommentsDateFormat'] = $CommentsDateFormat;
$jsonOptions['general']['HideCommentNameField'] = $HideCommentNameField;
$jsonOptions['general']['RatingVisibleForGalleryNoVoting'] = $RatingVisibleForGalleryNoVoting;

$jsonOptions['pro']['CommNoteActive'] = $CommNoteActive;
$jsonOptions['pro']['ReviewComm'] = $ReviewComm;
$jsonOptions['pro']['FbLikeOnlyShare'] = $FbLikeOnlyShare;

$jsonOptions['pro']['AllowUploadJPG'] = $AllowUploadJPG;
$jsonOptions['pro']['AllowUploadPNG'] = $AllowUploadPNG;
$jsonOptions['pro']['AllowUploadGIF'] = $AllowUploadGIF;
$jsonOptions['pro']['AllowUploadICO'] = $AllowUploadICO;

$jsonOptions['pro']['AdditionalFiles'] = $AdditionalFiles;
$jsonOptions['pro']['AdditionalFilesCount'] = $AdditionalFilesCount;

$jsonOptions['visual']['ThankVote'] = $ThankVote;

$jsonOptions['visual']['EnableSwitchStyleGalleryButton'] = $EnableSwitchStyleGalleryButton;
$jsonOptions['visual']['SwitchStyleGalleryButtonOnlyTopControls'] = $SwitchStyleGalleryButtonOnlyTopControls;
$jsonOptions['visual']['ShareButtons'] = $ShareButtons;
$jsonOptions['visual']['TextBeforeWpPageEntry'] = $TextBeforeWpPageEntry;
$jsonOptions['visual']['TextAfterWpPageEntry'] = $TextAfterWpPageEntry;
$jsonOptions['visual']['ForwardToWpPageEntry'] = $ForwardToWpPageEntry;
$jsonOptions['visual']['ForwardToWpPageEntryInNewTab'] = $ForwardToWpPageEntryInNewTab;
$jsonOptions['visual']['ShowBackToGalleryButton'] = $ShowBackToGalleryButton;
$jsonOptions['visual']['BackToGalleryButtonText'] = $BackToGalleryButtonText;
$jsonOptions['visual']['TextDeactivatedEntry'] = $TextDeactivatedEntry;
$jsonOptions['visual']['AdditionalCssEntryLandingPage'] = $AdditionalCssEntryLandingPage;
$jsonOptions['visual']['AdditionalCssGalleryPage'] = $AdditionalCssGalleryPage;

$jsonOptions['visual']['EnableSwitchStyleImageViewButton'] = $EnableSwitchStyleImageViewButton;
$jsonOptions['visual']['SwitchStyleImageViewButtonOnlyImageView'] = $SwitchStyleImageViewButtonOnlyImageView;
$jsonOptions['visual']['EnableEmojis'] = $EnableEmojis;// only json option, not in database
$jsonOptions['pro']['CheckLoginComment'] = $CheckLoginComment;// only json option, not in database
$jsonOptions['pro']['InformUserVote'] = $InformUserVote;// is in contest_gal1ery_mail_user_vote table
$jsonOptions['pro']['InformUserVoteMailInterval'] = $InformUserVoteMailInterval;// is in contest_gal1ery_mail_user_vote table
$jsonOptions['pro']['InformUserComment'] = $InformUserComment;// is in contest_gal1ery_mail_user_vote table
$jsonOptions['pro']['InformUserCommentMailInterval'] = $InformUserCommentMailInterval;// is in contest_gal1ery_mail_user_vote table
$jsonOptions['pro']['BackToGalleryButtonURL'] = $BackToGalleryButtonURL;
$jsonOptions['pro']['WpPageParentRedirectURL'] = $WpPageParentRedirectURL;
$jsonOptions['pro']['RedirectURLdeletedEntry'] = $RedirectURLdeletedEntry;
/*$jsonOptions['pro']['BulkUploadType'] = $BulkUploadType;
$jsonOptions['pro']['UploadFormAppearance'] = $UploadFormAppearance;*/
