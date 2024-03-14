<?php

/**
 * Listings
 *
 * @link    https://pluginsware.com
 * @since   1.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */

// Exit if accessed directly
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * ACADP_Admin_Listings Class.
 *
 * @since 1.0.0
 */
class ACADP_Admin_Listings { 
	
	/**
	 * Register a custom post type "acadp_listings".
	 *
	 * @since 1.0.0
	 */
	public function register_custom_post_type() {
		$general_settings = get_option( 'acadp_general_settings' );
		$permalink_settings = get_option( 'acadp_permalink_settings' );

		$labels = array(
			'name'                => _x( 'Listings', 'Post Type General Name', 'advanced-classifieds-and-directory-pro' ),
			'singular_name'       => _x( 'Listing', 'Post Type Singular Name', 'advanced-classifieds-and-directory-pro' ),
			'menu_name'           => __( 'Classifieds & Directory', 'advanced-classifieds-and-directory-pro' ),
			'name_admin_bar'      => __( 'Listing', 'advanced-classifieds-and-directory-pro' ),
			'all_items'           => __( 'All Listings', 'advanced-classifieds-and-directory-pro' ),
			'add_new_item'        => __( 'Add New Listing', 'advanced-classifieds-and-directory-pro' ),
			'add_new'             => __( 'Add New', 'advanced-classifieds-and-directory-pro' ),
			'new_item'            => __( 'New Listing', 'advanced-classifieds-and-directory-pro' ),
			'edit_item'           => __( 'Edit Listing', 'advanced-classifieds-and-directory-pro' ),
			'update_item'         => __( 'Update Listing', 'advanced-classifieds-and-directory-pro' ),
			'view_item'           => __( 'View Listing', 'advanced-classifieds-and-directory-pro' ),
			'search_items'        => __( 'Search Listing', 'advanced-classifieds-and-directory-pro' ),
			'not_found'           => __( 'No listings found', 'advanced-classifieds-and-directory-pro' ),
			'not_found_in_trash'  => __( 'No listings found in Trash', 'advanced-classifieds-and-directory-pro' ),
		);
		
		$supports = array( 'title', 'editor', 'author', 'revisions' );		
				
		if ( ! empty( $general_settings['has_comment_form'] ) ) {
			array_push( $supports, 'comments' );
		}
		
		$args = array(
			'label'               => __( 'Listings', 'advanced-classifieds-and-directory-pro' ),
			'description'         => __( 'The Listings post type.', 'advanced-classifieds-and-directory-pro' ),
			'labels'              => $labels,
			'supports'            => $supports,
			'taxonomies'          => array( '' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => ( current_user_can( 'administrator' ) || current_user_can( 'editor' ) ) ? true : false,
			'show_in_menu'        => false,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'acadp_listing',
			'map_meta_cap'        => true,
		);		
		
		if ( isset( $permalink_settings['listing'] ) ) {
			$args['rewrite'] = array(
				'slug' => $permalink_settings['listing']
			);
		}
				
		register_post_type( 'acadp_listings', $args );
	}

	/**
	 * Add "All Listings" menu & remove unwanted meta boxes.
	 *
	 * @since 1.7.3
	 */
	public function admin_menu() {	
		add_submenu_page(
			'advanced-classifieds-and-directory-pro',
			__( 'Advanced Classifieds and Directory Pro - All Listings', 'advanced-classifieds-and-directory-pro' ),
			__( 'All Listings', 'advanced-classifieds-and-directory-pro' ),
			'edit_others_acadp_listings',
			'edit.php?post_type=acadp_listings'
		);	

		// Remove meta boxes
		remove_meta_box( 'acadp_categoriesdiv', 'acadp_listings', 'side' );
		remove_meta_box( 'acadp_locationsdiv', 'acadp_listings', 'side' );
	}

	/**
	 * Move "All Listings" submenu under our plugin's main menu.
	 *
	 * @since  1.7.3
	 * @param  string $parent_file The parent file.
	 * @return string $parent_file The parent file.
	 */
	public function parent_file( $parent_file ) {	
		global $submenu_file, $current_screen;

		if ( 'acadp_listings' == $current_screen->post_type ) {
			$submenu_file = 'edit.php?post_type=acadp_listings';
			$parent_file  = 'advanced-classifieds-and-directory-pro';
		}

		return $parent_file;
	}
	
	/**
	 * Adds custom meta fields in Publish meta box.
	 *
	 * @since 1.0.0
	 */
	public function post_submitbox_misc_actions() {	
		global $post, $post_type;
		
		if ( 'acadp_listings' == $post_type ) {			
			$featured_settings = get_option( 'acadp_featured_listing_settings' );
			$badges_settings = get_option( 'acadp_badges_settings' );				
			
			$post_meta = get_post_meta( $post->ID );
			
			$never_expires = ! empty( $post_meta['never_expires'][0] ) ? 1 : 0;
			$has_featured = apply_filters( 'acadp_has_featured', isset( $featured_settings['enabled'] ) );
			$mark_as_sold = ! empty( $badges_settings['mark_as_sold'] ) ? 1 : 0;

			// Add a nonce field so we can check for it later
    		wp_nonce_field( 'acadp_save_listing_submitbox', 'acadp_listing_submitbox_nonce' );

			require_once ACADP_PLUGIN_DIR . 'admin/templates/listings/listing-submitbox.php';
		}		
	}
	
	/**
	 * Register meta boxes.
	 *
	 * @since 1.0.0
	 */
	public function add_meta_boxes() {
		$general_settings = get_option( 'acadp_general_settings' );
		
		add_meta_box( 'acadp-listing-details', __( 'Listing details', 'advanced-classifieds-and-directory-pro' ), array( $this, 'display_meta_box_listing_details' ), 'acadp_listings', 'normal', 'high' );
		
		if ( ! empty( $general_settings['has_location'] ) ) {
	    	add_meta_box( 'acadp-contact-details', __( 'Contact details', 'advanced-classifieds-and-directory-pro' ), array( $this, 'display_meta_box_contact_details' ), 'acadp_listings', 'normal', 'high' );
		}
		
		if ( ! empty( $general_settings['has_images'] ) ) {
			add_meta_box( 'acadp-listing-images', __( 'Images', 'advanced-classifieds-and-directory-pro' ), array( $this, 'display_meta_box_listing_images' ), 'acadp_listings', 'normal', 'high' );
		}
		
		if ( ! empty( $general_settings['has_video'] ) ) {
			add_meta_box( 'acadp-listing-video', __( 'Video', 'advanced-classifieds-and-directory-pro' ), array( $this, 'display_meta_box_listing_video' ), 'acadp_listings', 'normal', 'high' ); 
		}		
	}
	
	/**
	 * Display a listing details meta box.
	 *
	 * @since 1.0.0
	 * @param WP_Post $post WordPress Post object.
	 */
	public function display_meta_box_listing_details( $post ) {	
		$general_settings = get_option( 'acadp_general_settings' );
		
		$disable_parent_categories = empty( $general_settings['disable_parent_categories'] ) ? false : true;
		$has_price = empty( $general_settings['has_price'] ) ? false : true;
		
		$post_meta = get_post_meta( $post->ID );
		
		$category = wp_get_object_terms( $post->ID, 'acadp_categories', array( 'fields' => 'ids' ) );		
		
		// Add a nonce field so we can check for it later
    	wp_nonce_field( 'acadp_save_listing_details', 'acadp_listing_details_nonce' );
		
		require_once ACADP_PLUGIN_DIR . 'admin/templates/listings/listing-details.php';
	}
	
	/**
	 * Display a contact details meta box.
	 *
	 * @since 1.0.0
	 * @param WP_Post $post WordPress Post object.
	 */
	public function display_meta_box_contact_details( $post ) {
		$general_settings = get_option( 'acadp_general_settings' );

		$post_meta = get_post_meta( $post->ID );		
		
		$location = wp_get_object_terms( $post->ID, 'acadp_locations', array( 'fields' => 'ids' ) );
		$location = count( $location ) ? $location[0] : $general_settings['default_location'];

		$latitude  = isset( $post_meta['latitude'] ) ? $post_meta['latitude'][0] : 0;
		$longitude = isset( $post_meta['longitude'] ) ? $post_meta['longitude'][0] : 0;

		if ( ! empty( $general_settings['has_map'] ) && empty( $latitude ) ) {
			$coordinates = acadp_get_location_coordinates( $location );

			$latitude  = $coordinates['latitude']; 
			$longitude = $coordinates['longitude'];
		}
		
		// Add a nonce field so we can check for it later
    	wp_nonce_field( 'acadp_save_contact_details', 'acadp_contact_details_nonce' );
		
		require_once ACADP_PLUGIN_DIR . 'admin/templates/listings/contact-details.php';
	}
	
	/**
	 * Display a meta box to add images.
	 *
	 * @since 1.0.0
	 * @param WP_Post $post WordPress Post object.
	 */
	public function display_meta_box_listing_images( $post ) {
		$general_settings = get_option( 'acadp_general_settings' );
		
		$post_meta = get_post_meta( $post->ID );
			
		// Add a nonce field so we can check for it later
    	wp_nonce_field( 'acadp_save_listing_images', 'acadp_listing_images_nonce' );
		
		require_once ACADP_PLUGIN_DIR . 'admin/templates/listings/listing-images.php';
	}
	
	/**
	 * Display a meta box to add video.
	 *
	 * @since 1.0.0
	 * @param WP_Post $post WordPress Post object.
	 */
	public function display_meta_box_listing_video( $post ) {		
		$general_settings = get_option( 'acadp_general_settings' );
		
		$post_meta = get_post_meta( $post->ID );
			
		// Add a nonce field so we can check for it later
    	wp_nonce_field( 'acadp_save_listing_video', 'acadp_listing_video_nonce' );
		
		require_once ACADP_PLUGIN_DIR . 'admin/templates/listings/listing-video.php';
	}
	
	/**
	 * Display custom fields.
	 *
	 * @since 1.0.0
	 * @param int   $post_id Post ID.
	 */
	public function ajax_callback_custom_fields( $post_id = 0 ) {	
		$ajax  = false;
		$terms = array();
		
		if ( isset( $_POST['security'] ) && isset( $_POST['post_id'] ) ) {
			check_ajax_referer( 'acadp_ajax_nonce', 'security' );

			$ajax = true;
			$post_id = (int) $_POST['post_id'];

			if ( isset( $_POST['terms'] ) ) {
				$terms = is_array( $_POST['terms'] ) ? array_map( 'intval', $_POST['terms'] ) : (int) $_POST['terms'];	
			}		
		} else {
			$post_id = (int) $post_id;
			
			if ( $post_id > 0 ) {
				$terms = wp_get_object_terms( $post_id, 'acadp_categories', array( 'fields' => 'ids' ) );
			}
		}
		
		// Get post meta for the given post_id
		$post_meta = get_post_meta( $post_id  );		
		
		// Get custom fields
		$custom_field_ids = acadp_get_custom_field_ids( $terms );
		
		if ( ! empty( $custom_field_ids ) ) {
			$args = array(
				'post_type' => 'acadp_fields',
				'posts_per_page' => 500,	
				'post__in' => $custom_field_ids,
				'no_found_rows' => true,
				'update_post_term_cache' => false,
				'meta_key' => 'order',
				'orderby' => 'meta_value_num',			
				'order' => 'ASC',
			);		
			
			$posts = get_posts( $args );
			
			// Process output
			ob_start();
			include ACADP_PLUGIN_DIR . 'admin/templates/listings/custom-fields.php';
			$output = ob_get_clean();
				
			echo $output;
		}
		
		if ( $ajax ) wp_die();
	}

	/**
	 * Delete attachments.
	 *
	 * @since 1.0.0
	 */
	public function before_delete_post( $post_id ) {	
		global $post_type;
		
		if ( 'acadp_listings' != $post_type ) {
			return;
		}

		$misc_settings = get_option( 'acadp_misc_settings' );
		  
		if ( ! empty( $misc_settings['delete_media_files'] ) ) {
			$images = get_post_meta( $post_id, 'images', true );
			
			if ( ! empty( $images ) ) {		
				foreach ( $images as $image ) {
					wp_delete_attachment( $image, true );
				}		
			}
		}	
	}
	
	/**
	 * Save meta data.
	 *
	 * @since  1.0.0
	 * @param  int     $post_id Post ID.
	 * @param  WP_Post $post    The post object.
	 * @return int     $post_id If the save was successful or not.
	 */
	public function save_meta_data( $post_id, $post ) {	
		if ( ! isset( $_POST['post_type'] ) ) {
        	return $post_id;
    	}
	
		// Check this is the "acadp_listings" custom post type
    	if ( 'acadp_listings' != $post->post_type ) {
        	return $post_id;
    	}
		
		// If this is an autosave, our form has not been submitted, so we don't want to do anything
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        	return $post_id;
		}
		
		// Check the logged in user has permission to edit this post
    	if ( ! acadp_current_user_can( 'edit_acadp_listing', $post_id ) ) {
        	return $post_id;
    	}
		
		// Check if "acadp_listing_submitbox_nonce" nonce is set
    	if ( isset( $_POST['acadp_listing_submitbox_nonce'] ) ) {		
			// Verify that the nonce is valid
    		if ( wp_verify_nonce( $_POST['acadp_listing_submitbox_nonce'], 'acadp_save_listing_submitbox' ) ) {			
				// OK to save meta data
				if ( isset( $_POST['never_expires'] ) ) {
					update_post_meta( $post_id, 'never_expires', 1 );
				} else {
					delete_post_meta( $post_id, 'never_expires' );
				}

				if ( isset( $_POST['expiry_date'] ) ) {									
					$expiry_date = sanitize_text_field( $_POST['expiry_date'] );
					update_post_meta( $post_id, 'expiry_date', $expiry_date );					
				} elseif ( 'publish' == $post->post_status ) {				
					$expiry_date = acadp_listing_expiry_date( $post_id );
					update_post_meta( $post->ID, 'expiry_date', $expiry_date );			
				}				    			
				
				$featured = isset( $_POST['featured'] ) ? 1 : 0;
				update_post_meta( $post_id, 'featured', $featured );
				
				$sold = isset( $_POST['sold'] ) ? 1 : 0;
    			update_post_meta( $post_id, 'sold', $sold );
				
				if ( isset( $_POST['listing_status'] ) ) {
					$listing_status = sanitize_text_field( $_POST['listing_status'] );
					update_post_meta( $post_id, 'listing_status', $listing_status );
				}				
			}			
		}		
		
		// Check if "acadp_listing_details_nonce" nonce is set
    	if ( isset( $_POST['acadp_listing_details_nonce'] ) ) {		
        	// Verify that the nonce is valid
    		if ( wp_verify_nonce( $_POST['acadp_listing_details_nonce'], 'acadp_save_listing_details' ) ) {			
				// OK to save meta data
				$cat_ids = isset( $_POST['acadp_category'] ) ? array_map( 'intval', (array) $_POST['acadp_category'] ) : array();
				$cat_ids = array_unique( $cat_ids );

				wp_set_object_terms( $post_id, null, 'acadp_categories' );
				wp_set_object_terms( $post_id, $cat_ids, 'acadp_categories', true );

				if ( isset( $_POST['price'] ) ) {				
					$price = acadp_sanitize_amount( $_POST['price'] );
    				update_post_meta( $post_id, 'price', $price );				
				}
				
				if ( isset( $_POST['acadp_fields'] ) ) {	
					foreach ( $_POST['acadp_fields'] as $key => $value ) {
						$key  = sanitize_key( $key );
						$type = get_post_meta( $key, 'type', true );
					
						switch ( $type ) {
							case 'text':
								$value = sanitize_text_field( $value );
								break;
							case 'textarea':
								$value = sanitize_textarea_field( $value );
								break;	
							case 'select':
							case 'radio':
								$value = sanitize_text_field( $value );
								break;					
							case 'checkbox':
								$value = array_map( 'sanitize_text_field', $value );
								$value = implode( "\n", array_filter( $value ) );
								break;
							case 'number':
							case 'range':
								$value = (int) $value;
								break;							
							case 'date':
							case 'datetime':
								$value = sanitize_text_field( $value );
								break;
							case 'url':
								$value = esc_url_raw( $value );
								break;
							default:
								$value = sanitize_text_field( $value );
						}
					
						update_post_meta( $post_id, $key, $value );
					}				
				}
				
				$views = (int) $_POST['views'];
    			update_post_meta( $post_id, 'views', $views );				
    		}			
    	}
				
		// Check if "acadp_contact_details_nonce" nonce is set
    	if ( isset( $_POST['acadp_contact_details_nonce'] ) ) {		
        	// Verify that the nonce is valid
    		if ( wp_verify_nonce( $_POST['acadp_contact_details_nonce'], 'acadp_save_contact_details' ) ) {			
				// OK to save meta data
				$address = sanitize_textarea_field( $_POST['address'] );
    			update_post_meta( $post_id, 'address', $address );
				
				$location = isset( $_POST['acadp_location'] ) ? (int) $_POST['acadp_location'] : 0;
				wp_set_object_terms( $post_id, $location, 'acadp_locations' );
				
				$zipcode = sanitize_text_field( $_POST['zipcode'] );
    			update_post_meta( $post_id, 'zipcode', $zipcode );
				
				$phone = sanitize_text_field( $_POST['phone'] );
    			update_post_meta( $post_id, 'phone', $phone );
				
				$email = sanitize_email( $_POST['email'] );
    			update_post_meta( $post_id, 'email', $email );
				
				$website = esc_url_raw( $_POST['website'] );
    			update_post_meta( $post_id, 'website', $website );
				
				$latitude = isset( $_POST['latitude'] ) ? sanitize_text_field( $_POST['latitude'] ) : '';
    			update_post_meta( $post_id, 'latitude', $latitude );
				
				$longitude = isset( $_POST['longitude'] ) ? sanitize_text_field( $_POST['longitude'] ) : '';
    			update_post_meta( $post_id, 'longitude', $longitude );

				$hide_map = isset( $_POST['hide_map'] ) ? (int) $_POST['hide_map'] : 0;
    			update_post_meta( $post_id, 'hide_map', $hide_map );				
			}			
		}
		
		// Check if "acadp_listing_images_nonce" nonce is set
    	if ( isset( $_POST['acadp_listing_images_nonce'] ) ) {		
        	// Verify that the nonce is valid
    		if ( wp_verify_nonce( $_POST['acadp_listing_images_nonce'], 'acadp_save_listing_images' ) ) {				
				if ( isset( $_POST['images'] ) ) {				
					// OK to save meta data
					$images = array_filter( $_POST['images'] );
					$images = array_map( 'intval', $images );
	
        			if ( count( $images ) ) {						
            			update_post_meta( $post_id, 'images', $images );
						set_post_thumbnail( $post_id, $images[0] );
        			} else { 
            			delete_post_meta( $post_id, 'images' );
						delete_post_thumbnail( $post_id );
					}					
				} else {				
					// Nothing received, all fields are empty, delete option					
					delete_post_meta( $post_id, 'images' );
					delete_post_thumbnail( $post_id );				
				}				
			}				
		}
				
		// Check if "acadp_listing_video_nonce" nonce is set
    	if ( isset( $_POST['acadp_listing_video_nonce'] ) ) {		
        	// Verify that the nonce is valid
    		if ( wp_verify_nonce( $_POST['acadp_listing_video_nonce'], 'acadp_save_listing_video' ) ) {			
				if ( isset( $_POST['video'] ) ) {
					$video = esc_url_raw( $_POST['video'] );
    				update_post_meta( $post_id, 'video', $video );
				}				
			}			
		}
		
		return $post_id;	
	}
	
