<?php



use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Border;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Plugin;




class wpsection_wps_gradient_heading_Widget extends \Elementor\Widget_Base {


public function get_name() {
		return 'wpsection_wps_gradient_heading';
	}

	public function get_title() {
		return __( 'Gradient Heading', 'wpsection' );
	}

	public function get_icon() {
	     return 'eicon-heading';
	}

	public function get_keywords() {
		return [ 'wpsection', 'heading' ];
	}

	public function get_categories() {
    return [ 'wpsection_category' ];
	} 


	protected function register_controls() {

		$this->start_controls_section(
			'wps_content_section',
			[
				'label' => esc_html__( 'Content', 'wpsection' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'title',
			[
				'label'       => __( 'Title', 'rashid' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => __( 'Enter your title', 'rashid' ),
				'default' => 'Unique Business Ideas. ',
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'wps_section_style',
			[
				'label' => esc_html__( 'Style', 'wpsection' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'title_background',
				'label'    => esc_html__( 'Background', 'wpsection' ),
				'types'    => [ 'gradient', ],
				'selector' => '{{WRAPPER}} .wpsection-gradient-heading',
				'exclude'  => [
					'image'
				]
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .wpsection-gradient-heading',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label'      => esc_html__( 'Margin', 'wpsection' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .wpsection-gradient-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$allowed_tags = wp_kses_allowed_html('post');
		?>


<?php


?>



<h2 class="wpsection-gradient-heading"><?php echo $settings['title'];?></h2>

             
		<?php 
	}

}


Plugin::instance()->widgets_manager->register( new \wpsection_wps_gradient_heading_Widget() );