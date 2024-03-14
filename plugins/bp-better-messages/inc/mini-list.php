<?php
defined( 'ABSPATH' ) || exit;

class Better_Messages_Mini_List
{

    public static function instance()
    {

        // Store the instance locally to avoid private static replication
        static $instance = null;

        // Only run these methods if they haven't been run previously
        if ( null === $instance ) {
            $instance = new Better_Messages_Mini_List;
            $instance->setup_actions();
        }

        // Always return the instance
        return $instance;

        // The last metroid is in captivity. The galaxy is at peace.
    }

    public function setup_actions()
    {
        add_action('wp_footer', array( $this, 'html' ), 199);
    }


    public function html()
    {
        if ( ! is_user_logged_in() && ! Better_Messages()->guests->guest_access_enabled() ) return false;

        $threads = isset( Better_Messages()->script_variables['miniMessages'] ) && Better_Messages()->script_variables['miniMessages'] === '1';


        $friends = is_user_logged_in() && isset( Better_Messages()->script_variables['miniFriends'] ) && Better_Messages()->script_variables['miniFriends'];
        $groups  = is_user_logged_in() && isset( Better_Messages()->script_variables['miniGroups'] ) && Better_Messages()->script_variables['miniGroups'];

        if( $threads || $friends || $groups ) {
            echo '<div class="bp-messages-wrap bp-better-messages-list"></div>';
        }
    }
}

function Better_Messages_Mini_List()
{
    return Better_Messages_Mini_List::instance();
}
