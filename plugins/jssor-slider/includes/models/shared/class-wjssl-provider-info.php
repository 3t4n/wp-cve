<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

require_once WP_JSSOR_SLIDER_PATH . 'includes/models/shared/class-wjssl-document-node.php';

/**
 * Class WjsslThumbnailValues
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslProviderInfo extends WjsslDocumentNode
{
    // <summary>
    // external provider name
    // e.g. "wp_jssor_slider"
    // </summary>
    //[JsonProperty("jssorext")]
    public $jssorext;

    // <summary>
    // instance id of wordpress website
    // </summary>
    //[JsonProperty("instid")]
    public $instid;

    // <summary>
    // instance version of wordpress website
    // e.g. "4.6"
    // </summary>
    //[JsonProperty("instver")]
    public $instver;


    // <summary>
    // jssor api version
    // e.g. "1.0.1"
    // </summary>
    //[JsonProperty("extver")]
    public $extver;

    // <summary>
    // external provider allow lzw compresson or not
    // </summary>
    //[JsonProperty("lzw")]
    public $lzw;
}
