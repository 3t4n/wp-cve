<?php
/**
* WordPress Importer class for managing the import process of a CSV file
*/
if ( ! class_exists( 'PIECFW_Product_Import' ) ) {
	return;
}
class PIECFW_Product_Variation_Import extends PIECFW_Product_Import {
	/**
	* Constructor
	*/
	public function __construct() {
		parent::__construct();
		$this->import_page = 'piecfw_variation';

		add_filter( 'import_post_meta_value', array( $this, 'filter_post_meta_value' ), 10, 2 );
	}

	/**
	* Filter post meta values.
	*/
	public function filter_post_meta_value( $value, $key ) {
		// Format _sale_price_dates_from to timestamp
		if ( '_sale_price_dates_from' === $key ) {
			$value = strtotime( $value );
		}

		if ( '_sale_price_dates_to' === $key ) {
			$value = strtotime( $value );
		}

		return $value;
	}

	/**
	* Create new posts based on import information
	*/
	public function process_product( $post, $imported_file='' ) {
		global $wpdb;
		try{
			wp_suspend_cache_invalidation( true );
			$timezone_format = _x( 'Y-m-d H:i:s', 'timezone date format' );
			$created_at = date_i18n( $timezone_format );

			$merging               = ( ! empty( $post['merging'] ) && $post['merging'] ) ? true : false;
			$processing_product_id = absint( $post['post_id'] );
			$insert_meta_data      = array();

			$log_id = $wpdb->get_var( $wpdb->prepare( "SELECT log_id FROM ".$wpdb->prefix."piecfw_product_import_data_log WHERE product_sku = %s AND file_name = %s", $post['sku'],  $imported_file) );

			if($log_id){
				return;
			}

			if ( empty( $post['post_parent'] ) ) {
				$this->add_import_result( 'skipped', __( 'No product variation parent set', PIECFW_TRANSLATE_NAME ), $processing_product_id, 'Not set', $post['sku'] );
				PIECFW_Product_Import_Export::log( __('> Skipping - no post parent set.', PIECFW_TRANSLATE_NAME) );

				//Insert Data Log
				if(!empty($imported_file)){
					$wpdb->insert($wpdb->prefix.'piecfw_product_import_data_log', array(
			            'file_name' => $imported_file,
			            'product_id' => $processing_product_id,
			            'product_sku' => $post['sku'],
			            'product_name' => get_the_title( $post['post_parent'] ),
			            'product_type' => '',
			            'status' => 0, 
			            'status_message' => __( 'No product variation parent set', PIECFW_TRANSLATE_NAME ),
			            'created_at' => $created_at
			        ));
				}

				return;
			}

			if ( ! empty( $processing_product_id ) && isset( $this->processed_posts[ $processing_product_id ] ) ) {
				$this->add_import_result( 'skipped', __( 'Product variation already processed', PIECFW_TRANSLATE_NAME ), $processing_product_id, get_the_title( $post['post_parent'] ), $post['sku'] );
				PIECFW_Product_Import_Export::log( __('> Post ID already processed. Skipping.', PIECFW_TRANSLATE_NAME) );

				//Insert Data Log
				if(!empty($imported_file)){
					$wpdb->insert($wpdb->prefix.'piecfw_product_import_data_log', array(
			            'file_name' => $imported_file,
			            'product_id' => $processing_product_id,
			            'product_sku' => $post['sku'],
			            'product_name' => get_the_title( $post['post_parent'] ),
			            'product_type' => '',
			            'status' => 0, 
			            'status_message' => __( 'Product variation already processed', PIECFW_TRANSLATE_NAME ),
			            'created_at' => $created_at
			        ));
				}

				return;
			}

			if ( isset( $post['post_status'] ) && 'auto-draft' === $post['post_status'] ) {
				$this->add_import_result( 'skipped', __( 'Skipping auto-draft', PIECFW_TRANSLATE_NAME ), $processing_product_id, get_the_title( $post['post_parent'] ), $post['sku'] );
				PIECFW_Product_Import_Export::log( __('> Skipping auto-draft.', PIECFW_TRANSLATE_NAME) );

				//Insert Data Log
				if(!empty($imported_file)){
					$wpdb->insert($wpdb->prefix.'piecfw_product_import_data_log', array(
			            'file_name' => $imported_file,
			            'product_id' => $processing_product_id,
			            'product_sku' => $post['sku'],
			            'product_name' => get_the_title( $post['post_parent'] ),
			            'product_type' => '',
			            'status' => 0, 
			            'status_message' => __( 'Skipping auto-draft', PIECFW_TRANSLATE_NAME ),
			            'created_at' => $created_at
			        ));
				}

				return;
			}

			$post_parent = (int) $post['post_parent'];
			$post_parent_exists = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE ID = %d", $post_parent ) );

			if ( ! $post_parent_exists ) {
				$this->add_import_result( 'failed', __( 'Variation parent does not exist', PIECFW_TRANSLATE_NAME ), $processing_product_id, 'Does not exist', $post['sku'] );
				PIECFW_Product_Import_Export::log( sprintf( __('> Variation parent does not exist! (#%d)', PIECFW_TRANSLATE_NAME), $post_parent ) );

				//Insert Data Log
				if(!empty($imported_file)){
					$wpdb->insert($wpdb->prefix.'piecfw_product_import_data_log', array(
			            'file_name' => $imported_file,
			            'product_id' => $processing_product_id,
			            'product_sku' => $post['sku'],
			            'product_name' => get_the_title( $post['post_parent'] ),
			            'product_type' => '',
			            'status' => 0, 
			            'status_message' => __( 'Variation parent does not exist', PIECFW_TRANSLATE_NAME ),
			            'created_at' => $created_at
			        ));
				}

				return;
			}

			// Check post type to avoid conflicts with IDs
			if ( $merging && get_post_type( $processing_product_id ) !== 'product_variation' ) {
				$this->add_import_result( 'skipped', __( 'Post is not a product variation', PIECFW_TRANSLATE_NAME ), $processing_product_id, 'Not a variation', $post['sku'] );
				PIECFW_Product_Import_Export::log( sprintf( __('> &#8220;%s&#8221; is not a product variation.', PIECFW_TRANSLATE_NAME), $processing_product_id ), true );

				//Insert Data Log
				if(!empty($imported_file)){
					$wpdb->insert($wpdb->prefix.'piecfw_product_import_data_log', array(
			            'file_name' => $imported_file,
			            'product_id' => $processing_product_id,
			            'product_sku' => $post['sku'],
			            'product_name' => get_the_title( $post['post_parent'] ),
			            'product_type' => '',
			            'status' => 0, 
			            'status_message' => __( 'Post is not a product variation', PIECFW_TRANSLATE_NAME ),
			            'created_at' => $created_at
			        ));
				}

				unset( $post );
				return;
			}

			if ( $merging ) {

				// Only merge fields which are set
				$post_id = $processing_product_id;

				PIECFW_Product_Import_Export::log( sprintf( __('> Merging post ID %s.', PIECFW_TRANSLATE_NAME), $post_id ) );

				$postdata = array( 'ID' => $post_id );
				if (!empty($post['post_date'])) $postdata['post_date'] = date("Y-m-d H:i:s", strtotime( $post['post_date'] ) );
				if (!empty($post['post_date_gmt'])) $postdata['post_date_gmt'] = date("Y-m-d H:i:s", strtotime( $post['post_date_gmt'] ) );
				if (!empty($post['post_status'])) $postdata['post_status'] = $post['post_status'];
				if (!empty($post['menu_order'])) $postdata['menu_order'] = $post['menu_order'];
				$postdata['post_parent'] = $post_parent;

				if ( sizeof( $postdata ) ) {
					if ( wp_update_post( $postdata ) ) {
						PIECFW_Product_Import_Export::log( __( '> Merged post data: ', PIECFW_TRANSLATE_NAME ) . print_r( $postdata, true ) );
					} else {
						PIECFW_Product_Import_Export::log( __( '> Failed to merge post data: ', PIECFW_TRANSLATE_NAME ) . print_r( $postdata, true ) );
					}
				}

			} else {

				$processing_product_sku   = '';
				if ( ! empty( $post['sku'] ) ) {
					$processing_product_sku = $post['sku'];
				}

				if ( $this->variation_exists( $post_parent, $processing_product_id, $processing_product_sku ) ) {
					$this->add_import_result( 'skipped', __( 'Variation already exists', PIECFW_TRANSLATE_NAME ), $processing_product_id, get_the_title( $post['post_parent'] ), $processing_product_sku );
					PIECFW_Product_Import_Export::log( sprintf( __( '> &#8220;%s&#8221; already exists.', PIECFW_TRANSLATE_NAME ), esc_html( $post['post_title'] ) ), true );

					//Insert Data Log
					if(!empty($imported_file)){
						$wpdb->insert($wpdb->prefix.'piecfw_product_import_data_log', array(
				            'file_name' => $imported_file,
				            'product_id' => $processing_product_id,
				            'product_sku' => $processing_product_sku,
				            'product_name' => get_the_title( $post['post_parent'] ),
				            'product_type' => '',
				            'status' => 0, 
				            'status_message' => __( 'Variation already exists', PIECFW_TRANSLATE_NAME ),
				            'created_at' => $created_at
				        ));
					}

					unset( $post );
					return;
				}

				// Insert product
				PIECFW_Product_Import_Export::log( __('> Inserting variation.', PIECFW_TRANSLATE_NAME) );

				$postdata = array(
					'import_id' 	=> $processing_product_id,
					'post_date' 	=> ( $post['post_date'] ) ? date( 'Y-m-d H:i:s', strtotime( $post['post_date'] )) : '',
					'post_date_gmt' => ( $post['post_date_gmt'] ) ? date( 'Y-m-d H:i:s', strtotime( $post['post_date_gmt'] )) : '',
					'post_status' 	=> $post['post_status'],
					'post_parent' 	=> $post_parent,
					'menu_order' 	=> $post['menu_order'],
					'post_type' 	=> 'product_variation',
				);

				$post_id = wp_insert_post( $postdata, true );

				if ( is_wp_error( $post_id ) ) {

					$this->add_import_result( 'failed', __( 'Failed to import product variation', PIECFW_TRANSLATE_NAME ), $processing_product_id, get_the_title( $post['post_parent'] ), $post['sku'] );

					PIECFW_Product_Import_Export::log( sprintf( __( 'Failed to import product &#8220;%s&#8221;', PIECFW_TRANSLATE_NAME ), esc_html($post['post_title']) ) );

					//Insert Data Log
					if(!empty($imported_file)){
						$wpdb->insert($wpdb->prefix.'piecfw_product_import_data_log', array(
				            'file_name' => $imported_file,
				            'product_id' => $processing_product_id,
				            'product_sku' => $post['sku'],
				            'product_name' => get_the_title( $post['post_parent'] ),
				            'product_type' => '',
				            'status' => 0, 
				            'status_message' => __( 'Failed to import product variation', PIECFW_TRANSLATE_NAME ),
				            'created_at' => $created_at
				        ));
					}

					return;

				} else {
					PIECFW_Product_Import_Export::log( sprintf( __('> Inserted - post ID is %s.', PIECFW_TRANSLATE_NAME ), $post_id ) );

					// Set post title now we have an ID
					$postdata['ID']         = $post_id;
					$postdata['post_title'] = sprintf( __( 'Variation #%s of %s', 'woocommerce' ), $post_id, get_the_title( $post_parent ) );
					wp_update_post( $postdata );
				}
			}

			// map pre-import ID to local ID
			if ( empty( $processing_product_id ) ) {
				$processing_product_id = (int) $post_id;
			}

			$this->processed_posts[ intval( $processing_product_id ) ] = (int) $post_id;
			$this->process_terms( $post_id, $post['terms'] );

			// Process post meta
			if ( ! empty( $post['postmeta'] ) && is_array( $post['postmeta'] ) ) {
				foreach ( $post['postmeta'] as $meta ) {
					if ( $key = apply_filters( 'import_post_meta_key', $meta['key'] ) ) {
						/**
						 * Filter import_post_meta_value.
						 *
						 * To manipulate the value from the import
						 * @since 1.10.11
						 */
						$insert_meta_data[ $key ] = apply_filters( 'import_post_meta_value', maybe_unserialize( $meta['value'] ), $key );
					}
				}
			}

			// Import images and add to post
			if ( ! empty( $post['images'] ) ) {

				$featured = true;

				if ( $merging ) {

					// Remove old
					delete_post_meta( $post_id, '_thumbnail_id' );

					// Delete old attachments
					$attachments = get_posts( 'post_parent=' . $post_id . '&post_type=attachment&fields=ids&post_mime_type=image&numberposts=-1' );

					foreach ( $attachments as $attachment ) {

						$url = wp_get_attachment_url( $attachment );

						if ( in_array( $url, $post['images'] ) ) {
							if ( $url == $post['images'][0] ) {
								$insert_meta_data['_thumbnail_id'] = $attachment;
							}
							unset( $post['images'][ array_search( $url, $post['images'] ) ] );
						} else {
							// Detach
							$attachment_post = array();
							$attachment_post['ID'] = $attachment;
							$attachment_post['post_parent'] = '';
							wp_update_post( $attachment_post );
						}
					}

					PIECFW_Product_Import_Export::log( __( '> > Old images processed', PIECFW_TRANSLATE_NAME ) );

				}

				if ( $post['images'] ) foreach ( $post['images'] as $image ) {

					PIECFW_Product_Import_Export::log( sprintf( __( '> > Importing image "%s"', PIECFW_TRANSLATE_NAME ), $image ) );

					$wp_filetype = wp_check_filetype( basename( $image ), null );
					$wp_upload_dir = wp_upload_dir();
					$filename = basename( $image );

					$attachment = array(
						 'post_mime_type' 	=> $wp_filetype['type'],
						 'post_title' 		=> preg_replace('/\.[^.]+$/', '', basename( $filename )),
						 'post_content' 	=> '',
						 'post_status' 		=> 'inherit'
					);

					$attachment_id = $this->process_attachment( $attachment, $image, $post_id );

					if ( ! is_wp_error( $attachment_id ) ) {
						if ( $featured ) {
							$insert_meta_data['_thumbnail_id'] = $attachment_id;
						}

						update_post_meta( $attachment_id, '_woocommerce_exclude_image', 0 );

						$featured = false;
					} else {
						PIECFW_Product_Import_Export::log( '> > ' . $attachment_id->get_error_message() );
					}
				}

				PIECFW_Product_Import_Export::log( __( '> > Images set', PIECFW_TRANSLATE_NAME ) );
			}

			// Import GPF
			if ( ! empty( $post['gpf_data'] ) && is_array( $post['gpf_data'] ) ) {
				$insert_meta_data['_woocommerce_gpf_data'] = $post['gpf_data'];
			}

			// Delete existing meta first
			$wpdb->query( 'START TRANSACTION' );
			$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->postmeta WHERE meta_key IN ( '" . implode( "','", array_map( 'esc_sql', array_keys( $insert_meta_data ) ) ) . "' ) and post_id = %d", $post_id ) );

			$groups_active = class_exists( 'Groups_WS' );

			// When attributes come as uppercase, it cause variations combinations to bork.
			$insert_meta_data = array_change_key_case( $insert_meta_data, CASE_LOWER );

			// Format meta data
			foreach ( $insert_meta_data as $key => $value ) {
				$meta_key      = wp_unslash( $key );
				$meta_value    = wp_unslash( $value );
				$meta_value    = sanitize_meta( $meta_key, $meta_value, 'post' );

				if ( $groups_active && '_groups_variation_groups' === $key && ! empty( $value ) ) {
					foreach ( $value as $group ) {
						$meta_values[] = $wpdb->prepare( "( %d, %s, %s )", $post_id, $meta_key, $group );
					}

					continue;
				}

				if ( $groups_active && '_groups_variation_groups_remove' === $key && ! empty( $value ) ) {
					foreach ( $value as $group ) {
						$meta_values[] = $wpdb->prepare( "( %d, %s, %s )", $post_id, $meta_key, $group );
					}

					continue;
				}

				$meta_value    = maybe_serialize( $meta_value );
				$meta_values[] = $wpdb->prepare( "( %d, %s, %s )", $post_id, $meta_key, $meta_value );
			}

			// Then insert meta data
			$wpdb->query( "INSERT INTO $wpdb->postmeta ( post_id, meta_key, meta_value ) VALUES " . implode( ',', $meta_values ) );
			$wpdb->query( 'COMMIT' );

			if ( $merging ) {
				$this->add_import_result( 'merged', 'Merge successful', $post_id, get_the_title( $post_parent ), $post['sku'] );
				PIECFW_Product_Import_Export::log( sprintf( __('> Finished merging variation ID %s.', PIECFW_TRANSLATE_NAME), $post_id ) );

				//Insert Data Log
				if(!empty($imported_file)){
					$wpdb->insert($wpdb->prefix.'piecfw_product_import_data_log', array(
			            'file_name' => $imported_file,
			            'product_id' => $post_id,
			            'product_sku' => $post['sku'],
			            'product_name' => get_the_title( $post['post_parent'] ),
			            'product_type' => '',
			            'status' => 0, 
			            'status_message' => __( 'Merge successful', PIECFW_TRANSLATE_NAME ),
			            'created_at' => $created_at
			        ));
				}
			} else {
				$this->add_import_result( 'imported', 'Import successful', $post_id, get_the_title( $post_parent ), $post['sku'] );
				PIECFW_Product_Import_Export::log( sprintf( __('> Finished importing variation ID %s.', PIECFW_TRANSLATE_NAME), $post_id ) );

				//Insert Data Log
				if(!empty($imported_file)){
					$wpdb->insert($wpdb->prefix.'piecfw_product_import_data_log', array(
			            'file_name' => $imported_file,
			            'product_id' => $post_id,
			            'product_sku' => $post['sku'],
			            'product_name' => get_the_title( $post['post_parent'] ),
			            'product_type' => '',
			            'status' => 0, 
			            'status_message' => __( 'Import successful', PIECFW_TRANSLATE_NAME ),
			            'created_at' => $created_at
			        ));
				}
			}

			wp_suspend_cache_invalidation( false );
			clean_post_cache( $post_id );

			unset( $post );
		}
		catch(Error $e){
			PIECFW_Product_Import_Export::log( sprintf( __('> Error writing to database: %s.', PIECFW_TRANSLATE_NAME), $e->getMessage()." = ".json_encode($post) ) );
		}
	}

