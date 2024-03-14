<?php

/**
 * Class to handle everything related to the walk-through that runs on plugin activation
 */

if ( ! defined( 'ABSPATH' ) )
	exit;

class ewdupcpInstallationWalkthrough {

	public function __construct() {

		add_action( 'admin_menu', array( $this, 'register_install_screen' ) );
		add_action( 'admin_head', array( $this, 'hide_install_screen_menu_item' ) );
		add_action( 'admin_init', array( $this, 'redirect' ), 9999 );

		add_action( 'admin_head', array( $this, 'admin_enqueue' ) );

		add_action( 'wp_ajax_ewd_upcp_welcome_add_category', array( $this, 'add_category' ) );
		add_action( 'wp_ajax_ewd_upcp_welcome_add_catalog', array( $this, 'add_catalog' ) );
		add_action( 'wp_ajax_ewd_upcp_welcome_set_options', array( $this, 'set_options' ) );
		add_action( 'wp_ajax_ewd_upcp_welcome_add_product', array( $this, 'add_product' ) );
	}

	/**
	 * On activation, redirect the user if they haven't used the plugin before
	 * @since 5.0.0
	 */
	public function redirect() {
		global $ewd_upcp_controller;

		if ( ! get_transient( 'ewd-upcp-getting-started' ) ) 
			return;

		delete_transient( 'ewd-upcp-getting-started' );

		if ( is_network_admin() || isset( $_GET['activate-multi'] ) )
			return;

		if ( ! empty( get_posts( array( 'post_type' => EWD_UPCP_PRODUCT_POST_TYPE ) ) ) ) {
			return;
		}

		if ( ! empty( get_option( 'UPCP_Full_Version' ) ) ) {
			return;
		}

		wp_safe_redirect( admin_url( 'index.php?page=ewd-upcp-getting-started' ) );
		exit;
	}

	/**
	 * Create the installation admin page
	 * @since 5.0.0
	 */
	public function register_install_screen() {

		add_dashboard_page(
			esc_html__( 'Ultimate Product Catalog - Welcome!', 'ultimate-product-catalogue' ),
			esc_html__( 'Ultimate Product Catalog - Welcome!', 'ultimate-product-catalogue' ),
			'manage_options',
			'ewd-upcp-getting-started',
			array($this, 'display_install_screen')
		);
	}

	/**
	 * Hide the installation admin page from the WordPress sidebar menu
	 * @since 5.0.0
	 */
	public function hide_install_screen_menu_item() {

		remove_submenu_page( 'index.php', 'ewd-upcp-getting-started' );
	}

	/**
	 * Lets the user create the categories products will go in
	 * @since 5.0.0
	 */
	public function add_category() {
		global $ewd_upcp_controller;

		// Authenticate request
		if ( 
			! check_ajax_referer( 'ewd-upcp-getting-started', 'nonce' )
			or ! current_user_can( $ewd_upcp_controller->settings->get_setting( 'access-role' ) )
		) {
			ewdupcpHelper::admin_nopriv_ajax();
		}

		$category_name = isset( $_POST['category_name'] ) ? sanitize_text_field( $_POST['category_name'] ) : '';
		$category_description = isset( $_POST['category_description'] ) ? sanitize_textarea_field( $_POST['category_description'] ) : '';

		$category_term_id = wp_insert_term( $category_name, EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY, array('description' => $category_description) );

		echo json_encode ( array( 'category_name' => $category_name, 'category_id' => $category_term_id['term_id'] ) );

		exit();
	}

