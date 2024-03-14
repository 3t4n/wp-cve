<?php
defined('ABSPATH') or die('No script kiddies please!');
$autoUpdates = get_option('auto_update_plugins', []);
$pluginSlug = "free-facebook-reviews-and-recommendations-widgets/free-facebook-reviews-and-recommendations-widgets.php";
if (isset($_GET['auto_update'])) {
check_admin_referer('ti-auto-update');
if (!in_array($pluginSlug, $autoUpdates)) {
$autoUpdates []= $pluginSlug;
update_option('auto_update_plugins', $autoUpdates, false);
}
header('Location: admin.php?page=' . sanitize_text_field($_GET['page']) . '&tab=advanced');
exit;
}
if (isset($_GET['toggle_css_inline'])) {
check_admin_referer('ti-toggle-css');
$v = (int)$_GET['toggle_css_inline'];
update_option($pluginManagerInstance->get_option_name('load-css-inline'), $v, false);
if ($v && is_file($pluginManagerInstance->getCssFile())) {
unlink($pluginManagerInstance->getCssFile());
}
header('Location: admin.php?page=' . sanitize_text_field($_GET['page']) . '&tab=advanced');
exit;
}
if (isset($_GET['delete_css'])) {
check_admin_referer('ti-delete-css');
if (is_file($pluginManagerInstance->getCssFile())) {
unlink($pluginManagerInstance->getCssFile());
}
header('Location: admin.php?page=' . sanitize_text_field($_GET['page']) . '&tab=advanced');
exit;
}
if (isset($_POST['save-notification-email'])) {
check_admin_referer('ti-notification-email-save');
$type = strtolower(trim(sanitize_text_field($_POST['type'])));
$email = strtolower(trim(sanitize_text_field($_POST['save-notification-email'])));
$pluginManagerInstance->setNotificationParam($type, 'email', $email);
exit;
}
$yesIcon = '<span class="dashicons dashicons-yes-alt"></span>';
$noIcon = '<span class="dashicons dashicons-dismiss"></span>';
$pluginUpdated = ($pluginManagerInstance->get_plugin_current_version() <= "11.6");
$cssInline = get_option($pluginManagerInstance->get_option_name('load-css-inline'), 0);
$css = get_option($pluginManagerInstance->get_option_name('css-content'));
?>
<h1 class="ti-header-title"><?php echo __('Advanced', 'trustindex-plugin'); ?></h1>
<div class="ti-box">
<div class="ti-box-header"><?php echo __('Notifications', 'trustindex-plugin'); ?></div>
<ul class="ti-troubleshooting-checklist">
<li>
<?php echo __('Review download available', 'trustindex-plugin'); ?>
<ul>
<li>
<?php
$isNotificationActive = !$pluginManagerInstance->getNotificationParam('review-download-available', 'hidden', false);
echo __('Notification', 'trustindex-plugin') .': '. ($isNotificationActive ? $yesIcon : $noIcon); ?>
<?php if ($isNotificationActive): ?>
<a href="?page=<?php echo sanitize_text_field($_GET['page']); ?>&tab=advanced&notification=review-download-available&action=hide" class="ti-btn ti-btn-loading-on-click"><?php echo __('Disable', 'trustindex-plugin'); ?></a>
<?php else: ?>
<a href="?page=<?php echo sanitize_text_field($_GET['page']); ?>&tab=advanced&notification=review-download-available&action=unhide" class="ti-btn ti-btn-loading-on-click"><?php echo __('Enable', 'trustindex-plugin'); ?></a>
<?php endif; ?>
</li>

