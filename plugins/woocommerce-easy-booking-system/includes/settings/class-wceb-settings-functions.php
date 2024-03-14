<?php

namespace EasyBooking;

/**
*
* Settings functions.
* @version 3.0.0
*
**/

defined( 'ABSPATH' ) || exit;

class Settings {

	/**
	*
	* Outputs or returns a select input.
	* @param array - $args
	*
	**/
	public static function select( $args ) {

		$args['id']    = isset( $args['id'] ) ? $args['id'] : '';
		$args['name']  = isset( $args['name'] ) ? $args['name'] : '';
		$args['name']  = isset( $args['name'] ) ? $args['name'] : '';
		$args['value'] = isset( $args['value'] ) ? $args['value'] : '';
		$args['echo']  = isset( $args['echo'] ) ? $args['echo'] : true;

		$output = '';
		$output .= '<select name="' . esc_attr( $args['name'] ) . '" id="' . esc_attr( $args['id'] ) . '">';

			if ( isset( $args['options'] ) ) :

				foreach ( $args['options'] as $option => $value ) :
					$output .= '<option value="' . esc_attr( $option ) . '"' . selected( esc_attr( $args['value'] ), $option, false ) . '>' . esc_attr( $value ) . '</option>';
				endforeach;

			endif;

		$output .= '</select>';

		if ( isset( $args['description'] ) ) {
			$output .= '<p class="description">' . wp_kses_post( $args['description'] ) . '</p>';
		}

		if ( false === $args['echo'] ) {
			return $output;
		} else {
			echo $output;
		}

	}

	/**
	*
	* Outputs or returns a checkbox input.
	* @param array - $args
	*
	**/
	public static function checkbox( $args ) {

		$args['id']      = isset( $args['id'] ) ? $args['id'] : '';
		$args['class']   = isset( $args['class'] ) ? $args['class'] : '';
		$args['name']    = isset( $args['name'] ) ? $args['name'] : '';
		$args['value']   = isset( $args['value'] ) ? $args['value'] : '';
		$args['cbvalue'] = isset( $args['cbvalue'] ) ? $args['cbvalue'] : 'yes';
		$args['echo']    = isset( $args['echo'] ) ? $args['echo'] : true;

		$output = '';
		$output .= '<input type="checkbox" id="' . esc_attr( $args['id'] ) . '" name="' . esc_attr( $args['name'] ) . '"' .  checked( esc_attr( $args['value'] ), $args['cbvalue'], false ) . '/>';

		if ( isset( $args['description'] ) ) {
			$output .= '<p class="description">' . wp_kses_post( $args['description'] ) . '</p>';
		}

		if ( false === $args['echo'] ) {
			return $output;
		} else {
			echo $output;
		}

	}

	/**
	*
	* Outputs or returns a text input.
	* @param array - $args
	*
	**/
	public static function input( $args ) {

		$args['type']  = isset( $args['type'] ) ? $args['type'] : 'text';
		$args['id']    = isset( $args['id'] ) ? $args['id'] : '';
		$args['class'] = isset( $args['class'] ) ? $args['class'] : '';
		$args['name']  = isset( $args['name'] ) ? $args['name'] : '';
		$args['value'] = isset( $args['value'] ) ? $args['value'] : '';
		$args['echo']  = isset( $args['echo'] ) ? $args['echo'] : true;

		$output = '';

		// Custom attribute handling
		$custom_attributes = array();
		if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {

			foreach ( $args['custom_attributes'] as $attribute => $value ){
				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
			}
		}

		$output .= '<input type="' . esc_attr( $args['type'] ) . '" name="' . esc_attr( $args['name'] ) . '" value="' . esc_attr( $args['value'] ) . '" class="' . $args['class'] . '"' . implode( ' ', $custom_attributes ) . '>';

		if ( isset( $args['description'] ) ) {
			$output .= '<p class="description">' . wp_kses_post( $args['description'] ) . '</p>';
		}

		if ( false === $args['echo'] ) {
			return $output;
		} else {
			echo $output;
		}

	}

	/**
	*
	* Outputs or returns a texterea input.
	* @param array - $args
	*
	**/
	public static function textarea( $args ) {

		$args['id']    = isset( $args['id'] ) ? $args['id'] : '';
		$args['class'] = isset( $args['class'] ) ? $args['class'] : '';
		$args['name']  = isset( $args['name'] ) ? $args['name'] : '';
		$args['value'] = isset( $args['value'] ) ? $args['value'] : '';
		$args['echo']  = isset( $args['echo'] ) ? $args['echo'] : true;

		$output = '';

		// Custom attribute handling
		$custom_attributes = array();
		if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {

			foreach ( $args['custom_attributes'] as $attribute => $value ){
				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
			}
		}

		$output .= '<textarea id="' . esc_attr( $args['id'] ) . '" name="' . esc_attr( $args['name'] ) . '"' . implode( ' ', $custom_attributes ) . '/>' . esc_textarea( $args['value'] ) . '</textarea>';

		if ( isset( $args['description'] ) ) {
			$output .= '<p class="description">' . wp_kses_post( $args['description'] ) . '</p>';
		}

		if ( false === $args['echo'] ) {
			return $output;
		} else {
			echo $output;
		}

	}

