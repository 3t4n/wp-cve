<?php
if(!defined('ABSPATH')){exit;}

global $wpdb;
global $post;
global $cgEntryId;
global $galeryIDset;
global $galeryIDuset;
global $galeryIDnvset;
global $galeryIDwset;
global $galeryIDextender;
global $galeryIDuextender;
global $galeryIDnvextender;
global $galeryIDwextender;

if(!empty($galeryIDset)){
    if(!isset($galeryIDextender)){
        $galeryIDextender = 1;
    }else{
        $galeryIDextender++;
    }
}

if(!empty($galeryIDuset)){
    if(!isset($galeryIDuextender)){
        $galeryIDuextender = 1;
    }else{
        $galeryIDuextender++;
    }
}

if(!empty($galeryIDnvset)){
    if(!isset($galeryIDuextender)){
        $galeryIDuextender = 1;
    }else{
        $galeryIDuextender++;
    }
}

if(!empty($galeryIDwset)){
    if(!isset($galeryIDwextender)){
        $galeryIDwextender = 1;
    }else{
        $galeryIDwextender++;
    }
}
$postId = $post->ID;

$tablename = $wpdb->prefix . "contest_gal1ery";
$tablenameOptions = $wpdb->prefix . "contest_gal1ery_options";
$tablenameComments = $wpdb->prefix . "contest_gal1ery_comments";
$tablename_f_input = $wpdb->prefix . "contest_gal1ery_f_input";
$tablename_f_output = $wpdb->prefix . "contest_gal1ery_f_output";
$tablename_pro_options = $wpdb->prefix . "contest_gal1ery_pro_options";
$tablename_options_visual = $wpdb->prefix . "contest_gal1ery_options_visual";
$tablenameEntries = $wpdb->prefix . "contest_gal1ery_entries";
$tablenameIP = $wpdb->prefix ."contest_gal1ery_ip";
$table_posts = $wpdb->prefix ."posts";
$tablename_categories = $wpdb->prefix . "contest_gal1ery_categories";
$contest_gal1ery_options_input = $wpdb->prefix . "contest_gal1ery_options_input";

if(!isset($entryId)){
    $entryId = 0;
}

// will be used from a shortcode, but doing simply absint for fun :)
$galeryID = absint($galeryID);

$realGid = $galeryID;
$galeryIDuser = $galeryID;
$galeryIDuserForJs = $galeryIDuser;
$galeryIDshort = '';

if(!empty($isReallyGallery)){
    $galeryIDshort = '';
    if(!empty($galeryIDset)){
        $galeryIDuserForJs = $galeryIDuser.'-ext-'.$galeryIDextender;
    }
    $galeryIDset = true;
}
$isUserGallery = false;
    $isOnlyGalleryNoVoting = false;
    $isOnlyGalleryWinner = false;
$isOnlyUploadForm = false;// since 20.0.0 is always is only contact form
$isOnlyContactForm = false;
$WpPageShortCodeType = 'WpPage';
$WpPageParentShortCodeType = 'WpPageParent';
$cg_gallery_shortcode_type = 'cg_gallery';
$hasWpPageParent = false;
if(!empty($isReallyGalleryUser)){
    $isUserGallery = true; // will be used both :)
    $isOnlyGalleryUser = true;// will be used both :)
    $galeryIDshort = 'u';
    $galeryIDuser = $galeryID.'-u';
    $galeryIDuserForJs = $galeryIDuser;
    if(!empty($galeryIDuset)){
        $galeryIDuserForJs = $galeryIDuser.'-ext-'.$galeryIDuextender;
    }
    $galeryIDuset = true;
    $WpPageShortCodeType = 'WpPageUser';
    $WpPageParentShortCodeType = 'WpPageParentUser';
    $cg_gallery_shortcode_type = 'cg_gallery_user';
} else if(!empty($isReallyGalleryNoVoting)){
    $isOnlyGalleryNoVoting = true;
    $galeryIDshort = 'nv';
    $galeryIDuser = $galeryID.'-nv';
    $galeryIDuserForJs = $galeryIDuser;
    if(!empty($galeryIDnvset)){
        $galeryIDuserForJs = $galeryIDuser.'-ext-'.$galeryIDnvextender;
    }
    $galeryIDnvset = true;
    $WpPageShortCodeType = 'WpPageNoVoting';
    $WpPageParentShortCodeType = 'WpPageParentNoVoting';
    $cg_gallery_shortcode_type = 'cg_gallery_no_voting';
} else if(!empty($isReallyGalleryWinner)){
    $isOnlyGalleryWinner = true;
    $galeryIDshort = 'w';
    $galeryIDuser = $galeryID.'-w';
    $galeryIDuserForJs = $galeryIDuser;
    if(!empty($galeryIDwset)){
        $galeryIDuserForJs = $galeryIDuser.'-ext-'.$galeryIDwextender;
    }
    $galeryIDwset = true;
    $WpPageShortCodeType = 'WpPageWinner';
    $WpPageParentShortCodeType = 'WpPageParentWinner';
    $cg_gallery_shortcode_type = 'cg_gallery_winner';
}else if(!empty($isReallyUploadForm)){
    $isOnlyContactForm = true;
    $galeryIDshort = 'cf';
    $galeryIDuser = $galeryID.'-cf';
    $galeryIDuserForJs = $galeryIDuser;
    $cg_gallery_shortcode_type = 'cg_users_contact';
}else if(!empty($isReallyContactForm)){
    $isOnlyContactForm = true;
    $galeryIDshort = 'cf';
    $galeryIDuser = $galeryID.'-cf';
    $galeryIDuserForJs = $galeryIDuser;
    $cg_gallery_shortcode_type = 'cg_users_contact';
}

