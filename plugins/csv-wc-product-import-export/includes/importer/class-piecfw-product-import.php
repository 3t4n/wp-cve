<?php
/**
* WordPress Importer class for managing the import process of a CSV file
*/
if ( ! class_exists( 'WP_Importer' ) )
	return;

class PIECFW_Product_Import extends WP_Importer {

	var $id;
	var $file_url;
	var $delimiter;
	var $merge_empty_cells;

	// mappings from old information to new
	var $processed_terms = array();
	var $processed_posts = array();
	var $post_orphans    = array();
	var $attachments     = array();
	var $upsell_skus     = array();
	var $crosssell_skus  = array();

	// Results
	var $import_results  = array();

	/**
	* Constructor
	*/
	public function __construct() {
		$this->import_page             = 'piecfw';
		$this->file_url_import_enabled = apply_filters( 'piecfw_product_file_url_import_enabled', false );

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
	* Registered callback function for the WordPress Importer
	*/
	public function dispatch() {
		global $wpdb;

		$regenerate_thumbnail = apply_filters( 'woocommerce_background_image_regeneration', true );

		if ( ! empty( $_POST['delimiter'] ) ) {
			$this->delimiter = stripslashes( trim( sanitize_text_field($_POST['delimiter']) ) );
		}

		if ( ! $this->delimiter )
			$this->delimiter = ',';

		if ( ! empty( $_POST['merge_empty_cells'] ) ) {
			$this->merge_empty_cells = 1;
		} else {
			$this->merge_empty_cells = 0;
		}

		$step = empty( $_GET['step'] ) ? 0 : (int) sanitize_text_field($_GET['step']);

		switch ( $step ) {
			case 0 :
				$this->header();
				$this->greet();
			break;
			case 1 :
				$this->header();

				check_admin_referer( 'import-upload' );

				if ( $this->handle_upload() )
					$this->import_options();
				else
					_e( 'Error with handle_upload!', PIECFW_TRANSLATE_NAME );
			break;
			case 2 :
				$this->header();

				check_admin_referer( 'import-woocommerce' );

				$this->id = (int) sanitize_text_field($_POST['import_id']);

				if ( $this->file_url_import_enabled )
					$this->file_url = esc_attr( sanitize_url($_POST['import_url']) );

				if ( $this->id )
					$file = get_attached_file( $this->id );
				else if ( $this->file_url_import_enabled )
					$file = ABSPATH . $this->file_url;

				$file = str_replace( "\\", "/", $file );

				$timezone_format = _x( 'Y-m-d H:i:s', 'timezone date format' );
				$created_at = date_i18n( $timezone_format );

				if ( $file ) {

					//Insert File Log
					$imported_file = get_post_meta( $this->id, '_wp_imported_file', true);
					if(!empty($imported_file)){
						$wpdb->insert($wpdb->prefix.'piecfw_product_import_file_log', array(
			                'file_name' => $imported_file,
			                'file_status' => 'Success',
			                'file_date' => $created_at, 
			            ));
			        }
					?>
					<table id="piecfw-progress" class="widefat_importer widefat">
						<thead>
							<tr>
								<th class="status">&nbsp;</th>
								<th class="row"><?php _e( '#', PIECFW_TRANSLATE_NAME ); ?></th>
								<th><?php _e( 'SKU', PIECFW_TRANSLATE_NAME ); ?></th>
								<th style="width:100px;"><?php _e( 'Product', PIECFW_TRANSLATE_NAME ); ?></th>
								<th class="reason"><?php _e( 'Message', PIECFW_TRANSLATE_NAME ); ?></th>
							</tr>
						</thead>
						<tfoot>
							<tr class="importer-loading">
								<td colspan="5"></td>
							</tr>
						</tfoot>
						<tbody></tbody>
					</table>
					<script type="text/javascript">
						jQuery(document).ready(function($) {

							if ( ! window.console ) { window.console = function(){}; }

							var processed_terms = [];
							var processed_posts = {};
							var post_orphans    = [];
							var attachments     = [];
							var upsell_skus     = [];
							var crosssell_skus  = [];
							var i               = 1;
							var done_count      = 0;

							function import_rows( start_pos, end_pos ) {

								var data = {
									action: 	'piecfw_import_request',
									import_id: '<?php _e($this->id);?>',
									file:       '<?php _e(addslashes( $file )); ?>',
									mapping:    decodeURIComponent('<?php _e(rawurlencode( wp_json_encode( sanitize_text_field($_POST['map_to']) ) )); ?>'),
									delimiter:  '<?php _e($this->delimiter); ?>',
									merge_empty_cells: '<?php _e($this->merge_empty_cells); ?>',
									start_pos:  start_pos,
									end_pos:    end_pos,
								};

								return $.ajax({
									url:        '<?php _e(add_query_arg( array( 'import_page' => $this->import_page, 'step' => '3', 'merge' => ! empty( $_GET['merge'] ) ? '1' : '0' ), admin_url( 'admin-ajax.php' ) )); ?>',
									data:       data,
									type:       'POST',
									success:    function( response ) {
										//console.log( response );
										if ( response ) {

											try {
												// Get the valid JSON only from the returned string
												if ( response.indexOf("<!--WC_START-->") >= 0 )
													response = response.split("<!--WC_START-->")[1]; // Strip off before after WC_START

												if ( response.indexOf("<!--WC_END-->") >= 0 )
													response = response.split("<!--WC_END-->")[0]; // Strip off anything after WC_END

												// Parse
												var results = $.parseJSON( response );

												if ( results.error ) {

													$('#piecfw-progress tbody').append( '<tr id="row-' + i + '" class="error"><td class="status" colspan="5">' + results.error + '</td></tr>' );

													i++;

												} else if ( results.import_results && $( results.import_results ).size() > 0 ) {

													$.each( results.processed_terms, function( index, value ) {
														processed_terms.push( value );
													});

													$.each( results.processed_posts, function( index, value ) {
														processed_posts[ index ] = value;
													});

													$.each( results.post_orphans, function( index, value ) {
														post_orphans.push( value );
													});

													$.each( results.attachments, function( index, value ) {
														attachments.push( value );
													});

													upsell_skus    = jQuery.extend( {}, upsell_skus, results.upsell_skus );
													crosssell_skus = jQuery.extend( {}, crosssell_skus, results.crosssell_skus );

													$( results.import_results ).each(function( index, row ) {
														$('#piecfw-progress tbody').append( '<tr id="row-' + i + '" class="' + row['status'] + '"><td><mark class="result" title="' + row['status'] + '">' + row['status'] + '</mark></td><td class="row">' + i + '</td><td>' + row['sku'] + '</td><td style="word-break: break-word;width:500px;">' + row['post_id'] + ' - ' + row['post_title'] + '</td><td class="reason">' + row['reason'] + '</td></tr>' );

														i++;
													});
												}

											} catch(err) {}

										} else {
											$('#piecfw-progress tbody').append( '<tr class="error"><td class="status" colspan="5">' + '<?php _e( 'AJAX Error', PIECFW_TRANSLATE_NAME ); ?>' + '</td></tr>' );
										}

										var w = $(window);
										var row = $( "#row-" + ( i - 1 ) );

										if ( row.length ) {
										    w.scrollTop( row.offset().top - (w.height()/2) );
										}

										done_count++;

										$('body').trigger( 'piecfw_import_request_complete' );
									}
								});
							}

							var rows = [];

							<?php
							$limit = apply_filters( 'piecfw_import_limit_per_request', 20 );
							$enc   = piecfw_is_first_row_encoded_in( $file, 'UTF-8, ISO-8859-1' );
							if ( $enc ) {
								setlocale( LC_ALL, 'en_US.' . $enc );
							}
							@ini_set( 'auto_detect_line_endings', true );

							$count             = 0;
							$previous_position = 0;
							$position          = 0;
							$import_count      = 0;

							// Get CSV positions
							if ( ( $handle = fopen( $file, "r" ) ) !== FALSE ) {

								while ( ( $postmeta = fgetcsv( $handle, 0, $this->delimiter ) ) !== FALSE ) {
									$count++;

						            if ( $count >= $limit ) {
						            	$previous_position = $position;
										$position          = ftell( $handle );
										$count             = 0;
										$import_count      ++;

										// Import rows between $previous_position $position
						            	?>rows.push( [ <?php _e($previous_position); ?>, <?php _e($position); ?> ] ); <?php
						            }
		  						}

		  						// Remainder
		  						if ( $count > 0 ) {
		  							?>rows.push( [ <?php _e($position); ?>, '' ] ); <?php
		  							$import_count      ++;
		  						}

		    					fclose( $handle );
		    				}
							?>

							var data = rows.shift();
							var regen_count = 0;
							import_rows( data[0], data[1] );

							$('body').on( 'piecfw_import_request_complete', function() {
								if ( done_count == <?php _e($import_count); ?> ) {

									if ( attachments.length ) {

										$('#piecfw-progress tbody').append( '<tr class="regenerating"><td colspan="5"><div class="progress"></div></td></tr>' );

										index = 0;

										$.each( attachments, function( i, value ) {
											<?php if ( $regenerate_thumbnail ) : ?>
											regenerate_thumbnail( value );
											<?php endif; ?>
											index ++;
											if ( index == attachments.length ) {
												import_done();
											}
										});

									} else {
										import_done();
									}

								} else {
									// Call next request
									data = rows.shift();
									import_rows( data[0], data[1] );
								}
							} );

							// Regenerate a specified image via AJAX
							function regenerate_thumbnail( id ) {
								$.ajax({
									type: 'POST',
									url: ajaxurl,
									data: { action: "piecfw_import_regenerate_thumbnail", id: id },
									success: function( response ) {
										if ( response !== Object( response ) || ( typeof response.success === "undefined" && typeof response.error === "undefined" ) ) {
											response = new Object;
											response.success = false;
											response.error = "<?php printf( esc_js( __( 'The resize request was abnormally terminated (ID %s). This is likely due to the image exceeding available memory or some other type of fatal error.', PIECFW_TRANSLATE_NAME ) ), '" + id + "' ); ?>";
										}

										regen_count ++;

										$('#piecfw-progress tbody .regenerating .progress').css( 'width', '100%' ).html( regen_count + ' / ' + attachments.length + ' <?php _e(esc_js( __( 'thumbnails regenerated', PIECFW_TRANSLATE_NAME ) )); ?>' );

										if ( ! response.success ) {
											$('#piecfw-progress tbody').append( '<tr><td colspan="5">' + response.error + '</td></tr>' );
										}
									},
									error: function( response ) {
										$('#piecfw-progress tbody').append( '<tr><td colspan="5">' + response.error + '</td></tr>' );
									}
								});
							}

							function import_done() {
								var data = {
									action: 'piecfw_import_request',
									file: '<?php _e($file); ?>',
									processed_terms: processed_terms,
									processed_posts: processed_posts,
									post_orphans: post_orphans,
									upsell_skus: upsell_skus,
									crosssell_skus: crosssell_skus
								};

								$.ajax({
									url: '<?php _e(add_query_arg( array( 'import_page' => $this->import_page, 'step' => '4', 'merge' => ! empty( $_GET['merge'] ) ? 1 : 0 ), admin_url( 'admin-ajax.php' ) )); ?>',
									data:       data,
									type:       'POST',
									success:    function( response ) {
										//console.log( response );
										$('#piecfw-progress tbody').append( '<tr class="complete"><td colspan="5">' + response + '</td></tr>' );
										$('.importer-loading').hide();
									}
								});
							}
						});
					</script>
					<?php
				} else {

					//Insert File Log
					$imported_file = get_post_meta( $this->id, '_wp_imported_file', true);
					if(!empty($imported_file)){
						$wpdb->insert($wpdb->prefix.'piecfw_product_import_file_log', array(
			                'file_name' => $imported_file,
			                'file_status' => 'Failed',
			                'file_date' => $created_at, 
			            ));
			        }

					_e('<p class="error">' . __( 'Error finding uploaded file!', PIECFW_TRANSLATE_NAME ) . '</p>');
				}
			break;
			case 3 :
				// Check access - cannot use nonce here as it will expire after multiple requests
				if ( ! current_user_can( 'manage_woocommerce' ) )
					die();

				add_filter( 'http_request_timeout', array( $this, 'bump_request_timeout' ) );

				if ( function_exists( 'gc_enable' ) )
					gc_enable();

				@set_time_limit(0);
				@ob_flush();
				@flush();
				$wpdb->hide_errors();

				$file      = stripslashes( sanitize_post($_POST['file']) );
				$mapping   = json_decode( stripslashes( sanitize_text_field($_POST['mapping']) ), true );
				$start_pos = isset( $_POST['start_pos'] ) ? absint( sanitize_text_field($_POST['start_pos']) ) : 0;
				$end_pos   = isset( $_POST['end_pos'] ) ? absint( sanitize_text_field($_POST['end_pos']) ) : '';

				$position = $this->import_start( $file, $mapping, $start_pos, $end_pos );
				$this->import();
				$this->import_end();

				$results                    = array();
				$results['import_results']  = $this->import_results;
				$results['processed_terms'] = $this->processed_terms;
				$results['processed_posts'] = $this->processed_posts;
				$results['post_orphans']    = $this->post_orphans;
				$results['attachments']     = $this->attachments;
				$results['upsell_skus']     = $this->upsell_skus;
				$results['crosssell_skus']  = $this->crosssell_skus;

				_e("<!--WC_START-->");
				_e(function_exists( 'wc_esc_json' ) ? wc_esc_json( wp_json_encode( $results ), true ) : wp_specialchars( wp_json_encode( $results ), ENT_QUOTES, 'UTF-8', true ));
				_e("<!--WC_END-->");
				exit;
			break;
			case 4 :
				// Check access - cannot use nonce here as it will expire after multiple requests
				if ( ! current_user_can( 'manage_woocommerce' ) ) {
					die();
				}

				add_filter( 'http_request_timeout', array( $this, 'bump_request_timeout' ) );

				if ( function_exists( 'gc_enable' ) ) {
					gc_enable();
				}

				@set_time_limit(0);
				@ob_flush();
				@flush();
				$wpdb->hide_errors();

				$this->processed_terms = isset( $_POST['processed_terms'] ) ? sanitize_post($_POST['processed_terms']) : array();
				$this->processed_posts = isset( $_POST['processed_posts']) ? sanitize_post($_POST['processed_posts']) : array();
				$this->post_orphans    = isset( $_POST['post_orphans']) ? sanitize_post($_POST['post_orphans']) : array();
				$this->crosssell_skus  = isset( $_POST['crosssell_skus']) ? array_filter( (array) sanitize_post($_POST['crosssell_skus']) ) : array();
				$this->upsell_skus     = isset( $_POST['upsell_skus']) ? array_filter( (array) sanitize_post($_POST['upsell_skus']) ) : array();

				/*_e( 'Cleaning up...', PIECFW_TRANSLATE_NAME ) . ' ';*/

				wp_defer_term_counting( true );
				wp_defer_comment_counting( true );

				/*_e( 'Clearing transients...', PIECFW_TRANSLATE_NAME ) . ' ';*/

				// reset transients for products
				wc_delete_product_transients();

				// Delete parent transients for the products, like wc_product_children_ and wc_product_total_stock_. Kudos lauravaq
				$parents = array();

				if ( ! empty( $this->processed_posts ) ) {
					foreach ( $this->processed_posts as $post_id ) {
						// When merging parent products sync is never called,
						// thus resulting issue #66. Since we're trying to
						// avoid a call to wc_get_product for performance reason,
						// checking term product type from has_term.
						if ( has_term( 'variable', 'product_type', $post_id ) ) {
							WC_Product_Variable::sync( $post_id );
						}

						$parent = wp_get_post_parent_id( $post_id );
						if ( $parent ) {
							$parents[] = $parent;
						}
					}
				}

				$parents = array_unique( $parents );

				foreach ( $parents as $parent ) {
					wc_delete_product_transients( $parent );
				}

				delete_transient( 'wc_attribute_taxonomies' );

				/*'Reticulating Splines...' . ' '; // Easter egg*/

				/*_e( 'Backfilling parents...', PIECFW_TRANSLATE_NAME ) . ' ';*/

				$this->backfill_parents();

				if ( ! empty( $this->upsell_skus ) ) {
					/*_e( 'Linking upsells...', PIECFW_TRANSLATE_NAME ) . ' ';*/

					foreach ( $this->upsell_skus as $post_id => $skus ) {
						$this->link_product_skus( 'upsell', $post_id, $skus );
					}
				}

				if ( ! empty( $this->crosssell_skus ) ) {
					/*_e( 'Linking crosssells...', PIECFW_TRANSLATE_NAME ) . ' ';*/

					foreach ( $this->crosssell_skus as $post_id => $skus ) {
						$this->link_product_skus( 'crosssell', $post_id, $skus );
					}
				}

				if ( 'piecfw_variation' === $this->import_page && ! empty( $this->processed_posts ) ) {

					/*_e( 'Syncing variations...', PIECFW_TRANSLATE_NAME ) . ' ';*/

					foreach ( $parents as $parent ) {
						WC_Product_Variable::sync( $parent );
					}
				}

				// SUCCESS
				_e( 'Import complete.', PIECFW_TRANSLATE_NAME );

				$this->import_end();
				exit;
			break;
		}

		$this->footer();
	}

	/**
	* format_data_from_csv
	*/
	public function format_data_from_csv( $data, $enc ) {
		return ( $enc == 'UTF-8' ) ? $data : utf8_encode( $data );
	}

	/**
	* Display pre-import options
	*/
	public function import_options() {
		$j = 0;

		if ( $this->id )
			$file = get_attached_file( $this->id );
		else if ( $this->file_url_import_enabled )
			$file = ABSPATH . $this->file_url;
		else
			return;

		// Set locale
		$enc = piecfw_is_first_row_encoded_in( $file, 'UTF-8, ISO-8859-1' );
		if ( $enc ) {
			setlocale( LC_ALL, 'en_US.' . $enc );
		}
		@ini_set( 'auto_detect_line_endings', true );

		// Get headers
		if ( ( $handle = fopen( $file, "r" ) ) !== FALSE ) {

			$row = $raw_headers = array();
			$header = fgetcsv( $handle, 0, $this->delimiter );

		    while ( ( $postmeta = fgetcsv( $handle, 0, $this->delimiter ) ) !== FALSE ) {
	            foreach ( $header as $key => $heading ) {
	            	if ( ! $heading ) continue;
	            	$s_heading = strtolower( $heading );
	                $row[$s_heading] = ( isset( $postmeta[$key] ) ) ? $this->format_data_from_csv( $postmeta[$key], $enc ) : '';
	                $raw_headers[ $s_heading ] = $heading;
	            }
	            break;
		    }
		    fclose( $handle );
		}

		$merge = (!empty($_GET['merge']) && $_GET['merge']) ? 1 : 0;

		$taxonomies = get_taxonomies( '', 'names' );

		include( 'views/html-import-options.php' );
	}

	/**
	* The main controller for the actual import stage.
	*/
	public function import() {
		PIECFW_Product_Import_Export::log( '---' );
		PIECFW_Product_Import_Export::log( __( 'Processing products.', PIECFW_TRANSLATE_NAME ) );

		$imported_file = '';
		if(isset($_POST['import_id'])){
			$import_id = (int) sanitize_text_field($_POST['import_id']);
			$imported_file = get_post_meta( $import_id, '_wp_imported_file', true);
		}

		foreach ( $this->parsed_data as $key => &$item ) {
			$product = $this->parser->parse_product( $item, $this->merge_empty_cells );

			if ( ! is_wp_error( $product ) ) {
				if($item['tax:product_type']=='variation'){
					$variation_product = new PIECFW_Product_Variation_Import();
					$variation_product->process_product( $product, $imported_file );
				}else{
					$this->process_product( $product, $imported_file );
				}

			} else {
				$this->add_import_result( 'failed', $product->get_error_message(), 'Not parsed', json_encode( $item ), '-' );
			}

			unset( $item, $product );
		}

		if ( function_exists( 'wc_update_product_lookup_tables' ) ) {
			wc_update_product_lookup_tables();
		}

		PIECFW_Product_Import_Export::log( __( 'Finished processing products.', PIECFW_TRANSLATE_NAME ) );
	}

	/**
	* Parses the CSV file and prepares us for the task of processing parsed data
	*/
	public function import_start( $file, $mapping, $start_pos, $end_pos ) {

		$memory    = size_format( wc_let_to_num( ini_get( 'memory_limit' ) ) );
		$wp_memory = size_format( wc_let_to_num( WP_MEMORY_LIMIT ) );

		PIECFW_Product_Import_Export::log( '---[ New Import ] PHP Memory: ' . $memory . ', WP Memory: ' . $wp_memory );
		PIECFW_Product_Import_Export::log( __( 'Parsing products CSV.', PIECFW_TRANSLATE_NAME ) );

		$this->parser = new PIECFW_Parser( 'product' );

		list( $this->parsed_data, $this->raw_headers, $position ) = $this->parser->parse_data( $file, $this->delimiter, $mapping, $start_pos, $end_pos );

		PIECFW_Product_Import_Export::log( __( 'Finished parsing products CSV.', PIECFW_TRANSLATE_NAME ) );

		unset( $import_data );

		wp_defer_term_counting( true );
		wp_defer_comment_counting( true );

		return $position;
	}

	/**
	* Performs post-import cleanup of files and the cache
	*/
	public function import_end() {

		//wp_cache_flush(); Stops output in some hosting environments
		foreach ( get_taxonomies() as $tax ) {
			delete_option( "{$tax}_children" );
			_get_term_hierarchy( $tax );
		}

		wp_defer_term_counting( false );
		wp_defer_comment_counting( false );

		do_action( 'import_end' );
	}

	/**
	* Handles the CSV upload and initial parsing of the file to prepare for
	* displaying author import options
	*/
	public function handle_upload() {
		try {
			if ( empty( $_POST['file_url'] ) ) {
				$this->handle_initial_upload_file();
			} else {
				$this->handle_initial_path_file_check();
			}

			return true;
		} catch ( Exception $e ) {
			printf( '<p><strong>%s</strong></p>', esc_html( $e->getMessage() ) );
		}

		return false;
	}

	/**
	* Handle initial upload file before displaying import options.
	*/
	protected function handle_initial_upload_file() {
		$file = wp_import_handle_upload();

		if ( isset( $file['error'] ) ) {
			/* translators: placeholder is upload error from WP */
			throw new Exception( sprintf( __( 'Sorry, there has been an error: %s.', PIECFW_TRANSLATE_NAME ), $file['error'] ) );
		}

		$this->id = (int) $file['id'];

		if (!empty($_POST['filename']) && !empty($this->id)) {
			update_post_meta($this->id,'_wp_imported_file',sanitize_file_name($_POST['filename']));
		}
	}

	/**
	* Handle initial path file check before displaying import options.
	*/
	protected function handle_initial_path_file_check() {
		if ( ! $this->is_safe_path( ABSPATH, $_POST['file_url'] ) ) {
			/* translators: placeholder is base directory (ABSPATH) */
			throw new Exception( sprintf( __( 'Sorry, there has been an error: path file must exist inside %s.', PIECFW_TRANSLATE_NAME ), ABSPATH ) );
		}

		$filepath = ABSPATH . sanitize_url($_POST['file_url']);
		if ( ! file_exists( $filepath ) ) {
			/* translators: placeholder is file path */
			throw new Exception( sprintf( __( 'Sorry, there has been an error: %s does not exist.', PIECFW_TRANSLATE_NAME ), $filepath ) );
		}

		if ( ! $this->is_acceptable_piecfw_file( $filepath ) ) {
			$mime_types = implode( ', ', $this->get_acceptable_piecfw_mime_types() );

			/* translators: placeholder is comma-separated of accepted mime-types for import (e.g. 'text/csv') */
			throw new Exception( sprintf( __( 'File must have .csv extension with acceptable mime types (%s)', PIECFW_TRANSLATE_NAME ), $mime_types ) );
		}

		$this->file_url = esc_attr( sanitize_url($_POST['file_url']) );
	}

	public function product_exists( $title, $sku = '', $post_name = '' ) {
		global $wpdb;

		// Post Title Check
		$post_title = stripslashes( sanitize_post_field( 'post_title', $title, 0, 'db' ) );

	    $query = "SELECT ID FROM $wpdb->posts WHERE post_type = 'product' AND post_status IN ( 'publish', 'private', 'draft', 'pending', 'future' )";
	    $args = array();

	    if ( ! empty ( $title ) ) {
	        $query .= ' AND post_title = %s';
	        $args[] = $post_title;
	    }

	    if ( ! empty ( $post_name ) ) {
	        $query .= ' AND post_name = %s';
	        $args[] = $post_name;
	    }

	    if ( ! empty ( $args ) ) {
	        $posts_that_exist = $wpdb->get_col( $wpdb->prepare( $query, $args ) );

	        if ( $posts_that_exist ) {

	        	foreach( $posts_that_exist as $post_exists ) {

		        	// Check unique SKU
		        	$post_exists_sku = PIECFW_Product_Import_Export::get_meta_data( $post_exists, '_sku' );

					if ( $sku == $post_exists_sku ) {
						return true;
					}

	        	}

		    }
		}

		// Sku Check
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

	    return false;
	}

	/**
	* sort attributes
	*/
	public function attributes_cmp( $a, $b ) {
		if ( $a['position'] == $b['position'] ) return 0;
		return ( $a['position'] < $b['position'] ) ? -1 : 1;
	}

	/**
	* Sets product catalog visibility.
	*/
	public function set_catalog_visibility( $product_id, $post ) {
		$product = wc_get_product( $product_id );

		foreach( $post['postmeta'] as $meta ) {
			if ( '_visibility' === $meta['key'] ) {
				$product->set_catalog_visibility( $meta['value'] );
				$product->save();
				break;
			}
		}
	}

	/**
	* Sets product featured visibility.
	*/
	public function set_featured( $product_id, $post ) {
		$product = wc_get_product( $product_id );

		foreach( $post['postmeta'] as $meta ) {
			if ( '_featured' === $meta['key'] ) {
				$product->set_featured( 'yes' === $meta['value'] );
				$product->save();
				break;
			}
		}
	}

	/**
	* Create new posts based on import information
	*/
	public function process_product( $post, $imported_file='' ) {
		global $wpdb;
		try {
			wp_suspend_cache_invalidation( true );
			$timezone_format = _x( 'Y-m-d H:i:s', 'timezone date format' );
			$created_at = date_i18n( $timezone_format );
				
			$processing_product_id    = absint( $post['post_id'] );
			$processing_product       = get_post( $processing_product_id );

			$processing_product_title = $processing_product ? $processing_product->post_title : '';
			$processing_product_sku   = $processing_product ? $processing_product->sku : '';

			$merging                  = ! empty( $post['merging'] );
			$insert_meta_data         = array();

			if ( ! empty( $post['post_title'] ) ) {
				$processing_product_title = $post['post_title'];
			}

			if ( ! empty( $post['sku'] ) ) {
				$processing_product_sku = $post['sku'];
			}

			$log_id = $wpdb->get_var( $wpdb->prepare( "SELECT log_id FROM ".$wpdb->prefix."piecfw_product_import_data_log WHERE product_sku = %s AND file_name = %s", $processing_product_sku,  $imported_file) );

			if($log_id){
				return;
			}

			if ( ! empty( $processing_product_id ) && isset( $this->processed_posts[ $processing_product_id ] ) ) {
				$this->add_import_result( 'skipped', __( 'Product already processed', PIECFW_TRANSLATE_NAME ), $processing_product_id, $processing_product_title, $processing_product_sku );
				PIECFW_Product_Import_Export::log( __('> Post ID already processed. Skipping.', PIECFW_TRANSLATE_NAME), true );

				//Insert Data Log
				if(!empty($imported_file)){
					$wpdb->insert($wpdb->prefix.'piecfw_product_import_data_log', array(
			            'file_name' => $imported_file,
			            'product_id' => $processing_product_id,
			            'product_sku' => $processing_product_sku,
			            'product_name' => $processing_product_title,
			            'product_type' => '',
			            'status' => 0, 
			            'status_message' => __( 'Product already processed', PIECFW_TRANSLATE_NAME ),
			            'created_at' => $created_at
			        ));
				}
					
				unset( $post );
				return;
			}

			if ( ! empty ( $post['post_status'] ) && $post['post_status'] == 'auto-draft' ) {
				$this->add_import_result( 'skipped', __( 'Skipping auto-draft', PIECFW_TRANSLATE_NAME ), $processing_product_id, $processing_product_title, $processing_product_sku );
				PIECFW_Product_Import_Export::log( __('> Skipping auto-draft.', PIECFW_TRANSLATE_NAME), true );

				//Insert Data Log
				if(!empty($imported_file)){
					$wpdb->insert($wpdb->prefix.'piecfw_product_import_data_log', array(
			            'file_name' => $imported_file,
			            'product_id' => $processing_product_id,
			            'product_sku' => $processing_product_sku,
			            'product_name' => $processing_product_title,
			            'product_type' => '',
			            'status' => 0, 
			            'status_message' => __( 'Skipping auto-draft.', PIECFW_TRANSLATE_NAME ),
			            'created_at' => $created_at
			        ));
				}
					
				unset( $post );
				return;
			}

			// Check if post exists when importing
			if ( ! $merging ) {
				if ( $this->product_exists( $processing_product_title, $processing_product_sku, $post['post_name'] ) ) {
					$this->add_import_result( 'skipped', __( 'Product already exists', PIECFW_TRANSLATE_NAME ), $processing_product_id, $processing_product_title, $processing_product_sku );
					PIECFW_Product_Import_Export::log( sprintf( __('> &#8220;%s&#8221; already exists.', PIECFW_TRANSLATE_NAME), esc_html($processing_product_title) ), true );
					
					//Insert Data Log
					if(!empty($imported_file)){
						$wpdb->insert($wpdb->prefix.'piecfw_product_import_data_log', array(
				            'file_name' => $imported_file,
				            'product_id' => $processing_product_id,
				            'product_sku' => $processing_product_sku,
				            'product_name' => $processing_product_title,
				            'product_type' => '',
				            'status' => 0, 
				            'status_message' => __( 'Product already exists', PIECFW_TRANSLATE_NAME ),
				            'created_at' => $created_at
				        ));
					}
						
					unset( $post );
					return;
				}
				if ( $processing_product_id && is_string( get_post_status( $processing_product_id ) ) ) {
					$this->add_import_result( 'skipped', __( 'Importing post ID conflicts with an existing post ID', PIECFW_TRANSLATE_NAME ), $processing_product_id, get_the_title( $processing_product_id ), '' );
					PIECFW_Product_Import_Export::log( sprintf( __('> &#8220;%s&#8221; ID already exists.', PIECFW_TRANSLATE_NAME), esc_html( $processing_product_id ) ), true );

					//Insert Data Log
					if(!empty($imported_file)){
						$wpdb->insert($wpdb->prefix.'piecfw_product_import_data_log', array(
				            'file_name' => $imported_file,
				            'product_id' => $processing_product_id,
				            'product_sku' => $processing_product_sku,
				            'product_name' => get_the_title( $processing_product_id ),
				            'product_type' => '',
				            'status' => 0, 
				            'status_message' => __( 'Importing post ID conflicts with an existing post ID', PIECFW_TRANSLATE_NAME ),
				            'created_at' => $created_at
				        ));
				    }

					unset( $post );
					return;
				}
			}

			// Check post type to avoid conflicts with IDs
			if ( $merging && $processing_product_id && get_post_type( $processing_product_id ) !== 'product' ) {
				$this->add_import_result( 'skipped', __( 'Post is not a product', PIECFW_TRANSLATE_NAME ), $processing_product_id, $processing_product_title, $processing_product_sku );
				PIECFW_Product_Import_Export::log( sprintf( __('> &#8220;%s&#8221; is not a product.', PIECFW_TRANSLATE_NAME), esc_html($processing_product_id) ), true );

				//Insert Data Log
				if(!empty($imported_file)){
					$wpdb->insert($wpdb->prefix.'piecfw_product_import_data_log', array(
			            'file_name' => $imported_file,
			            'product_id' => $processing_product_id,
			            'product_sku' => $processing_product_sku,
			            'product_name' => $processing_product_title,
			            'product_type' => '',
			            'status' => 0, 
			            'status_message' => __( 'Post is not a product', PIECFW_TRANSLATE_NAME ),
			            'created_at' => $created_at
			        ));
			    }

				unset( $post );
				return;
			}

			if ( $merging ) {

				// Only merge fields which are set
				$post_id = $processing_product_id;

				PIECFW_Product_Import_Export::log( sprintf( __('> Merging post ID %s.', PIECFW_TRANSLATE_NAME), $post_id ), true );

				$postdata = array(
					'ID' => $post_id
				);

				if ( $this->merge_empty_cells ) {
					if ( isset( $post['post_content'] ) ) {
						$postdata['post_content'] = $post['post_content'];
					}
					if ( isset( $post['post_excerpt'] ) ) {
						$postdata['post_excerpt'] = $post['post_excerpt'];
					}
					if ( isset( $post['post_password'] ) ) {
						$postdata['post_password'] = $post['post_password'];
					}
					if ( isset( $post['post_parent'] ) ) {
						$postdata['post_parent'] = $post['post_parent'];
					}
				} else {
					if ( ! empty( $post['post_content'] ) ) {
						$postdata['post_content'] = $post['post_content'];
					}
					if ( ! empty( $post['post_excerpt'] ) ) {
						$postdata['post_excerpt'] = $post['post_excerpt'];
					}
					if ( ! empty( $post['post_password'] ) ) {
						$postdata['post_password'] = $post['post_password'];
					}
					if ( isset( $post['post_parent'] ) && $post['post_parent'] !== '' ) {
						$postdata['post_parent'] = $post['post_parent'];
					}
				}

				if ( ! empty( $post['post_title'] ) ) {
					$postdata['post_title'] = $post['post_title'];
				}
				if ( ! empty( $post['post_author'] ) ) {
					$postdata['post_author'] = absint( $post['post_author'] );
				}
				if ( ! empty( $post['post_date'] ) ) {
					$postdata['post_date'] = date("Y-m-d H:i:s", strtotime( $post['post_date'] ) );
				}
				if ( ! empty( $post['post_date_gmt'] ) ) {
					$postdata['post_date_gmt'] = date("Y-m-d H:i:s", strtotime( $post['post_date_gmt'] ) );
				}
				if ( ! empty( $post['post_name'] ) ) {
					$postdata['post_name'] = $post['post_name'];
				}
				if ( ! empty( $post['post_status'] ) ) {
					$postdata['post_status'] = $post['post_status'];
				}
				if ( isset( $post['menu_order'] ) ) {
					$postdata['menu_order'] = $post['menu_order'];
				}
				if ( ! empty( $post['comment_status'] ) ) {
					$postdata['comment_status'] = $post['comment_status'];
				}

				if ( sizeof( $postdata ) > 1 ) {
					$result = wp_update_post( $postdata, true );

					if ( is_wp_error( $result ) ) {
						$errors   = $result->get_error_messages();
						$messages = array();
						foreach ( $errors as $error ) {
							$messages[] = $error;
						}
						$this->add_import_result( 'failed', implode( ', ', $messages ), $post_id, $processing_product_title, $processing_product_sku );
						PIECFW_Product_Import_Export::log( sprintf( __('> Failed to update product %s', PIECFW_TRANSLATE_NAME), $post_id ), true );

						//Insert Data Log
						if(!empty($imported_file)){
							$wpdb->insert($wpdb->prefix.'piecfw_product_import_data_log', array(
					            'file_name' => $imported_file,
					            'product_id' => $post_id,
					            'product_sku' => $processing_product_sku,
					            'product_name' => $processing_product_title,
					            'product_type' => '',
					            'status' => 0, 
					            'status_message' => __( 'Failed to update product', PIECFW_TRANSLATE_NAME ),
					            'created_at' => $created_at
					        ));
					    }

						unset( $post );
						return;
					} else {
						if ( version_compare( WC_VERSION, '3.0', '>=' ) ) {
							$this->set_catalog_visibility( $post_id, $post );
							$this->set_featured( $post_id, $post );
						}

						PIECFW_Product_Import_Export::log( __( '> Merged post data: ', PIECFW_TRANSLATE_NAME ) . print_r( $postdata, true ) );
					}
				}

			} else {

				// Get parent
				$post_parent = $post['post_parent'];

				if ( $post_parent !== "" ) {
					$post_parent = absint( $post_parent );

					if ( $post_parent > 0 ) {
						// if we already know the parent, map it to the new local ID
						if ( isset( $this->processed_posts[ $post_parent ] ) ) {
							$post_parent = $this->processed_posts[ $post_parent ];
						// otherwise, attach it to an existing ID if the post exists, otherwise mark as an orphan for later
						} else if ( false === get_post_status( $post_parent ) ) {
							$this->post_orphans[ intval( $processing_product_id ) ] = $post_parent;
							$post_parent = 0;
						}
					}
				}

				// Insert product
				PIECFW_Product_Import_Export::log( sprintf( __('> Inserting %s', PIECFW_TRANSLATE_NAME), esc_html( $processing_product_title ) ), true );

				$postdata = array(
					'import_id'      => $processing_product_id,
					'post_author'    => $post['post_author'] ? absint( $post['post_author'] ) : get_current_user_id(),
					'post_date'      => ( $post['post_date'] ) ? date( 'Y-m-d H:i:s', strtotime( $post['post_date'] )) : '',
					'post_date_gmt'  => ( $post['post_date_gmt'] ) ? date( 'Y-m-d H:i:s', strtotime( $post['post_date_gmt'] )) : '',
					'post_content'   => $post['post_content'],
					'post_excerpt'   => $post['post_excerpt'],
					'post_title'     => $processing_product_title,
					'post_name'      => ( $post['post_name'] ) ? $post['post_name'] : sanitize_title( $processing_product_title ),
					'post_status'    => ( $post['post_status'] ) ? $post['post_status'] : 'publish',
					'post_parent'    => $post_parent,
					'menu_order'     => $post['menu_order'],
					'post_type'      => 'product',
					'post_password'  => $post['post_password'],
					'comment_status' => $post['comment_status'],
				);

				$post_id = wp_insert_post( $postdata, true );

				if ( is_wp_error( $post_id ) ) {
					$this->add_import_result( 'failed', __( 'Failed to import product', PIECFW_TRANSLATE_NAME ), $processing_product_id, $processing_product_title, $processing_product_sku );
					PIECFW_Product_Import_Export::log( sprintf( __( 'Failed to import product &#8220;%s&#8221;', PIECFW_TRANSLATE_NAME ), esc_html($processing_product_title) ) );

					//Insert Data Log
					if(!empty($imported_file)){
						$wpdb->insert($wpdb->prefix.'piecfw_product_import_data_log', array(
				            'file_name' => $imported_file,
				            'product_id' => $processing_product_id,
				            'product_sku' => $processing_product_sku,
				            'product_name' => $processing_product_title,
				            'product_type' => '',
				            'status' => 0, 
				            'status_message' => __( 'Failed to import product', PIECFW_TRANSLATE_NAME ),
				            'created_at' => $created_at
				        ));
					}
						
					unset( $post );
					return;
				} else {
					if ( version_compare( WC_VERSION, '3.0', '>=' ) ) {
						$this->set_catalog_visibility( $post_id, $post );
						$this->set_featured( $post_id, $post );
					}

					PIECFW_Product_Import_Export::log( sprintf( __('> Inserted - post ID is %s.', PIECFW_TRANSLATE_NAME), $post_id ) );
				}
			}

			unset( $postdata );

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
						$insert_meta_data[ $key ] = apply_filters( 'import_post_meta_value', maybe_unserialize( $meta['value'] ), $key );
					}
				}
			}

