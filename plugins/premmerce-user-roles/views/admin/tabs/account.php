<?php
if ( ! defined('WPINC')) {
    die;
}

if (function_exists('premmerce_re_fs') && premmerce_re_fs()->is_registered()) {
    premmerce_re_fs()->add_filter('hide_account_tabs', '__return_true');
    premmerce_re_fs()->_account_page_load();
    premmerce_re_fs()->_account_page_render();
}
