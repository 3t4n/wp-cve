<?php


add_action('init', 'fnsf_af2_v_migration');

function fnsf_af2_v_migration() {
    $af2_version_num_ = intval(get_option('af2_version_num_'));

    update_option('af2_version_num_', '2');
}