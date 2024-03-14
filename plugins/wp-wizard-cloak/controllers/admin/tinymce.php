<?php 
/**
 * Admin Home page
 * 
 * @author Pavel Kulbakin <p.kulbakin@gmail.com>
 */
class PMLC_Admin_Tinymce extends PMLC_Controller_Tinymce {
	
	public function index() {
		
		$this->data['links'] = $links = new PMLC_Link_List();
		$links->getBy(array('preset' => '', 'is_trashed' => 0), 'name')->convertRecords();
		
		$this->render();
	}
}