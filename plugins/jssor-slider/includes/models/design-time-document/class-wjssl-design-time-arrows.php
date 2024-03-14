<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

require_once WP_JSSOR_SLIDER_PATH . 'includes/models/shared/class-wjssl-document-node.php';

/**
 * Class WjsslDesignTimeDocument
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslDesignTimeArrows extends WjsslDocumentNode
{
    public $theme;

    public $itemWidth;

    public $itemHeight;

    public $posAutoCenter;

    public $poslTop;

    public $poslLeft;

    public $poslBottom;

    public $poslRight;

    public $posrTop;

    public $posrLeft;

    public $posrBottom;

    public $posrRight;

    public $bhvSteps;

    public $bhvChanceToShow;

    public $bhvNoScale;

    public $bhvScaleL;

    public $bhvScalePos;

    /**
     * @param array $object_vars
     */
    protected function do_deserialize_special_vars(&$object_vars) {
        $this->deserialize_object($object_vars, 'theme', 'WjsslTheme', true);
    }
}
