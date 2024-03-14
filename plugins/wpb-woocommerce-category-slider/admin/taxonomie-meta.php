<?php

/**
 * WPB WooCommerce Category Slider Plugin
 *
 * Product Category Icon Picker
 *
 * Author: WpBean
 */

/**
 * Course Category Icon Support
 */

add_action( 'product_cat_add_form_fields', 'wpb_wcs_taxonomy_add_icon_field', 20, 2 );
add_action( 'product_cat_edit_form_fields', 'wpb_wcs_taxonomy_edit_icon_field', 20, 2 );
add_action( 'edited_product_cat', 'wpb_wcs_save_taxonomy_icon', 10, 2 );  
add_action( 'create_product_cat', 'wpb_wcs_save_taxonomy_icon', 10, 2 );
add_filter( 'manage_edit-product_cat_columns', 'wpb_wcs_columns_head', 10, 3 );
add_filter( 'manage_product_cat_custom_column', 'wpb_wcs_columns_content_taxonomy', 10, 3 );
add_action( 'admin_enqueue_scripts', 'wpb_wcs_product_cat_icon_scripts_admin' );


/**
 * Icon scripts
 */

if( !function_exists('wpb_wcs_product_cat_icon_scripts_admin') ){
	function wpb_wcs_product_cat_icon_scripts_admin(){
		$screen = get_current_screen();
		if( $screen->id == 'edit-product_cat' ){

			wp_enqueue_script('wpb-wcs-fonticonpicker', plugins_url( '/assets/js/jquery.fonticonpicker.min.js', __FILE__ ), array('jquery'), '2.0', true);
			wp_enqueue_style( 'wpb-wcs-fonticonpicker',  plugins_url( '/assets/css/jquery.fonticonpicker.min.css', __FILE__ ), array(), '2.0' );
			wp_enqueue_style( 'wpb-wcs-fonticonpicker-grey-theme',  plugins_url( '/assets/css/jquery.fonticonpicker.grey.min.css', __FILE__ ), array(), '2.0' );

			wp_enqueue_style( 'font-awesoume', plugins_url( '../assets/icons/font-awesome/css/font-awesome.min.css', __FILE__ ), array(), '4.7.0' );
			wp_enqueue_style( 'wpb-wcs-plugin-icons-collections', plugins_url( '../assets/icons/plugin-icons-collections/css/flaticon.css', __FILE__ ), array(), '1.0' );
		}
	}
}



/* Add term page */

if( !function_exists('wpb_wcs_taxonomy_add_icon_field') ){
	function wpb_wcs_taxonomy_add_icon_field() {
		?>
		<div class="form-field">
			<label for="wpb_wcs_term_meta[wpb_wcs_cat_icons]"><?php esc_html_e( 'Icon', WPB_WCS_TEXTDOMAIN ); ?></label>
			<div class="wpb-wcs-icon-picker-wrapper">
				<input type="text" name="wpb_wcs_term_meta[wpb_wcs_cat_icons]" id="wpb-wcs-icon-picker" value="">
			</div>
			<p class="description"><?php esc_html_e( 'Choose an icon', WPB_WCS_TEXTDOMAIN ); ?></p>
		</div>
	<?php
	}
}


/* Edit term page */ 

if( !function_exists('wpb_wcs_taxonomy_edit_icon_field') ){
	function wpb_wcs_taxonomy_edit_icon_field( $term ) {
	 
		// put the term ID into a variable
		$t_id = $term->term_id;
	 
		// retrieve the existing value(s) for this meta field. This returns an array
		$wpb_wcs_term_meta = get_option( "taxonomy_$t_id" ); ?>
		<tr class="form-field">
		<th scope="row" valign="top"><label for="wpb_wcs_term_meta[wpb_wcs_cat_icons]"><?php esc_html_e( 'Icon', WPB_WCS_TEXTDOMAIN ); ?></label></th>
			<td>
				<div class="wpb-wcs-icon-picker-wrapper" data-pickerid="fa" data-iconsets='{"fa":"Category Icon"}'>
					<input type="text" name="wpb_wcs_term_meta[wpb_wcs_cat_icons]" id="wpb-wcs-icon-picker" value="<?php echo esc_attr( $wpb_wcs_term_meta['wpb_wcs_cat_icons'] ) ? esc_attr( $wpb_wcs_term_meta['wpb_wcs_cat_icons'] ) : ''; ?>">
				</div>
				<p class="description"><?php esc_html_e( 'Choose an icon', WPB_WCS_TEXTDOMAIN ); ?></p>
			</td>
		</tr>
	<?php
	}
}


/* Save extra taxonomy fields callback function */

