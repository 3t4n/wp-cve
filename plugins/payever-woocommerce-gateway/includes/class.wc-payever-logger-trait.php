<?php

if ( ! defined( 'ABSPATH' ) || trait_exists( 'WC_Payever_Logger_Trait' ) ) {
	return;
}

use Psr\Log\LoggerInterface;

trait WC_Payever_Logger_Trait {

	/** @var LoggerInterface */
	private $logger;

	/**
	 * @param LoggerInterface $logger
	 * @return $this
	 * @internal
	 */
	public function set_logger( LoggerInterface $logger ) {
		$this->logger = $logger;

		return $this;
	}

	/**
	 * @return LoggerInterface
	 * @codeCoverageIgnore
	 */
	protected function get_logger() {
		return null === $this->logger
			? $this->logger = WC_Payever_Api::get_instance()->get_logger()
			: $this->logger;
	}
}
