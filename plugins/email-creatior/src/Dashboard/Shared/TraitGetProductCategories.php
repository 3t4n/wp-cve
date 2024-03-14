<?php

namespace WilokeEmailCreator\Dashboard\Shared;

trait TraitGetProductCategories
{
	public function getProductCategories()
	{
		$aData = [];
		$aArgs = [
			'taxonomy'     => 'product_cat',
			'orderby'      => 'name',
			'pad_counts'   => false,
			'hierarchical' => 1,
			'hide_empty'   => false
		];
		$aCategories = get_categories($aArgs);
		if (!empty($aCategories)) {
			foreach ($aCategories as $items) {
				$aData[] = [
					'label' => $items->name,
					'value' => $items->term_id,
				];
			}
		}
		return $aData;
	}
}
