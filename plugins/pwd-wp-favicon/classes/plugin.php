<?php
/**
 * Contains methods for customizing the theme customization screen.
 * 
 * @link http://codex.wordpress.org/Theme_Customization_API
 * @since 1.0
 */

class PWD_FAVICON_Plugin {

	public function __construct() {
		add_action( 'customize_register' , array( __CLASS__, 'register' ) );

		// Add Favicon to website frontend
		add_action( 'wp_head', array( __CLASS__, 'pwd_favicon_frontend' ) );

		// Add Favicon to website backend
		add_action( 'admin_head', array( __CLASS__, 'pwd_favicon_backend' ) );
		add_action( 'login_head', array( __CLASS__, 'pwd_favicon_backend' ) );
	}

	public static function register( $wp_customize ) {
		if(method_exists('WP_Customize_Manager', 'add_panel')) {
			$wp_customize->add_panel( 'panel_custom_favicon', array(
	            'priority'   => 35,
	            'title'      => __('Favicon', 'pwd-wp-favicon'),
	            'capability' => 'edit_theme_options',
	        ) );
		}
        $wp_customize->add_section( 'custom_favicon_section', array(
            'title'     => __('Favicon', 'pwd-wp-favicon'),
            'priority'  => 35,
            'panel'	 	=> 'panel_custom_favicon'
        ));

        /*Favicon*/
        $wp_customize->add_setting('pwd_favicon');
        $wp_customize->add_control( new WP_Customize_Image_Control(
            $wp_customize,
            'pwd_favicon',
            array(
                'label'    => __( 'Add a favicon for website size 16*16 or 32*32', 'pwd-wp-favicon' ),
                'section'  => 'custom_favicon_section',
                'settings' => 'pwd_favicon',
            )
        ));
        $wp_customize->add_setting('pwd_admin_favicon');
        $wp_customize->add_control( new WP_Customize_Image_Control(
            $wp_customize,
            'pwd_admin_favicon',
            array(
                'label'    => __( 'Add a favicon for admin size 16*16 or 32*32', 'pwd-wp-favicon' ),
                'section'  => 'custom_favicon_section',
                'settings' => 'pwd_admin_favicon',
            )
        ));

        /*Apple Touch Icon*/
        $wp_customize->add_setting('pwd_apple_favicon');
        $wp_customize->add_control( new WP_Customize_Image_Control(
            $wp_customize,
            'pwd_apple_favicon',
            array(
                'label'    => __( 'Add a Apple Touch Icon for website size 16*16 or 32*32', 'pwd-wp-favicon' ),
                'section'  => 'custom_favicon_section',
                'settings' => 'pwd_apple_favicon',
            )
        ));
        $wp_customize->add_setting('pwd_apple_admin_favicon');
        $wp_customize->add_control( new WP_Customize_Image_Control(
            $wp_customize,
            'pwd_apple_admin_favicon',
            array(
                'label'    => __( 'Add a Apple Touch Icon for admin size 16*16 or 32*32', 'pwd-wp-favicon' ),
                'section'  => 'custom_favicon_section',
                'settings' => 'pwd_apple_admin_favicon',
            )
        ));
        $wp_customize->add_setting('pwd_apple_basic_favicon');
        $wp_customize->add_control( new WP_Customize_Control(
            $wp_customize,
            'pwd_apple_basic_favicon',
            array(
                'label'    => __( 'Disable Curved Border & reflective shine for Apple touch icon', 'pwd-wp-favicon' ),
                'section'  => 'custom_favicon_section',
                'settings' => 'pwd_apple_basic_favicon',
                'type'	   => 'checkbox'
            )
        ));
	}

	// Add Favicon to website frontend
	public static function pwd_favicon_frontend() {
		$pwd_favicon 				= get_theme_mod('pwd_favicon');
		$pwd_apple_favicon 			= get_theme_mod('pwd_apple_favicon');
		$pwd_apple_basic_favicon 	= get_theme_mod('pwd_apple_basic_favicon');

		if( !empty($pwd_favicon) ) {
	        echo '<link rel="shortcut icon" href="'.  esc_url( $pwd_favicon )  .'"/>'."\n";
	    }

	    if( !empty($pwd_apple_favicon) ) {
	    	if ( empty($pwd_apple_basic_favicon) ) {
	        	echo '<link rel="apple-touch-icon" href="'.  esc_url( $pwd_apple_favicon )  .'"/>'."\n";
	    	} else {
	    		echo '<link rel="apple-touch-icon-precomposed" href="'.  esc_url( $pwd_apple_favicon )  .'"/>'."\n";
	    	}
	    }
	}

	// Add Favicon to website backend
	public static function pwd_favicon_backend() {
		$pwd_admin_favicon 			= get_theme_mod('pwd_admin_favicon');
		$pwd_apple_admin_favicon 	= get_theme_mod('pwd_apple_admin_favicon');

		if( !empty($pwd_admin_favicon) ) {
	        echo '<link rel="shortcut icon" href="'.  esc_url( $pwd_admin_favicon )  .'"/>'."\n";
	    }

	    if( !empty($pwd_apple_admin_favicon) ) {
			if ( empty($pwd_apple_basic_favicon) ) {
	        	echo '<link rel="apple-touch-icon" href="'.  esc_url( $pwd_apple_admin_favicon )  .'"/>'."\n";
	    	} else {
	    		echo '<link rel="apple-touch-icon-precomposed" href="'.  esc_url( $pwd_apple_admin_favicon )  .'"/>'."\n";
	    	}
	    }
	}
}