<?php
declare(strict_types=1);

namespace TreBiMeteo;

final class IFrameBuilder {

	/**
	 * @var array<string, mixed>
	 */
	private $attr;

	/**
	 * @param array<string, string> $attr
	 */
	public function __construct( array $attr ) {
		$this->attr = $attr;
	}

	public function render(): string {
		return \sprintf(
			'<iframe %s></iframe>',
			\rtrim( $this->buildAttributes() )
		);
	}

	public function __toString() {
		return $this->render();
	}

	/**
	 * @return string
	 */
	private function buildAttributes(): string {

		$string = '';

		/**
		 * @var null|string $attribute
		 */
		foreach ( $this->attr as $key => $attribute ) {
			if ( $attribute === null ) {
				continue;
			}

			/**
			 * @psalm-suppress MixedArgument
			 */
			$string .= \sprintf(
				'%s="%s" ',
				\esc_html( $key ),
				( 'src' === $key ) ? \esc_url_raw( $attribute ) : \esc_attr( $attribute )
			);
		}
		return $string;
	}
}
