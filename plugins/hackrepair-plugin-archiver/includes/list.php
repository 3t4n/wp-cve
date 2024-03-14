<?php

class WP_Plugins_Archive_List_Table extends WP_List_Table {

	/**
	 * Constructor.
	 *
	 * @since 3.1.0
	 * @access public
	 *
	 * @see WP_List_Table::__construct() for more information on default arguments.
	 *
	 * @param array $args An associative array of arguments.
	 */
	public function __construct( $args = array() ) {
		global $status, $page;

		parent::__construct( array(
			'plural' => 'plugins',
			'screen' => isset( $args['screen'] ) ? $args['screen'] : 'plugins',
		) );

		$status = 'all';

		if ( isset($_REQUEST['s']) )
			$_SERVER['REQUEST_URI'] = add_query_arg('s', wp_unslash($_REQUEST['s']) );

		$page = $this->get_pagenum();
	}

	protected function get_table_classes() {
		return array( 'widefat', $this->_args['plural'] );
	}

	// public function ajax_user_can() {
	// 	return false current_user_can('activate_plugins');
	// }

	public function prepare_items() {
		global $status, $plugins, $totals, $page, $orderby, $order, $s;

		wp_reset_vars( array( 'orderby', 'order', 's' ) );
        $this->process_bulk_action();

		/**
		 * Filter the full array of plugins to list in the Plugins list table.
		 *
		 * @since 3.0.0
		 *
		 * @see get_plugins()
		 *
		 * @param array $plugins An array of plugins to display in the list table.
		 */
		$dir = false;
		if ( isset( $_REQUEST['archive_dir'] ) ) {
			$dir =  WP_CONTENT_DIR . '/' . $_REQUEST['archive_dir']; 
			HackRepair_Plugin_Archiver::$options['archive_dir'] = $_REQUEST['archive_dir'];
			update_option( 'hackrepair-plugin-archiver_options', HackRepair_Plugin_Archiver::$options );
		}
		$all_plugins = HackRepair_Plugin_Archiver::get_archived_plugins($dir);
		$wprocket = isset( $all_plugins['wp-rocket/wp-rocket.php'] ); 
		$all_plugins = apply_filters( 'all_plugins', $all_plugins );
		if ( !$wprocket ) {
			unset( $all_plugins['wp-rocket/wp-rocket.php'] );
		}
		$plugins = array(
			'all' => $all_plugins,
		);

		$screen = $this->screen;

		// set_transient( 'plugin_slugs', array_keys( $plugins['all'] ), DAY_IN_SECONDS );

		// foreach ( (array) $plugins['all'] as $plugin_file => $plugin_data ) {
		// 	// Extra info if known. array_merge() ensures $plugin_data has precedence if keys collide.
		// 	if ( isset( $plugin_info->response[ $plugin_file ] ) ) {
		// 		$plugins['all'][ $plugin_file ] = $plugin_data = array_merge( (array) $plugin_info->response[ $plugin_file ], $plugin_data );
		// 		// Make sure that $plugins['upgrade'] also receives the extra info since it is used on ?plugin_status=upgrade
		// 		if ( isset( $plugins['upgrade'][ $plugin_file ] ) ) {
		// 			$plugins['upgrade'][ $plugin_file ] = $plugin_data = array_merge( (array) $plugin_info->response[ $plugin_file ], $plugin_data );
		// 		}

		// 	} elseif ( isset( $plugin_info->no_update[ $plugin_file ] ) ) {
		// 		$plugins['all'][ $plugin_file ] = $plugin_data = array_merge( (array) $plugin_info->no_update[ $plugin_file ], $plugin_data );
		// 		// Make sure that $plugins['upgrade'] also receives the extra info since it is used on ?plugin_status=upgrade
		// 		if ( isset( $plugins['upgrade'][ $plugin_file ] ) ) {
		// 			$plugins['upgrade'][ $plugin_file ] = $plugin_data = array_merge( (array) $plugin_info->no_update[ $plugin_file ], $plugin_data );
		// 		}
		// 	}

		// 	// Filter into individual sections
		// 	if ( is_multisite() && ! $screen->in_admin( 'network' ) && is_network_only_plugin( $plugin_file ) && ! is_plugin_active( $plugin_file ) ) {
		// 		// On the non-network screen, filter out network-only plugins as long as they're not individually activated
		// 		unset( $plugins['all'][ $plugin_file ] );
		// 	} elseif ( ! $screen->in_admin( 'network' ) && is_plugin_active_for_network( $plugin_file ) ) {
		// 		// On the non-network screen, filter out network activated plugins
		// 		unset( $plugins['all'][ $plugin_file ] );
		// 	} elseif ( ( ! $screen->in_admin( 'network' ) && is_plugin_active( $plugin_file ) )
		// 		|| ( $screen->in_admin( 'network' ) && is_plugin_active_for_network( $plugin_file ) ) ) {
		// 		// On the non-network screen, populate the active list with plugins that are individually activated
		// 		// On the network-admin screen, populate the active list with plugins that are network activated
		// 		$plugins['active'][ $plugin_file ] = $plugin_data;
		// 	} else {
		// 		if ( ! $screen->in_admin( 'network' ) && isset( $recently_activated[ $plugin_file ] ) ) {
		// 			// On the non-network screen, populate the recently activated list with plugins that have been recently activated
		// 			$plugins['recently_activated'][ $plugin_file ] = $plugin_data;
		// 		}
		// 		// Populate the inactive list with plugins that aren't activated
		// 		$plugins['inactive'][ $plugin_file ] = $plugin_data;
		// 	}
		// }


		if ( $s ) {
			$status = 'search';
			$plugins['search'] = array_filter( $plugins['all'], array( $this, '_search_callback' ) );
		}

		$totals = array();
		foreach ( $plugins as $type => $list )
			$totals[ $type ] = count( $list );

		if ( empty( $plugins[ $status ] ) && !in_array( $status, array( 'all', 'search' ) ) )
			$status = 'all';

		$this->items = array();
		foreach ( $plugins[ $status ] as $plugin_file => $plugin_data ) {
			// Translate, Don't Apply Markup, Sanitize HTML
			$this->items[$plugin_file] = _get_plugin_data_markup_translate( $plugin_file, $plugin_data, false, true );
		}
		$total_this_page = $totals[ $status ];

		if ( $orderby ) {
			$orderby = ucfirst( $orderby );
			$order = strtoupper( $order );

			uasort( $this->items, array( $this, '_order_callback' ) );
		}

		$plugins_per_page = $this->get_items_per_page( str_replace( '-', '_', $screen->id . '_per_page' ), 999 );

		$start = ( $page - 1 ) * $plugins_per_page;

		if ( $total_this_page > $plugins_per_page )
			$this->items = array_slice( $this->items, $start, $plugins_per_page );

		$this->set_pagination_args( array(
			'total_items' => $total_this_page,
			'per_page' => $plugins_per_page,
		) );
	}

