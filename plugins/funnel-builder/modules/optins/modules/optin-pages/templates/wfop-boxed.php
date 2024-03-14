<?php
/**
 * Template Name: No Header Footer
 *
 * @package AeroCheckout
 */
?>

	<!DOCTYPE html>
	<html <?php language_attributes(); ?> class="no-js wfop_html_boxed">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>

	<?php
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	}

	do_action( 'woofunnels_container' );
	$attrs_string = WFFN_Common::get_wffn_container_attrs();
	?>
	<div class="woofunnels-container wfop-boxed wffn-page-template" <?php echo esc_attr( $attrs_string ); ?>>
		<?php do_action( 'woofunnels_container_top' ); ?>
		<div class="woofunnels-primary">
			<?php
			while ( have_posts() ) :

				the_post();
				the_content();

			endwhile;
			?>
		</div>
		<?php
		do_action( 'woofunnels_container_bottom' );
		?>
	</div>

	<?php do_action( 'woofunnels_wp_footer' ); ?>

	<?php wp_footer(); ?>
	</body>

	</html>

<?php
