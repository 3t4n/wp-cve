<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Plugin Updates
 *
 * @package			XML for Google Merchant Center
 * @subpackage		
 * @since			3.0.0
 * 
 * @version			1.0.1 (06-03-2023)
 * @author			Maxim Glazunov
 * @link			https://icopydoc.ru/
 * @see				[ 202, 402, 412, 418, 520 ]
 * 
 * @param	array	$args
 *
 * @return			
 *
 * @depends			classes:	
 *					traits:	
 *					methods:	
 *					functions:	common_option_get
 *					constants:	XFGMC_PLUGIN_VERSION
 *					options:	
 *
 */

final class XFGMC_Plugin_Upd {
	const API_URL = 'https://icopydoc.ru/api/v1';
	private $list_plugin_names = [ 
		'xfgmcp' => [ 'name' => 'PRO', 'code' => 'renewlicense20gp' ]
	];
	private $pref; // префикс плагина
	private $slug; // псевдоним плагина (например: oop-wp)
	private $plugin_slug; // полный псевдоним плагина (папка плагина + имя главного файла, например: oop-wp/oop-wp.php)
	private $premium_version; // номер версии плагина
	private $license_key;
	private $order_id;
	private $order_email;
	private $order_home_url; // номер базовой версии плагина

	public function __construct( $args = [] ) {
		$this->pref = $args['pref'];
		$this->slug = $args['slug'];
		$this->plugin_slug = $args['plugin_slug'];
		$this->premium_version = $args['premium_version'];
		if ( isset( $args['license_key'] ) ) {
			$this->license_key = $args['license_key'];
		} else {
			$license_key = $args['pref'] . '_license_key';
			$this->license_key = common_option_get( $license_key );
		}
		if ( isset( $args['order_id'] ) ) {
			$this->order_id = $args['order_id'];
		} else {
			$order_id = $args['pref'] . '_order_id';
			$this->order_id = common_option_get( $order_id );
		}
		if ( isset( $args['order_email'] ) ) {
			$this->order_email = $args['order_email'];
		} else {
			$order_email = $args['pref'] . '_order_email';
			$this->order_email = common_option_get( $order_email );
		}
		if ( isset( $args['order_home_url'] ) ) {
			$this->order_home_url = $args['order_home_url'];
		} else {
			$this->order_home_url = home_url( '/' );
		}
		$this->list_plugin_names = apply_filters( 'xfgmc_f_list_plugin_names', $this->list_plugin_names, $args );
		$this->init_hooks(); // подключим хуки
	}

	/**
	 * @uses add_filter()
	 *
	 * @return void
	 */
	private function init_hooks() {
		// проверка наличия обновлений:
		add_filter( 'pre_set_site_transient_update_plugins', [ $this, 'check_update' ], 10 );
		// проверка информации о плагине:
		add_filter( 'plugins_api', [ $this, 'plugin_api_check_info' ], 10, 3 );
		// установка плагина:
		add_filter( 'upgrader_package_options', [ $this, 'set_update_package' ] );
		add_filter( 'plugin_action_links', [ $this, 'add_plugin_action_links' ], 10, 2 );
		// add_action('admin_notices', [ $this, 'print_admin_notices' ], 10, 1);
		$this->get_info();
	}

	public function add_plugin_action_links( $actions, $plugin_file ) {
		if ( false === strpos( $plugin_file, $this->get_plugin_slug() ) ) { // проверка, что у нас текущий плагин
			return $actions;
		} else {
			$u = 'ok';
			$i = common_option_get( 'woo_ho' . $u . '_isc' . $this->get_pref() );
		}
		switch ( $i ) {
			case "202":
				$message = __( 'License is active', 'xml-for-google-merchant-center' );
				$color = 'green';
				break;
			case "402":
				$message = __( 'License expired', 'xml-for-google-merchant-center' );
				$color = '#dc3232';
				break;
			case "412":
				$message = __( 'License data is invalid', 'xml-for-google-merchant-center' );
				$color = '#dc3232';
				break;
			case "418":
				$message = __( 'This license cannot be used on this site. The package limit has been exceeded', 'xml-for-google-merchant-center' );
				$color = '#dc3232';
				break;
			default: // или ошибка 520
				$message = __( 'License data is invalid', 'xml-for-google-merchant-center' );
				$color = '#dc3232';
				break;
		}
		$settings_link = sprintf( '<span style="color: %s; font-weight: 700;">%s</span>',
			$color,
			$message
		);
		array_unshift( $actions, $settings_link );
		return $actions;
	}

