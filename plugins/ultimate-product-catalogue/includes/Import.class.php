<?php

/**
 * Class to handle importing products into the plugin
 */

if ( !defined( 'ABSPATH' ) )
	exit;

if (!class_exists('ComposerAutoloaderInit4618f5c41cf5e27cc7908556f031e4d4')) {require_once EWD_UPCP_PLUGIN_DIR . '/lib/PHPSpreadsheet/vendor/autoload.php';}
use PhpOffice\PhpSpreadsheet\Spreadsheet;
class ewdupcpImport {

	public $status;
	public $message;

	public function __construct() {
		add_action( 'admin_menu', array($this, 'register_install_screen' ));

		if ( isset( $_POST['ewdupcpImport'] ) ) { add_action( 'admin_init', array($this, 'import_products' )); }
	}

	public function register_install_screen() {
		global $ewd_upcp_controller;
		
		add_submenu_page( 
			'edit.php?post_type=upcp_product', 
			'Import Menu', 
			'Import', 
			$ewd_upcp_controller->settings->get_setting( 'access-role' ), 
			'ewd-upcp-import', 
			array( $this, 'display_import_screen' ) 
		);
	}

	public function display_import_screen() {
		global $ewd_upcp_controller;

		$import_permission = $ewd_upcp_controller->permissions->check_permission( 'import' );
		?>
		<div class='wrap'>
			<h2>Import</h2>
			<?php if ( $import_permission ) { ?> 
				<form method='post' enctype="multipart/form-data">
					
					<?php wp_nonce_field( 'EWD_UPCP_Import', 'EWD_UPCP_Import_Nonce' );  ?>

					<p>
						<label for="ewd_upcp_products_spreadsheet"><?php _e( 'Spreadsheet Containing Products', 'ultimate-product-catalogue' ) ?></label><br />
						<input name="ewd_upcp_products_spreadsheet" type="file" value=""/>
					</p>
					<input type='submit' name='ewdupcpImport' value='Import Products' class='button button-primary' />
				</form>
			<?php } else { ?>
				<div class='ewd-upcp-premium-locked'>
					<a href="https://www.etoilewebdesign.com/license-payment/?Selected=UPCP&Quantity=1&utm_source=upcp_import" target="_blank">Upgrade</a> to the premium version to use this feature
				</div>
			<?php } ?>
		</div>
	<?php }

	public function import_products() {
		global $ewd_upcp_controller;

		if ( ! current_user_can( 'edit_posts' ) ) { return; }

		if ( ! isset( $_POST['EWD_UPCP_Import_Nonce'] ) ) { return; }

    	if ( ! wp_verify_nonce( $_POST['EWD_UPCP_Import_Nonce'], 'EWD_UPCP_Import' ) ) { return; }

		$update = $this->handle_spreadsheet_upload();

    	$custom_fields = $ewd_upcp_controller->settings->get_custom_fields();

		if ( $update['message_type'] != 'Success' ) :
			$this->status = false;
			$this->message =  $update['message'];

			add_action( 'admin_notices', array( $this, 'display_notice' ) );

			return;
		endif;

		$excel_url = EWD_UPCP_PLUGIN_DIR . '/product-sheets/' . $update['filename'];

	    // Build the workbook object out of the uploaded spreadsheet
	    @$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load( $excel_url );
	
	    // Create a worksheet object out of the product sheet in the workbook
	    $sheet = $spreadsheet->getActiveSheet();
	
	    $allowable_custom_fields = array();
	    foreach ( $custom_fields as $custom_field ) { $allowable_custom_fields[] = $custom_field->name; }
	    
	    // Get column names
	    $additional_image_columns = array();

	    $highest_column = $sheet->getHighestColumn();
	    $highest_column_index = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString( $highest_column );
	    for ( $column = 1; $column <= $highest_column_index; $column++ ) {

	    	if ( empty( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) ) { continue; }

	    	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'ID' ) { $ID_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Name' ) { $name_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Slug' ) { $slug_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Description' ) { $description_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Price' ) { $price_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Sale Price' ) { $sale_price_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Image' ) { $image_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Link' ) { $link_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Category' ) { $category_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Sub-Category' ) { $subcategory_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Tags' ) { $tags_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'SEO Description' ) { $seo_decription_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Display' ) { $display_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Catalogue ID' ) { $catalog_id_column = $column; }
	
	        foreach ( $custom_fields as $custom_field ) {

        	    if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == $custom_field->name ) { $custom_field->column = $column; }
        	}

