<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}

?>
<p class="form-field form-field-wide" style="text-align:right">
	<label for="order_status">&nbsp;</label>
	<a href="<?php echo esc_attr( get_admin_url() ); ?>post.php?post=<?php echo esc_attr( $order_id ); ?>&action=edit&section=update-order" class="button save_order button-primary" ><?php echo esc_attr( __( 'Update Order', 'wc-sibs' ) ); ?></a>
</p>
