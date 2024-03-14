<?php

/**
 * HTML outputs.
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
 * Display user menu links.
 *
 * @since 1.0.0
 */
function the_acadp_user_menu() {	
	$listing_settings = get_option( 'acadp_listing_settings' );
	$registration_settings = get_option( 'acadp_registration_settings' );
	$page_settings = get_option( 'acadp_page_settings' );
	
	$links = array();					
	
	if ( acadp_current_user_can('edit_acadp_listings') && $page_settings['listing_form'] > 0 ) {
		$links[] = '<a href="' . esc_url( get_permalink( $page_settings['listing_form'] ) ) . '">' . __( 'Add New Listing', 'advanced-classifieds-and-directory-pro' ) . '</a>';
	}
	
	if ( acadp_current_user_can('edit_acadp_listings') && $page_settings['manage_listings'] > 0 ) {
		$links[] = '<a href="' . esc_url( get_permalink( $page_settings['manage_listings'] ) ) . '">' . esc_html( get_the_title( $page_settings['manage_listings'] ) ) . '</a>';
	}
	
	if ( ! empty( $listing_settings['has_favourites'] ) && $page_settings['favourite_listings'] > 0 ) {
		$links[] = '<a href="' . esc_url( get_permalink( $page_settings['favourite_listings'] ) ) . '">' . esc_html( get_the_title( $page_settings['favourite_listings'] ) ) . '</a>';
	}
	
	if ( acadp_current_user_can('edit_acadp_listings') && $page_settings['payment_history'] > 0 ) {
		$links[] = '<a href="' . esc_url( get_permalink( $page_settings['payment_history'] ) ) . '">' . esc_html( get_the_title( $page_settings['payment_history'] ) ) . '</a>';
	}
	
	if ( ! empty( $registration_settings['engine'] ) && 'acadp' == $registration_settings['engine'] && $page_settings['user_account'] > 0 ) {
		$links[] = '<a href="' . esc_url( get_permalink( $page_settings['user_account'] ) ) . '">' . __( 'User Account', 'advanced-classifieds-and-directory-pro' ) . '</a>';
	}
	
	echo '<p class="acadp-no-margin">' . implode( ' | ', $links ) . '</p>';
}

/**
 * Adds "Terms and Conditions" field to the registration/listing form.
 *
 * @since 1.0.0
 */
function the_acadp_terms_of_agreement() {
	$tos_settings = get_option( 'acadp_terms_of_agreement' );
	
	if ( ! empty( $tos_settings['show_agree_to_terms'] ) && ! empty( $tos_settings['agree_text'] ) ) {	
		$agree_text  = trim( $tos_settings['agree_text'] );
		$agree_type  = filter_var( $agree_text, FILTER_VALIDATE_URL ) ? 'url' : 'txt';
		$agree_label = ! empty( $tos_settings['agree_label'] ) ? trim( $tos_settings['agree_label'] ) : __( 'I have read and agree to the Terms and Conditions', 'advanced-classifieds-and-directory-pro' );
		
		$label = ( 'url' == $agree_type ) ? sprintf( '<a href="%s" target="_blank">%s</a>', $agree_text, $agree_label ) : $agree_label;
		$text  = ( 'txt' == $agree_type ) ? nl2br( $agree_text ) : '';
		
		printf( 
			'<div class="form-group acadp-terms_of_agreement"><div class="checkbox"><label><input type="checkbox" name="terms_of_agreement" required />%s<span class="acadp-star">*</span></label></div>%s</div>', 
			wp_kses_post( $label ), 
			wp_kses_post( $text ) 
		);		
	}	
}

/**
 * Adds "Privacy Policy" field to the registration/listing form.
 *
 * @since 1.9.0
 */
function the_acadp_privacy_policy() {
	$privacy_policy_settings = get_option( 'acadp_privacy_policy' );
	
	if ( ! empty( $privacy_policy_settings['show_privacy_policy'] ) && ! empty( $privacy_policy_settings['privacy_policy_text'] ) ) {	
		$privacy_policy_text  = trim( $privacy_policy_settings['privacy_policy_text'] );
		$privacy_policy_type  = filter_var( $privacy_policy_text, FILTER_VALIDATE_URL ) ? 'url' : 'txt';
		$privacy_policy_label = ! empty( $privacy_policy_settings['privacy_policy_label'] ) ? trim( $privacy_policy_settings['privacy_policy_label'] ) : __( 'I have read and agree to the Privacy Policy', 'advanced-classifieds-and-directory-pro' );
		
		$label = ( 'url' == $privacy_policy_type ) ? sprintf( '<a href="%s" target="_blank">%s</a>', $privacy_policy_text, $privacy_policy_label ) : $privacy_policy_label;
		$text  = ( 'txt' == $privacy_policy_type ) ? nl2br( $privacy_policy_text ) : '';
		
		printf( 
			'<div class="form-group acadp-privacy_policy"><div class="checkbox"><label><input type="checkbox" name="privacy_policy" required />%s<span class="acadp-star">*</span></label></div>%s</div>', 
			wp_kses_post( $label ), 
			wp_kses_post( $text )
		);		
	}	
}

