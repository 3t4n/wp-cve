<?php

class MobileMonkeyApi
{

	private $option_prefix = 'mobilemonkey_';
	private $api_domain = 'https://api.mobilemonkey.com/';
	private $src = 'wordpress';
	private $pages = [];
	private $active_page = false;
	private $env = true;
	private $pagination;
	private $contacts;
	private $plugin_name = 'wp-chatbot';
	private $page_info;
	private $bot_id;
	public $available_company;
	public $as_mm = false;
	public $notice = false;
	public $app_domain = 'https://app.mobilemonkey.com/';

	private function getApiDomain()
	{
		return $this->api_domain;
	}

	private function getSrc()
	{
		return $this->src;
	}

	public function getOptionPrefix()
	{
		return $this->option_prefix;
	}

	private function setToken()
	{
		$token = filter_input(INPUT_GET, "auth_token", FILTER_SANITIZE_STRING);
		if ($token) {
			update_option($this->option_prefix . 'token', $token);
			return true;
		}
		return false;
	}

	private function getToken()
	{
		return get_option($this->option_prefix . 'token');
	}

	private function setCompanyId()
	{
		$company_id = filter_input(INPUT_GET, "company_id", FILTER_SANITIZE_STRING);
		if ($company_id) {
			update_option($this->option_prefix . 'company_id', $company_id);
			return true;
		}
		return false;
	}

	public function getCompanyId($connection_page_id)
	{

		if ($connection_page_id) {
			$pages = $this->pages;
			foreach ($pages as $page) {
				if ($page['id'] && $connection_page_id == $page['remote_id']) {
					if (in_array($page['company_id'], $this->available_company)){
						return $page['company_id'];
					}else return false;
				}
			}
			return get_option($this->option_prefix . 'company_id');
		}
	}
	public function getActiveRemotePageId(){
		$data = get_option($this->option_prefix . 'active_page_info');
		if ($data == false) { return $data; }
		return $data['remote_id'];
	}


    private function getInternalPageId($connection_page_id)
    {

        if ($connection_page_id) {

            $pages = $this->pages;
            foreach ($pages as $page) {
                if ($page['id'] && $connection_page_id == $page['remote_id']) {
                    return $page['id'];
                }
            }
            return get_option($this->option_prefix . 'fb_internal_page_id');
        }
    }


    public function getActiveBotId()
    {
		$data = get_option($this->option_prefix . 'active_page_info', array());
	    return $data['bot_id'];
    }


    public function getActivePageId()
    {
		$data = get_option($this->option_prefix . 'active_page_info', array());
		return $data['id'];
    }

    public function getAvailableCompany(){
        $args = [
            'timeout' => 10,
            'headers' => [
                'Authorization' => $this->getToken(),
                'Content-Type' => 'application/json; charset=utf-8',
            ],
            'method' => 'GET',
        ];
        $response = wp_remote_post($this->getApiDomain() . 'api/wordpress_settings/my_company_ids?v=' . HTCC_VERSION.'.2', $args);
        $error = $this->ErrorHandler($response,'Available Company','get');
        if ($error) {
            $connect_response = json_decode($error);
            return $connect_response;
        }
    }

    public function setAvailableCompany(){
        $company = $this->getAvailableCompany();
        update_option($this->option_prefix . 'available_company', $company);
        $this->available_company = $company;
    }

    public function getPages()
    {
        $args = [
            'timeout' => 30,
            'headers' => [
                'Authorization' => $this->getToken()
            ],
            'body' =>[
                'src' => $this->getSrc()
            ]
        ];
        $pagesObj = NULL;
        $pages = [];
        $response = wp_remote_get($this->getApiDomain() . 'api/facebook_pages/available_options?src=' . $this->getSrc().'&limit=150&v=' . HTCC_VERSION.'.3', $args);
        $content = wp_remote_retrieve_body($response);
        if (!empty($content)) {
            $pagesObj = json_decode($content);

            if (empty($pagesObj->errors)) {
                if(is_array($pagesObj->data)) {
                    foreach ($pagesObj->data as $page) {
                        $p = [
                            'name' => $page->name,
                            'remote_id' => $page->remote_id,
                            'id' => $page->facebook_page_id,
                            'bot_id' => $page->bot_id,
                            'bot_kind' => $page->bot_kind,
                            'company_id' => $page->company_id,
                            'path' => add_query_arg([
                                'page' => $this->plugin_name,
                                'connect' => $page->remote_id,
                                'page_name' => $page->name,
                                'already_connected' => $page->facebook_page_id !== null
                            ], admin_url('admin.php')),
                        ];

                        $pages[] = $p;
                    }
                }
            }
        }
        $this->pages = $pages;
        return $pages;
    }

    public function getActivePage($reset = false)
    {

        if (!$reset && !empty($this->active_page)) {
            return $this->active_page;
        }

        $actpage = get_option("mobilemonkey_active_page_info");
        if ($actpage){
			$this->active_page = $actpage;
			return $actpage;
        }else
            return false;
    }


	public function getSubscribeInfo()
	{
		$args = [
			'timeout' => 10,
			'headers' => [
				'Authorization' => $this->getToken(),
				'Content-Type' => 'application/json; charset=utf-8',
			],
			'method' => 'GET',
		];
		$response = wp_remote_post($this->getApiDomain() . 'api/wordpress/subscriptions/for_page?facebook_page_id=' . $this->getActiveRemotePageId().'&v=' . HTCC_VERSION.'.2', $args);
		$error = $this->ErrorHandler($response,'Page Subscribe Info','get');
		if ($error) {
			$connect_response = json_decode($error);
			$this->setAccountInfo($connect_response->account);
			$this->setCurrentSubscription($connect_response->subscription);
			$this->setWpPlan($connect_response->plan);
			$this->setPageInfo($connect_response);
			$this->setMessageStatistic($connect_response->current_outgoing_messages_statistic);
		}
	}

