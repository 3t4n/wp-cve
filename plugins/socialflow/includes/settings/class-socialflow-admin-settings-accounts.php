<?php
/**
 * SocialFlow_Admin_Settings_Accounts
 *
 * @package SocialFlow
 */

/**
 *  SocialFlow_Admin_Settings_Accounts.
 */
class SocialFlow_Admin_Settings_Accounts extends SocialFlow_Admin_Settings_Page {

	/**
	 * Hold view filename
	 *
	 * @since 1.0
	 * @var  string
	 */
	public $slug = 'accounts';

	/**
	 * Add actions to manipulate messages
	 */
	public function __construct() {
		global $socialflow;

		// Do nothing if application is not authorized.
		if ( ! $socialflow->is_authorized() ) {
			return;
		}

		// add save filter.
		add_filter( 'sf_save_settings', array( $this, 'save_settings' ) );

		// add save filter.
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		// Add update notice.
	}

	/**
	 * This is callback for admin_menu action fired in construct
	 *
	 * @since 2.1
	 * @access public
	 */
	public function admin_menu() {
		add_submenu_page(
			'socialflow',
			esc_attr__( 'Account Settings', 'socialflow' ),
			esc_attr__( 'Account Settings', 'socialflow' ),
			'manage_options',
			$this->slug,
			array( $this, 'page' )
		);
	}

	/**
	 * Render admin page with all accounts
	 */
	public function page() {
		global $socialflow;

		$show = $socialflow->options->get( 'show', array() );

		if ( ! get_settings_errors() && empty( $show ) ) {
			$this->add_settings_error( 'empty_enabled_accounts' );
		}

		$socialflow->render_view( 'admin/accounts-settings' );
	}

	/**
	 * Send message to accounts
	 *
	 * @since 2.1
	 * @access protected
	 *
	 * @param array $account_ids array.
	 * @return array
	 */
	protected function mb_filter_enabled_accounts_data( $account_ids ) {
		global $socialflow;

		foreach ( $account_ids as $key => $account_id ) {
			$account = $socialflow->accounts->get_by_id( $account_id );

			if ( $account->is_valid() ) {
				continue;
			}

			unset( $account_ids[ $key ] );
		}

		return $account_ids;
	}

	/**
	 * Send message to accounts
	 *
	 * @since 2.1
	 * @access protected
	 *
	 * @return bool|null
	 */
	protected function update_accounts() {
		global $socialflow;

		$api = $socialflow->get_api();

		// Get list of all user account and enable sf by default for each account.
		$accounts = $api->get_account_list();

		if ( is_wp_error( $accounts ) ) {
			$this->add_settings_error( 'error_update_accounts' );
			return false;
		}

		if ( empty( $accounts ) ) {
			$this->add_settings_error( 'empty_accounts' );
			return false;
		}

		$send = $socialflow->options->get( 'send' );
		$show = $socialflow->options->get( 'show' );
		foreach ( $accounts as $key => $account ) {
			if ( $account['is_valid'] ) {
				if ( isset( $send[ $key ] ) ) {
					unset( $send[ $key ] );
				}

				if ( isset( $show[ $key ] ) ) {
					unset( $show[ $key ] );
				}
			}
		}
		$socialflow->options->set( 'send', $send );
		// Store all user accounts.
		$socialflow->options->set( 'show', $show );

		remove_filter( 'sf_save_settings', array( $this, 'save_settings' ) );
		$socialflow->options->save();
		wp_safe_redirect( admin_url( '/admin.php?page=accounts', 'http' ), 301 );

		exit;
	}

	/**
	 * Sanitizes settings
	 *
	 * Callback for "sf_save_settings" hook in method SocialFlow_Admin::save_settings()
	 *
	 * @see SocialFlow_Admin::save_settings()
	 * @since 2.0
	 * @access public
	 *
	 * @param string|array $settings Settings passed in from filter.
	 * @return string|array Sanitized settings
	 */
	public function save_settings( $settings ) {
		global $socialflow;
		if ( ! $socialflow->is_page( $this->slug ) ) {
			return $settings;
		}
		$socialflow_params = filter_input_array( INPUT_POST );
		if ( ! isset( $socialflow_params['socialflow'] ) ) {
			return $settings;
		}

		$data = $socialflow_params['socialflow'];

		$types = array(
			'show',
			'send',
		);

		$update = ( isset( $data['update-accounts'] ) && $this->update_accounts() );

		foreach ( $types as $type ) {
			$ids = isset( $data[ $type ] ) ? $data[ $type ] : array();

			if ( $ids && $update ) {
				$ids = $this->mb_filter_enabled_accounts_data( $ids );
			}

			$settings[ $type ] = array_map( 'absint', $ids );

			if ( empty( $settings[ $type ] ) ) {
				$this->add_settings_error( "empty_{$type}_accounts" );
			}
		}

		return $settings;
	}
	/**
	 * Send message to accounts
	 *
	 * @since 2.1
	 * @access protected
	 *
	 * @param string $key settings account empty.
	 */
	protected function add_settings_error( $key ) {
		switch ( $key ) {
			case 'empty_enabled_accounts':
			case 'empty_show_accounts':
				$message = __( 'No social accounts enabled.', 'socialflow' );
				break;

			case 'empty_send_accounts':
				$message = __( 'No social accounts send.', 'socialflow' );
				break;

			case 'error_update_accounts':
				$message = __( 'Server connection error occurred. Please try again.', 'socialflow' );
				break;

			case 'empty_accounts':
				$message = __( 'No social accounts found.', 'socialflow' );
				break;
		}

		if ( isset( $message ) ) {
			add_settings_error( $this->slug, $key, $message, 'error' );
		}
	}
}
