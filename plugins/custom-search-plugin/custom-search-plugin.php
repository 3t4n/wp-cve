<?php
/*
Plugin Name: Custom Search by BestWebSoft
Plugin URI: https://bestwebsoft.com/products/wordpress/plugins/custom-search/
Description: Add custom post types to WordPress website search results.
Author: BestWebSoft
Text Domain: custom-search-plugin
Domain Path: /languages
Version: 1.49
Author URI: https://bestwebsoft.com/
License: GPLv2 or later
*/

/*  © Copyright 2021  BestWebSoft  ( https://support.bestwebsoft.com )

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

/* Function are using to add on admin-panel Wordpress page 'bws_panel' and sub-page of this plugin */
if ( ! function_exists( 'add_cstmsrch_admin_menu' ) ) {
	function add_cstmsrch_admin_menu() {
		global $submenu, $wp_version, $cstmsrch_plugin_info;

		if ( ! is_plugin_active( 'custom-search-pro/custom-search-pro.php' ) ) {
			$settings = add_menu_page( __( 'Custom Search Settings', 'custom-search-plugin' ), 'Custom Search', 'manage_options', 'custom_search.php', 'cstmsrch_settings_page', 'none' );

			add_submenu_page( 'custom_search.php', __( 'Custom Search Settings', 'custom-search-plugin' ), __( 'Settings', 'custom-search-plugin'), 'manage_options', 'custom_search.php', 'cstmsrch_settings_page' );

			add_submenu_page( 'custom_search.php', 'BWS Panel', 'BWS Panel', 'manage_options', 'cstmsrch-bws-panel', 'bws_add_menu_render' );

			add_action( 'load-' . $settings, 'cstmsrch_add_tabs' );
		}

		if ( isset( $submenu['custom_search.php'] ) ) {
			$submenu['custom_search.php'][] = array(
				'<span style="color:#d86463"> ' . __( 'Upgrade to Pro', 'custom-search-plugin' ) . '</span>',
				'manage_options',
				'https://bestwebsoft.com/products/wordpress/plugins/custom-search/?k=f9558d294313c75b964f5f6fa1e5fd3cc&pn=81&v=' . $cstmsrch_plugin_info["Version"] . '&wp_v=' . $wp_version );
		}
	}
}

