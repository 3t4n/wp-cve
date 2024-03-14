<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

require_once WP_JSSOR_SLIDER_PATH . 'includes/models/shared/class-wjssl-document-node.php';

/**
 * Class WjsslDesignTimeLayer
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslDesignTimeLayer extends WjsslDocumentNode
{
    public $width = 100;
    public $height = 50;
    public $type;
    public $ctrls;
    public $image;
    public $content;

    public $bgColor;

    public $bgImage;

    public $bgImageEx;

    public $bgRepeat;

    public $motions;

    public $mctrl;

    public $children;

    public $className;

    #region since 8.0.0, 20180305

    public $id;

    #endregion

    public $ptrEvts;

    public $ofX;

    public $ofY;

    #region since 8.0.0, 20180305

    public $pDepth;
    public $poX;
    public $poY;

    #endregion

    public $borderWidth;

    public $borderStyle;

    public $borderColor;

    public $borderRadius;

    public $to;

    public $toX;

    public $toY;

    public $backVisibility;

    public $font;

    public $fontSize;

    public $fontWeight;

    public $color;

    //change since 6.0.0, 20170908
    public $italic;

    public $lineHeight;

    public $textAlign;

    public $padding;

    public $link;

    public $tgt;

    public $blend;

    public $isolation;

    public $lineHeightEx;

    public $letterSpacing;

    public $paddingX; //padding left

    public $paddingT; //padding right

    public $paddingY; //padding top

    public $paddingM; //padding bottom

    public $border;

    public $fontEx;

    public $linkEx;

    //added since 8.1.0, 20180516
    public $conditions;

    //added since 8.1.0, 20180629
    public $textShadow;

    //added since 8.3.0, 20180712
    public $boxShadow;

    //added since 8.9.0, 20180831
    public $acclk;

    /**
     * @param array $object_vars
     */
    protected function do_deserialize_special_vars(&$object_vars) {
        $this->deserialize_array($object_vars, 'motions', 'WjsslDesignTimeMotion', true);
        $this->deserialize_array($object_vars, 'children', 'WjsslDesignTimeLayer', true);

        $this->deserialize_object($object_vars, 'mctrl', 'WjsslDesignTimeMotionControl', true);
        $this->deserialize_object($object_vars, 'border', 'WjsslBorderInfo', true);
        $this->deserialize_object($object_vars, 'conditions', 'WjsslConditionInfo', true);
        $this->deserialize_object($object_vars, 'textShadow', 'WjsslShadowInfo', true);
        $this->deserialize_object($object_vars, 'boxShadow', 'WjsslShadowInfo', true);
        $this->deserialize_object($object_vars, 'linkEx', 'WjsslLinkInfo', true);
        $this->deserialize_object($object_vars, 'fontEx', 'WjsslFontInfo', true);
        $this->deserialize_object($object_vars, 'bgImageEx', 'WjsslBackgroundImageInfo', true);

    }
}
