<?php

namespace WpifyWoo\Modules\PricesLog;

use WP_Error;
use WP_Post;
use WpifyWoo\Abstracts\AbstractModule;

class PricesLogModule extends AbstractModule {
	private PricesLogRepository $prices_log_repository;

	public function __construct(
		PricesLogRepository $prices_log_repository
	) {
		parent::__construct();

		$this->prices_log_repository = $prices_log_repository;
	}

	/**
	 * @return void
	 */
	public function setup() {
		add_filter( 'wpify_woo_settings_' . $this->id(), array( $this, 'settings' ) );
		add_action( 'woocommerce_update_product', [ $this, 'handle_save_log' ] );
		add_action( 'woocommerce_new_product', [ $this, 'handle_save_log' ] );
		add_action( 'admin_init', array( $this, 'create_table' ) );
		add_filter( 'woocommerce_product_data_tabs', [ $this, 'product_tabs' ], 10, 1 );
		add_action( 'woocommerce_product_data_panels', [ $this, 'product_tab_content' ] );
		add_action( 'woocommerce_product_options_pricing', [ $this, 'display_lowest_price' ] );
		add_action( 'woocommerce_variation_options_pricing', [ $this, 'variation_lowest_price' ], 10, 3 );

	}

	function id() {
		return 'prices_log';
	}

	public function name() {
		return __( 'Prices Log', 'wpify-woo' );
	}

	/**
	 * @return array[]
	 */
	public function settings(): array {
		$settings = array(
			array(
				'type'  => 'title',
				'label' => __( 'Recording product price history', 'wpify-woo' ),
				'desc'  => __( 'Product prices are logged whenever they change. The lowest price recorded in the last 30 days is displayed below the product price field.',
					'wpify-woo' ),
			),
		);

		return $settings;
	}

	public function create_table() {
		$this->prices_log_repository->create_table();
	}

	/**
	 * Handle saving prices
	 *
	 * @param $product_id
	 *
	 * @throws \Exception
	 */
	public function handle_save_log( $product_id ) {
		if ( ! $this->is_module_enabled() ) {
			return;
		}

		if ( ! $this->prices_log_repository->table_exist() ) {
			$this->create_table();
		}

		$product = wc_get_product( $product_id );

		if ( $product->is_type( 'variable' ) ) {
			/** @var \WC_Product_Variable $product */
			$variations = $product->get_available_variations();

			foreach ( $variations as $variation ) {
				$variation = wc_get_product( $variation['variation_id'] );

				$this->save_log( $variation );
			}
		} else {

			$this->save_log( $product );
		}
	}

	/**
	 * Save price into log
	 *
	 * @param \WC_Product|\WC_Product_Variation $product
	 *
	 * @throws \Exception
	 */
	public function save_log( $product ) {
		$log  = $this->prices_log_repository->create();
		$last = $this->prices_log_repository->get_last_by_product_id( $product->get_id() );
		if ( $last && floatval( $last->regular_price ) === floatval( $product->get_regular_price() ) && floatval( $last->sale_price ) === floatval( $product->get_sale_price() ) ) {
			return;
		}

		$log->product_id    = $product->get_id();
		$log->regular_price = $product->get_regular_price();
		$log->sale_price    = $product->get_sale_price();

		$log->created_at = date( 'Y-m-d H:i:s' );
		$this->prices_log_repository->save( $log );
	}

	/**
	 * Add product tab
	 *
	 * @param $default_tabs
	 *
	 * @return array
	 */
	public function product_tabs( $default_tabs ): array {
		$default_tabs['wpify_prices_log'] = array(
			'target'   => 'wpify_prices_log',
			'label'    => __( 'Prices log', 'wpify-woo' ),
			'priority' => 60,
			'class'    => array(),
		);

		return $default_tabs;
	}

	/**
	 * Content of Price log tab
	 */
	public function product_tab_content() {
		global $product;

		if ( ! $product ) {
			$product = wc_get_product( (int) $_GET['post'] ?? 0 );
		}

		if ( empty( $product ) ) {
			return;
		}

		?>
        <div id="wpify_prices_log" class="panel woocommerce_options_panel">
			<?php
			if ( $product->is_type( 'variable' ) ) {
				/** @var \WC_Product_Variable $product */
				$variations = $product->get_available_variations();

				foreach ( $variations as $variation ) {

					$this->display_log_table( $variation['variation_id'] );
				}
			} else {

				$this->display_log_table( $product->get_id() );
			}
			?>
        </div>
		<?php
	}

	/**
	 * Render price log table
	 *
	 * @param $id
	 */
	public function display_log_table( $id ) {
		?>
        <table class="wp-list-table widefat fixed striped table-view-list">
            <thead>
            <tr>
                <th>Product ID</th>
                <th>Regular price</th>
                <th>Sale price</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
			<?php foreach ( array_reverse( $this->prices_log_repository->find_by_product_id( $id ) ) as $item ) { ?>
                <tr>
                    <td><?php echo $item->product_id; ?></td>
                    <td><?php echo $item->regular_price; ?></td>
                    <td><?php echo $item->sale_price; ?></td>
                    <td><?php echo $item->created_at; ?></td>
                </tr>
			<?php } ?>

            </tbody>

        </table>
	<?php }

	/**
	 * Lowest price for variation product
	 *
	 * @param int $loop Position in the loop.
	 * @param array $variation_data Variation data.
	 * @param WP_Post $variation Post data.
	 */
	public function variation_lowest_price( $loop, $variation_data, $variation ) {
		$this->display_lowest_price( $variation->ID );
	}

	/**
	 * Display the lowest price last 30 days
	 *
	 * @param $id
	 */
	public function display_lowest_price( $id ) {
		if ( ! $id ) {
			$id = (int) $_GET['post'] ?? 0;
		}

		$price = $this->prices_log_repository->find_lowest_price( $id );
		if ( ! $price ) {
			return;
		}

		echo sprintf( '<p class="form-row form-row-full">%s: %s</p>', __( 'The lowest price for the last 30 days', 'wpify-woo' ), wc_price( $price ) );
	}
}
