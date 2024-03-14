<?php
/**
 * Author: Vitaly Kukin
 * Date: 13.02.2018
 * Time: 8:59
 */

namespace dm;


class dmImport {
	
	public $message = '';
	
	private $post_id = false;
	private $publish = false;
	
	private $removeAttrib = false;
	
	private $content;
	
	private $product;
	private $meta;
	private $alimeta;
	private $post;
	private $attr;
	
	private $cats = [];
	
	/**
	 * Parameter marked to set images for attribute
	 *
	 * @var bool
	 */
	private $attrimg = false;
	
	private $recommendedPrice = false;
	
	private $var_images = [];
	
	private $attribute = [];
	
	private $images = [
		'gallery'    => [],
		'variations' => []
	];
	
	public function __construct( $content ) {
		
		$this->content = $content;
	}
	
	public function setPublish( $publish ) {
		
		$this->publish = (bool) $publish;
	}
	
	public function setRecommendedPrice( $var ) {
		
		$this->recommendedPrice = (bool) $var;
	}
	
	public function getRecommendedPrice() {
		
		return $this->recommendedPrice;
	}
	
	public function setAttributes( $attrib ) {
		
		$this->removeAttrib = (bool) $attrib;
	}
	
	public function getCats() {
		
		return $this->cats;
	}
	
	/**
	 * @return mixed
	 */
	public function getMeta() {
		return $this->meta;
	}
	
	/**
	 * @return mixed
	 */
	public function getAlimeta() {
		return $this->alimeta;
	}
	
	/**
	 * @return mixed
	 */
	public function getPost() {
		return $this->post;
	}
	
	/**
	 * @return mixed
	 */
	public function getAttr() {
		return $this->attr;
	}
	
	/**
	 * @return mixed
	 */
	public function getProduct() {
		return $this->product;
	}
	
	public function prepare() {
		
		$data = $this->content;
		
		$id = dm_integer( $data->productId );
		
		if( $id == 0 )
			$id = esc_attr( $data->productId );
		
		if( $this->checkExists( $id ) )
			return [
				'error' => __( 'Product already imported', 'dm' ),
			];
		
		$this->post[ 'post_title' ]   = $data->product_title;
		$this->post[ 'post_content' ] = $this->clearHtml( trim( htmlspecialchars_decode( $data->product_content ) ) );
		
		$prices = @unserialize( $data->prices );

		if( $prices && $this->getRecommendedPrice() ) {
			$this->product[ 'price' ]        = $prices['price'];
			$this->product[ 'priceMax' ]     = $prices['priceMax'];
			$this->product[ 'salePrice' ]    = $prices['salePrice'];
			$this->product[ 'salePriceMax' ] = $prices['salePriceMax'];
			
		} else {
			$this->product[ 'price' ]        = $data->origPrice;
			$this->product[ 'priceMax' ]     = $data->origPriceMax;
			$this->product[ 'salePrice' ]    = $data->origSalePrice;
			$this->product[ 'salePriceMax' ] = $data->origSalePriceMax;
		}
		
		$this->product[ 'currency' ]        = 'USD';
		$this->product[ 'packageType' ]     = $data->packageType;
		$this->product[ 'imageUrl' ]        = esc_url( $data->imageUrl );
		$this->product[ 'evaluateScore' ]   = 0;
		$this->product[ 'quantity' ]        = intval( $data->quantity );
		$this->product[ 'promotionVolume' ] = intval( $data->purchaseVolume );
		
		$this->meta[ 'gallery' ]            = $data->gallery;
		$this->meta[ 'pack' ]               = $data->pack;
		$this->meta[ 'lotNum' ]             = $data->lotNum;
		$this->meta[ 'sku' ]                = $data->sku;
		$this->meta[ 'skuAttr' ]            = $this->getRecommendedPrice() ? $data->skuAttr : $data->skuOriginaAttr;
		
		$this->alimeta[ 'product_id' ]       = $id;
		//$this->alimeta[ 'services' ]         = 'aliexpress';
		$this->alimeta[ 'productUrl' ]       = esc_url( $data->productUrl );
		$this->alimeta[ 'feedbackUrl' ]      = esc_url( $data->feedbackUrl );
		$this->alimeta[ 'storeUrl' ]         = esc_url( $data->storeUrl );
		$this->alimeta[ 'storeName' ]        = esc_attr( $data->storeName );
		$this->alimeta[ 'storeRate' ]        = esc_attr( $data->storeRate );
		$this->alimeta[ 'skuOriginaAttr' ]   = $data->skuOriginaAttr;
		$this->alimeta[ 'skuOriginal' ]      = $data->skuOriginal;
		$this->alimeta[ 'origPrice' ]        = $data->origPrice;
		$this->alimeta[ 'origPriceMax' ]     = $data->origPriceMax;
		$this->alimeta[ 'origSalePrice' ]    = $data->origSalePrice;
		$this->alimeta[ 'origSalePriceMax' ] = $data->origSalePriceMax;
		
		$this->cats = [
			'cat_1' => $data->cat_1,
			'cat_2' => $data->cat_2,
			'cat_3' => $data->cat_3,
		];
		
		$this->attr = @unserialize( $data->attributes );
		
		$this->images[ 'gallery' ] = @unserialize( $this->meta[ 'gallery' ] );
		
		return true;
	}
	