$isProVersion = false;
$plugin_dir_path = plugin_dir_path(__FILE__);
if(is_dir ($plugin_dir_path.'/../../../contest-gallery-pro') && strpos(cg_get_version_for_scripts(),'-PRO')!==false){
    $isProVersion = true;
}

if(!$isProVersion && isset($options['interval'])){
    unset($options['interval']);
}

$optionsSource = $options;

if(!empty($isOnlyContactForm)){
    // after options were saved, options array will be extended for other gallery ids
    $options =  (!empty($options[$galeryID])) ? $options[$galeryID] : $options;
}else{
    // after options were saved, options array will be extended for other gallery ids
    $options = (!empty($options[$galeryIDuser])) ? $options[$galeryIDuser] : $options;
}

$parentPermalink = '';
if(intval($options['general']['Version'])>=21){
    $parentPermalink = get_permalink($options['general'][$WpPageParentShortCodeType]);
    if(empty($parentPermalink)){
        echo "<p style='margin: 40px auto;text-align: center;font-weight: bold;'>The custom post type page of your Gallery (ID = $realGid) was deleted. <br>Please check backend and execute<br>
                    \"Edit options\" >>> \"Status, repair....\" >>>> \"Repair frontend\"</p>";
        return;
    }
}

$isModernOptions = (!empty($options[$galeryIDuser])) ? true : false;

$RatingVisibleForGalleryNoVoting = (!empty($options['general']['RatingVisibleForGalleryNoVoting'])) ? true : false;
$hasWpPageParent = (!empty($options['general']['WpPageParent'])) ? true : false;

$IsModernFiveStar = (!empty($options['pro']['IsModernFiveStar'])) ? true : false;

$is_frontend = true;// required for check-language, this file will be loaded in frontend only!

$WpUserId = '';

if(is_user_logged_in()){
    $WpUserId = get_current_user_id();
}

$wp_upload_dir = wp_upload_dir();

