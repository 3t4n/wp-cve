<?php
namespace BetterWidgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Better_Image_Box extends Widget_Base {

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
		return 'aee-image-box';
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
		return esc_html__( 'BETTER Image Box', 'better-el-addons' );
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
		return 'eicon-image-box';
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
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'better-el-addons' ];
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

		// start of the Content tab section
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		// Image Box Title
		$this->add_control(
			'better_image_box_number',
			[
				'label' => esc_html__( 'Number', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( '1' ),
			]
		);

		// Image Box Image
		$this->add_control(
			'better_image_box_image',
			[
				'label' => esc_html__( 'Choose Image', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'label_block' => true,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		// Image Box Title
		$this->add_control(
			'better_image_box_title',
			[
				'label' => esc_html__( 'Title', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Better Image box' ),
			]
		);

		// Image Box Description
		$this->add_control(
			'better_image_box_des',
			[
				'label' => esc_html__( 'Description', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'write your profissional text here and you can styling and customize it form style or advanced tabs or check documentation for more details.' ),
			]
		);
		$this->add_control(
			'btn_text',
			[
				'label' => __( 'Button Text','bim_plg' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'label_block' => true,
				'placeholder' => 'Insert your button text here..',
			]
		);
		
		$this->add_control(
			'link',
			[
				'label' => __( 'Button Link','bim_plg' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'Leave it blank if you don\'t want to use this button',
			]
		);
		
		$this->add_control(
			'icon_btn',
			[
				'label' => __( 'Button Icon', 'bim_plg' ),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
				'default' => '',
				'condition' => [
					'link!' => '',
				],
			]
		);

		$this->end_controls_section();
		// end of the Content tab section
//---------------------------------

		$this->start_controls_section(
			'section_box_style',
			[
				'label' => __( 'Box Settings', 'bim_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'box_Padding',
			[
				'label' => __( 'Box Padding', 'bim_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .better-single-image-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Image Box Alignment
		$this->add_responsive_control(
			'better_all_box_alignment',
			[
				'label' => esc_html__( 'Alignment', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'better-el-addons' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'better-el-addons' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'better-el-addons' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .better-single-image-box.style-1' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
//--------------------------------------
//---------------------------------

		$this->start_controls_section(
			'section_number_style',
			[
				'label' => __( 'Number Settings', 'bim_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		// Image Box Title Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_image_box_number_typography',
				'label' => esc_html__( 'Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-single-image-box h2',
			]
		);

		$this->add_responsive_control(
			'number_margin',
			[
				'label' => __( 'Number Margin', 'bim_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .better-single-image-box h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'number_style' );
				$this->start_controls_tab(
			'number_normal',
			[
				'label' => __( 'Normal', 'bim_plg' ),
			]
		);

		$this->add_control(
			'number_color_normal',
			[
				'label' => __( 'Number color.','bim_plg' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .better-single-image-box h2' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'number_color_stroke-normal',
			[
				'label' => __( 'Number color stroke','bim_plg' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .better-single-image-box h2' => '-webkit-text-stroke-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'number_width_stroke-normal',
			[
				'label' => __( 'Number stroke width','bim_plg' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .better-single-image-box h2' => '-webkit-text-stroke-width:{{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'number_hover',
			[
				'label' => __( 'Hover', 'bim_plg' ),
			]
		);
		$this->add_control(
			'number_color_hover',
			[
				'label' => esc_html__( 'Number color','better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .better-single-image-box:hover h2' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
//--------------------------------------

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => __( 'Title Settings', 'bim_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'title_style' );
				$this->start_controls_tab(
			'title_normal',
			[
				'label' => __( 'Normal', 'bim_plg' ),
			]
		);

		$this->add_control(
			'title_color_normal',
			[
				'label' => __( 'Title color.','bim_plg' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .better-single-image-box h4' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'title_hover',
			[
				'label' => __( 'Hover', 'bim_plg' ),
			]
		);
		$this->add_control(
			'title_color_hover',
			[
				'label' => esc_html__( 'Title color','better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .better-single-image-box:hover h4' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		// Image Box Title Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_image_box_title_typography',
				'label' => esc_html__( 'Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-single-image-box h4',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => __( 'Title Margin', 'bim_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .better-single-image-box h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


//----------------------------------------------

		$this->start_controls_section(
			'section_paragraph_style',
			[
				'label' => __( 'Paragraph Settings', 'bim_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'paragraph_style' );
				$this->start_controls_tab(
			'paragraph_normal',
			[
				'label' => __( 'Normal', 'bim_plg' ),
			]
		);

		$this->add_control(
			'paragraph_color_normal',
			[
				'label' => __( 'Paragraph color.','bim_plg' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .better-single-image-box p' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'paragraph_hover',
			[
				'label' => __( 'Hover', 'bim_plg' ),
			]
		);
		$this->add_control(
			'paragraph_color_hover',
			[
				'label' => esc_html__( 'Title color','better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .better-single-image-box:hover p' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		// Image Box Title Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'paragraph_typography',
				'label' => esc_html__( 'Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-single-image-box p',
			]
		);

		$this->add_responsive_control(
			'paragraph_margin',
			[
				'label' => __( 'Paragraph Margin', 'bim_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .better-single-image-box p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
//----------------------------------------------------------------------------------------

		$this->start_controls_section(
			'section_btn_style',
			[
				'label' => __( 'Button Settings', 'bim_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		// Image Box Title Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
				'label' => esc_html__( 'Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-single-image-box a',
			]
		);

		$this->add_responsive_control(
			'btn_margin',
			[
				'label' => __( 'Button Margin', 'bim_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .better-single-image-box a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'btn_padding',
			[
				'label' => __( 'Button Padding', 'bim_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .better-single-image-box a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'btn_display',
			[
				'label' => __( 'Display', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inline-block',
				'options' => [
					'inline-block' => __( 'Inline-block', 'elementor' ),
					'block' => __( 'Blok', 'elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .better-single-image-box a' => 'display: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'btn_style' );
				$this->start_controls_tab(
			'btn_normal',
			[
				'label' => __( 'Normal', 'bim_plg' ),
			]
		);

		$this->add_control(
			'btn_color_normal',
			[
				'label' => __( 'Button color.','bim_plg' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .better-single-image-box a' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'btn_hover',
			[
				'label' => __( 'Hover', 'bim_plg' ),
			]
		);
		$this->add_control(
			'btn_color_hover',
			[
				'label' => esc_html__( 'Button color','better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .better-single-image-box:hover a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();


		$this->end_controls_section();
//----------------------------------------------------------------------------------------
		// start of the Style tab section
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Image Style', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Image Box Image Image Options
		$this->add_control(
			'better_image_box_image_options',
			[
				'label' => esc_html__( 'Image.', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Image Box Image Size
		$this->add_responsive_control(
			'better_image_size',
			[
				'label' => esc_html__( 'Image Size (%)', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .better-single-image-box img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
 

		// Image Box Alignment Options
		$this->add_control(
			'better_image_box_options',
			[
				'label' => esc_html__( 'Alignment', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Image Box Alignment
		$this->add_responsive_control(
			'better_image_box_alignment',
			[
				'label' => esc_html__( 'Alignment', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'better-el-addons' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'better-el-addons' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'better-el-addons' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .better-single-image-box' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tabs();

		$this->end_controls_section();
		// end of the Style tab section
	}

	/**
	 * Render about us widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
	    // Get input from the widget settings.
	    $settings = $this->get_settings_for_display();
	    $better_image_box_number = $settings['better_image_box_number'];
	    $better_image_box_image = $settings['better_image_box_image']['url'];
	    $better_image_box_title = $settings['better_image_box_title'];
	    $better_image_box_des = $settings['better_image_box_des'];
	    ?>
	    <div class="better-single-image-box style-1">
	        <?php if (!empty($better_image_box_number)) : ?>
	            <h2><?php echo esc_html($better_image_box_number); ?></h2>
	        <?php endif; ?>
	        <?php if ($better_image_box_image) : ?>
	            <img src="<?php echo esc_url($better_image_box_image); ?>" alt="">
	        <?php endif; ?>
	        <h4><?php echo esc_html($better_image_box_title); ?></h4>
	        <p><?php echo esc_html($better_image_box_des); ?></p>
	        <?php if ($settings['btn_text'] && $settings['link']['url']) : ?>
	            <a class="feature-btn" href="<?php echo esc_url($settings['link']['url']); ?>">
	                <?php if (!empty($settings['icon_btn'])) : ?>
	                    <span <?php echo $this->get_render_attribute_string('icon-align'); ?>>
	                        <i class="<?php echo esc_attr($settings['icon_btn']); ?>" aria-hidden="true"></i>
	                    </span>
	                <?php endif; ?>
	                <?php echo esc_html($settings['btn_text']); ?>
	            </a>
	        <?php endif; ?>
	    </div>
	<?php
	}

}