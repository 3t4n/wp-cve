<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

require_once WP_JSSOR_SLIDER_PATH . 'includes/models/shared/class-wjssl-document-node.php';

/**
 * Class WjsslDesignTimeDocument
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslDesignTimeDocument extends WjsslDocumentNode
{
    public $slides = null;
    public $layouts = null;
    public $options = null;
    public $site = null;
    public $extInfo = null;
    public $ver = '';


    /**
     * @param array $object_vars
     */
    protected function do_deserialize_special_vars(&$object_vars) {
        $this->deserialize_array($object_vars, 'slides', 'WjsslDesignTimeSlide');
        $this->deserialize_object($object_vars, 'layouts', 'WjsslDesignTimeLayouts');
        $this->deserialize_object($object_vars, 'options', 'WjsslDesignTimeOptions');
        $this->deserialize_object($object_vars, 'site', 'WjsslSiteInfo');
        $this->deserialize_object($object_vars, 'extInfo', 'WjsslProviderInfo');
    }
}
