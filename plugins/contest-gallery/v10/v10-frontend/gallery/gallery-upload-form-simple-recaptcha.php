<?php
echo "<div class='cg_form_div cg_captcha_not_a_robot_field_in_gallery' data-cg-gid='$galeryIDuserForJs' >";
echo "<div>";
echo "<label for='cg_$uniqueIdAddition' >".sanitize_text_field(contest_gal1ery_convert_for_html_output_without_nl2br($fieldSimpleRecaptcha['Field_Content']['titel']))." *</label>";
echo "</div>";
echo "<p class='cg_input_error cg_hide'></p>";
echo "</div>";

?>