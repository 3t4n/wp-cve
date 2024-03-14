<?php

namespace WunderAuto\Types\Filters;

use Exception;
use WC_DateTime;
use WunderAuto\IPTools\Range;
use WunderAuto\JSONPath\JSONPath;
use WunderAuto\Resolver;
use WunderAuto\Types\BaseWorkflowEntity;
use WunderAuto\Types\Internal\Filter;

/**
 * Class BaseFilter
 */
class BaseFilter extends BaseWorkflowEntity
{
    /**
     * @var string
     */
    public $inputType;

    /**
     * @var string
     */
    public $placeholder;

    /**
     * @var string
     */
    public $ajaxAction;

    /**
     * @var string
     */
    public $nonceName;

    /**
     * @var array<string, string>
     */
    public $operators = [];

    /**
     * @var string
     */
    public $valueType = 'text';

    /**
     * @var array<int, array<string, string>>
     */
    public $compareValues = [];

    /**
     * @var array<int, string>
     */
    public $objects = [];

    /**
     * @var bool
     */
    public $usesCustomField = false;

    /**
     * @var bool
     */
    public $usesAdvancedCustomField = false;

    /**
     * @var string
     */
    public $customFieldPlaceholder = '';

    /**
     * @var bool
     */
    public $usesObjectPath = false;

    /**
     * @var Resolver
     */
    protected $resolver;

    /**
     * @var Filter
     */
    protected $filterConfig;

    /**
     * Create the filter.
     */
    public function __construct()
    {
    }

    /**
     * Class initialization
     *
     * @return void
     */
    public function initialize()
    {
    }

    /**
     * @return string
     */
    public function getFilterClass()
    {
        return __NAMESPACE__ . '\\' . __CLASS__;
    }

    /**
     * Evaluates the filter
     *
     * @return bool
     */
    public function evaluate()
    {
        return false;
    }

    /**
     * @param Filter $config
     *
     * @return void
     */
    public function setFilterConfig($config)
    {
        $this->filterConfig = $config;
    }

