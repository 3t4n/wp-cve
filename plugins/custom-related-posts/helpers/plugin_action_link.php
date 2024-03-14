<?php

class CRP_Plugin_Action_Link {

    public function __construct()
    {
        add_filter( 'plugin_action_links_custom-related-posts/custom-related-posts.php', array( $this, 'action_links' ) );
    }

    public function action_links( $links )
    {
        $links[] = '<a href="'. get_admin_url(null, 'options-general.php?page=bv_settings_crp') .'">'.__( 'Settings', 'custom-related-posts' ).'</a>';
        $links[] = '<a href="https://help.bootstrapped.ventures/collection/155-custom-related-posts" target="_blank">'.__( 'Documentation', 'custom-related-posts' ).'</a>';

        return $links;
    }
}