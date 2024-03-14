<?php
namespace Upress\Booter;

class Utilities {
	/**
	 * @var string[]
	 */
	protected static $bad_robots;
	/**
	 * @var string[]
	 */
	protected static $bad_referers;

	/**
	 * Explode a string by new lines
	 *
	 * @param $string
	 *
	 * @return string[]
	 */
	public static function explode_new_lines( $string ) {
		return preg_split( "/\\r\\n|\\r|\\n/u", trim( $string ), - 1, PREG_SPLIT_NO_EMPTY );
	}

	/**
	 * Get the user's IP address
	 * @return array
	 */
	public static function get_client_ip() {
		$client_ip = ! empty( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '';

		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$client_ip = $_SERVER['HTTP_CLIENT_IP'];
		}

		if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}

		if ( ! empty( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) {
			$client_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
		}

		return self::explode_new_lines( $client_ip );
	}

	/**
	 * Check if current user is logged in via cookie
	 * @return bool
	 */
	public static function is_user_logged_in() {
		// if the WordPress function exists refer to it
		if ( function_exists( 'is_user_logged_in') ) {
			return is_user_logged_in();
		}

		$siteurl = get_site_option( 'siteurl' );
		if ( $siteurl ) {
			$COOKIEHASH = md5( $siteurl );
		} else {
			$COOKIEHASH = '';
		}

		if ( empty( $_COOKIE[ 'wordpress_logged_in_' . $COOKIEHASH ] ) ) {
			return false;
		}

		$cookie = $_COOKIE[ 'wordpress_logged_in_' . $COOKIEHASH ];

		$cookie_elements = explode( '|', $cookie );
		if ( count( $cookie_elements ) !== 4 ) {
			return false;
		}

		list( $username, $expiration ) = $cookie_elements;

		if ( $expiration < time() ) {
			return false;
		}

		return true;
	}

	/**
	 * Get the user's fingerprint by useragent + ip
	 * @return string
	 */
	public static function generate_user_fingerprint_string() {
		$ips = self::get_client_ip();
		sort( $ips );
		$ips = implode( ';', $ips );

		return "{$ips};{$_SERVER['HTTP_USER_AGENT']}";
	}