	/**
	 * Lets the user create catalogues that can be displayed
	 * @since 5.0.0
	 */
	public function add_catalog() {
		global $ewd_upcp_controller;

		// Authenticate request
		if ( 
			! check_ajax_referer( 'ewd-upcp-getting-started', 'nonce' )
			or ! current_user_can( $ewd_upcp_controller->settings->get_setting( 'access-role' ) )
		) {
			ewdupcpHelper::admin_nopriv_ajax();
		}

		$args = array(
			'post_title' => isset( $_POST['catalog_name'] ) ? sanitize_text_field( $_POST['catalog_name'] ) : '',
			'post_content' => '',
			'post_status' => 'publish',
			'post_type' => EWD_UPCP_CATALOG_POST_TYPE
		);

		$catalog_id = wp_insert_post( $args );

		if ( $catalog_id ) {

			$args = array(
				'taxonomy'		=> EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY,
				'hide_empty'	=> false,
			);

			$categories = get_terms( $args );

			$items = array();

			foreach ( $categories as $category ) {

				$items[] = (object) array(
					'type'	=> 'category',
					'id'	=> $category->term_id,
				);
			}

			update_post_meta( $catalog_id, 'items', $items );
		}

		$args = array(
			'post_title' => isset( $_POST['catalog_name'] ) ? sanitize_text_field( $_POST['catalog_name'] ) : '',
			'post_content' => '<!-- wp:paragraph --><p> [product-catalogue id="' . $catalog_id . '"] </p><!-- /wp:paragraph -->',
			'post_status' => 'publish',
			'post_type' => 'page'
		);

		$post_id = wp_insert_post( $args );

		wp_send_json_success( 
			array(
				'post_id'	=> $post_id,
			)
		);

		exit();
	}

	/**
	 * Set a number of key options selected during the walk-through process
	 * @since 5.0.0
	 */
	public function set_options() {
		global $ewd_upcp_controller;

		// Authenticate request
		if ( 
			! check_ajax_referer( 'ewd-upcp-getting-started', 'nonce' )
			or ! current_user_can( $ewd_upcp_controller->settings->get_setting( 'access-role' ) )
		) {
			ewdupcpHelper::admin_nopriv_ajax();
		}

		$ewd_upcp_options = get_option( 'ewd-upcp-settings' );

		if ( isset( $_POST['currency_symbol'] ) ) { $ewd_upcp_options['currency-symbol'] = sanitize_text_field( $_POST['currency_symbol'] ); }
		if ( isset( $_POST['color_scheme'] ) ) { $ewd_upcp_options['color-scheme'] = sanitize_text_field( $_POST['color_scheme'] ); }
		if ( isset( $_POST['product_links'] ) ) { $ewd_upcp_options['product-links'] = sanitize_text_field( $_POST['product_links'] ); }
		if ( isset( $_POST['product_search'] ) ) { $ewd_upcp_options['product-search'] = explode( ',', sanitize_text_field( $_POST['product_search'] ) ); }

		update_option( 'ewd-upcp-settings', $ewd_upcp_options );

		exit();
	}

	/**
	 * Add in a new product
	 * @since 5.0.0
	 */
	public function add_product() {
		global $ewd_upcp_controller;

		// Authenticate request
		if ( 
			! check_ajax_referer( 'ewd-upcp-getting-started', 'nonce' )
			or ! current_user_can( $ewd_upcp_controller->settings->get_setting( 'access-role' ) )
		) {
			ewdupcpHelper::admin_nopriv_ajax();
		}

		$args = array(
			'post_title'	=> isset( $_POST['product_name'] ) ? sanitize_text_field( $_POST['product_name'] ) : '',
			'post_content'	=> isset( $_POST['product_description'] ) ? sanitize_textarea_field( $_POST['product_description'] ) : '',
			'post_type'		=> EWD_UPCP_PRODUCT_POST_TYPE,
			'post_status'	=> 'publish'
		);

		$post_id = wp_insert_post( $args );

		if ( ! $post_id ) { return; }

		update_post_meta( $post_id, 'order', 9999 );
		update_post_meta( $post_id, 'display', true );
		update_post_meta( $post_id, 'price', isset( $_POST['product_price'] ) ? sanitize_text_field( $_POST['product_price'] ) : 0 );

		$thumbnail_id = isset( $_POST['product_image'] ) ? attachment_url_to_postid( sanitize_url( $_POST['product_image'] ) ) : 0;

		if ( $thumbnail_id ) {

			set_post_thumbnail( $post_id, $thumbnail_id );
		}

		$term_id = isset( $_POST['product_category'] ) ? sanitize_text_field( $_POST['product_category'] ) : 0;

		if ( $term_id ) { 

			wp_set_object_terms( $post_id, intval( $term_id ), EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY );
		}
	
	    exit();
	}