	public function get_info() {
		$v = 'hook';
		$c = common_option_get( 'woo_' . $v . '_is' . 'c' . $this->get_pref() );
		$d = common_option_get( 'woo_' . $v . '_is' . 'd' . $this->get_pref() );

		$message = '';
		switch ( $c ) {
			case "202":
				break;
			case "402":
				$message = sprintf(
					'<span style="font-weight: 700;">YML for Google Merchant Center %1$s:</span> %2$s! %3$s, <a href="https://icopydoc.ru/product/%4$s/?utm_source=%4$s&utm_medium=organic&utm_campaign=in-plugin&utm_content=notice&utm_term=license-expired" target="_blank">%5$s</a> (%6$s: <span style="font-weight: 700;">%7$s</span>). %8$s <a href="/wp-admin/admin.php?page=xfgmcexport">%9$s</a>.',
					$this->list_plugin_names[ $this->get_pref()]['name'],
					__( 'License expired', 'xml-for-google-merchant-center' ),
					__( 'Please', 'xml-for-google-merchant-center' ),
					$this->get_slug(),
					__( 'purchase a new license', 'xml-for-google-merchant-center' ),
					__( 'to get a discount, use this promo code', 'xml-for-google-merchant-center' ),
					$this->list_plugin_names[ $this->get_pref()]['code'],
					__( 'If you have already done this', 'xml-for-google-merchant-center' ),
					__( 'enter the new license information here', 'xml-for-google-merchant-center' )
				);
				break;
			case "412":
				$message = sprintf( '<span style="font-weight: 700;">YML for Google Merchant Center %1$s:</span> %2$s! %1$s %3$s. <a href="/wp-admin/admin.php?page=xfgmcexport">%4$s</a> %5$s <a href="https://icopydoc.ru/product/%6$s/?utm_source=%6$s&utm_medium=organic&utm_campaign=in-plugin&utm_content=license-err&utm_term=notice" target="_blank">%7$s</a>.',
					$this->list_plugin_names[ $this->get_pref()]['name'],
					__( 'License data is invalid', 'xml-for-google-merchant-center' ),
					__( 'version features do not work and you can not install updates', 'xml-for-google-merchant-center' ),
					__( 'Enter your license information', 'xml-for-google-merchant-center' ),
					__( 'or', 'xml-for-google-merchant-center' ),
					$this->get_slug(),
					__( 'purchase a new one', 'xml-for-google-merchant-center' )
				);
				break;
			case "418":
				$message = sprintf( '<span style="font-weight: 700;">YML for Google Merchant Center %1$s:</span> %2$s! <a href="/wp-admin/admin.php?page=xfgmcexport">%3$s</a> %4$s <a href="https://icopydoc.ru/product/%5$s/?utm_source=%5$s&utm_medium=organic&utm_campaign=in-plugin&utm_content=license-limit&utm_term=notice" target="_blank">%6$s</a>.',
					$this->list_plugin_names[ $this->get_pref()]['name'],
					__( 'This license cannot be used on this site. The package limit has been exceeded', 'xml-for-google-merchant-center' ),
					__( 'Enter your license information', 'xml-for-google-merchant-center' ),
					__( 'or', 'xml-for-google-merchant-center' ),
					$this->get_slug(),
					__( 'purchase a new one', 'xml-for-google-merchant-center' )
				);
				break;
			default: // или ошибка 520
				$message = sprintf( '<span style="font-weight: 700;">YML for Google Merchant Center %1$s:</span> %2$s! %1$s %3$s. <a href="/wp-admin/admin.php?page=xfgmcexport">%4$s</a> %5$s <a href="https://icopydoc.ru/product/%6$s/?utm_source=%6$s&utm_medium=organic&utm_campaign=in-plugin&utm_content=license-err&utm_term=notice" target="_blank">%7$s</a>.',
					$this->list_plugin_names[ $this->get_pref()]['name'],
					__( 'License data is invalid', 'xml-for-google-merchant-center' ),
					__( 'version features do not work and you can not install updates', 'xml-for-google-merchant-center' ),
					__( 'Enter your license information', 'xml-for-google-merchant-center' ),
					__( 'or', 'xml-for-google-merchant-center' ),
					$this->get_slug(),
					__( 'purchase a new one', 'xml-for-google-merchant-center' )
				);
				break;
		}

		if ( ! empty( $message ) ) {
			$class = 'notice-error';
			add_action( 'admin_notices', function () use ($message, $class) {
				$this->admin_notices_func( $message, $class );
			}, 10, 2 );
		}

		if ( $c !== '0' ) {
			$remaining_seconds = $c - current_time( 'timestamp' );
			$remaining_days = ceil( ( $remaining_seconds / ( 24 * 60 * 60 ) ) );
			if ( $remaining_days > 0 && $remaining_days < 8 ) {
				$message = sprintf( '<span style="font-weight: 700;">XML for Google Merchant Center %1$s:</span> %2$s	<span style="font-weight: 700; color: red;">%3$s</span>. %4$s, <a href="https://icopydoc.ru/product/%5$s/?utm_source=link&utm_medium=organic&utm_campaign=in-plugin&utm_content=notice&utm_term=license-remaining" target="_blank">%6$s</a> (%7$s: <span style="font-weight: 700;">%8$s</span>). %9$s <a href="/wp-admin/admin.php?page=xfgmcexport">%10$s</a>.',
					$this->list_plugin_names[ $this->get_pref()]['name'],
					__( 'License expires in', 'xml-for-google-merchant-center' ),
					$this->num_decline( $remaining_days, [ __( 'day', 'xml-for-google-merchant-center' ), _x( 'days', '2 days', 'xml-for-google-merchant-center' ), _x( 'days', '5 days', 'xml-for-google-merchant-center' ) ] ),
					__( 'Please', 'xml-for-google-merchant-center' ),
					$this->get_slug(),
					__( 'purchase a new license', 'xml-for-google-merchant-center' ),
					__( 'to get a discount, use this promo code', 'xml-for-google-merchant-center' ),
					$this->list_plugin_names[ $this->get_pref()]['code'],
					__( 'If you have already done this', 'xml-for-google-merchant-center' ),
					__( 'enter the new license information here', 'xml-for-google-merchant-center' )
				);
				if ( ! empty( $message ) ) {
					$class = 'notice-error';
					add_action( 'admin_notices', function () use ($message, $class) {
						$this->admin_notices_func( $message, $class );
					}, 10, 2 );
				}
			}
		}
	}

