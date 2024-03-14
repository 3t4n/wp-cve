<?php

echo "<div style='visibility: hidden;'  id='mainCGdivLoginFormContainer' class='mainCGdivUploadFormContainer $cgResetPasswordLinkHideLoginFormClass'>";
echo "<p id='mainCGdivResetPasswordSuccessfullyExplanation' class='cg_hide'>$language_ResetPasswordSuccessfully</p>";
echo "<input type='hidden' id='cg_check_mail_name_value_for_login' value='0'>";
echo "<input type='hidden' id='cg_site_url' value='".get_site_url()."'/>";
echo "<input type='hidden' id='cg_gallery_id_login' value='$GalleryID'/>";
if(!empty($isFromStaticLoginForm)){
    echo "<input type='hidden' id='cg_ForwardAfterLoginUrlCheck' value='$ForwardAfterLoginUrlCheck'/>";
    echo "<input type='hidden' id='cg_ForwardAfterLoginUrl' value='$ForwardAfterLoginUrl'/>";
}
echo "<span id='cg_user_registry_anchor'></span>";
echo '<form action="" method="post" id="cg_user_login_form">';
echo "<input type='hidden' name='cg_login_check' id='cg_login_check' value='".$cg_login_check."'>";
echo "<div id='cg-login-1' class='cg_form_div'>";
echo "<label for='cg_login_name_mail'>$language_UsernameOrEmail</label>";
echo "<input type='text'  id='cg_login_name_mail' name='cg_login_name_mail'>";
echo "<p id='cg_append_login_name_mail_fail' class='cg_input_error cg_hide' ></p>";// Fehlermeldung erscheint hier
echo "</div>";
echo "<div id='cg-login-2' class='cg_form_div'>";
echo "<label for='cg_login_password'>$language_Password</label>";
echo "<input type='password'  id='cg_login_password' name='cg_login_password'>";
echo "<p id='cg_append_login_password_fail' class='cg_input_error cg_hide' ></p>";// Fehlermeldung erscheint hier
echo "</div>";
echo "<div class='cg_form_upload_submit_div cg_form_div' >";
echo '<input type="submit" name="submit" id="cg_user_login_check" value="'.$language_sendLogin.'">';
echo "<p id='cg_append_validation_system_fail' class='cg_input_error cg_hide' ></p>";// Fehlermeldung erscheint hier
//echo "<p id='cg_append_email_and_password_do_not_match' class='cg_input_error cg_hide' ></p>";// Fehlermeldung erscheint hier
echo "<p id='cg_append_login_and_password_do_not_match' class='cg_input_error cg_hide' ></p>";// Fehlermeldung erscheint hier
echo '<div class="cg_form_div_image_upload_preview_loader_container cg_hide"><div class="cg_form_div_image_upload_preview_loader cg-lds-dual-ring-gallery-hide cg-lds-dual-ring-gallery-hide-mainCGallery"></div></div>';
echo "</div>";
echo '</form>';

if($LostPasswordMailActive==1){
    echo "<div><a href='' id='cgLostPasswordLinkButton'>$language_LostPassword</a></div>";
}

echo "</div>";// mainCGdivUploadFormContainer close

?>