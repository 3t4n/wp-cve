<select name="<?php echo esc_attr( \IC\Plugin\CartLinkWooCommerce\Order\FilterOrderByCampaign::FILTER_FIELD_NAME ); ?>">
	<option value=""><?php _e( 'Filter by campaign…', 'cart-link-for-woocommerce' ); ?></option>

	<?php foreach ( $campaigns as $campaign_id )  : ?>
		<?php
		printf(
			'<option value="%s"%s>%s</option>',
			$campaign_id,
			selected( $campaign_id, $current, false ),
			get_the_title( $campaign_id )
		);
		?>
	<?php endforeach; ?>
</select>
