<?php
class MailControllerWtbp extends ControllerWtbp {
	public function testEmail() {
		$res = new ResponseWtbp();
		$email = ReqWtbp::getVar('test_email', 'post');
		if ($this->getModel()->testEmail($email)) {
			$res->addMessage(esc_html__('Now check your email inbox / spam folders for test mail.'));
		} else {
			$res->pushError ($this->getModel()->getErrors());
		}
		$res->ajaxExec();
	}
	public function saveMailTestRes() {
		$res = new ResponseWtbp();
		$result = (int) ReqWtbp::getVar('result', 'post');
		FrameWtbp::_()->getModule('options')->getModel()->save('mail_function_work', $result);
		$res->ajaxExec();
	}
	public function saveOptions() {
		$res = new ResponseWtbp();
		$optsModel = FrameWtbp::_()->getModule('options')->getModel();
		$submitData = ReqWtbp::get('post');
		if ($optsModel->saveGroup($submitData)) {
			$res->addMessage(esc_html__('Done', 'woo-product-tables'));
		} else {
			$res->pushError ($optsModel->getErrors());
		}
		$res->ajaxExec();
	}
	public function getPermissions() {
		return array(
			WTBP_USERLEVELS => array(
				WTBP_ADMIN => array('testEmail', 'saveMailTestRes', 'saveOptions')
			),
		);
	}
}
