<?php
namespace BetterWidgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Better_Map extends Widget_Base {

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
		return 'better-map';
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
		return esc_html__( 'BETTER Map', 'elementor-hello-world' );
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
		return 'eicon-posts-ticker';
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
		return [ 'elementor-hello-world' ];
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
			'section_shortcode',
			[
				'label' => esc_html__( 'Shortcode', 'genesis-core' ),
			]
        );
        
        $this->add_control(
			'section_title',
			[
				'label' => esc_html__( 'Title', 'better-el-addons' ),
				'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your title', 'better-el-addons' ),
				'default' => esc_html__('We Make Creative Solutions', 'better-el-addons' ),
			]
        );

		$this->add_control(
			'section_subtitle',
			[
				'label' => esc_html__( 'Sub-Title Text', 'better-el-addons' ),
				'type' => Controls_Manager::WYSIWYG,
                'placeholder' => esc_html__( 'Enter your sub-title', 'better-el-addons' ),
                'default' => esc_html__('Quisque massa ipsum, luctus at tempus eleifend congue quis
				lectus. Morbi bibendum nisl id
				porttitor ultrices odio elit vestibulum metus, ac semper velit quam sed nulla aenean eu
				hendreritt.', 'better-el-addons' )
			]
		);

		$this->add_control(
			'location_link',
			[
				'label' => esc_html__( 'Map location link', 'genesis-core' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_url( 'http://your-link.com' ),
				'default' => [
					'url' => esc_url('https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d22864.11283411948!2d-73.96468908098944!3d40.630720240038435!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew+York%2C+NY%2C+USA!5e0!3m2!1sen!2sbg!4v1540447494452'),
				],
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Content Style', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
        
        // Title Typography 
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_title_typography',
				'label' => esc_html__( 'Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-about .main-tit h2,{{WRAPPER}} .better-heading.style-4 .img-wrapper .title h3',
			]
		);

		// Sub-Title Typography 
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_sub_title_typography',
				'label' => esc_html__( 'Sub-Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-about .content p,{{WRAPPER}} .better-heading.style-4 .cont h4',
			]
		);

		$this->add_control(
			'better_title_color',
			[
				'label' => esc_html__( 'Title Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-heading.style-4 .img-wrapper .title h3' => '-webkit-text-stroke-color: {{VALUE}}',
                ],
			]
        );

        $this->add_control(
			'better_sub_title_color',
			[
				'label' => esc_html__( 'Sub-Title Accent Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-heading.style-4 .cont h4 .stroke' => '-webkit-text-stroke-color: {{VALUE}}',
				'{{WRAPPER}} .better-heading.style-4 .cont h4' => 'color: {{VALUE}}',
                ],
			]
        );

        $this->end_controls_section();

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
	    // get our input from the widget settings.
	    $settings = $this->get_settings_for_display();
	    ?>
	    <section class="better-heading style-4">
	        <div class="container">
	            <div class="row">
	                <div class="col-lg-7 col-md-9">
	                    <div class="cont">
	                        <h4><?php echo wp_kses_post($settings['section_subtitle']); ?></h4>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <div class="img-wrapper">
	            <div class="title">
	                <div class="container">
	                    <h3><?php echo wp_kses_post($settings['section_title']); ?></h3>
	                </div>
	            </div>
	            <div class="map" id="ieatmaps"><iframe class="shadow" src="<?php echo esc_url($settings['location_link']['url']); ?>" width="100%" height="100%" frameborder="0" allowfullscreen></iframe></div>
	        </div>
	    </section>
	    <?php
	}

}