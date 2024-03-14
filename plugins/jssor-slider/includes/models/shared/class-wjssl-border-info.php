<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

require_once WP_JSSOR_SLIDER_PATH . 'includes/models/shared/class-wjssl-document-node.php';

/**
 * Class WjsslDesignTimeSlide
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslBorderInfo extends WjsslDocumentNode
{
    public $width;

    public $style;

    public $color;

    public $radius;
}