			// Import images and add to post
			if ( ! empty( $post['images'] ) && is_array( $post['images'] ) ) {
				$featured    = true;
				$gallery_ids = array();

				if ( $merging ) {

					// Get basenames
					$image_basenames = array();

					foreach ( $post['images'] as $image ) {
						$image_basenames[] = basename( $image );
					}

					// Loop attachments already attached to the product
					$attachments = get_posts( 'post_parent=' . $post_id . '&post_type=attachment&fields=ids&post_mime_type=image&numberposts=-1' );

					foreach ( $attachments as $attachment_key => $attachment ) {
						$attachment_url 		= wp_get_attachment_url( $attachment );
						$attachment_basename 	= basename( $attachment_url );

						// Don't import existing images
						if ( in_array( $attachment_url, $post['images'] ) || in_array( $attachment_basename, $image_basenames ) ) {
							foreach( $post['images'] as $key => $image ) {
								if ( $image == $attachment_url || basename( $image ) == $attachment_basename ) {
									unset( $post['images'][ $key ] );

									PIECFW_Product_Import_Export::log( sprintf( __( '> > Image exists - skipping %s', PIECFW_TRANSLATE_NAME ), basename( $image ) ) );

									if ( $key == 0 ) {
										$insert_meta_data['_thumbnail_id'] = $attachment;
										$featured = false;
									} else {
										$gallery_ids[ $key ] = $attachment;
									}
								}
							}

						} else {

							// Detach image which is not being merged
							$attachment_post = array();
							$attachment_post['ID'] = $attachment;
							$attachment_post['post_parent'] = '';
							wp_update_post( $attachment_post );
							unset( $attachment_post );
						}
					}
					unset( $attachments );
				}

				if ( $post['images'] ) foreach ( $post['images'] as $image_key => $image ) {

					PIECFW_Product_Import_Export::log( sprintf( __( '> > Importing image "%s"', PIECFW_TRANSLATE_NAME ), $image ) );

					$filename = basename( $image );

					$attachment = array(
							'post_title'   => preg_replace( '/\.[^.]+$/', '', $processing_product_title . ' ' . ( $image_key + 1 ) ),
							'post_content' => '',
							'post_status'  => 'inherit',
							'post_parent'  => $post_id
					);

					$attachment_id = $this->process_attachment( $attachment, $image, $post_id );

					if ( ! is_wp_error( $attachment_id ) && $attachment_id ) {
						PIECFW_Product_Import_Export::log( sprintf( __( '> > Imported image "%s"', PIECFW_TRANSLATE_NAME ), $image ) );

						// Set alt
						update_post_meta( $attachment_id, '_wp_attachment_image_alt', $processing_product_title );
						update_post_meta( $attachment_id, '_woocommerce_exclude_image', 0 );

						if ( $featured ) {
							$insert_meta_data['_thumbnail_id'] = $attachment_id;
						} else {
							$gallery_ids[ $image_key ] = $attachment_id;
						}

						$featured = false;
					} else {
						PIECFW_Product_Import_Export::log( sprintf( __( '> > Error importing image "%s"', PIECFW_TRANSLATE_NAME ), $image ) );
						PIECFW_Product_Import_Export::log( '> > ' . $attachment_id->get_error_message() );
					}

					unset( $attachment, $attachment_id );
				}

				PIECFW_Product_Import_Export::log( __( '> > Images set', PIECFW_TRANSLATE_NAME ) );

				ksort( $gallery_ids );

				$insert_meta_data['_product_image_gallery'] = implode( ',', $gallery_ids );
			}

