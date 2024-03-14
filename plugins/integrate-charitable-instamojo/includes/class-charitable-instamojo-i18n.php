<?php
/**
 * Sets up translations for Charitable Instamojo.
 *
 * @package     Charitable/Classes/Charitable_i18n
 * @version     1.0.0
 * @author      Gautam Garg
 * @copyright   Copyright (c) 2018, GautamMKGarg
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/* Ensure that Charitable_i18n exists */
if ( ! class_exists( 'Charitable_i18n' ) ) : 
    return;
endif;

if ( ! class_exists( 'Charitable_Instamojo_i18n' ) ) : 

/**
 * Charitable_Instamojo_i18n
 *
 * @since       1.0.0
 */
class Charitable_Instamojo_i18n extends Charitable_i18n {

    /**
     * @var     string
     */
    protected $textdomain = 'charitable-instamojo';

    /**
     * Set up the class. 
     *
     * @access  protected
     * @since   1.0.0
     */
    protected function __construct() {
        $this->languages_directory = apply_filters( 'charitable_stripe_languages_directory', 'charitable-instamojo/languages' );
        $this->locale = apply_filters( 'plugin_locale', get_locale(), $this->textdomain );
        $this->mofile = sprintf( '%1$s-%2$s.mo', $this->textdomain, $this->locale );

        $this->load_textdomain();
    }
    
    public function charitable_start() {
        
    }
}

endif;