	public function publish() {
		
		$post_id = $this->post();
		
		if( is_array( $post_id ) ) return $post_id;
		
		$this->insertProduct( $post_id );
		$this->insertMeta( $post_id );
		$this->insertMetaAli( $post_id );
		
		$this->variablesImages();
		
		if( ! $this->removeAttrib )
			$this->customQuery( $this->attr, 'ads_attributes', $post_id );
		
		return [
			'id'      => $this->alimeta[ 'product_id' ],
			'post_id' => $post_id,
			'images'  => $this->prepareImages( $post_id, $this->images, $this->var_images )
		];
	}

	public function publishWoo() {

		$post_id = $this->post();

		if( is_array( $post_id ) ) return $post_id;
		
		// обрабатываем данные по упаковке
		$package = $this->preparePackaging();
		
		// подготовка и создание записей в таблице Meta для товара
		$this->preparePostMeta( $package, $post_id );

		// создаём запись в таблице adsw_ali_meta
		$this->saveAliMeta( $post_id );

		$variation = @unserialize( $this->alimeta[ 'skuOriginaAttr' ] );
		
		if( ! empty( $variation ) ) {
			
			$this->saveVariations( $package, $post_id );

			\WC_Product_Variable::sync( $post_id );
			wc_delete_product_transients( $post_id );

		} else {
			$this->setProductType( $post_id, 'simple', 'product_type' );
		}

		// проходим по аттрибутам обычным
		if( ! $this->removeAttrib )
			$this->productAttributes( $post_id );

		return [
			'id'      => $this->alimeta[ 'product_id' ],
			'post_id' => $post_id,
			'images'  => $this->prepareImages( $post_id, $this->images, $this->var_images )
		];
	}
	
	private function clearHtml( $str ) {
		
		$str = preg_replace( "/#\s(id|class)=\"[^\"]+\"#/", "", $str );
		
		return force_balance_tags( $str );
	}
	
	/**
	 * 1. Import to Import List then return $ID from wp_posts
	 * @return array|int|\WP_Error
	 */
	private function post() {
		
		global $user_ID;
		
		$status = 'draft';
		if( $this->publish )
			$status = 'publish';
		elseif( $this->checkPlugins() )
			$status = 'importlist';
		
		$args = [
			'comment_status' => 'open',
			'ping_status' 	 => 'closed',
			'post_author' 	 => $user_ID,
			'post_status' 	 => $status,
			'post_title' 	 => wp_strip_all_tags( $this->post[ 'post_title' ] ),
			'post_content'	 => $this->post[ 'post_content' ],
			'post_type' 	 => 'product',
		];
		
		$response = wp_insert_post( $args, true );
		
		if( ! is_wp_error( $response ) ) {
			wp_update_post( [
				'ID'        => $response,
				'post_name' => wp_unique_post_slug(
					sanitize_title( $this->post[ 'post_title' ] ),
					$response,
					$args[ 'post_status' ],
					$args[ 'post_type' ],
					0
				)
			] );
		}
		
		return is_wp_error( $response ) ? [ 'error' => $response->get_error_message() ] : $response;
	}
	
