<?php

/**
 * Metabox: Publish.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
?>

<div class="acadp">
	<?php if ( 0 == $never_expires && isset( $post_meta['expiry_date'] ) ) : ?>
		<div class="misc-pub-section misc-pub-acadp-expiry_date">
			<label for="acadp-form-control-expiry_date" class="acadp-block acadp-mb-2">
				<?php esc_html_e( 'Listing', 'advanced-classifieds-and-directory-pro' ); ?>
				<span class="acadp-font-medium">
					<?php esc_html_e( 'Expiry DateTime', 'advanced-classifieds-and-directory-pro' ); ?>
				</span>:
			</label>

			<input type="text" name="expiry_date" id="acadp-form-control-expiry_date" class="acadp-form-control acadp-form-control-datetime-picker acadp-form-input widefat" placeholder="0000-00-00 00:00:00" value="<?php echo esc_attr( $post_meta['expiry_date'][0] ); ?>" />
		</div>
	<?php endif; ?>
		
	<div class="misc-pub-section misc-pub-acadp-never_expires">
		<label>
			<input type="checkbox" name="never_expires" value="1" <?php if ( isset( $post_meta['never_expires'] ) ) checked( $post_meta['never_expires'][0], 1 ); ?>>
			<span class="acadp-font-medium">
				<?php esc_html_e( 'Never Expires', 'advanced-classifieds-and-directory-pro' ); ?>
			</span>
		</label>
	</div>

	<?php if ( $has_featured ) : ?>
		<div class="misc-pub-section misc-pub-acadp-featured">
			<label>
				<input type="checkbox" name="featured" value="1" <?php if ( isset( $post_meta['featured'] ) ) checked( $post_meta['featured'][0], 1 ); ?>>
				<?php esc_html_e( 'Mark as', 'advanced-classifieds-and-directory-pro' ); ?>
				<span class="acadp-font-medium">
					<?php echo esc_html( $featured_settings['label'] ); ?>
				</span>
			</label>
		</div>
	<?php endif; ?>

	<?php if ( $mark_as_sold ) : ?>
		<div class="misc-pub-section misc-pub-acadp-sold">
			<label>
				<input type="checkbox" name="sold" value="1" <?php if ( isset( $post_meta['sold'] ) ) checked( $post_meta['sold'][0], 1 ); ?>>
				<?php esc_html_e( 'Mark as', 'advanced-classifieds-and-directory-pro' ); ?>
				<span class="acadp-font-medium">
					<?php echo esc_html( $badges_settings['sold_listing_label'] ); ?>
				</span>
			</label>
		</div>
	<?php endif; ?>

	<input type="hidden" name="listing_status" value="<?php echo isset( $post_meta['listing_status'] ) ? esc_attr( $post_meta['listing_status'][0] ) : 'post_status'; ?>" />
</div>