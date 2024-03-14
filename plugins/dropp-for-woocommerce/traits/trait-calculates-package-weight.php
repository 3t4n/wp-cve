<?php

namespace Dropp;

trait Calculates_Package_Weight
{
	public function calculate_package_weight(): float
	{
		$total_weight = 0;
		if (!empty($this->package)) {
			foreach ($this->package['contents'] as $item) {
				if (empty($item['data'])) {
					continue;
				}
				$total_weight += $item['quantity'] * wc_get_weight($item['data']->get_weight(), 'kg');
			}
		}
		return $total_weight;
	}
}
