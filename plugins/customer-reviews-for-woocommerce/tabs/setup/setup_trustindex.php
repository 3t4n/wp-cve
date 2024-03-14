<?php
$ti_error = null;
$ti_success = "";
if(isset($_COOKIE['ti-success']))
{
$ti_success = sanitize_text_field($_COOKIE['ti-success']);
setcookie('ti-success', '', time() - 60, "/");
}
$page_url = "admin.php?page=$_page&tab=$selected_tab&step=1";
$sanitized_email = $current_user->user_email;
if($ti_command == "connect")
{
check_admin_referer('ti-woocommerce-connect');
update_option($trustindex_woocommerce->get_option_name("domain"), sanitize_text_field($_POST['domain']));
update_option($trustindex_woocommerce->get_option_name("subscription-id"), sanitize_text_field($_POST['subscription_id']));
update_option($trustindex_woocommerce->get_option_name("source-id"), sanitize_text_field($_POST['source_id']));
$GLOBALS['wp_object_cache']->delete($trustindex_woocommerce->get_option_name('subscription-id'), 'options');
$trustindex_woocommerce->save_trustindex();
$lang = strtolower(substr(get_locale(), 0, 2));
if(!isset(TrustindexWoocommercePlugin::$widget_languages[$lang]))
{
$lang = 'en';
}
update_option($trustindex_woocommerce->get_option_name('lang'), $lang, false);
header("Location: admin.php?page=$_page&tab=setup");
exit;
}
else if($ti_command == 'disconnect')
{
delete_option($trustindex_woocommerce->get_option_name("subscription-id"));
delete_option($trustindex_woocommerce->get_option_name("source-id"));
delete_option($trustindex_woocommerce->get_option_name("domain"));
delete_option($trustindex_woocommerce->get_option_name('campaign-active'));
delete_option($trustindex_woocommerce->get_option_name('widget-setted-up'));
delete_option($trustindex_woocommerce->get_option_name('scss-set'));
delete_option($trustindex_woocommerce->get_option_name('style-id'));
delete_option($trustindex_woocommerce->get_option_name('review-content'));
$wpdb->query("DROP TABLE IF EXISTS ". $trustindex_woocommerce->get_noreg_tablename());
setcookie('ti-success', 'disconnected', time() + 60, "/");
header('Location: '. $page_url);
exit;
}
?>
<div class="ti-box">
<?php if ($ti_success == "disconnected"): ?>
<?php echo TrustindexWoocommercePlugin::get_noticebox("success", TrustindexWoocommercePlugin::___('Trustindex account successfully disconnected!')); ?>
<?php endif; ?>
<?php
$ti_sub_id = $trustindex_woocommerce->is_trustindex_connected();
if(!$ti_sub_id): ?>
<h1 class="ti-free-title">
<?php echo TrustindexWoocommercePlugin::___('Create Your Review Summary Page'); ?>
<a href="?page=<?php echo $_page; ?>&tab=setup" class="ti-back-icon"><?php echo TrustindexWoocommercePlugin::___('Back'); ?></a>
</h1>
<p><?php echo TrustindexWoocommercePlugin::___("Set up your company's page, where you can collect your reviews. All your reviews will be listed on this page."); ?></p>
<p><?php echo TrustindexWoocommercePlugin::___("Fill out your website's details!"); ?></p>
<hr />
<div class="ti-row">
<form id="ti-setup-trustindex" class="box-content ti-col-6" method="post" action="">
<input type="hidden" name="command" value="connect" />
<input type="hidden" name="domain" class="field-domain" value="" />
<input type="hidden" name="source_id" value="" />
<input type="hidden" name="subscription_id" value="" />
<?php wp_nonce_field('ti-woocommerce-connect'); ?>
<div id="ti-domain-check" class="ti-connect-tab" style="display: block">
<div class="ti-notice notice-error notice-invalid-domain hidden">
<p><?php echo TrustindexWoocommercePlugin::___("You must provide a valid domain!"); ?></p>
</div>
<div class="ti-notice notice-error notice-invalid-email hidden">
<p><?php echo TrustindexWoocommercePlugin::___("You must provide a valid e-mail!"); ?></p>
</div>
<div class="ti-notice notice-error notice-empty hidden">
<p><?php echo TrustindexWoocommercePlugin::___("You must fill the required fields!"); ?></p>
</div>
<div class="ti-notice notice-error notice-exists hidden">
<p><?php echo TrustindexWoocommercePlugin::___("E-mail already exists!"); ?></p>
</div>
<div class="ti-input-flex-row">
<div class="ti-input-field">
<label><?php echo TrustindexWoocommercePlugin::___('Domain'); ?>*</label>
<input type="text"
placeholder="<?php echo TrustindexWoocommercePlugin::___('Domain'); ?>"
class="form-control field-domain"
required="required"
value="<?php echo esc_attr($_SERVER['SERVER_NAME']); ?>"
maxlength="100"
/>
</div>
</div>
<div class="ti-input-flex-row">
<div class="ti-input-field">
<label>E-mail*</label>
<input type="email"
placeholder="E-mail"
class="form-control"
required="required"
value="<?php echo $current_user->user_email; ?>"
maxlength="255"
/>
</div>
</div>
<div class="ti-input-flex-row">
<div class="ti-input-field">
<label><?php echo TrustindexWoocommercePlugin::___('Webshop name'); ?>*</label>
<input type="text"
placeholder="<?php echo TrustindexWoocommercePlugin::___('Webshop name'); ?>"
class="form-control"
required="required"
name="name"
value="<?php echo get_bloginfo('name'); ?>"
maxlength="100"
/>
</div>
</div>
<div class="ti-input-flex-row">
<div class="ti-input-field">
<label><?php echo TrustindexWoocommercePlugin::___('Webshop description'); ?>*</label>
<textarea rows="5" class="form-control" required="required" name="description" maxlength="600"><?php echo get_bloginfo('description'); ?></textarea>
</div>
</div>
<div class="ti-input-flex-row">
<div class="ti-input-field">
<label><?php echo TrustindexWoocommercePlugin::___('Webshop phone'); ?></label>
<input type="text"
placeholder="<?php echo TrustindexWoocommercePlugin::___('Webshop phone'); ?>"
class="form-control"
name="phone"
value=""
maxlength="20"
/>
</div>
</div>
<div class="ti-input-flex-row">
<div class="ti-input-field">
<label><?php echo TrustindexWoocommercePlugin::___('Default Language'); ?></label>
<select class="form-control" name="language">
<?php foreach(TrustindexWoocommercePlugin::$company_default_languages as $lang => $name): ?>
<option value="<?php echo $lang; ?>" <?php if($lang == substr(get_locale(), 0, 2)): ?>selected<?php endif; ?>><?php echo $name; ?></option>
<?php endforeach; ?>
</select>
</div>
</div>
<p class="text-center ti-domain-check-stage">
<a class="btn-text btn-check-domain" href="#" data-loading-text="<?php echo TrustindexWoocommercePlugin::___("Loading") ;?>">
<?php echo TrustindexWoocommercePlugin::___('Create review summary page'); ?>
<span class="ti-button-tooltip">
<span class="dashicons dashicons-info"></span>
<?php echo TrustindexWoocommercePlugin::___("We will check if the given domain is already available in the Trustindex system."); ?>
</span>
</a>
</p>
<div class="ti-domain-found-stage" style="display: none">
<div class="ti-notice notice-info" id="ti-found-notification" data-text="<?php echo TrustindexWoocommercePlugin::___("We have found a profile for <strong>DOMAIN_NAME</strong> domain."); ?>"></div>
<p class="text-center">
<a class="btn-text btn-primary btn-switch-tab" href="#ti-user-connect"><?php echo TrustindexWoocommercePlugin::___('Login'); ?></a>
<a class="btn-text btn-register" href="#" style="max-width: 300px">
<?php echo TrustindexWoocommercePlugin::___('Someone else registered the account...'); ?>
<span class="ti-button-tooltip">
<span class="dashicons dashicons-info"></span>
<?php echo TrustindexWoocommercePlugin::___("Create a new profile and write an e-mail from your official/company mailbox to our support staff to clarify the situation (we will transfer the profile of your domain to you)."); ?>
</span>
</a>
</p>
</div>
</div>
<div id="ti-user-connect" class="ti-connect-tab">
<h1 class="ti-free-title"><?php echo TrustindexWoocommercePlugin::___('Login'); ?></h1>
<div class="ti-notice notice-error notice-invalid hidden">
<p><?php echo TrustindexWoocommercePlugin::___("You must provide a password and a valid e-mail!"); ?></p>
</div>
<div class="ti-notice notice-error notice-wrong hidden">
<p><?php echo TrustindexWoocommercePlugin::___("Wrong e-mail or password!"); ?></p>
</div>
<div class="ti-input-flex-row">
<div class="ti-input-field">
<label>E-mail</label>
<input type="email"
placeholder="E-mail"
class="form-control"
required="required"
value="<?php echo $current_user->user_email; ?>"
/>
</div>
</div>
<div class="ti-input-flex-row">
<div class="ti-input-field">
<label><?php echo TrustindexWoocommercePlugin::___('Password'); ?></label>
<input type="password"
name="password"
placeholder="<?php echo TrustindexWoocommercePlugin::___('Password'); ?>"
class="form-control"
required="required"
/>
<span class="dashicons dashicons-visibility ti-toggle-password"></span>
</div>
</div>
<p class="text-center">
<a class="btn-text btn-forgot-password" href="<?php echo 'https://admin.trustindex.io/'; ?>forgot-password" target="_blank"><?php echo TrustindexWoocommercePlugin::___('Have you forgotten your password?'); ?></a>
<a class="btn-text btn-primary btn-login" href="#" data-loading-text="<?php echo TrustindexWoocommercePlugin::___("Loading") ;?>"><?php echo TrustindexWoocommercePlugin::___('Connect account'); ?></a>
</p>
</div>
<div id="ti-domain-choose" class="ti-connect-tab">
<div class="ti-notice notice-info">
<p><?php echo TrustindexWoocommercePlugin::___("The given domain do not found for your account, please choose a domain from the following options."); ?></p>
</div>
<p id="ti-choose-domain-template" class="ti-domain-choose-item" style="display: none">
<span></span>
<a class="btn-text btn-choose-domain ti-pull-right" href="#" data-loading-text="<?php echo TrustindexWoocommercePlugin::___("Loading") ;?>"><?php echo TrustindexWoocommercePlugin::___('Choose'); ?></a>
</p>
<div id="ti-domain-choose-list"></div>
</div>
</form>
<div class="ti-col-6"></div>
</div>
<?php else: ?>
<h1 class="ti-free-title">
<?php echo TrustindexWoocommercePlugin::___('Setup review summary page'); ?>
<a href="?page=<?php echo $_page; ?>&tab=setup" class="ti-back-icon"><?php echo TrustindexWoocommercePlugin::___('Back'); ?></a>
</h1>
<?php
$ti_widgets = $trustindex_woocommerce->get_trustindex_widgets();
$ti_package = is_array($ti_widgets) && $ti_widgets && isset($ti_widgets[0]['package']) ? $ti_widgets[0]['package'] : null;
?>
<p>
<?php echo TrustindexWoocommercePlugin::___("Your Trustindex account is connected."); ?><br />
- <?php echo TrustindexWoocommercePlugin::___('Your subscription ID:'); ?> <strong><?php echo $ti_sub_id; ?></strong><br />
- Domain: <strong><?php echo get_option($trustindex_woocommerce->get_option_name('domain'), ""); ?></strong><br />
<?php if ($ti_package): ?>
- <?php echo TrustindexWoocommercePlugin::___('Your package:'); ?> <strong><?php echo TrustindexWoocommercePlugin::___($ti_package); ?></strong>
<?php endif; ?>
</p>
<a href="?page=<?php echo $_page; ?>&tab=setup&step=1&command=disconnect" class="btn-text" data-loading-text="<?php echo TrustindexWoocommercePlugin::___("Loading") ;?>"><?php echo TrustindexWoocommercePlugin::___("Disconnect"); ?></a>
<br /><br />
<p>
<a href="https://admin.trustindex.io/<?php echo 'site/edit'; ?>" class="btn-text btn-primary" target="_blank"><?php echo TrustindexWoocommercePlugin::___("Edit your company page"); ?></a>
<a href="<?php echo $trustindex_woocommerce->getCompanyPage(); ?>" class="btn-text" target="_blank"><?php echo TrustindexWoocommercePlugin::___("View your company page"); ?></a>
</p>
<?php endif; ?>
</div>