			// Import attributes
			if ( ! empty( $post['attributes'] ) && is_array($post['attributes']) ) {

				if ( $merging ) {
					$attributes = array_filter( (array) PIECFW_Product_Import_Export::get_meta_data( $post_id, '_product_attributes' ) );
					$attributes = array_merge( $attributes, $post['attributes'] );
				} else {
					$attributes = $post['attributes'];
				}

				// Sort attribute positions
				uasort( $attributes, array( $this, 'attributes_cmp' ) );

				$insert_meta_data['_product_attributes'] = $attributes;
			}

			// Import GPF
			if ( ! empty( $post['gpf_data'] ) && is_array( $post['gpf_data'] ) ) {
				$insert_meta_data['_woocommerce_gpf_data'] = $post['gpf_data'];
			}

			if ( ! empty( $post['upsell_skus'] ) && is_array( $post['upsell_skus'] ) ) {
				$this->upsell_skus[ $post_id ] = $post['upsell_skus'];
			}

			if ( ! empty( $post['crosssell_skus'] ) && is_array( $post['crosssell_skus'] ) ) {
				$this->crosssell_skus[ $post_id ] = $post['crosssell_skus'];
			}

			// Delete existing meta first
			$wpdb->query( 'START TRANSACTION' );
			$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->postmeta WHERE meta_key IN ( '" . implode( "','", array_map( 'esc_sql', array_keys( $insert_meta_data ) ) ) . "' ) and post_id = %d", $post_id ) );

