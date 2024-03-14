<?php 

namespace app\Admin\Meta;

/* Exit if accessed directly. */
if (!defined('ABSPATH')) {
	exit;
}

use app\Helpers as Helper;

/**
 * Header class. This is the meat and potatoes.
 * All meta data is gathered and printed within
 * the header.
 *
 * @since  2.0.0
 */
class Header {

    public function __construct() {
		global $post;
		global $wp_query;

		/* double line break, we want some space to see our content within the header. */
		echo "\n\n".'<!-- This site is optimized with the Simple SEO plugin v'.SSEO_VERSION.' - https://wordpress.org/plugins/cds-simple-seo/ -->' . "\n";

		$keywords = null;
		$description = null;

		/**
		 *
		 * Remember always escape late, escape when you use a variable.
		 *
		 * esc_attr() within a attribute; src="esc_attr($value)"
		 * esc_html() within for a variable; <p>esc_html($value)</p>
		 * esc_url() within a href attribute; href="esc_url($value)"
		 * esc_textarea() within a textarea; <textarea>esc_textarea($value)</textarea>
		 *
		 */

		if (is_front_page() && is_home()) { /* Default Homepage */
			$description = get_option('sseo_default_meta_description');
			$keywords = get_option('sseo_default_meta_keywords');
		} elseif (is_home()) { /* Blog Page */
			$description = get_post_meta(get_option('page_for_posts'), 'sseo_meta_description', true);
			$keywords = get_post_meta(get_option('page_for_posts'), 'sseo_meta_keywords', true);
		} elseif ((is_front_page() || is_home()) && isset($post->ID)) { /* Static */
			$keywords = get_post_meta($post->ID, 'sseo_meta_keywords', true);
			$description = get_post_meta($post->ID, 'sseo_meta_description', true);
		} elseif (isset($post->ID)) {
			$keywords = get_post_meta($post->ID, 'sseo_meta_keywords', true);
			$description = get_post_meta($post->ID, 'sseo_meta_description', true);
		}

		if ($keywords) {
			echo '<meta name="keywords" content="'.esc_attr($keywords).'" />' . "\n";
		}

		$sseo_canonical_url = null;
		if (empty($sseo_canonical_url) && isset($post->ID)) {
			$sseo_canonical_url = get_post_meta($post->ID, 'sseo_canonical_url', true);
		}

		if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
			if (is_shop()) {
				$shop_page_id = get_option('woocommerce_shop_page_id');
				$description = get_post_meta($shop_page_id, 'sseo_meta_description', true);
				$description = apply_filters('sseo_meta_description', $description);

				$sseo_canonical_url = get_post_meta($shop_page_id, 'sseo_canonical_url', true);
				$sseo_canonical_url = apply_filters('sseo_canonical_url', $sseo_canonical_url);
			} elseif (is_product_category() || is_product_tag()) {
				$term = $wp_query->get_queried_object();
				if (!empty($term->term_id)) {
					$term_meta = get_option("taxonomy_".$term->term_id);

					if (!empty($term_meta['sseo_description']))
					$description = $term_meta['sseo_description'];

					if (!empty($term_meta['sseo_canonical_url']))
					$sseo_canonical_url = $term_meta['sseo_canonical_url'];
				}
			}
		}

		if (is_category() || is_tag()) {
			$term = $wp_query->get_queried_object();
			if (!empty($term->term_id)) {
				$term_meta = get_option("taxonomy_".$term->term_id);

				if (!empty($term_meta['sseo_description']))
				$description = $term_meta['sseo_description'];

				if (!empty($term_meta['sseo_canonical_url']))
				$sseo_canonical_url = $term_meta['sseo_canonical_url'];
			}
		}

		if ($description) {
			echo '<meta name="description" content="'.esc_attr($description).'" />' . "\n";
		}

		if (isset($post->ID)) {
			$sseo_robot_noindex = get_post_meta($post->ID, 'sseo_robot_noindex', true);
			$sseo_robot_nofollow = get_post_meta($post->ID, 'sseo_robot_nofollow', true);
		
			if (!empty($sseo_robot_noindex) && !empty($sseo_robot_nofollow)) {
				echo '<meta name="robots" content="noindex, nofollow" />' . "\n";
			} elseif (empty($sseo_robot_noindex) && !empty($sseo_robot_nofollow)) {
				echo '<meta name="robots" content="nofollow" />' . "\n";
			}
			if (!empty($sseo_robot_noindex) && empty($sseo_robot_nofollow)) {
				echo '<meta name="robots" content="noindex" />' . "\n";
			}

			$sseo_fb_title = get_post_meta($post->ID, 'sseo_fb_title', true);
			$sseo_fb_description = get_post_meta($post->ID, 'sseo_fb_description', true);
			$sseo_fb_image_id = get_post_meta($post->ID, 'sseo_fb_image', true);
		}

		$current_url = null;
		$sseo_fb_image = null;
		$sseo_fb_app_id = get_option('sseo_fb_app_id');
		if (isset($sseo_fb_image_id)) {
			$sseo_fb_image = wp_get_attachment_image_url($sseo_fb_image_id, 'full');
		}

