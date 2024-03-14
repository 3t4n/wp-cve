<?php

namespace Dropp;

class Cost_Tier
{
	public function __construct(
		public float $weightLimit,
		public string $suffix,
		public float $placeholder
	)
	{
	}

	public function getKey(int $i): string
	{
		$baseKey = 'cost';
		if ($i === 0) {
			return $baseKey;
		}

		return $baseKey . '_' . $i;
	}
}
