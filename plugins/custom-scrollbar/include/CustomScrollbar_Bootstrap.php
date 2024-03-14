<?php
/**
 * Custom Scrollbar
 * 
 * http://en.michaeluno.jp/custom-scrollbar/
 * Copyright (c) 2015-2016 Michael Uno
 * 
 */

/**
 * Loads the plugin.
 * 
 * @since       1
 */
final class CustomScrollbar_Bootstrap extends CustomScrollbar_AdminPageFramework_PluginBootstrap {
    
    /**
     * User constructor.
     */
    protected function construct()  {}        

        
    /**
     * Register classes to be auto-loaded.
     * 
     * @since        1
     */
    public function getClasses() {
        
        // Include the include lists. The including file reassigns the list(array) to the $_aClassFiles variable.
        $_aClassFiles   = array();
        $_bLoaded       = include( dirname( $this->sFilePath ) . '/include/class-list.php' );
        if ( ! $_bLoaded ) {
            return $_aClassFiles;
        }
        return $_aClassFiles;
                
    }

    /**
     * Sets up constants.
     */
    public function setConstants() {
        CustomScrollbar_AdminPageFramework_Debug::$iLegibleStringCharacterLimit= 1000000;
    }    
    
    /**
     * Sets up global variables.
     */
    public function setGlobals() {
    }    
    
    /**
     * The plugin activation callback method.
     */    
    public function replyToPluginActivation() {

        $this->_checkRequirements();
        
    }
        
        /**
         * 
         * @since            3
         */
        private function _checkRequirements() {

            $_oRequirementCheck = new CustomScrollbar_AdminPageFramework_Requirement(
                CustomScrollbar_Registry::$aRequirements,
                CustomScrollbar_Registry::NAME
            );
            
            if ( $_oRequirementCheck->check() ) {            
                $_oRequirementCheck->deactivatePlugin( 
                    $this->sFilePath, 
                    __( 'Deactivating the plugin', 'custom-scrollbar' ),  // additional message
                    true    // is in the activation hook. This will exit the script.
                );
            }        
             
        }    

        
    /**
     * The plugin activation callback method.
     */    
    public function replyToPluginDeactivation() {
        
        CustomScrollbar_WPUtility::cleanTransients( 
            array(
                CustomScrollbar_Registry::TRANSIENT_PREFIX,
                'apf_',
            )
        );
        
    }        
    
        
    /**
     * Load localization files.
     * 
     * @callback    action      init
     */
    public function setLocalization() {
        
        // This plugin does not have messages to be displayed in the front end.
        if ( ! $this->bIsAdmin ) { 
            return; 
        }
        
        load_plugin_textdomain( 
            CustomScrollbar_Registry::TEXT_DOMAIN, 
            false, 
            dirname( plugin_basename( $this->sFilePath ) ) . '/' . CustomScrollbar_Registry::TEXT_DOMAIN_PATH
        );
        
    }        
    
    /**
     * Loads the plugin specific components. 
     * 
     * @remark        All the necessary classes should have been already loaded.
     */
    public function setUp() {
        
        // This constant is set when uninstall.php is loaded.
        if ( defined( 'DOING_PLUGIN_UNINSTALL' ) && DOING_PLUGIN_UNINSTALL ) {
            return;
        }

        // Option Object - must be done before the template object.
        // The initial instantiation will handle formatting options from earlier versions of the plugin.
        CustomScrollbar_Option::getInstance();
     
        // Admin pages
        if ( $this->bIsAdmin ) {
            new CustomScrollbar_AdminPage( CustomScrollbar_Registry::$aOptionKeys[ 'setting' ], $this->sFilePath );
        }
        
        // CSS & Scripts
        if ( ! $this->bIsAdmin ) {
            new CustomScrollbar_ResourceLoader;
        }
        
    }

}