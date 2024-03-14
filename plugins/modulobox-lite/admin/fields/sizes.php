<?php
/**
 * @package   ModuloBox
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2017 Themeone
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ModuloBox_Sizes_Field' ) ) {

	class ModuloBox_Sizes_Field extends ModuloBox_Settings_field {

		/**
		 * Render HTML field
		 *
		 * @since 1.0.0
		 * @access static
		 *
		 * @param array $args Contains all field parameters
		 */
		static function render( $args ) {

			$args['value'] = (array) $args['value'];

			echo $args['desc'];
			echo $args['premium'];

			echo '<table class="mobx-table-sizes">';

				echo '<tbody>';

					echo '<tr>';

						echo '<td></td>';
						echo '<td>' . esc_html__( 'Browser', 'modulobox' ) . '</td>';
						echo '<td>' . esc_html__( 'Width', 'modulobox' ) . '</td>';
						echo '<td>' . esc_html__( 'Height', 'modulobox' ) . '</td>';
						echo '<td>' . esc_html__( 'Gutter', 'modulobox' ) . '</td>';
						echo '<td>(px)</td>';

					echo '</tr>';

					if ( isset( $args['value']['browser'] ) ) {

						$sizes = array();
						$value = $args['value'];

						foreach ( $value['browser'] as $index => $val ) {

							if ( isset( $value['width'] )  && isset( $value['width'][ $index ] )  &&
								 isset( $value['height'] ) && isset( $value['height'][ $index ] ) &&
								 isset( $value['gutter'] ) && isset( $value['gutter'][ $index ] )
							   ) {

								$browser_width = $value['browser'][ $index ];

								$sizes[ $browser_width ] = array(
									'args'    => $args,
									'count'   => $index,
									'browser' => $browser_width,
									'width'   => $value['width'][ $index ],
									'height'  => $value['height'][ $index ],
									'gutter'  => $value['gutter'][ $index ],
								);

							}
						}

						$count = 0;
						krsort( $sizes );

						foreach ( $sizes as $size ) {

							self::render_table_row(
								$size['args'],
								$count++,
								$size['browser'],
								$size['width'],
								$size['height'],
								$size['gutter']
							);

						}
					} else {

						self::render_table_row(
							$args,
							0,
							$args['default']['browser'][0],
							$args['default']['width'][0],
							$args['default']['height'][0],
							$args['default']['gutter'][0]
						);

					}

					echo '<tr>';

						echo '<td></td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td class="mobx-add-size"><span></span></td>';

					echo '</tr>';

				echo '</tbody>';

			echo '</table>';

		}

		/**
		 * Render table row
		 *
		 * @since 1.0.0
		 * @access static
		 *
		 * @param array $args
		 * @param number $count
		 * @param number $browser
		 * @param number $width
		 * @param number $height
		 * @param number $gutter
		 */
		static function render_table_row( $args, $count, $browser, $width, $height, $gutter ) {

			echo '<tr>';

				echo '<td><label>' . esc_html__( 'Size', 'modulobox' ) . ' ' . esc_html( $count + 1 ) . '</label></td>';
				echo '<td><input type="number" class="mobx-number" name="' . esc_attr( $args['name'] )  . '[browser][]" value="' . esc_attr( $browser ) . '" min="1" max="3840" step="1" autocomplete="off"></td>';
				echo '<td><input type="number" class="mobx-number" name="' . esc_attr( $args['name'] )  . '[width][]" value="' . esc_attr( $width ) . '" min="0" max="500" step="1" autocomplete="off"></td>';
				echo '<td><input type="number" class="mobx-number" name="' . esc_attr( $args['name'] )  . '[height][]" value="' . esc_attr( $height ) . '" min="0" max="500" step="1" autocomplete="off"></td>';
				echo '<td><input type="number" class="mobx-number" name="' . esc_attr( $args['name'] )  . '[gutter][]" value="' . esc_attr( $gutter ) . '" min="0" max="500" step="1" autocomplete="off"></td>';
				echo '<td class="mobx-delete-size"><span></span></td>';

			echo '</tr>';

		}

		/**
		 * Normalize field parameters
		 *
		 * @since 1.0.0
		 * @access static
		 *
		 * @param array $field
		 * @return array
		 */
		static function normalize( $field ) {

			return wp_parse_args( $field, array(
				'default' => array(
					'browser' => array( 1920 ),
					'width'   => array( 80 ),
					'height'  => array( 60 ),
					'gutter'  => array( 20 ),
				),
			));

		}

		/**
		 * Sanitize field value
		 *
		 * @since 1.0.0
		 * @access static
		 *
		 * @param mixed $val
		 * @param array $args
		 * @return string
		 */
		static function sanitize( $val, $args ) {

			$new_val = array();

			foreach ( (array) $val as $key => $sizes ) {

				if ( array_key_exists( $key, $args['default'] ) ) {

					foreach ( (array) $sizes as $width ) {

						if ( 'browser' === $key ) {
							$width_args = array( 'min' => 1, 'max' => 3840, 'step' => 1 );
						} else {
							$width_args = array( 'min' => 0, 'max' => 500, 'step' => 1 );
						}

						$new_val[ sanitize_key( $key ) ][] = ModuloBox_Number_Field::sanitize( $width, $width_args );

					}
				}
			}

			return $new_val;

		}
	}

}