	/**
	 * 2. Insert Product data to ads_products
	 * @param $post_id
	 * @return bool
	 */
	private function insertProduct( $post_id ) {
		
		global $wpdb;
		
		if( ! is_array( $this->product ) )
			return false;
		
		$this->product[ 'post_id' ] = $post_id;
		
		return $wpdb->insert( $wpdb->ads_products, $this->getProduct() );
	}
	
	/**
	 * 3. Insert Meta data of Product to ads_products_meta
	 * @param $post_id
	 * @return bool
	 */
	private function insertMeta( $post_id ) {
		
		global $wpdb;
		
		if( ! is_array( $this->meta ) )
			return false;
		
		$this->meta[ 'post_id' ] = $post_id;
		
		$meta = $this->meta;
		
		$meta[ 'gallery' ] = serialize( [] );
		
		return $wpdb->insert( $wpdb->ads_products_meta, $meta );
	}
	
	/**
	 * 4. Insert Ali Meta data of Product to ads_products_meta
	 * @param $post_id
	 * @return bool
	 */
	private function insertMetaAli( $post_id ) {
		
		if( ! is_array( $this->meta ) )
			return false;
		
		$this->alimeta[ 'post_id' ] = $post_id;
		
		global $wpdb;
		
		return $wpdb->insert( $wpdb->prefix . 'ads_ali_meta', $this->getAlimeta() );
	}

	/**
	 * WOO 1. Prepare Packaging
	 *
	 * @return array
	 */
	protected function preparePackaging() {

		$product_id = $this->alimeta[ 'product_id' ];
		$pack       = @unserialize( $this->meta[ 'pack' ] );

		$foo = [
			'_weight' => '',
			'_length' => '',
			'_width'  => '',
			'_height' => '',
			'_sku'    => $product_id,
		];

		$foo[ '_sku' ] = $product_id;

		$weight = isset( $pack[ 'weight' ] ) ? $pack[ 'weight' ] : 0;
		$size   = isset( $pack[ 'size' ] ) ? $pack[ 'size' ] : 0;

		$default_w = [
			'kg'  => 1,
			'g'   => 1000,
			'lbs' => 0.453592,
			'oz'  => 0.0283495
		];

		$weight_woo = get_option( 'woocommerce_weight_unit', 'kg' );
		if( isset( $default_w[ $weight_woo ] ) )
			$weight_woo = 'kg';

		$default_d = [
			'cm' => 1,
			'm'  => 0.01,
			'mm' => 0.001,
			'in' => 0.393701,
			'yd' => 0.0109361
		];

		$dimension_woo = get_option( 'woocommerce_dimension_unit', 'cm' );
		if( isset( $default_d[ $dimension_woo ] ) )
			$dimension_woo = 'cm';

		if( $weight != 0 ) {

			$_w = explode( ' ', $weight );
			$_w = dm_floatvalue( $_w[ 0 ] );
			$foo[ '_weight' ] = round( $_w * $default_w[ $weight_woo ], 2 );
		}

		if( $size != 0 ) {

			$size = explode( ' (', $size );
			$size = trim( $size[0] );
			$size = explode( ' x ', $size );

			$foo['_length'] = round( dm_integer( $size[ 0 ] ) * $default_d[ $dimension_woo ], 2 );
			$foo['_width']  = round( dm_integer( $size[ 1 ] ) * $default_d[ $dimension_woo ], 2 );
			$foo['_height'] = round( dm_integer( $size[ 2 ] ) * $default_d[ $dimension_woo ], 2 );
		}

		return $foo;
	}
	
