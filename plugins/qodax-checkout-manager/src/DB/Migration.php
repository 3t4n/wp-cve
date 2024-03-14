<?php

namespace Qodax\CheckoutManager\DB;

if ( ! defined('ABSPATH')) {
    exit;
}

abstract class Migration
{
    /**
     * @return string
     */
    abstract public function name(): string;

    /**
     * @param mixed $db
     *
     * @return void
     */
    abstract public function up($db): void;
}