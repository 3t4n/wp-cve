<?php

namespace WunderAuto;

use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Resolver
 */
class Resolver
{
    /**
     * @var array<string, \stdClass>
     */
    private $objects;

    /**
     * @var array<string, BaseParameter|\stdClass|object>
     */
    private $parameters;

    /**
     * @var TemplateParser
     */
    private $templateParser;

    /**
     * Associative array containing all object classes we treat
     * via the parameter classes. I.e WP_Post, WP_User etc.
     *
     * @var array<string, \stdClass>
     */
    private $objectTypes;

    /**
     * @param array<string, \stdClass>     $objects
     * @param array<string, BaseParameter> $parameters
     * @param array<string, \stdClass>     $objectTypes
     */
    public function __construct($objects, $parameters, $objectTypes)
    {
        $this->objects        = $objects;
        $this->parameters     = $parameters;
        $this->objectTypes    = $objectTypes;
        $this->templateParser = new TemplateParser($parameters, $objectTypes);
    }

    /**
     * Return all current objects
     *
     * @return array<string, \stdClass>
     */
    public function getObjects()
    {
        return $this->objects;
    }

    /**
     * Add a paramter object
     *
     * @param string                         $name
     * @param BaseParameter|\stdClass|object $object
     *
     * @return void
     */
    public function addParameter($name, $object)
    {
        $this->parameters[$name] = $object;
    }

    /**
     * Checks if a string identifies a parameter
     *
     * @param string $parameterTitle
     *
     * @return bool
     */
    public function isParameter($parameterTitle)
    {
        $parts          = explode(' ', $parameterTitle);
        $parameterTitle = $parts[0];
        $parameterTitle = strtolower(trim($parameterTitle));
        return isset($this->parameters[$parameterTitle]);
    }

    /**
     * @param string     $type
     * @param string     $name
     * @param string|int $id
     *
     * @return bool true if an object was added
     */
    public function addObjectById($type, $name, $id)
    {
        $object = null;
        switch ($type) {
            case 'post':
                $object = get_post((int)$id);
                if (!$object) {
                    $object = get_page_by_path((string)$id);
                }
                break;
            case 'user':
                $object = get_user_by('id', $id);
                if (!$object) {
                    $object = get_user_by('email', $id);
                }
                if (!$object) {
                    $object = get_user_by('login', $id);
                }
                break;
            case 'order':
                $object = wc_get_order($id);
                if (!$object) {
                    $object = wc_get_order_id_by_order_key((string)$id);
                }
                break;
            case 'comment':
                $object = get_comment($id);
                break;
            default:
                $object = apply_filters("wunderauto/resolver/getobject/$type", $object, $id);
        }

        if (!empty($object)) {
            $this->addObject($name, $type, $object);
            return true;
        }

        return false;
    }

    /**
     * @param string $name
     * @param string $type
     * @param mixed  $object
     *
     * @return void
     */
    public function addObject($name, $type, $object)
    {
        $this->objects[$name] = (object)[
            'id'    => $name,
            'type'  => $type,
            'value' => $object,
        ];
    }

    /**
     * Check if the object array have an object with id = $id
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasObjectId($name)
    {
        foreach ($this->objects as $object) {
            if ($object->id === $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * If the currentuser isn't already set, create and set it now
     *
     * @return void
     */
    public function maybeAddCurrentUser()
    {
        $wunderAuto = wa_wa();

        $user = $wunderAuto->createEmptyWpUser();
        if (!isset($this->objects['currentuser'])) {
            if (function_exists('wp_get_current_user')) {
                $user = wp_get_current_user();
            }
        }
        $this->addObject('currentuser', 'user', $user);
    }

    /**
     * Resolve the field
     *
     * @param string $text
     * @param string $dataType
     *
     * @return string
     */
    public function resolveField($text, $dataType = 'string')
    {
        $parsed = $this->templateParser->parse($text, $this->objects);

        if (in_array($dataType, ['float', 'int'])) {
            $mathResolved = $this->templateParser->evalMath->evaluate($parsed);
            if ($mathResolved !== false) {
                $parsed = $mathResolved;
            }
            switch ($dataType) {
                case 'float':
                    $parsed = (float)$parsed;
                    break;
                case 'int':
                    $parsed = (int)$parsed;
                    break;
            }
        }
        return $parsed;
    }

