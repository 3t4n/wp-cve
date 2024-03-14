<?php

namespace WpifyWoo\Modules\Template;

use WP_Error;
use WpifyWoo\Abstracts\AbstractModule;

class TemplateModule extends AbstractModule {
	private $rendered = [];

	/**
	 * @return void
	 */
	public function setup() {
		add_filter( 'wpify_woo_settings_' . $this->id(), array( $this, 'settings' ) );
		add_filter( 'woocommerce_order_button_text', [ $this, 'order_button_text' ] );
		add_filter( 'woocommerce_order_button_html', [ $this, 'order_button_html' ] );
		add_action( 'woocommerce_review_order_before_submit', [ $this, 'render_text' ] );
		add_action( 'init', [ $this, 'render_texts' ] );
	}

	function id() {
		return 'template';
	}

	/**
	 * @return array[]
	 */
	public function settings(): array {
		$settings = array(
			array(
				'id'    => 'place_order_button_text',
				'type'  => 'text',
				'label' => __( 'Order button text', 'wpify-woo' ),
			),
			array(
				'id'    => 'place_order_button_html',
				'type'  => 'toggle',
				'label' => __( 'Change order button HTML', 'wpify-woo' ),
				'desc'  => __( 'Turn on if you want to insert html entities into the button text. The text will be inserted directly into the button\'s HTML code. Warning: unprofessional intervention may affect the checkout page display.', 'wpify-woo' ),
			),

			array(
				'id'    => 'checkout_texts',
				'type'  => 'multi_group',
				'label' => __( 'Custom notes', 'wpify-woo' ),
				'items' => [
					[
						'id'    => 'content',
						'label' => __( 'Content', 'wpify-woo' ),
						'type'  => 'wysiwyg',
					],
					[
						'id'      => 'position',
						'label'   => __( 'Position', 'wpify-woo' ),
						'type'    => 'select',
						'options' => [
							[
								'label' => __( 'Checkout - Before place order button', 'wpify-woo' ),
								'value' => 'woocommerce_review_order_before_submit',
							],
							[
								'label' => __( 'Checkout - After place order button', 'wpify-woo' ),
								'value' => 'woocommerce_review_order_after_submit',
							],
							[
								'label' => __( 'Checkout - Before checkout form', 'wpify-woo' ),
								'value' => 'woocommerce_before_checkout_form',
							],
							[
								'label' => __( 'Checkout - Before checkout billing form', 'wpify-woo' ),
								'value' => 'woocommerce_before_checkout_billing_form',
							],
							[
								'label' => __( 'Checkout - After checkout billing form', 'wpify-woo' ),
								'value' => 'woocommerce_after_checkout_billing_form',
							],
							[
								'label' => __( 'Checkout - Before checkout shipping form', 'wpify-woo' ),
								'value' => 'woocommerce_before_checkout_shipping_form',
							],
							[
								'label' => __( 'Checkout - After checkout shipping form', 'wpify-woo' ),
								'value' => 'woocommerce_after_checkout_shipping_form',
							],
							[
								'label' => __( 'Checkout - Before order review', 'wpify-woo' ),
								'value' => 'woocommerce_checkout_before_order_review',
							],
							[
								'label' => __( 'Checkout - Before payment', 'wpify-woo' ),
								'value' => 'woocommerce_review_order_before_payment',
							],
							[
								'label' => __( 'Cart - Before cart', 'wpify-woo' ),
								'value' => 'woocommerce_before_cart',
							],
							[
								'label' => __( 'Cart - After cart contents', 'wpify-woo' ),
								'value' => 'woocommerce_after_cart_contents',
							],
							[
								'label' => __( 'Cart - Before cart totals', 'wpify-woo' ),
								'value' => 'woocommerce_before_cart_totals',
							],
							[
								'label' => __( 'Cart - Before shipping', 'wpify-woo' ),
								'value' => 'woocommerce_cart_totals_before_shipping',
							],
							[
								'label' => __( 'Cart - Before proceed to checkout', 'wpify-woo' ),
								'value' => 'woocommerce_proceed_to_checkout',
							],
							[
								'label' => __( 'Cart - After cart', 'wpify-woo' ),
								'value' => 'woocommerce_after_cart',
							],
						],
					],
					[
						'id'    => 'custom_position',
						'type'  => 'text',
						'label' => __( 'Custom position', 'wpify-woo' ),
						'desc'  => __( 'Insert the hook where the content is to be placed if you do not use the predefined positions.', 'wpify-woo' ),
					],
					[
						'id'      => 'style',
						'label'   => __( 'Woocommerce notice style', 'wpify-woo' ),
						'desc'    => __( 'Select woocommerce notification style or leave blank.', 'wpify-woo' ),
						'type'    => 'select',
						'options' => [
							[
								'label' => __( 'Woocommerce message', 'wpify-woo' ),
								'value' => 'woocommerce-message',
							],
							[
								'label' => __( 'Woocommerce info', 'wpify-woo' ),
								'value' => 'woocommerce-info',
							],
							[
								'label' => __( 'Woocommerce error', 'wpify-woo' ),
								'value' => 'woocommerce-error',
							],
						],
					],
					[
						'id'    => 'custom_class',
						'type'  => 'text',
						'label' => __( 'Custom class', 'wpify-woo' ),
						'desc'  => __( 'Insert a custom class selector if you want to apply custom styles to the message.', 'wpify-woo' ),
					],
				],
			),
		);

		return $settings;
	}

	public function name() {
		return __( 'Template', 'wpify-woo' );
	}

	public function order_button_text( $text ) {
		$custom_text = $this->get_setting( 'place_order_button_text' );

		return $custom_text ?: $text;
	}

	public function order_button_html( $html ) {
		$change_html = $this->get_setting( 'place_order_button_html' ) ?? false;

		if ( $change_html ) {
			$custom_text = $this->get_setting( 'place_order_button_text' );
			$html = '<button type="submit" class="button alt' . esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ) . '" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( strip_tags( str_replace(['<br>','<br/>','<br />'], ' ', $custom_text ) ) ) . '">' . $custom_text . '</button>';
		}

		return $html;
	}

	public function render_texts() {
		$positions = $this->get_setting( 'checkout_texts' ) ?: array();
		foreach ( $positions as $position ) {
			add_action( $position['custom_position'] ?: $position['position'], [ $this, 'render_text' ] );
		}
	}

	public function render_text() {
		$positions = $this->get_setting( 'checkout_texts' ) ?: array();
		foreach ( $positions as $key => $position ) {
			if ( ! in_array( current_filter(), array( $position['position'], $position['custom_position'] ) ) ) {
				continue;
			}

			if ( in_array( $key, $this->rendered ) ) {
				continue;
			}

			$style   = $position['style'] ?? '';
			$style   = $position['custom_class'] ? $style . ' ' . $position['custom_class'] : $style;
			$style   = $style ? ' class="' . $style . '"' : '';
			$content = '<div ' . $style . '>' . $position['content'] . '</div>';

			echo apply_filters( 'the_content', $content );
			$this->rendered[] = $key;
		}
	}

}
