<?php

/**
 * Required styles for the plugin.
 *
 * @link       http://www.webfactoryltd.com
 * @since      1.0
 */

if (!defined('WPINC')) {
	die;
}

echo '<style>' . "\r\n";

// Background cover
if ( ! empty( $options['bg_cover'] ) ) {
	echo 'body{background-image:url("' . esc_url($options['bg_cover']) . '");}' . "\r\n";
}


// Background color
if ( empty( $options['bg_cover'] ) && ! empty( $options['bg_color'] ) ) {
	echo 'body{background-color:#' . esc_attr($options['bg_color']) . ';}' . "\r\n";
}


// Header: font, size, and color
if ( ! empty( $options['header_font'] ) || ! empty( $options['header_font_size'] ) || ! empty( $options['header_font_color'] ) ) {
	echo '.header-text{';

	// header font
	if ( ! empty( $options['header_font'] ) ) {
		echo 'font-family:"' . esc_attr($options['header_font']) . '", Arial, sans-serif;';
	}

	// header font size
	if ( ! empty( $options['header_font_size'] ) ) {
		echo 'font-size:' . esc_attr($options['header_font_size']) . 'px;';
	}

	// header font color
	if ( ! empty( $options['header_font_color'] ) ) {
		echo 'color:#' . esc_attr($options['header_font_color']) . ';';
	}

	echo '}' . "\r\n";
}


// Secondary: font, size, and color
if ( ! empty( $options['secondary_font'] ) || ! empty( $options['secondary_font_size'] ) || ! empty( $options['secondary_font_color'] ) ) {
	echo '.gdpr_consent, .secondary-text{';

	// secondary font
	if ( ! empty( $options['secondary_font'] ) ) {
		echo 'font-family:"' . esc_attr($options['secondary_font']) . '", Arial, sans-serif;';
	}

	// secondary font size
	if ( ! empty( $options['secondary_font_size'] ) ) {
		echo 'font-size:' . esc_attr($options['secondary_font_size']) . 'px;';
	}

	// secondary font color
	if ( ! empty( $options['secondary_font_color'] ) ) {
		echo 'color:#' . esc_attr($options['secondary_font_color']) . ';';
	}

	echo '}' . "\r\n";
}


// Antispam: font, size, and color
// We apply secondary font family to antispam as well
if ( ! empty( $options['secondary_font'] ) || ! empty( $options['antispam_font_size'] ) || ! empty( $options['antispam_font_color'] ) ) {
	echo '.anti-spam{';

	// secondary font
	if ( ! empty( $options['secondary_font'] ) ) {
		echo 'font-family:"' . esc_attr($options['secondary_font']) . '", Arial, sans-serif;';
	}

	// antispam font size
	if ( ! empty( $options['antispam_font_size'] ) ) {
		echo 'font-size:' . esc_attr($options['antispam_font_size']) . 'px;';
	}

	// antispam font color
	if ( ! empty( $options['antispam_font_color'] ) ) {
		echo 'color:#' . esc_attr($options['antispam_font_color']) . ';';
	}

	echo '}' . "\r\n";
}


// Content: width, position, and alignment
if ( ! empty( $options['content_overlay'] ) || ! empty( $options['content_width'] ) || ! empty( $options['content_position'] ) || ! empty( $options['content_alignment'] ) ) {
	echo '.content{';

	// content overlay for background images
	if ( '1' == $options['content_overlay'] ) {
		echo 'padding:30px;border-radius:10px;box-shadow:0 0 10px 0 rgba(0, 0, 0, 0.33); background-color: rgba(0,0,0,0.55); ';
	}

	// content width
	if ( ! empty( $options['content_width'] ) ) {
		// Making sure the width is not < 100 and not > 1170
		if ( $options['content_width'] < 100 || $options['content_width'] > 1170 ) {
			$options['content_width'] = '1170';
		}

		echo 'max-width:' . esc_attr($options['content_width']) . 'px;';
	}

	// content position
	if ( ! empty( $options['content_position'] ) ) {
		if ( 'center' == $options['content_position'] ) {
			echo 'margin-left:auto;margin-right:auto;';
		} elseif ( 'right' == $options['content_position'] ) {
			echo 'float:right;';
		} else {
			echo 'margin-left:0;margin-right:0;';
		}
	}

	// content alignment
	if ( ! empty( $options['content_alignment'] ) ) {
		if ( 'right' == $options['content_alignment'] ) {
			echo 'text-align:right;';
		} elseif ( 'center' == $options['content_alignment'] ) {
			echo 'text-align:center;';
		} else {
			echo 'text-align:left;';
		}
	}

	echo '}' . "\r\n";

	// Content alignment for the input field
	if ( ! empty( $options['content_alignment'] ) ) {
		echo '.content input{';
			if ( 'right' == $options['content_alignment'] ) {
				echo 'text-align:right;';
			} elseif ( 'center' == $options['content_alignment'] ) {
				echo 'text-align:center;';
			} else {
				echo 'text-align:left;';
			}
		echo '}' . "\r\n";
	}

}


