<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
$reviews = [];
if($trustindex_woocommerce->is_noreg_linked())
{
$reviews = $wpdb->get_results('SELECT * FROM `'. $trustindex_woocommerce->get_tablename('reviews') .'` ORDER BY date DESC');
}
$auto_updates = get_option('auto_update_plugins', []);
$plugin_slug = "customer-reviews-for-woocommerce/customer-reviews-for-woocommerce.php";
if(isset($_GET['auto_update']))
{
if(!in_array($plugin_slug, $auto_updates))
{
array_push($auto_updates, $plugin_slug);
update_option('auto_update_plugins', $auto_updates, false);
}
header('Location: admin.php?page=' . sanitize_text_field($_GET['page']) . '&tab=troubleshooting');
exit;
}
if(isset($_GET['toggle_css_inline']))
{
$v = intval($_GET['toggle_css_inline']);
update_option($trustindex_woocommerce->get_option_name('load-css-inline'), $v, false);
if($v && is_file($trustindex_woocommerce->getCssFile()))
{
unlink($trustindex_woocommerce->getCssFile());
}
header('Location: admin.php?page=' . sanitize_text_field($_GET['page']) . '&tab=troubleshooting');
exit;
}
if(isset($_GET['delete_css']))
{
if(is_file($trustindex_woocommerce->getCssFile()))
{
unlink($trustindex_woocommerce->getCssFile());
}
header('Location: admin.php?page=' . sanitize_text_field($_GET['page']) . '&tab=troubleshooting');
exit;
}
$yes_icon = '<span class="dashicons dashicons-yes-alt"></span>';
$no_icon = '<span class="dashicons dashicons-dismiss"></span>';
$plugin_updated = ($trustindex_woocommerce->get_plugin_current_version() <= "3.2.1");
$css_inline = get_option($trustindex_woocommerce->get_option_name('load-css-inline'), 0);
$css = get_option($trustindex_woocommerce->get_option_name('css-content'));
?>
<div class="ti-box">
<div class="ti-header"><?php echo TrustindexWoocommercePlugin::___("Troubleshooting"); ?></div>
<p><strong><?php echo TrustindexWoocommercePlugin::___('If you have any problem, you should try these steps:'); ?></strong></p>
<ul class="troubleshooting-checklist">
<li>
<?php echo TrustindexWoocommercePlugin::___("Trustindex plugin"); ?>
<ul>
<li>
<?php echo TrustindexWoocommercePlugin::___('Use the latest version:') .' '. ($plugin_updated ? $yes_icon : $no_icon); ?>
<?php if(!$plugin_updated): ?>
<a href="/wp-admin/plugins.php"><?php echo TrustindexWoocommercePlugin::___("Update"); ?></a>
<?php endif; ?>
</li>
<li>
<?php echo TrustindexWoocommercePlugin::___('Use automatic plugin update:') .' '. (in_array($plugin_slug, $auto_updates) ? $yes_icon : $no_icon); ?>
<?php if(!in_array($plugin_slug, $auto_updates)): ?>
<a href="?page=<?php echo sanitize_text_field($_GET['page']); ?>&tab=troubleshooting&auto_update"><?php echo TrustindexWoocommercePlugin::___("Enable"); ?></a>
<div class="ti-notice notice-warning">
<p><?php echo TrustindexWoocommercePlugin::___("You should enable it, to get new features and fixes automatically, right after they published!"); ?></p>
</div>
<?php endif; ?>
</li>
</ul>
</li>
<?php if($css): ?>
<li>
CSS
<ul>
<li><?php
$upload_dir = dirname($trustindex_woocommerce->getCssFile());
echo TrustindexWoocommercePlugin::___('writing permission') .' (<strong>'. $upload_dir .'</strong>): '. (is_writable($upload_dir) ? $yes_icon : $no_icon); ?>
</li>
<li>
<?php echo TrustindexWoocommercePlugin::___('CSS content:'); ?>
<?php
if(is_file($trustindex_woocommerce->getCssFile()))
{
$content = file_get_contents($trustindex_woocommerce->getCssFile());
if($content === $css)
{
echo $yes_icon;
}
else
{
echo $no_icon .' '. TrustindexWoocommercePlugin::___("corrupted") .'
<div class="ti-notice notice-warning">
<p><a href="?page='. sanitize_text_field($_GET['page']) .'&tab=troubleshooting&delete_css">'. TrustindexWoocommercePlugin::___("Delete the CSS file at <strong>%s</strong>.", [ $trustindex_woocommerce->getCssFile() ]) .'</a></p>
</div>';
}
}
else
{
echo $no_icon;
}
?>
<span class="ti-checkbox row" style="margin-top: 5px">
<input type="checkbox" value="1" <?php if($css_inline): ?>checked<?php endif;?> onchange="window.location.href = '?page=<?php echo sanitize_text_field($_GET['page']); ?>&tab=troubleshooting&toggle_css_inline=' + (this.checked ? 1 : 0)">
<label><?php echo TrustindexWoocommercePlugin::___("Enable CSS internal loading"); ?></label>
</span>
</li>
</ul>
</li>
<?php endif; ?>
<li>
<?php echo TrustindexWoocommercePlugin::___('If you are using cacher plugin, you should:'); ?>
<ul>
<li><?php echo TrustindexWoocommercePlugin::___('clear the cache'); ?></li>
<li><?php echo TrustindexWoocommercePlugin::___("exclude Trustindex's JS file:"); ?> <strong><?php echo 'https://cdn.trustindex.io/'; ?>loader.js</strong>
<ul>
<li><a href="#" onclick="jQuery('#list-w3-total-cache').toggle(); return false;">W3 Total Cache</a>
<ol id="list-w3-total-cache" style="display: none;">
<li><?php echo TrustindexWoocommercePlugin::___('Navigate to'); ?> "Performance" > "Minify"</li>
<li><?php echo TrustindexWoocommercePlugin::___('Scroll to'); ?> "Never minify the following JS files"</li>
<li><?php echo TrustindexWoocommercePlugin::___('In a new line, add'); ?> https://cdn.trustindex.io/*</li>
<li><?php echo TrustindexWoocommercePlugin::___('Save'); ?></li>
</ol>
</li>
</ul>
</li>
</ul>
</li>
<li>
<?php
$plugin_url = 'https://wordpress.org/support/plugin/' . $trustindex_woocommerce->get_plugin_slug();
$screenshot_url = 'https://snipboard.io';
$screencast_url = 'https://streamable.com/upload-video';
$pastebin_url = 'https://pastebin.com';
echo TrustindexWoocommercePlugin::___("If the problem/question still exists, please create an issue here: %s", [ '<a href="'. $plugin_url .'" target="_blank">'. $plugin_url .'</a>' ]);
?>
<br />
<?php echo TrustindexWoocommercePlugin::___('Please help us with some information:'); ?>
<ul>
<li><?php echo TrustindexWoocommercePlugin::___('Describe your problem'); ?></li>
<li><?php echo TrustindexWoocommercePlugin::___('You can share a screenshot with %s', [ '<a href="'. $screenshot_url .'" target="_blank">'. $screenshot_url .'</a>' ]); ?></li>
<li><?php echo TrustindexWoocommercePlugin::___('You can share a screencast video with %s', [ '<a href="'. $screencast_url .'" target="_blank">'. $screencast_url .'</a>' ]); ?></li>
<li><?php echo TrustindexWoocommercePlugin::___('If you have an (webserver) error log, you can copy it to the issue, or link it with %s', [ '<a href="'. $pastebin_url .'" target="_blank">'. $pastebin_url .'</a>' ]); ?></li>
<li><?php echo TrustindexWoocommercePlugin::___('And include the information below:'); ?></li>
</ul>
</li>
</ul>
<?php
$dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'customer-reviews-for-woocommerce.php';
$plugin_data = get_plugin_data( $dir );
?>
<?php
$memory_limit = "N/A";
if(ini_get('memory_limit'))
{
$memory_limit = filter_var(ini_get('memory_limit'), FILTER_SANITIZE_STRING);
}
$upload_max = "N/A";
if (ini_get('upload_max_filesize'))
{
$upload_max = filter_var(ini_get('upload_max_filesize'), FILTER_SANITIZE_STRING);
}
$post_max = "N/A";
if (ini_get('post_max_size'))
{
$post_max = filter_var(ini_get('post_max_size'), FILTER_SANITIZE_STRING);
}
$max_execute = "N/A";
if (ini_get('max_execution_time'))
{
$max_execute = filter_var(ini_get('max_execution_time'));
}
?>
<textarea class="ti-troubleshooting-info" readonly>
URL: <?php echo esc_url(get_option('siteurl')) ."\n"; ?>
MySQL Version: <?php echo esc_html($wpdb->db_version()) ."\n"; ?>
WP Table Prefix: <?php echo esc_html($wpdb->prefix) ."\n"; ?>
WP Version: <?php echo esc_html($wp_version) ."\n"; ?>
Server Name: <?php echo esc_html($_SERVER['SERVER_NAME']) ."\n"; ?>
Cookie Domain: <?php $cookieDomain = parse_url(strtolower(get_bloginfo('wpurl'))); echo esc_html($cookieDomain['host']) ."\n"; ?>
CURL Library Present: <?php echo (function_exists('curl_init') ? "Yes" : "No") ."\n"; ?>
CSS path: <?php echo esc_html($trustindex_woocommerce->getCssFile()) ."\n\n"; ?>
PHP Info: <?php echo "\n\t"; ?>
Version: <?php echo esc_html(phpversion()) ."\n\t"; ?>
Memory Usage: <?php echo round(memory_get_usage() / 1024 / 1024, 2) . "MB\n\t"; ?>
Memory Limit: <?php echo esc_html($memory_limit) . "\n\t"; ?>
Max Upload Size: <?php echo esc_html($upload_max) . "\n\t"; ?>
Max Post Size: <?php echo esc_html($post_max) . "\n\t"; ?>
Allow URL fopen: <?php echo (ini_get('allow_url_fopen') ? "On" : "Off") . "\n\t"; ?>
Allow URL Include: <?php echo (ini_get('allow_url_include') ? "On" : "Off") . "\n\t"; ?>
Display Errors: <?php echo (ini_get('display_errors') ? "On" : "Off") . "\n\t"; ?>
Max Script Execution Time: <?php echo esc_html($max_execute) . " seconds\n\t"; ?>
WP_HTTP_BLOCK_EXTERNAL: <?php echo (defined('WP_HTTP_BLOCK_EXTERNAL') ? var_export(WP_HTTP_BLOCK_EXTERNAL, true) : 'not defined') . "\n\t"; ?>
WP_ACCESSIBLE_HOSTS: <?php echo (defined('WP_ACCESSIBLE_HOSTS') ? WP_ACCESSIBLE_HOSTS : 'not defined') . "\n\n"; ?>
Plugin: <?php echo esc_html($plugin_data['Name']) ."\n"; ?>
Plugin Version: <?php echo esc_html($plugin_data['Version']) ."\n"; ?>
Options: <?php foreach($trustindex_woocommerce->get_option_names() as $opt_name) {
if($opt_name == "css-content")
{
continue;
}
$option = get_option($trustindex_woocommerce->get_option_name( $opt_name ));
echo "\n\t". esc_html($opt_name) .": ";
if($opt_name == "page-details" || is_array($option))
{
if(isset($option['reviews']))
{
unset($option['reviews']);
}
echo esc_html(str_replace("\n", "\n\t\t", print_r($option, true)));
}
else if($opt_name == 'download-timestamp' && $option)
{
echo date('Y-m-d H:i:s', esc_html($option));
}
else
{
echo esc_html($option);
}
}
echo "\n\n"; ?>
Reviews: <?php echo esc_html(str_replace("\n", "\n\t", print_r($reviews, true))) ."\n\n\t"; ?>
CSS: <?php echo esc_html(get_option($trustindex_woocommerce->get_option_name('css-content'))) ."\n\n"; ?>
Active Theme: <?php
if (!function_exists('wp_get_theme'))
{
$theme = get_theme(get_current_theme());
echo esc_html($theme['Name'] . ' ' . $theme['Version']);
}
else
{
$theme = wp_get_theme();
echo esc_html($theme->Name . ' ' . $theme->Version);
}
echo "\n"; ?>
Plugins: <?php foreach (get_plugins() as $key => $plugin) {
echo "\n\t". esc_html($plugin['Name'].' ('.$plugin['Version'] . (is_plugin_active($key) ? ' - active' : '') . ')');
} ?>
</textarea>
<a href=".ti-troubleshooting-info" class="btn-text btn-copy2clipboard ti-pull-right ti-tooltip toggle-tooltip ti-tooltip-left">
<?php echo TrustindexWoocommercePlugin::___("Copy to clipboard") ;?>
<span class="ti-tooltip-message">
<span style="color: #00ff00; margin-right: 2px">✓</span>
<?php echo TrustindexWoocommercePlugin::___("Copied"); ?>
</span>
</a>
<div class="clear"></div>
</div>
<div class="ti-box">
<div class="ti-header"><?php echo TrustindexWoocommercePlugin::___("Re-create plugin"); ?></div>
<p><?php echo TrustindexWoocommercePlugin::___('Re-create the database tables of the plugin.<br />Please note: this removes all settings and reviews.'); ?></p>
<a href="?page=<?php echo esc_attr($_GET['page']); ?>&tab=setup&recreate" class="btn-text btn-refresh ti-pull-right" data-loading-text="<?php echo TrustindexWoocommercePlugin::___("Loading") ;?>" style="margin-left: 0"><?php echo TrustindexWoocommercePlugin::___("Re-create plugin"); ?></a>
<div class="clear"></div>
</div>
<div class="ti-box">
<div class="ti-header"><?php echo TrustindexWoocommercePlugin::___("Translation"); ?></div>
<p>
<?php echo TrustindexWoocommercePlugin::___('If you notice an incorrect translation in the plugin text, please report it here:'); ?>
 <a href="mailto:support@trustindex.io">support@trustindex.io</a>
</p>
</div>