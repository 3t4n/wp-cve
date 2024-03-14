<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WModes_Admin_Utils' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {


    class WModes_Admin_Utils {

        public static function get_size_fields( $args = array() ) {

            if ( !isset( $args[ 'default_size' ] ) ) {
                $args[ 'default_size' ] = 12;
            }

            if ( !isset( $args[ 'default_unit' ] ) ) {
                $args[ 'default_unit' ] = 'px';
            }

            if ( !isset( $args[ 'placeholder' ] ) ) {
                $args[ 'placeholder' ] = esc_html__( '0', 'wmodes-tdm' );
            }

            $in_fields = array();

            $in_fields[] = array(
                'id' => 'size',
                'type' => 'textbox',
                'input_type' => 'number',
                'default' => $args[ 'default_size' ],
                'placeholder' => $args[ 'placeholder' ],
                'attributes' => array(
                    'step' => '1',
                ),
                'box_width' => '55%',
                'width' => '102%',
            );

            $in_fields[] = array(
                'id' => 'unit',
                'type' => 'select2',
                'default' => $args[ 'default_unit' ],
                'options' => array(
                    'rem' => esc_html__( 'rem', 'wmodes-tdm' ),
                    'vh' => esc_html__( 'vh', 'wmodes-tdm' ),
                    'vw' => esc_html__( 'vw', 'wmodes-tdm' ),
                    'em' => esc_html__( 'em', 'wmodes-tdm' ),
                    'px' => esc_html__( 'px', 'wmodes-tdm' ),
                    '%' => esc_html__( '%', 'wmodes-tdm' ),
                ),
                'width' => '100%',
                'box_width' => '45%',
            );

            return $in_fields;
        }

        public static function get_boundary_sizes_fields( $args = array() ) {

            $default_sizes = array(
                'top' => '',
                'right' => '',
                'bottom' => '',
                'left' => '',
            );

            if ( isset( $args[ 'default_size' ] ) && is_array( $args[ 'default_size' ] ) ) {

                $default_sizes = $args[ 'default_size' ];
            } else if ( isset( $args[ 'default_size' ] ) ) {

                $default_sizes[ 'top' ] = $args[ 'default_size' ];
                $default_sizes[ 'right' ] = $args[ 'default_size' ];
                $default_sizes[ 'bottom' ] = $args[ 'default_size' ];
                $default_sizes[ 'left' ] = $args[ 'default_size' ];
            }

            if ( !isset( $args[ 'default_unit' ] ) ) {
                $args[ 'default_unit' ] = 'px';
            }

            if ( !isset( $args[ 'placeholder' ] ) ) {
                $args[ 'placeholder' ] = esc_html__( '0', 'wmodes-tdm' );
            }

            $in_fields = array();

            $in_fields[] = array(
                'id' => 'top',
                'type' => 'textbox',
                'input_type' => 'number',
                'default' => $default_sizes[ 'top' ],
                'placeholder' => $args[ 'placeholder' ],
                'tooltip' => array(
                    'title' => esc_html__( 'TOP', 'wmodes-tdm' ),
                    'is_bottom' => true,
                ),
                'attributes' => array(
                    'step' => '1',
                ),
                'box_width' => '20%',
                'width' => '102%',
            );

            $in_fields[] = array(
                'id' => 'right',
                'type' => 'textbox',
                'input_type' => 'number',
                'default' => $default_sizes[ 'right' ],
                'placeholder' => $args[ 'placeholder' ],
                'tooltip' => array(
                    'title' => esc_html__( 'RIGHT', 'wmodes-tdm' ),
                    'is_bottom' => true,
                ),
                'attributes' => array(
                    'step' => '1',
                ),
                'box_width' => '20%',
                'width' => '102%',
            );

            $in_fields[] = array(
                'id' => 'bottom',
                'type' => 'textbox',
                'input_type' => 'number',
                'default' => $default_sizes[ 'bottom' ],
                'placeholder' => $args[ 'placeholder' ],
                'tooltip' => array(
                    'title' => esc_html__( 'BOTTOM', 'wmodes-tdm' ),
                    'is_bottom' => true,
                ),
                'attributes' => array(
                    'step' => '1',
                ),
                'box_width' => '20%',
                'width' => '102%',
            );

            $in_fields[] = array(
                'id' => 'left',
                'type' => 'textbox',
                'input_type' => 'number',
                'default' => $default_sizes[ 'left' ],
                'placeholder' => $args[ 'placeholder' ],
                'tooltip' => array(
                    'title' => esc_html__( 'LEFT', 'wmodes-tdm' ),
                    'is_bottom' => true,
                ),
                'attributes' => array(
                    'step' => '1',
                ),
                'box_width' => '20%',
                'width' => '102%',
            );

            $in_fields[] = array(
                'id' => 'unit',
                'type' => 'select2',
                'default' => $args[ 'default_unit' ],
                'options' => array(
                    'rem' => esc_html__( 'rem', 'wmodes-tdm' ),
                    'vh' => esc_html__( 'vh', 'wmodes-tdm' ),
                    'vw' => esc_html__( 'vw', 'wmodes-tdm' ),
                    'em' => esc_html__( 'em', 'wmodes-tdm' ),
                    'px' => esc_html__( 'px', 'wmodes-tdm' ),
                    '%' => esc_html__( '%', 'wmodes-tdm' ),
                ),
                'box_width' => '20%',
                'width' => '100%',
            );

            return $in_fields;
        }

        public static function get_theme_value( $key ) {

            $theme_values = array(
                'accent_color_1' => '#ffc000',
                'accent_color_2' => '#ff0',
                'accent_color_3' => '#efa400',
                'color_1' => '#222',
                'color_2' => '#999',
                'color_3' => '#ddd',
                'color_4' => '#eee',
                'color_5' => '#fff',
            );

            return $theme_values[ $key ];
        }

    }

}