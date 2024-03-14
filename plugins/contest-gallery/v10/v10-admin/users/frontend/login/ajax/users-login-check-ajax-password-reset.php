<?php

$tablenameWpUsers = $wpdb->base_prefix . "users";

$cgLostPasswordNew = sanitize_text_field($_REQUEST['cgLostPasswordNew']);
$cgLostPasswordNewRepeat = sanitize_text_field($_REQUEST['cgLostPasswordNewRepeat']);

$cgResetPasswordWpUserID = sanitize_text_field($_REQUEST['cgResetPasswordWpUserID']);

if($cgLostPasswordNew!=$cgLostPasswordNewRepeat){
    ?>
    <script data-cg-processing="true">
        var cg_language_PasswordsDoNotMatch = document.getElementById("cg_language_PasswordsDoNotMatch").value;
        var cgLostPasswordPasswordsDoNotMatch = document.getElementById('cgLostPasswordPasswordsDoNotMatch');
        cgLostPasswordPasswordsDoNotMatch.innerHTML = cg_language_PasswordsDoNotMatch;
        cgLostPasswordPasswordsDoNotMatch.classList.remove('cg_hide');
    </script>
    <?php
    return;
}

if(empty($cgLostPasswordNew) OR strlen($cgLostPasswordNew)<6){
    ?>
    <script data-cg-processing="true">
        var cg_language_LostPasswordUrlIsNotValidAnymore = document.getElementById("cg_language_LostPasswordUrlIsNotValidAnymore").value;
        var cgLostPasswordUrlIsNotValidAnymore = document.getElementById('cgLostPasswordUrlIsNotValidAnymore');
        cgLostPasswordUrlIsNotValidAnymore.innerHTML = cg_language_LostPasswordUrlIsNotValidAnymore;
        cgLostPasswordUrlIsNotValidAnymore.classList.remove('cg_hide');
    </script>
    <?php
    return;
}

if(empty($cgResetPasswordWpUserID)){
    ?>
    <script data-cg-processing="true">
        var cg_language_LostPasswordUrlIsNotValidAnymore = document.getElementById("cg_language_LostPasswordUrlIsNotValidAnymore").value;
        var cgLostPasswordUrlIsNotValidAnymore = document.getElementById('cgLostPasswordUrlIsNotValidAnymore');
        cgLostPasswordUrlIsNotValidAnymore.innerHTML = cg_language_LostPasswordUrlIsNotValidAnymore;
        cgLostPasswordUrlIsNotValidAnymore.classList.remove('cg_hide');
    </script>
    <?php
    return;
}
$wpUserID = $wpdb->get_var("SELECT ID FROM $tablenameWpUsers WHERE ID = '".$cgResetPasswordWpUserID."'");
if(empty($wpUserID)){
    ?>
    <script data-cg-processing="true">
        var cg_language_LostPasswordUrlIsNotValidAnymore = document.getElementById("cg_language_LostPasswordUrlIsNotValidAnymore").value;
        var cgLostPasswordUrlIsNotValidAnymore = document.getElementById('cgLostPasswordUrlIsNotValidAnymore');
        cgLostPasswordUrlIsNotValidAnymore.innerHTML = cg_language_LostPasswordUrlIsNotValidAnymore;
        cgLostPasswordUrlIsNotValidAnymore.classList.remove('cg_hide');
    </script>
    <?php
    return;
}

$cgLostPasswordMailTimestamp = intval(get_the_author_meta( 'cgLostPasswordMailTimestamp', $wpUserID ) );

if(empty($cgLostPasswordMailTimestamp)){
    ?>
    <script data-cg-processing="true">
        var cg_language_LostPasswordUrlIsNotValidAnymore = document.getElementById("cg_language_LostPasswordUrlIsNotValidAnymore").value;
        var cgLostPasswordUrlIsNotValidAnymore = document.getElementById('cgLostPasswordUrlIsNotValidAnymore');
        cgLostPasswordUrlIsNotValidAnymore.innerHTML = cg_language_LostPasswordUrlIsNotValidAnymore;
        cgLostPasswordUrlIsNotValidAnymore.classList.remove('cg_hide');
    </script>
    <?php
    return;
}else{
    $cgResetPasswordLinkNotValidAnymore = true;
    if($cgLostPasswordMailTimestamp+((60*60*24)+(60*15))<time()){// in this case user has extra ten minutes more to reset password, 24 hours + 15 minutes to go sure time not expire during reset
        $cgResetPasswordLinkNotValidAnymore = true;
        delete_user_meta($wpUserID,'cgLostPasswordMailTimestamp');
        ?>
        <script data-cg-processing="true">
            var cg_language_LostPasswordUrlIsNotValidAnymore = document.getElementById("cg_language_LostPasswordUrlIsNotValidAnymore").value;
            var cgLostPasswordUrlIsNotValidAnymore = document.getElementById('cgLostPasswordUrlIsNotValidAnymore');
            cgLostPasswordUrlIsNotValidAnymore.innerHTML = cg_language_LostPasswordUrlIsNotValidAnymore;
            cgLostPasswordUrlIsNotValidAnymore.classList.remove('cg_hide');
        </script>
        <?php
        return;
    }else{

        $user_pass = wp_hash_password($cgLostPasswordNew);

        // set user id here by activation key, because created!!!
        $wpdb->update(
            "$tablenameWpUsers",
            array('user_pass' => $user_pass),
            array('ID' => $wpUserID),
            array('%s'),
            array('%d')
        );

        delete_user_meta($wpUserID,'cgLostPasswordMailTimestamp');

        ?>
        <script data-cg-processing="true">
            var mainCGdivLostPasswordResetContainer = document.getElementById('mainCGdivLostPasswordResetContainer');
            mainCGdivLostPasswordResetContainer.classList.add('cg_hide');
            var mainCGdivResetPasswordSuccessfullyExplanation = document.getElementById('mainCGdivResetPasswordSuccessfullyExplanation');
            mainCGdivResetPasswordSuccessfullyExplanation.classList.remove('cg_hide');
            var mainCGdivLoginFormContainer = document.getElementById('mainCGdivLoginFormContainer');
            mainCGdivLoginFormContainer.classList.remove('cg_hide');
        </script>
        <?php
        return;

    }
}

return;

?>