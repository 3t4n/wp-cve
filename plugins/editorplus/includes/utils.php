<?php

/**
 * Main file for plugin utilities
 *
 * @package EditorPlus
 */

/**
 * Will minify the given css
 *
 * @param string $input css.
 * @return string Minified css
 */
function editor_plus_minify_css($input) {

	if (trim($input) === "") return $input;
	return preg_replace(
		array(
			// Remove comment(s)
			'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
			// Remove unused white-space(s)
			'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~]|\s(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
			// Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
			'#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
			// Replace `:0 0 0 0` with `:0`
			'#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
			// Replace `background-position:0` with `background-position:0 0`
			'#(background-position):0(?=[;\}])#si',
			// Replace `0.6` with `.6`, but only when preceded by `:`, `,`, `-` or a white-space
			'#(?<=[\s:,\-])0+\.(\d+)#s',
			// Minify string value
			'#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
			'#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
			// Minify HEX color code
			'#(?<=[\s:,\-]\#)([a-f0-9]{3}|[a-f0-9]{6})\\b#i',
			// Replace `(border|outline):none` with `(border|outline):0`
			'#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
			// Remove empty selector(s)
			'#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
		),
		array(
			'$1',
			'$1$2$3$4$5$6$7',
			'$1',
			':0',
			'$1:0 0',
			'.$1',
			'$1$3',
			'$1$2$4$5',
			'$1$2$3',
			'$1:0',
			'$1$2'
		),
		$input
	);
}

/**
 * Checks if the current page has gutenberg editor.
 *
 * @return bool - true if editor exists, otherwise false.
 */
function editorplus_is_gutenberg_page() {

	// available via gutenberg plugin.
	if (function_exists('is_gutenberg_page') && is_gutenberg_page()) {
		return true;
	}

	// available since WP 5.0.
	global $current_screen;


	if (isset($current_screen) && method_exists($current_screen, 'is_block_editor') && $current_screen->is_block_editor() && property_exists($current_screen, "id") && $current_screen->id !== "site-editor") {

		return true;
	}

	return false;
}

/**
 * Will convert range to style css
 *
 * @param string $css_property - CSS Property that is used for this range.
 * @param array  $range - Range value.
 * @return string $style css.
 */
function editorplus_range_convert($css_property, $range, $css_value = false) {

	if (!is_array($range)) {
		return '';
	}

	if (!array_key_exists('value', $range) || !array_key_exists('important', $range) || !array_key_exists('unit', $range)) {
		return '';
	}

	if (empty($range['value']) && $range['value'] !== 0) {
		return '';
	}

	$is_important = $range['important'] ? ' !important' : '';
	$unit = is_array($range['unit']) ? $range['unit'][0] : $range['unit'];
	$css_style    = $range['value'] . $unit;

	if (is_string($css_value)) {
		$css_style = sprintf($css_value, $css_style);
	}

	return sprintf('%1$s:%2$s%3$s;', $css_property, $css_style, $is_important);
}

/**
 * Will convert editorplus dimensions into actual css
 *
 * @param string $css_property 	- CSS Property that is used for this dimension.
 * @param array  $dimension 	- Dimension value.
 * @return string $style css.
 */
function editorplus_dimension_convert($css_property, $dimension) {

	$unit         = $dimension['unit'] ?? 'px';
	$is_important = $dimension['important'] ?? false;

	$has_top_dimension_val    = isset($dimension['value']['top']) && '' !== $dimension['value']['top'];
	$has_right_dimension_val  = isset($dimension['value']['right']) && '' !== $dimension['value']['right'];
	$has_bottom_dimension_val = isset($dimension['value']['bottom']) && '' !== $dimension['value']['bottom'];
	$has_left_dimension_val   = isset($dimension['value']['left']) && '' !== $dimension['value']['left'];

	if (!$has_top_dimension_val && !$has_right_dimension_val && !$has_bottom_dimension_val && !$has_left_dimension_val) {
		return '';
	}

	$is_position_property = 'position' === $css_property;

	$top_dimension    = $has_top_dimension_val ? $dimension['value']['top'] : 0;
	$right_dimension  = $has_right_dimension_val ? $dimension['value']['right'] : 0;
	$bottom_dimension = $has_bottom_dimension_val ? $dimension['value']['bottom'] : 0;
	$left_dimension   = $has_left_dimension_val ? $dimension['value']['left'] : 0;

	// merging units in dimensions.
	if ('auto' !== $top_dimension) {
		$top_dimension .= $unit;
	}

	if ('auto' !== $right_dimension) {
		$right_dimension .= $unit;
	}

	if ('auto' !== $bottom_dimension) {
		$bottom_dimension .= $unit;
	}

	if ('auto' !== $left_dimension) {
		$left_dimension .= $unit;
	}

	$final_dimensions = array();
	$important        = $is_important ? ' !important' : '';

	if ($has_top_dimension_val && false === $is_position_property) {
		$final_dimensions[] = sprintf('%1$s-top:%2$s%3$s;', $css_property, $top_dimension, $important);
	} else if ($has_top_dimension_val && $is_position_property) {
		$final_dimensions[] = sprintf('top:%2$s%3$s;', $css_property, $top_dimension, $important);
	}

	if ($has_right_dimension_val && false === $is_position_property) {
		$final_dimensions[] = sprintf('%1$s-right:%2$s%3$s;', $css_property, $right_dimension, $important);
	} else if ($has_right_dimension_val && $is_position_property) {
		$final_dimensions[] = sprintf('right:%2$s%3$s;', $css_property, $right_dimension, $important);
	}

	if ($has_bottom_dimension_val && false === $is_position_property) {
		$final_dimensions[] = sprintf('%1$s-bottom:%2$s%3$s;', $css_property, $bottom_dimension, $important);
	} else if ($has_bottom_dimension_val && $is_position_property) {
		$final_dimensions[] = sprintf('bottom:%2$s%3$s;', $css_property, $bottom_dimension, $important);
	}

	if ($has_left_dimension_val && false === $is_position_property) {
		$final_dimensions[] = sprintf('%1$s-left:%2$s%3$s;', $css_property, $left_dimension, $important);
	} else if ($has_left_dimension_val && $is_position_property) {
		$final_dimensions[] = sprintf('left:%2$s%3$s;', $css_property, $left_dimension, $important);
	}


	return join('', $final_dimensions);
}

/**
 * Usefull to convert position values to percentage values.
 *
 * @param int $value - Value to convert.
 * @return string - Percentage value, with percentage unti appended
 */
