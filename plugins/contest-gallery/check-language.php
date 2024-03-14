<?php
if(!defined('ABSPATH')){exit;}
$is_admin = is_admin();
//$is_frontend = (!$is_admin) ? true : false;
if(empty($is_frontend)){
    $is_frontend = false;
}

$domainDefault = 'default';
$domain = 'contest-gallery';
$domainBackend = 'contest-gallery';

$wp_upload_dir = wp_upload_dir();

if(empty($galeryID) AND !empty($GalleryID)){
    $galeryID = $GalleryID;
}

$translationsFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/'.$galeryID.'-translations.json';

$translations = array();
if(empty($translation['pro'])){$translations['pro'] = array();}

if(file_exists($translationsFile)){
    $fp = fopen($translationsFile, 'r');
    $translationsFromFile =json_decode(fread($fp,filesize($translationsFile)),true);
    fclose($fp);

    if(count($translationsFromFile)){
        foreach($translationsFromFile as $translationKey => $translation) {
            if(is_array($translation)){// then must be PRO
                foreach($translation as $translationProKey => $translationProValue) {
               //     var_dump($translationProValue);
                        $translations[$translationKey][$translationProKey]  = contest_gal1ery_convert_for_html_output_without_nl2br($translationProValue);// is for html output this why without nl2br
                   // var_dump($translations[$translationKey][$translationProKey] );
                }
            }else{
                $translations[$translationKey] = contest_gal1ery_convert_for_html_output($translation);
            }
        }
    }else{
        $translations = $translationsFromFile;
    }

}

//PRO json translations
if(empty($translations['pro']['VotesPerUserAllVotesUsedHtmlMessage'])){$translations['pro']['VotesPerUserAllVotesUsedHtmlMessage'] = '';}
$language_VotesPerUserAllVotesUsedHtmlMessage = $translations['pro']['VotesPerUserAllVotesUsedHtmlMessage'];
//PRO json translations --- END

// Gallery icons

__('Participation form');$l_ImageUpload = "Participation form"; $language_ImageUpload = (!empty($translations[$l_ImageUpload]) && $is_frontend) ? $translations[$l_ImageUpload] : ((empty(trim(__($l_ImageUpload,$domain)))) ? __($l_ImageUpload,$domainDefault) : __($l_ImageUpload,$domain)); if(empty($translations[$l_ImageUpload])){$translations[$l_ImageUpload]='';}

__('Minimize participation form');$l_MinimizeUploadForm = "Minimize participation form"; $language_MinimizeUploadForm = (!empty($translations[$l_MinimizeUploadForm]) && $is_frontend) ? $translations[$l_MinimizeUploadForm] : ((empty(trim(__($l_MinimizeUploadForm,$domain)))) ? __($l_MinimizeUploadForm,$domainDefault) : __($l_MinimizeUploadForm,$domain)); if(empty($translations[$l_MinimizeUploadForm])){$translations[$l_MinimizeUploadForm]='';}

__('Further entry');$l_FurtherUpload = "Further entry"; $language_FurtherUpload = (!empty($translations[$l_FurtherUpload]) && $is_frontend) ? $translations[$l_FurtherUpload] : ((empty(trim(__($l_FurtherUpload,$domain)))) ? __($l_FurtherUpload,$domainDefault) : __($l_FurtherUpload,$domain)); if(empty($translations[$l_FurtherUpload])){$translations[$l_FurtherUpload]='';}

__('Search');$l_Search = "Search"; $language_Search = (!empty($translations[$l_Search]) && $is_frontend) ? $translations[$l_Search] : ((empty(trim(__($l_Search,$domain)))) ? __($l_Search,$domainDefault) : __($l_Search,$domain)); if(empty($translations[$l_Search])){$translations[$l_Search]='';}

__('Sort by');$l_SortBy = "Sort by"; $language_SortBy = (!empty($translations[$l_SortBy]) && $is_frontend) ? $translations[$l_SortBy] : ((empty(trim(__($l_SortBy,$domain)))) ? __($l_SortBy,$domainDefault) : __($l_SortBy,$domain)); if(empty($translations[$l_SortBy])){$translations[$l_SortBy]='';}

__('Random sort');$l_RandomSortIcon = "Random sort"; $language_RandomSortIcon = (!empty($translations[$l_RandomSortIcon]) && $is_frontend) ? $translations[$l_RandomSortIcon] : ((empty(trim(__($l_RandomSortIcon,$domain)))) ? __($l_RandomSortIcon,$domainDefault) : __($l_RandomSortIcon,$domain)); if(empty($translations[$l_RandomSortIcon])){$translations[$l_RandomSortIcon]='';}

__('Show all categories');$l_ShowAllCategories = "Show all categories"; $language_ShowAllCategories = (!empty($translations[$l_ShowAllCategories]) && $is_frontend) ? $translations[$l_ShowAllCategories] : ((empty(trim(__($l_ShowAllCategories,$domain)))) ? __($l_ShowAllCategories,$domainDefault) : __($l_ShowAllCategories,$domain)); if(empty($translations[$l_ShowAllCategories])){$translations[$l_ShowAllCategories]='';}

__('Show less categories');$l_ShowLessCategories = "Show less categories"; $language_ShowLessCategories = (!empty($translations[$l_ShowLessCategories]) && $is_frontend) ? $translations[$l_ShowLessCategories] : ((empty(trim(__($l_ShowLessCategories,$domain)))) ? __($l_ShowLessCategories,$domainDefault) : __($l_ShowLessCategories,$domain)); if(empty($translations[$l_ShowLessCategories])){$translations[$l_ShowLessCategories]='';}

__('Height view');$l_HeightView = "Height view"; $language_HeightView = (!empty($translations[$l_HeightView]) && $is_frontend) ? $translations[$l_HeightView] : ((empty(trim(__($l_HeightView,$domain)))) ? __($l_HeightView,$domainDefault) : __($l_HeightView,$domain)); if(empty($translations[$l_HeightView])){$translations[$l_HeightView]='';}

__('Thumb view');$l_ThumbView = "Thumb view"; $language_ThumbView = (!empty($translations[$l_ThumbView]) && $is_frontend) ? $translations[$l_ThumbView] : ((empty(trim(__($l_ThumbView,$domain)))) ? __($l_ThumbView,$domainDefault) : __($l_ThumbView,$domain)); if(empty($translations[$l_ThumbView])){$translations[$l_ThumbView]='';}

__('Row view');$l_RowView = "Row view"; $language_RowView = (!empty($translations[$l_RowView]) && $is_frontend) ? $translations[$l_RowView] : ((empty(trim(__($l_RowView,$domain)))) ? __($l_RowView,$domainDefault) : __($l_RowView,$domain)); if(empty($translations[$l_RowView])){$translations[$l_RowView]='';}

__('Slider view');$l_SliderView = "Slider view"; $language_SliderView = (!empty($translations[$l_SliderView]) && $is_frontend) ? $translations[$l_SliderView] : ((empty(trim(__($l_SliderView,$domain)))) ? __($l_SliderView,$domainDefault) : __($l_SliderView,$domain)); if(empty($translations[$l_SliderView])){$translations[$l_SliderView]='';}

__('Blog view');$l_BlogView = "Blog view"; $language_BlogView = (!empty($translations[$l_BlogView]) && $is_frontend) ? $translations[$l_BlogView] : ((empty(trim(__($l_BlogView,$domain)))) ? __($l_BlogView,$domainDefault) : __($l_BlogView,$domain)); if(empty($translations[$l_BlogView])){$translations[$l_BlogView]='';}

__('Dark style');$l_DarkStyle = "Dark style"; $language_DarkStyle = (!empty($translations[$l_DarkStyle]) && $is_frontend) ? $translations[$l_DarkStyle] : ((empty(trim(__($l_DarkStyle,$domain)))) ? __($l_DarkStyle,$domainDefault) : __($l_DarkStyle,$domain)); if(empty($translations[$l_DarkStyle])){$translations[$l_DarkStyle]='';}

__('Bright style');$l_BrightStyle = "Bright style"; $language_BrightStyle = (!empty($translations[$l_BrightStyle]) && $is_frontend) ? $translations[$l_BrightStyle] : ((empty(trim(__($l_BrightStyle,$domain)))) ? __($l_BrightStyle,$domainDefault) : __($l_BrightStyle,$domain)); if(empty($translations[$l_BrightStyle])){$translations[$l_BrightStyle]='';}

__('Select page');$l_SelectPage = "Select page"; $language_SelectPage = (!empty($translations[$l_SelectPage]) && $is_frontend) ? $translations[$l_SelectPage] : ((empty(trim(__($l_SelectPage,$domain)))) ? __($l_SelectPage,$domainDefault) : __($l_SelectPage,$domain)); if(empty($translations[$l_SelectPage])){$translations[$l_SelectPage]='';}

__('Previous page');$l_PreviousPage = "Previous page"; $language_PreviousPage = (!empty($translations[$l_PreviousPage]) && $is_frontend) ? $translations[$l_PreviousPage] : ((empty(trim(__($l_PreviousPage,$domain)))) ? __($l_PreviousPage,$domainDefault) : __($l_PreviousPage,$domain)); if(empty($translations[$l_PreviousPage])){$translations[$l_PreviousPage]='';}

__('Next page');$l_NextPage = "Next page"; $language_NextPage = (!empty($translations[$l_NextPage]) && $is_frontend) ? $translations[$l_NextPage] : ((empty(trim(__($l_NextPage,$domain)))) ? __($l_NextPage,$domainDefault) : __($l_NextPage,$domain)); if(empty($translations[$l_NextPage])){$translations[$l_NextPage]='';}

__('Previous entry');$l_PreviousImage = "Previous entry"; $language_PreviousImage = (!empty($translations[$l_PreviousImage]) && $is_frontend) ? $translations[$l_PreviousImage] : ((empty(trim(__($l_PreviousImage,$domain)))) ? __($l_PreviousImage,$domainDefault) : __($l_PreviousImage,$domain)); if(empty($translations[$l_PreviousImage])){$translations[$l_PreviousImage]='';}

__('Next entry');$l_NextImage = "Next entry"; $language_NextImage = (!empty($translations[$l_NextImage]) && $is_frontend) ? $translations[$l_NextImage] : ((empty(trim(__($l_NextImage,$domain)))) ? __($l_NextImage,$domainDefault) : __($l_NextImage,$domain)); if(empty($translations[$l_NextImage])){$translations[$l_NextImage]='';}

__('Previous file in entry');$l_PreviousFileInEntry = "Previous file in entry"; $language_PreviousFileInEntry = (!empty($translations[$l_PreviousFileInEntry]) && $is_frontend) ? $translations[$l_PreviousFileInEntry] : ((empty(trim(__($l_PreviousFileInEntry,$domain)))) ? __($l_PreviousFileInEntry,$domainDefault) : __($l_PreviousFileInEntry,$domain)); if(empty($translations[$l_PreviousFileInEntry])){$translations[$l_PreviousFileInEntry]='';}

__('Next file in entry');$l_NextFileInEntry = "Next file in entry"; $language_NextFileInEntry = (!empty($translations[$l_NextFileInEntry]) && $is_frontend) ? $translations[$l_NextFileInEntry] : ((empty(trim(__($l_NextFileInEntry,$domain)))) ? __($l_NextFileInEntry,$domainDefault) : __($l_NextFileInEntry,$domain)); if(empty($translations[$l_NextFileInEntry])){$translations[$l_NextFileInEntry]='';}

__('Go to top of the gallery');$l_GoToTopOfTheGallery = "Go to top of the gallery"; $language_GoToTopOfTheGallery = (!empty($translations[$l_GoToTopOfTheGallery]) && $is_frontend) ? $translations[$l_GoToTopOfTheGallery] : ((empty(trim(__($l_GoToTopOfTheGallery,$domain)))) ? __($l_GoToTopOfTheGallery,$domainDefault) : __($l_GoToTopOfTheGallery,$domain)); if(empty($translations[$l_GoToTopOfTheGallery])){$translations[$l_GoToTopOfTheGallery]='';}

__('Search or sort');$l_SearchOrSort = "Search or sort"; $language_SearchOrSort = (!empty($translations[$l_SearchOrSort]) && $is_frontend) ? $translations[$l_SearchOrSort] : ((empty(trim(__($l_SearchOrSort,$domain)))) ? __($l_SearchOrSort,$domainDefault) : __($l_SearchOrSort,$domain)); if(empty($translations[$l_SearchOrSort])){$translations[$l_SearchOrSort]='';}

