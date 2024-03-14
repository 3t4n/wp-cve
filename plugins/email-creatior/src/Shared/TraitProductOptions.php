<?php

namespace WilokeEmailCreator\Shared;

trait TraitProductOptions
{
	public function getProductOptions(): array
	{
		return [
			[
				'label' => esc_html__('Newest', 'emailcreator'),
				'value' => 'newest'
			],
			[
				'label' => esc_html__('Related', 'emailcreator'),
				'value' => 'related'
			],
			[
				'label' => esc_html__('Category', 'emailcreator'),
				'value' => 'category'
			],
			[
				'label' => esc_html__('On sale', 'emailcreator'),
				'value' => 'onSale'
			],
			[
				'label' => esc_html__('Featured', 'emailcreator'),
				'value' => 'featured'
			],
			[
				'label' => esc_html__('Up sell', 'emailcreator'),
				'value' => 'upSell'
			],
			[
				'label' => esc_html__('Cross sell', 'emailcreator'),
				'value' => 'crossSell'
			],
			[
				'label' => esc_html__('Best seller', 'emailcreator'),
				'value' => 'bestSeller'
			]
		];
	}
}
