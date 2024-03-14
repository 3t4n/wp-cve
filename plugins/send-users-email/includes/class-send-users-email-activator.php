<?php

/**
 * Fired during plugin activation.
 */
class Send_Users_Email_Activator
{
    public static function activate()
    {
        // Add email send capability to administrator
        $role = get_role( 'administrator' );
        if ( $role ) {
            $role->add_cap( SEND_USERS_EMAIL_SEND_MAIL_CAPABILITY );
        }
    }

}