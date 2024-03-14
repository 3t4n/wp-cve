<?php
namespace BetterLinks;

class Helper {

	use Traits\Query;
	use Traits\Clicks;

	public static function btl_menu_notice() {
		return BETTERLINKS_MENU_NOTICE !== get_option( 'betterlinks_menu_notice', 0 );
	}
	public static function get_links() {
		if ( BETTERLINKS_EXISTS_LINKS_JSON ) {
			$data = json_decode( file_get_contents( BETTERLINKS_UPLOAD_DIR_PATH . '/links.json' ), true );
			if ( empty( $data ) ) {
				$cron = new Cron();
				$cron->write_json_links();
				return json_decode( file_get_contents( BETTERLINKS_UPLOAD_DIR_PATH . '/links.json' ), true );
			}
			return $data;
		}
		$options = json_decode( get_option( BETTERLINKS_LINKS_OPTION_NAME ), true );
		return is_array( $options )
				? array(
					'wildcards_is_active'         => isset( $options['wildcards'] ) ? $options['wildcards'] : false,
					'disablebotclicks'            => isset( $options['disablebotclicks'] ) ? $options['disablebotclicks'] : false,
					'force_https'                 => isset( $options['force_https'] ) ? $options['force_https'] : false,
					'autolink_disable_post_types' => isset( $options['autolink_disable_post_types'] ) ? $options['autolink_disable_post_types'] : array(),
					'is_autolink_icon'            => isset( $options['is_autolink_icon'] ) ? $options['is_autolink_icon'] : false,
					'is_autolink_headings'        => isset( $options['is_autolink_headings'] ) ? $options['is_autolink_headings'] : false,
					'uncloaked_categories'        => isset( $options['uncloaked_categories'] ) ? $options['uncloaked_categories'] : array(),
					'is_disable_analytics_ip'     => isset( $options['is_disable_analytics_ip'] ) ? $options['is_disable_analytics_ip'] : false,
				)
				: array(
					'wildcards_is_active' => false,
					'disablebotclicks'    => false,
					'force_https'         => false,
				);
	}

	public static function split_test_enabled( $data ) {
		$extra = null;
		$id    = null;
		if ( 'string' === gettype( $data ) ) {
			$id               = $data;
			$dynamic_redirect = self::get_link_data_by_id( $id, 'dynamic_redirect' );

			$split_test_data_meta = self::get_link_meta( $id, 'split_test_data' );

			if ( ! empty( $split_test_data_meta ) ) {
				$split_test_data_meta              = (array) $split_test_data_meta;
				$split_test_data_meta['completed'] = true;
				return $split_test_data_meta;
			}

			if ( is_string( $dynamic_redirect ) ) {
				$dynamic_redirect = json_decode( $dynamic_redirect );
			}
			$extra = isset( $dynamic_redirect->extra ) ? (array) $dynamic_redirect->extra : null;
		} else {
			$id                   = $data['ID'];
			$split_test_data_meta = self::get_link_meta( $id, 'split_test_data' );
			if ( ! empty( $split_test_data_meta ) ) {
				$split_test_data_meta              = (array) $split_test_data_meta;
				$split_test_data_meta['completed'] = true;
				return $split_test_data_meta;
			}
			$extra = isset( $data['dynamic_redirect'], $data['dynamic_redirect']['extra'] ) ? $data['dynamic_redirect']['extra'] : null;
		}
		if ( empty( $extra ) ) {
			return false;
		}

		$is_enable = isset( $extra['split_test'] ) ? '1' === $extra['split_test'] : false;
		
		if ( ! $is_enable ) {
			return false;
		}

		$is_expire_enable = isset( $extra['expire_split'] ) ? '1' === $extra['expire_split'] : false;
		
		if ( $is_enable && ! $is_expire_enable ) {
			return ['result' => true];
		}

		$expire_metrics      = isset( $extra['expire_split_after'] ) ? $extra['expire_split_after'] : 'clicks';
		$expire_split_clicks = isset( $extra['expire_split_clicks'] ) ? (int) $extra['expire_split_clicks'] : 0;
		$clicks_count        = null;

		if ( class_exists( '\BetterLinksPro\Helper' ) ) {
			$pro_helper = new \BetterLinksPro\Helper();

			if ( 'clicks' === $expire_metrics ) {
				$clicks_count = $pro_helper::get_individual_clicks_count( $id );
			} elseif ( 'unique_clicks' === $expire_metrics ) {
				$clicks_count = $pro_helper::get_individual_unique_clicks_count( $id );
			}
		}

		$result = $clicks_count < $expire_split_clicks;
		return array(
			'result'              => $result,
			'expire_metrics'      => $expire_metrics,
			'expire_split_clicks' => $expire_split_clicks,
			'clicks_count'        => $clicks_count,
			'completed'           => ! $result,
		);
	}

