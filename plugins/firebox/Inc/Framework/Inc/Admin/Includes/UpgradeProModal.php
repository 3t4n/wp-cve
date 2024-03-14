<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Admin\Includes;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class UpgradeProModal
{
	/**
	 * The Modal ID.
	 * 
	 * @var  String
	 */
	const modal_id = 'fpfUpgradeToPro';
	
	/**
	 * Plugin slug
	 * 
	 * @var  string
	 */
	private $plugin_slug;
	
	/**
	 * Plugin name
	 * 
	 * @var  string
	 */
	private $plugin_name;

	static $run = false;
	
	public function __construct($plugin_slug = null, $plugin_name = null)
	{
		$this->plugin_slug = $plugin_slug;
		$this->plugin_name = $plugin_name;
		
		add_action('admin_footer', [$this, 'addModal'], 13);
	}

	/**
	 * Adds Upgrade to Pro Modal to the page
	 * 
	 * @return  void
	 */
	public function addModal()
	{
		if (self::$run)
		{
			return;
		}
		
		if (!function_exists('get_current_screen'))
		{
			return;
		}

		$current_screen = get_current_screen();

		$isPluginPage = strpos($current_screen->id, $this->plugin_slug) !== false;
		$isBlockEditor = $current_screen->is_block_editor;
		
		if (!$isPluginPage && !$isBlockEditor)
		{
			return;
		}

		self::$run = true;

		// CSS
		wp_register_style(
			'fpframework-pro-modal',
			FPF_MEDIA_URL . 'admin/css/fpf_pro_modal.css',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_style('fpframework-pro-modal');
		
		// JS
		wp_register_script(
			'fpframework-pro-modal',
			FPF_MEDIA_URL . 'admin/js/fpf_pro_modal.js',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_script('fpframework-pro-modal');

		// get smart tags table layout
		$content = fpframework()->renderer->admin->render('upgrade_pro', ['plugin_name' => $this->plugin_name], true);
		
		$payload = [
			'id' => self::modal_id,
			'class' => ['upgrade-pro'],
			'content' => $content,
			'width' => '480px',
			'overlay_click' => false
		];
		
		// render a pro modal
		\FPFramework\Helpers\HTML::renderModal($payload);
	}
}