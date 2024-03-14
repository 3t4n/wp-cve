<?php 

namespace app\Admin;

/* Exit if accessed directly. */
if (!defined('ABSPATH')) {
	exit;
}

class Save {

	/**
	 * Saves taxonomy data.
	 *
	 * @since  2.0.0
	 */
	public static function saveTaxonomy($term_id) {
		if (isset($_POST['term_meta'])) {
			$term_meta = get_option("taxonomy_".$term_id);
			$cat_keys = array_keys($_POST['term_meta']);
			foreach ($cat_keys as $key) {
				if (isset($_POST['term_meta'][$key])) {
					$term_meta[$key] = sanitize_text_field($_POST['term_meta'][$key]);
				}
			}
			update_option("taxonomy_".$term_id, stripslashes_deep($term_meta));
		}
	}
	
	/**
	 * Saves post data.
	 *
	 * @since  2.0.0
	 */
    public static function savePost($postId) {
        $old_meta_title = get_post_meta($postId, 'sseo_meta_title', true);
        $new_meta_title = null;
        if (isset($_POST['sseo_meta_title'])) {
            $new_meta_title = sanitize_text_field($_POST['sseo_meta_title']);
        }

        if ($new_meta_title && $new_meta_title != $old_meta_title) {
            update_post_meta($postId, 'sseo_meta_title', $new_meta_title);
        } elseif (empty($new_meta_title) && $old_meta_title) {
            delete_post_meta($postId, 'sseo_meta_title', $old_meta_title);
        }

        $old_meta_description = get_post_meta($postId, 'sseo_meta_description', true);
        $new_meta_description = null;
        if (isset($_POST['sseo_meta_description'])) {
            $new_meta_description = sanitize_text_field($_POST['sseo_meta_description']);
        }

        if ($new_meta_description && $new_meta_description != $old_meta_description) {
            update_post_meta($postId, 'sseo_meta_description', $new_meta_description);
        } elseif (empty($new_meta_description) && $old_meta_description) {
            delete_post_meta($postId, 'sseo_meta_description', $old_meta_description);
        }

        $old_canonical_url = get_post_meta($postId, 'sseo_canonical_url', true);
        $new_canonical_url = null;
        if (isset($_POST['sseo_canonical_url'])) {
            $new_canonical_url = sanitize_text_field($_POST['sseo_canonical_url']);
        }

        if ($new_canonical_url && $new_canonical_url != $old_canonical_url) {
            update_post_meta($postId, 'sseo_canonical_url', $new_canonical_url);
        } elseif (empty($new_canonical_url) && $old_canonical_url) {
            delete_post_meta($postId, 'sseo_canonical_url', $old_canonical_url);
        }

        $old_meta_keywords = get_post_meta($postId, 'sseo_meta_keywords', true);
        if (isset($_POST['sseo_meta_keywords'])) {
            update_post_meta($postId, 'sseo_meta_keywords', sanitize_text_field($_POST['sseo_meta_keywords']));
        }

        /* Robots */
        if (!empty($_POST['sseo_robot_noindex'])) {
            update_post_meta($postId, 'sseo_robot_noindex', sanitize_text_field($_POST['sseo_robot_noindex']));
        } else {
            delete_post_meta($postId, 'sseo_robot_noindex');
        }

        if (!empty($_POST['sseo_robot_nofollow'])) {
            update_post_meta($postId, 'sseo_robot_nofollow', sanitize_text_field($_POST['sseo_robot_nofollow']));
        } else {
            delete_post_meta($postId, 'sseo_robot_nofollow');
        }

        /* Facebook */
        $old_sseo_fb_app_id = get_post_meta($postId, 'sseo_fb_app_id', true);
        $new_sseo_fb_app_id = null;
        if (isset($_POST['sseo_fb_app_id'])) {
            $new_sseo_fb_app_id = sanitize_text_field($_POST['sseo_fb_app_id']);
        }

        if ($new_sseo_fb_app_id && $new_sseo_fb_app_id != $old_sseo_fb_app_id) {
            update_post_meta($postId, 'sseo_fb_app_id', $new_sseo_fb_app_id);
        } elseif (empty($new_sseo_fb_app_id) && $old_sseo_fb_app_id) {
            delete_post_meta($postId, 'sseo_fb_app_id', $old_sseo_fb_app_id);
        }

        $old_fb_title = get_post_meta($postId, 'sseo_fb_title', true);
        $new_fb_title = null;
        if (isset($_POST['sseo_fb_title'])) {
            $new_fb_title = sanitize_text_field($_POST['sseo_fb_title']);
        }

        if ($new_fb_title && $new_fb_title != $old_fb_title) {
            update_post_meta($postId, 'sseo_fb_title', $new_fb_title);
        } elseif (empty($new_fb_title) && $old_fb_title) {
            delete_post_meta($postId, 'sseo_fb_title', $old_fb_title);
        }

        $old_fb_description = get_post_meta($postId, 'sseo_fb_description', true);
        $new_fb_description = null;
        if (isset($_POST['sseo_fb_description'])) {
            $new_fb_description = sanitize_text_field($_POST['sseo_fb_description']);
        }

        if ($new_fb_description && $new_fb_description != $old_fb_description) {
            update_post_meta($postId, 'sseo_fb_description', $new_fb_description);
        } elseif (empty($new_fb_description) && $old_fb_description) {
            delete_post_meta($postId, 'sseo_fb_description', $old_fb_description);
        }

        $old_fb_image = get_post_meta($postId, 'sseo_fb_image', true);
        $new_fb_image = null;
        if (isset($_POST['sseo_fb_image'])) {
            $new_fb_image = sanitize_text_field($_POST['sseo_fb_image']);
        }

        if ($new_fb_image && $new_fb_image != $old_fb_image) {
            update_post_meta($postId, 'sseo_fb_image', $new_fb_image);
        } elseif (empty($new_fb_image) && $old_fb_image) {
            delete_post_meta($postId, 'sseo_fb_image', $old_fb_image);
        }

        /* Twitter */
        $old_sseo_twitter_username = get_post_meta($postId, 'sseo_twitter_username', true);
        $new_sseo_twitter_username = null;
        if (isset($_POST['sseo_twitter_username'])) {
            $new_sseo_twitter_username = sanitize_text_field($_POST['sseo_twitter_username']);
        }

        if ($new_sseo_twitter_username && $new_sseo_twitter_username != $old_sseo_twitter_username) {
            update_post_meta($postId, 'sseo_twitter_username', $new_sseo_twitter_username);
        } elseif (empty($new_sseo_twitter_username) && $old_sseo_twitter_username) {
            delete_post_meta($postId, 'sseo_twitter_username', $old_sseo_twitter_username);
        }

        $old_tw_title = get_post_meta($postId, 'sseo_tw_title', true);
        $new_tw_title = null;
        if (isset($_POST['sseo_tw_title'])) {
            $new_tw_title = sanitize_text_field($_POST['sseo_tw_title']);
        }

        if ($new_tw_title && $new_tw_title != $old_tw_title) {
            update_post_meta($postId, 'sseo_tw_title', $new_tw_title);
        } elseif (empty($new_tw_title) && $old_tw_title) {
            delete_post_meta($postId, 'sseo_tw_title', $old_tw_title);
        }

        $old_tw_description = get_post_meta($postId, 'sseo_tw_description', true);
        $new_tw_description = null;
        if (isset($_POST['sseo_tw_description'])) {
            $new_tw_description = sanitize_text_field($_POST['sseo_tw_description']);
        }

        if ($new_tw_description && $new_tw_description != $old_tw_description) {
            update_post_meta($postId, 'sseo_tw_description', $new_tw_description);
        } elseif (empty($new_tw_description) && $old_tw_description) {
            delete_post_meta($postId, 'sseo_tw_description', $old_tw_description);
        }

        $old_tw_image = get_post_meta($postId, 'sseo_tw_image', true);
        $new_tw_image = null;
        if (isset($_POST['sseo_tw_image'])) {
            $new_tw_image = sanitize_text_field($_POST['sseo_tw_image']);
        }

        if ($new_tw_image && $new_tw_image != $old_tw_image) {
            update_post_meta($postId, 'sseo_tw_image', $new_tw_image);
        } elseif (empty($new_tw_image) && $old_tw_image) {
            delete_post_meta($postId, 'sseo_tw_image', $old_tw_image);
        }
	}
	
}