	/**
	 * Склонение слова после числа.
	 *
	 * Примеры вызова:
	 * xfgmcp_num_decline($num, 'книга,книги,книг')
	 * xfgmcp_num_decline($num, ['книга','книги','книг'])
	 * xfgmcp_num_decline($num, 'книга', 'книги', 'книг')
	 * xfgmcp_num_decline($num, 'книга', 'книг')
	 *
	 * @param  int|string 		$number  Число после которого будет слово. Можно указать число в HTML тегах.
	 * @param  string|array		$titles  Варианты склонения или первое слово для кратного 1.
	 * @param  string			$param2  Второе слово, если не указано в параметре $titles.
	 * @param  string			$param3  Третье слово, если не указано в параметре $titles.
	 *
	 * @return string			1 книга, 2 книги, 10 книг.
	 *
	 */
	private function num_decline( $number, $titles, $param2 = '', $param3 = '' ) {
		if ( $param2 ) {
			$titles = [ $titles, $param2, $param3 ];
		}
		if ( is_string( $titles ) ) {
			$titles = preg_split( '/, */', $titles );
		}
		if ( empty( $titles[2] ) ) {
			$titles[2] = $titles[1]; // когда указано 2 элемента
		}
		$cases = [ 2, 0, 1, 1, 1, 2 ];
		$intnum = abs( intval( strip_tags( $number ) ) );
		return "$number " . $titles[ ( $intnum % 100 > 4 && $intnum % 100 < 20 ) ? 2 : $cases[ min( $intnum % 10, 5 ) ] ];
	}

