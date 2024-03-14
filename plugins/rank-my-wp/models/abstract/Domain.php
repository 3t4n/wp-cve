<?php

/**
 * ObjectAbstract
 *
 * Abstract base class for domain objects.
 * Magic setters and getters and get<Property>/set<Property> based on protected properies defined.
 */
abstract class RKMW_Models_Abstract_Domain implements ArrayAccess {

    public function __construct($properties = null) {
        if (isset($properties)) {
            if (!is_array($properties)) {
                $properties = (array)$properties;
            }

            if (isset($properties) && !empty($properties)) {
                foreach ($properties as $key => $value) {
                    // assign value to key
                    $this->$key = $value;
                }
            }
        }
    }

    // -----------------------------------------------------------------------

    /**
     * Magic methods
     */

    /**
     * Magic isset
     */
    public function __isset($name) {
        // map name to key; prepend _
        $key = '_' . $name;

        // if there is no such property
        if (!property_exists($this, $key))
            return FALSE;

        return isset($this->$key);
    }

    /**
     * Magic getter
     *
     * @param string $name the name of the property
     * @return mixed        the value of the property
     */
    public function __get($name) {
        // map name to getter method; prepend 'get'
        $method = 'get' . ucfirst($name);
        return $this->$method();
    }

    /**
     * Magic setter
     * $object->innerAttribute = $value; will call setInnerAttribute($value)
     *
     * @param string $name the name of the property
     * @param mixed $value the value to assign
     */
    public function __set($name, $value) {
        // map name to setter method; prepend 'set'
        $method = 'set' . ucfirst($name);
        $this->$method($value);
    }

    /**
     * Magic unset
     */
    public function __unset($name) {
        $this->$name = null;
    }

    /**
     * Return the string representation of this model.
     *
     * @return string
     */
    public function __toString() {
        return get_class($this);
    }

    /**
     * Magic call to implement default setters and getters.
     *
     * get<MyProperty>
     * set<MyProperty>
     * maps to $_myProperty if property exists
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, array $arguments) {
        $matches = array();

        // generate default getters
        if (preg_match('/^get(\w+?)?$/', $method, $matches)) {
            // matches[0] = method
            // matches[1] = property
            // map name to property; prepend _
            if (function_exists('lcfirst'))
                $property = lcfirst($matches[1]);
            else
                $property = strtolower(substr($matches[1], 0, 1)) . substr($matches[1], 1);

            $_property = "_" . $property;

            if (!property_exists($this, $_property))
                return null;
            // no property, throw exception
            // throw new Exception('Object does not have property [' . $property . ']');

            return $this->$_property;
        }

        if (preg_match('/^set(\w+?)?$/', $method, $matches)) {

            // matches[0] = method
            // matches[1] = property
            // map name to property; prepend _
            if ($matches[1] == 'ID') {
                $property = $matches[1];
            } elseif (function_exists('lcfirst')) {
                $property = lcfirst($matches[1]);
            } else {
                $property = strtolower(substr($matches[1], 0, 1)) . substr($matches[1], 1);
            }

            $value = $arguments[0];

            $_property = "_" . $property;

            if (property_exists($this, $_property)) {

                // no property, throw exception
                // throw new Exception('Object does not have property [' . $property . ']');
                // property exists, assign the value
                $this->$_property = $value;
            }
            // return $this to provide fluid interface
            return $this;
        }
    }

    // -----------------------------------------------------------------------

    /**
     * Utility methods
     *
     */

    /**
     * Return this model as an array.
     * Any properties of type object will be converted to string.
     *
     * @return array
     */
    public function toArray() {
        $args = func_get_args();

        $data = array();
        if (empty($args)) {

            // iterate over each property in this object
            foreach ($this->_getProperties() as $property => $value) {

                // if the property is ignored, just skip it
                if (in_array($property, $this->_getIgnoredProperties()))
                    continue;

                // if the property value is traversable
                if ($value instanceof Traversable) {

                    // if value is an array or an object traversable using foreach
                    $tmp = array();
                    foreach ($value as $k => $v) {
                        if (is_object($v) && method_exists($v, 'toArray'))
                            $tmp[$k] = $v->toArray();
                        else
                            $tmp[$k] = $v . '';
                    }

                    // if value is an iterator (or subclass), rewind it
                    if ($value instanceof Iterator)
                        $value->rewind();

                    $data[$property] = $tmp;
                } elseif (is_object($value)) {

                    if (method_exists($value, 'toArray'))
                        $data[$property] = $value->toArray();
                    elseif (method_exists($value, 'toString'))
                        $data[$property] = $value->toString();
                    else
                        $data[$property] = $value;
                } elseif (is_array($value)) {
                    $data[$property] = current($value);
                } else {
                    $data[$property] = $value . '';
                }
            }
        } else {

            foreach ($args as $arg)
                if (isset($this->$arg))
                    $data[$arg] = $this->$arg;
        }

        return $data;
    }