function editorplus_convert_position_to_percentage($value) {

	// Checking if the given string can be converted into an integer.
	if (ctype_digit($value)) {
		$value = (float) $value;
	}

	return ($value * 100) . '%';
}
/**
 * Will convert editorplus Text shadow attribute into actual css styles.
 *
 * @param array $attribute - Text shadow attributes.
 * @return string - CSS Styles.
 */
function editorplus_text_shadow_convert($attribute) {

	return sprintf(
		'text-shadow:%1$spx %2$spx %3$spx %4$s;',
		$attribute['horizontal'] ?? 0,
		$attribute['vertical'] ?? 0,
		$attribute['blurRadius'] ?? 0,
		$attribute['shadowColor'] ?? '#000',
	);
}

/**
 * Will convert editorplus typography (sub attribute) text style into CSS readable style
 *
 * @param array $attribute - Typography attribute.
 * @return string $css - Generated CSS Styles.
 */
function editorplus_convert_text_styles($attribute) {

	$text_styles = $attribute['textStyle'];

	$valid_text_decorations = array('underline', 'line-through');
	$valid_text_styles      = array('italic');
	$valid_text_transforms  = array('lowercase', 'uppercase', 'capitalize');

	$generated_text_styles = '';

	// Looping over each text style.
	foreach ($text_styles as $text_style) {

		// Checking if it's a valid text-decoration.
		if (in_array($text_style, $valid_text_decorations, true)) {
			$generated_text_styles .= sprintf('text-decoration:%1$s;', $text_style);
			continue;
		}

		// Checking if it's a valid text-style.
		if (in_array($text_style, $valid_text_styles, true)) {
			$generated_text_styles .= sprintf('font-style:%1$s;', $text_style);
			continue;
		}

		// Checking if it's a valid text-transform.
		if (in_array($text_style, $valid_text_transforms, true)) {
			$generated_text_styles .= sprintf('text-transform:%1$s;', $text_style);
			continue;
		}
	}

	// Applying additional linethrough styles, only if it exists in the textstyles.
	if (in_array('line-through', $text_styles, true) && isset($attribute['lineThrough'])) {

		$linethrough_color = $attribute['lineThrough']['color'] ?? null;

		if (!is_null($linethrough_color)) {
			$generated_text_styles .= sprintf('text-decoration-color:%1$s !important;', $linethrough_color);
		}

		$linethrough_style = $attribute['lineThrough']['style'] ?? null;

		if (!is_null($linethrough_style)) {
			$generated_text_styles .= sprintf('text-decoration-style:%1$s !important;', $linethrough_style);
		}
	}

	// Applying additional underline styles, only if it exists in the textstyles.
	if (in_array('underline', $text_styles, true) && isset($attribute['underline'])) {

		$underline_color = $attribute['underline']['color'] ?? null;

		if (!is_null($underline_color)) {
			$generated_text_styles .= sprintf('text-decoration-color:%1$s !important;', $underline_color);
		}

		$underline_style = $attribute['underline']['style'] ?? null;

		if (!is_null($underline_style)) {
			$generated_text_styles .= sprintf('text-decoration-style:%1$s !important;', $underline_style);
		}
	}

	return $generated_text_styles;
}

/**
 * Will convert editorplus typography attribute into actual css style.
 *
 * @param array $attribute - Typography attribute.
 * @return string - CSS Styles.
 */
function editorplus_typography_convert($attribute) {

	$generated_typography_styles = '';

	if (isset($attribute['lineHeight'])) {
		$generated_typography_styles .= editorplus_range_convert('line-height', $attribute['lineHeight']);
	}

	if (isset($attribute['letterSpacing'])) {
		$generated_typography_styles .= editorplus_range_convert('letter-spacing', $attribute['letterSpacing']);
	}

	if (isset($attribute['fontSize'])) {
		$generated_typography_styles .= editorplus_range_convert('font-size', $attribute['fontSize']);
	}

	if (isset($attribute['textAlignment']) && '' !== $attribute['textAlignment']) {
		$generated_typography_styles .= sprintf('text-align:%1$s;', $attribute['textAlignment']);
	}

	if (isset($attribute['textStyle'])) {

		$generated_typography_styles .= editorplus_convert_text_styles($attribute);
	}

	if (isset($attribute['textColor'])) {

		$is_important = $attribute['textColor']['imp'] ?? false;
		$text_color   = $attribute['textColor']['color'] ?? null;

		if (!is_null($text_color) && '' !== $text_color) {
			$generated_typography_styles .= sprintf(
				'color:%1$s%2$s;',
				$text_color,
				$is_important ? ' !important' : ''
			);
		}
	}

	if (isset($attribute['fontWeight'])) {
		$generated_typography_styles .= sprintf('font-weight: %1$s;', $attribute['fontWeight']);
	}

	return $generated_typography_styles;
}

/**
 * Will convert editorplus background attribute into actual css style.
 *
 * @param array $attribute - Background attribute.
 * @return string - CSS Styles.
 */
