<?php
/**
 * KB Template for KB Main Page.
 *
 * @author 		Echo Plugins
 */

global $epkb_password_checked;

$kb_id = EPKB_Utilities::get_eckb_kb_id();
$kb_config = epkb_get_instance()->kb_config_obj->get_kb_config_or_default( $kb_id );

/**
 * Display MAIN PAGE content
 */
if ( empty( $hide_header_footer ) ) {
	get_header();
}

// initialize Main Page title
if ( $kb_config[ 'template_main_page_display_title' ] === 'off' ) {
	$kb_main_pg_title = '';
} else {
	$kb_main_pg_title = '<h1 class="eckb_main_title">' . get_the_title() . '</h1>';
}

$template_style1 = EPKB_Utilities::get_inline_style(
           'padding-top::       template_main_page_padding_top,
	        padding-bottom::    template_main_page_padding_bottom,
	        padding-left::      template_main_page_padding_left,
	        padding-right::     template_main_page_padding_right,
	        margin-top::        template_main_page_margin_top,
	        margin-bottom::     template_main_page_margin_bottom,
	        margin-left::       template_main_page_margin_left,
	        margin-right::      template_main_page_margin_right,', $kb_config );       ?>

	<div class="eckb-kb-template" <?php echo $template_style1; ?>>		  <?php

		echo $kb_main_pg_title;

		while ( have_posts() ) {

		    the_post();

			if ( post_password_required() ) {
				echo get_the_password_form();
				echo '</div>';
				get_footer();
				return;
			}
			$epkb_password_checked = true;

			// get post content
			$post = empty( $GLOBALS['post'] ) ? '' : $GLOBALS['post'];
			if ( empty( $post ) || ! $post instanceof WP_Post ) {
				continue;
			}
			$post_content = $post->post_content;

			// output KB Main Page
			$striped_content = empty($post_content) ? '' : preg_replace('/\s+|&nbsp;/', '', $post_content);
			$plugin_first_version = get_option( 'epkb_version_first' );
			$plugin_first_version = empty($plugin_first_version) ? '6.7.0' : $plugin_first_version;

			// output the full content of the page using 'the_content' filter if any of the condition is true:
			// - page content is not empty and contains more than just KB Main Page shortcode
			// - Elementor plugin is enabled
			// - the first version of KB is higher than 6.6.0
			if ( ( ( ! empty($striped_content) && strlen($striped_content) > 26 ) ) || EPKB_Site_Builders::is_elementor_enabled() || version_compare( $plugin_first_version, '6.6.0', '>' ) ) {
				$post_content = apply_filters( 'the_content', $post_content );
				echo str_replace( ']]>', ']]&gt;', $post_content ); // the replacement is required to run Elementor editor correctly

			// directly generate the Main Page - keep same output as before KB version 6.6.0 to avoid affect of 'the_content' filter for older installations
			} else {
				echo EPKB_Layouts_Setup::output_main_page( $kb_config );
			}

		}  ?>


	</div>   <?php

if ( empty( $hide_header_footer ) ) {
	get_footer();
}