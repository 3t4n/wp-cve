<?php
/**
 * Custom Scrollbar
 *
 * http://en.michaeluno.jp/custom-scrollbar/
 * Copyright (c) 2015-2016 Michael Uno; Licensed GPLv2
 *
 */

/**
 * Defines the `Delete` form section.
 *
 * @since        1.3.0
 */
class CustomScrollbar_AdminPage__FormSection_Delete extends CustomScrollbar_AdminPage__FormSection_Base {

    /**
     * @param $oFactory
     * @return array
     */
    protected function _getArguments( $oFactory ) {
        return array(
            'section_id'    => 'delete',
            'title'         => __( 'Delete Options', 'custom-scrollbar' ),
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
                'field_id'          => 'delete_upon_uninstall',
                'type'              => 'checkbox',
                'title'             => __( 'On Uninstall', 'custom-scrollbar' ),
                'label'             => __( 'Delete options upon plugin uninstall.', 'custom-scrollbar' ),
            ),
        );
    }

}