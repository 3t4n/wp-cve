<?php

namespace Qodax\CheckoutManager\Includes\Fields;

use Qodax\CheckoutManager\Contracts\FieldDataPresenterInterface;

if ( ! defined('ABSPATH')) {
    exit;
}

class CheckoutField implements \JsonSerializable
{
    protected $name;
    protected $type;
    protected $native;
    protected $required;
    protected $active;
    protected $priority;
    protected $meta = [];
    protected $displayRules = [];

    public function __construct(FieldDataPresenterInterface $dataPresenter)
    {
        $this->name = $dataPresenter->getName();
        $this->type = $dataPresenter->getType();
        $this->priority = $dataPresenter->getPriority();
        $this->native = $dataPresenter->isNative();
        $this->required = $dataPresenter->isRequired();
        $this->active = $dataPresenter->isActive();
        $this->displayRules = $dataPresenter->getDisplayRules();
        $this->addMeta('label', $dataPresenter->getMeta('label'));
        $this->addMeta('placeholder', $dataPresenter->getMeta('placeholder'));
        $this->addMeta('default', $dataPresenter->getMeta('default'));
        $this->addMeta('class', $dataPresenter->getMeta('class', []));
        $this->addMeta('clear', $dataPresenter->getMeta('clear', 1));
        $this->addMeta('label_class', $dataPresenter->getMeta('label_class', []));
        $this->addMeta('validate', $dataPresenter->getMeta('validate', []));
        $this->addMeta('options', $dataPresenter->getMeta('options', []));
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getDisplayRules(): array
    {
        return $this->displayRules;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function addMeta(string $name, $value)
    {
        $this->meta[$name] = $value;
    }

    /**
     * @param string $name
     * @param mixed $default
     *
     * @return mixed
     */
    public function getMeta(string $name, $default = '')
    {
        return $this->meta[$name] ?? $default;
    }

    public function toWooCommerce(): array
    {
        $data = [
            'type' => $this->type,
            'label' => $this->getMeta('label'),
            'priority' => $this->priority,
            'required' => (bool)$this->required,
            'class' => $this->getMeta('class', []),
            'placeholder' => $this->getMeta('placeholder'),
            'default' => $this->getMeta('default'),
            'validate' => $this->getMeta('validate'),
            'clear' => (bool)$this->getMeta('clear')
        ];

        foreach ($this->getMeta('options', []) as $option) {
            $data['options'][ $option['value'] ] = $option['name'];
        }

        return $data;
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'active' => $this->active,
            'required' => $this->required,
            'native' => $this->native,
            'priority' => $this->priority,
            'meta' => $this->meta,
            'displayRules' => $this->displayRules,
        ];
    }
}