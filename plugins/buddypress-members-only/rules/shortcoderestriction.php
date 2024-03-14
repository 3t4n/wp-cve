<?php
if (!defined('ABSPATH'))
{
	exit;
}

function buddypress_members_only_pro_restriction_shortcode($atts, $inputcontent = null)
{
	global $user_ID , $post;

	extract( shortcode_atts(
			array(
					'link' => '',
			), $atts )
			);
	
	if ( is_user_logged_in() == false )
	{
		if (empty($link))
		{
			
		
			$saved_register_page_url = get_option ( 'bpmoregisterpageurl' );
			if (empty($saved_register_page_url))
			{
			    //3.2.3
			    
			    $current_url = sanitize_text_field($_SERVER['REQUEST_URI']);
				//$current_url = $_SERVER['REQUEST_URI'];
				$redirect_url = wp_login_url( );
			}
			else
			{
				$saved_register_page_url = $saved_register_page_url;
				$redirect_url = $saved_register_page_url;
			}
		}
		else 
		{
			$redirect_url = $link;
		}
		
		$restrcition_not_logged_in_message  = "Sorry, this is restricted content, please <a href='$redirect_url'> log in first </a> " ;
		return $restrcition_not_logged_in_message;
	}
	else 
	{
		return $inputcontent;
	}
}


add_shortcode( 'restriction', 'buddypress_members_only_pro_restriction_shortcode' );

