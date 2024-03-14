<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if(!current_user_can('edit_pages'))
{
die('The account you\'re logged in to doesn\'t have permission to access this page.');
}
if(!class_exists('Woocommerce'))
{
die(TrustindexWoocommercePlugin::___('Activate WooCommerce first!'));
}
if(isset($_GET['rate_us']))
{
switch(sanitize_text_field($_GET['rate_us']))
{
case 'open':
update_option($trustindex_woocommerce->get_option_name('rate-us') , 'hide', false);
$url = 'https://wordpress.org/support/plugin/'. $trustindex_woocommerce->get_plugin_slug() . '/reviews/?rate=5#new-post';
header('Location: '. $url);
die;
case 'later':
$time = time() + (30 * 86400);
update_option($trustindex_woocommerce->get_option_name('rate-us') , $time, false);
break;
case 'hide':
update_option($trustindex_woocommerce->get_option_name('rate-us') , 'hide', false);
break;
}
echo "<script type='text/javascript'>self.close();</script>";
die;
}
$widget_setted_up = get_option($trustindex_woocommerce->get_option_name('widget-setted-up'), false);
$ti_sub_id = $trustindex_woocommerce->is_trustindex_connected();
$tabs = [];
$tabs[ TrustindexWoocommercePlugin::___('Setup Guide') ] = [
'active' => true,
'href' => 'setup'
];
$tabs[ TrustindexWoocommercePlugin::___("Review Summary Page") ] = [
'active' => true,
'href' => 'setup&step=1'
];
$tabs[ TrustindexWoocommercePlugin::___("Invitation Settings") ] = [
'active' => !!$ti_sub_id,
'href' => 'setup&step=2'
];
$tabs[ TrustindexWoocommercePlugin::___("Invitations") ] = [
'active' => !!$ti_sub_id,
'href' => 'invite_list'
];
$tabs[ TrustindexWoocommercePlugin::___("My Reviews") ] = [
'active' => true,
'href' => 'my_reviews'
];
$tabs[ TrustindexWoocommercePlugin::___("Display Reviews") ] = [
'active' => true,
'href' => 'setup&step=5'
];
$tabs[ TrustindexWoocommercePlugin::___('Get More Features') ] = [
'active' => true,
'href' => 'more_features'
];
$tabs[ TrustindexWoocommercePlugin::___('Troubleshooting') ] = [
'active' => true,
'href' => 'troubleshooting'
];
$default_tab = 'setup';
$selected_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : null;
$included_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : $default_tab;
if($selected_tab == 'setup' && isset($_GET['step']))
{
$selected_tab = "setup&step=". intval($_GET['step']);
}
if(in_array($selected_tab, [ 'unsubscribes', 'setup&step=4' ]))
{
$selected_tab = 'invite_list';
}
$found = in_array($selected_tab, array_column($tabs, 'href'));
if(!$found && !in_array($selected_tab, [ 'unsubscribes' ]))
{
$selected_tab = $default_tab;
$included_tab = $default_tab;
}
$_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : null;
$ti_command = isset($_REQUEST['command']) ? sanitize_text_field($_REQUEST['command']) : null;
$http_blocked = false;
if(defined('WP_HTTP_BLOCK_EXTERNAL') && WP_HTTP_BLOCK_EXTERNAL)
{
if(!defined('WP_ACCESSIBLE_HOSTS') || strpos(WP_ACCESSIBLE_HOSTS, '*.trustindex.io') === FALSE)
{
$http_blocked = true;
}
}
?>
<div class="notice notice-warning is-dismissible" style="margin: 5px 0 15px">
<p><strong>We are developing the new plugin to make it better and better: <a href='https://wordpress.org/plugins/customer-reviews-collector-for-woocommerce/' target='_blank'>Customer Reviews Collector for WooCommerce</a>.</strong> Check it out! (We do not maintain this plugin for now, please use the new plugin instead!)</p>
<p>New plugin's features:</p>
<ul style="list-style-type: disc; margin-left: 10px; padding-left: 15px">
<li>Send unlimited review invitations for free</li>
<li>E-mail templates are fully customizable</li>
<li>Collect reviews on 100+ review platforms (Google, Facebook, Yelp, etc.)</li>
</ul>
<p>
<a href="https://wordpress.org/plugins/customer-reviews-collector-for-woocommerce/" target="_blank" style="text-decoration: none">
<button class="button button-primary">Try our new plugin for free!</button>
</a>
</p>
</div>
<?php /*
<div id="ti-assets-error" class="notice notice-warning" style="display: none; margin-left: 0; margin-right: 0; padding-bottom: 9px">
<p>
<?php echo TrustindexWoocommercePlugin::___("You got an error while trying to run this plugin. Please upgrade all the plugins from Trustindex and if the error still persist send the content of the webserver's error log and the content of the Troubleshooting tab to the support!"); ?>
</p>
<a href="?page=<?php echo esc_attr($_GET['page']); ?>&tab=troubleshooting" class="button button-primary"><?php echo TrustindexWoocommercePlugin::___("Troubleshooting") ;?></a>
</div>
<script type="text/javascript">
window.onload = function() {
let warning_box = document.getElementById("ti-assets-error");
let link = document.getElementById("trustindex-woocommerce-admin-css-css");
if(typeof Trustindex_WooCommerce_JS_loaded == "undefined" || typeof TI_copyTextToClipboard == "undefined" || !link || !Boolean(link.sheet))
{
warning_box.style.display = "block";
}
};
</script>
<div id="trustindex-woocommerce-admin" class="ti-toggle-opacity">
<h1 class="ti-free-title">
<?php echo TrustindexWoocommercePlugin::___("Customer Reviews for WooCommerce"); ?>
<a href="https://www.trustindex.io/ti-redirect.php?a=sys&c=wp-woocommerce-l" target="_blank" title="Trustindex" class="ti-pull-right">
<img src="<?php echo $trustindex_woocommerce->get_plugin_file_url('static/img/trustindex.svg'); ?>" />
</a>
</h1>
<div class="container_wrapper">
<div class="container_cell" id="container-main">
<?php if($http_blocked): ?>
<div class="ti-box ti-notice-error">
<p>
<?php echo TrustindexWoocommercePlugin::___("Your site cannot download our widget templates, because of your server settings not allowing that:"); ?><br /><a href="https://wordpress.org/support/article/editing-wp-config-php/#block-external-url-requests" target="_blank">https://wordpress.org/support/article/editing-wp-config-php/#block-external-url-requests</a><br /><br />
<strong><?php echo TrustindexWoocommercePlugin::___("Solution"); ?></strong><br />
<?php echo TrustindexWoocommercePlugin::___("a) You should define <strong>WP_HTTP_BLOCK_EXTERNAL</strong> as false"); ?><br />
<?php echo TrustindexWoocommercePlugin::___("b) or you should add Trustindex as an <strong>WP_ACCESSIBLE_HOSTS</strong>: \"*.trustindex.io\""); ?><br />
</p>
</div>
<?php endif; ?>
<div class="nav-tab-wrapper">
<?php foreach($tabs as $tab_name => $tab): ?>
<a
id="link-tab-<?php echo $tab['href']; ?>"
class="
nav-tab
 <?php if($selected_tab == $tab['href']): ?>nav-tab-active<?php endif; ?>
 <?php if(!$tab['active']): ?>nav-tab-disabled<?php endif; ?>
 <?php if($tab['href'] == 'troubleshooting'): ?>nav-tab-right<?php endif; ?>
"
href="<?php echo admin_url('admin.php?page='. $trustindex_woocommerce->get_plugin_slug() .'/settings.php&tab='.$tab['href']); ?>"
><?php echo $tab_name; ?></a>
<?php endforeach; ?>
</div>
<div id="tab-<?php echo $included_tab; ?>">
<?php include(plugin_dir_path(__FILE__ ) . 'tabs' . DIRECTORY_SEPARATOR . $included_tab . '.php'); ?>
</div>
</div>
</div>
</div> */ ?>