<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Routing;

class HttpProblemException extends \RuntimeException implements \WPDesk\ShopMagic\Exception\ShopMagicException {

	/**
	 * @var array{
	 *     title: string,
	 *     detail?: string
	 * }
	 */
	private $problem;

	/**
	 * @param array{
	 *     title: string,
	 *     detail?: string
	 * } $problem
	 * @param                 $code
	 * @param \Throwable|null $previous
	 */
	public function __construct( array $problem, $code = \WP_Http::BAD_REQUEST, ?\Throwable $previous = null ) {
		if ( ! isset( $problem['title'] ) ) { // @phpstan-ignore-line We have to manually check if parameter meets requirement
			throw new \InvalidArgumentException( 'HTTP Problem exception is missing "title" in `$problem` parameter' );
		}
		parent::__construct( $problem['title'], $code, $previous );
		$this->problem = $problem;
	}

	public static function from_throwable( \Throwable $throwable ): self {
		return new self(
			[
				'title'  => 'HTTP Exception',
				'detail' => $throwable->getMessage(),
			],
			\WP_Http::INTERNAL_SERVER_ERROR,
			$throwable
		);
	}

	public function to_http_response(): \WP_HTTP_Response {
		return new \WP_HTTP_Response(
			array_merge( $this->problem, [ 'code' => $this->code ] ),
			$this->code,
			[ 'Content-Type' => 'application/problem+json' ]
		);
	}

}
