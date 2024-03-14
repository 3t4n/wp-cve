<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

/**
 * Class WjsslResponsiveOptions
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslResponsiveOptions extends WjsslRuntimeNode
{
    public $ScaleTo;

    public $Bleeding;

    public $MaxW;

    public $MaxH;

    public function prefix_key_with_dollar() {
        return true;
    }
}
