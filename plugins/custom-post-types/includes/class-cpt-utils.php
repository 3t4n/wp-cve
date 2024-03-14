<?php

defined( 'ABSPATH' ) || exit;

final class CPT_Utils {


	/**
	 * @param $name
	 *
	 * @return string
	 */
	public function get_option_name( $name = '' ) {
		return CPT_OPTIONS_PREFIX . $name;
	}

	/**
	 * @param $post_types
	 *
	 * @return void
	 */
	public function flush_rewrite_rules( $post_types = array() ) {
		$ids = array();
		foreach ( $post_types as $post_type ) {
			if ( ! empty( $post_type['id'] ) ) {
				$ids[] = $post_type['id'];
			}
		}
		if ( ! empty( $ids ) ) {
			$registered_ids         = get_option( $this->get_option_name( 'registered_cpt_ids' ), array() );
			$ids_already_registered = ! array_diff( $ids, $registered_ids );
			if ( empty( $registered_ids ) || ! $ids_already_registered ) {
				$new_registered_ids = array_merge( $registered_ids, $ids );
				update_option( $this->get_option_name( 'registered_cpt_ids' ), array_unique( $new_registered_ids ) );
				flush_rewrite_rules();
			}
		}
	}

	/**
	 * @return void
	 */
	public function refresh_rewrite_rules( $post_type_id ) {
		$registered_ids     = get_option( $this->get_option_name( 'registered_cpt_ids' ), array() );
		$new_registered_ids = array_diff( $registered_ids, array( $post_type_id ) );
		update_option( $this->get_option_name( 'registered_cpt_ids' ), $new_registered_ids );
	}

	/**
	 * @return array
	 */
	public function get_post_types_options() {
		$registered_post_types = get_post_types( array( '_builtin' => false ), 'objects' );
		$exclude               = $this->get_ui_post_types();
		$post_types            = array(
			'post' => __( 'Posts' ),
			'page' => __( 'Pages' ),
		);
		foreach ( $registered_post_types as $post_type ) {
			if ( in_array( $post_type->name, $exclude, true ) ) {
				continue;
			}
			$post_types[ $post_type->name ] = $post_type->label;
		}
		unset( $registered_post_types );
		return apply_filters( 'cpt_options_post_types', $post_types );
	}

	/**
	 * @return array
	 */
	public function get_taxonomies_options() {
		$registered_taxonomies = get_taxonomies(
			array(
				'_builtin' => false,
				'show_ui'  => true,
			),
			'objects'
		);
		$taxonomies            = array(
			'category' => __( 'Categories' ),
			'post_tag' => __( 'Tags' ),
		);
		foreach ( $registered_taxonomies as $taxonomy ) {
			$taxonomies[ $taxonomy->name ] = $taxonomy->label;
		}
		unset( $registered_taxonomies );
		return apply_filters( 'cpt_options_taxonomies', $taxonomies );
	}

	/**
	 * @return array[]
	 */
	public function get_core_settings_pages_options() {
		return array(
			'general'    => array(
				'title' => __( 'Settings' ) . ' > ' . _x( 'General', 'settings screen' ),
				'url'   => 'options-general.php',
			),
			'writing'    => array(
				'title' => __( 'Settings' ) . ' > ' . __( 'Writing' ),
				'url'   => 'options-writing.php',
			),
			'reading'    => array(
				'title' => __( 'Settings' ) . ' > ' . __( 'Reading' ),
				'url'   => 'options-reading.php',
			),
			'discussion' => array(
				'title' => __( 'Settings' ) . ' > ' . __( 'Discussion' ),
				'url'   => 'options-discussion.php',
			),
			'media'      => array(
				'title' => __( 'Settings' ) . ' > ' . __( 'Media' ),
				'url'   => 'options-media.php',
			),
		);
	}

