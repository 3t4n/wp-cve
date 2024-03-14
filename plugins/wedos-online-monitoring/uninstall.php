<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die();
}

delete_option('won_api_url');
delete_option('won_pair_checkId');
delete_option('won_pair_apiToken');
delete_option('won_pair_publicToken');
