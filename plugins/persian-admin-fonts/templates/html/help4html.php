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

<h4 style="text-align: justify;"><?php echo __('The reason for the slow speed of the WordPress admin-side and sometimes the front-end of your WordPress site is the recent restrictions on the Internet in Iran and the communication infrastructure of the country, which is really a pity! And it has put many online and virtual businesses in the abyss of destruction. To get rid of this problem, you should minimize the external requests of your site as much as possible.', 'pfmdz') ?></h4>

<h4 style="text-align: justify;">
<?php echo __('To close and block all external WordPress requests, you can add the following piece of code to your sites wp-config.php file (from the Host file manager) so that you can continue your work faster.', 'pfmdz') ?>
</h4>

<div style="text-align: center;direction: ltr;margin-bottom: 25px;">
<strong>define( 'WP_HTTP_BLOCK_EXTERNAL', true );</strong>
</div>

<div onclick="exp_imgs_lightbox(this)"><img class="exampleimgs" src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/wp-config.webp'); ?>" width="300" height="auto" /></div>

<h4 style="text-align: justify;">
<?php echo __('To increase the speed on Iranian hosts, you can use the following free plugin:', 'pfmdz') ?>
</h4>

<div style="text-align: center;"><a href="https://www.rtl-theme.com/speedup-dashboard/" target="_blank"><?php echo __('The link to download the plugin to increase the speed of the admin', 'pfmdz') ?></a></div>

<h4 style="text-align: justify;">
<?php echo __('Tip: You can use the Unbloater plugin for more control and more limitation of external WordPress admin requests:', 'pfmdz') ?>
</h4>

<div style="text-align: center;"><a href="https://wordpress.org/plugins/unbloater/" target="_blank"><?php echo __('Unbloater download link', 'pfmdz') ?></a></div>

<h4><?php echo __('For more and more detailed training, click on the following link:', 'pfmdz') ?></h4>

<a class="button button-primary" href="https://wooslider.ir/admin-google-fonts/" target="_blank"><?php echo __('More Help', 'pfmdz') ?></a>

<h4><?php echo __('For a more professional and detailed review, you can contact our team (Mdesign):', 'pfmdz') ?></h4>

<p style="text-align: center;"><strong>Email: mdesign.fa@gmail.com</strong></p>

<a class="button button-primary" href="https://t.me/g_mdz" target="_blank"><?php echo __('Quick Contact', 'pfmdz') ?></a>

<h4 style="text-align: center;"><?php echo __('Hoping for brighter and more hopeful days for our dear country Iran :)', 'pfmdz') ?></h4>