	/**
	 * Notify listing owner when his listing approved/published.
	 *
	 * @since 1.0.0
	 * @param string  $new_status Transition to this post status.
	 * @param string  $old_status Previous post status.
	 * @param WP_Post $post       Post data.
	 */
	public function transition_post_status( $new_status, $old_status, $post ) {	
		if ( 'acadp_listings' !== $post->post_type ) {
			return;
		}
		
		// Check if we are transitioning from draft|pending to publish
    	if ( in_array( $old_status, array( 'draft', 'pending' ) ) && 'publish' == $new_status ) {
			acadp_email_listing_owner_listing_approved( $post->ID );			
		}
		
		// Check if we are transitioning from private to publish
    	if ( 'private' == $old_status && 'publish' == $new_status ) {		
			$listing_status = get_post_meta( $post->ID, 'listing_status', true );
			
			if ( 'expired' == $listing_status ) {
				update_post_meta( $post->ID, 'listing_status', 'post_status' );
				
				unset( $_POST['acadp_aa'] );
				unset( $_POST['listing_status'] );
			}			
		}		
	}
	
	/**
	 * Add custom filter options.
	 *
	 * @since 1.0.0
	 */
	public function restrict_manage_posts() {	
		global $typenow, $wp_query;
		
		if ( 'acadp_listings' == $typenow ) {			
			$general_settings = get_option( 'acadp_general_settings' );
			$featured_settings = get_option( 'acadp_featured_listing_settings' );

			// Restrict by location
			if ( ! empty( $general_settings['has_location'] ) ) {			
				$base_location = max( 0, $general_settings['base_location'] );
				
				wp_dropdown_categories(array(
            		'show_option_none'  => __( "All Locations", 'advanced-classifieds-and-directory-pro' ),					
					'option_none_value' => $base_location,
					'child_of'          => $base_location,
            		'taxonomy'          => 'acadp_locations',
            		'name'              => 'acadp_locations',
            		'orderby'           => 'name',
            		'selected'          => isset( $wp_query->query['acadp_locations'] ) ? $wp_query->query['acadp_locations'] : '',
            		'hierarchical'      => true,
            		'depth'             => 3,
            		'show_count'        => false,
            		'hide_empty'        => false,
        		));			
			}
			
			// Restrict by category
        	wp_dropdown_categories(array(
            	'show_option_none'  => __( 'All Categories', 'advanced-classifieds-and-directory-pro' ),
				'option_none_value' => 0,
            	'taxonomy'          => 'acadp_categories',
            	'name'              => 'acadp_categories',
            	'orderby'           => 'name',
            	'selected'          => isset( $wp_query->query['acadp_categories'] ) ? $wp_query->query['acadp_categories'] : '',
            	'hierarchical'      => true,
            	'depth'             => 3,
            	'show_count'        => false,
            	'hide_empty'        => false,
        	));

			// Restrict by featured
			$has_featured = apply_filters( 'acadp_has_featured', isset( $featured_settings['enabled'] ) );
			if ( $has_featured ) {			
				$featured = isset( $_GET['featured'] ) ? (int) $_GET['featured'] : 0;
			
				echo '<select name="featured">';
				printf( '<option value="%d"%s>%s</option>', 0, selected( 0, $featured, false ), __( "All Listings", 'advanced-classifieds-and-directory-pro' ) );
				printf( '<option value="%d"%s>%s</option>', 1, selected( 1, $featured, false ), __( "Featured only", 'advanced-classifieds-and-directory-pro' ) );
				echo '</select>';			
			}		
    	}	
	}
	
