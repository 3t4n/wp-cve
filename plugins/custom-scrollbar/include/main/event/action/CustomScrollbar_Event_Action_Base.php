<?php
/**
 * Custom Scrollbar
 * 
 * http://en.michaeluno.jp/custom-scrollbar/
 * Copyright (c) 2015-2016 Michael Uno
 * 
 */

/**
 * Provides base methods for plugin event actions.
 
 * @package      Custom Scrollbar
 * @since        1
 */
abstract class CustomScrollbar_Event_Action_Base extends CustomScrollbar_WPUtility {
    
    /**
     * Sets up hooks.
     * @since        1
     * @param       string      $sActionHookName
     * @param       integer     $iParameters        The number of parameters.
     */
    public function __construct( $sActionHookName, $iParameters=1 ) {

        add_action( 
            $sActionHookName, 
            array( 
                $this, 
                'doAction' 
            ),
            10, // priority
            $iParameters
        );    

    }
    
    /**
     * 
     * @callback        action       
     */
    public function doAction( /* $aArguments */ ) {
        
        $_aParams = func_get_args() + array( null );
        CustomScrollbar_Debug::log( $_aParams );
        
    }
    
}