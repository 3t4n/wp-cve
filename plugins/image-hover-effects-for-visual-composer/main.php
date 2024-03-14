<?php 

class VC_IHEC
{
	function __construct()
	{
		add_action( 'vc_before_init', array($this, 'vc_image_hover_effects' ));	
		add_action( 'wp_enqueue_scripts', array($this, 'adding_front_scripts') );
		add_shortcode( 'ihe-vc', array( $this, 'render_ihe_shortcode' ) );
		add_action( 'init', array( $this, 'check_if_vc_is_install' ) );
		remove_filter( 'the_content', 'wpautop' );
	}


	function adding_front_scripts () {
		// wp_enqueue_script( 'front-js-na', plugins_url( 'js/script.js' , __FILE__ ), array('jquery', 'jquery-ui-core'));
	}

		
	function vc_image_hover_effects() {
		include 'includes/hoverSetting.php';

	    $addon_settings = array(

	    	"hoverSetting" => array(
				"name" => "VC Image Hover Effects",
		        "base" => "ihe-vc",
		        "class" => "",
		        "description" => __('Image Hover Effects'),
		        "category" => __( 'VC IHE', 'my-text-domain' ),
				"params" => $hover_params,
				"icon" => plugin_dir_url( __FILE__ ).'/images/ihe.png'
			),
	);

		foreach ($addon_settings as $data) {
			vc_map($data);
		}
	}

	function render_ihe_shortcode($attrs, $content = null) {
		extract( shortcode_atts( array(
			'hover_effect' 			=> 		'NoEffect',
			'image_id'				=>		'',
			'title'					=>		'',
			'desc'					=>		'',
			'caption_url'			=>		'',
			'caption_url_target'	=>		'',
			'caption_bg'			=>		'',
			'title_size'			=>		'',
			'desc_size'				=>		'',
			'title_clr'				=>		'',
			'title_bg'				=>		'',
			'desc_clr'				=>		'',
			'border_width'			=>		'0',
			'border_style'			=>		'solid',
			'border_color'			=>		'',
			'style' 				=> 		'ihover',
			'caption' 				=> 		'slide-left-to-right',
		), $attrs ) );
		wp_enqueue_style( 'image-hover-effects-css', plugins_url( 'css/ihover.css' , __FILE__ ));
		wp_enqueue_style( 'image-caption-hover-css', plugins_url( 'css/caption.css' , __FILE__ ));
		if ($image_id != '') {
			$image_url = wp_get_attachment_url( $image_id );		
		}
		$content = wpb_js_remove_wpautop($content, true);
		ob_start();		
		include 'render/hover.php';
		return ob_get_clean();
	}

	function check_if_vc_is_install(){
        if ( ! defined( 'WPB_VC_VERSION' ) ) {
            // Display notice that Visual Compser is required
            add_action('admin_notices', array( $this, 'showVcVersionNotice' ));
            return;
        }			
	}

	function showVcVersionNotice(){
	    ?>
	    <div class="notice notice-warning is-dismissible">
	        <p>Please install <a href="https://1.envato.market/A1QAx">WPBakery Page Builder</a> to use Image Hover Effects.</p>
	    </div>
	    <?php
	}

}


$N_object = new VC_IHEC;
 ?>