	/**
	 * Parse a query string and filter listings accordingly.
	 *
	 * @since 1.0.0
	 * @param WP_Query $query WordPress Query object.
	 */
	public function parse_query( $query ) {	
		global $pagenow, $post_type;
		
    	if ( 'edit.php' == $pagenow && 'acadp_listings' == $post_type ) {			
			// Convert location id to taxonomy term in query
			if ( isset( $query->query_vars['acadp_locations'] ) && ctype_digit( $query->query_vars['acadp_locations'] ) && $query->query_vars['acadp_locations'] != 0 ) {
        		$term = get_term_by( 'id', $query->query_vars['acadp_locations'], 'acadp_locations' );
        		$query->query_vars['acadp_locations'] = $term->slug;			
			}
			
			// Convert category id to taxonomy term in query
			if ( isset( $query->query_vars['acadp_categories'] ) && ctype_digit( $query->query_vars['acadp_categories'] ) && $query->query_vars['acadp_categories'] != 0 ) {
        		$term = get_term_by( 'id', $query->query_vars['acadp_categories'], 'acadp_categories' );
        		$query->query_vars['acadp_categories'] = $term->slug;			
    		}

			// Set featured meta in query
			if ( isset( $_GET['featured'] ) && 1 == $_GET['featured'] ) {		
        		$query->query_vars['meta_key'] = 'featured';
        		$query->query_vars['meta_value'] = 1;			
    		}			
		}	
	}
	
