<?php

namespace Element_Ready\Base\Controls\Widget_Control;
use Elementor\Controls_Manager;

trait Element_ready_common_control {

    public function pro_message($key = 'element_ready_pro_message'){
    
        return [
			'controls'  => [
				$key=> [
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => sprintf( __( 'To get more features <a href="%s" target="_blank">Go Pro</a>', 'element-ready-lite' ), esc_url(ELEMENT_READY_DEMO_URL)),
				], 
			]
		];
      
    }

    public function run_controls( $get_controls ){

        if(is_array($get_controls)){
             
            foreach($get_controls['controls'] as $control_key => $control_item){
             
                if(isset($control_item['responsive'])){
                    $this->add_responsive_control(
                        $control_key,
                        $control_item
                    );
                }else{
                    $this->add_control(
                        $control_key,
                        $control_item
                    );
                }
            }
        }
    }

    public function options_controls($atts) {
        
        $atts_variable = shortcode_atts(
            array(
                'title'     => esc_html__('Heading','element-ready-lite'),
                'slug'      => '_heading_content',
                'condition' => '',
                'controls'  => [
    
                    'widget_content'=> [
                        'label'   => esc_html__( 'Heading Content', 'element-ready-lite' ),
                        'type'    => \Elementor\Controls_Manager::TEXTAREA,
                        'default' => '',
                    ],
                ]
            ), $atts );

        return $atts_variable;
       
    }
    
}