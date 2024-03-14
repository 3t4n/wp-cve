<?php

class SEO_Backlink_Monitor_Helper {

	public static $ua_desktop = [
		'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36',
		'Mozilla/5.0 (Macintosh; Intel Mac OS X 11_2_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36',
		'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:86.0) Gecko/20100101 Firefox/86.0',
		'Mozilla/5.0 (Macintosh; Intel Mac OS X 11_2_3) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0.3 Safari/605.1.15',
		'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36',
	];

	public static $ua_mobile = [
		'Mozilla/5.0 (iPhone; CPU iPhone OS 14_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1',
		'Mozilla/5.0 (iPhone; CPU iPhone OS 14_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/87.0.4280.77 Mobile/15E148 Safari/604.1',
		'Mozilla/5.0 (Linux; Android 10; SM-A102U) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Mobile Safari/537.36',
		'Mozilla/5.0 (Android 11; Mobile; rv:68.0) Gecko/68.0 Firefox/86.0',
	];

	public static function link_validator( $linkTo, $linkFrom, $mobile = false ) {
		$args = [
			'sslverify' => false,
			'user-agent' => self::$ua_desktop[array_rand(self::$ua_desktop, 1)],
		];
		if ( $mobile ) {
			$args['user-agent'] = self::$ua_mobile[array_rand(self::$ua_mobile, 1)];
		}
		$response = wp_remote_get( $linkFrom, $args );
		$response_code = wp_remote_retrieve_response_code( $response );
		// $urlHost = wp_parse_url( $linkTo );

		if( ! is_wp_error( $response ) && $response_code === 200 ) {

			if( is_array( $response ) ) {
				$response = $response['body'];
			}

			libxml_use_internal_errors( true );
			$doc = new \DOMDocument();
			$doc->loadHTML( $response );
			$links = [];
			$foundLinks = 0;
			$arr = $doc->getElementsByTagName('a');

			foreach ( $arr as $item ) {
				$href = $item->getAttribute("href");
				$rel = $item->getAttribute("rel");
				$text = trim( preg_replace( "/[\r\n]+/", " ", $item->nodeValue ) );

				# not exact link
				// if ( strpos( $href, $urlHost['host'] ) !== false ) {
				//     $foundLinks++;
				//     if ($foundLinks === 1) {
				//         $links = [ 'href' => $href, 'text' => $text, 'rel' => $rel ];
				//     }
				// }
				# exact link
				if ( self::get_url_without_https_and_lastslash($href) === self::get_url_without_https_and_lastslash($linkTo) ) {
					$foundLinks++;
					if ($foundLinks === 1) {
						$links = [ 'href' => $href, 'text' => $text, 'rel' => $rel ];
					}
				}
			}
			if( isset( $links['href'] ) && $foundLinks > 0 ) {
				$links['found'] = $foundLinks;
				return $links;
			} else {
				return [ 'href' => '', 'text' => '', 'rel' => '', 'found' => 0 ];
			}
		} elseif ( $response_code !== 200 ) {
			return $response_code;
		}
		return false;
	}

	public static function link_details( $linkvalidator ) {
		if (!$linkvalidator || !is_array($linkvalidator)) {
			return [
				'follow' => 0,
				'status' => !$linkvalidator ? 2 : $linkvalidator, // != 200
				'text' => ''
			];
		}
		elseif ($linkvalidator['found'] === 0) {
			return [
				'follow' => 0,
				'status' => 0,
				'text' => ''
			];
		}

		$parsed = [
			'follow' => 1,
			'status' => 1,
			'text' => $linkvalidator['text']
		];
		if ( strpos( $linkvalidator['rel'], 'nofollow' ) !== false ) {
			$parsed['follow'] = 0;
		}

		return $parsed;
	}

	public static function duplicate_check( $linkTo, $linkFrom, $editId = false ) {
		$linkTo = self::get_url_without_https_and_lastslash($linkTo);
		$linkFrom = self::get_url_without_https_and_lastslash($linkFrom);
		$link = implode('||', [$linkTo, $linkFrom]);

		$links = [];
		if ($dbLinks = get_option(SEO_BLM_OPTION_LINKS)) {
			foreach($dbLinks as $key => $dbLink) {
				# don't check the edited entry.
				if ( $editId && (int) $dbLink['id'] === (int) $editId ) {
					continue;
				}
				$linkTo = self::get_url_without_https_and_lastslash($dbLink['linkTo']);
				$linkFrom = self::get_url_without_https_and_lastslash($dbLink['linkFrom']);
				$links[] = implode('||', [$linkTo, $linkFrom]);
			}
		}
		return !empty($links) && in_array($link, $links);
	}

	public static function get_url_without_https_and_lastslash($url) {
		$url = preg_replace('/^(https?:\/\/)/i', '', $url);
		$url = preg_replace('/(\/*)$/i', '', $url);
		$url = strtolower($url);
		$url = html_entity_decode($url);
		return $url;
	}

	public static function get_next_link_id() {
		$link_id = 1;
		if ($links = get_option(SEO_BLM_OPTION_LINKS)) {
			$last_index = end($links);
			$id = (int)$last_index['id'];
			$link_id = $id + 1;
		}
		return $link_id;
	}

	public static function get_link_by_id($id) {
		$return = false;
		if ($links = get_option(SEO_BLM_OPTION_LINKS)) {
			foreach($links as $key => $link) {
				if ((int) $id === (int) $link['id']) {
					$return = $link;
					break;
				}
			}
		}
		return $return;
	}

