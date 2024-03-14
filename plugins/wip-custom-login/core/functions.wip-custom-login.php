<?php

/**
 * Wp in Progress
 * 
 * @package Wordpress
 * @theme Sueva
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * It is also available at this URL: http://www.gnu.org/licenses/gpl-3.0.txt
 */

/*-----------------------------------------------------------------------------------*/
/* SETTINGS */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('wip_custom_login_setting')) {

	function wip_custom_login_setting($id, $default = "" ) {
	
		$wip_custom_login_setting = get_option("wip_custom_login_settings");
		
		if(isset($wip_custom_login_setting[$id])):
		
			return $wip_custom_login_setting[$id];
		
		else:
		
			return $default;
		
		endif;
	
	}

}

/*-----------------------------------------------------------------------------------*/
/* JSON */
/*-----------------------------------------------------------------------------------*/  

if (!function_exists('wip_custom_login_jsonfile')) {
	
	function wip_custom_login_jsonfile( $name ) {
		
		global $pagenow;

		if ( $pagenow == 'admin.php' ) {

			$request = wp_remote_get( plugins_url('/core/admin/json/', dirname(__FILE__)) . 'googlefonts.json' );
			$response = wp_remote_retrieve_body( $request );
			$jsonfile = json_decode( $response, true );

			return $jsonfile;
		
		} else { 
		
			return false;
		
		}
		
	}
	
}

/*-----------------------------------------------------------------------------------*/
/* GOOGLE FONTS */
/*-----------------------------------------------------------------------------------*/  
 
if (!function_exists('wip_custom_login_get_font')) {
	
	function wip_custom_login_get_font( $name, $type) {

		global $pagenow;

		if ( $pagenow == 'admin.php' ) {
		
			$jsonfile = wip_custom_login_jsonfile("googlefonts.json");
			
			$fontsarray = $jsonfile['items'];
	
			if (!function_exists('wip_custom_login_variants')) {
	
				function wip_custom_login_variants( $array ) {
				  
					$search = array( 
					
						"regular" => "400", 
						"italic" => "400italic" 
					
					);
					
					foreach ( $search as $key => $val ) {
					
						if ( in_array( $key, $array)) {
							
							$array[ array_search( $key, $array )]= $val; 
						
						}
						
					}
				
					return $array;
				
				}
			
			}
			
			foreach ( $fontsarray as $font ) {
			
				$getfont[$font['family']] = implode( ",", wip_custom_login_variants( $font['variants']));
				$getlist[$font['family']] = $font['family'];
	
			}
	
			if ( $type == "getfont" ) :
			
				return $getfont[$name];
			
			else:
			
				return $getlist;
			
			endif;
	
		} else { 
		
			return false;
		
		}

	}

}

/*-----------------------------------------------------------------------------------*/
/* FONT LIST */
/*-----------------------------------------------------------------------------------*/  
 
if (!function_exists('wip_custom_login_fontlist')) {
	
	function wip_custom_login_fontlist() {

		global $pagenow;

		if ( $pagenow == 'admin.php' ) {
	
			$fontsarray = array (
				
				'Montserrat', 
				wip_custom_login_setting("wip_custom_login_font")
				
			);
			
			$fonts = array_unique($fontsarray); 
			
			foreach ( $fonts as $fontname ) {
				
				if ($fontname) { 
					
					$fontlist[] = str_replace(" ","+", $fontname) . ":" . wip_custom_login_get_font( $fontname, "getfont" ); 
				
				}
		
			}
	
			return implode( "|", $fontlist);
			
		} else { 
		
			return false;
		
		}

	}

}

/*-----------------------------------------------------------------------------------*/
/* RANDOM BANNER */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('wip_custom_login_random_banner')) {

	function wip_custom_login_random_banner() {
		
		$plugin1  = '<h1>'. __( 'WIP Custom Login.', 'wip-custom-login') . '</h1>';
		$plugin1 .= '<p>'. __( 'To enable all features, like the slideshow as background, please upgrade to the premium version.', 'wip-custom-login') . '</p>';
		$plugin1 .= '<div class="big-button">';		
		$plugin1 .= '<a href="'.esc_url( 'https://www.themeinprogress.com/c-login-free-custom-login-wordpress-plugin/?aff=panel').'" target="_blank">'.__( 'Upgrade to the premium version', 'wip-custom-login').'</a>';	
		$plugin1 .= '</div>';
		
		$plugin2  = '<h1>'. __( 'Internal Linking of Related Contents', 'wip-custom-login') . '</h1>';
		$plugin2 .= '<p>'. __( '<strong>Internal Linking of Related Contents</strong> WordPress plugin allow you to automatically insert related articles inside your WordPress posts.', 'wip-custom-login') . '</p>';
		$plugin2 .= '<div class="big-button">';		
		$plugin2 .= '<a href="'.esc_url( 'https://www.themeinprogress.com/internal-linking-of-related-contents-pro/?aff=wcl-panel').'" target="_blank">'.__( 'Download the free version, no email required', 'wip-custom-login').'</a>';	
		$plugin2 .= '</div>';

		$plugin3  = '<h1>'. __( 'Chatbox Manager', 'wip-custom-login') . '</h1>';
		$plugin3 .= '<p>'. __( '<strong>Chatbox Manager</strong> WordPress plugin allow you to display multiple WhatsApp buttons on your website.', 'wip-custom-login') . '</p>';
		$plugin3 .= '<div class="big-button">';		
		$plugin3 .= '<a href="'.esc_url( 'https://www.themeinprogress.com/chatbox-manager-pro/?aff=wcl-panel').'" target="_blank">'.__( 'Download the free version, no email required', 'wip-custom-login').'</a>';	
		$plugin3 .= '</div>';

		$plugin4  = '<h1>'. __( 'Content Snippet Manager', 'wip-custom-login') . '</h1>';
		$plugin4 .= '<p>'. __( '<strong>Content Snippet Manager</strong> WordPress plugin allow you to include every kind of code inside your Wordpress website.', 'wip-custom-login') . '</p>';
		$plugin4 .= '<div class="big-button">';		
		$plugin4 .= '<a href="'.esc_url( 'https://www.themeinprogress.com/content-snippet-manager/?aff=wcl-panel').'" target="_blank">'.__( 'Download the free version, no email required', 'wip-custom-login').'</a>';	
		$plugin4 .= '</div>';

		$banner = array($plugin1,$plugin2,$plugin3,$plugin4);
		echo $banner[array_rand($banner)];
	
	}

}

?>