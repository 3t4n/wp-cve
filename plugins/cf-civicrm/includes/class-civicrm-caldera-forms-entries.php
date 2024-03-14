<?php

/**
 * CiviCRM Caldera Forms Entries Class.
 *
 * @since 0.4.2
 */
class CiviCRM_Caldera_Forms_Entries {

	/**
	 * Plugin reference.
	 *
	 * @since 0.4.4
	 */
	public $plugin;

	/**
	 * Initialises this object.
	 *
	 * @since 0.4.2
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		// register Caldera Forms callbacks
		$this->register_hooks();

	}

	/**
	 * Register hooks.
	 *
	 * @since 0.4.2
	 */
	public function register_hooks() {

		add_filter( 'caldera_forms_get_entry', [ $this, 'get_entry' ], 10, 3 );

	}

	public function get_entry( $data, $entry_id, $form ) {

		foreach ( $data['data'] as $field_id => $values ) {
			$field = Caldera_Forms_Field_Util::get_field( $field_id, $form );
			if ( ! empty( $values['value'] ) && $field['type'] == 'file' && isset( $field['config']['civicrm_file_upload'] ) ) {
				try {
					$attachment = civicrm_api3( 'Attachment', 'getsingle', [
  						'id' => $values['value'],
					] );
				} catch ( CiviCRM_API3_Exception $e ) {

				}
				if ( isset( $attachment ) && ! $attachment['is_error'] ) {
					$data['data'][$field_id]['view'] = '<a href="' . $attachment['url'] . '" target="_blank">' . $attachment['name'] . '</a>';
				}
				unset( $attachment );
			}

		}

		return $data;
	}
}
