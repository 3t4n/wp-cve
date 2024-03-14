<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Xoo_Wl_Frontend{

	protected static $_instance = null;

	public static function get_instance(){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct(){
		$this->hooks();
	}

	public function hooks(){
		add_action( 'wp_enqueue_scripts' ,array( $this,'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts' , array( $this,'enqueue_scripts' ), 5 );
		add_action( 'wp_footer', array( $this, 'popup_markup' ) );
		add_action( 'woocommerce_before_single_product', array( $this, 'add_hook_for_waitlist_on_product_page' ) );
		if( xoo_wl_helper()->get_general_option( 'm-en-shop' ) === "yes" ){
			add_action( 'woocommerce_after_shop_loop_item', array( $this, 'show_waitlist_on_shop_page' ), 15 );
		}
	}


	public function popup_markup(){
		xoo_wl_helper()->get_template( 'xoo-wl-popup.php' );
	}


	//Enqueue stylesheets
	public function enqueue_styles(){
		wp_enqueue_style( 'xoo-wl-style', XOO_WL_URL.'/assets/css/xoo-wl-style.css', array(), XOO_WL_VERSION );
		wp_enqueue_style('xoo-wl-fonts',XOO_WL_URL.'/assets/css/xoo-wl-fonts.css',array(),XOO_WL_VERSION);

		$btn_bg_color 		= xoo_wl_helper()->get_style_option( 'btn-bgcolor' );
		$btn_txt_color 		= xoo_wl_helper()->get_style_option( 'btn-txtcolor' );
		$btn_form_width 	= xoo_wl_helper()->get_style_option( 'btn-form-width' );
		$btn_open_width 	= xoo_wl_helper()->get_style_option( 'btn-open-width' );
		$popup_width 		= xoo_wl_helper()->get_style_option( 'popup-width' );
		$popup_height 		= xoo_wl_helper()->get_style_option( 'popup-height' );
		$sidebar_img  		= xoo_wl_helper()->get_style_option( 'popup-sidebar-img') ;
		$sidebar_width 		= xoo_wl_helper()->get_style_option( 'popup-sidebar-width' );
		$sidebar_pos 		= xoo_wl_helper()->get_style_option( 'popup-sidebar-pos' );
		$popup_pos 			= xoo_wl_helper()->get_style_option( 'popup-pos' );


		$inline_style = "
			button.xoo-wl-action-btn{
				background-color: {$btn_bg_color};
				color: {$btn_txt_color};
			}
			button.xoo-wl-submit-btn{
				max-width: {$btn_form_width}px;
			}
			button.xoo-wl-open-form-btn{
				max-width: {$btn_open_width}px;
			}
			.xoo-wl-inmodal{
				max-width: {$popup_width}px;
				max-height: {$popup_height}px;
			}
			.xoo-wl-sidebar{
    			background-image: url({$sidebar_img});
    			min-width: {$sidebar_width}%;
    		}
		";

		if($sidebar_pos == 'right'){
			$inline_style .= "
				.xoo-wl-wrap{
					direction: rtl;
				}
				.xoo-wl-wrap > div{
					direction: ltr;
				}

			";
		}



		if($popup_pos  === 'middle'){
			$inline_style .= "
				.xoo-wl-modal:before {
				    content: '';
				    display: inline-block;
				    height: 100%;
				    vertical-align: middle;
				    margin-right: -0.25em;
				}
			";
		}
		else{
			$inline_style .= "
				.xoo-wl-inmodal{
					margin-top: 40px;
				}

			";
		}

		wp_add_inline_style('xoo-wl-style', $inline_style );
	}

	//Enqueue javascript
	public function enqueue_scripts(){

		//Enqueue Form field framework scripts
		xoo_wl()->aff->enqueue_scripts();

		wp_enqueue_script( 'xoo-wl-js', XOO_WL_URL.'/assets/js/xoo-wl-js.js', array('jquery'), XOO_WL_VERSION, true ); // Main JS
		wp_localize_script('xoo-wl-js','xoo_wl_localize',array(
			'adminurl'  			=> admin_url().'admin-ajax.php',
			'notices' 				=> array(
				'empty_id' 	=> xoo_wl_add_notice( __( 'Product ID not found, please contact support.', 'waitlist-woocommerce' ), 'error' ),
				'empty_email' 	=> xoo_wl_add_notice( __( 'Email address cannot be empty.', 'waitlist-woocommerce' ), 'error' ),
			),
			'showOnBackorders' 	=> xoo_wl_helper()->get_general_option( 'm-en-bod' )
		));
	}


	public function add_hook_for_waitlist_on_product_page(){

		global $product;

		add_action( 'woocommerce_' . $product->get_type() . '_add_to_cart', array( $this, 'get_waitlist_markup_for_product_page' ), 35 );
		
	}


	public function get_waitlist_markup_for_product_page(){

		global $product;

		echo xoo_wl_form_markup( $product->get_id(), xoo_wl_helper()->get_general_option('m-form-type')  );

	}

	public function show_waitlist_on_shop_page(){
		
		global $product;

		echo xoo_wl_form_markup( $product->get_id(), 'popup' );
	}

}

function xoo_wl_frontend(){
	return Xoo_Wl_Frontend::get_instance();
}
xoo_wl_frontend();
