<?php

use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

global $wpdb;

return [
    'UPDATE '.$wpdb->prefix.WordpressDatabaseService::TABLE_TERM_TAXONOMY.' SET `taxonomy`="pa_brand" WHERE `taxonomy`="pa_marca";'
];