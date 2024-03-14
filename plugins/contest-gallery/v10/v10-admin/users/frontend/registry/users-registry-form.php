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

if(!isset($FeControlsStyleRegistry)){
    $FeControlsStyleRegistry = $cgFeControlsStyle;
}

if(!isset($BorderRadiusRegistry)){
    $BorderRadiusRegistry = $BorderRadiusClass;
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
$intervalConf = cg_shortcode_interval_check($GalleryID,$optionsSource,'cg_users_reg');
if(!$intervalConf['shortcodeIsActive']){
    echo contest_gal1ery_convert_for_html_output($intervalConf['TextWhenShortcodeIntervalIsOff']);
}else{
    echo contest_gal1ery_convert_for_html_output($intervalConf['TextWhenShortcodeIntervalIsOn']);

    echo "<div id='cg_user_registry_div' class='mainCGdivUploadForm $mainCGdivUploadFormType $cgHideUserAjaxLoginForm $BorderRadiusRegistry $FeControlsStyleRegistry' data-cg-gid='$galeryIDuser' >";

    echo "<div class='cg_hover_effect cg-close-upload-form $FeControlsStyleRegistry $cgHideUserAjaxLoginFormCloseButton' data-cg-gid='$galeryIDuser' data-cg-tooltip='$language_Close'>";
    echo "</div>";


    echo "<div style='visibility: hidden;' class='mainCGdivUploadFormContainer' >";
    echo "<input type='hidden' id='cg_check_mail_name_value' value='0'>";
    echo "<input type='hidden' id='cg_site_url' value='" . get_site_url() . "'/>";

    echo "<span id='cg_user_registry_anchor'/></span>";

    echo '<form action="?cg_register=true" method="post" id="cg_user_registry_form" enctype="multipart/form-data" data-cg-gid="$GalleryID">';


// User ID �berpr�fung ob es die selbe ist
// $check = wp_create_nonce("check");
// new check required wp_create_nonce might be different when calling ajax
//$check = md5(wp_salt('auth') . '---cgreg---' . $GalleryID);
    $cg_login_check =  cg_hash_function('---cgreg---'.$GalleryID);

    echo "<input type='hidden' name='cg_current_page_id' id='cg_current_page_id' value='$cg_current_page_id'>";
    echo "<input type='hidden' name='cg_check' id='cg_check' value='$cg_login_check'>";
    echo "<input type='hidden' name='action' value='post_cg_registry'>";// !important, otherwise wordpress post will not work!!!

    echo "<input type='hidden' name='cg_gallery_id_registry' id='cg_gallery_id_registry' value='$GalleryID'>";

    echo "<input type='hidden' id='cg_db_version' value='$galleryDbVersion'>";

    foreach ($selectUserForm as $key => $value) {

        $required = ($value->Required == 1) ? "*" : "";

        $cgCheckUsernameNicknameMail = '';

        if ($value->Field_Type == 'main-user-name' OR $value->Field_Type == 'main-nick-name' OR $value->Field_Type == 'main-mail') {
            $placeholder = contest_gal1ery_convert_for_html_output_without_nl2br($value->Field_Content);
            if ($value->Field_Type == 'main-user-name') {
                $cgContentField = "<input type='text' maxlength='" . $value->Max_Char . "' placeholder='$placeholder' class='cg_registry_form_field cg-" . $value->Field_Type . "' id='cg_registry_form_field" . $value->id . "' name='cg_Fields[$i][Field_Content]'>";
                $cgCheckUsernameNicknameMail = "id='cg_user_name_check_alert'";
            }
            if ($value->Field_Type == 'main-nick-name') {
                $cgContentField = "<input type='text' maxlength='" . $value->Max_Char . "' placeholder='$placeholder' class='cg_registry_form_field cg-" . $value->Field_Type . "' id='cg_registry_form_field" . $value->id . "' name='cg_Fields[$i][Field_Content]'>";
                $cgCheckUsernameNicknameMail = "id='cg_nick_name_check_alert'";
            }
            if ($value->Field_Type == 'main-mail') {
                $cgContentField = "<input type='text' placeholder='$placeholder' class='cg_registry_form_field cg-" . $value->Field_Type . "' id='cg_registry_form_field" . $value->id . "' name='cg_Fields[$i][Field_Content]'>";
                $cgCheckUsernameNicknameMail = "id='cg_mail_check_alert'";
            }
        }
        if ($value->Field_Type == 'password' OR $value->Field_Type == 'password-confirm') {
            $placeholder = contest_gal1ery_convert_for_html_output_without_nl2br($value->Field_Content);
            $cgContentField = "<input type='password' maxlength='" . $value->Max_Char . "' placeholder='$placeholder' autocomplete='off' class='cg_registry_form_field cg-" . $value->Field_Type . "' id='cg_registry_form_field" . $value->id . "' name='cg_Fields[$i][Field_Content]' readonly onfocus='this.removeAttribute(\"readonly\")';>";
        }
        if ($value->Field_Type == 'user-comment-field') {
            $placeholder = contest_gal1ery_convert_for_html_output_without_nl2br($value->Field_Content);
            $cgContentField = "<textarea maxlength='" . $value->Max_Char . "' placeholder='$placeholder' class='cg_registry_form_field cg-" . $value->Field_Type . "' id='cg_registry_form_field" . $value->id . "' name='cg_Fields[$i][Field_Content]' rows='6' ></textarea>";
        }

        if ($value->Field_Type == 'profile-image') {
            $placeholder = contest_gal1ery_convert_for_html_output_without_nl2br($value->Field_Content);
            $cgContentField = "<input type='file' class='cg_registry_form_field cg-" . $value->Field_Type . "' id='cg_registry_form_field" . $value->id . "' name='cg_input_image_upload_file[]'>";
        }

        if ($value->Field_Type == 'wpfn') {
            $placeholder = contest_gal1ery_convert_for_html_output_without_nl2br($value->Field_Content);
            $cgContentField = "<input maxlength='" . $value->Max_Char . "' type='text' placeholder='$placeholder' class='cg_registry_form_field cg-" . $value->Field_Type . "' id='cg_registry_form_field" . $value->id . "' name='cg_Fields[$i][Field_Content]'>";
        }

        if ($value->Field_Type == 'wpln') {
            $placeholder = contest_gal1ery_convert_for_html_output_without_nl2br($value->Field_Content);
            $cgContentField = "<input maxlength='" . $value->Max_Char . "' type='text' placeholder='$placeholder' class='cg_registry_form_field cg-" . $value->Field_Type . "' id='cg_registry_form_field" . $value->id . "' name='cg_Fields[$i][Field_Content]'>";
        }

        if ($value->Field_Type == 'user-text-field') {
            $placeholder = contest_gal1ery_convert_for_html_output_without_nl2br($value->Field_Content);
            $cgContentField = "<input maxlength='" . $value->Max_Char . "' type='text' placeholder='$placeholder' class='cg_registry_form_field cg-" . $value->Field_Type . "' id='cg_registry_form_field" . $value->id . "' name='cg_Fields[$i][Field_Content]'>";
        }

        if ($value->Field_Type == 'user-html-field') {
            $content = contest_gal1ery_convert_for_html_output($value->Field_Content);
            $cgContentField = "<div class='cg-" . $value->Field_Type . "'>$content</div>";
        }


        if ($value->Field_Type == 'user-robot-field') {
            echo "<div class='cg_form_div cg_captcha_not_a_robot_field_class' id='cg_captcha_not_a_robot_registry_field'>";
            echo "<div>";
        } else {
            echo "<div id='cg-registry-" . $value->Field_Order . "' class='cg_form_div'>";
        }


        if ($value->Field_Type != 'user-html-field' && $value->Field_Type != 'user-robot-recaptcha-field') {

            if ($value->Field_Type == 'user-robot-field') {
                echo "<label for='cg_" . $cg_login_check . "_registry' >".contest_gal1ery_convert_for_html_output_without_nl2br($value->Field_Name)." *</label>";
            } else {
                echo "<label for='cg_registry_form_field" . $value->id . "' >" .contest_gal1ery_convert_for_html_output_without_nl2br($value->Field_Name) . " $required</label>";
            }

            echo "<input type='hidden' name='cg_Fields[$i][Form_Input_ID]' value='" . $value->id . "'>";
            echo "<input type='hidden' name='cg_Fields[$i][Field_Type]' value='" . $value->Field_Type . "'>";
            echo "<input type='hidden' name='cg_Fields[$i][Field_Order]' value='" . $value->Field_Order . "'>";

        }


        // Pr�fen ob check-agreement-feld ist ansonsten Text oder, Comment Felder anzeigen
        if ($value->Field_Type == 'user-check-agreement-field') {

            $cgCheckContent = contest_gal1ery_convert_for_html_output($value->Field_Content);
            echo "<div class='cg-check-agreement-container'>";
            echo "<div class='cg-check-agreement-checkbox'>";
            echo "<input type='checkbox' id='cg_registry_form_field" . $value->id . "' class='cg_check_f_checkbox' value='checked' name='cg_Fields[$i][Field_Content]'>";
            echo "<input type='hidden' class='cg_form_required' value='" . $value->Required . "'>";// Pr�fen ob Pflichteingabe
            echo "</div>";
            echo "<div class='cg-check-agreement-html'>";
            echo $cgCheckContent;
            echo "</div>";
            echo "</div>";

        } else {

            if ($value->Field_Type == 'user-select-field') {

                $textAr = explode("\n", $value->Field_Content);// sanitazing happens after that in foreach

                echo "<select name='cg_Fields[$i][Field_Content]' class='cg-" . $value->Field_Type . "' id='cg_registry_form_field" . $value->id . "' name='cg_Fields[$i][Field_Content]' >";

                echo "<option value=''>$language_pleaseSelect</option>";

                foreach ($textAr as $optionKey => $optionValue) {

                    $optionValue = sanitize_text_field(contest_gal1ery_convert_for_html_output_without_nl2br($optionValue));
                    echo "<option value='$optionValue'>$optionValue</option>";

                }

                echo "</select>";
                echo "<input type='hidden' class='cg_form_required' value='" . $value->Required . "'>";// Pr�fen ob Pflichteingabe
            } else if ($value->Field_Type == 'user-robot-field') {

                // NICHT ENTFERNEN!!!!
                // Wichtig!!! Empty if clausel muss hier bleiben beim aktullen Aufbau sonst verschieben sich Felder.

            } else if ($value->Field_Type == 'user-robot-recaptcha-field') {

                // NICHT ENTFERNEN!!!!
                // Wichtig!!! Empty if clausel muss hier bleiben beim aktullen Aufbau sonst verschieben sich Felder.

                if(!empty($GalleryID)){
                    $GalleryIDrecaptcha = $GalleryID;
                }else{
                    $GalleryIDrecaptcha = 'v14';
                }

                echo "<div class='cg_recaptcha_reg_form' id='cgRecaptchaRegForm$GalleryIDrecaptcha'>";

                echo "</div>";
                echo "<p class='cg_input_error cg_hide cg_recaptcha_not_valid_reg_form_error' id='cgRecaptchaNotValidRegFormError$GalleryIDrecaptcha'></p>";

                ?>

                <script type="text/javascript">
                    var ReCaKey = "<?php echo $value->ReCaKey; ?>";
                    var cgRecaptchaNotValidRegFormError = "<?php echo 'cgRecaptchaNotValidRegFormError' . $GalleryIDrecaptcha . ''; ?>";
                    var cgRecaptchaRegForm = "<?php echo 'cgRecaptchaRegForm' . $GalleryIDrecaptcha . ''; ?>";

                    var cgRecaptchaCallbackRegistryFormRendered = false;

                    if(typeof cgRecaptchaCallbackRendered == 'undefined'){

                        cgRecaptchaCallbackRendered = true;
                        cgRecaptchaCallbackRegistryFormRendered = true;

                        var cgCaRoReRegCallback = function () {
                            var element = document.getElementById(cgRecaptchaNotValidRegFormError);
                            //element.parentNode.removeChild(element);
                            element.classList.remove("cg_recaptcha_not_valid_reg_form_error");
                            element.classList.add("cg_hide");
                        };

                        if(typeof cgRecaptchaFormNormalRendered == 'undefined'){
                            cgRecaptchaFormNormalRendered = true;
                            var cgOnloadRegCallback = function () {
                                grecaptcha.render(cgRecaptchaRegForm, {
                                    'sitekey': ReCaKey,
                                    'callback': 'cgCaRoReRegCallback'
                                });
                            };
                        }

                    }


                </script>
                <script src="https://www.google.com/recaptcha/api.js?onload=cgOnloadRegCallback&render=explicit&hl=<?php echo $value->ReCaLang; ?>"
                        async defer>
                </script>

                <?php

            } else {

                echo $cgContentField;
                if ($value->Field_Type != 'user-html-field') {
                    echo "<input type='hidden' class='cg_Min_Char' value='" . $value->Min_Char . "'>"; // Pr�fen minimale Anzahl zeichen
                    echo "<input type='hidden' class='cg_Max_Char' value='" . $value->Max_Char . "'>"; // Pr�fen maximale Anzahl zeichen
                    echo "<input type='hidden' class='cg_form_required' value='" . $value->Required . "'>";// Pr�fen ob Pflichteingabe
                }

            }

        }

        if ($value->Field_Type != 'user-robot-recaptcha-field' && $value->Field_Type != 'user-robot-field') {
            echo "<p class='cg_input_error cg_hide' $cgCheckUsernameNicknameMail></p>";// Fehlermeldung erscheint hier
        }

        if ($value->Field_Type == 'user-robot-field') {
            echo "</div>";
            echo "<p class='cg_input_error cg_hide' $cgCheckUsernameNicknameMail></p>";// Fehlermeldung erscheint hier
        }

        echo "</div>";

        $i++;

    }

    echo "<div id='cg_registry_submit_container' class='cg_form_upload_submit_div cg_form_div'>";
    echo '<input type="submit" name="cg_registry_submit" id="cg_users_registry_check" class="cg_form_upload_submit" value="' . $language_sendRegistry . '">';
    echo "<p class='cg_input_error cg_hide' id='cg_registry_manipulation_error'></p>";
    echo '<div class="cg_form_div_image_upload_preview_loader_container cg_hide"><div class="cg_form_div_image_upload_preview_loader cg-lds-dual-ring-gallery-hide cg-lds-dual-ring-gallery-hide-mainCGallery"></div></div>';
    echo "</div>";
    echo '</form>';

    if(isset($isForAjax)){
        echo "<div class='cgConfirmationTextAfterRegistry cg_hide' data-cg-gid='$galeryIDuser'>
$ForwardAfterRegText
</div>";
    }

    echo "</div>";

    if(isset($isForAjax)){
        echo "<hr class='mainCGdivUploadFormAjaxRegistryButtonDivider'>";
        echo "<div class='cgLoginFormButton cgLoginFormButtonGallery  cgLoginFormButtonAjaxForm $cgFeControlsStyle $BorderRadiusClass' data-cg-gid='$galeryIDuser'><span>Sign in</span></div>";
    }


    echo "<div id='cg_user_registry_div_messages' style='height:0;visibility: hidden;'>";
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
    echo "<input type='hidden' id='cg_check_email_upload' value='$language_EmailAddressHasToBeValid'>";
    echo "<input type='hidden' id='cg_min_characters_text' value='$language_MinAmountOfCharacters'>";
    echo "<input type='hidden' id='cg_max_characters_text' value='$language_MaxAmountOfCharacters'>";
    echo "<input type='hidden' id='cg_no_picture_is_choosed' value='$language_ChooseYourImage'>";


    echo "<input type='hidden' id='cg_language_BulkUploadQuantityIs' value='$language_BulkUploadQuantityIs'>";
    echo "<input type='hidden' id='cg_language_BulkUploadLowQuantityIs' value='$language_BulkUploadLowQuantityIs'>";

    echo "<input type='hidden' id='cg_language_BulkUploadLowQuantityIs' value='$language_BulkUploadLowQuantityIs'>";
    echo "<input type='hidden' id='cg_language_ThisMailAlreadyExists' value='$language_ThisMailAlreadyExists'>";
    echo "<input type='hidden' id='cg_language_ThisNicknameAlreadyExists' value='$language_ThisNicknameAlreadyExists'>";
    echo "<input type='hidden' id='cg_language_ThisUsernameAlreadyExists' value='$language_ThisUsernameAlreadyExists'>";

    echo "<input type='hidden' id='cg_language_PleaseFillOut' value='$language_PleaseFillOut'>";
    echo "<input type='hidden' id='cg_language_youHaveNotSelected' value='$language_youHaveNotSelected'>";

    echo "<input type='hidden' id='cg_language_PasswordsDoNotMatch' value='$language_PasswordsDoNotMatch'>";
    echo "<input type='hidden' id='cg_language_ChooseYourImage' value='$language_ChooseYourImage'>";

    echo "<input type='hidden' id='cg_language_pleaseConfirm' value='$language_pleaseConfirm'>";
    echo "<input type='hidden' id='cg_language_ThisFileTypeIsNotAllowed' value='$language_ThisFileTypeIsNotAllowed'>";
    echo "<input type='hidden' id='cg_language_TheFileYouChoosedIsToBigMaxAllowedSize' value='$language_TheFileYouChoosedIsToBigMaxAllowedSize'>";

    echo "<input type='hidden' id='cg_users_registry_check_submit_language' value='$language_sendRegistry'>";

    echo "</div>";
    echo "</div>";

}


?>