	/**
	 * WOO 2. Prepare Post meta values
	 *
	 * @param array $package
	 * @param integer $post_id
	 *
	 * @return array
	 */
	protected function preparePostMeta( $package, $post_id ) {
		
		$regular_price = $this->product[ 'price' ] == 0 ? $this->product[ 'salePrice' ] : $this->product[ 'price' ];
		$sale_price    = $this->product[ 'price' ] == 0 ? '' : $this->product[ 'salePrice' ];

		$meta = [
            '_visibility'    		          => 'visible',
            'total_sales'    		          => 0,
            '_downloadable'  		          => 'no',
            '_virtual'       		          => 'no',
            '_purchase_note' 		          => '',
            '_featured'      		          => 'no',
            '_weight'        		          => $package[ '_weight' ],
            '_length'        		          => $package[ '_length' ],
            '_width'         		          => $package[ '_width' ],
            '_height'        		          => $package[ '_height' ],
            '_sku'           		          => $package[ '_sku' ],
            '_sold_individually'              => '',
            '_manage_stock'                   => 'yes',  //отнимать сток при покупке
            '_backorders'                     => 'no',  //не продавать если сток 0
            '_stock'                          => $this->product[ 'quantity' ], //общее количество товара с учётом всех вариаций
            '_stock_status'                   => 'instock',
            '_upsell_ids'                     => serialize( [] ),
            '_crosssell_ids'                  => serialize( [] ),
            '_product_version'                => '3.6.1',
            '_wc_review_count'		          => 0,
            '_wc_average_rating'              => 0,
            '_wc_rating_count'		          => serialize( [] ),
            '_regular_price'                  => $regular_price, // если есть вариации то пусто
            '_sale_price'                     => $sale_price, // если есть вариации то пусто
            '_price'                    	  => $sale_price != '' ? $sale_price : $regular_price, // если есть вариации то пусто
            '_sale_price_dates_from'          => '',
            '_sale_price_dates_to'            => '',
		];
		
		$prepare = [];
		
		foreach( $meta as $key => $val ) {
			$prepare[] = [
				'meta_key'   => $key,
				'meta_value' => $val,
			];
		}
		
		$this->customQuery( $prepare, 'postmeta', $post_id );
		
		return $meta;
	}
	
	/**
	 * Create Variations for product
	 *
	 * @param $package
	 * @param $post_id
	 *
	 * @return array|bool
	 */
	protected function saveVariations( $package, $post_id ) {
		
		$sku_arr  = @unserialize( $this->meta[ 'sku' ] );
		$attr_arr = $this->getRecommendedPrice() ? @unserialize( $this->meta[ 'skuAttr' ] ) :
			@unserialize( $this->alimeta[ 'skuOriginaAttr' ] );
		
		$args     = [];
		$vars     = [];
		$inserted = 0;
		
		foreach( $attr_arr as $key => $val ) {
			
			$koo      = explode( ';', $key );
			$continue = false;
			foreach( $koo as $k ) {
				if( ! isset( $sku_arr[ $k ] ) )
					$continue = true;
			}
			
			if( $continue ) continue;
			
			$price = dm_floatvalue( $val[ 'price' ] );
			
			$args[ $key ] = [
				'_stock_status'          => $val[ 'quantity' ] > 0 ? 'instock' : 'outofstock',
				'_regular_price'         => $price == 0 ? dm_floatvalue( $val[ 'salePrice' ] ) : $price,
				'_sale_price'            => dm_floatvalue( $val[ 'salePrice' ] ),
				'_sale_price_dates_from' => '',
				'_sale_price_dates_to'   => '',
				'_price'                 => ! empty( $val[ 'salePrice' ] ) ? dm_floatvalue( $val[ 'salePrice' ] ) : dm_floatvalue( $val[ 'price' ] ),
				'_sku'                   => '',
				'_thumbnail_id'          => '',
				'_virtual'               => 'no',
				'_weight'                => $package[ '_weight' ],
				'_length'                => $package[ '_length' ],
				'_width'                 => $package[ '_width' ],
				'_height'                => $package[ '_height' ],
				'_backorders'            => 'no',
				'_download_limit'        => '',
				'_download_expiry'       => '',
				'_downloadable_files'    => '',
				'_variation_description' => '',
				'_downloadable'          => '',
				'_manage_stock'          => 'yes',
				'_stock'                 => intval( $val[ 'quantity' ] ),
				'adswSKU'                => $key
			];
			
			$vars[ $key ] = $this->post[ 'post_title' ];
			
			$inserted++;
			
			foreach( $koo as $k ) {
				
				if( isset( $sku_arr[ $k ] ) ) {
					
					$taxonomy = $this->addAttribute( $sku_arr[ $k ][ 'prop_title' ] );
					$term     = $this->updateTerm( $sku_arr[ $k ][ 'title' ], $taxonomy );
					$thumb    = $sku_arr[ $k ][ 'img' ];
					
					if( dm_is_url( $thumb ) ) {
						
						if( ! $this->attrimg ) {
							
							$this->attrimg = true;
							
							update_post_meta( $post_id, 'adsw-attribute-image', $post_id . '-' . str_replace ( 'pa_', '', $taxonomy ) );
						}
						
						$this->var_images[ $key ] = $thumb;
					}
					
					if( ! isset( $term[ 'error' ] ) )
						wp_set_post_terms( $post_id, [ $term ], $taxonomy, true );
					else
						error_log( print_r( $term, true ) );
					
					$term = get_term_by( 'id', $term, $taxonomy );
					
					$slug = sanitize_title( $sku_arr[ $k ][ 'title' ] );
					if( $term && ! is_wp_error( $term ) )
						$slug = $term->slug;
					
					$args[ $key ]['attribute_' . $taxonomy] = $slug;
					
					if( ! in_array( $taxonomy, $this->attribute ) ) {
						
						$this->attribute[] = $taxonomy;
					}
				}
			}
		}
		
		$this->setVariables( $vars, $args, $post_id );
		
		$this->variationAttributes( $post_id, $this->attribute );
		
		if( $inserted > 0 )
			$this->setProductType( $post_id, 'variable', 'product_type' );
		else
			$this->setProductType( $post_id, 'simple', 'product_type' );
		
		return $args;
	}
	