			$groups_active = class_exists( 'Groups_WS' );

			// Format meta data
			foreach ( $insert_meta_data as $key => $value ) {
				$meta_key      = wp_unslash( $key );
				$meta_value    = wp_unslash( $value );
				$meta_value    = sanitize_meta( $meta_key, $meta_value, 'post' );

				if ( $groups_active && '_groups_groups' === $key && ! empty( $value ) ) {
					foreach ( $value as $group ) {
						$meta_values[] = $wpdb->prepare( "( %d, %s, %s )", $post_id, $meta_key, $group );
					}

					continue;
				}

				if ( $groups_active && '_groups_groups_remove' === $key && ! empty( $value ) ) {
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

			foreach ( $insert_meta_data as $key => $value ) {
				if ( $key === '_file_paths' ) {
					do_action( 'woocommerce_process_product_file_download_paths', $post_id, 0, $value );
				} else if ( '_stock_status' === $key && 'instock' === $value ) {
					wp_set_post_terms( $post_id, array( 'exclude_from_catalog', 'exclude_from_search' ), 'product_visibility', false );
				}
			}

			if ( $merging ) {
				$this->add_import_result( 'merged', 'Merge successful', $post_id, $processing_product_title, $processing_product_sku );
				PIECFW_Product_Import_Export::log( sprintf( __('> Finished merging post ID %s.', PIECFW_TRANSLATE_NAME), $post_id ) );

				//Insert Data Log
				if(!empty($imported_file)){
					$wpdb->insert($wpdb->prefix.'piecfw_product_import_data_log', array(
			            'file_name' => $imported_file,
			            'product_id' => $post_id,
			            'product_sku' => $processing_product_sku,
			            'product_name' => $processing_product_title,
			            'product_type' => '',
			            'status' => 1, 
			            'status_message' => __( 'Merge successful', PIECFW_TRANSLATE_NAME ),
			            'created_at' => $created_at
			        ));
			    }

			} else {
				$this->add_import_result( 'imported', 'Import successful', $post_id, $processing_product_title, $processing_product_sku );
				PIECFW_Product_Import_Export::log( sprintf( __('> Finished importing post ID %s.', PIECFW_TRANSLATE_NAME), $post_id ) );

				//Insert Data Log
				if(!empty($imported_file)){
					$wpdb->insert($wpdb->prefix.'piecfw_product_import_data_log', array(
			            'file_name' => $imported_file,
			            'product_id' => $post_id,
			            'product_sku' => $processing_product_sku,
			            'product_name' => $processing_product_title,
			            'product_type' => '',
			            'status' => 1, 
			            'status_message' => __( 'Import successful', PIECFW_TRANSLATE_NAME ),
			            'created_at' => $created_at
			        ));
			    }
			}

			wp_suspend_cache_invalidation( false );
			clean_post_cache( $post_id );

			// Allow extensions to run custom import logic.
			do_action( 'piecfw_product_imported', $post, $processing_product_id, $this );

			unset( $post );
		}
		catch(Error $e){
			PIECFW_Product_Import_Export::log( sprintf( __('> Error writing to database: %s.', PIECFW_TRANSLATE_NAME), $e->getMessage()." = ".json_encode($post) ) );
		}
	}

