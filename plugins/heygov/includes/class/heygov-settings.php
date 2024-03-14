<?php

class HeyGovSettings {

	function add_admin_menu() {
        add_menu_page(
            'HeyGov Settings',
            'HeyGov',
            'manage_options',
            'heygov_settings',
            [$this, 'render_setting_page'],
            'none',
            30
        );
	}

	function render_setting_page() {
		require_once HEYGOV_DIR . '/includes/view/show-heygov-settings.php';
	}

    function heygov_shortcode(){
        ob_start(); ?>

        <div class="heygov-embed"></div>

        <?php
        $outputString = ob_get_contents();
        ob_end_clean();
    
        return $outputString;
    }


  

}