__('Close');$l_Close = "Close"; $language_Close = (!empty($translations[$l_Close]) && $is_frontend) ? $translations[$l_Close] : ((empty(trim(__($l_Close,$domain)))) ? __($l_Close,$domainDefault) : __($l_Close,$domain)); if(empty($translations[$l_Close])){$translations[$l_Close]='';}

__('Open gallery in full window');$l_FullWindow = "Open gallery in full window"; $language_FullWindow = (!empty($translations[$l_FullWindow]) && $is_frontend) ? $translations[$l_FullWindow] : ((empty(trim(__($l_FullWindow,$domain)))) ? __($l_FullWindow,$domainDefault) : __($l_FullWindow,$domain)); if(empty($translations[$l_FullWindow])){$translations[$l_FullWindow]='';}

__('Close view');$l_CloseView= "Close view";$language_CloseView = (!empty($translations[$l_CloseView]) && $is_frontend) ? $translations[$l_CloseView] : ((empty(trim(__($l_CloseView,$domain)))) ? __($l_CloseView,$domainDefault) : __($l_CloseView,$domain)); if(empty($translations[$l_CloseView])){$translations[$l_CloseView]='';}

__('Full screen mode');$l_FullScreen = "Full screen mode"; $language_FullScreen = (!empty($translations[$l_FullScreen]) && $is_frontend) ? $translations[$l_FullScreen] : ((empty(trim(__($l_FullScreen,$domain)))) ? __($l_FullScreen,$domainDefault) : __($l_FullScreen,$domain)); if(empty($translations[$l_FullScreen])){$translations[$l_FullScreen]='';}

__('Close full screen mode');$l_CloseFullScreen = "Close full screen"; $language_CloseFullScreen = (!empty($translations[$l_CloseFullScreen]) && $is_frontend) ? $translations[$l_CloseFullScreen] : ((empty(trim(__($l_CloseFullScreen,$domain)))) ? __($l_CloseFullScreen,$domainDefault) : __($l_CloseFullScreen,$domain)); if(empty($translations[$l_CloseFullScreen])){$translations[$l_CloseFullScreen]='';}

__('Copy gallery file link');$l_CopyGalleryFileLink = "Copy gallery file link"; $language_CopyGalleryFileLink = (!empty($translations[$l_CopyGalleryFileLink]) && $is_frontend) ? $translations[$l_CopyGalleryFileLink] : ((empty(trim(__($l_CopyGalleryFileLink,$domain)))) ? __($l_CopyGalleryFileLink,$domainDefault) : __($l_CopyGalleryFileLink,$domain)); if(empty($translations[$l_CopyGalleryFileLink])){$translations[$l_CopyGalleryFileLink]='';}

__('Copy gallery entry link');$l_CopyGalleryEntryLink = "Copy gallery entry link"; $language_CopyGalleryEntryLink = (!empty($translations[$l_CopyGalleryEntryLink]) && $is_frontend) ? $translations[$l_CopyGalleryEntryLink] : ((empty(trim(__($l_CopyGalleryEntryLink,$domain)))) ? __($l_CopyGalleryEntryLink,$domainDefault) : __($l_CopyGalleryEntryLink,$domain)); if(empty($translations[$l_CopyGalleryEntryLink])){$translations[$l_CopyGalleryEntryLink]='';}

__('Copy original file source link');$l_CopyOriginalFileSourceLink = "Copy original file source link"; $language_CopyOriginalFileSourceLink = (!empty($translations[$l_CopyOriginalFileSourceLink]) && $is_frontend) ? $translations[$l_CopyOriginalFileSourceLink] : ((empty(trim(__($l_CopyOriginalFileSourceLink,$domain)))) ? __($l_CopyOriginalFileSourceLink,$domainDefault) : __($l_CopyOriginalFileSourceLink,$domain)); if(empty($translations[$l_CopyOriginalFileSourceLink])){$translations[$l_CopyOriginalFileSourceLink]='';}

__('Share to');$l_ShareTo = "Share to"; $language_ShareTo = (!empty($translations[$l_ShareTo]) && $is_frontend) ? $translations[$l_ShareTo] : ((empty(trim(__($l_ShareTo,$domain)))) ? __($l_ShareTo,$domainDefault) : __($l_ShareTo,$domain)); if(empty($translations[$l_ShareTo])){$translations[$l_ShareTo]='';}

__('Open original file in new tab');$l_OpenOriginalFileInNewTab = "Open original file in new tab"; $language_OpenOriginalFileInNewTab = (!empty($translations[$l_OpenOriginalFileInNewTab]) && $is_frontend) ? $translations[$l_OpenOriginalFileInNewTab] : ((empty(trim(__($l_OpenOriginalFileInNewTab,$domain)))) ? __($l_OpenOriginalFileInNewTab,$domainDefault) : __($l_OpenOriginalFileInNewTab,$domain)); if(empty($translations[$l_OpenOriginalFileInNewTab])){$translations[$l_OpenOriginalFileInNewTab]='';}

__('Download original file');$l_DownloadOriginalFile = "Download original file"; $language_DownloadOriginalFile = (!empty($translations[$l_DownloadOriginalFile]) && $is_frontend) ? $translations[$l_DownloadOriginalFile] : ((empty(trim(__($l_DownloadOriginalFile,$domain)))) ? __($l_DownloadOriginalFile,$domainDefault) : __($l_DownloadOriginalFile,$domain)); if(empty($translations[$l_DownloadOriginalFile])){$translations[$l_DownloadOriginalFile]='';}

__('Download');$l_Download = "Download"; $language_Download = (!empty($translations[$l_Download]) && $is_frontend) ? $translations[$l_Download] : ((empty(trim(__($l_Download,$domain)))) ? __($l_Download,$domainDefault) : __($l_Download,$domain)); if(empty($translations[$l_Download])){$translations[$l_Download]='';}

__('Delete entry');$l_DeleteImage = "Delete entry"; $language_DeleteImage = (!empty($translations[$l_DeleteImage]) && $is_frontend) ? $translations[$l_DeleteImage] : ((empty(trim(__($l_DeleteImage,$domain)))) ? __($l_DeleteImage,$domainDefault) : __($l_DeleteImage,$domain)); if(empty($translations[$l_DeleteImage])){$translations[$l_DeleteImage]='';}

__('Delete entries');$l_DeleteImages = "Delete entries"; $language_DeleteImages = (!empty($translations[$l_DeleteImages]) && $is_frontend) ? $translations[$l_DeleteImages] : ((empty(trim(__($l_DeleteImages,$domain)))) ? __($l_DeleteImages,$domainDefault) : __($l_DeleteImages,$domain)); if(empty($translations[$l_DeleteImages])){$translations[$l_DeleteImages]='';}

__('Show more info');$l_ShowMoreInfo = "Show more info"; $language_ShowMoreInfo = (!empty($translations[$l_ShowMoreInfo]) && $is_frontend) ? $translations[$l_ShowMoreInfo] : ((empty(trim(__($l_ShowMoreInfo,$domain)))) ? __($l_ShowMoreInfo,$domainDefault) : __($l_ShowMoreInfo,$domain)); if(empty($translations[$l_ShowMoreInfo])){$translations[$l_ShowMoreInfo]='';}

__('Show less info');$l_ShowLessInfo = "Show less info"; $language_ShowLessInfo = (!empty($translations[$l_ShowLessInfo]) && $is_frontend) ? $translations[$l_ShowLessInfo] : ((empty(trim(__($l_ShowLessInfo,$domain)))) ? __($l_ShowLessInfo,$domainDefault) : __($l_ShowLessInfo,$domain)); if(empty($translations[$l_ShowLessInfo])){$translations[$l_ShowLessInfo]='';}

__('Add comment');$l_AddComment = "Add comment"; $language_AddComment = (!empty($translations[$l_AddComment]) && $is_frontend) ? $translations[$l_AddComment] : ((empty(trim(__($l_AddComment,$domain)))) ? __($l_AddComment,$domainDefault) : __($l_AddComment,$domain)); if(empty($translations[$l_AddComment])){$translations[$l_AddComment]='';}

__('Show more comments');$l_ShowMoreComments = "Show more comments"; $language_ShowMoreComments = (!empty($translations[$l_ShowMoreComments]) && $is_frontend) ? $translations[$l_ShowMoreComments] : ((empty(trim(__($l_ShowMoreComments,$domain)))) ? __($l_ShowMoreComments,$domainDefault) : __($l_ShowMoreComments,$domain)); if(empty($translations[$l_ShowMoreComments])){$translations[$l_ShowMoreComments]='';}

__('Show less comments');$l_ShowLessComments = "Show less comments"; $language_ShowLessComments = (!empty($translations[$l_ShowLessComments]) && $is_frontend) ? $translations[$l_ShowLessComments] : ((empty(trim(__($l_ShowLessComments,$domain)))) ? __($l_ShowLessComments,$domainDefault) : __($l_ShowLessComments,$domain)); if(empty($translations[$l_ShowLessComments])){$translations[$l_ShowLessComments]='';}

__('Add an emoji');$l_AddAnEmoji = "Add an emoji"; $language_AddAnEmoji = (!empty($translations[$l_AddAnEmoji]) && $is_frontend) ? $translations[$l_AddAnEmoji] : ((empty(trim(__($l_AddAnEmoji,$domain)))) ? __($l_AddAnEmoji,$domainDefault) : __($l_AddAnEmoji,$domain)); if(empty($translations[$l_AddAnEmoji])){$translations[$l_AddAnEmoji]='';}

__('Hide emojis');$l_HideEmojis = "Hide emojis"; $language_HideEmojis = (!empty($translations[$l_HideEmojis]) && $is_frontend) ? $translations[$l_HideEmojis] : ((empty(trim(__($l_HideEmojis,$domain)))) ? __($l_HideEmojis,$domainDefault) : __($l_HideEmojis,$domain)); if(empty($translations[$l_HideEmojis])){$translations[$l_HideEmojis]='';}

__('Vote');$l_VoteNow = "Vote"; $language_VoteNow = (!empty($translations[$l_VoteNow]) && $is_frontend) ? $translations[$l_VoteNow] : ((empty(trim(__($l_VoteNow,$domain)))) ? __($l_VoteNow,$domainDefault) : __($l_VoteNow,$domain)); if(empty($translations[$l_VoteNow])){$translations[$l_VoteNow]='';}

__('Thank you for voting');$l_ThankYouForVoting = "Thank you for voting"; $language_ThankYouForVoting = (!empty($translations[$l_ThankYouForVoting]) && $is_frontend) ? $translations[$l_ThankYouForVoting] : ((empty(trim(__($l_ThankYouForVoting,$domain)))) ? __($l_ThankYouForVoting,$domainDefault) : __($l_ThankYouForVoting,$domain)); if(empty($translations[$l_ThankYouForVoting])){$translations[$l_ThankYouForVoting]='';}

__('Undo your last vote');$l_UndoYourLastVote = "Undo your last vote"; $language_UndoYourLastVote = (!empty($translations[$l_UndoYourLastVote]) && $is_frontend) ? $translations[$l_UndoYourLastVote] : ((empty(trim(__($l_UndoYourLastVote,$domain)))) ? __($l_UndoYourLastVote,$domainDefault) : __($l_UndoYourLastVote,$domain)); if(empty($translations[$l_UndoYourLastVote])){$translations[$l_UndoYourLastVote]='';}

// Gallery
__('of');$l_of = "of"; $language_of = (!empty($translations[$l_of]) && $is_frontend) ? $translations[$l_of] : (empty((trim(__($l_of,$domain)))) ? __($l_of,$domainDefault) : __($l_of,$domain)); if(empty($translations[$l_of])){$translations[$l_of]='';}

__('No files found');$l_NoImagesFound = "No files found"; $language_NoImagesFound = (!empty($translations[$l_NoImagesFound]) && $is_frontend) ? $translations[$l_NoImagesFound] : (empty((trim(__($l_NoImagesFound,$domain)))) ? __($l_NoImagesFound,$domainDefault) : __($l_NoImagesFound,$domain)); if(empty($translations[$l_NoImagesFound])){$translations[$l_NoImagesFound]='';}

