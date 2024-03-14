<?php

namespace SiteSEO\Actions\Api;

if (! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Core\Hooks\ExecuteHooks;

class GetPost implements ExecuteHooks
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
		register_rest_route('siteseo/v1', '/posts/(?P<id>\d+)', [
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

		register_rest_route('siteseo/v1', '/posts/by-url', [
			'methods'			 => 'GET',
			'callback'			=> [$this, 'processGetByUrl'],
			'permission_callback' => '__return_true',
		]);
	}

	/**
	 * @since 5.0.0
	 * @param int $id
	 * @return array
	 */
	protected function getData($id){
		$context = siteseo_get_service('ContextPage')->buildContextWithCurrentId($id)->getContext();

		$title = siteseo_get_service('TitleMeta')->getValue($context);
		$description = siteseo_get_service('DescriptionMeta')->getValue($context);
		$social = siteseo_get_service('SocialMeta')->getValue($context);
		$robots = siteseo_get_service('RobotMeta')->getValue($context);
		$redirections = siteseo_get_service('RedirectionMeta')->getValue($context);

		$canonical =  '';
		if(isset($robots['canonical'])){
			$canonical = $robots['canonical'];
			unset($robots['canonical']);
		}

		$primarycat =  '';
		if(isset($robots['primarycat'])){
			$primarycat = $robots['primarycat'];
			unset($robots['primarycat']);
		}

		$breadcrumbs =  '';
		if(isset($robots['breadcrumbs'])){
			$breadcrumbs = $robots['breadcrumbs'];
			unset($robots['breadcrumbs']);
		}

		$data = [
			"title" => $title,
			"description" => $description,
			"canonical" => $canonical,
			"og" => $social['og'],
			"twitter" => $social['twitter'],
			"robots" => $robots,
			"primarycat" => $primarycat,
			"breadcrumbs" => $breadcrumbs,
			"redirections" => $redirections
		];

		return apply_filters('siteseo_headless_get_post', $data, $id, $context);

	}

	/**
	 * @since 5.0.0
	 *
	 * @param \WP_REST_Request $request
	 */
	public function processGet(\WP_REST_Request $request)
	{
		$id	 = $request->get_param('id');
		$data = $this->getData($id);

		return new \WP_REST_Response($data);
	}
	/**
	 * @since 5.0.0
	 *
	 * @param \WP_REST_Request $request
	 */
	public function processGetByUrl(\WP_REST_Request $request)
	{
		$url	 = $request->get_param('url');

		if(empty($url) || !$url){
			return new \WP_Error("missing_parameters", "Need an URL");
		}

		try {
			$id = apply_filters('siteseo_headless_url_to_postid', url_to_postid($url), $request);
			if(!$id){
				return new \WP_Error("not_found", "ID for URL not found");
			}

			$data = $this->getData($id);

			return new \WP_REST_Response($data);
		} catch (\Exception $e) {
			return new \WP_Error("unknow");
		}
	}


}
