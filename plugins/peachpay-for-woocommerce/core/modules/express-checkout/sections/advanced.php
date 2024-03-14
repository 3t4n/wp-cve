<?php
/**
 * PeachPay Advanced Settings.
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

/**
 * Calls the functions that implement the subsections under Advanced Settings.
 */
function peachpay_express_checkout_advanced_render() {
	peachpay_settings_advanced_main();
}

/**
 * Registers the advanced settings options.
 */
function peachpay_settings_advanced_main() {
	add_settings_field(
		'peachpay_custom_checkout_js',
		__( 'Custom JS', 'peachpay-for-woocommerce' ),
		'peachpay_custom_js_section',
		'peachpay',
		'peachpay_express_checkout_render',
		array( 'class' => 'pp-header no-border-bottom' )
	);
}

/**
 * Renders custom checkout JS text area.
 */
function peachpay_field_checkout_js_cb() {
	?>
	<textarea
		id="peachpay_custom_checkout_js"
		name="peachpay_express_checkout_advanced[custom_checkout_js]"
		style="width: 400px; min-height: 200px;"
		placeholder="<script>
// Custom script element here
</script>"><?php echo esc_attr( peachpay_get_settings_option( 'peachpay_express_checkout_advanced', 'custom_checkout_js' ) ); ?></textarea>
	<p class="description"><?php esc_html_e( 'This setting will append any provided elements to the PeachPay checkout window.', 'peachpay-for-woocommerce' ); ?></p>
	<script>
		document.getElementById('peachpay_custom_checkout_js').addEventListener('keydown', function(e) {
		if (e.key == 'Tab') {
			e.preventDefault();
			var start = this.selectionStart;
			var end = this.selectionEnd;
			this.value = this.value.substring(0, start) +
			"\t" + this.value.substring(end);
			this.selectionStart = this.selectionEnd = start + 1;
		}
		});
	</script>
	<?php
}

/**
 * Render the settings field custom js section.
 */
function peachpay_custom_js_section() {
	?>
	<div class="peachpay-setting-section">
		<div>
			<h4><?php esc_html_e( 'Checkout JS', 'peachpay-for-woocommerce' ); ?></h4>
			<?php peachpay_field_checkout_js_cb(); ?>
		</div>
		<div class="pp-save-button-section">
			<?php submit_button( 'Save changes', 'pp-button-primary' ); ?>
		</div>
	</div>
	<?php
}
