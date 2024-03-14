<?php

//Activate PRO version
if ( !function_exists( 'elpt_activate' ) ) {
    //Flush rewrite rules after plugin activation
    function elpt_activate()
    {
        if ( !get_option( 'elpt_flush_rewrite_rules_flag' ) ) {
            add_option( 'elpt_flush_rewrite_rules_flag', true );
        }
    }  
    register_activation_hook( __FILE__, array( 'Powerfolio_Portfolio', 'add_cpt_support_for_elementor' ) );  
}

//Turn text into a slug
if ( !function_exists( 'elpt_get_text_slug' ) ) {
	function elpt_get_text_slug($text) {
		// strip out all whitespace
		$text = preg_replace('/\s+/', '_', $text);
		// convert the string to all lowercase
		$text = strtolower($text);

		return $text;
	}
}