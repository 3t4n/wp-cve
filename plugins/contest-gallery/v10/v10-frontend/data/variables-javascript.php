<?php
?>
<pre>
    <script data-cg-processing="true">

           if(typeof cgJsClass == 'undefined' ){ // required in JavaScript for first initialisation cgJsClass = cgJsClass || {}; would not work;
               cgJsClass = {};
           }

            cgJsClass.gallery = cgJsClass.gallery || {};
            cgJsClass.gallery.vars = cgJsClass.gallery.vars || {};

           if(typeof cgJsData == 'undefined' ){ // required in JavaScript for first initialisation cgJsData = cgJsData || {}; would not work;
               cgJsData = {};
           }

            // general stuff
           // var index = Object.keys(cgJsData).length;
            var index = <?php echo json_encode($galeryIDuserForJs); ?>;
            var shortcode_name = <?php echo json_encode($shortcode_name); ?>;

            // data gallery stuff
           if(cgJsData[index]){
               alert('This shortcode is inserted multiple times, which is not allowed: ['+shortcode_name+'  id="'+index+'"]');
           }else{
               cgJsData[index] = {};
               cgJsData[index].vars = {};
               cgJsData[index].vars.gidReal = <?php echo json_encode($galeryID); ?>;
               cgJsData[index].vars.versionDatabaseGallery = <?php echo json_encode($options['general']['Version']); ?>;
               cgJsData[index].vars.versionDatabaseGeneral = <?php echo json_encode($p_cgal1ery_db_version); ?>;
               cgJsData[index].vars.uploadFolderUrl = <?php echo json_encode($upload_folder_url); ?>;
               cgJsData[index].vars.cg_check_login = <?php echo json_encode($options['general']['CheckLogin']); ?>;
               cgJsData[index].vars.cg_user_login_check = <?php echo json_encode($UserLoginCheck); ?>;
               cgJsData[index].vars.cg_ContestEndTime = <?php echo json_encode($options['general']['ContestEndTime']); ?>;
               cgJsData[index].vars.cg_ContestEnd = <?php echo json_encode($options['general']['ContestEnd']); ?>;
               cgJsData[index].vars.formHasUrlField = 0;
               cgJsData[index].vars.cg_hide_hide_width = 0;
               cgJsData[index].vars.openedGalleryImageOrder = null;
               cgJsData[index].vars.categories = {};
               cgJsData[index].vars.categoriesUploadFormId = null;
               cgJsData[index].vars.categoriesUploadFormTitle = null;
               cgJsData[index].vars.showCategories = false;
               cgJsData[index].vars.info = {};
               cgJsData[index].vars.thumbViewWidth = null;
               cgJsData[index].vars.openedRealId = 0;
               cgJsData[index].vars.galleryLoaded = false;
               cgJsData[index].vars.getJson = [];
               cgJsData[index].vars.jsonGetInfo = [];
               cgJsData[index].vars.jsonGetComment = [];
               cgJsData[index].vars.jsonGetImageCheck = [];
               cgJsData[index].vars.searchInput = null;
               cgJsData[index].vars.categoriesLength = 0;
               cgJsData[index].vars.galleryAlreadyFullWindow = false;
               cgJsData[index].vars.lastRealIdInFullImageDataObject = 0;
               cgJsData[index].vars.thumbViewWidthFromLastImageInRow = false;
               cgJsData[index].vars.allVotesUsed = 0;
               cgJsData[index].vars.sorting = 0;
               cgJsData[index].vars.widthmain = 0;
               cgJsData[index].vars.translateX = <?php echo json_encode($options['pro']['SlideTransition']); ?>;
               cgJsData[index].vars.AllowRating = <?php echo json_encode($options['general']['AllowRating']); ?>;
               cgJsData[index].vars.maximumVisibleImagesInSlider = 0;
               cgJsData[index].vars.currentStep = 1;
               cgJsData[index].vars.sortedRandomFullData = null;
               cgJsData[index].vars.rowLogicCount = 0;
               cgJsData[index].vars.sortedDateDescFullData = null;
               cgJsData[index].vars.sortedDateAscFullData = null;
               cgJsData[index].vars.sortedRatingDescFullData = null;
               cgJsData[index].vars.sortedRatingAscFullData = null;
               cgJsData[index].vars.sortedCommentsDescFullData = null;
               cgJsData[index].vars.sortedCommentsAscFullData = null;
               cgJsData[index].vars.sortedSearchFullData = null;
               cgJsData[index].vars.isProVersion = <?php echo json_encode($isProVersion); ?>;
               cgJsData[index].vars.ShowFormAfterUploadOrContact = <?php echo json_encode($ShowFormAfterUploadOrContact); ?>;
               cgJsData[index].vars.imageDataLength = <?php echo json_encode($jsonImagesCount); ?>;
               cgJsData[index].vars.isUserGallery = <?php echo json_encode($isUserGallery); ?>;
               cgJsData[index].vars.isOnlyGalleryNoVoting = <?php echo json_encode($isOnlyGalleryNoVoting); ?>;
               cgJsData[index].vars.isOnlyGalleryWinner = <?php echo json_encode($isOnlyGalleryWinner); ?>;
               cgJsData[index].vars.isOnlyUploadForm = <?php echo json_encode($isOnlyUploadForm); ?>;
               cgJsData[index].vars.isOnlyContactForm = <?php echo json_encode($isOnlyContactForm); ?>;
               cgJsData[index].vars.galleryHash = <?php echo json_encode(cg_hash_function('---cngl1---'.$galeryIDuserForJs)); ?>;
               cgJsData[index].vars.RatingVisibleForGalleryNoVoting = <?php echo json_encode($RatingVisibleForGalleryNoVoting); ?>;
               cgJsData[index].vars.isFbLikeOnlyShareOn = <?php echo json_encode($isFbLikeOnlyShareOn); ?>;
               cgJsData[index].vars.upload = {};
               cgJsData[index].vars.upload.cg_upload_form_e_prevent_default = '';
               cgJsData[index].vars.upload.cg_upload_form_e_prevent_default_file_resolution = 0;
               cgJsData[index].vars.upload.cg_upload_form_e_prevent_default_file_not_loaded = 0;
               cgJsData[index].vars.upload.UploadedUserFilesAmount = <?php echo json_encode($UploadedUserFilesAmount); ?>;
               cgJsData[index].vars.upload.UploadedUserFilesAmountPerCategoryArray = <?php echo json_encode($UploadedUserFilesAmountPerCategoryArray); ?>;
               cgJsData[index].vars.upload.CookieId = <?php echo json_encode($CookieId); ?>;
               cgJsData[index].vars.centerWhite = <?php echo json_encode($cgCenterWhite); ?>;
               cgJsData[index].vars.blogViewImagesLoadedCount = <?php echo json_encode(0); ?>;
               cgJsData[index].fullImageInfoData = {};
               cgJsData[index].vars.language = {};
               cgJsData[index].vars.language.pro = {};
               cgJsData[index].vars.language.pro.VotesPerUserAllVotesUsedHtmlMessage = <?php echo json_encode($language_VotesPerUserAllVotesUsedHtmlMessage); ?>;
               cgJsData[index].vars.queryDataArray = <?php echo json_encode($queryDataArray); ?>;
               cgJsData[index].vars.hasWpPageParent = <?php echo json_encode($hasWpPageParent); ?>;
               cgJsData[index].vars.isCgWpPageEntryLandingPage = <?php echo json_encode($isCgWpPageEntryLandingPage); ?>;
               cgJsData[index].vars.galleryShortCodeEntryId = <?php echo json_encode($entryId); ?>;
               cgJsData[index].vars.nicknames = <?php echo json_encode($nicknamesArray); ?>;
               cgJsData[index].vars.profileImages = <?php echo json_encode($profileImagesArray); ?>;
           }

    </script>
</pre>

<?php

if($options['general']['CheckCookie'] == 1 && !isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting'])){
?>
    <pre>
    <script data-cg-processing="true">

        var index = <?php echo json_encode($galeryIDuserForJs); ?>;
        cgJsData[index].vars.cookieVotingId = <?php echo json_encode(md5(uniqid('cg',true)).time()); ?>;

    </script>
</pre>
    <?php

}


?>