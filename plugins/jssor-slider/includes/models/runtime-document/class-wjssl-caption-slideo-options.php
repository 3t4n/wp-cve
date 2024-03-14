
<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit();

/**
 * Class WjsslCaptionSlideoOptions
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslCaptionSlideoOptions extends WjsslRuntimeNode
{
    private static $keys_to_ignore = array (
        'TransitionArray' => true
        );

    public function __construct() {
        $this->Class = new WjsslRawNode('$JssorCaptionSlideo$');
    }

    public $Class;

    public $TransitionArray;

    public $Transitions;

    public $Breaks;

    public $Controls;

    public function prefix_key_with_dollar() {
        return true;
    }

    public function keys_to_ignore() {
        return WjsslCaptionSlideoOptions::$keys_to_ignore;
    }
}
