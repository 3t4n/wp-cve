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
    if (isset($_POST['process']) && $_POST['process'] == 'recaptcha_for_all_admin_page_settings') {
        //get limit

        $nonce = sanitize_text_field($_POST['_wpnonce'] ?? '');

        if (!wp_verify_nonce($nonce, 'update_recaptcha')) {
            echo 'Nonce verification failed.';
            return;
        }


        $recaptcha_for_all_updated = false;

        if (isset($_POST['settings'])) {

            $recaptcha_for_all_settings = sanitize_text_field($_POST['settings']);
            $recaptcha_for_all_settings_china = sanitize_text_field($_POST['settings_china']);
            $recaptcha_for_all_settings_provider = sanitize_text_field($_POST['settings_provider']);
            $recaptcha_for_all_update = sanitize_text_field($_POST['recaptcha_for_all_update']);


            //die(var_export($_POST['recaptcha_for_all_update']));

            if (!empty($recaptcha_for_all_update)) {
                update_option('recaptcha_for_all_update', $recaptcha_for_all_update);
                $recaptcha_for_all_updated = true;
            }



            if (!empty($recaptcha_for_all_settings)) {
                update_option('recaptcha_for_all_settings', $recaptcha_for_all_settings);
                $recaptcha_for_all_updated = true;
            }



            if (!empty($recaptcha_for_all_settings_china)) {
                update_option('recaptcha_for_all_settings_china', $recaptcha_for_all_settings_china);
                $recaptcha_for_all_updated = true;
            }

            if (!empty($recaptcha_for_all_settings_provider)) {
                update_option('recaptcha_for_all_settings_provider', $recaptcha_for_all_settings_provider);
                $recaptcha_for_all_updated = true;
            }       



            if (isset($_POST['recaptcha_score'])) {
                $recaptcha_for_all_recaptcha_score = sanitize_text_field($_POST['recaptcha_score']);
                if (!empty($recaptcha_for_all_recaptcha_score)) {
                    update_option('recaptcha_for_all_recaptcha_score', $recaptcha_for_all_recaptcha_score);
                    $recaptcha_for_all_updated = true;
                }
            }


            if ($recaptcha_for_all_updated)
                recaptcha_for_all_updated_message();
        }
    }
}




$recaptcha_for_all_settings = trim(sanitize_text_field(get_option('recaptcha_for_all_settings', '')));
$recaptcha_for_all_settings_china = trim(sanitize_text_field(get_option('recaptcha_for_all_settings_china', '')));
$recaptcha_for_all_settings_provider = trim(sanitize_text_field(get_option('recaptcha_for_all_settings_provider', 'google')));

$recaptcha_for_all_recaptcha_score = trim(sanitize_text_field(get_option('recaptcha_for_all_recaptcha_score', '')));


$recaptcha_for_all_update = trim(sanitize_text_field(get_option('recaptcha_for_all_update', '')));


echo '<div class="wrap-recaptcha ">' . "\n";
echo '<h2 class="title">'.esc_attr__("General Settings", "recaptcha-for-all").'</h2>' . "\n";
echo '<p class="description">'.esc_attr__("Activate or Deactivate the plugin, set Google or Turnstile,  set the minimum IP score and block visits from China.","recaptcha-for-all");?>




