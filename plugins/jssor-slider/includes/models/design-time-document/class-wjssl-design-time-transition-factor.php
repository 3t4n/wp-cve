<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

require_once WP_JSSOR_SLIDER_PATH . 'includes/models/shared/class-wjssl-document-node.php';

/**
 * Class WjsslDesignTimeTransitionFactor
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslDesignTimeTransitionFactor extends WjsslDocumentNode
{
    public $v;

    public $dif;

    // <summary>
    // easing
    // </summary>
    public $e;
}