	/**
	 * Get array of known bot useragent strings
	 * @return string[]
	 */
	public static function get_known_bots() {
		return apply_filters( 'booter_known_bots',
			array_merge(
				self::get_bad_robots(),
				[
					'Googlebot',
					'Google',
					'YandexBot',
					'Yandex',
					'bingbot',
					'BLEXBot',
					'BlackWidow',
					'Nutch',
					'Jetbot',
					'WebVac',
					'Stanford',
					'scooter',
					'naver',
					'dumbot',
					'Hatena Antenna',
					'grub',
					'looksmart',
					'WebZip',
					'larbin',
					'b2w/0.1',
					'Copernic',
					'psbot',
					'Python-urllib',
					'NetMechanic',
					'URL_Spider_Pro',
					'CherryPicker',
					'EmailCollector',
					'EmailSiphon',
					'WebBandit',
					'EmailWolf',
					'ExtractorPro',
					'CopyRightCheck',
					'Crescent',
					'SiteSnagger',
					'ProWebWalker',
					'LNSpiderguy',
					'Alexibot',
					'Teleport',
					'MIIxpc',
					'Telesoft',
					'Website Quester',
					'moget',
					'WebStripper',
					'WebSauger',
					'WebCopier',
					'NetAnts',
					'Mister PiX',
					'WebAuto',
					'TheNomad',
					'WWW-Collector-E',
					'libWeb/clsHTTP',
					'asterias',
					'httplib',
					'turingos',
					'spanner',
					'Harvest',
					'InfoNaviRobot',
					'Bullseye',
					'WebBandit',
					'NICErsPRO',
					'Microsoft URL Control',
					'DittoSpyder',
					'Foobot',
					'WebmasterWorldForumBot',
					'SpankBot',
					'BotALot',
					'lwp-trivial',
					'WebmasterWorld',
					'BunnySlippers',
					'URLy Warning',
					'LinkWalker',
					'cosmos',
					'hloader',
					'humanlinks',
					'LinkextractorPro',
					'Offline Explorer',
					'Mata Hari',
					'LexiBot',
					'Collector',
					'The Intraformant',
					'True_Robot',
					'BlowFish',
					'SearchEngineWorld',
					'JennyBot',
					'MIIxpc',
					'BuiltBotTough',
					'ProPowerBot',
					'BackDoorBot',
					'toCrawl/UrlDispatcher',
					'WebEnhancer',
					'suzuran',
					'WebViewer',
					'VCI',
					'Szukacz',
					'QueryN',
					'Openfind',
					'Openbot',
					'Webster',
					'EroCrawler',
					'LinkScan',
					'Keyword',
					'Kenjin',
					'Iron33',
					'Bookmark search tool',
					'GetRight',
					'FairAd Client',
					'Gaisbot',
					'Aqua_Products',
					'Radiation Retriever 1.1',
					'Flaming AttackBot',
					'Oracle Ultra Search',
					'MSIECrawler',
					'PerMan',
					'searchpreview',
					'sootle',
					'Enterprise_Search',
					'ChinaClaw',
					'Custo',
					'DISCo',
					'Download Demon',
					'eCatch',
					'EirGrabber',
					'EmailSiphon',
					'EmailWolf',
					'Express WebPictures',
					'ExtractorPro',
					'EyeNetIE',
					'FlashGet',
					'GetRight',
					'GetWeb!',
					'Go!Zilla',
					'Go-Ahead-Got-It',
					'GrabNet',
					'Grafula',
					'HMView',
					'HTTrack',
					'Image Stripper',
					'Image Sucker',
					'Indy Library',
					'InterGET',
					'Internet Ninja',
					'JetCar',
					'JOC',
					'Spider',
					'larbin',
					'LeechFTP',
					'Mass Downloader',
					'MIDown tool',
					'Mister PiX',
					'Navroad',
					'NearSite',
					'NetAnts',
					'NetSpider',
					'Net Vampire',
					'NetZIP',
					'Octopus',
					'Offline Explorer',
					'Offline Navigator',
					'PageGrabber',
					'Papa Foto',
					'pavuk',
					'pcBrowser',
					'RealDownload',
					'ReGet',
					'SiteSnagger',
					'SmartDownload',
					'SuperBot',
					'SuperHTTP',
					'Surfbot',
					'tAkeOut',
					'Teleport Pro',
					'VoidEYE',
					'Collector',
					'Sucker',
					'WebAuto',
					'WebCopier',
					'WebFetch',
					'WebGo IS',
					'WebLeacher',
					'WebReaper',
					'WebSauger',
					'Website eXtractor',
					'Website Quester',
					'WebStripper',
					'WebWhacker',
					'WebZIP',
					'Widow',
					'WWWOFFLE',
					'Xaldon WebSpider',
					'Zeus',
					'Semrush',
					'BecomeBot',
					'Screaming Frog SEO Spider',
					'GrapeshotCrawler',
					'trendkite-akashic-crawler',
					'GetIntent Crawler',
					'special_archiver',
					'SirdataBot',
					'bidswitchbot',
					'proximic',
					'NetSeer',
					'crawler',
					'rogerbot',
					'exabot',
					'Xenu',
					'gigabot',
					'BlekkoBot',
					'AhrefsBot',
					'omgili',
					'Slurp',
					'ia_archiver',
					'agent1',
					'Cheesebot',
					'Catall Spider',
					'MJ12bot',
					'seo-audit-check-bot',
					'webceo',
					'dotbot',
					'WP Rocket',
				]
			)
		);
	}

