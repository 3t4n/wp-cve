<?php
/**
Plugin Name: Sitemap by BestWebSoft
Plugin URI: https://bestwebsoft.com/products/wordpress/plugins/google-sitemap/
Description: Generate and add XML sitemap to WordPress website. Help search engines index your blog.
Author: BestWebSoft
Text Domain: google-sitemap-plugin
Domain Path: /languages
Version: 3.3.0
Author URI: https://bestwebsoft.com/
License: GPLv2 or later
 */

/*
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'gglstmp_admin_menu' ) ) {
	/**
	 * Dashboard menu
	 */
	function gglstmp_admin_menu() {
		if ( ! is_plugin_active( 'google-sitemap-pro/google-sitemap-pro.php' ) && ! is_plugin_active( 'google-sitemap-plus/google-sitemap-plus.php' ) ) {
			global $gglstmp_options, $wp_version, $submenu, $gglstmp_plugin_info;

			$settings = add_menu_page(
				esc_html__( 'Sitemap Settings', 'google-sitemap-plugin' ),
				'Sitemap',
				'manage_options',
				'google-sitemap-plugin.php',
				'gglstmp_settings_page',
				'none'
			);

			add_submenu_page(
				'google-sitemap-plugin.php',
				esc_html__( 'Sitemap Settings', 'google-sitemap-plugin' ),
				esc_html__( 'Settings', 'google-sitemap-plugin' ),
				'manage_options',
				'google-sitemap-plugin.php',
				'gglstmp_settings_page'
			);
			add_submenu_page(
				'google-sitemap-plugin.php',
				esc_html__( 'Custom Links', 'google-sitemap-plugin' ),
				esc_html__( 'Custom Links', 'google-sitemap-plugin' ),
				'manage_options',
				'google-sitemap-custom-links.php',
				'gglstmp_settings_page'
			);

			add_submenu_page(
				'google-sitemap-plugin.php',
				'BWS Panel',
				'BWS Panel',
				'manage_options',
				'gglstmp-bws-panel',
				'bws_add_menu_render'
			);
			if ( isset( $submenu['google-sitemap-plugin.php'] ) ) {
				$submenu['google-sitemap-plugin.php'][] = array(
					'<span style="color:#d86463"> ' . esc_html__( 'Upgrade to Pro', 'google-sitemap-plugin' ) . '</span>',
					'manage_options',
					'https://bestwebsoft.com/products/wordpress/plugins/google-sitemap/?k=28d4cf0b4ab6f56e703f46f60d34d039&pn=83&v=' . $gglstmp_plugin_info['Version'] . '&wp_v=' . $wp_version,
				);
			}

			add_action( "load-{$settings}", 'gglstmp_add_tabs' );
		}
	}
}