if ( ! function_exists( 'cstmsrch_plugins_loaded' ) ) {
	function cstmsrch_plugins_loaded() {
		/* Function adds translations in this plugin */
		load_plugin_textdomain( 'custom-search-plugin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

if ( ! function_exists ( 'cstmsrch_init' ) ) {
	function cstmsrch_init() {
		global $cstmsrch_plugin_info;

		require_once( dirname( __FILE__ ) . '/bws_menu/bws_include.php' );
		bws_include_init( plugin_basename( __FILE__ ) );

		$is_admin = ( is_admin() && ! ( defined( 'DOING_AJAX' ) && ! isset( $_REQUEST['pagenow'] ) ) );
		if ( ! $is_admin ) {
			add_filter( 'pre_get_posts', 'cstmsrch_searchfilter' );
			add_filter( 'posts_join', 'cstmsrch_posts_join' );
			add_filter( 'posts_groupby', 'cstmsrch_posts_groupby' );
			add_filter( 'posts_where', 'cstmsrch_posts_where_tax' );

			if ( is_plugin_active( 'multilanguage-pro/multilanguage-pro.php' ) || is_plugin_active( 'multilanguage/multilanguage.php' ) ) {
				add_filter( 'posts_clauses', 'cstmsrch_multilanguage_tax' );
			}
		}

		if ( empty( $cstmsrch_plugin_info ) ) {
			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}
			$cstmsrch_plugin_info = get_plugin_data( __FILE__ );
		}

		if ( ! is_admin() || ( isset( $_GET['page'] ) && 'custom_search.php' == $_GET['page'] ) ) {
			register_cstmsrch_settings();
		}
		/* Function check if plugin is compatible with current WP version */
		bws_wp_min_version_check( plugin_basename( __FILE__ ), $cstmsrch_plugin_info, '4.5' );
	}
}

if ( ! function_exists( 'cstmsrch_admin_init' ) ) {
	function cstmsrch_admin_init() {
		global $bws_plugin_info, $cstmsrch_plugin_info, $pagenow, $cstmsrch_options;
		if ( empty( $bws_plugin_info ) ) {
			$bws_plugin_info = array( 'id' => '81', 'version' => $cstmsrch_plugin_info['Version'] );
		}
		if ( 'plugins.php' == $pagenow ) {
			/* Install the option defaults */
			if ( function_exists( 'bws_plugin_banner_go_pro' ) ) {
				register_cstmsrch_settings();
				bws_plugin_banner_go_pro( $cstmsrch_options, $cstmsrch_plugin_info, 'cstmsrch', 'custom-search-plugin', '22f95b30aa812b6190a4a5a476b6b628', '214', 'custom-search-plugin' );
			}
		}		
	}
}

if ( ! function_exists( 'cstmsrch_default_options' ) ) {
	function cstmsrch_default_options() {
		global $cstmsrch_plugin_info;

		$cstmsrch_options_default = array(
			'plugin_option_version'		=> $cstmsrch_plugin_info['Version'],
			'output_order'				=> array(
				'post_type_post' => array( 'name' => 'post', 'type' => 'post_type', 'enabled' => 1 ),
				'post_type_page' => array( 'name' => 'page', 'type' => 'post_type', 'enabled' => 1 ),
			),
			'first_install'				=> strtotime( "now" ),
			'display_settings_notice'	=> 1,
			'suggest_feature_banner'	=> 1,
			'fields' 					=> array(),
			'show_hidden_fields'		=> 0,
			'show_tabs_post_type'		=> 0,
		);

		return $cstmsrch_options_default;
	}
}

/**
 * Update plugin options
 * if custom post types was added or deleted
 * @return void
 */

if ( ! function_exists( 'cstmsrch_add_menu_search_header' ) ) {
	function cstmsrch_add_menu_search_header() {
			global $cstmsrch_options, $wpdb, $cstmsrch_post_types_enabled, $wp_query, $cstmsrch_taxonomies_enabled;

			$search = get_search_query();
			if ( isset( $cstmsrch_options['show_tabs_post_type'] ) && 1 === $cstmsrch_options['show_tabs_post_type'] && '' !== $search && is_search() ) {
				$form = '<form action="'. esc_url( home_url( '/?s=' . get_search_query()  ) ) . '" method="get" class="cstmsrch-submit-type">';
				$form .= '<input type="submit" name="cstmsrch_submit_all_type" value="' . __( "all", "custom-search" ) . '"/>';
				if ( ! empty( $cstmsrch_taxonomies_enabled ) ){
					foreach ( $cstmsrch_taxonomies_enabled as $taxonomy ) {
						$taxonomies[] = "'" . esc_sql( $taxonomy ) . "'";
					}
					if ( ! empty( $taxonomies ) ) {
						$taxonomies = implode( ',', $taxonomies );
					}
					$taxonomies_value = " AND tt.taxonomy IN ( " . $taxonomies . " )";
				}else{
					$taxonomies_value = "";
				}
				$cusfields_sql_request = "'" . implode( "', '", $cstmsrch_options['fields'] ) . "'";
					
				$form .= '<input type="hidden" name="s" value="' . $search . '"/>';
				
				remove_filter( 'pre_get_posts', 'cstmsrch_searchfilter' );

				$sql =  "SELECT {$wpdb->posts}.`post_type` FROM {$wpdb->posts} JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id  LEFT JOIN {$wpdb->term_relationships} tr ON {$wpdb->posts}.ID = tr.object_id LEFT JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id=tr.term_taxonomy_id LEFT JOIN {$wpdb->terms} t ON t.term_id = tt.term_id  WHERE ( 1=1 ";
				$search_trim = explode( ' ', $search );
				foreach ( $search_trim as $value ) {
					$sql .= "AND (({$wpdb->posts}.post_title LIKE '%". $value ."%') OR ({$wpdb->posts}.post_excerpt LIKE '%". $value ."%') OR ({$wpdb->posts}.post_content LIKE '%". $value ."%'))";
				}
				$sql .=")  AND ({$wpdb->posts}.post_status = 'publish' OR {$wpdb->posts}.post_status = 'private') OR ( {$wpdb->postmeta}.meta_key IN ( ". $cusfields_sql_request ." ) ";
				foreach ( $search_trim as $value ) {
					$sql .= "AND {$wpdb->postmeta}.meta_value LIKE '%". $value ."%' ";
				}
				$sql .="AND ({$wpdb->posts}.post_status = 'publish' OR {$wpdb->posts}.post_status = 'private') )  OR ( t.name LIKE '%". $search ."%'". $taxonomies_value." AND {$wpdb->posts}.post_status = 'publish' ) GROUP BY {$wpdb->posts}.post_type";
				  
				$post_type = $wpdb->get_results( $sql, ARRAY_A );
				
				foreach ( $post_type as $post_type_value ) {
					foreach ( $post_type_value  as $value ) {
						if ( in_array( $value, $cstmsrch_post_types_enabled ) ) {
							$form .= '<input type="submit" name="cstmsrch_submit_post_type" value="' . $value . '"/>';
						}
					}
					
				}
				$form .= '</form>';
				echo $form;	
			}
	}
}

if ( ! function_exists( 'cstmsrch_scripts' ) ) {
	function cstmsrch_scripts() {
		if ( ! is_admin() ) {
			wp_enqueue_style( 'cstmsrch_stylesheet', plugins_url( 'css/style.css', __FILE__ ) );
			wp_enqueue_script( 'cstmsrch_script', plugins_url( 'js/script.js', __FILE__ ), array( 'jquery', 'jquery-ui-sortable' ) );
		}
	}
}

if ( ! function_exists( 'cstmsrch_update_option' ) ) {
	function cstmsrch_update_option( $options, $option_changed = false ) {
		global $cstmsrch_options, $taxonomies_global;

		/* get custom post types */
		$post_types_global = get_post_types( array( 'public' => true ), 'names' );
		$taxonomies_global = get_taxonomies( array( 'public' => true ), 'names' );
		unset( $post_types_global['attachment'] );
		unset( $taxonomies_global['post_format'] );
		$order_items_keys = array();
		/* unsetting non-existent post types/taxonomies */
		foreach ( $options['output_order'] as $key => $item ) {
			if (
				empty( $item['name'] ) || /* removing wrong items */
				! ( in_array( $item['name'], $post_types_global ) || in_array( $item['name'], $taxonomies_global ) ) || /* removing outdated items */
				in_array( $item['name'], $order_items_keys ) /* removing duplicating items */
			) {
				$option_changed = true;
				unset( $options['output_order'][ $key ] );
			} else {
				if ( ! isset( $item['enabled'] ) ) $options['output_order'][ $key ]['enabled'] = 0;
				$order_items_keys[ $item['name'] ] = $item['name'];
			}
		}

		/* adding new post types/taxonomies to order list */
		foreach ( $post_types_global as $key => $post_type ) {
			if ( ! in_array( $post_type, $order_items_keys ) ) {
				$options['output_order'][] = array (
					'name'		=> $post_type,
					'type'		=> 'post_type',
					'enabled'	=> 0
				);
				$option_changed = true;
			}
		}
		foreach ( $taxonomies_global as $taxonomy => $taxonomy_object ) {
			if ( ! in_array( $taxonomy, $order_items_keys ) ) {
				$options['output_order'][] = array (
					'name'		=> $taxonomy,
					'type'		=> 'taxonomy',
					'enabled'	=> 0
				);
				$option_changed = true;
			}
		}

		if ( $option_changed ) {
			$options['output_order'] = array_values( $options['output_order'] );
			$cstmsrch_options = $options;
			update_option( 'cstmsrch_options', $cstmsrch_options );
			cstmsrch_search_objects();
		}
		return $options;
	}
}

/* Function create column in table wp_options for option of this plugin. If this column exists - save value in variable. */
if ( ! function_exists( 'register_cstmsrch_settings' ) ) {
	function register_cstmsrch_settings() {
		global $cstmsrch_options, $bws_plugin_info, $cstmsrch_plugin_info, $cstmsrch_is_registered, $taxonomies_global;

		$cstmsrch_is_registered = true;
		$cstmsrch_options_default = cstmsrch_default_options();

		/* Install the option defaults */
		if ( ! get_option( 'cstmsrch_options' ) ) {
			add_option( 'cstmsrch_options', $cstmsrch_options_default );
		}

		$cstmsrch_options = get_option( 'cstmsrch_options' );
		/* Array merge incase this version has added new options */
		if ( ! isset( $cstmsrch_options['plugin_option_version'] ) || $cstmsrch_options['plugin_option_version'] != $cstmsrch_plugin_info['Version'] ) {

			$cstmfldssrch_options = get_option( 'cstmfldssrch_options' );

			if ( $cstmfldssrch_options ) {
				if ( ! isset( $cstmsrch_options['fields'] ) && ! empty( $cstmfldssrch_options['fields'] ) ) {
					$cstmsrch_options['fields'] = $cstmfldssrch_options['fields'];
				}
				if ( ! isset( $cstmsrch_options['show_hidden_fields'] ) && ! empty( $cstmfldssrch_options['show_hidden_fields'] ) ) {
					$cstmsrch_options['show_hidden_fields'] = $cstmfldssrch_options['show_hidden_fields'];
				}
			}

			$post_types_global= get_post_types( array( 'public' => true ), 'names' );
			unset( $post_types_global['attachment'] );
			$cstmsrch_post_types_enabled = array( 'post', 'page' );

			if ( ! empty( $_REQUEST['cstmsrch_post_types'] ) && is_array( $_REQUEST['cstmsrch_post_types'] ) ) {
				foreach ( $_REQUEST['cstmsrch_post_types'] as $post_type ) {
					if ( in_array( $post_type, $post_types_custom ) ) {
						$cstmsrch_post_types_enabled[] = $post_type;
					}
				}
			}
			$cstmsrch_taxonomies_enabled = array();
			if ( ! empty( $_REQUEST['cstmsrch_taxonomies'] ) && is_array( $_REQUEST['cstmsrch_taxonomies'] ) ) {
				foreach ( $_REQUEST['cstmsrch_taxonomies'] as $taxonomy ) {
					if ( in_array( $taxonomy, $taxonomies_global ) ) {
						$cstmsrch_taxonomies_enabled[] = $taxonomy;
					}
				}
			}

			$output_order = array();
			foreach ( $post_types_global as $post_type ) {
				$enabled = ( in_array( $post_type, $cstmsrch_post_types_enabled ) ) ? 1 : 0;
				$output_order[ 'post_type_' . $post_type ] = array(
					'name'		=> $post_type,
					'type'		=> 'post_type',
					'enabled'	=> $enabled
				);
			}
			if ( isset( $taxonomies_global ) && is_array( $taxonomies_global ) ) {
				foreach ( $taxonomies_global as $taxonomy ) {
				$enabled = ( in_array( $taxonomy, $cstmsrch_taxonomies_enabled ) ) ? 1 : 0;
				$output_order[ 'taxonomy_' . $taxonomy ] = array(
					'name'		=> $taxonomy,
					'type'		=> 'taxonomy',
					'enabled'	=> $enabled
				);
				}
			}

			foreach ( $cstmsrch_options_default as $key => $value ) {
				if (
					! isset( $cstmsrch_options[ $key ] ) ||
					( isset( $cstmsrch_options[ $key ] ) && is_array( $cstmsrch_options_default[ $key ] ) && ! is_array( $cstmsrch_options[ $key ] ) )
				) {
					if ( ! isset( $cstmsrch_options['fields'] ) ) {
						$cstmsrch_options_array = $cstmsrch_options;
						unset( $cstmsrch_options_array['plugin_option_version'] );
						$cstmsrch_options = array( 'fields' => $cstmsrch_options_array );
					}
					$cstmsrch_options[ $key ] = $cstmsrch_options_default[ $key ];
				} else {
					if ( is_array( $cstmsrch_options_default[ $key ] ) ) {
						foreach ( $cstmsrch_options_default[ $key ] as $key2 => $value2 ) {
							if ( ! isset( $cstmsrch_options[ $key ][ $key2 ] ) ) {
								$cstmsrch_options[ $key ][ $key2 ] = $cstmsrch_options_default[ $key ][ $key2 ];
							}
						}
					}
				}
			}

			$cstmsrch_options['plugin_option_version'] = $cstmsrch_plugin_info['Version'];
			/* show pro features */
			$cstmsrch_options['hide_premium_options'] = array();
			$cstmsrch_options = cstmsrch_update_option( $cstmsrch_options, true );
			cstmsrch_plugin_activate();
		}


		cstmsrch_search_objects();
	}
}

/**
 * Activation plugin function
 */
if ( ! function_exists( 'cstmsrch_plugin_activate' ) ) {
	function cstmsrch_plugin_activate() {

		$all_plugins = get_plugins();

		if ( array_key_exists( 'custom-fields-search/custom-fields-search.php', $all_plugins ) ) {
			 deactivate_plugins( 'custom-fields-search/custom-fields-search.php' );
		}

		if ( is_multisite() ) {
			switch_to_blog( 1 );
			register_uninstall_hook( __FILE__, 'delete_cstmsrch_settings' );
			restore_current_blog();
		} else {
			register_uninstall_hook( __FILE__, 'delete_cstmsrch_settings' );
		}
	}
}

/**
 * Preparing global array variables of post types and taxonomies enabled for search
 * @return void
 */
if ( ! function_exists( 'cstmsrch_search_objects' ) ) {
	function cstmsrch_search_objects() {
		global $cstmsrch_options, $cstmsrch_post_types_enabled, $cstmsrch_taxonomies_enabled;
		if ( empty( $cstmsrch_options ) ) {
			$cstmsrch_options = get_option( 'cstmsrch_options' );
		}
		$cstmsrch_post_types_enabled = $cstmsrch_taxonomies_enabled = array();
		foreach ( $cstmsrch_options['output_order'] as $key => $item ) {
			if ( isset( $item['type'] ) && ! empty( $item['enabled'] ) ) {
				if ( 'post_type' == $item['type'] ) {
					$cstmsrch_post_types_enabled[] = $item['name'];
				} elseif ( 'taxonomy' == $item['type'] ) {
					$cstmsrch_taxonomies_enabled[] = $item['name'];
				}
			}
		}
	}
}

/**
 * Change WP_Query for querying only necessary post types in search query
 * @param    object  $query   WP_Query object
 * @return   object  $query   WP_Query object
 */
if ( ! function_exists( 'cstmsrch_searchfilter' ) ) {
	function cstmsrch_searchfilter( $query ) {
		global $cstmsrch_post_types_enabled, $cstmsrch_is_registered, $wpdb;
		
		
		if ( ! $cstmsrch_is_registered ) {
			register_cstmsrch_settings();
		}
		
		if ( is_search() && ! is_admin() && ! empty( $cstmsrch_post_types_enabled ) && $query->is_main_query() ) {
			$post_type = $wpdb->get_results( "SELECT DISTINCT `post_type` FROM $wpdb->posts " );
			$i = 1;
			foreach ($post_type as $key => $obj_type) {
				$array_type_post =  (array) $obj_type;
				foreach ($array_type_post as $array_type) {
				 	$type[$i] = $array_type;
				}
				$i++;
			}
			if ( empty( $_REQUEST['cstmsrch_submit_all_type'] ) ){
				if ( ! empty( $_REQUEST['cstmsrch_submit_post_type'] ) ){
					foreach( $type as  $value ) {
						if( $value == $_REQUEST['cstmsrch_submit_post_type'] ){
							$query->set( 'post_type', $value );
						}
					} 
				}else {
					$query->set( 'post_type', $cstmsrch_post_types_enabled );
				}		
			}else{
				$query->set( 'post_type', $cstmsrch_post_types_enabled );
			}
			$query->set( 'ignore_sticky_posts', true );
		}

		return $query;
	}
}


/**
 * Changing SQL-join query for adding taxonomies to search query
 * @param    string  $join   SQL-join clause
 * @return   string  $join   SQL-join clause with necessary changes
 */
if ( ! function_exists( 'cstmsrch_posts_join' ) ) {
	function cstmsrch_posts_join( $join ) {
		if ( is_search() ) {
			global $wpdb;

			$join .= " LEFT JOIN {$wpdb->term_relationships} tr ON {$wpdb->posts}.ID = tr.object_id LEFT JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id=tr.term_taxonomy_id LEFT JOIN {$wpdb->terms} t ON t.term_id = tt.term_id ";
		}
		return $join;
	}
}

if ( ! function_exists( 'cstmsrch_posts_where_tax' ) ) {
	function cstmsrch_posts_where_tax( $where ) {
		if ( is_search() ) {
			global $cstmsrch_is_registered, $wpdb, $cstmsrch_post_types_enabled, $cstmsrch_taxonomies_enabled;
			if ( ! $cstmsrch_is_registered ) {
				register_cstmsrch_settings();
			}
			$taxonomies = array();
			$where_post_types = $where_tax = "";
			if ( isset( $_REQUEST['cstmsrch_submit_post_type'] ) ) {
				$name_post = $_REQUEST['cstmsrch_submit_post_type'];
				$taxonomy_objects = get_object_taxonomies( $name_post, 'objects' );
				foreach ( $taxonomy_objects as $key => $value ) {
					if( in_array( esc_sql( $key ), $cstmsrch_taxonomies_enabled ) ) {
							$taxonomies[] = "'" . esc_sql( $key ) . "'";
						}
					
				}
				if ( ! empty( $taxonomies ) ) {
					if ( ! empty( $taxonomies ) ) {
						$taxonomies = implode( ',', $taxonomies );
						$where_tax = " t.name LIKE '%" . esc_sql( get_search_query() ) . "%' AND tt.taxonomy IN ( $taxonomies ) AND";

					}
				}
				if ( ! empty( $_REQUEST['cstmsrch_submit_post_type'] ) && ( $_REQUEST['cstmsrch_submit_post_type'] == $cstmsrch_post_types_enabled || in_array( $_REQUEST['cstmsrch_submit_post_type'], $cstmsrch_post_types_enabled ) ) ) {
					$where_post_types = " {$wpdb->posts}.post_type = '" . esc_sql( $_REQUEST['cstmsrch_submit_post_type'] ) . "' AND";
				}
			}else{
				foreach ( $cstmsrch_taxonomies_enabled as $taxonomy ) {
					$taxonomies[] = "'" . esc_sql( $taxonomy ) . "'";
				}
				if ( ! empty( $taxonomies ) ) {
					$taxonomies = implode( ',', $taxonomies );
					$where_tax = " t.name LIKE '%" . esc_sql( get_search_query() ) . "%' AND tt.taxonomy IN ( $taxonomies ) AND";
				}
				if ( ! empty( $_REQUEST['cstmsrch_post_type'] ) && ( $_REQUEST['cstmsrch_post_type'] == $cstmsrch_post_types_enabled || in_array( $_REQUEST['cstmsrch_post_type'], $cstmsrch_post_types_enabled ) ) ) {
					$where_post_types = " {$wpdb->posts}.post_type = '" . esc_sql( $_REQUEST['cstmsrch_post_type'] ) . "' AND";
				}
			}
			if ( ! empty( $where_tax ) ) {
				$where .= " OR ( $where_post_types $where_tax {$wpdb->posts}.post_status = 'publish' )";
			}
		}
		return $where;
	}
}

if ( ! function_exists( 'cstmsrch_multilanguage_tax' ) ) {
	function cstmsrch_multilanguage_tax( $clauses ) {

		if ( is_search() ) {
			global $wpdb, $cstmsrch_taxonomies_enabled;
			
			if ( isset( $_REQUEST['cstmsrch_submit_post_type'] ) ) {
				$taxonomy_objects = get_object_taxonomies( $_REQUEST['cstmsrch_submit_post_type'], 'objects' );
				foreach ( $taxonomy_objects as $key => $value ) {
					$taxonomies[] = "'" . esc_sql( $key ) . "'";		
				}
			} else {
				foreach ( $cstmsrch_taxonomies_enabled as $taxonomy ) {					
					$taxonomies[] = "'" . esc_sql( $taxonomy ) . "'";			
				}
			}
			if ( ! empty( $taxonomies ) ) {
				$taxonomies = implode( ',', $taxonomies );
				$clauses['join'] .= " LEFT JOIN " . $wpdb->prefix . "mltlngg_terms_translate multi_t ON multi_t.term_ID = t.term_id";
				$clauses['where'] .= " OR ( multi_t.name LIKE '%" . esc_sql( get_search_query() ) . "%'";
				$clauses['where'] .= " AND tt.taxonomy IN ( " . $taxonomies . " )";
				$clauses['where'] .= " AND " . $wpdb->posts . ".post_status = 'publish' )";
			}
		}
	
		return $clauses;
	}
}

if ( ! function_exists( 'cstmsrch_posts_groupby' ) ) {
	function cstmsrch_posts_groupby( $groupby ) {
		if ( is_search() ) {
			global $wpdb;
			/* group on post ID */
			$groupby_id = "{$wpdb->posts}.ID";
			if ( ! is_search() || false !== strpos( $groupby, $groupby_id ) ) {
				return $groupby;
			}
			/* if groupby was empty, using ours */
			if ( ! strlen( trim( $groupby ) ) ) {
				return $groupby_id;
			}
			/* if groupby wasn't empty, append ours */
			return $groupby . ", " . $groupby_id;
		}
		return $groupby;
	}
}

/* Data settings page */
if ( ! function_exists( 'cstmsrch_settings_page' ) ) {
	function cstmsrch_settings_page() {
		if ( ! class_exists( 'Bws_Settings_Tabs' ) )
    		require_once( dirname( __FILE__ ) . '/bws_menu/class-bws-settings.php' );
		require_once( dirname( __FILE__ ) . '/includes/class-cstmsrch-settings.php' );
		$page = new Cstmsrch_Settings_Tabs( plugin_basename( __FILE__ ) ); ?>
		<div class="wrap">
			<h1><?php _e( 'Custom Search Settings', 'custom-search-plugin' ); ?></h1>
            <noscript>
                <div class="error below-h2">
                    <p><strong><?php _e( 'WARNING', 'custom-search-plugin' ); ?>:</strong> <?php _e( 'The plugin works correctly only if JavaScript is enabled.', 'custom-search-plugin' ); ?></p>
                </div>
            </noscript>
			<?php $page->display_content(); ?>
		</div>
	<?php }
}

/* Positioning in the page. End. */
if ( ! function_exists( 'cstmsrch_action_links' ) ) {
	function cstmsrch_action_links( $links, $file ) {
		if ( ! is_network_admin() ) {
			/* Static so we don't call plugin_basename on every plugin row. */
			static $this_plugin;
			if ( ! $this_plugin ) $this_plugin = plugin_basename( __FILE__ );

			if ( $file == $this_plugin ) {
				$settings_link = '<a href="admin.php?page=custom_search.php">' . __( 'Settings', 'custom-search-plugin' ) . '</a>';
				array_unshift( $links, $settings_link );
			}
		}
		return $links;
	}
} /* End function cstmsrch_action_links */

/* Function are using to create link 'settings' on admin page. */
if ( ! function_exists( 'cstmsrch_links' ) ) {
	function cstmsrch_links( $links, $file ) {
		$base = plugin_basename( __FILE__ );
		if ( $file == $base ) {
			if ( ! is_network_admin() ) {
				$links[] = '<a href="admin.php?page=custom_search.php">' . __( 'Settings','custom-search-plugin' ) . '</a>';
			}
			$links[] = '<a href="https://wordpress.org/plugins/custom-search-plugin/faq/" target="_blank">' . __( 'FAQ','custom-search-plugin' ) . '</a>';
			$links[] = '<a href="https://support.bestwebsoft.com">' . __( 'Support', 'custom-search-plugin' ) . '</a>';
		}
		return $links;
	}
}

if ( ! function_exists( 'cstmsrch_admin_js' ) ) {
	function cstmsrch_admin_js() {
		global $cstmsrch_plugin_info;
		wp_enqueue_style( 'cstmsrch_admin_page_stylesheet', plugins_url( 'css/admin_page.css', __FILE__ ) );
		if ( isset( $_REQUEST['page'] ) && 'custom_search.php' == $_REQUEST['page'] ) {
			wp_enqueue_script( 'cstmsrch_script', plugins_url( 'js/script.js', __FILE__ ), array(), $cstmsrch_plugin_info['Version'] );
			wp_enqueue_style( 'cstmsrch_stylesheet', plugins_url( 'css/style.css', __FILE__ ) );
			bws_enqueue_settings_scripts();
			bws_plugins_include_codemirror();
		}
	}
}

if ( ! function_exists ( 'cstmsrch_admin_notices' ) ) {
	function cstmsrch_admin_notices() {
		global $hook_suffix, $cstmsrch_plugin_info;

		if ( 'plugins.php' == $hook_suffix ) {
			/* Get options from the database */
			bws_plugin_banner_to_settings( $cstmsrch_plugin_info, 'cstmsrch_options', 'custom-search-plugin', 'admin.php?page=custom_search.php' );
		}

		if ( isset( $_REQUEST['page'] ) && 'custom_search.php' == $_REQUEST['page'] ) {
			bws_plugin_suggest_feature_banner( $cstmsrch_plugin_info, 'cstmsrch_options', 'custom-search-plugin' );
		}
	}
}

/* add help tab */
if ( ! function_exists( 'cstmsrch_add_tabs' ) ) {
	function cstmsrch_add_tabs() {
		$screen = get_current_screen();
		$args = array(
			'id'		=> 'cstmsrch',
			'section'	=> '200538949'
		);
		bws_help_tab( $screen, $args );
	}
}

/* Function for delete options from table `wp_options` */
if ( ! function_exists( 'delete_cstmsrch_settings' ) ) {
	function delete_cstmsrch_settings() {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		$all_plugins = get_plugins();

		if ( ! array_key_exists( 'custom-search-pro/custom-search-pro.php', $all_plugins ) ) {
			
			if ( is_multisite() ) {
				global $wpdb;
				/* Get all blog ids */
				$blogids = $wpdb->get_col( "SELECT `blog_id` FROM $wpdb->blogs" );
				$old_blog = $wpdb->blogid;
				foreach ( $blogids as $blog_id ) {
					switch_to_blog( $blog_id );
					delete_option( 'cstmsrch_options' );
				}
				switch_to_blog( $old_blog );
			} else {
				delete_option( 'cstmsrch_options' );
			}
		}
		require_once( dirname( __FILE__ ) . '/bws_menu/bws_include.php' );
		bws_include_init( plugin_basename( __FILE__ ) );
		bws_delete_plugin( plugin_basename( __FILE__ ) );
	}
}

/* Function exclude records that contain duplicate data in selected fields */
if ( ! function_exists( 'cstmsrch_distinct' ) ) {
	function cstmsrch_distinct( $distinct ) {
		global $wp_query, $cstmsrch_options;
		if ( ! empty( $wp_query->query_vars['s'] ) && ! empty( $cstmsrch_options['fields'] ) && is_search() ) {
			$distinct .= "DISTINCT";
		}
		return $distinct;
	}
}

/* Function join table `{$wpdb->posts}` with `{$wpdb->postmeta}` */
if ( ! function_exists( 'cstmsrch_join' ) ) {
	function cstmsrch_join( $join ) {
		global $wp_query, $wpdb, $cstmsrch_options;
		if ( ! empty( $wp_query->query_vars['s'] ) && ! empty( $cstmsrch_options['fields'] ) && is_search() ) {
			$join .= "JOIN " . $wpdb->postmeta . " ON " . $wpdb->posts . ".ID = " . $wpdb->postmeta . ".post_id ";
		}
		return $join;
	}
}

/* Function adds in request keyword search on custom fields, and list of meta_key, which user has selected */
if( ! function_exists( 'cstmsrch_request' ) ) {
	function cstmsrch_request( $where ) {
		global $wp_query, $wpdb, $cstmsrch_options;
		if ( method_exists( $wpdb,'remove_placeholder_escape' ) ) {
			$where = $wpdb->remove_placeholder_escape( $where );
		}
		$pos = strrpos( $where, '%' );
		if ( false !== $pos && ! empty( $wp_query->query_vars['s'] ) && ! empty( $cstmsrch_options['fields'] ) && is_search() ) {
			$end_pos_where = 5 + $pos; /* find position of the end of the request with check the type and status of the post */
			$end_of_where_request = substr( $where, $end_pos_where ); /* save check the type and status of the post in variable */
			/* Exclude for gallery and gallery pro from search - dont show attachment with keywords */
			$flag_gllr_image = array();
			if ( in_array( 'gllr_image_text', $cstmsrch_options['fields'] ) || in_array( 'gllr_image_alt_tag', $cstmsrch_options['fields'] ) ||
				in_array( 'gllr_link_url', $cstmsrch_options['fields'] ) || in_array( 'gllr_image_description', $cstmsrch_options['fields'] ) ||
				in_array( 'gllr_lightbox_button_url', $cstmsrch_options['fields'] ) ) {
				foreach ( $cstmsrch_options['fields'] as $key => $value ) {
					if ( 'gllr_image_text' == $value || 'gllr_link_url' == $value || 'gllr_image_alt_tag' == $value ||
					 'gllr_lightbox_button_url' == $value || 'gllr_image_description' == $value ) {
						unset( $cstmsrch_options['fields'][ $key ] );
						$flag_gllr_image[] = $value;
					}
				}
			}

			$user_request = esc_sql( trim( $wp_query->query_vars['s'] ) );
			$user_request_arr = preg_split( "/[\s,]+/", $user_request ); /* The user's regular expressions are used to separate array for the desired keywords */

			if ( ! empty( $cstmsrch_options['fields'] ) ) {
				$cusfields_sql_request = "'" . implode( "', '", $cstmsrch_options['fields'] ) . "'"; /* forming a string with the list of meta_key, which user has selected */
				$where .= " OR (" . $wpdb->postmeta . ".meta_key IN (" . $cusfields_sql_request . ") "; /* Modify the request */
				foreach ( $user_request_arr as $value ) {
					$where .= "AND " . $wpdb->postmeta . ".meta_value LIKE '%" . $value . "%' ";
				}
				$where .= $end_of_where_request . ") ";
			}

			/* This code special for gallery plugin */
			if ( ! empty( $flag_gllr_image ) ) {
				foreach ( $flag_gllr_image as $flag_gllr_image_key => $flag_gllr_image_value ) {

					$where_new_end = '';
					/* save search keywords */
					foreach ( $user_request_arr as $value ) {
						$where_new_end .= "AND " . $wpdb->postmeta . ".meta_value LIKE '%" . $value . "%' ";
					}
					/* search posts-attachments */
					$id_attachment_arr = $wpdb->get_col( "SELECT " . $wpdb->posts . ".id FROM " . $wpdb->postmeta . " JOIN " . $wpdb->posts . " ON " . $wpdb->posts . ".id = " . $wpdb->postmeta . ".post_id WHERE " . $wpdb->postmeta . ".meta_key = '" . $flag_gllr_image_value . "' " . $where_new_end );
					/* if posts-attachments exists - search gallery post ID */
					if ( ! empty( $id_attachment_arr ) ) {
						$array_id_gallery = array();
						foreach ( $id_attachment_arr as $value ) {
							$id_gallery = $wpdb->get_col( "SELECT DISTINCT(" . $wpdb->posts . ".post_parent) FROM " . $wpdb->posts . " WHERE " . $wpdb->posts . ".ID = " . $value );
							if ( ! in_array( $id_gallery[0],$array_id_gallery ) ) {
								$array_id_gallery[] = $id_gallery[0];
							}
						}
					}
					/* if gallery post ID exists - show on page */
					if ( ! empty( $array_id_gallery ) ) {
						foreach ( $array_id_gallery as $value ) {
							$where .= " OR " . $wpdb->posts . ".ID = " . $value;
						}
					}
				}
			}
		}
		return $where;
	}
}

/* add a class with theme name */
if ( ! function_exists ( 'cstmsrch_theme_body_classes' ) ) {
	function cstmsrch_theme_body_classes( $classes ) {
		if ( function_exists( 'wp_get_theme' ) ) {
			$current_theme = wp_get_theme();
			$classes[] = 'cstmsrch_' . basename( $current_theme->get( 'ThemeURI' ) );
		}
		return $classes;
	}
}

/* Function shows the shortcode */
 if ( ! function_exists( 'cstmsrch_search_shortcode' ) ) {
	function cstmsrch_search_shortcode() {
		return '<div class="cstmsrch-search ">
					<form class="search-form" action="'. esc_url( home_url( '/?s=' . get_search_query()  ) ) . '" method="get" >
				    	<input class="search-form-2" type="search" name="s" placeholder="' . __( "Site search", "custom-search" ) . '"> 
				    	<input class="search-submit" type="submit" name="cstmsrch_submit_all_type" value="' . __( "Search", "custom-search" ) . '"/>
					</form>
				</div>';
	}
}

register_activation_hook( __FILE__, 'cstmsrch_plugin_activate' );
add_action( 'plugins_loaded', 'cstmsrch_plugins_loaded' );
add_action( 'admin_menu', 'add_cstmsrch_admin_menu' );
add_action( 'init', 'cstmsrch_init' );
add_action( 'admin_init', 'cstmsrch_admin_init' );
add_action( 'admin_enqueue_scripts', 'cstmsrch_admin_js' );
add_action( 'loop_start', 'cstmsrch_add_menu_search_header' );
add_action( 'wp_enqueue_scripts', 'cstmsrch_scripts' );

add_shortcode( 'cstmsrch_search', 'cstmsrch_search_shortcode' );

/* Adds "Settings" link to the plugin action page */
add_filter( 'plugin_action_links', 'cstmsrch_action_links', 10, 2 );
/* Additional links on the plugin page */
add_filter( 'plugin_row_meta', 'cstmsrch_links', 10, 2 );
add_action( 'admin_notices', 'cstmsrch_admin_notices' );

add_filter( 'posts_distinct', 'cstmsrch_distinct' );
add_filter( 'posts_join', 'cstmsrch_join' );
add_filter( 'posts_where', 'cstmsrch_request' );
/* add theme name as class to body tag */
add_filter( 'body_class', 'cstmsrch_theme_body_classes' );