	/**
	 * WOO 4. Save data from aliexpress into adsw_ali_meta
	 *
	 * @param $id
	 */
	protected function saveAliMeta( $id ) {
		
		global $wpdb;
		
		$post_id = $wpdb->get_var(
			$wpdb->prepare( "SELECT post_id FROM {$wpdb->prefix}adsw_ali_meta WHERE post_id = %d", $id )
		);
		
		if( ! empty( $post_id ) )
			$wpdb->update( $wpdb->prefix . 'adsw_ali_meta', [
				'product_id' 	   => $this->alimeta[ 'product_id' ],
				'productUrl' 	   => $this->alimeta[ 'productUrl' ],
				'feedbackUrl' 	   => $this->alimeta[ 'feedbackUrl' ],
				'storeUrl' 		   => $this->alimeta[ 'storeUrl' ],
				'storeName' 	   => $this->alimeta[ 'storeName' ],
				'storeRate' 	   => isset( $this->alimeta[ 'storeRate' ] ) ? $this->alimeta[ 'storeRate' ] : '',
				'origPrice'		   => $this->alimeta[ 'origPrice' ],
				'origPriceMax'	   => $this->alimeta[ 'origPriceMax' ],
				'origSalePrice'	   => $this->alimeta[ 'origSalePrice' ],
				'origSalePriceMax' => $this->alimeta[ 'origSalePriceMax' ],
				'skuOriginaAttr'   => $this->alimeta[ 'skuOriginaAttr' ],
				'skuOriginal'      => $this->alimeta[ 'skuOriginal' ],
				'currencyCode' 	   => 'USD',
			], [
				'post_id' 		   => $id,
			] );
		else
			$wpdb->insert(
				$wpdb->prefix . 'adsw_ali_meta', [
				'post_id' 		   => $id,
				'product_id' 	   => $this->alimeta[ 'product_id' ],
				'productUrl' 	   => $this->alimeta[ 'productUrl' ],
				'feedbackUrl' 	   => $this->alimeta[ 'feedbackUrl' ],
				'storeUrl' 		   => $this->alimeta[ 'storeUrl' ],
				'storeName' 	   => $this->alimeta[ 'storeName' ],
				'storeRate' 	   => isset( $this->alimeta[ 'storeRate' ] ) ? $this->alimeta[ 'storeRate' ] : '',
				'origPrice'		   => $this->alimeta[ 'origPrice' ],
				'origPriceMax'	   => $this->alimeta[ 'origPriceMax' ],
				'origSalePrice'	   => $this->alimeta[ 'origSalePrice' ],
				'origSalePriceMax' => $this->alimeta[ 'origSalePriceMax' ],
				'skuOriginaAttr'   => $this->alimeta[ 'skuOriginaAttr' ],
				'skuOriginal'      => $this->alimeta[ 'skuOriginal' ],
				'currencyCode' 	   => 'USD',
			] );
	}
	
