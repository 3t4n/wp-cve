<?php

/**
 * Fired at plugin activation
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Includes;

class Dotdigital_WordPress_Activator
{
    /**
     * Activate.
     */
    public static function activate()
    {
        $messages = array('dm_API_form_title' => 'Subscribe to our newsletter', 'dm_API_invalid_email' => 'Please use a valid email address', 'dm_API_fill_required' => 'Please fill all the required fields', 'dm_API_nobook_message' => 'Please select one newsletter', 'dm_API_success_message' => 'You have now subscribed to our newsletter', 'dm_API_failure_message' => 'There was a problem signing you up.', 'dm_API_subs_button' => 'Subscribe');
        if (!get_option('dm_API_messages')) {
            add_option('dm_API_messages', $messages);
        }
    }
}