if ( ! function_exists( 'gglstmp_plugins_loaded' ) ) {
	/**
	 * Load plugin textdomain
	 */
	function gglstmp_plugins_loaded() {
		load_plugin_textdomain( 'google-sitemap-plugin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

/* Function adds language files */
if ( ! function_exists( 'gglstmp_init' ) ) {
	/**
	 * Plugin init
	 */
	function gglstmp_init() {
		global $gglstmp_plugin_info, $gglstmp_options;

		if ( empty( $gglstmp_plugin_info ) ) {
			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			$gglstmp_plugin_info = get_plugin_data( __FILE__ );
		}

		/* add general functions */
		require_once dirname( __FILE__ ) . '/bws_menu/bws_include.php';
		bws_include_init( plugin_basename( __FILE__ ) );

		/* check compatible with current WP version */
		bws_wp_min_version_check( plugin_basename( __FILE__ ), $gglstmp_plugin_info, '4.5' );

		/* Get options from the database */
		gglstmp_register_settings();

		if ( 1 === intval( get_option( 'gglstmp_robots' ) ) ) {
			add_filter( 'robots_txt', 'gglstmp_robots_add_sitemap', 10, 2 );
		}

		if ( isset( $_GET['gglstmp_robots'] ) ) {
			$robots_txt_url = ABSPATH . 'robots.txt';
			/* Get content from real robots.txt file and output its content + custom content */
			if ( file_exists( $robots_txt_url ) ) {
				$response = wp_remote_get(
					get_site_url() . '/robots.txt',
					array(
						'timeout'     => 120,
						'httpversion' => '1.1',
					)
				);
				if ( is_array( $response ) && ! is_wp_error( $response ) ) {
					$robots_content  = $response['body'];
					$robots_content .= "\n";
					$public          = get_option( 'blog_public' );
					header( 'Content-Type: text/plain; charset=utf-8' );
					echo wp_kses_post( apply_filters( 'robots_txt', $robots_content, $public ) );
					exit;
				}
			}
		}

		$default_name = 'sitemap';

		if ( 1 === intval( $gglstmp_options['media_sitemap'] ) ) {
			$sitemaps_type = array( 'video_sitemap', 'image_sitemap', $default_name );
		} else {
			$sitemaps_type = array( $default_name );
		}

		/* check for file existence and call refresh function */
		if ( is_multisite() ) {
			$blog_id = get_current_blog_id();
			foreach ( $sitemaps_type as $sitemap ) {
				$filename = "{$sitemap}_{$blog_id}.xml";
				if ( ! file_exists( ABSPATH . $filename ) ) {
					$sitemap_recreate = true;
				}
			}
		} else {
			foreach ( $sitemaps_type as $sitemap ) {
				$filename = "{$sitemap}.xml";
				if ( ! file_exists( ABSPATH . $filename ) ) {
					$sitemap_recreate = true;
				}
			}
		}

		if ( isset( $sitemap_recreate ) ) {
			gglstmp_schedule_sitemap( false, false, true );
		}

		if ( ! isset( $gglstmp_options['remove_all_canonical'] ) || 1 !== $gglstmp_options['remove_all_canonical'] ) {
			/* Functionality for canonical link */
			remove_action( 'wp_head', 'rel_canonical' );
			remove_action( 'embed_head', 'rel_canonical' );
			add_action( 'wp_head', 'gglstmp_canonical_tag' );
			add_action( 'embed_head', 'gglstmp_canonical_tag' );
		}
	}
}

if ( ! function_exists( 'gglstmp_admin_init' ) ) {
	/**
	 * Plugin dashboard init
	 */
	function gglstmp_admin_init() {
		/* Add variable for bws_menu */
		global $pagenow, $bws_plugin_info, $gglstmp_plugin_info, $gglstmp_options;

		if ( empty( $bws_plugin_info ) ) {
			$bws_plugin_info = array(
				'id'      => '83',
				'version' => $gglstmp_plugin_info['Version'],
			);
		}

		if ( isset( $_GET['page'] ) && sanitize_text_field( wp_unslash( $_GET['page'] ) ) === 'google-sitemap-plugin.php' ) {
			if ( ! session_id() ) {
				session_start();
			}
		}
		if ( 'plugins.php' === $pagenow ) {
			if ( function_exists( 'bws_plugin_banner_go_pro' ) ) {
				bws_plugin_banner_go_pro( $gglstmp_options, $gglstmp_plugin_info, 'gglstmp', 'google-sitemap', '8fbb5d23fd00bdcb213d6c0985d16ec5', '83', 'google-sitemap-plugin' );
			}
		}
		if ( isset( $_GET['state'] ) && ! empty( $_GET['code'] ) ) {
			try {
				$client = gglstmp_client();
				$client->authenticate( sanitize_text_field( wp_unslash( $_GET['code'] ) ) );
				$gglstmp_options['authorization_code'] = $client->getAccessToken();

				$_SESSION[ 'gglstmp_authorization_code_' . get_current_blog_id() ] = $gglstmp_options['authorization_code'];
				update_option( 'gglstmp_options', $gglstmp_options );
				echo '<script>if (window.opener != null && !window.opener.closed) { window.opener.location.reload(); } self.close(); </script>';
				exit;
			} catch ( Exception $e ) {
				return;
			}
		}
	}
}

if ( ! function_exists( 'gglstmp_activate' ) ) {
	/**
	 * Plugin activate hook
	 */
	function gglstmp_activate() {
		if ( is_multisite() ) {
			switch_to_blog( 1 );
			/* register uninstall function only for the main blog */
			register_uninstall_hook( __FILE__, 'gglstmp_delete_settings' );
			restore_current_blog();
		} else {
			/* register uninstall function */
			register_uninstall_hook( __FILE__, 'gglstmp_delete_settings' );
		}
	}
}

if ( ! function_exists( 'gglstmp_register_settings' ) ) {
	/**
	 * Function for register of the plugin settings on init core
	 */
	function gglstmp_register_settings() {
		global $gglstmp_options, $gglstmp_plugin_info;

		if ( ! get_option( 'gglstmp_options' ) ) {
			$sitemaprecreate = true;
			$options_default = gglstmp_get_options_default();
			add_option( 'gglstmp_options', $options_default );
		}

		$gglstmp_options = get_option( 'gglstmp_options' );

		if ( ! isset( $gglstmp_options['plugin_option_version'] ) || $gglstmp_options['plugin_option_version'] !== $gglstmp_plugin_info['Version'] ) {
			$options_default = gglstmp_get_options_default();
			$gglstmp_options = array_merge( $options_default, $gglstmp_options );
			/**
			 * Register uninstall hook
			 */
			if ( ! isset( $gglstmp_options['plugin_option_version'] ) || version_compare( str_replace( 'pro-', '', $gglstmp_options['plugin_option_version'] ), '3.1.0', '<' ) ) {
				unset( $gglstmp_options['sitemap'] );
				gglstmp_activate();
			}

			$gglstmp_options['plugin_option_version'] = $gglstmp_plugin_info['Version'];

			/* show pro features */
			$gglstmp_options['hide_premium_options'] = array();
			update_option( 'gglstmp_options', $gglstmp_options );
		}

		if ( isset( $sitemaprecreate ) ) {
			gglstmp_schedule_sitemap();
		}
	}
}

if ( ! function_exists( 'gglstmp_get_options_default' ) ) {
	/**
	 * Function for get default options
	 */
	function gglstmp_get_options_default() {
		global $gglstmp_plugin_info;

		$options_default = array(
			'plugin_option_version'   => $gglstmp_plugin_info['Version'],
			'first_install'           => strtotime( 'now' ),
			'display_settings_notice' => 1,
			'suggest_feature_banner'  => 1,
			'post_type'               => array( 'page', 'post' ),
			'taxonomy'                => array(),
			'limit'                   => 50000,
			'sitemap_cron_delay'      => 600, /* delay in seconds to next cron */
			'sitemaps'                => array(),
			'alternate_language'      => 0,
			'media_sitemap'           => 0,
			'images_quality'          => 'full',
			'remove_automatic_canonical' => 0,
			'remove_all_canonical'    => 0,
			'split_sitemap'           => 0,
			'split_sitemap_items'     => array(),
		);

		$frequency       = apply_filters( 'gglstmp_get_frequency_default', array() );
		$options_default = array_merge( $options_default, $frequency );
		return $options_default;
	}
}

if ( ! function_exists( 'gglstmp_rewrite_rules' ) ) {
	/**
	 * Update sitemap on permalink structure update.
	 *
	 * @since 3.1.1
	 *
	 * @param   array $rules array of existing rules. No modification is needed.
	 * @return  array   $rules
	 */
	function gglstmp_rewrite_rules( $rules ) {
		gglstmp_schedule_sitemap();

		return $rules;
	}
}


if ( ! function_exists( 'gglstmp_schedule_sitemap' ) ) {
	/**
	 * Schedules sitemap preparing task for specified blog.
	 *
	 * @since 3.1.0
	 *
	 * @param mixed $blog_id (int)The blog id the sitemap is created for. Default is false - for current blog.
	 * @param bool  $no_cron Set if sitemap creation would be executed using cron. Default is false.
	 * @param bool  $now     Flas for start now.
	 * @return  void
	 */
	function gglstmp_schedule_sitemap( $blog_id = false, $no_cron = false, $now = false ) {
		global $gglstmp_options;

		if ( empty( $blog_id ) ) {
			$blog_id = get_current_blog_id();
		}

		if ( $no_cron || ! isset( $gglstmp_options['link_count'] ) || $gglstmp_options['link_count'] < 10000 ) {
			gglstmp_prepare_sitemap( $blog_id );
		} else {
			if ( $now ) {
				wp_schedule_single_event( time(), 'gglstmp_sitemap_cron', array( $blog_id ) );
			} else {
				wp_schedule_single_event( time() + absint( $gglstmp_options['sitemap_cron_delay'] ), 'gglstmp_sitemap_cron', array( $blog_id ) );
			}
		}
	}
}

if ( ! function_exists( 'gglstmp_edited_term' ) ) {
	/**
	 * For taxonomy edit form.
	 *
	 * @param mixed $term_id Term id.
	 * @param bool  $tt_id Term taxonomy id.
	 * @param bool  $taxonomy Taxonomy slug.
	 */
	function gglstmp_edited_term( $term_id, $tt_id, $taxonomy ) {
		if ( isset( $taxonomy ) && 'nav_menu' !== $taxonomy ) {
			gglstmp_schedule_sitemap();
		}
	}
}

if ( ! function_exists( 'gglstmp_prepare_sitemap' ) ) {
	/**
	 * Function prepares all the items that should be included into blog's sitemap.
	 * After array of items is prepared, it is divided into multiple parts according to the limit value.
	 * A single sitemap file will be created if the limit isn't reached,
	 * otherwise sitemap file for each part of array of items will be created. Blog index file would be created also.
	 * If multisite network is used, network index file will be created also.
	 *
	 * @since 3.1.0
	 *
	 * @param   mixed $blog_id (int)The blog id the sitemap is created for. Default is false - for current blog.
	 */
	function gglstmp_prepare_sitemap( $blog_id = false ) {
		global $wpdb, $gglstmp_options;

		$old_blog = $wpdb->blogid;

		$counter        = 0;
		$part_num       = 1;
		$elements       = array();
		$image_elements = array();
		$video_elements = array();
		$is_multisite   = is_multisite();

		if ( $is_multisite && ! empty( $blog_id ) ) {
			switch_to_blog( absint( $blog_id ) );
		} else {
			$blog_id = get_current_blog_id();
		}

		$gglstmp_options = get_option( 'gglstmp_options' );

		$post_types = get_post_types( array( 'public' => true ) );
		/* get all posts */

		foreach ( $post_types as $post_type => $post_type_object ) {
			if ( is_array( $gglstmp_options['post_type'] ) && ! in_array( $post_type, $gglstmp_options['post_type'], true ) ) {
				unset( $post_types[ $post_type ] );
			}
		}

		$post_status = apply_filters( 'gglstmp_post_status', array( 'publish' ) );

		$excluded_posts = $wpdb->get_col(
			"
			SELECT
				`ID`
			FROM $wpdb->posts
			WHERE
				`post_status` IN ('hidden', 'private')
		"
		);

		if ( ! empty( $excluded_posts ) ) {
			while ( true ) {
				/* exclude bbPress forums and topics */
				$hidden_child_array = $wpdb->get_col(
					"SELECT
						`ID`
					FROM $wpdb->posts
					WHERE
						`post_status` IN ('" . implode( "','", $post_status ) . "')
						AND `ID` NOT IN (" . implode( ',', $excluded_posts ) . ")
						AND `post_type` IN ('forum', 'topic', 'reply')
						AND `post_parent` IN (" . implode( ',', $excluded_posts ) . ');'
				);

				if ( ! empty( $hidden_child_array ) ) {
					$excluded_posts = array_unique( array_merge( $excluded_posts, $hidden_child_array ) );
				} else {
					break 1;
				}
			}
		}

		/* get all taxonomies */
		$taxonomies = array(
			'category' => esc_html__( 'Post categories', 'google-sitemap-plugin' ),
			'post_tag' => esc_html__( 'Post tags', 'google-sitemap-plugin' ),
		);

		foreach ( $taxonomies as $key => $taxonomy_name ) {
			if ( is_array( $gglstmp_options['taxonomy'] ) && ! in_array( $key, $gglstmp_options['taxonomy'], true ) ) {
				unset( $taxonomies[ $key ] );
			}
		}

		/* add home page */
		$show_on_front      = ! ! ( 'page' === get_option( 'show_on_front' ) );
		$frontpage_id       = get_option( 'page_on_front' );
		$frontpage_is_added = false;

		$frequency = apply_filters( 'gglstmp_get_frequency', 'monthly' );

		if ( ! empty( $post_types ) ) {
			$post_status_string = "p.`post_status` IN ('" . implode( "','", (array) $post_status ) . "')";

			$excluded_posts_string = '';
			$post_types_string     = '';

			$post_types_string = "AND p.`post_type` IN ('" . implode( "','", (array) $post_types ) . "')";

			if ( ! empty( $excluded_posts ) ) {
				$excluded_posts_string = 'AND p.`ID` NOT IN (' . implode( ',', $excluded_posts ) . ')';
			}

			/* Get the number of posts for sitemap */
			$count_posts = $wpdb->query(
				"
				SELECT COUNT( * )
				FROM `{$wpdb->posts}` p
				LEFT JOIN {$wpdb->term_relationships} tr
					ON p.`ID` = tr.`object_id`
				LEFT JOIN {$wpdb->term_taxonomy} tt
					ON tt.`term_taxonomy_id` = tr.`term_taxonomy_id`
				LEFT JOIN {$wpdb->terms} t
					ON t.`term_id` = tt.`term_id`
				WHERE
					{$post_status_string}
					{$post_types_string}
					{$excluded_posts_string}
				GROUP BY `ID`
			"
			);

			/* Count the number of iterations needed */
			$counter = (int) ceil( $count_posts / 5000 );

			/* Loop to limit 5000 posts for iteration */
			for (
					$i = 0, $offset = 0, $limit = 5000;
					$i < $counter;
					$i++, $offset += 5000
			) {
				$posts = $wpdb->get_results(
					"SELECT
						`ID`,
						`post_author`,
						`post_status`,
						`post_name`,
						`post_parent`,
						`post_type`,
						`post_date`,
						`post_date_gmt`,
						`post_modified`,
						`post_modified_gmt`,
						GROUP_CONCAT(t.`term_id`) as term_id
					FROM `{$wpdb->posts}` p
					LEFT JOIN {$wpdb->term_relationships} tr
						ON p.`ID` = tr.`object_id`
					LEFT JOIN {$wpdb->term_taxonomy} tt
						ON tt.`term_taxonomy_id` = tr.`term_taxonomy_id`
					LEFT JOIN {$wpdb->terms} t
						ON t.`term_id` = tt.`term_id`
					WHERE
						{$post_status_string}
						{$post_types_string}
						{$excluded_posts_string}
					GROUP BY `ID`
					ORDER BY `post_date_gmt` DESC LIMIT {$offset}, {$limit};"
				);

				if ( ! empty( $posts ) ) {
					foreach ( $posts as $post ) {
						$priority = 0.8;
						if ( $show_on_front && intval( $frontpage_id ) === $post->ID ) {
							$priority           = 1.0;
							$frontpage_is_added = true;
						}

						if ( $gglstmp_options['media_sitemap'] ) {

							/* Prepear image_list data for sitemap */
							$image_list = get_attached_media( 'image', $post );

							/* Add image to list */
							$image_item = array();

							if ( ! empty( $image_list ) ) {
								$image_count = 0;
								foreach ( $image_list as $image ) {
									$image_count ++;
									if ( $image_count > 1000 ) {
										break;
									}
									$attachment_metadata = wp_get_attachment_metadata( $image->ID );

									$explode_metadata  = explode( '/', $attachment_metadata['file'] );
									$image_guid        = array_pop( $explode_metadata );
									$image_upload_date = implode( ' ', $explode_metadata );

									$check_img_exists = gglstmp_if_file_exists( $image_guid, $image_upload_date );
									if ( $check_img_exists ) {
										if ( isset( $gglstmp_options['images_quality'] ) && in_array( $gglstmp_options['images_quality'], array( 'thumbnail', 'medium', 'large', 'full' ) ) ) {
											$image_url      = wp_get_attachment_image_url( $image->ID, $gglstmp_options['images_quality'] );
										} else {
											$image_url      = wp_get_attachment_image_url( $image->ID, 'full' );
										}
										$pos_extensions = strrpos( $image->post_title, '.' );
										$image_title    = ( ( false === $pos_extensions ) ? ( $image->post_title ) : substr( $image->post_title, 0, $pos_extensions ) );
										$image_item[]   = array(
											'guid'        => $image_url,
											'image_title' => $image_title,
										);
									}
								}
								/* Add array image_elements of one post */
								$image_elements[] = array(
									'url'        => get_permalink( $post ),
									'image_list' => $image_item,
								);
							}
						}

						if ( 1 === intval( $gglstmp_options['split_sitemap'] ) && ! empty( $gglstmp_options['split_sitemap_items'] ) && in_array( $post->post_type, $gglstmp_options['split_sitemap_items'], true ) ) {
							/* Data for default sitemap by post type */
							$elements[ $post->post_type ][] = array(
								'url'       => get_permalink( $post ),
								'date'      => gmdate( 'Y-m-d\TH:i:sP', strtotime( $post->post_modified ) ),
								'frequency' => $frequency,
								'priority'  => $priority,
							);
						} else {
							/* Data for default sitemap */
							$elements['default'][] = array(
								'url'       => get_permalink( $post ),
								'date'      => date( 'Y-m-d\TH:i:sP', strtotime( $post->post_modified ) ),
								'frequency' => $frequency,
								'priority'  => $priority,
							);
						}
					}
				}
				if ( 0 !== $gglstmp_options['media_sitemap'] ) {
					/* Prepear video_list data for sitemap */
					$attachments = get_posts(
						array(
							'post_type' => 'attachment',
							'post_mime_type' => 'video',
						)
					);
					if ( ! empty( $posts ) ) {
						$video_count = 0;
						foreach ( $attachments as $video ) {
							$video_item = array();
							/* Add video to list */
							$video_count ++;
							if ( $video_count > 1000 ) {
								break;
							}
							$video_item[] = array(
								$video->guid,
								$video->post_title,
							);

							if ( ! in_array( get_permalink( $video ), array_column( $video_elements, 'url' ) ) ) {
								$video_elements[] = array(
									'url'            => get_permalink( $video ),
									'video_list_url' => $video_item,
								);
							}
						}
					}
				}
			}
		}

		if ( ! $frontpage_is_added ) {
			if ( 1 === intval( $gglstmp_options['split_sitemap'] ) && ! empty( $gglstmp_options['split_sitemap_items'] ) && in_array( 'page', $gglstmp_options['split_sitemap_items'] ) ) {
				$elements['page'][] = array(
					'url'       => home_url( '/' ),
					'date'      => gmdate( 'Y-m-d\TH:i:sP', time() ),
					'frequency' => $frequency,
					'priority'  => 1.0,
				);
			} else {
				$elements['default'][] = array(
					'url'       => home_url( '/' ),
					'date'      => gmdate( 'Y-m-d\TH:i:sP', time() ),
					'frequency' => $frequency,
					'priority'  => 1.0,
				);
			}
		}

		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy => $taxonomy_data ) {
				$terms = get_terms( $taxonomy, 'hide_empty=1' );

				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					foreach ( $terms as $term_value ) {
						$modified = $wpdb->get_var(
							$wpdb->prepare(
								'SELECT `post_modified` 
									FROM ' . $wpdb->posts . ', ' . $wpdb->term_relationships . ' 
									WHERE `post_status` = "publish" 
										AND `term_taxonomy_id` = %d 
										AND ' . $wpdb->posts . '.ID = ' . $wpdb->term_relationships . '.object_id 
									ORDER BY `post_modified` DESC',
								$term_value->term_taxonomy_id
							)
						);
						if ( 1 === intval( $gglstmp_options['split_sitemap'] ) && ! empty( $gglstmp_options['split_sitemap_items'] ) && in_array( $taxonomy, $gglstmp_options['split_sitemap_items'] ) ) {
							$elements[ $taxonomy ][] = array(
								'url'       => get_term_link( (int) $term_value->term_id, $taxonomy ),
								'date'      => gmdate( 'Y-m-d\TH:i:sP', strtotime( $modified ) ),
								'frequency' => $frequency,
								'priority'  => 0.8,
							);
						} else {
							$elements['default'][] = array(
								'url'       => get_term_link( (int) $term_value->term_id, $taxonomy ),
								'date'      => gmdate( 'Y-m-d\TH:i:sP', strtotime( $modified ) ),
								'frequency' => $frequency,
								'priority'  => 0.8,
							);
						}
					}
				}
			}
		}

		/* Removing existing sitemap and sitemap_index files for current blog */
		$existing_files = gglstmp_get_sitemap_files();
		array_map( 'unlink', $existing_files );

		$gglstmp_options['sitemaps'] = array();
		$elements_array              = $elements;
		$count_all_elements          = 0;
		foreach ( $elements_array as $sitemap_type => $elements ) {
			/* Standard sitemap with link */
			$count_all_elements += count( $elements );
			if ( count( $elements ) <= $gglstmp_options['limit'] ) {
				$part_num = 0;
				gglstmp_create_sitemap( $elements, $part_num, $sitemap_type );
			} else if ( 0 < $gglstmp_options['limit'] ) {
				$parts = array_chunk( $elements, $gglstmp_options['limit'] );
				foreach ( $parts as $part_num => $part_elements ) {
					gglstmp_create_sitemap( $part_elements, $part_num + 1, $sitemap_type );
				}
			} else {
				$part_num = 0;
				gglstmp_create_sitemap( $elements, $part_num, $sitemap_type );
			}

			/* Create media sitemap */
			if ( $gglstmp_options['media_sitemap'] ) {
				/* Checking image_sitemap */
				if ( count( $image_elements ) <= $gglstmp_options['limit'] ) {
					$image_part_num = 0;
					gglstmp_create_image_sitemap( $image_elements, $image_part_num );
				} else {
					$parts_image = array_chunk( $image_elements, $gglstmp_options['limit'] );
					foreach ( $parts_image as $image_part_num => $part_image_elements ) {
						gglstmp_create_image_sitemap( $part_image_elements, $image_part_num + 1 );
					}
				}
				/* Checking video_sitemap */
				if ( count( $video_elements ) <= $gglstmp_options['limit'] ) {
					$video_part_num = 0;
					gglstmp_create_video_sitemap( $video_elements, $video_part_num );
				} else {
					$parts_video = array_chunk( $video_elements, $gglstmp_options['limit'] );
					foreach ( $parts_video as $video_part_num => $part_video_elements ) {
						gglstmp_create_video_sitemap( $part_video_elements, $video_part_num + 1 );
					}
				}
			}
		}
		if ( $is_multisite ) {
			/* Removing main index file */
			$existing_files = gglstmp_get_sitemap_files( 0 );
			if ( 0 !== $gglstmp_options['media_sitemap'] && ( 1 !== intval( $gglstmp_options['split_sitemap'] ) || empty( $gglstmp_options['split_sitemap_items'] ) ) ) {
				array_map( 'unlink', $existing_files );
			}
		}
		if ( 1 === $gglstmp_options['media_sitemap'] || ( 1 === intval( $gglstmp_options['split_sitemap'] ) && ! empty( $gglstmp_options['split_sitemap_items'] ) ) ) {
			gglstmp_create_sitemap_index();
		}
		if ( $is_multisite ) {
			/* Create main network sitemap index file. Only for subfolder structure, as index sitemap cannot contain sitemaps located on different domain/subdomain. */
			gglstmp_create_sitemap_index( 0 );
		}
		$gglstmp_options['link_count'] = $count_all_elements;
		/**
		 * Options update is necessary because 'gglstmp_create_sitemap' and 'gglstmp_create_sitemap_index' functions
		 * have modified $gglstmp_options global variable by calling 'gglstmp_save_sitemap_info' function
		 */

		update_option( 'gglstmp_options', $gglstmp_options );
		if ( $is_multisite ) {
			switch_to_blog( $old_blog );
		}
	}
}

if ( ! function_exists( 'gglstmp_create_sitemap' ) ) {
	/**
	 * Function creates xml sitemap file with the provided list of elements.
	 * Global variables are used and function mltlngg_get_lang_link() is called from the plugin Multilanguage.
	 * Filename is generated in the following way:
	 * On a single site:
	 * a) $part_num isn't set: "sitemap.xml"
	 * b) $part_num is set and equals 2: "sitemap_2.xml".
	 * On single subsite of multisite network, $blog_id == 1:
	 * a) $part_num isn't set: "sitemap_1.xml"
	 * b) $part_num is set and equals 2: "sitemap_1_2.xml".
	 *
	 * @since 3.1.0
	 *
	 * @param array  $elements  An array of elements to include to the sitemap.
	 * @param int    $part_num  (optional) Indicates the number of the part of elements. It is included to the sitemap filename.
	 * @param string $post_type (optional) Post type slug.
	 */
	function gglstmp_create_sitemap( $elements, $part_num = 0, $post_type = 'default' ) {
		global $blog_id, $mltlngg_languages, $mltlngg_enabled_languages, $gglstmp_options;

		$xml                  = new DomDocument( '1.0', 'utf-8' );
		$home_url             = site_url( '/' );
		$xml_stylesheet_path  = ( defined( 'WP_CONTENT_DIR' ) ) ? $home_url . basename( WP_CONTENT_DIR ) : $home_url . 'wp-content';
		$xml_stylesheet_path .= ( defined( 'WP_PLUGIN_DIR' ) ) ? '/' . basename( WP_PLUGIN_DIR ) . '/google-sitemap-plugin/sitemap.xsl' : '/plugins/google-sitemap-plugin/sitemap.xsl';
		$xslt                 = $xml->createProcessingInstruction( 'xml-stylesheet', "type=\"text/xsl\" href=\"$xml_stylesheet_path\"" );
		$xml->appendChild( $xslt );
		$urlset = $xml->appendChild( $xml->createElementNS( 'http://www.sitemaps.org/schemas/sitemap/0.9', 'urlset' ) );

		/* Used to check compatibility and work with the plugin Multilanguage*/
		$count_lang    = empty( $mltlngg_enabled_languages ) ? '' : count( $mltlngg_enabled_languages );
		$compatibility = false;
		if ( ! empty( $gglstmp_options['alternate_language'] ) && ( '' !== $count_lang ) ) {
			$compatibility = true;
		}

		/* Create an array with active languages and add a value for hreflang */
		$enabled_languages = array();
		if ( $compatibility ) {
			$urlset->setAttributeNS( 'http://www.w3.org/2000/xmlns/', 'xmlns:xhtml', 'http://www.w3.org/1999/xhtml' );

			foreach ( $mltlngg_enabled_languages as $language ) {
				foreach ( $mltlngg_languages as $item ) {
					if ( $language['name'] === $item[2] ) {
						$language['lang']              = $item[0];
						$enabled_languages[ $item[2] ] = $language;
					}
				}
			}

			if ( function_exists( 'mltlngg_get_lang_link' ) ) {
				$lang_link = 'mltlngg_get_lang_link';
			}
			$args_links = array();
		}

		foreach ( $elements as $element ) {
			if ( $compatibility ) {
				foreach ( $enabled_languages as $language ) {
					$args_links['lang'] = $language['locale'];
					$args_links['url']  = $element['url'];

					$url = $urlset->appendChild( $xml->createElement( 'url' ) );
					$loc = $url->appendChild( $xml->createElement( 'loc' ) );
					$loc->appendChild( $xml->createTextNode( $lang_link( $args_links ) ) );

					foreach ( $enabled_languages as $language ) {
						$args_links['lang'] = $language['locale'];
						$link               = $url->appendChild( $xml->createElement( 'xhtml:link' ) );
						$link->setAttribute( 'rel', 'alternate' );
						$link->setAttribute( 'hreflang', $language['lang'] );
						$link->setAttribute( 'href', $lang_link( $args_links ) );
					}

					$lastmod = $url->appendChild( $xml->createElement( 'lastmod' ) );
					$lastmod->appendChild( $xml->createTextNode( $element['date'] ) );
					$changefreq = $url->appendChild( $xml->createElement( 'changefreq' ) );
					$changefreq->appendChild( $xml->createTextNode( $element['frequency'] ) );
					$priority = $url->appendChild( $xml->createElement( 'priority' ) );
					$priority->appendChild( $xml->createTextNode( $element['priority'] ) );
				}
			} else {
				$url = $urlset->appendChild( $xml->createElement( 'url' ) );
				$loc = $url->appendChild( $xml->createElement( 'loc' ) );
				$loc->appendChild( $xml->createTextNode( $element['url'] ) );
				$lastmod = $url->appendChild( $xml->createElement( 'lastmod' ) );
				$lastmod->appendChild( $xml->createTextNode( $element['date'] ) );
				$changefreq = $url->appendChild( $xml->createElement( 'changefreq' ) );
				$changefreq->appendChild( $xml->createTextNode( $element['frequency'] ) );
				$priority = $url->appendChild( $xml->createElement( 'priority' ) );
				$priority->appendChild( $xml->createTextNode( $element['priority'] ) );
			}
		}

		$xml->formatOutput = true;

		if ( ! is_writable( ABSPATH ) ) {
			@chmod( ABSPATH, 0755 );
		}

		$part_num = ( absint( $part_num ) > 0 ) ? '_' . absint( $part_num ) : '';

		$default_name = 'sitemap';

		if ( ( 1 === intval( $gglstmp_options['split_sitemap'] ) && ! empty( $gglstmp_options['split_sitemap_items'] ) ) || 0 !== $gglstmp_options['media_sitemap'] ) {
			$default_name = $post_type . '_' . $default_name;
		}

		if ( is_multisite() ) {
			$filename = $default_name . '_' . absint( $blog_id ) . $part_num . '.xml';
		} else {
			$filename = $default_name . $part_num . '.xml';
		}

		$xml->save( ABSPATH . $filename );

		gglstmp_save_sitemap_info( $filename );

	}
}

if ( ! function_exists( 'gglstmp_create_image_sitemap' ) ) {
	/**
	 * Function creates image xml sitemap file with the provided list of elements.
	 *
	 * @param array $elements An array of elements to include to the sitemap.
	 * @param int   $part_num (optional) Indicates the number of the part of elements. It is included to the sitemap filename.
	 */
	function gglstmp_create_image_sitemap( $elements, $part_num = 0 ) {
		global $blog_id, $mltlngg_languages, $mltlngg_enabled_languages, $gglstmp_options;

		$xml                  = new DomDocument( '1.0', 'utf-8' );
		$home_url             = site_url( '/' );
		$xml_stylesheet_path  = ( defined( 'WP_CONTENT_DIR' ) ) ? $home_url . basename( WP_CONTENT_DIR ) : $home_url . 'wp-content';
		$xml_stylesheet_path .= ( defined( 'WP_PLUGIN_DIR' ) ) ? '/' . basename( WP_PLUGIN_DIR ) . '/google-sitemap-plugin/image_sitemap.xsl' : '/plugins/google-sitemap-plugin/image_sitemap.xsl';
		$xslt                 = $xml->createProcessingInstruction( 'xml-stylesheet', "type=\"text/xsl\" href=\"$xml_stylesheet_path\"" );
		$xml->appendChild( $xslt );
		$urlset = $xml->appendChild( $xml->createElementNS( 'http://www.sitemaps.org/schemas/sitemap/0.9', 'urlset' ) );

		/* Add namespace for image_sitemap */
		$img_spacename        = $xml->createAttribute( 'xmlns:image' );
		$img_spacename->value = 'http://www.google.com/schemas/sitemap-image/1.1';
		$urlset->appendChild( $img_spacename );

		/* Used to check compatibility and work with the plugin Multilanguage */
		$count_lang    = empty( $mltlngg_enabled_languages ) ? '' : count( $mltlngg_enabled_languages );
		$compatibility = false;
		if ( ! empty( $gglstmp_options['alternate_language'] ) && ( '' !== $count_lang ) ) {
			$compatibility = true;
		}

		/* Create an array with active languages and add a value for hreflang */
		$enabled_languages = array();
		if ( $compatibility ) {
			$urlset->setAttributeNS( 'http://www.w3.org/2000/xmlns/', 'xmlns:xhtml', 'http://www.w3.org/1999/xhtml' );

			foreach ( $mltlngg_enabled_languages as $language ) {
				foreach ( $mltlngg_languages as $item ) {
					if ( $language['name'] === $item[2] ) {
						$language['lang']              = $item[0];
						$enabled_languages[ $item[2] ] = $language;
					}
				}
			}

			if ( function_exists( 'mltlngg_get_lang_link' ) ) {
				$lang_link = 'mltlngg_get_lang_link';
			}
			$args_links = array();
		}

		foreach ( $elements as $element ) {
			if ( $compatibility ) {
				foreach ( $enabled_languages as $language ) {
					$args_links['lang'] = $language['locale'];
					$args_links['url']  = $element['url'];

					$url = $urlset->appendChild( $xml->createElement( 'url' ) );
					$loc = $url->appendChild( $xml->createElement( 'loc' ) );
					$loc->appendChild( $xml->createTextNode( $lang_link( $args_links ) ) );

					foreach ( $enabled_languages as $language ) {
						$args_links['lang'] = $language['locale'];
						$link               = $url->appendChild( $xml->createElement( 'xhtml:link' ) );
						$link->setAttribute( 'rel', 'alternate' );
						$link->setAttribute( 'hreflang', $language['lang'] );
						$link->setAttribute( 'href', $lang_link( $args_links ) );
					}

					if ( ! empty( $element['image_list'] ) ) {
						foreach ( $element['image_list'] as $image_it ) {
							$image = $url->appendChild( $xml->createElement( 'image:image' ) );

							/* image title */
							$image_title = $image->appendChild( $xml->createElement( 'image:title' ) );
							$image_title->appendChild( $xml->createTextNode( $image_it['image_title'] ) );

							/* image loc */
							$image_loc = $image->appendChild( $xml->createElement( 'image:loc' ) );
							$image_loc->appendChild( $xml->createTextNode( $image_it['guid'] ) );
						}
					}
				}
			} else {
				$url = $urlset->appendChild( $xml->createElement( 'url' ) );
				$loc = $url->appendChild( $xml->createElement( 'loc' ) );
				$loc->appendChild( $xml->createTextNode( $element['url'] ) );

				if ( ! empty( $element['image_list'] ) ) {
					foreach ( $element['image_list'] as $image_it ) {

						$image = $url->appendChild( $xml->createElement( 'image:image' ) );

						/* image title */
						$image_title = $image->appendChild( $xml->createElement( 'image:title' ) );
						$image_title->appendChild( $xml->createTextNode( $image_it['image_title'] ) );

						/* image loc */
						$image_loc = $image->appendChild( $xml->createElement( 'image:loc' ) );
						$image_loc->appendChild( $xml->createTextNode( $image_it['guid'] ) );
					}
				}
			}
		}

		$xml->formatOutput = true;

		if ( ! is_writable( ABSPATH ) ) {
			@chmod( ABSPATH, 0755 );
		}

		$part_num = ( absint( $part_num ) > 0 ) ? '_' . absint( $part_num ) : '';

		$default_name = 'sitemap';

		if ( is_multisite() ) {
			$filename = 'image_' . $default_name . '_' . absint( $blog_id ) . $part_num . '.xml';
		} else {
			$filename = 'image_' . $default_name . $part_num . '.xml';
		}
		$xml->save( ABSPATH . $filename );
		gglstmp_save_sitemap_info( $filename );
	}
}

if ( ! function_exists( 'gglstmp_if_file_exists' ) ) {
	/**
	 * Function checkes if fiel exists
	 *
	 * @param string $filename           The sitemap filename.
	 * @param string $time_dir           (Optional) The temporary dir.
	 * @param string $format             (Optional) Format.
	 * @param bool   $echo               (Optional) Display or return.
	 * @param string $dir                (Optional) Directory for sitemap.
	 * @param string $show_if_not_exists (Optional) Flag for display if not exist.
	 * @return string $format
	 */
	function gglstmp_if_file_exists( $filename, $time_dir = null, $format = '', $echo = true, $dir = '', $show_if_not_exists = '' ) {
		$error   = false;
		$path    = '';
		$abspath = ltrim( ABSPATH, '/' );

		if ( ! is_bool( $dir ) ) {
			$dir = trim( trim( str_replace( $abspath, '', $dir ) ), '/' );
		}

		if ( false === $dir || empty( $dir ) ) {
			$uploads = wp_upload_dir( $time_dir );
			if ( isset( $uploads['error'] ) && ! empty( $uploads['error'] ) ) {
				$error = true;
			} else {
				$path = $uploads['path'];
				$dir  = str_replace( $abspath, '', $path );
			}
		} elseif ( true === $dir ) {
			/* If $dir is set to true, then $filename is already the full path */
			$path     = dirname( $filename );
			$filename = basename( $filename );
			$dir      = str_replace( ABSPATH, '', $path );
		} else {
			$path = ABSPATH . $dir;
		}

		$full_path = $path . '/' . $filename;

		$exists = ( $error || empty( $filename ) ) ? false : file_exists( $full_path );

		if ( empty( $format ) ) {
			$format = $exists;
			$echo   = false;
		} else {
			if ( ! $exists ) {
				$format = $show_if_not_exists;
			}

			if ( $format ) {
				$pathparts = pathinfo( $full_path );
				$tags      = array(
					'%file_directory%' => $pathparts['dirname'],
					'%file_extension%' => isset( $pathparts['extension'] ) ? $pathparts['extension'] : '',
					'%file_name%'      => $pathparts['basename'],
					'%file_path%'      => $full_path,
					'%file_url%'       => site_url() . '/' . $dir . '/' . $filename,
				);

				foreach ( $tags as $tag => $new ) {
					$format = str_replace( $tag, $new, $format );
				}
			}
		}

		if ( $echo ) {
			echo esc_attr( $format );
		}

		return $format;
	}
}

if ( ! function_exists( 'gglstmp_create_video_sitemap' ) ) {
	/**
	 * Function creates video xml sitemap file with the provided list of elements.
	 *
	 * @param array $elements An array of elements to include to the sitemap.
	 * @param int   $part_num (optional) Indicates the number of the part of elements. It is included to the sitemap filename.
	 */
	function gglstmp_create_video_sitemap( $elements, $part_num = 0 ) {
		global $blog_id, $mltlngg_languages, $mltlngg_enabled_languages, $gglstmp_options;

		$xml                  = new DomDocument( '1.0', 'utf-8' );
		$home_url             = site_url( '/' );
		$xml_stylesheet_path  = ( defined( 'WP_CONTENT_DIR' ) ) ? $home_url . basename( WP_CONTENT_DIR ) : $home_url . 'wp-content';
		$xml_stylesheet_path .= ( defined( 'WP_PLUGIN_DIR' ) ) ? '/' . basename( WP_PLUGIN_DIR ) . '/google-sitemap-plugin/video_sitemap.xsl' : '/plugins/google-sitemap-plugin/video_sitemap.xsl';
		$xslt                 = $xml->createProcessingInstruction( 'xml-stylesheet', "type=\"text/xsl\" href=\"$xml_stylesheet_path\"" );
		$xml->appendChild( $xslt );
		$urlset = $xml->appendChild( $xml->createElementNS( 'http://www.sitemaps.org/schemas/sitemap/0.9', 'urlset' ) );

		/* Add namespace for video_sitemap */
		$video_spacename        = $xml->createAttribute( 'xmlns:video' );
		$video_spacename->value = 'http://www.google.com/schemas/sitemap-video/1.1';
		$urlset->appendChild( $video_spacename );

		/* Used to check compatibility and work with the plugin Multilanguage */
		$count_lang    = empty( $mltlngg_enabled_languages ) ? '' : count( $mltlngg_enabled_languages );
		$compatibility = false;
		if ( ! empty( $gglstmp_options['alternate_language'] ) && ( '' !== $count_lang ) ) {
			$compatibility = true;
		}

		/* Create an array with active languages and add a value for hreflang */
		$enabled_languages = array();
		if ( $compatibility ) {
			$urlset->setAttributeNS( 'http://www.w3.org/2000/xmlns/', 'xmlns:xhtml', 'http://www.w3.org/1999/xhtml' );

			foreach ( $mltlngg_enabled_languages as $language ) {
				foreach ( $mltlngg_languages as $item ) {
					if ( $language['name'] === $item[2] ) {
						$language['lang']              = $item[0];
						$enabled_languages[ $item[2] ] = $language;
					}
				}
			}

			if ( function_exists( 'mltlngg_get_lang_link' ) ) {
				$lang_link = 'mltlngg_get_lang_link';
			}
			$args_links = array();
		}

		foreach ( $elements as $element ) {
			if ( $compatibility ) {
				foreach ( $enabled_languages as $language ) {
					$args_links['lang'] = $language['locale'];
					$args_links['url']  = $element['url'];

					$url = $urlset->appendChild( $xml->createElement( 'url' ) );
					$loc = $url->appendChild( $xml->createElement( 'loc' ) );
					$loc->appendChild( $xml->createTextNode( $lang_link( $args_links ) ) );

					foreach ( $enabled_languages as $language ) {
						$args_links['lang'] = $language['locale'];
						$link               = $url->appendChild( $xml->createElement( 'xhtml:link' ) );
						$link->setAttribute( 'rel', 'alternate' );
						$link->setAttribute( 'hreflang', $language['lang'] );
						$link->setAttribute( 'href', $lang_link( $args_links ) );
					}

					if ( isset( $element['video_list_url'] ) ) {
						foreach ( $element['video_list_url'] as $video_it ) {
							$video = $url->appendChild( $xml->createElement( 'video:video' ) );

							/*video url*/
							$videocont = $video->appendChild( $xml->createElement( 'video:content_loc' ) );
							$videocont->appendChild( $xml->createTextNode( $video_it[0] ) );

							/*video title*/
							$videotitle = $video->appendChild( $xml->createElement( 'video:title' ) );
							$videotitle->appendChild( $xml->createTextNode( $video_it[1] ) );
						}
					}
				}
			} else {
				$url = $urlset->appendChild( $xml->createElement( 'url' ) );
				$loc = $url->appendChild( $xml->createElement( 'loc' ) );
				$loc->appendChild( $xml->createTextNode( $element['url'] ) );

				if ( isset( $element['video_list_url'] ) ) {
					foreach ( $element['video_list_url'] as $video_it ) {
						$video = $url->appendChild( $xml->createElement( 'video:video' ) );

						/*video url*/
						$videocont = $video->appendChild( $xml->createElement( 'video:content_loc' ) );
						$videocont->appendChild( $xml->createTextNode( $video_it[0] ) );

						/*video title*/
						$videotitle = $video->appendChild( $xml->createElement( 'video:title' ) );
						$videotitle->appendChild( $xml->createTextNode( $video_it[1] ) );
					}
				}
			}
		}

		$xml->formatOutput = true;

		if ( ! is_writable( ABSPATH ) ) {
			@chmod( ABSPATH, 0755 );
		}

		$part_num = ( absint( $part_num ) > 0 ) ? '_' . absint( $part_num ) : '';

		$default_name = 'sitemap';

		if ( is_multisite() ) {
			$filename = 'video_' . $default_name . '_' . absint( $blog_id ) . $part_num . '.xml';
		} else {
			$filename = 'video_' . $default_name . $part_num . '.xml';
		}
		$xml->save( ABSPATH . $filename );
		gglstmp_save_sitemap_info( $filename );
	}
}

if ( ! function_exists( 'gglstmp_create_sitemap_index' ) ) {
	/**
	 * Function creates xml sitemap index file.
	 *
	 * @since 3.1.0
	 *
	 * @param mixed  $blog_id    (Optional) Sets if the index file is created for network (0) or for single subsite (false: current blog id).
	 * @param string $index_type (Optional) Index type.
	 */
	function gglstmp_create_sitemap_index( $blog_id = false, $index_type = '' ) {
		global $wpdb, $gglstmp_options;
		/* index sitemap for network supports only subfolder multisite installation */
		if ( 0 === $blog_id && is_multisite() && is_subdomain_install() ) {
			return;
		}

		$blog_id = ( false === $blog_id ) ? get_current_blog_id() : absint( $blog_id );

		$default_name = 'sitemap';

		if ( ! is_multisite() || 0 === $blog_id ) {
			if ( '' === $index_type ) {
				$index_filename = $default_name . '.xml';
			} else {
				$index_filename = $index_type . '_' . $default_name . '.xml';
			}
		} else {
			if ( '' === $index_type ) {
				$index_filename = $default_name . '_' . $blog_id . '.xml';
			} else {
				$index_filename = $index_type . '_' . $default_name . '_' . $blog_id . '.xml';
			}
		}

		$elements   = gglstmp_get_index_elements( $blog_id, $index_type );
		$index_file = ABSPATH . $index_filename;

		if ( file_exists( $index_file ) ) {
			unlink( $index_file );
		}

		$xmlindex = new DomDocument( '1.0', 'utf-8' );
		$site_url = ( 0 === $blog_id ) ? network_site_url( '/' ) : site_url( '/' );

		$xml_stylesheet_path  = ( defined( 'WP_CONTENT_DIR' ) ) ? $site_url . basename( WP_CONTENT_DIR ) : $site_url . 'wp-content';
		$xml_stylesheet_path .= ( defined( 'WP_PLUGIN_DIR' ) ) ? '/' . basename( WP_PLUGIN_DIR ) . '/google-sitemap-plugin/sitemap-index.xsl' : '/plugins/google-sitemap-plugin/sitemap-index.xsl';

		$xmlindex->appendChild( $xmlindex->createProcessingInstruction( 'xml-stylesheet', "type=\"text/xsl\" href=\"$xml_stylesheet_path\"" ) );
		$sitemapindex = $xmlindex->appendChild( $xmlindex->createElementNS( 'http://www.sitemaps.org/schemas/sitemap/0.9', 'sitemapindex' ) );
		foreach ( $elements as $element ) {
			$sitemap = $sitemapindex->appendChild( $xmlindex->createElement( 'sitemap' ) );
			$loc     = $sitemap->appendChild( $xmlindex->createElement( 'loc' ) );
			$loc->appendChild( $xmlindex->createTextNode( $element['loc'] ) );
			$lastmod = $sitemap->appendChild( $xmlindex->createElement( 'lastmod' ) );
			$lastmod->appendChild( $xmlindex->createTextNode( $element['lastmod'] ) );
		}

		if ( count( $elements ) > 0 ) {
			if ( ! is_writable( ABSPATH ) ) {
				@chmod( ABSPATH, 0755 );
			}
			$xmlindex->formatOutput = true;
			$xmlindex->save( $index_file );
			if ( 0 !== $blog_id ) {
				gglstmp_save_sitemap_info( $index_filename, true );
			}
		} elseif ( file_exists( $index_file ) ) {
			unlink( $index_file );
		}
	}
}

if ( ! function_exists( 'gglstmp_get_index_elements' ) ) {
	/**
	 * Function gets the elements from the blogs options and returns an array of elements to include to the index sitemap file.
	 *
	 * @since 3.1.0
	 *
	 * @param mixed $blog_id       (Optional) Sets the range of elements to return. false - current subsite, 0 - network index, (int) - id of the subsite.
	 * @param bool  $include_index (Optional) Flas for exclude index.
	 *
	 * @return  array       $include_index      (optional) Sets if index element should be also included.
	 */
	function gglstmp_get_index_elements( $blog_id = false, $include_index = false ) {
		global $wpdb;
		$index_elements          = array();
		$external_index_elements = array();
		$is_multisite            = is_multisite();
		if ( $is_multisite && 0 === $blog_id ) {
			$blogids  = $wpdb->get_col( "SELECT `blog_id` FROM $wpdb->blogs" );
			$default_name = 'sitemap';
			foreach ( $blogids as $id ) {
				$xml_file = $default_name . '_' . $id . '.xml';
				if ( file_exists( ABSPATH . $xml_file ) ) {
					$index_elements[ home_url( '/' . $xml_file ) ] = array(
						'is_index' => 1,
						'file'     => $xml_file,
						'path'     => ABSPATH . $xml_file,
						'loc'      => home_url( '/' . $xml_file ),
						'lastmod'  => gmdate( 'Y-m-d\TH:i:sP', filemtime( ABSPATH . $xml_file ) ),
					);
				}
			}
		} else {
			$blog_options = ( ! $is_multisite || empty( $blog_id ) ) ? get_option( 'gglstmp_options' ) : get_blog_option( absint( $blog_id ), 'gglstmp_options' );
			if ( ! empty( $blog_options['sitemaps'] ) && is_array( $blog_options['sitemaps'] ) ) {
				foreach ( $blog_options['sitemaps'] as $sitemap ) {
					if (
						( empty( $sitemap['is_index'] ) || $include_index ) &&
						isset( $sitemap['path'] ) && file_exists( $sitemap['path'] ) &&
						isset( $sitemap['loc'] )
					) {
						$index_elements[ $sitemap['loc'] ] = $sitemap;
					}
				}
			}
		}

		return $index_elements;
	}
}

if ( ! function_exists( 'gglstmp_get_sitemap_files' ) ) {
	/**
	 * Function returns all the corresponding existing sitemap files.
	 *
	 * @since 3.1.0
	 *
	 * @param   mixed $blog_id (optional, default: false) "all" || false || (int)blog_id. Specifies the range of xml files to return.
	 *                                      "all" - all availabe sitemap .xml files.
	 *                                      false - sitemaps of current blog.
	 *                                      blog_id - sitemaps of specified blog, 0 - network index file.
	 *
	 * @return  array       $files          An array of filenames of existing files of the specified type.
	 */
	function gglstmp_get_sitemap_files( $blog_id = false ) {
		global $gglstmp_options;
		$files = array();

		$default_name = 'sitemap';

		if ( is_multisite() ) {
			if ( 'all' !== $blog_id ) {
				$blog_id = ( false === $blog_id ) ? get_current_blog_id() : absint( $blog_id );
			}
			if ( 'all' === $blog_id ) {
				/* all existing sitemap files */
				$mask = '*' . $default_name . '*.xml';
			} elseif ( 0 === $blog_id ) {
				/* main network index */
				$mask = $default_name . '*.xml';
			} else {
				/* all subsite sitemap files */
				$mask = '*' . $default_name . '_ ' . $blog_id . '*.xml';
			}
		} else {
			$mask = '*' . $default_name . '*.xml';
		}

		if ( isset( $mask ) ) {
			$files = glob( ABSPATH . $mask );
		}

		return $files;
	}
}

if ( ! function_exists( 'gglstmp_check_sitemap' ) ) {
	/**
	 * Function checks the availability of the sitemap file by the provided URL.
	 *
	 * @param   string $url The url of the xml sitemap file to check.
	 *
	 * @return  array       $result         An array with the code and message of the external url check. 200 == $result['code'] if success.
	 */
	function gglstmp_check_sitemap( $url ) {
		$result = wp_remote_get( esc_url_raw( $url ) );
		if ( is_array( $result ) && ! is_wp_error( $result ) ) {
			return $result['response'];
		} else {
			return $result;
		}
	}
}

if ( ! function_exists( 'gglstmp_save_sitemap_info' ) ) {
	/**
	 * Function checks the availability of the sitemap file by the provided URL.
	 *
	 * @since 3.1.0
	 *
	 * @param   string $filename The filename to save to the options.
	 * @param   string $is_index Indicates if the file is an index sitemap.
	 *                                                  false if is regular sitemap.
	 *                                                  'index' if is sitemap index.
	 */
	function gglstmp_save_sitemap_info( $filename = 'sitemap.xml', $is_index = false ) {
		global $gglstmp_options;
		$xml_url  = home_url( '/' ) . $filename;
		$xml_path = ABSPATH . $filename;
		$is_index = ! ! $is_index ? 1 : 0;

		$sitemap_data = array(
			'is_index' => $is_index,
			'file'     => $filename,
			'path'     => $xml_path,
			'loc'      => $xml_url,
			'lastmod'  => gmdate( 'Y-m-d\TH:i:sP', filemtime( $xml_path ) ),
		);

		if ( file_exists( $xml_path ) ) {
			/* save data to blog options */
			$gglstmp_options['sitemaps'][ $filename ] = $sitemap_data;
			update_option( 'gglstmp_options', $gglstmp_options );
		}
	}
}

if ( ! function_exists( 'gglstmp_get_sitemap_info' ) ) {
	/**
	 * Function checks the availability of the sitemap file by the provided URL.
	 *
	 * @since 3.1.0
	 *
	 * @param mixed $blog_id Blog id for multisite or false.
	 * @return array $options['sitemaps']
	 */
	function gglstmp_get_sitemap_info( $blog_id = false ) {
		if ( is_multisite() && ! empty( $blog_id ) ) {
			$options = get_blog_option( absint( $blog_id ), 'gglstmp_options' );
		} else {
			$options = get_option( 'gglstmp_options' );
		}

		return ( ! empty( $options['sitemaps'] ) ) ? $options['sitemaps'] : array();
	}
}

if ( ! function_exists( 'gglstmp_client' ) ) {
	/**
	 * Function auth for Google Client.
	 */
	function gglstmp_client() {
		global $gglstmp_plugin_info;

		if ( is_multisite() ) {
			$gglstmp_options = get_blog_option( absint( get_current_blog_id() ), 'gglstmp_options' );
		} else {
			$gglstmp_options = get_option( 'gglstmp_options' );
		}

		if ( ! function_exists( 'google_api_php_client_autoload' ) || class_exists( 'Google_Client' ) ) {
			require_once dirname( __FILE__ ) . '/google_api/autoload.php';
		}

		$client = new Google_Client();
		if ( isset( $gglstmp_options['client_id'] ) && isset( $gglstmp_options['client_secret'] ) ) {
			$client->setClientId( $gglstmp_options['client_id'] );
			$client->setClientSecret( $gglstmp_options['client_secret'] );
			$client->setScopes(
				array(
					'https://www.googleapis.com/auth/webmasters',
					'https://www.googleapis.com/auth/siteverification',
				)
			);
			$client->setRedirectUri( admin_url( 'admin.php?page=google-sitemap-plugin.php' ) );
			$client->setApplicationName( $gglstmp_plugin_info['Name'] );
		}

		return $client;
	}
}

if ( ! function_exists( 'gglstmp_plugin_status' ) ) {
	/**
	 * Get status for plugin.
	 *
	 * @param array $plugins     Plugins array.
	 * @param array $all_plugins All plugins on the site.
	 * @param bool  $is_network  Fals for network.
	 * @return array $result
	 */
	function gglstmp_plugin_status( $plugins, $all_plugins, $is_network ) {
		$result = array(
			'status'      => '',
			'plugin'      => '',
			'plugin_info' => array(),
		);
		foreach ( (array) $plugins as $plugin ) {
			if ( array_key_exists( $plugin, $all_plugins ) ) {
				if (
					( $is_network && is_plugin_active_for_network( $plugin ) ) ||
					( ! $is_network && is_plugin_active( $plugin ) )
				) {
					$result['status']      = 'actived';
					$result['plugin']      = $plugin;
					$result['plugin_info'] = $all_plugins[ $plugin ];
					break;
				} else {
					$result['status']      = 'deactivated';
					$result['plugin']      = $plugin;
					$result['plugin_info'] = $all_plugins[ $plugin ];
				}
			}
		}
		if ( empty( $result['status'] ) ) {
			$result['status'] = 'not_installed';
		}

		return $result;
	}
}

if ( ! function_exists( 'gglstmp_settings_page' ) ) {
	/**
	 * Display setting page
	 */
	function gglstmp_settings_page() {
		global $gglstmp_plugin_info, $gglstmp_list_table;
		require_once dirname( __FILE__ ) . '/includes/pro_banners.php';
		if ( isset( $_GET['page'] ) && 'google-sitemap-plugin.php' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) {
			/* Showing settings tab */
			if ( ! class_exists( 'Bws_Settings_Tabs' ) ) {
				require_once dirname( __FILE__ ) . '/bws_menu/class-bws-settings.php';
			}
			require_once dirname( __FILE__ ) . '/includes/class-gglstmp-settings.php';
			$page = new Gglstmp_Settings_Tabs( plugin_basename( __FILE__ ) );
			if ( method_exists( $page, 'add_request_feature' ) ) {
				$page->add_request_feature();
			}
		} ?>
		<div class="wrap">
			<?php
			/* Showing settings tab */
			if ( 'google-sitemap-plugin.php' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) {
				?>
				<h1>Sitemap <?php esc_html_e( 'Settings', 'google-sitemap-plugin' ); ?></h1>
				<noscript>
					<div class="error below-h2"><p><strong><?php esc_html_e( 'Please enable JavaScript in your browser.', 'google-sitemap-plugin' ); ?></strong></p></div>
				</noscript>
				<?php
				$page->display_content();
			} else {
				?>
				<h1>
					<?php esc_html_e( 'Custom Links', 'google-sitemap-plugin' ); ?>
					<button disabled="disabled" class="page-title-action add-new-h2"><?php esc_html_e( 'Add New', 'google-sitemap-plugin' ); ?></button>
				</h1>
				<?php
				gglstmp_pro_block( 'gglstmp_custom_links_block', false );
			}
			?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'gglstmp_robots_add_sitemap' ) ) {
	/**
	 * Display setting page
	 *
	 * @param string $output Output string.
	 * @param string $public Flag for site status.
	 * @return string $output
	 */
	function gglstmp_robots_add_sitemap( $output, $public ) {
		global $gglstmp_options;
		if ( empty( $gglstmp_options ) ) {
			if ( is_multisite() ) {
				$gglstmp_options = get_blog_option( absint( get_current_blog_id() ), 'gglstmp_options' );
			} else {
				$gglstmp_options = get_option( 'gglstmp_options' );
			}
		}
		if ( '0' !== $public ) {
			$home_url = get_option( 'home' );

			$default_name = 'sitemap';

			$filename = ( is_multisite() ) ? $default_name . get_current_blog_id() . '.xml' : $default_name . '.xml';
			$line     = 'Sitemap: ' . $home_url . '/' . $filename;
			if ( file_exists( ABSPATH . $filename ) && false === strpos( $output, $line ) ) {
				$output .= "\n" . $line . "\n";
			}
		}

		return $output;
	}
}

if ( ! function_exists( 'gglstmp_add_plugin_stylesheet' ) ) {
	/**
	 * Function for adding style
	 */
	function gglstmp_add_plugin_stylesheet() {
		global $gglstmp_plugin_info;
		wp_enqueue_style( 'gglstmp_icon', plugins_url( 'css/icon.css', __FILE__ ), array(), $gglstmp_plugin_info['Version'] );
		if ( isset( $_GET['page'] ) && 'google-sitemap-plugin.php' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) {
			bws_enqueue_settings_scripts();
			wp_enqueue_style( 'gglstmp_stylesheet', plugins_url( 'css/style.css', __FILE__ ), array(), $gglstmp_plugin_info['Version'] );
			wp_enqueue_script( 'gglstmp_admin_script', plugins_url( 'js/admin_script.js', __FILE__ ), array( 'jquery' ), $gglstmp_plugin_info['Version'], true );
		}
	}
}

if ( ! function_exists( 'gglstmp_get_site_info' ) ) {
	/**
	 * Function to get info about site from Google Webmaster Tools
	 *
	 * @param object $webmasters Google Webmaster Tools.
	 * @param object $site_verification Site verification.
	 * @return string $return
	 */
	function gglstmp_get_site_info( $webmasters, $site_verification ) {
		global $gglstmp_options;

		$instruction_url  = 'https://bestwebsoft.com/documentation/sitemap/sitemap-user-guide/#h.2phv39y4trv1';
		$home_url         = home_url( '/' );
		$wmt_sites_array  = array();
		$wmt_sitemaps_arr = array();

		$return = '<table id="gglstmp_manage_table"><tr><th>' . esc_html__( 'Website', 'google-sitemap-plugin' ) . '</th>
					<td><a href="' . $home_url . '" target="_blank">' . $home_url . '</a></td></tr>';

		try {
			$wmt_sites = $webmasters->sites->listSites()->getSiteEntry();

			foreach ( $wmt_sites as $site ) {
				$wmt_sites_array[ $site->siteUrl ] = $site->permissionLevel;
			}

			if ( ! array_key_exists( $home_url, $wmt_sites_array ) ) {
				$return .= '<tr><th>' . esc_html__( 'Status', 'google-sitemap-plugin' ) . '</th>
					<td>' . esc_html__( 'Not added', 'google-sitemap-plugin' ) . '</td></tr>';
			} else {

				$return .= '<tr><th>' . esc_html__( 'Status', 'google-sitemap-plugin' ) . '</th>
					<td class="gglstmp_success">' . esc_html__( 'Added', 'google-sitemap-plugin' ) . '</td></tr>';

				$return .= '<tr><th>' . esc_html__( 'Verification Status', 'google-sitemap-plugin' ) . '</th>';
				if ( 'siteOwner' === $wmt_sites_array[ $home_url ] ) {
					$return .= '<td>' . esc_html__( 'Verified', 'google-sitemap-plugin' ) . '</td></tr>';
				} else {
					$return .= '<td>' . esc_html__( 'Not verified', 'google-sitemap-plugin' ) . '</td></tr>';
				}

				$webmasters_sitemaps = $webmasters->sitemaps->list_sitemaps( $home_url )->get_sitemap();

				foreach ( $webmasters_sitemaps as $sitemap ) {
					$wmt_sitemaps_arr[ $sitemap->path ] = ( $sitemap->errors > 0 || $sitemap->warnings > 0 ) ? true : false;
				}

				$return .= '<tr><th>' . esc_html__( 'Sitemap Status', 'google-sitemap-plugin' ) . '</th>';

				$default_name = 'sitemap';

				if ( is_multisite() ) {
					$blog_id     = get_current_blog_id();
					$xml_file    = $default_name . '_' . $blog_id . '.xml';
					$url_sitemap = home_url( '/' ) . $xml_file;
				} else {
					$xml_file    = $default_name . '.xml';
					$url_sitemap = home_url( '/' ) . $xml_file;
				}

				if ( ! empty( $url_sitemap ) ) {
					if ( ! array_key_exists( $url_sitemap, $wmt_sitemaps_arr ) ) {
						$return .= '<td>' . esc_html__( 'Not added', 'google-sitemap-plugin' ) . '</td></tr>';
					} else {
						if ( ! $wmt_sitemaps_arr[ $url_sitemap ] ) {
							$return .= '<td class="gglstmp_success">' . esc_html__( 'Added', 'google-sitemap-plugin' ) . '</td></tr>';
						} else {
							$return .= '<td>' . esc_html__( 'Added with errors.', 'google-sitemap-plugin' ) . '<a href="https://www.google.com/webmasters/tools/sitemap-details?hl=en&siteUrl=' . rawurlencode( $home_url ) . '&sitemapUrl=' . rawurlencode( $url_sitemap ) . '#ISSUE_FILTER=-1">' . esc_html__( 'View errors in Google Webmaster Tools', 'google-sitemap-plugin' ) . '</a></td></tr>';
						}
					}
					$return .= '<tr><th>' . esc_html__( 'Sitemap URL', 'google-sitemap-plugin' ) . '</th>
						<td><a href="' . $url_sitemap . '" target="_blank">' . $url_sitemap . '</a></td></tr>';
				} else {
					$return .= '<td><strong>' . esc_html__( 'Error', 'google-sitemap-plugin' ) . ':</strong> ' . esc_html__( 'Please check the sitemap file manually.', 'google-sitemap-plugin' ) . ' <a target="_blank" href="' . $instruction_url . '">' . esc_html__( 'Learn More', 'google-sitemap-plugin' ) . '</a></td></tr>';
				}
			}
		} catch ( Google_Service_Exception $e ) {
			$error    = $e->getErrors();
			$sv_error = isset( $error[0]['message'] ) ? $error[0]['message'] : esc_html__( 'Unexpected error', 'google-sitemap-plugin' );
		} catch ( Google_IO_Exception $e ) {
			$sv_error = $e->getMessage();
		} catch ( Google_Auth_Exception $e ) {
			$sv_error = true;
		} catch ( Exception $e ) {
			$sv_error = $e->getMessage();
		}

		if ( ! empty( $sv_error ) ) {
			if ( true !== $sv_error ) {
				$return .= '<tr><th></th><td><strong>' . esc_html__( 'Error', 'google-sitemap-plugin' ) . ':</strong> ' . $sv_error . '</td></tr>';
			}
			$return .= '<tr><th></th><td>' . esc_html__( 'Manual verification required.', 'google-sitemap-plugin' ) . ' <a target="_blank" href="' . $instruction_url . '">' . esc_html__( 'Learn More', 'google-sitemap-plugin' ) . '</a></td></tr>';
		}
		$return .= '</table>';

		return $return;
	}
}

if ( ! function_exists( 'gglstmp_delete_site' ) ) {
	/**
	 * Deleting site from Google Webmaster Tools
	 *
	 * @param object $webmasters Google Webmaster Tools.
	 * @param object $site_verification Site verification.
	 * @return string $return
	 */
	function gglstmp_delete_site( $webmasters, $site_verification ) {
		global $gglstmp_options;

		$home_url = home_url( '/' );
		$return   = '<table id="gglstmp_manage_table"><tr><th>' . esc_html__( 'Website', 'google-sitemap-plugin' ) . '</th>
					<td><a href="' . $home_url . '" target="_blank">' . $home_url . '</a></td></tr>';

		try {
			$webmasters_sitemaps = $webmasters->sitemaps->list_sitemaps( $home_url )->get_sitemap();
			foreach ( $webmasters_sitemaps as $sitemap ) {
				try {
					$webmasters->sitemaps->delete( $home_url, $sitemap->path );
				} catch ( Google_Service_Exception $e ) {
					$error    = $e->getErrors();
				} catch ( Google_IO_Exception $e ) {
					$error    = $e->getErrors();
				} catch ( Google_Auth_Exception $e ) {
					$error    = $e->getErrors();
				} catch ( Exception $e ) {
					$error    = $e->getErrors();
				}
			}

			$webmasters->sites->delete( $home_url );

			$return .= '<tr><th>' . esc_html__( 'Status', 'google-sitemap-plugin' ) . '</th>
					<td>' . esc_html__( 'Deleted', 'google-sitemap-plugin' ) . '</td></tr>';
			unset( $gglstmp_options['site_vererification_code'] );
			update_option( 'gglstmp_options', $gglstmp_options );

		} catch ( Google_Service_Exception $e ) {
			$error    = $e->getErrors();
			$sv_error = isset( $error[0]['message'] ) ? $error[0]['message'] : esc_html__( 'Unexpected error', 'google-sitemap-plugin' );
		} catch ( Google_IO_Exception $e ) {
			$sv_error = $e->getMessage();
		} catch ( Google_Auth_Exception $e ) {
			$sv_error = true;
		} catch ( Exception $e ) {
			$sv_error = $e->getMessage();
		}
		if ( ! empty( $sv_error ) ) {
			$return .= '<tr><th>' . esc_html__( 'Status', 'google-sitemap-plugin' ) . '</th>
				<td>' . esc_html__( 'Not added', 'google-sitemap-plugin' ) . '</td></tr>';
			if ( true !== $sv_error ) {
				$return .= '<tr><th></th><td><strong>' . esc_html__( 'Error', 'google-sitemap-plugin' ) . ':</strong> ' . $sv_error . '</td></tr>';
			}
		}
		$return .= '</table>';

		return $return;
	}
}

if ( ! function_exists( 'gglstmp_add_site' ) ) {
	/**
	 * Adding and verifing site, adding sitemap file to Google Webmaster Tools
	 *
	 * @param object $webmasters Google Webmaster Tools.
	 * @param object $site_verification Site verification.
	 * @return string $return
	 */
	function gglstmp_add_site( $webmasters, $site_verification ) {
		global $gglstmp_options;

		$instruction_url = 'https://bestwebsoft.com/documentation/sitemap/sitemap-user-guide/#h.2phv39y4trv1';
		$home_url        = home_url( '/' );

		$return = '<table id="gglstmp_manage_table"><tr><th>' . esc_html__( 'Website', 'google-sitemap-plugin' ) . '</th>
					<td><a href="' . $home_url . '" target="_blank">' . $home_url . '</a></td></tr>';
		try {
			$webmasters->sites->add( $home_url );
			$return .= '<tr><th>' . esc_html__( 'Status', 'google-sitemap-plugin' ) . '</th>
					<td class="gglstmp_success">' . esc_html__( 'Added', 'google-sitemap-plugin' ) . '</td></tr>';
		} catch ( Google_Service_Exception $e ) {
			$error     = $e->getErrors();
			$wmt_error = isset( $error[0]['message'] ) ? $error[0]['message'] : esc_html__( 'Unexpected error', 'google-sitemap-plugin' );
		} catch ( Google_IO_Exception $e ) {
			$wmt_error = $e->getMessage();
		} catch ( Google_Auth_Exception $e ) {
			$wmt_error = true;
		} catch ( Exception $e ) {
			$wmt_error = $e->getMessage();
		}

		if ( ! empty( $wmt_error ) ) {
			$return .= '<tr><th>' . esc_html__( 'Status', 'google-sitemap-plugin' ) . '</th>';
			if ( true !== $wmt_error ) {
				$return .= '<td><strong>' . esc_html__( 'Error', 'google-sitemap-plugin' ) . ':</strong> ' . $wmt_error . '</td></tr>
				<tr><th></th>';
			}
			$return .= '<td>' . esc_html__( 'Manual verification required.', 'google-sitemap-plugin' ) . ' <a target="_blank" href="' . $instruction_url . '">' . esc_html__( 'Learn More', 'google-sitemap-plugin' ) . '</a></td></tr>';
		} else {

			try {
				$gglstmp_sv_get_token_request_site = new Google_Service_SiteVerification_SiteVerificationWebResourceGettokenRequestSite();
				$gglstmp_sv_get_token_request_site->setIdentifier( $home_url );
				$gglstmp_sv_get_token_request_site->setType( 'SITE' );
				$gglstmp_sv_get_token_request = new Google_Service_SiteVerification_SiteVerificationWebResourceGettokenRequest();
				$gglstmp_sv_get_token_request->setSite( $gglstmp_sv_get_token_request_site );
				$gglstmp_sv_get_token_request->setVerificationMethod( 'META' );
				$getToken                                    = $site_verification->webResource->getToken( $gglstmp_sv_get_token_request );
				$gglstmp_options['site_vererification_code'] = htmlspecialchars( $getToken['token'] );
				if ( preg_match( '|^&lt;meta name=&quot;google-site-verification&quot; content=&quot;(.*)&quot; /&gt;$|', $gglstmp_options['site_vererification_code'] ) ) {
					update_option( 'gglstmp_options', $gglstmp_options );

					$return .= '<tr><th>' . esc_html__( 'Verification Code', 'google-sitemap-plugin' ) . '</th>
						<td>' . esc_html__( 'Received and added to the site', 'google-sitemap-plugin' ) . '</td></tr>';
				} else {
					$return .= '<tr><th>' . esc_html__( 'Verification Code', 'google-sitemap-plugin' ) . '</th>
						<td>' . esc_html__( 'Received, but has not been added to the site', 'google-sitemap-plugin' ) . '</td></tr>';
				}
			} catch ( Google_Service_Exception $e ) {
				$error    = $e->getErrors();
				$sv_error = isset( $error[0]['message'] ) ? $error[0]['message'] : esc_html__( 'Unexpected error', 'google-sitemap-plugin' );
			} catch ( Google_IO_Exception $e ) {
				$sv_error = $e->getMessage();
			} catch ( Google_Auth_Exception $e ) {
				$sv_error = true;
			} catch ( Exception $e ) {
				$sv_error = $e->getMessage();
			}

			if ( ! empty( $sv_error ) ) {
				if ( true !== $sv_error ) {
					$return .= '<tr><th>' . esc_html__( 'Verification Code', 'google-sitemap-plugin' ) . '</th>
						<td><strong>' . esc_html__( 'Error', 'google-sitemap-plugin' ) . ':</strong> ' . $sv_error . '</td></tr>';
				}

				$return .= '<tr><th>' . esc_html__( 'Verification Status', 'google-sitemap-plugin' ) . '</th>
					<td>' . _esc_html__( "The site couldn't be verified. Manual verification required.", 'google-sitemap-plugin' ) . ' <a target="_blank" href="' . $instruction_url . '">' . esc_html__( 'Learn More', 'google-sitemap-plugin' ) . '</a></td></tr>';
			} else {

				try {
					$gglstmp_wmt_resource_site = new Google_Service_SiteVerification_SiteVerificationWebResourceResourceSite();
					$gglstmp_wmt_resource_site->setIdentifier( $home_url );
					$gglstmp_wmt_resource_site->setType( 'SITE' );
					$gglstmp_wmt_resource = new Google_Service_SiteVerification_SiteVerificationWebResourceResource();
					$gglstmp_wmt_resource->setSite( $gglstmp_wmt_resource_site );
					$site_verification->webResource->insert( 'META', $gglstmp_wmt_resource );

					$return .= '<tr><th>' . esc_html__( 'Verification Status', 'google-sitemap-plugin' ) . '</th>
						<td class="gglstmp_success">' . esc_html__( 'Verified', 'google-sitemap-plugin' ) . '</td></tr>';
				} catch ( Google_Service_Exception $e ) {
					$error    = $e->getErrors();
					$sv_error = isset( $error[0]['message'] ) ? $error[0]['message'] : esc_html__( 'Unexpected error', 'google-sitemap-plugin' );
				} catch ( Google_IO_Exception $e ) {
					$sv_error = $e->getMessage();
				} catch ( Google_Auth_Exception $e ) {
					$sv_error = true;
				} catch ( Exception $e ) {
					$sv_error = $e->getMessage();
				}

				if ( ! empty( $sv_error ) ) {
					$return .= '<tr><th>' . esc_html__( 'Verification Status', 'google-sitemap-plugin' ) . '</th>';
					if ( true !== $sv_error ) {
						$return .= '<td><strong>' . esc_html__( 'Error', 'google-sitemap-plugin' ) . ':</strong> ' . $sv_error . '</td></tr>
							<tr><th></th>';
					}
					$return .= '<td>' . esc_html__( 'Manual verification required.', 'google-sitemap-plugin' ) . ' <a target="_blank" href="' . $instruction_url . '">' . esc_html__( 'Learn More', 'google-sitemap-plugin' ) . '</a></td></tr>';
				} else {

					$return .= '<tr><th>' . esc_html__( 'Sitemap Status', 'google-sitemap-plugin' ) . '</th>';

					$is_multisite = is_multisite();

					$default_name = 'sitemap';

					if ( $is_multisite ) {
						$blog_id          = get_current_blog_id();
						$sitemap_filename = $default_name . '_' . $blog_id . '.xml';
					} else {
						$sitemap_filename = $default_name . '.xml';
					}

					if (
						! empty( $gglstmp_options['sitemaps'][ $sitemap_filename ]['loc'] ) &&
						! empty( $gglstmp_options['sitemaps'][ $sitemap_filename ]['path'] ) &&
						file_exists( $gglstmp_options['sitemaps'][ $sitemap_filename ]['path'] )
					) {
						$sitemap_url  = $gglstmp_options['sitemaps'][ $sitemap_filename ]['loc'];
						$check_result = gglstmp_check_sitemap( $sitemap_url );
						if ( ! is_wp_error( $check_result ) && 200 === intval( $check_result['code'] ) ) {
							try {
								$webmasters->sitemaps->submit( $home_url, $sitemap_url );
								$return .= '<td class="gglstmp_success">' . esc_html__( 'Added', 'google-sitemap-plugin' ) . '</td></tr>';
							} catch ( Google_Service_Exception $e ) {
								$error     = $e->getErrors();
								$wmt_error = isset( $error[0]['message'] ) ? $error[0]['message'] : esc_html__( 'Unexpected error', 'google-sitemap-plugin' );
							} catch ( Google_IO_Exception $e ) {
								$wmt_error = $e->getMessage();
							} catch ( Google_Auth_Exception $e ) {
								$wmt_error = true;
							} catch ( Exception $e ) {
								$wmt_error = $e->getMessage();
							}
							if ( ! empty( $wmt_error ) ) {
								if ( true !== $wmt_error ) {
									$return .= '<td><strong>' . esc_html__( 'Error', 'google-sitemap-plugin' ) . ':</strong> ' . $wmt_error . '</td></tr>
										<tr><th></th>';
								}
								$return .= '<td>' . esc_html__( 'Please add the sitemap file manually.', 'google-sitemap-plugin' ) . ' <a target="_blank" href="' . $instruction_url . '">' . esc_html__( 'Learn More', 'google-sitemap-plugin' ) . '</a></td></tr>';
							}
						} else {
							$return .= sprintf(
								'<td><strong>%s:</strong> %s</td></tr>',
								esc_html__( 'Error 404', 'google-sitemap-plugin' ),
								sprintf(
									esc_html__( 'The sitemap file %s not found.', 'google-sitemap-plugin' ),
									sprintf(
										'(<a href="%s">%s</a>)',
										$gglstmp_options['sitemaps'][ $sitemap_filename ]['loc'],
										$sitemap_filename
									)
								)
							);
						}
					} else {
						$return .= '<td><strong>' . esc_html__( 'Error', 'google-sitemap-plugin' ) . ':</strong> ' . esc_html__( 'The sitemap file not found.', 'google-sitemap-plugin' ) . '</td></tr>';
					}
				}
			}
		}

		$return .= '</table>';

		return $return;
	}
}

if ( ! function_exists( 'gglstmp_add_verification_code' ) ) {
	/**
	 * Add verification code to the site head
	 */
	function gglstmp_add_verification_code() {
		global $gglstmp_options;

		if ( isset( $gglstmp_options['site_vererification_code'] ) ) {
			echo htmlspecialchars_decode( $gglstmp_options['site_vererification_code'] );
		}
	}
}

if ( ! function_exists( 'gglstmp_check_post_status' ) ) {
	/**
	 * Check post status before Updating
	 *
	 * @param string $new_status New status.
	 * @param string $old_status Old status.
	 * @param object $post       Post object.
	 */
	function gglstmp_check_post_status( $new_status, $old_status, $post ) {
		if ( ! wp_is_post_revision( $post->ID ) ) {
			global $gglstmp_update_sitemap;
			if ( in_array( $new_status, array( 'publish', 'trash', 'future' ), true ) ) {
				$gglstmp_update_sitemap = true;
			} elseif (
				in_array( $old_status, array( 'publish', 'future' ), true ) &&
				in_array( $new_status, array( 'auto-draft', 'draft', 'private', 'pending' ), true )
			) {
				$gglstmp_update_sitemap = true;
			}
		}
	}
}

if ( ! function_exists( 'gglstmp_update_sitemap' ) ) {
	/**
	 * Updating the sitemap after a post or page is trashed or published
	 *
	 * @param int   $post_id Post ID.
	 * @param mixed $post    Post object or null.
	 */
	function gglstmp_update_sitemap( $post_id, $post = null ) {
		if ( ! wp_is_post_revision( $post_id ) && ( ! isset( $post ) || ( 'nav_menu' !== $post->post_type && 'nav_menu_item' !== $post->post_type ) ) ) {
			global $gglstmp_update_sitemap;
			if ( true === $gglstmp_update_sitemap ) {
				gglstmp_register_settings();
				gglstmp_schedule_sitemap();
			}
		}
	}
}


if ( ! function_exists( 'gglstmp_canonical_tag' ) ) {
	/**
	 * Functionality for canonical link
	 */
	function gglstmp_canonical_tag() {
		global $post, $gglstmp_options;
		$gglstmp_meta_canonical = '';
		if ( isset( $post->ID ) ) {
			$gglstmp_meta_canonical = get_post_meta( $post->ID, '_gglstmp_meta_canonical_tag', true );
		}
		if ( '' === $gglstmp_meta_canonical && 1 === $gglstmp_options['remove_automatic_canonical'] ) {
			return;
		}

		if ( '' === $gglstmp_meta_canonical ) {
			$id = get_queried_object_id();

			if ( 0 === $id ) {
				return;
			}

			$gglstmp_meta_canonical = wp_get_canonical_url( $id );
		}
		if ( ! empty( $gglstmp_meta_canonical ) ) {
			$canonical_url  = '<link rel="canonical" href="' . esc_url( $gglstmp_meta_canonical ) . '"/>';
			$canonical_url .= "\n\n";
			echo wp_kses(
				apply_filters( 'gglstmp_canonical_tag', $canonical_url ),
				array(
					'link' => array(
						'href' => array(),
						'rel'  => array(),
					),
				)
			);
		}
	}
}

if ( ! function_exists( 'gglstmp_add_custom_canonical_url' ) ) {
	/**
	 * Functionality for add custom canonical link
	 *
	 * @param object $post Post object for metabox.
	 */
	function gglstmp_add_custom_canonical_url( $post ) {
		add_meta_box(
			'Meta Box',
			esc_html__( 'Sitemap plugin', 'google-sitemap-plugin' ),
			'gglstmp_custom_meta_box_content_canonical_url',
			'page',
			'normal',
			'high'
		);

		add_meta_box(
			'Meta Box',
			esc_html__( 'Sitemap plugin', 'google-sitemap-plugin' ),
			'gglstmp_custom_meta_box_content_canonical_url',
			'post',
			'normal',
			'high'
		);
	}
}

if ( ! function_exists( 'gglstmp_save_custom_canonical_tag_box' ) ) {
	/**
	 * Functionality for save custom canonical link to sitemap
	 */
	function gglstmp_save_custom_canonical_tag_box() {
		global $post;
		/* Get our form field */
		if ( isset( $_POST['gglstmp-meta-canonical-url'] ) && isset( $_POST['gglstmp_canonical_url'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gglstmp_canonical_url'] ) ), 'gglstmp_canonical_url_action' ) ) {
			$gglstmp_meta_canonical = esc_url_raw( wp_unslash( $_POST['gglstmp-meta-canonical-url'] ) );
			/* Update post meta canonical url */
			update_post_meta( $post->ID, '_gglstmp_meta_canonical_tag', $gglstmp_meta_canonical );
		}
	}
}

if ( ! function_exists( 'gglstmp_custom_meta_box_content_canonical_url' ) ) {
	/**
	 * Functionality for display custom canonical link block
	 *
	 * @param object $post Post object for metabox.
	 */
	function gglstmp_custom_meta_box_content_canonical_url( $post ) {
		/* Content for the custom meta box */
		?>
		<label><?php esc_html_e( 'Canonical Url', 'google-sitemap-plugin' ); ?>:</label>
		<input style="width:99%;" class="meta-text" type="text" name="gglstmp-meta-canonical-url" value="<?php echo esc_attr( get_post_meta( $post->ID, '_gglstmp_meta_canonical_tag', true ) ); ?>" /></p>
		<?php wp_nonce_field( 'gglstmp_canonical_url_action', 'gglstmp_canonical_url' ); ?>
		<?php
	}
}

if ( ! function_exists( 'gglstmp_action_links' ) ) {
	/**
	 * Adding setting link in activate plugin page
	 *
	 * @param array  $links Array with links.
	 * @param string $file  File path.
	 * @return array $links
	 */
	function gglstmp_action_links( $links, $file ) {
		/* Static so we don't call plugin_basename on every plugin row. */
		if ( ! is_network_admin() && ! is_plugin_active( 'google-sitemap-pro/google-sitemap-pro.php' ) ) {
			static $this_plugin;
			if ( ! $this_plugin ) {
				$this_plugin = plugin_basename( __FILE__ );
			}
			if ( $file === $this_plugin ) {
				$settings_link = '<a href="admin.php?page=google-sitemap-plugin.php">' . esc_html__( 'Settings', 'google-sitemap-plugin' ) . '</a>';
				array_unshift( $links, $settings_link );
			}
		}

		return $links;
	}
}

if ( ! function_exists( 'gglstmp_links' ) ) {
	/**
	 * Adding Settings, FAQ and Support links
	 *
	 * @param array  $links Array with links.
	 * @param string $file  File path.
	 * @return array $links
	 */
	function gglstmp_links( $links, $file ) {
		$base = plugin_basename( __FILE__ );
		if ( $file === $base ) {
			if ( ! is_network_admin() && ! is_plugin_active( 'google-sitemap-pro/google-sitemap-pro.php' ) ) {
				$links[] = '<a href="admin.php?page=google-sitemap-plugin.php">' . esc_html__( 'Settings', 'google-sitemap-plugin' ) . '</a>';
			}
			$links[] = '<a href="https://support.bestwebsoft.com/hc/en-us/sections/200538869" target="_blank">' . esc_html__( 'FAQ', 'google-sitemap-plugin' ) . '</a>';
			$links[] = '<a href="https://support.bestwebsoft.com">' . esc_html__( 'Support', 'google-sitemap-plugin' ) . '</a>';
		}

		return $links;
	}
}

if ( ! function_exists( 'gglstmp_plugin_banner' ) ) {
	/**
	 * Display plugin banner
	 */
	function gglstmp_plugin_banner() {
		global $hook_suffix, $gglstmp_plugin_info;

		if ( 'plugins.php' === $hook_suffix ) {
			bws_plugin_banner_to_settings( $gglstmp_plugin_info, 'gglstmp_options', 'google-sitemap-plugin', 'admin.php?page=google-sitemap-plugin.php' );
		}

		if ( isset( $_REQUEST['page'] ) && 'google-sitemap-plugin.php' === $_REQUEST['page'] ) {
			bws_plugin_suggest_feature_banner( $gglstmp_plugin_info, 'gglstmp_options', 'google-sitemap-plugin' );
		}
	}
}

if ( ! function_exists( 'gglstmp_add_tabs' ) ) {
	/**
	 * Add help tab
	 */
	function gglstmp_add_tabs() {
		$screen = get_current_screen();
		$args   = array(
			'id'      => 'gglstmp',
			'section' => '200538869',
		);
		bws_help_tab( $screen, $args );
	}
}

if ( ! function_exists( 'gglstmp_add_sitemap' ) ) {
	/**
	 * Fires when the new blog has been added or during the blog activation, marking as not spam or as not archived.
	 *
	 * @since   1.2.9
	 *
	 * @param   int $blog_id Blog ID.
	 *
	 * @return  void
	 */
	function gglstmp_add_sitemap( $blog_id ) {
		global $wpdb;

		/* don`t have to check blog status for new blog */
		if ( 'wpmu_new_blog' !== current_filter() ) {
			$blog_details = get_blog_details( $blog_id );
			if (
				! is_object( $blog_details ) ||
				1 === $blog_details->archived ||
				1 === $blog_details->deleted ||
				1 === $blog_details->spam
			) {
				return;
			}
		}

		gglstmp_schedule_sitemap( $blog_id );
	}
}

if ( ! function_exists( 'gglstmp_delete_sitemap' ) ) {
	/**
	 * Fires when the blog has been deleted or blog status has been changed to 'spam', 'deactivated(deleted)' or 'archived'.
	 *
	 * @since   1.2.9
	 *
	 * @param int  $blog_id Blog ID.
	 * @param bool $gglstmp_del_init Delete sitemap before create new file.
	 */
	function gglstmp_delete_sitemap( $blog_id, $gglstmp_del_init = false ) {
		global $gglstmp_options;
		$default_name = 'sitemap';

		if ( $gglstmp_options['media_sitemap'] ) {
			$masks = array( $default_name );
		} else {
			$masks = array( 'video_sitemap', 'image_sitemap', $default_name );
		}

		/* remove blog sitemap files */
		if ( is_multisite() ) {
			foreach ( $masks as $mask ) {
				$mask_file = $mask . '_' . $blog_id . '*.xml';
				array_map( 'unlink', glob( ABSPATH . $mask_file ) );
			}
			if ( $gglstmp_del_init ) {
				array_map( 'unlink', glob( ABSPATH . $default_name . '.xml' ) );
			}
		} else {
			foreach ( $masks as $mask ) {
				$mask_file = $mask . '*.xml';
				array_map( 'unlink', glob( ABSPATH . $mask_file ) );
			}
		}

		/* update network index file */
		if ( ! $gglstmp_del_init ) {
			gglstmp_create_sitemap_index( 0 );
		}
	}
}

if ( ! function_exists( 'gglstmp_delete_settings' ) ) {
	/**
	 * Function for delete of the plugin settings on register_activation_hook
	 */
	function gglstmp_delete_settings() {
		global $wpdb;
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$all_plugins = get_plugins();

		if ( ! array_key_exists( 'google-sitemap-pro/google-sitemap-pro.php', $all_plugins ) &&
			! array_key_exists( 'google-sitemap-plus/google-sitemap-plus.php', $all_plugins ) ) {
			if ( is_multisite() ) {
				/* Get all blog ids */
				$blogids = $wpdb->get_col( "SELECT `blog_id` FROM $wpdb->blogs" );
				foreach ( $blogids as $blog_id ) {
					delete_blog_option( $blog_id, 'gglstmp_options' );
					delete_blog_option( $blog_id, 'gglstmp_robots' );
				}
			} else {
				delete_option( 'gglstmp_options' );
				delete_option( 'gglstmp_robots' );
			}
			/* remove all sitemaps */
			$sitemaps = gglstmp_get_sitemap_files( 'all' );
			array_map( 'unlink', $sitemaps );
		}

		require_once dirname( __FILE__ ) . '/bws_menu/bws_include.php';
		bws_include_init( plugin_basename( __FILE__ ) );
		bws_delete_plugin( plugin_basename( __FILE__ ) );
	}
}

register_activation_hook( __FILE__, 'gglstmp_activate' );

add_action( 'admin_menu', 'gglstmp_admin_menu' );

add_action( 'init', 'gglstmp_init', 100 );
add_action( 'admin_init', 'gglstmp_admin_init' );

/* Initialization */
add_action( 'plugins_loaded', 'gglstmp_plugins_loaded' );

add_action( 'admin_enqueue_scripts', 'gglstmp_add_plugin_stylesheet' );

add_action( 'transition_post_status', 'gglstmp_check_post_status', 10, 3 );
add_action( 'save_post', 'gglstmp_update_sitemap', 10, 2 );
add_action( 'trashed_post', 'gglstmp_update_sitemap' );

add_action( 'gglstmp_sitemap_cron', 'gglstmp_prepare_sitemap' );

/* Rebuild sitemap on permalink structure change, on taxonomy term add/edit/delete */
add_action( 'permalink_structure_changed', 'gglstmp_schedule_sitemap', 10, 0 );
add_action( 'created_term', 'gglstmp_edited_term', 10, 3 );
add_action( 'edited_term', 'gglstmp_edited_term', 10, 3 );
add_action( 'delete_term', 'gglstmp_edited_term', 10, 3 );

add_filter( 'rewrite_rules_array', 'gglstmp_rewrite_rules', PHP_INT_MAX, 1 );

add_action( 'wp_head', 'gglstmp_add_verification_code' );

add_filter( 'plugin_action_links', 'gglstmp_action_links', 10, 2 );
add_filter( 'plugin_row_meta', 'gglstmp_links', 10, 2 );

add_action( 'admin_notices', 'gglstmp_plugin_banner' );

add_action( 'wpmu_new_blog', 'gglstmp_add_sitemap' );
add_action( 'activate_blog', 'gglstmp_add_sitemap' );
add_action( 'make_undelete_blog', 'gglstmp_add_sitemap' );
add_action( 'unarchive_blog', 'gglstmp_add_sitemap' );
add_action( 'make_ham_blog', 'gglstmp_add_sitemap' );

add_action( 'delete_blog', 'gglstmp_delete_sitemap' );
add_action( 'deactivate_blog', 'gglstmp_delete_sitemap' );
add_action( 'make_delete_blog', 'gglstmp_delete_sitemap' );
add_action( 'archive_blog', 'gglstmp_delete_sitemap' );
add_action( 'make_spam_blog', 'gglstmp_delete_sitemap' );

/* Create custom meta box */
add_action( 'add_meta_boxes', 'gglstmp_add_custom_canonical_url' );
/* Save meta box content */
add_action( 'save_post', 'gglstmp_save_custom_canonical_tag_box' );
/* Add a custom meta box to a post */