function editorplus_background_convert($attribute) {
	$generated_background_styles = '';

	$background_image = isset($attribute['media']) && isset($attribute['media']['background']['url']) ? sprintf(
		'url("%1$s")',
		$attribute['media']['background']['url']
	) : '';

	$background_placement = $attribute['media']['backgroundPlacement'] ?? 'back';

	// Checking if only solid background is needed.
	if (isset($attribute['solid']) && !empty($attribute['solid'])) {

		$solid_background = array(
			sprintf('linear-gradient(%1$s,%1$s)', $attribute['solid']),
			$background_image,
		);

		// Reversing the order based on background placement.
		if ('back' !== $background_placement) {
			$solid_background = array_reverse($solid_background);
		}

		$solid_background = array_filter($solid_background, 'strlen');

		$generated_background_styles .= sprintf('background-image:%1$s;', join(',', $solid_background));
	}

	// Checking if only gradient background is needed.
	if (isset($attribute['gradient']) && !empty($attribute['gradient'])) {

		$gradient_background = array($attribute['gradient'], $background_image);

		// Reversing the order, if the background placement is front.
		if ('back' !== $background_placement) {
			$gradient_background = array_reverse($gradient_background);
		}

		$gradient_background = array_filter($gradient_background, 'strlen');

		$generated_background_styles .= sprintf('background-image:%1$s;', join(',', $gradient_background));
	}

	// Checking if only media is needed.
	if (empty($attribute['solid']) && empty($attribute['gradient']) && !empty($background_image)) {
		$generated_background_styles .= sprintf('background-image:%1$s;', $background_image);
	}

	if (isset($attribute['media']['backgroundAttachment']) && '' !== $attribute['media']['backgroundAttachment']) {
		$generated_background_styles .= sprintf('background-attachment:%1$s;', $attribute['media']['backgroundAttachment']);
	}

	if (isset($attribute['media']['backgroundRepeat']) && '' !== $attribute['media']['backgroundRepeat']) {
		$generated_background_styles .= sprintf('background-repeat:%1$s;', $attribute['media']['backgroundRepeat']);
	}

	if (isset($attribute['media']['backgroundSize']) && '' !== $attribute['media']['backgroundSize']) {
		$generated_background_styles .= sprintf('background-size:%1$s;', $attribute['media']['backgroundSize']);
	}

	if (isset($attribute['media']['backgroundPositionX']) && '' !== $attribute['media']['backgroundPositionX']) {
		$generated_background_styles .= sprintf(
			'background-position-x:%1$s;',
			editorplus_convert_position_to_percentage($attribute['media']['backgroundPositionX'])
		);
	}

	if (isset($attribute['media']['backgroundPositionY']) && '' !== $attribute['media']['backgroundPositionY']) {
		$generated_background_styles .= sprintf(
			'background-position-y:%1$s;',
			editorplus_convert_position_to_percentage($attribute['media']['backgroundPositionY'])
		);
	}

	return $generated_background_styles;
}

/**
 * Will convert editorplus box shadow attribute into actual css styles.
 *
 * @param array $attribute - Box shadow attributes.
 * @return string - CSS Styles.
 */
function editorplus_box_shadow_convert($attribute) {

	$is_important = $attribute['important'] ?? false;
	$important    = $is_important ? ' !important' : '';

	$is_inset = isset($attribute['inset']) && true === $attribute['inset'];

	return sprintf(
		'box-shadow:%1$s %2$spx %3$spx %4$spx %5$spx %6$s%7$s;',
		$is_inset ? 'inset' : '',
		$attribute['horizontal'] ?? 0,
		$attribute['vertical'] ?? 0,
		$attribute['blurRadius'] ?? 0,
		$attribute['spreadRadius'] ?? 0,
		$attribute['shadowColor'] ?? '#000',
		$important
	);
}

/**
 * Will convert editorplus single border attribute into actual CSS.
 *
 * @param string $css_style - Borders CSS Style.
 * @param array  $attribute - Borders to convert.
 * @return string - CSS Styles.
 */
function editorplus_border_convert($css_style, $attribute) {

	$is_important = $attribute['important'] ?? false;

	if (!array_key_exists('area', $attribute) || '' === $attribute['area']) {
		return '';
	}

	return sprintf(
		'%5$s:%1$spx %2$s %3$s%4$s;',
		$attribute['area'],
		$attribute['style'] ?? 'solid',
		$attribute['color'] ?? '#000000',
		$is_important ? ' !important' : '',
		$css_style
	);
}

/**
 * Will convert editorplus borders attribute into actual CSS.
 *
 * @param array $attribute - Borders to convert.
 * @return string - CSS Styles.
 */
function editorplus_borders_convert($attribute) {

	$generated_borders_css = array();

	if (isset($attribute['borderTop'])) {
		$generated_borders_css[] = editorplus_border_convert('border-top', $attribute['borderTop']);
	}

	if (isset($attribute['borderRight'])) {
		$generated_borders_css[] = editorplus_border_convert('border-right', $attribute['borderRight']);
	}

	if (isset($attribute['borderBottom'])) {
		$generated_borders_css[] = editorplus_border_convert('border-bottom', $attribute['borderBottom']);
	}

	if (isset($attribute['borderLeft'])) {
		$generated_borders_css[] = editorplus_border_convert('border-left', $attribute['borderLeft']);
	}

	// Checking if all borders are same, Merging them at one property of border if all are same.
	$all_borders_same = 1 === count(array_unique($generated_borders_css));

	if ($all_borders_same) {
		return editorplus_border_convert('border', $attribute['borderAll']);
	}

	return join('', $generated_borders_css);
}

/**
 * Will convert editorplus border radius attribute into actual CSS.
 *
 * @param array $attribute - Border Radius to convert.
 * @return string - CSS Styles.
 */
function editorplus_border_radius_convert($attribute) {

	$is_important = $attribute['important'] ?? false;

	return sprintf(
		'border-radius:%1$s%5$s %2$s%5$s %3$s%5$s %4$s%5$s%6$s;',
		isset($attribute['value']['top']) && '' !== $attribute['value']['top'] ? $attribute['value']['top'] : 0,
		isset($attribute['value']['right']) && '' !== $attribute['value']['right'] ? $attribute['value']['right'] : 0,
		isset($attribute['value']['bottom']) && '' !== $attribute['value']['bottom'] ? $attribute['value']['bottom'] : 0,
		isset($attribute['value']['left']) && '' !== $attribute['value']['left'] ? $attribute['value']['left'] : 0,
		$attribute['unit'] ?? '%',
		$is_important ? ' !important' : ''
	);
}


/**
 * Will convert editorplus margin attribute into actual CSS.
 *
 * @param array $attribute - Margin to convert.
 * @return string - CSS Styles.
 */
function editorplus_margin_convert($attribute) {
	return editorplus_dimension_convert('margin', $attribute);
}


/**
 * Will convert editorplus padding attribute into actual CSS.
 *
 * @param array $attribute - padding to convert.
 * @return string - CSS Styles.
 */
function editorplus_padding_convert($attribute) {
	return editorplus_dimension_convert('padding', $attribute);
}

/**
 * Will convert editorplus sizing attribute into actual CSS.
 *
 * @param array $attribute - sizing to convert.
 * @return string - CSS Styles.
 */
function editorplus_sizing_convert($attribute) {

	$generated_sizing_styles = '';

	if (isset($attribute['height'])) {
		$generated_sizing_styles .= editorplus_range_convert('height', $attribute['height']);
	}

	if (isset($attribute['width'])) {
		$generated_sizing_styles .= editorplus_range_convert('width', $attribute['width']);
	}

	if (isset($attribute['maxWidth'])) {
		$generated_sizing_styles .= editorplus_range_convert('max-width', $attribute['maxWidth']);
	}

	if (isset($attribute['maxHeight'])) {
		$generated_sizing_styles .= editorplus_range_convert('max-height', $attribute['maxHeight']);
	}

	return $generated_sizing_styles;
}