	private function setProductType( $post_id, $slug, $taxonomy ){
		
		$term = get_term_by( 'slug', $slug, $taxonomy );
		
		if( $term )
			wp_set_post_terms( $post_id, $slug, $taxonomy, true );
	}
	
	protected function addAttribute( $attribute_name ) {
		
		$permalinks = get_option( 'woocommerce_permalinks' );
		
		$label = __( 'Item', 'dm' );
		$name  = wc_attribute_taxonomy_name( dm_prepare_var_slug( $attribute_name ) );
		
		$has = $this->hasAttribute( $attribute_name );
		
		if( ! $has ) {
			
			$taxonomy_data = [
				'hierarchical'          => true,
				'update_count_callback' => '_update_post_term_count',
				'labels'                => [
					'name'              => sprintf( _x( 'Product %s', 'Product Attribute', 'woocommerce' ), $attribute_name ),
					'singular_name'     => $attribute_name,
					'search_items'      => sprintf( __( 'Search %s', 'woocommerce' ), $label ),
					'all_items'         => sprintf( __( 'All %s', 'woocommerce' ), $label ),
					'parent_item'       => sprintf( __( 'Parent %s', 'woocommerce' ), $label ),
					'parent_item_colon' => sprintf( __( 'Parent %s:', 'woocommerce' ), $label ),
					'edit_item'         => sprintf( __( 'Edit %s', 'woocommerce' ), $label ),
					'update_item'       => sprintf( __( 'Update %s', 'woocommerce' ), $label ),
					'add_new_item'      => sprintf( __( 'Add New %s', 'woocommerce' ), $label ),
					'new_item_name'     => sprintf( __( 'New %s', 'woocommerce' ), $label )
				],
				'show_ui'            => true,
				'show_in_quick_edit' => false,
				'show_in_menu'       => false,
				'show_in_nav_menus'  => false,
				'meta_box_cb'        => false,
				'query_var'          => true,
				'sort'               => false,
				'public'             => true,
				'rewrite'            => [
					'slug'         => empty( $permalinks['attribute_base'] ) ? '' :
						trailingslashit( $permalinks['attribute_base'] ) . dm_prepare_var_slug( $attribute_name ),
					'with_front'   => false,
					'hierarchical' => true
				],
				'capabilities'       => [
					'manage_terms' => 'manage_product_terms',
					'edit_terms'   => 'edit_product_terms',
					'delete_terms' => 'delete_product_terms',
					'assign_terms' => 'assign_product_terms',
				]
			];
			
			register_taxonomy( $name, 'product', $taxonomy_data );
			
			$this->insertWooTax( [
				'attribute_label'   => wc_clean( stripslashes( $attribute_name ) ),
				'attribute_name'    => dm_prepare_var_slug( $attribute_name ),
				'attribute_type'    => 'select',
				'attribute_orderby' => 'menu_order',
				'attribute_public'  => 0
			] );
			
			return $name;
		}
		
		return wc_attribute_taxonomy_name( $has );
	}
	
	private function hasAttribute( $name ) {
		
		global $wpdb;
		
		$row = $wpdb->get_var(
			$wpdb->prepare( "SELECT attribute_name FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_label = %s", $name
			)
		);
		
		return empty( $row ) ? false : $row;
	}
	
	private function insertWooTax( $attribute ) {
		
		global $wpdb;
		
		$wpdb->insert( $wpdb->prefix . 'woocommerce_attribute_taxonomies', $attribute );
		
		do_action( 'woocommerce_attribute_added', $wpdb->insert_id, $attribute );
		
		flush_rewrite_rules();
		delete_transient( 'wc_attribute_taxonomies' );
	}
	
