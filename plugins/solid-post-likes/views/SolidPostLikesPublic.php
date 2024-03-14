<?php
namespace OACS\SolidPostLikes\Views;

use OACS\SolidPostLikes\Controllers\SolidPostLikesChecker as Checker;
use OACS\SolidPostLikes\Controllers\SolidPostLikesCounter as Counter;
use OACS\SolidPostLikes\Controllers\SolidPostLikesIcon as Icon;
use OACS\SolidPostLikes\Controllers\SolidPostLikesText as Text;
if ( ! defined( 'WPINC' ) ) { die; }

class SolidPostLikesPublic
{
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version)
    {

        $this->plugin_name    = $plugin_name;
        $this->version        = $version;

    }
    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style('oacs-spl-public', plugin_dir_url(__FILE__) . 'public/css/solid-post-likes-public.css', array(), $this->version, false);
        /** No fontawesome yet */
        // wp_enqueue_style('fontawesome', plugin_dir_url(__FILE__) . 'public/css/all.css', array(), '5.1.3', 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script('solid-post-likes-public-js', plugin_dir_url(__FILE__) . 'public/js/solid-post-likes-public.js', array( 'jquery' ), $this->version, false);

        wp_localize_script('solid-post-likes-public-js', 'oacs_spl_solid_likes', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        ));

        $oacs_spl_like_cache_support_setting       = get_option('_oacs_spl_cache_support') ?? '';

		if($oacs_spl_like_cache_support_setting === 'yes')
		{
        wp_enqueue_script('solid-post-likes-cache-js', plugin_dir_url(__FILE__) . 'public/js/solid-post-likes-cache.js', array( 'jquery' ), $this->version, false);

        wp_localize_script('solid-post-likes-cache-js', 'oacs_spl_solid_likes_cache', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        ));
        }


    }



    public function oacs_spl_display_like_button($post_id, $is_comment = null)
    {
        $is_comment         = (null == $is_comment) ? 0 : 1;

        if(is_array($post_id)) {
            if (array_key_exists(0, $post_id)) {

                $post_id = get_the_ID();

            } else {
                $post_id = strval($post_id['post_id']);
            }
        }
        // Check whether post exists in database (and whether input makes sense).
        $is_post_id_valid   = is_string( get_post_status( $post_id ) );


        if($is_post_id_valid) {
            // post exists, keep $post_id.
        } else {
           unset($post_id);
        }

        // Make your shit secure ;).
        $nonce = wp_create_nonce('oacs_spl_likes_nonce');

        if ($is_comment == 1) {
            $post_id       = get_comment_ID();
            $post_id_class = esc_attr('oacs-spl-like-comment-button-' . $post_id);
            $comment_class = esc_attr(' oacs-spl-like-comment');
            $like_count    = get_comment_meta($post_id, "_oacs_spl_comment_like_count", true);
            $like_count    = (isset($like_count) && is_numeric($like_count)) ? $like_count : 0;
        } else {
            $post_id          = ! empty( $post_id ) ? $post_id : get_the_ID();
            $post_id_class    = esc_attr('oacs-spl-like-button-' . $post_id);
            $comment_class    = esc_attr('');
            $like_count       = get_post_meta($post_id, "_oacs_spl_post_like_count", true);
            $like_count       = (isset($like_count) && is_numeric($like_count)) ? $like_count : 0;
        }


        $oacs_spl_hide_counter_setting    = carbon_get_theme_option('oacs_spl_hide_counter_when_zero');
        $post_likes_counter               = new Counter;
        $count                            = ($like_count == 0 && $oacs_spl_hide_counter_setting) ? '' : $post_likes_counter->oacs_spl_get_like_count($like_count);

        $post_likes_icon = new Icon;
        $icon_empty      = $post_likes_icon->oacs_spl_get_unliked_icon()  ?? '';
        $icon_full       = $post_likes_icon->oacs_spl_get_liked_icon()  ?? '';

        $post_likes_icon = new Checker;

        // Liked/Unliked Variables

        if ($post_likes_icon->oacs_spl_already_liked($post_id, $is_comment)) {
            $class    = esc_attr('oacs-spl-liked');
            $title    = esc_html__('Unlike', 'oaspl');
            $icon     = $icon_full;
            $get_text = new Text;
            $text     = $get_text->oacs_spl_get_unlike_text() ?? '';
        } else {
            $class    = '';
            $title    = esc_html__('Like', 'oaspl');
            $icon     = $icon_empty;
            $get_text = new Text;
            $text     = $get_text->oacs_spl_get_like_text() ?? '';
        }

        // Output button markup.

        // Filters for before and after the button markup.
        $before_button = apply_filters( 'before_oacs_spl_button', '' );
        $after_button = apply_filters( 'after_oacs_spl_button', '' );


        $output = "<div class='oacs_spl_before_button'>" . $before_button . "</div>" .  '<span class="oacs-spl-like-button-wrapper"><a href="' . admin_url('admin-ajax.php?action=oacs_spl_process_like' . '&post_id=' . $post_id . '&nonce=' . $nonce . '&is_comment=' . $is_comment . '&disabled=true') . '" class="oacs-spl-like-button ' . $post_id_class . ' ' . $class . $comment_class . '" data-nonce="' . $nonce . '" data-post-id="' . $post_id . '" data-iscomment="' . $is_comment . '" title="' . $title . '"><span class="spinner"></span>' . $icon . $count . ' ' . $text . "</a></span><div class='oacs_spl_after_button'>" . $after_button . "</div>";

        $oacs_spl_show_likes_setting    = carbon_get_theme_option('oacs_spl_show_likes');


        /** General output */

        if ($oacs_spl_show_likes_setting) {
            // only show on single posts.
              if(is_singular()) {
              return $output;
              }
        } else {
        // and not on archive pages.
            return;
        }
    }
    /** Shortcode specific output */
    public function oacs_spl_display_like_shortcode($atts)
    {
        // normalize attribute keys, lowercase
        $atts = array_change_key_case( (array) $atts, CASE_LOWER );

        shortcode_atts( array(
            'post_like' => 1,
        ), $atts );

        return wp_kses_post($this->oacs_spl_display_like_button($atts, 0));
    }

    public function oacs_spl_display_like_postlist_shortcode()
    {
        ob_start(); // Start output buffering

        $oacs_spl_post_list = new SolidPostLikesPostList;
        $oacs_spl_post_list->oacs_spl_show_user_likes_post_list(get_current_user_id());

        return ob_get_clean(); // End output buffering and return the captured output
    }
}