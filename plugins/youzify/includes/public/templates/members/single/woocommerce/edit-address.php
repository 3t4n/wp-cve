<?php
	/**
	 * WC Edit Address Template
	 */
?>
<div class="youzify-wc-main-content youzify-wc-edit-address-content">

	<?php do_action( 'youzify_wc_before_edit_address_content' ); ?>

	<?php echo do_shortcode( '[youzify_woocommerce_addresses]' ); ?>

	<?php do_action( 'youzify_wc_after_edit_address_content' ); ?>

</div>