<br> </p>
<big>

            <b>
            <?php esc_attr_e("Info about score", "recaptcha-for-all"); ?>:
            </b>
            <br>
            
            <?php esc_attr_e("For each interaction, Google return a IP score.", "recaptcha-for-all"); ?>
            <br>
            <?php esc_attr_e("1.0 is very likely a good interaction, 0.0 is very likely a bot. We suggest you begin with 0.7", "recaptcha-for-all"); ?>
            <br>
            
            <?php esc_attr_e("You can see details and your history chart at your Google.com site dashboard.", "recaptcha-for-all"); ?>
            
            <br><br>



    <?php

    if ($recaptcha_for_all_settings == 'yes')
        $radio_active = true;
    else
        $radio_active = false;


    if ($recaptcha_for_all_settings_china == 'yes')
      $radio_active_china = true;
    else
      $radio_active_china = false;


    $radio_active_google = false;
    $radio_active_turnstile = false;

    if ($recaptcha_for_all_settings_provider == 'google')
      $radio_active_google = true;
    else
      $radio_active_turnstile = true;


    $recaptcha_score = trim($recaptcha_for_all_recaptcha_score);


    ?>
    <form class="recaptcha_for_all-form" method="post"
        action="admin.php?page=recaptcha_for_all_admin_page&tab=settings">
        <input type="hidden" name="process" value="recaptcha_for_all_admin_page_settings" />

         <!-- Add the hidden input for nonce value -->
        <input type="hidden" name="_wpnonce" value="<?php echo esc_attr(wp_create_nonce('update_recaptcha')); ?>" />


   
        
        <?php esc_attr_e("Just mark Yes to activate the plugin.", "recaptcha-for-all"); ?>
        <br>
        <label for="radio_yes">
            <?php esc_attr_e("Yes", "recaptcha-for-all"); ?>
        </label>
        <input type="radio" id="radio_yes" name="settings" value="yes" <?php if ($radio_active) echo 'checked'; ?>>
        <label for="radio_no">
        <?php esc_attr_e("No", "recaptcha-for-all"); ?>
        </label>
        <input type="radio" id="radio_no" name="settings" value="no" <?php if (!$radio_active) echo 'checked'; ?>>
 


        <br><br>
        <?php esc_attr_e("Choose Google reCAPTCHA or Cloudflare Turnstile.", "recaptcha-for-all"); ?>
        <br>
        <label for="radio_google">
            <?php esc_attr_e("Google", "recaptcha-for-all"); ?>
        </label>
        <input type="radio" id="radio_google" name="settings_provider" value="google" <?php if ($radio_active_google) echo 'checked'; ?>>
        <label for="radio_turnstyle">
        <?php esc_attr_e("Turnstile", "recaptcha-for-all"); ?>
        </label>
        <input type="radio" id="radio_turnstile" name="settings_provider" value="turnstile" <?php if (! $radio_active_google) echo 'checked'; ?>>
 







        <br><br>
        <label for="recaptcha_for_alllimit">
            <?php esc_attr_e("Select the score minimum (Works only with Google) of the visitor to access your site:", "recaptcha-for-all"); ?> 
        </label>
        <select name="recaptcha_score" id="recaptcha_score">
            <option value="1" <?php echo ($recaptcha_score == '1') ? ' selected="selected"' : ''; ?>>0.1</option>
            <option value="2" <?php echo ($recaptcha_score == '2') ? ' selected="selected"' : ''; ?>>0.2</option>
            <option value="3" <?php echo ($recaptcha_score == '3') ? ' selected="selected"' : ''; ?>>0.3</option>
            <option value="4" <?php echo ($recaptcha_score == '4') ? ' selected="selected"' : ''; ?>>0.4</option>
            <option value="5" <?php echo ($recaptcha_score == '5') ? ' selected="selected"' : ''; ?>>0.5</option>
            <option value="6" <?php echo ($recaptcha_score == '6') ? ' selected="selected"' : ''; ?>>0.6</option>
            <option value="7" <?php echo ($recaptcha_score == '7') ? ' selected="selected"' : ''; ?>>0.7</option>
            <option value="8" <?php echo ($recaptcha_score == '8') ? ' selected="selected"' : ''; ?>>0.8</option>
            <option value="9" <?php echo ($recaptcha_score == '9') ? ' selected="selected"' : ''; ?>>0.9</option>
        </select>
        
        <br />
        <br />

        
        <?php esc_attr_e("Just mark Yes to Block Visits from China.", "recaptcha-for-all"); ?> 
        
        
        
        <br>
        <label for="radio_yes">
           <?php esc_attr_e("Yes", "recaptcha-for-all"); ?>
        </label>
        <input type="radio" id="radio_yes_china" name="settings_china" value="yes" <?php if ($radio_active_china) echo 'checked'; ?>>
        <label for="radio_no">
        <?php esc_attr_e("No", "recaptcha-for-all"); ?>
        </label>
        <input type="radio" id="radio_no_china" name="settings_china" value="no" <?php if (!$radio_active_china) echo 'checked'; ?>>
        
        
        <br> <br>
        
        <?php esc_attr_e("Enable Auto Update Plugin? (default Yes)", "recaptcha-for-all"); ?> 
        <br>

        <label>
        <input type="radio" name="recaptcha_for_all_update" value="yes" <?php echo checked('yes', $recaptcha_for_all_update, false); ?>>
        <?php esc_attr_e("Yes, enable Recaptcha For All Auto Update","recaptcha-for-all");?>
        </label>
        
        <label>
        <input type="radio" name="recaptcha_for_all_update" value="no" <?php echo checked('no', $recaptcha_for_all_update, false); ?>>
        <?php esc_attr_e("No (unsafe)","recaptcha-for-all");?>
        </label>
 
 
        <br />
        <br />
        <?php 
        echo '<input class="recaptcha_for_all-submit button-primary" type="submit" value="'.esc_attr__("Update", "recaptcha-for-all").'" />'; ?>

    </form>

</big>
</div>



        <?php
        function recaptcha_for_all_updated_message()
        {
            echo '<div class="notice notice-success is-dismissible">';
            echo '<br /><b>';
            esc_attr_e('Database Updated!', 'recaptcha_for_all');
            echo '<br /><br /></div>';
        }