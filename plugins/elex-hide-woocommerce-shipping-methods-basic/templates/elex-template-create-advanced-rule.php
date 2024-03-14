<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class='wrap postbox' style='padding:2px 20px;' id="elex_hs_adv_div">
	<h2>
		<?php esc_html_e( 'Create Advanced Rule', 'elex-hide-shipping-methods' ); ?>
	</h2>
	<hr>
	<table class='elex-hs-content-table'>
		<tr>
			<td class="elex-hs-content-table-left">
				<?php esc_html_e( 'Select Rules', 'elex-hide-shipping-methods' ); ?>
			</td>
			<td class='elex-hs-content-table-middle'>
				<span class='woocommerce-help-tip tooltip' data-tooltip='<?php esc_html_e( 'In situations where there is a conflict between multiple hide shipping rules you have created, you can create an advanced rule to manage. Select the rules you want to define new conditions when they are applied together.', 'elex-hide-shipping-methods' ); ?>'></span>
			</td>
			<td class="elex-hs-content-table-right">
				<span><select disabled data-placeholder='<?php esc_html_e( 'Select rules', 'elex-hide-shipping-methods' ); ?>' multiple class="elex-hs-chosen" id="elex_hs_filter_adv_rules" style="width: 26%;">
						
					</select></span>
			</td>
		</tr>
	</table>
	<button style="float: right; margin: -3% 0px 0px 0px;" class='button button-primary button-large' id="elex_hs_filter_adv_rule_btn"><?php esc_html_e( 'Save & Continue', 'elex-hide-shipping-methods' ); ?></button>
</div>
