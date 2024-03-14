<?php

namespace Dropp\Components;

class Choose_Location_Button {
	public function render(): string
	{
		return sprintf(
			'<span class="dropp-location__button button">%s</span>',
			esc_html__( 'Choose location', 'dropp-for-woocommerce' )
		);
	}
}
