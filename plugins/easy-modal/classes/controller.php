<?php class EModal_Controller {
	public function __construct(){}
	public function index_url(){}
	public function edit_url(){}
	public function add_url(){}
	static $instance;
	static function render(){
		self::$instance->view->render();
	}
	public function check_id()
	{
		if(!empty($_GET['id']) && !$_GET['id']){
			wp_redirect($this->index_url(), 302);
			exit();
		}
		return $_GET['id'];
	}
	public function redirect_to_edit()
	{
		wp_redirect($this->edit_url(), 302);
		exit();
	}
	public function check_post_nonce()
	{
		return !empty($_POST) && !empty($_POST[EMCORE_NONCE]) && wp_verify_nonce($_POST[EMCORE_NONCE], EMCORE_NONCE);
	}
	public function check_get_nonce()
	{
		return !empty($_GET) && !empty($_GET[EMCORE_NONCE]) && wp_verify_nonce($_GET[EMCORE_NONCE], EMCORE_NONCE);
	}

}