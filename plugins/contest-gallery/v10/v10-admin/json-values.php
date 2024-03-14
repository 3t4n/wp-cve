<?php

// general values

$GalleryName = '';
$PicsPerSite = 20;
$WidthThumb = 300;
$HeightThumb = 200;
$WidthGallery = 640;

$HeightGallery = 400;
$DistancePics = 15;
$DistancePicsV = 8;

$MinResJPGon = 0;
$MinResPNGon = 0;
$MinResGIFon = 0;

$MaxResJPGon = 1;
$MaxResPNGon = 1;
$MaxResGIFon = 1;
$MaxResICOon = 1;

$MaxResJPG = 25000000;
$MaxResJPGwidth = 2000;
$MaxResJPGheight = 2000;
$MaxResPNG = 25000000;
$MaxResPNGwidth = 2000;
$MaxResPNGheight = 2000;
$MaxResGIF = 25000000;
$MaxResGIFwidth = 2000;
$MaxResGIFheight = 2000;

$MaxResICOwidth = 2000;
$MaxResICOheight = 2000;

$MinResJPGwidth = 800;
$MinResJPGheight = 800;
$MinResPNGwidth = 800;
$MinResPNGheight = 800;
$MinResGIFwidth = 800;
$MinResGIFheight = 800;

$OnlyGalleryView = 0;
$SinglePicView = 0;
$ScaleOnly = 1;
$ScaleAndCut = 0;
$FullSize = 1;
$FullSizeGallery = 1;
$FullSizeSlideOutStart = 0;

$AllowSort = 1;
$RandomSort = 0;
$RandomSortButton = 1;
$AllowComments = 1;
$CommentsOutGallery = 1;
$AllowRating = 2;
$VotesPerUser = 0;
$RatingOutGallery = 0;
$ShowAlways = 3;
$ShowAlwaysInfoSlider = 0;
$IpBlock = 0;
$CheckLogin = 0;
$CheckIp = 1;
$CheckCookie = 0;
$CheckCookieAlertMessage = 'Please allow cookies to vote';

$FbLike = 0;
$FbLikeGallery = 0;
$FbLikeGalleryVote = 0;
$AllowGalleryScript = 0;
$InfiniteScroll = 0;
$FullSizeImageOutGallery = 0;
$FullSizeImageOutGalleryNewTab = 0;
$Inform = 0;
$InformAdmin = 0;
$TimestampPicDownload = 0;

$ThumbLook = 1;
$AdjustThumbLook = 1;
$HeightLook = 1;
$RowLook = 1;
$SliderLook = 1;
$BlogLook = 1;
$BlogLookFullWindow = 1;
$SliderThumbNav = 1;

$ImageViewFullWindow = 1;
$ImageViewFullScreen = 1;

$ThumbLookOrder = 1;
$HeightLookOrder = 2;
$RowLookOrder = 3;
$SliderLookOrder = 4;
$BlogLookOrder = 5;

$HeightLookHeight = 300;
$ThumbsInRow = 1;
$PicsInRow = 3;
$LastRow = 2;
$HideUntilVote = 0;
$HideInfo = 3;
$ActivateUpload = 1;
$ContestEnd = 3;// old logic
$ContestEndTime = '';

$ForwardToURL = 1;
$ForwardFrom = 1;
$ForwardType = 0;
$ActivatePostMaxMB = 1; // since 07.05.2022 activated when creating a gallery
$PostMaxMB = 2;
$ActivatePostMaxMBfile = 1; // since 07.05.2022 activated when creating a gallery
$PostMaxMBfile = 2;
$ActivateBulkUpload = 0;
$BulkUploadQuantity = 3;
$BulkUploadMinQuantity = 2;
$ShowOnlyUsersVotes = 0;
$FbLikeGoToGalleryLink = '';

// might be empty when getting values from some function like cg_create_registry_and_login_options_v14
if(empty($dbVersion)){
    $dbVersion = get_option( "p_cgal1ery_db_version" );
}

$Version = $dbVersion;

// visual

$CommentsAlignGallery = 'left';
$RatingAlignGallery = 'left';

$Field1IdGalleryView = '';
$Field1AlignGalleryView = 'left';
$Field2IdGalleryView = '';
$Field2AlignGalleryView = 'left';
$Field3IdGalleryView = '';
$Field3AlignGalleryView = 'left';

$ThumbViewBorderWidth = 0;
$ThumbViewBorderRadius = 0;
$ThumbViewBorderColor = '#000000';
$ThumbViewBorderOpacity = 1;
$HeightViewBorderWidth = 0;
$HeightViewBorderRadius = 0;
$HeightViewBorderColor = '#000000';
$HeightViewBorderOpacity = 1;
$HeightViewSpaceWidth = 15;
$HeightViewSpaceHeight = 8;

$RowViewBorderWidth = 0;
$RowViewBorderRadius = 0;
$RowViewBorderColor = '#000000';
$RowViewBorderOpacity = 1;
$RowViewSpaceWidth = 3;
$RowViewSpaceHeight = 3;
$TitlePositionGallery = 1;
$RatingPositionGallery = 1;
$CommentPositionGallery = 1;
$ActivateGalleryBackgroundColor = 0;

