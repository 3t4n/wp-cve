<?php

/**
 * Categories.
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
			<label for="<?php echo esc_attr( $this->get_field_id( 'template' ) ); ?>" class="acadp-form-label">
				<?php esc_html_e( 'Select template', 'advanced-classifieds-and-directory-pro' ); ?>
			</label> 
			<select name="<?php echo esc_attr( $this->get_field_name( 'template' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'template' ) ); ?>" class="acadp-form-control acadp-form-select widefat">
				<?php
				$options = array(
					'list'     => __( 'List', 'advanced-classifieds-and-directory-pro' ),
					'dropdown' => __( 'Dropdown', 'advanced-classifieds-and-directory-pro' )
				);
			
				foreach ( $options as $key => $value ) {
					printf( 
						'<option value="%s"%s>%s</option>', 
						$key, 
						selected( $key, $instance['template'] ), 
						esc_html( $value )
					);
				}
				?>
			</select>
		</div>

		<div class="acadp-form-group">
			<label for="<?php echo esc_attr( $this->get_field_id( 'parent' ) ); ?>" class="acadp-form-label">
				<?php esc_html_e( 'Select parent', 'advanced-classifieds-and-directory-pro' ); ?>
			</label> 
			<?php
			$categories_args = array(
				'placeholder' => '— ' . esc_html__( 'Select parent', 'advanced-classifieds-and-directory-pro' ) . ' —',
				'taxonomy'    => 'acadp_categories',
				'parent'      => 0,
				'name' 	      => esc_attr( $this->get_field_name( 'parent' ) ),
				'class'       => 'acadp-form-control widefat postform',
				'selected'    => (int) $instance['parent']
			);

			echo acadp_get_terms_dropdown_html( $categories_args );
			?>
		</div>

		<div class="acadp-form-group">
			<label for="<?php echo esc_attr( $this->get_field_id( 'imm_child_only' ) ); ?>" class="acadp-flex acadp-gap-1.5 acadp-items-center">
				<input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'imm_child_only' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'imm_child_only' ) ); ?>" class="acadp-form-control acadp-form-checkbox" value="1" <?php checked( $instance['imm_child_only'] ); ?> />
				<?php esc_html_e( 'Show only the immediate children of the selected category.', 'advanced-classifieds-and-directory-pro' ); ?>
			</label>
			<span class="description acadp-text-muted">
				<?php esc_html_e( 'Displays all the top level categories if no parent is selected.', 'advanced-classifieds-and-directory-pro' ); ?>
			</span>
		</div>

		<div class="acadp-form-group">
            <label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" class="acadp-form-label">
                <?php esc_html_e( 'Order by', 'advanced-classifieds-and-directory-pro' ); ?>
            </label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" class="acadp-form-control acadp-form-select widefat"> 
                <?php
                $options = array(
                    'id'    => __( 'ID', 'advanced-classifieds-and-directory-pro' ),
                    'count' => __( 'Count', 'advanced-classifieds-and-directory-pro' ),
                    'name'  => __( 'Name', 'advanced-classifieds-and-directory-pro' ),
                    'slug'  => __( 'Slug', 'advanced-classifieds-and-directory-pro' )
                );
            
                foreach ( $options as $key => $value ) {
                    printf( 
                        '<option value="%s"%s>%s</option>', 
                        $key, 
                        selected( $key, $instance['orderby'] ), 
                        esc_html( $value ) 
                    );
                }
                ?>
            </select>
        </div>

        <div class="acadp-form-group">
            <label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" class="acadp-form-label">
                <?php esc_html_e( 'Order', 'advanced-classifieds-and-directory-pro' ); ?>
            </label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" class="acadp-form-control acadp-form-select widefat"> 
                <?php
                $options = array(
                    'asc'  => __( 'Ascending', 'advanced-classifieds-and-directory-pro' ),
                    'desc' => __( 'Descending', 'advanced-classifieds-and-directory-pro' )
                );
            
                foreach ( $options as $key => $value ) {
                    printf( 
                        '<option value="%s"%s>%s</option>', 
                        $key, 
                        selected( $key, $instance['order'] ), 
                        esc_html( $value )
                    );
                }
                ?>
            </select>
        </div>
		
		<div class="acadp-form-group">
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>" class="acadp-flex acadp-gap-1.5 acadp-items-center">
				<input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'show_count' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>" class="acadp-form-control acadp-form-checkbox" value="1" <?php checked( $instance['show_count'] ); ?> />
				<?php esc_html_e( 'Show listings count', 'advanced-classifieds-and-directory-pro' ); ?>
			</label>
		</div>

		<div class="acadp-form-group">
			<label for="<?php echo esc_attr( $this->get_field_id( 'hide_empty' ) ); ?>" class="acadp-flex acadp-gap-1.5 acadp-items-center">
				<input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'hide_empty' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'hide_empty' ) ); ?>" class="acadp-form-control acadp-form-checkbox" value="1" <?php checked( $instance['hide_empty'] ); ?> />
				<?php esc_html_e( 'Hide empty categories', 'advanced-classifieds-and-directory-pro' ); ?>
			</label>
		</div>
	</div>
</div>