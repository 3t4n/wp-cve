<?php

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Thim_EL_Kit\GroupControlTrait;
use Thim_EL_Kit\Elementor\Controls\Controls_Manager as Thim_Control_Manager;

class Thim_Ekit_Widget_List_Product extends Thim_Ekit_Products_Base {
	use GroupControlTrait;

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

	}

	public function get_name() {
		return 'thim-ekits-list-product';
	}

	public function get_title() {
		return esc_html__( 'List Product', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-gallery-grid';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY );
	}

	public function get_keywords() {
		return [
			'thim',
			'product',
			'list product',
			'products',
		];
	}

	public function get_help_url() {
		return '';
	}

	protected function register_controls() {
		$this->register_product_setting();

		$this->register_style_layout();

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

	}

	protected function register_product_setting() {
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Options', 'thim-elementor-kit' ),
			)
		);
		$this->add_control(
			'style',
			array(
				'label'   => esc_html__( 'Skin', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => esc_html__( 'Default', 'thim-elementor-kit' ),
					'slider'  => esc_html__( 'Slider', 'thim-elementor-kit' ),
				),
			)
		);

		$this->add_control(
			'template_id',
			array(
				'label'   => esc_html__( 'Choose a template', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT2,
				'default' => '0',
				'options' => array( '0' => esc_html__( 'None', 'thim-elementor-kit' ) ) + \Thim_EL_Kit\Functions::instance()->get_pages_loop_item( 'product' ),
			)
		);

		$this->add_control(
			'cat_slug',
			array(
				'label'    => esc_html__( 'Select Category', 'thim-elementor-kit' ),
				'type'     => Thim_Control_Manager::SELECT2,
				'multiple' => true,
				'sortable' => true,
				'options'  => \Thim_EL_Kit\Elementor::get_cat_taxonomy( 'product_cat', false, false ),
			)
		);

		$this->add_control(
			'order_by',
			array(
				'label'   => esc_html__( 'Order by', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'recent' => esc_html__( 'Date', 'thim-elementor-kit' ),
					'title'  => esc_html__( 'Title', 'thim-elementor-kit' ),
					'random' => esc_html__( 'Random', 'thim-elementor-kit' ),
				),
				'default' => 'recent',
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => esc_html__( 'Order by', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'asc'  => esc_html__( 'ASC', 'thim-elementor-kit' ),
					'desc' => esc_html__( 'DESC', 'thim-elementor-kit' ),
				),
				'default' => 'asc',
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'   => esc_html__( 'Number Post', 'thim-elementor-kit' ),
				'default' => '4',
				'type'    => Controls_Manager::NUMBER,
			)
		);

		$this->end_controls_section();
	}


	protected function register_style_layout() {
		$this->start_controls_section(
			'section_design_layout',
			array(
				'label'     => esc_html__( 'Layout', 'thim-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'style' => 'default',
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => esc_html__( 'Columns', 'thim-elementor-kit' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'selectors'      => array(
					'{{WRAPPER}}' => '--thim-ekits-product-columns: repeat({{VALUE}}, 1fr)',
				),
			)
		);
		$this->add_responsive_control(
			'column_gap',
			array(
				'label'     => esc_html__( 'Columns Gap', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 5,
					],
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-ekits-product-column-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'row_gap',
			array(
				'label'     => esc_html__( 'Rows Gap', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-ekits-product-row-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->get_settings_for_display();
		wp_enqueue_style( 'woocommerce-general' );
		$query_args = array(
			'post_status'         => 'publish',
			'post_type'           => 'product',
			'posts_per_page'      => $settings['posts_per_page'],
			'order'               => ( 'asc' == $settings['order'] ) ? 'asc' : 'desc',
			'ignore_sticky_posts' => true,
		);

		switch ( $settings['order_by'] ) {
			case 'recent':
				$query_args['order_by'] = 'post_date';
				break;
			case 'title':
				$query_args['order_by'] = 'post_title';
				break;
			default: // random
				$query_args['order_by'] = 'rand';
		}
		if ( $settings['cat_slug'] ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $settings['cat_slug'],
				),
			);
		}

		$the_query   = new \WP_Query( $query_args );
		$class       = 'thim-ekits-product woocommerce';
		$class_inner = 'thim-ekits-product__inner';
		$class_item  = 'thim-ekits-product__item';

		if ( $the_query->have_posts() ) {
			if ( isset( $settings['style'] ) && $settings['style'] == 'slider' ) {
				$swiper_class = \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';
				$class        .= ' thim-ekits-sliders ' . $swiper_class;
				$class_inner  = 'swiper-wrapper';
				$class_item   .= ' swiper-slide';

				$this->render_nav_pagination_slider( $settings );
			}
			?>
			<div class="<?php echo esc_attr( $class ); ?>">
				<div class="<?php echo esc_attr( $class_inner ); ?>">
					<?php
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						?>
						<div <?php wc_product_class( $class_item ); ?>>
							<?php
							if ( ! empty( $settings['template_id'] ) ) {
								\Thim_EL_Kit\Utilities\Elementor::instance()->render_loop_item_content( $settings['template_id'] );
							} else {
								/**
								 * Hook: woocommerce_before_shop_loop_item.
								 *
								 * @hooked woocommerce_template_loop_product_link_open - 10
								 */
								do_action( 'woocommerce_before_shop_loop_item' );
								/**
								 * Hook: woocommerce_before_shop_loop_item_title.
								 *
								 * @hooked woocommerce_show_product_loop_sale_flash - 10
								 * @hooked woocommerce_template_loop_product_thumbnail - 10
								 */
								do_action( 'woocommerce_before_shop_loop_item_title' );

								/**
								 * Hook: woocommerce_shop_loop_item_title.
								 *
								 * @hooked woocommerce_template_loop_product_title - 10
								 */
								do_action( 'woocommerce_shop_loop_item_title' );

								/**
								 * Hook: woocommerce_after_shop_loop_item_title.
								 *
								 * @hooked woocommerce_template_loop_rating - 5
								 * @hooked woocommerce_template_loop_price - 10
								 */
								do_action( 'woocommerce_after_shop_loop_item_title' );
								/**
								 * Hook: woocommerce_after_shop_loop_item.
								 *
								 * @hooked woocommerce_template_loop_product_link_close - 5
								 * @hooked woocommerce_template_loop_add_to_cart - 10
								 */
								do_action( 'woocommerce_after_shop_loop_item' );
							}
							?>
						</div>
						<?php
					}
					?>
				</div>
			</div>
			<?php
		} else {
			echo '<div class="message-info">' . __( 'No data were found matching your selection, you need to create Post or select Category of Widget.', 'thim-elementor-kit' ) . '</div>';
		}

		wp_reset_postdata();
	}

}
