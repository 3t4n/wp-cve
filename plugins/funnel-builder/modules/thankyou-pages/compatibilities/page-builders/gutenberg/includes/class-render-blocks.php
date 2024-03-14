<?php
/**
 * This file handles the dynamic parts of our blocks.
 *
 * @package BWFBlocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Render the dynamic aspects of our blocks.
 *
 * @since 1.2.0
 */
#[AllowDynamicProperties]

  class WFTYBlocks_Render_Block {
	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Instance
	 * @since 1.2.0
	 */
	private static $instance;

	/**
	 * Initiator.
	 *
	 * @return object initialized object of class.
	 * @since 1.2.0
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_blocks' ) );
	}

	/**
	 * Register our dynamic blocks.
	 *
	 * @since 1.2.0
	 */
	public function register_blocks() {
		// Only load if Gutenberg is available.
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		$bwfblocks = [
			[
				'name'     => 'bwfblocks/customer-details',
				'callback' => 'do_customer_details_block',
			],
			[
				'name'     => 'bwfblocks/order-details',
				'callback' => 'do_order_details_block',
			],
		];

		foreach ( $bwfblocks as $block ) {
			register_block_type( $block['name'], array(
				'render_callback' => array( $this, $block['callback'] ),
			) );
		}
	}

	public function has_block_visibiliy_classes( $settings, $classes ) {
		if ( ! empty( $settings['vsdesk'] ) ) {
			$classes[] = 'bwf-hide-lg';
		}
		if ( ! empty( $settings['vstablet'] ) ) {
			$classes[] = 'bwf-hide-md';
		}
		if ( ! empty( $settings['vsmobile'] ) ) {
			$classes[] = 'bwf-hide-sm';
		}

		return $classes;
	}

	public function do_customer_details_block( $attributes, $content ) {
		$output   = '';
		$defaults = array();

		$settings = wp_parse_args( $attributes, $defaults );

		$classNames = array(
			'wfty-cust-details-block',
			'wfty-' . $settings['uniqueID'],
		);

		if ( ! empty( $settings['className'] ) ) {
			$classNames[] = $settings['className'];
		}

		$classNames = $this->has_block_visibiliy_classes( $settings, $classNames );

		$output  = sprintf( '<div %s>', bwfblocks_attr( 'accordion', array(
			'id'    => isset( $settings['anchor'] ) ? $settings['anchor'] : null,
			'class' => implode( ' ', $classNames ),
		), $settings ) );
		$heading = isset( $settings['content'] ) ? $settings['content'] : __( 'Customer Details' );

		$customer_layout = ( isset( $settings['layout'] ) && isset( $settings['layout']['desktop'] ) && '2c' !== $settings['layout']['desktop'] ) ? ' wfty_full_width' : '2c'; //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
		$customer_layout .= ( isset( $settings['layout'] ) && isset( $settings['layout']['tablet'] ) && '2c' === $settings['layout']['tablet'] ) ? ' wfty_2c_tab_width' : ''; //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
		$customer_layout .= ( isset( $settings['layout'] ) && isset( $settings['layout']['mobile'] ) && '2c' === $settings['layout']['mobile'] ) ? ' wfty_2c_mob_width' : ''; //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable

		if ( $customer_layout !== '' && $customer_layout !== '2c' ) {
			$customer_layout .= " wfty_cont_style";
		}

		$output .= '[wfty_customer_details layout_settings ="' . $customer_layout . '" customer_details_heading="' . $heading . '"]';

		$output .= '</div>';


		return $output;

	}

	public function do_order_details_block( $attributes, $content ) {
		$output   = '';
		$defaults = array(
			'subscriptionFont' => array(
				'desktop' => array(
					'size'     => 15,
					'sizeUnit' => 'px'
				)
			),
			'downloadFont'     => array(
				'desktop' => array(
					'size'     => 15,
					'sizeUnit' => 'px'
				)
			),
			'dividerColor'     => array(
				'dekstop' => '#dddddd'
			)
		);

		$settings = wp_parse_args( $attributes, $defaults );

		$classNames = array(
			'wfty-order-details-block',
			'wfty-' . $settings['uniqueID'],
		);

		if ( ! empty( $settings['className'] ) ) {
			$classNames[] = $settings['className'];
		}

		$classNames   = $this->has_block_visibiliy_classes( $settings, $classNames );
		$classNames[] = isset( $settings['downloadPreview'] ) && $settings['downloadPreview'] === true ? '' : 'wfty-hide-download';
		$classNames[] = isset( $settings['subscriptionPreview'] ) && $settings['subscriptionPreview'] === true ? '' : 'wfty-hide-subscription';

		$output            = sprintf( '<div %s>', bwfblocks_attr( 'accordion', array(
			'id'    => isset( $settings['anchor'] ) ? $settings['anchor'] : null,
			'class' => implode( ' ', $classNames ),
		), $settings ) );
		$order_details_img = true;
		if ( isset( $settings['orderProductImage'] ) && false === $settings['orderProductImage'] ) {
			$order_details_img = false;
		}
		$order_heading_text         = isset( $settings['orderHeading'] ) ? $settings['orderHeading'] : __( 'Order Details' );
		$order_subscription_heading = isset( $settings['subscriptionHeading'] ) ? $settings['subscriptionHeading'] : __( 'Subscription' );
		$order_download_heading     = isset( $settings['downloadHeading'] ) ? $settings['downloadHeading'] : __( 'Downloads' );
		$download_btn_text          = isset( $settings['downloadBtnText'] ) ? $settings['downloadBtnText'] : __( 'Download' );
		$show_column_download       = isset( $settings['downloadFileCount'] ) ? $settings['downloadFileCount'] : false;
		$show_column_file_expiry    = isset( $settings['downloadFileExpiry'] ) ? $settings['downloadFileExpiry'] : false;


		$output .= '[wfty_order_details order_details_img="' . $order_details_img . '" order_details_heading="' . $order_heading_text . '" order_subscription_heading="' . $order_subscription_heading . '" order_download_heading="' . $order_download_heading . '" order_downloads_btn_text="' . $download_btn_text . '" order_downloads_show_file_downloads="' . wp_json_encode( $show_column_download ) . '"  order_downloads_show_file_expiry="' . wp_json_encode( $show_column_file_expiry ) . '"]';

		$output .= '</div>';


		return $output;

	}

}

WFTYBlocks_Render_Block::get_instance();
