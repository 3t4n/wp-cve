<?php
/**
 * Author: Vitaly Kukin
 * Date: 01.02.2018
 * Time: 13:53
 */

namespace dm;


class dmApi {
	
	private $path = 'https://dropshipmeservice.com';
	
	public function parentNodes( $method = 'main' ) {

		$response = $this->callPath( [], "nodes/{$method}" );


		if( is_array( $response ) )
			return $response;
		
		return json_decode( $response, true );
	}
	
	public function productInfo( $id ) {
		
		$response = $this->callPath( [], "info/{$id}" );
		if( is_array( $response ) )
			return $response;

        if( dm_is_base64_encoded($response) )
            $response = base64_decode( $response, true );

        $result = json_decode( $response );

		$code     = get_option( 'dm_currency_code', 'USD' );
		
		$result->currency_format = dm_get_currency_symbol( $code );
		if( isset( $result->data->product_title ) ) {
			$result->data->product_title   = htmlspecialchars_decode( $result->data->product_title, ENT_QUOTES );
			$result->data->product_content = htmlspecialchars_decode( $result->data->product_content, ENT_QUOTES );
			$result->data->purchaseVolume = number_format($result->data->purchaseVolume, 0, '', ',');
			
			global $wpdb;
			
			$table  = ( DM_PLUGIN == 'woocommerce' ) ? 'adsw_ali_meta' : 'ads_ali_meta';
			$product_id = $wpdb->get_var( $wpdb->prepare(
				"SELECT product_id FROM {$wpdb->prefix}{$table} WHERE product_id = %s", $result->data->productId
			) );
			
			$result->data->already = ! empty( $product_id ) ?: false;
			
			$result->data->origPrices->origPrice        = dm_convert_price( $result->data->origPrices->origPrice, $code );
			$result->data->origPrices->origPriceMax     = dm_convert_price( $result->data->origPrices->origPriceMax, $code );
			$result->data->origPrices->origSalePrice    = dm_convert_price( $result->data->origPrices->origSalePrice, $code );
			$result->data->origPrices->origSalePriceMax = dm_convert_price( $result->data->origPrices->origSalePriceMax, $code );
			
			$result->data->prices->price        = dm_convert_price( $result->data->prices->price, $code );
			$result->data->prices->priceMax     = dm_convert_price( $result->data->prices->priceMax, $code );
			$result->data->prices->salePrice    = dm_convert_price( $result->data->prices->salePrice, $code );
			$result->data->prices->salePriceMax = dm_convert_price( $result->data->prices->salePriceMax, $code );
		}
		return $result;
	}
	
	public function getDeposit() {
		
		$response = $this->callPath( [], "deposit" );
		if( is_array( $response ) )
			return $response;
		
		$result   = json_decode( $response, true );
		
		return $result;
	}
	
	public function getRedirectLink( $id ) {
		
		$response = $this->callPath( [ 'product_id' => $id ], "link" );
		
		if( is_array( $response ) )
			return $response;
		
		$result = json_decode( $response, true );
		
		return $result;
	}
	
	public function productFull( $id ) {
		
		$response = $this->callPath( [], "product/{$id}" );
		
		if( is_array( $response ) )
			return $response;

        if( dm_is_base64_encoded( $response ) )
            $response = base64_decode( $response );

        return json_decode( $response );
	}
	
	public function productData( $id ) {
		
		$response = $this->call( [ 'product_id' => $id ], 'get_product' );

        if( dm_is_base64_encoded( $response ) )
            $response = base64_decode( $response );

		$result   = json_decode( $response, true  );
		
		return $result;
	}
	
