<?php
/**
 * Thank you page.
 *
 * @package WcGetnet
 */

?>
<div class="woocommerce">
	<?php \WcGetnet::render( 'partials/checkout/' . esc_attr( $args['method'] ) . '-thank-you', compact( 'order_id', 'args' ) ); ?>
</div>
