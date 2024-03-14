<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

require_once WP_JSSOR_SLIDER_PATH . 'includes/models/shared/class-wjssl-document-node.php';

/**
 * Class WjsslDesignTimeDocument
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslDesignTimeTransitionFactorClipHor extends WjsslDocumentNode
{
    // <summary>
    // Left
    // </summary>
    public $x;

    // <summary>
    // Right
    // </summary>
    public $t;

    /**
     * @param array $object_vars
     */
    protected function do_deserialize_special_vars(&$object_vars) {
        $this->deserialize_object($object_vars, 'x', 'WjsslDesignTimeTransitionFactor', true);
        $this->deserialize_object($object_vars, 't', 'WjsslDesignTimeTransitionFactor', true);
    }
}
