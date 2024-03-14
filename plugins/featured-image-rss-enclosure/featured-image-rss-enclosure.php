<?php
/*
Plugin Name: Featured Image RSS Enclosure
Description: Add featured images as separate enclosure fields in your site's RSS rather than having these images embedded with post content.
Author: timmcdaniels
Version: 1.0
Tested up to: 4.8
Requires at least: 4.6
Author URI: http://www.weareconvoy.com

Copyright 2017 by Tim McDaniels http://www.weareconvoy.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License,or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not,write to the Free Software
Foundation,Inc.,51 Franklin St,Fifth Floor,Boston,MA 02110-1301 USA
*/

function fire_add_rss_item_image() {
	global $post;
	if( has_post_thumbnail( $post->ID ) ) {
		$image_id = get_post_thumbnail_id( $post->ID );
		$url = wp_get_attachment_url( $image_id );
		$file_type = wp_check_filetype( basename( $url ) );
		$image_type = $file_type['type'];
		$attach = wp_get_attachment_metadata( $image_id );
		$upload_dir = wp_upload_dir();
		$length = 0;
		$file = $upload_dir['basedir'] . '/' . $attach['file'];
		if ( file_exists( $file ) ) {
			$length = filesize( $file );
		}
		echo "\t<enclosure url=\"{$url}\" length=\"$length\" type=\"{$image_type}\" />\n";
	}
}

add_action( 'rss2_item', 'fire_add_rss_item_image' );
add_action( 'rss_item', 'fire_add_rss_item_image' );

?>
