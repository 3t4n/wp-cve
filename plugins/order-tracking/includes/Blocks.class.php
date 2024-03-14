<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'ewdotpBlocks' ) ) {
/**
 * Class to handle plugin Gutenberg blocks
 *
 * @since 3.0.0
 */
class ewdotpBlocks {

	public function __construct() {

		add_action( 'init', array( $this, 'add_blocks' ) );
		
		add_filter( 'block_categories_all', array( $this, 'add_block_category' ) );
	}

	/**
	 * Add the Gutenberg block to the list of available blocks
	 * @since 3.0.0
	 */
	public function add_blocks() {

		if ( ! function_exists( 'render_block_core_block' ) ) { return; }

		$this->enqueue_assets();   

		$args = array(
			'attributes' => array(
				'show_orders' => array(
					'type' => 'string',
				),
			),
			'render_callback' 	=> 'ewd_otp_tracking_form_shortcode',
		);

		register_block_type( 'order-tracking/ewd-otp-display-tracking-form-block', $args );

		$args = array(
			'render_callback' 	=> 'ewd_otp_customer_form_shortcode',
		);

		register_block_type( 'order-tracking/ewd-otp-display-customer-form-block', $args );

		$args = array(
			'render_callback' 	=> 'ewd_otp_sales_rep_form_shortcode',
		);

		register_block_type( 'order-tracking/ewd-otp-display-sales-rep-form-block', $args );

		$args = array(
			'attributes' => array(
				'location' => array(
					'type' => 'string',
				),
			),
			'render_callback' 	=> 'ewd_otp_customer_order_form_shortcode',
		);

		register_block_type( 'order-tracking/ewd-otp-display-customer-order-form-block', $args );

		$args = array(
			'attributes' => array(
				'tracking_page_url' => array(
					'type' => 'string',
				),
			),
			'render_callback' 	=> 'ewd_otp_order_number_search_shortcode',
		);

		register_block_type( 'order-tracking/ewd-otp-order-number-search-block', $args );

		add_action( 'current_screen', array( $this, 'localize_data' ) );
	}

	/**
	 * Localize data for use in block parameters
	 * @since 3.0.0
	 */
	public function localize_data() {

		global $ewd_otp_controller;

		$screen = get_current_screen();

		if ( ! $screen->is_block_editor and $screen->id != 'widgets' ) { return; }

		wp_enqueue_style( 'ewd-otp-css' );
		wp_enqueue_style( 'ewd-otp-blocks-css' );
		wp_enqueue_script( 'ewd-otp-blocks-js' );

		$locations = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'locations' ) );

		$location_options = array( array( 'value' => '', 'label' => '' ) );
		foreach ( $locations as $location ) {
			$location_options[] = array(
				'value' => esc_attr( $location->name ),
				'label' => esc_attr( $location->name ),
			);

		}

		wp_add_inline_script(
			'ewd-otp-blocks-js',
			sprintf(
				'var ewd_otp_blocks = %s;',
				json_encode( array(
					'locationOptions' => $location_options,
				) )
			),
			'before'
		);
	}

	/**
	 * Create a new category of blocks to hold our block
	 * @since 3.0.0
	 */
	public function add_block_category( $categories ) {
		
		$categories[] = array(
			'slug'  => 'ewd-otp-blocks',
			'title' => __( 'Order Tracking', 'order-tracking' ),
		);

		return $categories;
	}

	/**
	 * Register the necessary JS and CSS to display the block in the editor
	 * @since 3.1.2
	 */
	public function enqueue_assets() {

		wp_register_style( 'ewd-otp-css', EWD_OTP_PLUGIN_URL . '/assets/css/ewd-otp.css', EWD_OTP_VERSION );
		wp_register_style( 'ewd-otp-blocks-css', EWD_OTP_PLUGIN_URL . '/assets/css/ewd-otp-blocks.css', array( 'wp-edit-blocks' ), EWD_OTP_VERSION );
		wp_register_script( 'ewd-otp-blocks-js', EWD_OTP_PLUGIN_URL . '/assets/js/ewd-otp-blocks.js', array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-server-side-render' ), EWD_OTP_VERSION );
	}
	
}
}