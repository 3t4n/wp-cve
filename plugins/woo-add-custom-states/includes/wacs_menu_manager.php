<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wacs_Menu_Manager {

	function wacs_add_menu_item() {
        add_menu_page(
            'Woo Add Custom States',
            'Woo Add Custom States',
            'manage_options',
            'wacs_add_states',
            'wacs_settings_page'
        );
    }

}