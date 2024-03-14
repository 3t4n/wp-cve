<?php
/**
 * Author: Vitaly Kukin
 * Date: 01.02.2018
 * Time: 11:42
 */

namespace dm;


use ads\update\pricingMarkupFormula;

class dmAliDropshipApi {
	/**
	 * @uses action_info()
	 * @uses action_search_form()
	 * @uses action_import_form()
	 * @uses action_search()
	 * @uses action_send_report()
	 * @uses action_subcat()
	 * @uses action_import_product()
	 * @uses action_check_not()
	 * @uses action_search_my()
	 */
	
	public function actions( $post ) {
		
		if ( isset( $post[ 'ads_action' ] ) && current_user_can( 'activate_plugins' ) ) {
			
			$ads_actions = 'action_' . $post[ 'ads_action' ];
			$args        = isset( $post[ 'args' ] ) ? $post[ 'args' ] : '';
			$data        = [];
			
			parse_str( $args, $data );
			
			if( method_exists( $this, $ads_actions ) ) {
				return $this->$ads_actions( $data );
			}
		}
		
		return [ 'error' => __( 'Undefined action', 'dm' ) ];
	}
	
	private function action_info( $data = [] ){
		
		$id = isset( $data[ 'id' ] ) && $data[ 'id' ] > 0 ? $data[ 'id' ] : false;
		
		if( ! $id )
			return [ 'error' => __( 'Product ID not found', 'dm' ) ];
		
		$ali = new dmApi();
		return $ali->productInfo( $id );
	}
	
	private function action_search_form() {
		
		$api = new dmApi();
		
		$parent_cats = $api->parentNodes();

		if( isset( $parent_cats[ 'error' ] ) )
			return $parent_cats;
		
		$cats = [ '0' => __( 'All categories', 'dm' ) ];
		$cats += ($parent_cats[ 'data' ] ? $parent_cats[ 'data' ] : []);
		$cats = dm_prepare_options( $cats );

		$sub_cats = dm_prepare_options( [ '0' => __( 'All Sub Categories', 'dm' ) ] );
		$code     = get_option( 'dm_currency_code', 'USD' );
		$symbol   = dm_get_currency_symbol( $code );
		
		$currency_convert = $code != 'USD' ? __( 'Currency' ) . ': ' . $symbol[ 'title' ] . ' '
                 . __( '(Equals', 'dm' ) . ' ' . dm_format_price( dm_reconvert_price( 1, $code ), 'USD' ).')' : '';
		
		$settings = $this->action_import_setting();
		
		$foo = [
			'categoryId'           => '0',
			'values_categoryId'    => $cats,
			'subCategoryId'        => '0',
			'values_subCategoryId' => $sub_cats,
			'keywords'             => '',
			'originalPriceFrom'    => '',
			'originalPriceTo'      => '',
			'volumeFrom'           => '',
			'volumeTo'             => '',
			'sort'                 => 'volumeDown',
			'page'                 => '1',
			'deposit'              => $parent_cats[ 'deposit' ],
			'currency_converter'   => $currency_convert,
			'currency_symbol'      => trim( $symbol[ 'symbol' ] ),
			'convert_value'        => dm_convert_price( 1, $code ),
			'create_cat'           => $settings[ 'create_cat' ],
			'create_cat_e'         => $settings[ 'create_cat' ],
			'attributes'           => $settings[ 'attributes' ],
			'attributes_e'         => $settings[ 'attributes' ],
			'publish'              => $settings[ 'publish' ],
			'publish_e'            => $settings[ 'publish' ],
			'recommended_price'    => $settings[ 'recommended_price' ],
			'recommended_price_e'  => $settings[ 'recommended_price' ],
			'cat_status'           => $settings[ 'cat_status' ]
		];
		
		return array_merge( $foo, $this->action_import_form() );
	}
	
	private function action_import_setting() {
		
		$settings = get_option( 'dm_import_settings', [] );
		$defaults = [
			'create_cat'        => 0,
			'attributes'        => 0,
			'publish'           => 0,
			'recommended_price' => 0,
			'cat_status'        => 0
		];
		
		return dm_parse_args( $defaults, $settings );
	}
	
