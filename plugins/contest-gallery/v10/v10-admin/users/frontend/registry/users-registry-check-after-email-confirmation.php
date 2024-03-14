<?php
if (!defined('ABSPATH')) {
    exit;
}

$tablenameWpUsers = $wpdb->base_prefix . "users";
$tablenameWpUserMeta = $wpdb->base_prefix . "usermeta";
$tablenameCreateUserEntries = $wpdb->prefix . "contest_gal1ery_create_user_entries";

$cgkey = sanitize_text_field($_GET["cgkey"]);

$cgkeyForWpUserTable = $cgkey;

if(strpos($cgkey,'-confirmed')!==false OR strpos($cgkey,'-unconfirmed')!==false){// then somebody must try to manipulate
    return;
}
$checkUserViaKey = $wpdb->get_row("SELECT * FROM $tablenameWpUsers WHERE user_activation_key LIKE '%$cgkey%'");

// then this user was directly registered after login, check if user exists, if exists then TextAfterEmailConfirmation can be always shown
// simply processing then, create_user_entries were already processed then
// must $cgkey-unconfirmed as user_activation_key in database
if (!empty($checkUserViaKey)) {

    // activation key can be emptied then, user confirmed then
    // in case that account was created right after registration empty activation_key in $tablenameCreateUserEntries
    $wpdb->update(
        "$tablenameCreateUserEntries",
        array('activation_key' => ''),
        array('activation_key' => $cgkey),
        array('%s'),
        array('%s')
    );

    if(intval($galleryDbVersion)>=14){
        $cgkeyForWpUserTable = 'cg-key---'.$cgkey;
    }

    // '-confirmed' was added in update 10.9.8.8.0
    // has to done with prepare because of LIKE syntax!
    $wpdb->query($wpdb->prepare(
        "
				UPDATE $tablenameWpUsers SET user_activation_key = %s WHERE user_activation_key LIKE %s
			",
        $cgkeyForWpUserTable."-confirmed","%$cgkey%"
    ));

    echo "<div id='cg_activation'  class='mainCGdivUploadForm mainCGdivUploadFormStatic $FeControlsStyleRegistry $BorderRadiusRegistry' >";
    echo "<p>";
    echo nl2br(html_entity_decode(stripslashes($pro_options->TextAfterEmailConfirmation)));
    echo "</p>";
    echo "</div>";

    ?>

    <script>

        setTimeout(function (){
            jQuery("html, body").animate({ scrollTop: jQuery('#cg_activation').offset().top-60}, 0);
        },100);

        window.history.replaceState({}, document.title, location.protocol + '//' + location.host + location.pathname);


    </script>

    <?php

} else {// then user is still not confirmed if exists

    $userAccountEntries = $wpdb->get_results("SELECT Field_Type, Field_Content FROM $tablenameCreateUserEntries WHERE activation_key='$cgkey'");

    //var_dump($userAccountEntries);
    // then registration was done and user should be directly logged and created account without waiting for mail
    if (count($userAccountEntries)) {

        $checkWpUserId = $wpdb->get_var("SELECT DISTINCT wp_user_id FROM $tablenameCreateUserEntries WHERE activation_key='$cgkey' AND wp_user_id >= 1");

        // for before and since v14 users
        $unconfirmedUser = $wpdb->get_row("SELECT * FROM $tablenameWpUsers WHERE user_activation_key='$cgkeyForWpUserTable-unconfirmed'");

        $isMailAlreadyExists = false;

        foreach($userAccountEntries as $entry){
            if($entry->Field_Type == 'main-mail'){
                $isMailAlreadyExists = $wpdb->get_var("SELECT user_email FROM $tablenameWpUsers WHERE user_email LIKE '".$entry->Field_Content."'");
                break;
            }
        }

        if ((!empty($checkWpUserId) AND empty($unconfirmedUser)) OR !empty($isMailAlreadyExists)) {// If user is completely confirmed then show this here, make this check because it works also for users registered in lower version then 10.9.8.8.0 for sure

            echo "<div id='cg_activation' >";
            echo "<p>";
            echo "This user is already registered.";
            echo "</p>";
            echo "</div>";

            ?>

            <script defer>

                setTimeout(function (){
                    jQuery("html, body").animate({ scrollTop: jQuery('#cg_activation').offset().top-60}, 0);
                },100);

                window.history.replaceState({}, document.title, location.protocol + '//' + location.host + location.pathname);

            </script>

            <?php
        } else {
            // !!!IMPORTANT!!!, THIS HERE HAS TO WORK FOR UNCONFIRMED USERS AND NOT CREATED USERS!!!!!!

            $i = 0;
            $fieldRow = '';

            $user_nicename = '';
            $display_name = '';

            foreach ($userAccountEntries as $key => $value) {

                foreach ($value as $key1 => $value1) {
                    $i++;
                    if ($value1 == "password") {
                        $fieldRow = "password";
                        continue;
                    }
                    if ($fieldRow == "password") {
                        $user_pass = $value1;
                        $fieldRow = '';
                        continue;
                    }
                    if ($value1 == "main-mail") {
                        $fieldRow = "main-mail";
                        continue;
                    }
                    if ($fieldRow == "main-mail") {
                        $user_email = $value1;
                        $fieldRow = '';
                        continue;
                    }
                    if ($value1 == "main-user-name") {
                        $fieldRow = "main-user-name";
                        continue;
                    }
                    if ($fieldRow == "main-user-name") {
                        $user_login = $value1;
                        $fieldRow = '';
                        continue;
                    }
                    if ($value1 == "main-nick-name") {
                        $fieldRow = "main-nick-name";
                        continue;
                    }
                    if ($fieldRow == "main-nick-name") {
                        $user_nicename = $value1;
                        $display_name = $value1;
                        $fieldRow = '';
                    }
                }

            }


            $cgkeyForWpUserTable = $cgkey;

            if(!empty($user_login) AND !empty($user_email) AND !empty($user_pass)){

                $user_registered = date("Y-m-d H:i:s");

                if(empty($unconfirmedUser)){

                    if(intval($galleryDbVersion)>=14){
                        $cgkeyForWpUserTable = 'cg-key---'.$cgkey;
                    }

                    if(empty($user_nicename)){
                        $user_nicename = $user_login;
                    }

                    if(empty($display_name)){
                        $display_name = $user_login;
                    }

                    // '-confirmed' was added update 10.9.8.8.0
                    $wpdb->query($wpdb->prepare(
                        "
                                INSERT INTO $tablenameWpUsers
                                ( id, user_login, user_pass, user_nicename, user_email, user_url,
                                user_registered, user_activation_key, user_status, display_name)
                                VALUES (%s,%s,%s,%s,%s,%s,
                                %s,%s,%d,%s)
                            ",
                        '', $user_login, $user_pass, $user_nicename, $user_email, '',
                        $user_registered, $cgkeyForWpUserTable.'-confirmed', '', $display_name
                    ));
                }else{// since 10.9.8.8.0
                    // if is unconfirmed user that already created after registration!!!!!!!!

                    $cgkeyForWpUserTable = $cgkey;

                    if(intval($galleryDbVersion)>=14){// $cgkey will be always send simple without cg-key---, it might be already with cg-key--- in the database but it will simply set new here
                        $cgkeyForWpUserTable = 'cg-key---'.$cgkey;
                    }

                    $wpdb->update(
                        "$tablenameWpUsers",
                        array('user_activation_key' => $cgkeyForWpUserTable.'-confirmed'),
                        array('ID' => $unconfirmedUser->ID),
                        array('%s'),
                        array('%d')
                    );

                }

                // '-confirmed' was added update 10.9.8.8.0
                $newWpId = $wpdb->get_var("SELECT ID FROM $tablenameWpUsers WHERE user_activation_key='$cgkeyForWpUserTable-confirmed'");

                // do this before updating $tablenameCreateUserEntries and deleting all fields, in cg_update_user_meta_when_register entries will be deleted also! !!!!
                $attach_id = $wpdb->get_var("SELECT Field_Content FROM $tablenameCreateUserEntries WHERE Field_Type = 'profile-image' AND activation_key = '".$cgkey."' LIMIT 1");

                if(intval($galleryDbVersion)>=14){
                    cg_update_user_meta_when_register( $newWpId, $cgkey ) ;
                }

                // Add new wp_user_id
                $wpdb->update(
                    "$tablenameCreateUserEntries",
                    array('wp_user_id' => $newWpId, 'activation_key' => ''),
                    array('activation_key' => $cgkey),
                    array('%d', '%s'),
                    array('%s')
                );

                if(intval($galleryDbVersion)>=14){
                    // new logic, delete all fields not only passwords, because all user data are in wp_usermeta then
                    $wpdb->query($wpdb->prepare(// delete simply all entries
                        "
                                    DELETE FROM $tablenameCreateUserEntries WHERE GeneralID = %d AND wp_user_id = %d
                                ",
                        1,$newWpId
                    ));
                }else{
                    $wpdb->query($wpdb->prepare(
                        "
                                    DELETE FROM $tablenameCreateUserEntries WHERE GalleryID = %d AND (Field_Type = %s OR Field_Type = %s OR Field_Type = %s OR Field_Type = %s) AND wp_user_id = %d
                                ",
                        $GalleryID, "password", "password-confirm", "main-user-name", "main-mail", $newWpId
                    ));
                }

                // User Rolle wird gesetzt
                wp_update_user(array('ID' => $newWpId, 'role' => $RegistryUserRole));

                if(!empty($attach_id)){
                    cg_registry_add_profile_image('cg_input_image_upload_file',$newWpId,false,false,$attach_id);
                }

                echo "<div id='cg_activation'  class='mainCGdivUploadForm mainCGdivUploadFormStatic $FeControlsStyleRegistry $BorderRadiusRegistry'>";

                if(!empty($pro_options)){
                    echo "<p>";
                    echo html_entity_decode(stripslashes(nl2br($pro_options->TextAfterEmailConfirmation)));
                    echo "</p>";

                }else{ // Fallback text if gallery was deleted

                    echo "<p>Thank you for your registration. <br>You are now able to log in.</p>";

                }

                echo "</div>";


                ?>

                <script defer>

                    setTimeout(function (){
                        jQuery("html, body").animate({ scrollTop: jQuery('#cg_activation').offset().top-60}, 0);
                    },100);

                </script>

                <?php


            }else{

                echo "<div id='cg_activation'  class='mainCGdivUploadForm mainCGdivUploadFormStatic $FeControlsStyleRegistry $BorderRadiusRegistry'>";

                echo "<p>Fields must be deleted manually from database.<br>Please contact administrator.</p>";

                echo "</div>";

                ?>

                <script defer>

                    setTimeout(function (){
                        jQuery("html, body").animate({ scrollTop: jQuery('#cg_activation').offset().top-60}, 0);
                    },100);

                    window.history.replaceState({}, document.title, location.protocol + '//' + location.host + location.pathname);


                </script>

                <?php

            }


        }


    } else {

        echo "<div id='cg_activation'  class='mainCGdivUploadForm mainCGdivUploadFormStatic $FeControlsStyleRegistry $BorderRadiusRegistry'>";

        echo "<p>Your mail must be already confirmed or you are using wrong registration link.</p>";

        echo "</div>";

        ?>

        <script>

            setTimeout(function (){
                jQuery("html, body").animate({ scrollTop: jQuery('#cg_activation').offset().top-60}, 0);
            },100);

            window.history.replaceState({}, document.title, location.protocol + '//' + location.host + location.pathname);


        </script>

        <?php

    }

}


?>