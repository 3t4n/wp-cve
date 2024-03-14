<?php
/**
 * Build Elementor Element
 *
 * @package Kadence Woocommerce Elementor.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Product Tabs Element
 *
 * @category class.
 */
class Product_Tabs_Element extends \Elementor\Widget_Base {

	protected $_has_template_content = false;

	public function get_name() {
		return 'product-tabs';
	}

	public function get_title() {
		return __( 'Product Tabs', 'kadence-woocommerce-elementor' );
	}

	public function get_icon() {
		return 'eicon-tabs';
	}

	public function get_categories() {
		return array( 'woocommerce-elements' );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Product Tabs', 'kadence-woocommerce-elementor' ),
			)
		);
		$this->add_control(
			'important_note',
			array(
				'label' => __( 'Element Information', 'kadence-woocommerce-elementor' ),
				'show_label' => false,
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __( 'This outputs the products tabs.', 'kadence-woocommerce-elementor' ),
				'content_classes' => 'kadence-woo-ele-info',
			)
		);

		$this->end_controls_section();

	}

	protected function render() {
		$post_type = get_post_type();
		if ( 'product' == $post_type ) {
			global $post;
			$template = get_post_meta( $post->ID, '_kt_woo_ele_product_template', true );
			if ( isset( $template ) && ! empty( $template ) && $template !== 'default' && $template === 'elementor' ) {
				// Save from endless Loop.
				add_filter( 'woocommerce_product_tabs', 'kt_woo_ele_remove_description_tab', 98 );
			} elseif ( did_action( 'kadence_woo_ele_content_ran' ) >= 1 ) {
				// Save from endless Loop.
				add_filter( 'woocommerce_product_tabs', 'kt_woo_ele_remove_description_tab', 98 );
			}
			woocommerce_output_product_data_tabs();
		} else if ( 'ele-product-template' == $post_type ) {
			echo '<div class="woocommerce"><div class="product"><div class="woocommerce-tabs wc-tabs-wrapper">
				<ul class="tabs wc-tabs" role="tablist">
					<li class="description_tab active" id="tab-title-description" role="tab" aria-controls="tab-description">
						<a href="#tab-description">Description</a>
					</li>
					<li class="additional_information_tab" id="tab-title-additional_information" role="tab" aria-controls="tab-additional_information">
						<a href="#tab-additional_information">Additional information</a>
					</li>
				</ul>
				<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--description panel entry-content wc-tab" id="tab-description" role="tabpanel" aria-labelledby="tab-title-description" style="display: block;">
					<h4>This outputs your products main content area</h4>
					<p>This can even be a elementor content from your product! While previewing in the template builder this demo content shows.</p>
					<p>Mauris eu est placerat, fringilla tellus ut, rhoncus ante. Nulla maximus ultrices ullamcorper. Aliquam dictum risus et odio pellentesque vestibulum. Vestibulum bibendum, erat eget luctus mollis, ante enim tincidunt sapien, at rutrum odio lorem eget ipsum. Vestibulum tincidunt fermentum ornare. Suspendisse consequat malesuada faucibus. Praesent fringilla, turpis nec convallis euismod, velit purus gravida nibh, in sodales orci leo non leo.</p>
					<p>Quisque tempor volutpat libero, aliquet venenatis turpis pulvinar sed. Maecenas eget ullamcorper purus. Vivamus magna libero, gravida at elit quis, eleifend faucibus dolor. Phasellus mattis risus at facilisis consequat. Donec mollis ipsum nec ex laoreet, at euismod metus finibus. Suspendisse interdum quam nec dignissim pulvinar.</p>
				</div>
				<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--additional_information panel entry-content wc-tab" id="tab-additional_information" role="tabpanel" aria-labelledby="tab-title-additional_information" style="display: none;">
					<table class="shop_attributes">
					<tbody>
						<tr>
							<th>Name</th>
							<td><p>Value</p></td>
						</tr>
					</tbody>
					</table>
				</div>
			</div></div></div>';
	    }
	}

	protected function _content_template() {}
}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Product_Tabs_Element());