__('No entries found');$l_NoEntriesFound = "No entries found"; $language_NoEntriesFound = (!empty($translations[$l_NoEntriesFound]) && $is_frontend) ? $translations[$l_NoEntriesFound] : (empty((trim(__($l_NoEntriesFound,$domain)))) ? __($l_NoEntriesFound,$domainDefault) : __($l_NoEntriesFound,$domain)); if(empty($translations[$l_NoEntriesFound])){$translations[$l_NoEntriesFound]='';}

__('Random');$l_RandomSortSorting = "Random"; $language_RandomSortSorting = (!empty($translations[$l_RandomSortSorting]) && $is_frontend) ? $translations[$l_RandomSortSorting] : (empty((trim(__($l_RandomSortSorting,$domain)))) ? __($l_RandomSortSorting,$domainDefault) : __($l_RandomSortSorting,$domain)); if(empty($translations[$l_RandomSortSorting])){$translations[$l_RandomSortSorting]='';}

__('Custom');$l_Custom = "Custom"; $language_Custom = (!empty($translations[$l_Custom]) && $is_frontend) ? $translations[$l_Custom] : ((empty(trim(__($l_Custom,$domain)))) ? __($l_Custom,$domainDefault) : __($l_Custom,$domain)); if(empty($translations[$l_Custom])){$translations[$l_Custom]='';}

__('Date descend');$l_DateDescend = "Date descend"; $language_DateDescend = (!empty($translations[$l_DateDescend]) && $is_frontend) ? $translations[$l_DateDescend] : ((empty(trim(__($l_DateDescend,$domain)))) ? __($l_DateDescend,$domainDefault) : __($l_DateDescend,$domain)); if(empty($translations[$l_DateDescend])){$translations[$l_DateDescend]='';}

__('Date ascend');$l_DateAscend = "Date ascend"; $language_DateAscend = (!empty($translations[$l_DateAscend]) && $is_frontend) ? $translations[$l_DateAscend] : ((empty(trim(__($l_DateAscend,$domain)))) ? __($l_DateAscend,$domainDefault) : __($l_DateAscend,$domain)); if(empty($translations[$l_DateAscend])){$translations[$l_DateAscend]='';}

__('Comments descend');$l_CommentsDescend = "Comments descend"; $language_CommentsDescend = (!empty($translations[$l_CommentsDescend]) && $is_frontend) ? $translations[$l_CommentsDescend] : ((empty(trim(__($l_CommentsDescend,$domain)))) ? __($l_CommentsDescend,$domainDefault) : __($l_CommentsDescend,$domain)); if(empty($translations[$l_CommentsDescend])){$translations[$l_CommentsDescend]='';}

__('Comments ascend');$l_CommentsAscend = "Comments ascend"; $language_CommentsAscend = (!empty($translations[$l_CommentsAscend]) && $is_frontend) ? $translations[$l_CommentsAscend] : ((empty(trim(__($l_CommentsAscend,$domain)))) ? __($l_CommentsAscend,$domainDefault) : __($l_CommentsAscend,$domain)); if(empty($translations[$l_CommentsAscend])){$translations[$l_CommentsAscend]='';}

__('Rating descend');$l_RatingDescend = "Rating descend"; $language_RatingDescend = (!empty($translations[$l_RatingDescend]) && $is_frontend) ? $translations[$l_RatingDescend] : ((empty(trim(__($l_RatingDescend,$domain)))) ? __($l_RatingDescend,$domainDefault) : __($l_RatingDescend,$domain)); if(empty($translations[$l_RatingDescend])){$translations[$l_RatingDescend]='';}

__('Rating ascend');$l_RatingAscend = "Rating ascend"; $language_RatingAscend = (!empty($translations[$l_RatingAscend]) && $is_frontend) ? $translations[$l_RatingAscend] : ((empty(trim(__($l_RatingAscend,$domain)))) ? __($l_RatingAscend,$domainDefault) : __($l_RatingAscend,$domain)); if(empty($translations[$l_RatingAscend])){$translations[$l_RatingAscend]='';}

__('Rating quantity descend');$l_RatingQuantityDescend = "Rating quantity descend"; $language_RatingQuantityDescend = (!empty($translations[$l_RatingQuantityDescend]) && $is_frontend) ? $translations[$l_RatingQuantityDescend] : ((empty(trim(__($l_RatingQuantityDescend,$domain)))) ? __($l_RatingQuantityDescend,$domainDefault) : __($l_RatingQuantityDescend,$domain)); if(empty($translations[$l_RatingQuantityDescend])){$translations[$l_RatingQuantityDescend]='';}

__('Rating quantity ascend');$l_RatingQuantityAscend = "Rating quantity ascend"; $language_RatingQuantityAscend = (!empty($translations[$l_RatingQuantityAscend]) && $is_frontend) ? $translations[$l_RatingQuantityAscend] : ((empty(trim(__($l_RatingQuantityAscend,$domain)))) ? __($l_RatingQuantityAscend,$domainDefault) : __($l_RatingQuantityAscend,$domain)); if(empty($translations[$l_RatingQuantityAscend])){$translations[$l_RatingQuantityAscend]='';}

__('Rating average descend');$l_RatingAverageDescend = "Rating average descend"; $language_RatingAverageDescend = (!empty($translations[$l_RatingAverageDescend]) && $is_frontend) ? $translations[$l_RatingAverageDescend] : ((empty(trim(__($l_RatingAverageDescend,$domain)))) ? __($l_RatingAverageDescend,$domainDefault) : __($l_RatingAverageDescend,$domain)); if(empty($translations[$l_RatingAverageDescend])){$translations[$l_RatingAverageDescend]='';}

__('Rating average ascend');$l_RatingAverageAscend = "Rating average ascend"; $language_RatingAverageAscend = (!empty($translations[$l_RatingAverageAscend]) && $is_frontend) ? $translations[$l_RatingAverageAscend] : ((empty(trim(__($l_RatingAverageAscend,$domain)))) ? __($l_RatingAverageAscend,$domainDefault) : __($l_RatingAverageAscend,$domain)); if(empty($translations[$l_RatingAverageAscend])){$translations[$l_RatingAverageAscend]='';}

__('Rating sum descend');$l_RatingSumDescend = "Rating sum descend"; $language_RatingSumDescend = (!empty($translations[$l_RatingSumDescend]) && $is_frontend) ? $translations[$l_RatingSumDescend] : ((empty(trim(__($l_RatingSumDescend,$domain)))) ? __($l_RatingSumDescend,$domainDefault) : __($l_RatingSumDescend,$domain)); if(empty($translations[$l_RatingSumDescend])){$translations[$l_RatingSumDescend]='';}

__('Rating sum ascend');$l_RatingSumAscend = "Rating sum ascend"; $language_RatingSumAscend = (!empty($translations[$l_RatingSumAscend]) && $is_frontend) ? $translations[$l_RatingSumAscend] : ((empty(trim(__($l_RatingSumAscend,$domain)))) ? __($l_RatingSumAscend,$domainDefault) : __($l_RatingSumAscend,$domain)); if(empty($translations[$l_RatingSumAscend])){$translations[$l_RatingSumAscend]='';}

__('Full size');$l_FullSize = "Full size"; $language_FullSize = (!empty($translations[$l_FullSize]) && $is_frontend) ? $translations[$l_FullSize] : ((empty(trim(__($l_FullSize,$domain)))) ? __($l_FullSize,$domainDefault) : __($l_FullSize,$domain)); if(empty($translations[$l_FullSize])){$translations[$l_FullSize]='';}

__('Picture comments');$l_PictureComments = "Picture comments"; $language_PictureComments = (!empty($translations[$l_PictureComments]) && $is_frontend) ? $translations[$l_PictureComments] : ((empty(trim(__($l_PictureComments,$domain)))) ? __($l_PictureComments,$domainDefault) : __($l_PictureComments,$domain)); if(empty($translations[$l_PictureComments])){$translations[$l_PictureComments]='';}

__('Comments');$l_PictureComments = "Comments"; $language_PictureComments = (!empty($translations[$l_PictureComments]) && $is_frontend) ? $translations[$l_PictureComments] : ((empty(trim(__($l_PictureComments,$domain)))) ? __($l_PictureComments,$domainDefault) : __($l_PictureComments,$domain)); if(empty($translations[$l_PictureComments])){$translations[$l_PictureComments]='';}

__('Your comment');$l_YourComment = "Your comment"; $language_YourComment = (!empty($translations[$l_YourComment]) && $is_frontend) ? $translations[$l_YourComment] : ((empty(trim(__($l_YourComment,$domain)))) ? __($l_YourComment,$domainDefault) : __($l_YourComment,$domain)); if(empty($translations[$l_YourComment])){$translations[$l_YourComment]='';}

__('Name');$l_Name = "Name"; $language_Name = (!empty($translations[$l_Name]) && $is_frontend) ? $translations[$l_Name] : ((empty(trim(__($l_Name,$domain)))) ? __($l_Name,$domainDefault) : __($l_Name,$domain)); if(empty($translations[$l_Name])){$translations[$l_Name]='';}

__('Comment');$l_Comment = "Comment"; $language_Comment = (!empty($translations[$l_Comment]) && $is_frontend) ? $translations[$l_Comment] : ((empty(trim(__($l_Comment,$domain)))) ? __($l_Comment,$domainDefault) : __($l_Comment,$domain)); if(empty($translations[$l_Comment])){$translations[$l_Comment]='';}

__('I am not a robot');$l_IamNotArobot = "I am not a robot"; $language_IamNotArobot = (!empty($translations[$l_IamNotArobot]) && $is_frontend) ? $translations[$l_IamNotArobot] : ((empty(trim(__($l_IamNotArobot,$domain)))) ? __($l_IamNotArobot,$domainDefault) : __($l_IamNotArobot,$domain)); if(empty($translations[$l_IamNotArobot])){$translations[$l_IamNotArobot]='';}

__('You can not vote in own gallery');$l_YouCanNotVoteInOwnGallery = "You can not vote in own gallery"; $language_YouCanNotVoteInOwnGallery = (!empty($translations[$l_YouCanNotVoteInOwnGallery]) && $is_frontend) ? $translations[$l_YouCanNotVoteInOwnGallery] : ((empty(trim(__($l_YouCanNotVoteInOwnGallery,$domain)))) ? __($l_YouCanNotVoteInOwnGallery,$domainDefault) : __($l_YouCanNotVoteInOwnGallery,$domain)); if(empty($translations[$l_YouCanNotVoteInOwnGallery])){$translations[$l_YouCanNotVoteInOwnGallery]='';}

__('You can not comment in own gallery');$l_YouCanNotCommentInOwnGallery = "You can not comment in own gallery"; $language_YouCanNotCommentInOwnGallery = (!empty($translations[$l_YouCanNotCommentInOwnGallery]) && $is_frontend) ? $translations[$l_YouCanNotCommentInOwnGallery] : ((empty(trim(__($l_YouCanNotCommentInOwnGallery,$domain)))) ? __($l_YouCanNotCommentInOwnGallery,$domainDefault) : __($l_YouCanNotCommentInOwnGallery,$domain)); if(empty($translations[$l_YouCanNotCommentInOwnGallery])){$translations[$l_YouCanNotCommentInOwnGallery]='';}

__('You have to be logged in to comment');$l_YouHaveToBeLoggedInToComment = "You have to be logged in to comment"; $language_YouHaveToBeLoggedInToComment = (!empty($translations[$l_YouHaveToBeLoggedInToComment]) && $is_frontend) ? $translations[$l_YouHaveToBeLoggedInToComment] : ((empty(trim(__($l_YouHaveToBeLoggedInToComment,$domain)))) ? __($l_YouHaveToBeLoggedInToComment,$domainDefault) : __($l_YouHaveToBeLoggedInToComment,$domain)); if(empty($translations[$l_YouHaveToBeLoggedInToComment])){$translations[$l_YouHaveToBeLoggedInToComment]='';}

__('Please use available emojis');$l_PleaseUseAvailableEmojis = "Please use available emojis"; $language_PleaseUseAvailableEmojis = (!empty($translations[$l_PleaseUseAvailableEmojis]) && $is_frontend) ? $translations[$l_PleaseUseAvailableEmojis] : ((empty(trim(__($l_PleaseUseAvailableEmojis,$domain)))) ? __($l_PleaseUseAvailableEmojis,$domainDefault) : __($l_PleaseUseAvailableEmojis,$domain)); if(empty($translations[$l_PleaseUseAvailableEmojis])){$translations[$l_PleaseUseAvailableEmojis]='';}

