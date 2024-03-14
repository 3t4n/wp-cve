<?php
if (!defined('ABSPATH')) {
	exit;
}
?>

<?php if ($surcharge): ?>
<div class="gf_payment_detail">
	<?= esc_html_x('Surcharge:', 'entry details', 'gravity-forms-dps-pxpay') ?>
	<span id="gfdpspxpay_surcharge"><?= esc_html($surcharge); ?></span>
</div>
<?php endif; ?>

<?php if ($authCode): ?>
<div class="gf_payment_detail">
	<?= esc_html_x('Auth Code:', 'entry details', 'gravity-forms-dps-pxpay') ?>
	<span id="gfdpspxpay_authcode"><?= esc_html($authCode); ?></span>
</div>
<?php endif; ?>

