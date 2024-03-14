<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

require_once WP_JSSOR_SLIDER_PATH . 'includes/models/shared/class-wjssl-document-node.php';

/**
 * Class WjsslDesignTimeDocument
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslDesignTimeLayouts extends WjsslDocumentNode
{
    public $layout;

    public $bullets;

    public $arrows;

    public $thumbnails;

    public $loading;

    /**
     * @param array $object_vars
     */
    protected function do_deserialize_special_vars(&$object_vars) {
        $this->deserialize_object($object_vars, 'layout', 'WjsslDesignTimeLayout');
        $this->deserialize_object($object_vars, 'bullets', 'WjsslDesignTimeBullets', true);
        $this->deserialize_object($object_vars, 'arrows', 'WjsslDesignTimeArrows', true);
        $this->deserialize_object($object_vars, 'thumbnails', 'WjsslDesignTimeThumbnails', true);
        $this->deserialize_object($object_vars, 'loading', 'WjsslDesignTimeLoading', true);
    }
}
