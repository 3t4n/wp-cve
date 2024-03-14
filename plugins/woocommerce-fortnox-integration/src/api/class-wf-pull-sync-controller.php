<?php

namespace src\api;

use src\fortnox\api\WF_Request;
use WP_REST_Controller;

class WF_Pull_Sync_Controller extends WP_REST_Controller{

    /**
     * Inits callback route
     */
    public function register_routes(){

        register_rest_route( WF_API_NAMESPACE, '/pull_sync', array(
            'methods' => 'GET',
            'callback' => array( $this, 'run_pull_sync' ),
            'permission_callback' => '__return_true'
        ));

    }

    /**
     * Main fetching function which is triggered by scheduler Loops through all
     * articles in Fortnox and updates inventory + price in WooCommerce.
     * @param \WP_REST_Request $request
     * @throws \Exception
     */
    public static function run_pull_sync( $request ) {

        fortnox_write_log("RUNNING PULL TASK");

        self::update_woo_stock_inventory();
    }

    /**
     * Fetches all active articles from Fortnox and updates stock in Woo
     * @throws \Exception
     */
    public static function update_woo_stock_inventory(){


        $current_page = 1;
        $total_pages = self::get_fortnox_products(1)['pages'];
        $index = 0;
        while ( $current_page <= $total_pages ) {

            // Attempt to set time limit in PHP to avoid script timeout
            set_time_limit(30);

            if ( $current_page == 1 ) {
                fortnox_write_log( 'Started syncing of Fortnox articles' );
                fortnox_write_log( 'There are ' . $total_pages . ' pages of articles to pull' );
                $start = microtime( true );
            }

            $fortnox_products = self::get_fortnox_products( $current_page )['products'];

            fortnox_write_log( 'Current page: ' . $current_page );

            foreach ( $fortnox_products as $product ) {
                fortnox_write_log( 'Updating ' . $product['ArticleNumber'] . ' ' . $product['DisposableQuantity'] );
                self::update_product_inventory_by_sku( $product['ArticleNumber'], $product['DisposableQuantity']);
                $index++;
            }
            $current_page++;

        }

        $time_elapsed_secs = microtime( true ) - $start;

        update_option( 'fortnox_pull_run_time', $time_elapsed_secs );
        update_option( 'fortnox_pull_lastrun', time() );

        fortnox_write_log( 'Finished syncing ' . $index . ' Fortnox articles in ' . $time_elapsed_secs . ' seconds' );

    }

    /**
     * Gets products (articles) from Fortnox and returns stock and price
     * together with total number of pages available
     *
     * @param      integer $page The page of products to get
     *
     * @return     array    The fortnox products
     * @throws \Exception
     */
    private static function get_fortnox_products( $page = 1) {

        $response = WF_Request::get("/articles?limit=500&filter=active&sortorder=descending&page={$page}");

        $arr['pages'] = $response->MetaInformation->{'@TotalPages'};

        foreach ( $response->Articles as $id => $article ) {
            $arr['products'][$id]['ArticleNumber'] = $article->ArticleNumber;
            $arr['products'][$id]['DisposableQuantity'] = $article->DisposableQuantity;
        }

        return $arr;

    }

    /**
     * Update inventory from Fortnox
     *
     * @param      mixed $sku The article number / SKU to update
     * @param      int $new_quantity The new inventory quantity
     * @return bool
     */
    private static function update_product_inventory_by_sku( $sku, $new_quantity ) {

        $product_id = wc_get_product_id_by_sku( $sku );

        if ( $product_id === null ) {
            return false;
        }

        $product = wc_get_product( $product_id );
        if( ! $product ){
            return false;
        }

        self::update_product_inventory( $product, $product_id, $new_quantity );
    }


    /**
     * Update product inventory from Fortnox
     *
     * @param \WC_Product $product
     * @param int $product_id
     * @param int $new_quantity The new inventory quantity
     * @return bool
     */

    private static function update_product_inventory( $product, $product_id, $new_quantity ){

        fortnox_write_log( 'Update product inventory for product_id: ' . $product_id );

        global $wpdb;

        if( ! $product->managing_stock() ){
            fortnox_write_log( 'Returning ' . $product_id );
            return false;
        }

        if( intval( $product->get_stock_quantity() ) == intval( $new_quantity ) ){
            return false;
        }

        $stock_status = $new_quantity <= 0 ? 'outofstock' : 'instock';
        $product->set_stock_quantity( $new_quantity );
        $product->set_stock_status( $stock_status );
        $product->save();

    }
}