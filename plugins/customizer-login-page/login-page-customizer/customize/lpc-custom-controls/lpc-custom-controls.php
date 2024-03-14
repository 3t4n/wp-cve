<?php
/**
 * Custom Controls.
 *
 * @package customizer login page
 */

// Include the WP_Customize_Control class.
// require_once ABSPATH . WPINC . '/class-wp-customize-control.php';
/**
 * Custom Control Base Class
 */
class Lpc_Custom_Control extends WP_Customize_Control {
	public function get_Lpc_resource_url() {
		if ( strpos( wp_normalize_path( __DIR__ ), wp_normalize_path( WP_PLUGIN_DIR ) ) === 0 ) {
			// We're in a plugin directory and need to determine the url accordingly.
			return plugin_dir_url( __DIR__ );
		}

		return trailingslashit( get_template_directory_uri() );
	}
}

/**
 * Switch sanitization
 *
 * @param  string       Switch value
 * @return integer  Sanitized value
 */
if ( ! function_exists( 'lpc_switch_sanitization' ) ) {
	function lpc_switch_sanitization( $input ) {
		// if ( 1 === $input ) {
		// return 1;
		// } else {
		// return 0;
		// }
		return boolval( $input );
	}
}
/**
	 * Integer sanitization
	 *
	 * @param  string       Input value to check
	 * @return integer  Returned integer value
	 */
if ( ! function_exists( 'lpc_sanitize_integer' ) ) {
	function lpc_sanitize_integer( $input ) {
		return (int) $input;
	}
}

/**
 * Alpha Color (Hex, RGB & RGBa) sanitization
 *
 * @param  string   Input to be sanitized
 * @return string   Sanitized input
 */
if ( ! function_exists( 'lpc_hex_rgba_sanitization' ) ) {
	function lpc_hex_rgba_sanitization( $input, $setting ) {
		if ( empty( $input ) || is_array( $input ) ) {
			return $setting->default;
		}

		if ( false === strpos( $input, 'rgb' ) ) {
			// If string doesn't start with 'rgb' then santize as hex color
			$input = sanitize_hex_color( $input );
		} else {
			if ( false === strpos( $input, 'rgba' ) ) {
				// Sanitize as RGB color
				$input = str_replace( ' ', '', $input );
				sscanf( $input, 'rgb(%d,%d,%d)', $red, $green, $blue );
				$input = 'rgb(' . lpc_in_range( $red, 0, 255 ) . ',' . lpc_in_range( $green, 0, 255 ) . ',' . lpc_in_range( $blue, 0, 255 ) . ')';
			} else {
				// Sanitize as RGBa color
				$input = str_replace( ' ', '', $input );
				sscanf( $input, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
				$input = 'rgba(' . lpc_in_range( $red, 0, 255 ) . ',' . lpc_in_range( $green, 0, 255 ) . ',' . lpc_in_range( $blue, 0, 255 ) . ',' . lpc_in_range( $alpha, 0, 1 ) . ')';
			}
		}
		return $input;
	}
}

/**
	 * Only allow values between a certain minimum & maxmium range
	 *
	 * @param  number   Input to be sanitized
	 * @return number   Sanitized input
	 */
if ( ! function_exists( 'lpc_in_range' ) ) {
	function lpc_in_range( $input, $min, $max ) {
		if ( $input < $min ) {
			$input = $min;
		}
		if ( $input > $max ) {
			$input = $max;
		}
		return $input;
	}
}

/**
	 * Radio Button and Select sanitization
	 *
	 * @param  string   Radio Button value.
	 * @return integer  Sanitized value.
	 */
if ( ! function_exists( 'lpc_radio_sanitization' ) ) {
	function lpc_radio_sanitization( $input, $setting ) {
		// get the list of possible radio box or select options.
		$choices = $setting->manager->get_control( $setting->id )->choices;

		if ( array_key_exists( $input, $choices ) ) {
			return $input;
		} else {
			return $setting->default;
		}
	}
}
/**
	 * Only allow values between a certain minimum & maxmium range
	 *
	 * @param  number   Input to be sanitized
	 * @return number   Sanitized input
	 */
if ( ! function_exists( 'lpc_sanitize_custom_error' ) ) {
	function lpc_sanitize_custom_error( $input ) {
		// Define allowed HTML tags.
		$allowed_html = array(
			'strong' => array(),
			'a'      => array(
				'href'   => array(),
				'title'  => array(),
				'rel'    => array(),
				'target' => array(),
			),
		);
		return wp_kses( $input, $allowed_html );
	}
}
/**
 * Sanitize Error Msg.
 */
if ( ! function_exists( 'lpc_sanitize_custom_error' ) ) {
	function lpc_sanitize_custom_error( $input ) {
		// Define allowed HTML tags.
		$allowed_html = array(
			'strong' => array(),
		);
		return wp_kses( $input, $allowed_html );
	}
}

/**
 * Simple Notice Custom Control
 */
class Lpc_Simple_Notice_Custom_Control extends Lpc_Custom_Control {
	/**
	 * The type of control being rendered
	 */
	public $type = 'simple_notice';
	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		$allowed_html = array(
			'a'      => array(
				'href'   => array(),
				'title'  => array(),
				'class'  => array(),
				'target' => array(),
			),
			'br'     => array(),
			'em'     => array(),
			'strong' => array(),
			'i'      => array(
				'class' => array(),
			),
			'span'   => array(
				'class' => array(),
			),
			'code'   => array(),
		);
		?>
			<div class="simple-notice-custom-control">
		<?php if ( ! empty( $this->label ) ) { ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php } ?>
			<?php if ( ! empty( $this->description ) ) { ?>
					<span class="customize-control-description"><?php echo wp_kses( $this->description, $allowed_html ); ?></span>
				<?php } ?>
			</div>
			<?php
	}
}

