<?php

/**
 * Author: wbolt team
 * Author URI: https://www.wbolt.com
 */

class OCW_Admin
{
	public static $name = 'wb_ocw';
	public static $option_name = 'ocw_option';
	public static $db_ver = 2;
	public static $sms_result = null;
	public static $cnf_fields = array(
		'contact_items' => array(
			'backtop' => array(
				'id' => 'backtop',
				'title' => '返回顶部',
				'name' => '返回顶部',
				'multiple' => false,
				'fields' => array(
					'url' => false,
					'img' => false,
					'label' => false
				)
			),
			'qq' => array(
				'id' => 'qq',
				'title' => 'QQ',
				'name' => 'QQ客服',
				'placeholder' => 'QQ号',
				'multiple' => true,
				'fields' => array(
					'url' => true,
					'img' => false,
					'label' => true
				)
			),
			'wx' => array(
				'id' => 'wx',
				'title' => '微信',
				'name' => '关注我们',
				'placeholder' => '微信号',
				'multiple' => true,
				'fields' => array(
					'img' => true,
					'url' => true,
					'nickname' => true,
					'label' => true,
				)
			),
			'tel' => array(
				'id' => 'tel',
				'title' => '电话',
				'name' => '电话联系',
				'placeholder' => '电话或手机号',
				'multiple' => true,
				'fields' => array(
					'url' => true,
					'img' => false,
					'label' => true
				)
			),
			'email' => array(
				'id' => 'email',
				'title' => '邮箱',
				'name' => '电子邮件',
				'placeholder' => '邮箱地址',
				'multiple' => true,
				'fields' => array(
					'url' => true,
					'img' => false,
					'label' => true
				)
			),
			'msg' => array(
				'id' => 'msg',
				'title' => '留言',
				'name' => '在线留言',
				'multiple' => false,
				'form_fields' => array(
					'name' => '昵称',
					'content' => '内容',
				),
				'form_contact_way' => array(
					'email' => '邮箱',
					'qq' => 'QQ',
					'wx' => '微信',
					'mobile' => '手机',
				),
				'notify_type' => array('邮箱通知', '短信通知'),
			),
			'order' => array(
				'id' => 'order',
				'title' => '订单',
				'name' => '我的订单',
				'multiple' => false,
				'fields' => array(
					'url' => true,
				),
			),
		),
		'position' => array(
			'lt' => array(
				'code' => 'lt',
				'label' => '左上'
			),
			'lc' => array(
				'code' => 'lc',
				'label' => '左中'
			),
			'lb' => array(
				'code' => 'lb',
				'label' => '左下'
			),
			'rt' => array(
				'code' => 'rt',
				'label' => '右上'
			),
			'rc' => array(
				'code' => 'lc',
				'label' => '右中'
			),
			'rb' => array(
				'code' => 'lb',
				'label' => '右下'
			),
		),
		//显示页面
		'active_page' => array(
			'0' => '所有页面',
			'1' => '指定页面',
			'2' => '例外页面'
		),
		// 暗黑模式
		'dark_switch' => '0',
		// 圆角模式
		'fillet_select' => array(
			'0' => '默认',
			'1' => '圆角'
		),
		// 大尺寸模式
		'size_select' => array(
			'0' => '默认',
			'1' => '大尺寸'
		),
		// 名称显示
		'name_switch' => '0',

	);

	public function __construct()
	{
	}

	public static function init()
	{
		register_activation_hook(ONLINE_CONTACT_WIDGET_FILE, [__CLASS__, 'plugin_activate']);
		register_deactivation_hook(ONLINE_CONTACT_WIDGET_FILE, [__CLASS__, 'plugin_deactivate']);

		if (is_admin()) {
			//插件设置连接
			add_filter('plugin_action_links', array(__CLASS__, 'action_links'), 10, 2);
			add_action('admin_menu', array(__CLASS__, 'admin_menu_handler'));

			add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_enqueue_scripts'), 1);

			add_filter('plugin_row_meta', array(__CLASS__, 'plugin_row_meta'), 10, 2);

			add_action('wp_ajax_wb_ocw_options', array(__CLASS__, 'wb_options_ajax_handler'));
		}
	}