	/**
	 * Enqueue the admin assets necessary to run the walk-through and display it nicely
	 * @since 5.0.0
	 */
	public function admin_enqueue() {

		if ( ! isset( $_GET['page'] ) or $_GET['page'] != 'ewd-upcp-getting-started' ) { return; }

		wp_enqueue_media();

		wp_enqueue_style( 'ewd-upcp-admin-css', EWD_UPCP_PLUGIN_URL . '/assets/css/admin.css', array(), EWD_UPCP_VERSION );
		wp_enqueue_style( 'ewd-upcp-sap-admin-css', EWD_UPCP_PLUGIN_URL . '/lib/simple-admin-pages/css/admin.css', array(), EWD_UPCP_VERSION );
		wp_enqueue_style( 'ewd-upcp-welcome-screen', EWD_UPCP_PLUGIN_URL . '/assets/css/ewd-upcp-welcome-screen.css', array(), EWD_UPCP_VERSION );
		wp_enqueue_style( 'ewd-upcp-admin-settings-css', EWD_UPCP_PLUGIN_URL . '/lib/simple-admin-pages/css/admin-settings.css', array(), EWD_UPCP_VERSION );
		
		wp_enqueue_script( 'ewd-upcp-getting-started', EWD_UPCP_PLUGIN_URL . '/assets/js/ewd-upcp-welcome-screen.js', array( 'jquery' ), EWD_UPCP_VERSION );
		wp_enqueue_script( 'ewd-upcp-admin-settings-js', EWD_UPCP_PLUGIN_URL . '/lib/simple-admin-pages/js/admin-settings.js', array( 'jquery' ), EWD_UPCP_VERSION );
		wp_enqueue_script( 'ewd-upcp-admin-spectrum-js', EWD_UPCP_PLUGIN_URL . '/lib/simple-admin-pages/js/spectrum.js', array( 'jquery' ), EWD_UPCP_VERSION );

		wp_localize_script(
			'ewd-upcp-getting-started',
			'ewd_upcp_getting_started',
			array(
				'nonce' => wp_create_nonce( 'ewd-upcp-getting-started' )
			)
		);
	}

