<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

require_once WP_JSSOR_SLIDER_PATH . 'includes/models/shared/class-wjssl-document-node.php';

/**
 * Class WjsslDesignTimeDocument
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslDesignTimeLayout extends WjsslDocumentNode
{
    public $slideWidth;
    public $slideHeight;
    public $slideCols;
    public $slideSpacing;
    public $slideAlign;
    public $slideOrientation;
    public $fillMode;
    public $slidesX;
    public $slidesY;
    public $slidesWidth;
    public $slidesHeight;

    public $slidesBorderWidth;
    public $slidesBorderStyle;
    public $slidesBorderColor;

    public $ocWidth;
    public $ocHeight;

    public $ocBgColor;

    public $ocBgImage;

    public $dirRTL;

    public $rspScaleTo;

    public $rspBleeding;

    //public $rspAdjust;

    //public $rspMin;

    public $rspMax;

    public $rspMaxW;

    public $rspMaxH;

    public $ldTheme;

    /**
     * @param array $object_vars
     */
    protected function do_deserialize_special_vars(&$object_vars) {
        $this->deserialize_object($object_vars, 'ldTheme', 'WjsslTheme');
    }
}