$GalleryBackgroundColor = '#000000';
$GalleryBackgroundOpacity = 1;
$OriginalSourceLinkInSlider = 1;
$PreviewInSlider = 1;
$FeControlsStyle = 'white';
$FeControlsStyleUpload = 'white';
$FeControlsStyleRegistry = 'white';
$FeControlsStyleLogin = 'white';

// input
$Forward = 0;
$Forward_URL = '';

$ShowExif = 0;

if(function_exists('exif_read_data')){
    $ShowExif = 1;
}

// pro


$ForwardAfterRegUrl = '';

$ForwardAfterLoginUrlCheck = 0;
$ForwardAfterLoginUrl = '';

$ForwardAfterLoginTextCheck = 1;


$RegUserUploadOnly = 3;// IP tracking
$Manipulate = 1;
$ShowOther = 1;
$CatWidget = 1;
$Search = 1;
$GalleryUpload = 1;
$GalleryUploadOnlyUser = 0;
$GalleryUploadTextAfter = '';
$ShowNickname = 0;
$ShowProfileImage = 0;
$MinusVote = 0;
$SlideTransition = 'translateX';
$VotesInTime = 0;
$VotesInTimeQuantity = 1;
$VotesInTimeIntervalReadable = '24:00';
$VotesInTimeIntervalSeconds = 86400;
$VotesInTimeIntervalAlertMessage = "You can vote only 1 time per day";
$SliderFullWindow = 0;

$HideRegFormAfterLogin = 0;
$HideRegFormAfterLoginShowTextInstead = 0;
$HideRegFormAfterLoginTextToShow = '';

$RegistryUserRole = '';
$RegistryUserRoleForRegistryAndLoginOptions = 'contest_gallery_user_since_v14';

$RegUserGalleryOnly = 0;
$RegUserGalleryOnlyText = 'You have to be registered and logged in to see the gallery.';
$RegUserMaxUpload = 0;
$RegUserMaxUploadPerCategory = 0;
$ContestStart = 0;
$ContestStartTime = '';

$IsModernFiveStar = 1;// all new created galleries are modern five star

$FbLikeNoShare = 0;
$FbLikeOnlyShare = 0;
$VoteNotOwnImage = 0;

$PreselectSort = 'custom';

$UploadRequiresCookieMessage = 'Please allow cookies to upload';

$AllowSortOptions = 'custom,date-desc,date-asc,rate-desc,rate-asc,rate-average-desc,rate-average-asc,rate-sum-desc,rate-sum-asc,comment-desc,comment-asc,random';
$GalleryStyle = 'center-white';

$ShowCatsUnchecked = 1;
$ShowCatsUnfolded = 1;
$RegMailOptional = 0;

$CustomImageName = 0;
$CustomImageNamePath = '';

$DeleteFromStorageIfDeletedInFrontend = 0;
$VotePerCategory = 0;
$VotesPerCategory = 0;

$BorderRadius = 1;
$BorderRadiusUpload = 1;
$BorderRadiusRegistry = 1;
$BorderRadiusLogin = 1;
$CopyImageLink = 1;
$CopyOriginalFileLink = 1;
$ForwardOriginalFile = 1;

$ShowExifModel = 1;
$ShowExifApertureFNumber = 1;
$ShowExifExposureTime = 1;
$ShowExifISOSpeedRatings = 1;
$ShowExifFocalLength = 1;
###NORMAL###
$ShowExifDateTimeOriginal = 0;
###NORMAL---END###
$ShowExifDateTimeOriginalFormat = 'YYYY-MM-DD';
$CommentsDateFormat = 'YYYY-MM-DD';

$VoteMessageSuccessActive = 0;
$VoteMessageWarningActive = 0;
$HideCommentNameField = 0;
$RatingVisibleForGalleryNoVoting = 0;

$VoteMessageSuccessText = '';
$VoteMessageWarningText = '';

// JSON only, no database option
$ShowTextUntilAnImageAdded = '<p><b>This text is visible until first entry appears in the gallery.</b>
<br/>This text can be configurated in "Edit options" >>> "Gallery view options" >>> "This text is visible until first entry appears in the gallery"
</p>';
$ShowTextUntilAnImageAdded = htmlentities($ShowTextUntilAnImageAdded, ENT_QUOTES);
// JSON only, no database option --- END

$CommNoteActive = 0;
/*$BulkUploadType = 1;
$UploadFormAppearance = 2;*/

$EnableSwitchStyleGalleryButton = 1;
$SwitchStyleGalleryButtonOnlyTopControls = 0;

$EnableSwitchStyleImageViewButton = 1;
$SwitchStyleImageViewButtonOnlyImageView = 0;

$ThankVote = 1;

$GoogleSignInUserUploadOnlyText = 'Please sign in via Google to upload your files.';

$EnableEmojis = 1;
$CheckLoginComment = 0;// only json option, not in database

$AllowUploadJPG = 1;
$AllowUploadPNG = 1;
$AllowUploadGIF = 1;
$AllowUploadICO = 1;

$AdditionalFiles = 0;
$AdditionalFilesCount = 2;
$ReviewComm = 0;
$InformUserVote = 0;
$InformUserVoteMailInterval = '24h';
$InformUserComment = 0;
$InformUserCommentMailInterval = '24h';
if(cg_get_version()=='contest-gallery-pro'){$AdditionalFilesCount = 9;}

// version 21 release values here
include ('json-values-21-version-release.php');
