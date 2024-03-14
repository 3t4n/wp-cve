<?php
declare(strict_types=1);

namespace TreBiMeteo\Http\Message;

use Fig\Http\Message\StatusCodeInterface;

final class ResponseFactory implements ResponseFactoryInterface, StatusCodeInterface {

	/**
	 * @inheritDoc
	 */
	public function createResponse( int $code = self::STATUS_OK, string $reasonPhrase = '' ): \WP_REST_Response {

		if ( $code === 200 ) {
			$reasonPhrase = 'OK';
		}

		return new \WP_REST_Response(
			$reasonPhrase,
			$code
		);
	}
}
