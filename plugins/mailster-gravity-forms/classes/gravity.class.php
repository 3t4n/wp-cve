<?php

class MailsterGravitiyForm {

	private $plugin_path;
	private $plugin_url;

	public function __construct() {

		$this->plugin_path = plugin_dir_path( MAILSTER_GRAVITYFORMS_FILE );
		$this->plugin_url  = plugin_dir_url( MAILSTER_GRAVITYFORMS_FILE );

		register_activation_hook( MAILSTER_GRAVITYFORMS_FILE, array( &$this, 'activate' ) );
		register_deactivation_hook( MAILSTER_GRAVITYFORMS_FILE, array( &$this, 'deactivate' ) );

		load_plugin_textdomain( 'mailster-gravityforms' );

		add_action( 'plugins_loaded', array( &$this, 'init' ) );

	}

	public function activate( $network_wide ) {}

	public function deactivate( $network_wide ) {}

	public function init() {

		add_filter( 'gform_after_submission', array( &$this, 'after_submission' ), 10, 2 );

		if ( is_admin() ) {

			add_filter( 'gform_form_settings_menu', array( &$this, 'settings_menu' ), 10, 2 );
			add_action( 'gform_form_settings_page_mailster', array( &$this, 'settings_page' ) );

			if ( isset( $_POST['gform_save_settings'] ) ) {
				$this->save();
			}
		}
	}

	public function after_submission( $entry, $form ) {

		// Mailster doesn't exists.
		if ( ! function_exists( 'mailster' ) ) {
			return;
		}

		// Mailster options are not defined.
		if ( ! isset( $form['mailster'] ) ) {
			return;
		}

		// form not active.
		if ( ! isset( $form['mailster']['active'] ) ) {
			return;
		}

		// condition check matches.
		if ( isset( $form['mailster']['conditional'] ) ) {

			// radio button.
			if ( isset( $form['mailster']['conditional_id'] ) ) {

				if ( isset( $entry[ $form['mailster']['conditional_id'] ] ) && ( $entry[ $form['mailster']['conditional_id'] ] != $form['mailster']['conditional_field'] ) ) {
					return;
				}
				if ( ! isset( $entry[ $form['mailster']['conditional_id'] ] ) ) {
					return;
				}

				// checkbox.
			} else {

				if ( isset( $entry[ $form['mailster']['conditional_field'] ] ) && empty( $entry[ $form['mailster']['conditional_field'] ] ) ) {
					return;
				}
				if ( ! isset( $entry[ $form['mailster']['conditional_field'] ] ) ) {
					return;
				}
			}
		}

		$userdata = array();
		$list_ids = isset( $form['mailster']['lists'] ) ? (array) $form['mailster']['lists'] : array();

		foreach ( $form['mailster']['map'] as $field_id => $key ) {
			if ( '_list' == $key ) {
				$listname = $entry[ $field_id ];
				if ( empty( $listname ) ) {
					continue;
				}
				$list_id = mailster( 'lists' )->get_by_name( $listname, 'ID' );
				if ( ! $list_id ) {
					$list_ids[] = mailster( 'lists' )->add( $listname, true );
				} else {
					$list_ids[] = $list_id;
				}
			} elseif ( $key != -1 ) {
				$userdata[ $key ] = $entry[ $field_id ];
			}
		}

		if ( ! isset( $userdata['email'] ) ) {
			return;
		}

		if ( $subscriber = mailster( 'subscribers' )->get_by_mail( $userdata['email'] ) ) {
			$userdata['status']      = $subscriber->status;
			$subscriber_notification = $subscriber->status == 0; // send again if not confirmed already,
		} else {
			$userdata['status']      = isset( $form['mailster']['double-opt-in'] ) ? 0 : 1;
			$subscriber_notification = true;
		}

		$overwrite     = true;
		$merge         = true;
		$subscriber_id = mailster( 'subscribers' )->add( $userdata, $overwrite, $merge, $subscriber_notification );

		if ( ! is_wp_error( $subscriber_id ) ) {
			mailster( 'subscribers' )->assign_lists( $subscriber_id, $list_ids, false, $userdata['status'] ? true : false );
		}

	}

	public function settings_page() {

		GFFormSettings::page_header();

		include $this->plugin_path . '/views/page.php';

		GFFormSettings::page_footer();
	}

	public function settings_menu( $settings_tabs, $form_id ) {

		wp_enqueue_style( 'mailster-gravityforms-style', $this->plugin_url . 'assets/style.css', array(), MAILSTER_GRAVITYFORMS_VERSION );

		$settings_tabs[] = array(
			'name'  => 'mailster',
			'label' => 'Mailster',
			'icon'  => 'gform-icon--mailster',
		);
		return $settings_tabs;
	}

	public function save() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return; }

		if ( ! isset( $_POST['gform_save_form_settings'] ) || ! wp_verify_nonce( $_POST['gform_save_form_settings'], 'mailster_gf_save_form' ) ) {
			return; }

		$form_id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : null;
		if ( ! $form_id ) {
			return; }

		$form = RGFormsModel::get_form_meta( $form_id );
		if ( ! $form ) {
			return; }

		$form['mailster'] = $_POST['mailster'];
		$conditional      = explode( '|', $form['mailster']['conditional_field'] );

		if ( count( $conditional ) > 1 ) {
			$form['mailster']['conditional_id'] = array_shift( $conditional );
		}

		$form['mailster']['conditional_field'] = implode( '|', $conditional );

		RGFormsModel::update_form_meta( $form_id, $form );

	}
}
