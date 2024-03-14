<?php

namespace WilokeEmailCreator\DataFactory\Shared;

use WilokeEmailCreator\Illuminate\Message\MessageFactory;
use WP_Post;
use WP_Query;

trait TraitHandlePosts
{
	public function getPosts($aRawArgs)
	{
		$aArgs = wp_parse_args($aRawArgs, [
			'post_status' => 'publish',
			'limit'       => 50,
			'post_type'   => ['post'],
			'order'       => 'ASC',
			'orderby'     => 'title'
		]);
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
			$id = $oQuery->post->ID;
			$aDataCategories = get_the_category($id);
			$aCategory = [];
			if (!empty($aDataCategories)) {
				foreach ($aDataCategories as $aItemCategory) {
					$aCategory[] = $aItemCategory->name;
				}
			}
			$aItems[] = [
				'id'          => (string)$id,
				'title'       => $oQuery->post->post_title,
				'handle'      => $oQuery->post->post_name,
				'description' => $oQuery->post->post_content,
				'link'        => get_permalink($id),
				'image'       => get_the_post_thumbnail_url($id),
				'categories'    => $aCategory,
				'createDate'  => date_format(date_create($oQuery->post->post_date), 'Y-m-d'),
				'updatedAt'   => date_format(date_create($oQuery->post->post_modified), 'Y-m-d')
			];
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
}
