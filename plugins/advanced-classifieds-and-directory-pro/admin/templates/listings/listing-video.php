<?php

/**
 * Metabox: Video.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */

if ( ! $general_settings['has_video'] ) {
	return false;
}
?>

<div class="acadp">
	<input type="url" name="video" class="acadp-form-control acadp-form-input acadp-my-2 widefat" placeholder="<?php esc_attr_e( 'Enter your YouTube or Vimeo video URL', 'advanced-classifieds-and-directory-pro' ); ?>" value="<?php if ( isset( $post_meta['video'] ) ) echo esc_attr( $post_meta['video'][0] ); ?>" />
</div>
