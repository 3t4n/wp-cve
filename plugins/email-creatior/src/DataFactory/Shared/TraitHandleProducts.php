<?php

namespace WilokeEmailCreator\DataFactory\Shared;

use WilokeEmailCreator\Illuminate\Message\MessageFactory;
use WP_Post;
use WP_Query;

trait TraitHandleProducts
{
	public function getProducts($aArgs)
	{
		$oQuery = new WP_Query($this->handleParseArgs($aArgs));
		if (!$oQuery->have_posts()) {
			wp_reset_postdata();
			return MessageFactory::factory()->success(
				esc_html__('We found no product', 'emailcreator'),
				[
					'items' => []
				]
			);
		}

		/**
		 * @var WP_Post $aCoupon
		 */

		while ($oQuery->have_posts()) {
			$oQuery->the_post();
			$productId = $oQuery->post->ID;

			$oProduct = wc_get_product($productId);
			$price = (float)$oProduct->get_price();
			$compareAtPrice = (float)$oProduct->get_sale_price();

			$aProduct = [
				'id'          => (string)$productId,
				'title'       => $oQuery->post->post_title,
				'description' => $oQuery->post->post_content,
				'handle'      => $oQuery->post->post_name,
				'link'        => get_permalink($productId),
				'image'       => get_the_post_thumbnail_url($productId),
				'price'       => wc_price($price),
				'createdAt'   => get_the_date('Y-m-d', $productId),
				'updatedAt'   => get_the_modified_date('Y-m-d', $productId),
			];
			if (!empty($compareAtPrice)) {
				$aProduct['compareAtPrice'] = wc_price($compareAtPrice);
			}
			$aItems[] = $aProduct;
		}
		$maxPages = $oQuery->max_num_pages;
		wp_reset_postdata();

		return MessageFactory::factory()->success(
			sprintf(esc_html__('We found %s items', 'emailcreator'), count($aItems)),
			[
				'items'    => $aItems,
				'maxPages' => $maxPages
			]
		);
	}

	public function handleParseArgs($aRawArgs)
	{
		$aArgs = wp_parse_args($aRawArgs, [
			'post_status' => 'publish',
			'limit'       => 50,
			'post_type'   => ['product'],
			'order'       => 'ASC',
			'orderby'     => 'title'
		]);

		if (isset($aArgs['page']) && $aArgs['page']) {
			$aArgs['paged'] = (int)$aArgs['page'];
		} else {
			$aArgs['paged'] = 1;
		}
		unset($aArgs['page']);
		if (isset($aArgs['notInIds']) && !empty($aArgs['notInIds'])) {
			$aArgs['post__not_in'] = $aArgs['notInIds'];
			unset($aArgs['notInIds']);
		}
		if (!empty($aArgs['limit'])) {
			$aArgs['posts_per_page'] = $aArgs['limit'];
			unset($aArgs['limit']);
		}

		if (isset($aArgs['s']) && !empty($aArgs['s'])) {
			$aArgs['sentence'] = true;
		} else {
			unset($aArgs['s']);
		}
		return $aArgs;
	}
}
