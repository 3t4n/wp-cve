<?php

namespace GSBEH;

function is_divi_active() {
    if (!defined('ET_BUILDER_PLUGIN_ACTIVE') || !ET_BUILDER_PLUGIN_ACTIVE) return false;
    return et_core_is_builder_used_on_current_request();
}

function is_divi_editor() {
    if ( !empty($_POST['action']) && $_POST['action'] == 'et_pb_process_computed_property' && !empty($_POST['module_type']) && $_POST['module_type'] == 'divi_gs_behance' ) return true;
}

function minimize_css_simple($css) {
    // https://datayze.com/howto/minify-css-with-php
    $css = preg_replace('/\/\*((?!\*\/).)*\*\//', '', $css); // negative look ahead
    $css = preg_replace('/\s{2,}/', ' ', $css);
    $css = preg_replace('/\s*([:;{}])\s*/', '$1', $css);
    $css = preg_replace('/;}/', '}', $css);
    return $css;
}

function get_shortcodes() {
    return (array) plugin()->builder->fetch_shortcodes(null, false, true);
}