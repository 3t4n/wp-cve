<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

/**
 * Class WjsslThumbnailNavigatorOptions
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslThumbnailNavigatorOptions extends WjsslRuntimeNode
{
    public function __construct() {
        $this->Class = new WjsslRawNode('$JssorThumbnailNavigator$');
    }

    public $Class;

    public $Rows;

    public $Cols;

    public $SpacingX;

    public $SpacingY;
    public $Orientation;
    public $ActionMode;
    public $ChanceToShow;
    public $Align;
    public $Loop;
    public $NoDrag;

    public function prefix_key_with_dollar() {
        return true;
    }
}