	/**
	* Process terms
	*/
	public function process_terms( $post_id, $terms_to_process ) {
		// add categories, tags and other terms
		if ( ! empty( $terms_to_process ) && is_array( $terms_to_process ) ) {
			$terms_to_set = array();

			foreach ( $terms_to_process as $term_group ) {
				$taxonomy 	 = $term_group['taxonomy'];
				$terms		 = $term_group['terms'];

				if ( ! $taxonomy || ! taxonomy_exists( $taxonomy ) ) {
					continue;
				}

				if ( ! is_array( $terms ) ) {
					$terms = array( $terms );
				}

				$terms_to_set[ $taxonomy ] = array();

				foreach ( $terms as $term_id ) {
					if ( ! $term_id ) continue;
					$terms_to_set[ $taxonomy ][] = intval( $term_id );
				}
			}

			foreach ( $terms_to_set as $tax => $ids ) {
				wp_set_post_terms( $post_id, $ids, $tax, false );
			}
		}
	}

	/**
	* Log a row's import status
	*/
	protected function add_import_result( $status, $reason, $post_id = '', $post_title = '', $sku = '' ) {
		$this->import_results[] = array(
			'post_title' => $post_title,
			'post_id'    => $post_id,
			'sku'    	 => $sku,
			'status'     => $status,
			'reason'     => $reason
		);
	}

