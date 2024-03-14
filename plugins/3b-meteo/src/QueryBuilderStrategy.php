<?php
declare(strict_types=1);

namespace TreBiMeteo;

interface QueryBuilderStrategy {

	/**
	 * @param array<int|string, mixed> $new_atts
	 * @return string
	 */
	public function build( array $new_atts ): string;
}
