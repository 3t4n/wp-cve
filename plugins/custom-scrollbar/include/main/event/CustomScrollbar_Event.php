<?php
/**
 * Custom Scrollbar
 * 
 * http://en.michaeluno.jp/custom-scrollbar/
 * Copyright (c) 2015-2016 Michael Uno
 * 
 */

/**
 * Plugin event handler.
 * 
 * @package      Custom Scrollbar
 * @since        1
 */
class CustomScrollbar_Event {

    /**
     * Triggers event actions.
     */
    public function __construct() {

        // This must be called after the above action hooks are added.
        // $_oOption               = CustomScrollbar_Option::getInstance();
// CustomScrollbar_Debug::log( $_oOption->get() );

    }
    
}