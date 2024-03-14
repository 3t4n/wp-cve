<?php
/**
 * Custom Scrollbar
 *
 * http://en.michaeluno.jp/custom-scrollbar/
 * Copyright (c) 2015-2016 Michael Uno; Licensed GPLv2
 *
 */

/**
 * Defines the `CSS` form section.
 *
 * @since        1.3.0
 */
class CustomScrollbar_AdminPage__FormSection_CSS extends CustomScrollbar_AdminPage__FormSection_Base {

    /**
     * @param $oFactory
     * @return array
     */
    protected function _getArguments($oFactory) {
        return array(
            'section_id'    => 'css',
            'title'         => __( 'Custom CSS', 'custom-scrollbar' ),
            'description'   => array(
                __( 'Define custom CSS.', 'custom-scrollbar' ),
            ),
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
                'field_id'        => 'custom_css',
                'type'            => 'textarea',
                'title'           => __( 'CSS Rules', 'custom-scrollbar' ),
                'tip'             => "<p>"
                    . __( 'Define custom CSS rules like <code>.my-selector { width: 100%; } </code>', 'custom-scrollbar' )
                    . "</p>",
            )
        );
    }

}