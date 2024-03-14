<?php
namespace LightGallery;

class SmartlogixControlsWrapper {
	/**
	 * Function to return the markup to display an HTML user input element.
	 *
	 * @param string $type The element type.
	 * @param string $label The element label.
	 * @param string $id The element id.
	 * @param string $name The element name.
	 * @param string $value The element value.
	 * @param array  $data The element data.  Eg for options in a select.
	 * @param string $info The element help text.
	 * @param string $style The element classes.
	 *
	 * @return string the markup for the HTML element.
	 */
	public static function get_control( $type, $label, $id, $name, $value = '', $data = null, $info = '', $style = 'input widefat' ) {
		if ( 'html' === $type ) {
			return $data;
		} else {
			$output = '<p class="control">';
			switch ( $type ) {
				case 'text':
					if ( '' !== $label ) {
						$output .= '<label for="' . esc_attr( $name ) . '">' . wp_kses( $label, self::get_allowed_html() ) . '</label>';
					}
					$output .= '<input type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="multilanguage-input ' . esc_attr( $style ) . '">';
					break;
				case 'number':
					if ( '' !== $label ) {
						$output .= '<label for="' . esc_attr( $name ) . '">' . wp_kses( $label, self::get_allowed_html() ) . '</label>';
					}
					$output .= '<input type="number" min="0" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="multilanguage-input ' . esc_attr( $style ) . '">';
					break;
				case 'number-placeholder':
					if ( '' !== $label ) {
						$output .= '<label for="' . esc_attr( $name ) . '">' . wp_kses( $label, self::get_allowed_html() ) . '</label>';
					}
					$output .= '<input placeholder="Optional" type="number" min="0" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="multilanguage-input ' . esc_attr( $style ) . '">';
					break;
				case 'checkbox':
					$output .= '<input type="checkbox" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="1" class="input" ' . checked( $value, 1, false ) . ' />';
					if ( '' !== $label ) {
						$output .= '<label for="' . esc_attr( $name ) . '">' . wp_kses( $label, self::get_allowed_html() ) . '</label>';
					}
					break;
				case 'textarea':
					if ( '' !== $label ) {
						$output .= '<label for="' . esc_attr( $name ) . '">' . wp_kses( $label, self::get_allowed_html() ) . '</label>';
					}
					$output .= '<textarea id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" class="multilanguage-input ' . esc_attr( $style ) . '" style="height: 100px;">' . wp_kses( $value, self::get_allowed_html() ) . '</textarea>';
					break;
				case 'textarea-big':
					if ( '' !== $label ) {
						$output .= '<label for="' . esc_attr( $name ) . '">' . wp_kses( $label, self::get_allowed_html() ) . '</label>';
					}
					$output .= '<textarea id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" class="multilanguage-input ' . esc_attr( $style ) . '" style="height: 300px;">' . wp_kses( $value, self::get_allowed_html() ) . '</textarea>';
					break;
				case 'select':
					if ( '' !== $label ) {
						$output .= '<label for="' . esc_attr( $name ) . '">' . wp_kses( $label, self::get_allowed_html() ) . '</label>';
					}
					$output .= '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" class="' . esc_attr( $style ) . '">';
					if ( $data ) {
						foreach ( $data as $option ) {
							$output .= '<option ' . ( ( isset( $option['parent'] ) ) ? 'data-parent="' . esc_attr( $option['parent'] ) . '"' : '' ) . ' value="' . esc_attr( $option['value'] ) . '" ' . selected( $value, $option['value'], false ) . '>' . $option['text'] . '</option>';
						}
					}
					$output .= '</select>';
					break;
				case 'toggle':
					if ( '' !== $label ) {
						$output .= '<label for="' . esc_attr( $name ) . '">' . wp_kses( $label, self::get_allowed_html() ) . '</label>';
					}
					$is_active = true;
					if ( isset( $value ) ) {
						if ( 'false' === $value ) {
							$is_active = false;
						}
					}
					$output     .= '<span class="toggle-control-wrapper">';
						$output .= '<span class="toggle-control' . ( ( $is_active ) ? ' active' : '' ) . '"></span>';
						$output .= '<input type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" class="' . esc_attr( $style ) . ' toggle-control-input" value="' . ( ( isset( $value ) && ( '' !== $value ) ) ? $value : 'true' ) . '">';
					$output     .= '</span>';
					break;
				case 'toggle-reverse':
					if ( '' !== $label ) {
						$output .= '<label for="' . esc_attr( $name ) . '">' . wp_kses( $label, self::get_allowed_html() ) . '</label>';
					}
					$is_active = false;
					if ( isset( $value ) ) {
						if ( 'true' === $value ) {
							$is_active = true;
						}
					}
					$output     .= '<span class="toggle-control-wrapper">';
						$output .= '<span class="toggle-control' . ( ( $is_active ) ? ' active' : '' ) . '"></span>';
						$output .= '<input type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" class="' . esc_attr( $style ) . ' toggle-control-input" value="' . ( ( isset( $value ) && ( '' !== $value ) ) ? $value : 'false' ) . '">';
					$output     .= '</span>';
					break;
				case 'upload_array':
					if ( '' !== $label ) {
						$output .= '<label for="' . esc_attr( $name ) . '">' . wp_kses( $label, self::get_allowed_html() ) . '</label><br />';
					}
					if ( isset( $data ) && isset( $value[ $data ] ) && ( '' !== $value[ $data ] ) ) {
						$image   = wp_get_attachment_image_src( $value[ $data ], 'full' );
						$output .= '<a href="#" class="smartlogix_uploader_button"><img src="' . $image[0] . '" style="max-height: 360px;margin: 10px 0;border: 1px solid #000;box-shadow: 1px 1px 5px #ddd;display: block;" /></a>';
						$output .= '<a href="#" class="smartlogix_uploader_remove_button button">Remove Image</a>';
						$output .= '<input type="hidden" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '[]" value="' . $value[ $data ] . '" />';
					} else {
						$output .= '<a href="#" class="smartlogix_uploader_button button">Upload image</a>';
						$output .= '<a href="#" class="smartlogix_uploader_remove_button button" style="display:none">Remove Image</a>';
						$output .= '<input type="hidden" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '[]" value="" />';
					}
					$output .= '<span class="clear"></span>';
					break;
				case 'upload':
					if ( '' !== $label ) {
						$output .= '<label for="' . esc_attr( $name ) . '">' . wp_kses( $label, self::get_allowed_html() ) . '</label><br />';
					}
					if ( '' !== $value ) {
						$image   = wp_get_attachment_image_src( $value, 'full' );
						$output .= '<a href="#" class="smartlogix_uploader_button"><img src="' . $image[0] . '" style="max-height: 360px;margin: 10px 0;border: 1px solid #000;box-shadow: 1px 1px 5px #ddd;display: block;" /></a>';
						$output .= '<a href="#" class="smartlogix_uploader_remove_button button">Remove Image</a>';
						$output .= '<input type="hidden" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" />';
					} else {
						$output .= '<a href="#" class="smartlogix_uploader_button button">Upload image</a>';
						$output .= '<a href="#" class="smartlogix_uploader_remove_button button" style="display:none">Remove Image</a>';
						$output .= '<input type="hidden" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="" />';
					}
					$output .= '<span class="clear"></span>';
					break;
				case 'multiselect':
					if ( '' !== $label ) {
						$output .= '<label for="' . esc_attr( $name ) . '">' . wp_kses( $label, self::get_allowed_html() ) . '</label><br />';
					}
					$output .= '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '[]" class="' . esc_attr( $style ) . '" multiple="multiple" style="height: 220px">';
					if ( $data ) {
						foreach ( $data as $option ) {
							if ( is_array( $value ) && in_array( $option['value'], $value, true ) ) {
								$output .= '<option value="' . esc_attr( $option['value'] ) . '" selected="selected">' . $option['text'] . '</option>';
							} else {
								$output .= '<option value="' . esc_attr( $option['value'] ) . '">' . esc_attr( $option['text'] ) . '</option>';
							}
						}
					}
					$output .= '</select>';
					break;
				default:
					if ( '' !== $label ) {
						$output .= '<label for="' . esc_attr( $name ) . '">' . wp_kses( $label, self::get_allowed_html() ) . '</label>';
					}
					$output .= '<input type="' . $type . '" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="' . esc_attr( $style ) . '">';
					break;
			}
			if ( '' !== $info ) {
				$output .= '<span class="settings-info">' . esc_html( $info ) . '</span>';
			}
			$output .= '</p>';
		}
		return $output;
	}

