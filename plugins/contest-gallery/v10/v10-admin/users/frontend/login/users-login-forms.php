<?php

if(!isset($cgHideUserAjaxLoginForm)){
    $cgHideUserAjaxLoginForm = '';
}

$mainCGdivUploadFormType = 'mainCGdivUploadFormStatic';
$cgHideUserAjaxLoginFormCloseButton = '';
if(isset($isForAjax)){
    $mainCGdivUploadFormType = 'mainCGdivUploadFormAjax';
}else{
    $cgHideUserAjaxLoginFormCloseButton = 'cg_hide';
}

if(!isset($galeryIDuser)){
    $galeryIDuser = $GalleryID;
}

$isProVersion = false;
$plugin_dir_path = plugin_dir_path(__FILE__);
if(is_dir ($plugin_dir_path.'/../../../../../../contest-gallery-pro') && strpos(cg_get_version_for_scripts(),'-PRO')!==false){
    $isProVersion = true;
}

if(!$isProVersion && isset($optionsSource['interval'])){
    unset($optionsSource['interval']);
}

$wp_upload_dir = wp_upload_dir();
$optionsPath = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-options.json';
$optionsSource =json_decode(file_get_contents($optionsPath),true);
$intervalConf = cg_shortcode_interval_check($GalleryID,$optionsSource,'cg_users_login');
if(!$intervalConf['shortcodeIsActive']){
    echo contest_gal1ery_convert_for_html_output($intervalConf['TextWhenShortcodeIntervalIsOff']);
}else{
    echo contest_gal1ery_convert_for_html_output($intervalConf['TextWhenShortcodeIntervalIsOn']);

if(!is_user_logged_in()){
    if(!empty($TextBeforeLoginForm)){
        echo "<div id='cgTextBeforeLoginFormContainer' >";
        echo $TextBeforeLoginForm;
        echo "</div>";
    }
}

echo "<div id='cg_user_login_div' class='mainCGdivUploadForm mainCGdivLoginForm $mainCGdivUploadFormType $cgHideUserAjaxLoginForm $FeControlsStyleLogin $BorderRadiusLogin' data-cg-gid='$galeryIDuser' >";

    echo "<div class='cg_hover_effect cg-close-upload-form $FeControlsStyleLogin $cgHideUserAjaxLoginFormCloseButton' data-cg-gid='$galeryIDuser' data-cg-tooltip='$language_Close'>";
echo "</div>";

//$check = wp_create_nonce("check");
// new check required wp_create_nonce might be different when calling ajax
$cg_login_check =  cg_hash_function('---cglogin---'.$GalleryID);

$cgResetPasswordLinkNotValidAnymore = false;
$cgResetPasswordLinkHideLoginFormClass = '';

if(!empty($_GET['cgResetPassword'])){

    $cgResetPasswordWpUserID = intval(sanitize_text_field(urldecode($_GET['cgResetPassword'])));

    $wpUserID = $wpdb->get_var("SELECT ID FROM $tablenameWpUsers WHERE ID = '".$cgResetPasswordWpUserID."'");

    $cgLostPasswordMailTimestamp = intval(get_the_author_meta( 'cgLostPasswordMailTimestamp', $wpUserID ) );
    if(empty($cgLostPasswordMailTimestamp)){
        $cgResetPasswordLinkNotValidAnymore = true;
        $cgResetPasswordLinkHideLoginFormClass = 'cg_hide';
    }else{
        if($cgLostPasswordMailTimestamp+(60*60*24)<time()){// 24 hours valid
            $cgResetPasswordLinkNotValidAnymore = true;
            $cgResetPasswordLinkHideLoginFormClass = 'cg_hide';
            delete_user_meta($wpUserID,'cgLostPasswordMailTimestamp');
        }else{
            $cgResetPasswordLinkHideLoginFormClass = 'cg_hide';
            include(__DIR__.'/forms/users-login-form-reset-password.php');
        }
    }

}

if($cgResetPasswordLinkNotValidAnymore){
    include(__DIR__.'/forms/users-login-form-reset-password-link-not-valid-anymore.php');
}

    include(__DIR__.'/forms/users-login-form-login.php');

if($LostPasswordMailActive==1){
    include(__DIR__.'/forms/users-login-form-lost-password.php');
}

// Wichtig! Ajax Abarbeitung hier!
echo "<div id='cg_login_message'>";

echo "</div>";

if(isset($isForAjax)){
    echo "<hr class='mainCGdivUploadFormAjaxRegistryButtonDivider'>";
    echo "<div class='cgRegistryFormButton cgRegistryFormButtonAjaxForm $cgRegistryFormButtonGallery $FeControlsStyleLogin $BorderRadiusLogin' data-cg-gid='$galeryIDuser'><span>Create account</span></div>";
}

//echo "$language_MaximumAllowedWidthForJPGsIs";
echo "<input type='hidden' id='cg_show_upload' value='1'>";

//echo "language_ThisFileTypeIsNotAllowed: $language_ThisFileTypeIsNotAllowed";
echo "<input type='hidden' id='cg_file_not_allowed_1' value='$language_ThisFileTypeIsNotAllowed'>";
echo "<input type='hidden' id='cg_file_size_to_big' value='$language_TheFileYouChoosedIsToBigMaxAllowedSize'>";
//echo "<input type='hidden' id='cg_post_size' value='$post_max_sizeMB'>";

echo "<input type='hidden' id='cg_to_high_resolution' value='$language_TheResolutionOfThisPicIs'>";

echo "<input type='hidden' id='cg_max_allowed_resolution_jpg' value='$language_MaximumAllowedResolutionForJPGsIs'>";
echo "<input type='hidden' id='cg_max_allowed_width_jpg' value='$language_MaximumAllowedWidthForJPGsIs'>";
echo "<input type='hidden' id='cg_max_allowed_height_jpg' value='$language_MaximumAllowedHeightForJPGsIs'>";

echo "<input type='hidden' id='cg_max_allowed_resolution_png' value='$language_MaximumAllowedResolutionForPNGsIs'>";
echo "<input type='hidden' id='cg_max_allowed_width_png' value='$language_MaximumAllowedWidthForPNGsIs'>";
echo "<input type='hidden' id='cg_max_allowed_height_png' value='$language_MaximumAllowedHeightForPNGsIs'>";

echo "<input type='hidden' id='cg_max_allowed_resolution_gif' value='$language_MaximumAllowedResolutionForGIFsIs'>";
echo "<input type='hidden' id='cg_max_allowed_width_gif' value='$language_MaximumAllowedWidthForGIFsIs'>";
echo "<input type='hidden' id='cg_max_allowed_height_gif' value='$language_MaximumAllowedHeightForGIFsIs'>";

echo "<input type='hidden' id='cg_check_agreement' value='$language_YouHaveToCheckThisAgreement '>";
echo "<input type='hidden' id='cg_check_email_upload_for_login' value=' $language_EmailAddressHasToBeValid'>";
echo "<input type='hidden' id='cg_min_characters_text' value='$language_MinAmountOfCharacters'>";
echo "<input type='hidden' id='cg_max_characters_text' value='$language_MaxAmountOfCharacters'>";
echo "<input type='hidden' id='cg_no_picture_is_choosed' value='$language_ChooseYourImage'>";


echo "<input type='hidden' id='cg_language_BulkUploadQuantityIs' value='$language_BulkUploadQuantityIs'>";
echo "<input type='hidden' id='cg_language_BulkUploadLowQuantityIs' value='$language_BulkUploadLowQuantityIs'>";

echo "<input type='hidden' id='cg_language_BulkUploadLowQuantityIs' value='$language_BulkUploadLowQuantityIs'>";

echo "<input type='hidden' id='cg_language_ThisMailAlreadyExists' value='$language_ThisMailAlreadyExists'>";
echo "<input type='hidden' id='cg_language_ThisNicknameAlreadyExists' value='$language_ThisNicknameAlreadyExists'>";
echo "<input type='hidden' id='cg_language_ThisUsernameAlreadyExists' value='$language_ThisUsernameAlreadyExists'>";

echo "<input type='hidden' id='cg_language_PasswordRequired' value='$language_PasswordRequired'>";
echo "<input type='hidden' id='cg_language_UsernameOrEmailRequired' value='$language_UsernameOrEmailRequired'>";
echo "<input type='hidden' id='cg_language_EmailRequired' value='$language_EmailRequired'>";
echo "<input type='hidden' id='cg_language_EmailAddressHasToBeValid' value='$language_EmailAddressHasToBeValid'>";

echo "<input type='hidden' id='cg_language_EmailAndPasswordDoNotMatch' value='$language_EmailAndPasswordDoNotMatch'>";
echo "<input type='hidden' id='cg_language_LoginAndPasswordDoNotMatch' value='$language_LoginAndPasswordDoNotMatch'>";
echo "<input type='hidden' id='cg_language_PleaseFillOut' value='$language_PleaseFillOut'>";
echo "<input type='hidden' id='cg_language_PasswordsDoNotMatch' value='$language_PasswordsDoNotMatch'>";
echo "<input type='hidden' id='cg_language_LostPasswordUrlIsNotValidAnymore' value='$language_LostPasswordUrlIsNotValidAnymore'>";
echo "<input type='hidden' id='cg_language_ResetPasswordSuccessfully' value='$language_ResetPasswordSuccessfully'>";
echo "<input type='hidden' id='cg_language_MinAmountOfCharacters' value='$language_MinAmountOfCharacters'>";

echo "</div>";

}



?>