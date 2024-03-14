<?php

namespace Shop_Ready\extension\elewidgets\widgets\product;

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

/**
 * WooCommerce Product Title | Name
 * @see https://docs.woocommerce.com/document/managing-products/
 * @author quomodosoft.com
 */
class Title extends \Shop_Ready\extension\elewidgets\Widget_Base
{


	/**
	 * Html Wrapper Class of html 
	 */
	public $wrapper_class = false;
	public static function title_before_style()
	{
		return [
			'set_title_before' => esc_html__('Set Title Before', 'shopready-elementor-addon'),
		];
	}
	protected function register_controls()
	{

		// Notice 
		$this->start_controls_section(
			'notice_content_section',
			[
				'label' => esc_html__('Notice', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'woo_ready_usage_direction_notice',
			[
				'label' => esc_html__('Important Note', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => esc_html__('Use This Widget in WooCommerce Product Details page  Template.', 'shopready-elementor-addon'),
				'content_classes' => 'woo-ready-product-page-notice',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'editor_content_section',
			[
				'label' => esc_html__('Editor Refresh', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_product_content',
			[
				'label' => esc_html__('Content Refresh?', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
				'label_off' => esc_html__('No', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'wready_product_id',
			[
				'label' => esc_html__('Demo Product', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'default' => shop_ready_get_single_product_key(),
				'options' => shop_ready_get_latest_products_id(50)
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'layouts_cart_content_section',
			[
				'label' => esc_html__('Title Layout', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'style',
			[
				'label' => esc_html__('Layout', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'wready-title-one',
				'options' => [
					'wready-title-one' => esc_html__('Style 1', 'shopready-elementor-addon'),


				]
			]
		);


		$this->end_controls_section();


		$this->start_controls_section(
			'content_cart_total_section',
			[
				'label' => esc_html__('Title Settings', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);



		$this->add_control(
			'title_tag',
			[
				'label' => esc_html__('Title Tag', 'elementor'),
				'type' => Controls_Manager::SELECT,
				'options' => shop_ready_html_tags_options(),
				'default' => 'h3',
				'separator' => 'before',
			]
		);


		$this->end_controls_section();


		/*----------------------------
				  TITLE STYLE
			  -----------------------------*/
		$this->text_css([
			'title' => esc_html__('Title', 'shopready-elementor-addon'),
			'slug' => 'woo_ready_product_title',
			'element_name' => '__woo_ready_product_title',
			'selector' => '{{WRAPPER}} .area__title',
			'hover_selector' => false,
			'disable_controls' => ['position', 'size', 'display', 'dimensions', 'bg', 'border', 'box-shadow']
		]);
		/*----------------------------
				  TITLE STYLE END
			  -----------------------------*/
	}

	/**
	 * Override By elementor render method
	 * @return void
	 * 
	 */
	protected function html()
	{

		$settings = $this->get_settings_for_display();

		if (shop_ready_is_elementor_mode()) {
			$temp_id = WC()->session->get('sr_single_product_id');
			if ($settings['show_product_content'] == 'yes' && is_numeric($settings['wready_product_id'])) {
				$temp_id = $settings['wready_product_id'];
			}
			if (is_numeric($temp_id)) {
				setup_postdata($temp_id);
			} else {
				setup_postdata(shop_ready_get_single_product_key());
			}
		}
		shop_ready_widget_template_part(
			'product/template-parts/title/wready-title-one.php',
			array(
				'settings' => $settings,
			)
		);
	}
}