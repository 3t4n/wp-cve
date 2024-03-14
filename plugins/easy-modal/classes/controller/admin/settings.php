<?php class EModal_Controller_Admin_Settings extends EModal_Controller {
	static function factory()
	{
		self::$instance = new EModal_Controller_Admin_Settings;
		if(empty($_GET['action']))
		{
			self::$instance->action_edit();
		}
		else
		{
			switch($_GET['action'])
			{
				case 'edit': self::$instance->action_edit(); break;
			}
		}
	}
	public function index_url()
	{
		return emodal_admin_url('settings');
	}
	public function edit_url()
	{
		return $this->index_url();
	}
	public function action_edit()
	{
		if($this->check_post_nonce() && isset($_POST['publish']))
		{
			/*
			$EModal_License = new EModal_License;
			$EModal_License->check_license(empost('license.key'));
			if(!empty(empost('access_key')))
			{
				$new = empost('access_key');
				$old = emodal_get_option( EMCORE_SLUG. '_access_key' );
				if($new != '')
				{
					if($old === null || $old == '')
					{
						$new = SHA1($new);		
					}
					elseif($old && $old != $new && $old != SHA1($new))
					{
						$new = SHA1($new);		
					}
					emodal_update_option(EMCORE_SLUG. '_access_key', $new);
				}
				else
				{
					emodal_delete_option(EMCORE_SLUG. '_access_key');
				}
			}
			*/
			$new_values = apply_filters('emodal_settings_pre_save', emodal_get_option('emodal_settings'));
			emodal_update_option('emodal_settings', $new_values);
			do_action('emodal_settings_save', $new_values);	
			EModal_Admin_Notice::add(__('Settings Updated.', 'easy-modal' ), 'updated');
			//EModal_Admin::check_updates();
			$this->redirect_to_edit();
		}
		$view = new EModal_View_Admin_Settings_Form;
		$view->set('title', __('Easy Modal Settings', 'easy-modal' ));
		$view->set('tabs', apply_filters('emodal_admin_settings_form_tabs', array()));
		self::$instance->view = $view;
	}
}


add_filter('emodal_settings_pre_save', 'emodal_settings_pre_save', 1);
function emodal_settings_pre_save($new_values)
{
	return $new_values;
}