<?php
/*
 * Plugin Name: Product Excel Import Export & Bulk Edit for WooCommerce 
 * Plugin URI: https://extend-wp.com/product-bulk-edit-product-excel-importer-for-woocommerce/
 * Description: Bulk Product Editing for Simple WooCommerce Products & Import with Excel
 * Version: 4.6
 * Author: extendWP
 * Author URI: https://extend-wp.com
 *
 * WC requires at least: 2.2
 * WC tested up to: 8.5.3
 *  
 * License: GPL2
 * Created On: 09-11-2017
 * Updated On: 06-02-2024
 * Text Domain: webd_bulk_edit 
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



include( plugin_dir_path(__FILE__) .'/bulk_edit_products.php');
include( plugin_dir_path(__FILE__) .'/excel_products.php');

function load_webd_woocommerce_product_excel_importer_bulk_edit_js(){


	$screen = get_current_screen();
	//var_dump( $screen );
	if ( 'toplevel_page_webd-woocommerce-product-excel-importer-bulk-edit'  !== $screen->base )
	return;

    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-accordion');
	wp_enqueue_script('jquery-ui-draggable');
	wp_enqueue_script('jquery-ui-droppable');
		
	if( ! wp_script_is( "webd_woocommerce_bulk_edit_fa", 'enqueued' ) ) {
		wp_enqueue_style( 'webd_woocommerce_bulk_edit_fa', plugins_url( '/css/font-awesome.min.css', __FILE__ ));	
	}
	
	//ENQUEUED CSS FILE INSTEAD OF INLINE CSS
	wp_enqueue_style( 'webd_woocommerce_product_excel_importer_bulk_edit_style_css', plugins_url( "/css/style.css?v=1ss", __FILE__ ) );	
	wp_enqueue_style( 'webd_woocommerce_product_excel_importer_bulk_edit_style_css');		
		
    wp_enqueue_script( 'webd_woocommerce_product_excel_importer_bulk_edit_style_js_excel', plugins_url( '/js/javascript_excel.js?v=1s', __FILE__ ), array('jquery','jquery-ui-core','jquery-ui-tabs','jquery-ui-draggable','jquery-ui-droppable') , null, true);		
	wp_enqueue_script( 'webd_woocommerce_product_excel_importer_bulk_edit_style_js_excel');
    wp_enqueue_script( 'webd_woocommerce_product_excel_importer_bulk_edit_style_js_bulk_edit', plugins_url( '/js/javascript_bulk_edit.js', __FILE__ ), array('jquery','jquery-ui-core','jquery-ui-tabs','jquery-ui-draggable','jquery-ui-droppable') , null, true);		
	wp_enqueue_script( 'webd_woocommerce_product_excel_importer_bulk_edit_style_js_bulk_edit');	
    $woopeipurl = array( 
		'plugin_url' => plugins_url( '', __FILE__ ),
		'siteUrl'	=>	site_url(),
		'nonce' => wp_create_nonce( 'wp_rest' ),
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),			
	);
	
    wp_localize_script( 'webd_woocommerce_product_excel_importer_bulk_edit_style_js_excel', 'wpeip_url', $woopeipurl );

	
}
add_action('admin_enqueue_scripts', 'load_webd_woocommerce_product_excel_importer_bulk_edit_js');



//ADD MENU LINK AND PAGE FOR WOOCOMMERCE IMPORTER
add_action('admin_menu', 'webd_woocommerce_product_excel_importer_bulk_edit_menu');

function webd_woocommerce_product_excel_importer_bulk_edit_menu() {
	add_submenu_page( 'edit.php?post_type=product', 'Product Excel Importer & Bulk Editing', 'Product Excel Importer & Bulk Editing', 'manage_options', 'webd-woocommerce-product-excel-importer-bulk-edit', 'webd_woocommerce_product_excel_importer_bulk_edit_init' );	
	add_submenu_page( 'woocommerce', 'Product Excel Importer & Bulk Editing', 'Product Excel Importer & Bulk Editing', 'manage_options', 'webd-woocommerce-product-excel-importer-bulk-edit', 'webd_woocommerce_product_excel_importer_bulk_edit_init' );
	add_menu_page('Product Excel Importer & Bulk Editing Settings', 'Product Excel Importer & Bulk Editing', 'administrator', 'webd-woocommerce-product-excel-importer-bulk-edit', 'webd_woocommerce_product_excel_importer_bulk_edit_init', 'dashicons-edit','50');
}


add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_webd_woocommerce_product_excel_importer_bulk_edit_links' );

function add_webd_woocommerce_product_excel_importer_bulk_edit_links ( $links ) {
 $links[] =  '<a href="' . admin_url( 'admin.php?page=webd-woocommerce-product-excel-importer-bulk-edit' ) . '">Settings</a>';
 $links[] = '<a href="https://extend-wp.com/product/woocommerce-product-excel-importer-bulk-editing-pro/" target="_blank">PRO Version</a>';
 $links[] = '<a href="https://extend-wp.com/" target="_blank">More plugins</a>';
   return $links;
}




function webd_woocommerce_product_excel_importer_bulk_edit_init() {
	
	$productsExcel = new WebdWoocommerceEProducts;
	$productsBulk = new WebdWoocommerceBProducts;
	
	webd_woocommerce_product_excel_importer_bulk_edit_form_header();
	?>
	<div class="excel_bulk_wrap_free" >
		<div class='left_wrap' >
			<div class='msg'></div>
			<?php 
			$tabs = array( 'main' => 'Import/Update - Excel','search-edit' => 'Search / Bulk Edit');
			if( isset( $_GET['tab'] ) ){
				$current = $_GET['tab'] ;
			}else $current = 'main';

			
			echo '<h2 class="nav-tab-wrapper" >';
			foreach( $tabs as $tab => $name ){
				$class = ( $tab == $current ) ? ' nav-tab-active' : '';
				echo "<a class='nav-tab$class contant' href='?page=webd-woocommerce-product-excel-importer-bulk-edit&tab=$tab'>$name</a>";
			}?>
				<a class='nav-tab premium' href='#'><?php esc_html_e( "Export Products", "webd_bulk_edit" ); ?></a>
				<a class='nav-tab premium' href='#'><?php esc_html_e( "Delete Products", "webd_bulk_edit" ); ?></a>			
				<a class='nav-tab premium' href='#'><?php esc_html_e( "Import Categories", "webd_bulk_edit" ); ?></a>
				<a class='nav-tab premium' href='#'><?php esc_html_e( "Delete Categories", "webd_bulk_edit" ); ?></a>
				<a class='nav-tab  excel_bulk_wrap_free_instructionsVideo' href='#excel_bulk_wrap_free_instructionsVideo'><?php esc_html_e( "Instructions", "webd_bulk_edit" ); ?></a>
				<a class='nav-tab  gopro' href='#'><?php esc_html_e( "GO PRO", "webd_bulk_edit" ); ?></a>
			<?php
			echo '</h2>';
			
			?>
			<div class='premium_msg'>
				<p>
					<strong>
						<?php esc_html_e( "Only available on Premium Version", "webd_bulk_edit" ); ?> <a class='premium_button' target='_blank'  href='https://extend-wp.com/product/woocommerce-product-excel-importer-bulk-editing-pro/'><?php esc_html_e( "Get it Here", "webd_bulk_edit" ); ?></a>
						</strong>
					</p>
			</div>
			<div class='the_Content'>
			<?php
			if(isset ( $_GET['tab'] )  && $_GET['tab']=='search-edit'){
				$productsBulk->editProductsDisplay();			
			}else $productsExcel->importProductsDisplay(); ?>	
			</div>
		</div>	
		<div class='right_wrap rightToLeft'>	
					<p>
						<a target='_blank'  href='https://extend-wp.com/product/woocommerce-product-excel-importer-bulk-editing-pro/'>
							<img class='premium_img' src='<?php echo plugins_url( 'images/webd_woocommerce_product_excel_importer_bulk_edit_pro.png', __FILE__ ); ?>' alt='Woocommerce Product Excel and Bulk Editing Pro' title='Woocommerce Product Excel and Bulk Editing Pro' />
						</a>
					</p>

					<div>
						<p><i class='fa fa-check'></i> <?php esc_html_e( "Advanced Search  - By any Taxonomy", "webd_bulk_edit" ); ?></p>					
						<p><i class='fa fa-check'></i> <?php esc_html_e( "Bulk Edit Variable Products and Variations", "webd_bulk_edit" ); ?></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e( "Bulk Edit Support for Product Taxonomies, Custom Taxonomies", "webd_bulk_edit" ); ?></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e( "Import Product Categories with their Images", "webd_bulk_edit" ); ?></p> 
						<p><i class='fa fa-check'></i> <?php esc_html_e( "Import Simple and Variable Products with Excel", "webd_bulk_edit" ); ?></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e( "Import / Update Simple Products unlimited Attributes Comma Separated!", "webd_bulk_edit" ); ?></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e( "Import / Export Affiliate/External, Downloadable products", "webd_bulk_edit" ); ?></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e( "Update Products by SKU, ID or TITLE with Excel", "webd_bulk_edit" ); ?></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e( "Import / Export ACF custom Product fields and manually defined fields", "webd_bulk_edit" ); ?></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e( "Edit Simple and Variable Products with Excel", "webd_bulk_edit" ); ?></p>	
						<p><i class='fa fa-check'></i> <?php esc_html_e( "Import WPML WooCommerce Product Translations with Excel", "webd_bulk_edit" ); ?></p>	
					    <p><i class='fa fa-check'></i> <?php esc_html_e( "Import / Export ACF custom Product fields and manually defined fields", "webd_bulk_edit" ); ?></p>
					    <p><i class='fa fa-check'></i> <?php esc_html_e( "Import / Export YOAST SEO Meta Product fields", "webd_bulk_edit" ); ?></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e( "Import Featured Image & Gallery with Excel", "webd_bulk_edit" ); ?></p>	
						<p><i class='fa fa-check'></i> <?php esc_html_e( "Save Fields Mapping Template to save Time", "webd_bulk_edit" ); ?></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e( "Schedule Product Import / Update with Cron Job from excel URL or Google sheets", "webd_bulk_edit" ); ?></p>							
						<p><i class='fa fa-check'></i> <?php esc_html_e( "Delete Simple/Variable Products from UI or Excel", "webd_bulk_edit" ); ?></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e( "Import Multiple Child-Parent Category Terms from UI or Excel", "webd_bulk_edit" ); ?></p>
						<p><i class='fa fa-check'></i> <?php esc_html_e( "Delete Category Terms from UI or Excel", "webd_bulk_edit" ); ?> </p>	
						<p><i class='fa fa-check'></i> <?php esc_html_e( "Export Products to Excel", "webd_bulk_edit" ); ?></p>	
						<p><i class='fa fa-check'></i> <?php esc_html_e( "Compatible with", "webd_bulk_edit" ); ?> <a target='_blank'  href='https://wordpress.org/plugins/woo-variation-swatches/' ><?php esc_html_e( "Variation Swatches for WooCommerce", "webd_bulk_edit" ); ?></a> , <a target='_blank'  href='https://wordpress.org/plugins/woo-variation-gallery/' ><?php esc_html_e( "Delete Categories", "webd_bulk_edit" ); ?><?php esc_html_e( "Variation Images Gallery", "webd_bulk_edit" ); ?></a> , <a target='_blank'  href='https://yithemes.com/themes/plugins/yith-woocommerce-color-and-label-variations/' ><?php esc_html_e( "YITH WooCommerce Color and Label Variations", "webd_bulk_edit" ); ?></a> , <a target='_blank'  href='https://wordpress.org/plugins/perfect-woocommerce-brands/' ><?php esc_html_e( "Perfect Brands for WooCommerce", "webd_bulk_edit" ); ?></a></p>
											
					</div>	
					<p class='center' >
						<a class='premium_button' target='_blank'  href='https://extend-wp.com/product/woocommerce-product-excel-importer-bulk-editing-pro/'>
							<i class='fa fa-tag' ></i> <?php esc_html_e("Get it here","webd_bulk_edit");?>	
						</a>
						<a href='https://www.youtube.com/watch?v=wWrKy64LIGw&rel=0' target='_blank'><?php esc_html_e( "Watch on Youtube", "webd_bulk_edit" ); ?></a><br/><br/>
					</p>
		</div>			
	</div>
	

	
	
	<?php
	webd_woocommerce_product_excel_importer_bulk_edit_form_footer();
}

function webd_woocommerce_product_excel_importer_bulk_edit_form_header() {
?>
	<h2><img src='<?php echo plugins_url( 'images/webd_woocommerce_product_excel_importer_bulk_edit.png', __FILE__ ); ?>' style='width:100%' />
<?php
}

function webd_woocommerce_product_excel_importer_bulk_edit_form_footer() {
?>
	<hr>
	<?php webd_Rating(); ?>
	
			<a target='_blank' class='web_logo' href='https://extend-wp.com/'>
			
				<img  src='<?php echo plugins_url( 'images/extendwp.png', __FILE__ ); ?>' alt='Get more plugins by extendWP' title='Get more plugins by extendWP' />
			</a>
			
			<div id='excel_bulk_wrap_free_instructionsVideo' class='rightToLeft'><iframe width="560" height="315" src="https://www.youtube.com/embed/p8PPBUsHA_I?rel=0" frameborder="0" allowfullscreen></iframe>
			</div>			
<?php
}

function webd_Rating(){
	?>
	<div class='excel_bulk_wrap_rating' >
		<p>
			<strong><?php esc_html_e( "You like this plugin? ", 'webd_woocommerce_bulk_edit' ); ?></strong><i class='fa fa-2x fa-smile-o' ></i><br/> <?php esc_html_e( "Then please give us ", 'webd_woocommerce_bulk_edit' ); ?> 
			<a target='_blank' href='https://wordpress.org/support/plugin/webd-woocommerce-product-excel-importer-bulk-edit/reviews/#new-post'>
					<span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span>
			</a>
		</p>
	</div> 	
	<?php	
}

// deactivation survey 

include( plugin_dir_path(__FILE__) .'/lib/codecabin/plugin-deactivation-survey/deactivate-feedback-form.php');	
add_filter('codecabin_deactivate_feedback_form_plugins', function($plugins) {

	$plugins[] = (object)array(
		'slug'		=> 'webd-woocommerce-product-excel-importer-bulk-edit',
		'version'	=> '4.6'
	);

	return $plugins;

});

// Email notification form
	
//register_activation_hook( __FILE__, 'webd_bulk_notification_hook' );
register_deactivation_hook( __FILE__, 'webd_bulk_deact_hook' );
function webd_bulk_deact_hook() {
    delete_transient( 'webd_bulk_notified' );
}
/*
function webd_bulk_notification_hook() {
    
	set_transient( 'webd_bulk_notification', true );
}
*/
add_action( 'admin_notices', 'webd_bulk_notification' );

