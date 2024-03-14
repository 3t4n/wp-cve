<?php

namespace WunderAuto\Types\Parameters;

use Exception;
use WC_Countries;
use WunderAuto\Format\Phone;
use WunderAuto\JSONPath\JSONPath;
use WunderAuto\Types\BaseWorkflowEntity;

/**
 * Class BaseParameter
 */
class BaseParameter extends BaseWorkflowEntity
{
    /**
     * @var int
     */
    public $sortOrder = 10;

    /**
     * @var bool
     */
    public $usesDefault = false;

    /**
     * @var bool
     */
    public $usesDateFormat = false;

    /**
     * @var bool
     */
    public $usesName = false;

    /**
     * @var bool
     */
    public $usesFieldName = false;

    /**
     * @var bool
     */
    public $usesObjectPath = false;

    /**
     * @var bool
     */
    public $usesAcfFieldName = false;

    /**
     * @var bool
     */
    public $usesPhoneFormat = false;

    /**
     * @var bool
     */
    public $usesOutputFormat = false;

    /**
     * @var bool
     */
    public $useTaxonomy = false;

    /**
     * @var bool
     */
    public $usesReturnAs = false;

    /**
     * @var bool
     */
    public $usesTreatAsType = false;

    /**
     * @var array<string, string>
     */
    public $outputFormats = [];

    /**
     * @var bool
     */
    public $usesConfirmationLinkFields = false;

    /**
     * @var string
     */
    public $dataType = 'string';

    /**
     * @var string
     */
    public $customFieldNameCaption = '';

    /**
     * @var string
     */
    public $customFieldNameDesc = '';

    /**
     * @var bool
     */
    public $usesEscapeNewLines = true;

    /**
     * @var bool
     */
    public $usesUrlEncode = true;

    /**
     * @var bool
     */
    public $isProOnly = false;

    /**
     * @var array<int, string>|string
     */
    public $objects = [];

    /**
     * Create
     */
    public function __construct()
    {
    }

    /**
     * @param mixed     $object
     * @param \stdClass $modifiers
     *
     * @return object|array<int, mixed>|string|float|int|null
     */
    public function getValue($object, $modifiers)
    {
        return null;
    }

    /**
     * @param \stdClass $modifiers
     *
     * @return mixed|null
     */
    protected function getDefaultValue($modifiers)
    {
        return isset($modifiers->default) ? $modifiers->default : null;
    }

    /**
     * @param mixed     $data
     * @param \stdClass $modifiers
     *
     * @return string|float|int
     */
    protected function getDataWithPath($data, $modifiers)
    {
        $value    = $data;
        $jsonPath = new JSONPath($data);
        $path     = isset($modifiers->path) ? $modifiers->path : null;
        if (strlen(trim($path)) > 0) {
            try {
                $value = $jsonPath->find($path)->data();
            } catch (Exception $e) {
                //
            }
        }
        $value = $jsonPath->getFirstElement($value);

        return $this->formatField($value, $modifiers);
    }