	public function isCommentGuard(){
		$args = [
			'timeout' => 10,
			'headers' => [
				'Authorization' => $this->getToken(),
				'Content-Type' => 'application/json; charset=utf-8',
			],
			'method' => 'GET',
		];
		$bot_id = $this->getActiveBotId();
		if ($bot_id){
			$response = wp_remote_post($this->getApiDomain() . 'api/bots/'.$bot_id.'/start_bots?page=1&per_page=25&kind=comment_guard&v=' . HTCC_VERSION.'.2', $args);
			$content = wp_remote_retrieve_body($response);
			if ($content){
				$data = json_decode($content);
				if ($data->start_bots){
					return true;
				}
				else return false;
			}
		}
	}

	public function setPageInfo($info){
		$data = [
			'pageName'=> $info->name,
			'connected_at'=> $info->connected_at,
			'is_wp_subscribe'=> $info->is_wp_subscription,
		];
		$this->page_info = $data;
		update_option($this->option_prefix . 'page_info', $data);
	}

	public function getPageInfo(){
		return get_option($this->option_prefix . 'page_info');
	}

	public function setAccountInfo($account){
		update_option($this->option_prefix . 'account_info', $account);
	}
	/**
	 * @return object
	 */
	public function getAccountInfo(){
		return get_option($this->option_prefix . 'account_info');
	}

	public function setMessageStatistic($account){
		update_option($this->option_prefix . 'message_statistic', $account);
	}
	/**
	 * @return object
	 */
	public function getMessageStatistic(){
		return get_option($this->option_prefix . 'message_statistic');
	}

	public function setCurrentSubscription($subscribe){
		update_option($this->option_prefix . 'current_subscribe', $subscribe);
	}
	/**
	 * @return object
	 */
	public function getCurrentSubscription(){
		return get_option($this->option_prefix . 'current_subscribe');
	}

	public function setWpPlan($plan){
		update_option($this->option_prefix . 'wp_plan', $plan);
	}
	/**
	 * @return object
	 */
	public function getWpPlan(){
		return get_option($this->option_prefix . 'wp_plan');
	}


	private function default_custom_setting(){
		$data = array();
		$data['fb_logged_in_greeting'] = 'Hi! How can we help you?';
		$data['fb_logged_out_greeting'] = 'Hi! We\'re here to answer any questions you may have';
		$data['fb_color'] = '#0084FF';
		$data['fb_greeting_dialog_display'] = 'show';
		$data['fb_greeting_dialog_delay'] = 1;
		return($data);
	}

	public function create_subscribe(){
		check_ajax_referer('htcc_nonce');
		if(!current_user_can('manage_options')) {
			wp_die('Unauthorized', 403);
		}

		$data = $_POST;
		$args = [
			'timeout' => 10,
			'headers' => [
				'Authorization' => $this->getToken(),
				'Content-Type' => 'application/json; charset=utf-8',
			],
			'body' => json_encode([
				'facebook_page_id'=> $this->getActiveRemotePageId(),
				'src' => $this->getSrc(),
			]),
			'method' => 'POST',
		];
		if(isset($data['token'])){
			$args['body'] = json_encode([
				'facebook_page_id'=> $this->getActiveRemotePageId(),
				'src' => $this->getSrc(),
				'token' => $data['token']
			]);
		}
		$response = wp_remote_post($this->getApiDomain() . 'api/wordpress/subscriptions?v=' . HTCC_VERSION.'.2', $args);
		$error = $this->ErrorHandler($response,'Subscribe','create');
		if ($error) {
			$connect_response = json_decode($error);
			$this->setAccountInfo($connect_response->account);
			$this->setCurrentSubscription($connect_response->subscription);
			$this->setWpPlan($connect_response->plan);
			$this->setPageInfo($connect_response);
			$this->setMessageStatistic($connect_response->current_outgoing_messages_statistic);
			wp_send_json_success ($error);
		}
	}

	public function cancel_subscribe(){
		check_ajax_referer('htcc_nonce');
		if(!current_user_can('manage_options')) {
			wp_die('Unauthorized', 403);
		}

		$args = [
			'timeout' => 10,
			'headers' => [
				'Authorization' => $this->getToken(),
				'Content-Type' => 'application/json; charset=utf-8',
			],
			'body' => json_encode([
				'facebook_page_id'=> $this->getActiveRemotePageId(),
				'src' => $this->getSrc(),
			]),
			'method' => 'POST',
		];
		$response = wp_remote_post($this->getApiDomain() . 'api/wordpress/subscriptions/cancel?v=' . HTCC_VERSION.'.2', $args);
		$error = $this->ErrorHandler($response,'Subscribe','Cancel');
		if ($error) {
			$connect_response = json_decode($error);
			$this->setAccountInfo($connect_response->account);
			$this->setCurrentSubscription($connect_response->subscription);
			$this->setWpPlan($connect_response->plan);
			$this->setPageInfo($connect_response);
			$this->setMessageStatistic($connect_response->current_outgoing_messages_statistic);
			wp_send_json_success ($error);
		}
	}


	private function setEnvironment($environment)
	{
		update_option($this->option_prefix . 'environment', $environment);
	}

	public function getEnvironment()
	{
		return get_option($this->option_prefix . 'environment');
	}

