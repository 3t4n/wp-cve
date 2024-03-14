<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="d_table">		
	<form method="post" id="trackship_mapping_form" action="" enctype="multipart/form-data">
		<div class="heading_panel section_mapping_heading">
			<strong><?php esc_html_e( 'Map Shipping Providers', 'trackship-for-woocommerce' ); ?></strong>
			<div class="heading_panel_save">
				<span class="dashicons dashicons-arrow-right-alt2"></span>
				<div class="spinner"></div>
				<button name="save" class="button-primary btn_green2 btn_large woocommerce-save-button button-trackship" type="submit"><?php esc_html_e( 'Save & close', 'trackship-for-woocommerce' ); ?></button>
				<?php wp_nonce_field( 'trackship_mapping_form', 'trackship_mapping_form_nonce' ); ?>
				<input type="hidden" name="action" value="trackship_mapping_form_update">
			</div>
		</div>
		<div class="panel_content section_mapping_content">
			<div class="outer_form_table">
				<?php if ( !is_plugin_active( 'ast-pro/ast-pro.php' ) ) { ?>
					<table class="form-table fixed map-provider-table">
						<thead>
							<p class="map_providers_note"><?php esc_html_e( 'If you get different names from your shipping service, you can map the Shipping Providers names to the ones on TrackShip.', 'trackship-for-woocommerce' ); ?></p>
							<tr class="ptw_provider_border">
								<th><?php esc_html_e( 'Shipping provider', 'trackship-for-woocommerce' ); ?></th>
								<th><?php esc_html_e( 'TrackShip Provider', 'trackship-for-woocommerce' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$trackship_map_provider = get_option( 'trackship_map_provider' );
							$ts_shippment_providers = $this->get_trackship_provider();
							if ( !empty( $trackship_map_provider ) ) :
								foreach ( $trackship_map_provider as $key => $val ) : 
									?>
									<tr>
										<td>
											<input type="text" class="map_shipping_provider_text" name="detected_provider[]" value="<?php esc_html_e( $key ); ?>">
										</td>
										<td>
											<select name="ts_provider[]" class="select2">
												<option value=""><?php esc_html_e( 'Select' ); ?></option>
												<?php foreach ( $ts_shippment_providers as $ts_provider ) { ?>
													<option value="<?php echo esc_html( $ts_provider->ts_slug ); ?>" <?php esc_html_e( $ts_provider->ts_slug == $val ? 'selected' : '' ); ?> ><?php echo esc_html( $ts_provider->provider_name ); ?></option>	
												<?php } ?>
												</select>
											<span class="dashicons dashicons-trash remove_custom_maping_row"></span>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>	
					<button class="button-primary add_custom_mapping_h3 button-trackship"><?php esc_html_e('Add mapping', 'trackship-for-woocommerce' ); ?><span class="dashicons dashicons-plus ptw-dashicons"></span></button><span class="dashicons dashicons-update update_shipping_provider"></span><div class="add-custom-mapping spinner"></div>
				<?php } else { ?>
					<span class="plugin_setting_note">
						<strong><?php esc_html_e( 'Please note: ', 'trackship-for-woocommerce' ); ?></strong>
						<?php /* translators: %s: search for a count */ ?>
						<?php printf( esc_html__( 'Since AST PRO is installed, the shipping provider name mapping is done on the shipping provider settings (WooCommerce > Shipment Tracking > %1$sShipping Providers%2$s)', 'trackship-for-woocommerce' ), '<a href="' . esc_url( admin_url( 'admin.php?page=woocommerce-advanced-shipment-tracking&tab=shipping-providers' ) ) . '">', '</a>' ); ?>
					</span>
				<?php } ?>
			</div>
		</div>
	</form>
</div>
