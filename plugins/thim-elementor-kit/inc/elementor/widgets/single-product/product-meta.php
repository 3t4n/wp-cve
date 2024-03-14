<?php
namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

class Thim_Ekit_Widget_Product_Meta extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-product-meta';
	}

	public function get_title() {
		return esc_html__( 'Product Meta', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-product-meta';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY_SINGLE_PRODUCT );
	}

	public function get_help_url() {
		return '';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Heading & Captions', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'category_heading',
			array(
				'label' => esc_html__( 'Category', 'thim-elementor-kit' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'category_singular',
			array(
				'label'       => esc_html__( 'Singular', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Category', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'category_plural',
			array(
				'label'       => esc_html__( 'Plural', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Categories', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'tag_heading',
			array(
				'label'     => esc_html__( 'Tag', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'tags_singular',
			array(
				'label'       => esc_html__( 'Singular', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Tag', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'tags_plural',
			array(
				'label'       => esc_html__( 'Plural', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Tags', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'sku_heading',
			array(
				'label'     => esc_html__( 'SKU', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'sku_caption',
			array(
				'label'       => esc_html__( 'Label', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'SKU', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'sku_missing_caption',
			array(
				'label'       => esc_html__( 'Missing', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'N/A', 'thim-elementor-kit' ),
			)
		);

		$this->end_controls_section();

		$this->register_style_controls();
	}

	protected function register_style_controls() {
		$this->start_controls_section(
			'section_style_content',
			array(
				'label' => esc_html__( 'Style', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'style_label',
			array(
				'label'     => esc_html__( 'Label', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-product__meta__label' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .thim-ekit-single-product__meta__label',
			)
		);

		$this->add_control(
			'style_content',
			array(
				'label'     => esc_html__( 'Content', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'content_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-product__meta__content, {{WRAPPER}} .thim-ekit-single-product__meta__content > *' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .thim-ekit-single-product__meta__content, {{WRAPPER}} .thim-ekit-single-product__meta__content > *',
			)
		);

		$this->end_controls_section();
	}

	public function render() {
		do_action( 'thim-ekit/modules/single-product/before-preview-query' );

		$product = wc_get_product( false );

		if ( ! $product ) {
			return;
		}

		$settings = $this->get_settings_for_display();

		$sku         = $product->get_sku();
		$sku_caption = ! empty( $settings['sku_caption'] ) ? $settings['sku_caption'] : esc_html__( 'SKU', 'thim-elementor-kit' );
		$sku_missing = ! empty( $settings['sku_missing_caption'] ) ? $settings['sku_missing_caption'] : esc_html__( 'N/A', 'thim-elementor-kit' );

		$category_label = ! empty( $settings['category_singular'] ) ? $settings['category_singular'] : esc_html__( 'Category', 'thim-elementor-kit' );
		$category_count = count( $product->get_category_ids() );

		if ( $category_count > 1 ) {
			$category_label = ! empty( $settings['category_plural'] ) ? $settings['category_plural'] : esc_html__( 'Categories', 'thim-elementor-kit' );
		}

		$tag_label = ! empty( $settings['tags_singular'] ) ? $settings['tags_singular'] : esc_html__( 'Tag', 'thim-elementor-kit' );
		$tag_count = count( $product->get_tag_ids() );

		if ( $tag_count > 1 ) {
			$tag_label = ! empty( $settings['tags_plural'] ) ? $settings['tags_plural'] : esc_html__( 'Tags', 'thim-elementor-kit' );
		}
		?>

		<div class="thim-ekit-single-product__meta">
			<?php do_action( 'woocommerce_product_meta_start' ); ?>

			<?php if ( wc_product_sku_enabled() && ( $sku || $product->is_type( 'variable' ) ) ) : ?>
				<span class="sku_wrapper thim-ekit-single-product__meta__sku">
					<span class="thim-ekit-single-product__meta__label"><?php echo esc_html( $sku_caption ); ?></span>
					<span class="sku thim-ekit-single-product__meta__content"><?php echo esc_html( $sku ? $sku : $sku_missing ); ?></span>
				</span>
			<?php endif; ?>

			<?php if ( $category_count ) : ?>
				<span class="posted_in thim-ekit-single-product__meta__categories">
					<span class="thim-ekit-single-product__meta__label"><?php echo esc_html( $category_label ); ?></span>
					<span class="thim-ekit-single-product__meta__content"><?php echo wp_kses_post( get_the_term_list( $product->get_id(), 'product_cat', '', ', ' ) ); ?></span>
				</span>
			<?php endif; ?>

			<?php if ( $tag_count ) : ?>
				<span class="tagged_as thim-ekit-single-product__meta__tags">
					<span class="thim-ekit-single-product__meta__label"><?php echo esc_html( $tag_label ); ?></span>
					<span class="thim-ekit-single-product__meta__content"><?php echo wp_kses_post( get_the_term_list( $product->get_id(), 'product_tag', '', ', ' ) ); ?></span>
				</span>
			<?php endif; ?>

			<?php do_action( 'woocommerce_product_meta_end' ); ?>
		</div>

		<?php
		do_action( 'thim-ekit/modules/single-product/after-preview-query' );
	}
}
