<?php

defined( 'ABSPATH' ) or exit;

if ( !class_exists( 'WT_Fb_Catalog_Manager_Settings' ) ) :

	/**
	 * Uninstall feedback class
	 */
	class WT_Fb_Catalog_Manager_Settings {

		/** @var string base settings page ID */
		const PAGE_ID = 'webtoffee-product-feed';

		/** @var string Facebook App ID */
		const CLIENT_ID = '235491744456870';
		
		const OAUTH_URL = 'https://facebook.com/dialog/oauth';

		const DISCONNECT_ACTION = 'wt_facebook_disconnect';
		const OPTION_FB_BUSINESS_ID = 'wt_facebook_business_id';
		const OPTION_EXTERNAL_BUSINESS_ID = 'wc_facebook_external_business_id';
		const OPTION_FB_CATALOG_ID = 'wt_facebook_catalog_id';
		const OPTION_USER_ID = 'wt_facebook_user_id';
		const OPTION_ACCESS_TOKEN = 'wt_facebook_access_token';
		
		const OPTION_FB_CONNECTED_TIME = 'wt_facebook_connected_time';

		/**
		 * settings constructor.
		 *
		 */
		public function __construct() {
			
		}

		public function wt_fbfeed_process_upload() {


			$product_data	 = [];
			$wc_fbfeed		 = new WT_Facebook_Catalog_Product();
			$args			 = array(
				'post_type'		 => array( 'product', 'product_variation' ),
				'posts_per_page' => -1,
				'fields'		 => 'ids'
			);
			$loop			 = new WP_Query( $args );

			foreach ( $loop->posts as $product_id ) {
				$product_item_data = $wc_fbfeed->process_item_update( $product_id );

				if ( !empty( $product_item_data[ 'data' ][ 'price' ] ) ) {
					$product_data[] = $product_item_data;
				}
			}

			$catalog_access_token = $this->get_access_token();

			$request_body = [
				"headers"	 => [
					"Authorization"	 => "Bearer {$catalog_access_token}",
					"Content-type"	 => "application/json",
					"accept"		 => "application/json" ],
				"body"		 => json_encode( [
					"allow_upsert"	 => true,
					"requests"		 => json_encode( $product_data )
				] ),
			];


			$this->wt_log_data_change( 'wt-feed-upload', json_encode( $request_body ) );
			$catalog_id		 = get_option( 'wt_facebook_catalog_id' );

			$batch_url		 = "https://graph.facebook.com/v17.0/$catalog_id/batch";
			$batch_response	 = wp_remote_post( $batch_url, $request_body );

			$this->wt_log_data_change( 'wt-feed-upload', json_encode( $batch_response ) );

			exit;
		}

		public function wt_fbfeed_category_mapping() {


			if ( count( $_POST ) && isset( $_POST[ 'map_to' ] ) ) {

				check_admin_referer( 'wt-category-mapping' );

				$mapping_option = 'wt_fbfeed_category_mapping';

				$mapping_data = array_map( 'absint', ($_POST[ 'map_to' ]) );
				foreach ( $mapping_data as $local_category_id => $fb_category_id ) {
					if ( $fb_category_id )
						update_term_meta( $local_category_id, 'wt_fb_category', $fb_category_id );
				}

				// Delete product categories dropdown cache
				wp_cache_delete( 'wt_fbfeed_dropdown_product_categories' );

				if ( update_option( $mapping_option, $mapping_data, false ) ) { // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
					update_option( 'wt_mapping_message', esc_html__( 'Mapping Added Successfully', 'webtoffee-product-feed' ), false );
					wp_safe_redirect( admin_url( 'admin.php?page=wt-fbfeed-category-mapping&wt_mapping_message=success' ) );
					die();
				} else {
					update_option( 'wt_mapping_message', esc_html__( 'Failed To Add Mapping', 'webtoffee-product-feed' ), false );
					wp_safe_redirect( admin_url( 'admin.php?page=wt-fbfeed-category-mapping&wt_mapping_message=error' ) );
					die();
				}
			}
			require plugin_dir_path( dirname( __FILE__ ) ) . 'fbcatalog/partials/wt-fbfeed-category-mapping.php';
			
		}

		/**
		 * Get All Default WooCommerce Attributes
		 * @return bool|array
		 */
		public static function get_all_wc_attributes() {
			global $wpdb;
			$info				 = array();
			//Load the main attributes
			$global_attributes	 = wc_get_attribute_taxonomy_labels();
			if ( count( $global_attributes ) ) {
				foreach ( $global_attributes as $key => $value ) {
					$info[ 'wt_attr_pa_' . $key ] = $value;
				}
			}

			return $info;
		}

		/**
		 * Load all WooCommerce attributes into an option
		 */
		public function load_attributes() {
			# Get All WooCommerce Attributes
			$wt_wc_attributes = self::get_all_wc_attributes();
			update_option( 'wt_wc_attributes', $wt_wc_attributes );
		}

		public function get_settings_url() {

			return admin_url( 'admin.php?page=webtoffee-product-feed' );
		}

		/**
		 * Gets the FB category by id.
		 *
		 * @since 2.0.0
		 *
		 * @return $fb_category
		 */
		public static function get_fb_categories( $fb_category_id ) {

			// Facebook all categories
                        $fb_category_list = wp_cache_get('wt_fbfeed_fb_product_categories_array');

                        if (false === $fb_category_list) {
                            $fb_category_list = Webtoffee_Product_Feed_Sync_Facebook::get_category_array();
                            wp_cache_set('wt_fbfeed_fb_product_categories_array', $fb_category_list, '', WEEK_IN_SECONDS);
                        }


                        $fb_category = isset($fb_category_list[$fb_category_id]) ? $fb_category_list[$fb_category_id] : '';

			return apply_filters( 'wt_fb_categories', $fb_category );
		}
		
	}

	new WT_Fb_Catalog_Manager_Settings();
endif;
