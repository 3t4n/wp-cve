<?php

use InspireLabs\WoocommerceInpost\EasyPack;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$field = $this->get_field_key( $key );

?>

<tr valign="top">
	<th scope="row" class="titledesc">
		<label for="woocommerce_easypack_parcel_machines_rates"><?php echo esc_html( $data['title'] ); ?></label>
	</th>
	<td class="forminp">
		<table id="<?php echo esc_attr( $field ); ?>" class="easypack_rates wc_input_table sortable widefat">
			<thead>
				<tr>
					<th class="sort">&nbsp;</th>
					<th><?php _e( 'Min', 'woocommerce-inpost' ); ?></th>
					<th><?php _e( 'Max', 'woocommerce-inpost' ); ?></th>
					<th><?php _e( 'Cost', 'woocommerce-inpost' ); ?></th>
					<th><?php _e( 'Action', 'woocommerce-inpost' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php $count = 0; ?>
				<?php if( is_array( $rates ) ): ?>
                    <?php foreach ( $rates as $key => $rate) : $count++; ?>
                        <?php if( is_array( $rate ) ): ?>
                            <tr>
                                <td class="sort"></td>
                                <td>
                                    <input class="input-text regular-input" type="number" style="" value="<?php echo  esc_attr ($rate['min'] ); ?>" placeholder="0.00" step="any" min="0" name=rates[<?php echo esc_attr( $count ); ?>][min]>
                                </td>
                                <td>
                                    <input class="input-text regular-input" type="number" style="" value="<?php echo esc_attr( $rate['max'] ); ?>" placeholder="0.00" step="any" min="0" name=rates[<?php echo esc_attr( $count ); ?>][max]>
                                </td>
                                <td>
                                    <input class="input-text regular-input" type="number" style="" value="<?php echo esc_attr( $rate['cost'] ); ?>" placeholder="0.00" step="any" min="0" name=rates[<?php echo esc_attr( $count ); ?>][cost]>
                                </td>
                                <td>
                                    <a id="delete_rate_<?php echo esc_attr( $count ); ?>" href="#" class="button delete_rate" data-id="<?php echo esc_attr( $count ); ?>"><?php _e( 'Delete row', 'woocommerce-inpost' ); ?></a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
				<?php endif; ?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="5">
						<a id="insert_rate" href="#" class="button plus insert"><?php _e( 'Insert row', 'woocommerce-inpost' ); ?></a>
					</th>
				</tr>
			</tfoot>
		</table>
		<script type="text/javascript">
			function append_row( id ) {
				var code = '<tr class="new">\
								<td class="sort"></td>\
								<td>\
									<input id="rates_'+id+'_min" class="input-text regular-input" type="number" style="" value="" placeholder="0.00" step="any" min="0" name=rates[' + id + '][min]>\
								</td>\
								<td>\
									<input class="input-text regular-input" type="number" style="" value="" placeholder="0.00" step="any" min="0" name=rates[' + id + '][max]>\
								</td>\
								<td>\
									<input class="input-text regular-input" type="number" style="" value="" placeholder="0.00" step="any" min="0" name=rates[' + id + '][cost]>\
								</td>\
								<td>\
									<a id="delete_rate_'+id+'" href="#" class="button delete_rate" data-id="'+id+'"><?php _e( 'Delete row', 'woocommerce-inpost' ); ?></a>\
								</td>\
							</tr>';
				var $tbody = jQuery('.easypack_rates').find('tbody');
				$tbody.append( code );
			}
			jQuery(document).ready(function() {
				var $tbody = jQuery('.easypack_rates').find('tbody');
				var append_id = $tbody.find('tr').size();
				var size = $tbody.find('tr').size();
				if ( size == 0 ) {
					append_id = append_id+1;
					append_row(append_id);
				}
				jQuery('#insert_rate').click(function() {
					append_id = append_id+1;
					append_row(append_id);
					jQuery('#rates_'+append_id+'_min').focus();
					return false;
				});
				jQuery(document).on('click', '.delete_rate',  function() {
					if (confirm('<?php _e( 'Are you sure?' , 'woocommerce-inpost' ); ?>')) {
						jQuery(this).closest('tr').remove();
					}
					return false;
				});

                // show-hide gabaryt fields
                let selector;
                selector = jQuery("[id$='_based_on']");
                if( selector.length > 0 ) {
                    show_hide_gabaryt_rows( jQuery(selector).val() );

                    jQuery(selector).on('change', function () {
                        show_hide_gabaryt_rows( jQuery(selector).val() );
                    });
                }

                function show_hide_gabaryt_rows(select_position) {
                    if(select_position === 'size') {
                        jQuery("[id$='_rates']").closest('tr').hide();
                        jQuery("[id$='_rates']").hide();
                        jQuery('.easypack_gabaryt_a').closest('tr').show();
                        jQuery('.easypack_gabaryt_b').closest('tr').show();
                        jQuery('.easypack_gabaryt_c').closest('tr').show();
                        jQuery('#easypack_dimensions_warning').parent('p').show();
                    } else {
                        jQuery('#easypack_dimensions_warning').parent('p').hide();
                        jQuery('.easypack_gabaryt_a').closest('tr').hide();
                        jQuery('.easypack_gabaryt_b').closest('tr').hide();
                        jQuery('.easypack_gabaryt_c').closest('tr').hide();
                        jQuery("[id$='_rates']").show();
                        jQuery("[id$='_rates']").closest('tr').show();
                    }
                }

			});
		</script>
	</td>
</tr>



