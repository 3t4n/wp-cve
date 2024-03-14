<?php
/**
Plugin Name: Post to CSV by BestWebSoft
Plugin URI: https://bestwebsoft.com/products/wordpress/plugins/post-to-csv/
Description: Export WordPress posts to CSV file format easily. Configure data order.
Author: BestWebSoft
Text Domain: post-to-csv
Domain Path: /languages
Version: 1.4.1
Author URI: https://bestwebsoft.com/
License: GPLv2 or later
 */

/**
© Copyright 2021  BestWebSoft  ( https://support.bestwebsoft.com )

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if ( ! function_exists( 'psttcsv_add_admin_menu' ) ) {
	function psttcsv_add_admin_menu() {
		global $submenu, $psttcsv_plugin_info, $wp_version;
		$settings = add_menu_page( __( 'Post to CSV Settings', 'post-to-csv' ), 'Post to CSV', 'manage_options', 'post-to-csv.php', 'psttcsv_settings_page', 'none' );
		add_submenu_page( 'post-to-csv.php', __( 'Post to CSV', 'post-to-csv' ), __( 'Settings', 'post-to-csv' ), 'manage_options', 'post-to-csv.php', 'psttcsv_settings_page' );
		add_submenu_page( 'post-to-csv.php', 'BWS Panel', 'BWS Panel', 'manage_options', 'psttcsv-bws-panel', 'bws_add_menu_render' );
		add_action( 'load-' . $settings, 'psttcsv_add_tabs' );

		if ( isset( $submenu['post-to-csv.php'] ) ) {
			$submenu['post-to-csv.php'][] = array(
				'<span style="color:#d86463"> ' . __( 'Update to Pro', 'post-to-csv' ) . '</span>',
				'manage_options',
				'https://bestwebsoft.com/products/wordpress/plugins/post-to-csv/?k=82475ff9ff086c6c45ba2d49bf7d952a&pn=113&v=' . $psttcsv_plugin_info['Version'] . '&wp_v=' . $wp_version,
			);
		}
	}
}

if ( ! function_exists( 'psttcsv_plugins_loaded' ) ) {
	function psttcsv_plugins_loaded() {
		/* Internationalization */
		load_plugin_textdomain( 'post-to-csv', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

if ( ! function_exists( 'psttcsv_plugin_init' ) ) {
	function psttcsv_plugin_init() {
		global $psttcsv_plugin_info;

		require_once dirname( __FILE__ ) . '/bws_menu/bws_include.php';
		bws_include_init( plugin_basename( __FILE__ ) );

		if ( empty( $psttcsv_plugin_info ) ) {
			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			$psttcsv_plugin_info = get_plugin_data( __FILE__ );
		}

		/* Function check if plugin is compatible with current WP version */
		bws_wp_min_version_check( plugin_basename( __FILE__ ), $psttcsv_plugin_info, '4.5' );
	}
}

if ( ! function_exists( 'psttcsv_plugin_admin_init' ) ) {
	function psttcsv_plugin_admin_init() {
		global $pagenow, $psttcsv_options, $bws_plugin_info, $psttcsv_plugin_info;

		if ( empty( $bws_plugin_info ) ) {
			$bws_plugin_info = array(
				'id'      => '113',
				'version' => $psttcsv_plugin_info['Version'],
			);
		}

		if ( isset( $_GET['page'] ) && 'post-to-csv.php' === $_GET['page'] ) {
			if ( '' === session_id() ) {
				session_start();
			}
			/* Install the option defaults */
			psttcsv_register_settings();

			$is_comments_submit = isset( $_POST['psttcsv_export_submit_comments'] );
			$is_general_submit  = isset( $_POST['psttcsv_export_submit'] );
			if ( $is_general_submit || $is_comments_submit ) {
				psttcsv_print_csv();
			}
		}

		if ( 'plugins.php' === $pagenow ) {
			/* Install the option defaults */
			if ( function_exists( 'bws_plugin_banner_go_pro' ) ) {
				psttcsv_register_settings();
				bws_plugin_banner_go_pro( $psttcsv_options, $psttcsv_plugin_info, 'psttcsv', 'post-to-csv', '82475ff9ff086c6c45ba2d49bf7d952a', '113', 'post-to-csv' );
			}
		}
	}
}

/* Plugin activate */
if ( ! function_exists( 'psttcsv_plugin_activate' ) ) {
	function psttcsv_plugin_activate() {
		/* register uninstall hook */
		if ( is_multisite() ) {
			switch_to_blog( 1 );
			register_uninstall_hook( plugin_basename( __FILE__ ), 'psttcsv_plugin_uninstall' );
			restore_current_blog();
		} else {
			register_uninstall_hook( plugin_basename( __FILE__ ), 'psttcsv_plugin_uninstall' );
		}
	}
}

	/**
	 * Default options
	 */
if ( ! function_exists( 'psttcsv_get_options_default' ) ) {
	function psttcsv_get_options_default() {
		global $psttcsv_plugin_info;

		$default_options = array(
			'plugin_option_version'      => $psttcsv_plugin_info['Version'],
			'suggest_feature_banner'     => 1,
			'psttcsv_post_type'          => array( 'post' ),
			'psttcsv_taxonomy'           => array(),
			'psttcsv_fields'             => array( 'post_title' ),
			'psttcsv_status'             => array( 'publish' ),
			'psttcsv_order'              => 'post_date',
			'psttcsv_direction'          => 'asc',
			'psttcsv_delete_html'        => '0',
			'psttcsv_show_hidden_fields' => 0,
			'psttcsv_comment_fields'     => array( 'comment_author' ),
			'psttcsv_order_comment'      => 'comment_ID',
			'psttcsv_direction_comment'  => 'desc',
			'psttcsv_export_type'        => 'post_type',
		);
		return $default_options;
	}
}

if ( ! function_exists( 'psttcsv_register_settings' ) ) {
	function psttcsv_register_settings() {
		global $psttcsv_plugin_info, $psttcsv_options;

		$options_default = psttcsv_get_options_default();

		/* Install the option defaults */
		if ( ! get_option( 'psttcsv_options' ) ) {
			add_option( 'psttcsv_options', $options_default );
		}
		$psttcsv_options = get_option( 'psttcsv_options' );

		/* Array merge incase this version has added new options */
		if ( ! isset( $psttcsv_options['plugin_option_version'] ) || $psttcsv_options['plugin_option_version'] !== $psttcsv_plugin_info['Version'] ) {
			psttcsv_plugin_activate();
			$psttcsv_options                          = array_merge( $options_default, $psttcsv_options );
			$psttcsv_options['plugin_option_version'] = $psttcsv_plugin_info['Version'];
			/* show pro features */
			$psttcsv_options['hide_premium_options'] = array();

			update_option( 'psttcsv_options', $psttcsv_options );
		}
	}
}

/* Register settings function */
if ( ! function_exists( 'psttcsv_settings_page' ) ) {
	function psttcsv_settings_page() {
		if ( ! class_exists( 'Bws_Settings_Tabs' ) ) {
			require_once dirname( __FILE__ ) . '/bws_menu/class-bws-settings.php';
		}
		require_once dirname( __FILE__ ) . '/includes/class-psttcsv-settings.php';
		$page = new Psttcsv_Settings_Tabs( plugin_basename( __FILE__ ) );
		if ( method_exists( $page, 'add_request_feature' ) ) {
			$page->add_request_feature();
		} ?>
		<div class="wrap">
			<h1 class="psttcsv-title"><?php esc_html_e( 'Post to CSV Settings', 'post-to-csv' ); ?></h1>
				<noscript>
					<div class="error below-h2">
						<p><strong><?php esc_html_e( 'WARNING:', 'timesheet-pro' ); ?>
						</strong> <?php esc_html_e( 'The plugin works correctly only if JavaScript is enabled.', 'post-to-csv' ); ?>
						</p>
					</div>
				</noscript>
				<?php $page->display_content(); ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'psttcsv_print_csv' ) ) {
	function psttcsv_print_csv() {
		global $wpdb, $psttcsv_options;

		if ( isset( $_POST['psttcsv_export_submit_comments'] ) ) {
			$fields = ( ! empty( $psttcsv_options['psttcsv_comment_fields'] ) && is_array( $psttcsv_options['psttcsv_comment_fields'] ) ) ? ', `' . implode( '`, `', $psttcsv_options['psttcsv_comment_fields'] ) . '`' : '';

			/* Adjust ordering settings for comments */

			$order     = isset( $psttcsv_options['psttcsv_order_comment'] ) ? $psttcsv_options['psttcsv_order_comment'] : 'comment_ID';
			$direction = isset( $psttcsv_options['psttcsv_direction_comment'] ) ? strtoupper( $psttcsv_options['psttcsv_direction_comment'] ) : 'DESC';
			$limit     = 1000;
			$start     = 0;

			if ( in_array( 'permalink', $psttcsv_options['psttcsv_comment_fields'], true ) ) {
				$fields  = str_replace( ', `permalink`', '', $fields );
				$results = $wpdb->get_results(
					$wpdb->prepare(
						'SELECT ' . $wpdb->comments . '.`comment_ID` ' . $fields . ' , ' . $wpdb->posts . '.`guid`
						FROM ' . $wpdb->comments . ' LEFT JOIN ' . $wpdb->posts . ' ON ' . $wpdb->comments . '.`comment_post_ID` = ' . $wpdb->posts . '.`ID`
						ORDER BY `' . $order . '` ' . $direction . '
						LIMIT %d, %d', 
						$start * $limit, 
						$limit
					),
					ARRAY_A
				);
			} else {
				$results = $wpdb->get_results(
					$wpdb->prepare(
						'SELECT ' . $wpdb->comments . '.`comment_ID` ' . $fields . '
						FROM ' . $wpdb->comments . '
						ORDER BY `' . $order . '` ' . $direction . '
						LIMIT %d, %d',
						$start * $limit,
						$limit
					),
					ARRAY_A
				);
			}

			$col_array = $psttcsv_options['psttcsv_comment_fields'];
			sort( $col_array );
			$filename = tempnam( sys_get_temp_dir(), 'csv' );
			$file     = fopen( $filename, 'w' );
			fputcsv( $file, $col_array, ';' );

			while ( ! empty( $results ) ) {
				foreach ( $results as $result ) {
					unset( $result['comment_ID'] );
					if ( $psttcsv_options['psttcsv_delete_html'] && isset( $result['comment_content'] ) ) {
						$comment_content = strip_tags( $result['comment_content'] );
						if( 1 === preg_match( '/^[=+-@]|0x09|0x0D/', $comment_content ) ) {
							$result['comment_content'] = ' ' . $comment_content;
						} else {
							$result['comment_content'] = $comment_content;
						}
					}
					ksort( $result );
					fputcsv( $file, $result, ';' );
				}
				$start++;
				if ( in_array( 'permalink', $psttcsv_options['psttcsv_comment_fields'] ) ) {
					$fields  = str_replace( ', `permalink`', '', $fields );
					$results = $wpdb->get_results(
						$wpdb->prepare(
							'SELECT ' . $wpdb->comments . '.`comment_ID` ' . $fields . ' , ' . $wpdb->posts . '.`guid`
							FROM ' . $wpdb->comments . ' LEFT JOIN ' . $wpdb->posts . ' ON ' . $wpdb->comments . '.`comment_post_ID` = ' . $wpdb->posts . '.`ID`
							ORDER BY `' . $order . '` ' . $direction . '
							LIMIT %d, %d',
							$start * $limit,
							$limit
						),
						ARRAY_A
					);
				} else {
					$results = $wpdb->get_results(
						$wpdb->prepare(
							'SELECT ' . $wpdb->comments . '.`comment_ID` ' . $fields . '
							FROM ' . $wpdb->comments . '
							ORDER BY `' . $order . '` ' . $direction . '
							LIMIT %d, %d',
							$start * $limit,
							$limit
						),
						ARRAY_A
					);
				}
			}
			fclose( $file );
			header( 'Content-Type: application/octet-stream' );
			header( 'Content-Disposition: attachment; filename="comments_export.csv"' );
			/* Send file to browser */
			readfile( $filename );
			unlink( $filename );
			exit();

		} elseif ( isset( $_POST['psttcsv_export_submit'] ) ) {
			if ( ! isset( $psttcsv_options['psttcsv_fields'] ) || ! isset( $psttcsv_options['psttcsv_post_type'] ) || ! isset( $psttcsv_options['psttcsv_status'] ) ) {
				return;
			}

			$filename  = tempnam( sys_get_temp_dir(), 'csv' );
			$order     = isset( $psttcsv_options['psttcsv_order'] ) ? $psttcsv_options['psttcsv_order'] : 'post_date';
			$direction = isset( $psttcsv_options['psttcsv_direction'] ) ? strtoupper( $psttcsv_options['psttcsv_direction'] ) : 'DESC';
			$post_type = '';
			$limit     = 10000;
			$start     = 0;

			/* Write column names */
			$col_meta_key = array();
			$col_array    = $psttcsv_options['psttcsv_fields'];

			if ( in_array( 'permalink', $col_array ) ) {
				unset( $psttcsv_options['psttcsv_fields'][ array_search( 'permalink', $col_array ) ] );
			}

			$status = implode( '", "', $psttcsv_options['psttcsv_status'] );
			if ( in_array( 'attachment', $psttcsv_options['psttcsv_post_type'] ) ) {
				$status .= '", "inherit';
			}

			$fields = ( ! empty( $psttcsv_options['psttcsv_fields'] ) && is_array( $psttcsv_options['psttcsv_fields'] ) ) ? ', `' . implode( '`, `', $psttcsv_options['psttcsv_fields'] ) . '`' : '';

			$fields = str_replace( array( '`taxonomy`', '`term`' ), array( 'term_taxonomy.taxonomy', 'terms.name' ), $fields );

			if ( 'taxonomy' === $psttcsv_options['psttcsv_export_type'] && ! empty( $psttcsv_options['psttcsv_taxonomy'] ) && is_array( $psttcsv_options['psttcsv_taxonomy'] ) ) {
				/* filter by terms */
				$tax_names = array_keys( $psttcsv_options['psttcsv_taxonomy'] );
				$tax_names = implode( '", "', $tax_names );
				$terms     = array();
				foreach ( $psttcsv_options['psttcsv_taxonomy'] as $taxonomy ) {
					$terms[] = implode( '", "', $taxonomy );
				}
				$terms_names = implode( '", "', $terms );

				$results = $wpdb->get_results(
					$wpdb->prepare(
						'SELECT posts.`ID`, posts.`post_type` ' . $fields . '
						FROM ' . $wpdb->terms . ' terms                            
								INNER JOIN ' . $wpdb->term_taxonomy . ' term_taxonomy ON (terms.term_id = term_taxonomy.term_id)
								INNER JOIN ' . $wpdb->term_relationships . ' term_relationships ON (term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id)
								INNER JOIN ' . $wpdb->posts . ' posts ON (term_relationships.object_id = posts.ID)                        
						WHERE posts.`post_status` IN ("' . $status . '") AND term_taxonomy.taxonomy IN ("' . $tax_names . '") AND  terms.name IN ("' . $terms_names . '")
						ORDER BY posts.`post_type`, `' . $order . '` ' . $direction . '
						LIMIT %d, %d',
						$start * $limit,
						$limit
					),
					ARRAY_A
				);
			} elseif ( 'post_type' === $psttcsv_options['psttcsv_export_type'] ) {
				$fields = ( ! empty( $psttcsv_options['psttcsv_fields'] ) && is_array( $psttcsv_options['psttcsv_fields'] ) ) ? ', `' . implode( '`, `', $psttcsv_options['psttcsv_fields'] ) . '`' : '';
				$fields = str_replace( array( '`taxonomy`', '`term`' ), array( 'term_taxonomy.taxonomy', 'group_concat(terms.name separator \', \')' ), $fields );

				$results = $wpdb->get_results(
					$wpdb->prepare(
						'SELECT `ID`, `post_type` ' . $fields . '
						FROM ' . $wpdb->posts . ' posts
								LEFT JOIN ' . $wpdb->term_relationships . ' term_relationships ON (term_relationships.object_id = posts.ID)
								LEFT JOIN ' . $wpdb->term_taxonomy . ' term_taxonomy ON (term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id)
								LEFT JOIN ' . $wpdb->terms . ' terms ON (terms.term_id = term_taxonomy.term_id)
						WHERE `post_type` IN ("' . implode( '", "', $psttcsv_options['psttcsv_post_type'] ) . '")
								AND `post_status` IN ("' . $status . '")
						GROUP BY `ID`
						ORDER BY `post_type`, `' . $order . '` ' . $direction . '                        
						LIMIT %d, %d',
						$start * $limit,
						$limit
					),
					ARRAY_A
				);
			}
			if ( ! empty( $results ) ) {
				foreach ( $psttcsv_options['psttcsv_post_type'] as $post_type ) {
					if ( ! empty( $psttcsv_options[ 'psttcsv_meta_key_' . $post_type ] ) ) {
						$col_meta_key = array_merge( $col_meta_key, $psttcsv_options[ 'psttcsv_meta_key_' . $post_type ] );
					}
				}

				$col_meta_key = array_unique( $col_meta_key );
				$col_array    = array_merge( $col_array, $col_meta_key );

				$file = fopen( $filename, 'w' );
				fputcsv( $file, $col_array, ';' );

				foreach ( $results as $result ) {
					if ( isset( $result['post_title'] ) && 1 === preg_match( '/^[=+-@]|0x09|0x0D/', $result['post_title'] ) ) {
						$result['post_title'] = ' ' . $result['post_title'];
					}
					if ( ! empty( $col_meta_key ) ) {
						foreach ( $col_meta_key as $meta_key ) {
							if ( in_array( $meta_key, $col_array, true ) ) {
								$post_meta = get_post_meta( $result['ID'], $meta_key, true );
								if ( ! is_array( $post_meta ) && 1 === preg_match( '/^[=+-@]|0x09|0x0D/', $post_meta ) ) {
									$result[ $meta_key ] = ' ' . $post_meta;
								} else {
									$result[ $meta_key ] = $post_meta;
								}
							}
						}
					}
					if ( in_array( 'permalink', $col_array, true ) ) {
						$result['permalink'] = get_permalink( $result['ID'] );
						unset( $result['ID'] );
					} else {
						unset( $result['ID'] );
					}
					if ( in_array( 'post_author', $col_array, true ) ) {
						$user = get_userdata( $result['post_author'] ); 
						if ( 1 === preg_match( '/^[=+-@]|0x09|0x0D/', $user->display_name ) ) {
							$result['post_author'] = ' ' . $user->display_name;
						} else {
							$result['post_author'] = $user->display_name;
						}
					}
					if ( $psttcsv_options['psttcsv_delete_html'] && isset( $result['post_content'] ) ) {
						$result['post_content'] = wp_strip_all_tags( $result['post_content'] );
						if ( 1 === preg_match( '/^[=+-@]|0x09|0x0D/', $result['post_content'] ) ) {
							$result['post_content'] = ' ' . $result['post_content'];
						} else {
							$result['post_content'] = $result['post_content'];
						}
					}
					if ( '' === $post_type ) {
						$post_type = $result['post_type'];
					}
					unset( $result['post_type'] );
					fputcsv( $file, $result, ';' );
				}
				fclose( $file );
				header( 'Content-Type: application/octet-stream' );
				header( 'Content-Disposition: attachment; filename="posts_export.csv"' );
				/* Send file to browser */
				readfile( $filename );
				unlink( $filename );
				exit();
			} else {
				$_SESSION['psttcsv_error_message'] = 'no_data';
			}
		} else {
			$_SESSION['psttcsv_error_message'] = 'no_data';
		}
	}
}

/**
 * Get meta fields of post type
 */
if ( ! function_exists( 'psttcsv_get_all_meta' ) ) {
	function psttcsv_get_all_meta( $type ) {
		global $wpdb;

		if ( empty( $type ) ) {
			return array();
		}
		$result = $wpdb->get_results( $wpdb->prepare( 'SELECT DISTINCT meta_key FROM ' . $wpdb->posts . ', ' . $wpdb->postmeta . ' WHERE post_type = %s AND ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id', $type ), ARRAY_A );
		return $result;
	}
}

/* Add script and style to the dashboard */
if ( ! function_exists( 'psttcsv_dashboard_js_css' ) ) {
	function psttcsv_dashboard_js_css() {
		global $psttcsv_plugin_info;
		wp_enqueue_style( 'psttcsv_icon', plugins_url( 'css/icon.css', __FILE__ ), array(), $psttcsv_plugin_info['Version'] );

		if ( isset( $_GET['page'] ) && 'post-to-csv.php' === $_GET['page'] ) {
			wp_enqueue_style( 'psttcsv_style', plugins_url( 'css/style.css', __FILE__ ), array(), $psttcsv_plugin_info['Version'] );
			wp_enqueue_script( 'psttcsv_script', plugins_url( 'js/script.js', __FILE__ ), array( 'jquery', 'jquery-ui-accordion' ), $psttcsv_plugin_info['Version'], true );
			bws_enqueue_settings_scripts();
			bws_plugins_include_codemirror();
		}
	}
}

/* add admin notices */
if ( ! function_exists( 'psttcsv_admin_notices' ) ) {
	function psttcsv_admin_notices() {
		global $psttcsv_plugin_info;
		if ( isset( $_GET['page'] ) && 'post-to-csv.php' === $_GET['page'] ) {
			bws_plugin_suggest_feature_banner( $psttcsv_plugin_info, 'psttcsv_options', 'post-to-csv' );
		}
	}
}

if ( ! function_exists( 'psttcsv_plugin_action_links' ) ) {
	function psttcsv_plugin_action_links( $links, $file ) {
		if ( ! is_network_admin() ) {
			/* Static so we don't call plugin_basename on every plugin row. */
			static $this_plugin;
			if ( ! $this_plugin ) {
				$this_plugin = plugin_basename( __FILE__ );
			}
			if ( $file === $this_plugin ) {
				$settings_link = '<a href="admin.php?page=post-to-csv.php">' . __( 'Settings', 'post-to-csv' ) . '</a>';
				array_unshift( $links, $settings_link );
			}
		}
		return $links;
	}
}
/* End function psttcsv_plugin_action_links */

if ( ! function_exists( 'psttcsv_register_plugin_links' ) ) {
	function psttcsv_register_plugin_links( $links, $file ) {
		$base = plugin_basename( __FILE__ );
		if ( $file === $base ) {
			if ( ! is_network_admin() ) {
				$links[] = '<a href="admin.php?page=post-to-csv.php">' . __( 'Settings', 'post-to-csv' ) . '</a>';
			}
			$links[] = '<a href="https://support.bestwebsoft.com/hc/en-us/sections/200538829" target="_blank">' . __( 'FAQ', 'post-to-csv' ) . '</a>';
			$links[] = '<a href="https://support.bestwebsoft.com">' . __( 'Support', 'post-to-csv' ) . '</a>';
		}
		return $links;
	}
}

/* add help tab */
if ( ! function_exists( 'psttcsv_add_tabs' ) ) {
	function psttcsv_add_tabs() {
		$screen = get_current_screen();
		$args   = array(
			'id'      => 'psttcsv',
			'section' => '200538829',
		);
		bws_help_tab( $screen, $args );
	}
}

if ( ! function_exists( 'psttcsv_plugin_uninstall' ) ) {
	function psttcsv_plugin_uninstall() {
		global $wpdb;

		$all_plugins = get_plugins();
		if ( ! array_key_exists( 'post-to-csv-pro/post-to-csv-pro.php', $all_plugins ) ) {
			if ( function_exists( 'is_multisite' ) && is_multisite() ) {
				$old_blog = $wpdb->blogid;
				/* Get all blog ids */
				$blogids = $wpdb->get_col( "SELECT `blog_id` FROM $wpdb->blogs" );
				foreach ( $blogids as $blog_id ) {
					switch_to_blog( $blog_id );
					delete_option( 'psttcsv_options' );
				}
				switch_to_blog( $old_blog );
			} else {
				delete_option( 'psttcsv_options' );
			}
		}

		require_once dirname( __FILE__ ) . '/bws_menu/bws_include.php';
		bws_include_init( plugin_basename( __FILE__ ) );
		bws_delete_plugin( plugin_basename( __FILE__ ) );
	}
}

register_activation_hook( __FILE__, 'psttcsv_plugin_activate' );
add_action( 'admin_menu', 'psttcsv_add_admin_menu' );
add_action( 'init', 'psttcsv_plugin_init' );
add_action( 'admin_init', 'psttcsv_plugin_admin_init' );
add_action( 'plugins_loaded', 'psttcsv_plugins_loaded' );
add_action( 'admin_enqueue_scripts', 'psttcsv_dashboard_js_css' );
/* Additional links on the plugin page */
add_filter( 'plugin_action_links', 'psttcsv_plugin_action_links', 10, 2 );
add_filter( 'plugin_row_meta', 'psttcsv_register_plugin_links', 10, 2 );
/* add admin notices */
add_action( 'admin_notices', 'psttcsv_admin_notices' );