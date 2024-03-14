<div id="<?php printf( 'wooccm_%s_settings-actions', esc_attr( $current_section ) ); ?>" class="tablenav top" style="margin-bottom:15px">
	<div class="alignleft actions bulkactions">
		<a href="javascript:;" id="<?php printf( 'wooccm_%s_settings_add', esc_attr( $current_section ) ); ?>" class="button button-primary"><?php esc_html_e( '+ Add New Field', 'woocommerce-checkout-manager' ); ?></a>
		<!--<a href="javascript:;" id="<?php printf( 'wooccm_%s_settings_import', esc_attr( $current_section ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Import', 'woocommerce-checkout-manager' ); ?></a>-->
		<a href="javascript:;" id="<?php printf( 'wooccm_%s_settings_reset', esc_attr( $current_section ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Reset', 'woocommerce-checkout-manager' ); ?></a>
	</div>
	<?php if ( 'additional' == esc_attr( $current_section ) ) : ?>
		<div class="alignright actions">
			<?php woocommerce_admin_fields( $settings ); ?>
		</div>
	<?php endif; ?>
</div>
