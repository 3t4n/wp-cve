<?php

/**
*
* Show a notice when a database update is available.
* @version 3.0.0
*
**/

defined( 'ABSPATH' ) || exit;

?>

<div class="notice easy-booking-notice">
	
	<p>

		<?php esc_html_e( 'A database update is required for Easy Booking. It won\'t take long.', 'woocommerce-easy-booking-system' ); ?>

	</p>
	<p>

		<button type="button" class="button easy-booking-button wceb-db-update">
			<?php esc_html_e( 'Update database', 'woocommerce-easy-booking-system' ); ?>
			<span class="wceb-response"></span>
		</button>

	</p>

</div>