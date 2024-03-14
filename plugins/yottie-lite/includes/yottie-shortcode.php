<?php

if (!defined('ABSPATH')) exit;


// shortcode [yottie]
function yottie_lite_shortcode($atts) {
	global $yottie_lite_defaults;

	foreach ($yottie_lite_defaults as $name => $value) {
		if (isset($atts[$name]) && is_bool($value)) {
			$atts[$name] = !empty($atts[$name]) && $atts[$name] != 'false';
		}
	}

	$options = shortcode_atts($defaults = $yottie_lite_defaults, $atts, 'yottie');

	$api_key = get_option('elfsight_yottie_youtube_api_key', '');

	$result = '<div data-yt';

	foreach ($options as $name => $value) {
		if ($value !== $yottie_lite_defaults[$name]) {

			// boolean
			if (is_bool($value)) {
				$value = $value ? 'true' : 'false';
			}

			// source groups
			if ($name == 'source_groups') {
				$value = json_decode(rawurldecode($value));

				if (!is_array($value)) {
					continue;
				}

				foreach($value as $key => $group) {
					if (empty($group->sources)) {
						unset($value[$key]);
					}
					elseif (is_string($group->sources)) {
						$group->sources = preg_split('/[\s\n]/', $group->sources);
					}
				}
				
				$value = !empty($value) ? rawurlencode(json_encode($value)) : '';
			}
			
			$result .= sprintf(' data-yt-%s="%s"', str_replace('_', '-', $name), esc_attr($value));
		}
	}

	if ($api_key) {
        $result .= ' data-yt-key="' . $api_key . '"';
    }

	$result .= '></div>';

	return $result;
}
add_shortcode('yottie', 'yottie_lite_shortcode');

?>