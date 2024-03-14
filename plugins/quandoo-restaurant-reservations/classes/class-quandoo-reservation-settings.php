<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

final class Quandoo_Reservation_Settings {

	private static $_instance = null;

	public static function instance () {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	} // End instance()

	public function validate_settings ( $input, $section ) {
		if ( is_array( $input ) && 0 < count( $input ) ) {
			$fields = $this->get_settings_fields( $section );

			foreach ( $input as $k => $v ) {
				if ( ! isset( $fields[$k] ) ) {
					continue;
				}
				// Determine if a method is available for validating this field.
				$method = 'validate_field_' . $fields[$k]['type'];

				if ( ! method_exists( $this, $method ) ) {
					if ( true === (bool)apply_filters( 'quandoo-reservation-validate-field-' . $fields[$k]['type'] . '_use_default', true ) ) {
						$method = 'validate_field_text';
					} else {
						$method = '';
					}
				}
				// If we have an internal method for validation, filter and apply it.
				if ( '' != $method ) {
					add_filter( 'quandoo-reservation-validate-field-' . $fields[$k]['type'], array( $this, $method ) );
				}

				$method_output = apply_filters( 'quandoo-reservation-validate-field-' . $fields[$k]['type'], $v, $fields[$k] );

				if ( ! is_wp_error( $method_output ) ) {
					$input[$k] = $method_output;
				}
			}
		}
		return $input;
	} // End validate_settings()

	public function validate_field_text ( $v ) {
		return (string)wp_kses_post( $v );
	} // End validate_field_text()

	public function validate_field_checkbox ( $v ) {
		if ( 'true' != $v ) {
			return 'false';
		} else {
			return 'true';
		}
	} // End validate_field_checkbox()

	public function render_field ( $args ) {
		$html = '';
		if ( ! in_array( $args['type'], $this->get_supported_fields() ) ) return ''; // Supported field type sanity check.

		// Make sure we have some kind of default, if the key isn't set.
		if ( ! isset( $args['default'] ) ) {
			$args['default'] = '';
		}

		if ( ! isset( $args['class'] ) ) {
			$args['class'] = '';
		}

		$method = 'render_field_' . $args['type'];

		if ( ! method_exists( $this, $method ) ) {
			$method = 'render_field_text';
		}
		// Construct the key.
		$key 				= Quandoo_Reservation()->token . '-' . $args['section'] . '[' . $args['id'] . ']';
		$method_output 		= $this->$method( $key, $args );

		if ( ! is_wp_error( $method_output ) ) {
			$html .= $method_output;
		}

		// Output the description, if the current field allows it.
		if ( isset( $args['type'] ) && ! in_array( $args['type'], (array)apply_filters( 'quandoo-reservation-no-description-fields', array( 'checkbox' ) ) ) ) {
			if ( isset( $args['description'] ) ) {
				$description = '<p class="description">' . wp_kses_post( $args['description'] ) . '</p>' . "\n";
				if ( in_array( $args['type'], (array)apply_filters( 'quandoo-reservation-new-line-description-fields', array( 'textarea', 'select' ) ) ) ) {
					$description = wpautop( $description );
				}
				$html .= $description;
			}
		}

		echo $html;
	} // End render_field()

	public function get_settings_sections () {
		$settings_sections = array();

		$settings_sections['standard-fields'] = __( 'Quick Reservation Button', 'quandoo-reservation' );
		//$settings_sections['special-fields'] = __( 'Custom Widgets', 'edit.php?post_type=quandoo-reservation' );
		// Add your new sections below here.
		// Admin tabs will be created for each section.
		// Don't forget to add fields for the section in the get_settings_fields() function below

		return (array)apply_filters( 'quandoo-reservation-settings-sections', $settings_sections );
	} // End get_settings_sections()



