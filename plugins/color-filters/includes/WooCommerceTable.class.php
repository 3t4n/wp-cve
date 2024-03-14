<?php
/**
 * Class to run WooCommerce in table mode, if enabled
 */

if ( !defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'ewduwcfWooCommerceTable' ) ) {
class ewduwcfWooCommerceTable {

	public function __construct() {

		add_action( 'admin_menu', array( $this, 'register_menu_screen' ) );

		add_filter(	'woocommerce_product_loop_start', array( $this, 'table_product_loop_start' ) );
		add_filter(	'woocommerce_product_loop_end', array( $this, 'table_product_loop_end' ) );
		add_filter(	'wc_get_template_part', array( $this, 'table_replace_product_content_template' ), 10, 3 );
	}

	/**
	 * Adds filtering form, controls and column titles
	 * @since 3.0.0
	 */
	public function register_menu_screen() {
		global $ewd_uwcf_controller;

		if ( ! $ewd_uwcf_controller->settings->get_setting( 'table-format' ) ) { return; }
		
		add_submenu_page(
			'ewd-uwcf-dashboard',
			esc_html__( 'WooCommerce Table Mode Settings', 'color-filters' ),
			esc_html__( 'WC Table Settings', 'color-filters' ),
			$ewd_uwcf_controller->settings->get_setting( 'access-role' ),
			'ewd-uwcf-table-mode',
			array( $this, 'display_admin_screen' )
		);
	}

	/**
	 * Adds filtering form, controls and column titles
	 * @since 3.0.0
	 */
	public function table_product_loop_start( $content ) {
		global $ewd_uwcf_controller;

		if ( ! $ewd_uwcf_controller->settings->get_setting( 'table-format' ) ) { return $content; }

		ewd_uwcf_load_view_files();

		ob_start();

		$view = new ewduwcfView( array() );

		$template = $view->find_template( 'table-mode-header' );
		if ( $template ) {
			include( $template );
		}

		$content = ob_get_clean();

		return $content;
	}

	/**
	 * Closes out the table mode HTML tags
	 * @since 3.0.0
	 */
	public function table_product_loop_end( $content ) {
		global $ewd_uwcf_controller;

		if ( ! $ewd_uwcf_controller->settings->get_setting( 'table-format' ) ) { return $content; }

		ewd_uwcf_load_view_files();

		ob_start();

		$view = new ewduwcfView( array() );

		$template = $view->find_template( 'table-mode-footer' );
		if ( $template ) {
			include( $template );
		}

		$content = ob_get_clean();

		return $content;
	}

	/**
	 * Replace the product content using the table mode template
	 * @since 3.0.0
	 */
	public function table_replace_product_content_template( $template, $slug, $name ) {
		global $ewd_uwcf_controller;

		if ( ! $ewd_uwcf_controller->settings->get_setting( 'table-format' ) ) { return $template; }

		if ( $slug == 'content' and $name == 'product' ) {

			$template = EWD_UWCF_PLUGIN_DIR . '/' . EWD_UWCF_TEMPLATE_DIR . '/table-mode-content.php';
		}

		return $template;
	}

	/**
	 * Adds in the filtering fields for the appropriate fields
	 * @since 3.0.0
	 */
	public function print_table_filering_headers() {
		global $ewd_uwcf_controller;

		ewd_uwcf_load_view_files();

		$fields = $ewd_uwcf_controller->settings->get_fields();

		$filtering = new ewduwcfViewFiltering( array() );

		foreach ( $fields as $field ) {

			if ( ! $this->field_displayed( $field ) ) { continue; }

			echo '<th class="ewd-uwcf-wc-table-filter">';

			if ( $field == 'name' ) { $filtering->maybe_print_text_search(); }
			elseif ( $field == 'price' ) { $filtering->maybe_print_price_filtering(); }
			elseif ( $field == 'rating' ) { $filtering->maybe_print_ratings_filtering(); }
			elseif ( $field == 'product_color' ) { $filtering->maybe_print_color_filtering(); }
			elseif ( $field == 'product_size' ) { $filtering->maybe_print_size_filtering(); }
			elseif ( $field == 'product_cat' ) { $filtering->maybe_print_category_filtering(); }
			elseif ( $field == 'product_tag' ) { $filtering->maybe_print_tag_filtering(); }
			else {

				foreach ( ewd_uwcf_get_woocommerce_taxonomies() as $attribute_taxonomy ) {

					if ( $attribute_taxonomy->attribute_name != $field ) { continue; }

					$filtering->maybe_print_filtering_for_attribute( $attribute_taxonomy );
				}
			}

			echo '</th>';
		}
	}

	/**
	 * Adds in the field titles for the appropriate fields
	 * @since 3.0.0
	 */
	public function print_table_column_titles() {
		global $ewd_uwcf_controller;

		$fields = $ewd_uwcf_controller->settings->get_fields();

		foreach ( $fields as $field ) {

			if ( ! $this->field_displayed( $field ) ) { continue; }

			echo '<td>';

			if ( $field == 'name' ) { echo '<a class="' . ( $ewd_uwcf_controller->settings->get_setting( 'allow-sorting' ) ? 'uwcf-table-format-header': '' ) . '" data-orderby="title">' . __( 'Product', 'color-filters' ) . '</a>'; }
			elseif ( $field == 'image' ) { _e( 'Image', 'color-filters' ); }
			elseif ( $field == 'price' ) { echo '<a class="' . ( $ewd_uwcf_controller->settings->get_setting( 'allow-sorting' ) ? 'uwcf-table-format-header': '' ) . '" data-orderby="price">' . __( 'Price', 'color-filters' ) . '</a>'; }
			elseif ( $field == 'rating' ) { echo '<a class="' . ( $ewd_uwcf_controller->settings->get_setting( 'allow-sorting' ) ? 'uwcf-table-format-header': '' ) . '" data-orderby="rating">' . __( 'Rating', 'color-filters' ) . '</a>'; }
			elseif ( $field == 'add_to_cart' ) { _e( 'Add to Cart', 'color-filters' ); }
			elseif ( $field == 'product_color' ) { _e( 'Colors', 'color-filters' ); }
			elseif ( $field == 'product_size' ) { _e( 'Sizes', 'color-filters' ); }
			elseif ( $field == 'product_cat' ) { _e( 'Categories', 'color-filters' ); }
			elseif ( $field == 'product_tag' ) { _e( 'Tags', 'color-filters' ); }
			else {

				foreach ( ewd_uwcf_get_woocommerce_taxonomies() as $attribute_taxonomy ) {

					if ( $attribute_taxonomy->attribute_name != $field ) { continue; }

					echo wp_kses_post( $attribute_taxonomy->attribute_label );
				}
			}

			echo '</td>';
		}
	}

	/**
	 * Adds in the field content for the appropriate field and product
	 * @since 3.0.0
	 */
	public function print_product_content() {
		global $ewd_uwcf_controller;

		ewd_uwcf_load_view_files();

		$fields = $ewd_uwcf_controller->settings->get_fields();

		$this->filtering = new ewduwcfViewProductFilters( array() );

		foreach ( $fields as $field ) {

			if ( ! $this->field_displayed( $field ) ) { continue; }

			echo '<td>';

			if ($field == 'name') { 

				echo '<a href=\'' . esc_url( get_permalink() ) . '\'>'; 
				woocommerce_template_loop_product_title();
				echo '</a>';
			}
			elseif ($field == 'image') { woocommerce_template_loop_product_thumbnail(); }
			elseif ($field == 'price') { woocommerce_template_loop_price(); }
			elseif ($field == 'rating') { woocommerce_template_loop_rating(); }
			elseif ($field == 'add_to_cart') { woocommerce_template_loop_add_to_cart(); }
			elseif ($field == 'colors') { $this->print_product_color_field(); }
			elseif ($field == 'sizes') { $this->print_product_size_field(); }
			else { $this->print_product_attribute_field( $field ); }

			echo '</td>';

		}
	}

	/**
	 * Adds in the color field content
	 * @since 3.0.0
	 */
	public function print_product_color_field() {

		ob_start();

		$template = $this->filtering->find_template( 'table-mode-product-colors' );
		if ( $template ) {
			include( $template );
		}

		$content = ob_get_clean();

		echo $content;
	}

	/**
	 * Adds in the size field content
	 * @since 3.0.0
	 */
	public function print_product_size_field() {

		ob_start();

		$template = $this->filtering->find_template( 'table-mode-product-sizes' );
		if ( $template ) {
			include( $template );
		}

		$content = ob_get_clean();

		echo $content;
	}

	/**
	 * Adds in the content for the different attribute fields
	 * @since 3.0.0
	 */
	public function print_product_attribute_field( $field ) {

		$this->filtering->current_attribute = ewd_uwcf_get_attribute( $field );

		ob_start();

		$template = $this->filtering->find_template( 'table-mode-product-attributes' );
		if ( $template ) {
			include( $template );
		}

		$content = ob_get_clean();

		echo $content;
	}

	/**
	 * Returns whether or not the field should be displayed
	 * @since 3.0.0
	 */
	public function field_displayed( $field ) {
		global $ewd_uwcf_controller;
		
		return $ewd_uwcf_controller->settings->get_setting( 'wc-table-product-' . $field . '-displayed' );
	}

	/**
	 * Diplays the admin screen where it's possible to edit the WooCommerce Table fields
	 * @since 3.0.0
	 */
	public function display_admin_screen() { 
		global $ewd_uwcf_controller;

		if ( isset( $_POST['wc_table_mode_submit'] ) ) { $this->save_table_mode_settings(); }

		?>

		<h2>WooCommerce Table Format Settings</h2>

		<div id='ewd-uwcf-wc-table-container'>

			<div id='ewd-uwcf-wc-table-explanation'>
				<?php _e( 'Use the table below to select which fields are displayed and filterable when table mode is enabled', 'color-filters' ); ?>
			</div>
			
			<form method='post'>
				<table class='ewd-uwcf-wc-table-format form-table'>
					<thead>
						<tr>
							<th><?php _e( 'Field Name', 'color-filters' ); ?></th>
							<th><?php _e( 'Display', 'color-filters' ); ?></th>
							<th><?php _e( 'Enable Filtering', 'color-filters' ); ?></th>
							<th><?php _e( 'Filter Type', 'color-filters' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $ewd_uwcf_controller->settings->get_fields() as $field ) { ?>

							<?php if ( $field == 'ewd_uwcf_colors' or $field == 'ewd_uwcf_sizes' ) { continue; } ?>
							
							<tr data-field="<?php echo esc_attr( $field ); ?>">
								<td><?php echo $this->get_field_name( $field ); ?></td>
								<td>
									<div class="sap-admin-hide-radios">
										<input type='checkbox' name='<?php echo esc_attr( $field ); ?>_displayed' value='1' <?php echo ( $this->field_displayed( $field ) ? 'checked' : '' ); ?> >
									</div>
									<label class="sap-admin-switch">
										<input type="checkbox" class="sap-admin-option-toggle" data-inputname="<?php echo esc_attr( $field ); ?>_displayed" <?php echo ( $this->field_displayed( $field ) ? 'checked' : '' ); ?>>
										<span class="sap-admin-switch-slider round"></span>
									</label>
								</td>
								<td>
									<?php if ( $this->filtering_possible( $field ) ) { ?>
										<div class="sap-admin-hide-radios">
											<input type='checkbox' name='<?php echo esc_attr( $field ); ?>_enable_filtering' value='1' <?php echo ( $this->field_filtering_enabled( $field ) ? 'checked' : '' ); ?> >
										</div>
										<label class="sap-admin-switch">
											<input type="checkbox" class="sap-admin-option-toggle" data-inputname="<?php echo esc_attr( $field ); ?>_enable_filtering" <?php echo ( $this->field_filtering_enabled( $field ) ? 'checked' : '' ); ?>>
											<span class="sap-admin-switch-slider round"></span>
										</label>
									<?php } ?>
								</td>
								<td>
									<?php if ( $this->filtering_possible( $field ) ) { ?>
										<select name='<?php echo esc_attr( $field ); ?>_filter_type' class='ewd-uwcf-wc-format-filter-type'>
											<?php foreach ( $this->field_filtering_options( $field ) as $filtering_option ) { ?>
												<option value='<?php echo esc_attr( $filtering_option ); ?>' <?php echo ( $this->field_filtering_type( $field ) == $filtering_option ? 'selected' : ''); ?>><?php echo esc_html( $filtering_option ) ?></option>
											<?php } ?>
										</select>
									<?php } ?>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>

				<input type='submit' class='button button-primary' name='wc_table_mode_submit' value='Update' />
			</form>

		</div>

	<?php }

	public function get_field_name( $field ) { 

		if ( $field == 'name' ) 			{ return __( 'Title', 'color-filters' ); }
		elseif ( $field == 'image' ) 		{ return __( 'Image', 'color-filters' ); }
		elseif ( $field == 'price' ) 		{ return __( 'Price', 'color-filters' ); }
		elseif ( $field == 'rating' ) 		{ return __( 'Rating', 'color-filters' ); }
		elseif ( $field == 'add_to_cart' ) 	{ return __( 'Add to Cart', 'color-filters' ); }
		elseif ( $field == 'colors' ) 		{ return __( 'Colors', 'color-filters' ); }
		elseif ( $field == 'sizes' ) 		{ return __( 'Sizes', 'color-filters' ); }
		else {
			
			foreach (ewd_uwcf_get_woocommerce_taxonomies() as $attribute_taxonomy ) {

				if ( $attribute_taxonomy->attribute_name == $field ) {
					return esc_html( $attribute_taxonomy->attribute_label );
				}
			}
		}
	}

	public function filtering_possible( $field ) {

		if ( $field == 'image' or $field == 'add_to_cart' ) { return false; }

		return true;
	}

	public function field_filtering_enabled( $field ) {
		global $ewd_uwcf_controller;

		if ( $field == 'name' ) 			{ return $ewd_uwcf_controller->settings->get_setting( 'text-search' ); }
		elseif ( $field == 'price' ) 		{ return $ewd_uwcf_controller->settings->get_setting( 'price-filtering' ); }
		elseif ( $field == 'rating' ) 		{ return $ewd_uwcf_controller->settings->get_setting( 'ratings-filtering' ); }
		elseif ( $field == 'colors' ) 		{ return $ewd_uwcf_controller->settings->get_setting( 'color-filtering' ); }
		elseif ( $field == 'sizes' ) 		{ return $ewd_uwcf_controller->settings->get_setting( 'size-filtering' ); }
		else {
			
			foreach (ewd_uwcf_get_woocommerce_taxonomies() as $attribute_taxonomy ) {

				if ( $attribute_taxonomy->attribute_name == $field ) { return $ewd_uwcf_controller->settings->get_setting( $attribute_taxonomy->attribute_name . '-filtering' ); }
			}
		}
	}

	public function field_filtering_options( $field ) {
	
		if ( $field == 'name' ) {

			$options = array( 
				'text' 
			);
		}
		elseif ( $field == 'price' ) {

			$options = array( 
				'text', 
				'slider' 
			);
		}
		elseif ( $field == 'colors' ) {

			$options = array( 
				'list', 
				'tiles', 
				'swatch', 
				'checklist', 
				'dropdown' 
			);
		}
		else {

			$options = array( 
				'list', 
				'checklist', 
				'dropdown' 
			);
		}
	
		return $options;
	}

	public function field_filtering_type( $field ) {
		global $ewd_uwcf_controller;

		if ( $field == 'name' ) 			{ return $ewd_uwcf_controller->settings->get_setting( 'text-search-display' ); }
		elseif ( $field == 'price' ) 		{ return $ewd_uwcf_controller->settings->get_setting( 'price-filtering-display' ); }
		elseif ( $field == 'rating' ) 		{ return $ewd_uwcf_controller->settings->get_setting( 'ratings-filtering-display' ); }
		elseif ( $field == 'colors' ) 		{ return $ewd_uwcf_controller->settings->get_setting( 'color-filtering-display' ); }
		elseif ( $field == 'sizes' ) 		{ return $ewd_uwcf_controller->settings->get_setting( 'size-filtering-display' ); }
		else {
			
			foreach (ewd_uwcf_get_woocommerce_taxonomies() as $attribute_taxonomy ) {

				if ( $attribute_taxonomy->attribute_name == $field ) { return $ewd_uwcf_controller->settings->get_setting( $attribute_taxonomy->attribute_name . '-display' ); }
			}
		}
	}

	public function save_table_mode_settings() {
		global $ewd_uwcf_controller;

		foreach ( $ewd_uwcf_controller->settings->get_fields() as $field ) {

			$ewd_uwcf_controller->settings->set_setting( 'wc-table-product-' . $field . '-displayed', isset( $_POST[ $field . '_displayed' ] ) ? true : false );
			
			
			if ( $field == 'name' ) { $ewd_uwcf_controller->settings->set_setting( 'text-search', isset( $_POST[ $field . '_enable_filtering' ] ) ? true : false ); }
			elseif ( $field == 'price' ) { $ewd_uwcf_controller->settings->set_setting( 'price-filtering', isset( $_POST[ $field . '_enable_filtering' ] ) ? true : false ); }
			elseif ( $field == 'rating' ) { $ewd_uwcf_controller->settings->set_setting( 'ratings-filtering', isset( $_POST[ $field . '_enable_filtering' ] ) ? true : false ); }
			elseif ( $field == 'colors' ) { $ewd_uwcf_controller->settings->set_setting( 'color-filtering', isset( $_POST[ $field . '_enable_filtering' ] ) ? true : false ); }
			elseif ( $field == 'sizes' ) { $ewd_uwcf_controller->settings->set_setting( 'size-filtering', isset( $_POST[ $field . '_enable_filtering' ] ) ? true : false ); }
			else { $ewd_uwcf_controller->settings->set_setting( $field . '-filtering', isset( $_POST[ $field . '_enable_filtering' ] ) ? true : false ); }

			if ( isset( $_POST[ $field . '_filter_type' ] ) ) {

				if ( $field == 'name' ) { $ewd_uwcf_controller->settings->set_setting( 'text-search-display', sanitize_text_field( $_POST[ $field . '_filter_type' ] ) ); }
				elseif ( $field == 'price' ) { $ewd_uwcf_controller->settings->set_setting( 'price-filtering-display', sanitize_text_field( $_POST[ $field . '_filter_type' ] ) ); }
				elseif ( $field == 'rating' ) { $ewd_uwcf_controller->settings->set_setting( 'ratings-filtering-display', sanitize_text_field( $_POST[ $field . '_filter_type' ] ) ); }
				elseif ( $field == 'colors' ) { $ewd_uwcf_controller->settings->set_setting( 'color-filtering-display', sanitize_text_field( $_POST[ $field . '_filter_type' ] ) ); }
				elseif ( $field == 'sizes' ) { $ewd_uwcf_controller->settings->set_setting( 'size-filtering-display', sanitize_text_field( $_POST[ $field . '_filter_type' ] ) ); }
				else { $ewd_uwcf_controller->settings->set_setting( $field . '-display', sanitize_text_field( $_POST[ $field . '_filter_type' ] ) ); }
			}
		}

		$ewd_uwcf_controller->settings->save_settings();
	}

}
} // endif;