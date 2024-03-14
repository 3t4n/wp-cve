<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

require_once WP_JSSOR_SLIDER_PATH . 'includes/models/shared/class-wjssl-document-node.php';

/**
 * Class WjsslDesignTimeSlide
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslDesignTimeSlide extends WjsslDocumentNode
{
    public $bgColor;
    //added since 8.9.0, 20180831
    public $bgGradient;

    public $image;
    public $fillMode;
    //added since 8.9.0, 20180831
    public $opacity;
    //added since 8.9.0, 20180831
    public $blur;
    //added since 8.9.0, 20180831
    public$grayscale;

    public $link;
    public $tgt;
    public $thumb;
    public $pDepth;
    public $poX;
    public $poY;
    public $layers;
    public $breaks;

    public $idle;

    /**
     * @param array $object_vars
     */
    protected function do_deserialize_special_vars(&$object_vars) {
        $this->deserialize_object($object_vars, 'thumb', 'WjsslThumbnailValues', true);
        $this->deserialize_array($object_vars, 'layers', 'WjsslDesignTimeLayer', true);
        $this->deserialize_array($object_vars, 'breaks', 'WjsslDesignTimeBreak', true);
        $this->deserialize_object($object_vars, 'bgGradient', 'WjsslGradientInfo', true);
    }
}
