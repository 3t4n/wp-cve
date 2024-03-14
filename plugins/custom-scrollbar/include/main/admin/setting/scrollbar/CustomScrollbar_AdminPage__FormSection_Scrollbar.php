<?php
/**
 * Custom Scrollbar
 *
 * http://en.michaeluno.jp/custom-scrollbar/
 * Copyright (c) 2015-2016 Michael Uno; Licensed GPLv2
 *
 */

/**
 * Defines the `Scrollbars` form section.
 *
 * @since        1.3.0
 */
class CustomScrollbar_AdminPage__FormSection_Scrollbar extends CustomScrollbar_AdminPage__FormSection_Base {

    protected function _construct( $oFactory ) {
        new CustomScrollbar_RevealerCustomFieldType( $oFactory->oProp->sClassName );
        new CustomScrollbar_NoUISliderCustomFieldType( $oFactory->oProp->sClassName );
    }

    /**
     * @param $oFactory
     * @return array
     */
    protected function _getArguments( $oFactory ) {
        return array(
            'section_id'    => 'scrollbars',
            'title'         => __( 'Scrollbars', 'custom-scrollbar' ),
            'description'   => array(
                __( 'Define scrollbars.', 'custom-scrollbar' ),
            ),
            'collapsible'       => array(
                'toggle_all_button' => array( 'top-left', 'bottom-left' ),
                'container'         => 'section',
                'is_collapsed'      => true,
            ),
            'repeatable'        => array(
                'disabled'  => array(
                    'message' => sprintf(
                        __( "Please upgrade to <a href='%1\$s' target='_blank'>Pro</a> to enable this feature.", 'custom-scrollbar' ),
                        esc_url( CustomScrollbar_Registry::PRO_URI )
                    ),
                    'caption' => CustomScrollbar_Registry::NAME,
                ),
            ),
            'sortable'          => true,
        );
    }

