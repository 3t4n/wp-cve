<input type="hidden" name="_elex_ajax_nonce" value="<?php esc_html_e( wp_create_nonce( '_elex_usps_shipping_ajax_nonce' ) ); ?>">
<tr valign="top" id="service_options" class="rates_tab_field" >
	<td class="forminp" colspan="2" style="padding-left:0px">
	<strong><?php esc_html_e( 'Services', 'wf-usps-woocommerce-shipping' ); ?></strong><br/>
		<table class="usps_services widefat" style="width:100%">
			<thead>
				<th class="sort">&nbsp;</th>
				<th><?php esc_html_e( 'Service(s)', 'wf-usps-woocommerce-shipping' ); ?></th>
				<th><?php esc_html_e( 'Price Adjustment ($) ', 'wf-usps-woocommerce-shipping' ); ?> <span class="wf-super"><?php esc_html_e( '[Premium]', 'wf-usps-woocommerce-shipping' ); ?> </span></th>
				<th><?php esc_html_e( 'Price Adjustment (%) ', 'wf-usps-woocommerce-shipping' ); ?>  <span class="wf-super"><?php esc_html_e( '[Premium]', 'wf-usps-woocommerce-shipping' ); ?> </span></th>
				
			</thead>
			<tbody>
				<?php
					$sort                   = 0;
					$this->ordered_services = array();
				if ( empty( $this->custom_services ) ) {
					$this->custom_services = array();
				}
				foreach ( $this->services as $code => $values ) {

					if ( isset( $this->custom_services[ $code ]['order'] ) ) {
						$sort = $this->custom_services[ $code ]['order'];
					}

					while ( isset( $this->ordered_services[ $sort ] ) ) {
						$sort++;
					}

					$this->ordered_services[ $sort ] = array( $code, $values );

					$sort++;
				}

					ksort( $this->ordered_services );

				foreach ( $this->ordered_services as $value ) {
					$code   = $value[0];
					$values = $value[1];
					if ( ! isset( $this->custom_services[ $code ] ) ) {
						$this->custom_services[ $code ] = array();
					}
					?>
						<tr>
							<td class="sort">
								<input type="hidden" class="order" name="usps_service[<?php esc_attr_e( $code ); ?>][order]" value="<?php esc_attr_e( isset( $this->custom_services['services'][ $code ]['order'] ) ? $this->custom_services[ $code ]['order'] : '' ); ?>" />
							</td>
							<td>
								<ul class="sub_services" style="font-size: 0.92em; color: #555">
								<?php foreach ( $values['services'] as $key => $name ) : ?>
									<li style="line-height: 23px;">
										<label>
											<input type="checkbox" name="usps_service[<?php esc_attr_e( $code ); ?>][<?php esc_attr_e( $key ); ?>][enabled]" <?php checked( ( ! isset( $this->custom_services[ $code ][ $key ]['enabled'] ) || ! empty( $this->custom_services[ $code ][ $key ]['enabled'] ) ), true ); ?> />
											<?php esc_attr_e( $name ); ?>
										</label>
									</li>

									<?php endforeach; ?>
								</ul>
							</td>
							<td>
								<ul class="sub_services" style="font-size: 0.92em; color: #555">
								<?php foreach ( $values['services'] as $key => $name ) : ?>
									<li>
										<?php esc_attr_e( get_woocommerce_currency_symbol() ); ?><input type="text" placeholder="N/A" size="4" disabled />
									</li>
									<?php endforeach; ?>
								</ul>
							</td>
							<td>
								<ul class="sub_services" style="font-size: 0.92em; color: #555">
								<?php foreach ( $values['services'] as $key => $name ) : ?>
									<li>
										<input type="text" placeholder="N/A" size="4" disabled />%
									</li>
									<?php endforeach; ?>
								</ul>
							</td>
						</tr>
						<?php
				}
				?>
			</tbody>
		</table>
	</td>