	public function refreshSettingsPage()
	{
		echo "<script type='text/javascript'>
				var path = location.protocol + '//' + location.host + location.pathname + '?page=wp-chatbot';
		        window.location = path;
		       
		        </script>";
	}

	public function connectLink()
	{
		$current_user = wp_get_current_user();

		if (!empty($current_user->user_email)) {
			$user_email = $current_user->user_email;
		} else {
			$user_email = get_option('admin_email', '');
		}
		return $this->getApiDomain() . 'wordpress/auth?callback="' . add_query_arg(['page' => $this->plugin_name], admin_url('admin.php')) . '"&email=' . $user_email . '&v=' . HTCC_VERSION.'.2';
	}

	public function connectMobileMonkey()
	{
		if ($this->setToken() && $this->setCompanyId()) {

			$this->getEnv();

			$this->sendUserEmail();

			$this->refreshSettingsPage();
		}
		return $this->getToken();
	}

	public function logoutMobilemonkey($reset = false)
	{
		$logout = filter_input(INPUT_GET, "logout", FILTER_SANITIZE_STRING);
		if ($logout || $reset) {
			$this->disconnectPage(get_option($this->active_page['page_id']));
			delete_option('htcc_fb_js_src');
			delete_option($this->option_prefix . 'token');
			$this->refreshSettingsPage();
		}
	}

	public function mmOnlyCheck($page_id)
	{
		$args = [
			'timeout' => 10,
			'headers' => [
				'Authorization' => $this->getToken(),
				'Content-Type' => 'application/json; charset=utf-8',
			],
			'method' => 'GET',
		];
		$response = wp_remote_post($this->getApiDomain() . 'api/facebook_pages/' . $this->getInternalPageId($page_id).'?v=' . HTCC_VERSION.'.2', $args);
		$error = $this->ErrorHandler($response,'MM Only State','get');
		if ($error) {
			$connect_response = json_decode($error);
			return $connect_response->wordpress_settings->mm_only_mode;
		}

	}


	public function connectPage()
	{
		$pageId = filter_input(INPUT_GET, "connect", FILTER_SANITIZE_STRING);
		$pageName = filter_input(INPUT_GET, "page_name", FILTER_SANITIZE_STRING);
		$alreadyConnected = filter_input(INPUT_GET, "already_connected", FILTER_SANITIZE_STRING);
		if ($pageId && $pageName) {
			set_transient( 'wp-chatbot__previously-connected-page', $alreadyConnected == '1' );
			$this->setAvailableCompany();
			if (!$this->getCompanyId($pageId)){
				$this->renderError('This Facebook page has already been connected in MobileMonkey to a company that you don\'t have access to. Please contact support@mobilemonkey.com for more information on how to resolve this issue.');
				return false;
			}
			$args = [
				'timeout' => 10,
				'body' => json_encode([
					'remote_id' => $pageId,
					'company_id' => $this->getCompanyId($pageId),
					'name' => $pageName,
					'base_url' => get_site_url(),
					'src' => $this->getSrc(),
				]),
				'headers' => [
					'Authorization' => $this->getToken(),
					'Content-Type' => 'application/json; charset=utf-8',
				],
				'method' => 'POST',
			];
			$response = wp_remote_post($this->getApiDomain() . 'api/facebook_pages?v=' . HTCC_VERSION.'.2', $args);
			$content = wp_remote_retrieve_body($response);
			$connect_response = json_decode($content);
			if (json_last_error() !== JSON_ERROR_NONE) {
				$this->renderNotice('API communication error. Unable to connect facebook page.');
			} elseif (!empty($connect_response->success)) {
				if ($connect_response->facebook_page->remote_id) {
					$options = get_option('htcc_options', array());
					$new_opt['remote_id'] = $connect_response->facebook_page->remote_id;
					$new_opt['bot_id'] = $connect_response->facebook_page->active_bot_id;
					$new_opt['name'] = $connect_response->facebook_page->name;
					$new_opt['id'] = $connect_response->facebook_page->id;
					$new_opt['path'] =add_query_arg([
                        'page' => $this->plugin_name,
                        'disconnect' => $connect_response->facebook_page->id,
                    ], admin_url('admin.php'));
                    update_option($this->option_prefix.'active_page_info', $new_opt);
					if ($connect_response->facebook_page->active_bot_id) {
                        set_transient( 'pre_value', true, YEAR_IN_SECONDS );
						$custom_settings = $this->getCustomChatSettings($connect_response->facebook_page->remote_id);
						$default_setting = $this->default_custom_setting();
						foreach ($custom_settings as $key=>$value){
							if ($value == '' || !isset($value)){
								$custom_options['fb_'.$key] = $default_setting['fb_'.$key];
							}else {
								$custom_options['fb_'.$key]=$value;
							}
						}
						$ref_cont = $this->getBotRef($connect_response->facebook_page->active_bot_id);
						$ref = stristr($ref_cont->test_link, '=');
						$ref_value = str_replace("=", "", $ref);
						update_option('htcc_fb_ref', $ref_value);
						update_option('htcc_fb_js_src', $custom_settings->js_src);
						update_option('htcc_custom_options', $custom_options);
						$options['fb_sdk_lang'] = $this->getLanguage($connect_response->facebook_page->remote_id);
						update_option('htcc_options', $options);
						delete_transient( 'done-tab' );
						delete_transient( 'current-tab' );
						delete_transient( 'cg_notice_off' );
						delete_transient( 'lead_notice_off' );
						$done_tab = array(
							'1'=>"true",
							'2'=>"false",
							'3'=>"false",
						);
						set_transient( 'done-tab', $done_tab,  YEAR_IN_SECONDS );
						set_transient( 'current-tab', '1',  YEAR_IN_SECONDS );
						$this->refreshSettingsPage();
						return true;
					}
					return true;
				}

			} elseif ($connect_response->error_code) {
				$this->renderNotice('Error code: ' . $connect_response->error_code);
				if (!empty($connect_response->errors)) {
					foreach ($connect_response->errors as $error) {
						$this->renderNotice('Error: ' . $error);
					}
				}
			} elseif (!empty($connect_response->errors)) {
				foreach ($connect_response->errors as $error) {
					$this->renderNotice('Error: ' . $error);
				}
			} else {
				$this->renderNotice('API communication error. Unable to connect facebook page.');
			}

		}
		return false;
	}