__('Emojis are not allowed');$l_EmojisAreNotAllowed = "Emojis are not allowed"; $language_EmojisAreNotAllowed = (!empty($translations[$l_EmojisAreNotAllowed]) && $is_frontend) ? $translations[$l_EmojisAreNotAllowed] : ((empty(trim(__($l_EmojisAreNotAllowed,$domain)))) ? __($l_EmojisAreNotAllowed,$domainDefault) : __($l_EmojisAreNotAllowed,$domain)); if(empty($translations[$l_EmojisAreNotAllowed])){$translations[$l_EmojisAreNotAllowed]='';}

__('Send');$l_Send = "Send"; $language_Send = (!empty($translations[$l_Send]) && $is_frontend) ? $translations[$l_Send] : ((empty(trim(__($l_Send,$domain)))) ? __($l_Send,$domainDefault) : __($l_Send,$domain));
if(empty($translations[$l_Send])){$translations[$l_Send]='';}

__('File was deleted');$l_ImageDeleted = "File was deleted"; $language_ImageDeleted = (!empty($translations[$l_ImageDeleted]) && $is_frontend) ? $translations[$l_ImageDeleted] : ((empty(trim(__($l_ImageDeleted,$domain)))) ? __($l_ImageDeleted,$domainDefault) : __($l_ImageDeleted,$domain));
if(empty($translations[$l_ImageDeleted])){$translations[$l_ImageDeleted]='';}

__('Files were deleted');$l_ImagesDeleted = "Files were deleted"; $language_ImagesDeleted = (!empty($translations[$l_ImagesDeleted]) && $is_frontend) ? $translations[$l_ImagesDeleted] : ((empty(trim(__($l_ImagesDeleted,$domain)))) ? __($l_ImagesDeleted,$domainDefault) : __($l_ImagesDeleted,$domain));
if(empty($translations[$l_ImagesDeleted])){$translations[$l_ImagesDeleted]='';}

__('Successfully logged in via Google. Page will be reloaded...');$l_GoogleSignSuccessfull = "Successfully logged in via Google. Page will be reloaded..."; $language_GoogleSignSuccessfull = (!empty($translations[$l_GoogleSignSuccessfull]) && $is_frontend) ? $translations[$l_GoogleSignSuccessfull] : ((empty(trim(__($l_GoogleSignSuccessfull,$domain)))) ? __($l_GoogleSignSuccessfull,$domainDefault) : __($l_GoogleSignSuccessfull,$domain));
if(empty($translations[$l_GoogleSignSuccessfull])){$translations[$l_GoogleSignSuccessfull]='';}

// Gallery User

__('Delete entry?');$l_DeleteImageQuestion = "Delete entry?"; $language_DeleteImageQuestion = (!empty($translations[$l_DeleteImageQuestion]) && $is_frontend) ? $translations[$l_DeleteImageQuestion] : ((empty(trim(__($l_DeleteImageQuestion,$domain)))) ? __($l_DeleteImageQuestion,$domainDefault) : __($l_DeleteImageQuestion,$domain)); if(empty($translations[$l_DeleteImageQuestion])){$translations[$l_DeleteImageQuestion]='';}

__('Delete entries?');$l_DeleteImagesQuestion = "Delete entries?"; $language_DeleteImagesQuestion = (!empty($translations[$l_DeleteImagesQuestion]) && $is_frontend) ? $translations[$l_DeleteImagesQuestion] : ((empty(trim(__($l_DeleteImagesQuestion,$domain)))) ? __($l_DeleteImagesQuestion,$domainDefault) : __($l_DeleteImagesQuestion,$domain)); if(empty($translations[$l_DeleteImagesQuestion])){$translations[$l_DeleteImagesQuestion]='';}

__('Entry successfully deleted');$l_DeleteImageConfirm = "Entry successfully deleted"; $language_DeleteImageConfirm = (!empty($translations[$l_DeleteImageConfirm]) && $is_frontend) ? $translations[$l_DeleteImageConfirm] : ((empty(trim(__($l_DeleteImageConfirm,$domain)))) ? __($l_DeleteImageConfirm,$domainDefault) : __($l_DeleteImageConfirm,$domain)); if(empty($translations[$l_DeleteImageConfirm])){$translations[$l_DeleteImageConfirm]='';}

__('Entries successfully deleted');$l_DeleteImagesConfirm = "Entries successfully deleted"; $language_DeleteImagesConfirm = (!empty($translations[$l_DeleteImagesConfirm]) && $is_frontend) ? $translations[$l_DeleteImagesConfirm] : ((empty(trim(__($l_DeleteImagesConfirm,$domain)))) ? __($l_DeleteImagesConfirm,$domainDefault) : __($l_DeleteImagesConfirm,$domain)); if(empty($translations[$l_DeleteImagesConfirm])){$translations[$l_DeleteImagesConfirm]='';}

__('Edit');$l_Edit = "Edit"; $language_Edit = (!empty($translations[$l_Edit]) && $is_frontend) ? $translations[$l_Edit] : ((empty(trim(__($l_Edit,$domain)))) ? __($l_Edit,$domainDefault) : __($l_Edit,$domain)); if(empty($translations[$l_Edit])){$translations[$l_Edit]='';}
__('Save');$l_Save = "Save"; $language_Save = (!empty($translations[$l_Save]) && $is_frontend) ? $translations[$l_Save] : ((empty(trim(__($l_Save,$domain)))) ? __($l_Save,$domainDefault) : __($l_Save,$domain)); if(empty($translations[$l_Save])){$translations[$l_Save]='';}

__('Data saved');$l_DataSaved = "Data saved"; $language_DataSaved = (!empty($translations[$l_DataSaved]) && $is_frontend) ? $translations[$l_DataSaved] : ((empty(trim(__($l_DataSaved,$domain)))) ? __($l_DataSaved,$domainDefault) : __($l_DataSaved,$domain)); if(empty($translations[$l_DataSaved])){$translations[$l_DataSaved]='';}

__('Original file source link copied');$l_OriginalFileSourceLinkCopied = "Original file source link copied"; $language_OriginalFileSourceLinkCopied = (!empty($translations[$l_OriginalFileSourceLinkCopied]) && $is_frontend) ? $translations[$l_OriginalFileSourceLinkCopied] : ((empty(trim(__($l_OriginalFileSourceLinkCopied,$domain)))) ? __($l_OriginalFileSourceLinkCopied,$domainDefault) : __($l_OriginalFileSourceLinkCopied,$domain)); if(empty($translations[$l_OriginalFileSourceLinkCopied])){$translations[$l_OriginalFileSourceLinkCopied]='';}

__('Gallery file link copied');$l_GalleryFileLinkCopied = "Gallery file link copied"; $language_GalleryFileLinkCopied = (!empty($translations[$l_GalleryFileLinkCopied]) && $is_frontend) ? $translations[$l_GalleryFileLinkCopied] : ((empty(trim(__($l_GalleryFileLinkCopied,$domain)))) ? __($l_GalleryFileLinkCopied,$domainDefault) : __($l_GalleryFileLinkCopied,$domain)); if(empty($translations[$l_GalleryFileLinkCopied])){$translations[$l_GalleryFileLinkCopied]='';}

__('Gallery entry link copied');$l_GalleryEntryLinkCopied = "Gallery entry link copied"; $language_GalleryEntryLinkCopied = (!empty($translations[$l_GalleryEntryLinkCopied]) && $is_frontend) ? $translations[$l_GalleryEntryLinkCopied] : ((empty(trim(__($l_GalleryEntryLinkCopied,$domain)))) ? __($l_GalleryEntryLinkCopied,$domainDefault) : __($l_GalleryEntryLinkCopied,$domain)); if(empty($translations[$l_GalleryEntryLinkCopied])){$translations[$l_GalleryEntryLinkCopied]='';}

__('Sum');$l_Sum = "Sum"; $language_Sum = (!empty($translations[$l_Sum]) && $is_frontend) ? $translations[$l_Sum] : ((empty(trim(__($l_Sum,$domain)))) ? __($l_Sum,$domainDefault) : __($l_Sum,$domain)); if(empty($translations[$l_Sum])){$translations[$l_Sum]='';}

__('Your vote');$l_YourVote = "Your vote"; $language_YourVote = (!empty($translations[$l_YourVote]) && $is_frontend) ? $translations[$l_YourVote] : ((empty(trim(__($l_YourVote,$domain)))) ? __($l_YourVote,$domainDefault) : __($l_YourVote,$domain)); if(empty($translations[$l_YourVote])){$translations[$l_YourVote]='';}

// Upload/Registry
__('The name field must contain two characters or more');$l_TheNameFieldMustContainTwoCharactersOrMore= "The name field must contain two characters or more";$language_TheNameFieldMustContainTwoCharactersOrMore = (!empty($translations[$l_TheNameFieldMustContainTwoCharactersOrMore]) && $is_frontend) ? $translations[$l_TheNameFieldMustContainTwoCharactersOrMore] : ((empty(trim(__($l_TheNameFieldMustContainTwoCharactersOrMore,$domain)))) ? __($l_TheNameFieldMustContainTwoCharactersOrMore,$domainDefault) : __($l_TheNameFieldMustContainTwoCharactersOrMore,$domain)); if(empty($translations[$l_SortBy])){$translations[$l_TheNameFieldMustContainTwoCharactersOrMore]='';}

__('The comment field must contain three characters or more');$l_TheCommentFieldMustContainThreeCharactersOrMore= "The comment field must contain three characters or more";$language_TheCommentFieldMustContainThreeCharactersOrMore = (!empty($translations[$l_TheCommentFieldMustContainThreeCharactersOrMore]) && $is_frontend) ? $translations[$l_TheCommentFieldMustContainThreeCharactersOrMore] : ((empty(trim(__($l_TheCommentFieldMustContainThreeCharactersOrMore,$domain)))) ? __($l_TheCommentFieldMustContainThreeCharactersOrMore,$domainDefault) : __($l_TheCommentFieldMustContainThreeCharactersOrMore,$domain)); if(empty($translations[$l_TheCommentFieldMustContainThreeCharactersOrMore])){$translations[$l_TheCommentFieldMustContainThreeCharactersOrMore]='';}

__('Plz check the checkbox to prove that you are not a robot');$l_PlzCheckTheCheckboxToProveThatYouAreNotArobot= "Plz check the checkbox to prove that you are not a robot";$language_PlzCheckTheCheckboxToProveThatYouAreNotArobot = (!empty($translations[$l_PlzCheckTheCheckboxToProveThatYouAreNotArobot]) && $is_frontend) ? $translations[$l_SortBy] : ((empty(trim(__($l_PlzCheckTheCheckboxToProveThatYouAreNotArobot,$domain)))) ? __($l_PlzCheckTheCheckboxToProveThatYouAreNotArobot,$domainDefault) : __($l_PlzCheckTheCheckboxToProveThatYouAreNotArobot,$domain)); if(empty($translations[$l_PlzCheckTheCheckboxToProveThatYouAreNotArobot])){$translations[$l_PlzCheckTheCheckboxToProveThatYouAreNotArobot]='';}

__('Your comment will be reviewed');$l_YourCommentWillBeReviewed = "Your comment will be reviewed";$language_YourCommentWillBeReviewed = (!empty($translations[$l_YourCommentWillBeReviewed]) && $is_frontend) ? $translations[$l_YourCommentWillBeReviewed] : ((empty(trim(__($l_YourCommentWillBeReviewed,$domain)))) ? __($l_YourCommentWillBeReviewed,$domainDefault) : __($l_YourCommentWillBeReviewed,$domain)); if(empty($translations[$l_YourCommentWillBeReviewed])){$translations[$l_YourCommentWillBeReviewed]='';}

__('Thank you for your comment'); $l_ThankYouForYourComment= "Thank you for your comment"; $language_ThankYouForYourComment = (!empty($translations[$l_ThankYouForYourComment]) && $is_frontend) ? $translations[$l_ThankYouForYourComment] : ((empty(trim(__($l_ThankYouForYourComment,$domain)))) ? __($l_ThankYouForYourComment,$domainDefault) : __($l_ThankYouForYourComment,$domain)); if(empty($translations[$l_ThankYouForYourComment])){$translations[$l_ThankYouForYourComment]='';}

