<?php

/**
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_Admin_Notices {

	static $ongoing_notice_removal = array();

	public function __construct( $dismiss_notice=false ) {
		if ( $dismiss_notice ) {
			add_action( 'wp_ajax_ephd_dismiss_ongoing_notice', array( $this, 'ajax_dismiss_ongoing_notice') );
			return;
		}

		add_action( 'admin_notices', array( $this, 'show_admin_notices' ) );

		add_filter( 'ephd_admin_sub_menu_contact_form', array( $this, 'add_admin_sub_menu_contact_form_notification_bubbles' ) );
	}

	/**
	 * Add notification bubbles to admin contact form sub menu
	 *
	 * @param $menu_title
	 *
	 * @return string
	 */
	public function add_admin_sub_menu_contact_form_notification_bubbles( $menu_title ) {

		$submissions_db = new EPHD_Submissions_DB();
		$submissions_count = $submissions_db->get_submissions_count_by_status();

		if ( ! empty( $submissions_count['error'] ) ) {
			$menu_title .= ' <span class="awaiting-mod">' . esc_html( $submissions_count['error'] ) . '</span>';
		}
		if ( ! empty( $submissions_count['success'] ) ) {
			$menu_title .= ' <span class="awaiting-mod" style="background-color: #3da521;">' . esc_html( $submissions_count['success'] ) . '</span>';
		}

		return $menu_title;
	}

	/**
	 * Show noticers for admin at the top of the page
	 */
	public function show_admin_notices() {

		$request_page = EPHD_Utilities::post( 'page' );

		// show notices on Help Dialog pages only
		$is_ephd_request = EPHD_Core_Utilities::is_help_dialog_admin_page( $request_page );
		if ( ! $is_ephd_request ) {
			return;
		}

		// only editors and admins should see the notice messages
		// $user = EPHD_Utilities::get_current_user();
		if ( ! function_exists( 'wp_get_current_user' ) || ( ! current_user_can( 'editor' ) && ! current_user_can( 'administrator' ) ) ) {
			return;
		}

		// ONE TIME notice is deleted right after it is displayed
		$notices = get_option( 'ephd_one_time_notices', array() );
		if ( ! empty($notices) ) {
			delete_option( 'ephd_one_time_notices' );
		}

		// display ONE TIME and LONG notices
		$notice = get_option( 'ephd_ongoing_notices', array() );
		if ( ! empty( $notice ) ) {
			$notices += $notice;
		}

		$update_notices = false;
		foreach ( $notices as $key => $notice ) {

			if ( ! isset( $notice['type'] ) ) {
				unset( $notices[ $key ] );
				$update_notices = true;
				continue;
			}

			if ( isset( $notice['id'] ) && in_array( $notice['id'], self::$ongoing_notice_removal ) ) {
				unset( $notices[ $key ] );
				$update_notices = true;
				continue;
			}

            EPHD_HTML_Forms::notification_box_top( array(
                'type'              => $notice['type'],
                'id'                => $notice['id'],
                'title'             => $notice['title'],
                'desc'              => $notice['text'],
                'button_confirm'    => 'Dismiss',
            ) );

		}

		// some notices are not valid any more or there have invalid data so remove them
		if ( $update_notices ) {
			update_option( 'ephd_ongoing_notices', $notices );
		}
	}

	/**
	 * ONE TIME notice appears only once.
	 * NOTE: this notice should not always happen. It should happen only once when the if user does some action.
	 *
	 * @param string $type - 'warning', 'error', 'info'
	 * @param string $text
	 */
	public static function add_one_time_notice( $type='warning', $text='' ) {
		$notices = get_option( 'ephd_one_time_notices', array() );
		$notices[] = array(
			'type' => $type,
			'id' => '',
			'icon'	=> '',
			'title' => '',
			'text' => $text
		);
		update_option( 'ephd_one_time_notices', $notices );
	}

	/**
	 * LONG TIME notice appears until user dismiss it. We also need to take care of case when the ongoing notice is not valid any more.
	 *
	 * @param string $type - 'warning', 'error', 'info'
	 * @param string $id - unique notice id string e.g. ephd_elementor_settings
	 * @param string $text
	 * @param string $title
	 * @param string $icon
	 */
	public static function add_ongoing_notice( $type='warning', $id='', $text='', $title='', $icon='' ) {

		// update current ongoing notices if needed
		$notices = get_option( 'ephd_ongoing_notices', array() );

		// Check if user already dismissed the notice
		if ( get_user_meta( get_current_user_id(), $id, true ) ) {
			if ( ! empty($notices[$id]) ) {
				unset( $notices[$id] );
				update_option( 'ephd_ongoing_notices', $notices );
			}
			return;
		}

		if ( ! isset($notices[$id]) ) {

			$notices[$id] = array(
				'type'  => $type,
				'id'    => $id,
				'icon'  => $icon,
				'title' => $title,
				'text'  => $text
			);
			update_option( 'ephd_ongoing_notices', $notices );
		}

		// if we are adding notice we don't want to remove it
		if ( isset(self::$ongoing_notice_removal[$id]) ) {
			unset(self::$ongoing_notice_removal[$id]);
		}
	}

	/**
	 * Do not show particular ongoing notice
	 * @param $id
	 */
	public static function remove_ongoing_notice( $id ) {
		self::$ongoing_notice_removal[$id] = $id;
	}

	/**
	 * Let user to see the ongoing notice again (notice condition is true again)
	 * @param $id
	 */
	public static function remove_dismissed_ongoing_notice( $id ) {
		delete_user_meta( get_current_user_id(), $id );
	}

	/**
	 * User dismissed ongoing notice so record it
	 */
	public static function ajax_dismiss_ongoing_notice() {
		$dismiss_id = EPHD_Utilities::post( 'ephd_dismiss_id' );
		if ( ! empty( $dismiss_id ) ) {
			update_user_meta( get_current_user_id(), $dismiss_id, 1 );
		   self::dismiss_ongoing_notice( $dismiss_id );
		}
	}

	/**
	 * Dismiss ongoing notice
	 * @param string $id
	 */
	public static function dismiss_ongoing_notice( $id = '' ) {
		if ( empty($id) ) {
			delete_option( 'ephd_ongoing_notices' );
			return;
		}

		$notices = get_option( 'ephd_ongoing_notices', array() );
		if ( isset($notices[$id]) ) {
			unset( $notices[$id] );
		   update_option( 'ephd_ongoing_notices', $notices );
	  }
	}

}
