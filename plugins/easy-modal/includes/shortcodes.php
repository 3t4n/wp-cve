<?php
add_shortcode( 'modal', 'emodal_shortcode_modal');
function emodal_shortcode_modal($atts, $content = NULL)
{
	$atts = shortcode_atts(
		apply_filters('emodal_shortcode_modal_default_atts', array(
			'id' => "",
			'theme_id' => 1,
			'title' => "",
			'overlay_disabled' => 0,
			'size' => "auto",
			'width' => "",
			'widthUnit' => "px",
			'height' => "",
			'heightUnit' => "px",
			'location' => "center top",
			'positionTop' => 100,
			'positionLeft' => 0,
			'positionBottom' => 0,
			'positionRight' => 0,
			'positionFixed' => 0,
			'animation' => "slide",
			'animationSpeed' => 350,
			'animationOrigin' => 'top',
			'overlayClose' => 0,
			'escClose' => 1,
			// Deprecated
			'theme' => NULL,
			'duration' => NULL,
			'direction' => NULL,
			'overlayEscClose' => NULL,
		)),
		apply_filters('emodal_shortcode_modal_atts', $atts)
	);

	$modal_fields = array(
		'id' => $atts['id'],
		'theme_id' => $atts['theme_id'],
		'title' => $atts['title'],
		'content' => $content,
		'meta' => array(
			'display' => array(
				'size' => $atts['size'],
				'overlay_disabled' => $atts['overlay_disabled'],
				'custom_width' => $atts['width'],
				'custom_width_unit' => $atts['widthUnit'],
				'custom_height' => $atts['height'],
				'custom_height_unit' => $atts['heightUnit'],
				'custom_height_auto' => $atts['width'] > 0 ? 0 : 1,
				'location' => $atts['location'],
				'position' => array(
					'top' => $atts['positionTop'],
					'left' => $atts['positionLeft'],
					'bottom' => $atts['positionBottom'],
					'right' => $atts['positionRight'],
					'fixed' => $atts['positionFixed'],
				),
				'animation' => array(
					'type' => $atts['animation'],
					'speed' => $atts['animationSpeed'],
					'origin' => $atts['animationOrigin'],
				),
			),
			'close' => array(
				'overlay_click' => $atts['overlayClose'],
				'esc_press' => $atts['escClose']
			),
		),
	);

	$modal_fields = apply_filters('emodal_shortcode_modal_settings', $modal_fields, $atts);
	$Modal = new EModal_Model_Modal;
	$Modal->set_fields($modal_fields);

	$View = new EModal_View_Modal;
	$View->set('modal', $Modal->as_array());
	return $View->output();
}