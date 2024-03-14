<?php
/*
* Plugin Name: Find My Custom Post Types
* Plugin URI: https://wordpress.org/plugins/find-my-custom-post-types
* Description: Find all the custom post types running on your WordPress site. 
* Version: 1.0
* Author: MIND Development and Design
* Author URI: www.minddnd.com
* License:GPLv2
* Text Domain: find-my-custom-post-types
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function minddnd_fmcpt_submenu_page() {
  add_submenu_page(
    'tools.php',
    'Find My Custom Post Types',
    'Find My CPTs',
    'manage_options',
    'minddnd-fmcpt-submenu-page',
    'minddnd_fmcpt_submenu_page_builder' 
  );
}

add_action( 'admin_menu', 'minddnd_fmcpt_submenu_page' );

function minddnd_fmcpt_submenu_page_builder() {
	
	if ( !current_user_can( 'manage_options' ) ) {
		return;
	} ?>

	<div class="wrap">
	  <h2><?php esc_html_e( get_admin_page_title() ) ?></h2>

	  <p><?php printf(
    /* translators: %s: Name of active theme */
    __( 'Listed below are all of the Custom Post Types running on your site. This includes Custom Post Types created in your current theme: <em>%s</em>, or through any activated plugins.', 'find-my-custom-post-types' ),
    wp_get_theme()
		); ?></p>

	  <?php $args = array(
     '_builtin' => false
		);?>
		<?php $show_post_types = get_post_types( $args, 'objects' ); ?>

		<?php if ( !empty( $show_post_types ) ) :?>

		<ol>
			<?php foreach ( $show_post_types as $post_type ): ?>
				<li>
					<?php echo esc_html( $post_type->name ); ?> 
					<?php if ( $post_type->publicly_queryable == 1 ) : ?>
					<?php  printf(__( ' - publicly queryable.', 'find-my-custom-post-types' )); ?>
					<?php endif;?>
				</li>
			<?php endforeach; ?>

		</ol>

		<p><?php printf(__( '<a href="https://developer.wordpress.org/reference/functions/register_post_type/#publicly_queryable" target="_blank">Publicly queryable</a> means queries can be performed on the front end as part of parse_request().', 'find-my-custom-post-types' )); ?></p> 	

		<?php else: ?>

			<p><?php printf(__( 'No registered custom post types found.', 'find-my-custom-post-types' )); ?></p>

		<?php endif; ?>	

	</div>	

<?php 
}