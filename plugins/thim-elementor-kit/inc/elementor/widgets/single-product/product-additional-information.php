<?php
namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

class Thim_Ekit_Widget_Product_Additional_Information extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-product-additional-information';
	}

	public function get_title() {
		return esc_html__( 'Product Additional Information', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return ' eicon-product-info';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY_SINGLE_PRODUCT );
	}

	public function get_help_url() {
		return '';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_product_addition_info_style',
			array(
				'label' => esc_html__( 'Style', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'show_heading',
			array(
				'label'        => esc_html__( 'Heading', 'thim-elementor-kit' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'thim-elementor-kit' ),
				'label_off'    => esc_html__( 'Hide', 'thim-elementor-kit' ),
				'render_type'  => 'ui',
				'return_value' => 'yes',
				'default'      => 'yes',
				'prefix_class' => 'elementor-show-heading-',
			)
		);

		$this->add_control(
			'heading_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} h2' => 'color: {{VALUE}}',
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
				'label'     => esc_html__( 'Typography', 'thim-elementor-kit' ),
				'selector'  => '{{WRAPPER}} h2',
				'condition' => array(
					'show_heading!' => '',
				),
			)
		);

		$this->add_control(
			'heading_label_style',
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
					'{{WRAPPER}} .shop_attributes .woocommerce-product-attributes-item__label' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'label_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .shop_attributes .woocommerce-product-attributes-item__label' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'label'    => esc_html__( 'Typography', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .shop_attributes .woocommerce-product-attributes-item__label',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'label_border',
				'selector' => '{{WRAPPER}} .shop_attributes .woocommerce-product-attributes-item__label',
			)
		);

		$this->add_control(
			'heading_value_style',
			array(
				'label'     => esc_html__( 'Value', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'value_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .shop_attributes .woocommerce-product-attributes-item__value' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'value_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .shop_attributes .woocommerce-product-attributes-item__value' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'value_typography',
				'label'    => esc_html__( 'Typography', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .shop_attributes .woocommerce-product-attributes-item__value',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'value_border',
				'selector' => '{{WRAPPER}} .shop_attributes .woocommerce-product-attributes-item__value',
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
		?>

		<div class="thim-ekit-single-product__additional-information">
			<?php wc_get_template( 'single-product/tabs/additional-information.php' ); ?>
		</div>

		<?php
		do_action( 'thim-ekit/modules/single-product/after-preview-query' );
	}
}
