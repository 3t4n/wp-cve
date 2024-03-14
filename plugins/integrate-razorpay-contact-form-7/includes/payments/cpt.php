<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Add custom post type 'cf7rzp_payments' for storing payments.
 */
add_action('init', 'cf7rzp_payments_create_cpt');
function cf7rzp_payments_create_cpt(){
	register_post_type('cf7rzp_payments', array(
		'labels'				=> array(
			'name'               => __('Razorpay Payments', 'contact-form-7'),
			'singular_name'      => __('Razorpay Payments', 'contact-form-7'),
			'add_new'            => __('Add New', 'contact-form-7'),
			'add_new_item'       => __('Add new payment', 'contact-form-7'),
			'edit_item'          => __('Edit payment', 'contact-form-7'),
			'new_item'           => __('New payment', 'contact-form-7'),
			'view_item'          => __('View payment', 'contact-form-7'),
			'search_items'       => __('Find payment', 'contact-form-7'),
			'not_found'          => __('No payments found.', 'contact-form-7'),
			'not_found_in_trash' => __('No payments found in Trash.', 'contact-form-7'),
			'parent_item_colon'  => '',
			'menu_name'          => __('Razorpay Payments', 'contact-form-7'),
		),
		'public'				=> false,
		'show_ui'				=> true,
		'show_in_menu'			=> 'wpcf7',
		'capability_type'		=> 'page',
		'supports'				=> array('custom-fields'),
		'rewrite'				=> false,
		'query_var'				=> false,
		'delete_with_user'		=> false,
		'capabilities' => array(
			'create_posts' => 'do_not_allow', // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
		  ),
		'map_meta_cap' => true, // Set to `false`, if users are not allowed to edit/delete existing posts
		//'register_meta_box_cb'	=> 'cf7rzp_payments_add_metaboxes'
	) );
}

/**
 * Register custom payment statuses.
 */
add_action('init', 'cf7rzp_register_payment_statuses');
function cf7rzp_register_payment_statuses() {
	$payment_statuses = cf7rzp_get_payment_statuses();

	foreach ($payment_statuses as $payment_status => $values) {
		$values = array_merge(
			array(
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true
			),
			$values
		);

		register_post_status( $payment_status, $values );
	}
}

/**
 * Get custom post statuses, used for payment status.
 */
function cf7rzp_get_payment_statuses() {
	$payment_statuses = array(
		'cf7rzp_pending'	=> array(
			'label'				=> __('Pending', 'contact-form-7'),
			'label_count'		=> _n_noop('Pending <span class="count">(%s)</span>', 'Pending <span class="count">(%s)</span>', 'contact-form-7')
		),
		'cf7rzp_success'	=> array(
			'label'				=> __('Success', 'contact-form-7'),
			'label_count'		=> _n_noop('Success <span class="count">(%s)</span>', 'Success <span class="count">(%s)</span>', 'contact-form-7')
		),
		'cf7rzp_failure'	=> array(
			'label'				=> __('Failure', 'contact-form-7'),
			'label_count'		=> _n_noop('Failure <span class="count">(%s)</span>', 'Failure <span class="count">(%s)</span>', 'contact-form-7')
		)
	);

	return $payment_statuses;
}

/**
 * Get payment status label.
 */
function cf7rzp_get_payment_status_label($status) {
	$payment_statuses = cf7rzp_get_payment_statuses();
	return array_key_exists($status, $payment_statuses) ? $payment_statuses[$status]['label'] : $status;
}



/**
 * Add the custom columns for cf7rzp_payments post type
 */
add_filter( 'manage_cf7rzp_payments_posts_columns', 'cf7rzp_custom_edit_payments_columns' );
function cf7rzp_custom_edit_payments_columns($columns) {
    unset($columns['title']);
    unset($columns['date']);

	$columns['order_id'] = 'Order #';
	$columns['item_price'] = 'Price (INR)';
	$columns['status'] = 'Status';
	$columns['form_name'] = 'Form Name';
	$columns['created_at'] = 'Created At';
	$columns['action'] = 'Action';

    return $columns;
}

/**
 * Add the data to the custom columns
 */