	private function action_save_settings( $data = [] ) {
		
		$settings = get_option( 'dm_import_settings', [] );
		$defaults = [
			'create_cat'        => 0,
			'attributes'        => 0,
			'publish'           => 0,
			'recommended_price' => 0,
			'cat_status'        => 0
		];
		$settings = dm_parse_args( $defaults, $settings );
		
		$args = [
			'create_cat'        => isset( $data[ 'create_cat' ] ) ? intval( $data[ 'create_cat' ] ) : $settings[ 'create_cat' ],
			'attributes'        => isset( $data[ 'attributes' ] ) ? intval( $data[ 'attributes' ] ) : $settings[ 'attributes' ],
			'publish'           => isset( $data[ 'publish' ] ) ? intval( $data[ 'publish' ] ) : $settings[ 'publish' ],
			'recommended_price' => isset( $data[ 'recommended_price' ] ) ? intval( $data[ 'recommended_price' ] ) : $settings[ 'recommended_price' ],
			'cat_status'        => isset( $data[ 'cat_status' ] ) ? intval( $data[ 'cat_status' ] ) : $settings[ 'cat_status' ],
		];
		
		update_option( 'dm_import_settings', $args );
		
		return $args;
	}
	
	private function action_import_form( $success_message = false ) {
		
		$categories = get_terms( 'product_cat', [ 'hide_empty' => false ] );
		
		if( ! $categories || is_wp_error( $categories ) ) {
			return [
				'categoryImport'        => '',
				'values_categoryImport' => [],
			];
		}
		
		$terms = [];
		$this->sort_terms_hierarchically( $categories, $terms );
		
		if( count( $terms ) == 0 )
			return [
				'categoryImport'        => '',
				'values_categoryImport' => [],
			];
		
		$foo = $this->prepare_terms( $terms );
		$foo = dm_prepare_options( $foo );
		
		$args = [
			'categoryImport'        => '',
			'values_categoryImport' => $foo
		];
		
		if( $success_message ) {
			$args[ 'success' ] = __( 'Category added', 'dm' );
		}
		
		return $args;
	}
	
	private function action_search( $data = [] ) {
		
		$args = [
			'categoryId'        => '',
			'subCategoryId'     => '',
			'keywords'          => '',
			'originalPriceFrom' => '',
			'originalPriceTo'   => '',
			'volumeFrom'        => '',
			'volumeTo'          => '',
			'sort'              => '',
			'free'				=> '',
			'warehouse'			=> '',
			'to'    			=> '',
			'company'           => '',
			'page'              => 1,
            'supplier'          => ''
		];
		
		foreach ( $args as $key => &$val ) {
			if ( isset( $data[ $key ] ) ) {
				$val = $data[ $key ];
			}
		}
		
		$ali = new dmApi();
		
		if( empty( $args[ 'categoryId' ] ) )
			$category = 0;
		else
			$category = ! empty( $args[ 'subCategoryId' ] ) ? intval( $args[ 'subCategoryId' ] ) : intval( $args[ 'categoryId' ] );
		
		return $ali->productsByCat( $category, $args[ 'page' ], $args );
	}
	
	private function action_check_not( $data = [] ) {
		
		$args = [
			'page' => 1,
		];
		
		foreach ( $args as $key => &$val ) {
			if ( isset( $data[ $key ] ) ) {
				$val = $data[ $key ];
			}
		}
		
		$ali = new dmApi();
		
		return $ali->notAvailableProducts( $args[ 'page' ] );
	}
	
	private function action_search_my( $data = [] ) {
		
		$args = [
			'page' => 1,
			'sort' => ''
		];
		
		foreach ( $args as $key => &$val ) {
			if ( isset( $data[ $key ] ) ) {
				$val = $data[ $key ];
			}
		}
		
		$ali = new dmApi();
		
		return $ali->myProducts( $args );
	}
	
	private function action_send_report( $data = [] ) {
		
		$args = [
			'report-id'      => '',
			'report-message' => '',
			'report-type'    => '',
		];
		$report_types = [
		    0 => 'Nothing selected',
		    1 => 'A mistake in product title',
            2 => 'Incorrect pricing',
            3 => 'A problem with images',
            4 => 'A mistake in product description',
            5 => 'Prohibited or offensive product',
            6 => 'Something else',
        ];
		foreach ( $args as $key => &$val ) {
			if ( isset( $data[ $key ] ) ) {
				$val = trim( esc_attr( $data[ $key ] ) );
			}
		}

		$ali = new dmApi();
		
		$post_id = intval( $args[ 'report-id' ] );
		$message = $args[ 'report-message' ];
        $report_type = $report_types[$args[ 'report-type' ]];

		return $ali->sendReport( $post_id, $message, $report_type );
	}

