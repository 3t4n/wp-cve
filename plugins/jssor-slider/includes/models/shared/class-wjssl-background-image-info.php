<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

require_once WP_JSSOR_SLIDER_PATH . 'includes/models/shared/class-wjssl-document-node.php';

class WjsslBackgroundImageInfo extends WjsslDocumentNode
{
    public $image;

    public $repeat;

    //added since 8.9.0, 20180831
    public $gradient;

    /**
     * @param array $object_vars
     */
    protected function do_deserialize_special_vars(&$object_vars) {
        $this->deserialize_object($object_vars, 'gradient', 'WjsslGradientInfo', true);
    }
}
