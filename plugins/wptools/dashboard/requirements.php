<?php
if (!defined('ABSPATH')) {
    die('We\'re sorry, but you can not directly access this file.');
}

bill_check_resources(true);

echo '<big>';
esc_attr_e("Go to Plugin Dashboard:","wptools");?>

<?php esc_attr_e("Dashboard => WP Tools => Dashboard","wptools");?>
<br />
<?php $site = esc_url(WPTOOLSADMURL) . "admin.php?page=wptools_options31&tab=dashboard";?>
<a href="<?php echo esc_url($site); ?>" class="button button-primary"><?php esc_attr_e("Go","wptools");?></a>
<br /><br />
</big>