	/**
	 * Retrieve the table columns.
	 *
	 * @since  1.0.0
	 * @param  array $columns Array of default table columns.
	 * @return array $columns Updated list of table columns.
	 */
	public function get_columns( $columns ) {	
		$general_settings = get_option( 'acadp_general_settings' );
		$featured_settings = get_option( 'acadp_featured_listing_settings' );

		$new_columns = array(
			'views' => __( 'Views', 'advanced-classifieds-and-directory-pro' )
		);
		
		$has_featured = apply_filters( 'acadp_has_featured', isset( $featured_settings['enabled'] ) );
		if ( $has_featured ) {
			$new_columns['featured'] = __( 'Featured', 'advanced-classifieds-and-directory-pro' );
		}	
		
		$new_columns['posted_date'] = __( 'Posted Date', 'advanced-classifieds-and-directory-pro' );
		$new_columns['expiry_date'] = __( 'Expires on', 'advanced-classifieds-and-directory-pro' );
		$new_columns['status'] = __( 'Status', 'advanced-classifieds-and-directory-pro' );
			
		unset( $columns['date'] );
		
		$taxonomy_column = 'taxonomy-acadp_categories';
		
		return acadp_array_insert_after( $taxonomy_column, $columns, $new_columns );		
	}
	
	/**
	 * This function renders the custom columns in the list table.
	 *
	 * @since 1.0.0
	 * @param string $column  The name of the column.
	 * @param string $post_id Post ID.
	 */
	public function custom_column_content( $column, $post_id ) {	
		switch ( $column ) {
			case 'views' :
				echo get_post_meta( $post_id, 'views', true );
				break;
			case 'featured' :
				$value = get_post_meta( $post_id, 'featured', true );
				echo '&nbsp;&nbsp;&nbsp;&nbsp;' . ( $value == 1 ? '&#x2713;' : '&#x2717;' );
				break;	
			case 'posted_date' :
				printf( _x( '%s ago', '%s = human-readable time difference', 'advanced-classifieds-and-directory-pro' ), human_time_diff( get_the_time( 'U', $post_id ), current_time( 'timestamp' ) ) );
				break;
			case 'expiry_date' :
				$never_expires = get_post_meta( $post_id, 'never_expires', true );
				
				if ( ! empty( $never_expires ) ) {
					_e( 'Never Expires', 'advanced-classifieds-and-directory-pro' );
				} else {
					$expiry_date = get_post_meta( $post_id, 'expiry_date', true );
					
					if ( ! empty( $expiry_date ) ) {
						echo date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $expiry_date ) );
					} else {
						echo '-';
					}
				}
				break;
			case 'status' :
				$listing_status = get_post_meta( $post_id, 'listing_status', true );
				
