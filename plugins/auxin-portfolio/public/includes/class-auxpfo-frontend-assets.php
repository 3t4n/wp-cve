<?php
/**
 * Load frontend scripts and styles
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://averta.net/phlox/
 * @copyright  (c) 2010-2023 averta
 */

/**
* Constructor
*/
class AUXPFO_Frontend_Assets {


	/**
	 * Construct
	 */
	public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'load_styles' ), 16 );
        add_action( 'wp_enqueue_scripts', array( $this, 'localize_scripts' ) );
    }

    public function localize_scripts() {
        wp_localize_script( AUXPFO_SLUG .'-portfolio', 'auxpfo', array(
                'ajax_url'          => admin_url( 'admin-ajax.php' ),
                'invalid_required'  => __( 'This is a required field', 'auxin-portfolio' ),
                'invalid_postcode'  => __( 'Zipcode must be digits', 'auxin-portfolio' ),
                'invalid_phonenum'  => __( 'Enter a valid phone number', 'auxin-portfolio' ),
                'invalid_emailadd'  => __( 'Enter a valid email address', 'auxin-portfolio' )
            )
        );
    }

    /**
     * Styles for admin
     *
     * @return void
     */
    public function load_styles() {
        if( current_theme_supports( 'auxin-portfolio' ) ){
            wp_enqueue_style( 'auxin-portfolio' , get_template_directory_uri() . '/css/portfolio.css', array('auxin-main'), AUXPFO_VERSION );
        }
        //wp_enqueue_style( AUXPFO_SLUG .'-main',   AUXPFO_PUB_URL . '/assets/css/main.css',  array(), AUXPFO_VERSION );
    }

    /**
     * Scripts for admin
     *
     * @return void
     */
    public function load_scripts() {
        wp_enqueue_script( AUXPFO_SLUG .'-portfolio', AUXPFO_PUB_URL . '/assets/js/portfolio.js', array('jquery'), AUXPFO_VERSION, true );
    }

}
return new AUXPFO_Frontend_Assets();