__('Thank you for your comment'); $l_ThankYouForYourComment= "Thank you for your comment"; $language_ThankYouForYourComment = (!empty($translations[$l_ThankYouForYourComment]) && $is_frontend) ? $translations[$l_ThankYouForYourComment] : ((empty(trim(__($l_ThankYouForYourComment,$domain)))) ? __($l_ThankYouForYourComment,$domainDefault) : __($l_ThankYouForYourComment,$domain)); if(empty($translations[$l_ThankYouForYourComment])){$translations[$l_ThankYouForYourComment]='';}

__('You have already voted for this entry');$l_YouHaveAlreadyVotedThisPicture= "You have already voted for this entry";$language_YouHaveAlreadyVotedThisPicture = (!empty($translations[$l_YouHaveAlreadyVotedThisPicture]) && $is_frontend) ? $translations[$l_YouHaveAlreadyVotedThisPicture] : ((empty(trim(__($l_YouHaveAlreadyVotedThisPicture,$domain)))) ? __($l_YouHaveAlreadyVotedThisPicture,$domainDefault) : __($l_YouHaveAlreadyVotedThisPicture,$domain)); if(empty($translations[$l_YouHaveAlreadyVotedThisPicture])){$translations[$l_YouHaveAlreadyVotedThisPicture]='';}

__('This entry is not a winner');$l_ThisEntryIsNotAWinner="This entry is not a winner";$language_ThisEntryIsNotAWinner = (!empty($translations[$l_ThisEntryIsNotAWinner]) && $is_frontend) ? $translations[$l_ThisEntryIsNotAWinner] : ((empty(trim(__($l_ThisEntryIsNotAWinner,$domain)))) ? __($l_ThisEntryIsNotAWinner,$domainDefault) : __($l_ThisEntryIsNotAWinner,$domain)); if(empty($translations[$l_ThisEntryIsNotAWinner])){$translations[$l_ThisEntryIsNotAWinner]='';}

__('You have already voted for this category');$l_YouHaveAlreadyVotedThisCategory= "You have already voted for this category";$language_YouHaveAlreadyVotedThisCategory = (!empty($translations[$l_YouHaveAlreadyVotedThisCategory]) && $is_frontend) ? $translations[$l_YouHaveAlreadyVotedThisCategory] : ((empty(trim(__($l_YouHaveAlreadyVotedThisCategory,$domain)))) ? __($l_YouHaveAlreadyVotedThisCategory,$domainDefault) : __($l_YouHaveAlreadyVotedThisCategory,$domain)); if(empty($translations[$l_YouHaveAlreadyVotedThisCategory])){$translations[$l_YouHaveAlreadyVotedThisCategory]='';}

__('You have no more votes in this category');$l_YouHaveNoMoreVotesInThisCategory = "You have no more votes in this category";$language_YouHaveNoMoreVotesInThisCategory = (!empty($translations[$l_YouHaveNoMoreVotesInThisCategory]) && $is_frontend) ? $translations[$l_YouHaveNoMoreVotesInThisCategory] : ((empty(trim(__($l_YouHaveNoMoreVotesInThisCategory,$domain)))) ? __($l_YouHaveNoMoreVotesInThisCategory,$domainDefault) : __($l_YouHaveNoMoreVotesInThisCategory,$domain)); if(empty($translations[$l_YouHaveNoMoreVotesInThisCategory])){$translations[$l_YouHaveNoMoreVotesInThisCategory]='';}

__('You have already used all your votes');$l_AllVotesUsed= "You have already used all your votes";$language_AllVotesUsed = (!empty($translations[$l_AllVotesUsed]) && $is_frontend) ? $translations[$l_AllVotesUsed] : ((empty(trim(__($l_AllVotesUsed,$domain)))) ? __($l_AllVotesUsed,$domainDefault) : __($l_AllVotesUsed,$domain)); if(empty($translations[$l_AllVotesUsed])){$translations[$l_AllVotesUsed]='';}
/*
__('It is not allowed to vote for your own picture');$l_ItIsNotAllowedToVoteForYourOwnPicture = "It is not allowed to vote for your own picture";$language_ItIsNotAllowedToVoteForYourOwnPicture = (!empty($translations[$l_ItIsNotAllowedToVoteForYourOwnPicture]) && $is_frontend) ? $translations[$l_ItIsNotAllowedToVoteForYourOwnPicture] : ((empty(trim(__($l_ItIsNotAllowedToVoteForYourOwnPicture,$domain)))) ? __($l_ItIsNotAllowedToVoteForYourOwnPicture,$domainDefault) : __($l_ItIsNotAllowedToVoteForYourOwnPicture,$domain)); if(empty($translations[$l_ItIsNotAllowedToVoteForYourOwnPicture])){$translations[$l_ItIsNotAllowedToVoteForYourOwnPicture]='';}*/

__('It is not allowed to vote for your own file');$l_ItIsNotAllowedToVoteForYourOwnPicture = "It is not allowed to vote for your own file";$language_ItIsNotAllowedToVoteForYourOwnPicture = (!empty($translations[$l_ItIsNotAllowedToVoteForYourOwnPicture]) && $is_frontend) ? $translations[$l_ItIsNotAllowedToVoteForYourOwnPicture] : ((empty(trim(__($l_ItIsNotAllowedToVoteForYourOwnPicture,$domain)))) ? __($l_ItIsNotAllowedToVoteForYourOwnPicture,$domainDefault) : __($l_ItIsNotAllowedToVoteForYourOwnPicture,$domain)); if(empty($translations[$l_ItIsNotAllowedToVoteForYourOwnPicture])){$translations[$l_ItIsNotAllowedToVoteForYourOwnPicture]='';}

__('Only registered users are allowed to vote');$l_OnlyRegisteredUsersCanVote= "Only registered users are allowed to vote";$language_OnlyRegisteredUsersCanVote = (!empty($translations[$l_OnlyRegisteredUsersCanVote]) && $is_frontend) ? $translations[$l_OnlyRegisteredUsersCanVote] : ((empty(trim(__($l_OnlyRegisteredUsersCanVote,$domain)))) ? __($l_OnlyRegisteredUsersCanVote,$domainDefault) : __($l_OnlyRegisteredUsersCanVote,$domain)); if(empty($translations[$l_OnlyRegisteredUsersCanVote])){$translations[$l_OnlyRegisteredUsersCanVote]='';}

__('This file type is not allowed');$l_ThisFileTypeIsNotAllowed= "This file type is not allowed";$language_ThisFileTypeIsNotAllowed = (!empty($translations[$l_ThisFileTypeIsNotAllowed]) && $is_frontend) ? $translations[$l_ThisFileTypeIsNotAllowed] : ((empty(trim(__($l_ThisFileTypeIsNotAllowed,$domain)))) ? __($l_ThisFileTypeIsNotAllowed,$domainDefault) : __($l_ThisFileTypeIsNotAllowed,$domain)); if(empty($translations[$l_ThisFileTypeIsNotAllowed])){$translations[$l_ThisFileTypeIsNotAllowed]='';}

__('The selected file is too large, max allowed size');$l_TheFileYouChoosedIsToBigMaxAllowedSize= "The selected file is too large, max allowed size";$language_TheFileYouChoosedIsToBigMaxAllowedSize = (!empty($translations[$l_TheFileYouChoosedIsToBigMaxAllowedSize]) && $is_frontend) ? $translations[$l_TheFileYouChoosedIsToBigMaxAllowedSize] : ((empty(trim(__($l_TheFileYouChoosedIsToBigMaxAllowedSize,$domain)))) ? __($l_TheFileYouChoosedIsToBigMaxAllowedSize,$domainDefault) : __($l_TheFileYouChoosedIsToBigMaxAllowedSize,$domain)); if(empty($translations[$l_TheFileYouChoosedIsToBigMaxAllowedSize])){$translations[$l_TheFileYouChoosedIsToBigMaxAllowedSize]='';}

__('The resolution of this image is');$l_TheResolutionOfThisPicIs= "The resolution of this image is";$language_TheResolutionOfThisPicIs = (!empty($translations[$l_TheResolutionOfThisPicIs]) && $is_frontend) ? $translations[$l_TheResolutionOfThisPicIs] : ((empty(trim(__($l_TheResolutionOfThisPicIs,$domain)))) ? __($l_TheResolutionOfThisPicIs,$domainDefault) : __($l_TheResolutionOfThisPicIs,$domain)); if(empty($translations[$l_TheResolutionOfThisPicIs])){$translations[$l_TheResolutionOfThisPicIs]='';}

__('Maximum number of files for one upload is');$l_BulkUploadQuantityIs= "Maximum number of files for one upload is";$language_BulkUploadQuantityIs = (!empty($translations[$l_BulkUploadQuantityIs]) && $is_frontend) ? $translations[$l_BulkUploadQuantityIs] : ((empty(trim(__($l_BulkUploadQuantityIs,$domain)))) ? __($l_BulkUploadQuantityIs,$domainDefault) : __($l_BulkUploadQuantityIs,$domain)); if(empty($translations[$l_BulkUploadQuantityIs])){$translations[$l_BulkUploadQuantityIs]='';}
__('Minimum number of files for one upload is');$l_BulkUploadLowQuantityIs= "Minimum number of files for one upload is";$language_BulkUploadLowQuantityIs = (!empty($translations[$l_BulkUploadLowQuantityIs]) && $is_frontend) ? $translations[$l_BulkUploadLowQuantityIs] : ((empty(trim(__($l_BulkUploadLowQuantityIs,$domain)))) ? __($l_BulkUploadLowQuantityIs,$domainDefault) : __($l_BulkUploadLowQuantityIs,$domain)); if(empty($translations[$l_BulkUploadLowQuantityIs])){$translations[$l_BulkUploadLowQuantityIs]='';}

__('Maximum allowed resolution for JPG is');$l_MaximumAllowedResolutionForJPGsIs= "Maximum allowed resolution for JPG is";$language_MaximumAllowedResolutionForJPGsIs = (!empty($translations[$l_MaximumAllowedResolutionForJPGsIs]) && $is_frontend) ? $translations[$l_MaximumAllowedResolutionForJPGsIs] : ((empty(trim(__($l_MaximumAllowedResolutionForJPGsIs,$domain)))) ? __($l_MaximumAllowedResolutionForJPGsIs,$domainDefault) : __($l_MaximumAllowedResolutionForJPGsIs,$domain)); if(empty($translations[$l_MaximumAllowedResolutionForJPGsIs])){$translations[$l_MaximumAllowedResolutionForJPGsIs]='';}

__('Maximum allowed width for JPG is');$l_MaximumAllowedWidthForJPGsIs= "Maximum allowed width for JPG is";$language_MaximumAllowedWidthForJPGsIs = (!empty($translations[$l_MaximumAllowedWidthForJPGsIs]) && $is_frontend) ? $translations[$l_MaximumAllowedWidthForJPGsIs] : ((empty(trim(__($l_MaximumAllowedWidthForJPGsIs,$domain)))) ? __($l_MaximumAllowedWidthForJPGsIs,$domainDefault) : __($l_MaximumAllowedWidthForJPGsIs,$domain)); if(empty($translations[$l_MaximumAllowedWidthForJPGsIs])){$translations[$l_MaximumAllowedWidthForJPGsIs]='';}

__('Maximum allowed height for JPG is');$l_MaximumAllowedHeightForJPGsIs= "Maximum allowed height for JPG is";$language_MaximumAllowedHeightForJPGsIs = (!empty($translations[$l_MaximumAllowedHeightForJPGsIs]) && $is_frontend) ? $translations[$l_MaximumAllowedHeightForJPGsIs] : ((empty(trim(__($l_MaximumAllowedHeightForJPGsIs,$domain)))) ? __($l_MaximumAllowedHeightForJPGsIs,$domainDefault) : __($l_MaximumAllowedHeightForJPGsIs,$domain)); if(empty($translations[$l_MaximumAllowedHeightForJPGsIs])){$translations[$l_MaximumAllowedHeightForJPGsIs]='';}