/**
 * Adds cookie consent banner.
 *
 * @since 1.9.0
 * @param string $type Type of embed.
 */
function the_acadp_cookie_consent( $type = 'map' ) {
	$cookie_consent_settings = get_option( 'acadp_cookie_consent' );

	$show_cookie_consent = false;
	if ( ! isset( $_COOKIE['acadp_gdpr_consent'] ) && ! empty( $cookie_consent_settings['show_cookie_consent'] ) && ! is_user_logged_in() ) {
		$show_cookie_consent = true;
	}

	if ( $show_cookie_consent ) {
		$consent_message = apply_filters( 'acadp_translate_strings', $cookie_consent_settings['consent_message'], 'consent_message' );
		$consent_button_label = apply_filters( 'acadp_translate_strings', $cookie_consent_settings['consent_button_label'], 'consent_button_label' );

		printf(
			'<div class="acadp-privacy-wrapper acadp-privacy-wrapper-%s"><div class="acadp-privacy-consent-block"><div class="acadp-privacy-consent-message">%s</div><button type="button" class="btn btn-default acadp-privacy-consent-button"><span class="glyphicon glyphicon-%s"></span> %s</button></div></div>',
			esc_attr( $type ),
			wp_kses_post( trim( $consent_message ) ),
			( 'video' == $type ? 'play-circle' : 'map-marker' ),
			esc_html( $consent_button_label )
		);
	}	
}

/**
 * Display Social Sharing Buttons.
 *
 * @since 1.0.0
 */
function the_acadp_social_sharing_buttons() {
	global $post;

	if ( ! isset( $post ) ) return;
	
	$page_settings = get_option( 'acadp_page_settings' );
	$socialshare_settings = get_option( 'acadp_socialshare_settings' );
		
	$page = 'none';
	
	if ( 'acadp_listings' == $post->post_type ) {
		$page = 'listing';
	}
	
	if ( $post->ID == $page_settings['locations'] ) {
		$page = 'locations';
	}
	
	if ( $post->ID == $page_settings['categories'] ) {
		$page = 'categories';
	}

	if ( in_array( $post->ID, array( $page_settings['listings'], $page_settings['location'], $page_settings['category'], $page_settings['search'] ) ) ) {
		$page = 'listings';
	}
	
	if ( isset( $socialshare_settings['pages'] ) && in_array( $page, $socialshare_settings['pages'] ) ) {	
		// Get current page URL 
		$url = acadp_get_current_url();
 
		// Get current page title
		$title = esc_html( $post->post_title );
			
		if ( $post->ID == $page_settings['location'] ) {			
			if ( $slug = get_query_var( 'acadp_location' ) ) {
				$term = get_term_by( 'slug', $slug, 'acadp_locations' );
				$title = $term->name;			
			}				
		}
		
		if ( $post->ID == $page_settings['category'] ) {			
			if ( $slug = get_query_var( 'acadp_category' ) ) {
				$term = get_term_by( 'slug', $slug, 'acadp_categories' );
				$title = $term->name;			
			}				
		}
			
		if ( $post->ID == $page_settings['user_listings'] ) {			
			if ( $slug = acadp_get_user_slug() ) {
				$user = get_user_by( 'slug', $slug );
				$title = $user->display_name;		
			}				
		}
			
		$title = str_replace( ' ', '%20', $title );
	
		// Get Post Thumbnail
		$thumbnail = '';
		
		if ( 'listing' == $page ) {
			$images = get_post_meta( $post->ID, 'images', true );
			
			if ( ! empty( $images ) ) { 
				$image_attributes = wp_get_attachment_image_src( $images[0], 'full' );
				$thumbnail = is_array( $image_attributes ) ? $image_attributes[0] : '';
			}
		} else {
			$image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			$thumbnail = is_array( $image_attributes ) ? $image_attributes[0] : '';
		}
 
		// Construct sharing buttons
		$buttons = array();
	
		if ( isset( $socialshare_settings['services'] ) ) {		
			if ( in_array( 'facebook', $socialshare_settings['services'] ) ) {
				$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
				$buttons[] = '<a class="acadp-social-link acadp-social-facebook" href="' . $facebookURL . '" target="_blank">' . __( 'Facebook', 'advanced-classifieds-and-directory-pro' ) . '</a>';
			}
	
			if ( in_array( 'twitter', $socialshare_settings['services'] ) ) {
				$twitterURL = 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $url;
				$buttons[] = '<a class="acadp-social-link acadp-social-twitter" href="' . $twitterURL . '" target="_blank">' . __( 'Twitter', 'advanced-classifieds-and-directory-pro' ) . '</a>';
			}
		
			if ( in_array( 'linkedin', $socialshare_settings['services'] ) ) {
				$linkedinURL = 'https://www.linkedin.com/shareArticle?url=' . $url . '&amp;title=' . $title;
				$buttons[] = '<a class="acadp-social-link acadp-social-linkedin" href="' . $linkedinURL . '" target="_blank">' . __( 'Linkedin', 'advanced-classifieds-and-directory-pro' ) . '</a>';
			}
	
			if ( in_array( 'pinterest', $socialshare_settings['services'] ) ) {
				$pinterestURL = 'https://pinterest.com/pin/create/button/?url=' . $url . '&amp;media=' . $thumbnail . '&amp;description=' . $title;
				$buttons[] = '<a class="acadp-social-link acadp-social-pinterest" href="' . $pinterestURL . '" target="_blank">' . __( 'Pin It', 'advanced-classifieds-and-directory-pro' ) . '</a>';
			}

			if ( in_array( 'whatsapp', $socialshare_settings['services'] ) ) {
				if ( wp_is_mobile() ) {
					$whatsappURL = "whatsapp://send?text={$title} " . rawurlencode( $url );
				} else {
					$whatsappURL = 'https://api.whatsapp.com/send?text=' . $title . '&nbsp;' . $url;
				}
								
				$buttons[] = '<a class="acadp-social-link acadp-social-whatsapp" href="' . $whatsappURL . '" target="_blank" data-text="' . $title . '" data-link="' . $url . '">' . __( 'WhatsApp', 'advanced-classifieds-and-directory-pro' ) . '</a>';
			}			
		}
	
		if ( count( $buttons ) ) {
			echo '<div class="acadp-social">' . implode( ' ', $buttons ) . '</div>';
		}	
	}
}

