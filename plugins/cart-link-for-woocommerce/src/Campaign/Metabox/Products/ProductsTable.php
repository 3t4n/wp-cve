<?php

namespace IC\Plugin\CartLinkWooCommerce\Campaign\Metabox\Products;

use IC\Plugin\CartLinkWooCommerce\Campaign\Campaign;
use IC\Plugin\CartLinkWooCommerce\Campaign\CampaignProduct;
use IC\Plugin\CartLinkWooCommerce\PluginData;
use Exception;
use WP_List_Table;

/**
 * Products table.
 */
class ProductsTable extends WP_List_Table {

	/**
	 * @var PluginData
	 */
	private $plugin_data;

	/**
	 * @param PluginData $plugin_data .
	 *
	 * @return void
	 */
	public function set_plugin_data( PluginData $plugin_data ) {
		$this->plugin_data = $plugin_data;
	}

	/**
	 * @param CampaignProduct[] $items .
	 *
	 * @return void
	 */
	public function set_items( array $items ) {
		$this->items = $items;
	}

	/**
	 * @param CampaignProduct $item .
	 *
	 * @return string
	 */
	public function column_cb( $item ): string {
		return '<input class="js--cb-field js--action-field-trigger" type="checkbox" />';
	}

	/**
	 * @param CampaignProduct $item .
	 *
	 * @return string
	 */
	public function column_name( CampaignProduct $item ): string {
		try {
			$option_value = $item->get_product_id();
			$option_label = $item->get_product()->get_name();
		} catch ( Exception $e ) {
			$option_value = '';
			$option_label = '';
		}

		return woocommerce_form_field(
			$this->get_field_key( $item, CampaignProduct::FIELD_PRODUCT_ID ),
			[
				'type'              => 'select',
				'return'            => true,
				'options'           => [
					$option_value => $option_label,
				],
				'input_class'       => [ 'wc-product-search' ],
				'required'          => true,
				'custom_attributes' => [
					'required'           => 'required',
					'data-placeholder'   => __( 'Search for a product&hellip;', 'cart-link-for-woocommerce' ),
					'data-action'        => 'woocommerce_json_search_products_and_variations',
					'data-display_stock' => 'true',
					'data-exclude_type'  => 'variable',
					'data-item_id'       => $item->get_id(),
				],
			]
		);
	}

	/**
	 * @param CampaignProduct $item .
	 *
	 * @return string
	 */
	public function column_qty( CampaignProduct $item ): string {
		return woocommerce_form_field(
			$this->get_field_key( $item, CampaignProduct::FIELD_QUANTITY ),
			[
				'type'              => 'number',
				'return'            => true,
				'required'          => true,
				'custom_attributes' => [
					'min'          => 0,
					'step'         => 'any',
					'required'     => 'required',
					'data-item_id' => $item->get_id(),
				],
			],
			$item->get_quantity()
		);
	}

	/***
	 * @param CampaignProduct $item .
	 *
	 * @return string
	 */
	public function column_price( CampaignProduct $item ): string {
		return woocommerce_form_field(
			$this->get_field_key( $item, CampaignProduct::FIELD_PRICE ),
			[
				'type'              => 'number',
				'return'            => true,
				'default'           => 1,
				'placeholder'       => '9.99',
				'custom_attributes' => [
					'min'          => 0,
					'step'         => '0.01',
					'data-item_id' => $item->get_id(),
				],
			],
			$item->get_price() === CampaignProduct::PRICE_UNDEFINED ? '' : $item->get_price()
		);
	}

	/**
	 * @return void
	 */
	public function prepare_items(): void {
		$this->_column_headers = [ $this->get_columns(), [], $this->get_sortable_columns() ];
	}

	/**
	 * Message to be displayed when there are no items.
	 *
	 * @return void
	 */
	public function no_items(): void {
		esc_attr_e( 'Add the first product to set up the campaigns.', 'cart-link-for-woocommerce' );
	}

	/**
	 * @return void
	 */
	public function display(): void {

		$this->screen->render_screen_reader_content( 'heading_list' );

		$table_classes = $this->get_table_classes();
		$column_count  = $this->get_column_count();
		$default       = new CampaignProduct( [ 'id' => 'XXX' ] );

		include $this->plugin_data->get_plugin_absolute_path( 'views/html-table-products.php' );
	}

	/**
	 * Gets a list of columns.
	 *
	 * @return string[]
	 */
	public function get_columns(): array {
		return [
			'cb'    => '<input type="checkbox" class="js--action-field-trigger" />',
			'name'  => __( 'Name', 'cart-link-for-woocommerce' ),
			'qty'   => __( 'Qty', 'cart-link-for-woocommerce' ),
			'price' => wc_help_tip( __( 'Determine the final discounted price to be used for the given amount of the products entered in the \'Qty\' field once the cart link is used.', 'cart-link-for-woocommerce' ) ) . __( 'Price', 'cart-link-for-woocommerce' ),
		];
	}

	/**
	 * @param CampaignProduct $item .
	 * @param string          $name .
	 *
	 * @return string
	 */
	private function get_field_key( CampaignProduct $item, string $name ): string {
		return sprintf( '%s[%s][%s]', Campaign::META_PRODUCTS, $item->get_id(), $name );
	}
}