	/**
	 * @return array[]
	 */
	public function get_settings_pages_options() {
		$pages            = $this->get_core_settings_pages_options();
		$registered_pages = cpt_admin_pages()->get_registered_admin_pages();
		$exclude          = array(
			CPT_UI_PREFIX . '_template',
			CPT_UI_PREFIX . '_admin_pages',
			CPT_UI_PREFIX . '_admin_notices',
		);
		foreach ( $registered_pages as $page ) {
			if ( in_array( $page['id'], $exclude, true ) ) {
				continue;
			}
			$pages[ $page['id'] ] = array( 'title' => $page['title'] );
		}
		return apply_filters( 'cpt_options_admin_pages', $pages );
	}

	/**
	 * @return array
	 */
	public function get_contents_options() {
		$options = array();

		$post_types = $this->get_post_types_options();
		foreach ( $post_types as $id => $label ) {
			$options[ '-- ' . __( 'Post type', 'custom-post-types' ) . ' --' ][ \CPT_Field_Groups::SUPPORT_TYPE_CPT . '/' . $id ] = $label;
		}
		unset( $post_types );

		$taxonomies = $this->get_taxonomies_options();
		foreach ( $taxonomies as $id => $label ) {
			$options[ '-- ' . __( 'Taxonomies', 'custom-post-types' ) . ' --' ][ \CPT_Field_Groups::SUPPORT_TYPE_TAX . '/' . $id ] = $label;
		}
		unset( $taxonomies );

		$settings_pages = $this->get_settings_pages_options();
		foreach ( $settings_pages as $id => $args ) {
			$options[ '-- ' . __( 'Admin pages', 'custom-post-types' ) . ' --' ][ \CPT_Field_Groups::SUPPORT_TYPE_OPTIONS . '/' . $id ] = $args['title'];
		}
		unset( $settings_pages );

		$options[ '-- ' . __( 'Extra', 'custom-post-types' ) . ' --' ] = array(
			\CPT_Field_Groups::SUPPORT_TYPE_EXTRA . '/' . \CPT_Field_Groups::SUPPORT_TYPE_EXTRA_USERS      => __( 'Users' ),
			\CPT_Field_Groups::SUPPORT_TYPE_EXTRA . '/' . \CPT_Field_Groups::SUPPORT_TYPE_EXTRA_MEDIA      => __( 'Media' ),
			\CPT_Field_Groups::SUPPORT_TYPE_EXTRA . '/' . \CPT_Field_Groups::SUPPORT_TYPE_EXTRA_COMMENTS   => __( 'Comments' ),
			\CPT_Field_Groups::SUPPORT_TYPE_EXTRA . '/' . \CPT_Field_Groups::SUPPORT_TYPE_EXTRA_MENU => __( 'Menu items' ),
		);

		return apply_filters( 'cpt_options_assignment', $options );
	}

	/**
	 * @return array
	 */
	public function get_roles_options() {
		if ( ! function_exists( 'get_editable_roles' ) ) {
			require_once ABSPATH . 'wp-admin/includes/user.php';
		}
		$registered_roles = get_editable_roles();
		$roles            = array();
		foreach ( $registered_roles as $role => $args ) {
			$roles[ $role ] = $args['name'];
		}
		unset( $registered_roles );

		return apply_filters( 'cpt_options_roles', $roles );
	}

	/**
	 * @param $post_id
	 * @param $title
	 *
	 * @return mixed|string
	 */
	public function get_post_title_with_parents( $post_id = 0, $title = '' ) {
		$post = get_post( $post_id );
		if ( 0 == $post_id || ! $post ) { //phpcs:ignore Universal.Operators.StrictComparisons
			return $title;
		}
		$title = empty( $title ) ? $post->post_title : $title;
		if ( 0 == $post->post_parent ) { //phpcs:ignore Universal.Operators.StrictComparisons
			return $title;
		}
		$title = get_the_title( $post->post_parent ) . ' > ' . $title;
		return $this->get_post_title_with_parents( $post->post_parent, $title );
	}

