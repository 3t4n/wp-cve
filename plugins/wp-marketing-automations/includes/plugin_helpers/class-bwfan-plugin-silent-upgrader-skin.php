<?php
// include the required file
require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader-skin.php';

/**
 * class BWFAN_Plugin_Silent_Upgrader_Skin
 */
class BWFAN_Plugin_Silent_Upgrader_Skin extends WP_Upgrader_Skin
{

    /**
     * Empty out the header of its HTML content and only check to see if it has
     * been performed or not.
     *
     * @since 1.5.6.1
     */
    public function header()
    {
    }

    /**
     * Empty out the footer of its HTML contents.
     *
     * @since 1.5.6.1
     */
    public function footer()
    {
    }

    /**
     * Instead of outputting HTML for errors, just return them.
     *
     */
    public function error($errors)
    {
        return $errors;
    }

    /**
     * Empty out JavaScript output that calls function to decrement the update counts.
     *
     */
    public function decrement_update_count($type)
    {
    }
}
