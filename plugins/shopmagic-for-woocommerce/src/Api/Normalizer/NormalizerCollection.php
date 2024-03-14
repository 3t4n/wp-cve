<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Normalizer;

/**
 * @implements Normalizer<object>
 */
class NormalizerCollection implements Normalizer {

	/** @var Normalizer[] */
	private $normalizers;

	public function __construct( Normalizer ...$normalizers ) {
		$this->normalizers = $normalizers;
	}

	public function normalize( object $object ): array {
		foreach ( $this->normalizers as $normalizer ) {
			if ( ! $normalizer->supports_normalization( $object ) ) {
				continue;
			}

			return $normalizer->normalize( $object );
		}

		throw new \LogicException( sprintf( 'No normalizer could handle object %s', get_class( $object ) ) );
	}

	public function supports_normalization( object $object ): bool {
		return true;
	}

}