	/**
	* Checks to see if a variation exists for a specific parent based on ID or SKU
	*/
	public function variation_exists( $parent_id, $id, $sku = '' ) {
		global $wpdb;

		// SKU Check
		if ( $sku ) {
			$post_exists_sku = $wpdb->get_var( $wpdb->prepare( "
				SELECT $wpdb->posts.ID
				FROM $wpdb->posts
				LEFT JOIN $wpdb->postmeta ON ( $wpdb->posts.ID = $wpdb->postmeta.post_id )
				WHERE $wpdb->posts.post_status IN ( 'publish', 'private', 'draft', 'pending', 'future' )
				AND $wpdb->postmeta.meta_key = '_sku' AND $wpdb->postmeta.meta_value = '%s'
			", $sku ) );

			if ( $post_exists_sku ) {
				return true;
			}
		}

		// ID check
		$query = $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = 'product_variation' AND post_parent = %d AND ID = %d AND post_status IN ( 'publish', 'private', 'draft', 'pending', 'future' )", $parent_id, $id );
		$posts_that_exist = $wpdb->get_col( $query );
		if ( $posts_that_exist ) {
			return true;
		}

		return false;
	}

	/**
	* Parses the CSV file and prepares us for the task of processing parsed data
	*/
	function import_start( $file, $mapping, $start_pos, $end_pos ) {
		PIECFW_Product_Import_Export::log( __( 'Parsing product variations CSV.', PIECFW_TRANSLATE_NAME ) );

		$this->parser = new PIECFW_Parser( 'product_variation' );

		list( $this->parsed_data, $this->raw_headers, $position ) = $this->parser->parse_data( $file, $this->delimiter, $mapping, $start_pos, $end_pos );

		PIECFW_Product_Import_Export::log( __( 'Finished parsing product variations CSV.', PIECFW_TRANSLATE_NAME ) );

		unset( $import_data );

		wp_defer_term_counting( true );
		wp_defer_comment_counting( true );

		return $position;
	}

	// Display import page title
	function header() {
		_e('<h2>' . ( empty( sanitize_text_field($_GET['merge']) ) ? __( 'Import Product Variations', PIECFW_TRANSLATE_NAME ) : __( 'Merge Product Variations', PIECFW_TRANSLATE_NAME ) ) . '</h2>');
	}

	/**
	* Display introductory text and file upload form
	*/
	public function greet() {
		$action     = 'admin.php?import=piecfw_variation&amp;step=1&amp;merge=' . ( ! empty( sanitize_text_field($_GET['merge']) ) ? 1 : 0 );
		$bytes      = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
		$size       = size_format( $bytes );
		$upload_dir = wp_upload_dir();

		include( 'views/html-import-variation-greeting.php' );
	}
}
