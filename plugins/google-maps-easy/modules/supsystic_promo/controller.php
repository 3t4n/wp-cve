<?php
class supsystic_promoControllerGmp extends controllerGmp {
    public function welcomePageSaveInfo() {
		$res = new responseGmp();
		installerGmp::setUsed();
		if($this->getModel()->welcomePageSaveInfo(reqGmp::get('get'))) {
			$res->addMessage(__('Information was saved. Thank you!', GMP_LANG_CODE));
		} else {
			$res->pushError($this->getModel()->getErrors());
		}
		$originalPage = reqGmp::getVar('original_page');
		$http = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';
		if(strpos($originalPage, $http. $_SERVER['HTTP_HOST']) !== 0) {
			$originalPage = '';
		}
		redirectGmp($originalPage);
	}
	public function sendContact() {
		$res = new responseGmp();
		$time = time();
		$prevSendTime = (int) get_option(GMP_CODE. '_last__time_contact_send');
		if($prevSendTime && ($time - $prevSendTime) < 5 * 60) {	// Only one message per five minutes
			$res->pushError(__('Please don\'t send contact requests so often - wait for response for your previous requests.'));
			$res->ajaxExec();
		}
        $data = reqGmp::get('post');
        $fields = $this->getModule()->getContactFormFields();
		foreach($fields as $fName => $fData) {
			$validate = isset($fData['validate']) ? $fData['validate'] : false;
			$data[ $fName ] = isset($data[ $fName ]) ? trim($data[ $fName ]) : '';
			if($validate) {
				$error = '';
				foreach($validate as $v) {
					if(!empty($error))
						break;
					switch($v) {
						case 'notEmpty':
							if(empty($data[ $fName ])) {
								$error = $fData['html'] == 'selectbox' ? __('Please select %s', GMP_LANG_CODE) : __('Please enter %s', GMP_LANG_CODE);
								$error = sprintf($error, $fData['label']);
							}
							break;
						case 'email':
							if(!is_email($data[ $fName ]))
								$error = __('Please enter valid email address', GMP_LANG_CODE);
							break;
					}
					if(!empty($error)) {
						$res->pushError($error, $fName);
					}
				}
			}
		}
		if(!$res->error()) {
			$msg = 'Message from: '. get_bloginfo('name').', Host: '. $_SERVER['HTTP_HOST']. '<br />';
			$msg .= 'Plugin: '. GMP_WP_PLUGIN_NAME. '<br />';
			foreach($fields as $fName => $fData) {
				if(in_array($fName, array('name', 'email', 'subject'))) continue;
				if($fName == 'category')
					$data[ $fName ] = $fData['options'][ $data[ $fName ] ];
                $msg .= '<b>'. $fData['label']. '</b>: '. nl2br($data[ $fName ]). '<br />';
            }
			if(frameGmp::_()->getModule('mail')->send('support@supsystic.zendesk.com', $data['subject'], $msg, $data['name'], $data['email'])) {
				update_option(GMP_CODE. '_last__time_contact_send', $time);
			} else {
				$res->pushError( frameGmp::_()->getModule('mail')->getMailErrors() );
			}

		}
        $res->ajaxExec();
	}
	public function addNoticeAction() {
		$res = new responseGmp();
		$code = reqGmp::getVar('code', 'post');
		$choice = reqGmp::getVar('choice', 'post');
		if(!empty($code) && !empty($choice)) {
			$optModel = frameGmp::_()->getModule('options')->getModel();
			switch($choice) {
				case 'hide':
					$optModel->save('hide_'. $code, 1);
					break;
				case 'later':
					$optModel->save('later_'. $code, time());
					break;
				case 'done':
					$optModel->save('done_'. $code, 1);
					if($code == 'enb_promo_link_msg') {
						$optModel->save('add_love_link', 1);
					}
					break;
			}
			$this->getModel()->saveUsageStat($code. '.'. $choice, true);
			$this->getModel()->checkAndSend( true );
		}
		$res->ajaxExec();
	}
  public function sendSubscribeMail()
  {
      $res = new responseGmp();
      $data = reqGmp::get('post');
      $apiUrl = 'https://supsystic.com/wp-admin/admin-ajax.php';
      $reqUrl = $apiUrl . '?action=ac_get_plugin_installed';
      $mail = $data['data'];
      $isPro = !empty($this->getModule('suspsystic_promo')->isPro()) ? true : false;
      $data = array(
          'body' => array(
              'key' => 'kJ#f3(FjkF9fasd124t5t589u9d4389r3r3R#2asdas3(#R03r#(r#t-4t5t589u9d4389r3r3R#$%lfdj',
              'user_name' => $mail['username'],
              'user_email' => $mail['email'],
              'site_url' => get_bloginfo('wpurl'),
              'site_name' => get_bloginfo('name'),
              'plugin_code' => 'gmp',
              'is_pro' => $isPro,
          ),
      );
      $response = wp_remote_post($reqUrl, $data);
      if (is_wp_error($response)) {
          $res->pushError( 'Some errors' );
      } else {
          update_option('gmp_ac_subscribe', true);
      }
      $res->ajaxExec();
  }
  public function sendSubscribeRemind()
  {
      $res = new responseGmp();
      update_option('gmp_ac_remind', date("Y-m-d h:i:s", time() + 86400));
      $res->ajaxExec();
  }
  public function sendSubscribeDisable()
  {
      $res = new responseGmp();
      update_option('gmp_ac_disabled', true);
      $res->ajaxExec();
  }
  	public function getNoncedMethods() {
		return array('welcomePageSaveInfo', 'sendContact', 'addNoticeAction', 'sendSubscribeMail', 'sendSubscribeRemind', 'sendSubscribeDisable');
	}
	/**
	 * @see controller::getPermissions();
	 */
	public function getPermissions() {
		return array(
			GMP_USERLEVELS => array(
				GMP_ADMIN => array('welcomePageSaveInfo', 'sendContact', 'addNoticeAction', 'sendSubscribeMail', 'sendSubscribeRemind', 'sendSubscribeDisable'),
			),
		);
	}
}
