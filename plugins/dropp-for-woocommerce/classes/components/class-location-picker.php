<?php

namespace Dropp\Components;

use Dropp\Shipping_Method\Dropp;
use WC_Shipping_Rate;

class Location_Picker {
	public function __construct(public ?WC_Shipping_Rate $shipping_rate)
	{
	}

	public function render(): string
	{
		$shipping_rate = $this->shipping_rate;
		$buffer = [];
		$buffer[] = sprintf(
			'<div class="dropp-location" %s style="display:none">',
			$shipping_rate
				? 'data-instance_id="'. esc_attr( $shipping_rate->get_instance_id() ) . '"'
				: ''
		);

		$shipping_method = Dropp::get_instance();
		if (! $shipping_method->location_name_in_label) {
			$location_name = '';
			$location_data = WC()->session->get( 'dropp_session_location' );
			if ( ! empty( $location_data['name'] ) ) {
				$location_name = $location_data['name'];
			}
			$buffer[] = sprintf(
				'<p class="dropp-location__name"%s>%s</p>',
				(empty($location_name) ? ' style="display:none"' : ''),
				esc_html($location_name)
			);
		}

		$button = new Choose_Location_Button;
		$buffer[] = $button->render();
		$buffer[] = '<div class="dropp-error" style="display:none"></div></div>';

		return implode('', $buffer);
	}
}
