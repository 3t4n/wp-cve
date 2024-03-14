<?php
defined('ABSPATH') or die('No script kiddies please!');
$tiSuccess = "";
if (isset($_COOKIE['ti-success'])) {
$tiSuccess = sanitize_text_field($_COOKIE['ti-success']);
setcookie('ti-success', '', time() - 60, "/");
}
$tiError = null;
$tiCommand = isset($_POST['command']) ? sanitize_text_field($_POST['command']) : null;
if (!in_array($tiCommand, [ 'connect', 'disconnect' ])) {
$tiCommand = null;
}
if ($tiCommand === 'connect') {
check_admin_referer('connect-reg_' . $pluginManagerInstance->get_plugin_slug());
$sanitizedEmail = sanitize_email($_POST['email']);
$sanitizedPassword = stripslashes(sanitize_text_field(htmlentities($_POST['password'], ENT_QUOTES)));
if ($sanitizedEmail && $sanitizedPassword) {
$serverOutput = $pluginManagerInstance->connect_trustindex_api([
'signin' => [
'username' => $sanitizedEmail,
'password' => html_entity_decode($sanitizedPassword),
],
'callback' => bin2hex(openssl_random_pseudo_bytes(10))
], 'connect');
if ($serverOutput['success']) {
setcookie('ti-success', 'connected', time() + 60, '/');
header('Location: admin.php?page=' . sanitize_text_field($_GET['page']) . '&tab=' . sanitize_text_field($_GET['tab']));
exit;
}
else {
$tiError = __('Wrong e-mail or password!', 'trustindex-plugin');
}
}
else {
$tiError = __('You must provide a password and a valid e-mail!', 'trustindex-plugin');
}
}
else if ($tiCommand === 'disconnect') {
check_admin_referer('disconnect-reg_' . $pluginManagerInstance->get_plugin_slug());
delete_option($pluginManagerInstance->get_option_name('subscription-id'));
setcookie('ti-success', 'disconnected', time() + 60, '/');
header('Location: admin.php?page=' . sanitize_text_field($_GET['page']) . '&tab=' . sanitize_text_field($_GET['tab']));
exit;
}
$trustindexSubscriptionId = $pluginManagerInstance->is_trustindex_connected();
$widgetNumber = $pluginManagerInstance->get_trustindex_widget_number();
?>
<?php if (!$trustindexSubscriptionId): ?>
<h1 class="ti-header-title"><?php echo __('Log In', 'trustindex-plugin'); ?></h1>
<?php else: ?>
<h1 class="ti-header-title">Trustindex admin</h1>
<?php endif; ?>
<div class="ti-box">
<div class="ti-box-header"><?php echo __('Connect your Trustindex account', 'trustindex-plugin'); ?></div>
<?php if ($tiSuccess === 'connected'): ?>
<?php echo $pluginManager::get_noticebox('success', __('Trustindex account successfully connected!', 'trustindex-plugin')); ?>
<?php elseif ($tiSuccess === 'disconnected'): ?>
<?php echo $pluginManager::get_noticebox('success', __('Trustindex account successfully disconnected!', 'trustindex-plugin')); ?>
<?php endif; ?>
<?php if ($tiError): ?>
<?php echo $pluginManager::get_noticebox('error', $tiError); ?>
<?php endif; ?>
<?php if ($trustindexSubscriptionId): ?>
<?php
$tiWidgets = $pluginManagerInstance->get_trustindex_widgets();
$tiPackage = is_array($tiWidgets) && $tiWidgets && isset($tiWidgets[0]['package']) ? $tiWidgets[0]['package'] : null;
?>
<p>
<?php echo sprintf(__('Your %s is connected.', 'trustindex-plugin'), __('Trustindex account', 'trustindex-plugin')); ?><br />
- <?php echo __('Your subscription ID:', 'trustindex-plugin'); ?> <strong><?php echo esc_html($trustindexSubscriptionId); ?></strong><br />
<?php if ($tiPackage): ?>
- <?php echo __('Your package:', 'trustindex-plugin'); ?> <strong><?php echo esc_html($tiPackage); ?></strong>
<?php endif; ?>
</p>
<?php if ($tiPackage === 'free'): ?>
<?php echo $pluginManager::get_noticebox('error', sprintf(__("Once the trial period has expired, the widgets will not appear. You can subscribe or switch back to the \"%s\" tab", 'trustindex-plugin'), [ __('Free Widget Configurator', 'trustindex-plugin') ])); ?>
<?php elseif ($tiPackage === 'trial'): ?>
<?php echo $pluginManager::get_noticebox('warning', sprintf(__("Once the trial period has expired, the widgets will not appear. You can subscribe or switch back to the \"%s\" tab", 'trustindex-plugin'), [ __('Free Widget Configurator', 'trustindex-plugin') ])); ?>
<?php endif; ?>
<form method="post" class="ti-mt-0" action="">
<input type="hidden" name="command" value="disconnect" />
<?php wp_nonce_field('disconnect-reg_' . $pluginManagerInstance->get_plugin_slug()); ?>
<button class="ti-btn ti-btn-loading-on-click ti-pull-right" type="submit"><?php echo __('Disconnect', 'trustindex-plugin'); ?></button>
<div class="clear"></div>
</form>
<?php else: ?>
<p><?php echo sprintf(__('You can connect your %s with your Trustindex account, and can display your widgets easier.', 'trustindex-plugin'), 'Widgets for Airbnb Reviews'); ?></p>
<form id="form-connect" method="post" action="">
<input type="hidden" name="command" value="connect" />
<?php wp_nonce_field('connect-reg_' . $pluginManagerInstance->get_plugin_slug()); ?>
<div class="ti-form-group">
<label>E-mail</label>
<input type="email" placeholder="E-mail" name="email" class="ti-form-control" required="required" id="ti-reg-email2" value="<?php echo esc_attr($current_user->user_email); ?>" />
</div>
<div class="ti-form-group ti-mb-1">
<label><?php echo __('Password', 'trustindex-plugin'); ?></label>
<input type="password" placeholder="<?php echo __('Password', 'trustindex-plugin'); ?>" name="password" class="ti-form-control" required="required" id="ti-reg-password2" />
<span class="dashicons dashicons-visibility ti-toggle-password"></span>
</div>
<p class="ti-text-center">
<button type="submit" class="ti-btn ti-mb-1"><?php echo __('CONNECT ACCOUNT', 'trustindex-plugin');?></button>
<br />
<a class="ti-btn ti-btn-default" href="<?php echo 'https://admin.trustindex.io/'; ?>forgot-password" target="_blank"><?php echo __('Have you forgotten your password?', 'trustindex-plugin'); ?></a>
<a class="ti-btn ti-btn-default" href="https://www.trustindex.io/ti-redirect.php?a=sys&c=wp-airbnb-4" target="_blank"><?php echo __('Create a new Trustindex account', 'trustindex-plugin');?></a>
</p>
</form>
<?php endif; ?>
</div>
<?php if ($trustindexSubscriptionId): ?>
<div class="ti-box">
<div class="ti-box-header"><?php echo __('Manage your Trustindex account', 'trustindex-plugin'); ?></div>
<a class="ti-btn" href="<?php echo 'https://admin.trustindex.io/'; ?>widget" target="_blank"><?php echo __("Go to Trustindex's admin!", 'trustindex-plugin'); ?></a>
</div>
<div class="ti-box">
<div class="ti-box-header"><?php echo __('Insert your widget into your wordpress site using shortcode', 'trustindex-plugin'); ?></div>
<?php if ($trustindexSubscriptionId): ?>
<?php if ($widgetNumber): ?>
<p><?php echo sprintf(__('You have got %d widgets saved in Trustindex admin.', 'trustindex-plugin'), $widgetNumber); ?></p>
<?php foreach ($tiWidgets as $wcIndex => $wc): ?>
<p class="ti-bold"><?php echo esc_html($wc['name']); ?>:</p>
<?php if ($wc['widgets']): ?>
<ul>
<?php foreach ($wc['widgets'] as $wiNum => $w): ?>
<li>
<?php echo esc_html($wiNum + 1); ?>.
<a href=".ti-w-<?php echo esc_attr($wcIndex .'-'. $wiNum); ?>" class="btn-toggle" data-ti-id="<?php echo esc_attr($w['id']); ?>"><?php echo esc_html($w['name']); ?></a>
<div style="display: none" class="ti-w-<?php echo esc_attr($wcIndex .'-'. $wiNum); ?>">
<code class="code-ti-w-<?php echo esc_attr($wcIndex .'-'. $wiNum); ?>">[<?php echo $pluginManagerInstance->get_shortcode_name(); ?> data-widget-id="<?php echo esc_attr($w['id']); ?>"]</code>
<a href=".code-ti-w-<?php echo esc_attr($wcIndex .'-'. $wiNum); ?>" class="btn-text btn-copy2clipboard"><?php echo __('Copy to clipboard', 'trustindex-plugin'); ?></a>
<br />
<br />
</div>
</li>
<?php endforeach; ?>
</ul>
<?php else: ?>
-
<?php endif; ?>
<?php endforeach; ?>
<?php else: ?>
<?php echo $pluginManager::get_noticebox('error', __('You have no widgets saved!', 'trustindex-plugin')); ?>
<?php endif; ?>
<a class="ti-btn" href="<?php echo 'https://admin.trustindex.io/'; ?>widget" target="_blank"><?php echo __('Create more!', 'trustindex-plugin'); ?></a>
<?php endif; ?>
</div>
<?php endif; ?>