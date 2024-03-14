<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once('wacs_notifications.php');

class Wacs_Bulk_Actions
{
    function wacs_delete_bulk_action($states, $referer)
    {
        foreach ($states as $state_delete) {
            $states = get_option('wacs_states');
            unset($states[$state_delete]);
            update_option('wacs_states', $states);
            delete_option('wacs_states');
            delete_option('wacs_country');
        }
        update_option('wacs_deleted', true);
        header('Location:'. $referer);
    }
}