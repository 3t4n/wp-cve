<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

abstract class WjsslCssPrimitiveValue extends WjsslCssValue {
    public function __construct($iLineNo = 0) {
        parent::__construct($iLineNo);
    }

}