/**
 * Toggle Switch Custom Control
 */
class Lpc_Toggle_Switch_Custom_Control extends Lpc_Custom_Control {
	/**
	 * The type of control being rendered
	 */
	public $type = 'toggle_switch';
	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		?>
		<div class="toggle-switch-control">
			<div class="toggle-switch">
				<input type="checkbox" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" class="toggle-switch-checkbox" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> <?php checked( $this->value() ); ?>>
				<label class="toggle-switch-label" for="<?php echo esc_attr( $this->id ); ?>">
					<span class="toggle-switch-inner"></span>
					<span class="toggle-switch-switch"></span>
				</label>
			</div>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php if ( ! empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php } ?>
		</div>
		<script>
			jQuery( document ).ready( function( $ ) {
				$( '#<?php echo esc_js( $this->id ); ?>' ).on( 'keypress', function( e ) {
					if ( e.keyCode === 13 ) { // 13 represents the Enter key
						e.preventDefault(); // Prevent form submission
					}
				} );
			} );
		</script>
		<?php
	}
}

/**
 * Slider Custom Control Range.
 */
class Lpc_Slider_Custom_Control extends Lpc_Custom_Control {
	/**
	 * The type of control being rendered
	 */
	public $type = 'slider_control';
	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		$reset_value = $this->setting->default;
		?>
		<div class="slider-custom-control">
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<input type="number" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" class="customize-control-slider-value" <?php $this->link(); ?> />
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php endif; ?>
			<div class="slider" slider-min-value="<?php echo esc_attr( $this->input_attrs['min'] ); ?>" slider-max-value="<?php echo esc_attr( $this->input_attrs['max'] ); ?>" slider-step-value="<?php echo esc_attr( $this->input_attrs['step'] ); ?>"></div>
			<span class="slider-reset dashicons dashicons-image-rotate" slider-reset-value="<?php echo esc_attr( $reset_value ); ?>"></span>
		</div>
		<?php
	}
}

/**
 * Alpha Color Picker Customizer Control
 *
 * This control adds a second slider for opacity to the stock WordPress color picker,
 * and it includes logic to seamlessly convert between RGBa and Hex color values as
 * opacity is added to or removed from a color.
 *
 * This Alpha Color Picker is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this Alpha Color Picker. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package customizer-controls
 */