	/**
	 * @param $term_id
	 * @param $title
	 *
	 * @return mixed|string
	 */
	public function get_term_title_with_parents( $term_id = 0, $title = '' ) {
		$term = get_term( $term_id );
		if ( 0 == $term_id || ! $term ) { //phpcs:ignore Universal.Operators.StrictComparisons
			return $title;
		}
		$title = empty( $title ) ? $term->name : $title;
		if ( 0 == $term->parent ) { //phpcs:ignore Universal.Operators.StrictComparisons
			return $title;
		}
		$title = get_term( $term->parent )->name . ' > ' . $title;
		return $this->get_term_title_with_parents( $term->parent, $title );
	}

	/**
	 * @return bool
	 */
	public function is_rest() {
		$prefix = rest_get_url_prefix();
		if (
			defined( 'REST_REQUEST' ) &&
			REST_REQUEST ||
			isset( $_GET['rest_route'] ) &&
			0 === strpos( trim( $_GET['rest_route'], '\\/' ), $prefix )
		) {
			return true;
		}
		global $wp_rewrite;
		if ( empty( $wp_rewrite ) ) {
			$wp_rewrite = new \WP_Rewrite(); // phpcs:ignore
		}
		$rest_url    = wp_parse_url( trailingslashit( rest_url() ) );
		$current_url = wp_parse_url( add_query_arg( array() ) );
		return 0 === strpos( $current_url['path'], $rest_url['path'] );
	}

	/**
	 * @param $post_type
	 *
	 * @return array
	 */
	public function get_fields_by_post_type( $post_type = false ) {
		if ( ! $post_type || ! post_type_exists( $post_type ) ) {
			return array();
		}
		$fields = array();
		if ( post_type_supports( $post_type, 'title' ) ) {
			$fields['title'] = array( 'label' => __( 'Post title', 'custom-post-types' ) );
		}
		if ( post_type_supports( $post_type, 'editor' ) ) {
			$fields['content'] = array( 'label' => __( 'Post content', 'custom-post-types' ) );
		}
		if ( post_type_supports( $post_type, 'excerpt' ) ) {
			$fields['excerpt'] = array( 'label' => __( 'Post excerpt', 'custom-post-types' ) );
		}
		if ( post_type_supports( $post_type, 'thumbnail' ) ) {
			$fields['thumbnail'] = array( 'label' => __( 'Post image', 'custom-post-types' ) );
		}
		if ( post_type_supports( $post_type, 'author' ) ) {
			$fields['author'] = array( 'label' => __( 'Post author', 'custom-post-types' ) );
		}
		$fields['written_date']  = array( 'label' => __( 'Post date', 'custom-post-types' ) );
		$fields['modified_date'] = array( 'label' => __( 'Post modified date', 'custom-post-types' ) );
		$registered_fields       = $this->get_fields_by_supports( \CPT_Field_Groups::SUPPORT_TYPE_CPT . "/$post_type" );
		return array_merge( $fields, $registered_fields );
	}

	/**
	 * @param $taxonomy
	 *
	 * @return array
	 */
	public function get_fields_by_taxonomy( $taxonomy = false ) {
		if ( ! $taxonomy || ! taxonomy_exists( $taxonomy ) ) {
			return array();
		}
		$fields                = array();
		$fields['name']        = array( 'label' => __( 'Term name', 'custom-post-types' ) );
		$fields['description'] = array( 'label' => __( 'Term description', 'custom-post-types' ) );
		$registered_fields     = $this->get_fields_by_supports( "tax/$taxonomy" );
		return array_merge( $fields, $registered_fields );
	}

	/**
	 * @param $option
	 *
	 * @return array
	 */
	public function get_fields_by_option( $option = false ) {
		if ( ! $option ) {
			return array();
		}
		return $this->get_fields_by_supports( "options/$option" );
	}

