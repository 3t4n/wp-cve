<?php
namespace OACS\SolidPostLikes\Controllers;

if ( ! defined( 'WPINC' ) ) {die;}

//** This sets the manual post like count via admin settings "Set Likes" */

class SolidPostLikesSetter
{
	public function oacs_spl_set_like_count()
		{
			$post_id                           = intval(carbon_get_theme_option( 'oacs_spl_set_like_count_post' ));
			$oacs_spl_set_like_count_setting   = intval(carbon_get_theme_option( 'oacs_spl_set_like_count' ));

			if (!empty($post_id) && isset($oacs_spl_set_like_count_setting)) {
				update_post_meta($post_id, "_oacs_spl_post_like_count", $oacs_spl_set_like_count_setting);
				carbon_set_theme_option('oacs_spl_set_like_count_post', '');
				carbon_set_theme_option('oacs_spl_set_like_count', '');
				return;
			}
			return ;
		}

}