	/**
	*
	* Outputs a (maybe sortable) table with the possibility to add or delete rows.
	* @param str - $content - the content name
	* @param array - $columns
	* @param array - $args
	*
	**/
	public static function table( $content, $columns, $args ) {
		$args = array(
			'table_classes' => isset( $args['table_classes'] ) ? $args['table_classes'] : '',
			'body_classes'  => isset( $args['body_classes'] ) ? $args['body_classes'] : '',
			'row_classes'   => isset( $args['row_classes'] ) ? $args['row_classes'] : '',
			'content'       => isset( $args['content'] ) ? $args['content'] : 'row', 
			'sortable'      => isset( $args['sortable'] ) ? $args['sortable'] : false,
			'delete'        => isset( $args['delete'] ) ? $args['delete'] : true
		);

		$column_count = count( $columns );
		
		if ( true === $args['sortable'] ) {
			$column_count += 1;
		}

		if ( true === $args['delete'] ) {
			$column_count += 1;
		}

		?>

		<table class="<?php echo esc_attr( $args['table_classes'] ); ?>">

			<thead>

	            <tr>

	            	<?php if ( true === $args['sortable'] ) :
	                	echo '<th>&nbsp;</th>';
	            	endif;

	            	if ( $columns ) :

	            		foreach ( $columns as $column ) :

	            			echo '<th>';
	            				echo wp_kses( $column['title'], array( 'span' => 'class' ) );
	            				if ( isset( $column['tip'] ) ) :
	            					echo '<span class="tips" data-tip="' . esc_attr( $column['tip'] ) . '">[?]</span>';
	            				endif;
	            			echo '</th>';

	                	endforeach;

	            	endif;

	            	if ( true === $args['delete'] ) :
	                	echo '<th>&nbsp;</th>';
	            	endif; ?>
	                
	            </tr>

		    </thead>

		    <tbody class="<?php echo esc_attr( $args['body_classes'] ); ?>">
	            <?php if ( $content ) foreach ( $content as $item ) :
	            	echo self::table_columns( $columns, $args, $item );
	            endforeach; ?>
	        </tbody>

	        <tfoot>
	            <tr>
	                <th colspan="<?php echo absint( $column_count ); ?>">
	                    <a href="#" class="button add-row add-<?php echo esc_attr( $args['content'] ); ?>" data-row="<?php
	                        ob_start();
	                        echo self::table_columns( $columns, $args );
	                        echo esc_attr( ob_get_clean() );
	                    ?>"><?php esc_html_e( 'Add', 'woocommerce-easy-booking-system' ); ?></a>
	                </th>
	            </tr>
	        </tfoot>

		</table>

		<?php

	}

	/**
	*
	* Generate columns for the table.
	* @param array - $columns
	* @param array - $args
	* @param array - $item - Existing item
	*
	**/
	public static function table_columns( $columns, $args, $item = array() ) {
		$output = '<tr class="' . esc_attr( $args['row_classes'] ) . '">';

		if ( true === $args['sortable'] ) :
			$output .= '<td class="sort"></td>';
		endif;

		foreach ( $columns as $column ) :

	        $output .= '<td class="' . esc_attr( $args['content'] ) . '_' . esc_attr( $column['name'] ) . '">';

	        	if ( ! empty( $item ) ) {
	        		$column['content']['data']['value'] = $item[$column['name']];
	        	}

	        	$func = $column['content']['function'];

	        	// Backward compatibility
	        	$func = str_replace( 'wceb_settings_', '', $func );
	        	
	        	$output .= self::$func( $column['content']['data'] );

			$output .= '</td>';

		endforeach;

		if ( true === $args['delete'] ) :
			$output .= '<td width="1%"><a href="#" class="delete delete-' . esc_attr( $args['content'] ) . '">' . __( 'Delete', 'woocommerce' ) . '</a></td>';
		endif;

		$output .= '</tr>';

		return $output;

	}

	/**
	*
	* Sanitize checkbox option.
	* @param str - $value
	* @return str - 'yes' or 'no'
	*
	**/
	public static function sanitize_checkbox( $value ) {
		return ( ! empty( $value ) && $value !== 'no' ) ? 'yes' : 'no';
	}

	/**
	*
	* Sanitize duraiton field option.
	* @param str - $value
	* @param int - $min
	* @param int - $max
	* @return int - $value
	*
	**/
	public static function sanitize_duration_field( $value, $min = 0, $max = 3650 ) {
		
		if ( $value < $min ) {
			$value = $min;
		}

		if ( $value > $max ) {
			$value = $max;
		}

		return absint( $value );
	}

}