	private function action_subcat( $data = [] ) {
		
		$cat = isset( $data[ 'categoryId' ] ) ? intval( $data[ 'categoryId' ] ) : false;
		
		if( $cat === false )
			return [ 'error' => __( 'Category is not selected', 'dm' ) ];
		
		$api = new dmApi();
		$items = $api->parentNodes( $cat );
		
		if( isset( $items[ 'error' ] ) )
			return $items;
		
		if( ! isset( $items[ 'data' ] ) || count( $items[ 'data' ] ) == 0 ||
		    ( isset( $items[ 'status' ] ) && $items[ 'status' ] == 'NOTFOUND' ) ) {
			return [ 'success' => 'hidesub' ];
		}
		
		$foo = [];
		
		$foo[] = [ 'key' => 0, 'val' => __( 'All Sub Categories', 'dm' ) ];
		
		foreach( $items[ 'data' ] as $key => $item ) {
			
			$foo[] = [
				'key' => $key,
				'val' => htmlspecialchars_decode( $item[ 'title' ] )
			];
			
			if( isset( $item[ 'sub' ] ) && $item[ 'sub' ] ) foreach( $item[ 'sub' ] as $k => $v ) {
				$foo[] = [
					'key' => $k,
					'val' => '— ' . htmlspecialchars_decode( $v )
				];
			}
		}
		
		return $foo;
	}
	
	public function action_import_images( $data = [] ) {
		
		$image = new dmUploadImages();
		
		$args = stripcslashes( base64_decode( $_POST[ 'args' ] ) );
		$args = json_decode( stripcslashes( $args ), true );
		
		global $wpdb;
		
		$post_id = intval( $args[ 'post_id' ] );
		$result = $wpdb->get_row(
			"SELECT option_value FROM {$wpdb->options} WHERE
					option_name LIKE 'me_task_upload_images_{$post_id}'",
			ARRAY_A );
		
		if( ! $result ) {
			
			$info = $image->getProductInfo( $post_id );
			
			return [
				'post_id' => $post_id,
				'product' => $args[ 'product' ],
				'id'      => $info->product_id,
				'url'     => $info->productUrl,
				'success' => '<strong>' . get_the_title( $post_id ) . '</strong> has been imported',
			];
		}
		
		$params = maybe_unserialize( $result[ 'option_value' ] );
		
		$post = get_post( $post_id );
		
		if( ! $post ) {
			
			$info = $image->getProductInfo( $post_id );
			
			\delete_option( 'me_task_upload_images_' . $post_id );
			
			return [
				'success'  => $post_id,
				'product'  => $info->product_id,
				'messages' => __( 'delete task upload images', 'dm' ),
				'action'   => 'upload_images',
			];
		}
		
		if( count( $params[ 'images' ] ) ) {
			
			if ( DM_PLUGIN == 'woocommerce' ) {
				
				return $image->uploadImagesWoo( $params );
			} else {
				
				return $image->uploadImages( $params );
			}
		}
	}
	