	private function get_body_request() {
		$body_request = [ 
			'action' => 'basic_check',
			'slug' => $this->get_slug(),
			'plugin_slug' => $this->get_plugin_slug(),
			'premium_version' => $this->get_premium_version(),
			'basic_version' => XFGMC_PLUGIN_VERSION,
			'license_key' => $this->get_license_key(),
			'order_id' => $this->get_order_id(),
			'order_email' => $this->get_order_email(),
			'order_home_url' => home_url( '/' ),
		];
		new XFGMC_Error_Log( $body_request );
		return $body_request;
	}

	private function get_pref() {
		return $this->pref;
	}

	private function get_slug() {
		return $this->slug;
	}

	private function get_plugin_slug() {
		return $this->plugin_slug;
	}

	private function get_premium_version() {
		return $this->premium_version;
	}

	private function get_license_key() {
		return $this->license_key;
	}

	private function get_order_id() {
		return $this->order_id;
	}

	private function get_order_email() {
		return $this->order_email;
	}

	private function response_to_api() {
		global $wp_version;
		$response = false;
		$request_arr = [ 
			'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo( 'url' ),
			'body' => [ 'request' => $this->get_body_request() ] // request будет передан как $_POST['request']
		];
		$api_url = apply_filters( 'xfgmc_f_api_url', self::API_URL );
		$response = wp_remote_post( esc_url_raw( $api_url ), $request_arr );
		return $response;
	}

	private function save_resp( $v, $d ) {
		$v = (int) $v;
		if ( is_multisite() ) {
			update_blog_option( get_current_blog_id(), 'woo_hook_isc' . $this->get_pref(), $v );
			update_blog_option( get_current_blog_id(), 'woo_hook_isd' . $this->get_pref(), $d );
		} else {
			update_option( 'woo_hook_isc' . $this->get_pref(), $v );
			update_option( 'woo_hook_isd' . $this->get_pref(), $d );
		}
	}

	//	проверка наличия обновлений
	public function check_update( $transient ) {
		/**
		 * Сначала проверяется наличие в массиве данных наличие поля "checked". Если оно есть, это значит, 
		 * что WordPress запросил и обработал данные об обновлении и сейчас самое время вставить в параметр 
		 * свои данные. Если нет, значит 12 часов ещё не прошло. Ничего не делаем.
		 * 
		 * ["no_update"]=> array(1) { 
		 *	["best-woocommerce-feed/rex-product-feed.php"]=> 
		 *		object(stdClass)#7367 (9) { 
		 *			["id"]=> string(35) "w.org/plugins/best-woocommerce-feed" 
		 *			["slug"]=> string(21) "best-woocommerce-feed" 
		 *			["plugin"]=> string(42) "best-woocommerce-feed/rex-product-feed.php" 
		 *			["new_version"]=> string(3) "3.4" 
		 *			["url"]=> string(52) "https://wordpress.org/plugins/best-woocommerce-feed/" 
		 *			["package"]=> string(68) "https://downloads.wordpress.org/plugin/best-woocommerce-feed.3.4.zip" 
		 *			["icons"]=> array(1) { 
		 *				["1x"]=> string(74) "https://ps.w.org/best-woocommerce-feed/assets/icon-128x128.jpg?rev=1737647" 
		 *			} 
		 *			["banners"]=> array(1) { 
		 *				["1x"]=> string(76) "https://ps.w.org/best-woocommerce-feed/assets/banner-772x250.png?rev=1944151"
		 *			} 
		 *			["banners_rtl"]=> array(0) { } 
		 *		} 
		 * }
		 */

		/* На время тестов строку ниже нужно раскомментировать */
		wp_clean_update_cache();

		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		$response = $this->response_to_api();
		$response_code = wp_remote_retrieve_response_code( $response );
		$response_message = wp_remote_retrieve_response_message( $response );

		if ( ( $response_code == 200 ) && $response_message == 'OK' ) {
			$resp = json_decode( $response['body'] );
			$this->save_resp( $resp->status_code, $resp->status_date );

			// Обновлений нет. Нет смысла что-то менять. Выходим.
			if ( ! isset( $resp->upd ) ) {
				return $transient;
			}
			$plugin = $this->get_plugin_response_data( $resp );

			$transient->response[ $this->plugin_slug ] = $plugin;
		} else {
			new XFGMC_Error_Log( 'Ошибка проверки наличия обновлений. Код ошибки: ' . $response_code . '; response_message: ' . $response_message . '; Файл: class-xfgmc-plugin-upd.php; Строка: ' . __LINE__ );
		}
		return $transient;
	}

