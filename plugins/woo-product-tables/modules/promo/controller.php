<?php
class PromoControllerWtbp extends ControllerWtbp {
	public function welcomePageSaveInfo() {
		$res = new ResponseWtbp();
		InstallerWtbp::setUsed();
		if ($this->getModel()->welcomePageSaveInfo(ReqWtbp::get('get'))) {
			$res->addMessage(esc_html__('Information was saved. Thank you!', 'woo-product-tables'));
		} else {
			$res->pushError($this->getModel()->getErrors());
		}
		$originalPage = ReqWtbp::getVar('original_page');
		$http = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';
		if (strpos($originalPage, $http . ( empty($_SERVER['HTTP_HOST']) ? '' : sanitize_text_field($_SERVER['HTTP_HOST']) )) !== 0) {
			$originalPage = '';
		}
		redirectWtbp($originalPage);
	}
	public function sendContact() {
		$res = new ResponseWtbp();
		$time = time();
		$prevSendTime = (int) get_option(WTBP_CODE . '_last__time_contact_send');
		if ($prevSendTime && ( $time - $prevSendTime ) < 5 * 60) {	// Only one message per five minutes
			$res->pushError(esc_html__('Please don\'t send contact requests so often - wait for response for your previous requests.'));
			$res->ajaxExec();
		}
		$data = ReqWtbp::get('post');
		$fields = $this->getModule()->getContactFormFields();
		foreach ($fields as $fName => $fData) {
			$validate = isset($fData['validate']) ? $fData['validate'] : false;
			$data[ $fName ] = isset($data[ $fName ]) ? trim($data[ $fName ]) : '';
			if ($validate) {
				$error = '';
				foreach ($validate as $v) {
					if (!empty($error)) {
						break;
					}
					switch ($v) {
						case 'notEmpty':
							if (empty($data[ $fName ])) {
								/* translators: %s: label */
								$error = 'selectbox' == $fData['html'] ? esc_html__('Please select %s', 'woo-product-tables') : esc_html__('Please enter %s', 'woo-product-tables');
								$error = sprintf($error, $fData['label']);
							}
							break;
						case 'email':
							if (!is_email($data[ $fName ])) {
								$error = esc_html__('Please enter valid email address', 'woo-product-tables');
							}
							break;
					}
					if (!empty($error)) {
						$res->pushError($error, $fName);
					}
				}
			}
		}
		if (!$res->error()) {
			$msg = 'Message from: ' . esc_html(get_bloginfo('name')) . ', Host: ' . esc_html(( empty($_SERVER['HTTP_HOST']) ? '' : sanitize_text_field($_SERVER['HTTP_HOST']) )) . '<br />';
			$msg .= 'Plugin: ' . WTBP_WP_PLUGIN_NAME . '<br />';
			foreach ($fields as $fName => $fData) {
				if (in_array($fName, array('name', 'email', 'subject'))) {
					continue;
				}
				if ('category' == $fName) {
					$data[ $fName ] = $fData['options'][ $data[ $fName ] ];
				}
				$msg .= '<b>' . esc_html($fData['label']) . '</b>: ' . esc_html(nl2br($data[ $fName ])) . '<br />';
			}
			if (FrameWtbp::_()->getModule('mail')->send('support@supsystic.zendesk.com', $data['subject'], $msg, $data['name'], $data['email'])) {
				update_option(WTBP_CODE . '_last__time_contact_send', $time);
			} else {
				$res->pushError( FrameWtbp::_()->getModule('mail')->getMailErrors() );
			}
			
		}
		$res->ajaxExec();
	}
	public function addNoticeAction() {
		$res = new ResponseWtbp();
		$code = ReqWtbp::getVar('code', 'post');
		$choice = ReqWtbp::getVar('choice', 'post');
		if (!empty($code) && !empty($choice)) {
			$optModel = FrameWtbp::_()->getModule('options')->getModel();
			switch ($choice) {
				case 'hide':
					$optModel->save('hide_' . $code, 1);
					break;
				case 'later':
					$optModel->save('later_' . $code, time());
					break;
				case 'done':
					$optModel->save('done_' . $code, 1);
					if ('enb_promo_link_msg' == $code) {
						$optModel->save('add_love_link', 1);
					}
					break;
			}
			$this->getModel()->saveUsageStat($code . '.' . $choice, true);
			$this->getModel()->checkAndSend( true );
		}
		$res->ajaxExec();
	}
	public function addTourStep() {
		$res = new ResponseWtbp();
		if ($this->getModel()->addTourStep(ReqWtbp::get('post'))) {
			$res->addMessage(esc_html__('Information was saved. Thank you!', 'woo-product-tables'));
		} else {
			$res->pushError($this->getModel()->getErrors());
		}
		$res->ajaxExec();
	}
	public function closeTour() {
		$res = new ResponseWtbp();
		if ($this->getModel()->closeTour(ReqWtbp::get('post'))) {
			$res->addMessage(esc_html__('Information was saved. Thank you!', 'woo-product-tables'));
		} else {
			$res->pushError($this->getModel()->getErrors());
		}
		$res->ajaxExec();
	}
	public function addTourFinish() {
		$res = new ResponseWtbp();
		if ($this->getModel()->addTourFinish(ReqWtbp::get('post'))) {
			$res->addMessage(esc_html__('Information was saved. Thank you!', 'woo-product-tables'));
		} else {
			$res->pushError($this->getModel()->getErrors());
		}
		$res->ajaxExec();
	}
	public function saveDeactivateData() {
		$res = new ResponseWtbp();
		if ($this->getModel()->saveDeactivateData(ReqWtbp::get('post'))) {
			$res->addMessage(esc_html__('Thank you for Feedback!', 'woo-product-tables'));
		} else {
			$res->pushError($this->getModel()->getErrors());
		}
		$res->ajaxExec();
	}
	public function enbStatsOpt() {
		$res = new ResponseWtbp();
		FrameWtbp::_()->getModule('options')->getModel()->save('send_stats', 1);
		$res->ajaxExec();
	}
	/**
	 * Get permissions
	 *
	 * @see controller::getPermissions();
	 */
	public function getPermissions() {
		return array(
			WTBP_USERLEVELS => array(
				WTBP_ADMIN => array('welcomePageSaveInfo', 'sendContact', 'addNoticeAction', 
					'addStep', 'closeTour', 'addTourFinish', 'saveDeactivateData', 'enbStatsOpt')
			),
		);
	}
}
