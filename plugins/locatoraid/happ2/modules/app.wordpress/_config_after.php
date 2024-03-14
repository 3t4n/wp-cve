<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/app/lib->isme'][] = function( $app, $return )
{
	if( ! $return ){
		return $return;
	}

	global $pagenow;
	$return = FALSE;

	$pages = array('edit.php', 'post.php', 'admin.php');
	$my_type_prefix = $app->app_short_name() . '-';
	$my_pages = $app->app_pages();

	if( ! is_admin() ){
		return $return;
	}

	if( ! in_array($pagenow, $pages) ){
		return $return;
	}

	switch( $pagenow ){
		case 'edit.php':
			$check_post_type = isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : '';
			if( (substr($check_post_type, 0, strlen($my_type_prefix)) != $my_type_prefix) ){
				return $return;
			}
			break;

		case 'post.php':
			global $post;
			$check_post_type = isset($post->post_type) ? $post->post_type : '';
			if( (substr($check_post_type, 0, strlen($my_type_prefix)) != $my_type_prefix) ){
				return $return;
			}
			break;

		case 'admin.php':
			$check_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
			if( ! in_array($check_page, $my_pages) ){
				return $return;
			}
			break;

		default:
			return $return;
			break;
	}

	$return = TRUE;
	return $return;
};