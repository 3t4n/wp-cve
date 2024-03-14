<?php
/**
 * Custom Scrollbar
 * 
 * http://en.michaeluno.jp/custom-scrollbar/
 * Copyright (c) 2015-2016 Michael Uno
 * 
 */


/**
 * Deals with the plugin admin pages.
 * 
 * @since       1
 */
class CustomScrollbar_AdminPage extends CustomScrollbar_AdminPageFramework {

    /**
     * User constructor.
     */
    public function start() {
        
        if ( ! $this->oProp->bIsAdmin ) {
            return;
        }     
        add_filter( 
            "options_" . $this->oProp->sClassName,
            array( $this, 'replyToSetOptions' )
        );
        
    }
        /**
         * Sets the default option values for the setting form.
         * @return      array       The options array.
         */
        public function replyToSetOptions( $aOptions ) {
            $_oOption    = CustomScrollbar_Option::getInstance();
            // return $aOptions + $_oOption->get();
            return $this->oUtil->uniteArrays(
                $aOptions,
                $_oOption->get()
            );
        }

    /**
     * Sets up admin pages.
     */
    public function setUp() {
        
        $this->setRootMenuPage( 'Appearance' );
                    
        // Add pages
        new CustomScrollbar_AdminPage__Page_Setting( $this );

        $this->_doPageSettings();

        $this->addLinkToPluginDescription(
           "<a href='http://en.michaeluno.jp/contact/custom-order/'>" . __( 'Order custom plugin', 'custom-scrollbar' ) . "</a>"
        );

    }

        /**
         * Page styling
         * @since       1
         * @return      void
         */
        private function _doPageSettings() {
                        
            $this->setPageTitleVisibility( false ); // disable the page title of a specific page.
            $this->setInPageTabTag( 'h2' );
            $this->enqueueStyle( CustomScrollbar_Registry::getPluginURL( 'asset/css/admin.css' ) );
        
        }

}