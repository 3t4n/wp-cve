<?php
/**
 * DB Upgrader class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'LaStudio_Kit_DB_Upgrader' ) ) {

	/**
	 * Define LaStudio_Kit_DB_Upgrader class
	 */
	class LaStudio_Kit_DB_Upgrader {

		/**
		 * Setting key
		 *
		 * @var string
		 */
		public $key = null;

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			$this->key = lastudio_kit_settings()->key;

			/**
			 * Plugin initialized on new LaStudio_Kit_DB_Upgrader call.
			 * Please ensure, that it called only on admin context
			 */
			$this->init_upgrader();
		}

		/**
		 * Initialize upgrader module
		 *
		 * @return void
		 */
		public function init_upgrader() {

		    return;

			$db_updater_data = lastudio_kit()->module_loader->get_included_module_data( 'cx-db-updater.php' );

			new CX_DB_Updater(
				array(
					'path'      => $db_updater_data['path'],
					'url'       => $db_updater_data['url'],
					'slug'      => 'lastudio-element-kit',
					'version'   => '1.0.3',
					'callbacks' => array(
						'1.0.3' => array(
							array( $this, 'update_db_1_0_3' ),
						),
					),
					'labels'    => array(
						'start_update' => esc_html__( 'Start Update', 'lastudio-kit' ),
						'data_update'  => esc_html__( 'Data Update', 'lastudio-kit' ),
						'messages'     => array(
							'error'   => esc_html__( 'Module DB Updater init error in %s - version and slug is required arguments', 'lastudio-kit' ),
							'update'  => esc_html__( 'We need to update your database to the latest version.', 'lastudio-kit' ),
							'updated' => esc_html__( 'Update complete, thank you for updating to the latest version!', 'lastudio-kit' ),
						),
					),
				)
			);
		}

		/**
		 * Update db updater 1.0.3
		 *
		 * @return void
		 */
		public function update_db_1_0_3() {
			if ( class_exists( 'Elementor\Plugin' ) ) {
				lastudio_kit()->elementor()->files_manager->clear_cache();
			}
		}
	}

}
