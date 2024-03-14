<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_TS4WC_Admin_Notices_Under_WC_Admin {

	/**
	 * Instance of this class.
	 *
	 * @var object Class Instance
	 */
	private static $instance;
	
	/**
	 * Get the class instance
	 *
	 * @return WC_Advanced_Shipment_Tracking_Admin_notice
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	
	/*
	* Admin notice in WC admin For UPGRADE TO PRO
	*/
	public function admin_notices_for_TrackShip_pro() {
		
		if ( ! class_exists( 'Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes' ) ) {
			return;
		}
		
		$note_name = 'trackship_wc_admin_notice';
		
		// Otherwise, add the note
		$activated_time = current_time( 'timestamp', 0 );
		$activated_time_formatted = gmdate( 'F jS', $activated_time );
		$note = new Automattic\WooCommerce\Admin\Notes\WC_Admin_Note();
		$note->set_title( 'Supercharge Customer Experience with TrackShip for WooCommerce' );
		$note->set_content( "Upgrade your plan today to unlock premium features and maximize your tracking capabilities. Whether you choose a monthly or yearly subscription, you'll enjoy enhanced tracking benefits. Plus, get up to 2 months FREE with an annual plan! Don't miss out on this opportunity to boost your post-shipping workflow." );

		$note->set_content_data( (object) array(
			'getting_started'		=> true,
			'activated'				=> $activated_time,
			'activated_formatted'	=> $activated_time_formatted,
		) );
		$note->set_type( 'info' );
		$note->set_image('');
		$note->set_name( $note_name );
		$note->set_source( 'TrackShip Pro' );
		$note->set_image('');
		$note->add_action( 
			'settings', 'UPGRADE NOW', 'https://my.trackship.com/settings/#billing'
		);
		$note->save();
	}

	/*
	* Admin notice in WC admin for Review
	*/
	public function admin_notices_for_TrackShip_review() {
		
		if ( ! class_exists( 'Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes' ) ) {
			return;
		}
		
		$note_name = 'trackship_review_wc_admin_notice';
		
		// Otherwise, add the note
		$activated_time = current_time( 'timestamp', 0 );
		$activated_time_formatted = gmdate( 'F jS', $activated_time );
		$note = new Automattic\WooCommerce\Admin\Notes\WC_Admin_Note();
		$note->set_title( 'Enjoying TrackShip for WooCommerce?' );
		$note->set_content( "We'd love to hear your thoughts! Please take a moment to leave a review on WordPress.org. Your feedback helps us improve and grow. Thank you for your support!" );

		$note->set_content_data( (object) array(
			'getting_started'		=> true,
			'activated'				=> $activated_time,
			'activated_formatted'	=> $activated_time_formatted,
		) );
		$note->set_type( 'info' );
		$note->set_image('');
		$note->set_name( $note_name );
		$note->set_source( 'TrackShip Review' );
		$note->set_image('');
		$note->add_action( 
			'settings', 'Review Now', 'https://wordpress.org/support/plugin/trackship-for-woocommerce/reviews/#new-post'
		);
		$note->save();
	}
}
