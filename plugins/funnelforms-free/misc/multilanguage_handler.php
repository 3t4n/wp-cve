<?php

function fnsf_af2_plugin_init_language() {
    load_plugin_textdomain( 'funnelforms-free', false, AF2F_LANGUAGES_PATH );
}
add_action('init', 'fnsf_af2_plugin_init_language'); 