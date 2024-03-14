<?php
/**
 * This file is included with WP Discord Invite WordPress Plugin (https://wordpress.com/plugins/wp-discord-invite), Developed by Sarvesh M Rao (https://sarveshmrao.in/).
 * This file is licensed under Generl Public License v2 (GPLv2)  or later.
 * Using the code on whole or in part against the license can lead to legal prosecution.
 * 
 * Sarvesh M Rao
 * https://sarveshmrao.in/
 */

if (!defined("ABSPATH")) {
  exit();
}

add_filter( 'plugin_row_meta', 'smr_discord_row_meta','./../wp-discord-invite.php', 10, 2 );
 
function smr_discord_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {
 
    if ( strpos( $plugin_file, 'wp-discord-invite.php' ) !== false ) {
        $new_links = array(
                'doc' => '<a href="https://docs.sarveshmrao.in/en/wp-discord-invite?mtm_campaign=WP%20Discord%20Invite&mtm_kwd=plugin-meta" target="_blank">Dcumentation</a>',
                'review' => '<a href="https://wordpress.org/support/plugin/wp-discord-invite/reviews/" target="_blank">Leave a review!</a>'
                );
         
        $plugin_meta = array_merge( $plugin_meta, $new_links );
    }
     
    return $plugin_meta;
}