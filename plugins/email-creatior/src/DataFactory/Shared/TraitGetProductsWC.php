<?php

namespace WilokeEmailCreator\DataFactory\Shared;

use WP_Query;

trait TraitGetProductsWC
{
	/**
	 * @param $aArgs
	 *'tax_query'      => [
	 * [
	 * 'taxonomy' => 'product_cat',
	 * 'field'    => 'term_id',
	 * 'terms'    => 26,
	 * 'operator' => 'IN'
	 * ]
	 * ]
	 */
	public function getProductWithCatID($aArgs): array
	{
		$aProducts = [];
		$aArgs = wp_parse_args($aArgs, [
			'post_type'      => 'product',
			'posts_per_page' => 10,
			'post_status'    => 'publish'
		]);

		$oQuery = new WP_Query($aArgs);
		if ($oQuery->have_posts()) :
			while ($oQuery->have_posts()) : $oQuery->the_post();
				$productId = get_the_ID();
				$aCategories = get_the_terms($productId, 'product_cat');
				$aDataCategories = [];
				if (!empty($aCategories)) {
					foreach ($aCategories as $aItem) {
						$aDataCategories[$aItem->term_id] = $aItem->name;
					}
				}

				$oProduct = wc_get_product($productId);
				$price = (float)$oProduct->get_price();
				$compareAtPrice = (float)$oProduct->get_sale_price();

				$aProducts[] = [
					'id'             => $productId,
					'image'          => get_the_post_thumbnail_url($productId),
					'title'          => get_the_title($productId),
					'price'          => wc_price($price),
					'compareAtPrice' => wc_price($compareAtPrice),
					'description'    => get_the_content($productId),
					'createdAt'      => get_the_date('Y-m-d', $productId),
					'updatedAt'      => get_the_modified_date('Y-m-d', $productId),
					'categories'     => array_values($aDataCategories),
					'link'           => get_permalink($productId),
				];
			endwhile;
		endif;
		wp_reset_postdata();
		return $aProducts;
	}

	public function getListProductBestseller(): array
	{
		global $wpdb;
		$aPostIds = [];
		$sql
			= "SELECT post_id FROM {$wpdb->postmeta} as post_meta WHERE post_meta.meta_key = 'total_sales' AND post_meta.meta_value > 0 ORDER BY post_meta.meta_value DESC";
		$aResponse = $wpdb->get_results($sql, ARRAY_A);
		if (!empty($aResponse)) {
			foreach ($aResponse as $aItem) {
				$aPostIds[] = (int)$aItem['post_id'];
			}
		}
		return $aPostIds;
	}
}