	public function AsStateSave($state, $fb_page_id)
	{
		$args = [
			'timeout' => 10,
			'headers' => [
				'Authorization' => $this->getToken(),
				'Content-Type' => 'application/json',
			],
			'body' => json_encode([
				'enabled' => $state,
				'fb_page_remote_id' => $fb_page_id,
				'src' => $this->getSrc()
			]),
			'method' => 'PUT',
		];
		$response = wp_remote_request($this->getApiDomain() . 'api/wordpress_settings/answering_service_v2?v=' . HTCC_VERSION.'.2', $args);
		$this->ErrorHandler($response,'AS State Save','');
	}

	public function localDisconnectPage()
	{
		$this->active_page = false;
		delete_option('htcc_as_options');
		delete_option($this->option_prefix . 'page_info');
		delete_option($this->option_prefix . 'account_info');
		delete_option($this->option_prefix . 'current_subscribe');
		delete_option($this->option_prefix . 'message_statistic');
		delete_option($this->option_prefix . 'wp_plan');
		delete_option($this->option_prefix . 'active_page_info');
		delete_option('htcc_fb_js_src');
		$this->refreshSettingsPage();
	}
	
	public function disconnectPage($pageId='')
	{
		if (!$pageId){
			$pageId = filter_input(INPUT_GET, "disconnect", FILTER_SANITIZE_STRING);
		}
		if ($pageId) {
			$previouslyConnected = get_transient('wp-chatbot__previously-connected-page');
			if ($previouslyConnected) {
				$this->localDisconnectPage();
				return true;
			}
			$args = [
				'timeout' => 10,
				'body' => json_encode([
					'src' => $this->getSrc(),
				]),
				'headers' => [
					'Authorization' => $this->getToken(),
					'Content-Type' => 'application/json; charset=utf-8',
				],
				'method' => 'DELETE',
			];
			$response = wp_remote_request($this->getApiDomain() . '/api/facebook_pages/' . $pageId.'?v=' . HTCC_VERSION.'.2', $args);
			$content = wp_remote_retrieve_body($response);
			$connect_response = json_decode($content);
			if (empty($content)) {
				$this->localDisconnectPage();
				return true;

			} elseif (isset($response["response"]["code"]) && $response["response"]["code"] == 422) {
				$this->renderNotice('The page is not connected!');
			} elseif ($connect_response->error_code) {
				$this->renderNotice('Error code: ' . $connect_response->error_code);
				if (!empty($connect_response->errors)) {
					foreach ($connect_response->errors as $error) {
						$this->renderNotice('Error: ' . $error);
					}
				}
			} elseif (!empty($connect_response->errors)) {
				foreach ($connect_response->errors as $error) {
					$this->renderNotice('Error: ' . $error);
				}
			} else {
				$this->renderNotice('API communication error. Unable to disconnect facebook page.');
			}
			return false;
		}
	}


	public function sendUserEmail()
	{
		if (function_exists('wp_get_current_user')) {
			$current_user = wp_get_current_user();
		}
		if (!empty($current_user->user_email)) {
			$user_email = $current_user->user_email;
		} else {
			$user_email = get_option('admin_email', '');
		}

		$args = [
			'timeout' => 10,
			'headers' => [
				'Authorization' => $this->getToken(),
				'Content-Type' => 'application/json',
			],
			'body' => json_encode([
				'user' => [
					"wp_email" => $user_email,
				],
				'src' => $this->getSrc(),
			]),
			'method' => 'PUT',
		];

		$response = wp_remote_request($this->getApiDomain() . '/api/user/', $args);
		$this->ErrorHandler($response,'User Email','put');
	}

	public function getWelcomeMessage($remote_id)
	{
		$args = [
			'timeout' => 10,
			'headers' => [
				'Authorization' => $this->getToken(),
				'Content-Type' => 'application/json',
			],
			'body' => [
				'src' => $this->getSrc()
			]
		];

		$response = wp_remote_get($this->getApiDomain() . 'api/wordpress_settings/welcome_message?fb_page_remote_id=' . $remote_id . '&v=' . HTCC_VERSION.'.2', $args);
		$error = ($this->ErrorHandler($response,'Welcome Message','get'));
		if ($error) {
			return str_replace('"', '', $error);
		}
	}

	public function updateWelcomeMessage($new_welcome_message, $fb_page_id)
	{
		$args = [
			'timeout' => 10,
			'headers' => [
				'Authorization' => $this->getToken(),
				'Content-Type' => 'application/json',
			],
			'body' => json_encode([
				'body' => $new_welcome_message,
				'fb_page_remote_id' => $fb_page_id,
				'src' => $this->getSrc()
			]),
			'method' => 'PUT',
		];
		$response = wp_remote_request($this->getApiDomain() . 'api/wordpress_settings/welcome_message?v=' . HTCC_VERSION.'.2', $args);
		$this->ErrorHandler($response,'Welcome Message','put');


	}