	/**
	 * 
	 * Проверка информации о плагине (запрос информации об обновлениях) 
	 */
	public function plugin_api_check_info( $result, $action, $args ) {
		if ( isset( $args->slug ) && ( $args->slug === $this->slug ) ) {
			$response = $this->response_to_api();
			$response_code = wp_remote_retrieve_response_code( $response );
			$response_message = wp_remote_retrieve_response_message( $response );

			if ( ( $response_code == 200 ) && $response_message == 'OK' ) {
				$resp = json_decode( $response['body'] );
				$this->save_resp( $resp->status_code, $resp->status_date );
				if ( ! isset( $resp->upd ) ) {
					return $result;
				}
				$plugin = $this->get_plugin_response_data( $resp );
				return $plugin;
			} else {
				new XFGMC_Error_Log( 'Ошибка запроса инфы о плагине. Код ошибки: ' . $response_code . '; response_message: ' . $response_message . '; Файл: class-xfgmc-plugin-upd.php; Строка: ' . __LINE__ );
				return $result;
			}
		} else { // это просмотр инфы другого плагина
			return $result;
		}
	}

	/* обновление плагина */
	public function set_update_package( $options ) {
		/**
		 * $options = Array (
		 *	[package] => name // сюда нужна ссылка до архива
		 *	[destination] => /home/p12345/www/site.ru/wp-content/plugins
		 *	[clear_destination] => 1
		 *	[abort_if_destination_exists] => 1
		 *	[clear_working] => 1
		 *	[is_multi] => 1
		 *	[hook_extra] => Array (
		 * 		[plugin] => pgo-plugin-demo-one/pgo-plugin-demo-one.php
		 * 	) 
		 * )
		 */
		if ( isset( $options['hook_extra']['plugin'] ) ) {
			if ( $options['hook_extra']['plugin'] === $this->plugin_slug ) {
				$api_url = apply_filters( 'xfgmc_f_api_url', self::API_URL );
				$package_url = sprintf(
					'%1$s/update/?order_id=%2$s&order_email=%3$s&order_home_url=%4$s&slug=%5$s&premium_version=%6$s&basic_version=%7$s',
					$api_url,
					$this->get_order_id(),
					$this->get_order_email(),
					home_url( '/' ),
					$this->get_slug(),
					$this->get_premium_version(),
					XFGMC_PLUGIN_VERSION
				);
				$package_url = apply_filters( 'xfgmc_f_package_url', $package_url, $options );
				$options['package'] = $package_url;
			}
		}
		return $options;
	}

	private function get_plugin_response_data( $resp ) {
		$plugin = new stdClass();
		$plugin->slug = $resp->slug;
		$plugin->plugin = $this->plugin_slug;
		$plugin->new_version = $resp->version;
		$plugin->url = ''; // страница на WordPress.org
		$plugin->package = $resp->package;
		$plugin->icons = json_decode( json_encode( $resp->icons ), true ); // массив иконки
		$plugin->banners = json_decode( json_encode( $resp->banners ), true ); // массив баннер
		$plugin->name = $resp->name; // название плагина
		$plugin->version = $resp->version; // версия
		$plugin->author = $resp->author; // имя автора
		$plugin->last_updated = $resp->last_updated; // Обновление:
		$plugin->added = $resp->last_updated;
		$plugin->requires = $resp->requires; // Требуемая версия WordPress
		$plugin->tested = $resp->tested; // совместим вполь до
		$plugin->homepage = $resp->homepage; // страница плагина
		$plugin->donate_link = $resp->donate_link; // сделать пожертвование
		$plugin->active_installs = (int) $resp->active_installs; // активные установик
		$plugin->rating = (int) $resp->rating; // рейтинг в звёздах
		$plugin->num_ratings = (int) $resp->num_ratings; // число голосов
		$plugin->sections = json_decode( json_encode( $resp->sections ), true ); // массив иконки
		$plugin->download_link = $resp->package; // 'https://icopydoc.ru/api/v1/pgo-plugin.zip';
		return $plugin;
	}

	private function admin_notices_func( $message, $class ) {
		printf( '<div class="notice %1$s"><p>%2$s</p></div>', $class, $message );
		return;
	}
}