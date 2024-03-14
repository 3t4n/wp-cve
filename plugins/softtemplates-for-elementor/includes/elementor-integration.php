<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    SoftHopper
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Soft_template_Core_Elementor_Integration' ) ) {

	/**
	 * Define Soft_template_Core_Elementor_Integration class
	 */
	class Soft_template_Core_Elementor_Integration {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 */
		public function __construct() {
			add_action( 'elementor/controls/controls_registered', array( $this, 'add_controls' ), 10 );
		}

		/**
		 * Register new controls
		 */
		public function add_controls( $controls_manager ) {

			$controls = array(
				'softtemplate_search' => 'Softtemplate_Control_Search',
			);

			foreach ( $controls as $control_id => $class_name ) {
				if ( $this->include_control( $class_name, false ) ) {
					$class_name = 'Elementor\\' . $class_name;
					$controls_manager->register_control( $control_id, new $class_name() );
				}
			}
		}

		/**
		 * Include control file by class name.
		 *
		 * @param  [type] $class_name [description]
		 * @return [type]             [description]
		 */
		public function include_control( $class_name, $grouped = false ) {

			$filename = sprintf(
				'includes/controls/%2$s%1$s.php',
				str_replace( 'softtemplate_control_', '', strtolower( $class_name ) ),
				( true === $grouped ? 'groups/' : '' )
			);

			if ( ! file_exists( soft_template_core()->plugin_path( $filename ) ) ) {
				return false;
			}

			require soft_template_core()->plugin_path( $filename );

			return true;
		}

	}

}