	public function updateLanguage($new_language, $fb_page_id)
	{
		$args = [
			'timeout' => 10,
			'headers' => [
				'Authorization' => $this->getToken(),
				'Content-Type' => 'application/json',
			],
			'body' => json_encode([
				'language' => $new_language,
				'fb_page_remote_id' => $fb_page_id,
				'src' => $this->getSrc()
			]),
			'method' => 'PUT',
		];
		$fb_lang = HTCC_Lang::$fb_lang;
		if (in_array($new_language, $fb_lang)){
			$response = wp_remote_request($this->getApiDomain() . 'api/wordpress_settings/language?v=' . HTCC_VERSION.'.2', $args);
		}else {
			$this->renderNotice('Incorrect Language Value');
		}
		$this->ErrorHandler($response,'Language','put');
	}

	public function getLanguage($remote_id)
	{
		$args = [
			'timeout' => 10,
			'headers' => [
				'Authorization' => $this->getToken(),
				'Content-Type' => 'application/json',
			],
			'body' => [
				'src' => $this->getSrc()
			]
		];

		$response = wp_remote_get($this->getApiDomain() . 'api/wordpress_settings/language?fb_page_remote_id=' . $remote_id . '&v=' . HTCC_VERSION.'.2', $args);
		$error = ($this->ErrorHandler($response,'Language','get'));
		if ($error) {
			return str_replace('"', '', $error);
		}
	}

	public function getWidgets($remote_id)
	{
		$args = [
			'timeout' => 10,
			'headers' => [
				'Authorization' => $this->getToken(),
				'Content-Type' => 'application/json',
			],
			'body' => [
				'src' => $this->getSrc()
			]
		];
		$response = wp_remote_get($this->getApiDomain() . 'api/wordpress_settings/answering_service_v2?fb_page_remote_id=' . $remote_id . '&v=' . HTCC_VERSION.'.2', $args);
		$error = $this->ErrorHandler($response,'Widget','get');
		if ($error) {
			$connect_response = json_decode($error);
			if (isset($connect_response->answering_service_mm_only_mode)||isset($connect_response->answering_service_not_available)){
			    return [];
            }
			$value_new['fb_answer'] = $connect_response->questions;
			$i=1;
			foreach ($connect_response->qualifiers as $key=>$value){
				foreach ($value as $k=>$v){
					$y=1;
					if ($k == 'question'){
						$value_new['lq_'.$i]['question'] = $v;
					}else {
						foreach ($v as $kr=>$an){
							foreach ($an as $t=>$a){
								if ($t == 'answer'){
									$value_new['lq_'.$i]['answers'.$y]['answer'] = $a;
								}else {
									$value_new['lq_'.$i]['answers'.$y]['qualified'] = $a;
								}
							}
							$y++;
						}
					}
				}
				$i++;
			}
			$value_new['notify_mode'] = $connect_response->notify_mode;
			$value_new['fb_welcome_message'] = $this->getWelcomeMessage($remote_id);
			$value_new['thank_message'] = $connect_response->thank_you_message;
			$value_new['email'] = $connect_response->notify_email;
			$value_new['fb_as_state'] = $connect_response->enabled;
			return $value_new;
		} else {
			return false;
		}
	}

	public function getTriggers($remote_id)
	{
		$args = [
			'timeout' => 10,
			'headers' => [
				'Authorization' => $this->getToken(),
				'Content-Type' => 'application/json',
			],
			'body' => [
				'src' => $this->getSrc()
			]
		];

		$response = wp_remote_get($this->getApiDomain() . 'api/wordpress_settings/triggers?fb_page_remote_id=' . $remote_id . '&v=' . HTCC_VERSION.'.2', $args);
		$error = $this->ErrorHandler($response,'Q&A','get');
		if ($error) {
			$connect_response = json_decode($error);
			$i=1;
			$value_new['advanced_triggers_present'] = $connect_response->advanced_triggers_present;
			foreach ($connect_response->triggers as $key=>$value){
				foreach ($value as $k=>$v){
					if ($k == 'bot_responses'){
						$value_new['qa_'.$i][$k] = $v[0];
					}else {
						foreach ($v as $kr=>$an){
							$value_new['qa_'.$i][$k][]=$an;
						}
					}
				}
				$i++;
			}
			return $value_new;
		} else {
			return false;
		}
	}



