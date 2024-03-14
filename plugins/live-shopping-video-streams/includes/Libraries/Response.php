<?php namespace Includes\Libraries;

use ReflectionClass;
use Includes\Libraries\Exceptions;

class Response {

	protected $statusCode;
	protected $headers;
	protected $body;

	protected $errorToExceptionMap = array(
		'400' => Exceptions\ValidationFailException::class,
		'401' => Exceptions\UnauthorizedException::class,
		'403' => Exceptions\ForbiddenException::class,
		'404' => Exceptions\NoRecordFoundException::class,
		'500' => Exceptions\InternalServerException::class,
	);

	public function __construct( $statusCode, $headers, $body ) {
		$this->statusCode = $statusCode;
		$this->headers    = $headers;
		$this->body       = $body;
	}

	public function getHeader() {
		return $this->headers;
	}

	public function getBody() {
		return $this->body;
	}

	public function assertValidResponse() {
		if ( empty( $this->body ) ) {
			return;
		}

		// Successful response code
		if ( $this->statusCode >= 200 && $this->statusCode < 300 ) {
			return;
		}

		$body = json_decode( $this->body, true );

		$this->unmappedErrorHandler();
		$exceptionClassName = $this->errorToExceptionMap[ $this->statusCode ];
		$refClass           = new ReflectionClass( $exceptionClassName );
		$error              = ! empty( $body['error'] ) ? $body['error'] : array();
		throw $refClass->newInstance( $error['message'], $error );
	}

	private function unmappedErrorHandler() {
		if ( isset( $this->errorToExceptionMap[ $this->statusCode ] ) === true ) {
			return;
		}

		throw new Exceptions\BaseException( 'Unrecognized status code: ' . $this->statusCode );
	}
}