	/**
	 * 插件设置
	 */
	public static function admin_menu_handler()
	{
		global $submenu;
		add_menu_page(
			'多合一在线客服插件',
			'多合一客服',
			'administrator',
			self::$name,
			array(__CLASS__, 'render_views'),
			plugin_dir_url(ONLINE_CONTACT_WIDGET_FILE) . 'assets/icon_for_menu.svg'
		);

		add_submenu_page(
			self::$name,
			'多合一在线客服插件',
			'插件设置',
			'administrator',
			self::$name . '#/setting-base',
			array(__CLASS__, 'render_views')
		);

		unset($submenu[self::$name][0]);
	}

	public static function render_views()
	{
		if (!current_user_can('administrator')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}

		echo '<div id="app"></div>';
	}

	// 设置连接方法
	public static function action_links($links, $file)
	{

		if ($file != plugin_basename(ONLINE_CONTACT_WIDGET_FILE))
			return $links;

		if (!get_option('wb_ocw_ver', 0)) {
			$a_link = '<a href="https://www.wbolt.com/plugins/wb-contact-widget" target="_blank"><span style="color: #FCB214;">升至Pro版</span></a>';
			array_unshift($links, $a_link);
		}

		$settings_link = '<a href="' . menu_page_url(self::$name, false) . '">设置</a>';

		array_unshift($links, $settings_link);

		return $links;
	}

	public static function admin_enqueue_scripts($hook)
	{
		if (!preg_match('#' . self::$name . '#', $hook)) return;

		$prompt_items = array();
		if (file_exists(__DIR__ . '/_prompt.php')) {
			include __DIR__ . '/_prompt.php';
		}

		$wb_ocw_ajax_nonce = wp_create_nonce('wb_ocw_admin_ajax');
		$wbm_api_ajax_nonce = wp_create_nonce('wbm_api_ajax');
		$wb_cnf = array(
			'_wb_ocw_ajax_nonce' => $wb_ocw_ajax_nonce,
			'_wbm_api_ajax_nonce' => $wbm_api_ajax_nonce,
			'base_url' => admin_url(),
			'home_url' => home_url(),
			'ajax_url' => admin_url('admin-ajax.php'),
			'dir_url' => ONLINE_CONTACT_WIDGET_URL,
			'pd_code' => ONLINE_CONTACT_WIDGET_CODE,
			'wb_vue_path' => ONLINE_CONTACT_WIDGET_URL . '/tpl/',
			'doc_url' => "https://www.wbolt.com/ocw-plugin-documentation.html",
			'pd_title' => '多合一在线客服插件',
			'login_url' => wp_login_url(),
			'pd_version' => ONLINE_CONTACT_WIDGET_VERSION,
			'is_pro' => get_option('wb_ocw_ver', 0),
			'use_theme_mail' => false,
			'action' => array(
				'act' => 'wb_ocw_options',
				'fetch' => 'get_setting',
				'push' => 'set_setting'
			),
			'subject_type' => OCW_Admin::opt('items_data.msg.subject_type'),
			'prompt' => $prompt_items,
			'wbm_url' => home_url('?wbp=member&slug=ocw')
		);
		if (OCW_Mail::use_theme_mail()) {
			$wb_cnf['use_theme_mail'] = true;
		}
		wp_register_script('wbs-inline-js', false, null, false);
		wp_enqueue_script('wbs-inline-js');
		wp_add_inline_script(
			'wbs-inline-js',
			' var wb_cnf=' . json_encode($wb_cnf, JSON_UNESCAPED_UNICODE) . ';',
			'before'
		);

		// wp_enqueue_style('wb-chunk-vendors', ONLINE_CONTACT_WIDGET_URL . '/tpl/assets/css/chunk-vendors.css', false, ONLINE_CONTACT_WIDGET_VERSION);
		wp_enqueue_style('wb-ocw', ONLINE_CONTACT_WIDGET_URL . '/tpl/assets/css/ocw.css', false, ONLINE_CONTACT_WIDGET_VERSION);
		wp_enqueue_script('wb-vendors-vue', ONLINE_CONTACT_WIDGET_URL . '/tpl/assets/js/chunk-vendors.js', null, ONLINE_CONTACT_WIDGET_VERSION, true);
		wp_enqueue_script('wb-ocw', ONLINE_CONTACT_WIDGET_URL . '/tpl/assets/js/ocw.js', null, ONLINE_CONTACT_WIDGET_VERSION, true);

		wp_enqueue_media();

		self::update_table();
	}

