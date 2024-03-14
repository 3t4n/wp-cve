<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

require_once WP_JSSOR_SLIDER_PATH . 'includes/models/shared/class-wjssl-document-node.php';

/**
 * Class WjsslThumbnailValues
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslThumbnailValues extends WjsslDocumentNode
{
    public $images;
    public $contents;
}
