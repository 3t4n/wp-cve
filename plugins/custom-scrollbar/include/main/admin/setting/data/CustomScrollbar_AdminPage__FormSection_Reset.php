<?php
/**
 * Custom Scrollbar
 *
 * http://en.michaeluno.jp/custom-scrollbar/
 * Copyright (c) 2015-2016 Michael Uno; Licensed GPLv2
 *
 */

/**
 * Defines the `Reset` form section.
 *
 * @since        1.3.0
 */
class CustomScrollbar_AdminPage__FormSection_Reset extends CustomScrollbar_AdminPage__FormSection_Base {

    /**
     * @param $oFactory
     * @return array
     */
    protected function _getArguments( $oFactory ) {
        return array(
            'section_id'    => '_reset',
            'title'         => __( 'Reset Options', 'custom-scrollbar' ),
            'save'          => false,
        );
    }

    /**
     * Retrieves form fields.
     * @since       1.3.0
     * @return      array
     */
    protected function _getFields( $oFactory ) {
        return array(
            array(
                'field_id'          => 'reset_confirmation_check',
                'title'             => __( 'Initialization', 'custom-scrollbar' ),
                'type'              => 'checkbox',
                'label'             => __( 'I understand the options will be erased by pressing the reset button.', 'custom-scrollbar' ),
                'save'              => false,
                'value'             => false,
            ),
            array(
                'field_id'          => 'reset',
                'type'              => 'submit',
                'reset'             => true,
                'save'              => false,
                'skip_confirmation' => true,
                'value'             => __( 'Reset', 'custom-scrollbar' ),
            )
        );
    }

    /**
     * Validates the submitted form data.
     *
     * @since       1.3.0
     * @return      array
     */
    protected function _validate( $aInputs, $aOldInputs, $oAdminPage, $aSubmitInfo ) {

        $_bVerified = true;
        $_aErrors   = array();

        // If the pressed button is not the one with the check box, do not set a field error.
        if ( 'reset' !== $aSubmitInfo[ 'field_id' ] ) {
            return $aInputs;
        }

        if ( ! $aInputs[ 'reset_confirmation_check' ] ) {
            $_bVerified = false;
            $_aErrors[ $this->_sSectionID ][ 'reset_confirmation_check' ] = __( 'Please check the check box to confirm you want to reset the settings.', 'custom-scrollbar' );
        }

        // An invalid value is found. Set a field error array and an admin notice and return the old values.
        if ( ! $_bVerified ) {
            $oAdminPage->setFieldErrors( $_aErrors );
            $oAdminPage->setSettingNotice( __( 'There was something wrong with your Inputs.', 'custom-scrollbar' ) );
            return $aOldInputs;
        }

        return $aInputs;

    }

}