	public function get_settings_fields ( $section ) {
		$settings_fields = array();
		// Declare the default settings fields.

		$cta_text_1 = __('My restaurant is already listed on Quandoo', 'quandoo-reservation');
		$cta_text_2 = __('Click here', 'quandoo-reservation');
		$cta_text_3 = __('to request your unique reservation key', 'quandoo-reservation');
		$cta_text_4 = __('I am not a Quandoo partner yet', 'quandoo-reservation');
		$cta_text_5 = __('Join Quandoo', 'quandoo-reservation');
		$cta_text_6 = __('here', 'quandoo-reservation');
		$cta_text_7 = __('to start getting online reservations!', 'quandoo-reservation');

		$cta_text = '<div class="qbook-cta-text">
		<h4>'.$cta_text_1.'</h4>
		<p><a target="_blank" href="https://sites.quandoo.com/request-reservation-key/?website='.get_site_url().'">'.$cta_text_2.'</a> '.$cta_text_3.'</p>
		<h4>-----------------------------------</h4>
		<h4>'.$cta_text_4.'</h4>
		<p>'.$cta_text_5.' <a target="_blank" href="https://b2b.quandoo.com/qualification/?source=wordpress">'.$cta_text_6.'</a> '.$cta_text_7.'</p>
		<br><hr>
		</div>';


		switch ( $section ) {
			case 'standard-fields':
				$settings_fields['bcid'] = array(
												'name' => __( 'Enter your reservation key', 'quandoo-reservation' ),
												'type' => 'text',
												'default' => '',
												'section' => 'standard-fields',
												'description' => $cta_text
											);

				$settings_fields['activate'] = array(
												'name' => __( 'Quick activate', 'quandoo-reservation' ),
												'type' => 'checkbox',
												'default' => '',
												'class' => 'qbook-checkbox',
												'section' => 'standard-fields',
												'description' => __( 'Display reservation button on all your website’s pages', 'quandoo-reservation' )
											);

				$settings_fields['advanced-settings'] = array(
												'name' => __( 'Advanced settings', 'quandoo-reservation' ),
												'type' => 'checkbox',
												'default' => 'hideble hideme',
												'class' => 'qbook-checkbox',
												'section' => 'standard-fields',
												'description' => __( 'Customise your reservation button', 'quandoo-reservation' )
											);

				$settings_fields['button-text'] = array(
												'name' => __( 'Button text', 'quandoo-reservation' ),
												'type' => 'text',
												'class' => 'hideble hideme',
												'default' => __('Book Now', 'quandoo-reservation'),
												'section' => 'standard-fields',
												'description' => __( 'This text will display on the button', 'quandoo-reservation' )
											);
				
				$settings_fields['select-position'] = array(
												'name' => __( 'Position', 'quandoo-reservation' ),
												'type' => 'radio',
												'default' => 'br',
												'class' => 'qbook-radio-button hideble hideme',
												'section' => 'standard-fields',
												'options' => array(
																'tl' => __( 'Top left', 'quandoo-reservation' ),
																'tr' => __( 'Top right', 'quandoo-reservation' ),
																'sr' => __( 'Side right', 'quandoo-reservation' ),
																'bl' => __( 'Bottom left', 'quandoo-reservation' ),
																'br' => __( 'Bottom right', 'quandoo-reservation' )
															),
												'description' => __( 'Choose the screen position of the button', 'quandoo-reservation' )
											);
				$settings_fields['select-size'] = array(
												'name' => __( 'Button size', 'quandoo-reservation' ),
												'type' => 'select',
												'default' => 'sm',
												'section' => 'standard-fields',
												'class' => 'hideble hideme',
												'options' => array(
																'sm' => __( 'Small', 'quandoo-reservation' ),
																'md' => __( 'Medium', 'quandoo-reservation' ),
																'lg' => __( 'Large', 'quandoo-reservation' )
															),
												'description' => __( 'Choose button size', 'quandoo-reservation' )
											);

				$settings_fields['select-background-color'] = array(
												'name' => __( 'Set button colour', 'quandoo-reservation' ),
												'type' => 'text',
												'class' => 'color-picker hideble hideme',
												'default' => '#f8b333',
												'section' => 'standard-fields',
												'description' => __( 'Set the background colour of the button', 'quandoo-reservation' )
											);

				$settings_fields['select-text-color'] = array(
												'name' => __( 'Button text colour', 'quandoo-reservation' ),
												'type' => 'text',
												'class' => 'color-picker hideble hideme',
												'default' => '#fff',
												'section' => 'standard-fields',
												'description' => __( 'Set the colour of the text on the button', 'quandoo-reservation' )
											);

				$settings_fields['select-calendar-color'] = array(
												'name' => __( 'Calendar Theme', 'quandoo-reservation' ),
												'type' => 'select',
												'default' => 'brand',
												'class' => 'hideble hideme',
												'section' => 'standard-fields',
												'options' => array(
																'brand' => __( 'Quandoo', 'quandoo-reservation' ),
																'dark' => __( 'Dark', 'quandoo-reservation' ),
																'light' => __( 'Light', 'quandoo-reservation' )
															),
												'description' => __( 'Set the colour scheme of the reservation calendar', 'quandoo-reservation' )
											);

				break;
			case 'special-fields':

				$settings_fields['select_taxonomy'] = array(
													'name' => __( 'Example Taxonomy Selector', 'quandoo-reservation' ),
													'type' => 'select_taxonomy',
													'default' => '',
													'section' => 'special-fields',
													'description' => __( '', 'quandoo-reservation' )
											);

				break;
			default:
				# code...
				break;
		}

		return (array)apply_filters( 'quandoo-reservation-settings-fields', $settings_fields );

		

	} // End get_settings_fields()

