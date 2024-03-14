<?php
/**
 * Custom Scrollbar
 * 
 * http://en.michaeluno.jp/custom-scrollbar/
 * Copyright (c) 2015-2016 Michael Uno
 * 
 */

/**
 * Provides common methods for option objects.
 * 
 * @since        1
 */
class CustomScrollbar_Option_Base extends CustomScrollbar_PluginUtility {
    
    /**
     * Stores the option values.
     * 
     * @access      public      Let the data being modified from outside.
     */
    protected $_aOptions = array();

    /**
     * stores the option key for this plugin. 
     */
    protected $_sOptionKey = '';

    /**
     * Stores whether the currently loading page is in the network admin area.
     */
    protected $_bIsNetworkAdmin = false;

    /**
     * Sets up properties.
     */
    public function __construct( $_sOptionKey ) {

        $this->_bIsNetworkAdmin = false; // disabled.
        $this->_sOptionKey      = $_sOptionKey;
        $this->_aOptions        = $this->_getFormattedOptions( $_sOptionKey );

    }
        /**
         * Returns the formatted options array.
         * @remark  Override this method in an extended class.
         * @return  array
         */
        protected function _getFormattedOptions( $_sOptionKey ) {
            $_aOptions = $this->uniteArrays(
                $this->getAsArray(
                    $this->_bIsNetworkAdmin
                        ? get_site_option( $_sOptionKey, array() )
                        : get_option( $_sOptionKey, array() )
                ),
                apply_filters(
                    CustomScrollbar_Registry::HOOK_SLUG . '_filter_default_options',
                    CustomScrollbar_Registry::$aOptions[ 'custom_scrollbar' ]
                )
            );

            // Format each scrollbar option array.
            $_aScrollbars = array();
            foreach( $this->getElementAsArray( $_aOptions, array( 'scrollbars' ) ) as $_iIndex => $_aScrollbar ) {
                $_aScrollbars[ $_iIndex ] = $_aScrollbar
                    + CustomScrollbar_Registry::$aOptions[ 'custom_scrollbar' ][ 'scrollbars' ][ 0 ];
            }
            $_aOptions[ 'scrollbars' ] = $_aScrollbars;

            return $_aOptions;
        }

    /**
     * Checks the version number
     *
     * @since        1
     * @return      boolean        True if yes; otherwise, false.
     * @remrk       not used at the moment
     */
    public function hasUpgraded() {

        $_sOptionVersion        = $this->get( 'version_saved' );
        if ( ! $_sOptionVersion ) {
            return false;
        }
        $_sOptionVersion        = $this->_getVersionByDepth( $_sOptionVersion );
        $_sCurrentVersion       = $this->_getVersionByDepth( CustomScrollbar_Registry::VERSION );
        return version_compare( $_sOptionVersion, $_sCurrentVersion, '<' );

    }
        /**
         * Returns a stating part of version by the given depth.
         * @since        1
         */
        private function _getVersionByDepth( $sVersion, $iDepth=2 ) {
            if ( ! $iDepth ) {
                return $sVersion;
            }
            $_aParts = explode( '.', $sVersion );
            $_aParts = array_slice( $_aParts, 0, $iDepth );
            return implode( '.', $_aParts );
        }

    /**
     * Deletes the option from the database.
     */
    public function delete()  {
        return $this->_bIsNetworkAdmin
            ? delete_site_option( $this->_sOptionKey )
            : delete_option( $this->_sOptionKey );
    }

    /**
     * Saves the options.
     */
    public function save( $aOptions=null ) {
        $_aOptions = $aOptions ? $aOptions : $this->_aOptions;
        return $this->_bIsNetworkAdmin
            ? update_site_option(
                $this->_sOptionKey,
                $_aOptions
            )
            : update_option(
                $this->_sOptionKey,
                $_aOptions
            );
    }
    
    /**
     * Sets the options.
     */
    public function set( /* $asKeys, $mValue */ ) {
        
        $_aParameters   = func_get_args();
        if ( ! isset( $_aParameters[ 0 ], $_aParameters[ 1 ] ) ) {
            return;
        }
        $_asKeys        = $_aParameters[ 0 ];
        $_mValue        = $_aParameters[ 1 ];
        
        // string, integer, float, boolean
        if ( ! is_array( $_asKeys ) ) {
            $this->_aOptions[ $_asKeys ] = $_mValue;
            return;
        }
        
        // the keys are passed as an array
        $this->setMultiDimensionalArray( 
            $this->_aOptions,
            $_asKeys,
            $_mValue 
        );

    }
    
    /**
     * Sets and save the options.
     */
    public function update( /* $asKeys, $mValue */ ) {
        
        $_aParameters = func_get_args();
        call_user_func_array( array( $this, 'set' ), $_aParameters );
        $this->save();

    }

    /**
     * Returns the specified option value.
     * 
     * @since        1
     */
    public function get( /* $sKey1, $sKey2, $sKey3, ... OR $aKeys, $vDefault */ ) {
        
        $_mDefault     = null;
        $_aKeys        = func_get_args() + array( null );
        if ( ! isset( $_aKeys[ 0 ] ) ) {
            return $this->_aOptions;
        }
        if ( is_array( $_aKeys[ 0 ] ) ) {
            $_mDefault = $_aKeys[ 1 ];
            $_aKeys    = $_aKeys[ 0 ];
        }
        return $this->getArrayValueByArrayKeys( 
            $this->_aOptions,
            $_aKeys,
            $_mDefault
        );
        
    }
  
    
}