<?php
namespace BetterLinks\Link;

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\AbstractDeviceParser;
use DeviceDetector\Parser\OperatingSystem;
use DeviceDetector\Parser\Client\Browser;

class Utils {

	public function __construct() {
		AbstractDeviceParser::setVersionTruncation( AbstractDeviceParser::VERSION_TRUNCATION_NONE );
	}
	public function get_slug_raw( $slug ) {
		if ( BETTERLINKS_EXISTS_LINKS_JSON ) {
			return apply_filters( 'betterlinks/link/get_link_by_slug', \BetterLinks\Helper::get_link_from_json_file( $slug ) );
		}
		$link_options      = json_decode( get_option( BETTERLINKS_LINKS_OPTION_NAME, '{}' ), true );
		$is_case_sensitive = isset( $link_options['is_case_sensitive'] ) ? $link_options['is_case_sensitive'] : false;
		$results           = current( \BetterLinks\Helper::get_link_by_short_url( $slug, $is_case_sensitive ) );
		if ( ! empty( $results ) ) {
			return apply_filters( 'betterlinks/link/get_link_by_slug', json_decode( json_encode( $results ), true ) );
		}
		// wildcards
		$links_option = json_decode( get_option( BETTERLINKS_LINKS_OPTION_NAME ), true );
		if ( isset( $links_option['wildcards'] ) && $links_option['wildcards'] ) {
			$results = \BetterLinks\Helper::get_link_by_wildcards( 1 );
			if ( is_array( $results ) && count( $results ) > 0 ) {
				foreach ( $results as $key => $item ) {
					$postion = strpos( $item['short_url'], '/*' );
					if ( $postion !== false ) {
						$item_short_url_substr = substr( $item['short_url'], 0, $postion );
						$slug_substr           = substr( $slug, 0, $postion );
						if ( ! $is_case_sensitive ) {
							$item_short_url_substr = strtolower( $item_short_url_substr );
							$slug_substr           = strtolower( $slug_substr );
						}
						if ( $item_short_url_substr === $slug_substr ) {
							$target_postion = strpos( $item['target_url'], '/*' );
							if ( $target_postion !== false ) {
								$target_url         = str_replace( '/*', substr( $slug, $postion ), $item['target_url'] );
								$item['target_url'] = $target_url;
								return apply_filters( 'betterlinks/link/get_link_by_slug', json_decode( wp_json_encode( $item ), true ) );
							}
							return apply_filters( 'betterlinks/link/get_link_by_slug', json_decode( wp_json_encode( $item ), true ) );
						}
					}
				}
			}
		}
	}
	public function dispatch_redirect( $data, $param ) {
		global $betterlinks;
		$comparable_url  = rtrim( preg_replace( '/https?\:\/\//', '', site_url( '/' ) ), '/' ) . '/' . $data['short_url'];
		$destination_url = rtrim( preg_replace( '/https?\:\/\//', '', $data['target_url'] ), '/' );
		$comparable_url  = rtrim( preg_replace( '/^www\.?/', '', $comparable_url ), '/' );
		$destination_url = rtrim( preg_replace( '/^www\.?/', '', $destination_url ), '/' );
		if ( ! $data || $comparable_url === $destination_url ) {
			return;
		}
		if ( filter_var( $data['track_me'], FILTER_VALIDATE_BOOLEAN ) ) {
            $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''; // phpcs:ignore
			$dd         = new DeviceDetector( $user_agent );
			$dd->parse();

			$data            = $this->device_data_collect( $data, $dd );
			$data['os']      = OperatingSystem::getOsFamily( $dd->getOs( 'name' ) );
			$data['browser'] = Browser::getBrowserFamily( $dd->getClient( 'name' ) );
			$data['device']  = $dd->getDeviceName();

			if ( isset( $betterlinks['disablebotclicks'] ) && $betterlinks['disablebotclicks'] ) {
				if ( ! $dd->isBot() ) {
					$this->start_trakcing( $data );
				}
			} else {
				$this->start_trakcing( $data );
			}
		}

		$robots_tags = array();
		if ( filter_var( $data['sponsored'], FILTER_VALIDATE_BOOLEAN ) ) {
			$robots_tags[] = 'sponsored';
		}
		if ( filter_var( $data['nofollow'], FILTER_VALIDATE_BOOLEAN ) ) {
			$robots_tags[] = 'noindex';
			$robots_tags[] = 'nofollow';
		}
		if ( ! empty( $robots_tags ) ) {
			header( 'X-Robots-Tag: ' . implode( ', ', $robots_tags ), true );
		}

		header( 'Cache-Control: no-store, no-cache, must-revalidate, max-age=0' );
		header( 'Cache-Control: post-check=0, pre-check=0', false );
		header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
		header( 'Cache-Control: no-cache' );
		header( 'Pragma: no-cache' );
		header( 'X-Redirect-Powered-By:  https://www.betterlinks.io/' );

		$target_url = $this->addScheme( $data['target_url'] );
		if ( filter_var( $data['param_forwarding'], FILTER_VALIDATE_BOOLEAN ) && ! empty( $param ) && $param !== $data['link_slug'] ) {
			// $   target_url = $   targ    et_url . '?' . $    param;
			$_target_url   = wp_parse_url( $target_url );
			$_query_params = array();
			wp_parse_str( $param, $_query_params );
			$target_url .= ( isset( $_target_url['query'] ) ? '&' : '?' ) . build_query( $_query_params );
		}

		switch ( $data['redirect_type'] ) {
			case '301':
				wp_redirect( esc_url_raw( $target_url ), 301 );
				exit();
			case '302':
				wp_redirect( esc_url_raw( $target_url ), 302 );
				exit();
			case '307':
				wp_redirect( esc_url_raw( $target_url ), 307 );
				exit();
			case 'cloak':
				do_action( 'betterlinks/make_cloaked_redirect', $target_url, $data );
				exit();
			default:
				wp_redirect( esc_url_raw( $target_url ) );
				exit();
		}
	}
	public function device_data_collect( $data, $dd ) {
		if ( ! apply_filters( 'betterlinks/is_extra_data_tracking_compatible', false ) ) {
			return $data;
		}

		$client_info = $dd->getClient();
		$os_details  = $dd->getOs();

		$language = isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : 'en-US,en;q=0.5';
		$language = explode( ',', $language )[0];

		$client_information_arr = array(
			'brand_name'      => $dd->getBrandName(),
			'model'           => $dd->getModel(),
			'bot_name'        => $dd->isBot() ? $dd->getBot()['name'] : null,
			'browser_type'    => isset( $client_info['type'] ) ? $client_info['type'] : null,
			'browser_version' => isset( $client_info['version'] ) ? $client_info['version'] : null,
			'os_version'      => isset( $os_details['version'] ) ? $os_details['version'] : null,
			'language'        => ! empty( $language ) ? $language : 'en-US',
		);

		return array_merge( $data, $client_information_arr );
	}
	public function start_trakcing( $data ) {
		global $betterlinks;
		$is_disable_analytics_ip = isset( $betterlinks['is_disable_analytics_ip'] ) ? $betterlinks['is_disable_analytics_ip'] : false;
		do_action( 'betterlinks/link/before_start_tracking', $data );
		$now            = current_time( 'mysql' );
		$now_gmt        = current_time( 'mysql', 1 );
		$visitor_cookie = 'betterlinks_visitor';
		if ( ! isset( $_COOKIE[ $visitor_cookie ] ) ) {
			$visitor_cookie_expire_time = time() + 60 * 60 * 24 * 365; // 1 year
			$visitor_uid                = uniqid( 'bl' );
			setcookie( $visitor_cookie, $visitor_uid, $visitor_cookie_expire_time, '/' );
		}
		// checking is split tes enabled
		$split_test_data  = \BetterLinks\Helper::split_test_enabled( $data );
		$is_split_enabled = isset( $split_test_data['result'] ) ? $split_test_data['result'] : false;

		if ( ! $is_split_enabled && ! empty( $split_test_data['completed'] ) ) {
			if ( ! \BetterLinks\Helper::get_link_meta( $data['ID'], 'split_test_data' ) ) {
				\BetterLinks\Helper::add_link_meta( $data['ID'], 'split_test_data', $split_test_data );
			}

			if ( class_exists( '\BetterLinksPro\Helper' ) && ! \BetterLinks\Helper::get_link_meta( $data['ID'], 'split_test_analytics' ) ) {
				$split_analytics = \BetterLinksPro\Helper::get_split_test_analytics_data( array( 'id' => $data['ID'] ) );
				\BetterLinks\Helper::add_link_meta( $data['ID'], 'split_test_analytics', $split_analytics );
			}
		}

		$click_data = array(
			'link_id'             => $data['ID'],
			'browser'             => isset( $data['browser'] ) ? $data['browser'] : '',
			'os'                  => isset( $data['os'] ) ? $data['os'] : '',
			'device'              => isset( $data['device'] ) ? $data['device'] : '',
			'referer'             => isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '',
			'uri'                 => $data['link_slug'],
			'click_count'         => 0,
			'visitor_id'          => isset( $_COOKIE[ $visitor_cookie ] ) ? sanitize_text_field( $_COOKIE[ $visitor_cookie ] ) : '',
			'click_order'         => 0,
			'created_at'          => $now,
			'created_at_gmt'      => $now_gmt,
			'rotation_target_url' => $data['target_url'],
			'target_url'          => $data['target_url'],
			'is_split_enabled'    => $is_split_enabled,
		);
		if ( ! $is_disable_analytics_ip ) {
			$IP                 = $this->get_current_client_IP();
			$click_data['ip']   = $IP;
			$click_data['host'] = $IP;
		}

		if ( apply_filters( 'betterlinks/is_extra_data_tracking_compatible', false ) ) {
			$click_data['brand_name']      = $data['brand_name'];
			$click_data['model']           = $data['model'];
			$click_data['bot_name']        = $data['bot_name'];
			$click_data['browser_type']    = $data['browser_type'];
			$click_data['browser_version'] = $data['browser_version'];
			$click_data['os_version']      = $data['os_version'];
			$click_data['language']        = $data['language'];
		}

		$arg = apply_filters( 'betterlinks/link/insert_click_arg', $click_data );

		if ( BETTERLINKS_EXISTS_CLICKS_JSON ) {
			$this->insert_json_into_file( BETTERLINKS_UPLOAD_DIR_PATH . '/clicks.json', $arg );
		} else {
			try {
				$click_id = \BetterLinks\Helper::insert_click( $arg );
				if ( ! empty( $click_id ) && $is_split_enabled ) {
					do_action( 'betterlinks/link/after_insert_click', $arg['link_id'], $click_id, $arg['target_url'] );
				}
			} catch ( \Throwable $th ) {
				echo $th->getMessage();
			}
		}
	}

