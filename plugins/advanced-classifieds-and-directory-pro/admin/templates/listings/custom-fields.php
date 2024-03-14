<?php

/**
 * Custom Fields.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */

if ( empty( $posts ) ) {
	return false;
}

$cached_meta = array();
if ( isset( $_POST['cached_meta'] ) ) {
	parse_str( $_POST['cached_meta'], $cached_meta );
} 
?>

<table class="acadp-form-table form-table widefat">
	<tbody>
		<?php foreach ( $posts as $post ) : 
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
				<tr class="acadp-form-group-<?php echo esc_attr( $field_id ); ?>">
					<th scope="row">
						<label for="acadp-form-control-<?php echo esc_attr( $field_id ); ?>">
							<?php echo esc_html( $post->post_title ); ?>
							<?php if ( $field_required ) echo '<span class="acadp-form-required">*</span>'; ?>
						</label>

						<?php if ( ! empty( $field_description ) ) : ?>
							<p class="description">
								<?php echo esc_textarea( $field_description ); ?>
							</p>
						<?php endif; ?>
					</th>

					<td>
						<?php
						printf( 
							'<input type="text" name="acadp_fields[%1$d]" id="acadp-form-control-%1$d" class="acadp-form-control acadp-form-input widefat" placeholder="%2$s" value="%3$s" />', 
							$field_id, 
							esc_attr( $field_placeholder ), 
							esc_attr( $field_value ) 
						);
						?>
					</td>
				</tr>
			<?php elseif ( 'textarea' == $field_type ) : ?>
				<!-- Textarea -->
				<tr class="acadp-form-group-<?php echo esc_attr( $field_id ); ?>">
					<th scope="row">
						<label for="acadp-form-control-<?php echo esc_attr( $field_id ); ?>">
							<?php echo esc_html( $post->post_title ); ?>
							<?php if ( $field_required ) echo '<span class="acadp-form-required">*</span>'; ?>
						</label>

						<?php if ( ! empty( $field_description ) ) : ?>
							<p class="description">
								<?php echo esc_textarea( $field_description ); ?>
							</p>
						<?php endif; ?>
					</th>

					<td>
						<?php
						printf( 
							'<textarea name="acadp_fields[%1$d]" id="acadp-form-control-%1$d" class="acadp-form-control acadp-form-textarea widefat" rows="%2$d" placeholder="%3$s">%4$s</textarea>', 
							$field_id, 
							(int) $field_meta['rows'][0], 
							esc_attr( $field_placeholder ), 
							esc_textarea( $field_value ) 
						);
						?>
					</td>
				</tr>
			<?php elseif ( 'select' == $field_type ) : ?>
				<!-- Select -->
				<tr class="acadp-form-group-<?php echo esc_attr( $field_id ); ?>">
					<th scope="row">
						<label for="acadp-form-control-<?php echo esc_attr( $field_id ); ?>">
							<?php echo esc_html( $post->post_title ); ?>
							<?php if ( $field_required ) echo '<span class="acadp-form-required">*</span>'; ?>
						</label>

						<?php if ( ! empty( $field_description ) ) : ?>
							<p class="description">
								<?php echo esc_textarea( $field_description ); ?>
							</p>
						<?php endif; ?>
					</th>

					<td>
						<?php
						$choices = $field_meta['choices'][0];
						$choices = explode( "\n", trim( $choices ) );
			
						printf( 
							'<select name="acadp_fields[%1$d]" id="acadp-form-control-%1$d" class="acadp-form-control acadp-form-select widefat">', 
							$field_id
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
						
								$value = $parts[0];
								$label = $parts[1];
							} else {
								$value = trim( $choice );
								$label = $value;
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
					</td>
				</tr>
			<?php elseif ( 'checkbox' == $field_type ) : ?>
				<!-- Checkbox -->
				<tr class="acadp-form-group-<?php echo esc_attr( $field_id ); ?>">
					<th scope="row">
						<label for="acadp-form-control-<?php echo esc_attr( $field_id ); ?>">
							<?php echo esc_html( $post->post_title ); ?>
							<?php if ( $field_required ) echo '<span class="acadp-form-required">*</span>'; ?>
						</label>

						<?php if ( ! empty( $field_description ) ) : ?>
							<p class="description">
								<?php echo esc_textarea( $field_description ); ?>
							</p>
						<?php endif; ?>
					</th>

					<td>
						<?php
						$choices = $field_meta['choices'][0];
						$choices = explode( "\n", trim( $choices ) );
			
						$field_value = is_array( $field_value ) ? $field_value : explode( "\n", $field_value );
						$field_value = array_map( 'trim', $field_value );	
			
						printf( 
							'<ul id="acadp-form-control-%1$d" class="acadp-form-checkbox-group acadp-flex acadp-flex-col acadp-gap-1 acadp-m-0 acadp-border acadp-border-solid acadp-border-gray-400 acadp-rounded acadp-p-2 acadp-list-none">', 
							$field_id 
						);

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
					
							echo '<li class="acadp-m-0 acadp-p-0 acadp-list-none">';
							printf( 
								'<label class="acadp-flex acadp-gap-1.5 acadp-items-center"><input type="hidden" name="acadp_fields[%1$d][%2$d]" value="" /><input type="checkbox" name="acadp_fields[%1$d][%2$d]" class="acadp-form-control acadp-form-checkbox" value="%3$s"%4$s>%5$s</label>', 
								$field_id, 
								$index, 
								esc_attr( $value ), 
								( in_array( $value, $field_value ) ? ' checked="checked"' : '' ), 
								esc_html( $label ) 
							);
							echo '</li>';
						}
						echo '</ul>';
						?>
					</td>
				</tr>
			<?php elseif ( 'radio' == $field_type ) : ?>
				<!-- Radio -->
				<tr class="acadp-form-group-<?php echo esc_attr( $field_id ); ?>">
					<th scope="row">
						<label for="acadp-form-control-<?php echo esc_attr( $field_id ); ?>">
							<?php echo esc_html( $post->post_title ); ?>
							<?php if ( $field_required ) echo '<span class="acadp-form-required">*</span>'; ?>
						</label>

						<?php if ( ! empty( $field_description ) ) : ?>
							<p class="description">
								<?php echo esc_textarea( $field_description ); ?>
							</p>
						<?php endif; ?>
					</th>

					<td>
						<?php
						$choices = $field_meta['choices'][0];
						$choices = explode( "\n", trim( $choices ) );
			
						printf( 
							'<ul id="acadp-form-control-%1$d" class="acadp-form-radio-group acadp-flex acadp-flex-col acadp-gap-1 acadp-m-0 acadp-border acadp-border-solid acadp-border-gray-400 acadp-rounded acadp-p-2 acadp-list-none">', 
							$field_id
						);

						foreach ( $choices as $choice ) {
							if ( strpos( $choice, ':' ) !== false ) {
								$parts = explode( ':', $choice );
								$parts = array_map( 'trim', $parts );
						
								$value = $parts[0];
								$label = $parts[1];
							} else {
								$value = trim( $choice );
								$label = $value;
							}
					
							echo '<li class="acadp-m-0 acadp-p-0 acadp-list-none">';
							printf( 
								'<label class="acadp-flex acadp-gap-1.5 acadp-items-center"><input type="radio" name="acadp_fields[%d]" class="acadp-form-control acadp-form-radio" value="%s"%s />%s</label>', 
								$field_id, 
								esc_attr( $value ), 
								( $value == trim( $field_value ) ? ' checked="checked"' : '' ),
								esc_html( $label ) 
							);
							echo '</li>';
						}
						echo '</ul>';
						?>
					</td>
				</tr>
			<?php elseif ( 'number' == $field_type ) : ?>
				<!-- Number -->
				<tr class="acadp-form-group-<?php echo esc_attr( $field_id ); ?>">
					<th scope="row">
						<label for="acadp-form-control-<?php echo esc_attr( $field_id ); ?>">
							<?php echo esc_html( $post->post_title ); ?>
							<?php if ( $field_required ) echo '<span class="acadp-form-required">*</span>'; ?>
						</label>

						<?php if ( ! empty( $field_description ) ) : ?>
							<p class="description">
								<?php echo esc_textarea( $field_description ); ?>
							</p>
						<?php endif; ?>
					</th>

					<td>
						<?php
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

						printf( 
							'<input type="number" name="acadp_fields[%1$d]" id="acadp-form-control-%1$d" class="acadp-form-control acadp-form-input widefat" placeholder="%2$s" value="%3$s" %4$s/>', 
							$field_id, 
							esc_attr( $field_placeholder ), 
							esc_attr( $field_value ), 
							implode( ' ', $attributes ) 
						);
						?>
					</td>
				</tr>
			<?php elseif ( 'range' == $field_type ) : ?>
				<!-- Range -->
				<tr class="acadp-form-group-<?php echo esc_attr( $field_id ); ?>">
					<th scope="row">
						<label for="acadp-form-control-<?php echo esc_attr( $field_id ); ?>">
							<?php echo esc_html( $post->post_title ); ?>
							<?php if ( $field_required ) echo '<span class="acadp-form-required">*</span>'; ?>
						</label>

						<?php if ( ! empty( $field_description ) ) : ?>
							<p class="description">
								<?php echo esc_textarea( $field_description ); ?>
							</p>
						<?php endif; ?>
					</th>

					<td>
						<?php
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
						?>
						<div class="acadp-form-control-range-slider">
							<div class="acadp-range-value"></div>
							<?php
							printf( 
								'<input type="range" name="acadp_fields[%1$d]" id="acadp-form-control-%1$d" class="acadp-form-control acadp-form-input acadp-range-input widefat" value="%2$s" %3$s/>', 
								$field_id, 
								esc_attr( $field_value ), 
								implode( ' ', $attributes ) 
							);
							?>
						</div>
					</td>
				</tr>
			<?php elseif ( 'date' == $field_type ) : ?>
				<!-- Date -->
				<tr class="acadp-form-group-<?php echo esc_attr( $field_id ); ?>">
					<th scope="row">
						<label for="acadp-form-control-<?php echo esc_attr( $field_id ); ?>">
							<?php echo esc_html( $post->post_title ); ?>
							<?php if ( $field_required ) echo '<span class="acadp-form-required">*</span>'; ?>
						</label>

						<?php if ( ! empty( $field_description ) ) : ?>
							<p class="description">
								<?php echo esc_textarea( $field_description ); ?>
							</p>
						<?php endif; ?>
					</th>

					<td>
						<?php
						if ( $field_value == 1 ) {
							$field_value = current_time( 'Y-m-d' );
						} elseif ( $field_value == 0 ) {
							$field_value = '';
						}	

						printf( 
							'<input type="text" name="acadp_fields[%1$d]" id="acadp-form-control-%1$d" class="acadp-form-control acadp-form-control-date-picker acadp-form-input widefat" placeholder="%2$s" value="%3$s" autocomplete="off" />', 
							$field_id, 
							esc_attr( $field_placeholder ), 
							esc_attr( $field_value ) 
						);
						?>
					</td>
				</tr>
			<?php elseif ( 'datetime' == $field_type ) : ?>
				<!-- Datetime -->
				<tr class="acadp-form-group-<?php echo esc_attr( $field_id ); ?>">
					<th scope="row">
						<label for="acadp-form-control-<?php echo esc_attr( $field_id ); ?>">
							<?php echo esc_html( $post->post_title ); ?>
							<?php if ( $field_required ) echo '<span class="acadp-form-required">*</span>'; ?>
						</label>

						<?php if ( ! empty( $field_description ) ) : ?>
							<p class="description">
								<?php echo esc_textarea( $field_description ); ?>
							</p>
						<?php endif; ?>
					</th>

					<td>
						<?php
						if ( $field_value == 1 ) {
							$field_value = current_time( 'mysql' );
						} elseif ( $field_value == 0 ) {
							$field_value = '';
						}

						printf( 
							'<input type="text" name="acadp_fields[%1$d]" id="acadp-form-control-%1$d" class="acadp-form-control acadp-form-control-datetime-picker acadp-form-input widefat" placeholder="%2$s" value="%3$s" autocomplete="off" />', 
							$field_id, 
							esc_attr( $field_placeholder ), 
							esc_attr( $field_value ) 
						);
						?>
					</td>
				</tr>
			<?php elseif ( 'url' == $field_type ) : ?>
				<!-- URL -->
				<tr class="acadp-form-group-<?php echo esc_attr( $field_id ); ?>">
					<th scope="row">
						<label for="acadp-form-control-<?php echo esc_attr( $field_id ); ?>">
							<?php echo esc_html( $post->post_title ); ?>
							<?php if ( $field_required ) echo '<span class="acadp-form-required">*</span>'; ?>
						</label>

						<?php if ( ! empty( $field_description ) ) : ?>
							<p class="description">
								<?php echo esc_textarea( $field_description ); ?>
							</p>
						<?php endif; ?>
					</th>

					<td>
						<?php
						printf( 
							'<input type="url" name="acadp_fields[%1$d]" id="acadp-form-control-%1$d" class="acadp-form-control acadp-form-input widefat" placeholder="%2$s" value="%3$s" />', 
							$field_id, 
							esc_attr( $field_placeholder ), 
							esc_url( $field_value ) 
						);
						?>
					</td>
				</tr>
			<?php else : ?>
				<!-- Hook for developers to add custom field types -->
				<?php do_action( 'acadp_admin_custom_field', $post_id, $field_id ); ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</tbody>
</table>