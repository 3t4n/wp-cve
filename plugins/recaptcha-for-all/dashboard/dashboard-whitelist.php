<?php
/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2021-03-02 17:19:27
 */
if (!defined('ABSPATH')) {
    die('We\'re sorry, but you can not directly access this file.');
}
if (isset($_GET['page']) && $_GET['page'] == 'recaptcha_for_all_admin_page') {
    if (isset($_POST['process']) && $_POST['process'] == 'recaptcha_for_all_admin_whitelist') {
        $recaptcha_for_all_updated = false;
        if (isset($_POST['string_whitelist'])) {
            $recaptcha_for_all_string_whitelist = implode(PHP_EOL, array_map('sanitize_textarea_field', explode(PHP_EOL, $_POST['string_whitelist'])));
            update_option('recaptcha_for_all_string_whitelist', $recaptcha_for_all_string_whitelist);
            $recaptcha_for_all_updated = true;
            if (isset($_POST['ip_whitelist'])) {

                $recaptcha_for_all_ip_whitelist = implode(PHP_EOL, array_map('sanitize_textarea_field', explode(PHP_EOL, $_POST['ip_whitelist'])));

                update_option('recaptcha_for_all_ip_whitelist', $recaptcha_for_all_ip_whitelist);
                $recaptcha_for_all_updated = true;
            }
            if ($recaptcha_for_all_updated)
                recaptcha_for_all_updated_message();
        }
    }
}
// escape below...
$recaptcha_for_all_string_whitelist = trim(get_site_option('recaptcha_for_all_string_whitelist', ''));
$recaptcha_for_all_ip_whitelist = trim(get_site_option('recaptcha_for_all_ip_whitelist', ''));

echo '<div class="wrap-recaptcha ">' . "\n";

echo '<h2 class="title">'.esc_attr__("Manage Whitelist IP and String", "recaptcha-for-all").'</h2>' . "\n";
echo '<p class="description">'.esc_attr__("You can create and manage whitelist of users and bots will skip the reCAPTCHA/Turnstile.","recaptcha-for-all");?>

<br>
<b> 


    <?php esc_attr_e("Don't use HTML, only plain text", "recaptcha-for-all"); ?>.


</b><br>

<?php

global $recaptcha_for_all_visitor_ip;
// echo 'Your IP is: ' . $recaptcha_for_all_visitor_ip;
esc_attr_e("Your IP is:", "recaptcha-for-all");
echo esc_attr($recaptcha_for_all_visitor_ip); 
?>
<br><br>
<form class="recaptcha_for_all-form" method="POST" action="admin.php?page=recaptcha_for_all_admin_page&tab=whitelist">
    <input type="hidden" name="process" value="recaptcha_for_all_admin_whitelist" />
    <label for="ip_whitelist">
       
        <?php esc_attr_e("IPs Whitelist:", "recaptcha-for-all"); ?>
    </label>
    <br>
    <textarea id="ip_whitelist" name="ip_whitelist" rows="4" cols="50"><?php echo esc_html($recaptcha_for_all_ip_whitelist); ?></textarea>
    <br><br>
    <label for="string_whitelist">
        
        <?php esc_attr_e("String Whitelist:", "recaptcha-for-all"); ?>
    </label>
    <br>
    <textarea id="string_whitelist" name="string_whitelist" rows="4" cols="50"><?php echo esc_html($recaptcha_for_all_string_whitelist); ?></textarea>
    <br><br>
    <?php
    echo '<br />';
    //echo '<input class="recaptcha_for_all-submit button-primary" type="submit" value="Update" />';
    echo '<input class="recaptcha_for_all-submit button-primary" type="submit" value="'.esc_attr__("Update", "recaptcha-for-all").'" />';

 
    echo '</form>' . "\n";
    echo '</div>';
    function recaptcha_for_all_updated_message()
    {
        echo '<div class="notice notice-success is-dismissible">';
        echo '<br /><b>';
        esc_attr_e('Database Updated!', 'recaptcha_for_all');
        echo '<br /><br /></div>';
    }