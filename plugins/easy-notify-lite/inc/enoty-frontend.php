<?php


/*-------------------------------------------------------------------------------*/
/* Get the default Notify
/*-------------------------------------------------------------------------------*/
function easynotify_init() {
	
	$defaultnoty = enoty_get_option( 'easynotify_defaultnotify' );
	
	if ( enoty_get_option( 'easynotify_disen_loggedusr' ) ) {
		
		if ( !is_user_logged_in() ) {
			
			if ( $defaultnoty != 'disabled' && !isset( $_COOKIE["notify-".$defaultnoty.""] ) ) {
				add_filter( 'the_content', 'easynotify_strip_default_noty' );
				add_filter( 'wp_footer', 'generate_global_notify' );
			}
			
		} else {
			add_filter( 'the_content', 'easynotify_not_logged_in' );
		}
		
	} else {
		
		if ( $defaultnoty != 'disabled' && !isset( $_COOKIE["notify-".$defaultnoty.""] ) ) {
			add_filter( 'the_content', 'easynotify_strip_default_noty' );
			add_filter( 'wp_footer', 'generate_global_notify' );
		}
	}

}


function easynotify_not_logged_in( $content ) {
	
	return easynotify_strip_shortcode( 'easy-notify', $content );
	
}

/*-------------------------------------------------------------------------------*/
/* Strip Notify from Post / Page
/*-------------------------------------------------------------------------------*/
function easynotify_strip_default_noty( $content ) {
	
 	$new_content = $content; $ishome = enoty_get_option( 'easynotify_swhome' ); $ispage	= enoty_get_option( 'easynotify_swpage' ); $ispost	= enoty_get_option( 'easynotify_swpost' ); $isctach = enoty_get_option( 'easynotify_swcatarch' ); $fromcp = enoty_get_option( 'easynotify_defaultnotify' );
	
	if ( !isset( $_COOKIE["notify-".$fromcp.""] ) || get_post_meta( $fromcp, 'enoty_cp_cookies', true ) == '-1' ) {
	
		if( $ispage && is_page() ) {
			$new_content = easynotify_strip_shortcode('easy-notify', $content);
			}
			
		elseif( $ispost && is_single() ) {
			$new_content = easynotify_strip_shortcode('easy-notify', $content);
			}
			
		elseif( ( $ishome && is_home() ) || ( $ishome && is_front_page() ) ) {
			$new_content = easynotify_strip_shortcode('easy-notify', $content);
			}	
				
		elseif( ( $isctach && is_category() ) || ( $isctach && is_archive() ) ) {
			$new_content = easynotify_strip_shortcode('easy-notify', $content);
			}
			
		}
		return $new_content;
		
	}

/*-------------------------------------------------------------------------------*/
/* Generate & Launch global Notify
/*-------------------------------------------------------------------------------*/
function generate_global_notify(){
	
 	$ishome	= enoty_get_option( 'easynotify_swhome' ); $ispage	= enoty_get_option( 'easynotify_swpage' ); $ispost	= enoty_get_option( 'easynotify_swpost' ); $isctach = enoty_get_option( 'easynotify_swcatarch' ); $fromcp = enoty_get_option( 'easynotify_defaultnotify' );
	
	if ( !isset( $_COOKIE["notify-".$fromcp.""] ) || get_post_meta( $fromcp, 'enoty_cp_cookies', true ) == '-1' ) {
	
		if( $ispage && is_page() && !is_front_page() && !is_home() ) {
			echo 'a';
			echo do_shortcode( '[easy-notify id="'.$fromcp.'"]' );
			}
			
		elseif( $ispost && is_single() && !is_front_page() && !is_home() ) {
			echo do_shortcode( '[easy-notify id="'.$fromcp.'"]' );
			}
			
		elseif( ( $ishome && is_home() ) || ( $ishome && is_front_page() ) ) {
			echo do_shortcode( '[easy-notify id="'.$fromcp.'"]' );
			}
			
		elseif( ( $isctach && is_category() ) || ( $isctach && is_archive() ) ) {
			echo do_shortcode( '[easy-notify id="'.$fromcp.'"]' );
			}	
				
		}
}	

function easynotify_render_custom_css() {
	
	if ( enoty_get_option( 'easynotify_custom_css' ) ) {
	
		$final_css = enoty_get_option( 'easynotify_custom_css' );
		$allowed_tags = wp_kses_allowed_html( 'post' );
		$inline_css = wp_kses( stripslashes_deep( easynotify_css_compress( $final_css ) ), $allowed_tags );
		
		wp_add_inline_style( 'enoty-frontend-style', htmlspecialchars_decode( $inline_css, ENT_QUOTES ) );

	}
	
}
	
function easynotify_remove_shortcode_from_index( $content ) {
	
	if ( is_home() || is_front_page() || is_category() || is_archive() ) {
		
		$content = easynotify_strip_shortcode( 'easy-notify', $content );
		
	}
	
	return $content;
  
 }
add_filter( 'the_content', 'easynotify_remove_shortcode_from_index' );