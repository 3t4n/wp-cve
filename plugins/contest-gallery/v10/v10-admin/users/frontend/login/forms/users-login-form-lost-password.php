<?php

$cgLostPasswordSiteUrl = get_permalink();
echo "<div id='mainCGdivLostPasswordContainer' class='mainCGdivUploadFormContainer mainCGdivLostPasswordContainer cg_hide'>";
echo "<p id='mainCGdivLostPasswordExplanation'>$language_LostPasswordExplanation</p>";
echo "<div class='cg_form_div' id='cgLostPasswordEmailContainer'>";
echo "<label for='cgLostPasswordEmail'>$language_Email</label>";
echo "<input type='text'  id='cgLostPasswordEmail' name='cgLostPasswordEmail'>";
echo "<input type='hidden'  id='cgLostPasswordSiteUrl' value='$cgLostPasswordSiteUrl'>";
echo "<p id='cgLostPasswordEmailValidationMessage' class='cg_input_error cg_hide' ></p>";// Fehlermeldung erscheint hier

echo "</div>";
echo "<div class='cg_form_upload_submit_div cg_form_div' >";
echo '<input type="submit" name="submit" id="cgLostPasswordEmailSend" value="'.$language_Send.'">';
echo '<div class="cg_form_div_image_upload_preview_loader_container cg_hide"><div class="cg_form_div_image_upload_preview_loader cg-lds-dual-ring-gallery-hide cg-lds-dual-ring-gallery-hide-mainCGallery"></div></div>';
echo "</div>";
echo "<div><a href='' class='cgLostPasswordBackToLoginFormButton'>$language_BackToLoginForm</a></div>";
echo "</div>";// mainCGdivUploadFormContainer close

echo "<div id='mainCGdivLostPasswordEmailSentContainer' class='mainCGdivUploadFormContainer mainCGdivLostPasswordContainer cg_hide'>";
echo "<p id='mainCGdivLostPasswordEmailSentExplanation'>$language_EmailLostPasswordSent</p>";
echo "<div><a href='' class='cgLostPasswordBackToLoginFormButton'>$language_BackToLoginForm</a></div>";
echo "</div>";// mainCGdivUploadFormContainer close

?>