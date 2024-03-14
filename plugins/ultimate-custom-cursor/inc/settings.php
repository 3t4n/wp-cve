<?php
/**
 * Settings
 *
 * @package DLUCC
 */
require_once DLUCC_PATH . '/inc/options-class.php';

class Dlucc_Settings {
    public function init() {
        $pages = array(
            'dlucc-opt' => array(
                'page_title'  => esc_html__( 'Ultimate Custom Cursor', 'dlucc' ),
                'parent_slug' => 'options-general.php',
                'menu_title'  => esc_html__( 'Custom Cursor Options', 'dlucc' ),
                'menu_slug'   => 'dl-custom-cursor-options',
                'sections'    => array(
                    'section-one' => array(
                        'title'  => esc_html__( 'You can control custom cursor options from here.', 'dlucc' ),
                        'fields' => array(
                            'custom/_cursor_control'     => array(
                                'title'   => esc_html__( 'Custom Cursor', 'dlucc' ),
                                'type'    => 'radio',
                                'text'  => esc_html__( 'You can enable/disable custom cursor using this option.', 'dlucc' ),
                                'value'   => 'enable',
                                'choices' => array(
                                    'enable' => esc_html__( 'Enable', 'dlucc' ),
                                    'disable' => esc_html__( 'Disable', 'dlucc' ),
                                ),
                            ),
                            'mobile_disable'     => array(
                                'title'   => esc_html__( 'Mobile Custom Cursor', 'dlucc' ),
                                'type'    => 'radio',
                                'text'  => esc_html__( 'You can enable/disable custom cursor for mobile devices using this option.', 'dlucc' ),
                                'value'   => 'enable',
                                'choices' => array(
                                    'enable' => esc_html__( 'Enable', 'dlucc' ),
                                    'disable' => esc_html__( 'Disable', 'dlucc' ),
                                ),
                            ),
                            'cursorcolor'        => array(
                                'title' => esc_html__( 'Cursor Color', 'dlucc' ),
                                'type'  => 'color',
                                'text'  => esc_html__( 'Change cursor color in hex.', 'dlucc' ),
                                'value' => '#fa575c'
                            ),
                            'cursorcolor_opacity'   => array(
                                'title'      => esc_html__( 'Cursor Color Opacity', 'dlucc' ),
                                'type'       => 'number',
                                'text'       => esc_html__( 'Change cursor color opacity using this field.', 'dlucc' ),
                                'value'      => 0.4,
                                'attributes' => [
                                    'min'  => 0,
                                    'max'  => 1,
                                    'step' => 0.1,
                                ],
                            ),
                            'cursorcolor_fill_opacity'   => array(
                                'title'      => esc_html__( 'Cursor Color Hover Opacity', 'dlucc' ),
                                'type'       => 'number',
                                'text'       => esc_html__( 'Change cursor color hover opacity using this field.', 'dlucc' ),
                                'value'      => 0.6,
                                'attributes' => [
                                    'min'  => 0,
                                    'max'  => 1,
                                    'step' => 0.1,
                                ],
                            ),
                            'hover_trigger_selectors'   => array(
                                'title'      => esc_html__( 'Hover Trigger Selectors', 'dlucc' ),
                                'type'       => 'text',
                                'text'       => esc_html__( 'You can add classes here for hover effect. Default is all links and buttons. Add classes with comma(,) separated.', 'dlucc' ),
                                'value'      => 'a,button',
                            ),
                        ),
                    ),
                ),
            ),
        );

        $option_page = new Dlucc_Options( $pages );
    }
}