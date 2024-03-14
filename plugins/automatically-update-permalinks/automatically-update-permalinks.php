<?php
/**
 * Plugin Name: Automatically Update Permalinks
 * Description: Automatically updates the permalink (slug) of a post or page when its title is changed.
 * Version: 1.0.4
 * Author: WP Zone
 * Author URI: https://wpzone.co/?utm_source=automatically-update-permalinks&utm_medium=link&utm_campaign=wp-plugin-author-uri
 * License: GNU General Public License version 3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/*
Automatically Update Permalinks plugin
Copyright (C) 2022 WP Zone

Despite the following, this project is licensed exclusively under
GNU General Public License (GPL) version 3 (or later versions).
This statement modifies the following text.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.

============

This plugin includes code based on WordPress, released
under GPLv2+, licensed under GPLv3 or later (see ./license/wp-license.txt
for the license and additional credits applicable to WordPress, and ./license/license.txt
for GPLv3 text).
*/

add_action('post_updated', 'hm_aup_post_updated', 10, 3);
function hm_aup_post_updated($postId, $after, $before) {
	if ( ( isset($_POST['post_title']) || ( defined('REST_REQUEST') && REST_REQUEST ) ) && $after->post_title != $before->post_title && empty($_POST['hm_aup_disable']) ) {
		$after->post_name = ''; // Reset permalink
		wp_update_post($after);
	}
}

add_action('post_submitbox_start', 'hm_aup_submitbox');
function hm_aup_submitbox() {
	global $post;
	echo('
	<div style="margin-bottom: 8px;">
		<label>
			<input type="checkbox" name="hm_aup_disable" />
			Disable automatic permalink update
		</label>
	</div>
	');
}