</ul>
</li>
<li>
<?php echo __('Review download finished', 'trustindex-plugin'); ?>
<ul>
<li>
<?php
$isNotificationActive = !$pluginManagerInstance->getNotificationParam('review-download-finished', 'hidden', false);
echo __('Notification', 'trustindex-plugin') .': '. ($isNotificationActive ? $yesIcon : $noIcon); ?>
<?php if ($isNotificationActive): ?>
<a href="?page=<?php echo sanitize_text_field($_GET['page']); ?>&tab=advanced&notification=review-download-finished&action=hide" class="ti-btn ti-btn-loading-on-click"><?php echo __('Disable', 'trustindex-plugin'); ?></a>
<?php else: ?>
<a href="?page=<?php echo sanitize_text_field($_GET['page']); ?>&tab=advanced&notification=review-download-finished&action=unhide" class="ti-btn ti-btn-loading-on-click"><?php echo __('Enable', 'trustindex-plugin'); ?></a>
<?php endif; ?>
</li>
<li>
<div class="ti-notification-email">
<div class="ti-notice ti-notice-error">
<p><?php echo __('Invalid email', 'trustindex-plugin'); ?></p>
</div>
<div class="ti-inner">
<span><?php echo __('Send email notification to:', 'trustindex-plugin'); ?></span>
<input type="text" data-type="review-download-finished" placeholder="email@example.com" data-nonce="<?php echo wp_create_nonce('ti-notification-email-save'); ?>" class="ti-form-control" value="<?php echo $pluginManagerInstance->getNotificationParam('review-download-finished', 'email', get_option('admin_email')); ?>" />
<a href="#" class="ti-btn btn-notification-email-save"><?php echo __('Save', 'trustindex-plugin'); ?></a>
</div>
<div class="ti-info-text"><?php echo __('Leave the field blank if you do not want email notification.', 'trustindex-plugin'); ?></div>
</div>
</li>
</ul>
</li>
</ul>
</div>
<div class="ti-box">
<div class="ti-box-header"><?php echo __("Troubleshooting", 'trustindex-plugin'); ?></div>
<p class="ti-bold"><?php echo __('If you have any problem, you should try these steps:', 'trustindex-plugin'); ?></p>
<ul class="ti-troubleshooting-checklist">
<li>
<?php echo __("Trustindex plugin", 'trustindex-plugin'); ?>
<ul>
<li>
<?php echo __('Use the latest version:', 'trustindex-plugin') .' '. ($pluginUpdated ? $yesIcon : $noIcon); ?>
<?php if (!$pluginUpdated): ?>
<a href="/wp-admin/plugins.php" class="ti-btn ti-btn-loading-on-click"><?php echo __('Update', 'trustindex-plugin'); ?></a>
<?php endif; ?>
</li>
<li>
<?php echo __('Use automatic plugin update:', 'trustindex-plugin') .' '. (in_array($pluginSlug, $autoUpdates) ? $yesIcon : $noIcon); ?>
<?php if(!in_array($pluginSlug, $autoUpdates)): ?>
<a href="<?php echo wp_nonce_url('?page='. sanitize_text_field($_GET['page']) .'&tab=advanced&auto_update', 'ti-auto-update'); ?>" class="ti-btn ti-btn-loading-on-click"><?php echo __("Enable", 'trustindex-plugin'); ?></a>
<div class="ti-notice ti-notice-warning">
<p><?php echo __("You should enable it, to get new features and fixes automatically, right after they published!", 'trustindex-plugin'); ?></p>
</div>
<?php endif; ?>
</li>
</ul>
</li>
<?php if ($css): ?>
<li>
CSS
<ul>
<li><?php
$uploadDir = dirname($pluginManagerInstance->getCssFile());
echo __('writing permission', 'trustindex-plugin') .' (<strong>'. $uploadDir .'</strong>): '. (is_writable($uploadDir) ? $yesIcon : $noIcon); ?>
</li>
<li>
<?php echo __('CSS content:', 'trustindex-plugin'); ?>
<?php
if (is_file($pluginManagerInstance->getCssFile())) {
$content = file_get_contents($pluginManagerInstance->getCssFile());
if ($content === $css) {
echo $yesIcon;
}
else {
echo $noIcon .' '. __("corrupted", 'trustindex-plugin') .'
<div class="ti-notice ti-notice-warning">
<p><a href="'. wp_nonce_url('?page='. sanitize_text_field($_GET['page']) .'&tab=advanced&delete_css', 'ti-delete-css') .'">'. sprintf(__("Delete the CSS file at <strong>%s</strong>.", 'trustindex-plugin'), $pluginManagerInstance->getCssFile()) .'</a></p>
</div>';
}
}
else {
echo $noIcon;
}
?>
<span class="ti-checkbox ti-checkbox-row" style="margin-top: 5px">
<input type="checkbox" value="1" <?php if ($cssInline): ?>checked<?php endif;?> onchange="window.location.href = '?page=<?php echo sanitize_text_field($_GET['page']); ?>&tab=advanced&_wpnonce=<?php echo wp_create_nonce('ti-toggle-css'); ?>&toggle_css_inline=' + (this.checked ? 1 : 0)">
<label><?php echo __('Enable CSS internal loading', 'trustindex-plugin'); ?></label>
</span>
</li>
</ul>
</li>
<?php endif; ?>
<li>
<?php echo __('If you are using cacher plugin, you should:', 'trustindex-plugin'); ?>
<ul>
<li><?php echo __('clear the cache', 'trustindex-plugin'); ?></li>
<li><?php echo __("exclude Trustindex's JS file:", 'trustindex-plugin'); ?> <strong><?php echo 'https://cdn.trustindex.io/'; ?>loader.js</strong>
<ul>
<li><a href="#" onclick="jQuery('#list-w3-total-cache').toggle(); return false;">W3 Total Cache</a>
<ol id="list-w3-total-cache" style="display: none;">
<li><?php echo __('Navigate to', 'trustindex-plugin'); ?> "Performance" > "Minify"</li>
<li><?php echo __('Scroll to', 'trustindex-plugin'); ?> "Never minify the following JS files"</li>
<li><?php echo __('In a new line, add', 'trustindex-plugin'); ?> https://cdn.trustindex.io/*</li>
<li><?php echo __('Save', 'trustindex-plugin'); ?></li>
</ol>
</li>
</ul>
</li>
</ul>
</li>
<li>
<?php
$pluginUrl = 'https://wordpress.org/support/plugin/' . $pluginManagerInstance->get_plugin_slug();
$screenshotUrl = 'https://snipboard.io';
$screencastUrl = 'https://streamable.com/upload-video';
$pastebinUrl = 'https://pastebin.com';
echo sprintf(__('If the problem/question still exists, please create an issue here: %s', 'trustindex-plugin'), '<a href="'. $pluginUrl .'" target="_blank">'. $pluginUrl .'</a>');
?>
<br />
<?php echo __('Please help us with some information:', 'trustindex-plugin'); ?>
<ul>
<li><?php echo __('Describe your problem', 'trustindex-plugin'); ?></li>
<li><?php echo sprintf(__('You can share a screenshot with %s', 'trustindex-plugin'), '<a href="'. $screenshotUrl .'" target="_blank">'. $screenshotUrl .'</a>'); ?></li>
<li><?php echo sprintf(__('You can share a screencast video with %s', 'trustindex-plugin'), '<a href="'. $screencastUrl .'" target="_blank">'. $screencastUrl .'</a>'); ?></li>
<li><?php echo sprintf(__('If you have an (webserver) error log, you can copy it to the issue, or link it with %s', 'trustindex-plugin'), '<a href="'. $pastebinUrl .'" target="_blank">'. $pastebinUrl .'</a>'); ?></li>
<li><?php echo __('And include the information below:', 'trustindex-plugin'); ?></li>
</ul>
</li>
</ul>
<textarea class="ti-troubleshooting-info" readonly><?php include $pluginManagerInstance->get_plugin_dir() . 'include' . DIRECTORY_SEPARATOR . 'troubleshooting.php'; ?></textarea>
<a href=".ti-troubleshooting-info" class="ti-btn ti-pull-right ti-tooltip toggle-tooltip btn-copy2clipboard">
<?php echo __('Copy to clipboard', 'trustindex-plugin') ;?>
<span class="ti-tooltip-message">
<span style="color: #00ff00; margin-right: 2px">✓</span>
<?php echo __('Copied', 'trustindex-plugin'); ?>
</span>
</a>
<div class="clear"></div>
</div>
<div class="ti-box">
<div class="ti-box-header"><?php echo __('Re-create plugin', 'trustindex-plugin'); ?></div>
<p><?php echo __('Re-create the database tables of the plugin.<br />Please note: this removes all settings and reviews.', 'trustindex-plugin'); ?></p>
<a href="<?php echo wp_nonce_url('?page='. esc_attr(sanitize_text_field($_GET['page'])) .'&tab=free-widget-configurator&recreate', 'ti-recreate'); ?>" class="ti-btn ti-btn-loading-on-click ti-pull-right"><?php echo __('Re-create plugin', 'trustindex-plugin'); ?></a>
<div class="clear"></div>
</div>
<div class="ti-box">
<div class="ti-box-header"><?php echo __('Translation', 'trustindex-plugin'); ?></div>
<p>
<?php echo __('If you notice an incorrect translation in the plugin text, please report it here:', 'trustindex-plugin'); ?>
 <a href="mailto:support@trustindex.io">support@trustindex.io</a>
</p>
</div>