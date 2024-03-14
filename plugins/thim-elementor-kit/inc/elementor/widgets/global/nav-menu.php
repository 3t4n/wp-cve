<?php

namespace Elementor;

use Thim_EL_Kit\Modules\MegaMenu\Main_Walker;
use Thim_EL_Kit\Settings;

class Thim_Ekit_Widget_Nav_Menu extends Widget_Base {
	public $base;

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-nav-menu';
	}

	public function get_title() {
		return esc_html__( 'Nav Menu', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-nav-menu';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY );
	}

	public function get_keywords() {
		return [
			'thim',
			'menu',
			'nav menu',
		];
	}

	public function get_list_menus() {
		$output = array(
			'0' => esc_html__( '--Select Menu--', 'thim-elementor-kit' ),
		);
		$menus  = wp_get_nav_menus();

		if ( ! empty( $menus ) ) {
			foreach ( $menus as $menu ) {
				$output[$menu->slug] = $menu->name;
			}
		}

		return $output;
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Nav Menu Settings', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'menu_id',
			array(
				'label'   => esc_html__( 'Select Menu', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '0',
				'options' => $this->get_list_menus(),
			)
		);
		$this->add_responsive_control(
			'menu_list_align',
			array(
				'label'     => esc_html__( 'Alignment', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'start', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'end', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'left',
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-menu__container .thim-ekits-menu__nav li::marker' => 'font-size: 0;',
					'{{WRAPPER}} .thim-ekits-menu__container .thim-ekits-menu__nav'            => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Setting
		$this->_register_style_setting_menu_item();
		$this->_register_style_setting_submenu_item();
		$this->_register_style_setting_submenu_panel();
		$this->_register_style_style_mobile();
	}

	protected function _register_style_setting_menu_item() {
		$this->start_controls_section(
			'style_tab_menuitem',
			array(
				'label' => esc_html__( 'Menu item', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'menuitem_content_typography',
				'label'    => esc_html__( 'Typography', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .thim-ekits-menu__container .thim-ekits-menu__nav > li > a',
			)
		);

		$this->add_control(
			'menu_item_h',
			array(
				'label'     => esc_html__( 'Menu Item', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs(
			'nav_menu_tabs'
		);
		// Normal
		$this->start_controls_tab(
			'nav_menu_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' ),
			)
		);

		$this->add_responsive_control(
			'menu_text_color',
			array(
				'label'           => esc_html__( 'Text color', 'thim-elementor-kit' ),
				'type'            => Controls_Manager::COLOR,
				'desktop_default' => '#000000',
				'tablet_default'  => '#000000',
				'selectors'       => array(
					'{{WRAPPER}}' => '--menu-text-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'arrow_icon_color',
			array(
				'label'     => esc_html__( 'Arrow Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--menu-arrow-icon-color: {{VALUE}}',
				),
			)
		);
		$this->end_controls_tab();

		// Hover
		$this->start_controls_tab(
			'nav_menu_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'thim-elementor-kit' ),
			)
		);

		$this->add_responsive_control(
			'item_color_hover',
			array(
				'label'     => esc_html__( 'Text color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#707070',
				'selectors' => array(
					'{{WRAPPER}}' => '--menu-text-color-hover: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		// active
		$this->start_controls_tab(
			'nav_menu_active_tab',
			array(
				'label' => esc_html__( 'Active', 'thim-elementor-kit' ),
			)
		);

		$this->add_responsive_control(
			'nav_menu_active_text_color',
			array(
				'label'     => esc_html__( 'Text color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#707070',
				'selectors' => array(
					'{{WRAPPER}}' => '--menu-active-text-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'menu_item_spacing',
			array(
				'label'           => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'            => Controls_Manager::DIMENSIONS,
				'separator'       => array( 'before' ),
				'desktop_default' => array(
					'top'    => 0,
					'right'  => 15,
					'bottom' => 0,
					'left'   => 15,
					'unit'   => 'px',
				),
				'tablet_default'  => array(
					'top'    => 10,
					'right'  => 15,
					'bottom' => 10,
					'left'   => 15,
					'unit'   => 'px',
				),
				'size_units'      => array( 'px' ),
				'selectors'       => array(
					'{{WRAPPER}} .thim-ekits-menu__nav > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);


		$this->end_controls_section();
	}

	protected function _register_style_setting_submenu_item() {
		$this->start_controls_section(
			'thim_kits_style_tab_submenu_item',
			array(
				'label' => esc_html__( 'Submenu item', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'thim_kits_menu_item_typography',
				'label'    => esc_html__( 'Typography', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .thim-ekits-menu__nav .thim-ekits-menu__dropdown  li > a,{{WRAPPER}} .thim-ekits-menu__nav .thim-ekits-menu__content  li > a',
			)
		);

		$this->add_responsive_control(
			'thim_kits_submenu_item_margin',
			array(
				'label'           => esc_html__( 'Margin', 'thim-elementor-kit' ),
				'type'            => Controls_Manager::DIMENSIONS,
				'devices'         => array( 'desktop', 'tablet' ),
				'desktop_default' => array(
					'top'    => 15,
					'right'  => 15,
					'bottom' => 15,
					'left'   => 15,
					'unit'   => 'px',
				),
				'tablet_default'  => array(
					'top'    => 15,
					'right'  => 15,
					'bottom' => 15,
					'left'   => 15,
					'unit'   => 'px',
				),
				'size_units'      => array( 'px' ),
				'selectors'       => array(
					'{{WRAPPER}} .thim-ekits-menu__nav .thim-ekits-menu__dropdown  li,{{WRAPPER}} .thim-ekits-menu__nav .thim-ekits-menu__content  li ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'thim_kits_submenu_item_spacing',
			array(
				'label'           => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'            => Controls_Manager::DIMENSIONS,
				'devices'         => array( 'desktop', 'tablet' ),
				'desktop_default' => array(
					'top'    => 15,
					'right'  => 15,
					'bottom' => 15,
					'left'   => 15,
					'unit'   => 'px',
				),
				'tablet_default'  => array(
					'top'    => 15,
					'right'  => 15,
					'bottom' => 15,
					'left'   => 15,
					'unit'   => 'px',
				),
				'size_units'      => array( 'px' ),
				'selectors'       => array(
					'{{WRAPPER}} .thim-ekits-menu__nav .thim-ekits-menu__dropdown  li,{{WRAPPER}} .thim-ekits-menu__nav .thim-ekits-menu__content  li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs(
			'thim_kits_submenu_active_hover_tabs'
		);
		$this->start_controls_tab(
			'thim_kits_submenu_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' ),
			)
		);

		$this->add_responsive_control(
			'thim_kits_submenu_item_color',
			array(
				'label'     => esc_html__( 'Text color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => array(
					'{{WRAPPER}}' => '--submenu-item-color: {{VALUE}}',
				),

			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'thim_kits_submenu_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'thim-elementor-kit' ),
			)
		);

		$this->add_responsive_control(
			'thim_kits_submenu_item_color_hover',
			array(
				'label'     => esc_html__( 'Text color (hover)', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#707070',
				'selectors' => array(
					'{{WRAPPER}}' => '--submenu-item-color-hover: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'thim_kits_submenu_active_tab',
			array(
				'label' => esc_html__( 'Active', 'thim-elementor-kit' ),
			)
		);

		$this->add_responsive_control(
			'thim_kits_submenu_item_color_active',
			array(
				'label'     => esc_html__( 'Text color (Active)', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#707070',
				'selectors' => array(
					'{{WRAPPER}}' => '--submenu-item-color-active: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'thim_kits_menu_item_border_heading',
			array(
				'label'     => esc_html__( 'Sub Menu Items Border', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'thim_kits_menu_item_border',
				'label'    => esc_html__( 'Border', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .thim-ekits-menu__nav .thim-ekits-menu__content  li,{{WRAPPER}} .thim-ekits-menu__nav .thim-ekits-menu__dropdown  li',
			)
		);

		$this->end_controls_section();
	}

	protected function _register_style_setting_submenu_panel() {
		$this->start_controls_section(
			'thim_kits_style_tab_submenu_panel',
			array(
				'label' => esc_html__( 'Submenu panel', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'thim_kits_sub_panel_margin',
			array(
				'label'      => esc_html__( 'Margin', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-menu__content,{{WRAPPER}} .thim-ekits-menu__dropdown' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'thim_kits_sub_panel_padding',
			array(
				'label'     => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'default'   => array(
					'top'      => '15',
					'bottom'   => '15',
					'left'     => '0',
					'right'    => '0',
					'isLinked' => false,
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-menu__content,{{WRAPPER}} .thim-ekits-menu__dropdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'thim_kits_panel_submenu_border',
				'label'    => esc_html__( 'Panel Menu Border', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .thim-ekits-menu__content,{{WRAPPER}} .thim-ekits-menu__dropdown',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'thim_kits_submenu_container_background',
				'label'    => esc_html__( 'Container background', 'thim-elementor-kit' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .thim-ekits-menu__content,{{WRAPPER}} .thim-ekits-menu__dropdown',
			)
		);

		$this->add_responsive_control(
			'thim_kits_submenu_panel_border_radius',
			array(
				'label'           => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'            => Controls_Manager::DIMENSIONS,
				'desktop_default' => array(
					'top'    => 0,
					'right'  => 0,
					'bottom' => 0,
					'left'   => 0,
					'unit'   => 'px',
				),
				'tablet_default'  => array(
					'top'    => 0,
					'right'  => 0,
					'bottom' => 0,
					'left'   => 0,
					'unit'   => 'px',
				),
				'size_units'      => array( 'px' ),
				'selectors'       => array(
					'{{WRAPPER}} .thim-ekits-menu__content,{{WRAPPER}} .thim-ekits-menu__dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'thim_kits_submenu_container_width',
			array(
				'label'           => esc_html__( 'Container width', 'thim-elementor-kit' ),
				'type'            => Controls_Manager::TEXT,
				'devices'         => array( 'desktop' ),
				'desktop_default' => '220px',
				'tablet_default'  => '200px',
				'selectors'       => array(
					'{{WRAPPER}} .thim-ekits-menu__nav .thim-ekits-menu__dropdown' => 'min-width: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'thim_kits_panel_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .thim-ekits-menu__content,{{WRAPPER}} .thim-ekits-menu__dropdown',
			)
		);

		$this->end_controls_section();
	}
	protected function _register_style_style_mobile() {
		$this->start_controls_section(
			'thim_kits_menu_mobile',
			array(
				'label' => esc_html__( 'Mobile Options', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'menu_mobile_bg_color',
			array(
				'label'           => esc_html__( 'Background color', 'thim-elementor-kit' ),
				'type'            => Controls_Manager::COLOR,
				'selectors'       => array(
					'{{WRAPPER}}' => '--thim-ekits-menu-mobile-container-bgcolor: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'hamburger_button_heading',
			array(
				'label'     => esc_html__( 'Hamburger Button Setting', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'icon_hamburger_margin',
			array(
				'label'           => esc_html__( 'Margin', 'thim-elementor-kit' ),
				'type'            => Controls_Manager::DIMENSIONS,
 				'size_units'      => array( 'px' ),
				'selectors'       => array(
					'{{WRAPPER}} .thim-ekits-menu__mobile .thim-ekits-menu__mobile__icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'icon_hamburger_border',
				'label'    => esc_html__( 'Border', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .thim-ekits-menu__mobile',
			)
		);
		$this->add_control(
			'icon_hamburger_border_radius',
			array(
				'label'           => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'            => Controls_Manager::DIMENSIONS,
 				'size_units'      => array( 'px','%' ),
				'selectors'       => array(
					'{{WRAPPER}} .thim-ekits-menu__mobile' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'bg_icon_hamburger',
			array(
				'label'           => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'            => Controls_Manager::COLOR,
				'selectors'       => array(
					'{{WRAPPER}}' => '--thim-ekits-menu-mobile-bg-button-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'icon_hamburger',
			array(
				'label'           => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'            => Controls_Manager::COLOR,
				'selectors'       => array(
					'{{WRAPPER}}' => '--thim-ekits-menu-mobile-button-color: {{VALUE}}',
				),
			)
		);


		$this->end_controls_section();
	}
	public function render() {
		$settings = $this->get_settings_for_display();
		if ( ! empty( $settings['menu_id'] ) && is_nav_menu( $settings['menu_id'] ) ) {
			$btn_mobile_close = '<button class="thim-ekits-menu__mobile__close">
									<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
									</svg>
								</button>';
			$args             = array(
				'items_wrap'      => apply_filters( 'thim_ekit/nav-menu/before/items_wrap', $btn_mobile_close ) . '<ul id="%1$s" class="%2$s">%3$s</ul>',
				'container'       => 'div',
				'container_id'    => 'thim-ekits-menu-' . esc_attr( $settings['menu_id'] ),
				'container_class' => 'thim-ekits-menu__container',
				'menu'            => $settings['menu_id'],
				'menu_class'      => 'thim-ekits-menu__nav navbar-main-menu',
				'depth'           => 4,
				'echo'            => true,
				'fallback_cb'     => 'wp_page_menu',
			);

			if ( Settings::instance()->get_enable_modules( 'megamenu' ) ) {
				$args['walker'] = new Main_Walker();
			}

			?>

			<div class="thim-ekits-menu">
				<button class="thim-ekits-menu__mobile">
					<span class="thim-ekits-menu__mobile__icon thim-ekits-menu__mobile__icon--open">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
							 stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								  d="M4 6h16M4 12h16M4 18h16"/>
						</svg>
					</span>
				</button>
				<?php wp_nav_menu( $args ); ?>
			</div>

			<?php
		} else {
			echo '<small>' . esc_html__( 'Edit widget and choose a menu', 'thim-elementor-kit' ) . '</small>';
		}
	}
}
