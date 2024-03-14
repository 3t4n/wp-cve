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

namespace FPFramework\Libs;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Media
{
	public function __construct()
	{
		add_action('enqueue_block_editor_assets', [$this, 'block_editor_assets']);
		
		$this->validate();
	}

	/**
	 * Load Gutenberg Editor Assets.
	 * 
	 * @return  void
	 */
	public function block_editor_assets()
	{
		wp_enqueue_style(
			'fpframework-gutenberg-styles',
			FPF_MEDIA_URL . 'admin/css/gutenberg_editor.css',
			[],
			FPF_VERSION
		);
	}

	/**
	 * Validate whether to load framework assets
	 * 
	 * @return  void
	 */
	private function validate()
	{
		if (!current_user_can('manage_options'))
		{
			return false;
		}

		global $pagenow;
		if (!in_array($pagenow, ['admin.php', 'post.php', 'post-new.php', 'edit.php', 'widgets.php', 'site-editor.php']))
		{
			return false;
		}

		add_action('admin_enqueue_scripts', [$this, 'load']);
	}

	/**
	 * Load media
	 * 
	 * @return  void
	 */
	public function load()
	{
		$this->registerAdminStyles();
		$this->registerAdminScripts();
	}

	/**
	 * Register admin scripts.
	 *
	 * @return  void
	 */
	public function registerAdminScripts()
	{
		// load framework main admin js
		wp_register_script(
			'fpframework-admin',
			FPF_MEDIA_URL . 'admin/js/fpframework_admin.js',
			[],
			FPF_VERSION,
			true
		);
		wp_enqueue_script('fpframework-admin');

		// send an object available in JS files
		$this->setAdminJSObject();
	}

	/**
	 * Register admin styles.
	 *
	 * @return  void
	 */
	public function registerAdminStyles()
	{
		// framework main admin css
		wp_register_style(
			'fpframework-admin',
			FPF_MEDIA_URL . 'admin/css/fpframework_admin.css',
			[],
			FPF_VERSION
		);
		
		wp_enqueue_style('fpframework-admin');
	}

	/**
	 * Send a helpful object to JavaScript files
	 *
	 * @return  void
	 */
	private function setAdminJSObject()
	{
		global $post;

		$data = [
			'wp_rest_nonce' => wp_create_nonce('wp_rest'),
			'nonce' => wp_create_nonce('fpf_js_nonce'),
			'media_url' => FPF_MEDIA_URL,
			'base_url' => get_site_url(),
			'admin_url' => get_admin_url(),
			'ajax_url' => admin_url('admin-ajax.php'),
			'post_id' => isset($post->ID) ? $post->ID : null,
			'FPF_NO_RESULTS_FOUND' => fpframework()->_('FPF_NO_RESULTS_FOUND'),
			'FPF_ENTER_VALID_IP_ADDRESS' => fpframework()->_('FPF_ENTER_VALID_IP_ADDRESS'),
			'FPF_LOADING' => fpframework()->_('FPF_LOADING'),
			'FPF_INVALID_IP_ADDRESS' => fpframework()->_('FPF_INVALID_IP_ADDRESS'),
			'FPF_DOWNLOADING_UPDATES_PLEASE_WAIT' => fpframework()->_('FPF_DOWNLOADING_UPDATES_PLEASE_WAIT'),
			'FPF_DATABASE_UPDATED' => fpframework()->_('FPF_DATABASE_UPDATED'),
			'FPF_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_ITEM' => fpframework()->_('FPF_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_ITEM'),
			'FPF_CB_SELECT_CONDITION_GET_STARTED' => fpframework()->_('FPF_CB_SELECT_CONDITION_GET_STARTED'),
		];

		$data = apply_filters('fpframework/filter_admin_js_object', $data);

		wp_localize_script('fpframework-admin', 'fpf_js_object', $data);
	}
}