<?php
/**
 * Author: Vitaly Kukin
 * Date: 25.07.2018
 * Time: 21:38
 */

namespace dm;


class dmReviews {
	
	/**
	 * @uses action_review_form()
	 * @uses action_first_review()
	 * @uses action_next_review()
	 * @uses action_upload_review()
	 */
	
	CONST FEEDBACKURL = 'https://feedback.aliexpress.com/display/productEvaluation.htm?v=2&ownerMemberId=0&memberType=seller&productId=0&companyId=&evaStarFilterValue=all+Stars&evaSortValue=sortlarest%40feedback&page=&currentPage=1&startValidDate=&i18n=true&withPictures=false&withPersonalInfo=false&withAdditionalFeedback=false&onlyFromMyCountry=false&version=&translate=+N+&jumpToTop=false';
	
	private $settings = [];
	
	public function __construct() {
		
		$args           = get_option( 'dm_review_import_settings' , [] );
		$this->settings = dm_parse_args( $this->default_setting(), $args );
	}
	
	/**
	 * @param array $post
	 *
	 * @return array
	 */
	public function actions( $post ) {
		
		if ( isset( $post[ 'dm_action' ] ) && current_user_can( 'activate_plugins' ) ) {
			
			$actions = 'action_' . $post[ 'dm_action' ];
			$args    = $post[ 'args' ];
			$data    = [];
			
			if( ! is_array( $args ) ) {
				parse_str( $args, $data );
			} else {
				$data = $args;
			}
			
			if( method_exists( $this, $actions ) ) {
				return $this->$actions( $data );
			}
		}
		
		return [ 'error' => __( 'Undefined action', 'dm' ) ];
	}
	
	private function isApplyEmpty() {
		
		return isset( $this->settings[ 'apply_empty' ] ) && $this->settings[ 'apply_empty' ] == '1';
	}
	
	private function applyMin() {
		
		return isset( $this->settings[ 'apply_min' ] ) ? $this->settings[ 'apply_min' ] : '1';
	}
	
	private function isApplyCategories() {
		
		return isset( $this->settings[ 'prod_type' ] ) && $this->settings[ 'prod_type' ] == 'categories';
	}
	
	private function getProductCat() {
		
		return isset( $this->settings[ 'product_cat' ] ) && ! empty( $this->settings[ 'product_cat' ] ) ?
			$this->settings[ 'product_cat' ] : false;
	}
	
	private function prepareProductCat( $args ) {
		
		$foo = [];
		
		foreach( $args as $item ) {
			$foo[] = esc_attr( $item );
		}
		
		return $foo;
	}
	
	/**
	 * The data for review panel
	 *
	 * @return array
	 */
	private function action_review_form() {
		
		$stars = [
			'5' => __( 'Only 5 stars', 'dm' ),
			'4' => __( '4 stars and higher', 'dm' ),
			'3' => __( '3 stars and higher', 'dm' ),
			'2' => __( '2 stars and higher', 'dm' ),
			'1' => __( '1 star and higher', 'dm' ),
		];
		
		$reviews = [
			'20'  => __( 'up to 20', 'dm' ),
			'40'  => __( 'up to 40', 'dm' ),
			'60'  => __( 'up to 60', 'dm' ),
			'80'  => __( 'up to 80', 'dm' ),
			'100' => __( 'up to 100', 'dm' ),
		];
		
		$args = get_option( 'dm_review_import_settings' , [] );
		$args = dm_parse_args( $this->default_setting(), $args );
		
		return [
			'count_review'        => $args[ 'count_review' ],
			'values_count_review' => dm_prepare_options( $reviews ),
			'min_star'            => $args[ 'min_star' ],
			'values_min_star'     => dm_prepare_options( $stars ),
			'onlyFromMyCountry'   => $args[ 'onlyFromMyCountry' ],
			'switchTranslate'     => $args[ 'switchTranslate' ],
			'ignoreImages'        => $args[ 'ignoreImages' ],
			'withImage'           => $args[ 'withImage' ],
			'uploadImage'         => $args[ 'uploadImage' ],
			'apply_empty'         => $args[ 'apply_empty' ],
			'apply_min'           => $args[ 'apply_min' ],
			'product_cat'         => $args[ 'product_cat' ],
			'values_product_cat'  => dm_generate_taxonomy_options( 'product_cat', 0, 0 ),
			'prod_type'           => $args[ 'prod_type' ],
			'values_prod_type'    => $this->templateValueSelect($this->values_prod_type()),
		];
	}
	
	private function values_prod_type() {
		
		return [
			''           => __( 'All Categories', 'dm' ),
			'categories' => __( 'Select categories', 'dm' ),
		];
	}
	
	private function templateValueSelect( $value ) {
		
		$foo = [];
		foreach( $value as $k => $v )
			$foo[] = [ 'title' => $v, 'value' => $k ];
		
		return $foo;
	}
	
