<?php
/**
 * Author: Vitaly Kukin
 * Date: 29.10.2018
 * Time: 10:42
 */

namespace dm;


class dmPricingConvert {
	
	private $post_id = null;
	
	private $type_product;
	
	private $rec;
	
	public function setRecommendedPrice( $res = false ) {
		
		$this->rec = $res;
	}
	
	public function getRecommendedPrice() {
		
		return $this->rec;
	}
	
	public function update( $post_id, $meta, $product ) {
		
		if ( ! is_numeric( $post_id ) )
			return false;
		
		$this->setType( $post_id );
		$this->post_id = $post_id;
		
		$code         = get_option( 'woocommerce_currency', 'USD' );
		$current_code = get_option( 'dm_currency_code', 'USD' );
		
		if( $code == 'USD' || $current_code == 'USD' )
			return false;
		
		$pricesOld = $this->getProduct( $post_id );
		
		if( empty( $pricesOld[ 'product_id' ] ) )
			return false;
		
		if( $this->getRecommendedPrice() )
			$pricesOld[ 'skuOriginaAttr' ] = maybe_unserialize( $meta[ 'skuAttr' ] );
		else
			$pricesOld[ 'skuOriginaAttr' ] = maybe_unserialize( $pricesOld[ 'skuOriginaAttr' ] );
		
		$updatePrice = $this->convert( $pricesOld, $current_code );
		
		$this->updatePriceProduct( $post_id, $updatePrice );
			
		if( ! $this->type_product && $pricesOld[ 'skuOriginaAttr' ] && count( $pricesOld[ 'skuOriginaAttr' ] ) > 0 ) {
				
			$this->updatePriceProductMeta( $post_id, $updatePrice );
			
			\WC_Product_Variable::sync( $post_id );
			wc_delete_product_transients( $post_id );
		} elseif( $this->type_product ) {
			
			$regular_price = $product[ 'price' ] == 0 ? $product[ 'salePrice' ] : $product[ 'price' ];
			$sale_price    = $product[ 'price' ] == 0 ? '' : $product[ 'salePrice' ];
			
			$args = [
				'_regular_price' => dm_convert_price( $regular_price, $code ),
				'_sale_price'    => dm_convert_price( $sale_price, $code ),
				'_price'         => $sale_price != '' ? dm_convert_price( $sale_price, $code ) : dm_convert_price( $regular_price, $code )
			];
			
			if( empty( $args[ '_sale_price' ] ) )
				$args[ '_sale_price' ] = '';
			
			foreach( $args as $key => $val )
				update_post_meta( $post_id, $key, $val );
		}
		
		return true;
	}
	
	public function updateAliDropship( $post_id, $meta, $product ) {
		
		global $wpdb;
		
		$discount  = $product[ 'price' ] > 0 ?
			round( ( ( $product[ 'price' ] - $product[ 'salePrice' ] ) / $product[ 'price' ] ) * 100 ) : 0;
		
		$wpdb->update( $wpdb->prefix . 'ads_products',
			[
				'price'        => $product[ 'price' ],
				'priceMax'     => $product[ 'priceMax' ],
				'salePrice'    => $product[ 'salePrice' ],
				'salePriceMax' => $product[ 'salePriceMax' ],
				'discount'     => $discount
			],
			[ 'post_id' => $post_id ],
			[ '%f', '%f', '%f', '%f', '%d' ],
			[ '%d' ]
		);
	
		$wpdb->update( $wpdb->prefix . 'ads_products_meta',
			[ 'skuAttr' => $meta[ 'skuAttr' ] ],
			[ 'post_id' => $post_id ],
			[ '%s' ],
			[ '%d' ]
		);
	}
	
	protected function convert( $pricesOld, $code ) {
		
		$updatePrice = [];
		
		$updatePrice[ 'skuAttr' ]   = [];
		$updatePrice[ 'price' ]     = 0;
		$updatePrice[ 'salePrice' ] = 0;
		
		if( $pricesOld[ 'skuOriginaAttr' ] && count( $pricesOld[ 'skuOriginaAttr' ] ) > 0 ) {
			
			$priceAll = [];
			$salePriceAll = [];
			
			foreach ( $pricesOld[ 'skuOriginaAttr' ] as $k => $v ) {
				
				$price     = dm_convert_price( $v[ 'price' ], $code );
				$salePrice = dm_convert_price( $v[ 'salePrice' ], $code );
				
				$priceAll[]     = dm_floatvalue( $price );
				$salePriceAll[] = dm_floatvalue( $salePrice );
				
				
				$updatePrice[ 'skuAttr' ][ $k ][ 'price' ]     = $price;
				$updatePrice[ 'skuAttr' ][ $k ][ 'salePrice' ] = $salePrice;
			}
			
			$updatePrice[ 'price' ]        = min($priceAll);
			$updatePrice[ 'priceMax' ]     = max($priceAll);
			$updatePrice[ 'salePrice' ]    = min($salePriceAll);
			$updatePrice[ 'salePriceMax' ] = max($salePriceAll);
			
		} else {
			
			$updatePrice[ 'price' ]        = dm_convert_price( $pricesOld[ 'origPrice' ], $code );
			$updatePrice[ 'priceMax' ]     = dm_convert_price( $pricesOld[ 'origPriceMax' ], $code );
			$updatePrice[ 'salePrice' ]    = dm_convert_price( $pricesOld[ 'origSalePrice' ], $code );
			$updatePrice[ 'salePriceMax' ] = dm_convert_price( $pricesOld[ 'origSalePriceMax' ], $code );
		}
		
		$args = $this->formatPrices( $updatePrice );
		
		$updatePrice[ 'price' ]        = $args[ 'price' ];
		$updatePrice[ 'priceMax' ]     = $args[ 'priceMax' ];
		$updatePrice[ 'salePrice' ]    = $args[ 'salePrice' ];
		$updatePrice[ 'salePriceMax' ] = $args[ 'salePriceMax' ];
		$updatePrice[ 'discount' ]     = $this->getDiscount( $updatePrice );
		
		if ( $updatePrice[ 'discount' ] <= 0 )
			$updatePrice[ 'price' ] = $updatePrice[ 'salePrice' ];
		
		return $updatePrice;
	}
	
