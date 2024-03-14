<?php
namespace BetterWidgets\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		
/**
 * @since 1.1.0
 */
class Better_Shadow extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'better-shadow';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Shadow', 'better-el-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-square';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.1.0
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
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {

        $this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Main', 'better-el-addons' ),
			]
		);

        $this->add_control(
			'better_shadow_position',
			[
				'label' => __( 'Position', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'initial' => __( 'initial', 'better-el-addons' ),
				],
				'default' => 'initial',
                'selectors' => [
					'{{WRAPPER}}' => 'position: {{VALUE}};',
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
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function render() {
	    $settings = $this->get_settings(); ?>
	     
	    <div class="better-shadows">
	        <img class="shadow sh-left" src="<?php echo esc_url( plugins_url('/assets/img/shadow.png', dirname(__FILE__)) ); ?>" alt="">
	        <img class="shadow sh-right" src="<?php echo esc_url( plugins_url('/assets/img/shadow.png', dirname(__FILE__)) ); ?>" alt="">
	    </div>
	    
	<?php }


	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function content_template() { }
}