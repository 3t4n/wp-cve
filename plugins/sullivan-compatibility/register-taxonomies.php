<?php
/* ====================================================================
|  REGISTER CUSTOM POST TAXONOMIE(S)
|  Add whatever taxonomies are needed here
'---------------------------------------------------------------------- */

// Register sullivan_slideshow_location taxonomy
if ( ! function_exists( 'sullivan_compat_register_taxonomies' ) ) {
	function sullivan_compat_register_taxonomies() {

		$args = array(
			'capabilities' 			=> array(
				'manage_terms' 			=> 'manage_categories', 
				'edit_terms'   			=> 'foobar', // Nonexistent, limiting users to select from the existing taxonomies
				'delete_terms' 			=> 'foobar', // Nonexistent, limiting users to select from the existing taxonomies
				'assign_terms' 			=> 'edit_posts'
			),
			'labels'				=> array(
				'name'					=> _x( 'Slideshow locations', 'Taxonomy General Name', 'sullivan-compatibility' ),
				'singular_name'			=> _x( 'Slideshow location', 'Taxonomy Singular Name', 'sullivan-compatibility' ),
				'menu_name'				=> __( 'Slideshow locations', 'sullivan-compatibility' ),
				'all_items'				=> __( 'All slideshow locations', 'sullivan-compatibility' ),
				'edit_item'				=> __( 'Edit slideshow location', 'sullivan-compatibility' ),
				'update_item'			=> __( 'Update slideshow location', 'sullivan-compatibility' ),
			),
			'hierarchical'     		=> true,
			'public'           		=> false,
			'show_ui'          		=> true,
			'show_admin_column'		=> true,
			'show_in_nav_menus'		=> true,
			'show_tagcloud'    		=> false,
		);

		register_taxonomy( 'sullivan_slideshow_location', array( 'sullivan_slideshow' ), $args );

	}
}
add_action( 'init', 'sullivan_compat_register_taxonomies' );


// Add our slideshow locations to the sullivan_slideshow_location taxonomy
if ( ! function_exists( 'sullivan_compat_add_terms_to_slideshow_location_taxonomy' ) ) {
	function sullivan_compat_add_terms_to_slideshow_location_taxonomy() {

		$taxonomy = 'sullivan_slideshow_location';

		// If the blog term doesn't exist, create it
		$blog_term = get_term_by( 'slug', 'blog', $taxonomy );

		if ( ! $blog_term ) {

			wp_insert_term( __( 'Blog', 'sullivan-compatibility' ), $taxonomy, array(
				'description'	=> __( 'Slides to display on the blog page.', 'sullivan-compatibility' ),
				'slug' 			=> 'blog',
			) );

		// If it does exist, but its name doesn't match the translation, update it
		} elseif ( $blog_term && $blog_term->name != __( 'Blog', 'sullivan-compatibility' ) ) {

			wp_update_term( $blog_term->term_id, $taxonomy, array(
				'name'	=> __( 'Blog', 'sullivan-compatibility' )
			) );

		}

		// If WooCommerce is active, make updates to the shop term
		if ( class_exists( 'woocommerce' ) ) {

			// If the blog term doesn't exist, create it
			$shop_term = get_term_by( 'slug', 'shop', $taxonomy );

			if ( ! $shop_term ) {

				wp_insert_term( __( 'Shop', 'sullivan-compatibility' ), $taxonomy, array(
					'description'	=> __( 'Slides to display on the shop start page.', 'sullivan-compatibility' ),
					'slug' 			=> 'shop',
				) );

			// If it does exist, but its name doesn't match the translation, update it
			} elseif ( $shop_term && $shop_term->name != __( 'Shop', 'sullivan-compatibility' ) ) {

				wp_update_term( $shop_term->term_id, $taxonomy, array(
					'name'	=> __( 'Shop', 'sullivan-compatibility' )
				) );

			}

		}

	}
}
add_action( 'init', 'sullivan_compat_add_terms_to_slideshow_location_taxonomy' );