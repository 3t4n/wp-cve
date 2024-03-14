<?php

/**
 * Search Form.
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
			
		<div class="acadp-form-group">
			<label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>" class="acadp-form-label">
				<?php esc_html_e( 'Select template', 'advanced-classifieds-and-directory-pro' ); ?>
			</label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>" class="acadp-form-control acadp-form-select widefat"> 
				<?php
				$options = array(
					'vertical' => __( 'Vertical', 'advanced-classifieds-and-directory-pro' ),
					'inline'   => __( 'Horizontal', 'advanced-classifieds-and-directory-pro' )
				);
			
				foreach ( $options as $key => $value ) {
					printf( 
						'<option value="%s"%s>%s</option>', 
						$key, 
						selected( $key, $instance['style'] ), 
						esc_html( $value )
					);
				}
				?>
			</select>
		</div>

		<div class="acadp-form-group">
			<label for="<?php echo esc_attr( $this->get_field_id( 'search_by_keyword' ) ); ?>" class="acadp-flex acadp-gap-1.5 acadp-items-center">
				<input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'search_by_keyword' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'search_by_keyword' ) ); ?>" class="acadp-form-control acadp-form-checkbox" value="1" <?php checked( $instance['search_by_keyword'] ); ?> />
				<?php esc_html_e( 'Search by keyword', 'advanced-classifieds-and-directory-pro' ); ?>
			</label>
		</div>
		
		<?php if ( $has_location ) : ?>
			<div class="acadp-form-group">
				<label for="<?php echo esc_attr( $this->get_field_id( 'search_by_location' ) ); ?>" class="acadp-flex acadp-gap-1.5 acadp-items-center">
					<input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'search_by_location' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'search_by_location' ) ); ?>" class="acadp-form-control acadp-form-checkbox" value="1" <?php checked( $instance['search_by_location'] ); ?> />
					<?php esc_html_e( 'Search by location', 'advanced-classifieds-and-directory-pro' ); ?>
				</label>
			</div>
		<?php endif; ?>

		<div class="acadp-form-group">
			<label for="<?php echo esc_attr( $this->get_field_id( 'search_by_category' ) ); ?>" class="acadp-flex acadp-gap-1.5 acadp-items-center">
				<input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'search_by_category' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'search_by_category' ) ); ?>" class="acadp-form-control acadp-form-checkbox" value="1" <?php checked( $instance['search_by_category'] ); ?> />
				<?php esc_html_e( 'Search by category', 'advanced-classifieds-and-directory-pro' ); ?>
			</label>
		</div>

		<div class="acadp-form-group">
			<label for="<?php echo esc_attr( $this->get_field_id( 'search_by_custom_fields' ) ); ?>" class="acadp-flex acadp-gap-1.5 acadp-items-center">
				<input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'search_by_custom_fields' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'search_by_custom_fields' ) ); ?>" class="acadp-form-control acadp-form-checkbox" value="1" <?php checked( $instance['search_by_custom_fields'] ); ?> />
				<?php esc_html_e( 'Search by custom fields', 'advanced-classifieds-and-directory-pro' ); ?>
			</label>
		</div>

		<?php if ( $has_price ) : ?>
			<div class="acadp-form-group">
				<label for="<?php echo esc_attr( $this->get_field_id( 'search_by_price' ) ); ?>" class="acadp-flex acadp-gap-1.5 acadp-items-center">
					<input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'search_by_price' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'search_by_price' ) ); ?>" class="acadp-form-control acadp-form-checkbox" value="1" <?php checked( $instance['search_by_price'] ); ?> />
					<?php esc_html_e( 'Search by price', 'advanced-classifieds-and-directory-pro' ); ?>
				</label>
		</div>
		<?php endif; ?>
	</div>
</div>