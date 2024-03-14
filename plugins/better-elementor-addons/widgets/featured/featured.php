<?php
namespace BetterWidgets\Widgets; 

use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		
/**
 * @since 1.0.0
 */
class Better_Featured extends Widget_Base {

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
		return 'better-featured';
	}

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
		return __( 'Better Featured', 'better_plg' );
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
		return 'eicon-person';
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
		return [ 'better-elements' ];
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
				'label' => __( 'Featured Settings', 'better_plg' ),
			]
		);
		
		$this->add_control(
            'title',
            [
                'label' => __( 'Name', 'better_plg'),
                'type' => Controls_Manager::TEXT,
				'default' => __( 'Featured Name', 'better_plg' ),
				'label_block' => true,
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __( 'Description', 'better_plg'),
                'type' => Controls_Manager::TEXT,
				'label_block' => true,
            ]
        );
		
		
		$this->add_control(
            'image',
            [
                'label' => __( 'Featured Image', 'better_plg' ),
                'type' => Controls_Manager::MEDIA,
				'default' => [
							'url' => Utils::get_placeholder_image_src(),
				],
            ]
        );
		
		
		
		 
		$this->add_control(
			'featured_icon',
			[
				'label' => __( 'Featured Social Icon', 'better_plg' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'icon' => 'fa fa-download',
					],
					[
						'icon' => 'fa fa-book',
					],
					[
						'icon' => 'fa fa-angle-double-right',
					],
				],
				'fields' => [
					[
						'name' => 'link',
						'label' => __( 'Icon link', 'better_plg' ),
						'type' => Controls_Manager::URL,
						'label_block' => true,
						'placeholder' => __( 'Your icon link..', 'better_plg' ),
					],
					[
						'name' => 'mention',
						'label' => __( 'Mention', 'better_plg' ),
						'type' => Controls_Manager::TEXT,
						'default' => __( 'Mention', 'better_plg' ),
						'label_block' => true,
					],
					
					[
						'name' => 'icon',
						'label' => __( 'Icon', 'better_plg' ),
						'type' => Controls_Manager::ICON,
						'label_block' => true,
						'default' => 'fa fa-download',
					],
				],
				'title_field' => '<i class="{{ icon }}"></i> {{{ icon.replace( \'fa fa-\',\'\' ).replace( \'-\',\' \' ).replace( /\b\w/g, function( letter ){ return letter.toUpperCase() } ) }}}',
			]
		);
		
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => __( 'Content Settings', 'better_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		
		
		$this->add_responsive_control(
			'port_content',
			[
				'label' => __( 'Content Margin (on hover)', 'better_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .dbox-relative' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'port_padding',
			[
				'label' => __( 'Content Padding (on hover)', 'better_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .dbox-relative' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'bg_content',
			[
				'label' => __( 'Content Background', 'better_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .dbox-relative' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'content_align',
			[
				'label' => __( 'Alignment', 'better_plg' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'better_plg' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'better_plg' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'better_plg' ),
						'icon' => 'fa fa-align-right',
					]
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .dbox-relative' => 'text-align: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'title_typo',
			[
				'label' => __( 'Title Content Settings', 'better_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'cport_typography',
				'label'     => __( 'Title Typography', 'better_plg' ),
				'selector'  => '{{WRAPPER}} .dbox-relative h3',
			]
		);
		
		$this->add_control(
			'title_cl',
			[
				'label' => __( 'Title Color', 'better_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .dbox-relative h3' => 'color: {{VALUE}};',
				],
			]
		);
		
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'sub_typo',
			[
				'label' => __( 'Text Content Settings', 'better_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'ctext_typography',
				'label'     => __( 'Text Typography', 'better_plg' ),
				'selector'  => '{{WRAPPER}} .dbox-relative p',
			]
		);
		
		$this->add_control(
			'txt_cl',
			[
				'label' => __( 'Text Color', 'better_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .dbox-relative p' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'icon_section_setting',
			[
				'label' => __( 'Icon Settings', 'better_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'better_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .featured-sicon li a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'icon_bg',
			[
				'label' => __( 'Background', 'better_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .featured-sicon li a' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'icon_radius',
			[
				'label' => __( 'Border Radius', 'better_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .featured-sicon li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size', 'better_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' =>0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .featured-sicon li a' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		
		$this->add_responsive_control(
			'icon_padding',
			[
				'label' => __( 'Padding', 'better_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .featured-sicon li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'icon_margin',
			[
				'label' => __( 'Margin', 'better_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .featured-sicon li a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'port_mask',
			[
				'label' => __( 'Mask Settings', 'better_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		
		$this->add_control(
			'mask_color',
			[
				'label' => __( 'Mask Color', 'better_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .port-inner:hover .port-box' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'mask_color_opacity',
			[
				'label' => __( 'Mask Color Opacity(on hover)', 'better_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' =>0,
						'max' => 1,
						'step' =>0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .port-inner:hover .port-box' => 'opacity: {{SIZE}};',
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

	    <!-- Start Featured -->
	    <div class="better-featured style-1">
	        <div class="item">
	            <div class="product-img">
	                <img src="<?php echo esc_url( $settings['image']['url'] ); ?>" alt="">
	            </div>
	            <div class="info">
	                <div>
	                    <h6><?php echo esc_html( $settings['title'] ); ?></h6>
	                    <span><?php echo esc_html( $settings['description'] ); ?></span>
	                    <div class="links">
	                        <?php foreach ( $settings['featured_icon'] as $index => $item ) :
	                            $link_key = 'link_' . $index;
	                            $this->add_render_attribute( $link_key, 'href', esc_url( $item['link']['url'] ) );

	                            if ( $item['link']['is_external'] ) {
	                                $this->add_render_attribute( $link_key, 'target', '_blank' );
	                            }

	                            if ( $item['link']['nofollow'] ) {
	                                $this->add_render_attribute( $link_key, 'rel', 'nofollow' );
	                            }
	                            ?>
	                            <a href='<?php echo esc_url( $item['link']['url'] ); ?>' class="icon">
	                                <i class="<?php echo esc_attr( $item['icon'] ); ?>"></i> 
	                                <div class="icon-text"><?php echo esc_html( $item['mention'] ); ?></div>
	                            </a>
	                        <?php endforeach; ?>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	    <!-- End Featured -->

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


