<?php 

add_action('wp_print_scripts', 'wat_add_script_fn');
function wat_add_script_fn(){


	wp_enqueue_style('wat_bootsrap_css', plugins_url('/inc/assets/css/boot-cont.css', __FILE__ ) ) ;
	#wp_enqueue_style('wfd_fx-min.css', 'http://static.mfbcdn.net/styles/fx-min.css' ) ;
	wp_enqueue_style('watawesome.min.css', plugins_url('/inc/fa/css/font-awesome.min.css', __FILE__ ) ) ;

	if(is_admin()){	
	
		wp_enqueue_script('wat_jqujquery.qtip.js', plugins_url('/inc/qtip/jquery.qtip.js', __FILE__ ), array('jquery'), '1.0' ) ;
		wp_enqueue_style('wat_jqujquery.qtip.css', plugins_url('/inc/qtip/jquery.qtip.css', __FILE__ ) ) ;
	
		wp_enqueue_media();
		wp_enqueue_script('wat_admi11n_js', plugins_url('/js/admin.js', __FILE__ ), array('jquery'  ), '1.0' ) ;
		wp_enqueue_style('wat_admin_css', plugins_url('/css/admin.css', __FILE__ ) ) ;	
	  }else{
    
 	
		
	  }
}
?>