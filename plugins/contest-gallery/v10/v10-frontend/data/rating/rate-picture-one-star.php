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

/*error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set('error_reporting', E_ALL);*/
//$testLala = $_POST['lala'];

//------------------------------------------------------------
// ----------------------------------------------------------- Bilder bewerten ----------------------------------------------------------
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
fclose($fp);
$optionsSource = $options;
if(!empty($options[$galeryID])){
    $options = $options[$galeryID];
}

$jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/image-data/image-data-'.$pictureID.'.json';
$fp = fopen($jsonFile, 'r');
$ratingFileData =json_decode(fread($fp,filesize($jsonFile)),true);
fclose($fp);

if (($rateValue>5 or $rateValue<1) or ($galleryHash != $galleryHashToCompare)){
    ?>
    <script data-cg-processing="true">

        var message = <?php echo json_encode('Please do not manipulate!');?>;
        var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
        var pictureID = <?php echo json_encode($pictureID);?>;
        var ratingFileData = <?php echo json_encode($ratingFileData);?>;

        cgJsClass.gallery.rating.setRatingOneStar(pictureID,0,false,galeryIDuser,false,false,ratingFileData);
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
            var ratingFileData = <?php echo json_encode($ratingFileData);?>;
            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
            var pictureID = <?php echo json_encode($pictureID);?>;
            cgJsClass.gallery.rating.setRatingOneStar(pictureID,0,false,galeryIDuser,false,false,ratingFileData);
            cgJsClass.gallery.function.message.show(galeryIDuser,cgJsClass.gallery.language[galeryIDuser].YouCanNotVoteInOwnGallery);

        </script>
        <?php

        return;
    }
  //  if($explodeHash[1]==$galeryID.'-nv'){
    if(strpos($explodeHash[1],$galeryID.'-nv')!==false){
            ?>
        <script data-cg-processing="true">
            var ratingFileData = <?php echo json_encode($ratingFileData);?>;
            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
            var pictureID = <?php echo json_encode($pictureID);?>;
            cgJsClass.gallery.rating.setRatingOneStar(pictureID,0,false,galeryIDuser,false,false,ratingFileData);

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
            var pictureID = <?php echo json_encode($pictureID);?>;
            cgJsClass.gallery.rating.setRatingOneStar(pictureID,0,false,galeryIDuser,false,false,ratingFileData);
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
    $VotesPerUser = intval($options['general']['VotesPerUser']);
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
    $countVotesOfUserPerGallery = 0;

    // requires after updating 12.3.0
    $VoteMessageSuccessActive = (!empty($options['pro']['VoteMessageSuccessActive'])) ? 1 : 0;
    $VoteMessageSuccessText = (!empty($options['pro']['VoteMessageSuccessText'])) ? $options['pro']['VoteMessageSuccessText'] : '';
    $VoteMessageWarningActive = (!empty($options['pro']['VoteMessageWarningActive'])) ? 1 : 0;
    $VoteMessageWarningText = (!empty($options['pro']['VoteMessageWarningText'])) ? $options['pro']['VoteMessageWarningText'] : '';

    $CategoriesOn = 0;// means that it was visible for user in that time that he votes for this category

    if(!empty($categoriesArray)){
        $CategoriesOn = 1;
    }

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

    if($CheckLogin==1 && $wpUserId==0){
        ?>
        <script data-cg-processing="true">

            var ContestStartTimeFromPhp = <?php echo json_encode($ContestStartTime);?>;
            var ContestStart = <?php echo json_encode($ContestStart);?>;
            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
            var pictureID = <?php echo json_encode($pictureID);?>;
            var ratingFileData = <?php echo json_encode($ratingFileData);?>;
            cgJsClass.gallery.rating.setRatingOneStar(pictureID,0,false,galeryIDuser,false,false,ratingFileData);

        </script>
        <?php
        return;
    }

    $time = time();

    if($time < $ContestStartTime && $ContestStart==1){
        ?>
        <script data-cg-processing="true">

            var ActualTimeSecondsFromPhp = <?php echo json_encode($time);?>;
            var ContestStartTimeFromPhp = <?php echo json_encode($ContestStartTime);?>;
            var ContestStart = <?php echo json_encode($ContestStart);?>;
            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
            var pictureID = <?php echo json_encode($pictureID);?>;
            var ratingFileData = <?php echo json_encode($ratingFileData);?>;


            cgJsClass.gallery.function.general.time.photoContestStartTimeCheck(galeryIDuser,ActualTimeSecondsFromPhp,ContestStartTimeFromPhp,ContestStart);
            cgJsClass.gallery.rating.setRatingOneStar(pictureID,0,false,galeryIDuser,false,false,ratingFileData);

        </script>
        <?php

        return;
    }

    if(($time>=$ContestEndTime && $ContestEnd==1) OR $ContestEnd==2){
        $ContestEnd = 2;// photo contest will be ended this way
        ?>
        <script data-cg-processing="true">

            var ActualTimeSecondsFromPhp = <?php echo json_encode($time);?>;
            var ContestEndTimeFromPhp = <?php echo json_encode($ContestEndTime);?>;
            var ContestEnd = <?php echo json_encode($ContestEnd);?>;
            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
            var pictureID = <?php echo json_encode($pictureID);?>;
            var ratingFileData = <?php echo json_encode($ratingFileData);?>;

            cgJsClass.gallery.function.general.time.photoContestEndTimeCheck(galeryIDuser,ActualTimeSecondsFromPhp,ContestEndTimeFromPhp,ContestEnd);
            cgJsClass.gallery.rating.setRatingOneStar(pictureID,0,false,galeryIDuser,false,false,ratingFileData);

        </script>
        <?php

        return;
    }


    if((time()>=$ContestEndTime && $ContestEnd==1) OR $ContestEnd==2){
        $ContestEnd = 2;// photo contest will be ended this way
        ?>
        <script data-cg-processing="true">

            var ContestEndTimeFromPhp = <?php echo json_encode($ContestEndTime);?>;
            var ContestEnd = <?php echo json_encode($ContestEnd);?>;
            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
            var pictureID = <?php echo json_encode($pictureID);?>;
            var ratingFileData = <?php echo json_encode($ratingFileData);?>;

            cgJsClass.gallery.function.general.time.photoContestEndTimeCheck(galeryIDuser,ContestEndTimeFromPhp,ContestEnd);
            cgJsClass.gallery.rating.setRatingOneStar(pictureID,0,false,galeryIDuser,false,false,ratingFileData);

        </script>
        <?php

        return;
    }

    $userIP = sanitize_text_field(cg_get_user_ip());
    if(empty($userIP) OR $userIP == 'unknown'){
        ?>
        <script data-cg-processing="true">
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

                cgJsClass.gallery.rating.setRatingOneStar(pictureID,0,false,galeryIDuser,true);
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

                var ContestEndTimeFromPhp = <?php echo json_encode($ContestEndTime);?>;
                var ContestEnd = <?php echo json_encode($ContestEnd);?>;
                var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
                var pictureID = <?php echo json_encode($pictureID);?>;
                var CheckCookieAlertMessage = <?php echo json_encode($CheckCookieAlertMessage);?>;
                var ratingFileData = <?php echo json_encode($ratingFileData);?>;

                cgJsClass.gallery.function.message.show(galeryIDuser,CheckCookieAlertMessage);
                    cgJsClass.gallery.rating.setRatingOneStar(pictureID,0,false,galeryIDuser,false,false,ratingFileData);
                    cgJsClass.gallery.vars.cookiesNotAllowed = true;

                // cookie value will be get via getCookie before request
                //var cookieValue = <?php //echo json_encode($cookieValue);?>;
                //cgJsClass.gallery.vars.cookieValue = cookieValue;

            </script>

            <?php

        }
    }


    $getRatingPicture = 0;
    $countVotesOfUserPerGallery = 0;
    $CookieId = '';

    if(!empty($_COOKIE['contest-gal1ery-'.$galeryID.'-voting']) && $options['general']['CheckCookie'] == 1) {
        $CookieId = $_COOKIE['contest-gal1ery-'.$galeryID.'-voting'];
    }

    // Prüfen ob ein bestimmtes Bild von dem User bewertet wurde
    if ($CheckLogin == 1 && $wpUserId>0)
    {
        if(is_user_logged_in()){
            $getRatingPicture = $wpdb->get_var( $wpdb->prepare(
                "
			SELECT COUNT(*) AS NumberOfRows
			FROM $tablenameIP 
			WHERE pid = %d and GalleryID = %d and WpUserId = %s and RatingS = %s
		",
                $pictureID,$galeryID,$wpUserId,1
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
        WHERE pid = %d and GalleryID = %d and CookieId = %s and RatingS = %s
    ",
                $pictureID,$galeryID,$CookieId,1
            ) );
        }

    }
    else if ($CheckIp == 1 && $CheckCookie != 1){
        $getRatingPicture = $wpdb->get_var( $wpdb->prepare(
            "
            SELECT COUNT(*) AS NumberOfRows
            FROM $tablenameIP 
            WHERE pid = %d and GalleryID = %d and IP = %s and RatingS = %s
        ",
            $pictureID,$galeryID,$userIP,1
        ) );
    } else if ($CheckIp == 1 && $CheckCookie == 1){

        if(isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting'])) {
            $getRatingPicture = $wpdb->get_var( $wpdb->prepare(
                "
        SELECT COUNT(*) AS NumberOfRows
        FROM $tablenameIP 
        WHERE (pid = %d and GalleryID = %d and IP = %s and RatingS = %s) OR (pid = %d and GalleryID = %d and CookieId = %s and RatingS = %s)
    ",
                $pictureID,$galeryID,$userIP,1,$pictureID,$galeryID,$CookieId,1
            ) );
        } else{
            $getRatingPicture = $wpdb->get_var( $wpdb->prepare(
                "
            SELECT COUNT(*) AS NumberOfRows
            FROM $tablenameIP 
            WHERE pid = %d and GalleryID = %d and IP = %s and RatingS = %s
        ",
                $pictureID,$galeryID,$userIP,1
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
                    WHERE GalleryID = %d and WpUserId = %s and RatingS > %d
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
                    WHERE GalleryID = %d and CookieId = %s and RatingS > %d
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
						WHERE GalleryID = %d and IP = %s and RatingS = %d
					",
                $galeryID,$userIP,1
            ) );
        }
        else if ($CheckIp == 1 && $CheckCookie==1) {

            if(isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting'])) {
                $countVotesOfUserPerGallery = $wpdb->get_var( $wpdb->prepare(
                    "
                    SELECT COUNT(*) AS NumberOfRows
                    FROM $tablenameIP
                    WHERE (GalleryID = %d and IP = %s and RatingS = %d) OR (GalleryID = %d and CookieId = %s and RatingS > %d)
                ",
                    $galeryID,$userIP,1,$galeryID,$CookieId,0
                ) );
            }else{
                $countVotesOfUserPerGallery = $wpdb->get_var( $wpdb->prepare(
                    "
						SELECT COUNT(*) AS NumberOfRows
						FROM $tablenameIP 
						WHERE GalleryID = %d and IP = %s and RatingS = %d
					",
                    $galeryID,$userIP,1
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
                    WHERE GalleryID = %d and WpUserId = %s and RatingS > %d and Category = %d and CategoriesOn = %d
                ",
                    $galeryID,$wpUserId,0,$ratingFileData['Category'],1
                ) );
            }

        }
        else if ($CheckCookie == 1 && $CheckIp != 1)
        {
            if(isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting'])) {
                $countVotesOfUserPerCategory = $wpdb->get_var( $wpdb->prepare(
                    "
                    SELECT COUNT(*) AS NumberOfRows
                    FROM $tablenameIP 
                    WHERE GalleryID = %d and CookieId = %s and RatingS > %d and Category = %d and CategoriesOn = %d
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
						WHERE GalleryID = %d and IP = %s and RatingS = %d and Category = %d and CategoriesOn = %d
					",
                $galeryID,$userIP,1,$ratingFileData['Category'],1
            ) );
        }
        else if ($CheckIp == 1 && $CheckCookie==1) {
            if(isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting'])) {
                $countVotesOfUserPerCategory = $wpdb->get_var( $wpdb->prepare(
                    "
						SELECT COUNT(*) AS NumberOfRows
						FROM $tablenameIP 
						WHERE (GalleryID = %d and IP = %s and RatingS = %d and Category = %d and CategoriesOn = %d) OR (GalleryID = %d and CookieId = %s and RatingS > %d and Category = %d and CategoriesOn = %d)
					",
                    $galeryID,$userIP,1,$ratingFileData['Category'],1,$galeryID,$CookieId,0,$ratingFileData['Category'],1
                ) );
            }else{
                $countVotesOfUserPerCategory = $wpdb->get_var( $wpdb->prepare(
                    "
						SELECT COUNT(*) AS NumberOfRows
						FROM $tablenameIP 
						WHERE GalleryID = %d and IP = %s and RatingS = %d and Category = %d and CategoriesOn = %d
					",
                    $galeryID,$userIP,1,$ratingFileData['Category'],1
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
            cg_update_to_pro_one_star($galeryIDuser,$pictureID,$ratingFileData,'Update to PRO version to use "One vote per picture" function');
            return true;
        }
        /**###NORMAL-END###**/

        $lastVotedIpId = 0;

        if ($CheckLogin == 1 && $wpUserId>0){
            if(is_user_logged_in()){
                $lastVotedIpId = $wpdb->get_var( "SELECT id FROM $tablenameIP WHERE RatingS = '1' && WpUserId = '$wpUserId' && GalleryID = '$galeryID' && pid = '$pictureID' ORDER BY id DESC LIMIT 1" );
                $countUserVotesForImage = $wpdb->get_var( "SELECT COUNT(*) AS NumberOfRows FROM $tablenameIP WHERE RatingS = '1' && WpUserId = '$wpUserId' && GalleryID = '$galeryID' && pid = '$pictureID'" );
            }
        }
        else if ($CheckCookie == 1 && $CheckIp != 1){
            if(isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting'])) {
                $lastVotedIpId = $wpdb->get_var( "SELECT id FROM $tablenameIP WHERE RatingS = '1' && CookieId = '$CookieId' && GalleryID = '$galeryID' && pid = '$pictureID' ORDER BY id DESC LIMIT 1" );
                $countUserVotesForImage = $wpdb->get_var( "SELECT COUNT(*) AS NumberOfRows FROM $tablenameIP WHERE RatingS = '1' && CookieId = '$CookieId' && GalleryID = '$galeryID' && pid = '$pictureID'" );
            }
        }
        else if ($CheckIp == 1 && $CheckCookie!=1) {
            $lastVotedIpId = $wpdb->get_var( "SELECT id FROM $tablenameIP WHERE RatingS = '1' && IP = '$userIP' && GalleryID = '$galeryID' && pid = '$pictureID' ORDER BY id DESC LIMIT 1" );
            $countUserVotesForImage = $wpdb->get_var( "SELECT COUNT(*) AS NumberOfRows FROM $tablenameIP WHERE RatingS = '1' && IP = '$userIP' && GalleryID = '$galeryID' && pid = '$pictureID'" );
        }
        else if ($CheckIp == 1 && $CheckCookie==1) {
            if(isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting'])) {
                $lastVotedIpId = $wpdb->get_var( "SELECT id FROM $tablenameIP WHERE  (RatingS = '1' && IP = '$userIP' && GalleryID = '$galeryID' && pid = '$pictureID') OR (RatingS = '1' && CookieId = '$CookieId' && GalleryID = '$galeryID' && pid = '$pictureID') ORDER BY id DESC LIMIT 1" );
                $countUserVotesForImage = $wpdb->get_var( "SELECT COUNT(*) AS NumberOfRows FROM $tablenameIP WHERE (RatingS = '1' && IP = '$userIP' && GalleryID = '$galeryID' && pid = '$pictureID') OR (RatingS = '1' && CookieId = '$CookieId' && GalleryID = '$galeryID' && pid = '$pictureID')" );
            }else{
                $lastVotedIpId = $wpdb->get_var( "SELECT id FROM $tablenameIP WHERE RatingS = '1' && IP = '$userIP' && GalleryID = '$galeryID' && pid = '$pictureID' ORDER BY id DESC LIMIT 1" );
                $countUserVotesForImage = $wpdb->get_var( "SELECT COUNT(*) AS NumberOfRows FROM $tablenameIP WHERE RatingS = '1' && IP = '$userIP' && GalleryID = '$galeryID' && pid = '$pictureID'" );
            }
       }

        if(!empty($lastVotedIpId)){

            $wpdb->delete( $tablenameIP, array( 'id' => $lastVotedIpId ), array( '%d' ) );

            $countS = intval($ratingFileData['CountS'])-1;
            $ratingFileData['CountS'] = $countS;

            $fp = fopen($jsonFile, 'w');
            fwrite($fp,json_encode($ratingFileData));
            fclose($fp);

            // update main table
            $wpdb->update(
                "$tablename",
                array('CountS' => $countS),
                array('id' => $pictureID),
                array('%d'),
                array('%d')
            );

            $isSetUserVoteToNull = false;
            if(empty($countUserVotesForImage)){
                $isSetUserVoteToNull = true;
            }

            ?>
            <script data-cg-processing="true">

                var ContestEndTimeFromPhp = <?php echo json_encode($ContestEndTime);?>;
                var ContestEnd = <?php echo json_encode($ContestEnd);?>;
                var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
                var pictureID = <?php echo json_encode($pictureID);?>;
                var ratingFileData = <?php echo json_encode($ratingFileData);?>;
                var isSetUserVoteToNull = <?php echo json_encode($isSetUserVoteToNull);?>;

                cgJsClass.gallery.rating.setRatingOneStar(pictureID,-1,false,galeryIDuser,false,false,ratingFileData,isSetUserVoteToNull);

                cgJsData[galeryIDuser].vars.allVotesUsed = 0;

            </script>
            <?php

            // simply create empty file for later check
            $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/frontend-added-votes/'.$pictureID.'-'.time().'.txt';
            $fp = fopen($jsonFile, 'w');
            fwrite($fp, '');
            fclose($fp);

            //cg_actualize_all_images_data_sort_values_file($galeryID);

            return;


        }else{


            ?>
            <script data-cg-processing="true">

                var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
                var pictureID = <?php echo json_encode($pictureID);?>;
                var ratingFileData = <?php echo json_encode($ratingFileData);?>;
                var isSetUserVoteToNull = <?php echo json_encode(true);?>;

                cgJsClass.gallery.rating.setRatingOneStar(pictureID,0,false,galeryIDuser,false,false,ratingFileData,isSetUserVoteToNull);


            </script>
            <?php

            // simply create empty file for later check
            $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/frontend-added-votes/'.$pictureID.'-'.time().'.txt';
            $fp = fopen($jsonFile, 'w');
            fwrite($fp, '');
            fclose($fp);

            //cg_actualize_all_images_data_sort_values_file($galeryID);

            return;


        }

    }

    if($VotesInTime == 1){

        $TstampToCompare = $Tstamp-$VotesInTimeIntervalSeconds;

        if($CheckLogin==1  && $wpUserId>0){
            if(is_user_logged_in()){
                $VotesUserInTstamp = $wpdb->get_var( "SELECT COUNT(*) FROM $tablenameIP WHERE Tstamp > '$TstampToCompare' && WpUserId='$wpUserId' && GalleryID = '$galeryID' && RatingS='1'");
            }
        }
        else if($CheckCookie){
            if(isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting'])) {
                $VotesUserInTstamp = $wpdb->get_var( "SELECT COUNT(*) FROM $tablenameIP WHERE Tstamp > '$TstampToCompare' && CookieId='$CookieId' && GalleryID = '$galeryID' && RatingS='1'");
            }
        }else{
            $VotesUserInTstamp = $wpdb->get_var( "SELECT COUNT(*) FROM $tablenameIP WHERE Tstamp > '$TstampToCompare' && IP='$userIP' && GalleryID = '$galeryID' && RatingS='1'");
        }

        if($VotesInTime == 1 && ($VotesUserInTstamp>=$VotesInTimeQuantity)){

            ?>
            <script data-cg-processing="true">

                var ContestEndTimeFromPhp = <?php echo json_encode($ContestEndTime);?>;
                var ContestEnd = <?php echo json_encode($ContestEnd);?>;
                var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
                var pictureID = <?php echo json_encode($pictureID);?>;
                var ratingFileData = <?php echo json_encode($ratingFileData);?>;

                cgJsClass.gallery.rating.setRatingOneStar(pictureID,0,false,galeryIDuser,false,true,ratingFileData);

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
            cg_update_to_pro_one_star($galeryIDuser,$pictureID,$ratingFileData,'Update to PRO version to use "One vote per picture" function');
            return true;
        }
        /**###NORMAL-END###**/

        ?>
        <script data-cg-processing="true">

            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
            var pictureID = <?php echo json_encode($pictureID);?>;
            var ratingFileData = <?php echo json_encode($ratingFileData);?>;
            cgJsData[galeryIDuser].options.pro.VoteMessageWarningActive = <?php echo json_encode($VoteMessageWarningActive);?>;
            cgJsData[galeryIDuser].options.pro.VoteMessageWarningText = <?php echo json_encode($VoteMessageWarningText);?>;

            cgJsClass.gallery.rating.setRatingOneStar(pictureID,0,false,galeryIDuser,false,false,ratingFileData);

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

    }
    else if (($countVotesOfUserPerGallery >= $VotesPerUser) && ($VotesPerUser!=0)){

        // All votes used case

        /**###NORMAL###**/
        if(is_dir ($plugin_dir_path.'/../../../../../contest-gallery')){
            cg_update_to_pro_one_star($galeryIDuser,$pictureID,$ratingFileData,'Update to PRO version to use "Votes per user" function');
            return true;
        }
        /**###NORMAL-END###**/

        ?>
        <script data-cg-processing="true">

            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
            var pictureID = <?php echo json_encode($pictureID);?>;
            var ratingFileData = <?php echo json_encode($ratingFileData);?>;
            cgJsData[galeryIDuser].options.pro.VoteMessageWarningActive = <?php echo json_encode($VoteMessageWarningActive);?>;
            cgJsData[galeryIDuser].options.pro.VoteMessageWarningText = <?php echo json_encode($VoteMessageWarningText);?>;

            cgJsClass.gallery.rating.setRatingOneStar(pictureID,0,false,galeryIDuser,true,false,ratingFileData);

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
            var ratingFileData = <?php echo json_encode($ratingFileData);?>;
            cgJsData[galeryIDuser].options.pro.VoteMessageWarningActive = <?php echo json_encode($VoteMessageWarningActive);?>;
            cgJsData[galeryIDuser].options.pro.VoteMessageWarningText = <?php echo json_encode($VoteMessageWarningText);?>;

            cgJsClass.gallery.rating.setRatingOneStar(pictureID,0,false,galeryIDuser,true,false,ratingFileData);

            if(cgJsData[galeryIDuser].options.pro.VoteMessageWarningActive==1){
                cgJsClass.gallery.function.message.showPro(galeryIDuser,cgJsData[galeryIDuser].options.pro.VoteMessageWarningText);
            }else{
                cgJsClass.gallery.function.message.show(galeryIDuser,cgJsClass.gallery.language[galeryIDuser].YouHaveAlreadyVotedThisCategory);
            }
            //    cgJsData[galeryIDuser].vars.allVotesUsed = 1;

        </script>
        <?php

    } else if (($countVotesOfUserPerCategory >= $VotesPerCategory) && (!empty($VotesPerCategory))){

        // Votes per category

        /**###NORMAL###**/
        if(is_dir ($plugin_dir_path.'/../../../../../contest-gallery')){
            cg_update_to_pro_one_star($galeryIDuser,$pictureID,$ratingFileData,'Update to PRO version to use "Votes per category" function');
            return true;
        }
        /**###NORMAL-END###**/

        ?>
        <script data-cg-processing="true">

            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
            var pictureID = <?php echo json_encode($pictureID);?>;
            var ratingFileData = <?php echo json_encode($ratingFileData);?>;
            cgJsData[galeryIDuser].options.pro.VoteMessageWarningActive = <?php echo json_encode($VoteMessageWarningActive);?>;
            cgJsData[galeryIDuser].options.pro.VoteMessageWarningText = <?php echo json_encode($VoteMessageWarningText);?>;

            cgJsClass.gallery.rating.setRatingOneStar(pictureID,0,false,galeryIDuser,true,false,ratingFileData);

            if(cgJsData[galeryIDuser].options.pro.VoteMessageWarningActive==1){
                cgJsClass.gallery.function.message.showPro(galeryIDuser,cgJsData[galeryIDuser].options.pro.VoteMessageWarningText);
            }else{
                cgJsClass.gallery.function.message.show(galeryIDuser,cgJsClass.gallery.language[galeryIDuser].YouHaveNoMoreVotesInThisCategory);
            }
            //    cgJsData[galeryIDuser].vars.allVotesUsed = 1;

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
            //if(!($MinusVote == 1 && $minusVoteNow)){
            $countS = intval($ratingFileData['CountS'])+1;
            // }

            $VoteDate = date('d-M-Y H:i:s', $Tstamp);

            // insert in tableIP
            $wpdb->query( $wpdb->prepare(
                "
					INSERT INTO $tablenameIP
					( id, IP, GalleryID, pid, Rating, RatingS,WpUserId,VoteDate,Tstamp,OptionSet,CookieId,Category,CategoriesOn)
					VALUES ( %s,%s,%d,%d,%d,%d,%d,%s,%d,%s,%s,%d,%d )
				",
                '',$userIP,$galeryID,$pictureID,0,1,$wpUserId,$VoteDate,$Tstamp,$OptionSet,$CookieId,$ratingFileData['Category'],$CategoriesOn
            ) );

            // update will still be done in case but not really used
            // since 13.02.2022 always dynamic values from tablename_ip will be get
            $wpdb->update(
                "$tablename",
                array('CountS' => $countS),
                array('id' => $pictureID),
                array('%d'),
                array('%d')
            );

            $fp = fopen($jsonFile, 'w');
            $ratingFileData['CountS'] = intval($countS);
            fwrite($fp,json_encode($ratingFileData));
            fclose($fp);

        }

        ?>
        <script data-cg-processing="true">

            var ContestEndTimeFromPhp = <?php echo json_encode($ContestEndTime);?>;
            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
            var ContestEnd = <?php echo json_encode($ContestEnd);?>;
            var pictureID = <?php echo json_encode($pictureID);?>;
            var ThankVote = <?php echo json_encode($ThankVote);?>;
            var cookieVotingJustActivated = <?php echo json_encode($cookieVotingJustActivated);?>;
            var ratingFileData = <?php echo json_encode($ratingFileData);?>;
            cgJsData[galeryIDuser].options.pro.VoteMessageSuccessActive = <?php echo json_encode($VoteMessageSuccessActive);?>;
            cgJsData[galeryIDuser].options.pro.VoteMessageSuccessText = <?php echo json_encode($VoteMessageSuccessText);?>;

            if(!cookieVotingJustActivated){

                var allVotesUsed = <?php echo json_encode($allVotesUsed);?>;

                cgJsData[galeryIDuser].lastVotedUserImageId = pictureID;
                cgJsClass.gallery.rating.setRatingOneStar(pictureID,1,false,galeryIDuser,false,false,ratingFileData);

                if(cgJsData[galeryIDuser].options.pro.VoteMessageSuccessActive==1){
                    cgJsClass.gallery.function.message.showPro(galeryIDuser,cgJsData[galeryIDuser].options.pro.VoteMessageSuccessText);
                }else{
                    if(ThankVote==1){
                        cgJsClass.gallery.function.message.show(galeryIDuser,cgJsClass.gallery.language[galeryIDuser].ThankYouForVoting);
                    }
                }

            }else{
                cgJsClass.gallery.rating.setRatingOneStar(pictureID,0,false,galeryIDuser);
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
           // cg_actualize_all_images_data_sort_values_file($galeryID);
        }


    }

}


?>