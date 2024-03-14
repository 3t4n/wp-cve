<?php
/**
 * Function to return the markup to display an HTML user input element.
 *
 * @param string $type The element type.
 * @param string $label The element label.
 * @param string $id The element id.
 * @param string $name The element name.
 * @param string $value The element value.
 * @param array  $data The element data.  Eg for options in a select.
 * @param string $class The element classes.
 * @param string $style The element styles.
 * @param string $placeholder The element placeholder.
 *
 * @return string the markup for the HTML element.
 */
function quickadsense_get_control( $type, $label, $id, $name, $value = '', $data = null, $class = 'input widefat', $style = '', $placeholder = '' ) {
	$output = '';
	switch ( $type ) {
		case 'hidden':
			$output .= '<input type="text" id="' . $id . '" name="' . $name . '" value="' . $value . '" style="display: none;" />';
			break;
		case 'text':
			if ( '' !== $label ) {
				$output .= '<label for="' . $name . '">' . $label . '</label>';
			}
			$output .= '<input type="text" id="' . $id . '" name="' . $name . '" value="' . $value . '" class="multilanguage-input ' . $class . '" style="' . $style . '" placeholder="' . $placeholder . '" />';
			break;
		case 'password':
			if ( '' !== $label ) {
				$output .= '<label for="' . $name . '">' . $label . '</label>';
			}
			$output .= '<input type="password" id="' . $id . '" name="' . $name . '" value="' . $value . '" class="multilanguage-input ' . $class . '" style="' . $style . '" placeholder="' . $placeholder . '" />';
			break;
		case 'number':
			if ( '' !== $label ) {
				$output .= '<label for="' . $name . '">' . $label . '</label>';
			}
			$output .= '<input type="number" id="' . $id . '" name="' . $name . '" value="' . $value . '" class="multilanguage-input ' . $class . '" style="' . $style . '" placeholder="' . $placeholder . '" />';
			break;
		case 'checkbox':
			$output .= '<input type="checkbox" id="' . $id . '" name="' . $name . '" value="1" class="input" ' . checked( $value, 1, false ) . '  style="' . $style . '" />';
			if ( '' !== $label ) {
				$output .= '<label for="' . $name . '">' . $label . '</label>';
			}
			break;
		case 'textarea':
			if ( '' !== $label ) {
				$output .= '<label for="' . $name . '">' . $label . '</label><br />';
			}
			$output .= '<textarea id="' . $id . '" name="' . $name . '" class="multilanguage-input ' . $class . '" class="multilanguage-input ' . $class . '" style="height: 100px; ' . $style . '"  placeholder="' . $placeholder . '">' . $value . '</textarea>';
			break;
		case 'textarea-big':
			if ( '' !== $label ) {
				$output .= '<label for="' . $name . '">' . $label . '</label><br />';
			}
			$output .= '<textarea id="' . $id . '" name="' . $name . '" class="multilanguage-input ' . $class . '" class="multilanguage-input ' . $class . '" style="height: 300px; ' . $style . '"  placeholder="' . $placeholder . '">' . $value . '</textarea>';
			break;
		case 'select':
			if ( '' !== $label ) {
				$output .= '<label for="' . $name . '">' . $label . '</label>';
			}
			$output .= '<select id="' . $id . '" name="' . $name . '" class="' . $class . '" style="' . $style . '" >';
			if ( $data ) {
				foreach ( $data as $option ) {
					$metadata = '';
					if ( isset( $option['metadata'] ) && is_array( $option['metadata'] ) ) {
						foreach ( $option['metadata'] as $key => $metavalue ) {
							$metadata .= 'data-' . $key . '="' . $metavalue . '"';
						}
					}
					$output .= '<option ' . $metadata . ' value="' . $option['value'] . '" ' . selected( $value, $option['value'], false ) . '>' . $option['text'] . '</option>';
				}
			}
			$output .= '</select>';
			break;
		case 'upload':
			if ( '' !== $label ) {
				$output .= '<label for="' . $name . '">' . $label . '</label><br />';
			}
			$output .= '<input type="text" id="' . $id . '" name="' . $name . '" value="' . $value . '" class="' . $class . '" class="width: 74%;" style="' . $style . '" />';
			$output .= '<input type="button" value="Upload Image" class="quick_adsense_uploader_button" id="upload_image_button" class="width: 25%;" />';
			break;
		case 'multiselect':
			if ( '' !== $label ) {
				$output .= '<label for="' . $name . '">' . $label . '</label><br />';
			}
			$output .= '<select id="' . $id . '" name="' . $name . '" class="' . $class . '" multiple="multiple" style="height: 120px; ' . $style . '" >';
			if ( $data ) {
				foreach ( $data as $option ) {
					if ( is_array( $value ) && in_array( $option['value'], $value, true ) ) {
						$output .= '<option value="' . $option['value'] . '" selected="selected">' . $option['text'] . '</option>';
					} else {
						$output .= '<option value="' . $option['value'] . '">' . $option['text'] . '</option>';
					}
				}
			}
			$output .= '</select>';
			break;
	}
	return $output;
}

