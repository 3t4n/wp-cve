<?php

namespace CODNetwork\Models;

use CODNetwork\Repositories\CodNetworkRepository;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('CODN_Settings')) {
    class CODN_Settings
    {
        private static $instance;

        /** @var CodNetworkRepository  */
        private $codNetworkRepository;

        public function __construct()
        {
            $this->codNetworkRepository = CodNetworkRepository::get_instance();
        }

        public static function get_instance()
        {
            if (self::$instance === null) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * Get token form Database
         */
        public function has_token(): bool
        {
            return $this->codNetworkRepository->has_token();
        }

        /**
         * Select Token form database
         */
        public function select_token(): ?string
        {
            return $this->codNetworkRepository->select_token();
        }
    }
}