				echo ( empty( $listing_status ) || 'post_status' == $listing_status ) ? get_post_status( $post_id ) : $listing_status;
				break;
		}		
	}	
	
	/**
	 * Filter the array of row action links on the Listings list table.
	 *
	 * @since  1.9.1
	 * @param  array   $actions An array of row action links.
	 * @param  WP_Post $post    The post object.
	 * @return array   $actions Updated array of row action links.
	 */
	public function post_row_actions( $actions, $post ) {	
		if ( 'acadp_listings' != $post->post_type ) {
			return $actions;
		}

		if ( ! acadp_current_user_can( 'edit_acadp_listings' ) ) {
			return $actions;
		}

		$url = wp_nonce_url(
			add_query_arg(
				array(
					'action' => 'acadp_duplicate_listing',
					'post' => $post->ID,
				),
				'admin.php'
			),
			basename( __FILE__ ),
			'acadp_duplicate_listing_nonce'
		);

		$actions['duplicate-listing'] = sprintf( 
			'<a href="%s" class="acadp-duplicate-listing">%s</a>',
			esc_url( $url ),
			esc_html__( 'Duplicate', 'advanced-classifieds-and-directory-pro' )
		);
		
		return $actions;	
	}

	/**
	 * Remove the listings location & category fields in the Quick Edit panel.
	 *
	 * @since  1.9.1
	 * @param  bool   $show_in_quick_edit Whether to show the current taxonomy in Quick Edit.
	 * @param  string $taxonomy_name      Taxonomy name.
	 * @param  string $post_type          Post type of current Quick Edit post.
	 * @return bool                       Filtered value.
	 */
	public function quick_edit_show_taxonomy( $show_in_quick_edit, $taxonomy_name, $post_type ) {	
		if ( 'acadp_locations' == $taxonomy_name || 'acadp_categories' == $taxonomy_name ) {
        	return false;
		}

    	return $show_in_quick_edit;	
	}

	/**
	 * Duplicate a listing.
	 *
	 * @since 1.9.1
	 */
	public function duplicate_listing() {	
		// Check if post ID has been provided and action
		if ( empty( $_GET[ 'post' ] ) ) {
			wp_die( esc_html__( 'No listing to duplicate has been provided!', 'advanced-classifieds-and-directory-pro' ) );
		}

		// Nonce verification
		if ( ! isset( $_GET[ 'acadp_duplicate_listing_nonce' ] ) || ! wp_verify_nonce( $_GET[ 'acadp_duplicate_listing_nonce' ], basename( __FILE__ ) ) ) {
			return;
		}

		// Get the original post id
		$post_id = absint( $_GET[ 'post' ] );

		// Get the original post data
		$post = get_post( $post_id );

		// Set the current user to be the new post author
		$current_user = wp_get_current_user();
		$new_post_author = $current_user->ID;

		// If post data exists, create the duplicate
		if ( $post ) {
			// New post data array
			$args = array(
				'post_type'      => 'acadp_listings',
				'post_title'     => $post->post_title,
				'post_name'      => $post->post_name,
				'post_content'   => $post->post_content,
				'post_excerpt'   => $post->post_excerpt,
				'post_parent'    => $post->post_parent,
				'post_password'  => $post->post_password,
				'post_author'    => $new_post_author,
				'post_status'    => 'draft',				
				'comment_status' => $post->comment_status,
				'ping_status'    => $post->ping_status,				
				'to_ping'        => $post->to_ping,
				'menu_order'     => $post->menu_order
			);

			// Insert the post
			$new_post_id = wp_insert_post( $args );

			/*
			 * Get all current post terms ad set them to the new post draft
			 */
			$taxonomies = get_object_taxonomies( get_post_type( $post ) );
			if ( $taxonomies ) {
				foreach ( $taxonomies as $taxonomy ) {
					$post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'ids' ) );
					wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
				}
			}

			// Duplicate all post meta
			$post_meta = get_post_meta( $post_id );
			if ( $post_meta ) {
				foreach ( $post_meta as $meta_key => $meta_values ) {
					$excluded_keys = array(						
						'expiry_date',
						'fee_plan_id',
						'wc_plan_id',
						'_wp_old_slug'
					);

					if ( in_array( $meta_key, $excluded_keys ) ) { // Do nothing for this meta key
						continue;
					}					

					foreach ( $meta_values as $meta_value ) {
						if ( 'listing_status' == $meta_key ) {
							$meta_value = 'post_status';
						}

						add_post_meta( $new_post_id, $meta_key, maybe_unserialize( $meta_value ) );
					}
				}
			}

			// Finally, redirect to the all listings page with a message
			wp_safe_redirect(
				add_query_arg(
					array(
						'post_type' => 'acadp_listings',
						'saved'     => 'acadp_listing_duplication_created'
					),
					admin_url( 'edit.php' )
				)
			);

			exit;
		} else {
			wp_die( esc_html__( 'Listing creation failed, could not find original listing.', 'advanced-classifieds-and-directory-pro' ) );
		}
	}

	/**
	 * Display an admin notice on the "All Listings" page after a listing is duplicated.
	 *
	 * @since 1.9.1
	 */
	public function admin_notices() {	
		$screen = get_current_screen();

		if ( 'edit' !== $screen->base ) {
			return;
		}

		if ( isset( $_GET[ 'saved' ] ) && 'acadp_listing_duplication_created' == $_GET[ 'saved' ] ) {
			printf(
				'<div class="notice notice-success is-dismissible"><p>%s.</p></div>',
				esc_html__( 'Listing copy created', 'advanced-classifieds-and-directory-pro' )
			);			
	   	}		
	}

}