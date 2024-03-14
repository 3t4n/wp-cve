<?php

namespace src\fortnox\api;

if ( !defined( 'ABSPATH' ) ) die();

use Exception;
use src\fortnox\WF_Plugin;
use src\fortnox\WF_Sku_Generator;
use src\fortnox\WF_Utils;
use \WC_Product;
use \WC_Product_Variable;


class WF_Products {

    /**
     * Check wether product is synced to Fortnox
     *
     *
     * @param int $product_id
     * @return bool|mixed
     */
	public static function is_synced( $product_id ) {
		$product = new WC_Product( $product_id );

		if( $variations = self::has_variations( $product_id ) ) {
			foreach( $variations as $variation ) {
				if( ! get_post_meta( $variation->ID, '_is_synced_to_fortnox', true ) ) return false;
			}
			return true;
		}else{
			return get_post_meta( $product->get_id(), '_is_synced_to_fortnox', true );
		}
	}

    /** Returns true if product is variation
     * @param \WC_Product $product
     * @return bool
     */
	public static function is_variation( $product ) {
		if( get_class( $product ) == 'WC_Product_Variation'  ) {
			return true;
		}
		return false;
	}

    /**
     * @return array
     */
    public static function get_all_product_ids() {
	    global $wpdb;
        $results = $wpdb->get_results( "SELECT ID FROM $wpdb->posts p WHERE p.post_type IN ( 'product', 'product_variation' ) AND NOT EXISTS ( SELECT * FROM $wpdb->postmeta pm
               WHERE pm.meta_key = '_is_synced_to_fortnox'
        AND pm.post_id=p.ID)" );

        $func = function( $result ) {
            return $result->ID;
        };
        return array_map( $func, $results );

    }

    /**
     * @param $product_id
     * @return array
     */
	public static function has_variations( $product_id ) {
		return get_posts( [
			'post_parent'   => $product_id,
			'post_status'   => "publish",
			'post_type'     => "product_variation",
			'numberposts'   => -1 # remove the output limitation
		] );
	}

    /**
     * Get custom account
     *
     * @param WC_Product $product
     * @return mixed
     */
    public static function get_custom_account( $product ) {

        if ( $product->is_taxable() ){
            $vat_percentage = (int) WF_Utils::get_wc_tax_rate( $product, 'SE'  );

            if ( in_array( $vat_percentage, [0, 6, 12, 25] ) ){
                $account = get_option( 'fortnox_products_' . $vat_percentage .  '_account' , true );

                if ( ! empty( $account ) ) {
                    if ( intval( $account ) >= 3000 && intval( $account ) < 4000) {
                        return $account;
                    }
                }
            }
        }

        return false;
    }

    /** Returns true if product exists in Fortnox
     * @param $sku
     * @return int
     * @throws \Exception
     */
	public static function exists_in_fortnox( $sku ) {
		$response = WF_Request::get("/articles?articlenumber=" . str_replace( ' ', '_', $sku ) );
        foreach ( $response->Articles as $article ) {
            if( $article->ArticleNumber === $sku ){
                return true;
            }
		}
		return false;

	}

    /** Returns variation name
     * @param $item
     * @param \WC_Product $product
     * @return string
     */
    public static function get_variation_name( $item, $product ) {

        $attribute_keys = array_filter( $product->get_attributes(), function( $attribute, $key ){
            if( $attribute['is_variation'] == 1 ){
                return $key;
            }
        }, ARRAY_FILTER_USE_BOTH );

        if( count( $attribute_keys ) > 0){
            $func = function( $key ) use( $item ){
                return $item[$key];
            };
            #TODO Change to 200 chars
            return self::truncate_over_fifty( $item['name'] . ' - ' . implode( array_map( $func, $attribute_keys ) ) );
        }
        else{
            return self::truncate_over_fifty( $item['name']);
        }
    }

    /** Return setting for default Fortnox price list
     * @return mixed
     */
    public static function fortnox_price_list() {
		$fortnox_default_price_list = get_option( 'fortnox_default_price_list'  );
		$price_list = ! isset( $fortnox_default_price_list ) || empty( $fortnox_default_price_list ) ? "A" : get_option( 'fortnox_default_price_list'  );
		return $price_list;
    }

    /**
     * Sanitize description
     *
     * @param string $description
     * @return string
     */
    public static function sanitize_description( $description ) {
        return sanitize_text_field( preg_replace( '/\"|\|/', '', $description ) );
    }

    /**
     * Sanitized SKU
     *
     * @param string $sku
     * @return string
     */
    public static function sanitized_sku( $sku ) {
        return preg_replace( '/[^A-Za-z0-9-+._\/]/', '', $sku );
    }

