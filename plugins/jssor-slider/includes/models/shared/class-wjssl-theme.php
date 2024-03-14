<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

require_once WP_JSSOR_SLIDER_PATH . 'includes/models/shared/class-wjssl-document-node.php';

/**
 * Class WjsslDesignTimeDocument
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslTheme extends WjsslDocumentNode
{
    public $url;

    public $skin;

    /**
     * @param array $object_vars
     */
    protected function deserialize(&$object_vars) {
        parent::deserialize_object($object_vars, 'skin', 'WjsslSkin');
        parent::deserialize($object_vars);
    }
}
