<?php
/**
 * @since 1.0.0
 */
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
class DirectoryPress_Elementor_Terms_Widget extends \Elementor\Widget_Base {
	public $selected_taxonomy = 'directorypress-category';
	public $data;
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
	}
	
	
	/**
	 * Get widget name.
	 *
	 * Retrieve oEmbed widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'directorypress-terms';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve oEmbed widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Terms', 'DIRECTORYPRESS' );
	}


	/**
	 * Get widget icon.
	 *
	 * Retrieve oEmbed widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fas fa-map-marked-alt';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the oEmbed widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'directorypress' ];
	}

	/**
	 * Register oEmbed widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		
		$this->start_controls_section(
			'setting_section',
			[
				'label' => __( 'Content', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'terms_taxonomy',
			[
				'label' => __( 'Select A Taxonomy', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT,
				'multiple' => false,
				'options' => $this->taxonomy(),
				'default' => 'directorypress-category',
			]
		);
		foreach(get_object_taxonomies('dp_listing') AS $key => $tax){
			$con = 'directorypress-category';
			$this->add_control(
				'terms_'. $tax,
				[
					'label' => __( 'Select A Term', 'DIRECTORYPRESS' ), 
					'label_block' => true,
					'type' => \Elementor\Controls_Manager::SELECT2,
					'condition' => [
						 'terms_taxonomy' => $tax
					],

					'multiple' => true,
					'options' => directorypress_terms_options_array($tax),
					'default' => 0,
				]
			);
		}
		$this->add_responsive_control(
			'divider',
			[
				'label' => esc_html__( 'Divider', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type here', 'DIRECTORYPRESS' ),
			]
		);
		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'DIRECTORYPRESS' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'DIRECTORYPRESS' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'DIRECTORYPRESS' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'DIRECTORYPRESS' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .terms-list-item a' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section(); 
		
		// Style tab and section
		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Style', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		
		$this->start_controls_tabs( 'terms_title_style' );

		$this->start_controls_tab(
			'title_tab_field_normal',
			array(
				'label' => __( 'Normal', 'DIRECTORYPRESS' ),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Title Typography', 'DIRECTORYPRESS' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .terms-list-item a',
			]
		);
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .terms-list-item a' => 'color: {{VALUE}} !important',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'DIRECTORYPRESS' ),
				'selector' => '{{WRAPPER}} .terms-list-item a',
			]
		);
		$this->add_control(
			'background_color',
			array(
				'label' => __( 'Background Color', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .terms-list-item a' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'title_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'DIRECTORYPRESS' ),
				'selector' => '{{WRAPPER}} .terms-list-item a',
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'title_tab_field_hover',
			array(
				'label' => __( 'Hover', 'DIRECTORYPRESS' ),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography_hover',
				'label' => __( 'Title Typography', 'DIRECTORYPRESS' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .terms-list-item:hover a',
			]
		);
		$this->add_control(
			'title_color_hover',
			[
				'label' => __( 'Color', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .terms-list-item:hover a' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_text_shadow_hover',
				'label' => esc_html__( 'Text Shadow', 'DIRECTORYPRESS' ),
				'selector' => '{{WRAPPER}} .terms-list-item:hover a',
			]
		);
		$this->add_control(
			'background_color_hover',
			array(
				'label' => __( 'Background Color', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .terms-list-item:hover a' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'title_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'DIRECTORYPRESS' ),
				'selector' => '{{WRAPPER}} .terms-list-item:hover a',
			]
		);
		
		$this->add_control(
			'title_border_color_hover',
			array(
				'label' => __( 'Border Color', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .terms-list-item:hover a' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => __( 'Border', 'DIRECTORYPRESS' ),
				'selector' => '{{WRAPPER}} .terms-list-item a',
			]
		);
		$this->add_responsive_control(
			'title_border_radius',
			[
				'label' => __( 'Border Radius', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .terms-list-item a' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_padding',
			[
				'label' => __( 'Padding', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .terms-list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_margin',
			[
				'label' => __( 'Margin', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom'=> '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .terms-list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

	}

	public function taxonomy() {
		$get_taxonomies = get_object_taxonomies('dp_listing');
		$options = array();
		foreach ($get_taxonomies AS $taxonomy) {
				$options[$taxonomy] = $taxonomy;
		}
	
		return $options;
	}
	protected function selected_taxonomy() {
		$this->selected_taxonomy = $this->get_data(['settings'], ['terms_taxonomy']);
	}
	
	protected function render() {
		$settings = $this->get_settings_for_display();
		$terms = $settings['terms_'.$settings['terms_taxonomy']];
		
		echo '<div class="directorypress-terms-list-widget">';
		
			$instance = array(
					'parent' => '',
					'depth' => 1,
					'columns' => 'inline',
					'count' => 0,
					'exact_terms' => $terms,
					'cat_style' => 'directorypress-terms-list',
					'divider' => $settings['divider']
					
			);
			$instance['tax'] = $settings['terms_taxonomy'];
			$instance['max_subterms'] = 0;

			$directorypress_handler = new DirectoryPress_Terms($instance);
			echo $directorypress_handler->display(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	
		echo '</div>';
	}

}