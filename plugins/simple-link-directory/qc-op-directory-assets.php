<?php

defined('ABSPATH') or die("No direct script access!");

add_action('init', 'qcopd_load_resources');
if ( ! function_exists( 'qcopd_load_resources' ) ) {
	function qcopd_load_resources(){
		add_action('wp_enqueue_scripts', 'qcopd_load_all_scripts');
		add_action( 'admin_enqueue_scripts', 'qcsld_admin_enqueue' );
		add_action( 'wp_enqueue_scripts', 'sld_packery_adding_scripts', 100 ); 
	}
}



if ( ! function_exists( 'qcopd_load_all_scripts' ) ) {
	function qcopd_load_all_scripts(){

		//Scripts
		wp_enqueue_script( 'jquery', 'jquery');
	   // wp_enqueue_script( 'qcopd-grid-packery', QCOPD_ASSETS_URL . '/js/packery.pkgd.js', array('jquery'),true,true);
		wp_register_script( 'qcopd-images-loaded', QCOPD_ASSETS_URL . '/js/imagesloaded.js', array('jquery'));
		wp_register_script( 'qcopd-custom-script', QCOPD_ASSETS_URL . '/js/directory-script.js', array('jquery', 'qcopd-images-loaded'));
		wp_register_style( 'qcsld-fa-css', QCOPD_ASSETS_URL . '/css/font-awesome.min.css' );
		//StyleSheets
		wp_register_style( 'qcopd-custom-css', QCOPD_ASSETS_URL . '/css/directory-style.css');
		wp_register_style( 'qcopd-custom-rwd-css', QCOPD_ASSETS_URL . '/css/directory-style-rwd.css');
		//default template css
		wp_register_style( 'sld-css-simple', OCOPD_TPL_URL . "/simple/template.css");
		//style 1 css
		wp_register_style('sld-css-style-1', OCOPD_TPL_URL . "/style-1/template.css" );
		//style-2 css
		wp_register_style('sld-css-style-2', OCOPD_TPL_URL . "/style-2/template.css" );
		//style-3 css
		wp_register_style('sld-css-style-3', OCOPD_TPL_URL . "/style-3/template.css" );
		//style 4 css
		wp_register_style('sld-css-style-4', OCOPD_TPL_URL . "/style-4/template.css" );
		//style 5 css
		wp_register_style('sld-css-style-5', OCOPD_TPL_URL . "/style-5/template.css" );
		//style 6 css
		wp_register_style('sld-css-style-16', OCOPD_TPL_URL . "/style-16/template.css" );

	}
}

if ( ! function_exists( 'qcsld_admin_enqueue' ) ) {
	function qcsld_admin_enqueue(){
		global $post_type;
		
		wp_register_style( 'qcopd-custom-admin-css', QCOPD_ASSETS_URL . '/css/admin-style.css');
		wp_enqueue_style( 'qcopd-custom-admin-css' );
		wp_register_style( 'jq-slick.css-css', QCOPD_ASSETS_URL . '/css/slick.css');
		wp_enqueue_style( 'jq-slick.css-css' );
		wp_register_style( 'jq-slick-theme-css', QCOPD_ASSETS_URL . '/css/slick-theme.css', array(), '1.0.1');
		wp_enqueue_style( 'jq-slick-theme-css' );
		wp_enqueue_script( 'jq-slick.min-js', QCOPD_ASSETS_URL . '/js/slick.min.js', array('jquery'));
		wp_register_script( 'sld-admin-common-script', QCOPD_ASSETS_URL . '/js/qcopd-admin-common.js', array('jquery'));
		wp_enqueue_script( 'sld-admin-common-script' );
		
		$scrolljs = "jQuery(document).ready(function($){
			$('.qc-up-pro-link').parent('a').on('click', function(e){
				e.preventDefault();
				var link = $(this).attr('href');
				window.open(link, '_blank');
			});
		});";
		wp_add_inline_script( 'sld-admin-common-script', ($scrolljs) );
		
		wp_register_script( 'sld-admin-trackoutbound-script', QCOPD_ASSETS_URL . '/js/qcopd-track-outbound.js', array('jquery'));
		
		
		
		$css = '';
	    if ($post_type == 'sld') {
	        $css .= "#edit-slug-box {display:none;}#qcopd_entry_time, #qcopd_timelaps { display: none; }";
	    }
	    
	    $css .= '.button.qcsld-promo-link {
	      color: #ff0000;
	      font-weight: normal;
	      margin-left: 0;
	      margin-top: 1px !important;
	    }
	    .clear{ clear: both; }';
		
		$css .= ".wpb-form-active .wpb-goodbye-form-bg{background:rgba(0,0,0,.5);position:fixed;top:0;left:0;width:100%;height:100%}.wpb-goodbye-form-wrapper{position:relative;z-index:999;display:none}.wpb-form-active .wpb-goodbye-form-wrapper{display:block}.wpb-goodbye-form{display:none}.wpb-form-active .wpb-goodbye-form{position:fixed;max-width:400px;background:#fff;white-space:normal;z-index:99;top:50%;left:50%;transform:translate(-50%,-50%);border-radius:5px}.wpb-goodbye-form-head{background:#7a00aa;color:#fff;padding:8px 18px;text-align:center;border-radius:5px 5px 0 0}.wpb-goodbye-form-body{padding:8px 18px;color:#444}.deactivating-spinner{display:none}.deactivating-spinner .spinner{float:none;margin:4px 4px 0 18px;vertical-align:bottom;visibility:visible}.wpb-goodbye-form-footer{padding:8px 18px}";

		wp_add_inline_style( 'qcopd-custom-admin-css', $css );

	}
}

if ( ! function_exists( 'sld_packery_adding_scripts' ) ) {
	function sld_packery_adding_scripts() {

		wp_register_script('sld-packery-script', QCOPD_ASSETS_URL . '/js/packery.pkgd.js','','1.1', true);

	}
}



