<?php
declare(strict_types=1);

namespace TreBiMeteo;

final class PlainQueryBuilder implements QueryBuilderStrategy {

	use ParseArrayQueryTrait;

	/**
	 * @inheritDoc
	 * @psalm-suppress MixedArgumentTypeCoercion
	 */
	public function build( array $new_atts ): string {
		$this->addKeysContainHexValueForUrlBuilder( ['text_1','bg_1','text_2','bg_2'] );
		return \implode( '/', $this->parseArrayQuery( $new_atts ) );
	}
}
