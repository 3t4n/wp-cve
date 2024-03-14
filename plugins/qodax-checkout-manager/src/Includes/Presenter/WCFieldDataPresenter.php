<?php

namespace Qodax\CheckoutManager\Includes\Presenter;

use Qodax\CheckoutManager\Contracts\FieldDataPresenterInterface;

if ( ! defined('ABSPATH')) {
    exit;
}

class WCFieldDataPresenter implements FieldDataPresenterInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $data;

    public function __construct(string $name, array $data)
    {
        $this->name = $name;
        $this->data = $data;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->data['type'] ?? 'text';
    }

    public function getPriority(): int
    {
        return (int)$this->data['priority'];
    }

    public function isActive(): int
    {
        return 1;
    }

    public function isRequired(): int
    {
        return (int)($this->data['required'] ?? 0);
    }

    public function isNative(): int
    {
        return 1;
    }

    public function getMeta(string $key, $default = '')
    {
        return $this->data[$key] ?? $default;
    }

    public function getDisplayRules(): array
    {
        return $this->data['display_rules'] ?? [];
    }
}