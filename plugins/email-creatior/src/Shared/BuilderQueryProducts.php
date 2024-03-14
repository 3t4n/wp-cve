<?php

namespace WilokeEmailCreator\Shared;

use Exception;
use WilokeEmailCreator\DataFactory\Shared\TraitGetProductsWC;

class BuilderQueryProducts
{
	use TraitGetProductsWC;

	private static ?BuilderQueryProducts $oSelf    = null;
	public array                         $aRawData = [];
	public string                        $type     = '';

	public static function init(): ?BuilderQueryProducts
	{
		if (self::$oSelf == null) {
			self::$oSelf = new self();
		}
		return self::$oSelf;
	}

	public function setRawData(array $aRawData): BuilderQueryProducts
	{
		$this->aRawData = $aRawData;
		return $this;
	}

	public function setTypeProduct(string $type): BuilderQueryProducts
	{
		$this->type = $type;
		return $this;
	}

	public function query()
	{
		$aData = [];
		$method = 'get' . ucfirst($this->type);
		if (method_exists($this, $method)) {
			$aData = call_user_func_array([$this, $method], []);
		} else {
			error_log('method type product query is not exist');
		}
		return $aData;
	}

	public function getNewest(): array
	{
		$aData = [];
		$aRawData = $this->aRawData;
		if (!empty($aRawData['orderProducts'])) {
			$aProductIDs = [];
			foreach ($aRawData['orderProducts'] as $aItem) {
				$aProductIDs[] = $aItem['id'];
			}
			$aData = $this->getProductWithCatID([
				'posts_per_page' => count($aRawData['productPlaceholder']),
				'post__not_in'   => $aProductIDs,
				'orderby'        => 'date',
				'order'          => 'DESC'
			]);
		}
		return $aData;
	}

	public function getRelated(): array
	{
		$aData = [];
		$aRawData = $this->aRawData;
		if (!empty($aRawData['orderProducts'])) {
			$aTagsSlug = [];
			$aProductIDs = [];
			foreach ($aRawData['orderProducts'] as $aItem) {
				$aTagsSlug = array_merge($aTagsSlug, $aItem['tags']);
				$aProductIDs[] = $aItem['id'];
			}
			$aArgs = [
				'posts_per_page' => count($aRawData['productPlaceholder']),
				'post__not_in'   => $aProductIDs
			];
			if (!empty($aTagsSlug)) {
				$aArgs['tax_query'] = [
					[
						'taxonomy' => 'product_tag',
						'field'    => 'slug',
						'terms'    => array_unique($aTagsSlug),
						'operator' => 'IN',
					]
				];
			}
			$aData = $this->getProductWithCatID($aArgs);
		}
		return $aData;
	}

	public function getFeatured(): array
	{
		$aData = [];
		$aRawData = $this->aRawData;
		if (!empty($aRawData['orderProducts'])) {
			$aFeaturedIds = wc_get_featured_product_ids();
			$aProductIDs = [];
			foreach ($aRawData['orderProducts'] as $aItem) {
				$aProductIDs[] = $aItem['id'];
			}
			$aArgs = [
				'posts_per_page' => count($aRawData['productPlaceholder']),
			];
			if (!empty($aFeaturedIds)) {
				$aArgs['post__in'] = array_diff(array_unique(array_merge($aFeaturedIds,$aProductIDs)),$aProductIDs);
			}
			$aData = $this->getProductWithCatID($aArgs);
		}
		return $aData;
	}

	public function getCrossSell(): array
	{
		$aData = [];
		$aRawData = $this->aRawData;
		if (!empty($aRawData['orderProducts'])) {
			$aCrosssellIds = [];
			$aProductIDs = [];
			foreach ($aRawData['orderProducts'] as $aItem) {
				$aCrosssellIds = array_merge($aCrosssellIds, $aItem['crosssellIds']);
				$aProductIDs[] = $aItem['id'];
			}
			$aArgs = [
				'posts_per_page' => count($aRawData['productPlaceholder']),
				'post__not_in'   => $aProductIDs
			];
			if (!empty($aCrosssellIds)) {
				$aArgs['post__in'] = array_unique($aCrosssellIds);
			}
			$aData = $this->getProductWithCatID($aArgs);
		}
		return $aData;
	}

