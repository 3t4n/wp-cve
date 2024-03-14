<?php

/**
*
* Admin: Reports page template.
* @version 3.1.9
*
**/

defined( 'ABSPATH' ) || exit;

?>

<div class="wrap">

	<div id="wceb-settings-container">

		<?php $wceb_tabs = apply_filters( 'easy_booking_reports_tabs', array(
			'bookings' => __('Bookings', 'woocommerce-easy-booking-system'),
			'calendar' => __( 'Calendar ', 'woocommerce-easy-booking-system' )
		));

		$current_tab = empty( $_GET['tab'] ) ? 'bookings' : sanitize_title( $_GET['tab'] ); ?>

		<?php foreach ( $wceb_tabs as $tab => $label ) { ?>
			<h2 class="screen-reader-text"><?php esc_html_e( $label ); ?></h2>
		<?php } ?>

		<div class="nav-tab-wrapper woo-nav-tab-wrapper">
			<?php foreach ( $wceb_tabs as $tab => $label ) { ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=easy-booking-reports&tab=' . esc_attr( $tab ) ) ); ?>" class="nav-tab <?php echo ( $current_tab == $tab ? 'nav-tab-active' : '' ) ?>"><?php esc_html_e( $label ); ?></a>
			<?php } ?>
		</div>

		<?php do_action( 'easy_booking_reports_' . $current_tab . '_tab' ); ?>

	</div>
	
</div>