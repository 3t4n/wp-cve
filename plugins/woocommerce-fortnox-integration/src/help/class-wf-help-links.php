<?php

namespace src\help;

if ( !defined( 'ABSPATH' ) ) die();

class WF_Help_Links{

    public static function get_error_log_text( $code ){
        $link = self::get_link( $code );

        if( $link ){
            return '<a href="' . $link . ' " target="_blank">HJÄLPAVSNITT</a>';
        }
        return '';
    }

    public static function get_error_text( $code ){
        $link = self::get_link( $code );

        if( $link ){
            return '<button class="button button-primary"><a href="' . $link . ' " target="_blank" style="color:white;">HJÄLPAVSNITT</a></button>';
        }
        return '';
    }

    private static function get_link( $code ){

        $ref_table = self::get_ref_table();
        if( array_key_exists( $code, $ref_table ) ){
            return $ref_table[$code];
        }
    }

    private static function get_ref_table(){
        return array(
            "2001103" => 'https://docs.wetail.io/woocommerce/fortnox-integration/api-licens-saknas/',
            "2000310" => 'https://docs.wetail.io/woocommerce/fortnox-integration/skapa-nya-autentiseringsnycklar-accesstoken-logga-in-igen-till-fortnox/',
            "2000134" => 'https://docs.wetail.io/woocommerce/fortnox-integration/skapa-nya-autentiseringsnycklar-accesstoken-logga-in-igen-till-fortnox/',
            "2000166" => 'https://docs.wetail.io/woocommerce/artikelnummer-i-woocommerce/',
            "2001303" => 'https://docs.wetail.io/wordpress/kunde-inte-hitta-konto/',
            "2001304" => 'https://docs.wetail.io/wordpress/kunde-inte-hitta-konto/',
            "2001302" => 'https://docs.wetail.io/woocommerce/fortnox-integration/felkod-kunde-inte-hitta-artikel/',
            "2001700" => 'https://docs.wetail.io/woocommerce/fortnox-integration/felkod-kunde-inte-hamta-hitta-betalningsvillkor/',
            "2001411" => 'https://docs.wetail.io/wordpress/felkod-kunde-inte-hamta-hitta-leveransvillkor/',
            "2001242" => 'https://docs.wetail.io/woocommerce/fortnox-integration/felkod-order-som-sparas-far-inte-ha-en-faktura-genererad-fran-sig-och-innehalla-andringar-pa-varden-som-ar-lasta/',
            "2001412" => 'https://docs.wetail.io/wordpress/felkod-kunde-inte-hamta-hitta-leveransvillkor/',
            "1000000" => 'https://docs.wetail.io/woocommerce/fortnox-integration/fortnox-ogiltig-licensnyckel/',
        );
    }
}
