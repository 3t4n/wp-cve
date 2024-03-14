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
/* Woocommerce is active */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'csm_is_woocommerce_active' ) ) {

	function csm_is_woocommerce_active( $type = '' ) {

        global $woocommerce;

        if ( isset( $woocommerce ) ) {

			if ( !$type || call_user_func($type) ) {

				return true;

			}

		}

	}

}

/*-----------------------------------------------------------------------------------*/
/* SETTINGS */
/*-----------------------------------------------------------------------------------*/

if (!function_exists('csm_setting')) {

	function csm_setting($id, $default = '' ) {

		$settings = get_option('csm_settings');

		if(isset($settings[$id]) && !empty($settings[$id])):

			return $settings[$id];

		else:

			return $default;

		endif;

	}

}

/*-----------------------------------------------------------------------------------*/
/* AJAX POSTS LIST */
/*-----------------------------------------------------------------------------------*/

if (!function_exists('csm_get_ajax_list_posts')) {

	function csm_get_ajax_list_posts() {

		global $wpdb;

		$result = array();

		$search = sanitize_text_field($_GET['q']);
		$post_type = sanitize_text_field($_REQUEST['csm_post_type']);

		if (strpos($search, '[Al') !== false || strpos($search, '[al') !== false) {

			$result[] = array(
				'text' => '[All]',
				'id' => '-1',
			);

		} else {

			add_filter('posts_where', function( $where ) use ($search) {
				$where .= (" AND post_title LIKE '%" . $search . "%'");
				return $where;
			});

			$query = array(
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'post_type' => $post_type,
				'order' => 'ASC',
				'orderby' => 'title',
				'suppress_filters' => false,
			);

			$posts = get_posts( $query );

			foreach ($posts as $this_post) {

				$post_title = $this_post->post_title;
				$id = $this_post->ID;

				$result[] = array(
					'text' => esc_html($post_title),
					'id' => esc_attr($id),
				);

			}

		}

		$posts['items'] = $result;
		echo json_encode($posts);
		die();

	}

	add_action( 'wp_ajax_csm_list_posts', 'csm_get_ajax_list_posts' );

}

/*-----------------------------------------------------------------------------------*/
/* AJAX TAXONOMY LIST */
/*-----------------------------------------------------------------------------------*/

if (!function_exists('csm_get_ajax_list_taxonomy')) {

	function csm_get_ajax_list_taxonomy() {

		global $wpdb;

		$result = array();
		$search = sanitize_text_field($_GET['q']);

		if (strpos($search, '[Al') !== false || strpos($search, '[al') !== false) {

			$result[] = array(
				'text' => '[All]',
				'id' => '-1',
			);

		} else {

			$args = array(
				'taxonomy' => sanitize_text_field($_REQUEST['csm_taxonomy_type']),
				'hide_empty' => false,
				'name__like' => $search
			);

			foreach ( get_terms($args) as $cat) {
				$result[] = array(
					'text' => esc_html($cat->name),
					'id' => esc_attr($cat->term_id),
				);
			}

		}

		$terms['items'] = $result;
		echo json_encode($terms);
		die();

	}

	add_action( 'wp_ajax_csm_list_taxonomy', 'csm_get_ajax_list_taxonomy' );

}

/*-----------------------------------------------------------------------------------*/
/* GET CUSTOM POST LIST */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'csm_get_custom_post_list' ) ) {

	function csm_get_custom_post_list() {

		$cpt = array(
			'post'		=> 'post',
			'page'		=> 'page',
		);

		if ( csm_is_woocommerce_active())
			$cpt = array_merge($cpt, array('product' => 'product'));

		return $cpt;

	}

}

/*-----------------------------------------------------------------------------------*/
/* GET TAXONOMIES LIST */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'csm_get_taxonomies_list' ) ) {

	function csm_get_taxonomies_list() {

		$ct = array(
			'category'		=> 'category',
			'post_tag'		=> 'post_tag'
		);

		if ( csm_is_woocommerce_active())
			$ct = array_merge($ct, array('product_cat' => 'product_cat'));

		return $ct;

	}

}

?>
