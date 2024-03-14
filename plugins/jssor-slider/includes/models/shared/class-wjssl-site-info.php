<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

require_once WP_JSSOR_SLIDER_PATH . 'includes/models/shared/class-wjssl-document-node.php';

/**
 * Class WjsslDesignTimeSlide
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslSiteInfo extends WjsslDocumentNode
{
    //[JsonProperty("protocol")]
    public $protocol;

    //[JsonProperty("hostname")]
    public $hostname;

    //[JsonProperty("port")]
    public $port;

    //[JsonProperty("pathname")]
    public $pathname;

    //[JsonProperty("search")]
    public $search;
}
