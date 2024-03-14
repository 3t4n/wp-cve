<?php
use Monolog\Handler\AbstractHandler;

/**
 * Class WC_BPost_Shipping_Logger_Handler allows WordPress to log through monolog
 */
class WC_BPost_Shipping_Logger_Handler extends AbstractHandler {
	/** @var WC_Logger */
	private $logger;

	/**
	 * Handler constructor.
	 *
	 * @param WC_Logger $logger
	 */
	public function __construct( WC_Logger $logger ) {
		parent::__construct();
		$this->logger = $logger;
	}


	/**
	 * {@inheritDoc}
	 */
	public function handle( array $record ): bool {
		if ( ! $this->isHandling( $record ) ) {
			return false;
		}

		$this->logger->add(
			$record['channel'],
			'[' . $record['level_name'] . '] ' . $record['message'] . ': ' . json_encode( $record['context'] )
		);

		return false === $this->bubble;
	}
}
