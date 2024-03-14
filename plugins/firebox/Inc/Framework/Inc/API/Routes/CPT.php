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

namespace FPFramework\API\Routes;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use WP_REST_Server;
use \FPFramework\API\EndpointController;

class CPT extends EndpointController
{
	/**
	 * Endpoint name
	 * 
	 * @return  string
	 */
	public function get_name()
	{
		return 'cpt';
	}

	/**
	 * Register routes
	 * 
	 * @return  void
	 */
	public function register()
	{
		$base_endpoint = '(?:\/(?P<ID>\d+))?';

		// update post status
		$this->register_route($base_endpoint . '/draft', WP_REST_Server::CREATABLE, [$this, 'update_post_status_draft']);
		$this->register_route($base_endpoint . '/publish', WP_REST_Server::CREATABLE, [$this, 'update_post_status_publish']);
	}

	/**
	 * Permissions callback to validate request
	 * 
	 * @param   object   $request
	 * 
	 * @return  boolean
	 */
	public function get_permission_callback($request)
	{
		if (!$request->get_param('ID'))
		{
			return false;
		}

		if (is_null(get_post($request->get_param('ID'))))
		{
			return false;
		}

		if (!current_user_can('manage_options'))
		{
			return false;
		}

		return true;
	}

	/**
	 * Set Post Status to Draft
	 * 
	 * @param   object  $request
	 * 
	 * @return  string
	 */
	public function update_post_status_draft($request)
	{
		$post_id = $request->get_param('ID');

		$post = ['ID' => $post_id, 'post_status' => 'draft'];
		wp_update_post($post);

		wp_send_json_success([
			'message' => 'Post status updated to Draft',
			'post_status' => 'draft',
			'post_id' => $post_id
		]);
	}

	/**
	 * Set Post Status to Published
	 * 
	 * @param   object  $request
	 * 
	 * @return  string
	 */
	public function update_post_status_publish($request)
	{
		$post_id = $request->get_param('ID');

		$post = ['ID' => $post_id, 'post_status' => 'publish'];
		wp_update_post($post);

		wp_send_json_success([
			'message' => 'Post status updated to Publish',
			'post_status' => 'publish',
			'post_id' => $post_id
		]);
	}
}