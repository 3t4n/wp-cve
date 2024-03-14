<?php
/**
 * Plugin Name: Before After Image Comparison Slider for WPBakery Page Builder
 * Description: Before After Image Comparison Slider for WPBakery Page Builder, You can compare two images with this plugin
 * Author: Plugin Devs
 * Author URI: https://plugin-devs.com/
 * Plugin URI: https://plugin-devs.com/product/before-after-slider-for-wpbakery/
 * Version: 2.0.1
 * Text Domain: before-after-image-comparison-slider-for-visual-composer
 * Domain Path: languages
 */


// don't load directly
if (!defined('ABSPATH')){
	die('-1');
}

/**
 * 
 */
class WB_VC_BAIC {
	
	function __construct() {
		$this->declare_constants();

		//Plugn Review Related Actions
		add_filter( 'plugin_action_links', [ $this, 'plugin_action_links'], 10, 3 );
		add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta'], 10, 3 );
		add_action( 'admin_notices', [ $this, 'leave_a_review' ] );

		//Enqueue Admin Scripts
		add_action( 'admin_enqueue_scripts',  [ $this, 'admin_scripts_styles' ] );

		// Wordpress Ajax
		add_action( 'wp_ajax_wb_vc_baic_review_transient', [$this, 'wb_vc_baic_review_transient'] );

		//Load Plugin Files
		add_action( 'vc_after_init', array( $this, 'load_files' ), 10 );
		// Custom Menu Order
		add_filter( 'custom_menu_order', array($this, 'wbvcbaic_order_submenu') );
	}

	function declare_constants(){
		define( 'WB_VC_BAIC_PATH', plugin_dir_path( __FILE__ ) );
		define( 'WB_VC_BAIC_URL', plugin_dir_url( __FILE__ ) ) ;
		define( 'WB_VC_BAIC_PRO_URL', 'https://plugin-devs.com/product/before-after-slider-for-wpbakery/' ) ;
	}

	public function plugin_action_links( $links, $file, $data ){
		if ( $file === 'before-after-image-comparison-slider-for-visual-composer/before-after-image-comparison-slider-for-visual-composer.php' ) {
			$new_links = array(
					'review' => '<a href="https://wordpress.org/support/plugin/before-after-image-comparison-slider-for-visual-composer/reviews/?rate=5#new-post" target="_blank" class="wb-vc-baic-color-red" ><b class="wb-vc-baic-extra-bold wb-vc-baic-font-15" >Leave a Review</b></a>',
					'upgrade_to_pro' => '<a href="https://plugin-devs.com/product/before-after-slider-for-wpbakery/" target="_blank" class="wb-vc-baic-upgrade-pro" ><b class="wb-vc-baic-extra-bold wb-vc-baic-font-16" >Upgrade to Pro</b></a>',
					);
			
			$links = array_merge( $links, $new_links );
		}
		
		return $links;
	}

	public function plugin_row_meta( $links, $file, $data ){
		if ( $file === 'before-after-image-comparison-slider-for-visual-composer/before-after-image-comparison-slider-for-visual-composer.php' ) {
			$new_links = array(
					'review' => '<a href="https://wordpress.org/support/plugin/before-after-image-comparison-slider-for-visual-composer/reviews/?rate=5#new-post" target="_blank" class="wb-vc-baic-color-red"><strong class="wb-vc-baic-extra-bold wb-vc-baic-font-15">Leave a Review</strong></a>',
					'upgrade_to_pro' => '<a href="https://plugin-devs.com/product/before-after-slider-for-wpbakery/" target="_blank" class="wb-vc-baic-upgrade-pro" ><b class="wb-vc-baic-extra-bold wb-vc-baic-font-16" >Upgrade to Pro</b></a>',
					);
			
			$links = array_merge( $links, $new_links );
		}
		
		return $links;
	}

