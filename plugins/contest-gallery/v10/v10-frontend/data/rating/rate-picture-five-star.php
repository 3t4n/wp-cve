<?php
if(!defined('ABSPATH')){exit;}

$galeryID = intval(sanitize_text_field($_REQUEST['gid']));// is gidReal
$pictureID = intval(sanitize_text_field($_REQUEST['pid']));
$rateValue = intval(sanitize_text_field($_REQUEST['value']));
$minusVoteNow = intval(sanitize_text_field($_REQUEST['minusVoteNow']));
$galeryIDuser = sanitize_text_field($_REQUEST['galeryIDuser']);
$galleryHash = sanitize_text_field($_REQUEST['galleryHash']);
$galleryHashDecoded = wp_salt( 'auth').'---cngl1---'.$galeryIDuser;
$galleryHashToCompare = cg_hash_function('---cngl1---'.$galeryIDuser, $galleryHash);
$isFromSingleView = sanitize_text_field(sanitize_text_field($_REQUEST['isFromSingleView']));
/*error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set('error_reporting', E_ALL);*/

//------------------------------------------------------------
// ----------------------------------------------------------- Rate images ----------------------------------------------------------
//------------------------------------------------------------


$tablename = $wpdb->prefix ."contest_gal1ery";
$tablenameIP = $wpdb->prefix ."contest_gal1ery_ip";
$tablenameOptions = $wpdb->prefix ."contest_gal1ery_options";
$tablename_pro_options = $wpdb->prefix . "contest_gal1ery_pro_options";
$tablename_mail_user_vote = $wpdb->prefix . "contest_gal1ery_mail_user_vote";
$tablename_user_vote_mails = $wpdb->prefix . "contest_gal1ery_user_vote_mails";
$wp_users = $wpdb->prefix . "users";

$wp_upload_dir = wp_upload_dir();
$options = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/'.$galeryID.'-options.json';
$fp = fopen($options, 'r');
$options =json_decode(fread($fp,filesize($options)),true);

$optionsSource = $options;

if(!empty($options[$galeryID])){
    $options = $options[$galeryID];
}

fclose($fp);

$jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/image-data/image-data-'.$pictureID.'.json';
$fp = fopen($jsonFile, 'r');
$ratingFileData =json_decode(fread($fp,filesize($jsonFile)),true);
fclose($fp);

// possible code if has to be checked if categories has to be some kind of visible in frontend
/*
$categoryFieldIsVisibleForFrontendUser = false;

$categoriesFormFieldId = 0;

$jsonFormUploadFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/'.$galeryID.'-form-upload.json';

if(file_exists($jsonFormUploadFile)){
    $fp = fopen($jsonFormUploadFile, 'r');
    $uploadFormFieldsArray = json_decode(fread($fp,filesize($jsonFormUploadFile)),true);
    fclose($fp);
    if(!empty($uploadFormFieldsArray) AND is_array($uploadFormFieldsArray)){
        foreach ($uploadFormFieldsArray as $formFieldId => $valuesArray){
            if($valuesArray['Field_Type']=='selectc-f'){
                $categoriesFormFieldId = $formFieldId;
            }
            if($valuesArray['Field_Type']=='selectc-f' AND !empty($valuesArray['Show_Slider'])){
                $categoriesFormFieldId = $formFieldId;
                $categoryFieldIsVisibleForFrontendUser = true;break;
            }
        }
    }
}*/


