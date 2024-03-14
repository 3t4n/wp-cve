<?php
/**
 * PeachPay analytics settings component.
 *
 * @package PeachPay
 */

defined( 'PEACHPAY_ABSPATH' ) || exit;
?>
<div class='pp-analytics-payment-methods-container'>
	<div class='pp-analytics-payment-methods-wide-graph' id='pp-analytics-settings'>
		<h1><?php esc_html_e( 'PeachPay Analytics are turned off right now.', 'peachpay-for-woocommerce' ); ?></h1>
		<p><?php esc_html_e( 'We will hang on to previously tracked analytics, but no new analytics will be tracked.', 'peachpay-for-woocommerce' ); ?></p>

		<form method='POST' action='options.php' style='display:none'>
			<input type='hidden' name='peachpay_analytics_settings_admin_settings_settings[analytics_enabled]' value='yes'/>
			<input type='hidden' name='option_page' value='peachpay_peachpay_analytics_settings_admin_settings_settings'/>
			<input type='hidden' name='action' value='update'/>

			<button class='activate-button'><?php esc_html_e( 'Turn on analytics', 'peachpay-for-woocommerce' ); ?></button>
		</form>
		<a href='?page=peachpay&tab=settings&section=analytics' class='activate-button'><?php esc_html_e( 'Turn on analytics', 'peachpay-for-woocommerce' ); ?></a>
	</div>
</div>
<div class='pp-analytics-payment-methods-container'></div>
