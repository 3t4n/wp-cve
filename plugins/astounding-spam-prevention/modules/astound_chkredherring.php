<?php
/******************************
This requires that the red herring form be placed on the content page.

******************************/
if (!defined('ABSPATH')) exit;

class astound_chkredherring extends astound_module  {
	public function process($ip,&$stats=array(),&$options=array(),&$post=array()) {
		if (!empty($_POST) && array_key_exists('astound_comment_nonce',$_POST)) {
			$nonce=$_POST['astound_comment_nonce'];
			if (!empty($nonce)&&wp_verify_nonce($nonce,'astound_comment_nonce')) {
				return 'Red Herring';
			}				
		}
		return false;
	}
}
?>