	/**
	* If fetching attachments is enabled then attempt to create a new attachment
	*/
	public function process_attachment( $post, $url, $post_id ) {
		$attachment_id 		= '';
		$attachment_url 	= '';
		$attachment_file 	= '';
		$upload_dir 		= wp_upload_dir();

		if ( strstr( $url, site_url() ) ) {
			$abs_url 	= str_replace( trailingslashit( site_url() ), trailingslashit( ABSPATH ), $url );
			$new_name 	= wp_unique_filename( $upload_dir['path'], basename( $url ) );
			$new_url 	= trailingslashit( $upload_dir['path'] ) . $new_name;

			if ( copy( $abs_url, $new_url ) ) {
				$url = basename( $new_url );
			}
		}

		if ( ! strstr( $url, 'http' ) ) {

			// Local file
			$attachment_file 	= trailingslashit( $upload_dir['basedir'] ) . 'product_images/' . $url;

			// We have the path, check it exists
			if ( ! file_exists( $attachment_file ) )
				$attachment_file 	= trailingslashit( $upload_dir['path'] ) . $url;

			// We have the path, check it exists
			if ( file_exists( $attachment_file ) ) {

				$attachment_url 	= str_replace( trailingslashit( ABSPATH ), trailingslashit( site_url() ), $attachment_file );

				if ( $info = wp_check_filetype( $attachment_file ) )
					$post['post_mime_type'] = $info['type'];
				else
					return new WP_Error( 'attachment_processing_error', __('Invalid file type', 'wordpress-importer') );

				$post['guid'] = $attachment_url;

				$attachment_id 		= wp_insert_attachment( $post, $attachment_file, $post_id );

			} else {
				return new WP_Error( 'attachment_processing_error', __('Local image did not exist!', 'wordpress-importer') );
			}

		} else {

			// if the URL is absolute, but does not contain address, then upload it assuming base_site_url
			if ( preg_match( '|^/[\w\W]+$|', $url ) )
				$url = rtrim( site_url(), '/' ) . $url;

			$upload = $this->fetch_remote_file( $url, $post );

			if ( is_wp_error( $upload ) )
				return $upload;

			if ( $info = wp_check_filetype( $upload['file'] ) )
				$post['post_mime_type'] = $info['type'];
			else
				return new WP_Error( 'attachment_processing_error', __('Invalid file type', 'wordpress-importer') );

			$post['guid']       = $upload['url'];
			$attachment_file 	= $upload['file'];
			$attachment_url 	= $upload['url'];

			// as per wp-admin/includes/upload.php
			$attachment_id = wp_insert_attachment( $post, $upload['file'], $post_id );

			unset( $upload );
		}

		if ( ! is_wp_error( $attachment_id ) && $attachment_id > 0 ) {
			PIECFW_Product_Import_Export::log( sprintf( __( '> > Inserted image attachment "%s"', PIECFW_TRANSLATE_NAME ), $url ) );
			$this->attachments[] = $attachment_id;
		}

		return $attachment_id;
	}