	/**
	 * The scripts for the upload element.
	 */
	public static function get_controls_js() {
		$output  = '<script type="text/javascript">' . PHP_EOL;
		$output .= 'jQuery(document).ready(function() {' . PHP_EOL;
		$output .= 'jQuery("body").on("click", ".smartlogix_uploader_button", function(e) {' . PHP_EOL;
		$output .= 'e.preventDefault();' . PHP_EOL;
		$output .= 'var button = jQuery(this),' . PHP_EOL;
		$output .= 'custom_uploader = wp.media({' . PHP_EOL;
		$output .= 'title: "Select / Upload Image",' . PHP_EOL;
		$output .= 'library : {' . PHP_EOL;
		$output .= 'uploadedTo : wp.media.view.settings.post.id,' . PHP_EOL;
		$output .= 'type : "image"' . PHP_EOL;
		$output .= '},' . PHP_EOL;
		$output .= 'button: {' . PHP_EOL;
		$output .= 'text: "Use this image"' . PHP_EOL;
		$output .= '},' . PHP_EOL;
		$output .= 'multiple: false' . PHP_EOL;
		$output .= '}).on("select", function() {' . PHP_EOL;
		$output .= 'var attachment = custom_uploader.state().get("selection").first().toJSON();' . PHP_EOL;
		$output .= 'button.html("<img src=\'"+attachment.url+"\' style=\'max-height: 360px;margin: 10px 0;border: 1px solid #000;box-shadow: 1px 1px 5px #ddd;display: block;\'>").removeClass("button");' . PHP_EOL;
		$output .= 'button.next().show();' . PHP_EOL;
		$output .= 'button.next().next().val(attachment.id);' . PHP_EOL;
		$output .= '}).open();' . PHP_EOL;
		$output .= '});' . PHP_EOL;
		$output .= 'jQuery("body").on("click", ".smartlogix_uploader_remove_button", function(e) {' . PHP_EOL;
		$output .= 'e.preventDefault();' . PHP_EOL;
		$output .= 'var button = jQuery(this);' . PHP_EOL;
		$output .= 'button.next().val("");' . PHP_EOL;
		$output .= 'button.hide().prev().html("Upload image").addClass("button");' . PHP_EOL;
		$output .= '});' . PHP_EOL;
		$output .= '});' . PHP_EOL;
		$output .= '</script>' . PHP_EOL;

		return $output;
	}

