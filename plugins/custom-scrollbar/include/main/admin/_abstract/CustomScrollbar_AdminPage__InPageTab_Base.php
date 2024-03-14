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
abstract class CustomScrollbar_AdminPage__InPageTab_Base extends CustomScrollbar_AdminPage__Element_Base {
    
    protected $_sPageSlug;
    
    protected $_sTabSlug;
        
    /**
     * Sets up hooks and properties.
     */
    public function __construct( $oFactory, $sPageSlug ) {
        
        $this->_oFactory     = $oFactory;
        $this->_sPageSlug    = $sPageSlug;
        $this->_aArguments   = $this->_getArguments( $oFactory );
        $this->_sTabSlug     = $this->getElement( $this->_aArguments, 'tab_slug', '' );
        
        $this->_construct( $oFactory );
        if ( ! $this->_sTabSlug ) {
            return;
        }
        
        $this->___addTab( $this->_sPageSlug, $this->_aArguments );
                
    }
    
        private function ___addTab( $sPageSlug, $aArguments ) {
            
            $this->_oFactory->addInPageTabs(
                $sPageSlug,
                $aArguments + array(
                    'tab_slug'          => null,
                    'title'             => null,
                    'parent_tab_slug'   => null,
                    'show_in_page_tab'  => null,
                )
            );
                
            if ( $aArguments[ 'tab_slug' ] ) {
                add_action( 
                    "load_{$sPageSlug}_{$aArguments[ 'tab_slug' ]}",
                    array( $this, 'replyToLoad' ) 
                );
                add_action(
                    "do_{$sPageSlug}_{$aArguments[ 'tab_slug' ]}",
                    array( $this, 'replyToDo' )
                );
            }
            
        }   
    
    public function replyToDo( $oFactory ) {
        $this->_do( $oFactory );
        $this->_doDebug( $oFactory );
    }
    
    protected function _do( $oFactory ) {}
    
    
    protected function _doDebug( $oFactory ) {
        if ( ! $this->isDebugMode() ) {
            return;
        }
        echo "<h3>" . __( 'Saved Options', 'fetch-tweets' ) . "</h3>";
        echo CustomScrollbar_AdminPageFramework_Debug::getDetails(
            $oFactory->oProp->aOptions
        );
        
    }
    
}