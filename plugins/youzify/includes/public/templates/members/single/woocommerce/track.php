<?php
/**
 * WC Track Template
 */
?>
<div class="youzify-wc-main-content youzify-wc-track-content">

	<?php do_action( 'youzify_wc_before_track_content' ); ?>

	<div class="youzify-wc-box-title">
		<?php echo youzify_wc_get_user_address_type_icon( 'tracking' ); ?>
    	<h3><?php _e( 'Track your order', 'youzify' ); ?></h3>
    </div>

	<?php echo do_shortcode( '[woocommerce_order_tracking]' ); ?>

	<?php do_action( 'youzify_wc_after_track_content' ); ?>

</div>