/**
 * Will convert editorplus transition into actual CSS.
 *
 * @param array $attribute - transition to convert.
 * @return string - CSS Styles.
 */
function editorplus_transition_convert($attribute) {

	if (!isset($attribute['duration'])) {
		return '';
	}

	if ('' === $attribute['duration']) {
		return '';
	}

	$is_important = $attribute['important'] ?? false;

	return sprintf(
		'transition:%1$sms %2$sms %3$s%4$s;',
		$attribute['duration'],
		isset($attribute['delay']) && '' !== $attribute['delay'] ? $attribute['delay'] : 0,
		isset($attribute['speedCurve']) && '' !== $attribute['speedCurve'] ? $attribute['speedCurve'] : 'ease',
		$is_important ? ' !important' : ''
	);
}

/**
 * Will convert editorplus extra attribute into actual CSS.
 *
 * @param array $attribute - extras to convert.
 * @return string - CSS Styles.
 */
function editorplus_extras_convert($attribute) {

	$generated_extra_styles = '';

	if (isset($attribute['zIndex'])) {

		$has_z_index          = '' !== $attribute['zIndex']['value'];
		$is_z_index_important = $attribute['zIndex']['important'] ?? false;

		if ($has_z_index) {
			$generated_extra_styles .= sprintf(
				'z-index:%1$s%2$s;',
				$attribute['zIndex']['value'],
				$is_z_index_important ? ' !important' : ''
			);
		}
	}

	if (isset($attribute['blockSpacing'])) {
		$generated_extra_styles .= editorplus_range_convert('grid-gap', $attribute['blockSpacing']);
	}

	if (isset($attribute['overflow'])) {

		$has_overflow          = '' !== $attribute['overflow']['value'];
		$is_overflow_important = $attribute['overflow']['important'] ?? false;

		if ($has_overflow) {
			$generated_extra_styles .= sprintf(
				'overflow:%1$s%2$s;',
				$attribute['overflow']['value'],
				$is_overflow_important ? ' !important' : ''
			);
		}
	}

	if (isset($attribute['position'])) {

		$has_position          = '' !== $attribute['position']['value'];
		$is_position_important = $attribute['position']['important'] ?? false;

		if ($has_position) {
			$generated_extra_styles .= sprintf(
				'position:%1$s%2$s;',
				$attribute['position']['value'],
				$is_position_important ? ' !important' : ''
			);
		}
	}

	if (isset($attribute['position']) && isset($attribute['offsets'])) {
		$generated_extra_styles .= editorplus_dimension_convert('position', $attribute['offsets']);
	}


	if (isset($attribute['hide'])) {

		$has_display          = '' !== $attribute['hide']['property'];
		$is_display_important = $attribute['hide']['important'] ?? false;

		if ($has_display) {
			$generated_extra_styles .= sprintf(
				'display:%1$s%2$s;',
				$attribute['hide']['property'],
				$is_display_important ? ' !important' : ''
			);
			if ('flex' === $attribute['hide']['property'] || 'grid' === $attribute['hide']['property']) {
				if (isset($attribute['alignItems'])) {

					$has_align_items          = '' !== $attribute['alignItems'];

					if ($has_align_items) {
						$generated_extra_styles .= sprintf(
							'align-items:%1$s;',
							$attribute['alignItems'],
						);
					}
				}
				if (isset($attribute['justifyContent'])) {

					$has_justify_content          = '' !== $attribute['justifyContent'];

					if ($has_justify_content) {
						$generated_extra_styles .= sprintf(
							'justify-content:%1$s;',
							$attribute['justifyContent']
						);
					}
				}
			}
			if ('flex' === $attribute['hide']['property'] && isset($attribute['flexDirection'])) {

				$has_flex_direction          = '' !== $attribute['flexDirection'];

				if ($has_flex_direction) {
					$generated_extra_styles .= sprintf(
						'flex-direction:%1$s;',
						$attribute['flexDirection'],
					);
				}
			}
		}
	}

	if (isset($attribute['transition'])) {
		$generated_extra_styles .= editorplus_transition_convert($attribute['transition']);
	}

	return $generated_extra_styles;
}

/**
 * Will check if the given extension is enabled or not
 *
 * @param string $extension_slug - Extension to test.
 * @return bool - True if enabled, otherwise false.
 */
function editorplus_is_extension_enabled($extension_slug) {

	if (!is_string($extension_slug)) {
		return false;
	}

	$option_key       = 'editor_plus_extensions_' . $extension_slug . '__enable';
	$extension_status = get_option($option_key, true);

	if ('1' === $extension_status || true === $extension_status) {
		return true;
	}

	return false;
}

/**
 * Will convert editorplus custom css attribute into actual CSS by replacing the selector.
 *
 * @param array $attribute - custom css.
 * @param array $selector - css selector.
 * @return string - CSS Styles.
 */
function editorplus_customcss_convert($attribute, $selector) {

	$with_selector = str_replace('selector', '.' . $selector, $attribute);

	return $with_selector;
}

/**
 * Will generate editorplus style tag.
 *
 * @param string $selector - CSS Selector.
 * @param string $desktop_styles - Desktop CSS Style.
 * @param string $tablet_styles - Tablet CSS Style.
 * @param string $mobile_styles - Mobile CSS Style.
 * @param string $hover_styles - Hover CSS Style.
 * @param string $additional_styles - Any additional styles to merge.
 *
 * @return string - CSS Styles.
 */
function editorplus_generate_css_output($selector, $desktop_styles, $tablet_styles, $mobile_styles, $hover_styles, $additional_styles = '') {

	// Generating output for desktop.
	$generated_styles_output = sprintf(
		'.%1$s {%2$s}',
		$selector,
		$desktop_styles
	);

	// Generating output for tablet, if needed.
	if ('' !== $tablet_styles) {
		$generated_styles_output .= editorplus_generate_css_output_for('.' . $selector, 'Tablet', $tablet_styles);
	}

	// Generating output for mobile, if needed.
	if ('' !== $mobile_styles) {
		$generated_styles_output .= editorplus_generate_css_output_for('.' . $selector, 'Mobile', $mobile_styles);
	}

	// Generating output for hover, if needed.
	if ('' !== $hover_styles) {
		$generated_styles_output .= editorplus_generate_css_output_for('.' . $selector, 'Hover', $hover_styles);
	}

	$generated_styles_output .= $additional_styles;

	return $generated_styles_output;
}

