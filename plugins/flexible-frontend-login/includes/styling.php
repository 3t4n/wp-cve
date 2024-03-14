<?php

/* Add styling from plugin or from theme folder */
add_action('wp_enqueue_scripts', 'paw_ffl_enqueue_styles');

function paw_ffl_enqueue_styles() 
{
	// check if there is a custom css file
	if ( file_exists( get_stylesheet_directory() . '/flexible-frontend-login/ffl-style.css' ) ) 	
	{
		wp_register_style( 'flexible-frontend-login', get_stylesheet_directory_uri() . '/flexible-frontend-login/ffl-style.css', 'screen, projection' );
	}
	elseif ( file_exists( get_template_directory() . '/flexible-frontend-login/ffl-style.css' ) ) 
	{
		wp_register_style( 'flexible-frontend-login', get_template_directory_uri() . '/flexible-frontend-login/ffl-style.css', 'screen, projection' );
	} 
	else 
	{
		wp_register_style( 'flexible-frontend-login', FFL_URL . '/css/ffl-style.css' , 'screen, projection' );
	}
	
	wp_enqueue_style( 'flexible-frontend-login' );
}



?>
