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
class WjsslGradientFromInfo extends WjsslDocumentNode
{
    public $h;

    public $v;
}