	/**
	 * Get list of products searched by parameters
	 * @param $cat_id
	 * @param int $page
	 * @param array $args
	 * @param int $page_size
	 * @return array
	 */
	public function productsByCat( $cat_id, $page = 1, $args = [], $page_size = 40 ) {
		
		$defaults = [
			'keywords' 			=> '',
			'originalPriceFrom' => '',
			'originalPriceTo' 	=> '',
			'volumeFrom' 		=> '',
			'volumeTo' 			=> '',
			'sort'				=> '',
			'free'				=> '',
			'warehouse'			=> '',
			'to'    			=> 'US',
			'company'           => '',
            'supplier'          => ''
		];
		
		$args = dm_parse_args( $defaults, $args );
		
		$args[ 'free' ]       = ! empty( $args[ 'free' ] ) ? 'f' : '';
		$args[ 'company' ]    = $args[ 'company' ] == '9999' ? '' : $args[ 'company' ];
		$args[ 'categoryId' ] = intval( $cat_id );
		$args[ 'pageNo' ] 	  = $page > 0 ? intval( $page ) : 1;
		$args[ 'pageSize' ]   = $page_size > 0 ? $page_size : 40;
		$code                 = get_option( 'dm_currency_code', 'USD' );
		
		if( ! empty( $args[ 'originalPriceFrom' ] ) )
			$args[ 'originalPriceFrom' ] = dm_reconvert_price( $args[ 'originalPriceFrom' ], $code );
		
		if( ! empty( $args[ 'originalPriceTo' ] ) )
			$args[ 'originalPriceTo' ] = dm_reconvert_price( $args[ 'originalPriceTo' ], $code );
		
		if( $args[ 'volumeFrom' ] > $args[ 'volumeTo' ] ) unset( $args[ 'volumeTo' ] );
		if( $args[ 'originalPriceFrom' ] > $args[ 'originalPriceTo' ] ) unset( $args[ 'originalPriceTo' ] );
		
		$args     = $this->unsetEmpty( $args );
		$response = $this->call( $args, 'product' );
		$result   = json_decode( $response );
		$foo      = $this->already( $result );
		$code     = get_option( 'dm_currency_code', 'USD' );
		$symbol   = dm_get_currency_symbol( $code );
		
		$currency_convert = $code != 'USD' ? __( 'Currency' ) . ': ' . $symbol[ 'title' ] . ' '
                 . __( '(Equals', 'dm' ) . ' ' . dm_format_price( dm_reconvert_price( 1, $code ), 'USD' ).')' : '';

		$result->currency_format = dm_get_currency_symbol( $code );
		$result->currency_converter = $currency_convert;
		
		if( isset( $result->products ) && ! empty( $result->products ) ) foreach( $result->products as &$item) {
			
			$item->already      = in_array( $item->productId, $foo );
			$item->productTitle = htmlspecialchars_decode( $item->productTitle, ENT_QUOTES );
			
			$item->purchaseVolume = number_format($item->purchaseVolume, 0, '', ',');
			
			$item->origPrices->origPrice        = dm_convert_price( $item->origPrices->origPrice, $code );
			$item->origPrices->origPriceMax     = dm_convert_price( $item->origPrices->origPriceMax, $code );
			$item->origPrices->origSalePrice    = dm_convert_price( $item->origPrices->origSalePrice, $code );
			$item->origPrices->origSalePriceMax = dm_convert_price( $item->origPrices->origSalePriceMax, $code );
			
			$item->prices->price        = dm_convert_price( $item->prices->price, $code );
			$item->prices->priceMax     = dm_convert_price( $item->prices->priceMax, $code );
			$item->prices->salePrice    = dm_convert_price( $item->prices->salePrice, $code );
			$item->prices->salePriceMax = dm_convert_price( $item->prices->salePriceMax, $code );
			$item->free                 = false;
			
			$shipping = ! empty( $item->shipping ) ? @json_decode( $item->shipping, true ) : false;
			if( $shipping && is_array( $shipping ) ) {
				foreach( $shipping as $wh => $val ) {
					if( isset( $val[ 'f' ][ $args[ 'to' ] ] ) )
						$item->free = true;
				}
			}
			
			unset( $item->shipping );
		}

		return $result;
	}
	
