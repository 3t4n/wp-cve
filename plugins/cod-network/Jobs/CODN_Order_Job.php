<?php

namespace CODNetwork\Jobs;

use CODNetwork\Services\CODN_Logger_Service;
use WP_Queue\Job;
use CODNetwork\Services\CODN_Order_Service;
use Throwable;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('CODN_Order_Job')) {
    class CODN_Order_Job extends Job
    {
        /** @var int */
        protected $orderId;

        /** @var CODN_Order_Service */
        protected $orderService;

        /** @var CODN_Logger_Service */
        protected $logger;

        /**
         * @param int $orderId
         */
        public function __construct(int $orderId)
        {
            $this->orderId = $orderId;
            $this->orderService = new CODN_Order_Service();
            $this->logger = new CODN_Logger_Service();
        }

        public function handle()
        {
            $this->logger->info('pushing new order is pending', ['order.id' => $this->orderId]);

            try {
                $this->orderService->push_new_order($this->orderId);
            } catch (Throwable $e) {
                $this->logger->error(
                    'something went wrong while pushing a new order from order job',
                    [
                        'order.id' => $this->orderId,
                        'extra.message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]
                );
            }
        }
    }
}