$imageDataJsonFiles = glob($wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/image-data/*.json');
$jsonImagesData = [];
foreach ($imageDataJsonFiles as $jsonFile) {
    $jsonFileData = json_decode(file_get_contents($jsonFile),true);
    $stringArray= explode('/image-data-',$jsonFile);
    $imageId = substr(substr($jsonFile,strrpos($jsonFile,'-')+1, 30),0,-5);
    // can only happen if database was cleared but json files were not deleted and old files from old "installation" are still there
	$jsonFileData = cg_check_and_repair_image_file_data($galeryID,$imageId,$jsonFileData,$IsModernFiveStar,true);
    if(empty($jsonFileData['Category'])){// repair here for sure
        $jsonFileData['Category'] = 0;
    }
    $jsonImagesData[$imageId] = $jsonFileData;
}

// simple check here, user might have different purposes on entry page, so other gallery shortcodes might be also inserted here
if(!empty($entryId) && !empty($cgEntryId) && $entryId==$cgEntryId && empty($jsonImagesData[$entryId])){
    echo contest_gal1ery_convert_for_html_output($options['visual']['TextDeactivatedEntry']);
    return;
}

include(__DIR__ ."/../../check-language.php");

###NORMAL###
cg_reset_to_normal_version_options_if_required($galeryID,$wp_upload_dir);
###NORMAL-END###

$intervalConf = cg_shortcode_interval_check($galeryID,$optionsSource,$cg_gallery_shortcode_type);

if($intervalConf['shortcodeIsActive']){
if(!empty($entryId) && !empty($cgEntryId) && $entryId==$cgEntryId && $isOnlyGalleryWinner && !empty($jsonImagesData[$entryId]) && empty($jsonImagesData[$entryId]['Winner'])){
    echo "<p class='mainCGentryNotWinnerMessage' >$language_ThisEntryIsNotAWinner</p>";
    return;
}
}

$isCgWpPageEntryLandingPage = false;
$cgWpPageEntryLandingPageGid = false;
if(!empty($entryId) && !empty($cgEntryId)  && $entryId==$cgEntryId){
    $isCgWpPageEntryLandingPage = true;
    $cgWpPageEntryLandingPageGid = $galeryIDuserForJs;
}

/*FBLIKE-WIDTH-CORRECTION-START*/
if($options['general']['FbLike']==1){
    if(!file_exists($wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/fblike-width-correction-done.txt')){

        $htmlFiles = glob($wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-' . $galeryID . '/*.html');

        $replace = '                        <!--FBLIKE-WIDTH-CORRECTION-START-->
                                                <style>
                                                    .fb_iframe_widget iframe {
                                                        width: unset !important;
                                                    }
                                                </style>
                                                <!--FBLIKE-WIDTH-CORRECTION-END-->
                                                 </head>';

        foreach ($htmlFiles as $htmlFile) {

            $fp = fopen($htmlFile, 'r');
            $htmlFileData = fread($fp, filesize($htmlFile));
            fclose($fp);

            if(strpos($htmlFileData,'<!--FBLIKE-WIDTH-CORRECTION-START-->')===false){
                $htmlFileDataNew = str_replace('</head>', $replace, $htmlFileData);
                $fp = fopen($htmlFile, 'w');
                fwrite($fp, $htmlFileDataNew);
                fclose($fp);
            }

        }

        $fp = fopen($wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/fblike-width-correction-done.txt', 'w');
        fwrite($fp, 'do not remove this txt file if you read this otherwise might break frontend gallery functionality');
        fclose($fp);

    }
}
/*FBLIKE-WIDTH-CORRECTION-END*/

$is_user_logged_in = is_user_logged_in();
$isShowGallery = true;


if(($options['pro']['RegUserGalleryOnly']==1 && $is_user_logged_in == false) || ($isUserGallery == true && $is_user_logged_in == false)){
    $isShowGallery = false;
}

if($isShowGallery == true){

    // check if sort values files exists
    if(!file_exists($wp_upload_dir['basedir'] . "/contest-gallery/gallery-id-".$galeryID."/json/".$galeryID."-images-sort-values.json")){
    }else{

        $jsonImagesSortValuesFile = $wp_upload_dir['basedir'] . "/contest-gallery/gallery-id-".$galeryID."/json/".$galeryID."-images-sort-values.json";

    }
    // check if sort values files exists --- ENDE

    // check if image-info-values-file-exists
    if(!file_exists($wp_upload_dir['basedir'] . "/contest-gallery/gallery-id-".$galeryID."/json/".$galeryID."-images-info-values.json")){
    //    cg_actualize_all_images_data_info_file($galeryID);
    }else{

        $jsonImagesInfoValuesFile = $wp_upload_dir['basedir'] . "/contest-gallery/gallery-id-".$galeryID."/json/".$galeryID."-images-info-values.json";

        if(filesize($jsonImagesInfoValuesFile)<10){// then must be empty array or empty file and have to be repaired
     //       cg_actualize_all_images_data_info_file($galeryID);
        }else{

            $frontendAddedImagesDir = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/frontend-added-or-removed-images';

        }

    }

    // if users were deleted
    if(file_exists($wp_upload_dir['basedir'] . "/contest-gallery/gallery-id-".$galeryID."/json/".$galeryID."-deleted-image-ids.json")){
        cg_actualize_all_images_data_deleted_images($galeryID);
    }

    $jsonImagesCount = count($jsonImagesData);

    $jsonCategories = array();

    $jsonCategoriesFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/'.$galeryID.'-categories.json';
    if(file_exists($jsonCategoriesFile)){
        $fp = fopen($jsonCategoriesFile, 'r');
        $jsonCategories = json_decode(fread($fp,filesize($jsonCategoriesFile)),true);
        fclose($fp);
    }

    $userIP = sanitize_text_field(cg_get_user_ip());
    $userIPtype = sanitize_text_field(cg_get_user_ip_type());
    $userIPisPrivate = cg_check_if_ip_is_private($userIP);
    $userIPtypesArray = cg_available_ip_getter_types();

    if($is_user_logged_in){
        $wpUserId = get_current_user_id();
    }
    else{
        $wpUserId=0;
    }

    $wp_create_nonce = wp_create_nonce("check");

    $LooksCount = 0;
    if($options['general']['ThumbLook'] == 1){$LooksCount++;}
    if($options['general']['HeightLook'] == 1){$LooksCount++;}
    if($options['general']['RowLook'] == 1){$LooksCount++;}
    if($options['general']['SliderLook'] == 1){$LooksCount++;}
    if(empty($options['visual']['BlogLook'])){
        $options['visual']['BlogLook'] = 0;
    }
    if($options['visual']['BlogLook'] == 1){$LooksCount++;}

    if(empty($options['pro']['SlideTransition'])){
        $options['pro']['SlideTransition']='translateX';
    }

    $ShowCatsUnchecked = 0;
    if(!empty($options['pro']['ShowCatsUnchecked'])){
        $ShowCatsUnchecked = 1;
    }

    $check = wp_create_nonce("check");
    $p_cgal1ery_db_version = get_option( "p_cgal1ery_db_version" );
    $upload_folder = wp_upload_dir();
    $upload_folder_url = $upload_folder['baseurl']; // Pfad zum Bilderordner angeben

    $wpNickname = '';

    if($is_user_logged_in){$current_user = wp_get_current_user();$wpNickname = $current_user->display_name;}

    if(is_ssl()){
        if(strpos($upload_folder_url,'http://')===0){
            $upload_folder_url = str_replace( 'http://', 'https://', $upload_folder_url );
        }
    }
    else{
        if(strpos($upload_folder_url,'https://')===0){
            $upload_folder_url = str_replace( 'https://', 'http://', $upload_folder_url );
        }
    }

    // correction of old five star
    if($options['general']['AllowRating']==1){
        $options['general']['AllowRating']=15;
    }

    if($options['general']['CheckLogin']==1 and ($options['general']['AllowRating']==1 or $options['general']['AllowRating']>=12 or $options['general']['AllowRating']==2)){
        if($is_user_logged_in){$UserLoginCheck = 1;$current_user = wp_get_current_user();$wpNickname = $current_user->display_name;} // Allow only registered users to vote (Wordpress profile) wird dadurch aktiviert
        else{$UserLoginCheck=0;}//Allow only registered users to vote (Wordpress profile): wird dadurch deaktiviert
    }
    else{$UserLoginCheck=0;}


    $cgGalleryStyle = 'center-black';
    $cgCenterWhite = false;

    if(!empty($options['visual']['GalleryStyle'])){
        if($options['visual']['GalleryStyle']=='center-white'){
            $cgGalleryStyle='center-white';
            $cgCenterWhite=true;
        }
    }



    $CheckLogin = 0;
    if(isset($options['general']['CheckLogin'])){// to go sure there is no undefined key error
        if($options['general']['CheckLogin']==1){
            $CheckLogin = 1;
        }
    }

    if(!empty($entryId) && !empty($cgEntryId) && $entryId==$cgEntryId){
        echo "<input type='hidden' id='mainCGdivEntryPageHiddenInput' value='true'>";
    }


    global $wp;
    $cgPageUrl = home_url( $wp->request );
    echo "<input type='hidden' id='cgPageUrl' value='$cgPageUrl'>";
    echo "<input type='hidden' id='cgIsUserLoggedIn' value='$is_user_logged_in'>";

    if(empty($options['general']['CheckIp']) && empty($options['general']['CheckLogin']) && empty($options['general']['CheckCookie'])){
        $options['general']['CheckIp']=1;
    }

    if($isOnlyGalleryNoVoting && !empty($options['general']['CheckCookie']) && !isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting'])){
        cg_set_cookie($galeryID,'voting'); // since 19.1.4 will be set via PHP if not exists
    }

    $cgFeControlsStyle = 'cg_fe_controls_style_white';
    $cgFeControlsStyleHideBlackSites ='';
    $cgFeControlsStyleHideWhiteSites ='cg_hide';
    $BorderRadiusClass = '';
    if(!empty($isOnlyContactForm)){

        $optionsVisual = $wpdb->get_row($wpdb->prepare( "SELECT * FROM $tablename_options_visual WHERE GalleryID = %d"  ,[$galeryID]));
        $FeControlsStyleUpload = $optionsVisual->FeControlsStyleUpload;
        $BorderRadiusUpload = $optionsVisual->BorderRadiusUpload;

        if($FeControlsStyleUpload=='black'){
            $cgFeControlsStyle='cg_fe_controls_style_black';
            $cgFeControlsStyleHideBlackSites ='cg_hide';
            $cgFeControlsStyleHideWhiteSites ='';
        }
        if($BorderRadiusUpload=='1' || empty($optionsVisual->FeControlsStyleUpload)){
            $BorderRadiusClass = 'cg_border_radius_controls_and_containers';
        }
    }else{
        if(!empty($options['visual']['FeControlsStyle'])){
            if($options['visual']['FeControlsStyle']=='black'){
                $cgFeControlsStyle='cg_fe_controls_style_black';
                $cgFeControlsStyleHideBlackSites ='cg_hide';
                $cgFeControlsStyleHideWhiteSites ='';
            }
        }
        if(!empty($options['visual']['BorderRadius'])){
            if($options['visual']['BorderRadius']=='1'){
                $BorderRadiusClass = 'cg_border_radius_controls_and_containers';
            }
        }
    }

    $cgHideDivContainerClass = '';

    if($isUserGallery && !$is_user_logged_in){
        $cgHideDivContainerClass = 'cg_hide';
    }

    if(!empty($entryId) && !empty($options['visual']['TextBeforeWpPageEntry'])){
        echo contest_gal1ery_convert_for_html_output($options['visual']['TextBeforeWpPageEntry']);
    }

/*
    echo "<pre>";
    print_r($intervalConf);
    echo "</pre>";

    die;*/

    if(!$intervalConf['shortcodeIsActive']){
            ?>
            <pre>
            <script data-cg-processing="true">
                if(typeof isNotActiveGallery == 'undefined'){
                    isNotActiveGallery = {};
                }
                var gid = <?php echo json_encode($galeryIDuserForJs);?>;
                isNotActiveGallery[gid] = <?php echo json_encode(true);?>;
            </script>
            </pre>
            <?php
            echo contest_gal1ery_convert_for_html_output($intervalConf['TextWhenShortcodeIntervalIsOff']);
    }else{

        echo contest_gal1ery_convert_for_html_output($intervalConf['TextWhenShortcodeIntervalIsOn']);

        echo "<input type='hidden' class='cg-loaded-gids' value='$galeryIDuserForJs' data-cg-short='$galeryIDshort'
 data-cg-real-gid='$realGid' data-cg-gid='$galeryIDuserForJs' >";

    echo "<div id='mainCGdivContainer$galeryIDuserForJs' class='mainCGdivContainer $cgHideDivContainerClass' data-cg-gid='$galeryIDuserForJs'>";
    echo "<div id='mainCGdivHelperParent$galeryIDuserForJs' class='mainCGdivHelperParent cg_display_block $cgFeControlsStyle' data-cg-gid='$galeryIDuserForJs'>";
    echo "<div id='cgLdsDualRingDivGalleryHide$galeryIDuserForJs' class='cg_hide cg-lds-dual-ring-div-gallery-hide cg-lds-dual-ring-div-gallery-hide-parent $BorderRadiusClass $cgFeControlsStyle'><div class='cg-lds-dual-ring-gallery-hide $cgFeControlsStyle'></div></div>";

    echo "<div id='mainCGdiv$galeryIDuserForJs' class='mainCGdiv $cgFeControlsStyle $BorderRadiusClass' data-cg-gid='$galeryIDuserForJs'>";

    $options['visual']['BlogLook'] = (!empty($options['visual']['BlogLook']) )? $options['visual']['BlogLook'] : 0;
    $BlogLookOrder = (!empty($options['visual']['BlogLookOrder']) )? $options['visual']['BlogLookOrder'] : 5;
    // Order of views will be determined
    $ThumbLookOrder = $options['general']['ThumbLookOrder'];
    $HeightLookOrder = $options['general']['HeightLookOrder'];
    $RowLookOrder = $options['general']['RowLookOrder'];
    $SliderLookOrder = $options['general']['SliderLookOrder'];
    $BlogLookOrder = (!empty($options['visual']['BlogLookOrder']) ) ? $options['visual']['BlogLookOrder'] : 5;
        // since 21.2.0
        // then remove heightLookOrder and also the old rowLookOrder and switch to thumbLook, also check for some strange values in thumbLook in JS
        if($HeightLookOrder==1 || $RowLookOrder==1){$ThumbLookOrder=1;}
        // since 21.2.0 $HeightLookOrder and $RowLookOrder completely
        $orderGalleries = array($SliderLookOrder =>'SliderLookOrder', $ThumbLookOrder =>'ThumbLookOrder', $BlogLookOrder => 'BlogLookOrder');
    ksort($orderGalleries);

    $currentLook = 'blog';
    foreach ($orderGalleries as $value) {
        if($value == 'BlogLookOrder' && $options['visual']['BlogLook']==1){$currentLook = 'blog';break;}
        if($value == 'ThumbLookOrder' && $options['general']['ThumbLook']==1){$currentLook = 'thumb';break;}
        if($value == 'SliderLookOrder' && $options['general']['SliderLook']==1){$currentLook = 'slider';break;}
    }

    include (__DIR__.'/gallery/gallery-upload-form-options.php');

    if($isOnlyContactForm){
        if($mainCGdivShowUncollapsed){
            echo "<div class='cg_skeleton_loader_on_page_load_div cg_skeleton_loader_on_page_load_div_uncollapsed'>";
                echo "<div class='cg_skeleton_loader_on_page_load_container'>";
                echo "<div class='cg_skeleton_loader_on_page_load' style='height:220px;width:100%;'></div>";
                echo "</div>";
                echo "<div class='cg_skeleton_loader_on_page_load_container' >";
                echo "<div class='cg_skeleton_loader_on_page_load' style='height:30px;width:100%;'></div>";
                echo "</div>";
                echo "<div class='cg_skeleton_loader_on_page_load_container' >";
                echo "<div class='cg_skeleton_loader_on_page_load' style='height:30px;width:100%;'></div>";
                echo "</div>";
                echo "<div class='cg_skeleton_loader_on_page_load_container' >";
                echo "<div class='cg_skeleton_loader_on_page_load' style='height:30px;width:150px;'></div>";
                echo "</div>";
            echo "</div>";
        }else{
            echo "<div class='cg_skeleton_loader_on_page_load_div cg_skeleton_loader_on_page_load_div_form_collapsed'>";
                echo "<div class='cg_skeleton_loader_on_page_load_container'>";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:60px;width:100%;'></div>";
                    echo "</div>";
                    echo "<div class='cg_skeleton_loader_on_page_load_container' >";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:300px;width:100%;'></div>";
                    echo "</div>";
                    echo "<div class='cg_skeleton_loader_on_page_load_container' style='margin-bottom: 10px;'>";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:30px;width:25%;'></div>";
                    echo "</div>";
                    echo "<div class='cg_skeleton_loader_on_page_load_container' style='margin-bottom: 10px;' >";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:30px;width:50%;'></div>";
                    echo "</div>";
                    echo "<div class='cg_skeleton_loader_on_page_load_container' style='margin-bottom: 10px;' >";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:30px;width:75%;'></div>";
                    echo "</div>";
                    echo "<div class='cg_skeleton_loader_on_page_load_container' >";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:30px;width:100%;'></div>";
                echo "</div>";
            echo "</div>";
        }
    }else{

        if(!empty($entryId) OR $currentLook=='blog' OR $currentLook=='slider'){
            echo "<div class='cg_skeleton_loader_on_page_load_div cg_skeleton_loader_on_page_load_div_blog_and_slider_view'>";
                echo "<div class='cg_skeleton_loader_on_page_load_container'>";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:60px;width:100%;'></div>";
                    echo "</div>";
                    echo "<div class='cg_skeleton_loader_on_page_load_container' >";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:300px;width:100%;'></div>";
                    echo "</div>";
                    echo "<div class='cg_skeleton_loader_on_page_load_container' style='margin-bottom: 10px;'>";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:30px;width:25%;'></div>";
                    echo "</div>";
                    echo "<div class='cg_skeleton_loader_on_page_load_container' style='margin-bottom: 10px;' >";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:30px;width:50%;'></div>";
                    echo "</div>";
                    echo "<div class='cg_skeleton_loader_on_page_load_container' style='margin-bottom: 10px;' >";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:30px;width:75%;'></div>";
                    echo "</div>";
                    echo "<div class='cg_skeleton_loader_on_page_load_container' >";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:30px;width:100%;'></div>";
                echo "</div>";
            echo "</div>";
        }else if($currentLook=='thumb'){
            echo "<div class='cg_skeleton_loader_on_page_load_div cg_skeleton_loader_on_page_load_div_thumb_view'>";
                echo "<div class='cg_skeleton_loader_on_page_load_container'>";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:60px;width:100%;'></div>";
                    echo "</div>";
                    echo "<div class='cg_skeleton_loader_on_page_load_container' style='margin-bottom: 10px;'>";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:250px;width: 32.3%;'></div>";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:250px;width: 32.3%;'></div>";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:250px;width: 32.3%;'></div>";
                    echo "</div>";
                    echo "<div class='cg_skeleton_loader_on_page_load_container' style='margin-bottom: 10px;'>";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:250px;width: 32.3%;'></div>";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:250px;width: 32.3%;'></div>";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:250px;width: 32.3%;'></div>";
                    echo "</div>";
                    echo "<div class='cg_skeleton_loader_on_page_load_container' >";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:250px;width: 32.3%;'></div>";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:250px;width: 32.3%;'></div>";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:250px;width: 32.3%;'></div>";
                echo "</div>";
            echo "</div>";
        }else if($currentLook=='height'){
            echo "<div class='cg_skeleton_loader_on_page_load_div cg_skeleton_loader_on_page_load_div_height_view'>";
                echo "<div class='cg_skeleton_loader_on_page_load_container'>";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:60px;width:100%;'></div>";
                    echo "</div>";
                    echo "<div class='cg_skeleton_loader_on_page_load_container' style='margin-bottom: 10px;'>";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:250px;width: 25%;'></div>";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:250px;width: 74%;'></div>";
                    echo "</div>";
                    echo "<div class='cg_skeleton_loader_on_page_load_container' style='margin-bottom: 10px;'>";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:250px;width: 74%;'></div>";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:250px;width: 25%;'></div>";
                    echo "</div>";
                    echo "<div class='cg_skeleton_loader_on_page_load_container' >";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:250px;width: 25%;'></div>";
                    echo "<div class='cg_skeleton_loader_on_page_load' style='height:250px;width: 74%;'></div>";
                echo "</div>";
            echo "</div>";
        }
   }

    if(!empty($entryId) && !empty($options['visual']['ShowBackToGalleryButton'])){
        if(!isset($options['visual']['BackToGalleryButtonText'])){$options['visual']['BackToGalleryButtonText']='';}
        if(!empty($options['pro']['BackToGalleryButtonURL'])){
            $entryPermalink = contest_gal1ery_convert_for_html_output_without_nl2br($options['pro']['BackToGalleryButtonURL']);
        }else{
            $entryPermalink = get_permalink(wp_get_post_parent_id($jsonImagesData[$entryId][$WpPageShortCodeType]));
        }
        echo "<div class='mainCGBackToGalleryButtonHrefContainer cg_hide'>";
            echo "<a href='$entryPermalink' class='mainCGBackToGalleryButtonHref' data-cg-gid='$galeryIDuserForJs'>";
                echo "<div id='mainCGBackToGalleryButton$galeryIDuserForJs' class=' mainCGBackToGalleryButton'>".contest_gal1ery_convert_for_html_output_without_nl2br($options['visual']['BackToGalleryButtonText'])."</div>";
            echo "</a>";
        echo "</div>";
    }

    if(is_user_logged_in()){
        if(current_user_can('manage_options')){
            $galleryJsonCommentsDir = $wp_upload_dir['basedir'].'/contest-gallery/changes-messages-frontend';
            if (!is_dir($galleryJsonCommentsDir)) {
                mkdir($galleryJsonCommentsDir, 0755, true);
            }

            ###NORMAL###
            $cgPro = false;

            $arrayNew = array(
                '824f6b8e4d606614588aa97eb8860b7e',
                'add4012c56f21126ba5a58c9d3cffcd7',
                'bfc5247f508f427b8099d17281ecd0f6',
                'a29de784fb7699c11bf21e901be66f4e',
                'e5a8cb2f536861778aaa2f5064579e29',
                '36d317c7fef770852b4ccf420855b07b'
            );

            if(file_exists($wp_upload_dir['basedir'].'/contest-gallery/changes-messages-frontend/pro-check.txt')){
                $cgPro = file_get_contents($wp_upload_dir['basedir'].'/contest-gallery/changes-messages-frontend/pro-check.txt');
                if($cgPro=='true'){
                    include('normal/download-proper-pro-version-info-frontend-area.php');
                }
            }else if(!file_exists($wp_upload_dir['basedir'].'/contest-gallery/changes-messages-frontend/pro-check.txt')){// if not exists, then one check and create file

                // Check start from here:
                $p_cgal1ery_reg_code = get_option("p_cgal1ery_reg_code");
                $p_c1_k_g_r_8 = get_option("p_c1_k_g_r_9");
                if((!empty($p_cgal1ery_reg_code) AND $p_cgal1ery_reg_code!='1') OR (!empty($p_c1_k_g_r_8) AND $p_c1_k_g_r_8!='1')){
                    $cgPro = true;
                }

                if (!is_dir($wp_upload_dir['basedir'].'/contest-gallery/changes-messages-frontend')) {
                    mkdir($wp_upload_dir['basedir'].'/contest-gallery/changes-messages-frontend', 0755);
                }

                if($cgPro){
                    file_put_contents($wp_upload_dir['basedir'].'/contest-gallery/changes-messages-frontend/pro-check.txt','true');
                    include('normal/download-proper-pro-version-info-frontend-area.php');
                }else{
                    file_put_contents($wp_upload_dir['basedir'].'/contest-gallery/changes-messages-frontend/pro-check.txt','false');
                }
            }
            ###NORMAL-END###

        }
    }

    echo "<div id='mainCGdivHelperChild$galeryIDuserForJs' class='mainCGdivHelperChild' data-cg-gid='$galeryIDuserForJs'>";

    echo "<div id='mainCGdivFullWindowConfigurationArea$galeryIDuserForJs' class='mainCGdivFullWindowConfigurationArea cg-header-controls-show-only-full-window cg_hide $cgFeControlsStyle' data-cg-gid='$galeryIDuserForJs'>";
    echo "<div class='mainCGdivFullWindowConfigurationAreaCloseButtonContainer' data-cg-gid='$galeryIDuserForJs'><div class='mainCGdivFullWindowConfigurationAreaCloseButton' data-cg-gid='$galeryIDuserForJs' ></div></div>";
    echo "</div>";

    echo "<span id='cgViewHelper$galeryIDuserForJs' class='cg_view_helper'></span>";

    echo "<input type='hidden' id='cg_language_i_am_not_a_robot' value='$language_IamNotArobot' >";

    echo "<div id='cg_ThePhotoContestIsOver_dialog' style='display:none;' class='cg_show_dialog'><p>$language_ThePhotoContestIsOver</p></div>";
    echo "<div id='cg_AlreadyRated_dialog' style='display:none;' class='cg_show_dialog'><p>$language_YouHaveAlreadyVotedThisPicture</p></div>";
    echo "<div id='cg_AllVotesUsed_dialog' style='display:none;' class='cg_show_dialog'><p>$language_AllVotesUsed</p></div>";

    //include('gallery/comment-div.php');
    //include('gallery/slider-div.php');

        echo "<div style='visibility: hidden;'  class='cg_header cg_hide'>";

    include('gallery/header.php');

    echo "</div>";
    echo "</div>";// Closing mainCGdivHelperChild

    include('gallery/further-images-steps-container.php');

    include('gallery/show-text-until-an-image-added-container.php');

    echo '<div class="cg-lds-dual-ring-div '.$cgFeControlsStyle.' cg_hide"><div class="cg-lds-dual-ring"></div></div>';
    echo "<div id='cgLdsDualRingMainCGdivHide$galeryIDuserForJs' class='cg-lds-dual-ring-div-gallery-hide cg-lds-dual-ring-div-gallery-hide-mainCGallery $cgFeControlsStyle cg_hide'><div class='cg-lds-dual-ring-gallery-hide $cgFeControlsStyle cg-lds-dual-ring-gallery-hide-mainCGallery'></div></div>";

    include('gallery/cg-messages.php');

        echo "<div  style='visibility: hidden;'  id='mainCGallery$galeryIDuserForJs' data-cg-gid='$galeryIDuserForJs' class='mainCGallery'   data-cg-real-gid='$realGid'>";
        echo "<div id='mainCGslider$galeryIDuserForJs' data-cg-gid='$galeryIDuserForJs' class='mainCGslider cg_hide cgCenterDivBackgroundColor' >";
        echo "</div>";
        include('gallery/inside-gallery-single-image-view.php');
        echo "<div id='cgLdsDualRingCGcenterDivHide$galeryIDuserForJs' class='cg-lds-dual-ring-div-gallery-hide $cgFeControlsStyle cg-lds-dual-ring-div-gallery-hide-cgCenterDiv cg_hide'><div class='cg-lds-dual-ring-gallery-hide $cgFeControlsStyle cg-lds-dual-ring-gallery-hide-cgCenterDiv'></div></div>";
    echo "</div>";
    echo "<div id='cgLdsDualRingCGcenterDivLazyLoader$galeryIDuserForJs' class='cg-lds-dual-ring-div-gallery-hide cg-lds-dual-ring-div-gallery-hide-mainCGallery $cgFeControlsStyle cg_hide'><div class='cg-lds-dual-ring-gallery-hide $cgFeControlsStyle cg-lds-dual-ring-gallery-hide-mainCGallery'></div></div>";
    echo "</div>";
    echo "<div id='cgCenterDivAppearenceHelper$galeryIDuserForJs' class='cgCenterDivAppearenceHelper'>
    </div>";

    echo "</div>";


    echo "<noscript>";

    echo "<div id='mainCGdivNoScriptContainer$galeryIDuserForJs' class='mainCGdivNoScriptContainer' data-cg-gid='$galeryIDuserForJs'>";

    if(file_exists($upload_folder["basedir"].'/contest-gallery/gallery-id-'.$galeryID.'/json/'.$galeryID.'-noscript.html')){
        echo file_get_contents($upload_folder["basedir"].'/contest-gallery/gallery-id-'.$galeryID.'/json/'.$galeryID.'-noscript.html');
    }

    echo "</div>";

    echo "</noscript>";


    echo "</div>";

    }

    if(!empty($entryId) && !empty($options['visual']['TextAfterWpPageEntry'])){
        echo contest_gal1ery_convert_for_html_output($options['visual']['TextAfterWpPageEntry']);
    }

include('load-data-ajax.php');

}
else{

    if(!$intervalConf['shortcodeIsActive']){
        echo contest_gal1ery_convert_for_html_output($intervalConf['TextWhenShortcodeIntervalIsOff']);
    }else{
    echo "<div id='cgRegUserGalleryOnly$galeryIDuserForJs' class='cgRegUserGalleryOnly' data-cg-gid='$galeryIDuserForJs'>";

        echo contest_gal1ery_convert_for_html_output($options['pro']['RegUserGalleryOnlyText']);

    echo "</div>";
    }

    ?>
    <pre>
    <script>
        // will be set in gallery entry page or single entry page
        var mainCGdivEntryPageContainer = document.getElementById('mainCGdivEntryPageContainer');
        if(mainCGdivEntryPageContainer){
            mainCGdivEntryPageContainer.classList.add("cg_visibility_visible");// better with add class CSS
        }
    </script>
        </pre>
    <?php


}



?>