<?php

namespace Shop_Ready\extension\elewidgets\widgets\general;

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
use Shop_Ready\base\elementor\style_controls\common\Widget_Animation;

/**
 * Popup
 * 
 * @author quomodosoft.com
 */
class Popup extends \Shop_Ready\extension\elewidgets\Widget_Base
{

	use Widget_Animation;
	/**
	 * Html Wrapper Class of html 
	 */
	public $wrapper_class = true;
	public function layout()
	{
		return [

			'style1' => esc_html__('Classic Popup', 'shopready-elementor-addon'),
			'style2' => esc_html__('Nifty Popup', 'shopready-elementor-addon'),

		];
	}
	protected function register_controls()
	{

		$this->start_controls_section(
			'menu_layout',
			[
				'label' => esc_html__('Layout', 'shopready-elementor-addon'),
			]
		);

		$this->add_control(
			'_style',
			[
				'label' => esc_html__('Style', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'style1',
				'options' => $this->layout()
			]
		);





		$this->end_controls_section();
		$this->start_controls_section(
			'interface_pop',
			[
				'label' => esc_html__('Interface', 'shopready-elementor-addon'),
			]
		);

		$this->add_control(
			'user_label',
			[
				'label' => __('Label', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'Account',
				'placeholder' => 'Account',

			]
		);

		$this->add_control(
			'icon_before_text',
			[
				'label' => __('Icon Before Text', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'shopready-elementor-addon'),
				'label_off' => __('No', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => 'yes',

			]
		);



		$this->add_control(
			'nav_icon',
			[
				'label' => esc_html__('Icon', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::ICONS,

			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'innercontent_pop',
			[
				'label' => esc_html__('Inner Content', 'shopready-elementor-addon'),
			]
		);

		$this->add_control(
			'modal_template_id',
			[
				'label' => esc_html__('Select Content Template', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SELECT2,
				'default' => '0',
				'multiple' => false,
				'condition' => [
					'_style' => ['style1', 'style2']
				],
				'options' => shop_ready_get_elementor_templates_arr(),
				'description' => esc_html__('Please select elementor templete from here, if not create elementor template from menu', 'shopready-elementor-addon')

			]
		);

		$this->add_control(
			'close_icon',
			[
				'label' => __('Close icon', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-times',
					'library' => 'solid',
				],
				'condition' => [
					'_style' => ['style1']
				],
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'_popup_inner_content_pop',
			[
				'label' => esc_html__('Popup Modal Style2', 'shopready-elementor-addon'),
				'condition' => [
					'_style' => ['style2']
				],
			]
		);

		$this->add_control(
			'shop_ready_popup_close',
			[
				'label' => __('Close?', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'shopready-elementor-addon'),
				'label_off' => __('Hide', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'_style' => ['style2']
				],
			]
		);

		$this->add_control(
			'shop_ready_popup_close_icon',
			[
				'label' => __('Close Icon', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fa fa-times',
					'library' => 'solid',
				],
				'condition' => [
					'_style' => ['style2', 'style1']
				],
			]
		);

		$this->add_control(
			'shop_ready_popup_modal_animation',
			[
				'label' => __('Animation', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'slide-in-bottom',
				'options' => [
					'slide-in-bottom' => __('Slide In Bottom', 'shopready-elementor-addon'),
					'fade-in-scale' => __('Fade Scale', 'shopready-elementor-addon'),
					'slide-in-right' => __('Slide Right', 'shopready-elementor-addon'),
					'newspaper' => __('Newspaper', 'shopready-elementor-addon'),
					'fall' => __('Fall', 'shopready-elementor-addon'),
					'slide-fall-in' => __('SLide Fall In', 'shopready-elementor-addon'),
					'slide-in-top-stick' => __('Slide In Top', 'shopready-elementor-addon'),
					'super-scaled' => __('Super Scale', 'shopready-elementor-addon'),
					'just-me' => __('Just Me', 'shopready-elementor-addon'),
					'blur' => __('Blur', 'shopready-elementor-addon'),
					'slide-in-bottom-perspective' => __('Slide Bottom Perspective', 'shopready-elementor-addon'),
					'slide-in-right-prespective' => __('Slide Right Perspective', 'shopready-elementor-addon'),
					'slip-in-top-perspective' => __('Slip Perspective', 'shopready-elementor-addon'),
					'threed-flip-horizontal' => __('3D Flip Horizontal', 'shopready-elementor-addon'),
					'threed-flip-vertical' => __('3D Flip Vertical', 'shopready-elementor-addon'),
					'threed-sign' => __('3d Sign', 'shopready-elementor-addon'),
					'threed-slit' => __('3D Slit', 'shopready-elementor-addon'),
					'threed-rotate-bottom' => __('3D Rotate Bottom', 'shopready-elementor-addon'),
					'threed-rotate-in-left' => __('3D Rotate Left', 'shopready-elementor-addon'),
				],
				'condition' => [
					'_style' => ['style2']
				],
			]
		);

		$this->add_responsive_control(
			'shop_ready_newslatter_modal_width',
			[
				'label' => __('Width', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 80,
				],
				'selectors' => [
					'{{WRAPPER}} .shop-ready-pro-minipopup-popup-modal' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'_style' => ['style2']
				],
			]
		);

		$this->add_responsive_control(
			'shop_ready_newslatter_min_width',
			[
				'label' => __('Minimum Width', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 320,
				],
				'selectors' => [
					'{{WRAPPER}} .shop-ready-pro-minipopup-popup-modal' => 'min-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'_style' => ['style2']
				],
			]
		);

		$this->add_responsive_control(
			'shop_ready_newslatter_max_width',
			[
				'label' => __('Max Width', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 80,
				],
				'selectors' => [
					'{{WRAPPER}} .shop-ready-pro-minipopup-popup-modal' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'_style' => ['style2']
				],
			]
		);

		$this->add_responsive_control(
			'shop_ready_newslatter_height',
			[
				'label' => __('Height', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],

				'default' => [
					'unit' => '%',
					'size' => 80,
				],

				'selectors' => [
					'{{WRAPPER}} .shop-ready-pro-minipopup-popup-modal' => 'height: {{SIZE}}{{UNIT}};',

				],
				'condition' => [
					'_style' => ['style2']
				],
			]
		);

		$this->add_responsive_control(
			'shop_ready_newslatter_min_height',
			[
				'label' => __('Minimum Height', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 80,
				],
				'selectors' => [
					'{{WRAPPER}} .shop-ready-pro-minipopup-popup-modal' => 'min-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'_style' => ['style2']
				],
			]
		);

		$this->add_control(
			'shop_ready_newslatter_overflow_y',
			[
				'label' => __('Overflow Vertical', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'hidden',
				'options' => [
					'hidden' => __('None', 'shopready-elementor-addon'),
					'scroll' => __('Scroll', 'shopready-elementor-addon'),
				],
				'selectors' => [
					'{{WRAPPER}} .shop-ready-pro-minipopup-popup-modal' => 'overflow-y: {{VALUE}};',
				],
				'condition' => [
					'_style' => ['style2']
				],
			]
		);

		$this->end_controls_section();

		$this->box_css(
			[
				'title' => esc_html__('Nifty Modal Conatiner', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_user_inifty_icon',
				'element_name' => 'wrating_nifty_n',
				'selector' => '{{WRAPPER}} .shop-ready-pro-minipopup-popup-modal',
				'hover_selector' => false,
				'condition' => [
					'_style' => ['style2']
				],

			]
		);

		$this->box_css(
			[
				'title' => esc_html__('Nifty Modal Close', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_user_inifty_cloose_icon',
				'element_name' => 'wrating_nifty_nss',
				'selector' => '{{WRAPPER}} .wready-md-close',
				'hover_selector' => false,
				'condition' => [
					'_style' => ['style2']
				],

			]
		);

		$this->text_minimum_css(
			[
				'title' => esc_html__('Nifty Modal Close icon', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_user_inifty_cloosei_icon',
				'element_name' => 'wrating_nifty_nssi',
				'selector' => '{{WRAPPER}} .wready-md-close i',
				'hover_selector' => '{{WRAPPER}} .wready-md-close i:hover',
				'condition' => [
					'_style' => ['style2']
				],

			]
		);

		/* Layouts End */
		$this->text_minimum_css(
			[
				'title' => esc_html__('Icon', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_user_interface_icon',
				'element_name' => 'wrating_user_interfacde_icon',
				'selector' => '{{WRAPPER}} .woo-ready-user-interface i',
				'hover_selector' => '{{WRAPPER}} .woo-ready-user-interface:hover i',
				'disable_controls' => [
					'display',
				],
			]
		);

		$this->text_wrapper_css(
			[
				'title' => esc_html__('Text', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_cart_count_s',
				'element_name' => 'wrating_count_texts',
				'selector' => '{{WRAPPER}} .woo-ready-user-interface .shop-ready-mini-popup-label-modifire',
				'hover_selector' => '{{WRAPPER}} .woo-ready-user-interface .shop-ready-mini-popup-label-modifire:hover',
				'disable_controls' => [
					'position',
				],
			]
		);

		$this->box_css(
			[
				'title' => esc_html__('PopUp Container', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_cart_popp',
				'element_name' => 'wrating_popup',
				'selector' => '{{WRAPPER}} .woo-ready-sub-content',
				'hover_selector' => false,
				'condition' => [
					'_style' => ['style1']
				],
				'disable_controls' => [
					'size',
					'display',
					'bg',
					'border',
					'dimensions',
					'alignment',
					'box-shadow'
				],
			]
		);

		$this->animate(['title' => 'Modal Animate', 'slug' => '_kt', 'hover' => false]);

		$this->box_css(
			[
				'title' => esc_html__('Close Icon Wrapper', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_user_close_icon',
				'element_name' => 'wrating_user_close_icon',
				'selector' => '{{WRAPPER}} .shop-ready-cart-count-close-btn',
				'hover_selector' => false,
				'condition' => [
					'_style' => ['style1']
				],
				'disable_controls' => [
					'size',
					'display',
					'alignment',
					'bg',
					'dimensions',
					'border'
				],
			]
		);

		$this->start_controls_section(
			'close_cioncontent_section',
			[
				'label' => __('Close icon', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'_style' => ['style1']
				],
			]
		);

		$this->add_control(
			'shop_ready_pro_icon_color',
			[
				'label' => __('Color', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::COLOR,

				'selectors' => [
					'{{WRAPPER}} .shop-ready-cart-count-close-btn i' => 'color: {{VALUE}}',
				],
			]
		);


		$this->add_control(
			'shop_ready_pro_icon_hover_color',
			[
				'label' => __('hover Color', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::COLOR,

				'selectors' => [
					'{{WRAPPER}} .shop-ready-cart-count-close-btn i:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'shop_ready_pro_icon_c_typography',
				'label' => __('Typography', 'shopready-elementor-addon'),
				'selector' => '{{WRAPPER}} .shop-ready-cart-count-close-btn i',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'shop_ready_pro_icon_colorbackground',
				'label' => __('Background', 'shopready-elementor-addon'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .shop-ready-cart-count-close-btn i',
			]
		);

		$this->add_control(
			'shop_ready_pro_icon_comargin',
			[
				'label' => __('Margin', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .shop-ready-cart-count-close-btn i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'shop_ready_pro_icon_padding',
			[
				'label' => __('Padding', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .shop-ready-cart-count-close-btn i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'shop_ready_pro_icon_border_radious',
			[
				'label' => __('Border Radius', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .shop-ready-cart-count-close-btn i' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'shop_ready_pro_icon_border',
				'label' => __('Border', 'shop-raedy-pro'),
				'selector' => '{{WRAPPER}} .shop-ready-cart-count-close-btn i',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Override By elementor render method
	 * @return void
	 * 
	 */
	protected function html()
	{

		$settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			'wrapper_style',
			[
				'class' => ['woo-ready-cart-popup-layout', $settings['_style']],
			]
		);

		echo wp_kses_post(sprintf("<div %s>", $this->get_render_attribute_string('wrapper_style')));

		if (file_exists(dirname(__FILE__) . '/template-parts/popup/' . $settings['_style'] . '.php')) {

			shop_ready_widget_template_part(
				'general/template-parts/popup/' . $settings['_style'] . '.php',
				array(
					'settings' => $settings,
				)
			);
		} else {

			shop_ready_widget_template_part(
				'general/template-parts/popup/style1.php',
				array(
					'settings' => $settings,
				)
			);
		}

		echo wp_kses_post('</div>');
	}
}