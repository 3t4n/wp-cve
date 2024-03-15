<?php

/*
 Plugin Name: Reverse Order Comments
 Plugin URI: http://www.zyblog.de/wordpress-plugins/reverse-order-comments/
 Description: Allows to display the comments in reverse order. Latest comment first, oldest last.
 Author: Tim Zylinski
 Version: 1.1.1
 Author URI: http://www.zyblog.de/
 */

function ro_comments_template( $file = '/comments.php', $separate_comments = false ) {
	add_filter('comments_array','ro_change_order');
	comments_template( $file, $separate_comments );
}

function ro_change_order($comms) {
	return array_reverse($comms);
}
?>