<?php

namespace QuadLayers\IGG\Models;

use QuadLayers\IGG\Models\Base as Models_Base;

/**
 * Models_Setting Class
 */
class Setting extends Models_Base {

	/**
	 * Table name
	 *
	 * @var string
	 */
	protected $table = 'insta_gallery_settings';

	/* CRUD */

	/**
	 * Function to get default args
	 *
	 * @return array
	 */
	public function get_args() {
		return array(
			'insta_flush'       => false,
			'insta_reset'       => 8,
			'spinner_image_url' => '',
			'mail_to_alert'     => get_option( 'admin_email' ),
		);
	}

	/**
	 * Function to get all settings
	 *
	 * @return array
	 */
	public function get() {
		$settings = wp_parse_args( $this->get_all(), $this->get_args() );
		return $settings;
	}

	/**
	 * Function to save settings
	 *
	 * @param array $settings Settings to be saved.
	 * @return boolean
	 */
	public function save( $settings = null ) {
		return $this->save_all( $settings );
	}

	/**
	 * Function to delete table
	 *
	 * @return void
	 */
	public function delete_table() {
		$this->delete_all();
	}
}
