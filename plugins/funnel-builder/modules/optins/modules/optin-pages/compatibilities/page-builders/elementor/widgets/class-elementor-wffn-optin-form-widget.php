<?php

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Elementor_WFFN_Optin_Form_Widget
 */
if ( ! class_exists( 'Elementor_WFFN_Optin_Form_Widget' ) ) {
	#[AllowDynamicProperties]

  class Elementor_WFFN_Optin_Form_Widget extends \Elementor\Widget_Base {

		private $add_tab_number = 1;
		private $add_heading_number = 1;
		private $add_divider_number = 1;

		/**
		 * Get widget name.
		 *
		 *
		 * @return string Widget name.
		 */
		public function get_name() {
			return 'wffn-optin-form';
		}

		/**
		 * Get widget title.
		 *
		 * @return string Widget title.
		 */
		public function get_title() {
			return __( 'Optin Form', 'funnel-builder' );
		}

		/**
		 * Get widget icon.
		 *
		 * @return string Widget icon.
		 */
		public function get_icon() {
			return 'eicon-form-horizontal';
		}

		/**
		 * Get widget categories.
		 *
		 * Retrieve the list of categories the widget belongs to.
		 * @access public
		 *
		 * @return array Widget categories.
		 */
		public function get_categories() {
			return [ 'wffn-flex' ];
		}

		/**
		 * Register widget controls.
		 *
		 * Adds different input fields to allow the user to change and customize the widget settings.
		 *
		 * @access protected
		 */
		public function register_controls() {

			$this->register_sections();
			$this->register_styles();

		}

		public function register_sections() {
			$this->start_controls_section( 'section_form_fields', [
				'label' => __( 'Form', 'funnel-builder' ),
			] );

			$optinPageId = WFOPP_Core()->optin_pages->get_optin_id();
			$get_fields  = [];
			if ( $optinPageId > 0 ) {
				$get_fields = WFOPP_Core()->optin_pages->form_builder->get_form_fields( $optinPageId );
			}

			foreach ( is_array( $get_fields ) ? $get_fields : [] as $field ) {
				$options = [
					'wffn-sm-100' => __( 'Full', 'funnel-builder' ),
					'wffn-sm-50'  => __( 'One Half', 'funnel-builder' ),
					'wffn-sm-33'  => __( 'One Third', 'funnel-builder' ),
					'wffn-sm-67'  => __( 'Two Third', 'funnel-builder' ),
				];
				$default = isset( $field['width'] ) ? $field['width'] : 'wffn-sm-100';


				$this->add_select( $field['InputName'], esc_html__( $field['label'] ), $options, $default );
			}


			$this->add_control( 'show_labels', [
				'label'        => __( 'Label', 'funnel-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'funnel-builder' ),
				'label_off'    => __( 'Hide', 'funnel-builder' ),
				'return_value' => 'true',
				'default'      => 'true',
				'separator'    => 'before',
			] );

			/* Register Optin form submit Button*/
			$this->add_heading( __( 'Submit Button', 'funnel-builder' ) );
			$this->add_text( 'button_text', __( 'Title', 'funnel-builder' ), __( 'Send Me My Free Guide', 'funnel-builder' ), [], '', '', __( 'Enter the Button Text', 'funnel-builder' ) );
			$this->add_text( 'subtitle', 'Sub Title', '', [], '', '', __( 'Enter subtitle', 'funnel-builder' ) );
			$this->add_text( 'button_submitting_text', 'Submitting Text', __( 'Submitting...', 'funnel-builder' ) );

			do_action( 'wffn_additional_controls', $this );

			$this->close_controls_tab();
			$this->close_controls_tabs();

			$this->end_tab();
			/* End Optin form submit Button*/
		}

		public function register_styles() {
			$this->start_controls_section( 'section_form_style', [
				'label' => __( 'Form', 'funnel-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			] );

			$this->add_control( 'heading_label', [
				'label'     => __( 'Label', 'funnel-builder' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'show_labels!' => '',
				],
			] );

			$this->add_control( 'label_color', [
				'label'     => __( 'Text', 'funnel-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bwfac_form_sec > label, {{WRAPPER}} .bwfac_form_sec .wfop_input_cont > label' => 'color: {{VALUE}};',
				],
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'condition' => [
					'show_labels!' => '',
				],
			] );

			$this->add_control( 'mark_required_color', [
				'label'     => __( 'Asterisk', 'funnel-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .bwfac_form_sec > label > span, {{WRAPPER}} .bwfac_form_sec .wfop_input_cont > label > span' => 'color: {{COLOR}};',
				],
				'condition' => [
					'show_labels!' => '',
				],
			] );

			$this->add_group_control( Group_Control_Typography::get_type(), [
				'name'      => 'label_typography',
				'selector'  => '{{WRAPPER}} .bwfac_form_sec > label, {{WRAPPER}} .bwfac_form_sec .wfop_input_cont > label',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'condition' => [
					'show_labels!' => '',
				],
			] );

			$this->add_control( 'input_label', [
				'label' => __( 'Input', 'funnel-builder' ),
				'type'  => Controls_Manager::HEADING,
			] );

			$this->add_control( 'field_text_color', [
				'label'     => __( 'Text', 'funnel-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bwfac_form_sec .wffn-optin-input, {{WRAPPER}} .bwfac_form_sec .wffn-optin-input::placeholder' => 'color: {{VALUE}};',
				],
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'default'   => '#3F3F3F',
			] );
			$this->add_group_control( Group_Control_Typography::get_type(), [
				'name'           => 'field_typography',
				'selector'       => '{{WRAPPER}} .bwfac_form_sec .wffn-optin-input',
				'global'         => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'fields_options' => [
					// first mimic the click on Typography edit icon
					'typography'  => [ 'default' => 'yes' ],
					// then redefine the Elementor defaults
					'font_family' => [ 'default' => 'Open Sans' ],
					'font_size'   => [ 'default' => [ 'size' => 16 ] ],
					'font_weight' => [ 'default' => 400 ],
				],
			] );

			$this->add_control( 'field_background_color', [
				'label'     => __( 'Background', 'funnel-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .bwfac_form_sec .wffn-optin-input' => 'background-color: {{VALUE}};',
				],
			] );

			$this->add_control( 'input_size', [
				'label'     => __( 'Field Size', 'funnel-builder' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => self::get_input_fields_sizes(),
				'default'   => '12px',
				'selectors' => [
					'{{WRAPPER}} .wffn-custom-optin-from .wffn-optin-input' => 'padding: {{VALUE}} 15px',
				],
			] );

			$this->add_control( 'advanced_form_label', [
				'label'     => __( 'Advanced', 'funnel-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			] );

			$fields_options = [
				'border' => [
					'default' => 'solid',
				],
				'width'  => [
					'default' => [
						'top'      => '2',
						'right'    => '2',
						'bottom'   => '2',
						'left'     => '2',
						'isLinked' => true,
					],
				],
				'color'  => [
					'default' => '#d8d8d8',
				],
			];

			$this->add_border( 'field_border', '{{WRAPPER}} .bwfac_form_sec .wffn-optin-input', array(), array(), $fields_options );

			$this->add_heading( __( "Spacing", 'funnel-builder' ) );
			$this->add_control( 'column_gap', [
				'label'     => __( 'Columns', 'funnel-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 10,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bwfac_form_sec'                => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .elementor-form-fields-wrapper' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
				],
			] );

			$this->add_control( 'row_gap', [
				'label'     => __( 'Rows', 'funnel-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 10,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bwfac_form_sec' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			] );

			$this->add_control( 'label_spacing', [
				'label'     => __( 'Label', 'funnel-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 0,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'body {{WRAPPER}} .bwfac_form_sec .wfop_input_cont' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_labels!' => '',
				],
			] );

			$this->add_heading( __( 'Submit Button', 'funnel-builder' ) );

			$this->add_responsive_control( 'button_width', [
				'label'     => __( 'Button width (in %)', 'funnel-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 100,
				],
				'range'     => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bwfac_form_sec #wffn_custom_optin_submit' => 'min-width: {{SIZE}}%;',
				],
			] );
			$this->add_text_alignments( 'button_alignment', [ '{{WRAPPER}} .wffn-custom-optin-from #bwf-custom-button-wrap' ], __( 'Alignment', 'funnel-builder' ) );
			$this->add_text_alignments( 'button_text_alignment', [ '{{WRAPPER}} .wffn-custom-optin-from #bwf-custom-button-wrap span' ], __( 'Text Alignment', 'funnel-builder' ) );

			$this->add_heading( __( "Color", 'funnel-builder' ) );
			$this->add_controls_tabs( "bwf_button_tabs" );
			$this->add_controls_tab( "bwf_button_normal_tab", 'Normal' );
			$this->add_background_color( 'button_bg_color', [ '{{WRAPPER}} .bwfac_form_sec #wffn_custom_optin_submit' ], '#FBA506' );
			$this->add_color( 'button_color', [ '{{WRAPPER}} .bwfac_form_sec #wffn_custom_optin_submit .bwf_heading, {{WRAPPER}} .bwfac_form_sec #wffn_custom_optin_submit .bwf_subheading' ], '#ffffff' );
			$this->close_controls_tab();
			$this->add_controls_tab( "bwf_button_hover_tab", 'Hover' );
			$this->add_background_color( 'button_hover_bg_color', [ '{{WRAPPER}} .bwfac_form_sec #wffn_custom_optin_submit:hover' ], '#E69500' );
			$this->add_color( 'button_hover_color', [
				'{{WRAPPER}} .bwfac_form_sec #wffn_custom_optin_submit:hover .bwf_heading',
				'{{WRAPPER}} .bwfac_form_sec #wffn_custom_optin_submit:hover .bwf_subheading'
			], '' );
			$this->close_controls_tab();
			$this->close_controls_tabs();

			$this->add_heading( __( "Typography", 'funnel-builder' ) );
			$this->add_typography( 'button_text_typo', '{{WRAPPER}} .bwfac_form_sec #wffn_custom_optin_submit .bwf_heading', array(), array(), 'Heading' );
			$this->add_typography( 'button_subheading_text_typo', '{{WRAPPER}} .bwfac_form_sec #wffn_custom_optin_submit .bwf_subheading', array(), array(), 'Sub Heading' );

			$this->add_heading( __( "Advanced", 'funnel-builder' ) );
			$padding_defaults = [ 'top' => 15, 'right' => 15, 'bottom' => 15, 'left' => 15, 'unit' => 'px' ];
			$margin_defaults  = [ 'top' => 15, 'right' => 0, 'bottom' => 25, 'left' => 0, 'unit' => 'px' ];
			$fields_options   = [
				'border' => [
					'default' => 'solid',
				],
				'width'  => [
					'default' => [
						'top'      => '2',
						'right'    => '2',
						'bottom'   => '2',
						'left'     => '2',
						'isLinked' => true,
					],
				],
				'color'  => [
					'default' => '#E69500',
				],
			];
			$this->add_padding( 'button_text_padding', '{{WRAPPER}} .bwfac_form_sec #wffn_custom_optin_submit', $padding_defaults );
			$this->add_margin( 'button_text_margin', '{{WRAPPER}} .bwfac_form_sec #wffn_custom_optin_submit', $margin_defaults );
			$this->add_heading( __( "Border", 'funnel-builder' ) );
			$this->add_border( 'bwf_button_border', '{{WRAPPER}} .bwfac_form_sec #wffn_custom_optin_submit', array(), array(), $fields_options );
			$this->add_border_shadow( 'button_text_alignment_box_shadow', '{{WRAPPER}} .bwfac_form_sec #wffn_custom_optin_submit' );

			do_action( 'wffn_additional_control_styling', $this );
			$this->end_controls_section();
		}

		public static function get_input_fields_sizes() {
			return [
				'6px'  => __( 'Small', 'funnel-builder' ),
				'9px'  => __( 'Medium', 'funnel-builder' ),
				'12px' => __( 'Large', 'funnel-builder' ),
				'15px' => __( 'Extra Large', 'funnel-builder' ),
			];
		}

		/**
		 * Render widget output on the frontend.
		 *
		 * Written in PHP and used to generate the final HTML.
		 *
		 * @access protected
		 */
		protected function render() {
			$settings                       = $this->get_settings_for_display();
			$settings['button_border_size'] = 0;

			$wrapper_class = 'elementor-form-fields-wrapper';
			$show_labels   = isset( $settings['show_labels'] ) ? $settings['show_labels'] : true;
			$wrapper_class .= $show_labels ? '' : ' wfop_hide_label';

			$optinPageId    = WFOPP_Core()->optin_pages->get_optin_id();
			$optin_fields   = WFOPP_Core()->optin_pages->form_builder->get_optin_layout( $optinPageId );
			$optin_settings = WFOPP_Core()->optin_pages->get_optin_form_integration_option( $optinPageId );

			foreach ( $optin_fields as $step_slug => $optinFields ) {
				foreach ( $optinFields as $key => $optin_field ) {
					$optin_fields[ $step_slug ][ $key ]['width'] = $settings[ $optin_field['InputName'] ];
				}
			}

			$custom_form = WFOPP_Core()->form_controllers->get_integration_object( 'form' );
			if ( $custom_form instanceof WFFN_Optin_Form_Controller_Custom_Form ) {
				$settings = wp_parse_args( $settings, WFOPP_Core()->optin_pages->form_builder->form_customization_settings_default() );
				$custom_form->_output_form( $wrapper_class, $optin_fields, $optinPageId, $optin_settings, 'inline', $settings );
			}
			if ( did_action( 'admin_action_elementor' ) || did_action( 'wp_ajax_elementor_ajax' ) ) {

				?>
				<script>
                    jQuery(document).trigger('wffn_reload_phone_field');
				</script>
				<?php
			}

		}

		/**
		 * Render Form widget output in the editor.
		 *
		 * Written as a Backbone JavaScript template and used to generate the live preview.
		 *
		 * @since 2.9.0
		 * @access protected
		 */
		protected function content_template() {
		}

		protected function add_tab( $title = '', $tab_type = 1, $condition = [] ) {
			if ( empty( $title ) ) {
				$title = $this->get_title();
			}
			$field_key = 'wffn_' . $this->add_tab_number . "_tab";
			$tab       = Controls_Manager::TAB_CONTENT;
			if ( 2 === $tab_type ) {
				$tab = Controls_Manager::TAB_STYLE;
			} elseif ( 3 === $tab_type ) {
				$tab = Controls_Manager::TAB_ADVANCED;
			} elseif ( 4 === $tab_type ) {
				$tab = Controls_Manager::TAB_SETTINGS;
			}

			$this->start_controls_section( $field_key, [
				'label'     => $title,
				'tab'       => $tab,
				'condition' => $condition
			] );

			$this->add_tab_number ++;
		}

		protected function end_tab() {
			$this->end_controls_section();
		}

		protected function add_margin_padding_border( $field_key, $selector, $full_selector = false, $default = [] ) {
			if ( false === $full_selector ) {
				$selector = '{{WRAPPER}} ' . $selector;
			}

			$this->add_group_control( \Elementor\Group_Control_Background::get_type(), [
				'name'     => $field_key . '_background',
				'label'    => __( 'Background', 'elementor' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => $selector,
			] );
			$this->add_responsive_control( $field_key . '_width', [
				'label'      => __( 'Width', 'elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 2500,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => isset( $default['width'] ) ? $default['width'] : 65,
				],
				'selectors'  => [
					$selector => 'width: {{SIZE}}{{UNIT}};',
				],
			] );

			$this->add_padding( $field_key, $selector );
			$this->add_margin( $field_key, $selector );
			$this->add_border( $field_key, $selector );

		}

		protected function add_width( $field_key, $selector, $label = '', $default = [], $condition = [], $size_unit = [], $tablet_default = [], $mobile_default = [] ) {
			if ( empty( $label ) ) {
				$label = __( 'Width', 'elementor' );
			}

			if ( empty( $size_unit ) ) {
				$size_unit = [ 'px', '%' ];
			}

			$args = [
				'label'      => $label,
				'type'       => Controls_Manager::SLIDER,
				'size_units' => $size_unit,
				'range'      => [
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min'  => 0,
						'max'  => 2500,
						'step' => 5,
					],
				],
				'default'    => [
					'unit' => isset( $default['unit'] ) ? $default['unit'] : '%',
					'size' => isset( $default['width'] ) ? $default['width'] : 100,
				],
				'selectors'  => [
					$selector => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => $condition
			];

			if ( ! empty( $size_unit ) ) {
				$args['tablet_default'] = $tablet_default;
				$args['mobile_default'] = $mobile_default;
			}

			$this->add_responsive_control( $field_key, $args );
		}

		protected function add_padding( $field_key, $selector, $default = [], $mobile_default = [], $condition = [], $tablet_default = [] ) {
			if ( empty( $default ) ) {
				$default = [ 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'unit' => 'px', 'isLinked' => false ];
			}

			$args = [
				'label'      => __( 'Padding', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => $default,
				'condition'  => $condition,
				'selectors'  => [
					$selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			];

			if ( ! empty( $mobile_default ) ) {
				$args['mobile_default'] = $mobile_default;
			}

			if ( ! empty( $tablet_default ) ) {
				$args['tablet_default'] = $tablet_default;
			}

			$this->add_responsive_control( $field_key . '_padding', $args );
		}

		protected function add_margin( $field_key, $selector, $default = [], $mobile_default = [], $condition = [], $tablet_default = [] ) {
			if ( empty( $default ) ) {
				$default = [ 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'unit' => 'px' ];
			}
			$args = [
				'label'      => __( 'Margin', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => $default,
				'condition'  => $condition,
				'selectors'  => [
					$selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			];

			if ( ! empty( $mobile_default ) ) {
				$args['mobile_default'] = $mobile_default;
			}

			if ( ! empty( $tablet_default ) ) {
				$args['tablet_default'] = $tablet_default;
			}

			$this->add_responsive_control( $field_key . '_margin', $args );
		}

		protected function add_border( $field_key, $selector, $condition = [], $default = [], $fields_options = [] ) {
			if ( empty( $default ) ) {
				$default = [ 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'unit' => 'px' ];
			}
			$borderdefault = [
				'name'      => $field_key . '_border',
				'label'     => __( 'Border', 'woofunnels-aero-checkout' ),
				'selector'  => $selector,
				'condition' => $condition,
			];
			if ( is_array( $fields_options ) && count( $fields_options ) > 0 ) {
				$borderdefault['fields_options'] = $fields_options;
			}
			$this->add_group_control( \Elementor\Group_Control_Border::get_type(), $borderdefault );

			$this->add_responsive_control( $field_key . '_border_radius', [
				'label'      => __( 'Border Radius', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => $default,
				'selectors'  => [
					$selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => $condition
			] );
		}

		protected function add_border_radius( $field_key, $selector, $condition = [], $default = [], $fields_options = [], $custom_label = '' ) {
			$label = __( 'Border Radius', 'elementor' );

			if ( ! empty( $custom_label ) ) {
				$label = $custom_label;
			}

			if ( empty( $default ) ) {
				$default = [ 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'unit' => 'px' ];
			}

			$this->add_responsive_control( $field_key . '_border_radius', [
				'label'      => $label,
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => $default,
				'selectors'  => [
					$selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => $condition
			] );
		}

		protected function add_border_without_radius( $field_key, $selector, $condition = [], $default = [], $fields_options = [] ) {
			if ( empty( $default ) ) {
				$default = [ 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'unit' => 'px' ];
			}
			$borderdefault = [
				'name'      => $field_key . '_border',
				'label'     => __( 'Border', 'woofunnels-aero-checkout' ),
				'selector'  => $selector,
				'condition' => $condition,
			];
			if ( is_array( $fields_options ) && count( $fields_options ) > 0 ) {
				$borderdefault['fields_options'] = $fields_options;
			}
			$this->add_group_control( \Elementor\Group_Control_Border::get_type(), $borderdefault );
		}

		public function add_heading( $heading, $separator = '', $conditions = [] ) {
			if ( empty( $separator ) ) {
				$separator = 'before';
			}

			$field_key = 'wffn_' . $this->add_heading_number . '_heading';
			$this->add_control( $field_key, [
				'label'     => __( $heading, 'woofunnels-aero-checkout' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => $separator,
				'condition' => $conditions
			] );
			$this->add_heading_number ++;
		}

		public function add_typography( $field_key, $selector, $fields_options = [], $conditions = [], $label = '' ) {

			if ( empty( $label ) ) {
				$label = __( 'Typography', 'woofunnels-aero-checkout' );
			}

			$args = [
				'name'      => $field_key,
				'label'     => $label,
				'selector'  => $selector,
				'condition' => $conditions,


			];
			if ( defined( 'ELEMENTOR_VERSION' ) ) {
				if ( version_compare( ELEMENTOR_VERSION, '2.8.0', '>=' ) ) {
					$args['scheme'] = \Elementor\Core\Schemes\Typography::TYPOGRAPHY_4;
				} elseif ( version_compare( ELEMENTOR_VERSION, '3.15.0', '>=' ) ) {
					$args['global'] = [
						'default' => Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_ACCENT,
					];
				} else {
					$args['scheme'] = \Elementor\Scheme_Typography::TYPOGRAPHY_4;
				}
			}
			if ( is_array( $fields_options ) && count( $fields_options ) > 0 ) {
				$args['fields_options'] = $fields_options;

			}


			$this->add_group_control( \Elementor\Group_Control_Typography::get_type(), $args );

		}

		public function add_color( $field_key, $selectors = [], $default = '', $label = '', $conditions = [] ) {
			if ( empty( $label ) ) {
				$label = esc_attr__( 'Label', 'elementor' );
			}

			$color_selectors = [];
			if ( is_array( $selectors ) && count( $selectors ) > 0 ) {
				foreach ( $selectors as $selector ) {
					$color_selectors[ $selector ] = 'color:{{VALUE}} !important;';
				}
			}
			$this->add_control( $field_key, [
				'label'     => $label,
				'type'      => Controls_Manager::COLOR,
				'default'   => $default,
				'selectors' => $color_selectors,
				'condition' => $conditions
			] );
		}

		public function add_background_color( $field_key, $selectors = [], $default = '#000000', $label = '', $conditions = [] ) {
			if ( empty( $label ) ) {
				$label = esc_attr__( 'Background', 'elementor' );
			}
			$color_selectors = [];
			if ( is_array( $selectors ) && count( $selectors ) > 0 ) {
				foreach ( $selectors as $selector ) {
					$color_selectors[ $selector ] = 'background-color:{{VALUE}}';
				}
			}
			$this->add_control( $field_key, [
				'label'     => $label,
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => $default,
				'selectors' => $color_selectors,
				'condition' => $conditions
			] );
		}

		public function add_controls_tabs( $key, $conditions = [], $classes = '' ) {
			$this->start_controls_tabs( $key, [ 'condition' => $conditions, 'classes' => $classes ] );
		}

		public function add_controls_tab( $key, $label ) {
			if ( empty( $label ) ) {
				$label = esc_attr__( 'Normal', 'elementor' );
			}

			$this->start_controls_tab( $key, [
				'label' => $label,
			] );
		}

		public function close_controls_tab() {
			$this->end_controls_tab();
		}

		public function close_controls_tabs() {
			$this->end_controls_tabs();
		}

		public function add_border_color( $field_key, $selectors = [], $default = '#000000', $label = '', $box_shadow = false, $conditions = [] ) {
			if ( empty( $label ) ) {
				$label = esc_attr__( 'Color', 'elementor' );
			}

			$color_selectors = [];
			if ( is_array( $selectors ) && count( $selectors ) > 0 ) {
				foreach ( $selectors as $selector ) {
					$border_color = 'border-color:{{VALUE}};';
					if ( true === $box_shadow ) {
						$border_color .= 'box-shadow:0 0 0 1px {{VALUE}} !important';
					}
					$color_selectors[ $selector ] = $border_color;
				}
			}
			$this->add_control( $field_key, [
				'label'     => $label,
				'type'      => Controls_Manager::COLOR,
				'default'   => $default,
				'selectors' => $color_selectors,
				'condition' => $conditions
			] );
		}

		public function add_hover( $field_key, $selectors = [], $default = '#000000', $label = '', $conditions = [] ) {
			if ( empty( $label ) ) {
				$label = esc_attr__( 'Hover Color', 'woofunnels-aero-checkout' );
			}

			$color_selectors = [];
			if ( is_array( $selectors ) && count( $selectors ) > 0 ) {
				foreach ( $selectors as $selector ) {
					$color_selectors[ $selector ] = 'color:{{VALUE}}';
				}
			}

			$this->add_control( $field_key, [
				'label'     => $label,
				'type'      => Controls_Manager::COLOR,
				'default'   => $default,
				'selectors' => $color_selectors,
				'condition' => $conditions,

			] );
		}

		public function add_background( $field_key, $selector, $default = '#000000', $label = '', $types = [], $conditions = [], $bg_type = [] ) {
			if ( empty( $label ) ) {
				$label = __( 'Background', 'elementor' );
			}
			if ( empty( $bg_type ) ) {
				$types = [ 'classic', 'gradient' ];
			}

			$this->add_group_control( \Elementor\Group_Control_Background::get_type(), [
				'name'      => $field_key,
				'label'     => $label,
				'types'     => $types,
				'default'   => $default,
				'selector'  => $selector,
				'condition' => $conditions,
			] );
		}

		public function add_number( $field_key, $label, $default = 1, $conditions = [] ) {
			$this->add_control( $field_key, [
				'label'     => $label,
				'type'      => Controls_Manager::NUMBER,
				'default'   => $default,
				'condition' => $conditions
			] );
		}

		public function add_text( $field_key, $label, $default = '', $conditions = [], $classes = "", $description = '', $placeholder = '', $device_args = [] ) {
			$textArg = [
				'label'     => $label,
				'type'      => Controls_Manager::TEXT,
				'default'   => $default,
				'condition' => $conditions,
			];

			if ( ! empty( $device_args ) ) {
				$textArg['device_args'] = $device_args;
			}
			if ( ! empty( $description ) ) {
				$textArg['description'] = $description;
			}

			if ( ! empty( $placeholder ) ) {
				$textArg['placeholder'] = $placeholder;
			}
			if ( ! empty( $classes ) ) {
				$textArg['classes'] = $classes;
			}
			$this->add_control( $field_key, $textArg );
		}

		public function add_textArea( $field_key, $label, $default = '', $conditions = [] ) {
			$this->add_control( $field_key, [
				'label'     => $label,
				'type'      => Controls_Manager::TEXTAREA,
				'default'   => $default,
				'condition' => $conditions
			] );
		}

		public function add_choose( $field_key, $label, $options = [], $default = '', $conditions = [], $description = '' ) {
			$args = [
				'label'     => $label,
				'type'      => Controls_Manager::CHOOSE,
				'options'   => $options,
				'default'   => $default,
				'condition' => $conditions,
				'toggle'    => true,
			];
			if ( ! empty( $description ) ) {
				$args['description'] = $description;
			}
			$this->add_control( $field_key, $args );
		}

		public function add_switcher( $field_key, $label = '', $label_on = '', $label_off = '', $default = 'no', $return_value = 'yes', $conditions = [], $tablet_default = "", $mobile_default = "", $classes = '', $device_args = [] ) {
			if ( empty( $label ) ) {
				$label = 'Enable';
			}
			if ( empty( $label_on ) ) {
				$label_on = 'Yes';
			}
			if ( empty( $label_off ) ) {
				$label_off = 'no';
			}

			$args = [
				'label'        => $label,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => $label_on,
				'label_off'    => $label_off,
				'return_value' => $return_value,
				'condition'    => $conditions,
				'default'      => $default,
			];

			if ( ! empty( $device_args ) ) {
				$args['device_args'] = $device_args;
			}

			if ( ! empty( $classes ) ) {
				$args['classes'] = $classes;
			}

			if ( ! empty( $tablet_default ) ) {
				$args['tablet_default'] = 'yes';
			}
			if ( ! empty( $tablet_default ) ) {
				$args['mobile_default'] = 'yes';
			}

			$this->add_responsive_control( $field_key, $args );
		}

		public function add_switcher_without_responsive( $field_key, $label = '', $label_on = '', $label_off = '', $default = 'no', $return_value = 'yes', $conditions = [], $tablet_default = "", $mobile_default = "", $classes = '', $device_args = [] ) {
			if ( empty( $label ) ) {
				$label = 'Enable';
			}
			if ( empty( $label_on ) ) {
				$label_on = 'Yes';
			}
			if ( empty( $label_off ) ) {
				$label_off = 'no';
			}

			$args = [
				'label'        => $label,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => $label_on,
				'label_off'    => $label_off,
				'return_value' => $return_value,
				'condition'    => $conditions,
				'default'      => $default,
			];

			if ( ! empty( $device_args ) ) {
				$args['device_args'] = $device_args;
			}

			if ( ! empty( $classes ) ) {
				$args['classes'] = $classes;
			}

			if ( ! empty( $tablet_default ) ) {
				$args['tablet_default'] = 'yes';
			}
			if ( ! empty( $tablet_default ) ) {
				$args['mobile_default'] = 'yes';
			}
			$this->add_control( $field_key, $args );
		}

		public function add_select( $field_key, $label, $options = [], $default = '', $conditions = [], $description = '', $classes = '' ) {
			if ( empty( $options ) ) {
				return;
			}
			$args = [
				'label'     => $label,
				'type'      => Controls_Manager::SELECT,
				'default'   => $default,
				'options'   => $options,
				'condition' => $conditions,
			];
			if ( ! empty( $classes ) ) {
				$args['classes'] = $classes;
			}
			if ( ! empty( $description ) ) {
				$args['description'] = $description;
			}
			$this->add_control( $field_key, $args );
		}

		public function add_text_alignments( $field_key, $selectors, $label = '', $options = [], $default = '', $conditions = [], $extra_css = null ) {
			$align_selectors = [];

			if ( is_array( $selectors ) && count( $selectors ) > 0 ) {
				foreach ( $selectors as $selector ) {
					if ( is_array( $extra_css ) && array_key_exists( $selector, $extra_css ) ) {
						$align_selectors[ $selector ] = 'text-align:{{VALUE}}; ' . $extra_css[ $selector ];
					} else {
						$align_selectors[ $selector ] = 'text-align:{{VALUE}};';
					}
				}
			}
			if ( empty( $label ) ) {
				$label = __( 'Alignment', 'elementor' );
			}
			if ( empty( $options ) ) {
				$options = [
					'left'   => [
						'title' => __( 'Left', 'funnel-builder' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'funnel-builder' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'funnel-builder' ),
						'icon'  => 'eicon-text-align-right',
					]
				];
			}

			$this->add_responsive_control( $field_key, [
				'label'     => $label,
				'type'      => Controls_Manager::CHOOSE,
				'options'   => $options,
				'default'   => is_rtl() ? 'right' : $default,
				'selectors' => $align_selectors,
				'condition' => $conditions,
			] );
		}

		public function add_font_family( $field_key, $selectors, $label = "", $default = '' ) {
			if ( empty( $label ) ) {
				$label = __( 'Fonts', 'woofunnels-aero-checkout' );
			}
			if ( empty( $default ) ) {
				$default = "Open Sans";
			}
			$fontfamily_selectors = [];
			if ( is_array( $selectors ) && count( $selectors ) > 0 ) {
				foreach ( $selectors as $selector ) {
					$fontfamily_selectors[ $selector ] = 'font-family:{{VALUE}}';
				}
			}

			$args = [
				'name'      => $field_key,
				'label'     => $label,
				'type'      => Controls_Manager::FONT,
				'selectors' => $fontfamily_selectors,
				'default'   => $default,
			];

			$this->add_control( $field_key, $args );
		}

		protected function add_divider( $separator = "" ) {
			if ( empty( $separator ) ) {
				$separator = "none";
			}
			$field_key = 'wffn_' . $this->add_divider_number . '_divider';

			$this->add_control( $field_key, [
				'type'      => Controls_Manager::DIVIDER,
				'separator' => $separator,
			] );
			$this->add_divider_number ++;
		}

		public function add_border_shadow( $field_key, $selector = '', $label = '' ) {
			if ( empty( $label ) ) {
				$label = __( 'Box Shadow', 'elementor' );
			}

			$this->add_group_control( \Elementor\Group_Control_Box_Shadow::get_type(), [
				'name'     => $field_key,
				'label'    => $label,
				'selector' => $selector,
			] );
		}

		public function add_icon( $field_key, $selectors, $label = "" ) {
			if ( empty( $label ) ) {
				$label = __( 'Icon', 'elementor' );
			}
			$this->add_control( $field_key, [
				'label'            => $label,
				'type'             => Controls_Manager::ICONS,
				'selector'         => $selectors,
				'fa4compatibility' => 'icon',
			] );
		}

		public function add_icon_position( $field_key, $selectors, $label = "" ) {
			if ( empty( $label ) ) {
				$label = __( 'Icon Position', 'elementor' );
			}

			$this->add_control( $field_key, [
				'label'    => $label,
				'type'     => Controls_Manager::SELECT,
				'selector' => $selectors,
				'options'  => [
					'left'  => __( 'Before', 'elementor' ),
					'right' => __( 'After', 'elementor' ),
				],
			] );
		}

		public function add_icon_indent( $field_key, $selectors, $label = "", $default = '' ) {
			if ( empty( $label ) ) {
				$label = __( 'Icon Spacing', 'elementor' );
			}
			$this->add_control( $field_key, [
				'label'     => $label,
				'type'      => Controls_Manager::SLIDER,
				'default'   => $default,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-button .elementor-align-icon-left'  => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			] );
		}
	}
}