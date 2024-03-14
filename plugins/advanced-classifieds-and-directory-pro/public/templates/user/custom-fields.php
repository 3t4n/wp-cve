<?php

/**
 * Custom Fields.
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
	$field_required = ( 1 == $field_meta['required'][0] ? true : false );	
	$field_placeholder = $field_meta['placeholder'][0];
	$field_description = $field_meta['instructions'][0];

	$field_value = $field_meta['default_value'][0];

	if ( isset( $post_meta[ $field_id ] ) ) {
		$field_value = $post_meta[ $field_id ][0];
	}

	if ( isset( $cached_meta['acadp_fields'] ) && isset( $cached_meta['acadp_fields'][ $field_id ] ) ) {
		$field_value = $cached_meta['acadp_fields'][ $field_id ];
	}

	if ( 'text' == $field_type ) : ?>
		<!-- Text -->
		<div id="acadp-form-group-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-group">
			<label for="acadp-form-control-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-label">
				<?php echo esc_html( get_the_title() ); ?>
				<?php if ( $field_required ) echo '<span class="acadp-form-required" aria-hidden="true">*</span>'; ?>
			</label>

			<?php
			$classes = array( 'acadp-form-control', 'acadp-form-input' );	
			if ( $field_required ) $classes[] = 'acadp-form-validate';

			$attributes = [];
			if ( $field_required ) {
				$attributes[] = 'required';
				$attributes[] = sprintf( 'aria-describedby="acadp-form-error-%d"', $field_id );
			}

			printf( 
				'<input type="text" name="acadp_fields[%1$d]" id="acadp-form-control-%1$d" class="%2$s" placeholder="%3$s" value="%4$s" %5$s/>', 
				$field_id, 
				implode( ' ', $classes ),
				esc_attr( $field_placeholder ), 
				esc_attr( $field_value ), 
				implode( ' ', $attributes )
			);
			?>

			<div hidden id="acadp-form-error-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-error"></div>

			<?php if ( ! empty( $field_description ) ) : ?>
				<div class="acadp-form-description acadp-text-muted acadp-text-sm">
					<?php echo esc_html( $field_description ); ?>
				</div>
			<?php endif; ?>
		</div>
	<?php elseif ( 'textarea' == $field_type ) : ?>
		<!-- Textarea -->
		<div id="acadp-form-group-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-group">
			<label for="acadp-form-control-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-label">
				<?php echo esc_html( get_the_title() ); ?>
				<?php if ( $field_required ) echo '<span class="acadp-form-required" aria-hidden="true">*</span>'; ?>
			</label>

			<?php
			$classes = array( 'acadp-form-control', 'acadp-form-textarea' );	
			if ( $field_required ) $classes[] = 'acadp-form-validate';

			$attributes = [];
			if ( $field_required ) {
				$attributes[] = 'required';
				$attributes[] = sprintf( 'aria-describedby="acadp-form-error-%d"', $field_id );
			}

			printf( 
				'<textarea name="acadp_fields[%1$d]" id="acadp-form-control-%1$d" class="%2$s" rows="%3$d" placeholder="%4$s" %5$s>%6$s</textarea>', 
				$field_id, 
				implode( ' ', $classes ),
				esc_attr( $field_meta['rows'][0] ), 
				esc_attr( $field_placeholder ), 
				implode( ' ', $attributes ),
				esc_textarea( $field_value ) 
			);
			?>

			<div hidden id="acadp-form-error-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-error"></div>

			<?php if ( ! empty( $field_description ) ) : ?>
				<div class="acadp-form-description acadp-text-muted acadp-text-sm">
					<?php echo esc_html( $field_description ); ?>
				</div>
			<?php endif; ?>
		</div>
	<?php elseif ( 'select' == $field_type ) : ?>
		<!-- Select -->
		<div id="acadp-form-group-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-group">
			<label for="acadp-form-control-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-label">
				<?php echo esc_html( get_the_title() ); ?>
				<?php if ( $field_required ) echo '<span class="acadp-form-required" aria-hidden="true">*</span>'; ?>
			</label>

			<?php
			$classes = array( 'acadp-form-control', 'acadp-form-select' );	
			if ( $field_required ) $classes[] = 'acadp-form-validate';

			$attributes = [];
			if ( $field_required ) {
				$attributes[] = 'required';
				$attributes[] = sprintf( 'aria-describedby="acadp-form-error-%d"', $field_id );
			}

			$choices = $field_meta['choices'][0];
			$choices = explode( "\n", trim( $choices ) );
		
			printf( 
				'<select name="acadp_fields[%1$d]" id="acadp-form-control-%1$d" class="%2$s" %3$s>', 
				$field_id, 
				implode( ' ', $classes ),
				implode( ' ', $attributes )
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

			<div hidden id="acadp-form-error-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-error"></div>

			<?php if ( ! empty( $field_description ) ) : ?>
				<div class="acadp-form-description acadp-text-muted acadp-text-sm">
					<?php echo esc_html( $field_description ); ?>
				</div>
			<?php endif; ?>
		</div>
	<?php elseif ( 'checkbox' == $field_type ) : ?>
		<!-- Checkbox -->
		<fieldset id="acadp-form-group-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-group<?php if ( $field_required ) echo ' acadp-form-validate-checkboxes'; ?>"<?php if ( $field_required ) echo ' aria-required="true"'; ?>>
			<legend class="acadp-form-label">
				<?php echo esc_html( get_the_title() ); ?>
				<?php if ( $field_required ) echo '<span class="acadp-form-required" aria-hidden="true">*</span>'; ?>
				<span class="screen-reader-text acadp-form-legend-error"></span>
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
						'<label class="acadp-flex acadp-gap-1.5 acadp-items-center"><input type="hidden" name="acadp_fields[%1$d][%2$d]" value="" /><input type="checkbox" name="acadp_fields[%1$d][%2$d]" class="%3$s" value="%4$s"%5$s/>%6$s</label>', 
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

			<div hidden id="acadp-form-error-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-error" aria-hidden="true"></div>

			<?php if ( ! empty( $field_description ) ) : ?>
				<div class="acadp-form-description acadp-text-muted acadp-text-sm">
					<?php echo esc_html( $field_description ); ?>
				</div>
			<?php endif; ?>
		</fieldset>
	<?php elseif ( 'radio' == $field_type ) : ?>
		<!-- Radio -->
		<fieldset id="acadp-form-group-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-group">
			<legend class="acadp-form-label">
				<?php echo esc_html( get_the_title() ); ?>
				<?php if ( $field_required ) echo '<span class="acadp-form-required" aria-hidden="true">*</span>'; ?>
			</legend>

			<div class="acadp-form-radio-group">
				<?php
				$classes = array( 'acadp-form-control', 'acadp-form-radio' );	
				if ( $field_required ) $classes[] = 'acadp-form-validate';

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
					if ( $field_required ) $attributes[] = 'required';

					printf( 
						'<label class="acadp-flex acadp-gap-1.5 acadp-items-center"><input type="radio" name="acadp_fields[%d]" class="%s" value="%s"%s/>%s</label>', 
						$field_id, 
						implode( ' ', $classes ),
						esc_attr( $value ), 
						implode( ' ', $attributes ),
						esc_html( $label ) 
					);
				}
				?>
			</div>

			<div hidden id="acadp-form-error-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-error"></div>

			<?php if ( ! empty( $field_description ) ) : ?>
				<div class="acadp-form-description acadp-text-muted acadp-text-sm">
					<?php echo esc_html( $field_description ); ?>
				</div>
			<?php endif; ?>
		</fieldset>
	<?php elseif ( 'number' == $field_type ) : ?>
		<!-- Number -->
		<div id="acadp-form-group-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-group">
			<label for="acadp-form-control-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-label">
				<?php echo esc_html( get_the_title() ); ?>
				<?php if ( $field_required ) echo '<span class="acadp-form-required" aria-hidden="true">*</span>'; ?>
			</label>

			<?php
			$classes = array( 'acadp-form-control', 'acadp-form-input' );	
			if ( $field_required ) $classes[] = 'acadp-form-validate';

			$attributes = array();

			if ( isset( $field_meta['min'] ) && '' != $field_meta['min'][0] ) {
				$attributes[] = sprintf( 'min="%d"', (int) $field_meta['min'][0] );
			}

			if ( isset( $field_meta['max'] ) && '' != $field_meta['max'][0] ) {
				$attributes[] = sprintf( 'max="%d"', (int) $field_meta['max'][0] );
			}

			if ( isset( $field_meta['step'] ) && '' != $field_meta['step'][0] ) {
				$attributes[] = sprintf( 'step="%d"', (int) $field_meta['step'][0] );
			}

			if ( $field_required ) {
				$attributes[] = 'required';
				$attributes[] = sprintf( 'aria-describedby="acadp-form-error-%d"', $field_id );
			}

			printf( 
				'<input type="number" name="acadp_fields[%1$d]" id="acadp-form-control-%1$d" class="%2$s" placeholder="%3$s" value="%4$s" %5$s/>', 
				$field_id, 
				implode( ' ', $classes ),
				esc_attr( $field_placeholder ), 
				esc_attr( $field_value ), 
				implode( ' ', $attributes ) 
			);
			?>

			<div hidden id="acadp-form-error-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-error"></div>

			<?php if ( ! empty( $field_description ) ) : ?>
				<div class="acadp-form-description acadp-text-muted acadp-text-sm">
					<?php echo esc_html( $field_description ); ?>
				</div>
			<?php endif; ?>
		</div>
	<?php elseif ( 'range' == $field_type ) : ?>
		<!-- Range -->
		<div id="acadp-form-group-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-group">
			<label for="acadp-form-control-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-label">
				<?php echo esc_html( get_the_title() ); ?>
				<?php if ( $field_required ) echo '<span class="acadp-form-required" aria-hidden="true">*</span>'; ?>
			</label>

			<div class="acadp-form-control-range-slider">
				<div class="acadp-range-value"></div>
				<?php
				$classes = array( 'acadp-form-control', 'acadp-range-input', 'acadp-form-range' );	
				if ( $field_required ) $classes[] = 'acadp-form-validate';

				$attributes = array();

				if ( isset( $field_meta['min'] ) && '' != $field_meta['min'][0] ) {
					$attributes[] = sprintf( 'min="%d"', (int) $field_meta['min'][0] );
				} else {
					$attributes[] = 'min="0"';
				}

				if ( isset( $field_meta['max'] ) && '' != $field_meta['max'][0] ) {
					$attributes[] = sprintf( 'max="%d"', (int) $field_meta['max'][0] );
				} else {
					$attributes[] = 'max="100"';
				}

				if ( isset( $field_meta['step'] ) && '' != $field_meta['step'][0] ) {
					$attributes[] = sprintf( 'step="%d"', (int) $field_meta['step'][0] );
				}

				if ( $field_required ) {
					$attributes[] = 'required';
					$attributes[] = sprintf( 'aria-describedby="acadp-form-error-%d"', $field_id );
				}

				printf( 
					'<input type="range" name="acadp_fields[%1$d]" id="acadp-form-control-%1$d" class="%2$s" value="%3$s" %4$s/>', 
					$field_id, 
					implode( ' ', $classes ),
					esc_attr( $field_value ), 
					implode( ' ', $attributes ) 
				);
				?>
			</div>

			<div hidden id="acadp-form-error-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-error"></div>

			<?php if ( ! empty( $field_description ) ) : ?>
				<div class="acadp-form-description acadp-text-muted acadp-text-sm">
					<?php echo esc_html( $field_description ); ?>
				</div>
			<?php endif; ?>
		</div>
	<?php elseif ( 'date' == $field_type ) : ?>
		<!-- Date -->
		<div id="acadp-form-group-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-group">
			<label for="acadp-form-control-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-label">
				<?php echo esc_html( get_the_title() ); ?>
				<?php if ( $field_required ) echo '<span class="acadp-form-required" aria-hidden="true">*</span>'; ?>
			</label>

			<?php
			$classes = array( 'acadp-form-control', 'acadp-form-control-date-picker', 'acadp-form-input' );	
			if ( $field_required ) $classes[] = 'acadp-form-validate';

			$attributes = [];
			if ( $field_required ) {
				$attributes[] = 'required';
				$attributes[] = sprintf( 'aria-describedby="acadp-form-error-%d"', $field_id );
			}

			if ( $field_value == 1 ) {
				$field_value = current_time( 'Y-m-d' );
			} elseif ( $field_value == 0 ) {
				$field_value = '';
			}									
							
			printf( 
				'<input type="text" name="acadp_fields[%1$d]" id="acadp-form-control-%1$d" class="%2$s" placeholder="%3$s" value="%4$s" autocomplete="off" %5$s/>', 
				$field_id, 
				implode( ' ', $classes ),
				esc_attr( $field_placeholder ), 
				esc_attr( $field_value ), 
				implode( ' ', $attributes )
			);
			?>

			<div hidden id="acadp-form-error-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-error"></div>

			<?php if ( ! empty( $field_description ) ) : ?>
				<div class="acadp-form-description acadp-text-muted acadp-text-sm">
					<?php echo esc_html( $field_description ); ?>
				</div>
			<?php endif; ?>
		</div>
	<?php elseif ( 'datetime' == $field_type ) : ?>
		<!-- DateTime -->
		<div id="acadp-form-group-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-group">
			<label for="acadp-form-control-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-label">
				<?php echo esc_html( get_the_title() ); ?>
				<?php if ( $field_required ) echo '<span class="acadp-form-required" aria-hidden="true">*</span>'; ?>
			</label>

			<?php
			$classes = array( 'acadp-form-control', 'acadp-form-control-datetime-picker', 'acadp-form-input' );	
			if ( $field_required ) $classes[] = 'acadp-form-validate';

			$attributes = [];
			if ( $field_required ) {
				$attributes[] = 'required';
				$attributes[] = sprintf( 'aria-describedby="acadp-form-error-%d"', $field_id );
			}

			if ( $field_value == 1 ) {
				$field_value = current_time( 'mysql' );
			} elseif ( $field_value == 0 ) {
				$field_value = '';
			}
												
			printf( 
				'<input type="text" name="acadp_fields[%1$d]" id="acadp-form-control-%1$d" class="%2$s" placeholder="%3$s" value="%4$s" autocomplete="off" %5$s/>', 
				$field_id, 
				implode( ' ', $classes ),
				esc_attr( $field_placeholder ), 
				esc_attr( $field_value ), 
				implode( ' ', $attributes )
			);
			?>

			<div hidden id="acadp-form-error-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-error"></div>

			<?php if ( ! empty( $field_description ) ) : ?>
				<div class="acadp-form-description acadp-text-muted acadp-text-sm">
					<?php echo esc_html( $field_description ); ?>
				</div>
			<?php endif; ?>
		</div>
	<?php elseif ( 'url' == $field_type ) : ?>
		<!-- URL -->
		<div id="acadp-form-group-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-group">
			<label for="acadp-form-control-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-label">
				<?php echo esc_html( get_the_title() ); ?>
				<?php if ( $field_required ) echo '<span class="acadp-form-required" aria-hidden="true">*</span>'; ?>
			</label>

			<?php
			$classes = array( 'acadp-form-control', 'acadp-form-input' );	
			if ( $field_required ) $classes[] = 'acadp-form-validate';

			$attributes = [];
			if ( $field_required ) {
				$attributes[] = 'required';
				$attributes[] = sprintf( 'aria-describedby="acadp-form-error-%d"', $field_id );
			}

			printf( 
				'<input type="text" name="acadp_fields[%1$d]" id="acadp-form-control-%1$d" class="%2$s" placeholder="%3$s" value="%4$s" %5$s/>', 
				$field_id, 
				implode( ' ', $classes ),
				esc_attr( $field_placeholder ), 
				esc_url( $field_value ), 
				implode( ' ', $attributes )
			);
			?>

			<div hidden id="acadp-form-error-<?php echo esc_attr( $field_id ); ?>" class="acadp-form-error"></div>

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