	public function get_current_client_IP() {
		$address = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( $_SERVER['REMOTE_ADDR'] ) : '';
		if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) && $_SERVER['HTTP_CLIENT_IP'] != '127.0.0.1' ) {
			$address = sanitize_text_field( $_SERVER['HTTP_CLIENT_IP'] );
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED'] ) && $_SERVER['HTTP_X_FORWARDED'] != '127.0.0.1' ) {
			$address = sanitize_text_field( $_SERVER['HTTP_X_FORWARDED'] );
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '127.0.0.1' ) {
			$address = sanitize_text_field( $_SERVER['HTTP_X_FORWARDED_FOR'] );
		} elseif ( isset( $_SERVER['HTTP_FORWARDED'] ) && $_SERVER['HTTP_FORWARDED'] != '127.0.0.1' ) {
			$address = sanitize_text_field( $_SERVER['HTTP_FORWARDED'] );
		} elseif ( isset( $_SERVER['HTTP_FORWARDED_FOR'] ) && $_SERVER['HTTP_FORWARDED_FOR'] != '127.0.0.1' ) {
			$address = sanitize_text_field( $_SERVER['HTTP_FORWARDED_FOR'] );
		}
		$IPS = explode( ',', $address );
		if ( isset( $IPS[1] ) ) {
			$address = $IPS[0];
		}
		return $address;
	}
	public function addScheme( $url, $scheme = 'http://' ) {
		if ( strpos( $url, '/' ) === 0 ) {
			return $url = site_url( '/' ) . $url;
		}
		return apply_filters( 'betterlinks/link/target_url', parse_url( $url, PHP_URL_SCHEME ) === null ? $scheme . $url : $url );
	}

	protected function insert_json_into_file( $file, $data ) {
		$existingData = file_get_contents( $file );
		$tempArray    = (array) json_decode( $existingData, true );
		array_push( $tempArray, $data );
		return file_put_contents( $file, json_encode( $tempArray ) );
	}

	/**
	 * @param string $request_uri - REQUEST_URI
	 *
	 * @return boolean - returns true if the request uri is self site url
	 */
	protected function is_self_url( $request_uri ) {
		// if the referer url is password-protected-form page, then return false;
		if ( str_contains( $request_uri, 'password-protected-form?short_url' ) ) {
			return false;
		}
		$is_self_url = url_to_postid( $request_uri );
		return ! empty( $is_self_url );
	}

	/**
	 * @param string $request_uri REQUEST_URI
	 *
	 * @return string Short URL
	 */
	public function get_protected_self_url_short_link( $request_uri ) {
		global $wpdb;
		$is_self_url = $this->is_self_url( $request_uri );
		if ( empty( $is_self_url ) ) {
			return false;
		}

		$sql = "SELECT l.short_url FROM {$wpdb->prefix}betterlinks AS l 
                LEFT JOIN {$wpdb->prefix}betterlinks_password as p 
                    on l.ID=p.link_id 
                where l.target_url='{$request_uri}' and p.status='1';";

		$short_url = $wpdb->get_var( $sql );
		return $short_url;
	}

	public function referer_short_url( $referer ) {
		if ( empty( $referer ) ) {
			return false;
		}
		$password_param = explode( '?short_url=', $referer );
		if ( count( $password_param ) > 1 ) {
			return $password_param[1];
		}
		return false;
	}

	/**
	 * @param boolean $remember_cookies - remember cookies setting enabled or not
	 * @param integer $id - id of the short link
	 *
	 * @return boolean - returns true if password is okay, and cookie is valid, otherwise returns false
	 */
	public function passsword_cookie_enabled( $remember_cookies, $id ) {
		$cookie_name = "betterlinks_pass_protect_{$id}";
		if ( ! empty( $remember_cookies ) && isset( $_COOKIE[ $cookie_name ] ) && class_exists( '\BetterLinksPro\Helper' ) ) {
			$result = \BetterLinksPro\Helper::check_password( $_COOKIE[ $cookie_name ], $id );
			return $result;
		}
		return false;
	}
}
