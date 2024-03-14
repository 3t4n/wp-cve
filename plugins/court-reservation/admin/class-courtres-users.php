<?php

/**
 * The admin-specific functionality for users.
 *
 * @link       https://www.webmuehle.at
 * @since      1.1.0
 *
 * @package    Courtres
 * @subpackage Courtres/admin
 */

/**
 * The admin-specific functionality for users..
 *
 * @package    Courtres
 * @subpackage Courtres/admin
 * @author     Webmühle e.U. <office@webmuehle.at>
 */

class CR_Users_List_Table extends WP_Users_List_Table {


	/**
	 * Prepare the users list for display.
	 *
	 * @since 3.1.0
	 *
	 * @global string $role
	 * @global string $usersearch
	 */
	public function prepare_items() {
		global $role, $usersearch;

		if ( isset( $_REQUEST['s'] ) ) {
			$req_s      = sanitize_text_field( $_REQUEST['s'] );
			$usersearch = wp_unslash( trim( $req_s ) );
		} else {
			$usersearch = '';
		}

		// 27.01.2019, astoian
		// only for "player" role
		$role = 'player'; // isset( $_REQUEST['role'] ) ? $_REQUEST['role'] : '';

		$per_page = ( $this->is_site_users ) ? 'site_users_network_per_page' : 'users_per_page';
		// print_r('per_page - '.$per_page);
		$users_per_page = $this->get_items_per_page( $per_page );
		// print_r('users_per_page - '.$users_per_page);

		$paged = $this->get_pagenum();

		if ( 'none' === $role ) {
			$args = array(
				'number'  => $users_per_page,
				'offset'  => ( $paged - 1 ) * $users_per_page,
				'include' => wp_get_users_with_no_role( $this->site_id ),
				'search'  => $usersearch,
				'fields'  => 'all_with_meta',
			);
		} else {
			$args = array(
				'number' => $users_per_page,
				'offset' => ( $paged - 1 ) * $users_per_page,
				'role'   => $role,
				'search' => $usersearch,
				'fields' => 'all_with_meta',
			);
		}

		if ( '' !== $args['search'] ) {
			$args['search'] = '*' . $args['search'] . '*';
		}

		if ( $this->is_site_users ) {
			$args['blog_id'] = $this->site_id;
		}

		$args['orderby'] = 'username';
		if ( isset( $_REQUEST['orderby'] ) ) {
			$args['orderby'] = sanitize_text_field( $_REQUEST['orderby'] );
		}

		$args['order'] = 'asc'; // 27.01.2019, astoian
		if ( isset( $_REQUEST['order'] ) ) {
			$args['order'] = sanitize_text_field( $_REQUEST['order'] );
		}

		/**
		 * Filters the query arguments used to retrieve users for the current users list table.
		 *
		 * @since 4.4.0
		 *
		 * @param array $args Arguments passed to WP_User_Query to retrieve items for the current
		 *                    users list table.
		 */
		$args = apply_filters( 'users_list_table_query_args', $args );

		// Query the user IDs for this page
		$wp_user_search = new WP_User_Query( $args );

		$this->items = $wp_user_search->get_results();

		$this->set_pagination_args(
			array(
				'total_items' => $wp_user_search->get_total(),
				'per_page'    => $users_per_page,
			)
		);
	}