define( 'LPC_ALPHA_VERSION', '1.0.0' );

/**
 * Class Hestia_Customize_Alpha_Color_Control
 */
class LPC_Alpha_Color_Control extends Lpc_Custom_Control {
	/**
	 * Official control name.
	 *
	 * @var string
	 */
	public $type = 'alpha-color';
	/**
	 * Add support for palettes to be passed in.
	 *
	 * Supported palette values are true, false, or an array of RGBa and Hex colors.
	 *
	 * @var bool
	 */
	public $palette;
	/**
	 * Add support for showing the opacity value on the slider handle.
	 *
	 * @var array
	 */
	public $show_opacity;
	/**
	 * Enqueue scripts and styles.
	 *
	 * Ideally these would get registered and given proper paths before this control object
	 * gets initialized, then we could simply enqueue them here, but for completeness as a
	 * stand alone class we'll register and enqueue them here.
	 */
	public function enqueue() {
		wp_enqueue_script(
			'customizer-alpha-color-picker',
			LOGINPC_PLUGIN_URL . '/customize/customizer-alpha-color-picker/alpha-color-picker.js',
			array( 'jquery', 'wp-color-picker' ),
			LPC_ALPHA_VERSION,
			true
		);
		wp_enqueue_style(
			'customizer-alpha-color-picker',
			LOGINPC_PLUGIN_URL . '/customize/customizer-alpha-color-picker/alpha-color-picker.css',
			array( 'wp-color-picker' ),
			LPC_ALPHA_VERSION
		);
	}
	/**
	 * Render the control.
	 */
	public function render_content() {
		// Process the palette
		if ( is_array( $this->palette ) ) {
			$palette = implode( '|', $this->palette );
		} else {
			// Default to true.
			$palette = ( false === $this->palette || 'false' === $this->palette ) ? 'false' : 'true';
		}
		// Support passing show_opacity as string or boolean. Default to true.
		$show_opacity = ( false === $this->show_opacity || 'false' === $this->show_opacity ) ? 'false' : 'true';
		// Begin the output.
		?>
		<label>
			<?php
			// Output the label and description if they were passed in.
			if ( isset( $this->label ) && '' !== $this->label ) {
				echo '<span class="customize-control-title">' . sanitize_text_field( $this->label ) . '</span>';
			}
			if ( isset( $this->description ) && '' !== $this->description ) {
				echo '<span class="description customize-control-description">' . sanitize_text_field( $this->description ) . '</span>';
			}
			?>
		</label>
		<input class="alpha-color-control" type="text" data-show-opacity="<?php echo esc_attr( $show_opacity ); ?>" data-palette="<?php echo esc_attr( $palette ); ?>" data-default-color="<?php echo esc_attr( $this->settings['default']->default ); ?>" <?php esc_attr( $this->link() ); ?>  />
		<?php
	}
}

/**
 * Text Area Custom Control
 */
class Lpc_Custom_Textarea_Control extends Lpc_Custom_Control {
	public $type = 'textarea';

	public function render_content() {
		$placeholder = '';
		$rows        = 3; // Default number of rows
		if ( isset( $this->input_attrs['placeholder'] ) ) {
			$placeholder = $this->input_attrs['placeholder'];
		}
		if ( isset( $this->input_attrs['rows'] ) && is_numeric( $this->input_attrs['rows'] ) ) {
			$rows = $this->input_attrs['rows'];
		}
		?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>
			<textarea rows="<?php echo esc_attr( $rows ); ?>" style="width:100%;" placeholder="<?php echo esc_attr( $placeholder ); ?>" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
		</label>
		<?php
	}
}

/**
 * Image Radio Button Custom Control
 */
class Lpc_Image_Radio_Button_Custom_Control extends Lpc_Custom_Control {
	/**
	 * The type of control being rendered
	 */
	public $type = 'image_radio_button';
	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		?>
		<div class="image_radio_button_control">
			<?php if ( ! empty( $this->label ) ) { ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php } ?>
			<?php if ( ! empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php } ?>

