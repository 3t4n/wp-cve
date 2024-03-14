<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

/**
 * Class WjsslArrowNavigatorOptions
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslArrowNavigatorOptions extends WjsslRuntimeNode
{
    public function __construct() {
        $this->Class = new WjsslRawNode('$JssorArrowNavigator$');
    }

    public $Class;

    public $AutoCenter;

    public $Steps;

    public $ChanceToShow;

    public function prefix_key_with_dollar() {
        return true;
    }
}
