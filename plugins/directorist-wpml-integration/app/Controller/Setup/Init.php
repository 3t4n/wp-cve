<?php

namespace Directorist_WPML_Integration\Controller\Setup;

use Directorist_WPML_Integration\Helper;

class Init {
    
    /**
	 * Constuctor
	 * 
     * @return void
	 */
	function __construct() {

		// Register Controllers
        $controllers = $this->get_controllers();
        Helper\Serve::register_services( $controllers );

	}

    /**
     * Get Controllers
     * 
     * @return array $controllers
     */
	protected function get_controllers() {
        return [
            Update_Options::class,
        ];
    }

}