<?php
/**
 * WooCommerce Product Grid
 *
 * @package AbsoluteAddons
 */

namespace AbsoluteAddons\Widgets;

use AbsoluteAddons\Absp_Widget;
use AbsoluteAddons\Controls\Absp_Control_Styles;
use Elementor\Group_Control_Image_Size;
use Elementor\Controls_Manager;
use WC_Product;
use WP_Query;
use YITH_WCQV_Frontend;
use YITH_WCWL_Shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class Absoluteaddons_Style_Product_Grid extends Absp_Widget {

	protected $wishlist_add_label;

	protected $wishlist_remove_label;

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_name() {
		return 'absolute-product-grid';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Product Grid', 'absolute-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'absp eicon-cart';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [
			'absolute-addons-custom',
			'ico-font',
			'absp-product-grid',
			'absp-pro-product-grid',
		];
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return [
			'absolute-addons-product-grid',
		];
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @return array Widget categories.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_categories() {
		return [ 'absp-widgets' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function get_product_grid_filterby_options() {
		return apply_filters( 'absp/product-grid/filterby-options', [
			'recent-products'       => esc_html__( 'Recent Products', 'absolute-addons' ),
			'featured-products'     => esc_html__( 'Featured Products', 'absolute-addons' ),
			'best-selling-products' => esc_html__( 'Best Selling Products', 'absolute-addons' ),
			'sale-products'         => esc_html__( 'Sale Products', 'absolute-addons' ),
			'top-products'          => esc_html__( 'Top Rated Products', 'absolute-addons' ),
		] );
	}

	protected function get_product_grid_orderby_options() {
		return apply_filters( 'absp/product-grid/orderby-options', [
			'ID'         => esc_html__( 'Product ID', 'absolute-addons' ),
			'title'      => esc_html__( 'Product Title', 'absolute-addons' ),
			'_price'     => esc_html__( 'Price', 'absolute-addons' ),
			'_sku'       => esc_html__( 'SKU', 'absolute-addons' ),
			'date'       => esc_html__( 'Date', 'absolute-addons' ),
			'modified'   => esc_html__( 'Last Modified Date', 'absolute-addons' ),
			'parent'     => esc_html__( 'Parent Id', 'absolute-addons' ),
			'rand'       => esc_html__( 'Random', 'absolute-addons' ),
			'menu_order' => esc_html__( 'Menu Order', 'absolute-addons' ),
		] );
	}

	protected function register_controls() {
		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Product_Grid $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/starts' ), [ &$this ] );

		$this->start_controls_section( 'style_layout_section', [ 'label' => esc_html__( 'Product Grid Style', 'absolute-addons' ) ] );

		$styles = apply_filters( 'absp/widgets/product-grid/styles', [
			'one'       => esc_html__( 'One', 'absolute-addons' ),
			'two-pro'   => esc_html__( 'Two (Pro)', 'absolute-addons' ),
			'three-pro' => esc_html__( 'Three (Pro)', 'absolute-addons' ),
			'four'      => esc_html__( 'Four', 'absolute-addons' ),
			'five-pro'  => esc_html__( 'Five (Pro)', 'absolute-addons' ),
			'six-pro'   => esc_html__( 'Six (Pro)', 'absolute-addons' ),
			'seven-pro' => esc_html__( 'Seven (Pro)', 'absolute-addons' ),
			'eight-pro' => esc_html__( 'Eight (Pro)', 'absolute-addons' ),
			'nine-pro'  => esc_html__( 'Nine (Pro)', 'absolute-addons' ),
			'ten-pro'   => esc_html__( 'Ten (Pro)', 'absolute-addons' ),
			'eleven'    => esc_html__( 'Eleven', 'absolute-addons' ),
		] );

		$this->add_control(
			'absolute_product_grid',
			[
				'label'   => esc_html__( 'Product Grid Style', 'absolute-addons' ),
				'type'    => Absp_Control_Styles::TYPE,
				'options' => $styles,
				'default' => 'one',
			]
		);

		$this->init_pro_alert( [
			'two-pro',
			'three-pro',
			'five-pro',
			'six-pro',
			'seven-pro',
			'eight-pro',
			'nine-pro',
			'ten-pro',
		] );

		if ( ! absp_is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$this->plugin_dependency_alert( [
				'plugins' => [
					[
						'path' => 'woocommerce/woocommerce.php',
						'name' => __( 'WooCommerce', 'absolute-addons' ),
						'slug' => 'woocommerce',
					],
				],
			] );

			$this->end_controls_section();
			return;
		}

		$this->plugin_dependency_alert( [
			[
				'plugins'   => [
					[
						'path' => 'yith-woocommerce-wishlist/init.php',
						'name' => __( 'YITH WooCommerce Wishlist', 'absolute-addons' ),
						'slug' => 'yith-woocommerce-wishlist',
					],
				],
				'condition' => [
					'absolute_product_grid' => 'one',
				],
			],
			[
				'plugins'   => [
					[
						'path' => 'yith-woocommerce-quick-view/init.php',
						'name' => __( 'YITH WooCommerce Quick View', 'absolute-addons' ),
						'slug' => 'yith-woocommerce-quick-view',
					],
					[
						'path' => 'yith-woocommerce-wishlist/init.php',
						'name' => __( 'YITH WooCommerce Wishlist', 'absolute-addons' ),
						'slug' => 'yith-woocommerce-wishlist',
					],
				],
				'condition' => [
					'absolute_product_grid' => 'two',
				],
			],
		] );

		// https://wordpress.org/plugins/woo-variation-swatches/

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_query',
			array(
				'label' => esc_html__( 'Product Query', 'absolute-addons' ),
			)
		);
		$this->add_control(
			'product_grid_product_filter',
			[
				'label'   => esc_html__( 'Filter By', 'absolute-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'recent-products',
				'options' => $this->get_product_grid_filterby_options(),
			]
		);
		$this->add_control(
			'orderby',
			[
				'label'   => esc_html__( 'Order By', 'absolute-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_product_grid_orderby_options(),
				'default' => 'date',
			]
		);
		$this->add_control(
			'order',
			[
				'label'   => esc_html__( 'Order', 'absolute-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'asc'  => 'Ascending',
					'desc' => 'Descending',
				],
				'default' => 'desc',
			]
		);

		$this->add_control(
			'product_grid_products_count',
			[
				'label'   => esc_html__( 'Show Product Items', 'absolute-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 1,
				'max'     => 1000,
				'step'    => 1,
			]
		);

		$this->add_responsive_control(
			'product_grid_column',
			[
				'label'           => esc_html__( 'Product Grid Column', 'absolute-addons' ),
				'type'            => Controls_Manager::SELECT,
				'default'         => '4',
				'options'         => [
					'1' => esc_html__( '1', 'absolute-addons' ),
					'2' => esc_html__( '2', 'absolute-addons' ),
					'3' => esc_html__( '3', 'absolute-addons' ),
					'4' => esc_html__( '4', 'absolute-addons' ),
					'5' => esc_html__( '5', 'absolute-addons' ),
					'6' => esc_html__( '6', 'absolute-addons' ),
				],
				'desktop_default' => 4,
				'tablet_default'  => 2,
				'mobile_default'  => 1,
				'prefix_class'    => 'absp-grid--col-%s',
				'style_transfer'  => true,
				'selectors'       => [
					'(desktop+){{WRAPPER}} .absp-product-grid-item .product-grid-item-wrapper ' => 'grid-template-columns: repeat({{product_grid_column.VALUE}}, 1fr);',
					'(tablet){{WRAPPER}} .absp-product-grid-item .product-grid-item-wrapper '   => 'grid-template-columns: repeat({{product_grid_column_tablet.VALUE}}, 1fr);',
					'(mobile){{WRAPPER}} .absp-product-grid-item .product-grid-item-wrapper '   => 'grid-template-columns: repeat({{product_grid_column_mobile.VALUE}}, 1fr);',

				],
			]
		);

		$this->add_responsive_control(
			'product_grid_column_gap',
			[
				'label'      => esc_html__( 'Product Grid Column Gap', 'absolute-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],

				'selectors'  => [
					'{{WRAPPER}} .absp-product-grid-item .product-grid-item-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$taxonomies = get_taxonomies( [ 'object_type' => [ 'product' ] ], 'objects' );

		foreach ( $taxonomies as $taxonomy => $object ) {
			if ( ! isset( $object->object_type[0] ) ) {
				continue;
			}

			$this->add_control(
				$taxonomy . '_ids',
				[
					'label'       => $object->label,
					'type'        => Controls_Manager::SELECT2,
					'label_block' => true,
					'multiple'    => true,
					'object_type' => $taxonomy,
					'options'     => wp_list_pluck( get_terms( $taxonomy ), 'name', 'term_id' ),
				]
			);
		}

		$this->add_control(
			'product_grid_not_found_msg',
			[
				'label'     => esc_html__( 'Product Not Found Message', 'absolute-addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Sorry, no posts matched your selected criteria.', 'absolute-addons' ),
				'separator' => 'after',
			]
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'        => 'product_grid_image_size',
				'exclude'     => [ 'custom' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'default'     => 'full',
				'label_block' => true,
			]
		);
		$this->add_control(
			'product_grid_img_hover_animation',
			[
				'label'        => esc_html__( 'Image Hover Animation', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'enable'       => esc_html__( 'Yes', 'absolute-addons' ),
				'disable'      => esc_html__( 'No', 'absolute-addons' ),
				'return_value' => 'enable',
				'default'      => 'enable',
			]
		);
		$this->end_controls_section();

		//Product grid product settings controller sections
		$this->start_controls_section(
			'section_product_settings',
			array(
				'label' => esc_html__( 'Product Settings', 'absolute-addons' ),
			)
		);

		$this->add_control(
			'product_grid_show_title',
			[
				'label'        => esc_html__( 'Show Product Title?', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'absolute-addons' ),
				'label_off'    => esc_html__( 'No', 'absolute-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'product_grid_title_length',
			[
				'label'     => esc_html__( 'Product Title Length', 'absolute-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'condition' => [
					'product_grid_show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'product_grid_rating',
			[
				'label'        => esc_html__( 'Show Product Rating?', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'absolute-addons' ),
				'label_off'    => esc_html__( 'No', 'absolute-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			'product_grid_rating_count',
			[
				'label'        => esc_html__( 'Show Product Rating Count?', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'absolute-addons' ),
				'label_off'    => esc_html__( 'No', 'absolute-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'product_grid_rating' => 'yes',
				],
			]
		);
		$this->add_control(
			'product_grid_price',
			[
				'label'        => esc_html__( 'Show Product Price?', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			'product_grid_category',
			[
				'label'        => esc_html__( 'Show Product Category?', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
				'separator'    => 'after',
			]
		);
		$this->add_control(
			'product_grid_excerpt',
			[
				'label'        => esc_html__( 'Show Product Short Description?', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);
		$this->add_control(
			'product_grid_excerpt_length',
			[
				'label'     => esc_html__( 'Excerpt Words', 'absolute-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '10',
				'condition' => [
					'product_grid_excerpt' => 'yes',
				],
			]
		);

		$this->add_control(
			'product_grid_excerpt_indicator',
			[
				'label'       => esc_html__( 'Excerpt Indicator', 'absolute-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false,
				'default'     => '...',
				'separator'   => 'after',
				'condition'   => [
					'product_grid_excerpt' => 'yes',
				],
			]
		);

		if ( absp_is_plugin_active( 'yith-woocommerce-wishlist/init.php' ) ) {
			$this->add_control(
				'enable_wishlist',
				[
					'label'        => esc_html__( 'Enable Wishlist', 'absolute-addons' ),
					'type'         => Controls_Manager::SWITCHER,
					'return_value' => 'yes',
					'default'      => 'yes',
				]
			);

			$this->add_control(
				'wishlist_add_label',
				[
					'label'     => esc_html__( 'Add To Wishlist Label', 'absolute-addons' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => __( 'Add to wishlist', 'absolute-addons' ),
					'condition' => [
						'enable_wishlist' => 'yes',
					],
				]
			);
			$this->add_control(
				'wishlist_remove_label',
				[
					'label'     => esc_html__( 'Remove From Wishlist Label', 'absolute-addons' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => __( 'Remove from list', 'absolute-addons' ),
					'separator' => 'after',
					'condition' => [
						'enable_wishlist' => 'yes',
					],
				]
			);
		}

		if ( absp_is_plugin_active( 'yith-woocommerce-quick-view/init.php' ) ) {
			$this->add_control(
				'enable_quick_view',
				[
					'label'        => esc_html__( 'Show Quick view?', 'absolute-addons' ),
					'type'         => Controls_Manager::SWITCHER,
					'return_value' => 'yes',
					'default'      => 'yes',
					'conditions'   => [
						'relation' => 'or',
						'terms'    => [
							[
								'name'     => 'absolute_product_grid',
								'operator' => '==',
								'value'    => 'two',
							],
							[
								'name'     => 'absolute_product_grid',
								'operator' => '==',
								'value'    => 'six',
							],
						],
					],
				]
			);
			$this->add_control(
				'quick_view_label',
				[
					'label'      => esc_html__( 'Quick View Label', 'absolute-addons' ),
					'type'       => Controls_Manager::TEXT,
					'default'    => YITH_WCQV_Frontend::get_instance()->get_button_label(),
					'separator'  => 'after',
					'conditions' => [
						'enable_quick_view' => 'yes',
						'relation'          => 'or',
						'terms'             => [
							[
								'name'     => 'absolute_product_grid',
								'operator' => '==',
								'value'    => 'two',
							],
							[
								'name'     => 'absolute_product_grid',
								'operator' => '==',
								'value'    => 'six',
							],
						],
					],
					'condition'  => [
						'enable_quick_view' => 'yes',
					],
				]
			);
		}

		$this->add_control(
			'product_grid_author',
			[
				'label'        => esc_html__( 'Show Product Author?', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
				'conditions'   => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_product_grid',
							'operator' => '==',
							'value'    => 'three',
						],
						[
							'name'     => 'absolute_product_grid',
							'operator' => '==',
							'value'    => 'four',
						],
					],
				],
			]
		);
		$this->add_control(
			'product_grid_label',
			[
				'label'        => esc_html__( 'Show Product Label?', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->end_controls_section();

		//Product settings label controller section
		$this->start_controls_section(
			'section_product_grid_label',
			array(
				'label'     => esc_html__( 'Product Label Settings', 'absolute-addons' ),
				'condition' => [
					'product_grid_label' => 'yes',
				],
			)
		);
		$this->add_control(
			'product_grid_sale_text',
			[
				'label' => esc_html__( 'Sale Text', 'absolute-addons' ),
				'type'  => Controls_Manager::TEXT,
			]
		);
		$this->add_control(
			'product_grid_stockout_text',
			[
				'label' => esc_html__( 'Stock Out Text', 'absolute-addons' ),
				'type'  => Controls_Manager::TEXT,
			]
		);
		$this->add_control(
			'product_sale_badge_alignment',
			[
				'label'   => esc_html__( 'Alignment', 'absolute-addons' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left'  => [
						'title' => esc_html__( 'Left', 'absolute-addons' ),
						'icon'  => 'fa fa-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'absolute-addons' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default' => 'left',
			]
		);
		$this->end_controls_section();

		$this->render_controller( 'style-controller-product-grid-item1-settings' );
		$this->render_controller( 'style-controller-product-grid-item-settings' );
		$this->render_controller( 'style-controller-product-grid-item-title' );
		$this->render_controller( 'style-controller-product-grid-item-category' );
		$this->render_controller( 'style-controller-product-grid-item-label' );
		$this->render_controller( 'style-controller-product-grid-item-price' );
		$this->render_controller( 'style-controller-product-grid-item-excerpt' );
		$this->render_controller( 'style-controller-product-grid-item-button' );
		$this->render_controller( 'style-controller-product-grid-item-quick-view-button' );
		$this->render_controller( 'style-controller-product-grid-item-wishlist-button' );
		$this->render_controller( 'style-controller-product-grid-item-rating' );
		$this->render_controller( 'style-controller-product-grid-item-popup' );
		$this->render_controller( 'style-controller-product-grid-item-not-found-msg' );

		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Product_Grid $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/ends' ), [ &$this ] );
	}

	protected function get_products() {
		$settings = $this->get_settings_for_display();

		$order_by = $settings['orderby'];
		$filter   = $settings['product_grid_product_filter'];
		$args     = [
			'post_type'      => 'product',
			'posts_per_page' => $settings['product_grid_products_count'] ? absint( $settings['product_grid_products_count'] ) : 4,
			'order'          => $settings['order'],
			'tax_query'      => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				'relation' => 'AND',
				[
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => [ 'exclude-from-search', 'exclude-from-catalog' ],
					'operator' => 'NOT IN',
				],
			],
		];

		if ( '_price' === $order_by || '_sku' === $order_by ) {
			$args['orderby']  = 'meta_value_num';
			$args['meta_key'] = $order_by;
		} else {
			$args['orderby'] = $order_by;
		}

		switch ( $filter ) {
			case 'featured-products':
				$count                       = isset( $args['tax_query'] ) ? count( $args['tax_query'] ) : 0; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				$args['tax_query'][ $count ] = [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
				];
				break;
			case 'best-selling-products':
				$args['meta_key'] = 'total_sales';
				$args['orderby']  = 'meta_value_num';
				$args['order']    = 'DESC';
				break;
			case 'top-products':
				$args['meta_key'] = '_wc_average_rating';
				$args['orderby']  = 'meta_value_num';
				$args['order']    = 'DESC';
				break;
			case 'sale-products':
				$count                        = isset( $args['meta_query'] ) ? count( $args['meta_query'] ) : 0;
				$args['meta_query'][ $count ] = [
					'relation' => 'OR',
					[
						'key'     => '_sale_price',
						'value'   => 0,
						'compare' => '>',
						'type'    => 'numeric',
					],
					[
						'key'     => '_min_variation_sale_price',
						'value'   => 0,
						'compare' => '>',
						'type'    => 'numeric',
					],
				];
				break;
			default:
				break;
		}

		if ( get_option( 'woocommerce_hide_out_of_stock_items' ) == 'yes' ) {
			$args['meta_query']   = [ 'relation' => 'AND' ]; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			$args['meta_query'][] = [
				'key'   => '_stock_status',
				'value' => 'instock',
			];
		}

		$taxonomies = get_taxonomies( [ 'object_type' => [ 'product' ] ], 'objects' );
		foreach ( $taxonomies as $object ) {
			$setting_key = $object->name . '_ids';
			if ( ! empty( $settings[ $setting_key ] ) ) {
				$args['tax_query'][] = [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					'taxonomy' => $object->name,
					'field'    => 'term_id',
					'terms'    => $settings[ $setting_key ],
				];
			}
		}

		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			return $query;
		}

		return false;
	}

	protected function get_render_args( $product, $settings ) {
		$grid_image_class = 'product-grid-item-img';
		if ( 'enable' === $settings['product_grid_img_hover_animation'] ) {
			$grid_image_class .= ' product-grid-img-hover-animation';
		}
		return [
			'product'                   => $product,
			'sale_badge_align'          => isset( $settings['product_sale_badge_alignment'] ) ? $settings['product_sale_badge_alignment'] : '',
			'sale_text'                 => ! empty( $settings['product_grid_sale_text'] ) ? $settings['product_grid_sale_text'] : __( 'SALE!', 'absolute-addons' ),
			'grid_image_class'          => $grid_image_class,
			'stockout_text'             => ! empty( $settings['product_grid_stockout_text'] ) ? $settings['product_grid_stockout_text'] : __( 'STOCK OUT', 'absolute-addons' ),
			'show_product_rating'       => isset( $settings['product_grid_rating'] ) && 'yes' === $settings['product_grid_rating'],
			'show_product_author'       => isset( $settings['product_grid_label'] ) && 'yes' === $settings['product_grid_label'],
			'show_product_rating_count' => isset( $settings['product_grid_rating_count'] ) && 'yes' === $settings['product_grid_rating_count'],
			'show_product_quick_view'   => isset( $settings['enable_quick_view'] ) && 'yes' === $settings['enable_quick_view'],
			'show_product_price'        => isset( $settings['product_grid_price'] ) && 'yes' === $settings['product_grid_price'],
			'show_product_category'     => isset( $settings['product_grid_category'] ) && 'yes' === $settings['product_grid_category'],
			'show_product_excerpt'      => isset( $settings['product_grid_excerpt'] ) && ( 'yes' === $settings['product_grid_excerpt'] && has_excerpt() ),
			'show_product_label'        => isset( $settings['product_grid_author'] ) && 'yes' === $settings['product_grid_author'],
		];
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( ! absp_is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return;
		}

		if ( 'three' === $settings['absolute_product_grid'] || 'ten' === $settings['absolute_product_grid'] ) {
			add_filter( 'woocommerce_loop_add_to_cart_link', [ $this, 'quantity_inputs_for_woocommerce_loop_add_to_cart_link' ], 10, 2 );
		}
		?>
		<div class="absp-wrapper absp-widget">
			<div class="absp-wrapper-inside">
				<div class="absp-wrapper-content">
					<!-- absp-product-grid-item -->
					<div class="absp-product-grid-item element-<?php echo esc_attr( $settings['absolute_product_grid'] ); ?>">
						<div class="product-grid-item-wrapper">
							<?php
							$products = $this->get_products();
							if ( $products ) {
								while ( $products->have_posts() ) {
									global $post, $product;
									$products->the_post();
									$GLOBALS['post']    = $products->post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
									$product            = wc_get_product( get_the_ID() );
									$GLOBALS['product'] = $product;
									if ( $product ) {
										$this->render_template( $settings['absolute_product_grid'], $this->get_render_args( $product, $settings ) );
									}
								}
								wp_reset_postdata();
							} else {
								?>
								<p class="no-products"><?php absp_render_title( $settings['product_grid_not_found_msg'] ); ?></p>
							<?php } ?>
						</div>
					</div>
					<!-- absp-product-grid-item -->
				</div>
			</div>
		</div>
		<?php

		if ( 'three' === $settings['absolute_product_grid'] || 'ten' === $settings['absolute_product_grid'] ) {
			remove_filter( 'woocommerce_loop_add_to_cart_link', [ $this, 'quantity_inputs_for_woocommerce_loop_add_to_cart_link' ], 10 );
		}
	}

	/**
	 * Override loop template and show quantities next to add to cart buttons
	 */
	function quantity_inputs_for_woocommerce_loop_add_to_cart_link( $html, $product ) {
		if ( $product && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() && ! $product->is_sold_individually() ) {
			$html = '<form action="' . esc_url( $product->add_to_cart_url() ) . '" class="cart" method="post" enctype="multipart/form-data">';
			$html .= woocommerce_quantity_input( array(), $product, false );
			$html .= '<button type="submit" class="button alt">' . esc_html( $product->add_to_cart_text() ) . '</button>';
			$html .= '</form>';
		}
		return $html;
	}

	protected function render_quick_view_button( $product, $settings ) {
		if ( isset( $settings['enable_quick_view'] ) && 'yes' === $settings['enable_quick_view'] ) {
			if ( absp_is_plugin_active( 'yith-woocommerce-quick-view/init.php' ) ) {
				if ( ! empty( $settings['quick_view_label'] ) ) {
					$label = esc_html( $settings['quick_view_label'] );
				} else {
					$label = YITH_WCQV_Frontend::get_instance()->get_button_label();
				}
				if ( 'six' === $settings['absolute_product_grid'] ) {
					$button = '<a href="#" class="product-grid-item-quick-view-btn yith-wcqv-button" data-product_id="' . esc_attr( get_the_ID() ) . '"><i aria-hidden="true" class="fa fa-eye"></i><span class="product-grid-item-btn-tooltip">' . $label . '</span></a>';
					echo $button; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				} else {
					$button = '<a href="#" class="product-grid-item-btn  yith-wcqv-button" data-product_id="' . esc_attr( get_the_ID() ) . '">' . $label . '</a>';
					echo $button; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			}
		}
	}

	public function yith_wcwl_button_label( $label ) {
		if ( $this->wishlist_add_label ) {
			return $this->wishlist_add_label;
		}

		return $label;
	}

	public function yith_wcwl_remove_from_wishlist_label( $label ) {
		if ( $this->wishlist_remove_label ) {
			return $this->wishlist_remove_label;
		}

		return $label;
	}

	protected function render_wishlist( $product, $settings, $before = '', $after = '' ) {
		if ( isset( $settings['enable_wishlist'] ) && 'yes' === $settings['enable_wishlist'] ) {
			if ( absp_is_plugin_active( 'yith-woocommerce-wishlist/init.php' ) ) {
				// product-grid-item-wishlist-btn

				$this->wishlist_add_label    = ! empty( $settings['wishlist_add_label'] ) ? $settings['wishlist_add_label'] : false;
				$this->wishlist_remove_label = ! empty( $settings['wishlist_remove_label'] ) ? $settings['wishlist_remove_label'] : false;

				if ( $this->wishlist_add_label ) {
					add_filter( 'yith_wcwl_button_label', [ $this, 'yith_wcwl_button_label' ] );
				}

				if ( $this->wishlist_remove_label ) {
					add_filter( 'yith_wcwl_remove_from_wishlist_label', [ $this, 'yith_wcwl_remove_from_wishlist_label' ] );
				}

				wp_kses_post_e( $before );

				echo YITH_WCWL_Shortcode::add_to_wishlist( [ // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					'is_single'  => false,
					'product_id' => $product->get_id(),
				] );

				wp_kses_post_e( $after );

				remove_filter( 'yith_wcwl_button_label', [ $this, 'yith_wcwl_button_label' ] );
				remove_filter( 'yith_wcwl_remove_from_wishlist_label', [ $this, 'yith_wcwl_remove_from_wishlist_label' ] );
			}
		}
	}

	protected function render_product_image( $product, $settings ) {
		echo $product->get_image( $settings['product_grid_image_size_size'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * @param bool $show_product_label
	 * @param bool $sale_badge_align
	 * @param bool $stockout_text
	 * @param bool $sale_text
	 * @param WC_Product $product
	 */
	protected function render_product_labels( $show_product_label, $sale_badge_align, $stockout_text, $sale_text, $product ) {
		if ( $show_product_label ) {
			if ( ! $product->managing_stock() && ! $product->is_in_stock() ) {
				echo '<span class="product-grid-item-label outofstock ' . esc_attr( $sale_badge_align ) . '">' . esc_html( $stockout_text ) . '</span>';
			}
			if ( $product->is_on_sale() ) {
				echo '<span class="product-grid-item-label ' . esc_attr( $sale_badge_align ) . '">' . esc_html( $sale_text ) . '</span>';
			}
		}
	}

	/**
	 * @param bool $show_product_category
	 * @param WC_Product $product
	 */
	protected function render_product_categories( $show_product_category, $product ) {
		if ( $show_product_category ) {
			$terms = get_the_terms( $product->get_id(), 'product_cat' );
			if ( ! empty( $terms ) ) {
				echo '<ul class="product-grid-item-category">';
				foreach ( $terms as $_term ) {
					$term_link = get_term_link( $_term );
					if ( ! $term_link || is_wp_error( $term_link ) ) {
						continue;
					}
					echo '<li><a href="' . esc_url( $term_link ) . '">' . esc_html( $_term->name ) . '</a></li>';
				}
				echo '</ul>';
			}
		}
	}

	/**
	 * @param $settings
	 * @param $product
	 */
	protected function render_product_title( $settings, $product ) {
		if ( $settings['product_grid_show_title'] ) {
			echo '<h2 class="product-grid-item-title">';
			echo '<a href="' . esc_url( $product->get_permalink() ) . '">';
			if ( empty( $settings['product_grid_title_length'] ) ) {
				echo $product->get_title(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				echo esc_html( wp_trim_words( $product->get_title(), $settings['product_grid_title_length'], '' ) );
			}
			echo '</a></h2>';
		}
	}

	protected function render_product_excerpt( $show_product_excerpt, $product, $settings ) {
		if ( $show_product_excerpt ) {
			?>
			<div class="product-grid-excerpt">
				<p><?php absp_render_excerpt( $product->get_short_description(), $settings['product_grid_excerpt_length'], $settings['product_grid_excerpt_indicator'] ); ?></p>
			</div>
			<?php
		}
	}

	protected function render_product_price( $show_product_price, $product ) {
		if ( $show_product_price ) {
			echo '<div class="product-grid-item-product-price">' . $product->get_price_html() . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	protected function render_product_rating( $show_product_rating, $show_product_rating_count, $product ) {
		if ( $show_product_rating ) {
			$rating_count = $product->get_rating_count();
			$average      = $product->get_average_rating();
			if ( $rating_count ) {
				?>
				<ul class="product-grid-item-product-ratting">
					<li><?php echo wc_get_rating_html( $average, $rating_count ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></li>
					<?php if ( $show_product_rating_count ) { ?>
						<li class="product-grid-item-product-ratting-count"><?php echo esc_html( $rating_count ); ?></li>
					<?php } ?>
				</ul>
			<?php } else { ?>
				<ul class="product-grid-item-product-ratting">
					<li class="star-rating"></li>
					<?php if ( $show_product_rating_count ) { ?>
						<li class="product-grid-item-product-ratting-count"><?php echo esc_html( $rating_count ); ?></li>
					<?php } ?>
				</ul>
			<?php }
		}
	}

	protected function render_product_author( $show_product_author ) {
		if ( $show_product_author ) {
			?>
			<ul class="product-grid-item-product-author">
				<li class="product-grid-item-product-author-by"><?php echo esc_html( 'By' ); ?></li>
				<li class="product-grid-item-product-author-name"><?php the_author(); ?></li>
			</ul>
		<?php }
	}
}
