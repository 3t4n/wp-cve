<?php
/*
Plugin Name: Widgets for Airbnb Reviews
Plugin Title: Widgets for Airbnb Reviews Plugin
Plugin URI: https://wordpress.org/plugins/review-widgets-for-airbnb/
Description: Embed Airbnb reviews fast and easily into your WordPress site. Increase SEO, trust and sales using Airbnb reviews.
Tags: airbnb, airbnb reviews, accommodations, apartments, reviews, ratings, recommendations, testimonials, widget, slider, review, rating, recommendation, testimonial, customer review
Author: Trustindex.io <support@trustindex.io>
Author URI: https://www.trustindex.io/
Contributors: trustindex
License: GPLv2 or later
Version: 11.6
Text Domain: review-widgets-for-airbnb
Domain Path: /languages
Donate link: https://www.trustindex.io/prices/
*/
/*
Copyright 2019 Trustindex Kft (email: support@trustindex.io)
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
require_once plugin_dir_path( __FILE__ ) . 'trustindex-plugin.class.php';
$trustindex_pm_airbnb = new TrustindexPlugin_airbnb("airbnb", __FILE__, "11.6", "Widgets for Airbnb Reviews", "Airbnb");
register_activation_hook(__FILE__, [ $trustindex_pm_airbnb, 'activate' ]);
register_deactivation_hook(__FILE__, [ $trustindex_pm_airbnb, 'deactivate' ]);
add_action('plugins_loaded', [ $trustindex_pm_airbnb, 'load' ]);
add_action('admin_menu', [ $trustindex_pm_airbnb, 'add_setting_menu' ], 10);
add_filter('plugin_action_links', [ $trustindex_pm_airbnb, 'add_plugin_action_links' ], 10, 2);
add_filter('plugin_row_meta', [ $trustindex_pm_airbnb, 'add_plugin_meta_links' ], 10, 2);
if (!function_exists('register_block_type')) {
add_action('widgets_init', [ $trustindex_pm_airbnb, 'init_widget' ]);
add_action('widgets_init', [ $trustindex_pm_airbnb, 'register_widget' ]);
}
if (is_file($trustindex_pm_airbnb->getCssFile())) {
add_action('init', function() {
global $trustindex_pm_airbnb;
if (!isset($trustindex_pm_airbnb) || is_null($trustindex_pm_airbnb)) {
require_once plugin_dir_path( __FILE__ ) . 'trustindex-plugin.class.php';
$trustindex_pm_airbnb = new TrustindexPlugin_airbnb("airbnb", __FILE__, "11.6", "Widgets for Airbnb Reviews", "Airbnb");
}
$path = wp_upload_dir()['baseurl'] .'/'. $trustindex_pm_airbnb->getCssFile(true);
if (is_ssl()) {
$path = str_replace('http://', 'https://', $path);
}
wp_register_style('ti-widget-css-airbnb', $path, [], filemtime($trustindex_pm_airbnb->getCssFile()));
});
}
if (!function_exists('ti_exclude_js')) {
function ti_exclude_js($list) {
$list []= 'trustindex.io';
return $list;
}
}
add_filter('rocket_exclude_js', 'ti_exclude_js');
add_filter('litespeed_optimize_js_excludes', 'ti_exclude_js');
if (!function_exists('ti_exclude_inline_js')) {
function ti_exclude_inline_js($list) {
$list []= 'Trustindex.init_pager';
return $list;
}
}
add_filter('rocket_excluded_inline_js_content', 'ti_exclude_inline_js');
add_action('init', [ $trustindex_pm_airbnb, 'init_shortcode' ]);
add_filter('script_loader_tag', function($tag, $handle) {
if (strpos($tag, 'trustindex.io/loader.js') !== false && strpos($tag, 'defer async') === false) {
$tag = str_replace(' src', ' defer async src', $tag);
}
return $tag;
}, 10, 2);
add_action('init', [ $trustindex_pm_airbnb, 'register_tinymce_features' ]);
add_action('init', [ $trustindex_pm_airbnb, 'output_buffer' ]);
add_action('wp_ajax_list_trustindex_widgets', [ $trustindex_pm_airbnb, 'list_trustindex_widgets_ajax' ]);
add_action('admin_enqueue_scripts', [ $trustindex_pm_airbnb, 'trustindex_add_scripts' ]);
add_action('rest_api_init', [ $trustindex_pm_airbnb, 'init_restapi' ]);
if (class_exists('Woocommerce') && !class_exists('TrustindexCollectorPlugin') && !function_exists('ti_woocommerce_notice')) {
function ti_woocommerce_notice() {
$wcNotification = get_option('trustindex-wc-notification', time() - 1);
if ($wcNotification == 'hide' || (int)$wcNotification > time()) {
return;
}
?>
<div class="notice notice-warning is-dismissible" style="margin: 5px 0 15px">
<p><strong><?php echo sprintf(__("Download our new <a href='%s' target='_blank'>%s</a> plugin and get features for free!", 'trustindex-plugin'), 'https://wordpress.org/plugins/customer-reviews-collector-for-woocommerce/', 'Customer Reviews Collector for WooCommerce'); ?></strong></p>
<ul style="list-style-type: disc; margin-left: 10px; padding-left: 15px">
<li><?php echo __('Send unlimited review invitations for free', 'trustindex-plugin'); ?></li>
<li><?php echo __('E-mail templates are fully customizable', 'trustindex-plugin'); ?></li>
<li><?php echo __('Collect reviews on 100+ review platforms (Google, Facebook, Yelp, etc.)', 'trustindex-plugin'); ?></li>
</ul>
<p>
<a href="<?php echo admin_url("admin.php?page=review-widgets-for-airbnb/settings.php&wc_notification=open"); ?>" target="_blank" class="trustindex-rateus" style="text-decoration: none">
<button class="button button-primary"><?php echo __('Download plugin', 'trustindex-plugin'); ?></button>
</a>
<a href="<?php echo admin_url("admin.php?page=review-widgets-for-airbnb/settings.php&wc_notification=hide"); ?>" class="trustindex-rateus" style="text-decoration: none">
<button class="button button-secondary"><?php echo __('Do not remind me again', 'trustindex-plugin'); ?></button>
</a>
</p>
</div>
<?php
}
add_action('admin_notices', 'ti_woocommerce_notice');
}


add_action('wp_ajax_nopriv_'. $trustindex_pm_airbnb->get_webhook_action(), $trustindex_pm_airbnb->get_webhook_action());
add_action('wp_ajax_'. $trustindex_pm_airbnb->get_webhook_action(), $trustindex_pm_airbnb->get_webhook_action());
function trustindex_reviews_hook_airbnb()
{
global $trustindex_pm_airbnb;
global $wpdb;
$token = isset($_POST['token']) ? sanitize_text_field($_POST['token']) : "";
if (isset($_POST['test']) && $token === get_option($trustindex_pm_airbnb->get_option_name('review-download-token'))) {
echo $token;
exit;
}
$ourToken = $trustindex_pm_airbnb->is_review_download_in_progress();
if (!$ourToken) {
$ourToken = get_option($trustindex_pm_airbnb->get_option_name('review-download-token'));
}
try {
if (!$token || $ourToken !== $token) {
throw new Exception('Token invalid');
}
if (!$trustindex_pm_airbnb->is_noreg_linked() || !$trustindex_pm_airbnb->is_table_exists('reviews')) {
throw new Exception('Platform not connected');
}
$name = 'Unknown source';
if (isset($_POST['error']) && $_POST['error']) {
update_option($trustindex_pm_airbnb->get_option_name('review-download-inprogress'), 'error', false);
}
else {
if (isset($_POST['details'])) {
$trustindex_pm_airbnb->save_details($_POST['details']);
$trustindex_pm_airbnb->save_reviews(isset($_POST['reviews']) ? $_POST['reviews'] : []);
}
delete_option($trustindex_pm_airbnb->get_option_name('review-download-inprogress'));
delete_option($trustindex_pm_airbnb->get_option_name('review-manual-download'));
}
update_option($trustindex_pm_airbnb->get_option_name('download-timestamp'), time() + (86400 * 10), false);
$trustindex_pm_airbnb->setNotificationParam('review-download-available', 'do-check', true);
$isConnecting = get_option($trustindex_pm_airbnb->get_option_name('review-download-is-connecting'));
if (!$isConnecting && !$trustindex_pm_airbnb->getNotificationParam('review-download-finished', 'hidden')) {
$trustindex_pm_airbnb->setNotificationParam('review-download-finished', 'active', true);
}
delete_option($trustindex_pm_airbnb->get_option_name('review-download-is-connecting'));
if (!$trustindex_pm_airbnb->getNotificationParam('review-download-available', 'hidden')) {
$trustindex_pm_airbnb->setNotificationParam('review-download-available', 'do-check', true);
$trustindex_pm_airbnb->setNotificationParam('review-download-available', 'active', false);
}
if (!$isConnecting) {
$trustindex_pm_airbnb->sendNotificationEmail('review-download-finished');
}
echo $ourToken;
}
catch(Exception $e) {
echo 'Error in WP: '. $e->getMessage();
}
exit;
}
add_action('admin_notices', function() {
global $trustindex_pm_airbnb;
$notifications = get_option($trustindex_pm_airbnb->get_option_name('notifications'), []);
foreach ([
'not-using-no-connection',
'not-using-no-widget',
'review-download-available',
'review-download-finished',
'rate-us'
] as $type) {
if (!$trustindex_pm_airbnb->isNotificationActive($type, $notifications)) {
continue;
}
echo '
<div class="notice notice-warning is-dismissible trustindex-notification-row'. ($type === 'rate-us' ? ' trustindex-popup' : '') .'" data-close-url="'. admin_url('admin.php?page=review-widgets-for-airbnb/settings.php&notification='. $type .'&action=close') .'">
<p>'. $trustindex_pm_airbnb->getNotificationText($type) .'<p>';
if ($type === 'rate-us') {
echo '
<a href="'. admin_url('admin.php?page=review-widgets-for-airbnb/settings.php&notification='. $type .'&action=open') .'" class="ti-close-notification" target="_blank">
<button class="button ti-button-primary button-primary">'. __('Write a review', 'trustindex-plugin') .'</button>
</a>
<a href="'. admin_url('admin.php?page=review-widgets-for-airbnb/settings.php&notification='. $type .'&action=later') .'" class="ti-remind-later">
'. __('Maybe later', 'trustindex-plugin') .'
</a>
<a href="'. admin_url('admin.php?page=review-widgets-for-airbnb/settings.php&notification='. $type .'&action=hide') .'" class="ti-hide-notification" style="float: right; margin-top: 14px">
'. __('Do not remind me again', 'trustindex-plugin') .'
</a>
';
}
else {
echo '
<a href="'. admin_url('admin.php?page=review-widgets-for-airbnb/settings.php&notification='. $type .'&action=open') .'">
<button class="button button-primary">'. $trustindex_pm_airbnb->getNotificationButtonText($type) .'</button>
</a>';
}
if ($type === 'not-using-no-widget') {
echo '
<a href="'. admin_url('admin.php?page=review-widgets-for-airbnb/settings.php&notification='. $type .'&action=later') .'" class="ti-remind-later" style="margin-left: 5px">
'. __('Remind me later', 'trustindex-plugin') .'
</a>';
}
echo '
</p>
</div>';
}
});
?>