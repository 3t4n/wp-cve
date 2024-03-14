<?php
/**
 * Class to handle all custom post type definitions for Ultimate WooCommerce Filters
 */

if ( !defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'ewduwcfCustomPostTypes' ) ) {
class ewduwcfCustomPostTypes {

	// flag for whether color filtering is enabled
	public $colors_enabled;

	// flag for whether size filtering is enabled
	public $sizes_enabled;

	public function __construct() {

		// Call when plugin is initialized on every page load
		add_action( 'init', array( $this, 'load_cpts' ) );
		add_action( 'admin_menu', array( $this, 'move_taxonomy_menu' ) );
		add_filter( 'parent_file', array( $this, 'highlight_taxonomy_parent_menu' ) );

		// Handle saving the color, synching with WC, etc.
		add_action( EWD_UWCF_PRODUCT_COLOR_TAXONOMY . '_add_form_fields',	array( $this, 'add_color_field' ) );
		add_action( EWD_UWCF_PRODUCT_COLOR_TAXONOMY . '_edit_form_fields',	array( $this, 'edit_color_field' ) );
		add_action( 'create_' . EWD_UWCF_PRODUCT_COLOR_TAXONOMY, 			array( $this, 'update_color_data' ) );
		add_action( 'edit_' . EWD_UWCF_PRODUCT_COLOR_TAXONOMY, 				array( $this, 'update_color_data' ) );
		add_action( 'create_' . EWD_UWCF_PRODUCT_SIZE_TAXONOMY, 			array( $this, 'update_size_data' ) );
		add_action( 'edit_' . EWD_UWCF_PRODUCT_SIZE_TAXONOMY, 				array( $this, 'update_size_data' ) );
		add_action( 'pre_delete_term',										array( $this, 'delete_color_field' ), 10, 2 );
		add_action( 'pre_delete_term',										array( $this, 'delete_size_field' ), 10, 2 );

		// Add columns and filters to the admin list of products
		add_filter( 'manage_edit-' . EWD_UWCF_PRODUCT_COLOR_TAXONOMY . '_columns',	array( $this, 'register_color_column' ) );
		add_filter( 'manage_' . EWD_UWCF_PRODUCT_COLOR_TAXONOMY . '_custom_column', array( $this, 'display_color_column_content' ), 10, 3 );

		// Sort the color and size tables by custom ordering 
		add_action( 'pre_get_posts', array( $this, 'sort_by_order' ) );

		// Save the custom ordering of the color and size tables
		add_action( 'wp_ajax_ewd_uwcf_update_color_order', array( $this, 'update_color_order' ) );
		add_action( 'wp_ajax_ewd_uwcf_update_size_order', array( $this, 'update_size_order' ) );
	}

	/**
	 * Initialize custom post types
	 * @since 3.0.0
	 */
	public function load_cpts() {
		
		$args = array(
			'labels'            => array(
				'name'              => _x( 'Colors', 'Color', 'color-filters' ),
				'singular_name'     => _x( 'Color', 'Color', 'color-filters' ),
				'search_items'      => __( 'Search Colors', 'color-filters' ),
				'all_items'         => __( 'All Colors', 'color-filters' ),
				'parent_item'       => __( 'Parent Color', 'color-filters' ),
				'parent_item_colon' => __( 'Parent Color:', 'color-filters' ),
				'edit_item'         => __( 'Edit Color', 'color-filters' ),
				'update_item'       => __( 'Update Color', 'color-filters' ),
				'add_new_item'      => __( 'Add New Color', 'color-filters' ),
				'new_item_name'     => __( 'New Color Name', 'color-filters' ),
				'menu_name'         => __( 'Colors', 'color-filters' ),
			),
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'product-color' )
		);
	 
		if ( $this->colors_enabled ) {

			// Create filter so addons can modify the arguments
			$args = apply_filters( 'ewd_uwcf_color_taxonomy_args', $args );

			register_taxonomy( EWD_UWCF_PRODUCT_COLOR_TAXONOMY, EWD_UWCF_WOOCOMMERCE_POST_TYPE, $args );
		}


		$args = array(
			'labels'            => array(
				'name'              => _x( 'Sizes', 'Size', 'color-filters' ),
				'singular_name'     => _x( 'Size', 'Size', 'color-filters' ),
				'search_items'      => __( 'Search Sizes', 'color-filters' ),
				'all_items'         => __( 'All Sizes', 'color-filters' ),
				'parent_item'       => __( 'Parent Size', 'color-filters' ),
				'parent_item_colon' => __( 'Parent Size:', 'color-filters' ),
				'edit_item'         => __( 'Edit Size', 'color-filters' ),
				'update_item'       => __( 'Update Size', 'color-filters' ),
				'add_new_item'      => __( 'Add New Size', 'color-filters' ),
				'new_item_name'     => __( 'New Size Name', 'color-filters' ),
				'menu_name'         => __( 'Sizes', 'color-filters' ),
			),
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'product-size' )
		);
	 
		if ( $this->sizes_enabled ) {

			// Create filter so addons can modify the arguments
			$args = apply_filters( 'ewd_uwcf_size_taxonomy_args', $args );

			register_taxonomy( EWD_UWCF_PRODUCT_SIZE_TAXONOMY, EWD_UWCF_WOOCOMMERCE_POST_TYPE, $args );
		}
	}

	/**
	 * Move the color and size taxonomies to the UWCF menu
	 * @since 3.0.0
	 */
	public function move_taxonomy_menu() {
		global $ewd_uwcf_controller;

		if ( $this->colors_enabled ) {
			
			add_submenu_page( 
				'ewd-uwcf-dashboard', 
				esc_html__( 'Colors', 'color-filters' ), 
				esc_html__( 'Colors', 'color-filters' ), 
				$ewd_uwcf_controller->settings->get_setting( 'access-role' ), 
				'edit-tags.php?taxonomy=' . EWD_UWCF_PRODUCT_COLOR_TAXONOMY
			);
		}

		if ( $this->sizes_enabled ) {

			add_submenu_page( 
				'ewd-uwcf-dashboard', 
				esc_html__( 'Sizes', 'color-filters' ), 
				esc_html__( 'Sizes', 'color-filters' ), 
				$ewd_uwcf_controller->settings->get_setting( 'access-role' ), 
				'edit-tags.php?taxonomy=' . EWD_UWCF_PRODUCT_SIZE_TAXONOMY
			);
		}
	}

	/**
	 * Highlight taxonomy parent menu
	 * @since 3.0.0
	 */
	public function highlight_taxonomy_parent_menu( $parent_file ) {

		if ( get_current_screen()->taxonomy == EWD_UWCF_PRODUCT_COLOR_TAXONOMY or get_current_screen()->taxonomy == EWD_UWCF_PRODUCT_SIZE_TAXONOMY ) {

			$parent_file = 'ewd-uwcf-dashboard';
		}

		return $parent_file;
	}

	/**
	 * Adds in a field to select a color for each color term
	 * @since 3.0.0
	 */
	public function add_color_field( $post ) { 
		
		if ( ! $this->colors_enabled ) { return; }

		?>

		<div class="form-field term-color-wrap cf-color-filters">
			<label for="normal_fill_color_picker"><?php _e( 'Color', 'color-filters' ); ?></label>
			<div>
				<div id="normal_fill_color_picker" class="colorSelector small-text"><div></div></div>		
				<input class="cf-color sap-spectrum" name="normal_fill" id="normal_fill_color" type="text" size="40" />
			</div>
			<div class='ewd-uwcf-color-image-upload'>
				<label><?php _e("Or upload an image of the color pattern below:", 'color-filter'); ?></label>
				<input id="color_image" type="text" size="36" name="color_image" value="http://" />
				<input id="color_image_button" class="button" type="button" value="Upload Image" />
			</div>
		</div>

	<?php } 

	/**
	 * Adds in a field to select a color for each color term
	 * @since 3.0.0
	 */
	public function edit_color_field( $term ) { 
		
		if ( ! $this->colors_enabled ) { return; }

		$color = get_term_meta( $term->term_id, 'EWD_UWCF_Color', true );

		?>

		<tr class="form-field term-color-wrap cf-color-filters"> 
			<th scope="row"><?php _e( 'Color', 'color-filters' ); ?></th>
			<td>
				<p id="normal_fill_color_picker" class="colorSelector small-text"><p></p></p>		
				<input class="cf-color small-text" name="normal_fill" id="normal_fill_color" type="text" size="40" value="<?php echo ( strlen( $color ) <= 7 ? esc_attr( $color ) : '' ); ?>" /><br/>
				<label><?php _e("Or upload an image of the color pattern below:", 'color-filter'); ?></label>
				<input id="color_image" type="text" size="36" name="color_image" value="<?php echo ( strlen( $color ) > 7 ? esc_attr( $color ) : 'http://' ); ?>" />
				<input id="color_image_button" class="button" type="button" value="Upload Image" />
			</td>
		</tr>

	<?php } 

	/**
	 * Saves the selected color for each color term
	 * @since 3.0.0
	 */
	public function update_color_data( $term_id ) { 

		// Save the color associated with this term
		if ( ! isset( $_POST['color_image'] ) or $_POST['color_image'] == 'http://' ) {

			update_term_meta( $term_id, 'EWD_UWCF_Color', sanitize_text_field( $_POST['normal_fill'] ) );
		}
		else {

			update_term_meta( $term_id, 'EWD_UWCF_Color', sanitize_text_field( $_POST['color_image'] ) );
		}

		$term = get_term( $term_id, EWD_UWCF_PRODUCT_COLOR_TAXONOMY );

		$args = array(
			'name' => $term->name,
			'slug' => $term->slug,
			'parent' => $term->parent,
			'description' => $term->description
		);

		// Sync with the WooCommerce taxonomy term
		$wc_term_id = get_term_meta( $term_id, 'EWD_UWCF_WC_Term_ID', true ); 

		if ( ! is_wp_error( $wc_term_id ) and $wc_term_id ) {

			wp_update_term( $wc_term_id, 'pa_ewd_uwcf_colors', $args );
		}
		else {

			$wc_term = get_term_by( 'name', sanitize_text_field( $term->name ), 'pa_ewd_uwcf_colors' );
			if ( $wc_term ) {

				update_term_meta( $term_id, 'EWD_UWCF_WC_Term_ID', $wc_term->term_id );
			}
			else {

				$wc_term = wp_insert_term( $term->name, 'pa_ewd_uwcf_colors', $args );
				if ( ! is_wp_error( $wc_term ) and $wc_term ) { update_term_meta( $term_id, 'EWD_UWCF_WC_Term_ID', $wc_term['term_id'] ); }
			}
		} 

		// Set the the term's ordering location
		if ( get_term_meta( $term_id, 'EWD_UWCF_Term_Order', true ) ) {
			
			if ( $term->parent != -1 ) {
	
				update_term_meta( $term['term_id'], 'EWD_UWCF_Term_Order', get_term_meta( $args['parent'], 'EWD_UWCF_Term_Order', true ) );
			}
			else { 
	
				update_term_meta($term['term_id'], 'EWD_UWCF_Term_Order', 999);
			}
		}

	}

	public function delete_color_field( $term_id, $taxonomy ) {

		if ( $taxonomy != EWD_UWCF_PRODUCT_COLOR_TAXONOMY ) { return; }

		$wc_term_id = get_term_meta( $term_id, 'EWD_UWCF_WC_Term_ID', true );

		if ( $wc_term_id ) { wp_delete_term( $wc_term_id, 'pa_ewd_uwcf_colors' );}
	}

	/**
	 * Saves the selected color for each color term
	 * @since 3.0.0
	 */
	public function update_size_data( $term_id ) { 

		$term = get_term( $term_id, EWD_UWCF_PRODUCT_SIZE_TAXONOMY );

		$args = array(
			'name' => $term->name,
			'slug' => $term->slug,
			'parent' => $term->parent,
			'description' => $term->description
		);

		// Sync with the WooCommerce taxonomy term
		$wc_term_id = get_term_meta( $term_id, 'EWD_UWCF_WC_Term_ID', true ); 

		if ( ! is_wp_error( $wc_term_id ) and $wc_term_id ) {

			wp_update_term( $wc_term_id, 'pa_ewd_uwcf_sizes', $args );
		}
		else {

			$wc_term = get_term_by( 'name', sanitize_text_field( $term->name ), 'pa_ewd_uwcf_sizes' );
			if ( $wc_term ) {

				update_term_meta( $term_id, 'EWD_UWCF_WC_Term_ID', $wc_term->term_id );
			}
			else {

				$wc_term = wp_insert_term( $term->name, 'pa_ewd_uwcf_sizes', $args ); 
				if ( ! is_wp_error( $wc_term ) and $wc_term ) { update_term_meta( $term_id, 'EWD_UWCF_WC_Term_ID', $wc_term['term_id'] ); }
			}
		} 

		// Set the the term's ordering location
		if ( get_term_meta( $term_id, 'EWD_UWCF_Term_Order', true ) ) {
			
			if ( $term->parent != -1 ) {
	
				update_term_meta( $term['term_id'], 'EWD_UWCF_Term_Order', get_term_meta( $args['parent'], 'EWD_UWCF_Term_Order', true ) );
			}
			else { 
	
				update_term_meta($term['term_id'], 'EWD_UWCF_Term_Order', 999);
			}
		}

	}

	public function delete_size_field( $term_id, $taxonomy ) {

		if ( $taxonomy != EWD_UWCF_PRODUCT_SIZE_TAXONOMY ) { return; }

		$wc_term_id = get_term_meta( $term_id, 'EWD_UWCF_WC_Term_ID', true );

		if ( $wc_term_id ) { wp_delete_term( $wc_term_id, 'pa_ewd_uwcf_sizes' ); }
	}

	/**
	 * Adds in a color column for the color taxonomy type
	 * @since 3.0.0
	 */
	public function register_color_column( $columns ) {
		
		$columns['color'] = 'Color';

		return $columns;
	}

	/**
	 * Displays the content for the color column of the color taxonomy
	 * @since 3.0.0
	 */
	public function display_color_column_content( $content, $column_name, $term_id ) {
		
		if ( $column_name == 'color' ) {

			$color = get_term_meta( $term_id, 'EWD_UWCF_Color', true );

			if ( strpos( $color, 'http' ) === false ) {
				
				$content = '<div style="width:25px; height:25px; margin-left:6px; background-color:' . $color . ';"></div>';
			}
			else {

				$content = '<div style="width:25px; height:25px; margin-left:6px; background:url(\'' . $color . '\'); background-size: cover;"></div>';
			}
		}

		return $content;
	}

	/**
	 * Sort the color and size taxonomy terms by the user-set custom order
	 * @since 3.0.0
	 */
	public function sort_by_order( $query ) {

		if ( ! is_admin() ) { return; }

		if ( ! isset( $_GET['taxonomy'] ) ) { return; } 

		if ( $_GET['taxonomy'] != 'product_color' and $_GET['taxonomy'] != 'product_size' ) { return; }

		if ( isset( $_GET['orderby'] ) ) { return; }
		
		$query->set( 'meta_key', 'EWD_UWCF_Term_Order' );
		$query->set( 'orderby', 'meta_value_num' );
		$query->set( 'order', 'ASC' );
	}

	/**
	 * Save the user-set custom order for the color taxonomy terms
	 * @since 3.0.0
	 */
	public function update_color_order() {

		// Authenticate request
		if ( ! check_ajax_referer( 'ewd-uwcf-admin-js', 'nonce' ) || ! current_user_can( 'manage_options' ) ) {

			ewduwcfHelper::admin_nopriv_ajax();
		}

		$ids = is_array( json_decode( stripslashes( $_POST['ids'] ) ) ) ? json_decode( stripslashes( $_POST['ids'] ) ) : array();
	
		foreach ( $ids as $order => $term_id ) {

			update_term_meta( intval( $term_id ), 'EWD_UWCF_Term_Order', sanitize_text_field( $order ) );
		}
	}

	/**
	 * Save the user-set custom order for the size taxonomy terms
	 * @since 3.0.0
	 */
	public function update_size_order() {

		// Authenticate request
		if ( ! check_ajax_referer( 'ewd-uwcf-admin-js', 'nonce' ) || ! current_user_can( 'manage_options' ) ) {

			ewduwcfHelper::admin_nopriv_ajax();
		}

		$ids = is_array( json_decode( stripslashes( $_POST['ids'] ) ) ) ? json_decode( stripslashes( $_POST['ids'] ) ) : array();
	
		foreach ( $ids as $order => $term_id ) {
			
			update_term_meta( intval( $term_id ), 'EWD_UWCF_Term_Order', sanitize_text_field( $order ) );
		}
	}
}
} // endif;
