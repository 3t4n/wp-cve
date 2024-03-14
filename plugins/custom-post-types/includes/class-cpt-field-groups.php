<?php

defined( 'ABSPATH' ) || exit;

final class CPT_Field_Groups extends CPT_Component {
	const SUPPORT_TYPE_CPT            = 'cpt';
	const SUPPORT_TYPE_TAX            = 'tax';
	const SUPPORT_TYPE_OPTIONS        = 'options';
	const SUPPORT_TYPE_EXTRA          = 'extra';
	const SUPPORT_TYPE_EXTRA_USERS    = 'users';
	const SUPPORT_TYPE_EXTRA_MEDIA    = 'media';
	const SUPPORT_TYPE_EXTRA_COMMENTS = 'comments';
	const SUPPORT_TYPE_EXTRA_MENU     = 'menu-items';

	public $screens_with_fields = array( 'plugins' );

	/**
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'init', array( $this, 'init_field_groups' ), PHP_INT_MAX );
	}

	/**
	 * @param $field
	 *
	 * @return array|mixed
	 */
	private function sanitize_field_args( $field = array() ) {
		$field['required'] = ! empty( $field['required'] ) && 'true' == $field['required']; //phpcs:ignore Universal.Operators.StrictComparisons
		if ( ! empty( $field['extra']['options'] ) && ! is_array( $field['extra']['options'] ) ) {
			$field['extra']['options'] = cpt_utils()->get_options_from_string( $field['extra']['options'] );
		}
		foreach ( $field as $key => $value ) {
			if ( substr( $key, 0, 5 ) == 'wrap_' ) { //phpcs:ignore Universal.Operators.StrictComparisons
				if ( ! empty( $value ) ) {
					$field['wrap'][ str_replace( 'wrap_', '', $key ) ] = $value;
				}
				unset( $field[ $key ] );
			}
		}
		return $field;
	}

	/**
	 * @return array
	 */
	public function get_registered_groups() {
		$field_groups = get_posts(
			array(
				'posts_per_page' => -1,
				'post_type'      => CPT_UI_PREFIX . '_field',
				'post_status'    => 'publish',
			)
		);

		$registered_field_groups = array();

		foreach ( $field_groups as $group ) {
			$id           = ! empty( get_post_meta( $group->ID, 'id', true ) ) ? sanitize_title( get_post_meta( $group->ID, 'id', true ) ) : sanitize_title( $group->post_title );
			$label        = $group->post_title;
			$supports     = ! empty( get_post_meta( $group->ID, 'supports', true ) ) ? array_map(
				function ( $support ) {
					$type = self::SUPPORT_TYPE_CPT;
					$id   = $support;
					if ( strpos( $support, '/' ) !== false ) {
						$type = explode( '/', $support )[0];
						$id   = explode( '/', $support )[1];
					}
					return array(
						'type' => $type,
						'id'   => $id,
					);
				},
				get_post_meta( $group->ID, 'supports', true )
			) : array();
			$position     = ! empty( get_post_meta( $group->ID, 'position', true ) ) ? get_post_meta( $group->ID, 'position', true ) : 'normal';
			$order        = get_post_meta( $group->ID, 'order', true );
			$admin_only   = 'true' == get_post_meta( $group->ID, 'admin_only', true ); //phpcs:ignore Universal.Operators.StrictComparisons
			$show_in_rest = 'true' == get_post_meta( $group->ID, 'show_in_rest', true ); //phpcs:ignore Universal.Operators.StrictComparisons
			$fields       = ! empty( get_post_meta( $group->ID, 'fields', true ) ) ? array_map(
				array( $this, 'sanitize_field_args' ),
				get_post_meta( $group->ID, 'fields', true )
			) : array();

			$registered_field_groups[] = array(
				'id'           => $id,
				'label'        => $label,
				'supports'     => $supports,
				'position'     => $position,
				'order'        => $order,
				'admin_only'   => $admin_only,
				'show_in_rest' => $show_in_rest,
				'fields'       => $fields,
			);
		}

		unset( $field_groups );

		return (array) apply_filters( 'cpt_field_groups_register', $registered_field_groups );
	}