        	if ( strpos( $sheet->getCellByColumnAndRow( $column, 1 )->getValue(), 'Additional Image' ) !== FALSE ) {
				
				$additional_image_columns[] = $column;
			}
	    }

	    $ID_column = ! empty( $ID_column ) ? $ID_column : -1;
	    $name_column = ! empty( $name_column ) ? $name_column : -1;
	    $slug_column = ! empty( $slug_column ) ? $slug_column : -1;
	    $description_column = ! empty( $description_column ) ? $description_column : -1;
	    $price_column = ! empty( $price_column ) ? $price_column : -1;
	    $sale_price_column = ! empty( $sale_price_column ) ? $sale_price_column : -1;
	    $image_column = ! empty( $image_column ) ? $image_column : -1;
	    $link_column = ! empty( $link_column ) ? $link_column : -1;
	    $category_column = ! empty( $category_column ) ? $category_column : -1;
	    $subcategory_column = ! empty( $subcategory_column ) ? $subcategory_column : -1;
	    $tags_column = ! empty( $tags_column ) ? $tags_column : -1;
	    $seo_decription_column = ! empty( $seo_decription_column ) ? $seo_decription_column : -1;
	    $display_column = ! empty( $display_column ) ? $display_column : -1;
	    $catalog_id_column = ! empty( $catalog_id_column ) ? $catalog_id_column : -1;
	
	    // Put the spreadsheet data into a multi-dimensional array to facilitate processing
	    $highest_row = $sheet->getHighestRow();
	    for ( $row = 2; $row <= $highest_row; $row++ ) {
	        for ( $column = 1; $column <= $highest_column_index; $column++ ) {
	            $data[$row][$column] = $sheet->getCellByColumnAndRow( $column, $row )->getValue();
	        }
	    }
	
	    // Create the query to insert the products one at a time into the database and then run it
	    foreach ( $data as $product_data ) {

	    	// Save the data into an array, so that an product can be updated based on the product
	    	// number if it exists already
	        $product_custom_fields = array();

	        $post_data = array(
	        	'post_type'		=> EWD_UPCP_PRODUCT_POST_TYPE,
	        	'post_status'	=> 'publish'
	        );

	        $post_meta = array(
	        	'display'	=> true,
	        );

	        $product_images = array();

	        foreach ( $product_data as $col_index => $value ) {

	            if ( $col_index == $ID_column ) { $post_data['ID'] = intval( $value ); }
	            elseif ( $col_index == $name_column ) { $post_data['post_title'] = sanitize_text_field( $value ); }
            	elseif ( $col_index == $slug_column ) { $post_data['post_name'] = sanitize_text_field( $value ); }
            	elseif ( $col_index == $description_column ) { $post_data['post_content'] = wp_kses_post( $value ); }
            	elseif ( $col_index == $price_column ) { $post_meta['price'] = sanitize_text_field( $value ); }
            	elseif ( $col_index == $sale_price_column ) { $post_meta['sale_price'] = sanitize_text_field( $value ); }
            	elseif ( $col_index == $image_column ) { $post_meta['image'] = sanitize_text_field( $value ); }
            	elseif ( $col_index == $link_column ) { $post_meta['link'] = sanitize_text_field( $value ); }
            	elseif ( $col_index == $category_column ) { $post_meta['category'] = sanitize_text_field( $value ); }
            	elseif ( $col_index == $subcategory_column ) { $post_meta['subcategory'] = sanitize_text_field( $value ); }
            	elseif ( $col_index == $tags_column ) { $post_meta['tags'] = sanitize_text_field( $value ); }
            	elseif ( $col_index == $seo_decription_column ) { $post_meta['seo_description'] = sanitize_text_field( $value ); }
            	elseif ( $col_index == $display_column ) { $post_meta['display'] = sanitize_text_field( $value ); }
            	elseif ( $col_index == $catalog_id_column ) { $post_meta['catalog_id'] = intval( $value ); }
            	elseif ( in_array( $col_index, $additional_image_columns ) ) {

            		if ( empty( $value ) ) { continue; }

					$product_image = (object) array(
						'url'			=> sanitize_text_field( $value ),
						'description'	=> '',
					);

					$product_images[] = $product_image;
            	}
            	else {

            		foreach ( $custom_fields as $custom_field ) {

            			if ( isset( $custom_field->column ) and $col_index == $custom_field->column ) { $product_custom_fields[ $custom_field->id ] = wp_kses_post( $value ); }
            		}
            	}
	        }
	        
	        if ( empty( $post_data['post_title'] ) ) { continue; }

	        $post_data['post_content'] = ! empty( $post_data['post_content'] ) ? $post_data['post_content'] : '';

	        $post_id = wp_insert_post( $post_data );

	        if ( $post_id ) {

	        	if ( isset( $post_meta['price'] ) ) { update_post_meta( $post_id, 'price', $post_meta['price'] ); }
	        	if ( isset( $post_meta['sale_price'] ) ) { update_post_meta( $post_id, 'sale_price', $post_meta['sale_price'] ); }
	        	if ( ! empty( $post_meta['link'] ) ) { update_post_meta( $post_id, 'link', $post_meta['link'] ); }
	        	if ( isset( $post_meta['display'] ) ) { update_post_meta( $post_id, 'display', $post_meta['display'] ); }

	        	if ( ! empty( $post_meta['seo_description'] ) ) { update_post_meta( $post_id, '_yoast_wpseo_metadesc', $post_meta['seo_description'] ); }

	        	if ( ! empty( $post_meta['image'] ) ) {

	        		$thumbnail_id = attachment_url_to_postid( $post_meta['image'] );

					if ( $thumbnail_id ) {

						set_post_thumbnail( $post_id, $thumbnail_id );
					}
					else {
						
						update_post_meta( $post_id, 'external_image', true );
						update_post_meta( $post_id, 'external_image_url', $post_meta['image'] );
					}
	        	}

	        	if ( ! empty( $post_meta['category'] ) or ! empty( $post_meta['subcategory'] ) ) { 

	        		$subcategories = ! empty( $post_meta['subcategory'] ) ? explode( ',', $post_meta['subcategory'] ) : array();

	        		$categories = ! empty( $post_meta['category'] ) ? explode( ',', $post_meta['category'] ) : array();

	        		$submitted_categories = array_merge( $subcategories, $categories );

	        		$filtered_categories = array_filter( $submitted_categories );

	        		$category_ids = array();

	        		foreach ( $filtered_categories as $category_name ) {

	        			$category_term = get_term_by( 'name', $category_name, EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY );

	        			if ( $category_term ) { $category_ids[] = $category_term->term_id; }
	        		}

	        		if ( ! empty( $category_ids ) ) { wp_set_post_terms( $post_id, $category_ids, EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY, true ); }
	        	}

	        	if ( ! empty( $post_meta['tags'] ) ) { 

	        		wp_set_post_terms( $post_id, explode( ',', $post_meta['tags'] ), EWD_UPCP_PRODUCT_TAG_TAXONOMY );
	        	}

	        	if ( ! empty( $post_meta['catalog_id'] ) ) {

	        		$items = is_array( get_post_meta( $post_meta['catalog_id'], 'items', true ) ) ? get_post_meta( $post_meta['catalog_id'], 'items', true ) : array();

	        		$items[] = (object) array(
	        			'type'	=> 'product',
						'id'	=> $post_id
	        		);

	        		update_post_meta( $post_meta['catalog_id'], 'items', $items );
	        	}

	        	if ( ! empty( $product_images ) ) {

	        		update_post_meta( $post_id, 'product_images', $product_images );
	        	}

	        	foreach ( $product_custom_fields as $field_id => $field_value ) {

	        		foreach ( $custom_fields as $custom_field ) {

	        			if ( $custom_field->id != $field_id ) { continue; }

	        			if ( $custom_field->type == 'checkbox' ){

	        				update_post_meta( $post_id, 'custom_field_' . $field_id, explode( ',', $field_value ) );
	        			}
	        			else {

	        				update_post_meta( $post_id, 'custom_field_' . $field_id, $field_value );
	        			}
	        		}
	        	}

				update_post_meta( $post_id, 'order', 9999 );
	        }

	        do_action( 'ewd_upcp_product_saved', $post_id );
	    }

	    $this->status = true;
		$this->message = __( 'Products added successfully.', 'ultimate-product-catalogue' );

		add_action( 'admin_notices', array( $this, 'display_notice' ) );
	}

	public function handle_spreadsheet_upload() {
		  /* Test if there is an error with the uploaded spreadsheet and return that error if there is */
        if ( ! empty( $_FILES['ewd_upcp_products_spreadsheet']['error'] ) ) {
                
            switch( $_FILES['ewd_upcp_products_spreadsheet']['error'] ) {

                case '1':
                    $error = __( 'The uploaded file exceeds the upload_max_filesize directive in php.ini', 'ultimate-product-catalogue' );
                    break;
                case '2':
                    $error = __( 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form', 'ultimate-product-catalogue' );
                    break;
                case '3':
                    $error = __( 'The uploaded file was only partially uploaded', 'ultimate-product-catalogue' );
                    break;
                case '4':
                    $error = __( 'No file was uploaded.', 'ultimate-product-catalogue' );
                    break;

                case '6':
                    $error = __( 'Missing a temporary folder', 'ultimate-product-catalogue' );
                    break;
                case '7':
                    $error = __( 'Failed to write file to disk', 'ultimate-product-catalogue' );
                    break;
                case '8':
                    $error = __( 'File upload stopped by extension', 'ultimate-product-catalogue' );
                    break;
                case '999':
                    default:
                    $error = __( 'No error code avaiable', 'ultimate-product-catalogue' );
            }
        }
        /* Make sure that the file exists */
        elseif ( empty($_FILES['ewd_upcp_products_spreadsheet']['tmp_name']) || $_FILES['ewd_upcp_products_spreadsheet']['tmp_name'] == 'none' ) {
                $error = __( 'No file was uploaded here..', 'ultimate-product-catalogue' );
        }
        /* Move the file and store the URL to pass it onwards*/
        /* Check that it is a .xls or .xlsx file */ 
        if ( ! isset($_FILES['ewd_upcp_products_spreadsheet']['name'] ) or ( ! preg_match("/\.(xls.?)$/", $_FILES['ewd_upcp_products_spreadsheet']['name'] ) and ! preg_match( "/\.(csv.?)$/", $_FILES['ewd_upcp_products_spreadsheet']['name'] ) ) ) {
            $error = __( 'File must be .csv, .xls or .xlsx', 'ultimate-product-catalogue' );
        }
        else {
        	$reg_function = extension_loaded( 'mbstring' ) ? 'mb_ereg_replace' : 'ereg_replace';
            
            $filename = basename( $_FILES['ewd_upcp_products_spreadsheet']['name'] );
            $filename = $reg_function( "([^\w\s\d\-_~,;\[\]\(\).])", '', $filename );
            $filename = $reg_function("([\.]{2,})", '', $filename );

            //for security reason, we force to remove all uploaded file
            $target_path = EWD_UPCP_PLUGIN_DIR . "/product-sheets/";

            $target_path = $target_path . $filename;

            if ( ! move_uploaded_file($_FILES['ewd_upcp_products_spreadsheet']['tmp_name'], $target_path ) ) {
                $error .= "There was an error uploading the file, please try again!";
            }
            else {
                $excel_file_name = $filename;
            }
        }

        /* Pass the data to the appropriate function in Update_Admin_Databases.php to create the products */
        if ( ! isset( $error ) ) {
                $update = array( "message_type" => "Success", "filename" => $excel_file_name );
        }
        else {
                $update = array( "message_type" => "Error", "message" => $error );
        }

        return $update;
	}

	public function display_notice() {

		if ( $this->status ) {

			echo "<div class='updated'><p>" . esc_html( $this->message ) . "</p></div>";
		}
		else {

			echo "<div class='error'><p>" . esc_html( $this->message ) . "</p></div>";
		}
	}

}