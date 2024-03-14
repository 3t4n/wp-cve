<?php
declare(strict_types=1);

namespace TreBiMeteo\Http\Message;

final class OutputSchemaForResponseHelper {

	const CODE = 'code';

	const MESSAGE = 'message';

	const DATA = 'data';

	const ERROR = 'error';

	const ERROR_MESSAGE = 'error_message';

	/**
	 * @var int|string $code
	 */
	private $code;

	/**
	 * @var mixed $message
	 */
	private $message;

	/**
	 * @var mixed
	 */
	private $data;

	/**
	 * @param int|string $code
	 * @return $this
	 */
	public function setCode( $code ): OutputSchemaForResponseHelper {
		$this->code = $code;
		return $this;
	}

	/**
	 * @param mixed $message
	 * @return $this
	 */
	public function setMessage( $message ): OutputSchemaForResponseHelper {
		$this->message = $message;
		return $this;
	}

	/**
	 * @param mixed $data
	 * @return $this
	 */
	public function setData( $data = null ): OutputSchemaForResponseHelper {
		$this->data = $data;
		return $this;
	}

	/**
	 * @return array<string, mixed>
	 */
	public function toArray(): array {
		if ( ! $this->code ) {
			throw new \InvalidArgumentException( 'The "code" must be provided.' );
		}

		if ( \is_null( $this->message ) ) {
			throw new \InvalidArgumentException( 'The "message" must be provided.' );
		}

		$code = $this->code;
		$message = $this->message;
		$data = $this->data;

		unset( $this->code, $this->message, $this->data );

		$output = [
			self::CODE		=> $code,
			self::MESSAGE	=> $message,
			self::DATA		=> $data,
		];

		return \array_filter( $output );
	}

	/**
	 * @param int|string $code
	 * @param mixed $message
	 * @param mixed $data
	 * @return array<string, mixed>
	 */
	public function __invoke( $code, $message, $data = null ): array {
		$this->setCode($code);
		$this->setMessage($message);
		$this->setData($data);

		return $this->toArray();
	}
}
