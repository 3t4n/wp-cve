<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Automattic\WooCommerce\Admin\BlockTemplates\BlockInterface;

if ( ! class_exists( 'WC_Szamlazz_Product_Options', false ) ) :

	class WC_Szamlazz_Product_Options {

		//Init notices
		public static function init() {
			add_action('woocommerce_product_options_advanced', array( __CLASS__, 'product_options_fields'));
			add_action('woocommerce_admin_process_product_object', array( __CLASS__, 'save_product_options_fields'), 10, 2);

			add_action( 'woocommerce_product_after_variable_attributes', array( __CLASS__, 'variable_options_fields'), 10, 3 );
			add_action( 'woocommerce_save_product_variation', array( __CLASS__, 'save_variable_options_fields'), 10, 2 );

			add_filter('woocommerce_shipping_instance_form_fields_flat_rate', array( __CLASS__, 'shipping_options_fields'));
			add_filter('woocommerce_shipping_instance_form_fields_free_shipping', array( __CLASS__, 'shipping_options_fields'));
			add_filter('woocommerce_shipping_instance_form_fields_local_pickup', array( __CLASS__, 'shipping_options_fields'));

			//Product editor compatibility
			add_action( 'woocommerce_block_template_area_product-form_after_add_block_product-pricing-section',  array( __CLASS__, 'create_tab_in_product_editor' ) );

		}

		public static function variable_options_fields($loop, $variation_data, $variation) {
			include( dirname( __FILE__ ) . '/views/html-variable-options.php' );
		}

		public static function product_options_fields() {
			global $post;
			include( dirname( __FILE__ ) . '/views/html-product-options.php' );
		}

		public static function shipping_options_fields($fields){
			$fields['wc_szamlazz_tetel_nev'] = [
				'title' => esc_html__('Line item name', 'wc-szamlazz'),
				'type'        => 'text',
				'description' => esc_html__('Enter a custom name that will appear on the invoice. Default is the name of the shipping method.', 'wc-szamlazz'),
				'default'     => '',
				'desc_tip'    => true,
			];
			$fields['wc_szamlazz_tetel_megjegyzes'] = [
				'title' => esc_html__('Note', 'wc-szamlazz'),
				'type'        => 'text',
				'description' => esc_html__('This note will be visible on the invoice line item.', 'wc-szamlazz'),
				'default'     => '',
				'desc_tip'    => true,
			];
			$fields['wc_szamlazz_tetel_mennyisegi_egyseg'] = [
				'title' => esc_html__('Unit type', 'wc-szamlazz'),
				'type'        => 'text',
				'description' => esc_html__('This is the unit type for the line item on the invoice. The default value is set in the plugin settings.', 'wc-szamlazz'),
				'default'     => '',
				'desc_tip'    => true,
			];
			return $fields;
		}

		public static function save_product_options_fields($product) {
			$fields = ['mennyisegi_egyseg', 'megjegyzes', 'tetel_nev', 'disable_auto_invoice', 'hide_item', 'custom_cost'];
			foreach ($fields as $field) {
				if(isset($_REQUEST['wc_szamlazz_'.$field])) {
					$posted_data = $_REQUEST['wc_szamlazz_'.$field];
					if(!empty($posted_data) && !is_array($posted_data)) {
						$posted_data = wp_kses_post( trim( wp_unslash($_REQUEST['wc_szamlazz_'.$field]) ) );
					} else {
						$posted_data = '';
					}
					$product->update_meta_data( 'wc_szamlazz_'.$field, $posted_data);
				} else {
					$product->delete_meta_data( 'wc_szamlazz_'.$field);
				}
			}
			$product->save_meta_data();
		}

		public static function save_variable_options_fields($variation_id, $i) {
			$fields = ['mennyisegi_egyseg', 'megjegyzes', 'tetel_nev', 'disable_auto_invoice', 'hide_item', 'custom_cost'];
			$product_variation = wc_get_product_object( 'variation', $variation_id );
			foreach ($fields as $field) {
				if(isset($_POST['wc_szamlazz_'.$field][$i])) {
					$custom_field = $_POST['wc_szamlazz_'.$field][$i];
					if ( ! empty( $custom_field ) ) {
						$product_variation->update_meta_data('wc_szamlazz_'.$field, wp_kses_post( trim( wp_unslash($custom_field) ) ));
					}
				} else {
					$product_variation->delete_meta_data('wc_szamlazz_'.$field);
				}
			}
			$product_variation->save();
		}

		public static function create_tab_in_product_editor(BlockInterface $pricing_group) {
			$parent = $pricing_group->get_parent();
			$section = $parent->add_section([
				'id'         => 'wc-szamlazz-invoice-details',
				'order'      => 30,
				'attributes' => [
				  'title' => __( 'Invoice settings', 'wc-szamlazz' ),
				  'description' => __('Change how this product will show up on your Számlázz.hu invoices.', 'wc-szamlazz')
				],
			]);

			$section->add_block([
				'id'         => 'wc_szamlazz_tetel_nev',
				'order'      => 1,
				'blockName'  => 'woocommerce/product-text-field',
				'attributes' => [
					'property' => 'meta_data.wc_szamlazz_tetel_nev',
					'label'    => __( 'Line item name', 'wc-szamlazz' ),
					'tooltip' => __('Enter a custom name that will appear on the invoice. Default is the name of the product.', 'wc-szamlazz')
				],
			]);

			$section->add_block([
				'id'         => 'wc_szamlazz_megjegyzes',
				'order'      => 2,
				'blockName'  => 'woocommerce/product-text-field',
				'attributes' => [
					'property' => 'meta_data.wc_szamlazz_megjegyzes',
					'label'    => __( 'Line item comment', 'wc-szamlazz' ),
					'tooltip' => __('This note will be visible on the invoice line item.', 'wc-szamlazz')
				],
			]);


			$columns = $section->add_block(
				[
					'id'        => 'wc-szamlazz-cost-columns',
					'blockName' => 'core/columns',
					'order'     => 3,
				]
			);

			$columns_left = $columns->add_block(
				[
					'id'         => 'wc-szamlazz-cost-columns-left',
					'blockName'  => 'core/column',
					'order'      => 10,
					'attributes' => [
						'templateLock' => 'all',
					],
				]
			);

			$columns_right = $columns->add_block(
				[
					'id'         => 'wc-szamlazz-cost-columns-right',
					'blockName'  => 'core/column',
					'order'      => 20,
					'attributes' => [
						'templateLock' => 'all',
					],
				]
			);

			$columns_left->add_block([
				'id'         => 'wc_szamlazz_custom_cost',
				'order'      => 2,
				'blockName'  => 'woocommerce/product-pricing-field',
				'attributes' => [
					'property' => 'meta_data.wc_szamlazz_custom_cost',
					'label'    => __( 'Cost on invoice', 'wc-szamlazz' ),
					'help' => __('You can overwrite the price of the product on the invoice with this option(enter a net price).', 'wc-szamlazz')
				],
			]);

			$columns_right->add_block([
				'id'         => 'wc_szamlazz_mennyisegi_egyseg',
				'order'      => 1,
				'blockName'  => 'woocommerce/product-text-field',
				'attributes' => [
					'property' => 'meta_data.wc_szamlazz_mennyisegi_egyseg',
					'label'    => __( 'Unit type', 'wc-szamlazz' ),
					'help' => __('This is the unit type for the line item on the invoice. The default value is set in the plugin settings.', 'wc-szamlazz')
				],
			]);

			$section->add_block([
				'id'         => 'wc_szamlazz_disable_auto_invoice',
				'order'      => 5,
				'blockName'  => 'woocommerce/product-toggle-field',
				'attributes' => [
					'property' => 'meta_data.wc_szamlazz_disable_auto_invoice',
					'checkedValue'   => 'yes',
					'uncheckedValue' => 'no',
					'label'    => __( 'Turn off auto invoicing', 'wc-szamlazz' ),
					'tooltip' => __('If checked, no invoice will be automatically issued for the order if this product is included in the order.', 'wc-szamlazz')
				],
			]);

			$section->add_block([
				'id'         => 'wc_szamlazz_hide_item',
				'order'      => 6,
				'blockName'  => 'woocommerce/product-toggle-field',
				'attributes' => [
					'property' => 'meta_data.wc_szamlazz_hide_item',
					'checkedValue'   => 'yes',
					'uncheckedValue' => 'no',
					'label'    => __( 'Hide from invoice', 'wc-szamlazz' ),
					'tooltip' => __('If checked, this product will be hidden on the invoices.', 'wc-szamlazz')
				],
			]);

		}
	}

	WC_Szamlazz_Product_Options::init();

endif;
