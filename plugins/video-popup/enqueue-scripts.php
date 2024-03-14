<?php

defined( 'ABSPATH' ) or die(':)');


function video_popup_include_css_js() {
	//wp_enqueue_style( 'video_popup_icomoon', plugins_url( '/css/icomoon/close-button-icon.css', __FILE__ ), array(), null, "all");
	wp_enqueue_style( 'video_popup_close_icon', plugins_url( '/css/vp-close-icon/close-button-icon.css', __FILE__ ), array(), time(), "all");
	wp_enqueue_style( 'oba_youtubepopup_css', plugins_url( '/css/YouTubePopUp.css', __FILE__ ), array(), time(), "all");
	wp_enqueue_script( 'oba_youtubepopup_plugin', plugins_url( '/js/YouTubePopUp.jquery.js', __FILE__ ), array('jquery'), time(), false);
	wp_enqueue_script( 'oba_youtubepopup_activate', plugins_url( '/js/YouTubePopUp.js', __FILE__ ), array('jquery'), time(), false);
}
add_action( 'wp_enqueue_scripts', 'video_popup_include_css_js' );


function video_popup_vars_script(){
	if( get_option('vp_gs_op_remove_boder') ){
		$r_border = 'true';
	}else{
		$r_border = null;
	}
	?>
		<script type='text/javascript'>
			var video_popup_translation_vars = {
				'soundcloud_url': '<?php echo home_url("/?vp_soundcloud="); ?>',
    			'o_v_shortcode': '[video_popup url="" text="" title="" auto="" n="" p="" wrap="" rv="" w="" h="" co="" dc="" di="" img="" iv=""]',
    			'shortcode_usage': '<?php echo admin_url('/admin.php?page=video_popup_shortcode'); ?>',
    			'gen_settings': '<?php echo admin_url('/admin.php?page=video_popup_general_settings'); ?>',
    			'on_pageload': '<?php echo admin_url('/admin.php?page=video_popup_on_pageload'); ?>',
    			'unprm_r_border': '<?php echo $r_border; ?>'
			};
		</script>
	<?php
}
add_action('admin_head', 'video_popup_vars_script');


function video_popup_unprm_vars_script(){
	if( get_option('vp_gs_op_remove_boder') ){
		$r_border = 'true';
	}else{
		$r_border = null;
	}
	?>
		<script type='text/javascript'>
			var video_popup_unprm_general_settings = {
    			'unprm_r_border': '<?php echo $r_border; ?>'
			};
		</script>
	<?php
}
add_action('wp_head', 'video_popup_unprm_vars_script');