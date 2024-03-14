<?php
/**
 * Ajax call function
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'LearnDash_PowerPack_Ajax_Call', false ) ) {
	/**
	 * Learndash_PowerPack_Ajax_Call Class.
	 */
	class LearnDash_PowerPack_Ajax_Call {
		/**
		 * Option name
		 *
		 * @var string
		 */
		public $option_name = 'learndash_powerpack_active_classes';

		/**
		 * Constructor
		 */
		public function __construct() {
			/**
			 * Ajax call enable/disable class.
			 */
			add_action( 'wp_ajax_enable_disable_class_ajax', [ $this, 'enable_disable_class_ajax' ] );
			/**
			 * Ajax call get model content.
			 */
			add_action( 'wp_ajax_learndash_get_modal_content', [ $this, 'learndash_get_modal_content' ] );
			/**
			 * Ajax call save form data.
			 */
			add_action( 'wp_ajax_learndash_save_class_data_ajax', [ $this, 'learndash_save_class_data_ajax' ] );
			/**
			 * Ajax call delete form data.
			 */
			add_action( 'wp_ajax_learndash_delete_class_data_ajax', [ $this, 'learndash_delete_class_data_ajax' ] );
		}

		/**
		 * Ajax call to enable/disable classes.
		 */
		public function enable_disable_class_ajax() {
			$get_option = get_option( $this->option_name );

			if ( isset( $_POST['nonce'] ) && ! empty( $_POST['nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'learndash-powerpack-settings-nonce-' . get_current_user_id() ) ) {
				if ( isset( $_POST['value'] ) ) {
					$class_name = sanitize_text_field( wp_unslash( $_POST['value'] ) );
				}
				if ( isset( $_POST['active'] ) ) {
					$active = sanitize_text_field( wp_unslash( $_POST['active'] ) );
				}
				$get_option[ $class_name ] = $active;
				$update_option             = update_option( $this->option_name, $get_option );

				if ( $update_option ) {
					$return = [
						'success' => 'true',
						'message' => 'Updated',
					];
					wp_send_json( $return );
					wp_die();
				} else {
					$return = [
						'success' => 'false',
						'message' => esc_html__( 'Error. Could not update setting. Please reload the page and try again.', 'learndash-powerpack' ),
					];
					wp_send_json_error( $return );
					wp_die();
				}
			}
			wp_send_json_error( [ 'message' => esc_html__( 'Error. Nonce verification failed', 'learndash-powerpack' ) ] );
			wp_die( esc_html__( "If you receive this error, you've been logged out by WordPress. Please log in and try again.", 'learndash-powerpack' ) );
		}

		/**
		 * Ajax call to get model content.
		 */
		public function learndash_get_modal_content() {
			if ( isset( $_POST['nonce'] ) && ! empty( $_POST['nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'learndash-powerpack-modal-nonce-' . get_current_user_id() ) ) {
				if ( isset( $_POST['class_name'] ) ) {
					$class_name_main = sanitize_text_field( wp_unslash( $_POST['class_name'] ) );

					$instatiate                 = new $class_name_main();
					$class_data                 = $instatiate->learndash_powerpack_class_details();
					$return['title']            = $class_data['title'];
					$return['settings_content'] = $class_data['settings'];
					$return['footer_content']   = '<input type="submit" data-class="' . esc_attr( $class_name_main ) . '" class="learndash_save_form_data imm-bg-white imm-py-2 imm-px-5 imm-border-solid imm-border-2 imm-border-gray-500 imm-rounded imm-font-semibold imm-cursor-pointer" value="' . esc_html__( 'Save Settings', 'learndash-powerpack' ) . '">
													<input type="button" data-class="' . esc_attr( $class_name_main ) . '" class="learndash_delete_form_data imm-my-1 imm-bg-red-50 imm-py-2 imm-px-5 imm-border-solid imm-border-2 imm-border-red-500 imm-rounded imm-font-semibold imm-cursor-pointer" value="' . esc_html__( 'Delete Settings', 'learndash-powerpack' ) . '">';
					$return['message']          = 'Content retrieved';

					wp_send_json_success( $return );
					wp_die();
				}
			}
			wp_send_json_error( [ 'message' => esc_html__( 'Error. Nonce verification failed or no class name provided', 'learndash-powerpack' ) ] );
			wp_die( esc_html__( "If you receive this error, you've been logged out by WordPress. Please log in and try again.", 'learndash-powerpack' ) );
		}

		/**
		 * Ajax call for save form content.
		 */
		public function learndash_save_class_data_ajax() {
			if ( isset( $_POST['nonce'] ) && ! empty( $_POST['nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'learndash-powerpack-modal-nonce-' . get_current_user_id() ) ) {
				if ( isset( $_POST['class_name'] ) ) {
					$class_name = sanitize_text_field( wp_unslash( $_POST['class_name'] ) );
				}

				if ( isset( $_POST['formData'] ) ) {
					$form_data = learndash_powerpack_sanitize_formdata( wp_unslash( $_POST['formData'] ) ); // phpcs:ignore  WordPress.Security.ValidatedSanitizedInput -- Input sanitized in function.
				}

				if ( ! empty( $class_name ) && ! empty( $form_data[0]['name'] ) && ! empty( $form_data[0]['value'] ) ) {
					$update_option = update_option( $class_name, $form_data );

					if ( $update_option ) {
						$return = [
							'message' => esc_html__( 'Data saved successfully.', 'learndash-powerpack' ),
						];
						wp_send_json_success( $return );
						wp_die();
					} else {
						$return = [
							'message' => esc_html__( 'Error. Data could not be saved. Please reload the page and try again.', 'learndash-powerpack' ),
						];
						wp_send_json_error( $return );
						wp_die();
					}
				} else {
					$return = [
						'message' => esc_html__( 'Error. Either no data was provided or data was incomplete.', 'learndash-powerpack' ),
					];
					wp_send_json_error( $return );
					wp_die();
				}
			}
			$return = [
				'message' => esc_html__( 'Error. Nonce verification failed. Please try logging in again.', 'learndash-powerpack' ),
			];
			wp_send_json_error( $return );
			wp_die( esc_html__( "If you receive this error, you've been logged out by WordPress. Please log in and try again.", 'learndash-powerpack' ) );
		}

		/**
		 * Ajax call to delete form content.
		 */
		public function learndash_delete_class_data_ajax() {
			if ( isset( $_POST['nonce'] ) && ! empty( $_POST['nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'learndash-powerpack-modal-nonce-' . get_current_user_id() ) ) {
				if ( isset( $_POST['class_name'] ) ) {
					$class_name = sanitize_text_field( wp_unslash( $_POST['class_name'] ) );
				}

				if ( ! empty( $class_name ) ) {
					$delete_option = delete_option( $class_name );

					if ( $delete_option ) {
						$return = [
							'message' => esc_html__( 'Data successfully deleted.', 'learndash-powerpack' ),
						];
						wp_send_json_success( $return );
						wp_die();
					} else {
						$return = [
							'message' => esc_html__( 'Error. No data to delete.', 'learndash-powerpack' ),
						];
						wp_send_json_error( $return );
						wp_die();
					}
				} else {
					$return = [
						'message' => esc_html__( 'Error. Snippet does not exist.', 'learndash-powerpack' ),
					];
					wp_send_json_error( $return );
					wp_die();
				}
			}
			$return = [
				'message' => esc_html__( 'Error. Nonce verification failed. Please try logging in again.', 'learndash-powerpack' ),
			];
			wp_send_json_error( $return );
			wp_die( esc_html__( "If you receive this error, you've been logged out by WordPress. Please log in and try again.", 'learndash-powerpack' ) );
		}
	}

	new LearnDash_PowerPack_Ajax_Call();
}