    /**
     * @param $oFactory
     * @return array
     */
    protected function _getFields( $oFactory ){

        $oFactory->oMsg->set( 'allowed_maximum_number_of_fields', __( 'Not allowed.', 'custom-scrollbar' ) );

        return array(
            array(
                'field_id'         => 'name',
                'type'             => 'section_title',
                'before_input'     => "<strong>"
                    . __( 'Name', 'custom-scrollbar' )
                    . "</strong>:&nbsp; ",
                'attributes'       => array(
                    'size'          => 48,
                    'style'         => 'width: auto;',
                    'placeholder'   => __( 'Enter a scrollbar name', 'custom-scrollbar' ),
                ),
            ),
            array(
                'field_id'         => 'status',
                'type'             => 'radio',
                'label'            => array(
                    1    => __( 'On', 'custom-scrollbar' ),
                    0    => __( 'Off', 'custom-scrollbar' ),
                ),
                'default'          => 1,
                'placement'        => 'section_title',
                'label_min_width'  => 40,
            ),
            array(
                'field_id'         => 'selector',
                'type'             => 'text',
                'title'            => __( 'Target Element Selector', 'custom-scrollbar' ),
                'tip'              => "<p>"
                    . __( 'Define the CSS (jQuery) target selector of the element.', 'custom-scrollbar' )
                    . ' e.g. <code>aside.widget</code>'
                    . "</p>"
                    . "<p>"
                    . __( 'For multiple selectors, delimit them by commas.', 'custom-scrollbar' )
                    . ' e.g. <code>div.widget > ul, div.widget > div</code>'
                    . "</p>",
                'attributes'       => array(
                    'size'  => 52,
                ),
            ),
            array(
                'field_id'          => 'height',
                'type'              => 'size',
                'title'             => __( 'Element Height', 'custom-scrollbar' ),
                'tip'               => __( 'The target element maximum height.', 'custom-scrollbar' )
                    . ' ' . __( 'HTML elements that exceed this value will have a scrollbar.', 'custom-scrollbar' ),
                'units'             => array(
                    'px'    => 'px',
                    '%'    => '%',
                ),
            ),
            array(
                'field_id'          => 'width',
                'type'              => 'size',
                'title'             => __( 'Element Width', 'custom-scrollbar' ),
                'tip'               => __( 'The target element maximum width.', 'custom-scrollbar' )
                    . ' ' . __( 'HTML elements that exceed this value will have a scrollbar.', 'custom-scrollbar' ),
                'units'             => array(
                    'px'    => 'px',
                    '%'    => '%',
                ),
            ),
            array(
                'field_id'          => 'position',
                'type'              => 'radio',
                'title'             => __( 'Position', 'custom-scrollbar' ),
                'label'             => array(
                    'inside'     => 'inside',
                    'outside'    => 'outside',
                ),
                'default'           => 'inside',
            ),
            array(
                'field_id'          => 'inline_css',
                'type'              => 'text',
                'title'             => __( 'Inline CSS', 'custom-scrollbar' ),
                'label'             => array(
                    'property' => __( 'Property', 'custom-scrollbar' ),
                    'value'    => __( 'Value', 'custom-scrollbar' ),
                ),
                'label_min_width'   => '60px',
                'tip'               => "<p>"
                    . __( 'Apply these inline CSS rules to the target elements.', 'custom-scrollbar' )
                    . ' e.g. ' . '<code>white-space</code>: <code>nowrap</code>'
                    . "</p>",
                'repeatable'        => true,
            ),
            array(
                'field_id'          => 'theme',
                'type'              => 'select',
                'title'             => __( 'Theme', 'custom-scrollbar' ),
                'label'             => array(
                    'light'                 => __( 'Light', 'custom-scrollbar' ),
                    'dark'                  => __( 'Dark', 'custom-scrollbar' ),
                    'minimal'               => __( 'Minimal', 'custom-scrollbar' ),
                    'minimal-dark'          => __( 'Minimal-Dark', 'custom-scrollbar' ),
                    'light-2'               => __( 'Light 2', 'custom-scrollbar' ),
                    'dark-2'                => __( 'Dark 2', 'custom-scrollbar' ),
                    'light-3'               => __( 'Light 3', 'custom-scrollbar' ),
                    'dark-3'                => __( 'Dark 3', 'custom-scrollbar' ),
                    'light-thick'           => __( 'Light Thick', 'custom-scrollbar' ),
                    'dark-thick'            => __( 'Dark Thick', 'custom-scrollbar' ),
                    'light-thin'            => __( 'Light thin', 'custom-scrollbar' ),
                    'dark-thin'             => __( 'Dark thin', 'custom-scrollbar' ),
                    'inset'                 => __( 'Inset', 'custom-scrollbar' ),
                    'inset-dark'            => __( 'Inset-Dark', 'custom-scrollbar' ),
                    'inset-2'               => __( 'Inset 2', 'custom-scrollbar' ),
                    'inset-2-dark'          => __( 'Inset 2 Dark', 'custom-scrollbar' ),
                    'inset-3'               => __( 'Inset 3', 'custom-scrollbar' ),
                    'inset-3-dark'          => __( 'Inset 3 Dark', 'custom-scrollbar' ),
                    'rounded'               => __( 'Rounded', 'custom-scrollbar' ),
                    'rounded-dark'          => __( 'Rounded Dark', 'custom-scrollbar' ),
                    'rounded-dots'          => __( 'Rounded Dots', 'custom-scrollbar' ),
                    'rounded-dots-dark'     => __( 'Rounded Dots Dark', 'custom-scrollbar' ),
                    '3d'                    => __( '3D', 'custom-scrollbar' ),
                    '3d-dark'               => __( '3D Dark', 'custom-scrollbar' ),
                    '3d-thick'              => __( '3D Thick', 'custom-scrollbar' ),
                    '3d-thick-dark'         => __( '3D Thick Dark', 'custom-scrollbar' ),
                ),
                'default'           => 'light',
            ),
            array(
                'field_id'          => 'mouseWheel',
                'title'             => __( 'Mouse Wheel', 'custom-scrollbar' ),
                'tip'               => __( 'Decide whether to enable or disable content scrolling via the mouse-wheel.', 'custom-scrollbar' ),
                'content'           => array(
                    array(
                        'field_id'    => 'enable',
                        'type'        => 'revealer',
                        'select_type' => 'radio',
                        'label'     => array(
                            1   => __( 'On', 'custom-scrollbar' ),
                            0   => __( 'Off', 'custom-scrollbar' ),
                        ),
                        'selectors'   => array(
                            1   => '.mouse-wheel',
                            0   => '.mouse-wheel-off', // non-existent element
                        ),
                        'default'       => 1,
                    ),
                    array(
                        'field_id'      => 'scrollAmount',
                        'title'         => __( 'Scroll Amount', 'custom-scrollbar' ),
                        'type'          => 'number',
                        'after_input'   => ' ' . __( 'pixels', 'custom-scrollbar' ),
                        'description'   => __( 'Leave it empty to be automatically configured.', 'custom-scrollbar' ),
                        'attributes'    => array(
                            'min'   => 0,
                        ),
                        'class'         => array(
                            'fieldset'  => 'mouse-wheel',
                        ),
                    ),
                ),
            ),
            array(
                'field_id'          => 'keyboard',
                'title'             => __( 'Keyboard', 'custom-scrollbar' ),
                'tip'               => __( 'Decide whether to enable or disable content scrolling via the keyboard. The following keys are supported: top, left, right and down, page-up (PgUp), page-down (PgDn), Home and End.', 'custom-scrollbar' ),
                'content'           => array(
                    array(
                        'field_id'      => 'enable',
                        'type'          => 'revealer',
                        'select_type'   => 'radio',
                        'label'     => array(
                            1   => __( 'On', 'custom-scrollbar' ),
                            0   => __( 'Off', 'custom-scrollbar' ),
                        ),
                        'selectors'   => array(
                            1   => '.keyboard',
                            0   => '.keyboard-off', // non-existent element
                        ),
                    ),
                    array(
                        'field_id'      => 'scrollAmount',
                        'title'         => __( 'Scroll Amount', 'custom-scrollbar' ),
                        'type'          => 'number',
                        'after_input'   => ' ' . __( 'pixels', 'custom-scrollbar' ),
                        'description'   => __( 'Leave it empty to be automatically configured.', 'custom-scrollbar' ),
                        'attributes'    => array(
                            'min'   => 0,
                        ),
                        'class'         => array(
                            'fieldset'  => 'keyboard',
                        ),
                    ),
                ),
            ),
            array(
                'field_id'          => 'scrollButtons',
                'title'             => __( 'Scroll Buttons', 'custom-scrollbar' ),
                'tip'               => __( 'Decide whether to enable or disable scrollbar buttons.', 'custom-scrollbar' ),
                'content'           => array(
                    array(
                        'field_id'      => 'enable',
                        'type'          => 'revealer',
                        'select_type'   => 'radio',
                        'label'         => array(
                            1   => __( 'On', 'custom-scrollbar' ),
                            0   => __( 'Off', 'custom-scrollbar' ),
                        ),
                        'selectors'   => array(
                            1   => '.scroll-buttons',
                            0   => '.scroll-buttons-off', // non-existent element
                        ),
                    ),
                    array(
                        'field_id'      => 'scrollAmount',
                        'title'         => __( 'Scroll Amount', 'custom-scrollbar' ),
                        'type'          => 'number',
                        'after_input'   => ' ' . __( 'pixels', 'custom-scrollbar' ),
                        'description'   => __( 'Leave it empty to be automatically configured.', 'custom-scrollbar' ),
                        'attributes'    => array(
                            'min'   => 0,
                        ),
                        'class'         => array(
                            'fieldset'  => 'scroll-buttons',
                        ),
                    ),
                    array(
                        'field_id'      => 'scrollType',
                        'title'         => __( 'Scroll Type', 'custom-scrollbar' ),
                        'type'          => 'radio',
                        'label'         => array(
                            'stepless' => __( 'Scrolls the content continuously while pressing the button.', 'custom-scrollbar' ),
                            'stepped'  => __( 'Each button click scrolls the content by the amount set in the Scroll Amount option.', 'custom-scrollbar' ),
                        ),
                        'class'         => array(
                            'fieldset'  => 'scroll-buttons',
                        ),
                    ),
                ),
            ),
            array(
                'field_id'          => 'mCSB_draggerContainer',
                'type'              => 'color',
                'title'             => '.mCSB_draggerContainer',
                'default'           => '',
                'class'             => array(
                    'fieldrow'  => 'custom-colors',
                ),
            ),
            array(
                'field_id'          => 'mCSB_dragger',
                'type'              => 'color',
                'title'             => '.mCSB_dragger',
                'default'           => '',
                'class'             => array(
                    'fieldrow'  => 'custom-colors',
                ),
            ),
            array(
                'field_id'          => 'mCSB_dragger_bar',
                'type'              => 'color',
                'title'             => '.mCSB_dragger_bar',
                'default'           => '',
                'class'             => array(
                    'fieldrow'  => 'custom-colors',
                ),
            ),
            array(
                'field_id'          => 'mCSB_draggerRail',
                'type'              => 'color',
                'title'             => '.mCSB_draggerRail',
                'default'           => '',
                'class'             => array(
                    'fieldrow'  => 'custom-colors',
                ),
            ),
            array(
                'field_id'          => 'mCSB_scrollTools',
                'type'              => 'color',
                'title'             => '.mCSB_scrollTools',
                'default'           => '',
                'class'             => array(
                    'fieldrow'  => 'custom-colors',
                ),
            ),
            array(
                'field_id'          => 'initialize_on_ajax_load',
                'title'             => __( 'Ajax Handling', 'custom-scrollbar' ),
                'type'              => 'checkbox',
                'label'             => __( 'Initialize scrollbars when Ajax requests are performed.', 'custom-scrollbar' ),
            ),
            array(
                'field_id'          => 'responsive',
                'title'             => __( 'Responsive', 'custom-scrollbar' ),
                'content'           => array(
                    array(
                        'field_id'    => 'enable',
                        'type'        => 'revealer',
                        'select_type' => 'radio',
                        'label'     => array(
                            1   => __( 'On', 'custom-scrollbar' ),
                            0   => __( 'Off', 'custom-scrollbar' ),
                        ),
                        'selectors'   => array(
                            1   => '.screen-width-range',
                            0   => '.screen-width-range-off', // non-existent element
                        ),
                        'default'       => 0,
                    ),
                    array(
                        'field_id'      => 'screen_width_range',
                        'type'          => 'no_ui_slider',
                        'title'         => __( 'Visible Screen Width Ranges', 'custom-scrollbar' ),
                        'description'   => __( 'The scrollbar will appear in the specified screen width ranges. The values are in pixels. Set empty for no limit.', 'custom-scrollbar' ),
                        'repeatable'    => true,
                        'sortable'      => true,
                        'disabled'      => true,
                        'disable'       => true,
                        'class'         => array(
                            'fieldset' => 'screen-width-range',
                        ),
                        'label_min_width'       => '40px',
                        'label'                 => array(
                            0   => __( 'Min' ),
                            1   => __( 'Max' ),
                        ),
                        'options'               => array(
                            'start' => array(
                                1, 1200
                            ),
                            'range'             => array(
                                'min'   => 1,
                                'max'   => 1200,
                            ),
                            'connect'           => array(
                                false, true, false,
                            ),
                            'interactive'       => array(
                                true, true
                            ),
                            'can_exceed_min'    => false,
                            'can_exceed_max'    => true,
                            'allow_empty'       => true,
                        ),
                        'attributes'    => array(
                            'fieldset' => array(
                                'style' => 'min-width: 30%;'
                            ),
                        ),

                    ),
                ),
            ),
        );
    }

}