	private function updateTerm( $value, $taxonomy ) {
		
		$term = get_term_by( 'name', $value, $taxonomy );
		
		if( $term )
			return $term->term_id;
		
		$result = wp_insert_term( $value, $taxonomy );
		
		if ( is_wp_error( $result ) )
			return [ 'error' => $result->get_error_message() ];
		
		return $result[ 'term_id' ];
	}
	
	private function setVariables( $vars, $args, $post_id ) {
		
		$i = 0;
		
		foreach( $vars as $post => $title ) {
			
			$i++;
			
			$id = wp_insert_post( [
				'post_title'    => 'Variation #' . $i . ' of ' . esc_attr(strip_tags($title)),
				'post_name'     => 'product-' . $post_id . '-variation-' . $i,
				'post_status'   => 'publish',
				'post_parent'   => $post_id,
				'post_type'     => 'product_variation',
				'guid'          =>  home_url() . '/?product_variation=product-' . $post_id . '-variation-' . $i
			] );
			
			$prepare = [];
			
			foreach( $args[ $post ] as $key => $val ) {
				$prepare[] = [
					'meta_key'   => $key,
					'meta_value' => $val,
				];
			}
			
			$this->customQuery( $prepare, 'postmeta', $id );
            $this->setProductMetaLookup( $args[ $post ], $id );
		}
		
		\WC_Product_Variable::sync( $post_id );
	}
    private function setProductMetaLookup( $args, $post_id = false ){

        global $woocommerce;

        if( version_compare( $woocommerce->version, '3.6.0', "<" ) )
            return false;

        if( ! $post_id )
            return false;


        if ( ! $args || empty( $args ) ) {
            return false;
        }

        global $wpdb;

        $table = $wpdb->prefix . 'wc_product_meta_lookup';

        $update_data = [
            'product_id'     => $post_id,
            'sku'            => $args[ '_sku' ],
            'virtual'        => 'yes' === $args[ '_virtual' ] ? 1 : 0,
            'downloadable'   => 'yes' === $args[ '_downloadable' ] ? 1 : 0,
            'min_price'      => $args[ '_sale_price' ] != '' ? $args[ '_sale_price' ] : $args[ '_regular_price' ],
            'max_price'      => $args[ '_sale_price' ] != '' ? $args[ '_sale_price' ] : $args[ '_regular_price' ],
            'onsale'         => $args[ '_sale_price' ] != '' ? 1 : 0,
            'stock_quantity' => wc_stock_amount( $args[ '_stock' ] ),
            'stock_status'   => $args[ '_stock_status' ],
            'rating_count'   => 0,
            'average_rating' => 0,
            'total_sales'    => isset( $args[ 'total_sales' ] ) ? $args[ 'total_sales' ] : 0,
        ];

        $wpdb->replace(
            $table,
            $update_data
        );
        wp_cache_set( 'lookup_table', $update_data, 'object_' . $post_id );;

        return true;
    }
	protected function variationAttributes( $post_id, $attributes, $visible = 0 ) {
		
		$i = 0;
		
		$product_attributes = [];
		
		foreach ( $attributes as $attribute ) {
			
			$product_attributes[ $attribute ] = [
				'name'         => $attribute, // set attribute name
				'value'        => '', // set attribute value
				'position'     => $i,
				'is_visible'   => $visible,
				'is_variation' => 1,
				'is_taxonomy'  => 1
			];
			
			$i++;
		}
		
		update_post_meta( $post_id, '_product_attributes', $product_attributes );
	}
	
