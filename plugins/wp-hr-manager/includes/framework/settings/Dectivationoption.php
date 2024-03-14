<?php

use WPHR\HR_MANAGER\Framework\WPHR_Settings_Page;

/**
 * General class
 */

class WPHR_Settings_Deactivation extends WPHR_Settings_Page {
	public function __construct() {
		$this->id = 'deactivation';
		$this->label = __('Deactivation Option', 'wphr');
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */

	public function get_settings() {
		$fields = array(

			array(
				'title' => __('Deactivation Options,
                    Do you want to Delete All Data when Plugin deactivated?', 'wphr'),
				'type' => 'title',
				'desc' => '',
				'id' => 'deactivation_options',
			),
			array(
				'title' => __('Do you want to delete all data when plugin deactivated?', 'wphr'),
				'id' => 'dactivatedata_id',
				'type' => 'checkbox',
				'tooltip' => true,
			),

			// array(
			// 	'title' => __('Do you want to delete all data when plugin deleted?', 'wphr'),
			// 	'id' => 'deletedata_id',
			// 	'type' => 'checkbox',
			// 	'tooltip' => true,
			// ),

			array(
				'type' => 'sectionend',
				'id' => 'script_styling_options',
			),
		);
		return apply_filters('wphr_settings_deactivation', $fields);
	}

}
return new WPHR_Settings_Deactivation();
?>