	/**
	 * @return void
	 */
	public function init_field_groups() {
		$field_groups = $this->get_registered_groups();

		foreach ( $field_groups as $i => $field_group ) {
			$id         = ! empty( $field_group['id'] ) && is_string( $field_group['id'] ) ? $field_group['id'] : false;
			$supports   = ! empty( $field_group['supports'] ) && is_array( $field_group['supports'] ) ? $field_group['supports'] : false;
			$label      = ! empty( $field_group['label'] ) ? $field_group['label'] : false;
			$admin_only = ! empty( $field_group['admin_only'] ) ? $field_group['admin_only'] : false;
			unset( $field_group['supports'], $field_group['admin_only'] );
			if (
				! cpt_utils()->is_rest() &&
				(
					( $admin_only && ! current_user_can( 'manage_options' ) ) ||
					( ! $admin_only && ! current_user_can( 'edit_posts' ) )
				)
			) {
				continue;
			}

			$notice_title = cpt_utils()->get_notices_title();
			$error_info   = cpt_utils()->get_registration_error_notice_info( $field_group, 'field' );

			if ( ! $id || ! $supports || ! $label ) {
				add_filter(
					'cpt_admin_notices_register',
					function ( $args ) use ( $error_info, $notice_title ) {
						$args[] = array(
							'id'          => $error_info['id'],
							'title'       => $notice_title,
							'message'     => __( 'Field group registration was not successful ("id" "label" and "supports" args are required).', 'custom-post-types' ) . $error_info['details'],
							'type'        => 'error',
							'dismissible' => 3,
							'admin_only'  => 'true',
							'buttons'     => false,
						);

						return $args;
					}
				);
				unset( $field_groups[ $i ] );
				continue;
			}

			foreach ( $supports as $content ) {
				$type = ! empty( $content['type'] ) ? $content['type'] : self::SUPPORT_TYPE_CPT;
				$id   = ! empty( $content['id'] ) ? $content['id'] : false;
				if ( ! $id ) {
					continue;
				}
				switch ( $type ) {
					case self::SUPPORT_TYPE_CPT:
						$this->screens_with_fields[] = $id;
						cpt_fields()->init_post_type_fields( $id, $field_group );
						break;
					case self::SUPPORT_TYPE_TAX:
						$this->screens_with_fields[] = 'edit-' . $id;
						cpt_fields()->init_taxonomy_fields( $id, $field_group );
						break;
					case self::SUPPORT_TYPE_OPTIONS:
						$core_pages = cpt_utils()->get_core_settings_pages_options();
						if ( isset( $core_pages[ $id ] ) ) {
							$this->screens_with_fields[] = 'options-' . $id;
						} else {
							$this->screens_with_fields[] = '_page_' . $id;
						}
						cpt_fields()->init_options_page_fields( $id, $field_group );
						break;
					case self::SUPPORT_TYPE_EXTRA:
						switch ( $id ) {
							case self::SUPPORT_TYPE_EXTRA_USERS:
								$this->screens_with_fields[] = 'user-edit';
								$this->screens_with_fields[] = 'profile';
								cpt_fields()->init_user_fields( $field_group );
								break;
							case self::SUPPORT_TYPE_EXTRA_MEDIA:
								$this->screens_with_fields[] = 'attachment';
								cpt_fields()->init_media_fields( $field_group );
								break;
							case self::SUPPORT_TYPE_EXTRA_COMMENTS:
								$this->screens_with_fields[] = 'comment';
								cpt_fields()->init_comment_fields( $field_group );
								break;
							case self::SUPPORT_TYPE_EXTRA_MENU:
								$this->screens_with_fields[] = 'nav-menus';
								cpt_fields()->init_menu_item_fields( $field_group );
								break;
						}
						break;
				}
			}
		}
	}

	/**
	 * @return array
	 */
	public function get_available_fields_label() {
		$options     = array();
		$field_types = cpt_fields()->get_field_types();
		foreach ( $field_types as $field_type => $field_class ) {

			$options[ $field_type ] = $field_class::get_label();
		}
		unset( $field_types );
		return $options;
	}
}