	public static function update_table()
	{
		global $wpdb;

		//error_log("update db \n",3,__DIR__.'/log.txt');
		$ver = get_option('ocw_db_ver', '');
		if (!$ver) {
			$ver = 1;
		}
		$ver = intval($ver);
		do {
			if ($ver >= self::$db_ver) {
				break;
			}

			if ($ver < 2) {
				$t = $wpdb->prefix . 'ocw_contact';
				$sql = $wpdb->get_var('SHOW CREATE TABLE `' . $t . '`', 1);
				if (!preg_match('#`wx`#is', $sql)) {
					$wpdb->query("ALTER TABLE $t ADD `wx` varchar(64) DEFAULT NULL AFTER `qq` ");
				}
				$ver = 2;
				update_option('ocw_db_ver', 2, false);
				//error_log("update db v1 \n",3,__DIR__.'/log.txt');
			}
		} while (0);
	}

	public static function plugin_row_meta($links, $file)
	{

		$base = plugin_basename(ONLINE_CONTACT_WIDGET_FILE);
		if ($file == $base) {
			$links[] = '<a href="https://www.wbolt.com/plugins/ocw" target="_blank">插件主页</a>';
			$links[] = '<a href="https://www.wbolt.com/ocw-plugin-documentation.html" target="_blank">说明文档</a>';
			$links[] = '<a href="https://www.wbolt.com/plugins/ocw#J_commentsSection" target="_blank">反馈</a>';
		}
		return $links;
	}