	public function setWidgets($options,$page_id,$update)
	{
		$var = true;
		$trig = true;

		if (!$update){
            $triggers = $this->getTriggers($page_id);
            $data_widget = $this->getWidgets($page_id);
            $this->as_mm = empty($data_widget)?true:false;
			$current_welcome_message = $this->getWelcomeMessage($page_id);
			$pre_val = get_transient( 'pre_value' );
			if (count($options,COUNT_NORMAL)==1 && $options['fb_welcome_message']||!$options) {
				if ($this->as_mm == false) {
					$data_widget = array_merge($data_widget, $triggers);
					foreach ($data_widget as $key=>$value){
						if (strpos($key, 'lq_')!==false){
							$var=false;
						}
					}

					if ($var){
						$data_widget['lq_1']['question'] = 'What is your budget?';
						$data_widget['lq_1']['answers1']['answer'] = '$0';
						$data_widget['lq_1']['answers2']['answer'] = '$1-$99';
						$data_widget['lq_1']['answers3']['answer'] = '$100-$500';
						$data_widget['lq_1']['answers4']['answer'] = '$500+';
						$data_widget['lq_1']['answers4']['qualified'] = '1';
					}
					if ($pre_val==true) {
						foreach ($data_widget as $k => $v) {
							if (strpos($k, 'qa_') !== false) {
								$trig = false;
							}
						}
						if ($trig) {
							$data_widget['qa_1']['phrases'] = '';
							$data_widget['qa_1']['bot_responses'] = '';
						}
					}
					$data_widget['answering_service_mm_only_mode'] = false;
					$data_widget['fb_welcome_message'] = $current_welcome_message;
					$data_widget['advanced_triggers_present'] = $triggers['advanced_triggers_present'];
					$data_widget['fb_as_state'] = 0;
					update_option('htcc_as_options', $data_widget);
				}else {
					if ($options){
						foreach ($options as $key => $value) {
							if (strpos($key, 'qa_') !== false) {
								if (!empty($value['phrases']) && !empty($value['bot_responses'])) {
									unset($options[$key]);
								}
							}
						}
						$data_widget = array_merge($options, $triggers);
						if (!$options) {
							foreach ($data_widget as $k => $v) {
								if (strpos($k, 'qa_') !== false) {
									$trig = false;
								}
							}
							if ($trig) {
								$data_widget['qa_1']['phrases'] = '';
								$data_widget['qa_1']['bot_responses'] = '';
							}
						}
						$data_widget['fb_welcome_message'] = $current_welcome_message;
						$data_widget['answering_service_mm_only_mode'] = true;
						$data_widget['advanced_triggers_present'] = $triggers['advanced_triggers_present'];
						update_option('htcc_as_options', $data_widget);
					}
				}
			}
			if ($this->as_mm == false) {
				$data_widget = array_merge($data_widget, $triggers);
                $pre_val = get_transient( 'pre_value' );
                foreach ($data_widget as $key=>$value){
                    if (strpos($key, 'lq_')!==false){
                        $var=false;
                    }
                }

                if ($var){
                    $data_widget['lq_1']['question'] = 'What is your budget?';
                    $data_widget['lq_1']['answers1']['answer'] = '$0';
                    $data_widget['lq_1']['answers2']['answer'] = '$1-$99';
                    $data_widget['lq_1']['answers3']['answer'] = '$100-$500';
                    $data_widget['lq_1']['answers4']['answer'] = '$500+';
                    $data_widget['lq_1']['answers4']['qualified'] = '1';
                }
                if ($pre_val==true) {
                    foreach ($data_widget as $k => $v) {
                        if (strpos($k, 'qa_') !== false) {
                            $trig = false;
                        }
                    }
                    if ($trig) {
                        $data_widget['qa_1']['phrases'] = '';
                        $data_widget['qa_1']['bot_responses'] = '';
                    }
                }
				$data_widget['answering_service_mm_only_mode'] = false;
				$data_widget['fb_welcome_message'] = $current_welcome_message;
				$data_widget['advanced_triggers_present'] = $triggers['advanced_triggers_present'];
				update_option('htcc_as_options', $data_widget);
			}else {
				if ($options){
				foreach ($options as $key => $value) {
					if (strpos($key, 'qa_') !== false) {
						if (!empty($value['phrases']) && !empty($value['bot_responses'])) {
							unset($options[$key]);
						}
					}
				}
				$data_widget['answering_service_mm_only_mode'] = empty($data_widget) ? true : '';
				$data_widget = array_merge($options, $triggers);
				}
				if (!$options) {
					foreach ($data_widget as $k => $v) {
						if (strpos($k, 'qa_') !== false) {
							$trig = false;
						}
					}
					if ($trig) {
						$data_widget['qa_1']['phrases'] = '';
						$data_widget['qa_1']['bot_responses'] = '';
					}
				}
				$data_widget['answering_service_mm_only_mode'] = true;
				$data_widget['advanced_triggers_present'] = $triggers['advanced_triggers_present'];
				update_option('htcc_as_options', $data_widget);
			}
		}else{
			$data_widget = $this->getWidgets($page_id);
			$triggers = $this->getTriggers($page_id);
            $this->as_mm = empty($data_widget)?true:false;
            $state=empty($data_widget)?true:false;
            if ($state==false){
                if ($options['fb_as_state']== null || $options['fb_as_state']==0){
                    $value_new['enabled'] = false;
                } else {
                    $value_new['enabled'] = true;
                }

                $value_new['questions'] = $options['fb_answer'];
                foreach ($options as $key=>$value){
                    if (strpos($key, 'lq_')!==false){
                        $answer = array();
                        $question = '';
                        foreach ($value as $k=>$v){
                            if ($k=='question'){
                                $question = $v;
                            }else{
                                if(isset($v['answer'])){
                                    if (!ctype_space($v['answer'])){
                                        $answer[]=array('answer'=>$v['answer'],'qualified'=> (bool)$v['qualified']);
                                    }
                                }
                            }
                        }
                        $value_new["qualifiers"][]=[
                            "question"=>$question,
                            "answers"=>$answer
                        ];

                    }
                }
                if (!isset($value_new["qualifiers"])){
                    $value_new["qualifiers"]=[];
                }
                $value_new["notify_email"]= $options["email"];
                $value_new["notify_mode"]= $options['notify_mode'];
                $value_new['thank_you_message'] = $options['thank_message'];
                $value_new['fb_page_remote_id'] = $this->getActiveRemotePageId();
				$this->updateWelcomeMessage($options['fb_welcome_message'],$page_id);
                $this->updateWidgets($value_new);

            }
            foreach ($options as $key=>$value){
                if (strpos($key, 'qa_')!==false){
                    $phrases = array();
                    $bot_responses = array();
                    foreach ($value as $k=>$v){
                        if ($k=='bot_responses'&&!empty($v)){
                            $bot_responses[] = $v;
                        }else{
                            if(!empty($v)){
                                if (!ctype_space($v)){
                                    $phrases=$v;
                                }
                            }
                        }

                    }
                    $trigger_new["triggers"][]=[
                        "phrases"=>$phrases,
                        "bot_responses"=>$bot_responses
                    ];
                }
            }
            $options['advanced_triggers_present'] = $triggers['advanced_triggers_present'];
			update_option('htcc_as_options', $options);
            $trigger_new['fb_page_remote_id'] = $this->getActiveRemotePageId();
            $this->updateTriggers($trigger_new);
		}
		return true;

	}