__('Minimum required width for JPG is');$l_MinimumRequiredWidthForJPGsIs= "Minimum required width for JPG is";$language_MinimumRequiredWidthForJPGsIs = (!empty($translations[$l_MinimumRequiredWidthForJPGsIs]) && $is_frontend) ? $translations[$l_MinimumRequiredWidthForJPGsIs] : ((empty(trim(__($l_MinimumRequiredWidthForJPGsIs,$domain)))) ? __($l_MinimumRequiredWidthForJPGsIs,$domainDefault) : __($l_MinimumRequiredWidthForJPGsIs,$domain)); if(empty($translations[$l_MinimumRequiredWidthForJPGsIs])){$translations[$l_MinimumRequiredWidthForJPGsIs]='';}

__('Minimum required height for JPG is');$l_MinimumRequiredHeightForJPGsIs= "Minimum required height for JPG is";$language_MinimumRequiredHeightForJPGsIs = (!empty($translations[$l_MinimumRequiredHeightForJPGsIs]) && $is_frontend) ? $translations[$l_MinimumRequiredHeightForJPGsIs] : ((empty(trim(__($l_MinimumRequiredHeightForJPGsIs,$domain)))) ? __($l_MinimumRequiredHeightForJPGsIs,$domainDefault) : __($l_MinimumRequiredHeightForJPGsIs,$domain)); if(empty($translations[$l_MinimumRequiredHeightForJPGsIs])){$translations[$l_MinimumRequiredHeightForJPGsIs]='';}

__('Maximum allowed resolution for PNG is');$l_MaximumAllowedResolutionForPNGsIs= "Maximum allowed resolution for PNG is";$language_MaximumAllowedResolutionForPNGsIs = (!empty($translations[$l_MaximumAllowedResolutionForPNGsIs]) && $is_frontend) ? $translations[$l_MaximumAllowedResolutionForPNGsIs] : ((empty(trim(__($l_MaximumAllowedResolutionForPNGsIs,$domain)))) ? __($l_MaximumAllowedResolutionForPNGsIs,$domainDefault) : __($l_MaximumAllowedResolutionForPNGsIs,$domain)); if(empty($translations[$l_MaximumAllowedResolutionForPNGsIs])){$translations[$l_MaximumAllowedResolutionForPNGsIs]='';}

__('Maximum allowed width for PNG is');$l_MaximumAllowedWidthForPNGsIs= "Maximum allowed width for PNG is";$language_MaximumAllowedWidthForPNGsIs = (!empty($translations[$l_MaximumAllowedWidthForPNGsIs]) && $is_frontend) ? $translations[$l_MaximumAllowedWidthForPNGsIs] : ((empty(trim(__($l_MaximumAllowedWidthForPNGsIs,$domain)))) ? __($l_MaximumAllowedWidthForPNGsIs,$domainDefault) : __($l_MaximumAllowedWidthForPNGsIs,$domain)); if(empty($translations[$l_MaximumAllowedWidthForPNGsIs])){$translations[$l_MaximumAllowedWidthForPNGsIs]='';}

__('Maximum allowed height for PNG is');$l_MaximumAllowedHeightForPNGsIs= "Maximum allowed height for PNG is";$language_MaximumAllowedHeightForPNGsIs = (!empty($translations[$l_MaximumAllowedHeightForPNGsIs]) && $is_frontend) ? $translations[$l_MaximumAllowedHeightForPNGsIs] : ((empty(trim(__($l_MaximumAllowedHeightForPNGsIs,$domain)))) ? __($l_MaximumAllowedHeightForPNGsIs,$domainDefault) : __($l_MaximumAllowedHeightForPNGsIs,$domain)); if(empty($translations[$l_MaximumAllowedHeightForPNGsIs])){$translations[$l_MaximumAllowedHeightForPNGsIs]='';}

__('Minimum required width for PNG is');$l_MinimumRequiredWidthForPNGsIs= "Minimum required width for PNG is";$language_MinimumRequiredWidthForPNGsIs = (!empty($translations[$l_MinimumRequiredWidthForPNGsIs]) && $is_frontend) ? $translations[$l_MinimumRequiredWidthForPNGsIs] : ((empty(trim(__($l_MinimumRequiredWidthForPNGsIs,$domain)))) ? __($l_MinimumRequiredWidthForPNGsIs,$domainDefault) : __($l_MinimumRequiredWidthForPNGsIs,$domain)); if(empty($translations[$l_MinimumRequiredWidthForPNGsIs])){$translations[$l_MinimumRequiredWidthForPNGsIs]='';}

__('Minimum required height for PNG is');$l_MinimumRequiredHeightForPNGsIs= "Minimum required height for PNG is";$language_MinimumRequiredHeightForPNGsIs = (!empty($translations[$l_MinimumRequiredHeightForPNGsIs]) && $is_frontend) ? $translations[$l_MinimumRequiredHeightForPNGsIs] : ((empty(trim(__($l_MinimumRequiredHeightForPNGsIs,$domain)))) ? __($l_MinimumRequiredHeightForPNGsIs,$domainDefault) : __($l_MinimumRequiredHeightForPNGsIs,$domain)); if(empty($translations[$l_MinimumRequiredHeightForPNGsIs])){$translations[$l_MinimumRequiredHeightForPNGsIs]='';}

__('Maximum allowed resolution for GIF is');$l_MaximumAllowedResolutionForGIFsIs= "Maximum allowed resolution for GIF is";$language_MaximumAllowedResolutionForGIFsIs = (!empty($translations[$l_MaximumAllowedResolutionForGIFsIs]) && $is_frontend) ? $translations[$l_MaximumAllowedResolutionForGIFsIs] : ((empty(trim(__($l_MaximumAllowedResolutionForGIFsIs,$domain)))) ? __($l_MaximumAllowedResolutionForGIFsIs,$domainDefault) : __($l_MaximumAllowedResolutionForGIFsIs,$domain)); if(empty($translations[$l_MaximumAllowedResolutionForGIFsIs])){$translations[$l_MaximumAllowedResolutionForGIFsIs]='';}

__('Maximum allowed width for GIF is');$l_MaximumAllowedWidthForGIFsIs= "Maximum allowed width for GIF is";$language_MaximumAllowedWidthForGIFsIs = (!empty($translations[$l_MaximumAllowedWidthForGIFsIs]) && $is_frontend) ? $translations[$l_MaximumAllowedWidthForGIFsIs] : ((empty(trim(__($l_MaximumAllowedWidthForGIFsIs,$domain)))) ? __($l_MaximumAllowedWidthForGIFsIs,$domainDefault) : __($l_MaximumAllowedWidthForGIFsIs,$domain)); if(empty($translations[$l_MaximumAllowedWidthForGIFsIs])){$translations[$l_MaximumAllowedWidthForGIFsIs]='';}

__('Maximum allowed height for GIF is');$l_MaximumAllowedHeightForGIFsIs= "Maximum allowed height for GIF is";$language_MaximumAllowedHeightForGIFsIs = (!empty($translations[$l_MaximumAllowedHeightForGIFsIs]) && $is_frontend) ? $translations[$l_MaximumAllowedHeightForGIFsIs] : ((empty(trim(__($l_MaximumAllowedHeightForGIFsIs,$domain)))) ? __($l_MaximumAllowedHeightForGIFsIs,$domainDefault) : __($l_MaximumAllowedHeightForGIFsIs,$domain)); if(empty($translations[$l_MaximumAllowedHeightForGIFsIs])){$translations[$l_MaximumAllowedHeightForGIFsIs]='';}

__('Minimum required width for GIF is');$l_MinimumRequiredWidthForGIFsIs= "Minimum required width for GIF is";$language_MinimumRequiredWidthForGIFsIs = (!empty($translations[$l_MinimumRequiredWidthForGIFsIs]) && $is_frontend) ? $translations[$l_MinimumRequiredWidthForGIFsIs] : ((empty(trim(__($l_MinimumRequiredWidthForGIFsIs,$domain)))) ? __($l_MinimumRequiredWidthForGIFsIs,$domainDefault) : __($l_MinimumRequiredWidthForGIFsIs,$domain)); if(empty($translations[$l_MinimumRequiredWidthForGIFsIs])){$translations[$l_MinimumRequiredWidthForGIFsIs]='';}

__('Minimum required height for GIF is');$l_MinimumRequiredHeightForGIFsIs= "Minimum required height for GIF is";$language_MinimumRequiredHeightForGIFsIs = (!empty($translations[$l_MinimumRequiredHeightForGIFsIs]) && $is_frontend) ? $translations[$l_MinimumRequiredHeightForGIFsIs] : ((empty(trim(__($l_MinimumRequiredHeightForGIFsIs,$domain)))) ? __($l_MinimumRequiredHeightForGIFsIs,$domainDefault) : __($l_MinimumRequiredHeightForGIFsIs,$domain)); if(empty($translations[$l_MinimumRequiredHeightForGIFsIs])){$translations[$l_MinimumRequiredHeightForGIFsIs]='';}

__('Maximum allowed width for ICO is');$l_MaximumAllowedWidthForICOsIs= "Maximum allowed width for ICO is";$language_MaximumAllowedWidthForICOsIs = (!empty($translations[$l_MaximumAllowedWidthForICOsIs]) && $is_frontend) ? $translations[$l_MaximumAllowedWidthForICOsIs] : ((empty(trim(__($l_MaximumAllowedWidthForICOsIs,$domain)))) ? __($l_MaximumAllowedWidthForICOsIs,$domainDefault) : __($l_MaximumAllowedWidthForICOsIs,$domain)); if(empty($translations[$l_MaximumAllowedWidthForICOsIs])){$translations[$l_MaximumAllowedWidthForICOsIs]='';}

__('Maximum allowed height for ICO is');$l_MaximumAllowedHeightForICOsIs= "Maximum allowed height for ICO is";$language_MaximumAllowedHeightForICOsIs = (!empty($translations[$l_MaximumAllowedHeightForICOsIs]) && $is_frontend) ? $translations[$l_MaximumAllowedHeightForICOsIs] : ((empty(trim(__($l_MaximumAllowedHeightForICOsIs,$domain)))) ? __($l_MaximumAllowedHeightForICOsIs,$domainDefault) : __($l_MaximumAllowedHeightForICOsIs,$domain)); if(empty($translations[$l_MaximumAllowedHeightForICOsIs])){$translations[$l_MaximumAllowedHeightForICOsIs]='';}

__('You have to check this agreement');$l_YouHaveToCheckThisAgreement= "You have to check this agreement";$language_YouHaveToCheckThisAgreement = (!empty($translations[$l_YouHaveToCheckThisAgreement]) && $is_frontend) ? $translations[$l_YouHaveToCheckThisAgreement] : ((empty(trim(__($l_YouHaveToCheckThisAgreement,$domain)))) ? __($l_YouHaveToCheckThisAgreement,$domainDefault) : __($l_YouHaveToCheckThisAgreement,$domain)); if(empty($translations[$l_YouHaveToCheckThisAgreement])){$translations[$l_YouHaveToCheckThisAgreement]='';}

__('This email is not valid');$l_EmailAddressHasToBeValid= "This email is not valid";$language_EmailAddressHasToBeValid = (!empty($translations[$l_EmailAddressHasToBeValid]) && $is_frontend) ? $translations[$l_EmailAddressHasToBeValid] : ((empty(trim(__($l_EmailAddressHasToBeValid,$domain)))) ? __($l_EmailAddressHasToBeValid,$domainDefault) : __($l_EmailAddressHasToBeValid,$domain)); if(empty($translations[$l_EmailAddressHasToBeValid])){$translations[$l_EmailAddressHasToBeValid]='';}

__('Reset password link is not valid anymore');$l_LostPasswordUrlIsNotValidAnymore = "Reset password link is not valid anymore";$language_LostPasswordUrlIsNotValidAnymore = (!empty($translations[$l_LostPasswordUrlIsNotValidAnymore]) && $is_frontend) ? $translations[$l_LostPasswordUrlIsNotValidAnymore] : ((empty(trim(__($l_LostPasswordUrlIsNotValidAnymore,$domain)))) ? __($l_LostPasswordUrlIsNotValidAnymore,$domainDefault) : __($l_LostPasswordUrlIsNotValidAnymore,$domain)); if(empty($translations[$l_LostPasswordUrlIsNotValidAnymore])){$translations[$l_LostPasswordUrlIsNotValidAnymore]='';}

