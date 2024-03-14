<?php
/**
 * Custom Scrollbar
 *
 * [PROGRAM_URI]
 * Copyright (c) 2015-2016 Michael Uno
 *
 */

/**
 * Provides an abstract base for adding form sections.
 * 
 * @since       1.3.0
 */
abstract class CustomScrollbar_AdminPage__FormSection_Base extends CustomScrollbar_AdminPage__Element_Base {

    protected $_sPageSlug;
    
    protected $_sTabSlug;
    
    protected $_sSectionID;

    /**
     * Sets up hooks and properties.
     */
    public function __construct( $oFactory, $sPageSlug='', $sTabSlug='' ) {
        
        $this->_oFactory     = $oFactory;
        $this->_sPageSlug    = $sPageSlug ? $sPageSlug : $this->_sPageSlug;
        $this->_sTabSlug     = $sTabSlug ? $sTabSlug : $this->_sTabSlug;
        $this->_aArguments   = $this->_getArguments( $oFactory ) + array(
            'tab_slug'  => $this->_sTabSlug
                ? $this->_sTabSlug
                : null,
        );
        $this->_sSectionID   = $this->_sSectionID
            ? $this->_sSectionID
            : $this->getElement( $this->_aArguments, array( 'section_id' ), '' );
            
        $this->_construct( $oFactory );
            
        if ( ! $this->_sSectionID ) {
            return;
        }
        $this->___addSection( $oFactory, $this->_sSectionID, $this->_aArguments );

    }
    
        private function ___addSection( $oFactory, $sSectionID, array $aArguments ) {
            
            $oFactory->addSettingSections( $aArguments );
            
            // Set the target section id
            $oFactory->addSettingFields( $sSectionID );
            
            // Set field-sets.
            foreach( ( array ) $this->_getFields( $oFactory ) as $_aFieldset ) {
                $_aFieldset[ 'tab_slug' ] = $this->getElement( $_aFieldset, 'tab_slug', $this->_sTabSlug );
                $oFactory->addSettingFields( $_aFieldset );
            }
            
            add_filter( 
                'validation_' . $oFactory->oProp->sClassName . '_' . $sSectionID,
                array( $this, 'replyToValidate' ), 
                10, 
                4 
            );
            
        }
    
    /**
     * @return      array
     */
    protected function _getFields( $oFactory ) {
        return array();
    }
    
    /**
     * @return      array
     * @callback    filter      validation_{class name}_{section id}
     */
    public function replyToValidate( $aInputs, $aOldInputs, $oFactory, $aSubmitInfo ) {
        return $this->_validate( $aInputs, $aOldInputs, $oFactory, $aSubmitInfo );
    }

    /**
     * @param   $aInputs
     * @param   $aOldInputs
     * @param   $oFactory
     * @param   $aSubmitInfo
     * @return  array
     * @since   0.0.3
     */
    protected function _validate( $aInputs, $aOldInputs, $oAdminPage, $aSubmitInfo ) {

        $_bVerified = true;
        $_aErrors   = array();

        // An invalid value is found. Set a field error array and an admin notice and return the old values.
        if ( ! $_bVerified ) {
            $oAdminPage->setFieldErrors( $_aErrors );
            $oAdminPage->setSettingNotice( __( 'There was something wrong with your input.', 'plugin-template' ) );
            return $aOldInputs;
        }

        return $aInputs;

    }
    
}