	public function updateTriggers($object)
	{
		$args = [
			'timeout' => 10,
			'headers' => [
				'Authorization' => $this->getToken(),
				'Content-Type' => 'application/json',
			],
			'body' => json_encode($object),
			'method' => 'PUT',
		];
		$response = wp_remote_request($this->getApiDomain() . 'api/wordpress_settings/triggers', $args);
		$error = ($this->ErrorHandler($response,'Triggers','put'));
		if ($error) {
			return $response;
		}
	}

	public function updateWidgets($object)
	{
		$args = [
			'timeout' => 10,
			'headers' => [
				'Authorization' => $this->getToken(),
				'Content-Type' => 'application/json',
			],
			'body' => json_encode($object),

			'method' => 'PUT',
		];
		$response = wp_remote_request($this->getApiDomain() . 'api/wordpress_settings/answering_service_v2', $args);
		$error = ($this->ErrorHandler($response,'Widget','put'));
		if ($error) {
			return $error;
		}
	}

	public function getBotRef($bot_id)
	{
		$args = [
			'timeout' => 10,
			'headers' => [
				'Authorization' => $this->getToken(),
				'Content-Type' => 'application/json; charset=utf-8',
			],
			'body' => [
				'src' => $this->getSrc()
			],
			'method' => 'GET',
		];
		$response = wp_remote_post($this->getApiDomain() . 'api/bots/' . $bot_id, $args);
		$error = ($this->ErrorHandler($response,'Bot Ref','get'));
		if ($error) {
			$connect_response = json_decode($error);
			return $connect_response;
		}
	}


	public function getEnv()
	{

		$args = [
			'timeout' => 10,
			'headers' => [
				'Authorization' => $this->getToken(),
			],
		];
		$response = wp_remote_get($this->getApiDomain() . '/api/env/', $args);
		$content = wp_remote_retrieve_body($response);
		if (!empty($content)) {

			$env = json_decode($content);

			if (json_last_error() !== JSON_ERROR_NONE) {
				$this->renderNotice('API communication error. Please try again later.');
			} elseif (!empty($env->errors)) {
				$this->renderNotice('API communication error. Please try again later.');
			} else {
				$this->env = $env;

				$options = get_option('htcc_options', array());
				$options['fb_app_id'] = $this->env->fb_app_id;
				update_option('htcc_options', $options);

				$this->setEnvironment($env);

				return $env;
			}
		}

		return false;
	}

	public function renderNotice($text)
	{
		$setting_page_args = [
			'text' => $text,
		];
		HT_CC::view('ht-cc-admin-fb-button-notice', $setting_page_args);
	}
	public function renderError($text)
	{
		$setting_page_args = [
			'text' => $text,
		];
		HT_CC::view('ht-cc-admin-fb-button-error', $setting_page_args);
	}

	public function settingSaveError($type)
	{
		$text = "";
		switch ($type){
			case "AS":
				$text = "Changes to the Answering Service fields will not be saved if you leave the fields blank. Please include non-empty field if you wish to make a change.";
				break;
			case "email":
				$text = "Validation failed: Invalid recipient email address(es)";
				break;
			case "welcome_message":
				$text = "Changes to the Welcome Message field will not be saved if you leave the fields blank. Please include non-empty field if you wish to make a change";
				break;
			case "delay_length":
				$text = "Greeting Dialog Delay shouldn't exceed 9 digits.";
				break;
			case "delay_0":
				$text = "Greeting Dialog Delay cannot be lower than 1 second";
				break;
		}
		add_settings_error(
		'htcc_setting_group', // setting name
		'', //
		__("$text", 'wp-chatbot'),
		'error' // type of notify
		);
	}
	public function debug()
	{
		$options = [];
		$options['token'] = get_option($this->option_prefix . 'token');
		$options['environment'] = get_option($this->option_prefix . 'environment');
		$options['htcc_options'] = get_option('htcc_options');
		return var_dump($options);
	}

	private function setContacts($data)
	{
		$this->contacts = $data;
	}

	public function getContacts()
	{
		if (empty($this->contacts)) {
			$this->getData();
		}
		return $this->contacts;
	}

	private function setPagination($data)
	{
		$this->pagination = $data;
	}

	public function getPagination()
	{
		if (empty($this->pagination)) {
			$this->getData();
		}
		return $this->pagination;
	}

	private function getArgs()
	{
		$get = [
			'page' => 1,
			'pre_page' => 25,
			'sort_column' => 'created_at',
			'sort_direction' => 'desc',
		];

		$paged = filter_input(INPUT_GET, "paged", FILTER_SANITIZE_STRING);
		if (!empty($paged)) {
			$get['page'] = $paged;
		}

		$orderby = filter_input(INPUT_GET, "orderby", FILTER_SANITIZE_STRING);
		if (!empty($orderby)) {
			if($orderby=='name'){
				$get['sort_column'] = 'first_name';
			}else{
				$get['sort_column'] = $orderby;
			}
		}

		$order = filter_input(INPUT_GET, "order", FILTER_SANITIZE_STRING);
		if (!empty($order)) {
			$get['sort_direction'] = $order;
		}

		return $get;

	}


