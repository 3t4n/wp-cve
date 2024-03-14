<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

require_once WP_JSSOR_SLIDER_PATH . 'includes/models/shared/class-wjssl-document-node.php';

//added since 8.9.0, 20180831
/**
 * Class WjsslDesignTimeSlide
 * @link   https://www.jssor.com
 * @author Jssor
 */
class WjsslGradientInfo extends WjsslDocumentNode
{
    public $type;

    public $angle;

    public $from;

    public $to;

    public $raw;

    public $stops;

    /**
     * @param array $object_vars
     */
    protected function do_deserialize_special_vars(&$object_vars) {
        $this->deserialize_object($object_vars, 'from', 'WjsslGradientFromInfo', true);
        $this->deserialize_array($object_vars, 'stops', 'WjsslGradientStopInfo', true);
    }
}
