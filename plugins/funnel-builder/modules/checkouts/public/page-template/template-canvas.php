<?php
/**
 * Template Name: No Header Footer
 *
 * @package AeroCheckout
 */

global $post;
$checkout_post = $post;

$temp_post = WFACP_Core()->template_loader->get_checkout_post();
if ( $post->post_type !== WFACP_Common::get_post_type_slug() && ! is_null( $temp_post ) ) {
	$checkout_post = $temp_post;
}
$wfacp_template      = wfacp_template();
$template_type = $wfacp_template->get_template_type();
?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?> class="no-js wfacp_html_canvas">
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <link rel="profile" href="http://gmpg.org/xfn/11">
		<?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?>>

	<?php
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	}

	do_action( 'wfacp_template_body_top' );


	$atts_string = WFACP_Common::get_template_container_atts();

	?>
    <div class="wfacp-template-container" <?php echo trim( $atts_string ); ?>>
		<?php
		do_action( 'wfacp_template_container_top' );
		if ( WFACP_Core()->public->is_checkout_override() && 'embed_forms' !== $template_type ) {
			
			WFACP_Common::the_content( $checkout_post );
		} else {
			while ( have_posts() ) {
				the_post();
				the_content();
			}
		}
		do_action( 'wfacp_template_container_bottom' );
		?>
    </div>
	<?php do_action( 'wfacp_template_wp_footer' ); ?>
	<?php wp_footer(); ?>
    </body>
    </html>
<?php