	public static function wb_options_ajax_handler()
	{
		if (!current_user_can('manage_options')) {
			exit();
		}
		$op = isset($_REQUEST['op']) ? sanitize_text_field($_REQUEST['op']) : '';
		switch ($op) {
			case 'chk_ver':
				$http = wp_remote_get('https://www.wbolt.com/wb-api/v1/themes/checkver?code=' . ONLINE_CONTACT_WIDGET_CODE . '&ver=' . ONLINE_CONTACT_WIDGET_VERSION . '&chk=1', array('sslverify' => false, 'headers' => array('referer' => home_url()),));;
				if (wp_remote_retrieve_response_code($http) == 200) {
					echo wp_remote_retrieve_body($http);
				}
				exit();
				break;

			case 'promote':
				$ret = ['code' => 0, 'desc' => 'success', 'data' => ''];
				$data = [];
				$expired = 0;
				$update_cache = false;
				do {
					$option = get_option('wb_ocw_promote', null);
					do {
						if (!$option || !is_array($option)) {
							break;
						}

						if (!isset($option['expired']) || empty($option['expired'])) {
							break;
						}

						$expired = intval($option['expired']);
						if ($expired < current_time('U')) {
							$expired = 0;
							break;
						}

						if (!isset($option['data']) || empty($option['data'])) {
							break;
						}

						$data = $option['data'];
					} while (0);

					if ($data) {
						$ret['data'] = $data;
						break;
					}
					if ($expired) {
						break;
					}

					$update_cache = true;
					$param = ['c' => 'wcw', 'h' => $_SERVER['HTTP_HOST']];
					$http = wp_remote_post('https://www.wbolt.com/wb-api/v1/promote', array('sslverify' => false, 'body' => $param, 'headers' => array('referer' => home_url()),));

					if (is_wp_error($http)) {
						$ret['error'] = $http->get_error_message();
						break;
					}
					if (wp_remote_retrieve_response_code($http) !== 200) {
						$ret['error-code'] = '201';
						break;
					}
					$body = trim(wp_remote_retrieve_body($http));
					if (!$body) {
						$ret['empty'] = 1;
						break;
					}
					$data = json_decode($body, true);
					if (!$data) {
						$ret['json-error'] = 1;
						$ret['body'] = $body;
						break;
					}
					//data = [title=>'',image=>'','expired'=>'2021-05-12','url=>'']
					$ret['data'] = $data;
					if (isset($data['expired']) && $data['expired'] && preg_match('#^\d{4}-\d{2}-\d{2}$#', $data['expired'])) {
						$expired = strtotime($data['expired'] . ' 23:50:00');
					}
				} while (0);
				if ($update_cache) {
					if (!$expired) {
						$expired = current_time('U') + 21600;
					}
					update_option('wb_ocw_promote', ['data' => $ret['data'], 'expired' => $expired], false);
				}

				header('content-type:text/json;charset=utf-8');
				echo json_encode($ret);
				exit();
				break;

			case 'verify':
				$ajax_nonce = isset($_POST['_ajax_nonce']) ? sanitize_text_field($_POST['_ajax_nonce']) : '';
				if (!wp_verify_nonce($ajax_nonce, 'wb_ocw_admin_ajax')) {

					echo json_encode(array('code' => 1, 'data' => __('Illegal operation.', 'online-contact-widget')));
					exit(0);
				}
				if (!current_user_can('manage_options')) {
					echo json_encode(array('code' => 1, 'data' => __('No permission.', 'online-contact-widget')));
					exit(0);
				}
				$key = isset($_POST['key']) ? trim(sanitize_text_field($_POST['key'])) : '';
				$host = isset($_POST['host']) ? trim(sanitize_text_field($_POST['host'])) : '';

				$param = array(
					'code' => $key,
					'host' => $host,
					'ver' => 'ocw',
				);
				$err = '';
				do {
					$http = wp_remote_post('https://www.wbolt.com/wb-api/v1/verify', array('sslverify' => false, 'body' => $param, 'headers' => array('referer' => home_url()),));
					if (is_wp_error($http)) {
						$err = __('Verification failed, please try again later', 'online-contact-widget')
							. '[error code001 ' . $http->get_error_message() . '])';
						break;
					}

					if ($http['response']['code'] != 200) {
						$err = __('Verification failed, please try again later', 'online-contact-widget')
							. '[error code001 ' . $http['response']['code'] . '])';
						break;
					}

					$body = $http['body'];


					$data = json_decode($body, true);
					if (!$data || $data['code']) {
						$err_code = $data['data'] ? $data['data'] : '';
						switch ($err_code) {
							case 100:
							case 101:
							case 102:
							case 103:
								$err = __('Configuration error, contact for', 'online-contact-widget')
									. '<a href="https://www.wbolt.com/?wb=member#/contact" target="_blank">'
									. __('technical support', 'online-contact-widget') . '</a>('
									. __('error code', 'online-contact-widget') . $err_code . ')';
								break;
							case 200:
								$err = __('Invalid key, please enter a correct key. (error code 200)', 'online-contact-widget');
								break;
							case 201:
								$err = __('Key usage out of limit. (error code 201)', 'online-contact-widget');
								break;
							case 202:
							case 203:
							case 204:
								$err = __('Verification server exception, contact for', 'online-contact-widget')
									. '<a href="https://www.wbolt.com/?wb=member#/contact" target="_blank">'
									. __('technical support', 'online-contact-widget') . '</a>('
									. __('error code', 'online-contact-widget') . $err_code . ')';
								break;
							default:
								$err = __('Unexpected error, contact for', 'online-contact-widget')
									. '<a href="https://www.wbolt.com/?wb=member#/contact" target="_blank">'
									. __('technical support', 'online-contact-widget') . '</a>('
									. __('error code', 'online-contact-widget') . $err_code . ')';
						}
						break;
					}
					if (!$data['data']) {
						$err = __('Verification failed, please try again later. (error code 004)', 'online-contact-widget');
						break;
					}
					update_option('wb_ocw_ver', $data['v'], false);
					update_option('wb_ocw_cnf_' . $data['v'], $data['data'], false);

					echo json_encode(array('code' => 0, 'data' => 'success'));
					exit(0);
				} while (false);
				echo json_encode(array('code' => 1, 'data' => $err));
				exit(0);
				break;

			case 'options':
				if (!current_user_can('manage_options') || !wp_verify_nonce($_GET['_ajax_nonce'], 'wb_ocw_admin_ajax')) {
					echo json_encode(array('o' => '', 'err' => 'no auth'));
					exit(0);
				}

				$ver = get_option('wb_ocw_ver', 0);
				$cnf = '';
				if ($ver) {
					$cnf = get_option('wb_ocw_cnf_' . $ver, '');
				}
				$list = array('o' => $cnf);
				header('content-type:text/json;charset=utf-8');
				echo json_encode($list);
				exit();
				break;

			case 'get_setting':
				$key = isset($_POST['key']) ? trim(sanitize_text_field($_POST['key'])) : null;

				$ret = array('code' => 0, 'desc' => 'success');
				$ret['data'] = self::get_opt_data($key);
				$ret['data']['cnf'] = self::$cnf_fields;
				if (!empty($ret['data']['opt'])) {
					$ret['data']['opt'] = apply_filters('get_wbm_cnf', $ret['data']['opt']);
				}
				header('content-type:text/json;charset=utf-8');
				echo json_encode($ret);
				exit();
				break;

			case 'set_setting':
				if (isset($_POST['opt'])) {
					$opt_data = $_POST['opt'];
					$opt_data = apply_filters('set_wbm_cnf', $opt_data);
					self::wb_set_setting($opt_data);
				}

				$ret = array('code' => 0, 'desc' => 'success');

				header('content-type:text/json;charset=utf-8');
				echo json_encode($ret);
				exit();
				break;

				//读取单个组件设置
			case 'get_item':
				$ret = array('code' => 1, 'data' => array(), 'desc' => 'success');
				$opt_items_data = self::opt('items_data');

				if (isset($_POST['id'])) {
					$id = trim(sanitize_text_field($_POST['id']));
					$ret['code'] = 0;
					$ret['data']['opt'] = $opt_items_data[$id];
					$ret['data']['cnf'] = self::$cnf_fields['contact_items'][$id];
				}

				header('content-type:text/json;charset=utf-8');
				echo json_encode($ret);
				exit();
				break;

				//保存单个组件设置
			case 'set_item':
				$ret = array('code' => 1, 'desc' => 'success');
				$opt = self::opt();

				if (isset($_POST['id']) && isset($_POST['opt'])) {
					$opt['items_data'][$_POST['id']] = $_POST['opt'];
					//					self::wb_set_setting($opt);
					$ret['data'] = $opt;
					$ret['save'] = self::wb_set_setting($opt);
					$ret['code'] = 0;
				}

				header('content-type:text/json;charset=utf-8');
				echo json_encode($ret);
				exit();
				break;
			case 'test_sms':

				add_filter('ocw_sms_send_result', function ($result) {
					self::$sms_result = $result;
				});
				$ret = array('code' => 0, 'desc' => 'success');
				$mobile = isset($_POST['mobile']) ? trim(sanitize_text_field($_POST['mobile'])) : '';
				$cnf = isset($_POST['opt']) ? $_POST['opt'] : array();
				if ($mobile && $cnf) {
					$msg = '测试短信';
					do_action('ocw_send_sms_test', $mobile, $cnf, $msg);
					if (isset(self::$sms_result)) {
						$ret = self::$sms_result;
					}
				}

				header('content-type:text/json;charset=utf-8');
				echo json_encode($ret);
				break;
			case 'mail_test':
				$ret = OCW_Mail::do_mail_test();

				header('content-type:text/json;charset=utf-8');
				echo json_encode($ret);
				break;
				//
		}

		exit();
	}