	/**
	 * Simple product attributes
	 *
	 * @param integer $post_id
	 */
	protected function productAttributes( $post_id ) {

		if( $this->attr && is_array( $this->attr ) ) {
			
			$product_attributes = [];
			
			$attr = get_post_meta( $post_id, '_product_attributes', true );
			
			if( $attr ) $product_attributes = $attr;
			
			$i = count( $product_attributes );
			
			foreach( $this->attr as $key => $val ) {
				
				$slug = sanitize_title( $val[ 'attr_name' ] );
				$product_attributes[ $slug ] = [
					'name'         => str_replace( ':', '', $val[ 'attr_name' ] ), // set attribute name
					'value'        => $val[ 'attr_value' ], // set attribute value
					'position'     => $i,
					'is_visible'   => 1,
					'is_variation' => 0,
					'is_taxonomy'  => 0
				];
				
				$i++;
			}
			
			update_post_meta( $post_id, '_product_attributes', $product_attributes );
		}
	}
	
	protected function prepareImages( $post_id, $images, $var_images ) {
		
		$foo = [];
		
		if( count( $images[ 'gallery' ] ) ) foreach ( $images[ 'gallery' ] as $k => $image ) {
			
			$type = $k == 0 ? 'thumb' : 'gallery';
			$foo[] = [
				'url'     => $image,
				'type'    => $type,
				'id'      => 0,
				'post_id' => $post_id
			];
		}
		
		if( count( $var_images ) ) foreach ( $var_images as $k => $image ) {
			
			$foo[] = [
				'url'     => $image,
				'type'    => 'variation',
				'id'      => 0,
				'post_id' => $k
			];
		}
		
		return $foo;
	}
	
	/**
	 * Multiple insert into vertical table
	 * @param $data
	 * @param $table
	 * @param string $post_id
	 *
	 * @return bool|false|int
	 */
	private function customQuery( $data, $table, $post_id = '' ) {
		
		if( ! $data || empty( $data ) ) return false;
		
		global $wpdb;
		
		$table = $wpdb->prefix . $table;
		
		$head   = true;
		$keys   = [];
		$values = [];
		
		foreach( $data as $key => $val ) {
			
			$foo = [];
			
			if( is_array( $val ) ) {
				foreach( $val as $k => $v ) {
					
					if( $head )
						$keys[] = $k;
					
					$foo[] = $v;
				}
			} else {
				if( $head )
					$keys[] = $key;
				
				$foo[] = $val;
			}
			
			if( $post_id != '' && $head )
				$keys[] = 'post_id';
			
			if( $post_id != '' )
				$foo[] = $post_id;
			
			$head = false;
			
			$values[] = "'". implode("', '", $foo) . "'";
		}
		
		$query = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES (" . implode('), (', $values) . ");";
		
		return $wpdb->query( $query );
	}
	
	public function checkExists( $id ) {
		
		global $wpdb;
		
		$table = ( DM_PLUGIN == 'woocommerce' ) ? 'adsw_ali_meta' : 'ads_ali_meta';
		
		$var = $wpdb->get_var(
			$wpdb->prepare( "SELECT `post_id` FROM {$wpdb->prefix}{$table} WHERE `product_id` = %s", $id )
		);
		
		return ! empty( $var );
	}
	
	protected function checkPlugins() {
		
		$plugins = (array) get_option( 'active_plugins', [] );
		
		if( in_array( 'alids/alids.php', $plugins ) )
			return 'alidropship';
		if( in_array( 'alidswoo/alidswoo.php', $plugins ) )
			return 'alidswoo';
		
		return false;
	}
	
	protected function variablesImages() {
		
		$sku_arr  = @unserialize( $this->meta[ 'sku' ] );
		$attr_arr = $this->getRecommendedPrice() ? @unserialize( $this->meta[ 'skuAttr' ] ) :
			@unserialize( $this->alimeta[ 'skuOriginaAttr' ] );
		
		if( $attr_arr ) foreach( $attr_arr as $key => $val ) {
			
			$koo      = explode( ';', $key );
			$continue = false;
			foreach( $koo as $k ) {
				if( ! isset( $sku_arr[ $k ] ) )
					$continue = true;
			}
			
			if( $continue ) continue;
			
			foreach( $koo as $k ) {
				
				if( isset( $sku_arr[ $k ] ) ) {
					
					$thumb = $sku_arr[ $k ][ 'img' ];
					
					if( dm_is_url( $thumb ) ) {
						
						$this->var_images[ $key ] = $thumb;
					}
				}
			}
		}
	}
}