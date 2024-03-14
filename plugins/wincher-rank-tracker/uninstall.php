<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

delete_option('wincher_oauth_token');
