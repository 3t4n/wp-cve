<?php
require_once(ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'upgrade.php');
$ti_error = null;
if($ti_command == 'setup-campaign')
{
$was_empty = false;
foreach([ 'trigger-event', 'trigger-delay', 'campaign-subject', 'campaign-text', 'sender-name', 'sender-email' ] as $field)
{
$value = $field == 'campaign-text' ? wp_kses_post(stripslashes(str_replace("\n", '<br />', $_REQUEST[ $field ]))) : sanitize_text_field($_REQUEST[ $field ]);
update_option($trustindex_woocommerce->get_option_name($field), $value, false);
if(!$value && $value != '0')
{
$was_empty = true;
}
}
dbDelta("CREATE TABLE ". $trustindex_woocommerce->get_schedule_tablename() ." (
id BIGINT(20) NOT NULL AUTO_INCREMENT,
email VARCHAR(255) NOT NULL,
order_id BIGINT(20) NOT NULL,
timestamp INT(11) NOT NULL,
sent TINYINT(1) NOT NULL DEFAULT '0',
created_at DATETIME,
PRIMARY KEY (id)
);");
if($was_empty)
{
$ti_error = TrustindexWoocommercePlugin::___('Please fill out the fields!');
}
else
{
header("Location: admin.php?page=$_page&tab=setup");
exit;
}
}
else if($ti_command == 'test-email')
{
check_admin_referer('ti-woocommerce-test-email');
$email = isset($_POST['email']) ? sanitize_email($_POST['email']) : "";
$subject = isset($_POST['subject']) ? sanitize_text_field($_POST['subject']) : "";
$text = isset($_POST['text']) ? wp_kses_post(stripslashes($_POST['text'])) : "";
if($email)
{
$trustindex_woocommerce->sendMail($email, null, $subject, $text);
}
exit;
}
$wc_mailer = WC()->mailer();
$trigger_event = get_option($trustindex_woocommerce->get_option_name('trigger-event'), "");
$trigger_delay = get_option($trustindex_woocommerce->get_option_name('trigger-delay'), 7);
$campaign_subject = get_option($trustindex_woocommerce->get_option_name('campaign-subject'), TrustindexWoocommercePlugin::getDefaultCampaignSubject());
$campaign_text = get_option($trustindex_woocommerce->get_option_name('campaign-text'), TrustindexWoocommercePlugin::getDefaultCampaignText());
$sender_email = get_option($trustindex_woocommerce->get_option_name('sender-email'), TrustindexWoocommercePlugin::getDefaultSenderEmail());
$sender_name = get_option($trustindex_woocommerce->get_option_name('sender-name'), TrustindexWoocommercePlugin::getDefaultSenderName());
$trigger_events = wc_get_order_statuses();
if(!$trigger_event)
{
if(isset($trigger_events['wc-completed']))
{
$trigger_event = 'wc-completed';
}
else if(isset($trigger_events['completed']))
{
$trigger_event = 'completed';
}
}
?>
<style>
<?php wc_get_template( 'emails/email-styles.php' ); ?>
#wrapper {
background-color: transparent !important;
padding-top: 20px;
padding-bottom: 0;
}
</style>
<div class="ti-box ti-campaign-setup">
<h1 class="ti-free-title">
<?php echo TrustindexWoocommercePlugin::___('Automate Your Review Invitations'); ?>
<a href="?page=<?php echo $_page; ?>&tab=setup" class="ti-back-icon"><?php echo TrustindexWoocommercePlugin::___('Back'); ?></a>
</h1>
<p><?php echo TrustindexWoocommercePlugin::___('Set up automation of your invitations once to get a continuous flow of verified reviews.'); ?></p>
<?php if($ti_error): ?>
<?php echo TrustindexWoocommercePlugin::get_noticebox("error", $ti_error); ?>
<?php endif; ?>
<div class="ti-notice notice-error notice-invalid-text hidden">
<p><?php echo TrustindexWoocommercePlugin::___("Email content should have a link with a \"{link}\" href attribute!"); ?></p>
</div>
<hr />
<form method="post">
<input type="hidden" name="command" value="setup-campaign" />
<?php wp_nonce_field('ti-woocommerce-campaign-setup'); ?>
<p><strong><?php echo TrustindexWoocommercePlugin::___('Timing and Frequency'); ?></strong></p>
<div class="ti-input-flex-row">
<div class="ti-input-field">
<label><?php echo TrustindexWoocommercePlugin::___('What action should trigger service review invitations?'); ?></label>
<select class="form-control" name="trigger-event">
<option value=""><?php echo TrustindexWoocommercePlugin::___("Select one..."); ?></option>
<?php foreach($trigger_events as $status => $name): ?>
<option value="<?php echo $status; ?>"<?php if($trigger_event == $status): ?> selected<?php endif; ?>>
<?php echo TrustindexWoocommercePlugin::___("Order's status changes to \"%s\"", [ $name ]); ?>
</option>
<?php endforeach; ?>
</select>
</div>
</div>
<div class="ti-input-flex-row">
<div class="ti-input-field">
<label><?php echo TrustindexWoocommercePlugin::___('How long after the trigger should immediately service review invitations be sent?'); ?></label>
<select class="form-control" name="trigger-delay">
<option value="0"<?php if($trigger_delay == 0): ?> selected<?php endif; ?>><?php echo TrustindexWoocommercePlugin::___('immediately'); ?></option>
<?php foreach([ 1, 2, 3, 4, 5, 6, 7, 14, 21 ] as $days): ?>
<option value="<?php echo $days; ?>"<?php if($trigger_delay == $days): ?> selected<?php endif; ?>><?php echo TrustindexWoocommercePlugin::___('%s days', [ $days ]); ?></option>
<?php endforeach; ?>
</select>
</div>
</div>
<hr />
<p><strong><?php echo TrustindexWoocommercePlugin::___('Email Settings'); ?></strong></p>
<div class="ti-input-flex-row">
<div class="ti-input-field">
<label><?php echo TrustindexWoocommercePlugin::___('Sender name'); ?></label>
<input type="text" class="form-control" name="sender-name" value="<?php echo $sender_name; ?>" />
</div>
</div>
<div class="ti-input-flex-row">
<div class="ti-input-field">
<label><?php echo TrustindexWoocommercePlugin::___('If customer replies to invitation, send the reply to'); ?></label>
<input type="text" class="form-control" name="sender-email" value="<?php echo $sender_email; ?>" />
</div>
</div>
<div class="ti-input-flex-row">
<div class="ti-input-field">
<label>
<?php echo TrustindexWoocommercePlugin::___('Invitations landing page to send customers to'); ?>
<a href="<?php echo $trustindex_woocommerce->getDefaultLandingPage(); ?>" target="_blank" class="ti-pull-right"><?php echo TrustindexWoocommercePlugin::___('View'); ?></a>
</label>
<input type="text" class="form-control" id="ti-email-link-url" readonly="true" value="<?php echo $trustindex_woocommerce->getDefaultLandingPage(); ?>" />
</div>
</div>
<hr />
<p><strong><?php echo TrustindexWoocommercePlugin::___('Review Invitation Email'); ?></strong></p>
<div class="ti-input-flex-row">
<div class="ti-input-field">
<label>
<?php echo TrustindexWoocommercePlugin::___('Subject'); ?>*
<a href="#" id="ti-reset-field-subject" data-value="<?php echo htmlspecialchars(TrustindexWoocommercePlugin::getDefaultCampaignSubject(), ENT_QUOTES); ?>" class="ti-pull-right"><?php echo TrustindexWoocommercePlugin::___('Reset content'); ?></a>
</label>
<input type="text" class="form-control" name="campaign-subject" placeholder="<?php echo TrustindexWoocommercePlugin::___('Subject'); ?>" value="<?php echo $campaign_subject; ?>" />
</div>
</div>
<div class="ti-input-flex-row">
<div class="ti-input-field">
<label>
<?php echo TrustindexWoocommercePlugin::___('Content'); ?>*
<a href="#" id="ti-reset-field-content" data-value="<?php echo htmlspecialchars(TrustindexWoocommercePlugin::getDefaultCampaignText(), ENT_QUOTES); ?>" class="ti-pull-right"><?php echo TrustindexWoocommercePlugin::___('Reset content'); ?></a>
</label>
<?php if(function_exists('wp_editor')): ?>
<?php wp_editor($campaign_text, 'campaign-text', [
'media_buttons' => false,
'tinymce' => [
'toolbar1' => 'undo,redo,copy,paste,formatselect,fontsizeselect,forecolor,backcolor,bold,italic,underline,strikethrough,alignleft,aligncenter,alignright,bullist,numlist,link,unlink,hr,removeformat',
'toolbar2' => '',
'toolbar3' => '',
'toolbar4' => ''
]
]); ?>
<?php else: ?>
<textarea class="form-control" id="campaign-text" name="campaign-text" rows="6"><?php echo $campaign_text; ?></textarea>
<?php endif; ?>
</div>
</div>
<div class="ti-footer">
<a href="#" class="btn-text btn-primary btn-test-email"><?php echo TrustindexWoocommercePlugin::___("Send test e-mail") ;?></a>
<a href="#" class="btn-text btn-save ti-pull-right" data-loading-text="<?php echo TrustindexWoocommercePlugin::___("Loading"); ?>"><?php echo TrustindexWoocommercePlugin::___("Save"); ?></a>
<div class="clear"></div>
<p id="ti-test-email-sent" data-html="<?php echo TrustindexWoocommercePlugin::___("Test email sent to <strong>%s</strong>", [ '%email%' ]); ?>"></p>
</div>
</form>
</div>
<!-- Modal -->
<div class="ti-modal" id="ti-test-email-modal">
<?php wp_nonce_field('ti-woocommerce-test-email'); ?>
<input type="hidden" name="command" value="test-email" />
<div class="ti-modal-dialog">
<div class="ti-modal-content">
<div class="ti-modal-header">
<span class="ti-modal-title"><?php echo TrustindexWoocommercePlugin::___("Send test e-mail") ;?></span>
</div>
<div class="ti-modal-body">
<div class="ti-input-flex-row">
<div class="ti-input-field">
<label><?php echo TrustindexWoocommercePlugin::___("Send to:") ;?></label>
<input type="text" class="form-control" id="ti-email-address" value="<?php echo $current_user->user_email; ?>" placeholder="E-mail" />
</div>
</div>
<div class="ti-notice notice-info" style="margin: 0">
<p><?php echo TrustindexWoocommercePlugin::___("Your e-mail will look like as seen in below. You can modify it in your <a href='%s' target='_blank'>Woocommerce e-mail settings</a>.", [ '?page=wc-settings&tab=email' ]); ?></p>
</div>
<?php echo $wc_mailer->wrap_message("%subject%", '%email_text%'); ?>
</div>
<div class="ti-modal-footer">
<a href="#" class="btn-text btn-modal-close"><?php echo TrustindexWoocommercePlugin::___("Back") ;?></a>
<a href="#" class="btn-text btn-primary btn-send-test-email" data-loading-text="<?php echo TrustindexWoocommercePlugin::___("Loading") ;?>"><?php echo TrustindexWoocommercePlugin::___("Send") ;?></a>
</div>
</div>
</div>
</div>