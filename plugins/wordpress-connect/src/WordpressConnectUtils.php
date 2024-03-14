<?php

require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php' );

/**
 * Wordpress Connect Utilities
 * @since 2.0
 */
class WordpressConnectUtils{

	/**
	 * Prints a select form element
	 *
	 *
	 * @param string $name		The form element name
	 * @param string $id		The html element id
	 * @param array $options	Associative array containig the select
	 * 							options. The array key is used as the option
	 * 							value and the value at the key will be
	 * 							displayed as option's text
	 *
	 * @param string $selected_value	The selected value - the value from
	 * 									$options array equal to this one
	 * 									will be set as selected
	 */
	public static function printSelectElement( $name, $id, $options, $selected_value ){
	?>
					<select name="<?php echo $name; ?>" id="<?php echo $id; ?>">
	<?php
						foreach( $options as $value => $text ) :
							$selected = ( $value == $selected_value ) ? ' selected="selected"' : '';
						?>
							<option value="<?php echo $value; ?>"<?php echo $selected; ?>><?php echo $text; ?></option>
						<?php endforeach;
	?>
					</select>
	<?php
	}

}
?>