	public static function get_link_from_json_file( $short_url ) {
		if ( empty( $short_url ) ) {
			return;
		}
		global $betterlinks;
		if ( ! ( isset( $betterlinks['is_case_sensitive'] ) && $betterlinks['is_case_sensitive'] ) ) {
			$short_url = strtolower( $short_url );
		}
		if ( isset( $betterlinks['links'][ $short_url ] ) ) {
			return $betterlinks['links'][ $short_url ];
		}
		if ( isset( $betterlinks['wildcards_is_active'] ) && $betterlinks['wildcards_is_active'] ) {
			if ( isset( $betterlinks['wildcards'] ) && count( $betterlinks['wildcards'] ) > 0 ) {
				foreach ( $betterlinks['wildcards'] as $key => $item ) {
					$postion = strpos( $key, '/*' );
					if ( false !== $postion ) {
						if ( substr( $key, 0, $postion ) === substr( $short_url, 0, $postion ) ) {
							$target_postion = strpos( $item['target_url'], '/*' );
							if ( false !== $target_postion ) {
								$target_url         = str_replace( '/*', substr( $short_url, $postion ), $item['target_url'] );
								$item['target_url'] = $target_url;
								return $item;
							}
							return $item;
						}
					}
				}
			}
		}
	}

	public static function get_menu_items() {
		$menu_items = array(
			BETTERLINKS_PLUGIN_SLUG                  => array(
				'title'      => __( 'Manage Links', 'betterlinks' ),
				'capability' => 'manage_options',
			),
			BETTERLINKS_PLUGIN_SLUG . '-manage-tags' => array(
				'title'      => __( 'Manage Tags', 'betterlinks' ),
				'capability' => 'manage_options',
			),
			BETTERLINKS_PLUGIN_SLUG . '-analytics'   => array(
				'title'      => __( 'Analytics', 'betterlinks' ),
				'capability' => 'manage_options',
			),
			BETTERLINKS_PLUGIN_SLUG . '-settings'    => array(
				'title'      => __( 'Settings', 'betterlinks' ),
				'capability' => 'manage_options',
			),
		);
		return apply_filters( 'betterlinks/helper/menu_items', $menu_items );
	}

	/**
	 * Check Supported Post type for admin page and plugin main settings page
	 *
	 * @return bool
	 */
	public static function plugin_page_hook_suffix( $hook ) {
		if ( 'toplevel_page_' . BETTERLINKS_PLUGIN_SLUG === $hook ) {
			return true;
		} else {
			foreach ( self::get_menu_items() as $key => $value ) {
				if ( BETTERLINKS_PLUGIN_SLUG . '_page_' . $key === $hook || strpos( $hook, BETTERLINKS_PLUGIN_SLUG . '_page_' . $key ) || strpos( '_' . $hook, 'betterlinks' ) ) {
					return true;
				}
			}
		}
		return false;
	}

	public static function make_slug( $str ) {
		if ( empty( $str ) ) {
			return;
		}
		if ( $str !== mb_convert_encoding( mb_convert_encoding( $str, 'UTF-32', 'UTF-8' ), 'UTF-8', 'UTF-32' ) ) {
			$str = mb_convert_encoding( $str, 'UTF-8', mb_detect_encoding( $str ) );
		}
		$str = htmlentities( $str, ENT_NOQUOTES, 'UTF-8' );
		$str = preg_replace( '`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\\1', $str );
		$str = html_entity_decode( $str, ENT_NOQUOTES, 'UTF-8' );
		$str = preg_replace( array( '`[^a-z0-9]`i', '`[-]+`' ), '-', $str );
		$str = strtolower( trim( $str, '-' ) );
		$str = substr( $str, 0, 100 );
		return $str;
	}