	/**
	* Attempt to download a remote file attachment
	*/
	public function fetch_remote_file( $url, $post ) {

		// extract the file name and extension from the url
		$file_name 		= basename( current( explode( '?', $url ) ) );
		$wp_filetype 	= wp_check_filetype( $file_name, null );
		$parsed_url 	= @parse_url( $url );

		// Check parsed URL
		if ( ! $parsed_url || ! is_array( $parsed_url ) )
			return new WP_Error( 'import_file_error', 'Invalid URL' );

		// Ensure url is valid
		$url = str_replace( " ", '%20', $url );

		// Get the file
		$response = wp_remote_get( $url, array(
			'timeout' => 10
		) );

		if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
			return new WP_Error( 'import_file_error', 'Error getting remote image' );
		}

		// Ensure we have a file name and type
		if ( ! $wp_filetype['type'] ) {

			$headers = wp_remote_retrieve_headers( $response );

			if ( isset( $headers['content-disposition'] ) && strstr( $headers['content-disposition'], 'filename=' ) ) {

				$disposition = end( explode( 'filename=', $headers['content-disposition'] ) );
				$disposition = sanitize_file_name( $disposition );
				$file_name   = $disposition;

			} elseif ( isset( $headers['content-type'] ) && strstr( $headers['content-type'], 'image/' ) ) {

				$file_name = 'image.' . str_replace( 'image/', '', $headers['content-type'] );

			}

			unset( $headers );
		}

