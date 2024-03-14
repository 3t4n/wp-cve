<?php
/**
 * Settings Page functionality of the plugin.
 *
 * @link       http://codexin.com
 * @since      1.0.0
 *
 * @package    Codexin\ImageMetadataSettings
 * @subpackage Codexin\ImageMetadataSettings/admin
 */

namespace Codexin\ImageMetadataSettings\Admin;

use Codexin\ImageMetadataSettings\Common\Images;

/**
 * Settings Page functionality of the plugin.
 */
class Media {

	/**
	 * Add new column to media table
	 *
	 * @param array $columns customize column.
	 * @return array
	 */
	public function custom_column( $columns ) {
		$author   = isset( $columns['author'] ) ? $columns['author'] : '';
		$date     = isset( $columns['date'] ) ? $columns['date'] : '';
		$comments = isset( $columns['comments'] ) ? $columns['comments'] : '';
		$parent   = isset( $columns['parent'] ) ? $columns['parent'] : '';
		unset( $columns['author'] );
		unset( $columns['date'] );
		unset( $columns['comments'] );
		unset( $columns['parent'] );
		$columns['alt']         = __( 'Alt', 'media-library-helper' );
		$columns['caption']     = __( 'Caption', 'media-library-helper' );
		$columns['description'] = __( 'Description', 'media-library-helper' );
		$columns['parent']      = $parent;
		$columns['author']      = $author;
		$columns['comments']    = $comments;
		$columns['date']        = $date;
		return $columns;
	}
	/**
	 * Sortable column.
	 *
	 * @param string $columns sortable column.
	 * @return array
	 */
	public function sortable_columns( $columns ) {
		$columns['alt']         = 'alt';
		$columns['caption']     = 'caption';
		$columns['description'] = 'description';
		return $columns;
	}

	/**
	 * Undocumented function
	 *
	 * @param array  $pieces query.
	 * @param object $query post query.
	 * @return array
	 */
	public function manage_media_sortable_columns( $pieces, $query ) {
		global $wpdb;
		/**
		 * We only want our code to run in the main WP query
		 * AND if an orderby query variable is designated.
		 */
		if ( $query->is_main_query() ) {
			$orderby = $query->get( 'orderby' );
			if ( $orderby ) {
				$order = strtoupper( $query->get( 'order' ) );
				if ( in_array( $order, array( 'ASC', 'DESC' ), true ) ) {
					switch ( $orderby ) {
						case 'caption':
							$pieces['orderby'] = " $wpdb->posts.post_excerpt $order ";
							break;
						case 'description':
							$pieces['orderby'] = " $wpdb->posts.post_content $order ";
							break;
					}
				}
			}
		}
		return $pieces;
	}

	/**
	 * Sortable column function.
	 *
	 * @param array $vars query var.
	 * @return array
	 */
	public function alt_column_orderby( $vars ) {
		if ( isset( $vars['orderby'] ) ) {
			if ( 'alt' === $vars['orderby'] ) {
				$vars = array_merge(
					$vars,
					array(
						'orderby'    => 'meta_value',
						'meta_query' => array(
							'relation' => 'OR',
							array(
								'key'     => '_wp_attachment_image_alt',
								'compare' => 'NOT EXISTS',
								'value'   => '',
							),
							array(
								'key'     => '_wp_attachment_image_alt',
								'compare' => 'EXISTS',
							),
						),
					)
				);
			}
		}
		return $vars;
	}
	/**
	 * Undocumented function
	 *
	 * @param string $column column name.
	 * @param init   $post_id attachment id.
	 * @return void
	 */
	public function display_column_value( $column, $post_id ) {
		$image = Images::wp_get_attachment( $post_id );
		switch ( $column ) {
			case 'alt':
				echo '<div class="edit-column-content" data-content-type="alt" data-image-id="' . esc_attr( $post_id ) . '" contenteditable="false">';
				echo esc_html( wp_strip_all_tags( $image['alt'] ) );
				echo '</div>';
				break;

			case 'caption':
				echo '<div class="edit-column-content" data-image-id="' . esc_attr( $post_id ) . '" data-content-type="caption" contenteditable="false">';
				echo esc_html( $image['caption'] );
				echo '</div>';
				break;

			case 'description':
				echo '<div class="edit-column-content" data-image-id="' . esc_attr( $post_id ) . '" data-content-type="description" contenteditable="false">';
				echo esc_html( $image['description'] );
				echo '</div>';
				break;

			default:
				break;

		}
	}


