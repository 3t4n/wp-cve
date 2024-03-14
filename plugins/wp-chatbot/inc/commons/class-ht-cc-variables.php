<?php
/**
 * Varibales to use among plugin - try to avoid globals .. 
 * replaced variables.php 
 * 
 * @method get_option retuns options table 'htcc_options' values
 * 
 * use like .. 
 * 
 * ht_cc()->variables->get_option['enable'];
 * or
 * $values = ht_cc()->variables->get_option;
 *      $values["enable"];
 *      $values["fb_app_id"];
 * 
 */


if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'HT_CC_Variables' ) ) :

class HT_CC_Variables {

    /**
     * db options table - htcc_options values
     * 
     * @var array get_options htcc_options
     * 
     * 
   
     */
    public $get_option;


    public function __construct() {
        $this->get_option();
    }

    public function get_option() {
        $this->get_option =  get_option('htcc_custom_options');
    }

    // public function ccw_enable() {
    //     $ccw_enable = esc_attr( $this->get_option['enable'] );
    //     return $ccw_enable;
    // }



}

endif; // END class_exists check