	public function updateCustomChatSettings($new_value, $fb_page_id)
	{
		$args = [
			'timeout' => 10,
			'headers' => [
				'Authorization' => $this->getToken(),
				'Content-Type' => 'application/json',
			],
			'body' => json_encode(
				$new_value
			),
			'method' => 'PUT',
		];
		$response = wp_remote_request($this->getApiDomain() . '/api/wordpress_settings/customer_chat?fb_page_remote_id=' . $fb_page_id . '&v=' . HTCC_VERSION.'.2', $args);
		$error = ($this->ErrorHandler($response,"custom settings",'put'));

	}

	public function getCustomChatSettings($remote_id)
	{
		$args = [
			'timeout' => 10,
			'headers' => [
				'Authorization' => $this->getToken(),
				'Content-Type' => 'application/json',
			],'method' => 'GET',
		];

		$response = wp_remote_get($this->getApiDomain() . 'api/wordpress_settings/customer_chat?fb_page_remote_id=' . $remote_id . '&v=' . HTCC_VERSION.'.2', $args);
		$error = ($this->ErrorHandler($response,"custom settings",'get'));
		if ($error) {
			return json_decode($error);
		}
	}

	public function setCustomChatSettings($page_id,$options,$update){
		$custom_settings = $this->getCustomChatSettings($page_id);
		if (!$update){
			if (!$options){
				$custom_settings = $this->getCustomChatSettings($page_id);
				if (!$custom_settings) {
					return false;
				}
				$default_setting = $this->default_custom_setting();
				foreach ($custom_settings as $key=>$value){
					if ($value == '' || !isset($value)){
						$custom_options['fb_'.$key] = $default_setting['fb_'.$key];
					}else {
						$custom_options['fb_'.$key]=$value;
					}
				}
				update_option('htcc_fb_js_src', $custom_settings->js_src);
				update_option('htcc_custom_options', $custom_options);
			}
		}
		if (!$custom_settings) {
			return false;
		}
		foreach ($custom_settings as $key=>$value){
			switch ($key){
				case 'js_src':
					break;
				case 'hide_mobile':
					$options['fb_'.$key] = false;
					break;
				case 'hide_desktop':
					$options['fb_'.$key] = false;
					break;
				default:
					$new_value[$key] = !isset($options['fb_'.$key])?'':$options['fb_'.$key];
				break;
			}
		}
		if (!empty($new_value)){
			$this->updateCustomChatSettings($new_value,$page_id);
		}
		return true;
	}

	private function ErrorHandler($response,$point,$type){
		$content = wp_remote_retrieve_body($response);
		$connect_response = json_decode($content);
		$code = wp_remote_retrieve_response_code( $response );
		$type =  "<style>#setting-error-settings_updated{display: none;}</style>";
		if (isset($code) && $code == 200) {
			$type ='';
			return $content;
		} elseif ($connect_response->error_code) {
			$this->renderError('Error code: ' . $connect_response->error_code);
			if (!empty($connect_response->errors)) {
				foreach ($connect_response->errors as $error) {
					$this->renderError('Error: ' . $error);
				}
				return $error;
			}
		} elseif (!empty($connect_response->errors)) {
			foreach ($connect_response->errors as $error) {
			    if ($code==422||$code==401||$code==404){
					if ($point!="Welcome Message") {
						delete_option('mobilemonkey_active_page_info');
						break;
					}
                }
			}
		}elseif ($code==404){
			delete_option('mobilemonkey_active_page_info');
			return false;
		} else {
			if ($point=="Welcome Message" && $code==422){
				return true;
			}else {
				$this->renderError('API communication error. Unable to '.$type .' ' .$point.'');
			}
		}
		echo $type;
	}
	public function csv(){
		check_ajax_referer('htcc_nonce');
		if(!current_user_can('manage_options')) {
			wp_die('Unauthorized', 403);
		}
		$contacts = $this->getContacts();
		$header_row = array(
			'Name',
			'Email',
			'Gender',
			'Locale',
			'Time Zone',
			'Created At'
		);
		$data_rows = array();
		foreach ( $contacts as $contact )
		{
			$row = array(
				$contact->first_name.$contact->last_name,
				$contact->email,
				$contact->gender,
				$contact->locale,
				$contact->timezone,
				$contact->created_at
			);
			$data_rows[] = $row;
		}
		header('Content-Description: File Transfer');
		header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename=contact.csv');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		$fh = fopen( 'php://output', 'w' );
		fputcsv( $fh, $header_row );
		foreach ( $data_rows as $data_row ) {
			fputcsv( $fh, $data_row );
		}
		fclose($fh);
		die();
	}
	private function getData()
	{
		$get = $this->getArgs();

		$args = [
			'timeout' => 10,
			'headers' => [
				'Authorization' => $this->getToken(),
			],
		];

		$activePageId = $this->getActiveRemotePageId();

		$response = wp_remote_get($this->getApiDomain() . 'api/wordpress/contacts?remote_page_id=' . $activePageId . '&page=' . $get['page'] . '&per_page=' . $get['pre_page'] . '&sort_column=' . $get['sort_column'] . '&sort_direction=' . $get['sort_direction'] . '&src=' . $this->getSrc(), $args);
		$content = wp_remote_retrieve_body($response);
		if (!empty($content)) {
			$contacts = json_decode($content);
			$this->setContacts($contacts->contacts);
			$this->setPagination($contacts->pagination);
		}
	}
}