/**
 * Display the listing entry classes.
 *
 * @since 1.5.5
 * @param array  $post_meta Post Meta.
 * @param string $class     CSS Class Names.
 */
function the_acadp_listing_entry_class( $post_meta, $class = '' ) {	
	$class .= ' acadp-entry';
	
	if ( isset( $post_meta['featured'] ) && 1 == (int) $post_meta['featured'][0] ) {
    	$class .= ' acadp-entry-featured';
	}
	
	printf( 'class="%s"', trim( $class ) );	
}

/**
 * Display the listing thumbnail.
 *
 * @since 1.0.0
 * @param array $post_meta Post Meta.
 */
function the_acadp_listing_thumbnail( $post_meta ) {
	$image = '';
		
	if ( isset( $post_meta['images'] ) ) {
		$images = unserialize( $post_meta['images'][0] );
		$image_attributes = wp_get_attachment_image_src( $images[0], 'medium' );
		$image = $image_attributes[0];
	}
	
	if ( ! $image ) {
		$image = apply_filters( 'acadp_no_image_file_path', ACADP_PLUGIN_URL . 'public/assets/images/no-image.png' );
	}
	
	$html = '<img src="' . esc_attr( $image ) . '" alt="" />';
	$filtered_html = apply_filters( 'acadp_listing_thumbnail_html', $html, $post_meta );

	echo $filtered_html;
}

/**
 * Get the listing labels.
 *
 * @since  1.8.6
 * @param  array $post_meta Post Meta.
 * @return array $labels    Listing labels array.
 */
