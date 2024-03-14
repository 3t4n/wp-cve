<?php

/**
 * HQ Reservation Link Lang Helper
 *
 */

if (!defined('FW')) {
    die('Forbidden');
}

function hq_reservation_link_lang_helper($atts)
{

    extract(shortcode_atts(array(
        'id' => ''
    ), $atts));

    if (get_locale() == 'en_US') :
        echo do_shortcode('[hq_rentals_reservations id=' . $id . ']');
    elseif (get_locale() == 'es_ES') :
        echo do_shortcode('[hq_rentals_reservations id=' . $id . ' forced_locale=es]');
    else :
        echo do_shortcode('[hq_rentals_reservations id=' . $id . ' forced_locale=pt]');
    endif;
}

add_shortcode('hq_reservation_link_lang_helper', 'hq_reservation_link_lang_helper');
