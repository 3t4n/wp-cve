<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

class ShareASale_WC_Tracker_Datafeed {

	/**
	* Generates, compresses, and cleans up product datafeed files
	* @var string $version Plugin version
	* @var string $version WooCommerce version
	* @var WP_Filesystem $filesystem WordPress filesystem object https://codex.wordpress.org/Filesystem_API
	* @var WP_Error $errors any datafeed generation failure errors
	*/
	private $version, $wc_version, $filesystem;
	public $errors;

	public function __construct( $version, $filesystem ) {
		$this->version    = $version;
		$this->wc_version = WC()->version;
		$this->filesystem = $filesystem;
		$this->errors     = new WP_Error();
		$this->load_dependencies();

		if ( ! $this->filesystem instanceof WP_Filesystem ) {
			$this->errors->add( 'filesystem', 'WP Filesystem API not initialized properly!' );
			return false;
		}

		return $this;
	}

	private function load_dependencies() {
		require_once plugin_dir_path( __FILE__ ) . 'class-shareasale-wc-tracker-datafeed-logger.php';
		$this->logger = new ShareASale_WC_Tracker_Datafeed_Logger( $this->version );
	}

	public function export( $file ) {
		$options           = get_option( 'shareasale_wc_tracker_options' );
		$product_posts_ids = $this->get_all_product_posts_ids();
		$rows = array();

		foreach ( $product_posts_ids as $product_post_id ) {
			$product_post = get_post( $product_post_id, 'OBJECT' );
			//protect against instantiating somehow orphaned variations (causing an exception) by checking its parent for a post_type value too
			if ( 'product_variation' == $product_post->post_type && 'product' == get_post_type( $product_post->post_parent ) ) {
				$product     = new WC_Product_Variation( $product_post );
				$parent_type = wp_get_post_terms( $product->get_parent_id(), 'product_type' )[0]->name;
			} elseif ( 'product' == $product_post->post_type ) {
				$product = new WC_Product( $product_post );
			} else {
				continue;
			}

			/* don't bother with a variant product if it:
			 *has the same non-unique SKU as its parent, usually because though its enabled it isn't yet assigned a SKU itself
			 *or somehow its parent product was once variable, but is now simple again... without the variations having been auto-trashed like normal (rare)
			*/
			if ( $product instanceof WC_Product_Variation && ( ! wc_product_has_unique_sku( $product->get_id(), $product->get_sku() ) || 'simple' == $parent_type ) ) {
				unset( $product );
				continue;
			}

			if( !empty( $options['category-exclusions'] ) && has_term( $options['category-exclusions'], 'product_cat', $product_post_id ) ){
				continue; 
			}

			$product->cross_sell_skus = $this->get_cross_sell_skus( $product );
			$rows[]                   = $this->make_row( $product );
			unset( $product );
		}

		if ( ! empty( $rows ) ) {
			$header  = implode( ',', array_keys( $rows[0] ) );
			$content = $header . "\r\n";

			foreach ( $rows as $row ) {
				$content .= implode( ',', $row ) . "\r\n";
			}
			$product_count = count( $rows );
			unset( $rows );

			if ( $csv = $this->write( $file, $content ) ) {
				if ( ! $compressed = $csv->compress( $file ) ) {
					//couldn't compress, so notify user just a csv is available.
					add_settings_error(
						'shareasale_wc_tracker_zip',
						esc_attr( 'datafeed-zip' ),
						$this->errors->get_error_message( 'compress' ) . ' You will need to manually compress the generated csv file into a gz or zip archive before uploading to ShareASale.',
						'notice-warning'
					);
				}

				$path             = esc_url( $file . ( $compressed ? '.zip' : '' ) );
				$product_warnings = array(
					'sku'         => array(
						'messages' => $this->errors->get_error_messages( 'sku' ),
						'data'     => $this->errors->get_error_data( 'sku' ),
					),
					'url'         => array(
						'messages' => $this->errors->get_error_messages( 'url' ),
						'data'     => $this->errors->get_error_data( 'url' ),
					),
					'price'       => array(
						'messages' => $this->errors->get_error_messages( 'price' ),
						'data'     => $this->errors->get_error_data( 'price' ),
					),
					'category'    => array(
						'messages' => $this->errors->get_error_messages( 'category' ),
						'data'     => $this->errors->get_error_data( 'category' ),
					),
					'subcategory' => array(
						'messages' => $this->errors->get_error_messages( 'subcategory' ),
						'data'     => $this->errors->get_error_data( 'subcategory' ),
					),
					/*
					just get first merchant_id error code message since rest will be identical, store in an array for uniformity
					*/
					'merchant_id' => array(
						'messages' => $this->errors->get_error_message( 'merchant_id' ) ? array( $this->errors->get_error_message( 'merchant_id' ) ) : array(),
					),
				);

				$logged = $this->logger->log( $path, maybe_serialize( $product_warnings ), $product_count, date( 'Y-m-d H:i:s' ) );

				add_settings_error(
					'shareasale_wc_tracker_success',
					esc_attr( 'datafeed-success' ),
					'Generating complete! Download from the link in the table below.',
					'updated'
				);
				settings_errors( 'shareasale_wc_tracker_success' );
				settings_errors( 'shareasale_wc_tracker_zip' );
			} else {
				//couldn't even create csv...
				add_settings_error(
					'shareasale_wc_tracker_csv',
					esc_attr( 'datafeed-csv' ),
					$this->errors->get_error_message( 'write' ) . ' Please contact your webhost for more information.'
				);
				settings_errors( 'shareasale_wc_tracker_csv' );
				return false;
			}
		} else {
			add_settings_error(
				'shareasale_wc_tracker_products',
				esc_attr( 'datafeed-products' ),
				'We found zero products to export! Start by adding one <a href="' . admin_url( 'post-new.php?post_type=product' ) . '">here</a>.',
				'notice-warning'
			);
			settings_errors( 'shareasale_wc_tracker_products' );
			return false;
		}
		//needs to return final local path for later possible FTP upload purposes
		return array( 'path' => $path, 'id' => $logged );
	}

