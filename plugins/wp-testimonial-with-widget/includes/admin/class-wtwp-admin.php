<?php
/**
 * Admin Class
 *
 * Handles the admin functionality of plugin
 *
 * @package WP Testimonials with rotator widget
 * @since 2.2.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wtwp_Admin {

	function __construct() {

		// Add Metabox
		add_action( 'admin_menu', array( $this, 'wtwp_meta_box_setup' ) );

		// Save Post Action 
		add_action( 'save_post_'.WTWP_POST_TYPE, array( $this, 'meta_box_save' ) );

		// Action to add metabox
		add_action( 'add_meta_boxes', array( $this, 'wtwp_post_sett_metabox'), 10, 2 );

		// Action to add admin menu
		add_action( 'admin_menu', array( $this, 'wtwp_register_menu' ), 12 );

		// Init Processes
		add_action( 'admin_init', array( $this, 'wtwp_admin_init_process' ) );

		// Testimonial post columns Processes
		add_filter( 'manage_edit-testimonial_columns', array( $this, 'register_custom_column_headings' ) );
		add_action( 'manage_posts_custom_column',  array( $this, 'register_custom_columns' ) );

		// Testimonial category columns Processes
		add_filter("manage_edit-testimonial-category_columns", array( $this, 'testimonial_category_manage_columns' ));
		add_filter("manage_testimonial-category_custom_column", array( $this, 'testimonial_category_columns' ), 10, 3);
	}

	/**
	 * Function to add meta box
	 * 
	 * @since 1.0
	 */
	function wtwp_meta_box_setup() {
		add_meta_box( 'testimonial-details', __( 'Testimonial Details', 'wp-testimonial-with-widget' ), array( $this, 'wtwp_meta_box_content' ), 'testimonial', 'normal', 'high' );
	}

	/**
	 * Function to Manage metabox content
	 * 
	 * @since 1.0
	 */
	function wtwp_meta_box_content() {

		global $post_id;

		$fields = get_post_custom( $post_id );
		$field_data = get_custom_fields_settings();

		$html = '';
		$html .= wp_nonce_field( 'meta_box_save', 'sp_testimonial_noonce' );
		if ( 0 < count( $field_data ) ) {
			$html .= '<table class="form-table">' . "\n";
			$html .= '<tbody>' . "\n";

			foreach ( $field_data as $k => $v ) {
				$data = $v['default'];

				if ( isset( $fields['_' . $k] ) && isset( $fields['_' . $k][0] ) ) {
					$data = $fields['_' . $k][0];
				}

				$html .= '<tr valign="top"><th scope="row"><label for="' . esc_attr( $k ) . '">' . $v['name'] . '</label></th><td><input name="' . esc_attr( $k ) . '" type="text" id="' . esc_attr( $k ) . '" class="regular-text" value="' . esc_attr( $data ) . '" />' . "<br/>";
				$html .= '<span class="description">' . $v['description'] . '</span>' . "\n";
				$html .= '</td><tr/>' . "\n";
			}

			$html .= '<tr class="wtwp-pro-feature"><th scope="row">Rating <span class="wtwp-pro-tag">PRO</span></th><td><input name="testimonial_rating" type="text" class="regular-text" value="" disabled="" />' . "<br/>";
			$html .= '<span class="description">' . __( 'Select testimonial rating.', 'wp-testimonial-with-widget' ) . '</span><strong>'.' '. sprintf( __( 'Utilize these <a href="%s" target="_blank">Premium Features (With Risk-Free 30 days money back guarantee)</a> to get best of this plugin with Annual or Lifetime bundle deal.', 'wp-testimonial-with-widget' ), WTWP_PLUGIN_LINK_UNLOCK);
			$html .= '</strong></td><tr/>' . "\n";

			$html .= '</tbody>' . "\n";
			$html .= '</table>' . "\n";
		}

		echo $html;
	}

	/**
	 * Function to Manage meta_box_save
	 * 
	 * @since 1.0
	 */	
	function meta_box_save( $post_id ) {

		global $post, $messages;

		// Verify
		if ( ( get_post_type( $post_id) != 'testimonial' ) ) {
			return $post_id;
		}
		if ( ! isset( $_POST['sp_testimonial_noonce'] ) ) {
			return $post_id;
		}
		if ( ! wp_verify_nonce( $_POST['sp_testimonial_noonce'], 'meta_box_save' ) ) {
			return $post_id;
		}
		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		$field_data = get_custom_fields_settings();
		$fields = array_keys( $field_data );

		foreach ( $fields as $f ) {

			${$f} = strip_tags(trim($_POST[$f]));
			//echo '<pre>';print_r(${$f});die;
			// Escape the URLs.
			if ( 'url' == $field_data[$f]['type'] ) {

				${$f} = esc_url( ${$f} );
			}

			if ( get_post_meta( $post_id, '_' . $f ) == '' ) {

				add_post_meta( $post_id, '_' . $f, ${$f}, true );
			} elseif( ${$f} != get_post_meta( $post_id, '_' . $f, true ) ) {
				update_post_meta( $post_id, '_' . $f, ${$f} );
			} elseif ( ${$f} == '' ) {
				delete_post_meta( $post_id, '_' . $f, get_post_meta( $post_id, '_' . $f, true ) );
			}
		}
	}

	/**
	 * Post Settings Metabox
	 * 
	 * @since 2.6.3
	 */
	function wtwp_post_sett_metabox( $post_type, $post ) {
		add_meta_box( 'wtwp-post-metabox-pro', __( 'More Premium - Settings', 'wp-testimonial-with-widget' ), array( $this, 'wtwp_post_sett_box_callback_pro' ), WTWP_POST_TYPE, 'normal', 'high' );
	}

	/**
	 * Function to handle 'premium ' metabox HTML
	 * 
	 * @since 2.6.3
	 */
	function wtwp_post_sett_box_callback_pro( $post ) {
		include_once( WTWP_DIR .'/includes/admin/metabox/wtwp-post-setting-metabox.php');
	}

	/**
	 * Function to add menu
	 * 
	 * @since 2.2.8
	 */
	function wtwp_register_menu() {

		// Register plugin how it work
		add_submenu_page( 'edit.php?post_type='.WTWP_POST_TYPE, __( 'How it works, our plugins and offers', 'wp-testimonial-with-widget' ), __( 'How It Works', 'wp-testimonial-with-widget' ), 'manage_options', 'wptww-designs', array($this, 'wptww_designs_page') );

		// Setting page
		add_submenu_page( 'edit.php?post_type='.WTWP_POST_TYPE, __( 'Overview - WP Testimonials with rotator widget', 'wp-testimonial-with-widget' ), '<span style="color:#2ECC71">'. __( 'Overview', 'wp-testimonial-with-widget' ).'</span>', 'manage_options', 'wtwp-solutions-features', array( $this, 'wtwp_solutions_features_page' ) );

		// Register plugin premium page
		add_submenu_page( 'edit.php?post_type='.WTWP_POST_TYPE, __( 'Upgrade To PRO - WP Testimonials with rotator widget', 'wp-testimonial-with-widget' ), '<span style="color:#ff2700">'.__( 'Upgrade To PRO', 'wp-testimonial-with-widget' ).'</span>', 'manage_options', 'wtwp-premium', array( $this, 'wtwp_premium_page' ) );
	}

	/**
	 * Function to display plugin design HTML
	 * 
	 * @since 1.0.0
	 */
	function wptww_designs_page() {
		include_once( WTWP_DIR . '/includes/admin/wtwp-how-it-work.php' );
	}

	/**
	 * Function to display HTML
	 * 
	 * @since 1.0.0
	 */
	function wtwp_solutions_features_page() {
		include_once( WTWP_DIR . '/includes/admin/settings/solution-features/solutions-features.php' );
	}

	/**
	 * Getting Started Page Html
	 * 
	 * @since 2.2.8
	 */
	function wtwp_premium_page() {
		//include_once( WTWP_DIR . '/includes/admin/settings/premium.php' );
	}

	/**
	 * Function to notification transient
	 * 
	 * @since 2.2.8
	 */
	function wtwp_admin_init_process() {

		global $typenow, $pagenow;

		$current_page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';

		// If plugin notice is dismissed
		if( isset( $_GET['message'] ) && $_GET['message'] == 'wtwp-plugin-notice' ) {
			set_transient( 'wtwp_install_notice', true, 604800 );
		}

		// Redirect to external page for upgrade to menu
		if( $typenow == WTWP_POST_TYPE ) {

			if( $current_page == 'wtwp-premium' ) {

				$tab_url		= add_query_arg( array( 'post_type' => WTWP_POST_TYPE, 'page' => 'wtwp-solutions-features', 'tab' => 'wtwp_basic_tabs' ), admin_url('edit.php') );

				wp_redirect( $tab_url );
				exit;
			}
		
		}
	}

	/**
	 * Function to Custom coloumns
	 * 
	 * @since 2.2.8
	 */
	function register_custom_columns ( $column_name ) {
		
		global $wpdb, $post;

		switch ( $column_name ) {
			case 'image':
				$value = '';
				$value = wtwp_get_image( get_the_ID(), 40 ,'square');
				echo $value;
			break;
			default:
			break;
		}
	}

	/**
	 * Function to Custom coloumn headings
	 * 
	 * @since 2.2.8
	 */
	function register_custom_column_headings ( $defaults ) {
		$new_columns = array( 'image' => __( 'Image', 'wp-testimonial-with-widget' ) );
		$last_item = '';
		if ( isset( $defaults['date'] ) ) { unset( $defaults['date'] ); }
		if ( count( $defaults ) > 2 ) {
			$last_item = array_slice( $defaults, -1 );
			array_pop( $defaults );
		}
		$defaults = array_merge( $defaults, $new_columns );
		if ( $last_item != '' ) {
			foreach ( $last_item as $k => $v ) {
				$defaults[$k] = $v;
				break;
			}
		}
		return $defaults;
	}

	/**
	 * Function to Manage Category manage Columns
	 * 
	 * @since 1.0
	 */
	function testimonial_category_manage_columns( $columns ) {

		$new_columns = array(
							'wtwp_cat_shortcode' => esc_html__( 'Testimonial Shortcode', 'wp-testimonial-with-widget' )
						);

		$columns = wtwp_add_array( $columns, $new_columns, 1, true );

		return $columns;
	}

	/**
	 * Function to Manage Category Shortcode Columns
	 * 
	 * @since 1.0
	 */
	function testimonial_category_columns($out, $column_name, $cat_id) {

		switch ( $column_name ) {

			case 'wtwp_cat_shortcode':
				$out .= '[sp_testimonials category="' . esc_attr( $cat_id ). '"]<br />';
				$out .= '[sp_testimonials_slider category="' . esc_attr( $cat_id ). '"]';
			break;

			default:
				break;
		}

		return $out;
	}
}

$wtwp_Admin = new Wtwp_Admin();