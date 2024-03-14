<?php
/**
 * Pending Assignments
 *
 * @package learndash-reports-by-wisdmlabs
 */

namespace WisdmReportsLearndashBlockRegistry;

require_once 'class-wrld-register-block.php';
if ( ! class_exists( '\WisdmReportsLearndashBlockRegistry\WRLD_Pending_Assignments' ) ) {
	/**
	 * This class contains the Functionality required to register the Pending Assignments Block
	 */
	class WRLD_Pending_Assignments extends WRLD_Register_Block {
		/**
		 * Constructor.
		 *
		 * @param string $block_name           Set block name during construct.
		 * @param string $block_title          To be displayed in the WP-Admin.
		 * @param string $description          Description of the block.
		 * @param string $server_side_callback Function name, the child class must implement the method specified as this arguement.
		 * @param int    $api_version           Block API Version , default 2.
		 */
		public function __construct( $block_name = 'pending-assignments', $block_title = 'Pending Assignments', $description = 'Total number of pending assignments', $server_side_callback = 'server_side_render_function', $api_version = 2 ) {
			$this->block_name  = $block_name ? $block_name : $this->block_name;
			$this->api_version = $api_version;
			$this->description = $description;
			$this->block_title = $block_title;
			add_filter( 'wisdm_learndash_reports_front_end_script_pending_assignments', array( $this, 'localize_additional_data' ), 11, 1 );
			$this->wrld_register_block_assets();
			$this->wrld_register_block_type();
			$this->server_side_callback = $server_side_callback;
		}

		/**
		 * The function can be used to render the block contenet on the server side.
		 */
		public function server_side_render_function() {
			return 'Html if required';
		}

		/**
		 * This function can be used to localizes the additional data required for the parent block.
		 *
		 * @param array $data Default data to be localized in the script.
		 */
		public function localize_additional_data( $data ) {
			$data['ld_api_settings'] = get_option( 'learndash_settings_rest_api', array() );
			return $data;
		}
	}
}