    /**
     * @param $product_id
     * @param bool $product_sync
     * @return bool|mixed|string
     * @throws \Exception
     */
    public static function sync( $product_id, $sync_stock = false ) {

		$product = wc_get_product( $product_id );
		$sku = self::get_sku( $product );

		if( $variations = self::has_variations( $product_id ) ) {

			if( ! get_option( 'fortnox_skip_product_variations'  ) )
				foreach( $variations as $variation ){
                    self::sync( $variation->ID, $sync_stock );
                }

			if( ! get_option( 'fortnox_sync_master_product'  ) )
                update_post_meta( $product_id, '_is_synced_to_fortnox', 1 );
				return $sku;
		}

		$article = [
			'ArticleNumber' => $sku,
			'Description'   => self::get_formatted_title( $product ),
			'Type'          => $product->is_virtual() ? 'SERVICE' : 'STOCK',
		];

        if ( $account = self::get_custom_account( $product ) ){
            $article['SalesAccount'] = $account;
        }

        $article = array_merge( $article, self::get_product_dimensions( $product ) );
        $article = array_merge( $article, self::get_product_weight( $product ) );

        if ( $product->get_manage_stock() && $sync_stock ) {
            $article['StockGoods'] = true;
            $article['QuantityInStock'] = $product->get_stock_quantity();
		}

        if( get_option( 'fortnox_enable_purchase_price' ) ) {
            if( floatval( get_post_meta( $product->get_id() , '_fortnox_purchase_price',true ) ) != 0){
                $article['PurchasePrice'] = get_post_meta( $product->get_id() , '_fortnox_purchase_price',true );
            }
        }

        $article = apply_filters( 'wf_product_payload_before_create_or_update', $article, $product );
        $article_response = false;
		if ( ! self::exists_in_fortnox( $sku ) ) {
			try {
				$article_response = WF_Request::post( "/articles", [ 'Article' => $article ] );
			} catch ( Exception $e ) {
				error_log( "Error when syncing article to Fortnox. SKU: " . $sku );
			}
		}
		else {
            if( ! get_option( 'fortnox_do_not_update_product_on_order_sync'  ) ) {

                try {
                    $article_response = WF_Request::put( "/articles/{$sku}", [ 'Article' => $article ]);
                } catch ( Exception $error ) {
                    if ( 2000513 === $error->getCode() ) {
                        $article_response = WF_Request::post( "/articles", [ 'Article' => $article  ]);
                    }
                }
            }
		}

        if( ! get_option( 'fortnox_do_not_sync_price'  ) ) {
            self::set_fortnox_price( $product, self::fortnox_price_list(), $sku );
        }

		update_post_meta( $product_id, '_is_synced_to_fortnox', 1 );

		return $article_response;
	}

    /**
     * Return SKU
     * @param \WC_Product $product
     * @return string
     * @throws \Exception
     */
    private static function get_sku( $product ){
        $sku = $product->get_sku();

        if( ! $sku ) {
            if( get_option( 'fortnox_auto_generate_sku'  ) ){
                return WF_Sku_Generator::set_new_sku( $product );
            }
            else{
                throw new \Exception(
                    __( "Product ID {$product->get_id()} is missing SKU.", WF_Plugin::TEXTDOMAIN ),
                    "2000166"
                );
            }
        }
        return $sku                                                                               ;
    }
    /**
     * Return formatted product title
     * @param \WC_Product $product
     * @return string
     */
    private static function get_formatted_title( $product ){


        $product_title = str_replace( '"', "'", $product->get_title() );

        if( get_class( $product ) == 'WC_Product_Variation'  ) {
            $variation_title_arr = $product->get_variation_attributes();
            $variation_title = implode(" - ", $variation_title_arr );
            $product_title = self::truncate_over_200( $product_title . " - " . $variation_title );

        }

        return self::sanitize_description( $product_title );
    }

    /**
     * @param $product
     * @param $price_list
     * @param $sku
     */
	private static function set_fortnox_price( $product, $price_list, $sku ){
        $price = apply_filters( 'wf_price_payload_before_create_or_update', [
            'ArticleNumber' => $sku,
            'FromQuantity' => 0,
            'Price' => wc_get_price_excluding_tax( $product ),
            'PriceList' => self::fortnox_price_list()
        ], $product );

        try { #TODO Test price list update
            WF_Request::get("/prices/{$price_list}/{$sku}/0");

            try {
                WF_Request::put("/prices/{$price_list}/{$sku}/0", [ 'Price' => $price ] );
            } catch ( Exception $e ) {
                error_log( "Error when updating pricelist in Fortnox. SKU: " . $sku );
            }
        }
        catch( \Exception $error ) {
            if ( 2000430 === $error->getCode() ) {// Pricelist didn't exist already, create it
                try {
                    WF_Request::post( "/prices", [ 'Price' => $price ] );
                } catch ( Exception $e ) {
                    error_log( "Error when syncing pricelist to Fortnox. SKU: " . $sku );
                }
            }
        }

        if ( has_action( 'wetail_fortnox_after_product_price_update'  ) ) {
            wc_deprecated_function( 'The wetail_fortnox_after_product_price_update action', '', 'wf_order_after_product_price_update'  );
            do_action( 'wetail_fortnox_after_product_price_update', $product->get_id(), $sku, $price_list );
        }
        else{
            do_action( 'wf_order_after_product_price_update', $product->get_id(), $sku, $price_list );
        }
    }

