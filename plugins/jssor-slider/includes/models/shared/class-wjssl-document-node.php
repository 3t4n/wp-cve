<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

/**
 * Class WjsslAbstractDocumentElement
 * @link   https://www.jssor.com
 * @author Neil.zhou
 * @author jssor
 */
class WjsslDocumentNode
{
    /**
     * @param array $object_vars
     */
    public function __construct($object_vars = null)
    {
        if(!empty($object_vars)) {
            $this->deserialize($object_vars);
        }
    }

    /**
     * @param array $object_vars
     */
    protected function deserialize(&$object_vars) {
        
        // deserialize array, object...
        $this->do_deserialize_special_vars($object_vars);

        foreach ($object_vars as $key => $value) {
            $this->{$key} = $value;
        }
    }

    protected function do_deserialize_special_vars(&$object_vars) {
        return true;
    }

    /**
     * @param array $json_model
     * @param string $class
     */
    protected function deserialize_array(&$object_vars, $key, $class, $allow_null = false) {
        if(isset($object_vars[$key])) {
            $new_array = array();

            foreach($object_vars[$key] as $value) {
                array_push($new_array, new $class($value));
            }

            unset($object_vars[$key]);

            $this->{$key} = $new_array;
        }
        else if(!$allow_null) {
            $this->{$key} = array();
        }
    }

    /**
     * @param array $object_vars
     * @param string $key
     * @param string $class
     */
    protected function deserialize_object(&$object_vars, $key, $class, $allow_null = false) {
        if(isset($object_vars[$key])) {
            $this->{$key} = new $class($object_vars[$key]);
            unset($object_vars[$key]);
        }
        else if(!$allow_null) {
            $this->{$key} = new $class();
        }
    }
}
