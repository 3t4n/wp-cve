<?php
/**
 * Get database updated to 2.1.0
 *
 * @package team-free
 * @subpackage team-free/src/Admin/update
 */

// Change slug for member post type.
add_action( 'init', 'sptp_member_permalink_flush' );
/**
 * Flush permalink.
 *
 * @return void
 */
function sptp_member_permalink_flush() {
	flush_rewrite_rules();
}

update_option( 'sp_wp_team_version', '2.1.0' );
update_option( 'sp_wp_team_db_version', '2.1.0' );
