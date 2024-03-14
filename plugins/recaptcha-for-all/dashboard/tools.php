<?php

/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2021-03-02 12:33:13
 */
if (!defined('ABSPATH')) {
    die('We\'re sorry, but you can not directly access this file.');
}

echo '<div class="wrap-recaptcha">' . "\n";



echo '<h2 class="title">useful free plugins from the same Author</h2>' . "\n";

if(!is_multisite()){
    recaptcha_for_all_new_more_plugins();
}
else
{

    echo '<script>';
    echo 'window.location.replace("'.esc_url(RECAPTCHA_FOR_ALLURL).'plugin-install.php?s=sminozzi&tab=search&type=author");';
    echo '</script>';


}




echo '</div>';
