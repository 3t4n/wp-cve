<?php
/**
 * Handles Post Setting metabox HTML
 *
 * @package Audio Player with Playlist Ultimate
 * @since 1.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $wp_version;

// Taking some variables
$prefix			= APWPULTIMATE_META_PREFIX; // Metabox prefix

// Getting saved values
$artist_name	= get_post_meta( $post->ID, $prefix.'artist_name', true );
$audio_file		= get_post_meta( $post->ID, $prefix.'audio_file', true );
$duration		= get_post_meta( $post->ID, $prefix.'duration', true );
?>
<table class="form-table apwpultimate-post-sett-tbl">
	<tbody>

		<tr>
			<th>
				<?php esc_html_e('Upload Audio File','audio-player-with-playlist-ultimate');?>
			</th>
			<td>
				<input type="text" name="<?php echo esc_attr($prefix).'audio_file';?>" value="<?php echo esc_url($audio_file); ?>" id="apwpultimate-audio-file" class="regular-text apwpultimate-audio-file" />
				<input type="button" name="banner_default_img" class="button-secondary apwpultimate-audio-file-uploader" value="<?php esc_html_e( 'Upload audio file', 'audio-player-with-playlist-ultimate'); ?>" />
				<input type="button" name="popu_default_file_clear" id="audio-default-file-clear" class="button button-secondary audio-file-clear" value="<?php esc_html_e( 'Clear', 'audio-player-with-playlist-ultimate'); ?>" /> <br />
				<span class="description"><?php esc_html_e( 'Upload audio file.','audio-player-with-playlist-ultimate' ); ?></span>
				
			</td>
		</tr>

		<!-- artist_name -->
		<tr valign="top">
			<th scope="row">
				<label for="apwpultimate-artist-name"><?php esc_html_e('Artist Name', 'audio-player-with-playlist-ultimate'); ?></label>
			</th>
			<td class="row-meta">
				<input type="text" name="<?php echo esc_attr($prefix);?>artist_name" id="apwpultimate-artist-name" value="<?php echo esc_attr($artist_name); ?>" class="apwpultimate-artist-name regular-text" placeholder="<?php esc_html_e('Ninja', 'audio-player-with-playlist-ultimate'); ?>" /><br/>
				<span class="description"><?php esc_html_e('Enter artist name', 'audio-player-with-playlist-ultimate'); ?></span>
			</td>
		</tr>

		<!-- artist_name -->
		<tr valign="top">
			<th scope="row">
				<label for="apwpultimate-duration"><?php esc_html_e('Audio Duration', 'audio-player-with-playlist-ultimate'); ?></label>
			</th>
			<td class="row-meta">
				<input type="text" name="<?php echo esc_attr($prefix);?>duration" id="apwpultimate-duration" value="<?php echo esc_attr($duration); ?>" class="apwpultimate-duration regular-text" placeholder="<?php esc_html_e('3:30', 'audio-player-with-playlist-ultimate'); ?>" /><br/>
				<span class="description"><?php esc_html_e('Enter audio duration', 'audio-player-with-playlist-ultimate'); ?></span>
			</td>
		</tr>

	</tbody>
</table><!-- end .apwpultimate-post-sett-tbl -->