	private function get_all_product_posts_ids() {
		//visibility is stored differently in WooCommerce v2 vs v3
		if ( version_compare( $this->wc_version, '3.0' ) >= 0 ) {
			$query['name']  = 'tax_query';
			$query['values'] = array(
				array(
		        	'taxonomy' => 'product_visibility',
		        	'field'    => 'name',
		        	'terms'    => 'exclude-from-catalog',
		        	'operator' => 'NOT IN',
		        ),
			);
		} else {
			$query['name']   = 'meta_query';
			$query['values'] = array(
				'relation' => 'OR',
				array(
		        	'key'     => '_visibility',
		        	'value'   => array( 'hidden', 'search' ),
		        	'compare' => 'NOT IN',
		        ),
				array(
					'key'     => '_visibility',
		        	'value'   => array( 'hidden', 'search' ),
					'compare' => 'NOT EXISTS',
				),
			);
		}

		//get all products and variations that are visible
		$product_posts = get_posts(
			array(
				'fields'       => 'ids',
				'post_type'    => array( 'product', 'product_variation' ),
				'numberposts'  => -1,
				'post_status'  => 'publish',
				'order'        => 'ASC',
				'orderby'      => 'ID',
				$query['name'] => $query['values'],
			    'post_parent__not_in' => get_posts(
					array(
						'fields'       => 'ids',
						'post_type'    => 'product',
						'numberposts'  => -1,
						'post_status'  => 'any',
						'order'        => 'ASC',
						'orderby'      => 'ID',
						$query['name'] => array( array_slice( $query['values'][0], 0, -1 ) ),
					)
				),
			)
		);

		return $product_posts;
	}

	private function get_cross_sell_skus( $product ) {
		$cross_sell_skus = array();

		if ( version_compare( $this->wc_version, '3.0' ) >= 0 ) {
			$cross_sell_product_ids = $product->get_cross_sell_ids();
		} else {
			$cross_sell_product_ids = $product->get_cross_sells();
		}

		foreach ( $cross_sell_product_ids as $cross_sell_product_id ) {
			$cross_sell_skus[] = get_post_meta( $cross_sell_product_id, '_sku', true );
		}

		return $cross_sell_skus;
	}