	/**
	 * Admin notice
	 *
	 * Request for leave a review
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function leave_a_review() {
		$wb_vc_baic_review_transient = get_transient('wb_vc_baic_review_transient');
		if( $wb_vc_baic_review_transient  != 'reviewed' ){
	?>
			<div class="notice notice-info wb-vcbaics-review-notice wb-pos-relative">
				<div class="notice-dismiss"></div>
				<p class="wb-vc-baic-font-16">Can you please let us know your thoughts about <strong>Before After Image Comparison Slider for WPBakery Page Builder</strong>, so that we can improve it</p>
				<p>
					<a class="wb-vc-baic-color-red wb-vc-baic-extra-bold wb-vc-baic-font-16 text-decoration-none" target="_blank" href="https://wordpress.org/support/plugin/before-after-image-comparison-slider-for-visual-composer/reviews/?rate=5#new-post">Leave a Review</a>
					<span class="wb-vc-baic-color-blue wb-vc-baic-font-16 wb-vc-baic-mx-10">|</span>
					<a class="wb-vc-baic-already-reviewed wb-vc-baic-bold text-decoration-none" href="#">Already Reviewed</a>
					<span class="wb-vc-baic-color-blue wb-vc-baic-font-16 wb-vc-baic-mx-10">|</span>
					<a class="wb-vc-baic-upgrade-pro wb-vc-baic-extra-bold wb-vc-baic-font-16 text-decoration-none"  href="https://plugin-devs.com/product/before-after-slider-for-wpbakery/" target="_blank" >Upgrade to Pro</a>
				</p>
			</div>
	<?php
		}
	}

	function load_files(){
		require_once( WB_VC_BAIC_PATH . '/admin/check-compatibility.php' );

		require_once( WB_VC_BAIC_PATH . '/admin/admin-pages.php' );
		require_once( WB_VC_BAIC_PATH . '/support-page/class-support-page.php' );
		
		if ( defined( 'WPB_VC_VERSION' ) ) {
			require_once( WB_VC_BAIC_PATH . '/admin/main.php' );
		}
		
		if( class_exists('WB_VC_BAIC_Check_Compatibility') ){
			new WB_VC_BAIC_Check_Compatibility();
		}

		if( class_exists('WB_VC_BAIC_Main') ){
			new WB_VC_BAIC_Main();
		}
	}

	/**
	 * Enqueue Admin Styles and Scripts
	 * 
	 * Load Admin stylesheets and scripts
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_scripts_styles(){

		wp_enqueue_style( 'wb-vc-baic-admin-style', WB_VC_BAIC_URL . '/assets/css/admin.css', array(), '1.0.0', 'all' );
		
		wp_enqueue_script( 'wb-vc-baic-admin-script', WB_VC_BAIC_URL . '/assets/js/admin.js', array('jquery'), '1.0.0', 'all' );

		wp_localize_script( 'wb-vc-baic-admin-script', 'wb_vc_baic_ajax_object',
            array(
            	'ajax_url' => admin_url( 'admin-ajax.php' ),
            ) 
        );
	}

	/**
	 * Submenu filter function. Tested with Wordpress 4.1.1
	 * Sort and order submenu positions to match your custom order.
	 *
	 */
	public function wbvcbaic_order_submenu( $menu_ord ) {

	  global $submenu;

	  // Enable the next line to see a specific menu and it's order positions
	  //echo '<pre>'; print_r( $submenu['wbvc-before-after-slider'] ); echo '</pre>'; exit();

	  $arr = array();

	  $arr[] = $submenu['wbvc-before-after-slider'][1];
	  $arr[] = $submenu['wbvc-before-after-slider'][2];
	  $arr[] = $submenu['wbvc-before-after-slider'][5];
	  $arr[] = $submenu['wbvc-before-after-slider'][4];

	  $submenu['wbvc-before-after-slider'] = $arr;

	  return $menu_ord;

	}

	public function wb_vc_baic_review_transient()
	{
		if ( is_admin() ) {
			$wb_vc_baic_review_transient = get_transient('wb_vc_baic_review_transient');
			if( $wb_vc_baic_review_transient  != 'reviewed' ){
				if( set_transient('wb_vc_baic_review_transient', 'reviewed', 1*YEAR_IN_SECONDS ) ){
					echo 'already_reviewed';
				}
			}
			wp_die();
		}
	}
}


new WB_VC_BAIC();

add_action( 'init', 'wb_vc_baic_load_textdomain' );
  
/**
 * Load plugin textdomain.
 */
function wb_vc_baic_load_textdomain() {
  load_plugin_textdomain( 'before-after-image-comparison-slider-for-visual-composer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

require_once( WB_VC_BAIC_PATH . '/class-plugin-deactivate-feedback.php' );

$wb_vcbaic_feedback = new Wp_vcbaic_Usage_Feedback(
	__FILE__,
	'webbuilders03@gmail.com',
	false,
	true

);

function wbvcebaic_pro_btn_settings_field( $settings, $value ) {
	ob_start();
 ?>
	<a class="vc_general vc_ui-button vc_ui-button-action vc_ui-button-shape-rounded vc_ui-button-fw" style="background: #ca4a1f; padding: 8px 10px;" target="_blank" href="<?php echo WB_VC_BAIC_PRO_URL; ?>"><?php echo $settings['value']; ?></a>
 <?php
 return ob_get_clean();
}