	/**
	 * @param array $data
	 *
	 * @return array|mixed|object|string
	 */
	private function action_import_product( $data = [] ) {
		
		$ali = new dmApi();
		
		$id         = isset( $data[ 'id' ] ) && $data[ 'id' ] > 0 ? $data[ 'id' ] : false;
		$cat        = isset( $data[ 'cat' ] ) && ! empty( $data[ 'cat' ] ) ? explode( ',', $data[ 'cat' ] ) : false;
		$create     = isset( $data[ 'create' ] ) && ! empty( $data[ 'create' ] ) ? true : false;
		$publish    = isset( $data[ 'publish' ] ) && ! empty( $data[ 'publish' ] ) ? true : false;
		$attrib     = isset( $data[ 'attributes' ] ) && ! empty( $data[ 'attributes' ] ) ? true : false;
		$rec_price  = isset( $data[ 'recommended_price' ] ) && ! empty( $data[ 'recommended_price' ] ) ? true : false;
        $cat_status = isset( $data[ 'cat_status' ] ) && ! empty( $data[ 'cat_status' ] ) ? $data[ 'cat_status' ] : false;

		if( ! $id ) return [
			'error'   => __( 'Product ID not found', 'dm' ),
			'product' => $id
		];
		
		$product = $ali->productFull( $id );
		
		if( is_array( $product ) && isset( $product[ 'error' ] ) ) {
			
			$product[ 'product' ] = $id;
			
			return $product;
		}
		
		if( isset( $product->error ) ) {
			
			return [
				'error'   => $product->error,
			    'product' => $id
			];
		}
		
		if( ! empty( $product ) && ! isset( $product->status ) ) {
			pr($product);
			var_dump($product);
		}
		
		if( $product->status == 'FOUND' ) {

			$import = new dmImport( $product->data );
			$import->setPublish( $publish );
			$import->setRecommendedPrice( $rec_price );
			$import->setAttributes( $attrib );

			$status = $import->prepare();

			if ( is_array( $status ) ) {

				$status[ 'product' ] = $id;

				return $status;
			}
			
			if ( DM_PLUGIN == 'woocommerce' ) {
				
				$params = $import->publishWoo();
				
				$post_id = $params[ 'post_id' ];
				
				$args = [
					'post_id' => $params[ 'post_id' ],
					'images'  => $params[ 'images' ],
				];

			} else {
				
				$params = $import->publish();
				
				$post_id = $params[ 'post_id' ];
				
				$args = [
					'post_id' => $params[ 'post_id' ],
					'images'  => $params[ 'images' ],
				];
			}

			if( $cat && ! $create ) {

				$koo = [];
				foreach( $cat as $k ) {
					$terms = $this->get_parents_terms( $k );
					
					if( $terms )
						$koo = array_merge( $terms, $koo );
				}
				$koo = array_unique( $koo );

				if( $koo && count( $koo ) )
					wp_set_post_terms( $post_id, $koo, 'product_cat' );
			}
			
			if( $create && ! empty( $import->getCats() ) ) {
				
				$koo = $this->setTerms( $import->getCats(), $cat_status);
				$koo = array_unique( $koo );
				
				if( $koo && count( $koo ) ) {
				    if( $cat_status === '2' ){
                        wp_set_post_terms($post_id, $koo[0], 'product_cat');
                    }
                    if( $cat_status === '1' ){
                        wp_set_post_terms($post_id, end($koo), 'product_cat');
                    }
                    else {
                        wp_set_post_terms($post_id, $koo, 'product_cat');
                    }
                }
			}
			
			if ( is_array( $post_id ) ) {
				
				$post_id[ 'product' ] = $id;
				
				return $post_id;
			}
			
			if( class_exists( '\ads\update\pricingMarkupFormula' ) && ! $import->getRecommendedPrice() ) {
				
				$res = new \ads\update\pricingMarkupFormula();
				$res->update( $post_id );
			} elseif( class_exists( '\adsw\update\pricingMarkupFormula' ) && ! $import->getRecommendedPrice() ) {
				
				$res = new \adsw\update\pricingMarkupFormula();
				$res->update( $post_id );
			} else {
				
				$res = new dmPricingConvert();
				$res->setRecommendedPrice( $import->getRecommendedPrice() );
				
				if( DM_PLUGIN != 'alidropship' )
					$res->update( $post_id, $import->getMeta(), $import->getProduct() );
				else
					$res->updateAliDropship( $post_id, $import->getMeta(), $import->getProduct() );
			}
			
			$this->addTaskuploadImages( $post_id, $id, $args[ 'images' ] );
			
			return [
				'product'        => $id,
				'images'         => $args[ 'images' ],
				'uploadedImages' => [],
				'deposit'        => $product->deposit,
				'post_id'        => $post_id
			];
		} else {
			
			return [
				'error'   => __( 'Access is denied', 'dm' ),
				'product' => $id
			];
		}
	}
	
	private function setTerms( $terms, $cat_status = '0' ) {
		
		$foo = [];
		$i   = 0;
		if( $cat_status === '2' ){
            $t = $this->createTerm($terms['cat_1'], $i);

            if ($t) {
                $i = $t;
                $foo[] = $t;
            }
        }
        elseif( $cat_status === '1' ){
		    $cat_import = !empty($terms['cat_3']) ? $terms['cat_3'] : (!empty($terms['cat_2']) ? $terms['cat_2'] : $terms['cat_1']);
            $t = $this->createTerm($cat_import, $i);

            if ($t) {
                $i = $t;
                $foo[] = $t;
            }
        }
        else {
            foreach ($terms as $cat => $term) {

                if (empty($term))
                    continue;

                $t = $this->createTerm($term, $i);

                if ($t) {
                    $i = $t;
                    $foo[] = $t;
                }
            }
        }
		return $foo;
	}
	
