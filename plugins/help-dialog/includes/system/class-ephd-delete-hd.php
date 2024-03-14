<?php

/**
 * Delete some or all Help Dialog data
 */
class EPHD_Delete_HD {

	private $message = array(); // error/warning/success messages

	function __construct() {
		// Handle manage HD buttons and other, set messages here
		$this->handle_form_actions();
	}

	/**
	 * Return HTML form to delete all Help Dialog data
	 *
	 * @return false|string
	 */
	public function get_delete_all_help_dialog_data_form() {

		// only administrators can handle this page
		if ( ! current_user_can('manage_options') ) {
			return '';
		}

		ob_start();

		// Deletion message
		if ( get_transient( '_ephd_delete_all_hd_data' ) ) {    ?>
			<div class="ephd-delete-all-data__message">
				<p><?php esc_html_e( 'All data will be deleted upon plugin uninstallation.', 'help-dialog' ); ?></p>
			</div>      <?php

		// Deletion form
		} else {    ?>
			<form class="ephd-delete-all-data__form" action="" method="post">

				<p class="ephd-delete-all-data__form-title"><?php echo sprintf( esc_html__( 'Write "%s" in the below input box if you want to delete ALL Help Dialog data when plugin uninstalled. ' .
				                                                                    'This includes Widgets, Questions, Help Dialog options.', 'help-dialog' ), 'delete' ); ?></p>    <?php

				EPHD_HTML_Elements::text_basic( array(
					'value' => '',
					'name'    => 'delete_text',
				) );
				EPHD_HTML_Elements::submit_button_v2( esc_html__( 'Delete All', 'help-dialog' ), 'ephd_delete_all', '', '', true, '', 'ephd-error-btn' );   ?>

			</form>     <?php
		}

		// show any notifications
		foreach ( $this->message as $class => $message ) {
			echo EPHD_HTML_Forms::notification_box_bottom( $message, '', $class );
		}

		return ob_get_clean();
	}

	// Handle actions that need reload of the page - manage tab and other from addons
	private function handle_form_actions() {

		// ensure user wants to delete the Help Dialog data
		$action = EPHD_Utilities::post( 'action' );
		if ( ! in_array( $action, ['ephd_delete_all', 'ephd_reset_all'] ) ) {
			return;
		}

		// verify that request is authentic
		if ( ! isset( $_REQUEST['_wpnonce_ephd_ajax_action'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce_ephd_ajax_action'], '_wpnonce_ephd_ajax_action' ) ) {
			$this->message['error'] = __( 'Error occurred', 'help-dialog' ) . ' (1)';
			return;
		}

		// ensure user has correct permissions
		if ( ! current_user_can( 'manage_options' ) ) {
			$this->message['error'] = __( 'You do not have permission.', 'help-dialog' );
			return;
		}

		// clear any messages
		$this->message = array();

		// reset configs
		$this->reset_all_handler( $action );
		$this->delete_all_handler( $action );
	}

	private function reset_all_handler( $action ) {

		if ( $action != 'ephd_reset_all' ) {
			return;
		}

		// ensure user typed delete word
		if ( EPHD_Utilities::post( 'reset_text' ) != 'reset' ) {
			$this->message['error'] = sprintf( __( 'Write "%s" in input box to delete ALL Help Dialog configuration', 'help-dialog' ), 'reset' );
			return;
		}

		self::reset_config();

		$this->message['success'] = __( 'All configs were reset.', 'help-dialog' );
	}

	private function delete_all_handler( $action ) {

		if ( $action != 'ephd_delete_all' ) {
			return;
		}

		// delete HD data
		delete_transient( '_ephd_delete_all_hd_data' );

		// ensure user typed delete word
		if ( EPHD_Utilities::post( 'delete_text' ) != 'delete' ) {
			$this->message['error'] = sprintf( __( 'Write "%s" in input box to delete ALL Help Dialog configuration', 'help-dialog' ), 'delete' );
			return;
		}

		// set option for deletion - execute deletion on plugin uninstallation
		set_transient( '_ephd_delete_all_hd_data', true, DAY_IN_SECONDS );

		$this->message['success'] = __( 'All data will be deleted upon plugin uninstallation.', 'help-dialog' );
	}

	/**
	 * Return HTML for Reset Configuration button
	 *
	 * @return false|string
	 */
	public static function get_reset_config_button() {

		// only administrators can reset configuration
		if ( ! current_user_can('manage_options') ) {
			return '';
		}

		ob_start(); ?>

		<form class="ephd-reset-all-configs__form" action="" method="post"> <?php
			EPHD_HTML_Elements::submit_button_v2( esc_html__( 'Reset Configuration', 'help-dialog' ), 'ephd_reset_config', '', '', true, '', 'ephd-error-btn' );   ?>
		</form>     <?php

		return ob_get_clean();
	}

	/**
	 * Handle request for Reset Configuration button if defined
	 */
	public static function reset_config_button_handler() {

		// only administrators can reset configuration
		if ( ! current_user_can('manage_options') ) {
			return;
		}

		$action = EPHD_Utilities::post( 'action' );
		if ( $action != 'ephd_reset_config' ) {
			return;
		}

		// verify that request is authentic
		if ( ! isset( $_REQUEST['_wpnonce_ephd_ajax_action'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce_ephd_ajax_action'], '_wpnonce_ephd_ajax_action' ) ) {
			return;
		}

		self::reset_config();
	}

	/**
	 * Reset HD configuration
	 */
	private static function reset_config() {

		// set defaults
		ephd_get_instance()->widgets_config_obj->reset_config();

		$global_config = EPHD_Config_Specs::get_default_hd_config( EPHD_Config_DB::EPHD_GLOBAL_CONFIG_NAME );
		ephd_get_instance()->global_config_obj->update_config( $global_config );
	}
}