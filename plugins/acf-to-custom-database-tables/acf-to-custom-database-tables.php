<?php
/*
Plugin Name: ACF to Custom Database Tables
Plugin URI: https://acf-custom-tables.abhisheksatre.com/?ref=plugin
Description: An add-on plugin for Advanced Custom Fields plugin which lets you save custom fields data in a custom database table.
Version: 1.0.6
Author: Abhishek Satre
Author URI: https://www.abhisheksatre.com/?ref=acfct
*/

if (!defined('ABSPATH')) exit;

if (!class_exists('ACF_to_custom_table')) :

	class ACF_to_custom_table
	{

		public $settings = array();

		public function initialize()
		{

			$this->define('ACF_CUSTOM_TABLE_PATH', plugin_dir_path(__FILE__));
			$this->define('ACF_CUSTOM_TABLE_URL', plugin_dir_url(__FILE__));
			$this->define('ACF_CT_BASE_NAME', plugin_basename(__FILE__));
			$this->define('ACF_CT_VERSION', '1.0.6');
			$this->define('ACF_CT_FREE_PLUGIN', true);
			$this->define('ACF_CT_PLUGIN_NAME', 'ACF To Custom Database Tables');
			$this->define('ACF_CUSTOM_TABLE_POST_ID_COLUMN', 'post_id');
			$this->define('ACF_CT_TABLE_NAME', 'acf_ct_table_name');
			$this->define('ACF_CT_ENABLE', 'acf_ct_enable');
			$this->define('ACF_CT_ADMIN_PAGE', admin_url('edit.php?post_type=acf-field-group&page=acf-custom-table'));
			$this->define('ACF_CT_LOG_KEY', 'acf_ct_log');

			$this->settings = array(
				'name' => ACF_CT_PLUGIN_NAME,
				'path' => ACF_CUSTOM_TABLE_PATH
			);
			include_once(ACF_CUSTOM_TABLE_PATH . 'includes/include.php');
		}

		public function define($name, $value = true)
		{
			if (!defined($name)) {
				define($name, $value);
			}
		}

	}

	/**
	 * Init plugin
	 * @return ACF_to_custom_table
	 */
	function acfCustomTable()
	{
		global $acfCustomTable;

		// Instantiate only once.
		if (!isset($acfCustomTable)) {
			$acfCustomTable = new ACF_to_custom_table();
			$acfCustomTable->initialize();

			register_activation_hook(__FILE__, function () {
				Acf_ct_update::activate();
			});

		}
		return $acfCustomTable;
	}

	acfCustomTable();

endif; // class_exists check