/**
 * Will generate editorplus css output for a certain device or breakpoint/hover.
 *
 * @param string $selector - CSS Selector.
 * @param string $type - Style Type.
 * @param string $css_output - CSS Styles.
 */
function editorplus_generate_css_output_for($selector, $type, $css_output) {

	if ('' === $css_output) {
		return '';
	}

	if ('Desktop' === $type) {
		return sprintf(
			'%1$s {%2$s}',
			$selector,
			$css_output
		);
	}

	if ('Tablet' === $type) {
		return sprintf(
			'@media (max-width: 981px) { %1$s { %2$s } }',
			$selector,
			$css_output
		);
	}

	if ('Mobile' === $type) {
		return sprintf(
			'@media (max-width: 600px) { %1$s { %2$s } }',
			$selector,
			$css_output
		);
	}

	if ('Hover' === $type) {

		$declaration = false !== stripos($selector, ':hover') ? '%1$s{ %2$s }' : '%1$s:hover { %2$s }';

		return sprintf(
			$declaration,
			$selector,
			$css_output
		);
	}

	return '';
}


/**
 * Will generate editorplus raw css output for a certain device or breakpoint/hover.
 *
 * @param string $type - Style Type.
 * @param string $css_output - CSS Styles.
 */
function editorplus_generate_raw_css_output_for($type, $css_output) {

	if ('' === $css_output) {
		return '';
	}

	if ('Desktop' === $type) {
		return $css_output;
	}

	if ('Tablet' === $type) {
		return sprintf(
			'@media (max-width: 981px) { %1$s }',
			$css_output
		);
	}

	if ('Mobile' === $type) {
		return sprintf(
			'@media (max-width: 600px) { %1$s }',
			$css_output
		);
	}

	return '';
}

/**
 * Will convert editorplus extended list block into css styling.
 *
 * @param array  $block - Block to generate styles.
 * @param string $selector - block selector.
 * @return string - CSS Styles.
 */
function editorplus_convert_extended_list_block($block, $selector) {

	if (!is_array($block) || !array_key_exists('attrs', $block)) {
		return '';
	}

	$attributes = $block['attrs'];

	$is_ordered_list = false !== stripos($block['innerHTML'], '</ol>');
	$use_icon        = false !== stripos($block['innerHTML'], 'ep-custom-icon-list');

	$generated_styles        = '';
	$generated_tablet_styles = '';
	$generated_mobile_styles = '';
	$generated_hover_styles  = '';
	$additional_styles       = '';

	if (isset($attributes['columns']) && '' !== $attributes['columns']) {
		$generated_styles        .= editorplus_range_convert('grid-template-columns', $attributes['columns']['desktop'], 'repeat(%1$s, 1fr)');
		$generated_tablet_styles .= editorplus_range_convert('grid-template-columns', $attributes['columns']['tablet'], 'repeat(%1$s, 1fr)');
		$generated_mobile_styles .= editorplus_range_convert('grid-template-columns', $attributes['columns']['mobile'], 'repeat(%1$s, 1fr)');
		$generated_hover_styles  .= editorplus_range_convert('grid-template-columns', $attributes['columns']['hover'], 'repeat(%1$s, 1fr)');
	}

	if (isset($attributes['itemsSpacing']) && '' !== $attributes['itemsSpacing']) {
		$generated_styles        .= editorplus_range_convert('grid-gap', $attributes['itemsSpacing']['desktop']);
		$generated_tablet_styles .= editorplus_range_convert('grid-gap', $attributes['itemsSpacing']['tablet']);
		$generated_mobile_styles .= editorplus_range_convert('grid-gap', $attributes['itemsSpacing']['mobile']);
		$generated_hover_styles  .= editorplus_range_convert('grid-gap', $attributes['itemsSpacing']['hover']);
	}

	if ($use_icon && isset($attributes['icon'])) {

		$use_icon_declaration = '.%1$s li::before { %2$s %3$s %4$s %5$s %6$s %7$s }';

		$additional_styles .= sprintf(
			$use_icon_declaration,
			$selector,
			'' !== $attributes['icon']['desktop']['icon'] ? sprintf('content: var(--%1$s);', $attributes['icon']['desktop']['icon']) : '',
			!empty($attributes['iconBackground']['desktop']) && '' !== $attributes['iconBackground']['desktop']['color']  ? sprintf('background-color:%1$s;', $attributes['iconBackground']['desktop']['color']) : '',
			!empty($attributes['iconColor']['desktop']) && '' !== $attributes['iconColor']['desktop']['color']  ? sprintf('color:%1$s;', $attributes['iconColor']['desktop']['color']) : '',
			editorplus_range_convert('font-size', $attributes['iconSize']['desktop']),
			editorplus_range_convert('border-radius', $attributes['iconRadius']['desktop']),
			editorplus_range_convert('padding', $attributes['iconPadding']['desktop'])
		);

		$additional_styles .= editorplus_generate_raw_css_output_for(
			'Tablet',
			sprintf(
				$use_icon_declaration,
				$selector,
				'' !== $attributes['icon']['tablet']['icon'] ? sprintf('content: var(--%1$s);', $attributes['icon']['tablet']['icon']) : '',
				'' !== $attributes['iconBackground']['tablet']['color'] ? sprintf('background-color:%1$s;', $attributes['iconBackground']['tablet']['color']) : '',
				'' !== $attributes['iconColor']['tablet']['color'] ? sprintf('color:%1$s;', $attributes['iconColor']['tablet']['color']) : '',
				editorplus_range_convert('font-size', $attributes['iconSize']['tablet']),
				editorplus_range_convert('border-radius', $attributes['iconRadius']['tablet']),
				editorplus_range_convert('padding', $attributes['iconPadding']['tablet'])
			)
		);

		$additional_styles .= editorplus_generate_raw_css_output_for(
			'Mobile',
			sprintf(
				$use_icon_declaration,
				$selector,
				'' !== $attributes['icon']['mobile']['icon'] ? sprintf('content: var(--%1$s);', $attributes['icon']['mobile']['icon']) : '',
				'' !== $attributes['iconBackground']['mobile']['color'] ? sprintf('background-color:%1$s;', $attributes['iconBackground']['mobile']['color']) : '',
				'' !== $attributes['iconColor']['mobile']['color'] ? sprintf('color:%1$s;', $attributes['iconColor']['mobile']['color']) : '',
				editorplus_range_convert('font-size', $attributes['iconSize']['mobile']),
				editorplus_range_convert('border-radius', $attributes['iconRadius']['mobile']),
				editorplus_range_convert('padding', $attributes['iconPadding']['mobile'])
			)
		);

		if ('' !== $attributes['icon']['hover']['icon']) {
			$additional_styles .= sprintf(
				'.%1$s li:hover:before { %2$s %3$s %4$s %5$s %6$s %7$s }',
				$selector,
				'' !== $attributes['icon']['hover']['icon'] ? sprintf('content: var(--%1$s);', $attributes['icon']['hover']['icon']) : '',
				$attributes['iconBackground']['hover']['color'] ? sprintf('background-color:%1$s;', $attributes['iconBackground']['hover']['color']) : '',
				$attributes['iconColor']['hover']['color'] ? sprintf('color:%1$s;', $attributes['iconColor']['hover']['color']) : '',
				editorplus_range_convert('font-size', $attributes['iconSize']['hover']),
				editorplus_range_convert('border-radius', $attributes['iconRadius']['hover']),
				editorplus_range_convert('padding', $attributes['iconPadding']['hover'])
			);
		}
	}

	if (!$is_ordered_list && false === $use_icon) {
		if (isset($attributes['unorderListStyle']) && '' !== $attributes['unorderListStyle']) {
			$generated_styles        .= sprintf('list-style-type:%1$s;', $attributes['unorderListStyle']['desktop']);
			$generated_tablet_styles .= sprintf('list-style-type:%1$s;', $attributes['unorderListStyle']['tablet']);
			$generated_mobile_styles .= sprintf('list-style-type:%1$s;', $attributes['unorderListStyle']['mobile']);
			$generated_hover_styles  .= sprintf('list-style-type:%1$s;', $attributes['unorderListStyle']['hover']);
		}
	} elseif ($is_ordered_list && false === $use_icon) {
		if (isset($attributes['orderListStyle']) && '' !== $attributes['orderListStyle']) {
			$generated_styles        .= sprintf('list-style-type:%1$s;', $attributes['orderListStyle']['desktop']);
			$generated_tablet_styles .= sprintf('list-style-type:%1$s;', $attributes['orderListStyle']['tablet']);
			$generated_mobile_styles .= sprintf('list-style-type:%1$s;', $attributes['orderListStyle']['mobile']);
			$generated_hover_styles  .= sprintf('list-style-type:%1$s;', $attributes['orderListStyle']['hover']);
		}
	}

	return editorplus_generate_css_output(
		$selector,
		$generated_styles,
		$generated_tablet_styles,
		$generated_mobile_styles,
		$generated_hover_styles,
		$additional_styles
	);
}

