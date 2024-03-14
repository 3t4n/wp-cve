<?php
declare(strict_types=1);

namespace TreBiMeteo;

interface UrlBuilderInterface {

	public function render(): string;

	/**
	 * @param array<string, string> $params
	 * @return UrlBuilder
	 */
	public function query( array $params ): UrlBuilder;
}