function acadp_get_listing_labels( $post_meta ) {
	global $post;
	
	$badges_settings = get_option( 'acadp_badges_settings' );
	$featured_listing_settings = get_option( 'acadp_featured_listing_settings' );	
	
	$labels = array();

	if ( ! empty( $badges_settings['show_new_tag'] ) ) {		
		$each_hours = 60 * 60 * 24; // seconds in a day
    	$s_date1 = strtotime( current_time( 'mysql' ) ); // seconds for date 1
    	$s_date2 = strtotime( $post->post_date ); // seconds for date 2
    	$s_date_diff = abs( $s_date1 - $s_date2 ); // different of the two dates in seconds
    	$days = round( $s_date_diff / $each_hours ); // divided the different with second in a day
	
		if ( $days <= (int) $badges_settings['new_listing_threshold'] ) {
			$labels[] = '<span class="label label-primary">' . $badges_settings['new_listing_label'] . '</span>';
		}		
	}
	
	if ( ! empty( $badges_settings['show_popular_tag'] ) ) {	
		if ( isset( $post_meta['views'] ) && (int) $post_meta['views'][0] >= (int) $badges_settings['popular_listing_threshold'] ) {
    		$labels[] = '<span class="label label-success">' . $badges_settings['popular_listing_label'] . '</span>';
		}		
	}
	
	if ( ! empty( $featured_listing_settings['show_featured_tag'] ) ) {	
		if ( isset( $post_meta['featured'] ) && 1 == (int) $post_meta['featured'][0] ) {
    		$labels[] = '<span class="label label-warning">' . $featured_listing_settings['label'] . '</span>';
		}		
	}

	if ( ! empty( $badges_settings['mark_as_sold'] ) ) {	
		if ( isset( $post_meta['sold'] ) && 1 == (int) $post_meta['sold'][0] ) {
    		$labels[] = '<span class="label label-danger">' . $badges_settings['sold_listing_label'] . '</span>';
		}		
	}

	return apply_filters( 'acadp_get_listing_labels', $labels, $post_meta );
}

/**
 * Display the listing labels.
 *
 * @since 1.0.0
 * @param array $post_meta Post Meta.
 */
function the_acadp_listing_labels( $post_meta ) {
	$labels = acadp_get_listing_labels( $post_meta );

	if ( ! empty( $labels ) ) {
		echo '<div class="acadp-labels">' . implode( "&nbsp;", $labels ) . '</div>';
	}
}

/**
 * Display the listing address.
 *
 * @since 1.0.0
 * @param array $post_meta Post Meta.
 * @param int   $term_id   Custom Taxonomy term ID.
 */
function the_acadp_address( $post_meta, $term_id ) {
	$listing_settings = get_option( 'acadp_listing_settings' );

	// Get all the location term ids
	$locations = array( $term_id );
	$ancestors = get_ancestors( $term_id, 'acadp_locations' );
	
	$locations = array_merge( $locations, $ancestors );
	
	// Build address vars
	echo '<p class="acadp-address">';
	
	if ( ! empty( $post_meta['address'][0] ) ) {
		echo '<span class="acadp-street-address">' . $post_meta['address'][0] . '</span>';
	}
	
	$pieces = array();
	
	$country = end( $locations );
	
	if ( count( $locations ) > 1 ) {
		array_pop( $locations );

		foreach ( $locations as $region ) {
			$term = get_term( $region, 'acadp_locations' );
			if ( ! empty( $term ) && ! is_wp_error( $term ) ) {
				$pieces[] = '<span class="acadp-locality"><a href="' . esc_url( acadp_get_location_page_link( $term ) ) . '">' . $term->name . '</a></span>';
			}
		}
	}	

	$term = get_term( $country, 'acadp_locations' );
	if ( ! empty( $term ) && ! is_wp_error( $term ) ) {
		$pieces[] = '<span class="acadp-country-name"><a href="' . esc_url( acadp_get_location_page_link( $term ) ) . '">' . $term->name . '</a></span>';
	}
	
	if ( ! empty( $post_meta['zipcode'][0] ) ) {
		$pieces[] = $post_meta['zipcode'][0];
	}
	
	echo implode( '<span class="acadp-delimiter">,</span>', $pieces );
	
	if ( 'never' != $listing_settings['show_phone_number'] && ! empty( $post_meta['phone'][0] ) ) {
		echo '<span class="acadp-phone">';
		echo '<span class="glyphicon glyphicon-earphone"></span>&nbsp;';
		if ( 'open' == $listing_settings['show_phone_number'] ) {
			echo '<span class="acadp-phone-number"><a href="tel:' . $post_meta['phone'][0] . '">' . $post_meta['phone'][0] . '</a></span>';
		} else {
			echo '<span><a class="acadp-show-phone-number" href="javascript: void(0);">' . __( 'Show phone number', 'advanced-classifieds-and-directory-pro' ) . '</a></span>';
			echo '<span class="acadp-phone-number" style="display: none;"><a href="tel:' . $post_meta['phone'][0] . '">' . $post_meta['phone'][0] . '</a></span>';
		}
		echo '</span>';
	}
		
	if ( 'never' != $listing_settings['show_email_address'] && ! empty( $post_meta['email'][0] ) ) {		
		if ( 'public' == $listing_settings['show_email_address'] || is_user_logged_in() ) {
			echo '<span class="acadp-email"><span class="glyphicon glyphicon-envelope"></span>&nbsp;<a href="mailto:' . $post_meta['email'][0] . '">' . $post_meta['email'][0] . '</a></span>';
		} else {
			echo '<span class="acadp-email"><span class="glyphicon glyphicon-envelope"></span>&nbsp;*****</span>';
		}
	}
	
	if ( ! empty( $post_meta['website'][0] ) ) {
		echo '<span class="acadp-website"><span class="glyphicon glyphicon-globe"></span>&nbsp;<a href="' . $post_meta['website'][0] . '" target="_blank">' . $post_meta['website'][0] . '</a></span>';
	}
	
	echo '</p>';	
}

