<?php

/**
 * @return CPT_Admin_Notices
 */
function cpt_admin_notices() {
	global $cpt_admin_notices;
	if ( ! ( $cpt_admin_notices instanceof CPT_Admin_Notices ) ) {
		$cpt_admin_notices = new CPT_Admin_Notices();
	}
	return $cpt_admin_notices;
}

/**
 * @return CPT_Admin_Pages
 */
function cpt_admin_pages() {
	global $cpt_admin_pages;
	if ( ! ( $cpt_admin_pages instanceof CPT_Admin_Pages ) ) {
		$cpt_admin_pages = new CPT_Admin_Pages();
	}
	return $cpt_admin_pages;
}

/**
 * @return CPT_Ajax
 */
function cpt_ajax() {
	global $cpt_ajax;
	if ( ! ( $cpt_ajax instanceof CPT_Ajax ) ) {
		$cpt_ajax = new CPT_Ajax();
	}
	return $cpt_ajax;
}

/**
 * @return CPT_Core
 */
function cpt_core() {
	global $cpt_core;
	if ( ! ( $cpt_core instanceof CPT_Core ) ) {
		$cpt_core = new CPT_Core();
	}
	return $cpt_core;
}

/**
 * @return CPT_Field_Groups
 */
function cpt_field_groups() {
	global $cpt_field_groups;
	if ( ! ( $cpt_field_groups instanceof CPT_Field_Groups ) ) {
		$cpt_field_groups = new CPT_Field_Groups();
	}
	return $cpt_field_groups;
}

/**
 * @return CPT_Fields
 */
function cpt_fields() {
	global $cpt_fields;
	if ( ! ( $cpt_fields instanceof CPT_Fields ) ) {
		$cpt_fields = new CPT_Fields();
	}
	return $cpt_fields;
}

/**
 * @return CPT_Plugin
 */
function cpt_plugin() {
	global $cpt_plugin;
	if ( ! ( $cpt_plugin instanceof CPT_Plugin ) ) {
		$cpt_plugin = new CPT_Plugin();
	}
	return $cpt_plugin;
}

/**
 * @return CPT_Post_Types
 */
function cpt_post_types() {
	global $cpt_post_types;
	if ( ! ( $cpt_post_types instanceof CPT_Post_Types ) ) {
		$cpt_post_types = new CPT_Post_Types();
	}
	return $cpt_post_types;
}

/**
 * @return CPT_Shortcodes
 */
function cpt_shortcodes() {
	global $cpt_shortcodes;
	if ( ! ( $cpt_shortcodes instanceof CPT_Shortcodes ) ) {
		$cpt_shortcodes = new CPT_Shortcodes();
	}
	return $cpt_shortcodes;
}

/**
 * @return CPT_Taxonomies
 */
function cpt_taxonomies() {
	global $cpt_taxonomies;
	if ( ! ( $cpt_taxonomies instanceof CPT_Taxonomies ) ) {
		$cpt_taxonomies = new CPT_Taxonomies();
	}
	return $cpt_taxonomies;
}

/**
 * @return CPT_Ui
 */
function cpt_ui() {
	global $cpt_ui;
	if ( ! ( $cpt_ui instanceof CPT_Ui ) ) {
		$cpt_ui = new CPT_Ui();
	}
	return $cpt_ui;
}

/**
 * @return CPT_Utils
 */
function cpt_utils() {
	global $cpt_utils;
	if ( ! ( $cpt_utils instanceof CPT_Utils ) ) {
		$cpt_utils = new CPT_Utils();
	}
	return $cpt_utils;
}

/**
 * @param $meta_key
 * @param $post_id
 * @param $output_filter
 *
 * @return mixed|string|null
 */
function cpt_get_post_meta( $meta_key, $post_id = null, $output_filter = true ) {
	global $post;
	$current_post = $post_id && get_post( $post_id ) ? get_post( $post_id ) : $post;
	if ( ! $post ) {
		return '';
	}
	return cpt_fields()->get_meta(
		$meta_key,
		\CPT_Field_Groups::SUPPORT_TYPE_CPT,
		$current_post->post_type,
		$current_post->ID,
		function ( $post_id, $meta_key ) {
			$core_fields = array(
				'title'         => get_the_title( $post_id ),
				'content'       => get_the_content( $post_id ),
				'excerpt'       => get_the_excerpt( $post_id ),
				'thumbnail'     => get_the_post_thumbnail( $post_id, 'full' ),
				'author'        => sprintf( '<a href="%1$s" title="%2$s" aria-title="%2$s">%2$s</a>', get_author_posts_url( get_the_author_meta( 'ID' ) ), get_the_author() ),
				'written_date'  => get_the_date( get_option( 'date_format', 'd/m/Y' ), $post_id ),
				'modified_date' => get_the_modified_date( get_option( 'date_format', 'd/m/Y' ), $post_id ),
			);
			return isset( $core_fields[ $meta_key ] ) ? $core_fields[ $meta_key ] : get_post_meta( $post_id, $meta_key, true );
		},
		$output_filter
	);
}

