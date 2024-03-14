<?php
/**
 * Custom Scrollbar
 *
 * http://en.michaeluno.jp/custom-scrollbar/
 * Copyright (c) 2015-2016 Michael Uno; Licensed GPLv2
 *
 */

/**
 * Defines the `Import / Export Options` form section.
 *
 * @since        1.3.0
 */
class CustomScrollbar_AdminPage__FormSection_Transport extends CustomScrollbar_AdminPage__FormSection_Base {

    /**
     * @param $oFactory
     * @return array
     */
    protected function _getArguments($oFactory) {
        return array(
            'section_id'    => 'transport',
            'title'         => __( 'Import / Export Options', 'custom-scrollbar' ),
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
                'field_id'          => 'export',
                'type'              => 'export',
                'title'             => __( 'Export', 'custom-scrollbar' ),
            ),
            array(
                'field_id'          => 'import',
                'type'              => 'import',
                'title'             => __( 'Import', 'custom-scrollbar' ),
                'value'             => __( 'Import', 'custom-scrollbar' ),
            ),
        );
    }

}