	public static function get_opt_data($key = '')
	{

		$ret = array();

		switch ($key) {
			case 'base':
				// $ret['opt']['login_url'] = self::opt('login_url');
				// $ret['opt']['need_login'] = self::opt('need_login');
				$ret['opt']['position'] = self::opt('position');
				$ret['opt']['dark_mode_class'] = self::opt('dark_mode_class');
				$ret['opt']['other_tool_name'] = self::opt('other_tool_name');
				$ret['opt']['items'] = self::opt('items');
				$ret['opt']['items_data'] = self::opt('items_data');
				$ret['opt']['items_orders'] = self::opt('items_orders');
				break;

			case 'pro':
				$ret['opt']['active_page'] = self::opt('active_page');
				$ret['opt']['active_device'] = self::opt('active_device');
				$ret['opt']['appoint_urls'] = self::opt('appoint_urls');
				$ret['opt']['exception_urls'] = self::opt('exception_urls');
				$ret['opt']['custom_style'] = self::opt('custom_style');
				$ret['opt']['color_head'] = self::opt('color_head');
				$ret['opt']['color_icon'] = self::opt('color_icon');
				$ret['opt']['fold_label'] = self::opt('fold_label');
				break;

			default:
				$ret['opt'] = self::opt($key);

				break;
		}

		return $ret;
	}

	/**
	 * 扩展属性
	 * @param $cnf array 当前属性
	 * @param $conf array 扩展属性
	 * @param array $skip 跳过
	 */
	public static function extend_conf(&$cnf, $conf, $skip = [])
	{
		if (is_array($conf)) foreach ($conf as  $k => $v) {
			if (!isset($cnf[$k])) {
				$cnf[$k] = $v;
				continue;
			}
			if (!empty($skip) && in_array($k, $skip)) {
				continue;
			}
			if (is_array($v)) {
				if (!is_array($cnf[$k])) {
					$cnf[$k] = array();
				}
				self::extend_conf($cnf[$k], $v, $skip);
			}
		}
	}

