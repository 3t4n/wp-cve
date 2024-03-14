<?php
if (!defined('ABSPATH')) {
    exit;
}

    $_POST = cg1l_sanitize_post($_POST);

    global $wpdb;

    $tablenameCreateUserForm = $wpdb->prefix . "contest_gal1ery_create_user_form";
    $tablenameCreateUserEntries = $wpdb->prefix . "contest_gal1ery_create_user_entries";
    $tablename_options = $wpdb->prefix . "contest_gal1ery_options";
    $tablenameProOptions = $wpdb->prefix . "contest_gal1ery_pro_options";
    $tablenameWpUsers = $wpdb->base_prefix . "users";
    $tablenameWpUserMeta = $wpdb->base_prefix . "usermeta";
    $tablename_options_visual = $wpdb->prefix . "contest_gal1ery_options_visual";
    $tablename_registry_and_login_options = $wpdb->prefix."contest_gal1ery_registry_and_login_options";

    if(intval($galleryDbVersion)>=14){
        $proOptions = $wpdb->get_row("SELECT * FROM $tablenameProOptions WHERE GeneralID = '1'");
    }else{
        $proOptions = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tablenameProOptions WHERE GalleryID = %d",[$GalleryID]));
    }

    if(intval($galleryDbVersion)>=14){
        $RegistryUserRole = $wpdb->get_var( "SELECT RegistryUserRole FROM $tablename_registry_and_login_options WHERE GeneralID = 1");
    }else{
        $RegistryUserRole = $wpdb->get_var($wpdb->prepare("SELECT RegistryUserRole FROM $tablename_options WHERE id=%d",[$GalleryID]));
    }

    include(__DIR__ . "/../../../../../check-language.php");

    $cg_check = $_POST['cg_Fields'];

    // Validierung und Erstellung von Activation Key
    foreach ($cg_check as $key => $value) {

        if ($value["Field_Type"] == "password") {
            $password = sanitize_text_field($value["Field_Content"]);
            $activation_key = md5(time() . $password);
        }

        if ($value["Field_Type"] == "password-confirm") {
            $passwordConfirm = sanitize_text_field($value["Field_Content"]);
        }

        if ($value["Field_Type"] == "main-mail") {
            $cg_main_mail = sanitize_text_field($value["Field_Content"]);
            $checkWpIdViaMail = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $tablenameWpUsers WHERE user_email = %s",[$cg_main_mail]));

        }

        if ($value["Field_Type"] == "main-user-name") {
            $cg_main_user_name = sanitize_text_field($value["Field_Content"]);
            $checkWpIdViaName = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $tablenameWpUsers WHERE user_login = %s OR 
				user_nicename = %s OR display_name = %s",[$cg_main_user_name,$cg_main_user_name,$cg_main_user_name]));
        }

        if ($value["Field_Type"] == "main-nick-name") {
            $cg_main_nick_name = sanitize_text_field($value["Field_Content"]);
        }

    }

    if ($password != $passwordConfirm) {
        ?>
        <script  data-cg-processing="true">
            var cg_error = "Please don't manipulate the registry Code:221";
            var cg_registry_manipulation_error = document.getElementById('cg_registry_manipulation_error');
            cg_registry_manipulation_error.innerHTML = cg_registry_manipulation_error.innerHTML + cg_error;
            cg_registry_manipulation_error.classList.remove("cg_hide");
            console.log(cg_error);
        </script>
        <?php
        die;
    }

    if ($checkWpIdViaMail) {
        ?>
        <script  data-cg-processing="true">
            var cg_error =  "Please don't manipulate the registry Code:222";
            var cg_registry_manipulation_error = document.getElementById('cg_registry_manipulation_error');
            cg_registry_manipulation_error.innerHTML = cg_registry_manipulation_error.innerHTML + cg_error;
            cg_registry_manipulation_error.classList.remove("cg_hide");
            console.log(cg_error);
        </script>
        <?php
        die;
    }
    if ($checkWpIdViaName) {
        ?>
        <script  data-cg-processing="true">
            var cg_error =  "Please don't manipulate the registry Code:223";
            var cg_registry_manipulation_error = document.getElementById('cg_registry_manipulation_error');
            cg_registry_manipulation_error.innerHTML = cg_registry_manipulation_error.innerHTML + cg_error;
            cg_registry_manipulation_error.classList.remove("cg_hide");
            console.log(cg_error);
        </script>
        <?php
        die;
    }
    if ($cg_main_mail == false) {
        ?>
        <script  data-cg-processing="true">
            var cg_error =  "Please don't manipulate the registry Code:224";
            var cg_registry_manipulation_error = document.getElementById('cg_registry_manipulation_error');
            cg_registry_manipulation_error.innerHTML = cg_registry_manipulation_error.innerHTML + cg_error;
            cg_registry_manipulation_error.classList.remove("cg_hide");
            console.log(cg_error);
        </script>
        <?php
        die;
    }
    if (is_email($cg_main_mail) == false) {
        ?>
        <script  data-cg-processing="true">
            var cg_error = <?php echo json_encode($language_EmailAddressHasToBeValid);?>;
            var cg_registry_manipulation_error = document.getElementById('cg_registry_manipulation_error');
            cg_registry_manipulation_error.innerHTML = cg_registry_manipulation_error.innerHTML + cg_error;
            cg_registry_manipulation_error.classList.remove("cg_hide");
            console.log(cg_error);
        </script>
        <?php
        die;
    }

    $posUrl = '$regurl$';
    $TextEmailConfirmation = contest_gal1ery_convert_for_html_output($proOptions->TextEmailConfirmation);

    if (stripos($TextEmailConfirmation, $posUrl) === false) {
        ?>
        <script  data-cg-processing="true">
            var cg_error = "Confirmation URL for e-mail can't be provided. Please contact Administrator";
            var cg_registry_manipulation_error = document.getElementById('cg_registry_manipulation_error');
            cg_registry_manipulation_error.innerHTML = cg_registry_manipulation_error.innerHTML + cg_error;
            cg_registry_manipulation_error.classList.remove("cg_hide");
            console.log(cg_error);
        </script>
        <?php
        die;
    }

    $passwordUnhashed = $password;
    $password = wp_hash_password($password);

    // Validierung und Erstellung von Activation Key --- ENDE

    // Einf�gen von Werten mit Kennzeichnung durch Activation Key zur sp�teren Wiederfindung

    $Tstamp = time();
    $attach_id = 0;

    foreach ($cg_check as $key => $value) {

        $Form_Input_ID = sanitize_text_field($value["Form_Input_ID"]);
        $Field_Type = sanitize_text_field($value["Field_Type"]);

        $Field_Order = sanitize_text_field($value["Field_Order"]);
        $Field_Content = sanitize_text_field((isset($value["Field_Content"]) ? $value["Field_Content"] : ''));

        if ($value["Field_Type"] == "password") {
            $Field_Content = $password;
        }
        if ($value["Field_Type"] == "password-confirm") {
            $Field_Content = $password;
        }
        if ($value["Field_Type"] == "profile-image") {
            if(!empty($_FILES) AND !empty($_FILES['cg_input_image_upload_file']) AND !empty($_FILES['cg_input_image_upload_file']['tmp_name']) AND !empty($_FILES['cg_input_image_upload_file']['tmp_name'][0])){
                $Field_Content = cg_registry_add_profile_image('cg_input_image_upload_file',0,true,true);
                $attach_id = $Field_Content;
            }else{
                $Field_Content = '';
            }
        }

        $Checked = 0;
        if ($Field_Type == 'user-check-agreement-field') {
            if ($Field_Content == 'checked') {
                $Checked = 1;
            } else {
                $Checked = 0;
            }
            // insert original checked field_content to show later!
            $Field_Content = $wpdb->get_row($wpdb->prepare("SELECT Field_Name, Field_Content, Required FROM $tablenameCreateUserForm WHERE id = %d",[$Form_Input_ID]));

            $RequiredString =  ($Field_Content->Required==1) ? 'yes' : 'no' ;
            $Field_Content = $Field_Content->Field_Name . ' --- required:' . $RequiredString . ' --- ' . $Field_Content->Field_Content;// get both in this case name and content for better documentation
        }

        $Version = cg_get_version_for_scripts();

        $GeneralID = 0;

        if(intval($galleryDbVersion)>=14){
            $GalleryID = 0;
            $GeneralID = 1;
        }

        $wpdb->query($wpdb->prepare(
            "
        INSERT INTO $tablenameCreateUserEntries
        (id, GalleryID, wp_user_id, f_input_id, Field_Type,
        Field_Content, activation_key, Checked, Version,GeneralID,Tstamp)
        VALUES (%s,%d,%d,%d,%s,
        %s,%s,%d,%s,%d,%d)
    ",
            '', $GalleryID, 0, $Form_Input_ID, $Field_Type,
            $Field_Content, $activation_key, $Checked, $Version,$GeneralID,$Tstamp
        ));

    }

    // Einf�gen von Werten mit Kennzeichnung durch Activation Key zur sp�teren Wiederfindung --- ENDE

    // Versand E-Mail mit confirmation Link

    // Check if valid mail. Wenn nicht dann admin Mail nehmen.
    if (is_email($proOptions->RegMailReply)) {
        $cgReply = $proOptions->RegMailReply;
    } else {
        $cgReply = get_option('admin_email');
    }

    $headers = array();
    $headers[] = "From: " . html_entity_decode(strip_tags($proOptions->RegMailAddressor)) . " <" . strip_tags($cgReply) . ">";
    $headers[] = "Reply-To: " . strip_tags($cgReply) . "";
    $headers[] = "MIME-Version: 1.0";
    $headers[] = "Content-Type: text/html; charset=utf-8";

    $ForwardAfterRegText = nl2br(html_entity_decode(stripslashes($proOptions->ForwardAfterRegText)));

    $currentPageUrlForEmail = (strpos($currentPageUrl, '?')) ? $currentPageUrl . '&' : $currentPageUrl . '?';
    $TextEmailConfirmation = str_ireplace($posUrl, $currentPageUrlForEmail . "cgkey=$activation_key#cg_activation", $TextEmailConfirmation);

    global $cgMailAction;
    global $cgMailGalleryId;
    $cgMailAction = "User registration e-mail";
    $cgMailGalleryId = $GalleryID;
    add_action('wp_mail_failed', 'cg_on_wp_mail_error', 10, 1);

    if (!wp_mail($cg_main_mail, contest_gal1ery_convert_for_html_output($proOptions->RegMailSubject), $TextEmailConfirmation, $headers)) {
        ?>
        <script  data-cg-processing="true">
            var cg_error = "Failed sending mail, please contact administrator";
            var cg_registry_manipulation_error = document.getElementById('cg_registry_manipulation_error');
            cg_registry_manipulation_error.innerHTML = cg_registry_manipulation_error.innerHTML + cg_error;
            cg_registry_manipulation_error.classList.remove("cg_hide");
            console.log(cg_error);
        </script>
        <?php
        die;
    }

    // $activation_key has definetely to be set to run it here!!!
    if($proOptions->RegMailOptional==1  && !empty($activation_key)){
        $user_registered = date("Y-m-d H:i:s");

        if(!empty($cg_main_nick_name)){
            $display_name=$cg_main_nick_name;
            $user_nicename=$cg_main_nick_name;
        }else{
            $display_name=$cg_main_user_name;
            $user_nicename=$cg_main_user_name;
        }

        $user_login=$cg_main_user_name;
        $user_email=$cg_main_mail;

        $activation_key_for_wp_users_table = $activation_key.'-unconfirmed';

        if(intval($galleryDbVersion)>=14){
            $activation_key_for_wp_users_table = 'cg-key---'.$activation_key.'-unconfirmed';
        }

        $wpdb->query( $wpdb->prepare(
            "
									INSERT INTO $tablenameWpUsers
									( id, user_login, user_pass, user_nicename, user_email, user_url,
									user_registered, user_activation_key, user_status, display_name)
									VALUES (%s,%s,%s,%s,%s,%s,
									%s,%s,%d,%s)
								",
            '',$user_login,$password,$user_nicename,$user_email,'',
            $user_registered,$activation_key_for_wp_users_table,'',$display_name
        ) );

        $newWpId = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $tablenameWpUsers WHERE user_activation_key=%s",[$activation_key_for_wp_users_table]));

        if(!empty($newWpId)){
            // set role here
            wp_update_user( array( 'ID' => $newWpId, 'role' => $RegistryUserRole ) );

            if(intval($galleryDbVersion)>=14){
                cg_update_user_meta_when_register( $newWpId, $activation_key ) ;
            }

            if(!empty($attach_id)){
                cg_registry_add_profile_image('cg_input_image_upload_file',$newWpId,false,false,$attach_id);
            }

        }

        // set user id here by activation key, because created!!!
        $wpdb->update(
            "$tablenameCreateUserEntries",
            array('wp_user_id' => $newWpId),
            array('activation_key' => $activation_key),
            array('%d'),
            array('%s')
        );

        if(intval($galleryDbVersion)>=14){
            // new logic, delete all fields not only passwords, because all user data are in wp_usermeta then
            $wpdb->query($wpdb->prepare(
                "
                                    DELETE FROM $tablenameCreateUserEntries WHERE GeneralID = %d AND wp_user_id = %d
                                ",
                1,$newWpId
            ));
        }else{
            // HASHED PASSWORDS CAN BE DELETED THEN!!!!
            $wpdb->query( $wpdb->prepare(
                "
										DELETE FROM $tablenameCreateUserEntries WHERE GalleryID = %d AND (Field_Type = %s OR Field_Type = %s) AND wp_user_id = %s
									",
                $GalleryID, "password", "password-confirm",$newWpId
            ));
        }

        wp_set_auth_cookie( $newWpId,true );

        $url = $currentPageUrl.'?cg_gallery_id_registry='.$GalleryID.'&cg_login_user_after_registration=true&cg_activation_key='.$activation_key;

        // if RegMailOptional and direct login after registration!!!
        ?>
        <script  data-cg-processing="true">

            var url = <?php echo json_encode($url);?>;
            window.location = url;

        </script>
        <?php
        die;

    }else{

        $url = $currentPageUrl.'?cg_gallery_id_registry='.$GalleryID.'&cg_forward_user_after_reg=true';

        // show only ForwardAfterRegText, no login
        ?>
        <script  data-cg-processing="true">

            var url = <?php echo json_encode($url);?>;
            window.location = url;

        </script>
        <?php
        die;

    }

?>