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
 * WooCommerce Product Rating | Review
 *
 * @see https://docs.woocommerce.com/document/managing-products/
 * @author quomodosoft.com
 */
class Rating extends \Shop_Ready\extension\elewidgets\Widget_Base {

	public $wrapper_class = false;

	protected function register_controls() {
		// Notice
		$this->start_controls_section(
			'notice_content_section',
			array(
				'label' => esc_html__( 'Notice', 'shopready-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'woo_ready_usage_direction_notice',
			array(
				'label'           => esc_html__( 'Important Note', 'shopready-elementor-addon' ),
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Use This Widget in WooCommerce Product Details page  Template.', 'shopready-elementor-addon' ),
				'content_classes' => 'woo-ready-product-page-notice',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'editor_content_section',
			array(
				'label' => esc_html__( 'Editor Refresh', 'shopready-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_product_content',
			array(
				'label'        => esc_html__( 'Content Refresh?', 'shopready-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'shopready-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'shopready-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'wready_product_id',
			array(
				'label'    => esc_html__( 'Demo Product', 'shopready-elementor-addon' ),
				'type'     => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'default'  => shop_ready_get_single_product_key(),
				'options'  => shop_ready_get_latest_products_id( 50 ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'layouts_cart_content_section',
			array(
				'label' => esc_html__( 'Layout', 'shopready-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => esc_html__( 'Style', 'shopready-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'rating',
				'options' => array(
					'rating' => esc_html__( 'Default', 'shopready-elementor-addon' ),
					// 'review-rating'   => esc_html__('Review Rating','shopready-elementor-addon'),

				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'content_rating_section',
			array(
				'label' => esc_html__( 'Settings', 'shopready-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'review_form_section',
			array(
				'label'       => esc_html__( 'Review Form Id', 'shopready-elementor-addon' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'review',
				'placeholder' => esc_html__( 'review', 'shopready-elementor-addon' ),

			)
		);

		$this->end_controls_section();

		/**
		 * Layouts Total Table
		 */
		$this->box_layout(
			array(
				'title'            => esc_html__( 'Container Wrapper', 'shopready-elementor-addon' ),
				'slug'             => 'wready_wc__product_rating_wrapper',
				'element_name'     => '__wrapper',
				'selector'         => '{{WRAPPER}} .woocommerce-product-rating',
				'disable_controls' => array(
					'position',
					'box-size',
				),
			)
		);
		/* Layouts End */

		$this->text_minimum_css(
			array(
				'title'            => esc_html__( 'Rating', 'shopready-elementor-addon' ),
				'slug'             => 'wready_wc_rating_s',
				'element_name'     => 'wrating_product_star',
				'selector'         => '{{WRAPPER}} .woocommerce-product-rating .star-rating::before',
				'hover_selector'   => false,
				'disable_controls' => array(
					'display',
					'bg',
					'border',
					'box-shadow',
				),
			)
		);

		$this->text_css(
			array(
				'title'            => esc_html__( 'Active Rating', 'shopready-elementor-addon' ),
				'slug'             => 'wready_wc_rating_inactives',
				'element_name'     => 'wrag_product_star',
				'selector'         => '{{WRAPPER}} .woocommerce-product-rating .star-rating span::before',
				'hover_selector'   => false,
				'disable_controls' => array(
					'display',
					'bg',
					'border',
					'box-shadow',
					'dimensions',
					'text_shadow',
				),
			)
		);

		$this->text_minimum_css(
			array(
				'title'            => esc_html__( 'Count Text', 'shopready-elementor-addon' ),
				'slug'             => 'wready_wc_rating_icount',
				'element_name'     => 'wreatag_product_star_count',
				'selector'         => '{{WRAPPER}} .woocommerce-review-link',
				'hover_selector'   => false,
				'disable_controls' => array(
					'display',
				),
			)
		);
	}

	/**
	 * Override By elementor render method
	 *
	 * @return void
	 */
	protected function html() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			'wrapper_style',
			array(
				'class' => array( 'woo-ready-product-rating-layout', $settings['style'] ),
			)
		);

		if ( shop_ready_is_elementor_mode() ) {
			$temp_id = WC()->session->get( 'sr_single_product_id' );
			if ( $settings['show_product_content'] == 'yes' && is_numeric( $settings['wready_product_id'] ) ) {
				$temp_id = $settings['wready_product_id'];
			}
			if ( is_numeric( $temp_id ) ) {
				setup_postdata( $temp_id );
			} else {
				setup_postdata( shop_ready_get_single_product_key() );
			}
		}

		echo wp_kses_post( sprintf( '<div %s>', $this->get_render_attribute_string( 'wrapper_style' ) ) );

		if ( file_exists( dirname( __FILE__ ) . '/template-parts/rating/' . $settings['style'] . '.php' ) ) {

			shop_ready_widget_template_part(
				'product/template-parts/rating/' . $settings['style'] . '.php',
				array(
					'settings' => $settings,
				)
			);
		} else {

			shop_ready_widget_template_part(
				'product/template-parts/rating/rating.php',
				array(
					'settings' => $settings,
				)
			);
		}

		echo wp_kses_post( '</div>' );
	}
}