	public static function link_exists( $title, $slug = '' ) {
		global $wpdb;

		$link_title  = wp_unslash( sanitize_post_field( 'link_title', $title, 0, 'db' ) );
		$short_url   = wp_unslash( sanitize_post_field( 'short_url', $slug, 0, 'db' ) );
		$betterlinks = $wpdb->prefix . 'betterlinks';
		$query       = "SELECT link_title, short_url FROM  $betterlinks WHERE ";
		$args        = array();

		if ( ! empty( $title ) ) {
			$query .= ' link_title = %s';
			$args[] = $link_title;
		}

		if ( ! empty( $slug ) ) {
			$query .= ' AND short_url = %s';
			$args[] = $short_url;
		}

		if ( ! empty( $args ) ) {
			$results = $wpdb->get_var( $wpdb->prepare( $query, $args ) );
			if ( ! empty( $results ) ) {
				return true;
			}
			return;
		}
	}
	public static function term_exists( $slug ) {
		global $wpdb;

		$term_slug   = wp_unslash( sanitize_post_field( 'term_slug', $slug, 0, 'db' ) );
		$betterlinks = $wpdb->prefix . 'betterlinks_terms';
		$query       = "SELECT term_slug FROM  $betterlinks WHERE ";
		$args        = array();

		if ( ! empty( $slug ) ) {
			$query .= ' term_slug = %s';
			$args[] = $term_slug;
		}

		if ( ! empty( $args ) ) {
			$results = $wpdb->get_var( $wpdb->prepare( $query, $args ) );
			if ( ! empty( $results ) ) {
				return true;
			}
			return;
		}
	}
	public static function click_exists( $ID ) {
		global $wpdb;
		$click_ID    = wp_unslash( sanitize_post_field( 'ID', $ID, 0, 'db' ) );
		$betterlinks = $wpdb->prefix . 'betterlinks_clicks';
		$query       = "SELECT ID FROM  $betterlinks WHERE ";
		$args        = array();

		if ( ! empty( $click_ID ) ) {
			$query .= ' ID = %d';
			$args[] = $click_ID;
		}

		if ( ! empty( $args ) ) {
			$results = $wpdb->get_var( $wpdb->prepare( $query, $args ) );
			if ( ! empty( $results ) ) {
				return true;
			}
			return;
		}
	}

	public static function create_cron_jobs_for_json_links() {
		wp_clear_scheduled_hook( 'betterlinks/write_json_links' );
		wp_schedule_single_event( time() + 5, 'betterlinks/write_json_links' );
	}

	public static function write_links_inside_json() {
		$cron = new Cron();
		$cron->write_json_links();
	}

	public static function create_cron_jobs_for_analytics() {
		wp_clear_scheduled_hook( 'betterlinks/analytics' );
		wp_schedule_single_event( time() + 5, 'betterlinks/analytics' );
	}

	public static function clear_query_cache() {
		delete_transient( BETTERLINKS_CACHE_LINKS_NAME );
	}

