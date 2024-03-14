<?php

defined( 'ABSPATH' ) || exit;

final class CPT_Post_Types extends CPT_Component {
	/**
	 * @var array|mixed|null
	 */
	private $default_args = array();

	/**
	 * @var array|mixed|null
	 */
	private $default_labels = array();

	public function __construct() {
		if ( empty( $this->default_args ) ) {
			$this->default_args = cpt_utils()->get_args( 'post-types-default-args' );
		}
		if ( empty( $this->default_labels ) ) {
			$this->default_labels = cpt_utils()->get_args( 'post-types-default-labels' );
		}
	}

	/**
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'init', array( $this, 'init_post_types' ) );
		add_action( 'updated_post_meta', array( $this, 'force_flush_rewrite_rules' ), 10, 3 );
	}

	/**
	 * @param $singular
	 * @param $plural
	 *
	 * @return array|mixed
	 */
	private function get_post_type_default_labels( $singular = '', $plural = '' ) {
		$labels = $this->default_labels;
		foreach ( $labels as $key => $label ) {
			$is_singular    = ! in_array( $key, array( 'name', 'menu_name', 'view_items', 'search_items', 'not_found', 'not_found_in_trash', 'all_items', 'filter_items_list', 'items_list_navigation', 'items_list' ), true );
			$labels[ $key ] = sprintf( $label, ( $is_singular ? $singular : $plural ) );
		}
		return $labels;
	}

	/**
	 * @param $admin_only
	 *
	 * @return array|mixed
	 */
	private function get_post_type_default_args( $admin_only = false ) {
		$args = $this->default_args;
		if ( $admin_only ) {
			$args['capabilities'] = array(
				'edit_post'          => 'update_core',
				'read_post'          => 'update_core',
				'delete_post'        => 'update_core',
				'edit_posts'         => 'update_core',
				'edit_others_posts'  => 'update_core',
				'delete_posts'       => 'update_core',
				'publish_posts'      => 'update_core',
				'read_private_posts' => 'update_core',
			);
		}
		return $args;
	}

	/**
	 * @param $post_type
	 * @param $columns
	 *
	 * @return void
	 */
	private function add_columns( $post_type = 'post', $columns = array() ) {
		if ( empty( $post_type ) || empty( $columns ) ) {
			return;
		}
		global $pagenow;
		if ( 'edit.php' == $pagenow && isset( $_GET['post_type'] ) && $_GET['post_type'] == $post_type ) { //phpcs:ignore Universal.Operators.StrictComparisons
			add_filter(
				'manage_posts_columns',
				function ( $post_columns ) use ( $columns ) {
					$stored_title_label = empty( $columns['title'] ) ? $post_columns['title'] : null;
					$stored_date_label  = empty( $columns['date'] ) ? $post_columns['date'] : null;
					unset( $post_columns['title'] );
					unset( $post_columns['date'] );

					foreach ( $columns as $key => $args ) {
						if ( 'title' == $key && empty( $args['label'] ) ) { //phpcs:ignore Universal.Operators.StrictComparisons
							$args['label'] = $stored_title_label;
						}
						if ( 'date' == $key && empty( $args['label'] ) ) { //phpcs:ignore Universal.Operators.StrictComparisons
							$args['label'] = $stored_date_label;
						}
						$post_columns[ $key ] = $args['label'];
					}
					return $post_columns;
				}
			);
			add_action(
				'manage_posts_custom_column',
				function ( $post_column, $post_id ) use ( $columns ) {
					if ( isset( $columns[ $post_column ]['callback'] ) ) {
						$columns[ $post_column ]['callback']( $post_id );
					}
				},
				10,
				2
			);
		}
	}

	/**
	 * @return array
	 */
	private function get_registered_post_types() {
		$post_types = get_posts(
			array(
				'posts_per_page' => -1,
				'post_type'      => CPT_UI_PREFIX,
				'post_status'    => 'publish',
			)
		);

		$registered_post_types = array();

		foreach ( $post_types as $post_type ) {
			$post_metas = get_post_meta( $post_type->ID );

			$post_type_id       = $post_metas['id'][0];
			$post_type_singular = $post_metas['singular'][0];
			$post_type_plural   = $post_metas['plural'][0];
			$post_type_slug     = ! empty( $post_metas['slug'][0] ) ? sanitize_title( $post_metas['slug'][0] ) : sanitize_title( $post_type_plural );

			unset( $post_metas['id'], $post_metas['singular'], $post_metas['plural'], $post_metas['slug'] );

			$post_type_labels = array();
			$post_type_args   = array();

			foreach ( $post_metas as $key => $value ) {
				$single_meta = get_post_meta( $post_type->ID, $key, true );
				if ( substr( $key, 0, 7 ) == 'labels_' ) { //phpcs:ignore Universal.Operators.StrictComparisons
					if ( ! empty( $single_meta ) ) {
						$post_type_labels[ str_replace( 'labels_', '', $key ) ] = $single_meta;
					}
				} elseif ( substr( $key, 0, 1 ) == '_' || empty( $single_meta ) ) { //phpcs:ignore Universal.Operators.StrictComparisons
					unset( $post_metas[ $key ] );
				} else {
					$post_type_args[ $key ] = in_array( $single_meta, array( 'true', 'false' ), true ) ? ( 'true' == $single_meta ) : $single_meta; //phpcs:ignore Universal.Operators.StrictComparisons
				}
				unset( $post_metas[ $key ] );
			}

			$post_type_args['rewrite']['slug'] = $post_type_slug;

			$registered_post_types[] = array(
				'id'       => $post_type_id,
				'singular' => $post_type_singular,
				'plural'   => $post_type_plural,
				'args'     => $post_type_args,
				'labels'   => $post_type_labels,
			);
		}

		unset( $post_types );

		return (array) apply_filters( 'cpt_post_types_register', $registered_post_types );
	}

