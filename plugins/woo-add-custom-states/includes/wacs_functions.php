<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Wacs_Functions
{
    function wacs_is_not_unique($state) {
        if(get_option('wacs_current_country') != get_option('wacs_country')) {
            delete_option('wacs_states');
        }
        if(get_option('wacs_states')) {
            if(array_key_exists($state, get_option('wacs_states'))) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function custom_wacs_states($states) {
        if(get_option('wacs_country') && get_option('wacs_states')) {
            $states[esc_attr(get_option('wacs_country'))] = array_map('esc_attr', get_option('wacs_states'));
	    asort($states[esc_attr(get_option('wacs_country'))]);
        }
        return $states;
    }
}