	private function createTerm( $name, $parent = 0 ) {
		
		$slug = sanitize_title( $name );
		
		$tt = get_term_by( 'slug', $slug, 'product_cat' );
		
		if( $tt ) return $tt->term_id;
		
		$term = wp_insert_term( $name, 'product_cat', [ 'parent' => $parent, 'slug' => $slug ] );
		
		return $term && ! is_wp_error( $term ) ? $term[ 'term_id' ] : false;
	}
	
	private function get_parents_terms( $term_id, $list = [] ) {
		
		$term = get_term_by( 'id', $term_id, 'product_cat' );
		
		if( ! $term )
			return count( $list ) > 0 ? $list : false;
		
		$list[] = $term_id;
		
		if( $term->parent != 0 )
			return $this->get_parents_terms( $term->parent, $list );
		
		return $list;
	}
	
	private function prepare_terms( $terms ) {
		
		$foo = [];
		
		foreach( $terms as $term ) {
			
			$foo[ $term->term_id ] = $term->name;
			
			if( isset( $term->children ) && count( $term->children ) ) {
				$too = $this->prepare_terms( $term->children );
				
				if( count( $too ) )
					$foo += $too;
			}
		}
		
		return $foo;
	}
	
	 /**
	 * Recursively sort an array of taxonomy terms hierarchically. Child categories will be
	 * placed under a 'children' member of their parent term.
	 * @param array   $cats     taxonomy term objects to sort
	 * @param array   $into     result array to put them in
	 * @param integer $parentId the current parent ID to put them in
	 * @param string  $depth    separator for subcategories
	 */
	protected function sort_terms_hierarchically( array &$cats, array &$into, $parentId = 0, $depth = '' ) {
		
		$str = '–';
		$str = $depth == '' ? '' : $depth . $str . ' ';
		
		foreach( $cats as $i => $cat ) {
			
			if( $cat->parent == $parentId ) {
				
				$cat->name = $str . htmlspecialchars_decode( $cat->name );
				
				$into[ $cat->term_id ] = $cat;
				
				unset( $cats[ $i ] );
			}
		}
		
		$depth .= '–';
		
		foreach( $into as $topCat ) {
			
			$topCat->children = [];
			
			$this->sort_terms_hierarchically( $cats, $topCat->children, $topCat->term_id, $depth );
		}
	}
	
	protected function addTaskuploadImages( $post_id, $product, $images ) {
		
		if( ! isset( $images ) || !is_array( $images ) || count( $images ) == 0 ) {
			return;
		}
		
		$params = [
			'post_id' => $post_id,
			'product' => $product,
			'images'  => $images,
			'count'   => count( $images )
		];
		
		\add_option( 'me_task_upload_images_' . $post_id, $params, null, 'no' );
		\set_transient( 'me_has_task_upload_images', $post_id, 30 );
	}
	
	protected function action_task() {
		
		return [ 'success' => false ];
	}
	
	protected function action_task_upload_images() {
		
		global $wpdb;
		
		$result = $wpdb->get_row(
			"SELECT option_value FROM {$wpdb->options} WHERE
					option_name LIKE 'me_task_upload_images_%' LIMIT 1",
			ARRAY_A );
		
		if( ! $result ) {
			
			\delete_transient( 'me_has_task_upload_images' );
			
			return [ 'success' => false, 'messages' => __( 'no task upload images', 'dm' ) ];
		}
		
		$params = maybe_unserialize( $result[ 'option_value' ] );
		
		$post_id  = $params[ 'post_id' ];
		$product  = $params[ 'product' ];
		$images   = $params[ 'images' ];
		
		$post = get_post( $post_id );
		
		if( ! $post ) {
			
			\delete_option( 'me_task_upload_images_' . $post_id );
			\delete_transient( 'me_has_task_upload_images' );
			
			return [
				'success'    => $post_id,
				'product_id' => $product,
				'messages'   => __( 'delete task upload images', 'dm' ),
				'action'     => 'upload_images',
			];
		}

		if( count( $images ) ) {
			
			$img = new dmUploadImages();
			
			if ( DM_PLUGIN == 'woocommerce' ) {
				
				return $img->uploadImagesWoo( $params );
			} else {
				
				return $img->uploadImages( $params );
			}
		}
		
		\delete_option( 'me_task_upload_images_' . $post_id );
		\delete_transient( 'me_has_task_upload_images' );
		
		return [
			'success' => $post_id,
			'product' => $product,
			'percent' => 100,
			'action'  => 'upload_images'
		];
	}
}