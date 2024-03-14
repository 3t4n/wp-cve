<?php
final class ChaportAppId {
	/** @var string Raw App ID */
	private $app_id;

	private function __construct($app_id) {
		$this->app_id = $app_id;
	}

	public function __toString() {
		return $this->app_id;
	}

	/** Constructs new App ID instance from given string */
	public static function fromString($maybeAppId) {
		if (!self::isValid($maybeAppId)) {
			throw new Exception('Invalid Chaport App ID');
		}
		return new self($maybeAppId);
	}

	/** Checks if string is a valid Chaport App ID */
	public static function isValid($maybeAppId) {
		return !!preg_match('/^[a-f\d]{24}$/i', $maybeAppId);
	}
}