    /** Updates stock on given product_id from Fortnox
     * @param $product_id
     * @throws \Exception
     */
	public static function update_stock_from_fortnox( $product_id ) {

		$product = wc_get_product( $product_id );

		if( $variations = self::has_variations( $product_id ) ) {
			if( ! get_option( 'fortnox_skip_product_variations'  ) ) {
				foreach( $variations as $variation ) {
					self::update_stock_from_fortnox( $variation->ID );
				}
			}
            $product->set_manage_stock( false );
            WC_Product_Variable::sync_stock_status( $product );
			if( ! get_option( 'fortnox_sync_master_product'  ) || get_option( 'fortnox_sync_master_product'  ) != 0 ) return;
		}

		$sku = $product->get_sku();

		try {
			$response = WF_Request::get( "/articles/{$sku}" );

			if( isset( $response->Article->DisposableQuantity ) ) {
                # Set stock status
				$product->set_stock_quantity( $response->Article->DisposableQuantity );
                $product->save();
            }
		}
		catch( \Exception $error ) {
			throw new \Exception( "Product ID {$product_id}: " . $error->getMessage() );
		}
	}

    /** Updates price on given product_id from Fortnox
     * @param $product_id
     * @throws \Exception
     */
	public static function update_price_from_fortnox( $product_id ) {
		$product = new WC_Product( $product_id );
		$sku = $product->get_sku();

		if( ! $sku ) {
			throw new \Exception(
                __( "Product ID {$product_id} is missing SKU.", WF_Plugin::TEXTDOMAIN ),
                "2000166"
			);
		}
		$fortnox_default_price_list = get_option( 'fortnox_default_price_list'  );
		$priceList = empty( $fortnox_default_price_list ) ? "A" : get_option( 'fortnox_default_price_list'  );

		$response = WF_Request::get( "/prices/{$priceList}/{$sku}" );

		if( ! empty( $response->ErrorInformation ) ) {
			throw new \Exception( "{$response->ErrorInformation->message} (Felkod: {$response->ErrorInformation->code})" );
		}

		# Update regular price
		update_post_meta( $product_id, '_regular_price', $response->Price->Price );

		if( ! get_post_meta( $product_id, '_sale_price', true ) ) {
			update_post_meta( $product_id, '_price', $response->Price->Price );
		}
	}

    /** Get product from Fortnox
     * @param $sku
     * @return mixed
     * @throws \Exception
     */
    public static function get( $sku ) {
		try {
			$response = WF_Request::get( "/article/{$sku}" );

			return $response->Article;
		}
		catch( \Exception $error ) {
			throw new \Exception( $error->getMessage() );
		}
	}

    /**
     * @param $sku
     * @return null|WC_Product
     */
    public static function get_product_by_sku( $sku ) {
		global $wpdb;
		$product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key = '_sku' AND meta_value = '%s'", $sku ) );
		if ( $product_id ) return new WC_Product( $product_id );
		return null;
    }

    /** Truncates string over 50 chars
     * @param $str
     * @return bool|string
     */
    public static function truncate_over_fifty( $str ){
        return mb_substr( $str , 0, 49 );
    }

    /** Truncates string over 200 chars
     * @param $str
     * @return bool|string
     */
    public static function truncate_over_200( $str ){
        return mb_substr( $str , 0, 199 );
    }

    /** Returns Fortnox-formatted product weight
     * @param $product
     * @return array
     */
    private static function get_product_weight( $product ){

        $weigth_arr = [
            'Weight' => null
        ];
        $weight = $product->get_weight();
        if ( ! empty( $weight ) )
            $weigth_arr['Weight'] = (int) $weight;

        if ( 'kg' == get_option( 'woocommerce_weight_unit'  ) )
            $weigth_arr['Weight'] = $weigth_arr['Weight'] * 1000;

        return $weigth_arr;

    }

    /** Returns Fortnox-formatted product dimensions
     * @param \WC_Product $product
     * @return array
     */
    private static function get_product_dimensions( $product ){
        $dimensions_arr = [
            'Width' => null,
            'Height' => null,
            'Depth' => null,
        ];

        $dimensions = $product->get_dimensions( false );
        if ( ! empty( $dimensions['width'] ) )
            $dimensions_arr['Width'] = (int) $dimensions['width'];
        if ( ! empty( $dimensions['height'] ) )
            $dimensions_arr['Height'] = (int) $dimensions['height'];
        if ( ! empty( $dimensions['length'] ) )
            $dimensions_arr['Depth'] = (int) $dimensions['length'];

        // Convert dimensions to mm
        if ( 'cm' == get_option( 'woocommerce_dimension_unit'  ) ) {
            $dimensions_arr['Width']   = $dimensions_arr['Width'] * 10;
            $dimensions_arr['Height']  = $dimensions_arr['Height'] * 10;
            $dimensions_arr['Depth']   = $dimensions_arr['Depth'] * 10;
        }
        if ( 'm' == get_option( 'woocommerce_dimension_unit'  ) ) {
            $dimensions_arr['Width']   = $dimensions_arr['Width'] * 1000;
            $dimensions_arr['Height']  = $dimensions_arr['Height'] * 1000;
            $dimensions_arr['Depth']   = $dimensions_arr['Depth'] * 1000;
        }

        return $dimensions_arr;
    }
}
