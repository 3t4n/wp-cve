<?php
declare(strict_types=1);

namespace TreBiMeteo;

final class HttpQueryBuilder implements QueryBuilderStrategy {

	use ParseArrayQueryTrait;

	/**
	 * @inheritDoc
	 */
	public function build( array $new_atts ): string {
		$this->addKeysContainHexValueForUrlBuilder( ['c1','c2','c3','b1','b2','b3'] );
		$output = \http_build_query( $this->parseArrayQuery( $new_atts ) );

		if ( empty( $output ) ) {
			return '';
		}

		return '?' . $output;
	}
}
