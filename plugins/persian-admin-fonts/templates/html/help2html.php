<?php
if (!function_exists('add_action'))
{
    echo "<h3>an error occured! You may not be able to access this plugin via direct URL...</h3>";
    exit();
}
else if (!defined('ABSPATH'))
{
    echo "<h3>an error occured! You may not be able to access this plugin via direct URL...</h3>";
    exit();
}
?>

<img src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/pfmdz-fulllogo.svg'); ?>" style="background-color: #ffffff;    border-radius: 20px;" width="300" height="169" />
<p><?php echo __('The use of this plugin is completely free', 'pfmdz') ?></p>
<p><?php echo __('Communication with the designer of this plugin: Email: mdesign.fa@gmail.com', 'pfmdz') ?></p>

<h4><?php echo __('«« compatibilities »»', 'pfmdz') ?></h4>
<p><?php echo __('Compatible with FontAwesome Icons-library', 'pfmdz') ?></p>
<p><?php echo __('Compatible with Visual Composer page builder', 'pfmdz') ?></p>
<p><?php echo __('Compatible with WP-Backery page builder', 'pfmdz') ?></p>
<p><?php echo __('Compatible with Elementor page builder', 'pfmdz') ?></p>
<p><?php echo __('Compatible with Woocommerce', 'pfmdz') ?></p>
<p><?php echo __('Compatible with Yoast-SEO Plugin', 'pfmdz') ?></p>
<p><?php echo __('Compatible with Admin Color Schemes Plugin', 'pfmdz') ?></p>
<p><?php echo __('Compatible with Gutenberg', 'pfmdz') ?></p>
<p><?php echo __('Compatible with WP Media Folders', 'pfmdz') ?></p>
<p><?php echo __('Compatible with BM Custom Login', 'pfmdz') ?></p>
<h4><?php echo __('«« Update »»', 'pfmdz') ?></h4>
<p><?php echo __('If you see any errors, inconsistencies or bugs, please contact me so that a new update for this plugin will be released.', 'pfmdz') ?></p>
<a class="button button-primary" href="https://t.me/g_mdz" target="_blank"><?php echo __('Quick Contact', 'pfmdz') ?></a>