	protected function setType( $post_id ) {
		
		$terms = wp_get_post_terms( $post_id, 'product_type' );
		
		if( $terms && ! is_wp_error( $terms ) ) {
			
			foreach( $terms as $term ) {
				
				if( $term->slug == 'simple' ) {
					$this->type_product = true;
					
					return true;
				}
			}
		} else {
			$this->type_product = false;
		}
		
		return true;
	}
	
	protected function getProduct( $post_id ) {
		
		global $wpdb;
		
		return $wpdb->get_row(
			$wpdb->prepare(
				"SELECT p.*, am.*
                  FROM {$wpdb->posts} p INNER JOIN {$wpdb->prefix}adsw_ali_meta am ON am.post_id = p.ID
                  WHERE p.ID = %d", $post_id
			),
			ARRAY_A
		);
	}
	
	protected function getDiscount( $updatePrice ) {
		
		$price     = floatval( $updatePrice[ 'price' ] );
		$salePrice = floatval( $updatePrice[ 'salePrice' ] );
		$discount  = $price > 0 ? round( ( ( $price - $salePrice ) / $price ) * 100 ) : 0;
		
		return $discount;
	}
	
	protected function formatPrices( array $prices = [] ) {
		
		$args = [
			'price'         => 0,
			'priceMax'      => 0,
			'salePrice'     => 0,
			'salePriceMax'  => 0
		];
		
		$price        = dm_floatvalue( $prices[ 'price' ] );
		$priceMax     = dm_floatvalue( $prices[ 'priceMax' ] );
		$salePrice    = dm_floatvalue( $prices[ 'salePrice' ] );
		$salePriceMax = dm_floatvalue( $prices[ 'salePriceMax' ] );
		
		if( $salePrice == $salePriceMax )
			$salePriceMax = 0;
		
		if( $salePriceMax == 0 ) {
			$priceMax = 0;
			
			if( $price <= $salePrice )
				$price = 0;
		}
		
		if( $price == $priceMax )
			$priceMax = 0;
		
		$args[ 'price' ]        = $price;
		$args[ 'priceMax' ]     = $priceMax;
		$args[ 'salePrice' ]    = $salePrice;
		$args[ 'salePriceMax' ] = $salePriceMax;
		
		return $args;
	}
	
	/**
	 * For Woo
	 * @param $post_id
	 * @param $updatePrice
	 *
	 * @return bool
	 */
	protected function updatePriceProduct( $post_id, $updatePrice ) {
		
		delete_post_meta( $post_id, '_regular_price' );
		delete_post_meta( $post_id, '_price' );
		delete_post_meta( $post_id, '_sale_price' );
		
		$price =  $updatePrice[ 'price' ];
		$salePrice = $updatePrice[ 'salePrice' ];
		
		if( empty( $price ) ) {
			$price     = $salePrice;
			$salePrice = '';
		} elseif( empty( $salePrice ) ) {
			$salePrice = '';
		}
		
		if( empty( $price ) && empty( $salePrice ) ) {
			$price     = 0;
			$salePrice = '';
		}
		
		$newPrice = [
			'_regular_price' => $price,
			'_sale_price'    => empty( $salePrice ) ? '' : $salePrice,
			'_price'     	 => ! empty( $salePrice ) ? $salePrice : $price
		];
		
		foreach( $newPrice as $k => $v )
			update_post_meta( $post_id, $k, $v);
		
		return true;
	}
	
	public function updatePriceProductMeta( $post_id, $updatePrice ) {
		
		global $wpdb;
		
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT ID, meta_value as SKU FROM {$wpdb->posts} p
				INNER JOIN {$wpdb->postmeta} m ON p.ID = m.post_id
				WHERE post_parent = %d AND post_type = 'product_variation' AND meta_key = 'adswSKU'", $post_id
			)
		);
		
		if( ! $results )
			return true;
		
		foreach( $results as $result ) {
			
			if( isset( $updatePrice[ 'skuAttr' ][ $result->SKU ] ) ) {
				
				$price     = $updatePrice[ 'skuAttr' ][ $result->SKU ][ 'price' ];
				$salePrice = $updatePrice[ 'skuAttr' ][ $result->SKU ][ 'salePrice' ];
				
				if( empty( $price ) ) {
					$price     = $salePrice;
					$salePrice = '';
				} elseif( empty($salePrice) ) {
					$salePrice = '';
				}
				
				if( empty($price) && empty($salePrice) ){
					$price = 0;
					$salePrice = '';
				}
				
				$newPrice = [
					'_regular_price' => $price,
					'_sale_price'    => $salePrice,
					'_price'     	 => ( ! empty( $salePrice ) ) ? $salePrice : $price
				];
				
				foreach( $newPrice as $k => $v )
					update_post_meta( $result->ID, $k, $v);
			}
		}
		
		return true;
	}
}