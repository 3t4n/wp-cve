<?php
namespace FPFramework\Admin\Includes;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class UpdateNotice
{
	/**
	 * Plugin alias for which we try to show an update notice.
	 * 
	 * @var  string
	 */
	private $plugin_alias;

	/**
	 * Plugin name for which we try to show an update notice.
	 * 
	 * @var  string
	 */
	private $plugin_name;
	
	/**
	 * Plugin version for which we try to show an update notice.
	 * 
	 * @var  string
	 */
	private $plugin_version;
	
	/**
	 * Holds the pages where the Update notice can run.
	 * 
	 * @var  array
	 */
	private $valid_pages;
	
	public function __construct($plugin_alias = null, $plugin_name = null, $plugin_version = '', $valid_pages = [])
	{
		$this->plugin_alias = $plugin_alias;
		$this->plugin_name = $plugin_name;
		$this->plugin_version = $plugin_version;
		$this->valid_pages = $valid_pages;
	}
	
	public function init()
	{
		$this->registerAjax();

		if (!$this->canRun())
		{
			return false;
		}

		$this->loadMedia();
	}

	private function canRun()
	{
		if (!$this->plugin_alias && !$this->plugin_name && !$this->plugin_version && !$this->valid_pages)
		{
			return;
		}
		
		global $pagenow;
		if (!$pagenow || $pagenow != 'admin.php')
		{
			return false;
		}


		$page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if (!in_array($page, $this->valid_pages))
		{
			return false;
		}
		
		return true;
	}

	private function registerAjax()
	{
		add_action('wp_ajax_fpf_show_update_notice', [$this, 'fpf_show_update_notice']);
	}

	public function fpf_show_update_notice()
	{
        // verify nonce
		$nonce = isset($_GET['nonce']) ? sanitize_text_field($_GET['nonce']) : '';
        if (!$verify = wp_verify_nonce($nonce, 'fpf_js_nonce'))
        {
			return;
		}
		
		$plugin_name = isset($_GET['plugin_name']) ? sanitize_text_field($_GET['plugin_name']) : '';
		if (!isset($plugin_name))
		{
			return;
		}
		
		$plugin_alias = isset($_GET['plugin_alias']) ? sanitize_text_field($_GET['plugin_alias']) : '';
		if (!isset($plugin_alias))
		{
			return;
		}
		
		$plugin_version = isset($_GET['plugin_version']) ? sanitize_text_field($_GET['plugin_version']) : '';
		if (!isset($plugin_version))
		{
			return;
		}
		
		$url = FPF_GET_LICENSE_VERSION_URL . $plugin_alias;

		$response = wp_remote_get($url);

		if (!is_array($response))
		{
			return;
		}

		$response_decoded = null;

		try
		{
			$response_decoded = json_decode($response['body']);
		}
		catch (Exception $ex)
		{
			return;
		}

		if (!isset($response_decoded->version))
		{
			return;
		}

		$new_version = $response_decoded->version;

		$installed_version = $plugin_version;

		if (!version_compare($installed_version, $new_version, '<'))
		{
			return;
		}

		$last_updated = !empty($response_decoded->last_updated) ? gmdate('j M Y', strtotime($response_decoded->last_updated)) : '';
		
		// load update notice
		$notice = fpframework()->renderer->render('admin/ui/update_notice', [
			'plugin_name' => $plugin_name,
			'plugin_alias' => $plugin_alias,
			'current_version' => $plugin_version,
			'version' => $new_version,
			'last_updated' => $last_updated
		], true);
		
		echo wp_json_encode([
			'html' => $notice
		]);
		wp_die();
	}

	private function loadMedia()
	{
		// load update notice css
		wp_register_style(
			'fpf-update-notice',
			FPF_MEDIA_URL . 'admin/css/fpf_update_notice.css',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_style( 'fpf-update-notice' );

		// load update notice js
		wp_register_script(
			'fpf-update-notice',
			FPF_MEDIA_URL . 'admin/js/fpf_update_notice.js',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_script( 'fpf-update-notice' );

		$data = array(
			'plugin_name' => $this->plugin_name,
			'plugin_alias' => $this->plugin_alias,
			'plugin_version' => $this->plugin_version,
		);
		wp_localize_script('fpf-update-notice', 'fpf_update_notice_js_object', $data);
	}
}