<?php
if( ! function_exists( 'get_plugin_data' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if( ! function_exists( 'rew_pri' ) ) :
function rew_pri( $data ) {
	echo '<pre>';
	if( is_object( $data ) || is_array( $data ) ) {
		print_r( $data );
	}
	else {
		var_dump( $data );
	}
	echo '</pre>';
}
endif;

if( ! function_exists( 'rew_has_pro' ) ) :
function rew_has_pro() {
	return apply_filters( 'rew_has_pro', false );
}
endif;

if( ! function_exists( 'rew_get_option' ) ) :
function rew_get_option( $key, $section, $default = '' ) {

	$options = get_option( $key );

	if ( isset( $options[ $section ] ) ) {
		return $options[ $section ];
	}

	return $default;
}
endif;

if( ! function_exists( 'rew_get_template' ) ) :
/**
 * Includes a template file resides in /views diretory
 *
 * It'll look into /restrict-elementor-widgets directory of your active theme
 * first. if not found, default template will be used.
 * can be overriden with restrict-elementor-widgets_template_override_dir hook
 *
 * @param string $slug slug of template. Ex: template-slug.php
 * @param string $sub_dir sub-directory under base directory
 * @param array $fields fields of the form
 */
function rew_get_template( $slug, $base = 'views', $args = null ) {

	// templates can be placed in this directory
	$override_template_dir = apply_filters( 'rew_template_override_dir', get_stylesheet_directory() . '/restrict-elementor-widgets/', $slug, $base, $args );
	
	// default template directory
	$plugin_template_dir = dirname( REW ) . "/{$base}/";

	// full path of a template file in plugin directory
	$plugin_template_path =  $plugin_template_dir . $slug . '.php';
	
	// full path of a template file in override directory
	$override_template_path =  $override_template_dir . $slug . '.php';

	// if template is found in override directory
	if( file_exists( $override_template_path ) ) {
		ob_start();
		include $override_template_path;
		return ob_get_clean();
	}
	// otherwise use default one
	elseif ( file_exists( $plugin_template_path ) ) {
		ob_start();
		include $plugin_template_path;
		return ob_get_clean();
	}
	else {
		return __( 'Template not found!', 'restrict-elementor-widgets' );
	}
}
endif;

/**
 * Generates some action links of a plugin
 *
 * @since 1.0
 */
if( ! function_exists( 'rew_action_link' ) ) :
function rew_action_link( $plugin, $action = '' ) {

	$exploded	= explode( '/', $plugin );
	$slug		= $exploded[0];

	$links = [
		'install'		=> wp_nonce_url( admin_url( "update.php?action=install-plugin&plugin={$slug}" ), "install-plugin_{$slug}" ),
		'update'		=> wp_nonce_url( admin_url( "update.php?action=upgrade-plugin&plugin={$plugin}" ), "upgrade-plugin_{$plugin}" ),
		'activate'		=> wp_nonce_url( admin_url( "plugins.php?action=activate&plugin={$plugin}&plugin_status=all&paged=1&s" ), "activate-plugin_{$plugin}" ),
		'deactivate'	=> wp_nonce_url( admin_url( "plugins.php?action=deactivate&plugin={$plugin}&plugin_status=all&paged=1&s" ), "deactivate-plugin_{$plugin}" ),
	];

	if( $action != '' && array_key_exists( $action, $links ) ) return $links[ $action ];

	return $links;
}
endif;


/**
 * Renders the output based on settings
 *
 * @since 1.0
 *
 * @return string
 */
if( ! function_exists( 'rew_render_message' ) ) :
function rew_render_message( $settings, $echo = true ) {

	$message = '';

	if( !isset( $settings['rew_show_message'] ) || $settings['rew_show_message'] == 'text' ) {;

		$message = isset( $settings['rew_message_text'] ) ? $settings['rew_message_text'] : '';

	}

	if( !isset( $settings['rew_show_message'] ) || $settings['rew_show_message'] == 'template' ) {
		if ( isset( $settings['rew_message_template'] ) && $settings['rew_message_template'] != '' ) {
			$elementor_instance = \Elementor\Plugin::instance();
			$message = $elementor_instance->frontend->get_builder_content_for_display( $settings['rew_message_template']  );
		}
	}

	if ( $echo ) {
		echo $message;
		return;
	}

	return $message;
}
endif;

/**
 * List of conditions
 *
 * @since 1.0
 *
 * @return []
 */
if( ! function_exists( 'rew_show_content_to' ) ) :
function rew_show_content_to() {

	$rules = [
		'loggedin'  => __( 'Logged In Users', 'restrict-elementor-widgets' ),
		'loggedout' => __( 'Logged Out Users', 'restrict-elementor-widgets' ),
		'role-wise' => __( 'Specific Roles', 'restrict-elementor-widgets' ),
		'user-wise' => __( 'Specific Users', 'restrict-elementor-widgets' ),
		'date-time' => __( 'Date Time', 'restrict-elementor-widgets' ),
		'query-string' => __( 'Query String', 'restrict-elementor-widgets' )
	];

	return apply_filters( 'rew_show_content_to', $rules );
}
endif;

/**
 * List of user roles
 *
 * @since 1.0
 *
 * @return []
 */
if( ! function_exists( 'rew_show_content_to_roles' ) ) :
function rew_show_content_to_roles() {

	$user_roles = [
		'subscriber'  	=> __( 'Subscriber', 'restrict-elementor-widgets' ),
		'contributor' 	=> __( 'Contributor', 'restrict-elementor-widgets' ),
		'author' 		=> __( 'Author', 'restrict-elementor-widgets' ),
		'editor' 		=> __( 'Editor', 'restrict-elementor-widgets' ),
		'administrator' => __( 'Administrator', 'restrict-elementor-widgets' ),
	];

	return apply_filters( 'rew_user_roles_to_show_content', $user_roles );
}
endif;

/**
 * Checks if a user has a role from a list
 *
 * @since 1.0
 *
 * @return bool
 */
if( ! function_exists( 'rew_user_has_role' ) ) :
function rew_user_has_role( $roles ) {

	$has_role = false;

	$user = wp_get_current_user();

	if ( count( $roles ) > 0 ) {
		$has_role = count( array_intersect( $roles, (array) $user->roles ) ) > 0;
	}

	return apply_filters( 'rew_user_has_role', $has_role );
}
endif;

/**
 * Checks if a user ID matches
 *
 * @since 1.0
 *
 * @return bool
 */
if( ! function_exists( 'rew_user_has_id' ) ) :
function rew_user_has_id( $ids ) {

	$has_id = false;
	
	if ( $ids != '' ) {

		$ids  = str_replace( ' ', '', $ids );
		$_ids = explode( ',', $ids );

		$has_id = in_array( get_current_user_id(), $_ids );
	}

	return apply_filters( 'rew_user_has_id', $has_id );
}
endif;

/**
 * Is a user eligible to see a widget?
 *
 * @since 1.0
 *
 * @return bool
 */
if( ! function_exists( 'rew_is_eligible_now' ) ) :
function rew_is_eligible_now( $specific_time, $time_list ) {

	$is_eligible 	= true;
	if ( isset( $specific_time ) && $specific_time == 'yes' ) {
		$is_eligible 	= false;
		if ( isset( $time_list ) && count( $time_list ) > 0 ) {
			foreach ( $time_list as $time_plot ) {
				if ( isset( $time_plot['rew_due_time_from'] ) && isset( $time_plot['rew_due_time_to'] ) ) {				
					if( current_time( 'timestamp' ) >= strtotime( $time_plot['rew_due_time_from'] ) &&
						current_time( 'timestamp' ) <= strtotime( $time_plot['rew_due_time_to'] ) ){
						return true;						    
					}
				}
			} //foreach
		}
	}

	return $is_eligible;
}
endif;

/**
 * Is a user eligible to see a widget?
 *
 * @since 1.0
 *
 * @return bool
 */
if( ! function_exists( 'rew_is_eligible_today' ) ) :
function rew_is_eligible_today( $dates ) {
	$today = date('Y-m-d', current_time( 'timestamp' ) );

	foreach ( $dates as $date ) {
		if ( isset( $date['rew_dates'] ) && $today == $date['rew_dates'] ) {
			return true;
		}
	}

	return false;
}
endif;

/**
 * Is a user eligible to see a widget?
 *
 * @since 1.0
 *
 * @return bool
 */
if( ! function_exists( 'rew_is_time_eligible' ) ) :
function rew_is_time_eligible( $settings )	{

	extract( $settings );	
	$is_eligible 	= false;
	$is_eligible_now = rew_is_eligible_now( $rewdt_specific_time, $rew_time_list );
					
	if ( isset( $rew_date_time_type ) ) {
		if ( $rew_date_time_type == 'daily' && $is_eligible_now ) {
			$is_eligible 	= true;
		}
		elseif ( $rew_date_time_type == 'date' && isset( $rew_date_list ) && count( $rew_date_list ) > 0 && $is_eligible_now ) {
			$is_eligible 	= rew_is_eligible_today( $rew_date_list );
		}
		elseif ( $rew_date_time_type == 'day' && isset( $rew_day_list ) && count( $rew_day_list ) > 0 ) {
			$today = strtolower( date('D') );
			if ( in_array( $today, $rew_day_list ) && $is_eligible_now ) {
				$is_eligible 	= true;
			}
		}
	}

	return $is_eligible;		
}
endif;

/**
 * Is a user eligible to see a widget?
 *
 * @since 1.0
 *
 * @return bool
 */
if( ! function_exists( 'rew_has_query' ) ) :
function rew_has_query( $settings )	{

	extract( $settings );
	$is_eligible = false;

	global $wp_query;
	$query_string = $wp_query->query_vars + $_GET;

	$relation = isset( $rew_query_relation ) ? $rew_query_relation : 'OR';
	$query_matched = 0;
	foreach ( $rew_query_list as $query ) {
		if ( array_key_exists( $query['rew_query_key'], $query_string ) && 
			( $query_string[ $query['rew_query_key'] ] == $query['rew_query_value'] || $query['rew_query_value'] == '' ) ) {
			$query_matched ++;
		}
	}

	if ( ( $relation == 'AND' && $query_matched == count( $rew_query_list ) ) ||  ( $relation == 'OR' && $query_matched > 0 ) ) {
		$is_eligible = true;
	}
	
	return $is_eligible;		
}
endif;

/**
 * Is a user eligible to see a widget?
 *
 * @since 1.0
 *
 * @return bool
 */
if( ! function_exists( 'rew_is_eligible' ) ) :
function rew_is_eligible( $settings ) {

	extract( $settings );

	$is_eligible = false;

	if ( isset( $rew_show_content_to ) && count( $rew_show_content_to ) > 0 ) {
		if ( 
			( in_array( 'loggedin', $rew_show_content_to ) && is_user_logged_in() ) ||
			( in_array( 'loggedout', $rew_show_content_to ) && !is_user_logged_in() ) ||
			( in_array( 'role-wise', $rew_show_content_to ) && isset( $rew_show_content_to_roles ) && rew_user_has_role( $rew_show_content_to_roles ) )|| 
			( in_array( 'user-wise', $rew_show_content_to ) && isset( $rew_user_ids ) && rew_user_has_id( $rew_user_ids ) ) ||
			( in_array( 'date-time', $rew_show_content_to ) && rew_is_time_eligible( $settings ) ) ||
			( in_array( 'query-string', $rew_show_content_to ) && rew_has_query( $settings ) ) 
		) {
			$is_eligible = true;
		}
	}

	return apply_filters( 'rew_is_eligible', $is_eligible, $settings );
}
endif;

/**
 * list of templates
 *
 * @since 1.0
 *
 * @return []
 */
if( ! function_exists( 'rew_get_message_templates' ) ) :
function rew_get_message_templates() {

	$args = [  
	    'post_type' 	 => 'elementor_library',
	    'post_status' 	 => 'publish',
	    'posts_per_page' => -1, 
	    'order' 		 => 'DESC',
	    'meta_query' 	 => [
	    	'relation' 	 => 'AND',
			[
				'key' 		=> '_elementor_template_type',
				'value' 	=> 'section',
			]
	    ]
	];

	$result = new \WP_Query( $args ); 
	$_tabs 	= $result->posts;

	$tabs = [];
	foreach ( $_tabs as $tab ) {
        $tabs[ $tab->ID ] = $tab->post_title;
    }   

    return $tabs;
}
endif;


/**
 * List of conditions
 *
 * @since 1.0
 *
 * @return []
 */
if( ! function_exists( 'rew_pro_text' ) ) :
function rew_pro_text() {

	$text = "<span class='rew-pro-text'> (PRO)</span>";

	return $text;
}
endif;

/**
 * List of available extensions
 *
 * @since 1.0
 *
 * @return []
 */
if( ! function_exists( 'rew_get_extensions' ) ) :
function rew_get_extensions() {

	$product_page	= 'https://codexpert.io/product/restrict-elementor-widgets';
	$utm			= [ 'utm_source' => 'dashboard', 'utm_medium' => 'settings', 'utm_campaign' => 'extensions' ];

	$extensions = apply_filters( 'rew_get_extensions', [
		'rew-wc'	=> [
			'title'		=> __( 'WooCommerce', 'restrict-elementor-widgets' ),
			'desc'		=> __( 'Show a widget to WooCommerce customers only! You can hide any widgets from the users that didn\'t place an order on your store yet!', 'restrict-elementor-widgets' ),
			'url'		=> add_query_arg( $utm, "{$product_page}/rew-wc/" ),
			'button'	=> sprintf( __( 'Purchase %s', 'restrict-elementor-widgets' ), '$14.99' ),
		],
		'rew-wcs'	=> [
			'title'		=> __( 'WooCommerce Subscriptions', 'restrict-elementor-widgets' ),
			'desc'		=> __( 'Show a widget to WooCommerce customers only! You can hide any widgets from the users that didn\'t place an order on your store yet!', 'restrict-elementor-widgets' ),
			'url'		=> add_query_arg( $utm, "{$product_page}/rew-wcs/" ),
			'button'	=> sprintf( __( 'Purchase %s', 'restrict-elementor-widgets' ), '$14.99' ),
		],
	] );

    return $extensions;
}
endif;