__('Password successfully changed');$l_ResetPasswordSuccessfully = "Password successfully changed";$language_ResetPasswordSuccessfully = (!empty($translations[$l_ResetPasswordSuccessfully]) && $is_frontend) ? $translations[$l_ResetPasswordSuccessfully] : ((empty(trim(__($l_ResetPasswordSuccessfully,$domain)))) ? __($l_ResetPasswordSuccessfully,$domainDefault) : __($l_ResetPasswordSuccessfully,$domain)); if(empty($translations[$l_ResetPasswordSuccessfully])){$translations[$l_ResetPasswordSuccessfully]='';}

__('Back to login form');$l_BackToLoginForm = "Back to login form";$language_BackToLoginForm = (!empty($translations[$l_BackToLoginForm]) && $is_frontend) ? $translations[$l_BackToLoginForm] : ((empty(trim(__($l_BackToLoginForm,$domain)))) ? __($l_BackToLoginForm,$domainDefault) : __($l_BackToLoginForm,$domain)); if(empty($translations[$l_BackToLoginForm])){$translations[$l_BackToLoginForm]='';}

__('Min amount of characters');$l_MinAmountOfCharacters= "Min amount of characters";$language_MinAmountOfCharacters = (!empty($translations[$l_MinAmountOfCharacters]) && $is_frontend) ? $translations[$l_MinAmountOfCharacters] : ((empty(trim(__($l_MinAmountOfCharacters,$domain)))) ? __($l_MinAmountOfCharacters,$domainDefault) : __($l_MinAmountOfCharacters,$domain)); if(empty($translations[$l_MinAmountOfCharacters])){$translations[$l_MinAmountOfCharacters]='';}

__('Max amount of characters');$l_MaxAmountOfCharacters= "Max amount of characters";$language_MaxAmountOfCharacters = (!empty($translations[$l_MaxAmountOfCharacters]) && $is_frontend) ? $translations[$l_MaxAmountOfCharacters] : ((empty(trim(__($l_MaxAmountOfCharacters,$domain)))) ? __($l_MaxAmountOfCharacters,$domainDefault) : __($l_MaxAmountOfCharacters,$domain)); if(empty($translations[$l_MaxAmountOfCharacters])){$translations[$l_MaxAmountOfCharacters]='';}

__('Select your file');$l_ChooseYourImage= "Select your file";$language_ChooseYourImage = (!empty($translations[$l_ChooseYourImage]) && $is_frontend) ? $translations[$l_ChooseYourImage] : ((empty(trim(__($l_ChooseYourImage,$domain)))) ? __($l_ChooseYourImage,$domainDefault) : __($l_ChooseYourImage,$domain)); if(empty($translations[$l_ChooseYourImage])){$translations[$l_ChooseYourImage]='';}

__('The photo contest has not started yet');$l_ThePhotoContestHasNotStartedYet= "The photo contest has not started yet";$language_ThePhotoContestHasNotStartedYet = (!empty($translations[$l_ThePhotoContestHasNotStartedYet]) && $is_frontend) ? $translations[$l_ThePhotoContestHasNotStartedYet] : ((empty(trim(__($l_ThePhotoContestHasNotStartedYet,$domain)))) ? __($l_ThePhotoContestHasNotStartedYet,$domainDefault) : __($l_ThePhotoContestHasNotStartedYet,$domain)); if(empty($translations[$l_ThePhotoContestHasNotStartedYet])){$translations[$l_ThePhotoContestHasNotStartedYet]='';}

__('The photo contest is over');$l_ThePhotoContestIsOver= "The photo contest is over";$language_ThePhotoContestIsOver = (!empty($translations[$l_ThePhotoContestIsOver]) && $is_frontend) ? $translations[$l_ThePhotoContestIsOver] : ((empty(trim(__($l_ThePhotoContestIsOver,$domain)))) ? __($l_ThePhotoContestIsOver,$domainDefault) : __($l_ThePhotoContestIsOver,$domain)); if(empty($translations[$l_ThePhotoContestIsOver])){$translations[$l_ThePhotoContestIsOver]='';}

__('Hold left mouse to see user info');$l_ShowMeUserInfoOnLeftMouseHold= "Hold left mouse to see user info";$language_ShowMeUserInfoOnLeftMouseHold = (!empty($translations[$l_ShowMeUserInfoOnLeftMouseHold]) && $is_frontend) ? $translations[$l_ShowMeUserInfoOnLeftMouseHold] : ((empty(trim(__($l_ShowMeUserInfoOnLeftMouseHold,$domain)))) ? __($l_ShowMeUserInfoOnLeftMouseHold,$domainDefault) : __($l_ShowMeUserInfoOnLeftMouseHold,$domain)); if(empty($translations[$l_ShowMeUserInfoOnLeftMouseHold])){$translations[$l_ShowMeUserInfoOnLeftMouseHold]='';}

__('Maximum amount of uploads per user is');$l_MaximumAmountOfUploadsIs = "Maximum amount of uploads per user is";$language_MaximumAmountOfUploadsIs = (!empty($translations[$l_MaximumAmountOfUploadsIs]) && $is_frontend) ? $translations[$l_MaximumAmountOfUploadsIs] : ((empty(trim(__($l_MaximumAmountOfUploadsIs,$domain)))) ? __($l_MaximumAmountOfUploadsIs,$domainDefault) : __($l_MaximumAmountOfUploadsIs,$domain)); if(empty($translations[$l_MaximumAmountOfUploadsIs])){$translations[$l_MaximumAmountOfUploadsIs]='';}

__('Maximum amount of uploads per category is');$l_MaximumAmountOfUploadsPerCategoryIs = "Maximum amount of uploads per category is";$language_MaximumAmountOfUploadsPerCategoryIs = (!empty($translations[$l_MaximumAmountOfUploadsPerCategoryIs]) && $is_frontend) ? $translations[$l_MaximumAmountOfUploadsPerCategoryIs] : ((empty(trim(__($l_MaximumAmountOfUploadsPerCategoryIs,$domain)))) ? __($l_MaximumAmountOfUploadsPerCategoryIs,$domainDefault) : __($l_MaximumAmountOfUploadsPerCategoryIs,$domain)); if(empty($translations[$l_MaximumAmountOfUploadsPerCategoryIs])){$translations[$l_MaximumAmountOfUploadsPerCategoryIs]='';}

__('You have already uploaded');$l_YouHaveAlreadyUploaded = "You have already uploaded";$language_YouHaveAlreadyUploaded = (!empty($translations[$l_YouHaveAlreadyUploaded]) && $is_frontend) ? $translations[$l_YouHaveAlreadyUploaded] : ((empty(trim(__($l_YouHaveAlreadyUploaded,$domain)))) ? __($l_YouHaveAlreadyUploaded,$domainDefault) : __($l_YouHaveAlreadyUploaded,$domain)); if(empty($translations[$l_YouHaveAlreadyUploaded])){$translations[$l_YouHaveAlreadyUploaded]='';}

__('You have already uploaded in category');$l_YouHaveAlreadyUploadedForCategory = "You have already uploaded in category";$language_YouHaveAlreadyUploadedForCategory = (!empty($translations[$l_YouHaveAlreadyUploadedForCategory]) && $is_frontend) ? $translations[$l_YouHaveAlreadyUploadedForCategory] : ((empty(trim(__($l_YouHaveAlreadyUploadedForCategory,$domain)))) ? __($l_YouHaveAlreadyUploadedForCategory,$domainDefault) : __($l_YouHaveAlreadyUploadedForCategory,$domain)); if(empty($translations[$l_YouHaveAlreadyUploadedForCategory])){$translations[$l_YouHaveAlreadyUploadedForCategory]='';}

__('Maximum amount of uploads left for you');$l_MaximumAmountOfUploadsLeftForYou = "Maximum amount of uploads left for you";$language_MaximumAmountOfUploadsLeftForYou = (!empty($translations[$l_MaximumAmountOfUploadsLeftForYou]) && $is_frontend) ? $translations[$l_MaximumAmountOfUploadsLeftForYou] : ((empty(trim(__($l_MaximumAmountOfUploadsLeftForYou,$domain)))) ? __($l_MaximumAmountOfUploadsLeftForYou,$domainDefault) : __($l_MaximumAmountOfUploadsLeftForYou,$domain)); if(empty($translations[$l_MaximumAmountOfUploadsLeftForYou])){$translations[$l_MaximumAmountOfUploadsLeftForYou]='';}


// Login

__('Lost password?');$l_LostPassword = "Lost password?";$language_LostPassword = (!empty($translations[$l_LostPassword]) && $is_frontend) ? $translations[$l_LostPassword] : ((empty(trim(__($l_LostPassword,$domain)))) ? __($l_LostPassword,$domainDefault) : __($l_LostPassword,$domain)); if(empty($translations[$l_LostPassword])){$translations[$l_LostPassword]='';}

__('This email address is already registered');$l_ThisMailAlreadyExists= "This email address is already registered";$language_ThisMailAlreadyExists = (!empty($translations[$l_ThisMailAlreadyExists]) && $is_frontend) ? $translations[$l_ThisMailAlreadyExists] : ((empty(trim(__($l_ThisMailAlreadyExists,$domain)))) ? __($l_ThisMailAlreadyExists,$domainDefault) : __($l_ThisMailAlreadyExists,$domain)); if(empty($translations[$l_ThisMailAlreadyExists])){$translations[$l_ThisMailAlreadyExists]='';}

__('This username is already taken');$l_ThisUsernameAlreadyExists= "This username is already taken";$language_ThisUsernameAlreadyExists = (!empty($translations[$l_ThisUsernameAlreadyExists]) && $is_frontend) ? $translations[$l_ThisUsernameAlreadyExists] : ((empty(trim(__($l_ThisUsernameAlreadyExists,$domain)))) ? __($l_ThisUsernameAlreadyExists,$domainDefault) : __($l_ThisUsernameAlreadyExists,$domain)); if(empty($translations[$l_ThisUsernameAlreadyExists])){$translations[$l_ThisUsernameAlreadyExists]='';}

__('Username or email');$l_UsernameOrEmail= "Username or email";$language_UsernameOrEmail = (!empty($translations[$l_UsernameOrEmail]) && $is_frontend) ? $translations[$l_UsernameOrEmail] : ((empty(trim(__($l_UsernameOrEmail,$domain)))) ? __($l_UsernameOrEmail,$domainDefault) : __($l_UsernameOrEmail,$domain)); if(empty($translations[$l_UsernameOrEmail])){$translations[$l_UsernameOrEmail]='';}

__('Username or email required');$l_UsernameOrEmailRequired= "Username or email required";$language_UsernameOrEmailRequired = (!empty($translations[$l_UsernameOrEmailRequired]) && $is_frontend) ? $translations[$l_UsernameOrEmailRequired] : ((empty(trim(__($l_UsernameOrEmailRequired,$domain)))) ? __($l_UsernameOrEmailRequired,$domainDefault) : __($l_UsernameOrEmailRequired,$domain)); if(empty($translations[$l_UsernameOrEmailRequired])){$translations[$l_UsernameOrEmailRequired]='';}

__('Username or email does not exist');$l_UsernameOrEmailDoesNotExist= "Username or email does not exist";$language_UsernameOrEmailDoesNotExist = (!empty($translations[$l_UsernameOrEmailDoesNotExist]) && $is_frontend) ? $translations[$l_UsernameOrEmailDoesNotExist] : ((empty(trim(__($l_UsernameOrEmailDoesNotExist,$domain)))) ? __($l_UsernameOrEmailDoesNotExist,$domainDefault) : __($l_UsernameOrEmailDoesNotExist,$domain)); if(empty($translations[$l_UsernameOrEmailDoesNotExist])){$translations[$l_UsernameOrEmailDoesNotExist]='';}

__('This username already exists');$l_ThisUsernameAlreadyExists= "This username already taken";$language_ThisUsernameAlreadyExists = (!empty($translations[$l_ThisUsernameAlreadyExists]) && $is_frontend) ? $translations[$l_ThisUsernameAlreadyExists] : ((empty(trim(__($l_ThisUsernameAlreadyExists,$domain)))) ? __($l_ThisUsernameAlreadyExists,$domainDefault) : __($l_ThisUsernameAlreadyExists,$domain)); if(empty($translations[$l_ThisUsernameAlreadyExists])){$translations[$l_ThisUsernameAlreadyExists]='';}

