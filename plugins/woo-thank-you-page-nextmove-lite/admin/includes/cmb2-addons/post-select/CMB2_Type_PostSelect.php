<?php
defined( 'ABSPATH' ) || exit;

/**
 * CMB select field type
 *
 * @since  2.2.2
 *
 * @category  WordPress_Plugin
 * @package   CMB2
 * @author    WebDevStudios
 * @license   GPL-2.0+
 * @link      http://webdevstudios.com
 */
class CMB2_Type_XLWCTY_PostSelect extends CMB2_Type_Multi_Base {

	public function render() {
		$a = $this->parse_args( 'xlwcty_post_select', array(
			'class'   => 'cmb2_select',
			'name'    => $this->_name(),
			'id'      => $this->_id(),
			'desc'    => $this->_desc( true ),
			'options' => $this->concat_items(),
		) );

		$attrs = $this->concat_attrs( $a, array( 'desc', 'options' ) );

		return $this->rendered( sprintf( '<select%s>%s</select>%s', $attrs, $a['options'], $a['desc'] ) );
	}

	/**
	 * Generates html for concatenated items
	 * @since  1.1.0
	 *
	 * @param  array $args Optional arguments
	 *
	 * @return string        Concatenated html items
	 */
	public function concat_items( $args = array() ) {

		$field = $this->field;

		$method = isset( $args['method'] ) ? $args['method'] : 'select_option';
		unset( $args['method'] );

		$value = $field->escaped_value() ? $field->escaped_value() : $field->get_default();

		$concatenated_items = '';
		$i                  = 1;


		$options = array();
		if ( $option_none = $field->args( 'show_option_none' ) ) {
			$options[''] = $option_none;
		}
		$pre_options = $field->options();
		if ( $value && is_array( $value ) ) {

			foreach ( $value as $each ) {

				if ( ! array_key_exists( $each, $pre_options ) ) {
					$post_name = ( isset( $field->args['options_name_cb'] ) ) ? call_user_func( $field->args['options_name_cb'], $each ) : '';
					if ( false !== $post_name ) {
						$options[ $each ] = $post_name;
					}
				} else {
					$options[ $each ] = $pre_options[ $each ];
				}
			}
		}


		$options = $options + (array) $field->options();
		foreach ( $options as $opt_value => $opt_label ) {

			// Clone args & modify for just this item
			$a = $args;

			$a['value'] = $opt_value;
			$a['label'] = $opt_label;

			// Check if this option is the value of the input
			if ( in_array( $opt_value, $value ) ) {
				$a['checked'] = 'checked';
			}

			$concatenated_items .= $this->$method( $a, $i ++ );
		}

		return $concatenated_items;
	}

}
