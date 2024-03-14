<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class='wrap postbox' style='padding:5px 20px;' id="elex_hs_hide_shipping_div">
	<h2>
		<?php esc_html_e( 'Hide Shipping', 'elex-hide-shipping-methods' ); ?>
	</h2>
	<hr>
	<table class='elex-hs-content-table'>
		<tr>
			<td class="elex-hs-content-table-left">
				<?php esc_html_e( 'Shipping Methods', 'elex-hide-shipping-methods' ); ?>
			</td>
			<td class='elex-hs-content-table-middle'>
				<span class='woocommerce-help-tip tooltip' data-tooltip='<?php esc_html_e( 'Choose the shipping methods you want to hide for the filter set in STEP 1.', 'elex-hide-shipping-methods' ); ?>'></span>
			</td>
			<td class="elex-hs-content-table-right">
				<?php
				global $woocommerce;
				$shipping_methods = $woocommerce->shipping()->get_shipping_methods();
				?>
				<span><select data-placeholder='<?php esc_html_e( 'Select Shipping Methods', 'elex-hide-shipping-methods' ); ?>' multiple class="elex-hs-chosen" id="elex_hs_hide_shipping_methods">
						<?php
						if ( count( $shipping_methods ) > 0 ) {
							foreach ( $shipping_methods as $key => $val ) {
								echo "<option value='" . esc_html( $key ) . "'>" . esc_html( $key ) . '</option>';
							}
						}
						?>
					</select></span>
			</td>
		</tr>
		<tr>
			<td class="elex-hs-content-table-left">
				<?php esc_html_e( 'Rule Name', 'elex-hide-shipping-methods' ); ?>
			</td>
			<td class='elex-hs-content-table-middle'>
				<span class='woocommerce-help-tip tooltip' data-tooltip='<?php esc_html_e( 'Enter a custom rule name as you wish. If you leave this field empty, the plugin will auto-generate a random name for the rule.', 'elex-hide-shipping-methods' ); ?>'></span>
			</td>
			<td class="elex-hs-content-table-right">
				<input type="text" id="elex_hs_rule_name">
			</td>
		</tr>
	</table>
	<button id='elex_hs_back_btn' style=" background-color: gray; color: white; width: 10%; " class='button button-large'><span class="update-text"><?php esc_html_e( 'Back', 'elex-hide-shipping-methods' ); ?></span></button>
	<button id='elex_hs_create_rule_btn' style=" float: right; color: white; width: 12%;" class='button button-primary button-large'><span class="update-text"><?php esc_html_e( 'Create Rule', 'elex-hide-shipping-methods' ); ?></span></button>

</div>
<?php
require_once ELEX_HIDE_SHIPPING_METHODS_TEMPLATE_PATH . '/elex-template-manage-rules.php';
