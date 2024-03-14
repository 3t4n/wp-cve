<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

delete_option('lwptoc_general');
delete_option('lwptoc_appearance');
delete_option('lwptoc_autoInsert');
delete_option('lwptoc_misc');

delete_option('rate_time');
