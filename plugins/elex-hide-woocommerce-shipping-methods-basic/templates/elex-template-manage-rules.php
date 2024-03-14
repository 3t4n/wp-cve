<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class='wrap postbox' style='padding:5px 20px;' id="elex_hs_manage_rule_div">
	<h2>
		<?php esc_html_e( 'Manage Rules ', 'elex-hide-shipping-methods' ); ?>
	</h2>
	<table class='elex-hs-content-table'>
		<tr>
			<th class='elex-hs-content-table-manage-rule-name'>
				<?php esc_html_e( 'Name', 'elex-hide-shipping-methods' ); ?>
			</th>
			<th class="elex-hs-content-table-manage-rule-param">
				<?php esc_html_e( 'Parameters involved', 'elex-hide-shipping-methods' ); ?>
			</th>
			<th class="elex-hs-content-table-manage-rule-actions">
				<?php esc_html_e( 'Actions', 'elex-hide-shipping-methods' ); ?>
			</th>
		</tr>
		<?php
		$saved_rules = get_option( 'elex_hs_rules_to_hide_shipping_methods' );
		if ( ! empty( $saved_rules ) ) {
			$saved_rules = array_reverse( $saved_rules );
			foreach ( $saved_rules as $rules ) {
				?>
				<tr>
					<td>
						<?php echo esc_html( $rules['rule_name'] ); ?>
					</td>
					<?php
					$param_involved = '';
					if ( isset( $rules['shipping_class'] ) && ! empty( $rules['shipping_class'] ) ) {
						$param_involved .= 'Shipping Class, ';
					}
					if ( isset( $rules['weight_action'] ) && ! empty( $rules['weight_action'] ) ) {
						$param_involved .= 'Order Weight, ';
					}
					if ( isset( $rules['filter_shipping_methods'] ) && ! empty( $rules['filter_shipping_methods'] ) ) {
						$param_involved .= 'Shipping Methods, ';
					}
					if ( '' == $param_involved ) {
						$param_involved = 'All Products';
					} else {
						$param_involved = substr( $param_involved, 0, -2 );
					}
					?>

					<td>
						<?php echo esc_html( $param_involved ); ?>
					</td>
					<td>
						<span class="elex-hs-edit-rule"  title="Edit" onclick="elex_hs_edit_copy_rule( '<?php echo esc_html( $rules['rule_name'] ); ?>', 'edit' )"  style="display: inline-block;"></span>
						<span class="elex-hs-copy-rule"  title="Copy" onclick="elex_hs_edit_copy_rule( '<?php echo esc_html( $rules['rule_name'] ); ?>', 'copy' )"  style="display: inline-block; margin: 0% 2% 0%;"></span>
						<span class="elex-hs-delete-rule"  title="Delete" onclick="elex_hs_delete_rule( '<?php echo esc_html( $rules['rule_name'] ); ?>' )"  style="display: inline-block;"></span>
					</td>
				</tr>
				<?php
			}
		}
		?>
	</table>
</div>
