<?php class EModal_Controller_Admin_Help extends EModal_Controller {
	static function factory()
	{
		self::$instance = new EModal_Controller_Admin_Help;
		self::$instance->action_index();
	}
	public function index_url()
	{
		return emodal_admin_url('help');
	}
	public function action_index()
	{
		$view = new EModal_View_Admin_Help;
		$view->set('title', __('Help', 'easy-modal' ));
		$view->set('tabs', apply_filters('emodal_admin_help_tabs', array()));
		self::$instance->view = $view;
	}
}