	/**
	 * Output the HTML of the walk-through screen
	 * @since 5.0.0
	 */
	public function display_install_screen() { 
		global $ewd_upcp_controller;

		$currency_symbol = $ewd_upcp_controller->settings->get_setting( 'currency-symbol' );
		$color_scheme = $ewd_upcp_controller->settings->get_setting( 'color-scheme' );
		$product_links = $ewd_upcp_controller->settings->get_setting( 'product-links' );
		$product_search = $ewd_upcp_controller->settings->get_setting( 'product-search' );

		$args = array(
			'taxonomy'		=> EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY,
			'hide_empty'	=> false
		);

		$categories = get_terms( $args );

		?>

		<div class='ewd-upcp-welcome-screen'>
			
			<div class='ewd-upcp-welcome-screen-header'>
				<h1><?php _e('Welcome to Ultimate Product Catalog', 'ultimate-product-catalogue'); ?></h1>
				<p><?php _e('Thanks for choosing Ultimate Product Catalog! The following will help you get started with the setup by setting up your statuses, creating an order tracking page and configuring a few key options.', 'ultimate-product-catalogue'); ?></p>
			</div>

			<div class='ewd-upcp-welcome-screen-box ewd-upcp-welcome-screen-categories ewd-upcp-welcome-screen-open' data-screen='categories'>
				<h2><?php _e('1. Categories', 'ultimate-product-catalogue'); ?></h2>
				<div class='ewd-upcp-welcome-screen-box-content'>
					<p><?php _e('Create categories, which can be used to organize your products within the catalog.', 'ultimate-product-catalogue'); ?></p>
					
					<table class='form-table ewd-upcp-welcome-screen-created-categories'>
						<tr class='ewd-upcp-welcome-screen-add-category-name ewd-upcp-welcome-screen-box-content-divs'>
							<th scope='row'><?php _e( 'Category Name', 'ultimate-product-catalogue' ); ?></th>
							<td class='ewd-upcp-welcome-screen-option'>
								<input type='text'>
							</td>
						</tr>
						<tr class='ewd-upcp-welcome-screen-add-category-description ewd-upcp-welcome-screen-box-content-divs'>
							<th scope='row'><?php _e( 'Category Description', 'ultimate-product-catalogue' ); ?></th>
							<td class='ewd-upcp-welcome-screen-option'>
								<textarea></textarea>
							</td>
						</tr>
						<tr>
							<th scope='row'></th>
							<td>
								<div class='ewd-upcp-welcome-screen-add-category-button'><?php _e('Add Category', 'ultimate-product-catalogue'); ?></div>
							</td>
						</tr>
						<tr></tr>
						<tr>
							<td colspan="2">
								<h3><?php _e('Created Categories', 'ultimate-product-catalogue'); ?></h3>
								<table class='ewd-upcp-welcome-screen-show-created-categories'>
									<tr>
										<th class='ewd-upcp-welcome-screen-show-created-categories-name'><?php _e('Name', 'ultimate-product-catalogue'); ?></th>
										<th class='ewd-upcp-welcome-screen-show-created-categories-description'><?php _e('Description', 'ultimate-product-catalogue'); ?></th>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					
					<div class='ewd-upcp-welcome-clear'></div>

					<div class='ewd-upcp-welcome-screen-next-button' data-nextaction='catalog-page'><?php _e( 'Next', 'ultimate-product-catalogue' ); ?></div>

					<div class='ewd-upcp-welcome-clear'></div>
				</div>
			</div>

			<div class='ewd-upcp-welcome-screen-box ewd-upcp-welcome-screen-catalog-page' data-screen='catalog-page'>
				<h2><?php _e('2. Add an Ultimate Product Catalog Page', 'ultimate-product-catalogue'); ?></h2>
				<div class='ewd-upcp-welcome-screen-box-content'>
					<p><?php _e('You can create a dedicated catalog page below, or skip this step and add your catalog to a page you\'ve already created manually.', 'ultimate-product-catalogue'); ?></p>
					<table class='form-table ewd-upcp-welcome-screen-booking-page'>
						<tr class='ewd-upcp-welcome-screen-add-catalog-page-name ewd-upcp-welcome-screen-box-content-divs'>
							<th scope='row'><?php _e( 'Page Title', 'ultimate-product-catalogue' ); ?></th>
							<td class='ewd-upcp-welcome-screen-option'>
								<input type='text'>
							</td>
						</tr>
						<tr>
							<th scope='row'></th>
							<td>
								<div class='ewd-upcp-welcome-screen-add-catalog-page-button' data-nextaction='options'><?php _e( 'Create Page', 'ultimate-product-catalogue' ); ?></div>
							</td>
						</tr>
					</table>

					<div class='ewd-upcp-welcome-clear'></div>
					<div class='ewd-upcp-welcome-screen-next-button' data-nextaction='options'><?php _e('Next', 'ultimate-product-catalogue'); ?></div>
					<div class='ewd-upcp-welcome-screen-previous-button' data-previousaction='categories'><?php _e('Previous', 'ultimate-product-catalogue'); ?></div>
					<div class='ewd-upcp-clear'></div>
				</div>
			</div>

			<div class='ewd-upcp-welcome-screen-box ewd-upcp-welcome-screen-options' data-screen='options'>
				<h2><?php _e('3. Set Key Options', 'ultimate-product-catalogue'); ?></h2>
				<div class='ewd-upcp-welcome-screen-box-content'>
					<p><?php _e('Options can always be changed later, but here are a few tha a lot of users want to set for themselves.', 'ultimate-product-catalogue'); ?></p>
					<table class='form-table'>
						<tr>
							<th scope='row'><?php _e('Currency Symbol', 'ultimate-product-catalogue'); ?></th>
							<td class='ewd-upcp-welcome-screen-option'>
								<fieldset>
									<legend class="screen-reader-text"><span>Currency Symbol</span></legend>
									<label for='currency_symbol'></label><input type='text' name='currency_symbol' value='<?php echo esc_attr( $currency_symbol ); ?>' /><br />
									<p><?php _e('What currency symbol, if any, should be displayed before or after the price? Leave blank for none.', 'ultimate-product-catalogue'); ?></p>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th scope='row'><?php _e('Catalog Color', 'ultimate-product-catalogue'); ?></th>
							<td class='ewd-upcp-welcome-screen-option'>
								<fieldset>
									<legend class="screen-reader-text"><span>Catalog Color</span></legend>
									<label class='sap-admin-input-container'><input type='radio' name='color_scheme' value='blue' <?php if($color_scheme == "blue") {echo "checked='checked'";} ?> /><span class='sap-admin-radio-button'></span> <span><?php _e( 'Blue', 'ultimate-product-catalogue' ); ?></span></label><br />
									<label class='sap-admin-input-container'><input type='radio' name='color_scheme' value='black' <?php if($color_scheme == "black") {echo "checked='checked'";} ?> /><span class='sap-admin-radio-button'></span> <span><?php _e( 'Black', 'ultimate-product-catalogue' ); ?></span></label><br />
									<label class='sap-admin-input-container'><input type='radio' name='color_scheme' value='grey' <?php if($color_scheme == "grey") {echo "checked='checked'";} ?> /><span class='sap-admin-radio-button'></span> <span><?php _e( 'Grey', 'ultimate-product-catalogue' ); ?></span></label><br />
									<p><?php _e('Set the color of the image and border elements.', 'ultimate-product-catalogue'); ?></p>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th scope='row'><?php _e('Product Links', 'ultimate-product-catalogue'); ?></th>
							<td class='ewd-upcp-welcome-screen-option'>
								<fieldset>
									<div class='sap-admin-hide-radios'>
										<input type='checkbox' name='product_links' value='1'>
									</div>
									<label class='sap-admin-switch'>
										<input type='checkbox' class='sap-admin-option-toggle' data-inputname='product_links' <?php if ( $product_links == '1' ) { echo 'checked'; } ?>>
										<span class='sap-admin-switch-slider round'></span>
									</label>		
									<p class='description'><?php _e('Should external product links open in a new window?', 'ultimate-product-catalogue'); ?></p>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th scope='row'><?php _e('Product Search', 'ultimate-product-catalogue'); ?></th>
							<td class='ewd-upcp-welcome-screen-option'>
								<fieldset>
									<label class='sap-admin-input-container'><input type='checkbox' name='product_search[]' value='name' <?php echo ( in_array( 'name', $product_search ) ? 'checked' : '' ); ?> /><span class='sap-admin-checkbox'></span> <span><?php _e( 'Name', 'ultimate-product-catalogue' ); ?></span></label><br />
									<label class='sap-admin-input-container'><input type='checkbox' name='product_search[]' value='description' <?php echo ( in_array( 'description', $product_search ) ? 'checked' : '' ); ?> /><span class='sap-admin-checkbox'></span> <span><?php _e( 'Description', 'ultimate-product-catalogue' ); ?></span></label><br />
									<label class='sap-admin-input-container'><input type='checkbox' name='product_search[]' value='custom_fields' <?php echo ( in_array( 'custom_fields', $product_search ) ? 'checked' : '' ); ?> /><span class='sap-admin-checkbox'></span> <span><?php _e( 'Custom Fields', 'ultimate-product-catalogue' ); ?></span></label><br />
									<p class='description'><?php _e('Select which portions of a product should be searched when using the text search box? Custom fields search can take significantly longer to return results.', 'ultimate-product-catalogue'); ?></p>
								</fieldset>
							</td>
						</tr>
					</table>
		
					<div class='ewd-upcp-welcome-screen-save-options-button'><?php _e('Save Options', 'ultimate-product-catalogue'); ?></div>
					<div class='ewd-upcp-welcome-clear'></div>
					<div class='ewd-upcp-welcome-screen-next-button' data-nextaction='products'><?php _e('Next', 'ultimate-product-catalogue'); ?></div>
					<div class='ewd-upcp-welcome-screen-previous-button' data-previousaction='catalog-page'><?php _e('Previous', 'ultimate-product-catalogue'); ?></div>
					
					<div class='ewd-upcp-clear'></div>
				</div>
			</div>

			<div class='ewd-upcp-welcome-screen-box ewd-upcp-welcome-screen-products' data-screen='products'>
				<h2><?php _e('4. Create a Product', 'ultimate-product-catalogue'); ?></h2>
				<div class='ewd-upcp-welcome-screen-box-content'>
					<p><?php _e('Create your first products. Don\'t worry, you can always add more later.', 'ultimate-product-catalogue'); ?></p>
					<table class='form-table ewd-upcp-welcome-screen-created-categories'>
						<tr class='ewd-upcp-welcome-screen-add-product-name ewd-upcp-welcome-screen-box-content-divs'>
							<th scope='row'><?php _e( 'Product Name', 'ultimate-product-catalogue' ); ?></th>
							<td class='ewd-upcp-welcome-screen-option'>
								<input type='text'>
							</td>
						</tr>
						<tr class='ewd-upcp-welcome-screen-add-product-image ewd-upcp-welcome-screen-box-content-divs'>
							<th scope='row'><?php _e( 'Product Image', 'ultimate-product-catalogue' ); ?></th>
							<td class='ewd-upcp-welcome-screen-option'>
								<div class='upcp-hidden upcp-welcome-screen-image-preview'>
									<img />
								</div>
								<input type='hidden' name='product_image_url' />
								<input id='welcome_item_image_button' class='button' type='button' value='Upload Image' />
							</td>
						</tr>
						<tr class='ewd-upcp-welcome-screen-add-product-description ewd-upcp-welcome-screen-box-content-divs'>
							<th scope='row'><?php _e( 'Product Description', 'ultimate-product-catalogue' ); ?></th>
							<td class='ewd-upcp-welcome-screen-option'>
								<textarea></textarea>
							</td>
						</tr>
						<tr class='ewd-upcp-welcome-screen-add-product-price ewd-upcp-welcome-screen-box-content-divs'>
							<th scope='row'><?php _e( 'Product Price', 'ultimate-product-catalogue' ); ?></th>
							<td class='ewd-upcp-welcome-screen-option'>
								<input type='text'>
							</td>
						</tr>
						<tr class='ewd-upcp-welcome-screen-add-product-category ewd-upcp-welcome-screen-box-content-divs'>
							<th scope='row'><?php _e( 'Product Category', 'ultimate-product-catalogue' ); ?></th>
							<td class='ewd-upcp-welcome-screen-option'>
								<select>
									<?php foreach ( $categories as $key => $category ) { ?>

										<option value='<?php echo esc_attr( $category->term_id ); ?>'><?php echo esc_html( $category->name ); ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<th scope='row'></th>
							<td>
								<div class='ewd-upcp-welcome-screen-add-product-button'><?php _e('Add Product', 'ultimate-product-catalogue'); ?></div>
							</td>
						</tr>
						<tr></tr>
						<tr>
							<td colspan="2">
								<h3><?php _e('Created Products', 'ultimate-product-catalogue'); ?></h3>
								<table class='ewd-upcp-welcome-screen-show-created-products'>
									<tr>
										<th class='ewd-upcp-welcome-screen-show-created-product-name'><?php _e('Name', 'ultimate-product-catalogue'); ?></th>
										<th class='ewd-upcp-welcome-screen-show-created-product-image'><?php _e('Image', 'ultimate-product-catalogue'); ?></th>
										<th class='ewd-upcp-welcome-screen-show-created-product-price'><?php _e('Price', 'ultimate-product-catalogue'); ?></th>
										<th class='ewd-upcp-welcome-screen-show-created-product-description'><?php _e('Description', 'ultimate-product-catalogue'); ?></th>
									</tr>
								</table>
							</td>
						</tr>
					</table>

					<div class='ewd-upcp-welcome-clear'></div>
					<div class='ewd-upcp-welcome-screen-previous-button' data-previousaction='options'><?php _e('Previous', 'ultimate-product-catalogue'); ?></div>
					<div class='ewd-upcp-welcome-screen-finish-button'><a href='edit.php?post_type=upcp_product&page=ewd-upcp-settings'><?php _e('Finish', 'ultimate-product-catalogue'); ?></a></div>
					<div class='ewd-upcp-clear'></div>
				</div>
			</div>

			<div class='ewd-upcp-welcome-screen-skip-container'>
				<a href='edit.php?post_type=upcp_product&page=ewd-upcp-settings'><div class='ewd-upcp-welcome-screen-skip-button'><?php _e('Skip Setup', 'ultimate-product-catalogue'); ?></div></a>
			</div>
		</div>

	<?php }
}
