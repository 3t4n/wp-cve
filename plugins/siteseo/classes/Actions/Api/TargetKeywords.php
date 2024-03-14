<?php

namespace SiteSEO\Actions\Api;

if (! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Core\Hooks\ExecuteHooks;

class TargetKeywords implements ExecuteHooks
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
		register_rest_route('siteseo/v1', '/posts/(?P<id>\d+)/target-keywords', [
			'methods'			 => 'GET',
			'callback'			=> [$this, 'processGet'],
			'args'				=> [
				'id' => [
					'validate_callback' => function ($param, $request, $key) {
						return is_numeric($param);
					},
				],
			],
			'permission_callback' => '__return_true',
		]);

		register_rest_route('siteseo/v1', '/posts/(?P<id>\d+)/target-keywords', [
			'methods'			 => 'PUT',
			'callback'			=> [$this, 'processPut'],
			'args'				=> [
				'id' => [
					'validate_callback' => function ($param, $request, $key) {
						return is_numeric($param);
					},
				],
			],
			'permission_callback' => function ($request) {
				$nonce = $request->get_header('x-wp-nonce');
				if (! wp_verify_nonce($nonce, 'wp_rest')) {
					return false;
				}

				if(!current_user_can('edit_posts')){
					return false;
				}

				return true;
			},
		]);
	}

	/**
	 * @since 5.0.0
	 */
	public function processGet(\WP_REST_Request $request)
	{
		$id	 = $request->get_param('id');
		$targetKeywords   =  array_filter(explode(',', strtolower(get_post_meta($id, '_siteseo_analysis_target_kw', true))));

		$data = siteseo_get_service('CountTargetKeywordsUse')->getCountByKeywords($targetKeywords, $id);

		return new \WP_REST_Response([
			'value' => $targetKeywords,
			'usage' => $data
		]);
	}

	/**
	 * @since 5.0.0
	 */
	public function processPut(\WP_REST_Request $request)
	{
		$id	 = $request->get_param('id');
		$params = $request->get_params();
		if (!isset($params['_siteseo_analysis_target_kw'])) {
			return new \WP_REST_Response([
				'code'		 => 'error',
				'code_message' => 'missed_parameters',
			], 403);
		}

		try {
			$targetKeywords = implode(',',array_map('trim', explode(',',$params['_siteseo_analysis_target_kw'])));
			update_post_meta($id, '_siteseo_analysis_target_kw', $targetKeywords);

			return new \WP_REST_Response([
				'code' => 'success',
			]);
		} catch (\Exception $e) {
			return new \WP_REST_Response([
				'code'		 => 'error',
				'code_message' => 'execution_failed',
			], 403);
		}
	}
}