if( !function_exists('wpb_wcs_save_taxonomy_icon') ){
	function wpb_wcs_save_taxonomy_icon( $term_id ) {
		if ( isset( $_POST['wpb_wcs_term_meta'] ) ) {
			$t_id = $term_id;
			$wpb_wcs_term_meta = get_option( "taxonomy_$t_id" );
			$cat_keys = array_keys( $_POST['wpb_wcs_term_meta'] );
			foreach ( $cat_keys as $key ) {
				if ( isset ( $_POST['wpb_wcs_term_meta'][$key] ) ) {
					$wpb_wcs_term_meta[$key] = $_POST['wpb_wcs_term_meta'][$key];
				}
			}
			// Save the option array.
			update_option( "taxonomy_$t_id", $wpb_wcs_term_meta );
		}
	}
}


/**
 * add icon to texonomy column
 */

/* Column Head */
if( !function_exists('wpb_wcs_columns_head') ){
	function wpb_wcs_columns_head($defaults) {
	    $defaults['wpb_wcs_cat_icons']  = esc_html__( 'Icon', WPB_WCS_TEXTDOMAIN );
	    return $defaults;
	}
}


/* Column Content */

if( !function_exists('wpb_wcs_columns_content_taxonomy') ){
	function wpb_wcs_columns_content_taxonomy( $columns, $column, $id ) {

	    if ( $column == 'wpb_wcs_cat_icons' ) {
	    	$wpb_wcs_term_meta = get_option( "taxonomy_$id" );

	    	if( $wpb_wcs_term_meta && array_key_exists( 'wpb_wcs_cat_icons', $wpb_wcs_term_meta ) ){
	    		$columns .= '<i class="fa-2x '. esc_attr($wpb_wcs_term_meta['wpb_wcs_cat_icons']) .'"></i>';
	    	}
	    }

	    return $columns;

	}
}




/**
 * Making Font Awesome icons string for icon picker 
 */

if( !function_exists('wpb_wcs_font_awesome_icons_for_iconpicker') ){
	function wpb_wcs_font_awesome_icons_for_iconpicker(){
		$pattern = '/\.(fa-(?:\w+(?:-)?)+):before\s+{\s*content:\s*"\\\\(.+)";\s+}/';
		$subject =  wp_remote_fopen( plugin_dir_url( __FILE__ ).'../assets/icons/font-awesome/css/font-awesome.css' );
		preg_match_all( $pattern, $subject, $matches, PREG_SET_ORDER );

		foreach($matches as $match) {
		    $icons[$match[1]] = $match[2];
		}

		$output = [];

		if( $icons && is_array($icons) ){
			ksort($icons);

			if( !empty($icons) ){
				foreach ($icons as $key => $icon) {
					$output[] = 'fa '.$key;
				}

				return "'".implode( "','", $output )."'";
			}
		}
	}
}

/**
 * Making Plugin icons string for icon picker 
 */

if( !function_exists('wpb_wcs_plugin_icons_collections_for_iconpicker') ){
	function wpb_wcs_plugin_icons_collections_for_iconpicker(){
		$pattern = '/\.(flaticon-(?:\w+(?:-)?)+):before\s+{\s*content:\s*"\\\\(.+)";\s+}/';
		$subject =  wp_remote_fopen( plugin_dir_url( __FILE__ ) . '../assets/icons/plugin-icons-collections/css/flaticon.css' );
		preg_match_all( $pattern, $subject, $matches, PREG_SET_ORDER );

		foreach($matches as $match) {
		    $icons[$match[1]] = $match[2];
		}

		$output = [];

		if( $icons && is_array($icons) ){
			ksort($icons);

			if( !empty($icons) ){
				foreach ($icons as $key => $icon) {
					$output[] = 'fa '.$key;
				}

				return "'".implode( "','", $output )."'";
			}
		}
	}
}



/**
 * Trigger the icon picker
 */

add_action( 'admin_footer', 'wpb_wcs_product_cat_icon_init' );

if( !function_exists('wpb_wcs_product_cat_icon_init') ){
	function wpb_wcs_product_cat_icon_init(){
		$screen = get_current_screen();
		if( $screen->id == 'edit-product_cat' ){
			?>
			<script>
			    jQuery(document).ready(function($) {
			    	var wpb_wcs_products_cat_icons = {
					    '<?php esc_html_e( 'Plugin Icons', WPB_WCS_TEXTDOMAIN ); ?>' 	: [<?php echo wpb_wcs_plugin_icons_collections_for_iconpicker();?>],
					    '<?php esc_html_e( 'Font Awesome', WPB_WCS_TEXTDOMAIN ); ?>' 	: [<?php echo wpb_wcs_font_awesome_icons_for_iconpicker();?>],
					    <?php do_action( 'wpb_wcs_products_cat_icons' ) ?>
					};

			        $('#wpb-wcs-icon-picker').fontIconPicker({
			            source:    wpb_wcs_products_cat_icons,
			            emptyIcon: true,
			            hasSearch: true,
			            iconsPerPage: 72,
			        });
			    });
			</script>
			<?php
		}
	}
}