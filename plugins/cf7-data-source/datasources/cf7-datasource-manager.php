<?php
if ( ! class_exists( 'CF7_DATASOURCE_MANAGER' ) ) {
	class CF7_DATASOURCE_MANAGER {

		private $ds_objs;
		private $caller;
		public function __construct( $caller ) {
			$this->ds_objs = array();
			$this->caller  = $caller;

			$basic_path = dirname( __FILE__ ) . '/cf7-datasource-basic.php';
			if ( file_exists( $basic_path ) ) {
				require_once $basic_path;
				$this->ds_objs[] = new CF7_DATASOURCE_BASIC( $this );
			}

			if ( ! empty( get_option( CF7_DATASOURCE::$plugin . '_LICENSE', '' ) ) ) {
				$advanced_path = dirname( __FILE__ ) . '/cf7-datasource-advanced.php';
				if ( file_exists( $advanced_path ) ) {
					require_once $advanced_path;
					$this->ds_objs[] = new CF7_DATASOURCE_ADVANCED( $this );
				}
			}
		} // End __construct

		public function get_current_post_id() {
			 return $this->caller->get_current_post_id();
		} // End get_current_post

		public function default_datasource_attributes() {
			$attributes = array();
			foreach ( $this->ds_objs as $ds_obj ) {
				$attributes = array_merge( $attributes, $ds_obj->default_attributes() );
			}
			return $attributes;
		} // End default_datasource_attributes

		public function ds( $settings, $data ) {
			if ( $settings['type'] ) {
				$method_name = $settings['type'] . '_ds';
				foreach ( $this->ds_objs as $ds_obj ) {
					if ( method_exists( $ds_obj, $method_name ) ) {
						$settings = $this->replace_characters( $settings );
						return $ds_obj->{$method_name}( $settings, $data );
					}
				}
				$this->error_log( 'Data source type is not supported' );
			} else {
				$this->error_log( 'Data source type is required' );
			}
			return array();
		} // End ds

		public function admin_ds_options() {
			$options = '';
			foreach ( $this->ds_objs as $ds_obj ) {
				if ( method_exists( $ds_obj, 'ds_options' ) ) {
					$options .= $ds_obj->ds_options();
				}
			}
			return $options;
		} // End admin_ds_options

		public function admin_ds_settings() {
			$settings = '';
			foreach ( $this->ds_objs as $ds_obj ) {
				if ( method_exists( $ds_obj, 'ds_settings' ) ) {
					$settings .= $ds_obj->ds_settings();
				}
			}
			return $settings;
		} // End admin_ds_settings

		public function replace_characters( $settings ) {
			foreach ( $settings as $attr => $value ) {
				$settings[ $attr ] = str_replace(
					array( '&#60;', '&#62;', '&#91;', '&#93;', '&#34;' ),
					array( '<', '>', '[', ']', '"' ),
					$value
				);
			}
			return $settings;
		} // End replace_characters

		public function error_log( $mssg ) {
			$this->caller->error_log( $mssg );
		} // End error_log

	} // End class CF7_DATASOURCE_MANAGER
}