    /**
     * Get array of object types and id's
     * Used for serializing to db
     *
     * @return array<int, array<string, mixed>>
     */
    public function getObjectIdArray()
    {
        $ret = [];
        foreach ($this->objects as $name => $object) {
            $id = $this->getObjectId($object->value);
            if ((int)$id === 0) {
                continue;
            }
            $ret[] = [
                'id'   => $this->getObjectId($object->value),
                'name' => $object->id,
                'type' => $object->type,
            ];
        }

        return $ret;
    }

    /**
     * Figure out what ID field is used and return the value
     *
     * @param object $object
     *
     * @return string|int|null
     */
    public function getObjectId($object)
    {
        $id = null;

        if (!is_object($object)) {
            return $id;
        }

        if ($id === null && method_exists($object, 'get_id')) {
            $id = $object->get_id();
        }
        if ($id === null && isset($object->ID)) {
            $id = $object->ID;
        }
        if ($id === null && isset($object->id)) {
            $id = $object->id;
        }
        if ($id === null && isset($object->comment_ID)) {
            $id = $object->comment_ID;
        }
        if ($id === null && isset($object->Id)) {
            $id = $object->Id;
        }

        return apply_filters('wa_get_object_id', $id, $object);
    }

    /**
     * Get the first object type in the array
     *
     * @return string|null
     */
    public function getFirstObjectType()
    {
        $first = reset($this->objects);
        if ($first instanceof \stdClass) {
            return $first->type;
        }

        return null;
    }

    /**
     * Return the first object in the current object context
     * of type $type
     *
     * @param string $type
     *
     * @return object|false
     */
    public function getFirstObjectByType($type)
    {
        if (!isset($this->objectTypes[$type])) {
            return false;
        }

        $objectType = $this->objectTypes[$type];
        foreach ($this->objects as $object) {
            if ($object->type === $type) {
                return $object->value;
            }
        }

        return false;
    }

    /**
     * Set a meta value for an object of known type
     *
     * @param string $name
     * @param string $key
     *
     * @return mixed
     */
    public function getMetaValue($name, $key)
    {
        $value  = null;
        $object = $this->getObject($name);
        if (is_null($object)) {
            return null;
        }
        $id   = $this->getObjectId($object);
        $type = $this->getObjectTypeByName($name);
        if (is_null($type)) {
            return null;
        }

        switch ($type) {
            case 'post':
            case 'user':
            case 'comment':
                $value = get_metadata($type, (int)$id, $key, true);
                break;
            case 'order':
                $value = get_metadata('post', (int)$id, $key, true);
                break;
        }

        $value = apply_filters('wa_get_meta_value', $value, $object, $key);
        $value = apply_filters('wunderauto/metavalue/get', $value, $object, $key);

        return $value;
    }

    /**
     * Get a resolver object by name
     *
     * @param string $name
     *
     * @return object|null
     */
    public function getObject($name)
    {
        if (isset($this->objects[$name])) {
            return $this->objects[$name]->value;
        }
        return null;
    }

    /**
     * Determine if the object in the current context has it's parameters
     * served by a parameter object
     *
     * @param object|string $objectName
     *
     * @return string|null
     */
    public function getObjectTypeByName($objectName)
    {
        foreach ($this->objects as $name => $objectType) {
            if ($name == $objectName) {
                return $objectType->type;
            }
        }

        return null;
    }

    /**
     * Set a meta value for an object of known type
     *
     * @param string $name
     * @param string $key
     * @param mixed  $newValue
     *
     * @return void
     */
    public function setMetaValue($name, $key, $newValue)
    {
        $object = $this->getObject($name);
        if (is_null($object)) {
            return;
        }

        $id = $this->getObjectId($object);
        if (is_null($id)) {
            return;
        }

        $type = $this->getObjectTypeByName($name);
        if (is_null($type)) {
            return;
        }

        switch ($type) {
            case 'post':
            case 'user':
            case 'comment':
                $updated = update_metadata($type, (int)$id, $key, $newValue);
                break;
            case 'order':
                $updated = update_metadata('post', (int)$id, $key, $newValue);
                break;
        }
        do_action('wunderauto/metavalue/set', $object, $name, $type, $key, $newValue);
    }
}