	/**
	 * @staticvar string $term
	 * @param array $plugin
	 * @return boolean
	 */
	public function _search_callback( $plugin ) {
		static $term;
		if ( is_null( $term ) )
			$term = wp_unslash( $_REQUEST['s'] );

		foreach ( $plugin as $value ) {
			if ( false !== stripos( strip_tags( $value ), $term ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @global string $orderby
	 * @global string $order
	 * @param array $plugin_a
	 * @param array $plugin_b
	 * @return int
	 */
	public function _order_callback( $plugin_a, $plugin_b ) {
		global $orderby, $order;

		$a = $plugin_a[$orderby];
		$b = $plugin_b[$orderby];

		if ( $a == $b )
			return 0;

		if ( 'DESC' == $order )
			return ( $a < $b ) ? 1 : -1;
		else
			return ( $a < $b ) ? -1 : 1;
	}

	public function no_items() {
		global $plugins;
		_e( 'You do not appear to have any archived plugins', 'hackrepair-plugin-archiver' );
	}

	public function get_columns() {
		global $status;

		return array(
			'cb'          => !in_array( $status, array( 'mustuse', 'dropins' ) ) ? '<input type="checkbox" />' : '',
			'name'        => __( 'Plugin' ),
			'description' => __( 'Description' ),
		);
	}

	protected function get_sortable_columns() {
		return array();
	}

	protected function get_views() {
		global $totals, $status;

		$screen = $this->screen;
		$status_links = array();
		$dirs = HackRepair_Plugin_Archiver::get_archive_dirs();
		foreach ($dirs as $dir) {
			$key = $dir;
			$name = preg_replace('/^plugins\-/ims', '', $dir );
			$name = str_replace('-', ' ', $name);
			$name[0] = strtoupper($name[0]);
			$count = HackRepair_Plugin_Archiver::get_archived_plugins( WP_CONTENT_DIR.'/'.$dir );
			$count = sizeof( $count );
			// if ( 1 > $count ) {
			// 	continue;
			// }
			$link = "<a href=\"%s\" %s>%s%s</a>";
			$href = admin_url( 'plugins.php?page=hackrepair-plugin-archiver' );
			if ( $dir !== HackRepair_Plugin_Archiver::$options['archive_dir'] ) {
				$href = add_query_arg( 'archive_dir', $dir, $href );
			}
			$status_links[$key] = sprintf( $link,
				$href,
				( $dir == HackRepair_Plugin_Archiver::$options['archive_dir'] ) ? ' class="current"' : '',
				$name, 
				0 < $count ? " <span class=\"count\">(".number_format_i18n( $count ).")</span>" : ''
			);
		}
		return $status_links;
	}

	protected function get_bulk_actions() {
		$actions = array(
			'restore-selected' => __( 'Unarchive', 'hackrepair-plugin-archiver' ),
			'remove-selected'  => __( 'Delete', 'hackrepair-plugin-archiver' ),
		);
		return $actions;
	}

	/**
	 * @global string $status
	 * @param string $which
	 * @return null
	 */
	public function bulk_actions( $which = '' ) {
		global $status;

		if ( in_array( $status, array( 'mustuse', 'dropins' ) ) )
			return;

		parent::bulk_actions( $which );
	}

	/**
	 * @global string $status
	 * @param string $which
	 * @return null
	 */
	protected function extra_tablenav( $which ) {
		global $status;
		echo '<div class="alignleft actions" style="margin-top:1px;">
		    <a class="button action" href="'.admin_url( 'plugins.php?page=hackrepair-plugin-archiver&amp;action=restore-selected&amp;all=true').'">'.__( 'Unarchive All', 'hackrepair-plugin-archiver' ).'</a>
		    <a class="button action" href="'.admin_url( 'options-general.php?page=hackrepair-plugin-archiver-settings').'">'.__( 'Plugin Archiver Settings', 'hackrepair-plugin-archiver' ).'</a>
		  </div>';
	}

	public function current_action() {
		if ( isset($_POST['clear-recent-list']) )
			return 'clear-recent-list';

		return parent::current_action();
	}

	public function display_rows() {
		global $status;

		// if ( is_multisite() && ! $this->screen->in_admin( 'network' ) && in_array( $status, array( 'mustuse', 'dropins' ) ) )
		// 	return;

		foreach ( $this->items as $plugin_file => $plugin_data )
			$this->single_row( array( $plugin_file, $plugin_data ) );
	}

	/**
	 * @global string $status
	 * @global int $page
	 * @global string $s
	 * @global array $totals
	 * @param array $item
	 */
	public function single_row( $item ) {
		global $status, $page, $s, $totals;

		list( $plugin_file, $plugin_data ) = $item;
		$context = $status;
		$screen = $this->screen;

		// Pre-order.
		$actions = array(
			'restore' => '<a href="' . wp_nonce_url('plugins.php?page=hackrepair-plugin-archiver&amp;action=restore-selected&amp;checked%5B0%5D=' . $plugin_file . '&amp;paged=' . $page . '&amp;s=' . $s, 'bulk-plugins') . '" title="' . esc_attr__('Unarchive this plugin','hackrepair-plugin-archiver') . '">' . __('Unarchive', 'hackrepair-plugin-archiver') . '</a>',
			'remove' => '<a href="' . wp_nonce_url('plugins.php?page=hackrepair-plugin-archiver&amp;action=remove-selected&amp;checked%5B0%5D=' . $plugin_file . '&amp;paged=' . $page . '&amp;s=' . $s, 'bulk-plugins') . '" title="' . esc_attr__('Delete this plugin','hackrepair-plugin-archiver') . '" class="delete">' . __('Delete', 'hackrepair-plugin-archiver') . '</a>',
		);



		$prefix = $screen->in_admin( 'network' ) ? 'network_admin_' : '';

		/**
		 * Filter the action links displayed for each plugin in the Plugins list table.
		 *
		 * The dynamic portion of the hook name, `$prefix`, refers to the context the
		 * action links are displayed in. The 'network_admin_' prefix is used if the
		 * current screen is the Network plugins list table. The prefix is empty ('')
		 * if the current screen is the site plugins list table.
		 *
		 * The default action links for the Network plugins list table include
		 * 'Network Activate', 'Network Deactivate', 'Edit', and 'Delete'.
		 *
		 * The default action links for the site plugins list table include
		 * 'Activate', 'Deactivate', and 'Edit', for a network site, and
		 * 'Activate', 'Deactivate', 'Edit', and 'Delete' for a single site.
		 *
		 * @since 2.5.0
		 *
		 * @param array  $actions     An array of plugin action links.
		 * @param string $plugin_file Path to the plugin file.
		 * @param array  $plugin_data An array of plugin data.
		 * @param string $context     The plugin context. Defaults are 'All', 'Active',
		 *                            'Inactive', 'Recently Activated', 'Upgrade',
		 *                            'Must-Use', 'Drop-ins', 'Search'.
		 */
		$actions = apply_filters( $prefix . 'plugin_action_links', array_filter( $actions ), $plugin_file, $plugin_data, $context );

		/**
		 * Filter the list of action links displayed for a specific plugin.
		 *
		 * The first dynamic portion of the hook name, $prefix, refers to the context
		 * the action links are displayed in. The 'network_admin_' prefix is used if the
		 * current screen is the Network plugins list table. The prefix is empty ('')
		 * if the current screen is the site plugins list table.
		 *
		 * The second dynamic portion of the hook name, $plugin_file, refers to the path
		 * to the plugin file, relative to the plugins directory.
		 *
		 * @since 2.7.0
		 *
		 * @param array  $actions     An array of plugin action links.
		 * @param string $plugin_file Path to the plugin file.
		 * @param array  $plugin_data An array of plugin data.
		 * @param string $context     The plugin context. Defaults are 'All', 'Active',
		 *                            'Inactive', 'Recently Activated', 'Upgrade',
		 *                            'Must-Use', 'Drop-ins', 'Search'.
		 */
		$actions = apply_filters( $prefix . "plugin_action_links_$plugin_file", $actions, $plugin_file, $plugin_data, $context );

		$class = 'inactive';
		$checkbox_id =  "checkbox_" . md5($plugin_data['Name']);
		if ( in_array( $status, array( 'mustuse', 'dropins' ) ) ) {
			$checkbox = '';
		} else {
			$checkbox = "<label class='screen-reader-text' for='" . $checkbox_id . "' >" . sprintf( __( 'Select %s' ), $plugin_data['Name'] ) . "</label>"
				. "<input type='checkbox' name='checked[]' value='" . esc_attr( $plugin_file ) . "' id='" . $checkbox_id . "' />";
		}
		if ( 'dropins' != $context ) {
			$description = '<p>' . ( $plugin_data['Description'] ? $plugin_data['Description'] : '&nbsp;' ) . '</p>';
			$plugin_name = $plugin_data['Name'];
		}

		$id = sanitize_title( $plugin_name );
		if ( ! empty( $totals['upgrade'] ) && ! empty( $plugin_data['update'] ) )
			$class .= ' update';

		$plugin_slug = ( isset( $plugin_data['slug'] ) ) ? $plugin_data['slug'] : '';
		printf( "<tr id='%s' class='%s' data-slug='%s'>",
			$id,
			$class,
			$plugin_slug
		);

		list( $columns, $hidden ) = $this->get_column_info();
		// var_dump($this->get_column_info());
		// $columns = $this->get_columns();

		foreach ( $columns as $column_name => $column_display_name ) {
			$style = '';
			if ( in_array( $column_name, $hidden ) )
				$style = ' style="display:none;"';

			switch ( $column_name ) {
				case 'cb':
					echo "<th scope='row' class='check-column'>$checkbox</th>";
					break;
				case 'name':
					echo "<td class='plugin-title'$style><strong>$plugin_name</strong>";
					echo $this->row_actions( $actions, true );
					echo "</td>";
					break;
				case 'description':
					echo "<td class='column-description desc'$style>
						<div class='plugin-description'>$description</div>
						<div class='$class second plugin-version-author-uri'>";

					$plugin_meta = array();
					if ( !empty( $plugin_data['Version'] ) )
						$plugin_meta[] = sprintf( __( 'Version %s' ), $plugin_data['Version'] );
					if ( !empty( $plugin_data['Author'] ) ) {
						$author = $plugin_data['Author'];
						if ( !empty( $plugin_data['AuthorURI'] ) )
							$author = '<a href="' . $plugin_data['AuthorURI'] . '">' . $plugin_data['Author'] . '</a>';
						$plugin_meta[] = sprintf( __( 'By %s' ), $author );
					}

					// Details link using API info, if available
					if ( isset( $plugin_data['slug'] ) && current_user_can( 'install_plugins' ) ) {
						$plugin_meta[] = sprintf( '<a href="%s" class="thickbox" aria-label="%s" data-title="%s">%s</a>',
							esc_url( network_admin_url( 'plugin-install.php?tab=plugin-information&plugin=' . $plugin_data['slug'] .
								'&TB_iframe=true&width=600&height=550' ) ),
							esc_attr( sprintf( __( 'More information about %s' ), $plugin_name ) ),
							esc_attr( $plugin_name ),
							__( 'View details' )
						);
					} elseif ( ! empty( $plugin_data['PluginURI'] ) ) {
						$plugin_meta[] = sprintf( '<a href="%s">%s</a>',
							esc_url( $plugin_data['PluginURI'] ),
							__( 'Visit plugin site' )
						);
					}

					/**
					 * Filter the array of row meta for each plugin in the Plugins list table.
					 *
					 * @since 2.8.0
					 *
					 * @param array  $plugin_meta An array of the plugin's metadata,
					 *                            including the version, author,
					 *                            author URI, and plugin URI.
					 * @param string $plugin_file Path to the plugin file, relative to the plugins directory.
					 * @param array  $plugin_data An array of plugin data.
					 * @param string $status      Status of the plugin. Defaults are 'All', 'Active',
					 *                            'Inactive', 'Recently Activated', 'Upgrade', 'Must-Use',
					 *                            'Drop-ins', 'Search'.
					 */
					$plugin_meta = apply_filters( 'plugin_row_meta', $plugin_meta, $plugin_file, $plugin_data, $status );
					echo implode( ' | ', $plugin_meta );

					echo "</div></td>";
					break;
				default:
					echo "<td class='$column_name column-$column_name'$style>";

					/**
					 * Fires inside each custom column of the Plugins list table.
					 *
					 * @since 3.1.0
					 *
					 * @param string $column_name Name of the column.
					 * @param string $plugin_file Path to the plugin file.
					 * @param array  $plugin_data An array of plugin data.
					 */
					do_action( 'manage_plugins_custom_column', $column_name, $plugin_file, $plugin_data );
					echo "</td>";
			}
		}

		echo "</tr>";

		/**
		 * Fires after each row in the Plugins list table.
		 *
		 * @since 2.3.0
		 *
		 * @param string $plugin_file Path to the plugin file, relative to the plugins directory.
		 * @param array  $plugin_data An array of plugin data.
		 * @param string $status      Status of the plugin. Defaults are 'All', 'Active',
		 *                            'Inactive', 'Recently Activated', 'Upgrade', 'Must-Use',
		 *                            'Drop-ins', 'Search'.
		 */
		do_action( 'after_plugin_row', $plugin_file, $plugin_data, $status );

		/**
		 * Fires after each specific row in the Plugins list table.
		 *
		 * The dynamic portion of the hook name, `$plugin_file`, refers to the path
		 * to the plugin file, relative to the plugins directory.
		 *
		 * @since 2.7.0
		 *
		 * @param string $plugin_file Path to the plugin file, relative to the plugins directory.
		 * @param array  $plugin_data An array of plugin data.
		 * @param string $status      Status of the plugin. Defaults are 'All', 'Active',
		 *                            'Inactive', 'Recently Activated', 'Upgrade', 'Must-Use',
		 *                            'Drop-ins', 'Search'.
		 */
		do_action( "after_plugin_row_$plugin_file", $plugin_file, $plugin_data, $status );
	}

}