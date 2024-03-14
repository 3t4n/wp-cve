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
    if (isset($_POST['process']) && $_POST['process'] == 'recaptcha_for_all_admin_page_pages') {
        //get limit
        $recaptcha_for_all_updated = false;

        if (isset($_POST['settings'])) {


            //var_dump($_POST['settings']);
            // die();


            $recaptcha_for_all_pages = sanitize_text_field($_POST['settings']);

            // $recaptcha_for_all_slugs = sanitize_text_field($_POST['settings_slugs']);
            $recaptcha_for_all_slugs = implode(PHP_EOL, array_map('sanitize_textarea_field', explode(PHP_EOL, $_POST['settings_slugs'])));



            if (!empty($recaptcha_for_all_pages)) {
                update_option('recaptcha_for_all_pages', $recaptcha_for_all_pages);
                $recaptcha_for_all_updated = true;
            }

            if (!empty($recaptcha_for_all_slugs)) {
                update_option('recaptcha_for_all_slugs', $recaptcha_for_all_slugs);
                $recaptcha_for_all_updated = true;
            }
            

            if ($recaptcha_for_all_updated)
                recaptcha_for_all_updated_message();
        }
    }
}
$recaptcha_for_all_pages = trim(sanitize_text_field(get_option('recaptcha_for_all_pages', '')));
// $recaptcha_for_all_slugs = trim(sanitize_text_field(get_option('recaptcha_for_all_slugs', '')));
$recaptcha_for_all_slugs = implode(PHP_EOL, array_map('sanitize_textarea_field', explode(PHP_EOL, get_option('recaptcha_for_all_slugs', ''))));



echo '<div class="wrap-recaptcha ">' . "\n";
echo '<h2 class="title">'.esc_attr__("Manage Pages", "recaptcha-for-all").'</h2>' . "\n";
echo '<p class="description">'.esc_attr__("Choose the pages and/or posts to enable reCAPTCHA/Turnstile.","recaptcha-for-all");

?>
<br> </p>

    <?php

    $radio_active1 = $radio_active2 = $radio_active3 = $radio_active4 = false;

    if ($recaptcha_for_all_pages == 'yes_all')
        $radio_active1 = true;
    elseif ($recaptcha_for_all_pages == 'yes_pages')
        $radio_active2 = true;
    elseif ($recaptcha_for_all_pages == 'yes_posts')
        $radio_active3 = true;
    else
        $radio_active4 = true;


   // $recaptcha_score = trim($recaptcha_for_all_recaptcha_score);


    ?>
    <form class="recaptcha_for_all-form" method="post"
        action="admin.php?page=recaptcha_for_all_admin_page&tab=pages">
        <input type="hidden" name="process" value="recaptcha_for_all_admin_page_pages" />

        <?php esc_attr_e("Enable reCAPTCHA/Turnstile on all Pages and Posts?", "recaptcha-for-all"); ?> <br>
        <label for="radio_yes"><?php esc_attr_e("Yes, all pages and posts", "recaptcha-for-all"); ?></label>
        <input type="radio" id="radio_yes_all" name="settings" value="yes_all" <?php if ($radio_active1) echo 'checked'; ?>>
        <br>
        <label for="radio_yes"><?php esc_attr_e("Yes, all pages only", "recaptcha-for-all"); ?></label>
        <input type="radio" id="radio_yes_pages" name="settings" value="yes_pages" <?php if ($radio_active2) echo 'checked'; ?>>
        <br>

        <label for="radio_yes"><?php esc_attr_e("Yes, all posts only", "recaptcha-for-all"); ?></label>
        <input type="radio" id="radio_yes_posts" name="settings" value="yes_posts" <?php if ($radio_active3) echo 'checked'; ?>>
        <br>

        <label for="radio_no"><?php esc_attr_e("No, I will choose below where enable *", "recaptcha-for-all"); ?></label>
        <input type="radio" id="radio_no" name="settings" value="no" <?php if ($radio_active4) echo 'checked'; ?>>
        <br>
        <br><br>

        <label for="recaptcha_for_alllimit">(*)<?php esc_attr_e("If you clicked on the last option, fill out the slug of each pages and or posts to enable reCAPTCHA/Turnstile.", "recaptcha-for-all"); ?>
            <?php esc_attr_e("Otherwise, left blank", "recaptcha-for-all"); ?>:
            <br>
            
            <?php esc_attr_e("To get the Slug, go to", "recaptcha-for-all"); ?>
            <br>
            <?php esc_attr_e("1) Dashboard => Pages => All Pages (or Posts)", "recaptcha-for-all"); ?>
            <br>
            
            <?php esc_attr_e("2) Click over Quick Edit", "recaptcha-for-all"); ?>
            <br>
            
            <?php esc_attr_e("3) Copy the content of the slug field", "recaptcha-for-all"); ?>
            <br>
          
            <?php esc_attr_e("4)Paste below, <strong>one slug by line", "recaptcha-for-all"); ?>
            </strong>.

        </label>
        
        <br />
        <br />
        <textarea id="settings_slugs" name="settings_slugs" rows="5" cols="50"><?php echo esc_html($recaptcha_for_all_slugs); ?></textarea>
        <br />
        <br />
        <?php 
        echo '<input class="recaptcha_for_all-submit button-primary" type="submit" value="'.esc_attr__("Update", "recaptcha-for-all").'" />'; ?>


    </form>

</div>



        <?php
        function recaptcha_for_all_updated_message()
        {
            echo '<div class="notice notice-success is-dismissible">';
            echo '<br /><b>';
            esc_attr_e('Database Updated!', 'recaptcha_for_all');
            echo '<br /><br /></div>';
        }