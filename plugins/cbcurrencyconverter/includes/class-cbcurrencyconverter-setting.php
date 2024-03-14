<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * weDevs Settings API wrapper class(Modified by codeboxr.com team)
 *
 * @version 1.1
 *
 * @author  Tareq Hasan <tareq@weDevs.com>
 * @link    http://tareq.weDevs.com Tareq's Planet
 * @example src/settings-api.php How to use the class
 *
 */
if ( ! class_exists( 'CBCurrencyconverterSetting' ) ):

	class CBCurrencyconverterSetting {
		/**
		 * settings sections array
		 *
		 * @var array
		 */
		private $settings_sections = [];

		/**
		 * Settings fields array
		 *
		 * @var array
		 */
		private $settings_fields = [];

		/**
		 * Singleton instance
		 *
		 * @var object
		 */
		private static $_instance;

		public function __construct() {

		}


		/**
		 * Set settings sections
		 *
		 * @param  array  $sections  setting sections array
		 */
		function set_sections( $sections ) {
			$this->settings_sections = $sections;

			return $this;
		}//end method set_sections

		/**
		 * Add a single section
		 *
		 * @param  array  $section
		 */
		function add_section( $section ) {
			$this->settings_sections[] = $section;

			return $this;
		}//end method set_sections

		/**
		 * Set settings fields
		 *
		 * @param  array  $fields  settings fields array
		 */
		function set_fields( $fields ) {
			$this->settings_fields = $fields;

			return $this;
		}//end method set_sections

		function add_field( $section, $field ) {
			$defaults = [
				'name'  => '',
				'label' => '',
				'desc'  => '',
				'type'  => 'text'
			];

			$arg                                 = wp_parse_args( $field, $defaults );
			$this->settings_fields[ $section ][] = $arg;

			return $this;
		}//end method set_sections

		/**
		 * Initialize and registers the settings sections and fileds to WordPress
		 *
		 * Usually this should be called at `admin_init` hook.
		 *
		 * This function gets the initiated settings sections and fields. Then
		 * registers them to WordPress and ready for use.
		 */

		function admin_init() {
			//register settings sections
			foreach ( $this->settings_sections as $section ) {
				if ( false == get_option( $section['id'] ) ) {
					$section_default_value = $this->getDefaultValueBySection( $section['id'] );
					add_option( $section['id'], $section_default_value );
				} else {
					$section_default_value = $this->getMissingDefaultValueBySection( $section['id'] );
					update_option( $section['id'], $section_default_value );
				}

				if ( isset( $section['desc'] ) && ! empty( $section['desc'] ) ) {
					$section['desc'] = '<div class="inside">' . $section['desc'] . '</div>';
					$callback        = function () use ( $section ) {
						echo str_replace( '"', '\"', $section['desc'] );
					};
				} elseif ( isset( $section['callback'] ) ) {
					$callback = $section['callback'];
				} else {
					$callback = null;
				}

				add_settings_section( $section['id'], $section['title'], $callback, $section['id'] );
			}

			//register settings fields
			foreach ( $this->settings_fields as $section => $field ) {
				foreach ( $field as $option ) {

					$name     = $option['name'];
					$type     = isset( $option['type'] ) ? $option['type'] : 'text';
					$label    = isset( $option['label'] ) ? $option['label'] : '';
					$callback = isset( $option['callback'] ) ? $option['callback'] : [
						$this,
						'callback_' . $type
					];

					$label_for = $this->settings_clean_label_for( "{$section}_{$option['name']}" );

					$args = [
						'id'                => $option['name'],
						'class'             => isset( $option['class'] ) ? $option['class'] : $name,
						'label_for'         => $label_for,
						'desc'              => isset( $option['desc'] ) ? $option['desc'] : '',
						'name'              => $label,
						'section'           => $section,
						'size'              => isset( $option['size'] ) ? $option['size'] : null,
						'min'               => isset( $option['min'] ) ? $option['min'] : '',
						'max'               => isset( $option['max'] ) ? $option['max'] : '',
						'step'              => isset( $option['step'] ) ? $option['step'] : '',
						'options'           => isset( $option['options'] ) ? $option['options'] : '',
						'default'           => isset( $option['default'] ) ? $option['default'] : '',
						'sanitize_callback' => isset( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : '',
						'placeholder'       => isset( $option['placeholder'] ) ? $option['placeholder'] : '',
						'multi'             => isset( $option['multi'] ) ? intval( $option['multi'] ) : 0,
						'type'              => $type,
						'optgroup'          => isset( $option['optgroup'] ) ? intval( $option['optgroup'] ) : 0,
						'sortable'          => isset( $option['sortable'] ) ? intval( $option['sortable'] ) : 0,
						'allow_new'         => isset( $option['allow_new'] ) ? intval( $option['allow_new'] ) : 0,
						'inline'            => isset( $option['inline'] ) ? absint( $option['inline'] ) : 1,
					];

					add_settings_field( "{$section}[{$name}]", $label, $callback, $section, $section, $args );
				}
			}

			// creates our settings in the options table
			foreach ( $this->settings_sections as $section ) {
				register_setting( $section['id'], $section['id'], [ $this, 'sanitize_options' ] );
			}
		}//end method admin_init

		/**
		 * Prepares default values by section
		 *
		 * @param $section_id
		 *
		 * @return array
		 */
		function getDefaultValueBySection( $section_id ) {
			$default_values = [];

			$fields = $this->settings_fields[ $section_id ];
			foreach ( $fields as $field ) {
				$default_values[ $field['name'] ] = isset( $field['default'] ) ? $field['default'] : '';
			}

			return $default_values;
		}//end method getDefaultValueBySection

		/**
		 * Prepares default values by section
		 *
		 * @param $section_id
		 *
		 * @return array
		 */
		function getMissingDefaultValueBySection( $section_id ) {
			$section_value = get_option( $section_id );

			$fields = $this->settings_fields[ $section_id ];
			foreach ( $fields as $field ) {
				if ( ! isset( $section_value[ $field['name'] ] ) ) {
					$section_value[ $field['name'] ] = isset( $field['default'] ) ? $field['default'] : '';
				}

			}

			return $section_value;
		}//end method getMissingDefaultValueBySection

		/**
		 * Get field description for display
		 *
		 *
		 * @param  array  $args
		 *
		 * @return string
		 */
		public function get_field_description( $args ) {
			if ( ! empty( $args['desc'] ) ) {
				$desc = sprintf( '<div class="description">%s</div>', $args['desc'] );
			} else {
				$desc = '';
			}

			return $desc;
		}//end method get_field_description

		/**
		 * Displays a textarea for a settings field
		 *
		 * @param  array  $args
		 * @param $value
		 *
		 * @return void
		 */
		function callback_html( $args, $value = null ) {
			echo $this->get_field_description( $args );
		}//end method callback_html

		/**
		 * Displays heading field using h3
		 *
		 * @param  array  $args
		 *
		 * @return string
		 */
		function callback_heading( $args ) {
			$html = '<h3 class="setting_heading"><span class="setting_heading_title">' . $args['name'] . '</span><a title="' . esc_attr__( 'Click to show hide', 'cbcurrencyconverter' ) . '" class="setting_heading_toggle" href="#"></a></h3>';
			$html .= $this->get_field_description( $args );

			echo $html;
		}//end method callback_heading

		/**
		 * Displays sub heading field using h4
		 *
		 * @param  array  $args
		 *
		 * @return void
		 */
		function callback_subheading( $args ) {
			$html = '<h4 class="setting_subheading">' . $args['name'] . '</h4>';
			$html .= $this->get_field_description( $args );

			echo $html;
		}//end method callback_subheading

		/**
		 * Displays an url field for a settings field
		 *
		 * @param  array  $args
		 *
		 * @return void
		 */
		function callback_url( $args ) {
			$this->callback_text( $args );
		}//end method callback_url

		/**
		 * Displays a number field for a settings field
		 *
		 * @param  array  $args
		 *
		 * @return void
		 */
		function callback_number( $args, $value = null ) {
			if ( $value === null ) {
				$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['default'] ) );
			}

			$size        = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$type        = isset( $args['type'] ) ? $args['type'] : 'number';
			$placeholder = empty( $args['placeholder'] ) ? '' : ' placeholder="' . $args['placeholder'] . '"';
			$min         = empty( $args['min'] ) ? '' : ' min="' . $args['min'] . '"';
			$max         = empty( $args['max'] ) ? '' : ' max="' . $args['max'] . '"';
			$step        = empty( $args['max'] ) ? '' : ' step="' . $args['step'] . '"';

			$html_id = "{$args['section']}_{$args['id']}";
			$html_id = $this->settings_clean_label_for( $html_id );

			$html = sprintf( '<input type="%1$s" class="%2$s-number" id="%10$s" name="%3$s[%4$s]" value="%5$s"%6$s%7$s%8$s%9$s/>', $type, $size, $args['section'], $args['id'], $value, $placeholder, $min, $max, $step, $html_id );
			$html .= $this->get_field_description( $args );
			echo $html;
		}//end method callback_number

		/**
		 * Displays a checkbox for a settings field
		 *
		 * @param  array  $args
		 * @param $value
		 *
		 * @return void
		 */
		function callback_checkbox( $args, $value = null ) {
			/*if ( $value === null ) {
				$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['default'] ) );
			}

			$html_id = "{$args['section']}_{$args['id']}";
			$html_id = $this->settings_clean_label_for( $html_id );

			$html = '<fieldset>';
			$html .= sprintf( '<label for="wpuf-%1$s">', $html_id );
			$html .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="off" />', $args['section'], $args['id'] );

			$active_class = ( $value == 'on' ) ? 'active' : '';
			$html         .= '<span class="checkbox-toggle-btn ' . esc_attr( $active_class ) . '">';
			//$html         .= sprintf( '<input type="checkbox" class="checkbox" id="wpuf-%1$s_%2$s" name="%1$s[%2$s]" value="on" %3$s />', $args['section'], $args['id'], checked( $value, 'on', false ) );
			$html .= sprintf( '<input type="checkbox" class="checkbox" id="wpuf-%4$s" name="%1$s[%2$s]" value="on" %3$s />', $args['section'], $args['id'], checked( $value, 'on', false ), $html_id );
			$html .= '<i class="checkbox-round-btn"></i></span>';

			$html .= sprintf( '<i class="checkbox-round-btn-text">%1$s</i></label>', $args['desc'] );
			$html .= '</fieldset>';

			echo $html;*/

			if ( $value === null ) {
				$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['default'] ) );
			}

			$html_id = "{$args['section']}_{$args['id']}";
			$html_id = $this->settings_clean_label_for( $html_id );

			//$display_inline        = isset( $args['inline']) ? absint($args['inline']) : 1;
			//$display_inline_class = ($display_inline)? 'radio_fields_inline' : '';

			$html = '<div class="checkbox_field magic_checkbox_field">';
			$html .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="off" />', $args['section'], $args['id'] );
			$html .= sprintf( '<input type="checkbox" class="magic-checkbox" id="wpuf-%4$s" name="%1$s[%2$s]" value="on" %3$s />', $args['section'], $args['id'], checked( $value, 'on', false ), $html_id );
			$html .= sprintf( '<label for="wpuf-%1$s">', $html_id );
			$html .= sprintf( '%1$s</label>', $args['desc'] );
			$html .= '</div>';

			echo $html;
		}//end method callback_checkbox


		/**
		 * Displays a multicheckbox a settings field
		 *
		 * @param  array  $args
		 * @param $value
		 *
		 * @return void
		 */
		function callback_radio( $args, $value = null ) {
			/*if ( $value === null ) {
				$value = $this->get_option( $args['id'], $args['section'], $args['default'] );
			}

			$html = '<fieldset class="radio_fields">';
			foreach ( $args['options'] as $key => $label ) {

				$html_id = "{$args['section']}_{$args['id']}_{$key}";
				$html_id = $this->settings_clean_label_for( $html_id );

				$html .= sprintf( '<label for="wpuf-%1$s">', $html_id );
				$html .= sprintf( '<input type="radio" class="radio" id="wpuf-%5$s" name="%1$s[%2$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked( $value, $key, false ), $html_id );
				$html .= sprintf( '%1$s</label>', $label );
			}
			$html .= $this->get_field_description( $args );
			$html .= '</fieldset>';

			echo $html;*/

			if ( $value === null ) {
				$value = $this->get_option( $args['id'], $args['section'], $args['default'] );
			}

			$display_inline       = isset( $args['inline'] ) ? absint( $args['inline'] ) : 1;
			$display_inline_class = ( $display_inline ) ? 'radio_fields_inline' : '';

			$html = '<div class="radio_fields magic_radio_fields ' . esc_attr( $display_inline_class ) . '">';

			foreach ( $args['options'] as $key => $label ) {

				$html_id = "{$args['section']}_{$args['id']}_{$key}";
				$html_id = $this->settings_clean_label_for( $html_id );


				$html .= '<div class="magic-radio-field">';
				//$html .= sprintf( '<input type="radio" class="radio" id="wpuf-%5$s" name="%1$s[%2$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked( $value, $key, false ), $html_id );
				$html .= sprintf( '<input type="radio" class="magic-radio" id="wpuf-%5$s" name="%1$s[%2$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked( $value, $key, false ), $html_id );
				$html .= sprintf( '<label for="wpuf-%1$s">', $html_id );
				$html .= sprintf( '%1$s</label>', $label );
				$html .= '</div>';
			}


			$html .= $this->get_field_description( $args );
			$html .= '</div>';

			echo $html;
		}//end method callback_radio


		/**
		 * Displays a selectbox for a settings field
		 *
		 * @param  array  $args  settings field args
		 */
		function callback_select_backup( $args ) {
			$html_id = "{$args['section']}_{$args['id']}";
			$html_id = $this->settings_clean_label_for( $html_id );

			$size = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular selecttwo-select';
			$html = sprintf( '<div class="selecttwo-select-wrapper"><select class="%1$s" name="%2$s[%3$s]" id="%4$s">', $size, $args['section'], $args['id'], $html_id );


			if ( isset( $args['optgroup'] ) && $args['optgroup'] ) {
				$value = $this->get_option( $args['id'], $args['section'], $args['default'] );
				if ( ! is_array( $value ) || $value == '' ) {
					$value = [];
				}

				foreach ( $args['options'] as $opt_grouplabel => $data ) {
					$html .= '<optgroup label="' . $opt_grouplabel . '">';

					if ( ! is_array( $data ) ) {
						$data = [];
					}

					foreach ( $data as $key => $label ) {
						$selected = in_array( $key, $value ) ? ' selected="selected" ' : '';
						$html     .= sprintf( '<option value="%s" ' . $selected . '>%s</option>', $key, $label );
					}
					$html .= '<optgroup>';
				}
			} else {
				$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['default'] ) );
				foreach ( $args['options'] as $key => $label ) {
					//$selected = in_array($key, $value) ? ' selected="selected" ' : '';
					//$html     .= sprintf('<option value="%s" ' . $selected . '>%s</option>', $key, $val);
					$html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $value, $key, false ), $label );
				}
			}

			$html .= sprintf( '</select></div>' );
			$html .= $this->get_field_description( $args );

			echo $html;
		}//end method callback_select_backup


		/**
		 * Displays a selectbox for a settings field
		 *
		 * @param  array  $args
		 *
		 * @return void
		 */
		function callback_select( $args ) {
			$value = $this->get_option( $args['id'], $args['section'], $args['default'] );

			$multi      = isset( $args['multi'] ) ? intval( $args['multi'] ) : 0;
			$multi_name = ( $multi ) ? '[]' : '';
			$multi_attr = ( $multi ) ? 'multiple' : '';

			if ( $multi && ! is_array( $value ) ) {
				$value = [];
			}

			/*if ( ! is_array( $value ) ) {
				$value = [];
			}*/

			$size = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular selecttwo-select';

			if ( $args['placeholder'] == '' ) {
				$args['placeholder'] = esc_html__( 'Please Select', 'cbxresume' );
			}

			$html_id = "{$args['section']}_{$args['id']}";
			$html_id = $this->settings_clean_label_for( $html_id );

			//$html = sprintf( '<input type="hidden" name="%1$s[%2$s][]" value="" />', $args['section'], $args['id'] );
			$html = sprintf( '<div class="selecttwo-select-wrapper"><select ' . $multi_attr . ' class="%1$s" name="%2$s[%3$s]' . $multi_name . '" id="%5$s" style="min-width: 150px !important;"  placeholder="%4$s" data-placeholder="%4$s">', $size, $args['section'], $args['id'], $args['placeholder'], $html_id );

			if ( isset( $args['optgroup'] ) && $args['optgroup'] ) {
				foreach ( $args['options'] as $opt_grouplabel => $option_vals ) {
					$html .= '<optgroup label="' . $opt_grouplabel . '">';

					if ( ! is_array( $option_vals ) ) {
						$option_vals = [];
					} else {
						$option_vals = $option_vals;
					}

					foreach ( $option_vals as $key => $val ) {
						$selected = in_array( $key, $value ) ? ' selected="selected" ' : '';
						$html     .= sprintf( '<option value="%s" ' . $selected . '>%s</option>', $key, $val );
					}
					$html .= '<optgroup>';
				}
			} else {
				$option_vals = $args['options'];

				foreach ( $option_vals as $key => $val ) {
					if ( $multi ) {
						$selected = in_array( $key, $value ) ? ' selected="selected" ' : '';
						$html     .= sprintf( '<option value="%s" ' . $selected . '>%s</option>', $key, $val );
					} else {
						$html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $value, $key, false ), $val );
					}
				}
			}

			$html .= '</select></div>';
			$html .= $this->get_field_description( $args );

			echo $html;
		}//end method callback_select

		/**
		 * Displays a multi-selectbox for a settings field(deprecated)
		 *
		 * @param $args
		 *
		 * @return void
		 */
		function callback_multiselect( $args ) {
			$value = $this->get_option( $args['id'], $args['section'], $args['default'] );

			if ( ! is_array( $value ) ) {
				$value = [];
			}

			$size = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular selecttwo-select';

			if ( $args['placeholder'] == '' ) {
				$args['placeholder'] = esc_html__( 'Please Select', 'wpnextpreviouslink' );
			}

			$html_id = "{$args['section']}_{$args['id']}";
			$html_id = $this->settings_clean_label_for( $html_id );

			$html = sprintf( '<input type="hidden" name="%1$s[%2$s][]" value="" />', $args['section'], $args['id'] );
			$html .= sprintf( '<div class="selecttwo-select-wrapper"><select multiple class="%1$s" name="%2$s[%3$s][]" id="%5$s" style="min-width: 150px !important;"  placeholder="%4$s" data-placeholder="%4$s">', $size, $args['section'], $args['id'], $args['placeholder'], $html_id );


			if ( isset( $args['optgroup'] ) && $args['optgroup'] ) {
				foreach ( $args['options'] as $opt_grouplabel => $option_vals ) {
					$html .= '<optgroup label="' . $opt_grouplabel . '">';

					if ( ! is_array( $option_vals ) ) {
						$option_vals = [];
					} else {
						$option_vals = $option_vals;
					}


					foreach ( $option_vals as $key => $val ) {
						$selected = in_array( $key, $value ) ? ' selected="selected" ' : '';
						$html     .= sprintf( '<option value="%s" ' . $selected . '>%s</option>', $key, $val );
					}
					$html .= '<optgroup>';
				}
			} else {

				$option_vals = $args['options'];
				foreach ( $option_vals as $key => $val ) {
					$selected = in_array( $key, $value ) ? ' selected="selected" ' : '';
					$html     .= sprintf( '<option value="%s" ' . $selected . '>%s</option>', $key, $val );
				}
			}

			$html .= '</select></div>';
			$html .= $this->get_field_description( $args );

			echo $html;
		}//end method callback_multiselect

		/**
		 * Displays a textarea for a settings field
		 *
		 * @param  array  $args
		 * @param $value
		 *
		 * @return void
		 */
		function callback_textarea( $args, $value = null ) {
			if ( $value === null ) {
				$value = esc_textarea( $this->get_option( $args['id'], $args['section'], $args['default'] ) );
			}
			$size = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			$html_id = "{$args['section']}_{$args['id']}";
			$html_id = $this->settings_clean_label_for( $html_id );

			$html = sprintf( '<textarea rows="5" cols="55" class="%1$s-text" id="%5$s" name="%2$s[%3$s]">%4$s</textarea>', $size, $args['section'], $args['id'], $value, $html_id );
			$html .= $this->get_field_description( $args );

			echo $html;
		}//end method callback_textarea

		/**
		 * Displays a rich text textarea for a settings field
		 *
		 * @param  array  $args
		 * @param $value
		 *
		 * @return void
		 */
		function callback_wysiwyg( $args, $value = null ) {
			if ( $value === null ) {
				$value = $this->get_option( $args['id'], $args['section'], $args['default'] );
			}
			$size = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : '500px';

			echo '<div style="max-width: ' . $size . ';">';

			$html_id = "{$args['section']}_{$args['id']}";
			$html_id = $this->settings_clean_label_for( $html_id );

			$editor_settings = [
				'teeny'         => true,
				'textarea_name' => $args['section'] . '[' . $args['id'] . ']',
				'textarea_rows' => 10
			];
			if ( isset( $args['options'] ) && is_array( $args['options'] ) ) {
				$editor_settings = array_merge( $editor_settings, $args['options'] );
			}

			//wp_editor( $value, $args['section'] . '-' . $args['id'], $editor_settings );
			wp_editor( $value, $html_id, $editor_settings );

			echo '</div>';

			echo $this->get_field_description( $args );
		}//end method callback_wysiwyg


		/**
		 * Displays a file upload field for a settings field
		 *
		 * @param  array  $args  settings field args
		 */
		function callback_file( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['default'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			//$id    = $args['section'] . '[' . $args['id'] . ']';
			$html_id = "{$args['section']}_{$args['id']}";
			$html_id = $this->settings_clean_label_for( $html_id );


			$label = isset( $args['options']['button_label'] ) ?
				$args['options']['button_label'] :
				esc_html__( 'Choose File', 'cbcurrencyconverter' );

			$html = '<div class="wpsa-browse-wrap">';
			$html .= sprintf( '<input type="text" class="chota-inline %1$s-text wpsa-url" id="%5$s" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value, $html_id );
			$html .= '<input type="button" class="button outline primary wpsa-browse" value="' . $label . '" />';
			$html .= '</div>';
			$html .= $this->get_field_description( $args );

			echo $html;
		}

		/**
		 * Displays a color picker field for a settings field
		 *
		 * @param  array  $args
		 * @param $value
		 *
		 * @return void
		 */
		function callback_color( $args, $value = null ) {

			if ( $value === null ) {
				$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['default'] ) );
			}

			$size = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			$html_id = "{$args['section']}_{$args['id']}";
			$html_id = $this->settings_clean_label_for( $html_id );

			$choose_color = esc_html__( 'Choose Color', 'cbcurrencyconverter' );

			$html = '<div class="setting-color-picker-wrapper">';
			$html .= sprintf( '<input type="hidden" class="%1$s-text setting-color-picker" id="%6$s" name="%2$s[%3$s]" value="%4$s" /><span data-current-color="%4$s"  class="button setting-color-picker-fire">%7$s</span>', $size, $args['section'], $args['id'], $value, $args['default'], $html_id, $choose_color );
			$html .= '</div>';

			$html .= $this->get_field_description( $args );

			echo $html;
		}//end callback_color

		/**
		 * Host servers type field
		 *
		 * @param $args
		 *
		 * @return void
		 */
		function callback_repeat( $args ) {
			$section_name = esc_attr( $args['section'] );
			$option_name  = esc_attr( $args['id'] );

			$default   = $args['default'];
			$fields    = isset( $args['fields'] ) ? $args['fields'] : [];
			$allow_new = isset( $args['allow_new'] ) ? intval( $args['allow_new'] ) : 0;
			$value     = $this->get_option( $args['id'], $args['section'], $args['default'] );


			if ( ! is_array( $value ) ) {
				$value = [];
			}


			$html  = '';
			$index = 0;

			$html .= '<div class="form-table-fields-parent-wrap">';
			$html .= '<div class="form-table-fields-parent">';
			if ( is_array( $fields ) & sizeof( $fields ) > 0 ) {

				foreach ( $value as $val ) {
					if ( ! is_array( $val ) ) {
						$val = [];
					}

					$html .= '<div class="form-table-fields-parent-item">';
					$html .= '<h5>' . $args['name'] . ' #' . ( $index + 1 );
					$html .= '<span class="form-table-fields-parent-item-icon form-table-fields-parent-item-sort"></span>';
					$html .= '<span class="form-table-fields-parent-item-icon form-table-fields-parent-item-control"></span>';
					if ( $allow_new ) {
						//if allow new then allow delete
						$html .= '<span class="form-table-fields-parent-item-icon form-table-fields-parent-item-delete"></span>';
					}
					$html .= '</h5>';
					$html .= '<div class="form-table-fields-parent-item-wrap">';

					$html .= '<table class="form-table-fields-items">';
					foreach ( $fields as $field ) {
						$args_t = $args;
						unset( $args_t['fields'] );
						unset( $args_t['allow_new'] );

						$args_t['section']           = isset( $args['section'] ) ? $args['section'] . '[' . $args['id'] . '][' . $index . ']' : '';
						$args_t['desc']              = isset( $field['desc'] ) ? $field['desc'] : '';
						$args_t['name']              = isset( $field['name'] ) ? $field['name'] : '';
						$args_t['label']             = isset( $field['label'] ) ? $field['label'] : '';
						$args_t['class']             = isset( $field['class'] ) ? $field['class'] : $args_t['name'];
						$args_t['id']                = $args_t['name'];
						$args_t['size']              = isset( $field['size'] ) ? $field['size'] : null;
						$args_t['min']               = isset( $field['min'] ) ? $field['min'] : '';
						$args_t['max']               = isset( $field['max'] ) ? $field['max'] : '';
						$args_t['step']              = isset( $field['step'] ) ? $field['step'] : '';
						$args_t['options']           = isset( $field['options'] ) ? $field['options'] : '';
						$args_t['default']           = isset( $field['default'] ) ? $field['default'] : '';
						$args_t['sanitize_callback'] = isset( $field['sanitize_callback'] ) ? $field['sanitize_callback'] : '';
						$args_t['placeholder']       = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
						$args_t['type']              = isset( $field['type'] ) ? $field['type'] : 'text';
						$args_t['optgroup']          = isset( $field['optgroup'] ) ? intval( $field['optgroup'] ) : 0;
						$args_t['sortable']          = isset( $field['sortable'] ) ? intval( $field['sortable'] ) : 0;
						$callback                    = isset( $field['callback'] ) ? $field['callback'] : [
							$this,
							'callback_' . $args_t['type']
						];


						//$val_t = isset( $val[ $field['name'] ] ) ? $val[ $field['name'] ] : ( is_array( $args_t['default'] ) ? [] : '' );
						$val_t = isset( $val[ $field['name'] ] ) ? $val[ $field['name'] ] : $args_t['default'];

						$html    .= '<tr class="form-table-fields-item"><td>';
						$html_id = "{$args_t['section']}_{$args_t['id']}";
						$html_id = $this->settings_clean_label_for( $html_id );
						$html    .= sprintf( '<label class="main-label" for="%1$s">%2$s</label>', $html_id, $args_t['label'] );
						$html    .= '</td></tr>';

						$html .= '<tr class="form-table-fields-item"><td>';
						ob_start();
						call_user_func( $callback, $args_t, $val_t );
						$html .= ob_get_contents();
						ob_end_clean();
						$html .= '</td></tr>';
					}
					$html .= '</table>';
					$html .= '</div>';
					$html .= '</div>';
					$index ++;
				}

			}

			$html .= '</div>';

			if ( $allow_new ) {
				$html .= '<p style="text-align: center;"><a data-index="' . intval( $index ) . '" data-busy="0" data-field_name="' . $args['name'] . '" data-section_name="' . $section_name . '" data-option_name="' . $option_name . '" class="button secondary form-table-fields-new" href="#">' . esc_html__( 'Add New', 'cbcurrencyconverter' ) . '</a></p>';
			}

			$html .= '</div>';
			$html .= $this->get_field_description( $args );

			echo $html;
		}//end callback_repeat

		/**
		 * Displays a text field for a settings field
		 *
		 * @param  array  $args  settings field args
		 */
		function callback_password( $args, $value = null ) {

			if ( $value === null ) {
				$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['default'] ) );
			}
			$size = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$type = isset( $args['type'] ) ? $args['type'] : 'password';

			$html_id = "{$args['section']}_{$args['id']}";
			$html_id = $this->settings_clean_label_for( $html_id );

			$html = sprintf( '<input onfocus="this.removeAttribute(\'readonly\');" readonly autocomplete="none" type="%1$s" class="cbx-hideshowpassword %2$s-text" id="%6$s" name="%3$s[%4$s]" value="%5$s"/>', $type, $size, $args['section'], $args['id'], $value, $html_id );
			$html .= $this->get_field_description( $args );

			echo $html;
		}//end method callback_password

		/**
		 * Displays a text field for a settings field
		 *
		 * @param  array  $args
		 * @param $value
		 *
		 * @return void
		 */
		function callback_text( $args, $value = null ) {
			if ( $value === null ) {
				$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['default'] ) );
			}
			$size = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$type = isset( $args['type'] ) ? $args['type'] : 'text';

			$html_id = "{$args['section']}_{$args['id']}";
			$html_id = $this->settings_clean_label_for( $html_id );

			$html = sprintf( '<input autocomplete="none" onfocus="this.removeAttribute(\'readonly\');" readonly type="%1$s" class="%2$s-text" id="%6$s" name="%3$s[%4$s]" value="%5$s"/>', $type, $size, $args['section'], $args['id'], $value, $html_id );
			$html .= $this->get_field_description( $args );

			echo $html;
		}//end callback_text

		/**
		 * Displays a textarea for a settings field
		 *
		 * @param  array  $args  settings field args
		 */
		function callback_shortcode( $args ) {
			$value     = $args['default'];
			$value_esc = esc_textarea( $value );
			$size      = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$class     = isset( $args['class'] ) && ! is_null( $args['class'] ) ? $args['class'] : '';

			$required = isset( $args['required'] ) ? 'required' : '';

			$html_id = "{$args['section']}_{$args['id']}";
			$html_id = $this->settings_clean_label_for( $html_id );


			$html = sprintf( '<textarea readonly rows="5" cols="55" class="%1$s-text" id="%7$s" name="%2$s[%3$s]" %5$s>%4$s</textarea>',
				$size,
				$args['section'],
				$args['id'],
				$value_esc,
				$required, $class, $html_id );


			$html .= '<p><a data-target-cp="#' . $html_id . '" class="shortcode_demo_btn" href="#">' . esc_html__( 'Click to copy shortcode', 'cbcurrencyconverter' ) . '</a></p>';
			$html .= $this->get_field_description( $args );


			$html .= '<div class="shortcode_demo_wrap">' . do_shortcode( $value ) . '</div>';
			echo $html;
		}//end method callback_shortcode


		/**
		 * Custom rates display field
		 *
		 * @param $args
		 */
		function callback_custom_rates( $args ) {
			//$value = $this->get_option( $args['id'], $args['section'], $args['default'] );

			$custom_rates = get_option( 'cbcurrencyconverter_custom_rates', true );
			if ( ! is_array( $custom_rates ) ) {
				$custom_rates = [];
			}

			$from_curs = $this->get_option( 'from_rates', $args['section'], [] );
			if ( ! is_array( $from_curs ) ) {
				$from_curs = [];
			}

			$from_curs = array_values( array_filter( $from_curs ) );

			if ( sizeof( $from_curs ) == 0 ) {
				echo esc_html__( 'Please choose at least one currency from above field.', 'cbcurrencyconverter' );
			}

			echo '<div id="custom_rates_wrap">';

			echo '<ul id="custom_rates_ul">';
			$i = 0;
			foreach ( $from_curs as $from_cur ) {
				$active_class = '';
				if ( $i == 0 ) {
					$active_class = 'active';
				}

				echo '<li class="' . esc_attr( $active_class ) . '"><a href="#" class="custom-currency-link" data-currency=' . esc_attr( $from_cur ) . '>' . esc_attr( $from_cur ) . '</a></li>';
				$i ++;
			}
			echo '</ul>';

			echo '<div id="custom_rates_contents">';

			$i = 0;
			foreach ( $from_curs as $from_cur ) {
				$hide_style   = '';
				$active_class = '';
				if ( $i != 0 ) {
					$hide_style = ' display: none; ';
				} else {
					$active_class = 'active';
				}

				echo '<div class="custom_rates_content custom_rates_content_' . esc_attr( $from_cur ) . '  ' . esc_html( $active_class ) . '" style="' . $hide_style . '">';

				echo '<p class="custom_rate_heading">' . sprintf( esc_html__( 'Custom rates based on %s', 'cbcurrencyconverteraddon' ), $from_cur ) . '</p>';

				$all_currencies = CBCurrencyConverterHelper::getCurrencyList();

				if ( ! empty( $all_currencies ) ) {
					foreach ( $all_currencies as $code => $currency ) {
						$value = isset( $custom_rates['custom_rates'][ $from_cur . '-' . $code ] ) ? $custom_rates['custom_rates'][ $from_cur . '-' . $code ] : 0;
						echo '<div class="custom_rate_from custom_rate_from_' . esc_attr( $code ) . '">';
						echo '<p class="custom_rate_name">' . $currency . '-' . $code . '</p>';
						echo '<input type="number" style="height: 30px;" class="regular-text" id="' . $args['section'] . '_custom_rates_' . $from_cur . '_' . $code . '" name="' . $args['section'] . '[custom_rates][' . $from_cur . '-' . $code . ']" value="' . $value . '" step="0.000000001"/>';
						echo '<a data-busy="0" type="button" data-from="' . $from_cur . '" data-to="' . $code . '" title="' . esc_attr__( 'Auto fill from Api Source', 'cbcurrencyconverteraddon' ) . '" class="button outline primary auto_fill_customrate">' . esc_attr__( 'Auto Fill via Api', 'cbcurrencyconverteraddon' ) . '</a>';
						echo '</div>';
					}
				}

				echo '</div>';

				$i ++;
			}
			echo '</div>';

			echo '</div>';
		}//end method callback_custom_rates

		/**
		 * Displays a textarea for a settings field
		 *
		 * @param  array  $args  settings field args
		 */
		function callback_woocomemrce_check( $args ) {
			$value     = $args['default'];
			$value_esc = esc_textarea( $value );
			$size      = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$class     = isset( $args['class'] ) && ! is_null( $args['class'] ) ? $args['class'] : '';

			$required = isset( $args['required'] ) ? 'required' : '';

			$html_id = "{$args['section']}_{$args['id']}";
			$html_id = $this->settings_clean_label_for( $html_id );


			$woo_active       = false;
			$woo_active_class = '';
			$woo_active_text  = esc_html__( 'Plugin Woocommerce is not installed or activated', 'cbcurrencyconverter' );

			if ( class_exists( 'woocommerce' ) ) {
				$woo_active       = true;
				$woo_active_class = 'plugin_check_wrap_on';
				$woo_active_text  = esc_html__( 'Plugin Woocommerce is installed and activated', 'cbcurrencyconverter' );
			}

			$html = '<div class="plugin_check_wrap ' . esc_attr( $woo_active_class ) . '">' . $woo_active_text . '</div>';


			echo $html;
		}//end method callback_woocomemrce_check


		/**
		 * Convert an array to associative if not
		 *
		 * @param $value
		 *
		 * @return array|mixed
		 */
		private function convert_associate( $value ) {
			if ( ! $this->is_associate( $value ) && sizeof( $value ) > 0 ) {
				$new_value = [];
				foreach ( $value as $val ) {
					$new_value[ $val ] = ucfirst( $val );
				}

				return $new_value;
			}


			return $value;
		}//end method convert_associate


		/**
		 * check if any array is associative
		 *
		 * @param  array  $array
		 *
		 * @return bool
		 */
		private function is_associate( array $array ) {
			return count( array_filter( array_keys( $array ), 'is_string' ) ) > 0;
		}//end method is_associate

		/**
		 * Sanitize callback for Settings API
		 */
		function sanitize_options( $options ) {
			foreach ( $options as $option_slug => $option_value ) {
				$sanitize_callback = $this->get_sanitize_callback( $option_slug );

				// If callback is set, call it
				if ( $sanitize_callback ) {
					$options[ $option_slug ] = call_user_func( $sanitize_callback, $option_value );
					continue;
				}
			}

			return $options;
		}

		/**
		 * Get sanitization callback for given option slug
		 *
		 * @param  string  $slug  option slug
		 *
		 * @return mixed string or bool false
		 */
		function get_sanitize_callback( $slug = '' ) {
			if ( empty( $slug ) ) {
				return false;
			}

			// Iterate over registered fields and see if we can find proper callback
			foreach ( $this->settings_fields as $section => $options ) {
				foreach ( $options as $option ) {
					if ( $option['name'] != $slug ) {
						continue;
					}

					if ( ( $option['type'] == 'select' && isset( $option['multi'] ) && $option['multi'] ) || $option['type'] == 'multicheck' ) {
						$option['sanitize_callback'] = [ $this, 'sanitize_multi_select_check' ];
					}

					// Return the callback name
					return isset( $option['sanitize_callback'] ) && is_callable( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : false;
				}
			}

			return false;
		}//end get_sanitize_callback

		/**
		 * Remove empty values from multi select fields (multi select and multi checkbox)
		 *
		 * @param $option_value
		 *
		 * @return array
		 */
		public function sanitize_multi_select_check( $option_value ) {
			if ( is_array( $option_value ) ) {
				return array_filter( $option_value );
			}

			return $option_value;
		}

		/**
		 * Clean label_for or id tad
		 *
		 * @param $str
		 *
		 * @return string
		 */
		public function settings_clean_label_for( $str ) {
			$str = str_replace( '][', '_', $str );
			$str = str_replace( ']', '_', $str );

			return str_replace( '[', '_', $str );

			//return $str;
		}//end settings_clean_label_for

		/**
		 * Get the value of a settings field
		 *
		 * @param  string  $option  settings field name
		 * @param  string  $section  the section name this field belongs to
		 * @param  string  $default  default text if it's not found
		 *
		 * @return string
		 */
		function get_option( $option, $section, $default = '' ) {

			$options = get_option( $section );

			if ( isset( $options[ $option ] ) ) {
				return $options[ $option ];
			}

			return $default;
		}//end method get_option

		/**
		 * Show navigations as tab
		 *
		 * Shows all the settings section labels as tab
		 */
		function show_navigation() {
			$html = '<nav class="tabs setting-tabs setting-tabs-nav mb-0">';

			$i = 0;

			$mobile_navs = '<div class="selecttwo-select-wrapper setting-select-wrapper"><select data-minimum-results-for-search="Infinity" class="setting-select setting-select-nav selecttwo-select">';

			foreach ( $this->settings_sections as $tab ) {
				$active_class  = ( $i === 0 ) ? 'active' : '';
				$active_select = ( $i === 0 ) ? ' selected ' : '';

				$html        .= sprintf( '<a data-tabid="' . $tab['id'] . '" href="#%1$s" class="%3$s" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['title'], $active_class );
				$mobile_navs .= '<option ' . esc_attr( $active_select ) . ' value="' . $tab['id'] . '">' . esc_attr( $tab['title'] ) . '</option>';
				$i ++;
			}

			$mobile_navs .= '</select></div>';
			$html        .= '</nav>';

			echo '<div id="setting-tabs-nav-wrap">';
			echo $html;
			echo $mobile_navs;
			echo '</div>';
		}//end method show_navigation

		/**
		 * Show the section settings forms
		 *
		 * This function displays every sections in a different form
		 */
		function show_forms() {
			?>
            <div id="setting-tabs-contents">
                <div id="global_setting_group_actions" class="mb-0 mt-10">
					<?php do_action( 'cbcurrencyconverter_setting_group_actions_start' ); ?>
                    <a class="button outline primary global_setting_group_action global_setting_group_action_open pull-right" href="#"><?php esc_html_e( 'Toggle All Sections', 'cbcurrencyconverter' ); ?></a>
					<?php do_action( 'cbcurrencyconverter_setting_group_actions_end' ); ?>
                    <div class="clear clearfix"></div>
                </div>
                <div class="metabox-holder">
					<?php

					$i = 0;
					foreach ( $this->settings_sections as $form ) {
						$display_style = ( $i === 0 ) ? '' : 'display: none;';


						?>
                        <div id="<?php echo $form['id']; ?>" class="global_setting_group" style="<?php echo $display_style; ?>">
                            <form method="post" action="options.php" class="cbcurrencyconverter_setting_form">
								<?php
								do_action( 'cbcurrencyconverter_setting_form_start', $form );
								do_action( 'cbcurrencyconverter_setting_form_top_' . $form['id'], $form );

								settings_fields( $form['id'] );
								do_settings_sections( $form['id'] );

								do_action( 'cbcurrencyconverter_setting_form_bottom_' . $form['id'], $form );
								do_action( 'cbcurrencyconverter_setting_form_end', $form );
								?>
                                <div class="global_setting_submit_buttons_wrap">
									<?php //submit_button( esc_html__( 'Save Settings', 'cbcurrencyconverter' ), 'button primary submit_setting', 'submit', true, [ 'id' => 'submit_' . esc_attr( $form['id'] ) ] ); ?>
									<?php do_action( 'cbcurrencyconverter_setting_submit_buttons_start', $form['id'] ); ?>
									<?php submit_button( esc_html__( 'Save Settings', 'cbcurrencyconverter' ), 'button primary submit_setting', 'submit', true, [ 'id' => 'submit_' . esc_attr( $form['id'] ) ] ); ?>
									<?php do_action( 'cbcurrencyconverter_setting_submit_buttons_end', $form['id'] ); ?>
                                </div>
                            </form>
                        </div>
						<?php
						$i ++;
					}
					?>
                </div>
            </div>
			<?php
		}//end show_forms

	}//end class CBCurrencyconverterSetting
endif;