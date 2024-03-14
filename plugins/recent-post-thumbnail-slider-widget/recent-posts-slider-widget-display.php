<?php
function add_recent_posts_slider_widget_stylesheet() {

			if( !is_admin() ){						
			wp_enqueue_style( '', plugins_url('/css/sliderman.css', __FILE__) );
	   		}
}

add_action('wp_print_styles', 'add_recent_posts_slider_widget_stylesheet');


function add_recent_posts_slider_widget_scripts(){
			// Loads our scripts, only on the front end of the site
			if( !is_admin() ){				
				// Load javascript
				$load_js_in_footer = '';
				wp_enqueue_script( '', plugins_url('/js/sliderman.1.1.1.js', __FILE__), array('jquery'), FALSE, $load_js_in_footer );				
			}
		}

add_action('wp_print_scripts', 'add_recent_posts_slider_widget_scripts');
?>