</tr>
<style type="text/css">
	.usps_services{
		width: 51.5%;
	}
	.usps_services td, .usps_services th {
		vertical-align: middle;
		padding: 4px 7px;
	}
	.usps_services th.sort {
		width: 16px;
	}
	.usps_services td.sort {
		cursor: move;
		width: 16px;
		padding: 0;
		cursor: move;
		background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAYAAADED76LAAAAHUlEQVQYV2O8f//+fwY8gJGgAny6QXKETRgEVgAAXxAVsa5Xr3QAAAAASUVORK5CYII=) no-repeat center;					}
</style>
<script type="text/javascript">

	jQuery(window).load(function(){

		jQuery('#woocommerce_usps_enable_standard_services').change(function(){
			if ( jQuery(this).is(':checked') ) {
				jQuery('#woocommerce_usps_mediamail_restriction').closest('tr').show();
				jQuery('#service_options, #packing_options').show();
				jQuery('#woocommerce_usps_packing_method, #woocommerce_usps_offer_rates').closest('tr').show();
				jQuery('#woocommerce_usps_packing_method').change();
			} else {
				jQuery('#woocommerce_usps_mediamail_restriction').closest('tr').hide();
				jQuery('#service_options, #packing_options').hide();
				jQuery('#woocommerce_usps_packing_method, #woocommerce_usps_offer_rates').closest('tr').hide();
			}
		}).change();

		jQuery('#woocommerce_usps_packing_method').change(function(){

			if ( jQuery('#woocommerce_usps_enable_standard_services').is(':checked') ) {

				if ( jQuery(this).val() == 'box_packing' ) {
					jQuery('#packing_options').show();
					jQuery('#woocommerce_usps_unpacked_item_handling').closest('tr').show();
				} else {
					jQuery('#packing_options').hide();
					jQuery('#woocommerce_usps_unpacked_item_handling').closest('tr').hide();
				}

				if ( jQuery(this).val() == 'weight' )
					jQuery('#woocommerce_usps_max_weight').closest('tr').show();
				else
					jQuery('#woocommerce_usps_max_weight').closest('tr').hide();

			}

		}).change();

		jQuery('#woocommerce_usps_enable_flat_rate_boxes').change(function(){

			if ( jQuery(this).val() == 'yes' ) {
				jQuery('#woocommerce_usps_flat_rate_express_title').closest('tr').show();
				jQuery('#woocommerce_usps_flat_rate_priority_title').closest('tr').show();
				jQuery('#woocommerce_usps_flat_rate_fee').closest('tr').show();
			} else if ( jQuery(this).val() == 'no' ) {
				jQuery('#woocommerce_usps_flat_rate_express_title').closest('tr').hide();
				jQuery('#woocommerce_usps_flat_rate_priority_title').closest('tr').hide();
				jQuery('#woocommerce_usps_flat_rate_fee').closest('tr').hide();
			} else if ( jQuery(this).val() == 'priority' ) {
				jQuery('#woocommerce_usps_flat_rate_express_title').closest('tr').hide();
				jQuery('#woocommerce_usps_flat_rate_priority_title').closest('tr').show();
				jQuery('#woocommerce_usps_flat_rate_fee').closest('tr').show();
			} else if ( jQuery(this).val() == 'express' ) {
				jQuery('#woocommerce_usps_flat_rate_express_title').closest('tr').show();
				jQuery('#woocommerce_usps_flat_rate_priority_title').closest('tr').hide();
				jQuery('#woocommerce_usps_flat_rate_fee').closest('tr').show();
			}

		}).change();

		// Ordering
		jQuery('.usps_services tbody').sortable({
			items:'tr',
			cursor:'move',
			axis:'y',
			handle: '.sort',
			scrollSensitivity:40,
			forcePlaceholderSize: true,
			helper: 'clone',
			opacity: 0.65,
			placeholder: 'wc-metabox-sortable-placeholder',
			start:function(event,ui){
				ui.item.css('baclbsround-color','#f6f6f6');
			},
			stop:function(event,ui){
				ui.item.removeAttr('style');
				usps_services_row_indexes();
			}
		});

		function usps_services_row_indexes() {
			jQuery('.usps_services tbody tr').each(function(index, el){
				jQuery('input.order', el).val( parseInt( jQuery(el).index('.usps_services tr') ) );
			});
		};

	});

</script>
