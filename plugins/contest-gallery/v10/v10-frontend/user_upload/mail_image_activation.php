<?php
if(!defined('ABSPATH')){exit;}

if (filter_var($userMail, FILTER_VALIDATE_EMAIL)) {

    do_action( 'contest_gal1ery_mail_image_activation', $selectSQLemail,$userMail,$nextId,$galeryID,$post_title,$galeryIDuser,$selectSQL1);

    $wpdb->update(
        "$tablename1",
        array('Informed' => '1'),
        array('id' => $nextId),
        array('%d'),
        array('%d')
    );

}




?>