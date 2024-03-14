<?php

	/**
	* @Description : Custom Functions
	* @Package : Drag & Drop Multiple File Upload - WooCommerce
	* @Author : CodeDropz
	*/

	if ( ! defined( 'ABSPATH' ) || ! defined('DNDMFU_WC') ) {
		exit;
	}

	/**
	* Change icon on File Uploads - tab
	*/

	add_action( 'admin_head', 'dndmfu_wc_product_tabs_icon' );

	function dndmfu_wc_product_tabs_icon() {
		echo '<style>.dndmfu_wc_panel a:before { content: "\f317"!important; }</style>';
	}

	// Product Tabs
	function dndmfu_wc_product_tabs( $tabs ) {
		$tabs['dndmfu_file_uploads'] = array(
			'label'		=> __( 'File Uploads', 'dnd-file-upload-wc' ),
			'target'	=> 'dndmfu_wc_panel',
			'class'		=> array( 'dndmfu_wc_panel' ),
			'priority'	=> 80,
		);
		return $tabs;
	}

	/**
	* Product panels
	*/

	function dndmfu_wc_product_panels( $tabs ) {
		echo '<div id="dndmfu_wc_panel" class="panel woocommerce_options_panel">';
			echo '<div class="options_group">';

				if( get_option('wcf_drag_n_drop_disable') == 'yes' ) {
					woocommerce_wp_checkbox(
						array(
							'id'		=>	'enable_dnd_file_upload_wc',
							'label'     => __( 'Enable File Upload?', 'dnd-file-upload-wc' )
						)
					);
				}else {
					woocommerce_wp_checkbox(
						array(
							'id'		=>	'disable_dnd_file_upload_wc',
							'label'     => __( 'Disable File Upload?', 'dnd-file-upload-wc' )
						)
					);
				}

				woocommerce_wp_text_input(
					array(
						'id'        	=> 'label_dnd_file_upload_wc',
						'placeholder'	=>	'Multiple File Uploads',
						'label'     	=> __( 'Label', 'dnd-file-upload-wc' ),
						'type'      	=> 'text',

					)
				);

			echo '</div>';
		echo '</div>';
	}

	/**
	* Save custom fields individual product.
	*/

	function dndmfu_wc_save_fields( $post_id ) {

		$custom_fields = array(
			'disable_dnd_file_upload_wc',
			'enable_dnd_file_upload_wc',
			'label_dnd_file_upload_wc'
		);

		foreach( $custom_fields as $field ) {
			$new_val = ( isset( $_POST[ $field ] ) ? sanitize_text_field( $_POST[ $field ] ) : '' );
			update_post_meta( $post_id, $field, $new_val );
		}
	}

	/**
	* Custom WC Admin Settings
	*/

	add_filter('woocommerce_get_settings_pages','dndmfu_wc_settings_tabs');

	function dndmfu_wc_settings_tabs( $settings ) {
		$dnd_settings = DNDMFU_WC_DIR .'/inc/admin/dnd-wc-admin-settings.php';
		if( file_exists( $dnd_settings ) ) {
			$settings[] = include $dnd_settings;
		}
		return $settings;
	}

	/**
	* Get file upload name
	*/

	function dndmfu_wc_get_filename() {
		return get_option('wcf_drag_n_drop_field_name') ? get_option('wcf_drag_n_drop_field_name') : 'wc-upload-file';
	}

	/**
	* Display - File Upload Template
	*/

	function dndmfu_wc_display_file_upload() {

		$html_attr = array();
		$product_id = get_the_ID();

		// Get upload label - single product override
		$label = get_post_meta( $product_id, 'label_dnd_file_upload_wc', true );

		// Disable File Upload
		if( get_option('wcf_drag_n_drop_disable') == 'yes' && get_post_meta( $product_id, 'enable_dnd_file_upload_wc', true ) == '' ) {
			return;
		}
        
		// Disable upload for individual product.
		if(  get_option('wcf_drag_n_drop_disable') !== 'yes' && get_post_meta( $product_id, 'disable_dnd_file_upload_wc', true ) === 'yes' ) {
			return;
		}

		// Get supported file types
		$types = ( get_option('wcf_drag_n_drop_support_file_upload') ? explode( ',', get_option('wcf_drag_n_drop_support_file_upload') ) : null );

		// Get file upload name
		$name = dndmfu_wc_get_filename();

		// Custom data attributes
		$attributes['data-name'] 	= $name;
		$attributes['data-type'] 	= ( is_array( $types ) ? implode( '|', array_map('trim', $types) ) : 'jpg|jpeg|png|gif|pdf|doc|docx|xls|xlsx|stl|mp4|mp3|zip' );
		$attributes['data-limit'] 	= get_option('wcf_drag_n_drop_file_size_limit') ? get_option('wcf_drag_n_drop_file_size_limit') : 10485760;
		$attributes['data-max'] 	= get_option('wcf_drag_n_drop_max_file_upload') ? (int)get_option('wcf_drag_n_drop_max_file_upload') : 10;
		$attributes['data-min'] 	= get_option('wcf_drag_n_drop_min_file_upload') ? (int)get_option('wcf_drag_n_drop_min_file_upload') : 0;
		$attributes['data-id']   	= $product_id;
		$attributes['multiple'] 	= 'multiple';

        // Allow other plugin to filter file types
        $accept_all = apply_filters('dndmfu_wc_all_types', false );

        // Add accept file types attributes
        if( ! $accept_all ) {
            $types = explode('|', $attributes['data-type'] );
            $attributes['accept'] = '.' . implode(', .', array_map( 'trim', $types ) );
        }

		foreach( $attributes as $name => $attr ) {
			$html_attr[] = $name .'="'. esc_attr( trim( $attr ) ) .'"';
		}
		?>
			<div class="wc-dnd-file-upload">
				<?php echo ( $label ? esc_html( $label ) : '<label>'. esc_html( get_option('wcf_drag_n_drop_default_label') ) .'</label>' ); ?>
				<input type="file" class="wc-drag-n-drop-file d-none" <?php echo implode(' ', $html_attr ); ?>>
			</div>
		<?php
	}

	/**
	* Cart Validation
	*/

	function dndmfu_wc_cart_validation( $passed, $product_id, $quantity, $variation_id=null ) {

		// Disable File Upload
		if( get_option('wcf_drag_n_drop_disable') == 'yes' && get_post_meta( $product_id, 'enable_dnd_file_upload_wc', true ) == '' ) {
			return $passed;
		}

		// Disable upload for individual product.
		if(  get_option('wcf_drag_n_drop_disable') == 'no' && get_post_meta( $product_id, 'disable_dnd_file_upload_wc', true ) !== '' ) {
			return $passed;
		}

		// Get file upload name
		$file_upload = dndmfu_wc_get_filename();

		// Get files
		$files = ( isset( $_POST[ $file_upload ] ) ? array_map('sanitize_text_field', $_POST[ $file_upload ] ) : null );

		// Check only if file upload is required & not disabled ( edit product page )
		if( get_option('wcf_drag_n_drop_required') == 'yes' ) {
			// Validate file upload - required
			if( is_null( $files ) ) {
				$passed = false;
				wc_add_notice( __( 'File upload is required.', 'dnd-file-upload-wc' ), 'error' );
			}
		}

		// Minimum
		$minimum_file = ( get_option('wcf_drag_n_drop_min_file_upload') ? get_option('wcf_drag_n_drop_min_file_upload') : 0 );

		if( $files && count( $files ) < (int)$minimum_file ) {
			$passed = false;
			$error = __( 'Please upload atleast %s file(s).', 'dnd-file-upload-wc' );

			// Get error msg from option settings
			if( get_option('wcf_drag_n_drop_error_min_file') ) {
				$error = get_option('wcf_drag_n_drop_error_min_file');
			}

			wc_add_notice( str_replace( '%s', (int)$minimum_file , $error ) , 'error' );
		}

		return $passed;
	}

	/**
	* Add item to cart
	*/

	function dndmfu_wc_add_cart_data( $cart_item_data, $product_id, $variation_id ) {

		$dir = trailingslashit( dndmfu_wc_dir() );
		$name = dndmfu_wc_get_filename();
		$post_files = ( isset( $_POST[ $name ] ) ? array_map('sanitize_text_field', $_POST[ $name ] ) : null );
		$files = array();

		if( $post_files ) {

			// Loop files
			foreach( $post_files as $file ) {
				$tmp_file = $dir . wc_clean( wp_unslash( $file ) );
				if( file_exists( $tmp_file ) ) {
                    $new_name = wp_unique_filename( $dir, wp_basename( $file ) );
					if( rename( $tmp_file, $dir . $new_name ) ) {
						$files[] = wp_basename( $new_name );
					}
				}
			}

			// Add files to cart items
			$cart_item_data['dnd-wc-file-upload'] = $files;
		}

		return $cart_item_data;
	}

	/**
	* Display cart items
	*/

	function dndmfu_wc_get_cart_item( $item_data, $cart_item_data ) {

		if( isset( $cart_item_data[ 'dnd-wc-file-upload' ] ) ) {

			// Get files - return an array
			$files_upload = dndmfu_wc_get_files( $cart_item_data['dnd-wc-file-upload'] );

			// setup item data
			if( $files_upload ) {
				$item_data[] = array(
					'key' 	=> apply_filters('dndmfu_wc_cart_item_title', __( 'Files Upload', 'dnd-file-upload-wc' ) ),
					'value' => apply_filters( 'dndmfu_wc_cart_items', implode("\n", $files_upload ) )
				);
			}
		}

		return $item_data;
	}

	/**
	* Add custom meta to order - after payment
	*/

	function dndmfu_wc_order_item( $item, $cart_item_key, $values, $order ) {

		if( isset( $values['dnd-wc-file-upload'] ) ) {

			// Get all files
			$files_upload = dndmfu_wc_get_files( $values['dnd-wc-file-upload'] );

			// Setup order meta - order details
			if( $files_upload ) {
				$item->add_meta_data(
					apply_filters( 'dndmfu_wc_order_item_title', __( 'Files Upload', 'dnd-file-upload-wc' ) ),
					implode( ", ", $files_upload ),
					true
				);
			}

		}

	}

	/**
	* Add custom cart item data - Email
	*/

	function dndmfu_wc_order_item_name( $product_name, $item ) {
		if( isset( $item['dnd-wc-file-upload'] ) ) {
			$product_name .= sprintf( '<ul><li>%s: %s</li></ul>', __( 'Files Upload', 'dnd-file-upload-wc' ), implode( "\n", $item['dnd-wc-file-upload'] ) );
		}
		return $product_name;
	}

	/**
	* Cart Quantity Update - When reaches 0 ( Delete also the files )
	*/

	function dndmfu_wc_update_cart_validation( $passed, $cart_key, $cart_item, $quantity ) {

		if( $quantity === 0 ) {
			$dir = dndmfu_wc_dir();
			$cart_files = ( isset( $cart_item['dnd-wc-file-upload'] ) ? $cart_item['dnd-wc-file-upload'] : null );
			if( $cart_files ) {
				foreach( $cart_files as $file ) {
					$file = realpath( trailingslashit( $dir ) . wp_basename( $file ) );
					if( file_exists( $file ) ) {
						dndmfu_wc_delete_file( $file );
					}
				}
			}
		}

		return $passed;
	}

	/**
	* Remove files - from remove cart contents ( only items - deleted from the cart )
	*/

	function dndmfu_wc_remove_files_from_contents() {
		if ( is_admin() || 'GET' != $_SERVER['REQUEST_METHOD'] || is_robots() || is_feed() || is_trackback() ) {
			return;
		}

		// Get remove items from cart
		$items = WC()->cart->get_removed_cart_contents();
		$dir = dndmfu_wc_dir();
		$files = array();

		// Get files upload only.
		if( $items ) {
			foreach( $items as $item ) {
				if( isset( $item['dnd-wc-file-upload'] ) && count( $item['dnd-wc-file-upload'] ) > 0 ) {
					foreach( $item['dnd-wc-file-upload'] as $_file ) {
						$cart_files = realpath( trailingslashit( $dir ) . $_file );
						if( file_exists( $cart_files ) ) {
							$seconds = apply_filters('dndmfu_wc_time_before_cart_deletion', 300 ); // 5 minutes
							$uploaded_time = @filemtime( $cart_files );
							if( $uploaded_time && time() < $uploaded_time + absint( $seconds ) ) { //modified > current_time
								continue;
							}
							dndmfu_wc_delete_file( $cart_files );
						}
					}
				}
			}
		}
	}
