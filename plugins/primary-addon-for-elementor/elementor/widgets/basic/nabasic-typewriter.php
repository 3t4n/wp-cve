<?php
/*
 * Elementor Primary Addon for Elementor Typewriter Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'pafe_bw_settings' )['napafe_typewriter'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Primary_Addon_Typewriter extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'prim_basic_typewriter';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Typewriter', 'primary-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-animation-text';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['prim-basic-category'];
	}

	/**
	 * Register Primary Addon for Elementor Typewriter widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		$this->start_controls_section(
			'section_typewriter',
			[
				'label' => __( ' Options', 'primary-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'before_title',
			[
				'label' => esc_html__( 'Before Animation Title', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'This is an ', 'primary-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title here', 'primary-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater = new Repeater();

		$repeater->add_control(
			'animation_text',
			[
				'label' => esc_html__( 'Text', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Amazing...', 'primary-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'animation_groups',
			[
				'label' => esc_html__( 'Animation Text', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'animation_text' => esc_html__( 'Amazing...', 'primary-addon-for-elementor' ),
					],

				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ animation_text }}}',
			]
		);
		$this->add_control(
			'cursorChar',
			[
				'label' => esc_html__( 'Animation Text Cursor', 'primary-addon-for-elementor' ),
				'default' => esc_html__( '|', 'primary-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Enter Cursor here', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);
		$this->add_control(
			'after_title',
			[
				'label' => esc_html__( 'After Animation Title', 'primary-addon-for-elementor' ),
				'default' => esc_html__( ' Heading', 'primary-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title here', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);
		$this->add_responsive_control(
			'content_alignment',
			[
				'label' => esc_html__( 'Content Alignment', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'primary-addon-for-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'primary-addon-for-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'primary-addon-for-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .napae-typewriter' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'typeSpeed',
			[
				'label' => esc_html__( 'Type Speed', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 1500,
				'step' => 1,
				'default' => 100,
				'description' => esc_html__( 'Set the typing speed.', 'primary-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'backSpeed',
			[
				'label' => esc_html__( 'Back Speed', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 1500,
				'step' => 1,
				'default' => 100,
				'description' => esc_html__( 'Set the back speed.', 'primary-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'startDelay',
			[
				'label' => esc_html__( 'Start Delay', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 1500,
				'step' => 1,
				'default' => 100,
				'description' => esc_html__( 'Set the starting delay.', 'primary-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'backDelay',
			[
				'label' => esc_html__( 'Back Delay', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 1500,
				'step' => 1,
				'default' => 100,
				'description' => esc_html__( 'Set the back delay.', 'primary-addon-for-elementor' ),
			]
		);
		$this->end_controls_section();// end: Section

		// Style
		// Animation Title
		$this->start_controls_section(
			'section_anim_title_style',
			[
				'label' => esc_html__( 'Animation Title', 'primary-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Title Typography', 'primary-addon-for-elementor' ),
				'name' => 'sasban_title_typography',
				'selector' => '{{WRAPPER}} .napae-typewriter h1',
			]
		);
		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Title Color', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .napae-typewriter h1' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Animated Title Typography', 'primary-addon-for-elementor' ),
				'name' => 'sasban_anim_title_typography',
				'selector' => '{{WRAPPER}} .napae-typewriter h1 span',
			]
		);
		$this->add_control(
			'anim_title_color',
			[
				'label' => esc_html__( 'Animated Title Color', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .napae-typewriter h1 span' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

	}

	/**
	 * Render App Works widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();
		$before_title = !empty( $settings['before_title'] ) ? $settings['before_title'] : '';
		$animation_groups = !empty( $settings['animation_groups'] ) ? $settings['animation_groups'] : '';
		$after_title = !empty( $settings['after_title'] ) ? $settings['after_title'] : '';
		$cursorChar = !empty( $settings['cursorChar'] ) ? $settings['cursorChar'] : '';
		$typeSpeed = !empty( $settings['typeSpeed'] ) ? $settings['typeSpeed'] : '';
		$backSpeed = !empty( $settings['backSpeed'] ) ? $settings['backSpeed'] : '';
		$startDelay = !empty( $settings['startDelay'] ) ? $settings['startDelay'] : '';
		$backDelay = !empty( $settings['backDelay'] ) ? $settings['backDelay'] : '';

		$typed_id = uniqid();
		$id = rand(999, 9999);

		$output = '<div class="napae-typewriter">
			          <h1>'.esc_html($before_title).'
			            <span class="typed_'.esc_attr($typed_id).'_'.esc_attr($id).'_strings">';
			            // Group Param Output
									if ( is_array( $animation_groups ) && !empty( $animation_groups ) ){
									  foreach ( $animation_groups as $each_list ) {
											$animation_text = $each_list['animation_text'] ? $each_list['animation_text'] : '';
										  $output .= '<span>'. esc_html($animation_text) .'</span>';
									  }
									}
      $output .= '</span>
			            <span class="typed_'.esc_attr($typed_id).'_'.esc_attr($id).'"></span>
			          '.esc_html($after_title).'</h1>
			         </div>';
							?>
							<script type="text/javascript">
								jQuery(document).ready(function($) {
								  "use strict";
								  //Write Erase Script
								  var typed_<?php echo esc_attr($typed_id); ?>_<?php echo esc_attr($id); ?> = new Typed('.typed_<?php echo esc_attr($typed_id); ?>_<?php echo esc_attr($id); ?>', {
								    typeSpeed: <?php echo esc_attr($typeSpeed); ?>,
								    backSpeed: <?php echo esc_attr($backSpeed); ?>,
								    backDelay: <?php echo esc_attr($backDelay); ?>,
								    startDelay: <?php echo esc_attr($startDelay); ?>,
								    cursorChar: '<?php echo esc_attr($cursorChar); ?>',
								    loop: true,
								    stringsElement: '.typed_<?php echo esc_attr($typed_id); ?>_<?php echo esc_attr($id); ?>_strings',
								  });
								});
							</script>
							<?php
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Primary_Addon_Typewriter() );

} // enable & disable