			<div class="grid-container">
				<?php foreach ( $this->choices as $key => $value ) { ?>
					<label class="radio-button-label" id="<?php echo esc_attr( $key ); ?>">
						<input type="radio" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $key ); ?>" <?php $this->link(); ?> <?php checked( esc_attr( $key ), $this->value() ); ?>/>
						<img src="<?php echo esc_attr( $value['image'] ); ?>" alt="<?php echo esc_attr( $value['name'] ); ?>" title="<?php echo esc_attr( $value['name'] ); ?>" />
						<span><?php echo esc_html( $value['name'] ); ?></span>
						<a href="https://awplife.com/wordpress-plugins/customizer-login-page-premium/" class="lpc-pro-link"></a>
					</label>
				<?php } ?>
			</div>
		</div>
		<?php
	}

}
/**
 * Custom Title Control
 */
class LPC_Custom_Title_Text_Control extends Lpc_Custom_Control {
	public $type = 'lpc_custom_title_text';

	public function render_content() {
		$image_url = LOGINPC_PLUGIN_URL . 'assets/images/customizer/lpc_page_title.png';
		?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<div class="lpc-image-preview">
				<img src="<?php echo esc_url( $image_url ); ?>" alt="Logo Image" style="max-width: 100%; height: auto;"/>
			</div>
			<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<input type="text" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?>/>
		</label>
		<?php
	}
}
/**
 * Text Radio Button Custom Control
 */
class Lpc_Text_Radio_Button_Custom_Control extends Lpc_Custom_Control {
	/**
	 * The type of control being rendered
	 */
	public $type = 'text_radio_button';

	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		?>
		<div class="text_radio_button_control">
			<?php if ( ! empty( $this->label ) ) { ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php } ?>
			<?php if ( ! empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php } ?>

			<div class="radio-buttons">
				<?php foreach ( $this->choices as $key => $value ) { ?>
					<label class="radio-button-label">
						<input type="radio" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $key ); ?>" <?php $this->link(); ?> <?php checked( esc_attr( $key ), $this->value() ); ?>/>
						<span><?php echo esc_html( $value ); ?></span>
					</label>
				<?php	} ?>
			</div>
		</div>
		<?php
	}
}
/**
 * Import/Export Custom Control
 */
final class LPC_Export_Import_Control extends Lpc_Custom_Control {
	/**
	 * Render the control in the customizer
	 */
	protected function render_content() {
		?>
		<span class="customize-control-title">
			<?php esc_html_e( 'Export', 'customizer-login-page' ); ?>
		</span>
		<span class="description customize-control-description">
			<?php esc_html_e( 'Click the button below to export the customizer login page  settings for this site.', 'customizer-login-page' ); ?>
		</span>
		<input type="button" class="button lpc-export-button" name="lpc-export-button" value="<?php esc_attr_e( 'Export', 'customizer-login-page' ); ?>" />

		<hr />

		<span class="customize-control-title">
			<?php esc_html_e( 'Import', 'customizer-login-page' ); ?>
		</span>
		<span class="description customize-control-description">
			<?php esc_html_e( 'Upload a file to import customizer login page settings for this site.', 'customizer-login-page' ); ?>
		</span>
		<div class="lpc-import-controls">
			<input type="file" name="lpc-import-file" class="lpc-import-file" />
			<?php wp_nonce_field( 'lpc-importing', 'lpc-import' ); ?>
		</div>
		<div class="lpc-uploading"><?php esc_html_e( 'Uploading...', 'customizer-login-page' ); ?></div>
		<input type="button" class="button lpc-import-button" name="lpc-import-button" value="<?php esc_attr_e( 'Import', 'customizer-login-page' ); ?>" onclick="return confirmImport();" />

		<script type="text/javascript">
			function confirmImport() {
				return confirm("All non-published changes will be lost, and respective customizer login page settings will be overwritten. Continue?");
			}
		</script>
		<?php
	}
}
