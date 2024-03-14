<?php
/* * * Social Icon * * */
function consultco_get_social_icon_default() {
	return apply_filters(
		'consultco_get_social_icon_default', json_encode(
				 array(
				array(
					'icon_value'	  =>  esc_html__( 'fa-facebook', 'icyclub' ),
					'link'	  =>  '#',
					'id'              => 'customizer_repeater_header_social_101',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-twitter', 'icyclub' ),
					'link'	  =>  '#',
					'id'              => 'customizer_repeater_header_social_102',
				),

				array(
					'icon_value'	  =>  esc_html__( 'fa-linkedin', 'icyclub' ),
					'link'	  =>  '#',
					'id'              => 'customizer_repeater_header_social_103',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-instagram', 'icyclub' ),
					'link'	  =>  '#',
					'id'              => 'customizer_repeater_header_social_104',
				),
			)
		)
	);
}




/* * * Service Default * * */
 function consultco_get_service_default() {
	return apply_filters(
		'consultco_get_service_default', json_encode(
				 array(
				array(
					'title'           => esc_html__( 'Business Consulting', 'icyclub' ),
					'text'            => esc_html__( 'We’re the leading consulting explain to you how all this mista ke idea of denouncing pleasure and praising pain was born', 'icyclub' ),
					'icon_value'       => 'fa-hands-helping',
					'id'              => 'customizer_repeater_service_101',
					
				),
				array(
					'title'           => esc_html__( 'Market Analysis', 'icyclub' ),
					'text'            => esc_html__( 'We’re the leading consulting explain to you how all this mista ke idea of denouncing pleasure and praising pain was born', 'icyclub' ),
					'icon_value'       => 'fa-chart-line',
					'id'              => 'customizer_repeater_service_102',				
				),
				array(
					'title'           => esc_html__( 'Financial Planning', 'icyclub' ),
					'text'            => esc_html__( 'We’re the leading consulting explain to you how all this mista ke idea of denouncing pleasure and praising pain was born', 'icyclub' ),
					'icon_value'       => 'fa-briefcase',
					'id'              => 'customizer_repeater_service_103',
				),
			)
		)
	);
}


/* * * Features Default * * */
 function consultco_get_features_default() {
	return apply_filters(
		'consultco_get_features_default', json_encode(
				 array(
				array(
					'title'           => esc_html__( 'Financial Analysis', 'icyclub' ),
					'text'            => esc_html__( 'evolved from generation X is on the runway heading towards', 'icyclub' ),
					'icon_value'       => 'fa-chart-area',
					'id'              => 'customizer_repeater_features_101',
					
				),
				array(
					'title'           => esc_html__( 'Business Growth', 'icyclub' ),
					'text'            => esc_html__( 'evolved from generation X is on the runway heading towards', 'icyclub' ),
					'icon_value'       => 'fa-signal',
					'id'              => 'customizer_repeater_features_102',			
				),
				array(
					'title'           => esc_html__( 'Success Report', 'icyclub' ),
					'text'            => esc_html__( 'evolved from generation X is on the runway heading towards', 'icyclub' ),
					'icon_value'       => 'fa-chart-pie',
					'id'              => 'customizer_repeater_features_103',
				),
				array(
					'title'           => esc_html__( 'Marketing Plan', 'icyclub' ),
					'text'            => esc_html__( 'evolved from generation X is on the runway heading towards', 'icyclub' ),
					'icon_value'       => 'fa-chart-line',
					'id'              => 'customizer_repeater_features_104',
				),
				array(
					'title'           => esc_html__( 'Risk Management', 'icyclub' ),
					'text'            => esc_html__( 'evolved from generation X is on the runway heading towards', 'icyclub' ),
					'icon_value'       => 'fa-file-contract',
					'id'              => 'customizer_repeater_features_105',
				),
				array(
					'title'           => esc_html__( 'Global Business', 'icyclub' ),
					'text'            => esc_html__( 'evolved from generation X is on the runway heading towards', 'icyclub' ),
					'icon_value'       => 'fa-globe-americas',
					'id'              => 'customizer_repeater_features_106',
				),
			)
		)
	);
}