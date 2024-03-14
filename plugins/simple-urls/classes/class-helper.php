<?php
/**
 * Declare class Helper
 *
 * @package Helper
 */

namespace LassoLite\Classes;

use LassoLite\Admin\Constant;

use LassoLite\Classes\Amazon_Api;
use LassoLite\Classes\Cache_Per_Process;
use LassoLite\Classes\Enum;
use LassoLite\Classes\Import;
use LassoLite\Classes\Setting;
use LassoLite\Classes\SURL;

use LassoLite\Models\Model;
use LassoLite\Models\Url_Details;


use Exception;

/**
 * Lasso_Helper
 */
class Helper {

	/**
	 * User agent
	 *
	 * @var string $user_agent
	 */
	public static $user_agent = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:60.0) Gecko/20100101 Firefox/60.0';

	/**
	 * GET PHP POST
	 *
	 * @return array|string
	 */
	public static function POST() { // phpcs:ignore
		$post = wp_unslash( $_POST ); // phpcs:ignore

		return $post;
	}

	/**
	 * GET PHP GET
	 *
	 * @return array|string
	 */
	public static function GET() { // phpcs:ignore
		$get = wp_unslash( $_GET ); // phpcs:ignore

		return $get;
	}

	/**
	 * Include variables
	 *
	 * @param string $file_path File path.
	 * @param array  $variables List of variables.
	 * @param bool   $output_ajax_html Output a ajax html string or not.
	 */
	public static function include_with_variables( $file_path, $variables = array(), $output_ajax_html = true ) {
		$output = null;
		if ( file_exists( $file_path ) ) {
			extract( $variables ); // phpcs:ignore
			if ( $output_ajax_html ) {
				ob_start();
				include $file_path;
				$output = ob_get_clean();
				return $output;
			} else {
				require $file_path;
			}
		}

		return $output;
	}

	/**
	 * Get path to views folder
	 *
	 * @return string
	 */
	public static function get_path_views_folder() {
		return SIMPLE_URLS_DIR . 'admin/views/';
	}

	/**
	 *  Enqueue a Lasso script.
	 *
	 * @param string $handle    Name of the script. Should be unique.
	 * @param string $file_name Lasso script file name.
	 * @param array  $deps      Optional. An array of registered script handles this script depends on. Default empty array.
	 * @param bool   $in_footer Optional. Whether to enqueue the script before </body> instead of in the <head>. Default 'false'.
	 */
	public static function enqueue_script( $handle, $file_name, $deps = array(), $in_footer = false ) {
		$handle    = SIMPLE_URLS_SLUG . '-' . $handle;
		$file_path = SIMPLE_URLS_DIR . '/admin/assets/js/' . $file_name;

		if ( file_exists( $file_path ) ) {
			$src = SIMPLE_URLS_URL . '/admin/assets/js/' . $file_name;
			$ver = strval( @filemtime( $file_path ) ); // phpcs:ignore

			wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
		}
	}

	/**
	 *  Enqueue a Lasso CSS stylesheet.
	 *
	 * @param string $handle              Name of the stylesheet. Should be unique.
	 * @param string $file_name           Lasso stylesheet file name.
	 * @param array  $deps                Optional. An array of registered stylesheet handles this stylesheet depends on. Default empty array.
	 * @param string $media               Optional. The media for which this stylesheet has been defined.
	 *                                    Default 'all'. Accepts media types like 'all', 'print' and 'screen', or media queries like
	 *                                    '(orientation: portrait)' and '(max-width: 640px)'.
	 * @param bool   $apply_handle_prefix Is apply prefix for handle. Default to true.
	 */
	public static function enqueue_style( $handle, $file_name, $deps = array(), $media = 'all', $apply_handle_prefix = true ) {
		$handle    = $apply_handle_prefix ? SIMPLE_URLS_SLUG . '-' . $handle : $handle;
		$file_path = SIMPLE_URLS_DIR . '/admin/assets/css/' . $file_name;

		if ( file_exists( $file_path ) ) {
			$src = SIMPLE_URLS_URL . '/admin/assets/css/' . $file_name;
			$ver = strval( @filemtime( $file_path ) ); // phpcs:ignore

			wp_enqueue_style( $handle, $src, $deps, $ver, $media );
		}
	}

	/**
	 * Get list page
	 *
	 * @return Page[]
	 */
	public static function available_pages() {
		$pages[ Enum::PAGE_DASHBOARD ]     = new Page( 'Dashboard', Enum::PAGE_DASHBOARD, 'dashboard/index.php' );
		$pages[ Enum::PAGE_OPPORTUNITIES ] = new Page( 'Opportunities', Enum::PAGE_OPPORTUNITIES, 'opportunities/index.php' );
		$pages[ Enum::PAGE_IMPORT ]        = new Page( 'Import', Enum::PAGE_IMPORT, 'import/index.php' );
		$pages[ Enum::PAGE_TABLES ]        = new Page( 'Tables', Enum::PAGE_TABLES, 'tables/index.php' );
		$pages[ Enum::PAGE_URL_DETAILS ]   = new Page( 'Link Details', Enum::PAGE_URL_DETAILS, '/dashboard/url-details.php' );

		$pages[ Enum::PAGE_SETTINGS_GENERAL ] = new Page( 'General', Enum::PAGE_SETTINGS_GENERAL, 'settings/general.php' );
		$pages[ Enum::PAGE_SETTINGS_DISPLAY ] = new Page( 'Display', Enum::PAGE_SETTINGS_DISPLAY, 'settings/display.php' );
		$pages[ Enum::PAGE_SETTINGS_AMAZON ]  = new Page( 'Amazon', Enum::PAGE_SETTINGS_AMAZON, 'settings/amazon.php' );
		$pages[ Enum::PAGE_GROUPS ]           = new Page( 'Groups', Enum::PAGE_GROUPS, '/groups/index.php' );
		$pages[ Enum::PAGE_GROUP_DETAIL ]     = new Page( 'Group Detail', Enum::PAGE_GROUP_DETAIL, '/groups/detail.php' );

		if ( get_option( Enum::LASSO_LITE_ACTIVE ) && ! self::get_option( Enum::IS_VISITED_WELCOME_PAGE ) ) {
			$pages[ Enum::PAGE_ONBOARDING ] = new Page( 'Onboarding', Enum::PAGE_ONBOARDING, 'onboarding/index.php' );
		}

		return $pages;
	}

	/**
	 * Convert date time to WordPress format
	 *
	 * @param string $datetime Date time. Format must be 'Y-m-d H:i:s' (example: 2018-09-14 10:34:54).
	 * @param bool   $time     Is it time. Default to true.
	 */
	public static function convert_datetime_format( $datetime, $time = true ) {
		if ( ! $datetime ) {
			return $datetime;
		}

		$date_format     = get_option( 'date_format' );
		$time_format     = 'g:i a T';
		$datetime_format = $date_format . ' ' . $time_format;
		$format          = ( $time ) ? $datetime_format : $date_format;

		try {
			$result = date_create_from_format( 'Y-m-d H:i:s', $datetime );
			$result = $result->format( $format );
		} catch ( \Exception $e ) {
			$result = $datetime;
		}

		return $result;
	}

	/**
	 * Add surl prefix
	 *
	 * @param string $page Page name.
	 * @return string
	 */
	public static function add_prefix_page( $page ) {
		return SIMPLE_URLS_SLUG . '-' . $page;
	}

	/**
	 * Print a wrapper for js render library
	 *
	 * @param string $html_id_selector Html id selector.
	 * @param string $file_path Absolute path file.
	 * @return string|null
	 */
	public static function wrapper_js_render( $html_id_selector, $file_path ) {
		$output  = '<script id="' . $html_id_selector . '" type="text/x-jsrender">';
		$output .= self::include_with_variables( $file_path, array(), true );
		$output .= '</script>';
		return $output;
	}

	/**
	 * Check a url has protocol or not
	 *
	 * @param string $url URL.
	 * @return bool
	 */
	public static function has_protocol( $url ) {
		if ( strpos( $url, 'http' ) === 0 || strpos( $url, 'https' ) === 0 ) {
			return true;
		}
		return false;
	}

	/**
	 * Check if Classic Editor plugin is active.
	 *
	 * @return bool
	 */
	public static function is_classic_editor_plugin_active() {
		return self::get_is_plugin_active( 'classic-editor/classic-editor.php' );
	}

	/**
	 * Check if Disable Gutenberg plugin is active.
	 *
	 * @return bool
	 */
	public static function is_disable_gutenberg_plugin_active() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return self::get_is_plugin_active( 'disable-gutenberg/disable-gutenberg.php' );
	}

	/**
	 * If the Lasso Pro plugin is installed, return true. Otherwise, return false
	 */
	public static function is_lasso_pro_installed() {
		return self::get_is_plugin_active( 'lasso/affiliate-plugin.php' );
	}

	/**
	 * If the Lasso Pro plugin is active, return true. Otherwise, return false
	 */
	public static function is_lasso_pro_plugin_active() {
		return self::is_lasso_pro_installed() && self::get_license_status();
	}

	/**
	 * Get license status in DB
	 */
	public static function get_license_status() {
		$db_status      = get_option( 'lasso_license_status', '' );
		$active_license = boolval( $db_status );

		return $active_license;
	}

	/**
	 * Check whether slug exists or not
	 *
	 * @param string $post_name Post name.
	 * @param int    $post_id   Post id. Default to 0.
	 */
	public static function the_slug_exists( $post_name, $post_id = 0 ) {
		if ( empty( $post_name ) ) {
			return false;
		}

		$posts_tbl = Model::get_wp_table_name( 'posts' );
		$sql       = '
			SELECT 
				ID,
				post_name,
				post_type
			FROM '
				. $posts_tbl . ' 
			WHERE 
				post_name = %s 
				AND ID != %d 
				AND post_status <> "trash"
				AND post_type = %s
			LIMIT 1
		';

		$prepare = Model::prepare( $sql, $post_name, $post_id, Constant::LASSO_POST_TYPE ); // phpcs:ignore
		$row     = Model::get_row( $prepare, 'ARRAY_A' ); // phpcs:ignore

		return $row ? $row : false;
	}

	/**
	 * Add https to the url
	 *
	 * @param string $url URL.
	 */
	public static function add_https( $url ) {
		$invalid_url = array(
			'https://%20https:/',
			'https://xhttps://',
			'http:/https://',
			'http://https://',
			'https://https://',
			'https://hhttps://',
			'https://]https://',
			'https://&quot;https://',
			'[gift_item link=&quot;https://',
			']https://',
		);
		$url         = trim( $url );
		$url         = str_replace( $invalid_url, 'https://', $url );

		// ? fix mailto in <a> href
		if ( strpos( $url, 'mailto:' ) !== false || filter_var( $url, FILTER_VALIDATE_EMAIL ) ) {
			$email = explode( 'mailto:', $url )[1] ?? '';
			if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
				$url = 'mailto:' . $email;
			}

			return $url;
		}

		if ( '' === $url || is_null( $url ) || strpos( $url, '[' ) === 0 ) {
			return $url;
		}

		if ( strpos( $url, 'http://' ) !== 0 && strpos( $url, 'https://' ) !== 0 && strpos( $url, '.' ) !== false && '#' !== $url ) {
			$url = 'https://' . $url;
		}

		return $url;
	}

	/**
	 * Format URL before sending request
	 *
	 * @param string $url    URL.
	 * @param bool   $encode Encode url or not. Default to false.
	 */
	public static function format_url_before_requesting( $url, $encode = false ) {
		$url = trim( $url );
		$url = $encode ? rawurlencode( $url ) : $url;

		return $url;
	}

	/**
	 * Get title by url
	 *
	 * @param string $url URL.
	 */
	public static function get_title_by_url( $url ) {
		$url   = self::add_https( $url );
		$parse = wp_parse_url( $url );
		$host  = $parse['host'] ?? '';
		$host  = str_replace( 'www.', '', $host );
		$host  = explode( '.', $host );
		$host  = $host[ count( $host ) - 2 ] ?? '';
		$host  = str_replace( '-', ' ', $host );
		$host  = ucwords( $host );

		return $host;
	}

	/**
	 * Remove a action out of WordPress hook
	 *
	 * Example 1: Lasso_Helper::remove_action('admin_print_footer_scripts', 'register_tinymce_quicktags'); $callback is a function name.
	 * Example 2: Lasso_Helper::remove_action('admin_print_footer_scripts', array('EarnistProductPicker', 'register_tinymce_quicktags')); $callback is array.
	 *
	 * @param string       $hook_name Hook name.
	 * @param array|string $callback  Callback function. $callback[0] is a class name, $callback[1] is a function name.
	 * @param int          $priority  Priority.
	 */
	public static function remove_action( $hook_name, $callback, $priority = 10 ) {
		global $wp_filter;

		if ( ! isset( $wp_filter[ $hook_name ]->callbacks[ $priority ] ) ) {
			return;
		}

		foreach ( $wp_filter[ $hook_name ]->callbacks[ $priority ] as $key_function_name_wp => $data ) {
			$should_remove    = false;
			$obj_name_wp      = null;
			$function_name_wp = is_array( $data['function'] ) ? $data['function'][1] : $data['function'];
			if ( ! is_array( $callback ) ) {
				$function_name = $callback;
				if ( $function_name_wp === $function_name ) {
					$should_remove = true;
				}
			} else {
				list( $object_name, $function_name ) = $callback;
				if ( gettype( $object_name ) === 'object' ) {
					$object_name = get_class( $object_name );
				}

				if ( ! $data['function'] instanceof \Closure ) {
					$obj_name_wp = $data['function'][0];
					if ( gettype( $obj_name_wp ) === 'object' ) {
						$obj_name_wp = get_class( $obj_name_wp );
						if ( $obj_name_wp === $object_name && $function_name_wp === $function_name ) {
							$should_remove = true;
						}
					}
				}
			}

			if ( $should_remove ) {
				unset( $wp_filter[ $hook_name ]->callbacks[ $priority ][ $key_function_name_wp ] );
				break;
			}
		}
	}

	/**
	 * It includes the HTML for the display modal dialogs
	 *
	 * @return string the html for the modal.
	 */
	public static function get_display_modal_html() {
		$html  = self::include_with_variables( Helper::get_path_views_folder() . 'modals/display-add.php' ); // phpcs:ignore
		$html .= self::include_with_variables( Helper::get_path_views_folder() . 'modals/url-add.php', array( 'is_from_editor' => true, ) ); // phpcs:ignore
		$html .= self::include_with_variables( Helper::get_path_views_folder() . 'modals/url-quick-detail.php' ); // phpcs:ignore
		$html .= self::wrapper_js_render( 'single-list', Helper::get_path_views_folder() . 'modals/single-jsrender.html' ); // phpcs:ignore
		$html .= self::wrapper_js_render( 'url-quick-detail-jsrender', Helper::get_path_views_folder() . 'components/url-quick-detail-jsrender.html' ); // phpcs:ignore

		return $html;
	}

	/**
	 * Get countries of Amazon
	 *
	 * @param string $selected_country Country code.
	 */
	public static function get_countries_dd( $selected_country ) {
		$countries = Amazon_Api::get_amazon_api_countries();

		$countries_dd = '<select id="amazon_default_tracking_country" name="amazon_default_tracking_country" class="form-control">';
		foreach ( $countries as $key => $country ) {
			if ( strlen( $key ) !== 2 ) {
				continue;
			}

			$selected = '';
			if ( $selected_country === $key ) {
				$selected = 'selected';
			}
			$countries_dd .= '<option value="' . $key . '" ' . $selected . ' >' . $country['name'] . '</option>';
		}
		$countries_dd .= '</select>';

		return $countries_dd;
	}

	/**
	 * Get setup progress information
	 *
	 * @return array
	 */
	public static function get_setup_progress_information() {
		$enable_support        = boolval( Setting::get_setting( Enum::SUPPORT_ENABLED ) ) ? 20 : 0;
		$total_links           = SURL::total();
		$links                 = $total_links > 20 ? 20 : $total_links;
		$links_percent         = 20 === $links ? 100 : ( $links / 20 ) * 100;
		$setup_amz_tracking_id = boolval( get_option( Enum::SETUP_AMZ_TRACKING_ID ) ) ? 15 : 0;
		$follow_on_twitter     = boolval( get_option( Enum::FOLLOW_ON_TWITTER ) ) ? 10 : 0;
		$share_on_twitter      = boolval( get_option( Enum::SHARE_ON_TWITTER ) ) ? 10 : 0;
		$leave_a_review        = boolval( get_option( Enum::LEAVE_A_REVIEW ) ) ? 5 : 0;
		$is_show_review_note   = ! $leave_a_review && $total_links >= 20 && $enable_support && $setup_amz_tracking_id && $follow_on_twitter && $share_on_twitter ? 1 : 0;
		$progress              = $enable_support + $setup_amz_tracking_id + $follow_on_twitter + $share_on_twitter + ( $links * 2 ) + $leave_a_review;
		$progress              = $progress ? $progress / 100 : 0;
		$open_modal_add_link   = $links < 20 ? 'btn-add-20-links' : '';

		$data = array(
			'progress'              => round( $progress, 2 ),
			'progress_percent'      => round( $progress * 100 ),
			'links'                 => $links,
			'links_percent'         => $links_percent,
			'setup_amz_tracking_id' => $setup_amz_tracking_id,
			'follow_on_twitter'     => $follow_on_twitter,
			'share_on_twitter'      => $share_on_twitter,
			'leave_a_review'        => $leave_a_review,
			'is_show_review_note'   => $is_show_review_note,
			'enable_support'        => $enable_support,
			'setting_amz_url'       => Page::get_lite_page_url( Enum::PAGE_SETTINGS_AMAZON ),
			'follow_twitter_url'    => home_url() . '?' . Enum::SLUG_CLOAK_FOLLOW_TWITTER,
			'share_twitter_url'     => home_url() . '?' . Enum::SLUG_CLOAK_SHARE_TWITTER,
			'review_url'            => home_url() . '?' . Enum::SLUG_CLOAK_LASSO_REVIEW_URL,
			'open_modal_add_link'   => $open_modal_add_link,
		);

		return $data;
	}

	/**
	 * Send request
	 *
	 * @param string $method Method (get or post). Default to get.
	 * @param string $url    URL. Default to empty.
	 * @param array  $data   Post data. Default to empty array.
	 * @param array  $headers Headers. Default to empty array.
	 * @param bool   $is_lasso_save Is Lasso save data action. Default to false.
	 */
	public static function send_request( $method = 'get', $url = '', $data = array(), $headers = array(), $is_lasso_save = false ) {
		$method          = strtolower( $method );
		$request_options = array(
			'headers'   => $headers,
			'timeout'   => Constant::TIME_OUT,
			'sslverify' => Constant::SSL_VERIFY,
		);
		$body            = wp_json_encode( $data );
		$headers_expect  = ! empty( $body ) && strlen( $body ) > 1048576 ? '100-Continue' : '';
		if ( 'get' === $method ) {
			$res = wp_remote_get( $url, $request_options );
		} elseif ( 'post' === $method ) {
			$request_options['headers']['expect'] = $headers_expect;
			$request_options['body']              = $body;
			$res                                  = wp_remote_post( $url, $request_options );
		} elseif ( 'put' === $method ) {
			$request_options['headers']['expect'] = $headers_expect;
			$request_options['body']              = $body;
			$request_options['method']            = 'PUT';
			$res                                  = wp_remote_request( $url, $request_options );
		}

		if ( is_wp_error( $res ) ) {
			return array(
				'status_code' => 500,
				'response'    => array(),
			);
		}

		$body   = wp_remote_retrieve_body( $res );
		$status = wp_remote_retrieve_response_code( $res );

		return array(
			'status_code' => $status,
			'response'    => json_decode( $body ),
		);
	}

	/**
	 * Get Lasso Lite - WP option
	 *
	 * @param string $option_name Option name.
	 * @param mixed  $default     Default value.
	 * @return mixed|void
	 */
	public static function get_option( $option_name, $default = false ) {
		return get_option( SIMPLE_URLS_SLUG . '_' . $option_name, $default );
	}

	/**
	 * Update Lasso Lite - WP option
	 *
	 * @param string $option_name  Option name.
	 * @param mixed  $option_value Option value.
	 * @param bool   $autoload     Autoload.
	 * @return bool
	 */
	public static function update_option( $option_name, $option_value, $autoload = null ) {
		return update_option( SIMPLE_URLS_SLUG . '_' . $option_name, $option_value, $autoload );
	}

	/**
	 * Validate URL
	 *
	 * @param string $url URL.
	 */
	public static function validate_url( $url ) {
		if ( ! is_string( $url ) ) {
			return false;
		}

		$url = str_replace( ' ', '%20', $url );
		$url = preg_replace( '/[^\00-\255]+/u', '', $url );

		return ( ( strpos( $url, 'http://' ) === 0 || strpos( $url, 'https://' ) === 0 ) &&
			filter_var( $url, FILTER_VALIDATE_URL ) !== false );
	}

	/**
	 * Get argument from url
	 *
	 * @param string $link     Amazon link.
	 * @param string $argument URL argument.
	 * @return string
	 */
	public static function get_argument_from_url( $link, $argument ) {
		if ( ! $argument ) {
			return '';
		}

		$link    = str_replace( '&amp;', '&', $link );
		$parse   = wp_parse_url( $link );
		$queries = array();
		parse_str( $parse['query'] ?? '', $queries );

		return $queries[ $argument ] ?? '';
	}

	/**
	 * Build url parameter string
	 * Parameter example: array( 'rel_=abc', 'maas=def' );
	 *
	 * @param array $parameters Parameter key=value array.
	 * @return string|mixed
	 */
	public static function build_url_parameter_string( $parameters = array() ) {
		$result = implode( '&', $parameters );
		$result = preg_replace( '!\&+!', '&', $result );
		$result = trim( $result, '&' );

		return $result;
	}

	/**
	 * Convert query array to a string (in a url)
	 *
	 * @param array $query Query.
	 */
	public static function get_query_from_array( $query ) {
		$result = array();
		foreach ( $query as $key => $value ) {
			$result[] = $key . '=' . rawurlencode( $value );
		}
		$result = implode( '&', $result );
		return $result;
	}

	/**
	 * Convert parse_url() to a url
	 *
	 * @param array $parse Parse data from a URL.
	 * @param bool  $host Is it host. Default to false.
	 */
	public static function get_url_from_parse( $parse, $host = false ) {
		$parse['host']  = $parse['host'] ?? '';
		$host           = false !== $host ? $host : $parse['host'];
		$parse['host']  = '://' . $host;
		$parse['query'] = isset( $parse['query'] ) ? '?' . $parse['query'] : '';

		return implode( '', $parse );
	}

	/**
	 * Get param of $_SERVER
	 *
	 * @param string $name Name of param.
	 */
	public static function get_server_param( $name ) {
		return wp_unslash( $_SERVER[ $name ] ?? '' ); // phpcs:ignore
	}

	/**
	 * Check if Lasso URL description is empty.
	 *
	 * @param string $description Lasso URL description.
	 * @return bool
	 */
	public static function is_description_empty( $description ) {
		if ( empty( $description ) || '<p><br></p>' === $description ) {
			return true;
		}

		return false;
	}

	/**
	 * Get base domain
	 * Ex: "http://domain.com" would return "domain.com"
	 *
	 * @param string $domain Domain. It must be passed WITH protocol. Default to empty.
	 */
	public static function get_base_domain( $domain = '' ) {
		$domain = self::add_https( $domain );
		if ( ! self::validate_url( $domain ) ) {
			return '';
		}

		$url  = @wp_parse_url( $domain ); // phpcs:ignore
		$host = $url['host'] ?? '';
		$host = str_replace( 'www.', '', $host );
		$host = trim( $host );

		return $host ? $host : '';
	}

	/**
	 * Convert stdclass object to array
	 *
	 * @param bool $obj StdClass object.
	 */
	public static function convert_stdclass_to_array( $obj ) {
		$array = json_decode( wp_json_encode( $obj ), true );

		return $array;
	}

	/**
	 * Check if mysql error relative to Lasso's table does not exist
	 *
	 * @param string $mysql_error mysql error.
	 * @return boolean
	 */
	public static function is_lasso_tables_does_not_exist_error( $mysql_error ) {
		return (bool) preg_match( "/(\.)(.)*lasso_(.)*doesn\'t(\s)exist/", $mysql_error );
	}

	/**
	 * If the AAWP plugin is active, return true. Otherwise, return false
	 */
	public static function is_aawp_active() {
		return is_plugin_active( 'aawp/aawp.php' );
	}

	/**
	 * If the Amalinks Pro plugin is active, return true. Otherwise, return false
	 */
	public static function is_amalinks_pro_active() {
		return is_plugin_active( 'amalinkspro/amalinkspro.php' );
	}

	/**
	 * Format importable data before showing/importing/reverting
	 *
	 * @param object $p Importable post.
	 */
	public static function format_importable_data( $p ) {
		$lasso_db         = new Lasso_DB();
		$lasso_helper     = new Helper();
		$lasso_amazon_api = new Amazon_Api();

		$home_url            = home_url();
		$p->import_permalink = get_permalink( $p->id );

		if ( 'Pretty Links' === $p->import_source ) {
			$pretty_link_data = $lasso_db->get_pretty_link_by_id( $p->id );
			$defaul_permalink = $home_url . '/' . $pretty_link_data->slug . '/';

			$prlipro             = get_option( 'prlipro_options', array() );
			$prlipro             = is_array( $prlipro ) ? $prlipro : array();
			$base_slug_prefix    = $prlipro['base_slug_prefix'] ?? '';
			$p->import_permalink = '' !== $base_slug_prefix && strpos( $p->post_name, $base_slug_prefix ) === false
				? $home_url . '/' . $base_slug_prefix . '/' . $p->post_name . '/'
				: $defaul_permalink;
		} elseif ( 'AAWP' === $p->import_source ) {
			$aawp_row            = $lasso_db->get_aawp_product( $p->id );
			$p->import_permalink = $aawp_row->url ?? '';
			$shortcode           = '[amazon link="' . $p->post_name . '"]';
			$p->shortcode        = $shortcode;

			// ? AAWP list
			if ( 'aawp_list' === $p->post_type ) {
				$p->import_permalink = 'https://amazon.com/s?k=' . $p->post_title;
				$p->check_status     = self::check_aawp_list_is_imported( $p->id ) ? 'checked' : '';

				$cat             = term_exists( $p->post_title, Constant::LASSO_CATEGORY );
				$p->check_status = $cat ? 'checked' : $p->check_status;

				$aawp_list = $lasso_db->get_aawp_list( $p->id );
				if ( $aawp_list ) {
					$items_count  = $aawp_list->items_count ?? 0;
					$attr_type    = 'bestseller' === $aawp_list->type ? 'bestseller' : 'link';
					$attr_type    = 'new_releases' === $aawp_list->type ? 'new' : $attr_type;
					$shortcode    = '[amazon ' . $attr_type . '="' . $aawp_list->keywords . '" items="' . $items_count . '"]';
					$p->shortcode = $shortcode;
				}
			}
		} elseif ( 'EasyAzon' === $p->import_source ) {
			$product             = Lasso_DB::get_easyazon_option( $p->post_title );
			$p->post_title       = $product['title'];
			$p->id               = $product['identifier'];
			$p->import_permalink = $product['url'];
			$p->post_name        = strtolower( $product['identifier'] );
			$shortcode           = '[easyazon_link identifier="' . $p->id . '"]' . $p->post_title . '[/easyazon_link]';
			$p->shortcode        = $shortcode;

			$revert = $lasso_db->is_easyazon_product_imported( $product['identifier'] );
			if ( $revert ) {
				$p->id = $revert->lasso_id;
			}
		} elseif ( 'AmaLinks Pro' === $p->import_source ) {
			$shortcode           = $p->post_name;
			$attributes          = $lasso_helper->get_attributes( $shortcode );
			$p->shortcode        = $shortcode;
			$p->import_permalink = $attributes['apilink'] ?? '';
			if ( empty( $p->id ) ) {
				$p->id = $attributes['asin'] ?? $p->id;

				$url_details     = $lasso_db->get_url_details_by_product_id( $p->id, Amazon_Api::PRODUCT_TYPE );
				$lasso_id        = $url_details->lasso_id ?? 0;
				$p->check_status = $lasso_id > 0 ? 'checked' : '';

			}
			if ( empty( $p->import_permalink ) ) {
				$p->import_permalink = $lasso_amazon_api->get_amazon_link_by_product_id( $p->id );
			}
		} elseif ( 'Lasso Pro' === $p->import_source ) {
			$target_url          = Import::get_lasso_pro_target_url( $p->id );
			$p->import_permalink = $target_url;
			$p->shortcode        = '[lasso rel="' . $p->post_name . '" id="' . $p->id . '"]';
		}

		return $p;
	}

	/**
	 * Get attributes of shortcode
	 *
	 * @param string $tags_data Shortcode string.
	 */
	public static function get_attributes( $tags_data ) {
		// ? fix shortcode contains content: [shortcode]content[/shortcode]
		preg_match_all(
			'/' . get_shortcode_regex() . '/',
			$tags_data,
			$matches,
			PREG_SET_ORDER
		);
		$content_between = $matches[0][5] ?? '';
		$shortcode_name  = $matches[0][2] ?? '';
		if ( ! empty( $shortcode_name ) ) {
			// ? remove content between content/anchor text
			$tags_data = str_replace( ']' . $content_between . '[', '][', $tags_data );
			// ? remove the end shortcode
			$tags_data = str_replace( '[/' . $shortcode_name . ']', '', $tags_data );
		}
		$tags_data = str_replace( '/]', ']', $tags_data );

		$attributes = array();
		$parse      = shortcode_parse_atts( esc_html( $tags_data ) );

		$temp_key = 'temp';
		foreach ( $parse as $key => $value ) {
			if ( ! is_integer( $key ) ) {
				$temp_key           = $key;
				$attributes[ $key ] = self::remove_special_character_in_attributes( $value );
			} else {
				// ? join data with old key.
				$attributes[ $temp_key ] = trim( ( $attributes[ $temp_key ] ?? '' ) . ' ' . self::remove_special_character_in_attributes( $value ) );
			}
		}
		unset( $attributes['temp'] );

		return $attributes;
	}

	/**
	 * Remove special character in attributes
	 *
	 * @param string $text Text string.
	 */
	public static function remove_special_character_in_attributes( $text ) {
		$text = str_replace( 'u0026', '&', $text );
		$text = str_replace( array( '\\', 'u0022', '&quot;', 'u003c', 'u003e', '&lt;', '&gt;' ), '', $text );
		return htmlspecialchars_decode( trim( $text, ']' ), ENT_QUOTES );
	}

	/**
	 * Check whether aawp list id is imported to Lasso or not
	 *
	 * @param int $id Aawp list id.
	 */
	public static function check_aawp_list_is_imported( $id ) {
		$lasso_db = new Lasso_DB();

		$sql        = '
			SELECT lud.product_id 
			FROM ' . ( new Url_Details() )->get_table_name() . ' AS lud
				LEFT JOIN ' . $lasso_db->posts . ' AS p
				ON lud.lasso_id = p.ID
			WHERE lud.product_id != \'\' 
				AND p.ID is not null 
				AND lud.product_type = \'' . Amazon_Api::PRODUCT_TYPE . '\'
		';
		$row        = $lasso_db->get_col( $sql );
		$amazon_ids = $row ? $row : array();

		$aawp_list       = $lasso_db->get_aawp_list( $id );
		$aawp_amazon_ids = $aawp_list->product_asins ?? '';
		$aawp_amazon_ids = '' !== $aawp_amazon_ids ? explode( ',', $aawp_amazon_ids ) : array();

		$same_elements = array_intersect( $aawp_amazon_ids, $amazon_ids );

		return $aawp_amazon_ids === $same_elements;
	}

	/**
	 * Paginate items by a sql query
	 * Reset page number if results is empty.
	 *
	 * @param string $sql   Sql query.
	 * @param int    $page  Number of page.
	 * @param int    $limit Number of results. Default to 10.
	 */
	public static function paginate( $sql, &$page, $limit = 10 ) {
		$start_index    = ( $page - 1 ) * $limit;
		$pagination_sql = $sql . ' LIMIT ' . $start_index . ', ' . $limit;

		if ( $page > 1 ) {
			$result = Model::get_row( $pagination_sql );

			if ( ! $result ) {
				$page           = 1;
				$pagination_sql = $sql . ' LIMIT 0, ' . $limit;
			}
		}

		return $pagination_sql;
	}

	/**
	 * Format "post title" to escape html and apply limit length.
	 *
	 * @param Title $title lasso urls title.
	 * @param int   $limit_length limit length of title.
	 */
	public static function format_post_title( $title, $limit_length = 200 ) {
		$title = esc_html( $title );

		if ( strlen( $title ) > $limit_length ) {
			$title = substr( $title, 0, $limit_length ) . '...';
		}

		return $title;
	}

	/**
	 * Get unique post name of lasso post
	 *
	 * @param int    $post_id   Post id.
	 * @param string $post_name Post name.
	 */
	public static function lasso_unique_post_name( $post_id, $post_name ) {
		if ( intval( $post_id ) > 0 && ! empty( $post_name ) && self::the_slug_exists( $post_name, $post_id ) ) {
			$post_name = rtrim( $post_name, '-link' ); // ? Fix the issue adding multiple "-link" string to the end.
			$post_name = wp_unique_post_slug( $post_name, $post_id, 'publish', Constant::LASSO_POST_TYPE, 0 );
		}

		return $post_name;
	}

	/**
	 * Get plugin status result
	 *
	 * @param string $plugin Plugin key.
	 * @return bool
	 */
	public static function get_is_plugin_active( $plugin ) {
		$cache_result = Cache_Per_Process::get_instance()->get_cache( 'is_plugin_active_' . md5( $plugin ), null );
		if ( null !== $cache_result ) {
			return $cache_result;
		}

		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$result = \is_plugin_active( $plugin );
		Cache_Per_Process::get_instance()->set_cache( 'is_plugin_active_' . md5( $plugin ), $result );

		return $result;
	}

	/**
	 *
	 * Check plugin earnist is loaded https://www.getearnist.com.
	 *
	 * @return bool
	 */
	public static function is_earnist_plugin_loaded() {
		return self::get_is_plugin_active( 'earnist/earnist.php' );
	}

	/**
	 *
	 * Check plugin Shortcode Star Rating is loaded https://github.com/modshrink/shortcode-star-rating.
	 *
	 * @return bool
	 */
	public static function is_shortcode_start_rating_plugin_loaded() {
		return self::get_is_plugin_active( 'shortcode-star-rating/shortcode-star-rating.php' );
	}

	/**
	 * Check plugin "Easy Table of Contents" is activated
	 *
	 * @return bool
	 */
	public static function is_plugin_easy_table_of_contents_activated() {
		return self::get_is_plugin_active( 'easy-table-of-contents/easy-table-of-contents.php' );
	}

	/**
	 * Check if Gravity Perks plugin is active.
	 *
	 * @return bool
	 */
	public static function is_gravity_perks_plugin_active() {
		return self::get_is_plugin_active( 'gravityperks/gravityperks.php' );
	}

	/**
	 * Check if Ezoic plugin is active.
	 *
	 * @return bool
	 */
	public static function is_ezoic_plugin_active() {
		return self::get_is_plugin_active( 'ezoic-integration/ezoic-integration.php' );
	}

	/**
	 * Check if WP Rocket - Lazyload is enabled.
	 *
	 * @return bool
	 */
	public static function is_wp_rocket_lazyload_image_enabled() {
		if ( self::get_is_plugin_active( 'wp-rocket/wp-rocket.php' ) ) {
			// ? Check if layzyload enabled
			$wp_rocket_settings = get_option( 'wp_rocket_settings', array() );
			if ( $wp_rocket_settings['lazyload'] ?? false ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check whether current page is WP post page
	 */
	public static function is_wordpress_post() {
		global $pagenow;

		$get          = self::GET(); // phpcs:ignore
		$action       = $get['action'] ?? '';
		$add_new_page = 'post-new.php' === $pagenow;
		$edit_page    = 'post.php' === $pagenow && 'edit' === $action;
		$post_type    = $get['post_type'] ?? '';

		if ( ( 'edit.php' === $pagenow || $add_new_page ) && '' === $post_type ) {
			$post_type = 'post';
		} elseif ( $add_new_page ) {
			$post_type = $get['post_type'] ?? $post_type;
		} elseif ( $edit_page ) {
			$post_id   = intval( $get['post'] ?? 0 );
			$post_type = $post_id > 0 ? get_post_type( $post_id ) : $post_type;
		}

		if ( 'term.php' === $pagenow ) {
			$post_type = '';
		}

		return 'post' === $post_type || 'page' === $post_type;
	}

	/**
	 * Cast the value to boolean
	 *
	 * @param bool|string $value A string boolean like "true" or "false".
	 *
	 * @return bool
	 */
	public static function cast_to_boolean( $value ) {
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Get CPU load of server/hosting
	 */
	public static function get_cpu_load() {
		$load = null;

		if ( stristr( PHP_OS, 'win' ) ) {
			$cmd = 'wmic cpu get loadpercentage /all';
			@exec( $cmd, $output ); // phpcs:ignore

			if ( $output ) {
				foreach ( $output as $line ) {
					if ( $line && preg_match( '/^[0-9]+$/', $line ) ) {
						$load = $line;
						break;
					}
				}
			}
		} else {
			try {
				if ( @is_readable( '/proc/stat' ) ) { // phpcs:ignore
					$cached_cpu_load = Cache_Per_Process::get_instance()->get_cache( 'cpu_load', null );
					if ( $cached_cpu_load ) {
						$stat_data1 = $cached_cpu_load;
					} else {
						$stat_data1 = self::get_server_load_linux_data();
					}

					// ? Collect 2 samples - each with 1 second period
					// ? See: https://de.wikipedia.org/wiki/Load#Der_Load_Average_auf_Unix-Systemen
					sleep( 1 );

					$stat_data2 = self::get_server_load_linux_data();
					Cache_Per_Process::get_instance()->set_cache( 'cpu_load', $stat_data2 );

					if ( ( ! is_null( $stat_data1 ) ) && ( ! is_null( $stat_data2 ) ) ) {
						// ? Get difference
						$stat_data2[0] -= $stat_data1[0];
						$stat_data2[1] -= $stat_data1[1];
						$stat_data2[2] -= $stat_data1[2];
						$stat_data2[3] -= $stat_data1[3];

						// ? Sum up the 4 values for User, Nice, System and Idle and calculate
						// ? the percentage of idle time (which is part of the 4 values!)
						$cpu_time = $stat_data2[0] + $stat_data2[1] + $stat_data2[2] + $stat_data2[3];

						// ? Invert percentage to get CPU time, not idle time
						$load = 100 - ( $stat_data2[3] * 100 / max( $cpu_time, 1 ) );
					}
				}
			} catch ( Exception $e ) {
				$load = 0; // Just run because we can't detect CPU load.
			}
		}

		return round( $load, 2 );
	}

	/**
	 * Get server load linux data
	 */
	private static function get_server_load_linux_data() {
		if ( @is_readable( '/proc/stat' ) ) { // phpcs:ignore
			$stats = @file_get_contents( '/proc/stat' ); // phpcs:ignore

			if ( false !== $stats ) {
				// ? Remove double spaces to make it easier to extract values with explode()
				$stats = preg_replace( '/[[:blank:]]+/', ' ', $stats );

				// ? Separate lines
				$stats = str_replace( array( "\r\n", "\n\r", "\r" ), "\n", $stats );
				$stats = explode( "\n", $stats );

				// ? Separate values and find line for main CPU load
				foreach ( $stats as $stat_line ) {
					$stat_line_data = explode( ' ', trim( $stat_line ) );

					// ? Found
					if ( count( $stat_line_data ) >= 5 && 'cpu' === $stat_line_data[0] ) {
						return array(
							$stat_line_data[1],
							$stat_line_data[2],
							$stat_line_data[3],
							$stat_line_data[4],
						);
					}
				}
			}
		}

		return null;
	}

	/**
	 * Remove unexpected character from post title
	 *
	 * @param string $post_title Post title.
	 * @return string
	 */
	public static function remove_unexpected_characters_from_post_title( $post_title ) {
		// ? Remove unexpected character.
		$post_title = preg_replace( "/[^A-Za-z0-9\s`~!@#$%^&:;\/\?\"\'\+\=\.\,\-\_\*\(\)\|\[\]\<\>\{\}\\\]/", ' ', $post_title );
		// ? Remove duplicated space.
		$post_title = preg_replace( '/\s\s+/', ' ', $post_title );

		return $post_title;
	}

	/**
	 * Check whether this install is new
	 */
	public static function is_new_install() {
		return boolval( get_option( Enum::LASSO_LITE_ACTIVE ) );
	}

	/**
	 * Build image lazyload attributes
	 *
	 * @return string
	 */
	public static function build_img_lazyload_attributes() {
		$result = 'loading="lazy"'; // ? WP default lazyload.

		if ( self::is_ezoic_plugin_active() ) {
			$result = 'class="ezlazyload"';
		} elseif ( self::is_wp_rocket_lazyload_image_enabled() ) {
			$result = 'class="rocket-lazyload"';
		}

		return $result;
	}

	/**
	 * Check whether import page should display
	 *
	 * @return bool
	 */
	public static function should_show_import_page() {
		return ! empty( ( new Lasso_DB() )->get_import_plugins( true ) ) ? true : false;
	}

	/**
	 * Get brag icon
	 *
	 * @param bool $force_to_show Force to show the brag. Default to false.
	 */
	public static function get_brag_icon( $force_to_show = false ) {
		$cache_key  = 'lasso_lite_brag_icon';
		$brag_cache = Cache_Per_Process::get_instance()->get_cache( $cache_key, '' );

		if ( $brag_cache ) {
			return $brag_cache;
		}

		$lasso_settings = Setting::get_settings();

		$enable_brag_mode = $lasso_settings['enable_brag_mode'] ?? false;
		$lasso_url        = $lasso_settings['lasso_affiliate_URL'] ?? false;

		if ( $lasso_url && ( $force_to_show || $enable_brag_mode ) ) {
			$icon_brag           = esc_url( SIMPLE_URLS_URL . '/admin/assets/images/lasso-icon-brag.svg' );
			$lasso_affiliate_url = self::add_params_to_url( $lasso_url, array( 'utm_source' => 'brag' ) );
			$img_attr            = self::build_img_lazyload_attributes();
			$icon                = '
				<a class="lasso-brag" href="' . esc_url( $lasso_affiliate_url ) . '" target="_blank" rel="nofollow noindex">
					<img src="' . esc_url( $icon_brag ) . '" ' . $img_attr . ' alt="Lasso Brag" width="30" height="30">
				</a>
			';

			Cache_Per_Process::get_instance()->set_cache( $cache_key, $icon );

			return $icon;
		}

		return '';
	}

	/**
	 * Add params to URL
	 *
	 * @param string $url    URL.
	 * @param array  $params Params.
	 */
	public static function add_params_to_url( $url, $params ) {
		// ? parse url
		$parse = wp_parse_url( $url );
		parse_str( $parse['query'] ?? '', $query );

		$query          = array_merge( $query, $params );
		$query          = self::get_query_from_array( $query );
		$parse['query'] = $query;

		return self::get_url_from_parse( $parse );

	}

	/**
	 * Get final url in the url
	 * Example: https://affiliate.com/redirect?url=https://getlasso.co
	 *
	 * @param string $url URL.
	 */
	public static function get_final_url_from_url_param( $url ) {
		if ( ! self::validate_url( $url ) ) {
			return false;
		}

		$final_url   = false;
		$base_domain = self::get_base_domain( $url );

		if ( self::is_shareasale_url( $url ) ) {
			$final_url = self::get_argument_from_url( $url, 'urllink' );
		} elseif ( 'pntra.com' === $base_domain ) {
			$final_url = self::get_argument_from_url( $url, 'url' );
		} elseif ( 'wordseed.com' === $base_domain ) {
			$final_url = self::get_argument_from_url( $url, 'url' );
		} elseif ( 'titan.fitness' === $base_domain ) {
			$final_url = 'https://www.titan.fitness' . self::get_argument_from_url( $url, 'redirect' );
		} else { // ? other urls
			$parse   = wp_parse_url( $url );
			$queries = array();
			parse_str( $parse['query'] ?? '', $queries );
			foreach ( $queries as $key => $param ) {
				if ( 'referrer' === $key ) {
					continue;
				}

				$param = str_replace( ' ', '%20', $param );
				if ( self::validate_url( $param ) ) {
					$final_url = $param;
					break;
				}
			}
		}
		$final_url = self::add_https( $final_url );

		if ( empty( $final_url ) || ! self::validate_url( $final_url ) ) {
			$final_url = false;
		} else {
			$final_url = trim( $final_url, '/' );
		}

		return $final_url;
	}

	/**
	 * Check whether url is shareasale domain or not
	 *
	 * @param string $url URL.
	 * @return bool
	 */
	public static function is_shareasale_url( $url ) {
		$allow_domains = array( 'shareasale.com', 'shareasale-analytics.com' );
		$domain        = self::get_base_domain( $url );

		return in_array( $domain, $allow_domains, true );
	}

	/**
	 * Check importable
	 *
	 * @return bool
	 */
	public static function is_importable() {
		$lasso_lite_db = new Lasso_DB();
		$sql           = $lasso_lite_db->get_importable_urls_query( true );
		$post          = Model::get_row( $sql );

		if ( ! empty( $post ) ) {
			$post->post_title = self::format_post_title( $post->post_title ?? '' );
			$post->shortcode  = '';

			// ? Get import target permalinks
			$post = self::format_importable_data( $post );

			// ? Check first record from list import
			return 'checked' !== $post->check_status;
		}

		return false;
	}

	/**
	 * Check whether Lite is using new or old UI
	 *
	 * @return bool true: new UI. false: old UI.
	 */
	public static function is_lite_using_new_ui() {
		$new_ui = get_option( Enum::SWITCH_TO_NEW_UI );
		$new_ui = self::cast_to_boolean( $new_ui );

		if ( ! $new_ui ) {
			return false;
		}

		return true;
	}

	/**
	 * Whether show Request Review at the top of the page
	 */
	public static function show_request_review() {
		$link_count = SURL::total();

		$lasso_review_allow      = self::cast_to_boolean( self::get_option( Constant::LASSO_OPTION_REVIEW_ALLOW, '1' ) );
		$lasso_review_snooze     = self::cast_to_boolean( self::get_option( Constant::LASSO_OPTION_REVIEW_SNOOZE, '0' ) );
		$lasso_review_link_count = intval( self::get_option( Constant::LASSO_OPTION_REVIEW_LINK_COUNT, $link_count ) );

		$show            = ! $lasso_review_snooze && $link_count >= 20;
		$snooze_but_show = $lasso_review_snooze && $link_count - $lasso_review_link_count >= 20;

		if ( ! $lasso_review_allow ) {
			return false;
		}

		if ( $show || $snooze_but_show ) {
			return true;
		}

		return false;
	}

	/**
	 * Get Ajax URL
	 */
	public static function get_ajax_url() {
		return admin_url( 'admin-ajax.php' );
	}

	/**
	 * Get price value from price text including currency symbol.
	 *
	 * @param string $price_text   Price text.
	 * @param string $price_symbol Price symbol.
	 * @return mixed|string
	 */
	public static function get_price_value_from_price_text( $price_text, $price_symbol = '' ) {
		if ( preg_match( '/[€]|R\$|TL|kr|zł/', $price_text ) || in_array( $price_symbol, array( '€', 'R$', 'TL', 'kr', 'zł' ), true ) ) {
			// ? For price use , as decimal separator and . as thousands separator.
			$replace_character = '.';
			$reg_pattern       = '/\d+,?\d*/';
		} else {
			// ? For price use . as decimal separator and , as thousands separator.
			$replace_character = ',';
			$reg_pattern       = '/\d+\.?\d*/';
		}

		$price_without_thousands_separator = str_replace( $replace_character, '', $price_text );
		preg_match( $reg_pattern, $price_without_thousands_separator, $matches );

		// ? Final format to general float number by replace ',' to '.'.
		return isset( $matches[0] ) ? str_replace( ',', '.', $matches[0] ) : '';
	}

	/**
	 * Get currency symbol from ISO currency code
	 *
	 * @param string $iso Iso currency code.
	 * @return string
	 */
	public static function get_currency_symbol_from_iso_code( $iso ) {
		$iso    = strtoupper( $iso );
		$result = '$';

		$currencies = array(
			'USD' => '$',
			'AUD' => '$',
			'CAD' => '$',
			'EUR' => '€',
			'MXN' => '$',
			'CNY' => '¥',
			'JPY' => '¥',
			'INR' => '₹',
			'SEK' => 'kr',
			'BRL' => 'R$',
			'TRY' => 'TL',
			'GBP' => '£',
			'PLN' => 'zł',
			'EGP' => 'E£',
			'SGD' => 'S$',
			'AED' => 'AED',
		);

		return isset( $currencies[ $iso ] ) ? $currencies[ $iso ] : $result;
	}

	/**
	 * Check using WP classic editor
	 *
	 * @return bool
	 */
	public static function is_classic_editor() {
		return self::is_classic_editor_plugin_active() || self::is_disable_gutenberg_plugin_active();
	}

	/**
	 * Escape string prevent SQL injection
	 *
	 * @param string $keyword    Keyword.
	 * @param bool   $search_from_after Option to check search string from end of string.
	 * @return string
	 */
	public static function esc_like_query( $keyword, $search_from_after = false ) {
		global $wpdb;

		$wild = '%';
		if ( $search_from_after ) {
			$query = $wpdb->esc_like( $keyword ) . $wild;
		} else {
			$query = $wild . $wpdb->esc_like( $keyword ) . $wild;
		}

		$query = str_replace( ' ', '%', $query );
		return $query;
	}

	/**
	 * Remove all the script code from the HTML.
	 * Remove script tags and event attributes (e.g., onload, onsubmit, etc.)
	 *
	 * @param string $html HTML code.
	 */
	public static function sanitize_script( $html ) {
		if ( ! $html ) {
			return $html;
		}

		// ? Remove <script> tags and their variations
		$html = preg_replace( '/<script\b[^>]*>.*?<\/script\s*>/is', '', $html );

		// ? Remove event attributes (e.g., onload, onsubmit, etc.) and their values
		$html = preg_replace( '/\s+on\w+\s*=\s*["\'][^"\']*["\']/', ' ', $html );

		return $html;
	}

	/**
	 * Verify access and nonce, then return wp_send_json_error if unverified.
	 *
	 * @param bool $allow_edit_post_access Allow access for editor, author, contributor.
	 * @return void
	 */
	public static function verify_access_and_nonce( $allow_edit_post_access = false ) {
		try {
			// ? Verify access token.
			if ( ! current_user_can( 'manage_options' ) ) {
				if ( $allow_edit_post_access ) {
					// ? Allow access for editor, author, contributor.
					// ? WP User Roles: https://wordpress.com/support/invite-people/user-roles/#:~:text=Editor%3A%20Has%20access%20to%20all,posts%20until%20they%20are%20published.
					$current_user_role = self::get_current_user_role();
					if ( ! in_array( $current_user_role, array( 'editor', 'author', 'contributor' ), true ) ) {
						wp_send_json_error( 'Access denied.' );
					}
				} else {
					wp_send_json_error( 'Access denied.' );
				}
			}

			// ? Verify nonce.
			$data   = array();
			$server = wp_unslash( $_SERVER );
			$method = $server['REQUEST_METHOD'] ?? '';
			if ( 'POST' === $method ) {
				$data = self::POST();
			} elseif ( 'GET' === $method ) {
				$data = self::GET();
			}

			$nonce = $data['nonce'] ?? '';
			if ( false === wp_verify_nonce( $nonce, Constant::LASSO_LITE_NONCE . wp_salt() ) ) {
				wp_send_json_error( 'Nonce not verified.' );
			}
		} catch ( \Exception $e ) {
			wp_send_json_error( 'Verify access and nonce error.' );
		}
	}

	/**
	 * Get current user role
	 *
	 * @return string
	 */
	public static function get_current_user_role() {
		if ( is_user_logged_in() ) {
			$user  = wp_get_current_user();
			$roles = (array) $user->roles;

			return $roles[0];
		} else {
			return 'guest';
		}
	}
}
