<?php class EModal_Controller_Admin_Addons extends EModal_Controller {
	static function factory()
	{
		self::$instance = new EModal_Controller_Admin_Addons;
		if(empty($_GET['action']))
		{
			self::$instance->action_browse();
		}
		else
		{
			switch($_GET['action'])
			{
				case 'edit': self::$instance->action_browse(); break;
			}
		}
	}
	public function index_url()
	{
		return emodal_admin_url('addons');
	}
	public function edit_url()
	{
		return $this->index_url();
	}
	public function action_browse()
	{
		$view = new EModal_View_Admin_Addons;
		$view->set('title', __('Easy Modal Add Ons', 'easy-modal' ));
		$view->set('tabs', apply_filters('emodal_admin_addons_tabs', array()));
		self::$instance->view = $view;
	}
}