	public static function parse_link_response( $items, $analytic, $broken_links ) {
		$results                  = array();
		$broken_link_status_codes = array( 401, 403, 404 );

		$tags_list = array();

		foreach ( $items as $item ) {
			if ( null !== $item->ID && 'tags' === $item->term_type ) {
				array_push( $tags_list, $item );
			}
		}

		foreach ( $items as $item ) {
			if ( 'category' === $item->term_type ) {
				// insert analytic data.
				if ( isset( $analytic[ $item->ID ] ) ) {
					$item->analytic = $analytic[ $item->ID ];
				}

				// $item->old_link_status = $item->link_status;.
				if ( isset( $broken_links[ $item->ID ] ) && in_array( $broken_links[ $item->ID ]['status']['status_code'], $broken_link_status_codes ) && empty( $broken_links[ $item->ID ]['is_log_removed'] ) ) {
					$item->link_status = 'broken';
				} elseif ( 'broken' === $item->link_status && isset( $broken_links[ $item->ID ]['old_link_status'] ) && 'broken' !== $broken_links[ $item->ID ]['old_link_status'] ) {
					// if the link is fixed, but if db is not updated it to fixed link immediately then it will be marked as old status code.
					$item->link_status = $broken_links[ $item->ID ]['old_link_status'];
				}
				$item->tags_data = array();

				foreach ( $tags_list as $tag ) {
					if ( $tag->ID === $item->ID ) {
						array_push(
							$item->tags_data,
							array(
								'term_id'   => $tag->cat_id,
								'term_name' => $tag->term_name,
								'term_slug' => $tag->term_slug,
							)
						);
					}
				}

				// formatting response.
				if ( ! isset( $results[ $item->cat_id ] ) ) {
					$results[ $item->cat_id ] = array(
						'term_name' => $item->term_name,
						'term_slug' => $item->term_slug,
						'term_type' => $item->term_type,
					);
					if ( null !== $item->ID ) {
						$results[ $item->cat_id ]['lists'][] = $item;
					} else {
						$results[ $item->cat_id ]['lists'] = array();
					}
				} else {
					$results[ $item->cat_id ]['lists'][] = $item;
				}
			}
		}
		return $results;
	}
	public static function json_link_formatter( $data ) {
		$res = array(
			'ID'               => $data['ID'],
			'link_slug'        => $data['link_slug'],
			'link_status'      => ( isset( $data['link_status'] ) ? $data['link_status'] : 'publish' ),
			'short_url'        => $data['short_url'],
			'redirect_type'    => ( isset( $data['redirect_type'] ) ? $data['redirect_type'] : '307' ),
			'target_url'       => $data['target_url'],
			'nofollow'         => ( isset( $data['nofollow'] ) ? $data['nofollow'] : false ),
			'sponsored'        => ( isset( $data['sponsored'] ) ? $data['sponsored'] : false ),
			'param_forwarding' => ( isset( $data['param_forwarding'] ) ? $data['param_forwarding'] : false ),
			'track_me'         => ( isset( $data['track_me'] ) ? $data['track_me'] : false ),
			'wildcards'        => ( isset( $data['wildcards'] ) ? $data['wildcards'] : false ),
			'expire'           => ( isset( $data['expire'] ) ? $data['expire'] : null ),
			'dynamic_redirect' => ( isset( $data['dynamic_redirect'] ) ? $data['dynamic_redirect'] : null ),
			'cat_id'           => isset( $data['cat_id'] ) ? $data['cat_id'] : null,
		);
		if ( isset( $data['uncloaked'] ) && $data['uncloaked'] ) {
			$res['uncloaked'] = $data['uncloaked'];
		}
		return $res;
	}
	public static function insert_json_into_file( $file, $data ) {
		$existingData              = file_get_contents( $file );
		$existingData              = json_decode( $existingData, true );
		$case_sensitive_is_enabled = isset( $existingData['is_case_sensitive'] ) ? $existingData['is_case_sensitive'] : false;
		$short_url                 = $case_sensitive_is_enabled ? $data['short_url'] : strtolower( $data['short_url'] );
		if ( isset( $data['wildcards'] ) && $data['wildcards'] ) {
			$tempArray                 = $existingData['wildcards'];
			$tempArray[ $short_url ]   = self::json_link_formatter( $data );
			$existingData['wildcards'] = $tempArray;
		} else {
			$tempArray               = ( isset( $existingData['links'] ) ? $existingData['links'] : array() );
			$tempArray[ $short_url ] = self::json_link_formatter( $data );
			$existingData['links']   = $tempArray;
		}
		return file_put_contents( $file, wp_json_encode( $existingData ) );
	}
	public static function update_json_into_file( $file, $data, $old_short_url = '' ) {
		if ( ! isset( $data['short_url'] ) ) {
			return false;
		}
		$existingData              = file_get_contents( $file );
		$existingData              = json_decode( $existingData, true );
		$case_sensitive_is_enabled = isset( $existingData['is_case_sensitive'] ) ? $existingData['is_case_sensitive'] : false;
		$short_url                 = $case_sensitive_is_enabled ? $data['short_url'] : strtolower( $data['short_url'] );
		if ( isset( $data['wildcards'] ) && ! empty( $data['wildcards'] ) ) {
			$tempArray = $existingData['wildcards'];
			if ( is_array( $tempArray ) ) {
				if ( ! empty( $old_short_url ) ) {
					unset( $tempArray[ $old_short_url ] );
					unset( $tempArray[ strToLower( $old_short_url ) ] );
				}
				$tempArray[ $short_url ]   = self::json_link_formatter( $data );
				$existingData['wildcards'] = $tempArray;
				return file_put_contents( $file, wp_json_encode( $existingData ) );
			}
		} else {
			$tempArray = $existingData['links'];
			if ( is_array( $tempArray ) ) {
				if ( ! empty( $old_short_url ) ) {
					unset( $tempArray[ $old_short_url ] );
					unset( $tempArray[ strToLower( $old_short_url ) ] );
				}
				$tempArray[ $short_url ] = self::json_link_formatter( $data );
				$existingData['links']   = $tempArray;
				return file_put_contents( $file, wp_json_encode( $existingData ) );
			}
		}
	}
	public static function delete_json_into_file( $file, $short_url ) {
		$existingData = file_get_contents( $file );
		$existingData = json_decode( $existingData, true );
		if ( isset( $existingData['wildcards'][ $short_url ] ) || isset( $existingData['wildcards'][ strToLower( $short_url ) ] ) ) {
			$tempArray = $existingData['wildcards'];
			if ( is_array( $tempArray ) ) {
				unset( $tempArray[ $short_url ] );
				unset( $tempArray[ strToLower( $short_url ) ] );
				$existingData['wildcards'] = $tempArray;
				return file_put_contents( $file, wp_json_encode( $existingData ) );
			}
		} elseif ( isset( $existingData['links'][ $short_url ] ) || isset( $existingData['links'][ strtolower( $short_url ) ] ) ) {
			$tempArray = $existingData['links'];
			if ( is_array( $tempArray ) ) {
				unset( $tempArray[ $short_url ] );
				unset( $tempArray[ strtolower( $short_url ) ] );
				$existingData['links'] = $tempArray;
				return file_put_contents( $file, wp_json_encode( $existingData ) );
			}
		}
		return;
	}

