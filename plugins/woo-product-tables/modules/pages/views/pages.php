<?php
class PagesViewWtbp extends ViewWtbp {
	public function displayDeactivatePage() {
		$this->assign('GET', ReqWtbp::get('get'));
		$this->assign('POST', ReqWtbp::get('post'));
		$this->assign('REQUEST_METHOD', strtoupper(ReqWtbp::getVar('REQUEST_METHOD', 'server')));
		$this->assign('REQUEST_URI', basename(ReqWtbp::getVar('REQUEST_URI', 'server')));
		parent::display('deactivatePage');
	}
}
