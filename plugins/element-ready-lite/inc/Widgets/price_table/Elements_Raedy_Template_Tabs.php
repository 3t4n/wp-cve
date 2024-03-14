<?php

namespace Element_Ready\Widgets\price_table;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Repeater;
use \Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use \Elementor\Core\Kits\Documents\Tabs\Global_Typography;

/**
 * Elementor tabs widget.
 *
 * Elementor widget that displays vertical or horizontal tabs with different
 * pieces of content.
 *
 * @since 1.0.0
 */
class Elements_Raedy_Template_Tabs extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve tabs widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'tabs';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve tabs widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Tabs', 'element-ready-lite' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve tabs widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-tabs';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'tabs', 'accordion', 'toggle' ];
	}

	public function element_ready_elementor_template() {

        $templates = Plugin::instance()->templates_manager->get_source( 'local' )->get_items();
        $types     = array();
        if ( empty( $templates ) ) {
            $template_lists = [ '0' => esc_html__( 'Do not Saved Templates.', 'element-ready-lite' ) ];
        } else {
            $template_lists = [ '0' => esc_html__( 'Select Template', 'element-ready-lite' ) ];
            foreach ( $templates as $template ) {
                $template_lists[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
            }
        }
        return $template_lists;
    }

	/**
	 * Register tabs widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 3.1.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_tabs',
			[
				'label' => esc_html__( 'Tabs', 'element-ready-lite' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'tab_title',
			[
				'label' => esc_html__( 'Title & Description', 'element-ready-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Tab Title', 'element-ready-lite' ),
				'placeholder' => esc_html__( 'Tab Title', 'element-ready-lite' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'tab_icon',
			[
				'label' => esc_html__( 'Icon', 'element-ready-lite' ),
				'type' => \Elementor\Controls_Manager::ICONS,
			]
		);

		$repeater->add_control(
			'element_ready_text_type', [
                'label'   => esc_html__('Content Type', 'element-ready-lite'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'content'  => esc_html__('Content', 'element-ready-lite'),
                    'template' => esc_html__('Saved Templates', 'element-ready-lite'),
                ],
                'default' => 'content',
			]
        );

		$repeater->add_control(
			'tab_content',
			[
				'label' => esc_html__( 'Content', 'element-ready-lite' ),
				'default' => esc_html__( 'Tab Content', 'element-ready-lite' ),
				'placeholder' => esc_html__( 'Tab Content', 'element-ready-lite' ),
				'type' => Controls_Manager::WYSIWYG,
				'show_label' => false,
				'condition' => [
					'element_ready_text_type' => ['content']
				]
			]
		);

		$repeater->add_control(
			'element_ready_primary_templates', [
                'label'     => esc_html__('Choose Template', 'element-ready-lite'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'options'   => $this->element_ready_elementor_template(),
                'condition' => [
                    'element_ready_text_type' => 'template',
                ],
			]
        ); 

		$this->add_control(
			'tabs',
			[
				'label' => esc_html__( 'Tabs Items', 'element-ready-lite' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'tab_title' => esc_html__( 'Tab #1', 'element-ready-lite' ),
						'tab_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'element-ready-lite' ),
					],
					[
						'tab_title' => esc_html__( 'Tab #2', 'element-ready-lite' ),
						'tab_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'element-ready-lite' ),
					],
				],
				'title_field' => '{{{ tab_title }}}',
			]
		);

		$this->add_control(
			'view',
			[
				'label' => esc_html__( 'View', 'element-ready-lite' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->add_control(
			'type',
			[
				'label' => esc_html__( 'Position', 'element-ready-lite' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'element-ready-lite' ),
					'vertical' => esc_html__( 'Vertical', 'element-ready-lite' ),
				],
				'prefix_class' => 'elementor-tabs-view-',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tabs_align_horizontal',
			[
				'label' => esc_html__( 'Alignment', 'element-ready-lite' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'' => [
						'title' => esc_html__( 'Start', 'element-ready-lite' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'element-ready-lite' ),
						'icon' => 'eicon-h-align-center',
					],
					'end' => [
						'title' => esc_html__( 'End', 'element-ready-lite' ),
						'icon' => 'eicon-h-align-right',
					],
					'stretch' => [
						'title' => esc_html__( 'Justified', 'element-ready-lite' ),
						'icon' => 'eicon-h-align-stretch',
					],
				],
				'prefix_class' => 'elementor-tabs-alignment-',
				'condition' => [
					'type' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'tabs_align_vertical',
			[
				'label' => esc_html__( 'Alignment', 'element-ready-lite' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'' => [
						'title' => esc_html__( 'Start', 'element-ready-lite' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'element-ready-lite' ),
						'icon' => 'eicon-v-align-middle',
					],
					'end' => [
						'title' => esc_html__( 'End', 'element-ready-lite' ),
						'icon' => 'eicon-v-align-bottom',
					],
					'stretch' => [
						'title' => esc_html__( 'Justified', 'element-ready-lite' ),
						'icon' => 'eicon-v-align-stretch',
					],
				],
				'prefix_class' => 'elementor-tabs-alignment-',
				'condition' => [
					'type' => 'vertical',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_tabs_style',
			[
				'label' => esc_html__( 'Tabs', 'element-ready-lite' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'navigation_width',
			[
				'label' => esc_html__( 'Navigation Width', 'element-ready-lite' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-tabs-wrapper' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'type' => 'vertical',
				],
			]
		);

		$this->add_control(
			'border_width',
			[
				'label' => esc_html__( 'Border Width', 'element-ready-lite' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-tab-title, {{WRAPPER}} .elementor-tab-title:before, {{WRAPPER}} .elementor-tab-title:after, {{WRAPPER}} .elementor-tab-content, {{WRAPPER}} .elementor-tabs-content-wrapper' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'border_color',
			[
				'label' => esc_html__( 'Border Color', 'element-ready-lite' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-tab-mobile-title, {{WRAPPER}} .elementor-tab-desktop-title.elementor-active, {{WRAPPER}} .elementor-tab-title:before, {{WRAPPER}} .elementor-tab-title:after, {{WRAPPER}} .elementor-tab-content, {{WRAPPER}} .elementor-tabs-content-wrapper' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => esc_html__( 'Background Color', 'element-ready-lite' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-tab-desktop-title.elementor-active' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-tabs-content-wrapper' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_title',
			[
				'label' => esc_html__( 'Title', 'element-ready-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tab_color',
			[
				'label' => esc_html__( 'Color', 'element-ready-lite' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-tab-title, {{WRAPPER}} .elementor-tab-title a' => 'color: {{VALUE}}',
				],
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
			]
		);

		$this->add_control(
			'tab_active_color',
			[
				'label' => esc_html__( 'Active Color', 'element-ready-lite' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-tab-title.elementor-active,
					 {{WRAPPER}} .elementor-tab-title.elementor-active a' => 'color: {{VALUE}}',
				],
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_typography',
				'selector' => '{{WRAPPER}} .elementor-tab-title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'text_stroke',
				'selector' => '{{WRAPPER}} .elementor-tab-title',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_shadow',
				'selector' => '{{WRAPPER}} .elementor-tab-title',
			]
		);

		$this->add_control(
			'title_align',
			[
				'label' => esc_html__( 'Alignment', 'element-ready-lite' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'element-ready-lite' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'element-ready-lite' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'element-ready-lite' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-tab-title' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'tabs_align' => 'stretch',
				],
			]
		);

		$this->add_control(
			'heading_content',
			[
				'label' => esc_html__( 'Content', 'element-ready-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Color', 'element-ready-lite' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-tab-content' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .elementor-tab-content',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'content_shadow',
				'selector' => '{{WRAPPER}} .elementor-tab-content',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_tabs_icon_style',
			[
				'label' => esc_html__( 'Tabs Icon', 'element-ready-lite' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

			// $this->add_control(
			// 	'tab_icon_align',
			// 	[
			// 		'label' => esc_html__( 'Icon Position Right', 'element-ready-lite' ),
			// 		'type' => \Elementor\Controls_Manager::SWITCHER,
			// 		'label_on' => esc_html__( 'Show', 'your-plugin' ),
			// 		'label_off' => esc_html__( 'Hide', 'your-plugin' ),
			// 		'return_value' => 'yes',
			// 		'default' => 'yes',
			// 	]
			// );

			$this->add_responsive_control(
				'tab__icon_align',
				[
					'label' => esc_html__( 'Alignment', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::CHOOSE,
					'options' => [
						'display:inline-flex;flex-direction:row;' => [
							'title' => esc_html__( 'Row', 'element-ready-lite' ),
							'icon' => 'eicon-h-align-left',
						],
						'display:inline-flex;flex-direction:row-reverse;' => [
							'title' => esc_html__( 'Row Reverse', 'element-ready-lite' ),
							'icon' => 'eicon-h-align-right',
						],
						'display:inline-flex;flex-direction:column;' => [
							'title' => esc_html__( 'Column', 'element-ready-lite' ),
							'icon' => 'eicon-justify-start-v',
						],
						'display:inline-flex;flex-direction:column-reverse;' => [
							'title' => esc_html__( 'Column', 'element-ready-lite' ),
							'icon' => 'eicon-justify-end-v',
						],
					],
					'default' => '',
					'toggle' => true,
					'selectors' => [
						'body {{WRAPPER}} .elementor-tab-desktop-title' => '{{VALUE}}',
					],
				]
			);

			$this->add_responsive_control(
				'tab_cv_icon_align',
				[
					'label' => esc_html__( 'Column Alignment', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::CHOOSE,
					'condition' => [

						'tab__icon_align' => [
							'display:inline-flex;flex-direction:column-reverse;',
							'display:inline-flex;flex-direction:column;'
						]
						
					],
					'options' => [

						'align-items:flex-start;' => [
							'title' => esc_html__( 'Row', 'element-ready-lite' ),
							'icon' => 'eicon-h-align-left',
						],
						'align-items:flex-end;' => [
							'title' => esc_html__( 'Row Reverse', 'element-ready-lite' ),
							'icon' => 'eicon-h-align-right',
						],
						'align-items:center;' => [
							'title' => esc_html__( 'Column', 'element-ready-lite' ),
							'icon' => 'eicon-justify-start-v',
						],
					
					],
					'default' => '',
					'toggle' => true,
					'selectors' => [
						'body {{WRAPPER}} .elementor-tab-desktop-title' => '{{VALUE}}',
					],
				]
			);

		
			$this->add_control(
				'tab_icon_color',
				[
					'label' => esc_html__( 'Color', 'element-ready-lite' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elementor-tab-title i' => 'color: {{VALUE}};',
						'{{WRAPPER}} .elementor-tab-title svg' => 'fill: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'tab_icon_active_color',
				[
					'label' => esc_html__( 'Active Color', 'element-ready-lite' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elementor-tab-title.elementor-active i' => 'color: {{VALUE}}',
						'{{WRAPPER}} .elementor-tab-title.elementor-active svg' => 'fill: {{VALUE}}',
					],
					
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'content_tab_icon_typography',
					'selector' => '{{WRAPPER}} .elementor-tab-title i',
				]
			);

			$this->add_responsive_control(
				'tab_icon_gap',
				[
					'label' => esc_html__( 'Gap', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
							'step' => 1,
						],
					],
					
					'selectors' => [
						'{{WRAPPER}} .elementor-tab-title' => 'gap: {{SIZE}}{{UNIT}};',
					],
				]
			);

			

		$this->end_controls_section();

		$this->start_controls_section(
			'section_tabs_wrapper_style',
			[
				'label' => esc_html__( 'Tabs Menu Wrapper', 'element-ready-lite' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'tab_menu_e_r__border',
					'label' => esc_html__( 'Border', 'element-ready-lite' ),
					'selector' => '{{WRAPPER}} .elementor-tabs-wrapper .elementor-tab-title',
				]
			);

			$this->add_control(
				'tab_menu_e_rmore_options',
				[
					'label' => esc_html__( 'Active Border', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'tab_menu_e_active_r__border',
					'label' => esc_html__( 'Active Border', 'element-ready-lite' ),
					'selector' => '{{WRAPPER}} .elementor-tabs-wrapper .elementor-active.elementor-tab-title',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'section_tabs_menu_wrapper_background',
					'label' => esc_html__( 'Background', 'element-ready-lite' ),
					'types' => [ 'classic', 'gradient'],
					'selector' => '{{WRAPPER}} .elementor-tabs-wrapper',
				]
			);

			$this->add_responsive_control(
				'tab_item_padding',
				[
					'label' => esc_html__( 'Menu Padding', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .elementor-tab-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_tabs_content_er_style',
			[
				'label' => esc_html__( 'Tabs Content', 'element-ready-lite' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'tab_content_er_padding',
				[
					'label' => esc_html__( 'Padding', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .elementor-tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'tab_content_er_margin',
				[
					'label' => esc_html__( 'Margin', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .elementor-tab-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'tab_content_er__border',
					'label' => esc_html__( 'Border', 'element-ready-lite' ),
					'selector' => '{{WRAPPER}} .elementor-tab-content',
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Render tabs widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$tabs = $this->get_settings_for_display( 'tabs' );
		$id_int = substr( $this->get_id_int(), 0, 3 );
		$a11y_improvements_experiment = Plugin::$instance->experiments->is_feature_active( 'a11y_improvements' );
		$this->add_render_attribute( 'elementor-tabs', 'class', 'elementor-tabs' );

		?>
		<div <?php $this->print_render_attribute_string( 'elementor-tabs' ); ?>>
			<div class="elementor-tabs-wrapper" role="tablist" >
				<?php
				foreach ( $tabs as $index => $item ) :
					$tab_count = $index + 1;
					$tab_title_setting_key = $this->get_repeater_setting_key( 'tab_title', 'tabs', $index );
					$tab_title = $a11y_improvements_experiment ? $item['tab_title'] : '<a href="">' . $item['tab_title'] . '</a>';

					$this->add_render_attribute( $tab_title_setting_key, [
						'id' => 'elementor-tab-title-' . esc_attr($id_int . $tab_count),
						'class' => [ 'elementor-tab-title', 'elementor-tab-desktop-title' ],
						'aria-selected' => 1 === $tab_count ? 'true' : 'false',
						'data-tab' => $tab_count,
						'role' => 'tab',
						'tabindex' => 1 === $tab_count ? '0' : '-1',
						'aria-controls' => 'elementor-tab-content-' . esc_attr($id_int . $tab_count),
						'aria-expanded' => 'false',
					] );
					?>
					<div <?php $this->print_render_attribute_string( $tab_title_setting_key ); ?>><?php
						// PHPCS - the main text of a widget should not be escaped.
						\Elementor\Icons_Manager::render_icon( $item['tab_icon'], [ 'class' => 'er-tab-icon' ] );
						echo wp_kses_post( $tab_title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?></div>
				<?php endforeach; ?>
			</div>
			<div class="elementor-tabs-content-wrapper" role="tablist" aria-orientation="vertical">
				<?php
				foreach ( $tabs as $index => $item ) :
					$tab_count = $index + 1;
					$hidden = 1 === $tab_count ? 'false' : 'hidden';
					$tab_content_setting_key = $this->get_repeater_setting_key( 'tab_content', 'tabs', $index );
					$tab_title_mobile_setting_key = $this->get_repeater_setting_key( 'tab_title_mobile', 'tabs', $tab_count );

					$this->add_render_attribute( $tab_content_setting_key, [
						'id' => 'elementor-tab-content-' . esc_attr($id_int . $tab_count),
						'class' => [ 'elementor-tab-content', 'elementor-clearfix' ],
						'data-tab' => esc_attr($tab_count),
						'role' => 'tabpanel',
						'aria-labelledby' => 'elementor-tab-title-' . esc_attr($id_int . $tab_count),
						'tabindex' => '0',
						'hidden' => $hidden,
					] );

					$this->add_render_attribute( $tab_title_mobile_setting_key, [
						'class' => [ 'elementor-tab-title', 'elementor-tab-mobile-title' ],
						'aria-selected' => 1 === $tab_count ? 'true' : 'false',
						'data-tab' => esc_attr($tab_count),
						'role' => 'tab',
						'tabindex' => 1 === $tab_count ? '0' : '-1',
						'aria-controls' => 'elementor-tab-content-' . esc_attr($id_int . $tab_count),
						'aria-expanded' => 'false',
					] );

					$this->add_inline_editing_attributes( $tab_content_setting_key, 'advanced' );
					?>
					<div <?php $this->print_render_attribute_string( $tab_title_mobile_setting_key ); ?>><?php
						$this->print_unescaped_setting( 'tab_title', 'tabs', $index );
					?></div>
					<div <?php $this->print_render_attribute_string( $tab_content_setting_key ); ?>><?php
						if($item['element_ready_text_type'] == 'template'){
							echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($item['element_ready_primary_templates'],true);
						}else{
							$this->print_text_editor( $item['tab_content'] );
						}
						
					?></div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	
}
