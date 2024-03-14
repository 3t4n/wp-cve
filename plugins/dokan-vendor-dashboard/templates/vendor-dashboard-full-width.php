<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title><?php echo esc_html( get_the_title() ); ?></title>
		<?php
			// Page header.
			wp_head();
		?>
	</head>
	<body>
	<?php
		/**
		* Template Name: Dokan Vendor Dashboard Template
		*/
		while ( have_posts() ) :
			the_post();

			the_content();
		endwhile; // End of the loop.

		// The page footer.
		if ( dokan_vendor_dashboard()->assets->is_old_route() ) {
			wp_footer();
		}

		/**
		 * Fires after dokan vendor dashboard finished loading.
		 *
		 * @hooked dokan_vendor_dashboard_after_footer
		 *
		 * @since 1.0.0
		 */
		do_action( 'dokan_vendor_dashboard_after_footer' );
	?>
	</body>
</html>
