<?php
namespace OACS\SolidPostLikes\Views;

/** This class manages the Likes output for WooCommerce Products */
if ( ! defined( 'WPINC' ) ) { die; }
class SolidPostLikesWoo extends SolidPostLikesPublic
{

   public function oacs_spl_get_woo_hook_settings()
   {
        $oacs_spl_hook_woo_hook = carbon_get_theme_option('oacs_spl_hook_woo_hook');
        return $oacs_spl_hook_woo_hook;
   }


    public function oacs_spl_display_product_likes()
    {
        $oacs_available_posts_setting = carbon_get_theme_option('oacs_spl_available_posts');
        global $post;

        if (is_product() && in_array(get_post_type($post), $oacs_available_posts_setting))
        {
            echo $this->oacs_spl_display_like_button(get_the_ID(), 0);
        }
    }
}