	/**
	 * @return void
	 */
	public function init_post_types() {
		$post_types = $this->get_registered_post_types();

		$post_types = array_merge( cpt_utils()->get_args( 'core-post-types' ), $post_types );

		foreach ( $post_types as $i => $post_type ) {
			$id       = ! empty( $post_type['id'] ) && is_string( $post_type['id'] ) ? $post_type['id'] : false;
			$singular = ! empty( $post_type['singular'] ) && is_string( $post_type['singular'] ) ? $post_type['singular'] : false;
			$plural   = ! empty( $post_type['plural'] ) && is_string( $post_type['plural'] ) ? $post_type['plural'] : false;
			$args     = ! empty( $post_type['args'] ) && is_array( $post_type['args'] ) ? $post_type['args'] : array();
			$labels   = ! empty( $post_type['labels'] ) && is_array( $post_type['labels'] ) ? $post_type['labels'] : array();

			$notice_title = cpt_utils()->get_notices_title();
			$error_info   = cpt_utils()->get_registration_error_notice_info( $post_type );

			if ( ! $id || ! $singular || ! $plural ) {
				add_filter(
					'cpt_admin_notices_register',
					function ( $args ) use ( $error_info, $notice_title ) {
						$args[] = array(
							'id'          => $error_info['id'],
							'title'       => $notice_title,
							'message'     => __( 'Post type registration was not successful ("id" "singular" and "plural" args are required).', 'custom-post-types' ) . $error_info['details'],
							'type'        => 'error',
							'dismissible' => 3,
							'admin_only'  => 'true',
							'buttons'     => false,
						);
						return $args;
					}
				);
				unset( $post_types[ $i ] );
				continue;
			}

			if ( in_array( $id, cpt_utils()->get_post_type_blacklist(), true ) ) {
				add_filter(
					'cpt_admin_notices_register',
					function ( $args ) use ( $error_info, $notice_title ) {
						$args[] = array(
							'id'          => $error_info['id'],
							'title'       => $notice_title,
							'message'     => __( 'Post type reserved or already registered, try a different "id".', 'custom-post-types' ) . $error_info['details'],
							'type'        => 'error',
							'dismissible' => 3,
							'admin_only'  => 'true',
							'buttons'     => false,
						);
						return $args;
					}
				);
				unset( $post_types[ $i ] );
				continue;
			}

			$columns = ! empty( $post_type['columns'] ) && is_array( $post_type['columns'] ) ? $post_type['columns'] : false;
			if ( $columns ) {
				$this->add_columns( $id, $columns );
			}

			$admin_only = isset( $args['admin_only'] ) ? $args['admin_only'] : false;

			$registration_labels = array_replace_recursive( $this->get_post_type_default_labels( $singular, $plural ), $labels );
			$registration_labels = apply_filters( 'cpt_post_types_register_labels', $registration_labels, $id );

			$registration_args           = array_replace_recursive( $this->get_post_type_default_args( $admin_only ), $args );
			$registration_args['labels'] = array_map( 'esc_html', $registration_labels );
			$registration_args           = apply_filters( 'cpt_post_types_register_args', $registration_args, $id );

			$register = register_post_type( $id, $registration_args );

			if ( is_wp_error( $register ) ) {
				add_filter(
					'cpt_admin_notices_register',
					function ( $args ) use ( $error_info, $notice_title ) {
						$args[] = array(
							'id'          => $error_info['id'] . '_core',
							'title'       => $notice_title,
							'message'     => __( 'Post type registration was not successful.', 'custom-post-types' ) . $error_info['details'],
							'type'        => 'error',
							'dismissible' => 3,
							'admin_only'  => 'true',
							'buttons'     => false,
						);
						return $args;
					}
				);
				unset( $post_types[ $i ] );
			}
		}

		cpt_utils()->flush_rewrite_rules( $post_types );
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
		$post_type_id        = get_post_meta( $object_id, 'id', true );
		$flush_rewrite_rules = false;
		$post_types          = $this->get_registered_post_types();
		foreach ( $post_types as $post_type ) {
			if ( $flush_rewrite_rules ) {
				break;
			}
			$id = ! empty( $post_type['id'] ) && is_string( $post_type['id'] ) ? $post_type['id'] : false;
			if ( $id == $post_type_id ) {
				$flush_rewrite_rules = true;
			}
		}
		if ( $flush_rewrite_rules ) {
			cpt_utils()->refresh_rewrite_rules( $post_type_id );
		}
	}
}