	public static function is_exists_short_url( $short_url ) {
		$resutls = self::get_link_by_short_url( $short_url );
		if ( count( $resutls ) > 0 ) {
			return true;
		}
		return false;
	}

	public static function sanitize_text_or_array_field( $array_or_string ) {
		$boolean = array( 'true', 'false', '1', '0' );
		$skip    = array( 'affiliate_disclosure_text', 'allow_contact_text', 'form_title' );
		if ( is_string( $array_or_string ) ) {
			$array_or_string = in_array( $array_or_string, $boolean ) || is_bool( $array_or_string ) ? rest_sanitize_boolean( $array_or_string ) : sanitize_text_field( $array_or_string );
		} elseif ( is_array( $array_or_string ) ) {
			foreach ( $array_or_string as $key => &$value ) {
				if ( in_array( $key, $skip ) ) {
					continue;
				}
				if ( is_array( $value ) ) {
					$value = self::sanitize_text_or_array_field( $value );
				} else {
					$value = in_array( $value, $boolean ) || is_bool( $value ) ? rest_sanitize_boolean( $value ) : sanitize_text_field( $value );
				}
			}
		}
		return $array_or_string;
	}
	public static function fresh_ajax_request_data( $data ) {
		$remove = array( 'action', 'security' );
		return array_diff_key( $data, array_flip( $remove ) );
	}

	public static function force_relative_url( $url ) {
		return preg_replace( '/^(http)?s?:?\/\/[^\/]*(\/?.*)$/i', '$2', '' . $url );
	}

	/**
	 * Normalizing Clicks Data
	 *
	 * This function is responsible for manualy filter the duplicates IPs and link_id's from the data.
	 *
	 * @internal this is used in update_links_analytics for clicks on cron hook called 'betterlinks/analytics'
	 *
	 * @since 1.3.1
	 *
	 * @param array $data This should be the clicks data for IP's and links.
	 * @return array
	 */
	public static function normalize_ips_data( &$data ) {
		$_results = array();
		if ( ! empty( $data ) ) {
			foreach ( $data as &$analytic ) {
				$_link_id    = $analytic['link_id'];
				$_link_count = $analytic['lidc'];
				$_ip         = isset( $analytic['ip'] ) ? trim( $analytic['ip'] ) : '';
				$_ip_count   = $analytic['ipc'];

				if ( ! isset( $_results[ $_link_id ] ) ) {
					$_results[ $_link_id ] = array(
						'link_count' => $_link_count,
						'ip'         => array(),
					);
				}

				if ( $_ip && ! isset( $_results[ $_link_id ]['ip'][ $_ip ] ) ) {
					$_results[ $_link_id ]['ip'][ $_ip ] = $_ip_count;
				}
			}
		}

		return $_results;
	}

	public static function update_links_analytics() {
		$results      = array();
		$clicks_count = self::get_clicks_count();

		$total_clicks  = $clicks_count['total_clicks'];
		$unique_clicks = $clicks_count['unique_clicks'];

		for ( $i = 0; $i < count( $total_clicks ); $i++ ) {
			$results[ $total_clicks[ $i ]['link_id'] ] = array(
				'link_count' => $total_clicks[ $i ]['total_clicks'],
				'ip'         => isset( $unique_clicks[ $i ]['unique_clicks'] ) ? $unique_clicks[ $i ]['unique_clicks'] : 1,
			);
		}

		return update_option( 'betterlinks_analytics_data', wp_json_encode( $results ), false );
	}

