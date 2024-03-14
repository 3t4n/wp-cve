<?php

declare(strict_types=1);

namespace WPDesk\ShopMagic\Api\Controller;

use ShopMagicVendor\Psr\Log\LoggerInterface;

class LogController {
    /** @var LoggerInterface */
    private $logger;

	public function __construct(
		LoggerInterface $logger
	) {
        $this->logger = $logger;
	}

	public function log(string $message, string $level = 'debug', array $context = []): \WP_REST_Response {
		$this->logger->log($level, $message, $context);
		return new \WP_REST_Response(null, \WP_Http::CREATED);
	}
}
