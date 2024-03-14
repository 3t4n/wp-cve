<?php
if( !defined( 'ABSPATH' ) )
	die( 'Cheatin\' uh?' );

function bawmpp_field_no_admin()
{
	global $bawmpp_options;
?>
	<label><input type="checkbox" name="bawmpp[no_admin]" <?php checked( $bawmpp_options['no_admin'], 'on' ); ?> /> <em><?php _e( 'Administrators does not need the password anymore.', 'bawmpp' ); ?></em></label>
<?php
}

function bawmpp_field_no_author()
{
	global $bawmpp_options;
?>
	<label><input type="checkbox" name="bawmpp[no_author]" <?php checked( $bawmpp_options['no_author'], 'on' ); ?> /> <em><?php _e( 'Posts authors does not need the password anymore.', 'bawmpp' ); ?></em></label>
<?php
}

function bawmpp_field_no_member()
{
	global $bawmpp_options;
?>
	<label><input type="checkbox" name="bawmpp[no_member]" <?php checked( $bawmpp_options['no_member'], 'on' ); ?> /> <em><?php _e( 'Logged members does not need the password anymore.', 'bawmpp' ); ?></em></label>
<?php
}

function bawmpp_field_clone_pass()
{
	global $bawmpp_options;
?>
	<label><input type="checkbox" name="bawmpp[clone_pass]" <?php checked( $bawmpp_options['clone_pass'], 'on' ); ?> /> <em><?php _e( 'Check me if you do not want that when adding or updating a main password, all parent and child pages/posts are updated with this password.', 'bawmpp' ); ?></em></label>
<?php
}
