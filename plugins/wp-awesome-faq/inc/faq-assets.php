<?php

/*
* Load Script When adding new post
*/
add_action( 'admin_enqueue_scripts', 'jltmaf_load_admin_scripts' );
function jltmaf_load_admin_scripts() {
	global $typenow;
	if( $typenow == 'faq' ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style('dashicons');
	}

	// Scripts
	wp_enqueue_style( 'fonticonpicker', MAF_URL . '/assets/fonticonpicker/css/base/jquery.fonticonpicker.min.css', false, MAF_VERSION );		
	wp_enqueue_style( 'fonticonpicker-grey', MAF_URL . '/assets/fonticonpicker/css/themes/grey-theme/jquery.fonticonpicker.grey.min.css', false, MAF_VERSION );
	wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css' );	

	wp_enqueue_script( 'fonticonpicker', MAF_URL . '/assets/fonticonpicker/js/jquery.fonticonpicker.min.js', array( 'jquery'), MAF_VERSION, true );	
	wp_enqueue_script( 'master-accordion-admin', MAF_URL . '/assets/js/proscript.js', array('jquery', 'wp-color-picker'), MAF_VERSION, true );

	$jltmaf_upgrade_pro = '<div class="jltmaf-text-small" style="padding-top:20px;"> Upgrade to  <a href="' . jltmaf_accordion()->get_upgrade_url() . '">Pro Version</a> unlock this feature.</div>';
	$admin_localize_data = array(
		'upgrade_pro'   => $jltmaf_upgrade_pro
	);
	wp_localize_script( 'master-accordion-admin', 'jltmaf_admin_scripts', $admin_localize_data );

}


/*
 * Enqueue Bootstrap According JS and Styleseets
 */
add_action( 'wp_enqueue_scripts', 'jltmaf_frontend_scripts' );
function jltmaf_frontend_scripts() {
	wp_enqueue_style( 'master-accordion', MAF_URL . '/assets/css/master-accordion.css', array(), MAF_VERSION, 'all' );
	wp_enqueue_style( 'font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css' );		
	wp_enqueue_script( 'master-accordion', MAF_URL . '/assets/js/master-accordion.js', array('jquery'), MAF_VERSION, true );


	$jltmaf_post_close_icon = get_post_meta( get_the_ID(), 'close_icon', true );
	$jltmaf_options_close_icon = jltmaf_options('faq_close_icon', 'jltmaf_settings' );

	$jltmaf_post_open_icon = get_post_meta( get_the_ID(), 'open_icon', true );
	$jltmaf_options_open_icon = jltmaf_options('faq_open_icon', 'jltmaf_settings' );

	$localize_data = array(
		'close_icon'    => ($jltmaf_post_close_icon) ? $jltmaf_post_close_icon : $jltmaf_options_close_icon,
		'open_icon'     => ($jltmaf_post_open_icon) ? $jltmaf_post_open_icon : $jltmaf_options_open_icon
	);
	wp_localize_script( 'master-accordion', 'jltmaf_scripts', $localize_data );
}



add_action( 'admin_head', 'jltmaf_dashboard_icon' );
// Add FAQs icon in dashboard
function jltmaf_dashboard_icon(){ ?>
	<style>
		/*FAQs Dashboard Icons*/
		#adminmenu .menu-icon-faq div.wp-menu-image:before { content: "\f348"; }

		.jltmaf-pro-badge{
		  	position: absolute;
		  	z-index: 333;
		  	text-align: center;
		  	padding-left: 23%;
		  	font-size: 70px !important;
		  	padding-top: 10%;
		}
		.jltmaf-pro-badge:before{
			content: 'Pro';
			font-size: 90px;
			color: #fff;
			background: #000;
			padding: 20px;
			border-radius: 30px; 
			opacity: .6;
		}
		.top-badge{
		  	padding-left: 20%;
		  	padding-top: 0;
		}
		.jltmaf-disabled{
		  	pointer-events: none;
		  	opacity: 0.4;
		}
		.jltmaf-pro-feature{
			position: absolute;
			z-index: 555;
			text-align: center;
			font-size: 30px;
			opacity: 1;
			padding-left: 3%;
			font-size: 70px !important;
			padding-top: 10%;
		}
		.jltmaf-text-small{
			position: relative !important;
			font-size: 12px !important;
			font-style: italic !important;
			color: #000 !important;
			opacity: .8 !important;
		}
	</style>
	<?php
}