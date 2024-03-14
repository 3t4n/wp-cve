<?php
	/**
	 * WC Payment Methods Template
	 */
?>
<div class="youzify-wc-main-content youzify-wc-payment-methods-content">

	<?php do_action( 'youzify_wc_before_payment_methods_content' ); ?>

	<?php echo do_shortcode( '[youzify_payment_methods]' ); ?>

	<?php do_action( 'youzify_wc_after_payment_methods_content' ); ?>

</div>