<?php

echo "<div id='mainCGdivLostPasswordResetContainer' class='mainCGdivUploadFormContainer mainCGdivLostPasswordContainer'>";
echo "<div class='cg_form_div' id='cgLostPasswordNewContainer'>";
echo "<label for='cgLostPasswordNew'>$language_NewPassword</label>";
echo "<input type='password'  id='cgLostPasswordNew' name='cgLostPasswordNew'>";
echo "<input type='hidden'  id='cgResetPasswordWpUserID' value='$cgResetPasswordWpUserID'>";
echo "<p id='cgLostPasswordNewValidationMessage' class='cg_input_error cg_hide' ></p>";// Fehlermeldung erscheint hier
echo "</div>";
echo "<div class='cg_form_div' id='cgLostPasswordNewRepeatContainer'>";
echo "<label for='cgLostPasswordNewRepeat'>$language_NewPasswordRepeat</label>";
echo "<input type='password'  id='cgLostPasswordNewRepeat' name='cgLostPasswordNewRepeat'>";
echo "<p id='cgLostPasswordNewRepeatValidationMessage' class='cg_input_error cg_hide' ></p>";// Fehlermeldung erscheint hier
echo "</div>";
echo "<div class='cg_form_upload_submit_div cg_form_div' >";
echo '<input type="submit" name="submit" id="cgLostPasswordNewSend" value="'.$language_Send.'">';
echo "<p id='cgLostPasswordPasswordsDoNotMatch' class='cg_input_error cg_hide' ></p>";// Fehlermeldung erscheint hier
echo "<p id='cgLostPasswordUrlIsNotValidAnymore' class='cg_input_error cg_hide' ></p>";// Fehlermeldung erscheint hier

echo '<div class="cg_form_div_image_upload_preview_loader_container cg_hide"><div class="cg_form_div_image_upload_preview_loader cg-lds-dual-ring-gallery-hide cg-lds-dual-ring-gallery-hide-mainCGallery"></div></div>';
echo "</div>";
echo "</div>";// mainCGdivUploadFormContainer close

?>