	/**
	 * Get array of bad bot useragent strings
	 * @return string[]
	 */
	public static function get_bad_robots() {
		if ( ! self::$bad_robots ) {
			$additional_robots = self::get_list_from_url( 'upress-additional-blocked-bots', 'https://bitbucket.org/upress-team/lists/raw/717e042067c61aa291b4bc45635b8019a97edbb0/bad-user-agents-extra.list' );
			$excluded_robots = self::get_list_from_url( 'upress-excluded-bots', 'https://bitbucket.org/upress-team/lists/raw/717e042067c61aa291b4bc45635b8019a97edbb0/bad-user-agents-whitelist.list' );

			$bad_robots = self::get_list_from_url(
				'booter_bad_robots',
				'https://raw.githubusercontent.com/mitchellkrogza/nginx-ultimate-bad-bot-blocker/master/_generator_lists/bad-user-agents.list',
				$additional_robots,
				$excluded_robots
			);

			self::$bad_robots = apply_filters( 'booter_bad_bots', $bad_robots );
		}

		return self::$bad_robots;
	}

	/**
	 * Get array of bad referer domains
	 * @return string[]
	 */
	public static function get_bad_referers() {
		if ( ! self::$bad_referers ) {
			self::$bad_referers = self::get_list_from_url( 'booter_bad_referers', 'https://raw.githubusercontent.com/mitchellkrogza/nginx-ultimate-bad-bot-blocker/master/_generator_lists/bad-referrers.list' );
		}

		return self::$bad_referers;
	}

	/**
	 * Get an array of items from a list at a specified URL
	 *
	 * @param string $identifier
	 * @param string $url
	 * @param array $additional_items
	 * @param array $excluded_items
	 *
	 * @return array
	 */
	protected static function get_list_from_url( $identifier, $url, $additional_items = [], $excluded_items = [] ) {
		$list = get_transient( $identifier );

		if ( ! $list ) {
			$response = wp_safe_remote_get( $url );
			if ( is_wp_error( $response ) ) {
				error_log( $response->get_error_message() );
				$list = [];
			} else {
				$list = wp_remote_retrieve_body( $response );
				$list = stripslashes( $list );
				$list = Utilities::explode_new_lines( $list );
				set_transient( $identifier, $list );
				set_transient( "{$identifier}_updated_at", time() );
			}
		}

		$list = array_merge( $list, $additional_items );
		$list = array_diff( $list, $excluded_items );

		return apply_filters( $identifier, array_values($list) );
	}

	/**
	 * Get an item from an array using "dot" notation.
	 *
	 * @param array $array
	 * @param string $key
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public static function array_get( $array, $key, $default = null ) {
		if ( is_null( $key ) ) {
			return $array;
		}

		if ( isset( $array[ $key ] ) ) {
			return $array[ $key ];
		}

		foreach ( explode( '.', $key ) as $segment ) {
			if ( ! is_array( $array ) || ! array_key_exists( $segment, $array ) ) {
				return $default;
			}

			$array = $array[ $segment ];
		}

		return $array;
	}

	public static function bool_value( $value ) {
		return '1' === $value || 'yes' === $value;
	}

	public static function sanitize_int( $value ) {
		return (string) intval( $value );
	}

	public static function sanitize_bool( $value ) {
		return (!! $value) ? 'yes' : 'no';
	}

	/**
	 * Check if the current request is running through the CLI or WP-CLI
	 * @return bool
	 */
	public static function is_running_in_cli() {
		return ! isset( $_SERVER['REQUEST_METHOD'] ) || ( defined( 'WP_CLI' ) && WP_CLI );
	}

	/**
	 * Check if the request is coming from the servers IP
	 * @return bool
	 */
	public static function is_request_coming_from_server_ip() {
		if ( ! empty( $_SERVER['SERVER_ADDR'] ) )
			$server_ip = $_SERVER['SERVER_ADDR'];
		elseif ( ! empty( $_SERVER['LOCAL_ADDR'] ) )
			$server_ip = $_SERVER['LOCAL_ADDR'];
		else
			return false;

		$ips = self::get_client_ip();

		return in_array( $server_ip, $ips );
	}

}
