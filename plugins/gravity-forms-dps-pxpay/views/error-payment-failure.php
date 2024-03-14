<?php
namespace webaware\gf_dpspxpay;

// error message displayed on failure of payment request
// replaces confirmation text

if (!defined('ABSPATH')) {
	exit;
}
?>
<?= $anchor; ?>
<div id="gform_confirmation_wrapper_<?= esc_attr($form['id']); ?>" class="gform_confirmation_wrapper <?= esc_attr($cssClass); ?>">
	<div id="gform_confirmation_message_<?= esc_attr($form['id']); ?>" class="gform_confirmation_message_<?= esc_attr($form['id']); ?> gform_confirmation_message">
		<p class="gfdpspxpay-failure-message"><strong><?php esc_html_e('Windcave Free payment request error', 'gravity-forms-dps-pxpay'); ?></strong></p>
		<?= $error_msg; ?>
	</div>
</div>
