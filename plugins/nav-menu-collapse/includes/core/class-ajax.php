<?php
/*!
 * AJAX functionality.
 *
 * @since 2.0.0
 *
 * @package    Nav Menu Collapse
 * @subpackage AJAX
 */

if (!defined('ABSPATH'))
{
	exit;
}

/**
 * Class used to implement the AJAX functionality.
 *
 * @since 2.0.0
 *
 * @uses Nav_Menu_Collapse_Wrapper
 */
final class Nav_Menu_Collapse_AJAX extends Nav_Menu_Collapse_Wrapper
{
	/**
	 * Constructor function.
	 *
	 * @since 2.0.1 Changed AJAX check.
	 * @since 2.0.0
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		if ($this->base->cache->doing_ajax)
		{
			add_action('wp_ajax_' . Nav_Menu_Collapse_Constants::HOOK_COLLAPSED, array($this, 'collapsed'));
			add_action('wp_ajax_' . Nav_Menu_Collapse_Constants::HOOK_SAVE_SETTINGS, array($this, 'save_settings'));
		}
		else
		{
			$query_arg = '';
			
			if (isset($_GET[Nav_Menu_Collapse_Constants::HOOK_SAVE_SETTINGS]))
			{
				$query_arg = Nav_Menu_Collapse_Constants::HOOK_SAVE_SETTINGS;
				
				Nav_Menu_Collapse_Noatice::add_success(__('Settings saved successfully.', 'nav-menu-collapse'));
			}
			
			if (!empty($query_arg))
			{
				$this->base->cache->push('remove_query_args', $query_arg);
			}
		}
	}
	
	/**
	 * Save the menu collapsed state for the logged in user.
	 * 
	 * @since 2.0.3 Improved structure.
	 * @since 2.0.2 Added capability check.
	 * @since 2.0.0
	 * 
	 * @access public
	 * @return void
	 */
	public function collapsed()
	{
		if ($this->_invalid_submission(Nav_Menu_Collapse_Constants::HOOK_COLLAPSED, 'edit_theme_options'))
		{
			$this->_send_error(__('You are not authorized save collapsed states.', 'nav-menu-collapse'), 403);
		}
		else if
		(
			!isset($_POST['menu_id'])
			||
			!is_numeric($_POST['menu_id'])
		)
		{
			$this->_send_error(__('Collapsed states could not be saved.', 'nav-menu-collapse'));
		}
		
		$collapsed = array();
		
		$collapsed_raw =
		(
			!isset($_POST['collapsed'])
			||
			!is_array($_POST['collapsed'])
		)
		? array()
		: $_POST['collapsed'];
		
		foreach ($collapsed_raw as $menu_item_id)
		{
			if (is_numeric($menu_item_id))
			{
				$collapsed[] = sanitize_key($menu_item_id);
			}
		}
		
		$user_id = get_current_user_id();
		$collapsed_menus = get_user_meta($user_id, Nav_Menu_Collapse_Constants::USER_META_COLLAPSED, true);
		
		$collapsed_menus = (is_array($collapsed_menus))
		? $collapsed_menus
		: array();
		
		$collapsed_menus[sanitize_key($_POST['menu_id'])] = $collapsed;

		update_user_meta($user_id, Nav_Menu_Collapse_Constants::USER_META_COLLAPSED, $collapsed_menus);
		
		wp_send_json_success(array
		(
			'no_buttons' => true
		));
	}
	
	/**
	 * Save the plugin settings.
	 *
	 * @since 2.1.2 Improved query argument.
	 * @since 2.1.0 Added data structure validation.
	 * @since 2.0.3 Improved structure.
	 * @since 2.0.2 Added capability check and additional data validation.
	 * @since 2.0.1 Removed escape from response URL.
	 * @since 2.0.0
	 *
	 * @access public
	 * @return void
	 */
	public function save_settings()
	{
		if ($this->_invalid_submission(Nav_Menu_Collapse_Constants::HOOK_SAVE_SETTINGS))
		{
			$this->_send_error(__('You are not authorized to save settings.', 'nav-menu-collapse'), 403);
		}
		else if ($this->_invalid_redirect())
		{
			$this->_send_error(__('Settings could not be saved.', 'nav-menu-collapse'));
		}
		
		$this->base->settings->prepare_meta_boxes();
		
		$option_name = sanitize_key($_POST['option-name']);
		
		update_option($option_name, Nav_Menu_Collapse_Sanitization::sanitize
		(
			/**
			 * Validate the data for the settings form.
			 *
			 * @since 2.1.0
			 *
			 * @param array $valid_data Validated data.
			 */
			apply_filters(Nav_Menu_Collapse_Constants::HOOK_VALIDATE_DATA, array())
		));
		
		wp_send_json_success(array
		(
			'url' => add_query_arg
			(
				array
				(
					'page' => $option_name,
					Nav_Menu_Collapse_Constants::HOOK_SAVE_SETTINGS => 1
				),
				
				admin_url(sanitize_text_field($_POST['admin-page']))
			)
		));
	}
	
	/**
	 * Check for invalid redirect data.
	 *
	 * @since 2.0.3
	 *
	 * @access private
	 * @return boolean True if the required redirect data is missing.
	 */
	private function _invalid_redirect()
	{
		return
		(
			!isset($_POST['admin-page'])
			||
			empty($_POST['admin-page'])
			||
			!isset($_POST['option-name'])
			||
			empty($_POST['option-name'])
		);
	}
	
	/**
	 * Check for an invalid submission.
	 *
	 * @since 2.0.3
	 *
	 * @access private
	 * @param  string $action     AJAX action to verify the nonce for.
	 * @param  string $capability User capability required to complete the submission.
	 * @return boolean            True if the submission is invalid.
	 */
	private function _invalid_submission($action, $capability = 'manage_options')
	{
		return
		(
			!check_ajax_referer($action, false, false)
			||
			(
				!empty($capability)
				&&
				!current_user_can($capability)
			)
		);
	}
	
	/**
	 * Send a general error message.
	 * 
	 * @since 2.0.3 Added status code argument.
	 * @since 2.0.0
	 * 
	 * @access private
	 * @param  string  $message     Message displayed in the error noatice.
	 * @param  integer $status_code HTTP status code to send with the error.
	 * @return void
	 */
	private function _send_error($message, $status_code = null)
	{
		wp_send_json_error
		(
			array
			(
				'noatice' => Nav_Menu_Collapse_Noatice::generate_error($message)
			),
			
			$status_code
		);
	}
}