/**
 * Check for null and empty and returns the value or the default
 *
 * @param array  $data The data.
 * @param string $field_name The name of field/key for data.
 * @param string $default The default value to send if the selected data value if empty or not set.
 */
function quick_adsense_get_value( $data, $field_name, $default = '' ) {
	if ( isset( $data ) && is_array( $data ) && isset( $data[ $field_name ] ) && ( '' !== $data[ $field_name ] ) ) {
		return $data[ $field_name ];
	}
	return $default;
}

/**
 * Allowed HTML attributes and tags for the wp_kses function.
 */
function quick_adsense_get_allowed_html() {
	$common_html_attributes = [
		'id'             => [],
		'name'           => [],
		'class'          => [],
		'for'            => [],
		'href'           => [],
		'target'         => [],
		'rel'            => [],
		'title'          => [],
		'datetime'       => [],
		'style'          => [],
		'alt'            => [],
		'height'         => [],
		'src'            => [],
		'srcset'         => [],
		'width'          => [],
		'type'           => [],
		'value'          => [],
		'checked'        => [],
		'selected'       => [],
		'multiple'       => [],
		'data-index'     => [],
		'onclick'        => [],
		'async'          => [],
		'crossorigin'    => [],
		'action'         => [],
		'method'         => [],
		'content'        => [],
		'property'       => [],
		'data-ad-client' => [],
		'data-ad-slot'   => [],
	];
	$common_html_tags       = [
		'a'          => $common_html_attributes,
		'abbr'       => $common_html_attributes,
		'b'          => $common_html_attributes,
		'br'         => $common_html_attributes,
		'blockquote' => $common_html_attributes,
		'cite'       => $common_html_attributes,
		'code'       => $common_html_attributes,
		'del'        => $common_html_attributes,
		'dd'         => $common_html_attributes,
		'div'        => $common_html_attributes,
		'dl'         => $common_html_attributes,
		'dt'         => $common_html_attributes,
		'em'         => $common_html_attributes,
		'form'       => $common_html_attributes,
		'h1'         => $common_html_attributes,
		'h2'         => $common_html_attributes,
		'h3'         => $common_html_attributes,
		'h4'         => $common_html_attributes,
		'h5'         => $common_html_attributes,
		'h6'         => $common_html_attributes,
		'hr'         => $common_html_attributes,
		'i'          => $common_html_attributes,
		'img'        => $common_html_attributes,
		'ins'        => $common_html_attributes,
		'label'      => $common_html_attributes,
		'link'       => $common_html_attributes,
		'li'         => $common_html_attributes,
		'meta'       => $common_html_attributes,
		'ol'         => $common_html_attributes,
		'ul'         => $common_html_attributes,
		'p'          => $common_html_attributes,
		'q'          => $common_html_attributes,
		'span'       => $common_html_attributes,
		'strike'     => $common_html_attributes,
		'strong'     => $common_html_attributes,
		'script'     => $common_html_attributes,
		'noscript'   => $common_html_attributes,
		'style'      => $common_html_attributes,
		'input'      => $common_html_attributes,
		'textarea'   => $common_html_attributes,
		'title'      => $common_html_attributes,
		'select'     => $common_html_attributes,
		'option'     => $common_html_attributes,
	];
	return $common_html_tags;
}
