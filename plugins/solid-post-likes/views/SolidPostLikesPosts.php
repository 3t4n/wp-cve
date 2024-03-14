<?php
namespace OACS\SolidPostLikes\Views;

/** This class manages the Likes output for WordPress Posts */
if ( ! defined( 'WPINC' ) ) { die; }
class SolidPostLikesPosts extends SolidPostLikesPublic
{
    public function oacs_spl_display_like_position($content)
    {
        global $post;
		$oacs_spl_likes_position_setting    = carbon_get_theme_option('oacs_spl_like_position');
		$oacs_available_posts_setting       = carbon_get_theme_option('oacs_spl_available_posts');
		$oacs_skip_posts                    = carbon_get_theme_option('oacs_spl_disable_likes');
		$post_id                            = get_the_ID();

        if(!strstr($oacs_skip_posts, "$post_id"))
        {
            if ($oacs_spl_likes_position_setting == 'top' && in_array(get_post_type($post), $oacs_available_posts_setting))
            {
                $link = $this->oacs_spl_display_like_button($post_id, 0);
                if ( !class_exists( 'woocommerce' ) )  {
                    (is_singular()) ? ($content = $link . $content) : ($content = $content);
                    }
                if ( class_exists( 'woocommerce' ) )  {
                    (is_singular() && !is_product()) ? ($content = $link . $content) : ($content = $content);
                    }
            }
            elseif ($oacs_spl_likes_position_setting == 'bottom' && in_array(get_post_type($post), $oacs_available_posts_setting))
            {
                $link = $this->oacs_spl_display_like_button($post_id, 0);
                if ( !class_exists( 'woocommerce' ) ) {
                    (is_singular()) ? ($content = $content . $link) : ($content = $content);
                    }
                if ( class_exists( 'woocommerce' ) ) {
                    (is_singular() && !is_product()) ? ($content = $content . $link) : ($content = $content);
                    }
            }
        }
        return $content;
    }

    public function oacs_spl_display_post_likes_hook()
    {
        echo esc_html($this->oacs_spl_display_like_button(get_the_ID(), 0));
    }
}