/**
 * Will convert editorplus extended columns block into css styling.
 *
 * @param array  $block - Block to generate styles.
 * @param string $selector - block selector.
 * @return string - CSS Styles.
 */
function editorplus_convert_extended_columns_block($block, $selector) {

	if (!is_array($block) || !array_key_exists('attrs', $block)) {
		return '';
	}

	$is_disabled = false === stripos($block['innerHTML'], 'ep-custom-column');

	if ($is_disabled) {
		return '';
	}

	$attributes = $block['attrs'];

	$generated_styling        = '';
	$generated_tablet_styling = '';
	$generated_mobile_styling = '';
	$generated_hover_styling  = '';

	if (isset($attributes['epCustomColumnsSpacing']) && '' !== $attributes['epCustomColumnsSpacing']) {
		$generated_styling        .= editorplus_range_convert('gap', $attributes['epCustomColumnsSpacing']['desktop']);
		$generated_tablet_styling .= editorplus_range_convert('gap', $attributes['epCustomColumnsSpacing']['tablet']);
		$generated_mobile_styling .= editorplus_range_convert('gap', $attributes['epCustomColumnsSpacing']['mobile']);
	}

	if (isset($attributes['epCustomColumnsReverse']) && '' !== $attributes['epCustomColumnsReverse']) {
		$generated_styling        .= sprintf('flex-direction:%1$s;', $attributes['epCustomColumnsReverse']['desktop']);
		$generated_tablet_styling .= sprintf('flex-direction:%1$s;', $attributes['epCustomColumnsReverse']['tablet']);
		$generated_mobile_styling .= sprintf('flex-direction:%1$s;', $attributes['epCustomColumnsReverse']['mobile']);
	}

	return editorplus_generate_css_output(
		$selector,
		$generated_styling,
		$generated_tablet_styling,
		$generated_mobile_styling,
		$generated_hover_styling
	);
}

/**
 * Will generate icon styles.
 *
 * @param string $viewport - Viewport.
 * @param string $attributes - Attributes.
 */
function editorplus_get_icon_with_styles($viewport, $attributes) {

	$icon_alignment = '' !== $attributes['epCustomButtonsAlignment'][$viewport] ? $attributes['epCustomButtonsAlignment'][$viewport] : $attributes['epCustomButtonsAlignment']['desktop'];
	$pseudo         = 'right' !== $icon_alignment ? 'epCustomButtonIconBefore' : 'epCustomButtonIconAfter';

	$icon_background_color = $attributes['epCustomIconBackground'][$viewport]['color'] ?? '';
	$icon_color            = $attributes['epCustomIconColor'][$viewport]['color'] ?? '';
	$icon_radius           = $attributes['epCustomIconRadius'][$viewport];
	$icon_padding          = $attributes['epCustomIconPadding'][$viewport];
	$icon_spacing          = 'epCustomButtonIconAfter' === $pseudo ? $attributes['epCustomAfterIconSpace'][$viewport] : $attributes['epCustomBeforeIconSpace'][$viewport];

	return sprintf(
		'%1$s %2$s %3$s %4$s %5$s %6$s',
		'' !== $attributes[$pseudo][$viewport]['icon'] ? sprintf('content: var(--%1$s);', $attributes[$pseudo][$viewport]['icon']) : '',
		'' !== $icon_background_color ? sprintf('background-color:%1$s;', $icon_background_color) : '',
		'' !== $icon_color ? sprintf('color:%1$s;', $icon_color) : '',
		editorplus_range_convert('border-radius', $icon_radius),
		editorplus_range_convert('padding', $icon_padding),
		editorplus_range_convert('epCustomButtonIconAfter' === $pseudo ? 'margin-left' : 'margin-right', $icon_spacing)
	);
}


