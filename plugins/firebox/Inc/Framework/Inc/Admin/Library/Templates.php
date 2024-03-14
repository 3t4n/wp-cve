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

namespace FPFramework\Admin\Library;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use \FPFramework\Helpers\Templates as TemplatesHelper;

trait Templates
{
	public function templates_init()
	{
		// Get Templates Layout AJAX
		add_action('wp_ajax_fpf_library_get_templates', [$this, 'fpf_library_get_templates']);
		
		// Refresh Templates from Remote and return Layout - AJAX
		add_action('wp_ajax_fpf_library_refresh_templates', [$this, 'fpf_library_refresh_templates']);
		
		// Insert template
		add_action('wp_ajax_fpf_library_insert_template', [$this, 'fpf_library_insert_template']);
	}

	/**
	 * Checks whether we have the template locally and retrives its layout.
	 * If no local template is found, then retrieves it from remote and returns its layout.
	 * 
	 * @return  string
	 */
	public function fpf_library_get_templates()
	{
		if (!current_user_can('manage_options'))
		{
			return false;
		}
		
		$nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
		
        // verify nonce
        if (!$verify = wp_verify_nonce($nonce, 'fpf_js_nonce'))
        {
			return false;
		}

		$this->getTemplates($this->getList());
	}

	/**
	 * Returns all available templates.
	 * 
	 * @param   object  $templates
	 * 
	 * @return  void
	 */
	private function getTemplates($templates)
	{
		if (isset($templates->code) || \is_wp_error($templates))
		{
			echo wp_json_encode($templates);
			wp_die();
		}

		$layout_payload = [
			'plugin' => $this->library_settings['plugin'],
			'plugin_name' => $this->library_settings['plugin_name'],
			'plugin_license_type' => $this->library_settings['plugin_license_type'],
			'plugin_version' => $this->library_settings['plugin_version'],
			'plugin_license_settings_url' => $this->library_settings['plugin_license_settings_url'],
			'template_use_url' => $this->library_settings['template_use_url'],
			'license_key' => $this->library_settings['license_key'],
			'license_key_status' => $this->library_settings['license_key_status'],
			'templates' => isset($templates->templates) ? $templates->templates : [],
			'favorites' => $this->getFavorites()
		];

		$filters_payload = [
			'filters' => $this->getTemplatesFilters($templates->filters)
		];

		echo wp_json_encode([
			'templates' => fpframework()->renderer->admin->render('library/items_list', $layout_payload, true),
			'filters' => fpframework()->renderer->admin->render('library/filters', $filters_payload, true)
		]);
		wp_die();
	}

	/**
	 * Returns the filters payload.
	 * 
	 * @param   object  $filters
	 * 
	 * @return  array
	 */
	private function getTemplatesFilters($filters)
	{
		// Main filters
		$data = [
			'solution' => [
				'label' => fpframework()->_('FPF_SOLUTIONS'),
				'items' => isset($filters->solutions) ? $filters->solutions : [],
			],
			'events' => [
				'label' => fpframework()->_('FPF_EVENTS'),
				'items' => isset($filters->events) ? $filters->events : [],
			],
			'category' => [
				'label' => isset($this->library_settings['main_category_label']) ? $this->library_settings['main_category_label'] : fpframework()->_('FPF_CATEGORIES'),
				'items' => isset($filters->categories) ? $filters->categories : [],
			]
		];

		// Add compatibility filter (Free/Pro filtering) only in the Lite version
		if ($this->library_settings['plugin_license_type'] === 'lite')
		{
			$data['compatibility'] = [
				'label' => fpframework()->_('FPF_COMPATIBILITY'),
				'items' => isset($filters->compatibility) ? $filters->compatibility : []
			];
		}

		return $data;
	}

	/**
	 * Retrieve remote templates, store them locally and return new layout.
	 * 
	 * @return  string
	 */
	public function fpf_library_refresh_templates()
	{
		if (!current_user_can('manage_options'))
		{
			return false;
		}
		
		$nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
		
        // verify nonce
        if (!$verify = wp_verify_nonce($nonce, 'fpf_js_nonce'))
        {
			return false;
		}

		$this->getTemplates(TemplatesHelper::getRemoteTemplatesAndStore($this->library_settings['plugin']));
	}

	/**
	 * Insert template.
	 * 
	 * @return  void
	 */
	public function fpf_library_insert_template()
	{
		if (!current_user_can('manage_options'))
		{
			return false;
		}
		
		$nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
		
        // verify nonce
        if (!$verify = wp_verify_nonce($nonce, 'fpf_js_nonce'))
        {
			return false;
		}
		
		$template = isset($_POST['template']) ? sanitize_text_field($_POST['template']) : '';
		
		$license = get_option($this->library_settings['plugin'] . '_license_key');
		$site_url = preg_replace('(^https?://)', '', get_site_url());
		$site_url = preg_replace('(^www.)', '', $site_url);
        $site_url = rtrim($site_url, '/') . '/';
        
        // get remote templates
		$templates_url = str_replace('{{PLUGIN}}', $this->library_settings['plugin'], FPF_TEMPLATE_GET_URL);
		$templates_url = str_replace('{{TEMPLATE}}', $template, $templates_url);
		$templates_url = str_replace('{{LICENSE_KEY}}', $license, $templates_url);
		$templates_url = str_replace('{{SITE_URL}}', $site_url, $templates_url);

        $response = wp_remote_get($templates_url);

        if (!is_array($response) || \is_wp_error($response))
        {
			echo wp_json_encode([
				'error' => true,
				'message' => 'Cannot insert template. Please try again.'
			]);
			wp_die();
        }
        else
        {
            $body = json_decode($response['body']);

            // an error has occurred
            if (isset($body->error))
            {
				echo wp_json_encode([
					'error' => true,
					'message' => $body->message
				]);
				wp_die();
            }

			// Save template locally so we can fetch its contents on redirect
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents
			file_put_contents($this->getTemplatesPath() . '/template.json', wp_json_encode($body));
            
			echo wp_json_encode([
				'error' => false,
				'message' => 'Inserting template.',
				'redirect' => $this->library_settings['template_use_url'] . $template
			]);
			wp_die();
        }
	}

    /**
     * Returns the local templates
     * 
     * @return  array
     */
	private function getLocalTemplates()
	{
        $path = $this->getTemplatesPath() . '/templates.json';

		if (!file_exists($path))
		{
			return false;
        }

		// If templates are old, fetch remote list
		if (TemplatesHelper::requireUpdate($path))
		{
			return false;
		}

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		return json_decode(file_get_contents($path));
	}

    /**
     * Get templates list
     * 
     * @return  array
     */
    public function getList()
    {
		// try to find local templates with fallback remote templates
        return $this->getLocalTemplates() ?: TemplatesHelper::getRemoteTemplatesAndStore($this->library_settings['plugin']);
    }
}