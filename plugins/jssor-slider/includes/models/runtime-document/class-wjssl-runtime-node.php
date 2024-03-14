<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

interface iWjsslRuntimeNode {
    public function toJson();
}

/**
 * @link   https://www.jssor.com
 * @author jssor
 */
class WjsslRuntimeNode implements iWjsslRuntimeNode
{
    public function __construct() {
    }

    #region json serialization

    /**
     * @return string
     */
    public function toJson() {
        $keys_to_ignore = $this->keys_to_ignore();

        $json_text = '{';

        $data = (array) $this;

        $is_first_node = true;
        foreach($data as $key => $value) {
            if(!is_null($value) && (is_null($keys_to_ignore) || !isset($keys_to_ignore[$key]))) {
                if($is_first_node) {
                    $is_first_node = false;
                }
                else {
                    $json_text .= ',';
                }

                if($this->prefix_key_with_dollar()) {
                    $json_text .= '$';
                }

                $json_text .= $key;
                $json_text .= ':';

                if($value instanceof iWjsslRuntimeNode) {
                    $json_text .= $value->toJson();
                }
                else {
                    $json_text .= json_encode($value);
                }
            }
        }

        $json_text .= '}';

        return $json_text;
    }

    public function prefix_key_with_dollar() {
        return false;
    }

    public function keys_to_ignore() {
        return null;
    }

    #endregion
}

/**
 * @author jssor
 */
class WjsslRawNode extends WjsslRuntimeNode {

    private $value;

    public function __construct($value) {
        $this->value = $value;
    }

    public function toJson() {
        if(empty($this->value)) {
            return 'null';
        }

        return $this->value;
    }
}
