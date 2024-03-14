<?php

namespace ZPOS\Structure;

trait AddDefaultImage
{
	public function add_default_images(\WP_REST_Response $response)
	{
		if (empty($response->data["images"])) {
			$response->data["images"] = [
				[
					'id' => 0,
					'date_created' => wc_rest_prepare_date_response(current_time('mysql'), false),
					'date_created_gmt' => wc_rest_prepare_date_response(current_time('timestamp', true)),
					'date_modified' => wc_rest_prepare_date_response(current_time('mysql'), false),
					'date_modified_gmt' => wc_rest_prepare_date_response(current_time('timestamp', true)),
					'src' => wc_placeholder_img_src(),
					'name' => __('Placeholder', 'woocommerce'),
					'alt' => __('Placeholder', 'woocommerce'),
					'position' => 0,
				]
			];
		}

		return $response;
	}

	public function add_default_image(\WP_REST_Response $response)
	{
		if (empty($response->data["image"])) {
			$response->data["image"] =
				[
					'id' => 0,
					'date_created' => wc_rest_prepare_date_response(current_time('mysql'), false),
					'date_created_gmt' => wc_rest_prepare_date_response(current_time('timestamp', true)),
					'date_modified' => wc_rest_prepare_date_response(current_time('mysql'), false),
					'date_modified_gmt' => wc_rest_prepare_date_response(current_time('timestamp', true)),
					'src' => wc_placeholder_img_src(),
					'name' => __('Placeholder', 'woocommerce'),
					'alt' => __('Placeholder', 'woocommerce'),
					'position' => 0
				];
		}

		return $response;
	}
}
