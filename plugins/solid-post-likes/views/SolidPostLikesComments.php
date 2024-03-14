<?php
namespace OACS\SolidPostLikes\Views;

/** This class manages the Likes output for WordPress Posts */
if ( ! defined( 'WPINC' ) ) { die; }

class SolidPostLikesComments extends SolidPostLikesPublic
{

    public function oacs_spl_display_like_position_comments($comment_text)
    {
        global $post;

		$oacs_spl_likes_position_setting        = carbon_get_theme_option('oacs_spl_like_position');
        $oacs_available_posts_setting           = carbon_get_theme_option('oacs_spl_available_posts');
        $oacs_spl_likes_for_comments_setting    = carbon_get_theme_option('oacs_spl_likes_for_comments_setting');

        if ($oacs_spl_likes_position_setting == 'top' && in_array(get_post_type($post), $oacs_available_posts_setting) && $oacs_spl_likes_for_comments_setting) {

            $link = $this->oacs_spl_display_like_button(get_the_ID(), 1);

            if ( ! function_exists( 'is_woocommerce_activated' ) ) {
                (is_singular()) ? ($comment_text = $link . $comment_text) : ($comment_text = $comment_text);
                }
            if ( function_exists( 'is_woocommerce_activated' ) ) {
                (is_singular() && !is_product()) ? ($comment_text = $link . $comment_text) : ($comment_text = $comment_text);
                }

        } elseif ($oacs_spl_likes_position_setting == 'bottom' && in_array(get_post_type($post), $oacs_available_posts_setting) && $oacs_spl_likes_for_comments_setting) {

            $link = $this->oacs_spl_display_like_button(get_the_ID(), 1);

            if ( ! function_exists( 'is_woocommerce_activated' ) ) {
                (is_singular()) ? ($comment_text = $comment_text . $link) : ($comment_text = $comment_text);
                }
            if ( function_exists( 'is_woocommerce_activated' ) ) {
                (is_singular() && !is_product()) ? ($comment_text = $comment_text . $link) : ($comment_text = $comment_text);
                }

        }
        return $comment_text;
    }

}