/**
 * Get activated payment gateways.
 *
 * @since 1.0.0
 */
function the_acadp_payment_gateways() {
	$gateways = acadp_get_payment_gateways();	
	$settings = get_option( 'acadp_gateway_settings' );	
	
	$list = array();
	
	if ( isset( $settings['gateways'] ) ) {	
		foreach ( $gateways as $key => $label ) {			
			if ( in_array( $key, $settings['gateways'] ) ) {			
				$gateway_settings = get_option( 'acadp_gateway_' . $key . '_settings' );
				$label = ! empty( $gateway_settings['label'] ) ? $gateway_settings['label'] : $label;
					
				$html  = '<li class="list-group-item acadp-no-margin-left">';
				$html .= sprintf( '<div class="radio acadp-no-margin"><label><input type="radio" name="payment_gateway" value="%s"%s>%s</label></div>', $key, ( $key == end( $settings['gateways'] ) ? ' checked' : '' ), $label );
				
				if ( ! empty( $gateway_settings['description'] ) ) {
					$html .= '<p class="text-muted acadp-no-margin">' . $gateway_settings['description'] . '</p>';
				}
				
				$html .= '</li>';
				
				$list[] = $html;
			}			
		}		
	}
	
	if ( count( $list ) ) {
		echo '<ul class="list-group">' . implode( "\n", $list ) . '</ul>';
	}	
}

/**
 * Get instructions to do offline payment.
 *
 * @since 1.0.0
 */
function the_acadp_offline_payment_instructions() {
	$settings = get_option('acadp_gateway_offline_settings');
	echo '<p>' . nl2br( $settings['instructions'] ) . '</p>';	
}

/**
 * Retrieve paginated link for listing pages.
 *
 * @since 1.5.4
 * @param int   $numpages  The total amount of pages.
 * @param int   $pagerange How many numbers to either side of current page.
 * @param int   $paged     The current page number.
 */
function the_acadp_pagination( $numpages = '', $pagerange = '', $paged = '' ) {	
	if ( empty( $pagerange ) ) {
    	$pagerange = 2;
  	}

  	/**
   	 * This first part of our function is a fallback
     * for custom pagination inside a regular loop that
     * uses the global $paged and global $wp_query variables.
     * 
     * It's good because we can now override default pagination
     * in our theme, and use this function in default quries
     * and custom queries.
     */
  	if ( empty( $paged ) ) {
    	$paged = acadp_get_page_number();
  	}
	
  	if ( '' == $numpages ) {
    	global $wp_query;
    	
		$numpages = $wp_query->max_num_pages;
    	if ( ! $numpages ) {
        	$numpages = 1;
    	}
  	}

	// Construct the pagination arguments to enter into our paginate_links function
	$arr_params = array();

	parse_str( $_SERVER['QUERY_STRING'], $queries );
	if ( ! empty( $queries ) ) {
		$arr_params = array_keys( $queries );
	}
	 
	$base = acadp_remove_query_arg( $arr_params, get_pagenum_link( 1 ) );
	
	if ( ! get_option('permalink_structure') || isset( $_GET['q'] ) ) {
		$prefix = strpos( $base, '?' ) ? '&' : '?';
    	$format = $prefix . 'paged=%#%';
    } else {
		$prefix = ( '/' == substr( $base, -1 ) ) ? '' : '/';
    	$format = $prefix . 'page/%#%';
    } 
	
  	$pagination_args = array(
    	'base'         => $base . '%_%',
    	'format'       => $format,
    	'total'        => $numpages,
    	'current'      => $paged,
    	'show_all'     => false,
    	'end_size'     => 1,
    	'mid_size'     => $pagerange,
    	'prev_next'    => true,
    	'prev_text'    => __( '&laquo;' ),
    	'next_text'    => __( '&raquo;' ),
    	'type'         => 'array',
    	'add_args'     => false,
    	'add_fragment' => ''
  	);

  	$paginate_links = paginate_links( $pagination_args );

  	if ( $paginate_links ) {
		echo "<div class='row text-center acadp-no-margin'>";
		
		echo "<div class='pull-left text-muted'>";
		printf( __( "Page %d of %d", 'advanced-classifieds-and-directory-pro' ), $paged, $numpages );
		echo "</div>";
		
		echo "<ul class='pagination acadp-no-margin'>"; 		   	
		foreach ( $paginate_links as $key => $page_link ) {		
			if ( strpos( $page_link, 'current' ) !== false ) {
			 	echo '<li class="active">' . $page_link . '</li>';
			} else {
				echo '<li>' . $page_link . '</li>';
			}			
		}
   		echo "</ul>";
		
		echo "</div>";
  	}
}

