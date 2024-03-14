<?php

final class ChaportInstallationCode {
	/** @var string Raw Installation code */
	private $installation_code;

	private function __construct($installation_code) {
		$this->installation_code = $installation_code;
	}

	public function __toString() {
		return $this->installation_code;
	}

	/** Constructs new Installation code instance from given string */
	public static function fromString($maybeInstallationCode) {
		if (!self::isValid($maybeInstallationCode)) {
			throw new Exception('Invalid Chaport Installation Code');
		}
		return new self($maybeInstallationCode);
	}

	/** Checks if string is a valid Chaport Installation Code */
	public static function isValid($maybeInstallationCode) {
		if ((substr_count($maybeInstallationCode, '<script'))===(substr_count($maybeInstallationCode, '</script>'))){
			return true;
		}
	}
}