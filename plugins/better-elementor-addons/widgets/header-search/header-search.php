<?php
namespace BetterWidgets\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Get unique ID. 
function better_unique_id( $prefix = '' ) {
	static $id_counter = 0;
	if ( function_exists( 'wp_unique_id' ) ) {
		return wp_unique_id( $prefix );
	}
	return $prefix . (string) ++$id_counter;
}
		
/**
 * @since 1.0.0
 */
class Better_Header_Search extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'better-header-search';
	}
		//script depend
	public function get_script_depends() { return [ 'better-header-search' ]; }

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Better Header search', 'better-el-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-search';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'better-category' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {
	
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Search Settings', 'better-el-addons' ),
			]
		);
		
		 

        $this->add_control(
            'search_icons',
            [
                'label' => esc_html__('Select Icon', 'better-el-addons'),
                'fa4compatibility' => 'better_search_icon',
				'default' => [
					'value' => 'fa fa-search',
					'library' => 'fa-solid',
				],
                'label_block' => true,
                'type' => Controls_Manager::ICONS,

            ]
        );
		
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => __( 'Content Settings', 'better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		
		$this->add_control(
			'color_icon',
			[
				'label' => __( 'Content Background', 'better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#eee',
				'selectors' => [
					'{{WRAPPER}} .better-header-search-icon a i' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size', 'better-el-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .better-header-search-icon a.search' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		
		$this->end_controls_section();

        $this->start_controls_section(
            'header_search_section_tab_style',
            [
                'label' => esc_html__('Header Search', 'better-el-addons'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        // box shadow
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(), [
                'name'       => 'better_header_search',
                'selector'   => '{{WRAPPER}} .better-header-search-icon a.search',

            ]
        );
        // border radius
        $this->add_control(
            'header_border_radius',
            [
                'label' => esc_html__( 'Border radius', 'better-el-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default' => [
                    'top' => '50',
                    'right' => '50',
                    'bottom' => '50' ,
                    'left' => '50',
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .better-header-search-icon a.search' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'search_margin',
            [
                'label'         => esc_html__('Margin', 'better-el-addons'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em'],
                'default' => [
                    'top' => '5',
                    'right' => '5',
                    'bottom' => '5' ,
                    'left' => '5',
                ],
                'selectors' => [
                    '{{WRAPPER}} .better-header-search-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
			'search_padding',
			[
				'label' => esc_html__( 'Padding', 'better-el-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '0' ,
                    'left' => '0',
                ],
				'selectors' => [
					'{{WRAPPER}} .better-header-search-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


        $this->add_control(
			'better-header-search-icon a.search',
			[
				'label' => esc_html__( 'Use Height Width', 'better-el-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'better-el-addons' ),
				'label_off' => esc_html__( 'Hide', 'better-el-addons' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

        $this->add_responsive_control(
            'search_width',
            [
                'label'         => esc_html__('Width', 'better-el-addons'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em', '%'],
                'default' => [
                    'unit' => 'px',
                    'size' => '40',
                ],
                'selectors' => [
                    '{{WRAPPER}} .better-header-search-icon a.search' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    '.better-header-search-icon a.search' => 'yes'
                ]
            ]
        );
        $this->add_responsive_control(
            'search_height',
            [
                'label'         => esc_html__('Height', 'better-el-addons'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em', '%'],
                'default' => [
                    'unit' => 'px',
                    'size' => '40',
                ],
                'selectors' => [
                    '{{WRAPPER}} .better-header-search-icon a.search' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    '.better-header-search-icon a.search' => 'yes'
                ]
            ]
        );
        $this->add_responsive_control(
            'search_line_height',
            [
                'label'         => esc_html__('Line Height', 'better-el-addons'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em', '%'],
                'default' => [
                    'unit' => 'px',
                    'size' => '40',
                ],
                'selectors' => [
                    '{{WRAPPER}} .better-header-search-icon a.search' => 'line-height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    '.better-header-search-icon a.search' => 'yes'
                ]
            ]
        );
        $this->add_responsive_control(
            'search_icon_text_align',
            [
                'label' => esc_html__( 'Alignment', 'better-el-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'better-el-addons' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'better-el-addons' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'better-el-addons' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .better-header-search-icon' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() { 
	    $settings = $this->get_settings();
	    $this->add_inline_editing_attributes( 'title' );
	    $this->add_inline_editing_attributes( 'text' );
	    
	    ?>
	        

	         <!-- ===================================== 
	        ==== Start Featured -->

	        <div class="better-header-search-icon hidden-xs hidden-sm">
	            <a class="search" href="#">
	                <?php Icons_Manager::render_icon( $settings['search_icons'], [ 'aria-hidden' => 'true' ] );?>
	            </a>
	            <div class="black-search-block">
	                <div class="black-search-table">
	                    <div class="black-search-table-cell">
	                        <div>
	                            <?php $better_unique_id = better_unique_id( 'search-form-' ); ?>
	                            <form role="search" method="get" id="<?php echo esc_attr( $better_unique_id ); ?>" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	                                <input type="search" class="focus-input" placeholder="<?php echo esc_attr__('Type search keyword...','better-el-addons'); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	                                <input type="submit" class="searchsubmit" value="">
	                            </form>
	                        </div>
	                    </div>
	                </div>
	                <div class="close-black-block"><a href="#"><i class="fa fa-times"></i></a></div>
	            </div>
	        </div>

	        <!-- End Featureds ====
	        ======================================= -->
	             
	    <?php 
	    
	}


	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function content_template() {
		
		
	}
}