		// Upload the file
		$upload = wp_upload_bits( $file_name, '', wp_remote_retrieve_body( $response ) );

		if ( $upload['error'] )
			return new WP_Error( 'upload_dir_error', $upload['error'] );

		// Get filesize
		$filesize = filesize( $upload['file'] );

		if ( 0 == $filesize ) {
			@unlink( $upload['file'] );
			unset( $upload );
			return new WP_Error( 'import_file_error', __('Zero size file downloaded', PIECFW_TRANSLATE_NAME) );
		}

		unset( $response );

		return $upload;
	}

	/**
	* Decide what the maximum file size for downloaded attachments is.
	* Default is 0 (unlimited), can be filtered via import_attachment_size_limit
	*/
	public function max_attachment_size() {
		return apply_filters( 'import_attachment_size_limit', 0 );
	}

	/**
	* Attempt to associate posts and menu items with previously missing parents
	*/
	public function backfill_parents() {
		global $wpdb;

		// find parents for post orphans
		if ( ! empty( $this->post_orphans ) && is_array( $this->post_orphans ) )
			foreach ( $this->post_orphans as $child_id => $parent_id ) {
				$local_child_id = $local_parent_id = false;
				if ( isset( $this->processed_posts[$child_id] ) )
					$local_child_id = $this->processed_posts[$child_id];
				if ( isset( $this->processed_posts[$parent_id] ) )
					$local_parent_id = $this->processed_posts[$parent_id];

				if ( $local_child_id && $local_parent_id )
					$wpdb->update( $wpdb->posts, array( 'post_parent' => $local_parent_id ), array( 'ID' => $local_child_id ), '%d', '%d' );
			}
	}

	/**
	* Attempt to associate posts and menu items with previously missing parents
	*/
	public function link_product_skus( $type, $product_id, $skus ) {
		global $wpdb;

		$ids = array();

		foreach ( $skus as $sku ) {
			$ids[] = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_sku' AND meta_value = %s;", $sku ) );
		}

		$ids = array_filter( $ids );

		update_post_meta( $product_id, "_{$type}_ids", $ids );
	}

	// Display import page title
	public function header() {
		_e('<div class="tool-box"><h3 class="title"><img src="'.PIECFW_PLUGIN_DIR_URL.'assets/images/import.png" />&nbsp;' . ( empty( $_GET['merge'] ) ? __( 'Product Import', PIECFW_TRANSLATE_NAME ) : __( 'Merge Products', PIECFW_TRANSLATE_NAME ) ) . '</h3></div>');
	}

	// Close div.wrap
	public function footer() {
		_e('</div>');
	}

	/**
	* Display introductory text and file upload form
	*/
	public function greet() {
		$action     = 'admin.php?import=piecfw&amp;step=1&amp;merge=' . ( ! empty( $_GET['merge'] ) ? 1 : 0 );
		$bytes      = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
		$size       = size_format( $bytes );
		$upload_dir = wp_upload_dir();

		include( 'views/html-import-greeting.php' );
	}

	/**
	* Added to http_request_timeout filter to force timeout at 60 seconds during import
	*/
	public function bump_request_timeout( $val ) {
		return 60;
	}

	/**
	* Checks whether a given path file is a safe path.
	*
	* Safe path file means a given path starts with a given base directory.
	*/
	protected function is_safe_path( $base_dir, $path ) {
		return substr( realpath( $base_dir . $path ), 0, strlen( $base_dir ) ) === $base_dir;
	}

	/**
	* Checks whether a CSV file path is acceptable for importer.
	*
	* Filepath is acceptable if file extension is csv and MIME Content-type is
	* whitelisted.
	*/
	protected function is_acceptable_piecfw_file( $filepath ) {
		$pathinfo = pathinfo( $filepath );
		if ( ! isset( $pathinfo['extension'] ) || 'csv' !== $pathinfo['extension'] ) {
			return false;
		}

		return in_array( mime_content_type( $filepath ), $this->get_acceptable_piecfw_mime_types() );
	}

	/**
	* Get list of acceptable CSV mime types.
	*/
	protected function get_acceptable_piecfw_mime_types() {
		return apply_filters(
			'piecfw_import_acceptable_piecfw_mime_types',
			array(
				'text/csv',
				'text/plain',
				'application/csv',
				'text/comma-separated-values',
				'application/excel',
				'application/vnd.ms-excel',
				'application/vnd.msexcel',
				'text/anytext',
				'application/octet-stream',
				'application/txt',
			)
		);
	}
}