	private function make_row( $product_row ) {
		//this is just here because of WooCommerce's own "WooCommerce Product Add-ons" optional plugin uses a global $product instead of $this for the woocommerce_product_add_to_cart_url filter that WC_Product::add_to_cart_url() runs... otherwise $product is null and causes a fatal error...
		global $product;
		$product = $product_row;

		$options           = get_option( 'shareasale_wc_tracker_options' );
		$merchant_id       = @$options['merchant-id'];
		$store_id          = @$options['store-id'];
		$product_id        = $product_row->get_id();
		$category          = get_post_meta( $product_id, 'shareasale_wc_tracker_datafeed_product_category', true ) ?: @$options['default-category'];
		$subcategory       = get_post_meta( $product_id, 'shareasale_wc_tracker_datafeed_product_subcategory', true ) ?: @$options['default-subcategory'];
		$merchant_taxonomy = wc_get_product_terms( $product_id, 'product_cat',
			array(
				'orderby' => 'parent',
				'fields' => 'names',
			)
		);

		$row = array(
				//required
				'SKU'                                   => $product_row->get_sku() ? $product_row->get_sku() : $this->errors->add(
					'sku',
					'<a target="_blank" href="' . esc_url( get_edit_post_link( $product_id, '' ) ) . '">' . esc_html( $product_id ) . '</a> is missing a SKU.',
					$this->push_error_data( 'sku', $product_id )
				),
				'Name'                                  => $product_row->get_title(),
				//required
				'URL'                                   => $product_row->get_permalink() ? $product_row->get_permalink() : $this->errors->add(
					'url',
					'<a target="_blank" href="' . esc_url( get_edit_post_link( $product_id, '' ) ) . '">' . esc_html( $product_id ) . '</a> is missing a URL.',
					$this->push_error_data( 'url', $product_id )
				),
				//required
				'Price'                                 => $product_row->get_price() ? $product_row->get_price() : $this->errors->add(
					'price',
					'<a target="_blank" href="' . esc_url( get_edit_post_link( $product_id, '' ) ) . '">' . esc_html( $product_id ) . '</a> is missing a price.',
					$this->push_error_data( 'price', $product_id )
				),
				'Retailprice'                           => $product_row->get_regular_price(),
				'FullImage'                             => get_the_post_thumbnail_url( $product_id, 'shop_single' ),
				'ThumbnailImage'                        => get_the_post_thumbnail_url( $product_id, 'shop_thumbnail' ),
				'Commission'                            => '',
				//required
				'Category'                              => $category ? $category : $this->errors->add(
					'category',
					'<a target="_blank" href="' . esc_url( get_edit_post_link( $product_id, '' ) ) . '">' . esc_html( $product_id ) . '</a> is missing a ShareASale category number.',
					$this->push_error_data( 'category', $product_id )
				),
				//required
				'Subcategory'                           => $subcategory ? $subcategory : $this->errors->add(
					'subcategory',
					'<a target="_blank" href="' . esc_url( get_edit_post_link( $product_id, '' ) ) . '">' . esc_html( $product_id ) . '</a> is missing a ShareASale subcategory number.',
					$this->push_error_data( 'subcategory', $product_id )
				),
				'Description'                           => version_compare( $this->wc_version, '3.0' ) >= 0 ? wp_strip_all_tags( preg_replace( '/\[(.*?)\]/', '', $product_row->get_description() ) ) : wp_strip_all_tags( preg_replace( '/\[(.*?)\]/', '', $product_row->get_post_data()->post_content ) ),
				'SearchTerms'                           => version_compare( $this->wc_version, '3.0' ) >= 0 ? wp_strip_all_tags( wc_get_product_tag_list( $product_id, ',' ) ) : wp_strip_all_tags( $product_row->get_tags( ',' ) ),
				'Status'                                => $product_row->is_in_stock() ? 'instock' : 'soldout',
				//required
				'MerchantID'                            => ! empty( $merchant_id ) ? $merchant_id : $this->errors->add(
					'merchant_id',
					'No <a href="' . esc_url( admin_url( 'admin.php?page=shareasale_wc_tracker' ) ) . '">Merchant ID</a> entered yet.'
				),
				'Custom1'                               => '',
				'Custom2'                               => '',
				'Custom3'                               => '',
				'Custom4'                               => '',
				'Custom5'                               => '',
				'Manufacturer'                          => $product_row->get_attribute( 'manufacturer' ),
				'PartNumber'                            => $product_row->get_attribute( 'partnumber' ),
				'MerchantCategory'                      => reset( $merchant_taxonomy ),
				'MerchantSubcategory'                   => next( $merchant_taxonomy ),
				'ShortDescription'                      => version_compare( $this->wc_version, '3.0' ) >= 0 ? wp_strip_all_tags( preg_replace( '/\[(.*?)\]/', '', $product_row->get_short_description() ) ) : '',
				'ISBN'                                  => $product_row->get_attribute( 'ISBN' ),
				'UPC'                                   => $product_row->get_attribute( 'UPC' ),
				//array_filter used without callback argument to remove false values from array
				'CrossSell'                             => implode( ',', array_filter( $product_row->cross_sell_skus ) ),
				'MerchantGroup'                         => next( $merchant_taxonomy ),
				'MerchantSubgroup'                      => next( $merchant_taxonomy ),
				'CompatibleWith'                        => '',
				'CompareTo'                             => '',
				'QuantityDiscount'                      => '',
				'Bestseller'                            => $product_row->is_featured() ? 1 : 0,
				'AddToCartURL'                          => version_compare( $this->wc_version, '3.0' ) >= 0 ? $product_row->add_to_cart_url() : $product_row->add_to_cart_url,
				'ReviewsRSSURL'                         => '',
				'Option1'                               => '',
				'Option2'                               => '',
				'Option3'                               => '',
				'Option4'                               => '',
				'Option5'                               => '',
				'customCommissions'                     => '',
				'customCommissionIsFlatRate'            => 0,
				'customCommissionNewCustomerMultiplier' => 1,
				'mobileURL'                             => '',
				'mobileImage'                           => get_the_post_thumbnail_url( $product_id, 'shop_single' ),
				'mobileThumbnail'                       => get_the_post_thumbnail_url( $product_id, 'shop_thumbnail' ),
				'ReservedForFutureUse'                  => '',
				'ReservedForFutureUse'                  => '',
				'ReservedForFutureUse'                  => '',
				'ReservedForFutureUse'                  => '',
			);

		if ( $store_id ) {
			$row = array_slice( $row, 0, 19, true ) + array( 'storeID' => $store_id ) + array_slice( $row, 19, count( $row ) -19, true );
		}
		return array_map( array( $this, 'wrap_row' ), $row );
	}

