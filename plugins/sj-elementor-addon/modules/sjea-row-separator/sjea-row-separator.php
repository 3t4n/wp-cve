<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_SJEaRowSeparator extends Widget_Base {

	public function get_name() {
		return 'sjea-row-separator';
	}

	public function get_title() {
		return __( 'SJEA - Row Separator', 'sjea' );
	}

	public function get_categories() {
		return [ 'sjea-elements' ];
	}

	/*public static function get_type() {
	 	return 'sjea';
	 }*/ 

	public function get_icon() {
		return 'eicon-divider-shape';
	}

	protected function _register_controls() {

        $this->start_controls_section(
            'general_section',
            [
                'label' => __( 'General Setting', 'sjea' )
            ]
        );

		$this->add_control(
				'enable_separator',
				[
						'label' => __( 'Enable Separator', 'sjea' ),
						'type' => Controls_Manager::SELECT,
						'options' => [
								'above' => __( 'Top Separator', 'sjea' ),
								'below' => __( 'Bottom Separator', 'sjea' ),
						],
						'default' => 'above',

				]
		);

		$this->add_control(
				'separator_style',
				[
						'label' => __( 'Separator Style', 'sjea' ),
						'type' => Controls_Manager::SELECT,
						'options' => [
							'triangle_svg'		 	=>	__( 'Triangle', 'sjea' ),
							'xlarge_triangle'		=>	__( 'Big Triangle', 'sjea' ),
							'xlarge_triangle_left'	=>	__( 'Big Triangle Left', 'sjea' ),
							'xlarge_triangle_right'	=>	__( 'Big Triangle Right', 'sjea' ),
							'circle_svg'		 	=>	__( 'Half Circle', 'sjea' ),
							'xlarge_circle'		 	=>	__( 'Curve Center', 'sjea' ),
							'curve_up'		 		=>	__( 'Curve Left', 'sjea' ),
							'curve_down'		 	=>	__( 'Curve Right', 'sjea' ),
							'tilt_left'		 		=>	__( 'Tilt Left', 'sjea' ),
							'tilt_right'		 	=>	__( 'Tilt Right', 'sjea' ),
							'waves'		 			=>	__( 'Waves', 'sjea' ),
							'clouds'		 		=>	__( 'Clouds', 'sjea' )
						],
						'default' => 'xlarge_triangle',

				]
		);

		$this->add_control(
				'separator_color',
				[
						'label' => __( 'Separator Color', 'sjea' ),
						'type' => Controls_Manager::COLOR,
						'scheme' => [
								'type' => Scheme_Color::get_type(),
								'value' => Scheme_Color::COLOR_1,
						],
						'selectors' => [
								'{{WRAPPER}} svg' => 'fill:{{VALUE}}',
						],
				]
		);

		$this->add_control(
				'separator_height',
				[
						'type' => Controls_Manager::NUMBER,
						'label' => __( 'Separator Height (in px)', 'sjea' ),
						'placeholder' => __( '100', 'sjea' ),
						'default' => __( '100', 'sjea' ),
				]
		);

		$this->add_control(
		    'overlap_seperator',
		    [
		        'label' => __( 'Overlap Row Separator', 'sjea' ),
		        'type' => Controls_Manager::SWITCHER,
		        'default' => '',
		        'label_on' => __( 'Yes', 'sjea' ),
		        'label_off' => __( 'No', 'sjea' ),
		        'return_value' => 'yes',
		    ]
		);

		$this->end_controls_section();
	}

	protected function render( ) {
		$node_id = $this->get_id();
		$name = $this->get_name();
		$settings = $this->get_settings();

		SJEaModuleScripts::sjea_row_separator();

		// var_dump( Plugin::instance()->editor->is_edit_mode() );
		//var_dump( Plugin::instance()->preview->is_preview_mode() );

		include SJ_EA_DIR . 'modules/sjea-row-separator/includes/frontend.php';
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Widget_SJEaRowSeparator() );