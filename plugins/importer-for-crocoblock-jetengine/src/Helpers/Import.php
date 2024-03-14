<?php

namespace CodingChicken\Importer\JetEngine\Helpers;

if( !class_exists('CodingChicken\Importer\JetEngine\Helpers\Import')) {
	class Import
	{
		public function __construct()
		{

		}

		public function get_post_type() {
			global $argv;
			$import_id = false;
			/**
			 * Show fields based on post type
			 **/

			$custom_type = false;

			if ( ! empty( $argv ) ) {
				if ( isset( $argv[3] ) ) {
					$import_id = intval($argv[3]);
				}
			}

			if ( ! $import_id ) {
				// Get import ID from URL or set to 'new'
				if ( isset( $_GET['import_id'] ) ) {
					$import_id = intval($_GET['import_id']);
				} elseif ( isset( $_GET['id'] ) ) {
					$import_id = intval($_GET['id']);
				}

				if ( empty( $import_id ) ) {
					$import_id = 'new';
				}
			}

			// Declaring $wpdb as global to access database
			global $wpdb;

			// Get values from import data table
			$imports_table = $wpdb->prefix . 'pmxi_imports';

			// Get import session from database based on import ID or 'new'
			$import_options = $wpdb->get_row( $wpdb->prepare("SELECT options FROM $imports_table WHERE id = %d", $import_id), ARRAY_A );

			// If this is an existing import load the custom post type from the array
			if ( ! empty($import_options) )	{
				$import_options_arr = unserialize($import_options['options']);
				$custom_type = $import_options_arr['custom_type'];
			} else {
				// If this is a new import get the custom post type data from the current session
				$import_options = $wpdb->get_row( $wpdb->prepare("SELECT option_name, option_value FROM $wpdb->options WHERE option_name = %s", '_wpallimport_session_' . $import_id . '_'), ARRAY_A );
				$import_options_arr = empty($import_options) ? array() : unserialize($import_options['option_value']);
				$custom_type = empty($import_options_arr['custom_type']) ? '' : $import_options_arr['custom_type'];
			}

			return $custom_type;
		}

		public function get_taxonomy_type() {
			global $argv;
			$import_id = false;
			/**
			 * Show fields based on post type
			 **/

			$custom_type = false;

			if ( ! empty( $argv ) ) {
				if ( isset( $argv[3] ) ) {
					$import_id = $argv[3];
				}
			}

			if ( ! $import_id ) {
				// Get import ID from URL or set to 'new'
				if ( isset( $_GET['import_id'] ) ) {
					$import_id = intval($_GET['import_id']);
				} elseif ( isset( $_GET['id'] ) ) {
					$import_id = intval($_GET['id']);
				}

				if ( empty( $import_id ) ) {
					$import_id = 'new';
				}
			}

			// Declaring $wpdb as global to access database
			global $wpdb;

			// Get values from import data table
			$imports_table = $wpdb->prefix . 'pmxi_imports';

			// Get import session from database based on import ID or 'new'
			$import_options = $wpdb->get_row( $wpdb->prepare("SELECT options FROM $imports_table WHERE id = %d", $import_id), ARRAY_A );

			// If this is an existing import load the custom post type from the array
			if ( ! empty($import_options) )	{
				$import_options_arr = unserialize($import_options['options']);
				$taxonomy_type = $import_options_arr['taxonomy_type'];
			} else {
				// If this is a new import get the custom post type data from the current session
				$import_options = $wpdb->get_row( $wpdb->prepare("SELECT option_name, option_value FROM $wpdb->options WHERE option_name = %s", '_wpallimport_session_' . $import_id . '_'), ARRAY_A );
				$import_options_arr = empty($import_options) ? array() : unserialize($import_options['option_value']);
				$taxonomy_type = empty($import_options_arr['taxonomy_type']) ? '' : $import_options_arr['taxonomy_type'];
			}

			return $taxonomy_type;
		}


	}
}
