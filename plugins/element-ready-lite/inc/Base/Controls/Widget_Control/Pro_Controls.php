<?php

namespace Element_Ready\Base\Controls\Widget_Control;
use Element_Ready\Base\BaseController;
/**
 * @package Element Ready
 */
class Pro_Controls extends BaseController
{
	public function register(){
		add_action('element_ready_go_pro_section', array( $this, 'pro_section' ) );
	}

	public function pro_section( $element ){

		
		$element->start_controls_section(
			'element_ready_sy_custom_sticky_section',
			[
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				'label' => esc_html__( 'PRO SECTION', 'element-ready-lite' ),
			]
		);
		
			$element->add_control(
				'element_ready_sticky',
				[
					'label'        => esc_html__( ' Sticky', 'element-ready-lite' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Enable', 'element-ready-lite' ),
					'label_off'    => esc_html__( 'Disable', 'element-ready-lite' ),
					'return_value' => 'yes',
					'default'      => '',
				]
			);

		$element->end_controls_section();
		
	}

}