/**
 * Retrieve paginated link for listing pages.
 *
 * @since      1.0.0
 * @deprecated 1.5.4
 * @param      int   $numpages  The total amount of pages.
 * @param      int   $pagerange How many numbers to either side of current page.
 * @param      int   $paged     The current page number.
 */
function acadp_pagination( $numpages = '', $pagerange = '', $paged = '' ) {
	the_acadp_pagination( $numpages, $pagerange, $paged );
}

/**
 * Outputs the ACADP categories/locations dropdown.
 *
 * @since  1.5.5
 * @param  array  $args Array of options to control the field output.
 * @param  bool   $echo Whether to echo or just return the string.
 * @return string       HTML attribute or empty string.
 */
function acadp_dropdown_terms( $args = array(), $echo = true ) {
	// Vars
	$args = array_merge( array(
		'show_option_none'  => '-- ' . __( 'Select category', 'advanced-classifieds-and-directory-pro' ) . ' --',
		'option_none_value' => '',
		'taxonomy'          => 'acadp_categories',
		'name' 			    => 'acadp_category',
		'class'             => 'form-control',
		'required'          => false,
		'base_term'         => 0,
		'parent'            => 0,
		'orderby'           => 'name',
		'order'             => 'ASC',
		'selected'          => 0
	), $args );
	
	if ( ! empty( $args['selected'] ) ) {
		$ancestors = get_ancestors( $args['selected'], $args['taxonomy'] );
		$ancestors = array_merge( array_reverse( $ancestors ), array( $args['selected'] ) );
	} else {
		$ancestors = array();
	}

	// Build data
	$html = '';
		
	if ( isset( $args['walker'] ) ) {
		$selected = count( $ancestors ) >= 2 ? (int) $ancestors[1] : 0;
		
		$html .= '<div class="acadp-terms">';	
		$html .= sprintf( '<input type="hidden" name="%s" class="acadp-term-hidden" value="%d" />', $args['name'], $selected );
		
		$term_args = array(
			'show_option_none'  => $args['show_option_none'],
			'option_none_value' => $args['option_none_value'],			
			'taxonomy'          => $args['taxonomy'],			
			'child_of'          => $args['parent'],
			'orderby'           => $args['orderby'],
			'order'             => $args['order'],
			'selected'          => $selected,
			'hierarchical'      => true,
			'depth'             => 2,
			'show_count'        => false,
			'hide_empty'        => false,
			'walker'            => $args['walker'],
			'echo'              => 0
		);
		
		unset( $args['walker'] );
	
		$select  = wp_dropdown_categories( $term_args );
		$required = $args['required'] ? ' required' : '';
		$replace = sprintf( '<select class="%s" data-taxonomy="%s" data-parent="%d"%s>', $args['class'], $args['taxonomy'], $args['parent'], $required );
				
		$html .= preg_replace( '#<select([^>]*)>#', $replace, $select );
		
		if ( $selected > 0 ) { 
			$args['parent'] = $selected;
			$html .= acadp_dropdown_terms( $args, false );
		}
		
		$html .= '</div>'; 	
	} else { 
		$has_children = 0;
		$child_of     = 0;
	
		$term_args = array(			
			'parent'       => $args['parent'], 
			'orderby'      => 'name',   
			'order'        => 'ASC',  
			'hide_empty'   => false,  
			'hierarchical' => false  
		);		
		$terms = get_terms( $args['taxonomy'], $term_args );
 
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) { 		
			if ( $args['parent'] == $args['base_term'] ) {
				$required = $args['required'] ? ' required' : '';
				 
				$html .= '<div class="acadp-terms">';	 
				$html .= sprintf( '<input type="hidden" name="%s" class="acadp-term-hidden" value="%d" />', $args['name'], $args['selected'] ); 
				$html .= sprintf( '<select class="%s" data-taxonomy="%s" data-parent="%d"%s>', $args['class'], $args['taxonomy'], $args['parent'], $required ); 
				$html .= sprintf( '<option value="%s">%s</option>', $args['option_none_value'],  $args['show_option_none'] ); 
			} else {
				$html .= sprintf( '<div class="acadp-child-terms acadp-child-terms-%d">', $args['parent'] );	 
				$html .= sprintf( '<select class="%s" data-taxonomy="%s" data-parent="%d">', $args['class'], $args['taxonomy'], $args['parent'] ); 

				$parent = get_term_by( 'id', $args['parent'], $args['taxonomy'] );
				$parent_name = sprintf( __( 'All %s', 'advanced-classifieds-and-directory-pro' ), $parent->name );
				$html .= sprintf( '<option value="%d">%s</option>', $args['parent'], '-- ' . $parent_name . ' --' );
			} 
		
			foreach ( $terms as $term ) { 
				$selected = '';
				if ( in_array( $term->term_id, $ancestors ) ) { 
					$has_children = 1;
					$child_of = $term->term_id; 
					$selected = ' selected'; 
				} elseif ( $term->term_id == $args['selected'] ) { 
					$selected = ' selected';
				}
				$html .= sprintf( '<option value="%d"%s>%s</option>', $term->term_id, $selected, $term->name ); 
			}
			
			$html .= '</select>';	
			if ( $has_children ) {
				$args['parent'] = $child_of;
				$html .= acadp_dropdown_terms( $args, false );
			}
			$html .= '</div>';			
		} else {		
			if ( $args['parent'] == $args['base_term'] ) {
				$required = $args['required'] ? ' required' : '';
				
				$html .= '<div class="acadp-terms">';	
				$html .= sprintf( '<input type="hidden" name="%s" class="acadp-term-hidden" value="%d" />', $args['name'], $args['selected'] );
				$html .= sprintf( '<select class="%s" data-taxonomy="%s" data-parent="%d"%s>', $args['class'], $args['taxonomy'], $args['parent'], $required ); 
				$html .= sprintf( '<option value="%s">%s</option>', $args['option_none_value'],  $args['show_option_none'] ); 
				$html .= '</select>';
				$html .= '</div>';
			}			
		}
	}
	
	// Echo or Return
	if ( $echo ) {
		echo $html;
		return '';
	} else {
		return $html;
	}
}

