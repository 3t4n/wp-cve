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
class Better_Video_Box extends Widget_Base {

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
		return 'better-video-box';
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
		return esc_html__( 'BETTER Video Box', 'elementor-hello-world' );
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
			'better_video_background',
			[
				'label' => __( 'Video Box Background', 'better-el-addons' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
			]
		);

		$this->add_control(
			'better_video_background_mask',
			[
				'label' => __( 'Background Mask', 'avo_plg' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
				
			]
		);

		$this->add_control(
			'better_video_link',
			[
				'label' => esc_html__( 'Video link', 'genesis-core' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_url( 'http://your-link.com' ),
				'default' => [
					'url' => esc_url('https://vimeo.com/127203262'),
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
        <section>
            <div class="container-fluid">
                <div class="better-video-wrapper better-section-padding better-bg-img better-valign"
                    data-background="<?php echo esc_url( $settings['better_video_background']['url'] ) ?>" data-overlay-dark="<?php echo esc_attr($settings['better_video_background_mask']);?>">
                    <div class="better-full-width text-center">
                        <a class="vid" href="<?php echo esc_url($settings['better_video_link']['url']) ?>">
                            <div class="vid-butn">
                                <span class="icon">
                                    <i class="fas fa-play"></i>
                                </span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
       <?php
	}
}