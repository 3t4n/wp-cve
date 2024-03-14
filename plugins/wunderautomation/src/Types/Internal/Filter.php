<?php

namespace WunderAuto\Types\Internal;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class Filter
 */
class Filter extends BaseInternalType
{
    /**
     * @var string
     */
    public $filter = '';

    /**
     * @var string
     */
    public $filterKey = '';

    /**
     * @var string
     */
    public $object = '';

    /**
     * @var object|string
     */
    public $compare = '';

    /**
     * @var array<string, mixed>|array<int, \stdClass>|string|int
     */
    public $value;

    /**
     * @var string
     */
    public $value2 = '';

    /**
     * @var array<int, \stdClass>|array<string, mixed>
     */
    public $arrValue = [];

    /**
     * @var string
     */
    public $field = '';

    /**
     * @var string
     */
    public $path = '';

    /**
     * @param \stdClass|array<int, mixed>|null $state
     */
    public function __construct($state = null)
    {
        parent::__construct($state);

        if (isset($state->arrValue) && !empty($state->arrValue)) {
            $this->value = $state->arrValue;
        }

        $this->filter    = str_replace('|', '\\', $this->filter);
        $this->filterKey = str_replace('|', '\\', $this->filterKey);

        $this->sanitizeObjectProp($this, 'filter', 'text');
        $this->sanitizeObjectProp($this, 'filterKey', 'text');
        $this->sanitizeObjectProp($this, 'compare', 'key');
        $this->sanitizeObjectProp($this, 'value2', 'text');
        $this->sanitizeObjectProp($this, 'field', 'text');
        $this->sanitizeObjectProp($this, 'path', 'text');

        if (is_array($this->value) && count($this->value) > 0) {
            foreach ($this->value as &$value) {
                $this->sanitizeObjectProp($value, 'label', 'text');
                $this->sanitizeObjectProp($value, 'value', 'text');
            }
            $this->arrValue = $this->value;
        } else {
            $this->sanitizeObjectProp($this, 'value', 'text');
        }

        if (strlen($this->filterKey) > 0) {
            $parts = explode('::', $this->filterKey, 2);
            if (count($parts) > 1) {
                $this->object = $parts[0];
                $this->filter = $parts[1];
            }
        }
    }

    /**
     * Handle json<string, mixed>
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize()
    {
        $return = [];
        foreach (get_object_vars($this) as $property => $value) {
            switch ($property) {
                case 'map':
                    break;
                case 'arrValue':
                    if (static::$wpPostMetaMode === false) {
                        $return[$property] = $value;
                    }
                    break;
                case 'filter':
                case 'filterKey':
                    $return[$property] = BaseInternalType::$wpPostMetaMode ?
                        str_replace('\\', '|', $value) :
                        $value;
                    break;
                default:
                    $return[$property] = $value;
            }
        }

        return $return;
    }
}