	/**
	 * Check for null and empty and returns the value or the default
	 *
	 * @param array  $data The data.
	 * @param string $field_name The name of field/key for data.
	 * @param string $default The default value to send if the selected data value if empty or not set.
	 */
	public static function get_value( $data, $field_name, $default = '' ) {
		if ( isset( $data ) && is_array( $data ) && isset( $data[ $field_name ] ) && ( '' !== $data[ $field_name ] ) ) {
			return $data[ $field_name ];
		}
		return $default;
	}

	/**
	 * Allowed HTML attributes and tags for the wp_kses function.
	 */
	public static function get_allowed_html() {
		$common_html_attributes = [
			'id'       => [],
			'name'     => [],
			'class'    => [],
			'for'      => [],
			'href'     => [],
			'rel'      => [],
			'title'    => [],
			'datetime' => [],
			'style'    => [],
			'alt'      => [],
			'height'   => [],
			'src'      => [],
			'width'    => [],
			'type'     => [],
			'value'    => [],
			'selected' => [],
			'multiple' => [],
		];
		return [
			'a'          => $common_html_attributes,
			'abbr'       => $common_html_attributes,
			'b'          => $common_html_attributes,
			'blockquote' => $common_html_attributes,
			'cite'       => $common_html_attributes,
			'code'       => $common_html_attributes,
			'del'        => $common_html_attributes,
			'dd'         => $common_html_attributes,
			'div'        => $common_html_attributes,
			'dl'         => $common_html_attributes,
			'dt'         => $common_html_attributes,
			'em'         => $common_html_attributes,
			'h1'         => $common_html_attributes,
			'h2'         => $common_html_attributes,
			'h3'         => $common_html_attributes,
			'h4'         => $common_html_attributes,
			'h5'         => $common_html_attributes,
			'h6'         => $common_html_attributes,
			'i'          => $common_html_attributes,
			'img'        => $common_html_attributes,
			'label'      => $common_html_attributes,
			'li'         => $common_html_attributes,
			'ol'         => $common_html_attributes,
			'ul'         => $common_html_attributes,
			'p'          => $common_html_attributes,
			'q'          => $common_html_attributes,
			'span'       => $common_html_attributes,
			'strike'     => $common_html_attributes,
			'strong'     => $common_html_attributes,
			'input'      => $common_html_attributes,
			'textarea'   => $common_html_attributes,
			'select'     => $common_html_attributes,
			'option'     => $common_html_attributes,
		];
	}
}

