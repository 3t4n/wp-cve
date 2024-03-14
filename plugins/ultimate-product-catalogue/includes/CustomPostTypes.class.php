<?php
/**
 * Class to handle all custom post type definitions for Ultimate Product Catalog
 */

if ( !defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'ewdupcpCustomPostTypes' ) ) {
class ewdupcpCustomPostTypes {

	public function __construct() {

		// Call when plugin is initialized on every page load
		add_action( 'admin_init', 		array( $this, 'create_nonce' ) );
		add_action( 'init', 			array( $this, 'load_cpts' ) );

		// Handle metaboxes
		add_action( 'add_meta_boxes', 		array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', 			array( $this, 'save_product_meta' ) );
		add_action( 'save_post', 			array( $this, 'manage_create_product_capability' ) );
		add_action( 'save_post', 			array( $this, 'save_catalog_meta' ) );
		add_action( 'post_edit_form_tag', 	array( $this, 'add_multipart_form_encoding' ) );
		add_action( 'added_post_meta',	array( $this, 'manage_product_external_image_flag_bridge' ), 10, 3 );
		add_action( 'update_post_meta',		array( $this, 'manage_product_external_image_flag' ), 10, 3 );

		// Add columns and filters to the admin list of Catalogs
		add_filter( 'manage_upcp_catalog_posts_columns', 			array( $this, 'register_catalog_table_columns' ) );
		add_action( 'manage_upcp_catalog_posts_custom_column', 		array( $this, 'display_catalog_columns_content' ), 10, 2 );

		// Add columns and filters to the admin list of Categories
		add_filter( 'manage_edit-upcp-product-category_columns', 	array( $this, 'register_category_table_columns' ) );
		add_action( 'manage_upcp-product-category_custom_column', 	array( $this, 'display_category_columns_content' ), 10, 3 );

		// Add columns and filters to the admin list of Categories
		add_filter( 'manage_edit-upcp-product-tag_columns', 		array( $this, 'register_tag_table_columns' ) );
		add_action( 'manage_upcp-product-tag_custom_column', 		array( $this, 'display_tag_columns_content' ), 10, 3 );

		// Add columns and filters to the admin list of Products
		add_filter( 'manage_upcp_product_posts_columns', 			array( $this, 'register_product_table_columns' ) );
		add_action( 'manage_upcp_product_posts_custom_column', 		array( $this, 'display_product_columns_content' ), 10, 2 );
		add_filter( 'manage_edit-upcp_product_sortable_columns', 	array( $this, 'register_post_column_sortables' ) );
		add_filter( 'request', 										array( $this, 'orderby_custom_columns' ) );
		//add_filter( 'restrict_manage_posts', 						array( $this, 'add_categories_dropdown' ) ); //@to-do: not working, add back in when you've got time to fix

		// Sort the produts by custom ordering, save updated order
		add_action( 'pre_get_posts', 								array( $this, 'sort_products_by_order' ) );
		add_action( 'wp_ajax_ewd_upcp_update_product_order', 		array( $this, 'update_product_order' ) );

		// Add an image input for categories, save image data
		add_action( EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY . '_add_form_fields',	array( $this, 'add_category_image_field' ) );
		add_action( EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY . '_edit_form_fields',	array( $this, 'edit_category_image_field' ) );
		add_action( 'create_' . EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY, 			array( $this, 'update_category_data' ) );
		add_action( 'edit_' . EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY, 				array( $this, 'update_category_data' ) );
		add_action( 'create_' . EWD_UPCP_PRODUCT_TAG_TAXONOMY, 					array( $this, 'update_tag_data' ) );
		add_action( 'edit_' . EWD_UPCP_PRODUCT_TAG_TAXONOMY, 					array( $this, 'update_tag_data' ) );

		// Sort taxnomy tables by custom ordering, save updated order
		add_action( 'terms_clauses', array( $this, 'sort_categories_and_tags_by_order' ), 10, 2 );
		add_action( 'wp_ajax_ewd_upcp_update_category_order', array( $this, 'update_category_and_tag_order' ) );
		add_action( 'wp_ajax_ewd_upcp_update_tag_order', array( $this, 'update_category_and_tag_order' ) );

		// Add the option to bulk reset views from Products
		add_filter( 'bulk_actions-edit-upcp_product', 			array( $this, 'add_reset_view_count_bulk_action' ) );
		add_filter( 'handle_bulk_actions-edit-upcp_product', 	array( $this, 'handle_reset_view_count_bulk_action' ), 10, 3 );

		// Add a link to edit an individual product when viewing it via the catalog on the front-end
		add_action( 'admin_bar_menu', array( $this, 'add_toolbar_edit_product' ), 999 );
	}

	/**
	 * Initialize custom post types
	 * @since 5.0.0
	 */
	public function load_cpts() {
		global $ewd_upcp_controller;

		// Define the product custom post type
		$args = array(
			'labels' => array(
				'name' 					=> __( 'Products',           			'ultimate-product-catalogue' ),
				'singular_name' 		=> __( 'Product',                   	'ultimate-product-catalogue' ),
				'menu_name'         	=> __( 'Product Catalog',          		'ultimate-product-catalogue' ),
				'name_admin_bar'    	=> __( 'Products',                  	'ultimate-product-catalogue' ),
				'add_new'           	=> __( 'Add New',                 		'ultimate-product-catalogue' ),
				'add_new_item' 			=> __( 'Add New Product',           	'ultimate-product-catalogue' ),
				'edit_item'         	=> __( 'Edit Product',               	'ultimate-product-catalogue' ),
				'new_item'          	=> __( 'New Product',                	'ultimate-product-catalogue' ),
				'view_item'         	=> __( 'View Product',               	'ultimate-product-catalogue' ),
				'search_items'      	=> __( 'Search Products',           	'ultimate-product-catalogue' ),
				'not_found'         	=> __( 'No Products found',          	'ultimate-product-catalogue' ),
				'not_found_in_trash'	=> __( 'No Products found in trash', 	'ultimate-product-catalogue' ),
				'all_items'         	=> __( 'Products',              		'ultimate-product-catalogue' ),
			),
			'public' 		=> true,
			'has_archive' 	=> true,
			'menu_icon' 	=> 'dashicons-feedback',
			'rewrite' 		=> array( 
				'slug' 			=> $ewd_upcp_controller->settings->get_setting( 'product-page-permalink-base' ) 
			),
			'supports' 		=> array(
				'title', 
				'editor',
				'thumbnail',
			),
			'show_in_rest' 	=> true,
			'map_meta_cap'	=> true,
			'capabilities'	=> array(
				'edit_post'					=> 'edit_upcp_product',
				'read_post'					=> 'read_upcp_product',
				'delete_post'				=> 'delete_upcp_product',
				'delete_posts'				=> 'delete_upcp_products',
				'delete_private_posts'		=> 'delete_private_upcp_products',
				'delete_published_posts'	=> 'delete_published_upcp_products',
				'delete_others_posts'		=> 'delete_others_upcp_products',
				'edit_posts'				=> 'edit_upcp_products',
				'edit_private_posts'		=> 'edit_private_upcp_products',
				'edit_published_posts'		=> 'edit_published_upcp_products',
				'edit_others_posts'			=> 'edit_others_upcp_products',
				'publish_posts'				=> 'publish_upcp_products',
				'read_private_posts'		=> 'read_private_upcp_products',
				'create_posts'				=> 'create_upcp_products',
			),
		);

		// Create filter so addons can modify the arguments
		$args = apply_filters( 'ewd_upcp_products_args', $args );

		// Add an action so addons can hook in before the post type is registered
		do_action( 'ewd_upcp_products_pre_register' );

		// Register the post type
		register_post_type( EWD_UPCP_PRODUCT_POST_TYPE, $args );

		// Add an action so addons can hook in after the post type is registered
		do_action( 'ewd_upcp_products_post_register' );

		// Define the product category taxonomy
		$args = array(
			'labels' => array(
				'name' 				=> __( 'Product Categories',			'ultimate-product-catalogue' ),
				'singular_name' 	=> __( 'Product Category',				'ultimate-product-catalogue' ),
				'search_items' 		=> __( 'Search Product Categories', 	'ultimate-product-catalogue' ),
				'all_items' 		=> __( 'All Product Categories', 		'ultimate-product-catalogue' ),
				'parent_item' 		=> __( 'Parent Product Category', 		'ultimate-product-catalogue' ),
				'parent_item_colon' => __( 'Parent Product Category:', 		'ultimate-product-catalogue' ),
				'edit_item' 		=> __( 'Edit Product Category', 		'ultimate-product-catalogue' ),
				'update_item' 		=> __( 'Update Product Category', 		'ultimate-product-catalogue' ),
				'add_new_item' 		=> __( 'Add New Product Category', 		'ultimate-product-catalogue' ),
				'new_item_name' 	=> __( 'New Product Category Name', 	'ultimate-product-catalogue' ),
				'menu_name' 		=> __( 'Categories', 					'ultimate-product-catalogue' ),
            ),
			'public' 		=> true,
			'query_var'		=> true,
            'hierarchical' 	=> true,
            'show_in_rest' 	=> true,
		);

		// Create filter so addons can modify the arguments
		$args = apply_filters( 'ewd_upcp_category_args', $args );

		register_taxonomy( EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY, EWD_UPCP_PRODUCT_POST_TYPE, $args );

		// Define the review category taxonomy
		$args = array(
			'labels' => array(
				'name' 				=> __( 'Product Tags',				'ultimate-product-catalogue' ),
				'singular_name' 	=> __( 'Product Tag',				'ultimate-product-catalogue' ),
				'search_items' 		=> __( 'Search Product Tags', 		'ultimate-product-catalogue' ),
				'all_items' 		=> __( 'All Product Tags', 			'ultimate-product-catalogue' ),
				'parent_item' 		=> __( 'Parent Product Tag', 		'ultimate-product-catalogue' ),
				'parent_item_colon' => __( 'Parent Product Tag:', 		'ultimate-product-catalogue' ),
				'edit_item' 		=> __( 'Edit Product Tag', 			'ultimate-product-catalogue' ),
				'update_item' 		=> __( 'Update Product Tag', 		'ultimate-product-catalogue' ),
				'add_new_item' 		=> __( 'Add New Product Tag', 		'ultimate-product-catalogue' ),
				'new_item_name' 	=> __( 'New Product Tag Name', 		'ultimate-product-catalogue' ),
				'menu_name' 		=> __( 'Tags', 						'ultimate-product-catalogue' ),
            ),
			'public' 		=> true,
            'hierarchical' 	=> false,
            'show_in_rest' 	=> true,
		);

		// Create filter so addons can modify the arguments
		$args = apply_filters( 'ewd_upcp_tag_args', $args );

		register_taxonomy( EWD_UPCP_PRODUCT_TAG_TAXONOMY, EWD_UPCP_PRODUCT_POST_TYPE, $args );

		// Define the catalog custom post type
		$args = array(
			'labels' => array(
				'name' 					=> __( 'Catalogs',           			'ultimate-product-catalogue' ),
				'singular_name' 		=> __( 'Catalog',                   	'ultimate-product-catalogue' ),
				'menu_name'         	=> __( 'Catalogs',          			'ultimate-product-catalogue' ),
				'name_admin_bar'    	=> __( 'Catalogs',                  	'ultimate-product-catalogue' ),
				'add_new'           	=> __( 'Add New',                 		'ultimate-product-catalogue' ),
				'add_new_item' 			=> __( 'Add New Catalog',           	'ultimate-product-catalogue' ),
				'edit_item'         	=> __( 'Edit Catalog',               	'ultimate-product-catalogue' ),
				'new_item'          	=> __( 'New Catalog',                	'ultimate-product-catalogue' ),
				'view_item'         	=> __( 'View Catalog',               	'ultimate-product-catalogue' ),
				'search_items'      	=> __( 'Search Catalogs',           	'ultimate-product-catalogue' ),
				'not_found'         	=> __( 'No Catalogs found',          	'ultimate-product-catalogue' ),
				'not_found_in_trash'	=> __( 'No Catalogs found in trash', 	'ultimate-product-catalogue' ),
				'all_items'         	=> __( 'Catalogs',              		'ultimate-product-catalogue' ),
			),
			'public' 		=> false,
			'show_ui'		=> true,
			'show_in_menu'	=> 'edit.php?post_type=upcp_product',
			'has_archive' => true,
			'supports' => array(
				'title', 
				'editor',
			),
			'show_in_rest' => true,
		);

		// Create filter so addons can modify the arguments
		$args = apply_filters( 'ewd_upcp_catalogs_args', $args );

		// Add an action so addons can hook in before the post type is registered
		do_action( 'ewd_upcp_catalogs_pre_register' );

		// Register the post type
		register_post_type( EWD_UPCP_CATALOG_POST_TYPE, $args );

		// Add an action so addons can hook in after the post type is registered
		do_action( 'ewd_upcp_catalogs_post_register' );
	}

	/**
	 * Generate a nonce for secure saving of metadata
	 * @since 5.0.0
	 */
	public function create_nonce() {

		$this->nonce = wp_create_nonce( basename( __FILE__ ) );
	}

	/**
	 * Add in new columns for the upcp_product type
	 * @since 5.0.0
	 */
	public function add_meta_boxes() {

		$meta_boxes = array(

			// Add in the Product meta information
			'product_meta' => array (
				'id'		=>	'upcp_product_meta',
				'title'		=> esc_html__( 'Product Details', 'ultimate-product-catalogue' ),
				'callback'	=> array( $this, 'show_product_meta' ),
				'post_type'	=> EWD_UPCP_PRODUCT_POST_TYPE,
				'context'	=> 'normal',
				'priority'	=> 'high'
			),

			// Add in a link to the documentation for the plugin
			'upcp_meta_need_help' => array (
				'id'		=>	'ewd_upcp_meta_need_help',
				'title'		=> esc_html__( 'Need Help?', 'ultimate-product-catalogue' ),
				'callback'	=> array( $this, 'show_need_help_meta' ),
				'post_type'	=> EWD_UPCP_PRODUCT_POST_TYPE,
				'context'	=> 'side',
				'priority'	=> 'high'
			),

			// Add in the Catalog meta information
			'catalog_meta' => array (
				'id'		=>	'upcp_catalog_meta',
				'title'		=> esc_html__( 'Catalog Details', 'ultimate-product-catalogue' ),
				'callback'	=> array( $this, 'show_catalog_meta' ),
				'post_type'	=> EWD_UPCP_CATALOG_POST_TYPE,
				'context'	=> 'normal',
				'priority'	=> 'high'
			),
		);

		// Create filter so addons can modify the metaboxes
		$meta_boxes = apply_filters( 'ewd_upcp_meta_boxes', $meta_boxes );

		// Create the metaboxes
		foreach ( $meta_boxes as $meta_box ) {
			add_meta_box(
				$meta_box['id'],
				$meta_box['title'],
				$meta_box['callback'],
				$meta_box['post_type'],
				$meta_box['context'],
				$meta_box['priority']
			);
		}
	}

	/**
	 * Add in the extra information about a product
	 * @since 5.0.0
	 */

	public function show_product_meta( $post ) {
		global $ewd_upcp_controller;

		$premium_permission = $ewd_upcp_controller->permissions->check_permission( 'premium' );

		$tabs = array(
			'details'	=> array(
				'id'		=> 'details',
				'name'		=> 'Details',
				'callback'	=> array( $this, 'show_product_details' )
			),
			'custom_fields'	=> array(
				'id'		=> 'custom_fields',
				'name'		=> 'Custom Fields',
				'callback'	=> array( $this, 'show_product_custom_fields' )
			),
			'related'	=> array(
				'id'		=> 'related',
				'name'		=> 'Related',
				'callback'	=> array( $this, 'show_product_related_next_previous' )
			),
			'images'	=> array(
				'id'		=> 'images',
				'name'		=> 'Images',
				'callback'	=> array( $this, 'show_product_images' )
			),
			'videos'	=> array(
				'id'		=> 'videos',
				'name'		=> 'Videos',
				'callback'	=> array( $this, 'show_product_videos' )
			),
		);

		if ( empty( $premium_permission ) ) {

			unset( $tabs['custom_fields'] );
			unset( $tabs['related'] );
			unset( $tabs['images'] );
			unset( $tabs['videos'] );
		}

		// @todo: Add in ability to select individual FAQs to display for a given product
		/* if ( $ewd_upcp_controller->settings->get_setting( 'product-faqs' ) ) {

			$tabs[] = array(
				'id'		=> 'faqs',
				'name'		=> 'FAQs',
				'callback'	=> array( $this, 'show_product_faqs' )
			);
		} */

		$tabs = apply_filters( 'ewd_upcp_product_meta_tabs', $tabs );

		?>

		<div class='ewd-upcp-product-meta'>

			<div class='ewd-upcp-product-meta-menu'>

				<?php foreach ( $tabs as $count => $tab ) { ?>

					<div class='ewd-upcp-product-meta-menu-tab' data-tab_id='<?php echo esc_attr( $tab['id'] ); ?>'>
						<?php echo esc_attr( $tab['name'] ); ?>
					</div>

				<?php } ?>

			</div>

			<div class='ewd-upcp-product-meta-tabs'>

				<?php foreach ( $tabs as $tab ) { ?>

					<div class='ewd-upcp-product-meta-tab' data-tab_id='<?php echo esc_attr( $tab['id'] ); ?>'>
						<?php echo call_user_func( $tab['callback'], $post ); ?>
					</div>

				<?php } ?>

			</div>

		</div>

		<?php
	}

	/**
	 * Show the basic product details (price, link, etc.)
	 * @since 5.0.0
	 */
	public function show_product_details( $post ) { 
		global $ewd_upcp_controller;

		$price = get_post_meta( $post->ID, 'price', true );
		$sale_price = get_post_meta( $post->ID, 'sale_price', true );
		$sale_mode = get_post_meta( $post->ID, 'sale_mode', true );
		$link = get_post_meta( $post->ID, 'link', true );
		$display = $post->post_status != 'auto-draft' ? get_post_meta( $post->ID, 'display', true ) : true;

		?>
	
		<input type="hidden" name="ewd_upcp_nonce" value="<?php echo esc_attr( $this->nonce ); ?>">

		<div class='ewd-upcp-meta-field'>
			<div class='ewd-upcp-meta-field-label'>
				<label for='price'>
					<?php _e( 'Price:', 'ultimate-product-catalogue' ); ?>
				</label>
			</div>
			<div class='ewd-upcp-meta-field-input'>
				<input type='text' name='price' value='<?php echo esc_attr( $price ); ?>' size='25' />
			</div>
		</div>

		<div class='ewd-upcp-meta-field'>
			<div class='ewd-upcp-meta-field-label'>
				<label for='sale_price'>
					<?php _e( 'Sale Price:', 'ultimate-product-catalogue' ); ?>
				</label>
			</div>
			<div class='ewd-upcp-meta-field-input'>
				<input type='text' name='sale_price' value='<?php echo esc_attr( $sale_price ); ?>' size='25' />
			</div>
		</div>

		<div class='ewd-upcp-meta-field'>
			<div class='ewd-upcp-meta-field-label'>
				<label for='sale_mode'>
					<?php _e( 'Sale Mode Enabled:', 'ultimate-product-catalogue' ); ?>
				</label>
			</div>
			<div class='ewd-upcp-meta-field-input'>
				<div class='ewd-upcp-admin-hide-radios'>
					<input type='checkbox' name='sale_mode' value='1' <?php echo ( $sale_mode ? 'checked' : '' ); ?>>
				</div>
				<label class='ewd-upcp-admin-switch'>
					<input type='checkbox' class='ewd-upcp-admin-option-toggle' data-inputname='sale_mode' <?php echo ( $sale_mode ? 'checked' : '' ); ?>>
					<span class='ewd-upcp-admin-switch-slider round'></span>
				</label>
			</div>
		</div>

		<div class='ewd-upcp-meta-field'>
			<div class='ewd-upcp-meta-field-label'>
				<label for='link'>
					<?php _e( 'Product Link:', 'ultimate-product-catalogue' ); ?>
				</label>
			</div>
			<div class='ewd-upcp-meta-field-input'>
				<input type='text' name='link' value='<?php echo esc_attr( $link ); ?>' />
			</div>
		</div>

		<div class='ewd-upcp-meta-field'>
			<div class='ewd-upcp-meta-field-label'>
				<label for='display'>
					<?php _e( 'Product Displaying:', 'ultimate-product-catalogue' ); ?>
				</label>
			</div>
			<div class='ewd-upcp-meta-field-input'>
				<div class='ewd-upcp-admin-hide-radios'>
					<input type='checkbox' name='display' value='1' <?php echo ( $display ? 'checked' : '' ); ?>>
				</div>
				<label class='ewd-upcp-admin-switch'>
					<input type='checkbox' class='ewd-upcp-admin-option-toggle' data-inputname='display' <?php echo ( $display ? 'checked' : '' ); ?>>
					<span class='ewd-upcp-admin-switch-slider round'></span>
				</label>
			</div>
		</div>

		<?php

	} 

	/**
	 * Show the custom fields for this particular product
	 * @since 5.0.0
	 */
	public function show_product_custom_fields( $post ) {
		global $ewd_upcp_controller;

		$custom_fields = $ewd_upcp_controller->settings->get_custom_fields(); 

		?>

		<?php foreach ( $custom_fields as $custom_field ) { ?>

			<?php $field_value = get_post_meta( $post->ID, 'custom_field_' . $custom_field->id, true ); ?>

			<div class='ewd-upcp-meta-field'>
				<div class='ewd-upcp-meta-field-label'>
					<label class='ewd-upcp-custom-field-label' for='<?php echo esc_attr( $custom_field->name ); ?>'>
						<?php echo esc_html( $custom_field->name ) ?>
					</label>
				</div>

				<div class='ewd-upcp-meta-field-input'>
					<?php $options = explode( ',', $custom_field->options ); ?>

					<?php if ( $custom_field->type == 'textarea' ) { ?>
	
							<textarea id='ewd-upcp-<?php echo esc_attr( $custom_field->name ); ?>' name='ewd_upcp_custom_field_<?php echo esc_attr( $custom_field->id ); ?>'><?php echo esc_html( $field_value ); ?></textarea>
	
					<?php } elseif ( $custom_field->type == 'select' ) { ?>
						<?php if ( ! empty( $options ) ) { ?>
	
							<select name='ewd_upcp_custom_field_<?php echo esc_attr( $custom_field->id ); ?>'>
								<?php foreach ( $options as $option ) { ?>
	
									<option value='<?php echo esc_attr( $option ); ?>' <?php echo ( $option == $field_value ? 'selected' : '' ); ?> >
										<?php echo esc_html( $option ); ?>
									</option>
								<?php } ?>
							</select>
	
						<?php } ?>
					<?php } elseif ( $custom_field->type == 'checkbox' ) { ?>
						<?php $field_value = is_array( $field_value ) ? $field_value : array(); ?>
						<?php if ( ! empty( $options ) ) { ?>
	
							<div class='ewd-upcp-fields-page-radio-checkbox-container'>
								<?php foreach ( $options as $option ) { ?>
	
									<div class='ewd-upcp-fields-page-radio-checkbox-each'>
										<input type='checkbox' name='ewd_upcp_custom_field_<?php echo esc_attr( $custom_field->id ); ?>[]' value='<?php echo esc_attr( $option ); ?>' <?php echo ( in_array( trim( $option ), $field_value ) ? 'checked' : '' ); ?> />
										<?php echo esc_html( $option ); ?>
									</div>
								<?php } ?>
							</div>
	
						<?php } ?>
					<?php } elseif ( $custom_field->type == 'radio' ) { ?>
						<?php if ( ! empty( $options ) ) { ?>
	
							<div class='ewd-upcp-fields-page-radio-checkbox-container'>
								<?php foreach ( $options as $option ) { ?>
	
									<div class='ewd-upcp-fields-page-radio-checkbox-each'>
										<input type='radio' name='ewd_upcp_custom_field_<?php echo esc_attr( $custom_field->id ); ?>' value='<?php echo esc_attr( $option ); ?>' <?php echo ( $option == $field_value ? 'checked' : '' ); ?> />
										<?php echo esc_html( $option ); ?>
									</div>
								<?php } ?>
							</div>
	
						<?php } ?>
					<?php } elseif ( $custom_field->type == 'file' ) { ?>
	
						<?php if ( ! empty( $field_value ) ) { ?>
							
							<span>

								<?php _e( 'Current file:', 'ultimate-product-catalogue' ); ?>
								<?php echo esc_html( $field_value ); ?>

							</span>

						<?php } ?>

						<input type='file' id='ewd-upcp-<?php echo esc_attr( $custom_field->name ); ?>' name='ewd_upcp_custom_field_<?php echo esc_attr( $custom_field->id ); ?>' />
	
					<?php } elseif ( $custom_field->type == 'date' ) { ?>
	
						<input type='date' id='ewd-upcp-<?php echo esc_attr( $custom_field->name ); ?>' name='ewd_upcp_custom_field_<?php echo esc_attr( $custom_field->id ); ?>' value='<?php echo esc_attr( $field_value ); ?>' />
	
					<?php } elseif ( $custom_field->type == 'datetime' ) { ?>
	
						<input type='datetime-local' id='ewd-upcp-<?php echo esc_attr( $custom_field->name ); ?>' name='ewd_upcp_custom_field_<?php echo esc_attr( $custom_field->id ); ?>' value='<?php echo esc_attr( $field_value ); ?>' />
	
					<?php } elseif ( $custom_field->type == 'number' ) { ?>
	
						<input type='number' id='ewd-upcp-<?php echo esc_attr( $custom_field->name ); ?>' name='ewd_upcp_custom_field_<?php echo esc_attr( $custom_field->id ); ?>' value='<?php echo esc_attr( $field_value ); ?>' />
	
					<?php } elseif ( $custom_field->type == 'rich-text' ) { ?>
	
						<?php
							wp_editor(
								$field_value,
								preg_replace( '/[^\da-z]/i', '', 'ewd-upcp-'.$custom_field->name ),
								array( 'textarea_name' => 'ewd_upcp_custom_field_'.$custom_field->id )
							);
						?>
	
					<?php } else { ?>
	
						<input type='text' id='ewd-upcp-<?php echo esc_attr( $custom_field->name ); ?>' name='ewd_upcp_custom_field_<?php echo esc_attr( $custom_field->id ); ?>' value='<?php echo esc_attr( $field_value ); ?>' size='25' />
	
					<?php } ?>

				</div>

			</div>

			<?php 
		}
	}

	/**
	 * Show select boxes to add related, next and previous products
	 * @since 5.0.0
	 */
	public function show_product_related_next_previous( $post ) {

		$related_products = is_array( get_post_meta( $post->ID, 'related_products', true ) ) ? get_post_meta( $post->ID, 'related_products', true ) : array();

		$next_product = get_post_meta( $post->ID, 'next_product', true );
		$previous_product = get_post_meta( $post->ID, 'previous_product', true );

		$args = array(
			'post_type'			=> EWD_UPCP_PRODUCT_POST_TYPE,
			'posts_per_page'	=> -1
		);

		$products = get_posts( $args );

		?>

		<div class='ewd-upcp-meta-field'>
			<div class='ewd-upcp-meta-field-label'>
				<label for='related_products'>
					<?php _e( 'Related Products:', 'ultimate-product-catalogue' ); ?>
				</label>
			</div>

			<div class='ewd-upcp-meta-field-input'>

				<div class='ewd-upcp-related-product-template ewd-upcp-hidden'>
				
					<select name='related_products[]'>

						<option value=''></option>

							<?php foreach ( $products as $product ) { ?>
								<option value='<?php echo esc_attr( $product->ID ); ?>'><?php echo esc_html( $product->post_title ); ?></option>
							<?php } ?>

					</select>

				</div>

				<?php foreach ( $related_products as $related_product ) { ?>

					<div class='ewd-upcp-related-product'>
				
						<select name='related_products[]'>

							<option value=''></option>

								<?php foreach ( $products as $product ) { ?>
									<option value='<?php echo esc_attr( $product->ID ); ?>' <?php echo ( $related_product == $product->ID ? 'selected' : '' ); ?>><?php echo esc_html( $product->post_title ); ?></option>
								<?php } ?>

						</select>

						<div class='ewd-upcp-delete-related-product'>
							<?php _e( 'Delete', 'ultimate-product-catalogue' ); ?>
						</div>

					</div>

				<?php } ?>

				<div class='ewd-upcp-add-related-product'>
					<?php _e( 'Add', 'ultimate-product-catalogue' ); ?>
				</div>
			</div>
		</div>

		<div class='ewd-upcp-meta-field'>
			<div class='ewd-upcp-meta-field-label'>
				<label for='previous_product'>
					<?php _e( 'Previous Product:', 'ultimate-product-catalogue' ); ?>
				</label>
			</div>
			<div class='ewd-upcp-meta-field-input'>
				<select name='previous_product'>
					<option value=''></option>

						<?php foreach ( $products as $product ) { ?>
							<option value='<?php echo esc_attr( $product->ID ); ?>' <?php echo ( $previous_product == $product->ID ? 'selected' : '' ); ?>><?php echo esc_html( $product->post_title ); ?></option>
						<?php } ?>

				</select>
			</div>
		</div>

		<div class='ewd-upcp-meta-field'>
			<div class='ewd-upcp-meta-field-label'>
				<label for='next_product'>
					<?php _e( 'Next Product:', 'ultimate-product-catalogue' ); ?>
				</label>
			</div>
			<div class='ewd-upcp-meta-field-input'>
				<select name='next_product'>
					<option value=''></option>

						<?php foreach ( $products as $product ) { ?>
							<option value='<?php echo esc_attr( $product->ID ); ?>' <?php echo ( $next_product == $product->ID ? 'selected' : '' ); ?>><?php echo esc_html( $product->post_title ); ?></option>
						<?php } ?>

				</select>
			</div>
		</div>

		<?php
	}

	/**
	 * Allow additional images to be uploaded and assigned to a product
	 * @since 5.0.0
	 */
	public function show_product_images( $post ) {

		$product_images = is_array( get_post_meta( $post->ID, 'product_images', true ) ) ? get_post_meta( $post->ID, 'product_images', true ) : array();

		?>

		<div class='ewd-upcp-meta-field'>
			<div class='ewd-upcp-meta-field-label'>
				<label for='product_images'>
					<?php _e( 'Product Images:', 'ultimate-product-catalogue' ); ?>
				</label>
			</div>

			<div class='ewd-upcp-meta-field-input'>

				<?php foreach ( $product_images as $product_image ) { ?>

					<div class='ewd-upcp-product-image'>
				
						<div class='ewd-upcp-product-image-image'>
							<img src='<?php echo esc_attr( $product_image->url ); ?>' />
						</div>

						<input type='hidden' name='product_image_url[]' value='<?php echo esc_attr( $product_image->url ); ?>' />

						<div class='ewd-upcp-product-image-description'>
							<div class='ewd-upcp-product-image-description-label'>
								<?php _e( 'Image Description', 'ultimate-product-catalogue' ); ?>
							</div>
							<input type='text' name='product_image_description[]' value='<?php echo esc_attr( $product_image->description ); ?>' />
						</div>

						<div class='ewd-upcp-product-image-delete'>
							<div class='ewd-upcp-delete-product-image'>
								<?php _e( 'Delete', 'ultimate-product-catalogue' ); ?>
							</div>
						</div>

					</div>

				<?php } ?>

				<div class='ewd-upcp-add-product-image'>
					<?php _e( 'Add', 'ultimate-product-catalogue' ); ?>
				</div>

			</div>

		</div>

		<?php
	}

	/**
	 * Allow YouTube videos to be displayed for the product
	 * @since 5.0.0
	 */
	public function show_product_videos( $post ) {

		$product_videos = is_array( get_post_meta( $post->ID, 'product_videos', true ) ) ? get_post_meta( $post->ID, 'product_videos', true ) : array();

		?>

		<div class='ewd-upcp-meta-field'>
			<div class='ewd-upcp-meta-field-label'>
				<label for='product_videos'>
					<?php _e( 'Product Videos:', 'ultimate-product-catalogue' ); ?>
				</label>
			</div>

			<div class='ewd-upcp-meta-field-input'>

				<div class='ewd-upcp-product-video-template ewd-upcp-hidden'>

					<label><?php _e( 'YouTube Video URL', 'ultimate-product-catalogue' ); ?></label>

					<input type='text' name='product_video_url[]' value='' />

					<input type='hidden' name='product_video_type[]' value='youtube' />

					<div class='ewd-upcp-product-video-delete'>
						<div class='ewd-upcp-delete-product-video'>
							<?php _e( 'Delete', 'ultimate-product-catalogue' ); ?>
						</div>
					</div>

				</div>

				<?php foreach ( $product_videos as $product_video ) { ?>

					<div class='ewd-upcp-product-video'>
				
						<div class='ewd-upcp-product-image-image'>
							<img src='https://img.youtube.com/vi/<?php echo esc_attr( $product_video->url ); ?>/default.jpg' />
						</div>

						<input type='hidden' name='product_video_url[]' value='<?php echo esc_attr( $product_video->url ); ?>' />

						<input type='hidden' name='product_video_type[]' value='youtube' />

						<div class='ewd-upcp-product-video-delete'>
							<div class='ewd-upcp-delete-product-video'>
								<?php _e( 'Delete', 'ultimate-product-catalogue' ); ?>
							</div>
						</div>

					</div>

				<?php } ?>

				<div class='ewd-upcp-add-product-video'>
					<?php _e( 'Add', 'ultimate-product-catalogue' ); ?>
				</div>

			</div>

		</div>

		<?php
	}

	/**
	 * Add in a link to the plugin documentation
	 * @since 5.0.0
	 */
	public function show_need_help_meta() { ?>
    
    	<div class='ewd-upcp-need-help-box'>
    		<div class='ewd-upcp-need-help-text'>Visit our Support Center for documentation and tutorials</div>
    	    <a class='ewd-upcp-need-help-button' href='https://www.etoilewebdesign.com/support-center/?Plugin=UProduct' target='_blank'>GET SUPPORT</a>
    	</div>

	<?php }

	/**
	 * Add in the extra details for a catalog
	 * @since 5.0.0
	 */

	public function show_catalog_meta( $post ) { 
		global $ewd_upcp_controller;

		$items = is_array( get_post_meta( $post->ID, 'items', true ) ) ? get_post_meta( $post->ID, 'items', true ) : array();

		$args = array(
			'post_type'			=> EWD_UPCP_PRODUCT_POST_TYPE,
			'posts_per_page'	=> -1
		);

		$products = get_posts( $args );

		$args = array(
			'taxonomy'			=> EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY,
			'hide_empty'		=> false
		);

		$categories = get_terms( $args );

		?>

		<div class='ewd-upcp-add-items-background-div ewd-upcp-hidden'>

			<div class='ewd-upcp-add-items-close-button'>x</div>

			<div class='ewd-upcp-catalog-meta-add-items'>

				<div class='ewd-upcp-catalog-meta-add-items-title'><?php _e( 'Add Items to the Catalog', 'ultimate-product-catalogue' ); ?></div>

				<div class='ewd-upcp-catalog-meta-add-items-header'>

					<div class='ewd-upcp-catalog-meta-add-items-label ewd-upcp-meta-add-items-selected-label' data-selected='products'><?php _e( 'Products', 'ultimate-product-catalogue' ); ?></div>

					<div class='ewd-upcp-catalog-meta-add-items-label' data-selected='categories'><?php _e( 'Categories', 'ultimate-product-catalogue' ); ?></div>

				</div>

				<div class='ewd-upcp-catalog-meta-add-items-selection' data-selected='products'>

					<?php foreach ( $products as $product ) { ?>
						<label class='ewd-upcp-admin-input-container'>
							<input type='checkbox' class='ewd-upcp-add-items-product-checkbox' value='<?php echo esc_attr( $product->ID ); ?>' />
							<span class='ewd-upcp-admin-checkbox'></span> <span><?php echo esc_html( $product->post_title ); ?></span>
						</label>
					<?php } ?>

					<div class='ewd-upcp-meta-add-items-products'>
						<?php _e( 'Add Products', 'ultimate-product-catalogue' ); ?>
					</div>

				</div>

				<div class='ewd-upcp-catalog-meta-add-items-selection ewd-upcp-hidden' data-selected='categories'>

					<?php foreach ( $categories as $category ) { ?>
						<label class='ewd-upcp-admin-input-container'>
							<input type='checkbox' class='ewd-upcp-add-items-category-checkbox' value='<?php echo esc_attr( $category->term_id ); ?>' />
							<span class='ewd-upcp-admin-checkbox'></span> <span><?php echo esc_html( $category->name ); ?></span>
						</label>
					<?php } ?>

					<div class='ewd-upcp-meta-add-items-categories'>
						<?php _e( 'Add Categories', 'ultimate-product-catalogue' ); ?>
					</div>

				</div>

			</div>

		</div>

		<div class='ewd-upcp-catalog-meta'>

			<div class='ewd-upcp-catalog-meta-current-items'>

				<input type="hidden" name="ewd_upcp_nonce" value="<?php echo esc_attr( $this->nonce ); ?>">

				<table class='ewd-upcp-catalog-meta-items'>

					<thead>

						<tr>

							<th></th>

							<th><?php _e( 'Item Name', 'ultimate-product-catalogue' ); ?></th>

							<th><?php _e( 'Item Type', 'ultimate-product-catalogue' ); ?></th>

						</tr>

					</thead>

					<tbody>

						<?php foreach ( $items as $item ) { ?>

							<?php $item_name = $item->type == 'product' ? get_the_title( $item->id ) : get_term( $item->id, EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY )->name; ?>

							<?php if ( empty( $item_name ) ) { continue; } ?>

							<tr data-item_name='<?php echo esc_attr( $item_name ); ?>'>

								<td class='ewd-upcp-catalog-meta-item-delete'><?php _e( 'Delete', 'ultimate-product-catalogue' ); ?></td>

								<td>

									<input type='hidden' name='catalog_item_id[]' value='<?php echo esc_attr( $item->id ); ?>' />

									<input type='hidden' name='catalog_item_type[]' value='<?php echo esc_attr( $item->type ); ?>' />

									<?php echo esc_html( $item_name ) ?>
										
								</td>

								<td><?php echo ( $item->type == 'product' ? __( 'Product', 'ultimate-product-catalogue' ) : __( 'Category', 'ultimate-product-catalogue' ) ); ?></td>

							</tr>

						<?php } ?>

					</tbody>

					<tfoot>

						<tr>

							<td class='ewd-upcp-catalog-meta-add-items-button' colspan='3'><?php _e( 'Add', 'ultimate-product-catalogue' ); ?></td>

						</tr>

					</tfoot>

				</table>

			</div>

			<div class='ewd-upcp-catalog-meta-sort'>

				<button class='ewd-upcp-catalog-sort-items-alphabetically'>
					<?php _e( 'Sort Items Alphabetically (A-Z)', 'ultimate-product-catalogue' ); ?>
				</button>

				<button class='ewd-upcp-catalog-sort-items-reverse-alphabetically'>
					<?php _e( 'Sort Items Reverse Alphabetically (Z-A)', 'ultimate-product-catalogue' ); ?>
				</button>

			</div>

		</div>

		<?php
	}

	/**
	 * Save the metabox data for each product
	 * @since 5.0.0
	 */
	public function save_product_meta( $post_id ) {
		global $ewd_upcp_controller;

		if ( get_post_type( $post_id ) != EWD_UPCP_PRODUCT_POST_TYPE ) { return; }

		// Verify nonce
		if ( ! isset( $_POST['ewd_upcp_nonce'] ) || ! wp_verify_nonce( $_POST['ewd_upcp_nonce'], basename( __FILE__ ) ) ) {

			return $post_id;
		}

		// Check autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {

			return $post_id;
		}

		// Check permissions
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		if ( ! get_post_meta( $post_id, 'order', true ) ) { update_post_meta( $post_id, 'order', 9999 ); }

		if ( isset( $_POST['price'] ) ) 			{ update_post_meta( $post_id, 'price', sanitize_text_field( $_POST['price'] ) ); }
		if ( isset( $_POST['sale_price'] ) ) 		{ update_post_meta( $post_id, 'sale_price', sanitize_text_field( $_POST['sale_price'] ) ); }
		if ( isset( $_POST['link'] ) ) 				{ update_post_meta( $post_id, 'link', sanitize_text_field( $_POST['link'] ) ); }

		update_post_meta( $post_id, 'sale_mode', empty( $_POST['sale_mode'] ) ? false : true );
		update_post_meta( $post_id, 'display', empty( $_POST['display'] ) ? false : true );
		
		if ( isset( $_POST['related_products'] ) ) 	{ update_post_meta( $post_id, 'related_products', is_array( $_POST['related_products'] ) ? array_filter( array_map( 'sanitize_text_field', $_POST['related_products'] ) ) : array() ); }
		if ( isset( $_POST['next_product'] ) ) 		{ update_post_meta( $post_id, 'next_product', sanitize_text_field( $_POST['next_product'] ) ); }
		if ( isset( $_POST['previous_product'] ) ) 	{ update_post_meta( $post_id, 'previous_product', sanitize_text_field( $_POST['previous_product'] ) ); }

		$custom_fields = $ewd_upcp_controller->settings->get_custom_fields();

		foreach ( $custom_fields as $custom_field ) { 
			
			$input_name = 'ewd_upcp_custom_field_' . $custom_field->id;

			if ( $custom_field->type == 'file' ) {

				if ( empty( $_FILES[ $input_name ]['name'] ) ) { 
					
					$field_value = get_post_meta( $post_id, 'custom_field_' . $custom_field->id, true ); 
				}
				else {
					
					$uploaded_file = wp_handle_upload( $_FILES[ $input_name ], array( 'test_form' => false ) );
					$field_value = $uploaded_file['url'];
				}
			}
			elseif ( $custom_field->type == 'checkbox' ) {

				$field_value = ( isset( $_POST[ $input_name ] ) and is_array( $_POST[ $input_name ] ) ) ? array_map( 'sanitize_text_field', $_POST[ $input_name ] ) : array();
			}
			else {
				
				$field_value = wp_kses( $_POST[ $input_name ], 'post' );
			}

			update_post_meta( $post_id, 'custom_field_' . $custom_field->id, $field_value );
		}

		$this->save_product_images( $post_id );

		$this->save_product_videos( $post_id );

		do_action( 'ewd_upcp_product_saved', $post_id );
	}

	/**
	 * Save the metabox data for each catalog
	 * @since 5.0.0
	 */
	public function save_catalog_meta( $post_id ) {
		global $ewd_upcp_controller;

		if ( get_post_type( $post_id) != EWD_UPCP_CATALOG_POST_TYPE ) { return; }

		// Verify nonce
		if ( ! isset( $_POST['ewd_upcp_nonce'] ) || ! wp_verify_nonce( $_POST['ewd_upcp_nonce'], basename( __FILE__ ) ) ) {

			return $post_id;
		}

		// Check autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {

			return $post_id;
		}

		// Check permissions
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		$catalog_items = array();

		foreach ( $_POST['catalog_item_id'] as $key => $item_id ) {

			if ( empty( $item_id ) ) { continue; }

			$catalog_item = (object) array(
				'id'	=> intval( $item_id ),
				'type'	=> ! empty( $_POST['catalog_item_type'][ $key ] ) ? sanitize_text_field( $_POST['catalog_item_type'][ $key ] ) : '',
			);

			$catalog_items[] = $catalog_item;
		}

		update_post_meta( $post_id, 'items', $catalog_items );
	}

	/**
	 * Save any product images as objects in an array
	 * @since 5.0.0
	 */
	public function save_product_images( $post_id ) {

		$images = array_key_exists( 'product_image_url', $_POST ) ? $_POST['product_image_url'] : array();

		$product_images = array();

		foreach ( $_POST['product_image_url'] as $key => $image_url ) {

			if ( empty( $image_url ) ) { continue; }

			$product_image = (object) array(
				'url'			=> esc_url_raw( $image_url ),
				'description'	=> ! empty( $_POST['product_image_description'][ $key ] ) ? sanitize_text_field( $_POST['product_image_description'][ $key ] ) : '',
			);

			$product_images[] = $product_image;
		}

		update_post_meta( $post_id, 'product_images', $product_images );
	}

	/**
	 * Save any product videos as objects in an array
	 * @since 5.0.0
	 */
	public function save_product_videos( $post_id ) {

		$videos = array_key_exists( 'product_video_url', $_POST ) ? $_POST['product_video_url'] : array();

		$product_videos = array();

		foreach ( $videos as $key => $video_url ) {

			if ( empty( $video_url ) ) { continue; }

			if ( strtolower( substr( $video_url, 0, 4 ) ) == 'http' ) {
			
				$parsed = parse_url( $video_url );

				parse_str( $parsed['query'], $params );

				$video_id = sanitize_text_field( $params['v'] );
			}
			else {

				$video_id = sanitize_text_field( $video_url );
			}

			$product_video = (object) array(
				'url'			=> $video_id,
				'type'			=> ! empty( $_POST['product_video_type'][ $key ] ) ? sanitize_text_field( $_POST['product_video_type'][ $key ] ) : '',
			);

			$product_videos[] = $product_video;
		}

		update_post_meta( $post_id, 'product_videos', $product_videos );
	}

	/**
	 * Add in multi-part encoding to handle file uploading in custom fields
	 * @since 5.0.0
	 */
	public function add_multipart_form_encoding() {
		global $post;

    	if ( ! $post ) { return; }

    	if ( get_post_type( $post->ID ) != EWD_UPCP_PRODUCT_POST_TYPE ) { return; }

    	echo ' enctype="multipart/form-data"';
	}

	/**
	 * Remove the external image flag, if thumbnail image set for a product
	 * @since 5.1.4
	 */
	public function manage_product_external_image_flag_bridge( $meta_id, $post_id, $meta_key )
	{
		$this->manage_product_external_image_flag( $meta_id, $post_id, $meta_key );
	}

	/**
	 * Remove the external image flag, if the attachment ID is set for a product
	 * @since 5.0.0
	 */
	public function manage_product_external_image_flag( $meta_id, $post_id, $meta_key ) {

		if ( $meta_key != '_thumbnail_id' ) { return; }

		if ( get_post_type( $post_id ) != EWD_UPCP_PRODUCT_POST_TYPE ) { return; }

		delete_post_meta( $post_id, 'external_image' );
		delete_post_meta( $post_id, 'external_image_url' );
	}

	/**
	 * Adds in a field to upload an image for categories
	 * @since 5.0.0
	 */
	public function add_category_image_field( $term ) { 

		?>

		<div class="form-field ewd-upcp-category-image-wrap">
			<label><?php _e( 'Image', 'ultimate-product-catalogue' ); ?></label>
			<div class='ewd-upcp-color-image-upload'>
				<input id="category_image" type="text" size="36" name="category_image" value="http://" />
				<input id="category_image_button" class="button" type="button" value="Upload Image" />
			</div>
		</div>

	<?php } 

	/**
	 * Adds in a field to upload an image for categories
	 * @since 5.0.0
	 */
	public function edit_category_image_field( $term ) { 

		$image = get_term_meta( $term->term_id, 'image', true );

		?>

		<tr class="form-field ewd-upcp-category-image-wrap"> 
			<th scope="row"><?php _e( 'Image', 'ultimate-product-catalogue' ); ?></th>
			<td>	
				<input id="category_image" type="text" size="36" name="category_image" value="<?php echo esc_attr( $image ); ?>" />
				<input id="category_image_button" class="button" type="button" value="Upload Image" />
			</td>
		</tr>

	<?php } 

	/**
	 * Sort the category and tag taxonomy terms by the user-set custom order
	 * @since 5.0.0
	 */
	public function sort_categories_and_tags_by_order( $clauses, $taxonomies ) {
		global $wpdb;

		if ( ! in_array( EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY, $taxonomies ) and ! in_array( EWD_UPCP_PRODUCT_TAG_TAXONOMY, $taxonomies ) ) { return $clauses; }
		if ( ! empty( $clauses['orderby'] ) and $clauses['orderby'] != 'ORDER BY t.name' ) { return $clauses; }

		$clauses['join'] .= ' INNER JOIN ' . $wpdb->prefix . 'termmeta AS tm ON t.term_id = tm.term_id';

		$clauses['where'] .= ' AND tm.meta_key = "order"';

		$clauses['orderby'] = 'ORDER BY tm.meta_value+0';

		return $clauses;
	}

	/**
	 * Save the user-set custom order for the category and tag taxonomy terms
	 * @since 5.0.0
	 */
	public function update_category_and_tag_order() {
		global $ewd_upcp_controller;

		// Authenticate request
		if ( 
			! check_ajax_referer( 'ewd-upcp-admin-js', 'nonce' )
			||
			! current_user_can( $ewd_upcp_controller->settings->get_setting( 'access-role' ) )
		) {
			ewdupcpHelper::admin_nopriv_ajax();
		}

		$ids = is_array( $_POST['tag'] ) ? array_map( 'intval', $_POST['tag'] ) : array();
		
		foreach ( $ids as $order => $term_id ) {

			update_term_meta( $term_id, 'order', $order );
		}
	}

	/**
	 * Saves the meta data for each category
	 * @since 5.0.0
	 */
	public function update_category_data( $term_id ) { 

		// Save the image associated with this term
		if ( isset( $_POST['category_image'] ) and $_POST['category_image'] != 'http://' ) {

			update_term_meta( $term_id, 'image', sanitize_text_field( $_POST['category_image'] ) );
		}

		if ( empty( get_term_meta( $term_id, 'order', true ) ) ) {

			update_term_meta( $term_id, 'order', 9999 );
		}
	}

	/**
	 * Saves the meta data for each tag
	 * @since 5.0.0
	 */
	public function update_tag_data( $term_id ) {

		if ( empty( get_term_meta( $term_id, 'order', true ) ) ) {

			update_term_meta( $term_id, 'order', 9999 );
		}
	}

	/**
	 * Add in new columns for the upcp_product type
	 * @since 5.0.19
	 */
	public function register_catalog_table_columns( $defaults ) {

		$defaults['ewd_upcp_catalog_id'] = __( 'Catalog ID', 'ultimate-product-catalogue' );
		$defaults['ewd_upcp_catalog_shortcode'] = __( 'Shortcode', 'ultimate-product-catalogue' );

		return $defaults;
	}

	/**
	 * Set the content for the custom columns
	 * @since 5.0.19
	 */
	public function display_catalog_columns_content ( $column_name, $post_id ) {

		if ( $column_name == 'ewd_upcp_catalog_id' ) {

			echo intval( $post_id );
		}

		if ( $column_name == 'ewd_upcp_catalog_shortcode' ) {

			echo "[product-catalogue id='" . intval( $post_id ) . "']"; 
		}
	}

	/**
	 * Add in new columns for the upcp-product-category taxonomy type
	 * @since 5.1.8
	 */
	public function register_category_table_columns( $defaults ) {

		$defaults['ewd_upcp_category_id'] = __( 'Category ID', 'ultimate-product-catalogue' );

		return $defaults;
	}

	/**
	 * Set the content for the custom category columns
	 * @since 5.1.8
	 */
	public function display_category_columns_content( $string, $column_name, $term_id ) {

		if ( $column_name == 'ewd_upcp_category_id' ) {

			echo intval( $term_id );
		}
	}

	/**
	 * Add in new columns for the upcp-product-tag taxonomy type
	 * @since 5.1.8
	 */
	public function register_tag_table_columns( $defaults ) {

		$defaults['ewd_upcp_tag_id'] = __( 'Tag ID', 'ultimate-product-catalogue' );

		return $defaults;
	}

	/**
	 * Set the content for the custom tag columns
	 * @since 5.1.8
	 */
	public function display_tag_columns_content( $string, $column_name, $term_id ) {

		if ( $column_name == 'ewd_upcp_tag_id' ) {

			echo intval( $term_id );
		}
	}

	/**
	 * Add in new columns for the upcp_product type
	 * @since 5.0.0
	 */
	public function register_product_table_columns( $defaults ) {
		global $ewd_upcp_controller;
		
		$defaults['ewd_upcp_description'] = __( 'Description', 'ultimate-product-catalogue' );
		$defaults['ewd_upcp_price'] = __( 'Price', 'ultimate-product-catalogue' );
		$defaults['ewd_upcp_categories'] = __( 'Categories', 'ultimate-product-catalogue' );
		$defaults['ewd_upcp_subcategories'] = __( 'Sub-Categories', 'ultimate-product-catalogue' );
		$defaults['ewd_upcp_views'] = __( '# of Views', 'ultimate-product-catalogue' );
		$defaults['ewd_upcp_post_id'] = __( 'Post ID', 'ultimate-product-catalogue' );

		return $defaults;
	}


	/**
	 * Set the content for the custom columns
	 * @since 5.0.0
	 */
	public function display_product_columns_content ( $column_name, $post_id ) {
		
		if ( $column_name == 'ewd_upcp_description' ) {

			echo substr( strip_tags( get_the_content( null, false, $post_id ) ), 0, 60 );
		}

		if ( $column_name == 'ewd_upcp_price' ) {

			echo ( get_post_meta( $post_id, 'price', true ) ? get_post_meta( $post_id, 'price', true ) : 0 );
		}
		
		if ( $column_name == 'ewd_upcp_categories' ) {

			$args = array(
				'fields'	=> 'names',
				'parent'	=> 0
			);

			echo implode( ',', wp_get_post_terms( $post_id, EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY, $args ) );
		}

		if ( $column_name == 'ewd_upcp_subcategories' ) {

			$categories = wp_get_post_terms( $post_id, EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY );

			$subcategory_string = '';

			foreach ( $categories as $category ) {

				if ( empty( $category->parent ) ) { continue; }

				$subcategory_string .= $category->name;
			}

			echo trim( $subcategory_string, ',' );
		}

		if ( $column_name == 'ewd_upcp_views' ) {

			echo ( get_post_meta( $post_id, 'views', true ) ? get_post_meta( $post_id, 'views', true ) : 0 );
		}

		if ( $column_name == 'ewd_upcp_post_id' ) {

			echo intval( $post_id );
		}
	}

	/**
	 * Register the sortable columns
	 * @since 5.0.0
	 */
	public function register_post_column_sortables( $column ) {
		global $ewd_upcp_controller;
	    
	    $column['ewd_upcp_price'] = 'ewd_upcp_price';
	    $column['ewd_upcp_views'] = 'ewd_upcp_views';

   		return $column;
	}

	/**
	 * Adjust the wp_query if the orderby clause is one of the custom ones
	 * @since 5.0.0
	 */
	public function orderby_custom_columns( $vars ) {
		global $wpdb;

		if ( ! isset( $vars['orderby'] ) ) { return $vars; }

		if ( $vars['orderby'] == 'ewd_upcp_views' ) {
			
			$vars = array_merge( 
				$vars, 
				array(
        	    	'meta_key' => 'views',
        	    	'orderby' => 'meta_value_num'
        	    ) 
        	);
		}

		if ( $vars['orderby'] == 'ewd_upcp_price' ) {
			
			$vars = array_merge( 
				$vars, 
				array(
        	    	'meta_key' => 'price',
        	    	'orderby' => 'meta_value_num'
        	    ) 
        	);
		}

		return $vars;
	}


	/**
	 * Add a select box for the Product's category for Product posts
	 * @since 5.0.0
	 */
	public function add_categories_dropdown() {
		global $typenow;
	    global $wp_query;

	    if ( $typenow != EWD_UPCP_PRODUCT_POST_TYPE ) { return; }

	    $product_taxonomy = get_taxonomy( EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY );

	    $args = array(
	        'show_option_all' =>  __("Show All {$product_taxonomy->label}"),
	        'taxonomy'        =>  EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY,
	        'name'            =>  EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY,
	        'orderby'         =>  'name',
	        'selected'        =>  isset( $wp_query->query['term'] ) ? $wp_query->query['term'] : '',
	        'hierarchical'    =>  true,
	        'depth'           =>  3,
	        'show_count'      =>  true, // Show # listings in parens
	        'hide_empty'      =>  true,
	    );

	    wp_dropdown_categories( $args );
	}

	/**
	 * Sort the products by the user-set custom order
	 * @since 5.0.0
	 */
	public function sort_products_by_order( $query ) {

		if ( ! is_admin() ) { return; }

		if ( ! isset( $_GET['post_type'] ) ) { return; } 

		if ( $_GET['post_type'] != EWD_UPCP_PRODUCT_POST_TYPE ) { return; }

		if ( ! empty( $_GET['page'] ) ) { return; }

		if ( isset( $_GET['orderby'] ) ) { return; }
		
		$query->set( 'meta_key', 'order' );
		$query->set( 'orderby', 'meta_value_num' );
		$query->set( 'order', 'ASC' );
	}

	/**
	 * Save the user-set custom order for the products, used within categories in catalog display
	 * @since 5.0.0
	 */
	public function update_product_order() {
		global $ewd_upcp_controller;

		// Authenticate request
		if ( 
			! check_ajax_referer( 'ewd-upcp-admin-js', 'nonce' )
			||
			! current_user_can( $ewd_upcp_controller->settings->get_setting( 'access-role' ) )
		) {
			ewdupcpHelper::admin_nopriv_ajax();
		}

		$ids = is_array( $_POST['post'] ) ? array_map( 'intval', $_POST['post'] ) : array();
		
		foreach ( $ids as $order => $post_id ) {

			update_post_meta( $post_id, 'order', $order );
		}
	}

	/**
	 * Adds in a bulk action to reset the Product view count
	 * @since 5.0.0
	 */
	public function add_reset_view_count_bulk_action( $actions ) {

		$actions['reset_view_count'] = __( 'Reset View Count', 'ultimate-product-catalogue' );

		return $actions;
	}

	/**
	 * Handles the bulk action to reset the Product view count
	 * @since 5.0.0
	 */
	public function handle_reset_view_count_bulk_action( $redirect_to, $doaction, $post_ids ) {

		if ( $doaction != 'reset_view_count' ) { return $redirect_to; }

		foreach ( $post_ids as $post_id ) {
			
			update_post_meta( $post_id, 'views', 0 );
		}

		return $redirect_to;
	}

	/**
	 * Adds a link to edit a particular product to the admin bar when viewing that product
	 * @since 5.0.0
	 */
	public function add_toolbar_edit_product( $wp_admin_bar ) {
		global $ewd_upcp_controller;

		if ( empty( get_query_var('single_product') ) and empty( $_GET['singleproduct'] ) ) { return; }

		if ( ! empty( $_GET['singleproduct'] ) ) { $selected_product_id = intval( $_GET['singleproduct'] ); }
		if ( ! empty( get_query_var( 'single_product' ) ) ) { 

			$post = get_page_by_path( sanitize_text_field( trim( get_query_var( 'single_product' ), '/? ' ) ), OBJECT, EWD_UPCP_PRODUCT_POST_TYPE );

			$selected_product_id = ! empty( $post ) ? $post->ID : 0; 
		}

		if ( empty( $selected_product_id ) ) { return; }

		$edit_product_link = get_edit_post_link( $selected_product_id );

		if ( empty( $edit_product_link ) ) { return; }
		
		$args = array(
			'id'    => 'ewd_upcp_edit_product',
			'title' => __( 'Edit Product', 'ultimate-product-catalogue' ),
			'href'  => $edit_product_link,
			'meta'  => array( 'class' => 'ewd-upcp-edit-product-admin-bar-link' )
		);

		$wp_admin_bar->add_node( $args );
	}

	/**
	 * Manages the ability to create products
	 * @since 5.0.0
	 */
	public function manage_create_product_capability( $post_id ) {
		global $wp_roles;
		global $ewd_upcp_controller;

		if ( get_post_type( $post_id ) != EWD_UPCP_PRODUCT_POST_TYPE ) { return; }

		// Verify nonce
		if ( ! isset( $_POST['ewd_upcp_nonce'] ) || ! wp_verify_nonce( $_POST['ewd_upcp_nonce'], basename( __FILE__ ) ) ) {

			return $post_id;
		}

		// Check autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {

			return $post_id;
		}

		$args = array(
			'post_type' 	=> EWD_UPCP_PRODUCT_POST_TYPE,
			'numberposts'	=> 100,
		);

		$products = get_posts( $args );

		if ( $ewd_upcp_controller->permissions->check_permission( 'premium' ) or sizeof( $products ) < 100 ) { return $post_id; }

		$remove_product_roles = array(
			'administrator',
			'editor',
			'author',
			'contributor',
		);

		foreach ( $remove_product_roles as $role ) {

			$role_object = get_role( $role );

			$role_object->remove_cap( $role, 'create_upcp_products' );
		}
	}
}
} // endif;
