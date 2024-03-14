<?php

namespace Directorist_WPML_Integration\Controller\Ajax;

use Directorist_WPML_Integration\Helper;

class Init {
    
    /**
	 * Constuctor
	 * 
     * @return void
	 */
	function __construct() {

		// Register AJAX Controllers
        $ajax_controllers = $this->get_controllers();
        Helper\Serve::register_services( $ajax_controllers );

	}

    /**
     * Get AJAX Controllers
     * 
     * @return array $ajax_controllers
     */
	protected function get_controllers() {
        return [
            Get_Directory_Type_Translations::class,
        ];
    }

}