	public function getUpSell(): array
	{
		$aData = [];
		$aRawData = $this->aRawData;
		if (!empty($aRawData['orderProducts'])) {
			$aUpSellIDs = [];
			$aProductIDs = [];
			foreach ($aRawData['orderProducts'] as $aItem) {
				$aUpSellIDs = array_merge($aUpSellIDs, $aItem['upsellIds']);
				$aProductIDs[] = $aItem['id'];
			}
			$aArgs = [
				'posts_per_page' => count($aRawData['productPlaceholder']),
				'post__not_in'   => $aProductIDs
			];
			if (!empty($aUpSellIDs)) {
				$aArgs['post__in'] = array_unique($aUpSellIDs);
			}
			$aData = $this->getProductWithCatID($aArgs);
		}

		return $aData;
	}

	public function getBestSeller(): array
	{
		$aData = [];
		$aRawData = $this->aRawData;
		if (!empty($aRawData['orderProducts'])) {
			$aProductIDs = [];
			foreach ($aRawData['orderProducts'] as $aItem) {
				$aProductIDs[] = $aItem['id'];
			}
			$aProductBestsellerIDs = $this->getListProductBestseller();
			$aArgs =[
				'post__in' => array_diff(array_unique(array_merge($aProductBestsellerIDs,$aProductIDs)),$aProductIDs),
				'posts_per_page' => count($aRawData['productPlaceholder']),
			];
			$aData = $this->getProductWithCatID($aArgs);
		}
		return $aData;
	}

	public function getCategory(): array
	{
		$aData = [];
		$aRawData = $this->aRawData;
		if (!empty($aRawData['orderProducts'])) {
			$aCatIds = [];
			$aProductIDs = [];
			foreach ($aRawData['orderProducts'] as $aItem) {
				$aCatIds = array_merge($aCatIds, $aItem['catIds']);
				$aProductIDs[] = $aItem['id'];
			}
			$aData = $this->getProductWithCatID([
				'tax_query'      => [
					[
						'taxonomy' => 'product_cat',
						'field'    => 'term_id',
						'terms'    => $aCatIds,
						'operator' => 'IN'
					]
				],
				'posts_per_page' => count($aRawData['productPlaceholder']),
				'post__not_in'   => $aProductIDs
			]);
		}
		return $aData;
	}

	public function getSelect(): array
	{
		$aData = [];
		$aRawData = $this->aRawData;
		if (!empty($aRawData['productPlaceholder'])) {
			foreach ($aRawData['productPlaceholder'] as $aProduct) {
				$aData[] = $aProduct;
			}
		}
		return $aData;
	}

	public function getOnSale(): array
	{
		$aData = [];
		$aRawData = $this->aRawData;
		if (!empty($aRawData['orderProducts'])) {
			$aCatIds = [];
			$aProductIDs = [];
			foreach ($aRawData['orderProducts'] as $aItem) {
				$aCatIds = array_merge($aCatIds, $aItem['catIds']);
				$aProductIDs[] = $aItem['id'];
			}
			$aData = $this->getProductWithCatID([
				'meta_query'     => [
					'relation' => 'OR',
					[
						'key'     => '_sale_price',
						'value'   => 0,
						'compare' => '>',
						'type'    => 'numeric'
					],
					[
						'key'     => '_min_variation_sale_price',
						'value'   => 0,
						'compare' => '>',
						'type'    => 'numeric'
					]
				],
				'posts_per_page' => count($aRawData['productPlaceholder']),
				'post__not_in'   => $aProductIDs
			]);
		}
		return $aData;
	}
}
