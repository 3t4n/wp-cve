<?php
/**
 * Assets class
 *
 * @package DLUCC
 */

defined( 'ABSPATH' ) || die();

class DLUCC_ASSETS {

    /**
     * Frontend
     *
     * @return void
     */
    public function frontend() {
        add_action( "wp_enqueue_scripts", [$this, 'scripts'] );
    }

    /**
     * Admin
     *
     * @return void
     */
    public function admin() {
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
    }

    /**
     * Register Scripts/Styles
     *
     * @return void
     */
    public function scripts() {

        wp_register_style( 'dlucc-style', DLUCC_ASSETS . '/css/style.css', false, filemtime( DLUCC_PATH . '/assets/css/style.css' ) );
        wp_register_script( 'dlucc-script', DLUCC_ASSETS . '/js/dlucc-init.js', ['jquery'], filemtime( DLUCC_PATH . '/assets/css/style.css' ), true );
        wp_enqueue_style( 'dlucc-style' );
        wp_enqueue_script( 'dlucc-script' );

        $cursor_color         = dlucc_get_option( 'cursor_color', 'enable' );
        $cursor_opacity       = dlucc_get_option( 'cursor_color_opacity', '0.4' );
        $cursor_hover_opacity = dlucc_get_option( 'cursor_color_hover_opacity', '0.6' );
        $cursor_bd            = $this->hex2rgba( $cursor_color, $cursor_hover_opacity );
        $cursor_bg            = $this->hex2rgba( $cursor_color, $cursor_opacity );
        $css_data             = '
            .dl-cursor{ background-color: ' . esc_attr( $cursor_color ) . '; }
            .dl-fill{border-color: ' . esc_attr( $cursor_bd ) . ' }
            .dl-fill:before{ background-color: ' . esc_attr( $cursor_bg ) . '; }
        ';

        wp_add_inline_style( 'dlucc-style', $css_data );

        $data = [
            'status'        => esc_attr( dlucc_get_option( 'custom_cursor', 'enable' ) ),
            'mobile_status' => esc_attr( dlucc_get_option( 'mobile_custom_cursor', 'enable' ) ),
            'selectors'     => esc_attr( dlucc_get_option( 'hover_trigger_selectors', 'a,button' ) ),
        ];
        wp_localize_script( 'dlucc-script', 'dlucc_data', $data );

    }

    /**
     * Admin Scripts/Styles
     *
     * @return void
     */
    public function admin_scripts( $hook ) {
        if ($hook != 'settings_page_dl-custom-cursor-options') {
            return;
        }
        wp_register_style( 'dlucc-options', DLUCC_ASSETS . '/css/admin-style.css', [], DLUCC_VERSION );
        wp_enqueue_style( 'dlucc-options' );
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker-alpha', plugins_url( '../assets/js/wp-color-picker-alpha.min.js', __FILE__ ), array('wp-color-picker'), false, true );
        wp_enqueue_script('dlucc-color-picker-script', plugins_url( '../assets/js/color-picker.js', __FILE__ ), array('wp-color-picker-alpha'), false, true );
    }

    public function hex2rgba( $color, $opacity = false ) {

        $default = 'rgb(0,0,0)';

        //Return default if no color provided
        if ( empty( $color ) ) {
            return $default;
        }

        //Sanitize $color if "#" is provided
        if ( $color[0] == '#' ) {
            $color = substr( $color, 1 );
        }

        //Check if color has 6 or 3 characters and get values
        if ( strlen( $color ) == 6 ) {
            $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
            $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb = array_map( 'hexdec', $hex );

        //Check if opacity is set(rgba or rgb)
        if ( $opacity ) {
            if ( abs( $opacity ) > 1 ) {
                $opacity = 1.0;
            }

            $output = 'rgba(' . implode( ",", $rgb ) . ',' . $opacity . ')';
        } else {
            $output = 'rgb(' . implode( ",", $rgb ) . ')';
        }

        //Return rgb(a) color string
        return $output;
    }

}