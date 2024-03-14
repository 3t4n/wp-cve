<?php
/**
 * Custom Scrollbar
 * 
 * http://en.michaeluno.jp/custom-scrollbar/
 * Copyright (c) 2015-2016 Michael Uno
 * 
 */

/**
 * Handles plugin options.
 * 
 * @since       1
 */
class CustomScrollbar_Option extends CustomScrollbar_Option_Base {

    /**
     * Stores instances by option key.
     * 
     * @since        1
     */
    static public $aInstances = array(
        // key => object
    );

    /**
     * Returns the instance of the class.
     * 
     * This is to ensure only one instance exists.
     * 
     * @since      3
     */
    static public function getInstance( $sOptionKey='' ) {
        
        $sOptionKey = $sOptionKey 
            ? $sOptionKey
            : CustomScrollbar_Registry::$aOptionKeys[ 'setting' ];
        
        if ( isset( self::$aInstances[ $sOptionKey ] ) ) {
            return self::$aInstances[ $sOptionKey ];
        }
        $_sClassName = apply_filters( 
            CustomScrollbar_Registry::HOOK_SLUG . '_filter_option_class_name',
            __CLASS__ 
        );
        self::$aInstances[ $sOptionKey ] = new $_sClassName( $sOptionKey );
        return self::$aInstances[ $sOptionKey ];
        
    }         
        
    /**
     * Checks whether the plugin debug mode is on or not.
     * @return      boolean
     * @deprecated  1.3.0
     */ 
//    public function isDebug() {
//        return defined( 'WP_DEBUG' ) && WP_DEBUG;
//    }
//
}