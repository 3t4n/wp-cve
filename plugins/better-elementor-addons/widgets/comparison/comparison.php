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
class Better_Comparison extends Widget_Base {

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
		return 'comparison';
	}
	//script depend 
	public function get_script_depends() { return [ 'jquery.event.move','jquery.twentytwenty','imageLoaded']; }
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
		return __( 'Better Comparison', 'better-el-addons' );
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
		return 'eicon-image-before-after';
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
	 * Register oEmbed widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		// start of the Content tab section
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Comparison Settings', 'bim_plg' ),
			]
		);
		
		
	
		// Team Image
		$this->add_control(
			'better_before_image',
			[
				'label' => esc_html__( 'Choose Image', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'label_block' => true,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);
		// Team Image
		$this->add_control(
			'better_after_image',
			[
				'label' => esc_html__( 'Choose Image', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'label_block' => true,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);
		$this->end_controls_section();
		

	}

	/**
	 * Render oEmbed widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
	    $settings = $this->get_settings_for_display();
	    $better_before_image = esc_url( $settings['better_before_image']['url'] );
	    $better_after_image = esc_url( $settings['better_after_image']['url'] );
	    ?>
	    <div class="better-comparison-image style-1"> 
	        <div class="twentytwenty-container"> 
	            <img src="<?php echo esc_url( $better_before_image ); ?>" alt="img"/>
	            <img src="<?php echo esc_url( $better_after_image ); ?>" alt="img"/>
	        </div>
	    </div>
	    <?php 
	}

}