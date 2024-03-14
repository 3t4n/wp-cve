<?php
/*

**************************************************************************

Plugin Name:  Autoset Featured Image
Plugin URI:   http://www.arefly.com/autoset-featured-image/
Description:  Auto Set the first image of your post to Featured Image.
Version:      1.2.2
Author:       Arefly
Author URI:   http://www.arefly.com/

**************************************************************************

	Copyright 2014  Arefly  (email : eflyjason@gmail.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

**************************************************************************/

define("AUTOSET_FEATURED_IMAGE_PLUGIN_URL", plugin_dir_url( __FILE__ ));
define("AUTOSET_FEATURED_IMAGE_FULL_DIR", plugin_dir_path( __FILE__ ));
define("AUTOSET_FEATURED_IMAGE_TEXT_DOMAIN", "autoset-featured-image");

function autoset_featured_image(){
	global $post;
	$no_featured_image = get_post_custom_values('no_featured_image', $post->ID);
	if(empty($no_featured_image[0])){
		$already_has_thumb = has_post_thumbnail($post->ID);
		if (!$already_has_thumb){
			$attached_image = get_children("post_parent=$post->ID&post_type=attachment&post_mime_type=image&numberposts=1");
			if ($attached_image){
				foreach ($attached_image as $attachment_id => $attachment) {
					set_post_thumbnail($post->ID, $attachment_id);
				}
			}
		}
	}
}
add_action('the_post', 'autoset_featured_image');
add_action('save_post', 'autoset_featured_image');
add_action('draft_to_publish', 'autoset_featured_image');
add_action('new_to_publish', 'autoset_featured_image');
add_action('pending_to_publish', 'autoset_featured_image');
add_action('future_to_publish', 'autoset_featured_image');