	protected function render_field_text ( $key, $args ) {
		$html = '<input id="' . esc_attr( $key ) . '" autocomplete="off" name="' . esc_attr( $key ) . '" size="40" type="text" value="' . esc_attr( $this->get_value( $args['id'], $args['default'], $args['section'] ) ) . '" class="' . esc_attr($args['class']) . '" />' . "\n";
		return $html;
	} // End render_field_text()

	protected function render_field_radio ( $key, $args ) {
		$html = '';
		if ( isset( $args['options'] ) && ( 0 < count( (array)$args['options'] ) ) ) {
			$html = '';
			foreach ( $args['options'] as $k => $v ) {
				$html .= '<input type="radio" name="' . esc_attr( $key ) . '" value="' . esc_attr( $k ) . '"' . checked( esc_attr( $this->get_value( $args['id'], $args['default'], $args['section'] ) ), $k, false ) . ' /> ' . esc_html( $v ) . '<br />' . "\n";
			}
		}
		return $html;
	} // End render_field_radio()

	protected function render_field_textarea ( $key, $args ) {
		// Explore how best to escape this data, as esc_textarea() strips HTML tags, it seems.
		$html = '<textarea id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" cols="42" rows="5">' . $this->get_value( $args['id'], $args['default'], $args['section'] ) . '</textarea>' . "\n";
		return $html;
	} // End render_field_textarea()

	protected function render_field_checkbox ( $key, $args ) {
		$has_description = false;
		$html = '';
		if ( isset( $args['description'] ) ) {
			$has_description = true;
			$html .= '<label for="' . esc_attr( $key ) . '">' . "\n";
		}
		$html .= '<input id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" type="checkbox" class="'.$args['class'].'" value="true"' . checked( esc_attr( $this->get_value( $args['id'], $args['default'], $args['section'] ) ), 'true', false ) . ' />' . "\n";
		if ( $has_description ) {
			$html .= wp_kses_post( $args['description'] ) . '</label>' . "\n";
		}
		return $html;
	} // End render_field_checkbox()

	protected function render_field_select ( $key, $args ) {
		$this->_has_select = true;
		

		$html = '';
		if ( isset( $args['options'] ) && ( 0 < count( (array)$args['options'] ) ) ) {
			$html .= '<select id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '">' . "\n";
				foreach ( $args['options'] as $k => $v ) {
					$html .= '<option value="' . esc_attr( $k ) . '"' . selected( esc_attr( $this->get_value( $args['id'], $args['default'], $args['section'] ) ), $k, false ) . '>' . esc_html( $v ) . '</option>' . "\n";
				}
			$html .= '</select>' . "\n";
		}
		return $html;
	} // End render_field_select()

	public function get_supported_fields () {
		return (array)apply_filters( 'quandoo-reservation-supported-fields', array( 'text', 'checkbox', 'radio', 'textarea', 'select', 'select_taxonomy' ) );
	} // End get_supported_fields()

	public function get_value ( $key, $default, $section ) {
		$values = get_option( 'quandoo-reservation-' . $section, array() );

		if ( is_array( $values ) && isset( $values[$key] ) ) {
			$response = $values[$key];
		} else {
			$response = $default;
		}

		return $response;
	} // End get_value()	


} // End Class

add_action( 'admin_enqueue_scripts', 'mw_enqueue_color_picker' );
function mw_enqueue_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_style(  'custom-styles', plugins_url('custom-styles.css', __FILE__ ) );
    wp_enqueue_script( 'my-script-handle', plugins_url('myscript.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}