__('This nickname is already taken');$l_ThisNicknameAlreadyExists= "This nickname is already taken";$language_ThisNicknameAlreadyExists = (!empty($translations[$l_ThisNicknameAlreadyExists]) && $is_frontend) ? $translations[$l_ThisNicknameAlreadyExists] : ((empty(trim(__($l_ThisNicknameAlreadyExists,$domain)))) ? __($l_ThisNicknameAlreadyExists,$domainDefault) : __($l_ThisNicknameAlreadyExists,$domain)); if(empty($translations[$l_ThisNicknameAlreadyExists])){$translations[$l_ThisNicknameAlreadyExists]='';}

__('Email');$l_Email= "Email";$language_Email = (!empty($translations[$l_Email]) && $is_frontend) ? $translations[$l_Email] : ((empty(trim(__($l_Email,$domain)))) ? __($l_Email,$domainDefault) : __($l_Email,$domain)); if(empty($translations[$l_Email])){$translations[$l_Email]='';}

__('An email was sent to reset password');$l_EmailLostPasswordSent = "An email was sent to reset password";$language_EmailLostPasswordSent = (!empty($translations[$l_EmailLostPasswordSent]) && $is_frontend) ? $translations[$l_EmailLostPasswordSent] : ((empty(trim(__($l_EmailLostPasswordSent,$domain)))) ? __($l_EmailLostPasswordSent,$domainDefault) : __($l_EmailLostPasswordSent,$domain)); if(empty($translations[$l_EmailLostPasswordSent])){$translations[$l_EmailLostPasswordSent]='';}

__('Type in your e-mail to receive link for resetting your password');$l_LostPasswordExplanation = "Type in your e-mail to receive link for resetting your password";$language_LostPasswordExplanation = (!empty($translations[$l_LostPasswordExplanation]) && $is_frontend) ? $translations[$l_LostPasswordExplanation] : ((empty(trim(__($l_LostPasswordExplanation,$domain)))) ? __($l_LostPasswordExplanation,$domainDefault) : __($l_LostPasswordExplanation,$domain)); if(empty($translations[$l_LostPasswordExplanation])){$translations[$l_LostPasswordExplanation]='';}

__('Email required');$l_EmailRequired= "Email required";$language_EmailRequired = (!empty($translations[$l_EmailRequired]) && $is_frontend) ? $translations[$l_EmailRequired] : ((empty(trim(__($l_EmailRequired,$domain)))) ? __($l_EmailRequired,$domainDefault) : __($l_EmailRequired,$domain)); if(empty($translations[$l_EmailRequired])){$translations[$l_EmailRequired]='';}

__('Password');$l_Password = "Password";$language_Password = (!empty($translations[$l_Password]) && $is_frontend) ? $translations[$l_Password] : ((empty(trim(__($l_Password,$domain)))) ? __($l_Password,$domainDefault) : __($l_Password,$domain)); if(empty($translations[$l_Password])){$translations[$l_Password]='';}

__('New Password');$l_NewPassword = "New Password";$language_NewPassword = (!empty($translations[$l_NewPassword]) && $is_frontend) ? $translations[$l_NewPassword] : ((empty(trim(__($l_NewPassword,$domain)))) ? __($l_NewPassword,$domainDefault) : __($l_NewPassword,$domain)); if(empty($translations[$l_NewPassword])){$translations[$l_NewPassword]='';}

__('New Password repeat');$l_NewPasswordRepeat = "New Password repeat";$language_NewPasswordRepeat = (!empty($translations[$l_NewPasswordRepeat]) && $is_frontend) ? $translations[$l_NewPasswordRepeat] : ((empty(trim(__($l_NewPasswordRepeat,$domain)))) ? __($l_NewPasswordRepeat,$domainDefault) : __($l_NewPasswordRepeat,$domain)); if(empty($translations[$l_NewPasswordRepeat])){$translations[$l_NewPasswordRepeat]='';}

__('Password required');$l_PasswordRequired= "Password required";$language_PasswordRequired = (!empty($translations[$l_PasswordRequired]) && $is_frontend) ? $translations[$l_PasswordRequired] : ((empty(trim(__($l_PasswordRequired,$domain)))) ? __($l_PasswordRequired,$domainDefault) : __($l_PasswordRequired,$domain)); if(empty($translations[$l_PasswordRequired])){$translations[$l_PasswordRequired]='';}

__('Email and password do not match');$l_EmailAndPasswordDoNotMatch = "Email and password do not match";$language_EmailAndPasswordDoNotMatch = (!empty($translations[$l_EmailAndPasswordDoNotMatch]) && $is_frontend) ? $translations[$l_EmailAndPasswordDoNotMatch] : ((empty(trim(__($l_EmailAndPasswordDoNotMatch,$domain)))) ? __($l_EmailAndPasswordDoNotMatch,$domainDefault) : __($l_EmailAndPasswordDoNotMatch,$domain)); if(empty($translations[$l_EmailAndPasswordDoNotMatch])){$translations[$l_EmailAndPasswordDoNotMatch]='';}

__('Login and password do not match');$l_LoginAndPasswordDoNotMatch = "Login and password do not match";$language_LoginAndPasswordDoNotMatch = (!empty($translations[$l_LoginAndPasswordDoNotMatch]) && $is_frontend) ? $translations[$l_LoginAndPasswordDoNotMatch] : ((empty(trim(__($l_LoginAndPasswordDoNotMatch,$domain)))) ? __($l_LoginAndPasswordDoNotMatch,$domainDefault) : __($l_LoginAndPasswordDoNotMatch,$domain)); if(empty($translations[$l_LoginAndPasswordDoNotMatch])){$translations[$l_LoginAndPasswordDoNotMatch]='';}

__('Please fill out');$l_PleaseFillOut= "Please fill out";$language_PleaseFillOut = (!empty($translations[$l_PleaseFillOut]) && $is_frontend) ? $translations[$l_PleaseFillOut] : ((empty(trim(__($l_PleaseFillOut,$domain)))) ? __($l_PleaseFillOut,$domainDefault) : __($l_PleaseFillOut,$domain)); if(empty($translations[$l_PleaseFillOut])){$translations[$l_PleaseFillOut]='';}

__('Passwords do not match');$l_PasswordsDoNotMatch= "Passwords do not match";$language_PasswordsDoNotMatch = (!empty($translations[$l_PasswordsDoNotMatch]) && $is_frontend) ? $translations[$l_PasswordsDoNotMatch] : ((empty(trim(__($l_PasswordsDoNotMatch,$domain)))) ? __($l_PasswordsDoNotMatch,$domainDefault) : __($l_PasswordsDoNotMatch,$domain)); if(empty($translations[$l_PasswordsDoNotMatch])){$translations[$l_PasswordsDoNotMatch]='';}

__('Upload');$l_sendUpload= "Upload";$language_sendUpload = (!empty($translations[$l_sendUpload]) && $is_frontend) ? $translations[$l_sendUpload] : ((empty(trim(__($l_sendUpload,$domain)))) ? __($l_sendUpload,$domainDefault) : __($l_sendUpload,$domain)); if(empty($translations[$l_sendUpload])){$translations[$l_sendUpload]='';}

__('Send');$l_sendButton = "Send";$language_sendButton = (!empty($translations[$l_sendButton]) && $is_frontend) ? $translations[$l_sendButton] : ((empty(trim(__($l_sendButton,$domain)))) ? __($l_sendButton,$domainDefault) : __($l_sendButton,$domain)); if(empty($translations[$l_sendButton])){$translations[$l_sendButton]='';}

__('Register');$l_sendRegistry= "Register";$language_sendRegistry = (!empty($translations[$l_sendRegistry]) && $is_frontend) ? $translations[$l_sendRegistry] : ((empty(trim(__($l_sendRegistry,$domain)))) ? __($l_sendRegistry,$domainDefault) : __($l_sendRegistry,$domain)); if(empty($translations[$l_sendRegistry])){$translations[$l_sendRegistry]='';}

__('Login');$l_sendLogin= "Login";$language_sendLogin = (!empty($translations[$l_sendLogin]) && $is_frontend) ? $translations[$l_sendLogin] : ((empty(trim(__($l_sendLogin,$domain)))) ? __($l_sendLogin,$domainDefault) : __($l_sendLogin,$domain)); if(empty($translations[$l_sendLogin])){$translations[$l_sendLogin]='';}

__('Please select');$l_pleaseSelect= "Please select";$language_pleaseSelect = (!empty($translations[$l_pleaseSelect]) && $is_frontend) ? $translations[$l_pleaseSelect] : ((empty(trim(__($l_pleaseSelect,$domain)))) ? __($l_pleaseSelect,$domainDefault) : __($l_pleaseSelect,$domain)); if(empty($translations[$l_pleaseSelect])){$translations[$l_pleaseSelect]='';}

__('You have not selected');$l_youHaveNotSelected= "You have not selected";$language_youHaveNotSelected = (!empty($translations[$l_youHaveNotSelected]) && $is_frontend) ? $translations[$l_youHaveNotSelected] : ((empty(trim(__($l_youHaveNotSelected,$domain)))) ? __($l_youHaveNotSelected,$domainDefault) : __($l_youHaveNotSelected,$domain)); if(empty($translations[$l_youHaveNotSelected])){$translations[$l_youHaveNotSelected]='';}

__('Please confirm');$l_pleaseConfirm= "Please confirm";$language_pleaseConfirm = (!empty($translations[$l_pleaseConfirm]) && $is_frontend) ? $translations[$l_pleaseConfirm] : ((empty(trim(__($l_pleaseConfirm,$domain)))) ? __($l_pleaseConfirm,$domainDefault) : __($l_pleaseConfirm,$domain)); if(empty($translations[$l_pleaseConfirm])){$translations[$l_pleaseConfirm]='';}

__('File is not activated');$l_imageIsNotActivated= "File is not activated";$language_imageIsNotActivated = (!empty($translations[$l_imageIsNotActivated]) && $is_frontend) ? $translations[$l_imageIsNotActivated] : ((empty(trim(__($l_imageIsNotActivated,$domain)))) ? __($l_imageIsNotActivated,$domainDefault) : __($l_imageIsNotActivated,$domain)); if(empty($translations[$l_imageIsNotActivated])){$translations[$l_imageIsNotActivated]='';}

__('Your email could not be confirmed.');$l_ConfirmationWentWrong= "Your email could not be confirmed.";$language_ConfirmationWentWrong = (!empty($translations[$l_ConfirmationWentWrong]) && $is_frontend) ? $translations[$l_ConfirmationWentWrong] : ((empty(trim(__($l_ConfirmationWentWrong,$domain)))) ? __($l_ConfirmationWentWrong,$domainDefault) : __($l_ConfirmationWentWrong,$domain)); if(empty($translations[$l_ConfirmationWentWrong])){$translations[$l_ConfirmationWentWrong]='';}

__('Please enter a valid URL');$l_URLnotValid= "Please enter a valid URL";$language_URLnotValid = (!empty($translations[$l_URLnotValid]) && $is_frontend) ? $translations[$l_URLnotValid] : ((empty(trim(__($l_URLnotValid,$domain)))) ? __($l_URLnotValid,$domainDefault) : __($l_URLnotValid,$domain)); if(empty($translations[$l_URLnotValid])){$translations[$l_URLnotValid]='';}

__('Other');$l_Other= "Other";$language_Other = (!empty($translations[$l_Other]) && $is_frontend) ? $translations[$l_Other] : ((empty(trim(__($l_Other,$domain)))) ? __($l_Other,$domainDefault) : __($l_Other,$domain)); if(empty($translations[$l_Other])){$translations[$l_Other]='';}

// General
__('Yes');$l_Yes = "Yes"; $language_Yes = (!empty($translations[$l_Yes]) && $is_frontend) ? $translations[$l_Yes] : ((empty(trim(__($l_Yes,$domain)))) ? __($l_Yes,$domainDefault) : __($l_Yes,$domain)); if(empty($translations[$l_Yes])){$translations[$l_Yes]='';}
__('No');$l_No = "No"; $language_No = (!empty($translations[$l_No]) && $is_frontend) ? $translations[$l_No] : ((empty(trim(__($l_No,$domain)))) ? __($l_No,$domainDefault) : __($l_No,$domain)); if(empty($translations[$l_No])){$translations[$l_No]='';}


?>