<?php
/*
	Plugin Name: Easy Pricing Tables: Free
	Plugin URI: https://fatcatapps.com/easypricingtables
	Description: Create a Beautiful, Responsive and Highly Converting Pricing Table in Less Than 5 Minutes with Easy Pricing Tables for WordPress. No Coding Required.
	Text Domain: easy-pricing-tables
	Domain Path: /languages
	Author: Fatcat Apps
	Version: 3.2.5
	Author URI: https://fatcatapps.com
*/


// DO NOT EDIT THIS LINE -> WILL GET FILTERED BY BUILD SCRIPT, NEEDED FOR LICENSING 

if( !defined( 'PTP_PLUGIN_PATH' ) ){
	
	define( 'DH_PTP_LICENSE_PACKAGE', 'Free' );
	define( 'PTP_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
	define( 'PTP_PLUGIN_PATH_FOR_SUBDIRS', plugins_url(str_replace(dirname(dirname(__FILE__)), '', dirname(__FILE__))));
	define( 'PTP_PLUGIN_FILE', __FILE__);
	define( 'PTP_PLUGIN_URL', plugins_url( '', __FILE__ ));
	define( 'PTP_DEBUG', FALSE );

	if ( PTP_DEBUG ){
		define( 'PTP_PLUGIN_VER', '3.2.' . time() );
	} else {
		define( 'PTP_PLUGIN_VER', '3.2.5' );
	}

	include ( PTP_PLUGIN_PATH . 'includes/ept-block.php' );	
	
	if( file_exists( PTP_PLUGIN_PATH . 'assets/pricing-tables/font-awesome/font-awesome-icons.php' ) ) {
		include ( PTP_PLUGIN_PATH . 'assets/pricing-tables/font-awesome/font-awesome-icons.php' );
	}
	
	if( file_exists( PTP_PLUGIN_PATH . 'includes/licensing/licensing.php' ) ) {
		include ( PTP_PLUGIN_PATH . 'includes/licensing/licensing.php' );
	}
	if( file_exists( PTP_PLUGIN_PATH . 'includes/settings.php' ) ) {
		include ( PTP_PLUGIN_PATH . 'includes/settings.php' );
	}
	if( file_exists( PTP_PLUGIN_PATH . 'includes/notices/notices.php' ) ) {
		include ( PTP_PLUGIN_PATH . 'includes/notices/notices.php' );
	}
	
	if( file_exists( PTP_PLUGIN_PATH . 'assets/blocks/toggle/fca-ept-toggle.php' ) ) {
		include ( PTP_PLUGIN_PATH . 'assets/blocks/toggle/fca-ept-toggle.php' );
	}
	
	if( file_exists( PTP_PLUGIN_PATH . 'includes/toggle.php' ) ) {
		include ( PTP_PLUGIN_PATH . 'includes/toggle.php' );
	}
	
	if ( !function_exists( 'is_plugin_active' ) ){
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}
	
	if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && file_exists( PTP_PLUGIN_PATH . 'includes/integrations/woocommerce/woo.php' ) ) {
		include ( PTP_PLUGIN_PATH . 'includes/integrations/woocommerce/woo.php' );
	}
	
	$show_legacy_tables = get_option( 'dh_ptp_show_legacy_tables', null );
	if( $show_legacy_tables === null ) {
		
		$existing_posts = get_posts( array(
			'post_type' => 'easy-pricing-table',
			'numberposts' => '1'
        ) );
		
		if( $existing_posts ) {
			$show_legacy_tables = true;
			update_option( 'dh_ptp_show_legacy_tables', true );
		}
	}
	
	// only if legacy tables are available, include the rest
	if( $show_legacy_tables ){

		// Include required for dh_ptp_generate_pricing_table()
		include ( PTP_PLUGIN_PATH . 'includes/table-generation/table-generator.php');
		include ( PTP_PLUGIN_PATH . 'includes/post-types.php' );
		include ( PTP_PLUGIN_PATH . 'includes/shortcodes.php' );
		include ( PTP_PLUGIN_PATH . 'assets/blocks/legacy/block.php' );
		if( !class_exists( 'WPAlchemy_MetaBox' ) ){
			include_once ( PTP_PLUGIN_PATH . 'includes/wpalchemy/MetaBox.php' );
		}

		include_once ( PTP_PLUGIN_PATH . 'includes/metaboxes/spec.php' );

		if( is_admin() ){
			// include WPAlchemy scripts
			include_once ( PTP_PLUGIN_PATH . 'includes/metaboxes/setup.php' );
		}
				
		//INCLUDE INTEGRATIONS
		if ( is_plugin_active( 'easy-digital-downloads/easy-digital-downloads.php' ) && file_exists( PTP_PLUGIN_PATH . 'includes/integrations/edd/edd.php' ) ){
			include ( PTP_PLUGIN_PATH . 'includes/integrations/edd/edd.php' );
		}
		
		if ( is_plugin_active( 'wp-simple-pay-pro-for-stripe/stripe-checkout-pro.php' ) && file_exists( PTP_PLUGIN_PATH . 'includes/integrations/simplepaypro/scp.php' ) ){
			$path = WP_PLUGIN_DIR . '/wp-simple-pay-pro-for-stripe/stripe-checkout-pro.php';
			$scp_plugin_meta = get_plugin_data( $path );

			if ( version_compare( $scp_plugin_meta['Version'], '2.4.1' ) >= 0 ){
				include ( PTP_PLUGIN_PATH . 'includes/integrations/simplepaypro/scp.php' );
			}
		}

		if ( is_plugin_active( 'wp-simple-pay-pro-for-stripe-subscriptions-add-on/stripe-subscriptions.php' ) && file_exists( PTP_PLUGIN_PATH . 'includes/integrations/simplepaypro/scp.php' ) ) {
			$path = WP_PLUGIN_DIR . '/wp-simple-pay-pro-for-stripe-subscriptions-add-on/stripe-subscriptions.php';
			$scp_plugin_meta = get_plugin_data( $path );

			if ( version_compare( $scp_plugin_meta['Version'], '1.3.0' ) >= 0 && file_exists( PTP_PLUGIN_PATH . 'includes/integrations/simplepaypro/scp.php' ) ){
				define( 'PTP_SCP_SUBS_ACTIVE', TRUE );
			}
		}

		if ( is_plugin_active( 'wp-simple-pay-pro-3/simple-pay.php' ) && file_exists( PTP_PLUGIN_PATH . 'includes/integrations/simplepay3/simplepay.php' ) ){
			include ( PTP_PLUGIN_PATH . 'includes/integrations/simplepay3/simplepay.php' );
		}
	}
	
	function dh_ptp_register_pricing_table_post_type() {

		$labels = array(
			'name' => __('Pricing Tables', 'easy-pricing-tables'),
			'singular_name' => __('Pricing Table', 'easy-pricing-tables'),
			'add_new' => __('Add New', 'easy-pricing-tables'),
			'add_new_item' => __('Add New Pricing Table', 'easy-pricing-tables'),
			'edit_item' => __('Edit Pricing Table', 'easy-pricing-tables'), 
			'new_item' => __('New Pricing Table', 'easy-pricing-tables'),
			'all_items' => __('Legacy Pricing Tables', 'easy-pricing-tables'),
			'view_item' => __('View Pricing Table', 'easy-pricing-tables'),
			'search_items' => __('Search Pricing Tables', 'easy-pricing-tables'),
			'not_found' =>  __('No Pricing Tables found', 'easy-pricing-tables'),
			'not_found_in_trash' => __('No Pricing Tables found in Trash', 'easy-pricing-tables'),
			'parent_item_colon' => '',
			'menu_name' => __('Pricing Tables', 'easy-pricing-tables')
		);

		$args = array(
			'labels' => $labels,
			'public' => false,
			'exclude_from_search' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'show_in_menu' => true, 
			'query_var' => true,
			'rewrite' => array( 'slug' => 'pricing-table' ),
			'capability_type' => 'post',
			'has_archive' => false, 
			'hierarchical' => false,
			'menu_position' => 104,
			'menu_icon' => PTP_PLUGIN_PATH_FOR_SUBDIRS.'/assets/ept-icon-16x16.png',
			'supports' => array( 'title', 'revisions' ),
		); 

		register_post_type( 'easy-pricing-table', $args);

	}
	add_action( 'init', 'dh_ptp_register_pricing_table_post_type' );
	
	// Localization
	function fca_eoi_load_localization_easy_pricing_tables(){

		$locale = apply_filters( 'plugin_locale', get_locale(), 'easy-pricing-tables' );

		load_textdomain( 'easy-pricing-tables', trailingslashit( WP_LANG_DIR ) . 'easy-pricing-tables' . '/' . 'easy-pricing-tables' . '-' . $locale . '.mo' );

		load_plugin_textdomain( 'easy-pricing-tables', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );

	}
	
	add_action( 'plugins_loaded', 'fca_eoi_load_localization_easy_pricing_tables' );
	
	// Add settings link on plugin page
	function dh_ptp_plugin_settings_link( $links ){
		// Remove Edit link
		unset( $links['edit'] );
		
		$add_new_link = '<a href=' . admin_url( 'admin.php?page=ept3-list&add_new=1' ) . '>' . __('Add New', 'easy-pricing-tables') . '</a>'; 
		$forum_link   = '<a href="http://wordpress.org/support/plugin/easy-pricing-tables">' . __('Support', 'easy-pricing-tables' ) . '</a>';
		$support_link = '<a href="https://fatcatapps.com/support" target="_blank">' . esc_attr__( 'Support', 'easy-pricing-tables' ) . '</a>';
		$premium_link = '<a href="https://fatcatapps.com/easypricingtables/?utm_campaign=Purchase%2BPremium%2Bin%2Bplugins.php&utm_source=Easy%2BPricing%2BTables%2BFree&utm_medium=plugin&utm_content=v1">' . __('Purchase Premium',  'easy-pricing-tables' ) . '</a>';
		
		array_unshift( $links, $add_new_link );
		
		if ( DH_PTP_LICENSE_PACKAGE === 'Free') {
			array_push( $links, $forum_link );
			array_push( $links, $premium_link );
			
		} else {
			array_push( $links, $support_link );
		}

		return $links; 
	}
	$plugin = plugin_basename(__FILE__); 
	add_filter("plugin_action_links_$plugin", 'dh_ptp_plugin_settings_link' );
	
}
