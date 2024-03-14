<?php

namespace Qodax\CheckoutManager\Includes\Presenter;

use Qodax\CheckoutManager\Contracts\FieldDataPresenterInterface;

if ( ! defined('ABSPATH')) {
    exit;
}

class DBFieldDataPresenter implements FieldDataPresenterInterface
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var array
     */
    private $meta;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->meta = json_decode($data['field_meta'], true);
    }

    public function getName(): string
    {
        return $this->data['field_name'];
    }

    public function getType(): string
    {
        return $this->data['field_type'];
    }

    public function getPriority(): int
    {
        return (int)$this->data['priority'];
    }

    public function isActive(): int
    {
        return (int)$this->data['active'];
    }

    public function isRequired(): int
    {
        return (int)$this->data['required'];
    }

    public function isNative(): int
    {
        return (int)$this->data['native'];
    }

    public function getMeta(string $key, $default = '')
    {
        return $this->meta[$key] ?? $default;
    }

    public function getDisplayRules(): array
    {
        return $this->data['display_rules'] ?? [];
    }
}