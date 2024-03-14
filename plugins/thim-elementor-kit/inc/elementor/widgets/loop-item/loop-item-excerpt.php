<?php
namespace Elementor;

use Thim_EL_Kit\Utilities\Widget_Loop_Trait;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

class Thim_Ekit_Widget_Loop_Item_Excerpt extends Widget_Base {

	use Widget_Loop_Trait;

	public function get_name() {
		return 'thim-loop-item-excerpt';
	}

	public function get_title() {
		return esc_html__( 'Item Excerpt', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'eicon-post-excerpt';
	}

	public function get_keywords() {
		return array( 'title', 'excerpt', 'content' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'excerpt',
			array(
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => array(
					'active'  => true,
					'default' => \Elementor\Plugin::$instance->dynamic_tags->tag_data_to_tag_text( null, 'thim-item-excerpt' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Style', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => esc_html__( 'Alignment', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-container' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector' => '{{WRAPPER}} .elementor-widget-container',
			)
		);

		$this->add_control(
			'excerpt_max_line',
			array(
				'label'       => esc_html__( 'Max Line', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => 0,
				'step'        => 1,
				'selectors'   => array(
					'{{WRAPPER}} .elementor-widget-container' => 'display: -webkit-box; text-overflow: ellipsis; -webkit-line-clamp: {{VALUE}};-webkit-box-orient:vertical; overflow: hidden;',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$this->print_unescaped_setting( 'excerpt' );
	}
}
