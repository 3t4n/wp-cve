<?php
namespace TheTribalPlugin;

class AjaxImportPost
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
        add_action('wp_ajax_ttt_import_post', [$this, 'import']);
        //add_action('wp_ajax_ttt_import_post', [$this, 'import']);
    }

    public function import()
    {
		tttCustomLogs("start import posts : ");
		tttCustomLogs("manual import ");

        $ret =  \TheTribalPlugin\ImportPost::get_instance()->import();
		tttCustomLogs("manual import ret");
		tttCustomLogs($ret);
		tttCustomLogs("manual import ret");
		
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

		$getLastCheck = \TheTribalPlugin\HealthStatus::get_instance()->lastChecked([
			'action' => 'r',
		]);
		
		$getLastImport = \TheTribalPlugin\HealthStatus::get_instance()->lastDownload([
			'action' => 'r',
		]);
		
		$dateGetLastImport = '';
		if( $getLastImport && $getLastImport != ''){
			$dateGetLastImport = date_i18n('d F Y h:i A', strtotime($getLastImport));
		}

		$arrReturnMsg = [
			'code' 						=> $returnCode,
			'msg_header' 				=> $returnMsgHeader,
			'msg' 						=> $returnMsg,
			'status' 					=> $ret->status,
			'msg_content' 				=> $msgContent,
			'action' 					=> true,
			'last_check' 				=> date_i18n('d F Y h:i A', strtotime($getLastCheck)),
			'last_successfull_import' 	=> $dateGetLastImport
		];

		tttCustomLogs("return import posts : ");
        tttCustomLogs($ret);

		tttCustomLogs("end import posts");

		wp_send_json_error($arrReturnMsg);
    }

}