/**
 * Will convert editorplus extended button block into css styling.
 *
 * @param array  $block - Block to generate styles.
 * @param string $selector - block selector.
 * @return string - CSS Styles.
 */
function editorplus_convert_extended_button_block($block, $selector) {

	// TODO: This is a little buggy 🐞.

	if (!is_array($block) || !array_key_exists('attrs', $block)) {
		return '';
	}

	$attributes = $block['attrs'];
	$use_icon   = false !== stripos($block['innerHTML'], 'ep-custom-buttons-icon');
	$generated_styling = '';

	if (false === $use_icon) {
		return '';
	}

	$generated_icon_desktop_styling = editorplus_get_icon_with_styles('desktop', $attributes);

	if ('' !== trim($generated_icon_desktop_styling)) {
		$generated_styling .= editorplus_generate_raw_css_output_for(
			'Desktop',
			sprintf(
				'.%1$s.wp-block-button__link:%2$s { %3$s }',
				$selector,
				'right' !== $attributes['epCustomButtonsAlignment']['desktop'] ? 'before' : 'after',
				$generated_icon_desktop_styling
			)
		);
	}

	$generated_icon_tablet_styling = editorplus_get_icon_with_styles('tablet', $attributes);
	$tablet_alignment = '';

	if ('' !== trim($generated_icon_tablet_styling)) {
		if (!empty($attributes['epCustomButtonsAlignment']['tablet'])) {
			$tablet_alignment = $attributes['epCustomButtonsAlignment']['tablet'];
		} else if (!empty($attributes['epCustomButtonsAlignment']['desktop'])) {
			$tablet_alignment = $attributes['epCustomButtonsAlignment']['desktop'];
		}
		$generated_styling .= editorplus_generate_raw_css_output_for(
			'Tablet',
			sprintf(
				'.%1$s.wp-block-button__link:%2$s { %3$s }',
				$selector,
				'right' !== $tablet_alignment ? 'before' : 'after',
				$generated_icon_tablet_styling
			)
		);
	}

	$generated_icon_mobile_styling = editorplus_get_icon_with_styles('mobile', $attributes);
	$mobile_alignment = '';
	if ('' !== trim($generated_icon_mobile_styling)) {
		if (!empty($attributes['epCustomButtonsAlignment']['mobile'])) {
			$mobile_alignment = $attributes['epCustomButtonsAlignment']['mobile'];
		} else if (!empty($attributes['epCustomButtonsAlignment']['tablet'])) {
			$mobile_alignment = $attributes['epCustomButtonsAlignment']['tablet'];
		} else if (!empty($attributes['epCustomButtonsAlignment']['desktop'])) {
			$mobile_alignment = $attributes['epCustomButtonsAlignment']['desktop'];
		}
		$generated_styling .= editorplus_generate_raw_css_output_for(
			'Mobile',
			sprintf(
				'.%1$s.wp-block-button__link:%2$s { %3$s }',
				$selector,
				'right' !== $mobile_alignment ? 'before' : 'after',
				$generated_icon_mobile_styling
			)
		);
	}

	$generated_icon_hover_styling = editorplus_get_icon_with_styles('hover', $attributes);
	$hover_alignment = '';

	if ('' !== trim($generated_icon_hover_styling)) {
		if (!empty($attributes['epCustomButtonsAlignment']['hover'])) {
			$hover_alignment = $attributes['epCustomButtonsAlignment']['hover'];
		} else if (!empty($attributes['epCustomButtonsAlignment']['mobile'])) {
			$hover_alignment = $attributes['epCustomButtonsAlignment']['mobile'];
		} else if (!empty($attributes['epCustomButtonsAlignment']['tablet'])) {
			$hover_alignment = $attributes['epCustomButtonsAlignment']['tablet'];
		} else if (!empty($attributes['epCustomButtonsAlignment']['desktop'])) {
			$hover_alignment = $attributes['epCustomButtonsAlignment']['desktop'];
		}
		$generated_styling .= sprintf(
			'.%1$s.wp-block-button__link:hover:%2$s { %3$s }',
			$selector,
			'right' !== $hover_alignment ? 'before' : 'after',
			$generated_icon_hover_styling
		);
	}

	return $generated_styling;
}

/**
 * Will convert editorplus animation.
 *
 * @param array  $block - block to apply.
 * @param string $selector - block selector.
 * @return string - CSS Styles.
 */
function editorplus_animation_convert($block, $selector) {

	if (!is_array($block) || !isset($block['attrs'])) {
		return '';
	}

	$attributes         = $block['attrs']['epCustomAnimation'];
	$animation_selector = $block['attrs']['epAnimationGeneratedClass'] ?? '';

	if ('' !== $attributes['type']) {

		$animation_direction                   = 'center' === $attributes['direction'] ? '' : ucfirst($attributes['direction']);
		$animation_iteration_count             = 'repeat' === $attributes['repeat'] ? 'Infinite' : '1';
		$animation_with_unsupported_directions = array('fade', 'roll', 'zoom');

		if (in_array($attributes['type'], $animation_with_unsupported_directions, true)) {
			$animation_direction = '';
		}

		return sprintf(
			'.%6$s {animation: editor-plus-%1$s %2$ss %3$s %4$ss %5$s forwards}',
			$attributes['type'] . $animation_direction,
			$attributes['duration'],
			$attributes['speedCurve'] ?? 'ease',
			$attributes['delay'] ?? 0,
			$animation_iteration_count,
			$animation_selector
		);
	}

	return '';
}

/**
 * Will provide editorplus block selector based on the block name.
 *
 * @param string $selector - CSS Selector.
 * @param string $block_name - Block name which is being selected.
 *
 * @return string - block selector.
 */
function editorplus_get_block_selector($selector, $block_name) {

	switch ($block_name) {

		case 'core/button':
			return sprintf('%1$s a', $selector);

		case 'core/image':
			return sprintf('%1$s img', $selector);

		default:
			return $selector;
	}
}
/**
 * Will convert editorplus transform into actual CSS.
 *
 * @param array $attribute - transform to convert.
 * @return string - CSS Styles.
 */