	/**
	 * Undocumented function
	 *
	 * @param string $post_type post type name.
	 * @return void
	 */
	public function search_box( $post_type ) {
		if ( 'attachment' !== $post_type ) {
			return;
		}
		$options     = array(
			'all'         => __( 'All', 'media-library-helper' ),
			'alt'         => __( 'Alt', 'media-library-helper' ),
			'caption'     => __( 'Caption', 'media-library-helper' ),
			'description' => __( 'Description', 'media-library-helper' ),
		);
		$search_term = '';
		if ( isset( $_GET['search-term'] ) && ! empty( $_GET['search-term'] ) ) {
			if ( isset( $_GET['name_cdxn_media_field'] ) ) {
				$nonce_value = sanitize_text_field( wp_unslash( $_GET['name_cdxn_media_field'] ) );
				if ( wp_verify_nonce( $nonce_value, 'name_cdxn_action' ) ) {
					$search_term = sanitize_text_field( wp_unslash( $_GET['search-term'] ) );
				}
			}
		}
		echo '<select id="search-term" name="search-term">';
		foreach ( $options as $key => $value ) {
			$selected = ( $search_term === $key ) ? 'selected' : '';
			echo '<option value="' . esc_attr( $key ) . '" ' . esc_attr( $selected ) . '>' . esc_attr( $value ) . ' </option>';
		}
		echo '</select>';
	}
	/**
	 * MOdify filter.
	 *
	 * @param Obj $query query object.
	 * @return obj
	 */
	public function media_filter( $query ) {
		if ( is_admin() && $query->is_main_query() ) {
			if ( isset( $_GET['_ajax_nonce'] ) && isset( $_GET['search-term'] ) && ! empty( $_GET['search-term'] ) ) {
				$ajax_nonce = sanitize_text_field( wp_unslash( $_GET['_ajax_nonce'] ) );
				if ( ! current_user_can( 'manage_options' ) && ! wp_verify_nonce( $ajax_nonce, 'find-posts' ) ) {
					return $query;
				}
				$search_term = sanitize_text_field( wp_unslash( $_GET['search-term'] ) );
				$search_text = sanitize_text_field( wp_unslash( $_GET['s'] ) );
				if ( 'alt' === $search_term ) {
					$operator = ! empty($_GET['s']) ? 'LIKE' : 'NOT EXISTS';
					$query->set( 's', '' );
					$query->set(
						'meta_query',
						array(
							'relation' => 'OR',
							array(
								'key'     => '_wp_attachment_image_alt',
								'value'   => $search_text,
								'compare' => $operator,
							),
							array(
								'key'     => '_wp_attachment_image_alt',
								'value'   => $search_text,
								'compare' => '=',
							),
						)
					);
				}
			}
		}

		return $query;
	}

	/**
	 * Join custom tables.
	 *
	 * Use add_filter( 'posts_join', 'ss_join_table', 20, 2 ).
	 *
	 * @param string   $join String containing all joins.
	 * @param WP_Query $wp_query object.
	 *
	 * @return string
	 */
	public function search_join_table( $join, $wp_query ) {
		if ( isset( $_GET['_ajax_nonce'] ) && isset( $_GET['search-term'] ) && isset( $_GET['s'] ) ) {
			global $wpdb;
			$ajax_nonce = sanitize_text_field( wp_unslash( $_GET['_ajax_nonce'] ) );
			if ( ! current_user_can( 'manage_options' ) && ! wp_verify_nonce( $ajax_nonce, 'find-posts' ) ) {
				return $join;
			}
			$search_term = sanitize_text_field( wp_unslash( $_GET['search-term'] ) );
			$search_text = sanitize_text_field( wp_unslash( $_GET['s'] ) );
			
			$cdxn_column_exists = 'exists';
			if(is_plugin_active('give/give.php')) {
				$cdxn_column_exists = 'not-exists';
			}
			if ( ! empty( $search_text )) {
				if ( ('all' === $search_term) && ($cdxn_column_exists == 'exists')) {
					$join .= " INNER JOIN $wpdb->postmeta as cdxn_post_meta on cdxn_post_meta.post_id = $wpdb->posts.ID ";
				}
			}
		}
		return $join;

	}

	/**
	 * Join Query.
	 *
	 * Filter add_filter( 'posts_where', 'ss_where_table', 20, 2 );
	 *
	 * @param [type] $where sadfsd.
	 * @param [type] $wp_query asdfasd.
	 * @return obj
	 */
	public function search_where_table( $where, $wp_query ) {
		global $wpdb;

		if ( isset( $_GET['_ajax_nonce'] ) && isset( $_GET['search-term'] ) && ! empty( $_GET['search-term'] ) && isset( $_GET['s'] ) ) {
			$ajax_nonce = sanitize_text_field( wp_unslash( $_GET['_ajax_nonce'] ) );
			if ( ! current_user_can( 'manage_options' ) && ! wp_verify_nonce( $ajax_nonce, 'find-posts' ) ) {
				return $where;
			}
			$search_term = sanitize_text_field( wp_unslash( $_GET['search-term'] ) );
			$search_text = sanitize_text_field( wp_unslash( $_GET['s'] ) );
			
			$cdxn_column_exists = 'exists';
			if (!function_exists('is_plugin_active')) {
				include_once(ABSPATH . 'wp-admin/includes/plugin.php');
			}
			if(is_plugin_active('give/give.php')) {
				$cdxn_column_exists = 'not-exists';
			}

			switch ($search_term) {
				case 'all':
					if($cdxn_column_exists == 'exists') {
						if(!empty($search_text) ) {
							$where .= $wpdb->prepare( " OR cdxn_post_meta.meta_key='_wp_attachment_image_alt' AND cdxn_post_meta.meta_value LIKE %s ", '%' . $search_text . '%' );
						}
					}
					break;
				case 'description':
					$where .= $wpdb->prepare( " AND $wpdb->posts.post_content  LIKE %s ", '%' . $search_text . '%' );
					if( empty($search_text) ) {
						$where .= $wpdb->prepare( " AND TRIM($wpdb->posts.post_content) = %s", $search_text );
					}
					break;
				case 'caption':
					$where .= $wpdb->prepare( " AND $wpdb->posts.post_excerpt  LIKE %s ", '%' . $search_text . '%' );
					if( empty($search_text) ) {
						$where .= $wpdb->prepare( " AND TRIM($wpdb->posts.post_excerpt) = %s", $search_text );
					}
					break;
			}
		}
		return $where;
	}

	/**
	 * Replace media list table/
	 */
	public function add_bulk_action_export_to_list_media() {
		require_once CDXN_MLH_PATH . '/templates/extended-upload.php';
		exit();
	}
}
