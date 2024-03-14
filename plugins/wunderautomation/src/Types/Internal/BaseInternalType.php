<?php

namespace WunderAuto\Types\Internal;

/**
 * Class BaseInternalType
 */
class BaseInternalType implements \JsonSerializable
{
    /**
     * @var bool
     */
    protected static $wpPostMetaMode = false;

    /**
     * @var bool
     */
    protected static $stateFromUI = false;

    /**
     * @var array<string, string>
     */
    protected $map = [];

    /**
     * @param \stdClass|array<int, mixed>|null $state
     */
    public function __construct($state = null)
    {
        if (is_null($state)) {
            return;
        }

        $count = 0;
        foreach (get_object_vars($this) as $property => $value) {
            if ('map' === $property) {
                continue;
            }

            $count++;
            if (!empty($this->map[$property])) {
                $class = $this->map[$property];

                if (isset($state->$property) && is_object($state->$property)) {
                    $this->$property = new $class($state->$property);
                    continue;
                }

                if (isset($state->$property) && is_array($state->$property)) {
                    $objects = [];
                    foreach ($state->$property as $value) {
                        $objects[] = new $class($value);
                    }
                    $this->$property = $objects;

                    continue;
                }

                if (is_array($state) && $count === 1) {
                    $objects = [];
                    foreach ($state as $value) {
                        $objects[] = new $class($value);
                    }
                    $this->$property = $objects;

                    continue;
                }
            }

            if ($state instanceof \stdClass && property_exists($state, $property)) {
                $this->$property = $state->$property;
            }
        }
    }

    /**
     * Handle serialization
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize()
    {
        $return = [];
        foreach (get_object_vars($this) as $property => $value) {
            if ($property === 'map') {
                continue;
            }

            if ($value instanceof BaseInternalType) {
                $return[$property] = $value->jsonSerialize();
            } else {
                $return[$property] = $value;
            }
        }

        return $return;
    }

    /**
     * Return the internal struct as stdClass object
     *
     * @param bool $postMetaMode
     *
     * @return object
     */
    public function toObject($postMetaMode = false)
    {
        static::$wpPostMetaMode = $postMetaMode;
        $ret                    = json_decode(json_encode($this)); // @phpstan-ignore-line
        static::$wpPostMetaMode = false;

        return $ret;
    }

    /**
     * @param \stdClass          $object
     * @param string             $prop
     * @param string             $type
     * @param array<int, string> $values
     *
     * @return void
     */
    public function sanitizeObjectProp(&$object, $prop, $type, $values = [])
    {
        if (isset($object->$prop)) {
            $this->sanitizeValue($object->$prop, $type, $values);
        }
    }

    /**
     * @param \stdClass             $object
     * @param string                $prop
     * @param array<string, string> $types
     *
     * @return void
     */
    public function sanitizeObjectArray(&$object, $prop, $types)
    {
        if (isset($object->$prop) && is_array($object->$prop)) {
            foreach ($object->$prop as &$element) {
                foreach ($types as $index => $type) {
                    $this->sanitizeObjectProp($element, $index, $type);
                }
            }
        }
    }

    /**
     * @param \stdClass              $object
     * @param string                 $prop
     * @param string                 $type
     * @param array<int, int|string> $values
     *
     * @return void
     */
    public function sanitizeValueArray(&$object, $prop, $type, $values = [])
    {
        if (isset($object->$prop)) {
            if (!is_array($object->$prop)) {
                $object->$prop = [];
            }
            foreach ($object->$prop as &$element) {
                $this->sanitizeValue($element, $type, $values);
            }
        }
    }

    /**
     * @param mixed                  $value
     * @param string                 $type
     * @param array<int, int|string> $values
     *
     * @return void
     */
    private function sanitizeValue(&$value, $type, $values = [])
    {
        switch ($type) {
            case 'bool':
                $value = (bool)$value;
                break;
            case 'text':
                $value = sanitize_text_field($value);
                break;
            case 'textarea':
                $value = sanitize_textarea_field($value);
                break;
            case 'key':
                $value = sanitize_key($value);
                break;
            case 'url':
                $value = sanitize_url($value);
                break;
            case 'int':
                $value = (int)$value;
                break;
            case 'kses_post':
                $value = wp_kses_post($value);
                break;
            case 'enum':
                if (!in_array($value, $values)) {
                    $value = $values[0];
                }
                break;
        }
    }
}
