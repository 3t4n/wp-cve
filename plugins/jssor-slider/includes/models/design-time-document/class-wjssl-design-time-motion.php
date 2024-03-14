<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

require_once WP_JSSOR_SLIDER_PATH . 'includes/models/shared/class-wjssl-document-node.php';

/**
 * Class WjsslDesignTimeMotion
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslDesignTimeMotion extends WjsslDocumentNode
{
    public $duration;
    public $trans;

    /**
     * @param array $object_vars
     */
    protected function do_deserialize_special_vars(&$object_vars) {
        $this->deserialize_object($object_vars, 'trans', 'WjsslDesignTimeSlideoTransition', true);
    }
}
