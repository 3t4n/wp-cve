<?php
/**
 * Custom Scrollbar
 *
 * [PROGRAM_URI]
 * Copyright (c) 2015-2016 Michael Uno
 *
 */

/**
 * Provides an abstract base for adding pages.
 * 
 * @since       1.3.0
 */
abstract class CustomScrollbar_AdminPage__Element_Base extends CustomScrollbar_PluginUtility {
    
    protected $_oFactory;
    
    protected $_aArguments = array();
    
    /* Common Protected Methods which should be overridden. */
    protected function _construct( $oFactory ) {}
    
    protected function _getArguments( $oFactory ) {
        return array();
    }    
        
    protected function _load( $oFactory ) {}
    
    /* Common Internal Methods */
    /**
     * Called when the in-page tab loads.
     */
    public function replyToLoad( $oFactory ) {
        $this->_load( $oFactory );
    }        
    
}