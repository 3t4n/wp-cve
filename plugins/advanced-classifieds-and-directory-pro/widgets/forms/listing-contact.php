<?php

/**
 * Listing Contact.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
?>

<div class="acadp">
	<div class="acadp-widget-form acadp-flex acadp-flex-col acadp-gap-3">
		<div class="acadp-form-group">
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="acadp-form-label">
				<?php esc_html_e( 'Title', 'advanced-classifieds-and-directory-pro' ); ?>
			</label> 
			<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="acadp-form-control acadp-form-input widefat" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</div>
	</div>
</div>