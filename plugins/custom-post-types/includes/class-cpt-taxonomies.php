<?php

defined( 'ABSPATH' ) || exit;

final class CPT_Taxonomies extends CPT_Component {


	private $default_args = array();

	private $default_labels = array();

	public function __construct() {
		if ( empty( $this->default_args ) ) {
			$this->default_args = cpt_utils()->get_args( 'taxonomies-default-args' );
		}
		if ( empty( $this->default_labels ) ) {
			$this->default_labels = cpt_utils()->get_args( 'taxonomies-default-labels' );
		}
	}

	/**
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'init', array( $this, 'init_taxonomies' ) );
		add_action( 'updated_post_meta', array( $this, 'force_flush_rewrite_rules' ), 10, 3 );
	}

	/**
	 * @param $singular
	 * @param $plural
	 *
	 * @return array|mixed
	 */
	private function get_taxonomy_default_labels( $singular = '', $plural = '' ) {
		$labels = $this->default_labels;
		foreach ( $labels as $key => $label ) {
			$is_singular    = ! in_array( $key, array( 'name', 'menu_name', 'popular_items', 'search_items', 'not_found', 'all_items', 'back_to_items', 'add_or_remove_items', 'separate_items_with_commas' ), true );
			$labels[ $key ] = sprintf( $label, ( $is_singular ? $singular : $plural ) );
		}
		return $labels;
	}

	/**
	 * @param $admin_only
	 *
	 * @return array|mixed
	 */
	private function get_taxonomy_default_args( $admin_only = false ) {
		$args = $this->default_args;
		if ( $admin_only ) {
			$args['capabilities'] = array(
				'manage_terms' => 'update_core',
				'edit_terms'   => 'update_core',
				'delete_terms' => 'update_core',
				'assign_terms' => 'update_core',
			);
		}
		return $args;
	}

	/**
	 * @return array
	 */
	private function get_registered_taxonomies() {
		$taxonomies = get_posts(
			array(
				'posts_per_page' => -1,
				'post_type'      => CPT_UI_PREFIX . '_tax',
				'post_status'    => 'publish',
			)
		);

		$registered_taxonomies = array();

		foreach ( $taxonomies as $taxonomy ) {
			$post_metas = get_post_meta( $taxonomy->ID );

			$taxonomy_id       = $post_metas['id'][0];
			$taxonomy_singular = $post_metas['singular'][0];
			$taxonomy_plural   = $post_metas['plural'][0];
			$taxonomy_slug     = ! empty( $post_metas['slug'][0] ) ? sanitize_title( $post_metas['slug'][0] ) : sanitize_title( $taxonomy_plural );

			unset( $post_metas['id'], $post_metas['singular'], $post_metas['plural'], $post_metas['slug'] );

			$taxonomy_labels = array();
			$taxonomy_args   = array();

			foreach ( $post_metas as $key => $value ) {
				$single_meta = get_post_meta( $taxonomy->ID, $key, true );
				if ( substr( $key, 0, 7 ) == 'labels_' ) { //phpcs:ignore Universal.Operators.StrictComparisons
					if ( ! empty( $single_meta ) ) {
						$taxonomy_labels[ str_replace( 'labels_', '', $key ) ] = $single_meta;
					}
				} elseif ( substr( $key, 0, 1 ) == '_' || empty( $single_meta ) ) { //phpcs:ignore Universal.Operators.StrictComparisons
					unset( $post_metas[ $key ] );
				} else {
					$taxonomy_args[ $key ] = in_array( $single_meta, array( 'true', 'false' ), true ) ? ( 'true' == $single_meta ) : $single_meta; //phpcs:ignore Universal.Operators.StrictComparisons
				}
				unset( $post_metas[ $key ] );
			}

			$taxonomy_post_types = ! empty( $taxonomy_args['supports'] ) && is_array( $taxonomy_args['supports'] ) ? $taxonomy_args['supports'] : array();
			unset( $taxonomy_args['supports'] );

			$taxonomy_args['rewrite']['slug'] = $taxonomy_slug;

			$registered_taxonomies[] = array(
				'id'         => $taxonomy_id,
				'singular'   => $taxonomy_singular,
				'plural'     => $taxonomy_plural,
				'post_types' => $taxonomy_post_types,
				'args'       => $taxonomy_args,
				'labels'     => $taxonomy_labels,
			);
		}

		return (array) apply_filters( 'cpt_taxonomies_register', $registered_taxonomies );
	}

