<?php
/**
 * Widget class
 */

class SarbacaneNewsWidget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'sarbacane_newsletter',
			__( 'Sarbacane Newsletter', 'sarbacane-desktop' ),
			array(
				'description' => __( 'Have visitors fill out this form and the associated list will be updated accordingly', 'sarbacane-desktop' )
			)
		);
	}

	public function add_admin_menu() {
		add_submenu_page(
			'sarbacane',
			__( 'Widget', 'sarbacane-desktop' ),
			__( 'Widget', 'sarbacane-desktop' ),
			'administrator',
			'wp_news_widget', array(
				$this,
				'display_settings'
			)
		);
	}

	/**
	 * save newsletter subscribe
	 */
	public function sarbacane_save_widget() {
		$valid = false;
		if ( isset ( $_POST ['sarbacane_form_value'], $_POST ['email'] ) ) {
			$sarbacane_form_value = sanitize_text_field( $_POST ['sarbacane_form_value'] );
			if ($sarbacane_form_value == 'sarbacane_desktop_widget' ) {
				$user_registering = new stdClass();
				$user_registering->email = sanitize_email( $_POST ['email'] );
				if ( is_email( $user_registering->email ) ) {
					$fields = get_option( 'sarbacane_news_fields', array() );
					$columns = array();
					foreach ( $fields as $field ) {
						if ( strtolower( $field->label ) == 'email' ) {
							continue;
						}
						$field_label = $field->label;
						$field_label_html = str_replace( ' ', '_', $field->label );
						$field_label = strtolower( $field_label );
						if ( isset ( $_POST [ $field_label_html ] ) ) {
							$fieldValue = stripslashes( sanitize_text_field( $_POST [ $field_label_html ] ) );
						} else {
							$fieldValue = '';
						}
						$user_registering->$field_label = $fieldValue;
						$columns [ $field_label ] = $fieldValue;
					}
					$user_registering->registration_date = gmdate( 'Y-m-d H:i:s' );
					$version = get_option( 'sarbacane_version' );
					$result = true;
					if ( $version !== false ) {
						global $wpdb;
						$sql = "
						INSERT INTO `{$wpdb->prefix}sd_subscribers` (`email`, `columns`, `registration_date`)
						VALUES (%s, %s, %s);";
						$result = $wpdb->query(
							$wpdb->prepare(
								$sql,
								$user_registering->email,
								json_encode( $columns ),
								$user_registering->registration_date
							)
						);
					}
					if ( $version === false || $result === false ) {
						$users_registered = get_option( 'sarbacane_newsletter_list', array() );
						$users_registered[] = $user_registering;
						update_option( 'sarbacane_newsletter_list', $users_registered, false );
					}
					$valid = true;
				}
			}
		}
		return $this->display_registration_message($valid);
	}

	public function display_registration_message($valid) {
		if ( $valid ) {
			$message = json_encode(
				array(
					'message' => get_option(
						'sarbacane_news_registration_message',
						__( 'Congrats! You signed up for our newsletter.', 'sarbacane-desktop' )
					)
				)
			);
		} else {
			$message = json_encode( array( 'message' => __( 'Email isn\'t valid.', 'sarbacane-desktop' ) ) );
		}
		return '
		<script type="text/javascript">
			var registration_message = ' . $message . ';
			alert( registration_message.message );
			document.location = "' . get_home_url() . '";
		</script>';
	}

	public function widget( $args, $instance ) {
		$title = get_option( 'sarbacane_news_title', __( 'Newsletter', 'sarbacane-desktop' ) );
		$description =  get_option( 'sarbacane_news_description', '' );
		$fields = get_option( 'sarbacane_news_fields', array() );
		if ( ! is_array( $fields ) || count( $fields ) == 0 ) {
			$default_email = new stdClass();
			$default_email->label = 'email';
			$default_email->placeholder = '';
			$default_email->mandatory = true;
			update_option( 'sarbacane_news_fields', array( $default_email ) );
			$fields = get_option( 'sarbacane_news_fields' );
		}
		$registration_button = get_option(
			'sarbacane_news_registration_button',
			__( 'Inscription', 'sarbacane-desktop' )
		);
		$registration_mandatory_fields = get_option(
			'sarbacane_news_registration_mandatory_fields',
			__( 'Fields marked with * are mandatory', 'sarbacane-desktop' )
		);
		$registration_legal_notices_mentions = get_option( 'sarbacane_news_registration_legal_notices_mentions', '' );
		$registration_legal_notices_url = get_option( 'sarbacane_news_registration_legal_notices_url', '' );

		$list_type = 'N';
		$rand = mt_rand( 0, 1000000 );
		wp_enqueue_script(
			'sarbacane-widget.js',
			plugins_url( 'js/sarbacane-widget.js', __FILE__ ),
			array( 'jquery' ),
			'1.4.9'
		);
		wp_enqueue_style(
			'sarbacane_widget.css',
			plugins_url( 'css/sarbacane_widget.css', __FILE__ ),
			array(),
			'1.4.9'
		);
		include( 'views/sarbacane-widget.php' );
	}

	public function update( $new_instance, $old_instance ) {
		return $old_instance;
	}

	public function form( $instance ) {
		_e( 'Setup this widget by clicking the Sarbacane widget menu', 'sarbacane-desktop' );
	}

	public function sarbacane_init_widget() {
		register_widget( 'SarbacaneNewsWidget' );
	}

	/**
	 * Widget configuration page
	 */
	public function display_settings() {
		if ( ! current_user_can( 'administrator' ) ) {
			return;
		}
		wp_enqueue_style(
			'sarbacane_global.css',
			plugins_url( 'css/sarbacane_global.css', __FILE__ ),
			array( 'wp-admin' ),
			'1.4.9'
		);
		wp_enqueue_style(
			'sarbacane_widget_admin_panel.css',
			plugins_url( 'css/sarbacane_widget_admin_panel.css', __FILE__ ),
			array( 'wp-admin' ),
			'1.4.9'
		);
		wp_enqueue_script(
			'sarbacane-widget-adminpanel.js',
			plugins_url( 'js/sarbacane-widget-adminpanel.js', __FILE__ ),
			array( 'jquery', 'underscore' ),
			'1.4.9'
		);
		$nonce_ok = false;
		if ( isset( $_POST ['sarbacane_token'] ) ) {
			if ( wp_verify_nonce( $_POST ['sarbacane_token'], 'sarbacane_save_configuration' ) ) {
				$nonce_ok = true;
			}
		}
		if ( $nonce_ok && isset ( $_POST ['sarbacane_save_configuration'] ) ) {
			if ( isset ( $_POST ['sarbacane_widget_title'], $_POST ['sarbacane_widget_description'],
				$_POST ['sarbacane_widget_registration_button'], $_POST ['sarbacane_widget_registration_message'],
				$_POST ['sarbacane_field_number'], $_POST ['sarbacane_widget_registration_mandatory_fields'] ) ) {
				$sanitized_post = stripslashes_deep( sanitize_post( $_POST, 'db' ) );
				foreach ( $sanitized_post as $key => $post ) {
					$sanitized_post [ $key ] = sanitize_text_field( $post );
				}
				$this->save_parameters( $sanitized_post );
			}
		}

		$title = get_option( 'sarbacane_news_title', __( 'Newsletter', 'sarbacane-desktop' ) );
		$description = get_option( 'sarbacane_news_description', '' );
		$registration_message = get_option(
			'sarbacane_news_registration_message',
			__( 'Congrats! You signed up for our newsletter.', 'sarbacane-desktop' )
		);
		$registration_button = get_option( 'sarbacane_news_registration_button', __( 'Inscription', 'sarbacane-desktop' ) );
		$registration_mandatory_fields = get_option(
			'sarbacane_news_registration_mandatory_fields',
			__( 'Fields marked with * are mandatory', 'sarbacane-desktop' )
		);
		$registration_legal_notices_mentions = get_option( 'sarbacane_news_registration_legal_notices_mentions', '' );
		$registration_legal_notices_url = get_option( 'sarbacane_news_registration_legal_notices_url', '' );

		$fields = get_option( 'sarbacane_news_fields', array() );
		if ( ! is_array( $fields ) || count( $fields ) == 0 ) {
			$default_email = new stdClass();
			$default_email->label = 'email';
			$default_email->placeholder = '';
			$default_email->mandatory = true;
			update_option( 'sarbacane_news_fields', array( $default_email ) );
			$fields = get_option( 'sarbacane_news_fields' );
		}

		require_once( 'views/sarbacane-widget-adminpanel.php' );
	}

	/**
	 * Save widget configuration
	 */
	public function save_parameters( $sanitized_post ) {
		$title = $sanitized_post ['sarbacane_widget_title'];
		$description = $sanitized_post ['sarbacane_widget_description'];
		$registration_button = $sanitized_post ['sarbacane_widget_registration_button'];
		$registration_message = $sanitized_post ['sarbacane_widget_registration_message'];
		$registration_mandatory_fields = $sanitized_post ['sarbacane_widget_registration_mandatory_fields'];
		$registration_legal_notices_mentions = $sanitized_post ['sarbacane_widget_registration_legal_notices_mentions'];
		$registration_legal_notices_url = $sanitized_post ['sarbacane_widget_registration_legal_notices_url'];
		$field_number = $sanitized_post ['sarbacane_field_number'];
		$fields = get_option( 'sarbacane_news_fields', array() );
		$new_fields = array();
		if ( isset ( $field_number ) && $field_number > 0 ) {
			for ( $i = 0; $i < $field_number; $i ++ ) {
				$field_config = new stdClass();
				if ( isset ( $sanitized_post [ 'sarbacane_label_' . $i ] ) && '' != $sanitized_post [ 'sarbacane_label_' . $i ] ) {
					$field_config->label = $sanitized_post [ 'sarbacane_label_' . $i ];
					if ( isset ( $sanitized_post [ 'sarbacane_field_' . $i ] ) && '' != $sanitized_post [ 'sarbacane_field_' . $i ] ) {
						$field_config->placeholder = $sanitized_post [ 'sarbacane_field_' . $i ];
					} else {
						$field_config->placeholder = '';
					}
					$field_config->mandatory = isset ( $sanitized_post [ 'sarbacane_mandatory_' . $i ] )
						&& $sanitized_post [ 'sarbacane_mandatory_' . $i ] == "true";
					$new_fields[] = $field_config;
				}
			}
		}

		// stucture change: reset for last check
		if ( json_encode( $new_fields ) != json_encode( $fields ) ) {
			$sd_ids_saved = get_option( 'sarbacane_sd_id_list', array() );
			foreach ( $sd_ids_saved as $sd_id_saved ) {
				update_option( 'sarbacane_news_call_' . $sd_id_saved, '', false );
			}
		}
		update_option( 'sarbacane_news_title', $title );
		update_option( 'sarbacane_news_description', $description );
		update_option( 'sarbacane_news_fields', $new_fields );
		update_option( 'sarbacane_news_registration_message', $registration_message );
		update_option( 'sarbacane_news_registration_button', $registration_button );
		update_option( 'sarbacane_news_registration_mandatory_fields', $registration_mandatory_fields );
		update_option( 'sarbacane_news_registration_legal_notices_mentions', $registration_legal_notices_mentions );
		update_option( 'sarbacane_news_registration_legal_notices_url', $registration_legal_notices_url );
	}

}
