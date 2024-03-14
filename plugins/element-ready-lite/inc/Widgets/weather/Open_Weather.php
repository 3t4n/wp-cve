<?php

namespace Element_Ready\Widgets\weather;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Custom_Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Element_Ready\Api\Open_Weather_Api as Weather_Api;

if ( ! defined( 'ABSPATH' ) ) exit;

require_once( ELEMENT_READY_DIR_PATH . '/inc/style_controls/common/common.php' );
require_once( ELEMENT_READY_DIR_PATH . '/inc/style_controls/position/position.php' );
require_once( ELEMENT_READY_DIR_PATH . '/inc/style_controls/box/box_style.php' );
require_once( ELEMENT_READY_DIR_PATH . '/inc/content_controls/common.php' );

class Open_Weather extends Widget_Base {

    use \Elementor\Element_Ready_Common_Style;
    use \Elementor\Element_ready_common_content;
    use \Elementor\Element_Ready_Box_Style;
    public $base;
    public function get_name() {
        return 'element-ready-open-weather';
    }

    public function get_title() {
        return esc_html__( 'ER Weather', 'element-ready-lite' );
    }

    public function get_icon() { 
        return "eicon-cloud-check";
    }

   public function get_categories() {
      return [ 'element-ready-addons' ];
   }
   public function get_keywords() {
    return [ 'weather', 'temp', 'er weather' ];
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
					'style2' => esc_html__( 'Style 2', 'element-ready-lite' ),
					'style3' => esc_html__( 'Style 3', 'element-ready-lite' ),
					'style4' => esc_html__( 'Style 4', 'element-ready-lite' ),
					
				
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
                    'label'        => esc_html__( ' Coordinate ? ', 'element-ready-lite' ),
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
                 
                 'weather_cache_enable'=> [
                    'label'        => esc_html__( 'Cach ?', 'element-ready-lite' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Show', 'element-ready-lite' ),
                    'label_off'    => esc_html__( 'Hide', 'element-ready-lite' ),
                    'return_value' => 'yes',
                    'default'      => '',
                 ],

         
            ]
         ]);
      
         $this->content_text([
            'title' => esc_html__('Content Settings','element-ready-lite'),
            'slug' => '_content',
            'condition' => '',
            'controls' => [
           
              

                'show_time'=> [
                    'label'        => esc_html__( 'Time Enable', 'element-ready-lite' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Show', 'element-ready-lite' ),
                    'label_off'    => esc_html__( 'Hide', 'element-ready-lite' ),
                    'return_value' => 'yes',
                    'default'      => 'yes',
                 ],

                'time_title'=> [
                    'label'   => esc_html__( 'Time', 'element-ready-lite' ),
                    'type'    => \Elementor\Controls_Manager::TEXT,
                    'default' => esc_html__('Today','element-ready-lite'),
                    'condition' =>[
                        'show_time' => ['yes']
                    ]
                ],

               
                'humidity_show'=> [
                    'label'        => esc_html__( 'Humidity', 'element-ready-lite' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Show', 'element-ready-lite' ),
                    'label_off'    => esc_html__( 'Hide', 'element-ready-lite' ),
                    'return_value' => 'yes',
                    'default'      => 'yes',
                ],
                'humidity_title'=> [
                    'label'   => esc_html__( 'Humidity', 'element-ready-lite' ),
                    'type'    => \Elementor\Controls_Manager::TEXT,
                    'default' => esc_html__('Humidity','element-ready-lite'),
                    'condition' =>[
                        'humidity_show' => ['yes']
                    ]
                ],

                
                'wind_speed_show'=> [
                    'label'        => esc_html__( 'Wind Speed', 'element-ready-lite' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Show', 'element-ready-lite' ),
                    'label_off'    => esc_html__( 'Hide', 'element-ready-lite' ),
                    'return_value' => 'yes',
                    'default'      => 'yes',
                ],
                'wind_speed_title'=> [
                    'label'   => esc_html__( 'Wind Speed', 'element-ready-lite' ),
                    'type'    => \Elementor\Controls_Manager::TEXT,
                    'default' => esc_html__('Speed','element-ready-lite'),
                    'condition' =>[
                        'wind_speed_show' => ['yes']
                    ]
                ],

                'wind_deg_show'=> [
                    'label'        => esc_html__( 'Wind deg', 'element-ready-lite' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Show', 'element-ready-lite' ),
                    'label_off'    => esc_html__( 'Hide', 'element-ready-lite' ),
                    'return_value' => 'yes',
                    'default'      => 'yes',
                ],
                'wind_deg_title'=> [
                    'label'   => esc_html__( 'Wind Deg', 'element-ready-lite' ),
                    'type'    => \Elementor\Controls_Manager::TEXT,
                    'default' => esc_html__('Deg','element-ready-lite'),
                    'condition' =>[
                        'wind_deg_show' => ['yes']
                    ]
                ],

                'show_weather_icon'=> [
                    'label'        => esc_html__( 'Icon Enable', 'element-ready-lite' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Show', 'element-ready-lite' ),
                    'label_off'    => esc_html__( 'Hide', 'element-ready-lite' ),
                    'return_value' => 'yes',
                    'default'      => 'yes',
                ],

                'show_weather_desc'=> [
                    'label'        => esc_html__( 'Desc Enable', 'element-ready-lite' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Show', 'element-ready-lite' ),
                    'label_off'    => esc_html__( 'Hide', 'element-ready-lite' ),
                    'return_value' => 'yes',
                    'default'      => 'yes',
                ],

                'show_weather_slide'=> [
                    'label'        => esc_html__( 'Slide Enable', 'element-ready-lite' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Show', 'element-ready-lite' ),
                    'label_off'    => esc_html__( 'Hide', 'element-ready-lite' ),
                    'return_value' => 'yes',
                    'default'      => 'yes',
                ],


   
         
            ]
         ]);

         $this->content_text([
            'title' => esc_html__('Content Order','element-ready-lite'),
            'slug' => 'order_content_item',
            'condition' => '',
            'controls' => [
           
                'time_order'=> [
                    'label'   => esc_html__( 'Time Order', 'element-ready-lite' ),
                    'type'    => \Elementor\Controls_Manager::NUMBER,
                    'min' => -100,
				    'max' => 100,
				    'step' => 5,
				    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .element-ready-weather-time' => 'order: {{VALUE}};',
                      
                     ],
                ],

                
                'icon_order'=> [
                    'label'   => esc_html__( 'Icon Order', 'element-ready-lite' ),
                    'type'    => \Elementor\Controls_Manager::NUMBER,
                    'min' => -100,
				    'max' => 100,
				    'step' => 5,
				    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .element-ready-weather-icon' => 'order: {{VALUE}};',
                      
                     ],
                ],
                
                'desc_order'=> [
                    'label'   => esc_html__( 'Desc Order', 'element-ready-lite' ),
                    'type'    => \Elementor\Controls_Manager::NUMBER,
                    'min' => -100,
				    'max' => 100,
				    'step' => 5,
				    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .element-ready-weather-desc' => 'order: {{VALUE}};',
                      
                     ],
                ],

                
                'temp_order'=> [
                    'label'   => esc_html__( 'Tempareture Order', 'element-ready-lite' ),
                    'type'    => \Elementor\Controls_Manager::NUMBER,
                    'min' => -100,
				    'max' => 100,
				    'step' => 5,
				    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .element-ready-weather-temp' => 'order: {{VALUE}};',
                      
                     ],
                ],
         
            ]
         ]);
        // 
        
        $this->box_css(
            array(
                'title' => esc_html__('Header','element-ready-lite'),
                'slug' => 'wrapper_header_box_style',
                'element_name' => 'wrapper_header_element_ready_',
                'selector' => '{{WRAPPER}} .element-ready-weather-wrapper .element-ready-weather-topbar',
                'condition' =>[
                    'layout' => ['style4']
                ] 
               
            )
        );
        
        $this->text_wrapper_css(
            array(
                'title' => esc_html__('Time Wrapper','element-ready-lite'),
                'slug' => '_time_box_style',
                'element_name' => '_time_wrapper_element_ready_',
                'selector' => '{{WRAPPER}} .element-ready-weather-time',
            )
        ); 

        $this->text_css(
            array(
                'title' => esc_html__('Time','element-ready-lite'),
                'slug' => '_time_text_style',
                'element_name' => '_time_element_ready_',
                'selector' => '{{WRAPPER}} .element-ready-weather-time h5',
                'hover_selector' => '{{WRAPPER}} .element-ready-weather-time:hover h5',
            )
        ); 

        $this->text_wrapper_css(
            array(
                'title' => esc_html__('Weather Desc','element-ready-lite'),
                'slug' => '_time_desc_text_style',
                'element_name' => '_time_edesc_element_ready_',
                'selector' => '{{WRAPPER}} .element-ready-weather-desc',
                'hover_selector' => false,
            )
        ); 

        $this->box_css(
            array(
                'title' => esc_html__('Main Wrapper','element-ready-lite'),
                'slug' => 'wrapper_body_box_style',
                'element_name' => 'wrapper_body_element_ready_',
                'selector' => '{{WRAPPER}} .element-ready-weather-wrapper',
               
               
            )
        );
       
    }

    public function get_weather_icon($icon){

        if($icon == ''){
            return;
        }
        
        $iconurl = 'http://openweathermap.org/img/w/'.$icon.'.png';
        $this->add_render_attribute(
            'element_img_wrapper',
            [
                'src' => esc_url($iconurl),
                'class' => [ 'thumb'],
            ]
        );
        return "<img ". $this->get_render_attribute_string( 'element_img_wrapper' )." />";
    }

    protected function render( ) { 
      
        $settings = $this->get_settings();
        $api_key = element_ready_get_api_option( 'weather_api_key' );
        $options = [
           'api_key'              => esc_attr($api_key),
           'coordinates_lat'      => esc_attr($settings['coordinates_lat']),
           'coordinates_lon'      => esc_attr($settings['coordinates_lon']),
           'city_name'            => esc_attr($settings['city_name']),
           'weather_cache_enable' => esc_attr($settings['weather_cache_enable']),
           'weather_coordinate'   => esc_attr($settings['weather_coordinate']),
           'units'                => $settings['units'],
        ];

        $icon = '';
        $data = Weather_Api::get($options);   
        if( isset( $data->weather[0]->icon ) ){
            $icon = $this->get_weather_icon($data->weather[0]->icon);
        }
       
     ?>
<?php if($settings['layout'] == 'style1'): ?>
    <div class="element-ready-weather-wrapper">
        <div class="element-ready-weather-time">
            <h5><?php echo esc_html($settings['time_title']);  ?></h5>
        </div>
        <?php if( $settings['show_weather_icon'] =='yes' && $icon !=''): ?>
            <div class="element-ready-weather-icon">
                <?php echo wp_kses_post($icon); ?>
            </div>
        <?php endif; ?>
    <?php if( $settings['show_weather_desc'] && isset($data->weather[0]->description) ): ?>
            <div class="element-ready-weather-desc">
                <?php echo esc_html($data->weather[0]->description) ?>
            </div>
        <?php endif; ?>
        <div class="element-ready-weather-temp">
            <?php if(isset($data->main)): ?>
                <div class="temp">
                    <?php echo esc_html($data->main->temp); ?>
                    <span>
                        <?php if($settings['units'] == 'metric'): ?>
                            <?php echo esc_html__('°C', 'element-ready-lite'); ?>
                        <?php elseif($settings['units'] == 'imperial'): ?>
                            <?php echo esc_html__('°F', 'element-ready-lite'); ?>
                        <?php else: ?>
                            <?php echo esc_html__('K', 'element-ready-lite'); ?>
                        <?php endif; ?>
                    </span>
                </div>
            <?php if($settings['humidity_show'] =='yes'): ?>
            <div class="humidity">
                <?php if($settings['humidity_title'] !=''): ?>
                <span><?php echo esc_html( $settings['humidity_title'] ); ?></span>
                <?php endif; ?>
                <?php echo esc_html($data->main->humidity); ?>
            </div>
            <?php endif; ?>
            <?php if($settings['wind_speed_show'] =='yes'): ?>
                <div class="wind-speed">
                    <?php if($settings['wind_speed_title'] !=''): ?>
                    <span><?php echo esc_html( $settings['wind_speed_title'] ); ?> </span>
                    <?php endif; ?>
                    <?php echo esc_html($data->wind->speed); ?>
                </div>
            <?php endif; ?>
            <?php if($settings['wind_deg_show'] =='yes'): ?>
                <div class="wind-deg">
                    <?php if($settings['wind_deg_title'] !=''): ?>
                        <span><?php echo esc_html( $settings['wind_deg_title'] ); ?> </span>
                    <?php endif; ?>
                    <?php echo esc_html($data->wind->deg); ?>
                </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
<?php if($settings['layout'] == 'style2'): ?>
    <div class="element-ready-weather-wrapper style2">
        <div class="element-ready-weather-time">
            <h5><?php echo esc_html($settings['time_title']);  ?></h5>
        </div>
        <?php if( $settings['show_weather_icon'] =='yes' && $icon !=''): ?>
        <div class="element-ready-weather-icon">
            <?php echo wp_kses_post($icon); ?>
        </div>
        <?php endif; ?>
    <?php if( $settings['show_weather_desc'] && isset($data->weather[0]->description) ): ?>
        <div class="element-ready-weather-desc">
            <?php echo esc_html($data->weather[0]->description) ?>
        </div>
        <?php endif; ?>
        <div class="element-ready-weather-temp">
            <?php if(isset($data->main)): ?>
                <div class="temp">
                    <?php echo esc_html($data->main->temp); ?>
                    <span>
                        <?php if($settings['units'] == 'metric'): ?>
                         <?php echo esc_html__('°C', 'element-ready-lite'); ?>
                        <?php elseif($settings['units'] == 'imperial'): ?>
                          <?php echo esc_html__('°F', 'element-ready-lite'); ?>
                        <?php else: ?>
                            <?php echo esc_html__('K', 'element-ready-lite'); ?>
                        <?php endif; ?>
                    </span>
                </div>
            <?php if($settings['humidity_show'] =='yes'): ?>
                <div class="humidity">
                    <?php if($settings['humidity_title'] !=''): ?>
                    <span> <?php echo esc_html( $settings['humidity_title'] ); ?> </span>
                    <?php endif; ?>
                    <?php echo esc_html($data->main->humidity); ?>
                </div>
            <?php endif; ?>
            <?php if($settings['wind_speed_show'] =='yes'): ?>
                <div class="wind-speed">
                    <?php if($settings['wind_speed_title'] !=''): ?>
                    <span> <?php echo esc_html( $settings['wind_speed_title'] ); ?> </span>
                    <?php endif; ?>
                    <?php echo esc_html($data->wind->speed); ?>
                </div>
            <?php endif; ?>
                <?php if($settings['wind_deg_show'] =='yes'): ?>
                    <div class="wind-deg">
                        <?php if($settings['wind_deg_title'] !=''): ?>
                        <span><?php echo esc_html( $settings['wind_deg_title'] ); ?> </span>
                        <?php endif; ?>
                        <?php echo esc_html($data->wind->deg); ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
<?php if($settings['layout'] == 'style3'): ?>
    <div class="element-ready-weather-wrapper style3">
            <div class="element-ready-weather-time">
                <h5><?php echo esc_html($settings['time_title']);  ?></h5>
            </div>
        <?php if( $settings['show_weather_icon'] =='yes' && $icon !=''): ?>
            <div class="element-ready-weather-icon">
                <?php echo wp_kses_post($icon); ?>
            </div>
        <?php endif; ?>
        <?php if( $settings['show_weather_desc'] && isset($data->weather[0]->description) ): ?>
                <div class="element-ready-weather-desc">
                    <?php echo esc_html($data->weather[0]->description) ?>
                </div>
        <?php endif; ?>
        <div class="element-ready-weather-temp">
            <?php if(isset($data->main)): ?>
                <div class="temp">
                    <?php echo esc_html($data->main->temp); ?>
                    <span>
                        <?php if($settings['units'] == 'metric'): ?>
                            <?php echo esc_html__('°C','element-ready-lite'); ?>
                        <?php elseif($settings['units'] == 'imperial'): ?>
                            <?php echo esc_html__('°F','element-ready-lite'); ?>
                        <?php else: ?>
                            <?php echo esc_html__('K','element-ready-lite'); ?>
                        <?php endif; ?>
                    </span>
                </div>
                <?php if($settings['humidity_show'] =='yes'): ?>
                    <div class="humidity">
                        <?php if($settings['humidity_title'] !=''): ?>
                            <span><?php echo esc_html( $settings['humidity_title'] ); ?></span>
                        <?php endif; ?>
                        <?php echo esc_html($data->main->humidity); ?>
                    </div>
                <?php endif; ?>
                <?php if($settings['wind_speed_show'] =='yes'): ?>
                    <div class="wind-speed">
                        <?php if($settings['wind_speed_title'] !=''): ?>
                          <span><?php echo esc_html( $settings['wind_speed_title'] ); ?> </span>
                        <?php endif; ?>
                        <?php echo esc_html($data->wind->speed); ?>
                    </div>
                <?php endif; ?>
                <?php if($settings['wind_deg_show'] =='yes'): ?>
                    <div class="wind-deg">
                        <?php if($settings['wind_deg_title'] !=''): ?>
                        <span><?php echo esc_html( $settings['wind_deg_title'] ); ?></span>
                        <?php endif; ?>
                        <?php echo esc_html($data->wind->deg); ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
<?php if($settings['layout'] == 'style4'): ?>
    <div class="element-ready-weather-wrapper style4">
        <div class="element-ready-weather-topbar">
            <div class="element-ready-weather-time">
                <h5> <?php echo esc_html($settings['time_title']);  ?> </h5>
            </div>
        <?php if( $settings['show_weather_desc'] && isset($data->weather[0]->description) ): ?>
            <div class="element-ready-weather-desc">
                <?php echo esc_html($data->weather[0]->description) ?>
            </div>
            <?php endif; ?>
            <?php if( $settings['show_weather_icon'] =='yes' && $icon !=''): ?>
            <div class="element-ready-weather-icon">
                <?php echo wp_kses_post($icon); ?>
            </div>
            <?php endif; ?>
        </div>
        <div class="element-ready-weather-temp">
            <?php if(isset($data->main)): ?>
                <div class="temp">
                    <?php echo esc_html($data->main->temp); ?>
                    <span>
                        <?php if($settings['units'] == 'metric'): ?>
                            <?php echo esc_html__('°C','element-ready-lite'); ?>
                        <?php elseif($settings['units'] == 'imperial'): ?>
                        <?php echo esc_html__('°F','element-ready-lite'); ?>
                        <?php else: ?>
                            <?php echo esc_html__('K','element-ready-lite'); ?>
                        <?php endif; ?>
                    </span>
                </div>
                <?php if($settings['humidity_show'] =='yes'): ?>
                    <div class="humidity">
                        <?php if($settings['humidity_title'] !=''): ?>
                            <span><?php echo esc_html( $settings['humidity_title'] ); ?> </span>
                        <?php endif; ?>
                        <?php echo esc_html($data->main->humidity); ?>
                    </div>
                <?php endif; ?>
                <?php if($settings['wind_speed_show'] =='yes'): ?>
                    <div class="wind-speed">
                        <?php if($settings['wind_speed_title'] !=''): ?>
                            <span> <?php echo esc_html( $settings['wind_speed_title'] ); ?> </span>
                        <?php endif; ?>
                        <?php echo esc_html($data->wind->speed); ?>
                    </div>
                <?php endif; ?>
                <?php if($settings['wind_deg_show'] =='yes'): ?>
                    <div class="wind-deg">
                        <?php if($settings['wind_deg_title'] !=''): ?>
                            <span><?php echo esc_html( $settings['wind_deg_title'] ); ?> </span>
                        <?php endif; ?>
                        <?php echo esc_html($data->wind->deg); ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

    </div>
<?php endif; ?>
<?php  
    }
   
}