	/**
	 * 合并属性
	 * @param $old array 原属性
	 * @param $new array 新属性
	 */
	public static function combine_conf(&$old, $new)
	{
		if (is_array($new)) foreach ($new as  $k => $v) {
			if (!isset($old[$k])) {
				$old[$k] = $v;
			} else if (is_array($v)) {
				self::combine_conf($old[$k], $v);
			} else {
				$old[$k] = $v;
			}
		}
	}

	/**
	 * 默认设置值
	 * @return array
	 */
	public static function def_opt()
	{
		$def_items_key = self::$cnf_fields['contact_items'];
		$cnf = array(
			'items' => array('backtop', 'msg'), //选择中的选项
			'items_data' => array(
				'backtop' => '',
				'qq' => '',
				'wx' => '',
				'tel' => '',
				'email' => '',
				'msg' => array(
					'name' => '在线留言',
					'form_contact_ways' => 'mobile,email,qq,wx', //联系方式
					'subject_type' => array('业务咨询', '市场合作', '其他事宜'),
					'auto_reply_msg' => '您的工单我们已经收到，我们将会尽快跟您联系！',
					'notify_type' => '0',
					'captcha' => [
						'type' => 'none',
						'google' => [
							'public' => '',
							'private' => '',
							'score' => 0.5,
						],
					],
					'notice' => [],
					'mail' => [
						'mailer' => '0',
						'to' => '',
						'from' => '',
						'name' => '',
						'proc' => array(
							array(
								'id' => 'php',
								'name' => 'PHP'
							),
							array(
								'id' => 'qq',
								'name' => 'QQ邮箱',
								'host' => 'smtp.qq.com',
								'secure' => '',
								'port' => '25',
								'user' => '',
								'password' => '',
							),
							array(
								'id' => '163',
								'name' => '163邮箱',
								'host' => 'smtp.163.com',
								'secure' => '',
								'port' => '25',
								'user' => '',
								'password' => '',
							),
							array(
								'id' => 'other',
								'name' => '其他SMTP',
								'host' => '',
								'secure' => '',
								'port' => '',
								'user' => '',
								'password' => '',
							),
						),
					],
					'sms' => [
						'to' => '',
						'vendor' => 'upyun',
						'upyun' => [
							'id' => '',
							'secret' => '',
							'tpl' => '',
							'sign' => '',
						],
						'aliyun' => [
							'id' => '',
							'secret' => '',
							'tpl' => '',
							'sign' => '',
						],
						'huawei' => [
							'api' => '',
							'id' => '',
							'secret' => '',
							'tpl' => '',
							'sign' => '',
							'channel' => '',
						],
					],
					'need_login' => '0',
					'login_url' => '',
				),
				'order' => '',
			),
			'items_orders' => array_keys($def_items_key),
			'contact_color' => '',
			'position' => 'rb', //右下
			'position_offset_x' => 0,
			'position_offset_y' => 0,
			'is_fold' => '0', //是否收起
			'fold_icon' => '1',
			'fold_label' => '在线客服',
			'avatar_url' => '',
			'contact_name' => '',
			'contact_msg' => '我们将24小时内回复。',
			'open_msg' => '您好，有任何疑问请与我们联系！',
			'active_device' => array('0', '1'),
			'active_page' => '0',
			'appoint_urls' => '',
			'exception_urls' => '',
			'custom_style' => '',
			'color_head' => '',
			'color_icon' => '',
			'panel_hd_fcolor' => '', // 顶部文字颜色
			'custom_theme_color' => '',
			'dark_switch' => '0', // 是否黑暗模式 仅展开显示
			'fillet_select' => '0', // 展示默认、圆角
			'size_select' => '0', // 尺寸展示
			'name_switch' => '0', // 是否显示组件名称
			'other_tool_name' => '.tool-bar', // 隐藏主题原有tool class name
			'unfold_size' => '38',
			'unfold_radius' => '0',
			'panel_width' => '320',
			'buoy_icon_size' => '24',
			'buoy_icon_custom' => '',
			'buoy_animation' => '1',
			'base_font_size' => '',
			'buoy_animation_interval' => 5,
			'dark_mode_class' => '.dark-mode',
			'wb_member' => 0
		);
		//error_log(print_r($cnf,true),3,__DIR__.'/log.txt');
		return $cnf;
	}