	/**
	 * Generate HTML for a single row on the users.php admin panel.
	 *
	 * @since 3.1.0
	 * @since 4.2.0 The `$style` parameter was deprecated.
	 * @since 4.4.0 The `$role` parameter was deprecated.
	 *
	 * @param WP_User $user_object The current user object.
	 * @param string  $style       Deprecated. Not used.
	 * @param string  $role        Deprecated. Not used.
	 * @param int     $numposts    Optional. Post count to display for this user. Defaults
	 *                             to zero, as in, a new user has made zero posts.
	 * @return string Output for a single row.
	 */
	public function single_row( $user_object, $style = '', $role = '', $numposts = 0 ) {
		if ( ! ( $user_object instanceof WP_User ) ) {
			$user_object = get_userdata( (int) $user_object );
		}
		$user_object->filter = 'display';
		$email               = $user_object->user_email;

		if ( $this->is_site_users ) {
			$url = "site-users.php?id={$this->site_id}&amp;";
		} else {
			$url = 'users.php?';
		}

		$user_roles = $this->get_role_list( $user_object );
		// if ( !in_array( 'player', (array) $user_object->roles ) ) {
		// The user has no the "player" role
		// return;
		// }

		// Set up the hover actions for this user
		$actions     = array();
		$checkbox    = '';
		$super_admin = '';

		if ( is_multisite() && current_user_can( 'manage_network_users' ) ) {
			if ( in_array( $user_object->user_login, get_super_admins(), true ) ) {
				$super_admin = ' &mdash; ' . __( 'Super Admin' );
			}
		}

		// Check if the user for this row is editable
		if ( current_user_can( 'list_users' ) ) {
			// Set up the user editing link
			$edit_link = sanitize_url( add_query_arg( 'wp_http_referer', urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ), get_edit_user_link( $user_object->ID ) ) );

			if ( current_user_can( 'edit_user', $user_object->ID ) ) {
				$edit            = "<strong><a href=\"{$edit_link}\">{$user_object->user_login}</a>{$super_admin}</strong><br />";
				$actions['edit'] = '<a href="' . $edit_link . '">' . __( 'Edit' ) . '</a>';
			} else {
				$edit = "<strong>{$user_object->user_login}{$super_admin}</strong><br />";
			}

			if ( ! is_multisite() && get_current_user_id() != $user_object->ID && current_user_can( 'delete_user', $user_object->ID ) ) {
				$actions['delete'] = "<a class='submitdelete' href='" . wp_nonce_url( "users.php?action=delete&amp;user=$user_object->ID", 'bulk-users' ) . "'>" . __( 'Delete' ) . '</a>';
			}

			if ( is_multisite() && get_current_user_id() != $user_object->ID && current_user_can( 'remove_user', $user_object->ID ) ) {
				$actions['remove'] = "<a class='submitdelete' href='" . wp_nonce_url( $url . "action=remove&amp;user=$user_object->ID", 'bulk-users' ) . "'>" . __( 'Remove' ) . '</a>';
			}

			// Add a link to the user's author archive, if not empty.
			$author_posts_url = get_author_posts_url( $user_object->ID );
			if ( $author_posts_url ) {
				$actions['view'] = sprintf(
					'<a href="%s" aria-label="%s">%s</a>',
					esc_url( $author_posts_url ),
					/* translators: %s: author's display name */
					esc_attr( sprintf( __( 'View posts by %s' ), $user_object->display_name ) ),
					__( 'View' )
				);
			}

			/**
			 * Filters the action links displayed under each user in the Users list table.
			 *
			 * @since 2.8.0
			 *
			 * @param array   $actions     An array of action links to be displayed.
			 *                             Default 'Edit', 'Delete' for single site, and
			 *                             'Edit', 'Remove' for Multisite.
			 * @param WP_User $user_object WP_User object for the currently-listed user.
			 */
			$actions = apply_filters( 'user_row_actions', $actions, $user_object );

			// Role classes.
			$role_classes = esc_attr( implode( ' ', array_keys( $user_roles ) ) );

			// Set up the checkbox ( because the user is editable, otherwise it's empty )
			$checkbox = '<label class="screen-reader-text" for="user_' . $user_object->ID . '">' . sprintf( __( 'Select %s' ), $user_object->user_login ) . '</label>'
				. "<input type='checkbox' name='users[]' id='user_{$user_object->ID}' class='{$role_classes}' value='{$user_object->ID}' />";

		} else {
			$edit = "<strong>{$user_object->user_login}{$super_admin}</strong>";
		}
		$avatar = get_avatar( $user_object->ID, 32 );

		// Comma-separated list of user roles.
		$roles_list = implode( ', ', $user_roles );
		// print_r('step3');

		$r = "<tr id='user-$user_object->ID'>";

		list($columns, $hidden, $sortable, $primary) = $this->get_column_info();
		// print_r(count($this->get_column_info()));

