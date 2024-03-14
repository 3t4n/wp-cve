<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

require_once WP_JSSOR_SLIDER_PATH . 'includes/models/shared/class-wjssl-document-node.php';

/**
 * Class WjsslDesignTimeSlide
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslShadowInfo extends WjsslDocumentNode
{
    public $x;

    public $y;

    public $color;

    public $blur;

    //added since 8.9.0, 20180831
    public $type;
}