	private function push_error_data( $code, $data ) {
		$error_data = $this->errors->get_error_data( $code );
		if ( is_array( $error_data ) ) {
			$error_data[] = $data;
			return $error_data;
		} else {
			return array( $data );
		}
	}

	private function wrap_row( $value ) {
		$value = trim( $value );
		return '"' . str_replace( '"', '""', $value ) . '"';
	}

	private function write( $file, $content ) {
		if ( ! $this->filesystem->put_contents( $file, $content, FS_CHMOD_FILE ) ) {
			//unfortunately WP_Filesystem doesn't have a more useful WP_Error for put_contents()...
			$this->errors->add( 'write', 'Couldn\'t write CSV file.', $file );
			return false;
		}

		return $this;
	}

	private function compress( $file ) {
		if ( ! class_exists( 'ZipArchive' ) ) {
			$this->errors->add( 'compress', 'Couldn\'t compress because PHP Zip extension not installed or enabled.' );
			return false;
		}

		$zip        = new ZipArchive;
		$compressed = $file . '.zip';
		$dir        = dirname( $file );
		//use the WP_Filesystem instance to temporary 0777 chmod the /datafeeds directory so less of a chance ZipArchive::open(), ::addFile(), or ::close() fail
		$this->filesystem->chmod( $dir, 0777 );

		if ( true !== $zip->open( $compressed, ZipArchive::CREATE ) ) {
			$this->errors->add( 'compress', 'Couldn\'t compress because the zip archive cannot be opened.', $compressed );
			$this->filesystem->chmod( $dir, FS_CHMOD_DIR );
			return false;
		}

		if ( ! $zip->addFile( $file, basename( $file ) ) ) {
		    $this->errors->add( 'compress', 'Couldn\'t compress because CSV file not found.', $file );
			$this->filesystem->chmod( $dir, FS_CHMOD_DIR );
			return false;
		}

		if ( ! $zip->close() ) {
		    $this->errors->add( 'compress', 'Couldn\'t compress because the zip archive cannot be closed.', $compressed );
			$this->filesystem->chmod( $dir, FS_CHMOD_DIR );
			return false;
		}

		//delete leftover csv now compressed, and remove any previous logged entries of it now that a zip version exists
		//change /datafeeds back to defined directory permissions for WP config...
		$this->filesystem->chmod( $dir, FS_CHMOD_DIR );
		$this->filesystem->delete( $file );
		$this->logger->unlog( $file );
		return $this;
	}

	public function clean_up( $dir, $days_age ) {
		$files = $this->filesystem->dirlist( $dir );

		foreach ( $files as $file_details ) {
			$file = trailingslashit( $dir ) . $file_details['name'];
			if ( time() - $file_details['lastmodunix'] > ( 60 * 60 * 24 * $days_age ) ) {
				$this->filesystem->delete( $file );
				$this->logger->unlog( $file );
			}
		}
	}
}