		foreach ( $columns as $column_name => $column_display_name ) {
			$classes = "$column_name column-$column_name";
			if ( $primary === $column_name ) {
				$classes .= ' has-row-actions column-primary';
			}
			if ( 'posts' === $column_name ) {
				$classes .= ' num'; // Special case for that column
			}

			if ( in_array( $column_name, $hidden ) ) {
				$classes .= ' hidden';
			}

			$data = 'data-colname="' . wp_strip_all_tags( $column_display_name ) . '"';

			$attributes = "class='$classes' $data";

			if ( 'cb' === $column_name ) {
				$r .= "<th scope='row' class='check-column'>$checkbox</th>";
			} else {
				$r .= "<td $attributes>";
				switch ( $column_name ) {
					case 'username':
						$r .= "$avatar $edit";
						break;
					case 'name':
						if ( $user_object->first_name && $user_object->last_name ) {
							$r .= "$user_object->first_name $user_object->last_name";
						} elseif ( $user_object->first_name ) {
							$r .= $user_object->first_name;
						} elseif ( $user_object->last_name ) {
							$r .= $user_object->last_name;
						} else {
							$r .= '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . _x( 'Unknown', 'name' ) . '</span>';
						}
						break;
					case 'email':
						$r .= "<a href='" . esc_url( "mailto:$email" ) . "'>$email</a>";
						break;
					case 'role':
						$r .= esc_html( $roles_list );
						break;
					case 'posts':
						if ( $numposts > 0 ) {
							$r .= "<a href='edit.php?author=$user_object->ID' class='edit'>";
							$r .= '<span aria-hidden="true">' . $numposts . '</span>';
							$r .= '<span class="screen-reader-text">' . sprintf( _n( '%s post by this author', '%s posts by this author', $numposts ), number_format_i18n( $numposts ) ) . '</span>';
							$r .= '</a>';
						} else {
							$r .= 0;
						}
						break;
					default:
						/**
						 * Filters the display output of custom columns in the Users list table.
						 *
						 * @since 2.8.0
						 *
						 * @param string $output      Custom column output. Default empty.
						 * @param string $column_name Column name.
						 * @param int    $user_id     ID of the currently-listed user.
						 */
						$r .= apply_filters( 'manage_users_custom_column', '', $column_name, $user_object->ID );
				}

				if ( $primary === $column_name ) {
					$r .= $this->row_actions( $actions );
				}
				$r .= '</td>';
			}
		}
		$r .= '</tr>';

		return $r;
	}

	protected function get_column_info() {
		return array(
			$this->get_columns(),
			array(),
			array(),
			'user',
		);
	}

	/**
	 * Get a list of columns for the list table.
	 *
	 * @since  3.1.0
	 *
	 * @return array Array in which the key is the ID of the column,
	 *               and the value is the description.
	 */
	public function get_columns() {
		$c = array(
			// 'cb' => '<input type="checkbox" />',
			'username' => __( 'Username' ),
			'name'     => __( 'Name' ),
			'email'    => __( 'Email' ),
		);

		if ( $this->is_site_users ) {
			unset( $c['posts'] );
		}

		return $c;
	}

	/**
	 * Get a list of sortable columns for the list table.
	 *
	 * @since 3.1.0
	 *
	 * @return array Array of sortable columns.
	 */
	protected function get_sortable_columns() {
		$c = array(
			'username' => 'login',
			'email'    => 'email',
		);

		return $c;
	}

	/**
	 * Generate the table navigation above or below the table
	 *
	 * @since 3.1.0
	 * @param string $which
	 */
	protected function display_tablenav( $which ) {
		if ( 'top' === $which ) {
			wp_nonce_field( 'bulk-' . $this->_args['plural'] );
		}
		?>
		<div class="tablenav <?php echo esc_attr( $which ); ?>">
	
			<?php if ( $this->has_items() ) : ?>
			<div class="alignleft actions bulkactions">
				<?php // $this->bulk_actions( $which ); ?>
			</div>
				<?php
			endif;
			$this->extra_tablenav( $which );
			$this->pagination( $which );
			?>
	
			<br class="clear" />
		</div>
		<?php
	}

	/**
	 * Output the controls to allow user roles to be changed in bulk.
	 *
	 * @since 3.1.0
	 *
	 * @param string $which Whether this is being invoked above ("top")
	 *                      or below the table ("bottom").
	 */
	protected function extra_tablenav( $which ) {
		return '';
	}
}