    /**
     * @param string|float|int|false $value
     * @param \stdClass              $modifiers
     *
     * @return string|float|int
     */
    public function formatField($value, $modifiers)
    {
        if ($value === false) {
            return '';
        }

        if (!is_object($modifiers)) {
            $modifiers = (object)[];
        }

        if (isset($modifiers->type)) {
            // Date
            if ($modifiers->type === 'date' && !is_float($value)) {
                $value = $this->formatDate($value, $modifiers);
            }
            // Phone
            if ($modifiers->type === 'phone' && (is_string($value))) {
                if (isset($modifiers->format) && trim($modifiers->format) === 'e.164') {
                    $wunderAuto      = wa_wa();
                    $currentResolver = $wunderAuto->getCurrentResolver();
                    $order           = $currentResolver->getFirstObjectByType('order');

                    $isoCountry = '';
                    if ($order && $order instanceof \WC_Order) {
                        $isoCountry = $order->get_billing_country();
                    }
                    if (empty($isoCountry) && class_exists('WC_Countries')) {
                        $wcCountries = new WC_Countries();
                        $isoCountry  = $wcCountries->get_base_country();
                    }

                    if (empty($isoCountry)) {
                        $isoCountry = 'US';
                    }
                    $phoneFormat = new Phone();
                    $value       = $phoneFormat->formatE164($value, $isoCountry);
                }
            }
        }

        if (isset($modifiers->pluck) && is_string($value)) {
            $value = $this->pluck($value, $modifiers);
        }

        if (isset($modifiers->transform) && is_string($value)) {
            $value = $this->transform($value, $modifiers);
        }

        if (isset($modifiers->escnl)) {
            if ($modifiers->escnl === 'sl') {
                $value = str_replace("\r\n", "\\r\\n", $value);
                $value = str_replace("\n", "\\n", $value);
            }
            if ($modifiers->escnl === 'br') {
                $value = str_replace("\r\n", "<br>", $value);
                $value = str_replace("\n", "<br>", $value);
            }
        }

        if (isset($modifiers->urlenc) && in_array($modifiers->urlenc, ['yes', 'true', '1', 1])) {
            $value = urlencode($value);
        }

        return $value;
    }

    /**
     * Formats a value as date using format found either in modifier, WunderAutomation
     * settings or WordPress settings
     *
     * @param string|int|false $value
     * @param object           $modifiers
     *
     * @return string|false
     */
    public function formatDate($value, $modifiers)
    {
        $waOptions = get_option('wunderauto-general');
        $value     = $value !== false ? $this->toEpoch($value) : false;

        if ($value === false) {
            return false;
        }

        if (isset($modifiers->add)) {
            $value = strtotime($modifiers->add, $value);
        }

        $format = null;
        if (isset($modifiers->format)) {
            $format = $modifiers->format;
        }
        if (empty($format) && isset($waOptions['datetimeformat']) && strlen($waOptions['datetimeformat']) > 0) {
            $format = $waOptions['datetimeformat'];
        }
        if (empty($format)) {
            $format = get_option('date_format');
        }

        return date($format, $value);
    }

    /**
     * Takes any value and tries to interpret is as a datetime and return the
     * epoch of that datetime
     *
     * @param object|string|int $str
     *
     * @return int|false
     */
    public function toEpoch($str)
    {
        if (is_object($str)) {
            if (get_class($str) === 'WC_DateTime') {
                return strtotime($str->date('Y-m-d H:i:s')); // @phpstan-ignore-line
            }

            return false;
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
     * Pick the nth element from a $divider separated string
     *
     * @param object|array<int, string>|string $value
     * @param \stdClass                        $modifiers
     *
     * @return mixed|string|null
     */
    private function pluck($value, $modifiers)
    {
        if (is_object($value) || is_array($value)) {
            return $value;
        }

        $divider = isset($modifiers->divider) ? $modifiers->divider : ',';
        $limit   = isset($modifiers->limit) ? (int)$modifiers->limit : 99999;
        $item    = (int)$modifiers->pluck;
        $parts   = explode($divider, strval($value), $limit);

        if ($item < 0) {
            return null;
        }

        if ($item > count($parts) - 1) {
            return null;
        }

        return trim($parts[$item]);
    }

    /**
     * Performs string transformations according to $modifiers
     *
     * @param string    $value
     * @param \stdClass $modifiers
     *
     * @return string
     */
    private function transform($value, $modifiers)
    {
        if ($modifiers->transform === 'upper') {
            $value = function_exists('mb_strtoupper') ?
                mb_strtoupper($value) :
                strtoupper($value);
        }

        if ($modifiers->transform === 'lower') {
            $value = function_exists('mb_strtolower') ?
                mb_strtolower($value) :
                strtolower($value);
        }

        return $value;
    }
}
