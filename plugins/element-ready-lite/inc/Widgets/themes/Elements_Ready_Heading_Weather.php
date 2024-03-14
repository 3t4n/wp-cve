<?php

namespace Element_Ready\Widgets\themes;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Custom_Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Element_Ready\Api\Open_Weather_Api as Weather_Api;
if ( ! defined( 'ABSPATH' ) ) exit;

class Elements_Ready_Heading_Weather extends Widget_Base {

    use \Elementor\Element_Ready_Common_Style;
    use \Elementor\Element_ready_common_content;
    use \Elementor\Element_Ready_Box_Style;

    public $base;

    public function get_name() {
        return 'Elements_Ready_Heading_Weather';
    }

    public function get_title() {
        return esc_html__( 'Heading Weather', 'element-ready-lite' );
    }

    public function get_icon() { 
        return "eicon-cloud-check";
    }

   public function get_categories() {
      return [ 'element-ready-addons' ];
   }
   public function get_keywords() {
    return [ 'heading weather', 'temp', 'er weather' ];
   }
   
   public function get_style_depends() {

    wp_register_style( 'eready-weather' , ELEMENT_READY_ROOT_CSS. 'widgets/weather.css' );
    return [ 'eready-weather' ];
  }

    protected function register_controls() {

        $this->start_controls_section(
            'section_layouts_tab',
            [
                'label' => esc_html__('Layout', 'element-ready-lite'),
            ]
        );

        $this->add_control(
			'layout',
			[
				'label' => esc_html__( 'Style', 'element-ready-lite' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'style1',
				'options' => [
					'style1'  => esc_html__( 'Style 1', 'element-ready-lite' ),
				],
			]
		);

       $this->end_controls_section();
       
        $this->content_text([
            'title' => esc_html__('Settings','element-ready-lite'),
            'slug' => '_heading_content',
            'condition' => '',
            'controls' => [
              
                'weather_coordinate'=> [
                    'label'        => esc_html__( 'Coordinate ? ', 'element-ready-lite' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Enable', 'element-ready-lite' ),
                    'label_off'    => esc_html__( 'Diable', 'element-ready-lite' ),
                    'return_value' => 'yes',
                    'default'      => '',
                 ],
                
                'coordinates_lat'=> [
                    'label'   => esc_html__( 'Coordinates latitude', 'element-ready-lite' ),
                    'type'    => \Elementor\Controls_Manager::TEXT,
                    'default' => '',
                    'condition' => [
                        'weather_coordinate' => ['yes']
                    ]
                ], 
                
                'coordinates_lon'=> [
                    'label'   => esc_html__( 'coordinates Longitude', 'element-ready-lite' ),
                    'type'    => \Elementor\Controls_Manager::TEXT,
                    'default' => '',
                    'condition' => [
                        'weather_coordinate' => ['yes']
                    ]
                ],

                 'city_name'=> [
                    'label'   => esc_html__( 'City Name', 'element-ready-lite' ),
                    'type'    => \Elementor\Controls_Manager::TEXT,
                    'default' => 'london',
                    'condition' => [
                        'weather_coordinate!' => ['yes']
                    ]
                 ],

                 
                'units' =>   [
                    'label' => esc_html__( 'Unit', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'standard',
                    'options' => [
                        'standard'  => esc_html__( 'Standard', 'element-ready-lite' ),
                        'metric' => esc_html__( 'Metric', 'element-ready-lite' ),
                        'imperial' => esc_html__( 'Imperial', 'element-ready-lite' ),
                    ],
                    
                ],

                'unit_text'=> [
                    'label'   => esc_html__( 'Unit text', 'element-ready-lite' ),
                    'type'    => \Elementor\Controls_Manager::TEXT,
                    'default' => '°C | °F',
                   
                ],
                 
                 'weather_cache_enable'=> [
                    'label'        => esc_html__( 'Cach ?', 'element-ready-lite' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Show', 'element-ready-lite' ),
                    'label_off'    => esc_html__( 'Hide', 'element-ready-lite' ),
                    'return_value' => 'yes',
                    'default'      => '',
                 ],

				 'weather_icon'=> [
                    'label'        => esc_html__( 'Weather Icon?', 'element-ready-lite' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Yes', 'element-ready-lite' ),
                    'label_off'    => esc_html__( 'No', 'element-ready-lite' ),
                    'return_value' => 'yes',
                    'default'      => '',
                 ],
				 'custom_icon_image'=> [
					'label' => esc_html__( 'Choose Image', 'textdomain' ),
					'type' => \Elementor\Controls_Manager::MEDIA,
					'default' => [
						'url' => ELEMENT_READY_ROOT_IMG .'/temp.png',
					],
                 ],

            ]
         ]);

         $this->start_controls_section(
			'style_location_section',
			[
				'label' => esc_html__( 'Location', 'element-ready-lite' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'location_color',
				[
					'label' => esc_html__( 'Text Color', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .element-ready-weather-area' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'location_typography',
					'selector' => '{{WRAPPER}} .element-ready-weather-area',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'location_text_shadow',
					'label' => esc_html__( 'Text Shadow', 'element-ready-lite' ),
					'selector' => '{{WRAPPER}} .element-ready-weather-area',
				]
			);

		$this->end_controls_section();

         $this->start_controls_section(
			'style_weather_res_section',
			[
				'label' => esc_html__( 'Weather', 'element-ready-lite' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'text_color',
				[
					'label' => esc_html__( 'Text Color', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .element-ready-w-temp_count' => 'color: {{VALUE}}',
					],
				]
			);

          
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'content_typography',
					'selector' => '{{WRAPPER}} .element-ready-w-temp_count',
				]
			);

            $this->add_control(
				'text__unit_color',
				[
					'label' => esc_html__( 'unit Color', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .element-ready-w-temp_count sup' => 'color: {{VALUE}}',
					],
				]
			);

            $this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'content_units_typography',
                    'label' => esc_html__( 'Unit Typho', 'element-ready-lite' ),
					'selector' => '{{WRAPPER}} .element-ready-w-temp_count sup',
				]
			);

            $this->add_control(
				'unit_width_wrapper_padding',
				[
					'label' => esc_html__( 'Unit Padding', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .element-ready-w-temp_count sup' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'date_text_shadow',
					'label' => esc_html__( 'Text Shadow', 'element-ready-lite' ),
					'selector' => '{{WRAPPER}} .element-ready-w-temp_count',
				]
			);

            $this->add_responsive_control(
				'flex_unit_gap',
				[
					'label' => esc_html__( 'Gap', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 5,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 1,
					],
					'selectors' => [
						'{{WRAPPER}} .er-content-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
					],
					

				]
			);

            
            $this->add_responsive_control(
				'_section_direction_section_we_flex_align',
				[
					'label' => esc_html__( 'Layout Direction', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => '',
					'options' => [
						'row'    => esc_html__( 'Row', 'element-ready-lite' ),
						'row-reverse'      => esc_html__( 'Row Reverse', 'element-ready-lite' ),
						'column'        => esc_html__( 'Column', 'element-ready-lite' ),
						'column-reverse'              => esc_html__( 'Column Reverse', 'element-ready-lite' ),
					],

					'selectors' => [
						'{{WRAPPER}} .er-content-wrapper' => 'flex-direction: {{VALUE}};'
				    ],
				]
				
			);

		$this->end_controls_section();

         $this->start_controls_section(
			'style_data_wrapper_section',
			[
				'label' => esc_html__( 'Wrapper', 'element-ready-lite' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'flex_width_gap',
				[
					'label' => esc_html__( 'Gap', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 5,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 10,
					],
					'selectors' => [
						'{{WRAPPER}} .element-ready-weather-inner' => 'gap: {{SIZE}}{{UNIT}};',
					],
					

				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'flex_width_wrapper_background',
					'label' => esc_html__( 'Background', 'element-ready-lite' ),
					'types' => [ 'classic', 'gradient'],
					'selector' => '{{WRAPPER}} .element-ready-weather-inner',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'flex_width_wrapper_border',
					'label' => esc_html__( 'Border', 'element-ready-lite' ),
					'selector' => '{{WRAPPER}} .element-ready-weather-inner',
				]
			);

			$this->add_control(
				'flex_width_wrapper_padding',
				[
					'label' => esc_html__( 'Padding', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .element-ready-weather-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'_section_align_section_w__flex_align',
				[
					'label' => esc_html__( 'Alignment', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => '',
					'options' => [

						'flex-start'    => esc_html__( 'Left', 'element-ready-lite' ),
						'flex-end'      => esc_html__( 'Right', 'element-ready-lite' ),
						'center'        => esc_html__( 'Center', 'element-ready-lite' ),
						''              => esc_html__( 'inherit', 'element-ready-lite' ),
					],

					'selectors' => [
						'{{WRAPPER}} .element-ready-weather-inner' => 'align-items: {{VALUE}};'
				],
				]
				
			);

            $this->add_responsive_control(
				'_section_direction_section_e__flex_align',
				[
					'label' => esc_html__( 'Layout Direction', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => '',
					'options' => [
						'row'    => esc_html__( 'Row', 'element-ready-lite' ),
						'row-reverse'      => esc_html__( 'Row Reverse', 'element-ready-lite' ),
						'column'        => esc_html__( 'Column', 'element-ready-lite' ),
						'column-reverse'              => esc_html__( 'Column Reverse', 'element-ready-lite' ),
					],

					'selectors' => [
						'{{WRAPPER}} .element-ready-weather-inner' => 'flex-direction: {{VALUE}};'
				    ],
				]
				
			);

		$this->end_controls_section();

    
    }

    public function get_weather_icon($icon){

        if($icon == ''){
            return;
        }

        $iconurl = 'http://openweathermap.org/img/w/'.$icon.'.png';

        $this->add_render_attribute(
            'element_img_wrapper',
            [
                'src' => $iconurl,
                'class' => [ 'thumb'],
            ]
        );

        return "<img ". $this->get_render_attribute_string( 'element_img_wrapper' )." />";
    }

    protected function render( ) { 
      
        $settings = $this->get_settings();
        $api_key = element_ready_get_api_option( 'weather_api_key' );
        $options = [
           'api_key'              => $api_key,
           'coordinates_lat'      => $settings['coordinates_lat'],
           'coordinates_lon'      => $settings['coordinates_lon'],
           'city_name'            => $settings['city_name'],
           'weather_cache_enable' => $settings['weather_cache_enable'],
           'weather_coordinate'   => $settings['weather_coordinate'],
           'units'                => $settings['units'],
        ];

        $icon = sprintf('<img src="%s" />', ELEMENT_READY_ROOT_IMG .'/temp.png');
        $weather_data = Weather_Api::get($options);   
        if( isset( $weather_data->weather[0]->icon ) ){
            $icon = $this->get_weather_icon($weather_data->weather[0]->icon);
	    }

        if(isset($settings['custom_icon_image']['url']) && $settings['custom_icon_image']['url'] !=''){
            $icon = sprintf('<img src="%s" />', esc_url($settings['custom_icon_image']['url'] ));
        }

     ?>
		<?php if($settings['layout'] == 'style1'): ?>
			<div class="element-ready-weather-heading-wrapper">
				<div class="element-ready-weather-inner">
				    <?php if($settings['weather_icon'] == 'yes'): ?>	
						<div class="temp_icon">
							<?php echo wp_kses_post($icon); ?>
						</div>
					<?php endif; ?>
                    <div class="er-content-wrapper">
					<?php if(isset($weather_data->main->temp)): ?>
						<div class="element-ready-w-temp_count">
                            <?php echo esc_html($weather_data->main->temp); ?>
                            <sup><?php echo esc_html($settings['unit_text']); ?></sup>
                        </div>
					<?php endif; ?>
					<?php if(isset($weather_data->name)): ?>
						<div class="element-ready-weather-area"> <?php echo esc_html($weather_data->name); ?></div>
					<?php endif; ?>
                    </div>
				</div>
			</div>
		<?php endif; ?>

<?php  
    }
    

    
}