function editorplus_transform_convert($attribute) {
	$converted_transform = '';
	$converted_origin    = '';

	if (!empty($attribute['rotate']['x'])) {
		$converted_transform .= sprintf('rotateX(%1$s)', $attribute['rotate']['x']);
	}

	if (!empty($attribute['rotate']['y'])) {
		$converted_transform .= sprintf('rotateY(%1$s)', $attribute['rotate']['y']);
	}

	if (!empty($attribute['rotate']['z'])) {
		$converted_transform .= sprintf('rotateZ(%1$s)', $attribute['rotate']['z']);
	}

	if (!empty($attribute['translate']['x'])) {
		$converted_transform .= sprintf('translateX(%1$s)', $attribute['translate']['x']);
	}

	if (!empty($attribute['translate']['y'])) {
		$converted_transform .= sprintf('translateY(%1$s)', $attribute['translate']['y']);
	}

	if (!empty($attribute['translate']['z'])) {
		$converted_transform .= sprintf('translateZ(%1$s)', $attribute['translate']['z']);
	}

	if (!empty($attribute['skew']['x'])) {
		$converted_transform .= sprintf('skewX(%1$s)', $attribute['skew']['x']);
	}

	if (!empty($attribute['skew']['y'])) {
		$converted_transform .= sprintf('skewY(%1$s)', $attribute['skew']['y']);
	}

	if (!empty($attribute['scale'])) {
		$converted_transform .= sprintf('scale(%1$s)', $attribute['scale']);
	}

	if (!empty($attribute['origin']['x']) || !empty($attribute['origin']['y'])) {
		$converted_origin = sprintf(
			'transform-origin:%1$s %2$s;',
			!empty($attribute['origin']['x']) ? $attribute['origin']['x'] : '0',
			!empty($attribute['origin']['y']) ? $attribute['origin']['y'] : '0',
		);
	}
	$final_transform = sprintf('transform:%1$s;%2$s', $converted_transform, $converted_origin);

	return strlen($converted_origin) > 0 || strlen($converted_transform) > 0 ? $final_transform : "";
}

function editor_plus_image_settings($attribute) {
	$generated_object_fit_css = '';
	$generated_object_position_css = '';

	if (isset($attribute['objectFit'])) {
		$generated_object_fit_css = sprintf('object-fit:%1$s;', $attribute['objectFit']);
	}

	if (!empty($attribute['objectPosition']['x']) || !empty($attribute['objectPosition']['y'])) {
		$generated_object_position_css = sprintf(
			'object-position:%1$s %2$s;',
			!empty($attribute['objectPosition']['x']) ? $attribute['objectPosition']['x'] : '0',
			!empty($attribute['objectPosition']['y']) ? $attribute['objectPosition']['y'] : '0',
		);
	}
	$final_object = sprintf('%1$s%2$s', $generated_object_fit_css, $generated_object_position_css);

	return strlen($generated_object_position_css) > 0 || strlen($generated_object_fit_css) > 0 ? $final_object : "";
}

/**
 * Will convert editorplus shape divider for before pseudo.
 *
 * @param array  $block - Block to apply shape divider to.
 * @param string $selector - block selector.
 * @return string CSS Styles - Shape divider in a css string.
 */
function editorplus_shape_divider_convert($block, $selector) {

	if (!is_array($block) || !isset($block['attrs'])) {
		return '';
	}

	$attributes    = $block['attrs'];
	$shape_divider = $attributes['epCustomShapeDividerBefore'];

	$position    = $shape_divider['position'] ?? 'top';
	$flip        = $shape_divider['flip'] ?? false;
	$arrangement = $shape_divider['arrangement'] ?? 'top';
	$color       = $shape_divider['color'] ?? '#000000';
	$style       = $shape_divider['style'] ?? '';

	if ('' === $style) {
		return '';
	}

	$generated_divider_styles = 'content:"";background-size:100% 100%;position:absolute;';

	if (isset($shape_divider['height']) && '' !== $shape_divider['height']) {
		$generated_divider_styles .= editorplus_range_convert('height', $shape_divider['height']);
	}

	if (isset($shape_divider['width']) && '' !== $shape_divider['width']) {
		$generated_divider_styles .= editorplus_range_convert('width', $shape_divider['width']);
	}

	if ('top' === $position) {
		$generated_divider_styles .= 'top:0!important;left:0!important;';
	} else {
		$generated_divider_styles .= 'bottom:-1px!important;left:0!important;';
	}

	// Updating the selected color in svg code.
	$style = preg_replace('/fill="#\w+"/', sprintf('fill="%1$s"', $color), $style);

	// For flipping the divider.
	$transforms  = '';
	$transforms .= $flip ? 'rotateY(180deg)' : '';
	$transforms .= 'bottom' === $position ? 'rotateX(180deg)' : '';

	if ('' !== $transforms) {
		$generated_divider_styles .= sprintf('transform:%1$s;', $transforms);
	}

	// For pushing the divider back.
	$generated_divider_styles .= 'underneath' === $arrangement ? 'z-index:-1;' : 'z-index:99;';

	$encoded_svg = rawurlencode(str_replace(array("\r", "\n"), ' ', $style));

	return sprintf(
		'%1$s {position:relative;} %1$s:before { %2$s background-image:url(%3$s); }',
		'.' . $selector,
		$generated_divider_styles,
		'data:image/svg+xml;utf8,' . $encoded_svg
	);
}

/**
 * Will provide a unique selector for the given block.
 *
 * @param array $block - block to find unique selector in.
 *
 * @return string - Selector.
 */
function editorplus_get_block_unique_selector($block) {

	if (!isset($block['innerHTML'])) {
		return isset($block['attrs']['epGeneratedClass']) ? $block['attrs']['epGeneratedClass'] : '';
	}

	$block = apply_filters('render_block', $block['innerHTML'], $block);

	$matched_selector = array();

	preg_match('(eplus-styles-uid-\w+)', $block, $matched_selector);

	return reset($matched_selector);
}


/**
 * Will generate editorplus unique classname for the block.
 *
 * @param array $block - Block to generate unique id for.
 * @return string - Unique id for the block.
 */
function editorplus_generate_unique_uid_for_block($block) {

	// Editorplus styling attributes.
	$styling_attributes = editorplus_get_block_styling_attributes($block);

	// Creating a unique hash from these attributes.
	// This id will only be changed if the user changes the editorplus attributes.
	// Doing so, will allow us to group multiple same styles attributes into one css declaration.
	$unique_block_id = md5(wp_json_encode($styling_attributes));

	// Providing the first 5 unique hash characters.
	if (strlen($unique_block_id) > 5) {
		$unique_block_id = substr($unique_block_id, 0, 6);
	}

	return $unique_block_id;
}
