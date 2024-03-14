<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

require_once WP_JSSOR_SLIDER_PATH . 'includes/models/shared/class-wjssl-document-node.php';

/**
 * Class WjsslDesignTimeDocument
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslDesignTimeSkinTemplateItemInfo extends WjsslDocumentNode
{
    // <summary>
    // value
    // </summary>
    public $v;
    // <summary>
    // image
    // </summary>
    public $i;
    // <summary>
    // content
    // </summary>
    public $c;
    // <summary>
    // after adjust value
    // </summary>
    public $a;
    // <summary>
    // before adjust value
    // </summary>
    public $b;
    // <summary>
    // adjust factor
    // </summary>
    public $f;
    // <summary>
    // dest value type, 1: int
    // </summary>
    public $t;
}
