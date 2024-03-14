<?php
namespace TheTribalPlugin;

/**
 * Dashboard
 */
class Dashboard
{
    /**
	 * instance of this class
	 *
	 * @since 0.0.1
	 * @access protected
	 * @var	null
	 * */
	protected static $instance = null;

	/**
	 * Return an instance of this class.
	 *
	 * @since     0.0.1
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function __construct(){}

    public function init()
    {
		$retUpdate = $this->update();

		if($retUpdate) {
			$alertArgs = [
				'alert' => ($retUpdate['code'] == 'error') ? 'danger':'success',
				'code'  => $retUpdate['code'],
				'msg'   => $retUpdate['msg'],
				'msg-header' => $retUpdate['msg-header'] ?? '',
				'msg-content' => $retUpdate['msg-content'] ?? '',
				'close' => true
			];
		}

		$apiKey = WPOptions::get_instance()->apiKey();
		
		$publishPosts = WPOptions::get_instance()->publishPosts();
		
		$action = 'ttt_update_dashboard_user';
		if(!$apiKey && $apiKey == ''){
			$action = 'activate';
		}

		$lastDownload = HealthStatus::get_instance()->lastDownload([
            'action' => 'r',
        ]);
		
		$lastChecked = HealthStatus::get_instance()->lastChecked([
            'action' => 'r',
        ]);

		$nextScheduleCron = tttGetNextCronTimeDate();

		$defaultAuthor 	= WPOptions::get_instance()->defaultAuthor();

		$users = get_users();

        $template = tttc_get_plugin_dir() . 'admin/partials/dashboard/main.php';
		$partTemplateApi = tttc_get_plugin_dir() . 'admin/partials/dashboard/api.php';
		$partTemplateSettings = tttc_get_plugin_dir() . 'admin/partials/dashboard/settings.php';
		$partTemplateImport = tttc_get_plugin_dir() . 'admin/partials/dashboard/import.php';

        if ( is_file( $template ) ) {
            require_once $template;
        }
    }
    
	public function update()
	{
		if( $_POST )
		{
			if ( 
				empty( $_POST['_wpnonce'] ) 
				&& ! wp_verify_nonce( $_POST['_wpnonce'], 'ttt_client_update_plugin' ) 
				&& check_admin_referer( $_POST['_wp_http_referer'], 'ttt_client_update_plugin' ) 
			) {
				return;
			}

			$generalErrorVerbage = tttThrowGeneralErrorMsg();

			$arrReturnMsg = [
				'code' => 'error',
				'msg-header' => $generalErrorVerbage['header'],
				'msg' => $generalErrorVerbage['msg'],
				'status' => 200,
				'msg-content' => '',
				'action' => false
			];

			$updateSync = $this->updateSync($_POST);
						
			if($updateSync){
				return $updateSync;
			}

			if( isset($_POST['ttt_api_key']) && trim($_POST['ttt_api_key']) != '' ) {
				$updateApiKey = $this->updateAPIKey($_POST);

				if( $updateApiKey )
				{
					return $updateApiKey;
				}
			} 
			
			$forceImport = $this->forceImport($_POST);
			if( $forceImport )
			{
				return $forceImport;
			}
		}
	}

	private function forceImport($request)
	{
		if( $_POST && isset($request['action']) && $request['action'] == 'ttt_force_import' ){
			$arrReturnMsg['action'] = true;

			tttImportJobVia('Manual Import');
			tttCustomLogs("start import posts : ");

			$ret = \TheTribalPlugin\ImportPost::get_instance()->import();
			
			$returnCode = $ret->data['code'];
			$returnMsg = $ret->data['msg'];
			$returnMsgHeader = $ret->data['msg-header'] ?? '';
			$msgContent = '';

			if(isset($ret->data['code']) && ! $ret->data['success']) {
				$returnMsg = isset($ret->data['msg']['errors']['invalid'][0]) ? $ret->data['msg']['errors']['invalid'][0] : $ret->data['msg'];
				$returnCode = (!$ret->data['success']) ? 'error':'';
			}

			$msgContent = '';
			if(isset($ret->data['summary']) && isset($ret->data['post_count_imported']) && $ret->data['post_count_imported'] > 0) {
				$statusVerbage = \TheTribalPlugin\StatusVerbage::get_instance()->get('import');

				$msgContent .= '<p>';
				$msgContent .= '('. $ret->data['post_count_imported'].') '.$statusVerbage['imported']['msg'].' : ';
				$msgContent .= '</p>';

				$msgContent .= '<ul>';
				foreach($ret->data['summary']['post'] as $post) {
					$msgContent .= '<li>';
					$msgContent .= $post['title'];
					$msgContent .= '</li>';
				}
				$msgContent .= '</ul>';
			}

			$arrReturnMsg = [
				'code' 		=> $returnCode,
				'msg-header' => $returnMsgHeader,
				'msg' 		=> $returnMsg,
				'status' 	=> $ret->status,
				'msg-content' => $msgContent,
				'action' 	=> true
			];
			
			tttCustomLogs("return import posts : ");
        	tttCustomLogs($ret);
			
			tttCustomLogs("end import posts");

			return $arrReturnMsg;
		}
		return false;
	}

	private function updateSync($request)
	{
		
		if( $_POST && isset($request['action']) && $request['action'] == 'ttt_update_dashboard_user' ){
			$arrReturnMsg['action'] = true;
			if(
				isset($request['ttt_publish_post'])
				|| isset($request['ttt_post_author'])
			) {
				$publishPosts 	= sanitize_text_field($request['ttt_publish_post']);
				WPOptions::get_instance()->publishPosts([
					'action' 	=> 'u',
					'value' 	=> $publishPosts
				]);
				
				$defaultAuthor 	= sanitize_text_field($request['ttt_post_author']);
				WPOptions::get_instance()->defaultAuthor([
					'action' 	=> 'u',
					'value' 	=> $defaultAuthor
				]);

				$arrReturnMsg = [
					'code' 		=> 'success',
					'msg' 		=> 'Sucessfully Updated',
					'status' 	=> 200,
					'action' 	=> true
				];

				return $arrReturnMsg;
			}
			
		}
		return false;
	}

	private function updateAPIKey($request)
	{
		if( $_POST && isset($request['action']) && $request['action'] == 'ttt_activate_key' ){
			$arrReturnMsg['action'] = true;

			if(
				isset($request['ttt_api_key']) 
				&& !empty(trim($request['ttt_api_key'])) 
			){
				$apiKey	= sanitize_text_field($request['ttt_api_key']);
				$apiVerbage = tttGetAPIVerbage();
				
				//verify the auth
				$verifyArgs = [
					'user_domain' 	=> site_url(),
					'user_api_key' 	=> $apiKey,
				];

				$ret = \TheTribalPlugin\User::get_instance()->isValid($verifyArgs);

				//insert api key
				WPOptions::get_instance()->apiKey([
					'action' 	=> 'u',
					'value' 	=> $apiKey
				]);
				
				//insert domain used
				WPOptions::get_instance()->domain([
					'action' 	=> 'u',
					'value' 	=> site_url()
				]);
	
				$returnCode = $ret->data['code'] ?? 'error';
				$returnMsg = $ret->data['msg'] ?? '';
				$returnMsgHeader = $ret->data['msg-header'] ?? '';
	
				if(isset($ret->data['code']) && ! $ret->data['success']) {
					$returnMsg = isset($ret->data['msg']['errors']['invalid'][0]) ? $ret->data['msg']['errors']['invalid'][0] : $ret->data['msg'];
					$returnCode = (!$ret->data['success']) ? 'error':'';

					//invalid api key, means wrong
					if(isset($ret->data['msg']['errors']['invalid']) && $ret->data['msg']['errors']['invalid'][0] == 'api error'){
						$returnMsgHeader = $apiVerbage['error']['header'];
						$returnMsg = $apiVerbage['error']['msg'];
					}

					//invalid api key, means wrong
					if(isset($ret->data['msg']['errors']['ac_tag']) && $ret->data['msg']['errors']['ac_tag'][0] == 'ac tag error'){
						$acTagVerbage = tttGetACTagVerbage();
						$returnMsgHeader = $acTagVerbage['error']['header'];
						$returnMsg = $acTagVerbage['error']['msg'];
					}

					//invalid domain url
					if(isset($ret->data['msg']['errors']['alreadyused']) && $ret->data['msg']['errors']['alreadyused'][0] == 'domain already used'){
						$domainVerbage = tttGetDomainVerbage();
						$returnMsgHeader = $domainVerbage['error']['header'];
						$returnMsg = $domainVerbage['error']['msg'];
					}
					
					tttSetKeyActive(0);
				}

				if(!isset($ret->data['code']) && !is_array($ret->data)){
					$returnMsg = $ret->data;

					$getTimeOutErrror = tttThrowGeneralErrorMsg($returnMsg);
					if($getTimeOutErrror){
						$returnMsgHeader = $getTimeOutErrror['header'];
						$returnMsg = $getTimeOutErrror['msg'];
					}

					tttSetKeyActive(0);
				}

				if(isset($ret->data['code']) && $ret->data['success']) {
					$returnMsgHeader = $apiVerbage['success']['header'];
					$returnMsg = $apiVerbage['success']['msg'];

					tttSetKeyActive(1);
				}
				
				$arrReturnMsg = [
					'code' 		=> $returnCode,
					'msg-header' => $returnMsgHeader,
					'msg' 		=> $returnMsg,
					'status' 	=> $ret->status,
					'action' 	=> true
				];

				tttVerifyChecked($returnCode, $returnMsg);

				return $arrReturnMsg;
			}
		}
		return false;
	}

}