	public function notAvailableProducts( $page = 1, $page_size = 40 ) {
		
		$args = [
			'pageNo'   => $page > 0 ? intval( $page ) : 1,
			'pageSize' => $page_size > 0 ? $page_size : 40
		];
		
		$response = $this->call( $args, 'notavailable' );
		
		if( ! isset( $response->total ) )
			return $response;
		
		if( $response->total == 0 )
			return [ 'error' => __( 'Not found', 'dm' ) ];
		
		global $wpdb;
		
		$current = count( $response->products ) * $response->page;
		$ids     = "'" . implode( "', '", $response->products ) . "'";
		
		if( DM_PLUGIN == 'alidropship' )
			$exists = $wpdb->get_results(
				"SELECT m.post_id
				 FROM {$wpdb->prefix}adsw_ali_meta m LEFT JOIN {$wpdb->postmeta} p ON p.post_id = m.post_id
				 WHERE product_id IN( $ids ) AND p.post_id IS NOT NULL"
			);
		else
			$exists = $wpdb->get_results(
				"SELECT m.post_id
				 FROM {$wpdb->prefix}ads_ali_meta m LEFT JOIN {$wpdb->postmeta} p ON p.post_id = m.post_id
				 WHERE product_id IN( $ids ) AND p.post_id IS NOT NULL" );
		
		if( ! $exists )
			return [ 'total' => $response->total, $current, 'page' => $response->page + 1 ];
		
		foreach( $exists as $item ) {
			update_post_meta( $item->post_id, 'dm_not_available', 1 );
		}
		
		return [ 'total' => $response->total, 'current' => $current, 'page' => $response->page + 1 ];
	}
	
	/**
	 * Get list of imported products already
	 * @param array $args
	 * @return array
	 */
	public function myProducts( $args ) {
		
		$args = [
			'pageNo'   => $args[ 'page' ] > 0 ? intval( $args[ 'page' ] ) : 1,
			'pageSize' => 40,
			'sort'	   => esc_attr( $args[ 'sort' ] ),
		];
		
		$response = $this->call( $args, 'myproduct' );
		
		$result   = json_decode( $response );

		$foo      = $this->already( $result );
		$code     = get_option( 'dm_currency_code', 'USD' );
		$symbol   = dm_get_currency_symbol( $code );
		
		$currency_convert = $code != 'USD' ? __( 'Currency' ) . ': ' . $symbol[ 'title' ] . ' '
                 . __( '(Equals', 'dm' ) . ' ' . dm_format_price( dm_reconvert_price( 1, $code ), 'USD' ).')' : '';

		$result->currency_format = dm_get_currency_symbol( $code );
		$result->currency_converter = $currency_convert;
				
		if( isset( $result->products ) && ! empty( $result->products ) ) {
			
			foreach( $result->products as &$item) {
			
				$item->already      = in_array( $item->productId, $foo );
				$item->productTitle = htmlspecialchars_decode( $item->productTitle, ENT_QUOTES );
				
				$item->origPrices->origPrice        = dm_convert_price( $item->origPrices->origPrice, $code );
				$item->origPrices->origPriceMax     = dm_convert_price( $item->origPrices->origPriceMax, $code );
				$item->origPrices->origSalePrice    = dm_convert_price( $item->origPrices->origSalePrice, $code );
				$item->origPrices->origSalePriceMax = dm_convert_price( $item->origPrices->origSalePriceMax, $code );
				
				$item->prices->price        = dm_convert_price( $item->prices->price, $code );
				$item->prices->priceMax     = dm_convert_price( $item->prices->priceMax, $code );
				$item->prices->salePrice    = dm_convert_price( $item->prices->salePrice, $code );
				$item->prices->salePriceMax = dm_convert_price( $item->prices->salePriceMax, $code );
			}
			
			$result->products = array_values( (array) $result->products );
		}
		//$test = (array)$result->products;
		//return array_values($test);
		if( $result->notavailable > 0 )
			$result->notavailable .= ' ' . __( 'Products are no longer available on AliExpress', 'dm' );
		else
			$result->notavailable = '';
		
		return $result;
	}
	
	/**
	 * Get list of products searched by parameters
	 * @param int $post_id
	 * @param string $message
	 * @param string $report_type
	 * @return object
	 */
	public function sendReport( $post_id, $message, $report_type ) {

		$response = $this->callPostPath( [
            'report_type' => $report_type,
			'message' => $report_type.', '.$message
		], "report/{$post_id}" );

        if( dm_is_base64_encoded( $response ) )
            $response = base64_decode( $response );

		$result = json_decode(  $response  );

		return $result;
	}
	
	private function already( $data ) {
		
		$data = (array) $data;

		if( ! isset( $data[ 'products' ] ) || count( (array) $data[ 'products' ] ) == 0 )
			return [];
		
		global $wpdb;
		
		$ids = array_map( function( $item ) { return $item->productId; }, (array) $data[ 'products' ] );
		
		if( count( $ids ) == 0 )
			return [];
		
		$ids    = implode( ',', $ids );
		$table  = ( DM_PLUGIN == 'woocommerce' ) ? 'adsw_ali_meta' : 'ads_ali_meta';
		$result = $wpdb->get_results( "SELECT product_id FROM {$wpdb->prefix}{$table} WHERE product_id IN ({$ids})" );
		
		$foo = [];
		if( $result )
			$foo = array_map( function( $item ) { return $item->product_id; }, $result );
		
		return $foo;
	}
    public function dm_convertCurrencyAds( $amount = 1, $from = 'USD', $to='' )
    {
        //$foo = [];
        $foo = get_transient('dm_convert_currency_ads');
        if (!$foo) {

            $foo = [];

            //$key = get_option('ali-license', '');
           // $site = get_bloginfo('url');

            $url = 'https://allpartnership.com/?exchange=true&key=UIRL0LR2L944BJZREKSEA04V';

            $args = [
                'method' => 'GET',
                'headers' => [
                    'user-agent' => 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.116 Safari/537.36',
                ],
                'timeout' => 45
            ];

            $response = \wp_remote_get( $url, [
                	'timeout'    => 45,
                	'sslverify'  => false,
                	'user-agent' => 'AliDropship/' . DM_VERSION . '; ' . get_bloginfo('url') ]
                );
            //$response = \wp_remote_request($url, $args);

            if (is_wp_error($response)) {
                return false;
            }

            if (!wp_remote_retrieve_response_code($response) == '200') {
                return false;
            }

            $data = wp_remote_retrieve_body($response);

            $data = json_decode($data, true);

            $foo = $data['data'];
            set_transient('dm_convert_currency_ads', $foo, 600);

            return $foo;

        }
        return $foo;
    }
	public static function run() {
		
		$currency_code = '';
		
//		if( DM_PLUGIN != 'alidropship' ) {
//
			$cached       = get_transient( 'currency_code' );
			$code         = get_option( 'woocommerce_currency', 'USD' );
			$current_code = get_option( 'dm_currency_code', 'USD' );

			if ( $cached === false || $code != $current_code ) {

				$list = dm_list_currency();

				if ( $current_code != 'USD' && $code != 'USD' && isset( $list[ $code ] ) ) {
					$currency_code =  $code;
				} else {
					delete_option( 'dm_currency_value' );
					update_option( 'dm_currency_code', $code );
				}
			}
//		} else {
			
			//$code = ADS_CUR_DEF;
			
			//if( $code != 'USD' ) {
			//	$currency_code = '?code=' . ADS_CUR_DEF;
			//} else {
			//	delete_option( 'dm_currency_value' );
			//	update_option( 'dm_currency_code', 'USD' );
			//}
		//}
		
		//$obj = new self();
		//
		//$response = \wp_remote_get( $obj->path . $currency_code, [
		//	'timeout'    => 45,
		//	'sslverify'  => false,
		//	'user-agent' => 'AliDropship/' . DM_VERSION . '; ' . get_bloginfo('url') ]
		//);
        //var_dump($response);
		//if( is_wp_error( $response ) )
		//	return [ 'error' => $response->get_error_message() ];
		//
		//if( ! wp_remote_retrieve_response_code( $response ) == '200' )
		//	return [ 'error' => __( 'The response from the server has not been received', 'dm' ) ];
		//
		//$body = json_decode( wp_remote_retrieve_body( $response ), true );
		//
		//if( $currency_code != '' && isset( $body[ 'currency' ] ) && ! empty( $body[ 'currency' ] ) ) {
		//	update_option( 'dm_currency_code', $code );
		//	update_option( 'dm_currency_value', esc_attr( $body[ 'currency' ] ) );
		//	set_transient( 'currency_code', esc_attr( $body[ 'currency' ] ), 12 * HOUR_IN_SECONDS );
		//}
        $obj = new self();
        $cur = $obj->dm_convertCurrencyAds($amount = 1, $from = 'USD', $currency_code);
        	update_option( 'dm_currency_code', $code );
        	update_option( 'dm_currency_value', esc_attr( $cur[$code] ) );
        	set_transient( 'currency_code', esc_attr( $cur[$code] ), 12 * 60 );
        //var_dump($cur);
        return $cur;
		//return $body;
	}
	
	private function unsetEmpty( $args ){
		
		foreach($args as $key => $val)
			if( $val == '' )
				unset($args[$key]);
		
		return $args;
	}
	
	private function call( $args, $method ) {
		
		$args[ 'site' ]    = dm_get_domain();
		$args[ 'license' ] = get_option( 'dm-license' );
		
		$url = $this->path . '/dm/search/' . $method . '/';

		$foo = [];

		foreach( $args as $key => $val ){
			
			$key = str_replace( "%7E", "~", rawurlencode($key) );
			$val = str_replace( "%7E", "~", rawurlencode($val) );
			$foo[] = $key . "=" . $val;
		}
		
		$url .= '?' . implode( "&", $foo );

		$response = \wp_remote_get( $url, [
			'timeout'    => 45,
			'sslverify'  => false,
			'user-agent' => 'AliDropship/' . DM_VERSION . '; ' . get_bloginfo('url') ]
		);
		//pr($response);
		if( is_wp_error( $response ) )
			return [ 'error' => $response->get_error_message() ];
		
		if( ! wp_remote_retrieve_response_code( $response ) == '200' )
			return [ 'error' => __( 'The response from the server has not been received', 'dm' ) ];

		return wp_remote_retrieve_body( $response );
	}
	
	private function callPath( $args, $method ) {
		
		$args[ 'site' ]    = dm_get_domain();
		$args[ 'license' ] = get_option( 'dm-license' );
		
		$url = $this->path . '/dm/' . $method . '/';
		
		$foo = [];
		
		if( ! empty( $args ) ) {
			
			foreach ( $args as $key => $val ) {
				
				$key   = str_replace( "%7E", "~", rawurlencode( $key ) );
				$val   = str_replace( "%7E", "~", rawurlencode( $val ) );
				$foo[] = $key . "=" . $val;
			}
			
			$url .= '?' . implode( "&", $foo );
		}
		
		$response = \wp_remote_get( $url, [
			'timeout'    => 35,
			'sslverify'  => false,
			'user-agent' => 'AliDropship/' . DM_VERSION . '; ' . get_bloginfo('url') ]
		);
		
		if( is_wp_error( $response ) )
			return [ 'error' => $response->get_error_message() ];
		
		if( ! wp_remote_retrieve_response_code( $response ) == '200' )
			return [ 'error' => __( 'The response from the server has not been received', 'dm' ) ];
		
		return wp_remote_retrieve_body( $response );
	}
	
	private function callPostPath( $args, $method ) {

		$params = [
			'site'    => dm_get_domain(),
			'license' => get_option( 'dm-license' )
		];

		$url = $this->path . '/dm/' . $method . '/';

		$foo = [];

		if( ! empty( $params ) ) {

			foreach ( $params as $key => $val ) {

				$key   = str_replace( "%7E", "~", rawurlencode( $key ) );
				$val   = str_replace( "%7E", "~", rawurlencode( $val ) );
				$foo[] = $key . "=" . $val;
			}

			$url .= '?' . implode( "&", $foo );
		}

		$response = \wp_remote_post( $url, [
			'method'    => 'POST',
			'timeout'   => 45,
			'sslverify' => false,
			'header' => [
				'user-agent' => 'AliDropship/' . DM_VERSION . '; ' . get_bloginfo('url')
			],
			'body' => json_encode( $args )
		] );

		if( is_wp_error( $response ) )
			return [ 'error' => $response->get_error_message() ];

		if( ! wp_remote_retrieve_response_code( $response ) == '200' )
			return [ 'error' => __( 'The response from the server has not been received', 'dm' ) ];

		return wp_remote_retrieve_body( $response );
	}
	
	private function getSignature() {
		
		$site    = dm_get_domain();
		$license = get_option( 'dm-license' );
		
		return md5( $site . $license );
	}
}