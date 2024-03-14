<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * Plugin Name: Youtube Thumbnail to Featured Image
 * Plugin URI: https://estudioweb.pro/featured-youtube-thumbnail
 * Description: This plugin sets a youtube thumbnail as a featured image automatically.
 * Version: 1.1
 * Author: Chuy Sánchez
 * Author URI: https://estudioweb.pro
 * License: GPL2
 */

	/*  Copyright 2015  Chuy Sánchez  (email : soyjesusb@gmail.com)

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
	*/

/**
* Youtube Thumbnail to Featured Image
*/
class YouTubeThumbnail
{
	
	function __construct()
	{
		add_action( 'add_meta_boxes', array($this,'fyt_init_meta_boxes') );
		add_action( 'save_post', array($this,'fyt_save_meta_box') );
	}

	public function fyt_init_meta_boxes()
	{
		add_meta_box( 'fyt-meta-box', __('YouTube URL'), array($this,'fyt_render'), 'post', 'side', 'high' );
	}

	public function fyt_render($post)
	{
		wp_nonce_field( 'fyt_meta_box', 'fyt_meta_box_nonce' );
		?>
		<label>YouTube Video URL</label>
		<input type="text" class="widefat" name="fyt_video_url" placeholder="Insert a YouTube URL" value="<?php echo get_post_meta( $post->ID, 'fyt_video_url', true ); ?>">
		<?php
	}

	public function fyt_save_meta_box($post_id)
	{
		if (!isset($_POST['fyt_meta_box_nonce'])) return $post_id;

		if (!wp_verify_nonce( $_POST['fyt_meta_box_nonce'], 'fyt_meta_box' )) return $post_id;

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;

		if ('post' == $_POST['post_type']) {
			if (!current_user_can( 'edit_post' , $post_id )) {
				return $post_id;
			}
		}

		$youtubeVideoUrl = sanitize_text_field( $_POST['fyt_video_url'] );

		update_post_meta( $post_id, 'fyt_video_url', $youtubeVideoUrl );

		// Get the video thumbnail and attach to post
		$ytVideoParts = parse_url($youtubeVideoUrl);
		parse_str($ytVideoParts['query'],$ytQuery);
		$ytVideoId = $ytQuery['v'];
		$ytImgUrl = 'http://img.youtube.com/vi/'.$ytQuery['v'].'/maxresdefault.jpg';
		$filename = $ytQuery['v'] . '_maxres.jpg';
		$fileContents = file_get_contents($ytImgUrl);

		if ( !$fileContents ) {
			return false;
		}

		$upload_file = wp_upload_bits($ytQuery['v'] . '_maxres.jpg', null, $fileContents);
		if (!$upload_file['error']) {
			$wp_filetype = wp_check_filetype($filename, null );
			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_parent' => $parent_post_id,
				'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
				'post_content' => '',
				'post_status' => 'inherit'
			);
			$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $parent_post_id );
			if (!is_wp_error($attachment_id)) {
				require_once(ABSPATH . "wp-admin" . '/includes/image.php');
				$attachment_data = wp_generate_attachment_metadata( $attachment_id, $ytQuery['v'] . '_maxres.jpg' );
				$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
				wp_update_attachment_metadata( $attachment_id,  $attachment_data );

				add_post_meta($post_id,'_thumbnail_id',$attachment_id);
			}
		}
	}
}

$youTubeThumbnail = new YouTubeThumbnail();