	public static function return_edit_link_data($item) {
		$object = [
			'linkTo' => $item['linkTo'],
			'linkFrom' => $item['linkFrom'],
			//'notes' => str_replace(["\\'", '\\"'],["'",'"'],$item['notes']),
			'notes' => $item['notes'],
		];
		return $object;
	}

	public static function return_formatted_by_type( $type, $value, $fulldata = [] ) {
		if ($type === 'follow') {
			$value = sprintf('<span class="dashicons dashicons-desktop dashicons-desktop-%s" title="%s"></span>',
					$value === 1 ? 'yes' : 'no',
					esc_attr__('Default user agent', 'seo-backlink-monitor'));
		}
		elseif ($type === 'followMob') {
			$value = sprintf('<span class="dashicons dashicons-smartphone dashicons-smartphone-%s" title="%s"></span>',
					$value === 1 ? 'yes' : ($value === 0 ? 'no' : 'unchecked'),
					esc_attr__('Mobile user agent', 'seo-backlink-monitor'));
		}
		elseif ($type === 'status') {
			if ($value === 0) {
				$icon = 'dashicons-editor-unlink';
				$title = esc_attr__('Link Not Found', 'seo-backlink-monitor');
			} elseif ($value === 1) {
				$icon = 'dashicons-admin-site';
				$title = esc_attr__('Link Found', 'seo-backlink-monitor');
			} elseif ($value === 2) {
				$icon = 'dashicons-admin-site dashicons-warning down';
				$title = esc_attr__('Server Down', 'seo-backlink-monitor');
			} else {
				$icon = 'dashicons-format-status';
				$title = $value;
			}
			$value = sprintf('<span class="dashicons %s" title="%s"></span>', $icon, $title);
		}
		elseif ($type === 'date' || $type === 'dateRefresh') {
			$dateFormat = get_option( 'date_format' );
			if ($settings = get_option(SEO_BLM_OPTION_SETTINGS)) {
				if (isset($settings['dateFormat']) && $settings['dateFormat'] !== '') {
					$dateFormat = $settings['dateFormat'];
				}
			}
			$value = date_i18n( $dateFormat, $value );
		}
		elseif ($type === 'linkTo' || $type === 'linkFrom') {
			static $i = 0;
			$extended = '';
			$host = $type === 'linkFrom' ? 'linkFromHost' : 'linkToHost';
			$fulldataHost = self::get_url_without_https_and_lastslash($fulldata[ $host ]);
			$link = self::get_url_without_https_and_lastslash($value);
			$linkAfterHost = htmlentities( explode($fulldata[ $host ], $value)[1] );
			if ($link !== $fulldataHost) {
				$extended = sprintf('<a href="#" title="%s" class="toggle-extended" data-toggle-single=".extended-%d"><span class="dashicons dashicons-arrow-right"></span></a><div class="extended extended-%d">%s</div>',
								esc_attr($linkAfterHost),
								$fulldata['id'],
								$fulldata['id'],
								$linkAfterHost);
				$i++;
			}
			$value = sprintf('<a href="%s" target="_blank" class="%s" title="%s" rel="noopener noreferrer">%s</a>%s',
						$value,
						$type,
						esc_attr($value),
						$fulldata[ $host ],
						$extended);
		}
		elseif ($type === 'anchorText') {
			$value = strlen($value) === 0 ?
				'<span class="dashicons dashicons-minus"></span>' :
				$value;
		}
		elseif ($type === 'notes') {
			$value = strlen($value) > 1 ?
				sprintf('<a href="#" class="toggle-note" title="%s" data-toggle-single="#note-%d"><span class="dashicons dashicons-format-aside"></span></a><div class="notes" id="note-%d">%s</div>',
					esc_attr__( 'Notes', 'seo-backlink-monitor' ),
					$fulldata['id'],
					$fulldata['id'],
					nl2br(stripcslashes($value))
				) :
				'';
		}
		return $value;
	}

	public static function return_combined_formatted_by_type( $type, $fulldata = [] ) {
		$return = '';
		$column_name = $type;
		if ($type === 'follow') {
			$return =
				self::return_formatted_by_type('follow', $fulldata[ 'follow' ], $fulldata) .
				self::return_formatted_by_type('followMob', $fulldata[ 'followMob' ], $fulldata);
		}
		elseif ($type === 'dateRefresh') {
			$return =
				'<div class="date">' .
					self::return_formatted_by_type($column_name, $fulldata[ $column_name ], $fulldata) .
				'</div>' .
				'<div class="mobile">' .
					self::return_formatted_by_type('linkTo', $fulldata[ 'linkTo' ], $fulldata) . '<br>' .
					self::return_formatted_by_type('linkFrom', $fulldata[ 'linkFrom' ], $fulldata) .
					'<div class="icons">' .
						self::return_formatted_by_type('follow', $fulldata[ 'follow' ], $fulldata) .
						self::return_formatted_by_type('followMob', $fulldata[ 'followMob' ], $fulldata) . ' ' .
						self::return_formatted_by_type('status', $fulldata[ 'status' ], $fulldata) .
					'</div>'.
				'</div>';
		}
		elseif ($type === 'anchorText') {
			$return =
				self::return_formatted_by_type($column_name, $fulldata[ $column_name ], $fulldata) .
				self::return_formatted_by_type('notes', $fulldata[ 'notes' ], $fulldata);
		}
		return $return;
	}
}
