<?php
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'ewdupcpAdminProductPage' ) ) {
/**
 * Class to handle the admin product page editor for Ultimate Product Catalog
 *
 * @since 5.0.0
 */
class ewdupcpAdminProductPage {

	public function __construct() {

		// Add the admin menu
		add_action( 'admin_menu', array( $this, 'add_menu_page' ), 12 );

		// Enqueue admin scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 12 );

		add_action( 'wp_ajax_ewd_upcp_save_serialized_product_page', array( $this, 'save_gridster_layout' ) );

		if ( ! empty( $_GET['action'] ) and $_GET['action'] == 'ewd_upcp_restore_default_product_page' ) {

			$this->restore_product_page();
		}
	}

	/**
	 * Add the top-level admin menu page
	 * @since 5.0.0
	 */
	public function add_menu_page() {
		global $ewd_upcp_controller;

		add_submenu_page( 
			'edit.php?post_type=upcp_product', 
			_x( 'Product Page', 'Title of admin page that lets you edit your product page', 'ultimate-product-catalogue' ),
			_x( 'Product Page', 'Title of the product page editor admin menu item', 'ultimate-product-catalogue' ), 
			$ewd_upcp_controller->settings->get_setting( 'access-role' ), 
			'ewd-upcp-product-page', 
			array( $this, 'show_admin_product_page_editor_page' )
		);
	}

	/**
	 * Display the admin custom fields page
	 * @since 5.0.0
	 */
	public function show_admin_product_page_editor_page() {
		global $ewd_upcp_controller;

		$product_page_permission = $ewd_upcp_controller->permissions->check_permission( 'product_page' );

		if ( empty( $product_page_permission ) ) { ?> 

			<div class='ewd-upcp-premium-locked'>
				<a href="https://www.etoilewebdesign.com/license-payment/?Selected=UPCP&Quantity=1&utm_source=upcp_admin_product_page" target="_blank">Upgrade</a> to the premium version to use this feature
			</div>

			<?php 

			return;

		}

		if ( ! empty( $_POST['ewd-upcp-product-tabs-submit'] ) ) {

			$this->save_product_tabs();
		}

		if ( ! empty( $_POST['ewd-upcp-large-screen-custom-fields-submit'] ) ) {

			$this->save_large_custom_product_page();
		}

		$tabs = array(
			'details'					=> __( 'Product Details', 'ultimate-product-catalogue' ),
			'additional_information'	=> __( 'Additional Information', 'ultimate-product-catalogue' ),
		);

		if ( ! empty( $ewd_upcp_controller->settings->get_setting( 'product-inquiry-form' ) ) ) {

			$tabs['contact'] = __( 'Contact Us', 'ultimate-product-catalogue' );
		}

		if ( ! empty( $ewd_upcp_controller->settings->get_setting( 'product-reviews' ) ) ) {

			$tabs['reviews'] = __( 'Customer Reviews', 'ultimate-product-catalogue' );
		}

		if ( ! empty( $ewd_upcp_controller->settings->get_setting( 'product-faqs' ) ) ) {

			$tabs['faqs'] = __( 'Product FAQs', 'ultimate-product-catalogue' );
		}

		$custom_tabs = is_array( get_option( 'ewd-upcp-product-page-tabs' ) ) ? get_option( 'ewd-upcp-product-page-tabs' ) : array();

		$tabs = array_merge( $tabs, $custom_tabs );

		$starting_tab = get_option( 'ewd-upcp-product-page-starting-tab' );
		
		?>

		<div class="wrap">
			<h1>
				<?php _e( 'Product Page', 'ultimate-product-catalogue' ); ?>
			</h1>

			<select name='product-page-selector'>
				<option value='tabbed'><?php _e( 'Tabs', 'ultimate-product-page' ); ?></option>
				<option value='custom_large'><?php _e( 'Custom Page - Large Screen', 'ultimate-product-page' ); ?></option>
				<option value='custom_mobile'><?php _e( 'Custom Page - Mobile', 'ultimate-product-page' ); ?></option>
			</select>

			<form id="ewd-upcp-additional-tabs-table" method="POST" action="">

				<div class='ewd-upcp-product-page-type' data-page='tabbed'>

					<div class='ewd-upcp-product-page-starting-tab'>

						<label>
							<?php _e( 'Starting Tab', 'ultimate-product-catalogue' ); ?>
						</label>

						<select name='ewd-upcp-starting-tab'>

							<option value='details' <?php echo ( $starting_tab == 'details' ? 'selected' : '' ); ?>><?php _e( 'Product Details', 'ultimate-product-catalogue' ); ?></option>
							<option value='additional_information' <?php echo ( $starting_tab == 'additional_information' ? 'selected' : '' ); ?>><?php _e( 'Additional Information', 'ultimate-product-catalogue' ); ?></option>

							<?php if ( ! empty( $ewd_upcp_controller->settings->get_setting( 'product-inquiry-form' ) ) ) { ?>

								<option value='contact' <?php echo ( $starting_tab == 'contact' ? 'selected' : '' ); ?>><?php _e( 'Contact Us', 'ultimate-product-catalogue' ); ?></option>

							<?php } ?>

							<?php if ( ! empty( $ewd_upcp_controller->settings->get_setting( 'product-reviews' ) ) ) { ?>

								<option value='reviews' <?php echo ( $starting_tab == 'reviews' ? 'selected' : '' ); ?>><?php _e( 'Customer Reviews', 'ultimate-product-catalogue' ); ?></option>			
		
							<?php } ?>

							<?php if ( ! empty( $ewd_upcp_controller->settings->get_setting( 'product-faqs' ) ) ) { ?>

								<option value='faqs' <?php echo ( $starting_tab == 'faqs' ? 'selected' : '' ); ?>><?php _e( 'Product FAQs', 'ultimate-product-catalogue' ); ?></option>
		
							<?php } ?>

							<?php foreach ( $custom_tabs as $custom_tab ) { ?>
								
								<option value='<?php echo esc_attr( sanitize_title( $custom_tab->name ) ); ?>' <?php echo ( $starting_tab == sanitize_title( $custom_tab->name ) ? 'selected' : '' ); ?>><?php echo esc_html( $custom_tab->name ); ?></option>
							
							<?php } ?>

						</select>

					</div>

					<div class='ewd-upcp-product-page-custom-tabs'>

						<h4>
							<?php _e( 'Additional Tabs', 'ultimate-product-catalogue' ); ?>
						</h4>

						<table>

							<thead>

								<tr>

									<th></th>
									<th><?php _e( 'Tab Name', 'ultimate-product-catalogue' ); ?></th>
									<th><?php _e( 'Content', 'ultimate-product-catalogue' ); ?></th>

								</tr>

							</thead>

							<tbody>

								<?php foreach ( $custom_tabs as $custom_tab ) { ?>

									<tr>

										<td class='ewd-upcp-delete-custom-tab'><?php _e( 'Delete', 'ultimate-product-catalogue' ); ?></td>
										<td><input type='text' name='ewd_upcp_custom_tab_name[]' value='<?php echo esc_attr( $custom_tab->name ); ?>' /></td>
										<td><textarea name='ewd_upcp_custom_tab_content[]'><?php echo esc_html( $custom_tab->content ); ?></textarea></td>

									</tr>

								<?php } ?>

								<tr class='ewd-upcp-additional-tab-template ewd-upcp-hidden'>

									<td class='ewd-upcp-delete-custom-tab'><?php _e( 'Delete', 'ultimate-product-catalogue' ); ?></td>
									<td><input type='text' name='ewd_upcp_custom_tab_name[]' /></td>
									<td><textarea name='ewd_upcp_custom_tab_content[]'></textarea></td>

								</tr>

								<tr class='ewd-upcp-additional-tab-add' colspan='3'>
									<td><?php _e( 'Add', 'ultimate-product-catalogue' ); ?></td>
								</tr>

							</tbody>

						</table>

					</div>

					<input type='submit' class='button button-primary' name='ewd-upcp-product-tabs-submit' value='<?php _e( 'Update Tabs', 'ultimate-product-catalogue' ); ?>' />

				</div>

				<?php 

				$elements = array(
					(object) array(
						'name'		=> __( 'Additional Images', 'ultimate-product-catalogue' ),
						'class'		=> 'additional_images',
						'id'		=> '',
						'x_size'	=> 2,
						'y_size'	=> 8,
					),
					(object) array(
						'name'		=> __( 'Back', 'ultimate-product-catalogue' ),
						'class'		=> 'back',
						'id'		=> '',
						'x_size'	=> 2,
						'y_size'	=> 1,
					),
					(object) array(
						'name'		=> __( 'Blank', 'ultimate-product-catalogue' ),
						'class'		=> 'blank',
						'id'		=> '',
						'x_size'	=> 1,
						'y_size'	=> 1,
					),
					(object) array(
						'name'		=> __( 'Category', 'ultimate-product-catalogue' ),
						'class'		=> 'category',
						'id'		=> '',
						'x_size'	=> 1,
						'y_size'	=> 1,
					),
					(object) array(
						'name'		=> __( 'Category Label', 'ultimate-product-catalogue' ),
						'class'		=> 'category_label',
						'id'		=> '',
						'x_size'	=> 1,
						'y_size'	=> 1,
					),
					(object) array(
						'name'		=> __( 'Description', 'ultimate-product-catalogue' ),
						'class'		=> 'description',
						'id'		=> '',
						'x_size'	=> 5,
						'y_size'	=> 4,
					),
					(object) array(
						'name'		=> __( 'Main Image', 'ultimate-product-catalogue' ),
						'class'		=> 'main_image',
						'id'		=> '',
						'x_size'	=> 4,
						'y_size'	=> 6,
					),
					(object) array(
						'name'		=> __( 'Next/Previous', 'ultimate-product-catalogue' ),
						'class'		=> 'next_previous',
						'id'		=> '',
						'x_size'	=> 2,
						'y_size'	=> 3,
					),
					(object) array(
						'name'		=> __( 'Price', 'ultimate-product-catalogue' ),
						'class'		=> 'price',
						'id'		=> '',
						'x_size'	=> 1,
						'y_size'	=> 1,
					),
					(object) array(
						'name'		=> __( 'Price Label', 'ultimate-product-catalogue' ),
						'class'		=> 'price_label',
						'id'		=> '',
						'x_size'	=> 1,
						'y_size'	=> 1,
					),
					(object) array(
						'name'		=> __( 'Product Link', 'ultimate-product-catalogue' ),
						'class'		=> 'product_link',
						'id'		=> '',
						'x_size'	=> 1,
						'y_size'	=> 1,
					),
					(object) array(
						'name'		=> __( 'Product Name', 'ultimate-product-catalogue' ),
						'class'		=> 'product_name',
						'id'		=> '',
						'x_size'	=> 3,
						'y_size'	=> 1,
					),
					(object) array(
						'name'		=> __( 'Related Products', 'ultimate-product-catalogue' ),
						'class'		=> 'related_products',
						'id'		=> '',
						'x_size'	=> 5,
						'y_size'	=> 3,
					),
					(object) array(
						'name'		=> __( 'Sub-Category', 'ultimate-product-catalogue' ),
						'class'		=> 'subcategory',
						'id'		=> '',
						'x_size'	=> 1,
						'y_size'	=> 1,
					),
					(object) array(
						'name'		=> __( 'Sub-Category Labels', 'ultimate-product-catalogue' ),
						'class'		=> 'subcategory_label',
						'id'		=> '',
						'x_size'	=> 1,
						'y_size'	=> 1,
					),
					(object) array(
						'name'		=> __( 'Tags', 'ultimate-product-catalogue' ),
						'class'		=> 'tags',
						'id'		=> '',
						'x_size'	=> 1,
						'y_size'	=> 1,
					),
					(object) array(
						'name'		=> __( 'Tags Label', 'ultimate-product-catalogue' ),
						'class'		=> 'tags_label',
						'id'		=> '',
						'x_size'	=> 1,
						'y_size'	=> 1,
					),
					(object) array(
						'name'		=> __( 'Text', 'ultimate-product-catalogue' ),
						'class'		=> 'text',
						'id'		=> '',
						'x_size'	=> 2,
						'y_size'	=> 2,
					),
				);

				$custom_fields = $ewd_upcp_controller->settings->get_custom_fields();

				foreach ( $custom_fields as $custom_field ) { 

					$elements[] = (object) array(
						'name'		=> $custom_field->name,
						'class'		=> 'custom_field',
						'id'		=> $custom_field->id,
						'x_size'	=> 1,
						'y_size'	=> 1,
					);

					$elements[] = (object) array(
						'name'		=> $custom_field->name . __( ' Label', 'ultimate-product-catalogue' ),
						'class'		=> 'custom_label',
						'id'		=> $custom_field->id,
						'x_size'	=> 1,
						'y_size'	=> 1,
					);
				}

				$product_page_serialized = get_option( 'UPCP_Product_Page_Serialized' );

				$gridster = strpos( $product_page_serialized, 'class=\\\\') !== false ? json_decode( stripslashes( $product_page_serialized ) ) : json_decode( $product_page_serialized );

				$gridster = is_array( $gridster ) ? $gridster : array();

				usort( $gridster, array( $this, 'sort_gridster' ) );

				$max_column = 0;
				$max_row = 0;

				foreach ( $gridster as $grid_element ) { 

					$max_column = max( $max_column, $grid_element->col );
					$max_row = max( $max_row, $grid_element->row );
				}

				?>

				<div class='ewd-upcp-product-page-type ewd-upcp-hidden' data-page='custom_large'>

					<div class='ewd-upcp-custom-product-page-notice'>
						<?php _e( 'Some users have reported problems using the admin area functions of this feature with FireFox and IE browsers. No issues reported yet with Chrome, or with any browser on the visitor\'s side.', 'ultimate-product-catalogue' ); ?>
					</div>

					<div class='ewd-upcp-custom-product-page-large-element-selector'>

						<div class='ewd-upcp-custom-product-page-heading'><?php _e( 'Elements', 'ultimate-product-catalogue' ); ?></div>
						
						<ul>

							<?php foreach ( $elements as $element ) { ?>

								<li>

									<a class='ewd-upcp-custom-product-page-add-element' data-name='<?php echo esc_attr( $element->name ); ?>' data-class='<?php echo esc_attr( $element->class ); ?>' data-id='<?php echo esc_attr( $element->id ); ?>' data-x_size='<?php echo esc_attr( $element->x_size ); ?>' data-y_size='<?php echo esc_attr( $element->y_size ); ?>'><?php echo esc_html( $element->name ); ?></a>

								</li>

							<?php } ?>

						</ul>

					</div>

					<div class='ewd-upcp-product-page-custom-large-screen'>

						<div class='wrapper gridster gridster-large'>

							<ul>

								<?php foreach ( $gridster as $grid_element ) { ?>

									<li data-col='<?php echo esc_attr( $grid_element->col ); ?>' data-row='<?php echo esc_attr( $grid_element->row ); ?>' data-sizex='<?php echo esc_attr( $grid_element->size_x ); ?>' data-sizey='<?php echo esc_attr( $grid_element->size_y); ?>'  data-elementclass='<?php echo esc_attr( $grid_element->element_class ); ?>' data-elementid='<?php echo esc_attr( $grid_element->element_id ); ?>' class='prod-page-div gs-w' style='display: list-item; position:absolute;'>

										<?php echo substr( $grid_element->element_type, 0, strpos( $grid_element->element_type, '<' ) ); ?>

										<div class='gs-delete-handle'></div>

										<?php if ( $grid_element->element_class == 'text' ) { ?>

											<textarea class='ewd-upcp-pb-textarea'>
												<?php echo esc_textarea( $grid_element->element_id ); ?>
											</textarea>

										<?php } ?>

									</li>

								<?php } ?>

							</ul>
							
						</div>

						<div class='ewd-upcp-custom-product-page-restore-default'>

							<?php 

								$args = array(
									'type'		=> 'large',
									'action'	=> 'ewd_upcp_restore_default_product_page',
									'page'		=> 'ewd-upcp-product-page',
								);

								?>

							<a href='<?php echo esc_attr( add_query_arg( $args , admin_url() . 'admin.php' ) ); ?>' class='button'>
								<?php _e( 'Restore Default Layout', 'ultimate-product-catalogue' ); ?>
							</a>

						</div>

						<button class='gridster-large-save button button-primary'><?php _e( 'Save Layout', 'ultimate-product-catalogue' ); ?></button>

					</div>

				</div>

				<?php

				$product_page_serialized = get_option( 'UPCP_Product_Page_Serialized_Mobile' );

				$gridster = strpos( $product_page_serialized, 'class=\\\\' ) !== false ? json_decode( stripslashes( $product_page_serialized ) ) : json_decode( $product_page_serialized );

				$gridster = is_array( $gridster ) ? $gridster : array();

				usort( $gridster, array( $this, 'sort_gridster' ) );

				$max_column = 0;
				$max_row = 0;

				foreach ( $gridster as $grid_element ) { 

					$max_column = max( $max_column, $grid_element->col );
					$max_row = max( $max_row, $grid_element->row );
				}

				?>

				<div class='ewd-upcp-product-page-type ewd-upcp-hidden' data-page='custom_mobile'>

					<div class='ewd-upcp-custom-product-page-notice'>
						<?php _e( 'Some users have reported problems using the admin area functions of this feature with FireFox and IE browsers. No issues reported yet with Chrome, or with any browser on the visitor\'s side.', 'ultimate-product-catalogue' ); ?>
					</div>

					<div class='ewd-upcp-custom-product-page-mobile-element-selector'>

						<div class='ewd-upcp-custom-product-page-heading'><?php _e( 'Elements', 'ultimate-product-catalogue' ); ?></div>

						<ul>

							<?php foreach ( $elements as $element ) { ?>

								<li>

									<a class='ewd-upcp-custom-product-page-add-element' data-name='<?php echo esc_attr( $element->name ); ?>' data-class='<?php echo esc_attr( $element->class ); ?>' data-x_size='<?php echo esc_attr( $element->x_size ); ?>' data-y_size='<?php echo esc_attr( $element->y_size ); ?>'><?php echo esc_html( $element->name ); ?></a>

								</li>

							<?php } ?>

						</ul>

					</div>

					<div class='ewd-upcp-product-page-custom-mobile-screen'>

						<div class='wrapper gridster gridster-mobile'>

							<ul>

								<?php foreach ( $gridster as $grid_element ) { ?>

									<li data-col='<?php echo esc_attr( $grid_element->col ); ?>' data-row='<?php echo esc_attr( $grid_element->row ); ?>' data-sizex='<?php echo esc_attr( $grid_element->size_x ); ?>' data-sizey='<?php echo esc_attr( $grid_element->size_y ); ?>'  data-elementclass='<?php echo esc_attr( $grid_element->element_class ); ?>' data-elementid='<?php echo esc_attr( $grid_element->element_id ); ?>' class='prod-page-div gs-w' style='display: list-item; position:absolute;'>

										<?php echo substr( $grid_element->element_type, 0, strpos( $grid_element->element_type, '<' ) ); ?>

										<div class='gs-delete-handle'></div>

										<?php if ( $grid_element->element_class == 'text' ) { ?>

											<textarea class='ewd-upcp-pb-textarea'>
												<?php echo esc_textarea( $grid_element->element_id ); ?>
											</textarea>

										<?php } ?>

									</li>

								<?php } ?>

							</ul>

						</div>

						<div class='ewd-upcp-custom-product-page-restore-default'>

							<?php 

								$args = array(
									'type'		=> 'mobile',
									'action'	=> 'ewd_upcp_restore_default_product_page',
									'page'		=> 'ewd-upcp-product-page',
								);

								?>

							<a href='<?php echo esc_attr( add_query_arg( $args , admin_url() . 'admin.php' ) ); ?>' class='button'>
								<?php _e( 'Restore Default Layout', 'ultimate-product-catalogue' ); ?>
							</a>

						</div>

						<button class='gridster-mobile-save button button-primary'><?php _e( 'Save Layout', 'ultimate-product-catalogue' ); ?></button>

					</div>

				</div>

			</form>

		</div>

		<?php
	}

	/**
	 * Sort the Gridster elements into the correct order 
	 * @since 5.0.0
	 */
	public function sort_gridster( $a, $b ) {

		if ( $a->row != $b->row ) {

			return $a->row - $b->row;
		}
		
		return $a->col - $b->col;
	}

	/**
	 * Save the custom tabs and starting tab information when the form is submitted
	 * @since 5.0.0
	 */
	public function save_product_tabs() {

		update_option( 'ewd-upcp-product-page-starting-tab', sanitize_text_field( $_POST['ewd-upcp-starting-tab'] ) );

		$custom_tab_names = is_array( $_POST['ewd_upcp_custom_tab_name'] ) ? array_map( 'sanitize_text_field', $_POST['ewd_upcp_custom_tab_name'] ) : array();

		$custom_tabs = array();

		foreach ( $custom_tab_names as $key => $custom_tab_name ) {

			if ( empty( $custom_tab_name ) ) { continue; }

			$custom_tabs[] = (object) array(
				'name'		=> $custom_tab_name,
				'content'	=> sanitize_textarea_field( $_POST['ewd_upcp_custom_tab_content'][ $key ] ),
			);
		}

		update_option( 'ewd-upcp-product-page-tabs', $custom_tabs );
	}

	public function save_gridster_layout() {
		global $ewd_upcp_controller;

		// Authenticate request
		if ( 
			! check_ajax_referer( 'ewd-upcp-admin-js', 'nonce' )
			||
			! current_user_can( $ewd_upcp_controller->settings->get_setting( 'access-role' ) )
		) {
			ewdupcpHelper::admin_nopriv_ajax();
		}

		$option_name = $_POST['type'] == 'mobile' ? 'UPCP_Product_Page_Serialized_Mobile' : 'UPCP_Product_Page_Serialized';

		update_option( $option_name, sanitize_text_field( stripslashes_deep( $_POST['serialized_product_page'] ) ) );
	}

	public function restore_product_page() {

		$option_name = $_GET['type'] == 'mobile' ? 'UPCP_Product_Page_Serialized_Mobile' : 'UPCP_Product_Page_Serialized';

		$custom_product_page = '[{"element_type":"Product Description<div class=\"gs-delete-handle\" onclick=\"remove_element(this);\"></div><span class=\"gs-resize-handle gs-resize-handle-both\"></span>","element_class":"description","element_id":"","col":3,"row":9,"size_x":5,"size_y":4},{"element_type":"Back Link<div class=\"gs-delete-handle\" onclick=\"remove_element(this);\"></div><span class=\"gs-resize-handle gs-resize-handle-both\"></span>","element_class":"back","element_id":"","col":1,"row":1,"size_x":2,"size_y":1},{"element_type":"Additional Images<div class=\"gs-delete-handle\" onclick=\"remove_element(this);\"></div><span class=\"gs-resize-handle gs-resize-handle-both\"></span>","element_class":"additional_images","element_id":"","col":1,"row":2,"size_x":2,"size_y":9},{"element_type":"Main Image<div class=\"gs-delete-handle\" onclick=\"remove_element(this);\"></div><span class=\"gs-resize-handle gs-resize-handle-both\"></span>","element_class":"main_image","element_id":"","col":3,"row":3,"size_x":4,"size_y":6},{"element_type":"Permalink<div class=\"gs-delete-handle\" onclick=\"remove_element(this);\"></div><span class=\"gs-resize-handle gs-resize-handle-both\"></span>","element_class":"product_link","element_id":"","col":6,"row":2,"size_x":1,"size_y":1},{"element_type":"Product Name<div class=\"gs-delete-handle\" onclick=\"remove_element(this);\"></div><span class=\"gs-resize-handle gs-resize-handle-both\"></span>","element_class":"product_name","element_id":"","col":3,"row":2,"size_x":3,"size_y":1},{"element_type":"Blank<div class=\"gs-delete-handle\" onclick=\"remove_element(this);\"></div><span class=\"gs-resize-handle gs-resize-handle-both\"></span>","element_class":"blank","element_id":"","col":7,"row":2,"size_x":1,"size_y":7},{"element_type":"Blank<div class=\"gs-delete-handle\" onclick=\"remove_element(this);\"></div><span class=\"gs-resize-handle gs-resize-handle-both\"></span>","element_class":"blank","element_id":"","col":3,"row":1,"size_x":5,"size_y":1}]';

		update_option( $option_name, $custom_product_page );
	}

	public function enqueue_scripts() {

		$screen = get_current_screen();

		if ( $screen->id == 'upcp_product_page_ewd-upcp-product-page' ) {

			wp_enqueue_style( 'ewd-upcp-gridster', EWD_UPCP_PLUGIN_URL . '/assets/css/jquery.gridster.css', array(), EWD_UPCP_VERSION );
			wp_enqueue_style( 'ewd-upcp-admin-css', EWD_UPCP_PLUGIN_URL . '/assets/css/ewd-upcp-admin.css', array(), EWD_UPCP_VERSION );

			wp_enqueue_script( 'ewd-upcp-gridster', EWD_UPCP_PLUGIN_URL . '/assets/js/jquery.gridster.js', array( 'jquery' ), EWD_UPCP_VERSION, true );
			wp_enqueue_script( 'ewd-upcp-admin-js', EWD_UPCP_PLUGIN_URL . '/assets/js/ewd-upcp-admin.js', array( 'jquery', 'jquery-ui-sortable', 'ewd-upcp-gridster' ), EWD_UPCP_VERSION, true );
		}
	}
}
} // endif;
