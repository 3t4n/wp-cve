<?php
namespace ECSL\Modules\WrapperLinks;

use Elementor\Controls_Manager;



class Module {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct() {
		add_action( 'elementor/element/before_section_start', [ $this, 'add_fields' ], 10, 3 );
		add_action( 'elementor/frontend/element/before_render', [ $this, 'before_section_render' ], 10, 1 );

		add_action( 'elementor/frontend/section/before_render', [ $this, 'before_section_render' ], 10, 1 );
		add_action( 'elementor/frontend/column/before_render', [ $this, 'before_section_render' ], 10, 1 );
	}

	public function add_fields( $element, $section_id, $args ) {

		if ( ( 'section' === $element->get_name() && 'section_background' === $section_id ) || ( 'column' === $element->get_name() && 'section_style' === $section_id ) ) {


			//SECTION LINK
			if ( ( 'section' === $element->get_name() && 'section_background' === $section_id )){
				$element->start_controls_section(
					'section_link',
					[
						'tab'   => Controls_Manager::TAB_LAYOUT,
						'label' => __( 'Section Link', 'ecsl' ),
					]
				);
			}

			//COLUMN LINK
			if ( ( 'column' === $element->get_name() && 'section_style' === $section_id )){
				$element->start_controls_section(
					'column_link',
					[
						'tab'   => Controls_Manager::TAB_LAYOUT,
						'label' => __( 'Column Link', 'ecsl' ),
					]
				);
			}


			$element->add_control(
				'ecsl_enable_link',
				[
					'label'        => __( 'Enable Link', 'ecsl' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => '',
					'label_on'     => __( 'Yes', 'ecsl' ),
					'label_off'    => __( 'No', 'ecsl' ),
					'return_value' => 'yes',
					'prefix_class' => 'ecsl-link-',
				]
			);

			$element->add_control(
				'column_section_link',
				[
					'label'         => __( 'Link', 'ecsl' ),
					'type'          => Controls_Manager::URL,
					'label_block'   => true,
					'show_external' => false,
					'dynamic'       => [
						'active' => true,
					],
					'placeholder'   => __( '#', 'ecsl' ),
					'condition'     => [
						'ecsl_enable_link' => 'yes',
					],
				]
			);

			$element->add_control(
				'enable_open_in_new_window',
				[
					'label'        => __( 'Enable Open In New Window', 'ecsl' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => '',
					'label_on'     => __( 'Yes', 'ecsl' ),
					'label_off'    => __( 'No', 'ecsl' ),
					'return_value' => 'yes',
					'condition'    => [
						'ecsl_enable_link' => 'yes',
					],
				]
			);

			$element->end_controls_section();
		}
	}

	public function before_section_render( $element ) {

		if ( $element->get_settings( 'ecsl_enable_link' ) === 'yes' ) {
			$settings = $element->get_settings_for_display();
			$link     = $settings['column_section_link'];

			$element->add_render_attribute(
				'_wrapper',
				[
					'data-ecsl-url'        => $link['url'],
					'data-ecsl-link'       => $element->get_settings( 'ecsl_enable_link' ),
					'data-ecsl-new-window' => $settings['enable_open_in_new_window'],
				]
			);

		}
	}


}
