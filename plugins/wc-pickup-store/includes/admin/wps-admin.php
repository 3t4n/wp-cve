<?php
/**
 * Customize Options
 */
function wps_store_customize_options( $wp_customize ) {
	$stores = wps_store_get_store_admin();
	$label = apply_filters( 'wps_store_select_first_option', __( 'Select a store', 'wc-pickup-store' ) );
	
	if ( !empty( $label ) ) {
		array_unshift($stores, sprintf( __('Label: %s', 'wc-pickup-store'), $label ) );
	}
	array_unshift($stores, __('None', 'wc-pickup-store'));
	$wp_customize->add_section('wps_store_customize_section', array(
		'title' => __('WC Pickup Store', 'wc-pickup-store'),
		'priority' => 1,
		'capability' => 'edit_theme_options',
		'description' => __('Default store', 'wc-pickup-store'),
	));

	$wp_customize->add_setting('wps_store_default', array(
		'default' => '',
		'capability' => 'edit_theme_options',
		'type' => 'theme_mod',
		'transport' => 'refresh',
	));

	$wp_customize->add_control( new WP_Customize_Control($wp_customize, 'wps_store_default', array(
		'label'    => __('Stores', 'wc-pickup-store'),
		'type' => 'select',
		'choices' =>  $stores,
		'settings' => 'wps_store_default',
		'description' => __('Choose a default store', 'wc-pickup-store'),
		'section'  => 'wps_store_customize_section',
	)));
}
add_action('customize_register', 'wps_store_customize_options');

/**
 * Get Stores in admin customizer
 */
function wps_store_get_store_admin($return_id = false, $args = array(), $array_keys = false) {
	$stores = array();
	$defaults = array(
		'post_type' => 'store',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'orderby' => WPS()->stores_order_by,
		'order' => WPS()->stores_order,
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key' => '_exclude_store',
				'compare' => 'EXISTS',
			),
			array(
				'key' => '_exclude_store',
				'value' => '0',
			)
		),
	);
	$args = apply_filters('wps_store_query_args', wp_parse_args($args, $defaults));
		
	$query = new WP_Query($args);

	if($query->have_posts()) {
		while ($query->have_posts()) : $query->the_post();
			if ($array_keys) {
				$stores[] = array(
					'id' => get_the_ID(),
					'name' => $query->post->post_title
				);
			} else {
				if(!$return_id) {
					$stores[$query->post->post_title] = $query->post->post_title;
				} else {
					$stores[get_the_ID()] = $query->post->post_title;
				}
			}
		endwhile;
		wp_reset_postdata();
	}

	return $stores;
}

/**
 * Save the custom field.
 * 
 * @version 1.8.6
 * @since 1.x
 * 
 * @param WC_Order $order
 */
function wps_store_save_order_meta( $order ) {
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;

	$store = isset( $_POST['shipping_pickup_stores'] ) ? $_POST['shipping_pickup_stores'] : '';

	if ( $store ) {
		update_user_meta( $user_id, '_shipping_pickup_stores', $store );
		$order->add_meta_data( '_shipping_pickup_stores', $store );
		// $order->save() is triggered from parent create_order method
	}
}
add_action( 'woocommerce_checkout_create_order', 'wps_store_save_order_meta' );

/**
 * Add Settings action links
 */
function wps_store_links($links) {
	$id = "wc_pickup_store";

	$plugin_links = array(
		'<a href="' . admin_url('admin.php?page=wc-settings&tab=shipping&section=' . $id) . '">' . __('Settings', 'wc-pickup-store') . '</a>',
	);

	// Merge our new link with the default ones
	return array_merge($plugin_links, $links);
}
add_filter('plugin_action_links_' . WPS_PLUGIN_FILE, 'wps_store_links');

/**
 * Add Settings links to Store menu
 */
function wps_store_admin_submenu() {
	$id = "wc_pickup_store";
	add_submenu_page(
		'edit.php?post_type=store', __('Settings', 'wc-pickup-store'), __('Settings', 'wc-pickup-store'), 'edit_posts', 'admin.php?page=wc-settings&tab=shipping&section=' . $id
	);
}
add_action('admin_menu', 'wps_store_admin_submenu');

/**
 * Check store field before allow checkout to proceed.
 * 
 * @version 1.8.5
 * @since 1.5.x
 */
function wps_store_validate_checkout( $data ) {
	if ( is_array( $data['shipping_method'] ) && in_array( 'wc_pickup_store', $data['shipping_method'] ) ) {
		if ( isset( $_POST['shipping_pickup_stores'] ) ) {
			if ( empty( $_POST['shipping_pickup_stores'] ) ) {
				$notice_error = apply_filters( 'wps_notice_store_validation', __( 'You must either choose a store or use other shipping method', 'wc-pickup-store' ) );
				wc_add_notice( $notice_error, 'error' );
			}
		}
	
		if ( count( wps_store_get_store_admin() ) == 0 ) {
			wc_add_notice( wps_no_stores_availables_message(), 'error' );
		}
	}
}
add_action('woocommerce_after_checkout_validation', 'wps_store_validate_checkout', 10, 2);

/**
 * Add selected store to billing details, admin page
 * 
 * @version 1.8.6
 * @since 1.x
 * 
 * @param WC_Order $order
 */
function wps_show_store_in_admin( $order ) {
	$store = $order->get_meta( '_shipping_pickup_stores' );
	
	if ( !empty( $store ) ) {
		?>
		<p>
			<strong class="title"><?php echo __('Pickup Store', 'wc-pickup-store') . ':' ?></strong>
			<span class="data"><?= $store ?></span>
		</p>
		<?php
	}
}
add_action('woocommerce_admin_order_data_after_billing_address', 'wps_show_store_in_admin');

/**
 * Language
 */
function wps_store_language_init() {
	load_plugin_textdomain('wc-pickup-store', false, dirname( WPS_PLUGIN_FILE ) . '/languages/');
}
add_action('plugins_loaded', 'wps_store_language_init');

/**
 * Get waze icon from plugin
 */
function wps_store_get_waze_icon($width = '') {
	$attr = !empty($width) ? 'width="' . $width . '"' : '';
	return '<img src="' . WPS_PLUGIN_DIR_URL . 'assets/images/icon_waze.svg' . '" ' . $attr . ' />';
}

/**
 * No stores message
 */
function wps_no_stores_availables_message() {
	return apply_filters('wps_no_stores_availables_message', __('There are not available stores. Please choose another shipping method.', 'wc-pickup-store'));
}