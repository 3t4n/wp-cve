<?php

namespace Directorist_WPML_Integration\Controller\Hook;

use Directorist_WPML_Integration\Helper;

class Init {
	
    /**
     * Constuctor
     * 
     * @return void
     */
    function __construct() {

        // Register Hooks
        $hooks = $this->get_hooks();
        Helper\Serve::register_services( $hooks );

    }

    /**
     * Get Hooks
     * 
     * @return array $hooks
     */
    protected function get_hooks() {
        return [
            Filter_Permalinks::class,
            Directory_Builder_Actions::class,
            Listings_Actions::class,
        ];
    }
}