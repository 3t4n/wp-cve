<?php
/**
 * UM Custom Tab Builder Tab.
 *
 * @since   1.0.0
 * @package UM_Custom_Tab_Builder
 */

/**
 * UM Custom Tab Builder Tab.
 *
 * @since 1.0.0
 */
class UMCTB_Tab {
	/**
	 * Parent plugin class.
	 *
	 * @since 1.0.0
	 *
	 * @var   UM_Custom_Tab_Builder
	 */
	protected $plugin = null;

	public $tabs      = array();

	public $cur_tab  = '';

	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 *
	 * @param  UM_Custom_Tab_Builder $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		if ( empty( $this->tabs ) ) {
			$this->set_tabs();
		}
		$this->hooks();
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  1.0.0
	 */
	public function hooks() {
		add_filter( 'um_profile_tabs', array( $this, 'add_custom_profile_tabs' ), 1000, 1 );
		if ( ! empty( $this->tabs ) ) {
			foreach ( $this->tabs as $slug => $tab ) {
				if ( 'profile' == $tab['type'] ) {
					add_action( 'um_profile_content_' . $slug . '_default', array( $this, 'um_profile_content_mycustomtab_default' ) );
				}
			}
		}
	}

	public function set_tabs() {
		global $wpdb;
		$results = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} WHERE post_status='publish' AND post_type='um_ctb' " );
		if ( ! empty( $results ) ) {
			foreach( $results as $row ) {
				$name        = $row->post_title;
				$slug        = get_post_meta( $row->ID, '_um_ctb_slug', true );
				$icon        = get_post_meta( $row->ID, '_um_ctb_icon', true );
				$icon        = $icon ? $icon : 'um-faicon-comments';
				$type        = get_post_meta( $row->ID, '_um_ctb_tab_type', true );
				$private     = get_post_meta( $row->ID, '_um_ctb_private', true );
				$roles_view  = get_post_meta( $row->ID, '_um_ctb_roles_view', true );
				$roles_owner = get_post_meta( $row->ID, '_um_ctb_roles_own', true );
				if ( $slug ) {
					$this->tabs[ $slug ] = array(
						'name'        => $name,
						'icon'        => $icon,
						'type'        => $type,
						'private'     => $private,
						'roles_view'  => $roles_view,
						'roles_owner' => $roles_owner,
					);
				}
			}
		}
	}
	public function add_custom_profile_tabs( $tabs ) {
		$user_id      = get_current_user_id();
		$role         = '';
		$profile_role = '';

		if ( $user_id ) {
			$role = UM()->roles()->get_priority_user_role( $user_id );
		}

		$profile_id = um_get_requested_user();
		if ( $profile_id ) {
			$profile_role = UM()->roles()->get_priority_user_role( $profile_id );
		}

		if ( ! empty( $this->tabs ) ) {
			foreach ( $this->tabs as $slug => $tab ) {
				if ( ! is_admin() ) {
					if ( 'profile' != $tab['type'] ) {
						continue;
					}
					if ( 'on' == $tab['private'] && ! um_is_user_himself() ) {
						continue;
					}

					if ( ! empty( $tab['roles_owner'] ) && $profile_role && ! in_array( $profile_role, $tab['roles_owner'] ) ) {
						continue;
					}

					if ( ! empty( $tab['roles_view'] ) && $role && ! in_array( $role, $tab['roles_view'] ) ) {
						continue;
					}
				}
				$tabs[ $slug ] = array(
					'name'   => $tab['name'],
					'icon'   => $tab['icon'],
					'custom' => true,
				);
			}
		}

		return $tabs;
	}

	public function um_profile_content_mycustomtab_default( $args ) {
		$tab = esc_attr( $_REQUEST['profiletab'] );
		$this->get_tab_content_by_slug( $tab, 'profile', um_get_requested_user() );
	}

	public function convert_short_tags( $string = '' ) {
		if ( strpos( $string, '{viewer_id}') !== false ) {
			$string = str_replace('{viewer_id}', get_current_user_id(), $string );
		}
		if ( strpos( $string, '{profile_id}') !== false ) {
			$string = str_replace('{profile_id}', um_user( 'ID' ), $string );
		}
		if ( strpos( $string, '{user_email}') !== false ) {
			$string = str_replace('{user_email}', um_user( 'user_email' ), $string );
		}
		if ( strpos( $string, '{display_name}') !== false ) {
			$string = str_replace('{display_name}', um_user( 'display_name' ), $string );
		}
		if ( strpos( $string, '{profile_photo}') !== false ) {
			$string = str_replace('{profile_photo}', um_user( 'profile_photo' ), $string );
		}
		if ( strpos( $string, '{tab_title}') !== false ) {
			$name = $this->tabs[ $this->cur_tab ]['name'];
			$string = str_replace('{tab_title}', $name, $string );
		}
		if ( strpos( $string, '{tab_slug}') !== false ) {
			$string = str_replace('{tab_slug}', $this->cur_tab, $string );
		}

		return $string;
	}
	public function get_tab_content_by_slug( $tab = '', $type = '', $object_id = 0  ) {
		global $wpdb;

		if ( ! $tab ) {
			return;
		}

		$this->cur_tab = $tab;

		$tab_details = $wpdb->get_row( $wpdb->prepare( "SELECT m1.post_id, m2.meta_value AS content_type FROM {$wpdb->postmeta} AS m1 LEFT JOIN {$wpdb->postmeta} AS m2 ON m1.post_id=m2.post_id WHERE m1.meta_key='_um_ctb_slug' AND m1.meta_value=%s AND m2.meta_key='_um_ctb_content_type'", $tab ) );

		if ( empty( $tab_details ) ) {
			return;
		}

		switch( $tab_details->content_type ) {
			case 'shortcode':
				$shortcode = get_post_meta( $tab_details->post_id, '_um_ctb_type_shortcode', true );
				if ( $shortcode ) {
					$shortcode = $this->convert_short_tags( $shortcode );

					echo apply_filters( 'the_content', $shortcode );
				}
			break;
			case 'content':
				$content = get_post_meta( $tab_details->post_id, '_um_ctb_type_content', true );
				if ( $content ) {
					$content = $this->convert_short_tags( $content );
					add_filter( 'the_content', 'wpautop' );

					$content = apply_filters( 'the_content', $content );
					remove_filter( 'the_content', 'wpautop' );
					
					add_filter( 'wp_kses_allowed_html', array( $this, 'custom_wpkses_allowed_tags' ), 10, 2 );

					echo wp_kses_post( $content );

					remove_filter( 'wp_kses_allowed_html', array( $this, 'custom_wpkses_allowed_tags' ) );
				}
			break;
		}
	}

	public function custom_wpkses_allowed_tags( $tags, $context ) {
		if ( 'post' === $context ) {
			$tags['iframe'] = array(
				'src'             => true,
				'height'          => true,
				'width'           => true,
				'frameborder'     => true,
				'allowfullscreen' => true,
			);
		}
		return $tags;
	}
	/**
	 * Returns the edit profile link
	 *
	 * @return mixed|string|void
	 */
	public function um_edit_profile_url() {
		if ( um_is_core_page( 'user' )) {
			$url = UM()->permalinks()->get_current_url();
		} else {
			$url = um_user_profile_url();
		}

		$url = remove_query_arg( 'subnav', $url );
		$url = add_query_arg( 'um_action', 'edit', $url );

		return $url;
	}

	/**
	 * boolean for profile edit page
	 *
	 * @return bool
	 */
	public function um_is_on_edit_profile() {
		if (isset( $_REQUEST['profiletab'] ) && isset( $_REQUEST['um_action'] )) {
			if ( sanitize_text_field( $_REQUEST['um_action'] ) == 'edit') {
				return true;
			}
		}

		return false;
	}
}
