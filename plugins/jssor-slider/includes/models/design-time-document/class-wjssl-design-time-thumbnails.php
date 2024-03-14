<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

require_once WP_JSSOR_SLIDER_PATH . 'includes/models/shared/class-wjssl-document-node.php';

/**
 * Class WjsslDesignTimeDocument
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslDesignTimeThumbnails extends WjsslDocumentNode
{
    public $theme;

    public $cntrWidth;

    public $cntrHeight;

    public $cntrAutoCenter;

    public $cntrTop;

    public $cntrLeft;

    public $cntrBottom;

    public $cntrRight;

    public $cntrBgColor;

    public $itemWidth;

    public $itemHeight;

    public $itemSpacingX;

    public $itemSpacingY;

    public $itemRows;

    public $itemCols;

    public $itemAlign;

    public $itemOrientation;

    public $bhvActionMode;

    public $bhvChanceToShow;

    public $bhvNoScale;

    public $bhvScaleL;

    public $bhvScalePos;

    public $bhvLoop;

    public $bhvNoDrag;

    /**
     * @param array $object_vars
     */
    protected function do_deserialize_special_vars(&$object_vars) {
        $this->deserialize_object($object_vars, 'theme', 'WjsslTheme', true);
    }
}
