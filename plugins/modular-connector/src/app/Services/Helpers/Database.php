<?php

namespace Modular\Connector\Services\Helpers;

/**
 * @deprecated
 */
class Database
{
    /**
     * Returns the first 'administrator' user found or null if no one found.
     *
     * @return mixed|\stdClass|null
     */
    public function getFirstAdministratorUser()
    {
        global $wpdb;

        $query = "SELECT * FROM {$wpdb->prefix}users INNER JOIN {$wpdb->prefix}usermeta ON {$wpdb->prefix}usermeta.user_id =
                 {$wpdb->prefix}users.id WHERE {$wpdb->prefix}usermeta.meta_key = '{$wpdb->prefix}user_level' AND 
                 {$wpdb->prefix}usermeta.meta_value > %d;";

        $wpdb->get_row($wpdb->prepare($query, 8));

        return $wpdb->last_result[0] ?? null;
    }
}