	public static function maybe_json( $data ) {
		if ( is_array( $data ) || is_object( $data ) ) {
			return wp_json_encode( $data );
		}

		if ( is_string( $data ) ) {
			return sanitize_text_field( $data );
		}

		return $data;
	}
	public static function generate_short_url( $short_url ) {
		return site_url( '/' ) . trim( $short_url, '/' );
	}

	public static function btl_get_option( $option_name ) {
		global $wpdb;
		$result = $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}options WHERE option_name=%s", $option_name ),
			ARRAY_A
		);
		$value  = false;
		if ( ! empty( $result['option_id'] ) ) {
			$value = maybe_unserialize( $result['option_value'] );
		}
		return $value;
	}
	public static function btl_update_option( $option_name, $option_value, $careless_insert = false, $careless_update = false ) {
		global $wpdb;
		$option_value = maybe_serialize( $option_value );
		$result       = false;
		if ( ! $careless_insert && ! $careless_update ) {
			$result = $wpdb->get_row(
				$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}options WHERE option_name=%s", $option_name ),
				ARRAY_A
			);
		}
		if ( $careless_insert || ( ! $careless_update && empty( $result['option_id'] ) ) ) {
			$result = $wpdb->query(
				$wpdb->prepare(
					"INSERT INTO {$wpdb->prefix}options ( option_name, option_value, autoload ) VALUES ( %s, %s, %s )",
					array(
						$option_name,
						$option_value,
						'no',
					)
				)
			);
			return $result;
		}
		if ( $careless_update || ! empty( $result['option_id'] ) ) {
			$result = $wpdb->update(
				"{$wpdb->prefix}options",
				array(
					'option_value' => $option_value,
					'autoload'     => 'no',
				),
				array( 'option_name' => $option_name )
			);
			return $result !== false;
		}
	}
	public static function btl_update_autoload_option( $option_name, $autoload = false ) {
		global $wpdb;
		$result = $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}options WHERE option_name=%s", $option_name ),
			ARRAY_A
		);

		if ( ! empty( $result['option_id'] ) && ! empty( $result['option_value'] ) ) {
			if ( $autoload === false ) {
				$result = $wpdb->update(
					"{$wpdb->prefix}options",
					array(
						'option_value' => $result['option_value'],
						'autoload'     => 'no',
					),
					array( 'option_name' => $option_name )
				);
			} elseif ( $autoload === true ) {
				$result = $wpdb->update(
					"{$wpdb->prefix}options",
					array(
						'option_value' => $result['option_value'],
						'autoload'     => 'yes',
					),
					array( 'option_name' => $option_name )
				);
			}
			return $result !== false;
		}
	}
	public static function run_migration_for_ptrl_links_in_background( $installer, $links_count ) {
		global $wpdb;
		$per_page   = 10000;
		$total_page = ceil( $links_count / $per_page );
		for ( $page = 1; $page <= $total_page; $page++ ) {
			$offset = ( $page - 1 ) * $per_page;
			$links  = $wpdb->get_col(
				"SELECT concat('prli_links-', ID) AS ID FROM {$wpdb->prefix}prli_links LIMIT $per_page OFFSET {$offset}",
				0
			);
			$installer->data( $links )->save();
		}
		$installer->data( array( 'betterlinks_ptl_links_migrated' ) )->save();
		return $installer;
	}
	public static function run_migration_for_ptrl_clicks_in_background( $installer, $clicks_count ) {
		global $wpdb;
		$per_page   = 10000;
		$total_page = ceil( $clicks_count / $per_page );
		for ( $page = 1; $page <= $total_page; $page++ ) {
			$offset = ( $page - 1 ) * $per_page;
			$clicks = $wpdb->get_col(
				"SELECT concat('prli_clicks-', ID) AS ID FROM {$wpdb->prefix}prli_clicks LIMIT $per_page OFFSET {$offset}",
				0
			);
			$installer->data( $clicks )->save();
		}
		$installer->data( array( 'betterlinks_ptl_clicks_migrated' ) )->save();
		return $installer;
	}
}