	private function action_first_review( $args = [] ) {
		
		$args = dm_parse_args( $this->default_setting(), $args );
		
		if( isset( $args[ 'product_cat' ] ) && ! empty( $args[ 'product_cat' ] ) )
			$args[ 'product_cat' ] = $this->prepareProductCat( $args[ 'product_cat' ] );
		
		$this->settings = $args;
			
		update_option( 'dm_review_import_settings' , $args );
		update_option( 'dm_current_product_id', 0 );
		
		$total = $this->count_products();
		$row = $this->get_next_product( 0 );
		
		$foo = [
			'total'   => $total,
			'row'     => $row,
			'current' => 1
		];
		
		if( $total == 0 ) {
			$foo[ 'error' ] = __( 'Products not found', 'dm' );
		}
		
		if( $row )
			update_option( 'dm_current_product_id', $row[ 'post_id' ] );
		
		return $foo;
	}
	
	private function default_setting() {
		
		return [
			'min_star'          => '5',
			'count_review'      => '20',
			'onlyFromMyCountry' => '0',
			'switchTranslate'   => '0',
			'ignoreImages'      => '0',
			'withImage'         => '0',
			'uploadImage'       => '0',
			'apply_empty'       => '0',
			'apply_min'         => '1',
			'product_cat'       => '',
			'prod_type'         => '',
		];
	}
	
	private function action_next_review( $args = [] ) {
		
		$total      = intval( $args[ 'total_item' ] );
		$current    = intval( $args[ 'current_item' ] ) + 1;
		$current_id = get_option( 'dm_current_product_id', 0 );
		
		$row = $this->get_next_product( $current_id );
		
		if( $row ) {
			
			update_option( 'dm_current_product_id', $row[ 'post_id' ] );
			
			return [
				'total'   => $total,
				'current' => $current,
				'row'     => $row
			];
		}
		
		update_option( 'dm_current_product_id', 0 );
		
		return [
			'message' => __( 'Import reviews has been successfully finished', 'dm' ),
			'total'   => $total,
			'current' => $current,
			'row'     => $row
		];
	}
	