/**
 * Custom Select: Categories / Locations.
 *
 * @since  3.0.0
 * @param  array  $params Array of params input.
 * @return string         Dropdown HTML string.
 */
function acadp_get_terms_dropdown_html( $params = array() ) {
	// Vars
	$defaults = array(
		'placeholder'     => '— ' . esc_html__( 'Select Category', 'advanced-classifieds-and-directory-pro' ) . ' —',
		'taxonomy'        => 'acadp_categories',
		'parent'          => 0,
		'parent_disabled' => false,
 		'name' 	          => 'acadp_category',
		'id'              => '',
		'class' 	      => '',
		'multiple'        => false,
		'required'        => false,	
		'selected'        => array(),
		'ancestors'       => array(),		
		'level'           => 1
	);

	$attributes = array_merge( $defaults, $params );

	$attributes['selected'] = (array) $attributes['selected'];
	
	if ( empty( $attributes['ancestors'] ) && ! empty( $attributes['selected'] ) ) {
		$ancestors = array();
		foreach ( $attributes['selected'] as $selected ) {
			$ancestors = array_merge( $ancestors, get_ancestors( $selected, $attributes['taxonomy'] ) );
		}

		$ancestors = array_reverse( $ancestors );
		$attributes['ancestors'] = array_merge( $ancestors, $attributes['selected'] );
	}

	$field_type = ! empty( $attributes['multiple'] ) ? 'checkbox' : 'radio';	
	$field_required = false;

	$field_classes = array( 'acadp-form-control', 'acadp-form-' . $field_type );
	if ( 'radio' === $field_type && ! empty( $attributes['required'] ) ) {
		$field_required  = true;
		$field_classes[] = 'acadp-form-validate';
	}	

	$html = '';	

	// Populate
	if ( 1 === $attributes['level'] ) {
		$html .= sprintf( 
			'<acadp-dropdown-terms id="%s" class="%s" data-type="%s" data-name="%s" data-taxonomy="%s" data-required="%s">', 
			esc_attr( $attributes['id'] ),
			esc_attr( $attributes['class'] ),
			esc_attr( $field_type ),
			esc_attr( $attributes['name'] ),
			esc_attr( $attributes['taxonomy'] ),			
			esc_attr( $attributes['required'] )
		);

		// Select box
		$html .= '<div class="acadp-dropdown-input">';

		$html .= sprintf( 
			'<input type="text" class="form-control acadp-form-control acadp-form-select" placeholder="%s" readonly />', 
			esc_attr( $attributes['placeholder'] ) 
		);

		$html .= '<button type="button" class="acadp-button-clear">
			<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="16px" height="16px" stroke-width="1.5" stroke="currentColor" class="acadp-flex-shrink-0">
				<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
			</svg>
		</button>';

		$html .= '</div>';

		// Dropdown list 
		$html .= '<div class="acadp-dropdown-list" hidden>';

		// Search field
		$html .= '<div class="acadp-dropdown-search">';
		$html .= sprintf( '<input type="text" class="acadp-form-input acadp-form-search" placeholder="%s..." />', esc_attr__( 'Search', 'advanced-classifieds-and-directory-pro' ) );
		$html .= '<button type="button" class="acadp-button-reset" hidden>
			<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="16px" height="16px" stroke-width="1.5" stroke="currentColor" class="acadp-flex-shrink-0">
				<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
			</svg>
		</button>';
		$html .= '</div>';				
	}

	// Terms list
	$terms = get_terms( 
		$attributes['taxonomy'], 
		array(			
			'parent'       => $attributes['parent'], 
			'orderby'      => 'name',   
			'order'        => 'ASC',  
			'hide_empty'   => false,  
			'hierarchical' => false  
		) 
	);

	$terms_found = false;

	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) { 
		$terms_found = true;

		$html .= sprintf( 
			'<ul class="acadp-terms-group acadp-terms-group-%s" data-level="%d">', 
			( 1 === $attributes['level'] ? 'parent' : 'children' ),
			$attributes['level'] 
		);

		foreach ( $terms as $term ) { 
			$html .= '<li class="acadp-term">';

			// Label
			if ( 0 === $term->parent && $attributes['parent_disabled'] ) {
				$html .= sprintf( '<label class="acadp-term-label" style="padding-left: %dpx;" disabled>', $attributes['level'] * 12 );

				$html .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="14px" height="14px" fill="currentColor" class="acadp-flex-shrink-0">
					<path fill-rule="evenodd" d="M10.21 14.77a.75.75 0 01.02-1.06L14.168 10 10.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
					<path fill-rule="evenodd" d="M4.21 14.77a.75.75 0 01.02-1.06L8.168 10 4.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
				</svg>';

				$html .= sprintf( '<span class="acadp-term-name">%s</span>', esc_html( $term->name ) );
				$html .= '</label>';
			} else {
				$html .= sprintf( '<label class="acadp-term-label" style="padding-left: %dpx;">', $attributes['level'] * 12 );
				
				$boolean_attributes = array();			
				if ( $field_required ) $boolean_attributes[] = 'required';
				if ( in_array( $term->term_id, $attributes['selected'] ) ) $boolean_attributes[] = 'checked';

				$html .= sprintf( 
					'<input type="%s" name="%s" class="%s" value="%d" %s/>', 
					$field_type,
					esc_attr( $attributes['name'] ),
					implode( ' ', $field_classes ),
					$term->term_id,
					implode( ' ', $boolean_attributes )
				);			

				$html .= sprintf( '<span class="acadp-term-name">%s</span>', esc_html( $term->name ) );
				$html .= '</label>';
			}

			// Children
			$args = $attributes;
			$args['parent'] = $term->term_id;
			$args['level']  = $attributes['level'] + 1;

			if ( in_array( $term->term_id, $attributes['ancestors'] ) || ( $attributes['parent_disabled'] && 1 === $attributes['level'] ) ) {
				$html .= acadp_get_terms_dropdown_html( $args );
			}

            $html .= '</li>';
		}

		$html .= '</ul>';		
	}

	if ( 1 === $attributes['level'] ) {
		$html .= sprintf(
			'<div class="acadp-dropdown-search-status acadp-text-muted"%s>%s</div>',
			( ! $terms_found ? '' : ' hidden' ),
			esc_attr__( 'No results found', 'advanced-classifieds-and-directory-pro' ) 
		);

		$html .= '</div>';
		$html .= '</acadp-dropdown-terms>';
	}
	
	// Output
	return $html;
}