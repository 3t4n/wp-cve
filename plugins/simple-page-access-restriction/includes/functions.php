<?php
/**
 * Helper Functions
 *
 * @package     Simple_Page_Access_Restriction\Functions
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'ps_simple_par_get_admin_pages' ) ) {
	function ps_simple_par_get_admin_pages() {
		$admin_pages = array(
			'main'    => array(
				'title'     => __( 'Simple Page Access Restriction', 'simple-page-access-restriction' ),
				'sub_title' => __( 'Settings', 'simple-page-access-restriction' ),
				'icon'      => 'menu-icon.svg',
				'slug'      => 'simple-page-access-restriction',
			),
		);

		return $admin_pages;
	}
}

if ( ! function_exists( 'ps_simple_par_get_admin_page_by_name' ) ) {
	function ps_simple_par_get_admin_page_by_name( $page_name = 'main' ) {
		
		$pages = ps_simple_par_get_admin_pages();

		if ( ! isset( $pages[ $page_name ] ) ) {
			$page = array(
				'title' => __( 'Page Title', 'simple-page-access-restriction' ),
				'slug' => 'simple-par-not-available',
			);
		} else {
			$page = $pages[ $page_name ];
		}

		return $page;
	}
}


if ( ! function_exists( 'ps_simple_par_show_message' ) ) {
	/**
	 * Generic function to show a message to the user using WP's
	 * standard CSS classes to make use of the already-defined
	 * message colour scheme.
	 *
	 * @param $message string message you want to tell the user.
	 * @param $error_message boolean true, the message is an error, so use
	 * the red message style. If false, the message is a status
	 * message, so use the yellow information message style.
	 */
	function ps_simple_par_show_message( $message, $error_message = false ) {
		
		if ( $error_message ) {
			echo '<div id="message" class="error">';
		} else {
			echo '<div id="message" class="updated fade">';
		}

		echo '<p><strong>' . esc_html( $message ) . '</strong></p></div>';
	}
}

if ( ! function_exists( 'ps_simple_par_is_admin_page' ) ) {
	/**
	 * Helper function for checking an admin page or sub view
	 *
	 * @param $page_name string page to check.
	 * @param $sub_view string sub view to check.
	 * @return boolean
	 */
	function ps_simple_par_is_admin_page( $page_name, $sub_view = '' ) {
		if ( ! is_admin() ) {
			return false;
		}

		global $pagenow;
		$page_id = get_current_screen()->id;

		if ( ! $pagenow === $page_name ) {
			return false;
		}

		if ( ! empty( $sub_view ) && ! stristr( $page_id, $sub_view ) ) {
			return false;
		}

		return true;
	}
}

if ( ! function_exists( 'ps_simple_par_is_front_end_page' ) ) {
	/**
	 * Helper function for checking a front page or sub view
	 *
	 * @param $page_name string page to check.
	 * @param $sub_view string sub view to check.
	 * @return boolean
	 */
	function ps_simple_par_is_front_end_page( $page_name, $sub_view = '' ) {
		if ( is_admin() ) {
			return false;
		}

		/* Add Custom Logic Here */
		
		return true;
	}
}

if ( ! function_exists( 'ps_simple_par_get_settings' ) ) {
	/**
	 * Helper function for returning an array of saved settings
	 *
	 * @return array
	 */
	function ps_simple_par_get_settings() {
		$defaults = array(
			'redirect_type'      => 'page',
			'login_page'         => '',
			'redirect_url'       => '',
			'remove_data'        => '',
			'redirect_parameter' => 'redirect_to',
			'restrict_new_posts' => '',
			'post_types'         => array(
				'page',
			),
		);

		$settings = get_option( 'ps_simple_par_settings' );

		if ( ! is_array( $settings ) ) {
			$settings = array();
		}

		foreach ( $defaults as $key => $value ) {
			if ( ! isset( $settings[ $key ] ) ) {
				$settings[ $key ] = $value;
			}
		}

		return $settings;
	}
}

if ( ! function_exists( 'ps_simple_par_is_page_restricted' ) ) {
	/**
	 * Helper function for returning an array of saved settings
	 *
	 * @return array
	 */
	function ps_simple_par_is_page_restricted( $page_id ) {
		return 1 === intval( get_post_meta( $page_id, 'page_access_restricted', true ) );
	}
}

if ( ! function_exists( 'ps_simple_par_is_new_post_restricted' ) ) {
	/**
	 * Helper function for returning an array of saved settings
	 *
	 * @return array
	 */
	function ps_simple_par_is_new_post_restricted() {
		$settings = ps_simple_par_get_settings();
		return intval( $settings['restrict_new_posts'] ) == 1;
	}
}

if ( ! function_exists( 'ps_simple_par_get_login_page_id' ) ) {
	/**
	 * Helper function for returning an ID of Page selected for Login Redirect
	 *
	 * @return array
	 */
	function ps_simple_par_get_login_page_id() {
		$settings = ps_simple_par_get_settings();
		return intval( $settings['login_page'] );
	}
}