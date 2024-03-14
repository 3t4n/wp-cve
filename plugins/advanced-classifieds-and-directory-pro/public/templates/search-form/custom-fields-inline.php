<?php

/**
 * Custom Fields (Horizontal Search Form).
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */

if ( ! $acadp_query->have_posts() ) {
	return false;
}

$cached_meta = array();
if ( isset( $_POST['cached_meta'] ) ) {
	parse_str( $_POST['cached_meta'], $cached_meta );
} 

while ( $acadp_query->have_posts() ) : 
	$acadp_query->the_post(); 

	$field_id = (int) $post->ID;
	$field_meta = get_post_meta( $field_id );
	$field_type = $field_meta['type'][0];
	$field_placeholder = $field_meta['placeholder'][0];

	// Field instructions are disabled in the search form.
	$field_description = ''; // $field_meta['instructions'][0];

	$field_value = '';

	if ( isset( $_GET['cf'][ $field_id ] ) ) {
		$field_value = $_GET['cf'][ $field_id ];
	}

	if ( isset( $cached_meta['cf'] ) && isset( $cached_meta['cf'][ $field_id ] ) ) {
		$field_value = $cached_meta['cf'][ $field_id ];
	}

	if ( 'text' == $field_type ) : ?>
		<!-- Text -->
		<div class="acadp-form-group acadp-form-group-custom-field">
			<label class="acadp-form-label">
				<?php echo esc_html( get_the_title() ); ?>
			</label>

			<?php
			$classes = array( 'acadp-form-control', 'acadp-form-input' );	

			printf( 
				'<input type="text" name="cf[%1$d]" class="%2$s" placeholder="%3$s" value="%4$s" />', 
				$field_id, 
				implode( ' ', $classes ),
				esc_attr( $field_placeholder ), 
				esc_attr( $field_value )
			);
			?>

			<?php if ( ! empty( $field_description ) ) : ?>
				<div class="acadp-form-description acadp-text-muted acadp-text-sm">
					<?php echo esc_html( $field_description ); ?>
				</div>
			<?php endif; ?>
		</div>
	<?php elseif ( 'textarea' == $field_type ) : ?>
		<!-- Textarea -->
		<div class="acadp-form-group acadp-form-group-custom-field">
			<label class="acadp-form-label">
				<?php echo esc_html( get_the_title() ); ?>
			</label>

			<?php
			$classes = array( 'acadp-form-control', 'acadp-form-textarea' );	

			printf( 
				'<textarea name="cf[%1$d]" class="%2$s" rows="%3$d" placeholder="%4$s">%5$s</textarea>', 
				$field_id, 
				implode( ' ', $classes ),
				esc_attr( $field_meta['rows'][0] ), 
				esc_attr( $field_placeholder ), 
				esc_textarea( $field_value ) 
			);
			?>

			<?php if ( ! empty( $field_description ) ) : ?>
				<div class="acadp-form-description acadp-text-muted acadp-text-sm">
					<?php echo esc_html( $field_description ); ?>
				</div>
			<?php endif; ?>
		</div>
	<?php elseif ( 'select' == $field_type ) : ?>
		<!-- Select -->
		<div class="acadp-form-group acadp-form-group-custom-field">
			<label class="acadp-form-label">
				<?php echo esc_html( get_the_title() ); ?>
			</label>

			<?php
			$classes = array( 'acadp-form-control', 'acadp-form-select' );	

			$choices = $field_meta['choices'][0];
			$choices = explode( "\n", trim( $choices ) );
		
			printf( 
				'<select name="cf[%1$d]" class="%2$s">', 
				$field_id, 
				implode( ' ', $classes )
			);

			if ( ! empty( $field_meta['allow_null'][0] ) ) {
				printf( 
					'<option value="">%s</option>', 
					'— ' . esc_html__( 'Select an Option', 'advanced-classifieds-and-directory-pro' ) . ' —' 
				);
			}

			foreach ( $choices as $choice ) {
				if ( strpos( $choice, ':' ) !== false ) {
					$parts = explode( ':', $choice );
					$parts = array_map( 'trim', $parts );
					
					$value  = $parts[0];
					$label  = $parts[1];
				} else {
					$value  = trim( $choice );
					$label  = $value;
				}
	
				printf( 
					'<option value="%s"%s>%s</option>', 
					esc_attr( $value ), 
					( $value == trim( $field_value ) ? ' selected' : '' ), 
					esc_html( $label ) 
				);
			} 
			echo '</select>';
			?>

			<?php if ( ! empty( $field_description ) ) : ?>
				<div class="acadp-form-description acadp-text-muted acadp-text-sm">
					<?php echo esc_html( $field_description ); ?>
				</div>
			<?php endif; ?>
		</div>
	<?php elseif ( 'checkbox' == $field_type ) : ?>
		<!-- Checkbox -->
		<fieldset class="acadp-form-group acadp-form-group-custom-field">
			<legend class="acadp-form-label">
				<?php echo esc_html( get_the_title() ); ?>
			</legend>

			<div class="acadp-form-checkbox-group">
				<?php
				$classes = array( 'acadp-form-control', 'acadp-form-checkbox' );	

				$choices = $field_meta['choices'][0];
				$choices = explode( "\n", trim( $choices ) );
			
				$field_value = is_array( $field_value ) ? $field_value : explode( "\n", $field_value );
				$field_value = array_map( 'trim', $field_value );			
			
				foreach ( $choices as $index => $choice ) {
					if ( strpos( $choice, ':' ) !== false ) {
						$parts = explode( ':', $choice );
						$parts = array_map( 'trim', $parts );
						
						$value = $parts[0];
						$label = $parts[1];
					} else {
						$value = trim( $choice );
						$label = $value;
					}
				
					$attributes = [];
					if ( in_array( $value, $field_value ) ) $attributes[] = 'checked="checked"';
			
					printf( 
						'<label class="acadp-flex acadp-gap-1.5 acadp-items-center"><input type="checkbox" name="cf[%1$d][%2$d]" class="%3$s" value="%4$s"%5$s/>%6$s</label>', 
						$field_id, 
						$index, 
						implode( ' ', $classes ),
						esc_attr( $value ), 
						implode( ' ', $attributes ), 
						esc_html( $label ) 
					);
				}
				?>
			</div>

			<?php if ( ! empty( $field_description ) ) : ?>
				<div class="acadp-form-description acadp-text-muted acadp-text-sm">
					<?php echo esc_html( $field_description ); ?>
				</div>
			<?php endif; ?>
		</fieldset>
	<?php elseif ( 'radio' == $field_type ) : ?>
		<!-- Radio -->
		<fieldset class="acadp-form-group acadp-form-group-custom-field">
			<legend class="acadp-form-label">
				<?php echo esc_html( get_the_title() ); ?>
			</legend>

			<div class="acadp-form-radio-group">
				<?php
				$classes = array( 'acadp-form-control', 'acadp-form-radio' );	

				$choices = $field_meta['choices'][0];
				$choices = explode( "\n", trim( $choices ) );			
			
				foreach ( $choices as $choice ) {
					if ( strpos( $choice, ':' ) !== false ) {
						$parts = explode( ':', $choice );
						$parts = array_map( 'trim', $parts );
						
						$value  = $parts[0];
						$label  = $parts[1];
					} else {
						$value  = trim( $choice );
						$label  = $value;
					}
				
					$attributes = array();
					if ( $value == trim( $field_value ) ) $attributes[] = 'checked="checked"';

					printf( 
						'<label class="acadp-flex acadp-gap-1.5 acadp-items-center"><input type="radio" name="cf[%d]" class="%s" value="%s"%s/>%s</label>', 
						$field_id, 
						implode( ' ', $classes ),
						esc_attr( $value ), 
						implode( ' ', $attributes ),
						esc_html( $label ) 
					);
				}
				?>
			</div>

			<?php if ( ! empty( $field_description ) ) : ?>
				<div class="acadp-form-description acadp-text-muted acadp-text-sm">
					<?php echo esc_html( $field_description ); ?>
				</div>
			<?php endif; ?>
		</fieldset>
	<?php elseif ( 'number' == $field_type ) : ?>
		<!-- Number -->
		<div class="acadp-form-group acadp-form-group-custom-field">
			<label class="acadp-form-label">
				<?php echo esc_html( get_the_title() ); ?>
			</label>

			<?php
			$classes = array( 'acadp-form-control', 'acadp-form-input' );	

			$attributes = array();

			if ( isset( $field_meta['min'] ) && '' != $field_meta['min'][0] ) {
				$attributes[] = sprintf( 'min="%d"', (int) $field_meta['min'][0] );
			}

			if ( isset( $field_meta['max'] ) && '' != $field_meta['max'][0] ) {
				$attributes[] = sprintf( 'max="%d"', (int) $field_meta['max'][0] );
			}

			printf( 
				'<input type="number" name="cf[%1$d]" class="%2$s" placeholder="%3$s" value="%4$s" %5$s/>', 
				$field_id, 
				implode( ' ', $classes ),
				esc_attr( $field_placeholder ), 
				esc_attr( $field_value ), 
				implode( ' ', $attributes ) 
			);
			?>

			<?php if ( ! empty( $field_description ) ) : ?>
				<div class="acadp-form-description acadp-text-muted acadp-text-sm">
					<?php echo esc_html( $field_description ); ?>
				</div>
			<?php endif; ?>
		</div>
	<?php elseif ( 'range' == $field_type ) : ?>
		<!-- Range -->
		<div class="acadp-form-group acadp-form-group-custom-field">
			<label class="acadp-form-label">
				<?php echo esc_html( get_the_title() ); ?>
			</label>

			<div class="acadp-flex acadp-gap-2">
				<?php
				$classes = array( 'acadp-form-control', 'acadp-form-input', 'acadp-w-1/2' );	

				$field_value = array_map( 'sanitize_text_field', (array) $field_value );

				if ( 1 == count( $field_value ) ) {
					$field_value[1] = '';
				}

				$attributes = array();

				if ( isset( $field_meta['min'] ) && '' != $field_meta['min'][0] ) {
					$attributes[] = sprintf( 'min="%d"', (int) $field_meta['min'][0] );
				}

				if ( isset( $field_meta['max'] ) && '' != $field_meta['max'][0] ) {
					$attributes[] = sprintf( 'max="%d"', (int) $field_meta['max'][0] );
				}

				printf( 
					'<input type="number" name="cf[%1$d][0]" class="%2$s" placeholder="%3$s" value="%4$s" %5$s/>', 
					$field_id, 
					implode( ' ', $classes ),
					esc_attr__( 'min', 'advanced-classifieds-and-directory-pro' ),
					esc_attr( $field_value[0] ), 
					implode( ' ', $attributes ) 
				);

				printf( 
					'<input type="number" name="cf[%1$d][1]" class="%2$s" placeholder="%3$s" value="%4$s" %5$s/>', 
					$field_id, 
					implode( ' ', $classes ),
					esc_attr__( 'max', 'advanced-classifieds-and-directory-pro' ),
					esc_attr( $field_value[1] ), 
					implode( ' ', $attributes ) 
				);
				?>
			</div>

			<?php if ( ! empty( $field_description ) ) : ?>
				<div class="acadp-form-description acadp-text-muted acadp-text-sm">
					<?php echo esc_html( $field_description ); ?>
				</div>
			<?php endif; ?>
		</div>
	<?php elseif ( 'date' == $field_type ) : ?>
		<!-- Date -->
		<div class="acadp-form-group acadp-form-group-custom-field">
			<label class="acadp-form-label">
				<?php echo esc_html( get_the_title() ); ?>
			</label>

			<?php
			$classes = array( 'acadp-form-control', 'acadp-form-control-date-picker', 'acadp-form-input' );	

			$type_search = ! empty( $field_meta['type_search'][0] ) ? sanitize_text_field( $field_meta['type_search'][0] ) : '';
			if ( 'daterange' == $type_search ) {
				$classes[] = 'acadp-has-daterange';
			}						
							
			printf( 
				'<input type="text" name="cf[%1$d]" class="%2$s" placeholder="%3$s" value="%4$s" autocomplete="off" />', 
				$field_id, 
				implode( ' ', $classes ),
				esc_attr( $field_placeholder ), 
				esc_attr( $field_value )
			);
			?>

			<?php if ( ! empty( $field_description ) ) : ?>
				<div class="acadp-form-description acadp-text-muted acadp-text-sm">
					<?php echo esc_html( $field_description ); ?>
				</div>
			<?php endif; ?>
		</div>
	<?php elseif ( 'datetime' == $field_type ) : ?>
		<!-- DateTime -->
		<div class="acadp-form-group acadp-form-group-custom-field">
			<label class="acadp-form-label">
				<?php echo esc_html( get_the_title() ); ?>
			</label>

			<?php
			$classes = array( 'acadp-form-control', 'acadp-form-control-date-picker', 'acadp-form-input' );	

			$type_search = ! empty( $field_meta['type_search'][0] ) ? sanitize_text_field( $field_meta['type_search'][0] ) : '';
			if ( 'daterange' == $type_search ) {
				$classes[] = 'acadp-has-daterange';
			}
												
			printf( 
				'<input type="text" name="cf[%1$d]" class="%2$s" placeholder="%3$s" value="%4$s" autocomplete="off" />', 
				$field_id, 
				implode( ' ', $classes ),
				esc_attr( $field_placeholder ), 
				esc_attr( $field_value )
			);
			?>

			<?php if ( ! empty( $field_description ) ) : ?>
				<div class="acadp-form-description acadp-text-muted acadp-text-sm">
					<?php echo esc_html( $field_description ); ?>
				</div>
			<?php endif; ?>
		</div>
	<?php elseif ( 'url' == $field_type ) : ?>
		<!-- URL -->
		<div class="acadp-form-group acadp-form-group-custom-field">
			<label class="acadp-form-label">
				<?php echo esc_html( get_the_title() ); ?>
			</label>

			<?php
			$classes = array( 'acadp-form-control', 'acadp-form-input' );	

			printf( 
				'<input type="text" name="cf[%1$d]" class="%2$s" placeholder="%3$s" value="%4$s" />', 
				$field_id, 
				implode( ' ', $classes ),
				esc_attr( $field_placeholder ), 
				esc_url( $field_value )
			);
			?>

			<?php if ( ! empty( $field_description ) ) : ?>
				<div class="acadp-form-description acadp-text-muted acadp-text-sm">
					<?php echo esc_html( $field_description ); ?>
				</div>
			<?php endif; ?>
		</div>
	<?php else : ?>
		<!-- Hook for developers to add custom field types -->
		<?php do_action( 'acadp_custom_field', $post_id, $field_id ); ?>
	<?php endif;      
endwhile;
