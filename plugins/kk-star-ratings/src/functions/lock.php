<?php

/*
 * This file is part of bhittani/kk-star-ratings.
 *
 * (c) Kamal Khan <shout@bhittani.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bhittani\StarRating\functions;

use Exception;

if (! defined('KK_STAR_RATINGS')) {
    http_response_code(404);
    exit();
}

function lock(string $name, int $ttl = 60): object
{
    return new class($name, $ttl) {
        /** @var string */
        protected $name;

        /** @var int */
        protected $ttl;

        public function __construct(string $name, int $ttl)
        {
            $this->name = "{$name}_lock";
            $this->ttl = $ttl;
        }

        /** @throws Exception If the lock is already acquired. */
        public function acquire(): bool
        {
            if ($this->isLocked()) {
                throw new Exception("A lock for '{$this->name}' has already been acquired.");
            }

            return set_transient($this->name, true, $this->ttl);
        }

        public function release(): bool
        {
            return delete_transient($this->name);
        }

        public function isLocked(): bool
        {
            return (bool) get_transient($this->name);
        }
    };
}
