<?php

namespace Qodax\CheckoutManager\Contracts;

if ( ! defined('ABSPATH')) {
    exit;
}

interface FieldDataPresenterInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return int
     */
    public function getPriority(): int;

    /**
     * @return int
     */
    public function isActive(): int;

    /**
     * @return int
     */
    public function isRequired(): int;

    /**
     * @return int
     */
    public function isNative(): int;

    /**
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function getMeta(string $key, $default = '');

    public function getDisplayRules(): array;
}