<?php
/*!
 * Global plugin hooks.
 *
 * @since 2.0.0
 *
 * @package    Nav Menu Collapse
 * @subpackage Global
 */

if (!defined('ABSPATH'))
{
	exit;
}

/**
 * Class used to implement global hooks.
 *
 * @since 2.0.0
 */
final class Nav_Menu_Collapse_Global
{
	/**
	 * Enqueue plugin assets.
	 *
	 * @since 2.0.3 Added AJAX script options.
	 * @since 2.0.1 Improved condition.
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  string $hook_suffix Current page token.
	 * @return void
	 */
	public static function admin_enqueue_scripts($hook_suffix)
	{
		$nmc = Nav_Menu_Collapse();
		$script_dependencies = array();
		
		$script_options = array
		(
			'admin_page' => $nmc->cache->admin_page,
			'noatices' => Nav_Menu_Collapse_Noatice::output(),
			'option_name' => $nmc->cache->option_name,
			'token' => Nav_Menu_Collapse_Constants::TOKEN,

			'urls' => array
			(
				'ajax' => admin_url('admin-ajax.php')
			)
		);
		
		if ($hook_suffix === 'nav-menus.php')
		{
			$script_dependencies = array('nav-menu', 'wp-util');
			
			$collapsed = ($nmc->settings->store_collapsed_states)
			? get_user_meta(get_current_user_id(), Nav_Menu_Collapse_Constants::USER_META_COLLAPSED, true)
			: true;
			
			$script_options['collapsed'] = (empty($collapsed))
			? array()
			: $collapsed;
			
			$script_options['strings'] = array
			(
				'collapse_expand' => __('Collapse/Expand', 'nav-menu-collapse'),
				'nested' => _x('%d Nested Menu Items', 'Nested Menu Item Count', 'nav-menu-collapse'),
				'saving' => __('Saving collapsed states...', 'nav-menu-collapse')
			);
		}
		else
		{
			$script_dependencies = array('postbox');
			$script_options['urls']['current'] = remove_query_arg($nmc->cache->get_remove_query_args());
			
			$script_options['strings'] = array
			(
				'save_alert' => __('The changes you made will be lost if you navigate away from this page.', 'nav-menu-collapse')
			);
		}
		
		wp_enqueue_style('noatice', $nmc->cache->asset_path('styles', 'noatice.css'), array(), Nav_Menu_Collapse_Constants::VERSION);
		wp_enqueue_style('nmc-style', $nmc->cache->asset_path('styles', 'style.css'), array(), Nav_Menu_Collapse_Constants::VERSION);
		
		wp_enqueue_script('noatice', $nmc->cache->asset_path('scripts', 'noatice.js'), array(), Nav_Menu_Collapse_Constants::VERSION, true);
		wp_enqueue_script('nmc-script', $nmc->cache->asset_path('scripts', 'script.js'), array_merge(array('noatice'), $script_dependencies), Nav_Menu_Collapse_Constants::VERSION, true);
		
		wp_localize_script('nmc-script', 'nmc_script_options', $script_options);
	}
}
