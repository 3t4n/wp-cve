<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

require_once WP_JSSOR_SLIDER_PATH . 'includes/models/shared/class-wjssl-document-node.php';

/**
 * Class WjsslDesignTimeSlide
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslConditionInfo extends WjsslDocumentNode
{
    public $play;

    #region rollback

    public $rollback;

    public $idle;

    public $group;

    #endregion

    #region misc

    public $rbSpeed;

    public $initial;

    public $pause;

    #endregion
}