	/**
	 * @param $extra
	 *
	 * @return array
	 */
	public function get_fields_by_extra( $extra = false ) {
		if ( ! $extra ) {
			return array();
		}
		return $this->get_fields_by_supports( "extra/$extra" );
	}

	/**
	 * @param $support
	 *
	 * @return array
	 */
	public function get_fields_by_supports( $support ) {
		$created_fields_groups = get_posts(
			array(
				'posts_per_page' => -1,
				'post_type'      => CPT_UI_PREFIX . '_field',
				'meta_query'     => array(
					array(
						'key'     => 'supports',
						'value'   => $support,
						'compare' => 'LIKE',
					),
				),
			)
		);
		$fields                = array();
		foreach ( $created_fields_groups as $created_fields_group ) {
			$fields_group_fields = get_post_meta( $created_fields_group->ID, 'fields', true );
			if ( ! empty( $fields_group_fields ) ) {
				foreach ( $fields_group_fields as $field ) {
					$fields[ $field['key'] ] = array(
						'label' => $field['label'],
						'type'  => $field['type'],
						'extra' => $field['extra'],
					);
				}
			}
		}
		return $fields;
	}

	/**
	 * @return bool
	 */
	public function is_pro_version_active() {
		return in_array( 'custom-post-types-pro/custom-post-types-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true );
	}

	/**
	 * @return string
	 */
	public function get_pro_banner() {
		$output = '<p><strong>' . __( 'This feature requires the <u>PRO version</u> and a valid license key.', 'custom-post-types' ) . '</strong></p>';
		if ( ! $this->is_pro_version_active() ) {
			$output .= sprintf(
				'<p><a href="%1$s" class="button button-primary button-hero" target="_blank" title="%2$s" aria-label="%2$s">%2$s</a></p>',
				CPT_PLUGIN_URL,
				__( 'Get PRO version', 'custom-post-types' )
			);
		}
		return '<div class="cpt-pro-banner">' . $output . '</div>';
	}

	/**
	 * @return string|null
	 */
	public function get_notices_title() {
		return __( '<strong>' . CPT_NAME . '</strong> notice:', 'custom-post-types' );
	}

	/**
	 * @param $args
	 * @param $type
	 *
	 * @return array
	 */
	public function get_registration_error_notice_info( $args = array(), $type = 'post' ) {
		$id_parts = array();
		foreach ( $args as $arg ) {
			$id_parts[] = ! empty( $arg ) ? ( is_array( $arg ) ? count( $arg ) : $arg ) : 'none';
		}
		return array(
			'id'      => $type . '_args_error_' . implode( '_', $id_parts ),
			'details' => sprintf(
				'<pre class="error-code"><a href="#" title="%1$s" aria-label="%1$s">%1$s</a><code>%2$s</code></pre>',
				__( 'See registration args', 'custom-post-types' ),
				wp_json_encode( $args, JSON_PRETTY_PRINT )
			),
		);
	}

	/**
	 * @return array
	 */
	public function get_js_variables() {
		return array(
			'js_fields_events_hook'      => 'cpt-fields-events',
			'js_fields_events_namespace' => 'custom-post-types',
			'ajax_url'                   => admin_url( 'admin-ajax.php' ),
			'ajax_nonce'                 => wp_create_nonce( CPT_NONCE_KEY ),
		);
	}

	/**
	 * @return array
	 */
	public function get_ui_post_types() {
		return array(
			CPT_UI_PREFIX,
			CPT_UI_PREFIX . '_tax',
			CPT_UI_PREFIX . '_field',
			CPT_UI_PREFIX . '_template',
			CPT_UI_PREFIX . '_page',
			CPT_UI_PREFIX . '_notice',
		);
	}

	/**
	 * @return int[]|string[]
	 */
	public function get_post_type_blacklist() {
		$registered = array_keys( get_post_types() );
		return $registered;
	}

	/**
	 * @return int[]|string[]
	 */
	public function get_taxonomies_blacklist() {
		$registered = array_keys( get_taxonomies() );
		return $registered;
	}

	/**
	 * @return array
	 */
	public function get_admin_pages_blacklist() {
		global $menu, $submenu;
		$registered = array();
		foreach ( $menu as $registered_menu ) {
			if (
				empty( $registered_menu[2] ) || // error
				strpos( $registered_menu[2], '.php' ) !== false || // core page
				( ! empty( $registered_menu[4] ) && 'wp-menu-separator' == $registered_menu[4] )  //phpcs:ignore Universal.Operators.StrictComparisons
			) {
				continue;
			}
			$registered[] = $registered_menu[2];
		}
		foreach ( $submenu as $registered_submenu ) {
			foreach ( $registered_submenu as $single_menu ) {
				if (
					empty( $single_menu[2] ) || // error
					strpos( $single_menu[2], '.php' ) !== false // core page
				) {
					continue;
				}
				$registered[] = $single_menu[2];
			}
		}
		return $registered;
	}

	/**
	 * @param $page
	 *
	 * @return bool
	 */
	public function current_user_can_access_parent_page( $page ) {
		global $_wp_submenu_nopriv;
		if ( isset( $_wp_submenu_nopriv[ $page ] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * @param $config_name
	 *
	 * @return mixed|void
	 */
	public function get_args( $config_name ) {
		$file_path = CPT_PATH . '/includes/args/' . $config_name . '.php';
		if ( file_exists( $file_path ) ) {
			return include $file_path;
		}
	}

	/**
	 * @return array
	 */
	public function get_ui_args_title_field() {
		return array(
			'key'      => 'args_title',
			'label'    => '',
			'info'     => '',
			'required' => false,
			'type'     => 'html',
			'extra'    => array(
				'content' => sprintf(
					'<h2>%s</h2>',
					__( 'Registration args', 'custom-post-types' )
				),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => 'advanced-field',
				'id'     => '',
				'layout' => '',
			),
		);
	}

	/**
	 * @return array
	 */
	public function get_ui_labels_title_field() {
		return array(
			'key'      => 'labels_title',
			'label'    => '',
			'info'     => '',
			'required' => false,
			'type'     => 'html',
			'extra'    => array(
				'content' => sprintf(
					'<h2>%s</h2>',
					__( 'Registration labels', 'custom-post-types' )
				),
			),
			'wrap'     => array(
				'width'  => '',
				'class'  => 'advanced-field',
				'id'     => '',
				'layout' => '',
			),
		);
	}

	/**
	 * @return array
	 */
	public function get_ui_advanced_switch_field() {
		return array(
			'key'      => 'advanced_fields',
			'label'    => '',
			'info'     => '',
			'required' => false,
			'type'     => 'html',
			'extra'    => array(
				'content' => sprintf(
					'<button class="button button-primary"><span class="dashicons dashicons-insert"></span><span class="label">%s</span></button>',
					__( 'Advanced view', 'custom-post-types' )
				),
			),
			'wrap'     => array(
				'width' => '',
				'class' => 'advanced-field-btn',
				'id'    => '',
			),
		);
	}

	/**
	 * @param $key
	 * @param $label
	 * @param $info
	 * @param $default_value
	 * @param $wrap_class
	 * @param $wrap_width
	 * @param $wrap_layout
	 *
	 * @return array
	 */
	public function get_ui_yesno_field( $key, $label, $info, $default_value = 'NO', $wrap_class = '', $wrap_width = '', $wrap_layout = 'horizontal' ) {
		$yes           = __( 'YES', 'custom-post-types' );
		$no            = __( 'NO', 'custom-post-types' );
		$default_label = ' - ' . __( 'Default', 'custom-post-types' );
		return array(
			'key'      => $key,
			'label'    => $label,
			'info'     => $info,
			'required' => false,
			'type'     => 'select',
			'extra'    => array(
				'placeholder' => ( 'NO' == $default_value ? $no : $yes ) . $default_label, //phpcs:ignore Universal.Operators.StrictComparisons
				'multiple'    => false,
				'options'     => array(
					'true'  => $yes . ( 'NO' == $default_value ? '' : $default_label ), //phpcs:ignore Universal.Operators.StrictComparisons
					'false' => $no . ( 'NO' == $default_value ? $default_label : '' ), //phpcs:ignore Universal.Operators.StrictComparisons
				),
			),
			'wrap'     => array(
				'width'  => $wrap_width,
				'class'  => $wrap_class,
				'id'     => '',
				'layout' => $wrap_layout,
			),
		);
	}

	/**
	 * @param $wrap_width
	 *
	 * @return array
	 */
	public function get_ui_placeholder_field( $wrap_width = '' ) {
		return array(
			'key'      => 'placeholder',
			'label'    => __( 'Placeholder', 'custom-post-types' ),
			'info'     => false,
			'required' => false,
			'type'     => 'text',
			'extra'    => array(),
			'wrap'     => array(
				'width'  => $wrap_width,
				'class'  => '',
				'id'     => '',
				'layout' => '',
			),
		);
	}

	/**
	 * @param $wrap_width
	 * @param $type
	 *
	 * @return array
	 */
	public function get_ui_min_field( $wrap_width = '', $type = 'number' ) {
		$extra = 'number' == $type ? array(
			'min'         => '0',
			'placeholder' => '0',
		) : array();
		return array(
			'key'      => 'min',
			'label'    => __( 'Min', 'custom-post-types' ),
			'info'     => false,
			'required' => false,
			'type'     => $type,
			'extra'    => $extra,
			'wrap'     => array(
				'width'  => $wrap_width,
				'class'  => '',
				'id'     => '',
				'layout' => '',
			),
		);
	}

	/**
	 * @param $wrap_width
	 * @param $type
	 *
	 * @return array
	 */
	public function get_ui_max_field( $wrap_width = '', $type = 'number' ) {
		$extra = 'number' == $type ? array( 'min' => '0' ) : array();
		return array(
			'key'      => 'max',
			'label'    => __( 'Max', 'custom-post-types' ),
			'info'     => false,
			'required' => false,
			'type'     => $type,
			'extra'    => $extra,
			'wrap'     => array(
				'width'  => $wrap_width,
				'class'  => '',
				'id'     => '',
				'layout' => '',
			),
		);
	}

	/**
	 * @param $wrap_width
	 *
	 * @return array
	 */
	public function get_ui_prepend_field( $wrap_width = '' ) {
		return array(
			'key'      => 'prepend',
			'label'    => __( 'Prepend', 'custom-post-types' ),
			'info'     => false,
			'required' => false,
			'type'     => 'text',
			'extra'    => array(),
			'wrap'     => array(
				'width'  => $wrap_width,
				'class'  => '',
				'id'     => '',
				'layout' => '',
			),
		);
	}

	/**
	 * @param $wrap_width
	 *
	 * @return array
	 */
	public function get_ui_append_field( $wrap_width = '' ) {
		return array(
			'key'      => 'append',
			'label'    => __( 'Append', 'custom-post-types' ),
			'info'     => false,
			'required' => false,
			'type'     => 'text',
			'extra'    => array(),
			'wrap'     => array(
				'width'  => $wrap_width,
				'class'  => '',
				'id'     => '',
				'layout' => '',
			),
		);
	}

	/**
	 * @param $value
	 *
	 * @return array
	 */
	public function get_options_from_string( $value = '' ) {
		$rows    = explode( PHP_EOL, $value );
		$options = array();
		foreach ( $rows as $row ) {
			if ( strpos( $row, '|' ) !== false ) {
				$options[ trim( explode( '|', $row )[0] ) ] = trim( explode( '|', $row )[1] );
			} else {
				$options[ trim( $row ) ] = trim( $row );
			}
		}
		return $options;
	}
}