/**
 * @param $meta_key
 * @param $term_id
 * @param $output_filter
 *
 * @return mixed|string|null
 */
function cpt_get_term_meta( $meta_key, $term_id, $output_filter = true ) {
	$term = $term_id && get_term( $term_id ) ? get_term( $term_id ) : false;
	if ( ! $term ) {
		return '';
	}
	return cpt_fields()->get_meta(
		$meta_key,
		\CPT_Field_Groups::SUPPORT_TYPE_TAX,
		$term->taxonomy,
		$term->term_id,
		function ( $term_id, $meta_key ) {
			$core_fields = array(
				'name'        => get_term( $term_id )->name,
				'description' => get_term( $term_id )->description,
			);
			return isset( $core_fields[ $meta_key ] ) ? $core_fields[ $meta_key ] : get_term_meta( $term_id, $meta_key, true );
		},
		$output_filter
	);
}

/**
 * @param $meta_key
 * @param $option_id
 * @param $output_filter
 *
 * @return mixed|string|null
 */
function cpt_get_option_meta( $meta_key, $option_id, $output_filter = true ) {
	return cpt_fields()->get_meta(
		$meta_key,
		\CPT_Field_Groups::SUPPORT_TYPE_OPTIONS,
		$option_id,
		$option_id,
		function ( $option_id, $meta_key ) {
			return get_option( "$option_id-$meta_key" );
		},
		$output_filter
	);
}

/**
 * @param $taxonomy
 * @param $post_id
 * @param $output_type
 * @param $terms_sep
 *
 * @return string
 */
function cpt_get_post_terms( $taxonomy, $post_id = null, $output_type = 'links', $terms_sep = ', ' ) {
	global $post;
	$current_post = $post_id && get_post( $post_id ) ? get_post( $post_id ) : $post;
	$post_terms   = get_the_terms( $current_post->ID, $taxonomy );
	$terms        = array();
	foreach ( $post_terms as $term ) {
		switch ( $output_type ) {
			case 'links':
				$terms[] = sprintf( '<a href="%1$s" title="%2$s" aria-title="%2$s">%2$s</a>', get_term_link( $term->term_id ), $term->name );
				break;
			case 'names':
				$terms[] = $term->name;
				break;
			case 'ids':
				$terms[] = $term->term_id;
				break;
		}
	}
	return implode( $terms_sep, $terms );
}

/**
 * @param $meta_key
 * @param $user_id
 * @param $output_filter
 *
 * @return mixed|string|null
 */
function cpt_get_user_meta( $meta_key, $user_id, $output_filter = true ) {
	return cpt_fields()->get_meta(
		$meta_key,
		\CPT_Field_Groups::SUPPORT_TYPE_EXTRA,
		\CPT_Field_Groups::SUPPORT_TYPE_EXTRA_USERS,
		$user_id,
		function ( $user_id, $meta_key ) {
			return get_user_meta( $user_id, $meta_key, true );
		},
		$output_filter
	);
}

/**
 * @param $meta_key
 * @param $media_id
 * @param $output_filter
 *
 * @return mixed|string|null
 */
function cpt_get_media_meta( $meta_key, $media_id, $output_filter = true ) {
	return cpt_fields()->get_meta(
		$meta_key,
		\CPT_Field_Groups::SUPPORT_TYPE_EXTRA,
		\CPT_Field_Groups::SUPPORT_TYPE_EXTRA_MEDIA,
		$media_id,
		function ( $media_id, $meta_key ) {
			return get_post_meta( $media_id, $meta_key, true );
		},
		$output_filter
	);
}

/**
 * @param $meta_key
 * @param $comment_id
 * @param $output_filter
 *
 * @return mixed|string|null
 */
function cpt_get_comment_meta( $meta_key, $comment_id, $output_filter = true ) {
	return cpt_fields()->get_meta(
		$meta_key,
		\CPT_Field_Groups::SUPPORT_TYPE_EXTRA,
		\CPT_Field_Groups::SUPPORT_TYPE_EXTRA_COMMENTS,
		$comment_id,
		function ( $comment_id, $meta_key ) {
			return get_comment_meta( $comment_id, $meta_key, true );
		},
		$output_filter
	);
}

/**
 * @param $meta_key
 * @param $menu_item_id
 * @param $output_filter
 *
 * @return mixed|string|null
 */
function cpt_get_menu_item_meta( $meta_key, $menu_item_id, $output_filter = true ) {
	return cpt_fields()->get_meta(
		$meta_key,
		\CPT_Field_Groups::SUPPORT_TYPE_EXTRA,
		\CPT_Field_Groups::SUPPORT_TYPE_EXTRA_MENU,
		$menu_item_id,
		function ( $menu_item_id, $meta_key ) {
			return get_post_meta( $menu_item_id, $meta_key, true );
		},
		$output_filter
	);
}