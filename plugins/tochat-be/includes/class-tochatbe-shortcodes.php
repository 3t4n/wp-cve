<?php
defined( 'ABSPATH' ) || exit;

class TOCHATBE_Shortcode {

    public function __construct() {
        add_shortcode( 'tochatbe_whatsapp', array( $this, 'whatsapp_button' ) );
    }

    public function whatsapp_button( $atts ) {
        $a = shortcode_atts( array(
            'bg_color'   => '#075367',
            'text_color' => '#ffffff',
            'text'       => 'How can we help?',
            'number'     => '1234567890',
            'message'    => '',
            'id'         => '',
        ), $atts );

        if ( wp_is_mobile() ) {
            $url = 'https://api.whatsapp.com/send?phone=' . $a['number'] . '&text=' . $a['message'] . '';
        } else {
            $url = 'https://web.whatsapp.com/send?phone=' . $a['number'] . '&text=' . $a['message'] . '';
        }

        $style = '';
        $style .= 'background-color: ' . $a['bg_color'] . ';';
        $style .= 'color: ' . $a['text_color'] . ';';

        return sprintf(
            '<a href="%s" id="%s" class="tochatbe-whatsapp-button" style="%s" target="_blank"><i class="tochatbe-icon-whatsapp"></i> %s</a>',
            esc_url( $url ),
            esc_attr( $a['id'] ),
            esc_attr( $style ),
            esc_html( $a['text'] )
        );
    }
}

new TOCHATBE_Shortcode;