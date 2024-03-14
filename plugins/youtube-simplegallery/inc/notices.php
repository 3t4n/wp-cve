<?php
/* Display a notice that can be dismissed */

add_action('admin_notices', 'YTSG_20_notice');

function YTSG_20_notice() {
	global $current_user, $pagenow;
	$user_id = $current_user->ID;
	
	/* Check that the user hasn't already clicked to ignore the message */
	if ( ! get_user_meta($user_id, 'YTSG_20_notice_ignore') ) {
		if( $pagenow != 'options-general.php' && $_GET['page'] != 'youtube-gallery-options' ) {
			echo '<div class="updated"><p>';
			printf(__('<a href="%1$s" style="float: right;">Dismiss</a>'), '?YTSG_20_notice_ignore=0');
			echo '<strong>YouTube SimpleGallery has gone through a major overhaul in version 2.0! ';
			printf(__('<a href="%1$s">Find out what’s new!</a>'), 'options-general.php?page=youtube-gallery-options&whatsnew=true');
			echo '</strong>';
			echo "</p></div>";
		}
	}
}

add_action('admin_init', 'YTSG_notice_ignore');

function YTSG_notice_ignore() {
	global $current_user, $pagenow;
	$user_id = $current_user->ID;

	if( $pagenow == 'options-general.php' && $_GET['page'] == 'youtube-gallery-options' && isset($_GET['whatsnew']) && $_GET['whatsnew']=='true' ) {
		add_user_meta($user_id, 'YTSG_20_notice_ignore', 'true', true);
	}

	if ( isset($_GET['YTSG_20_notice_ignore']) && '0' == $_GET['YTSG_20_notice_ignore'] ) {
		add_user_meta($user_id, 'YTSG_20_notice_ignore', 'true', true);
	}

	if ( isset($_GET['YTSG_20_whatsnew_ignore']) && '0' == $_GET['YTSG_20_whatsnew_ignore'] ) {
		add_user_meta($user_id, 'YTSG_20_whatsnew_ignore', 'true', true);
	}
}
?>