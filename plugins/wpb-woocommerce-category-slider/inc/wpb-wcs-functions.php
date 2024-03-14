<?php

/**
 * Plugin functions
 * Author : WpBean
 */


/**
 * Enqueue Script For Front-end
 */

if( !function_exists('wpb_wcs_adding_scripts') ){

	function wpb_wcs_adding_scripts() {

		wp_register_style( 'font-awesoume', plugins_url( '../assets/icons/font-awesome/css/font-awesome.min.css', __FILE__ ), array(), '4.7.0' );
		wp_register_style( 'wpb-wcs-plugin-icons-collections', plugins_url( '../assets/icons/plugin-icons-collections/css/flaticon.css', __FILE__ ), array(), '1.0' );

		wp_register_style( 'owl-carousel',  plugins_url( '../assets/css/owl.carousel.css', __FILE__ ), array(), '2.2.1' );
		wp_register_script( 'owl-carousel', plugins_url( '../assets/js/owl.carousel.js', __FILE__ ), array('jquery'), '2.2.1', false);

		wp_register_style( 'wpb-wcs-bootstrap-grid', plugins_url( '../assets/css/bootstrap-grid.min.css', __FILE__ ), array(), '4.0' );

		wp_register_style( 'wpb-wcs-main', plugins_url( '../assets/css/main.css', __FILE__ ), array(), '1.0' );
		wp_register_script('wpb-wcs-main', plugins_url( '../assets/js/main.js', __FILE__ ), array('jquery'), '1.0', false);
		
	}

}
add_action( 'wp_enqueue_scripts', 'wpb_wcs_adding_scripts' );


/**
 * Custom styles
 */

add_action('wp_enqueue_scripts','wpb_wcs_custom_style');

if( !function_exists('wpb_wcs_custom_style') ){
	function wpb_wcs_custom_style(){
		wp_enqueue_style( 'wpb-wcs-main', plugins_url('../assets/css/main.css', __FILE__), '', '1.0', false);

		$text_color = wpb_wcs_get_option( 'text_color', 'style_settings', '' );
		$primary_color = wpb_wcs_get_option( 'primary_color', 'style_settings', '#39a1f4' );
		$secondary_color = wpb_wcs_get_option( 'secondary_color', 'style_settings', '#2196F3' );
		$slider_bg_color = wpb_wcs_get_option( 'slider_bg_color', 'style_settings', '#ededed' );

		ob_start();
		?>
			<?php if( $text_color ): ?>
				body .wpb-woo-cat-items, body  .wpb-woo-cat-items a:visited {
				    color: <?php echo $text_color; ?>;
				}
			<?php endif; ?>

			.wpb-woo-cat-items .wpb-woo-cat-item a.btn:hover,
			.wpb-woo-cat-items.owl-theme .owl-nav [class*=owl-]:hover,
			.wpb-woo-cat-items.owl-theme .owl-dots .owl-dot.active span, .wpb-woo-cat-items.owl-theme .owl-dots .owl-dot:hover span {
				background: <?php echo $primary_color; ?>;
			}
			.wpb-woo-cat-items.wpb-wcs-content-type-plain_text .wpb-woo-cat-item a:hover,
			.wpb-woo-cat-items .wpb-woo-cat-item a:hover {
				color: <?php echo $primary_color; ?>;
			}

			.wpb-woo-cat-items .wpb-woo-cat-item a.btn,
			.wpb-woo-cat-items.owl-theme .owl-nav [class*=owl-] {
				background: <?php echo $secondary_color; ?>;
			}

			.wpb-woo-cat-items .wpb-woo-cat-item {
				background: <?php echo $slider_bg_color; ?>;
			}

		<?php
		$custom_css = ob_get_clean();
		wp_add_inline_style( 'wpb-wcs-main', $custom_css );
	}
}


/**
 * Admin scripts
 */

if( !function_exists('wpb_wcs_load_admin_scripts') ){
	function wpb_wcs_load_admin_scripts() {
	    wp_register_style( 'wpb-wcs-admin', plugins_url( '../admin/assets/css/admin-style.css', __FILE__ ), array(), '1.0' );
	}
}
add_action( 'admin_enqueue_scripts', 'wpb_wcs_load_admin_scripts' );


/**
 * Get the setting values 
 */

if( !function_exists('wpb_wcs_get_option') ){
	function wpb_wcs_get_option( $option, $section, $default = '' ) {
	 
	    $options = get_option( $section );
	 
	    if ( isset( $options[$option] ) ) {
	        return $options[$option];
	    }
	 
	    return $default;
	}
}



/**
 * Adding the menu page
 */

