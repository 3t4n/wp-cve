<?php
/**
 * Custom Scrollbar
 *
 * http://en.michaeluno.jp/custom-scrollbar/
 * Copyright (c) 2015-2016 Michael Uno; Licensed GPLv2
 *
 */

/**
 * Defines the `Load` form section.
 *
 * @since        1.3.0
 * @deprecated   1.3.0
 */
class CustomScrollbar_AdminPage__FormSection_Load extends CustomScrollbar_AdminPage__FormSection_Base {

    /**
     * @param $oFactory
     * @return array
     */
    protected function _getArguments($oFactory) {
        return array(
            'section_id'    => 'load',
            'title'         => __( 'Load', 'custom-scrollbar' ),
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
                'field_id'          => 'ajax_initialization',
                'type'              => 'checkbox',
                'title'             => __( 'Ajax Handling', 'custom-scrollbar' ),
                'label'             => __( 'Initialize scrollbars when Ajax requests are performed.', 'custom-scrollbar' ),
            ),
        );
    }

}