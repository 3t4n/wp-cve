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

if ( ! class_exists( 'Soft_template_Core_Config' ) ) {

	/**
	 * Define Soft_template_Core_Config class
	 */
	class Soft_template_Core_Config {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Config holder
		 *
		 * @var array
		 */
		private $config = array();


		/**
		 * Constructor for the class
		 */
		public function __construct() {

			// register default config
			$this->config =  array(
				'dashboard_page_name' => esc_html__( 'SoftTemplate', 'soft-template-core' ),
				'documentation' => '#',
				'editor' => array(
					'template_before' => '',
					'template_after'  => '',
				),
				'library_button' => '',
				'menu_icon' => 'dashicons-index-card',
				'library' => array(
					'version' => '1.0.0',
					'tabs'    => array(
						'softtemplate_header'  => '',
						'softtemplate_footer'  => '',
						'softtemplate_section' => '',
						'softtemplate_page'    => '',
					),
					'keywords' => array(),
				),
				'skins' => array(
					'enabeld' => true,
					'synch'   => true,
				),
			);

			/**
			 * Register custom config on this hook
			 */
			do_action( 'soft-template-core/register-config', $this );
		}

		/**
		 * Register custom config from theme or plugin
		 *
		 * @param  array $config Config to register
		 * @return void
		 */
		public function register_config( $config ) {

			foreach ( $config as $key => $data ) {

				if ( ! empty( $this->config[ $key ] ) ) {
					if ( is_array( $this->config[ $key ] ) ) {
						$this->config[ $key ] = array_merge( $this->config[ $key ], $data );
					} else {
						$this->config[ $key ] = $data;
					}
				} else {
					$this->config[ $key ] = $data;
				}

			}

		}

		/**
		 * Returns config value by key
		 *
		 * @param  string $key Key to get.
		 * @return mixed
		 */
		public function get( $key = '' ) {
			return isset( $this->config[ $key ] ) ? $this->config[ $key ] : false;
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

}
