<?php

/**
 * payment-booklets - Checkout form.
 *
 * @author  Iugu
 * @package Iugu_WooCommerce/Templates
 * @version 1.1.0
 */
if (!defined('ABSPATH')) {
	exit;
} // end if;
?>

<fieldset id="iugu-payment-booklets-fields"> 

	<?php if (WC()->checkout()->is_registration_required()) { ?>
		<input type="hidden" value="1" id="iugu-is_registration_required">
	<?php } else { ?>
		<input type="hidden" value="0" id="iugu-is_registration_required">
	<?php } ?>

	<?php if ('yes' === get_option('woocommerce_enable_signup_and_login_from_checkout') || $registration_required) { ?>
		<input type="hidden" value="1" id="iugu-woocommerce_enable_signup_and_login_from_checkout">
	<?php } else { ?>
		<input type="hidden" value="0" id="iugu-woocommerce_enable_signup_and_login_from_checkout">
	<?php } ?>


	<?php if (is_user_logged_in()) { ?>
		<input type="hidden" value="1" id="iugu-is_user_logged_in">
	<?php } else { ?>
		<input type="hidden" value="0" id="iugu-is_user_logged_in">
	<?php } ?>

	<?php if (isset($installments) && $installments > 0) { ?>
		<p class=" form-row form-row-wide">
			<label for="iugu-payment-booklets-installments"><?php _e('Installments', IUGU); ?> <span class="required">*</span></label>
			<select id="iugu-payment-booklets-installments" onchange="iugu_payment_booklets_installments_onchange(this);" name="iugu_payment_booklets_installments" style="font-size: 1.5em; padding: 4px; width: 100%;">
				<?php if (!isset($fixed_installments) || $fixed_installments == 0) { ?>
					<option value=""><?php echo __('Select', IUGU); ?></option>
				<?php } ?>
				<?php for ($i = 1; $i <= $installments; $i++) :
					$total_to_pay = $order_total;
					$installment_total = $total_to_pay / $i;
					$interest_text = __('free interest', IUGU);
					/**
					 * Set the interest rate.
					 */
					if ($pass_interest == 'yes') {
						$total_rate = (($total_to_pay / 100) * $rates[$i]);
						$total_to_pay = $total_to_pay + $total_rate;
						$installment_total = ($total_to_pay / $i);
						if ($rates[$i] > 0) {
							$interest_text = __('with interest', IUGU);
						}
					} // end if;
					/**
					 * Stop when the installment total is less than the smallest installment configure.
					 */
					if ($i > 1 && $installment_total < $smallest_installment) {
						break;
					} // end if;
				?>
					<?php if (isset($fixed_installments) && $fixed_installments == $i) { ?>
						<option value="<?php echo $i; ?>"><?php echo esc_attr(sprintf(__('%dx of %s %s (Total: %s)', IUGU), $i, sanitize_text_field(wc_price($installment_total)), $interest_text, sanitize_text_field(wc_price($total_to_pay)))); ?></option>
					<?php } ?>
					<?php if (!isset($fixed_installments) || $fixed_installments == 0) { ?>
						<option value="<?php echo $i; ?>"><?php echo esc_attr(sprintf(__('%dx of %s %s (Total: %s)', IUGU), $i, sanitize_text_field(wc_price($installment_total)), $interest_text, sanitize_text_field(wc_price($total_to_pay)))); ?></option>
					<?php } ?>
				<?php endfor; ?>
			</select>
		</p>
	<?php } ?>

	<div class="clear"></div>
	<div style="margin-bottom: 1em;"></div>
</fieldset>

<script>
	function iugu_payment_booklets_installments_onchange(sel) {
		if ($latValue !== sel.value) {
			$latValue = sel.value;
			jQuery('body').trigger('update_checkout');
		}
	}
</script>