<?php
/**
 * Admin Tabs
 *
 * Tabs for the admin settings page.
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
} ?>

<p>
	<nav class="nav-tab-wrapper">

        <?php foreach ( $tabs as $id => $tab ) { ?>

			<?php printf( '
				<a href="%s%s" class="nav-tab %s %s">%s</a>',
		        esc_url( $href ),
		        esc_attr( $id ),
		        esc_attr( $id ),
		        esc_attr( $tab['status'] ),
		        esc_html( $tab['label'] )
	        ); ?>

     <?php } ?>

	</nav>
</p>
