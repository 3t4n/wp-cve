<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\Controllers;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Dashboard extends BaseController
{
	/**
	 * Render view
	 * 
	 * @return  void
	 */
	public function render()
	{
		firebox()->renderer->admin->render('pages/dashboard');
	}

	/**
	 * Load required media files
	 * 
	 * @return void
	 */
	public function addMedia()
	{
		wp_register_script('firebox-react', 'https://unpkg.com/react@18/umd/react.production.min.js', [], FBOX_VERSION, true);
		wp_enqueue_script('firebox-react');
		wp_register_script('firebox-react-dom', 'https://unpkg.com/react-dom@18/umd/react-dom.production.min.js', [], FBOX_VERSION, true);
		wp_enqueue_script('firebox-react-dom');

		// load dashboard
		wp_register_script(
			'firebox-dashboard',
			FBOX_MEDIA_ADMIN_URL . 'js/dashboard.js',
			['wp-api-fetch'],
			FBOX_VERSION,
			true
		);
		wp_enqueue_script('firebox-dashboard');

		// load geoip js
		wp_register_script(
			'fpf-geoip',
			FPF_MEDIA_URL . 'admin/js/fpf_geoip.js',
			[],
			FPF_VERSION,
			true
		);
		wp_enqueue_script('fpf-geoip');
	}
}