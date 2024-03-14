<?php

/* class for font-family */
if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'digicrew_Font_Control' ) ) :
class digicrew_Font_Control extends WP_Customize_Control
{
    public function render_content() { ?>
        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
        <?php  $google_api_url = 'https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyC8GQW0seCcIYbo8xt_gXuToPK8xAMx83A';
            //lets fetch it
            $response = wp_remote_retrieve_body( wp_remote_get($google_api_url, array('sslverify' => false )));
            if($response=='') {  ?>
                <script><?php
                    echo esc_js('jQuery(document).ready(function() {alert("Something went wrong! this works only when you are connected to Internet....!!");});');
                ?></script> <?php

            }
            if( is_wp_error( $response ) ) {
                echo esc_html__('Something went wrong!', WL_COMPANION_DOMAIN );
            } else {
                $json_fonts = json_decode($response,  true);
                // that's it
                $items = $json_fonts['items'];
                $i = 0; ?>
                <select <?php $this->link(); ?> >
                    <?php foreach( $items as $item) { $i++; $str = $item['family']; ?>
                        <option  value="<?php echo esc_attr($str); ?>" <?php if($this->value()== $str) echo esc_attr('selected="selected"');?>><?php echo esc_attr($str); ?></option>
                    <?php } ?>
                </select> <?php
            }
    }
}
endif;
if ( ! function_exists( 'enigma_fonts_url' ) ) :
    /**
     * Register Google fonts.
     *
     * Create your own digicrew_fonts_url() function to override in a child theme.
     *
     * @since league 1.0
     *
     * @return string Google fonts URL for the theme.
     */
    function enigma_fonts_url()
    {
        $fonts_url = '';
        $fonts     = array();

        if ( 'off' !== _x( 'on', 'Open Sans font: on or off',WL_COMPANION_DOMAIN ) )
        {
            $fonts[] = 'Open+Sans:600,700';
        }
        if ( 'off' !== _x( 'on', 'Roboto font: on or off', WL_COMPANION_DOMAIN ) )
        {
            $fonts[] = 'Roboto:700';
        }
        if ( 'off' !== _x( 'on', 'Raleway font: on or off', WL_COMPANION_DOMAIN ) )
        {
            $fonts[] = 'Raleway:600';
        }


        if ( $fonts ) {
            $fonts_url = add_query_arg( array(
                'family' => urlencode( implode( '|', $fonts ) ),
            ), 'https://fonts.googleapis.com/css' );
        }
        return esc_url_raw( $fonts_url );
    }
endif;

 ?>
