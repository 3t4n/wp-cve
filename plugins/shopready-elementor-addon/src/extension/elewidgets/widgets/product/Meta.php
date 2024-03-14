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
 * WooCommerce Product Meta | category | Sku | Tags
 *
 * @see https://docs.woocommerce.com/document/managing-products/
 * @author quomodosoft.com
 */
class Meta extends \Shop_Ready\extension\elewidgets\Widget_Base
{



	/**
	 * Html Wrapper Class of html
	 */
	public $wrapper_class = false;

	protected function register_controls()
	{
		// Notice
		$this->start_controls_section(
			'notice_content_section',
			array(
				'label' => esc_html__('Notice', 'shopready-elementor-addon'),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'woo_ready_usage_direction_notice',
			array(
				'label'           => esc_html__('Important Note', 'shopready-elementor-addon'),
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => esc_html__('Use This Widget in WooCommerce Product Details page  Template.', 'shopready-elementor-addon'),
				'content_classes' => 'woo-ready-product-page-notice',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'editor_content_section',
			array(
				'label' => esc_html__('Editor Refresh', 'shopready-elementor-addon'),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_product_content',
			array(
				'label'        => esc_html__('Content Refresh?', 'shopready-elementor-addon'),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Yes', 'shopready-elementor-addon'),
				'label_off'    => esc_html__('No', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'wready_product_id',
			array(
				'label'    => esc_html__('Demo Product', 'shopready-elementor-addon'),
				'type'     => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'default'  => '',
				'options'  => shop_ready_get_latest_products_id(100),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'layouts_cart_content_section',
			array(
				'label' => esc_html__('Layout', 'shopready-elementor-addon'),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => esc_html__('Layout', 'shopready-elementor-addon'),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'meta',
				'options' => array(
					'meta' => esc_html__('Default', 'shopready-elementor-addon'),
					// 'wready-rating-two'   => esc_html__('Style 2','shopready-elementor-addon'),

				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'content_rating_section',
			array(
				'label' => esc_html__('Settings', 'shopready-elementor-addon'),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'separator',
			array(
				'label'       => esc_html__('Tags / Category Separetor', 'shopready-elementor-addon'),
				'type'        => Controls_Manager::TEXT,
				'default'     => ', ',
				'show_label'  => true,
				'placeholder' => esc_html__(', ', 'shopready-elementor-addon'),

			)
		);

		$this->add_control(
			'show_sku',
			array(
				'label'        => __('Sku?', 'shopready-elementor-addon'),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __('Show', 'shopready-elementor-addon'),
				'label_off'    => __('Hide', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_sku_label',
			array(
				'label'        => __('Sku Label?', 'shopready-elementor-addon'),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __('Show', 'shopready-elementor-addon'),
				'label_off'    => __('Hide', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'show_sku' => array('yes'),
				),
			)
		);

		$this->add_control(
			'show_sku_label_text',
			array(
				'label'       => esc_html__('Sku text', 'shopready-elementor-addon'),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'sku:',
				'show_label'  => false,
				'placeholder' => esc_html__('sku:', 'shopready-elementor-addon'),

			)
		);

		$this->add_control(
			'show_cat',
			array(
				'label'        => __('Category?', 'shopready-elementor-addon'),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __('Show', 'shopready-elementor-addon'),
				'label_off'    => __('Hide', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_cat_label',
			array(
				'label'        => __('Category Label?', 'shopready-elementor-addon'),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __('Show', 'shopready-elementor-addon'),
				'label_off'    => __('Hide', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'show_cat' => array('yes'),
				),
			)
		);

		$this->add_control(
			'show_category_label_text',
			array(
				'label'       => esc_html__('Sku text singular', 'shopready-elementor-addon'),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Category:',
				'show_label'  => true,
				'placeholder' => esc_html__('Categories:', 'shopready-elementor-addon'),

			)
		);

		$this->add_control(
			'show_category_label_text_pl',
			array(
				'label'       => esc_html__('Sku text plural', 'shopready-elementor-addon'),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Categories:',
				'show_label'  => true,
				'placeholder' => esc_html__('Categories:', 'shopready-elementor-addon'),

			)
		);

		$this->add_control(
			'show_tags',
			array(
				'label'        => __('Tags?', 'shopready-elementor-addon'),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __('Show', 'shopready-elementor-addon'),
				'label_off'    => __('Hide', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_tags_label',
			array(
				'label'        => __('Tags Label?', 'shopready-elementor-addon'),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __('Show', 'shopready-elementor-addon'),
				'label_off'    => __('Hide', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'show_tags' => array('yes'),
				),
			)
		);

		$this->add_control(
			'show_tags_label_text',
			array(
				'label'       => esc_html__('Tags text singular', 'shopready-elementor-addon'),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Tag:',
				'show_label'  => true,
				'placeholder' => esc_html__('Tags:', 'shopready-elementor-addon'),

			)
		);

		$this->add_control(
			'show_tags_label_text_pl',
			array(
				'label'       => esc_html__('Tags text plural', 'shopready-elementor-addon'),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Tags:',
				'show_label'  => true,
				'placeholder' => esc_html__('Tags:', 'shopready-elementor-addon'),

			)
		);

		$this->end_controls_section();

		/**
		 * Layouts Total Table
		 */
		$this->box_layout(
			array(
				'title'            => esc_html__('Container Wrapper', 'shopready-elementor-addon'),
				'slug'             => 'wready_wc__product_meta',
				'element_name'     => '__wrapper',
				'selector'         => '{{WRAPPER}} .product_meta',
				'disable_controls' => array(
					'position',
					'box-size',
				),
			)
		);
		$this->box_layout(
			array(
				'title'            => esc_html__('Sku Wrapper', 'shopready-elementor-addon'),
				'slug'             => 'wready_wc_default_product_meta_sku',
				'element_name'     => 'star_wrapper',
				'selector'         => '{{WRAPPER}} .sku_wrapper',
				'disable_controls' => array(
					'position',
					'box-size',
				),
			)
		);

		$this->box_layout(
			array(
				'title'            => esc_html__('Category', 'shopready-elementor-addon'),
				'slug'             => 'wready_wc_default_produt_meta_cat',
				'element_name'     => 'count_wrapper',
				'selector'         => '{{WRAPPER}} .posted_in',
				'disable_controls' => array(
					'position',
					'box-size',
				),

			)
		);

		$this->box_layout(
			array(
				'title'            => esc_html__('Tags', 'shopready-elementor-addon'),
				'slug'             => 'wready_wc_default_product_meta_tags',
				'element_name'     => 'count_wrapper',
				'selector'         => '{{WRAPPER}} .tagged_as',
				'disable_controls' => array(
					'position',
					'box-size',
				),
			)
		);

		/* Layouts End */

		/* Style Tab controls */
		$this->text_minimum_css(
			array(
				'title'            => esc_html__('Sku Label', 'shopready-elementor-addon'),
				'slug'             => 'wready_wc_product_sku_label',
				'element_name'     => 'wrating_product_slu',
				'selector'         => '{{WRAPPER}} .sku_wrapper ,{{WRAPPER}} .sku_wrapper :not(span)',
				'hover_selector'   => false,
				'disable_controls' => array(
					'display',
				),

			)
		);

		$this->text_minimum_css(
			array(
				'title'            => esc_html__('Sku', 'shopready-elementor-addon'),
				'slug'             => 'wready_wc_prodduct_sku',
				'element_name'     => 'wrating_product_sku',
				'selector'         => '{{WRAPPER}} .sku_wrapper span',
				'hover_selector'   => false,
				'disable_controls' => array(
					'display',
				),
			)
		);

		$this->text_minimum_css(
			array(
				'title'            => esc_html__('Category Label', 'shopready-elementor-addon'),
				'slug'             => 'wready_wc_category_leble',
				'element_name'     => 'wrating_product_label',
				'selector'         => '{{WRAPPER}} .posted_in, {{WRAPPER}} .posted_in :not(a)',
				'hover_selector'   => false,
				'disable_controls' => array(
					'display',
				),
			)
		);

		$this->text_css(
			array(
				'title'            => esc_html__('Category', 'shopready-elementor-addon'),
				'slug'             => 'wready_wc_category',
				'element_name'     => 'wrating_product_categry',
				'selector'         => '{{WRAPPER}} .posted_in a',
				'hover_selector'   => '{{WRAPPER}} .posted_in a:hover',
				'disable_controls' => array(
					'display',
					'position',
					'size',
				),

			)
		);

		$this->text_minimum_css(
			array(
				'title'            => esc_html__('Tags Label', 'shopready-elementor-addon'),
				'slug'             => 'wready_wc_wrating_product_tag',
				'element_name'     => 'wrating_product_tag_label',
				'selector'         => '{{WRAPPER}} .tagged_as , {{WRAPPER}} .tagged_as :not(a)',
				'hover_selector'   => false,
				'disable_controls' => array(
					'display',
				),

			)
		);

		$this->text_css(
			array(
				'title'            => esc_html__('Tags', 'shopready-elementor-addon'),
				'slug'             => 'wready_wc_product_tag',
				'element_name'     => 'wrating_product_tag',
				'selector'         => '{{WRAPPER}} .tagged_as a',
				'hover_selector'   => '{{WRAPPER}} .tagged_as a:hover',
				'disable_controls' => array(
					'display',
					'position',
					'size',
				),
			)
		);
	}

	/**
	 * Override By elementor render method
	 *
	 * @return void
	 */
	protected function html()
	{
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			'wrapper_style',
			array(
				'class' => array('woo-ready-product-meta-layout', $settings['style']),
			)
		);

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

		echo wp_kses_post(sprintf('<div %s>', $this->get_render_attribute_string('wrapper_style')));

		if (file_exists(dirname(__FILE__) . '/template-parts/meta/' . $settings['style'] . '.php')) {
			shop_ready_widget_template_part(
				'product/template-parts/meta/' . $settings['style'] . '.php',
				array(
					'settings' => $settings,

				)
			);
		} else {
			shop_ready_widget_template_part(
				'product/template-parts/meta/meta.php',
				array(
					'settings' => $settings,
				)
			);
		}

		echo wp_kses_post('</div>');
	}
}
