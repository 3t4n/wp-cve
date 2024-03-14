<?php

/**
 * Default template path.
 *
 * @since  1.0.0
 * 
 * @return string
 */
function wphr_hr_frontend_template_path() {
    return apply_filters( 'wphr_hr_frontend_template_path', 'wp-hr-frontend/' );
}

/**
 * Get settings value only for HR
 *
 * @since  1.0.0
 * 
 * @param  string  $option_name 
 * @param  boolean $setions     
 * @param  string  $default     
 * 
 * @return string|array
 */
function wphr_hr_get_settings_options( $option_name, $default = '' ) {

	$options       = get_option( 'wphr_settings_hr-frontend-page', array() );
	$option_value  = isset( $options[$option_name] ) ? $options[$option_name] : $default;
   
    return $option_value;
}

/**
 * Create a page and store the ID in an option.
 *
 * @param string $slug 
 * @param string $option 
 * @param string $page_title 
 * @param string $page_content 
 * @param int $post_parent (default: 0) Parent for the new page
 *
 * @since  1.0.0
 * 
 * @return int page ID
 */
function wphr_hr_frontend_create_page( $slug, $option = '', $page_title = '', $page_content = '', $post_parent = 0 ) {
	global $wpdb;

	$option_value = wphr_hr_get_settings_options( $option, 'hr-frontend-page' );

	if ( $option_value > 0 ) {
		$page_object = get_post( $option_value );
	
		if ( $page_object && 'page' === $page_object->post_type && ! in_array( $page_object->post_status, array( 'pending', 'trash', 'future', 'auto-draft' ) ) ) {
			// Valid page is already in place
			return $page_object->ID;
		}
	}
	
	if ( strlen( $page_content ) > 0 ) {
		// Search for an existing page with the specified page content (typically a shortcode)
		$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' ) AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
	
	} else {
	
		// Search for an existing page with the specified page slug
		$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' )  AND post_name = %s LIMIT 1;", $slug ) );
	
	}
	
	if ( $valid_page_found ) {
		if ( $option ) {
			$page          = get_option( 'wphr_settings_hr-frontend-page' );
			$page[$option] = $valid_page_found;
			
			update_option( 'wphr_settings_hr-frontend-page', $page );
		}

		return $valid_page_found;
	}

	// Search for a matching valid trashed page
	if ( strlen( $page_content ) > 0 ) {
		// Search for an existing page with the specified page content (typically a shortcode)
		$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
	} else {
		// Search for an existing page with the specified page slug
		$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_name = %s LIMIT 1;", $slug ) );
	}

	if ( $trashed_page_found ) {
		$page_id   = $trashed_page_found;
		$page_data = array(
			'ID'             => $page_id,
			'post_status'    => 'publish',
		);
	 	
	 	wp_update_post( $page_data );
	
	} else {
		$page_data = array(
			'post_status'    => 'publish',
			'post_type'      => 'page',
			'post_author'    => 1,
			'post_name'      => $slug,
			'post_title'     => $page_title,
			'post_content'   => $page_content,
			'post_parent'    => $post_parent,
			'comment_status' => 'closed'
		);
		
		$page_id = wp_insert_post( $page_data );
	}

	if ( $option ) {
		$page          = get_option( 'wphr_settings_hr-frontend-page' );
		$page[$option] = $page_id;
		
		update_option( 'wphr_settings_hr-frontend-page', $page );
	}

	return $page_id;
}

/**
 * Get other templates by passing attributes and including the file.
 *
 * @param string $template_name
 * @param array $args 
 * @param string $template_path 
 * @param string $default_path
 *
 * @since  1.0.0
 *
 * @return void
 */
function wphr_hr_frontend_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	if ( ! empty( $args ) && is_array( $args ) ) {
		extract( $args );
	}

	$located = wphr_hr_frontend_locate_template( $template_name, $template_path, $default_path );

	if ( ! file_exists( $located ) ) {
		return;
	}

	// Allow 3rd party plugin filter template file from their plugin.
	$located = apply_filters( 'wphr_hr_frontend_get_template', $located, $template_name, $args, $template_path, $default_path );

	do_action( 'wphr_hr_frontend_before_template_part', $template_name, $template_path, $located, $args );

	include( $located );

	do_action( 'wphr_hr_frontend_after_template_part', $template_name, $template_path, $located, $args );
}

/**
 * Locate a template and return the path for inclusion.
 *
 * @param string $template_name
 * @param string $template_path 
 * @param string $default_path 
 *
 * @since  1.0.0
 * 
 * @return string
 */
function wphr_hr_frontend_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
		$template_path = wphr_hr_frontend_template_path();
	}

	if ( ! $default_path ) {
		$default_path = WPHR_HR_FRONTEND_PATH . '/templates/';
	}

	// Look within passed path within the theme - this is priority.
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name
		)
	);

	// Get default template/
	if ( ! $template  ) {
		$template = $default_path . $template_name;
	}

	// Return what we found.
	return apply_filters( 'wphr_hr_frontend_locate_template', $template, $template_name, $template_path );
}

/**
 * Get Current page number for list table pagination
 *
 * @since  1.0.0 
 * 
 * @return int
 */
function wphr_hr_frontend_current_page_number() {
	global $wp_query;
    
    $query_var = isset( $wp_query->query_vars ) ? $wp_query->query_vars : [];
        
    return isset( $query_var['paged'] ) && intval( $query_var['paged'] ) ? intval( $query_var['paged'] ) : 1;
}

/**
 * Single employee url for frontend
 *
 * @param  int  employee id
 *
 * @since  1.0.0
 *
 * @return string  $url 
 */
function wphr_hr_frontend_employee_tab_url( $tab_url, $tab, $employee_id ) {
	if ( is_admin() ) {
		return $tab_url;
	}

	$args = [
		'action' => 'view',
		'id'     => $employee_id,
		'tab'    => $tab
	];

	$emp_profile_page_id = wphr_hr_get_settings_options( 'emp_profile' );

	return add_query_arg( $args, get_permalink( $emp_profile_page_id ) );
}

/**
 * Filter employee list url
 *
 * @since  1.1.10
 *
 * @param string $url
 * @param array $args
 * 
 * @return string
 */
function wphr_hr_frontend_employee_list_url( $url, $args ) {
	if ( is_admin() ) {
		return $url;
	}

	$emp_list_page = wphr_hr_get_settings_options( 'emp_list' );

	return get_permalink( $emp_list_page );
}














