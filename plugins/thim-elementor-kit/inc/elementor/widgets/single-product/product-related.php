<?php

namespace Elementor;

use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Thim_EL_Kit\GroupControlTrait;
use Thim_EL_Kit\Elementor\Controls\Controls_Manager as Thim_Control_Manager;

class Thim_Ekit_Widget_Product_Related extends Thim_Ekit_Products_Base{
	use GroupControlTrait;
	public function __construct($data = array(), $args = null){
		parent::__construct($data, $args);
	}

	public function get_name(){
		return 'thim-ekits-product-related';
	}

	public function get_title(){
		return esc_html__('Product Related', 'thim-elementor-kit');
	}

	public function get_icon()
	{
		return 'thim-eicon eicon-product-related';
	}

	public function get_categories(){
		return array(\Thim_EL_Kit\Elementor::CATEGORY_SINGLE_PRODUCT);
	}

	public function get_help_url(){
		return '';
	}

	protected function register_controls(){
		$this->start_controls_section(
			'section_Setting',
			array(
				'label' => esc_html__('Setting', 'thim-elementor-kit'),
			)
		);
		$this->add_control(
			'style',
			array(
				'label'   => esc_html__('Skin', 'thim-elementor-kit'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => esc_html__('Default', 'thim-elementor-kit'),
					'slider'  => esc_html__('Slider', 'thim-elementor-kit'),
				),
			)
		);
		$this->add_control(
			'build_loop_item',
			array(
				'label'     => esc_html__('Build Loop Item', 'thim-elementor-kit'),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'template_id',
			array(
				'label'   => esc_html__('Choose a template', 'thim-elementor-kit'),
				'type'    => Controls_Manager::SELECT2,
				'default' => '0',
				'options' => array('0' => esc_html__('None', 'thim-elementor-kit')) + \Thim_EL_Kit\Functions::instance()->get_pages_loop_item('product'),
				'condition'   => array(
					'build_loop_item' => 'yes',
				),
			)
		);
		$this->add_control(
			'posts_per_page',
			array(
				'label'   => esc_html__('Products Per Page', 'thim-elementor-kit'),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 1,
				'max'     => 20,
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => esc_html__('Columns', 'thim-elementor-kit'),
				'type'           => Controls_Manager::NUMBER,
				'default'        => 4,
				'tablet_default' => 3,
				'mobile_default' => 2,
				'min'            => 1,
				'max'            => 6,
				'selectors'      => array(
					'{{WRAPPER}}' => '--grid-template-columns-related-products: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => esc_html__('Order By', 'thim-elementor-kit'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'       => esc_html__('Date', 'thim-elementor-kit'),
					'title'      => esc_html__('Title', 'thim-elementor-kit'),
					'price'      => esc_html__('Price', 'thim-elementor-kit'),
					'popularity' => esc_html__('Popularity', 'thim-elementor-kit'),
					'rating'     => esc_html__('Rating', 'thim-elementor-kit'),
					'rand'       => esc_html__('Random', 'thim-elementor-kit'),
					'menu_order' => esc_html__('Menu Order', 'thim-elementor-kit'),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => esc_html__('Order', 'thim-elementor-kit'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => array(
					'asc'  => esc_html__('ASC', 'thim-elementor-kit'),
					'desc' => esc_html__('DESC', 'thim-elementor-kit'),
				),
			)
		);

		$this->end_controls_section();
		$this->_register_style_layout();
		$this->register_heading_controls();
		$this->_register_settings_slider(
			array(
				'style' => 'slider',
			)
		);

		$this->_register_setting_slider_dot_style(
			array(
				'style'                   => 'slider',
				'slider_show_pagination!' => 'none',
			)
		);

		$this->_register_setting_slider_nav_style(
			array(
				'style'             => 'slider',
				'slider_show_arrow' => 'yes',
			)
		);
		parent::register_controls();
	}
	protected function _register_style_layout(){
		$this->start_controls_section(
			'section_design_layout',
			array(
				'label'     => esc_html__('Layout', 'thim-elementor-kit'),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'build_loop_item' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'column_gap',
			array(
				'label'     => esc_html__('Columns Gap', 'thim-elementor-kit'),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 30,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-ekits-related-product-column-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'row_gap',
			array(
				'label'     => esc_html__('Rows Gap', 'thim-elementor-kit'),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 35,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-ekits-related-product-row-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();
	}
	protected function register_heading_controls(){
		$this->start_controls_section(
			'section_style_heading_product',
			array(
				'label' => esc_html__('Heading', 'thim-elementor-kit'),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'show_heading',
			array(
				'label'        => esc_html__('Heading', 'thim-elementor-kit'),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => esc_html__('Hide', 'thim-elementor-kit'),
				'label_on'     => esc_html__('Show', 'thim-elementor-kit'),
				'default'      => 'yes',
				'return_value' => 'yes',
				'prefix_class' => 'thim-ekit-single-product__related--show-heading-', 
			)
		);

		$this->add_control(
			'heading_color',
			array(
				'label'     => esc_html__('Color', 'thim-elementor-kit'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .title-related-products' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'show_heading!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'heading_typography',
				'selector'  => '{{WRAPPER}} .title-related-products',
				'condition' => array(
					'show_heading!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'heading_text_align',
			array(
				'label'     => esc_html__('Text Align', 'thim-elementor-kit'),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__('Left', 'thim-elementor-kit'),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__('Center', 'thim-elementor-kit'),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__('Right', 'thim-elementor-kit'),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .title-related-products' => 'text-align: {{VALUE}}',
				),
				'condition' => array(
					'show_heading!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'heading_spacing',
			array(
				'label'      => esc_html__('Spacing', 'thim-elementor-kit'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array('px', 'em'),
				'selectors'  => array(
					'{{WRAPPER}} .title-related-products' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'show_heading!' => '',
				),
			)
		);

		$this->end_controls_section();
	}

	public function render(){
		do_action('thim-ekit/modules/single-product/before-preview-query');

		$product = wc_get_product(false);

		if (!$product) {
			return;
		}

		$settings = $this->get_settings_for_display();

		$args = array(
			'posts_per_page' => 4,
			'columns'        => 4,
			'orderby'        => $settings['orderby'] ?? 'date',
			'order'          => $settings['order'] ?? 'desc',
		);

		if (!empty($settings['posts_per_page'])) {
			$args['posts_per_page'] = $settings['posts_per_page'];
		}

		if (!empty($settings['columns'])) {
			$args['columns'] = $settings['columns'];
		}

		// Get visible related products then sort them at random.
		$args['related_products'] = array_filter(array_map('wc_get_product', wc_get_related_products($product->get_id(), $args['posts_per_page'], $product->get_upsell_ids())), 'wc_products_array_filter_visible');

		// Handle orderby.
		$args['related_products'] = wc_products_array_orderby($args['related_products'], $args['orderby'], $args['order']);

		ob_start();
		wc_get_template('single-product/related.php', $args);
		$html = ob_get_clean();
		$class       = 'thim-ekits-product__related';
		$class_inner = 'thim-ekits-product__related__inner product-grid';
		$class_item  = 'thim-ekits-product__related__item';

?>
		<div class="thim-ekit-single-product__related woocommerce">
			<?php
			if ($args['related_products']) {
				$heading = apply_filters('woocommerce_product_related_products_heading', __('Related products', 'woocommerce'));
				if ($heading) :
			?>
					<h2 class="title-related-products"><?php echo esc_html($heading); ?></h2>
				<?php endif;
				if (isset($settings['style']) && $settings['style'] == 'slider') {
					$swiper_class = \Elementor\Plugin::$instance->experiments->is_feature_active('e_swiper_latest') ? 'swiper' : 'swiper-container';
					$class        .= ' thim-ekits-sliders ' . $swiper_class;
					$class_inner  = 'swiper-wrapper';
					$class_item   .= ' swiper-slide';
					$this->render_nav_pagination_slider($settings);
				}
				?>
				<div class="<?php echo esc_attr($class); ?>">
					<div class="<?php echo esc_attr($class_inner); ?>">
						<?php
						foreach ($args['related_products'] as $related_product) :

							$post_object = get_post($related_product->get_id());

							setup_postdata($GLOBALS['post'] = &$post_object); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

						?>
							<div <?php wc_product_class($class_item); ?>>
								<?php
								if (!empty($settings['template_id'])) {
									\Thim_EL_Kit\Utilities\Elementor::instance()->render_loop_item_content($settings['template_id']);
								} else {
									/**
									 * Hook: woocommerce_before_shop_loop_item.
									 *
									 * @hooked woocommerce_template_loop_product_link_open - 10
									 */
									do_action('woocommerce_before_shop_loop_item');
									/**
									 * Hook: woocommerce_before_shop_loop_item_title.
									 *
									 * @hooked woocommerce_show_product_loop_sale_flash - 10
									 * @hooked woocommerce_template_loop_product_thumbnail - 10
									 */
									do_action('woocommerce_before_shop_loop_item_title');

									/**
									 * Hook: woocommerce_shop_loop_item_title.
									 *
									 * @hooked woocommerce_template_loop_product_title - 10
									 */
									do_action('woocommerce_shop_loop_item_title');

									/**
									 * Hook: woocommerce_after_shop_loop_item_title.
									 *
									 * @hooked woocommerce_template_loop_rating - 5
									 * @hooked woocommerce_template_loop_price - 10
									 */
									do_action('woocommerce_after_shop_loop_item_title');
									/**
									 * Hook: woocommerce_after_shop_loop_item.
									 *
									 * @hooked woocommerce_template_loop_product_link_close - 5
									 * @hooked woocommerce_template_loop_add_to_cart - 10
									 */
									do_action('woocommerce_after_shop_loop_item');
								}
								?>
							</div>
						<?php
						endforeach;
						?>
					</div>
				</div>
			<?php
			}
			?>
		</div>

<?php
		do_action('thim-ekit/modules/single-product/after-preview-query');
	}
}
