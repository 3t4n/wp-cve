<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="https://gmpg.org/xfn/11">

		<?php wp_head(); ?>
	</head>
	<body <?php body_class(['element-ready-lite','element-ready-header-enable']); ?>>
		<?php
		
			if ( function_exists( 'wp_body_open' ) ) {
				wp_body_open();
			}

			do_action( 'element_ready_header_builder_before' );
			do_action( 'element_ready_header_builder' );
			do_action( 'element_ready_header_builder_after' );
		
		?>
		
			
			
