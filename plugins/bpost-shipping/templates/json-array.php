<?php
/**
 * @var string[] $no_free_countries
 * @var string[] $free_shipping
 * @var string $field
 * @var string[] $allowed_countries
 * @var bool $prices_include_tax
 */
$esc_field = esc_attr($field);
?>

<table id="<?php echo $esc_field ?>" class="wc_free_shipping wc_input_table widefat">
	<thead>
	<tr>
		<th>&nbsp;</th>
		<th class="name"><?php _e( 'Country', 'woocommerce' ); ?>&nbsp;</th>
		<th class="id"><?php _e( 'From', 'woocommerce' ); ?>&nbsp;
            <span class="tips" data-tip="<?php esc_attr_e( 'A minimum amount to get the free shipping, for the given country', 'woocommerce' ); ?>">[?]</span>
		</th>

	</tr>
	</thead>
	<tbody id="free_shipping">
	<?php foreach ( $free_shipping as $free_shipping_id => $free_shipping_value ) { ?>
		<tr class="tips" data-tip="<?php echo __( 'Free Shipping ID', 'woocommerce' ) . ': ' . $free_shipping_id; ?>">
			<td width="8%"><input type="hidden" class="<?php echo $esc_field ?>_remove"
			                                   name="<?php echo $esc_field ?>_remove[<?php echo $free_shipping_id ?>]"
			                                   value="0"/>
				<a href="#" class="button minus <?php echo $esc_field ?>_remove"><?php _e( 'Remove row', 'woocommerce' ); ?></a>
			</td>

			<td class="country">
				<select multiple="multiple" class="multiselect chosen_select" name="<?php echo $esc_field ?>_country[<?php echo $free_shipping_id ?>][]" id="<?php echo $esc_field ?>_country[<?php echo $free_shipping_id ?>]">
					<?php foreach ( (array) $allowed_countries as $option_key => $option_value ) : ?>
						<option value="<?php echo esc_attr( $option_key ); ?>"
                            <?php selected( in_array( $option_key, (array)$free_shipping_value['country'], true ) ); ?>
                        >
                            <?php echo esc_attr( $option_value ); ?></option>
					<?php endforeach; ?>
				</select>
			</td>

			<td class="state">
				<input type="number" value="<?php echo esc_attr( $free_shipping_value['from'] ) ?>" placeholder="*"
				       required="required"
				       min="0" max="100000" step="0.01"
				       name="<?php echo $esc_field ?>_from[<?php echo $free_shipping_id ?>]"/>
			</td>

		</tr>
<?php
	}
?>
	</tbody>
	<tfoot>
	<tr>
		<th colspan="3">
			<a href="#" class="button plus insert"><?php _e( 'Insert row', 'woocommerce' ); ?></a>
		</th>
	</tr>
<?php if ( $no_free_countries ) { ?>
	<tr>
		<th colspan="3" class="description">
			<p>
				<span class="description">
<?php
if ( $prices_include_tax ) {
	echo bpost__( 'Amounts ("From" column) include VAT' );
} else {
	echo bpost__( 'Amounts ("From" column) exclude VAT' );
}
?>
				</span>
			</p>
			<p>
				<span class="description">
					<?php echo bpost__("Free shipping is not defined for "), join( ', ', $no_free_countries ) ?>.
				</span>
			</p>
		</th>
<?php } ?>
	</tr>

	</tfoot>
</table>

<script type="text/javascript">
	jQuery(function () {
		function on_row_delete_click() {

			$current = jQuery(this).closest('tr');
			$current.find('input').val('');
			$current.find('input.<?php echo $esc_field ?>_remove').val('1');

			$current.each(function () {
				if (jQuery(this).is('.new'))
					jQuery(this).remove();
				else{
					jQuery(this).find('input').removeAttr('required');
					jQuery(this).hide();

				}
			});

			return false;
		}

		function trigger_row_click_error() {
			jQuery('.wc_free_shipping .<?php echo $esc_field ?>_remove').click(on_row_delete_click);
		}
		trigger_row_click_error();

		jQuery('#<?php echo $esc_field ?> .insert').click(function () {
			var $tbody = jQuery('#<?php echo $esc_field ?>').find('tbody');
			var size = $tbody.find('tr').size();
			var code = '<tr class="new">\
                <td width="8%"><a href="#" class="button minus <?php echo $esc_field ?>_remove"><?php echo bpost__( 'Remove row' ); ?></a></td>\
                <td class="country">\
                <select multiple="multiple" class="multiselect chosen_select" name="<?php echo $esc_field ?>_country[new-' + size + '][]">\
					<?php foreach ( (array) $allowed_countries as $option_key => $option_value ) : ?>\
					<option value="<?php echo esc_attr( $option_key ); ?>"><?php echo esc_attr( $option_value ); ?></option>\
					<?php endforeach; ?>\
				</select>\
				</td>\
                <td class="state"><input required="required" type="number" min="0" max="100000" step="0.01" placeholder="*" name="<?php echo $esc_field ?>_from[new-' + size + ']" /></td>\
                </tr>';

			if ($tbody.find('tr.current').size() > 0) {
				$tbody.find('tr.current').after(code);
			} else {
				$tbody.append(code);
			}

			jQuery( document.body ).trigger( 'wc-enhanced-select-init' );
			trigger_row_click_error();

			return false;
		});
	});
</script>

<style type="text/css">
	table.wc_free_shipping {
		width: 40%;
	}
	input[type=number]::-webkit-outer-spin-button,
	input[type=number]::-webkit-inner-spin-button {
		-webkit-appearance: none;
		margin: 0;
	}

	input[type=number] {
		-moz-appearance:textfield;
	}
	td.country select{
		width: 95%;
	}
	.woocommerce table.form-table table.widefat th.description {
		padding-top: 5px;
		padding-bottom: 5px;
	}
</style>