add_action( 'manage_cf7rzp_payments_posts_custom_column' , 'cf7rzp_custom_edit_payments_columns_data', 99, 2 );
function cf7rzp_custom_edit_payments_columns_data( $column, $post_id ) {
	switch ($column) {

		case 'order_id': 
			echo esc_html(get_post_meta($post_id, 'cf7rzp_order_id', true));
			break;
		case 'item_price': 
			echo esc_html(get_post_meta($post_id, 'item_price', true));
			break;
		case 'status': 
			$status = get_post_status($post_id);
			echo "<span class=".esc_attr($status).">".esc_html(cf7rzp_get_payment_status_label($status))."</span>";
			break;		
		case 'form_name': 
			$cf7_id = get_post_meta($post_id, 'cf7_id', true);
			echo get_the_title($cf7_id);
			break;	
		case 'created_at':
			//echo get_the_date( 'F j, Y', $post_id);
			$dt = get_the_date( 'Y-m-d H:i:s', $post_id);
			$dt = new DateTime($dt, new DateTimeZone('UTC'));
			$dt->setTimezone(new DateTimeZone('Asia/Kolkata'));
			echo esc_html($dt->format('F j, Y | h:i:s a'));
			break;
		case 'action': 
			echo '<a href="#" onclick="cf7rzp_getPaymentMoreInfo(event,'.esc_attr($post_id).')"><strong>View</strong></a>';
			if( function_exists('cf7rzppa_admin_payments_additional_actions') ) {
				do_action("cf7rzp_admin_payments_additional_actions", $post_id);		
			}
			break;	
		case 'item_price_type': 
			if( function_exists('cf7rzppa_admin_payments_price_type') ) {
				do_action("cf7rzp_admin_payments_price_type", $post_id);		
			}
			break;	
	}
}


/**
 * Add custom filters - Contact Form
 */
add_action('restrict_manage_posts', 'cf7rzp_filters', 10, 2);
function cf7rzp_filters($post_type, $which) {
	global $wpdb;
	// Apply this only on a 'cf7rzp_payments' post type
	if ($post_type != 'cf7rzp_payments') return;

	// Display Contact form filter HTML
	$cf7_posts = $wpdb->get_results(
		"
			SELECT ID,post_title FROM $wpdb->posts
			WHERE post_type = 'wpcf7_contact_form'
		"
	);

	$selected_form = isset($_GET['cf7rzp_form']) ? sanitize_text_field($_GET['cf7rzp_form']) : '';
	print(
		'<select name="cf7rzp_form" id="cf7rzp_form" class="postform">
			<option value="">Show all Contact Forms</option>'
	);
	foreach($cf7_posts as $cf7){
		printf(
			'<option value="%u"%s>%s</option>',
		$cf7->ID,	
		$selected_form == $cf7->ID ? ' selected="selected"' : '',
		$cf7->post_title
		);
	}
	print('</select>');
}

/**
 * This hook will alter the main query according to the selection of cf7
 */
add_action('parse_query', 'cf7rzp_payments_filter_parse_query');
function cf7rzp_payments_filter_parse_query($query) {
    global $pagenow;

    if ($pagenow != 'edit.php' || !isset($_GET['post_type']) || $_GET['post_type'] != 'cf7rzp_payments' || !isset($_GET['filter_action']) || $_GET['filter_action'] != 'Filter') return;
    
    $meta_query = array();

    if ( !empty($_GET['cf7rzp_form']) ) {
    	$meta_query[] = array(
        	'key'     => 'cf7_id',
        	'value'   => sanitize_text_field($_GET['cf7rzp_form']),
        	'compare' => '='
        );
    }

    if ( !empty($meta_query) ) {
	    $query->set('meta_query', $meta_query);
	}
}


/**
 * Remove link "Mine" on payments edit page
 */
add_filter('views_edit-cf7rzp_payments', 'cf7rzp_views_remove_mine');
function cf7rzp_views_remove_mine($views) {
	unset($views['mine']);
	return $views;
}

/**
 * Remove post row actions.
 */
add_filter('post_row_actions', 'cf7rzp_remove_payments_quick_edit', 10, 2);
function cf7rzp_remove_payments_quick_edit($actions, $post) {
	if ($post->post_type != 'cf7rzp_payments') return $actions;

    return array();
}

/**
 * Remove Bulk actions.
 */
add_filter ('bulk_actions-edit-cf7rzp_payments', 'cf7rzp_remove_bulk_action' );
function cf7rzp_remove_bulk_action ($actions) {
	// remove all actions
	return array();
}