	/**
	 * @return void
	 */
	public function init_taxonomies() {
		$taxonomies = $this->get_registered_taxonomies();

		foreach ( $taxonomies as $i => $taxonomy ) {
			$id         = ! empty( $taxonomy['id'] ) && is_string( $taxonomy['id'] ) ? $taxonomy['id'] : false;
			$singular   = ! empty( $taxonomy['singular'] ) && is_string( $taxonomy['singular'] ) ? $taxonomy['singular'] : false;
			$plural     = ! empty( $taxonomy['plural'] ) && is_string( $taxonomy['plural'] ) ? $taxonomy['plural'] : false;
			$post_types = ! empty( $taxonomy['post_types'] ) && is_array( $taxonomy['post_types'] ) ? $taxonomy['post_types'] : array();
			$args       = ! empty( $taxonomy['args'] ) && is_array( $taxonomy['args'] ) ? $taxonomy['args'] : array();
			$labels     = ! empty( $taxonomy['labels'] ) && is_array( $taxonomy['labels'] ) ? $taxonomy['labels'] : array();

			$notice_title = cpt_utils()->get_notices_title();
			$error_info   = cpt_utils()->get_registration_error_notice_info( $taxonomy, 'tax' );

			if ( ! $id || ! $singular || ! $plural ) {
				add_filter(
					'cpt_admin_notices_register',
					function ( $args ) use ( $error_info, $notice_title ) {
						$args[] = array(
							'id'          => $error_info['id'],
							'title'       => $notice_title,
							'message'     => __( 'Taxonomy registration was not successful ("id" "singular" and "plural" args are required).', 'custom-post-types' ) . $error_info['details'],
							'type'        => 'error',
							'dismissible' => 3,
							'admin_only'  => 'true',
							'buttons'     => false,
						);
						return $args;
					}
				);
				unset( $taxonomies[ $i ] );
				continue;
			}

			if ( in_array( $id, cpt_utils()->get_post_type_blacklist(), true ) ) {
				add_filter(
					'cpt_admin_notices_register',
					function ( $args ) use ( $error_info, $notice_title ) {
						$args[] = array(
							'id'          => $error_info['id'],
							'title'       => $notice_title,
							'message'     => __( 'Taxonomy reserved or already registered, try a different "id".', 'custom-post-types' ) . $error_info['details'],
							'type'        => 'error',
							'dismissible' => 3,
							'admin_only'  => 'true',
							'buttons'     => false,
						);
						return $args;
					}
				);
				unset( $taxonomies[ $i ] );
				continue;
			}

			$admin_only = isset( $args['admin_only'] ) ? $args['admin_only'] : false;

			$registration_labels = array_replace_recursive( $this->get_taxonomy_default_labels( $singular, $plural ), $labels );
			$registration_labels = apply_filters( 'cpt_taxonomies_register_labels', $registration_labels, $id );

			$registration_args           = array_replace_recursive( $this->get_taxonomy_default_args( $admin_only ), $args );
			$registration_args['labels'] = array_map( 'esc_html', $registration_labels );
			$registration_args           = apply_filters( 'cpt_taxonomies_register_args', $registration_args, $id );

			$register = register_taxonomy( $id, $post_types, $registration_args );

			if ( is_wp_error( $register ) ) {
				add_filter(
					'cpt_admin_notices_register',
					function ( $args ) use ( $error_info, $notice_title ) {
						$args[] = array(
							'id'          => $error_info['id'] . '_core',
							'title'       => $notice_title,
							'message'     => __( 'Taxonomy registration was not successful.', 'custom-post-types' ) . $error_info['details'],
							'type'        => 'error',
							'dismissible' => 3,
							'admin_only'  => 'true',
							'buttons'     => false,
						);
						return $args;
					}
				);
				unset( $taxonomies[ $i ] );
			}
		}

		cpt_utils()->flush_rewrite_rules( $taxonomies );
	}

	/**
	 * @param $meta_id
	 * @param $object_id
	 * @param $meta_key
	 *
	 * @return void
	 */
	public function force_flush_rewrite_rules( $meta_id, $object_id, $meta_key ) {
		if ( ! in_array( $meta_key, array( 'slug', 'public', 'hierarchical', 'has_archive' ), true ) ) {
			return;
		}
		$taxonomy_id         = get_post_meta( $object_id, 'id', true );
		$flush_rewrite_rules = false;
		$taxonomies          = $this->get_registered_taxonomies();
		foreach ( $taxonomies as $taxonomy ) {
			if ( $flush_rewrite_rules ) {
				break;
			}
			$id = ! empty( $taxonomy['id'] ) && is_string( $taxonomy['id'] ) ? $taxonomy['id'] : false;
			if ( $id == $taxonomy_id ) {
				$flush_rewrite_rules = true;
			}
		}
		if ( $flush_rewrite_rules ) {
			cpt_utils()->refresh_rewrite_rules( $taxonomy_id );
		}
	}
}