    /**
     * Return this model as an array.
     * Any properties of type object will be converted to string.
     *
     * @return array
     */
    public function prepareDB() {
        $args = func_get_args();

        $data = array();
        if (empty($args)) {

            // iterate over each property in this object
            foreach ($this->_getProperties() as $property => $value) {

                // if the property is ignored, just skip it
                if (in_array($property, $this->_getIgnoredProperties()))
                    continue;

                // if the property value is traversable
                if ($value instanceof Traversable) {

                    // if value is an array or an object traversable using foreach
                    $tmp = array();
                    foreach ($value as $k => $v) {
                        if (is_object($v) && method_exists($v, 'toArray'))
                            $tmp[$k] = $v->prepareDB();
                        else
                            $tmp[$k] = $v . '';
                    }

                    // if value is an iterator (or subclass), rewind it
                    if ($value instanceof Iterator)
                        $value->rewind();

                    $data[$property] = $tmp;
                } // if the property is a object
                else if (is_object($value)) {

                    if (method_exists($value, 'toArray'))
                        $data[$property] = $value->prepareDB();
                    elseif (method_exists($value, 'toString'))
                        $data[$property] = $value->toString();
                    else
                        $data[$property] = $value;
                } // otherwise
                else {
                    if (isset($value))
                        $data[$property] = $value . '';
                }
            }
        } else {

            foreach ($args as $arg)
                if (isset($this->$arg))
                    $data[$arg] = $this->$arg;
        }

        return $data;
    }

    /**
     * Return this model as json.
     * Any properties of type object will be converted to string.
     *
     * @return string
     */
    public function toJson() {
        return wp_json_encode($this->toArray());
    }

    /**
     * Return this model as xml.
     * Any properties of type object will be converted to string.
     *
     * @return string
     */
    public function toXml() {
        $schema = $this->_xmlSchema();

        // the dom document
        $dom = new DOMDocument;
        $dom->formatOutput = true;

        // the node
        $nodeKey = key($schema);
        $node = $dom->createElement($nodeKey);

        // attributes
        $attributes = $schema[$nodeKey]['attributes'];
        if (isset($attributes) && is_array($attributes))
            foreach ($attributes as $key => $value) {
                $attribute = $dom->createAttribute($key);
                $attribute->value = htmlentities($value);
                $node->appendChild($attribute);
            }

        // elements
        $elements = $schema[$nodeKey]['elements'];
        if (isset($elements) && is_array($elements))
            foreach ($elements as $key => $element) {
                if (is_array($element) || ($element instanceof Traversable)) {
                    if (is_int($key)) {
                        foreach ($element as $e) {
                            $tmpDom = new DOMDocument;
                            $tmpDom->loadXML($e->toXml());
                            $iNode = $tmpDom->getElementsByTagName('*')->item(0);
                            $node->appendChild($dom->importNode($iNode, true));
                        }
                    } elseif (is_string($key)) {
                        $tmp = $dom->createElement($key);
                        foreach ($element as $e) {
                            $tmpDom = new DOMDocument;
                            $tmpDom->loadXML($e->toXml());
                            $iNode = $tmpDom->getElementsByTagName('*')->item(0);
                            $tmp->appendChild($dom->importNode($iNode, true));
                        }
                        $node->appendChild($tmp);
                    }
                } else {
                    $tmpDom = new DOMDocument;
                    $tmpDom->loadXML($element->toXml());
                    $iNode = $tmpDom->getElementsByTagName('*')->item(0);
                    $node->appendChild($dom->importNode($iNode, true));
                }
            }

        // add node to the dom
        $dom->appendChild($node);

        // only get node xml
        return $dom->saveXML($node);
    }

    /**
     * The xml schema is an array that defines how the xml is build from the current object.
     * This method may be overriden in child classes which may add/modify attributes/elements.
     *
     * @return array
     */
    protected function _xmlSchema() {
        $className = get_class($this);
        $classParts = explode('_', $className);
        $node = array_pop($classParts);

        // build an xml schema automatically based on data members' name and value
        $attributes = array();
        $elements = array();

        // traverse all dtaa members, getting the key (name of the variable) and value
        foreach ($this->_getProperties() as $property => $value) {
            if (is_scalar($value) || !isset($value))
                // if the value is scalar or null
                // treat is as an attribute
                $attributes[ucfirst($property)] = $value;
            else
                if (is_array($value))
                    $elements[ucfirst($property)] = $value;
        }

        return array(
            // node name => node content
            $node => array(
                'attributes' => $attributes,
                'elements' => $elements,
            )
        );
    }

    // -----------------------------------------------------------------------

    /**
     * Methods required by the ArrayAccess interface.
     * Allows to access object properties using the array notation, with [].
     */
    public function offsetExists($offset) {
        return isset($this->$offset);
    }

    public function offsetGet($offset) {
        return $this->offsetExists($offset) ? $this->$offset : null;
    }

    public function offsetSet($offset, $value) {
        $this->$offset = $value;
    }

    public function offsetUnset($offset) {
        $this->$offset = null;
    }

    // -----------------------------------------------------------------------

    /**
     * Get ignored properties
     *
     * Allows to define a list of property names that will be ignored
     * when converting the object to array, json, xml.
     *
     * There may be multiple reasons to ignore properties:
     * - we don't want to export the value for the property
     * - to resolve circular reference
     *  (eg. an object contains one or more children objects, which will each
     *   contain a reference back to the parent; we may set the 'parent' property
     *   as ignored in the child object to eliminate circular toArray() calls
     *   which result in 'out of memory' error)
     *
     * @return array
     */
    protected function _getIgnoredProperties() {
        return array();
    }

    /**
     * Get an array with the object's properties.
     *
     * The valid object's properties are
     * - protected
     * - start with one underscore
     *
     * Private properties will be ignored.
     *
     * @return array
     * @throws Exception
     */
    protected function _getProperties() {
        $properties = array();

        // get object vars
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value)
            $properties[substr($key, 1)] = $value;

        // if no properies are found
        if (empty($properties))
            throw new Exception('no properties found');

        return $properties;
    }

}
