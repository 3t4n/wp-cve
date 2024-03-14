<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

require_once WP_JSSOR_SLIDER_PATH . 'includes/models/shared/class-wjssl-document-node.php';

/**
 * Class WjsslDesignTimeDocument
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslDesignTimeTransitionFactorClipVer extends WjsslDocumentNode
{
    // <summary>
    // Top
    // </summary>
    public $y;

    // <summary>
    // Bottom
    // </summary>
    public $m;

    /**
     * @param array $object_vars
     */
    protected function do_deserialize_special_vars(&$object_vars) {
        $this->deserialize_object($object_vars, 'y', 'WjsslDesignTimeTransitionFactor', true);
        $this->deserialize_object($object_vars, 'm', 'WjsslDesignTimeTransitionFactor', true);
    }
}
