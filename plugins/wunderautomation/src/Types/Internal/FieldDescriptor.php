<?php

namespace WunderAuto\Types\Internal;

/**
 * Class FieldDescriptor
 */
class FieldDescriptor
{
    /**
     * @var string
     */
    protected $label = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var string
     */
    protected $type = '';

    /**
     * @var string
     */
    protected $model = '';

    /**
     * @var string
     */
    protected $variable = '';

    /**
     * @var string
     */
    protected $condition = '';

    /**
     * @var int
     */
    protected $min = 0;

    /**
     * @var array<string, \stdClass>|string
     */
    protected $options;

    /**
     * @var bool
     */
    protected $dynamic = false;

    /**
     * @var int
     */
    protected $prio = 10;

    /**
     * @param array<string, mixed> $args
     */
    public function __construct($args)
    {
        $this->label       = isset($args['label']) ? (string)$args['label'] : $this->label;
        $this->description = isset($args['description']) ? (string)$args['description'] : $this->description;
        $this->type        = isset($args['type']) ? (string)$args['type'] : $this->type;
        $this->model       = isset($args['model']) ? (string)$args['model'] : $this->model;
        $this->variable    = isset($args['variable']) ? (string)$args['variable'] : $this->variable;
        $this->condition   = isset($args['condition']) ? (string)$args['condition'] : $this->condition;
        $this->min         = isset($args['min']) ? (int)$args['min'] : $this->min;
        $this->dynamic     = isset($args['dynamic']) ? (bool)$args['dynamic'] : $this->dynamic;
        $this->prio        = isset($args['prio']) ? (int)$args['prio'] : $this->prio;
        $this->options     = isset($args['options']) ? $args['options'] : $this->options;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getVariable()
    {
        return $this->variable;
    }

    /**
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @return int
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @return string
     */
    public function getOptionsString()
    {
        return is_array($this->options) ? '' : $this->options;
    }

    /**
     * @return array<string, \stdClass>
     */
    public function getOptionsArray()
    {
        return is_array($this->options) ? $this->options : [];
    }

    /**
     * @return bool
     */
    public function isDynamic()
    {
        return $this->dynamic;
    }

    /**
     * @return int
     */
    public function getPrio()
    {
        return $this->prio;
    }
}