	protected function get_next_product( $current_id ) {
		
		global $wpdb;
		
		$WHERE = '';
		
		if( $this->isApplyEmpty() ) {
            if( DM_PLUGIN == 'woocommerce' ) {
                $WHERE .= 'AND p.comment_count <= '. (int)$this->applyMin();
            }
            else{
                $WHERE .= 'AND posts.comment_count <= '. (int)$this->applyMin();
            }
		}
		
		if( $this->isApplyCategories() ) {
			
			$args = [
                'posts_per_page' => -1,
			    'tax_query' => [ [
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $this->getProductCat(),
			] ] ];
			
			$query = new \WP_Query( $args );
			
			if( $query->post_count > 0 ) {
				
				$post_in = [];
				
				foreach( $query->posts as $v ) {
					$post_in[] = $v->ID;
				}
				
				$WHERE .= ' AND pr.post_id IN(' . implode( ',', $post_in ) . ')';
			} else {
				return false;
			}
		}
		
		if( DM_PLUGIN == 'woocommerce' ) {
			$row = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT
				  		p.ID as post_id, product_id,
				  		post_title, productUrl, feedbackUrl
					 FROM {$wpdb->posts} p INNER JOIN {$wpdb->prefix}adsw_ali_meta pr ON pr.post_id = p.ID
					 WHERE p.ID <> 0 {$WHERE} AND p.ID > %d LIMIT 1", $current_id
				),
				ARRAY_A
			);
		} else {
			
			$row = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT
					pr.post_id as post_id, product_id,
					pr.imageUrl, post_title, productUrl, feedbackUrl
                 FROM {$wpdb->prefix}ads_products pr
                  	INNER JOIN {$wpdb->posts} posts ON pr.post_id = posts.ID
                    LEFT JOIN {$wpdb->prefix}ads_products_meta pm ON pm.post_id = pr.post_id
                    LEFT JOIN {$wpdb->prefix}ads_ali_meta am ON am.post_id = pm.post_id
                 WHERE pr.post_id <> 0 {$WHERE} AND pr.post_id > %d LIMIT 1", $current_id
				),
				ARRAY_A
			);
		}
		
		if( ! empty( $row ) ) {
			
			$row[ 'feedbackUrl' ] = $this->formatFeedbackUrl( $row[ 'product_id' ], $row[ 'feedbackUrl' ] );
			
			if( DM_PLUGIN == 'woocommerce' ) {
				$row[ 'imageUrl' ]     = $this->get_product_thumbnail_url( $row[ 'post_id' ], 'thumbnail' );
				$row[ 'product_link' ] = get_permalink( $row[ 'post_id' ] );
			}
		}
		
		return empty( $row ) ? false : $row;
	}
	
	protected function formatFeedbackUrl( $product_id,  $feedbackUrl ) {
		
		$url    = self::FEEDBACKURL;
        $result = parse_url( 'http:' . str_replace( [ '#038;', '038;', 'amp;' ], '', $feedbackUrl ) );
		
		parse_str( $result[ 'query' ], $params );
		
		$foo = [
			'productId'     => $product_id,
			'ownerMemberId' => $params[ 'ownerMemberId' ],
			'companyId'     => $params[ 'companyId' ],
		];
		
		if ( ! isset( $params[ 'ownerMemberId' ] ) || ! isset( $params[ 'companyId' ] ) ) {
			return false;
		}
		
		return \add_query_arg( $foo, $url );
	}
	
	public function count_products() {
		
		global $wpdb;
		
		$WHERE = '';
		if( $this->isApplyEmpty() ) {
            if( DM_PLUGIN == 'woocommerce' ) {
                $WHERE .= 'AND p.comment_count <= '. (int)$this->applyMin();
            }
            else{
                $WHERE .= 'AND posts.comment_count <= '. (int)$this->applyMin();
            }
		}
		
		if( $this->isApplyCategories() ) {
			
			$args = [
                'posts_per_page' => -1,
			    'tax_query' => [ [
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $this->getProductCat(),
			] ] ];
			
			$query = new \WP_Query($args);
			
			if( $query->post_count > 0 ) {
				
				$post_in = [];
				
				foreach( $query->posts as $v ) {
					$post_in[] = $v->ID;
				}
				
				$WHERE .= ' AND pr.post_id IN(' . implode( ',', $post_in ) . ')';
			} else {
				return 0;
			}
		}
		
		if( DM_PLUGIN == 'woocommerce' ) {
			$var = $wpdb->get_var(
				"SELECT count(p.ID) as `con` FROM {$wpdb->posts} p
				 INNER JOIN {$wpdb->prefix}adsw_ali_meta pr ON pr.post_id = p.ID
			 	 WHERE p.ID <> 0 {$WHERE}"
			);
		} else {
			$var = $wpdb->get_var(
				"SELECT COUNT(pr.post_id) FROM {$wpdb->prefix}ads_products pr
                 INNER JOIN {$wpdb->posts} posts ON pr.post_id = posts.ID
                 LEFT JOIN {$wpdb->prefix}ads_products_meta pm ON pm.post_id = pr.post_id
                 LEFT JOIN {$wpdb->prefix}ads_ali_meta am ON am.post_id = pm.post_id
                 WHERE pr.post_id <> 0 {$WHERE}"
			);
		}
		
		return ! empty( $var ) ? $var : 0;
	}
	
	private function action_upload_review( $data ) {
		
		$data[ 'feed_list' ]      = json_decode( base64_decode( $data[ 'feed_list' ] ), true );
		$data[ 'importedReview' ] = (int) $data[ 'importedReview' ];
		$data[ 'approved' ]       = $data[ 'approved' ] == "false"; //Send reviews to draft
		$data[ 'count_review' ]   = (int) $data[ 'count_review' ];
		$data[ 'apply_min' ]      = (int) $data[ 'apply_min' ];
		$data[ 'apply_min' ]      = $data[ 'apply_min' ] < 0 ? 1 : $data[ 'apply_min' ];
		$data[ 'uploadImages' ]   = $data[ 'uploadImages' ] == 'true';
		
		$data[ 'count_review' ] = mt_rand( round( $data[ 'count_review' ] - ( $data[ 'count_review' ] * 0.3 ) ), $data[ 'count_review' ] );
		
		$count = $this->addFeedback( $data );
		
		if ( $data[ 'uploadImages' ] ) {
			$this->downloadImageReview( $data[ 'post_id' ] );
		}
		
		$data[ 'count' ] = (int) $count;
		$data[ 'page' ]  = ( (int) $data[ 'page' ] ) + 1;
		
		return $data;
	}
	
	/**
	 * downloadImageReview
	 * @param int $post_id
	 */
	private function downloadImageReview( $post_id ) {
		
		$uploadImage = new dmUploadImages();
		$uploadImage->uploadImagesReview( $post_id );
	}
	
	protected function addFeedback( $post ) {
		
		$feedList = $post[ 'feed_list' ];
		$post_id  = intval( $post[ 'post_id' ] );
		$count    = 0;
		$starMin  = isset( $post[ 'star_min' ] ) && $post[ 'star_min' ] <= 5 && $post[ 'star_min' ] > 0 ?
			absint( $post[ 'star_min' ] ) : 1;
		
		$count_review   = $post[ 'count_review' ];
		$importedReview = $post[ 'importedReview' ];
		$approved       = $post[ 'approved' ] ? 1 : 0;
		
		$domain = str_replace( [ 'https://', 'http://' ], '', get_bloginfo( 'url' ) );
		
		if( ! empty( $feedList ) ) foreach ( $feedList as $k => $f ) {
			
			$content = trim( preg_replace( '/\s+/', ' ', $f[ 'feedback' ] ) );
			if ( ! $content ) continue;
			
			$author = trim( $f[ 'author' ] );
			if ( ! $author ) continue;
			
			$flag = trim( $f[ 'flag' ] );
			if ( ! $flag ) continue;
			
			$star = absint( trim( $f[ 'star' ] ) );
			if ( ! $star ) continue;
			if ( $star < $starMin ) continue;
			
			$date = trim( $f[ 'date' ] );
			if ( ! $date )continue;
			
			$date = $this->formatDate( $date );
			
			$commentdata = [
				'comment_post_ID'      => $post_id,
				'comment_author'       => strpos( $author, 'AliExpress' ) !== false ? __( 'Customer', 'dm' ) : $author,
				'comment_author_email' => '',
				'comment_content'      => str_replace(
					[
						'AliExpress', 'aliexpress', 'aliExpress', 'ali express',
						'АлиЭкспресс', 'АлиЭкспрес', 'алиэкспресс', 'алиэкспрес',
						'алиЭкспресс', 'алиЭкспрес', 'али экспресс', 'али экспрес'
					],
					$domain, $content ),
				'comment_type'         => '',
				'user_ID'              => 0,
				'comment_date'         => $date,
				'comment_approved'     => 1,
				'comment_author_url'   => ''
			];
			
			if ( $this->existsReview( $commentdata ) )
				continue;
			
			if( $count_review <= $importedReview )
				return $count;
			
			$importedReview++;
			$count++;
			
			$comment_id = wp_new_comment( $commentdata, true );
			
			if ( $comment_id && ! is_wp_error( $comment_id ) && intval( $comment_id ) > 0 ) {
				
				if( DM_PLUGIN == 'alidropship' ) {
					add_comment_meta( $comment_id, 'star', $star, true );
				} else {
					add_comment_meta( $comment_id, 'rating', $star, true );
				}
				add_comment_meta( $comment_id, 'flag', $flag, true );
				
				if( $post[ 'ignoreImages' ] == 'false' ) {
					add_comment_meta( $comment_id, 'images', serialize( $f[ 'images' ] ), true);
				}
				
				wp_update_comment( [ 'comment_ID' => $comment_id, 'comment_approved' => $approved, 'user_ID' => 0 ] );
			}
			
			if( DM_PLUGIN == 'alidropship' )
				$this->updateProductInfoReview( $post_id );
		}
		
		return $count;
	}
	
	private function updateProductInfoReview( $post_id ) {
		
		global $wpdb;
		
		$var = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT COUNT(m.meta_id) as countReview, AVG(m.meta_value) as evaluateScore
                 FROM {$wpdb->comments} com INNER JOIN {$wpdb->commentmeta} m ON com.comment_ID = m.comment_id
                 WHERE com.comment_post_ID = '%d' AND m.meta_key = 'star'",
				$post_id
			),
			ARRAY_A
		);
		
		if ( ! $var[ 'countReview' ] && ! $var[ 'evaluateScore' ] ) {
			return false;
		}
		
		$newPrice = [
			'countReview'   => $var[ 'countReview' ],
			'evaluateScore' => $var[ 'evaluateScore' ]
		];
		
		return $wpdb->update( $wpdb->ads_products, $newPrice, [ 'post_id' => $post_id ], [ '%d', '%f' ], [ '%d' ] );
	}
	
	/**
	 * existsReview
	 *
	 * @global object $wpdb
	 * @param array $cd
	 * @return boolean
	 */
	private function existsReview( $cd ) {
		
		global $wpdb;
		
		$sql = $wpdb->prepare(
			"SELECT count(comment_ID) as countComment FROM `{$wpdb->comments}`
			 WHERE `comment_post_ID` = '%d' AND `comment_date` = '%s' AND `comment_author` = '%s'",
			$cd[ 'comment_post_ID' ], $cd[ 'comment_date' ], $cd[ 'comment_author' ]
		);
		
		$count = $wpdb->get_var( $sql );
		
		return $count > 0;
	}
	
	private function formatDate( $date ) {
		
		$date = date_parse( $date );
		$date = mktime( $date[ 'hour' ], $date[ 'minute' ], $date[ 'second' ], $date[ 'month' ], $date[ 'day' ], $date[ 'year' ] );
		$date = date( 'Y-m-d H:i:s', $date );
		
		return $date;
	}
	
	protected function get_product_thumbnail_url( $post_id, $size = 'full' ) {
		
		if ( has_post_thumbnail( $post_id ) )
			return get_the_post_thumbnail_url( $post_id, $size );
	}
}