if( !function_exists('wpb_wcs_register_menu_page') ){
	function wpb_wcs_register_menu_page() {
	    add_menu_page(
	        __( 'WPB WooCommerce Category Slider', WPB_WCS_TEXTDOMAIN ),
	        __( 'Woo Cat Slider', WPB_WCS_TEXTDOMAIN ),
	        apply_filters( 'wpb_wcs_settings_user_capability', 'manage_options' ),
	        WPB_WCS_TEXTDOMAIN.'-about',
	        'wpb_wcs_get_menu_page',
	        'dashicons-images-alt'
	    );
	}
}
add_action( 'admin_menu', 'wpb_wcs_register_menu_page' );


/**
 * Getting the menu page
 */

if( !function_exists('wpb_wcs_get_menu_page') ){
	function wpb_wcs_get_menu_page(){
		require ( WPB_WCS_PLUGIN_DIR . '/admin/wpb-wcs-admin-page.php' );
	}
}


/**
 * bottom left admin text
 */

if( !function_exists('wpb_wcs_wp_admin_bottom_left_text') ){
	function wpb_wcs_wp_admin_bottom_left_text( $text ) {
		$screen = get_current_screen();

		if( $screen->base == 'toplevel_page_wpb-woocommerce-category-slider-about' || $screen->base == 'woo-cat-slider_page_wpb-woocommerce-category-slider-settings' ){
			$text = 'If you like <strong>WooCommerce Category Slider</strong> please leave us a <a href="https://wordpress.org/support/plugin/wpb-woocommerce-category-slider/reviews?rate=5#new-post" target="_blank" class="wpb-wcs-rating-link" data-rated="Thanks :)">★★★★★</a> rating. A huge thanks in advance!';
		}
		
		return $text;
	}
}
add_filter( 'admin_footer_text', 'wpb_wcs_wp_admin_bottom_left_text' );


/**
 * Show Categories ID's in admin column
 */

add_filter( "manage_edit-product_cat_columns",          'wpb_wcs_add_new_col_for_id' );
add_filter( "manage_edit-product_cat_sortable_columns", 'wpb_wcs_add_new_col_for_id' );
add_filter( "manage_product_cat_custom_column",         'wpb_wcs_product_cat_id_display', 10, 3 );

if( !function_exists('wpb_wcs_add_new_col_for_id') ){
	function wpb_wcs_add_new_col_for_id( $columns ) {

	    $columns['tax_id'] = __( 'ID', WPB_WCS_TEXTDOMAIN );

	    return $columns;
	}
}


if( !function_exists('wpb_wcs_product_cat_id_display') ){
	function wpb_wcs_product_cat_id_display( $columns, $column, $id ) {    

	    if ( 'tax_id' == $column ) {
	    	$columns .= esc_html( $id ); 
	    }

	    return $columns;
	}
}


/**
 * Pro version discount
 */

function wpb_wcs_pro_discount_admin_notice() {
    $user_id = get_current_user_id();
    if ( !get_user_meta( $user_id, 'wpb_wcs_pro_discount_dismissed' ) ){
        printf('<div class="wpb-wcs-discount-notice updated" style="padding: 30px 20px;border-left-color: #27ae60;border-left-width: 5px;margin-top: 20px;"><p style="font-size: 18px;line-height: 32px">%s <a target="_blank" href="%s">%s</a>! %s <b>%s</b></p><a href="%s">%s</a></div>', esc_html__( 'Get a 10% exclusive discount on the premium version of the', WPB_WCS_TEXTDOMAIN ), 'https://wpbean.com/downloads/wpb-woocommerce-category-slider-pro/', esc_html__( 'WPB Product Categories Slider for WooCommerce', WPB_WCS_TEXTDOMAIN ), esc_html__( 'Use discount code - ', WPB_WCS_TEXTDOMAIN ), '10PERCENTOFF', esc_url( add_query_arg( 'wpb-wcs-pro-discount-admin-notice-dismissed', 'true' ) ), esc_html__( 'Dismiss', WPB_WCS_TEXTDOMAIN ));
    }
}


function wpb_wcs_pro_discount_admin_notice_dismissed() {
    $user_id = get_current_user_id();
    if ( isset( $_GET['wpb-wcs-pro-discount-admin-notice-dismissed'] ) ){
        add_user_meta( $user_id, 'wpb_wcs_pro_discount_dismissed', 'true', true );
    }
}


/**
 * Plugin Deactivation
 */

function wpb_wcs_lite_plugin_deactivation() {
  $user_id = get_current_user_id();
  if ( get_user_meta( $user_id, 'wpb_wcs_pro_discount_dismissed' ) ){
  	delete_user_meta( $user_id, 'wpb_wcs_pro_discount_dismissed' );
  }
}



/**
 * Add a widget to the dashboard.
 *
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */
function wpb_wcs_add_dashboard_widgets() {
    wp_add_dashboard_widget( 'wpb_wcs_pro_features', esc_html__( 'WPB Product Categories Slider for WooCommerce', WPB_WCS_TEXTDOMAIN ), 'wpb_wcs_pro_features_dashboard_widget_render' ); 
}

