<?php

namespace Directorist_WPML_Integration\Controller\Setup;

class Update_Options {
    
    /**
	 * Constuctor
	 * 
     * @return void
	 */
	function __construct() {
        $this->enable_multidirectory();
	}

    /**
     * Enable multi directory
     * 
     * @return void
     */
	protected function enable_multidirectory() {

        if ( ! is_admin() ) {
            return;
        }

        $is_multidirectory_enebled = get_directorist_option( 'enable_multi_directory', false );

        if ( $is_multidirectory_enebled ) {
            return;
        }

        update_directorist_option( 'enable_multi_directory', true );
    }

}