<?php
defined( 'ABSPATH' ) or die();
/**
 *  Fonts Features Section 
**/
class wl_Fonts_customizer {
	
	public static function wl_enigma_parallax_Fonts_customizer( $wp_customize ) {

    	/* Fonts Features Section */
    	$wp_customize->add_section(
    	   'font_section',
    	    array(
    	       'title' 		  => __('Fonts Options',WL_COMPANION_DOMAIN),
    			'panel'			  => 'enigma_parallax_theme_option',
    	        'description' 	  => __('Here you can add fonts',WL_COMPANION_DOMAIN),
    			'capability'	  => 'edit_theme_options',
    	        'priority' 		  => 36,
    			//'active_callback' => 'is_front_page',
    	   )
    	);
        
        $font_choices = array(
            'Source Sans Pro' => 'Source Sans Pro',
            'Open Sans' => 'Open Sans',
            'Oswald' => 'Oswald',
            'Playfair Display' => 'Playfair Display',
            'Montserrat' => 'Montserrat',
            'Raleway' => 'Raleway',
            'Droid Sans' => 'Droid Sans',
            'Lato' => 'Lato',
            'Arvo' => 'Arvo',
            'Lora' => 'Lora',
            'Merriweather' => 'Merriweather',
            'Oxygen' => 'Oxygen',
            'PT Serif' => 'PT Serif',
            'PT Sans' => 'PT Sans',
            'PT Sans Narrow' => 'PT Sans Narrow',
            'Cabin' => 'Cabin',
            'Fjalla One',
            'Francois One',
            'Josefin Sans' => 'Josefin Sans',
            'Libre Baskerville' => 'Libre Baskerville',
            'Arimo' => 'Arimo',
            'Ubuntu' => 'Ubuntu',
            'Bitter' => 'Bitter',
            'Droid Serif' => 'Droid Serif',
            'Roboto' => 'Roboto',
            'Open Sans Condensed' => 'Open Sans Condensed',
            'Roboto Condensed' => 'Roboto Condensed',
            'Roboto Slab' => 'Roboto Slab',
            'Yanone Kaffeesatz' => 'Yanone Kaffeesatz',
            'Rokkitt' => 'Rokkitt',
        );
      
        $wp_customize->add_setting('enigma_parallax_show_Google_Fonts',
            array(
                'sanitize_callback' => 'enigma_parallax_sanitize_checkbox_function',
                'default'           => 0,
            )
        );

        $wp_customize->add_control('enigma_parallax_show_Google_Fonts',
            array(
                'type'        => 'checkbox',
                'label'       => esc_html__('Enable Fonts', 'enigma_parallax'),
                'section'     => 'font_section',
                'description' => esc_html__('Check this box to Enable Custom Fonts', 'enigma_parallax'),
            )
        );

        $wp_customize->add_setting( 'font_family', array(
            'default' =>'',
            'sanitize_callback' => 'enigma_parallax_Theme_Fonts_Sanitize_Text_Function',
            )
        );

        $wp_customize->add_control( 'font_family', array(
            'type' => 'select',
            'label' => __('Select your desired font family for heading','enigma_parallax'),
            'section' => 'font_section',
            'choices' => $font_choices
            )
        );

        $wp_customize->add_setting( 'font_family2', array(
            'default' =>'',
            'sanitize_callback' => 'enigma_parallax_Theme_Fonts_Sanitize_Text_Function',
            )
        );

        $wp_customize->add_control( 'font_family2', array(
            'type' => 'select',
            'label' => __('Select your desired font family for body','enigma_parallax'),
            'section' => 'font_section',
            'choices' => $font_choices
            )
        );

        // Enigma Parallax Fonts Sanitize Function
        function enigma_parallax_Theme_Fonts_Sanitize_Text_Function( $text ) {
            return sanitize_text_field( $text );
        }


        //////////////////////////////////////////////////////
        function enigma_parallax_sanitize_checkbox_function( $checked ) {
            // Boolean check.
            return ( ( isset( $checked ) && true == $checked ) ? true : false );
        }
	  

	} 
}
?>
<?php
        function enigma_parallax_script() {
            $headings_font = esc_html(get_theme_mod('font_family'));
            $body_font = esc_html(get_theme_mod('font_family2'));

            if( $headings_font ) {
                wp_enqueue_style( 'enigma-parallax-headings-fonts', '//fonts.googleapis.com/css?family='. $headings_font );
            } else {
                wp_enqueue_style( 'enigma-parallax-source-sans', '//fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic');
            }
            if( $body_font ) {
                wp_enqueue_style( 'enigma-parallax-body-fonts', '//fonts.googleapis.com/css?family='. $body_font );
            } else {
                wp_enqueue_style( 'enigma-parallax-source-body', '//fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,700,600');
            }
        }
        add_action( 'wp_enqueue_scripts', 'enigma_parallax_script' );