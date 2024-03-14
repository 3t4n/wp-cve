<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

/**
 * Class WjsslSlideshowOptions
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslSlideshowOptions extends WjsslRuntimeNode
{
    private static $keys_to_ignore = array (
        'TransitionArray' => true
        );

    public function __construct() {
        $this->Class = new WjsslRawNode('$JssorSlideshowRunner$');
    }

    public $Class;
    public $TransitionArray;
    public $Transitions;
    public $TransitionOrder;
    public $ShowLink;

    public function prefix_key_with_dollar() {
        return true;
    }

    public function keys_to_ignore() {
        return WjsslSlideshowOptions::$keys_to_ignore;
    }
}
