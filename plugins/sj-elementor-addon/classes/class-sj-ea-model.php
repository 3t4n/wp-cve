<?php 
if ( ! class_exists( 'SJEaModel' ) ) {
	
	/**
	* Responsible for include modules.
	*
	* @since 0.1
	*/
	final class SJEaModel {
		/**
		 * Initialize hooks.
		 *
		 * @since 0.1 
		 * @return void
		 */
		static public function init() {
			
			add_action('elementor/widgets/widgets_registered', __CLASS__ . '::add_modules');
		}

		/**
		 * Initialize Modules.
		 *
		 * @since 0.1 
		 * @return void
		 */
		static public function add_modules() {
			
			include	 SJ_EA_DIR . 'modules/sjea-row-separator/sjea-row-separator.php';
			include	 SJ_EA_DIR . 'modules/sjea-image-separator/sjea-image-separator.php';
			include	 SJ_EA_DIR . 'modules/sjea-subscribe-form/sjea-subscribe-form.php';
		}		
	}

	SJEaModel::init();
}