    /**
     * @param Resolver $resolver
     *
     * @return void
     */
    public function setResolver($resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Evaluates expected vs actual values using one of the supported operators.
     * Used by Filter child classes to simplify filter evaluations
     *
     * @param WC_DateTime|array<int, string|int>|string|float|int $actualValue
     *
     * @return bool
     */
    public function evaluateCompare($actualValue)
    {
        $ret = false;

        $operators = ['eq', 'neq', 'gte', 'lte'];
        if (in_array($this->filterConfig->compare, $operators)) {
            $expected = $this->filterConfig->value;
            if (is_array($expected)) {
                $expected = $expected['value'];
            }
            if (is_object($expected)) {
                $expected = $expected->value; // @phpstan-ignore-line
            }

            switch ($this->filterConfig->compare) {
                case 'eq':
                    $ret = $expected == $actualValue;
                    break;
                case 'neq':
                    $ret = $expected != $actualValue;
                    break;
                case 'gte':
                    $ret = $actualValue >= $expected;
                    break;
                case 'lte':
                    $ret = $actualValue <= $expected;
                    break;
            }
            return $ret;
        }

        $operators = ['empty', 'nempty'];
        if (in_array($this->filterConfig->compare, $operators)) {
            switch ($this->filterConfig->compare) {
                case 'empty':
                    $ret = empty($actualValue);
                    break;
                case 'nempty':
                    $ret = !empty($actualValue);
            }

            return $ret;
        }

        $operators = ['contains', 'ncontains', 'startswith', 'endswith', 'nstartswith', 'nendswith'];
        if (in_array($this->filterConfig->compare, $operators)) {
            $needle = $this->filterConfig->value;
            if (is_array($needle)) {
                $needle = isset($needle['value']) ? $needle['value'] : '';
            }
            if (is_object($needle)) {
                $needle = isset($needle->value) ? $needle->value : '';
            }

            if (strlen($needle) === 0) {
                return false;
            }

            if (!is_string($actualValue)) {
                return false;
            }

            switch ($this->filterConfig->compare) {
                case 'contains':
                    $ret = strpos($actualValue, $needle) !== false;
                    break;
                case 'ncontains':
                    $ret = strpos($actualValue, $needle) === false;
                    break;
                case 'startswith':
                    $ret = substr($actualValue, 0, strlen($needle)) == $needle;
                    break;
                case 'nstartswith':
                    $ret = substr($actualValue, 0, strlen($needle)) != $needle;
                    break;
                case 'endswith':
                    $ret = substr($actualValue, strlen($needle) * -1) == $needle;
                    break;
                case 'nendswith':
                    $ret = substr($actualValue, strlen($needle) * -1) != $needle;
                    break;
            }
            return $ret;
        }

        $operators = ['datebefore', 'dateafter', 'datesameday', 'datebetween', 'datenbetween'];
        if (in_array($this->filterConfig->compare, $operators)) {
            $expected = $this->filterConfig->value;
            if (is_object($expected)) {         // @phpstan-ignore-line
                $expected = $expected->value;
            }
            $expected2 = PHP_INT_MAX;
            if (in_array($this->filterConfig->compare, ['datebetween', 'datenbetween'])) {
                $expected2 = $this->filterConfig->value2;
                if (is_object($expected2)) { // @phpstan-ignore-line
                    $expected2 = $expected2->value;
                }
            }

            if (is_array($expected) || is_array($expected2) || is_array($actualValue)) { // @phpstan-ignore-line
                return false;
            }

            if (is_float($actualValue)) {
                $actualValue = intval($actualValue);
            }

            switch ($this->filterConfig->compare) {
                case 'datebefore':
                    $ret = $this->toEpoch($actualValue) <= $this->toEpoch($expected);
                    break;
                case 'dateafter':
                    $ret = $this->toEpoch($actualValue) >= $this->toEpoch($expected);
                    break;
                case 'datebetween':
                    $ret = $this->toEpoch($actualValue) >= $this->toEpoch($expected) &&
                        $this->toEpoch($actualValue) <= $this->toEpoch($expected2);
                    break;
                case 'datenbetween':
                    $ret = $this->toEpoch($actualValue) <= $this->toEpoch($expected) ||
                        $this->toEpoch($actualValue) >= $this->toEpoch($expected2);
                    break;
                case 'datesameday':
                    $date1 = strtotime('Y-m-d', (int)$this->toEpoch($expected));
                    $date2 = strtotime('Y-m-d', (int)$this->toEpoch($actualValue));
                    $ret   = $date1 == $date2;
                    break;
            }
            return $ret;
        }

        $operators = ['anyinlist', 'allinlist', 'noneinlist'];
        if (in_array($this->filterConfig->compare, $operators)) {
            if (!is_array($actualValue)) {
                return false;
            }
            $codes       = $this->filterCompareValues();
            $actualValue = array_unique($actualValue);

            switch ($this->filterConfig->compare) {
                case 'anyinlist':
                    $ret = count(array_intersect($actualValue, $codes)) > 0;
                    break;
                case 'noneinlist':
                    $ret = count(array_intersect($actualValue, $codes)) == 0;
                    break;
                case 'allinlist':
                    $ret = count(array_intersect($actualValue, $codes)) == count($codes);
                    break;
            }
            return $ret;
        }

        $operators = ['isoneof', 'isnotoneof'];
        if (in_array($this->filterConfig->compare, $operators)) {
            $codes = $this->filterCompareValues();
            switch ($this->filterConfig->compare) {
                case 'isoneof':
                    $ret = in_array($actualValue, $codes);
                    break;
                case 'isnotoneof':
                    $ret = !in_array($actualValue, $codes);
                    break;
            }

            return $ret;
        }

        $operators = ['innetwork', 'ninnetwork'];
        if (in_array($this->filterConfig->compare, $operators)) {
            try {
                $range  = Range::parse($this->filterConfig->value); // @phpstan-ignore-line
                $ip     = new \WunderAuto\IPTools\IP($actualValue); // @phpstan-ignore-line
                $result = $range->contains($ip);
            } catch (Exception $e) {
                $result = false;
            }

            switch ($this->filterConfig->compare) {
                case 'innetwork':
                    $ret = ($result === true);
                    break;
                case 'ninnetwork':
                    $ret = ($result === false);
            }
            return $ret;
        }

        return $ret;
    }

    /**
     * Takes any value and tries to interpret is as a datetime and return the
     * epoch of that datetime
     *
     * @param object|string|int $str
     *
     * @return int|false
     */
    private function toEpoch($str)
    {
        if (is_object($str) && get_class($str) === 'WC_DateTime') {
            return strtotime($str->date('Y-m-d H:i:s')); // @phpstan-ignore-line
        }

        if (is_object($str)) {
            return strtotime('1970-01-01');
        }

        if (is_int($str)) {
            return $str;
        }

        if ((string)(int)$str === $str) {
            return (int)$str;
        }

        return strtotime($str);
    }

    /**
     * Compares standardized filters and values
     *
     * @return array<int, string>
     */
    private function filterCompareValues()
    {
        $valueArr = $this->filterConfig->value;
        if (is_string($valueArr)) {
            $values   = explode(',', $valueArr);
            $valueArr = [];
            foreach ($values as $value) {
                $valueArr[] = (object)['value' => $value];
            }
        }

        $ret = array_map(function ($a) {
            if ($a instanceof \stdClass) {
                return trim($a->value);
            }
            return $a;
        }, (array)$valueArr);

        return array_values($ret);
    }

    /**
     * Retrieves the object that this filter is working on
     *
     * @return object|null
     */
    protected function getObject()
    {
        $object = null;
        $name   = $this->filterConfig->object;

        if (!empty($name)) {
            $object = $this->resolver->getObject($name);
        }
        return $object;
    }

    /**
     * @param string $actualValue
     * @param string $path
     *
     * @return mixed
     */
    protected function evaluateJSONPath($actualValue, $path)
    {
        $jsonPath = new JSONPath($actualValue);
        if (strlen(trim($path)) > 0) {
            try {
                $actualValue = $jsonPath->find($path)->data();
            } catch (Exception $e) {
                $actualValue = '';
            }
        }
        // Ensure it's a single value
        $actualValue = $jsonPath->getFirstElement($actualValue);
        return $actualValue;
    }

    /**
     * Standard operators for strings
     *
     * @return array<string, string>
     */
    protected function stringOperators()
    {
        return [
            'eq'         => __('Equals', 'wunderauto'),
            'neq'        => __('Does not equal', 'wunderauto'),
            'contains'   => __('Contains', 'wunderauto'),
            'ncontains'  => __('Does not contain', 'wunderauto'),
            'startswith' => __('Starts with', 'wunderauto'),
            'endswith'   => __('Ends with', 'wunderauto'),
            'empty'      => __('Is empty', 'wunderauto'),
            'nempty'     => __('Is not empty', 'wunderauto'),
            'gte'        => __('Greater than or equals', 'wunderauto'),
            'lte'        => __('Lower than or equals', 'wunderauto'),
            'isoneof'    => __('Is one of', 'wunderauto'),
            'isnotoneof' => __('Is not one of', 'wunderauto'),
        ];
    }

    /**
     * Standard operators for dates
     *
     * @return array<string, string>
     */
    protected function dateOperators()
    {
        return [
            'datebefore' => __('Is before', 'wunderauto'),
            'dateafter'  => __('Is after', 'wunderauto'),
            'dateempty'  => __('Is empty', 'wunderauto'),
        ];
    }

    /**
     * Standard operators for single value in set
     *
     * @return array<string, string>
     */
    protected function setOperators()
    {
        return [
            'isoneof'    => __('Is one of', 'wunderauto'),
            'isnotoneof' => __('Is not one of', 'wunderauto'),
        ];
    }

    /**
     * Standard operators for multiple values in set
     *
     * @return array<string, string>
     */
    protected function multiSetOperators()
    {
        return [
            'anyinlist'  => __('Contains any', 'wunderauto'),
            'noneinlist' => __('Contains all', 'wunderauto'),
            'allinlist'  => __('Contains none', 'wunderauto'),
        ];
    }

    /**
     * Standard operators for numbers
     *
     * @return array<string, string>
     */
    protected function numberOperators()
    {
        return [
            'eq'  => __('Equals', 'wunderauto'),
            'neq' => __('Not equals', 'wunderauto'),
            'gte' => __('Greater than or equals', 'wunderauto'),
            'lte' => __('Lower than or equals', 'wunderauto'),
        ];
    }
}