function webd_bulk_notification(){

	$screen = get_current_screen();
	//var_dump( $screen );
	if ( 'toplevel_page_webd-woocommerce-product-excel-importer-bulk-edit'  !== $screen->base )
	return;

    /* Check transient, if available display notice */
    if( get_transient( 'webd_bulk_notification' )   ){
        ?>
        <div class="updated notice webd_bulk_notification">
			<a href="#" class='dismiss' style='float:right;padding:4px' >close</a>

			<h3><i><?php esc_html_e( "Add your Email below & win ", 'webd_bulk_edit' ); ?><strong>10% off </strong><?php esc_html_e( " in our pro plugins at", 'webd_bulk_edit' ); ?> <a href='https://extend-wp.com' target='_blank' >extend-wp.com!</a></i></h3>
			<form method='post' id='webd_bulk_signup'>
			<p>
				<input required type='email' name='woopei_email' />
				<input required type='hidden' name='product' value='952' />
				<input type='submit' class='button button-primary' name='submit' value='<?php esc_html_e("Sign up", "webd_bulk_edit" ); ?>' />
			</p>
			</form>
        </div>
        <?php

    }
}
add_action( 'wp_ajax_nopriv_webd_bulk_push_not', 'webd_bulk_push_not'  );
add_action( 'wp_ajax_webd_bulk_push_not', 'webd_bulk_push_not' );

function webd_bulk_push_not(){
	
	delete_transient( 'webd_bulk_notification' );
	set_transient( 'webd_bulk_notified', true );		
}

// HPOS compatibility declaration

add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

?>