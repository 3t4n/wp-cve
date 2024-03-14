<?php

namespace SiteSEO\Actions\Api;

if (! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Core\Hooks\ExecuteHooks;
use SiteSEO\ManualHooks\ApiHeader;

class CountTargetKeywordsUse implements ExecuteHooks
{
	public function hooks()
	{
		add_action('rest_api_init', [$this, 'register']);
	}

	/**
	 * @since 5.0.0
	 *
	 * @return void
	 */
	public function register()
	{
		register_rest_route('siteseo/v1', '/posts/(?P<id>\d+)/count-target-keywords-use', [
			'methods'			 => 'GET',
			'callback'			=> [$this, 'get'],
			'args'				=> [
				'id' => [
					'validate_callback' => function ($param, $request, $key) {
						return is_numeric($param);
					},
				],
			],
			'permission_callback' => '__return_true',
		]);

	}

	/**
	 * @since 5.0.0
	 */
	public function get(\WP_REST_Request $request)
	{
		$apiHeader = new ApiHeader();
		$apiHeader->hooks();

		$id   = (int) $request->get_param('id');
		$targetKeywords   =  $request->get_param('keywords');

		$data = siteseo_get_service('CountTargetKeywordsUse')->getCountByKeywords($targetKeywords, $id);

		return new \WP_REST_Response($data);
	}



}