// If the default form & button styles need to be ignored
if ( '1' == $options['ignore_form_styles'] ) {
	// Input: size, color, background, border
	if ( ! empty( $options['input_font_size'] ) || ! empty( $options['input_font_color'] ) || ! empty( $options['input_bg'] ) || ! empty( $options['input_border'] ) ) {
		echo '.content input[type="text"]{';

		// input font size
		if ( ! empty( $options['input_font_size'] ) ) {
			echo 'font-size:' . esc_attr($options['input_font_size']) . 'px;';
		}

		// input color
		if ( ! empty( $options['input_font_color'] ) ) {
			echo 'color:#' . esc_attr($options['input_font_color']) . ';';
		}

		// input background
		if ( ! empty( $options['input_bg'] ) ) {
			echo 'background:#' . esc_attr($options['input_bg']) . ';';
		}

		// input border
		if ( ! empty( $options['input_border'] ) ) {
			echo 'border:1px solid #' . esc_attr($options['input_border']) . ';';
		}

		echo '}' . "\r\n";
	}

	// Input: background:focus, border:focus
	if ( ! empty( $options['input_bg_hover'] ) || ! empty( $options['input_border_hover'] ) ) {
		echo '.content input[type="text"]:focus{';

		// input background:focus
		if ( ! empty( $options['input_bg_hover'] ) ) {
			echo 'background:#' . esc_attr($options['input_bg_hover'] ). ';';
		}

		// input border:focus
		if ( ! empty( $options['input_border_hover'] ) ) {
			echo 'border:1px solid #' . esc_attr($options['input_border_hover']) . ';';
		}

		echo '}' . "\r\n";
	}

	// Button: size, color, background, border
	if ( ! empty( $options['button_font_size'] ) || ! empty( $options['button_font_color'] ) || ! empty( $options['button_bg'] ) || ! empty( $options['button_border'] ) ) {
		echo '.content input[type="submit"]{';

		// button font size
		if ( ! empty( $options['button_font_size'] ) ) {
			echo 'font-size:' . esc_attr($options['button_font_size']) . 'px;';
		}

		// button color
		if ( ! empty( $options['button_font_color'] ) ) {
			echo 'color:#' . esc_attr($options['button_font_color']) . ';';
		}

		// button background
		if ( ! empty( $options['button_bg'] ) ) {
			echo 'background:#' . esc_attr($options['button_bg']) . ';';
		}

		// button border
		if ( ! empty( $options['button_border'] ) ) {
			echo 'border:1px solid #' . esc_attr($options['button_border']) . ';';
		}

		echo '}' . "\r\n";
	}

	// Button: background:focus, border:focus
	if ( ! empty( $options['button_bg_hover'] ) || ! empty( $options['button_border_hover'] ) ) {
		echo '.content input[type="submit"]:hover,';
		echo '.content input[type="submit"]:focus{';

		// input background:focus
		if ( ! empty( $options['button_bg_hover'] ) ) {
			echo 'background:#' . esc_attr($options['button_bg_hover']) . ';';
		}

		// input border:focus
		if ( ! empty( $options['button_border_hover'] ) ) {
			echo 'border:1px solid #' . esc_attr($options['button_border_hover']) . ';';
		}

		echo '}' . "\r\n";
	}

	// Message: success
	if ( ! empty( $options['success_background'] ) || ! empty( $options['success_color'] ) ) {
		echo '.signals-alert-success{';

		// success background
		if ( ! empty( $options['success_background'] ) ) {
			echo 'background:#' . esc_attr($options['success_background']) . ';';
		}

		// success color
		if ( ! empty( $options['success_color'] ) ) {
			echo 'color:#' . esc_attr($options['success_color']) . ';';
		}

		echo '}' . "\r\n";
	}

	// Message: error
	if ( ! empty( $options['error_background'] ) || ! empty( $options['error_color'] ) ) {
		echo '.signals-alert-danger{';

		// error background
		if ( ! empty( $options['error_background'] ) ) {
			echo 'background:#' . esc_attr($options['error_background']) . ';';
		}

		// error color
		if ( ! empty( $options['error_color'] ) ) {
			echo 'color:#' . esc_attr($options['error_color']) . ';';
		}

		echo '}' . "\r\n";
	}

  echo '::-webkit-input-placeholder {
  color: #' . esc_attr($options['form_placeholder_color']) . ';
}
::-moz-placeholder {
  color: #' . esc_attr($options['form_placeholder_color']) . ';
}
:-ms-input-placeholder {
  color: #' . esc_attr($options['form_placeholder_color']) . ';
}
:-moz-placeholder {
  color: #' . esc_attr($options['form_placeholder_color']) . ';
}';
}


// Custom CSS
if ( ! empty( $options['custom_css'] ) ) {
	CSMM::wp_kses_wf(stripslashes( $options['custom_css']));
}

echo '</style>' . "\r\n";
