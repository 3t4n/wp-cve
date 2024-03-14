<?php
namespace AllEmebdAddon\Widgets;
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
class mixcloud_addon extends Widget_Base {

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
		return 'mixcloud';
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
		return esc_html__( 'mixcloud Player', 'allembed' );
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
		return 'bl_icon fab fa-mixcloud';
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
		return [ 'AllEmbed' ];
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

  //Vedio player Content Settings

    $this->start_controls_section(
        '_section_mixcloud',
        [
            'label' => __( 'mixcloud Content Settings', 'allembed' ),
             
           
        ]
    );

    $this->add_control(
        'mixcloud_link',
        [
            'label' => esc_html__( 'mixcloud Link', 'allembed' ),
            'label_block' => true,
            'type' => Controls_Manager::TEXT,
            'placeholder' => esc_html__( 'https://your-link.com', 'allembed' ),
            'default' =>'https://www.mixcloud.com/widget/iframe/?hide_cover=1&feed=%2Fmonstercat%2F347-monstercat-call-of-the-wild%2F',
       
        ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
        'mixcloud_ttabb', [
            'label' =>esc_html__( 'mixcloud Other Settings', 'allembed' ),
        ]
    );

            $this->add_control(
			'width',
			[
				'label' 		=> esc_html__( 'Width', 'allembed' ),
				'type' 			=> Controls_Manager::SLIDER,
				'size_units' 	=> [ '%', 'px' ],
				'range' 		=> 
				[
					'px' => [
						'min' 	=> 0,
						'max' 	=> 1500,
						'step' 	=> 5,
					],
					'%' => [
						'min' 	=> 0,
						'max' 	=> 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 560,
				],
				'selectors' => [
					'{{WRAPPER}} .mixed iframe' => 'max-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'height',
			[
				'label' => esc_html__( 'Height', 'allembed' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => 
				[
					'px' => [
						'min' 	=> 0,
						'max' 	=> 1500,
						'step' 	=> 5,
					],
					'%' => [
						'min' 	=> 0,
						'max' 	=> 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 120,
				],
				'selectors' => [
					'{{WRAPPER}} .mixed iframe' => 'height: {{SIZE}}{{UNIT}}',
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

		$settings = $this->get_settings_for_display();
        $mixcloud_link    = $settings['mixcloud_link'];
       
	
	?>
	
    <div class="mixed"><iframe src="<?php echo esc_url($mixcloud_link); ?>" frameborder="0" ></iframe></div>

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
	// protected function _content_template() {}
	
}