function wpb_wcs_pro_features_dashboard_widget_render() {
    ?>
    <ul class="wpb-wcs-dash-widget-feature">
		<li><span class="dashicons dashicons-yes-alt"></span>Show any of your custom taxonomy or category in the slider.</li>
		<li><span class="dashicons dashicons-yes-alt"></span>Category icon picker with couple of different icon packs.</li>
		<li><span class="dashicons dashicons-yes-alt"></span>Category Slider with Category Image Background, Icons, Sub Categories, button etc.</li>
		<li><span class="dashicons dashicons-yes-alt"></span>Super easy ShortCode builder for generating customized slider ShortCodes.</li>
		<li><span class="dashicons dashicons-yes-alt"></span>Couple of different sinks for the slider.</li>
		<li><span class="dashicons dashicons-yes-alt"></span>Tested with most of the popular premium WordPress themes.</li>
	</ul>
	<div class="wpb-wcs-dash-widget-upgrade-btns">
		<a class="wpb-wcs-dash-widget-btn wpb-wcs-dash-widget-upgrade-btn" href="https://wpbean.com/downloads/wpb-woocommerce-category-slider-pro/" target="_blank">Upgrade to Pro</a>
		<a class="wpb-wcs-dash-widget-btn wpb-wcs-dash-widget-demo-btn" href="http://demo5.wpbean.com/wpb-woo-category-slider/" target="_blank">Live Demo</a>
	</div>
	<style>
		.wpb-wcs-dash-widget-btn {
			border-radius: 5px;
			margin-right: 7px;
			color: #fff;
			display: inline-block;
			margin-top: 10px;
			margin-bottom: 15px;
			padding: 15px 28px 17px;
			text-decoration: none;
			font-weight: 700;
			line-height: normal;
			font-size: 15px;
			-webkit-font-smoothing: antialiased;
			-webkit-transition: all .3s linear;
			-moz-transition: all .3s linear;
			-ms-transition: all .3s linear;
			-o-transition: all .3s linear;
			transition: all .3s linear;
		}
		.wpb-wcs-dash-widget-upgrade-btn{
			background: #f2295b;
		}
		.wpb-wcs-dash-widget-upgrade-btn:hover, .wpb-wcs-dash-widget-upgrade-btn:focus { 
			background: #c71843;
		}
		.wpb-wcs-dash-widget-demo-btn{
			background: #007cf5;
		}
		.wpb-wcs-dash-widget-demo-btn:hover, .wpb-wcs-dash-widget-demo-btn:focus { 
			background: #126dca;
		}
		.wpb-wcs-dash-widget-btn:hover, .wpb-wcs-dash-widget-btn:focus {
			color: #fff;
			-webkit-box-shadow: 0 7px 12px rgba(50,50,93,.1), 0 3px 6px rgba(0,0,0,.08);
			-moz-box-shadow: 0 7px 12px rgba(50,50,93,.1),0 3px 6px rgba(0,0,0,.08);
			box-shadow: 0 7px 12px rgba(50,50,93,.1), 0 3px 6px rgba(0,0,0,.08);
		}
		.wpb-wcs-dash-widget-feature li {
		    margin-bottom: 15px;
		}
		.wpb-wcs-dash-widget-feature .dashicons {
			color: #f2295b;
			margin-right: 10px;
		}
		.rtl .wpb-wcs-dash-widget-feature .dashicons {
			margin-right: 0;
			margin-left: 10px;
		}
		.rtl .wpb-wcs-dash-widget-btn {
			margin-right: 0;
			margin-left: 7px;
		}
	</style>
    <?php
}


/**
 * Template loader
 */

if ( ! class_exists( 'WPB_Gamajo_Template_Loader' ) ) {
	require_once dirname( __FILE__ ) . '/class-gamajo-template-loader.php';
}

class WPB_WCS_Template_Loader extends WPB_Gamajo_Template_Loader {
  /**
   * Prefix for filter names.
   *
   * @since 1.0.0
   *
   * @var string
   */
  protected $filter_prefix = 'wpb_wcs';

  /**
   * Directory name where custom templates for this plugin should be found in the theme.
   *
   * @since 1.0.0
   *
   * @var string
   */
  protected $theme_template_directory = 'wpb-woocommerce-category-slider';

  /**
   * Reference to the root directory path of this plugin.
   *
   * Can either be a defined constant, or a relative reference from where the subclass lives.
   *
   * In this case, `WPB_WCS_PLUGIN_DIR` would be defined in the root plugin file as:
   *
   * ~~~
   * define( 'WPB_WCS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
   * ~~~
   *
   * @since 1.0.0
   *
   * @var string
   */
  protected $plugin_directory = WPB_WCS_PLUGIN_DIR;

  /**
   * Directory name where templates are found in this plugin.
   *
   * Can either be a defined constant, or a relative reference from where the subclass lives.
   *
   * e.g. 'templates' or 'includes/templates', etc.
   *
   * @since 1.1.0
   *
   * @var string
   */
  protected $plugin_template_directory = 'templates';
}