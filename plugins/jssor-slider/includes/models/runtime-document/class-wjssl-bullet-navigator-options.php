<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

/**
 * Class WjsslBulletNavigatorOptions
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslBulletNavigatorOptions extends WjsslRuntimeNode
{
    public function __construct() {
        $this->Class = new WjsslRawNode('$JssorBulletNavigator$');
    }

    public $Class;

    public $Rows;

    public $SpacingX;

    public $SpacingY;
    public $Orientation;
    public $AutoCenter;
    public $Steps;
    public $ActionMode;
    public $ChanceToShow;

    public function prefix_key_with_dollar() {
        return true;
    }
}
