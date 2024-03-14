<?php

if ( ! function_exists( 'tj_print_select_option' ) ) :
	function tj_print_select_option( $options ) {
		$options['id']          = isset( $options['id'] ) ? $options['id'] : '';
		$options['description'] = isset( $options['description'] ) ? $options['description'] : '';
		$options['premium']     = isset( $options['premium'] ) ? $options['premium'] : false;

		// premium feature.
		if ( ! function_exists( 'is_ecae_premium_exist' ) && $options['premium'] ) {
			$disabled_opt = ' disabled';
		} else {
			$disabled_opt = '';
		}
		?>
		<tr valign="top" id="<?php echo esc_attr( $options['id'] ); ?>">
			<th scope="row"><?php echo wp_kses( $options['label'], array( 'span' => array() ) ); ?></th>
			<td>
				<select<?php echo esc_attr( $disabled_opt ); ?> name="<?php echo esc_attr( $options['name'] ); ?>">
					<?php foreach ( $options['select_array'] as $select ) : ?>
						<option value="<?php echo esc_attr( $select['value'] ); ?>" <?php selected( $options['value'], $select['value'] ); ?>><?php echo esc_html( $select['label'] ); ?></option>
					<?php endforeach; ?>
				</select>
			</td>
			<td>
				<?php echo wp_kses_post( $options['description'] ); ?>
			</td>
		</tr>
		<?php
	}

endif;

if ( ! function_exists( 'tj_print_text_option' ) ) :
	function tj_print_text_option( $options ) {

		$options['id']          = isset( $options['id'] ) ? $options['id'] : '';
		$options['description'] = isset( $options['description'] ) ? $options['description'] : '';
		?>
		<tr valign="top" id="<?php echo esc_attr( $options['id'] ); ?>">
			<th scope="row"><?php echo esc_html( $options['label'] ); ?></th>
			<td>
				<input type="text" name="<?php echo esc_attr( $options['name'] ); ?>" value="<?php echo esc_attr( $options['value'] ); ?>">
			</td>
			<td>
				<?php echo wp_kses_post( $options['description'] ); ?>
			</td>
		</tr>
		<?php
	}

endif;

if ( ! function_exists( 'tj_print_text_area_option' ) ) :
	function tj_print_text_area_option( $options ) {

		if ( ! $options['row'] ) {
			$options['row'] = 4;
		}
		if ( ! $options['column'] ) {
			$options['column'] = 50;
		}
		?>
		<tr valign="top" id="{$options['id']}" >
			<th scope="row"><?php echo esc_html( $options['label'] ); ?></th>
			<td>
				<textarea  name="<?php echo esc_attr( $options['name'] ); ?>" rows="<?php echo esc_attr( $options['row'] ); ?>" cols="<?php echo esc_attr( $options['column'] ); ?>"><?php echo esc_textarea( $options['value'] ); ?></textarea>
			</td>
		</tr>
		<?php
	}

endif;