if (($rateValue>10 or $rateValue<1) or ($galleryHash != $galleryHashToCompare)){
    ?>
    <script data-cg-processing="true">

        var message = <?php echo json_encode('Please do not manipulate!');?>;
        var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
        var pictureID = <?php echo json_encode($pictureID);?>;
        var isFromSingleView = <?php echo json_encode($isFromSingleView);?>;
        var ratingFileData = <?php echo json_encode($ratingFileData);?>;

        cgJsClass.gallery.function.message.close(true);

        cgJsClass.gallery.rating.setRatingFiveStar(pictureID,0,0,false,galeryIDuser,false,false,ratingFileData,isFromSingleView,undefined,undefined,true);
        cgJsClass.gallery.function.message.show(galeryIDuser,message);

    </script>
    <?php

    return;
}
else {

    $explodeHash = explode('---cngl1---',$galleryHashDecoded);
    //if($explodeHash[1]==$galeryID.'-u'){
    if(strpos($explodeHash[1],$galeryID.'-u')!==false){
            ?>
        <script data-cg-processing="true">

            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
            var pictureID = <?php echo json_encode($pictureID);?>;
            var isFromSingleView = <?php echo json_encode($isFromSingleView);?>;
            var ratingFileData = <?php echo json_encode($ratingFileData);?>;

            cgJsClass.gallery.function.message.close(true);

            cgJsClass.gallery.rating.setRatingFiveStar(pictureID,0,0,false,galeryIDuser,false,false,ratingFileData,isFromSingleView,undefined,undefined,true);
            cgJsClass.gallery.function.message.show(galeryIDuser,cgJsClass.gallery.language[galeryIDuser].YouCanNotVoteInOwnGallery);

        </script>
        <?php

        return;
    }
    //if($explodeHash[1]==$galeryID.'-nv'){
    if(strpos($explodeHash[1],$galeryID.'-nv')!==false){
            ?>
        <script data-cg-processing="true">

            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
            var pictureID = <?php echo json_encode($pictureID);?>;
            var isFromSingleView = <?php echo json_encode($isFromSingleView);?>;
            var ratingFileData = <?php echo json_encode($ratingFileData);?>;

            cgJsClass.gallery.function.message.close(true);

            cgJsClass.gallery.rating.setRatingFiveStar(pictureID,0,0,false,galeryIDuser,false,false,ratingFileData,isFromSingleView);

        </script>
        <?php
        return;
    }

    //if($explodeHash[1]==$galeryID.'-w'){
    if(strpos($explodeHash[1],$galeryID.'-w')!==false){
            return;
    }

    $intervalConf = cg_shortcode_interval_check($galeryID,$optionsSource,'cg_gallery');
    if(!$intervalConf['shortcodeIsActive']){
        ?>
        <script data-cg-processing="true">
            var ratingFileData = <?php echo json_encode($ratingFileData);?>;
            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
            var isFromSingleView = <?php echo json_encode($isFromSingleView);?>;
            var pictureID = <?php echo json_encode($pictureID);?>;
            cgJsClass.gallery.rating.setRatingFiveStar(pictureID,0,0,false,galeryIDuser,false,false,ratingFileData,isFromSingleView,undefined,undefined,true);
        </script>
        <?php
        cg_shortcode_interval_check_show_ajax_message($intervalConf,$galeryIDuser);
        return;
    }

    $categoriesArray = array();

    $jsonCategoriesFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/'.$galeryID.'-categories.json';

    if(file_exists($jsonCategoriesFile)){
        $fp = fopen($jsonCategoriesFile, 'r');
        $categoriesArray = json_decode(fread($fp,filesize($jsonCategoriesFile)),true);
        fclose($fp);
        if(empty($categoriesArray) OR !is_array($categoriesArray)){
            $categoriesArray = array();
        }
    }

    $plugin_dir_path = plugin_dir_path(__FILE__);

    // $getOptions = $wpdb->get_row( "SELECT AllowGalleryScript, CheckLogin, AllowRating, ShowOnlyUsersVotes, IpBlock, VotesPerUser, HideUntilVote, RatingOutGallery, ContestEnd, ContestEndTime FROM $tablenameOptions WHERE id = '$galeryID'" );
    $ShowOnlyUsersVotes = $options['general']['ShowOnlyUsersVotes'];
    $CheckLogin = $options['general']['CheckLogin'];
    $CheckIp= (!empty($options['general']['CheckIp'])) ? 1 : 0;
    $CheckCookie = (!empty($options['general']['CheckCookie'])) ? 1 : 0;
    $CheckCookieAlertMessage = (!empty($options['general']['CheckCookieAlertMessage'])) ? $options['general']['CheckCookieAlertMessage'] : 'Please allow cookies and reload the page to be able to vote.';
    $AllowRating = $options['general']['AllowRating'];
    $OneVotePerPicture = $options['general']['IpBlock'];// ATTENTION! IpBlock means show only vote per Picture Configuration
    $VotesPerUser = $options['general']['VotesPerUser'];
    $HideUntilVote = $options['general']['HideUntilVote'];
    $RatingOutGallery = $options['general']['RatingOutGallery'];
    $ContestStart = (!empty($options['general']['ContestStart'])) ? $options['general']['ContestStart'] : 0;
    if(floatval($options['general']['Version'])>=21.1){
        $ContestStart = 0;
    }
    $ContestStartTime = (!empty($options['general']['ContestStartTime'])) ? $options['general']['ContestStartTime'] : 0;
    $ContestEnd = $options['general']['ContestEnd'];
    if(floatval($options['general']['Version'])>=21.1){
        $ContestEnd = 0;
    }
    $ContestEndTime = $options['general']['ContestEndTime'];

    // ThankVote correction added 16.01.2021
    if(!empty($options['visual']['ThankVote'])){
        $ThankVote = $options['visual']['ThankVote'];
    }else{
        $ThankVote = 0;
    }

    $MinusVote = $options['pro']['MinusVote'];
    $VotesInTime = $options['pro']['VotesInTime'];
    $VotesInTimeQuantity = $options['pro']['VotesInTimeQuantity'];
    $VotesInTimeIntervalSeconds = $options['pro']['VotesInTimeIntervalSeconds'];
    $VoteNotOwnImage = (!empty($options['pro']['VoteNotOwnImage'])) ? $options['pro']['VoteNotOwnImage'] : 0;
    $IsModernFiveStar = intval($options['pro']['IsModernFiveStar']);
    //$Field1IdGalleryView = intval($options['visual']['Field1IdGalleryView']);

    // requires after updating 12.3.0
    $VoteMessageSuccessActive = (!empty($options['pro']['VoteMessageSuccessActive'])) ? 1 : 0;
    $VoteMessageSuccessText = (!empty($options['pro']['VoteMessageSuccessText'])) ? $options['pro']['VoteMessageSuccessText'] : '';
    $VoteMessageWarningActive = (!empty($options['pro']['VoteMessageWarningActive'])) ? 1 : 0;
    $VoteMessageWarningText = (!empty($options['pro']['VoteMessageWarningText'])) ? $options['pro']['VoteMessageWarningText'] : '';

    $CategoriesOn = 0;// means that it was visible for user in that time that he votes for this category

    if(!empty($categoriesArray)){
        $CategoriesOn = 1;
    }

    // possible code if has to be checked if categories has to be some kind of visible in frontend
    /*if($categoryFieldIsVisibleForFrontendUser){
        $CategoriesOn = 1;
    }else{
        if(!empty($categoriesArray) AND !empty($categoriesFormFieldId)){// then there were categories in that moment
            if($categoriesFormFieldId==$Field1IdGalleryView){ // then check also if "Show as title in gallery view" is checked for category
                $CategoriesOn = 1;
            }
        }
    }*/

    if(!empty($options['pro']['VotePerCategory'])){
        $VotePerCategory = $options['pro']['VotePerCategory'];
    }else{
        $VotePerCategory = 0;
    }
    if(!empty($options['pro']['VotesPerCategory'])){
        $VotesPerCategory = $options['pro']['VotesPerCategory'];
    }else{
        $VotesPerCategory = 0;
    }
    $countVotesOfUserPerGallery = 0;

    if(!is_dir($wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/frontend-added-votes')){
        mkdir($wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/frontend-added-votes',0755,true);
    }

    $ratingFileData = cg_check_and_repair_image_file_data($galeryID,$pictureID,$ratingFileData,$IsModernFiveStar);

    if(empty($ratingFileData['Category'])){// repair here for sure
        $ratingFileData['Category'] = 0;
    }

    $cookieVotingJustActivated = false;

    $OptionSet = '';

    if($CheckLogin==1){
        $OptionSet = 'CheckLogin';
    }else if($CheckCookie==1 && $CheckIp!=1){
        $OptionSet = 'CheckCookie';
    }else if($CheckIp==1 && $CheckCookie!=1){
        $OptionSet = 'CheckIp';
    }else if($CheckIp==1 && $CheckCookie==1){
        $OptionSet = 'CheckIpAndCookie';
    }

    if(is_user_logged_in()){
        $wpUserId = get_current_user_id();
    }
    else{
        $wpUserId=0;
    }

    if($CheckLogin==1 &&  $wpUserId==0){

        ?>
        <script data-cg-processing="true">

            var ContestStartTimeFromPhp = <?php echo json_encode($ContestStartTime);?>;
            var ContestStart = <?php echo json_encode($ContestStart);?>;
            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
            var pictureID = <?php echo json_encode($pictureID);?>;
            var isFromSingleView = <?php echo json_encode($isFromSingleView);?>;
            var ratingFileData = <?php echo json_encode($ratingFileData);?>;

            cgJsClass.gallery.rating.setRatingFiveStar(pictureID,0,0,false,galeryIDuser,false,false,ratingFileData,isFromSingleView);

        </script>
        <?php

        return;
    }

    $time = time();

    if($time < $ContestStartTime && $ContestStart==1){

        ?>
        <script data-cg-processing="true">

            var ContestStartTimeFromPhp = <?php echo json_encode($ContestStartTime);?>;
            var ActualTimeSecondsFromPhp = <?php echo json_encode($time);?>;
            var ContestStart = <?php echo json_encode($ContestStart);?>;
            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
            var pictureID = <?php echo json_encode($pictureID);?>;
            var isFromSingleView = <?php echo json_encode($isFromSingleView);?>;
            var ratingFileData = <?php echo json_encode($ratingFileData);?>;


            cgJsClass.gallery.function.general.time.photoContestStartTimeCheck(galeryIDuser,ActualTimeSecondsFromPhp,ContestStartTimeFromPhp,ContestStart);
            cgJsClass.gallery.rating.setRatingFiveStar(pictureID,0,0,false,galeryIDuser,false,false,ratingFileData,isFromSingleView);

        </script>
        <?php

        return;
    }

    if(($time>=$ContestEndTime && $ContestEnd==1) OR $ContestEnd==2){
        $ContestEnd = 2;// photo contest will be ended this way
        ?>
        <script data-cg-processing="true">

            var ContestEndTimeFromPhp = <?php echo json_encode($ContestEndTime);?>;
            var ActualTimeSecondsFromPhp = <?php echo json_encode($time);?>;
            var ContestEnd = <?php echo json_encode($ContestEnd);?>;
            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
            var pictureID = <?php echo json_encode($pictureID);?>;
            var isFromSingleView = <?php echo json_encode($isFromSingleView);?>;
            var ratingFileData = <?php echo json_encode($ratingFileData);?>;

            cgJsClass.gallery.function.general.time.photoContestEndTimeCheck(galeryIDuser,ActualTimeSecondsFromPhp,ContestEndTimeFromPhp,ContestEnd);
            cgJsClass.gallery.rating.setRatingFiveStar(pictureID,0,0,false,galeryIDuser,false,false,ratingFileData,isFromSingleView);

        </script>
        <?php

        return;
    }

    $userIP = sanitize_text_field(cg_get_user_ip());
    if(empty($userIP) OR $userIP == 'unknown'){
        ?>
        <script data-cg-processing="true">
            cgJsClass.gallery.function.message.close(true);
            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
            cgJsClass.gallery.function.message.show(galeryIDuser,"IP could not be identified, code 506. Please contact administrator.");
        </script>
        <?php
        echo 'IP could not be identified, code 506. Please contact administrator.';
        die;
    }

    if($VoteNotOwnImage==1 && empty($minusVoteNow) && $CheckCookie!=1){//does not need to work for check cookie in the moment

        // Get IP of uploaded image. Get WpUserId of uploaded image to go sure.
        $uploadedImageIPandWpUserId = $wpdb->get_row( "SELECT IP, WpUserId FROM $tablename WHERE id = $pictureID ORDER BY id DESC LIMIT 1" );

        $isOwnImage = false;

        if($CheckLogin==1 && ($uploadedImageIPandWpUserId->WpUserId==$wpUserId && !empty($wpUserId))){
            $isOwnImage = true;
        }else if($CheckIp==1 && ($uploadedImageIPandWpUserId->IP==$userIP && !empty($userIP))){
            $isOwnImage = true;
        }

        if($isOwnImage){

            ?>
            <script data-cg-processing="true">

                var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
                var pictureID = <?php echo json_encode($pictureID);?>;
                var isFromSingleView = <?php echo json_encode($isFromSingleView);?>;
                var ratingFileData = <?php echo json_encode($ratingFileData);?>;

                cgJsClass.gallery.function.message.close(true);

                cgJsClass.gallery.rating.setRatingFiveStar(pictureID,0,0,false,galeryIDuser,true,false,ratingFileData,isFromSingleView,undefined,undefined,true);
                cgJsClass.gallery.function.message.show(galeryIDuser,cgJsClass.gallery.language[galeryIDuser].ItIsNotAllowedToVoteForYourOwnPicture);

            </script>

            <?php

            return;

        }

    }


    if($CheckCookie==1) {
        if(!isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting'])) {
            $cookieValue = cg_set_cookie($galeryID,'voting');
            ?>
            <script data-cg-processing="true">

                var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
                var pictureID = <?php echo json_encode($pictureID);?>;
                var CheckCookieAlertMessage = <?php echo json_encode($CheckCookieAlertMessage);?>;
                var isFromSingleView = <?php echo json_encode($isFromSingleView);?>;
                var ratingFileData = <?php echo json_encode($ratingFileData);?>;

                    cgJsClass.gallery.function.message.close(true);
                    cgJsClass.gallery.rating.setRatingFiveStar(pictureID,0,0,false,galeryIDuser,false,false,ratingFileData,isFromSingleView,undefined,undefined,true);
                cgJsClass.gallery.function.message.show(galeryIDuser,CheckCookieAlertMessage);
                    cgJsClass.gallery.vars.cookiesNotAllowed = true;

                // cookie value will be get via getCookie before request
                //var cookieValue = <?php //echo json_encode($cookieValue);?>;
                //cgJsClass.gallery.vars.cookieValue = cookieValue;

            </script>

            <?php

            // no return here!!! Upper script tag might be processed with another script tag

        }
    }


    $getRatingPicture = 0;
    $countVotesOfUserPerGallery = 0;
    $CookieId = '';
    if(isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting']) && $options['general']['CheckCookie'] == 1) {
        $CookieId = $_COOKIE['contest-gal1ery-'.$galeryID.'-voting'];
    }

    // Sowohl Rating mit 5 Sternen wie auch Rating mit 1 Stern sollen von einander getrennt behandelt werden.
    // Deswegen die Abfragen mit if AllowRating ....
    if ($CheckLogin == 1 && $wpUserId>0)
    {
        if(is_user_logged_in()){
            $getRatingPicture = $wpdb->get_var( $wpdb->prepare(
                "
			SELECT COUNT(*) AS NumberOfRows
			FROM $tablenameIP
			WHERE pid = %d and GalleryID = %d and WpUserId = %s and Rating > %s
		",
                $pictureID,$galeryID,$wpUserId,0
            ) );
        }
    }
    else if ($CheckCookie == 1 && $CheckIp!=1)
    {
        if(isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting'])) {

            $getRatingPicture = $wpdb->get_var( $wpdb->prepare(
                "
                SELECT COUNT(*) AS NumberOfRows
                FROM $tablenameIP 
                WHERE pid = %d and GalleryID = %d and CookieId = %s and Rating > %s
            ",
                $pictureID,$galeryID,$CookieId,0
            ) );

        }
    }
    else if ($CheckIp == 1 && $CheckCookie!=1) {
        $getRatingPicture = $wpdb->get_var( $wpdb->prepare(
            "
            SELECT COUNT(*) AS NumberOfRows
            FROM $tablenameIP 
            WHERE pid = %d and GalleryID = %d and IP = %s and Rating > %s
        ",
            $pictureID,$galeryID,$userIP,0
        ) );
    } else if ($CheckIp == 1 && $CheckCookie == 1){

        if(isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting'])) {

            $getRatingPicture = $wpdb->get_var( $wpdb->prepare(
                "
            SELECT COUNT(*) AS NumberOfRows
            FROM $tablenameIP 
            WHERE (pid = %d and GalleryID = %d and IP = %s and Rating > %s) OR (pid = %d and GalleryID = %d and CookieId = %s and Rating > %s)
        ",
                $pictureID,$galeryID,$userIP,0,$pictureID,$galeryID,$CookieId,0
            ) );

        } else{
            $getRatingPicture = $wpdb->get_var( $wpdb->prepare(
                "
            SELECT COUNT(*) AS NumberOfRows
            FROM $tablenameIP 
            WHERE pid = %d and GalleryID = %d and IP = %s and Rating > %s
        ",
                $pictureID,$galeryID,$userIP,0
            ) );
        }

    }

    // Check how many votings a certail user has
    if(!empty($VotesPerUser)){

        if ($CheckLogin == 1 && $wpUserId>0)
        {
            if(is_user_logged_in()){
                $countVotesOfUserPerGallery = $wpdb->get_var( $wpdb->prepare(
                    "
						SELECT COUNT(*) AS NumberOfRows
						FROM $tablenameIP 
						WHERE GalleryID = %d and WpUserId = %s and Rating > %d
					",
                    $galeryID,$wpUserId,0
                ) );
            }
        }
        else if ($CheckCookie == 1 && $CheckIp!=1)
        {
            if(isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting'])) {
                $countVotesOfUserPerGallery = $wpdb->get_var( $wpdb->prepare(
                    "
						SELECT COUNT(*) AS NumberOfRows
						FROM $tablenameIP 
						WHERE GalleryID = %d and CookieId = %s and Rating > %d
					",
                    $galeryID,$CookieId,0
                ) );
            }
        }
        else if ($CheckIp == 1 && $CheckCookie!=1) {

            $countVotesOfUserPerGallery = $wpdb->get_var( $wpdb->prepare(
                "
						SELECT COUNT(*) AS NumberOfRows
						FROM $tablenameIP 
						WHERE GalleryID = %d and IP = %s and Rating > %d
					",
                $galeryID,$userIP,0
            ) );

        }
        else if ($CheckIp == 1 && $CheckCookie==1) {
            if(isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting'])) {
                $countVotesOfUserPerGallery = $wpdb->get_var( $wpdb->prepare(
                    "
						SELECT COUNT(*) AS NumberOfRows
						FROM $tablenameIP 
						WHERE (GalleryID = %d and IP = %s and Rating > %d) OR (GalleryID = %d and CookieId = %s and Rating > %d)
					",
                    $galeryID,$userIP,0,$galeryID,$CookieId,0
                ) );
            }else{
                $countVotesOfUserPerGallery = $wpdb->get_var( $wpdb->prepare(
                    "
						SELECT COUNT(*) AS NumberOfRows
						FROM $tablenameIP 
						WHERE GalleryID = %d and IP = %s and Rating > %d
					",
                    $galeryID,$userIP,0
                ) );
            }
        }
    }

    $countVotesOfUserPerCategory = 0;

    if(!empty($VotePerCategory) OR !empty($VotesPerCategory)){

        if ($CheckLogin == 1 && $wpUserId>0)
        {
            if(is_user_logged_in()){
                $countVotesOfUserPerCategory = $wpdb->get_var( $wpdb->prepare(
                    "
						SELECT COUNT(*) AS NumberOfRows
						FROM $tablenameIP 
						WHERE GalleryID = %d and WpUserId = %s and Rating > %d and Category = %d and CategoriesOn = %d
					",
                    $galeryID,$wpUserId,0,$ratingFileData['Category'],1
                ) );
            }
        }
        else if ($CheckCookie == 1 && $CheckIp!=1)
        {
            if(isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting'])) {
                $countVotesOfUserPerCategory = $wpdb->get_var( $wpdb->prepare(
                    "
						SELECT COUNT(*) AS NumberOfRows
						FROM $tablenameIP 
						WHERE GalleryID = %d and CookieId = %s and Rating > %d and Category = %d and CategoriesOn = %d
					",
                    $galeryID,$CookieId,0,$ratingFileData['Category'],1
                ) );
            }
        }
        else if ($CheckIp == 1 && $CheckCookie!=1) {
            $countVotesOfUserPerCategory = $wpdb->get_var( $wpdb->prepare(
                "
						SELECT COUNT(*) AS NumberOfRows
						FROM $tablenameIP 
						WHERE GalleryID = %d and IP = %s and Rating > %d and Category = %d and CategoriesOn = %d
					",
                $galeryID,$userIP,0,$ratingFileData['Category'],1
            ) );
        }
        else if ($CheckIp == 1 && $CheckCookie==1) {
            if(isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting'])) {
                $countVotesOfUserPerCategory = $wpdb->get_var( $wpdb->prepare(
                    "
						SELECT COUNT(*) AS NumberOfRows
						FROM $tablenameIP 
						WHERE (GalleryID = %d and IP = %s and Rating > %d and Category = %d and CategoriesOn = %d) OR (GalleryID = %d and CookieId = %s and Rating > %d and Category = %d and CategoriesOn = %d)
					",
                    $galeryID,$userIP,0,$ratingFileData['Category'],1,$galeryID,$CookieId,0,$ratingFileData['Category'],1
                ) );
            }else{
                $countVotesOfUserPerCategory = $wpdb->get_var( $wpdb->prepare(
                    "
						SELECT COUNT(*) AS NumberOfRows
						FROM $tablenameIP 
						WHERE GalleryID = %d and IP = %s and Rating > %d and Category = %d and CategoriesOn = %d
					",
                    $galeryID,$userIP,0,$ratingFileData['Category'],1
                ) );
            }
        }

    }

    $allVotesUsed = 0;

    if(($countVotesOfUserPerGallery >= $VotesPerUser) && ($VotesPerUser!=0)){
        $allVotesUsed = 1;
    }

    $Tstamp = time();

    $VotesUserInTstamp = 0;


   if($MinusVote == 1 && !empty($minusVoteNow)){

       /**###NORMAL###**/
       if(is_dir ($plugin_dir_path.'/../../../../../contest-gallery')){
           cg_update_to_pro_five_stars($galeryIDuser,$pictureID,$ratingFileData,'Update to PRO version to use "One vote per picture" function');
           return true;
       }
       /**###NORMAL-END###**/

     $lastVotedIpRow = 0;
    if($CheckLogin==1 && $wpUserId>0){
        if(is_user_logged_in()){
            $lastVotedIpRow = $wpdb->get_row( "SELECT id, Rating FROM $tablenameIP WHERE Rating >= '1' && WpUserId = '$wpUserId' && GalleryID = '$galeryID' && pid = '$pictureID' ORDER BY id DESC LIMIT 1" );
            $countUserVotesForImage = $wpdb->get_var( "SELECT COUNT(*) AS NumberOfRows FROM $tablenameIP WHERE Rating >= '1' && WpUserId = '$wpUserId' && GalleryID = '$galeryID' && pid = '$pictureID'" );
        }
     }else if ($CheckCookie == 1 && $CheckIp != 1){
        if(isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting'])) {
            $lastVotedIpRow = $wpdb->get_row( "SELECT id, Rating FROM $tablenameIP WHERE Rating >= '1' && CookieId = '$CookieId' && GalleryID = '$galeryID' && pid = '$pictureID' ORDER BY id DESC LIMIT 1" );
            $countUserVotesForImage = $wpdb->get_var( "SELECT COUNT(*) AS NumberOfRows FROM $tablenameIP WHERE Rating >= '1' && CookieId = '$CookieId' && GalleryID = '$galeryID' && pid = '$pictureID'" );
        }
     } else if ($CheckIp == 1 && $CheckCookie!=1) {
        $lastVotedIpRow = $wpdb->get_row("SELECT id, Rating FROM $tablenameIP WHERE Rating >= '1' && IP = '$userIP' && GalleryID = '$galeryID' && pid = '$pictureID' ORDER BY id DESC LIMIT 1");
        $countUserVotesForImage = $wpdb->get_var("SELECT COUNT(*) AS NumberOfRows FROM $tablenameIP WHERE Rating >= '1' && IP = '$userIP' && GalleryID = '$galeryID' && pid = '$pictureID'");
    }else if ($CheckIp == 1 && $CheckCookie==1) {
        if(isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting'])) {
            $lastVotedIpRow = $wpdb->get_row( "SELECT id, Rating FROM $tablenameIP WHERE (Rating >= '1' && IP = '$userIP' && GalleryID = '$galeryID' && pid = '$pictureID') OR (Rating >= '1' && CookieId = '$CookieId' && GalleryID = '$galeryID' && pid = '$pictureID') ORDER BY id DESC LIMIT 1" );
            $countUserVotesForImage = $wpdb->get_var( "SELECT COUNT(*) AS NumberOfRows FROM $tablenameIP WHERE  (Rating >= '1' && IP = '$userIP' && GalleryID = '$galeryID' && pid = '$pictureID') OR (Rating >= '1' && CookieId = '$CookieId' && GalleryID = '$galeryID' && pid = '$pictureID')" );
        } else {
            $lastVotedIpRow = $wpdb->get_row("SELECT id, Rating FROM $tablenameIP WHERE Rating >= '1' && IP = '$userIP' && GalleryID = '$galeryID' && pid = '$pictureID' ORDER BY id DESC LIMIT 1");
            $countUserVotesForImage = $wpdb->get_var("SELECT COUNT(*) AS NumberOfRows FROM $tablenameIP WHERE Rating >= '1' && IP = '$userIP' && GalleryID = '$galeryID' && pid = '$pictureID'");
        }
    }

    // maybe vote was done without reloading after changing configuration and user votes were reseted! Thats why this check has to be done!
        if(!empty($lastVotedIpRow)){


            $wpdb->delete( $tablenameIP, array( 'id' => $lastVotedIpRow->id ), array( '%d' ) );

            $countR = intval($ratingFileData['CountR'])-1;
            $rating = intval($ratingFileData['Rating'])-intval($lastVotedIpRow->Rating);
            $ratingFileData['CountR'] = $countR;


            if($IsModernFiveStar==1){
                $countRtype = 'CountR'.$lastVotedIpRow->Rating;
                $countRtoAdd = intval($ratingFileData['CountR'.$lastVotedIpRow->Rating])-1;
                // update main table
                $wpdb->update(
                    "$tablename",
                    array('CountR' => $countR,'Rating' => $rating,$countRtype => $countRtoAdd),
                    array('id' => $pictureID),
                    array('%d','%d','%d'),
                    array('%d')
                );
            }else{
                $wpdb->update(
                    "$tablename",
                    array('CountR' => $countR,'Rating' => $rating),
                    array('id' => $pictureID),
                    array('%d','%d'),
                    array('%d')
                );
            }


            $fp = fopen($jsonFile, 'w');
            $ratingFileData['CountR'] = intval($countR);
            $ratingFileData['Rating'] = intval($rating);

            if($IsModernFiveStar==1){
                $ratingFileData['CountR'.$lastVotedIpRow->Rating] = $countRtoAdd;
            }


            fwrite($fp,json_encode($ratingFileData));
            fclose($fp);

            $isSetUserVoteToNull = false;
            if(empty($countUserVotesForImage)){
                $isSetUserVoteToNull = true;
            }

            ?>
            <script data-cg-processing="true">

                var ContestEndTimeFromPhp = <?php echo json_encode($ContestEndTime);?>;
                var ContestEnd = <?php echo json_encode($ContestEnd);?>;
                var pictureID = <?php echo json_encode($pictureID);?>;
                var ratingFileData = <?php echo json_encode($ratingFileData);?>;
                var isFromSingleView = <?php echo json_encode($isFromSingleView);?>;
                var isSetUserVoteToNull = <?php echo json_encode($isSetUserVoteToNull);?>;
                var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;

                if(cgJsClass.gallery.function.general.time.photoContestEndTimeCheck(galeryIDuser,ContestEndTimeFromPhp,ContestEnd)==true){
                    var Rating = <?php echo json_encode($lastVotedIpRow->Rating);?>;
                    Rating = parseInt(Rating);

                    cgJsClass.gallery.rating.setRatingFiveStar(pictureID,-1,-Rating,false,galeryIDuser,false,false,ratingFileData,isFromSingleView,isSetUserVoteToNull);

                }else{
                    cgJsClass.gallery.rating.setRatingFiveStar(pictureID,0,0,false,galeryIDuser,false,false,ratingFileData,isFromSingleView,isSetUserVoteToNull);
                }

                cgJsData[galeryIDuser].vars.allVotesUsed = 0;

            </script>
            <?php

            // simply create empty file for later check
            $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/frontend-added-votes/'.$pictureID.'-'.time().'.txt';
            $fp = fopen($jsonFile, 'w');
            fwrite($fp, '');
            fclose($fp);

            //cg_actualize_all_images_data_sort_values_file($galeryID,false,$IsModernFiveStar);

            return;


        }else{
            ?>
            <script data-cg-processing="true">

                var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
                var isFromSingleView = <?php echo json_encode($isFromSingleView);?>;
                var pictureID = <?php echo json_encode($pictureID);?>;
                var ratingFileData = <?php echo json_encode($ratingFileData);?>;
                var isSetUserVoteToNull = <?php echo json_encode(true);?>;
                cgJsClass.gallery.rating.setRatingFiveStar(pictureID,0,0,false,galeryIDuser,false,false,ratingFileData,isFromSingleView,isSetUserVoteToNull);

            </script>
            <?php

            // simply create empty file for later check
            $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/frontend-added-votes/'.$pictureID.'-'.time().'.txt';
            $fp = fopen($jsonFile, 'w');
            fwrite($fp, '');
            fclose($fp);

            //cg_actualize_all_images_data_sort_values_file($galeryID,false,$IsModernFiveStar);

            return;

        }



    }
    if($VotesInTime == 1){

        $TstampToCompare = $Tstamp-$VotesInTimeIntervalSeconds;

        if($CheckLogin==1 && $wpUserId>0) {

            if (is_user_logged_in()) {
                $VotesUserInTstamp = $wpdb->get_var("SELECT COUNT(*) FROM $tablenameIP WHERE Tstamp > '$TstampToCompare' && WpUserId='$wpUserId' && GalleryID = '$galeryID' && Rating>='1'");
            }

        }else if($CheckCookie){
            if(isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting'])) {
                $VotesUserInTstamp = $wpdb->get_var( "SELECT COUNT(*) FROM $tablenameIP WHERE Tstamp > '$TstampToCompare' && CookieId='$CookieId' && GalleryID = '$galeryID' && Rating>='1'");
            }
        }else{
            $VotesUserInTstamp = $wpdb->get_var( "SELECT COUNT(*) FROM $tablenameIP WHERE Tstamp > '$TstampToCompare' && IP='$userIP' && GalleryID = '$galeryID' && Rating>='1'");
        }

        if(empty($VotesUserInTstamp)){
            $VotesUserInTstamp = 0;
        }

        if($VotesInTime == 1 && ($VotesUserInTstamp>=$VotesInTimeQuantity)){

            ?>
            <script data-cg-processing="true">

                var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
                var pictureID = <?php echo json_encode($pictureID);?>;
                var isFromSingleView = <?php echo json_encode($isFromSingleView);?>;
                var ratingFileData = <?php echo json_encode($ratingFileData);?>;

                cgJsClass.gallery.rating.setRatingFiveStar(pictureID,0,0,false,galeryIDuser,false,true,ratingFileData,isFromSingleView);


            </script>
            <?php

            return;

        }

    }

    // FIVE CASES HERE:
    // 1. One vote per picture
    // 2. All votes used
    // 3. One vote per Category.
    // 4. Votes per Category.
    // 5. No restrictions. Vote always.

    // ATTENTION!!! IpBlock means show only vote per Picture Configuration
    if (!empty($getRatingPicture) and $OneVotePerPicture==1){
        // One vote per picture case
        // Picture already rated!!!!

        /**###NORMAL###**/
        if(is_dir ($plugin_dir_path.'/../../../../../contest-gallery')){
            cg_update_to_pro_five_stars($galeryIDuser,$pictureID,$ratingFileData,$isFromSingleView,'Update to PRO version to use "One vote per picture" function');
            return true;
        }
        /**###NORMAL-END###**/

        ?>
        <script data-cg-processing="true">

            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
            var pictureID = <?php echo json_encode($pictureID);?>;
            var isFromSingleView = <?php echo json_encode($isFromSingleView);?>;
            var ratingFileData = <?php echo json_encode($ratingFileData);?>;
            cgJsData[galeryIDuser].options.pro.VoteMessageWarningActive = <?php echo json_encode($VoteMessageWarningActive);?>;
            cgJsData[galeryIDuser].options.pro.VoteMessageWarningText = <?php echo json_encode($VoteMessageWarningText);?>;

            cgJsClass.gallery.function.message.close(true);

            cgJsClass.gallery.rating.setRatingFiveStar(pictureID,0,0,false,galeryIDuser,false,false,ratingFileData,isFromSingleView,undefined,undefined,true);

            if(cgJsData[galeryIDuser].options.pro.VoteMessageWarningActive==1){
                cgJsClass.gallery.function.message.showPro(galeryIDuser,cgJsData[galeryIDuser].options.pro.VoteMessageWarningText);
            }else{
                if(cgJsData[galeryIDuser].vars.rawData[pictureID].MultipleFilesParsed){
                    cgJsClass.gallery.function.message.show(galeryIDuser,cgJsClass.gallery.language[galeryIDuser].YouHaveAlreadyVotedThisPicture);
                }else{
                    cgJsClass.gallery.function.message.show(galeryIDuser,cgJsClass.gallery.language[galeryIDuser].YouHaveAlreadyVotedThisPicture);
            }
            }

        </script>
        <?php

    } else if (($countVotesOfUserPerGallery >= $VotesPerUser) && ($VotesPerUser!=0)){
        // All votes used case

        /**###NORMAL###**/
        if(is_dir ($plugin_dir_path.'/../../../../../contest-gallery')){
            cg_update_to_pro_five_stars($galeryIDuser,$pictureID,$ratingFileData,$isFromSingleView,'Update to PRO version to use "Votes per user" function');
            return true;
        }
        /**###NORMAL-END###**/

        ?>
        <script data-cg-processing="true">

            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
            var pictureID = <?php echo json_encode($pictureID);?>;
            var isFromSingleView = <?php echo json_encode($isFromSingleView);?>;
            var ratingFileData = <?php echo json_encode($ratingFileData);?>;
            cgJsData[galeryIDuser].options.pro.VoteMessageWarningActive = <?php echo json_encode($VoteMessageWarningActive);?>;
            cgJsData[galeryIDuser].options.pro.VoteMessageWarningText = <?php echo json_encode($VoteMessageWarningText);?>;

            cgJsClass.gallery.function.message.close(true);

            cgJsClass.gallery.rating.setRatingFiveStar(pictureID,0,0,false,galeryIDuser,true,false,ratingFileData,isFromSingleView,undefined,undefined,true);

            if(cgJsData[galeryIDuser].options.pro.VoteMessageWarningActive==1){
                cgJsClass.gallery.function.message.showPro(galeryIDuser,cgJsData[galeryIDuser].options.pro.VoteMessageWarningText);
            }else if(cgJsData[galeryIDuser].vars.language.pro.VotesPerUserAllVotesUsedHtmlMessage){
                cgJsClass.gallery.function.message.showPro(galeryIDuser,cgJsData[galeryIDuser].vars.language.pro.VotesPerUserAllVotesUsedHtmlMessage);
            }else{
                cgJsClass.gallery.function.message.show(galeryIDuser,cgJsClass.gallery.language[galeryIDuser].AllVotesUsed);
            }

            cgJsData[galeryIDuser].vars.allVotesUsed = 1;

        </script>
        <?php

    } else if (($countVotesOfUserPerCategory >= 1) && (!empty($VotePerCategory))){
        // One vote per category

        ?>
        <script data-cg-processing="true">

            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
            var pictureID = <?php echo json_encode($pictureID);?>;
            var isFromSingleView = <?php echo json_encode($isFromSingleView);?>;
            var ratingFileData = <?php echo json_encode($ratingFileData);?>;
            cgJsData[galeryIDuser].options.pro.VoteMessageWarningActive = <?php echo json_encode($VoteMessageWarningActive);?>;
            cgJsData[galeryIDuser].options.pro.VoteMessageWarningText = <?php echo json_encode($VoteMessageWarningText);?>;

            cgJsClass.gallery.function.message.close(true);

            cgJsClass.gallery.rating.setRatingFiveStar(pictureID,0,0,false,galeryIDuser,true,false,ratingFileData,isFromSingleView,undefined,undefined,true);

            if(cgJsData[galeryIDuser].options.pro.VoteMessageWarningActive==1){
                cgJsClass.gallery.function.message.showPro(galeryIDuser,cgJsData[galeryIDuser].options.pro.VoteMessageWarningText);
            }else{
                cgJsClass.gallery.function.message.show(galeryIDuser,cgJsClass.gallery.language[galeryIDuser].YouHaveAlreadyVotedThisCategory);
            }
           // cgJsData[gid].vars.allVotesUsed = 1;

        </script>
        <?php

    } else if (($countVotesOfUserPerCategory >= $VotesPerCategory) && (!empty($VotesPerCategory))){
        // Votes per category

        /**###NORMAL###**/
        if(is_dir ($plugin_dir_path.'/../../../../../contest-gallery')){
            cg_update_to_pro_five_stars($galeryIDuser,$pictureID,$ratingFileData,$isFromSingleView,'Update to PRO version to use "Votes per category" function');
            return true;
        }
        /**###NORMAL-END###**/

        ?>
        <script data-cg-processing="true">

            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
            var pictureID = <?php echo json_encode($pictureID);?>;
            var isFromSingleView = <?php echo json_encode($isFromSingleView);?>;
            var ratingFileData = <?php echo json_encode($ratingFileData);?>;
            cgJsData[galeryIDuser].options.pro.VoteMessageWarningActive = <?php echo json_encode($VoteMessageWarningActive);?>;
            cgJsData[galeryIDuser].options.pro.VoteMessageWarningText = <?php echo json_encode($VoteMessageWarningText);?>;

            cgJsClass.gallery.function.message.close(true);

            cgJsClass.gallery.rating.setRatingFiveStar(pictureID,0,0,false,galeryIDuser,true,false,ratingFileData,isFromSingleView,undefined,undefined,true);

            if(cgJsData[galeryIDuser].options.pro.VoteMessageWarningActive==1){
                cgJsClass.gallery.function.message.showPro(galeryIDuser,cgJsData[galeryIDuser].options.pro.VoteMessageWarningText);
            }else{
                cgJsClass.gallery.function.message.show(galeryIDuser,cgJsClass.gallery.language[galeryIDuser].YouHaveNoMoreVotesInThisCategory);
            }
           // cgJsData[gid].vars.allVotesUsed = 1;

        </script>
        <?php

    }
    else{

        // KANN NUR EINTRETEN WENN DIE OPTIONS GERADE GEÄNDERT WURDEN UND KEIN SEITENRELOAD STATTFAND
        // ES SOLL NICHT VERARBEITET WERDEN WEIL ES SEIN KÖNNTE DAS COOKIES BEIM NUTZER GAR NICHT ERLAUBT WAREN,
        if($CheckCookie==1) {
            if(!isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting'])) {
                //  return;
                $cookieVotingJustActivated = true;
            }
        }

        if($cookieVotingJustActivated == false){

                // vote done!!! Save and forward
            $VoteDate = date('d-M-Y H:i:s', $Tstamp);

            // speichern in der IP Tabelle
            $wpdb->query( $wpdb->prepare(
                "
                        INSERT INTO $tablenameIP
                        ( id, IP, GalleryID, pid, Rating, RatingS,WpUserId,VoteDate,Tstamp,OptionSet,CookieId,Category,CategoriesOn)
                        VALUES ( %s,%s,%d,%d,%d,%d,%d,%s,%d,%s,%s,%d,%d )
                    ",
                '',$userIP,$galeryID,$pictureID,$rateValue,0,$wpUserId,$VoteDate,$Tstamp,$OptionSet,$CookieId,$ratingFileData['Category'],$CategoriesOn
            ) );

            // speichern in der Haupttabelle und im File

            $countR = intval($ratingFileData['CountR'])+1;
            $rating = intval($ratingFileData['Rating'])+intval($rateValue);

            if($IsModernFiveStar==1){
                $countRtype = 'CountR'.$rateValue;
                $countRtoAdd = intval($ratingFileData['CountR'.$rateValue])+1;
                // update will still be done in case but not really used
                // since 13.02.2022 always dynamic values from tablename_ip will be get
               $wpdb->update(
                    "$tablename",
                    array('CountR' => $countR,'Rating' => $rating,$countRtype => $countRtoAdd),
                    array('id' => $pictureID),
                    array('%d','%d','%d'),
                    array('%d')
                );
            }else{
                $wpdb->update(
                    "$tablename",
                    array('CountR' => $countR,'Rating' => $rating),
                    array('id' => $pictureID),
                    array('%d','%d'),
                    array('%d')
                );
            }

            $fp = fopen($jsonFile, 'w');
            $ratingFileData['CountR'] = intval($countR);
            $ratingFileData['Rating'] = intval($rating);

            if($IsModernFiveStar==1){
                $ratingFileData['CountR'.$rateValue] = $countRtoAdd;
            }

            fwrite($fp,json_encode($ratingFileData));
            fclose($fp);


        }


        ?>
        <script data-cg-processing="true">

            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
            var pictureID = <?php echo json_encode($pictureID);?>;
            var ratingFileData = <?php echo json_encode($ratingFileData);?>;
            var cookieVotingJustActivated = <?php echo json_encode($cookieVotingJustActivated);?>;
            var ThankVote = <?php echo json_encode($ThankVote);?>;
            var isFromSingleView = <?php echo json_encode($isFromSingleView);?>;
            cgJsData[galeryIDuser].options.pro.VoteMessageSuccessActive = <?php echo json_encode($VoteMessageSuccessActive);?>;
            cgJsData[galeryIDuser].options.pro.VoteMessageSuccessText = <?php echo json_encode($VoteMessageSuccessText);?>;

             if(!cookieVotingJustActivated){
                var rateValue = <?php echo json_encode($rateValue);?>;

                 var isSomeMessageWillBeShown = undefined;
                 if(ThankVote==1){
                     isSomeMessageWillBeShown = true;
                 }

                 cgJsClass.gallery.function.message.close(true);

                 cgJsData[galeryIDuser].lastVotedUserImageId = pictureID;
                cgJsClass.gallery.rating.setRatingFiveStar(pictureID,1,parseInt(rateValue),false,galeryIDuser,false,false,ratingFileData,isFromSingleView,undefined,undefined,isSomeMessageWillBeShown);


                 if(cgJsData[galeryIDuser].options.pro.VoteMessageSuccessActive==1){
                    cgJsClass.gallery.function.message.showPro(galeryIDuser,cgJsData[galeryIDuser].options.pro.VoteMessageSuccessText);
                }else{

                     if(ThankVote==1){
                        cgJsClass.gallery.function.message.show(galeryIDuser,cgJsClass.gallery.language[galeryIDuser].ThankYouForVoting);
                    }

                }

            }else{
                 cgJsClass.gallery.function.message.close(true);
                 // isSomeMessageWillBeShown always set in that case then!
                cgJsClass.gallery.rating.setRatingFiveStar(pictureID,0,0,false,galeryIDuser,false,false,ratingFileData,isFromSingleView,undefined,undefined,true);
                cgJsClass.gallery.function.message.show(galeryIDuser,'Check Cookie voting activated');
            }

        </script>
        <?php

        // simply create empty file for later check
        $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/frontend-added-votes/'.$pictureID.'-'.time().'.txt';
        $fp = fopen($jsonFile, 'w');
        fwrite($fp, '');
        fclose($fp);

        if($cookieVotingJustActivated == false){
           // cg_actualize_all_images_data_sort_values_file($galeryID,false,$IsModernFiveStar);
        }

    }

}


?>