		if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
			if (is_shop()) {
				$shop_page_id = get_option('woocommerce_shop_page_id');
				$sseo_fb_title = get_post_meta($shop_page_id, 'sseo_fb_title', true);
				$sseo_fb_description = get_post_meta($shop_page_id, 'sseo_fb_description', true);
				$sseo_fb_image_id = get_post_meta($shop_page_id, 'sseo_fb_image', true);
				$sseo_fb_image = wp_get_attachment_image_url($sseo_fb_image_id, 'full');
				$current_url = get_permalink($shop_page_id);
			}
		}

		if (empty($current_url) && (is_category() || is_tag() || is_tax())) {
			$obj_id = get_queried_object_id();
			$current_url = get_term_link($obj_id);
		} elseif (empty($current_url) && isset($post->ID)) {
			$current_url = get_permalink($post->ID);
		}

		if (empty($sseo_fb_title)) {
			$sseo_fb_title = Title::getTitle();
		}

		if ($sseo_fb_app_id) { echo '<meta property="fb:app_id" content="'.esc_attr($sseo_fb_app_id).'" />' . "\n"; }
		echo '<meta property="og:site_name" content="'.esc_attr(get_bloginfo('name')).'" />'."\n";
		if ($current_url) { echo '<meta property="og:url" content="'.esc_url($current_url).'" />' . "\n"; }
		if ($current_url) { echo '<meta property="og:type" content="website" />'."\n"; }
		if ($sseo_fb_title) { echo '<meta property="og:title" content="'.esc_attr($sseo_fb_title).'" />' . "\n"; }
		if (isset($sseo_fb_description)) { echo '<meta property="og:description" content="'.esc_attr($sseo_fb_description).'" />' . "\n"; }
		
		/* Default to the featured image sense we have no Facebook image. */
		if (empty($sseo_fb_image)) {
			$sseo_fb_image = wp_get_attachment_url(get_post_thumbnail_id());
		}
		
		if (!empty($sseo_fb_image)) {
			echo '<meta property="og:image" content="'.esc_url($sseo_fb_image).'" />' . "\n";
			echo '<meta property="og:image:url" content="'.esc_url($sseo_fb_image).'" />' . "\n";
		}
		
		if (isset($post->ID)) {
			$sseo_tw_title = get_post_meta($post->ID, 'sseo_tw_title', true);
			$sseo_tw_description = get_post_meta($post->ID, 'sseo_tw_description', true);
			$sseo_tw_image_id = get_post_meta($post->ID, 'sseo_tw_image', true);
		}
		
		$sseo_tw_image = null;
		$sseo_twitter_username = get_option('sseo_twitter_username');
		if (!empty($sseo_tw_image_id)) {
			$sseo_tw_image = wp_get_attachment_image_url($sseo_tw_image_id, 'full');
		}

		if (empty($sseo_tw_title)) {
			$sseo_tw_title = Title::getTitle();
		}
		if (empty($sseo_tw_description)) {
			$sseo_tw_description = $description;
		}

		if ($sseo_twitter_username) { echo '<meta name="twitter:site" content="'.esc_attr($sseo_twitter_username).'">' . "\n"; }

		if ($sseo_tw_title) { echo '<meta name="twitter:title" content="'.esc_attr($sseo_tw_title).'" />' . "\n"; }
		if ($sseo_tw_description) { echo '<meta name="twitter:description" content="'.esc_attr($sseo_tw_description).'" />' . "\n"; }
		
		/* Twitter image or featured image */
		if (empty($sseo_tw_image) && isset($post->ID) && has_post_thumbnail($post->ID)) {
			$sseo_tw_image = wp_get_attachment_url(get_post_thumbnail_id());
		}
		
		if (!empty($sseo_tw_image)) {
			echo '<meta name="twitter:image" content="'.esc_url($sseo_tw_image).'" />' . "\n";
			echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
		}

		$sseo_gsite_verification = esc_attr(get_option('sseo_gsite_verification'));
		if ($sseo_gsite_verification) {
			echo '<meta name="google-site-verification" content="'.esc_attr($sseo_gsite_verification).'" />' . "\n";
		}

		$sseo_baidu = esc_attr(get_option('sseo_baidu'));
		if ($sseo_baidu) {
			echo '<meta name="baidu-site-verification" content="'.esc_attr($sseo_baidu).'" />' . "\n";
		}

		$sseo_bing = esc_attr(get_option('sseo_bing'));
		if ($sseo_bing) {
			echo '<meta name="msvalidate.01" content="'.esc_attr($sseo_bing).'" />' . "\n";
		}

		$sseo_yandex = esc_attr(get_option('sseo_yandex'));
		if ($sseo_yandex) {
			echo '<meta name="yandex-verification" content="'.esc_attr($sseo_yandex).'" />' . "\n";
		}

		if (!empty($sseo_canonical_url)) {
			echo '<link rel="canonical" href="'.esc_url($sseo_canonical_url).'" />' . "\n";
		}

		echo '<!-- / Simple SEO plugin. -->' . "\n\n";
	}
}

?>