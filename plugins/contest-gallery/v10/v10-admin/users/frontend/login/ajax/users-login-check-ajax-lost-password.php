<?php

$tablename_registry_and_login_options = $wpdb->prefix . "contest_gal1ery_registry_and_login_options";
$tablenameWpUsers = $wpdb->base_prefix . "users";

$registryAndLoginOptions = $wpdb->get_row( "SELECT * FROM $tablename_registry_and_login_options WHERE GeneralID = '1'" );

//if(false){
if(true){
    // if($registryAndLoginOptions->LostPasswordMailActive==1){

    $cgLostPasswordEmail = sanitize_text_field($_REQUEST['cgLostPasswordEmail']);

    // Check if valid mail. Wenn nicht dann admin Mail nehmen.
    if (!is_email($cgLostPasswordEmail)) {
        ?>
        <script data-cg-processing="true">
            var cg_language_EmailAddressHasToBeValid = document.getElementById("cg_language_EmailAddressHasToBeValid").value;
            var cgLostPasswordEmailValidationMessage = document.getElementById('cgLostPasswordEmailValidationMessage');
            cgLostPasswordEmailValidationMessage.innerHTML = cg_language_EmailAddressHasToBeValid;
            cgLostPasswordEmailValidationMessage.classList.remove('cg_hide');
        </script>
        <?php
        return;
    }

    $wpUserID = $wpdb->get_var("SELECT ID FROM $tablenameWpUsers WHERE user_email = '".$cgLostPasswordEmail."'");

    if(!empty($wpUserID)){

        update_user_meta( $wpUserID, 'cgLostPasswordMailTimestamp', time());

        $LostPasswordMailAddressor = contest_gal1ery_convert_for_html_output_without_nl2br($registryAndLoginOptions->LostPasswordMailAddressor);
        $LostPasswordMailReply = contest_gal1ery_convert_for_html_output_without_nl2br($registryAndLoginOptions->LostPasswordMailReply);
        $LostPasswordMailSubject = contest_gal1ery_convert_for_html_output_without_nl2br($registryAndLoginOptions->LostPasswordMailSubject);
        $LostPasswordMailConfirmation = contest_gal1ery_convert_for_html_output($registryAndLoginOptions->LostPasswordMailConfirmation);

        $cgLostPasswordSiteUrl = sanitize_text_field($_REQUEST['cgLostPasswordSiteUrl']);

        $headers = array();
        $headers[] = "From: " . html_entity_decode(strip_tags($LostPasswordMailAddressor)) . " <" . strip_tags($LostPasswordMailReply) . ">";
        $headers[] = "Reply-To: " . strip_tags($LostPasswordMailReply) . "";
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-Type: text/html; charset=utf-8";

        $posUrl = '$resetpasswordurl$';

        // eventuell über user meta link gültigkeitsdauer speichern

        if (stripos($LostPasswordMailConfirmation, $posUrl) !== false) {
            $cgLostPasswordSiteUrl = (strpos($cgLostPasswordSiteUrl, '?')) ? $cgLostPasswordSiteUrl . '&' : $cgLostPasswordSiteUrl . '?';
            $LostPasswordMailConfirmation = str_ireplace($posUrl, $cgLostPasswordSiteUrl . "cgResetPassword=$wpUserID#cgResetPassword", $LostPasswordMailConfirmation);
        } else {
            ?>
            <script data-cg-processing="true">
                var cg_language_LoginAndPasswordDoNotMatch = document.getElementById("cgLostPasswordEmailValidationMessage").value;
                var cgLostPasswordEmailValidationMessage = document.getElementById('cgLostPasswordEmailValidationMessage');
                cgLostPasswordEmailValidationMessage.innerHTML = 'Lost password e-mail could not be send, required parameter not set. Please contact administrator.';
                cgLostPasswordEmailValidationMessage.classList.remove('cg_hide');
            </script>
            <?php
            return;
        }
        global $cgMailAction;
        global $cgMailGalleryId;
        $cgMailAction = "Lost password e-mail";
        $cgMailGalleryId = $GalleryID;
        add_action('wp_mail_failed', 'cg_on_wp_mail_error', 10, 1);

        if (!wp_mail($cgLostPasswordEmail, $LostPasswordMailSubject, $LostPasswordMailConfirmation, $headers)) {
            ?>
            <script data-cg-processing="true" >
                var cg_language_LoginAndPasswordDoNotMatch = document.getElementById("cgLostPasswordEmailValidationMessage").value;
                var cgLostPasswordEmailValidationMessage = document.getElementById('cgLostPasswordEmailValidationMessage');
                cgLostPasswordEmailValidationMessage.innerHTML = 'Lost password e-mail could not be send. wp_mail not worked. Please contact administrator.';
                cgLostPasswordEmailValidationMessage.classList.remove('cg_hide');
            </script>
            <?php
        }else{
            ?>
            <script data-cg-processing="true" data-cg-processing-success="true">
                var mainCGdivLostPasswordContainer = document.getElementById('mainCGdivLostPasswordContainer');
                mainCGdivLostPasswordContainer.classList.add('cg_hide');
                var mainCGdivLostPasswordEmailSentContainer = document.getElementById('mainCGdivLostPasswordEmailSentContainer');
                mainCGdivLostPasswordEmailSentContainer.classList.remove('cg_hide');
            </script>
            <?php
        }

    }else{
        ?>
        <script data-cg-processing="true"  data-cg-processing-success="true">
            var mainCGdivLostPasswordContainer = document.getElementById('mainCGdivLostPasswordContainer');
            mainCGdivLostPasswordContainer.classList.add('cg_hide');
            var mainCGdivLostPasswordEmailSentContainer = document.getElementById('mainCGdivLostPasswordEmailSentContainer');
            mainCGdivLostPasswordEmailSentContainer.classList.remove('cg_hide');
        </script>
        <?php
    }

}else{
    ?>
    <script data-cg-processing="true">
        var cg_language_LoginAndPasswordDoNotMatch = document.getElementById("cgLostPasswordEmailValidationMessage").value;
        var cgLostPasswordEmailValidationMessage = document.getElementById('cgLostPasswordEmailValidationMessage');
        cgLostPasswordEmailValidationMessage.innerHTML = 'Lost password e-mail could not be send. Lost Password reset option is not activated.';
        cgLostPasswordEmailValidationMessage.classList.remove('cg_hide');
    </script>
    <?php
}

return;

?>