	/**
	 * 读取设置值
	 * @param string $name
	 * @param bool $default
	 *
	 * @return bool|mixed|null
	 */
	public static function opt($name = '', $default = false)
	{
		static $options = null;

		if (null == $options) {
			$options = get_option(self::$option_name, array());

			/*$is_new = false;
			if (!$options) {
				$is_new = true;
			}*/

			//, ['subject_type','items_orders','active_device']
			self::extend_conf($options, self::def_opt());

			/*if ($is_new) {
				$new_default = array();
				foreach ($new_default as $k => $v) {
					if (is_array($v)) {
						$options[$k] = array_merge($options[$k], $v);
					} else {
						$options[$k] = $v;
					}
				}
			}*/
			if (!class_exists('WP_VK') && in_array('order', $options['items_orders'])) {
				$k = array_search('order', $options['items_orders']);
				unset($options['items_orders'][$k]);
			}
		}

		$return = null;
		do {

			if (!$name) {
				$return = $options;
				break;
			}

			$ret = $options;
			$ak = explode('.', $name);

			foreach ($ak as $sk) {
				if (isset($ret[$sk])) {
					$ret = $ret[$sk];
				} else {
					$ret = $default;
					break;
				}
			}

			$return = $ret;
		} while (0);

		return apply_filters('wb_ocw_get_conf', $return, $name, $default);
	}

	/**
	 * 保存设置值
	 * @param $data
	 *
	 * @return bool
	 */
	public static function wb_set_setting($data)
	{

		$opt = self::opt();

		foreach ($data as $key => $value) {
			$opt[$key] = self::stripslashes_deep($value);
		}

		update_option(self::$option_name, $opt);
		return true;
	}

	public static function stripslashes_deep($value)
	{
		if (empty($value)) {
			return $value;
		}

		if (is_array($value)) {

			foreach ($value as $k => $v) {
				$value[$k] = self::stripslashes_deep($v);
			}
		} else {
			$value = stripslashes($value);
		}
		return $value;
	}


	public static function plugin_deactivate()
	{
	}

	public static function plugin_activate()
	{

		global $wpdb;
		$prefix = $wpdb->prefix;
		$charset_collate = $wpdb->get_charset_collate();

		$sql = 'CREATE TABLE IF NOT EXISTS `' . $prefix . 'ocw_contact` (
				`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`sn` varchar(16) NOT NULL,
				`name` varchar(100) DEFAULT NULL,
				`email` varchar(64) DEFAULT NULL,
				`qq` varchar(32) DEFAULT NULL,
				`wx` varchar(64) DEFAULT NULL,
				`mobile` varchar(32) DEFAULT NULL,
				`title` varchar(200) DEFAULT NULL,
				`create_date` datetime DEFAULT NULL,
				`update_time` datetime DEFAULT NULL,
				`status` tinyint(3) UNSIGNED NOT NULL DEFAULT \'1\',
				`uid` bigint(20) UNSIGNED DEFAULT \'0\',
				`type` varchar(32) DEFAULT \'\',
				`pid` int(10) UNSIGNED NOT NULL DEFAULT \'0\',
				`is_read` tinyint(1) UNSIGNED NOT NULL DEFAULT \'1\',
				`is_new` tinyint(1) UNSIGNED NOT NULL DEFAULT \'0\',
				PRIMARY KEY (`id`),
				KEY `pid` (`pid`),
				KEY `uid` (`uid`),
				KEY `type` (`type`),
				KEY `status` (`status`)
			) ' . $charset_collate;
		$wpdb->query($sql);



		$sql = 'CREATE TABLE IF NOT EXISTS `' . $prefix . 'ocw_contact_content` (
				`cid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`pid` int(10) UNSIGNED NOT NULL DEFAULT \'0\',
				`uid` bigint(20) UNSIGNED NOT NULL DEFAULT \'0\',
				`pics` varchar(250) DEFAULT NULL,
				`create_date` datetime DEFAULT NULL,
				`ip` varchar(32) DEFAULT NULL,
				`content` mediumtext,
				PRIMARY KEY (`cid`),
				KEY `pid` (`pid`),
				KEY `uid` (`uid`)
			) ' . $charset_collate;
		$wpdb->query($sql);

		update_option('ocw_db_ver', self::$db_ver, false);
	}
}
